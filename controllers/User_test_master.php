<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User_test_master extends CI_Controller {

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

    /* function index() {
      //echo "here"; die();

      if ($this->session->flashdata("error")) {
      $data["error"] = $this->session->flashdata("error");
      }
      $data["login_data"] = loginuser();
      //	print_r($data["login_data"]); die();
      //$data['test'] = $this->user_test_master_model->master_fun_get_tbl_val("test_master", array("status" => 1),array("id","asc"));
      //print_r($data["test"]); die();
      $testname = $this->input->post('testname');
      $data['testname'] = $this->input->post('testname');
      $data['test'] = $this->user_test_master_model->search_test($testname);
      $data['success'] = $this->session->flashdata("success");
      $data['error'] = $this->session->flashdata("error");
      $data['active_class'] = "book_test";
      $this->load->view('user/header', $data);
      //$this->load->view('nav',$data);
      $this->load->view('user/test_list', $data);
      $this->load->view('user/footer');
      } */

    function book_test() {
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $pid = $this->input->post('pid1');
        $data["test_city_session"] = $this->session->userdata("test_city");
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        $data1 = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "id" => $uid), array("id", "asc"));
        if ($this->input->post('test')) {
            $test = $this->input->post('test');
            $test = implode($test, ',');
        } else {
            $test = $this->uri->segment(3);
        }
        if ($test != "") {
            $test1 = explode(',', $test);
        }
        $order_id = $this->get_job_id();
        $date = date('Y-m-d H:i:s');
        $price = 0;
        $cnt = 0;
        $package_exist = 0;
        foreach ($test1 as $key) {
            $ex = explode('-', $key);
            $first_pos = $ex[0];
            $id = $ex[1];
            if ($first_pos == "t") {
                //$price1 = $this->user_master_model->get_val("SELECT * FROM test_master_city_price WHERE test_fk='" . $id . "' AND city_fk='" . $data["test_city_session"] . "' AND STATUS='1'");
                $price1 = $this->user_master_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master`.`id`='" . $id . "' AND `test_master_city_price`.`city_fk`='" . $data["test_city_session"] . "'");
                $price += $price1[0]['price'];
            }
            if ($first_pos == "p") {
                $package_exist = 1;
                $price1 = $this->user_master_model->master_fun_get_tbl_val("package_master_city_price", array("status" => 1, "package_fk" => $id, "city_fk" => $data["test_city_session"]), array("id", "asc"));
                //$price += $price1[0]['d_price'];
            }
            $cnt++;
        }
        $maxid = $this->user_wallet_model->total_wallet($uid);
        $data['total'] = $this->user_wallet_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "id" => $maxid), array("id", "asc"));
        $wallet_amount = $data['total'][0]['total'];
        if (empty($test)) {
            $this->session->set_flashdata("error", array("Please Select Your Test From List"));
            redirect("user_master/suggested_test/" . $pid, "refresh");
        }
        if ($package_exist != 1) {
            //echo $wallet_amount."-".$price; die();
            if ($wallet_amount >= $price) {
                /* $insert = $this->user_test_master_model->master_fun_insert("job_master", array("cust_fk" => $uid,"date"=>$date,"order_id"=>$order_id));	
                  $price = 0 ;
                  for($i=0 ; $i < count($test) ; $i++){
                  $insert_code = $this->user_test_master_model->master_fun_insert("job_test_list_master", array("job_fk" => $insert,"test_fk"=>$test[$i]));
                  $data = $this->user_test_master_model->master_fun_get_tbl_val("test_master", array("status" => 1,'id'=>$test[$i]),array("id","asc"));
                  $price = $price + $data[0]['price'];
                  }
                  $update = $this->user_test_master_model->master_fun_update("job_master",array('id',$insert),array("price"=>$price));
                  if($update){
                  $query = $this->user_test_master_model->master_fun_get_tbl_val("wallet_master", array("status" =>1,"cust_fk"=>$uid), array("id", "desc"));
                  $total = $query[0]['total'];
                  $data = array(
                  "cust_fk"=>$uid,
                  "debit"=>$price,
                  "total"=>$total-$price,
                  "job_fk"=>$insert,
                  "created_time"=>date('Y-m-d H:i:s')
                  );
                  $insert = $this->user_test_master_model->master_fun_insert("wallet_master",$data);
                  $this->session->set_flashdata("success", array("Test Book Successfully..Our Team Contact You soon"));
                  redirect("user_test_master", "refresh");
                  }
                 */
                redirect('user_test_master/payment_method_wallet/' . $test . '/' . $this->uri->segment(4), "refresh");
            } else {

                $payamount = $price - $wallet_amount;
                //	$this->session->set_flashdata("error", array("No Sufficient Balance in Wallet.. Please Add Money to wallet"));
                // redirect('user_test_master/payumoney/'.$test1.'/'.$payamount, "refresh");
                redirect('user_test_master/payment_method/' . $test . '/' . $this->uri->segment(4), "refresh");
            }
        } else {
            redirect('user_test_master/payment_method/' . $test . '/' . $this->uri->segment(4), "refresh");
        }
    }

    function payment_method() {
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["test_city_session"] = $this->session->userdata("test_city");
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        $maxid = $this->user_wallet_model->total_wallet($uid);
        $data['total'] = $this->user_wallet_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "id" => $maxid), array("id", "asc"));
        $wallet_amount = $data['total'][0]['total'];
        $test1 = $this->uri->segment(3);
        $data["booking_info"] = $this->uri->segment(4);
        $data['user_info'] = $this->user_wallet_model->master_fun_get_tbl_val("customer_master", array("status" => 1, "id" => $uid), array("id", "asc"));
        $data["test_ids"] = $test1;
        if ($test1 != "") {
            $test = explode(',', $test1);
        }
        $price = 0;
        $p_price = 0;
        /* for ($i = 0; $i < count($test); $i++) {
          $data1 = $this->user_test_master_model->master_fun_get_tbl_val("test_master", array("status" => 1, 'id' => $test[$i]), array("id", "asc"));
          $price = $price + $data1[0]['price'];
          } */
        /* Nishit price count start */
        $data["package_exist"] = 0;
        $data["test_exist"] = 0;
        $cnt = 0;
        $booking_info = $this->user_test_master_model->master_fun_get_tbl_val("booking_info", array("status" => "1", "id" => $data["booking_info"]), array("id", "asc"));
        foreach ($test as $key) {
            $ex = explode('-', $key);
            $first_pos = $ex[0];
            $id = $ex[1];
            if ($first_pos == "t") {
                //$price1 = $this->user_master_model->get_val("SELECT * FROM test_master_city_price WHERE test_fk='" . $id . "' AND city_fk='" . $data["test_city_session"] . "' AND STATUS='1'");
                $price1 = $this->user_master_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master`.`id`='" . $id . "' AND `test_master_city_price`.`city_fk`='" . $data["test_city_session"] . "'");
                $data["test_exist"] = 1;
                $price += $price1[0]['price'];
            }
            if ($first_pos == "p") {
                $data["package_exist"] = 1;
                //$price1 = $this->user_master_model->master_fun_get_tbl_val("package_master_city_price", array("status" => 1, "package_fk" => $id, "city_fk" => $data["test_city_session"]), array("id", "asc"));
                $price1 = $this->job_model->get_val("SELECT 
          `package_master`.*,
          `package_master_city_price`.`a_price` AS `a_price`,
          `package_master_city_price`.`d_price` AS `d_price`
          FROM
          `package_master`
          INNER JOIN `package_master_city_price`
          ON `package_master`.`id` = `package_master_city_price`.`package_fk`
          WHERE `package_master`.`status` = '1'
          AND `package_master_city_price`.`status` = '1' AND `package_master_city_price`.`city_fk` = '" . $data["test_city_session"] . "' AND `package_master`.`id`='" . $id . "'");
                $b_package_data = $this->user_test_master_model->get_val("SELECT `active_package`.*,`package_master`.`title`,`job_master`.`order_id` FROM `active_package` INNER JOIN `package_master` ON `active_package`.`package_fk`=`package_master`.`id` INNER JOIN job_master ON job_master.`id`=`active_package`.`job_fk` WHERE `job_master`.`status`!='0' AND `active_package`.`user_fk`='" . $uid . "' AND `active_package`.package_fk='" . $id . "' AND `active_package`.`family_fk`='" . $booking_info[0]["family_member_fk"] . "' AND `active_package`.`due_to` >= '" . date("Y-m-d") . "' AND `active_package`.`parent`='0' AND `active_package`.`status`='1'");
                if (empty($b_package_data)) {
                    $p_price += $price1[0]['d_price'];
                    $data["is_active_package"] = 0;
                } else {
                    $b_package_data[0]["price"] = $price1[0]['d_price'];
                    $data["is_active_package"] = 1;
                }
                $data["user_active_package"][] = $b_package_data;
            }
            $cnt++;
        }
        //print_r($data["user_active_package"]); die();
        /* Nishit price count end */
        $data['testid'] = $this->uri->segment(3);
        if ($price < 300 && $data["test_exist"] == 1) {
            $price = $price + 100;
        }
        if (empty($b_package_data) && $p_price < 300 && $data["package_exist"] == 1) {
            $p_price = $p_price + 100;
        }
        $data['price'] = $price;
        $data['package_price'] = $p_price;
        if ($price > $wallet_amount) {
            $data['payamount'] = $price - $wallet_amount;
        } else {
            $data['payamount'] = 0;
        }
        $data['wallet'] = $wallet_amount;
        $data["state"] = $this->user_test_master_model->master_fun_get_tbl_val("state", array("status" => 1), array("state_name", "asc"));
        $data['refrence'] = $this->user_test_master_model->master_fun_get_tbl_val("source_master", array("status" => 1), array("name", "asc"));
        $this->load->view('user/header', $data);
        $this->load->view('user/confirm_amount', $data);
        $this->load->view('user/footer');
    }

    function payment_method_wallet() {
        $data['payumoneydetail'] = $this->config->item('payumoneydetail');
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
        $data["test_city_session"] = $this->session->userdata("test_city");
        $uid = $data["login_data"]['id'];
        $data["booking_info"] = $this->uri->segment(4);
        $data['user_info'] = $this->user_wallet_model->master_fun_get_tbl_val("customer_master", array("status" => 1, "id" => $uid), array("id", "asc"));
        $maxid = $this->user_wallet_model->total_wallet($uid);
        $data['total'] = $this->user_wallet_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "id" => $maxid), array("id", "asc"));
        $wallet_amount = $data['total'][0]['total'];
        $test1 = $this->uri->segment(3);
        if ($test1 != "") {
            $test = explode(',', $test1);
        }
        $price = 0;
        /* for ($i = 0; $i < count($test); $i++) {
          $data1 = $this->user_test_master_model->master_fun_get_tbl_val("test_master", array("status" => 1, 'id' => $test[$i]), array("id", "asc"));
          $price = $price + $data1[0]['price'];
          } */
        /* Nishit price count start */
        $data["package_exist"] = 0;
        $data["test_exist"] = 0;
        $cnt = 0;
        foreach ($test as $key) {
            $ex = explode('-', $key);
            $first_pos = $ex[0];
            $id = $ex[1];
            if ($first_pos == "t") {
                $data["test_exist"] = 1;
                //$price1 = $this->user_master_model->get_val("SELECT * FROM test_master_city_price WHERE test_fk='" . $id . "' AND city_fk='" . $data["test_city_session"] . "' AND STATUS='1'");
                $price1 = $this->user_master_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master`.`id`='" . $id . "' AND `test_master_city_price`.`city_fk`='" . $data["test_city_session"] . "'");
                $price += $price1[0]['price'];
            }
            if ($first_pos == "p") {
                $data["package_exist"] = 1;
                //$price1 = $this->user_master_model->master_fun_get_tbl_val("package_master_city_price", array("status" => 1, "package_fk" => $id, "city_fk" => $data["test_city_session"]), array("id", "asc"));
                $price1 = $this->job_model->get_val("SELECT 
          `package_master`.*,
          `package_master_city_price`.`a_price` AS `a_price`,
          `package_master_city_price`.`d_price` AS `d_price`
          FROM
          `package_master`
          INNER JOIN `package_master_city_price`
          ON `package_master`.`id` = `package_master_city_price`.`package_fk`
          WHERE `package_master`.`status` = '1'
          AND `package_master_city_price`.`status` = '1' AND `package_master_city_price`.`city_fk` = '" . $data["test_city_session"] . "' AND `package_master`.`id`='" . $id . "'");
                $p_price += $price1[0]['d_price'];
            }
            $cnt++;
        }
        /* Nishit price count end */
        $data["state"] = $this->user_test_master_model->master_fun_get_tbl_val("state", array("status" => 1), array("state_name", "asc"));
        $data["doctor"] = $this->user_test_master_model->master_fun_get_tbl_val("doctor_master", array("status" => 1), array("full_name", "asc"));
        $data['testid'] = $this->uri->segment(3);
        $data["test_ids"] = $test1;
        if ($price < 300 && $data["test_exist"] == 1) {
            $price = $price + 100;
        }
        if ($p_price < 300 && $data["package_exist"] == 1) {
            $p_price = $p_price + 100;
        }
        $data['price'] = $price;
        if ($wallet_amount >= $price) {
            $data['payamount'] = 0;
        } else {
            $data['payamount'] = $price - $wallet_amount;
        }
        $data['refrence'] = $this->user_test_master_model->master_fun_get_tbl_val("source_master", array("status" => 1), array("name", "asc"));
        $data['wallet'] = $wallet_amount;
        $this->load->view('user/header', $data);
        //$this->load->view('user/confirm_amount1', $data);
        $this->load->view('user/confirm_amount', $data);
        $this->load->view('user/footer');
    }

    function save_user_data() {
        /* Nishit upate user info start */
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["test_city_session"] = $this->session->userdata("test_city");
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        $mobile = $this->input->post("mobile1");
        $dob = $this->input->post("dob");
        $gender = $this->input->post("gender1");
        $this->service_model->master_fun_update1("customer_master", array("id" => $uid), array("gender" => $gender, "dob" => $dob));
        /* Nishit upate user info end */
    }

    function payumoney() {
        $data['payumoneydetail'] = $this->config->item('payumoneydetail');
        $this->load->library('session');
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["test_city_session"] = $this->session->userdata("test_city");
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        $data['rndtxn'] = random_string('numeric', 20);
        $data['testid'] = $this->uri->segment(3);
        $data['booking_info'] = $this->uri->segment(5);
        $data['payamount'] = $this->input->post('amount');
        $data['method'] = $this->uri->segment(4);
        $data["source_by"] = $this->input->get_post("source_by");
        $destail = $this->user_wallet_model->master_fun_get_tbl_val("customer_master", array("status" => 1, "id" => $uid), array("id", "asc"));
        $data['user_detail'] = $destail;
        $this->load->view('user/payumoney1', $data);
    }

    function fail_payumoney() {
        echo "Your transaction is fail.";
    }

    function success_payumoney() {
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $this->load->helper("Email");
        $email_cnt = new Email;

        $data["test_city_session"] = $this->session->userdata("test_city");
        $data["login_data"] = loginuser();
        //print_r($data["login_data"]); die();
        $uid = $data["login_data"]['id'];
        $response = $_REQUEST;
        //print_R($response);
        $trnscaton_id = $response['txnid'];
        $amount = $response['amount'];
        $status = $response['status'];
        $paydate = $response['addedon'];
        $phone = $response['phone'];
        $this->load->library('session');
        $test1 = $this->uri->segment(3);
        $payment_method = $this->uri->segment(4);
        $test_city = $this->uri->segment(5);
        $booking_info = $this->uri->segment(6);
        $new_id = explode("-", $booking_info);
        $booking_info = $new_id[0];
        $refrence_by = $new_id[1];
        //die();
        $booking_info_data = $this->user_test_master_model->master_fun_get_tbl_val("booking_info", array('id' => $booking_info), array("id", "asc"));
        $test = explode(',', $test1);
        $t = json_encode($response);
        $chcek_transaction_id = $this->user_test_master_model->master_fun_get_tbl_val("payment", array('payomonyid' => $trnscaton_id), array("id", "asc"));
        if (empty($chcek_transaction_id)) {
            if ($response['status'] == "success") {
                // job add
                $order_id = $this->get_job_id($test_city);
                $date = date('Y-m-d H:i:s');
                $price = 0;
                $test_name_mail = array();
                for ($i = 0; $i < count($test); $i++) {
                    $data = $this->user_master_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master`.`id`='" . $test[$i] . "' AND `test_master_city_price`.`city_fk`='" . $test_city . "'");
                    $price = $price + $data[0]['price'];
                    $$test_name_mail[$i] = $data[0]['test_name'];
                }
                $maxid = $this->user_wallet_model->total_wallet($uid);
                $data['total'] = $this->user_wallet_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "id" => $maxid), array("id", "asc"));
                $wallet_amount = $data['total'][0]['total'];
                if (empty($test)) {
                    $this->session->set_flashdata("error", array("Please Select Your Test From List"));
                    redirect("user_test_master", "refresh");
                }

                $insert = $this->user_test_master_model->master_fun_insert("job_master", array("cust_fk" => $uid, "date" => $date, "order_id" => $order_id, "payment_type" => "PayUMoney", "doctor" => $doct, "other_reference" => $refrence_by, "booking_info" => $booking_info, "test_city" => $test_city, "mobile" => $phone, "payable_amount" => "0.00", "address" => $booking_info_data[0]["address"]));
                $price = 0;
                $test_package_name = array();
                $package_list = explode(",", $test1);
                foreach ($package_list as $key) {
                    $tn = explode("-", $key);
                    if ($tn[0] == 't') {
                        $tst_price = $this->job_model->get_val("select price from test_master_city_price where test_fk='" . $tn[1] . "' and city_fk='" . $test_city . "' and status='1'");
                        $this->user_test_master_model->master_fun_insert("job_test_list_master", array('job_fk' => $insert, "test_fk" => $tn[1],"price" => $tst_price[0]["price"]));
                        //$this->user_test_master_model->master_fun_insert("booked_job_test", array("job_fk" => $insert, "test_city" => $test_city, "test_fk" => "t-" . $tn[1], "price" => $tst_price[0]["price"]));
                    }
                    if ($tn[0] == 'p') {
                        $tst_price = $this->user_test_master_model->get_val("select d_price from package_master_city_price where package_fk='" . $tn[1] . "' and city_fk='" . $test_city . "' and status='1'");
                        $this->user_test_master_model->master_fun_insert("book_package_master", array("cust_fk" => $uid, "package_fk" => $tn[1], 'job_fk' => $insert, "status" => "1", "type" => "2","price" => $tst_price[0]["d_price"]));
                        //$this->user_test_master_model->master_fun_insert("booked_job_test", array("job_fk" => $insert, "test_city" => $test_city, "test_fk" => "p-" . $tn[1], "price" => $tst_price[0]["d_price"]));
                        $this->check_active_package($tn[1], $insert);
                    }
                }

                $cnt = 0;
                $check_price = 0;
                foreach ($test as $key) {
                    $ex = explode('-', $key);
                    $first_pos = $ex[0];
                    $id = $ex[1];
                    if ($first_pos == "t") {
                        //$price1 = $this->user_test_master_model->master_fun_get_tbl_val("test_master", array("status" => 1, "id" => $id), array("test_name", "asc"));
                        $price1 = $this->user_test_master_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master`.`id`='" . $id . "'  AND `test_master_city_price`.`city_fk`='" . $test_city . "'");
                        $price += $price1[0]['price'];
                        $check_price += $price1[0]['price'];
                        $test_package_name[] = $price1[0]['test_name'];
                    }
                    if ($first_pos == "p") {
                        //$price1 = $this->user_test_master_model->master_fun_get_tbl_val("package_master", array("status" => 1, "id" => $id), array("title", "asc"));
                        $price1 = $this->user_test_master_model->get_val("SELECT 
          `package_master`.*,
          `package_master_city_price`.`a_price` AS `a_price`,
          `package_master_city_price`.`d_price` AS `d_price`
          FROM
          `package_master`
          INNER JOIN `package_master_city_price`
          ON `package_master`.`id` = `package_master_city_price`.`package_fk`
          WHERE `package_master`.`status` = '1'
          AND `package_master_city_price`.`status` = '1' AND package_master.`id`='" . $id . "' AND `package_master_city_price`.`city_fk` = '" . $test_city . "' ");
                        $check_price += $price1[0]['d_price'];
                        $test_package_name[] = $price1[0]['title'];
                    }
                    $cnt++;
                }

                $j_total_price = $amount;
                $collection_charge = 0;
                if ($check_price < 300) {
                    $check_price = $check_price + 100;
                    $collection_charge = 1;
                }

                $update = $this->user_test_master_model->master_fun_update("job_master", array('id', $insert), array("price" => $check_price, "collection_charge" => $collection_charge));
                $j_id = $insert;
                $this->check_prescription_test($j_id);
                // end job add
                $query = $this->user_wallet_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $uid), array("id", "desc"));
                $total = $query[0]['total'];

                $data1 = array("payomonyid" => $trnscaton_id,
                    "amount" => $response['amount'],
                    "paydate" => $paydate,
                    "status" => $status,
                    "uid" => $uid,
                    "type" => "job",
                    "job_fk" => $insert,
                    "data" => $t,
                );
                $insert1 = $this->user_wallet_model->master_fun_insert("payment", $data1);
                $pay_from_wallet_amount = 0;
                if ($payment_method == "wallet" && $total != 0) {

                    $data1 = array(
                        "cust_fk" => $uid,
                        "debit" => $wallet_amount,
                        "total" => $total - $wallet_amount,
                        "job_fk" => $insert,
                        "created_time" => date('Y-m-d H:i:s')
                    );
                    if ($price != 0) {
                        $insert1 = $this->user_wallet_model->master_fun_insert("wallet_master", $data1);
                        $pay_from_wallet_amount = $wallet_amount;
                    }
                }
                $file = $this->pdf_invoice($j_id);
                $this->service_model->master_fun_update1("job_master", array("id" => $insert), array("invoice" => $file));
                /* $query = $this->user_test_master_model->master_fun_get_tbl_val("offer_master", array("status" => 1), array("id", "desc"));
                  $caseback_per = $query[0]['caseback_per'];
                  if ($price != 0) {
                  $caseback_amount = ($price * $caseback_per) / 100;
                  } else {
                  $caseback_amount = 0;
                  }
                  $query = $this->user_wallet_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $uid), array("id", "desc"));
                  $total = $query[0]['total'];
                  $data = array(
                  "cust_fk" => $uid,
                  "credit" => $caseback_amount,
                  "total" => $total + $caseback_amount,
                  "type" => "Case Back",
                  "job_fk" => $insert,
                  "created_time" => date('Y-m-d H:i:s')
                  );
                  if ($price != 0) {
                  $insert = $this->user_test_master_model->master_fun_insert("wallet_master", $data);
                  } */
                $query = $this->user_wallet_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $uid), array("id", "desc"));
                $Current_wallet = $query[0]['total'];

                $destail = $this->user_wallet_model->master_fun_get_tbl_val("customer_master", array("status" => 1, "id" => $uid), array("id", "asc"));
                $family_member_name = $this->user_test_master_model->get_family_member_name($j_id);
                if (!empty($family_member_name)) {
                    $destail[0]['full_name'] = $family_member_name[0]["name"];
                }
                $timeslot = $this->job_model->get_val("SELECT ts.start_time,ts.end_time,b.date FROM booking_info as b left join phlebo_time_slot as ts on b.time_slot_fk=ts.id WHERE ts.status='1' AND b.status='1' AND b.id='" . $booking_info . "'");
                $s_time = date('h:i A', strtotime($timeslot[0]["start_time"]));
                $e_time = date('h:i A', strtotime($timeslot[0]["end_time"]));
                $datebb = date("d F,Y", strtotime($timeslot[0]['date']));
                $config['mailtype'] = 'html';
                $this->email->initialize($config);

                /* $message = "You recently requested to reset your password for your  Patholab Account. Click the button below to reset it.<br/><br/>";
                  $message .= "<a href='".base_url()."user_get_password/index/".$rs."' style='background-color:#dc4d2f;color:#ffffff;display:inline-block;font-size:15px;line-height:45px;text-align:center;width:200px;border-radius:3px;text-decoration:none;'>Reset your password</a><br/><br/>";
                  $message .= "If you did not request a password reset, please ignore this email or reply to let us know.<br/><br/>";
                  $message .= "Thanks <br/> Patholab"; */
                $family_member_name = $this->user_test_master_model->get_family_member_name($j_id);
                if (!empty($family_member_name)) {
                    $destail[0]['full_name'] = $family_member_name[0]["name"];
                }
                $message = '<div style="padding:0 4%;">
                    <h4><b>Dear </b>' . $destail[0]['full_name'] . '</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Test has been book successfully. </p>
                       <p style="color:#7e7e7e;font-size:13px;"> Sample Collection time :  ' . $datebb . ' ' . $s_time . '-' . $e_time . '  </p>  
<p style="color:#7e7e7e;font-size:13px;"> Rs. ' . $pay_from_wallet_amount . ' Debited From your account. </p>
<p style="color:#7e7e7e;font-size:13px;"> Rs. ' . $response['amount'] . ' Paid using PayUMoney. </p>
		<p style="color:#7e7e7e;font-size:13px;">Your Current Wallet balance is Rs. ' . $Current_wallet . '</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
                $message = $email_cnt->get_design($message);
                $this->email->to($destail[0]['email']);
                $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
                $this->email->subject('Test Book Successfully');
                $this->email->message($message);
                $attatchPath = base_url() . "upload/result/" . $file;
                $this->email->attach($attatchPath);
                $this->email->send();
                if (!empty($family_member_name)) {
                    $c_email = $family_member_name[0]["email"];
                    if (!empty($c_email)) {
                        $this->email->to($c_email);
                        $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
                        $this->email->subject('Test Book Successfully');
                        $this->email->message($message);
                        $this->email->send();
                    }
                }

                /*

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
                  <h4><b>Dear </b>' . $destail[0]['full_name'] . '</h4>
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
                  $this->email->to($destail[0]['email']);
                  $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
                  $this->email->subject('CashBack');
                  $this->email->message($message);
                  if ($price != 0) {
                  $this->email->send();
                  }
                 */
                $family_member_name = $this->job_model->get_family_member_name($j_id);
                if (!empty($family_member_name)) {
                    $c_name = $family_member_name[0]["name"];
                    $cmobile = $family_member_name[0]["phone"];
                    if (empty($cmobile)) {
                        $cmobile = $destail[0]["mobile"];
                    }
                } else {
                    $c_name = $destail[0]["full_name"];
                    $cmobile = $destail[0]["mobile"];
                }
                $this->load->helper("sms");
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
                //$sms_message = preg_replace("/{{TOTALPRICE}}/", $amount, $sms_message);
                $notification::send($cmobile, $sms_message);
                $configmobile = $this->config->item('admin_alert_phone');
                foreach ($configmobile as $p_key) {
                    //$notification::send($configmobile, $sms_message);
                    $this->job_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $p_key, "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
                }
                if (!empty($family_member_name)) {
                    $this->job_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $destail[0]['mobile'], "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
                }
                /* Delhi and gurgaon booking alert sms start */
                $test_city = $this->session->userdata("test_city");
                if ($test_city == 4 || $test_city == 5) {
                    $sms_message = '';
                    $sms_message = $this->job_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "delhi_booking"), array("id", "asc"));
                    $city_name = $this->job_model->master_fun_get_tbl_val("test_cities", array('status' => 1, "id" => $this->session->userdata("test_city")), array("id", "asc"));
                    $sms_message = preg_replace("/{{MOBILE}}/", $mobile, $sms_message[0]["message"]);
                    $sms_message = preg_replace("/{{CITY}}/", $city_name[0]["name"], $sms_message);
                    if ($pid != '' && $tid != '') {
                        $sms_message = preg_replace("/{{TESTPACK}}/", 'Test/Package', $sms_message);
                    } else if ($pid != '') {
                        $sms_message = preg_replace("/{{TESTPACK}}/", 'Package', $sms_message);
                    } else {
                        $sms_message = preg_replace("/{{TESTPACK}}/", 'Test', $sms_message);
                    }
                    $sms_message = preg_replace("/{{TESTPACKLIST}}/", $test_package, $sms_message);
                    $sms_message = preg_replace("/{{TOTALPRICE}}/", $check_price, $sms_message);
                    $configmobile = $this->config->item('booking_alert_phone');
                    foreach ($configmobile as $p_key) {
                        //$notification::send($configmobile, $sms_message);
                        $this->job_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $p_key, "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
                    }
                }
                /* Delhi and gurgaon booking alert sms end */
                // Case Back Email end
                $this->book_phlebo($j_id);
                $this->session->set_flashdata('payment_success', array("Thank You..! Your Test has been Booked Successfully."));
                $url = "/";
                //redirect('user_master');
                redirect("user_test_master/invoice/" . $insert);
            } else {
                $this->session->set_flashdata("unsuccess", array("Transaction Fail Try again."));
                redirect("/", "refresh");
            }
        } else {
            redirect("/", "refresh");
        }
    }

    function book_test1() {

        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $this->load->helper("Email");
        $email_cnt = new Email;

        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];

        $test1 = $this->uri->segment(3);
        if ($test1 != "") {
            $test = explode(',', $test1);
        }
        $doctor = $this->input->post('doctor');
        $refe = $this->input->post('reference');
        $order_id = $this->get_job_id();
        $date = date('Y-m-d H:i:s');
        $price = 0;
        $test_name_mail = array();
        /*  for ($i = 0; $i < count($test); $i++) {
          $data = $this->user_test_master_model->master_fun_get_tbl_val("test_master", array("status" => 1, 'id' => $test[$i]), array("id", "asc"));
          $price = $price + $data[0]['price'];
          $test_name_mail[$i]=$data[0]['test_name'];
          } */
        $maxid = $this->user_wallet_model->total_wallet($uid);
        $data['total'] = $this->user_wallet_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "id" => $maxid), array("id", "asc"));
        $wallet_amount = $data['total'][0]['total'];
        if (empty($test)) {
            $this->session->set_flashdata("error", array("Please Select Your Test From List"));
            redirect("user_test_master", "refresh");
        }
        $insert = $this->user_test_master_model->master_fun_insert("job_master", array("cust_fk" => $uid, "date" => $date, "order_id" => $order_id, "payment_type" => "Wallet", "doctor" => $doctor, "other_reference" => $refe));
        $package_list = explode(",", $test1);
        foreach ($package_list as $key) {
                    $testid = explode("-", $key);
                    if ($testid[0] == 't') {
                        $tst_price = $this->job_model->get_val("select price from test_master_city_price where test_fk='" . $testid[1] . "' and city_fk='" . $test_city . "' and status='1'");
                        $this->user_test_master_model->master_fun_insert("job_test_list_master", array('job_fk' => $insert, "test_fk" => $testid[1],"price" => $tst_price[0]["price"]));
                        //$this->user_test_master_model->master_fun_insert("booked_job_test", array("job_fk" => $insert, "test_city" => $test_city, "test_fk" => "t-" . $testid[1], "price" => $tst_price[0]["price"]));
                    }
                    if ($testid[0] == 'p') {
                        $tst_price = $this->user_test_master_model->get_val("select d_price from package_master_city_price where package_fk='" . $testid[1] . "' and city_fk='" . $test_city . "' and status='1'");
                        $this->user_test_master_model->master_fun_insert("book_package_master", array("cust_fk" => $uid, "package_fk" => $testid[1], 'job_fk' => $insert, "status" => "1", "type" => "2","price" => $tst_price[0]["d_price"]));
                        //$this->user_test_master_model->master_fun_insert("booked_job_test", array("job_fk" => $insert, "test_city" => $test_city, "test_fk" => "p-" . $testid[1], "price" => $tst_price[0]["d_price"]));
                        $this->check_active_package($testid[1], $insert);
                    }
                }
        $cnt = 0;
        foreach ($test as $key) {
            $ex = explode('-', $key);
            $first_pos = $ex[0];
            $id = $ex[1];
            if ($first_pos == "t") {
                $price1 = $this->user_test_master_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master`.`id`='" . $id . "'  AND `test_master_city_price`.`city_fk`='" . $data["test_city_session"] . "'");
                //$price1 = $this->user_test_master_model->master_fun_get_tbl_val("test_master", array("status" => 1, "id" => $id), array("test_name", "asc"));
                $price += $price1[0]['price'];
            }
            if ($first_pos == "p") {
                $price1 = $this->job_model->get_val("SELECT 
          `package_master`.*,
          `package_master_city_price`.`a_price` AS `a_price`,
          `package_master_city_price`.`d_price` AS `d_price`
          FROM
          `package_master`
          INNER JOIN `package_master_city_price`
          ON `package_master`.`id` = `package_master_city_price`.`package_fk`
          WHERE `package_master`.`status` = '1'
          AND `package_master_city_price`.`status` = '1' AND `package_master_city_price`.`city_fk` = '" . $data["test_city_session"] . "' AND `package_master`.`id`='" . $id . "'");
                //      $price1 = $this->user_test_master_model->master_fun_get_tbl_val("package_master", array("status" => 1, "id" => $id), array("title", "asc"));
                $price += $price1[0]['d_price'];
            }
            $cnt++;
        }
        $j_id = $insert;
        $this->check_prescription_test($j_id);
        $update = $this->user_test_master_model->master_fun_update("job_master", array('id', $insert), array("price" => $price));
        if ($update) {
            $query = $this->user_test_master_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $uid), array("id", "desc"));
            $total = $query[0]['total'];

            $query = $this->user_test_master_model->master_fun_get_tbl_val("offer_master", array("status" => 1), array("id", "desc"));
            $caseback_per = $query[0]['caseback_per'];
            $caseback_amount = ($price * $caseback_per) / 100;

            $data = array(
                "cust_fk" => $uid,
                "debit" => $price,
                "total" => $total - $price,
                "job_fk" => $insert,
                "created_time" => date('Y-m-d H:i:s')
            );
            $insert1 = $this->user_test_master_model->master_fun_insert("wallet_master", $data);
            /* $query = $this->user_test_master_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $uid), array("id", "desc"));
              $total = $query[0]['total'];
              $data = array(
              "cust_fk" => $uid,
              "credit" => $caseback_amount,
              "total" => $total + $caseback_amount,
              "type" => "Case Back",
              "job_fk" => $insert,
              "created_time" => date('Y-m-d H:i:s')
              );
              $insert = $this->user_test_master_model->master_fun_insert("wallet_master", $data);
             */
            $query = $this->user_test_master_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $uid), array("id", "desc"));
            $Current_wallet = $query[0]['total'];

            $destail = $this->user_wallet_model->master_fun_get_tbl_val("customer_master", array("status" => 1, "id" => $uid), array("id", "asc"));
            $family_member_name = $this->user_test_master_model->get_family_member_name($j_id);
            if (!empty($family_member_name)) {
                $destail[0]['full_name'] = $family_member_name[0]["name"];
            }
            $config['mailtype'] = 'html';
            $this->email->initialize($config);

            /* $message = "You recently requested to reset your password for your  Patholab Account. Click the button below to reset it.<br/><br/>";
              $message .= "<a href='".base_url()."user_get_password/index/".$rs."' style='background-color:#dc4d2f;color:#ffffff;display:inline-block;font-size:15px;line-height:45px;text-align:center;width:200px;border-radius:3px;text-decoration:none;'>Reset your password</a><br/><br/>";
              $message .= "If you did not request a password reset, please ignore this email or reply to let us know.<br/><br/>";
              $message .= "Thanks <br/> Patholab"; */
            $family_member_name = $this->user_test_master_model->get_family_member_name($j_id);
            if (!empty($family_member_name)) {
                $destail[0]['full_name'] = $family_member_name[0]["name"];
            }
            $message = '<div style="padding:0 4%;">
                    <h4><b>Dear </b>' . $destail[0]['full_name'] . '</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Test has been book successfully. </p>
                        
		<p style="color:#7e7e7e;font-size:13px;"> Rs. ' . $price . ' Debited From your account. </p>
		<p style="color:#7e7e7e;font-size:13px;">Your Current balance is Rs. ' . $total . '</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
            $message = $email_cnt->get_design($message);
            $this->email->to($destail[0]['email']);
            $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
            $this->email->subject('Test Book Successfully');
            $this->email->message($message);
            $this->email->send();
            $family_member_name = $this->user_test_master_model->get_family_member_name($j_id);
            if (!empty($family_member_name)) {
                $c_email = $family_member_name[0]["email"];
                if (!empty($c_email)) {
                    $this->email->to($c_email);
                    $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
                    $this->email->subject('Test Book Successfully');
                    $this->email->message($message);
                    $this->email->send();
                }
            }
            /*
              // Case Back Email start

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
              <h4><b>Dear </b>' . $destail[0]['full_name'] . '</h4>
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
              $this->email->to($destail[0]['email']);
              $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
              $this->email->subject('CashBack');
              $this->email->message($message);
              $this->email->send();
             */

            $this->session->set_flashdata("payment_success", array("Your Test Book Successfully. Our Representative will Call You shortly"));
            redirect("user_master");
        }
    }

    function get_city($id) {
        $data["state"] = $this->user_test_master_model->master_fun_get_tbl_val("city", array("status" => 1, "state_fk" => $id), array("city_name", "asc"));
        if (!empty($data["state"])) {
            echo '<option value="">--Select--</option>';
        } else {
            echo '<option value="">--Data not available--</option>';
        }
        foreach ($data["state"] as $key) {
            echo '<option value="' . $key["id"] . '">' . ucfirst($key["city_name"]) . '</option>';
        }
    }

    function cash_on_delivery() {
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        
        $this->load->helper("Email");
        $email_cnt = new Email;
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        $mobile = $this->input->get_post('mobile');
        $select_tests = $this->input->get_post('test_ids');
        $total = $this->input->get_post('price');
        $wallet_price = $this->input->get_post('wallet_price');
        $method = $this->input->get_post('method');
        $method = str_replace(' ', '', $method);
        $test_city = $this->input->get_post('test_city');
        $dob = $this->input->get_post('dob');
        $gender = $this->input->get_post('gender');
        $source_by = $this->input->get_post('source_by');
        //echo "<pre>";print_R($_POST);die();
        $order_id = $this->get_job_id($test_city);
        $booking_info = $this->uri->segment(3);
        $date = date('Y-m-d H:i:s');
        
        //print_r(array("mobile" => $mobile,"gender"=>$gender,"dob"=>$dob)); die();
        $this->service_model->master_fun_update1("customer_master", array("id" => $uid), array("gender" => $gender, "dob" => $dob));
        if ($uid != NULL && $select_tests != Null && $total != Null) {
            $booking_info_data = $this->user_test_master_model->master_fun_get_tbl_val("booking_info", array('id' => $booking_info), array("id", "asc"));
            $data = array(
                "order_id" => $order_id,
                "cust_fk" => $uid,
                "date" => $date,
                "other_reference" => $source_by,
                "price" => $total + $wallet_price,
                "status" => '1',
                "payment_type" => "Cash On Delivery",
                "mobile" => $mobile,
                "test_city" => $test_city,
                "booking_info" => $booking_info,
                "address" => $booking_info_data[0]["address"]
            );
            $tl_sms_price = $total + $wallet_price;
            $insert = $this->user_test_master_model->master_fun_insert("job_master", $data);
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
            if (!empty($packageid)) {
                $pid = implode($packageid, ',');
            } else {
                $pid = '';
            }
            if (!empty($testid)) {
                $tid = implode($testid, ',');
            } else {
                $tid = '';
            }

            if ($insert) {
                $sms_message = $this->job_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "book_login"), array("id", "asc"));
                $user = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "id" => $uid), array("id", "asc"));
                $family_member_name = $this->job_model->get_family_member_name($insert);
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
                $amount = $total + $wallet_price;
                //$sms_message = preg_replace("/{{TOTALPRICE}}/", $tl_sms_price, $sms_message);
                $this->load->helper("sms");
                $notification = new Sms();
                if ($cmobile != NULL) {
                    $notification->send($cmobile, $sms_message);
                }
                if (!empty($family_member_name)) {
                    $this->job_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $mobile, "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
                }
                $this->check_prescription_test($insert);
                $data = $this->user_test_master_model->master_fun_get_tbl_val("job_master", array("cust_fk" => $uid), array("id", "asc"));
                $count = count($data);
                if ($count == 1) {
                    $refdata = $this->user_test_master_model->master_fun_get_tbl_val("refer_code_master", array("status" => 1, "cust_fk" => $uid), array("id", "asc"));
                    $userdcode = $refdata[0]['used_code'];
                    if ($usercode != "") {
                        $refdata1 = $this->user_test_master_model->master_fun_get_tbl_val("refer_code_master", array("status" => 1, "used_code" => $userdcode), array("id", "asc"));
                    }
                    if (!empty($refdata1)) {
                        $refdata1 = $this->user_test_master_model->master_fun_get_tbl_val("refer_code_master", array("status" => 1, "refer_code" => $userdcode), array("id", "asc"));
                        $usedcust_fk = $refdata1[0]['cust_fk'];
                        $query = $this->user_test_master_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $usedcust_fk), array("id", "desc"));
                        $waltotal = $query[0]['total'];
                        $datacust = $this->user_test_master_model->master_fun_get_tbl_val("customer_master", array("id" => $usedcust_fk), array("id", "asc"));
                        $refemail = $datacust[0]['email'];
                        $ref_name = $datacust[0]['full_name'];

                        $data = array(
                            "cust_fk" => $usedcust_fk,
                            "credit" => 100,
                            "total" => $waltotal + 100,
                            "type" => "referral code",
                            "created_time" => date('Y-m-d H:i:s')
                        );
                        $insert1 = $this->user_test_master_model->master_fun_insert("wallet_master", $data);

                        $config['mailtype'] = 'html';
                        $this->email->initialize($config);

                        $message = '<div style="padding:0 4%;">
                    <h4><b>You have one more refferal</b></h4>
                        <p><b>Dear </b>' . ucfirst($ref_name) . '</p>
                        <p style="color:#7e7e7e;font-size:13px;">Congratulation You have One more refferal  </p>
                        <p style="color:#7e7e7e;font-size:13px;">Rs.100 has been Credited in your wallet</p>
                </div>';
                        $message = $email_cnt->get_design($message);
                        $this->email->to($refemail);
                        $this->email->from("donotreply@airmedpathlabs.com", "AirmedLabs");
                        $this->email->subject("Refferal Amount Credited");
                        $this->email->message($message);
                        $this->email->send();
                    }
                }
                //end referral for first test book
                $test = explode(',', $select_tests);
                $test_package_name = array();
                $check_price = 0;
                foreach ($test as $key) {
                    $ex = explode('-', $key);
                    $first_pos = $ex[0];
                    $id = $ex[1];
                    if ($first_pos == "t") {
                        //$price1 = $this->user_test_master_model->master_fun_get_tbl_val("test_master", array("status" => 1, "id" => $id), array("test_name", "asc"));
                        $price1 = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $test_city . "' AND `test_master`.`id`='" . $id . "'");
                        $price += $price1[0]['price'];
                        $check_price += $price1[0]['price'];
                        $test_package_name[] = $price1[0]['test_name'];
                    }
                    if ($first_pos == "p") {
                        //$price1 = $this->user_test_master_model->master_fun_get_tbl_val("package_master", array("status" => 1, "id" => $id), array("title", "asc"));
                        $price1 = $this->job_model->get_val("SELECT 
          `package_master`.*,
          `package_master_city_price`.`a_price` AS `a_price`,
          `package_master_city_price`.`d_price` AS `d_price`
          FROM
          `package_master`
          INNER JOIN `package_master_city_price`
          ON `package_master`.`id` = `package_master_city_price`.`package_fk`
          WHERE `package_master`.`status` = '1'
          AND `package_master_city_price`.`status` = '1' AND package_master.`id`='" . $id . "' AND `package_master_city_price`.`city_fk` = '" . $test_city . "' ");
                        $check_price += $price1[0]['d_price'];
                        $test_package_name[] = $price1[0]['title'];
                    }
                    //$cnt++;
                }
                //	print_r($test_package_name); die();
                $j_total_price = $total + $wallet_price;
                $collection_charge = 0;
                if ($j_total_price != $check_price && $j_total_price < 400 && $tl_sms_price != 0) {
                    $collection_charge = 1;
                }
                $query = $this->user_test_master_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $uid), array("id", "desc"));
                $wallettotal = $query[0]['total'];
                $payfrom_wallet = '';
                $payemnttype = "Cash on blood Collection";
                if ($method == "yes") {
                    if ($wallettotal > 0) {
                        if ($wallettotal >= $wallet_price) {

                            $data1 = array(
                                "cust_fk" => $uid,
                                "debit" => $wallet_price,
                                "total" => $wallettotal - $wallet_price,
                                "job_fk" => $insert,
                                "created_time" => date('Y-m-d H:i:s')
                            );

                            $payable = 0;
                            $payfrom_wallet = '<p style="color:#7e7e7e;font-size:13px;"> You paid From Your Wallet Rs. ' . $wallet_price . '  </p>
						<p style="color:#7e7e7e;font-size:13px;"> Your Payable Amount Rs. ' . $total . '  </p> 
						';
                            $payemnttype = "Paid From Wallet";
                        } else {

                            $data1 = array(
                                "cust_fk" => $uid,
                                "debit" => $wallettotal,
                                "total" => 0,
                                "job_fk" => $insert,
                                "created_time" => date('Y-m-d H:i:s')
                            );
                            $payable = $total - $wallettotal;
                            $payfrom_wallet = '<p style="color:#7e7e7e;font-size:13px;"> You paid From Your Wallet Rs. ' . $wallettotal . '  </p>
						<p style="color:#7e7e7e;font-size:13px;"> Your Payable Amount Rs. ' . $total . '  </p> 
						';
                            $payemnttype = "Cash on blood Collection";
                        }
                        if ($wallettotal != 0) {
                            $insert12 = $this->user_test_master_model->master_fun_insert("wallet_master", $data1);
                        }
                        $email_total_amount = $total;
                        $update = $this->service_model->master_fun_update1("job_master", array("id" => $insert), array("payable_amount" => $total, "collection_charge" => $collection_charge));
                    } else {
                        $email_total_amount = $total;
                        $update = $this->service_model->master_fun_update1("job_master", array("id" => $insert), array("payable_amount" => $total, "collection_charge" => $collection_charge));
                    }
                } else {
                    $email_total_amount = $total + $wallet_price;
                    $update = $this->service_model->master_fun_update1("job_master", array("id" => $insert), array("payable_amount" => $total + $wallet_price, "collection_charge" => $collection_charge));
                }

                
                $package_list = explode(",", $select_tests);
                foreach ($package_list as $key) {
                    $testid = explode("-", $key);
                    if ($testid[0] == 't') {
                        $tst_price = $this->job_model->get_val("select price from test_master_city_price where test_fk='" . $testid[1] . "' and city_fk='" . $test_city . "' and status='1'");
                        $this->user_test_master_model->master_fun_insert("job_test_list_master", array('job_fk' => $insert, "test_fk" => $testid[1], "price" => $tst_price[0]["price"]));
                        $this->user_test_master_model->master_fun_insert("booked_job_test", array("job_fk" => $insert, "test_city" => $test_city, "test_fk" => "t-" . $testid[1], "price" => $tst_price[0]["price"]));
                    }
                    if ($testid[0] == 'p') {
                        $tst_price = $this->user_test_master_model->get_val("select d_price from package_master_city_price where package_fk='" . $testid[1] . "' and city_fk='" . $test_city . "' and status='1'");
                        $this->user_test_master_model->master_fun_insert("book_package_master", array("cust_fk" => $uid, "package_fk" => $testid[1], 'job_fk' => $insert, "status" => "1", "type" => "2", "price" => $tst_price[0]["d_price"]));
                        $this->user_test_master_model->master_fun_insert("booked_job_test", array("job_fk" => $insert, "test_city" => $test_city, "test_fk" => "p-" . $testid[1], "price" => $tst_price[0]["d_price"]));
                        $this->check_active_package($testid[1], $insert);
                    }
                }
                
                
                $destail = $this->user_test_master_model->master_fun_get_tbl_val("customer_master", array("status" => 1, "id" => $uid), array("id", "asc"));
                $ttl_prc = $total + $wallet_price;
                $config['mailtype'] = 'html';
                $family_member_name = $this->user_test_master_model->get_family_member_name($j_id);
                if (!empty($family_member_name)) {
                    $destail[0]['full_name'] = $family_member_name[0]["name"];
                }
                $this->email->initialize($config);
                $family_member_name = $this->user_test_master_model->get_family_member_name($j_id);
                if (!empty($family_member_name)) {
                    $destail[0]['full_name'] = $family_member_name[0]["name"];
                }
                $timeslot = $this->job_model->get_val("SELECT ts.start_time,ts.end_time,b.date FROM booking_info as b left join phlebo_time_slot as ts on b.time_slot_fk=ts.id WHERE ts.status='1' AND b.status='1' AND b.id='" . $booking_info . "'");
                $s_time = date('h:i A', strtotime($timeslot[0]["start_time"]));
                $e_time = date('h:i A', strtotime($timeslot[0]["end_time"]));
                $datebb = date("d F,Y", strtotime($timeslot[0]['date']));
                $payable_amt = $total + $wallet_price;
                $message = '<div style="padding:0 4%;">
                    <h4><b>Dear </b>' . $destail[0]['full_name'] . '</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Booking has been successfully. </p>
                     <p style="color:#7e7e7e;font-size:13px;"> You Booked :  ' . implode($test_package_name, ', ') . '  </p>  
                         <p style="color:#7e7e7e;font-size:13px;"> Sample Collection time :  ' . $datebb . ' ' . $s_time . '-' . $e_time . '  </p>  
<p style="color:#7e7e7e;font-size:13px;"> Your Total Amount is Rs.' . $payable_amt . '  </p> ' . $payfrom_wallet . ' 
        <p style="color:#7e7e7e;font-size:13px;"> Payment Type : ' . $payemnttype . '</p>  
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
                $family_member_name = $this->user_test_master_model->get_family_member_name($insert);
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
                $this->job_model->master_fun_insert("test", array("test" => $message));
                if ($booking_info_data[0]["emergency"] == 0) {
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
                    $configmobile = $this->config->item('admin_alert_phone');
                    foreach ($configmobile as $p_key) {
                        //$notification::send($configmobile, $sms_message);
                        $this->job_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $p_key, "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
                    }
                }
                /* Delhi and gurgaon booking alert sms start */
                $test_city = $this->session->userdata("test_city");
                if ($test_city == 4 || $test_city == 5) {
                    $sms_message = '';
                    $sms_message = $this->job_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "delhi_booking"), array("id", "asc"));
                    $city_name = $this->job_model->master_fun_get_tbl_val("test_cities", array('status' => 1, "id" => $this->session->userdata("test_city")), array("id", "asc"));
                    $sms_message = preg_replace("/{{MOBILE}}/", $mobile, $sms_message[0]["message"]);
                    $sms_message = preg_replace("/{{CITY}}/", $city_name[0]["name"], $sms_message);
                    if ($pid != '' && $tid != '') {
                        $sms_message = preg_replace("/{{TESTPACK}}/", 'Test/Package', $sms_message);
                    } else if ($pid != '') {
                        $sms_message = preg_replace("/{{TESTPACK}}/", 'Package', $sms_message);
                    } else {
                        $sms_message = preg_replace("/{{TESTPACK}}/", 'Test', $sms_message);
                    }
                    $sms_message = preg_replace("/{{TESTPACKLIST}}/", $test_package, $sms_message);
                    $sms_message = preg_replace("/{{TOTALPRICE}}/", $tl_sms_price, $sms_message);
                    $configmobile = $this->config->item('booking_alert_phone');
                    foreach ($configmobile as $p_key) {
                        //$notification::send($configmobile, $sms_message);
                        $this->job_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $p_key, "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
                    }
                }
                //$file = $this->pdf_invoice($insert);
                
                //$this->service_model->master_fun_update1("job_master", array("id" => $insert), array("invoice" => $file));
                /* Delhi and gurgaon booking alert sms end */
                $this->book_phlebo($insert);
                $this->session->set_flashdata("payment_success", array("Cash on delivery request successfully received."));
                //redirect("user_master");
                redirect("user_test_master/invoice/" . $insert);
            }
        } else {
            echo "<script>alert('Oops somthing wrong Try again!');</script>";
            redirect("/");
        }
    }

    function price_cal() {

        $test = $this->input->post('id');
        $test1 = explode(",", $test);
        foreach ($test1 as $key) {
            $ex = explode('-', $key);
            $first_pos = $ex[0];
            $id = $ex[1];
            if ($first_pos == "t") {
                //$price1 = $this->user_test_master_model->master_fun_get_tbl_val("test_master", array("status" => 1, "id" => $id), array("test_name", "asc"));
                $price1 = $this->user_master_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master`.`id`='" . $id . "' AND `test_master_city_price`.`city_fk`='" . $data["test_city_session"] . "'");
                $price += $price1[0]['price'];
            }
            if ($first_pos == "p") {
                $price1 = $this->job_model->get_val("SELECT 
          `package_master`.*,
          `package_master_city_price`.`a_price` AS `a_price`,
          `package_master_city_price`.`d_price` AS `d_price`
          FROM
          `package_master`
          INNER JOIN `package_master_city_price`
          ON `package_master`.`id` = `package_master_city_price`.`package_fk`
          WHERE `package_master`.`status` = '1'
          AND `package_master_city_price`.`status` = '1' AND `package_master_city_price`.`city_fk` = '" . $data["test_city_session"] . "' AND `package_master`.`id`='" . $id . "'");

//                $price1 = $this->user_test_master_model->master_fun_get_tbl_val("package_master", array("status" => 1, "id" => $id), array("title", "asc"));
                $price += $price1[0]['d_price'];
            }

            //echo $price1[0]['price'];die();

            $cnt++;
        }
        echo $price;
    }

    function book_phlebo($job_id) {

        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
        $login_id = $data["login_data"]["id"];
        $new_job_details = $this->user_test_master_model->master_fun_get_tbl_val("job_master", array('id' => $job_id), array("id", "asc"));
        $booking_info = $this->user_test_master_model->master_fun_get_tbl_val("booking_info", array('id' => $new_job_details[0]["booking_info"]), array("id", "asc"));
        $job_details = $this->user_test_master_model->master_fun_get_tbl_val("job_master", array('id' => $job_id), array("id", "asc"));
        /* Nishit send phebo notification start */
        $phlebo_list = $this->user_test_master_model->master_fun_get_tbl_val("phlebo_master", array('status' => "1", "type" => 1), array("id", "asc"));
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
            $customer_details = $this->user_test_master_model->master_fun_get_tbl_val("customer_master", array('id' => $job_details[0]['cust_fk']), array("id", "asc"));
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
            $job_details = $this->get_job_details($job_id);
            $b_details = array();
            foreach ($job_details[0]["book_test"] as $bkey) {
                $b_details[] = $bkey["test_name"] . " Rs." . $bkey["price"];
            }
            foreach ($job_details[0]["book_package"] as $bkey) {
                $b_details[] = $bkey["title"] . " Rs." . $bkey["d_price"];
            }
            $sms_message = $this->user_test_master_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "emergency_book"), array("id", "asc"));
            $sms_message = preg_replace("/{{MOBILE}}/", $c_name . " (" . $cmobile . ")", $sms_message[0]["message"]);
            $sms_message = preg_replace("/{{TESTPACKLIST}}/", implode(",", $b_details), $sms_message);
            $configmobile = $this->config->item('admin_alert_phone');
            foreach ($configmobile as $p_key) {
                $this->job_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $p_key, "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
            }
        }
    }

    function get_job_details($job_id) {
        $job_details = $this->user_test_master_model->master_fun_get_tbl_val("job_master", array("status !=" => 0, "id" => $job_id), array("id", "asc"));
        if (!empty($job_details)) {
            $book_test = $this->user_test_master_model->master_fun_get_tbl_val("job_test_list_master", array("job_fk" => $job_id), array("id", "desc"));
            $book_package = $this->user_test_master_model->master_fun_get_tbl_val("book_package_master", array("job_fk" => $job_id, "status" => "1"), array("id", "desc"));
            $test_name = array();
            foreach ($book_test as $key) {
                //$price1 = $this->user_test_master_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master`.`id`='" . $key["test_fk"] . "'");
                $price1 = $this->user_master_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master`.`id`='" . $key["test_fk"] . "' AND `test_master_city_price`.`city_fk`='" . $job_details[0]["test_city"] . "'");
                $test_name[] = $price1[0];
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
                // print_r($price1); die();
                $package_name[] = $price1[0];
            }
            $job_details[0]["book_package"] = $package_name;
        }
        return $job_details;
    }

    function check_prescription_test($jid) {
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
        $login_id = $data["login_data"]["id"];
        $job_details = $this->get_job_details($jid);
        $jtest = array();
        foreach ($job_details[0]["book_test"] as $jkey) {
            $jtest[] = $jkey["id"];
        }
        $get_user_prescription = $this->user_test_master_model->master_fun_get_tbl_val("prescription_upload", array("cust_fk" => $login_id, "status" => "2"), array("id", "desc"));
        if (!empty($get_user_prescription)) {
            foreach ($get_user_prescription as $key) {
                $p_data = $this->user_test_master_model->master_fun_get_tbl_val("suggested_test", array("p_id" => $key["id"], "status" => "1"), array("id", "desc"));
                $p_tst = array();
                foreach ($p_data as $pkey) {
                    $p_tst[] = $pkey["test_id"];
                }
                $result = array_diff($jtest, $p_tst);
                if (empty($result) && empty($key["job_fk"])) {
                    $this->user_test_master_model->master_fun_update("prescription_upload", array("id", $key["id"]), array("job_fk" => $jid));
                }
            }
        }
    }

    function get_content($URL) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $URL);
        $data = curl_exec($ch);
        curl_close($ch);
    }

    function invoice() {
        $data["login_data"] = loginuser();
        $this->load->model('add_result_model');
        $cid = $data['cid'] = $this->uri->segment(3);
        $data["test_city_session"] = $this->session->userdata("test_city");
        // echo $cid;
        $data['query'] = $this->add_result_model->job_details($cid);
        $get_user_details = $this->add_result_model->master_fun_get_tbl_val("customer_master", array("id" => $data["login_data"]["id"], "status" => "1"), array("id", "desc"));
        $data['book_list'] = array();
        $tid = explode(",", $data['query'][0]['testid']);
        $fast = array();
        if ($data['query'][0]['testid'] != '') {
            foreach ($tid as $tst_id) {
                $para = $this->add_result_model->get_val("SELECT t.test_name as book_name,t.id as tid,p.price as price FROM test_master as t left join test_master_city_price as p  on p.test_fk=t.id WHERE t.status='1' AND p.status='1' AND t.id='" . $tst_id . "' AND p.city_fk='" . $data['query'][0]['test_city'] . "' order by t.test_name ASC");
                array_push($data['book_list'], $para);
                $test_fast = $this->add_result_model->get_val("SELECT fasting_requird FROM test_master WHERE status='1' AND id='" . $tst_id . "'");
                array_push($fast, $test_fast[0]['fasting_requird']);
            }
        }
        $pid = explode("%", $data['query'][0]['packageid']);
        if ($data['query'][0]['packageid'] != '') {
            foreach ($pid as $pack_id) {
                $para = $this->add_result_model->get_val("SELECT p.id as pid,p.title as book_name,pr.d_price as price FROM package_master as p left join package_master_city_price as pr on pr.package_fk=p.id WHERE p.status='1' AND pr.status='1' AND p.id='" . $pack_id . "' AND pr.city_fk='" . $data['query'][0]['test_city'] . "' order by p.title ASC");
                array_push($data['book_list'], $para);
            }
        }
        if (in_array("1", $fast)) {
            $data['fasting'] = 'Fasting required for 12 hours.';
        } else {
            $data['fasting'] = 'Fasting not required.';
        }
        $data['time'] = $this->add_result_model->get_val("SELECT ts.start_time,ts.end_time FROM booking_info as b left join phlebo_time_slot as ts on b.time_slot_fk=ts.id WHERE ts.status='1' AND b.status='1' AND b.id='" . $data['query'][0]['booking_info'] . "'");
        if (empty($get_user_details[0]["age"])) {
            if (!empty($get_user_details[0]["dob"])) {
                $this->load->library("Util");
                $util = new Util;
                $new_age = $util->get_age($get_user_details[0]["dob"]);
                $data['query'][0]["age"] = $new_age[0];
            }
        }
        //echo "<pre>"; print_r($data['query']); die();
        $data["wallet_manage"] = $this->add_result_model->master_fun_get_tbl_val("wallet_master", array("job_fk" => $data['cid'], "status" => "1"), array("id", "desc"));
        $this->load->view('user/header', $data);
        $this->load->view("user/user_job_invoice", $data);
        $this->load->view('user/footer', $data);
    }

    function pdf_invoice($id) {
        $data["login_data"] = loginuser();
        $this->load->model('add_result_model');
        $data['query'] = $this->add_result_model->job_details($id);
        $data['book_list'] = array();
        $tid = explode(",", $data['query'][0]['testid']);
        $fast = array();
        $emergency_tests = $this->job_model->master_fun_get_tbl_val("booking_info", array('id' => $data['query'][0]["booking_info"]), array("id", "asc"));


        $f_data = $this->job_model->master_fun_get_tbl_val("customer_family_master", array('id' => $emergency_tests[0]["family_member_fk"]), array("id", "asc"));
        $f_data1 = $this->job_model->master_fun_get_tbl_val("relation_master", array('id' => $f_data[0]["relation_fk"]), array("id", "asc"));
        $relation = "Self";
        if (!empty($f_data1)) {
            $relation = ucfirst($f_data1[0]["name"] . " (" . $f_data[0]["name"] . ")");
        }
        $data['test_for'] = $relation;
        $data['relation'] = $f_data;
        if ($data['query'][0]['testid'] != '') {
            $get_user_details = $this->add_result_model->master_fun_get_tbl_val("customer_master", array("id" => $data["login_data"]["id"], "status" => "1"), array("id", "desc"));

            foreach ($tid as $tst_id) {
                $para = $this->add_result_model->get_val("SELECT t.test_name as book_name,t.id as tid,p.price as price FROM test_master as t left join test_master_city_price as p  on p.test_fk=t.id WHERE t.status='1' AND p.status='1' AND t.id='" . $tst_id . "' AND p.city_fk='" . $data['query'][0]['test_city'] . "' order by t.test_name ASC");
                array_push($data['book_list'], $para);
                $test_fast = $this->add_result_model->get_val("SELECT fasting_requird FROM test_master WHERE status='1' AND id='" . $tst_id . "'");
                array_push($fast, $test_fast[0]['fasting_requird']);
            }
        }
        $pid = explode("%", $data['query'][0]['packageid']);
        if ($data['query'][0]['packageid'] != '') {
            foreach ($pid as $pack_id) {
                $para = $this->add_result_model->get_val("SELECT p.id as pid,p.title as book_name,pr.d_price as price FROM package_master as p left join package_master_city_price as pr on pr.package_fk=p.id WHERE p.status='1' AND pr.status='1' AND p.id='" . $pack_id . "' AND pr.city_fk='" . $data['query'][0]['test_city'] . "' order by p.title ASC");
                array_push($data['book_list'], $para);
            }
        }
        if (in_array("1", $fast)) {
            $data['fasting'] = 'Fasting required for 12 hours.';
        } else {
            $data['fasting'] = 'Fasting not required for 12 hours.';
        }
        if (empty($get_user_details[0]["age"])) {
            if (!empty($get_user_details[0]["dob"])) {
                $this->load->library("Util");
                $util = new Util;
                $new_age = $util->get_age($get_user_details[0]["dob"]);
                $data['query'][0]["age"] = $new_age[0];
            }
        }
        $data["wallet_manage"] = $this->add_result_model->master_fun_get_tbl_val("wallet_master", array("job_fk" => $id, "status" => "1"), array("id", "desc"));
        $data['time'] = $this->add_result_model->get_val("SELECT ts.start_time,ts.end_time FROM booking_info as b left join phlebo_time_slot as ts on b.time_slot_fk=ts.id WHERE ts.status='1' AND b.status='1' AND b.id='" . $data['query'][0]['booking_info'] . "'");
        //echo "<pre>"; print_r($data['parameter_list']); die();
        $pdfFilePath = FCPATH . "/upload/result/" . $data['query'][0]['order_id'] . "_invoice.pdf";
        $data['page_title'] = 'AirmedLabs'; // pass data to the view
        ini_set('memory_limit', '32M'); // boost the memory limit if it's low <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="?" draggable="false" class="emoji">
        $html = $this->load->view('user/user_job_invoice_pdf', $data, true); // render the view into HTML
        //$param = '"en-GB-x","A4","","",10,10,0,10,6,3,"P"'; // Landscape
        //$lorem = utf8_encode($html); // render the view into HTML
        //$html = "<!DOCTYPE html>                         <html><body>\u0627\u0644\u0643\u0647\u0631\u0628\u0627\u0621 \u0648 \u0627\u0644\u0633\u0628\u0627\u0643\u0629</body></html>      ";
        ob_end_flush();
        $this->load->library('pdf');

        $pdf = $this->pdf->load();
        $pdf->autoScriptToLang = true;
        $pdf->baseScript = 1; // Use values in classes/ucdn.php  1 = LATIN
        $pdf->autoVietnamese = true;
        $pdf->autoArabic = true;

        $pdf->autoLangToFont = true;
        $pdf->AddPage('p', // L - landscape, P - portrait
                '', '', '', '', 5, // margin_left
                5, // margin right
                2, // margin top
                2, // margin bottom
                2, // margin header
                2); // margin footer
        //$pdf->AddPage('P', '', 1, 'i', 'on');
        //$pdf->SetDirectionality('rtl');
        /* $pdf->AddPage('P', // L - landscape, P - portrait
          '', '', '', '', 00, // margin_left
          0, // margin right
          0, // margin top
          0, // margin bottom
          0, // margin header
          0); */

        //$pdf->SetDisplayMode('fullpage');
        //$pdf=new mPDF('utf-8','A4');
        //$pdf->debug = true;
        // $pdf->h2toc = array('H2' => 0);
        // $html = '';
        // Split $lorem into words
        $pdf->WriteHTML($html);
        $pdf->debug = true;
        $pdf->allow_output_buffering = TRUE;
        //$pdf->SetFooter('www.' . $_SERVER['HTTP_HOST'] . '||' . $new_time); // Add a footer for good measure <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="?" draggable="false" class="emoji">
        // $pdf->WriteHTML($html); // write the HTML into the PDF
        $pdf->Output($pdfFilePath, 'F'); // save to file because we can
        $name = $data['query'][0]['order_id'] . "_invoice.pdf";
        $this->add_result_model->master_fun_update('job_master', array('id', $id), array("invoice" => $name));
        //redirect("/upload/result/" . $data['query'][0]['order_id'] . "_invoice.pdf");
        return $name;
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

    function nishit_test() {
        $this->load->library("Util");
        $util = new Util;
        $util->job_book();
    }

}
