<?php

class Instument extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('patholab_api_model');
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
        $app_info = $this->patholab_api_model->master_fun_insert("instument_user_track", $user_track_data);
        //return true;
    }

    function uplodData() {
        $Lab_id = trim($this->input->get_post('lab_id'));
        $Test_Date_Time = trim($this->input->get_post('test_date_time'));
        $Analyte_code = trim($this->input->get_post('analyte_code'));
        $Result_value = trim($this->input->get_post('result_value'));
        $Status = trim($this->input->get_post('status'));
        if (!empty($Lab_id) && !empty($Test_Date_Time) && !empty($Analyte_code) && !empty($Result_value) && !empty($Status)) {
            $insert = array(
                "lab_id" => $Lab_id,
                "test_date_time" => $Test_Date_Time,
                "analyte_code" => $Analyte_code,
                "result_value" => $Result_value,
                "status" => $Status
            );
            $app_info = $this->patholab_api_model->master_fun_insert("instument_data_storage", $insert);
            echo $this->json_data("1", "", "success");
        } else {
            echo $this->json_data("0", "All fields are required.", "");
        }
    }

    function showData() {
        $data = $this->patholab_api_model->master_fun_get_tbl_val("instument_data_storage", array("id !=" => ""), array("id", "desc"));
        $output = '<!DOCTYPE html>
<html>
<head>
<style>
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}

tr:nth-child(even) {
    background-color: #dddddd;
}
</style>
</head>
<body>

<table>
<tr>
    <th>id</th>
    <th>lab_id</th>
    <th>test_date_time</th>
    <th>analyte_code</th>
    <th>result_value</th>
    <th>status</th>
  </tr>';
        foreach ($data as $key) {
            $output .= '<tr>
    <td>' . $key['id'] . '</td>
    <td>' . $key['lab_id'] . '</td>
    <td>' . $key['test_date_time'] . '</td>
    <td>' . $key['analyte_code'] . '</td>
    <td>' . $key['result_value'] . '</td>
    <td>' . $key['status'] . '</td>
  </tr>';
        }
        $output .= '</table>

</body>
</html>';
        echo $output;
    }

    function json_data($status, $error_msg, $data = NULL) {
        if ($data == NULL) {
            $data = array();
        }
        $final = array("status" => $status, "error_msg" => $error_msg, "data" => $data);
        return str_replace("null", '" "', json_encode($final));
    }

    function getJobData() {
        $barcode = trim($this->input->get_post('barcode'));
        if (!empty($barcode)) {
            $job_data = $this->patholab_api_model->get_val("SELECT id,cust_fk,doctor,booking_info FROM job_master WHERE STATUS!='0' AND `barcode`='" . $barcode . "'");
            $emergency_tests = $this->patholab_api_model->master_fun_get_tbl_val("booking_info", array('id' => $job_data[0]["booking_info"]), array("id", "asc"));
            $f_data1 = array();
            if (!empty($emergency_tests[0]["family_member_fk"])) {
                $f_data = $this->patholab_api_model->master_fun_get_tbl_val("customer_family_master", array('id' => $emergency_tests[0]["family_member_fk"]), array("id", "asc"));
                $f_data1 = $this->patholab_api_model->master_fun_get_tbl_val("relation_master", array('id' => $f_data[0]["relation_fk"]), array("id", "asc"));
            }
            $account_holder_data = $this->patholab_api_model->master_fun_get_tbl_val("customer_master", array('id' => $job_data[0]["cust_fk"]), array("id", "asc"));
            $doctor_data = $this->patholab_api_model->master_fun_get_tbl_val("doctor_master", array('id' => $job_data[0]["doctor"]), array("id", "asc"));
            $relation = "Self";
            if (!empty($f_data1)) {
                $patient_name = ucfirst($f_data[0]["name"]);
                $patient_age = ucfirst($f_data[0]["dob"]);
                $patient_gender = ucfirst($f_data[0]["gender"]);
            } else {
                $patient_name = ucfirst($account_holder_data[0]["full_name"]);
                $patient_gender = ucfirst($account_holder_data[0]["gender"]);
                $patient_age = ucfirst($account_holder_data[0]["dob"]);
            }
            $this->load->library("util");
            $util = new Util;
            $data["age"] = $util->get_age($patient_age);
            if (!empty($patient_age)) {
                $p_age = $data["age"][0];
            } else {
                $p_age = "NA";
            }
            $p_gender = "";
            if (strtoupper($patient_gender) == "MALE") {
                $p_gender = "M";
            }
            if (strtoupper($patient_gender) == "FEMALE") {
                $p_gender = "F";
            }
            echo $this->json_data("1", "", array("patient_name" => $patient_name, "patient_age" => $p_age, "patient_gender" => $p_gender, "patient_doctor" => $doctor_data[0]["full_name"]));
        } else {
            echo $this->json_data("0", "Barcode is required.", "");
        }
    }

    function uplodDataNew() {
        $data = stripslashes($_POST['data']);
        $data = str_replace('/', '', $data);
        $json_data = json_decode($data);
        //print_r($json_data);  die();
        //echo $data; die();
        //echo $data; die();
        if (!empty($data)) {

            $json_data = json_decode($data);
            //print_r($json_data); die();
            foreach ($json_data as $key) {

                $Lab_id = $key->lab_id;
                $barcode = $key->barcode;
                $Test_Date_Time = date("Y-m-d H:i:s");
                $Analyte_code = $key->para_code;
                $Result_value = $key->value;
                $range = $key->range;
                $test_code = $key->test_code;

                $insert = array(
                    "lab_id" => $Lab_id,
                    "barcode" => $barcode,
                    "test_date_time" => $Test_Date_Time,
                    "para_code" => $Analyte_code,
                    "para_value" => $Result_value,
                    "para_range" => $range,
                    "test_code" => $test_code
                );
                //print_r($insert); die();
                $app_info = $this->patholab_api_model->master_fun_insert("instument_data_storage_new", $insert);
            }

            echo $this->json_data("1", "", "success");
        } else {
            echo $this->json_data("0", "All fields are required.", "");
        }
    }

    function getNewJobs() {
        $branch_fk = $this->input->get_post("branch_fk");
        if (!empty($branch_fk)) {
            $get_new_job = $job_details = $this->patholab_api_model->get_val("select id from job_master where status!='0' and is_new='N' and branch_fk='" . $branch_fk . "' order by id desc");
            $result = $this->get_job_details($get_new_job);
            if (!empty($result)) {
                echo json_encode(array("status" => "1", "message" => "", "data" => $result));
            } else {
                echo json_encode(array("status" => "0", "message" => "New job not available.", "data" => ""));
            }
        } else {
            echo json_encode(array("status" => "0", "message" => "branch_fk is required", "data" => ""));
        }
    }

    function updateStatus() {
        $branch_fk = $this->input->get_post("job_fk");
        if (!empty($branch_fk) && is_array($branch_fk)) {
            foreach ($branch_fk as $key) {
                $this->patholab_api_model->master_fun_update("job_master", array("id" => $key), array("is_new" => "O"));
            }
            echo json_encode(array("status" => "1", "message" => "", "data" => "success"));
        } else {
            echo json_encode(array("status" => "0", "message" => "invalid job_fk value.", "data" => ""));
        }
    }

    function get_job_details($job_data) {
        $this->load->library("util");
        $util = new Util;
        $final_array = array();
        foreach ($job_data as $key) {
            $job_id = $key["id"];
            $new_array = array();
            $job_details = $this->patholab_api_model->get_val("SELECT j.id,j.branch_fk,c.dob,j.barcode,j.test_city,j.order_id,j.date,c.full_name as patient_name,c.gender FROM job_master j   LEFT JOIN job_test_list_master jtl ON jtl.job_fk=j.`id` LEFT JOIN customer_master c ON c.id=j.`cust_fk` LEFT JOIN test_master t ON t.id=jtl.test_fk LEFT JOIN  book_package_master pb 
    ON pb.job_fk = j.id 
  LEFT JOIN package_master p 
    ON p.id = pb.package_fk
LEFT JOIN doctor_master dm ON dm.id=j.`doctor`	where j.id=$job_id GROUP BY j.id ORDER BY j.id DESC");
            $data["age"] = $util->get_age($data['query'][0]["dob"]);
            if (!empty($job_details)) {
                $book_test = $this->patholab_api_model->master_fun_get_tbl_val("job_test_list_master", array("job_fk" => $job_id), array("id", "desc"));
                $book_package = $this->patholab_api_model->master_fun_get_tbl_val("book_package_master", array("job_fk" => $job_id, "status" => "1"), array("id", "desc"));

                $test_name = array();
                foreach ($book_test as $key) {
                    $test_info = $this->patholab_api_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $job_details[0]["test_city"] . "' AND `test_master`.`id`='" . $key["test_fk"] . "'");
                    if (!empty($test_info[0])) {
                        $test_name[] = $test_info[0];
                    }
                }
                foreach ($book_test as $t_key) {
                    $sub_test_list = $this->patholab_api_model->get_val("SELECT `sub_test_master`.*,`test_master`.`test_name` FROM `sub_test_master` INNER JOIN test_master ON `sub_test_master`.`sub_test`=test_master.`id` WHERE `sub_test_master`.`status`='1' AND `test_master`.`status`='1' AND `sub_test_master`.`test_fk`='" . $t_key["test_fk"] . "'");
                    if (!empty($sub_test_list[0])) {
                        $test_name[] = $sub_test_list[0];
                    }
                }
                $selected_package = $this->patholab_api_model->get_val("SELECT `book_package_master`.*,`package_master`.`title` FROM `book_package_master` INNER JOIN `package_master` ON `book_package_master`.`package_fk`=`package_master`.`id` WHERE `book_package_master`.`status`='1' AND `package_master`.`status`='1' AND `book_package_master`.`job_fk`='" . $job_id . "'");
                foreach ($selected_package as $pkey) {
                    $package_test_list = $this->patholab_api_model->get_val("SELECT 
  `package_test`.*,
  `test_master`.`test_name` 
FROM
  `package_test` 
  INNER JOIN `test_master` 
    ON `package_test`.`test_fk` = `test_master`.`id` 
WHERE `package_test`.`status` = '1' 
  AND `test_master`.`status` = '1' 
  AND `package_test`.`package_fk` = '" . $pkey["package_fk"] . "'");
                    if (!empty($package_test_list)) {
                        foreach ($package_test_list as $p_key) {
                            $test_info = $this->patholab_api_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $job_details[0]["test_city"] . "' AND `test_master`.`id`='" . $p_key["test_fk"] . "'");
                            if (!empty($test_info[0])) {
                                $test_name[] = $test_info[0];
                            }
                        }
                    }
                }
                $job_details[0]["book_test"] = $test_name;
                $package_name = array();
            }
            $final_array[] = $job_details[0];
        }
        return $final_array;
    }

}
