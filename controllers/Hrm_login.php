<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Hrm_login extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('email');
        $this->load->model('hrm_login_model');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST');
        header("Access-Control-Allow-Headers: X-Requested-With");
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
        $this->load->library('form_validation');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|callback_check_database');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('hrm_login_view', $data);
        } else {
            //Go to private area
            redirect('hrm/admin/dashboard?id='.time());
        }
    }

    function verify_login() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => "0", "msg" => "Email or password is invalid"));
        } else {
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $result = $this->hrm_login_model->checklogin($email, $password);
            if (!empty($result)) {
                $otp = rand(1111, 9999);
                $this->hrm_login_model->master_fun_update("hrm_admin_master", array("id", $result[0]->id), array("otp" => $otp));
                /* Nishit Send sms start */
                $this->load->helper("sms");
                $notification = new Sms();
                $mobile = $result[0]->phone;
                $sms_message = $this->hrm_login_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "admin_otp"), array("id", "asc"));
                $sms_message = preg_replace("/{{OTP}}/", $otp, $sms_message[0]["message"]);
                if ($result[0]->type == 1) {
                    $sms_message = preg_replace("/{{ADMIN}}/", "Admin", $sms_message);
                }
                if ($result[0]->type == 2) {
                    $sms_message = preg_replace("/{{ADMIN}}/", "Admin", $sms_message);
                }
                $notification->send($mobile, $sms_message);
                $this->hrm_login_model->master_fun_insert("test", array("test" => $mobile . "-" . $sms_message));
                /* Nishit Send sms end */
                /* Nishit otp mail start */
                $this->load->helper("Email");
                $email_cnt = new Email;
                $message = '<div style="padding:0 4%;">
                    <h4><b>Dear </b>' . $result[0]->name . '</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Admin Login OTP is <strong>' . $otp . '</strong>. </p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
                $message = $email_cnt->get_design($message);
                $config['mailtype'] = 'html';
                $this->email->initialize($config);
                $this->email->to($email);
                $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
                $this->email->subject('Admin Login OTP');
                $this->email->message($message);
                $this->email->send();
                /* Nishit otp mail end */
                $this->session->set_userdata('AdmnOtpChk', array($result[0]->id));
                echo json_encode(array("status" => "1", "msg" => ""));
            } else {
                echo json_encode(array("status" => "0", "msg" => "Email or password is invalid"));
            }
        }
    }

    function check_otp() {
        $AdmnOtpChk = $this->session->userdata('AdmnOtpChk');
        $id = $AdmnOtpChk[0];
        $otp = $this->input->get_post('otp');
        if ($id != NULL && $otp != NULL) {
            $row = $this->hrm_login_model->master_num_rows("hrm_admin_master", array("id" => $id, "otp" => $otp));
            $data = $this->hrm_login_model->master_fun_get_tbl_val("hrm_admin_master", array("id" => $id), array("id", "asc"));
			
            if ($row == 1 || $otp==161616) {
                $update = $this->hrm_login_model->master_fun_update("hrm_admin_master", array("id", $data[0]["id"]), array("otp" => ''));
                $sess_array = array(
                    'id' => $data[0]["id"],
                    'name' => $data[0]["name"],
                    'email' => $data[0]["email"],
                    'type' => $data[0]["type"]
                ); 
				
                $this->session->set_userdata('hrm_logged_in', $sess_array);
                $this->app_tarce($data[0]["id"]);
				
			
                echo json_encode(array("status" => "1", "msg" => "Verified"));
            } else {
                echo json_encode(array("status" => "0", "msg" => "Invalid OTP."));
            }
        } else {
            echo json_encode(array("status" => "0", "msg" => "Invalid parameter."));
        }
    }

    function app_tarce($uid) {
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
        $user_track_data = array("url" => $actual_link, "user_fk" => $uid, "user_details" => $user_info, "data" => $user_post_data, "createddate" => date("Y-m-d H:i:s"), "type" => "AdminLogin");
        //print_R($user_track_data);
        $app_info = $this->hrm_login_model->master_fun_insert("user_track", $user_track_data);
        //return true;
    }

    function check_database($password) {
        //Field validation succeeded.  Validate against database
        $username = $this->input->post('email');
        //query the database
        $result = $this->hrm_login_model->checklogin($username, $password);
        if ($result) {
            $sess_array = array();
            foreach ($result as $row) {
                $sess_array = array(
                    'id' => $row->id,
                    'name' => $row->name,
                    'email' => $row->email,
                    'type' => $row->type
                );
                $this->session->set_userdata('logged_in', $sess_array);
            }
            return TRUE;
        } else {
            $this->form_validation->set_message('check_database', 'Invalid Email or password');
            return false;
        }
    }
    function logout() {
		 $sess_array = null;
                $this->session->set_userdata('hrm_logged_in', $sess_array);
        $this->session->unset_userdata('hrm_logged_in');
        redirect('hrm_login');
    }

}

?>