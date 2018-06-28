<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class doctor_req extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('user_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->helper('string');
        $data["login_data"] = logindata();
                $this->app_track();
    }
	function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }
    function request_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");

        if ($user != "" || $date != "" || $city != "" || $status != "" || $mobile != "" || $t_id != "" || $p_id != "" || $p_amount != "") {
            /*            $total_row = $this->master_model->num_row_srch_job_list($user, $date, $city, $status, $mobile, $t_id, $p_id, $p_amount);
              $config = array();
              $get = $_GET;
              unset($get['offset']);
              $config["base_url"] = base_url() . "add_result/sample_collected_list?" . http_build_query($get);
              $config["total_rows"] = $total_row;
              $config["per_page"] = 10;
              $config['page_query_string'] = TRUE;
              $config['cur_tag_open'] = '<span>';
              $config['cur_tag_close'] = '</span>';
              $config['next_link'] = 'Next &rsaquo;';
              $config['prev_link'] = '&lsaquo; Previous';
              $this->pagination->initialize($config);
              $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
              $data['query'] = $this->add_result_model->row_srch_job_list($user, $date, $city, $status, $mobile, $t_id, $p_id, $p_amount, $config["per_page"], $page);
              $data["links"] = $this->pagination->create_links();
              $data["pages"] = $page; */
        } else {
            $total_row = $this->master_model->master_fun_get_tbl_val("doctor_req_discount", array('status' => 1), array("id", "desc"));
            $config["base_url"] = base_url() . "doctor_req/request_list";
            $config["total_rows"] = count($total_row);
            $config["per_page"] = 10;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config); 
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->master_model->srch_doctor_list($config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;
        }
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('doctor_req', $data);
        $this->load->view('footer');
    }

    function delete($id) {
        $data["login_data"] = logindata();
        $data1 = array('status' => 0);
        $update = $this->master_model->master_fun_update('doctor_req_discount', $id, $data1);
        $this->session->set_flashdata("success",array("Record successfully deleted."));
        if ($update) {
            redirect("doctor_req/request_list");
        }
    }

}
