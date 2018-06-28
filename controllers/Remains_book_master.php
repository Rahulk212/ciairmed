<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Remains_book_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('user_model');
        $this->load->model('remains_book_model');
        $this->load->model('job_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->helper('string');
        $this->load->library('email');
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
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        /* New pagination with search start */
        $caller = $this->input->get('caller');
        $call_from = $this->input->get('call_from');
        $call_to = $this->input->get('call_to');
        $direction = $this->input->get('direction');
        $start_date = $this->input->get('start_date');
        $duration = $this->input->get('duration');
        $call_type = $this->input->get('call_type');
        $call_date = $this->input->get('call_date');
        $agent = $this->input->get('agent');
        $agent_number = $this->input->get('agent_number');
        $data['caller_fk'] = $caller;
        $data['call_from'] = $call_from;
        $data['call_to'] = $call_to;
        $data['direction'] = $direction;
        $data['start_date'] = $start_date;
        $data['duration'] = $duration;
        $data['call_type'] = $call_type;
        $data['call_date'] = $call_date;
        $data['agent'] = $agent;
        $data['agent_number'] = $agent_number;
        if ($caller != "" || $call_from != "" || $call_to != "" || $direction != "" || $start_date != "" || $duration != "" || $call_type != "" || $call_date != "" || $agent != "" || $agent_number != "") {
            $total_row = $this->remains_book_model->num_row_srch_call_list($caller, $call_from, $call_to, $direction, $start_date, $duration, $call_type, $call_date, $agent, $agent_number);
            $config = array();
            $get = $_GET;
            unset($get['offset']);
            $config["base_url"] = base_url() . "user_call_master/index?" . http_build_query($get);
            $config["total_rows"] = $total_row;
            $config["per_page"] = 50;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->remains_book_model->row_srch_call_list($caller, $call_from, $call_to, $direction, $start_date, $duration, $call_type, $call_date, $agent, $agent_number, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        } else {
            $total_row = $this->remains_book_model->num_row('oncall_booking_data', array('status' => 1));
            $config["base_url"] = base_url() . "user_call_master/index";
            $config["total_rows"] = $total_row;
            $config["per_page"] = 50;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->remains_book_model->srch_call_list($config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        }
        /* New pagination with search end */
        $data['customer_list'] = $this->remains_book_model->master_fun_get_tbl_val('customer_master', array('status' => 1, 'active' => 1), array('id', 'asc'));
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('remains_book_list', $data);
        $this->load->view('footer');
    }

    function add_book_job($bid) {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->helper("Email");
        $email_cnt = new Email;

        $data['bid'] = $bid;
        $data["login_data"] = logindata();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required');
        $this->form_validation->set_rules('test_city', 'Test city', 'trim|required');

        if ($this->form_validation->run() != FALSE) {
            //print_R($this->input->post());
            //die();
            $customer = $this->input->post("customer_fk");
            $name = $this->input->post("name");
            $phone = $this->input->post("phone");
            $email = $this->input->post("email");
            if ($email == '') {
                $email = 'noreply@airmedlabs.com';
            }
            $gender = $this->input->post("gender");
            $test_city = $this->input->post("test_city");
            $address = $this->input->post("address");
            $note = $this->input->post("note");
            $discount = $this->input->post("discount");
            $payable = $this->input->post("payable");
            $test = $this->input->post("test");
            $referral_by = $this->input->post("referral_by");
            $source = $this->input->post("source");
            $phlebo = $this->input->post("phlebo");
            $phlebo_date = $this->input->post("phlebo_date");
            $phlebo_time = $this->input->post("phlebo_time");
            $notify = $this->input->post("notify");
            $order_id = $this->get_job_id($test_city);
            $date = date('Y-m-d H:i:s');
            if ($customer != '') {
                $c_data = array(
                    "full_name" => $name,
                    "gender" => $gender,
                    "email" => $email,
                    "mobile" => $phone,
                    "address" => $address
                );
                $this->job_model->master_fun_update("customer_master", array("id", $customer), $c_data);

                $price = 0;
                foreach ($test as $key) {
                    $tn = explode("-", $key);
                    if ($tn[0] == 't') {
                        $result = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $test_city . "' AND `test_master`.`id`='" . $tn[1] . "'");
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
                /* Nishit book phlebo start */
                $testforself = "self";
                $family_mem_id = 0;
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
                $booking_fk = $this->job_model->master_fun_insert("booking_info", array("user_fk" => $customer, "type" => $testforself, "family_member_fk" => $family_mem_id, "address" => $address, "date" => $date1, "time_slot_fk" => $time_slot_id, "emergency" => 0, "status" => "1", "createddate" => date("Y-m-d H:i:s")));
                /* Nishit book phlebo end */
                $data = array(
                    "order_id" => $order_id,
                    "cust_fk" => $customer,
                    "doctor" => $referral_by,
                    "other_reference" => $source,
                    "date" => $date,
                    "price" => $price,
                    "status" => '6',
                    "payment_type" => "Cash On Delivery",
                    "discount" => $discount,
                    "payable_amount" => $payable,
                    "test_city" => $test_city,
                    "note" => $this->input->post('note'),
                    "added_by" => $data["login_data"]["id"],
                    "booking_info" => $booking_fk
                );
                $insert = $this->job_model->master_fun_insert("job_master", $data);
                foreach ($test as $key) {
                    $tn = explode("-", $key);
                    if ($tn[0] == 't') {
                        $this->job_model->master_fun_insert("job_test_list_master", array('job_fk' => $insert, "test_fk" => $tn[1]));
                    }
                    if ($tn[0] == 'p') {
                        $this->job_model->master_fun_insert("book_package_master", array("cust_fk" => $uid, "package_fk" => $tn[1], 'job_fk' => $insert, "status" => "1", "type" => "2"));
                    }
                }
            } else {
                $password = rand(11111111, 9999999);
                $c_data = array(
                    "full_name" => $name,
                    "gender" => $gender,
                    "email" => $email,
                    "mobile" => $phone,
                    "address" => $address,
                    "password" => $password
                );
                $customer = $this->job_model->master_fun_insert("customer_master", $c_data);
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
                $this->email->from($this->config->item('admin_booking_email'), 'Airmed PathLabs');
                $this->email->subject('Account Created Successfully');
                $this->email->message($message);
                $this->email->send();
                $price = 0;
                foreach ($test as $key) {
                    $tn = explode("-", $key);
                    if ($tn[0] == 't') {
                        $result = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $test_city . "' AND `test_master`.`id`='" . $tn[1] . "'");
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
                /* Nishit book phlebo start */
                $testforself = "self";
                $family_mem_id = 0;
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
                $booking_fk = $this->job_model->master_fun_insert("booking_info", array("user_fk" => $customer, "type" => $testforself, "family_member_fk" => $family_mem_id, "address" => $address, "date" => $date1, "time_slot_fk" => $time_slot_id, "emergency" => 0, "status" => "1", "createddate" => date("Y-m-d H:i:s")));
                /* Nishit book phlebo end */
                $data = array(
                    "order_id" => $order_id,
                    "cust_fk" => $customer,
                    "doctor" => $referral_by,
                    "other_reference" => $source,
                    "date" => $date,
                    "price" => $price,
                    "status" => '6',
                    "payment_type" => "Cash On Delivery",
                    "discount" => $discount,
                    "payable_amount" => $payable,
                    "test_city" => $test_city,
                    "note" => $this->input->post('note'),
                    "added_by" => $data["login_data"]["id"],
                    "booking_info" => $booking_fk
                );
                $insert = $this->job_model->master_fun_insert("job_master", $data);
                foreach ($test as $key) {
                    $tn = explode("-", $key);
                    if ($tn[0] == 't') {
                        $this->job_model->master_fun_insert("job_test_list_master", array('job_fk' => $insert, "test_fk" => $tn[1]));
                    }
                    if ($tn[0] == 'p') {
                        $this->job_model->master_fun_insert("book_package_master", array("cust_fk" => $uid, "package_fk" => $tn[1], 'job_fk' => $insert, "status" => "1", "type" => "2"));
                    }
                }
            }
            $this->load->model('service_model');
            $file = $this->pdf_invoice($insert);
            $this->service_model->master_fun_update1("job_master", array("id" => $insert), array("invoice" => $file));
//            if($phlebo != NULL) {
//            $job_cnt = $this->job_model->master_num_rows("phlebo_assign_job", array("status" => "1", "job_fk" => $insert));
//        if ($job_cnt == 0) {
//            $data = array("job_fk" => $insert, "phlebo_fk" => $phlebo, "address" => $address, "date" =>$phlebo_date, "time" =>$phlebo_time, "created_date" => date("Y-m-d H:i:s"), "created_by" => $data["login_data"]["id"]);
//            $this->job_model->master_fun_insert("phlebo_assign_job", $data);
//            $this->job_model->master_fun_insert("job_log", array("job_fk" => $insert, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "message_fk" => "8", "date_time" => date("Y-m-d H:i:s")));
//        } else {
//            $data = array("job_fk" => $insert, "phlebo_fk" => $phlebo, "address" => $address, "date" =>$phlebo_date, "time" =>$phlebo_time, "updated_by" => $data["login_data"]["id"]);
//            $this->job_model->master_fun_update("phlebo_assign_job", array("job_fk", $insert), $data);
//            $this->job_model->master_fun_insert("job_log", array("job_fk" => $insert, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "message_fk" => "9", "date_time" => date("Y-m-d H:i:s")));
//        }
//        /* Nishit code end */
//        $phlebo_details = $this->job_model->master_fun_get_tbl_val("phlebo_master", array('id' => $phlebo), array("id", "asc"));
//        $phlebo_job_details = $this->job_model->master_fun_get_tbl_val("phlebo_assign_job", array('job_fk' => $insert), array("id", "asc"));
//        $job_details = $this->job_model->master_fun_get_tbl_val("job_master", array('id' => $insert), array("id", "asc"));
//        $customer_details = $this->job_model->master_fun_get_tbl_val("customer_master", array('id' => $job_details[0]['cust_fk']), array("id", "asc"));
//        
//            /* Pinkesh send sms code start */
//
//            $sms_message = $this->job_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "phlebo_msg"), array("id", "asc"));
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
//            if ($notify == 1) {
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
//            }
            $this->assign_phlebo_job($insert);
            $this->job_model->master_fun_update("oncall_booking_data", array("id", $bid), array("status" => 0));
            $this->session->set_flashdata("success", array("Test successfully Booked."));
            redirect("remains_book_master");
        } else {
            $data['success'] = $this->session->flashdata("success");
            $data['query'] = $this->job_model->master_fun_get_tbl_val("oncall_booking_data", array('status' => 1, "id" => $bid), array("id", "asc"));
            $data['test'] = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`=" . $data['query'][0]['customer_testcity']);
            $data["package"] = $this->job_model->get_val("SELECT 
              `package_master`.*,
              `package_master_city_price`.`a_price` AS `a_price1`,
              `package_master_city_price`.`d_price` AS `d_price1`
              FROM
              `package_master`
              INNER JOIN `package_master_city_price`
              ON `package_master`.`id` = `package_master_city_price`.`package_fk`
              WHERE `package_master`.`status` = '1'
              AND `package_master_city_price`.`status` = '1' AND `package_master_city_price`.`city_fk` = " . $data['query'][0]['customer_testcity']);
            $data['phlebo_list'] = $this->job_model->master_fun_get_tbl_val("phlebo_master", array('status' => 1), array("name", "asc"));
            $data['source_list'] = $this->job_model->master_fun_get_tbl_val("source_master", array('status' => 1), array("name", "asc"));
            //$data['referral_list'] = $this->job_model->master_fun_get_tbl_val("doctor_master", array('status' => 1), array("full_name", "asc"));
            $data['referral_list'] = $this->remains_book_model->get_all_data("doctor_master", "id,full_name,mobile", array('status' => 1), array("full_name", "asc"));
            $data['customer'] = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "full_name !=" => ""), array("full_name", "asc"));
            $data['test_cities'] = $this->job_model->master_fun_get_tbl_val("test_cities", array('status' => 1), array("id", "asc"));
            $data['unread'] = $this->job_model->master_fun_get_tbl_val("prescription_upload", array('status' => 1, "is_read" => "0"), array("id", "asc"));

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('remains_book_job', $data);
            $this->load->view('footer');
        }
    }

    function remains_spam() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $bid = $this->uri->segment('3');
        $this->job_model->master_fun_update("oncall_booking_data", array("id", $bid), array("status" => "0"));
        $this->session->set_flashdata("success", array("Remains booking Deleted Successfully"));
        redirect("remains_book_master", "refresh");
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
                    $sms_message = preg_replace("/{{BOOKINFO}}/", implode(",", $b_details), $sms_message);
                    $mobile = $customer_details[0]['mobile'];
                    //$sms_message="done";
                    $notification->send($mobile, $sms_message);
                    /* Pinkesh send sms code end */
                    //}
                } else {
                    
                }
            }
        }
    }

    function get_job_details($job_id) {
        $this->load->model("service_model");
        $job_details = $this->service_model->master_fun_get_tbl_val("job_master", array("status !=" => "0", "id" => $job_id), array("id", "asc"));
        if (!empty($job_details)) {
            $book_test = $this->service_model->master_fun_get_tbl_val("job_test_list_master", array("job_fk" => $job_id), array("id", "desc"));
            $book_package = $this->service_model->master_fun_get_tbl_val("book_package_master", array("job_fk" => $job_id, "status" => "1"), array("id", "desc"));
            $test_name = array();
            foreach ($book_test as $key) {
                $price1 = $this->service_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $job_details[0]['test_city'] . "' AND `test_master`.`id`='" . $key["test_fk"] . "'");
                $test_name[] = $price1[0];
            }
            $job_details[0]["book_test"] = $test_name;
            $package_name = array();
            foreach ($book_package as $key) {

                $price1 = $this->service_model->get_val("SELECT
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

    function get_job_id($city = null) {
        $this->load->library("Util");
        $util = new Util();
        $new_id = $util->get_job_id($city);
        return $new_id;
    }

}

?>
