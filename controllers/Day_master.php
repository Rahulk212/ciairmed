<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Day_master extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('Day_model');
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

    public function day_list() {
        $relid = $this->input->get('search');
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $totalRows = $this->Day_model->num_row('phlebo_day_master', array('status' => 1));
        $config = array();
        $config["base_url"] = base_url() . "Day_master/day_list/";
        $config["total_rows"] = $totalRows;
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $config['cur_tag_open'] = '<span>';
        $config['cur_tag_close'] = '</span>';
        $config['next_link'] = 'Next <span class="icon-text">&#59230;</span>';
        $config['prev_link'] = '<span class="icon-text">&#59229;</span> Previous';
        $this->pagination->initialize($config);
        $sort = $this->input->get("sort");
        $by = $this->input->get("by");
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["query"] = $this->Day_model->manage_condition_view($relid, $config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();
        $cnt = 0;
        foreach ($data['query'] as $key) {
            $data['query'][$cnt] = $key;
            $cnt++;
        }
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('day_list', $data);
        $this->load->view('footer');
    }

    public function day_add() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Day', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        if ($this->form_validation->run() != FALSE) {
            $post['name'] = $this->input->post('name');
            $post['status'] = "1";
            $post['created_by'] = $data['user']->id;
            $post['createddate'] = date('d-m-y g:i:s');
            $post['deleted_by'] = $data['user']->id;
            $post['updated_by'] = $data['user']->id;
            /* print_r($post); die(); */
            $data['query'] = $this->Day_model->master_get_insert("phlebo_day_master", $post);
            $this->session->set_flashdata("success", array("Day Successfull Added."));
            redirect("Day_master/day_list", "refresh");
        } else {
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('day_add', $data);
            $this->load->view('footer');
        }
    }

    public function day_edit() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["cid"] = $this->uri->segment('3');
        $ids = $data["cid"];
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Day', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        if ($this->form_validation->run() != FALSE) {
            $post['name'] = $this->input->post('name');
            $post['status'] = "1";
            $data['query'] = $this->Day_model->master_get_update("phlebo_day_master", array('id' => $_POST['id']), $post);
            $cnt = 0;
            $this->session->set_flashdata("success", array("Day Successfull Updated."));
            redirect("Day_master/day_list", "refresh");
        } else {
            $data['query'] = $this->Day_model->master_get_tbl_val("phlebo_day_master", array("id" => $data["cid"]), array("id", "desc"));
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('day_edit', $data);
            $this->load->view('footer');
        }
    }

    public function day_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->Day_model->master_get_update("phlebo_day_master", array("id" => $cid), array("status" => "0"), array("id", "desc"));
        $this->session->set_flashdata("success", array("Day Successfull Deleted."));
        redirect("Day_master/day_list", "refresh");
    }

}

?>