<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class SupportDoc_system extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('supportDoc_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        //echo current_url(); die();
        $data["login_data"] = logindata();
    }

    function index() {
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $totalRows = $this->supportDoc_model->num_row();
        $config["base_url"] = base_url() . "SupportDoc_system/";
        $config["total_rows"] = $totalRows;
        $config["per_page"] = 10;
        $config['page_query_string'] = TRUE;
        $config["uri_segment"] = 3;
        $config['cur_tag_open'] = '<span>';
        $config['cur_tag_close'] = '</span>';
        $config['next_link'] = 'Next <span class="icon-text">&#59230;</span>';
        $config['prev_link'] = '<span class="icon-text">&#59229;</span> Previous';
        $this->pagination->initialize($config);
        $sort = $this->input->get("sort");
        $by = $this->input->get("by");
        $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
        $data['query'] = $this->supportDoc_model->master_get_search($config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();
        $data["cnt"] = $page;
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('SupportDoc_list', $data);
        $this->load->view('footer');
    }

    public function supportdoc_delete() {
        $cid = $this->uri->segment('3');
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $post['status'] = '0';
        $post['updated_date'] = date('d-m-y g:i:s');
        $data['query'] = $this->supportDoc_model->master_get_spam('support_system', $cid, $post);
        $this->session->set_flashdata('success', 'Message Successfull Deleted');
        redirect('SupportDoc_system', 'refresh');
    }

    function supportdoc_pending() {
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $ccid = $this->uri->segment('3');
        $post['status'] = 1;
        $post['updated_by'] = $data['user']->id;
        $post['updated_date'] = date('d-m-y g:i:s');
        $data['query'] = $this->supportDoc_model->master_get_update("support_system", $ccid, $post);
        $this->session->set_flashdata('success', 'Message Successful Pending');
        redirect('SupportDoc_system', 'refresh');
    }

    function supportdoc_complate() {
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $ccid = $this->uri->segment('3');
        $post['status'] = 2;
        $post['updated_by'] = $data['user']->id;
        //$post['updated_date'] = date('d-m-y g:i:s');
        //print_R($post); die();
        $data['query'] = $this->supportDoc_model->master_get_update("support_system", $ccid, $post);
        $this->session->set_flashdata('success', 'Message Successfull Completed');
        redirect('SupportDoc_system', 'refresh');
    }

    function supportdoc_add() {
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $this->load->library('form_validation');
        $this->form_validation->set_rules('message', 'Message', 'trim|required');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

        if ($this->form_validation->run() != FALSE) {

            $post['message'] = $this->input->post('message');
            $post['created_date'] = date('Y-m-d H:i:s');
            //$post['updated_date'] = date('d-m-y g:i:s');
            $post['created_by'] = $data['user']->id	;
            //$post['updated_by'] = $data['user']->id;

            /* echo "<pre>";
              print_r($post);
              exit; */
            $data['query'] = $this->supportDoc_model->master_get_insert("support_system", $post);
            $this->session->set_flashdata('success', "Message Successfull Added");
            redirect("SupportDoc_system");
        } else {
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('supportDoc_add', $data);
            $this->load->view('footer');
        }
    }

    function supportdoc_edit() {

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["cid"] = $this->uri->segment('3');
        $ccid = $this->uri->segment('3');

        $this->load->library('form_validation');

        $this->form_validation->set_rules('message', 'Message', 'trim|required');

        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

        if ($this->form_validation->run() == TRUE) {

            $post['message'] = $this->input->post('message');
            //$post['status'] = 2;
            $post['updated_by'] = $data['user']->id;
            $post['updated_date'] = date('d-m-y g:i:s');
            $data['view_data'] = $this->supportDoc_model->master_get_update("support_system", $ccid, $post);
            /*  print_r($data['view_data']);
              exit; */
            $this->session->set_flashdata("success", "Message Successfull Updated.");

            redirect("SupportDoc_system");
        } else {

            $data['view_data'] = $this->supportDoc_model->master_get_table('support_system', array('id' => $ccid), array('id', 'DESC'));

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('supportDoc_edit', $data, FALSE);
            $this->load->view('footer');
        }
    }

}

?>
