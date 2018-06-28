<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Issue_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('user_model');
        $this->load->model('issue_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        //echo current_url(); die();

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
        $data["login_data"] = logindata();
        //print_r($data["login_data"]); die();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $data['success'] = $this->session->flashdata("success");

        $data['customer'] = $this->issue_model->master_fun_get_tbl_val("customer_master", array('status' => 1), array("id", "asc"));
        $cust_fk = $this->input->get('user');
        $data['customerfk'] = $cust_fk;
        $data['query'] = $this->issue_model->issue_list($cust_fk);
        //$this->load->view('admin/state_list_view', $data);
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('issue_list', $data);
        $this->load->view('footer');
    }

}

?>
