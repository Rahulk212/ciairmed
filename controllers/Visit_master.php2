<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Visit_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('user_model');
        $this->load->model('user_test_master_model');
        $this->load->model('visit_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('pushserver');
        $this->load->library('email');
        $this->load->helper('string');
        $data["login_data"] = logindata();
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
        $user = $this->input->get('user');
        $test_pack = $this->input->get('test_package');
        $p_amount = $this->input->get('p_amount');
        $date = $this->input->get('date');
        $city = $this->input->get('city');
        $status = $this->input->get('status');
        $mobile = $this->input->get('mobile');
        $data['customerfk'] = $user;
        $data['test_pac'] = $test_pack;
        $data['amount'] = $p_amount;
        $data['date'] = $date;
        $data['cityfk'] = $city;
        $data['statusid'] = $status;
        $data['mobile'] = $mobile;
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $test_packages = explode("_", $test_pack);
        $alpha = $test_packages[0];
        $tp_id = $test_packages[1];
        if ($alpha == 't') {
            $t_id = $tp_id;
        }
        if ($alpha == 'p') {
            $p_id = $tp_id;
        }
        $data['success'] = $this->session->flashdata("success");
        //$data['query'] = $this->visit_model->pending_job_search($user, $date, $city, $status, $mobile, $t_id, $p_id, $p_amount);
        if ($user != "" || $date != "" || $city != "" || $status != "" || $mobile != "" || $t_id != "" || $p_id != "" || $p_amount != "") {
            $total_row = $this->visit_model->num_row_srch_job_list($user, $date, $city, $status, $mobile, $t_id, $p_id, $p_amount);
            $config = array();
            $get = $_GET;
            unset($get['offset']);
            $config["base_url"] = base_url() . "visit_master/index?" . http_build_query($get);
            $config["total_rows"] = $total_row;
            $config["per_page"] = 10;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->visit_model->row_srch_job_list($user, $date, $city, $status, $mobile, $t_id, $p_id, $p_amount, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;
        } else {
            $total_row = $this->visit_model->num_srch_job_list();
            $config["base_url"] = base_url() . "visit_master/index";
            $config["total_rows"] = $total_row;
            $config["per_page"] = 10;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->visit_model->srch_job_list($config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;
        }
        $data['customer'] = $this->visit_model->master_fun_get_tbl_val("customer_master", array('status' => 1), array("full_name", "asc"));
        /* $data['test_list'] = $this->visit_model->test_with_city(); */
        /* $data['package_list'] = $this->visit_model->master_fun_get_tbl_val("package_master", array('status' => 1), array("title", "asc")); */
        $data['city'] = $this->visit_model->master_fun_get_tbl_val("city", array('status' => 1), array("id", "asc"));
        $cnt = 0;
        foreach ($data['query'] as $key) {
            $w_prc = 0;
            /* Count booked test price */
            $booked_tests = $this->visit_model->master_fun_get_tbl_val("wallet_master", array('job_fk' => $key["id"]), array("id", "asc"));
            $emergency_tests = $this->visit_model->master_fun_get_tbl_val("booking_info", array('id' => $key["booking_info"]), array("id", "asc"));
            $f_data = $this->visit_model->master_fun_get_tbl_val("customer_family_master", array('id' => $emergency_tests[0]["family_member_fk"]), array("id", "asc"));
            $f_data1 = $this->visit_model->master_fun_get_tbl_val("relation_master", array('id' => $f_data[0]["relation_fk"]), array("id", "asc"));
            $phlebo = $this->visit_model->master_fun_get_tbl_val("phlebo_assign_job", array('job_fk' => $key["id"]), array("id", "asc"));
            $phlebo_name = $this->visit_model->master_fun_get_tbl_val("phlebo_master", array('id' => $phlebo[0]["phlebo_fk"]), array("id", "asc"));
            $time_slot = $this->visit_model->master_fun_get_tbl_val("phlebo_time_slot", array('id' => $emergency_tests[0]["time_slot_fk"]), array("id", "asc"));
            $relation = "Self";
            if (!empty($f_data1)) {
                $relation = ucfirst($f_data1[0]["name"] . " (" . $f_data[0]["name"] . ")");
            }
            $data['query'][$cnt]["relation"] = $relation;
            foreach ($booked_tests as $tkey) {
                if ($tkey["debit"]) {
                    $w_prc = $w_prc + $tkey["debit"];
                }
            }
            if ($time_slot[0]["start_time"] == '') {
                $s_time = "";
                $e_time = "";
            } else {
                $s_time = date('h:i A', strtotime($time_slot[0]["start_time"]));
                $e_time = date('h:i A', strtotime($time_slot[0]["end_time"]));
            }
            if ($emergency_tests[0]["date"] == '0000-00-00') {
                $datebb = "-";
            } else {
                $datebb = date("d F,Y", strtotime($emergency_tests[0]["date"]));
            }
            $data['query'][$cnt]["emergency"] = $emergency_tests[0]["emergency"];
            $data['query'][$cnt]["address"] = $emergency_tests[0]["address"];
            $data['query'][$cnt]["sample_date"] = $datebb;
            $data['query'][$cnt]["sample_time"] = $s_time . " - " . $e_time;
            $data['query'][$cnt]["cut_from_wallet"] = $w_prc;
            $data['query'][$cnt]["phlebo_name"] = $phlebo_name[0]["name"];
            $cnt++;
        }
        //echo "<pre>"; print_r($data['query']); die();
        $url = "http://" . $_SERVER['HTTP_HOST'] . ":" . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
        $this->session->set_userdata("job_master_r", $url);
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('visitor_job_list', $data);
        $this->load->view('footer');
    }

    function changing_status_job() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $status = $this->input->post('status');
        $job_id = $this->input->post('jobid');
        $customer_last_job_id = $this->visit_model->master_fun_get_tbl_val("job_master", array('id' => $job_id), array("id", "asc"));
        if ($status == 2) {
            $this->job_mark_completed($job_id);
        }
        if ($status == 7) {
            $this->sample_collected_calculation($job_id);
        }
        $status_update = $this->visit_model->master_fun_update("job_master", array("id", $job_id), array("status" => $status));
        $this->visit_model->master_fun_insert("job_log", array("job_fk" => $job_id, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => $customer_last_job_id[0]["status"] . "-" . $status, "message_fk" => "3", "date_time" => date("Y-m-d H:i:s")));
        if ($status_update) {
            echo 1;
        }
    }

    function changing_spam($id) {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $status_update = $this->visit_model->master_fun_update("job_master", array("id", $id), array("status" => '0'));
        $this->visit_model->master_fun_update("phlebo_assign_job", array("job_fk", $id), array("status" => '0'));
        $this->visit_model->master_fun_insert("job_log", array("job_fk" => $id, "created_by" => "", "updated_by" => "", "deleted_by" => $data["login_data"]["id"], "message_fk" => "4", "date_time" => date("Y-m-d H:i:s")));
        $this->session->set_flashdata("success", array("Job successfully mark as Spam."));
        if (!empty($this->session->userdata("job_master_r"))) {
            redirect($this->session->userdata("job_master_r"), "refresh");
        } else {
            redirect("job-master/pending-list", "refresh");
        }
    }

    function prescription_spam($id) {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $status_update = $this->visit_model->master_fun_update("prescription_upload", array("id", $id), array("status" => '0'));
        //$this->visit_model->master_fun_insert("job_log", array("job_fk" => $id, "created_by" => "", "updated_by" => "", "deleted_by" => $data["login_data"]["id"], "message_fk" => "4", "date_time" => date("Y-m-d H:i:s")));
        $this->session->set_flashdata("success", array("Prescription successfully mark as Spam."));
        redirect("job-master/prescription-report", "refresh");
    }

    function spam_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();

        $user = $this->input->get('user');
        $date = $this->input->get('date');
        $city = $this->input->get('city');
        $data['customerfk'] = $user;
        $data['date'] = $date;
        $data['cityfk'] = $city;
        //print_r($data["login_data"]); die();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $data['success'] = $this->session->flashdata("success");
        $data['query'] = $this->visit_model->spam_job_search($user, $date, $city);
        //$this->load->view('admin/state_list_view', $data);
        $data['customer'] = $this->visit_model->master_fun_get_tbl_val("customer_master", array('status' => 1), array("id", "asc"));
        $data['city'] = $this->visit_model->master_fun_get_tbl_val("city", array('status' => 1), array("id", "asc"));

        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('spam_job_list', $data);
        $this->load->view('footer');
    }

    function completed_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();

        $user = $this->input->get('user');
        $date = $this->input->get('date');
        $city = $this->input->get('city');
        $data['customerfk'] = $user;
        $data['date'] = $date;
        $data['cityfk'] = $city;
        //print_r($data["login_data"]); die();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $data['success'] = $this->session->flashdata("success");
        $data['query'] = $this->visit_model->completed_job_search($user, $date, $city);
        //$this->load->view('admin/state_list_view', $data);

        $data['customer'] = $this->visit_model->master_fun_get_tbl_val("customer_master", array('status' => 1), array("id", "asc"));
        $data['city'] = $this->visit_model->master_fun_get_tbl_val("city", array('status' => 1), array("id", "asc"));
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('completed_job_list', $data);
        $this->load->view('footer');
    }

    function job_details() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['cid'] = $this->uri->segment(3);
        $data['success'] = $this->session->flashdata("success");
        $data['error'] = $this->session->flashdata("error");
        $data['family_error'] = $this->session->flashdata("family_error");
        $data['amount_history_success'] = $this->session->flashdata("amount_history_success");
        if ($this->session->userdata("amount_history_success")) {
            $data['amount_history_success'] = $this->session->userdata("amount_history_success");
            $this->session->unset_userdata("amount_history_success");
        }
        $data['query'] = $this->visit_model->job_details($data['cid']);
        if ($data['query'][0]["payment_type"] == 'PayUMoney') {
            $data['payumoney_details'] = $this->visit_model->master_fun_get_tbl_val("payment", array('job_fk' => $data['cid']), array("id", "asc"));
        }
        $data['report'] = $this->visit_model->master_fun_get_tbl_val("report_master", array('status' => 1, "job_fk" => $data['cid'], "type !=" => "c"), array("id", "asc"));
        $data['common_report'] = $this->visit_model->master_fun_get_tbl_val("report_master", array('status' => 1, "job_fk" => $data['cid'], "type" => "c"), array("id", "asc"));
        $data['city'] = $this->visit_model->master_fun_get_tbl_val("city", array('status' => 1), array("id", "asc"));
        $data['state'] = $this->visit_model->master_fun_get_tbl_val("state", array('status' => 1), array("id", "asc"));
        $data['country'] = $this->visit_model->master_fun_get_tbl_val("country", array('status' => 1), array("id", "asc"));
        $data['package_price'] = $this->visit_model->master_fun_get_tbl_val("package_master_city_price", array('status' => 1), array("id", "asc"));
        $data['phlebo_list'] = $this->visit_model->master_fun_get_tbl_val("phlebo_master", array('status' => 1), array("id", "asc"));
        $data['phlebo_assign_job'] = $this->visit_model->master_fun_get_tbl_val("phlebo_assign_job", array('status' => 1, "job_fk" => $this->uri->segment(3)), array("id", "asc"));
        $p_prc = 0;
        foreach ($data['phlebo_assign_job'] as $pkey) {
            if ($pkey["time_fk"]) {
                $time_slot = $this->visit_model->master_fun_get_tbl_val("phlebo_time_slot", array('status' => "1", "id" => $pkey["time_fk"]), array("id", "asc"));
                $s_time = date('h:i a', strtotime($time_slot[0]["start_time"]));
                $e_time = date('h:i a', strtotime($time_slot[0]["end_time"]));
                $data['phlebo_assign_job'][$p_prc]["time"] = $s_time . " TO " . $time_slot[0]["end_time"];
            }
            $data['phlebo_assign_job'][$p_prc]["time1"] = $pkey["time"];
            $p_prc++;
        }
        $data["emergency_tests"] = $this->visit_model->master_fun_get_tbl_val("booking_info", array('id' => $data['query'][0]["booking_info"]), array("id", "asc"));
        $data["booking_info"] = $this->visit_model->get_val("SELECT 
      `booking_info`.*,
      TIME_FORMAT(
        `phlebo_time_slot`.`start_time`,
        '%l:%i %p'
      ) AS `start_time`,
      TIME_FORMAT(
        `phlebo_time_slot`.`end_time`,
        '%l:%i %p'
      ) AS `end_time`,
      `customer_family_master`.`name` 
    FROM
      `booking_info` 
      INNER JOIN `phlebo_time_slot` 
        ON `booking_info`.`time_slot_fk` = `phlebo_time_slot`.`id` 
      LEFT JOIN `customer_family_master` 
        ON `booking_info`.`family_member_fk` = `customer_family_master`.`id` where booking_info.id='" . $data['query'][0]["booking_info"] . "'");
        $f_data = $this->visit_model->master_fun_get_tbl_val("customer_family_master", array('id' => $data["emergency_tests"][0]["family_member_fk"]), array("id", "asc"));
        $f_data1 = $this->visit_model->master_fun_get_tbl_val("relation_master", array('id' => $f_data[0]["relation_fk"]), array("id", "asc"));
        $data["relation"] = "Self";
        $data["family_fk"] = $f_data;
        if (!empty($f_data1)) {
            $data["relation"] = ucfirst($f_data1[0]["name"] . " (" . $f_data[0]["name"] . ")");
        }
        $update = $this->visit_model->master_fun_update("job_master", array('id', $data['cid']), array("views" => "1"));
        $booked_tests = $this->visit_model->master_fun_get_tbl_val("wallet_master", array('job_fk' => $data['cid']), array("id", "asc"));
        $w_prc = 0;
        foreach ($booked_tests as $tkey) {
            if ($tkey["debit"]) {
                $w_prc = $w_prc + $tkey["debit"];
            }
        }
        $test1 = array();
        $tid = explode(",", $data['query'][0]['testid']);
        foreach ($tid as $tst_id) {
            $para = $this->visit_model->get_val("SELECT p.id as pid,p.test_fk,p.parameter_name,p.parameter_range,p.parameter_unit,g.id as gid,g.parameter_fk,g.subparameter_name,g.subparameter_range,g.subparameter_unit FROM test_parameter_master as p LEFT JOIN parameter_group_master as g ON p.id=g.parameter_fk WHERE p.status='1' AND p.test_fk='" . $tst_id . "' order by p.id ASC");
            array_push($test1, $para);
        }
        $data["job_master_receiv_amount"] = $this->visit_model->get_val("SELECT `job_master_receiv_amount`.*,`admin_master`.`name` FROM `job_master_receiv_amount` INNER JOIN `admin_master` ON `job_master_receiv_amount`.`added_by`=`admin_master`.`id` WHERE job_master_receiv_amount.job_fk='" . $data['cid'] . "' AND job_master_receiv_amount.status='1'");
        $data['relation_list'] = $this->visit_model->get_val("SELECT 
  `customer_family_master`.*,
  `relation_master`.`name` AS relation_name 
FROM
  `customer_family_master` 
  INNER JOIN `relation_master` 
    ON `customer_family_master`.`relation_fk` = `relation_master`.`id` 
WHERE `customer_family_master`.`status` = '1' 
  AND `relation_master`.`status` = '1' 
  AND `customer_family_master`.`user_fk` = '" . $data['query'][0]["custid"] . "'");
        $data["relation1"] = $this->visit_model->master_fun_get_tbl_val("relation_master", array('status' => "1"), array("name", "asc"));
        $data['parameter_list'] = $test1;
        $data["cut_from_wallet"] = $w_prc;
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('job_details', $data);
        $this->load->view('footer');
    }

    function job_note_update() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $job_note = $this->input->post("job_note");
        $job_id = $this->input->post("job_id");
        if ($job_note != '' && $job_id != '') {
            $this->visit_model->master_fun_update("job_master", array('id', $job_id), array("note" => $job_note));
            $this->visit_model->master_fun_insert("job_log", array("job_fk" => $job_id, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "message_fk" => "5", "date_time" => date("Y-m-d H:i:s")));
            echo json_encode(array("status" => "1", "msg" => "<span style='color:green;'>Note successfully saved.</span>"));
        } else {
            echo json_encode(array("status" => "0", "msg" => "<span style='color:red;'>Invalid parameter.</span>"));
        }
    }

    function job_mark_spam() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->visit_model->master_fun_update("job_master", array("id", $cid), array("status" => "3"));
        $this->visit_model->master_fun_insert("job_log", array("job_fk" => $cid, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "message_fk" => "4", "date_time" => date("Y-m-d H:i:s")));
        $data = $this->visit_model->master_fun_get_tbl_val("job_master", array("id" => $cid), array("id", "asc"));
        $cust_fk = $data[0]['cust_fk'];

        $data1 = $this->visit_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "id" => $cust_fk), array("id", "asc"));
        $query = $this->visit_model->job_details($cid);
        $testid = $query[0]['id'];
//		$testname = str_replace(","," ",$query[0]['testname']);
        $testname = $query[0]['testname'];
        $testprice = $query[0]['price'];
        $testdate = $query[0]['date'];
        $device_id = $data1[0]['device_id'];
        $device_id = $data1[0]['device_id'];
        $mobile = $data1[0]['mobile'];
        $orderid = $data[0]['order_id'];
        $message = "Your Report has been Spam";
        if ($device_type == 'android') {
            //$notification_data=array("title" => "Patholab","message" =>$message,"type"=>"spam");
            $notification_data = array("title" => "AirmedLabs", "message" => $message, "type" => "spam", "testid" => $testid, "testname" => $testname, "testprice" => $testprice, "testdate" => $testdate, "order_id" => $orderid);
//print_r($notification_data); die();
            $pushServer = new PushServer();
            $pushServer->pushToGoogle($device_id, $notification_data);
            $device_type = $data1[0]['device_type'];
        }
        if ($device_type == 'ios') {
            $url = 'http://website-demo.in/patholabpush/push.php?device_id=' . $device_id . '&msg=' . $message . '&type=spam&testid=' . $testid . '&testname=' . $testname . '&testprice=' . $testprice . '&testdate=' . $testdate . '&orderid=' . $orderid . '';
            $url = str_replace(" ", "%20", $url);
            $data = $this->get_content($url);
            $data2 = json_decode($data);
        }
        /* Nishit send sms code start */
        $sms_message = $this->visit_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "Span_report"), array("id", "asc"));
        $sms_message = preg_replace("/{{NAME}}/", ucfirst($data1[0]["full_name"]), $sms_message[0]["message"]);
        $sms_message = preg_replace("/{{OID}}/", $orderid, $sms_message);
        $sms_message = preg_replace("/{{PRICE}}/", "Rs." . $testprice, $sms_message);
        $this->load->helper("sms");
        $notification = new Sms();
        $notification::send($mobile, $sms_message);
        /* Nishit send sms code end */

        $this->session->set_flashdata("success", array("Job successfully mark as Spam."));
        if (!empty($this->session->userdata("job_master_r"))) {
            redirect($this->session->userdata("job_master_r"), "refresh");
        } else {
            redirect("job-master/pending-list", "refresh");
        }
    }

    function testme() {
        $pushServer = new PushServer();
        $notification_data = array("title" => "AirmedLabs", "message" => "this is test massage notification", "type" => "completed");
        $device = "APA91bHqOvx5_EqEp8UUcVOhnhEF9t-AjOe6hddRjADf-hIMytTrvJ_5Wq3iNxXW_mgPmMopGH7_NvMxS_DHXUJiVhSoq3X0aZhIPjIb4mEC3DzLecBJS8w";
        echo $res = $pushServer->pushToGoogle($device, $notification_data);
//$res = $pushServer->pushToGoogle("APA91bHqOvx5_EqEp8UUcVOhnhEF9t-AjOe6hddRjADf-hIMytTrvJ_5Wq3iNxXW_mgPmMopGH7_NvMxS_DHXUJiVhSoq3X0aZhIPjIb4mEC3DzLecBJS8w",$notification_data);
        //  $url = 'http://website-demo.in/chatonpush/push.php?device_id='.$ios.'&msg='.$message['message'].'&user_id='.$user_id.'&type='.$type;
        //$url = str_replace(" ","%20",$url);
        //$data = $this->get_content($url);
        //$data2 = json_decode($data);
    }

    function testios() {

        echo $url = 'http://website-demo.in/patholabpushtest/push.php?device_id=8b6465df4803375109e78460508d4a26758f932451b6228c37b42e6563d33b18
&msg=this is test massage notification&type=suggested_test&id=&desc=&date=';
        $url = str_replace(" ", "%20", $url);
        $data = $this->get_content($url);
        $data2 = json_decode($data);
    }

    function job_mark_pending() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->visit_model->master_fun_update("job_master", array("id", $cid), array("status" => "1"));
        $this->visit_model->master_fun_insert("job_log", array("job_fk" => $cid, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "message_fk" => "5", "date_time" => date("Y-m-d H:i:s")));
        $data = $this->visit_model->master_fun_get_tbl_val("job_master", array("id" => $cid), array("id", "asc"));
        $cust_fk = $data[0]['cust_fk'];

        $data1 = $this->visit_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "id" => $cust_fk), array("id", "asc"));
        $device_id = $data1[0]['device_id'];
        $mobile = $data1[0]['mobile'];
        $device_type = $data1[0]['device_type'];
        $query = $this->visit_model->job_details($cid);
        $testid = $query[0]['id'];
        $testname = $query[0]['testname'];
        $testprice = $query[0]['price'];
        $testdate = $query[0]['date'];
        $orderid = $query[0]['order_id'];
        $message = "Your Report has been Pending";
        if ($device_type == 'android') {
            //$notification_data=array("title" => "Patholab","message" =>$message,"type"=>"Pending");
            $notification_data = array("title" => "AirmedLabs", "message" => $message, "type" => "Pending", "testid" => $testid, "testname" => $testname, "testprice" => $testprice, "testdate" => $testdate, "order_id" => $orderid);
            $pushServer = new PushServer();
            $pushServer->pushToGoogle($device_id, $notification_data);
        }
        if ($device_type == 'ios') {
            $url = 'http://website-demo.in/patholabpush/push.php?device_id=' . $device_id . '&msg=' . $message . '&type=pending&testid=' . $testid . '&testname=' . $testname . '&testprice=' . $testprice . '&testdate=' . $testdate . '&orderid=' . $orderid . '';
            $url = str_replace(" ", "%20", $url);
            $data = $this->get_content($url);
            $data2 = json_decode($data);
        }
        /* Nishit send sms code start */
        $sms_message = $this->visit_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "Pending_Report"), array("id", "asc"));
        $sms_message = preg_replace("/{{NAME}}/", ucfirst($data1[0]["full_name"]), $sms_message[0]["message"]);
        $sms_message = preg_replace("/{{OID}}/", $orderid, $sms_message);
        $sms_message = preg_replace("/{{PRICE}}/", "Rs." . $testprice, $sms_message);
        $this->load->helper("sms");
        $notification = new Sms();
        $notification::send($mobile, $sms_message);
        /* Nishit send sms code end */
        $this->session->set_flashdata("success", array("Job successfully mark as pending."));
        if (!empty($this->session->userdata("job_master_r"))) {
            redirect($this->session->userdata("job_master_r"), "refresh");
        } else {
            redirect("job-master/pending-list", "refresh");
        }
    }

    function job_mark_completed($cid) {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->helper("Email");
        $email_cnt = new Email;
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        // $cid = $this->uri->segment('3');
        $update = $this->visit_model->master_fun_update("job_master", array("id", $cid), array("status" => "2"));
        //$this->visit_model->master_fun_insert("job_log", array("job_fk" => $cid, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "message_fk" => "3", "date_time" => date("Y-m-d H:i:s")));
        if ($update) {

            $data = $this->visit_model->master_fun_get_tbl_val("job_master", array("id" => $cid), array("id", "asc"));
            $cust_fk = $data[0]['cust_fk'];
            $data1 = $this->visit_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "id" => $cust_fk), array("id", "asc"));
            $device_id = $data1[0]['device_id'];
            $mobile = $data1[0]['mobile'];
            $device_type = $data1[0]['device_type'];
            $name = $data1[0]['full_name'];
            $email = $data1[0]['email'];
            $query = $this->visit_model->job_details($cid);
            $testid = $query[0]['id'];
            $testname = $query[0]['testname'];
            $testprice = $query[0]['price'];
            $testdate = $query[0]['date'];
            $orderid = $query[0]['order_id'];
            $message = "Your Report has been Completed";
            if ($device_type == 'android') {
                //$notification_data=array("title" => "Patholab","message" =>$message,"type"=>"completed");
                $notification_data = array("title" => "AirmedLabs", "message" => $message, "type" => "completed", "testid" => $testid, "testname" => $testname, "testprice" => $testprice, "testdate" => $testdate, "order_id" => $orderid);
                $pushServer = new PushServer();
                $pushServer->pushToGoogle($device_id, $notification_data);
            }
            if ($device_type == 'ios') {
                $url = 'http://website-demo.in/patholabpush/push.php?device_id=' . $device_id . '&msg=' . $message . '&type=completed&testid=' . $testid . '&testname=' . $testname . '&testprice=' . $testprice . '&testdate=' . $testdate . '&orderid=' . $orderid . '';
                $url = str_replace(" ", "%20", $url);
                $data = $this->get_content($url);
                //die();
                $data2 = json_decode($data);
            }
            /* Nishit send sms code start */
            $family_member_name = $this->visit_model->get_family_member_name($cid);
            if (!empty($family_member_name)) {
                $cmobile = $family_member_name[0]["phone"];
                if (empty($cmobile)) {
                    $cmobile = $data1[0]['mobile'];
                }
            } else {
                $cmobile = $data1[0]['mobile'];
            }
            $sms_message = $this->visit_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "Completed_Report"), array("id", "asc"));
            // $sms_message = preg_replace("/{{NAME}}/", ucfirst($data1[0]["full_name"]), $sms_message[0]["message"]);
            $sms_message = preg_replace("/{{OID}}/", $orderid, $sms_message[0]["message"]);
            //$sms_message = preg_replace("/{{PRICE}}/", "Rs." . $testprice, $sms_message);
            $this->load->helper("sms");
            $notification = new Sms();
            $notification->send($cmobile, $sms_message);
            if (!empty($family_member_name)) {
                $this->visit_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $mobile, "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
            }
            /* Nishit send sms code end */

            $report = $this->visit_model->master_fun_get_tbl_val("report_master", array("job_fk" => $cid), array("id", "asc"));
            $nw_ary = array();
            foreach ($report as $rkey) {
                if ($rkey['type'] == 'c') {
                    $nw_ary = $rkey;
                }
            }
            if (!empty($nw_ary)) {
                $report = array($nw_ary);
            }
            //print_R($report); die();
            $config['mailtype'] = 'html';

            $this->email->initialize($config);
            $family_member_name = $this->visit_model->get_family_member_name($cid);
            if (!empty($family_member_name)) {
                $name = $family_member_name[0]["name"];
            }
            /* Nishit cashback start */
            $job_details = $this->get_job_details($cid);
            $b_details = array();
            foreach ($job_details[0]["book_test"] as $bkey) {
                //$b_details[] = $bkey["test_name"] . " Rs." . $bkey["price"];
            }
            $test_prc = 0;
            foreach ($job_details[0]["book_test"] as $t_key) {
                $tst_prc = $this->visit_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $job_details[0]["test_city"] . "' AND `test_master`.`id`='" . $t_key["id"] . "'");
                $test_prc = $test_prc + $tst_prc[0]["price"];
            }
            if (!empty($job_details[0]["book_test"]) && $job_details[0]["status"] != '0') {
                $query = $this->visit_model->master_fun_get_tbl_val("offer_master", array("status" => 1), array("id", "desc"));
                $caseback_per = $query[0]['caseback_per'];
                $query = $this->visit_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $job_details[0]["cust_fk"]), array("id", "desc"));
                $total = $query[0]['total'];
                $price = $test_prc;
                $caseback_amount = ($price * $caseback_per) / 100;
                $data = array(
                    "cust_fk" => $cust_fk,
                    "credit" => $caseback_amount,
                    "total" => $total + $caseback_amount,
                    "type" => "Case Back",
                    "job_fk" => $cid,
                    "created_time" => date('Y-m-d H:i:s')
                );
                if ($test_prc != 0) {
                    $insert1 = $this->visit_model->master_fun_insert("wallet_master", $data);
                }
                $query = $this->visit_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $job_details[0]["cust_fk"]), array("id", "desc"));
                $Current_wallet = $query[0]['total'];
// Case Back Email start
                $config['mailtype'] = 'html';
                $this->email->initialize($config);

                $message = '<div style="padding:0 4%;">
                    <h4><b>Dear </b>' . $name . '</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Cashback Credited in your Wallet. </p>
                        <p style="color:#7e7e7e;font-size:13px;"> Rs. ' . $caseback_amount . ' Credited in your account. </p>
                        <p style="color:#7e7e7e;font-size:13px;">Your Current Wallet Amount is Rs. ' . $Current_wallet . '</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
                $message = $email_cnt->get_design($message);
                $this->email->to($email);
                $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
                $this->email->subject('CashBack');
                $this->email->message($message);
                if ($test_prc != 0) {
                    $this->email->send();
                }
                // Case Back Email end			
            }
            /* Nishit cashback end */
            $book_test_details = array();
            foreach ($job_details[0]["book_test"] as $bkey) {
                $book_test_details[] = $bkey["test_name"];
            }
            foreach ($job_details[0]["book_package"] as $bkey) {
                $book_test_details[] = $bkey["title"];
            }
            $message1 = '<div style="padding:0 4%;">
                    <h4><b>Dear </b>' . $name . '</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Report completed successfully for test ' . implode(",", $book_test_details) . ' </p>
                        <p style="color:#7e7e7e;font-size:13px;">Your Report ID : ' . $orderid . ' </p>    
		<p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
            $message1 = $email_cnt->get_design($message1);
            $this->email->to($email);
            //  $this->email->to('jeel@virtualheight.com');
            $this->email->from($this->config->item('admin_booking_email'), "AirmedLabs");
            $this->email->subject("Report Completed");
            $this->email->message($message1);
            $attatchPath = "";
            foreach ($report as $key) {
                $attatchPath = base_url() . "upload/report/" . $key['report'];
                $this->email->attach($attatchPath);
            }
            //$this->email->attach(implode(',',$attatchPath));
            $this->email->send();
            $family_member_name = $this->visit_model->get_family_member_name($cid);
            if (!empty($family_member_name)) {
                $c_email = $family_member_name[0]["email"];
                if (!empty($c_email)) {
                    $this->email->to($c_email);
                    //  $this->email->to('jeel@virtualheight.com');
                    $this->email->from($this->config->item('admin_booking_email'), "AirmedLabs");
                    $this->email->subject("Report Completed");
                    $this->email->message($message1);
                    $attatchPath = "";
                    foreach ($report as $key) {
                        $attatchPath = base_url() . "upload/report/" . $key['report'];
                        $this->email->attach($attatchPath);
                    }
                    //$this->email->attach(implode(',',$attatchPath));
                    $this->email->send();
                }
            }

            $this->session->set_flashdata("success", array("Job successfully mark as completed."));
            //redirect("job-master/pending-list", "refresh");
        }
    }

    function sample_collected() {
        $cid = $this->uri->segment('3');
        //	die();
        $this->sample_collected_calculation($cid);
        $this->session->set_flashdata("success", array("Sample Collection completed."));
        redirect("job-master/pending-list", "refresh");
    }

    function sample_collected_calculation($cid) {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data2 = $this->visit_model->master_fun_get_tbl_val("job_master", array("id" => $cid), array("id", "asc"));
        $cust_fk = $data2[0]['cust_fk'];
        $price = $data2[0]['price'];
        $payment_type = $data2[0]['payment_type'];
        $status = $data2[0]['sample_collection'];
        $update = $this->visit_model->master_fun_update("job_master", array("id", $cid), array("sample_collection" => "1"));
        if ($update) {
            $data1 = $this->visit_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "id" => $cust_fk), array("id", "asc"));
            $data1r = $this->visit_model->master_fun_get_tbl_val("refer_code_master", array('used_code !=' => "", "cust_fk" => $cust_fk), array("id", "asc"));
            if ($data1r) {
                $rcode = $data1r[0]['used_code'];
                $urcode = $data1r[0]['cust_fk'];
                $data1r1 = $this->visit_model->master_fun_get_tbl_val("refer_code_master", array('refer_code' => $rcode), array("id", "asc"));
                $addmid = $data1r1[0]['cust_fk'];
                if ($data1r1) {
                    $data2 = $this->visit_model->master_fun_get_tbl_val("job_master", array("cust_fk" => $cust_fk), array("id", "asc"));
                    $cnt = count($data2);
                    if ($cnt == "1") {
                        $query = $this->visit_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $addmid), array("id", "desc"));
                        $total = $query[0]['total'];
                        $caseback_amount = ($price * $caseback_per) / 100;
                        $data = array(
                            "cust_fk" => $addmid,
                            "credit" => 100,
                            "total" => $total + 100,
                            "type" => "Case Back",
                            "job_fk" => $cid,
                            "created_time" => date('Y-m-d H:i:s')
                        );
                        if ($total != 0) {
                            $insert1 = $this->visit_model->master_fun_insert("wallet_master", $data);
                        }
                    }
                }
            }

            $data1 = $this->visit_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "id" => $cust_fk), array("id", "asc"));
            //print_r($data1);
            //die();
            $device_id = $data1[0]['device_id'];
            $mobile = $data1[0]['mobile'];
            $device_type = $data1[0]['device_type'];
            $name = $data1[0]['full_name'];
            $email = $data1[0]['email'];
            $query = $this->visit_model->job_details($cid);
            $testid = $query[0]['id'];
            $discount = $query[0]['discount'];
            $testname = $query[0]['testname'];
            $packagename = $query[0]['packagename'];
            $testprice = $query[0]['price'];
            $testdate = $query[0]['date'];
            $orderid = $query[0]['order_id'];
            $test_name_mail = explode("#", $testname);
            $package_name_mail = explode("@", $packagename);
            //print_r($test_name_mail); echo "<br>"; print_r($package_name_mail); die();
            $test_mail = implode(",", $test_name_mail);
            $package_mail = implode(",", $package_name_mail);
            $message = "Your Test Sample has been collected";
            if ($device_type == 'android') {
                //$notification_data=array("title" => "Patholab","message" =>$message,"type"=>"Pending");
                $notification_data = array("title" => "AirmedLabs", "message" => $message, "type" => "Pending", "testid" => $testid, "testname" => $testname, "testprice" => $testprice, "testdate" => $testdate, "order_id" => $orderid);
                $pushServer = new PushServer();
                $pushServer->pushToGoogle($device_id, $notification_data);
            }
            if ($device_type == 'ios') {
                $url = 'http://website-demo.in/patholabpush/push.php?device_id=' . $device_id . '&msg=' . $message . '&type=pending&testid=' . $testid . '&testname=' . $testname . '&testprice=' . $testprice . '&testdate=' . $testdate . '&orderid=' . $orderid . '';
                $url = str_replace(" ", "%20", $url);
                $data = $this->get_content($url);
                $data2 = json_decode($data);
            }
            $family_member_name = $this->visit_model->get_family_member_name($cid);
            if (!empty($family_member_name)) {
                $c_name = $family_member_name[0]["name"];
                $cmobile = $family_member_name[0]["phone"];
                if (empty($cmobile)) {
                    $cmobile = $mobile;
                }
            } else {
                $c_name = $data1[0]["full_name"];
                $cmobile = $mobile;
            }
            $sms_message = $this->visit_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "Sample_collection_Sms"), array("id", "asc"));
            $sms_message = preg_replace("/{{NAME}}/", ucfirst($c_name), $sms_message[0]["message"]);
            $sms_message = preg_replace("/{{OID}}/", $orderid, $sms_message);
            //$sms_message = preg_replace("/{{PRICE}}/", "Rs." . $testprice, $sms_message);
            $this->load->helper("sms");
            $notification = new Sms();
            $notification->send($cmobile, $sms_message);
            if (!empty($family_member_name)) {
                $this->visit_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $mobile, "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
            }
            $package_price = $this->visit_model->master_fun_get_tbl_val("book_package_master", array("status" => 1, "job_fk" => $cid), array("id", "desc"));
            $book_tst = $this->visit_model->master_fun_get_tbl_val("job_test_list_master", array("job_fk" => $cid), array("id", "desc"));
            /* $test_prc = 0;
              foreach ($book_tst as $t_key) {
              $tst_prc = $this->visit_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master`.`id`='" . $t_key["test_fk"] . "'");
              $test_prc = $test_prc + $tst_prc[0]["price"];
              }
              if ($payment_type == "Cash On Delivery" && $status == "0" && $discount == "0") {
              $query = $this->visit_model->master_fun_get_tbl_val("offer_master", array("status" => 1), array("id", "desc"));
              $caseback_per = $query[0]['caseback_per'];
              $query = $this->visit_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $cust_fk), array("id", "desc"));
              $total = $query[0]['total'];
              $price = $test_prc;
              $caseback_amount = ($price * $caseback_per) / 100;
              $data = array(
              "cust_fk" => $cust_fk,
              "credit" => $caseback_amount,
              "total" => $total + $caseback_amount,
              "type" => "Case Back",
              "job_fk" => $cid,
              "created_time" => date('Y-m-d H:i:s')
              );
              if ($test_prc != 0) {
              $insert1 = $this->visit_model->master_fun_insert("wallet_master", $data);
              }
              $query = $this->visit_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $cust_fk), array("id", "desc"));
              $Current_wallet = $query[0]['total'];
              // Case Back Email start
              $config['mailtype'] = 'html';
              $this->email->initialize($config);

              $message = '<div style="background:#a3a3a3 !important;font-family: "Roboto",sans-serif;">
              <script src="https://use.fontawesome.com/44049e3dc5.js"></script>
              <div style="background: #6b6b6b none repeat scroll 0 0;float: left;padding: 7%;">
              <div style="border:1px solid #f1f1f1;border-bottom:1px solid #c7c7c7;padding:1.5%;width:100%;float:left;background:#fff;">
              <div style="float:left;width:64%;">
              <img alt="" src="' . base_url() . 'user_assets/images/logo.png" style="height: 100px;width:213px;"/>
              </div>
              <div style="float:right;text-align: right;width:33%;padding-top:7px;">



              </div>
              </div>
              <div style="padding:1.5%;width:100%;float:left;background-color:#fff;border:1px solid #f1f2f3;">
              <div style="padding:0 4%;">
              <h4><b>Dear </b>' . $data1[0]['full_name'] . '</h4>
              <p style="color:#7e7e7e;font-size:13px;">Cashback Credited in your Wallet. </p>

              <p style="color:#7e7e7e;font-size:13px;"> Rs. ' . $caseback_amount . ' Credited in your account. </p>
              <p style="color:#7e7e7e;font-size:13px;">Your Current Wallet Amount is Rs. ' . $Current_wallet . '</p>
              <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
              </div>
              </div>
              <div style="width:100%;float:left;background:#F5F5F5;padding:1.5%;color:#828282;font-size:12px;border:1px solid #dfdfdf;text-align:center;">
              <a href="' . base_url() . 'user_master/about_us" style="color:#515151;text-decoration:none;">About Us</a> |
              <a href="' . base_url() . 'user_master/contact_us" style="color:#515151;text-decoration:none;">Contact Us</a> |
              <a href="' . base_url() . 'user_login" style="color:#515151;text-decoration:none;">Login</a> |
              <a href="' . base_url() . 'user_master/collection" style="color:#515151;text-decoration:none;">Home-collection</a> |
              <a href="https://www.facebook.com/airmedpathlabs" style="color:#515151;text-decoration:none;"><i aria-hidden="true"><img src="' . base_url() . 'user_assets/images/face_book_icon1.jpeg" style="height:15px;width:15px;"></i></a>
              </div>
              <div style="width:100%;float:left;background:#333333;padding:1.5%;color:#ccc;font-size:12px;border:1px solid #323232;text-align:center;">
              Copyright @ 2016 AirmedLabs. All rights reserved
              </div>
              </div>
              </div>';
              $this->email->to($email);
              $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
              $this->email->subject('CashBack');
              $this->email->message($message);
              if ($test_prc != 0) {
              $this->email->send();
              }
              }
             */
            $this->load->helper("Email");
            $email_cnt = new Email;

            $config['mailtype'] = 'html';

            $this->email->initialize($config);
            $family_member_name = $this->visit_model->get_family_member_name($cid);
            if (!empty($family_member_name)) {
                $name = $family_member_name[0]["name"];
            }
            $message1 = '<div style="padding:0 4%;">
                    <h4><b>Dear </b>' . $name . '</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Test Sample has been collected successfully. </p>
						<p style="color:#7e7e7e;font-size:13px;"> You Booked Following Test/Package : ' . $test_mail;
            if ($package_mail != NULL) {
                $message1 .= ' / ' . $package_mail;
            }
            $message1 .= ' </p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
            $message1 = $email_cnt->get_design($message1);
            $this->email->to($email);
            $this->email->from($this->config->item('admin_booking_email'), "AirmedLabs");
            $this->email->subject("Sample Collection Successfully");
            $this->email->message($message1);
            $this->email->send();
            //$this->email->print_debugger();
            $family_member_name = $this->visit_model->get_family_member_name($cid);
            if (!empty($family_member_name)) {
                $c_email = $family_member_name[0]["email"];
                if (!empty($c_email)) {
                    $this->email->to($c_email);
                    $this->email->from($this->config->item('admin_booking_email'), "AirmedLabs");
                    $this->email->subject("Sample Collection Successfully");
                    $this->email->message($message1);
                    $this->email->send();
                }
            }
            //	die();

            /* $this->session->set_flashdata("success", array("Sample Collection completed."));
              redirect("job-master/pending-list", "refresh"); */
        }
    }

    function export_csv() {
        $result = $this->visit_model->csv_job_list();
        $data['city'] = $this->visit_model->master_fun_get_tbl_val("city", array('status' => 1), array("id", "asc"));
        $cnt = 0;
        foreach ($result as $key) {
            $w_prc = 0;
            /* Count booked test price */
            $booked_tests = $this->visit_model->master_fun_get_tbl_val("wallet_master", array('job_fk' => $key["id"]), array("id", "asc"));
            foreach ($booked_tests as $tkey) {
                $w_prc = $w_prc + $tkey["debit"];
            }
            $test_city = $this->visit_model->master_fun_get_tbl_val("test_cities", array('id' => $key["test_city"]), array("id", "asc"));
            $user_info = $this->visit_model->master_fun_get_tbl_val("customer_master", array('id' => $key["cid"]), array("id", "asc"));
            $get_state = $this->visit_model->master_fun_get_tbl_val("state", array('id' => $user_info[0]["state"]), array("id", "asc"));
            $getcity = $this->visit_model->master_fun_get_tbl_val("city", array('id' => $user_info[0]["city"]), array("id", "asc"));
            if ($key["address"] == null || $key["address"] == '') {
                $result[$cnt]["address"] = $user_info[0]["address"];
            }
            $result[$cnt]["state"] = $get_state[0]["state_name"];
            $result[$cnt]["city"] = $getcity[0]["city_name"];
            $result[$cnt]["test_city"] = $test_city[0]["name"];
            $result[$cnt]["cut_from_wallet"] = $w_prc;
            $cnt++;
        }
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"All_Jobs_Report-" . date('d-M-Y') . ".csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("Order Id", "Date", "Customer Name", "Mobile No.", "state", "city", "Address", "Test/Package Name", "Payable Amount", "Debited From Wallet", "Price", "Job Status", "Payment Type", "Blood Sample Collected", "Portal"));

        foreach ($result as $key) {
            if ($key['status'] == 1) {
                $j_status = "Waiting For Approval";
            }
            if ($key['status'] == 6) {
                $j_status = "Approved";
            }
            if ($key['status'] == 7) {
                $j_status = "Sample Collected";
            }
            if ($key['status'] == 8) {
                $j_status = "Processing";
            }
            if ($key['status'] == 2) {
                $j_status = "Completed";
            }
            $sample_collected = 'No';
            if ($key["sample_collection"] == 1) {
                $sample_collected = 'Yes';
            }
            if (!$key["payable_amount"]) {
                $key["payable_amount"] = 0;
            }
            fputcsv($handle, array($key["order_id"], $key["date"], $key["full_name"], $key["mobile"], $key["state"], $key["city"], $key["address"], $key["testname"] . " " . $key["packagename"], $key["payable_amount"], $key["cut_from_wallet"], $key["price"], $j_status, $key["payment_type"], $sample_collected, $key["portal"]));
        }
        fclose($handle);
        exit;
    }

    function upload_report($cid = "") {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $files = $_FILES;
        $this->load->library('upload');
        $testid = $this->input->post('testids');
        $testtype = $this->input->post('testtype');
        $testids = $this->input->post('testids');
        $report_status = $this->input->post('report_status');
        $ts = $testids;
        $file_loop = count($_FILES['userfile']['name']);
        $file_upload = array();
        if (empty($_FILES['common_report']['name'])) {
            if (!empty($_FILES['userfile']['name'])) {
                for ($i = 0; $i < $file_loop; $i++) {
                    $desc = $this->input->post('desc_' . $i);
                    $_FILES['userfile']['name'] = $files['userfile']['name'][$i];
                    $_FILES['userfile']['type'] = $files['userfile']['type'][$i];
                    $_FILES['userfile']['tmp_name'] = $files['userfile']['tmp_name'][$i];
                    $_FILES['userfile']['error'] = $files['userfile']['error'][$i];
                    $_FILES['userfile']['size'] = $files['userfile']['size'][$i];
                    $config['upload_path'] = './upload/report/';
                    $config['allowed_types'] = 'pdf';
                    $config['file_name'] = time() . $files['userfile']['name'][$i];
                    $config['file_name'] = str_replace(' ', '_', $config['file_name']);
                    $config['overwrite'] = FALSE;
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload()) {
                        $error = $this->upload->display_errors();
                        $this->session->set_flashdata("error", array($error));
                        redirect('job-master/job-details/' . $cid);
                    } else {
                        $file_upload[] = array("job_fk" => $cid, "report" => $config['file_name'], "original" => $_FILES['userfile']['name'], "test_fk" => $ts[$i], "description" => $desc, "type" => $testtype[$i]);
                    }
                }
            }
        } else {
            $desc = $this->input->post('desc_common_report');
            $type_common_report = $this->input->post('type_common_report');
            $_FILES['userfile']['name'] = $files['common_report']['name'];
            $_FILES['userfile']['type'] = $files['common_report']['type'];
            $_FILES['userfile']['tmp_name'] = $files['common_report']['tmp_name'];
            $_FILES['userfile']['error'] = $files['common_report']['error'];
            $_FILES['userfile']['size'] = $files['common_report']['size'];
            $config['upload_path'] = './upload/report/';
            $config['allowed_types'] = 'pdf';
            $config['file_name'] = time() . $files['common_report']['name'];
            $config['file_name'] = str_replace(' ', '_', $config['file_name']);
            $config['overwrite'] = FALSE;
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if (!$this->upload->do_upload()) {
                $error = $this->upload->display_errors();
                $this->session->set_flashdata("error", array($error));
                redirect('job-master/job-details/' . $cid);
            } else {
                $file_upload[] = array("job_fk" => $cid, "report" => $config['file_name'], "original" => $_FILES['common_report']['name'], "test_fk" => "", "description" => $desc, "type" => $type_common_report);
            }
        }
        foreach ($file_upload as $f_key) {
            $row = $this->visit_model->master_num_rows("report_master", array("job_fk" => $f_key["job_fk"], "test_fk" => $f_key["test_fk"], "status" => "1"));
            if ($row == 1) {
                $delete = $this->visit_model->master_fun_update_multi("report_master", array("job_fk" => $f_key["job_fk"], "test_fk" => $f_key["test_fk"], "type" => $f_key["type"]), array("report" => $f_key["report"], "original" => $f_key["original"], "description" => $f_key["description"]));
            } else {
                $data['query'] = $this->visit_model->master_fun_insert("report_master", array("job_fk" => $f_key["job_fk"], "report" => $f_key["report"], "original" => $f_key["original"], "test_fk" => $f_key["test_fk"], "description" => $f_key["description"], "type" => $f_key["type"]));
            }
        }

        $this->visit_model->master_fun_insert("job_log", array("job_fk" => $cid, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "message_fk" => "7", "date_time" => date("Y-m-d H:i:s")));
        //$ts = explode(',', $testid);
        $ctn = 0;
        if (!empty($ts)) {
            foreach ($ts as $key) {
                $desc = $this->input->post('desc_' . $ctn);
                //	die();
                $update = $this->visit_model->master_fun_update_multi("report_master", array("test_fk" => $key, "job_fk" => $cid, "type" => $testtype[$ctn]), array("description" => $desc));
                $ctn++;
            }
        }
        $this->visit_model->master_fun_update("job_master", array("id", $cid), array("report_status" => $report_status));
        $this->session->set_flashdata("success", array("Report Upload successfully."));
        redirect('job-master/job-details/' . $cid);
    }

    function remove_report() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $jid = $this->uri->segment('4');
        $data['query'] = $this->visit_model->master_fun_update("report_master", array("id", $cid), array("status" => "0"));

        $this->session->set_flashdata("success", array("Report successfully Remove"));
        redirect("job-master/job-details/" . $jid, "refresh");
    }

    function download_report($name) {
        $this->load->helper('download');
        $data = file_get_contents(base_url() . "/upload/" . $name); // Read the file's contents

        force_download($name, $data);
    }

    function prescription_report() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();

        $user = $this->input->get('user');
        $date = $this->input->get('date');
        $mobile = $this->input->get('mobile');
        $status = $this->input->get('status');
        $data['customerfk'] = $user;
        $data['date'] = $date;
        $data['mobile'] = $mobile;
        $data['status'] = $status;
        if ($user != "" || $date != "" || $mobile != "" || $status != "") {
            $total_row = $this->visit_model->num_row_srch_prescription($user, $date, $mobile, $status);
            $config = array();
            $get = $_GET;
            unset($get['offset']);
            $config["base_url"] = base_url() . "job-master/prescription-report?" . http_build_query($get);
            $config["total_rows"] = $total_row;
            $config["per_page"] = 10;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->visit_model->row_srch_prescription($user, $date, $mobile, $status, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        } else {
            $total_row = $this->visit_model->num_row_srch_prescription("", "", "", "");
            $config["base_url"] = base_url() . "job-master/prescription-report";
            $config["total_rows"] = $total_row;
            $config["per_page"] = 10;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->visit_model->srch_prescription($config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        }
        $data["per_page"] = $page;
        //$data['cityfk'] = $city;
        //print_r($data["login_data"]); die();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        //$data['query'] = $this->visit_model->prescription_report_search($user, $date, $mobile, $status);
        //$this->load->view('admin/state_list_view', $data);
        $cnt = 0;
        foreach ($data['query'] as $key) {
            if ($key["cid"] == '') {
                $u_dtd = $this->visit_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "mobile" => $key["mobile"]), array("id", "asc"));
                if (!empty($u_dtd)) {
                    $data['query'][$cnt]["cid"] = $u_dtd[0]["id"];
                    $data['query'][$cnt]["full_name"] = $u_dtd[0]["full_name"];
                }
            }
            $cnt++;
        }
        $data['customer'] = $this->visit_model->master_fun_get_tbl_val("customer_master", array('status' => 1), array("full_name", "asc"));
        $data['city'] = $this->visit_model->master_fun_get_tbl_val("city", array('status' => 1), array("id", "asc"));
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('prescription_report', $data);
        $this->load->view('footer');
    }

    function prescription_csv_report() {
        $result = $this->visit_model->prescription_report();
        $data['city'] = $this->visit_model->master_fun_get_tbl_val("city", array('status' => 1), array("id", "asc"));
        $cnt = 0;
        foreach ($result as $key) {
            $w_prc = 0;
            /* Count booked test price */
            $test_city = $this->visit_model->master_fun_get_tbl_val("test_cities", array('id' => $key["city"]), array("id", "asc"));
            $user_info = $this->visit_model->master_fun_get_tbl_val("customer_master", array('id' => $key["cid"]), array("id", "asc"));
            $get_state = $this->visit_model->master_fun_get_tbl_val("state", array('id' => $user_info[0]["state"]), array("id", "asc"));
            $getcity = $this->visit_model->master_fun_get_tbl_val("city", array('id' => $user_info[0]["city"]), array("id", "asc"));
            $p_test = $this->visit_model->master_fun_get_tbl_val("suggested_test", array('p_id' => $key["id"]), array("id", "asc"));
            $tst_name = array();
            foreach ($p_test as $tkey) {
                $test_info = $this->visit_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $test_city[0]["id"] . "' AND `test_master`.`id`='" . $tkey["test_id"] . "'");
                $tst_name[] = $test_info[0]["test_name"] . "(Rs." . $test_info[0]["price"] . ")";
            }

            $result[$cnt]["address"] = $user_info[0]["address"];
            $result[$cnt]["state"] = $get_state[0]["state_name"];
            $result[$cnt]["city"] = $getcity[0]["city_name"];
            $result[$cnt]["test_city"] = $test_city[0]["name"];
            $result[$cnt]["sugested_test"] = implode(",", $tst_name);
            if ($key["status"] == 1) {
                $result[$cnt]["status"] = "Pending";
            } else {
                $result[$cnt]["status"] = "Completed";
            }
            $cnt++;
        }
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"All_Prescription_Report-" . date('d-M-Y') . ".csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("Order Id", "Date", "Customer Name", "Mobile No.", "state", "city", "Address", "Test City", "Status", "Sugested Test"));

        foreach ($result as $key) {
            fputcsv($handle, array($key["order_id"], $key["date"], $key["full_name"], $key["mobile"], $key["state"], $key["city"], $key["address"], $key["test_city"], $key["status"], $key["sugested_test"]));
        }
        fclose($handle);
        exit;
    }

    /* function prescription_details() {
      if (!is_loggedin()) {
      redirect('login');
      }
      $data["login_data"] = logindata();

      $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
      $data['cid'] = $this->uri->segment(3);
      $data['success'] = $this->session->flashdata("success");
      $data['error'] = $this->session->flashdata("error");
      $data['query'] = $this->visit_model->prescription_details($data['cid']);
      $data['test'] = $this->visit_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $data['query'][0]["city"] . "'");
      $data['test_check'] = $this->visit_model->get_suggested_test($data['cid']);
      $data['state'] = $this->visit_model->master_fun_get_tbl_val("state", array('status' => 1), array("state_name", "asc"));
      $data['city'] = $this->visit_model->master_fun_get_tbl_val("test_cities", array('status' => 1), array("name", "asc"));
      $data['city1'] = $this->visit_model->master_fun_get_tbl_val("city", array('status' => 1), array("city_name", "asc"));
      $this->load->view('header');
      $this->load->view('nav', $data);
      $this->load->view('test', $data);
      $this->load->view('footer');
      } */

    function prescription_details() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->model('visit_model');
        $upd = $this->visit_model->master_fun_update("prescription_upload", array("id", $this->uri->segment(3)), array("is_read" => "1"));
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['cid'] = $this->uri->segment(3);
        $data['success'] = $this->session->flashdata("success");
        $data['error'] = $this->session->flashdata("error");
        $data['test_error'] = $this->session->flashdata("test_error");
        $this->load->model('visit_model');
        $data['cid'] = $this->uri->segment(3);
        $this->load->library('form_validation');
        $this->form_validation->set_rules('prescription_fk', 'Job Id', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $test_city = $this->input->post("test_city");
            $customer = $this->input->post("customer");
            if ($customer == 0) {
                $name = $this->input->post("name");
                $phone = $this->input->post("phone");
                $email = $this->input->post("email");
                $password = $this->input->post("password");
                $gender = $this->input->post("gender");
                $address = $this->input->post("address");
                $note = $this->input->post("note");
                $total_amount = $this->input->post("total_amount");
                $c_data = array("full_name" => $name, "gender" => $gender, "email" => $email, "password" => $password, "mobile" => $phone, "address" => $address, "test_city" => $test_city);
                $uid = $this->visit_model->master_fun_insert("customer_master", $c_data);
                $config['mailtype'] = 'html';
                $this->email->initialize($config);
                $this->load->helper("Email");
                $email_cnt = new Email;

                $message = '<div style="padding:0 4%;">
                    <h4><b>Create Account</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your account successfully created. </p>
                        <p style="color:#7e7e7e;font-size:13px;"> Username/Email : . ' . $email . '  </p>  
                        <p style="color:#7e7e7e;font-size:13px;"> Password : ' . $password . '  </p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
                $message = $email_cnt->get_design($message);
                $this->email->to($email);
                $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
                $this->email->subject('Account Created Successfully');
                $this->email->message($message);
                $this->email->send();
                $this->visit_model->master_fun_update("prescription_upload", array("id", $data['cid']), array("cust_fk" => $uid));
            }
            if ($customer == 1) {
                $uid = $this->input->post("userid");
            }
            $upd = $this->visit_model->master_fun_update("prescription_upload", array("id", $this->uri->segment(3)), array("status" => "2"));
            $submit_type = $this->input->post("submit_type");
            if ($submit_type == '1') {
                $id = $this->uri->segment(3);
                $test = $this->input->post('test');
                //$uid = $this->input->post('userid');
                $payable = $this->input->post('payable');
                $discount = $this->input->post('discount');
                $total_amount = $this->input->post('total_amount');
                $order_id = $this->get_job_id($test_city);
                $date = date('Y-m-d H:i:s');
                //$test = explode(',', $test);
                $test_package_name = array();
                /* foreach ($test as $key) {
                  $price1 = $this->visit_model->master_fun_get_tbl_val("test_master", array("status" => 1, "id" => $key), array("test_name", "asc"));
                  $data['test'] = $this->visit_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $data['query'][0]["test_city"] . "'");
                  $price += $price1[0]['price'];
                  $test_package_name[] = $price1[0]['test_name'];
                  } */
                $price = 0;
                foreach ($test as $key) {
                    $tn = explode("-", $key);
                    if ($tn[0] == 't') {
                        $result = $this->visit_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $test_city . "' AND `test_master`.`id`='" . $tn[1] . "'");
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
                    "order_id" => $order_id,
                    "cust_fk" => $uid,
                    "date" => $date,
                    "price" => $total_amount,
                    "status" => '6',
                    "payment_type" => "Cash On Delivery",
                    "discount" => $discount,
                    "payable_amount" => $payable,
                    "test_city" => $test_city,
                    "note" => $this->input->post('note'),
                    "added_by" => $data["login_data"]["id"]
                );
                $insert = $this->visit_model->master_fun_insert("job_master", $data);
                foreach ($test as $key) {
                    $tn = explode("-", $key);
                    if ($tn[0] == 't') {
                        $this->visit_model->master_fun_insert("job_test_list_master", array('job_fk' => $insert, "test_fk" => $tn[1]));
                    }
                    if ($tn[0] == 'p') {
                        $this->visit_model->master_fun_insert("book_package_master", array("cust_fk" => $uid, "package_fk" => $tn[1], 'job_fk' => $insert, "status" => "1", "type" => "2"));
                    }
                }
                $destail = $this->visit_model->master_fun_get_tbl_val("customer_master", array("status" => 1, "id" => $uid), array("id", "asc"));
                $config['mailtype'] = 'html';
                $this->email->initialize($config);
                $message = '<div style="padding:0 4%;">
                    <h4><b>Dear </b>' . $destail[0]['full_name'] . '</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Booking successfully. </p>
                        <p style="color:#7e7e7e;font-size:13px;"> You Booked : . ' . implode($test_package_name, ', ') . '  </p>  
                        <p style="color:#7e7e7e;font-size:13px;"> Your Booked Amount is Rs. ' . $price . '  </p>
                        <p style="color:#7e7e7e;font-size:13px;"> Payment Type : Cash on Blood Collection</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
                $message = $email_cnt->get_design($message);
                $this->email->to($destail[0]['email']);
                $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
                $this->email->subject('Test Book Successfully');
                $this->email->message($message);
                $this->email->send();

                $this->session->set_flashdata("success", array("Booked Successfully"));
                redirect("job_master/prescription_report", "refresh");
            }
            if ($submit_type == '0') {
                $data["login_data"] = logindata();
                $this->load->model("user_model");
                $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
                $cid = $this->uri->segment('3');
                $test = $this->input->post('test');
                $desc = $this->input->post('desc');
                $update = $this->visit_model->master_fun_update_multi("suggested_test", array('p_id' => $cid), array("status" => 0));
                $price = 0;
                $test_name_mail = array();
                foreach ($test as $key) {
                    $tn = explode("-", $key);
                    if ($tn[0] == 't') {
                        //$this->visit_model->master_fun_insert("job_test_list_master", array('job_fk' => $insert, "test_fk" => $tn[1]));
                        $result = $this->visit_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $test_city . "' AND `test_master`.`id`='" . $tn[1] . "'");
                        $price += $result[0]["price"];
                        $test_name_mail[] = $result[0]["test_name"];
                        $data = array(
                            "p_id" => $cid,
                            "test_id" => $tn[1]
                                //"description" => $desc[$i]
                        );
                        $chk = $this->visit_model->master_fun_get_tbl_val("suggested_test", array('status' => 1, 'p_id' => $cid, 'test_id' => $tn[1]), array("id", "asc"));
                        $test_check = $this->visit_model->master_fun_get_tbl_val("suggested_test", array('status' => 1, 'p_id' => $cid), array("id", "asc"));
                        $this->visit_model->master_fun_insert("suggested_test", $data);
                        $upd = $this->visit_model->master_fun_update("prescription_upload", array("id", $cid), array("status" => "2", "discount" => $this->input->post("discount"), "city" => $this->input->post("test_city")));
                    }
                    if ($tn[0] == 'p') {
                        $this->visit_model->master_fun_insert("book_package_master", array("cust_fk" => $uid, "package_fk" => $tn[1], 'job_fk' => $insert, "status" => "1", "type" => "2"));
                        $query = $this->db->get_where('package_master_city_price', array('package_fk' => $tn[1], "city_fk" => $test_city));
                        $result = $query->result();
                        $query1 = $this->db->get_where('package_master', array('id' => $tn[1]));
                        $result1 = $query1->result();
                        $price += $result[0]->d_price;
                        $test_name_mail[] = $result1[0]->title;
                    }
                }
                $data = $this->visit_model->master_fun_get_tbl_val("prescription_upload", array("id" => $cid), array("id", "asc"));
                $cust_fk = $data[0]['cust_fk'];
                $img = $data[0]['image'];
                $desc = $data[0]['description'];
                $orderid = $data[0]['order_id'];
                $created_date = $data[0]['created_date'];
                $data1 = $this->visit_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "id" => $cust_fk), array("id", "asc"));

                $device_id = $data1[0]['device_id'];
                $mobile = $data1[0]['mobile'];
                $name = $data1[0]['full_name'];
                $email = $data1[0]['email'];
                $device_type = $data1[0]['device_type'];
                $message = "Your Suggested Test has been Generated";

                if ($device_type == 'android') {
                    $notification_data = array("title" => "AirmedLabs", "message" => $message, "type" => "suggested_test", "id" => $cid, "img" => $img, "desc" => $desc, "created_date" => $created_date, "order_id" => $orderid);
                    //print_r($notification_data); die();
                    $pushServer = new PushServer();
                    $pushServer->pushToGoogle($device_id, $notification_data);
                    //print_r($result);
                }
                if ($device_type == 'ios') {
                    $url = 'http://website-demo.in/patholabpush/push.php?device_id=' . $device_id . '&msg=' . $message . '&type=suggested_test&testid=&testname=&testprice=&testdate=&id=' . $cid . '&desc=' . $desc . '&date=' . $created_date . '&orderid=' . $orderid . '&img=' . $img;
                    $url = str_replace(" ", "%20", $url);
                    $data = $this->get_content($url);

                    $data2 = json_decode($data);
                }
                /* Nishit send sms code start */
                $sms_message = $this->visit_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "Suggested_Test_Generated"), array("id", "asc"));
                $sms_message = preg_replace("/{{NAME}}/", ucfirst($data1[0]["full_name"]), $sms_message[0]["message"]);
                $sms_message = preg_replace("/{{OID}}/", $orderid, $sms_message);
                $sms_message = preg_replace("/{{PRICE}}/", '', $sms_message);
                $this->load->helper("sms");
                $notification = new Sms();
                $notification::send($mobile, $sms_message);
                /* Nishit send sms code end */
                $config['mailtype'] = 'html';
                $pathToUploadedFile = base_url() . "upload/" . $img;
                $this->email->initialize($config);

                $message1 = '<div style="padding:0 4%;">
                    <h4><b>Dear ' . $name . '</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Suggested Test has been Generated. </p>
						 <p style="color:#7e7e7e;font-size:13px;">Your Suggested Test are ' . implode($test_name_mail, ', ') . ' </p>
                        <p style="color:#7e7e7e;font-size:13px;">Your Order ID : ' . $orderid . '</p>    
		<p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
                $message1 = $email_cnt->get_design($message1);
                $this->email->to($email);
                $this->email->from($this->config->item('admin_booking_email'), "AirmedLabs");
                $this->email->subject("Suggested Test has been Generated");
                $this->email->message($message1);
                $this->email->send();

                $this->session->set_flashdata("success", array("Test Suggested successfully"));
                redirect("job_master/prescription_report", "refresh");
            }
        }

        $data['query'] = $this->visit_model->prescription_details($data['cid']);
        $data['test'] = $this->visit_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $data['query'][0]["city"] . "'");
        $data['test_info'] = $this->visit_model->get_suggested_test($data['cid']);
        $data['state'] = $this->visit_model->master_fun_get_tbl_val("state", array('status' => 1), array("state_name", "asc"));
        $data['city'] = $this->visit_model->master_fun_get_tbl_val("test_cities", array('status' => 1), array("name", "asc"));
        $data['user_info'] = array();
        if ($data['query'][0]["cust_fk"] != '') {
            $data['user_info'] = $this->visit_model->master_fun_get_tbl_val("customer_master", array('id' => $data['query'][0]["cust_fk"]), array("id", "asc"));
        }
        $data['test_cities'] = $this->visit_model->master_fun_get_tbl_val("test_cities", array('status' => 1), array("id", "asc"));
        if ($data['query'][0]["city"] == null) {
            $data['query'][0]["city"] = 1;
        }

        if ($data['query'][0]["job_fk"]) {
            $data['book_test_details'] = $this->visit_model->master_fun_get_tbl_val("job_master", array('id' => $data['query'][0]["job_fk"]), array("id", "asc"));
            $data["n_discount"] = $data['book_test_details'][0]["discount"];
        } else {
            $data["n_discount"] = $data['query'][0]["discount"];
        }
        //echo $data["n_discount"];
        //print_r($data['query']); die();
        //print_r($data['query']); die();
        $data['test'] = $this->visit_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $data['query'][0]["city"] . "'");
        /* $data["package"] = $this->visit_model->get_val("SELECT 
          `package_master`.*,
          `package_master_city_price`.`a_price` AS `a_price`,
          `package_master_city_price`.`d_price` AS `d_price`
          FROM
          `package_master`
          INNER JOIN `package_master_city_price`
          ON `package_master`.`id` = `package_master_city_price`.`package_fk`
          WHERE `package_master`.`status` = '1'
          AND `package_master_city_price`.`status` = '1' AND `package_master_city_price`.`city_fk` = '" . $data['query'][0]["city"] . "' "); */
        $this->visit_model->master_fun_update("prescription_upload", array("id", $this->uri->segment(3)), array("is_read" => "1"));
        $data['unread'] = $this->visit_model->master_fun_get_tbl_val("prescription_upload", array('status' => 1, "is_read" => "0"), array("id", "asc"));
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('prescription_details_new', $data);
        $this->load->view('footer');
    }

    function get_test_list() {
        $ids = $this->input->post('ids');
        $cnt = 0;
        /* foreach ($ids as $key) {
          $nw = explode("-", $key);
          $ids[$cnt] = $nw[1];
          $cnt++;
          }
          $id = implode(",", $ids); */
        $testid = array();
        $packageid = array();
        foreach ($ids as $key) {
            $tn = explode("-", $key);
            if ($tn[0] == 't') {
                $testid[$cnt] = $tn[1];
            }
            if ($tn[0] == 'p') {
                $packageid[$cnt] = $tn[1];
            }
            $cnt++;
        }
        if (!empty(testid)) {
            $id = implode(",", $testid);
        }
        if (!empty($packageid)) {
            $pids = implode(",", $packageid);
        }
        $pds = $this->input->post('pid');
        $ctid = $this->input->post('ctid');
        $data['query'] = $this->visit_model->prescription_details($pds);
        if ($data['query'][0]["city"] == '') {
            $data['query'][0]["city"] = 1;
        } else {
            $data['query'][0]["city"] = $ctid;
        }
        if ($ids != NULL) {
            if ($id != NULL) {
                $test = $this->visit_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND test_master.`id` NOT IN ($id) AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $data['query'][0]["city"] . "'");
            } else {
                $test = $this->visit_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $data['query'][0]["city"] . "'");
            }
            if ($pids != NULL) {
                $package = $this->visit_model->get_val("SELECT 
          `package_master`.*,
          `package_master_city_price`.`a_price` AS `a_price`,
          `package_master_city_price`.`d_price` AS `d_price`
          FROM
          `package_master`
          INNER JOIN `package_master_city_price`
          ON `package_master`.`id` = `package_master_city_price`.`package_fk`
          WHERE `package_master`.`status` = '1'
          AND `package_master_city_price`.`status` = '1' AND package_master.`id` NOT IN ($pids) AND `package_master_city_price`.`city_fk` = '" . $data['query'][0]["city"] . "' ");
            } else {
                $package = $this->visit_model->get_val("SELECT 
          `package_master`.*,
          `package_master_city_price`.`a_price` AS `a_price`,
          `package_master_city_price`.`d_price` AS `d_price`
          FROM
          `package_master`
          INNER JOIN `package_master_city_price`
          ON `package_master`.`id` = `package_master_city_price`.`package_fk`
          WHERE `package_master`.`status` = '1'
          AND `package_master_city_price`.`status` = '1' AND `package_master_city_price`.`city_fk` = '" . $data['query'][0]["city"] . "' ");
            }
        } else {
            $test = $this->visit_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $data['query'][0]["city"] . "'");
            $package = $this->visit_model->get_val("SELECT 
          `package_master`.*,
          `package_master_city_price`.`a_price` AS `a_price`,
          `package_master_city_price`.`d_price` AS `d_price`
          FROM
          `package_master`
          INNER JOIN `package_master_city_price`
          ON `package_master`.`id` = `package_master_city_price`.`package_fk`
          WHERE `package_master`.`status` = '1'
          AND `package_master_city_price`.`status` = '1' AND `package_master_city_price`.`city_fk` = '" . $data['query'][0]["city"] . "' ");
        }
        echo '<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
<script  type="text/javascript">
                                jQuery(".chosen-select").chosen({
                                    search_contains: true
                                });
				/*$("#exampleModal").modal("show");*/
				$("#show_test_btn").attr("style","display:none;");
                            </script>';
        echo '<select class="chosen-select" data-live-search="true" id="test" data-placeholder="Select Test">
			<option value="">--Select Test--</option>';
        foreach ($test as $ts) {
            echo ' <option value="t-' . $ts['id'] . '"> ' . ucfirst($ts['test_name']) . ' (Rs.' . $ts['price'] . ')</option>';
        }
        /* foreach ($package as $pk) {
          echo ' <option value="p-' . $pk['id'] . '"> ' . ucfirst($pk['title']) . ' (Rs.' . $pk['d_price'] . ')</option>';
          } */
        echo '</select>';
        //echo "<pre>"; print_r($data['test']);
    }

    function get_test($city) {
        if (!is_loggedin()) {
            redirect('login');
        }
        if ($city != "") {
            $testdetils = $this->visit_model->get_testval($city);
            $json_array = array();

            foreach ($testdetils as $teval) {

                $lable['testid'] = $teval['test_fk'];
                $lable['testname'] = $teval['test_name'];
                $lable['testprice'] = $teval['price'];
                array_push($json_array, $lable);
            } echo json_encode($json_array);
        } else {
            show_404();
        }
    }

    function suggest_test() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->helper("Email");
        $email_cnt = new Email;

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $test = $this->input->post('test');
        //	print_r($test); die();
        $desc = $this->input->post('desc');
        $update = $this->visit_model->master_fun_update_multi("suggested_test", array('p_id' => $cid), array("status" => 0));
        for ($i = 0; $i < count($test); $i++) {
            $data = array(
                "p_id" => $cid,
                "test_id" => $test[$i],
                "description" => $desc[$i]
            );
            $chk = $this->visit_model->master_fun_get_tbl_val("suggested_test", array('status' => 1, 'p_id' => $cid, 'test_id' => $test[$i]), array("id", "asc"));
            $test_check = $this->visit_model->master_fun_get_tbl_val("suggested_test", array('status' => 1, 'p_id' => $cid), array("id", "asc"));
            $insert = $this->visit_model->master_fun_insert("suggested_test", $data);
            $upd = $this->visit_model->master_fun_update("prescription_upload", array("id", $cid), array("status" => "2"));
        }
        $test_name_mail = array();
        for ($i = 0; $i < count($test); $i++) {
            $data = $this->visit_model->master_fun_get_tbl_val("test_master", array("status" => 1, 'id' => $test[$i]), array("id", "asc"));
            $price = $price + $data[0]['price'];
            $test_name_mail[$i] = $data[0]['test_name'];
        }

        $data = $this->visit_model->master_fun_get_tbl_val("prescription_upload", array("id" => $cid), array("id", "asc"));
        $cust_fk = $data[0]['cust_fk'];
        $img = $data[0]['image'];
        $desc = $data[0]['description'];
        $orderid = $data[0]['order_id'];
        $created_date = $data[0]['created_date'];
        $data1 = $this->visit_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "id" => $cust_fk), array("id", "asc"));

        $device_id = $data1[0]['device_id'];
        $mobile = $data1[0]['mobile'];
        $name = $data1[0]['full_name'];
        $email = $data1[0]['email'];
        $device_type = $data1[0]['device_type'];
        $message = "Your Suggested Test has been Generated";

        if ($device_type == 'android') {
            $notification_data = array("title" => "AirmedLabs", "message" => $message, "type" => "suggested_test", "id" => $cid, "img" => $img, "desc" => $desc, "created_date" => $created_date, "order_id" => $orderid);
            //print_r($notification_data); die();
            $pushServer = new PushServer();
            $pushServer->pushToGoogle($device_id, $notification_data);
            //print_r($result);
        }
        if ($device_type == 'ios') {
            $url = 'http://website-demo.in/patholabpush/push.php?device_id=' . $device_id . '&msg=' . $message . '&type=suggested_test&testid=&testname=&testprice=&testdate=&id=' . $cid . '&desc=' . $desc . '&date=' . $created_date . '&orderid=' . $orderid . '&img=' . $img;
            $url = str_replace(" ", "%20", $url);
            $data = $this->get_content($url);

            $data2 = json_decode($data);
        }
        /* Nishit send sms code start */
        $sms_message = $this->visit_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "Suggested_Test_Generated"), array("id", "asc"));
        $sms_message = preg_replace("/{{NAME}}/", ucfirst($data1[0]["full_name"]), $sms_message[0]["message"]);
        $sms_message = preg_replace("/{{OID}}/", $orderid, $sms_message);
        $sms_message = preg_replace("/{{PRICE}}/", '', $sms_message);
        $this->load->helper("sms");
        $notification = new Sms();
        $notification::send($mobile, $sms_message);
        /* Nishit send sms code end */
        $config['mailtype'] = 'html';
        $pathToUploadedFile = base_url() . "upload/" . $img;
        $this->email->initialize($config);

        $message1 = '<div style="padding:0 4%;">
                    <h4><b>Dear, ' . $name . '</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Suggested Test has been Generated. </p>
						 <p style="color:#7e7e7e;font-size:13px;">Your Suggested Test are ' . implode($test_name_mail, ', ') . ' </p>
                        <p style="color:#7e7e7e;font-size:13px;">Your Order ID : ' . $orderid . '</p>    
		<p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
        $message1 = $email_cnt->get_design($message1);
        $this->email->to($email);
        $this->email->from($this->config->item('admin_booking_email'), "AirmedLabs");
        $this->email->subject("Suggested Test has been Generated");
        $this->email->message($message1);
        $this->email->send();

        $this->session->set_flashdata("success", array("Test Suggested Successfully"));
        redirect("job-master/prescription-details/" . $cid, "refresh");
    }

    function Package_test_inquiry_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        //print_r($data["login_data"]); die();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        //$data['query'] = $this->visit_model->package_test_inquiry();
        $mobile = $this->input->get('mobile');
        $package = $this->input->get('package');
        $date = $this->input->get('date');
        $test = $this->input->get('test');
        $user = $this->input->get('user');
        $status = $this->input->get('status');
        $data['statusid'] = $status;
        $data['mobile'] = $mobile;
        $data['package'] = $package;
        $data['date'] = $date;
        $data['test'] = $test;
        $data['customerfk'] = $user;
        if ($date != "" || $status != "" || $mobile != "" || $package != "" || $test != "" || $user != "") {
            $total_row = $this->visit_model->num_row_srch_pti_list($mobile, $package, $test, $user, $status, $date);
            $config = array();
            $get = $_GET;
            unset($get['offset']);
            $config["base_url"] = base_url() . "job_master/Package_test_inquiry_list?" . http_build_query($get);
            $config["total_rows"] = $total_row;
            $config["per_page"] = 10;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->visit_model->row_srch_pti_list($mobile, $package, $test, $user, $status, $date, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        } else {
            $total_row = $this->visit_model->num_srch_pti_list();
            $config["base_url"] = base_url() . "job_master/Package_test_inquiry_list";
            $config["total_rows"] = $total_row;
            $config["per_page"] = 10;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next <span class="icon-text">&#59230;</span>';
            $config['prev_link'] = '<span class="icon-text">&#59229;</span> Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->visit_model->srch_pti_list($config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        }
        $data["page"] = $page;
        //print_r($data['query']); die();
        $data['customer'] = $this->visit_model->master_fun_get_tbl_val("customer_master", array("status" => "1"));
        $data['package_list'] = $this->visit_model->master_fun_get_tbl_val("package_master", array("status" => "1"));
        $data['test_list'] = $this->visit_model->master_fun_get_tbl_val("test_master", array("status" => "1"));
        //$this->load->view('admin/state_list_view', $data);
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('package_test_inquiry_list', $data);
        $this->load->view('footer');
    }

    function inquiry_csv_report() {
        $result = $this->visit_model->inquiry_csv_report();
        /* $data['city'] = $this->visit_model->master_fun_get_tbl_val("city", array('status' => 1), array("id", "asc")); */
        $cnt = 0;
        foreach ($result as $key) {
            $w_prc = 0;
            /* Count booked test price */
            $user_info = $this->visit_model->master_fun_get_tbl_val("customer_master", array('id' => $key["uid"]), array("id", "asc"));
            $get_state = $this->visit_model->master_fun_get_tbl_val("state", array('id' => $user_info[0]["state"]), array("id", "asc"));
            $getcity = $this->visit_model->master_fun_get_tbl_val("city", array('id' => $user_info[0]["city"]), array("id", "asc"));
            $result[$cnt]["address"] = $user_info[0]["address"];
            $result[$cnt]["state"] = $get_state[0]["state_name"];
            $result[$cnt]["city"] = $getcity[0]["city_name"];
            if ($key["status"] == 1) {
                $result[$cnt]["status"] = "Pending";
            } else {
                $result[$cnt]["status"] = "Completed";
            }
            $cnt++;
        }
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"Inquiry_Report-" . date('d-M-Y') . ".csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("Mobile No.", "Date", "Inquiry Package", "Inquiry Test", "Customer Name", "state", "city", "Address", "Status"));

        foreach ($result as $key) {
            fputcsv($handle, array($key["mobile"], $key["date"], $key["packagename"], $key["testname"], $key["full_name"], $key["state"], $key["city"], $key["address"], $key["status"]));
        }
        fclose($handle);
        exit;
    }

    function check_phone() {
        $data = array("status" => "0", "count" => "0", "id" => "");
        if (is_loggedin()) {
            $mobile = $this->input->get_post("mobile");
            $user_data = $this->visit_model->master_fun_get_tbl_val("customer_master", array("status" => "1", "mobile" => $mobile));
            $data = array("status" => "1", "count" => count($user_data), "id" => $user_data[0]["id"]);
        }
        echo json_encode($data);
    }

    function contact_pending() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->visit_model->master_fun_update("book_without_login", array("id", $cid), array("status" => "1"));

        $this->session->set_flashdata("success", array("Pending successfully"));
        redirect("job_master/Package_test_inquiry_list", "refresh");
    }

    function contact_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->visit_model->master_fun_update("book_without_login", array("id", $cid), array("status" => "0"));

        $this->session->set_flashdata("success", array("Inquiry  Deleted Successfully"));
        redirect("job_master/Package_test_inquiry_list", "refresh");
    }

    function contact_complete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->visit_model->master_fun_update("book_without_login", array("id", $cid), array("status" => "2"));

        $this->session->set_flashdata("success", array("Completed successfully"));
        redirect("job_master/Package_test_inquiry_list", "refresh");
    }

    function book_by_admin() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->helper("Email");
        $email_cnt = new Email;

        $data["login_data"] = logindata();
        $test = $this->input->get_post('test_fk');
        $package = $this->input->get_post('package_fk');
        $uid = $this->input->get_post('uid');
        $id = $this->input->get_post('id');
        $order_id = $this->get_job_id("1");
        $date = date('Y-m-d H:i:s');
        $test = explode(',', $test);
        $package = explode(',', $package);
        $test_package_name = array();
        foreach ($test as $key) {
            /*    $data['test'] = $this->visit_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $data['query'][0]["city"] . "'");
              $data["package"] = $this->visit_model->get_val("SELECT
              `package_master`.*,
              `package_master_city_price`.`a_price` AS `a_price`,
              `package_master_city_price`.`d_price` AS `d_price`
              FROM
              `package_master`
              INNER JOIN `package_master_city_price`
              ON `package_master`.`id` = `package_master_city_price`.`package_fk`
              WHERE `package_master`.`status` = '1'
              AND `package_master_city_price`.`status` = '1' AND `package_master_city_price`.`city_fk` = '" . $data['query'][0]["city"] . "' "); */
            $price1 = $this->visit_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='1' AND `test_master`.`id`='" . $key . "'");
            $price += $price1[0]['price'];
            $test_package_name[] = $price1[0]['test_name'];
        }
        foreach ($package as $key) {
            $price1 = $this->visit_model->get_val("SELECT 
          `package_master`.*,
          `package_master_city_price`.`a_price` AS `a_price`,
          `package_master_city_price`.`d_price` AS `d_price`
          FROM
          `package_master`
          INNER JOIN `package_master_city_price`
          ON `package_master`.`id` = `package_master_city_price`.`package_fk`
          WHERE `package_master`.`status` = '1'
          AND `package_master_city_price`.`status` = '1' AND `package_master_city_price`.`city_fk` = '1' and `package_master`.`id`='" . $key . "' ");
            //$price1 = $this->user_test_master_model->master_fun_get_tbl_val("package_master", array("status" => 1, "id" => $key), array("title", "asc"));
            $price += $price1[0]['d_price'];
            $test_package_name[] = $price1[0]['title'];
        }

        //echo $price ; die();
        // Add in job master 
        $data = array(
            "order_id" => $order_id,
            "cust_fk" => $uid,
            "date" => $date,
            "price" => $price,
            "status" => '1',
            "payment_type" => "Cash On Delivery",
            "payable_amount" => $price,
            "test_city" => "1",
            "added_by" => $data["login_data"]["id"]
        );
        //print_r($data); die();
        $insert = $this->user_test_master_model->master_fun_insert("job_master", $data);
        foreach ($test as $key) {

            $this->user_test_master_model->master_fun_insert("job_test_list_master", array("job_fk" => $insert, "test_fk" => $key));
        }
        foreach ($package as $key) {
            $this->user_test_master_model->master_fun_insert("book_package_master", array("job_fk" => $insert, "date" => $date, "order_id" => $order_id, "package_fk" => $key, "cust_fk" => $uid, "type" => "2"));
        }
        $query = $this->visit_model->master_fun_update("book_without_login", array("id", $id), array("status" => "2"));

        $destail = $this->user_test_master_model->master_fun_get_tbl_val("customer_master", array("status" => 1, "id" => $uid), array("id", "asc"));

        $config['mailtype'] = 'html';
        $this->email->initialize($config);

        $message = '<div style="padding:0 4%;">
                    <h4><b>Dear </b>' . $destail[0]['full_name'] . '</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Booking successfully. </p>
                     <p style="color:#7e7e7e;font-size:13px;"> You Booked : . ' . implode($test_package_name, ', ') . '  </p>  
<p style="color:#7e7e7e;font-size:13px;"> Your Booked Amount is Rs. ' . $price . '  </p>
        <p style="color:#7e7e7e;font-size:13px;"> Payment Type : Cash on Blood Collection</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
        $message = $email_cnt->get_design($message);
        $this->email->to($destail[0]['email']);
        $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
        $this->email->subject('Test Book Successfully');
        $this->email->message($message);
        $this->email->send();

        $this->session->set_flashdata("success", array("Booked successfully"));
        redirect("job_master/Package_test_inquiry_list", "refresh");
    }

    function get_price() {

        $id = $this->input->post(ids);

        foreach ($id as $key) {
            $price1 = $this->user_test_master_model->master_fun_get_tbl_val("test_master", array("status" => 1, "id" => $key), array("test_name", "asc"));
            $price += $price1[0]['price'];
        }
        echo $price;
    }

    public function book_from_prescription() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $id = $this->uri->segment(3);
        $test = $this->input->post('testids');
        $uid = $this->input->post('userid');
        $payable = $this->input->post('payable');
        $discount = $this->input->post('discount');
        $order_id = $this->get_job_id();

        $date = date('Y-m-d H:i:s');
        $test = explode(',', $test);
        $test_package_name = array();
        foreach ($test as $key) {

            $price1 = $this->user_test_master_model->master_fun_get_tbl_val("test_master", array("status" => 1, "id" => $key), array("test_name", "asc"));
            $price += $price1[0]['price'];
            $test_package_name[] = $price1[0]['test_name'];
        }
        $data = array(
            "order_id" => $order_id,
            "cust_fk" => $uid,
            "date" => $date,
            "price" => $price,
            "status" => '1',
            "payment_type" => "Cash On Delivery",
            "discount" => $discount,
            "payable_amount" => $payable,
        );
        $insert = $this->user_test_master_model->master_fun_insert("job_master", $data);
        $this->visit_model->master_fun_insert("job_log", array("job_fk" => $insert, "created_by" => $data["login_data"]["id"], "updated_by" => "", "deleted_by" => "", "message_fk" => "6", "date_time" => date("Y-m-d H:i:s")));
        foreach ($test as $key) {

            $this->user_test_master_model->master_fun_insert("job_test_list_master", array("job_fk" => $insert, "test_fk" => $key));
        }

        $destail = $this->user_test_master_model->master_fun_get_tbl_val("customer_master", array("status" => 1, "id" => $uid), array("id", "asc"));

        $config['mailtype'] = 'html';
        $this->email->initialize($config);
        $this->load->helper("Email");
        $email_cnt = new Email;

        $message = '<div style="padding:0 4%;">
                    <h4><b>Dear </b>' . $destail[0]['full_name'] . '</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Booking successfully. </p>
                     <p style="color:#7e7e7e;font-size:13px;"> You Booked : . ' . implode($test_package_name, ', ') . '  </p>  
<p style="color:#7e7e7e;font-size:13px;"> Your Booked Amount is Rs. ' . $payable . '  </p>
        <p style="color:#7e7e7e;font-size:13px;"> Payment Type : Cash on Blood Collection</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
        $message = $email_cnt->get_design($message);
        $this->email->to($destail[0]['email']);
        $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
        $this->email->subject('Test Book Successfully');
        $this->email->message($message);
        $this->email->send();

        $this->session->set_flashdata("success", array("Booked successfully"));
        redirect("job-master/prescription-details/" . $id, "refresh");
    }

    function all_pushnotification() {

        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $message = $this->input->post('desc');

        if ($this->session->userdata('unsuccess') != null) {
            $data['unsuccess'] = $this->session->userdata("unsuccess");
            $this->session->unset_userdata('unsuccess');
        }
        $data['success'] = $this->session->flashdata("success");

        $this->form_validation->set_rules('desc', 'Message', 'trim|required');

        if ($this->form_validation->run() == FALSE) {

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('pushnotification_view', $data);
            $this->load->view('footer');
        } else {

            $data1 = $this->visit_model->master_fun_get_tbl_val("customer_master", array('status' => 1), array("id", "asc"));
//	$ctn = 1 ;
            foreach ($data1 as $key) {
                $device_id = $key['device_id'];
                $mobile = $key['mobile'];
                $name = $key['full_name'];
                $email = $key['email'];
                $device_type = $key['device_type'];
                //$message = "this is developer test";

                if ($device_type == 'android') {
                    $notification_data = array("title" => "AirmedLabs", "message" => $message, "type" => "home");
                    //print_r($notification_data); die();
                    $pushServer = new PushServer();
                    $pushServer->pushToGoogle($device_id, $notification_data);
                    //print_r($result);
                }
                if ($device_type == 'ios') {
                    $url = 'http://website-demo.in/patholabpush/push.php?device_id=' . $device_id . '&msg=' . $message . '&type=home&testid=&testname=&testprice=&testdate=';
                    $url = str_replace(" ", "%20", $url);
                    $data = $this->get_content($url);

                    $data2 = json_decode($data);
                }
            }
            $this->session->set_flashdata("success", array("Notification sent successfully"));
            redirect("job_master/all_pushnotification/", "refresh");
        }
    }

    function pending_count() {

        $data = $this->visit_model->pending_job_count();
        $data1 = $this->visit_model->instant_contact_count();
        $data2 = $this->visit_model->all_inquiry_count();
        $package_count = $this->visit_model->master_num_rows('instant_contact', array("status" => '1'));
        $allticket = $this->visit_model->master_num_rows('ticket_master', array("views" => '0', "status" => '1'));
        $jobs = $this->visit_model->master_num_rows('job_master', array("views" => '0', "status !=" => '0'));
        $contact_us = $this->visit_model->master_num_rows('contact_us', array("views" => '0', "status" => '1'));
        $prescription_upload = $this->visit_model->master_num_rows('prescription_upload', array("is_read" => '0', "status !=" => '0'));
        $job_total = $data->total;
        $all_inquiry_total = $data2->total;
        $all_total = $package_total + $all_inquiry_total + $contact_us;
        $myarray = array("job_count" => $jobs, "inquiry_total" => $all_inquiry_total + $package_count, "all_inquiry" => $all_inquiry_total, "package_inquiry" => $package_count, "all_total" => $all_total, "tickepanding" => $allticket, "contact_us_count" => $contact_us, "prescription_upload" => $prescription_upload);
        echo $json = json_encode($myarray);
    }

    function get_content($URL) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $URL);
        $data = curl_exec($ch);
        curl_close($ch);
    }

    function sms_test() {
        $mobile = "09879572294";
        //echo    $mobile = (string)$mobile;
        /* Nishit send sms code start */
        $this->load->helper("sms");
        $notification = new Sms();
        $mb_length = strlen($mobile);
        if ($mb_length == 10) {
            $notification::send($mobile, $message);
        }
        if ($mb_length == 11 || $mb_length == 12 || $mb_length == 13) {
            $check_phone = substr($mobile, 0, 2);
            $check_phone1 = substr($mobile, 0, 1);
            $check_phone2 = substr($mobile, 0, 3);
            if ($check_phone2 == '+91') {
                $get_phone = substr($mobile, 3);
                $notification::send($get_phone, $message);
            }
            if ($check_phone == '91') {
                $get_phone = substr($mobile, 2);
                $notification::send($get_phone, $message);
            }
            if ($check_phone1 == '0') {
                $get_phone = substr($mobile, 1);
                $notification::send($get_phone, $message);
            }
        }
        /* Nishit send sms code end */

        $this->db->close();
        die();
        $this->load->helper("sms");
        $notification = new Sms();
        $notification::send($phone, "This is nishit test.");
    }

    function test() {
        $data = array();
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('test', $data);
        $this->load->view('footer');
    }

    function prescription_submit() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->helper("Email");
        $email_cnt = new Email;

        $this->load->model('visit_model');
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $data['cid'] = $this->uri->segment(3);
        $data['success'] = $this->session->flashdata("success");
        $data['error'] = $this->session->flashdata("error");

        $this->load->model('visit_model');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('prescription_fk', 'Job Id', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $test_city = $this->input->post("test_city");
            $customer = $this->input->post("customer");
            if ($customer == 0) {
                $name = $this->input->post("name");
                $phone = $this->input->post("phone");
                $email = $this->input->post("email");
                $password = $this->input->post("password");
                $gender = $this->input->post("gender");
                $address = $this->input->post("address");
                $total_amount = $this->input->post("total_amount");
                $payable = $this->input->post("payable");
                $note = $this->input->post("note");
                $discount = $this->input->post('discount');
                $c_data = array("full_name" => $name, "gender" => $gender, "email" => $email, "password" => $password, "mobile" => $phone, "address" => $address, "test_city" => $test_city);
                $uid = $this->visit_model->master_fun_insert("customer_master", $c_data);
                $config['mailtype'] = 'html';
                $this->email->initialize($config);

                $message = '<div style="padding:0 4%;">
                    <h4><b>Create Account</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your account successfully created. </p>
                     <p style="color:#7e7e7e;font-size:13px;"> Username/Email : . ' . $email . '  </p>  
<p style="color:#7e7e7e;font-size:13px;"> Password : ' . $password . '  </p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
                $message = $email_cnt->get_design($message);
                $this->email->to($email);
                $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
                $this->email->subject('Account Created Successfully');
                $this->email->message($message);
                $this->email->send();
                $this->visit_model->master_fun_update("prescription_upload", array("id", $data['cid']), array("cust_fk" => $uid));
            }
            if ($customer == 1) {
                $uid = $this->input->post("userid");
            }
            $upd = $this->visit_model->master_fun_update("prescription_upload", array("id", $this->uri->segment(3)), array("status" => "2"));
            $submit_type = $this->input->post("submit_type");
            if ($submit_type == '1') {
                $id = $this->uri->segment(3);
                $test = $this->input->post('test');
                $order_id = $this->get_job_id($test_city);
                $date = date('Y-m-d H:i:s');
                //$test = explode(',', $test);	
                $test_package_name = array();
                $price = 0;
                foreach ($test as $key) {
                    $tn = explode("-", $key);
                    if ($tn[0] == 't') {
                        $result = $this->visit_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $test_city . "' AND `test_master`.`id`='" . $tn[1] . "'");
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
                $payable = $this->input->post('payable');
                $data = array(
                    "order_id" => $order_id,
                    "cust_fk" => $uid,
                    "date" => $date,
                    "price" => $this->input->post("total_amount"),
                    "status" => '6',
                    "payment_type" => "Cash On Delivery",
                    "discount" => $this->input->post('discount'),
                    "payable_amount" => $payable,
                    "test_city" => $test_city,
                    "note" => $this->input->post('note'),
                    "added_by" => $data["login_data"]["id"]
                );
                //print_r($data); die();
                $insert = $this->visit_model->master_fun_insert("job_master", $data);
                $this->visit_model->master_fun_update("prescription_upload", array("id", $this->uri->segment(3)), array("job_fk" => $insert));
                $testid = array();
                $packageid = array();
                foreach ($test as $key) {
                    $tn = explode("-", $key);
                    if ($tn[0] == 't') {
                        $testid[] = $tn[1];
                        $this->visit_model->master_fun_insert("job_test_list_master", array('job_fk' => $insert, "test_fk" => $tn[1]));
                    }
                    if ($tn[0] == 'p') {
                        $packageid[] = $tn[1];
                        $this->visit_model->master_fun_insert("book_package_master", array("cust_fk" => $uid, "package_fk" => $tn[1], 'job_fk' => $insert, "status" => "1", "type" => "2"));
                    }
                }

                /* Nishit send sms start */
                $pid = implode($packageid, ',');
                $tid = implode($testid, ',');
                $sms_message = $this->visit_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "book_login"), array("id", "asc"));
                $user = $this->visit_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "id" => $uid), array("id", "asc"));
                $sms_message = preg_replace("/{{NAME}}/", ucfirst($user[0]['full_name']), $sms_message[0]["message"]);
                $sms_message = preg_replace("/{{MOBILE}}/", ucfirst($user[0]['mobile']), $sms_message);
                if ($pid != '' && $tid != '') {
                    $sms_message = preg_replace("/{{TESTPACK}}/", 'Test/Package', $sms_message);
                } else if ($pid != '') {
                    $sms_message = preg_replace("/{{TESTPACK}}/", 'Package', $sms_message);
                } else {
                    $sms_message = preg_replace("/{{TESTPACK}}/", 'Test', $sms_message);
                }
                $mobile = $user[0]['mobile'];
                $this->load->helper("sms");
                $notification = new Sms();
                if ($mobile != NULL) {
                    $notification->send($mobile, $sms_message);
                }
                // Referral amount for first test book//
                /* Nishit send sms end */

                $destail = $this->visit_model->master_fun_get_tbl_val("customer_master", array("status" => 1, "id" => $uid), array("id", "asc"));
                $config['mailtype'] = 'html';
                $this->email->initialize($config);

                $message = '<div style="padding:0 4%;">
                    <h4><b>Dear </b>' . $destail[0]['full_name'] . '</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Booking successfully. </p>
                     <p style="color:#7e7e7e;font-size:13px;"> You Booked : . ' . implode($test_package_name, ', ') . '  </p>  
<p style="color:#7e7e7e;font-size:13px;"> Your Booked Total Amount is Rs. ' . $this->input->post("total_amount") . '  </p>
    <p style="color:#7e7e7e;font-size:13px;"> Discount is ' . $this->input->post('discount') . '%  </p>
        <p style="color:#7e7e7e;font-size:13px;"> Your Booked Payable Amount is Rs. ' . $payable . '  </p>
        <p style="color:#7e7e7e;font-size:13px;"> Payment Type : Cash on Blood Collection</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
                $message = $email_cnt->get_design($message);
                $this->email->to($destail[0]['email']);
                $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
                $this->email->subject('Test Book Successfully');
                $this->email->message($message);
                $this->email->send();

                $this->session->set_flashdata("success", array("Test Booked Successfully"));
                redirect("job-master/prescription-details/" . $cid, "refresh");
            }
            if ($submit_type == '0') {
                $data["login_data"] = logindata();
                $this->load->model("user_model");
                $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
                $cid = $this->uri->segment('3');
                $test = $this->input->post('test');
                if (count(array_count_values($test)) != count($test)) {
                    $this->session->set_flashdata("test_error", array("Same test are not allowed."));
                    redirect("job-master/prescription-details/$cid");
                }
                $desc = $this->input->post('desc');
                $discount = $this->input->post('discount');
                $this->visit_model->master_fun_update("prescription_upload", array("id", $this->uri->segment(3)), array("discount" => $discount, "city" => $this->input->post('test_city')));
                $update = $this->visit_model->master_fun_update_multi("suggested_test", array('p_id' => $cid), array("status" => 0));
                $price = 0;
                $test_name_mail = array();
                $test_price_mail = array();
                foreach ($test as $key) {
                    $tn = explode("-", $key);
                    if ($tn[0] == 't') {
                        //$this->visit_model->master_fun_insert("job_test_list_master", array('job_fk' => $insert, "test_fk" => $tn[1]));
                        $result = $this->visit_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $test_city . "' AND `test_master`.`id`='" . $tn[1] . "'");
                        $price += $result[0]["price"];
                        $test_price_mail[] = $result[0]["price"];
                        $test_name_mail[] = $result[0]["test_name"];
                        $data = array(
                            "p_id" => $cid,
                            "test_id" => $tn[1]
                                //"description" => $desc[$i]
                        );
                        $chk = $this->visit_model->master_fun_get_tbl_val("suggested_test", array('status' => 1, 'p_id' => $cid, 'test_id' => $tn[1]), array("id", "asc"));
                        $test_check = $this->visit_model->master_fun_get_tbl_val("suggested_test", array('status' => 1, 'p_id' => $cid), array("id", "asc"));
                        $insert = $this->visit_model->master_fun_insert("suggested_test", $data);
                        $upd = $this->visit_model->master_fun_update("prescription_upload", array("id", $cid), array("status" => "2"));
                    }
                    if ($tn[0] == 'p') {
                        $this->visit_model->master_fun_insert("book_package_master", array("cust_fk" => $uid, "package_fk" => $tn[1], 'job_fk' => $insert, "status" => "1", "type" => "2"));
                        $query = $this->db->get_where('package_master_city_price', array('package_fk' => $tn[1], "city_fk" => $test_city));
                        $result = $query->result();
                        $query1 = $this->db->get_where('package_master', array('id' => $tn[1]));
                        $result1 = $query1->result();
                        $price += $result[0]->d_price;
                        $test_price_mail[] = $result[0]->d_price;
                        $test_name_mail[] = $result1[0]->title;
                    }
                }


                $data = $this->visit_model->master_fun_get_tbl_val("prescription_upload", array("id" => $cid), array("id", "asc"));
                $cust_fk = $data[0]['cust_fk'];
                $img = $data[0]['image'];
                $desc = $data[0]['description'];
                $orderid = $data[0]['order_id'];
                $created_date = $data[0]['created_date'];
                $data1 = $this->visit_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "id" => $cust_fk), array("id", "asc"));

                $device_id = $data1[0]['device_id'];
                $mobile = $data1[0]['mobile'];
                $name = $data1[0]['full_name'];
                $email = $data1[0]['email'];
                $device_type = $data1[0]['device_type'];
                $message = "Your Suggested Test has been Generated";

                if ($device_type == 'android') {
                    $notification_data = array("title" => "AirmedLabs", "message" => $message, "type" => "suggested_test", "id" => $cid, "img" => $img, "desc" => $desc, "created_date" => $created_date, "order_id" => $orderid);
                    //print_r($notification_data); die();
                    $pushServer = new PushServer();
                    $pushServer->pushToGoogle($device_id, $notification_data);
                    //print_r($result);
                }
                if ($device_type == 'ios') {
                    $url = 'http://website-demo.in/patholabpush/push.php?device_id=' . $device_id . '&msg=' . $message . '&type=suggested_test&testid=&testname=&testprice=&testdate=&id=' . $cid . '&desc=' . $desc . '&date=' . $created_date . '&orderid=' . $orderid . '&img=' . $img;
                    $url = str_replace(" ", "%20", $url);
                    $data = $this->get_content($url);

                    $data2 = json_decode($data);
                }
                /* Nishit send sms code start */
                $test_with_price = array();
                $tcnt = 0;
                foreach ($test_name_mail as $tkey) {
                    $test_with_price[] = $tkey . "-Rs." . $test_price_mail[$tcnt] . ",\n";
                    $tcnt++;
                }
                $sms_message = $this->visit_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "Suggested_Test_Generated"), array("id", "asc"));
                $sms_message = preg_replace("/{{NAME}}/", ucfirst($data1[0]["full_name"]), $sms_message[0]["message"]);
                $sms_message = preg_replace("/{{OID}}/", $orderid, $sms_message);
                $sms_message = preg_replace("/{{PRICE}}/", '', $sms_message);
                $sms_message = preg_replace("/{{TEST}}/", implode($test_with_price, ''), $sms_message);
                $this->load->helper("sms");
                $notification = new Sms();
                $notification->send($mobile, $sms_message);
                /* Nishit send sms code end */
                $config['mailtype'] = 'html';
                $pathToUploadedFile = base_url() . "upload/" . $img;
                $this->email->initialize($config);
                $message1 = '<div style="padding:0 4%;">
                    <h4><b>Dear </b> ' . $name . '</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Suggested Test has been Generated. </p>
						 <p style="color:#7e7e7e;font-size:13px;">Your Suggested Test are ' . implode($test_name_mail, ', ') . ' </p>
                        <p style="color:#7e7e7e;font-size:13px;">Your Order ID : ' . $orderid . '</p>    
		<p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
                $message1 = $email_cnt->get_design($message1);
                $this->email->to($email);
                $this->email->from($this->config->item('admin_booking_email'), "AirmedLabs");
                $this->email->subject("Suggested Test has been Generated");
                $this->email->message($message1);
                $this->email->send();
                $this->session->set_flashdata("success", array("Test Suggested Successfully"));
                redirect("job-master/prescription-details/" . $cid, "refresh");
            }
        }



        $data['query'] = $this->visit_model->prescription_details($data['cid']);
        $data['test'] = $this->visit_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $data['query'][0]["city"] . "'");
        $data['test_check'] = $this->visit_model->get_suggested_test($data['cid']);
        $data['state'] = $this->visit_model->master_fun_get_tbl_val("state", array('status' => 1), array("state_name", "asc"));
        $data['city'] = $this->visit_model->master_fun_get_tbl_val("test_cities", array('status' => 1), array("name", "asc"));
        $data['user_info'] = array();
        if ($data['query'][0]["cust_fk"] != '') {
            $data['user_info'] = $this->visit_model->master_fun_get_tbl_val("customer_master", array('id' => $data['query'][0]["cust_fk"]), array("id", "asc"));
        }
        $data['test_cities'] = $this->visit_model->master_fun_get_tbl_val("test_cities", array('status' => 1), array("id", "asc"));
        if ($data['query'][0]["city"] == null) {
            $data['query'][0]["city"] = 1;
        }
        $data['test'] = $this->visit_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $data['query'][0]["city"] . "'");
        $data["package"] = $this->visit_model->get_val("SELECT 
    `package_master`.*,
    `package_master_city_price`.`a_price` AS `a_price1`,
    `package_master_city_price`.`d_price` AS `d_price1`
  FROM
    `package_master` 
    INNER JOIN `package_master_city_price` 
      ON `package_master`.`id` = `package_master_city_price`.`package_fk` 
  WHERE `package_master`.`status` = '1' 
    AND `package_master_city_price`.`status` = '1' AND `package_master_city_price`.`city_fk` = '" . $data['query'][0]["city"] . "' ");
        $this->visit_model->master_fun_update("prescription_upload", array("id", $this->uri->segment(3)), array("is_read" => "1"));
        $data['unread'] = $this->visit_model->master_fun_get_tbl_val("prescription_upload", array('status' => 1, "is_read" => "0"), array("id", "asc"));
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('prescription_details_new', $data);
        $this->load->view('footer');
    }

    function get_test_list1() {
        $ids = $this->input->post('ids');
        $cnt = 0;
        /* foreach ($ids as $key) {
          $nw = explode("-", $key);
          $ids[$cnt] = $nw[1];
          $cnt++;
          }
          $id = implode(",", $ids); */
        $testid = array();
        $packageid = array();
        foreach ($ids as $key) {
            $tn = explode("-", $key);
            if ($tn[0] == 't') {
                $testid[$cnt] = $tn[1];
            }
            if ($tn[0] == 'p') {
                $packageid[$cnt] = $tn[1];
            }
            $cnt++;
        }
        if (!empty(testid)) {
            $id = implode(",", $testid);
        }
        if (!empty($packageid)) {
            $pids = implode(",", $packageid);
        }
        $ctid = $this->input->post('ctid');

        if ($ids != NULL) {
            if ($id != NULL) {
                $test = $this->visit_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND test_master.`id` NOT IN ($id) AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $ctid . "'");
            } else {
                $test = $this->visit_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $ctid . "'");
            }
            if ($pids != NULL) {
                $package = $this->visit_model->get_val("SELECT 
          `package_master`.*,
          `package_master_city_price`.`a_price` AS `a_price`,
          `package_master_city_price`.`d_price` AS `d_price`
          FROM
          `package_master`
          INNER JOIN `package_master_city_price`
          ON `package_master`.`id` = `package_master_city_price`.`package_fk`
          WHERE `package_master`.`status` = '1'
          AND `package_master_city_price`.`status` = '1' AND package_master.`id` NOT IN ($pids) AND `package_master_city_price`.`city_fk` = '" . $ctid . "' ");
            } else {
                $package = $this->visit_model->get_val("SELECT 
          `package_master`.*,
          `package_master_city_price`.`a_price` AS `a_price`,
          `package_master_city_price`.`d_price` AS `d_price`
          FROM
          `package_master`
          INNER JOIN `package_master_city_price`
          ON `package_master`.`id` = `package_master_city_price`.`package_fk`
          WHERE `package_master`.`status` = '1'
          AND `package_master_city_price`.`status` = '1' AND `package_master_city_price`.`city_fk` = '" . $ctid . "' ");
            }
        } else {
            $test = $this->visit_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $ctid . "'");
            $package = $this->visit_model->get_val("SELECT 
          `package_master`.*,
          `package_master_city_price`.`a_price` AS `a_price`,
          `package_master_city_price`.`d_price` AS `d_price`
          FROM
          `package_master`
          INNER JOIN `package_master_city_price`
          ON `package_master`.`id` = `package_master_city_price`.`package_fk`
          WHERE `package_master`.`status` = '1'
          AND `package_master_city_price`.`status` = '1' AND `package_master_city_price`.`city_fk` = '" . $ctid . "' ");
        }
        echo '<script type="text/javascript" src="' . base_url() . 'user_assets/chosen/chosen.jquery.js"></script>
<script  type="text/javascript">

                                jQuery(".chosen-select").chosen({
                                    search_contains: true
                                });
								$("#exampleModal").modal("show");
								$("#show_test_btn").attr("disabled",false);
                            </script>

<link href="' . base_url() . 'user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />';
        echo '<select class="chosen-select" data-live-search="true" id="test" data-placeholder="Select Test">
			<option value="">--Select Test--</option>';
        foreach ($test as $ts) {
            echo ' <option value="t-' . $ts['id'] . '"> ' . ucfirst($ts['test_name']) . ' (Rs.' . $ts['price'] . ')</option>';
        }
        foreach ($package as $pk) {
            echo ' <option value="p-' . $pk['id'] . '"> ' . ucfirst($pk['title']) . ' (Rs.' . $pk['d_price'] . ')</option>';
        }
        echo '</select>';
    }

    function assign_phlebo_job() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $login_id = $data["login_data"]["id"];
        $phlebo_id = $this->input->post('phlebo_id');
        $job_id = $this->input->post('job_id');
        $dated = $this->input->post('dated');
        $timed = $this->input->post('timed');
        $address = $this->input->post('address');
        $notify = $this->input->post('notify');
        $sharp_time = $this->input->post('sharp_time');
        if (!empty($sharp_time)) {
            $timed = null;
        }
        /* Nishit code start */
        //$data = array("job_fk" => $job_id, "phlebo_fk" => $phlebo_id, "date" => $dated, "time" => $timed, "address" => $address, "notify_cust" => $notify);
        $job_cnt = $this->visit_model->master_num_rows("phlebo_assign_job", array("status" => "1", "job_fk" => $job_id));
        if ($job_cnt == 0) {
            $data = array("job_fk" => $job_id, "phlebo_fk" => $phlebo_id, "date" => $dated, "time" => $sharp_time, "time_fk" => $timed, "address" => $address, "notify_cust" => $notify, "created_date" => date("Y-m-d H:i:s"), "created_by" => $data["login_data"]["id"]);
            $insert = $this->visit_model->master_fun_insert("phlebo_assign_job", $data);
            $this->visit_model->master_fun_insert("job_log", array("job_fk" => $job_id, "created_by" => "", "updated_by" => $login_id, "deleted_by" => "", "message_fk" => "8", "date_time" => date("Y-m-d H:i:s")));
        } else {
            $data = array("job_fk" => $job_id, "phlebo_fk" => $phlebo_id, "date" => $dated, "time" => $sharp_time, "time_fk" => $timed, "address" => $address, "notify_cust" => $notify, "updated_by" => $data["login_data"]["id"]);
            $insert = $this->visit_model->master_fun_update("phlebo_assign_job", array("job_fk", $job_id), $data);
            $this->visit_model->master_fun_insert("job_log", array("job_fk" => $job_id, "created_by" => "", "updated_by" => $login_id, "deleted_by" => "", "message_fk" => "9", "date_time" => date("Y-m-d H:i:s")));
        }
        /* Nishit code end */
        //$update = $this->visit_model->master_fun_update("phlebo_master", array("id", $phlebo_id), $data);
        $phlebo_details = $this->visit_model->master_fun_get_tbl_val("phlebo_master", array('id' => $phlebo_id), array("id", "asc"));
        $phlebo_job_details = $this->visit_model->master_fun_get_tbl_val("phlebo_assign_job", array('job_fk' => $job_id), array("id", "asc"));
        $job_details = $this->visit_model->master_fun_get_tbl_val("job_master", array('id' => $job_id), array("id", "asc"));
        $customer_details = $this->visit_model->master_fun_get_tbl_val("customer_master", array('id' => $job_details[0]['cust_fk']), array("id", "asc"));
        if ($insert) {
            $family_member_name = $this->visit_model->get_family_member_name($job_id);
            if (!empty($family_member_name)) {
                $c_name = $family_member_name[0]["name"];
                $cmobile = $family_member_name[0]["phone"];
                if (empty($cmobile)) {
                    $cmobile = $customer_details[0]["mobile"];
                }
            } else {
                $c_name = $customer_details[0]["full_name"];
                $cmobile = $customer_details[0]["mobile"];
            }
            $job_details = $this->get_job_details($job_id);
            $b_details = array();
            foreach ($job_details[0]["book_test"] as $bkey) {
                $b_details[] = $bkey["test_name"] . " Rs." . $bkey["price"];
            }
            foreach ($job_details[0]["book_package"] as $bkey) {
                $b_details[] = $bkey["title"] . " Rs." . $bkey["d_price"];
            }
            /* Pinkesh send sms code start */
            $p_time = $this->visit_model->master_fun_get_tbl_val("phlebo_time_slot", array('id' => $timed), array("id", "asc"));
            $s_time = date('h:i a', strtotime($p_time[0]["start_time"]));
            $e_time = date('h:i a', strtotime($p_time[0]["end_time"]));
            $sms_message = $this->visit_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "phlebo_msg"), array("id", "asc"));
            $sms_message = preg_replace("/{{NAME}}/", ucfirst($phlebo_details[0]["name"]), $sms_message[0]["message"]);
            $sms_message = preg_replace("/{{MOBILE}}/", $phlebo_details[0]["mobile"], $sms_message);
            $sms_message = preg_replace("/{{ORDERID}}/", $job_details[0]["order_id"], $sms_message);
            $sms_message = preg_replace("/{{CNAME}}/", $c_name, $sms_message);
            $sms_message = preg_replace("/{{CMOBILE}}/", $cmobile, $sms_message);
            $sms_message = preg_replace("/{{CADDRESS}}/", $phlebo_job_details[0]["address"], $sms_message);
            $sms_message = preg_replace("/{{DATE}}/", $phlebo_job_details[0]["date"], $sms_message);
            $sms_message = preg_replace("/{{BOOKINFO}}/", implode(",", $b_details), $sms_message);
            if (!empty($sharp_time)) {
                $sms_message = preg_replace("/{{TIME}}/", $sharp_time, $sms_message);
            } else {
                $sms_message = preg_replace("/{{TIME}}/", $s_time . " To " . $e_time, $sms_message);
            }
            $job_details = $this->get_job_details($job_id);
            $b_details = array();
            foreach ($job_details[0]["book_test"] as $bkey) {
                $b_details[] = $bkey["test_name"] . " Rs." . $bkey["price"];
            }
            foreach ($job_details[0]["book_package"] as $bkey) {
                $b_details[] = $bkey["title"] . " Rs." . $bkey["d_price"];
            }
            $sms_message = preg_replace("/{{BOOKINFO}}/", implode(",", $b_details), $sms_message);
            //$sms_message="done";
            $mobile = $phlebo_details[0]['mobile'];
            $this->load->helper("sms");
            $notification = new Sms();
            $notification::send($mobile, $sms_message);
            /* Pinkesh send sms code end */

            //if ($notify == 1) {
            /* Pinkesh send sms code start */
            $sms_message = $this->visit_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "phlebo_msg_cust"), array("id", "asc"));
            $sms_message = preg_replace("/{{NAME}}/", ucfirst($c_name), $sms_message[0]["message"]);
            $sms_message = preg_replace("/{{MOBILE}}/", $customer_details[0]["mobile"], $sms_message);
            $sms_message = preg_replace("/{{ORDERID}}/", $job_details[0]["order_id"], $sms_message);
            $sms_message = preg_replace("/{{PNAME}}/", ucfirst($phlebo_details[0]["name"]), $sms_message);
            $sms_message = preg_replace("/{{PMOBILE}}/", $phlebo_details[0]["mobile"], $sms_message);
            $sms_message = preg_replace("/{{DATE}}/", $phlebo_job_details[0]["date"], $sms_message);
            $sms_message = preg_replace("/{{BOOKINFO}}/", implode(",", $b_details), $sms_message);
            if (!empty($sharp_time)) {
                $sms_message = preg_replace("/{{TIME}}/", $sharp_time, $sms_message);
            } else {
                $sms_message = preg_replace("/{{TIME}}/", $s_time . " To " . $e_time, $sms_message);
            }
            $mobile = $customer_details[0]['mobile'];
            //$sms_message="done"; 
            $notification::send($cmobile, $sms_message);
            if (!empty($family_member_name)) {
                $this->visit_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $customer_details[0]["mobile"], "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
            }
            /* Pinkesh send sms code end */
            //}
            echo "1";
        } else {
            echo "0";
        }
    }

    function get_job_details($job_id) {
        $job_details = $this->visit_model->master_fun_get_tbl_val("job_master", array("status !=" => "0", "id" => $job_id), array("id", "asc"));
        if (!empty($job_details)) {
            $book_test = $this->visit_model->master_fun_get_tbl_val("job_test_list_master", array("job_fk" => $job_id), array("id", "desc"));
            $book_package = $this->visit_model->master_fun_get_tbl_val("book_package_master", array("job_fk" => $job_id, "status" => "1"), array("id", "desc"));
            $test_name = array();
            foreach ($book_test as $key) {
                $price1 = $this->visit_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $job_details[0]["test_city"] . "' AND `test_master`.`id`='" . $key["test_fk"] . "'");
                $test_name[] = $price1[0];
            }
            $job_details[0]["book_test"] = $test_name;
            $package_name = array();
            foreach ($book_package as $key) {

                $price1 = $this->visit_model->get_val("SELECT 
          `package_master`.*,
          `package_master_city_price`.`a_price` AS `a_price`,
          `package_master_city_price`.`d_price` AS `d_price`
          FROM
          `package_master`
          INNER JOIN `package_master_city_price`
          ON `package_master`.`id` = `package_master_city_price`.`package_fk`
          WHERE `package_master`.`status` = '1'
          AND `package_master_city_price`.`status` = '1' AND `package_master_city_price`.`city_fk` = '" . $job_details[0]["test_city"] . "' AND `package_master`.`id`='" . $key["package_fk"] . "'");
                $package_name[] = $price1[0];
            }
            $job_details[0]["book_package"] = $package_name;
        }
        return $job_details;
    }

    function get_job_log($jid) {
        $job_log = $this->visit_model->master_fun_get_tbl_val("job_log", array('status' => 1, "job_fk" => $jid), array("id", "asc"));
        if (!empty($job_log)) {
            echo "<hr>";
            foreach ($job_log as $key) {
                $message = $this->visit_model->master_fun_get_tbl_val("job_log_message", array('status' => 1, "id" => $key["message_fk"]), array("id", "asc"));

                if ($key["created_by"] != 0) {
                    $AID = $key["created_by"];
                }
                if ($key["updated_by"] != 0) {
                    $AID = $key["updated_by"];
                }
                if ($key["deleted_by"] != 0) {
                    $AID = $key["deleted_by"];
                }
                $admin_details = $this->visit_model->master_fun_get_tbl_val("admin_master", array('status' => 1, "id" => $AID), array("id", "asc"));
                $message = $message[0]["message"];
                $originalDate = $key["date_time"];
                $newDate = date("d-M-Y g:i A", strtotime($originalDate));
                $message = preg_replace("/{{ANAME}}/", "<b>" . ucfirst($admin_details[0]["name"]) . "</b>", $message);
                $message = preg_replace("/{{DATE}}/", $newDate, $message);
                if ($key["message_fk"] == 3) {

                    /* NISHIT Status check start */
                    $jobs_fk = explode("-", $key["job_status"]);
                    if ($jobs_fk[0] == 1) {
                        $j_from = "Waiting For Approval";
                    }
                    if ($jobs_fk[0] == 6) {
                        $j_from = "Approved";
                    }
                    if ($jobs_fk[0] == 7) {
                        $j_from = "Sample Collected";
                    }
                    if ($jobs_fk[0] == 8) {
                        $j_from = "Processing";
                    }
                    if ($jobs_fk[0] == 2) {
                        $j_from = "Completed";
                    }
                    if ($jobs_fk[0] == 0) {
                        $j_from = "Spam";
                    }
                    if ($jobs_fk[1] == 1) {
                        $t_from = "Waiting For Approval";
                    }
                    if ($jobs_fk[1] == 6) {
                        $t_from = "Approved";
                    }
                    if ($jobs_fk[1] == 7) {
                        $t_from = "Sample Collected";
                    }
                    if ($jobs_fk[1] == 8) {
                        $t_from = "Processing";
                    }
                    if ($jobs_fk[1] == 2) {
                        $t_from = "Completed";
                    }
                    if ($jobs_fk[1] == 0) {
                        $t_from = "Spam";
                    }

                    /* NISHIT Status check end */
                    $message = preg_replace("/{{FROM}}/", $j_from, $message);
                    $message = preg_replace("/{{TO}}/", $t_from, $message);
                }
                echo '<p><small>' . $message . '</small></p>';
            }
        }
    }

    function payment_received() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $jid = $this->uri->segment(3);
        $amount = $this->input->post("amount");
        $ttl_amount = $this->input->post("ttl_amount");
        if (!empty($jid) && $ttl_amount >= $amount) {
            $this->visit_model->master_fun_insert("job_master_receiv_amount", array("job_fk" => $jid, "added_by" => $data["login_data"]["id"], "amount" => $amount, "createddate" => date("Y-m-d H:i:s")));
            $remaining_amount = $ttl_amount - $amount;
            $this->visit_model->master_fun_update("job_master", array("id", $jid), array("payable_amount" => $remaining_amount));
        }
        $this->session->set_flashdata("amount_history_success", array("Payment Successfully added."));
        redirect("job-master/job-details/" . $jid);
    }

    function delete_assign_payment() {
        $jid = $this->input->post("jid");
        $id = $this->input->post("id");
        $delete_received_payment = $this->input->post("d_amount");
        $ttl_amount = $this->input->post("ttl_amount");
        $d_amount = $this->input->post("d_amount");
        if (!empty($jid) && !empty($id)) {
            $this->visit_model->master_fun_update("job_master_receiv_amount", array("id", $id), array("status" => 0));
            $remaining_amount = $d_amount + $ttl_amount;
            $this->visit_model->master_fun_update("job_master", array("id", $jid), array("payable_amount" => $remaining_amount));
        }
        $this->session->set_userdata("amount_history_success", array("Payment Successfully deleted."));
        echo 1;
    }

    function change_family_member() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $jid = $this->uri->segment(3);
        $family_change = $this->input->post("family_mem");
        $f_name = $this->input->post("f_name");
        $family_relation = $this->input->post("family_relation");
        $f_phone = $this->input->post("f_phone");
        $f_email = $this->input->post("f_email");

        $job_details = $this->visit_model->master_fun_get_tbl_val("job_master", array('id' => $jid), array("id", "asc"));
        $booking_details = $this->visit_model->master_fun_get_tbl_val("booking_info", array('id' => $job_details[0]["booking_info"]), array("id", "asc"));
        //if (!empty($family_change) || (!empty($f_name) && !empty($family_relation))) {
        if (!empty($f_name) && !empty($family_relation)) {
            $insert = $this->visit_model->master_fun_insert("customer_family_master", array("user_fk" => $booking_details[0]["user_fk"], "name" => $f_name, "relation_fk" => $family_relation, "phone" => $f_phone, "email" => $f_email, "status" => "1", "created_date" => date("Y-m-d H:i:s")));
            $f_type = "family";
            $this->visit_model->master_fun_update("booking_info", array("id", $booking_details[0]["id"]), array("type" => $f_type, "family_member_fk" => $insert));
        } else {
            $f_type = "self";
            if ($family_change != 0) {
                $f_type = "family";
            }
            $this->visit_model->master_fun_update("booking_info", array("id", $booking_details[0]["id"]), array("type" => $f_type, "family_member_fk" => $family_change));
        }
        /*    //$this->session->set_flashdata("amount_history_success", array("Payment Successfully added."));
          } else {
          $this->session->set_flashdata("family_error", array("Please fill family info."));
          } */
        redirect("job-master/job-details/" . $jid);
    }

    function get_job_id($city = null) {
        $this->load->library("Util");
        $util = new Util();
        $new_id = $util->get_job_id($city);
        return $new_id;
    }

}

?>