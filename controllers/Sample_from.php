<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sample_from extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('sample_from_model');
        $this->load->model('registration_admin_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('session');
        $data["login_data"] = logindata();
    }

    public function sample_list() {
        $data["login_data"] = logindata();
        $cfname = $this->input->get('cfname');
        $data['cfname'] = $cfname;
        if ($cfname != "") {
            $totalRows = $this->sample_from_model->num_row($cfname);
            //echo $totalRows;die();
            $config = array();
            $get = $_GET;
            //print_r($get);die();
            unset($get['offset']);
            $config["base_url"] = base_url() . "Sample_from/sample_list?" . http_build_query($get);
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 2;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->sample_from_model->search($cfname, $config["per_page"], $page);
            //print_r($data);die();
            $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;
        } else {
            $totalRows = $this->sample_from_model->num_row($cfname);
            $config = array();
            $get = $_GET;
            //print_r($get);die();
            $config["base_url"] = base_url() . "Sample_from/sample_list";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 5;
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
            $data['query'] = $this->sample_from_model->search($cfname, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;
        }
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('sample_from_list', $data);
        $this->load->view('footer');
    }

    public function add() {
        $data["login_data"] = logindata();
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        if ($this->form_validation->run() != FALSE) {
            $name = $this->input->post('pcname');
            $data = array(
                'name' => $this->input->post('name'),
                'createddate' => date("Y-m-d H:i:s"),
            );

            $data['query'] = $this->sample_from_model->insert($data);
            $this->session->set_flashdata('success', "Data Successfully Added");
            redirect("Sample_from/sample_list");
        } else {
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('sample_from_add');
            $this->load->view('footer');
        }
    }

    public function delete() {
        $pcid = $this->uri->segment('3');
        $data = array(
            'status' => '0');

        $data['query'] = $this->sample_from_model->delete_pc($pcid, $data);
        $this->session->set_flashdata('success', 'Data Successfully Deleted');
        redirect("Sample_from/sample_list");
    }

    public function edit() {
        $data["login_data"] = logindata();
        $ccid = $this->uri->segment('3');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

        if ($this->form_validation->run() == TRUE) {
            $data = array('name' => $this->input->post('name'));
            $data['view_data'] = $this->sample_from_model->update($ccid, $data);
            $this->session->set_flashdata("success", "Data Successfully Updated.");
            redirect("Sample_from/sample_list");
        } else {
            $data['view_data'] = $this->sample_from_model->get_pc($ccid);
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('sample_from_edit', $data, FALSE);
            $this->load->view('footer');
        }
    }

}

?>