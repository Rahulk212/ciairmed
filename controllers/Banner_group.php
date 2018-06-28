<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Banner_group extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('user_model');
        $this->load->model('test_model');
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

    function group_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        //print_r($data["login_data"]); die();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $data['success'] = $this->session->flashdata("success");
        $data['query'] = $this->test_model->master_fun_get_tbl_val("banner_group", array('status' => 1), array("id", "asc"));
        //$this->load->view('admin/state_list_view', $data);
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('group_list', $data);
        $this->load->view('footer');
    }

    function group_add() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');


        if ($this->form_validation->run() != FALSE) {
            $name = $this->input->post('name');


            $data['query'] = $this->test_model->master_fun_insert("banner_group", array("group_name" => $name,));
            $this->session->set_flashdata("success", array("Group successfully added."));
            redirect("banner-group/group-list", "refresh");
        } else {

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('group_add', $data);
            $this->load->view('footer');
        }
    }

    function group_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->user_model->master_fun_update("banner_group", array("id", $cid), array("status" => "0"));

        $this->session->set_flashdata("success", array("Group successfully deleted."));
        redirect("banner-group/group-list", "refresh");
    }

    function group_edit() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["cid"] = $this->uri->segment('3');

        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');


        if ($this->form_validation->run() != FALSE) {
            $name = $this->input->post('name');


            $data['query'] = $this->test_model->master_fun_update("banner_group", array("id", $data["cid"]), array("group_name" => $name));
            $this->session->set_flashdata("success", array("Group successfully updated."));
            redirect("banner-group/group-list", "refresh");
        } else {
            $data['query'] = $this->test_model->master_fun_get_tbl_val("banner_group", array("id" => $data["cid"]), array("id", "desc"));

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('group_edit', $data);
            $this->load->view('footer');
        }
    }

}

?>
