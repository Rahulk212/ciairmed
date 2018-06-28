<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Holiday extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('user_model');
        $this->load->model('hrm/holiday_model');
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
            $total_row = $this->holiday_model->search_num($search);
            $config = array();
            $config["base_url"] = base_url() . "hrm/holiday/index?" . http_build_query($get);
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
            $data['query'] = $this->holiday_model->list_search($search, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        } else {
            $search = "";
            $totalRows = $this->holiday_model->search_num($search);
            $config = array();
            $config["base_url"] = base_url() . "hrm/holiday/index/";
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
            $data["query"] = $this->holiday_model->list_search($search, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        }
        $data['designation_list'] = $this->holiday_model->get_all('hrm_designation', array("status" => 1));
        $this->load->view('header');
        $this->load->view('hrm/nav', $data);
        $this->load->view('hrm/holiday_list', $data);
        $this->load->view('hrm/footer');
    }
    function add() {
        if (!is_hrmlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_hrmlogin();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $this->load->view('hrm/header', $data);
        $this->load->view('hrm/nav', $data);
        $this->load->view('hrm/holiday_add', $data);
        $this->load->view('hrm/footer', $data);
    }
    function add_all() {
        if (!is_hrmlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_hrmlogin();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $count_holiday = $this->input->post('count_holiday');
        for ($j = 1; $j <= $count_holiday; $j++) {
            $date = $this->input->post('date_' . $j);
            $occasion = $this->input->post('occasion_' . $j);
            if ($occasion != "") {
                $data1 = array(
                    "date" => $date,
                    "occasion" => $occasion,
                    "created_by" => $data["login_data"]["id"],
                    "created_date" => date("Y-m-d H:i:s")
                );
                $value = $this->holiday_model->insert("hrm_holiday", $data1);
            }
        }
        $this->session->set_flashdata("success", "Holiday successfully added.");
        redirect("hrm/holiday", "refresh");
    }

    function delete($cid) {
        if (!is_hrmlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_hrmlogin();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['query'] = $this->holiday_model->update("hrm_holiday", array("id" => $cid), array("status" => '0'));
        $this->session->set_flashdata("success", "Holiday successfully deleted.");
        redirect("hrm/holiday", "refresh");
    }
    
}

?>
