<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Offer_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('health_feed_model');
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
        $desc = $this->input->post('desc');
        $per = $this->input->post('caseback_per');
        if ($this->session->userdata('unsuccess') != null) {
            $data['unsuccess'] = $this->session->userdata("unsuccess");
            $this->session->unset_userdata('unsuccess');
        }
        $data['success'] = $this->session->flashdata("success");
        $this->form_validation->set_rules('desc', 'Description', 'trim|required');
        $this->form_validation->set_rules('caseback_per', 'CaseBack Percentage', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $data['query'] = $this->health_feed_model->master_fun_get_tbl_val("offer_master", array("id" => 1), array("id", "desc"));
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('offer_view', $data);
            $this->load->view('footer');
        } else {
            if ($_FILES["sliderfile"]["name"] != NULL) {
                $config['upload_path'] = './upload/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['file_name'] = time() . $_FILES["sliderfile"]["name"];
                $this->load->library('upload', $config);
                if ($this->upload->do_upload("sliderfile")) {
                    $data = array('upload_data' => $this->upload->data());
                    $file_name = $data["upload_data"]["file_name"];
                    if (isset($file_name)) {
                        $data1 = array(
                            "image" => $file_name,
                            "description" => $desc,
                            "caseback_per" => $per
                        );

                        $update = $this->health_feed_model->master_fun_update("offer_master", 1, $data1);
                        $ses = array("Offer Successfully Updated!");
                        $this->session->set_flashdata('success', $ses);
                        redirect('offer_master');
                    }
                }
            } else {
                $data1 = array(
                    "description" => $desc,
                    "caseback_per" => $per
                );
                $update = $this->health_feed_model->master_fun_update("offer_master", 1, $data1);
                $ses = array("Offer Successfully Updated!");
                $this->session->set_flashdata('success', $ses);
                redirect('offer_master');
            }
        }
    }

}

?>
