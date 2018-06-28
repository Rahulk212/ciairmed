<?php

class Service extends CI_Controller {

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
        $this->load->library('email');
        $email = $this->input->get_post('email');
        $name = $this->input->get_post('name');
        $password = $this->input->get_post('password');
        $mobile = $this->input->get_post('mobile');
        $gender = $this->input->get_post('gender');
        $usedcode = $this->input->get_post('refer_code');
        $test_city = $this->input->get_post('test_city');
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
                    $insert = $this->service_model->master_fun_insert("customer_master", array("full_name" => $name, "gender" => $gender, "email" => $email, "password" => $password, "active" => 0, "confirm_code" => $confirm_code, "otp" => $OTP, "test_city" => $test_city, "mobile" => $mobile, "status" => "0"));
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
                    <h4><b>You have one more refferal</b></h4>
                        <p><b>Dear </b>  ' . ucfirst($ref_name) . '</p>
                        <p style="color:#7e7e7e;font-size:13px;">Congratulation You have One more refferal  </p>
                        <p style="color:#7e7e7e;font-size:13px;">Rs.100 has been Credited in your wallet</p>
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

                        $this->email->to($refemail);
                        $this->email->from("donotreply@airmedpathlabs.com", "AirmedLabs");
                        $this->email->subject("Refferal Amount Credited");
                        $this->email->message($message);
                        $this->email->send();
                    }
                    // end code for refer price
                    $config['mailtype'] = 'html';
                    $this->email->initialize($config);

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
                    <h4><b>Confirm Your Register</b></h4>
                        <p><b>Dear </b> ' . ucfirst($name) . '</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank you for registering for the Airmed PATH LAB. </p>
                        <p style="color:#7e7e7e;font-size:13px;">Please confirm Your Email to get all services provided by Airmed PATH LAB</p>
								<a href="' . base_url() . 'register/confirm_email/' . $confirm_code . '" style="background: #D01130;color: #f9f9f9;padding: 1%;text-decoration: none;">Confirm</a>

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
                echo $this->json_data("0", "Email Or Password Not match", "");
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
        $this->load->library('email');
        $email = $this->input->get_post('email');
        if ($email != NULL) {
            $row = $this->service_model->master_fun_get_tbl_val("doctor_master", array("email" => $email, "status" => 1, "active" => 1), array("id", "asc"));
            $password = $row[0]['password'];

            $config['mailtype'] = 'html';
            $this->email->initialize($config);
            // $message = "You recently requested to reset your password for your  Patholab Account.<br/><br/>";
            // $message .= "Your Password id <strong>" . $password . "</strong><br/><br/> ";
            //  $message .= "If you did not request a password reset, please ignore this email or reply to let us know.<br/><br/>";
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
                    <h4><b>Forgot Password</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;">You recently requested to reset your password for your AirmedLabs Account. Your password is ' . $password . ' </p>
                        <p style="color:#7e7e7e;font-size:13px;">If you did not request , please ignore this email or reply to let us know.</p>
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
            $message .= "Thanks <br/>";
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
                    <h4><b>Password Change AirmedLabs</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;font-weight: bold;"><b>Dear </b> ' . ucfirst($row[0]['full_name']) . '</p>
                        <p style="color:#7e7e7e;font-size:13px;">Your Password Successfully Changed. </p>
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
        $this->load->library('email');
        $email = $this->input->get_post('email');
        if ($email != NULL) {
            $row = $this->service_model->master_fun_get_tbl_val("customer_master", array("email" => $email, "status" => 1, "active" => 1), array("id", "asc"));
            $password = $row[0]['password'];

            $config['mailtype'] = 'html';
            $this->email->initialize($config);
            // $message = "You recently requested to reset your password for your  Patholab Account.<br/><br/>";
            // $message .= "Your Password id <strong>" . $password . "</strong><br/><br/> ";
            //  $message .= "If you did not request a password reset, please ignore this email or reply to let us know.<br/><br/>";
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
                    <h4><b>Forgot Password</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;">You recently requested to reset your password for your AirmedLabs Account. Your password is ' . $password . ' </p>
                        <p style="color:#7e7e7e;font-size:13px;">If you did not request , please ignore this email or reply to let us know.</p>
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
            $message .= "Thanks <br/> AirmedLabs";
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
                    <h4><b>Password Change AirmedLabs</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;font-weight: bold;"><b>Dear </b>  ' . ucfirst($row[0]['full_name']) . '</p>
                        <p style="color:#7e7e7e;font-size:13px;">Your Password Successfully Changed. </p>
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
            $qry = "SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $city . "'";
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
            $qry = "SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='1'";
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
            /* User alert sms start */
            $udata = $this->service_model->master_fun_get_tbl_val("customer_master", array("id" => $user_fk), array("id", "asc"));
            $this->load->helper("sms");
            $notification = new Sms();
            $mobile = $udata[0]['mobile'];
            if ($mobile != NULL) {
                $sms_message = "Hello there, we have come  up with some cool new features in this update. Please update your app and enjoy it. For any query please call (8101161616)";
                $notification->send($mobile, $sms_message);
            }
            /* User alert sms end */
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
        $user_fk = $this->input->get_post('user_id');
        $testfk = $this->input->get_post('test_id');
        $amount = $this->input->get_post('total');
        $doctor = $this->input->get_post('doctor');
        $type = $this->input->get_post("type");
        $other_reference = $this->input->get_post('other_reference');
        $order_id = random_string('numeric', 13);
        $date = date('Y-m-d H:i:s');
        if ($user_fk != "" && $testfk != "") {
            $insert = $this->service_model->master_fun_insert("job_master", array("cust_fk" => $user_fk, "date" => $date, "order_id" => $order_id, "doctor" => $doctor, "other_reference" => $other_reference, "payment_type" => "Wallet", "portal" => $type));
            $test = explode(',', $testfk);
            $price = 0;
            $test_name_mail = array();
            for ($i = 0; $i < count($test); $i++) {
                $insert_code = $this->service_model->master_fun_insert("job_test_list_master", array("job_fk" => $insert, "test_fk" => $test[$i]));
                $data = $this->service_model->master_fun_get_tbl_val("test_master", array("status" => 1, 'id' => $test[$i]), array("id", "asc"));
                $price = $price + $data[0]['price'];
                $test_name_mail[$i] = $data[0]['test_name'];
            }
            $update = $this->service_model->master_fun_update("job_master", $insert, array("price" => $price));
            if ($update) {
                $query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $user_fk), array("id", "desc"));
                $total = $query[0]['total'];
                $query = $this->service_model->master_fun_get_tbl_val("offer_master", array("status" => 1), array("id", "desc"));
                $caseback_per = $query[0]['caseback_per'];
                $caseback_amount = ($price * $caseback_per) / 100;
                $data = array(
                    "cust_fk" => $user_fk,
                    "debit" => $price,
                    "total" => $total - $price,
                    "job_fk" => $insert,
                    "created_time" => date('Y-m-d H:i:s')
                );
                $insert1 = $this->service_model->master_fun_insert("wallet_master", $data);
                $query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $user_fk), array("id", "desc"));
                $total1 = $query[0]['total'];
                $data1 = array(
                    "cust_fk" => $user_fk,
                    "credit" => $caseback_amount,
                    "total" => $total1 + $caseback_amount,
                    "job_fk" => $insert,
                    "type" => "Case Back",
                    "created_time" => date('Y-m-d H:i:s')
                );



                $insert = $this->service_model->master_fun_insert("wallet_master", $data1);

                $query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $user_fk), array("id", "desc"));
                $Current_wallet = $query[0]['total'];

                $destail = $this->service_model->master_fun_get_tbl_val("customer_master", array("status" => 1, "id" => $user_fk), array("id", "asc"));


                $config['mailtype'] = 'html';
                $this->email->initialize($config);

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
                    <h4><b>Dear </b>' . $destail[0]['full_name'] . '</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Test has been book successfully. </p>
                        <p style="color:#7e7e7e;font-size:13px;">You booked ' . implode($test_name_mail, ',') . '</p>
<p style="color:#7e7e7e;font-size:13px;"> Rs. ' . $price . ' Debited From your Wallet. </p>
        <p style="color:#7e7e7e;font-size:13px;">Your Current balance is Rs. ' . ($total - $price) . '</p>
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
                $this->email->subject('Test Book Successfully');
                $this->email->message($message);
                $this->email->send();

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
				<h4><b>Dear </b>' . $destail[0]['full_name'] . '</h4>
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
                    <h4><b>Contact Detail</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;font-weight: bold;">Follow information shows Contact Person Detail :</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Customer Name : </b> ' . ucfirst($detail[0]['full_name']) . '</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Email : </b> ' . $detail[0]['email'] . '</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Mobile : </b> ' . $mobile . '</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Description : </b> ' . ucfirst($desc) . '</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Uploaded file : </b> ' . base_url() . 'upload/' . $image . '</p>
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
                $pathToUploadedFile = base_url() . 'upload/' . $image;

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
            if (empty($data)) {

                echo $this->json_data("0", "no data available", "");
            } else {
                echo $this->json_data("1", "", $data);
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
//die();
//echo "this page is success page".$t; die();
        $payumonyid = $this->input->post('txnid');
        $paydate = $_POST['addedon'];
        $amount = $_POST['net_amount_debit'];
        $status = $_POST['status'];
//$uid = "";
        $jobid = $this->uri->segment(4);
        $wallet_amt = $this->uri->segment(5);
        $method = $this->uri->segment(6);
        $data1 = $this->service_model->master_fun_get_tbl_val("job_master", array("id" => $jobid), array("id", "asc"));
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
                    <h4><b>Dear </b>' . $destail[0]['full_name'] . '</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Test has been book successfully. </p>
                    ' . $email_use_wallet . '
					 <p style="color:#7e7e7e;font-size:13px;"> Rs. ' . $amount . ' Paid using PayUMoney </p>
        <p style="color:#7e7e7e;font-size:13px;">Your Current Wallet balance is Rs. ' . $Current_wallet . '</p>
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
        $this->email->subject('Test Book Successfully');
        $this->email->message($message);
        $this->email->send();

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
        }
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
                    <h4><b>Money Added to wallet</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;">RS.' . $amount . ' successfully added to Wallet. Total Wallet Amount is RS.' . ($total + $amount) . ' </p>
                        <p style="color:#7e7e7e;font-size:13px;">Your Transaction id is ' . $payumonyid . '</p>
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
        $type = $this->input->get_post("type");
        $other_reference = $this->input->get_post('other_reference');
        $order_id = random_string('numeric', 13);
        $date = date('Y-m-d H:i:s');
        if ($user_fk != "" && $testfk != "") {
            $this->service_model->master_fun_insert("test", array("test" => json_encode($_GET)));
            $insert = $this->service_model->master_fun_insert("job_master", array("cust_fk" => $user_fk, "date" => $date, "status" => 0, "order_id" => $order_id, "doctor" => $doctor, "other_reference" => $other_reference, "payment_type" => "PayUMoney", "portal" => $type));
            $test = explode(',', $testfk);
            $price = 0;
            for ($i = 0; $i < count($test); $i++) {
                $insert_code = $this->service_model->master_fun_insert("job_test_list_master", array("job_fk" => $insert, "test_fk" => $test[$i]));
                $data = $this->service_model->master_fun_get_tbl_val("test_master", array("status" => 1, 'id' => $test[$i]), array("id", "asc"));
                $price = $price + $data[0]['price'];
            }
            $update = $this->service_model->master_fun_update("job_master", $insert, array("price" => $price));
            if ($update) {

                echo $this->json_data("1", "", array(array("id" => $insert)));
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function mail_issue() {
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
                    <h4 style="color:##c7c7c7;text-decoration: underline;">Customer Report & Issue</h4>
    <p>Hi! We just received your inquiry. We get back to you about your order as soon as possible. For your records, your support ticket number is  ' . $ticket . '. Include it in any future correspondence you might send. </p>
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
            $subject = $subject . " Issue id " . $ticket;
            $this->email->to($data[0]['email']);
            $this->email->from("donotreply@airmedpathlabs.com", "AirmedLabs");
            $this->email->subject($subject);
            $this->email->message($message);
            $this->email->send();

            /* pinkesh code start */
            $config['mailtype'] = 'html';

            $this->email->initialize($config);

            $messagen = '<div style="background:#a3a3a3 !important;font-family: "Roboto",sans-serif;">
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
                    <h4 style="color:##c7c7c7;text-decoration: underline;">Customer Report & Issue</h4>
<p style="color:#7e7e7e;font-size:13px;"><b>Ticket Id:</b> ' . $ticket . ' </p>                        
<p style="color:#7e7e7e;font-size:13px;"><b>Message:</b> ' . $message1 . ' </p>
    
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
        $data = $this->service_model->master_fun_get_tbl_val("package_master", array("status" => 1), array("title", "asc"), array("id", "title", "image,desc_app"));
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

        $MERCHANT_ID = "5669867";
        $MERCHANT_KEY = "IcZRGO7S";
        $SALT = "spjyl2ubfa";
        $MERCHANT_ID = "5669867";
        $MERCHANT_KEY = "IcZRGO7S";
        $SALT = "spjyl2ubfa";
        $MERCHANT_KEY = "gtKFFx";
        $SALT = "eCwWELxi";
        $URL = "https://test.payu.in/_payment";
        $URL = "https://secure.payu.in/_payment";
        $MERCHANT_ID = "5669867";
        $MERCHANT_KEY = "IcZRGO7S";
        $SALT = "spjyl2ubfa";
        $configuration = array("MERCHANT_ID" => $MERCHANT_ID, "MERCHANT" => $MERCHANT_KEY, "SALT" => $SALT, 'URl' => $URL, "WalletIsUsedInPackagePayment" => "0");
        $data3 = $this->service_model->master_fun_get_tbl_val("offer_master", array("status" => 1), array("id", "asc"));
        if (empty($data)) {
            echo $this->json_data("0", "no data available", "");
        } else {
            echo $this->json_data("1", "", array(array("data1" => $data3, "data2" => $data, "pay_conf" => $configuration)));
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

        $user_fk = $this->input->get_post('user_id');
        $package = $this->input->get_post('package_id');
        $amount = $this->input->get_post('price');
        $type = $this->input->get_post("type");
        $order_id = random_string('numeric', 13);
        $date = date('Y-m-d H:i:s');
        $query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $user_fk), array("id", "desc"));
        $total = $query[0]['total'];
        if ($total >= $amount) {
            if ($user_fk != "" && $amount != "") {

                $insert1 = $this->service_model->master_fun_insert("job_master", array("cust_fk" => $user_fk, "price" => $amount, "date" => $date, "order_id" => $order_id, "payment_type" => "wallet", "portal" => $type));

                $insert = $this->service_model->master_fun_insert("book_package_master", array("cust_fk" => $user_fk, "package_fk" => $package, "date" => $date, "order_id" => $order_id, "job_fk" => $insert1));

                if ($insert) {

                    $data1 = array(
                        "cust_fk" => $user_fk,
                        "debit" => $amount,
                        "total" => $total - $amount,
                        "package_fk" => $insert,
                        "created_time" => date('Y-m-d H:i:s')
                    );
                    $insertwallet = $this->service_model->master_fun_insert("wallet_master", $data1);
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



                    $insert = $this->service_model->master_fun_insert("wallet_master", $data1);

                    $query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $user_fk), array("id", "desc"));
                    $Current_wallet = $query[0]['total'];
                    $config['mailtype'] = 'html';
                    $this->email->initialize($config);
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

        $order_id = random_string('numeric', 13);
        $date = date('Y-m-d H:i:s');

        $data = array("cust_fk" => $userfk1, "package_fk" => $packageid, "date" => $date, "order_id" => $order_id);

        //	print_r($data);
        //	die();
        $package_user = $this->service_model->master_fun_get_tbl_val("package_master", array("status" => 1, "id" => $packageid), array("id", "asc"));
        $price = $package_user[0]['d_price'];

        $insert1 = $this->service_model->master_fun_insert("job_master", array("cust_fk" => $userfk1, "price" => $price, "date" => $date, "order_id" => $order_id, "payment_type" => "PayUMoney", "status" => 6, "portal" => $type));

        $insert = $this->service_model->master_fun_insert("book_package_master", array("cust_fk" => $userfk1, "package_fk" => $packageid, "date" => $date, "order_id" => $order_id, "job_fk" => $insert1));

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
                $insert12 = $this->service_model->master_fun_insert("wallet_master", $data1);
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

        $query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $userfk1), array("id", "desc"));
        $Current_wallet = $query[0]['total'];
        $config['mailtype'] = 'html';
        $this->email->initialize($config);
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
                    <h4><b>Dear </b>' . $destail[0]['full_name'] . '</h4>
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
        //$insert = $this->service_model->master_fun_insert("payment", $data1);

        $config['mailtype'] = 'html';
        $this->email->initialize($config);

        $message = '<div style="background:#a3a3a3 !important;font-family: "Roboto",sans-serif;">
<script src="https://use.fontawesome.com/44049e3dc5.js"></script>
    <div style="background: #6b6b6b none repeat scroll 0 0;float: left;padding: 7%;">
        <div style="border:1px solid #f1f1f1;border-bottom:1px solid ##c7c7c7;padding:1.5%;width:100%;float:left;background:#fff;">
            <div style="float:left;width:64%;">
                        <img alt="" src="' . base_url() . 'user_assets/images/logo.png" style="height: 100px;width:213px;"/>
            </div>
                <div style="float:right;text-align: right;width:33%;padding-top:7px;">

                
                
                </div>
                </div>
        <div style="padding:1.5%;width:100%;float:left;background-color:#fff;border:1px solid #f1f2f3;">
                <div style="padding:0 4%;">
                    <h4><b>Dear </b>' . $destail[0]['full_name'] . '</h4>
                        <p style="color:#7e7e7e;font-size:13px;">' . ucfirst($package_user[0]['title']) . ' Package Add successfully. </p>

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
        $this->email->subject('Package Successfully Added');
        $this->email->message($message);
        $this->email->send();
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

        $configmobile = $this->config->item('admin_alert_phone');
        foreach ($configmobile as $p_key) {
            $mb_length = strlen($p_key);
            $configmobile = $p_key;
            //$notification->send($configmobile, $sms_message);
            $this->service_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $configmobile, "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
        }
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
        $uid = $this->input->get_post('uid');
        $address = $this->input->get_post('address');
        $city = $this->input->get_post('city');
        $state = $this->input->get_post('state');
        $pin = $this->input->get_post('pin');
        $select_tests = $this->input->get_post('select_tests');
        $total = $this->input->get_post('total');
        $docid = $this->input->get_post('docid');
        $reference_by = $this->input->get_post('reference_by');
        $method = $this->input->get_post('wallet');
        $test_city = $this->input->get_post('test_city');
        $type = $this->input->get_post("type");
        $order_id = random_string('numeric', 13);
        $date = date('Y-m-d H:i:s');
        if ($uid != NULL && $select_tests != Null && $total != Null) {
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
                "portal" => $this->input->get_post("type")
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
                    <h4><b>You have one more refferal</b></h4>
                        <p><b>Dear </b>  ' . ucfirst($ref_name) . '</p>
                        <p style="color:#7e7e7e;font-size:13px;">Congratulation You have One more refferal  </p>
                        <p style="color:#7e7e7e;font-size:13px;">Rs.100 has been Credited in your wallet</p>
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
                    $data = $this->service_model->master_fun_get_tbl_val("test_master", array("status" => 1, 'id' => $test[$i]), array("id", "asc"));
                    $price = $price + $data[0]['price'];
                    $test_name_mail[$i] = $data[0]['test_name'];
                }
                $destail = $this->service_model->master_fun_get_tbl_val("customer_master", array("status" => 1, "id" => $uid), array("id", "asc"));
                /* sms send start */
                $sms_message = $this->service_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "book_login"), array("id", "asc"));
                $user = $this->service_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "id" => $uid), array("id", "asc"));
                $sms_message = preg_replace("/{{NAME}}/", ucfirst($user[0]['full_name']), $sms_message[0]["message"]);
                $sms_message = preg_replace("/{{MOBILE}}/", ucfirst($user[0]['mobile']), $sms_message);
                $sms_message = preg_replace("/{{TESTPACK}}/", 'Test', $sms_message);
                $this->load->helper("sms");
                $notification = new Sms();
                $mobile = $user[0]['mobile'];
                if ($mobile != NULL) {
                    $notification->send($mobile, $sms_message);
                }
                //$this->service_model->master_fun_insert("test", array("test"=>$get_phone."-".$sms_message));
                /* sms send end */
                $config['mailtype'] = 'html';
                $this->email->initialize($config);

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
                    <h4><b>Dear </b>' . $destail[0]['full_name'] . '</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Test has been book successfully. </p>
                        <p style="color:#7e7e7e;font-size:13px;">You booked ' . implode($test_name_mail, ',') . '</p>
<p style="color:#7e7e7e;font-size:13px;"> Your Test Amount is Rs. ' . $price . '  </p>
' . $payfrom_wallet . '
        <p style="color:#7e7e7e;font-size:13px;"> Payment Type : ' . $payemnttype . '</p>
		<p style="color:#7e7e7e;font-size:13px;"> Mobile : ' . $destail[0]['mobile'] . '</p>
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
                $this->email->cc($this->config->item('admin_booking_email'));
                $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
                $this->email->subject('Test Book Successfully');
                $this->email->message($message);
                $this->email->send();

                /* Nishit code start */
                $mobile = $destail[0]['mobile'];
                $mobile = ucfirst($destail[0]['full_name']) . "(" . $mobile . ")";
                $test_package = implode($test_name_mail, ',');
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

                echo $this->json_data("1", "", array(array("msg" => "Your request successfully send.")));
            }
        } else {
            echo $this->json_data("0", "All fields are required.", "");
        }
    }

    function cash_on_delivery_package() {
        $uid = $this->input->get_post('uid');
        $address = $this->input->get_post('address');
        $city = $this->input->get_post('city');
        $state = $this->input->get_post('state');
        $pin = $this->input->get_post('pin');
        $select_package = $this->input->get_post('select_package');
        $total = $this->input->get_post('total');
        $docid = $this->input->get_post('docid');
        $reference_by = $this->input->get_post('reference_by');
        $method = $this->input->get_post('wallet');
        $test_city = $this->input->get_post('test_city');
        $type = $this->input->get_post("type");
        $order_id = random_string('numeric', 13);
        $date = date('Y-m-d H:i:s');
        if ($uid != NULL && $select_package != Null && $total != Null) {

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
                "portal" => $type
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
                    <h4><b>You have one more refferal</b></h4>
                        <p><b>Dear </b> ' . ucfirst($ref_name) . '</p>
                        <p style="color:#7e7e7e;font-size:13px;">Congratulation You have One more refferal  </p>
                        <p style="color:#7e7e7e;font-size:13px;">Rs.100 has been Credited in your wallet</p>
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
                        $update = $this->service_model->master_fun_update1("job_master", array("id" => $insert), array("payable_amount" => $total));
                    } else {
                        $update = $this->service_model->master_fun_update1("job_master", array("id" => $insert), array("payable_amount" => $total));
                    }
                } else {

                    $update = $this->service_model->master_fun_update1("job_master", array("id" => $insert), array("payable_amount" => $total));
                }

                $test = explode(",", $select_package);
                foreach ($test as $key) {
                    $this->service_model->master_fun_insert("book_package_master", array("job_fk" => $insert, "date" => $date, "order_id" => $order_id, "package_fk" => $key, "cust_fk" => $uid, "type" => "2"));
                }

                $data = $this->service_model->master_fun_get_tbl_val("package_master", array("status" => 1, 'id' => $select_package), array("id", "asc"));
                $packname = $data[0]['title'];
                $destail = $this->service_model->master_fun_get_tbl_val("customer_master", array("status" => 1, "id" => $uid), array("id", "asc"));

                $config['mailtype'] = 'html';
                $this->email->initialize($config);

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
                    <h4><b>Dear </b>' . $destail[0]['full_name'] . '</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Package has been book successfully. </p>
                        <p style="color:#7e7e7e;font-size:13px;">You booked ' . $packname . '</p>
<p style="color:#7e7e7e;font-size:13px;"> Your Package Amount is Rs. ' . $total . '  </p>
' . $payfrom_wallet . '
        <p style="color:#7e7e7e;font-size:13px;"> Payment Type : ' . $payemnttype . '</p>
		<p style="color:#7e7e7e;font-size:13px;"> Mobile : ' . $destail[0]['mobile'] . '</p>
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
                $this->email->cc($this->config->item('admin_booking_email'));
                $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
                $this->email->subject('Package Book Successfully');
                $this->email->message($message);
                $this->email->send();

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
                $sms_message = $this->service_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "book_login"), array("id", "asc"));
                $sms_message = preg_replace("/{{NAME}}/", ucfirst($user_details[0]["full_name"]), $sms_message[0]["message"]);
                $sms_message = preg_replace("/{{TESTPACK}}/", 'Package', $sms_message);
                $sms_message = preg_replace("/{{MOBILE}}/", $user_details[0]['mobile'], $sms_message);
                $mobile = $user_details[0]['mobile'];
                $notification->send($mobile, $sms_message);
                $this->service_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $mobile, "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
                /* Nishit send sms code end */
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
        $this->load->model("user_model");
        $this->load->helper("sms");
        $notification = new Sms();
        /* Send report start */
        if (date("H:i") == "12:00" || date("H:i") == "15:00" || date("H:i") == "18:00") {
            $data['total_revenue'] = $this->user_model->total_revenue();
            $data['total_due_amount'] = $this->user_model->total_due();
            $job_status = $this->user_model->get_job_status();

            $today_total_revenue = $data['total_revenue'][0]["revenue"];
            $today_total_due = $data['total_due_amount'][0]["due_amount"];
            $total_paid = $today_total_revenue - $today_total_due;

            $total_jobs = 0;
            $waiting_for_approve = 0;
            $approved = 0;
            $sample_collected = 0;
            $processing = 0;
            $completed = 0;
            foreach ($job_status as $key) {
                $total_jobs = $total_jobs + $key["count"];
                if ($key["status"] == 1) {
                    $waiting_for_approve = $key["count"];
                }

                if ($key["status"] == 6) {
                    $approved = $key["count"];
                }

                if ($key["status"] == 7) {
                    $sample_collected = $key["count"];
                }

                if ($key["status"] == 8) {
                    $processing = $key["count"];
                }

                if ($key["status"] == 2) {
                    $completed = $key["count"];
                }
            }
            $mobile = $this->config->item('admin_report_sms');
            $sms_message = $this->master_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "daily_report"), array("id", "asc"));
            $sms_message = preg_replace("/{{TJBS}}/", $total_jobs, $sms_message[0]["message"]);
            $sms_message = preg_replace("/{{WFA}}/", $waiting_for_approve, $sms_message);
            $sms_message = preg_replace("/{{APR}}/", $approved, $sms_message);
            $sms_message = preg_replace("/{{SC}}/", $sample_collected, $sms_message);
            $sms_message = preg_replace("/{{PRC}}/", $processing, $sms_message);
            $sms_message = preg_replace("/{{CPTD}}/", $completed, $sms_message);
            /* $sms_message = preg_replace("/{{TRVN}}/", "Rs.".$today_total_revenue, $sms_message);
              $sms_message = preg_replace("/{{TPID}}/", "Rs.".$total_paid, $sms_message);
              $sms_message = preg_replace("/{{TDUE}}/", "Rs.".$today_total_due, $sms_message); */
            foreach ($mobile as $key) {
                $notification::send($key, $sms_message);
            }
        }
        /* Send report end */
        $sms_details = $this->service_model->master_fun_get_tbl_val("admin_alert_sms", array("is_send" => "0"), array("id", "asc"));
        foreach ($sms_details as $key) {
            $notification->send($key["mobile_no"], $key["message"]);
            $this->service_model->master_fun_update1("admin_alert_sms", array("id" => $key["id"]), array('is_send' => 1));
        }
        echo $this->json_data("1", "", "success");
    }

    function dashboard_1() {
        $data = array();
        $data = array(array("graph" => array(array("name" => "All", "per" => "100", "value" => "100", "color" => "#e85629"), array("name" => "Critical", "per" => "50", "value" => "50", "color" => "#f42e48"), array("name" => "Semi-Critical", "per" => "20", "value" => "20", "color" => "#FFD600"), array("name" => "Normal", "per" => "25", "value" => "25", "color" => "#33691E"), array("name" => "Pending", "per" => "5", "value" => "5", "color" => "#2e4de8")), "total_payment" => "5000", "total_payment_collection" => "₹7000"));
        echo $this->json_data("1", "", $data);
    }

    function dashboard_2() {
        $data = array();
        $data = array(array("graph" => array(array("name" => "All", "per" => "100", "value" => "100", "color" => "#e85629"), array("name" => "Critical", "per" => "25", "value" => "25", "color" => "#f42e48"), array("name" => "Semi-Critical", "per" => "45", "value" => "45", "color" => "#FFD600"), array("name" => "Normal", "per" => "25", "value" => "25", "color" => "#33691E"), array("name" => "Pending", "per" => "5", "value" => "5", "color" => "#2e4de8")), "total_payment" => "5000", "total_payment_collection" => "₹7000"));
        echo $this->json_data("1", "", $data);
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
            $this->service_model->master_fun_insert("doctor_req_discount", $data_array);
            echo $this->json_data("1", "", array());
        } else {
            echo $this->json_data("0", "All fields are required", "");
        }
    }

}

?>
