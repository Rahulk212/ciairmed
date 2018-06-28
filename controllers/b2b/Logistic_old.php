<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Logistic extends CI_Controller {

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

    function details() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->helper("Email");
        $email_cnt = new Email;
        $this->load->model('job_model');
        $data["login_data"] = logindata();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('abc', 'abc', 'trim|required');

        if ($this->form_validation->run() != FALSE) {
            $customer_name = $this->input->post("customer_name");
            $customer_mobile = $this->input->post("customer_mobile");
            $customer_email = $this->input->post("customer_email");
            if ($customer_email == '') {
                $customer_email = 'noreply@airmedlabs.com';
            }
            $customer_gender = $this->input->post("customer_gender");
            $dob = $this->input->post("dob");
            $address = $this->input->post("address");
            $note = $this->input->post("note");
            $discount = $this->input->post("discount");
            $payable = $this->input->post("payable");
            $test = $this->input->post("test");
            $order_id = $this->get_job_id();
            $date = date('Y-m-d H:i:s');

            $price = 0;
            foreach ($test as $key) {
                $tn = explode("-", $key);
                if ($tn[0] == 't') {
                    $result = $this->job_model->get_val("SELECT `sample_test_master`.`id`,`sample_test_master`.`test_name`,`sample_test_master`.`price`,`sample_test_master`.`status` FROM `sample_test_master` WHERE `sample_test_master`.`status`='1' AND `sample_test_master`.`id`='" . $tn[1] . "'");
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
                "barcode_fk" => $this->uri->segment(4),
                "customer_name" => $customer_name,
                "customer_mobile" => $customer_mobile,
                "customer_email" => $customer_email,
                "customer_gender" => $customer_gender,
                "customer_dob" => $dob,
                "customer_address" => $address,
                "price" => $price,
                "status" => '1',
                "discount" => $discount,
                "payable_amount" => $payable,
                "added_by" => $data["login_data"]["id"],
                "note" => $note
            );
            $check_barcode = $this->job_model->master_fun_get_tbl_val("sample_job_master", array("barcode_fk" => $this->uri->segment(4), "status" => 1), array("id", "asc"));
            if (count($check_barcode) == 0) {
                $insert = $this->job_model->master_fun_insert("sample_job_master", $data);
            } else {
                $insert = $check_barcode[0]["id"];
                $this->Logistic_model->master_fun_update("sample_job_master", array("id" => $insert), $data);
                $this->Logistic_model->master_fun_update("sample_job_test", array("job_fk" => $insert), array("status" => "0"));
                $this->Logistic_model->master_fun_update("sample_book_package", array("job_fk" => $insert), array("status" => "0"));
            }
            foreach ($test as $key) {
                $tn = explode("-", $key);
                if ($tn[0] == 't') {
                    $result = $this->job_model->get_val("SELECT `sample_test_master`.`id`,`sample_test_master`.`test_name`,`sample_test_master`.`price`,`sample_test_master`.`status` FROM `sample_test_master` WHERE `sample_test_master`.`status`='1' AND `sample_test_master`.`id`='" . $tn[1] . "'");
                    $this->job_model->master_fun_insert("sample_job_test", array('job_fk' => $insert, "test_fk" => $tn[1], "price" =>$result[0]["price"]));
                }
                if ($tn[0] == 'p') {
                    $this->job_model->master_fun_insert("sample_book_package", array("cust_fk" => $uid, "package_fk" => $tn[1], 'job_fk' => $insert, "status" => "1", "type" => "2"));
                }
            }
            $this->session->set_flashdata("success", array("Test successfully Booked."));
            redirect("b2b/Logistic/details/" . $this->uri->segment(4));
        } else {
            $data["id"] = $this->uri->segment(4);
            $data['barcode_detail'] = $this->job_model->get_val("SELECT `logistic_log`.*,`phlebo_master`.`name`,`phlebo_master`.`mobile`,`collect_from`.`name` AS `c_name` FROM `logistic_log` 
INNER JOIN `phlebo_master` ON `logistic_log`.`phlebo_fk`=`phlebo_master`.`id` 
INNER JOIN `collect_from` ON `logistic_log`.`collect_from`=`collect_from`.`id`
join sample_job_master on sample_job_master.barcode_fk = logistic_log.id
WHERE `phlebo_master`.`status`='1' AND `logistic_log`.`status`='1' and `logistic_log`.`id`='" . $data["id"] . "'");
            $data['job_details'] = $this->job_model->master_fun_get_tbl_val("sample_job_master", array("barcode_fk" => $this->uri->segment(4), 'status' => 1), array("id", "asc"));
            $cnt = 0;
            foreach ($data['job_details'] as $key) {
                $job_test = $this->job_model->master_fun_get_tbl_val("sample_job_test", array("job_fk" => $key["id"], 'status' => 1), array("id", "asc"));
                $tst_name = array();
                foreach ($job_test as $tkey) {
                    $test_info = $this->job_model->get_val("SELECT sample_test_master.`id`,`sample_test_master`.`test_name`,`sample_test_master`.`price`,`sample_test_master`.`status` FROM `sample_test_master` WHERE `sample_test_master`.`status`='1' AND `sample_test_master`.`id`='" . $tkey["test_fk"] . "'");
                    $tkey["info"] = $test_info;
                    $tst_name[] = $tkey;
                }
                $data['job_details'][0]["test_list"] = $tst_name;
                $job_packages = $this->job_model->master_fun_get_tbl_val("sample_book_package", array("job_fk" => $key["id"], 'status' => 1), array("id", "asc"));
                $data['job_details'][0]["package_list"] = $job_packages;
                $cnt++;
            }
            //echo "<pre>"; print_R($data['job_details']); die();
            $data['success'] = $this->session->userdata("success");
            if ($this->session->userdata("error") != '') {
                $data["error"] = $this->session->userdata("error");
                $this->session->unset_userdata("error");
            }
            $this->load->view('b2b/header');
            $this->load->view('b2b/nav_logistic', $data);
            $this->load->view('b2b/sample_register', $data);
            $this->load->view('b2b/footer');
        }
    }
    
    function sample_add() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->model('job_model');
        $data["login_data"] = logindata();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('abc', 'abc', 'trim|required');

        if ($this->form_validation->run() != FALSE) {
            $barcode = $this->input->post("barcode");
            $lab_id = $this->input->post("lab_id");
            $logistic_id = $this->input->post("logistic_id");
            $customer_name = $this->input->post("customer_name");
            $customer_mobile = $this->input->post("customer_mobile");
            $customer_email = $this->input->post("customer_email");
            if ($customer_email == '') {
                $customer_email = 'noreply@airmedlabs.com';
            }
            $customer_gender = $this->input->post("customer_gender");
            $dob = $this->input->post("dob");
            $address = $this->input->post("address");
            $note = $this->input->post("note");
            $discount = $this->input->post("discount");
            $payable = $this->input->post("payable");
            $test = $this->input->post("test");
            $order_id = $this->get_job_id();
            $date = date('Y-m-d H:i:s');
            //photo
            $files = $_FILES;
            $this->load->library('upload');
            $config['allowed_types'] = 'jpg|gif|png|jpeg';
            if ($files['upload_pic']['name'] != "") {
                $config['upload_path'] = './upload/barcode/';
                $config['file_name'] = time().$files['upload_pic']['name'];
                $this->upload->initialize($config);
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0755, TRUE);
                }
                if (!$this->upload->do_upload('upload_pic')) {
                    $error = $this->upload->display_errors();
                    $error = str_replace("<p>", "", $error);
                    $error = str_replace("</p>", "", $error);
                    $this->session->set_flashdata("error", $error);
                    redirect("b2b/logistic/sample_add", "refresh");
                } else {
                    $doc_data = $this->upload->data();
                    $photo = $doc_data['file_name'];
                }
            }
            //photo
            //$password = rand(11111111, 9999999);
            $c_data = array(
                "phlebo_fk" => $logistic_id,
                "barcode" => $barcode,
                "collect_from" => $lab_id,
                "status" => '1',
                "pic" => $photo,
                "created_by" => $data["login_data"]["id"],
                "createddate" => date('Y-m-d H:i:s'),
                "scan_date" => date('Y-m-d H:i:s')
            );
            $barcd = $this->job_model->master_fun_insert("logistic_log", $c_data);

            $price = 0;
            foreach ($test as $key) {
                $tn = explode("-", $key);
                if ($tn[0] == 't') {
                    $result = $this->job_model->get_val("SELECT `sample_test_master`.`id`,`sample_test_master`.`test_name`,`sample_test_master`.`price`,`sample_test_master`.`status` FROM `sample_test_master` WHERE `sample_test_master`.`status`='1' AND `sample_test_master`.`id`='" . $tn[1] . "'");
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
                "barcode_fk" => $barcd,
                "order_id" => $order_id,
                "customer_name" => $customer_name,
                "customer_mobile" => $customer_mobile,
                "customer_email" => $customer_email,
                "customer_gender" => $customer_gender,
                "customer_dob" => $dob,
                "customer_address" => $address,
                "price" => $price,
                "status" => '1',
                "discount" => $discount,
                "payable_amount" => $payable,
                "added_by" => $data["login_data"]["id"],
                "note" => $note,
                "date" => $date
            );
            $insert = $this->job_model->master_fun_insert("sample_job_master", $data);
            foreach ($test as $key) {
                $tn = explode("-", $key);
                if ($tn[0] == 't') {
                    $result = $this->job_model->get_val("SELECT `sample_test_master`.`id`,`sample_test_master`.`test_name`,`sample_test_master`.`price`,`sample_test_master`.`status` FROM `sample_test_master` WHERE `sample_test_master`.`status`='1' AND `sample_test_master`.`id`='" . $tn[1] . "'");
                    $this->job_model->master_fun_insert("sample_job_test", array('job_fk' => $insert, "test_fk" => $tn[1], "price" =>$result[0]["price"]));
                }
                if ($tn[0] == 'p') {
                    $this->job_model->master_fun_insert("sample_book_package", array("cust_fk" => $uid, "package_fk" => $tn[1], 'job_fk' => $insert, "status" => "1", "type" => "2"));
                }
            }
            $this->session->set_flashdata("success", array("Test successfully Booked."));
            redirect("b2b/Logistic/sample_list");
        } else {
            
            $data['lab_list'] = $this->job_model->master_fun_get_tbl_val("collect_from", array('status' => 1), array("id", "asc"));
            $data['phlebo_list'] = $this->job_model->master_fun_get_tbl_val("phlebo_master", array('status' => 1), array("id", "asc"));
            $this->load->view('b2b/header');
            $this->load->view('b2b/nav_logistic', $data);
            $this->load->view('b2b/sample_add', $data);
            $this->load->view('b2b/footer');
        }
    }

    /* Sample list end */

    function get_job_id() {
        $get_max_id = $this->job_model->get_val("SELECT order_id AS `max` FROM sample_job_master ORDER BY id DESC LIMIT 0,1");
        $new_id = $get_max_id[0]['max'] + 1;
        $i = 0;
        $cnt = 0;
        while ($i == 0) {
            $new_id = $new_id + $cnt;
            $check_number = $this->job_model->get_val("SELECT count(order_id) AS `count` FROM sample_job_master where order_id='" . $new_id . "'");
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
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('lab_name', 'Lab Name', 'trim|required');
        $this->form_validation->set_rules('person_name', 'Contact Person Name', 'trim|required');
        $this->form_validation->set_rules('mobile_number', 'Mobile Number', 'trim|required');
        $this->form_validation->set_rules('alternate_number', 'Alternate Number', 'trim|required');
        $this->form_validation->set_rules('address', 'Address', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $lab_name = $this->input->post('lab_name');
            $person_name = $this->input->post('person_name');
            $mobile_number = $this->input->post('mobile_number');
            $alternate_number = $this->input->post('alternate_number');
            $address = $this->input->post('address');
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $data1 = array(
                "name" => $lab_name,
                "contact_person_name" => $person_name,
                "mobile_number" => $mobile_number,
                "alternate_number" => $alternate_number,
                "address" => $address,
                "email" => $email,
                "password" => $password,
                "createddate" => date("Y-m-d H:i:s")
                    );
            $data['query'] = $this->Logistic_model->master_fun_insert("collect_from", $data1);
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
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('lab_name', 'Lab Name', 'trim|required');
        $this->form_validation->set_rules('person_name', 'Contact Person Name', 'trim|required');
        $this->form_validation->set_rules('mobile_number', 'Mobile Number', 'trim|required');
        $this->form_validation->set_rules('alternate_number', 'Alternate Number', 'trim|required');
        $this->form_validation->set_rules('address', 'Address', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $lab_name = $this->input->post('lab_name');
            $person_name = $this->input->post('person_name');
            $mobile_number = $this->input->post('mobile_number');
            $alternate_number = $this->input->post('alternate_number');
            $address = $this->input->post('address');
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $data1 = array(
                "name" => $lab_name,
                "contact_person_name" => $person_name,
                "mobile_number" => $mobile_number,
                "alternate_number" => $alternate_number,
                "address" => $address,
                "email" => $email,
                "password" => $password
                    );
            $this->Logistic_model->master_fun_update("collect_from", array("id" => $id), $data1);
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
    
    function get_lab_tests() {
        $lab = $this->input->get_post("lab");
        $data['test'] = $this->Logistic_model->get_val("SELECT `sample_test_master`.`id`,`sample_test_master`.`test_name`,`sample_test_master`.`price`,`sample_test_master`.`status` FROM `sample_test_master` WHERE `sample_test_master`.`status`='1' AND `sample_test_master`.`lab_fk`='" . $lab . "'");
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
                redirect('b2b/Logistic/details/' . $cid);
            } else {
                $file_upload[] = array("job_fk" => $cid, "report" => $config['file_name'], "original" => $_FILES['common_report']['name'], "test_fk" => "", "description" => $desc, "type" => $type_common_report);
            }
        }
        foreach ($file_upload as $f_key) {
                $delete = $this->Logistic_model->master_fun_update("sample_job_master", array("barcode_fk" => $cid), array("report" => $f_key["report"], "report_orignal" => $f_key["original"], "report_description" => $desc));
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
                $this->Logistic_model->master_fun_update("thyrocare_api", array("id" => 1), array("api_key" => $apikey,"last_api_key_time"=>date("Y-m-d H:i:s")));
        
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
