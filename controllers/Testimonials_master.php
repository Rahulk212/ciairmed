<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Testimonials_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('testimonials_model');
        $this->load->model('user_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->app_track();
    }

    function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }

    function add() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $userid = $data["login_data"]["id"];
        if ($this->session->userdata('success') != null) {
            $data['success'] = $this->session->userdata("success");
            $this->session->unset_userdata('success');
        }
        if ($this->session->userdata('unsuccess') != null) {
            $data['unsuccess'] = $this->session->userdata("unsuccess");
            $this->session->unset_userdata('unsuccess');
        }
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('address', 'Address', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $name = $this->input->post('name');
            $address = $this->input->post('address');
            $description = $this->input->post('description');
            $config['upload_path'] = './upload/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['file_name'] = time() . $_FILES["file"]["name"];
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload("file")) {
                $error = array('error' => $this->upload->display_errors());
                $this->load->view('header');
                $this->load->view('nav', $data);
                $this->load->view('testimonials_add', $error);
                $this->load->view('footer');
            } else {
                $data = array('upload_data' => $this->upload->data());
                $file_name = $data["upload_data"]["file_name"];
                if (isset($file_name)) {
                    $time = $this->testimonials_model->get_server_time();
                    $data1 = array(
                        "name" => $name,
                        "image" => $file_name,
                        "description" => $description,
                        "address" => $address,
                        "status" => '1',
                        "createddate" => $time['UTC_TIMESTAMP()']
                    );
                    $insert = $this->testimonials_model->master_fun_insert('testimonials_master', $data1);
                    $ses = array("Testimonial Successfully Added");
                    $this->session->set_userdata('success', $ses);
                    redirect('testimonials_master/index');
                } else {
                    $ses = array("please select valid image!");
                    $this->session->set_userdata('unsuccess', $ses);
                    redirect('testimonials_master/add');
                }
            }
        } else {
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('testimonials_add');
            $this->load->view('footer');
        }
    }

    function index() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $userid = $data["login_data"]["id"];
        if ($this->session->userdata('success') != null) {
            $data['success'] = $this->session->userdata("success");
            $this->session->unset_userdata('success');
        }
        if ($this->session->userdata('unsuccess') != null) {
            $data['unsuccess'] = $this->session->userdata("unsuccess");
            $this->session->unset_userdata('unsuccess');
        }

        $data["query"] = $this->testimonials_model->get_active_record();
        $totalRows = count($data["query"]);
        $config = array();
        $config["base_url"] = base_url() . "testimonials_master/index/";
        $config["total_rows"] = $totalRows;
        $config["per_page"] = 50;
        $config["uri_segment"] = 3;
        $config['next_link'] = 'Next &rsaquo;';
        $config['prev_link'] = '&lsaquo; Previous';
        $this->pagination->initialize($config);
        $sort = $this->input->get("sort");
        $by = $this->input->get("by");
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["query"] = $this->testimonials_model->get_active_record1($config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('testimonials_view', $data);
        $this->load->view('footer');
    }

    function edit() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $id = $data["id"] = $this->uri->segment('3');
        $name = $this->input->post('name');
        $address = $this->input->post('address');
        $description = $this->input->post('description');
        if ($this->session->userdata('unsuccess') != null) {
            $data['unsuccess'] = $this->session->userdata("unsuccess");
            $this->session->unset_userdata('unsuccess');
        }
        $this->form_validation->set_rules('id', 'Id', 'required');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('address', 'Address', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $data['query'] = $this->testimonials_model->master_fun_get_tbl_val("testimonials_master", array("id" => $this->uri->segment('3')), array("id", "desc"));
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('testimonials_edit', $data);
            $this->load->view('footer');
        } else {
            if ($_FILES["file"]["name"] != NULL) {
                $config['upload_path'] = './upload/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['file_name'] = time() . $_FILES["file"]["name"];
                $this->load->library('upload', $config);
                if ($this->upload->do_upload("file")) {
                    $data = array('upload_data' => $this->upload->data());
                    $file_name = $data["upload_data"]["file_name"];
                    if (isset($file_name)) {
                        $data1 = array(
                            "name" => $name,
                            "address" => $address,
                            "image" => $file_name,
                            "description" => $description
                        );
                        $update = $this->testimonials_model->master_fun_update("testimonials_master", $this->uri->segment('3'), $data1);
                        $ses = array("Testimonial Successfully Updated!");
                        $this->session->set_userdata('success', $ses);
                        redirect('testimonials_master/index');
                    }
                }
            } else {
                $data1 = array(
                    "name" => $name,
                    "address" => $address,
                    "description" => $description
                );
                $update = $this->testimonials_model->master_fun_update("testimonials_master", $this->uri->segment('3'), $data1);
                $ses = array("Testimonials Successfully Updated");
                $this->session->set_userdata('success', $ses);
                redirect('testimonials_master/index');
            }
        }
    }

    function delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $userid = $data["login_data"]["id"];
        $cid = $this->uri->segment('3');
        $data = array(
            "status" => '0'
        );
        $delete = $this->testimonials_model->master_fun_update("testimonials_master", $this->uri->segment('3'), $data);
        if ($delete) {
            $ses = array("Testimonial Successfully Deleted");
            $this->session->set_userdata('success', $ses);
            redirect('testimonials_master/index', 'refresh');
        }
    }

}

?>
