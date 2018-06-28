<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Relation_Master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('Relation_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $data["login_data"] = logindata();
        $this->app_track();
    }

    function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }

    public function relation_list() {
        $relid = $this->input->get('relation');
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        if ($relid != "") {
            $totalRows = $this->Relation_model->num_row('relation_master', $relid);
            $config = array();
            $get = $_GET;
            unset($get['offset']);
            $config["base_url"] = base_url() . "Relation_master/relation_list?" . http_build_query($get);
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 1;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->Relation_model->manage_condition_view($relid, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;
            $cnt = 0;
        } else {
            $totalRows = $this->Relation_model->num_rows('relation_master');
            $config = array();
            $get = $_GET;
            $config["base_url"] = base_url() . "Relation_master/relation_list/";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 10;
            $config["uri_segment"] = 3;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next <span class="icon-text">&#59230;</span>';
            $config['prev_link'] = '<span class="icon-text">&#59229;</span> Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $data['query'] = $this->Relation_model->manage_view_list($config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $cnt = 0;
        }
        $data["page"] = $page;
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('relation_list', $data);
        $this->load->view('footer');
    }

    public function relation_add() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Relation', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        if ($this->form_validation->run() != FALSE) {
            $post['name'] = $this->input->post('name');
            $post['status'] = 1;
            $post['created_by'] = $data['user']->id;
            $post['created_date'] = date('d-m-y g:i:s');
            $post['updated_by'] = $data['user']->id;
            $data['query'] = $this->Relation_model->master_get_insert("relation_master", $post);
            $this->session->set_flashdata("success", array("Relation Successfull Added."));
            redirect("Relation_master/relation_list", "refresh");
        } else {
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('relation_add', $data);
            $this->load->view('footer');
        }
    }

    public function relation_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->Relation_model->master_get_update("relation_master", array("id" => $cid), array("status" => "0"), array("id", "desc"));
        $this->session->set_flashdata("success", array("Relation Successfull Deleted."));
        redirect("Relation_master/relation_list", "refresh");
    }

    public function relation_edit() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["cid"] = $this->uri->segment('3');
        $ids = $data["cid"];
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Relation', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        if ($this->form_validation->run() != FALSE) {
            $post['name'] = $this->input->post('name');
            $post['status'] = 1;
            $data['query'] = $this->Relation_model->master_get_update("relation_master", array('id' => $_POST['id']), $post);
            $cnt = 0;
            $this->session->set_flashdata("success", array("Relation Successfull Updated."));
            redirect("Relation_master/relation_list", "refresh");
        } else {
            $data['query'] = $this->Relation_model->master_get_tbl_val("relation_master", array("id" => $data["cid"]), array("id", "desc"));
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('relation_edit', $data);
            $this->load->view('footer');
        }
    }

}

?>