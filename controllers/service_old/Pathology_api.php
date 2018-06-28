<?php

class Pathology_api extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->library('email');
        $this->load->model('patholab_api_model');
        $this->load->helper('security');
        $this->load->helper('string');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST');
        header("Access-Control-Allow-Headers: X-Requested-With");
        $this->app_tarce();
    }

    function app_tarce() {
        $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $ipaddress = $_SERVER['REMOTE_ADDR'];
        $page = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}";
        if (!empty($_SERVER['QUERY_STRING'])) {
            $page = $_SERVER['QUERY_STRING'];
        } else {
            $page = "";
        }
        if (!empty($_POST)) {
            $user_post_data = $_POST;
        } else {
            $user_post_data = array();
        }
        $user_post_data = json_encode($user_post_data);
        $useragent = $_SERVER['HTTP_USER_AGENT'];
        $remotehost = @getHostByAddr($ipaddress);
        $user_info = json_encode(array("Ip" => $ipaddress, "Page" => $page, "UserAgent" => $useragent, "RemoteHost" => $remotehost));
        $user_track_data = array("url" => $actual_link, "user_details" => $user_info, "data" => $user_post_data, "createddate" => date("Y-m-d H:i:s"), "type" => "service");
        //print_R($user_track_data);
        $app_info = $this->patholab_api_model->master_fun_insert("user_track", $user_track_data);
        //return true;
    }

    public function generateToken($userId) {
        $static_str = 'ARMD';
        $currenttimeseconds = date("mdY_His");
        $token_id = $static_str . $userId . $currenttimeseconds;
        $data = array(
            'active_token' => sha1($token_id)
        );
        $this->patholab_api_model->master_fun_update('admin_master', array("id" => $userId), $data);
        return sha1($token_id);
    }

    function check_token($user_id = null, $token = null, $time_stamp = null) {
        return 1;
        $date_a = new DateTime($time_stamp);
        $date_b = new DateTime(date("Y-m-d H:i:s"));
        $interval = date_diff($date_a, $date_b);
        $interval = explode(":", $interval->format('%h:%i:%s'));
        //print_r($interval); die();
        if ($interval[0] == 0 && $interval[1] == 0 && $interval[2] != 0 && !empty($time_stamp)) {
            $row = $this->patholab_api_model->master_num_rows("admin_master", array("id" => $user_id, "active_token" => $token, "status" => "1", "type" => "5"));
            if ($row == 1) {
                return 1;
            } else {
                echo $this->json_data("0", "Invalid token.", "");
                die();
            }
        } else {
            echo $this->json_data("0", "Request time out.Try again.", "");
            die();
        }
    }

    function login() {
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        //$device_id = $this->input->post('device_id');
        if ($email != NULL && $password != NULL) {
            $row = $this->patholab_api_model->master_num_rows("admin_master", array("email" => $email, "password" => $password, "status" => 1, "type" => 5));
            if ($row == 1) {
                $data = $this->patholab_api_model->master_fun_get_tbl_val("admin_master", array("email" => $email, "password" => $password, "status" => 1, "type" => 5), array("id", "asc"));
                $token = $this->generateToken($data[0]["id"]);
                $data[0]['active_token'] = $token;
                echo $this->json_data("1", "", $data);
            } else {
                echo $this->json_data("0", "Email Or Password Not match", "");
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function dashboard() {
        /* Check token start */
        $uid = $this->input->post("uid");
        $time_stamp = $this->input->post("time_stamp");
        $headers = apache_request_headers();
        $this->check_token($uid, $headers['Tkn'], $time_stamp);
        /* Check token end */
        /* Get branch start */
        $branch_list = $this->patholab_api_model->master_fun_get_tbl_val("user_branch", array("user_fk" => $uid, "status" => "1"), array("id", "desc"));
        $user_branch = array();
        if (!empty($branch_list)) {
            foreach ($branch_list as $key) {
                $user_branch[] = $key["branch_fk"];
            }
        }
        /* Get branch end */
        $job_status = $this->patholab_api_model->get_job_status($user_branch);
        $final_data = array();
        $status1 = 0;
        foreach ($job_status as $key) {
            $status1 = $status1 + $key["count"];
        }
        $final_data[0]["total_jobs"] = "" . $status1;
        $status1 = 0;
        foreach ($job_status as $key) {
            if ($key["status"] == 1) {
                $final_data[0]["waiing_for_approve"] = $key["count"];
                $status1 = 1;
            }
        } if ($status1 == 0) {
            $final_data[0]["waiing_for_approve"] = "0";
        }
        $status1 = 0;
        foreach ($job_status as $key) {
            if ($key["status"] == 6) {
                $final_data[0]["approve"] = $key["count"];
                $status1 = 1;
            }
        } if ($status1 == 0) {
            $final_data[0]["approve"] = "0";
        }
        $status1 = 0;
        foreach ($job_status as $key) {
            if ($key["status"] == 7) {
                $final_data[0]["sample_collected"] = $key["count"];
                $status1 = 1;
            }
        } if ($status1 == 0) {
            $final_data[0]["sample_collected"] = "0";
        }
        $status1 = 0;
        foreach ($job_status as $key) {
            if ($key["status"] == 8) {
                $final_data[0]["processing"] = $key["count"];
                $status1 = 1;
            }
        } if ($status1 == 0) {
            $final_data[0]["processing"] = "0";
        }
        $status1 = 0;
        foreach ($job_status as $key) {
            if ($key["status"] == 2) {
                $final_data[0]["completed"] = $key["count"];
                $status1 = 1;
            }
        } if ($status1 == 0) {
            $final_data[0]["completed"] = "0";
        }
        /* Nishit new customer and old count start */
        $job_cust_details = $this->patholab_api_model->get_val("SELECT j.*,(SELECT COUNT(id) FROM job_master WHERE STATUS !='0' AND cust_fk= j.cust_fk AND id <j.id) AS user_cnt FROM  job_master j  WHERE j.`status`!='0' AND j.DATE>='" . date("Y-m-d") . "' ORDER BY j.`status` ASC");
        $new_user = 0;
        $current_user = 0;
        foreach ($job_cust_details as $key) {
            if ($key["user_cnt"] > 1) {
                $current_user++;
            } else {
                $new_user++;
            }
        }
        /* END */
        $data = array(array("graph" =>
                array(
                    array("name" => "Total", "per" => "100", "value" => $final_data[0]["total_jobs"], "color" => "#e85629"),
                    array("name" => "Waiting For Approve", "per" => "" . intval(($final_data[0]["waiing_for_approve"] / $final_data[0]["total_jobs"]) * 100), "value" => $dash['critical'], "color" => "#f42e48"),
                    array("name" => "Approve", "per" => "" . intval(($final_data[0]["approve"] / $final_data[0]["total_jobs"]) * 100), "value" => $dash['semicritical'], "color" => "#FFD600"),
                    array("name" => "Sample Collected", "per" => "" . intval(($final_data[0]["sample_collected"] / $final_data[0]["total_jobs"]) * 100), "value" => $dash['normal'], "color" => "#33691E"),
                    array("name" => "Processing", "per" => "" . intval(($final_data[0]["processing"] / $final_data[0]["total_jobs"]) * 100), "value" => "" . $dash['pending'], "color" => "#2e4de8"),
                    array("name" => "Completed", "per" => "" . intval(($final_data[0]["completed"] / $final_data[0]["total_jobs"]) * 100), "value" => "" . $dash['pending'], "color" => "#2e4de8")
                ), "new_user" => "" . $new_user, "new_per" => "" . intval(($new_user / $final_data[0]["total_jobs"]) * 100), "current_user" => "" . $current_user, "current_per" => "" . intval(($current_user / $final_data[0]["total_jobs"]) * 100))
        );
        echo $this->json_data("1", "", array($final_data[0], $data[0]));
    }

    function json_data($status, $error_msg, $data = NULL) {
        if ($data == NULL) {
            $data = array();
        }
        $final = array("status" => $status, "error_msg" => $error_msg, "data" => $data);
        return str_replace("null", '" "', json_encode($final));
    }

    function logout() {
        /* Check token start */
        $uid = $this->input->post("uid");
        $time_stamp = $this->input->post("time_stamp");
        $headers = apache_request_headers();
        $this->check_token($uid, $headers['Tkn'], $time_stamp);
        /* Check token end */
        if (!empty($uid)) {
            $this->patholab_api_model->master_fun_update('admin_master', array("id" => $uid), array("active_token" => ""));
            echo $this->json_data("1", "", "");
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function patient_list() {
        /* Check token start */
        $uid = $this->input->post("uid");
        $time_stamp = $this->input->post("time_stamp");
        $headers = apache_request_headers();
        $this->check_token($uid, $headers['Tkn'], $time_stamp);
        /* Check token end */
        /* Get branch start */
        $branch_list = $this->patholab_api_model->master_fun_get_tbl_val("user_branch", array("user_fk" => $uid, "status" => "1"), array("id", "desc"));
        $user_branch = array();
        if (!empty($branch_list)) {
            foreach ($branch_list as $key) {
                $user_branch[] = $key["branch_fk"];
            }
        }
        /* Get branch end */
        $from = $this->input->post("from");
        $from = date("Y-m-d");
        $to = $this->input->post("to");
        $to = date('Y-m-d', strtotime(date('Y-m-d') . ' -30 day'));
        $type = $this->input->post("type");
        //echo $from."-".$to; die();
        if (!empty($from) && !empty($to) && !empty($uid)) {
            $data = $this->patholab_api_model->patient_data($from, $to, $type, $user_branch);
            $new_array = array();
            $pendnig_patient = array();
            foreach ($data as $key1) {
                $new_array1 = $this->get_job_details($key1["id"]);
                $result = array();
                $approved = 0;
                $notapproved = 0;
                foreach ($new_array1[0]["book_test"] as $key) {
                    $proove_test = $this->patholab_api_model->master_fun_get_tbl_val("approve_job_test", array("job_fk" => $new_array1[0]["id"], "test_fk" => $key["id"], "status" => "1"), array("id", "desc"));
                    if (!empty($proove_test)) {
                        $key["is_approve"] = '1';
                        $approved++;
                    } else {
                        $key["is_approve"] = '0';
                        $notapproved++;
                    }
                    $result[] = $key;
                }
                $new_array1[0]["book_test"] = $result;
                $new_array1[0]["approved_cnt"] = "" . $approved;
                $new_array1[0]["unapproved_cnt"] = "" . $notapproved;
                if (!empty($new_array1[0]["report_approve_by"])) {
                    $new_array[] = $new_array1[0];
                } else {
                    $pendnig_patient[] = $new_array1[0];
                }
            }
            if (!empty($new_array) || !empty($pendnig_patient)) {
                echo $this->json_data("1", "", array(array("approved_report" => $new_array, "pending_report" => $pendnig_patient)));
            } else {
                echo $this->json_data("0", "No new job available.", "");
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function patient_job_test() {
        $job_id = $this->input->post("job_fk");
        if (!empty($job_id)) {
            $new_array = $this->get_job_details($job_id);
            $result = array();
            $approve = 0;
            $unapprove = 0;
            foreach ($new_array[0]["book_test"] as $key) {
                $proove_test = $this->patholab_api_model->master_fun_get_tbl_val("approve_job_test", array("job_fk" => $job_id, "test_fk" => $key["id"], "status" => "1"), array("id", "desc"));
                if (!empty($proove_test)) {
                    $key["is_approve"] = '1';
                    $approve++;
                } else {
                    $key["is_approve"] = '0';
                    $unapprove++;
                }
                $result[] = $key;
            }
            $approve_status = 0;
            if ($unapprove < 2) {
                $approve_status = 1;
            }
            $new_array[0]["approve_status"] = "" . $approve_status;
            $new_array[0]["book_test"] = $result;
            if (!empty($new_array)) {
                echo $this->json_data("1", "", $new_array);
            } else {
                echo $this->json_data("0", "Data not available.", "");
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function get_job_details($job_id) {
        $job_details = $this->patholab_api_model->get_val("SELECT 
  j.id,
  j.branch_fk,
  GROUP_CONCAT(report_master.`original`) AS report,
  j.date,
  j.collection_charge,
  j.dispatch,
  j.`order_id`,
  j.ack,
  j.doctor,
  j.booking_info,
  j.discount,
  j.test_city,
  j.views,
  j.`payment_type`,
  j.sample_collection,
  CONCAT_WS(' - ', c.full_name, j.`order_id`) AS full_name ,
  c.mobile,
  j.`payable_amount`,
  j.status,
  j.price,
  j.report_status,
  j.report_approve_by,
  c.`dob`,
  c.`gender`,
  c.id AS cid,
  booking_info.`emergency` AS emergency,
  `booking_info`.`family_member_fk`,
  doctor_master.`full_name` AS doctor_name,
  doctor_master.`mobile` AS doctor_mobile,
  wallet_master.`debit` AS cut_from_wallet,
  `report_master`.`original` AS report,
  customer_family_master.`name` AS family_name,
  IF(
    `booking_info`.`family_member_fk` = 0,
    'Self',
    CONCAT(
      relation_master.`name`,
      '-',
      customer_family_master.`name`
    )
  ) AS relation 
FROM
  job_master j 
  LEFT JOIN job_test_list_master jtl 
    ON jtl.job_fk = j.`id` 
  INNER JOIN customer_master c 
    ON c.id = j.`cust_fk` 
  LEFT JOIN test_master t 
    ON t.id = jtl.test_fk 
  LEFT JOIN book_package_master pb 
    ON pb.job_fk = j.id 
  LEFT JOIN package_master p 
    ON p.id = pb.package_fk 
  LEFT JOIN `booking_info` 
    ON j.`booking_info` = `booking_info`.`id` 
  LEFT JOIN `doctor_master` 
    ON j.`doctor` = `doctor_master`.`id` 
  LEFT JOIN `report_master` 
    ON `report_master`.`job_fk` = j.id 
  LEFT JOIN wallet_master 
    ON j.`id` = wallet_master.`job_fk` 
  LEFT JOIN customer_family_master 
    ON customer_family_master.`id` = `booking_info`.`family_member_fk` 
  LEFT JOIN relation_master 
    ON relation_master.`id` = customer_family_master.`relation_fk` 
WHERE 1=1 AND j.`id`='" . $job_id . "' AND j.status != '0' GROUP BY j.id ORDER BY j.id DESC");

        $this->load->library("util");
        $util = new Util;
        $data["age"] = $util->get_age($job_details[0]["dob"]);
        $job_details[0]["age"] = $data["age"][0];
        if (!empty($job_details)) {
            $book_test = $this->patholab_api_model->master_fun_get_tbl_val("job_test_list_master", array("job_fk" => $job_id), array("id", "desc"));
            $book_package = $this->patholab_api_model->master_fun_get_tbl_val("book_package_master", array("job_fk" => $job_id, "status" => "1"), array("id", "desc"));
            $test_array = array();
            foreach ($book_test as $b_key) {
                $sub_test = $this->patholab_api_model->master_fun_get_tbl_val("sub_test_master", array("test_fk" => $b_key['test_fk'], "status" => "1"), array("id", "desc"));
                $test_array[] = $b_key['test_fk'];
                foreach ($sub_test as $s_key) {
                    if (!in_array($s_key["sub_test"], $test_array)) {
                        $test_array[] = $s_key["sub_test"];
                    }
                }
            }

            foreach ($book_package as $b_key) {
                $sub_test = $this->patholab_api_model->master_fun_get_tbl_val("package_test", array("package_fk" => $b_key['id'], "status" => "1"), array("id", "desc"));
                foreach ($sub_test as $s_key) {
                    if (!in_array($s_key["test_fk"], $test_array)) {
                        $test_array[] = $s_key["test_fk"];
                    }
                }
            }
            //print_R($test_array); die();
            $test_name = array();
            foreach ($test_array as $key) {
                $price1 = $this->patholab_api_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $job_details[0]["test_city"] . "' AND `test_master_city_price`.`status`='1' AND `test_master`.`id`='" . $key . "'");
                $test_name[] = $price1[0];
            }
            $job_details[0]["book_test"] = $test_name;
            $job_details[0]["test_count"] = "" . count($test_name);
            $package_name = array();
            foreach ($book_package as $key) {

                $price1 = $this->patholab_api_model->get_val("SELECT 
          `package_master`.*,
          `package_master_city_price`.`a_price` AS `a_price`,
          `package_master_city_price`.`d_price` AS `d_price`
          FROM
          `package_master`
          INNER JOIN `package_master_city_price`
          ON `package_master`.`id` = `package_master_city_price`.`package_fk`
          WHERE `package_master`.`status` = '1'
          AND `package_master_city_price`.`status` = '1' AND `package_master_city_price`.`city_fk` = '" . $job_details[0]["test_city"] . "' AND `package_master`.`id`='" . $key["package_fk"] . "'");
                $package_name[] = $price1[0];
            }
            //$job_details[0]["book_package"] = $package_name;
        }
        return $job_details;
    }

    function test_parameter() {
        $id = $data['cid'] = $this->input->post("job_fk");
        $test_id = $this->input->post("test_fk");
        if (!empty($id) && !empty($test_id)) {
            $data['query'] = $this->patholab_api_model->job_details($id);
            $branch_fk = $data['query'][0]["branch_fk"];
            $data['user_booking_info'] = $this->patholab_api_model->master_fun_get_tbl_val("booking_info", array('status' => 1, 'id' => $data['query'][0]["booking_info"]), array("id", "asc"));
            $data['user_data'] = $this->patholab_api_model->master_fun_get_tbl_val("customer_master", array('status' => 1, 'id' => $data['query'][0]["custid"]), array("id", "asc"));

            if ($data['user_booking_info'][0]["family_member_fk"] != 0) {
                $data['user_family_info'] = $this->patholab_api_model->master_fun_get_tbl_val("customer_family_master", array('status' => 1, 'id' => $data['user_booking_info'][0]["family_member_fk"]), array("id", "asc"));
                $data['user_data'][0]["gender"] = $data['user_family_info'][0]["gender"];
                $data['user_data'][0]["age"] = $data['user_family_info'][0]["age"];
                $data['user_data'][0]["age_type"] = $data['user_family_info'][0]["age_type"];
                $data['user_data'][0]["full_name"] = $data['user_family_info'][0]["name"];
                $data['user_data'][0]["email"] = $data['user_family_info'][0]["email"];
                $data['user_data'][0]["phone"] = $data['user_family_info'][0]["phone"];
                $data['user_data'][0]["dob"] = $data['user_family_info'][0]["dob"];
            }
//        if (empty($data['user_data'][0]["dob"])) {
//            $data['user_data'][0]["dob"] = '1992-09-30';
//        }
            /* Check bitrth date start */
            if ($data['user_data'][0]["dob"] != '') {
                $this->load->library("util");
                $util = new Util;
                $age = $util->get_age($data['user_data'][0]["dob"]);
                if ($age[0] != 0) {
                    $data['user_data'][0]["age"] = $age[0];
                    $data['user_data'][0]["age_type"] = 'Y';
                }
                if ($age[0] == 0 && $age[1] != 0) {
                    $data['user_data'][0]["age"] = $age[1];
                    $data['user_data'][0]["age_type"] = 'M';
                }
                if ($age[0] == 0 && $age[1] == 0 && $age[2] != 0) {
                    $data['user_data'][0]["age"] = $age[2];
                    $data['user_data'][0]["age_type"] = 'D';
                }
            } else {
                $data['user_data'][0]["age"] = '-';
                $data['user_data'][0]["age_type"] = '';
            }
            $tid = array($test_id);
            $cnt = 0;
            $new_data_array = array();
            foreach ($tid as $tst_id) {
                $get_test_parameter = $this->patholab_api_model->get_val("SELECT `test_parameter`.*,`test_master`.`test_name` FROM `test_parameter` INNER JOIN `test_master` ON `test_parameter`.`test_fk`=`test_master`.`id` WHERE `test_parameter`.`status`='1' AND `test_master`.`status`='1' AND `test_parameter`.`test_fk`='" . $tst_id . "' order by `test_parameter`.order asc");
                //print_R($get_test_parameter); die();
                $pid = array();
                foreach ($get_test_parameter as $tp_key) {
                    $pid[] = $tp_key["parameter_fk"];
                }
                if (!empty($pid)) {
                    $para = $this->patholab_api_model->get_val("SELECT * FROM `test_parameter_master` WHERE `status`='1' AND is_group='0' AND id IN (" . implode(",", $pid) . ") ORDER BY FIELD(id," . implode(",", $pid) . ")");
                    $cnt_1 = 0;
                    foreach ($para as $para_key) {
                        $formula = $this->patholab_api_model->get_val("SELECT * from use_formula where test_fk='" . $tst_id . "' and job_fk='" . $data['cid'] . "' and status='1'");
                        $get_test_parameter[$cnt_1]['use_formula'] = $formula[0]["use_formula"];
                        $get_test_parameter[$cnt_1]['on_new_page'] = $formula[0]["on_new_page"];
                        $graph_pic = $this->patholab_api_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                        $get_test_parameter[$cnt_1]['graph'] = $graph_pic;
                        $get_test_parameter1 = $get_test_parameter[$cnt_1];
//echo "SELECT * from user_test_result where test_id='".$tst_id."' and parameter_id='" . $para_key["id"] . "' and job_id='".$data['cid']."' and  status='1'";
                        //echo "SELECT * from user_test_result where test_id='" . $tst_id . "' and parameter_id='" . $para_key["id"] . "' and job_id='" . $data['cid'] . "' and  status='1'";die();

                        $para_ref_rng = $this->patholab_api_model->get_val("SELECT * FROM `parameter_referance_range` WHERE `status`='1' AND parameter_fk='" . $para_key["id"] . "' order by gender asc");
                        $final_qry = "SELECT * FROM `parameter_referance_range` WHERE STATUS='1' AND `parameter_fk`='" . $para_key["id"] . "'";

                        if ($data['user_data'][0]["age_type"] == 'D') {
                            $final_qry .= " AND gender='N'  AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='D'";
                        } else if ($data['user_data'][0]["age_type"] == 'M') {
                            $final_qry .= " AND gender='C' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='M'";
                            $data["common"] = 0;
                        } else if ($para_ref_rng[0]["gender"] == 'B' && $data['user_data'][0]["age_type"] == 'Y') {
                            $final_qry .= " AND gender='B' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                            $data["common"] = 0;
                        } else if (strtoupper($data['user_data'][0]["gender"]) == 'MALE' && $data['user_data'][0]["age_type"] == 'Y') {
                            $final_qry .= " AND gender='M' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                            $data["common"] = 0;
                        } else if (strtoupper($data['user_data'][0]["gender"]) == 'FEMALE' && $data['user_data'][0]["age_type"] == 'Y') {
                            $final_qry .= " AND gender='F' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                            $data["common"] = 0;
                        }
                        //$final_qry = $final_qry . " AND is_group='1' ";
                        $final_qry = $final_qry . " ORDER BY `type_period` ASC limit 0,1";
                        $final_qry1 = "SELECT * FROM `test_result_status` WHERE STATUS='1' AND `parameter_fk`='" . $para_key["id"] . "'";
                        $data["common"] = 1;
                        $data["para_ref_rng"] = $this->patholab_api_model->get_val($final_qry);
                        $data["para_ref_rng"][0]["common"] = "1";
                        $data["para_ref_rng"][0]["tst_id"] = $tst_id;
                        $para[$cnt_1]['parameter_range'] = $data["para_ref_rng"][0]["ref_range_low"] . "-" . $data["para_ref_rng"][0]["ref_range_high"];
                        $para_ref_status = $this->patholab_api_model->get_val($final_qry1);

                        $para_user_val = $this->patholab_api_model->get_val("SELECT * from user_test_result where test_id='" . $tst_id . "' and parameter_id='" . $para_key["id"] . "' and job_id='" . $data['cid'] . "' and  status='1'");
                        $rslt = '';
                        if (!empty($para_ref_status)) {
                            foreach ($para_ref_status as $prst) {
                                if ($prst["id"] == $para_user_val[0]["value"]) {
                                    $rslt = $prst["parameter_name"];
                                }
                            }
                        } else {
                            $rslt = $para_user_val[0]["value"];
                        }
                        $para[$cnt_1]["user_val"] = $rslt;


                        //$para[$cnt_1]['para_ref_status'] = $data["para_ref_status"];
                        $cnt_1++;
                    }
                    $get_test_parameter1['parameter'] = $para;
                } else {
                    $get_test_parameter1 = $this->patholab_api_model->get_val("SELECT id as test_fk,test_name FROM `test_master` WHERE id='" . $tst_id . "'");
                    $graph_pic = $this->patholab_api_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                    $get_test_parameter1['graph'] = $graph_pic;
                }
                $new_data_array[] = $get_test_parameter1;
                $cnt++;
            }
            echo $this->json_data("1", "", $new_data_array);
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function job_approve() {
        $id = $this->input->post("job_fk");
        $uid = $this->input->post("uid");
        if (!empty($id) && !empty($uid)) {
            $this->approve_report($id, "1");
            echo $this->json_data("1", "", array("successfully approved."));
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function test_approve() {
        $id = $this->input->post("job_fk");
        $test_id = $this->input->post("test_fk");
        $uid = $this->input->post("uid");
        $job_status = $this->input->post("approve_status");
        if (!empty($id) && !empty($test_id) && !empty($uid)) {
            $row = $this->patholab_api_model->master_num_rows("approve_job_test", array("job_fk" => $id, "test_fk" => $test_id, "status" => "1"));
            if ($row == 0) {
                $app_info = $this->patholab_api_model->master_fun_insert("approve_job_test", array("job_fk" => $id, "test_fk" => $test_id, "approve_by" => $uid));
                echo $this->json_data("1", "", "");
            } else {
                echo $this->json_data("0", "This test is already approved.", "");
            }
            $this->approve_report($id, $job_status);
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function approve_report($id, $is_job) {
        $uid = $this->input->post("uid");
        $data['cid'] = $id;
        $data['query'] = $this->patholab_api_model->job_details($data['cid']);
        $branch_fk = $data["branch_fk"] = $data['query'][0]["branch_fk"];
        $data['user_booking_info'] = $this->patholab_api_model->master_fun_get_tbl_val("booking_info", array('status' => 1, 'id' => $data['query'][0]["booking_info"]), array("id", "asc"));
        $data['user_data'] = $this->patholab_api_model->master_fun_get_tbl_val("customer_master", array('status' => 1, 'id' => $data['query'][0]["custid"]), array("id", "asc"));

        if ($data['user_booking_info'][0]["family_member_fk"] != 0) {
            $data['user_family_info'] = $this->patholab_api_model->master_fun_get_tbl_val("customer_family_master", array('status' => 1, 'id' => $data['user_booking_info'][0]["family_member_fk"]), array("id", "asc"));
            $data['user_data'][0]["gender"] = $data['user_family_info'][0]["gender"];
            $data['user_data'][0]["age"] = $data['user_family_info'][0]["age"];
            $data['user_data'][0]["age_type"] = $data['user_family_info'][0]["age_type"];
            $data['user_data'][0]["full_name"] = $data['user_family_info'][0]["name"];
            $data['user_data'][0]["email"] = $data['user_family_info'][0]["email"];
            $data['user_data'][0]["phone"] = $data['user_family_info'][0]["phone"];
            $data['user_data'][0]["dob"] = $data['user_family_info'][0]["dob"];
        }

        /* Check bitrth date start */
        $var = 0;
        if ($data['user_data'][0]["dob"] != '') {
            $var = 1;
            $this->load->library("util");
            $util = new Util;
            $age = $util->get_age($data['user_data'][0]["dob"]);
            if ($age[0] != 0) {
                $data['user_data'][0]["age"] = $age[0];
                $data['user_data'][0]["age_type"] = 'Y';
            }
            if ($age[0] == 0 && $age[1] != 0) {
                $data['user_data'][0]["age"] = $age[1];
                $data['user_data'][0]["age_type"] = 'M';
            }
            if ($age[0] == 0 && $age[1] == 0 && $age[2] != 0) {
                $data['user_data'][0]["age"] = $age[2];
                $data['user_data'][0]["age_type"] = 'D';
            }
        } else {
            $data['user_data'][0]["age"] = '-';
            $data['user_data'][0]["age_type"] = '';
        }
        /* Check birth date end */
        $tid = array();
        $data['parameter_list'] = array();
        if (trim($data['query'][0]['testid']) == null && $data['query'][0]["packageid"] != null) {
            $package_id = $data['query'][0]["packageid"];
            $pid = explode("%", $data['query'][0]['packageid']);
            foreach ($pid as $pkey) {
                $p_test = $this->patholab_api_model->get_val("SELECT `package_test`.`test_fk` FROM `package_test` INNER JOIN `test_master` ON `package_test`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `package_test`.`status`='1' AND `package_test`.`package_fk`='" . $pkey . "'");
                foreach ($p_test as $tp_key) {
                    $tid[] = $tp_key["test_fk"];
                }
            }
        } else if (trim($data['query'][0]['testid']) != null && $data['query'][0]["packageid"] != null) {

            $tid = explode(",", $data['query'][0]['testid']);
            $package_id = $data['query'][0]["packageid"];
            $pid = explode("%", $data['query'][0]['packageid']);
            foreach ($pid as $pkey) {
                $p_test = $this->patholab_api_model->get_val("SELECT `package_test`.`test_fk` FROM `package_test` INNER JOIN `test_master` ON `package_test`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `package_test`.`status`='1' AND `package_test`.`package_fk`='" . $pkey . "'");
                foreach ($p_test as $tp_key) {
                    $tid[] = $tp_key["test_fk"];
                }
            }
        } else {
            $tid = explode(",", $data['query'][0]['testid']);
        }
        foreach ($tid as $t_key) {
            $p_test = $this->patholab_api_model->get_val("SELECT sub_test_master.* FROM `sub_test_master` INNER JOIN `test_master` ON `sub_test_master`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `sub_test_master`.`status`='1' AND `sub_test_master`.`test_fk`='" . $t_key . "'");
            foreach ($p_test as $tp_key) {
                $tid[] = $tp_key["sub_test"];
            }
        }
        $tid = array_unique($tid);
        $cnt = 0;
        $new_data_array = array();
        foreach ($tid as $tst_id) {
            $get_test_parameter = $this->patholab_api_model->get_val("SELECT `test_parameter`.*,`test_master`.`test_name` FROM `test_parameter` INNER JOIN `test_master` ON `test_parameter`.`test_fk`=`test_master`.`id` WHERE `test_parameter`.`status`='1' AND `test_master`.`status`='1' AND `test_parameter`.`test_fk`='" . $tst_id . "' order by `test_parameter`.order asc");
            $pid = array();
            foreach ($get_test_parameter as $tp_key) {
                $pid[] = $tp_key["parameter_fk"];
            }
            if (!empty($pid)) {
                $para = $this->patholab_api_model->get_val("SELECT * FROM `test_parameter_master` WHERE `status`='1' AND id IN (" . implode(",", $pid) . ") ORDER BY FIELD(id," . implode(",", $pid) . ")");
                $cnt_1 = 0;
                foreach ($para as $para_key) {
                    $formula = $this->patholab_api_model->get_val("SELECT * from use_formula where test_fk='" . $tst_id . "' and job_fk='" . $data['cid'] . "' and status='1'");
                    $get_test_parameter[$cnt_1]['use_formula'] = $formula[0]["use_formula"];
                    $get_test_parameter[$cnt_1]['on_new_page'] = $formula[0]["on_new_page"];
                    $graph_pic = $this->patholab_api_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                    $get_test_parameter[$cnt_1]['graph'] = $graph_pic;
                    $get_test_parameter1 = $get_test_parameter[$cnt_1];
                    $para_user_val = $this->patholab_api_model->get_val("SELECT * from user_test_result where test_id='" . $tst_id . "' and parameter_id='" . $para_key["id"] . "' and job_id='" . $data['cid'] . "' and  status='1'");
                    $para[$cnt_1]["user_val"] = $para_user_val;
                    $para_ref_rng = $this->patholab_api_model->get_val("SELECT * FROM `parameter_referance_range` WHERE `status`='1' AND parameter_fk='" . $para_key["id"] . "' order by gender asc");
                    $final_qry = "SELECT * FROM `parameter_referance_range` WHERE STATUS='1' AND `parameter_fk`='" . $para_key["id"] . "'";
                    if ($data['user_data'][0]["age_type"] == 'D') {
                        $final_qry .= " AND gender='N'  AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='D'";
                    } else if ($data['user_data'][0]["age_type"] == 'M') {
                        $final_qry .= " AND gender='C' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='M'";
                        $data["common"] = 0;
                    } else if ($para_ref_rng[0]["gender"] == 'B' && $data['user_data'][0]["age_type"] == 'Y') {
                        $final_qry .= " AND gender='B' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                        $data["common"] = 0;
                    } else if (strtoupper($data['user_data'][0]["gender"]) == 'MALE' && $data['user_data'][0]["age_type"] == 'Y') {
                        $final_qry .= " AND gender='M' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                        $data["common"] = 0;
                    } else if (strtoupper($data['user_data'][0]["gender"]) == 'FEMALE' && $data['user_data'][0]["age_type"] == 'Y') {
                        $final_qry .= " AND gender='F' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                        $data["common"] = 0;
                    }
                    $final_qry = $final_qry . " ORDER BY `type_period` ASC limit 0,1";
                    $final_qry1 = "SELECT * FROM `test_result_status` WHERE STATUS='1' AND `parameter_fk`='" . $para_key["id"] . "'";
                    $data["common"] = 1;
                    $data["para_ref_rng"] = $this->patholab_api_model->get_val($final_qry);
                    $data["para_ref_rng"][0]["common"] = "1";
                    $data["para_ref_rng"][0]["tst_id"] = $tst_id;
                    $para[$cnt_1]['para_ref_rng'] = $data["para_ref_rng"];

                    $data["para_ref_status"] = $this->patholab_api_model->get_val($final_qry1);
                    $para[$cnt_1]['para_ref_status'] = $data["para_ref_status"];
                    $cnt_1++;
                }
                $get_test_parameter1[0]['parameter'] = $para;
                $new_data_array[] = $get_test_parameter1;
            } else {
                $get_test_parameter1 = $this->patholab_api_model->get_val("SELECT id as test_fk,test_name FROM `test_master` WHERE id='" . $tst_id . "'");
                $graph_pic = $this->patholab_api_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                $get_test_parameter1[0]['graph'] = $graph_pic;
                $new_data_array[] = $get_test_parameter1[0];
            }

            $cnt++;
        }
        //print_r($new_data_array); die();
        $data["new_data_array"] = $new_data_array;
        $data['result_list'] = $this->patholab_api_model->master_fun_get_tbl_val("user_test_result", array('status' => 1, 'job_id' => $data['cid']), array("id", "asc"));
        $pdfFilePath = FCPATH . "/upload/report/" . $data['query'][0]['order_id'] . "_result_wlpd.pdf";
        $pdfFilePath1 = FCPATH . "/upload/report/" . $data['query'][0]['order_id'] . "_result.pdf";
        $data['page_title'] = 'AirmedLabs'; // pass data to the view
        ini_set('memory_limit', '128M'); // boost the memory limit if it's low <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="?" draggable="false" class="emoji">
        $html = $this->load->view('result_pdf', $data, true); // render the view into HTML 
        if (file_exists($pdfFilePath)) {
            $this->delete_downloadfile($pdfFilePath);
        }

        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->autoScriptToLang = true;
        $pdf->baseScript = 1; // Use values in classes/ucdn.php  1 = LATIN
        $pdf->autoVietnamese = true;
        $pdf->autoArabic = true;
        $pdf->autoLangToFont = true;

        $name = "DR. Self";
        if ($data["query"][0]['dname'] != "") {
            $name = ucfirst($data["query"][0]['dname']);
        }
        $base_url = base_url();

        $content = $this->patholab_api_model->master_fun_get_tbl_val("pdf_design", array('branch_fk' => $branch_fk), array("id", "asc"));
        //print_r($content); die();
        $find = array(
            '/{{BARCODE}}/',
            '/{{CUSTID}}/',
            '/{{REGDATE}}/',
            '/{{COLLECTIONON}}/',
            '/{{NAME}}/',
            '/{{REPORTDATE}}/',
            '/{{AGE}}/',
            '/{{GENDER}}/',
            '/{{REFFERBY}}/',
            '/{{LOCATION}}/',
            '/{{TELENO}}/'
        );
        $barecode_url = $base_url . 'user_assets/images/pdf_barcode.png';
        $logo_url = $base_url . 'user_assets/images/logoaastha.png';
        $barecode_url = $base_url . 'user_assets/images/pdf_barcode.png';
        $sign_url = $base_url . 'user_assets/images/dr_gupta_sign.png';
        $phone_url = $base_url . 'user_assets/images/pdf_phn_btn.png';
        $replace = array(
            'pdf_barcode.png',
            $id,
            date("d-M-Y g:i", strtotime($data["query"][0]['regi_date'])),
            date("d-M-Y g:i", strtotime($data["query"][0]['date'])),
            strtoupper($data["user_data"][0]['full_name']),
            date('d-M-Y'),
            $data["user_data"][0]['age'] . " " . $data['user_data'][0]["age_type"],
            strtoupper($data["user_data"][0]['gender']),
            strtoupper($name),
            strtoupper($data["query"][0]['test_city_name']),
            $data["user_data"][0]['mobile']
        );
        $header = preg_replace($find, $replace, $content[0]["header"]);


        $pdf->SetHTMLHeader($header);
        $pdf->AddPage('p', // L - landscape, P - portrait
                '', '', '', '', 5, // margin_left
                5, // margin right
                83, // margin top
                80, // margin bottom
                2, // margin header
                2); // margin footer
        $sign_url = $base_url . 'user_assets/images/dr_gupta_sign.png';
        $phone_url = $base_url . 'user_assets/images/pdf_phn_btn.png';
        $emailimg = $base_url . 'user_assets/images/email-icon.png';
        $webimg = $base_url . 'user_assets/images/web-icon.png';
        $lastimg = $base_url . 'user_assets/images/lastimg.png';
        $pdf->SetHTMLFooter($content[0]["footer"]);
        $pdf->WriteHTML($html);
        $pdf->Output($pdfFilePath, 'F'); // save to file because we can
        $name1 = $this->without_approve_report($data);

        $name = $data['query'][0]['order_id'] . "_result_wlpd.pdf";
        $name_orgnl = $data['user_data'][0]["full_name"] . "_result_with_latterhead.pdf";
        $name_orgnl1 = $data['user_data'][0]["full_name"] . "_result.pdf";
        $count = $this->patholab_api_model->master_fun_get_tbl_val("report_master", array('job_fk' => $id), array("id", "asc"));
        if (!empty($count)) {
            $data1 = array('job_fk' => $id, 'report' => $name, 'status' => 1, "original" => $name_orgnl, 'without_laterpad' => $name1, "without_laterpad_original" => $name_orgnl1, "type" => "c", "updated_date" => date("Y-m-d H:i:s"));
            $this->patholab_api_model->master_fun_update('report_master', array('job_fk' => $id), $data1);
        } else {
            $data1 = array('job_fk' => $id, 'report' => $name, 'status' => 1, "original" => $name_orgnl, "type" => "c", 'without_laterpad' => $name1, "without_laterpad_original" => $name_orgnl1, "created_date" => date("Y-m-d H:i:s"));
            $this->patholab_api_model->master_fun_insert("report_master", $data1);
        }
        if ($is_job == 1) {
            $this->patholab_api_model->master_fun_update('job_master', array('id' => $id), array("report_approve_by" => $uid));
        }
        $this->patholab_api_model->master_fun_insert("job_log", array("job_fk" => $id, "created_by" => "", "updated_by" => $uid, "deleted_by" => "", "job_status" => '', "message_fk" => "17", "date_time" => date("Y-m-d H:i:s")));
    }

    function without_approve_report($data) {
        $pdfFilePath = FCPATH . "/upload/report/" . $data['query'][0]['order_id'] . "_result.pdf";
        $data['page_title'] = 'AirmedLabs'; // pass data to the view
        ini_set('memory_limit', '128M'); // boost the memory limit if it's low <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="?" draggable="false" class="emoji">
        $html = $this->load->view('result_pdf', $data, true); // render the view into HTML 
        if (file_exists($pdfFilePath)) {
            $this->delete_downloadfile($pdfFilePath);
        }
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->autoScriptToLang = true;
        $pdf->baseScript = 1; // Use values in classes/ucdn.php  1 = LATIN
        $pdf->autoVietnamese = true;
        $pdf->autoArabic = true;
        $pdf->autoLangToFont = true;
        $name = "DR. Self";
        if ($data["query"][0]['dname'] != "") {
            $name = ucfirst($data["query"][0]['dname']);
        }
        $base_url = base_url();
        $branch_fk = $data["branch_fk"];
        if ($branch_fk == 1 || $branch_fk == 2 || $branch_fk == 6 || $branch_fk == 7 || $branch_fk == 8 || $branch_fk == 9) {
            $pdf_id = 1;
        } else {
            $pdf_id = 11;
        }
        $content = $this->patholab_api_model->master_fun_get_tbl_val("pdf_design", array('id' => $pdf_id), array("id", "asc"));
        $find = array(
            '/{{BARCODE}}/',
            '/{{CUSTID}}/',
            '/{{REGDATE}}/',
            '/{{COLLECTIONON}}/',
            '/{{NAME}}/',
            '/{{REPORTDATE}}/',
            '/{{AGE}}/',
            '/{{GENDER}}/',
            '/{{REFFERBY}}/',
            '/{{LOCATION}}/',
            '/{{TELENO}}/'
        );
        $replace = array(
            'pdf_barcode.png',
            $data['cid'],
            date("d-M-Y g:i", strtotime($data["query"][0]['regi_date'])),
            date("d-M-Y g:i", strtotime($data["query"][0]['date'])),
            $data["user_data"][0]['full_name'],
            date('d-M-Y'),
            $data["user_data"][0]['age'] . " " . $data['user_data'][0]["age_type"],
            ucfirst($data["user_data"][0]['gender']),
            $name,
            $data["query"][0]['test_city_name'],
            $data["user_data"][0]['mobile']
        );
        $header = preg_replace($find, $replace, $content[0]["header"]);
        $pdf->SetHTMLHeader($header);
        $pdf->AddPage('p', // L - landscape, P - portrait
                '', '', '', '', 5, // margin_left
                5, // margin right
                83, // margin top
                80, // margin bottom
                2, // margin header
                2); // margin footer
        $pdf->SetHTMLFooter($content[0]["footer"]);

        $pdf->WriteHTML($html);
        $pdf->Output($pdfFilePath, 'F'); // save to file because we can
        return $data['query'][0]['order_id'] . "_result.pdf";
    }

    function delete_downloadfile($path) {
        $this->load->helper('file');
        unlink($path);
    }

    function check_api_version() {
        $check_version_array = array("android" => array("version" => "47", "compulsory" => "yes", "message" => "New version available please update it and enjoy new feature."), "ios" => array("version" => "2", "compulsory" => "no", "message" => "New version available please update it and enjoy new feature."));
        echo $this->json_data("1", "", array($check_version_array));
    }

}
