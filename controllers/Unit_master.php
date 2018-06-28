<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Unit_master extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('user_model');
        $this->load->model('unit_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('pushserver');
        $this->load->library('email');
        $this->load->helper('string');
        $data["login_data"] = logindata();
        $this->app_track();
    }

    function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }

    function index() {
        $cfname = $this->input->get('cfname');
        $data['cfname'] = $cfname;
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        if ($cfname != "") {
            $totalRows = $this->unit_model->num_row($cfname);
            $config = array();
            $get = $_GET;
            unset($get['offset']);
            $config["base_url"] = base_url() . "unit_master/index?" . http_build_query($get);
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 5;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->unit_model->master_get_search($cfname, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;
        } else {
            $totalRows = $this->unit_model->num_row($cfname);
            $config = array();
            $get = $_GET;
            $config["base_url"] = base_url() . "unit_master/index";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 10;
            $config['page_query_string'] = TRUE;
            $config["uri_segment"] = 3;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->unit_model->master_get_search($cfname, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;
        }
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('unit_list', $data);
        $this->load->view('footer');
    }

    public function unit_delete() {
        $cid = $this->uri->segment('3');
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $post['status'] = '0';
        $data['query'] = $this->unit_model->master_get_spam('CTMS_PARAMETER_MST', $cid, $post);
        $this->session->set_flashdata('success', 'Unit Successfully Deleted');
        redirect('unit_master');
    }

    function unit_add() {
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $this->load->library('form_validation');
        $this->form_validation->set_rules('pname', ' PARAMETER NAME', 'trim|required');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        if ($this->form_validation->run() != FALSE) {
            $post['PARAMETER_NAME'] = $this->input->post('pname');
            $post['PARAMETER_CODE'] = "UOM";
            $post['status'] = '1';
            $data['query'] = $this->unit_model->master_get_insert("CTMS_PARAMETER_MST", $post);
            $this->session->set_flashdata('success', "Unit Successfully Added");
            redirect("unit_master");
        } else {
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('unit_add', $data);
            $this->load->view('footer');
        }
    }

    function unit_edit() {
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["cid"] = $this->uri->segment('3');
        $ccid = $this->uri->segment('3');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('pname', 'Parameter Name', 'trim|required');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        if ($this->form_validation->run() == TRUE) {
            $post['PARAMETER_NAME'] = $this->input->post('pname');
            $post['PARAMETER_CODE'] = "UOM";
            $post['status'] = '1';
            $data['view_data'] = $this->unit_model->master_get_update("CTMS_PARAMETER_MST", $ccid, $post);
            $this->session->set_flashdata("success", "Unit Successfully Updated.");
            redirect("unit_master");
        } else {
            $data['view_data'] = $this->unit_model->master_get_table('CTMS_PARAMETER_MST', array('id' => $ccid), array('id', 'DESC'));
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('unit_edit', $data, FALSE);
            $this->load->view('footer');
        }
    }

}

?>
