<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Press_media_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('press_media_model');
        $this->load->model('registration_admin_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('session');
        //$this->load->library('pushserver');
        //$this->load->library('email');
        //$this->load->helper('string');
        //echo current_url(); die();
        if(!logindata()){
            redirect("login");
        }
        $data["login_data"] = logindata();
    }

    public function press_list() {
        $data["login_data"] = logindata();
        $ptitle = $this->input->get('ptitle');
        $data['ptitle'] = $ptitle;
        if ($ptitle != "") {
            $totalRows = $this->press_media_model->num_row($ptitle);
            //echo $totalRows;die();
            $config = array();
            $get = $_GET;
            //print_r($get);die();
            unset($get['offset']);
            $config["base_url"] = base_url() . "press_media_master/press_list?" . http_build_query($get);
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 2;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->press_media_model->search($ptitle, $config["per_page"], $page);
            //print_r($data);die();
            $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;
        } else {
            $totalRows = $this->press_media_model->num_row($ptitle);
            $config = array();
            $get = $_GET;
            //print_r($get);die();
            $config["base_url"] = base_url() . "press_media_master/press_list";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 2;
            $config['page_query_string'] = TRUE;
            $config["uri_segment"] = 3;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            // $sort = $this->input->get("sort");
            //$by = $this->input->get("by");
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->press_media_model->search($ptitle, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;
        }
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('press_list', $data);
        $this->load->view('footer');
    }

    public function press_add() {
        $data["login_data"] = logindata();
        $this->form_validation->set_rules('ptitle', ' PACKAGE CATEGORY NAME', 'trim|required');
        $this->form_validation->set_rules('plink', ' PACKAGE CATEGORY NAME', 'trim|required');
        if (empty($_FILES['open']['name'])) {
            $this->form_validation->set_rules('open', ' IMAGE', 'trim|required');
        }
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');


        if (!empty($_FILES['open']['name'])) {
            $config['upload_path'] = './upload/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['file_name'] = $_FILES['open']['name'];

            //Load upload library and initialize configuration
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('open')) {
                $uploadData = $this->upload->data();
                $picture = $uploadData['file_name'];
            } else {
                $picture = '';
            }
        } else {
            $one = "empty";
            $picture = '';
        }


        if ($this->form_validation->run() != FALSE) {

            $test = new DateTime($this->input->post('pressdate'));
            $dob = date_format($test, 'Y-m-d');

            $data = array(
                'title' => $this->input->post('ptitle'),
                'link' => $this->input->post('plink'),
                'date' => $dob,
                'logo' => $picture,
                'created_by' => date('Y-m-d H:i:s'),
                'status' => '1');

            $data['query'] = $this->press_media_model->insert($data);
            $this->session->set_flashdata('success', "press media Successfully Added");
            redirect("press_media_master/press_list");
        } else {
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('press_add');
            $this->load->view('footer');
        }
    }

    public function press_delete() {
        $pcid = $this->uri->segment('3');
        $data = array(
            'status' => '0', 'deleted_by' => date('Y-m-d H:i:s'),);

        $data['query'] = $this->press_media_model->delete_pc($pcid, $data);
        $this->session->set_flashdata('success', 'Press Media Successfully Deleted');
        redirect("press_media_master/press_list");
    }

    public function press_edit() {
        $data["login_data"] = logindata();
        // $data["cid"] = $this->uri->segment('3');
        $ccid = $this->uri->segment('3');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('ptitle', 'PACKAGE CATEGORY NAME', 'trim|required');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

        if (!empty($_FILES['open']['name'])) {
            $config['upload_path'] = './upload/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['file_name'] = $_FILES['open']['name'];

            //Load upload library and initialize configuration
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('open')) {
                $uploadData = $this->upload->data();
                $picture = $uploadData['file_name'];
            } else {
                $picture = '';
            }
        } else {
            $picture = '';
        }
        $test = new DateTime($this->input->post('pressdate'));
        $dob = date_format($test, 'Y-m-d');


        if ($this->form_validation->run() == TRUE) {

            if ($picture) {

                $data = array('title' => $this->input->post('ptitle'), 'logo' => $picture, 'updated_by' => date('Y-m-d H:i:s'),
                    'link' => $this->input->post('plink'), 'date' => $dob);

                $data['view_data'] = $this->press_media_model->update($ccid, $data);
                $this->session->set_flashdata("success", "Press_media Successfully Updated.");
                redirect("press_media_master/press_list");
            } else {

                $data = array('title' => $this->input->post('ptitle'), 'updated_by' => date('Y-m-d H:i:s'),
                    'link' => $this->input->post('plink'), 'date' => $dob);

                $data['view_data'] = $this->press_media_model->update($ccid, $data);
                $this->session->set_flashdata("success", "Press_media Successfully Updated.");
                redirect("press_media_master/press_list");
            }
        } else {
            $data['view_data'] = $this->press_media_model->get_pc($ccid);
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('press_edit', $data, FALSE);
            $this->load->view('footer');
        }
    }

}

?>