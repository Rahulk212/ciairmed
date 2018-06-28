<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Just_dial_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('user_model');
        $this->load->model('Just_dial_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        //echo current_url(); die();

        $data["login_data"] = logindata();
    }

    function index() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        //print_r($data["login_data"]); die();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);


        $name = $this->input->get('name');
        $mobile = $this->input->get('mobile');
        $email = $this->input->get('email');
        $type = $this->input->get('type');
        $gender = $this->input->get("gender");
        if ($name != "" || $email != "" || $mobile != "" || $type != '' || $gender != '') {
            $srchdata = array("name" => $name, "email" => $email, "mobile" => $mobile, "type" => $type, "gender" => $gender);
            $data['name'] = $name;
            $data['mobile'] = $mobile;
            $data['email'] = $email;
            $data['type'] = $type;
            $data["gender"] = $gender;
            $totalRows = $this->Just_dial_model->phlebocount_list($srchdata);
            $config = array();
            $config["base_url"] = base_url() . "Just_dial_master/index/";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 10;
            $config["uri_segment"] = 3;
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $page = $data["page"] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $data["query"] = $this->Just_dial_model->phlebolist_list($srchdata, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        } else {
            $srchdata = array();
            $totalRows = $this->Just_dial_model->phlebocount_list($srchdata);
            $config = array();
            $config["base_url"] = base_url() . "Just_dial_master/index/";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 10;
            $config["uri_segment"] = 3;
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $page = $data["page"] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $data["query"] = $this->Just_dial_model->phlebolist_list($srchdata, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        }
        //$data['test_city'] = $this->Just_dial_model->master_fun_get_tbl_val("test_cities", array("status" => '1'), array("name", "asc"));
        $data['success'] = $this->session->flashdata("success");
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('just_dial_data_list', $data);
        $this->load->view('footer');
    }

    function details($id = null) {
        if (!empty($id)) {
            $data["query"] = $this->Just_dial_model->master_fun_get_tbl_val("just_dial_data", array("id" => $id), array("id", "desc"));
            //print_r($data["query"]);
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('just_dial_details', $data);
            $this->load->view('footer');
        } else {
            redirect("Just_dial_master/index");
        }
    }

    function delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->user_model->master_fun_update("just_dial_data", array("id", $cid), array("status" => "0"));

        $this->session->set_flashdata("success", array("Data successfully deleted."));
        redirect("Just_dial_master/index", "refresh");
    }

    function export_csv() {

        $name = $this->input->get('name');
        $mobile = $this->input->get('mobile');
        $email = $this->input->get('email');
        $type = $this->input->get('type');
        $gender = $this->input->get("gender");
        $srchdata = array("name" => $name, "email" => $email, "mobile" => $mobile, "type" => $type, "gender" => $gender);
        $result = $this->Just_dial_model->phlebolist_list_csv($srchdata);

        //print_r($result); die();
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"Just_Dial_Data-" . date('d-M-Y') . ".csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array(
            "Id",
            "Name",
            "Email",
            "Phone",
            "Gender",
            "Date Of Birth",
            "Age",
            "Address",
            "Note",
            "City",
            "Search Data Time",
            "Created Date",
            "Requirement",
            "Branch Info.",
            "From"
        ));

        foreach ($result as $key) {
            fputcsv($handle, array(
                $key["id"],
                $key["name"],
                $key["email"],
                $key["phone"],
                $key["gender"],
                $key["date_of_birth"],
                $key["age"],
                $key["address"],
                $key["note"],
                $key["city"],
                $key["search_date_time"],
                $key["created_date"],
                $key["requirement"],
                $key["branch_info"],
                $key["from"]
            ));
        }
        fclose($handle);
        exit;
    }

}

?>