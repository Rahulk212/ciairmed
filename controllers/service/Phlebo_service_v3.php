<?php

class Phlebo_service_v3 extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->library('email');
        $this->load->model('service_model');
        $this->load->model('user_master_model');
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
        $app_info = $this->service_model->master_fun_insert("user_track", $user_track_data);
        //return true;
    }

    function get_phlebo_schedule() {
        $usercode = $this->input->get_post('bdate');
        $type = $this->input->get_post('type');

        if ($type) {
            $usercode1 = explode("/", $usercode);
            $usercode = $usercode1[2] . "-" . $usercode1[1] . "-" . $usercode1[0];
        } else {
            $usercode = date('Y-m-d', strtotime(str_replace('-', '/', $usercode)));
        }
        $day_of_the_week = date('w', strtotime($usercode));
        $row = $this->service_model->phlebo_time_slot_on_day($day_of_the_week, $usercode);
        if ($usercode >= date("Y-m-d")) {
            if (!empty($row)) {
                $new_ary = array();
                $phone_array = array();
                $cnt = 0;
                foreach ($row as $key) {
                    $phlebo_count = $this->service_model->master_fun_get_tbl_val("phlebo_master", array("status" => "1"), array("id", "desc"));
                    $today_booking = $this->service_model->master_fun_get_tbl_val("phlebo_assign_job", array("status" => "1", "time_fk" => $key["time_slot_fk"], "date" => $usercode), array("id", "desc"));
                    if (count($phlebo_count) > count($today_booking)) {
                        $key["booking_status"] = "Available";
                    } else {
                        $key["booking_status"] = "Unavailable";
                    }
                    if ($usercode == date("Y-m-d") && $key["real_time"] > date("H:i:s")) {
                        if ($cnt != 0) {
                            $new_ary[] = $key;
                        }
                        if (!empty($type)) {
                            if (count($phlebo_count) > count($today_booking)) {
                                if ($cnt != 0) {
                                    $phone_array[] = $key;
                                }
                            }
                        }
                        $cnt++;
                    } else if ($usercode != date("Y-m-d")) {
                        if ($cnt != 0) {
                            $new_ary[] = $key;
                        }
                        if (!empty($type)) {
                            if (count($phlebo_count) > count($today_booking)) {
                                if ($cnt != 0) {
                                    $phone_array[] = $key;
                                }
                            }
                        }
                        $cnt++;
                    }
                }
                if (!empty($new_ary)) {
                    if (empty($type)) {
                        echo $this->json_data("1", "", $new_ary);
                    } else {
                        if (!empty($phone_array)) {
                            echo $this->json_data("1", "", $phone_array);
                        } else {
                            echo $this->json_data("0", "Time slot unavailable.", "");
                        }
                    }
                } else {
                    echo $this->json_data("0", "Time slot unavailable.", "");
                }
            } else {
                echo $this->json_data("0", "Time slot unavailable.", "");
            }
        } else {
            echo $this->json_data("0", "Invalid date.", "");
        }
    }

    function user_family_member() {
        $uid = $this->input->get_post('uid');
        $relation_list = $this->service_model->get_user_family_member($uid);
        if (!empty($relation_list)) {
            echo $this->json_data("1", "", $relation_list);
        } else {
            echo $this->json_data("0", "Add your family member in your profile.", "");
        }
    }

    function user_adderss() {
        $uid = $this->input->get_post('uid');
        $job_address = $this->service_model->master_fun_get_tbl_val("job_master", array("status !=" => "0", "address !=" => "", "cust_fk" => $uid), array("address", "asc"));
        $customer_info = $this->service_model->master_fun_get_tbl_val("customer_master", array("id" => $uid), array("address", "asc"));
        $cnt = 0;
        $address = array();
        $add_array = array();
        if (trim($customer_info[0]["address"])) {
            if (!in_array($customer_info[0]["address"], $add_array)) {
                $address[$cnt]["address"] = $customer_info[0]["address"];
                $add_array[] = $customer_info[0]["address"];
                $cnt++;
            }
        }
        foreach ($job_address as $key) {
            if (trim($key["address"])) {
                if (!in_array($key["address"], $add_array)) {
                    $address[$cnt]["address"] = $key["address"];
                    $add_array[] = $key["address"];
                    $cnt++;
                }
            }
        }
        if (!empty($address)) {
            //$address = array_unique($address);
            echo $this->json_data("1", "", $address);
        } else {
            echo $this->json_data("0", "Add your adderss in your profile.", "");
        }
    }

    function store_booking_info() {
        $typ = $this->input->get_post('typ');
        $crelation = $this->input->get_post('crelation');
        $uaddress = $this->input->get_post('uaddress');
        $bookdate = $this->input->get_post('bookdate');
        $select_slot = $this->input->get_post('select_slot');

        $data = array(
            "type" => $typ,
            "family_member_fk" => $crelation,
            "address" => $uaddress,
            "date" => $bookdate,
            "time_slot_fk" => $select_slot,
            "createddate" => date("Y-m-d H:i:s")
        );
        $customer_info = $this->service_model->master_fun_insert("booking_info", $data);
        return $customer_info;
    }

    function json_data($status, $error_msg, $data = NULL) {
        if ($data == NULL) {
            $data = array();
        }
        $final = array("status" => $status, "error_msg" => $error_msg, "data" => $data);
        return str_replace("null", '" "', json_encode($final));
    }

    function relation() {
        $data = $this->service_model->master_fun_get_tbl_val("relation_master", array("status" => 1), array("name", "asc"));
        if (!empty($data)) {
            echo $this->json_data("1", "", $data);
        } else {
            echo $this->json_data("0", "Data not available", "");
        }
    }

    function add_family_member() {
        $uid = $this->input->get_post('uid');
        $name = $this->input->get_post('name');
        $relation_fk = $this->input->get_post('relation_fk');
        $email = $this->input->get_post('email');
        $phone = $this->input->get_post('phone');
        if ($uid != '' && $name != '' && $relation_fk != '') {
            $data = array(
                "user_fk" => $uid,
                "name" => $name,
                "relation_fk" => $relation_fk,
                "email" => $email,
                "phone" => $phone,
                "created_date" => date("Y-m-d H:i:s")
            );
            $customer_info = $this->service_model->master_fun_insert("customer_family_master", $data);
            $R_data = $this->service_model->master_fun_get_tbl_val("relation_master", array("id" => $relation_fk), array("name", "asc"));
            echo $this->json_data("1", "", array(array("id" => $customer_info, "msg" => "Successfully", "relation_name" => $R_data[0]["name"])));
        } else {
            echo $this->json_data("0", "Name and relation is required.", "");
        }
    }

    function save_booking_data() {
        $uid = $this->input->get_post('uid');
        $type = $this->input->get_post('type');
        $crelation = $this->input->get_post('crelation');
        $uaddress = $this->input->get_post('uaddress');
        $bookdate = $this->input->get_post('bookdate');
        $select_slot = $this->input->get_post('select_slot');
        $bookdate = date('Y-m-d', strtotime(str_replace('-', '/', $bookdate)));
        $cal_data = $this->service_model->master_fun_get_tbl_val("phlebo_calender", array("status" => 1, "id" => $select_slot), array("id", "asc"));
        $booking_slot = $this->service_model->master_fun_get_tbl_val("phlebo_time_slot", array("status" => "1", "id" => $cal_data[0]["time_slot_fk"]), array("id", "asc"));

        if ($bookdate == date("Y-m-d") && $booking_slot[0]["start_time"] >= date("H:i:s")) {
            $data = array(
                "user_fk" => $uid,
                "type" => $type,
                "family_member_fk" => $crelation,
                "address" => $uaddress,
                "date" => $bookdate,
                "time_slot_fk" => $select_slot,
                "createddate" => date("Y-m-d H:i:s")
            );
        } else if ($bookdate >= date("Y-m-d")) {
            $data = array(
                "user_fk" => $uid,
                "type" => $type,
                "family_member_fk" => $crelation,
                "address" => $uaddress,
                "date" => $bookdate,
                "time_slot_fk" => $select_slot,
                "createddate" => date("Y-m-d H:i:s")
            );
        } else {
            echo $this->json_data("0", "Invalid date or time.", "");
            die();
        }
        if ($select_slot == 'emergency') {
            $data = $data + array("emergency" => "1");
        }
        $customer_info = $this->service_model->master_fun_insert("booking_info", $data);
        if ($customer_info) {
            echo $this->json_data("1", "", array(array("id" => $customer_info, "msg" => "Successfully")));
        } else {
            echo $this->json_data("0", "Oops somthing wrong.Try again.", "");
        }
    }

    function user_family_member_edit() {
        $uid = $this->input->get_post('mid');
        $member_details = $this->service_model->get_user_family_member_data($uid);
        if ($uid != "") {
            if (!empty($member_details)) {
                echo $this->json_data("1", "", $member_details);
            } else {
                echo $this->json_data("0", "Data Not Available.", "");
            }
        } else {
            echo $this->json_data("0", "Parameter Not Passed.", "");
        }
    }

    function user_family_member_update() {
        $uid = $this->input->get_post('mid');
        $name = $this->input->get_post('mname');
        $relation = $this->input->get_post('mrelation');
        $phone = $this->input->get_post('mphone');
        $email = $this->input->get_post('memail');
        $data = array("name" => $name, "relation_fk" => $relation, "email" => $email, "phone" => $phone);
        $member_details = $this->service_model->master_fun_update('customer_family_master', $uid, $data);
        if ($uid != "") {
            if ($member_details == 1) {
                echo $this->json_data("1", "Family Member Successfully Updated.", "");
            } else {
                echo $this->json_data("0", "Data Not Available.", "");
            }
        } else {
            echo $this->json_data("0", "Parameter Not Passed.", "");
        }
    }

    function user_family_member_delete() {
        $uid = $this->input->get_post('mid');
        $member_details = $this->service_model->master_fun_update('customer_family_master', $uid, array('status' => 0));
        if ($uid != "") {
            if ($member_details == 1) {
                echo $this->json_data("1", "Family Member Successfully Deleted.", "");
            } else {
                echo $this->json_data("0", "Data Not Available.", "");
            }
        } else {
            echo $this->json_data("0", "Parameter Not Passed.", "");
        }
    }

    function pending_job_list() {
        $job_list = $this->service_model->master_fun_get_tbl_val('job_master', array('status' => "1"), array("id", "desc"));
        if (!empty($job_list)) {
            $data = array();
            foreach ($job_list as $key) {
                $details = $this->get_job_details($key["id"]);
                $booking_details = $this->service_model->get_val("SELECT 
  `booking_info`.*,
  `customer_family_master`.`name`,
  `phlebo_time_slot`.`start_time`,
  `phlebo_time_slot`.`end_time`,
  `customer_master`.`full_name` 
FROM 
  `booking_info` 
  LEFT JOIN `customer_family_master` 
    ON `customer_family_master`.`id` = `booking_info`.`family_member_fk` 
  INNER JOIN `customer_master` 
    ON `booking_info`.`user_fk` = `customer_master`.`id` 
  LEFT JOIN `phlebo_time_slot` 
    ON `booking_info`.`time_slot_fk` = `phlebo_time_slot`.`id` 
WHERE `booking_info`.`id` = '" . $key["booking_info"] . "' ");
                $tcname = $this->service_model->master_fun_get_tbl_val('test_cities', array("id" => $job_list[0]["test_city"]), array("id", "desc"));
                $originalDate = $booking_details[0]["date"];
                $newDate = date("d-m-Y", strtotime($originalDate));
                $booking_details[0]["date"] = $newDate;

                $originalDate = $booking_details[0]["start_time"];
                $newDate = date("g:i A", strtotime($originalDate));
                $booking_details[0]["start_time"] = $newDate;

                $originalDate = $booking_details[0]["end_time"];
                $newDate = date("g:i A", strtotime($originalDate));
                $booking_details[0]["end_time"] = $newDate;

                $details[0]["test_city"] = $tcname[0]["name"];
                $details[0]["booking_info"] = $booking_details;
                $data[] = $details[0];
            }
			//echo "<pre>";print_r($data); die();
            echo $this->json_data("1", "", $data);
        } else {
            echo $this->json_data("0", "New job not available.", "");
        }
    }

    function get_job_details($job_id) {
        $job_details = $this->service_model->master_fun_get_tbl_val("job_master", array("status !=" => "0", "id" => $job_id), array("id", "asc"));
        if (!empty($job_details)) {
            $book_test = $this->service_model->master_fun_get_tbl_val("job_test_list_master", array("job_fk" => $job_id), array("id", "desc"));
            $book_package = $this->service_model->master_fun_get_tbl_val("book_package_master", array("job_fk" => $job_id, "status" => "1"), array("id", "desc"));
            $test_name = array();
            foreach ($book_test as $key) {
                $price1 = $this->service_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master`.`id`='" . $key["test_fk"] . "'");
                if(count($price1)>0){
				$test_name[] = $price1[0];
				}
            }
			if(empty($test_name)){
				$test_name=array();
			}
			if(count($test_name)==0){
				$test_name=array();
			}
            $job_details[0]["book_test"] = $test_name;
            $package_name = array();
            foreach ($book_package as $key) {
                $price1 = $this->service_model->get_val("SELECT 
          `package_master`.*,
          `package_master_city_price`.`a_price` AS `a_price`,
          `package_master_city_price`.`d_price` AS `d_price`
          FROM
          `package_master`
          INNER JOIN `package_master_city_price`
          ON `package_master`.`id` = `package_master_city_price`.`package_fk`
          WHERE `package_master`.`status` = '1'
          AND `package_master_city_price`.`status` = '1' AND `package_master_city_price`.`city_fk` = '" . $job_details[0]["test_city"] . "' AND `package_master`.`id`='" . $key["package_fk"] . "'");
if(count($price1)>0){               
			   $package_name[] = $price1[0];
}
            }
			if(empty($package_name)){
				$package_name=array();
			}
			if(count($package_name)==0){
				$package_name=array();
			}
            $job_details[0]["book_package"] = $package_name;
        }
        return $job_details;
    }

    function phlebo_login() {
        $email = $this->input->get_post('email');
        $password = $this->input->get_post('password');
        //$device_id = $this->input->get_post('device_id');
        if ($email != NULL && $password != NULL) {
            $row = $this->service_model->master_num_rows("phlebo_master", array("email" => $email, "password" => $password, "status" => 1));
            if ($row == 1) {
                $data = $this->service_model->master_fun_get_tbl_val("phlebo_master", array("email" => $email, "password" => $password, "status" => 1), array("id", "asc"));
                echo $this->json_data("1", "", $data);
            } else {
                echo $this->json_data("0", "Email Or Password Not match", "");
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function get_phlebo_did() {
        $cid = $this->input->get_post('pid');
        $did = $this->input->get_post('did');
        $type = $this->input->get_post('type');
        $member_details = $this->service_model->master_fun_update('phlebo_master', $cid, array('device_id' => $did, "device_type" => $type));
        if ($cid != "") {
            if ($member_details == 1) {
                echo $this->json_data("1", "Successfully", "");
            } else {
                echo $this->json_data("0", "Oops somthing wrong.", "");
            }
        } else {
            echo $this->json_data("0", "Phlebo id is required.", "");
        }
    }

    function accept_job() {
        $job_id = $this->input->get_post("job_id");
        $phlebo_fk = $this->input->get_post("phlebo_fk");
        $new_job_details = $this->service_model->master_fun_get_tbl_val("job_master", array('id' => $job_id), array("id", "asc"));
        $booking_info = $this->service_model->master_fun_get_tbl_val("booking_info", array('id' => $new_job_details[0]["booking_info"]), array("id", "asc"));
        $check_job_status = $this->service_model->master_num_rows("phlebo_assign_job", array('job_fk' => $job_id, "status" => "1"), array("id", "asc"));
        if ($check_job_status == 0) {
            if (!empty($phlebo_fk)) {
                $data = array("job_fk" => $job_id, "phlebo_fk" => $phlebo_fk, "date" => $booking_info[0]["date"], "time_fk" => $booking_info[0]["time_slot_fk"], "address" => $booking_info[0]["address"], "notify_cust" => 1, "created_date" => date("Y-m-d H:i:s"));
                $insert = $this->service_model->master_fun_insert("phlebo_assign_job", $data);
                //$this->user_test_master_model->master_fun_insert("job_log", array("job_fk" => $job_id, "created_by" => "", "updated_by" => $login_id, "deleted_by" => "", "message_fk" => "8", "date_time" => date("Y-m-d H:i:s")));
                //$update = $this->job_model->master_fun_update("phlebo_master", array("id", $phlebo_id), $data);
                $phlebo_details = $this->service_model->master_fun_get_tbl_val("phlebo_master", array('id' => $phlebo_fk), array("id", "asc"));
                $phlebo_job_details = $this->service_model->master_fun_get_tbl_val("phlebo_assign_job", array('job_fk' => $job_id), array("id", "asc"));
                $p_time = $this->service_model->master_fun_get_tbl_val("phlebo_time_slot", array('id' => $phlebo_job_details[0]["time_fk"]), array("id", "asc"));
                $job_details = $this->service_model->master_fun_get_tbl_val("job_master", array('id' => $job_id), array("id", "asc"));
                $customer_details = $this->service_model->master_fun_get_tbl_val("customer_master", array('id' => $job_details[0]['cust_fk']), array("id", "asc"));
                if ($insert) {
                    $family_member_name = $this->service_model->get_family_member_name($job_id);
                    if (!empty($family_member_name)) {
                        $c_name = $family_member_name[0]["name"];
                        $cmobile = $family_member_name[0]["phone"];
                        if (empty($cmobile)) {
                            $cmobile = $customer_details[0]["mobile"];
                        }
                    } else {
                        $c_name = $customer_details[0]["full_name"];
                        $cmobile = $customer_details[0]["mobile"];
                    }
                    $s_time = date('h:i a', strtotime($p_time[0]["start_time"]));
                    $e_time = date('h:i a', strtotime($p_time[0]["end_time"]));
                    $sms_message = $this->service_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "phlebo_msg"), array("id", "asc"));
                    $sms_message = preg_replace("/{{NAME}}/", ucfirst($phlebo_details[0]["name"]), $sms_message[0]["message"]);
                    $sms_message = preg_replace("/{{MOBILE}}/", $phlebo_details[0]["mobile"], $sms_message);
                    $sms_message = preg_replace("/{{ORDERID}}/", $job_details[0]["order_id"], $sms_message);
                    $sms_message = preg_replace("/{{CNAME}}/", $c_name, $sms_message);
                    $sms_message = preg_replace("/{{CMOBILE}}/", $cmobile, $sms_message);
                    $sms_message = preg_replace("/{{CADDRESS}}/", $phlebo_job_details[0]["address"], $sms_message);
                    $sms_message = preg_replace("/{{DATE}}/", $phlebo_job_details[0]["date"], $sms_message);
                    $sms_message = preg_replace("/{{TIME}}/", $s_time . " To " . $e_time, $sms_message);
                    $job_details = $this->get_job_details($job_id);
                    $b_details = array();
                    $collectable_amount = 0;
                    foreach ($job_details[0]["book_test"] as $bkey) {
                        $b_details[] = $bkey["test_name"] . " Rs." . $bkey["price"];
                        $collectable_amount = $collectable_amount + $bkey["price"];
                    }
                    foreach ($job_details[0]["book_package"] as $bkey) {
                        $b_details[] = $bkey["title"] . " Rs." . $bkey["d_price"];
                        $collectable_amount = $collectable_amount + $bkey["d_price"];
                    }
                    $sms_message = preg_replace("/{{BOOKINFO}}/", implode(",", $b_details) . " Total amount: Rs." . $collectable_amount, $sms_message);
                    //$this->user_test_master_model->master_fun_insert("test", array("test"=>$sms_message."-".json_encode($job_details)));
                    //$sms_message="done";
                    $mobile = $phlebo_details[0]['mobile'];
                    $this->load->helper("sms");
                    $notification = new Sms();
                    $notification->send($mobile, $sms_message);
                    // if ($notify == 1) {
                    $sms_message = $this->service_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "phlebo_msg_cust"), array("id", "asc"));
                    $sms_message = preg_replace("/{{NAME}}/", ucfirst($c_name), $sms_message[0]["message"]);
                    $sms_message = preg_replace("/{{MOBILE}}/", $customer_details[0]["mobile"], $sms_message);
                    $sms_message = preg_replace("/{{ORDERID}}/", $job_details[0]["order_id"], $sms_message);
                    $sms_message = preg_replace("/{{PNAME}}/", ucfirst($phlebo_details[0]["name"]), $sms_message);
                    $sms_message = preg_replace("/{{PMOBILE}}/", $phlebo_details[0]["mobile"], $sms_message);
                    $sms_message = preg_replace("/{{DATE}}/", $phlebo_job_details[0]["date"], $sms_message);
                    $sms_message = preg_replace("/{{TIME}}/", $s_time . " To " . $e_time, $sms_message);
                    $sms_message = preg_replace("/{{BOOKINFO}}/", implode(",", $b_details) . " Total amount: Rs." . $collectable_amount, $sms_message);
                    $mobile = $customer_details[0]['mobile'];
                    //$sms_message="done";
                    $notification->send($cmobile, $sms_message);
                    if (!empty($job_details[0]["address"])) {
                        $this->service_model->master_fun_update("customer_master", array('id', $job_details[0]["cust_fk"]), array("address" => $job_details[0]["address"]));
                    }
                    if (!empty($family_member_name)) {
                        $this->service_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $customer_details[0]["mobile"], "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
                    }
                    echo $this->json_data("1", "", array());
                    //}
                } else {
                    echo $this->json_data("0", "Invalid parameter.", "");
                }
            } else {
                echo $this->json_data("0", "Invalid parameter.", "");
            }
        } else {
            echo $this->json_data("0", "This job already assign.", "");
        }
    }

}
