<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Contact_us_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('location_model');
        $this->load->model('user_model');
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

    function index() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $update = $this->user_model->master_fun_update("contact_us", array("status", '1'), array("views" => "1"));
        $name = $this->input->get('name');
        $email = $this->input->get('email');
        $mobile = $this->input->get('mobile');
        $subject = $this->input->get('subject');
        $message = $this->input->get('message');
        if ($name != "" || $email != "" || $mobile != "" || $subject != "" || $message != "") {
            $srchdata = array("name" => $name, "email" => $email, "mobile" => $mobile, "subject" => $subject, "message" => $message);
            $total_row = $this->location_model->contact_us_count($srchdata);
            $data['name'] = $name;
            $data['email'] = $email;
            $data['mobile'] = $mobile;
            $data['subject'] = $subject;
            $data['message'] = $message;
            $config = array();
            $get = $_GET;
            unset($get['offset']);
            $config["base_url"] = base_url() . "contact_us_master/index?" . http_build_query($get);
            $config["total_rows"] = $total_row;
            $config["per_page"] = 50;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->location_model->contact_us_list($srchdata, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        } else {
            $srchdata = array();
            $totalRows = $this->location_model->contact_us_count($srchdata);
            $config = array();
            $config["base_url"] = base_url() . "contact_us_master/index/";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 10;
            $config["uri_segment"] = 3;
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $data["query"] = $this->location_model->contact_us_list($srchdata, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        }
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('contact_us_list', $data);
        $this->load->view('footer');
    }

    function delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->user_model->master_fun_update("contact_us", array("id", $cid), array("status" => "0"));
        $this->session->set_flashdata("success", array("Contact successfully deleted!"));
        redirect("contact_us_master", "refresh");
    }

}

?>