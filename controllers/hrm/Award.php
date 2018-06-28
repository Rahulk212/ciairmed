<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Award extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('user_model');
        $this->load->model('hrm/award_model');
        $this->load->helper('string');
        //echo current_url(); die();

        $data["login_data"] = is_hrmlogin();
    }

    function index() {
        if (!is_hrmlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_hrmlogin();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $this->load->library('pagination');
        $search = $this->input->get('search');
        $data['search'] = $search;
        if ($search != "") {
            $total_row = $this->award_model->search_num($search);
            $config = array();
            $config["base_url"] = base_url() . "hrm/award/index?" . http_build_query($get);
            $config["total_rows"] = $total_row;
            $config["per_page"] = 10;
            $config["uri_segment"] = 3;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $config['page_query_string'] = TRUE;
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->award_model->list_search($search, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        } else {
            $search = "";
            $totalRows = $this->award_model->search_num($search);
            $config = array();
            $config["base_url"] = base_url() . "hrm/award/index/";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 10;
            $config["uri_segment"] = 3;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data["query"] = $this->award_model->list_search($search, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        }
        $this->load->view('hrm/header');
        $this->load->view('hrm/nav', $data);
        $this->load->view('hrm/award_list', $data);
        $this->load->view('hrm/footer');
    }
    function add() {
        if (!is_hrmlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_hrmlogin();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $data['employee_list'] = $this->award_model->get_all('hrm_employees', array("status" => 1));
        //$data['month_list'] = array("January","February","March","April","May","June","July","August","September","October","November","December");
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('gift', 'Giftf Item', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $name = $this->input->post('name');
            $gift = $this->input->post('gift');
            $cash = $this->input->post('cash');
            $employee_id = $this->input->post('employee_id');
            $month = $this->input->post('month');
            $year = $this->input->post('year');
            $data1 = array(
                "name" => $name,
                "gift_item" => $gift,
                "cash_price" => $cash,
                "employee_id" => $employee_id,
                "for_the_month" => $month,
                "for_the_year" => $year,
                "created_by" => $data["login_data"]["id"],
                "created_date" => date("Y-m-d H:i:s")
            );
            $insert = $this->award_model->insert("hrm_award", $data1);
            $this->session->set_flashdata("success", "Award successfully added.");
            redirect("hrm/award", "refresh");
        } else {
            $this->load->view('hrm/header', $data);
            $this->load->view('hrm/nav', $data);
            $this->load->view('hrm/award_add', $data);
            $this->load->view('hrm/footer', $data);
        }
    }

    function delete($cid) {
        if (!is_hrmlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_hrmlogin();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['query'] = $this->award_model->update("hrm_award", array("id" => $cid), array("status" => '0'));
        $this->session->set_flashdata("success", "Award successfully deleted.");
        redirect("hrm/award", "refresh");
    }

    function edit($cid) {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = is_hrmlogin();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['cid'] = $cid;
        $data['query'] = $this->award_model->get_one("hrm_award", array("id" => $cid,"status" => 1));
        $data['employee_list'] = $this->award_model->get_all('hrm_employees', array("status" => 1));
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('gift', 'Giftf Item', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $name = $this->input->post('name');
            $gift = $this->input->post('gift');
            $cash = $this->input->post('cash');
            $employee_id = $this->input->post('employee_id');
            $month = $this->input->post('month');
            $year = $this->input->post('year');
            $data1 = array(
                "name" => $name,
                "gift_item" => $gift,
                "cash_price" => $cash,
                "employee_id" => $employee_id,
                "for_the_month" => $month,
                "for_the_year" => $year,
                "updated_by" => $data["login_data"]["id"],
                "updated_date" => date("Y-m-d H:i:s")
            );
            $update = $this->award_model->update("hrm_award", array("id" => $cid), $data1);
            $this->session->set_flashdata("success", "Award successfully updated.");
            redirect("hrm/award", "refresh");
        } else {
            $this->load->view('hrm/header', $data);
            $this->load->view('hrm/nav', $data);
            $this->load->view('hrm/award_edit', $data);
            $this->load->view('hrm/footer', $data);
        }
    }

}

?>