<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Support_system extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('user_model');
        $this->load->model('support_system_model');
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

    function index() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $ticket = $this->input->get('ticket');
        $subject = $this->input->get('subject');
        $user = $this->input->get('user');
        $status = $this->input->get('status');
        if ($ticket != "" || $subject != "" || $user != "" || $status != '') {
            $srchdata = array("ticket" => $ticket, "subject" => $subject, "user" => $user, "status" => $status);
            $total_row = $this->support_system_model->num_row_srch($srchdata);
            $data['ticket'] = $ticket;
            $data['subject'] = $subject;
            $data['user'] = $user;
            $data['status'] = $status;
            $config = array();
            $get = $_GET;
            unset($get['offset']);
            $config["base_url"] = base_url() . "support_system/index?" . http_build_query($get);
            $config["total_rows"] = $total_row;
            $config["per_page"] = 50;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->support_system_model->row_srch($srchdata, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        } else {
            $srchdata = array();
            $data['ticket'] = '';
            $data['subject'] = '';
            $data['user'] = '';
            $data['status'] = '';
            $total_row = $this->support_system_model->num_row_srch($srchdata);
            $config["base_url"] = base_url() . "support_system/index";
            $config["total_rows"] = $total_row;
            $config["per_page"] = 50;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->support_system_model->row_srch($srchdata, $config["per_page"], $page);

            $data["links"] = $this->pagination->create_links();
        }
        $data['customer'] = $this->support_system_model->master_fun_get_tbl_val("customer_master", array('status' => 1), array("id", "asc"));
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('ticket_list', $data);
        $this->load->view('footer');
    }

    function view_details() {

        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $this->load->library('form_validation');
        $this->form_validation->set_rules('message', 'Message', 'trim|required');
        $ticketid = $this->uri->segment(3);
        $data['ticket'] = $ticketid;
        if ($this->form_validation->run() != FALSE) {
            $message = $this->input->post('message');
            $result = $this->support_system_model->master_fun_get_tbl_val("ticket_master", array("ticket" => $ticketid), array("id", "desc"));
            $tid = $result[0]['id'];
            $insert = $this->support_system_model->master_fun_insert("message_master", array("ticket_fk" => $tid, "message" => $message, "type" => 0, "created_date" => date('Y-m-d h:i:s')));
            $this->session->set_flashdata("success", array("Message Sent Successfully!"));
            redirect("support_system/view_details/" . $ticketid, "refresh");
        } else {
            $data['details'] = $this->support_system_model->ticket_details($ticketid);
            if ($data['details'] != null) {
                $this->support_system_model->updateRowWhere('ticket_master', array("ticket" => $ticketid), array("views" => '1'));
            }
            $data['success'] = $this->session->flashdata("success");
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('ticket_details', $data);
            $this->load->view('footer');
        }
    }

    function close() {
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
        $delete = $this->support_system_model->master_fun_update("ticket_master", $this->uri->segment('3'), $data);
        if ($delete) {
            $ses = array("Ticket Successfully Closed!");
            $this->session->set_userdata('success', $ses);
            redirect('support_system', 'refresh');
        }
    }

    function test() {
        $tests = $this->support_system_model->master_fun_get_tbl_val("test_master", array("status" => "1"), array("id", "asc"));
        for ($i = 0; $i < 639; $i++) {
            $this->support_system_model->master_fun_insert("test_master_city_price", array("test_fk" => $tests[$i]["id"], "city_fk" => "1", "price" => $tests[$i]["price"]));
        }
        for ($i = 639; $i < 1278; $i++) {
            $this->support_system_model->master_fun_insert("test_master_city_price", array("test_fk" => $tests[$i]["id"], "city_fk" => "2", "price" => $tests[$i]["price"]));
        }
        for ($i = 1278; $i < 1917; $i++) {
            $this->support_system_model->master_fun_insert("test_master_city_price", array("test_fk" => $tests[$i]["id"], "city_fk" => "3", "price" => $tests[$i]["price"]));
        }

        for ($i = 1917; $i < 2927; $i++) {
            $this->support_system_model->master_fun_insert("test_master_city_price", array("test_fk" => $tests[$i]["id"], "city_fk" => "4", "price" => $tests[$i]["price"]));
        }
        for ($i = 2927; $i < 3937; $i++) {
            $this->support_system_model->master_fun_insert("test_master_city_price", array("test_fk" => $tests[$i]["id"], "city_fk" => "5", "price" => $tests[$i]["price"]));
        }
        echo "Done";
    }

}

?>
