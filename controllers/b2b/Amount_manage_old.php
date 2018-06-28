<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Amount_manage extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('b2b/amount_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('pushserver');
        $this->load->library('email');
        $data["login_data"] = logindata();
        $this->load->helper('string');
        $this->app_track();
    }

    function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }

    function lab_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->amount_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $state_serch = $this->input->get('state_search');
        $data["email"] = $this->input->get('email');
        $data['state_search'] = $state_serch;
        if ($state_serch != "" || $data["email"] != "") {
            $total_row = $this->amount_model->lab_num_rows($state_serch, $data["email"]);
            $config = array();
            $get = $_GET;
            unset($get['offset']);
            $config["base_url"] = base_url() . "b2b/Amount_manage/lab_list?" . http_build_query($get);
            $config["total_rows"] = $total_row;
            $config["per_page"] = 100;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->amount_model->lab_data($state_serch, $data["email"], $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        } else {
            $total_row = $this->amount_model->lab_num_rows();
            $config["base_url"] = base_url() . "b2b/Amount_manage/lab_list";
            $config["total_rows"] = $total_row;
            $config["per_page"] = 100;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->amount_model->srch_lab_list($config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        }
        $data["page"] = $page;
        $this->load->view('b2b/header');
        $this->load->view('b2b/nav_logistic', $data);
        $this->load->view('b2b/amount_lab_list', $data);
        $this->load->view('b2b/footer');
    }

    function details() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["id"] = $this->uri->segment(4);
        if (!empty($data["id"])) {
            $data["lab_details"] = $this->amount_model->master_fun_get_tbl_val("sample_from", array('status' => "1", "id" => $data["id"]), array("id", "desc"));
            $data["sample_credit"] = $this->amount_model->master_fun_get_tbl_val("sample_credit", array('status' => "1", "lab_fk" => $data["id"]), array("id", "desc"));
            //print_r($data["sample_credit"]); die();
            $this->load->view('b2b/header');
            $this->load->view('b2b/nav_logistic', $data);
            $this->load->view('b2b/lab_amount_details', $data);
            $this->load->view('b2b/footer');
        } else {
            redirec("b2b/Amount_manage/lab_list");
        }
    }

    function add_payment() {
        $lab_fk = $this->uri->segment(4);
        $type = $this->input->post("type");
        $amount = $this->input->post("amount");
        $note = $this->input->post("note");
        if (!empty($type) && !empty($amount) && !empty($lab_fk)) {
            $lab_data = $this->amount_model->get_val("SELECT * FROM sample_credit WHERE STATUS='1' AND lab_fk='" . $lab_fk . "' ORDER BY id DESC LIMIT 0,1");
            if (!empty($lab_data)) {
                $old_total = $lab_data[0]["total"];
            } else {
                $old_total = 0;
            }
            if ($type == 'credit') {
                $c_data = array(
                    "lab_fk" => $lab_fk,
                    "credit" => $amount,
                    "debit" => 0,
                    "total" => $old_total + $amount,
                    "transaction" => "Credited",
                    "note" => $note,
                    "created_date" => date("Y-m-d H:i:s")
                );
            } else {
                $c_data = array(
                    "lab_fk" => $lab_fk,
                    "credit" => 0,
                    "debit" => $amount,
                    "total" => $old_total - $amount,
                    "transaction" => "Debited",
                    "note" => $note,
                    "created_date" => date("Y-m-d H:i:s")
                );
            }
            $customer = $this->amount_model->master_fun_insert("sample_credit", $c_data);
            $this->session->set_flashdata("success", array("Amount successfully added."));
            redirect("b2b/Amount_manage/details/" . $lab_fk);
        } else {
            redirec("b2b/Amount_manage/lab_list");
        }
    }

}
