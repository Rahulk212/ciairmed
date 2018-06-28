<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Press_release extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Master_model');
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
        $this->form_validation->set_rules('date', 'Date', 'trim|required');
        $this->form_validation->set_rules('link', 'Link', 'trim|required');

        if ($this->form_validation->run() != FALSE) {

            $title = $this->input->post('title');
            $date = $this->input->post('date');
            $link = $this->input->post('link');
            $news_name = '';
            if (!empty($_FILES["news"]["name"])) {
                $config['upload_path'] = './upload/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['file_name'] = time() . $_FILES["news"]["name"];
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload("news")) {
                    $ses = array($this->upload->display_errors());
                    $this->session->set_userdata('unsuccess', $ses);
                    redirect('press_release/index');
                } else {
                    $data = array('upload_data' => $this->upload->data());
                    $news_name = $data["upload_data"]["file_name"];
                }
            }
            $pic_name = '';
            if (!empty($_FILES["pic"]["name"])) {
                $config['upload_path'] = './upload/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['file_name'] = time() . $_FILES["pic"]["name"];
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload("pic")) {
                    $ses = array($this->upload->display_errors());
                    $this->session->set_userdata('unsuccess', $ses);
                    redirect('press_release/index');
                } else {
                    $data = array('upload_data' => $this->upload->data());
                    $pic_name = $data["upload_data"]["file_name"];
                }
            }
            if ($pic_name == '' || $news_name == '') {
                $ses = array("NEWS logo and pic is required.");
                $this->session->set_userdata('unsuccess', $ses);
                redirect('press_release/index');
            }

            $data1 = array(
                "title" => $title,
                "date" => $date,
                "news_logo" => $news_name,
                "pic" => $pic_name,
                "link" => $link,
                "createddate" => date("Y-m-d H:i:s")
            );
            $insert = $this->Master_model->master_fun_insert('press_release', $data1);
            $ses = array("Press release Successfully added!");
            $this->session->set_userdata('success', $ses);
            redirect('press_release/press_list');
        } else {
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('press_release_add');
            $this->load->view('footer');
        }
    }

    function press_list() {
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

        $data['query'] = $this->Master_model->master_fun_get_tbl_val("press_release", array("status" => 1), array("id", "desc"));
        $totalRows = count($data["query"]);
        $config = array();
        $config["base_url"] = base_url() . "press_release/press_list/";
        $config["total_rows"] = $totalRows;
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $sort = $this->input->get("sort");
        $by = $this->input->get("by");
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["query"] = $this->Master_model->get_active_record1($config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();

        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('press_release_view', $data);
        $this->load->view('footer');
    }

    function edit() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $id = $data["id"] = $this->uri->segment('3');
        $data["id"] = $id;
        $group = $this->input->post('group');
        if ($this->session->userdata('unsuccess') != null) {
            $data['unsuccess'] = $this->session->userdata("unsuccess");
            $this->session->unset_userdata('unsuccess');
        }
        $this->form_validation->set_rules('title', 'Title', 'trim|required');
        $this->form_validation->set_rules('date', 'Date', 'trim|required');
        $this->form_validation->set_rules('link', 'Link', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $data['query'] = $this->Master_model->master_fun_get_tbl_val("press_release", array("id" => $this->uri->segment('3')), array("id", "desc"));
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('press_release_edit', $data);
            $this->load->view('footer');
        } else {
            $title = $this->input->post('title');
            $date = $this->input->post('date');
            $link = $this->input->post('link');
            $data1 = array(
                "title" => $title,
                "date" => $date,
                "link" => $link,
                "createddate" => date("Y-m-d H:i:s")
            );
            if (!empty($_FILES["news"]["name"])) {
                $config['upload_path'] = './upload/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['file_name'] = time() . $_FILES["news"]["name"];
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload("news")) {
                    $ses = array($this->upload->display_errors());
                    $this->session->set_userdata('unsuccess', $ses);
                    redirect('press_release/edit/' . $id);
                } else {
                    $data = array('upload_data' => $this->upload->data());
                    $news_name = $data["upload_data"]["file_name"];
                    $data1 = $data1 + array("news_logo" => $news_name);
                }
            }

            if (!empty($_FILES["pic"]["name"])) {
                $config['upload_path'] = './upload/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['file_name'] = time() . $_FILES["pic"]["name"];
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload("pic")) {
                    $ses = array($this->upload->display_errors());
                    $this->session->set_userdata('unsuccess', $ses);
                    redirect('press_release/edit/' . $id);
                } else {
                    $data = array('upload_data' => $this->upload->data());
                    $pic_name = $data["upload_data"]["file_name"];
                    $data1 = $data1 + array("pic" => $pic_name);
                }
            }
            $insert = $this->Master_model->master_fun_update('press_release', $this->uri->segment(3), $data1);
            $ses = array("Press release Successfully updated!");
            $this->session->set_userdata('success', $ses);
            redirect('press_release/press_list');
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
        $delete = $this->Master_model->master_fun_update("press_release", $this->uri->segment('3'), $data);
        if ($delete) {
            $ses = array("Press release successfully deleted!");
            $this->session->set_userdata('success', $ses);
            redirect('press_release/press_list');
        }
    }

}

?>
