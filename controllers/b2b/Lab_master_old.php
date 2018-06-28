<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Lab_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Logistic_model');
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

    function details() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $lab_fk = $data["lab_fk"] = $this->uri->segment(4);
        //echo $lab_fk; die();
        //$data["common_report"] = $this->job_model->master_fun_get_tbl_val("sample_report_master", array('status' => "1", "job_fk" => $data['job_details'][0]["id"]), array("id", "asc"));
        $data['test_list'] = $this->Logistic_model->master_fun_get_tbl_val("sample_test_master", array('status' => 1, "lab_fk" => $lab_fk), array("id", "desc"), array(5, 0));
        $data['credit_list'] = $this->Logistic_model->master_fun_get_tbl_val("sample_credit", array('status' => 1, "lab_fk" => $lab_fk), array("id", "desc"), array(5, 0));
        $data['lab_details'] = $this->Logistic_model->master_fun_get_tbl_val("collect_from", array('status' => 1, "id" => $lab_fk), array("id", "asc"));
        $this->load->view('b2b/header');
        $this->load->view('b2b/nav_logistic', $data);
        $this->load->view('b2b/lab_details', $data);
        $this->load->view('b2b/footer');
    }

    function dashboard() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->Logistic_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $this->load->view('b2b/header');
        $this->load->view('b2b/nav_logistic', $data);
        $this->load->view('b2b/logistic_dashboard', $data);
        $this->load->view('b2b/footer');
    }

    /* Sample list start */

    function sample_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->Logistic_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $search = $this->input->get('search');
        $data['name'] = $this->input->get('name');
        $data['barcode'] = $this->input->get('barcode');
        $data['date'] = $this->input->get('date');
        $data['from'] = $this->input->get('from');
        $cquery = "";
        if ($data['name'] != "" || $data['barcode'] != '' && $data['date'] != '' && $data['from'] != '') {
            $total_row = $this->Logistic_model->sample_list_num($data['name'], $data['barcode'], $data['date'], $data['from']);
            $config = array();
            $config["base_url"] = base_url() . "b2b/Logistic/sample_list/?search=$search&name=" . $data['name'] . "&barcode=" . $data['barcode'] . "&date=" . $data['date'] . "&from=" . $data['from'];
            $config["total_rows"] = count($total_row);
            $config["per_page"] = 50;
            $config["uri_segment"] = 4;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $config['page_query_string'] = TRUE;
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->Logistic_model->sample_list_num($data['name'], $data['barcode'], $data['date'], $data['from'], $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        } else {
            $totalRows = $this->Logistic_model->sample_list();
            $config = array();
            $config["base_url"] = base_url() . "b2b/Logistic/sample_list/";
            $config["total_rows"] = count($totalRows);
            $config["per_page"] = 50;
            $config["uri_segment"] = 4;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
            $data["query"] = $this->Logistic_model->sample_list($config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        }
        $cnt = 0;
        foreach ($data["query"] as $key) {
            $sample_track = $this->Logistic_model->get_val("SELECT logistic_sample_arrive.*,`phlebo_master`.`name` FROM logistic_sample_arrive INNER JOIN `phlebo_master` ON logistic_sample_arrive.`phlebo_fk`=`phlebo_master`.`id` WHERE `phlebo_master`.`status`='1' AND logistic_sample_arrive.`status`='1' AND logistic_sample_arrive.`barcode_fk`='" . $key["id"] . "' order by logistic_sample_arrive.id asc");
            $data["query"][$cnt]["sample_track"] = $sample_track;
            $data["query"][$cnt]["job_details"] = $this->Logistic_model->get_val("SELECT sample_job_master.`order_id`,`sample_report_master`.`original` FROM `sample_job_master` left JOIN `sample_report_master` ON `sample_job_master`.`id`=`sample_report_master`.`job_fk` WHERE `sample_job_master`.`status`!='0' AND `sample_job_master`.`barcode_fk`='" . $key["id"] . "'");
//$data["query"][$cnt]["report_details"] = $this->job_model->master_fun_get_tbl_val("sample_report_master", array('status' => "1", "job_fk" => $this->uri->segment(3)), array("id", "asc"));
            $cnt++;
        }
//echo "<pre>"; print_R($data["query"]); die();
        $url = "http://" . $_SERVER['HTTP_HOST'] . ":" . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
        $this->session->set_userdata("test_master_r", $url);
        $data['citys'] = $this->Logistic_model->master_fun_get_tbl_val("test_cities", array("status" => '1'), array("name", "asc"));
        $this->load->view('b2b/header');
        $this->load->view('b2b/nav_logistic', $data);
        $this->load->view('b2b/sample_list', $data);
        $this->load->view('b2b/footer');
    }

    function sample_delete($id) {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $update_data = array("status" => "0", "deleted_by" => $data["login_data"]["id"]);
        $update = $this->Logistic_model->master_fun_update("logistic_log", array("id" => $id), $update_data);
        if ($update) {
            $this->session->set_flashdata("success", array("Record successfully deleted."));
        }
        redirect("b2b/Logistic/sample_list");
    }

    /* Sample list end */

    function get_job_id() {
        $get_max_id = $this->job_model->get_val("SELECT order_id AS `max` FROM job_master ORDER BY id DESC LIMIT 0,1");
        $new_id = $get_max_id[0]['max'] + 1;
        $i = 0;
        $cnt = 0;
        while ($i == 0) {
            $new_id = $new_id + $cnt;
            $check_number = $this->job_model->get_val("SELECT count(order_id) AS `count` FROM job_master where order_id='" . $new_id . "'");
            if ($check_number[0]["count"] == 0) {
                $i = 1;
            }
            $cnt++;
        }
        return $new_id;
    }

    function lab_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->Logistic_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $state_serch = $this->input->get('state_search');
        $data["email"] = $this->input->get('email');
        $data['state_search'] = $state_serch;
        if ($state_serch != "" || $data["email"] != "") {
            $total_row = $this->Logistic_model->lab_num_rows($state_serch, $data["email"]);
            $config = array();
            $get = $_GET;
            unset($get['offset']);
            $config["base_url"] = base_url() . "b2b/logistic/lab_list?" . http_build_query($get);
            $config["total_rows"] = $total_row;
            $config["per_page"] = 50;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->Logistic_model->lab_data($state_serch, $data["email"], $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        } else {
            $total_row = $this->Logistic_model->lab_num_rows();
            $config["base_url"] = base_url() . "b2b/logistic/lab_list";
            $config["total_rows"] = $total_row;
            $config["per_page"] = 50;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->Logistic_model->srch_lab_list($config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        }
        $data["page"] = $page;
        $this->load->view('b2b/header');
        $this->load->view('b2b/nav_logistic', $data);
        $this->load->view('b2b/lab_list1', $data);
        $this->load->view('b2b/footer');
    }

    function lab_add() {

        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->Logistic_model->getUser($data["login_data"]["id"]);
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'State Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $name = $this->input->post('name');
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $data['query'] = $this->Logistic_model->master_fun_insert("collect_from", array("name" => $name, "email" => $email, "password" => $password));
            $this->session->set_flashdata("success", array("Lab successfully added."));
            redirect("Logistic/lab_list", "refresh");
        } else {
            $this->load->view('b2b/header');
            $this->load->view('b2b/nav_logistic', $data);
            $this->load->view('b2b/lab_add', $data);
            $this->load->view('b2b/footer');
        }
    }

    function lab_delete($id) {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $this->Logistic_model->master_fun_update("collect_from", array("id" => $id), array("status" => "0"));
        $this->session->set_flashdata("success", array("Lab successfully deleted."));
        redirect("b2b/Logistic/lab_list", "refresh");
    }

    function lab_edit($id) {

        if (!is_loggedin()) {
            redirect('login');
        }
        $data["id"] = $id;
        $data["login_data"] = logindata();
        $data["user"] = $this->Logistic_model->getUser($data["login_data"]["id"]);
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'State Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $name = $this->input->post('name');
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $this->Logistic_model->master_fun_update("collect_from", array("id" => $id), array("name" => $name, "email" => $email, "password" => $password));
            $this->session->set_flashdata("success", array("Lab successfully updated."));
            redirect("Logistic/lab_list", "refresh");
        } else {
            $data['query'] = $this->Logistic_model->master_fun_get_tbl_val("collect_from", array('id' => $id), array("id", "asc"));
            $this->load->view('b2b/header');
            $this->load->view('b2b/nav_logistic', $data);
            $this->load->view('b2b/lab_edit', $data);
            $this->load->view('b2b/footer');
        }
    }

    function get_city_test() {

        $city = $this->input->get_post("city");
        if ($city) {
            $data['test'] = $this->Logistic_model->get_val("SELECT `sample_test_master`.`id`,`sample_test_master`.`test_name`,`sample_test_master`.`test_name`,`sample_test_master`.`status`,`sample_test_city_price`.`price` FROM `sample_test_master` INNER JOIN `sample_test_city_price` ON `sample_test_master`.`id`=`sample_test_city_price`.`test_fk` WHERE `sample_test_master`.`status`='1' AND `sample_test_city_price`.`status`='1' AND `sample_test_city_price`.`city_fk`='" . $city . "'");
        } else {
            $data['test'] = $this->Logistic_model->get_val("SELECT `sample_test_master`.`id`,`sample_test_master`.`test_name`,`sample_test_master`.`test_name`,`sample_test_master`.`status`,`sample_test_city_price`.`price` FROM `sample_test_master` INNER JOIN `sample_test_city_price` ON `sample_test_master`.`id`=`sample_test_city_price`.`test_fk` WHERE `sample_test_master`.`status`='1' AND `sample_test_city_price`.`status`='1' AND `sample_test_city_price`.`city_fk`='1'");
        }
        $this->load->view("b2b/get_city_test_reg", $data);
    }

    function upload_report($cid = "") {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $files = $_FILES;
        $this->load->library('upload');
        $file_upload = array();
        if (!empty($_FILES['common_report']['name'])) {
            $desc = $this->input->post('desc_common_report');
            $type_common_report = $this->input->post('type_common_report');
            $_FILES['userfile']['name'] = $files['common_report']['name'];
            $_FILES['userfile']['type'] = $files['common_report']['type'];
            $_FILES['userfile']['tmp_name'] = $files['common_report']['tmp_name'];
            $_FILES['userfile']['error'] = $files['common_report']['error'];
            $_FILES['userfile']['size'] = $files['common_report']['size'];
            $config['upload_path'] = './upload/business_report/';
            $config['allowed_types'] = 'pdf';
            $config['file_name'] = time() . $files['common_report']['name'];
            $config['file_name'] = str_replace(' ', '_', $config['file_name']);
            $config['overwrite'] = FALSE;
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if (!$this->upload->do_upload()) {
                $error = $this->upload->display_errors();
                $this->session->set_flashdata("error", array($error));
                redirect('Logistic/details/' . $cid);
            } else {
                $file_upload[] = array("job_fk" => $cid, "report" => $config['file_name'], "original" => $_FILES['common_report']['name'], "test_fk" => "", "description" => $desc, "type" => $type_common_report);
            }
        }
        foreach ($file_upload as $f_key) {
            $job_details = $this->Logistic_model->master_fun_get_tbl_val("sample_job_master", array('barcode_fk' => $f_key["job_fk"]), array("id", "asc"));
            $row = $this->Logistic_model->master_num_rows("sample_report_master", array("job_fk" => $job_details[0]["id"], "status" => "1"));
            if ($row == 1) {
                $delete = $this->Logistic_model->master_fun_update("sample_report_master", array("job_fk" => $job_details[0]["id"], "type" => 'c'), array("report" => $f_key["report"], "original" => $f_key["original"], "description" => $desc));
            } else {
                $data['query'] = $this->Logistic_model->master_fun_insert("sample_report_master", array("job_fk" => $job_details[0]["id"], "report" => $f_key["report"], "original" => $f_key["original"], "description" => $desc, "type" => 'c'));
            }
        }
        $this->session->set_flashdata("success", array("Report Upload successfully."));
        redirect('b2b/Logistic/details/' . $cid);
    }

    function sendToThyrocare($id) {
        $this->load->model('job_model');
        $this->load->library('Thyrocare');
        $data['job_details'] = $this->job_model->master_fun_get_tbl_val("sample_job_master", array("barcode_fk" => $id, 'status' => 1), array("id", "asc"));
        $cnt = 0;

        foreach ($data['job_details'] as $key) {
            $job_test = $this->job_model->master_fun_get_tbl_val("sample_job_test", array("job_fk" => $key["id"], 'status' => 1), array("id", "asc"));
            $tst_name = array();
            foreach ($job_test as $tkey) {
//echo "SELECT sample_test_master.`id`,`sample_test_master`.`TEST_CODE`,`sample_test_master`.`test_name`,`sample_test_master`.`test_name`,`sample_test_master`.`status`,`sample_test_city_price`.`price` FROM `sample_test_master` INNER JOIN `sample_test_city_price` ON `sample_test_master`.`id`=`sample_test_city_price`.`test_fk` WHERE `sample_test_master`.`status`='1' AND `sample_test_city_price`.`status`='1' AND `sample_test_city_price`.`city_fk`='" . $data['job_details'][0]["test_city"] . "' AND `sample_test_master`.`id`='" . $tkey["test_fk"] . "'"; die();
                $test_info = $this->job_model->get_val("SELECT sample_test_master.`id`,`sample_test_master`.`test_name`,`sample_test_master`.`test_name`,`sample_test_master`.`status`,`sample_test_city_price`.`price` FROM `sample_test_master` INNER JOIN `sample_test_city_price` ON `sample_test_master`.`id`=`sample_test_city_price`.`test_fk` WHERE `sample_test_master`.`status`='1' AND `sample_test_city_price`.`status`='1' AND `sample_test_city_price`.`city_fk`='" . $data['job_details'][0]["test_city"] . "' AND `sample_test_master`.`id`='" . $tkey["test_fk"] . "'");
                $tkey["info"] = $test_info;
                $tst_name[] = $tkey;
            }
            $data['job_details'][0]["test_list"] = $tst_name;
            $job_packages = $this->job_model->master_fun_get_tbl_val("sample_book_package", array("job_fk" => $key["id"], 'status' => 1), array("id", "asc"));
            $data['job_details'][0]["package_list"] = $job_packages;
            $c_details = $this->job_model->master_fun_get_tbl_val("sample_customer_master", array("id" => $key["cust_fk"], 'status' => 1), array("id", "asc"));
            $data['job_details'][0]["cusomer_details"] = $c_details;
            $cnt++;
        }
        $data['barcode_detail'] = $this->job_model->get_val("SELECT `logistic_log`.*,`phlebo_master`.`name`,`phlebo_master`.`mobile`,`collect_from`.`name` AS `c_name` FROM `logistic_log` 
INNER JOIN `phlebo_master` ON `logistic_log`.`phlebo_fk`=`phlebo_master`.`id` 
INNER JOIN `collect_from` ON `logistic_log`.`collect_from`=`collect_from`.`id`
WHERE `phlebo_master`.`status`='1' AND `logistic_log`.`status`='1' and `logistic_log`.`id`='" . $id . "'");
        /*  echo "<pre>";
          print_R($data);
          print_R($c_details); */
        $c_details = $c_details[0];

        $this->load->library("util");
        $util = new Util;
        $age = $util->get_age($c_details["dob"]);
        $woe = array();
        $woe["PATIENT_NAME"] = $c_details["full_name"];
        $woe["AGE"] = $age[0];
        $woe["GENDER"] = ($c_details["gender"] == 'male') ? "M" : "F";
        $woe["EMAIL_ID"] = $c_details["email"];
        $woe["CONTACT_NO"] = $c_details["mobile"];
        $woe["SPECIMEN_COLLECTION_TIME"] = $data['job_details'][0]["date"];
        $woe["TOTAL_AMOUNT"] = $data['job_details'][0]["price"];
        $woe["AMOUNT_COLLECTED"] = $data['job_details'][0]["price"] - $data['job_details'][0]["payable_amount"];
        $woe["AMOUNT_DUE"] = $data['job_details'][0]["payable_amount"];

        $testname = array();
        foreach ($data['job_details'][0]["test_list"] as $test) {

            $testname[] = $test["info"]["0"]["test_name"];
        }
        $barcodelist = array(array(
                'BARCODE' => $data['barcode_detail'][0]["barcode"],
                'TESTS' => implode(",", $testname),
                'SAMPLE_TYPE' => 'SERUM',
                'Vial_Image' => '',
        ));
        $APIDATA = $this->Logistic_model->master_fun_get_tbl_val("thyrocare_api", array("id" => "1"), array("id" => "asc"));
        $thyrocare = new thyrocare();
        $thyrocare->loadkey($APIDATA[0]["api_key"]);
        $result = $thyrocare->saveWOE($woe, $barcodelist);
// $result='{"RES_ID":"RES0000","barcode_patient_id":"GUJ86185934039873312","message":"WORK ORDER ENTRY DONE SUCCESSFULLY","status":"SUCCESS"}';
        $resultObj = json_decode($result);
        if ($resultObj->status == "INVALID API KEY") {
            echo $result = $thyrocare->getKey($APIDATA[0]["login_code"], $APIDATA[0]["password"]);
            $resultObj = json_decode($result);

            if ($resultObj->RESPONSE == "SUCCESS") {
                $apikey = $resultObj->API_KEY;
                $thyrocare->loadkey($apikey);
                $this->Logistic_model->master_fun_update("thyrocare_api", array("id" => 1), array("api_key" => $apikey, "last_api_key_time" => date("Y-m-d H:i:s")));
            }
            $result = $thyrocare->saveWOE($woe, $barcodelist);
            $resultObj = json_decode($result);
        }

        if ($resultObj->status == "SUCCESS") {
            $this->Logistic_model->master_fun_update("logistic_log", array("id" => $id), array("thyrocare_job_id" => $resultObj->barcode_patient_id));
        } else {
            
        }
        echo $result;
    }

}
