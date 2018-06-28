<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Lab extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('b2b/Lab_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('pushserver');
        $this->load->library('email');
        $data["login_data"] = is_lablogin();
        if (!is_lablogin()) {
            redirect('b2b/login1');
        }
        $this->load->helper('string');
        $this->app_track();
    }

    function job_list() {
        echo "OK1";
    }

    function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }

    function dashboard() {
        if (!is_lablogin()) {
            redirect('b2b/login1');
        }
        $data["login_data"] = is_lablogin();
        $data["user"] = $this->Lab_model->getUser($data["login_data"]["id"]);
        $data["total_job"] = $this->Lab_model->master_fun_get_tbl_val('logistic_log', array('status' => '1', 'collect_from' => $data["login_data"]["id"]), array("id", "desc"));
        $data["today_job"] = $this->Lab_model->master_fun_get_tbl_val('logistic_log', array('status' => '1', 'collect_from' => $data["login_data"]["id"], "scan_date >" => date("Y-m-d") . " 00:00:00"), array("id", "desc"));
        $data["credit_job"] = $this->Lab_model->master_fun_get_tbl_val('sample_credit', array('status' => '1', 'lab_fk' => $data["login_data"]["id"]), array("id", "desc"));
        $data['success'] = $this->session->flashdata("success");
        $this->load->view('b2b/header');
        $this->load->view('b2b/nav_lab', $data);
        $this->load->view('b2b/lab_dashboard', $data);
        $this->load->view('b2b/footer');
    }

    /* Sample list start */

    function sample_list() {

        $data["login_data"] = is_lablogin();
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
            $config["base_url"] = base_url() . "b2b/Lab/sample_list/?search=$search&name=" . $data['name'] . "&barcode=" . $data['barcode'] . "&date=" . $data['date'] . "&from=" . $data['from'];
            $config["total_rows"] = count($total_row);
            $config["per_page"] = 100;
            $config["uri_segment"] = 4;
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
            $data["query"] = $this->Lab_model->sample_list($data["login_data"]["id"], $config["per_page"], $page);
            //echo $this->db->last_query())

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
        $this->load->view('b2b/header');
        $this->load->view('b2b/nav_lab', $data);
        $this->load->view('b2b/lab_sample_list', $data);
        $this->load->view('b2b/footer');
    }

    function details() {
        //die("OK"); 
        $data["login_data"] = is_lablogin();
        //print_r($data["login_data"]); die();
        $this->load->model('job_model');
        $this->load->library("util");
        $util = new Util;
        $data["id"] = $this->uri->segment(4);
        $data['barcode_detail'] = $this->job_model->get_val("SELECT `logistic_log`.*,`phlebo_master`.`name`,`phlebo_master`.`mobile`,`collect_from`.`name` AS `c_name` FROM `logistic_log` 
left JOIN `phlebo_master` ON `logistic_log`.`phlebo_fk`=`phlebo_master`.`id`  and `phlebo_master`.`status`='1' 
INNER JOIN `collect_from` ON `logistic_log`.`collect_from`=`collect_from`.`id`
join sample_job_master on sample_job_master.barcode_fk = logistic_log.id
WHERE  `logistic_log`.`status`='1' and `logistic_log`.`id`='" . $data["id"] . "'");
        $data['job_details'] = $this->job_model->master_fun_get_tbl_val("sample_job_master", array("barcode_fk" => $this->uri->segment(4), 'status' => 1), array("id", "asc"));
        $age = $util->get_age($data['job_details'][0]["customer_dob"]);
        if ($age[0] != 0) {
            $data['job_details'][0]["age"] = $age[0];
            $data['job_details'][0]["age_type"] = 'Y';
        }
        if ($age[0] == 0 && $age[1] != 0) {
            $data['job_details'][0]["age"] = $age[1];
            $data['job_details'][0]["age_type"] = 'M';
        }
        if ($age[0] == 0 && $age[1] == 0 && $age[2] != 0) {
            $data['job_details'][0]["age"] = $age[2];
            $data['job_details'][0]["age_type"] = 'D';
        }

        if ($age[0] == 0 && $age[1] == 0 && $age[2] == 0) {
            $data['job_details'][0]["age"] = '0';
            $data['job_details'][0]["age_type"] = 'D';
        }
        $cnt = 0;
        foreach ($data['job_details'] as $key) {
            $job_test = $this->job_model->master_fun_get_tbl_val("sample_job_test", array("job_fk" => $key["id"], 'status' => 1), array("id", "asc"));
            $tst_name = array();
            foreach ($job_test as $tkey) {
                //echo "SELECT sample_test_master.`id`,`sample_test_master`.`test_name`,`sample_test_master`.`price`,`sample_test_master`.`status` FROM `sample_test_master` WHERE `sample_test_master`.`status`='1' AND `sample_test_master`.`id`='" . $tkey["test_fk"] . "'"; die();
                $test_info = $this->job_model->get_val("SELECT sample_test_master.`id`,`sample_test_master`.`test_name`,`sample_test_city_price`.`price`,`sample_test_city_price`.`b2b_price`,`sample_test_city_price`.`special_price`,`sample_test_master`.`status` FROM `sample_test_master` INNER JOIN `sample_test_city_price` ON `sample_test_city_price`.`test_fk`=sample_test_master.id WHERE `sample_test_master`.`status`='1' AND `sample_test_city_price`.`id`='" . $tkey["test_fk"] . "'");
                $tkey["info"] = $test_info;
                $tst_name[] = $tkey;
            }
            $data['job_details'][0]["test_list"] = $tst_name;
            $job_packages = $this->job_model->master_fun_get_tbl_val("sample_book_package", array("job_fk" => $key["id"], 'status' => 1), array("id", "asc"));
            $data['job_details'][0]["package_list"] = $job_packages;
            $cnt++;
        }
//        $data['barcode_detail'] = $this->job_model->get_val("SELECT `logistic_log`.*,`phlebo_master`.`name`,`phlebo_master`.`mobile`,`collect_from`.`name` AS `c_name` FROM `logistic_log` 
//INNER JOIN `phlebo_master` ON `logistic_log`.`phlebo_fk`=`phlebo_master`.`id` 
//INNER JOIN `collect_from` ON `logistic_log`.`collect_from`=`collect_from`.`id`
//WHERE `phlebo_master`.`status`='1' AND `logistic_log`.`status`='1' and `logistic_log`.`id`='" . $data["id"] . "'");
//        //print_r($data['barcode_detail']); die();
//        $data['job_details'] = $this->job_model->master_fun_get_tbl_val("sample_job_master", array("barcode_fk" => $this->uri->segment(3), 'status' => 1), array("id", "asc"));
//        $cnt = 0;
//        foreach ($data['job_details'] as $key) {
//            $job_test = $this->job_model->master_fun_get_tbl_val("sample_job_test", array("job_fk" => $key["id"], 'status' => 1), array("id", "asc"));
//            $tst_name = array();
//            foreach ($job_test as $tkey) {
//                //echo "SELECT sample_test_master.`id`,`sample_test_master`.`TEST_CODE`,`sample_test_master`.`test_name`,`sample_test_master`.`test_name`,`sample_test_master`.`status`,`sample_test_city_price`.`price` FROM `sample_test_master` INNER JOIN `sample_test_city_price` ON `sample_test_master`.`id`=`sample_test_city_price`.`test_fk` WHERE `sample_test_master`.`status`='1' AND `sample_test_city_price`.`status`='1' AND `sample_test_city_price`.`city_fk`='" . $data['job_details'][0]["test_city"] . "' AND `sample_test_master`.`id`='" . $tkey["test_fk"] . "'"; die();
//                $test_info = $this->job_model->get_val("SELECT sample_test_master.`id`,`sample_test_master`.`test_name`,`sample_test_master`.`test_name`,`sample_test_master`.`status`,`sample_test_city_price`.`price` FROM `sample_test_master` INNER JOIN `sample_test_city_price` ON `sample_test_master`.`id`=`sample_test_city_price`.`test_fk` WHERE `sample_test_master`.`status`='1' AND `sample_test_city_price`.`status`='1' AND `sample_test_city_price`.`city_fk`='" . $data['job_details'][0]["test_city"] . "' AND `sample_test_master`.`id`='" . $tkey["test_fk"] . "'");
//                $tkey["info"] = $test_info;
//                $tst_name[] = $tkey;
//            }
//            $data['job_details'][0]["test_list"] = $tst_name;
//            $job_packages = $this->job_model->master_fun_get_tbl_val("sample_book_package", array("job_fk" => $key["id"], 'status' => 1), array("id", "asc"));
//            $data['job_details'][0]["package_list"] = $job_packages;
//            $c_details = $this->job_model->master_fun_get_tbl_val("sample_customer_master", array("id" => $key["cust_fk"], 'status' => 1), array("id", "asc"));
//            $data['job_details'][0]["cusomer_details"] = $c_details;
//            $cnt++;
//        }
//        //echo "<pre>"; print_R($data['job_details']); die();
//        $data['success'] = $this->session->userdata("success");
//        if ($this->session->userdata("error") != '') {
//            $data["error"] = $this->session->userdata("error");
//            $this->session->unset_userdata("error"); 
//        }
//        $data["common_report"] = $this->job_model->master_fun_get_tbl_val("sample_report_master", array('status' => "1", "job_fk" => $data['job_details'][0]["id"]), array("id", "asc"));
//        $data['test_cities'] = $this->job_model->master_fun_get_tbl_val("test_cities", array('status' => 1), array("id", "asc"));
        $data['jobspdf'] = $this->job_model->master_fun_get_tbl_val("b2b_jobspdf", array("job_fk" => $data["id"],"approve"=>'1','status' =>'1'), array("id", "asc"));

        $this->load->view('b2b/header');
        $this->load->view('b2b/nav_lab', $data);
        $this->load->view('b2b/sample_report', $data);
        $this->load->view('b2b/footer');
    }

    function payment() {
        $data["login_data"] = is_lablogin();
        $data["id"] = $data["login_data"]["id"];
        if (!empty($data["id"])) {
            $data["lab_details"] = $this->Lab_model->master_fun_get_tbl_val("collect_from", array('status' => "1", "id" => $data["id"]), array("id", "desc"));
            $data["sample_credit"] = $this->Lab_model->master_fun_get_tbl_val("sample_credit", array('status' => "1", "lab_fk" => $data["id"]), array("id", "desc"));
            //print_r($data["sample_credit"]); die();
            $this->load->view('b2b/header');
            $this->load->view('b2b/nav_lab', $data);
            $this->load->view('b2b/lab_amount_details1', $data);
            $this->load->view('b2b/footer');
        } else {
            redirect("b2b/Amount_manage/lab_list");
        }
    }

    function sample_add() {
        if (!is_lablogin()) {
            redirect('login');
        }
        //print_r($_POST); die();
        $this->load->model('job_model');
        $data["login_data"] = is_lablogin();
        $data['lab_id'] = $data["login_data"]["id"];
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
            $total_amount = $this->input->post("total_amount");
            $test = $this->input->post("test");
            $order_id = $this->get_job_id();
            $date = date('Y-m-d H:i:s');
            //photo
            $files = $_FILES;
            $this->load->library('upload');
            $config['allowed_types'] = 'jpg|gif|png|jpeg';
            if ($files['upload_pic']['name'] != "") {
                $config['upload_path'] = './upload/barcode/';
                $config['file_name'] = time() . $files['upload_pic']['name'];
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
					
                    /* $result = $this->job_model->get_val("SELECT `sample_test_master`.`id`,`sample_test_master`.`test_name`,`sample_test_master`.`price`,`sample_test_master`.`status` FROM `sample_test_master` WHERE `sample_test_master`.`status`='1' AND `sample_test_master`.`id`='" . $tn[1] . "'");
                    $price += $result[0]["price"];
                    $test_package_name[] = $result[0]["test_name"]; */
					$result = $this->job_model->get_val("SELECT price,b2b_price FROM sample_test_city_price where status='1' and id='".$tn[1]."';");
					
                   if($result[0]["b2b_price"] != ""){ $price += $result[0]["b2b_price"]; }else{ $price +=$result[0]["price"]; }
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
                "price" => $total_amount,
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
                    /* $result = $this->job_model->get_val("SELECT `sample_test_master`.`id`,`sample_test_master`.`test_name`,`sample_test_master`.`price`,`sample_test_master`.`status` FROM `sample_test_master` WHERE `sample_test_master`.`status`='1' AND `sample_test_master`.`id`='" . $tn[1] . "'");
                    $this->job_model->master_fun_insert("sample_job_test", array('job_fk' => $insert, "test_fk" => $tn[1], "price" => $result[0]["price"])); */
					$result = $this->job_model->get_val("SELECT price,b2b_price,test_fk FROM sample_test_city_price where status='1' and id='".$tn[1]."';");
					if($result[0]["b2b_price"] != ""){ $price1=$result[0]["b2b_price"]; }else{ $price1=$result[0]["price"]; }
                    $this->job_model->master_fun_insert("sample_job_test", array('job_fk' => $insert, "test_fk" =>$tn[1], "price" =>$price1));
                }
                if ($tn[0] == 'p') {
                    $this->job_model->master_fun_insert("sample_book_package", array("cust_fk" => $uid, "package_fk" => $tn[1], 'job_fk' => $insert, "status" => "1", "type" => "2"));
                }
            }
            $crditdetis = $this->job_model->creditget_last($lab_id);
            if ($crditdetis != "") {
                $total = ($crditdetis->total - $payable);
            } else {
                $total = (0 - $payable);
            }
            $this->job_model->master_fun_insert("sample_credit", array("lab_fk" => $lab_id, "job_fk" => $insert, "debit" => $payable, "total" => $total, "created_date" => date("Y-m-d H:i:s")));
            $this->session->set_flashdata("success", array("Test successfully Booked."));
            redirect("b2b/Lab/sample_list");
        } else {
            $data['lab_list'] = $this->job_model->master_fun_get_tbl_val("collect_from", array('status' => 1), array("id", "asc"));
            $data['phlebo_list'] = $this->job_model->master_fun_get_tbl_val("phlebo_master", array('status' => 1), array("id", "asc"));
            $this->load->view('b2b/header');
            $this->load->view('b2b/nav_lab', $data);

            $this->load->view('b2b/sample_add', $data);
            $this->load->view('b2b/footer');
        }
    }

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

    function upload_report($cid = "") {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $files = $_FILES;
        $this->load->library('upload');
        $file_upload = array();
        /* if (!empty($_FILES['common_report']['name'])) {
          $desc = $this->input->post('desc_common_report');
          $type_common_report = $this->input->post('type_common_report');
          $_FILES['userfile']['name'] = $files['common_report']['name'];
          $_FILES['userfile']['type'] = $files['common_report']['type'];
          $_FILES['userfile']['tmp_name'] = $files['common_report']['tmp_name'];
          $_FILES['userfile']['error'] = $files['common_report']['error'];
          $_FILES['userfile']['size'] = $files['common_report']['size'];
          $config['upload_path'] = './upload/business_report/';
          $config['allowed_types'] = '*';
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
          } */
        if (!empty($_FILES['common_report']['name'])) {
            $filesCount = count($_FILES['common_report']['name']);
            $type_common_report = $this->input->post('type_common_report');
            $desc = $this->input->post('desc_common_report');
            $upcount = 1;
            for ($i = 0; $i < $filesCount; $i++) {

                $_FILES['userFile']['name'] = $_FILES['common_report']['name'][$i];
                $_FILES['userFile']['type'] = $_FILES['common_report']['type'][$i];
                $_FILES['userFile']['tmp_name'] = $_FILES['common_report']['tmp_name'][$i];
                $_FILES['userFile']['error'] = $_FILES['common_report']['error'][$i];
                $_FILES['userFile']['size'] = $_FILES['common_report']['size'][$i];

                $config['upload_path'] = './upload/business_report/';
                $config['allowed_types'] = 'pdf';
                $config['file_name'] = time() . "_" . $upcount . $_FILES['userFile']['name'];
                $config['file_name'] = str_replace(' ', '_', $config['file_name']);
                $config['overwrite'] = FALSE;

                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if ($this->upload->do_upload('userFile')) {

                    $fileData = $this->upload->data();
                    $clientname = $fileData['client_name'];
                    $filename = $fileData['file_name'];
                    $upcount++;
                    $file_upload = array("job_fk" => $cid, "report" => $filename, "original" => $clientname, "description" => $desc, "type" => $type_common_report);

                    $delete = $this->Logistic_model->master_fun_insert("b2b_jobspdf", $file_upload);
                }
            }
        }

        $this->session->set_flashdata("success", array("$upcount Report Upload successfully."));
        redirect('b2b/Lab/details/' . $cid);
    }

    public function getreportpdf() {
        $pid = $this->input->post("pid");

        $jobspdf = $this->Lab_model->master_fun_get_tbl_val("b2b_jobspdf", array("job_fk" => $pid, 'status' => 1), array("id", "asc"));
        ?>
        <table class="table table-striped" id="city_wiae_price123">
                <?php /* <thead>
                  <tr><th>file Name</th><th></th></tr>
                  </thead> */ ?>
            <tbody id="t_body123">
                <?php if ($jobspdf != null) {
                    foreach ($jobspdf as $pdf) { ?>
                        <tr id="pdf_<?= $pdf['id']; ?>">
                            <td><a href="<?php echo base_url(); ?>upload/business_report/<?php echo $pdf['report']; ?>" target="_blank"> <?php echo $pdf['original']; ?> </a></td>
                            <td><a href="<?php echo base_url(); ?>b2b/lab/downlodepdf/<?php echo $pdf['report']; ?>" data_temp="<?= base_url() . "b2b/lab/downlodepdf/" . $pdf['report']; ?>" target="_blank" class="pdfdelete" >Downlod</a></td>
                        </tr>
            <?php }
        } else {
            echo "<tr><td colspan='2'>no data available</td></tr>";
        } ?>
            </tbody>
        </table>



    <?php
    }

    public function downlodepdf($file) {

        if ($file != "") {
            $filename = "upload/business_report/" . $file;
            header('Pragma: public');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Cache-Control: private', false); // required for certain browsers
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . basename($filename) . '";');
            header('Content-Transfer-Encoding: binary');
            header('Content-Length: ' . filesize($filename));

            readfile($filename);

            exit;
        } else {
            show_404();
        }
    }

}
