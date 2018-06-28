<?php

class Doctor_api extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->library('email');
        $this->load->model('doctor_app_model');
        $this->load->model('user_master_model');
        $this->load->model('service_model');
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
        $app_info = $this->doctor_app_model->master_fun_insert("user_track", $user_track_data);
        //return true;
    }

//////////vishal code start 09-02-2016////////
    function static_name() {
        echo $this->json_data("1", "", array(array("name" => "This is test", "key" => "1")));
    }

//////////vishal code end 09-02-2016////////
    function gcm() {
        $user_id = $this->input->get_post('user_id');
        $device_id = $this->input->get_post('device_id');
        $device_type = $this->input->get_post('device_type');
        if ($user_id != NULL && $device_id != NULL) {
            $update = $this->doctor_app_model->master_fun_update("customer_master", $user_id, array("device_id" => $device_id, "device_type" => $device_type));
            //$data = $this->doctor_app_model->master_fun_get_tbl_val("customer_master", array("email"=>$email,"password"=>$password,"status" => 1),array("id","asc"));
            echo $this->json_data("1", "", array(array("msg" => "Your Device Id Saved")));
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function doctor_register() {

        $email = $this->input->get_post('email');
        $password = $this->input->get_post('password');
        $name = $this->input->get_post('name');
        $mobile = $this->input->get_post('mobile');

        $address = $this->input->get_post('address');
        if ($name != null && $email != null && $password != null && $mobile != null && $address != null) {

            $row = $this->doctor_app_model->master_num_rows("doctor_master", array("email" => $email, "status" => 1));
            if ($row == 0) {
                $insert = $this->doctor_app_model->master_fun_insert("doctor_master", array("full_name" => $name, "email" => $email, "password" => $password, "mobile" => $mobile, "address" => $address, "status" => 2));
                echo $this->json_data("1", "", array(array("id" => $insert, "msg" => "Register Successfully")));
            } else {
                echo $this->json_data("0", "Email Address Already Registered", "");
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function doctor_register2() {

        $email = $this->input->get_post('email');
        $password = $this->input->get_post('password');
        $name = $this->input->get_post('name');
        $mobile = $this->input->get_post('mobile');
        $sales = $this->input->get_post('sales');

        $address = $this->input->get_post('address');
        if ($name != null && $email != null && $password != null && $mobile != null && $address != null) {

            $row = $this->doctor_app_model->master_num_rows("doctor_master", array("email" => $email, "status" => 1));
            if ($row == 0) {
                $insert = $this->doctor_app_model->master_fun_insert("doctor_master", array("full_name" => $name, "email" => $email, "password" => $password, "mobile" => $mobile, "address" => $address, "status" => 1, "sales_fk" => $sales));
                echo $this->json_data("1", "", array(array("id" => $insert, "msg" => "Register Successfully")));
            } else {
                echo $this->json_data("0", "Email Address Already Registered", "");
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function doctor_login() {
        $email = $this->input->get_post('email');
        $password = $this->input->get_post('password');
        //$device_id = $this->input->get_post('device_id');
        if ($email != NULL && $password != NULL) {
            $row = $this->doctor_app_model->master_num_rows("doctor_master", array("email" => $email, "password" => $password, "status !=" => 0));
            $data = $this->doctor_app_model->master_fun_get_tbl_val("doctor_master", array("email" => $email, "password" => $password, "status" => 1), array("id", "asc"));
            if ($row == 1) {
                $row1 = $this->doctor_app_model->master_num_rows("doctor_master", array("email" => $email, "password" => $password, "status" => 2));
                if ($row1 == 1) {
                    echo $this->json_data("0", "Your Account not Confirm", "");
                } else {
                    $data = $this->doctor_app_model->master_fun_get_tbl_val("doctor_master", array("email" => $email, "password" => $password, "status" => 1), array("id", "asc"));
                    echo $this->json_data("1", "", $data);
                }
            } else {
                //echo "OK";
                $row = $this->doctor_app_model->master_num_rows("doctor_master", array("mobile" => $email, "password" => $password, "status !=" => 0));
                $data = $this->doctor_app_model->master_fun_get_tbl_val("doctor_master", array("mobile" => $email, "password" => $password, "status" => 1), array("id", "asc"));
                if ($row == 1) {
                    //$update = $this->doctor_app_model->master_fun_update("customer_master",$data[0]['id'],array("device_id"=>$device_id));
                    $row1 = $this->doctor_app_model->master_num_rows("doctor_master", array("mobile" => $email, "password" => $password, "status" => 2));
                    if ($row1 == 1) {
                        echo $this->json_data("0", "Your Account not Confirm", "");
                    } else {
                        $data = $this->doctor_app_model->master_fun_get_tbl_val("doctor_master", array("mobile" => $email, "password" => $password, "status" => 1), array("id", "asc"));
                        echo $this->json_data("1", "", $data);
                    }
                } else {
                    echo $this->json_data("0", "Email Or Password Not match", "");
                }
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function doctor_gcm() {
        $user_id = $this->input->get_post('user_id');
        $device_id = $this->input->get_post('device_id');
        $device_type = $this->input->get_post('device_type');
        if ($user_id != NULL && $device_id != NULL) {

            $update = $this->doctor_app_model->master_fun_update("doctor_master", $user_id, array("device_id" => $device_id, "device_type" => $device_type));
            //$data = $this->doctor_app_model->master_fun_get_tbl_val("customer_master", array("email"=>$email,"password"=>$password,"status" => 1),array("id","asc"));
            echo $this->json_data("1", "", array(array("msg" => "Your Device Id Saved")));
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function doctor_logout() {
        $user_id = $this->input->get_post('user_id');
        //$device_id = $this->input->get_post('device_id');
        if ($user_id != NULL) {
            $update = $this->doctor_app_model->master_fun_update("doctor_master", $user_id, array("device_id" => "", "device_type" => ""));
            //$data = $this->doctor_app_model->master_fun_get_tbl_val("customer_master", array("email"=>$email,"password"=>$password,"status" => 1),array("id","asc"));
            echo $this->json_data("1", "", array(array("msg" => "Logout Successfully")));
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function doctor_forget_password() {
        $this->load->library('email');
        $this->load->helper("Email");
        $email_cnt = new Email;

        $email = $this->input->get_post('email');
        if ($email != NULL) {
            $row = $this->doctor_app_model->master_fun_get_tbl_val("doctor_master", array("email" => $email, "status" => 1, "active" => 1), array("id", "asc"));
            $password = $row[0]['password'];

            $config['mailtype'] = 'html';
            $this->email->initialize($config);
            // $message = "You recently requested to reset your password for your  Patholab Account.<br/><br/>";
            // $message .= "Your Password id <strong>" . $password . "</strong><br/><br/> ";
            //  $message .= "If you did not request a password reset, please ignore this email or reply to let us know.<br/><br/>";
            $message = '<div style="padding:0 4%;">
                    <h4><b>Forgot Password</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;">You recently requested to reset your password for your AirmedLabs Account. Your password is ' . $password . ' </p>
                        <p style="color:#7e7e7e;font-size:13px;">If you did not request , please ignore this email or reply to let us know.</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
            $message = $email_cnt->get_design($message);
            $this->email->to($email);
            $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
            $this->email->subject('AirmedLabs Reset your forgotten Password');
            $this->email->message($message);
            $this->email->send();
            echo $this->json_data("1", "", array(array("msg" => "Password has been Sent on Your Email")));
        } else {

            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function doctor_view_profile() {
        $userid = $this->input->get_post('user_id');
        if (!empty($userid)) {
            $data = $this->doctor_app_model->doctor_view_profile($userid);
            if (empty($data)) {
                echo $this->json_data("0", "no data available", "");
            } else {
                echo $this->json_data("1", "", $data);
            }
        } else {
            echo $this->json_data("0", "All parameter are required.", "");
        }
    }

    function last_7_days_doctor_customer_job() {
        $user_fk = $this->input->get_post('user_id');
        if ($user_fk != null) {
            $data = $this->doctor_app_model->last_7_doctor_customer_job($user_fk);
            if (empty($data)) {
                echo $this->json_data("0", "no data available", "");
            } else {

                echo $this->json_data("1", "", $data);
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function doctor_edit_profile() {
        $user_fk = $this->input->get_post('user_id');
        $name = $this->input->get_post('name');
        $email = $this->input->get_post('email');
        $mobile = $this->input->get_post('mobile');
        $address = $this->input->get_post('address');
        $state = $this->input->get_post('state');
        $city = $this->input->get_post('city');
        $pic = $this->input->get_post('pic');
        $gender = $this->input->get_post('gender');

        if ($user_fk != "" && $name != "" && $email != "") {
            $data = array(
                "full_name" => $name,
                "email" => $email,
                "mobile" => $mobile,
                "address" => $address,
                "state" => $state,
                "city" => $city,
                "pic" => $pic,
                "gender" => $gender,
            );


            $row = $this->doctor_app_model->master_fun_get_tbl_val("doctor_master", array("id" => $user_fk, "status" => 1), array("id", "asc"));
            $save_email = $row[0]['email'];
            if ($save_email != $email) {

                $row = $this->doctor_app_model->master_num_rows("doctor_master", array("email" => $email, "status" => 1));
                if ($row == 0) {
                    $update = $this->doctor_app_model->master_fun_update("doctor_master", $user_fk, $data);
                } else {

                    echo $this->json_data("0", "Email Already Exist", "");
                }
            } else {

                $update = $this->doctor_app_model->master_fun_update("doctor_master", $user_fk, $data);
            }

            if ($update) {
                echo $this->json_data("1", "", array(array("msg" => "Your Profile has been Updated")));
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function doctor_change_password() {
        $this->load->helper("Email");
        $email_cnt = new Email;

        $user_fk = $this->input->get_post('user_id');
        $oldpassword = $this->input->get_post('oldpassword');
        $password = $this->input->get_post('password');
        if ($user_fk != "" && $password != "") {
            $row = $this->doctor_app_model->master_fun_get_tbl_val("doctor_master", array("id" => $user_fk, "status" => 1), array("id", "asc"));
            $oldpass = $row[0]['password'];
            if ($oldpassword == $oldpass) {
                $data = array(
                    "password" => $password,
                );
                $update = $this->doctor_app_model->master_fun_update("doctor_master", $user_fk, $data);
                if ($update) {
                    $config['mailtype'] = 'html';
                    $this->email->initialize($config);
                    $message = '<div style="padding:0 4%;">
                    <h4><b>Password Change AirmedLabs</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;font-weight: bold;"><b>Dear </b> ' . ucfirst($row[0]['full_name']) . '</p>
                        <p style="color:#7e7e7e;font-size:13px;">Your Password Successfully Changed. </p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
                    $message = $email_cnt->get_design($message);
                    $this->email->to($row[0]['email']);
//$this->email->to("jeel@virtualheight.com");
                    $this->email->from("donotreply@airmedpathlabs.com", 'AirmedLabs');
                    $this->email->subject("Password changed for AirmedLabs");
                    $this->email->message($message);
                    $this->email->send();
                    echo $this->json_data("1", "", array(array("msg" => "Your Password has been Updated")));
                }
            } else {
                echo $this->json_data("0", "old password not match", "");
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function forget_password() {
        $this->load->helper("Email");
        $email_cnt = new Email;

        $this->load->library('email');
        $email = $this->input->get_post('email');
        if ($email != NULL) {
            $row = $this->doctor_app_model->master_fun_get_tbl_val("customer_master", array("email" => $email, "status" => 1, "active" => 1), array("id", "asc"));
            $password = $row[0]['password'];

            $config['mailtype'] = 'html';
            $this->email->initialize($config);
            // $message = "You recently requested to reset your password for your  Patholab Account.<br/><br/>";
            // $message .= "Your Password id <strong>" . $password . "</strong><br/><br/> ";
            //  $message .= "If you did not request a password reset, please ignore this email or reply to let us know.<br/><br/>";
            $message = '<div style="padding:0 4%;">
                    <h4><b>Forgot Password</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;">You recently requested to reset your password for your AirmedLabs Account. Your password is ' . $password . ' </p>
                        <p style="color:#7e7e7e;font-size:13px;">If you did not request , please ignore this email or reply to let us know.</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
            $message = $email_cnt->get_design($message);
            $this->email->to($email);
            $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
            $this->email->subject('AirmedLabs Reset your forgotten Password');
            $this->email->message($message);
            $this->email->send();
            echo $this->json_data("1", "", array(array("msg" => "Password has been Sent on Your Email")));
        } else {

            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function today_doctor_customer_job() {
        $user_fk = $this->input->get_post('user_id');
        if ($user_fk != null) {
            $data = $this->doctor_app_model->today_doctor_customer_job($user_fk);
            if (empty($data)) {
                echo $this->json_data("0", "no data available", "");
            } else {

                echo $this->json_data("1", "", $data);
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function doctor_customer_job() {
        $user_fk = $this->input->get_post('user_id');
        $from = $this->input->get_post('from');
        $to = $this->input->get_post('to');
        $type = $this->input->get_post('type');
        if ($user_fk != null) {
            /*
              if(isset($from) and $from!="") {
              }else{
              $data = $this->doctor_app_model->doctor_customer_job($user_fk);
              //$data = $this->doctor_app_model->doctor_customer_job2($user_fk,$from,$to,$type);
              }
             */
            $data = $this->doctor_app_model->doctor_customer_job2($user_fk, $from, $to, $type);
            if (empty($data)) {
                echo $this->json_data("0", "no data available", "");
            } else {

                echo $this->json_data("1", "", $data);
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function doctor_dashboard() {
        $user_fk = $this->input->get_post('user_id');
        if ($user_fk != null) {
            $all = $this->doctor_app_model->doctor_customer_job($user_fk);
            $countall = count($all);
            $all_sum = 0;
            foreach ($all as $key) {
                $all_sum += $key['price'];
            }

            $last_7days = $this->doctor_app_model->last_7_doctor_customer_job($user_fk);
            $countlast7 = count($last_7days);
            $days7_sum = 0;
            foreach ($last_7days as $key) {
                $days7_sum += $key['price'];
            }
            $todays = $this->doctor_app_model->today_doctor_customer_job($user_fk);
            $counttoday = count($todays);
            $today_sum = 0;
            foreach ($todays as $key) {
                $today_sum += $key['price'];
            }

            echo $this->json_data("1", "", array(array("total" => (int) $countall, "last7days" => (int) $countlast7, "today" => (int) $counttoday, "all_price" => $all_sum, "7days_price" => $days7_sum, "today_price" => $today_sum)));
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function doctor_view_report2() {
        $jid = $this->input->get_post('job_id');
        if ($jid != null) {
            $data = $this->doctor_app_model->doctor_view_report($jid);
            if (empty($data)) {
                echo $this->json_data("0", "no data available", "");
            } else {
                echo $this->json_data("1", "", $data);
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function doctor_view_report() {
        $jid = $this->input->get_post('job_id');
        if ($jid != null) {
            $data = $this->service_model->master_fun_get_tbl_val("job_master", array("id" => $jid), array("id", "asc"));
            //print_R($data); die();
            $tests = $this->service_model->master_fun_get_tbl_val("job_test_list_master", array("job_fk" => $jid), array("id", "asc"));
            $packages = $this->service_model->master_fun_get_tbl_val("book_package_master", array("job_fk" => $jid, "package_fk != " => "0"), array("id", "asc"));
            $data_new = array();
            $cnt = 0;

            foreach ($tests as $key) {

                /* $tst_details = $this->service_model->get_result("SELECT 
                  `test_master`.*,
                  `test_master_city_price`.`price` AS price1,
                  `test_master_city_price`.`test_fk`
                  FROM
                  `test_master`
                  INNER JOIN `test_master_city_price`
                  ON `test_master`.`id` = `test_master_city_price`.`test_fk`
                  WHERE `test_master_city_price`.`status` = '1'
                  AND `test_master`.`id` = '" . $key["test_fk"] . "' AND `test_master_city_price`.`city_fk`='" . $data[0]["test_city"] . "'"); */
                $tst_details = $this->service_model->get_result("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND test_master.id='" . $key["test_fk"] . "'");
                $report_data = $this->service_model->get_result("SELECT * from report_master where job_fk='" . $jid . "' and test_fk='" . $key["test_fk"] . "'");
                $report = '';
                $original = '';
                $description = "";
                if (!empty($report_data)) {
                    $report = $report_data[0]["report"];
                    $original = $report_data[0]["original"];
                    $description = $report_data[0]["description"];
                }
                $common_report_data = $this->service_model->get_result("SELECT * from report_master where job_fk='" . $jid . "' and test_fk='0' AND type='c'");

                if (!empty($common_report_data)) {
                    $report = $common_report_data[0]["report"];
                    $original = $common_report_data[0]["original"];
                    $description = $common_report_data[0]["description"];
                }

                $data_new[$cnt]["job_fk"] = $jid;
                $data_new[$cnt]["test_fk"] = $key["id"];
                if ($report) {
                    $data_new[$cnt]["report"] = $report;
                } else {
                    $data_new[$cnt]["report"] = " ";
                }
                $data_new[$cnt]["original"] = $original;
                $data_new[$cnt]["description"] = $description;
                $data_new[$cnt]["created_date"] = $data[0]["date"];
                $data_new[$cnt]["updated_date"] = "";
                $data_new[$cnt]["test_name"] = $tst_details[0]["test_name"];
                $data_new[$cnt]["price"] = $tst_details[0]["price"];
                $data_new[$cnt]["status"] = $data[0]["status"];
                $data_new[$cnt]["order_id"] = $data[0]["order_id"];
                $data_new[$cnt]["id"] = $key["id"];
                $cnt++;
            }

            foreach ($packages as $key1) {

                $tst_details = $this->service_model->get_result("SELECT 
    `package_master`.*,
    `package_master_city_price`.`a_price`,
    `package_master_city_price`.`d_price` 
  FROM
    `package_master` 
    INNER JOIN `package_master_city_price` 
      ON `package_master`.`id` = `package_master_city_price`.`package_fk` 
  WHERE `package_master`.`status` = '1' 
    AND `package_master_city_price`.`status` = '1' 
    AND `package_master`.`id` = '" . $key1["package_fk"] . "' 
    AND `package_master_city_price`.`city_fk` = '" . $data[0]["test_city"] . "'");
                $report_data = $this->service_model->get_result("SELECT * from report_master where job_fk='" . $jid . "' and test_fk='" . $key["test_fk"] . "'");
                $report = '';
                $original = '';
                if (!empty($report_data)) {
                    $report = $report_data[0]["report"];
                    $original = $report_data[0]["original"];
                }
                $description = $tst_details[0]["description"];
                $common_report_data = $this->service_model->get_result("SELECT * from report_master where job_fk='" . $jid . "' and test_fk='0' AND type='c'");

                if (!empty($common_report_data)) {
                    $report = $common_report_data[0]["report"];
                    $original = $common_report_data[0]["original"];
                    $description = $common_report_data[0]["description"];
                }
                //$tst_details;
                $data_new[$cnt]["job_fk"] = $jid;
                $data_new[$cnt]["test_fk"] = $key1["id"];
                if ($_GET['debug'] == '1') {
                    echo "here1";
                    die();
                }
                if ($report) {
                    $data_new[$cnt]["report"] = $report;
                } else {
                    $data_new[$cnt]["report"] = " ";
                }
                $data_new[$cnt]["original"] = "";
                $data_new[$cnt]["description"] = $description;
                $data_new[$cnt]["created_date"] = $data[0]["date"];
                $data_new[$cnt]["updated_date"] = "";
                $data_new[$cnt]["test_name"] = $tst_details[0]["title"];
                $data_new[$cnt]["price"] = $tst_details[0]["d_price"];
                $data_new[$cnt]["status"] = $data[0]["status"];
                $data_new[$cnt]["order_id"] = $data[0]["order_id"];
                $data_new[$cnt]["id"] = $key1["id"];
                $cnt++;
            }
            $data = $data_new;
            if (empty($data)) {
                echo $this->json_data("0", "no data available", "");
            } else {
                echo $this->json_data("1", "", $data);
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function doctor_date_vise_report() {
        $user_fk = $this->input->get_post('user_id');
        $from = $this->input->get_post('from');
        $from = explode("-",$from);
        $from = $from[2]."-".$from[1]."-".$from[0];
        $to = $this->input->get_post('to');
        $to = explode("-",$to);
        $to = $to[2]."-".$to[1]."-".$to[0];
        if ($user_fk != null && $from != null && $to != null) {
            $data = $this->doctor_app_model->doctor_date_vise_report1($user_fk, $from, $to);
            if (empty($data)) {
                echo $this->json_data("0", "no data available", "");
            } else {

                echo $this->json_data("1", "", $data);
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function doctor_stat() {
        $user_fk = $this->input->get_post('user_id');
        $month = $this->input->get_post('month');
        $year = $this->input->get_post('year');
        if ($user_fk != null) {
            $data = $this->doctor_app_model->doctor_stat($user_fk, $month, $year);
            if (empty($data)) {
                echo $this->json_data("0", "no data available", "");
            } else {
                echo $this->json_data("1", "", $data);
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function doctor_year_month_count() {
        $user_fk = $this->input->get_post('user_id');

        if ($user_fk != null) {
            $data = $this->doctor_app_model->doctor_get_year_month_count($user_fk);
            if (empty($data)) {
                echo $this->json_data("0", "no data available", "");
            } else {
                echo $this->json_data("1", "", $data);
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function get_country() {
        $data = $this->doctor_app_model->master_fun_get_tbl_val("country", array("status" => 1), array("id", "asc"));
        if (empty($data)) {
            echo $this->json_data("0", "no data available", "");
        } else {
            echo $this->json_data("1", "", $data);
        }
    }

    function get_state() {
        //$cid = $this->input->get_post('country_id');
        //if($cid !=null){
        $data = $this->doctor_app_model->master_fun_get_tbl_val("state", array("status" => 1), array("id", "asc"));
        if (empty($data)) {
            echo $this->json_data("0", "no data available", "");
        } else {
            echo $this->json_data("1", "", $data);
        }
    }

    function get_city() {
        $cid = $this->input->get_post('state_id');
        if ($cid != null) {
            $data = $this->doctor_app_model->master_fun_get_tbl_val("city", array("state_fk" => $cid, "status" => 1), array("id", "asc"));
            if (empty($data)) {
                echo $this->json_data("0", "no data available", "");
            } else {
                echo $this->json_data("1", "", $data);
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function search_test() {
        $test = trim($this->input->get_post('testname'));
        $city = trim($this->input->get_post("city"));
        if ($city == 333 || $city == '') {
            if ($test == '') {
                $data = $this->doctor_app_model->master_fun_get_tbl_val("test_master", array("status" => 1), array("test_name", "asc"));
            } else {
                $qry1 = "SELECT * FROM test_master WHERE STATUS='1' and test_name LIKE '%" . $test . "%' order by test_name asc";
                $data = $this->doctor_app_model->get_result($qry1);
            }
            if (empty($data)) {
                echo $this->json_data("0", "no data available", "");
            } else {
                echo $this->json_data("1", "", $data);
            }
        } else {
            if ($test == '') {
                $qry1 = "SELECT * FROM test_master WHERE STATUS='1' order by test_name asc";
            } else {
                $qry1 = "SELECT * FROM test_master WHERE STATUS='1' AND test_name LIKE '%" . $test . "%' order by test_name asc";
            }
            $data1 = $this->doctor_app_model->get_result($qry1);
            $cnt = 0;
            foreach ($data1 as $key) {
                $data2 = $this->doctor_app_model->get_result("select * from test_master_city_price where test_fk='" . $key["id"] . "' and city_fk='" . $city . "'");
                if (!empty($data2)) {
                    $data1[$cnt]["price"] = $data2[0]["price"];
                } else {
                    $data1[$cnt]["price"] = "0";
                }
                $cnt++;
            }
            echo $this->json_data("1", "", $data1);
        }
    }

    function dashboard_12() {
        $user_id = $this->input->get_post('user_id');
        $data = array();
        $dash = $this->doctor_app_model->get_yesterday_dashbaord($user_id)[0];
        //print_R($dash );
        $dash["pending"] = $dash["total"] - $dash["critical"] - $dash["semicritical"] - $dash["normal"];
        $data = array(array("graph" => array(array("name" => "All", "per" => "100", "value" => $dash['total'], "color" => "#e85629"), array("name" => "Critical", "per" => "" . ($dash['critical'] / $dash['total']) * 100, "value" => $dash['critical'], "color" => "#f42e48"), array("name" => "Semi-Critical", "per" => "" . ($dash['semicritical'] / $dash['total']) * 100, "value" => $dash['semicritical'], "color" => "#FFD600"), array("name" => "Normal", "per" => "" . ($dash['normal'] / $dash['total']) * 100, "value" => $dash['normal'], "color" => "#33691E"), array("name" => "Pending", "per" => "" . ($dash['pending'] / $dash['total']) * 100, "value" => "" . $dash['pending'], "color" => "#2e4de8")), "total_payment" => $dash['total'], "total_payment_collection" => "₹" . $dash['total_amount']));
        echo $this->json_data("1", "", $data);
    }

    function dashboard_1() {
        $user_id = $this->input->get_post('user_id');
        $data = array();
        $dash = $this->doctor_app_model->get_today_dashbaord($user_id)[0];
        //print_R($dash );
        $dash["pending"] = $dash["total"] - $dash["critical"] - $dash["semicritical"] - $dash["normal"];
        $data = array(array("graph" => array(array("name" => "All", "per" => "100", "value" => $dash['total'], "color" => "#e85629"), array("name" => "Critical", "per" => "" . intval(($dash['critical'] / $dash['total']) * 100), "value" => $dash['critical'], "color" => "#f42e48"), array("name" => "Semi-Critical", "per" => "" . intval(($dash['semicritical'] / $dash['total']) * 100), "value" => $dash['semicritical'], "color" => "#FFD600"), array("name" => "Normal", "per" => "" . intval(($dash['normal'] / $dash['total']) * 100), "value" => $dash['normal'], "color" => "#33691E"), array("name" => "Pending", "per" => "" . intval(($dash['pending'] / $dash['total']) * 100), "value" => "" . $dash['pending'], "color" => "#2e4de8")), "total_payment" => $dash['total'], "total_payment_collection" => "₹" . $dash['total_amount']));
        echo $this->json_data("1", "", $data);
    }

    function dashboard_2() {
        $data = array();
        $user_id = $this->input->get_post('user_id');
        $dash = $this->doctor_app_model->get_yesterday_dashbaord($user_id)[0];
        $dash["pending"] = $dash["total"] - $dash["critical"] - $dash["semicritical"] - $dash["normal"];
        $data = array(array("graph" => array(array("name" => "All", "per" => "100", "value" => $dash['total'], "color" => "#e85629"), array("name" => "Critical", "per" => "" . intval(($dash['critical'] / $dash['total']) * 100), "value" => $dash['critical'], "color" => "#f42e48"), array("name" => "Semi-Critical", "per" => "" . intval(($dash['semicritical'] / $dash['total']) * 100), "value" => $dash['semicritical'], "color" => "#FFD600"), array("name" => "Normal", "per" => "" . intval(($dash['normal'] / $dash['total']) * 100), "value" => $dash['normal'], "color" => "#33691E"), array("name" => "Pending", "per" => "" . intval(($dash['pending'] / $dash['total']) * 100), "value" => "" . $dash['pending'], "color" => "#2e4de8")), "total_payment" => $dash['total'], "total_payment_collection" => "₹" . $dash['total_amount']));
        echo $this->json_data("1", "", $data);
    }

    function report_filtration() {
        $data = array();
        $data = array(array("graph" => array(array("per" => "20", "value" => "100", "color" => "#000000"), array("per" => "20", "value" => "100", "color" => "#000000"), array("per" => "20", "value" => "100", "color" => "#000000"), array("per" => "20", "value" => "100", "color" => "#000000"), array("per" => "20", "value" => "100", "color" => "#000000")), "total_payment" => "5000", "total_payment_collection" => "Rs.7000"));
        echo $this->json_data("1", "", $data);
    }

    function json_data($status, $error_msg, $data = NULL) {
        if ($data == NULL) {
            $data = array();
        }
        $final = array("status" => $status, "error_msg" => $error_msg, "data" => $data);
        return str_replace("null", '" "', json_encode($final));
    }

    function request_discount() {
        $did = $this->input->get_post("did");
        $patient_name = $this->input->get_post("patient_name");
        $mobile = $this->input->get_post("mobile");
        $description = $this->input->get_post("description");
        if (!empty($did) && !empty($patient_name) && !empty($mobile) && !empty($description)) {
            $data_array = array(
                "doctor_fk" => $did,
                "patient_name" => $patient_name,
                "mobile" => $mobile,
                "desc" => $description,
                "createddate" => date("Y-m-d H:i:s"),
                "status" => "1"
            );
            $this->doctor_app_model->master_fun_insert("doctor_req_discount", $data_array);
            echo $this->json_data("1", "", array());
        } else {
            echo $this->json_data("0", "All fields are required", "");
        }
    }

    public function upload_pic() {
        $files = $_FILES;
        $data = array();
        $this->load->library('upload');
        $config['allowed_types'] = 'png|jpg|gif|jpeg';
        if (isset($files['userfile']) && $files['userfile']['name'] != "") {
            $config['upload_path'] = './upload/';
            $config['file_name'] = $files['userfile']['name'];
            $this->upload->initialize($config);
            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0755, TRUE);
            }
            if (!$this->upload->do_upload('userfile')) {
                $error = $this->upload->display_errors();
                $error = str_replace("<p>", "", $error);
                $error = str_replace("</p>", "", $error);
                echo $this->json_data("0", $error, "");
            } else {
                $doc_data = $this->upload->data();
                $filename = $doc_data['file_name'];
                $uploads = array('upload_data' => $this->upload->data("identity"));
                echo $this->json_data("1", "", array(array("filename" => $filename)));
            }
        } else {
            echo $this->json_data("0", "You did not select a file to upload", "");
        }
    }

    /* function sales_info() {
      if (!is_loggedin()) {
      redirect('login');
      }
      $start_date = $this->input->get('start_date');
      $end_date = $this->input->get('end_date');
      $statusid = $this->input->get('statusid');

      $data["login_data"] = logindata();
      $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

      if ($start_date != "" || $end_date != "" || $statusid != "") {
      $data['start_date'] = $start_date;
      $data['end_date'] = $end_date;
      $data['statusid'] = $statusid;
      if ($statusid == '0') {
      $data["deleted_selected"] = 1;
      }
      $data['query'] = $this->job_model->job_report_data($start_date, $end_date, $statusid);
      } else {
      $data['query'] = array();
      }
      $data['customer'] = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1), array("full_name", "asc"));
      $data['city'] = $this->job_model->master_fun_get_tbl_val("city", array('status' => 1), array("id", "asc"));
      $cnt = 0;
      foreach ($data['query'] as $key) {
      $w_prc = 0;
      $booked_tests = $this->job_model->master_fun_get_tbl_val("wallet_master", array('job_fk' => $key["id"]), array("id", "asc"));
      $emergency_tests = $this->job_model->master_fun_get_tbl_val("booking_info", array('id' => $key["booking_info"]), array("id", "asc"));
      $f_data = $this->job_model->master_fun_get_tbl_val("customer_family_master", array('id' => $emergency_tests[0]["family_member_fk"]), array("id", "asc"));
      $f_data1 = $this->job_model->master_fun_get_tbl_val("relation_master", array('id' => $f_data[0]["relation_fk"]), array("id", "asc"));
      $relation = "Self";
      if (!empty($f_data1)) {
      $relation = ucfirst($f_data1[0]["name"] . " (" . $f_data[0]["name"] . ")");
      }
      $data['query'][$cnt]["relation"] = $relation;
      foreach ($booked_tests as $tkey) {
      if ($tkey["debit"]) {
      $w_prc = $w_prc + $tkey["debit"];
      }
      }
      $data['query'][$cnt]["emergency"] = $emergency_tests[0]["emergency"];
      $data['query'][$cnt]["cut_from_wallet"] = $w_prc;
      $package_ids = $this->job_model->get_job_booking_package($key["id"]);
      if (trim($package_ids) != '') {
      $data['query'][$cnt]["packagename"] = $package_ids;
      }
      $cnt++;
      }

      } */

    function check_doctor_account() {
        $mobile = $this->input->get_post("mobile");
        $row = $this->doctor_app_model->master_num_rows("doctor_master", array("mobile" => $mobile, "status" => 1));
        if ($row == 0) {
            //$insert = $this->doctor_app_model->master_fun_insert("doctor_master", array("full_name" => $name, "email" => $email, "password" => $password, "mobile" => $mobile, "address" => $address, "status" => 2));
            // echo $this->json_data("0", "New Account", array());
            $insert = $this->doctor_app_model->master_fun_insert("doctor_master", array("mobile" => $mobile, "status" => 2));
            /* Nishit send sms code start */
            if ($mobile == '9876543210') {
                $OTP = 12345;
            } else {
                $OTP = rand(11111, 99999);
            }
            $this->doctor_app_model->master_fun_update1("doctor_master", array("id" => $insert), array('otp' => $OTP));
            $sms_message = $this->doctor_app_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "doctor_login_otp"), array("id", "asc"));
            $sms_message = preg_replace("/{{NAME}}/", ucfirst($dr_data[0]["full_name"]), $sms_message[0]["message"]);
            $sms_message = preg_replace("/{{OTP}}/", $OTP, $sms_message);
            //$sms_message = preg_replace("/{{PASSWORD}}/", $password, $sms_message);
            $this->load->helper("sms");
            $notification = new Sms();
            $notification::send($mobile, $sms_message);
            $dr_data = $this->doctor_app_model->master_fun_get_tbl_val("doctor_master", array("id" => $insert), array("id", "asc"));
            /* Nishit send sms code end */
            echo $this->json_data("2", "OTP", $dr_data);
        } else {
            $dr_data = $this->doctor_app_model->master_fun_get_tbl_val("doctor_master", array("mobile" => $mobile, "status" => 1), array("id", "asc"));
            /* if (!empty($dr_data[0]["password"])) {
              $dr_data[0]["password"] = "";
              echo $this->json_data("1", "old", $dr_data);
              } else { */
            /* Nishit send sms code start */
            if ($mobile == '9876543210') {
                $OTP = 12345;
            } else {
                $OTP = rand(11111, 99999);
            }
            $this->doctor_app_model->master_fun_update1("doctor_master", array("id" => $dr_data[0]["id"], "status" => 1), array('otp' => $OTP));
            $sms_message = $this->doctor_app_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "doctor_login_otp"), array("id", "asc"));
            $sms_message = preg_replace("/{{NAME}}/", ucfirst($dr_data[0]["full_name"]), $sms_message[0]["message"]);
            $sms_message = preg_replace("/{{OTP}}/", $OTP, $sms_message);
            //$sms_message = preg_replace("/{{PASSWORD}}/", $password, $sms_message);
            $this->load->helper("sms");
            $notification = new Sms();
            $notification::send($mobile, $sms_message);
            $dr_data = $this->doctor_app_model->master_fun_get_tbl_val("doctor_master", array("mobile" => $mobile, "status" => 1), array("id", "asc"));
            /* Nishit send sms code end */
            echo $this->json_data("2", "OTP", $dr_data);
            //}
        }
    }

    function check_doctor_otp() {
        $id = $this->input->get_post('id');
        $otp = $this->input->get_post('otp');
        if ($id != NULL && $otp != NULL) {
            $row = $this->doctor_app_model->master_num_rows("doctor_master", array("id" => $id, "otp" => $otp));
            $data = $this->doctor_app_model->master_fun_get_tbl_val("doctor_master", array("id" => $id), array("id", "asc"));
            if ($row == 1) {
                $update = $this->doctor_app_model->master_fun_update("doctor_master", $id, array("otp" => ''));
                $password = rand(111111, 999999);
                $this->doctor_app_model->master_fun_update1("doctor_master", array("id" => $id), array("password" => $password));
                /* Nishit send sms code start */
                $sms_message = $this->doctor_app_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "doctor_welcome"), array("id", "asc"));
                $sms_message = preg_replace("/{{NAME}}/", ucfirst($data[0]["full_name"]), $sms_message[0]["message"]);
                $sms_message = preg_replace("/{{PASSWORD}}/", $password, $sms_message);
                $this->load->helper("sms");
                $notification = new Sms();
                $notification::send($data[0]["mobile"], $sms_message);
                /* Nishit send sms code end */
                $data[0]["password"] = "";
                echo $this->json_data("1", "", $data);
            } else {
                echo $this->json_data("0", "Invalid OTP.", "");
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function doctor_pro_request() {

        $name = $this->input->get_post('name');
        $mobile = $this->input->get_post('mobile');
        $city = $this->input->get_post('city');

        if ($name != null && $mobile != null && $city != null) {

            $insert = $this->doctor_app_model->master_fun_insert("doctor_pro_request", array("name" => $name, "mobile" => $mobile, "city" => $city, "created_date" => date("Y-m-d H:i:s")));
            /* Nishit mail start */
            $get_email = $this->doctor_app_model->master_fun_get_tbl_val("doctor_pro_request_email", array('email !=' => null), array("id", "asc"));
            $this->load->helper("Email");
            $email_cnt = new Email;
            $config['mailtype'] = 'html';
            $this->email->initialize($config);
            $message = '<div style="padding:0 4%;">
                    <h4><b>You have one more doctor PRO request.</b></h4>
                        <p><b>Name </b>:-  ' . ucfirst($name) . '</p>
                            <p><b>Mobile </b>:-  ' . ucfirst($mobile) . '</p>
                                <p><b>City </b>:-  ' . ucfirst($city) . '</p>
                                    <p><b>Date </b>:-  ' . date("jS F Y g:ia") . '</p>
                </div>';
            $message = $email_cnt->get_design($message);
            $this->email->to($get_email[0]["email"]);
            $this->email->from("donotreply@airmedpathlabs.com", "AirmedLabs");
            $this->email->subject("Doctor PRO Request");
            $this->email->message($message);
            $this->email->send();
            /* Nishit mail end */
            echo $this->json_data("1", "", array(array("msg" => "Register Successfully")));
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

}
