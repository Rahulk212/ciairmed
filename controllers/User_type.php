<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User_type extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('user_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        //echo current_url(); die();
        $menu = $this->user_model->master_fun_get_tbl_val("menu_master", array('status' => 1), array("id", "asc"));
        //print_r($menu); die();
        $this->data['menu'] = $menu;
        $path = uri_string();
        $data["login_data"] = logindata();
        $this->data['usertype'] = $data["login_data"]["type"];
        $data['query'] = $this->user_model->set_user_permission($this->data['usertype'], $path);
        $this->data['user_permission'] = $data['query'];
        $this->app_track();
    }

    function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }

    function user_type_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $data['query'] = $this->user_model->master_fun_get_tbl_val("user_type_master", array('status' => 1), array("id", "asc"));
        //$this->load->view('admin/state_list_view', $data);
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('user_type_list', $data);
        $this->load->view('footer');
    }

    function user_type_add() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $name = $this->input->post('name');
            $data['query'] = $this->user_model->master_fun_insert("user_type_master", array("type" => $name, "status" => "1"));
            $this->session->set_flashdata("success", array("Exterior successfully added."));
            redirect("user_type/user_type_list", "refresh");
        } else {
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('user_type_add', $data);
            $this->load->view('footer');
        }
    }

    function user_type_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->user_model->master_fun_update("user_type_master", array("id", $cid), array("status" => "0"));
        $this->session->set_flashdata("success", array("Exterior successfully deleted."));
        redirect("user_type/user_type_list", "refresh");
    }

    function user_type_edit() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["cid"] = $this->uri->segment('3');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $name = $this->input->post('name');
            $data['query'] = $this->user_model->master_fun_update("user_type_master", array("id", $data["cid"]), array("type" => $name));
            $this->session->set_flashdata("success", array("House successfully updated."));
            redirect("user_type/user_type_list", "refresh");
        } else {
            $data['query'] = $this->user_model->master_fun_get_tbl_val("user_type_master", array("id" => $data["cid"]), array("id", "desc"));
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('user_type_edit', $data);
            $this->load->view('footer');
        }
    }

    function permission_update() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $menu = $this->user_model->master_fun_get_tbl_val("menu_master", array('status' => 1), array("id", "asc"));
        $type = $this->input->post('rid');
        foreach ($menu as $key) {
            $add = $this->input->post('add_' . $key['id']);
            $update = $this->input->post('update_' . $key['id']);
            $delete = $this->input->post('delete_' . $key['id']);
            $read = $this->input->post('read_' . $key['id']);
            if ($add == "") {
                $add = 0;
            }
            if ($update == "") {
                $update = 0;
            }
            if ($delete == "") {
                $delete = 0;
            }
            if ($read == "") {
                $read = 0;
            }
            $data = array(
                "add" => $add,
                "update" => $update,
                "delete" => $delete,
                "read" => $read,
                "menu_fk" => $key['id'],
                "type_fk" => $type,
            );
            $row = $this->user_model->check_permission($key['id'], $type);
            if ($row >= "1") {
                $cond = array("menu_fk" => $key['id'], "type_fk" => $type);
                //	print_r($cond); die();
                $data['query'] = $this->user_model->permission_update("user_permission", $cond, $data);
            } else {
                $data['query'] = $this->user_model->master_fun_insert("user_permission", $data);
            }
        }
        $this->session->set_flashdata("success", array("House successfully updated."));
        redirect("user_type/user_type_permission", "refresh");
    }

    function user_type_permission() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $data['rid'] = $this->input->post('role');
        if (!empty($data['rid'])) {
            $data['permission'] = $this->user_model->get_user_permission($data['rid']);
        }
        $data['query'] = $this->user_model->master_fun_get_tbl_val("user_type_master", array('status' => 1), array("type", "asc"));
        $data['menu'] = $this->user_model->master_fun_get_tbl_val("menu_master", array('status' => 1), array("id", "asc"));
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('user_type_role_list', $data);
        $this->load->view('footer');
    }

}

?>
