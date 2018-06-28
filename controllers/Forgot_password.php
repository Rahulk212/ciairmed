<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Forgot_password extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('register_model');
        $this->load->model('user_model');
        $this->load->library('form_validation');
        $menu = $this->user_model->getmenu();
        $final = array();
        foreach ($menu as $menus) {
            //echo $menus['id'];
            $submenu = $this->user_model->getsubmenu($menus['id']);
            $cnt = count($submenu);
            array_push($final, array($menus, $submenu));
            if ($cnt == '0') {
                //array_push($final,$menus);
            } else {
                //array_push($final,$menus,$submenu);
            }
        }
        $this->data['final'] = $final;
        $this->app_track();
    }

    function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }

    function index() {

        $data["dynamic"] = $this->user_model->headfoot();
        $this->form_validation->set_rules('phone', 'Phone Number', 'trim|required|min_length[10]|max_length[12]|numeric|callback_check_phoneforgot');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('user/header', $data);
            $this->load->view('user/forgot_password', $data);
            $this->load->view('user/footer', $data);
        } else {
            $otp = mt_rand(111111, 999999);
            $phone = $this->input->post('phone');
            $data1 = array(
                "password" => $otp
            );
            $update = $this->register_model->forgot_password($data1, $phone);
            if ($update) {
                $context = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));
                $message = "$otp is your new password# on Powersforinvestments.";
                // $message = str_replace(" ", "%20", $message);

                $message = urlencode($message);
                //$url= "http://api-server3.com/api/send.aspx?username=powerfi4&password=power4inv33w&language=1&sender=Taqat&mobile=$phone&message=".$message;
                //  die();
                //$xml = file_get_contents($url, false, $context);


                $this->session->set_userdata('success', array("Your password successfully changed,We send you code!"));
                redirect("user_master");
            }
        }
    }

    function check_phoneforgot($phone) {
        $result = $this->register_model->master_num_rows("bmh_registration", array("phone" => $phone, "status" => '1'));
        if ($result == 1) {
            return TRUE;
        } else {

            $this->form_validation->set_message('check_phoneforgot', 'Phone Number is not exist!');
            return false;
        }
    }

}

?>