<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Phlebo_schedule extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('email');
        $data["login_data"] = logindata();
        $this->load->helper('string');
    }

    function index() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('phlebo', 'Phlebo', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $cal = $this->input->post("cal");
            $phlebo = $this->input->post("phlebo");
            $center = $this->input->post("center");
            $this->master_model->master_fun_update_new("phlebo_schedule", array("status", "1"), array("status" => "0"));
            $this->master_model->master_fun_update_new("phlebo_assign_center", array("phlebo_fk", $phlebo), array("status" => "0","deleted_by"=>$data["login_data"]["id"]));
            if ($center == '') {
                foreach ($cal as $value) {
                    $this->master_model->master_fun_insert("phlebo_schedule", array("phlebo_fk" => $phlebo, "time_slot_fk" => $value, "createddate" => date("Y-m-d H:i:s")));
                }
            } else {
                $this->master_model->master_fun_insert("phlebo_assign_center", array("phlebo_fk" => $phlebo, "branch_fk" => $center,"created_by"=>$data["login_data"]["id"], "created_date" => date("Y-m-d H:i:s")));
            }
            $this->session->set_flashdata("success", array("Data successfully updated."));
            redirect("Phlebo_schedule/index?phlebo=" . $phlebo, "refresh");
        } else {
            $data["selected_phlebo"] = $this->input->get_post("phlebo");
            if ($data["selected_phlebo"] != '') {
                $data["phlebo_schedule"] = $this->master_model->master_fun_get_tbl_val("phlebo_schedule", array("phlebo_fk" => $data["selected_phlebo"], "status" => "1"), array("id", "asc"));
            }
            $data["phlebo"] = $this->master_model->master_fun_get_tbl_val("phlebo_master", array("status" => "1"), array("id", "asc"));
            $data["time_slot"] = $this->master_model->master_fun_get_tbl_val("phlebo_time_slot", array("status" => "1"), array("start_time", "asc"));
            $data["branch"] = $this->master_model->master_fun_get_tbl_val("branch_master", array("status" => "1"), array("branch_name", "asc"));
            $data["phlebo_assign_branch"] = $this->master_model->master_fun_get_tbl_val("phlebo_assign_center", array("phlebo_fk" => $data["selected_phlebo"],"status"=>"1"), array("id", "desc"));
            $data['success'] = $this->session->flashdata("success");
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view("phlebo_schedule", $data);
            $this->load->view('footer');
        }
    }

}
