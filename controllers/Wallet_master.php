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
        $this->load->library('email');
        $data["login_data"] = logindata();
        $this->app_track();
    }

    function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }

    function wallet_update() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->helper("Email");
        $email_cnt = new Email;
        $data["login_data"] = logindata();
        $added_by = $data["login_data"]["id"];
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
                    "created_time" => date('Y-m-d H:i:s'),
                    "added_by"=>$added_by
                );

                $data['query'] = $this->wallet_model->master_fun_insert("wallet_master", $data);
                $total = $total + $amount;

                $customer = $this->wallet_model->master_fun_get_tbl_val("customer_master", array("status" => 1, "id" => $user), array("id", "desc"));
                $email = $customer[0]['email'];
                $username = $customer[0]['full_name'];
                $config['mailtype'] = 'html';
                $this->email->initialize($config);

                $message = '<div style="padding:0 4%;">
                    <h4><b>Dear </b>' . $username . '</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Credited in your Wallet. </p>
                       
<p style="color:#7e7e7e;font-size:13px;"> Rs. ' . $amount . ' Credited in your account. </p>
		<p style="color:#7e7e7e;font-size:13px;">Your Current Wallet Amount is Rs. ' . $total . '</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
                $message = $email_cnt->get_design($message);
                $this->email->to($email);
                $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
                $this->email->subject('Credited');
                $this->email->message($message);
                $this->email->send();






                $this->session->set_flashdata("success", array("Rs.$amount Credited in Account successfully."));
                redirect("wallet_master/account_history", "refresh");
            } else {

                $data = array(
                    "cust_fk" => $user,
                    "debit" => $amount,
                    "total" => $total - $amount,
                    "created_time" => date('Y-m-d H:i:s'),
                    "added_by"=>$added_by
                );

                $data['query'] = $this->wallet_model->master_fun_insert("wallet_master", $data);


                $total = $total - $amount;

                $customer = $this->wallet_model->master_fun_get_tbl_val("customer_master", array("status" => 1, "id" => $user), array("id", "desc"));
                $email = $customer[0]['email'];
                $username = $customer[0]['full_name'];
                $config['mailtype'] = 'html';
                $this->email->initialize($config);

                $message = '<div style="padding:0 4%;">
                    <h4><b>Dear </b>' . $username . '</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Debited in your Wallet. </p>
                       
<p style="color:#7e7e7e;font-size:13px;"> Rs. ' . $amount . ' Debited in your account. </p>
		<p style="color:#7e7e7e;font-size:13px;">Your Current Wallet Amount is Rs. ' . $total . '</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
                $message = $email_cnt->get_design($message);
                $this->email->to($email);
                $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
                $this->email->subject('Debited');
                $this->email->message($message);
                $this->email->send(); 

                $this->session->set_flashdata("success", array("Rs.$amount Debited From Account successfully."));
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
        //$data['query'] =  $this->wallet_model->wallet_history($user,$credit,$debit,$total,$date);

        if ($credit != "" || $total != "" || $user != "" || $debit != '' || $total != '' || $date != '') {
            $srchdata = array("credit" => $credit, "total" => $total, "user" => $user, "debit" => $debit, "total" => $total, "date" => $date);
            $total_row = $this->wallet_model->num_row_srch($srchdata);
            //echo $this->db->last_query(); die();
            $config = array();
            $get = $_GET;
            unset($get['offset']);
            $config["base_url"] = base_url() . "wallet_master/account_history?" . http_build_query($get);
            $config["total_rows"] = $total_row;
            $config["per_page"] = 50;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->wallet_model->row_srch($srchdata, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        } else {
            $srchdata = array();
            $data['ticket'] = '';
            $data['subject'] = '';
            $data['user'] = '';
            $data['status'] = '';
            $total_row = $this->wallet_model->num_row_srch($srchdata);
            //echo $this->db->last_query();
            $config["base_url"] = base_url() . "wallet_master/account_history";
            $config["total_rows"] = $total_row;
            $config["per_page"] = 50;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->wallet_model->row_srch($srchdata, $config["per_page"], $page);
            //echo $this->db->last_query();
            $data["links"] = $this->pagination->create_links();
        }
        //$this->load->view('admin/state_list_view', $data);

        $data['customer'] = $this->wallet_model->master_fun_get_tbl_val("customer_master", array('status' => 1), array("full_name", "asc"));
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
        $this->db->close();
        die();
        //$this->load->view('admin/state_list_view', $data);
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('account_history', $data);
        $this->load->view('footer');
    }

}

?>
