<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Lab extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Lab_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('pushserver');
        $this->load->library('email');
        $data["login_data"] = loginlab();
        $this->load->helper('string');
        $this->app_track();
    }

    function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }

    function dashboard() {
        if (!is_labloggedin()) {
            redirect('login');
        }
        $data["login_data"] = loginlab();
        $data["user"] = $this->Lab_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $this->load->view('header');
        $this->load->view('nav_lab', $data);
        $this->load->view('logistic_dashboard', $data);
        $this->load->view('footer_lab');
    }

    /* Sample list start */

    function sample_list() {
        if (!is_labloggedin()) {
            redirect('login');
        }
        $data["login_data"] = loginlab();
        $data["user"] = $this->Lab_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");

        $search = $this->input->get('search');
        $data['name'] = $this->input->get('name');
        $data['barcode'] = $this->input->get('barcode');
        $data['date'] = $this->input->get('date');
        $data['from'] = $this->input->get('from');
        $cquery = "";
        if ($data['name'] != "" || $data['barcode'] != '' && $data['date'] != '' && $data['from'] != '') {
            $total_row = $this->Lab_model->sample_list_num($data["login_data"]["id"], $data['name'], $data['barcode'], $data['date'], $data['from']);
            $config = array();
            $config["base_url"] = base_url() . "Logistic/sample_list/?search=$search&name=" . $data['name'] . "&barcode=" . $data['barcode'] . "&date=" . $data['date'] . "&from=" . $data['from'];
            $config["total_rows"] = count($total_row);
            $config["per_page"] = 50;
            $config["uri_segment"] = 3;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $config['page_query_string'] = TRUE;
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->Lab_model->sample_list_num($data["login_data"]["id"], $data['name'], $data['barcode'], $data['date'], $data['from'], $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        } else {
            $totalRows = $this->Lab_model->sample_list($data["login_data"]["id"]);
            $config = array();
            $config["base_url"] = base_url() . "Logistic/sample_list/";
            $config["total_rows"] = count($totalRows);
            $config["per_page"] = 50;
            $config["uri_segment"] = 3;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $data["query"] = $this->Lab_model->sample_list($data["login_data"]["id"], $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        }
        $cnt = 0;
        foreach ($data["query"] as $key) {
            $sample_track = $this->Lab_model->get_val("SELECT logistic_sample_arrive.*,`phlebo_master`.`name` FROM logistic_sample_arrive INNER JOIN `phlebo_master` ON logistic_sample_arrive.`phlebo_fk`=`phlebo_master`.`id` WHERE `phlebo_master`.`status`='1' AND logistic_sample_arrive.`status`='1' AND logistic_sample_arrive.`barcode_fk`='" . $key["id"] . "' order by logistic_sample_arrive.id asc");
            $data["query"][$cnt]["sample_track"] = $sample_track;
            $data["query"][$cnt]["job_details"] = $this->Lab_model->get_val("SELECT sample_job_master.`order_id`,`sample_report_master`.`original` FROM `sample_job_master` left JOIN `sample_report_master` ON `sample_job_master`.`id`=`sample_report_master`.`job_fk` WHERE `sample_job_master`.`status`!='0' AND `sample_job_master`.`barcode_fk`='" . $key["id"] . "'");
            //$data["query"][$cnt]["report_details"] = $this->job_model->master_fun_get_tbl_val("sample_report_master", array('status' => "1", "job_fk" => $this->uri->segment(3)), array("id", "asc"));
            $cnt++;
        }
        //echo "<pre>"; print_R($data["query"]); die();
        $url = "http://" . $_SERVER['HTTP_HOST'] . ":" . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
        $this->session->set_userdata("test_master_r", $url);
        $data['citys'] = $this->Lab_model->master_fun_get_tbl_val("test_cities", array("status" => '1'), array("name", "asc"));
        $this->load->view('header');
        $this->load->view('nav_lab', $data);
        $this->load->view('lab_sample_list', $data);
        $this->load->view('footer_lab');
    }

    function sample_delete($id) {
        if (!is_labloggedin()) {
            redirect('login');
        }
        $data["login_data"] = loginlab();
        $update_data = array("status" => "0", "deleted_by" => $data["login_data"]["id"]);
        $update = $this->Lab_model->master_fun_update("logistic_log", array("id" => $id), $update_data);
        if ($update) {
            $this->session->set_flashdata("success", array("Record successfully deleted."));
        }
        redirect("Logistic/sample_list");
    }

    function details() {
        if (!is_labloggedin()) {
            redirect('login');
        }
        $data["login_data"] = loginlab();
        $this->load->helper("Email");
        $email_cnt = new Email;
        $this->load->model('job_model');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('abc', 'abc', 'trim|required');

        if ($this->form_validation->run() != FALSE) {
            //echo "<pre>"; print_R($_POST); die(); 
            $customer = $this->input->post("customer");
            $name = $this->input->post("name");
            $phone = $this->input->post("phone");
            $email = $this->input->post("email");
            if ($email == '') {
                $email = 'noreply@airmedlabs.com';
            }
            $gender = $this->input->post("gender");
            $dob = $this->input->post("dob");
//            $test_city = $this->input->post("test_city");
            $test_city = 4;
            $address = $this->input->post("address");
            $note = $this->input->post("note");
            $discount = $this->input->post("discount");
            $payable = $this->input->post("payable");
            $test = $this->input->post("test");
            $referral_by = $this->input->post("referral_by");
            $source = $this->input->post("source");
            $order_id = $this->get_job_id();
            $date = date('Y-m-d H:i:s');

            $password = rand(11111111, 9999999);
            $c_data = array(
                "full_name" => $name,
                "gender" => $gender,
                "email" => $email,
                "mobile" => $phone,
                "address" => $address,
                "password" => $password,
                "dob" => $dob
            );
            $customer = $this->job_model->master_fun_insert("sample_customer_master", $c_data);
            $config['mailtype'] = 'html';
            $this->email->initialize($config);

            $price = 0;
            foreach ($test as $key) {
                $tn = explode("-", $key);
                if ($tn[0] == 't') {
                    $result = $this->job_model->get_val("SELECT `sample_test_master`.`id`,`sample_test_master`.`test_name`,`sample_test_master`.`test_name`,`sample_test_master`.`status`,`sample_test_city_price`.`price` FROM `sample_test_master` INNER JOIN `sample_test_city_price` ON `sample_test_master`.`id`=`sample_test_city_price`.`test_fk` WHERE `sample_test_master`.`status`='1' AND `sample_test_city_price`.`status`='1' AND `sample_test_city_price`.`city_fk`='" . $test_city . "' AND `sample_test_master`.`id`='" . $tn[1] . "'");
                    $price += $result[0]["price"];
                    $test_package_name[] = $result[0]["test_name"];
                }
                if ($tn[0] == 'p') {
                    //print_r(array('package_fk' => $tn[1], "city_fk" => $test_city)); die();
                    $query = $this->db->get_where('package_master_city_price', array('package_fk' => $tn[1], "city_fk" => $test_city));
                    $result = $query->result();
                    $query1 = $this->db->get_where('package_master', array('id' => $tn[1]));
                    $result1 = $query1->result();
                    $price += $result[0]->d_price;
                    $test_package_name[] = $result1[0]->title;
                }
            }

            $data = array(
                "barcode_fk" => $this->uri->segment(3),
                "order_id" => $order_id,
                "cust_fk" => $customer,
                "doctor" => $referral_by,
                "other_reference" => $source,
                "date" => $date,
                "price" => $price,
                "status" => '1',
                "payment_type" => "Cash On Delivery",
                "discount" => $discount,
                "payable_amount" => $payable,
                "test_city" => $test_city,
                "note" => $this->input->post('note'),
                "added_by" => $data["login_data"]["id"],
                "note" => $note
            );
//            print_r($data);
//            die();
            $check_barcode = $this->job_model->master_fun_get_tbl_val("sample_job_master", array("barcode_fk" => $this->uri->segment(3), "status" => 1), array("id", "asc"));
            if (count($check_barcode) == 0) {
                $insert = $this->job_model->master_fun_insert("sample_job_master", $data);
            } else {
                $insert = $check_barcode[0]["id"];
                $this->Lab_model->master_fun_update("sample_job_master", array("id" => $insert), $data);
                $this->Lab_model->master_fun_update("sample_job_test", array("job_fk" => $insert), array("status" => "0"));
                $this->Lab_model->master_fun_update("sample_book_package", array("job_fk" => $insert), array("status" => "0"));
            }
            foreach ($test as $key) {
                $tn = explode("-", $key);
                if ($tn[0] == 't') {
                    $this->job_model->master_fun_insert("sample_job_test", array('job_fk' => $insert, "test_fk" => $tn[1]));
                }
                if ($tn[0] == 'p') {
                    $this->job_model->master_fun_insert("sample_book_package", array("cust_fk" => $uid, "package_fk" => $tn[1], 'job_fk' => $insert, "status" => "1", "type" => "2"));
                }
            }
            $this->session->set_flashdata("success", array("Test successfully Booked."));
            redirect("Logistic/details/" . $this->uri->segment(3));
        } else {
            $data["id"] = $this->uri->segment(3);
            $data['barcode_detail'] = $this->job_model->get_val("SELECT `logistic_log`.*,`phlebo_master`.`name`,`phlebo_master`.`mobile`,`collect_from`.`name` AS `c_name` FROM `logistic_log` 
INNER JOIN `phlebo_master` ON `logistic_log`.`phlebo_fk`=`phlebo_master`.`id` 
INNER JOIN `collect_from` ON `logistic_log`.`collect_from`=`collect_from`.`id`
WHERE `phlebo_master`.`status`='1' AND `logistic_log`.`status`='1' and `logistic_log`.`id`='" . $data["id"] . "'");
            //print_r($data['barcode_detail']); die();
            $data['job_details'] = $this->job_model->master_fun_get_tbl_val("sample_job_master", array("barcode_fk" => $this->uri->segment(3), 'status' => 1), array("id", "asc"));
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
            //echo "<pre>"; print_R($data['job_details']); die();
            $data['success'] = $this->session->userdata("success");
            if ($this->session->userdata("error") != '') {
                $data["error"] = $this->session->userdata("error");
                $this->session->unset_userdata("error");
            }
            $data["common_report"] = $this->job_model->master_fun_get_tbl_val("sample_report_master", array('status' => "1", "job_fk" => $data['job_details'][0]["id"]), array("id", "asc"));
            $data['test_cities'] = $this->job_model->master_fun_get_tbl_val("test_cities", array('status' => 1), array("id", "asc"));
            $this->load->view('header');
            $this->load->view('nav_lab', $data);
            $this->load->view('lab_sample_register', $data);
            $this->load->view('footer_lab');
        }
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
        if (!is_labloggedin()) {
            redirect('login');
        }
        $data["login_data"] = loginlab();
        $data["user"] = $this->Lab_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $state_serch = $this->input->get('state_search');
        $data['state_search'] = $state_serch;
        if ($state_serch != "") {
            $total_row = $this->Lab_model->lab_num_rows($state_serch);
            $config = array();
            $get = $_GET;
            unset($get['offset']);
            $config["base_url"] = base_url() . "logistic/lab_list?" . http_build_query($get);
            $config["total_rows"] = $total_row;
            $config["per_page"] = 50;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->Lab_model->lab_data($state_serch, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        } else {
            $total_row = $this->Lab_model->lab_num_rows();
            $config["base_url"] = base_url() . "logistic/lab_list";
            $config["total_rows"] = $total_row;
            $config["per_page"] = 50;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->Lab_model->srch_lab_list($config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        }
        $data["page"] = $page;
        $this->load->view('header');
        $this->load->view('nav_lab', $data);
        $this->load->view('lab_list1', $data);
        $this->load->view('footer_lab');
    }

    function lab_add() {
        if (!is_labloggedin()) {
            redirect('login');
        }
        $data["login_data"] = loginlab();
        $data["user"] = $this->Lab_model->getUser($data["login_data"]["id"]);
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'State Name', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $name = $this->input->post('name');
            $data['query'] = $this->Lab_model->master_fun_insert("collect_from", array("name" => $name));
            $this->session->set_flashdata("success", array("Lab successfully added."));
            redirect("Logistic/lab_list", "refresh");
        } else {
            $this->load->view('header');
            $this->load->view('nav_lab', $data);
            $this->load->view('lab_add', $data);
            $this->load->view('footer_lab');
        }
    }

    function lab_delete($id) {
        if (!is_labloggedin()) {
            redirect('login');
        }
        $data["login_data"] = loginlab();
        $this->Lab_model->master_fun_update("collect_from", array("id" => $id), array("status" => "0"));
        $this->session->set_flashdata("success", array("Lab successfully deleted."));
        redirect("Logistic/lab_list", "refresh");
    }

    function lab_edit($id) {
        if (!is_labloggedin()) {
            redirect('login');
        }
        $data["login_data"] = loginlab();
        $data["id"] = $id;
        $data["user"] = $this->Lab_model->getUser($data["login_data"]["id"]);
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'State Name', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $name = $this->input->post('name');
            $this->Lab_model->master_fun_update("collect_from", array("id" => $id), array("name" => $name));
            $this->session->set_flashdata("success", array("Lab successfully updated."));
            redirect("Logistic/lab_list", "refresh");
        } else {
            $data['query'] = $this->Lab_model->master_fun_get_tbl_val("collect_from", array('id' => $id), array("id", "asc"));
            $this->load->view('header');
            $this->load->view('nav_lab', $data);
            $this->load->view('lab_edit', $data);
            $this->load->view('footer_lab');
        }
    }

    function get_city_test() {

        $city = $this->input->get_post("city");
        if ($city) {
            $data['test'] = $this->Lab_model->get_val("SELECT `sample_test_master`.`id`,`sample_test_master`.`test_name`,`sample_test_master`.`test_name`,`sample_test_master`.`status`,`sample_test_city_price`.`price` FROM `sample_test_master` INNER JOIN `sample_test_city_price` ON `sample_test_master`.`id`=`sample_test_city_price`.`test_fk` WHERE `sample_test_master`.`status`='1' AND `sample_test_city_price`.`status`='1' AND `sample_test_city_price`.`city_fk`='" . $city . "'");
        } else {
            $data['test'] = $this->Lab_model->get_val("SELECT `sample_test_master`.`id`,`sample_test_master`.`test_name`,`sample_test_master`.`test_name`,`sample_test_master`.`status`,`sample_test_city_price`.`price` FROM `sample_test_master` INNER JOIN `sample_test_city_price` ON `sample_test_master`.`id`=`sample_test_city_price`.`test_fk` WHERE `sample_test_master`.`status`='1' AND `sample_test_city_price`.`status`='1' AND `sample_test_city_price`.`city_fk`='1'");
        }
        $this->load->view("get_city_test_reg", $data);
    }

    function upload_report($cid = "") {
        if (!is_labloggedin()) {
            redirect('login');
        }
        $data["login_data"] = loginlab();
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
            $job_details = $this->Lab_model->master_fun_get_tbl_val("sample_job_master", array('barcode_fk' => $f_key["job_fk"]), array("id", "asc"));
            $row = $this->Lab_model->master_num_rows("sample_report_master", array("job_fk" => $job_details[0]["id"], "status" => "1"));
            if ($row == 1) {
                $delete = $this->Lab_model->master_fun_update("sample_report_master", array("job_fk" => $job_details[0]["id"], "type" => 'c'), array("report" => $f_key["report"], "original" => $f_key["original"], "description" => $desc));
            } else {
                $data['query'] = $this->Lab_model->master_fun_insert("sample_report_master", array("job_fk" => $job_details[0]["id"], "report" => $f_key["report"], "original" => $f_key["original"], "description" => $desc, "type" => 'c'));
            }
        }
        $this->session->set_flashdata("success", array("Report Upload successfully."));
        redirect('Logistic/details/' . $cid);
    }

}
