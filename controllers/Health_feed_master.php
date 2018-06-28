<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Health_feed_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('health_feed_model');
        $this->load->model('user_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('email');
        $this->load->helper('url');
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
        $this->load->helper("Email");
        $email_cnt = new Email;
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
        $this->form_validation->set_rules('desc', 'Description', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $title = $this->input->post('title');
            $desc = $this->input->post('desc');
            $config['upload_path'] = './upload/health_feed/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['file_name'] = time() . $_FILES["sliderfile"]["name"];
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload("sliderfile")) {
                $error = array('error' => $this->upload->display_errors());
                $this->load->view('header');
                $this->load->view('nav', $data);
                $this->load->view('health_feed_add', $error);
                $this->load->view('footer');
            } else {
                $data = array('upload_data' => $this->upload->data());
                $file_name = $data["upload_data"]["file_name"];
                if (isset($file_name)) {
                    $time = $this->health_feed_model->get_server_time();
                    $data1 = array(
                        "title" => $title,
                        "image" => $file_name,
                        "desc" => $desc,
                        "created_date" => $time['UTC_TIMESTAMP()']
                    );
                    $users = $this->user_model->master_fun_get_tbl_val("customer_master", array("status !=" => 0), array("id", "asc"));
                    $insert = $this->health_feed_model->master_fun_insert('health_feed_master', $data1);
                    if ($insert) {
                        $img_src = base_url() . "upload/health_feed/" . $file_name;
                        foreach ($users as $key) {
                            $config['mailtype'] = 'html';
                            $this->email->initialize($config);
                            $message1 = '<div style="padding:0 4%;">
                    <h4 style="color:#67B1A3;text-decoration: underline;">' . ucfirst($title) . '</h4>
		<img src="' . $img_src . '" style="padding:2px;width:100%;border:1px solid #f1f2f3;"/>
                        <p style="color:#7e7e7e;font-size:13px;">' . substr(ucfirst($desc), 0, 150) . '. </p>
		<a href="' . base_url() . 'user_master/health_feed_details/' . $insert . '" style="background:#D01130;color: #fff;padding: 2%;text-decoration: none;border-radius: 5px;float:right;" href="">Read More</a>
                </div>';
                            $message1 = $email_cnt->get_design($message1);
                            $this->email->to($key['email']);
                            $this->email->from("donotreply@airmed.com", "AirmedLabs");
                            $this->email->subject(ucfirst($title));
                            $this->email->message($message1);
                            $this->email->send();
                        }
                        $ses = array("Health feed Successfully Added");
                        $this->session->set_userdata('success', $ses);
                        redirect('health_feed_master/health_feed_list');
                    } else {
                        $ses = array("Health feed Not Inserted!");
                        $this->session->set_userdata('success', $ses);
                        redirect('health_feed_master/health_feed_list');
                    }
                } else {
                    $ses = array("please select valid image!");
                    $this->session->set_userdata('unsuccess', $ses);
                    redirect('health_feed_master/index');
                }
            }
        } else {
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('health_feed_add');
            $this->load->view('footer');
        }
    }

    function health_feed_list() {
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
        $data["query"] = $this->health_feed_model->get_active_record();
        $totalRows = count($data["query"]);
        $config = array();
        $config["base_url"] = base_url() . "health_feed_master/health_feed_list/";
        $config["total_rows"] = $totalRows;
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $sort = $this->input->get("sort");
        $by = $this->input->get("by");
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["query"] = $this->health_feed_model->get_active_record1($config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('health_feed_view', $data);
        $this->load->view('footer');
    }

    function health_feed_edit() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $id = $data["id"] = $this->uri->segment('3');
        $title = $this->input->post('title');
        $desc = $this->input->post('desc');
        if ($this->session->userdata('unsuccess') != null) {
            $data['unsuccess'] = $this->session->userdata("unsuccess");
            $this->session->unset_userdata('unsuccess');
        }
        $this->form_validation->set_rules('id', 'Id', 'required');
        $this->form_validation->set_rules('title', 'Title', 'trim|required');
        $this->form_validation->set_rules('desc', 'Description', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $data['query'] = $this->health_feed_model->master_fun_get_tbl_val("health_feed_master", array("id" => $this->uri->segment('3')), array("id", "desc"));
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('health_feed_edit', $data);
            $this->load->view('footer');
        } else {
            if ($_FILES["sliderfile"]["name"] != NULL) {
                $config['upload_path'] = './upload/health_feed/';
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
                            "desc" => $desc,
                        );
                        $update = $this->health_feed_model->master_fun_update("health_feed_master", $this->uri->segment('3'), $data1);
                        $ses = array("Health feed Successfully Updated");
                        $this->session->set_userdata('success', $ses);
                        redirect('health_feed_master/health_feed_list');
                    }
                }
            } else {
                $data1 = array(
                    "title" => $title,
                    "desc" => $desc,
                );
                $update = $this->health_feed_model->master_fun_update("health_feed_master", $this->uri->segment('3'), $data1);
                $ses = array("Health Feed Successfully Updated");
                $this->session->set_userdata('success', $ses);
                redirect('health_feed_master/health_feed_list');
            }
        }
    }

    function health_feed_delete() {
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
        $delete = $this->health_feed_model->master_fun_update("health_feed_master", $this->uri->segment('3'), $data);
        if ($delete) {
            $ses = array("Health Feed Successfully Deleted");
            $this->session->set_userdata('success', $ses);
            redirect('health_feed_master/health_feed_list', 'refresh');
        }
    }

}

?>
