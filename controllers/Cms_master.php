<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cms_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('package_model');
        $this->load->model('user_model');
        $this->load->model('master_model');
        $this->load->library('form_validation');
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
        $userid = $data["login_data"]["id"];

        if ($this->session->userdata('success') != null) {
            $data['success'] = $this->session->userdata("success");
            $this->session->unset_userdata('success');
        }
        if ($this->session->userdata('unsuccess') != null) {
            $data['unsuccess'] = $this->session->userdata("unsuccess");
            $this->session->unset_userdata('unsuccess');
        }

        $data['query'] = $this->package_model->master_fun_get_tbl_val("patholab_home_master", array("status" => '1'), array("id", "asc"));
//	echo $data["edit_id"]; die();
        $data["login_data"] = logindata();
//$data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $userid = $data["login_data"]["id"];

        $this->form_validation->set_rules('email', 'Email Address', 'trim|required|xss_clean|valid_email');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('home_edit', $data);
            $this->load->view('footer');
        } else {
            $email = $this->input->post("email");
            $fb_link = $this->input->post("fb_link");
            $tw_link = $this->input->post("tw_link");
            $gmail_link = $this->input->post("gmail_link");
            $insta_link = $this->input->post("insta_link");
            $linkedin_link = $this->input->post("linkedin_link");
            $first_file_name = array();
            if ($_FILES["file"]["name"]) {
                $config['upload_path'] = './upload/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['file_name'] = time() . $_FILES["file"]["name"];
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload("file")) {
                    $error = array('error' => $this->upload->display_errors());
                    $this->load->view('header');
                    $this->load->view('nav', $data);
                    $this->load->view('home_edit', $error);
                    $this->load->view('footer');
                } else {
                    $data = array('upload_data' => $this->upload->data());
                    $file_name = $data["upload_data"]["file_name"];
                    $first_file_name["index_banner"] = $file_name;
                }
            }

            $data1 = array(
                "email" => $email,
                "linkedin_link" => $linkedin_link,
                "insta_link" => $insta_link,
                "gmail_link" => $gmail_link,
                "tw_link" => $tw_link,
                "fb_link" => $fb_link,
                "status" => '1'
            );
            $data2 = $data1 + $first_file_name;
//echo "<pre>"; print_r($data2); die();

            $update = $this->master_model->master_fun_update_new("patholab_home_master", array('status', '1'), $data2);
            if ($update) {
                $ses = array("home successfully updated!");
                $this->session->set_userdata('success', $ses);
                redirect('cms_master/index');
            }
        }
    }

    function test_city() {
        $city_val = $this->input->post("city_val");
        $action = $this->input->post("action");

        switch ($action) {
            case "add":
                $this->master_model->master_fun_insert("test_cities", array("name" => $city_val));
                $data = $this->master_model->master_fun_get_tbl_val("test_cities", array("status" => '1'), array("name", "asc"));
                $output = '<tbody><tr><th>City</th><th>Action</th></tr></tbody>';
                foreach ($data as $value) {
                    /* $output .= '<tr id="tr_' . $value["id"] . '"><td>' . $value["name"] . '</td><td><a href="javascript:void(0);" onclick="edit_city_price(\'' . $value["id"] . '\')">Edit</a>&nbsp;<a href="javascript:void(0);" onclick="delete_city_price(\'' . $value["id"] . '\')">Delete</a></td></tr>'; */
                    $output .= '<tr id="tr_' . $value["id"] . '"><td>' . $value["name"] . '</td><td><a href="javascript:void(0);" onclick="edit_city_price(\'' . $value["id"] . '\')"> <i class="fa fa-edit"></i> </a><a href="javascript:void(0);" onclick="delete_city_price(\'' . $value["id"] . '\')"> <i class="fa fa-trash-o"></i></a></td></tr>';
                }
                echo $output;
                break;
            case "edit":
                $id = $this->input->post("id");
                //$this->master_model->master_fun_update("test_cities",$id, array("status" => '0'));
                $data = $this->master_model->master_fun_get_tbl_val("test_cities", array("id" => $id, "status" => '1'), array("name", "asc"));
                $output = '';
                foreach ($data as $value) {
                    $output .= '<div class="form-group">
                    <label for="message-text" class="control-label">City name:</label>
                    <input type="text" id="edit_name" value="' . $value["name"] . '" class="form-control"/>
                        <input type="hidden" id="edit_id" value="' . $value["id"] . '"/>
                    <span style="color:red;" id="edit_name_error"></span>
                </div>';
                }
                echo $output;
                break;
            case "edit1":
                $id = $this->input->post("id");
                $this->master_model->master_fun_update("test_cities", $id, array("name" => $city_val));
                $data = $this->master_model->master_fun_get_tbl_val("test_cities", array("status" => '1'), array("name", "asc"));
                $output = '<tbody><tr><th>City</th><th>Action</th></tr></tbody>';
                if (!empty($data)) {
                    foreach ($data as $value) {
                        /* $output .= '<tr id="tr_' . $value["id"] . '"><td>' . $value["name"] . '</td><td><a href="javascript:void(0);" onclick="edit_city_price(\'' . $value["id"] . '\')">Edit</a>&nbsp;<a href="javascript:void(0);" onclick="delete_city_price(\'' . $value["id"] . '\')">Delete</a></td></tr>'; */
                        $output .= '<tr id="tr_' . $value["id"] . '"><td>' . $value["name"] . '</td><td><a href="javascript:void(0);" onclick="edit_city_price(\'' . $value["id"] . '\')"> <i class="fa fa-edit"></i> </a><a href="javascript:void(0);" onclick="delete_city_price(\'' . $value["id"] . '\')"> <i class="fa fa-trash-o"></i></a></td></tr>';
                    }
                } else {
                    $output .= '<tr><td colspan="3"><center>Data not available.</center></td></tr>';
                }
                echo $output;
                break;
            case "delete":
                $id = $this->input->post("id");
                $this->master_model->master_fun_update("test_cities", $id, array("status" => '0'));
                $data = $this->master_model->master_fun_get_tbl_val("test_cities", array("status" => '1'), array("name", "asc"));
                $output = '<tbody><tr><th>City</th><th>Action</th></tr></tbody>';
                if (!empty($data)) {
                    foreach ($data as $value) {
                        /* $output .= '<tr id="tr_' . $value["id"] . '"><td>' . $value["name"] . '</td><td><a href="javascript:void(0);" onclick="edit_city_price(\'' . $value["id"] . '\')">Edit</a>&nbsp;<a href="javascript:void(0);" onclick="delete_city_price(\'' . $value["id"] . '\')">Delete</a></td></tr>'; */
                        $output .= '<tr id="tr_' . $value["id"] . '"><td>' . $value["name"] . '</td><td><a href="javascript:void(0);" onclick="edit_city_price(\'' . $value["id"] . '\')"> <i class="fa fa-edit"> </i> </a> <a href="javascript:void(0);" onclick="delete_city_price(\'' . $value["id"] . '\')"> <i class="fa fa-trash-o"></i></a></td></tr>';
                    }
                } else {
                    $output .= '<tr><td colspan="3"><center>Data not available.</center></td></tr>';
                }
                echo $output;
                break;
            case "get":
                $data = $this->master_model->master_fun_get_tbl_val("test_cities", array("status" => '1'), array("name", "asc"));
                $output = '<tbody><tr><th>City</th><th>Action</th></tr></tbody>';
                if (!empty($data)) {
                    foreach ($data as $value) {
                        /* $output .= '<tr id="tr_' . $value["id"] . '"><td>' . $value["name"] . '</td><td><a href="javascript:void(0);" onclick="edit_city_price(\'' . $value["id"] . '\')">Edit</a>&nbsp;<a href="javascript:void(0);" onclick="delete_city_price(\'' . $value["id"] . '\')">Delete</a></td></tr>'; */
                        $output .= '<tr id="tr_' . $value["id"] . '"><td>' . $value["name"] . '</td><td><a href="javascript:void(0);" onclick="edit_city_price(\'' . $value["id"] . '\')"> <i class="fa fa-edit"></i> </a> <a href="javascript:void(0);" onclick="delete_city_price(\'' . $value["id"] . '\')"> <i class="fa fa-trash-o"></i></a></td></tr>';
                    }
                } else {
                    $output .= '<tr><td colspan="3"><center>Data not available.</center></td></tr>';
                }
                echo $output;
                break;
            default:
                $data = $this->master_model->master_fun_get_tbl_val("test_cities", array("status" => '1'), array("name", "asc"));
                break;
        }
    }

    function sms() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $userid = $data["login_data"]["id"];

        if ($this->session->userdata('success') != null) {
            $data['success'] = $this->session->userdata("success");
            $this->session->unset_userdata('success');
        }
        if ($this->session->userdata('unsuccess') != null) {
            $data['unsuccess'] = $this->session->userdata("unsuccess");
            $this->session->unset_userdata('unsuccess');
        }

        $data['query'] = $this->package_model->master_fun_get_tbl_val("sms_master", array("status" => '1'), array("id", "asc"));

        $data["login_data"] = logindata();
        $userid = $data["login_data"]["id"];
        $this->form_validation->set_rules('Sample_collection_Sms', 'Sample collection Sms', 'trim|required|xss_clean');
        $this->form_validation->set_rules('Span_report', 'Span report', 'trim|required|xss_clean');
        $this->form_validation->set_rules('Pending_Report', 'Pending Report', 'trim|required|xss_clean');
        $this->form_validation->set_rules('Completed_Report', 'Completed Report', 'trim|required|xss_clean');
        $this->form_validation->set_rules('Suggested_Test_Generated', 'Suggested Test Generated', 'trim|required|xss_clean');
        $this->form_validation->set_rules('OTP', 'Mobile OTP Message', 'trim|required|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('sms_edit', $data);
            $this->load->view('footer');
        } else {
            $sample_collection_sms = $this->input->post("Sample_collection_Sms");
            $data1 = array(
                "message" => $sample_collection_sms
            );
            $update = $this->master_model->master_fun_update_new("sms_master", array('title', 'Sample_collection_Sms'), $data1);

            $Span_report = $this->input->post("Span_report");
            $data1 = array(
                "message" => $Span_report
            );
            $update = $this->master_model->master_fun_update_new("sms_master", array('title', 'Span_report'), $data1);

            $Pending_Report = $this->input->post("Pending_Report");
            $data1 = array(
                "message" => $Pending_Report
            );
            $update = $this->master_model->master_fun_update_new("sms_master", array('title', 'Pending_Report'), $data1);

            $Completed_Report = $this->input->post("Completed_Report");
            $data1 = array(
                "message" => $Completed_Report
            );
            $update = $this->master_model->master_fun_update_new("sms_master", array('title', 'Completed_Report'), $data1);

            $Suggested_Test_Generated = $this->input->post("Suggested_Test_Generated");
            $data1 = array(
                "message" => $Suggested_Test_Generated
            );
            $update = $this->master_model->master_fun_update_new("sms_master", array('title', 'Suggested_Test_Generated'), $data1);

            $Suggested_Test_Generated = $this->input->post("OTP");
            $data1 = array(
                "message" => $Suggested_Test_Generated
            );
            $update = $this->master_model->master_fun_update_new("sms_master", array('title', 'OTP'), $data1);

            $test_book_without_login = $this->input->post("tbwlm");
            $data1 = array(
                "message" => $test_book_without_login
            );
            $update = $this->master_model->master_fun_update_new("sms_master", array('title', 'book_not_login'), $data1);

            $upload_presc = $this->input->post("upload_pres");
            $data1 = array(
                "message" => $upload_presc
            );
            $update = $this->master_model->master_fun_update_new("sms_master", array('title', 'upload_presc'), $data1);

            $book_login = $this->input->post("tblm");
            $data1 = array(
                "message" => $book_login
            );
            $update = $this->master_model->master_fun_update_new("sms_master", array('title', 'book_login'), $data1);
            $prescription_info = $this->input->post("prescription_info");
            $data1 = array(
                "message" => $prescription_info
            );
            $update = $this->master_model->master_fun_update_new("sms_master", array('title', 'prescription_info'), $data1);
            $test_info = $this->input->post("test_info");
            $data1 = array(
                "message" => $test_info
            );
            $update = $this->master_model->master_fun_update_new("sms_master", array('title', 'test_info'), $data1);
            if ($update) {
                $ses = array("SMS successfully updated!");
                $this->session->set_userdata('success', $ses);
                redirect('cms_master/sms');
            }
        }
    }

}

?>
