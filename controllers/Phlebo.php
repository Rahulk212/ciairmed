<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Phlebo extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('email');
        $data["login_data"] = logindata();
        $this->load->helper('string');
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
        $this->load->library('form_validation');
        $this->form_validation->set_rules('cal[]', 'Slot', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $this->master_model->master_fun_update_new("phlebo_calender", array("status", "1"), array("status" => "0", "updated_by" => $data["login_data"]["id"]));
            $cal = $this->input->post("cal");
            foreach ($cal as $value) {
                $val = explode("-", $value);
                $this->master_model->master_fun_insert("phlebo_calender", array("day_fk" => $val[0], "time_slot_fk" => $val[1], "created_by" => $data["login_data"]["id"], "createddate" => date("Y-m-d H:i:s")));
            }
            $this->session->set_flashdata("success", array("Data successfully updated."));
            redirect("Phlebo", "refresh");
        } else {
            $data["week_day"] = $this->master_model->master_fun_get_tbl_val("phlebo_day_master", array("status" => "1"), array("id", "asc"));
            $data["time_slot"] = $this->master_model->master_fun_get_tbl_val("phlebo_time_slot", array("status" => "1"), array("start_time", "asc"));
            $data["calendar_slot"] = $this->master_model->master_fun_get_tbl_val("phlebo_calender", array("status" => "1"), array("id", "asc"));
            $data['success'] = $this->session->flashdata("success");
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view("phlebo_manage");
            $this->load->view('footer');
        }
    }

}
