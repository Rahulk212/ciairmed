<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Creative_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('creative_model');
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
        $this->form_validation->set_rules('title', 'Title', 'trim|required');
        $this->form_validation->set_rules('price', 'Price', 'trim|required');
        $this->form_validation->set_rules('test[]', 'Test', 'trim|required');
        $this->form_validation->set_rules('desc_web', 'Description for Website', 'trim|required');
        $this->form_validation->set_rules('desc_app', 'Description for Application', 'trim|required');


        if ($this->form_validation->run() != FALSE) {

            $title = $this->input->post('title');
            $price = $this->input->post('price');
            $test1 = $this->input->post('test');
            $desc_web = $this->input->post('desc_web');
            $desc_app = $this->input->post('desc_app');

            $test = implode($test1, ',');

            $config['upload_path'] = './upload/creative/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['file_name'] = time() . $_FILES["sliderfile"]["name"];
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload("sliderfile")) {
                $error = array('error' => $this->upload->display_errors());

                $this->load->view('header');
                $this->load->view('nav', $data);
                $this->load->view('creative_add', $error);
                $this->load->view('footer');
            } else {
                $data = array('upload_data' => $this->upload->data());
                $file_name = $data["upload_data"]["file_name"];
                if (isset($file_name)) {
                    $time = $this->creative_model->get_server_time();
                    $data1 = array(
                        "title" => $title,
                        "image" => $file_name,
                        "price" => $price,
                        "test" => $test,
                        "desc_web" => $desc_web,
                        "desc_app" => $desc_app
                    );

                    $insert = $this->creative_model->master_fun_insert('creative_master', $data1);
                    $ses = array("Creative Successfully Inserted!");
                    $this->session->set_userdata('success', $ses);
                    redirect('creative_master/creative_list');
                } else {
                    $ses = array("please select valid image!");
                    $this->session->set_userdata('unsuccess', $ses);
                    redirect('creative_master/index');
                }
            }
        } else {
            $data['test'] = $this->creative_model->master_fun_get_tbl_val("test_master", array("status" => 1), array("id", "desc"));

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('creative_add', $data);
            $this->load->view('footer');
        }
    }

    function creative_list() {
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

        $data["query"] = $this->creative_model->get_active_record();
        $totalRows = count($data["query"]);
        $config = array();
        $config["base_url"] = base_url() . "creative_master/creative_list/";
        $config["total_rows"] = $totalRows;
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $sort = $this->input->get("sort");
        $by = $this->input->get("by");
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["query"] = $this->creative_model->get_active_record1($config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();


        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('creative_view', $data);
        $this->load->view('footer');
    }

    function creative_edit() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $id = $data["id"] = $this->uri->segment('3');

        $title = $this->input->post('title');
        $price = $this->input->post('price');
        $test1 = $this->input->post('test');
        $desc_web = $this->input->post('desc_web');
        $desc_app = $this->input->post('desc_app');
        $test = implode($test1, ',');

        if ($this->session->userdata('unsuccess') != null) {
            $data['unsuccess'] = $this->session->userdata("unsuccess");
            $this->session->unset_userdata('unsuccess');
        }
        $this->form_validation->set_rules('id', 'Id', 'required');
        $this->form_validation->set_rules('title', 'Title', 'trim|required');
        $this->form_validation->set_rules('price', 'Price', 'trim|required');
        $this->form_validation->set_rules('test[]', 'Test', 'trim|required');
        $this->form_validation->set_rules('desc_web', 'Description for Website', 'trim|required');
        $this->form_validation->set_rules('desc_app', 'Description for Application', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $data['query'] = $this->creative_model->master_fun_get_tbl_val("creative_master", array("id" => $this->uri->segment('3')), array("id", "desc"));
            $data['test'] = $this->creative_model->master_fun_get_tbl_val("test_master", array("status" => 1), array("id", "desc"));

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('creative_edit', $data);
            $this->load->view('footer');
        } else {

            if ($_FILES["sliderfile"]["name"] != NULL) {
                //echo "hello"; die();
                $config['upload_path'] = './upload/creative/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['file_name'] = time() . $_FILES["sliderfile"]["name"];
                $this->load->library('upload', $config);
                if ($this->upload->do_upload("sliderfile")) {
                    $data = array('upload_data' => $this->upload->data());
                    $file_name = $data["upload_data"]["file_name"];
                    if (isset($file_name)) {
                        $data1 = array(
                            "title" => $title,
                            "image" => $file_name,
                            "price" => $price,
                            "test" => $test,
                            "desc_web" => $desc_web,
                            "desc_app" => $desc_app
                        );

                        $update = $this->creative_model->master_fun_update("creative_master", $this->uri->segment('3'), $data1);
                        $ses = array("Creative Successfully Updated!");
                        $this->session->set_userdata('success', $ses);
                        redirect('creative_master/creative_list');
                    }
                }
            } else {
                $data1 = array(
                    "title" => $title,
                    "price" => $price,
                    "test" => $test,
                    "desc_web" => $desc_web,
                    "desc_app" => $desc_app
                );

                $update = $this->creative_model->master_fun_update("creative_master", $this->uri->segment('3'), $data1);
                $ses = array("Creative Successfully Updated!");
                $this->session->set_userdata('success', $ses);
                redirect('creative_master/creative_list');
            }
        }
    }

    function creative_delete() {
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
        $delete = $this->creative_model->master_fun_update("creative_master", $this->uri->segment('3'), $data);
        if ($delete) {
            $ses = array("Creative Successfully Deleted!");
            $this->session->set_userdata('success', $ses);
            redirect('creative_master/creative_list', 'refresh');
        }
    }

}

?>
