<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Active_packages extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('active_package_model');
        $this->load->library('email');
        $this->load->library('firebase_notification');
        $this->app_track();
    }

    function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }

    function index() {
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["test_city_session"] = $this->session->userdata("test_city");
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data['active_package'] = $this->active_package_model->get_val("SELECT `active_package`.*,`package_master`.`title`,job_master.order_id FROM `active_package` 
INNER JOIN `package_master` ON `package_master`.`id`=`active_package`.`package_fk` inner join job_master on `active_package`.`job_fk`=job_master.id 
WHERE `active_package`.`status`='1' AND `package_master`.`status`='1' AND job_master.status !='0' AND `active_package`.`user_fk`='" . $uid . "' AND (`active_package`.`parent`='0' OR `active_package`.`parent`=NULL)");
        $cnt = 0;
        foreach ($data['active_package'] as $key) {
            if ($key["family_fk"] == '0') {
                $data['active_package'][$cnt]["family"] = "Self";
            } else {
                $family_mem = $this->active_package_model->get_val("SELECT `customer_family_master`.`name`,`relation_master`.`name` AS relation FROM `customer_family_master` INNER JOIN `relation_master` ON `relation_master`.`id`=`customer_family_master`.`relation_fk` WHERE `customer_family_master`.`status`='1' AND `customer_family_master`.`id`='" . $key["family_fk"] . "'");
                $data['active_package'][$cnt]["family"] = $family_mem[0]["name"] . "(" . $family_mem[0]["relation"] . ")";
            }
            $cnt++;
        }
        $data['success'] = $this->session->flashdata("success");
        $data['error'] = $this->session->flashdata("error");
        $data['active_class'] = "my_active_package";
        $this->load->view('user/header', $data);
        $this->load->view('user/active_package_list', $data);
        $this->load->view('user/footer');
    }

    function package_details() {
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["test_city_session"] = $this->session->userdata("test_city");
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        $active_pid = $this->uri->segment(3);
        $data['active_package'] = $this->active_package_model->get_val("SELECT `active_package`.*,`package_master`.`title`,`job_master`.`order_id` FROM `active_package` INNER JOIN `package_master` ON `package_master`.`id`=`active_package`.`package_fk` INNER JOIN `job_master` ON `active_package`.`job_fk`=`job_master`.`id` WHERE `active_package`.`status`='1' AND `job_master`.`status`!='0' AND `package_master`.`status`='1' AND (`active_package`.`parent`='" . $active_pid . "' OR `active_package`.`id`='" . $active_pid . "')");
        $cnt = 0;
        $new_array = array();
        foreach ($data['active_package'] as $key) {
            if (!empty($this->get_job_details($key["job_fk"]))) {
                if ($key["family_fk"] == '0') {
                    $data['active_package'][$cnt]["family"] = "Self";
                } else {
                    $family_mem = $this->active_package_model->get_val("SELECT `customer_family_master`.`name`,`relation_master`.`name` AS relation FROM `customer_family_master` INNER JOIN `relation_master` ON `relation_master`.`id`=`customer_family_master`.`relation_fk` WHERE `customer_family_master`.`status`='1' AND `customer_family_master`.`id`='" . $key["family_fk"] . "'");
                    $data['active_package'][$cnt]["family"] = $family_mem[0]["name"] . "(" . $family_mem[0]["relation"] . ")";
                }
                $report = $this->active_package_model->get_val("SELECT * FROM `report_master` WHERE (`type`='c' OR `type`='t' OR `type`='p') AND job_fk='" . $key["job_fk"] . "' AND `status`='1'");
                $data['active_package'][$cnt]["report"] = $report;
                $data['active_package'][$cnt]["job_details"] = $this->get_job_details($key["job_fk"]);
                $new_array[] = $data['active_package'][$cnt];
                $cnt++;
            }
        }
        $data['active_package'] = $new_array;
        $data["job_address_list"] = $this->active_package_model->master_fun_get_tbl_val("job_master", array("status !=" => 0, "cust_fk" => $uid), array("id", "asc"));
        /* echo "<pre>";
          print_R($data['active_package']);
          die(); */
        $data['success'] = $this->session->flashdata("success");
        $data['error'] = $this->session->flashdata("error");
        $data['active_class'] = "my_active_package";
        $this->load->view('user/header', $data);
        $this->load->view('user/active_package_details', $data);
        $this->load->view('user/footer');
    }

    function get_job_details($job_id) {
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["test_city_session"] = $this->session->userdata("test_city");
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        $job_details = $this->active_package_model->master_fun_get_tbl_val("job_master", array("status !=" => "0", "id" => $job_id), array("id", "asc"));
        if (!empty($job_details)) {
            $book_test = $this->active_package_model->master_fun_get_tbl_val("job_test_list_master", array("job_fk" => $job_id), array("id", "desc"));
            $book_package = $this->active_package_model->master_fun_get_tbl_val("book_package_master", array("job_fk" => $job_id, "status" => "1"), array("id", "desc"));
            $test_name = array();
            foreach ($book_test as $key) {
                //$price1 = $this->user_test_master_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master`.`id`='" . $key["test_fk"] . "'");
                $price1 = $this->active_package_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master`.`id`='" . $key["test_fk"] . "' AND `test_master_city_price`.`city_fk`='" . $job_details[0]["test_city"] . "'");
                $test_name[] = $price1[0];
            }
            $job_details[0]["book_test"] = $test_name;
            $package_name = array();
            foreach ($book_package as $key) {
                $price1 = $this->active_package_model->get_val("SELECT 
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

    function book() {
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $this->load->helper("Email");
        $email_cnt = new Email;

        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        $data["test_city_session"] = $this->session->userdata("test_city");
        if (empty($data["test_city_session"])) {
            $data["test_city_session"] = 1;
        }
        $select_tests = $this->uri->segment(3);
        $total = 0;
        $wallet_price = 0;
        $test_city = $data["test_city_session"];
        $this->load->helper('string');
        $order_id = $this->get_job_id($test_city);
        $booking_info = $this->uri->segment(4);
        $date = date('Y-m-d H:i:s');
        $user = $this->active_package_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "id" => $uid), array("id", "asc"));
        $mobile = $user[0]["mobile"];
        //echo $uid."-".$select_tests."-".$total; die();   
        $booking_info_data = $this->active_package_model->master_fun_get_tbl_val("booking_info", array('id' => $booking_info), array("id", "asc"));
        $data = array(
            "order_id" => $order_id,
            "cust_fk" => $uid,
            "date" => $date,
            "price" => "0",
            "status" => '1',
            "payment_type" => "Cash On Delivery",
            "mobile" => $user[0]["mobile"],
            "test_city" => $test_city,
            "booking_info" => $booking_info,
            "address" => $booking_info_data[0]["address"]
        );

        $insert = $this->active_package_model->master_fun_insert("job_master", $data);
        $ids = explode(',', $select_tests);
        foreach ($ids as $key) {
            $ex = explode('-', $key);
            $first_pos = $ex[0];
            if ($first_pos == "t") {
                $testid[] = $ex[1];
            }
            if ($first_pos == "p") {
                $packageid[] = $ex[1];
            }
        }
        $pid = implode($packageid, ',');
        $tid = implode($testid, ',');
        if ($insert) {
            $sms_message = $this->active_package_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "book_login"), array("id", "asc"));
            $family_member_name = $this->active_package_model->get_family_member_name($insert);
            if (!empty($family_member_name)) {
                $c_name = $family_member_name[0]["name"];
                $cmobile = $family_member_name[0]["phone"];
                if (empty($cmobile)) {
                    $cmobile = $user[0]["mobile"];
                }
            } else {
                $c_name = $user[0]["full_name"];
                $cmobile = $user[0]["mobile"];
            }
            $sms_message = preg_replace("/{{NAME}}/", ucfirst($c_name), $sms_message[0]["message"]);
            $sms_message = preg_replace("/{{MOBILE}}/", ucfirst($cmobile), $sms_message);
            if ($pid != '' && $tid != '') {
                $sms_message = preg_replace("/{{TESTPACK}}/", 'Test/Package', $sms_message);
            } else if ($pid != '') {
                $sms_message = preg_replace("/{{TESTPACK}}/", 'Package', $sms_message);
            } else {
                $sms_message = preg_replace("/{{TESTPACK}}/", 'Test', $sms_message);
            }
            $this->load->helper("sms");
            $notification = new Sms();
            if ($cmobile != NULL) {
                $notification->send($cmobile, $sms_message);
            }
            if (!empty($family_member_name)) {
                $this->active_package_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $mobile, "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
            }

            $test = explode(',', $select_tests);
            $test_package_name = array();
            foreach ($test as $key) {
                $ex = explode('-', $key);
                $first_pos = $ex[0];
                $id = $ex[1];
                if ($first_pos == "t") {
                    $price1 = $this->active_package_model->master_fun_get_tbl_val("test_master", array("status" => 1, "id" => $id), array("test_name", "asc"));
                    $test_package_name[] = $price1[0]['test_name'];
                }
                if ($first_pos == "p") {
                    $price1 = $this->active_package_model->master_fun_get_tbl_val("package_master", array("status" => 1, "id" => $id), array("title", "asc"));
                    $test_package_name[] = $price1[0]['title'];
                }
                //$cnt++;
            }
            $package_list = explode(",", $select_tests);
            foreach ($package_list as $key) {
                $test = explode("-", $key);
                if ($test[0] == 't') {
                    $this->active_package_model->master_fun_insert("job_test_list_master", array("job_fk" => $insert, "test_fk" => $test[1]));
                }
                if ($test[0] == 'p') {
                    $this->active_package_model->master_fun_insert("book_package_master", array("job_fk" => $insert, "date" => $date, "order_id" => $order_id, "package_fk" => $test[1], "cust_fk" => $uid, "type" => "2"));
                    $this->check_active_package($test[1], $insert);
                }
            }

            /* $file = $this->pdf_invoice($insert);
              $this->service_model->master_fun_update1("job_master", array("id" => $insert), array("invoice" => $file)); */
            $destail = $this->active_package_model->master_fun_get_tbl_val("customer_master", array("status" => 1, "id" => $uid), array("id", "asc"));
            $ttl_prc = $total + $wallet_price;
            $config['mailtype'] = 'html';
            $family_member_name = $this->active_package_model->get_family_member_name($j_id);
            if (!empty($family_member_name)) {
                $destail[0]['full_name'] = $family_member_name[0]["name"];
            }
            $this->email->initialize($config);
            $family_member_name = $this->active_package_model->get_family_member_name($j_id);
            if (!empty($family_member_name)) {
                $destail[0]['full_name'] = $family_member_name[0]["name"];
            }
            $message = '<div style="padding:0 4%;">
                    <h4><b>Dear </b>' . $destail[0]['full_name'] . '</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Booking has been successfully. </p>
                     <p style="color:#7e7e7e;font-size:13px;"> You Booked :  ' . implode($test_package_name, ', ') . '  </p>  
<p style="color:#7e7e7e;font-size:13px;"> Your Booked Amount is Rs.' . $ttl_prc . '  </p>
		<p style="color:#7e7e7e;font-size:13px;"> Mobile : ' . $destail[0]['mobile'] . '</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
            $message = $email_cnt->get_design($message);
            $this->email->to($destail[0]['email']);
            $this->email->cc("booking.airmed@gmail.com");
            $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
            $this->email->subject('Test Book Successfully');
            $this->email->message($message);
            $attatchPath = base_url() . "upload/result/" . $file;
            $this->email->attach($attatchPath);
            $this->email->send();
            $family_member_name = $this->active_package_model->get_family_member_name($insert);
            if (!empty($family_member_name)) {
                $c_email = $family_member_name[0]["email"];
                if (!empty($c_email)) {
                    $this->email->to($c_email);
                    $this->email->cc("booking.airmed@gmail.com");
                    $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
                    $this->email->subject('Test Book Successfully');
                    $this->email->message($message);
                    $this->email->send();
                }
            }
            if ($booking_info_data[0]["emergency"] == 0) {
                $mobile = ucfirst($c_name) . "(" . $cmobile . ")";
                $test_package = implode($test_package_name, ', ');
                $sms_message = $this->active_package_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "test_info"), array("id", "asc"));
                $sms_message = preg_replace("/{{MOBILE}}/", $mobile, $sms_message[0]["message"]);
                if ($pid != '' && $tid != '') {
                    $sms_message = preg_replace("/{{TESTPACK}}/", 'Test/Package', $sms_message);
                } else if ($pid != '') {
                    $sms_message = preg_replace("/{{TESTPACK}}/", 'Package', $sms_message);
                } else {
                    $sms_message = preg_replace("/{{TESTPACK}}/", 'Test', $sms_message);
                }
                $sms_message = preg_replace("/{{TESTPACKLIST}}/", $test_package, $sms_message);
                $configmobile = $this->config->item('admin_alert_phone');
                foreach ($configmobile as $p_key) {
                    //$notification::send($configmobile, $sms_message);
                    $this->active_package_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $p_key, "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
                }
            }
            $this->book_phlebo($insert);
            $this->session->set_flashdata("payment_success", array("Your Active package successfully Booked."));
            redirect("/");
            //redirect("user_test_master/invoice/" . $insert);
            //echo "<script>alert('OK');</script>";
        }
    }

    function get_job_id($city = null) {
        $this->load->library("Util");
        $util = new Util();
        $new_id = $util->get_job_id($city);
        return $new_id;
    }

    function check_active_package($pid, $jid) {
        /* Nishit active package start */
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]["id"];
        $this->load->library("util");
        $util = new Util;
        $util->check_active_package($pid, $jid, $uid);
        /* Nishit active package end */
    }

    function book_phlebo($job_id) {

        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
        $login_id = $data["login_data"]["id"];
        $new_job_details = $this->active_package_model->master_fun_get_tbl_val("job_master", array('id' => $job_id), array("id", "asc"));
        $booking_info = $this->active_package_model->master_fun_get_tbl_val("booking_info", array('id' => $new_job_details[0]["booking_info"]), array("id", "asc"));
        $job_details = $this->active_package_model->master_fun_get_tbl_val("job_master", array('id' => $job_id), array("id", "asc"));
        /* Nishit send phebo notification start */
        $phlebo_list = $this->active_package_model->master_fun_get_tbl_val("phlebo_master", array('status' => "1"), array("id", "asc"));
        foreach ($phlebo_list as $pkey) {
            /* $message = "New job added order id:" . $job_details[0]["order_id"]; */
            $message = "New job added";
            if ($pkey["device_type"] == 'android') {
                $serverObject = new Firebase_notification();
                $fields = array(
                    'to' => $pkey["device_id"],
                    'notification' => array('title' => 'AirmedLabs', 'body' => $message),
                    'data' => array('j_id' => $job_id)
                );
                $jsonString = $serverObject->sendPushNotificationToGCMSever($fields);
            }
            if ($pkey["device_type"] == 'ios') {
                $url = 'http://website-demo.in/patholabpush/push.php?device_id=' . $pkey["device_id"] . '&msg=' . $message . '&type=completed&testid=' . $job_id . '';
                $url = str_replace(" ", "%20", $url);
                $data = $this->get_content($url);
                $data2 = json_decode($data);
            }
        }
        /* Nishit send phlebo notification end */
        if ($booking_info[0]["emergency"] == "0") {
            /*
              $get_random_phlebo = $this->user_test_master_model->get_random_phlebo($booking_info[0]);
              if (!empty($get_random_phlebo)) {
              $data = array("job_fk" => $job_id, "phlebo_fk" => $get_random_phlebo[0]["id"], "date" => $booking_info[0]["date"], "time_fk" => $booking_info[0]["time_slot_fk"], "address" => $booking_info[0]["address"], "notify_cust" => 1, "created_date" => date("Y-m-d H:i:s"), "created_by" => $data["login_data"]["id"]);
              $insert = $this->user_test_master_model->master_fun_insert("phlebo_assign_job", $data);
              //$this->user_test_master_model->master_fun_insert("job_log", array("job_fk" => $job_id, "created_by" => "", "updated_by" => $login_id, "deleted_by" => "", "message_fk" => "8", "date_time" => date("Y-m-d H:i:s")));
              //$update = $this->job_model->master_fun_update("phlebo_master", array("id", $phlebo_id), $data);
              $phlebo_details = $this->user_test_master_model->master_fun_get_tbl_val("phlebo_master", array('id' => $get_random_phlebo[0]["id"]), array("id", "asc"));
              $phlebo_job_details = $this->user_test_master_model->master_fun_get_tbl_val("phlebo_assign_job", array('job_fk' => $job_id), array("id", "asc"));
              $p_time = $this->user_test_master_model->master_fun_get_tbl_val("phlebo_time_slot", array('id' => $phlebo_job_details[0]["time_fk"]), array("id", "asc"));
              $customer_details = $this->user_test_master_model->master_fun_get_tbl_val("customer_master", array('id' => $job_details[0]['cust_fk']), array("id", "asc"));
              if ($insert) {
              $family_member_name = $this->user_test_master_model->get_family_member_name($job_id);
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
              //echo $c_name."-".$cmobile; die();
              $s_time = date('h:i a', strtotime($p_time[0]["start_time"]));
              $e_time = date('h:i a', strtotime($p_time[0]["end_time"]));
              $sms_message = $this->user_test_master_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "phlebo_msg"), array("id", "asc"));
              $sms_message = preg_replace("/{{NAME}}/", ucfirst($phlebo_details[0]["name"]), $sms_message[0]["message"]);
              $sms_message = preg_replace("/{{MOBILE}}/", $phlebo_details[0]["mobile"], $sms_message);
              $sms_message = preg_replace("/{{ORDERID}}/", $job_details[0]["order_id"], $sms_message);
              $sms_message = preg_replace("/{{CNAME}}/", $c_name, $sms_message);
              $sms_message = preg_replace("/{{CMOBILE}}/", $cmobile, $sms_message);
              $sms_message = preg_replace("/{{CADDRESS}}/", $phlebo_job_details[0]["address"], $sms_message);
              $sms_message = preg_replace("/{{DATE}}/", $phlebo_job_details[0]["date"], $sms_message);
              $sms_message = preg_replace("/{{TIME}}/", $s_time . " To " . $e_time, $sms_message);
              $job_details = $this->get_job_details($job_id);
              $b_details = array();
              foreach ($job_details[0]["book_test"] as $bkey) {
              $b_details[] = $bkey["test_name"] . " Rs." . $bkey["price"];
              }
              foreach ($job_details[0]["book_package"] as $bkey) {
              $b_details[] = $bkey["title"] . " Rs." . $bkey["d_price"];
              }
              $sms_message = preg_replace("/{{BOOKINFO}}/", implode(",", $b_details), $sms_message);
              //$this->user_test_master_model->master_fun_insert("test", array("test"=>$sms_message."-".json_encode($job_details)));
              //$sms_message="done";
              $mobile = $phlebo_details[0]['mobile'];
              $this->load->helper("sms");
              $notification = new Sms();
              $notification->send($mobile, $sms_message);
              //if ($notify == 1) {
              $sms_message = $this->user_test_master_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "phlebo_msg_cust"), array("id", "asc"));
              $sms_message = preg_replace("/{{NAME}}/", ucfirst($c_name), $sms_message[0]["message"]);
              $sms_message = preg_replace("/{{MOBILE}}/", $customer_details[0]["mobile"], $sms_message);
              $sms_message = preg_replace("/{{ORDERID}}/", $job_details[0]["order_id"], $sms_message);
              $sms_message = preg_replace("/{{PNAME}}/", ucfirst($phlebo_details[0]["name"]), $sms_message);
              $sms_message = preg_replace("/{{PMOBILE}}/", $phlebo_details[0]["mobile"], $sms_message);
              $sms_message = preg_replace("/{{DATE}}/", $phlebo_job_details[0]["date"], $sms_message);
              $sms_message = preg_replace("/{{TIME}}/", $s_time . " To " . $e_time, $sms_message);
              $sms_message = preg_replace("/{{BOOKINFO}}/", implode(",", $b_details), $sms_message);
              $mobile = $customer_details[0]['mobile'];
              //$sms_message="done";
              $notification->send($cmobile, $sms_message);
              if (!empty($job_details[0]["address"])) {
              $this->user_test_master_model->master_fun_update("customer_master", array('id', $job_details[0]["cust_fk"]), array("address" => $job_details[0]["address"]));
              }
              if (!empty($family_member_name)) {
              $this->job_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $customer_details[0]["mobile"], "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
              }
              // }
              } else {

              }
              }
             */
        } else {
            $customer_details = $this->active_package_model->master_fun_get_tbl_val("customer_master", array('id' => $job_details[0]['cust_fk']), array("id", "asc"));
            $family_member_name = $this->active_package_model->get_family_member_name($job_id);
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
            $sms_message = $this->active_package_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "emergency_book"), array("id", "asc"));
            $sms_message = preg_replace("/{{MOBILE}}/", $c_name . " (" . $cmobile . ")", $sms_message[0]["message"]);
            $sms_message = preg_replace("/{{TESTPACKLIST}}/", implode(",", $b_details), $sms_message);
            $configmobile = $this->config->item('admin_alert_phone');
            foreach ($configmobile as $p_key) {
                $this->active_package_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $p_key, "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
            }
        }
    }

}
