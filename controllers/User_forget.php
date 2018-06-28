<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User_forget extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper(array('form', 'url'));
        $this->load->model('master_model');
        $this->load->library('form_validation');
        $this->app_track();
    }
	function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }

    function index_old() {
        $this->load->helper("Email");
        $email_cnt = new Email;

        $this->load->library('email');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $captch = $this->varify_captcha();
        if ($this->form_validation->run() == FALSE && $captch != 1) {
            //die("0");
            $data = '';
            if ($this->session->userdata('getmsg1') != null && $captch != 1) {
                $data['getmsg1'] = $this->session->userdata("getmsg1");
                $this->session->unset_userdata('getmsg1');
            }$this->load->view('user/header', $data);
            $this->load->view('user/forget_password', $data);
            $this->load->view('user/footer');
        } else {
            die("1");
            $email = $this->input->post('email');
            $check_email = $this->master_model->master_num_rows("customer_master", array("email" => $email, 'status' => '1'));
            if ($check_email == '0') {
                $this->session->set_userdata('getmsg1', array("Invalid email Please Check It!"));
                redirect('user_forget', 'refresh');
            }
            $this->load->helper('string');
            $rs = random_string('alnum', 5);
            $data = array(
                'rs' => $rs
            );
            $this->db->where('email', $email);
            $this->db->where('status', '1');
            // $this->db->where('type', '1');   
            $this->db->update('customer_master', $data);
            $config['mailtype'] = 'html';
            $this->email->initialize($config);
            $message = '<div style="padding:0 4%;">
                    <h4>Forgot Password</h4>
                        <p style="color:#7e7e7e;font-size:13px;">You recently requested to reset your password for your  AirmedLabs Account. Click the button below to reset it. </p>
			<a href="' . base_url() . 'user_get_password/index/' . $rs . '" style="background: #D01130;color: #f9f9f9;padding: 1%;text-decoration: none;">Reset</a>
                        <p style="color:#7e7e7e;font-size:13px;">If you did not request a password reset, please ignore this email or reply to let us know.</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
            $message = $email_cnt->get_design($message);
            $this->email->to($email);
            $this->email->from('donotreply@airmed.com', 'AirmedLabs');
            $this->email->subject('AirmedLabs Reset your forgotten Password');
            $this->email->message($message);
            $this->email->send();
            $this->session->set_flashdata('success', array("We have Sent you a link to change your password Please Check It!"));
            if ($captch == 1) {
                redirect('user_login', 'refresh');
            } else {
                $this->session->set_userdata('captcha2', "invalid captcha.please enter valid captcha!");
                redirect('user_forget', 'refresh');
            }
        }
    }
    function index() {
        $this->load->helper("Email");
        $email_cnt = new Email;

        $this->load->library('email');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'required|trim');
        $captch = $this->varify_captcha();
        if ($this->form_validation->run() == FALSE||$captch!=1) {
            $data = '';
            if ($this->session->userdata('getmsg1') != null) {
                $data['getmsg1'] = $this->session->userdata("getmsg1");
                $this->session->unset_userdata('getmsg1');
            }$this->load->view('user/header', $data);
            $this->load->view('user/forget_password', $data);
            $this->load->view('user/footer');
        } else {
            $email = $this->input->post('email');
            $check_email = $this->master_model->master_num_rows("customer_master", array("email" => $email, 'status' => '1'));
            if ($check_email == '0') {
                $this->session->set_userdata('getmsg1', array("Invalid email Please Check It!"));
                redirect('user_forget', 'refresh');
            }
            $this->load->helper('string');
            $rs = random_string('alnum', 5);
            $data = array(
                'rs' => $rs
            );
            $this->db->where('email', $email);
            $this->db->where('status', '1');
            // $this->db->where('type', '1');   
            $this->db->update('customer_master', $data);
            $config['mailtype'] = 'html';
            $this->email->initialize($config);
            $message = '<div style="padding:0 4%;">
                    <h4>Forgot Password</h4>
                        <p style="color:#7e7e7e;font-size:13px;">You recently requested to reset your password for your  AirmedLabs Account. Click the button below to reset it. </p>
			<a href="' . base_url() . 'user_get_password/index/' . $rs . '" style="background: #D01130;color: #f9f9f9;padding: 1%;text-decoration: none;">Reset</a>
                        <p style="color:#7e7e7e;font-size:13px;">If you did not request a password reset, please ignore this email or reply to let us know.</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
            $message = $email_cnt->get_design($message);
            $this->email->to($email);
            $this->email->from('donotreply@airmed.com', 'AirmedLabs');
            $this->email->subject('AirmedLabs Reset your forgotten Password');
            $this->email->message($message);
            $this->email->send();
            $this->session->set_flashdata('success', array("We have Sent you a link to change your password Please Check It!"));
            redirect('user_login');
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

}

?>
