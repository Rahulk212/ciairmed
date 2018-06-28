<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->helper('string');
        if (!is_hrmlogin()) {
            redirect('hrm_login');
        }
    }

    function dashboard() {
        if (!is_hrmlogin()) {
            redirect('Hrm_login');
        }
        //die("OK");
        $data["login_data"] = logindata();
        $cust = $this->master_model->master_fun_get_tbl_val("hrm_department", array('status' => 1), array("id", "asc"));
        $data['total_department'] = count($cust);
        $test = $this->master_model->master_fun_get_tbl_val("hrm_employees", array('status' => 1), array("id", "asc"));
        $data['total_employee'] = count($test);
        
        $this->load->view('hrm/header', $data);
        $this->load->view('hrm/nav', $data);
        $this->load->view('hrm/dashboard', $data);
        $this->load->view('hrm/footer');
    }

}
