<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Register extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('register_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('email');
        $this->load->helper('string');
        /* pinkesh code start */
        $this->load->model('user_master_model');
        $data['links'] = $this->user_master_model->master_fun_get_tbl_val("patholab_home_master", array("status" => 1), array("id", "asc"));
        $this->data['all_links'] = $data['links'];
        /* pinkesh code start */
        $this->app_track();
    }

    function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }

    function checkmobile($mobile) {
        $result = $this->user_master_model->checkemail($mobile);
        if ($result >= 1) {
            $this->form_validation->set_message('checkmobile', 'Mobile Number Already Exists!');
            return false;
        } else {
            return TRUE;
        }
    }

    function checkmobilebyadmin() {
        $mobile = $this->input->post('mobile');
        $result = $this->user_master_model->checkemail($mobile);
        if ($result >= 1) {
            echo "0";
        } else {
            echo "1";
        }
    }

    function checkemailbyadmin() {
        $mobile = $this->input->post('email');
        $result = $this->user_master_model->checkemail1($mobile);
        if ($result >= 1) {
            echo "0";
        } else {
            echo "1";
        }
    }

    function index() {
        $this->load->helper("Email");
        $email_cnt = new Email;
        $data["test_city"] = $this->register_model->master_fun_get_tbl_val("test_cities", array("status" => "1"), array("name", "asc"));
        $data["success"] = $this->session->flashdata('success');
        $data["unsuccess"] = $this->session->flashdata('unsuccess');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('email', 'email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'password', 'trim|required|matches[cpassword]|min_length[6]');
        $this->form_validation->set_rules('mobile', 'mobile', 'trim|required|numeric|min_length[10]|max_length[13]|callback_checkmobile');
        $this->form_validation->set_rules('gender', 'gender', 'trim|required');
        $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required');
        $this->form_validation->set_rules('test_city', 'Test city', 'trim|required');
        $this->form_validation->set_rules('birth_date', 'Date of birth', 'trim|required');
        $this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'required|trim');
        //echo "<pre>";print_R($_POST); die();
        //echo validation_errors(); die();
        $captcha = $this->varify_captcha();
        $captcha = 1;
        if ($this->form_validation->run() != FALSE && $captcha == 1) {
            //die("1");
            $email = $this->input->post('email');
            $name = $this->input->post('name');
            $password = $this->input->post('password');
            $mobile = $this->input->post('mobile');
            $gender = $this->input->post('gender');
            $usedcode = $this->input->post('refer_code');
            $birth_date = $this->input->post('birth_date');
            $code = random_string('alnum', 6);
            $confirm_code = random_string('alnum', 6);
            $OTP = rand(11111, 99999);
            $count = $this->user_master_model->num_row('customer_master', array("email" => $email, "status" => '1'));
            if ($count != 0) {
                $this->session->set_flashdata("unsuccess", array("Email Address Already Exists!"));
                $this->load->view('user/header');
                $this->load->view('user/register', $data);
                $this->load->view('user/footer');
            } else {
                $insert = $this->register_model->master_fun_insert("customer_master", array("full_name" => $name, "gender" => $gender, "email" => $email, "password" => $password, "active" => 0, "status" => 0, "confirm_code" => $confirm_code, "otp" => $OTP, "mobile" => $mobile, "dob" => $birth_date,"created_date"=>date("Y-m-d H:i:s")));
                $this->register_model->master_fun_insert("user_change_phone", array("user_fk" => $insert, "mobile" => $mobile));
                $insert_code = $this->register_model->master_fun_insert("refer_code_master", array("cust_fk" => $insert, "refer_code" => $code, "used_code" => $usedcode));
                // code for refer price
                $data = $this->register_model->master_fun_get_tbl_val("refer_code_master", array("refer_code" => $usedcode), array("id", "asc"));
                if (!empty($data)) {
                    $custfk = $data[0]['cust_fk'];
                    $data1 = $this->register_model->master_fun_get_tbl_val("customer_master", array("id" => $insert), array("id", "asc"));
                    $refemail = $data1[0]['email'];
                    $ref_name = $data1[0]['full_name'];
                    // $query = $this->register_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $custfk), array("id", "desc"));
                    //$total = $query[0]['total'];
                    $data = array(
                        "cust_fk" => $insert,
                        "credit" => 100,
                        "total" => 100,
                        "type" => "referral code",
                        "created_time" => date('Y-m-d H:i:s')
                    );
                    $insert1 = $this->register_model->master_fun_insert("wallet_master", $data);
                    $config['mailtype'] = 'html';
                    $this->email->initialize($config);
                    $message = '<div style="padding:0 4%;">
                    <h4><b>You have one more refferal</b></h4>
                        <p>Dear, ' . ucfirst($ref_name) . '</p>
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
                $config['mailtype'] = 'html';
                $this->email->initialize($config);
                /* $message='<body style="background:#f1f2f3;padding:2%;width:100%;">
                  <div style="background:#fff;width:80%;margin-left:10%;border:1px solid #ccc">
                  <div style="background: lightsteelblue none repeat scroll 0 0;border-bottom: 1px solid #ddd;padding: 20px;text-align: center;">
                  <h1>LOGO HERE</h1>
                  </div>
                  <div style="padding:2%;color:#232552">
                  <p>Hello ,'.$name .' </p>
                  <p> Click Here To confirm Your Email </p>
                  <a href="'.base_url().'register/confirm_email/'.$confirm_code.'"> Click Here </a>
                  </div>
                  <div style=" background:#f3f3dd;border-top: 1px solid #ccc;color: #7e7e7e;font-size: 12px;padding: 7px;text-align: center;">
                  If you did not request a for Register, please ignore this email or reply to let us know.<br/><br/>
                  Thanks <br/> Patholab
                  hello@pathology.com<br>
                  </div>
                  </div>
                  </body>'; */
                $message = '<div style="padding:0 4%;">
                    <h4><b>Confirm Your Register</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;font-weight: bold;">Dear ' . $name . ',</p>
                        
                        <p style="color:#7e7e7e;font-size:13px;">Please confirm Your Email to get all services provided by Airmed PATH LAB</p>
								<a href="' . base_url() . 'register/confirm_email/' . $confirm_code . '" style="background: rgb(103, 177, 163) none repeat scroll 0 0;color: #f9f9f9;padding: 1%;text-decoration: none;">Confirm</a>
                </div>';
                $message = $email_cnt->get_design($message);
                $this->email->to($email);
//$this->email->to("jeel@virtualheight.com");
                $this->email->from("donotreply@airmedpathlabs.com", 'AirmedLabs');
                $this->email->subject("Please Confirm Your Register for AirmedLabs");
                $this->email->message($message);
                //$this->email->send();
                /* Nishit code start */
                /* Nishit send sms code start */
                $sms_message = $this->user_master_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "OTP"), array("id", "asc"));
                $sms_message = preg_replace("/{{NAME}}/", ucfirst($name), $sms_message[0]["message"]);
                $sms_message = preg_replace("/{{OTP}}/", $OTP, $sms_message);
                $sms_message = preg_replace("/{{PRICE}}/", "", $sms_message);
                $this->load->helper("sms");
                $notification = new Sms();
                $mb_length = strlen($mobile);
                if ($mb_length == 10) {
                    $notification->send($mobile, $sms_message);
                }
                if ($mb_length == 11 || $mb_length == 12 || $mb_length == 13) {
                    $check_phone = substr($mobile, 0, 2);
                    $check_phone1 = substr($mobile, 0, 1);
                    $check_phone2 = substr($mobile, 0, 3);
                    if ($check_phone2 == '+91') {
                        $get_phone = substr($mobile, 3);
                        $notification::send($get_phone, $sms_message);
                    }
                    if ($check_phone == '91') {
                        $get_phone = substr($mobile, 2);
                        $notification::send($get_phone, $sms_message);
                    }
                    if ($check_phone1 == '0') {
                        $get_phone = substr($mobile, 1);
                        $notification::send($get_phone, $sms_message);
                    }
                }
                /* Nishit send sms code end */
                /* Nishit code end */
                $this->session->set_flashdata("success", array("Registration Successfully.Please Check Your email for confirm Your Account"));
                //redirect("user_login", "refresh");
                redirect("Register/varify_phone/" . $insert, "refresh");
            }
        } else {

            $data["unsuccess"] = $this->session->flashdata('unsuccess');
            $data['success'] = $this->session->flashdata("success");
            $data['refcode'] = $this->uri->segment(3);
            /* Nishit capcha start */
            $this->load->helper('captcha');
            // numeric random number for captcha
            $random_number = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
            // setting up captcha config
            $vals = array(
                'word' => $random_number,
                'img_path' => './captcha/',
                'img_url' => base_url() . 'captcha/',
                'img_width' => 140,
                'img_height' => 32,
                'expiration' => 7200
            );
            $data['captcha'] = create_captcha($vals);
            $this->session->set_userdata('captchaWord', $data['captcha']['word']);
            $this->load->view('user/header');
            $this->load->view('user/register', $data);
            $this->load->view('user/footer');
        }
    }

    function varify_captcha() {
        $recaptchaResponse = trim($this->input->get_post('g-recaptcha-response'));
        $google_url = "https://www.google.com/recaptcha/api/siteverify";
        $secret = '6Ld5_x8UAAAAAGn_AV4406lg29xu2hpQQJMaD2BC';
        $ip = $_SERVER['REMOTE_ADDR'];
        $url = $google_url . "?secret=" . $secret . "&response=" . $recaptchaResponse . "&remoteip=" . $ip;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
        $res = curl_exec($curl);
        curl_close($curl);
        $res = json_decode($res, true);
        //var_dump($res);
        if ($res['success'] == true) {
            return 1;
        } else {
            return 0;
        }
    }

    public function check_captcha($str) {
        $word = $this->session->userdata('captchaWord');
        if (strcmp(strtoupper($str), strtoupper($word)) == 0) {
            return true;
        } else {
            $this->form_validation->set_message('check_captcha', 'Please enter correct captcha!');
            return false;
        }
    }

    function login($id) {
        $result = $this->register_model->master_fun_get_tbl_val("customer_master", array("status" => "1", "id" => $id), array("id", "asc"));
        if ($result[0]["mobile"] == '') {
            redirect("Register/varify_phone/" . $id);
        }
        foreach ($result as $row) {
            $sess_array = array(
                'id' => $row["id"],
                'name' => $row["full_name"],
                'type' => $row["type"],
            );
            $this->session->set_userdata('logged_in_user', $sess_array);
            if ($this->session->userdata('search_test_id') != null) {
                redirect("user_master/order_search");
            } else {
                redirect('user_master', 'refresh');
            }
        }
    }

    function varify_phone($id = null) {
        $data["user_info"] = $this->register_model->master_fun_get_tbl_val("customer_master", array("status" => "1", "id" => $id), array("id", "asc"));
        if (empty($data["user_info"]) && $id == null) {
            $this->session->set_flashdata("unsuccess", array("Oops somthing is wromg. Please try again."));
            redirect("register/index");
        }
        $data["user_mb"] = $this->register_model->master_fun_get_tbl_val("user_change_phone", array("user_fk" => $id), array("id", "desc"));
        $data["id"] = $id;
        $data["msg"] = "We'll send you OTP, Please verify here. It's take a few minutes.";
        $this->load->view('user/header');
        $this->load->view('user/varify_phone', $data);
        $this->load->view('user/footer');
    }

    function varify_phone1($id = null) {
        $data["user_info"] = $this->register_model->master_fun_get_tbl_val("customer_master", array("status" => "1", "id" => $id), array("id", "asc"));
        if (empty($data["user_info"]) && $id == null) {
            $this->session->set_flashdata("unsuccess", array("Oops somthing is wromg. Please try again."));
            redirect("register/index");
        }
        $data["user_mb"] = $this->register_model->master_fun_get_tbl_val("user_change_phone", array("user_fk" => $id), array("id", "desc"));
        $data["id"] = $id;
        $data["msg"] = "We'll send you OTP, Please verify here.It's take a few minutes.";
        $this->load->view('user/header');
        $this->load->view('user/varify_phone1', $data);
        $this->load->view('user/footer');
    }

    function confirm_email($code) {
        $this->load->helper("Email");
        $email_cnt = new Email;
        $result = $this->register_model->master_fun_get_tbl_val("customer_master", array("status" => 1, "confirm_code" => $code), array("id", "desc"));
        //$id = $query[0]['id'];
        $update = $this->register_model->master_fun_update1("customer_master", array("confirm_code" => $code), array("confirm_code" => "", "active" => 1));

        if ($update) {

            $sess_array = array();
            foreach ($result as $row) {
                $sess_array = array(
                    'id' => $row['id'],
                    'name' => $row['full_name'],
                    'type' => $row['type'],
                );
                $insert = $this->register_model->master_fun_insert("notification_master", array("title" => "Welcome To Airmed PATH LAB. Thank you For Register.", "url" => "user_master", "user_fk" => $row['id'], "status" => '1'));
                $config['mailtype'] = 'html';

                $this->email->initialize($config);
                $message = '<div style="padding:0 4%;">
                    <h4><b>Welcome To Airmed PATH LAB</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;font-weight: bold;">Welcome, ' . ucfirst($row['full_name']) . '</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank you, Your Register Mail Confirm Successfully. </p>
                        <p style="color:#7e7e7e;font-size:13px;"> With the focus on providing quality care to our patients, best in class diagnostic support to our doctors, we are a team of dedicated pathologists supported by a highly professional team of business and clinical experts.</p>

 <p style="color:#7e7e7e;font-size:13px;">We strive to attain highest levels of service quality to strongly influence patient satisfaction.</p>
 <p style="color:#7e7e7e;font-size:14px;"><b> Thank You</b></p>
                </div>';
                $message = $email_cnt->get_design($message);
                $this->email->to($row['email']);
//$this->email->to("jeel@virtualheight.com");
                $this->email->from("donotreply@airmedpathlabs.com", 'AirmedLabs');
                $this->email->subject("Welcome To AirmedLabs");
                $this->email->message($message);
                $this->email->send();
                $this->session->set_userdata('logged_in_user', $sess_array);
            }
            //$this->session->set_flashdata("success", array(""));
            redirect("user_master", "refresh");
        }
    }

    function invite() {
        $this->load->view('user/header');

        $this->load->view('user/invite_view');
        $this->load->view('user/footer');
    }

}
