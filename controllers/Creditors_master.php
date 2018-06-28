<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Creditors_master extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('user_model');
        $this->load->model('creditors_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('pushserver');
        $this->load->library('email');
        $this->load->helper('string');
        //echo current_url(); die();

        $data["login_data"] = logindata();
    }

    function emailcheck($email) {
        if (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email)) {
            $emailErr = "Invalid email format";
            $this->form_validation->set_message('emailcheck', 'Please enter a valid email address.');
            return false;
        } else {

            return TRUE;
        }
    }

    function check_email($email) {
        $result = $this->creditors_model->check_email($email, 'creditors_master');
        if ($result == '0') {
            return TRUE;
        } else {
            $this->form_validation->set_message('check_email', 'Email address already exist.');
            return false;
        }
    }

    public function index() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $this->load->library('pagination');
        //print_r($data['success']);
        //$user = $this->input->get('user');
        $name = $this->input->get('name');
        $email = $this->input->get('email');
        $mobile = $this->input->get('mobile');




        //$data['user1'] = $user;

        $data['name'] = $name;
        $data['email'] = $email;
        $data['mobile'] = $mobile;


        if ($name != "" || $email != "" || $mobile != "") {

            $total_row = $this->creditors_model->creditors_row_num($name, $email, $mobile);
            $config = array();
            $get = $_GET;
            unset($get['offset']);
            $config["base_url"] = base_url() . "creditors_master/index?" . http_build_query($get);
            $config["total_rows"] = $total_row;
            $config["per_page"] = 10;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->creditors_model->creditors_srch($name, $email, $mobile, $config["per_page"], $page);
            //echo "<pre>";print_r($data['query']); die();
            $data["links"] = $this->pagination->create_links();
            $data["page"] = $page;
        } else {

            $total_row = $this->creditors_model->creditors_row_num($name, $email, $mobile);
            $config = array();
            $get = $_GET;
            unset($get['offset']);
            $config["base_url"] = base_url() . "creditors_master/index";
            $config["total_rows"] = $total_row;
            $config["per_page"] = 10;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->creditors_model->creditors_srch($name, $email, $mobile, $config["per_page"], $page);

            $data["page"] = $page;
            $data["links"] = $this->pagination->create_links();
        }

        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('creditors_list', $data);
        $this->load->view('footer');
    }

    public function add() {

        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);


        $this->load->library('form_validation');

        $this->form_validation->set_rules('name', 'First Name', 'trim|required');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required');
        $this->form_validation->set_rules('branch[]', 'Branch', 'trim|required');
        $email = $this->input->post('email');
        if ($email != "") {
            $this->form_validation->set_rules('email', 'Email', 'trim|callback_emailcheck|callback_check_email');
        }
        if ($this->form_validation->run() != FALSE) {
            $post['name'] = $this->input->post('name');
            $post['email'] = $this->input->post('email');
            $post['mobile'] = $this->input->post('mobile');
            $post['address'] = $this->input->post('address');
            $post['created_date'] = date('Y-m-d H:i:s');
            $post['added_by'] = $data["login_data"]["id"];
            $post['status'] = '1';
            $insert = $this->creditors_model->insert("creditors_master", $post);
            if ($insert) {
                $branch = $this->input->post('branch');
                foreach ($branch as $br) {
                    $this->creditors_model->insert("creditors_branch", array("creditors_fk" => $insert, "branch_fk" => $br, "created_by" => $data["login_data"]['id']));
                }
            }
            $this->session->set_flashdata("success", "Creditors successfully added.");
            redirect("creditors_master", "refresh");
        } else {
            $data['branch_list'] = $this->creditors_model->get_all("branch_master", array("status" => 1));
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('creditors_add', $data);
            $this->load->view('footer');
        }
    }

    function delete($uid) {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $data['query'] = $this->creditors_model->update("creditors_master", array("id" => $uid), array("status" => "0", 'deleted_by' => $data["login_data"]["id"]));
        $this->session->set_flashdata("success", "Creditors successfully deleted.");
        redirect("creditors_master", "refresh");
    }

    public function edit($uid) {

        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["uid"] = $uid;
        $data['query'] = $this->creditors_model->get_one("creditors_master", array("id" => $uid));
        $data['branch_all'] = $this->creditors_model->get_all("creditors_branch", array("creditors_fk" => $data['query']->id, "status" => 1));
        $data['branchs'] = array();
        foreach ($data['branch_all'] as $branch) {
            array_push($data['branchs'], $branch->branch_fk);
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'First Name', 'trim|required');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required');
        $this->form_validation->set_rules('branch[]', 'Branch', 'trim|required');
        $email = $this->input->post('email');
        if ($email != $data['query']->email) {
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_emailcheck|callback_check_email');
        }
        if ($this->form_validation->run() != FALSE) {
            $post['name'] = $this->input->post('name');
            $post['email'] = $this->input->post('email');
            $post['mobile'] = $this->input->post('mobile');
            $post['address'] = $this->input->post('address');
            $post['created_date'] = date('Y-m-d H:i:s');
            $post['update_by'] = $data["login_data"]["id"];
            $post['status'] = '1';
            $update = $this->creditors_model->update("creditors_master", array('id' => $uid), $post);
            $delete = $this->creditors_model->delete_record("creditors_branch", array("status" => 1,"creditors_fk" =>$uid));
            if ($update) {
                $branch = $this->input->post('branch');
                foreach ($branch as $br) {
                    $this->creditors_model->insert("creditors_branch", array("creditors_fk" => $uid, "branch_fk" => $br, "updated_by" => $data["login_data"]['id'],"updated_date" => date('Y-m-d H:i:s')));
                }
            }
            $this->session->set_flashdata("success", "Creditors successfully added.");

            redirect("creditors_master", "refresh");
        } else {
            $data['branch_list'] = $this->creditors_model->get_all("branch_master", array("status" => 1));
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('creditors_edit', $data);
            $this->load->view('footer');
        }
    }

}

?>
