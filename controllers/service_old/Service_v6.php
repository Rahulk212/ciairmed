<?php

class Service_v6 extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->library('email');
        $this->load->model('service_model');
        $this->load->model('user_master_model');
        $this->load->library('Firebase_notification');
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
        if ($actual_link != "http://www.airmedlabs.com/index.php/api/send") {
            $user_track_data = array("url" => $actual_link, "user_details" => $user_info, "data" => $user_post_data, "createddate" => date("Y-m-d H:i:s"), "type" => "service");
        }
        $app_info = $this->service_model->master_fun_insert("user_track", $user_track_data);
        //return true;
    }

    function check_refer_code() {
        $usercode = $this->input->get_post('refer_code');
        if ($usercode != "") {
            $row = $this->service_model->master_num_rows("refer_code_master", array("refer_code" => $usercode, "status" => 1));
            if ($row == 1) {
                echo $this->json_data("1", "", array(array("msg" => "Your Code has been Matched")));
            } else {
                echo $this->json_data("0", "Invalid Refere Code", "");
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    public function register() {
        $this->load->helper("Email");
        $email_cnt = new Email;
        $this->load->library('email');
        $email = $this->input->get_post('email');
        $name = $this->input->get_post('name');
        $password = $this->input->get_post('password');
        $mobile = $this->input->get_post('mobile');
        $gender = $this->input->get_post('gender');
        $usedcode = $this->input->get_post('refer_code');
        $test_city = $this->input->get_post('test_city');
        $dob = $this->input->get_post('dob');
        $tst = explode("/", $dob);
        $dob = $tst[2] . "-" . $tst[1] . "-" . $tst[0];
        if (empty($test_city)) {
            $test_city = 1;
        }
        $code = random_string('alnum', 6);
        $confirm_code = random_string('alnum', 6);
        $OTP = rand(11111, 99999);
        //$OTP = 12345;
        //$this->service_model->master_fun_insert("test",array("test"=>"test1"));
        if ($mobile != NULL && $email != null && $password != null && $gender != null && $test_city != null) {
            $row = $this->service_model->master_num_rows("customer_master", array("email" => $email, "status" => 1));
            if ($row == 0) {
                $number_check = $this->service_model->master_num_rows("customer_master", array("mobile" => $mobile, "status" => 1));
                if ($number_check == 0) {
                    $insert = $this->service_model->master_fun_insert("customer_master", array("full_name" => $name, "gender" => $gender, "email" => $email, "password" => $password, "active" => 0, "dob" => $dob, "confirm_code" => $confirm_code, "otp" => $OTP, "test_city" => $test_city, "mobile" => $mobile, "status" => "0"));
                    $insert_code = $this->service_model->master_fun_insert("refer_code_master", array("cust_fk" => $insert, "refer_code" => $code, "used_code" => $usedcode));
                    $this->service_model->master_fun_insert("user_change_phone", array("user_fk" => $insert, "mobile" => $mobile));
                    // code for refer price
                    $data = $this->service_model->master_fun_get_tbl_val("refer_code_master", array("refer_code" => $usedcode), array("id", "asc"));
                    if (!empty($data)) {
                        $custfk = $data[0]['cust_fk'];
                        $data1 = $this->service_model->master_fun_get_tbl_val("customer_master", array("id" => $insert), array("id", "asc"));
                        $refemail = $data1[0]['email'];
                        $ref_name = $data1[0]['full_name'];
                        //$query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $custfk), array("id", "desc"));
                        //$total = $query[0]['total'];
                        $data = array(
                            "cust_fk" => $insert,
                            "credit" => 100,
                            "total" => 100,
                            "type" => "referral code",
                            "created_time" => date('Y-m-d H:i:s')
                        );
                        $insert1 = $this->service_model->master_fun_insert("wallet_master", $data);
                        $config['mailtype'] = 'html';
                        $this->email->initialize($config);
                        $message = '<div style="padding:0 4%;">
                    <h4><b>You have one more refferal</b></h4>
                        <p><b>Dear </b>  ' . ucfirst($ref_name) . '</p>
                        <p style="color:#7e7e7e;font-size:13px;">Congratulation You have One more refferal  </p>
                        <p style="color:#7e7e7e;font-size:13px;">Rs.100 has been Credited in your wallet</p>
                </div>';
                        $message = $email_cnt->get_design($message);
                        $this->email->to($refemail);
                        $this->email->from("donotreply@airmedpathlabs.com", "AirmedLabs");
                        $this->email->subject("Refferal Amount Credited");
                        $this->email->message($message);
                        $this->email->send();
                    }
                    // end code for refer price
                    $config['mailtype'] = 'html';
                    $this->email->initialize($config);

                    $message = '<div style="padding:0 4%;">
                    <h4><b>Confirm Your Register</b></h4>
                        <p><b>Dear </b> ' . ucfirst($name) . '</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank you for registering for the Airmed PATH LAB. </p>
                        <p style="color:#7e7e7e;font-size:13px;">Please confirm Your Email to get all services provided by Airmed PATH LAB</p>
			<a href="' . base_url() . 'register/confirm_email/' . $confirm_code . '" style="background: #D01130;color: #f9f9f9;padding: 1%;text-decoration: none;">Confirm</a>
                </div>';
                    $message = $email_cnt->get_design($message);
                    $this->email->to($email);
                    $this->email->from("donotreply@airmedlabs.com", "AirmedLabs");
                    $this->email->subject("AirmedLabs Confirm email");
                    $this->email->message($message);
                    //$this->email->send();
                    /* Nishit code start */
                    /* Nishit send sms code start */
                    $sms_message = $this->service_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "OTP"), array("id", "asc"));
                    $sms_message = preg_replace("/{{NAME}}/", ucfirst($name), $sms_message[0]["message"]);
                    $sms_message = preg_replace("/{{OTP}}/", $OTP, $sms_message);
                    $sms_message = preg_replace("/{{PRICE}}/", "", $sms_message);
                    $this->load->helper("sms");
                    $notification = new Sms();
                    $mb_length = strlen($mobile);
                    $notification->send($mobile, $sms_message);
                    $this->service_model->master_fun_insert("test", array("test" => $mobile . "-" . $sms_message));
                    /* Nishit send sms code end */
                    /* Nishit code end */
                    $data = $this->service_model->master_fun_get_tbl_val("customer_master", array("id" => $insert), array("id", "asc"));
                    //$data[0]["test_city"] = $test_city;
                    echo $this->json_data("1", "", $data);
                } else {
                    echo $this->json_data("0", "Mobile Number Already Exists!", "");
                }
            } else {
                echo $this->json_data("0", "Email Already Registered", "");
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    //function confirm_email(){
    //	$code = $this->uri->segemnt(3);
    //	$update = $this->service_model->master_fun_update1("customer_master",array("confirm_code"=>$code),array("confirm_code"=>"","active"=>1));
    //	
    //}
    function check_otp() {
        $id = $this->input->get_post('id');
        $otp = $this->input->get_post('otp');
        if ($id != NULL && $otp != NULL) {
            $row = $this->service_model->master_num_rows("customer_master", array("id" => $id, "otp" => $otp));
            $data = $this->service_model->master_fun_get_tbl_val("customer_master", array("id" => $id), array("id", "asc"));
            if ($row == 1) {
                $user_info = $this->service_model->master_fun_get_tbl_val("user_change_phone", array('user_fk' => $id), array("id", "desc"));
                $update = $this->service_model->master_fun_update("customer_master", $data[0]["id"], array("otp" => '', "mobile" => $user_info[0]["mobile"], "status" => "1", "active" => "1"));
                $data = $this->service_model->master_fun_get_tbl_val("customer_master", array("id" => $id), array("id", "asc"));
                echo $this->json_data("1", "", $data);
            } else {
                echo $this->json_data("0", "Invalid OTP.", "");
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function resend_opt_newuser() {
        $id = $this->input->get_post('id');
        $OTP = rand(11111, 99999);
        if ($id != NULL && $OTP != NULL) {
            $row = $this->service_model->master_num_rows("customer_master", array("id" => $id));
            $data = $this->service_model->master_fun_get_tbl_val("customer_master", array("id" => $id), array("id", "asc"));
            if ($row == 1) {
                $user_info = $this->service_model->master_fun_get_tbl_val("user_change_phone", array('user_fk' => $id), array("id", "desc"));
                $mobile = $user_info[0]['mobile'];
                $update = $this->service_model->master_fun_update("customer_master", $id, array("otp" => $OTP));
                $sms_message = $this->user_master_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "OTP"), array("id", "asc"));
                $sms_message = preg_replace("/{{NAME}}/", ucfirst($name), $sms_message[0]["message"]);
                $sms_message = preg_replace("/{{OTP}}/", $OTP, $sms_message);
                $sms_message = preg_replace("/{{PRICE}}/", "", $sms_message);
                $this->load->helper("sms");
                $notification = new Sms();
                $mb_length = strlen($mobile);
                $notification->send($mobile, $sms_message);
                //echo "<pre>"; print_r($user_info); die();
                //$update = $this->service_model->master_fun_update("customer_master", $data[0]["id"], array("otp" => '', "mobile" => $user_info[0]["mobile"], "status" => "1", "active" => "1"));
                //$data = $this->service_model->master_fun_get_tbl_val("customer_master", array("id" => $id), array("id", "asc"));
                echo $this->json_data("1", "", "");
            } else {
                echo $this->json_data("0", "Invalid OTP.", "");
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function check_otp_test() {
        $mobile = $this->input->get_post('mobile');
        $otp = $this->input->get_post('otp');
        if ($mobile != NULL && $otp != NULL) {
            $row = $this->service_model->master_num_rows("test_otp", array("mobile" => $mobile, "otp" => $otp));
            $data = $this->service_model->master_fun_get_tbl_val("test_otp", array("otp" => $otp, "mobile" => $mobile), array("id", "asc"));
            if ($row == 1) {
                $user_info = $this->service_model->master_fun_get_tbl_val("test_otp", array('mobile' => $mobile, "otp" => $otp), array("id", "desc"));
                $update = $this->service_model->master_fun_update("customer_master", $data[0]["id"], array("otp" => '', "mobile" => $mobile));
                echo $this->json_data("1", "", "valid");
            } else {
                echo $this->json_data("0", "Invalid OTP.", "");
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function add_opt_test() {

        $mobile = $this->input->get_post("mobile");
        /* Nishit code start */
        $otp = rand(11111, 99999);
        $update = $this->service_model->master_fun_insert("test_otp", array("mobile" => $mobile, "otp" => ""));
        $update = $this->service_model->master_fun_insert("test_otp", array("mobile" => $mobile, "otp" => $otp));
        if ($update) {
            /* Nishit send sms code start */
            $sms_message = $this->service_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "OTP"), array("id", "asc"));
            $sms_message = preg_replace("/{{NAME}}/", ucfirst($user_info[0]["full_name"]), $sms_message[0]["message"]);
            $sms_message = preg_replace("/{{OTP}}/", $otp, $sms_message);
            $sms_message = preg_replace("/{{PRICE}}/", "", $sms_message);
            $this->load->helper("sms");
            $notification = new Sms();
            $notification->send($mobile, $sms_message);
            /* Nishit send sms code end */
            echo $this->json_data("1", "", "");
            /* Nishit code end */
        } else {
            echo $this->json_data("0", "Oops somthing wrong. Try again.", "");
        }
    }

    function login() {
        $email = $this->input->get_post('email');
        $password = $this->input->get_post('password');
        //$device_id = $this->input->get_post('device_id');
        if ($email != NULL && $password != NULL) {
            $row = $this->service_model->master_num_rows("customer_master", array("email" => $email, "password" => $password, "status" => 1, "active" => 1));
            $row1 = $this->service_model->master_num_rows("customer_master", array("email" => $email, "password" => $password, "status" => 1, "active" => 0));
            $data = $this->service_model->master_fun_get_tbl_val("customer_master", array("email" => $email, "password" => $password, "status" => 1), array("id", "asc"));
            if ($row == 1) {
                //$update = $this->service_model->master_fun_update("customer_master",$data[0]['id'],array("device_id"=>$device_id));
                $data = $this->service_model->master_fun_get_tbl_val("customer_master", array("email" => $email, "password" => $password, "status" => 1), array("id", "asc"));
                echo $this->json_data("1", "", $data);
            } else if ($row1 == 1) {

                echo $this->json_data("0", "Your Email Address is not Activated Yet", "");
            } else {
                $row = $this->service_model->master_num_rows("customer_master", array("mobile" => $email, "password" => $password, "status" => 1, "active" => 1));
                $row1 = $this->service_model->master_num_rows("customer_master", array("mobile" => $email, "password" => $password, "status" => 1, "active" => 0));
                if ($row == 1) {
                    $data = $this->service_model->master_fun_get_tbl_val("customer_master", array("mobile" => $email, "password" => $password, "status" => 1), array("id", "asc"));
                    echo $this->json_data("1", "", $data);
                } else if ($row1 == 1) {

                    echo $this->json_data("0", "Your Email Address is not Activated Yet", "");
                } else {
                    echo $this->json_data("0", "Email Or Password Not match", "");
                }
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function gcm() {
        $user_id = $this->input->get_post('user_id');
        $device_id = $this->input->get_post('device_id');
        $device_type = $this->input->get_post('device_type');
        if ($user_id != NULL && $device_id != NULL) {
            $update = $this->service_model->master_fun_update("customer_master", $user_id, array("device_id" => $device_id, "device_type" => $device_type));
            //$data = $this->service_model->master_fun_get_tbl_val("customer_master", array("email"=>$email,"password"=>$password,"status" => 1),array("id","asc"));
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
        if ($name != null & $email != null && $password != null && $mobile != null && $address != null) {

            $row = $this->service_model->master_num_rows("doctor_master", array("email" => $email, "status" => 1));
            if ($row == 0) {
                $insert = $this->service_model->master_fun_insert("doctor_master", array("full_name" => $name, "email" => $email, "password" => $password, "mobile" => $mobile, "address" => $address, "status" => 2));
                echo $this->json_data("1", "", array(array("id" => $insert, "msg" => "Created Successfully")));
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
            $row = $this->service_model->master_num_rows("doctor_master", array("email" => $email, "password" => $password, "status !=" => 0));
            $data = $this->service_model->master_fun_get_tbl_val("doctor_master", array("email" => $email, "password" => $password, "status" => 1), array("id", "asc"));
            if ($row == 1) {
                //$update = $this->service_model->master_fun_update("customer_master",$data[0]['id'],array("device_id"=>$device_id));
                $row1 = $this->service_model->master_num_rows("doctor_master", array("email" => $email, "password" => $password, "status" => 2));
                if ($row1 == 1) {
                    echo $this->json_data("0", "Your Account not Confirm", "");
                } else {
                    $data = $this->service_model->master_fun_get_tbl_val("doctor_master", array("email" => $email, "password" => $password, "status" => 1), array("id", "asc"));
                    echo $this->json_data("1", "", $data);
                }
            } else {
                echo $this->json_data("0", "Email Or Password Not match", "");
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

            $update = $this->service_model->master_fun_update("doctor_master", $user_id, array("device_id" => $device_id, "device_type" => $device_type));
            //$data = $this->service_model->master_fun_get_tbl_val("customer_master", array("email"=>$email,"password"=>$password,"status" => 1),array("id","asc"));
            echo $this->json_data("1", "", array(array("msg" => "Your Device Id Saved")));
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function doctor_logout() {
        $user_id = $this->input->get_post('user_id');
        //$device_id = $this->input->get_post('device_id');
        if ($user_id != NULL) {
            $update = $this->service_model->master_fun_update("doctor_master", $user_id, array("device_id" => "", "device_type" => ""));
            //$data = $this->service_model->master_fun_get_tbl_val("customer_master", array("email"=>$email,"password"=>$password,"status" => 1),array("id","asc"));
            echo $this->json_data("1", "", array(array("msg" => "Logout Successfully")));
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function logout() {
        $user_id = $this->input->get_post('user_id');
        //$device_id = $this->input->get_post('device_id');
        if ($user_id != NULL) {

            $update = $this->service_model->master_fun_update("customer_master", $user_id, array("device_id" => "", "device_type" => ""));
            //$data = $this->service_model->master_fun_get_tbl_val("customer_master", array("email"=>$email,"password"=>$password,"status" => 1),array("id","asc"));
            echo $this->json_data("1", "", array(array("msg" => "Logout Successfully")));
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function doctor_forget_password() {
        $this->load->helper("Email");
        $email_cnt = new Email;

        $this->load->library('email');
        $email = $this->input->get_post('email');
        if ($email != NULL) {
            $row = $this->service_model->master_fun_get_tbl_val("doctor_master", array("email" => $email, "status" => 1, "active" => 1), array("id", "asc"));
            if (!empty($row)) {
                $new_password = random_string('alnum', 6);
                $this->service_model->master_fun_update("customer_master", $row[0]["id"], array("password" => $new_password));
                $password = $new_password;
                $config['mailtype'] = 'html';
                $this->email->initialize($config);
                // $message = "You recently requested to reset your password for your  Patholab Account.<br/><br/>";
                // $message .= "Your Password id <strong>" . $password . "</strong><br/><br/> ";
                //  $message .= "If you did not request a password reset, please ignore this email or reply to let us know.<br/><br/>";
                $message = '<div style="padding:0 4%;">
                    <h4><b>Forgot Password</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;">You recently requested to reset your password for your AirmedLabs Account. Your password is <b>' . $password . '</b> </p>
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
                echo $this->json_data("0", "Invalid Parameter", "");
            }
        } else {

            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function doctor_view_profile() {
        $userid = $this->input->get_post('user_id');
        $data = $this->service_model->doctor_view_profile($userid);
        if (empty($data)) {
            echo $this->json_data("0", "no data available", "");
        } else {
            echo $this->json_data("1", "", $data);
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


            $row = $this->service_model->master_fun_get_tbl_val("doctor_master", array("id" => $user_fk, "status" => 1), array("id", "asc"));
            $save_email = $row[0]['email'];
            if ($save_email != $email) {

                $row = $this->service_model->master_num_rows("doctor_master", array("email" => $email, "status" => 1));
                if ($row == 0) {
                    $update = $this->service_model->master_fun_update("doctor_master", $user_fk, $data);
                } else {

                    echo $this->json_data("0", "Email Already Exist", "");
                }
            } else {

                $update = $this->service_model->master_fun_update("doctor_master", $user_fk, $data);
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
            $row = $this->service_model->master_fun_get_tbl_val("doctor_master", array("id" => $user_fk, "status" => 1), array("id", "asc"));
            $oldpass = $row[0]['password'];
            if ($oldpassword == $oldpass) {
                $data = array(
                    "password" => $password,
                );
                $update = $this->service_model->master_fun_update("doctor_master", $user_fk, $data);
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
            $row = $this->service_model->master_fun_get_tbl_val("customer_master", array("email" => $email, "status" => 1, "active" => 1), array("id", "asc"));
            if (!empty($row)) {
                $new_password = random_string('alnum', 6);
                $this->service_model->master_fun_update("customer_master", $row[0]["id"], array("password" => $new_password));
                $password = $new_password;
                $config['mailtype'] = 'html';
                $this->email->initialize($config);
                // $message = "You recently requested to reset your password for your  Patholab Account.<br/><br/>";
                // $message .= "Your Password id <strong>" . $password . "</strong><br/><br/> ";
                //  $message .= "If you did not request a password reset, please ignore this email or reply to let us know.<br/><br/>";
                $message = '<div style="padding:0 4%;">
                    <h4><b>Forgot Password</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;">You recently requested to reset your password for your AirmedLabs Account. Your password is <b>' . $password . '</b> </p>
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
                $row1 = $this->service_model->master_fun_get_tbl_val("customer_master", array("mobile" => $email, "status" => 1, "active" => 1), array("id", "asc"));
                if (!empty($row1)) {
                    $new_password = random_string('alnum', 6);
                    $this->service_model->master_fun_update("customer_master", $row1[0]["id"], array("password" => $new_password));
                    $password = $new_password;
                    /* Nishit sms start */
                    $this->load->helper("sms");
                    $notification = new Sms();

                    $sms_message = $this->service_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "forgot_pass"), array("id", "asc"));
                    $sms_message = preg_replace("/{{CNAME}}/", ucfirst($row1[0]["full_name"]), $sms_message[0]["message"]);
                    $sms_message = preg_replace("/{{PASS}}/",$password , $sms_message);
                    $mobile = $email;
                    $notification::send($mobile, $sms_message);
                    /* Nishit sms end */
                    echo $this->json_data("1", "", array(array("msg" => "Password has been Sent on Your Email")));
                } else {
                    echo $this->json_data("0", "Invalid Email or Phone No.", "");
                }
            }
        } else {

            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function get_country() {
        $data = $this->service_model->master_fun_get_tbl_val("country", array("status" => 1), array("id", "asc"));
        if (empty($data)) {
            echo $this->json_data("0", "no data available", "");
        } else {
            echo $this->json_data("1", "", $data);
        }
    }

    function view_profile() {
        $userid = $this->input->get_post('user_id');
        if ($userid != null) {
            $data = $this->service_model->view_profile($userid);
            if (empty($data)) {
                echo $this->json_data("0", "no data available", "");
            } else {
                echo $this->json_data("1", "", $data);
            }
        } else {

            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function get_state() {
        //$cid = $this->input->get_post('country_id');
        //if($cid !=null){
        $data = $this->service_model->master_fun_get_tbl_val("state", array("status" => 1), array("id", "asc"));
        if (empty($data)) {
            echo $this->json_data("0", "no data available", "");
        } else {
            echo $this->json_data("1", "", $data);
        }
        //}else{
        //	echo $this->json_data("0","Parameter not passed","");	
        //}
    }

    function get_city() {
        $cid = $this->input->get_post('state_id');
        if ($cid != null) {
            $data = $this->service_model->master_fun_get_tbl_val("city", array("state_fk" => $cid, "status" => 1), array("id", "asc"));
            if (empty($data)) {
                echo $this->json_data("0", "no data available", "");
            } else {
                echo $this->json_data("1", "", $data);
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function edit_profile() {
        $user_fk = $this->input->get_post('user_id');
        $name = $this->input->get_post('name');
        $email = $this->input->get_post('email');
        $mobile = $this->input->get_post('mobile');
        $address = $this->input->get_post('address');
        $gender = $this->input->get_post('gender');
        $country = $this->input->get_post('country');
        $state = $this->input->get_post('state');
        $city = $this->input->get_post('city');
        $pic = $this->input->get_post('pic');
        if ($user_fk != "" && $name != "" && $email != "") {
            $data = array(
                "pic" => $pic,
                "full_name" => $name,
                "email" => $email,
                "mobile" => $mobile,
                "address" => $address,
                "gender" => $gender,
                "country" => $country,
                "state" => $state,
                "city" => $city,
            );


            $row = $this->service_model->master_fun_get_tbl_val("customer_master", array("id" => $user_fk, "status" => 1), array("id", "asc"));
            $save_email = $row[0]['email'];
            if ($save_email != $email) {

                $row = $this->service_model->master_num_rows("customer_master", array("email" => $email, "status" => 1));
                if ($row == 0) {
                    $update = $this->service_model->master_fun_update("customer_master", $user_fk, $data);
                } else {

                    echo $this->json_data("0", "Email Already Exist", "");
                }
            } else {

                $update = $this->service_model->master_fun_update("customer_master", $user_fk, $data);
            }

            if ($update) {
                echo $this->json_data("1", "", array(array("msg" => "Your Profile has been Updated")));
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function change_password() {
        $this->load->helper("Email");
        $email_cnt = new Email;

        $user_fk = $this->input->get_post('user_id');
        $oldpassword = $this->input->get_post('oldpassword');
        $password = $this->input->get_post('password');
        if ($user_fk != "" && $password != "") {
            $row = $this->service_model->master_fun_get_tbl_val("customer_master", array("id" => $user_fk, "status" => 1), array("id", "asc"));
            $oldpass = $row[0]['password'];
            if ($oldpassword == $oldpass) {
                $data = array(
                    "password" => $password,
                );
                $update = $this->service_model->master_fun_update("customer_master", $user_fk, $data);
                if ($update) {
                    $config['mailtype'] = 'html';
                    $this->email->initialize($config);
                    $message = '<div style="padding:0 4%;">
                    <h4><b>Password Change AirmedLabs</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;font-weight: bold;"><b>Dear </b>  ' . ucfirst($row[0]['full_name']) . '</p>
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

    function test_list() {
        $city = $this->input->get_post("city");
        /* if ($city == 333 || $city == '') {
          $data = $this->service_model->master_fun_get_tbl_val("test_master", array("status" => 1), array("test_name", "asc"));
          if (empty($data)) {
          echo $this->json_data("0", "no data available", "");
          } else {
          echo $this->json_data("1", "", $data);
          }
          } else {
          $qry1 = "SELECT * FROM test_master WHERE STATUS='1' order by test_name asc";
          $data1 = $this->service_model->get_result($qry1);
          $cnt = 0;
          foreach ($data1 as $key) {
          $data2 = $this->service_model->get_result("select * from test_master_city_price where test_fk='" . $key["id"] . "' and city_fk='" . $city . "'");
          if (!empty($data2)) {
          $data1[$cnt]["price"] = $data2[0]["price"];
          } else {
          $data1[$cnt]["price"] = "0";
          }
          $cnt++;
          }
          echo $this->json_data("1", "", $data1);
          } */
        if ($city) {
            //$qry = "select * from test_master where status='1'";
            $qry = "SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master`.`is_view`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $city . "'";
            $data2 = $this->service_model->get_result($qry);
            /* $cnt = 0;
              foreach ($data2 as $key) {
              //$tst = $this->service_model->get_result("select * from test_master_city_price where test_fk='" . $key["id"] . "' and city_fk='" . $city . "' and status='1'");
              if (!empty($tst)) {
              $data2[$cnt]["price"] = $tst[0]["price"];
              } else {
              $data2[$cnt]["price"] = "0";
              }
              $cnt++;
              } */
        } else {
            $qry = "SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master`.`is_view`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='1'";
            $data2 = $this->service_model->get_result($qry);
            /* $cnt = 0;
              foreach ($data2 as $key) {
              $data2[$cnt]["price"] = "0";
              $cnt++;
              } */
        }
        echo $this->json_data("1", "", $data2);
    }

    function my_job() {
        $user_fk = $this->input->get_post('user_id');
        if ($user_fk != "") {
            $data = $this->service_model->my_job($user_fk);
            if (empty($data)) {
                echo $this->json_data("0", "no data available", "");
            } else {
                $newdata = array();
                foreach ($data as $key) {

                    $key['test'] = (($key['test'] == "") ? "" : $key['test'] . ',' ) . $key['packge_name'];
                    $newdata[] = $key;
                    //$newdata[]	=$key;
                }
                echo $this->json_data("1", "", $newdata);
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function pending_job() {
        $user_fk = $this->input->get_post('user_id');
        if ($user_fk != "") {
            $data = $this->service_model->pending_job($user_fk);
            if (empty($data)) {
                echo $this->json_data("0", "no data available", "");
            } else {
                $newdata = array();
                foreach ($data as $key) {
                    $key['test'] = (($key['test'] == "") ? "" : $key['test'] . ',' ) . $key['packge_name'];
                    $newdata[] = $key;
                }

                echo $this->json_data("1", "", $newdata);
            }
        } else {

            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function approved_job() {
        $user_fk = $this->input->get_post('user_id');
        $this->joblist_by_user_status($user_fk, 6);
    }

    function sample_collected_job() {
        $user_fk = $this->input->get_post('user_id');
        $this->joblist_by_user_status($user_fk, 7);
    }

    function processing_job() {
        $user_fk = $this->input->get_post('user_id');
        $this->joblist_by_user_status($user_fk, 8);
    }

    function joblist_by_user_status($user_fk, $status) {
        if ($user_fk != "") {
            $data = $this->service_model->joblist_by_status($user_fk, $status);
            if (empty($data)) {
                echo $this->json_data("0", "no data available", "");
            } else {
                $newdata = array();
                foreach ($data as $key) {
                    $key['test'] = (($key['test'] == "") ? "" : $key['test'] . ',' ) . $key['packge_name'];
                    $newdata[] = $key;
                }

                echo $this->json_data("1", "", $newdata);
            }
        } else {

            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function completed_job() {
        $user_fk = $this->input->get_post('user_id');
        if ($user_fk != "") {
            $data = $this->service_model->completed_job($user_fk);
            if (empty($data)) {
                echo $this->json_data("0", "no data available", "");
            } else {

                $newdata = array();
                foreach ($data as $key) {
                    $key['test'] = (($key['test'] == "") ? "" : $key['test'] . ',' ) . $key['packge_name'];
                    $newdata[] = $key;
                }
                echo $this->json_data("1", "", $newdata);
            }
        } else {

            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function show_cancle_job() {
        $user_fk = $this->input->get_post('user_id');
        if ($user_fk != "") {
            $data = $this->service_model->cancle_job($user_fk);
            if (empty($data)) {
                echo $this->json_data("0", "no data available", "");
            } else {


                echo $this->json_data("1", "", $data);
            }
        } else {

            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function total_wallet() {
        $user_fk = $this->input->get_post('user_id');
        if ($user_fk != "") {
            $maxid = $this->service_model->total_wallet($user_fk);
            $data = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "id" => $maxid), array("id", "asc"));
            if (empty($data)) {
                echo $this->json_data("1", "", array(array("total" => "0.00")));
            } else {
                echo $this->json_data("1", "", $data);
            }
        } else {

            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function add_wallet() {
        $user_fk = $this->input->get_post('user_id');
        $amount = $this->input->get_post('amount');
        if ($user_fk != "" && $amount != "") {
            $query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $user_fk), array("id", "desc"));
            $total = $query[0]['total'];
            $data = array(
                "cust_fk" => $user_fk,
                "credit" => $amount,
                "total" => $total + $amount,
                "created_time" => date('Y-m-d H:i:s')
            );
            $insert = $this->service_model->master_fun_insert("wallet_master", $data);
            if ($insert) {
                $query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $user_fk), array("id", "desc"));
                $total = $query[0]['total'];
                echo $this->json_data("1", "", array(array("msg" => "Money Added Successfully", "total" => $total)));
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function job_report() {
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
                $tst_details = $this->service_model->get_result("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $data[0]["test_city"] . "' AND `test_master_city_price`.`status`='1' AND test_master.id='" . $key["test_fk"] . "'");
                $report = '';
                $original = '';
                $description = "";

                $common_report_data = $this->service_model->get_result("SELECT * from report_master where job_fk='" . $jid . "' AND type='c'");

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
                $data_new[$cnt]["dueprice"] = $data[0]['payable_amount'];
                $data_new[$cnt]["payment_link"] = base_url() . 'u/j/' . $data[0]['id'];
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
                $report = '';
                $original = '';

                $description = $tst_details[0]["description"];
                $common_report_data = $this->service_model->get_result("SELECT * from report_master where job_fk='" . $jid . "' AND type='c'");
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
                $data_new[$cnt]["dueprice"] = $data[0]['payable_amount'];
                $data_new[$cnt]["payment_link"] = base_url() . 'u/j/' . $data[0]['id'];
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

    function job_report1() {
        $jid = $this->input->get_post('job_id');
        if ($jid != null) {
            $data = $this->get_job_details($jid);
            $new_array = array();
            foreach ($data[0]["book_package"] as $key) {
                $key["test_name"] = $key["title"];
                $key["price"] = $key["d_price"];
                $key["desc_web"] = '';
                $key["desc_app"] = '';
                $new_array[] = $key;
            }
            //print_r($new_array); die();
            $data[0]["book_package"] = array();
            $data[0]["book_package"] = $new_array;
            $common_report_data = $this->service_model->get_result("SELECT * from report_master where job_fk='" . $jid . "' AND type='c'");
            if (!empty($common_report_data)) {
                $report = $common_report_data[0]["report"];
                $original = $common_report_data[0]["original"];
            }
            if ($report) {
                $data[0]["report"] = $report;
            } else {
                $data[0]["report"] = " ";
            }
            if ($data[0]["collection_charge"] == '' || $data[0]["collection_charge"] == 0) {
                $data[0]["collection_charge"] = 0;
                $scc = "0";
            } else {
                $scc = "100";
            }
            $final_total = $data[0]['price'] + $scc;
            $data[0]["total"] = $data[0]['price'];
            $data[0]["collection_charge"] = $scc;
            $data[0]["final_total"] = "" . $final_total;
            $data[0]["original"] = $original;
            $data[0]["dueprice"] = $data[0]['payable_amount'];
            $data[0]["payment_link"] = base_url() . 'u/j/' . $data[0]['id'];
            $data[0]["invoice_link"] = base_url() . 'upload/result/' . $data[0]['invoice'];
            if (empty($data)) {
                echo $this->json_data("0", "no data available", "");
            } else {
                echo $this->json_data("1", "", $data);
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    /* function search_test() {
      $test = $this->input->get_post('testname');
      if ($test != "") {
      $data = $this->service_model->search_test($test);
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
      $data = $this->service_model->master_fun_get_tbl_val("test_master", array("status" => 1), array("test_name", "asc"));
      } else {
      $qry1 = "SELECT * FROM test_master WHERE STATUS='1' and test_name LIKE '%" . $test . "%' order by test_name asc";
      $data = $this->service_model->get_result($qry1);
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
      $qry1 = "SELECT * FROM test_master WHERE STATUS='1' AND test_name LIKE '%".$test."%' order by test_name asc";
      }
      $data1 = $this->service_model->get_result($qry1);
      $cnt = 0;
      foreach ($data1 as $key) {
      $data2 = $this->service_model->get_result("select * from test_master_city_price where test_fk='" . $key["id"] . "' and city_fk='" . $city . "'");
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
     */

    function search_test() {
        $city = $this->input->get_post("city");
        $testname = $this->input->get_post("testname");
        if ($city == '') {
            $city = 1;
        }
        //$qry = "select * from test_master where status='1' and test_name like '%" . $testname . "%'";
        $qry = "SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $city . "' AND test_master.test_name LIKE '%" . $testname . "%'";
        $data2 = $this->service_model->get_result($qry);
        /* $cnt = 0;
          foreach ($data2 as $key) {
          $tst = $this->service_model->get_result("select * from test_master_city_price where test_fk='" . $key["id"] . "' and city_fk='" . $city . "' and status='1' order by id asc");
          if (!empty($tst)) {
          $data2[$cnt]["price"] = $tst[0]["price"];
          } else {
          $data2[$cnt]["price"] = "0";
          }
          $cnt++;
          }
          } else {
          $qry = "select * from test_master where status='1' and test_name like '%" . $testname . "%'";
          $data2 = $this->service_model->get_result($qry);
          $cnt = 0;
          foreach ($data2 as $key) {
          $tst = $this->service_model->get_result("select * from test_master_city_price where test_fk='" . $key["id"] . "' and city_fk='1' and status='1' order by id asc");
          if (!empty($tst)) {
          $data2[$cnt]["price"] = $tst[0]["price"];
          } else {
          $data2[$cnt]["price"] = "0";
          }
          $cnt++;
          }
          } */
        echo $this->json_data("1", "", $data2);
    }

    function add_job() {
        $this->load->helper("Email");
        $email_cnt = new Email;

        $user_fk = $this->input->get_post('user_id');
        $testfk = $this->input->get_post('test_id');
        $amount = $this->input->get_post('total');
        $doctor = $this->input->get_post('doctor');
        $type = $this->input->get_post("type");
        $landmark = $this->input->get_post("landmark");
        $other_reference = $this->input->get_post('other_reference');
        $order_id = $this->get_job_id($this->input->get_post("test_city"));
        $date = date('Y-m-d H:i:s');
        /* Nishit book phlebo start */
        $testforself = $this->input->get_post("testforself");
        $family_mem_id = $this->input->get_post("family_mem_id");
        $address = $this->input->get_post("address");
        $date1 = $this->input->get_post("date");
        $date1 = explode("/", $date1);
        $date1 = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $time_slot_id = $this->input->get_post("time_slot_id");
        $emergency_req = $this->input->get_post("emergency_req");
        $address = $this->get_address();
        if ($testforself == "true") {
            $b_type = "self";
            $family_mem_id = 0;
        } else {
            $b_type = "family";
        }
        if ($emergency_req == "true") {
            $date1 = date("Y-m-d");
            $emergency_req = 1;
            $time_slot_id = 0;
        } else {
            $emergency_req = 0;
        }
        $test_city = $this->input->get_post("test_city");
        if (empty($test_city)) {
            $test_city = 1;
        }
        $booking_fk = $this->service_model->master_fun_insert("booking_info", array("user_fk" => $user_fk, "type" => $b_type, "landmark" => $landmark, "family_member_fk" => $family_mem_id, "address" => $address, "date" => $date1, "time_slot_fk" => $time_slot_id, "emergency" => $emergency_req, "status" => "1", "createddate" => date("Y-m-d H:i:s")));
        /* Nishit book phlebo end */
        if ($user_fk != "" && $testfk != "") {
            $insert = $this->service_model->master_fun_insert("job_master", array("cust_fk" => $user_fk, "date" => $date, "test_city" => $test_city, "order_id" => $order_id, "doctor" => $doctor, "other_reference" => $other_reference, "payment_type" => "Wallet", "portal" => $type, "booking_info" => $booking_fk));
            $test = explode(',', $testfk);
            $price = 0;
            $test_name_mail = array();
            for ($i = 0; $i < count($test); $i++) {
                $insert_code = $this->service_model->master_fun_insert("job_test_list_master", array("job_fk" => $insert, "test_fk" => $test[$i]));
                //$data = $this->service_model->master_fun_get_tbl_val("test_master", array("status" => 1, 'id' => $test[$i]), array("id", "asc"));
                $data = $this->service_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $test_city . "' AND `test_master_city_price`.`status`='1' AND `test_master`.`id`='" . $test[$i] . "'");
                $price = $price + $data[0]['price'];
                $test_name_mail[$i] = $data[0]['test_name'];
            }
            $update = $this->service_model->master_fun_update("job_master", $insert, array("price" => $price));
            $this->assign_phlebo_job($insert);
            $file = $this->pdf_invoice($insert);
            $this->check_prescription_test($insert, $user_fk);
            if ($update) {
                $query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $user_fk), array("id", "desc"));
                $total = $query[0]['total'];
                if ($total >= $amount) {
                    $query = $this->service_model->master_fun_get_tbl_val("offer_master", array("status" => 1), array("id", "desc"));
                    $caseback_per = $query[0]['caseback_per'];
                    $caseback_amount = ($price * $caseback_per) / 100;
                    $data = array(
                        "cust_fk" => $user_fk,
                        "debit" => $amount,
                        "total" => $total - $amount,
                        "job_fk" => $insert,
                        "created_time" => date('Y-m-d H:i:s')
                    );
                    if ($total >= $price) {
                        $insert1 = $this->service_model->master_fun_insert("wallet_master", $data);
                    }
                    $query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $user_fk), array("id", "desc"));
                    $total1 = $query[0]['total'];
                    /* collection charge start */
                    $j_total_price = $amount;
                    $collection_charge = 0;
                    $payable_amount = 0;
                    if ($j_total_price < 300) {
                        $collection_charge = 1;
                        /* $j_total_price = $j_total_price + 100;
                          $payable_amount = $payable_amount + 100; */
                    }
                    /* collection charge end */
                    $this->service_model->master_fun_update("job_master", $insert, array("price" => $amount, "payable_amount" => $payable_amount));
                    $j_id = $insert;
                } else {
                    $query = $this->service_model->master_fun_get_tbl_val("offer_master", array("status" => 1), array("id", "desc"));
                    $caseback_per = $query[0]['caseback_per'];
                    $caseback_amount = ($price * $caseback_per) / 100;
                    $dabit_amount = $amount - ($amount - $total);
                    $data = array(
                        "cust_fk" => $user_fk,
                        "debit" => $dabit_amount,
                        "total" => 0,
                        "job_fk" => $insert,
                        "created_time" => date('Y-m-d H:i:s')
                    );
                    if ($total > 0) {
                        $insert1 = $this->service_model->master_fun_insert("wallet_master", $data);
                    }
                    $query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $user_fk), array("id", "desc"));
                    $total1 = $query[0]['total'];
                    /* collection charge start */
                    $j_total_price = $amount;
                    $collection_charge = 0;
                    $payable_amount = $amount - $total;
                    if ($j_total_price < 300) {
                        $collection_charge = 1;
                        //$j_total_price = $j_total_price + 100;
                        //$payable_amount = $payable_amount + 100;
                    }
                    /* collection charge end */
                    $this->service_model->master_fun_update("job_master", $insert, array("price" => $amount, "payable_amount" => $payable_amount, "collection_charge" => $collection_charge));
                    $j_id = $insert;
                }
                $query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $user_fk), array("id", "desc"));
                $Current_wallet = $query[0]['total'];

                $destail = $this->service_model->master_fun_get_tbl_val("customer_master", array("status" => 1, "id" => $user_fk), array("id", "asc"));


                $config['mailtype'] = 'html';
                $this->email->initialize($config);
                $family_member_name = $this->service_model->get_family_member_name($j_id);
                if (!empty($family_member_name)) {
                    $destail[0]['full_name'] = $family_member_name[0]["name"];
                }
                $timeslot = $this->service_model->get_val("SELECT ts.start_time,ts.end_time,b.date FROM booking_info as b left join phlebo_time_slot as ts on b.time_slot_fk=ts.id WHERE ts.status='1' AND b.status='1' AND b.id='" . $booking_fk . "'");
                $s_time = date('h:i A', strtotime($timeslot[0]["start_time"]));
                $e_time = date('h:i A', strtotime($timeslot[0]["end_time"]));
                $datebb = date("d F,Y", strtotime($timeslot[0]['date']));

                $message = '<div style="padding:0 4%;">
                    <h4><b>Dear </b>' . $destail[0]['full_name'] . '</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Test has been book successfully. </p>
                        <p style="color:#7e7e7e;font-size:13px;">You booked ' . implode($test_name_mail, ',') . '</p>
                            <p style="color:#7e7e7e;font-size:13px;"> Sample Collection time :  ' . $datebb . ' ' . $s_time . '-' . $e_time . '  </p>  
                        <p style="color:#7e7e7e;font-size:13px;"> Rs. ' . $price . ' Debited From your Wallet. </p>
                        <p style="color:#7e7e7e;font-size:13px;">Your Current balance is Rs. ' . ($total - $price) . '</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
                $message = $email_cnt->get_design($message);
                $this->email->to($destail[0]['email']);
                $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
                $this->email->subject('Test Book Successfully');
                $this->email->message($message);
                $attatchPath = base_url() . "upload/result/" . $file;
                $this->email->attach($attatchPath);
                $this->email->send();
                $family_member_name = $this->service_model->get_family_member_name($j_id);
                if (!empty($family_member_name)) {
                    $c_email = $family_member_name[0]["email"];
                    if (!empty($c_email)) {
                        $this->email->to($c_email);
                        $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
                        $this->email->subject('Test Book Successfully');
                        $this->email->message($message);
                        $this->email->send();
                    }
                }
                $message = '<div style="padding:0 4%;">
				<h4><b>Dear </b>' . $destail[0]['full_name'] . '</h4>
                    <h4 style="color:##c7c7c7;text-decoration: underline;">Cashback Credited Successfully</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Cashback Credited in your Wallet. </p>
                       
<p style="color:#7e7e7e;font-size:13px;"> Rs. ' . $caseback_amount . ' Credited in your Wallet. </p>
		<p style="color:#7e7e7e;font-size:13px;">Your Current Wallet Amount is Rs. ' . $Current_wallet . '</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
                $message = $email_cnt->get_design($message);
                $this->email->to($destail[0]['email']);
                $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
                $this->email->subject('CashBack');
                $this->email->message($message);
                $this->email->send();

                /* Nishit code start */
                //$this->load->helper("sms");
                //$notification = new Sms();
                $mobile = $destail[0]['mobile'];
                $mobile = ucfirst($user[0]['full_name']) . "(" . $mobile . ")";
                $test_package = implode($test_name_mail, ',');
                $sms_message = $this->service_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "test_info"), array("id", "asc"));
                $sms_message = preg_replace("/{{MOBILE}}/", $mobile, $sms_message[0]["message"]);
                $sms_message = preg_replace("/{{TESTPACK}}/", 'Test', $sms_message);
                $sms_message = preg_replace("/{{TESTPACKLIST}}/", $test_package, $sms_message);
                $sms_message = preg_replace("/{{TOTALPRICE}}/", $amount, $sms_message);
                $configmobile = $this->config->item('admin_alert_phone');
                foreach ($configmobile as $p_key) {
                    $mb_length = strlen($p_key);
                    $configmobile = $p_key;
                    //$notification->send($configmobile, $sms_message);
                    $this->service_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $configmobile, "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
                }
                /* Nishit code end */
                echo $this->json_data("1", "", array(array("msg" => "Money Debited Successfully")));
                //echo $this->json_data("1","",array(array("id"=>$insert)));
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function cancle_job() {
        $user_fk = $this->input->get_post('user_id');
        $job_fk = $this->input->get_post('job_id');

        $date = date('d/m/Y');
        if ($user_fk != "" && $job_fk != "") {
            $update = $this->service_model->master_fun_update("job_master", $job_fk, array("status" => 4));

            if ($update) {
                $query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "job_fk" => $job_fk, "cust_fk" => $user_fk), array("id", "desc"));
                $total = $query[0]['total'];
                $query1 = $this->service_model->master_fun_get_tbl_val("job_master", array("id" => $job_fk), array("id", "desc"));
                //print_r($query1); die();
                $amount = $query1[0]['price'];

                $data = array(
                    "cust_fk" => $user_fk,
                    "credit" => $amount,
                    "total" => $total + $amount,
                    "created_time" => date('Y-m-d H:i:s')
                );
                $insert = $this->service_model->master_fun_insert("wallet_master", $data);
                echo $this->json_data("1", "", array(array("msg" => "Money Credited Successfully")));

                //echo $this->json_data("1","",array(array("id"=>$insert)));
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function dashboard_banner() {
        $data = $this->service_model->master_fun_get_tbl_val("banner_master", array("status" => 1, "group" => 3), array("id", "asc"));
        echo $this->json_data("1", "", $data);
    }

    function test_upload() {
        echo form_open_multipart("api/img_upload/", array("method" => "post", "role" => "form"));
        echo "<input type='file' name='userfile' />";
        echo "<input type='submit' name='test' value='upload'/>";
        echo "</form>";
    }

    public function upload_pic() {
        $files = $_FILES;
        $data = array();
        $this->load->library('upload');
        $config['allowed_types'] = 'png|jpg|gif|jpeg';
        //$config['encrypt_name'] = true;
        // $config['max_size'] = '2000'; // 2MB
        // $config['max_width'] = '3000'; // 3000px
        // $config['max_height'] = '3000'; // 3000px
        //echo $files['userfile']['name'];
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

    function img_upload() {
        //print_R($_FILES);
        if (isset($_FILES['userfile']['name'])) {
            $config['upload_path'] = './upload/';
            $config['allowed_types'] = '*';
            $config['file_name'] = time() . $_FILES['userfile']['name'];
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if (!$this->upload->do_upload('userfile')) {
                $error = array('error' => $this->upload->display_errors());
                echo $this->json_data("0", $error, "");
            } else {
                $data = array('upload_data' => $this->upload->data());
                echo $this->json_data("1", "", array(array("file_name" => $config['file_name'])));
            }
        } else {
            echo $this->json_data("0", "You did not select a file to upload", "");
        }
    }

    function check_balance() {
        $user_fk = $this->input->get_post('user_id');
        $amount = $this->input->get_post('total');
        $total = 0.00;
        if ($user_fk != "" && $amount != "") {
            $query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $user_fk), array("id", "desc"));
            $total = $query[0]['total'];
            if ($total >= $amount) {
                echo $this->json_data("1", "", array(array("msg" => "Wallet amount is enoght", "wallet" => $total)));
            } else {

                echo $this->json_data("0", "", array(array("msg" => "Please Upgread your Wallet", "wallet" => $total)));
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function debit_amount() {
        $user_fk = $this->input->get_post('user_id');
        $amount = $this->input->get_post('total');
        if ($user_fk != "" && $amount != "") {
            $query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $user_fk), array("id", "desc"));
            $total = $query[0]['total'];
            if ($total >= $amount) {
                $data = array(
                    "cust_fk" => $user_fk,
                    "debit" => $amount,
                    "total" => $total - $amount,
                    "created_time" => date('Y-m-d H:i:s')
                );
                $insert = $this->service_model->master_fun_insert("wallet_master", $data);
                if ($insert) {
                    echo $this->json_data("1", "", array(array("msg" => "Money Debited Successfully")));
                }
            } else {
                echo $this->json_data("0", "Upgread Your Wallet", "");
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function prescription_upload() {
        $this->load->helper("Email");
        $email_cnt = new Email;

        $image = $this->input->get_post('image');
        $desc = $this->input->get_post('description');
        $mobile = $this->input->get_post('mobile');
        $user_id = $this->input->get_post('user_id');
        $city = $this->input->get_post('city');
        $order_id = random_string('numeric', 13);
        $date = date('Y-m-d H:i:s');
        if ($image != NULL && $user_id != Null && $mobile != Null) {
            $insert = $this->service_model->master_fun_insert("prescription_upload", array("cust_fk" => $user_id, "image" => $image, "description" => $desc, "created_date" => $date, "order_id" => $order_id, "mobile" => $mobile, "city" => $city));
            $detail = $this->service_model->master_fun_get_tbl_val("customer_master", array("status" => 1, "id" => $user_id), array("id", "asc"));
            if ($insert) {
                $config['mailtype'] = 'html';
                $this->email->initialize($config);
                $message = '<div style="padding:0 4%;">
                    <h4><b>Contact Detail</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;font-weight: bold;">Follow information shows Contact Person Detail :</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Customer Name : </b> ' . ucfirst($detail[0]['full_name']) . '</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Email : </b> ' . $detail[0]['email'] . '</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Mobile : </b> ' . $mobile . '</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Description : </b> ' . ucfirst($desc) . '</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Uploaded file : </b> ' . base_url() . 'upload/' . $image . '</p>
                </div>';
                $pathToUploadedFile = base_url() . 'upload/' . $image;
                $message = $email_cnt->get_design($message);
                //$this->email->to("booking.airmed@gmail.com");
                $this->email->to($this->config->item('admin_booking_email'));
                //	$this->email->cc("hiten@virtualheight.com");
                $this->email->from("donotreply@airmedpathlabs.com", "AirmedLabs");
                $this->email->subject("New Prescription Uploaded");
                $this->email->message($message);
                $this->email->attach($pathToUploadedFile);
                $this->email->send();
                $this->load->helper("sms");
                $notification = new Sms();
                /* Nishit code start */
                $sms_message = $this->service_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "prescription_info"), array("id", "asc"));

                $sms_message = $sms_message[0]["message"];
                if ($detail[0]['full_name'] == "") {

                    $sms_message = str_replace("{{NAME}} ({{MOBILE}})", $mobile, $sms_message);
                } else {
                    $sms_message = preg_replace("/{{NAME}}/", ucfirst($detail[0]['full_name']), $sms_message);
                    $sms_message = preg_replace("/{{MOBILE}}/", ucfirst($mobile), $sms_message);
                }
                $configmobile = $this->config->item('admin_alert_phone');
                foreach ($configmobile as $p_key) {
                    $mb_length = strlen($p_key);
                    $configmobile = $p_key;
                    //$notification->send($configmobile, $sms_message);
                    $this->service_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $configmobile, "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
                }
                $this->service_model->master_fun_insert("test", array("test" => $configmobile . "-" . $sms_message));
                /* Nishit code end */
                /* Nishit send sms code start */
                $sms_message = $this->service_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "upload_presc"), array("id", "asc"));
                $sms_message = $sms_message[0]["message"];
                $notification->send($mobile, $sms_message);
                //$this->service_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $mobile, "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
                $this->service_model->master_fun_insert("test", array("test" => $mobile . "-" . $sms_message));
                /* Nishit send sms code end */
                echo $this->json_data("1", "", array(array("msg" => "Presrciption Uploaded Successfully")));
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function show_upload_prescription() {

        $userid = $this->input->get_post('user_id');
        if ($userid != "") {
            $data = $this->service_model->prescription_report($userid);
            if (empty($data)) {
                echo $this->json_data("0", "no data available", "");
            } else {
                echo $this->json_data("1", "", $data);
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function wallet_history() {
        $userid = $this->input->get_post('user_id');
        if ($userid != null) {
            $data = $this->service_model->wallet_history($userid);
            if (empty($data)) {
                echo $this->json_data("0", "no data available", "");
            } else {
                echo $this->json_data("1", "", $data);
            }
        } else {

            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function payment_history() {
        $userid = $this->input->get_post('user_id');
        if ($userid != null) {
            $data = $this->service_model->payment_history($userid);
            if (empty($data)) {
                echo $this->json_data("0", "no data available", "");
            } else {
                echo $this->json_data("1", "", $data);
            }
        } else {

            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function suggest_test() {
        $userid = $this->input->get_post('pid');
        if ($userid != null) {
            $data = $this->service_model->suggest_test($userid);
            $new_data=array();
            foreach($data as $key){
                $key["id"]=$key["test_id"];
                $new_data[]=$key;
            }
            if (empty($new_data)) {

                echo $this->json_data("0", "no data available", "");
            } else {
                echo $this->json_data("1", "", $new_data);
            }
        } else {

            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    public function fb_login() {
        $email = $this->input->get_post('email');
        $name = $this->input->get_post('name');
        $device_id = $this->input->get_post('device_id');
        $fb_id = $this->input->get_post('fb_id');
        $pic = $this->input->get_post('profile_pic');
        $code = random_string('alnum', 6);
        if ($email != null && $name != null) {
            $row = $this->service_model->master_num_rows("customer_master", array("email" => $email, "status" => 1));
            if ($row == 0) {
                $insert = $this->service_model->master_fun_insert("customer_master", array("full_name" => $name, "email" => $email, "device_id" => $device_id, "fbid" => $fb_id, "pic" => $pic, "status" => 0, "active" => 0));
                $insert_code = $this->service_model->master_fun_insert("refer_code_master", array("cust_fk" => $insert, "refer_code" => $code));
                $data = $this->service_model->master_fun_get_tbl_val("customer_master", array("id" => $insert), array("id", "asc"));
                echo $this->json_data("1", "", $data);
            } else {
                $data = $this->service_model->master_fun_get_tbl_val("customer_master", array("email" => $email, "status" => 1), array("id", "asc"));
                $update = $this->service_model->master_fun_update("customer_master", $data[0]['id'], array("full_name" => $name, "email" => $email, "device_id" => $device_id, "fbid" => $fb_id, "pic" => $pic));
                echo $this->json_data("1", "", $data);
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function user_info_dev() {
        $id = $this->input->get_post("id");
        $insert = $this->service_model->master_fun_get_tbl_val("customer_master", array("id" => $id), array("id", "asc"));
        echo json_encode($insert);
    }

    function user_info_delete_dev() {
        $id = $this->input->get_post("id");
        $insert = $this->service_model->master_fun_delete("customer_master", array("id", $id));
        echo "Success";
    }

    function add_opt() {
        $id = $this->input->get_post("id");
        $mobile = $this->input->get_post("mobile");
        $result = $this->user_master_model->get_val("SELECT * FROM customer_master WHERE mobile='" . $mobile . "' AND `status`='1' AND id NOT IN (" . $id . ")");
        if (empty($result)) {
            /* Nishit code start */
            $otp = rand(11111, 99999);
            $update = $this->service_model->master_fun_update("customer_master", $id, array("otp" => $otp));
            $this->service_model->master_fun_insert("user_change_phone", array("user_fk" => $id, "mobile" => $mobile));
            $user_info = $this->service_model->master_fun_get_tbl_val("customer_master", array('id' => $id), array("id", "asc"));
            if ($update) {
                /* Nishit send sms code start */
                $sms_message = $this->service_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "OTP"), array("id", "asc"));
                $sms_message = preg_replace("/{{NAME}}/", ucfirst($user_info[0]["full_name"]), $sms_message[0]["message"]);
                $sms_message = preg_replace("/{{OTP}}/", $otp, $sms_message);
                $sms_message = preg_replace("/{{PRICE}}/", "", $sms_message);
                $this->load->helper("sms");
                $notification = new Sms();
                $notification->send($mobile, $sms_message);
                /* Nishit send sms code end */
                echo $this->json_data("1", "", "");
                /* Nishit code end */
            } else {
                echo $this->json_data("0", "Oops somthing wrong. Try again.", "");
            }
        } else {
            echo $this->json_data("0", "Mobile number already registered.", "");
        }
    }

    function payuresponce() {

        $status = $this->input->post();
///$data=array("status"=>$status);
        echo json_encode($status);
        $this->load->view('payu_success');
    }

    function payu_success() {
        $t = json_encode($this->input->post());
        $this->load->helper("Email");
        $email_cnt = new Email;

        $payumonyid = $this->input->post('txnid');
        $paydate = $_POST['addedon'];
        $amount = $_POST['net_amount_debit'];
        $status = $_POST['status'];
//$uid = "";
        $jobid = $this->uri->segment(4);
        $wallet_amt = $this->uri->segment(5);
        $method = $this->uri->segment(6);
        $data1 = $this->service_model->master_fun_get_tbl_val("job_master", array("id" => $jobid), array("id", "asc"));
        $timeslot = $this->service_model->get_val("SELECT ts.start_time,ts.end_time,b.date FROM booking_info as b left join phlebo_time_slot as ts on b.time_slot_fk=ts.id WHERE ts.status='1' AND b.status='1' AND b.id='" . $data1[0]['booking_info'] . "'");
        $s_time = date('h:i A', strtotime($timeslot[0]["start_time"]));
        $e_time = date('h:i A', strtotime($timeslot[0]["end_time"]));
        $datebb = date("d F,Y", strtotime($timeslot[0]['date']));
        $user_fk = $data1[0]['cust_fk'];
        $price = $data1[0]['price'];
        $query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $user_fk), array("id", "desc"));
        $total = $query[0]['total'];
        $email_use_wallet = "";
        if ($wallet_amt > 0) {
            if ($method == "wallet") {
                $data1 = array(
                    "cust_fk" => $user_fk,
                    "debit" => $wallet_amt,
                    "total" => $total - $wallet_amt,
                    "job_fk" => $jobid,
                    "created_time" => date('Y-m-d H:i:s')
                );
                $email_use_wallet = '<p style="color:#7e7e7e;font-size:13px;"> Rs. ' . $wallet_amt . ' Debited From your Wallet. </p>';
                $insert = $this->service_model->master_fun_insert("wallet_master", $data1);
            }
        }
        /*
          $query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $user_fk), array("id", "desc"));
          $total1 = $query[0]['total'];
          $query = $this->service_model->master_fun_get_tbl_val("offer_master", array("status" => 1), array("id", "desc"));
          $caseback_per = $query[0]['caseback_per'];

          $caseback_amount = ($price * $caseback_per) / 100;

          $data = array(
          "cust_fk" => $user_fk,
          "credit" => $caseback_amount,
          "total" => $total1 + $caseback_amount,
          "type" => "Case Back",
          "job_fk" => $jobid,
          "created_time" => date('Y-m-d H:i:s')
          );
          $insert = $this->service_model->master_fun_insert("wallet_master", $data);
         */
        $update = $this->service_model->master_fun_update("job_master", $jobid, array("status" => 1, "payment_type" => "PayUMoney"));

        $data1 = array("payomonyid" => $payumonyid,
            "amount" => $amount,
            "paydate" => $paydate,
            "status" => $status,
            "uid" => $user_fk,
            "job_fk" => $jobid,
            "type" => "job",
            "data" => $t,
        );
        $insert = $this->service_model->master_fun_insert("payment", $data1);
        $destail = $this->service_model->master_fun_get_tbl_val("customer_master", array("status" => 1, "id" => $user_fk), array("id", "asc"));
        $query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $user_fk), array("id", "desc"));
        $Current_wallet = $query[0]['total'];

        $config['mailtype'] = 'html';
        $this->email->initialize($config);
        $family_member_name = $this->service_model->get_family_member_name($jobid);
        if (!empty($family_member_name)) {
            $destail[0]['full_name'] = $family_member_name[0]["name"];
        }
        $message = '<div style="padding:0 4%;">
                    <h4><b>Dear </b>' . $destail[0]['full_name'] . '</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Test has been book successfully. </p>
                        <p style="color:#7e7e7e;font-size:13px;"> Sample Collection time :  ' . $datebb . ' ' . $s_time . '-' . $e_time . '  </p>  
                    ' . $email_use_wallet . '
					 <p style="color:#7e7e7e;font-size:13px;"> Rs. ' . $amount . ' Paid using PayUMoney </p>
        <p style="color:#7e7e7e;font-size:13px;">Your Current Wallet balance is Rs. ' . $Current_wallet . '</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
        $message = $email_cnt->get_design($message);
        $this->email->to($destail[0]['email']);
        $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
        $this->email->subject('Test Book Successfully');
        $this->email->message($message);
        $this->email->send();
        $family_member_name = $this->service_model->get_family_member_name($jobid);
        if (!empty($family_member_name)) {
            $c_email = $family_member_name[0]["email"];
            if (!empty($c_email)) {
                $this->email->to($c_email);
                $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
                $this->email->subject('Test Book Successfully');
                $this->email->message($message);
                $this->email->send();
            }
        }
        /*
          $message = '<div style="background:#a3a3a3 !important;font-family: "Roboto",sans-serif;">
          <script src="https://use.fontawesome.com/44049e3dc5.js"></script>
          <div style="background: #6b6b6b none repeat scroll 0 0;float: left;padding: 7%;">
          <div style="border:1px solid #f1f1f1;border-bottom:1px solid #c7c7c7;padding:1.5%;width:100%;float:left;background:#fff;">
          <div style="float:left;width:64%;">
          <img alt="" src="' . base_url() . 'user_assets/images/logo.png" style="height: 100px;width:213px;"/>
          </div>
          <div style="float:right;text-align: right;width:33%;padding-top:7px;">



          </div>
          </div>
          <div style="padding:1.5%;width:100%;float:left;background-color:#fff;border:1px solid #f1f2f3;">
          <div style="padding:0 4%;">
          <h4 style="color:##c7c7c7;text-decoration: underline;">Cashback Credited Successfully</h4>
          <p style="color:#7e7e7e;font-size:13px;">Cashback Credited in your Wallet. </p>

          <p style="color:#7e7e7e;font-size:13px;"> Rs. ' . $caseback_amount . ' Credited in your Wallet. </p>
          <p style="color:#7e7e7e;font-size:13px;">Your Current Wallet Amount is Rs. ' . $Current_wallet . '</p>
          <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
          </div>
          </div>
          <div style="width:100%;float:left;background:#F5F5F5;padding:1.5%;color:#828282;font-size:12px;border:1px solid #dfdfdf;text-align:center;">
          <a href="' . base_url() . 'user_master/about_us" style="color:#515151;text-decoration:none;">About Us</a> |
          <a href="' . base_url() . 'user_master/contact_us" style="color:#515151;text-decoration:none;">Contact Us</a> |
          <a href="' . base_url() . 'user_login" style="color:#515151;text-decoration:none;">Login</a> |
          <a href="' . base_url() . 'user_master/collection" style="color:#515151;text-decoration:none;">Home-collection</a> |
          <a href="https://www.facebook.com/airmedpathlabs" style="color:#515151;text-decoration:none;"><i aria-hidden="true"><img src="' . base_url() . 'user_assets/images/face_book_icon1.jpeg" style="height:15px;width:15px;"></i></a>
          </div>
          <div style="width:100%;float:left;background:#333333;padding:1.5%;color:#ccc;font-size:12px;border:1px solid #323232;text-align:center;">
          Copyright @ 2016 AirmedLabs. All rights reserved
          </div>
          </div>
          </div>';
          $this->email->to($destail[0]['email']);
          $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
          $this->email->subject('CashBack');
          $this->email->message($message);
          $this->email->send();
          $package_user1 = $this->service_model->get_job_test_name($this->uri->segment(4));
          $package_user = array();
          foreach ($package_user1 as $p_nm) {
          $package_user[] = $p_nm["test_name"];
          } */
        /* Nishit code start */
        //$this->load->helper("sms");
        //$notification = new Sms();
        $mobile = $destail[0]['mobile'];
        $mobile = ucfirst($destail[0]['full_name']) . "(" . $mobile . ")";
        $test_package = implode(",", $package_user);
        $sms_message = $this->service_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "test_info"), array("id", "asc"));
        $sms_message = preg_replace("/{{MOBILE}}/", $mobile, $sms_message[0]["message"]);
        $sms_message = preg_replace("/{{TESTPACK}}/", 'Test', $sms_message);
        $sms_message = preg_replace("/{{TESTPACKLIST}}/", $test_package, $sms_message);

        $configmobile = $this->config->item('admin_alert_phone');
        foreach ($configmobile as $p_key) {
            $mb_length = strlen($p_key);
            $configmobile = $p_key;
            //$notification->send($configmobile, $sms_message);
            $this->service_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $configmobile, "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
        }
        /* Nishit code end */

        // Case Back Email end

        $data['payuMoneyId'] = $_POST['payuMoneyId'];
        $this->load->view('success1', $data);
    }

    function payu_fail() {
        $data['payuMoneyId'] = $_POST['payuMoneyId'];
        $this->load->view('fail', $data);
    }

    function add_wallet_success() {
        $this->load->helper("Email");
        $email_cnt = new Email;
        $t = json_encode($this->input->post());
//die();
//echo "this page is success page".$t; die();
        $userid = $this->uri->segment(4);
        $payumonyid = $this->input->post('txnid');
        $paydate = $_POST['addedon'];
        $amount = $_POST['net_amount_debit'];
        $status = $_POST['status'];
        $destail = $this->service_model->master_fun_get_tbl_val("customer_master", array("status" => 1, "id" => $userid), array("id", "asc"));
        $query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $userid), array("id", "desc"));
        $total = $query[0]['total'];

        $data1 = array("payomonyid" => $payumonyid,
            "amount" => $amount,
            "paydate" => $paydate,
            "status" => $status,
            "uid" => $userid,
            "type" => "wallet",
            "data" => $t,
        );
        $insert = $this->service_model->master_fun_insert("payment", $data1);

        $data1 = array(
            "cust_fk" => $userid,
            "credit" => $amount,
            "total" => $total + $amount,
            "payment_id" => $insert,
            "created_time" => date('Y-m-d H:i:s')
        );
        $insert = $this->service_model->master_fun_insert("wallet_master", $data1);

        $config['mailtype'] = 'html';
        $this->email->initialize($config);

        $message = '<div style="padding:0 4%;">
                    <h4><b>Money Added to wallet</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;">RS.' . $amount . ' successfully added to Wallet. Total Wallet Amount is RS.' . ($total + $amount) . ' </p>
                        <p style="color:#7e7e7e;font-size:13px;">Your Transaction id is ' . $payumonyid . '</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
        $message = $email_cnt->get_design($message);
        $this->email->to($destail[0]['email']);
        $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
        $this->email->subject('Money Added To Wallet');
        $this->email->message($message);
        $this->email->send();


        $data['payuMoneyId'] = $this->input->post('payuMoneyId');
        $this->load->view('success1', $data);
    }

/// ---------for half payment add_job --
    function add_job_half_payment() {
        $user_fk = $this->input->get_post('user_id');
        $testfk = $this->input->get_post('test_id');
        $amount = $this->input->get_post('total');
        $doctor = $this->input->get_post('doctor');
        $landmark = $this->input->get_post("landmark");
        $type = $this->input->get_post("type");
        $test_city = $this->input->get_post("test_city");
        if (empty($test_city)) {
            $test_city = 1;
        }
        $other_reference = $this->input->get_post('other_reference');
        $address = $this->get_address();
        $order_id = $this->get_job_id($this->input->get_post("test_city"));
        $date = date('Y-m-d H:i:s');
        if ($user_fk != "" && $testfk != "") {
            /* Nishit book phlebo start */
            $testforself = $this->input->get_post("testforself");
            $family_mem_id = $this->input->get_post("family_mem_id");
            //$address = $this->input->get_post("address");
            $date1 = $this->input->get_post("date");
            //$date1 = explode("/", $date1);
            //$date1 = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
            $time_slot_id = $this->input->get_post("time_slot_id");
            $emergency_req = $this->input->get_post("emergency_req");
            if ($testforself == "true") {
                $b_type = "self";
                $family_mem_id = 0;
            } else {
                $b_type = "family";
            }
            if ($emergency_req == "true") {
                $date1 = date("Y-m-d");
                $emergency_req = 1;
                $time_slot_id = 0;
            } else {
                $emergency_req = 0;
            }
            $booking_fk = $this->service_model->master_fun_insert("booking_info", array("user_fk" => $user_fk, "landmark" => $landmark, "type" => $b_type, "family_member_fk" => $family_mem_id, "address" => $address, "date" => $date1, "time_slot_fk" => $time_slot_id, "emergency" => $emergency_req, "status" => "1", "createddate" => date("Y-m-d H:i:s")));
            /* Nishit book phlebo end */
            $insert = $this->service_model->master_fun_insert("job_master", array("cust_fk" => $user_fk, "date" => $date, "status" => 0, "order_id" => $order_id, "doctor" => $doctor, "other_reference" => $other_reference, "payment_type" => "PayUMoney", "portal" => $type, "test_city" => $test_city, "booking_info" => $booking_fk));
            $test = explode(',', $testfk);
            $price = 0;
            for ($i = 0; $i < count($test); $i++) {
                $insert_code = $this->service_model->master_fun_insert("job_test_list_master", array("job_fk" => $insert, "test_fk" => $test[$i]));
                //$data = $this->service_model->master_fun_get_tbl_val("test_master", array("status" => 1, 'id' => $test[$i]), array("id", "asc"));
                $data = $this->service_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $test_city . "' AND `test_master_city_price`.`status`='1' AND `test_master`.`id`='" . $test[$i] . "'");
                $price = $price + $data[0]['price'];
            }
            /* collection charge start */
            $j_total_price = $price;
            $collection_charge = 0;
            if ($j_total_price != $amount && $j_total_price < 300) {
                $j_total_price = $j_total_price + 100;
                $collection_charge = 1;
            }
            /* collection charge end */
            $update = $this->service_model->master_fun_update("job_master", $insert, array("price" => $j_total_price, "collection_charge" => $collection_charge));

            if ($update) {

                echo $this->json_data("1", "", array(array("id" => $insert)));
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function mail_issue() {
        $this->load->helper("Email");
        $email_cnt = new Email;

        $this->load->library('email');
        $user_fk = $this->input->get_post('user_id');
        $subject = $this->input->get_post('subject');
        $message1 = $this->input->get_post('massage');
        if ($user_fk != "" && $subject != "" && $message1 != "") {
            $data = $this->service_model->master_fun_get_tbl_val("customer_master", array("status" => 1, 'id' => $user_fk), array("id", "asc"));
            $email = $data[0]['email'];
            $name = $data[0]['full_name'];
            /* $data1 = array("cust_fk" => $user_fk, "subject" => $subject, "massage" => $message1, "created_date" => date('d/m/Y'));
              $insert = $this->service_model->master_fun_insert("issue_master", $data1); */
            $ticket = rand(1111111, 9999999);
            $data1 = array("user_id" => $user_fk, "ticket" => $ticket, "title" => $subject, "status" => '1', "views" => "1", "status" => "1");
            $insert = $this->service_model->master_fun_insert("ticket_master", $data1);
            $this->service_model->master_fun_insert("message_master", array("ticket_fk" => $insert, "message" => $message1, "type" => "1", "status" => "1", "created_date" => date("Y-m-d H:i:s")));
            $config['mailtype'] = 'html';

            $this->email->initialize($config);

            $message = '<div style="padding:0 4%;">
                    <h4 style="color:##c7c7c7;text-decoration: underline;">Your Report & Issue</h4>
    <p>Hi! We just received your inquiry. We will get back to you as soon as possible. For your records, your support ticket number is  #' . $ticket . '. Include it in any future correspondence you might send. </p>
        <p>Your issue - ' . $message1 . '</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
            $message = $email_cnt->get_design($message);
            $subject = $subject;
            $this->email->to($data[0]['email']);
            $this->email->from("donotreply@airmedpathlabs.com", "AirmedLabs");
            $this->email->subject($subject);
            $this->email->message($message);
            $this->email->send();

            /* pinkesh code start */
            $config['mailtype'] = 'html';

            $this->email->initialize($config);

            $messagen = '<div style="padding:0 4%;">
                    <h4 style="color:##c7c7c7;text-decoration: underline;">Customer Report & Issue</h4>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Name : </b> ' . ucfirst($data[0]['full_name']) . '</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Phone : </b> ' . $data[0]['mobile'] . '</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Subject : </b> ' . ucfirst($subject) . '</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Issue : </b> ' . ucfirst($message1) . '</p>
                </div>';
            $messagen = $email_cnt->get_design($messagen);
            $this->email->to($this->config->item('admin_booking_email'));
            $this->email->from("donotreply@airmedpathlabs.com", "AirmedLabs");
            $this->email->subject($subject);
            $this->email->message($messagen);
            $this->email->send();
            /* pinkesh code end */

            echo $this->json_data("1", "", array(array("msg" => "Your issue raised has been submitted successfully. You will soon get an email confirmation")));
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function faq() {

        $this->load->view('faq');
    }

    function terms_condition() {

        $this->load->view('termsandcondition');
    }

    function get_refer_code() {
        $uid = $this->input->get_post('user_id');
        if ($uid != null) {
            $data = $this->service_model->master_fun_get_tbl_val("refer_code_master", array("cust_fk" => $uid, "status" => 1), array("id", "asc"));
            if (empty($data)) {
                echo $this->json_data("0", "no data available", "");
            } else {
                $refercode = $data[0]['refer_code'];
                echo $this->json_data("1", "", array(array("refer_code" => $refercode, "refer_price" => 100)));
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function add_health_line() {
        $uid = $this->input->get_post('user_id');
        $image = $this->input->get_post('image');
        $text = $this->input->get_post('text');
        $type = $this->input->get_post('type');
        if ($uid != null) {

            $data1 = array(
                "user_id" => $uid,
                "image" => $image,
                "text" => $text,
                "type" => $type,
                "created_date" => date('Y-m-d H:i:s'),
            );
            $insert = $this->service_model->master_fun_insert("health_line", $data1);
            if ($insert) {
                if ($type == 'text') {
                    echo $this->json_data("1", "", array(array("msg" => "Text Post successfully")));
                } else {
                    echo $this->json_data("1", "", array(array("msg" => "Image Uploaded successfully")));
                }
            }
        } else {

            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function view_health_line() {
        $uid = $this->input->get_post('user_id');
        $lastid = $this->input->get_post('lastid');
        $size = $this->input->get_post('size');

        if ($uid != null) {


            $data = $this->service_model->health_line($uid);

            $new_data = array();
            foreach ($data as $dt) {
                if ($dt['type'] == "text") {
                    $dt["type"] = 'text';
                } else if ($dt['type'] == "image") {
                    $dt["type"] = 'image';
                }

                $new_data[] = $dt;
            }
            $data = $new_data;



            $data1 = $this->service_model->my_job($uid);
            $new_data = array();
            foreach ($data1 as $dt) {
                $dt["type"] = 'test';
                $new_data[] = $dt;
            }
            $data1 = $new_data;

            $newdata = array();
            foreach ($data1 as $key) {

                $key['test'] = (($key['test'] == "") ? "" : $key['test'] . ',' ) . $key['packge_name'];
                $newdata[] = $key;
            }

            function cmp($a, $b) {
                $ad = strtotime($a['date']);
                $bd = strtotime($b['date']);
                return ($bd - $ad);
            }

            $new = array_merge($data, $newdata);
            usort($new, 'cmp');
            $count = 1;
            $new_data = array();
            foreach ($new as $dt) {
                $dt["number"] = (int) $count++;
                $new_data[] = $dt;
            }
            $new = $new_data;
            $new_data = array();
            if ($lastid == 0) {
                $new_data[] = $new[0];
            }
            for ($k = 1; $k <= $size && count($new) > $k + $lastid; $k++) {

                $new_data[] = $new[$k + $lastid];
            }
            $new = $new_data;
            // echo "<pre>";
            //print_r($new);

            if (empty($new) || $new[0] == "") {

                echo $this->json_data("0", "no data available", "");
            } else {
                echo $this->json_data("1", "", $new);
            }
        } else {

            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function package_list() {
        $city = $this->input->get_post("city");
        /* $data = $this->service_model->package_list();
          if (empty($data)) {
          echo $this->json_data("0", "no data available", "");
          } else {
          echo $this->json_data("1", "", $data);
          } */
        /* Nishit code start */
        $data = $this->service_model->master_fun_get_tbl_val("package_master", array("status" => 1, "is_view" => "1"), array("order", "asc"), array("id", "title", "image,desc_app"));
        $cnt = 0;
        foreach ($data as $key) {
            if ($city != '') {
                $pkg_price = $this->service_model->master_fun_get_tbl_val("package_master_city_price", array("status" => "1", "package_fk" => $key["id"], "city_fk" => $city), array("id", "asc"));
                //echo "select id,title,image,desc_app from package_master_city_price where status ='1' AND package_fk= '".$key["id"]."' AND city_fk='".$city."'<br>";
                //$pkg_price = $this->service_model->get_result("select * from package_master_city_price where status ='1' AND package_fk= '".$key["id"]."' AND city_fk='".$city."'");
                //print_r($pkg_price); die();
                if (!empty($pkg_price)) {
                    $data[$cnt]["a_price"] = $pkg_price[0]["a_price"];
                    $data[$cnt]["d_price"] = $pkg_price[0]["d_price"];
                } else {
                    $data[$cnt]["a_price"] = "0";
                    $data[$cnt]["d_price"] = "0";
                }
            } else {
                $data[$cnt]["a_price"] = "0";
                $data[$cnt]["d_price"] = "0";
            }
            $cnt++;
        }
        if (!empty($data)) {
            echo $this->json_data("1", "", $data);
        } else {
            echo $this->json_data("0", "no data available", "");
        }
        /* Nishit code end */
    }

    function health_feed() {

        $data = $this->service_model->health_feed();
        /*   //$new_data=array();
          //	foreach($data1 as $dt){
          //	$dt["type"]='health_feed';
          //	$new_data[]=$dt;
          //}
          //$data1=$new_data;
          //$data2 = $this->service_model->creative_show();
          //$new_data=array();
          //foreach($data2 as $dt){
          //	$dt["type"]='Creative';
          //	$new_data[]=$dt;
          //	}
          //$data2=$new_data;
          //	$data = array_merge($data1,$data2);
          $MERCHANT_ID = "5669867";
          $MERCHANT_KEY = "IcZRGO7S";
          $SALT = "spjyl2ubfa";
          $MERCHANT_ID = "5669867";
          $MERCHANT_KEY = "IcZRGO7S";
          $SALT = "spjyl2ubfa";
          $MERCHANT_KEY = "gtKFFx";
          $SALT = "eCwWELxi"; */
        //$URL = "https://test.payu.in/_payment";
        $URL = "https://secure.payu.in/_payment";
        $MERCHANT_ID = "5669867";
        $MERCHANT_KEY = "IcZRGO7S";
        $SALT = "spjyl2ubfa";
        /* user app version start */
        $user_id = $this->input->get_post('user_id');
        $version = $this->input->get_post('version');
        $this->service_model->master_fun_update("customer_master", $user_id, array("app_version" => $version));
        /* user app version end */

        /* Check api version */
        $check_version_array = array("android" => array("version" => "40", "compulsory" => "yes", "message" => "New version available please update it and enjoy new feature."), "ios" => array("version" => "2", "compulsory" => "no", "message" => "New version available please update it and enjoy new feature."));
        $configuration = array("MERCHANT_ID" => $MERCHANT_ID, "MERCHANT" => $MERCHANT_KEY, "SALT" => $SALT, 'URl' => $URL, "WalletIsUsedInPackagePayment" => "0");
        $data3 = $this->service_model->master_fun_get_tbl_val("offer_master", array("status" => 1), array("id", "asc"));
        if (empty($data)) {
            echo $this->json_data("0", "no data available", "");
        } else {
            echo $this->json_data("1", "", array(array("data1" => $data3, "data2" => $data, "pay_conf" => $configuration, "version_check" => $check_version_array)));
        }
    }

    function offer() {
        $data = $this->service_model->master_fun_get_tbl_val("offer_master", array("status" => 1), array("id", "asc"));
        if (empty($data)) {
            echo $this->json_data("0", "no data available", "");
        } else {
            echo $this->json_data("1", "", $data);
        }
    }

    function book_package_from_wallet() {
        $this->load->helper("Email");
        $email_cnt = new Email;
        $user_fk = $this->input->get_post('user_id');
        $package = $this->input->get_post('package_id');
        $amount = $this->input->get_post('price');
        $type = $this->input->get_post("type");
        $landmark = $this->input->get_post("landmark");
        $order_id = $this->get_job_id($this->input->get_post("test_city"));
        $address = $this->get_address();
        $date = date('Y-m-d H:i:s');
        $query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $user_fk), array("id", "desc"));
        $total = $query[0]['total'];
        if ($total >= $amount) {
            if ($user_fk != "" && $amount != "") {
                /* Nishit book phlebo start */
                $testforself = $this->input->get_post("testforself");
                $family_mem_id = $this->input->get_post("family_mem_id");
                //$address = $this->input->get_post("address");
                $date1 = $this->input->get_post("date");
                $date1 = explode("/", $date1);
                $date1 = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
                $time_slot_id = $this->input->get_post("time_slot_id");
                $emergency_req = $this->input->get_post("emergency_req");
                if ($testforself == "true") {
                    $b_type = "self";
                    $family_mem_id = 0;
                } else {
                    $b_type = "family";
                }
                if ($emergency_req == "true") {
                    $date1 = date("Y-m-d");
                    $emergency_req = 1;
                    $time_slot_id = 0;
                } else {
                    $emergency_req = 0;
                }
                $booking_fk = $this->service_model->master_fun_insert("booking_info", array("user_fk" => $user_fk, "type" => $b_type, "landmark" => $landmark, "family_member_fk" => $family_mem_id, "address" => $address, "date" => $date1, "time_slot_fk" => $time_slot_id, "emergency" => $emergency_req, "status" => "1", "createddate" => date("Y-m-d H:i:s")));
                /* Nishit book phlebo end */
                $insert1 = $this->service_model->master_fun_insert("job_master", array("cust_fk" => $user_fk, "price" => $amount, "date" => $date, "order_id" => $order_id, "payment_type" => "wallet", "portal" => $type, "booking_info" => $booking_fk));

                $insert = $this->service_model->master_fun_insert("book_package_master", array("cust_fk" => $user_fk, "package_fk" => $package, "date" => $date, "order_id" => $order_id, "job_fk" => $insert1));

                if ($insert) {

                    $data1 = array(
                        "cust_fk" => $user_fk,
                        "debit" => $amount,
                        "total" => $total - $amount,
                        "package_fk" => $insert,
                        "created_time" => date('Y-m-d H:i:s')
                    );
                    $this->assign_phlebo_job($insert1);
                    $file = $this->pdf_invoice($insert1);
                    $this->check_prescription_test($insert1, $user_fk);
                    //$insertwallet = $this->service_model->master_fun_insert("wallet_master", $data1);
                    // Cashback code.

                    $destail = $this->service_model->master_fun_get_tbl_val("customer_master", array("status" => 1, "id" => $user_fk), array("id", "asc"));
                    $query = $this->service_model->master_fun_get_tbl_val("offer_master", array("status" => 1), array("id", "desc"));
                    $caseback_per = $query[0]['caseback_per'];
                    $caseback_amount = ($amount * $caseback_per) / 100;

                    $query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $user_fk), array("id", "desc"));
                    $total1 = $query[0]['total'];
                    $data1 = array(
                        "cust_fk" => $user_fk,
                        "credit" => $caseback_amount,
                        "total" => $total1 + $caseback_amount,
                        "job_fk" => $insert1,
                        "type" => "Case Back",
                        "created_time" => date('Y-m-d H:i:s')
                    );



                    //$insert = $this->service_model->master_fun_insert("wallet_master", $data1);

                    $query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $user_fk), array("id", "desc"));
                    $Current_wallet = $query[0]['total'];
                    $config['mailtype'] = 'html';
                    $this->email->initialize($config);
                    $message = '<div style="padding:0 4%;">
                    <h4 style="color:##c7c7c7;text-decoration: underline;">Cashback Credited Successfully</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Cashback Credited in your Wallet. </p>
<p style="color:#7e7e7e;font-size:13px;"> Rs. ' . $caseback_amount . ' Credited in your Wallet. </p>
		<p style="color:#7e7e7e;font-size:13px;">Your Current Wallet Amount is Rs. ' . $Current_wallet . '</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
                    $message = $email_cnt->get_design($message);
                    $this->email->to($destail[0]['email']);
                    $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
                    $this->email->subject('CashBack');
                    $this->email->message($message);
                    $attatchPath = base_url() . "upload/result/" . $file;
                    $this->email->attach($attatchPath);
                    $this->email->send();

                    echo $this->json_data("1", "", array(array("msg" => "Package Booked successfully")));
                    /* Nishit code start */
                    $package_destail = $this->service_model->master_fun_get_tbl_val("package_master", array("id" => $package), array("id", "asc"));
                    //$this->load->helper("sms");
                    //$notification = new Sms();
                    $mobile = $destail[0]['mobile'];
                    $mobile = ucfirst($destail[0]['full_name']) . "(" . $mobile . ")";
                    $test_package = ucfirst($package_destail[0]["title"]);
                    $sms_message = $this->service_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "test_info"), array("id", "asc"));
                    $sms_message = preg_replace("/{{MOBILE}}/", $mobile, $sms_message[0]["message"]);
                    $sms_message = preg_replace("/{{TESTPACK}}/", 'Package', $sms_message);
                    $sms_message = preg_replace("/{{TESTPACKLIST}}/", $test_package, $sms_message);
                    $sms_message = preg_replace("/{{TOTALPRICE}}/", $amount, $sms_message);
                    $configmobile = $this->config->item('admin_alert_phone');
                    foreach ($configmobile as $p_key) {
                        $mb_length = strlen($p_key);
                        $configmobile = $p_key;
                        //$notification->send($configmobile, $sms_message);
                        $this->service_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $configmobile, "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
                    }
                    /* Nishit code end */
                }
            }
        } else {

            echo $this->json_data("0", "Not Sufficent Amount in Wallet", "");
        }
    }

    function create_ticket() {

        $user_fk = $this->input->get_post('user_id');
        $title = $this->input->get_post('title');
        $message = $this->input->get_post('message');
        $ticket = random_string('alnum', 10);

        if ($user_fk != "" && $title != "" && $message != "") {


            $data1 = array(
                "ticket" => $ticket,
                "user_id" => $user_fk,
                "title" => $title,
            );

            $insert = $this->service_model->master_fun_insert("ticket_master", $data1);

            $data2 = array(
                "ticket_fk" => $insert,
                "message" => $message,
                "type" => 1,
                "created_date" => date('Y-m-d H:i:s')
            );
            $insert1 = $this->service_model->master_fun_insert("message_master", $data2);
            if ($insert1) {

                echo $this->json_data("1", "", array(array("msg" => "Ticket Created Successfully")));
            }
        } else {

            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function medical_id() {
        $user_fk = $this->input->get_post('user_id');
        $dob = $this->input->get_post('dob');
        $note = $this->input->get_post('medical_note');
        $reaction = $this->input->get_post('reaction');
        $medication = $this->input->get_post('medication');
        $blood_type = $this->input->get_post('blood_type');
        $contact = $this->input->get_post('contact');
        $weight = $this->input->get_post('weight');
        $height = $this->input->get_post('height');

        if ($user_fk != "") {

            $data = array(
                "cust_fk" => $user_fk,
                "dob" => $dob,
                "medical_note" => $note,
                "reaction" => $reaction,
                "medication" => $medication,
                "blood_type" => $blood_type,
                "emargency_no" => $contact,
                "weight" => $weight,
                "height" => $height,
            );
            $row = $this->service_model->master_num_rows("medical_id", array("cust_fk" => $user_fk, "status" => 1));
            if ($row == 1) {

                $update = $this->service_model->master_fun_update1("medical_id", array("cust_fk" => $user_fk), $data);
                if ($update) {
                    echo $this->json_data("1", "", array(array("msg" => "Medical Id Updated")));
                }
            } else {
                $insert = $this->service_model->master_fun_insert("medical_id", $data);
                if ($insert) {
                    echo $this->json_data("1", "", array(array("msg" => "Medical Id Updated")));
                }
            }
        }
    }

    function medical_id_view() {

        $user_fk = $this->input->get_post('user_id');
        $data3 = $this->service_model->master_fun_get_tbl_val("customer_master", array("status" => 1, 'id' => $user_fk), array("id", "asc"));
        $name = $data3[0]['full_name'];
        $gender = $data3[0]['gender'];
        $pic = $data3[0]['pic'];
        $data = $this->service_model->medical_id_view($user_fk);
        $data1 = array(
            "full_name" => $name,
            "gender" => $gender,
            "pic" => $pic,
            "cust_fk" => "",
            "dob" => "",
            "medical_note" => "",
            "reaction" => "",
            "medication" => "",
            "blood_type" => "",
            "emargency_no" => "",
            "weight" => "",
            "height" => "",
        );

        if (empty($data)) {
            echo $this->json_data("1", "", array($data1));
        } else {
            echo $this->json_data("1", "", $data);
        }
    }

    function package_payu_success() {
        $this->load->helper("Email");
        $email_cnt = new Email;

        $t = json_encode($this->input->post());
        $payumonyid = $this->input->post('txnid');
        $paydate = $_POST['addedon'];
        $amount = $_POST['net_amount_debit'];
        $status = $_POST['status'];
        $type = $this->input->get_post("type");
        $packageid = $this->uri->segment(4);
        $wallet_amt = $this->uri->segment(5);
        $method = $this->uri->segment(6);
        $userfk1 = $this->uri->segment(7);
        /* Nishit book phlebo start */
        $testforself = $this->uri->segment(8);
        $family_mem_id = $this->uri->segment(9);
        $address = $this->uri->segment(10);
        $date1 = $this->uri->segment(11);
        //$date1 = explode("/", $date1);
        //$date1 = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $time_slot_id = $this->uri->segment(12);
        $emergency_req = $this->uri->segment(13);
        $test_city = $this->uri->segment(14);
        if (empty($test_city)) {
            $test_city = 1;
        }
        if ($testforself == "true") {
            $b_type = "self";
            $family_mem_id = 0;
        } else {
            $b_type = "family";
        }
        if ($emergency_req == "true") {
            $date1 = date("Y-m-d");
            $emergency_req = 1;
            $time_slot_id = 0;
        } else {
            $emergency_req = 0;
        }
        //print_r(array("user_fk" => $userfk1, "type" => $b_type, "family_member_fk" => $family_mem_id, "address" => $address, "date" => $date1, "time_slot_fk" => $time_slot_id, "emergency" => $emergency_req, "status" => "1", "createddate" => date("Y-m-d H:i:s")));die();
        $booking_fk = $this->service_model->master_fun_insert("booking_info", array("user_fk" => $userfk1, "type" => $b_type, "family_member_fk" => $family_mem_id, "address" => $address, "date" => $date1, "time_slot_fk" => $time_slot_id, "emergency" => $emergency_req, "status" => "1", "createddate" => date("Y-m-d H:i:s")));
        /* Nishit book phlebo end */
        $order_id = $this->get_job_id($test_city);
        $date = date('Y-m-d H:i:s');

        $data = array("cust_fk" => $userfk1, "package_fk" => $packageid, "date" => $date, "order_id" => $order_id);
        $package_user = $this->service_model->master_fun_get_tbl_val("package_master_city_price", array("status" => "1", "package_fk" => $packageid, "city_fk" => $test_city), array("id", "asc"));
        $price = $package_user[0]['d_price'];

        $insert1 = $this->service_model->master_fun_insert("job_master", array("cust_fk" => $userfk1, "price" => $price, "date" => $date, "order_id" => $order_id, "payment_type" => "PayUMoney", "status" => 6, "portal" => $type, "booking_info" => $booking_fk, "test_city" => $test_city, "address" => $address));

        $insert = $this->service_model->master_fun_insert("book_package_master", array("cust_fk" => $userfk1, "package_fk" => $packageid, "date" => $date, "order_id" => $order_id, "job_fk" => $insert1));
        $this->load->library("util");
        $util = new Util;
        $util->check_active_package($packageid, $insert1, $userfk1);
        $package_user = $this->service_model->master_fun_get_tbl_val("package_master", array("status" => 1, "id" => $packageid), array("id", "asc"));
        $query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $userfk1), array("id", "desc"));
        $destail = $this->service_model->master_fun_get_tbl_val("customer_master", array("status" => 1, "id" => $userfk1), array("id", "asc"));
        $total = $query[0]['total'];
        if ($wallet_amt > 0) {
            if ($method == "wallet") {

                $data1 = array(
                    "cust_fk" => $userfk1,
                    "debit" => $wallet_amt,
                    "total" => $total - $wallet_amt,
                    "package_fk" => $insert,
                    "created_time" => date('Y-m-d H:i:s')
                );
                //$insert12 = $this->service_model->master_fun_insert("wallet_master", $data1);
            }
        }


        // Cashback code
        $destail = $this->service_model->master_fun_get_tbl_val("customer_master", array("status" => 1, "id" => $userfk1), array("id", "asc"));
        $query = $this->service_model->master_fun_get_tbl_val("offer_master", array("status" => 1), array("id", "desc"));
        $caseback_per = $query[0]['caseback_per'];
        $caseback_amount = ($price * $caseback_per) / 100;

        $query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $userfk1), array("id", "desc"));
        $total1 = $query[0]['total'];
        $data1 = array(
            "cust_fk" => $userfk1,
            "credit" => $caseback_amount,
            "total" => $total1 + $caseback_amount,
            "job_fk" => $insert1,
            "type" => "Case Back",
            "created_time" => date('Y-m-d H:i:s')
        );

        //$insert = $this->service_model->master_fun_insert("wallet_master", $data1);
        $this->assign_phlebo_job($insert1);
        $file = $this->pdf_invoice($insert1);
        $this->check_prescription_test($insert1, $userfk1);
        $query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $userfk1), array("id", "desc"));
        $Current_wallet = $query[0]['total'];
        $config['mailtype'] = 'html';
        $this->email->initialize($config);
        $message = '<div style="padding:0 4%;">
                    <h4><b>Dear </b>' . $destail[0]['full_name'] . '</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Cashback Credited in your Wallet. </p>
                       
<p style="color:#7e7e7e;font-size:13px;"> Rs. ' . $caseback_amount . ' Credited in your Wallet. </p>
		<p style="color:#7e7e7e;font-size:13px;">Your Current Wallet Amount is Rs. ' . $Current_wallet . '</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
        $message = $email_cnt->get_design($message);
        $this->email->to($destail[0]['email']);
        $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
        $this->email->subject('CashBack');
        $this->email->message($message);
        //$this->email->send();
        // end cashback code
        $data1 = array("payomonyid" => $payumonyid,
            "amount" => $amount,
            "paydate" => $paydate,
            "status" => $status,
            "uid" => $userfk1,
            "package_fk" => $insert,
            "type" => "package",
            "data" => $t,
        );
        $insert = $this->service_model->master_fun_insert("payment", $data1);

        $config['mailtype'] = 'html';
        $this->email->initialize($config);
        $family_member_name = $this->service_model->get_family_member_name($insert1);
        if (!empty($family_member_name)) {
            $destail[0]['full_name'] = $family_member_name[0]["name"];
        }
        $message = '<div style="padding:0 4%;">
                    <h4><b>Dear </b>' . $destail[0]['full_name'] . '</h4>
                        <p style="color:#7e7e7e;font-size:13px;">' . ucfirst($package_user[0]['title']) . ' Package Add successfully. </p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
        $message = $email_cnt->get_design($message);
        $this->email->to($destail[0]['email']);
        $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
        $this->email->subject('Package Successfully Added');
        $this->email->message($message);
        $attatchPath = base_url() . "upload/result/" . $file;
        $this->email->attach($attatchPath);
        $this->email->send();
        $family_member_name = $this->service_model->get_family_member_name($insert1);
        if (!empty($family_member_name)) {
            $c_email = $family_member_name[0]["email"];
            if (!empty($c_email)) {
                $this->email->to($c_email);
                $this->email->cc("booking.airmed@gmail.com");
                $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
                $this->email->subject('Package Book Successfully');
                $this->email->message($message);
                $this->email->send();
            }
        }
        /* Nishit code start */
        $package_destail = $this->service_model->master_fun_get_tbl_val("package_master", array("id" => $packageid), array("id", "asc"));
        //$this->load->helper("sms");
        //$notification = new Sms();
        $mobile = $destail[0]['mobile'];
        $mobile = ucfirst($destail[0]['full_name']) . "(" . $mobile . ")";
        $test_package = ucfirst($package_destail[0]["title"]);
        $sms_message = $this->service_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "test_info"), array("id", "asc"));
        $sms_message = preg_replace("/{{MOBILE}}/", $mobile, $sms_message[0]["message"]);
        $sms_message = preg_replace("/{{TESTPACK}}/", 'Package', $sms_message);
        $sms_message = preg_replace("/{{TESTPACKLIST}}/", $test_package, $sms_message);
        $sms_message = preg_replace("/{{TOTALPRICE}}/", $amount, $sms_message);
        $configmobile = $this->config->item('admin_alert_phone');
        foreach ($configmobile as $p_key) {
            $mb_length = strlen($p_key);
            $configmobile = $p_key;
            //$notification->send($configmobile, $sms_message);
            $this->service_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $configmobile, "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
        }
        /* Delhi and gurgaon booking alert sms start */
        $test_city = $this->uri->segment(14);
        if ($test_city == 4 || $test_city == 5) {
            $sms_message = '';
            $sms_message = $this->service_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "delhi_booking"), array("id", "asc"));
            $city_name = $this->service_model->master_fun_get_tbl_val("test_cities", array('status' => 1, "id" => $test_city), array("id", "asc"));
            $sms_message = preg_replace("/{{MOBILE}}/", $mobile, $sms_message[0]["message"]);
            $sms_message = preg_replace("/{{CITY}}/", $city_name[0]["name"], $sms_message);
            $sms_message = preg_replace("/{{TESTPACK}}/", 'Package', $sms_message);
            $sms_message = preg_replace("/{{TESTPACKLIST}}/", $test_package, $sms_message);
            $sms_message = preg_replace("/{{TOTALPRICE}}/", $amount, $sms_message);
            $configmobile = $this->config->item('booking_alert_phone');
            foreach ($configmobile as $p_key) {
                //$notification::send($configmobile, $sms_message);
                $this->service_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $p_key, "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
            }
        }
        /* Delhi and gurgaon booking alert sms end */
        /* Nishit code end */
        $data['payuMoneyId'] = $_POST['payuMoneyId'];
        $this->load->view('success1', $data);
    }

    function my_package() {
        $user_fk = $this->input->get_post('user_id');
        $data = $this->service_model->my_package($user_fk);
        if (empty($data)) {
            echo $this->json_data("0", "no data available", "");
        } else {
            echo $this->json_data("1", "", $data);
        }
    }

    function doctor_list() {
        $data = $this->service_model->master_fun_get_tbl_val("doctor_master", array("status" => 1), array("id", "asc"));
        if (empty($data)) {
            echo $this->json_data("0", "no data available", "");
        } else {
            echo $this->json_data("1", "", $data);
        }
    }

    function search_doctor() {
        $name = $this->input->get_post('doctorname');
        if ($name != "") {
            $data = $this->service_model->search_doctor($name);
            if (empty($data)) {
                echo $this->json_data("0", "no data available", "");
            } else {
                echo $this->json_data("1", "", $data);
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function post_bug() {
        $this->load->library('email');
        $message1 = $this->input->get_post('message');
        if ($message1 != "") {
            $config['mailtype'] = 'html';

            $this->email->initialize($config);
            $this->email->to("ishani@virtualheight.com");
            $this->email->from("info@virtualheight.com", "info");
            $this->email->subject("bug");
            $this->email->message($message1);
            $this->email->send();
            echo $this->json_data("1", "", array(array("msg" => "Your Report Submitted Successfully")));
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function json_data($status, $error_msg, $data = NULL) {
        if ($data == NULL) {
            $data = array();
        }
        $final = array("status" => $status, "error_msg" => $error_msg, "data" => $data);
        return str_replace("null", '" "', json_encode($final));
    }

    function cash_on_delivery() {
        $this->load->helper("Email");
        $email_cnt = new Email;

        $uid = $this->input->get_post('uid');
        $address = $this->input->get_post('address');
        $city = $this->input->get_post('city');
        $state = $this->input->get_post('state');
        $pin = $this->input->get_post('pin');
        $select_tests = $this->input->get_post('select_tests');
        $total = $this->input->get_post('total');
        $docid = $this->input->get_post('docid');
        $landmark = $this->input->get_post("landmark");
        $reference_by = $this->input->get_post('reference_by');
        $method = $this->input->get_post('wallet');
        $test_city = $this->input->get_post('test_city');
        $address = $this->get_address();
        if (empty($test_city)) {
            $test_city = 1;
        }
        $type = $this->input->get_post("type");
        $order_id = $this->get_job_id($this->input->get_post('test_city'));
        $date = date('Y-m-d H:i:s');
        if ($uid != NULL && $select_tests != Null && $total != Null) {
            /* Nishit book phlebo start */
            $testforself = $this->input->get_post("testforself");
            $family_mem_id = $this->input->get_post("family_mem_id");
            //$address = $this->input->get_post("address");
            $date1 = $this->input->get_post("date");
            $date1 = explode("/", $date1);
            $date1 = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
            $time_slot_id = $this->input->get_post("time_slot_id");
            $emergency_req = $this->input->get_post("emergency_req");
            if ($testforself == "true") {
                $b_type = "self";
                $family_mem_id = 0;
            } else {
                $b_type = "family";
            }
            if ($emergency_req == "true") {
                $date1 = date("Y-m-d");
                $emergency_req = 1;
                $time_slot_id = 0;
            } else {
                $emergency_req = 0;
            }
            //echo "<pre>";print_r(array("user_fk" => $uid, "type" => $b_type, "family_member_fk" => $family_mem_id, "address" => $address, "date" => $date1, "time_slot_fk" => $time_slot_id, "emergency" => $emergency_req, "status" => "1", "createddate" => date("Y-m-d H:i:s"))); die();
            $booking_fk = $this->service_model->master_fun_insert("booking_info", array("user_fk" => $uid, "type" => $b_type, "landmark" => $landmark, "family_member_fk" => $family_mem_id, "address" => $address, "date" => $date1, "time_slot_fk" => $time_slot_id, "emergency" => $emergency_req, "status" => "1", "createddate" => date("Y-m-d H:i:s")));
            /* Nishit book phlebo end */
            $data = array(
                "order_id" => $order_id,
                "cust_fk" => $uid,
                "date" => $date,
                "price" => $total,
                "status" => '1',
                "payment_type" => "Cash On Delivery",
                "address" => $address,
                "city" => $city,
                "state" => $state,
                "pin" => $pin,
                "doctor" => $docid,
                "other_reference" => $reference_by,
                "test_city" => $test_city,
                "portal" => $this->input->get_post("type"),
                "booking_info" => $booking_fk
            );

            $insert = $this->service_model->master_fun_insert("job_master", $data);
            if ($insert) {

                // Referral amount for first test book//
                $data = $this->service_model->master_fun_get_tbl_val("job_master", array("cust_fk" => $uid), array("id", "asc"));
                $count = count($data);
                if ($count == 1) {

                    $refdata = $this->service_model->master_fun_get_tbl_val("refer_code_master", array("status" => 1, "cust_fk" => $uid), array("id", "asc"));
                    $userdcode = $refdata[0]['used_code'];
                    if ($usercode != "") {
                        $refdata1 = $this->service_model->master_fun_get_tbl_val("refer_code_master", array("status" => 1, "used_code" => $userdcode), array("id", "asc"));
                    }

                    if (!empty($refdata1)) {

                        $refdata1 = $this->service_model->master_fun_get_tbl_val("refer_code_master", array("status" => 1, "refer_code" => $userdcode), array("id", "asc"));
                        $usedcust_fk = $refdata1[0]['cust_fk'];
                        $query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $usedcust_fk), array("id", "desc"));
                        $wallettotal = $query[0]['total'];
                        //die();
                        $datacust = $this->service_model->master_fun_get_tbl_val("customer_master", array("id" => $usedcust_fk), array("id", "asc"));
                        $refemail = $datacust[0]['email'];
                        $ref_name = $datacust[0]['full_name'];

                        $data = array(
                            "cust_fk" => $usedcust_fk,
                            "credit" => 100,
                            "total" => $wallettotal + 100,
                            "type" => "referral code",
                            "created_time" => date('Y-m-d H:i:s')
                        );
                        $insert1 = $this->service_model->master_fun_insert("wallet_master", $data);



                        $config['mailtype'] = 'html';
                        $this->email->initialize($config);

                        $message = '<div style="padding:0 4%;">
                    <h4><b>You have one more refferal</b></h4>
                        <p><b>Dear </b>  ' . ucfirst($ref_name) . '</p>
                        <p style="color:#7e7e7e;font-size:13px;">Congratulation You have One more refferal  </p>
                        <p style="color:#7e7e7e;font-size:13px;">Rs.100 has been Credited in your wallet</p>
                </div>';
                        $message = $email_cnt->get_design($message);
                        $this->email->to($refemail);
                        $this->email->from("donotreply@airmedpathlabs.com", "AirmedLabs");
                        $this->email->subject("Refferal Amount Credited");
                        $this->email->message($message);
                        $this->email->send();
                    }
                }



                //end referral for first test book


                $query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $uid), array("id", "desc"));
                $wallettotal = $query[0]['total'];
                $payfrom_wallet = '';
                $payemnttype = "Cash on blood Collection";
                if ($method == "yes") {
                    if ($wallettotal > 0) {
                        if ($wallettotal >= $total) {

                            $data1 = array(
                                "cust_fk" => $uid,
                                "debit" => $total,
                                "total" => $wallettotal - $total,
                                "job_fk" => $insert,
                                "created_time" => date('Y-m-d H:i:s')
                            );

                            $payable = 0;
                            $payfrom_wallet = '<p style="color:#7e7e7e;font-size:13px;"> You paid From Your Wallet Rs. ' . $total . '  </p>
						<p style="color:#7e7e7e;font-size:13px;"> Your Payable Amount Rs. ' . $payable . '  </p> 
						';
                            $payemnttype = "Paid From Wallet";
                        } else {

                            $data1 = array(
                                "cust_fk" => $uid,
                                "debit" => $wallettotal,
                                "total" => 0,
                                "job_fk" => $insert,
                                "created_time" => date('Y-m-d H:i:s')
                            );
                            $payable = $total - $wallettotal;
                            $payfrom_wallet = '<p style="color:#7e7e7e;font-size:13px;"> You paid From Your Wallet  Rs. ' . $wallettotal . '  </p>
				<p style="color:#7e7e7e;font-size:13px;"> Your Payable Amount Rs. ' . $payable . '  </p>';
                            $payemnttype = "Cash on blood Collection";
                        }

                        $insert12 = $this->service_model->master_fun_insert("wallet_master", $data1);
                        $total = $payable;
                        $update = $this->service_model->master_fun_update1("job_master", array("id" => $insert), array("payable_amount" => $payable));
                    } else {

                        $update = $this->service_model->master_fun_update1("job_master", array("id" => $insert), array("payable_amount" => $total));
                    }
                } else {

                    $update = $this->service_model->master_fun_update1("job_master", array("id" => $insert), array("payable_amount" => $total));
                }

                $test = explode(",", $select_tests);
                foreach ($test as $key) {
                    $this->service_model->master_fun_insert("job_test_list_master", array("job_fk" => $insert, "test_fk" => $key));
                }


                $test_name_mail = array();
                for ($i = 0; $i < count($test); $i++) {
                    // $insert_code = $this->service_model->master_fun_insert("job_test_list_master", array("job_fk" => $insert, "test_fk" => $test[$i]));
                    //$data = $this->service_model->master_fun_get_tbl_val("test_master", array("status" => 1, 'id' => $test[$i]), array("id", "asc"));
                    $data = $this->service_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $test_city . "' AND `test_master_city_price`.`status`='1' AND `test_master`.`id`='" . $test[$i] . "'");
                    $price = $price + $data[0]['price'];
                    $test_name_mail[$i] = $data[0]['test_name'];
                }
                /* collection charge start */
                $j_total_price = $total;
                $collection_charge = 0;
                if ($price < 300) {
                    $j_total_price = $j_total_price + 100;
                    $collection_charge = 1;
                }
                /* collection charge end */
                $update = $this->service_model->master_fun_update1("job_master", array("id" => $insert), array("collection_charge" => $collection_charge));
                $destail = $this->service_model->master_fun_get_tbl_val("customer_master", array("status" => 1, "id" => $uid), array("id", "asc"));
                /* sms send start */
                $user = $this->service_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "id" => $uid), array("id", "asc"));
                $family_member_name = $this->service_model->get_family_member_name($insert);
                if (!empty($family_member_name)) {
                    $c_name = $family_member_name[0]["name"];
                    $cmobile = $family_member_name[0]["phone"];
                    if (empty($cmobile)) {
                        $cmobile = $user[0]["mobile"];
                    }
                } else {
                    $c_name = $user[0]["full_name"];
                    $cmobile = $user[0]["mobile"];
                }
                $sms_message = $this->service_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "book_login"), array("id", "asc"));

                $sms_message = preg_replace("/{{NAME}}/", ucfirst($c_name), $sms_message[0]["message"]);
                $sms_message = preg_replace("/{{MOBILE}}/", ucfirst($user[0]['mobile']), $sms_message);
                $sms_message = preg_replace("/{{TESTPACK}}/", 'Test', $sms_message);
                $sms_message = preg_replace("/{{TOTALPRICE}}/", $total, $sms_message);
                $this->load->helper("sms");
                $notification = new Sms();
                $mobile = $user[0]['mobile'];
                if ($cmobile != NULL) {
                    $notification->send($cmobile, $sms_message);
                }

                if (!empty($family_member_name)) {
                    $this->service_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $mobile, "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
                }
                /* Delhi and gurgaon booking alert sms start */
                $test_city = $this->input->get_post('test_city');
                if ($test_city == 4 || $test_city == 5) {
                    $sms_message = '';
                    $sms_message = $this->service_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "delhi_booking"), array("id", "asc"));
                    $city_name = $this->service_model->master_fun_get_tbl_val("test_cities", array('status' => 1, "id" => $test_city), array("id", "asc"));
                    $sms_message = preg_replace("/{{MOBILE}}/", $mobile, $sms_message[0]["message"]);
                    $sms_message = preg_replace("/{{CITY}}/", $city_name[0]["name"], $sms_message);
                    $sms_message = preg_replace("/{{TESTPACK}}/", 'Test', $sms_message);
                    $sms_message = preg_replace("/{{TESTPACKLIST}}/", implode($test_name_mail, ','), $sms_message);
                    $sms_message = preg_replace("/{{TOTALPRICE}}/", $total, $sms_message);
                    $configmobile = $this->config->item('booking_alert_phone');
                    foreach ($configmobile as $p_key) {
                        //$notification::send($configmobile, $sms_message);
                        $this->service_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $p_key, "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
                    }
                }
                /* Delhi and gurgaon booking alert sms end */
                //$this->service_model->master_fun_insert("test", array("test"=>$get_phone."-".$sms_message));
                /* sms send end */
                $config['mailtype'] = 'html';
                $this->email->initialize($config);
                $family_member_name = $this->service_model->get_family_member_name($insert);
                if (!empty($family_member_name)) {
                    $destail[0]['full_name'] = $family_member_name[0]["name"];
                }
                $file = $this->pdf_invoice($insert);
                $timeslot = $this->service_model->get_val("SELECT ts.start_time,ts.end_time,b.date FROM booking_info as b left join phlebo_time_slot as ts on b.time_slot_fk=ts.id WHERE ts.status='1' AND b.status='1' AND b.id='" . $booking_fk . "'");
                $s_time = date('h:i A', strtotime($timeslot[0]["start_time"]));
                $e_time = date('h:i A', strtotime($timeslot[0]["end_time"]));
                $datebb = date("d F,Y", strtotime($timeslot[0]['date']));
                $message = '<div style="padding:0 4%;">
                    <h4><b>Dear </b>' . $destail[0]['full_name'] . '</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Test has been book successfully. </p>
                        <p style="color:#7e7e7e;font-size:13px;">You booked ' . implode($test_name_mail, ',') . '</p>
                            <p style="color:#7e7e7e;font-size:13px;"> Sample Collection time :  ' . $datebb . ' ' . $s_time . '-' . $e_time . '  </p>  
<p style="color:#7e7e7e;font-size:13px;"> Your Test Amount is Rs. ' . $price . '  </p>
' . $payfrom_wallet . '
        <p style="color:#7e7e7e;font-size:13px;"> Payment Type : ' . $payemnttype . '</p>
		<p style="color:#7e7e7e;font-size:13px;"> Mobile : ' . $destail[0]['mobile'] . '</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
                $message = $email_cnt->get_design($message);
                $this->email->to($destail[0]['email']);
                $this->email->cc($this->config->item('admin_booking_email'));
                $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
                $this->email->subject('Test Book Successfully');
                $this->email->message($message);
                $attatchPath = base_url() . "upload/result/" . $file;
                $this->email->attach($attatchPath);
                $this->email->send();
                $family_member_name = $this->service_model->get_family_member_name($insert);
                if (!empty($family_member_name)) {
                    $c_email = $family_member_name[0]["email"];
                    if (!empty($c_email)) {
                        $this->email->to($c_email);
                        //$this->email->cc($this->config->item('admin_booking_email'));
                        $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
                        $this->email->subject('Test Book Successfully');
                        $this->email->message($message);
                        $this->email->send();
                    }
                }
                /* Nishit code start */
                $mobile = $destail[0]['mobile'];
                $mobile = ucfirst($destail[0]['full_name']) . "(" . $mobile . ")";
                $test_package = implode($test_name_mail, ',');
                $sms_message = $this->service_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "test_info"), array("id", "asc"));
                $sms_message = preg_replace("/{{MOBILE}}/", $mobile, $sms_message[0]["message"]);
                $sms_message = preg_replace("/{{TESTPACK}}/", 'Test', $sms_message);
                $sms_message = preg_replace("/{{TESTPACKLIST}}/", $test_package, $sms_message);
                $sms_message = preg_replace("/{{TOTALPRICE}}/", $total, $sms_message);
                $configmobile = $this->config->item('admin_alert_phone');
                foreach ($configmobile as $p_key) {
                    $mb_length = strlen($p_key);
                    $configmobile = $p_key;
                    //$notification->send($configmobile, $sms_message);
                    $this->service_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $configmobile, "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
                }
                /* Nishit code end */
                $this->assign_phlebo_job($insert);

                $this->check_prescription_test($insert, $uid);
                echo $this->json_data("1", "", array(array("msg" => "Your request successfully send.")));
            }
        } else {
            echo $this->json_data("0", "All fields are required.", "");
        }
    }

    function cash_on_delivery_package() {
        $this->load->helper("Email");
        $email_cnt = new Email;

        $uid = $this->input->get_post('uid');
        $address = $this->input->get_post('address');
        $city = $this->input->get_post('city');
        $state = $this->input->get_post('state');
        $pin = $this->input->get_post('pin');
        $select_package = $this->input->get_post('select_package');
        $total = $this->input->get_post('total');
        $docid = $this->input->get_post('docid');
        $reference_by = $this->input->get_post('reference_by');
        $landmark = $this->input->get_post("landmark");
        $method = $this->input->get_post('wallet');
        $test_city = $this->input->get_post('test_city');
        $address = $this->get_address();
        if (empty($test_city)) {
            $test_city = 1;
        }
        $type = $this->input->get_post("type");
        $order_id = $this->get_job_id($this->input->get_post('test_city'));
        $date = date('Y-m-d H:i:s');
        if ($uid != NULL && $select_package != Null && $total != Null) {
            /* Nishit book phlebo start */
            $testforself = $this->input->get_post("testforself");
            $family_mem_id = $this->input->get_post("family_mem_id");
            //$address = $this->input->get_post("address");
            $date1 = $this->input->get_post("date");
            $date1 = explode("/", $date1);
            $date1 = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
            $time_slot_id = $this->input->get_post("time_slot_id");
            $emergency_req = $this->input->get_post("emergency_req");
            if ($testforself == "true") {
                $b_type = "self";
                $family_mem_id = 0;
            } else {
                $b_type = "family";
            }
            if ($emergency_req == "true") {
                $date1 = date("Y-m-d");
                $emergency_req = 1;
                $time_slot_id = 0;
            } else {
                $emergency_req = 0;
            }
            //echo "<pre>";print_r(array("user_fk" => $uid, "type" => $b_type, "family_member_fk" => $family_mem_id, "address" => $address, "date" => $date1, "time_slot_fk" => $time_slot_id, "emergency" => $emergency_req, "status" => "1", "createddate" => date("Y-m-d H:i:s"))); die();
            $booking_fk = $this->service_model->master_fun_insert("booking_info", array("user_fk" => $uid, "type" => $b_type, "landmark" => $landmark, "family_member_fk" => $family_mem_id, "address" => $address, "date" => $date1, "time_slot_fk" => $time_slot_id, "emergency" => $emergency_req, "status" => "1", "createddate" => date("Y-m-d H:i:s")));
            /* Nishit book phlebo end */
            $data = array(
                "order_id" => $order_id,
                "cust_fk" => $uid,
                "date" => $date,
                "price" => $total,
                "status" => '1',
                "payment_type" => "Cash On Delivery",
                "address" => $address,
                "city" => $city,
                "state" => $state,
                "pin" => $pin,
                "doctor" => $docid,
                "other_reference" => $reference_by,
                "test_city" => $test_city,
                "portal" => $type,
                "booking_info" => $booking_fk
            );
            $insert = $this->service_model->master_fun_insert("job_master", $data);
            if ($insert) {
                //$insert1 = $this->service_model->master_fun_insert("job_master", array("cust_fk" => $uid,"price"=>$total, "date" => $date, "order_id" => $order_id, "payment_type" => "Cash On Delivery"));
                // Referral amount for first test book//
                $data = $this->service_model->master_fun_get_tbl_val("job_master", array("cust_fk" => $uid), array("id", "asc"));
                $count = count($data);
                if ($count == 1) {

                    $refdata = $this->service_model->master_fun_get_tbl_val("refer_code_master", array("status" => 1, "cust_fk" => $uid), array("id", "asc"));
                    $userdcode = $refdata[0]['used_code'];
                    if ($usercode != "") {
                        $refdata1 = $this->service_model->master_fun_get_tbl_val("refer_code_master", array("status" => 1, "used_code" => $userdcode), array("id", "asc"));
                    }

                    if (!empty($refdata1)) {
                        $refdata1 = $this->service_model->master_fun_get_tbl_val("refer_code_master", array("status" => 1, "refer_code" => $userdcode), array("id", "asc"));
                        $usedcust_fk = $refdata1[0]['cust_fk'];
                        $query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $usedcust_fk), array("id", "desc"));
                        $wallettotal = $query[0]['total'];
                        //die();
                        $datacust = $this->service_model->master_fun_get_tbl_val("customer_master", array("id" => $usedcust_fk), array("id", "asc"));
                        $refemail = $datacust[0]['email'];
                        $ref_name = $datacust[0]['full_name'];

                        $data = array(
                            "cust_fk" => $usedcust_fk,
                            "credit" => 100,
                            "total" => $wallettotal + 100,
                            "type" => "referral code",
                            "created_time" => date('Y-m-d H:i:s')
                        );
                        $insert1 = $this->service_model->master_fun_insert("wallet_master", $data);

                        $config['mailtype'] = 'html';
                        $this->email->initialize($config);

                        $message = '<div style="padding:0 4%;">
                    <h4><b>You have one more refferal</b></h4>
                        <p><b>Dear </b> ' . ucfirst($ref_name) . '</p>
                        <p style="color:#7e7e7e;font-size:13px;">Congratulation You have One more refferal  </p>
                        <p style="color:#7e7e7e;font-size:13px;">Rs.100 has been Credited in your wallet</p>
                </div>';
                        $message = $email_cnt->get_design($message);
                        $this->email->to($refemail);
                        $this->email->from("donotreply@airmedpathlabs.com", "AirmedLabs");
                        $this->email->subject("Refferal Amount Credited");
                        $this->email->message($message);
                        $this->email->send();
                    }
                }


                //end referral for first test book
                /* Check collecion charge start */
                $data['package_price'] = $this->service_model->master_fun_get_tbl_val("package_master_city_price", array('package_fk' => $select_package, "city_fk" => $test_city, "status" => "1"), array("id", "asc"));
                $collection_charge = 0;
                if ($data['package_price'][0]["d_price"] < 300) {
                    $collection_charge = 1;
                }
                /* Check collection charge end */

                $query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $uid), array("id", "desc"));
                $wallettotal = $query[0]['total'];
                $payfrom_wallet = '';
                $payemnttype = "Cash on blood Collection";
                if ($method == "yes") {
                    if ($wallettotal > 0) {
                        if ($wallettotal >= $total) {

                            $data1 = array(
                                "cust_fk" => $uid,
                                "debit" => $total,
                                "total" => $wallettotal - $total,
                                "job_fk" => $insert,
                                "created_time" => date('Y-m-d H:i:s')
                            );
                            $payable = 0;
                            /* $payfrom_wallet = '<p style="color:#7e7e7e;font-size:13px;"> You paid From Your Wallet Rs. ' . $total . '  </p>
                              <p style="color:#7e7e7e;font-size:13px;"> Your Payable Amount Rs. ' . $payable . '  </p>'; */
                            $payfrom_wallet = '<p style="color:#7e7e7e;font-size:13px;"> Your Payable Amount Rs. ' . $payable . '  </p>';
                            $payemnttype = "Paid From Wallet";
                        } else {
                            $data1 = array(
                                "cust_fk" => $uid,
                                "debit" => $wallettotal,
                                "total" => 0,
                                "job_fk" => $insert,
                                "created_time" => date('Y-m-d H:i:s')
                            );
                            $payable = $total - $wallettotal;
                            /* $payfrom_wallet = '<p style="color:#7e7e7e;font-size:13px;"> You paid From Your Wallet Rs. ' . $wallettotal . '  </p>
                              <p style="color:#7e7e7e;font-size:13px;"> Your Payable Amount Rs. ' . $payable . '  </p>'; */
                            $payfrom_wallet = '<p style="color:#7e7e7e;font-size:13px;"> Your Payable Amount Rs. ' . $payable . '  </p>';
                            $payemnttype = "Cash on blood Collection";
                        }

                        $insert12 = $this->service_model->master_fun_insert("wallet_master", $data1);
                        $update = $this->service_model->master_fun_update1("job_master", array("id" => $insert), array("payable_amount" => $total, "collection_charge" => $collection_charge));
                    } else {
                        $update = $this->service_model->master_fun_update1("job_master", array("id" => $insert), array("payable_amount" => $total, "collection_charge" => $collection_charge));
                    }
                } else {

                    $update = $this->service_model->master_fun_update1("job_master", array("id" => $insert), array("payable_amount" => $total, "collection_charge" => $collection_charge));
                }

                $test = explode(",", $select_package);
                $this->load->library("util");
                $util = new Util;
                foreach ($test as $key) {
                    $this->service_model->master_fun_insert("book_package_master", array("job_fk" => $insert, "date" => $date, "order_id" => $order_id, "package_fk" => $key, "cust_fk" => $uid, "type" => "2"));
                    $util->check_active_package($key, $insert, $uid);
                }

                $data = $this->service_model->master_fun_get_tbl_val("package_master", array("status" => 1, 'id' => $select_package), array("id", "asc"));
                $packname = $data[0]['title'];
                $destail = $this->service_model->master_fun_get_tbl_val("customer_master", array("status" => 1, "id" => $uid), array("id", "asc"));

                $config['mailtype'] = 'html';
                $this->email->initialize($config);
                $family_member_name = $this->service_model->get_family_member_name($insert);
                if (!empty($family_member_name)) {
                    $destail[0]['full_name'] = $family_member_name[0]["name"];
                }
                $file = $this->pdf_invoice($insert);
                $message = '<div style="padding:0 4%;">
                    <h4><b>Dear </b>' . $destail[0]['full_name'] . '</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Package has been book successfully. </p>
                        <p style="color:#7e7e7e;font-size:13px;">You booked ' . $packname . '</p>
<p style="color:#7e7e7e;font-size:13px;"> Your Package Amount is Rs. ' . $total . '  </p>
' . $payfrom_wallet . '
        <p style="color:#7e7e7e;font-size:13px;"> Payment Type : ' . $payemnttype . '</p>
		<p style="color:#7e7e7e;font-size:13px;"> Mobile : ' . $destail[0]['mobile'] . '</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
                $message = $email_cnt->get_design($message);
                $this->email->to($destail[0]['email']);
                $this->email->cc($this->config->item('admin_booking_email'));
                $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
                $this->email->subject('Package Book Successfully');
                $this->email->message($message);
                $attatchPath = base_url() . "upload/result/" . $file;
                $this->email->attach($attatchPath);
                $this->email->send();
                $family_member_name = $this->service_model->get_family_member_name($insert);
                if (!empty($family_member_name)) {
                    $c_email = $family_member_name[0]["email"];
                    if (!empty($c_email)) {
                        $this->email->to($c_email);
                        $this->email->cc($this->config->item('admin_booking_email'));
                        $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
                        $this->email->subject('Package Book Successfully');
                        $this->email->message($message);
                        $this->email->send();
                    }
                }
                /* Nishit code start */
                $this->load->helper("sms");
                $notification = new Sms();
                $mobile = $destail[0]['mobile'];
                $mobile = ucfirst($destail[0]['full_name']) . "(" . $mobile . ")";
                $test_package = ucfirst($packname);
                $sms_message = $this->service_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "test_info"), array("id", "asc"));
                $sms_message = preg_replace("/{{MOBILE}}/", $mobile, $sms_message[0]["message"]);
                $sms_message = preg_replace("/{{TESTPACK}}/", 'Package', $sms_message);
                $sms_message = preg_replace("/{{TESTPACKLIST}}/", $test_package, $sms_message);
                $sms_message = preg_replace("/{{TOTALPRICE}}/", $total, $sms_message);
                $configmobile = $this->config->item('admin_alert_phone');
                foreach ($configmobile as $p_key) {
                    $mb_length = strlen($p_key);
                    $configmobile = $p_key;
                    // $notification->send($configmobile, $sms_message);
                    $this->service_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $configmobile, "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
                }
                /* Nishit code end */
                /* Nishit send sms code start */
                $user_details = $this->service_model->master_fun_get_tbl_val("customer_master", array("id" => $uid), array("id", "asc"));
                $family_member_name = $this->service_model->get_family_member_name($insert);
                if (!empty($family_member_name)) {
                    $c_name = $family_member_name[0]["name"];
                    $cmobile = $family_member_name[0]["phone"];
                    if (empty($cmobile)) {
                        $cmobile = $user_details[0]["mobile"];
                    }
                } else {
                    $c_name = $user_details[0]["full_name"];
                    $cmobile = $user_details[0]["mobile"];
                }

                $sms_message = $this->service_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "book_login"), array("id", "asc"));
                $sms_message = preg_replace("/{{NAME}}/", ucfirst($c_name), $sms_message[0]["message"]);
                $sms_message = preg_replace("/{{TESTPACK}}/", 'Package', $sms_message);
                $sms_message = preg_replace("/{{MOBILE}}/", $cmobile, $sms_message);
                $sms_message = preg_replace("/{{TOTALPRICE}}/", $total, $sms_message);
                $mobile = $user_details[0]['mobile'];
                $notification->send($cmobile, $sms_message);
                $this->service_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $mobile, "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
                if (!empty($family_member_name)) {
                    $this->service_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $customer_details[0]["mobile"], "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
                }
                /* Delhi and gurgaon booking alert sms start */
                $test_city = $this->input->get_post('test_city');
                if ($test_city == 4 || $test_city == 5) {
                    $sms_message = '';
                    $sms_message = $this->service_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "delhi_booking"), array("id", "asc"));
                    $city_name = $this->service_model->master_fun_get_tbl_val("test_cities", array('status' => 1, "id" => $test_city), array("id", "asc"));
                    $sms_message = preg_replace("/{{MOBILE}}/", $cmobile, $sms_message[0]["message"]);
                    $sms_message = preg_replace("/{{CITY}}/", $city_name[0]["name"], $sms_message);
                    $sms_message = preg_replace("/{{TESTPACK}}/", 'Package', $sms_message);
                    $sms_message = preg_replace("/{{TESTPACKLIST}}/", $test_package, $sms_message);
                    $sms_message = preg_replace("/{{TOTALPRICE}}/", $total, $sms_message);
                    $configmobile = $this->config->item('booking_alert_phone');
                    foreach ($configmobile as $p_key) {
                        //$notification::send($configmobile, $sms_message);
                        $this->service_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $p_key, "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
                    }
                }
                /* Delhi and gurgaon booking alert sms end */
                /* Nishit send sms code end */
                $this->assign_phlebo_job($insert);
                $this->check_prescription_test($insert, $uid);
                echo $this->json_data("1", "", array(array("msg" => "Your request successfully send.")));
            }
        } else {
            echo $this->json_data("0", "All fields are required.", "");
        }
    }

    function delete_health_line() {
        $uid = $this->input->get_post('id');
        if ($uid != null) {

            $update = $this->service_model->master_fun_update1("health_line", array("id" => $uid), array('status' => 0));
            if ($update) {
                echo $this->json_data("1", "", array(array("msg" => "Image deleted successfully")));
            }
        } else {

            echo $this->json_data("0", "parameter not passed", "");
        }
    }

    function doctor_customer_job() {
        $user_fk = $this->input->get_post('user_id');
        if ($user_fk != null) {
            $data = $this->service_model->doctor_customer_job($user_fk);
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
        $to = $this->input->get_post('to');
        if ($user_fk != null && $from != null && $to != null) {
            $data = $this->service_model->doctor_date_vise_report($user_fk, $from, $to);
            if (empty($data)) {
                echo $this->json_data("0", "no data available", "");
            } else {

                echo $this->json_data("1", "", $data);
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function last_7_days_doctor_customer_job() {
        $user_fk = $this->input->get_post('user_id');
        if ($user_fk != null) {
            $data = $this->service_model->last_7_doctor_customer_job($user_fk);
            if (empty($data)) {
                echo $this->json_data("0", "no data available", "");
            } else {

                echo $this->json_data("1", "", $data);
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function today_doctor_customer_job() {
        $user_fk = $this->input->get_post('user_id');
        if ($user_fk != null) {
            $data = $this->service_model->today_doctor_customer_job($user_fk);
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
            $data = $this->service_model->doctor_get_year_month_count($user_fk);
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
            $data = $this->service_model->doctor_stat($user_fk, $month, $year);
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
            $all = $this->service_model->doctor_customer_job($user_fk);
            $countall = count($all);
            $all_sum = 0;
            foreach ($all as $key) {
                $all_sum += $key['price'];
            }

            $last_7days = $this->service_model->last_7_doctor_customer_job($user_fk);
            $countlast7 = count($last_7days);
            $days7_sum = 0;
            foreach ($last_7days as $key) {
                $days7_sum += $key['price'];
            }
            $todays = $this->service_model->today_doctor_customer_job($user_fk);
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

    function doctor_view_report() {
        $jid = $this->input->get_post('job_id');
        if ($jid != null) {
            $data = $this->service_model->doctor_view_report($jid);
            if (empty($data)) {
                echo $this->json_data("0", "no data available", "");
            } else {
                echo $this->json_data("1", "", $data);
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function test_city() {
        /* $qry = "SELECT DISTINCT `test_master_city_price`.`city_fk`,`test_master`.`test_name` FROM `test_master_city_price` INNER JOIN `test_master` ON `test_master_city_price`.`test_fk`=`test_master`.`id` WHERE `test_master_city_price`.`status`='1' AND `test_master`.`status`='1'";
          $data = $this->service_model->get_result($qry);
          if (empty($data)) {
          $qry1 = "SELECT * FROM city WHERE id IN (333) AND STATUS='1'";
          $data1 = $this->service_model->get_result($qry1);
          echo $this->json_data("1", "", $data1);
          } else {
          $city_ids = array();
          foreach ($data as $key) {
          $city_ids[] = $key["city_fk"];
          }
          if(!empty($city_ids)){
          $qry1 = "SELECT * FROM city WHERE id IN (" . implode(",", $city_ids) . ",333) AND STATUS='1'";
          }else{
          $qry1 = "SELECT * FROM city WHERE id IN (333) AND STATUS='1'";
          }
          $data1 = $this->service_model->get_result($qry1);
          echo $this->json_data("1", "", $data1);
          } */
        $qry = 'select * from test_cities where status="1" order by name asc';
        $data1 = $this->service_model->get_result($qry);
        echo $this->json_data("1", "", $data1);
    }

    function check_user_adr() {
        $user_fk = $this->input->get_post('user_id');
        /* $user_details = $this->check_user_address($user_fk);
          if ($user_details["address"]) {
          echo $this->json_data("1", "", "success");
          } else {
          echo $this->json_data("0", "Please add your current address in your profile and book package.", "");
          } */
        echo $this->json_data("1", "", array());
    }

    function send() {
        $this->load->helper("sms");
        $notification = new Sms();
        $sms_details = $this->service_model->master_fun_get_tbl_val("admin_alert_sms", array("is_send" => "0"), array("id", "asc"));
        foreach ($sms_details as $key) {
            $notification->send($key["mobile_no"], $key["message"]);
            $this->service_model->master_fun_update1("admin_alert_sms", array("id" => $key["id"]), array('is_send' => 1));
        }
        echo $this->json_data("1", "", "success");
    }

    function assign_phlebo_job($job_id) {
        $new_job_details = $this->service_model->master_fun_get_tbl_val("job_master", array('id' => $job_id), array("id", "asc"));
        $booking_info = $this->service_model->master_fun_get_tbl_val("booking_info", array('id' => $new_job_details[0]["booking_info"]), array("id", "asc"));
        $phlebo_list = $this->service_model->master_fun_get_tbl_val("phlebo_master", array('status' => "1", "type" => 1), array("id", "asc"));
        foreach ($phlebo_list as $pkey) {
            /* $message = "New job added order id:" . $new_job_details[0]["order_id"]; */
            $message = "New job added.";
            if ($pkey["device_type"] == 'android') {
                $serverObject = new Firebase_notification();
                $fields = array(
                    'to' => $pkey["device_id"],
                    'notification' => array('title' => 'AirmedLabs', 'body' => $message),
                    'data' => array('j_id' => $job_id)
                );
                $jsonString = $serverObject->sendPushNotificationToGCMSever($fields);
            }
            if ($pkey["device_type"] == 'ios') {
                $url = 'http://website-demo.in/patholabpush/push.php?device_id=' . $pkey["device_id"] . '&msg=' . $message . '&type=completed&testid=' . $job_id . '';
                $url = str_replace(" ", "%20", $url);
                $data = $this->get_content($url);
                $data2 = json_decode($data);
            }
        }
        if ($booking_info[0]["emergency"] == "0" || $booking_info[0]["emergency"] == null) {
            /* $get_random_phlebo = $this->service_model->get_random_phlebo($booking_info[0]);
              if (!empty($get_random_phlebo)) {
              $data = array("job_fk" => $job_id, "phlebo_fk" => $get_random_phlebo[0]["id"], "date" => $booking_info[0]["date"], "time_fk" => $booking_info[0]["time_slot_fk"], "address" => $booking_info[0]["address"], "notify_cust" => 1, "created_date" => date("Y-m-d H:i:s"), "created_by" => $data["login_data"]["id"]);
              $insert = $this->service_model->master_fun_insert("phlebo_assign_job", $data);
              //$this->user_test_master_model->master_fun_insert("job_log", array("job_fk" => $job_id, "created_by" => "", "updated_by" => $login_id, "deleted_by" => "", "message_fk" => "8", "date_time" => date("Y-m-d H:i:s")));
              //$update = $this->job_model->master_fun_update("phlebo_master", array("id", $phlebo_id), $data);
              $phlebo_details = $this->service_model->master_fun_get_tbl_val("phlebo_master", array('id' => $get_random_phlebo[0]["id"]), array("id", "asc"));
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
              foreach ($job_details[0]["book_test"] as $bkey) {
              $b_details[] = $bkey["test_name"] . " Rs." . $bkey["price"];
              }
              foreach ($job_details[0]["book_package"] as $bkey) {
              $b_details[] = $bkey["title"] . " Rs." . $bkey["d_price"];
              }
              $sms_message = preg_replace("/{{BOOKINFO}}/", implode(",", $b_details), $sms_message);
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
              $sms_message = preg_replace("/{{BOOKINFO}}/", implode(",", $b_details), $sms_message);
              $mobile = $customer_details[0]['mobile'];
              //$sms_message="done";
              $notification->send($cmobile, $sms_message);
              if (!empty($job_details[0]["address"])) {
              $this->service_model->master_fun_update("customer_master", array('id', $job_details[0]["cust_fk"]), array("address" => $job_details[0]["address"]));
              }
              if (!empty($family_member_name)) {
              $this->service_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $customer_details[0]["mobile"], "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
              }
              //}
              } else {
              }
              } */
        } else {
            $customer_details = $this->service_model->master_fun_get_tbl_val("customer_master", array('id' => $job_details[0]['cust_fk']), array("id", "asc"));
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
            $job_details = $this->get_job_details($job_id);
            $b_details = array();
            foreach ($job_details[0]["book_test"] as $bkey) {
                $b_details[] = $bkey["test_name"] . " Rs." . $bkey["price"];
            }
            foreach ($job_details[0]["book_package"] as $bkey) {
                $b_details[] = $bkey["title"] . " Rs." . $bkey["d_price"];
            }
            $sms_message = $this->service_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "emergency_book"), array("id", "asc"));
            $sms_message = preg_replace("/{{MOBILE}}/", $customer_details[0]["full_name"] . " (" . $customer_details[0]["mobile"] . ")", $sms_message[0]["message"]);
            $sms_message = preg_replace("/{{TESTPACKLIST}}/", implode(",", $b_details), $sms_message);
            $configmobile = $this->config->item('admin_alert_phone');
            foreach ($configmobile as $p_key) {
                $this->service_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $p_key, "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
            }
        }
    }

    function get_job_details($job_id) {
        $job_details = $this->service_model->master_fun_get_tbl_val("job_master", array("status !=" => "0", "id" => $job_id), array("id", "asc"));
        if (!empty($job_details)) {
            $book_test = $this->service_model->master_fun_get_tbl_val("job_test_list_master", array("job_fk" => $job_id), array("id", "desc"));
            $book_package = $this->service_model->master_fun_get_tbl_val("book_package_master", array("job_fk" => $job_id, "status" => "1"), array("id", "desc"));
            $test_name = array();
            foreach ($book_test as $key) {
                $price1 = $this->service_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $job_details[0]["test_city"] . "' AND `test_master_city_price`.`status`='1' AND `test_master`.`id`='" . $key["test_fk"] . "'");
                $test_name[] = $price1[0];
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
                $package_name[] = $price1[0];
            }
            $job_details[0]["book_package"] = $package_name;
        }
        return $job_details;
    }

    function package_details() {
        $pid = $this->input->get_post('pid');
        $data = $this->service_model->master_fun_get_tbl_val("package_master", array("id" => $pid), array("title", "asc"), array("id", "image,desc_web"));
        if (!empty($data)) {
            echo $this->json_data("1", "", $data[0]['desc_web']);
        } else {
            echo $this->json_data("0", "no data available", "");
        }
        /* Nishit code end */
    }

    function check_prescription_test($jid, $login_id) {
        $job_details = $this->get_job_details($jid);
        $jtest = array();
        foreach ($job_details[0]["book_test"] as $jkey) {
            $jtest[] = $jkey["id"];
        }
        $get_user_prescription = $this->service_model->master_fun_get_tbl_val("prescription_upload", array("cust_fk" => $login_id, "status" => "2"), array("id", "desc"));
        if (!empty($get_user_prescription)) {
            foreach ($get_user_prescription as $key) {
                $p_data = $this->service_model->master_fun_get_tbl_val("suggested_test", array("p_id" => $key["id"], "status" => "1"), array("id", "desc"));
                $p_tst = array();
                foreach ($p_data as $pkey) {
                    $p_tst[] = $pkey["test_id"];
                }
                $result = array_diff($jtest, $p_tst);

                if (empty($result) && empty($key["job_fk"])) {
                    $this->service_model->master_fun_update("prescription_upload", $key["id"], array("job_fk" => $jid));
                }
            }
        }
    }

    function get_content($URL) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $URL);
        $data = curl_exec($ch);
        curl_close($ch);
    }

    function get_job_id($city = null) {
        $this->load->library("Util");
        $util = new Util();
        $new_id = $util->get_job_id($city);
        return $new_id;
    }

    function pdf_invoice($id) {
        $data["login_data"] = loginuser();
        $this->load->model('add_result_model');
        $data['query'] = $this->service_model->job_details($id);
        $data['book_list'] = array();
        $tid = explode(",", $data['query'][0]['testid']);
        $fast = array();
        if ($data['query'][0]['testid'] != '') {
            foreach ($tid as $tst_id) {
                $para = $this->service_model->get_val("SELECT t.test_name as book_name,t.id as tid,p.price as price FROM test_master as t left join test_master_city_price as p  on p.test_fk=t.id WHERE t.status='1' AND p.status='1' AND t.id='" . $tst_id . "' AND p.city_fk='" . $data['query'][0]['test_city'] . "' order by t.test_name ASC");
                array_push($data['book_list'], $para);
                $test_fast = $this->service_model->get_val("SELECT fasting_requird FROM test_master WHERE status='1' AND id='" . $tst_id . "'");
                array_push($fast, $test_fast[0]['fasting_requird']);
            }
        }
        $pid = explode("%", $data['query'][0]['packageid']);
        if ($data['query'][0]['packageid'] != '') {
            foreach ($pid as $pack_id) {
                $para = $this->service_model->get_val("SELECT p.id as pid,p.title as book_name,pr.d_price as price FROM package_master as p left join package_master_city_price as pr on pr.package_fk=p.id WHERE p.status='1' AND pr.status='1' AND p.id='" . $pack_id . "' AND pr.city_fk='" . $data['query'][0]['test_city'] . "' order by p.title ASC");
                array_push($data['book_list'], $para);
            }
        }
        if (in_array("1", $fast)) {
            $data['fasting'] = 'Fasting required for 12 hours.';
        } else {
            $data['fasting'] = 'Fasting not required for 12 hours.';
        }
        $data['time'] = $this->service_model->get_val("SELECT ts.start_time,ts.end_time FROM booking_info as b left join phlebo_time_slot as ts on b.time_slot_fk=ts.id WHERE ts.status='1' AND b.status='1' AND b.id='" . $data['query'][0]['booking_info'] . "'");
        //echo "<pre>"; print_r($data['parameter_list']); die();
        $pdfFilePath = FCPATH . "/upload/result/" . $data['query'][0]['order_id'] . "_invoice.pdf";
        $data['page_title'] = 'AirmedLabs'; // pass data to the view
        ini_set('memory_limit', '32M'); // boost the memory limit if it's low <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="?" draggable="false" class="emoji">
        $html = $this->load->view('user/user_job_invoice_pdf', $data, true); // render the view into HTML
        //$param = '"en-GB-x","A4","","",10,10,0,10,6,3,"P"'; // Landscape
        //$lorem = utf8_encode($html); // render the view into HTML
        //$html = "<!DOCTYPE html>                         <html><body>\u0627\u0644\u0643\u0647\u0631\u0628\u0627\u0621 \u0648 \u0627\u0644\u0633\u0628\u0627\u0643\u0629</body></html>      ";
        ob_end_flush();
        $this->load->library('pdf');

        $pdf = $this->pdf->load();
        $pdf->autoScriptToLang = true;
        $pdf->baseScript = 1; // Use values in classes/ucdn.php  1 = LATIN
        $pdf->autoVietnamese = true;
        $pdf->autoArabic = true;

        $pdf->autoLangToFont = true;
        $pdf->AddPage('p', // L - landscape, P - portrait
                '', '', '', '', 5, // margin_left
                5, // margin right
                2, // margin top
                2, // margin bottom
                2, // margin header
                2); // margin footer
        //$pdf->AddPage('P', '', 1, 'i', 'on');
        //$pdf->SetDirectionality('rtl');
        /* $pdf->AddPage('P', // L - landscape, P - portrait
          '', '', '', '', 00, // margin_left
          0, // margin right
          0, // margin top
          0, // margin bottom
          0, // margin header
          0); */

        //$pdf->SetDisplayMode('fullpage');
        //$pdf=new mPDF('utf-8','A4');
        //$pdf->debug = true;
        // $pdf->h2toc = array('H2' => 0);
        // $html = '';
        // Split $lorem into words
        $pdf->WriteHTML($html);
        $pdf->debug = true;
        $pdf->allow_output_buffering = TRUE;
        //$pdf->SetFooter('www.' . $_SERVER['HTTP_HOST'] . '||' . $new_time); // Add a footer for good measure <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="?" draggable="false" class="emoji">
        // $pdf->WriteHTML($html); // write the HTML into the PDF
        $pdf->Output($pdfFilePath, 'F'); // save to file because we can
        $name = $data['query'][0]['order_id'] . "_invoice.pdf";
        $this->service_model->master_fun_update('job_master', $id, array("invoice" => $name));
        //redirect("/upload/result/" . $data['query'][0]['order_id'] . "_invoice.pdf");
        return $name;
    }

    function user_active_packege_list() {
        $test_city = $this->input->get_post("test_city");
        if (empty($test_city)) {
            $test_city = 1;
        }
        $uid = $this->input->get_post("uid");
        $data['active_package'] = $this->service_model->get_val("SELECT `active_package`.*,`package_master`.`title`,job_master.order_id FROM `active_package` 
INNER JOIN `package_master` ON `package_master`.`id`=`active_package`.`package_fk` inner join job_master on `active_package`.`job_fk`=job_master.id 
WHERE `active_package`.`status`='1' AND `package_master`.`status`='1' AND job_master.status !='0' AND `active_package`.`user_fk`='" . $uid . "' AND (`active_package`.`parent`='0' OR `active_package`.`parent`=NULL)");
        $cnt = 0;
        foreach ($data['active_package'] as $key) {
            if ($key["family_fk"] == '0') {
                $data['active_package'][$cnt]["family"] = "Self";
            } else {
                $family_mem = $this->service_model->get_val("SELECT `customer_family_master`.`name`,`relation_master`.`name` AS relation FROM `customer_family_master` INNER JOIN `relation_master` ON `relation_master`.`id`=`customer_family_master`.`relation_fk` WHERE `customer_family_master`.`status`='1' AND `customer_family_master`.`id`='" . $key["family_fk"] . "'");
                $data['active_package'][$cnt]["family"] = $family_mem[0]["name"] . "(" . $family_mem[0]["relation"] . ")";
            }
            $cnt++;
        }
        if (!empty($data['active_package'])) {
            $cnt = 0;
            foreach ($data['active_package'] as $key) {
                $timestamp = strtotime($key["book_date"]);
                $n_date = date('d/m/Y', $timestamp);
                $data['active_package'][$cnt]["book_date"] = $n_date;
                $timestamp = strtotime($key["due_to"]);
                $n_date = date('d/m/Y', $timestamp);
                $data['active_package'][$cnt]["due_to"] = $n_date;
                $cnt++;
            }
            echo $this->json_data("1", "", $data['active_package']);
        } else {
            echo $this->json_data("0", "no data available", "");
        }
    }

    function active_package_details() {
        $uid = $this->input->get_post("uid");
        $active_pid = $this->input->get_post("package_id");
        $data['active_package'] = $this->service_model->get_val("SELECT `active_package`.*,`package_master`.`title`,`job_master`.`order_id` FROM `active_package` INNER JOIN `package_master` ON `package_master`.`id`=`active_package`.`package_fk` INNER JOIN `job_master` ON `active_package`.`job_fk`=`job_master`.`id` WHERE `active_package`.`status`='1' AND `job_master`.`status`!='0' AND `package_master`.`status`='1' AND (`active_package`.`parent`='" . $active_pid . "' OR `active_package`.`id`='" . $active_pid . "')");
        $cnt = 0;
        $new_array = array();
        foreach ($data['active_package'] as $key) {
            if (!empty($this->get_job_details($key["job_fk"]))) {
                if ($key["family_fk"] == '0') {
                    $data['active_package'][$cnt]["family"] = "Self";
                } else {
                    $family_mem = $this->service_model->get_val("SELECT `customer_family_master`.`name`,`relation_master`.`name` AS relation FROM `customer_family_master` INNER JOIN `relation_master` ON `relation_master`.`id`=`customer_family_master`.`relation_fk` WHERE `customer_family_master`.`status`='1' AND `customer_family_master`.`id`='" . $key["family_fk"] . "'");
                    $data['active_package'][$cnt]["family"] = $family_mem[0]["name"] . "(" . $family_mem[0]["relation"] . ")";
                }
                $report = $this->service_model->get_val("SELECT * FROM `report_master` WHERE (`type`='c' OR `type`='t' OR `type`='p') AND job_fk='" . $key["job_fk"] . "' AND `status`='1'");
                $data['active_package'][$cnt]["report"] = $report;
                $data['active_package'][$cnt]["job_details"] = $this->get_job_details($key["job_fk"]);
                $new_array[] = $data['active_package'][$cnt];
                $cnt++;
            }
        }
        $data["job_address_list"] = $this->service_model->master_fun_get_tbl_val("job_master", array("status !=" => 0, "cust_fk" => $uid), array("id", "asc"));
        if (!empty($new_array)) {
            $cnt = 0;
            foreach ($new_array as $key) {
                $timestamp = strtotime($key["book_date"]);
                $n_date = date('d/m/Y', $timestamp);
                $new_array[$cnt]["book_date"] = $n_date;
                $timestamp = strtotime($key["due_to"]);
                $n_date = date('d/m/Y', $timestamp);
                $new_array[$cnt]["due_to"] = $n_date;
                $cnt++;
            }
            echo $this->json_data("1", "", $new_array);
        } else {
            echo $this->json_data("0", "no data available", "");
        }
    }

    /* function active_package_details() {
      $uid = $this->input->get_post("uid");
      $active_pid = $this->input->get_post("package_id");
      $data['active_package'] = $this->service_model->get_val("SELECT `active_package`.*,`package_master`.`title`,`job_master`.`order_id` FROM `active_package` INNER JOIN `package_master` ON `package_master`.`id`=`active_package`.`package_fk` INNER JOIN `job_master` ON `active_package`.`job_fk`=`job_master`.`id` WHERE `active_package`.`status`='1' AND `job_master`.`status`!='0' AND `package_master`.`status`='1' AND (`active_package`.`parent`='" . $active_pid . "' OR `active_package`.`id`='" . $active_pid . "')");
      $cnt = 0;
      foreach ($data['active_package'] as $key) {
      if ($key["family_fk"] == '0') {
      $data['active_package'][$cnt]["family"] = "Self";
      } else {
      $family_mem = $this->service_model->get_val("SELECT `customer_family_master`.`name`,`relation_master`.`name` AS relation FROM `customer_family_master` INNER JOIN `relation_master` ON `relation_master`.`id`=`customer_family_master`.`relation_fk` WHERE `customer_family_master`.`status`='1' AND `customer_family_master`.`id`='" . $key["family_fk"] . "'");
      $data['active_package'][$cnt]["family"] = $family_mem[0]["name"] . "(" . $family_mem[0]["relation"] . ")";
      }
      $report = $this->service_model->get_val("SELECT * FROM `report_master` WHERE (`type`='c' OR `type`='t' OR `type`='p') AND job_fk='" . $key["job_fk"] . "' AND `status`='1'");
      $data['active_package'][$cnt]["report"] = $report;
      $data['active_package'][$cnt]["job_details"] = $this->get_job_details($key["job_fk"]);
      $cnt++;
      }
      $data["job_address_list"] = $this->service_model->master_fun_get_tbl_val("job_master", array("status !=" => 0, "cust_fk" => $uid), array("id", "asc"));
      if (!empty($data['active_package'])) {
      $cnt=0;
      foreach($data['active_package'] as $key){
      $timestamp = strtotime($key["book_date"]);
      $n_date = date('d/m/Y', $timestamp);
      $data['active_package'][$cnt]["book_date"]=$n_date;
      $timestamp = strtotime($key["due_to"]);
      $n_date = date('d/m/Y', $timestamp);
      $data['active_package'][$cnt]["due_to"]=$n_date;
      $cnt++;}
      echo $this->json_data("1", "", $data['active_package']);
      } else {
      echo $this->json_data("0", "no data available", "");
      }
      } */

    function book_active_package() {
        $this->load->helper("Email");
        $email_cnt = new Email;
        $uid = $this->input->get_post('uid');
        $address = $this->input->get_post('address');
        $city = $this->input->get_post('city');
        $state = $this->input->get_post('state');
        $pin = $this->input->get_post('pin');
        $select_package = $this->input->get_post('select_package');
        $total = 0;
        $docid = 0;
        $reference_by = 0;
        $method = 0;
        $test_city = $this->input->get_post('test_city');
        if (empty($test_city)) {
            $test_city = 1;
        }
        $type = $this->input->get_post("type");
        $order_id = $this->get_job_id($this->input->get_post('test_city'));
        $date = date('Y-m-d H:i:s');
        if ($uid != '' && $select_package != '') {
            /* Nishit book phlebo start */
            $testforself = $this->input->get_post("testforself");
            $family_mem_id = $this->input->get_post("family_mem_id");
            $address = $this->input->get_post("address");
            $date1 = $this->input->get_post("date");
            $date1 = explode("/", $date1);
            $date1 = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
            $time_slot_id = $this->input->get_post("time_slot_id");
            $emergency_req = $this->input->get_post("emergency_req");
            if ($testforself == "true") {
                $b_type = "self";
                $family_mem_id = 0;
            } else {
                $b_type = "family";
            }
            if ($emergency_req == "true") {
                $date1 = date("Y-m-d");
                $emergency_req = 1;
                $time_slot_id = 0;
            } else {
                $emergency_req = 0;
            }
            $booking_fk = $this->service_model->master_fun_insert("booking_info", array("user_fk" => $uid, "type" => $b_type, "family_member_fk" => $family_mem_id, "address" => $address, "date" => $date1, "time_slot_fk" => $time_slot_id, "emergency" => $emergency_req, "status" => "1", "createddate" => date("Y-m-d H:i:s")));
            /* Nishit book phlebo end */
            $data = array(
                "order_id" => $order_id,
                "cust_fk" => $uid,
                "date" => $date,
                "price" => 0,
                "status" => '1',
                "payment_type" => "Cash On Delivery",
                "address" => $address,
                "city" => $city,
                "state" => $state,
                "pin" => $pin,
                "doctor" => $docid,
                "other_reference" => $reference_by,
                "test_city" => $test_city,
                "portal" => $type,
                "booking_info" => $booking_fk
            );
            $insert = $this->service_model->master_fun_insert("job_master", $data);
            if ($insert) {
                //$insert1 = $this->service_model->master_fun_insert("job_master", array("cust_fk" => $uid,"price"=>$total, "date" => $date, "order_id" => $order_id, "payment_type" => "Cash On Delivery"));
                // Referral amount for first test book//
                $data = $this->service_model->master_fun_get_tbl_val("job_master", array("cust_fk" => $uid), array("id", "asc"));
                $count = count($data);
                if ($count == 1) {

                    $refdata = $this->service_model->master_fun_get_tbl_val("refer_code_master", array("status" => 1, "cust_fk" => $uid), array("id", "asc"));
                    $userdcode = $refdata[0]['used_code'];
                    if ($usercode != "") {
                        $refdata1 = $this->service_model->master_fun_get_tbl_val("refer_code_master", array("status" => 1, "used_code" => $userdcode), array("id", "asc"));
                    }

                    if (!empty($refdata1)) {
                        $refdata1 = $this->service_model->master_fun_get_tbl_val("refer_code_master", array("status" => 1, "refer_code" => $userdcode), array("id", "asc"));
                        $usedcust_fk = $refdata1[0]['cust_fk'];
                        $query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $usedcust_fk), array("id", "desc"));
                        $wallettotal = $query[0]['total'];
                        //die();
                        $datacust = $this->service_model->master_fun_get_tbl_val("customer_master", array("id" => $usedcust_fk), array("id", "asc"));
                        $refemail = $datacust[0]['email'];
                        $ref_name = $datacust[0]['full_name'];

                        $data = array(
                            "cust_fk" => $usedcust_fk,
                            "credit" => 100,
                            "total" => $wallettotal + 100,
                            "type" => "referral code",
                            "created_time" => date('Y-m-d H:i:s')
                        );
                        $insert1 = $this->service_model->master_fun_insert("wallet_master", $data);

                        $config['mailtype'] = 'html';
                        $this->email->initialize($config);

                        $message = '<div style="padding:0 4%;">
                    <h4><b>You have one more refferal</b></h4>
                        <p><b>Dear </b> ' . ucfirst($ref_name) . '</p>
                        <p style="color:#7e7e7e;font-size:13px;">Congratulation You have One more refferal  </p>
                        <p style="color:#7e7e7e;font-size:13px;">Rs.100 has been Credited in your wallet</p>
                </div>';
                        $message = $email_cnt->get_design($message);
                        $this->email->to($refemail);
                        $this->email->from("donotreply@airmedpathlabs.com", "AirmedLabs");
                        $this->email->subject("Refferal Amount Credited");
                        $this->email->message($message);
                        $this->email->send();
                    }
                }
                //end referral for first test book
                $query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $uid), array("id", "desc"));
                $wallettotal = $query[0]['total'];
                $payfrom_wallet = '';
                $payemnttype = "Cash on blood Collection";
                if ($method == "yes") {
                    if ($wallettotal > 0) {
                        if ($wallettotal >= $total) {

                            $data1 = array(
                                "cust_fk" => $uid,
                                "debit" => $total,
                                "total" => $wallettotal - $total,
                                "job_fk" => $insert,
                                "created_time" => date('Y-m-d H:i:s')
                            );
                            $payable = 0;
                            $payfrom_wallet = '<p style="color:#7e7e7e;font-size:13px;"> Your Payable Amount Rs. ' . $payable . '  </p>';
                            $payemnttype = "Paid From Wallet";
                        } else {
                            $data1 = array(
                                "cust_fk" => $uid,
                                "debit" => $wallettotal,
                                "total" => 0,
                                "job_fk" => $insert,
                                "created_time" => date('Y-m-d H:i:s')
                            );
                            $payable = $total - $wallettotal;
                            $payfrom_wallet = '<p style="color:#7e7e7e;font-size:13px;"> Your Payable Amount Rs. ' . $payable . '  </p>';
                            $payemnttype = "Cash on blood Collection";
                        }
                        $insert12 = $this->service_model->master_fun_insert("wallet_master", $data1);
                        $update = $this->service_model->master_fun_update1("job_master", array("id" => $insert), array("payable_amount" => 0));
                    } else {
                        $update = $this->service_model->master_fun_update1("job_master", array("id" => $insert), array("payable_amount" => 0));
                    }
                } else {
                    $update = $this->service_model->master_fun_update1("job_master", array("id" => $insert), array("payable_amount" => 0));
                }
                $test = explode(",", $select_package);
                $this->load->library("util");
                $util = new Util;
                foreach ($test as $key) {
                    $this->service_model->master_fun_insert("book_package_master", array("job_fk" => $insert, "date" => $date, "order_id" => $order_id, "package_fk" => $key, "cust_fk" => $uid, "type" => "2"));
                    $util->check_active_package($key, $insert, $uid);
                }
                $data = $this->service_model->master_fun_get_tbl_val("package_master", array("status" => 1, 'id' => $select_package), array("id", "asc"));
                $packname = $data[0]['title'];
                $destail = $this->service_model->master_fun_get_tbl_val("customer_master", array("status" => 1, "id" => $uid), array("id", "asc"));
                $config['mailtype'] = 'html';
                $this->email->initialize($config);
                $family_member_name = $this->service_model->get_family_member_name($insert);
                if (!empty($family_member_name)) {
                    $destail[0]['full_name'] = $family_member_name[0]["name"];
                }
                $file = $this->pdf_invoice($insert);
                $message = '<div style="padding:0 4%;">
                    <h4><b>Dear </b>' . $destail[0]['full_name'] . '</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Package has been book successfully. </p>
                        <p style="color:#7e7e7e;font-size:13px;">You booked ' . $packname . '</p>
<p style="color:#7e7e7e;font-size:13px;"> Your Package Amount is Rs. ' . $total . '  </p>
' . $payfrom_wallet . '
		<p style="color:#7e7e7e;font-size:13px;"> Mobile : ' . $destail[0]['mobile'] . '</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
                $message = $email_cnt->get_design($message);
                $this->email->to($destail[0]['email']);
                $this->email->cc($this->config->item('admin_booking_email'));
                $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
                $this->email->subject('Package Book Successfully');
                $this->email->message($message);
                $attatchPath = base_url() . "upload/result/" . $file;
                $this->email->attach($attatchPath);
                $this->email->send();
                $family_member_name = $this->service_model->get_family_member_name($insert);
                if (!empty($family_member_name)) {
                    $c_email = $family_member_name[0]["email"];
                    if (!empty($c_email)) {
                        $this->email->to($c_email);
                        $this->email->cc($this->config->item('admin_booking_email'));
                        $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
                        $this->email->subject('Package Book Successfully');
                        $this->email->message($message);
                        $this->email->send();
                    }
                }
                /* Nishit code start */
                $this->load->helper("sms");
                $notification = new Sms();
                $mobile = $destail[0]['mobile'];
                $mobile = ucfirst($destail[0]['full_name']) . "(" . $mobile . ")";
                $test_package = ucfirst($packname);
                $sms_message = $this->service_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "test_info"), array("id", "asc"));
                $sms_message = preg_replace("/{{MOBILE}}/", $mobile, $sms_message[0]["message"]);
                $sms_message = preg_replace("/{{TESTPACK}}/", 'Package', $sms_message);
                $sms_message = preg_replace("/{{TESTPACKLIST}}/", $test_package, $sms_message);
                $sms_message = preg_replace("/{{TOTALPRICE}}/", 0, $sms_message);
                $configmobile = $this->config->item('admin_alert_phone');
                foreach ($configmobile as $p_key) {
                    $mb_length = strlen($p_key);
                    $configmobile = $p_key;
                    // $notification->send($configmobile, $sms_message);
                    $this->service_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $configmobile, "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
                }
                /* Delhi and gurgaon booking alert sms start */
                $test_city = $this->input->get_post('test_city');
                if ($test_city == 4 || $test_city == 5) {
                    $sms_message = '';
                    $sms_message = $this->service_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "delhi_booking"), array("id", "asc"));
                    $city_name = $this->service_model->master_fun_get_tbl_val("test_cities", array('status' => 1, "id" => $test_city), array("id", "asc"));
                    $sms_message = preg_replace("/{{MOBILE}}/", $mobile, $sms_message[0]["message"]);
                    $sms_message = preg_replace("/{{CITY}}/", $city_name[0]["name"], $sms_message);
                    $sms_message = preg_replace("/{{TESTPACK}}/", 'Package', $sms_message);
                    $sms_message = preg_replace("/{{TESTPACKLIST}}/", $test_package, $sms_message);
                    $sms_message = preg_replace("/{{TOTALPRICE}}/", 0, $sms_message);
                    $configmobile = $this->config->item('booking_alert_phone');
                    foreach ($configmobile as $p_key) {
                        //$notification::send($configmobile, $sms_message);
                        $this->service_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $p_key, "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
                    }
                }
                /* Delhi and gurgaon booking alert sms end */
                /* Nishit code end */
                /* Nishit send sms code start */
                $user_details = $this->service_model->master_fun_get_tbl_val("customer_master", array("id" => $uid), array("id", "asc"));
                $family_member_name = $this->service_model->get_family_member_name($insert);
                if (!empty($family_member_name)) {
                    $c_name = $family_member_name[0]["name"];
                    $cmobile = $family_member_name[0]["phone"];
                    if (empty($cmobile)) {
                        $cmobile = $user_details[0]["mobile"];
                    }
                } else {
                    $c_name = $user_details[0]["full_name"];
                    $cmobile = $user_details[0]["mobile"];
                }
                $sms_message = $this->service_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "book_login"), array("id", "asc"));
                $sms_message = preg_replace("/{{NAME}}/", ucfirst($c_name), $sms_message[0]["message"]);
                $sms_message = preg_replace("/{{TESTPACK}}/", 'Package', $sms_message);
                $sms_message = preg_replace("/{{MOBILE}}/", $cmobile, $sms_message);
                $mobile = $user_details[0]['mobile'];
                $notification->send($cmobile, $sms_message);
                $this->service_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $mobile, "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
                if (!empty($family_member_name)) {
                    $this->service_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $customer_details[0]["mobile"], "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
                }
                /* Nishit send sms code end */
                $this->assign_phlebo_job($insert);
                $this->check_prescription_test($insert, $uid);
                echo $this->json_data("1", "", array(array("msg" => "Your request successfully send.")));
            }
        } else {
            echo $this->json_data("0", "All fields are required.", "");
        }
    }

    function job_delete() {
        $uid = $this->input->get_post("uid");
        $jid = $this->input->get_post("jid");
        $check_job = $this->service_model->master_fun_get_tbl_val("job_master", array("cust_fk" => $uid, "id" => $jid, "status!=" => "0"), array("id", "asc"));
        if (!empty($check_job)) {
            $this->service_model->master_fun_update1("job_master", array("id" => $jid), array('status' => 0, "deleted_by" => $uid));
            echo $this->json_data("1", "", "");
        } else {
            echo $this->json_data("0", "Invalid required.", "");
        }
    }

    function save_booking_data() {
        $uid = $this->input->get_post('uid');
        $type = $this->input->get_post('type');
        $crelation = $this->input->get_post('crelation');
        $uaddress = $this->input->get_post('uaddress');
        $bookdate = $this->input->get_post('bookdate');
        $landmark = $this->input->get_post('landmark');
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
                "landmark" => $landmark,
                "date" => $bookdate,
                "time_slot_fk" => $select_slot,
                "createddate" => date("Y-m-d H:i:s")
            );
        } else if ($bookdate >= date("Y-m-d")) {
            $data = array(
                "user_fk" => $uid,
                "type" => $type,
                "family_member_fk" => $crelation,
                "landmark" => $landmark,
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

    function home_page() {
        $city = $this->input->get_post("test_city");
        if (empty($city)) {
            $city = 1;
        }
        $URL = "https://secure.payu.in/_payment";
        $MERCHANT_ID = "5669867";
        $MERCHANT_KEY = "IcZRGO7S";
        $SALT = "spjyl2ubfa";
        /* Test credential start */
        /* $URL = "https://test.payu.in/_payment";
          $MERCHANT_ID = "5669867";
          $MERCHANT_KEY = "gtKFFx";
          $SALT = "eCwWELxi";

          https://test.payu.in/_payment */
        /* end */
        /* user app version start */
        $user_id = $this->input->get_post('user_id');
        $version = $this->input->get_post('version');
        $this->service_model->master_fun_update("customer_master", $user_id, array("app_version" => $version));
        /* user app version end */
        /* Package start */
        $package_data = $this->service_model->get_val("SELECT * FROM package_master WHERE STATUS='1' ORDER BY `order` ASC LIMIT 0,3");
        $cnt = 0;
        foreach ($package_data as $key) {
            if ($city != '') {
                $pkg_price = $this->service_model->master_fun_get_tbl_val("package_master_city_price", array("status" => "1", "package_fk" => $key["id"], "city_fk" => $city), array("id", "asc"));
                //echo "select id,title,image,desc_app from package_master_city_price where status ='1' AND package_fk= '".$key["id"]."' AND city_fk='".$city."'<br>";
                //$pkg_price = $this->service_model->get_result("select * from package_master_city_price where status ='1' AND package_fk= '".$key["id"]."' AND city_fk='".$city."'");
                //print_r($pkg_price); die();
                if (!empty($pkg_price)) {
                    $package_data[$cnt]["a_price"] = $pkg_price[0]["a_price"];
                    $package_data[$cnt]["d_price"] = $pkg_price[0]["d_price"];
                } else {
                    $package_data[$cnt]["a_price"] = "0";
                    $package_data[$cnt]["d_price"] = "0";
                }
            } else {
                $package_data[$cnt]["a_price"] = "0";
                $package_data[$cnt]["d_price"] = "0";
            }
            $cnt++;
        }

        /* Package end */
        $a = base_url() . "user_assets/home_page_banner/1470818385D.jpg";
        $b = base_url() . "user_assets/home_page_banner/1470818494E.jpg";
        $c = base_url() . "user_assets/home_page_banner/1470818573F.jpg";
        $d = base_url() . "user_assets/home_page_banner/cash_back_30.png";
        $e = base_url() . "user_assets/home_page_banner/banner2.jpg";
        $images = array(
            array("pic" => $a),
            array("pic" => $b),
            array("pic" => $c),
            array("pic" => $d),
            array("pic" => $e)
        );
        $a1 = base_url() . "user_assets/home_page_banner/1470818385D1.jpg";
        $b1 = base_url() . "user_assets/home_page_banner/1470818494E1.jpg";
        $c1 = base_url() . "user_assets/home_page_banner/1470818573F1.jpg";
        $d1 = base_url() . "user_assets/home_page_banner/cash_back_301.png";
        $e1 = base_url() . "user_assets/home_page_banner/banner21.jpg";
        $images1 = array(
            array("pic" => $a1),
            array("pic" => $b1),
            array("pic" => $c1),
            array("pic" => $d1),
            array("pic" => $e1)
        );
        /* Check api version */
        $check_version_array = array("android" => array("version" => "47", "compulsory" => "yes", "message" => "New version available please update it and enjoy new feature."), "ios" => array("version" => "2", "compulsory" => "no", "message" => "New version available please update it and enjoy new feature."));
        $configuration = array("MERCHANT_ID" => $MERCHANT_ID, "MERCHANT" => $MERCHANT_KEY, "SALT" => $SALT, 'URl' => $URL, "WalletIsUsedInPackagePayment" => "0");
        $data3 = $this->service_model->master_fun_get_tbl_val("offer_master", array("status" => 1), array("id", "asc"));
        if (empty($package_data)) {
            echo $this->json_data("0", "no data available", "");
        } else {
            echo $this->json_data("1", "", array(array("data1" => $data3, "images" => $images, "android_image" => $images1, "package_data" => $package_data, "pay_conf" => $configuration, "version_check" => $check_version_array)));
        }
    }

    function user_address() {
        $uid = $this->input->get_post("uid");
        $check_job = $this->service_model->master_fun_get_tbl_val("user_address", array("user_fk" => $uid, "status" => "1"), array("id", "desc"));
        $addr = array();
        $landmark = array();
        /* foreach ($check_job as $key) {
          if (trim($key["address"])) {
          $addr[] = trim($key["address"]);
          }
          if (trim($key["landmark"])) {
          $landmark[] = trim($key["landmark"]);
          }
          }
          $addr = array_unique($addr);
          $landmark = array_unique($landmark); */
        echo $this->json_data("1", "", $check_job);
    }

    function add_address() {
        $uid = trim($this->input->get_post("uid"));
        $house_no = trim($this->input->get_post("house_no"));
        $house_name = trim($this->input->get_post("house_name"));
        $nearby = trim($this->input->get_post("nearby"));
        $landmark = trim($this->input->get_post("landmark"));
        $area = trim($this->input->get_post("area"));
        $address = $this->get_address();
        $row = $this->service_model->master_num_rows("user_address", array("user_fk" => $uid, "address" => $address, "status" => 1));
        if ($row == 0) {
            $this->service_model->master_fun_insert("user_address", array(
                "user_fk" => $uid,
                "address" => $address,
                "house_no" => $house_no,
                "house_name" => $house_name,
                "nearby" => $nearby,
                "landmark" => $landmark,
                "area" => $area
            ));
        }
        echo $this->json_data("1", "", "");
    }

    function check_user_info() {
        $uid = trim($this->input->get_post("uid"));
        $family = $this->input->get_post('family');
        if ($family != '' && $uid != '') {
            if ($family == 0) {
                $row = $this->service_model->master_fun_get_tbl_val("customer_master", array("id" => $uid, "status" => "1"), array("id", "desc"));
                if (empty($row)) {
                    echo $this->json_data("0", "Invalid id.", "");
                } else {
                    if ($row[0]["gender"] != '' && $row[0]["dob"] != '') {
                        echo $this->json_data("1", "", array(array("status" => "1")));
                    } else {
                        echo $this->json_data("1", "", array(array("status" => "0", "data" => $row)));
                    }
                }
            } else {
                $row = $this->service_model->master_fun_get_tbl_val("customer_family_master", array("id" => $family, "status" => "1"), array("id", "desc"));
                if (empty($row)) {
                    echo $this->json_data("0", "Invalid id.", "");
                } else {
                    if ($row[0]["gender"] != '' && $row[0]["dob"] != '') {
                        echo $this->json_data("1", "", array(array("status" => "1")));
                    } else {
                        echo $this->json_data("1", "", array(array("status" => "0", "data" => $row)));
                    }
                }
            }
        } else {
            echo $this->json_data("0", "All fields are required.", "");
        }
    }

    function update_user_info() {
        $id = $this->input->get_post('uid');
        $family = $this->input->get_post('family');
        $gender = $this->input->get_post('gender');
        $dob = $this->input->get_post('dob');
        if ($id != NULL && $gender != NULL && $dob != '' && $family != '') {
            if ($family == 0) {
                $row = $this->service_model->master_num_rows("customer_master", array("id" => $id, "status" => '1'));
                if ($row == 1) {
                    $update = $this->service_model->master_fun_update("customer_master", $id, array("gender" => $gender, "dob" => $dob));
                    echo $this->json_data("1", "", array());
                } else {
                    echo $this->json_data("0", "Invalid user id.", "");
                }
            } else {
                $row = $this->service_model->master_num_rows("customer_family_master", array("id" => $family, "status" => '1'));
                if ($row == 1) {
                    $update = $this->service_model->master_fun_update("customer_family_master", $family, array("gender" => $gender, "dob" => $dob));
                    echo $this->json_data("1", "", array());
                } else {
                    echo $this->json_data("0", "Invalid user id.", "");
                }
            }
        } else {
            echo $this->json_data("0", "All parameters are required.", "");
        }
    }

    function get_address() {
        $address = $this->input->get_post('address');
        $landmark = $this->input->get_post('landmark');
        $house_no = $this->input->get_post('house_no');
        $house_name = $this->input->get_post('house_name');
        $nearby = $this->input->get_post('nearby');
        $area = $this->input->get_post('area');
        if ($landmark == '' && $house_no == '' && $house_name == '' && $nearby == '' && $area == '' && $address != '') {
            return $address;
        } else {
            $adr = '';
            if (trim($house_no) != '') {
                $adr .= $house_no . ",";
            }
            if (trim($house_name) != '') {
                $adr .= $house_name . ",";
            }
            if (trim($nearby) != '') {
                $adr .= $nearby . ",";
            }
            if (trim($area) != '') {
                $adr .= $area;
            }
            if (trim($landmark) != '') {
                $adr .= $landmark . ",";
            }
            return $adr;
        }
    }

    function city_area_list() {
        $city = $this->input->post("city");
        $data = $this->service_model->get_val("SELECT DISTINCT area_name,city_fk,id FROM `area_master` WHERE STATUS='1' ORDER BY area_name ASC");
        if (!empty($data)) {
            echo $this->json_data("1", "", $data);
        } else {
            echo $this->json_data("0", "Data not available.", "");
        }
    }

}
