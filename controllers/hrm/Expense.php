<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Expense extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('user_model');
        $this->load->model('hrm/expense_model');
        $this->load->helper('string');
        //echo current_url(); die();

        $data["login_data"] = is_hrmlogin();
    }

    function index() {
        if (!is_hrmlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_hrmlogin();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $this->load->library('pagination');
        $search = $this->input->get('search');
        $data['search'] = $search;
        if ($search != "") {
            $total_row = $this->expense_model->search_num($search);
            $config = array();
            $config["base_url"] = base_url() . "hrm/expense/index?" . http_build_query($get);
            $config["total_rows"] = $total_row;
            $config["per_page"] = 10;
            $config["uri_segment"] = 3;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $config['page_query_string'] = TRUE;
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->expense_model->list_search($search, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        } else {
            $search = "";
            $totalRows = $this->expense_model->search_num($search);
            $config = array();
            $config["base_url"] = base_url() . "hrm/expense/index/";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 10;
            $config["uri_segment"] = 3;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data["query"] = $this->expense_model->list_search($search, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        }
        $this->load->view('hrm/header');
        $this->load->view('hrm/nav', $data);
        $this->load->view('hrm/expense_list', $data);
        $this->load->view('hrm/footer');
    }
    function add() {
        if (!is_hrmlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_hrmlogin();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $data['error'] = $this->session->flashdata("error");
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Item Name', 'trim|required');
        $this->form_validation->set_rules('amount', 'Price of Item', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $name = $this->input->post('name');
            $from = $this->input->post('from');
            $date = $this->input->post('date');
            $amount = $this->input->post('amount');
            //docs
            $files = $_FILES;
            $this->load->library('upload');
            $config['allowed_types'] = 'jpg|gif|png|jpeg|pdf|PDF|doc|DOC';
            if ($files['bill']['name'] != "") {
                $config['upload_path'] = './upload/employee/';
                $config['file_name'] = time().$files['bill']['name'];
                $this->upload->initialize($config);
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0755, TRUE);
                }
                if (!$this->upload->do_upload('bill')) {
                    $error = $this->upload->display_errors();
                    $error = str_replace("<p>", "", $error);
                    $error = str_replace("</p>", "", $error);
                    $this->session->set_flashdata("error", $error);
                    redirect("hrm/expense/add", "refresh");
                } else {
                    $doc_data = $this->upload->data();
                    $bill = $doc_data['file_name'];
                }
            }
            $data1 = array(
                "item_name" => $name,
                "purchase_from" => $from,
                "purchase_date" => $date,
                "amount" => $amount,
                "bill" => $bill,
                "created_by" => $data["login_data"]["id"],
                "created_date" => date("Y-m-d H:i:s")
            );
            $insert = $this->expense_model->insert("hrm_expense", $data1);
            $this->session->set_flashdata("success", "Expense successfully added.");
            redirect("hrm/expense", "refresh");
        } else {
            $this->load->view('hrm/header', $data);
            $this->load->view('hrm/nav', $data);
            $this->load->view('hrm/expense_add', $data);
            $this->load->view('hrm/footer', $data);
        }
    }

    function delete($cid) {
        if (!is_hrmlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_hrmlogin();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['query'] = $this->expense_model->update("hrm_expense", array("id" => $cid), array("status" => '0'));
        $this->session->set_flashdata("success", "Expense successfully deleted.");
        redirect("hrm/expense", "refresh");
    }

    function edit($cid) {
        if (!is_hrmlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_hrmlogin();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['cid'] = $cid;
        $data['error'] = $this->session->flashdata("error");
        $data['query'] = $this->expense_model->get_one("hrm_expense", array("id" => $cid,"status" => 1));
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Item Name', 'trim|required');
        $this->form_validation->set_rules('amount', 'Price of Item', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $name = $this->input->post('name');
            $from = $this->input->post('from');
            $date = $this->input->post('date');
            $amount = $this->input->post('amount');
            //docs
            $files = $_FILES;
            $this->load->library('upload');
            $config['allowed_types'] = 'jpg|gif|png|jpeg|pdf|PDF|doc|DOC';
            if ($files['bill']['name'] != "") {
                $config['upload_path'] = './upload/employee/';
                $config['file_name'] = time().$files['bill']['name'];
                $this->upload->initialize($config);
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0755, TRUE);
                }
                if (!$this->upload->do_upload('bill')) {
                    $error = $this->upload->display_errors();
                    $error = str_replace("<p>", "", $error);
                    $error = str_replace("</p>", "", $error);
                    $this->session->set_flashdata("error", $error);
                    redirect("hrm/expense/edit/".$cid, "refresh");
                } else {
                    $doc_data = $this->upload->data();
                    $bill = $doc_data['file_name'];
                }
            }
            if (empty($bill)) {
                $bill = $data['query']->bill;
            }
            $data1 = array(
                "item_name" => $name,
                "purchase_from" => $from,
                "purchase_date" => $date,
                "amount" => $amount,
                "bill" => $bill,
                "updated_by" => $data["login_data"]["id"],
                "updated_date" => date("Y-m-d H:i:s")
            );
            $update = $this->expense_model->update("hrm_expense", array("id" => $cid), $data1);
            $this->session->set_flashdata("success", "Expense successfully updated.");
            redirect("hrm/expense", "refresh");
        } else {
            $this->load->view('hrm/header', $data);
            $this->load->view('hrm/nav', $data);
            $this->load->view('hrm/expense_edit', $data);
            $this->load->view('hrm/footer', $data);
        }
    }

}

?>