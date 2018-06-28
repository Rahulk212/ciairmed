<?php

class Service extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->library('email');
        $this->load->model('service_model');
        $this->load->helper('security');
        $this->load->helper('string');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST');
        header("Access-Control-Allow-Headers: X-Requested-With");
    }

    public function index() {
        
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
        $code = random_string('alnum', 6);
        $confirm_code = random_string('alnum', 6);
        if ($mobile != NULL && $email != null && $password != null && $gender != null) {
            $row = $this->service_model->master_num_rows("customer_master", array("email" => $email, "status" => 1));
            if ($row == 0) {
                $insert = $this->service_model->master_fun_insert("customer_master", array("full_name" => $name, "gender" => $gender, "email" => $email, "password" => $password, "mobile" => $mobile, "active" => 0, "confirm_code" => $confirm_code));
                $insert_code = $this->service_model->master_fun_insert("refer_code_master", array("cust_fk" => $insert, "refer_code" => $code, "used_code" => $usedcode));
               
					
					// code for refer price
					 $data = $this->service_model->master_fun_get_tbl_val("refer_code_master", array("refer_code" => $usedcode), array("id", "asc"));
					
					 //print_r($data);
					 //die();
					 if(!empty($data)){
						 
						 $custfk = $data[0]['cust_fk'];
						 
						  $data1 = $this->service_model->master_fun_get_tbl_val("customer_master", array("id" =>$custfk), array("id", "asc"));
						  $refemail = $data1[0]['email'];
						  $ref_name = $data1[0]['full_name'];
						  
						 $query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $custfk), array("id", "desc"));
			$total = $query[0]['total'];
						 
						 $data = array(
                "cust_fk" => $custfk,
                "credit" => "100",
                "total" =>$total+100,
                "type"=> "referral code",
                "created_time" => date('Y-m-d H:i:s')
            );
            $insert1 = $this->service_model->master_fun_insert("wallet_master", $data);
						 
						 
						 $config['mailtype'] = 'html';
                $this->email->initialize($config);

$message='<div style="background:#a3a3a3 !important;font-family: "Roboto",sans-serif;">
<script src="https://use.fontawesome.com/44049e3dc5.js"></script>
    <div style="background: #6b6b6b none repeat scroll 0 0;float: left;padding: 7%;">
        <div style="border:1px solid #f1f1f1;border-bottom:1px solid #67B1A3;padding:1.5%;width:100%;float:left;background:#fff;">
            <div style="float:left;width:64%;">
                        <img alt="" src="'.base_url().'user_assets/images/logo.png" style="height: 100px;width:213px;"/>
            </div>
                <div style="float:right;text-align: right;width:33%;padding-top:7px;">

                <p style=" width:100%;color: #111111;font-weight: 500;float:right;margin:0px 0px 5px 0px;padding:0;"><img src="'.base_url().'user_assets/images/message.png" style="height:10px;width:10px;"> <a href="mailto:info@airmedpathlabs.com" style="text-decoration:none;color:#7e7e7e;">info@airmedpathlabs.com</p>
                <p style=" width:100%;color: #111111;font-weight: 500;float:right;margin:0;padding:0;"><img src="'.base_url().'user_assets/images/fa-phone-2x.png" style="height:15px;width:15px;"> +917043215052 </p>
                
                </div>
                </div>
        <div style="padding:1.5%;width:100%;float:left;background-color:#fff;border:1px solid #f1f2f3;">
                <div style="padding:0 4%;">
                    <h4><b>You have one more refferal</b></h4>
                        <p>Dear, '.ucfirst($ref_name) .'</p>
                        <p style="color:#7e7e7e;font-size:13px;">Congratulation You have One more refferal  </p>
                        <p style="color:#7e7e7e;font-size:13px;">Rs.100 has been Credited in your wallet</p>
                </div>
        </div>
        <div style="width:100%;float:left;background:#F5F5F5;padding:1.5%;color:#828282;font-size:12px;border:1px solid #dfdfdf;text-align:center;">
                <a href="'.base_url().'user_master/about_us" style="color:#515151;text-decoration:none;">About Us</a> |
                <a href="'.base_url().'user_master/contact_us" style="color:#515151;text-decoration:none;">Contact Us</a> |
                <a href="'.base_url().'user_login" style="color:#515151;text-decoration:none;">Login</a> |
                <a href="'.base_url().'user_master/collection" style="color:#515151;text-decoration:none;">Home-collection</a> |
                <a href="https://www.facebook.com/airmedpathlabs" style="color:#515151;text-decoration:none;"><i aria-hidden="true"><img src="'.base_url().'user_assets/images/face_book_icon1.jpeg" style="height:15px;width:15px;"></i></a>                  
        </div>
        <div style="width:100%;float:left;background:#333333;padding:1.5%;color:#ccc;font-size:12px;border:1px solid #323232;text-align:center;">
                Copyright @ 2016 Airmed PATH LAB. All rights reserved
        </div>
    </div>
</div>';	

                $this->email->to($refemail);
                $this->email->from("donotreply@airmed.com");
                $this->email->subject("Refferal Amount Credited");
                $this->email->message($message);
                $this->email->send();
						 
					 }
				// end code for refer price
				 $config['mailtype'] = 'html';
                $this->email->initialize($config);

$message='<div style="background:#a3a3a3 !important;font-family: "Roboto",sans-serif;">
<script src="https://use.fontawesome.com/44049e3dc5.js"></script>
    <div style="background: #6b6b6b none repeat scroll 0 0;float: left;padding: 7%;">
        <div style="border:1px solid #f1f1f1;border-bottom:1px solid #67B1A3;padding:1.5%;width:100%;float:left;background:#fff;">
            <div style="float:left;width:64%;">
                        <img alt="" src="'.base_url().'user_assets/images/logo.png" style="height: 100px;width:213px;"/>
            </div>
                <div style="float:right;text-align: right;width:33%;padding-top:7px;">

                <p style=" width:100%;color: #111111;font-weight: 500;float:right;margin:0px 0px 5px 0px;padding:0;"><img src="'.base_url().'user_assets/images/message.png" style="height:10px;width:10px;"> <a href="mailto:info@airmedpathlabs.com" style="text-decoration:none;color:#7e7e7e;">info@airmedpathlabs.com</p>
                <p style=" width:100%;color: #111111;font-weight: 500;float:right;margin:0;padding:0;"><img src="'.base_url().'user_assets/images/fa-phone-2x.png" style="height:15px;width:15px;"> +917043215052 </p>
                
                </div>
                </div>
        <div style="padding:1.5%;width:100%;float:left;background-color:#fff;border:1px solid #f1f2f3;">
                <div style="padding:0 4%;">
                    <h4><b>Confirm Your Register</b></h4>
                        <p>Dear, '.ucfirst($name) .'</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank you for registering for the Airmed PATH LAB. </p>
                        <p style="color:#7e7e7e;font-size:13px;">Please confirm Your Email to get all services provided by Airmed PATH LAB</p>
								<a href="'.base_url().'register/confirm_email/'.$confirm_code.'" style="background: rgb(103, 177, 163) none repeat scroll 0 0;color: #f9f9f9;padding: 1%;text-decoration: none;">Confirm</a>

                </div>
        </div>
        <div style="width:100%;float:left;background:#F5F5F5;padding:1.5%;color:#828282;font-size:12px;border:1px solid #dfdfdf;text-align:center;">
                <a href="'.base_url().'user_master/about_us" style="color:#515151;text-decoration:none;">About Us</a> |
                <a href="'.base_url().'user_master/contact_us" style="color:#515151;text-decoration:none;">Contact Us</a> |
                <a href="'.base_url().'user_login" style="color:#515151;text-decoration:none;">Login</a> |
                <a href="'.base_url().'user_master/collection" style="color:#515151;text-decoration:none;">Home-collection</a> |
                <a href="https://www.facebook.com/airmedpathlabs" style="color:#515151;text-decoration:none;"><i aria-hidden="true"><img src="'.base_url().'user_assets/images/face_book_icon1.jpeg" style="height:15px;width:15px;"></i></a>                  
        </div>
        <div style="width:100%;float:left;background:#333333;padding:1.5%;color:#ccc;font-size:12px;border:1px solid #323232;text-align:center;">
                Copyright @ 2016 Airmed PATH LAB. All rights reserved
        </div>
    </div>
</div>';	

                $this->email->to($email);
                $this->email->from("donotreply@airmed.com");
                $this->email->subject("Patholab Confirm email");
                $this->email->message($message);
                $this->email->send();

                echo $this->json_data("1", "", array(array("id" => $insert, "msg" => "Please Confirm Your Email Address")));
            } else {
                echo $this->json_data("0", "Email Allready Registered", "");
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

                echo $this->json_data("0", "Your Email Address is not Activted yet", "");
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
	function doctor_register(){
		
		        $email = $this->input->get_post('email');
        $password = $this->input->get_post('password');
		        $name = $this->input->get_post('name');
        $mobile = $this->input->get_post('mobile');
		       
        $address = $this->input->get_post('address');
		if($name !=null &  $email!=null && $password!=null && $mobile!=null && $address!=null ){
			
			 $row = $this->service_model->master_num_rows("doctor_master", array("email" => $email, "status" => 1));
            if ($row == 0) {
                $insert = $this->service_model->master_fun_insert("doctor_master", array("full_name" => $name,"email" => $email, "password" => $password, "mobile" => $mobile,"address"=>$address,"status"=>2));
                echo $this->json_data("1", "", array(array("id" => $insert, "msg" => "Register Successfully")));
            } else {
                echo $this->json_data("0", "Email Allready Registered", "");
            }
			
			
		}else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
		
		
		
	}

    function doctor_login() {
        $email = $this->input->get_post('email');
        $password = $this->input->get_post('password');
        //$device_id = $this->input->get_post('device_id');
        if ($email != NULL && $password != NULL) {
            $row = $this->service_model->master_num_rows("doctor_master", array("email" => $email, "password" => $password,"status !="=>0));
            $data = $this->service_model->master_fun_get_tbl_val("doctor_master", array("email" => $email, "password" => $password, "status" => 1), array("id", "asc"));
            if ($row == 1) {
                //$update = $this->service_model->master_fun_update("customer_master",$data[0]['id'],array("device_id"=>$device_id));
				$row1 = $this->service_model->master_num_rows("doctor_master", array("email" => $email, "password" => $password, "status" => 2));
				if ($row1 == 1) {
					 echo $this->json_data("0", "Your Account not Confirm", "");
				}else{
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
$message='<div style="background:#a3a3a3 !important;font-family: "Roboto",sans-serif;">
<script src="https://use.fontawesome.com/44049e3dc5.js"></script>
    <div style="background: #6b6b6b none repeat scroll 0 0;float: left;padding: 7%;">
        <div style="border:1px solid #f1f1f1;border-bottom:1px solid #67B1A3;padding:1.5%;width:100%;float:left;background:#fff;">
            <div style="float:left;width:64%;">
                        <img alt="" src="'.base_url().'user_assets/images/logo.png" style="height: 100px;width:213px;"/>
            </div>
                <div style="float:right;text-align: right;width:33%;padding-top:7px;">

                <p style=" width:100%;color: #111111;font-weight: 500;float:right;margin:0px 0px 5px 0px;padding:0;"><img src="'.base_url().'user_assets/images/message.png" style="height:10px;width:10px;"> <a href="mailto:info@airmedpathlabs.com" style="text-decoration:none;color:#7e7e7e;">info@airmedpathlabs.com</p>
                <p style=" width:100%;color: #111111;font-weight: 500;float:right;margin:0;padding:0;"><img src="'.base_url().'user_assets/images/fa-phone-2x.png" style="height:15px;width:15px;"> +917043215052 </p>
                
                </div>
                </div>
        <div style="padding:1.5%;width:100%;float:left;background-color:#fff;border:1px solid #f1f2f3;">
                <div style="padding:0 4%;">
                    <h4><b>Forgot Password</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;">You recently requested to reset your password for your Airmed PATH LABS Account. Your password is '.$password.' </p>
                        <p style="color:#7e7e7e;font-size:13px;">If you did not request , please ignore this email or reply to let us know.</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>
        </div>
        <div style="width:100%;float:left;background:#F5F5F5;padding:1.5%;color:#828282;font-size:12px;border:1px solid #dfdfdf;text-align:center;">
                <a href="'.base_url().'user_master/about_us" style="color:#515151;text-decoration:none;">About Us</a> |
                <a href="'.base_url().'user_master/contact_us" style="color:#515151;text-decoration:none;">Contact Us</a> |
                <a href="'.base_url().'user_login" style="color:#515151;text-decoration:none;">Login</a> |
                <a href="'.base_url().'user_master/collection" style="color:#515151;text-decoration:none;">Home-collection</a> |
                <a href="https://www.facebook.com/airmedpathlabs" style="color:#515151;text-decoration:none;"><i aria-hidden="true"><img src="'.base_url().'user_assets/images/face_book_icon1.jpeg" style="height:15px;width:15px;"></i></a>                  
        </div>
        <div style="width:100%;float:left;background:#333333;padding:1.5%;color:#ccc;font-size:12px;border:1px solid #323232;text-align:center;">
                Copyright @ 2016 Airmed PATH LAB. All rights reserved
        </div>
    </div>
</div>';
            $message .= "Thanks <br/>";
            $this->email->to($email);
            $this->email->from('donotreply@airmed.com', 'Airmed PATH LAB');
        $this->email->subject('Patholab Reset your forgotten Password');
        $this->email->message($message);
        $this->email->send();
            echo $this->json_data("1", "", array(array("msg" => "Password has been Sent on Your Email")));
        } else {

            echo $this->json_data("0", "Parameter not passed", "");
        }
    }
	
	function doctor_view_profile() {
		$userid = $this->input->get_post('user_id');
        $data = $this->service_model->master_fun_get_tbl_val("doctor_master", array("status" => 1,'id'=>$userid), array("id", "asc"));
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
                
                "full_name" =>$name,
                "email" =>$email,
                "mobile" =>$mobile,
                "address" =>$address,
				"state"=>$state,
				"city"=>$city,
				"pic"=>$pic,
				"gender"=>$gender,
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
$message='<div style="background:#a3a3a3 !important;font-family: "Roboto",sans-serif;">
<script src="https://use.fontawesome.com/44049e3dc5.js"></script>
    <div style="background: #6b6b6b none repeat scroll 0 0;float: left;padding: 7%;">
        <div style="border:1px solid #f1f1f1;border-bottom:1px solid #67B1A3;padding:1.5%;width:100%;float:left;background:#fff;">
            <div style="float:left;width:64%;">
                        <img alt="" src="'.base_url().'user_assets/images/logo.png" style="height: 100px;width:213px;"/>
            </div>
                <div style="float:right;text-align: right;width:33%;padding-top:7px;">

                <p style=" width:100%;color: #111111;font-weight: 500;float:right;margin:0px 0px 5px 0px;padding:0;"><img src="'.base_url().'user_assets/images/message.png" style="height:10px;width:10px;"> <a href="mailto:info@airmedpathlabs.com" style="text-decoration:none;color:#7e7e7e;">info@airmedpathlabs.com</p>
                <p style=" width:100%;color: #111111;font-weight: 500;float:right;margin:0;padding:0;"><img src="'.base_url().'user_assets/images/fa-phone-2x.png" style="height:15px;width:15px;"><span style="color: rgb(100, 100, 100); font-size: 11px;"> +917043215052 </span></p>
                
                </div>
                </div>
        <div style="padding:1.5%;width:100%;float:left;background-color:#fff;border:1px solid #f1f2f3;">
                <div style="padding:0 4%;">
                    <h4><b>Password Change Airmed PATH LAB</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;font-weight: bold;">Dear, '.ucfirst($row[0]['full_name']) .'</p>
                        <p style="color:#7e7e7e;font-size:13px;">Your Password Successfully Change. </p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>
        </div>
        <div style="width:100%;float:left;background:#F5F5F5;padding:1.5%;color:#828282;font-size:12px;border:1px solid #dfdfdf;text-align:center;">
                <a href="'.base_url().'user_master/about_us" style="color:#515151;text-decoration:none;">About Us</a> |
                <a href="'.base_url().'user_master/contact_us" style="color:#515151;text-decoration:none;">Contact Us</a> |
                <a href="'.base_url().'user_login" style="color:#515151;text-decoration:none;">Login</a> |
                <a href="'.base_url().'user_master/collection" style="color:#515151;text-decoration:none;">Home-collection</a> |
                <a href="https://www.facebook.com/airmedpathlabs" style="color:#515151;text-decoration:none;"><i aria-hidden="true"><img src="'.base_url().'user_assets/images/face_book_icon1.jpeg" style="height:15px;width:15px;"></i></a>                  
        </div>
        <div style="width:100%;float:left;background:#333333;padding:1.5%;color:#ccc;font-size:12px;border:1px solid #323232;text-align:center;">
                Copyright @ 2016 Airmed PATH LABS. All rights reserved
        </div>
    </div>
</div>';	
        $this->email->to($row[0]['email']);
//$this->email->to("jeel@virtualheight.com");
       $this->email->from("donotreply@airmed.com",'Airmed PATH LAB');
        $this->email->subject("Password changed for Airmed PATH LAB");
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
$message='<div style="background:#a3a3a3 !important;font-family: "Roboto",sans-serif;">
<script src="https://use.fontawesome.com/44049e3dc5.js"></script>
    <div style="background: #6b6b6b none repeat scroll 0 0;float: left;padding: 7%;">
        <div style="border:1px solid #f1f1f1;border-bottom:1px solid #67B1A3;padding:1.5%;width:100%;float:left;background:#fff;">
            <div style="float:left;width:64%;">
                        <img alt="" src="'.base_url().'user_assets/images/logo.png" style="height: 100px;width:213px;"/>
            </div>
                <div style="float:right;text-align: right;width:33%;padding-top:7px;">

                <p style=" width:100%;color: #111111;font-weight: 500;float:right;margin:0px 0px 5px 0px;padding:0;"><img src="'.base_url().'user_assets/images/message.png" style="height:10px;width:10px;"> <a href="mailto:info@airmedpathlabs.com" style="text-decoration:none;color:#7e7e7e;">info@airmedpathlabs.com</p>
                <p style=" width:100%;color: #111111;font-weight: 500;float:right;margin:0;padding:0;"><img src="'.base_url().'user_assets/images/fa-phone-2x.png" style="height:15px;width:15px;"> +917043215052 </p>
                
                </div>
                </div>
        <div style="padding:1.5%;width:100%;float:left;background-color:#fff;border:1px solid #f1f2f3;">
                <div style="padding:0 4%;">
                    <h4><b>Forgot Password</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;">You recently requested to reset your password for your Airmed PATH LABS Account. Your password is '.$password.' </p>
                        <p style="color:#7e7e7e;font-size:13px;">If you did not request , please ignore this email or reply to let us know.</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>
        </div>
        <div style="width:100%;float:left;background:#F5F5F5;padding:1.5%;color:#828282;font-size:12px;border:1px solid #dfdfdf;text-align:center;">
                <a href="'.base_url().'user_master/about_us" style="color:#515151;text-decoration:none;">About Us</a> |
                <a href="'.base_url().'user_master/contact_us" style="color:#515151;text-decoration:none;">Contact Us</a> |
                <a href="'.base_url().'user_login" style="color:#515151;text-decoration:none;">Login</a> |
                <a href="'.base_url().'user_master/collection" style="color:#515151;text-decoration:none;">Home-collection</a> |
                <a href="https://www.facebook.com/airmedpathlabs" style="color:#515151;text-decoration:none;"><i aria-hidden="true"><img src="'.base_url().'user_assets/images/face_book_icon1.jpeg" style="height:15px;width:15px;"></i></a>                  
        </div>
        <div style="width:100%;float:left;background:#333333;padding:1.5%;color:#ccc;font-size:12px;border:1px solid #323232;text-align:center;">
                Copyright @ 2016 Airmed PATH LAB. All rights reserved
        </div>
    </div>
</div>';
            $message .= "Thanks <br/> Patholab";
            $this->email->to($email);
            $this->email->from('donotreply@airmed.com', 'Airmed PATH LAB');
        $this->email->subject('Patholab Reset your forgotten Password');
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
$message='<div style="background:#a3a3a3 !important;font-family: "Roboto",sans-serif;">
<script src="https://use.fontawesome.com/44049e3dc5.js"></script>
    <div style="background: #6b6b6b none repeat scroll 0 0;float: left;padding: 7%;">
        <div style="border:1px solid #f1f1f1;border-bottom:1px solid #67B1A3;padding:1.5%;width:100%;float:left;background:#fff;">
            <div style="float:left;width:64%;">
                        <img alt="" src="'.base_url().'user_assets/images/logo.png" style="height: 100px;width:213px;"/>
            </div>
                <div style="float:right;text-align: right;width:33%;padding-top:7px;">

                <p style=" width:100%;color: #111111;font-weight: 500;float:right;margin:0px 0px 5px 0px;padding:0;"><img src="'.base_url().'user_assets/images/message.png" style="height:10px;width:10px;"> <a href="mailto:info@airmedpathlabs.com" style="text-decoration:none;color:#7e7e7e;">info@airmedpathlabs.com</p>
                <p style=" width:100%;color: #111111;font-weight: 500;float:right;margin:0;padding:0;"><img src="'.base_url().'user_assets/images/fa-phone-2x.png" style="height:15px;width:15px;"><span style="color: rgb(100, 100, 100); font-size: 11px;"> +917043215052 </span></p>
                
                </div>
                </div>
        <div style="padding:1.5%;width:100%;float:left;background-color:#fff;border:1px solid #f1f2f3;">
                <div style="padding:0 4%;">
                    <h4><b>Password Change Airmed PATH LAB</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;font-weight: bold;">Dear, '.ucfirst($row[0]['full_name']) .'</p>
                        <p style="color:#7e7e7e;font-size:13px;">Your Password Successfully Change. </p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>
        </div>
        <div style="width:100%;float:left;background:#F5F5F5;padding:1.5%;color:#828282;font-size:12px;border:1px solid #dfdfdf;text-align:center;">
                <a href="'.base_url().'user_master/about_us" style="color:#515151;text-decoration:none;">About Us</a> |
                <a href="'.base_url().'user_master/contact_us" style="color:#515151;text-decoration:none;">Contact Us</a> |
                <a href="'.base_url().'user_login" style="color:#515151;text-decoration:none;">Login</a> |
                <a href="'.base_url().'user_master/collection" style="color:#515151;text-decoration:none;">Home-collection</a> |
                <a href="https://www.facebook.com/airmedpathlabs" style="color:#515151;text-decoration:none;"><i aria-hidden="true"><img src="'.base_url().'user_assets/images/face_book_icon1.jpeg" style="height:15px;width:15px;"></i></a>                  
        </div>
        <div style="width:100%;float:left;background:#333333;padding:1.5%;color:#ccc;font-size:12px;border:1px solid #323232;text-align:center;">
                Copyright @ 2016 Airmed PATH LABS. All rights reserved
        </div>
    </div>
</div>';	
        $this->email->to($row[0]['email']);
//$this->email->to("jeel@virtualheight.com");
       $this->email->from("donotreply@airmed.com",'Airmed PATH LAB');
        $this->email->subject("Password changed for Airmed PATH LAB");
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
        $data = $this->service_model->master_fun_get_tbl_val("test_master", array("status" => 1), array("id", "asc"));
        if (empty($data)) {
            echo $this->json_data("0", "no data available", "");
        } else {
            echo $this->json_data("1", "", $data);
        }
    }

    function my_job() {
        $user_fk = $this->input->get_post('user_id');
        if ($user_fk != "") {
            $data = $this->service_model->my_job($user_fk);
            if (empty($data)) {
                echo $this->json_data("0", "no data available", "");
            } else {
				$newdata=array();
				foreach($data as $key){
					
					$key['test']=(($key['test']=="")?"":$key['test'].',' ).$key['packge_name'];
				$newdata[]	=$key;
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
				$newdata=array();
				foreach($data as $key){
					$key['test']=(($key['test']=="")?"":$key['test'].',' ).$key['packge_name'];
				$newdata[]	=$key;
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

$newdata=array();
				foreach($data as $key){
					$key['test']=(($key['test']=="")?"":$key['test'].',' ).$key['packge_name'];
				$newdata[]	=$key;
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
            //	$data = $this->service_model->master_fun_get_tbl_val("job_master", array("id"=>$jid,"status" => 1),array("id","asc"));

            $data1 = $this->service_model->view_test_report($jid);
			$data2 = $this->service_model->view_package_report($jid);
            $data = array_merge($data1,$data2);
			//print_r($data2);
			//die();
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

    function add_job() {
        $user_fk = $this->input->get_post('user_id');
        $testfk = $this->input->get_post('test_id');
        $amount = $this->input->get_post('total');
        $doctor = $this->input->get_post('doctor');
        $other_reference = $this->input->get_post('other_reference');
        $order_id = random_string('numeric', 13);
        $date = date('Y-m-d H:i:s');
        if ($user_fk != "" && $testfk != "") {
            $insert = $this->service_model->master_fun_insert("job_master", array("cust_fk" => $user_fk, "date" => $date, "order_id" => $order_id, "doctor" => $doctor, "other_reference" => $other_reference,"payment_type"=>"Wallet",));
            $test = explode(',', $testfk);
            $price = 0;
            $test_name_mail=array();
            for ($i = 0; $i < count($test); $i++) {
                $insert_code = $this->service_model->master_fun_insert("job_test_list_master", array("job_fk" => $insert, "test_fk" => $test[$i]));
                $data = $this->service_model->master_fun_get_tbl_val("test_master", array("status" => 1, 'id' => $test[$i]), array("id", "asc"));
                $price = $price + $data[0]['price'];
                $test_name_mail[$i]=$data[0]['test_name'];
            }
            $update = $this->service_model->master_fun_update("job_master", $insert, array("price" => $price));
            if ($update) {
                $query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $user_fk), array("id", "desc"));
                $total = $query[0]['total'];
				$query = $this->service_model->master_fun_get_tbl_val("offer_master", array("status" => 1), array("id", "desc"));
            $caseback_per = $query[0]['caseback_per'];
				$caseback_amount = ($price * $caseback_per)/100;
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
					"type"=> "Case Back",
                    "created_time" => date('Y-m-d H:i:s')
                );
				
				
                
				$insert = $this->service_model->master_fun_insert("wallet_master", $data1);
				
				$query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $user_fk), array("id", "desc"));
			$Current_wallet = $query[0]['total'];
			
 $destail = $this->service_model->master_fun_get_tbl_val("customer_master", array("status" => 1, "id" => $user_fk), array("id", "asc"));

        $config['mailtype'] = 'html';
        $this->email->initialize($config);
       
$message='<div style="background:#a3a3a3 !important;font-family: "Roboto",sans-serif;">
<script src="https://use.fontawesome.com/44049e3dc5.js"></script>
    <div style="background: #6b6b6b none repeat scroll 0 0;float: left;padding: 7%;">
        <div style="border:1px solid #f1f1f1;border-bottom:1px solid #67B1A3;padding:1.5%;width:100%;float:left;background:#fff;">
            <div style="float:left;width:64%;">
                        <img alt="" src="'.base_url().'user_assets/images/logo.png" style="height: 100px;width:213px;"/>
            </div>
                <div style="float:right;text-align: right;width:33%;padding-top:7px;">

                <p style=" width:100%;color: #111111;font-weight: 500;float:right;margin:0px 0px 5px 0px;padding:0;"><img src="'.base_url().'user_assets/images/message.png" style="height:10px;width:10px;"> <a href="mailto:info@airmedpathlabs.com" style="text-decoration:none;color:#7e7e7e;">info@airmedpathlabs.com</p>
                <p style=" width:100%;color: #111111;font-weight: 500;float:right;margin:0;padding:0;"><img src="'.base_url().'user_assets/images/fa-phone-2x.png" style="height:15px;width:15px;"> +917043215052 </p>
                
                </div>
                </div>
        <div style="padding:1.5%;width:100%;float:left;background-color:#fff;border:1px solid #f1f2f3;">
                <div style="padding:0 4%;">
                    <h4><b>Test Book Successfully</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Test has been book successfully. </p>
                        <p style="color:#7e7e7e;font-size:13px;">You booked '.implode($test_name_mail,',').'</p>
<p style="color:#7e7e7e;font-size:13px;"> Rs. '.$price.' Debited From your account. </p>
        <p style="color:#7e7e7e;font-size:13px;">Your Current balance is Rs. '.($total - $price).'</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>
        </div>
        <div style="width:100%;float:left;background:#F5F5F5;padding:1.5%;color:#828282;font-size:12px;border:1px solid #dfdfdf;text-align:center;">
                <a href="'.base_url().'user_master/about_us" style="color:#515151;text-decoration:none;">About Us</a> |
                <a href="'.base_url().'user_master/contact_us" style="color:#515151;text-decoration:none;">Contact Us</a> |
                <a href="'.base_url().'user_login" style="color:#515151;text-decoration:none;">Login</a> |
                <a href="'.base_url().'user_master/collection" style="color:#515151;text-decoration:none;">Home-collection</a> |
                <a href="https://www.facebook.com/airmedpathlabs" style="color:#515151;text-decoration:none;"><i aria-hidden="true"><img src="'.base_url().'user_assets/images/face_book_icon1.jpeg" style="height:15px;width:15px;"></i></a>                  
        </div>
        <div style="width:100%;float:left;background:#333333;padding:1.5%;color:#ccc;font-size:12px;border:1px solid #323232;text-align:center;">
                Copyright @ 2016 Airmed PATH LAB. All rights reserved
        </div>
    </div>
</div>';
        $this->email->to($destail[0]['email']);
        $this->email->from('donotreply@airmed.com', 'Airmed PATH LAB');
        $this->email->subject('Test Book Successfully');
        $this->email->message($message);
        $this->email->send();
		
		$message='<div style="background:#a3a3a3 !important;font-family: "Roboto",sans-serif;">
<script src="https://use.fontawesome.com/44049e3dc5.js"></script>
    <div style="background: #6b6b6b none repeat scroll 0 0;float: left;padding: 7%;">
        <div style="border:1px solid #f1f1f1;border-bottom:1px solid #67B1A3;padding:1.5%;width:100%;float:left;background:#fff;">
            <div style="float:left;width:64%;">
                        <img alt="" src="'.base_url().'user_assets/images/logo.png" style="height: 100px;width:213px;"/>
            </div>
                <div style="float:right;text-align: right;width:33%;padding-top:7px;">

                <p style=" width:100%;color: #111111;font-weight: 500;float:right;margin:0px 0px 5px 0px;padding:0;"><img src="'.base_url().'user_assets/images/message.png" style="height:10px;width:10px;"> <a href="mailto:info@airmedpathlabs.com" style="text-decoration:none;color:#7e7e7e;">info@airmedpathlabs.com</p>
                <p style=" width:100%;color: #111111;font-weight: 500;float:right;margin:0;padding:0;"><img src="'.base_url().'user_assets/images/fa-phone-2x.png" style="height:15px;width:15px;"> +917043215052 </p>
                
                </div>
                </div>
        <div style="padding:1.5%;width:100%;float:left;background-color:#fff;border:1px solid #f1f2f3;">
                <div style="padding:0 4%;">
                    <h4 style="color:#67B1A3;text-decoration: underline;">Cashback Credited Successfully</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Cashback Credited in your Wallet. </p>
                       
<p style="color:#7e7e7e;font-size:13px;"> Rs. '.$caseback_amount.' Credited in your account. </p>
		<p style="color:#7e7e7e;font-size:13px;">Your Current Wallet Amount is Rs. '.$Current_wallet.'</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>
        </div>
        <div style="width:100%;float:left;background:#F5F5F5;padding:1.5%;color:#828282;font-size:12px;border:1px solid #dfdfdf;text-align:center;">
                <a href="'.base_url().'user_master/about_us" style="color:#515151;text-decoration:none;">About Us</a> |
                <a href="'.base_url().'user_master/contact_us" style="color:#515151;text-decoration:none;">Contact Us</a> |
                <a href="'.base_url().'user_login" style="color:#515151;text-decoration:none;">Login</a> |
                <a href="'.base_url().'user_master/collection" style="color:#515151;text-decoration:none;">Home-collection</a> |
                <a href="https://www.facebook.com/airmedpathlabs" style="color:#515151;text-decoration:none;"><i aria-hidden="true"><img src="'.base_url().'user_assets/images/face_book_icon1.jpeg" style="height:15px;width:15px;"></i></a>                  
        </div>
        <div style="width:100%;float:left;background:#333333;padding:1.5%;color:#ccc;font-size:12px;border:1px solid #323232;text-align:center;">
                Copyright @ 2016 Airmed PATH LABS. All rights reserved
        </div>
    </div>
</div>';
        $this->email->to($destail[0]['email']);
        $this->email->from('donotreply@airmed.com', 'Airmed PATH LAB');
        $this->email->subject('CashBack');
        $this->email->message($message);
        $this->email->send();
	
	// Case Back Email end

		
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
        $total=0.00;
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
        $order_id = random_string('numeric', 13);
        $date = date('Y-m-d H:i:s');
        if ($image != NULL && $user_id != Null && $mobile != Null) {
            $insert = $this->service_model->master_fun_insert("prescription_upload", array("cust_fk" => $user_id, "image" => $image, "description" => $desc, "created_date" => $date, "order_id" => $order_id,"mobile"=>$mobile));
            $detail = $this->service_model->master_fun_get_tbl_val("customer_master", array("status" => 1, "id" => $user_id), array("id", "asc"));
	if ($insert) {
		$config['mailtype'] = 'html';      
		$this->email->initialize($config);
 $message='<div style="background:#a3a3a3 !important;font-family: "Roboto",sans-serif;">
<script src="https://use.fontawesome.com/44049e3dc5.js"></script>
    <div style="background: #6b6b6b none repeat scroll 0 0;float: left;padding: 7%;">
        <div style="border:1px solid #f1f1f1;border-bottom:1px solid #67B1A3;padding:1.5%;width:100%;float:left;background:#fff;">
            <div style="float:left;width:64%;">
                        <img alt="" src="'.base_url().'user_assets/images/logo.png" style="height: 100px;width:213px;"/>
            </div>
                <div style="float:right;text-align: right;width:33%;padding-top:7px;">

                <p style=" width:100%;color: #111111;font-weight: 500;float:right;margin:0px 0px 5px 0px;padding:0;"><img src="'.base_url().'user_assets/images/message.png" style="height:10px;width:10px;"> <a href="mailto:info@airmedpathlabs.com" style="text-decoration:none;color:#7e7e7e;">info@airmedpathlabs.com</p>
                <p style=" width:100%;color: #111111;font-weight: 500;float:right;margin:0;padding:0;"><img src="'.base_url().'user_assets/images/fa-phone-2x.png" style="height:15px;width:15px;"><span style="color: rgb(100, 100, 100); font-size: 11px;"> +917043215052 </span></p>
                
                </div>
                </div>
        <div style="padding:1.5%;width:100%;float:left;background-color:#fff;border:1px solid #f1f2f3;">
                <div style="padding:0 4%;">
                    <h4><b>Contact Detail</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;font-weight: bold;">Follow information shows Contact Person Detail :</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Customer Name : </b> '.ucfirst($detail[0]['full_name']).'</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Email : </b> '.$detail[0]['email'].'</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Mobile : </b> '.$mobile.'</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Description : </b> '.ucfirst($desc).'</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Uploaded file : </b> '.base_url().'upload/'.$image.'</p>
                </div>
        </div>
        <div style="width:100%;float:left;background:#F5F5F5;padding:1.5%;color:#828282;font-size:12px;border:1px solid #dfdfdf;text-align:center;">
                <a href="'.base_url().'user_master/about_us" style="color:#515151;text-decoration:none;">About Us</a> |
                <a href="'.base_url().'user_master/contact_us" style="color:#515151;text-decoration:none;">Contact Us</a> |
                <a href="'.base_url().'user_login" style="color:#515151;text-decoration:none;">Login</a> |
                <a href="'.base_url().'user_master/collection" style="color:#515151;text-decoration:none;">Home-collection</a> |
                <a href="https://www.facebook.com/airmedpathlabs" style="color:#515151;text-decoration:none;"><i aria-hidden="true"><img src="'.base_url().'user_assets/images/face_book_icon1.jpeg" style="height:15px;width:15px;"></i></a>                  
        </div>
        <div style="width:100%;float:left;background:#333333;padding:1.5%;color:#ccc;font-size:12px;border:1px solid #323232;text-align:center;">
                Copyright @ 2016 Airmed Path labs. All rights reserved
        </div>
    </div>
</div>';
		$pathToUploadedFile=base_url().'upload/'.$image;
			
        $this->email->to("hiten@virtualheight.com");
        $this->email->from("prescription@airmed.com","Airmed PATH LAB");
        $this->email->subject("New Prescription Uploaded");
        $this->email->message($message);
$this->email->attach($pathToUploadedFile);
        $this->email->send();
		
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
                $insert = $this->service_model->master_fun_insert("customer_master", array("full_name" => $name, "email" => $email, "device_id" => $device_id, "fbid" => $fb_id, "pic" => $pic));
                $insert_code = $this->service_model->master_fun_insert("refer_code_master", array("cust_fk" => $insert, "refer_code" => $code));
                $data = $this->service_model->master_fun_get_tbl_val("customer_master", array("id" => $insert, "status" => 1), array("id", "asc"));
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
				$email_use_wallet = '<p style="color:#7e7e7e;font-size:13px;"> Rs. '.$wallet_amt.' Debited From your Wallet. </p>';
                $insert = $this->service_model->master_fun_insert("wallet_master", $data1);
            }
        }
		$query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $user_fk), array("id", "desc"));
        $total1 = $query[0]['total'];
		$query = $this->service_model->master_fun_get_tbl_val("offer_master", array("status" => 1), array("id", "desc"));
            $caseback_per = $query[0]['caseback_per'];
		
		$caseback_amount = ($price * $caseback_per)/100;
		
		$data = array(
                "cust_fk" => $user_fk,
                "credit" => $caseback_amount,
                "total" => $total1 + $caseback_amount,
                "type"=> "Case Back",
				"job_fk"=>$jobid,
                "created_time" => date('Y-m-d H:i:s')
            );
			 $insert = $this->service_model->master_fun_insert("wallet_master", $data);

        $update = $this->service_model->master_fun_update("job_master", $jobid, array("status" => 1,"payment_type"=>"PayUMony"));

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
       
$message='<div style="background:#a3a3a3 !important;font-family: "Roboto",sans-serif;">
<script src="https://use.fontawesome.com/44049e3dc5.js"></script>
    <div style="background: #6b6b6b none repeat scroll 0 0;float: left;padding: 7%;">
        <div style="border:1px solid #f1f1f1;border-bottom:1px solid #67B1A3;padding:1.5%;width:100%;float:left;background:#fff;">
            <div style="float:left;width:64%;">
                        <img alt="" src="'.base_url().'user_assets/images/logo.png" style="height: 100px;width:213px;"/>
            </div>
                <div style="float:right;text-align: right;width:33%;padding-top:7px;">

                <p style=" width:100%;color: #111111;font-weight: 500;float:right;margin:0px 0px 5px 0px;padding:0;"><img src="'.base_url().'user_assets/images/message.png" style="height:10px;width:10px;"> <a href="mailto:info@airmedpathlabs.com" style="text-decoration:none;color:#7e7e7e;">info@airmedpathlabs.com</p>
                <p style=" width:100%;color: #111111;font-weight: 500;float:right;margin:0;padding:0;"><img src="'.base_url().'user_assets/images/fa-phone-2x.png" style="height:15px;width:15px;"> +917043215052 </p>
                
                </div>
                </div>
        <div style="padding:1.5%;width:100%;float:left;background-color:#fff;border:1px solid #f1f2f3;">
                <div style="padding:0 4%;">
                    <h4><b>Test Book Successfully</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Test has been book successfully. </p>
                    '.$email_use_wallet.'
					 <p style="color:#7e7e7e;font-size:13px;"> Rs. '.$amount.' Paid using PayUMoney </p>
        <p style="color:#7e7e7e;font-size:13px;">Your Current Wallet balance is Rs. '.$Current_wallet.'</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>
        </div>
        <div style="width:100%;float:left;background:#F5F5F5;padding:1.5%;color:#828282;font-size:12px;border:1px solid #dfdfdf;text-align:center;">
                <a href="'.base_url().'user_master/about_us" style="color:#515151;text-decoration:none;">About Us</a> |
                <a href="'.base_url().'user_master/contact_us" style="color:#515151;text-decoration:none;">Contact Us</a> |
                <a href="'.base_url().'user_login" style="color:#515151;text-decoration:none;">Login</a> |
                <a href="'.base_url().'user_master/collection" style="color:#515151;text-decoration:none;">Home-collection</a> |
                <a href="https://www.facebook.com/airmedpathlabs" style="color:#515151;text-decoration:none;"><i aria-hidden="true"><img src="'.base_url().'user_assets/images/face_book_icon1.jpeg" style="height:15px;width:15px;"></i></a>                  
        </div>
        <div style="width:100%;float:left;background:#333333;padding:1.5%;color:#ccc;font-size:12px;border:1px solid #323232;text-align:center;">
                Copyright @ 2016 Airmed PATH LAB. All rights reserved
        </div>
    </div>
</div>';
        $this->email->to($destail[0]['email']);
        $this->email->from('donotreply@airmed.com', 'Airmed PATH LAB');
        $this->email->subject('Test Book Successfully');
        $this->email->message($message);
        $this->email->send();
		
		$message='<div style="background:#a3a3a3 !important;font-family: "Roboto",sans-serif;">
<script src="https://use.fontawesome.com/44049e3dc5.js"></script>
    <div style="background: #6b6b6b none repeat scroll 0 0;float: left;padding: 7%;">
        <div style="border:1px solid #f1f1f1;border-bottom:1px solid #67B1A3;padding:1.5%;width:100%;float:left;background:#fff;">
            <div style="float:left;width:64%;">
                        <img alt="" src="'.base_url().'user_assets/images/logo.png" style="height: 100px;width:213px;"/>
            </div>
                <div style="float:right;text-align: right;width:33%;padding-top:7px;">

                <p style=" width:100%;color: #111111;font-weight: 500;float:right;margin:0px 0px 5px 0px;padding:0;"><img src="'.base_url().'user_assets/images/message.png" style="height:10px;width:10px;"> <a href="mailto:info@airmedpathlabs.com" style="text-decoration:none;color:#7e7e7e;">info@airmedpathlabs.com</p>
                <p style=" width:100%;color: #111111;font-weight: 500;float:right;margin:0;padding:0;"><img src="'.base_url().'user_assets/images/fa-phone-2x.png" style="height:15px;width:15px;"> +917043215052 </p>
                
                </div>
                </div>
        <div style="padding:1.5%;width:100%;float:left;background-color:#fff;border:1px solid #f1f2f3;">
                <div style="padding:0 4%;">
                    <h4 style="color:#67B1A3;text-decoration: underline;">Cashback Credited Successfully</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Cashback Credited in your Wallet. </p>
                       
<p style="color:#7e7e7e;font-size:13px;"> Rs. '.$caseback_amount.' Credited in your account. </p>
		<p style="color:#7e7e7e;font-size:13px;">Your Current Wallet Amount is Rs. '.$Current_wallet.'</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>
        </div>
        <div style="width:100%;float:left;background:#F5F5F5;padding:1.5%;color:#828282;font-size:12px;border:1px solid #dfdfdf;text-align:center;">
                <a href="'.base_url().'user_master/about_us" style="color:#515151;text-decoration:none;">About Us</a> |
                <a href="'.base_url().'user_master/contact_us" style="color:#515151;text-decoration:none;">Contact Us</a> |
                <a href="'.base_url().'user_login" style="color:#515151;text-decoration:none;">Login</a> |
                <a href="'.base_url().'user_master/collection" style="color:#515151;text-decoration:none;">Home-collection</a> |
                <a href="https://www.facebook.com/airmedpathlabs" style="color:#515151;text-decoration:none;"><i aria-hidden="true"><img src="'.base_url().'user_assets/images/face_book_icon1.jpeg" style="height:15px;width:15px;"></i></a>                  
        </div>
        <div style="width:100%;float:left;background:#333333;padding:1.5%;color:#ccc;font-size:12px;border:1px solid #323232;text-align:center;">
                Copyright @ 2016 Airmed PATH LABS. All rights reserved
        </div>
    </div>
</div>';
        $this->email->to($destail[0]['email']);
        $this->email->from('donotreply@airmed.com', 'Airmed PATH LAB');
        $this->email->subject('CashBack');
        $this->email->message($message);
        $this->email->send();
	
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
  $destail = $this->service_model->master_fun_get_tbl_val("customer_master", array("status" => 1,"id"=>$userid),array("id","asc"));
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
       
$message='<div style="background:#a3a3a3 !important;font-family: "Roboto",sans-serif;">
<script src="https://use.fontawesome.com/44049e3dc5.js"></script>
    <div style="background: #6b6b6b none repeat scroll 0 0;float: left;padding: 7%;">
        <div style="border:1px solid #f1f1f1;border-bottom:1px solid #67B1A3;padding:1.5%;width:100%;float:left;background:#fff;">
            <div style="float:left;width:64%;">
                        <img alt="" src="'.base_url().'user_assets/images/logo.png" style="height: 100px;width:213px;"/>
            </div>
                <div style="float:right;text-align: right;width:33%;padding-top:7px;">

                <p style=" width:100%;color: #111111;font-weight: 500;float:right;margin:0px 0px 5px 0px;padding:0;"><img src="'.base_url().'user_assets/images/message.png" style="height:10px;width:10px;"> <a href="mailto:info@airmedpathlabs.com" style="text-decoration:none;color:#7e7e7e;">info@airmedpathlabs.com</p>
                <p style=" width:100%;color: #111111;font-weight: 500;float:right;margin:0;padding:0;"><img src="'.base_url().'user_assets/images/fa-phone-2x.png" style="height:15px;width:15px;"> +917043215052 </p>
                
                </div>
                </div>
        <div style="padding:1.5%;width:100%;float:left;background-color:#fff;border:1px solid #f1f2f3;">
                <div style="padding:0 4%;">
                    <h4><b>Money Added to wallet</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;">RS.'.$amount.' successfully added to Wallet. Total Wallet Amount is RS.'.($total+$amount).' </p>
                        <p style="color:#7e7e7e;font-size:13px;">Your Transaction id is '.$payumonyid.'</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>
        </div>
        <div style="width:100%;float:left;background:#F5F5F5;padding:1.5%;color:#828282;font-size:12px;border:1px solid #dfdfdf;text-align:center;">
                <a href="'.base_url().'user_master/about_us" style="color:#515151;text-decoration:none;">About Us</a> |
                <a href="'.base_url().'user_master/contact_us" style="color:#515151;text-decoration:none;">Contact Us</a> |
                <a href="'.base_url().'user_login" style="color:#515151;text-decoration:none;">Login</a> |
                <a href="'.base_url().'user_master/collection" style="color:#515151;text-decoration:none;">Home-collection</a> |
                <a href="https://www.facebook.com/airmedpathlabs" style="color:#515151;text-decoration:none;"><i aria-hidden="true"><img src="'.base_url().'user_assets/images/face_book_icon1.jpeg" style="height:15px;width:15px;"></i></a>                  
        </div>
        <div style="width:100%;float:left;background:#333333;padding:1.5%;color:#ccc;font-size:12px;border:1px solid #323232;text-align:center;">
                Copyright @ 2016 Airmed PATH LAB. All rights reserved
        </div>
    </div>
</div>';
        $this->email->to($destail[0]['email']);
        $this->email->from('info@airmed.com', 'Airmed PATH LAB');
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
        $other_reference = $this->input->get_post('other_reference');
        $order_id = random_string('numeric', 13);
        $date = date('Y-m-d H:i:s');
        if ($user_fk != "" && $testfk != "") {
            $insert = $this->service_model->master_fun_insert("job_master", array("cust_fk" => $user_fk, "date" => $date, "status" => 5, "order_id" => $order_id, "doctor" => $doctor, "other_reference" => $other_reference,"payment_type"=>"PayUMoney"));
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
            $data1 = array("cust_fk" => $user_fk, "subject" => $subject, "massage" => $message1, "created_date" => date('d/m/Y'));
            $insert = $this->service_model->master_fun_insert("issue_master", $data1);
            $config['mailtype'] = 'html';

            $this->email->initialize($config);

            $message = '<body style="background:#f1f2f3;padding:2%;width:100%;">
    <div style="background:#fff;width:80%;margin-left:10%;border:1px solid #ccc">
        <div style="background: lightsteelblue none repeat scroll 0 0;border-bottom: 1px solid #ddd;padding: 20px;text-align: center;">
            <h1>LOGO HERE</h1>
        </div>
        <div style="padding:2%;color:#232552">
            <p>Hello ' . $message1 . ' </p>
            
        </div>
       <div style=" background:#f3f3dd;border-top: 1px solid #ccc;color: #7e7e7e;font-size: 12px;padding: 7px;text-align: center;">
            hello@pathology.com<br>
            
        </div>
    </div>
</body>';

            $this->email->to("nimesh@virtualheight.com");
            $this->email->from("donotreply@airmed.com", "Airmed PATH LAB");
            $this->email->subject($subject);
            $this->email->message($message);
            $this->email->send();
            echo $this->json_data("1", "", array(array("msg" => "Your Report Submitted Successfully")));
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
                echo $this->json_data("1", "", array(array("refer_code" => $refercode,"refer_price" => 100)));
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function add_health_line() {
        $uid = $this->input->get_post('user_id');
        $image = $this->input->get_post('image');
        //$desc = $this->input->get_post('desc');
        if ($uid != null && $image != null) {

            $data1 = array(
                "user_id" => $uid,
                "image" => $image,
                "created_date" => date('Y-m-d H:i:s'),
            );
            $insert = $this->service_model->master_fun_insert("health_line", $data1);
            if ($insert) {

                echo $this->json_data("1", "", array(array("msg" => "Image Uploaded successfully")));
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
                $dt["type"] = 'image';
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

            function cmp($a, $b) {
                $ad = strtotime($a['date']);
                $bd = strtotime($b['date']);
                return ($bd - $ad);
            }

            $new = array_merge($data, $data1);
            usort($new, 'cmp');
            $count = 0;
            $new_data = array();
            foreach ($new as $dt) {
                $dt["number"] = $count++;
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

            if (empty($new) || $new[0]=="") {

                echo $this->json_data("0", "no data available", "");
            } else {
                echo $this->json_data("1", "", $new);
            }
        } else {

            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function package_list() {
        $data = $this->service_model->package_list();
        if (empty($data)) {
            echo $this->json_data("0", "no data available", "");
        } else {
            echo $this->json_data("1", "", $data);
        }
    }

    function health_feed() {

        $data = $this->service_model->health_feed();
        //$new_data=array();
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

        $data3 = $this->service_model->master_fun_get_tbl_val("offer_master", array("status" => 1), array("id", "asc"));
        if (empty($data)) {
            echo $this->json_data("0", "no data available", "");
        } else {
            echo $this->json_data("1", "", array(array("data1" => $data3, "data2" => $data)));
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
        $order_id = random_string('numeric', 13);
        $date = date('Y-m-d H:i:s');
        $query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $user_fk), array("id", "desc"));
        $total = $query[0]['total'];
        if ($total >= $amount) {
            if ($user_fk != "" && $amount != "") {
				
				$insert1 = $this->service_model->master_fun_insert("job_master", array("cust_fk" => $user_fk,"price"=>$amount, "date" => $date, "order_id" => $order_id, "payment_type" => "wallet"));
				
                $insert = $this->service_model->master_fun_insert("book_package_master", array("cust_fk" => $user_fk, "package_fk" => $package, "date" => $date, "order_id" => $order_id,"job_fk"=>$insert1));

                if ($insert) {

                    $data1 = array(
                        "cust_fk" => $user_fk,
                        "debit" => $amount,
                        "total" => $total - $amount,
                        "package_fk" => $insert,
                        "created_time" => date('Y-m-d H:i:s')
                    );
                    $insertwallet = $this->service_model->master_fun_insert("wallet_master", $data1);
                   // Cashback code
					  $destail = $this->service_model->master_fun_get_tbl_val("customer_master", array("status" => 1, "id" => $user_fk), array("id", "asc"));
					$query = $this->service_model->master_fun_get_tbl_val("offer_master", array("status" => 1), array("id", "desc"));
            $caseback_per = $query[0]['caseback_per'];
			$caseback_amount = ($amount * $caseback_per)/100;
					
					$query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $user_fk), array("id", "desc"));
				  $total1 = $query[0]['total'];
				$data1 = array(
                    "cust_fk" => $user_fk,
                    "credit" => $caseback_amount,
                    "total" => $total1 + $caseback_amount,
                    "job_fk" => $insert1,
					"type"=> "Case Back",
                    "created_time" => date('Y-m-d H:i:s')
                );
				
				
                
				$insert = $this->service_model->master_fun_insert("wallet_master", $data1);
				
				$query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $user_fk), array("id", "desc"));
			$Current_wallet = $query[0]['total'];
					$config['mailtype'] = 'html';
        $this->email->initialize($config);
					$message='<div style="background:#a3a3a3 !important;font-family: "Roboto",sans-serif;">
<script src="https://use.fontawesome.com/44049e3dc5.js"></script>
    <div style="background: #6b6b6b none repeat scroll 0 0;float: left;padding: 7%;">
        <div style="border:1px solid #f1f1f1;border-bottom:1px solid #67B1A3;padding:1.5%;width:100%;float:left;background:#fff;">
            <div style="float:left;width:64%;">
                        <img alt="" src="'.base_url().'user_assets/images/logo.png" style="height: 100px;width:213px;"/>
            </div>
                <div style="float:right;text-align: right;width:33%;padding-top:7px;">

                <p style=" width:100%;color: #111111;font-weight: 500;float:right;margin:0px 0px 5px 0px;padding:0;"><img src="'.base_url().'user_assets/images/message.png" style="height:10px;width:10px;"> <a href="mailto:info@airmedpathlabs.com" style="text-decoration:none;color:#7e7e7e;">info@airmedpathlabs.com</p>
                <p style=" width:100%;color: #111111;font-weight: 500;float:right;margin:0;padding:0;"><img src="'.base_url().'user_assets/images/fa-phone-2x.png" style="height:15px;width:15px;"> +917043215052 </p>
                
                </div>
                </div>
        <div style="padding:1.5%;width:100%;float:left;background-color:#fff;border:1px solid #f1f2f3;">
                <div style="padding:0 4%;">
                    <h4 style="color:#67B1A3;text-decoration: underline;">Cashback Credited Successfully</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Cashback Credited in your Wallet. </p>
                       
<p style="color:#7e7e7e;font-size:13px;"> Rs. '.$caseback_amount.' Credited in your Wallet. </p>
		<p style="color:#7e7e7e;font-size:13px;">Your Current Wallet Amount is Rs. '.$Current_wallet.'</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>
        </div>
        <div style="width:100%;float:left;background:#F5F5F5;padding:1.5%;color:#828282;font-size:12px;border:1px solid #dfdfdf;text-align:center;">
                <a href="'.base_url().'user_master/about_us" style="color:#515151;text-decoration:none;">About Us</a> |
                <a href="'.base_url().'user_master/contact_us" style="color:#515151;text-decoration:none;">Contact Us</a> |
                <a href="'.base_url().'user_login" style="color:#515151;text-decoration:none;">Login</a> |
                <a href="'.base_url().'user_master/collection" style="color:#515151;text-decoration:none;">Home-collection</a> |
                <a href="https://www.facebook.com/airmedpathlabs" style="color:#515151;text-decoration:none;"><i aria-hidden="true"><img src="'.base_url().'user_assets/images/face_book_icon1.jpeg" style="height:15px;width:15px;"></i></a>                  
        </div>
        <div style="width:100%;float:left;background:#333333;padding:1.5%;color:#ccc;font-size:12px;border:1px solid #323232;text-align:center;">
                Copyright @ 2016 Airmed PATH LABS. All rights reserved
        </div>
    </div>
</div>';
        $this->email->to($destail[0]['email']);
        $this->email->from('donotreply@airmed.com', 'Airmed PATH LAB');
        $this->email->subject('CashBack');
        $this->email->message($message);
        $this->email->send();
				   
				   
				   // end cashback code
				   

				   echo $this->json_data("1", "", array(array("msg" => "Package Booked successfully")));
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
		$data3 = $this->service_model->master_fun_get_tbl_val("customer_master", array("status" => 1,'id'=>$user_fk), array("id", "asc"));
		$name = $data3[0]['full_name'];
		$gender = $data3[0]['gender'];
		$pic = $data3[0]['pic'];
        $data = $this->service_model->medical_id_view($user_fk);
        $data1 = array(
			"full_name"=>$name,
			"gender"=>$gender,
			"pic"=>$pic,
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

        $packageid = $this->uri->segment(4);
        $wallet_amt = $this->uri->segment(5);
        $method = $this->uri->segment(6);
        $userfk1 = $this->uri->segment(7);

        $order_id = random_string('numeric', 13);
        $date = date('Y-m-d H:i:s');

        $data = array("cust_fk" => $userfk1, "package_fk" => $packageid, "date" => $date, "order_id" => $order_id);

        //	print_r($data);
        //	die();
		$package_user = $this->service_model->master_fun_get_tbl_val("package_master", array("status" => 1,"id"=>$packageid),array("id","asc"));
		$price = $package_user[0]['d_price'];

		$insert1 = $this->service_model->master_fun_insert("job_master", array("cust_fk" => $userfk1,"price"=>$price, "date" => $date, "order_id" => $order_id, "payment_type" => "PayUMoney"));
		
        $insert = $this->service_model->master_fun_insert("book_package_master", array("cust_fk" => $userfk1, "package_fk" => $packageid, "date" => $date, "order_id" => $order_id,"job_fk"=>$insert1));

  $package_user = $this->service_model->master_fun_get_tbl_val("package_master", array("status" => 1,"id"=>$packageid),array("id","asc"));
        $query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $userfk1), array("id", "desc"));
  $destail = $this->service_model->master_fun_get_tbl_val("customer_master", array("status" => 1,"id"=>$userfk1),array("id","asc"));
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
			$caseback_amount = ($price * $caseback_per)/100;
					
					$query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $userfk1), array("id", "desc"));
				  $total1 = $query[0]['total'];
				$data1 = array(
                    "cust_fk" => $userfk1,
                    "credit" => $caseback_amount,
                    "total" => $total1 + $caseback_amount,
                    "job_fk" => $insert1,
					"type"=> "Case Back",
                    "created_time" => date('Y-m-d H:i:s')
                );
				
				$insert = $this->service_model->master_fun_insert("wallet_master", $data1);
				
				$query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $userfk1), array("id", "desc"));
			$Current_wallet = $query[0]['total'];
					$config['mailtype'] = 'html';
        $this->email->initialize($config);
					$message='<div style="background:#a3a3a3 !important;font-family: "Roboto",sans-serif;">
<script src="https://use.fontawesome.com/44049e3dc5.js"></script>
    <div style="background: #6b6b6b none repeat scroll 0 0;float: left;padding: 7%;">
        <div style="border:1px solid #f1f1f1;border-bottom:1px solid #67B1A3;padding:1.5%;width:100%;float:left;background:#fff;">
            <div style="float:left;width:64%;">
                        <img alt="" src="'.base_url().'user_assets/images/logo.png" style="height: 100px;width:213px;"/>
            </div>
                <div style="float:right;text-align: right;width:33%;padding-top:7px;">

                <p style=" width:100%;color: #111111;font-weight: 500;float:right;margin:0px 0px 5px 0px;padding:0;"><img src="'.base_url().'user_assets/images/message.png" style="height:10px;width:10px;"> <a href="mailto:info@airmedpathlabs.com" style="text-decoration:none;color:#7e7e7e;">info@airmedpathlabs.com</p>
                <p style=" width:100%;color: #111111;font-weight: 500;float:right;margin:0;padding:0;"><img src="'.base_url().'user_assets/images/fa-phone-2x.png" style="height:15px;width:15px;"> +917043215052 </p>
                
                </div>
                </div>
        <div style="padding:1.5%;width:100%;float:left;background-color:#fff;border:1px solid #f1f2f3;">
                <div style="padding:0 4%;">
                    <h4 style="color:#67B1A3;text-decoration: underline;">Cashback Credited Successfully</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Cashback Credited in your Wallet. </p>
                       
<p style="color:#7e7e7e;font-size:13px;"> Rs. '.$caseback_amount.' Credited in your account. </p>
		<p style="color:#7e7e7e;font-size:13px;">Your Current Wallet Amount is Rs. '.$Current_wallet.'</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>
        </div>
        <div style="width:100%;float:left;background:#F5F5F5;padding:1.5%;color:#828282;font-size:12px;border:1px solid #dfdfdf;text-align:center;">
                <a href="'.base_url().'user_master/about_us" style="color:#515151;text-decoration:none;">About Us</a> |
                <a href="'.base_url().'user_master/contact_us" style="color:#515151;text-decoration:none;">Contact Us</a> |
                <a href="'.base_url().'user_login" style="color:#515151;text-decoration:none;">Login</a> |
                <a href="'.base_url().'user_master/collection" style="color:#515151;text-decoration:none;">Home-collection</a> |
                <a href="https://www.facebook.com/airmedpathlabs" style="color:#515151;text-decoration:none;"><i aria-hidden="true"><img src="'.base_url().'user_assets/images/face_book_icon1.jpeg" style="height:15px;width:15px;"></i></a>                  
        </div>
        <div style="width:100%;float:left;background:#333333;padding:1.5%;color:#ccc;font-size:12px;border:1px solid #323232;text-align:center;">
                Copyright @ 2016 Airmed PATH LABS. All rights reserved
        </div>
    </div>
</div>';
        $this->email->to($destail[0]['email']);
        $this->email->from('donotreply@airmed.com', 'Airmed PATH LAB');
        $this->email->subject('CashBack');
        $this->email->message($message);
        $this->email->send();
				   
				   
				   // end cashback code
        //$update = $this->service_model->master_fun_update("job_master",$jobid,array("status" =>1));
		
		
		

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
       
$message='<div style="background:#a3a3a3 !important;font-family: "Roboto",sans-serif;">
<script src="https://use.fontawesome.com/44049e3dc5.js"></script>
    <div style="background: #6b6b6b none repeat scroll 0 0;float: left;padding: 7%;">
        <div style="border:1px solid #f1f1f1;border-bottom:1px solid #67B1A3;padding:1.5%;width:100%;float:left;background:#fff;">
            <div style="float:left;width:64%;">
                        <img alt="" src="'.base_url().'user_assets/images/logo.png" style="height: 100px;width:213px;"/>
            </div>
                <div style="float:right;text-align: right;width:33%;padding-top:7px;">

                <p style=" width:100%;color: #111111;font-weight: 500;float:right;margin:0px 0px 5px 0px;padding:0;"><img src="'.base_url().'user_assets/images/message.png" style="height:10px;width:10px;"> <a href="mailto:info@airmedpathlabs.com" style="text-decoration:none;color:#7e7e7e;">info@airmedpathlabs.com</p>
                <p style=" width:100%;color: #111111;font-weight: 500;float:right;margin:0;padding:0;"><img src="'.base_url().'user_assets/images/fa-phone-2x.png" style="height:15px;width:15px;"> +917043215052 </p>
                
                </div>
                </div>
        <div style="padding:1.5%;width:100%;float:left;background-color:#fff;border:1px solid #f1f2f3;">
                <div style="padding:0 4%;">
                    <h4><b>Package Added Successfully</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;">'.ucfirst($package_user[0]['title']).' Package Add successfully. </p>

                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>
        </div>
        <div style="width:100%;float:left;background:#F5F5F5;padding:1.5%;color:#828282;font-size:12px;border:1px solid #dfdfdf;text-align:center;">
                <a href="'.base_url().'user_master/about_us" style="color:#515151;text-decoration:none;">About Us</a> |
                <a href="'.base_url().'user_master/contact_us" style="color:#515151;text-decoration:none;">Contact Us</a> |
                <a href="'.base_url().'user_login" style="color:#515151;text-decoration:none;">Login</a> |
                <a href="'.base_url().'user_master/collection" style="color:#515151;text-decoration:none;">Home-collection</a> |
                <a href="https://www.facebook.com/airmedpathlabs" style="color:#515151;text-decoration:none;"><i aria-hidden="true"><img src="'.base_url().'user_assets/images/face_book_icon1.jpeg" style="height:15px;width:15px;"></i></a>                  
        </div>
        <div style="width:100%;float:left;background:#333333;padding:1.5%;color:#ccc;font-size:12px;border:1px solid #323232;text-align:center;">
                Copyright @ 2016 Airmed PATH LAB. All rights reserved
        </div>
    </div>
</div>';
        $this->email->to($destail[0]['email']);
        $this->email->from('donotreply@airmed.com', 'Airmed PATH LAB');
        $this->email->subject('Package Successfully Added');
        $this->email->message($message);
        $this->email->send();

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

        $order_id = random_string('numeric', 13);
        $date = date('Y-m-d H:i:s');
        if ($uid != NULL && $address != NULL && $city != Null && $state != Null && $select_tests != Null && $total != Null) {

            $data = array(
                "order_id" => $order_id,
                "cust_fk" => $uid,
                "date" => $date,
                "price" => $total,
                "status" => '1',
                "payment_type"=>"Cash On Delivery",
                "address" => $address,
                "city" => $city,
                "state" => $state,
                "pin"=>$pin,
                "doctor"=>$docid,
                "other_reference"=>$reference_by
            );

            $insert = $this->service_model->master_fun_insert("job_master", $data);
            if ($insert) {
				
				$query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $uid), array("id", "desc"));
        $wallettotal = $query[0]['total'];
        if ($wallettotal > 0) {
            if ($method == "yes") {

                $data1 = array(
                    "cust_fk" => $uid,
                    "debit" => $wallettotal,
                    "total" => $wallettotal - $wallettotal,
                    "job_fk" => $insert,
                    "created_time" => date('Y-m-d H:i:s')
                );
                $insert12 = $this->service_model->master_fun_insert("wallet_master", $data1);
				$update = $this->service_model->master_fun_update1("job_master", array("id" => $insert),array("payable_amount"=>$total-$wallettotal));
            }
        }
				
                $test = explode(",",$select_tests);
                foreach ($test as $key){
                $this->service_model->master_fun_insert("job_test_list_master", array("job_fk"=>$insert,"test_fk"=>$key));
                }
				
           
            $test_name_mail=array();
            for ($i = 0; $i < count($test); $i++) {
               // $insert_code = $this->service_model->master_fun_insert("job_test_list_master", array("job_fk" => $insert, "test_fk" => $test[$i]));
                $data = $this->service_model->master_fun_get_tbl_val("test_master", array("status" => 1, 'id' => $test[$i]), array("id", "asc"));
                $price = $price + $data[0]['price'];
                $test_name_mail[$i]=$data[0]['test_name'];
            }
				$destail = $this->service_model->master_fun_get_tbl_val("customer_master", array("status" => 1, "id" => $uid), array("id", "asc"));

        $config['mailtype'] = 'html';
        $this->email->initialize($config);
       
$message='<div style="background:#a3a3a3 !important;font-family: "Roboto",sans-serif;">
<script src="https://use.fontawesome.com/44049e3dc5.js"></script>
    <div style="background: #6b6b6b none repeat scroll 0 0;float: left;padding: 7%;">
        <div style="border:1px solid #f1f1f1;border-bottom:1px solid #67B1A3;padding:1.5%;width:100%;float:left;background:#fff;">
            <div style="float:left;width:64%;">
                        <img alt="" src="'.base_url().'user_assets/images/logo.png" style="height: 100px;width:213px;"/>
            </div>
                <div style="float:right;text-align: right;width:33%;padding-top:7px;">

                <p style=" width:100%;color: #111111;font-weight: 500;float:right;margin:0px 0px 5px 0px;padding:0;"><img src="'.base_url().'user_assets/images/message.png" style="height:10px;width:10px;"> <a href="mailto:info@airmedpathlabs.com" style="text-decoration:none;color:#7e7e7e;">info@airmedpathlabs.com</p>
                <p style=" width:100%;color: #111111;font-weight: 500;float:right;margin:0;padding:0;"><img src="'.base_url().'user_assets/images/fa-phone-2x.png" style="height:15px;width:15px;"> +917043215052 </p>
                
                </div>
                </div>
        <div style="padding:1.5%;width:100%;float:left;background-color:#fff;border:1px solid #f1f2f3;">
                <div style="padding:0 4%;">
                    <h4><b>Test Book Successfully</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Test has been book successfully. </p>
                        <p style="color:#7e7e7e;font-size:13px;">You booked '.implode($test_name_mail,',').'</p>
<p style="color:#7e7e7e;font-size:13px;"> Your Test Amount is Rs. '.$price.'  </p>
        <p style="color:#7e7e7e;font-size:13px;"> Payment Type : Case On Delivery</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>
        </div>
        <div style="width:100%;float:left;background:#F5F5F5;padding:1.5%;color:#828282;font-size:12px;border:1px solid #dfdfdf;text-align:center;">
                <a href="'.base_url().'user_master/about_us" style="color:#515151;text-decoration:none;">About Us</a> |
                <a href="'.base_url().'user_master/contact_us" style="color:#515151;text-decoration:none;">Contact Us</a> |
                <a href="'.base_url().'user_login" style="color:#515151;text-decoration:none;">Login</a> |
                <a href="'.base_url().'user_master/collection" style="color:#515151;text-decoration:none;">Home-collection</a> |
                <a href="https://www.facebook.com/airmedpathlabs" style="color:#515151;text-decoration:none;"><i aria-hidden="true"><img src="'.base_url().'user_assets/images/face_book_icon1.jpeg" style="height:15px;width:15px;"></i></a>                  
        </div>
        <div style="width:100%;float:left;background:#333333;padding:1.5%;color:#ccc;font-size:12px;border:1px solid #323232;text-align:center;">
                Copyright @ 2016 Airmed PATH LAB. All rights reserved
        </div>
    </div>
</div>';
        $this->email->to($destail[0]['email']);
        $this->email->from('donotreply@airmed.com', 'Airmed PATH LAB');
        $this->email->subject('Test Book Successfully');
        $this->email->message($message);
        $this->email->send();
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

        $order_id = random_string('numeric', 13);
        $date = date('Y-m-d H:i:s');
        if ($uid != NULL && $address != NULL && $city != Null && $state != Null && $select_package != Null && $total != Null ) {

            $data = array(
                "order_id" => $order_id,
                "cust_fk" => $uid,
                "date" => $date,
                "price" => $total,
                "status" => '1',
                "payment_type"=>"Cash On Delivery",
                "address" => $address,
                "city" => $city,
                "state" => $state,
                "pin"=>$pin,
                "doctor"=>$docid,
                "other_reference"=>$reference_by
            );

            $insert = $this->service_model->master_fun_insert("job_master", $data);
            if ($insert) {
				
				$insert1 = $this->service_model->master_fun_insert("job_master", array("cust_fk" => $uid,"price"=>$total, "date" => $date, "order_id" => $order_id, "payment_type" => "Cash On Delivery"));
				
                $test = explode(",",$select_package);
                foreach ($test as $key){
                $this->service_model->master_fun_insert("book_package_master", array("job_fk" => $insert,"date" => $date,"order_id" => $order_id, "package_fk" => $key,"cust_fk" => $uid,"type"=>"2","job_fk"=>$insert1));
                }
				
				
				
                echo $this->json_data("1", "", array(array("msg" => "Your request successfully send.")));
            }
        } else {
            echo $this->json_data("0", "All fields are required.", "");
        }
    }
	
	function delete_health_line(){
		$uid = $this->input->get_post('id');
		if($uid!=null){
			
		 $update = $this->service_model->master_fun_update1("health_line", array("id" => $uid), array('status'=>0));
                if ($update) {
                    echo $this->json_data("1", "", array(array("msg" => "Image deleted successfully")));
                }
		}else{
			
			 echo $this->json_data("0", "parameter not passed", "");
		}
		
	}
	
	
	function doctor_customer_job(){
		$user_fk = $this->input->get_post('user_id');
		if($user_fk!=null){
			$data = $this->service_model->doctor_customer_job($user_fk);
			 if (empty($data)) {
                echo $this->json_data("0", "no data available", "");
            } else {
			
			 echo $this->json_data("1", "", $data);
			}
		
		}else{
			echo $this->json_data("0", "Parameter not passed", "");
		}
		
	}
	function last_7_days_doctor_customer_job(){
		$user_fk = $this->input->get_post('user_id');
		if($user_fk!=null){
			$data = $this->service_model->last_7_doctor_customer_job($user_fk);
			 if (empty($data)) {
                echo $this->json_data("0", "no data available", "");
            } else {
			
			 echo $this->json_data("1", "", $data);
			}
		
		}else{
			echo $this->json_data("0", "Parameter not passed", "");
		}
		
	}
	
	function today_doctor_customer_job(){
		$user_fk = $this->input->get_post('user_id');
		if($user_fk!=null){
			$data = $this->service_model->today_doctor_customer_job($user_fk);
			 if (empty($data)) {
                echo $this->json_data("0", "no data available", "");
            } else {
			
			 echo $this->json_data("1", "", $data);
			}
		
		}else{
			echo $this->json_data("0", "Parameter not passed", "");
		}	
	}
	
	function doctor_stat(){
		$user_fk = $this->input->get_post('user_id');
		$month = $this->input->get_post('month');
		$year = $this->input->get_post('year');
		if($user_fk!=null){
			$data = $this->service_model->doctor_stat($user_fk,$month,$year);
			 if (empty($data)) {
                echo $this->json_data("0", "no data available", "");
            } else {
			 echo $this->json_data("1", "", $data);
			}
		
		}else{
			echo $this->json_data("0", "Parameter not passed", "");
		}	
	}
	
	function doctor_dashboard(){
		$user_fk = $this->input->get_post('user_id');
		if($user_fk!=null){
			$all = $this->service_model->doctor_customer_job($user_fk);
			$countall = count($all);
			$last_7days = $this->service_model->last_7_doctor_customer_job($user_fk);
			$countlast7 = count($last_7days);
			$todays = $this->service_model->today_doctor_customer_job($user_fk);
			$counttoday = count($todays);
			
			 echo $this->json_data("1", "",array("total"=>$countall,"last7days"=>$countlast7,"today"=>$counttoday));
			
		
		}else{
			echo $this->json_data("0", "Parameter not passed", "");
		}	
	}
	
		function doctor_view_report(){
		 $jid = $this->input->get_post('job_id');
			 if($jid!=null){
				  $data = $this->service_model->doctor_view_report($jid);
				 if (empty($data)) {
						echo $this->json_data("0", "no data available", "");
						} else {
							echo $this->json_data("1", "", $data);
						}
					 }else{
					  echo $this->json_data("0", "Parameter not passed", "");
				 }
		}
}

?>
