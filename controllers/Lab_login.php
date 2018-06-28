<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Lab_login extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('email');
        $this->load->model('login_model');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST');
        header("Access-Control-Allow-Headers: X-Requested-With");
        $this->load->library('session');
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
        $this->load->library('form_validation');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|callback_check_database');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('lab_login_view', $data);
        } else {
            //echo "OK"; die();
            //Go to private area
            redirect('Lab/sample_list', 'refresh');
        }
    }

    function check_database($password) {
        //Field validation succeeded.  Validate against database
        $username = $this->input->post('email');
        //query the database
        $result = $this->login_model->lab_checklogin($username, $password);
        if ($result) {
            $sess_array = array();
            foreach ($result as $row) {
                $sess_array = array(
                    'id' => $row->id,
                    'name' => $row->name,
                    'email' => $row->email,
                    'type' => $row->type
                );
                $this->session->set_userdata('lab_logged_in', $sess_array);
            }
            return TRUE;
        } else {
            $this->form_validation->set_message('check_database', 'Invalid Email or password');
            return false;
        }
    }

    function logout() {
        $this->session->unset_userdata('lab_logged_in');
        redirect('Lab_login');
    }

}

?>