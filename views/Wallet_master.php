<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Wallet_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('user_model');
        $this->load->model('wallet_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $data["login_data"] = logindata();
    }

    function wallet_update() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $this->load->library('form_validation');

        $this->form_validation->set_rules('user', 'Full Name', 'trim|required');

        $this->form_validation->set_rules('type', 'Type', 'trim|required');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required|numeric');

        if ($this->form_validation->run() != FALSE) {

            $user = $this->input->post('user');
            $type = $this->input->post('type');
            $amount = $this->input->post('amount');

            $query = $this->wallet_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $user), array("id", "desc"));

            $total = $query[0]['total'];

            if ($type == "1") {

                $data = array(
                    "cust_fk" => $user,
                    "credit" => $amount,
                    "total" => $total + $amount,
                    "created_time" => date('Y-m-d H:i:s')
                );

                $data['query'] = $this->wallet_model->master_fun_insert("wallet_master", $data);
                $this->session->set_flashdata("success", array("$amount Credited in Account successfully."));
                redirect("wallet_master/account_history", "refresh");
            } else {

                $data = array(
                    "cust_fk" => $user,
                    "debit" => $amount,
                    "total" => $total - $amount,
                    "created_time" => date('Y-m-d H:i:s')
                );

                $data['query'] = $this->wallet_model->master_fun_insert("wallet_master", $data);
                $this->session->set_flashdata("success", array("$amount Debited From Account successfully."));
                redirect("wallet_master/account_history", "refresh");
            }
        } else {

            $data['customer'] = $this->wallet_model->master_fun_get_tbl_val("customer_master", array('status' => 1), array("id", "asc"));
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('update_wallet', $data);
            $this->load->view('footer');
        }
    }

    function export_csv() {
        $this->load->dbutil();
        $this->load->helper('file');
        $cust = $this->uri->segment(3);
        $this->load->helper('download');
        $delimiter = ",";
        $newline = "\r\n";
        $filename = "filename_you_wish.csv";
        $query = "SELECT  w.*,c.`full_name` FROM wallet_master w LEFT JOIN customer_master c ON c.id=w.`cust_fk` WHERE c.`status`=1";
        if (isset($cust)) {

            $query .= " AND cust_fk='$cust'";
        }
        $result = $this->db->query($query);
        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
        force_download($filename, $data);
    }

    function account_history() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        //print_r($data["login_data"]); die();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $data['success'] = $this->session->flashdata("success");

        $data['customer'] = $this->wallet_model->master_fun_get_tbl_val("customer_master", array('status' => 1), array("id", "asc"));
        $user = $this->input->get('user');
        $credit = $this->input->get('credit');
        $debit = $this->input->get('debit');
        $total = $this->input->get('total');
        $date = $this->input->get('date');
        $data['customerfk'] = $user;
        $data['credit'] = $credit;
        $data['debit'] = $debit;
        $data['date'] = $date;
        $data['total'] = $total;
        $data['query'] = $this->wallet_model->wallet_history($user, $credit, $debit, $total, $date);
        //$this->load->view('admin/state_list_view', $data);
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('account_history', $data);
        $this->load->view('footer');
    }

    function payment_history() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        //print_r($data["login_data"]); die();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $data['success'] = $this->session->flashdata("success");

        $data['customer'] = $this->wallet_model->master_fun_get_tbl_val("customer_master", array('status' => 1), array("id", "asc"));
        $cust_fk = $this->input->get('user');
        $data['customerfk'] = $cust_fk;
        $data['query'] = $this->wallet_model->payment_history($cust_fk);
        print_r($data['query']);
        die();
        //$this->load->view('admin/state_list_view', $data);
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('account_history', $data);
        $this->load->view('footer');
    }

}

?>
