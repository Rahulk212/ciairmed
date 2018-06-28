<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Slider extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('slider_model');
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

        $this->load->library('form_validation');
        $this->form_validation->set_rules('group', 'Banner Group', 'trim|required');
        //$this->form_validation->set_rules('sliderfile', 'Banner', 'trim|required');


        if ($this->form_validation->run() != FALSE) {

            $group = $this->input->post('group');


            $config['upload_path'] = './upload/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['file_name'] = time() . $_FILES["sliderfile"]["name"];
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload("sliderfile")) {
                $error = array('error' => $this->upload->display_errors());

                $this->load->view('header');
                $this->load->view('nav', $data);
                $this->load->view('slider_add', $error);
                $this->load->view('footer');
            } else {
                $data = array('upload_data' => $this->upload->data());
                $file_name = $data["upload_data"]["file_name"];
                if (isset($file_name)) {
                    $time = $this->slider_model->get_server_time();
                    $data1 = array(
                        "group" => $group,
                        "pic" => $file_name,
                    );

                    $insert = $this->slider_model->master_fun_insert('banner_master', $data1);
                    $ses = array("Slider Successfully Inserted!");
                    $this->session->set_userdata('success', $ses);
                    redirect('slider/slider_list');
                } else {
                    $ses = array("please select valid image!");
                    $this->session->set_userdata('unsuccess', $ses);
                    redirect('slider/index');
                }
            }
        } else {
            $data['query'] = $this->slider_model->master_fun_get_tbl_val("banner_group", array("status" => 1), array("id", "desc"));

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('slider_add');
            $this->load->view('footer');
        }
    }

    function slider_list() {
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

        $data["query"] = $this->slider_model->get_active_record();
        $totalRows = count($data["query"]);
        $config = array();
        $config["base_url"] = base_url() . "slider/slider_list/";
        $config["total_rows"] = $totalRows;
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $sort = $this->input->get("sort");
        $by = $this->input->get("by");
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["query"] = $this->slider_model->get_active_record1($config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();


        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('slider_view', $data);
        $this->load->view('footer');
    }

    function slider_edit() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $id = $data["id"] = $this->uri->segment('3');
        $group = $this->input->post('group');
        if ($this->session->userdata('unsuccess') != null) {
            $data['unsuccess'] = $this->session->userdata("unsuccess");
            $this->session->unset_userdata('unsuccess');
        }
        $this->form_validation->set_rules('id', 'Id', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['query'] = $this->slider_model->master_fun_get_tbl_val("banner_master", array("id" => $this->uri->segment('3')), array("id", "desc"));
            $data['group'] = $this->slider_model->master_fun_get_tbl_val("banner_group", array("status" => 1), array("id", "desc"));
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('slider_edit', $data);
            $this->load->view('footer');
        } else {

            if ($_FILES["sliderfile"]["name"] != NULL) {
                //echo "hello"; die();
                $config['upload_path'] = './upload/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['file_name'] = time() . $_FILES["sliderfile"]["name"];
                $this->load->library('upload', $config);
                if ($this->upload->do_upload("sliderfile")) {
                    $data = array('upload_data' => $this->upload->data());
                    $file_name = $data["upload_data"]["file_name"];
                    if (isset($file_name)) {
                        $data1 = array(
                            "pic" => $file_name,
                            "group" => $group,
                        );

                        $update = $this->slider_model->master_fun_update("banner_master", $this->uri->segment('3'), $data1);
                        $ses = array("Slider Successfully Updated!");
                        $this->session->set_userdata('success', $ses);
                        redirect('slider/slider_list');
                    }
                }
            } else {

                $ses = array("Slider Successfully Updated!");
                $this->session->set_userdata('success', $ses);
                redirect('slider/slider_list');
            }
        }
    }

    function slider_delete() {
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
        //$delete=$this->admin_model->delete($cid,$data);
        $delete = $this->slider_model->master_fun_update("banner_master", $this->uri->segment('3'), $data);
        if ($delete) {
            $ses = array("Slider Successfully Deleted!");
            $this->session->set_userdata('success', $ses);
            redirect('slider/slider_list', 'refresh');
        }
    }

}

?>
