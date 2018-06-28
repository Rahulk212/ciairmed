<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Phlebo_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('user_model');
        $this->load->model('phlebo_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        //echo current_url(); die();

        $data["login_data"] = logindata();
    }

    function phlebo_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        //print_r($data["login_data"]); die();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);


        $name = $this->input->get('name');
        $mobile = $this->input->get('mobile');
        $email = $this->input->get('email');
        $type = $this->input->get('type');
        $test_city = $this->input->get("test_city");
        if ($name != "" || $email != "" || $mobile != "" || $type != ''||$test_city!='') {
            $srchdata = array("name" => $name, "email" => $email, "mobile" => $mobile, "type" => $type,"test_city"=>$test_city);
            $data['name'] = $name;
            $data['mobile'] = $mobile;
            $data['email'] = $email;
            $data['type'] = $type;
            $data["test_city1"]=$test_city;
            $totalRows = $this->phlebo_model->phlebocount_list($srchdata);
            $config = array();
            $config["base_url"] = base_url() . "phlebo_master/phlebo_list/";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 10;
            $config["uri_segment"] = 3;
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $data["query"] = $this->phlebo_model->phlebolist_list($srchdata, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        } else {
            $srchdata = array();
            $totalRows = $this->phlebo_model->phlebocount_list($srchdata);
            $config = array();
            $config["base_url"] = base_url() . "phlebo_master/phlebo_list/";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 10;
            $config["uri_segment"] = 3;
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $data["query"] = $this->phlebo_model->phlebolist_list($srchdata, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        }
        $data['test_city'] = $this->phlebo_model->master_fun_get_tbl_val("test_cities", array("status" => '1'), array("name", "asc"));
        $data['success'] = $this->session->flashdata("success");
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('phlebo_list', $data);
        $this->load->view('footer');
    }

    function phlebo_add() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('test_city', 'Test City', 'required');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');


        if ($this->form_validation->run() != FALSE) {
            $name = $this->input->post('name');
            $email = $this->input->post('email');
            $mobile = $this->input->post('mobile');
            $type = $this->input->post('type');
            $password = $this->input->post('password');
            $test_city = $this->input->post("test_city");

            $data['query'] = $this->phlebo_model->master_fun_insert("phlebo_master", array("name" => $name, "email" => $email, "type" => $type, "password" => $password, "mobile" => $mobile, "status" => '1', "test_city" => $test_city, "created_by" => $data["login_data"]["id"]));
            $this->session->set_flashdata("success", array("Phlebo successfully added."));
            redirect("phlebo_master/phlebo_list", "refresh");
        } else {
            $data['test_city'] = $this->phlebo_model->master_fun_get_tbl_val("test_cities", array("status" => '1'), array("name", "asc"));
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('phlebo_add', $data);
            $this->load->view('footer');
        }
    }

    function phlebo_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->user_model->master_fun_update("phlebo_master", array("id", $cid), array("status" => "0"));

        $this->session->set_flashdata("success", array("Phlebo successfully deleted."));
        redirect("phlebo_master/phlebo_list", "refresh");
    }

    function phlebo_active() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->user_model->master_fun_update("phlebo_master", array("id", $cid), array("status" => "1"));

        $this->session->set_flashdata("success", array("Phlebo successfully activated."));
        redirect("phlebo_master/phlebo_list", "refresh");
    }

    function phlebo_deactive() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->user_model->master_fun_update("phlebo_master", array("id", $cid), array("status" => "2"));

        $this->session->set_flashdata("success", array("Phlebo successfully deactivated."));
        redirect("phlebo_master/phlebo_list", "refresh");
    }

    function phlebo_edit() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["cid"] = $this->uri->segment('3');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('test_city', 'Test City', 'required');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');

        if ($this->form_validation->run() != FALSE) {
            $name = $this->input->post('name');
            $email = $this->input->post('email');
            $mobile = $this->input->post('mobile');
            $type = $this->input->post('type');
            $password = $this->input->post('password');
            $test_city = $this->input->post('test_city');
            $data['query'] = $this->phlebo_model->master_fun_update("phlebo_master", array("id", $data["cid"]), array("name" => $name, "email" => $email, "password" => $password, "type" => $type, "test_city" => $test_city, "mobile" => $mobile));
            $this->session->set_flashdata("success", array("Phlebo successfully updated."));
            redirect("phlebo_master/phlebo_list", "refresh");
        } else {
            $data['query'] = $this->phlebo_model->master_fun_get_tbl_val("phlebo_master", array("id" => $data["cid"]), array("id", "desc"));
            $data['test_city'] = $this->phlebo_model->master_fun_get_tbl_val("test_cities", array("status" => '1'), array("name", "asc"));
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('phlebo_edit', $data);
            $this->load->view('footer');
        }
    }

}

?>