<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Registration_admin extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('registration_admin_model');
        $this->load->model('user_call_model');
        $this->load->model('job_model');
        $this->load->model('customer_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('pushserver');
        $this->load->library('email');
        $this->load->helper("Email");
        $data["login_data"] = logindata();
        $this->load->helper('string');
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
        $data['success'] = $this->session->flashdata("success");
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required');
        $this->form_validation->set_rules('test_city', 'Test city', 'trim|required');
        $data['branchlist'] = $this->registration_admin_model->get_val("SELECT * from branch_master where status='1'");
        $data["cntr_arry"] = array();
        $data["branch_city_arry"] = array();
        if (!empty($data["login_data"]['branch_fk'])) {
            foreach ($data["login_data"]['branch_fk'] as $key) {
                $data["cntr_arry"][] = $key["branch_fk"];
                $b_data = $this->registration_admin_model->get_val("SELECT * from branch_master where id='" . $key["branch_fk"] . "'");
                $data["branch_city_arry"][] = $b_data[0]["city"];
            }
        }
        $data["cntr_arry"] = array_values(array_unique($data["cntr_arry"]));
        $data["branch_city_arry"] = array_values(array_unique($data["branch_city_arry"]));
        //print_r($data["cntr_arry"]); print_r($data["branch_city_arry"]); die();
        if ($this->form_validation->run() != FALSE) {
            $customer = $this->input->post("customer");
            $name = $this->input->post("name");
            $phone = $this->input->post("phone");
            $email = $this->input->post("email");
            if ($email == '') {
                $email = 'noreply@airmedlabs.com';
            }
            $gender = $this->input->post("gender");
            $test_city = $this->input->post("test_city");
            $address = $this->input->post("address");
            $branch = $this->input->post("branch");
            $birth_date = $this->input->post("dob");
            $year = $this->input->post("year");
            $month = $this->input->post("month");
            $day = $this->input->post("day");
            $phone_no = $this->input->post("phone_no");
            $sample_form = $this->input->post("sample_form");
            $phlebo = $this->input->post("phlebo");
            $phlebo_date = $this->input->post("phlebo_date");
            $phlebo_time = $this->input->post("phlebo_time");
            $dispatch_at = $this->input->post("dispatch_at");
            $referral_no = $this->input->post("referral_no");
            $referral_by = $this->input->post("referral_by");
            $urgent = $this->input->post("urgent");
            $payment_type = $this->input->post("payment_type");
            $note = $this->input->post("note");
            $discount = $this->input->post("discount");
            $payable = $this->input->post("payable");
            $test = $this->input->post("test");
            $source = $this->input->post("source");
            $order_id = $this->get_job_id($test_city);
            $date = date('Y-m-d H:i:s');



            if ($customer != '') {
                $c_data = array(
                    "full_name" => $name,
                    "gender" => $gender,
                    "email" => $email,
                    "mobile" => $phone,
                    "address" => $address,
                    "test_city" => $test_city,
                    "dob" => $birth_date
                );
                $this->registration_admin_model->master_fun_update("customer_master", array("id", $customer), $c_data);

                $price = 0;
                foreach ($test as $key) {
                    $tn = explode("-", $key);
                    if ($tn[0] == 't') {
                        $result = $this->registration_admin_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $test_city . "' AND `test_master`.`id`='" . $tn[1] . "'");
                        $price += $result[0]["price"];
                        $test_package_name[] = $result[0]["test_name"];
                    }
                    if ($tn[0] == 'p') {
                        //print_r(array('package_fk' => $tn[1], "city_fk" => $test_city)); die();
                        $query = $this->db->get_where('package_master_city_price', array('package_fk' => $tn[1], "city_fk" => $test_city, "status" => "1"));
                        $result = $query->result();
                        $query1 = $this->db->get_where('package_master', array('id' => $tn[1]));
                        $result1 = $query1->result();
                        $price += $result[0]->d_price;
                        $test_package_name[] = $result1[0]->title;
                    }
                }
                /* Nishit book phlebo start */
                $test_for = $this->input->post("test_for");
                $testforself = "self";
                $family_mem_id = 0;
                if ($test_for == "new") {
                    $f_name = $this->input->post("f_name");
                    $family_relation = $this->input->post("family_relation");
                    $relation_details = $this->job_model->get_val("SELECT * from relation_master where id='" . $family_relation . "'");
                    $f_dob = $this->input->post("f_dob");
                    $f_phone = $this->input->post("f_phone");
                    $f_email = $this->input->post("f_email");
                    $family_gender = $this->input->post("family_gender");
                    if ($f_name != '' && $family_relation != '') {
                        $family_mem_id = $this->job_model->master_fun_insert("customer_family_master", array("user_fk" => $customer, "gender" => $family_gender, "dob" => $f_dob, "name" => $f_name, "relation_fk" => $family_relation, "email" => $f_email, "phone" => $f_phone, "status" => "1", "created_date" => date("Y-m-d H:i:s")));
                        $testforself = "family";
                    }
                } else if ($test_for != '') {
                    $family_mem_id = $test_for;
                    $testforself = "family";
                }
                $address = $this->input->get_post("address");
                $date1 = $this->input->get_post("phlebo_date");
                $time_slot_id = $this->input->get_post("phlebo_time");
                /* $emergency_req = $this->input->get_post("emergency_req");
                  if ($emergency_req == "true") {
                  $emergency_req = 1;
                  $time_slot_id = 0;
                  } else {
                  $emergency_req = 0;
                  } */
                //echo "<pre>";print_r(array("user_fk" => $uid, "type" => $b_type, "family_member_fk" => $family_mem_id, "address" => $address, "date" => $date1, "time_slot_fk" => $time_slot_id, "emergency" => $emergency_req, "status" => "1", "createddate" => date("Y-m-d H:i:s"))); die();
                $booking_fk = $this->job_model->master_fun_insert("booking_info", array("user_fk" => $customer, "type" => $testforself, "family_member_fk" => $family_mem_id, "address" => $address, "date" => $date1, "time_slot_fk" => $time_slot_id, "emergency" => $urgent, "status" => "1", "createddate" => date("Y-m-d H:i:s")));
                /* Nishit book phlebo end */
                $data = array(
                    "order_id" => $order_id,
                    "cust_fk" => $customer,
                    "doctor" => $referral_by,
                    "other_reference" => $souce,
                    "date" => $date,
                    "price" => $price,
                    "status" => '6',
                    "payment_type" => "Cash On Delivery",
                    "discount" => $discount,
                    "payable_amount" => $payable,
                    "test_city" => $test_city,
                    "note" => $this->input->post('note'),
                    "added_by" => $data["login_data"]["id"],
                    "booking_info" => $booking_fk,
                    "branch_fk" => $branch,
                    "date" => date("Y-m-d H:i:s")
                );

                $insert = $this->registration_admin_model->master_fun_insert("job_master", $data);
                foreach ($test as $key) {
                    $tn = explode("-", $key);
                    if ($tn[0] == 't') {
                        $this->registration_admin_model->master_fun_insert("job_test_list_master", array('job_fk' => $insert, "test_fk" => $tn[1]));
                    }
                    if ($tn[0] == 'p') {
                        $this->registration_admin_model->master_fun_insert("book_package_master", array("cust_fk" => $uid, "package_fk" => $tn[1], 'job_fk' => $insert, "status" => "1", "type" => "2"));
                    }
                }
                $reg_count = $this->registration_admin_model->master_num_rows('registration_admin', array("status" => '1', "customer_id" => $customer));
                if ($reg_count == 0) {
                    $data = array(
                        "name" => $name,
                        "gender" => $gender,
                        "birthdate" => $birth_date,
                        "age_year" => $year,
                        "age_month" => $month,
                        "age_day" => $day,
                        "mobile_no" => $phone,
                        "phone_no" => $phone_no,
                        "email_id" => $email,
                        "sample_form" => $sample_form,
                        "city" => $test_city,
                        "address" => $address,
                        "phlebo" => $phlebo,
                        "dispatch_at" => $dispatch_at,
                        "referral_no" => $referral_no,
                        "referral_by" => $referral_by,
                        "urgent" => $urgent,
                        "payment_type" => $payment_type,
                        "branch_id" => $branch,
                        "customer_id" => $customer,
                        "source_by" => $source,
                        "created_by" => $data["login_data"]["id"],
                        "created_date" => date('Y-m-d H:i:s')
                    );
                    $this->registration_admin_model->master_fun_insert("registration_admin", $data);
                } else {
                    $data = array(
                        "name" => $name,
                        "gender" => $gender,
                        "birthdate" => $birth_date,
                        "age_year" => $year,
                        "age_month" => $month,
                        "age_day" => $day,
                        "mobile_no" => $phone,
                        "phone_no" => $phone_no,
                        "email_id" => $email,
                        "sample_form" => $sample_form,
                        "city" => $test_city,
                        "address" => $address,
                        "phlebo" => $phlebo,
                        "dispatch_at" => $dispatch_at,
                        "referral_no" => $referral_no,
                        "referral_by" => $referral_by,
                        "urgent" => $urgent,
                        "payment_type" => $payment_type,
                        "branch_id" => $branch,
                        "customer_id" => $customer,
                        "source_by" => $source,
                        "modified_by" => $data["login_data"]["id"],
                        "modified_date" => date('Y-m-d H:i:s')
                    );
                    $this->registration_admin_model->master_fun_update("registration_admin", array("customer_id", $customer), $data);
                }
            } else {
                $password = rand(11111111, 9999999);
                $c_data = array(
                    "full_name" => $name,
                    "gender" => $gender,
                    "email" => $email,
                    "mobile" => $phone,
                    "address" => $address,
                    "password" => $password,
                    "test_city" => $test_city,
                    "dob" => $birth_date
                );
                $customer = $this->registration_admin_model->master_fun_insert("customer_master", $c_data);
                $data = array(
                    "name" => $name,
                    "gender" => $gender,
                    "birthdate" => $birth_date,
                    "age_year" => $year,
                    "age_month" => $month,
                    "age_day" => $day,
                    "mobile_no" => $phone,
                    "phone_no" => $phone_no,
                    "email_id" => $email,
                    "sample_form" => $sample_form,
                    "city" => $test_city,
                    "address" => $address,
                    "phlebo" => $phlebo,
                    "dispatch_at" => $dispatch_at,
                    "referral_no" => $referral_no,
                    "referral_by" => $referral_by,
                    "urgent" => $urgent,
                    "payment_type" => $payment_type,
                    "branch_id" => $branch,
                    "customer_id" => $customer,
                    "source_by" => $source,
                    "created_by" => $data["login_data"]["id"],
                    "created_date" => date('Y-m-d H:i:s')
                );
                $this->registration_admin_model->master_fun_insert("registration_admin", $data);
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
                $this->email->from($this->config->item('admin_booking_email'), 'Airmed PathLabs');
                $this->email->subject('Account Created Successfully');
                $this->email->message($message);
                // $this->email->send();
                $price = 0;
                foreach ($test as $key) {
                    $tn = explode("-", $key);
                    if ($tn[0] == 't') {
                        $result = $this->registration_admin_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $test_city . "' AND `test_master_city_price`.`status`='1' AND `test_master`.`id`='" . $tn[1] . "'");
                        $price += $result[0]["price"];
                        $test_package_name[] = $result[0]["test_name"];
                    }
                    if ($tn[0] == 'p') {
                        //print_r(array('package_fk' => $tn[1], "city_fk" => $test_city)); die();
                        $query = $this->db->get_where('package_master_city_price', array('package_fk' => $tn[1], "city_fk" => $test_city, "status" => "1"));
                        $result = $query->result();
                        $query1 = $this->db->get_where('package_master', array('id' => $tn[1]));
                        $result1 = $query1->result();
                        $price += $result[0]->d_price;
                        $test_package_name[] = $result1[0]->title;
                    }
                }
                /* Nishit book phlebo start */
                $testforself = "self";
                $family_mem_id = 0;
                $address = $this->input->get_post("address");
                $date1 = $this->input->get_post("phlebo_date");
                $time_slot_id = $this->input->get_post("phlebo_time");
                $test_for = $this->input->post("test_for");
                if ($test_for == "new") {
                    $f_name = $this->input->post("f_name");
                    $family_relation = $this->input->post("family_relation");
                    $relation_details = $this->job_model->get_val("SELECT * from relation_master where id='" . $family_relation . "'");
                    $f_dob = $this->input->post("f_dob");
                    $f_phone = $this->input->post("f_phone");
                    $f_email = $this->input->post("f_email");
                    $family_gender = $this->input->post("family_gender");
                    if ($f_name != '' && $family_relation != '') {
                        $family_mem_id = $this->job_model->master_fun_insert("customer_family_master", array("user_fk" => $customer, "gender" => $family_gender, "dob" => $f_dob, "name" => $f_name, "relation_fk" => $family_relation, "email" => $f_email, "phone" => $f_phone, "status" => "1", "created_date" => date("Y-m-d H:i:s")));
                        $testforself = "family";
                    }
                } else if ($test_for != '') {
                    $family_mem_id = $test_for;
                    $testforself = "family";
                }
                $address = $this->input->get_post("address");
                $date1 = $this->input->get_post("phlebo_date");
                $time_slot_id = $this->input->get_post("phlebo_time");

                $booking_fk = $this->job_model->master_fun_insert("booking_info", array("user_fk" => $customer, "type" => $testforself, "family_member_fk" => $family_mem_id, "address" => $address, "date" => $date1, "time_slot_fk" => $time_slot_id, "emergency" => $urgent, "status" => "1", "createddate" => date("Y-m-d H:i:s")));
                /* Nishit book phlebo end */
                $data = array(
                    "order_id" => $order_id,
                    "cust_fk" => $customer,
                    "doctor" => $referral_by,
                    "other_reference" => $souce,
                    "date" => $date,
                    "price" => $price,
                    "status" => '6',
                    "payment_type" => "Cash On Delivery",
                    "discount" => $discount,
                    "payable_amount" => $payable,
                    "test_city" => $test_city,
                    "note" => $this->input->post('note'),
                    "added_by" => $data["login_data"]["id"],
                    "booking_info" => $booking_fk,
                    "branch_fk" => $branch,
                    "date" => date("Y-m-d H:i:s")
                );
                $insert = $this->registration_admin_model->master_fun_insert("job_master", $data);
                foreach ($test as $key) {
                    $tn = explode("-", $key);
                    if ($tn[0] == 't') {
                        $this->registration_admin_model->master_fun_insert("job_test_list_master", array('job_fk' => $insert, "test_fk" => $tn[1]));
                    }
                    if ($tn[0] == 'p') {
                        $this->registration_admin_model->master_fun_insert("book_package_master", array("cust_fk" => $customer, "package_fk" => $tn[1], 'job_fk' => $insert, "status" => "1", "type" => "2"));
                    }
                }
            }
            $this->load->model('service_model');
            $file = $this->pdf_invoice($insert);
            $this->service_model->master_fun_update1("job_master", array("id" => $insert), array("invoice" => $file));
//            if($phlebo != NULL) {
//            $job_cnt = $this->registration_admin_model->master_num_rows("phlebo_assign_job", array("status" => "1", "job_fk" => $insert));
//        if ($job_cnt == 0) {
//            $data = array("job_fk" => $insert, "phlebo_fk" => $phlebo, "address" => $address, "date" =>$phlebo_date, "time" =>$phlebo_time, "created_date" => date("Y-m-d H:i:s"), "created_by" => $data["login_data"]["id"]);
//            $this->registration_admin_model->master_fun_insert("phlebo_assign_job", $data);
//            $this->registration_admin_model->master_fun_insert("job_log", array("job_fk" => $insert, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "message_fk" => "8", "date_time" => date("Y-m-d H:i:s")));
//        } else {
//            $data = array("job_fk" => $insert, "phlebo_fk" => $phlebo, "address" => $address, "date" =>$phlebo_date, "time" =>$phlebo_time, "updated_by" => $data["login_data"]["id"]);
//            $this->registration_admin_model->master_fun_update("phlebo_assign_job", array("job_fk", $insert), $data);
//            $this->registration_admin_model->master_fun_insert("job_log", array("job_fk" => $insert, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "message_fk" => "9", "date_time" => date("Y-m-d H:i:s")));
//        }
//        /* Nishit code end */
//        $phlebo_details = $this->registration_admin_model->master_fun_get_tbl_val("phlebo_master", array('id' => $phlebo), array("id", "asc"));
//        $phlebo_job_details = $this->registration_admin_model->master_fun_get_tbl_val("phlebo_assign_job", array('job_fk' => $insert), array("id", "asc"));
//        $job_details = $this->registration_admin_model->master_fun_get_tbl_val("job_master", array('id' => $insert), array("id", "asc"));
//        $customer_details = $this->registration_admin_model->master_fun_get_tbl_val("customer_master", array('id' => $job_details[0]['cust_fk']), array("id", "asc"));
//        
//            /* Pinkesh send sms code start */
//
//            $sms_message = $this->registration_admin_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "phlebo_msg"), array("id", "asc"));
//            $sms_message = preg_replace("/{{NAME}}/", ucfirst($phlebo_details[0]["name"]), $sms_message[0]["message"]);
//            $sms_message = preg_replace("/{{MOBILE}}/", $phlebo_details[0]["mobile"], $sms_message);
//            $sms_message = preg_replace("/{{ORDERID}}/", $job_details[0]["order_id"], $sms_message);
//            $sms_message = preg_replace("/{{CNAME}}/", $customer_details[0]["full_name"], $sms_message);
//            $sms_message = preg_replace("/{{CMOBILE}}/", $customer_details[0]["mobile"], $sms_message);
//            $sms_message = preg_replace("/{{CADDRESS}}/", $phlebo_job_details[0]["address"], $sms_message);
//            $sms_message = preg_replace("/{{DATE}}/", $phlebo_job_details[0]["date"], $sms_message);
//            $sms_message = preg_replace("/{{TIME}}/", $phlebo_job_details[0]["time"], $sms_message);
//            //$sms_message="done";
//            $mobile = $phlebo_details[0]['mobile'];
//            $this->load->helper("sms");
//            $notification = new Sms();
//            $notification::send($mobile, $sms_message);
//                /* Pinkesh send sms code start */
//                $sms_message = $this->job_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "phlebo_msg_cust"), array("id", "asc"));
//                $sms_message = preg_replace("/{{NAME}}/", ucfirst($customer_details[0]["full_name"]), $sms_message[0]["message"]);
//                $sms_message = preg_replace("/{{MOBILE}}/", $customer_details[0]["mobile"], $sms_message);
//                $sms_message = preg_replace("/{{ORDERID}}/", $job_details[0]["order_id"], $sms_message);
//                $sms_message = preg_replace("/{{PNAME}}/", ucfirst($phlebo_details[0]["name"]), $sms_message);
//                $sms_message = preg_replace("/{{PMOBILE}}/", $phlebo_details[0]["mobile"], $sms_message);
//                $sms_message = preg_replace("/{{DATE}}/", $phlebo_job_details[0]["date"], $sms_message);
//                $sms_message = preg_replace("/{{TIME}}/", $phlebo_job_details[0]["time"], $sms_message);
//                $mobile = $customer_details[0]['mobile'];
//                //$sms_message="done";
//                $notification::send($mobile, $sms_message);
//                /* Pinkesh send sms code end */
//            }
            $this->assign_phlebo_job($insert);
            $ids = explode(',', $test);
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
            $sms_message = $this->registration_admin_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "book_login"), array("id", "asc"));
            $user = $this->registration_admin_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "id" => $customer), array("id", "asc"));
            $mobile = $user[0]['mobile'];
            $sms_message = preg_replace("/{{NAME}}/", ucfirst($user[0]['full_name']), $sms_message[0]["message"]);
            $sms_message = preg_replace("/{{MOBILE}}/", ucfirst($user[0]['mobile']), $sms_message);
            if ($pid != '' && $tid != '') {
                $sms_message = preg_replace("/{{TESTPACK}}/", 'Test/Package', $sms_message);
            } else if ($pid != '') {
                $sms_message = preg_replace("/{{TESTPACK}}/", 'Package', $sms_message);
            } else {
                $sms_message = preg_replace("/{{TESTPACK}}/", 'Test', $sms_message);
            }
            $this->load->helper("sms");
            $notification = new Sms();
            if ($mobile != NULL) {
                //   $notification->send($mobile, $sms_message);
            }
            $this->session->set_flashdata("success", array("Test successfully Booked."));
            redirect("registration_admin");
        } else {
            $data['success'] = $this->session->flashdata("success");
            /* $data['test'] = $this->registration_admin_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='1'");
              $data["package"] = $this->registration_admin_model->get_val("SELECT
              `package_master`.*,
              `package_master_city_price`.`a_price` AS `a_price1`,
              `package_master_city_price`.`d_price` AS `d_price1`
              FROM
              `package_master`
              INNER JOIN `package_master_city_price`
              ON `package_master`.`id` = `package_master_city_price`.`package_fk`
              WHERE `package_master`.`status` = '1'
              AND `package_master_city_price`.`status` = '1' AND `package_master_city_price`.`city_fk` = '1' "); */
            $data['customer'] = $this->registration_admin_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "full_name !=" => ""), array("full_name", "asc"));
            $data['test_cities'] = $this->registration_admin_model->master_fun_get_tbl_val("test_cities", array('status' => 1), array("id", "asc"));
            $data['phlebo_list'] = $this->registration_admin_model->master_fun_get_tbl_val("phlebo_master", array('status' => 1), array("name", "asc"));
            $data['source_list'] = $this->registration_admin_model->master_fun_get_tbl_val("source_master", array('status' => 1), array("name", "asc"));
            //$data['referral_list'] = $this->registration_admin_model->master_fun_get_tbl_val("doctor_master", array('status' => 1), array("full_name", "asc"));
            $data['branch_list'] = $this->registration_admin_model->master_fun_get_tbl_val("branch_master", array('status' => 1), array("branch_name", "asc"));
            $data['unread'] = $this->registration_admin_model->master_fun_get_tbl_val("prescription_upload", array('status' => 1, "is_read" => "0"), array("id", "asc"));
            $data["relation1"] = $this->registration_admin_model->master_fun_get_tbl_val("relation_master", array('status' => "1"), array("name", "asc"));
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('registration_admin', $data);
            $this->load->view('footer');
        }
    }

    function get_user_info() {
        $mobile = $this->input->get_post("mobile");
        $user_id = $this->input->get_post("user_id");
        if ($mobile != null) {
            //$customer = $this->registration_admin_model->master_fun_get_tbl_val("customer_master", array("id" => $user_id), array("full_name", "asc"));
            $customer = $this->registration_admin_model->get_val("select c.*,r.birthdate,r.age_year,r.age_month,r.age_day,r.phone_no,r.sample_form,r.phlebo,r.dispatch_at,r.referral_no,r.referral_by,r.urgent,r.payment_type,r.branch_id,r.source_by from customer_master as c left join registration_admin as r on c.id = r.customer_id where c.mobile='$mobile' and c.status=1");
            $cnt = 0;
            $nw_ary = array();
            foreach ($customer as $key) {
                if ($key["id"] == '' || $key["id"] == null) {
                    $key["id"] = "";
                }
                if ($key["full_name"] == '' || $key["full_name"] == null) {
                    $key["full_name"] = "";
                }
                if ($key["gender"] == '' || $key["gender"] == null) {
                    $key["gender"] = '';
                }
                if ($key["email"] == '' || $key["email"] == null) {
                    $key["email"] = "";
                }
                $key["password"] = "";
                if ($key["mobile"] == '' || $key["mobile"] == null) {
                    $key["mobile"] = "";
                }
                if ($key["address"] == '' || $key["address"] == null) {
                    $key["address"] = "";
                }
                if ($key["country"] == '' || $key["country"] == null) {
                    $key["country"] = "";
                }
                if ($key["state"] == '' || $key["state"] == null) {
                    $key["state"] = "";
                }
                if ($key["city"] == '' || $key["city"] == null) {
                    $key["city"] = "";
                }
                if ($key["test_city"] == '' || $key["test_city"] == null) {
                    $key["test_city"] = "";
                }
                if ($key["birthdate"] == '' || $key["birthdate"] == null) {
                    $key["birthdate"] = "";
                }
                if ($key["age_year"] == '' || $key["age_year"] == null) {
                    $key["age_year"] = "";
                }
                if ($key["age_month"] == '' || $key["age_month"] == null) {
                    $key["age_month"] = "";
                }
                if ($key["age_day"] == '' || $key["age_day"] == null) {
                    $key["age_day"] = "";
                }
                if ($key["phone_no"] == '' || $key["phone_no"] == null) {
                    $key["phone_no"] = "";
                }
                if ($key["sample_form"] == '' || $key["sample_form"] == null) {
                    $key["sample_form"] = "";
                }
                if ($key["phlebo"] == '' || $key["phlebo"] == null) {
                    $key["phlebo"] = "";
                }
                if ($key["dispatch_at"] == '' || $key["dispatch_at"] == null) {
                    $key["dispatch_at"] = "";
                }
                if ($key["referral_no"] == '' || $key["referral_no"] == null) {
                    $key["referral_no"] = "";
                }
                if ($key["referral_by"] == '' || $key["referral_by"] == null) {
                    $key["referral_by"] = "";
                }
                if ($key["urgent"] == '' || $key["urgent"] == null) {
                    $key["urgent"] = "";
                }
                if ($key["payment_type"] == '' || $key["payment_type"] == null) {
                    $key["payment_type"] = "";
                }
                if ($key["branch_id"] == '' || $key["branch_id"] == null) {
                    $key["branch_id"] = "";
                }
                if ($key["source_by"] == '' || $key["source_by"] == null) {
                    $key["source_by"] = "";
                }
                //print_r($key); die();
                $nw_ary[$cnt] = $key;
                $cnt++;
            }
            $data['relation_list'] = $this->registration_admin_model->get_val("SELECT 
  `customer_family_master`.*,
  `relation_master`.`name` AS relation_name 
FROM
  `customer_family_master` 
  INNER JOIN `relation_master` 
    ON `customer_family_master`.`relation_fk` = `relation_master`.`id` 
WHERE `customer_family_master`.`status` = '1' 
  AND `relation_master`.`status` = '1' 
  AND `customer_family_master`.`user_fk` = '" . $key["id"] . "'");
            $family_html = "";
            $family_html .= "<option value=''>Self</option>";
            //print_R($data['relation_list']); die();
            foreach ($data['relation_list'] as $key) {
                $family_html .= "<option value='" . $key["id"] . "'>" . $key["name"] . " (" . $key["relation_name"] . ")</option>";
            }
            $family_html .= "<option value='new'>--Add New--</option>";
            $nw_ary[0]["family"] = $family_html;
            echo json_encode($nw_ary[0]);
        }
    }

    function check_phone() {
        $phone = $this->input->get_post("phone");
        $cust_id = $this->input->get_post("cust_id");
        if ($cust_id != '') {
            $cnt = $this->registration_admin_model->get_val("SELECT count(*) as count from customer_master where status='1' AND mobile='" . $phone . "' AND id not in (" . $cust_id . ")");
        } else {
            $cnt = $this->registration_admin_model->get_val("SELECT count(*) as count from customer_master where status='1' AND mobile='" . $phone . "'");
        }
        //print_r($cnt);
        echo $cnt[0]["count"];
    }

    function check_email() {
        $email = $this->input->get_post("email");
        $cust_id = $this->input->get_post("cust_id");
        $cnt = $this->registration_admin_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "id !=" => $cust_id, "email" => $email), array("id", "asc"));
        echo count($cnt);
    }

    function get_call_user_data() {
        $data["login_data"] = logindata();
        $result = array();
        $running_call = $this->user_call_model->user_running_call($data["login_data"]["email"]);
        $numbers = '"' . substr($running_call[0]->CallFrom, 1) . '"';
        $calls = $this->user_call_model->master_fun_get_tbl_val('exotel_calls', array('status' => 1, 'CallFrom' => $running_call[0]->CallFrom), array('id', 'asc'));
        if ($running_call[0]->maxid != "") {

            $register = $this->user_call_model->master_fun_get_tbl_val('customer_master', array('status' => 1, 'mobile' => substr($running_call[0]->CallFrom, 1)));
            $result["number"] = substr($running_call[0]->CallFrom, 1);
            if (count($register) > 0) {

                $result["customer"] = $register[0];
            }
        }
        echo json_encode($result);
    }

    function get_city_test() {

        $city = $this->input->get_post("city");
        if (empty($city)) {
            $city = 1;
        }
        $data['test'] = $this->registration_admin_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $city . "'");
        $data["package"] = $this->job_model->get_val("SELECT 
    `package_master`.*,
    `package_master_city_price`.`a_price` AS `a_price1`,
    `package_master_city_price`.`d_price` AS `d_price1`
  FROM
    `package_master` 
    INNER JOIN `package_master_city_price` 
      ON `package_master`.`id` = `package_master_city_price`.`package_fk` 
  WHERE `package_master`.`status` = '1' 
    AND `package_master_city_price`.`status` = '1' AND `package_master_city_price`.`city_fk` = '" . $city . "' ");
        $this->load->view("get_city_test_reg", $data);
    }

    function pending_count() {

        $data = $this->registration_admin_model->pending_job_count();
        $data1 = $this->registration_admin_model->instant_contact_count();
        $data2 = $this->registration_admin_model->all_inquiry_count();
        $package_count = $this->registration_admin_model->master_num_rows('instant_contact', array("status" => '1'));
        $allticket = $this->registration_admin_model->master_num_rows('ticket_master', array("views" => '0', "status" => '1'));
        $jobs = $this->registration_admin_model->master_num_rows('job_master', array("views" => '0', "status !=" => '0'));
        $contact_us = $this->registration_admin_model->master_num_rows('contact_us', array("views" => '0', "status" => '1'));
        $prescription_upload = $this->registration_admin_model->master_num_rows('prescription_upload', array("is_read" => '0', "status !=" => '0'));
        $job_total = $data->total;
        $all_inquiry_total = $data2->total;
        $all_total = $package_total + $all_inquiry_total + $contact_us;
        $myarray = array("job_count" => $jobs, "inquiry_total" => $all_inquiry_total + $package_count, "all_inquiry" => $all_inquiry_total, "package_inquiry" => $package_count, "all_total" => $all_total, "tickepanding" => $allticket, "contact_us_count" => $contact_us, "prescription_upload" => $prescription_upload);
        echo $json = json_encode($myarray);
    }

    function changing_status_job() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $status = $this->input->post('status');
        $job_id = $this->input->post('jobid');
        $customer_last_job_id = $this->registration_admin_model->master_fun_get_tbl_val("job_master", array('id' => $job_id), array("id", "asc"));
        if ($status == 2) {
            $this->job_mark_completed($job_id);
        }
        if ($status == 7) {
            $this->sample_collected_calculation($job_id);
        }
        $status_update = $this->registration_admin_model->master_fun_update("job_master", array("id", $job_id), array("status" => $status));
        $this->registration_admin_model->master_fun_insert("job_log", array("job_fk" => $job_id, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => $customer_last_job_id[0]["status"] . "-" . $status, "message_fk" => "3", "date_time" => date("Y-m-d H:i:s")));
        if ($status_update) {
            echo 1;
        }
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
                $test = $this->registration_admin_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND test_master.`id` NOT IN ($id) AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $ctid . "'");
            } else {
                $test = $this->registration_admin_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $ctid . "'");
            }
            if ($pids != NULL) {
                $package = $this->registration_admin_model->get_val("SELECT 
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
                $package = $this->registration_admin_model->get_val("SELECT 
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
            $test = $this->registration_admin_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $ctid . "'");
            $package = $this->registration_admin_model->get_val("SELECT 
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

    function assign_phlebo_job($job_id) {
        $this->load->model("service_model");
        $new_job_details = $this->service_model->master_fun_get_tbl_val("job_master", array('id' => $job_id), array("id", "asc"));
        $booking_info = $this->service_model->master_fun_get_tbl_val("booking_info", array('id' => $new_job_details[0]["booking_info"]), array("id", "asc"));
        if ($booking_info[0]["emergency"] == "0" || $booking_info[0]["emergency"] == null) {
            $get_random_phlebo = $this->service_model->get_random_phlebo($booking_info[0]);
            if (!empty($get_random_phlebo)) {
                /* Nishit code start */
                $data = array("job_fk" => $job_id, "phlebo_fk" => $get_random_phlebo[0]["id"], "date" => $booking_info[0]["date"], "time_fk" => $booking_info[0]["time_slot_fk"], "address" => $booking_info[0]["address"], "notify_cust" => 1, "created_date" => date("Y-m-d H:i:s"), "created_by" => $data["login_data"]["id"]);
                $insert = $this->service_model->master_fun_insert("phlebo_assign_job", $data);
                //$this->user_test_master_model->master_fun_insert("job_log", array("job_fk" => $job_id, "created_by" => "", "updated_by" => $login_id, "deleted_by" => "", "message_fk" => "8", "date_time" => date("Y-m-d H:i:s")));

                /* Nishit code end */
                //$update = $this->job_model->master_fun_update("phlebo_master", array("id", $phlebo_id), $data);
                $phlebo_details = $this->service_model->master_fun_get_tbl_val("phlebo_master", array('id' => $get_random_phlebo[0]["id"]), array("id", "asc"));
                $phlebo_job_details = $this->service_model->master_fun_get_tbl_val("phlebo_assign_job", array('job_fk' => $job_id), array("id", "asc"));
                $p_time = $this->service_model->master_fun_get_tbl_val("phlebo_time_slot", array('id' => $phlebo_job_details[0]["time_fk"]), array("id", "asc"));
                $job_details = $this->service_model->master_fun_get_tbl_val("job_master", array('id' => $job_id), array("id", "asc"));
                $customer_details = $this->service_model->master_fun_get_tbl_val("customer_master", array('id' => $job_details[0]['cust_fk']), array("id", "asc"));
                if ($insert) {
                    /* Pinkesh send sms code start */
                    $s_time = date('h:i a', strtotime($p_time[0]["start_time"]));
                    $e_time = date('h:i a', strtotime($p_time[0]["end_time"]));
                    $sms_message = $this->service_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "phlebo_msg"), array("id", "asc"));
                    $sms_message = preg_replace("/{{NAME}}/", ucfirst($phlebo_details[0]["name"]), $sms_message[0]["message"]);
                    $sms_message = preg_replace("/{{MOBILE}}/", $phlebo_details[0]["mobile"], $sms_message);
                    $sms_message = preg_replace("/{{ORDERID}}/", $job_details[0]["order_id"], $sms_message);
                    $sms_message = preg_replace("/{{CNAME}}/", $customer_details[0]["full_name"], $sms_message);
                    $sms_message = preg_replace("/{{CMOBILE}}/", $customer_details[0]["mobile"], $sms_message);
                    $sms_message = preg_replace("/{{CADDRESS}}/", $phlebo_job_details[0]["address"], $sms_message);
                    $sms_message = preg_replace("/{{DATE}}/", $phlebo_job_details[0]["date"], $sms_message);
                    $sms_message = preg_replace("/{{TIME}}/", $s_time . " To " . $e_time, $sms_message);
                    $job_details = $this->get_job_details($job_id);
                    $b_details = array();
                    $total_price = 0;
                    foreach ($job_details[0]["book_test"] as $bkey) {
                        $b_details[] = $bkey["test_name"] . " Rs." . $bkey["price"];
                        $total_price = $total_price + $bkey["price"];
                    }
                    foreach ($job_details[0]["book_package"] as $bkey) {
                        $b_details[] = $bkey["title"] . " Rs." . $bkey["d_price"];
                        $total_price = $total_price + $bkey["d_price"];
                    }
                    $sms_message = preg_replace("/{{BOOKINFO}}/", implode(",", $b_details) . " Total Price-Rs." . $total_price, $sms_message);
                    //$this->user_test_master_model->master_fun_insert("test", array("test"=>$sms_message."-".json_encode($job_details)));
                    //$sms_message="done";
                    $mobile = $phlebo_details[0]['mobile'];
                    $this->load->helper("sms");
                    $notification = new Sms();
                    // $notification->send($mobile, $sms_message);
                    /* Pinkesh send sms code end */

                    // if ($notify == 1) {
                    /* Pinkesh send sms code start */
                    $sms_message = $this->service_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "phlebo_msg_cust"), array("id", "asc"));
                    $sms_message = preg_replace("/{{NAME}}/", ucfirst($customer_details[0]["full_name"]), $sms_message[0]["message"]);
                    $sms_message = preg_replace("/{{MOBILE}}/", $customer_details[0]["mobile"], $sms_message);
                    $sms_message = preg_replace("/{{ORDERID}}/", $job_details[0]["order_id"], $sms_message);
                    $sms_message = preg_replace("/{{PNAME}}/", ucfirst($phlebo_details[0]["name"]), $sms_message);
                    $sms_message = preg_replace("/{{PMOBILE}}/", $phlebo_details[0]["mobile"], $sms_message);
                    $sms_message = preg_replace("/{{DATE}}/", $phlebo_job_details[0]["date"], $sms_message);
                    $sms_message = preg_replace("/{{TIME}}/", $s_time . " To " . $e_time, $sms_message);
                    $sms_message = preg_replace("/{{BOOKINFO}}/", implode(",", $b_details) . " Total Price-Rs." . $total_price, $sms_message);
                    $mobile = $customer_details[0]['mobile'];
                    //$sms_message="done";
                    //  $notification->send($mobile, $sms_message);
                    /* Pinkesh send sms code end */
                    //}
                } else {
                    
                }
            }
        }
    }

    function get_job_details($job_id) {
        $job_details = $this->job_model->master_fun_get_tbl_val("job_master", array("status !=" => 0, "id" => $job_id), array("id", "asc"));
        if (!empty($job_details)) {
            $book_test = $this->job_model->master_fun_get_tbl_val("job_test_list_master", array("job_fk" => $job_id), array("id", "desc"));
            $book_package = $this->job_model->master_fun_get_tbl_val("book_package_master", array("job_fk" => $job_id, "status" => "1"), array("id", "desc"));
            $test_name = array();
            foreach ($book_test as $key) {
                //$price1 = $this->user_test_master_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master`.`id`='" . $key["test_fk"] . "'");
                $price1 = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master`.`id`='" . $key["test_fk"] . "' AND `test_master_city_price`.`city_fk`='" . $job_details[0]["test_city"] . "'");
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

    function get_job_id($city = null) {
        $this->load->library("Util");
        $util = new Util();
        $new_id = $util->get_job_id($city);
        return $new_id;
    }

    function pdf_invoice($id) {
        $data["login_data"] = loginuser();
        $this->load->model('add_result_model');
        $data['query'] = $this->add_result_model->job_details($id);
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
            $data['fasting'] = 'Fasting not required for 12 hours.';
        }
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

    function test() {
        $message = '<div style="padding:0 4%;"> 
                    <h4><b>Create Account</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your account successfully created. </p>
                     <p style="color:#7e7e7e;font-size:13px;"> Username/Email : .  </p>  
                        <p style="color:#7e7e7e;font-size:13px;"> Password :  </p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
        $this->load->helper("Email");
        $email_cnt = new Email;
        echo $email_cnt->get_design($message);
    }

}
