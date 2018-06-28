<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Department extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('user_model');
        $this->load->model('hrm/department_model');
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
            $total_row = $this->department_model->search_num($search);
            $config = array();
            $config["base_url"] = base_url() . "hrm/department/index?" . http_build_query($get);
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
            $data['query'] = $this->department_model->list_search($search, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        } else {
            $search = "";
            $totalRows = $this->department_model->search_num($search);
            $config = array();
            $config["base_url"] = base_url() . "hrm/department/index/";
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
            $data["query"] = $this->department_model->list_search($search, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        }
        $data['designation_list'] = $this->department_model->get_all('hrm_designation', array("status" => 1));
        $this->load->view('hrm/header');
        $this->load->view('hrm/nav', $data);
        $this->load->view('hrm/department_list', $data);
        $this->load->view('hrm/footer');
    }
    function add() {
        if (!is_hrmlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_hrmlogin();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $this->load->view('hrm/header', $data);
        $this->load->view('hrm/nav', $data);
        $this->load->view('hrm/department_add', $data);
        $this->load->view('hrm/footer', $data);
    }
    function add_all() {
        if (!is_hrmlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_hrmlogin();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $department_name = $this->input->post('department_name');
        $insert = $this->department_model->insert("hrm_department", array("name" => $department_name,"created_by" => $data["login_data"]["id"],"created_date" => date("Y-m-d H:i:s")));
        $count_designation = $this->input->post('count_designation');
        for ($j = 1; $j <= $count_designation; $j++) {
            $designation = $this->input->post('designation_' . $j);
            if ($designation != "") {
                $data1 = array(
                    "department_fk" => $insert,
                    "name" => $designation,
                    "created_by" => $data["login_data"]["id"],
                    "created_date" => date("Y-m-d H:i:s")
                );
                $value = $this->department_model->insert("hrm_designation", $data1);
            }
        }
        $this->session->set_flashdata("success", "Department successfully added.");
        redirect("hrm/department", "refresh");
    }

    function delete($cid) {
        if (!is_hrmlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_hrmlogin();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['query'] = $this->department_model->update("hrm_department", array("id" => $cid), array("status" => '0'));
        $data['query'] = $this->department_model->update("hrm_designation", array("department_fk" => $cid), array("status" => '0'));
        $this->session->set_flashdata("success", "Department successfully deleted.");
        redirect("hrm/department", "refresh");
    }

    function edit($cid) {
        if (!is_hrmlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_hrmlogin();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['cid'] = $cid;
        $data['query'] = $this->department_model->get_one("hrm_department", array("id" => $cid,"status" => 1));
        $data['designation_list'] = $this->department_model->get_all('hrm_designation', array("status" => 1,"department_fk" => $cid));
        $this->load->view('hrm/header', $data);
        $this->load->view('hrm/nav', $data);
        $this->load->view('hrm/department_edit', $data);
        $this->load->view('hrm/footer', $data);
    }
    function edit_all($cid) {
        if (!is_hrmlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_hrmlogin();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $department_name = $this->input->post('department_name');
        $update = $this->department_model->update("hrm_department", array("id" => $cid), array("name" => $department_name,"updated_by" => $data["login_data"]["id"],"updated_date" => date("Y-m-d H:i:s")));
        $count_designation = $this->input->post('count_designation');
        $edit_count_designation = $this->input->post('edit_count_designation');
        for ($j = 1; $j <= $edit_count_designation; $j++) {
            $edit_designation_id = $this->input->post('edit_designation_id_' . $j);
            $edit_designation = $this->input->post('edit_designation_' . $j);
            if ($edit_designation != "") {
                $data1 = array(
                    "department_fk" => $cid,
                    "name" => $edit_designation,
                    "updated_by" => $data["login_data"]["id"],
                    "updated_date" => date("Y-m-d H:i:s")
                );
                $value = $this->department_model->update("hrm_designation", array("id" => $edit_designation_id), $data1);
            }
        }
        for ($j = 1; $j <= $count_designation; $j++) {
            $designation = $this->input->post('designation_' . $j);
            if ($designation != "") {
                $data1 = array(
                    "department_fk" => $cid,
                    "name" => $designation,
                    "created_by" => $data["login_data"]["id"],
                    "created_date" => date("Y-m-d H:i:s")
                );
                $value = $this->department_model->insert("hrm_designation", $data1);
            }
        }
        $this->session->set_flashdata("success", "Department successfully updated.");
        redirect("hrm/department", "refresh");
    }
    function remove_designation() {
        if (!is_hrmlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_hrmlogin();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $designation_id = $this->input->post('designation_id');
        $update = $this->department_model->update("hrm_designation", array("id" => $designation_id), array("status" => 0));
        if($update) {
            return 1;
        }
    }

}

?>
