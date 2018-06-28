<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Notice_board extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('user_model');
        $this->load->model('hrm/noticeboard_model');
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
            $total_row = $this->noticeboard_model->search_num($search);
            $config = array();
            $config["base_url"] = base_url() . "hrm/notice_board/index?" . http_build_query($get);
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
            $data['query'] = $this->noticeboard_model->list_search($search, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        } else {
            $search = "";
            $totalRows = $this->noticeboard_model->search_num($search);
            $config = array();
            $config["base_url"] = base_url() . "hrm/notice_board/index/";
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
            $data["query"] = $this->noticeboard_model->list_search($search, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        }
        $this->load->view('hrm/header');
        $this->load->view('hrm/nav', $data);
        $this->load->view('hrm/noticeboard_list', $data);
        $this->load->view('hrm/footer');
    }
    function add() {
        if (!is_hrmlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_hrmlogin();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $this->load->library('form_validation');
        $this->form_validation->set_rules('title', 'Title', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $title = $this->input->post('title');
            $description = $this->input->post('description');
            $data1 = array(
                "title" => $title,
                "description" => $description,
                "type" => "notice",
                "created_by" => $data["login_data"]["id"],
                "created_date" => date("Y-m-d H:i:s")
            );
            $insert = $this->noticeboard_model->insert("hrm_notice_board", $data1);
            $this->session->set_flashdata("success", "Notice successfully added.");
            redirect("hrm/notice_board", "refresh");
        } else {
            $this->load->view('hrm/header', $data);
            $this->load->view('hrm/nav', $data);
            $this->load->view('hrm/noticeboard_add', $data);
            $this->load->view('hrm/footer', $data);
        }
    }

    function delete($cid) {
        if (!is_hrmlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_hrmlogin();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['query'] = $this->noticeboard_model->update("hrm_notice_board", array("id" => $cid), array("status" => '0'));
        $this->session->set_flashdata("success", "Notice successfully deleted.");
        redirect("hrm/notice_board", "refresh");
    }

    function edit($cid) {
        if (!is_hrmlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_hrmlogin();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['cid'] = $cid;
        $data['query'] = $this->noticeboard_model->get_one("hrm_notice_board", array("id" => $cid,"status" => 1));
        $this->load->library('form_validation');
        $this->form_validation->set_rules('title', 'Title', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $title = $this->input->post('title');
            $description = $this->input->post('description');
            $data1 = array(
                "title" => $title,
                "description" => $description,
                "updated_by" => $data["login_data"]["id"],
                "updated_date" => date("Y-m-d H:i:s")
            );
            $update = $this->noticeboard_model->update("hrm_notice_board", array("id" => $cid), $data1);
            $this->session->set_flashdata("success", "Notice successfully updated.");
            redirect("hrm/notice_board", "refresh");
        } else {
            $this->load->view('hrm/header', $data);
            $this->load->view('hrm/nav', $data);
            $this->load->view('hrm/noticeboard_edit', $data);
            $this->load->view('hrm/footer', $data);
        }
    }
    function action($cid) {
        if (!is_hrmlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_hrmlogin();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $details = $this->noticeboard_model->get_one("hrm_notice_board", array("id" => $cid,"status" => 1));
        if($details->active == 1) {
            $data['query'] = $this->noticeboard_model->update("hrm_notice_board", array("id" => $cid), array("active" => '0'));
            $this->session->set_flashdata("success", "Notice successfully inactivated.");
            redirect("hrm/notice_board", "refresh");
        } else {
            $data['query'] = $this->noticeboard_model->update("hrm_notice_board", array("id" => $cid), array("active" => '1'));
            $this->session->set_flashdata("success", "Notice successfully activated.");
            redirect("hrm/notice_board", "refresh");
        }
    }

}

?>