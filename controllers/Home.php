<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('home_model');
        $this->load->model('user_model');
        $data["login_data"] = logindata();
        $this->app_track();
    }

    function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }

    function index() {
        if (!is_loggedin()) {
            redirect('login');
        }
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data["login_data"] = logindata();
        //$data["ongoing"] = $this->home_model->getongoing();
        //$data["complete"] = $this->home_model->getcomplete();
        //$data["totalsubcategory"] = $this->home_model->getsubcategory();
        $cust = $this->user_model->master_fun_get_tbl_val("customer_master", array('status' => 1), array("id", "asc"));
        $data['totalcustomer'] = count($cust);
        $test = $this->user_model->master_fun_get_tbl_val("test_master", array('status' => 1), array("id", "asc"));
        $data['totaltest'] = count($test);
        $pending = $this->user_model->master_fun_get_tbl_val("job_master", array('status' => 1), array("id", "asc"));
        $data['pending'] = count($pending);
        $complete = $this->user_model->master_fun_get_tbl_val("job_master", array('status' => 2), array("id", "asc"));
        $data['complete'] = count($complete);
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('home_view', $data);
        $this->load->view('footer');
    }

}

?>
