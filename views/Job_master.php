<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Job_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('user_model');
        $this->load->model('user_test_master_model');
        $this->load->model('job_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('pushserver');
        $this->load->library('email');
        $this->load->helper('string');
        //echo current_url(); die();

        $data["login_data"] = logindata();
    }

    function pending_list() {
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
        //print_r($data["login_data"]); die();
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
        $data['query'] = $this->job_model->pending_job_search($user, $date, $city, $status, $mobile, $t_id, $p_id, $p_amount);
        $data['customer'] = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1), array("full_name", "asc"));
        $data['test_list'] = $this->job_model->master_fun_get_tbl_val("test_master", array('status' => 1), array("test_name", "asc"));
        $data['package_list'] = $this->job_model->master_fun_get_tbl_val("package_master", array('status' => 1), array("title", "asc"));
        $data['city'] = $this->job_model->master_fun_get_tbl_val("city", array('status' => 1), array("id", "asc"));

        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('pending_job_list', $data);
        $this->load->view('footer');
    }

    function changing_status_job() {
        $status = $this->input->post('status');
        $job_id = $this->input->post('jobid');
        if ($status == 2) {
            $this->job_mark_completed($job_id);
        }
        if ($status == 7) {
            $this->sample_collected_calculation($job_id);
        }
        $status_update = $this->job_model->master_fun_update("job_master", array("id", $job_id), array("status" => $status));
        if ($status_update) {
            echo 1;
        }
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
        $data['query'] = $this->job_model->spam_job_search($user, $date, $city);
        //$this->load->view('admin/state_list_view', $data);
        $data['customer'] = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1), array("id", "asc"));
        $data['city'] = $this->job_model->master_fun_get_tbl_val("city", array('status' => 1), array("id", "asc"));

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
        $data['query'] = $this->job_model->completed_job_search($user, $date, $city);
        //$this->load->view('admin/state_list_view', $data);

        $data['customer'] = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1), array("id", "asc"));
        $data['city'] = $this->job_model->master_fun_get_tbl_val("city", array('status' => 1), array("id", "asc"));
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
        //print_r($data["login_data"]); die();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['cid'] = $this->uri->segment(3);
        $data['success'] = $this->session->flashdata("success");
        $data['error'] = $this->session->flashdata("error");
        $data['query'] = $this->job_model->job_details($data['cid']);

        //echo "<pre>"; print_r($data['query']); die();
        $data['report'] = $this->job_model->master_fun_get_tbl_val("report_master", array('status' => 1, "job_fk" => $data['cid']), array("id", "asc"));
        $data['city'] = $this->job_model->master_fun_get_tbl_val("city", array('status' => 1), array("id", "asc"));
        $data['state'] = $this->job_model->master_fun_get_tbl_val("state", array('status' => 1), array("id", "asc"));
        $data['country'] = $this->job_model->master_fun_get_tbl_val("country", array('status' => 1), array("id", "asc"));
        $data['package_price'] = $this->job_model->master_fun_get_tbl_val("package_master_city_price", array('status' => 1), array("id", "asc"));
        $update = $this->job_model->master_fun_update("job_master", array('id', $data['cid']), array("views" => "1"));
        //print_r($data['package_price']); die();
        //$this->load->view('admin/state_list_view', $data);
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('job_details', $data);
        $this->load->view('footer');
    }

    function job_mark_spam() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->job_model->master_fun_update("job_master", array("id", $cid), array("status" => "3"));
        $data = $this->job_model->master_fun_get_tbl_val("job_master", array("id" => $cid), array("id", "asc"));
        $cust_fk = $data[0]['cust_fk'];

        $data1 = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "id" => $cust_fk), array("id", "asc"));
        $query = $this->job_model->job_details($cid);
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
            $notification_data = array("title" => "Airmed Path lab", "message" => $message, "type" => "spam", "testid" => $testid, "testname" => $testname, "testprice" => $testprice, "testdate" => $testdate, "order_id" => $orderid);
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
        $sms_message = $this->job_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "Span_report"), array("id", "asc"));
        $sms_message = preg_replace("/{{NAME}}/", ucfirst($data1[0]["full_name"]), $sms_message[0]["message"]);
        $sms_message = preg_replace("/{{OID}}/", $orderid, $sms_message);
        $sms_message = preg_replace("/{{PRICE}}/", "Rs." . $testprice, $sms_message);
        $this->load->helper("sms");
        $notification = new Sms();
        $mb_length = strlen($mobile);
        if ($mb_length == 10) {
            $notification::send($mobile, $sms_message);
        }
        if ($mb_length == 11 || $mb_length == 12 || $mb_length == 13) {
            $check_phone = substr($mobile, 0, 2);
            $check_phone1 = substr($mobile, 0, 1);
            $check_phone2 = substr($mobile, 0, 3);
            if ($check_phone2 == '+91') {
                $get_phone = substr($mobile, 3);
                $notification::send($get_phone, $sms_message);
            }
            if ($check_phone == '91') {
                $get_phone = substr($mobile, 2);
                $notification::send($get_phone, $sms_message);
            }
            if ($check_phone1 == '0') {
                $get_phone = substr($mobile, 1);
                $notification::send($get_phone, $sms_message);
            }
        }
        /* Nishit send sms code end */

        $this->session->set_flashdata("success", array("Job successfully mark as Spam."));
        redirect("job-master/pending-list", "refresh");
    }

    function testme() {
        $pushServer = new PushServer();
        $notification_data = array("title" => "Pathelab", "message" => "this is test massage notification", "type" => "completed");
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
        $data['query'] = $this->job_model->master_fun_update("job_master", array("id", $cid), array("status" => "1"));
        $data = $this->job_model->master_fun_get_tbl_val("job_master", array("id" => $cid), array("id", "asc"));
        $cust_fk = $data[0]['cust_fk'];

        $data1 = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "id" => $cust_fk), array("id", "asc"));
        $device_id = $data1[0]['device_id'];
        $mobile = $data1[0]['mobile'];
        $device_type = $data1[0]['device_type'];
        $query = $this->job_model->job_details($cid);
        $testid = $query[0]['id'];
        $testname = $query[0]['testname'];
        $testprice = $query[0]['price'];
        $testdate = $query[0]['date'];
        $orderid = $query[0]['order_id'];
        $message = "Your Report has been Pending";
        if ($device_type == 'android') {
            //$notification_data=array("title" => "Patholab","message" =>$message,"type"=>"Pending");
            $notification_data = array("title" => "Airmed Path lab", "message" => $message, "type" => "Pending", "testid" => $testid, "testname" => $testname, "testprice" => $testprice, "testdate" => $testdate, "order_id" => $orderid);
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
        $sms_message = $this->job_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "Pending_Report"), array("id", "asc"));
        $sms_message = preg_replace("/{{NAME}}/", ucfirst($data1[0]["full_name"]), $sms_message[0]["message"]);
        $sms_message = preg_replace("/{{OID}}/", $orderid, $sms_message);
        $sms_message = preg_replace("/{{PRICE}}/", "Rs." . $testprice, $sms_message);
        $this->load->helper("sms");
        $notification = new Sms();
        $mb_length = strlen($mobile);
        if ($mb_length == 10) {
            $notification::send($mobile, $sms_message);
        }
        if ($mb_length == 11 || $mb_length == 12 || $mb_length == 13) {
            $check_phone = substr($mobile, 0, 2);
            $check_phone1 = substr($mobile, 0, 1);
            $check_phone2 = substr($mobile, 0, 3);
            if ($check_phone2 == '+91') {
                $get_phone = substr($mobile, 3);
                $notification::send($get_phone, $sms_message);
            }
            if ($check_phone == '91') {
                $get_phone = substr($mobile, 2);
                $notification::send($get_phone, $sms_message);
            }
            if ($check_phone1 == '0') {
                $get_phone = substr($mobile, 1);
                $notification::send($get_phone, $sms_message);
            }
        }
        /* Nishit send sms code end */

        $this->session->set_flashdata("success", array("Job successfully mark as pending."));
        redirect("job-master/pending-list", "refresh");
    }

    function job_mark_completed($cid) {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        // $cid = $this->uri->segment('3');

        $update = $this->job_model->master_fun_update("job_master", array("id", $cid), array("status" => "2"));
        if ($update) {

            $data = $this->job_model->master_fun_get_tbl_val("job_master", array("id" => $cid), array("id", "asc"));

            $cust_fk = $data[0]['cust_fk'];

            $data1 = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "id" => $cust_fk), array("id", "asc"));
            $device_id = $data1[0]['device_id'];
            $mobile = $data1[0]['mobile'];
            $device_type = $data1[0]['device_type'];
            $name = $data1[0]['full_name'];
            $email = $data1[0]['email'];
            $query = $this->job_model->job_details($cid);
            $testid = $query[0]['id'];
            $testname = $query[0]['testname'];
            $testprice = $query[0]['price'];
            $testdate = $query[0]['date'];
            $orderid = $query[0]['order_id'];
            $message = "Your Report has been Completed";
            if ($device_type == 'android') {
                //$notification_data=array("title" => "Patholab","message" =>$message,"type"=>"completed");
                $notification_data = array("title" => "Airmed Path lab", "message" => $message, "type" => "completed", "testid" => $testid, "testname" => $testname, "testprice" => $testprice, "testdate" => $testdate, "order_id" => $orderid);
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
            $sms_message = $this->job_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "Completed_Report"), array("id", "asc"));
            // $sms_message = preg_replace("/{{NAME}}/", ucfirst($data1[0]["full_name"]), $sms_message[0]["message"]);
            $sms_message = preg_replace("/{{OID}}/", $orderid, $sms_message[0]["message"]);
            //$sms_message = preg_replace("/{{PRICE}}/", "Rs." . $testprice, $sms_message);
            $this->load->helper("sms");
            $notification = new Sms();
            $mb_length = strlen($mobile);
            if ($mb_length == 10) {
                $notification::send($mobile, $sms_message);
            }
            if ($mb_length == 11 || $mb_length == 12 || $mb_length == 13) {
                $check_phone = substr($mobile, 0, 2);
                $check_phone1 = substr($mobile, 0, 1);
                $check_phone2 = substr($mobile, 0, 3);
                if ($check_phone2 == '+91') {
                    $get_phone = substr($mobile, 3);
                    $notification::send($get_phone, $sms_message);
                }
                if ($check_phone == '91') {
                    $get_phone = substr($mobile, 2);
                    $notification::send($get_phone, $sms_message);
                }
                if ($check_phone1 == '0') {
                    $get_phone = substr($mobile, 1);
                    $notification::send($get_phone, $sms_message);
                }
            }
            /* Nishit send sms code end */
            $report = $this->job_model->master_fun_get_tbl_val("report_master", array("job_fk" => $cid), array("id", "asc"));
            $config['mailtype'] = 'html';

            $this->email->initialize($config);

            $message1 = '<div style="background:#a3a3a3 !important;font-family: "Roboto",sans-serif;">
<script src="https://use.fontawesome.com/44049e3dc5.js"></script>
    <div style="background: #6b6b6b none repeat scroll 0 0;float: left;padding: 7%;">
        <div style="border:1px solid #f1f1f1;border-bottom:1px solid #67B1A3;padding:1.5%;width:100%;float:left;background:#fff;">
            <div style="float:left;width:64%;">
                        <img alt="" src="' . base_url() . 'user_assets/images/logo.png" style="height: 100px;width:213px;"/>
            </div>
                <div style="float:right;text-align: right;width:33%;padding-top:7px;">

                
                
                </div>
                </div>
        <div style="padding:1.5%;width:100%;float:left;background-color:#fff;border:1px solid #f1f2f3;">
                <div style="padding:0 4%;">
                    <h4><b>Dear</b>, ' . $name . '</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Report completed successfully for test ' . $testname . ' </p>
                        <p style="color:#7e7e7e;font-size:13px;">Your Report ID : ' . $orderid . ' </p>    
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
                Copyright @ 2016 Airmed PATH LABS. All rights reserved
        </div>
    </div>
</div>';

            $this->email->to($email);
            //  $this->email->to('jeel@virtualheight.com');
            $this->email->from("donotreply@airmedpathlabs.com", "Airmed PathLabs");
            $this->email->subject("Report Completed");
            $this->email->message($message1);
            $attatchPath = "";
            foreach ($report as $key) {
                $attatchPath = base_url() . "upload/report/" . $key['report'];
                $this->email->attach($attatchPath);
            }
            //$this->email->attach(implode(',',$attatchPath));

            $this->email->send();

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
        //$cid = $this->uri->segment('3');
        $data2 = $this->job_model->master_fun_get_tbl_val("job_master", array("id" => $cid), array("id", "asc"));
        //	print_r($data);
        $cust_fk = $data2[0]['cust_fk'];
        $price = $data2[0]['price'];
        $payment_type = $data2[0]['payment_type'];
        $status = $data2[0]['sample_collection'];

        $update = $this->job_model->master_fun_update("job_master", array("id", $cid), array("sample_collection" => "1"));



        if ($update) {
			
$data1 = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "id" => $cust_fk), array("id", "asc"));
            $data1r = $this->job_model->master_fun_get_tbl_val("refer_code_master", array('used_code !=' => "", "cust_fk" => $cust_fk), array("id", "asc"));

            if ($data1r) {
                $rcode = $data1r[0]['used_code'];
                $urcode = $data1r[0]['cust_fk'];
                $data1r1 = $this->job_model->master_fun_get_tbl_val("refer_code_master", array('refer_code' => $rcode), array("id", "asc"));

                $addmid = $data1r1[0]['cust_fk'];
                if ($data1r1) {
                    $data2 = $this->job_model->master_fun_get_tbl_val("job_master", array("cust_fk" => $cust_fk), array("id", "asc"));
                    $cnt = count($data2);
                    if ($cnt == "1") {
                        $query = $this->job_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $addmid), array("id", "desc"));
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
                        $insert1 = $this->job_model->master_fun_insert("wallet_master", $data);
                    }
                }
            }

            $data1 = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "id" => $cust_fk), array("id", "asc"));
            //print_r($data1);
            //die();
            $device_id = $data1[0]['device_id'];
            $mobile = $data1[0]['mobile'];
            $device_type = $data1[0]['device_type'];
            $name = $data1[0]['full_name'];
            $email = $data1[0]['email'];
            $query = $this->job_model->job_details($cid);
            $testid = $query[0]['id'];
            $discount = $query[0]['discount'];
            $testname = $query[0]['testname'];
            $packagename = $query[0]['packagename'];
            $testprice = $query[0]['price'];
            $testdate = $query[0]['date'];
            $orderid = $query[0]['order_id'];
            $message = "Your Test Sample has been collected";
            if ($device_type == 'android') {
                //$notification_data=array("title" => "Patholab","message" =>$message,"type"=>"Pending");
                $notification_data = array("title" => "Airmed Pathlabs", "message" => $message, "type" => "Pending", "testid" => $testid, "testname" => $testname, "testprice" => $testprice, "testdate" => $testdate, "order_id" => $orderid);
                $pushServer = new PushServer();
                $pushServer->pushToGoogle($device_id, $notification_data);
            }
            if ($device_type == 'ios') {
                $url = 'http://website-demo.in/patholabpush/push.php?device_id=' . $device_id . '&msg=' . $message . '&type=pending&testid=' . $testid . '&testname=' . $testname . '&testprice=' . $testprice . '&testdate=' . $testdate . '&orderid=' . $orderid . '';
                $url = str_replace(" ", "%20", $url);
                $data = $this->get_content($url);
                $data2 = json_decode($data);
            }
            $sms_message = $this->job_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "Sample_collection_Sms"), array("id", "asc"));
            $sms_message = preg_replace("/{{NAME}}/", ucfirst($data1[0]["full_name"]), $sms_message[0]["message"]);
            $sms_message = preg_replace("/{{OID}}/", $orderid, $sms_message);
            //$sms_message = preg_replace("/{{PRICE}}/", "Rs." . $testprice, $sms_message);
            $this->load->helper("sms");
            $notification = new Sms();
            $mb_length = strlen($mobile);
            if ($mb_length == 10) {
                $notification::send($mobile, $sms_message);
            }
            if ($mb_length == 11 || $mb_length == 12 || $mb_length == 13) {
                $check_phone = substr($mobile, 0, 2);
                $check_phone1 = substr($mobile, 0, 1);
                $check_phone2 = substr($mobile, 0, 3);
                if ($check_phone2 == '+91') {
                    $get_phone = substr($mobile, 3);
                    $notification::send($get_phone, $sms_message);
                }
                if ($check_phone == '91') {
                    $get_phone = substr($mobile, 2);
                    $notification::send($get_phone, $sms_message);
                }
                if ($check_phone1 == '0') {
                    $get_phone = substr($mobile, 1);
                    $notification::send($get_phone, $sms_message);
                }
            }
            if ($payment_type == "Cash On Delivery" && $status == "0" && $discount == "0") {
                //echo "here";
                //die();
                $query = $this->job_model->master_fun_get_tbl_val("offer_master", array("status" => 1), array("id", "desc"));
                $caseback_per = $query[0]['caseback_per'];
                $query = $this->job_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $cust_fk), array("id", "desc"));
                $total = $query[0]['total'];
                $caseback_amount = ($price * $caseback_per) / 100;

                $data = array(
                    "cust_fk" => $cust_fk,
                    "credit" => $caseback_amount,
                    "total" => $total + $caseback_amount,
                    "type" => "Case Back",
                    "job_fk" => $cid,
                    "created_time" => date('Y-m-d H:i:s')
                );
                $insert1 = $this->job_model->master_fun_insert("wallet_master", $data);
                $query = $this->job_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $cust_fk), array("id", "desc"));
                $Current_wallet = $query[0]['total'];
// Case Back Email start
                $config['mailtype'] = 'html';
                $this->email->initialize($config);

                $message = '<div style="background:#a3a3a3 !important;font-family: "Roboto",sans-serif;">
<script src="https://use.fontawesome.com/44049e3dc5.js"></script>
    <div style="background: #6b6b6b none repeat scroll 0 0;float: left;padding: 7%;">
        <div style="border:1px solid #f1f1f1;border-bottom:1px solid #67B1A3;padding:1.5%;width:100%;float:left;background:#fff;">
            <div style="float:left;width:64%;">
                        <img alt="" src="' . base_url() . 'user_assets/images/logo.png" style="height: 100px;width:213px;"/>
            </div>
                <div style="float:right;text-align: right;width:33%;padding-top:7px;">

                
                
                </div>
                </div>
        <div style="padding:1.5%;width:100%;float:left;background-color:#fff;border:1px solid #f1f2f3;">
                <div style="padding:0 4%;">
                    <h4>' . $data1[0]['full_name'] . '</h4>
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
                Copyright @ 2016 Airmed PATH LABS. All rights reserved
        </div>
    </div>
</div>';
                $this->email->to($email);
                $this->email->from('donotreply@airmedpathlabs.com', 'Airmed PathLabs');
                $this->email->subject('CashBack');
                $this->email->message($message);
                $this->email->send();

                // Case Back Email end			
            }

            $config['mailtype'] = 'html';

            $this->email->initialize($config);

            $message1 = '<div style="background:#a3a3a3 !important;font-family: "Roboto",sans-serif;">
<script src="https://use.fontawesome.com/44049e3dc5.js"></script>
    <div style="background: #6b6b6b none repeat scroll 0 0;float: left;padding: 7%;">
        <div style="border:1px solid #f1f1f1;border-bottom:1px solid #67B1A3;padding:1.5%;width:100%;float:left;background:#fff;">
            <div style="float:left;width:64%;">
                        <img alt="" src="' . base_url() . 'user_assets/images/logo.png" style="height: 100px;width:213px;"/>
            </div>
                <div style="float:right;text-align: right;width:33%;padding-top:7px;">

               
                
                </div>
                </div>
        <div style="padding:1.5%;width:100%;float:left;background-color:#fff;border:1px solid #f1f2f3;">
                <div style="padding:0 4%;">
                    <h4><b>Dear</b>, ' . $name . '</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Test Sample has been collected successfully. </p>
						<p style="color:#7e7e7e;font-size:13px;"> You Booked Following Test/Package : ' . $testname . '/' . $packagename . ' </p>
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
                Copyright @ 2016 Airmed PATH LABS. All rights reserved
        </div>
    </div>
</div>';

            $this->email->to($email);
            $this->email->from("donotreply@airmedpathlabs.com", "Airmed PathLabs");
            $this->email->subject("Sample Collection Successfully");
            $this->email->message($message1);
            $this->email->send();
            $this->email->print_debugger();
            //	die();

            /* $this->session->set_flashdata("success", array("Sample Collection completed."));
              redirect("job-master/pending-list", "refresh"); */
        }
    }

    function export_csv() {

        $id = $this->uri->segment(3);
        $this->load->dbutil();

        $this->load->helper('file');
        $this->load->helper('download');
        $delimiter = ",";
        $newline = "\r\n";
        $filename = "filename_you_wish.csv";
        $query = "SELECT j.id,GROUP_CONCAT(t.test_name) testname,j.date,c.full_name FROM job_master j   LEFT JOIN job_test_list_master jtl ON jtl.job_fk=j.`id` LEFT JOIN customer_master c ON c.id=j.`cust_fk` LEFT JOIN test_master t ON t.id=jtl.test_fk WHERE j.`status`=$id GROUP BY j.id ORDER BY j.id DESC";
        $result = $this->db->query($query);
        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
        force_download($filename, $data);
    }

    function upload_report($cid = "") {

        $files = $_FILES;
        $this->load->library('upload');

        $testid = $this->input->post('testids');

        $ts = explode(',', $testid);

        //print_r($ts);
        //print_r($files['userfile']['name']);
        //	die();
        $file_loop = count($_FILES['userfile']['name']);

        if (!empty($_FILES['userfile']['name'])) {

            for ($i = 0; $i < $file_loop; $i++) {
                $desc = $this->input->post('desc_' . $i);

                $_FILES['userfile']['name'] = $files['userfile']['name'][$i];
                $_FILES['userfile']['type'] = $files['userfile']['type'][$i];
                $_FILES['userfile']['tmp_name'] = $files['userfile']['tmp_name'][$i];
                $_FILES['userfile']['error'] = $files['userfile']['error'][$i];
                $_FILES['userfile']['size'] = $files['userfile']['size'][$i];
                $config['upload_path'] = './upload/report/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|doc|docx';
                $config['file_name'] = time() . $files['userfile']['name'][$i];
                $config['file_name'] = str_replace(' ', '_', $config['file_name']);
                $config['overwrite'] = FALSE;
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();

                    //$this->session->set_flashdata("error", array($error));
                    //		redirect('job-master/job-details/'.$cid);
                } else {
                    //foreach ($ts as $key){
                    $row = $this->job_model->master_num_rows("report_master", array("job_fk" => $cid, "test_fk" => $ts[$i]));
                    //die();
                    if ($row == 1) {
                        $delete = $this->job_model->master_fun_update_multi("report_master", array("job_fk" => $cid, "test_fk" => $ts[$i]), array("report" => $config['file_name'], "original" => $_FILES['userfile']['name'], "description" => $desc));
                    } else {
                        $data['query'] = $this->job_model->master_fun_insert("report_master", array("job_fk" => $cid, "report" => $config['file_name'], "original" => $_FILES['userfile']['name'], "test_fk" => $ts[$i], "description" => $desc));
                    }




                    //}
                }
            }


            $ts = explode(',', $testid);
            $ctn = 0;
            foreach ($ts as $key) {
                $desc = $this->input->post('desc_' . $ctn);
                //	die();
                $update = $this->job_model->master_fun_update_multi("report_master", array("test_fk" => $key, "job_fk" => $cid), array("description" => $desc));
                $ctn++;
            }
            $this->session->set_flashdata("success", array("Report Upload successfully."));
            redirect('job-master/job-details/' . $cid);
        } else {
            //echo "hre"; die();
        }
    }

    function remove_report() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $jid = $this->uri->segment('4');
        $data['query'] = $this->job_model->master_fun_update("report_master", array("id", $cid), array("status" => "0"));

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
            $total_row = $this->job_model->num_row_srch_prescription($user, $date, $mobile, $status);
            $config = array();
            $get = $_GET;
            unset($get['offset']);
            $config["base_url"] = base_url() . "job-master/prescription-report?" . http_build_query($get);
            $config["total_rows"] = $total_row;
            $config["per_page"] = 10;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next <span class="icon-text">&#59230;</span>';
            $config['prev_link'] = '<span class="icon-text">&#59229;</span> Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->job_model->row_srch_prescription($user, $date, $mobile, $status, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        } else {
            $total_row = $this->job_model->num_row_srch_prescription("", "", "", "");
            $config["base_url"] = base_url() . "job-master/prescription-report";
            $config["total_rows"] = $total_row;
            $config["per_page"] = 10;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next <span class="icon-text">&#59230;</span>';
            $config['prev_link'] = '<span class="icon-text">&#59229;</span> Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->job_model->srch_prescription($config["per_page"], $page);
            if ($_GET['debug'] == "1") {
                print_r($data['query']);
                die();
            }
            $data["links"] = $this->pagination->create_links();
        }
        //$data['cityfk'] = $city;
        //print_r($data["login_data"]); die();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $data['success'] = $this->session->flashdata("success");
        //$data['query'] = $this->job_model->prescription_report_search($user, $date, $mobile, $status);
        //$this->load->view('admin/state_list_view', $data);

        $data['customer'] = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1), array("id", "asc"));
        $data['city'] = $this->job_model->master_fun_get_tbl_val("city", array('status' => 1), array("id", "asc"));
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('prescription_report', $data);
        $this->load->view('footer');
    }
	function get_test_list(){
		$ids = $this->input->post('ids');
		$pds = $this->input->post('pid');
		$data['query'] = $this->job_model->prescription_details($pds);
		$id = implode(",",$ids);
        $test = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND test_master.`id` NOT IN ($id) AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $data['query'][0]["city"] . "'");
		echo '<select class="selectpicker" data-live-search="true" id="test" data-placeholder="Select Test">
			<option value="">--Select Test--</option>';
			foreach ($test as $ts) { 
				echo ' <option value="'.$ts['id'].'"> '.ucfirst($ts['test_name']).' (Rs.'.$ts['price'].')</option>';
			}
		echo '</select>';
		//echo "<pre>"; print_r($data['test']);
	}
    function prescription_details() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();

        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['cid'] = $this->uri->segment(3);
        $data['success'] = $this->session->flashdata("success");
        $data['error'] = $this->session->flashdata("error");

        $data['query'] = $this->job_model->prescription_details($data['cid']);
        if ($_GET['debug'] == "1") {
            //print_r($data['query']);
            //die();
        }
        $data['test'] = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $data['query'][0]["city"] . "'");
        $data['test_check'] = $this->job_model->get_suggested_test($data['cid']);
        $data['state'] = $this->job_model->master_fun_get_tbl_val("state", array('status' => 1), array("state_name", "asc"));
        $data['city'] = $this->job_model->master_fun_get_tbl_val("test_cities", array('status' => 1), array("name", "asc"));
        $data['city1'] = $this->job_model->master_fun_get_tbl_val("city", array('status' => 1), array("city_name", "asc"));

        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('test', $data);
        $this->load->view('footer');
    }

    function get_test($city) {
        if (!is_loggedin()) {
            redirect('login');
        }
        if ($city != "") {
            $testdetils = $this->job_model->get_testval($city);
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
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $test = $this->input->post('test');
        //	print_r($test); die();
        $desc = $this->input->post('desc');
        $update = $this->job_model->master_fun_update_multi("suggested_test", array('p_id' => $cid), array("status" => 0));
        for ($i = 0; $i < count($test); $i++) {
            $data = array(
                "p_id" => $cid,
                "test_id" => $test[$i],
                "description" => $desc[$i]
            );
            $chk = $this->job_model->master_fun_get_tbl_val("suggested_test", array('status' => 1, 'p_id' => $cid, 'test_id' => $test[$i]), array("id", "asc"));
            $test_check = $this->job_model->master_fun_get_tbl_val("suggested_test", array('status' => 1, 'p_id' => $cid), array("id", "asc"));
            $insert = $this->job_model->master_fun_insert("suggested_test", $data);
            $upd = $this->job_model->master_fun_update("prescription_upload", array("id", $cid), array("status" => "2"));
        }
        $test_name_mail = array();
        for ($i = 0; $i < count($test); $i++) {
            $data = $this->job_model->master_fun_get_tbl_val("test_master", array("status" => 1, 'id' => $test[$i]), array("id", "asc"));
            $price = $price + $data[0]['price'];
            $test_name_mail[$i] = $data[0]['test_name'];
        }

        $data = $this->job_model->master_fun_get_tbl_val("prescription_upload", array("id" => $cid), array("id", "asc"));
        $cust_fk = $data[0]['cust_fk'];
        $img = $data[0]['image'];
        $desc = $data[0]['description'];
        $orderid = $data[0]['order_id'];
        $created_date = $data[0]['created_date'];
        $data1 = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "id" => $cust_fk), array("id", "asc"));

        $device_id = $data1[0]['device_id'];
        $mobile = $data1[0]['mobile'];
        $name = $data1[0]['full_name'];
        $email = $data1[0]['email'];
        $device_type = $data1[0]['device_type'];
        $message = "Your Suggested Test has been Generated";

        if ($device_type == 'android') {
            $notification_data = array("title" => "Airmed Path Lab", "message" => $message, "type" => "suggested_test", "id" => $cid, "img" => $img, "desc" => $desc, "created_date" => $created_date, "order_id" => $orderid);
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
        $sms_message = $this->job_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "Suggested_Test_Generated"), array("id", "asc"));
        $sms_message = preg_replace("/{{NAME}}/", ucfirst($data1[0]["full_name"]), $sms_message[0]["message"]);
        $sms_message = preg_replace("/{{OID}}/", $orderid, $sms_message);
        $sms_message = preg_replace("/{{PRICE}}/", '', $sms_message);
        $this->load->helper("sms");
        $notification = new Sms();
        $mb_length = strlen($mobile);
        if ($mb_length == 10) {
            $notification::send($mobile, $sms_message);
        }
        if ($mb_length == 11 || $mb_length == 12 || $mb_length == 13) {
            $check_phone = substr($mobile, 0, 2);
            $check_phone1 = substr($mobile, 0, 1);
            $check_phone2 = substr($mobile, 0, 3);
            if ($check_phone2 == '+91') {
                $get_phone = substr($mobile, 3);
                $notification::send($get_phone, $sms_message);
            }
            if ($check_phone == '91') {
                $get_phone = substr($mobile, 2);
                $notification::send($get_phone, $sms_message);
            }
            if ($check_phone1 == '0') {
                $get_phone = substr($mobile, 1);
                $notification::send($get_phone, $sms_message);
            }
        }
        /* Nishit send sms code end */
        $config['mailtype'] = 'html';
        $pathToUploadedFile = base_url() . "upload/" . $img;
        $this->email->initialize($config);

        $message1 = '<div style="background:#a3a3a3 !important;font-family: "Roboto",sans-serif;">
<script src="https://use.fontawesome.com/44049e3dc5.js"></script>
    <div style="background: #6b6b6b none repeat scroll 0 0;float: left;padding: 7%;">
        <div style="border:1px solid #f1f1f1;border-bottom:1px solid #67B1A3;padding:1.5%;width:100%;float:left;background:#fff;">
            <div style="float:left;width:64%;">
                        <img alt="" src="' . base_url() . 'user_assets/images/logo.png" style="height: 100px;width:213px;"/>
            </div>
                <div style="float:right;text-align: right;width:33%;padding-top:7px;">

              
                
                </div>
                </div>
        <div style="padding:1.5%;width:100%;float:left;background-color:#fff;border:1px solid #f1f2f3;">
                <div style="padding:0 4%;">
                    <h4><b>Dear, ' . $name . '</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Suggested Test has been Generated. </p>
						 <p style="color:#7e7e7e;font-size:13px;">Your Suggested Test are ' . implode($test_name_mail, ',') . ' </p>
                        <p style="color:#7e7e7e;font-size:13px;">Your Order ID : ' . $orderid . '</p>    
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
                Copyright @ 2016 Airmed PATH LABS. All rights reserved
        </div>
    </div>
</div>';

        $this->email->to($email);
        $this->email->from("donotreply@airmedpathlabs.com", "Airmed PathLabs");
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
        $data['customer'] = $this->job_model->master_fun_get_tbl_val("customer_master", array("status" => "1"));
        $data['query'] = $this->job_model->package_test_inquiry();
        //$this->load->view('admin/state_list_view', $data);
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('package_test_inquiry_list', $data);
        $this->load->view('footer');
    }

    function contact_pending() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->job_model->master_fun_update("book_without_login", array("id", $cid), array("status" => "1"));

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
        $data['query'] = $this->job_model->master_fun_update("book_without_login", array("id", $cid), array("status" => "0"));

        $this->session->set_flashdata("success", array("Contact Deleted Successfully"));
        redirect("job_master/Package_test_inquiry_list", "refresh");
    }

    function contact_complete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->job_model->master_fun_update("book_without_login", array("id", $cid), array("status" => "2"));

        $this->session->set_flashdata("success", array("Completed successfully"));
        redirect("job_master/Package_test_inquiry_list", "refresh");
    }

    function book_by_admin() {

        $test = $this->uri->segment(3);
        $package = $this->uri->segment(4);
        $uid = $this->uri->segment(5);
        $id = $this->uri->segment(6);
        $order_id = random_string('numeric', 13);
        $date = date('Y-m-d H:i:s');
        $test = explode(',', $test);
        $package = explode(',', $package);
        $test_package_name = array();
        foreach ($test as $key) {

            $price1 = $this->user_test_master_model->master_fun_get_tbl_val("test_master", array("status" => 1, "id" => $key), array("test_name", "asc"));
            $price += $price1[0]['price'];
            $test_package_name[] = $price1[0]['test_name'];
        }
        foreach ($package as $key) {
            $price1 = $this->user_test_master_model->master_fun_get_tbl_val("package_master", array("status" => 1, "id" => $key), array("title", "asc"));
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
        );

        $insert = $this->user_test_master_model->master_fun_insert("job_master", $data);
        foreach ($test as $key) {

            $this->user_test_master_model->master_fun_insert("job_test_list_master", array("job_fk" => $insert, "test_fk" => $key));
        }
        foreach ($package as $key) {
            $this->user_test_master_model->master_fun_insert("book_package_master", array("job_fk" => $insert, "date" => $date, "order_id" => $order_id, "package_fk" => $key, "cust_fk" => $uid, "type" => "2"));
        }
        $query = $this->job_model->master_fun_update("book_without_login", array("id", $id), array("status" => "2"));

        $destail = $this->user_test_master_model->master_fun_get_tbl_val("customer_master", array("status" => 1, "id" => $uid), array("id", "asc"));

        $config['mailtype'] = 'html';
        $this->email->initialize($config);

        $message = '<div style="background:#a3a3a3 !important;font-family: "Roboto",sans-serif;">
<script src="https://use.fontawesome.com/44049e3dc5.js"></script>
    <div style="background: #6b6b6b none repeat scroll 0 0;float: left;padding: 7%;">
        <div style="border:1px solid #f1f1f1;border-bottom:1px solid #67B1A3;padding:1.5%;width:100%;float:left;background:#fff;">
            <div style="float:left;width:64%;">
                        <img alt="" src="' . base_url() . 'user_assets/images/logo.png" style="height: 100px;width:213px;"/>
            </div>
                <div style="float:right;text-align: right;width:33%;padding-top:7px;">

               
                
                </div>
                </div>
        <div style="padding:1.5%;width:100%;float:left;background-color:#fff;border:1px solid #f1f2f3;">
                <div style="padding:0 4%;">
                    <h4><b>Booked Successfully</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Booking successfully. </p>
                     <p style="color:#7e7e7e;font-size:13px;"> You Booked : . ' . implode($test_package_name, ',') . '  </p>  
<p style="color:#7e7e7e;font-size:13px;"> Your Booked Amount is Rs. ' . $price . '  </p>
        <p style="color:#7e7e7e;font-size:13px;"> Payment Type : Cash on Blood Collection</p>
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
                Copyright @ 2016 Airmed PATH LAB. All rights reserved
        </div>
    </div>
</div>';
        $this->email->to($destail[0]['email']);
        $this->email->from('donotreply@airmedpathlabs.com', 'Airmed PathLabs');
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
        $id = $this->uri->segment(3);
        $test = $this->input->post('testids');
        $uid = $this->input->post('userid');
        $payable = $this->input->post('payable');
        $discount = $this->input->post('discount');
        $order_id = random_string('numeric', 13);

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
        foreach ($test as $key) {

            $this->user_test_master_model->master_fun_insert("job_test_list_master", array("job_fk" => $insert, "test_fk" => $key));
        }

        $destail = $this->user_test_master_model->master_fun_get_tbl_val("customer_master", array("status" => 1, "id" => $uid), array("id", "asc"));

        $config['mailtype'] = 'html';
        $this->email->initialize($config);

        $message = '<div style="background:#a3a3a3 !important;font-family: "Roboto",sans-serif;">
<script src="https://use.fontawesome.com/44049e3dc5.js"></script>
    <div style="background: #6b6b6b none repeat scroll 0 0;float: left;padding: 7%;">
        <div style="border:1px solid #f1f1f1;border-bottom:1px solid #67B1A3;padding:1.5%;width:100%;float:left;background:#fff;">
            <div style="float:left;width:64%;">
                        <img alt="" src="' . base_url() . 'user_assets/images/logo.png" style="height: 100px;width:213px;"/>
            </div>
                <div style="float:right;text-align: right;width:33%;padding-top:7px;">

               
                
                </div>
                </div>
        <div style="padding:1.5%;width:100%;float:left;background-color:#fff;border:1px solid #f1f2f3;">
                <div style="padding:0 4%;">
                    <h4><b>Booked Successfully</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Booking successfully. </p>
                     <p style="color:#7e7e7e;font-size:13px;"> You Booked : . ' . implode($test_package_name, ',') . '  </p>  
<p style="color:#7e7e7e;font-size:13px;"> Your Booked Amount is Rs. ' . $payable . '  </p>
        <p style="color:#7e7e7e;font-size:13px;"> Payment Type : Cash on Blood Collection</p>
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
                Copyright @ 2016 Airmed PATH LAB. All rights reserved
        </div>
    </div>
</div>';
        $this->email->to($destail[0]['email']);
        $this->email->from('donotreply@airmedpathlabs.com', 'Airmed PathLabs');
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

            $data1 = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1), array("id", "asc"));
//	$ctn = 1 ;
            foreach ($data1 as $key) {
                $device_id = $key['device_id'];
                $mobile = $key['mobile'];
                $name = $key['full_name'];
                $email = $key['email'];
                $device_type = $key['device_type'];
                //$message = "this is developer test";

                if ($device_type == 'android') {
                    $notification_data = array("title" => "Airmed Path Lab", "message" => $message, "type" => "home");
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
                /* Nishit send sms code start 
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
                  Nishit send sms code end */
                //	$ctn++;
            }
//echo $ctn; die();
            $this->session->set_flashdata("success", array("Notification sent successfully"));
            redirect("job_master/all_pushnotification/", "refresh");
        }
    }

    function pending_count() {

        $data = $this->job_model->pending_job_count();
        $data1 = $this->job_model->instant_contact_count();
        $data2 = $this->job_model->all_inquiry_count();
        $allticket = $this->job_model->master_num_rows('ticket_master', array("views" => '0', "status" => '1'));
        $jobs = $this->job_model->master_num_rows('job_master', array("views" => '0', "status" => '1'));
        $contact_us = $this->job_model->master_num_rows('contact_us', array("views" => '0', "status" => '1'));
        $job_total = $data->total;
        $package_total = $data1->total;
        $all_inquiry_total = $data2->total;
        $all_total = $package_total + $all_inquiry_total + $contact_us;
        $myarray = array("job_count" => $jobs, "package_count" => $package_total, "all_inquiry" => $all_inquiry_total, "all_total" => $all_total, "tickepanding" => $allticket, "contact_us_count" => $contact_us);
        //print_r($myarray); die();
        echo $json = json_encode($myarray);
        //print_r($data['query']);die();
    }

    function get_content($URL) {
        //echo $URL;
        //die();
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

}

?>
