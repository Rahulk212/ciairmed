<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Attendance extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('user_model');
        $this->load->model('hrm/attendance_model');
        $this->load->helper('string');
        //echo current_url(); die();

        $data["login_data"] = is_hrmlogin();
    }

    function leave_types() {
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
            $total_row = $this->attendance_model->search_num($search);
            $config = array();
            $config["base_url"] = base_url() . "hrm/attendance/leave_types?" . http_build_query($get);
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
            $data['query'] = $this->attendance_model->list_search($search, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        } else {
            $search = "";
            $totalRows = $this->attendance_model->search_num($search);
            $config = array();
            $config["base_url"] = base_url() . "hrm/attendance/leave_types/";
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
            $data["query"] = $this->attendance_model->list_search($search, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        }
        $this->load->view('hrm/header');
        $this->load->view('hrm/nav', $data);
        $this->load->view('hrm/leave_type_list', $data);
        $this->load->view('hrm/footer');
    }

    function leave_type_add() {
        if (!is_hrmlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_hrmlogin();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $this->load->library('form_validation');
        $this->form_validation->set_rules('leave_type', 'Leave Type', 'trim|required');
        $this->form_validation->set_rules('number_leaves', 'Number of leaves in a year', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $leave_type = $this->input->post('leave_type');
            $number_leaves = $this->input->post('number_leaves');
            $data1 = array(
                "leave" => $leave_type,
                "number_of_leave" => $number_leaves,
                "created_by" => $data["login_data"]["id"],
                "created_date" => date("Y-m-d H:i:s")
            );
            $insert = $this->attendance_model->insert("hrm_leave_type", $data1);
            $this->session->set_flashdata("success", "Leave Type successfully added.");
            redirect("hrm/attendance/leave_types", "refresh");
        } else {
            $this->load->view('hrm/header', $data);
            $this->load->view('hrm/nav', $data);
            $this->load->view('hrm/leave_type_add', $data);
            $this->load->view('hrm/footer', $data);
        }
    }

    function leave_type_delete($cid) {
        if (!is_hrmlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_hrmlogin();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['query'] = $this->attendance_model->update("hrm_leave_type", array("id" => $cid), array("status" => '0'));
        $this->session->set_flashdata("success", "Leave Type successfully deleted.");
        redirect("hrm/attendance/leave_types", "refresh");
    }

    function leave_type_edit($cid) {
        if (!is_hrmlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_hrmlogin();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['cid'] = $cid;
        $data['query'] = $this->attendance_model->get_one("hrm_leave_type", array("id" => $cid, "status" => 1));
        $this->load->library('form_validation');
        $this->form_validation->set_rules('leave_type', 'Leave Type', 'trim|required');
        $this->form_validation->set_rules('number_leaves', 'Number of leaves in a year', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $leave_type = $this->input->post('leave_type');
            $number_leaves = $this->input->post('number_leaves');
            $data1 = array(
                "leave" => $leave_type,
                "number_of_leave" => $number_leaves,
                "updated_by" => $data["login_data"]["id"],
                "updated_date" => date("Y-m-d H:i:s")
            );
            $update = $this->attendance_model->update("hrm_leave_type", array("id" => $cid), $data1);
            $this->session->set_flashdata("success", "Leave Type successfully updated.");
            redirect("hrm/attendance/leave_types", "refresh");
        } else {
            $this->load->view('hrm/header', $data);
            $this->load->view('hrm/nav', $data);
            $this->load->view('hrm/leave_type_edit', $data);
            $this->load->view('hrm/footer', $data);
        }
    }

    function mark_attendance() {
        if (!is_hrmlogin()) {
            redirect('login');
        }
        $data['today'] = date("d/m/Y");
        $data["login_data"] = is_hrmlogin();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $check_attend = $this->attendance_model->get_all('hrm_attendance', array("attend_date" => $data['today']));
        if(empty($check_attend)) {
            $employee = $this->attendance_model->get_all('hrm_employees', array("status" => 1,"active" => 1));
            foreach ($employee as $get) {
                $data1 = array(
                    "employee_id" => $get->employee_id,
                    "attend_status" => '1',
                    "attend_date" => $data['today'],
                    "created_by" => $data["login_data"]["id"],
                    "created_date" => date("Y-m-d H:i:s")
                );
                $insert = $this->attendance_model->insert("hrm_attendance", $data1);
            }
        }
        $data['employee_list'] = $this->attendance_model->employee_attendance($data['today']);
        $data['type_list'] = $this->attendance_model->get_all('hrm_leave_type', array("status" => 1));
        $this->load->view('hrm/header', $data);
        $this->load->view('hrm/nav', $data);
        $this->load->view('hrm/employee_attendance', $data);
        $this->load->view('hrm/footer', $data);
    }
    
    function do_attendance() {
        if (!is_hrmlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_hrmlogin();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $attendance_date = $this->input->post('attendance_date');
        $employee = $this->attendance_model->get_all('hrm_employees', array("status" => 1,"active" => 1));
        foreach ($employee as $get) {
            $employee_fk = $this->input->post('employee_fk_'.$get->id);
            $attend_status = $this->input->post('attend_status_'.$get->id);
            if($attend_status != 1) {
                $attend_status = 2;
            }
            $type = $this->input->post('type_'.$get->id);
            $reason = $this->input->post('reason_'.$get->id);
            $data1 = array(
                "employee_id" => $employee_fk,
                "attend_status" => $attend_status,
                "type_of_leave" => $type,
                "reason" => $reason,
                "attend_date" => $attendance_date,
                "updated_by" => $data["login_data"]["id"],
                "updated_date" => date("Y-m-d H:i:s")
            );
            $update = $this->attendance_model->update("hrm_attendance", array("attend_date" => $attendance_date,"employee_id" =>$employee_fk), $data1);
        }
        $this->session->set_flashdata("success", "Attendance successfully updated.");
        redirect('hrm/attendance/mark_attendance');
    }
    
    function another_date() {
        if (!is_hrmlogin()) {
            redirect('login');
        }
        $data['today'] = $this->input->post('chose_date');
        $data["login_data"] = is_hrmlogin();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $check_attend = $this->attendance_model->get_all('hrm_attendance', array("attend_date" => $data['today']));
        if(empty($check_attend)) {
            $employee = $this->attendance_model->get_all('hrm_employees', array("status" => 1,"active" => 1));
            foreach ($employee as $get) {
                $data1 = array(
                    "employee_id" => $get->employee_id,
                    "attend_status" => '1',
                    "attend_date" => $data['today'],
                    "created_by" => $data["login_data"]["id"],
                    "created_date" => date("Y-m-d H:i:s")
                );
                $insert = $this->attendance_model->insert("hrm_attendance", $data1);
            }
        }
        $data['employee_list'] = $this->attendance_model->employee_attendance($data['today']);
        $data['type_list'] = $this->attendance_model->get_all('hrm_leave_type', array("status" => 1));
        $this->load->view('hrm/header', $data);
        $this->load->view('hrm/nav', $data);
        $this->load->view('hrm/employee_attendance', $data);
        $this->load->view('hrm/footer', $data);
    }

}

?>