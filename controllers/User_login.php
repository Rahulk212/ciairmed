<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User_login extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('user_login_model');
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

    function index() {
        $data = '';
        if ($this->session->userdata('getmsg') != null) {
            $data['getmsg'] = $this->session->userdata("getmsg");
            $this->session->unset_userdata('getmsg');
        }
        if ($this->session->userdata('getmsg1') != null) {
            $data['getmsg1'] = $this->session->userdata("getmsg1");
            $this->session->unset_userdata('getmsg1');
        }
        $data["success"] = $this->session->flashdata('success');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|callback_check_database');
        $this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'required|trim');
        $captcha = $this->varify_captcha();
        if ($this->form_validation->run() == FALSE || $captcha != 1) {
            //die("0");
            $data["error1"] = $this->session->flashdata('notlogin');
            $data["error"] = $this->session->flashdata('error');
            $data["success"] = $this->session->flashdata('success');
            /* Nishit capcha end */
		
            $this->load->view('user/header', $data);
            $this->load->view('user/login', $data);
            $this->load->view('user/footer', $data);
        } else {
            //die("1");
            if ($captcha == 1) {
                //Go to private area
				 
                redirect('user_master', 'refresh');
            } else {
				$this->session->unset_userdata('logged_in_user');
                $this->session->set_userdata('captcha2', "invalid captcha.please enter valid captcha!");
               
				redirect('user_login', 'refresh');
            }
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
    function verify_login() {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|callback_check_database');
        $captch = $this->user_master_model->varify_captcha();

        if ($this->form_validation->run() == FALSE && $captch != 1) {
            //Field validation failed.  User redirected to login page
            $sess_array = array();
            $ses = "User Email Or Password Wrong";
            $this->session->set_flashdata('notlogin', $ses);
            $data["error1"] = $this->session->flashdata('notlogin');
            $data["error"] = $this->session->flashdata('success');
            $data["success"] = $this->session->flashdata('error');
            $this->load->view('user/header');
            $this->load->view('user/login');
            $this->load->view('user/footer');
            // die();
            // redirect('login', 'refresh');
        } else {
            if ($captch == 1) {
                //Go to private area
                redirect('user_master', 'refresh');
            } else {
                redirect('user_login', 'refresh');
            }
        }
    }

    function check_database($password) {
        // $this->load->library('session');
        //Field validation succeeded.  Validate against database
        $username = $this->input->post('email');

        //query the database
        $result = $this->user_login_model->checklogin($username, $password);
        //print_R($result) ;DIE();
        if ($result) {

            $sess_array = array();
            foreach ($result as $row) {
                if ($row->mobile == '') {
                    redirect("Register/varify_phone1/" . $row->id);
                }
                $sess_array = array(
                    'id' => $row->id,
                    'name' => $row->full_name,
                    'type' => $row->type,
                );
                $this->session->set_userdata('logged_in_user', $sess_array);
            }
            return TRUE;
        } else {

            $this->form_validation->set_message('check_database', 'Invalid Email or password');
            return false;
        }
    }

    function fb_login() {
        $this->load->library('session');
        $this->load->library('facebook'); // Automatically picks appId and secret from config
        $action = $this->uri->segment(3);
        $access_token = $this->uri->segment(4);

        switch ($action) {
            case "fblogin":
                $appid = "156557021428302";
                $appsecret = "6edb4964e5edfd7ae01d052804610a3b";
                //$appid = "655569194623281";
                //$appsecret = "182956ad5685b6dd4ed970ba79ab963c";
                $facebook = new Facebook(array(
                    'appId' => $appid,
                    'secret' => $appsecret,
                    'cookie' => TRUE,
                ));
                $fbuser = $facebook->getUser();
                $OTP = rand(11111, 99999);
                if ($fbuser) {
                    //echo $access_token = $facebook->getAccessToken();
                    echo $facebook->setAccessToken($access_token);
                    try {
                        $user_profile = $facebook->api('/me?fields=id,email,name,first_name,last_name,picture.height(400)', $_GET);
                        //$this->facebook->api('/me?fields=id,birthday,email,name,first_name,picture.height(400),last_name,gender,about,age_range,cover,currency,devices,install_type,installed,test_group,third_party_id,timezone,updated_time,verified,video_upload_limits,viewer_can_send_gift,work');
                    } catch (Exception $e) {
                        echo $e->getMessage();
                        redirect("/");
                        exit();
                    }
                    if ($user_profile["email"] == NULL) {
                        redirect('user_login');
                    }
                    $chk = $this->user_login_model->master_num_rows("customer_master", array("status" => "1", "email" => $user_profile["email"]));
                    if ($chk >= '1') {
                        $val = $this->user_login_model->master_fun_get_tbl_val("customer_master", array("status" => '1', "email" => $user_profile["email"]), array("id", "desc"));
                        $data1 = array(
                            'email' => $user_profile['email'],
                            'full_name' => $user_profile['first_name'] . ' ' . $user_profile['last_name'],
                            "pic" => $user_profile['picture']['data']['url'],
                            "fbid" => $user_profile['id'],
                            'type' => 2);
                        if ($val[0]["mobile"] == '') {
                            //$this->user_login_model->master_fun_update("user_change_phone", array("user_fk"=>$val[0]["id"]));
                            redirect("Register/varify_phone1/" . $val[0]["id"]);
                        }
                        $update = $this->user_login_model->master_fun_update("customer_master", $val[0]["id"], $data1);
                        $sess_array = array(
                            'id' => $val[0]["id"],
                            'type' => 2,
                            'email' => $val[0]["email"],
                            'name' => $val[0]['full_name'],
                        );
                        $this->session->set_userdata('logged_in_user', $sess_array);
                        $session_data = $this->session->userdata('logged_in_user');
                    } else {

                        $data1 = array(
                            'email' => $user_profile['email'],
                            'full_name' => $user_profile['first_name'] . ' ' . $user_profile['last_name'],
                            "pic" => $user_profile['picture']['data']['url'],
                            "fbid" => $user_profile['id'],
                            "active" => 0,
                            "status" => 0,
                            "created_date"=>date("Y-m-d H:i:s"),
                            'type' => 2);
                        $insert = $this->user_login_model->master_fun_insert('customer_master', $data1);
                        redirect("Register/varify_phone1/" . $insert);
                        if ($insert) {
                            $sess_array = array(
                                'id' => $insert,
                                'type' => 2,
                                'name' => $user_profile['first_name'] . ' ' . $user_profile["last_name"]
                            );
                            $this->session->set_userdata('logged_in_user', $sess_array);
                            $session_data = $this->session->userdata('logged_in_user');
                        }
                    }
                    redirect('user_master', "refresh");
                } else {
                    redirect('user_master', "refresh");
                }
            //    break;
        }
    }

    function google_login() {

        $name = $this->input->post('name');
        $id = $this->input->post('id');
        $email = $this->input->post('email');
        $image = $this->input->post('image');

        $chk = $this->user_login_model->master_num_rows("customer_master", array("status" => "1", "email" => $email));
        if ($chk >= '1') {
            $val = $this->user_login_model->master_fun_get_tbl_val("customer_master", array("status" => '1', "email" => $email), array("id", "desc"));
            // echo "<pre>"; print_r($val); die();
            $data1 = array(
                'email' => $email,
                'full_name' => $name,
                "pic" => $image,
                "fbid" => $id,
                'type' => 2);
            $update = $this->user_login_model->master_fun_update("customer_master", $val[0]["id"], $data1);
            if ($val[0]["mobile"] == '') {
                echo '{"status":"0","id":"' . $val[0]["id"] . '"}';
                $this->db->close();
                die();
            } else {
                echo '{"status":"1"}';
            }
            $sess_array = array(
                'id' => $val[0]["id"],
                'type' => 2,
                'email' => $val[0]["email"],
                'name' => $val[0]['full_name'],
            );
//print_r($sess_array); die();
            $this->session->set_userdata('logged_in_user', $sess_array);
            $session_data = $this->session->userdata('logged_in_user');
            //print_r($session_data); die();
            //redirect('user_master', "refresh");
        } else {

            $data1 = array(
                'email' => $email,
                'full_name' => $name,
                "pic" => $image,
                "fbid" => $id,
                "active" => 0,
                "status" => 0,
                "created_date"=>date("Y-m-d H:i:s"),
                'type' => 2);
            $insert = $this->user_login_model->master_fun_insert('customer_master', $data1);
            if ($insert) {
                $sess_array = array(
                    'id' => $insert,
                    'type' => 2,
                    'email' => $email,
                    'name' => $name
                );
            }
            echo '{"status":"0","id":"' . $insert . '"}';
            $this->db->close();
            die();
        }
    }

    function logout() {
        $this->session->unset_userdata('logged_in_user');
        redirect('user_login');
    }

}

?>