<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class U extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('home_model');
        $this->load->model('job_model');
        $this->load->model('user_test_master_model');
        $this->load->model('user_wallet_model');
        $this->load->model('service_model');
        $this->load->model("user_master_model");
        $this->load->library('pushserver');
        $this->load->library('firebase_notification');
        $this->load->helper('string');
        $this->load->library('email');
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        if ($uid != 0) {
            $maxid = $this->user_wallet_model->total_wallet($uid);
            $data['total'] = $this->user_wallet_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "id" => $maxid), array("id", "asc"));
            $this->data['wallet_amount'] = $data['total'][0]['total'];
        }
        /* pinkesh code start */
        $data['links'] = $this->user_test_master_model->master_fun_get_tbl_val("patholab_home_master", array("status" => 1), array("id", "asc"));
        $this->data['all_links'] = $data['links'];
        $this->app_track();
    }

    function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }

    function j() {
        $data['cid'] = $this->uri->segment(3);
        $data['success'] = $this->session->flashdata("success");
        $data['error'] = $this->session->flashdata("error");
        $data['family_error'] = $this->session->flashdata("family_error");
        $data['amount_history_success'] = $this->session->flashdata("amount_history_success");
        if ($this->session->userdata("amount_history_success")) {
            $data['amount_history_success'] = $this->session->userdata("amount_history_success");
            $this->session->unset_userdata("amount_history_success");
        }
        $data['query'] = $this->get_job_details($data['cid']);
        if ($data['query'][0]["payment_type"] == 'PayUMoney') {
            $data['payumoney_details'] = $this->job_model->master_fun_get_tbl_val("payment", array('job_fk' => $data['cid']), array("id", "asc"));
        }
        $cust_details = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "id" => $data['query'][0]["cust_fk"]), array("id", "asc"));
        $data['query'][0]["full_name"] = $cust_details[0]['full_name'];
        $p_prc = 0;

        $data["emergency_tests"] = $this->job_model->master_fun_get_tbl_val("booking_info", array('id' => $data['query'][0]["booking_info"]), array("id", "asc"));
        $data["booking_info"] = $this->job_model->get_val("SELECT 
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
        $f_data = $this->job_model->master_fun_get_tbl_val("customer_family_master", array('id' => $data["emergency_tests"][0]["family_member_fk"]), array("id", "asc"));

        $update = $this->job_model->master_fun_update("job_master", array('id', $data['cid']), array("views" => "1"));
        $booked_tests = $this->job_model->master_fun_get_tbl_val("wallet_master", array('job_fk' => $data['cid']), array("id", "asc"));
        $w_prc = 0;


        /* echo "<pre>";
          print_r($data["query"]);
          die(); */
        $this->load->view("j", $data);
    }

    function payumoney() {
        $data['payumoneydetail'] = $this->config->item('payumoneydetail');
        $this->load->library('session');

        $data['rndtxn'] = random_string('numeric', 20);
        $data['jid'] = $this->uri->segment(3);
        $data['booking_info'] = $this->uri->segment(5);
        $data['payamount'] = $this->input->post('amount');
        $data['payamount'] = $this->uri->segment(4);
        $destail = $this->user_wallet_model->master_fun_get_tbl_val("job_master", array("id" => $data['jid']), array("id", "asc"));
        $destail1 = $this->user_wallet_model->master_fun_get_tbl_val("customer_master", array("id" => $destail[0]['cust_fk']), array("id", "asc"));
        $data['user_detail'] = $destail1;
        $this->load->view('payumoney', $data);
    }

    function success_payumoney() {
        $this->load->helper("Email");
        $email_cnt = new Email;

        $data["test_city_session"] = $this->session->userdata("test_city");

        $response = $_REQUEST;
        //print_R($response);
        $trnscaton_id = $response['txnid'];
        $amount = $response['amount'];
        $status = $response['status'];
        $paydate = $response['addedon'];
        $phone = $response['phone'];
        $this->load->library('session');
        $jid = $this->uri->segment(3);
        $amount = $this->uri->segment(4);
        $booking_info_data = $this->user_test_master_model->master_fun_get_tbl_val("booking_info", array('id' => $booking_info), array("id", "asc"));
        $job_details = $this->user_test_master_model->master_fun_get_tbl_val("job_master", array('id' => $jid), array("id", "asc"));

        $t = json_encode($response);
        $chcek_transaction_id = $this->job_model->master_fun_get_tbl_val("payment", array('payomonyid' => $trnscaton_id), array("id", "asc"));
        if (empty($chcek_transaction_id)) {
            if ($response['status'] == "success") {

                $data1 = array("payomonyid" => $trnscaton_id,
                    "amount" => $amount,
                    "paydate" => $paydate,
                    "status" => $status,
                    "uid" => $job_details[0]["cust_fk"],
                    "type" => "remaining_amount",
                    "job_fk" => $jid,
                    "data" => $t,
                );
                $insert1 = $this->job_model->master_fun_insert("payment", $data1);

                $ttl_amount = $job_details[0]["payable_amount"];
                if (!empty($jid) && $ttl_amount >= $amount) {
                    $this->job_model->master_fun_insert("job_master_receiv_amount", array("job_fk" => $jid, "type" => "User Pay", "amount" => $amount, "paypal_log_fk" => $insert1, "createddate" => date("Y-m-d H:i:s")));
                    $remaining_amount = $ttl_amount - $amount;
                    $this->job_model->master_fun_update("job_master", array("id", $jid), array("payable_amount" => $remaining_amount));
                }
                /*    $this->load->helper("sms");
                  $notification = new Sms();
                  $mobile = $destail[0]['mobile'];
                  $mobile = ucfirst($c_name) . "(" . $cmobile . ")";
                  $test_package = implode($test_package_name, ', ');
                  $sms_message = $this->job_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "test_info"), array("id", "asc"));
                  $sms_message = preg_replace("/{{MOBILE}}/", $mobile, $sms_message[0]["message"]);
                  if ($pid != '' && $tid != '') {
                  $sms_message = preg_replace("/{{TESTPACK}}/", 'Test/Package', $sms_message);
                  } else if ($pid != '') {
                  $sms_message = preg_replace("/{{TESTPACK}}/", 'Package', $sms_message);
                  } else {
                  $sms_message = preg_replace("/{{TESTPACK}}/", 'Test', $sms_message);
                  }
                  $sms_message = preg_replace("/{{TESTPACKLIST}}/", $test_package, $sms_message);
                  $sms_message = preg_replace("/{{TOTALPRICE}}/", $amount, $sms_message);

                  $notification::send($cmobile, $sms_message);
                  $configmobile = $this->config->item('admin_alert_phone');
                  foreach ($configmobile as $p_key) {
                  //$notification::send($configmobile, $sms_message);
                  $this->job_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $p_key, "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
                  }
                  if (!empty($family_member_name)) {
                  $this->job_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $destail[0]['mobile'], "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
                  }

                  //redirect('user_master');
                  redirect("user_test_master/invoice/" . $insert); */
                echo '<div class="alert alert-success">
  Your payment successfully received.
</div>';
            } else {
                echo '<div class="alert alert-success">
  Payment fail,Try agamin.
</div>';
            }
        } else {
            echo '<div class="alert alert-success">
  Payment fail,Try agamin.
</div>';
        }
    }

    function get_job_details($job_id) {
        $job_details = $this->job_model->master_fun_get_tbl_val("job_master", array("status !=" => "0", "id" => $job_id), array("id", "asc"));
        if (!empty($job_details)) {
            $book_test = $this->job_model->master_fun_get_tbl_val("job_test_list_master", array("job_fk" => $job_id), array("id", "desc"));
            $book_package = $this->job_model->master_fun_get_tbl_val("book_package_master", array("job_fk" => $job_id, "status" => "1"), array("id", "desc"));
            $test_name = array();
            foreach ($book_test as $key) {
                $price1 = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $job_details[0]["test_city"] . "' AND `test_master`.`id`='" . $key["test_fk"] . "'");
                if (!empty($price1[0])) {
                    $test_name[] = $price1[0];
                }
            }
            $job_details[0]["book_test"] = $test_name;
            $package_name = array();
            foreach ($book_package as $key) {

                $price1 = $this->job_model->get_val("SELECT 
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

    function fail_payumoney() {
        echo "Oops payment is fail.";
    }

}
