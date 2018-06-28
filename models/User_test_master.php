<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User_test_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('home_model');
        $this->load->model('user_test_master_model');
        $this->load->model('user_wallet_model');
		 $this->load->model('service_model');
		
        $this->load->helper('string');
        $this->load->library('email');

        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        if ($uid != 0) {
            $maxid = $this->user_wallet_model->total_wallet($uid);
            $data['total'] = $this->user_wallet_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "id" => $maxid), array("id", "asc"));
            $this->data['wallet_amount'] = $data['total'][0]['total'];
        }
		/*pinkesh code start*/
		$data['links'] = $this->user_test_master_model->master_fun_get_tbl_val("patholab_home_master", array("status" => 1),array("id","asc"));
		$this->data['all_links']=$data['links'];
	/*pinkesh code end*/
    }

    function index() {
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
    }

    function book_test() {

        if (!is_userloggedin()) {
            redirect('user_login');
        }

        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
if($this->input->post()){
 $test = $this->input->post('test');
	$test = implode($test,',');
 
}else{
	$test = $this->uri->segment(3);
}
      
	   
	   
        if ($test != "") {
            $test1 = explode(',',$test);
        }
		

       // print_r($test1); die();
        $order_id = random_string('numeric', 13);
        $date = date('Y-m-d H:i:s');

        		$price = 0;
	//	print_r($data["test_ids"]);
		$cnt = 0;
		foreach($test1 as $key){
		$ex = explode('-',$key);
	 $first_pos = $ex[0];
	 $id = $ex[1];
		if($first_pos=="t"){
		$price1 = $this->user_test_master_model->master_fun_get_tbl_val("test_master", array("status" => 1,"id"=>$id),array("test_name","asc"));
$price += $price1[0]['price'];		
		}
		if($first_pos=="p"){
			$price1 = $this->user_test_master_model->master_fun_get_tbl_val("package_master", array("status" => 1,"id"=>$id),array("title","asc"));
			$price += $price1[0]['d_price'];
		}
		
			//echo $price1[0]['price'];die();
			
		$cnt++;
		}
		//echo $price; die();
        $maxid = $this->user_wallet_model->total_wallet($uid);
        $data['total'] = $this->user_wallet_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "id" => $maxid), array("id", "asc"));
        $wallet_amount = $data['total'][0]['total'];
        if (empty($test)) {

            $this->session->set_flashdata("error", array("Please Select Your Test From List"));
            redirect("user_master/upload_prescription", "refresh");
        }
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
            redirect('user_test_master/payment_method_wallet/' . $test, "refresh");
        } else {

            $payamount = $price - $wallet_amount;
            //	$this->session->set_flashdata("error", array("No Sufficient Balance in Wallet.. Please Add Money to wallet"));
            // redirect('user_test_master/payumoney/'.$test1.'/'.$payamount, "refresh");
            redirect('user_test_master/payment_method/' . $test, "refresh");
        }
    }

    function payment_method() {

        if (!is_userloggedin()) {
            redirect('user_login');
        }

        $data["login_data"] = loginuser();
        // print_r( $data["login_data"]);
        // die();
        $uid = $data["login_data"]['id'];

        $maxid = $this->user_wallet_model->total_wallet($uid);
        $data['total'] = $this->user_wallet_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "id" => $maxid), array("id", "asc"));
        $wallet_amount = $data['total'][0]['total'];

        $test1 = $this->uri->segment(3);
        $data["test_ids"] = $test1;
        if ($test1 != "") {
            $test = explode(',', $test1);
        }
        
        $price = 0;
        /*for ($i = 0; $i < count($test); $i++) {
            $data1 = $this->user_test_master_model->master_fun_get_tbl_val("test_master", array("status" => 1, 'id' => $test[$i]), array("id", "asc"));
            $price = $price + $data1[0]['price'];
        }*/
        /* Nishit price count start */
        $cnt = 0;
        foreach ($test as $key) {
            $ex = explode('-', $key);
            $first_pos = $ex[0];
            $id = $ex[1];
            if ($first_pos == "t") {
                $price1 = $this->user_test_master_model->master_fun_get_tbl_val("test_master", array("status" => 1, "id" => $id), array("test_name", "asc"));
                $price += $price1[0]['price'];
            }
            if ($first_pos == "p") {
                $price1 = $this->user_test_master_model->master_fun_get_tbl_val("package_master", array("status" => 1, "id" => $id), array("title", "asc"));
                $price += $price1[0]['d_price'];
            }
            $cnt++;
        }
        /* Nishit price count end */
        $data['testid'] = $this->uri->segment(3);
        $data['price'] = $price;
        $data['payamount'] = $price - $wallet_amount;
        $data['wallet'] = $wallet_amount;

        $data["state"] = $this->user_test_master_model->master_fun_get_tbl_val("state", array("status" => 1), array("state_name", "asc"));
        $this->load->view('user/header', $data);
        $this->load->view('user/confirm_amount', $data);
       $this->load->view('user/footer');
    }

    function payment_method_wallet() {
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
        // print_r( $data["login_data"]);
        // die();
        $uid = $data["login_data"]['id'];
        $maxid = $this->user_wallet_model->total_wallet($uid);
        $data['total'] = $this->user_wallet_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "id" => $maxid), array("id", "asc"));
        $wallet_amount = $data['total'][0]['total'];
        $test1 = $this->uri->segment(3);
        if ($test1 != "") {
            $test = explode(',', $test1);
        }
        $price = 0;
        /*for ($i = 0; $i < count($test); $i++) {
            $data1 = $this->user_test_master_model->master_fun_get_tbl_val("test_master", array("status" => 1, 'id' => $test[$i]), array("id", "asc"));
            $price = $price + $data1[0]['price'];
        }*/
        /* Nishit price count start */
        $cnt = 0;
        foreach ($test as $key) {
            $ex = explode('-', $key);
            $first_pos = $ex[0];
            $id = $ex[1];
            if ($first_pos == "t") {
                $price1 = $this->user_test_master_model->master_fun_get_tbl_val("test_master", array("status" => 1, "id" => $id), array("test_name", "asc"));
                $price += $price1[0]['price'];
            }
            if ($first_pos == "p") {
                $price1 = $this->user_test_master_model->master_fun_get_tbl_val("package_master", array("status" => 1, "id" => $id), array("title", "asc"));
                $price += $price1[0]['d_price'];
            }
            $cnt++;
        }
        /* Nishit price count end */
		 $data["state"] = $this->user_test_master_model->master_fun_get_tbl_val("state", array("status" => 1), array("state_name", "asc"));
		  $data["doctor"] = $this->user_test_master_model->master_fun_get_tbl_val("doctor_master", array("status" => 1), array("full_name", "asc"));
        $data['testid'] = $this->uri->segment(3);
		$data["test_ids"] = $test1;
        $data['price'] = $price;
        $data['payamount'] = $wallet_amount - $price;
        $data['wallet'] = $wallet_amount;
        $this->load->view('user/header', $data);
        $this->load->view('user/confirm_amount1', $data);
        $this->load->view('user/footer');
    }

    function payumoney() {

        $this->load->library('session');
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
        //print_r($data["login_data"]); die();
        $uid = $data["login_data"]['id'];
        // $data['payamount'] = $this->uri->segment(4);
        //
	 //die();
        $data['rndtxn'] = random_string('numeric', 20);
        $data['testid'] = $this->uri->segment(3);

//$type = $this->input->post('ptype');
        //  $data['method'] = $type;
        $data['payamount'] = $this->input->post('amount');
        $data['method'] = $this->uri->segment(4);

        //die();
        $destail = $this->user_wallet_model->master_fun_get_tbl_val("customer_master", array("status" => 1, "id" => $uid), array("id", "asc"));

        $data['user_detail'] = $destail;

        $this->load->view('user/payumoney1', $data);
    }

    function success_payumoney() {
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
        //print_r($data["login_data"]); die();
        $uid = $data["login_data"]['id'];
        $response = $_REQUEST;
        //print_R($response); die();
        $trnscaton_id = $response['txnid'];
        $amount = $response['amount'];
        $status = $response['status'];
        $paydate = $response['addedon'];
        $this->load->library('session');
        $test1 = $this->uri->segment(3);
        $payment_method = $this->uri->segment(4);
		$refe = $this->uri->segment(5);
		$doct = $this->uri->segment(6);
        $test = explode(',', $test1);
        //print_r($test);
        //count($test1);
        // die();
        $t = json_encode($response);
        //$user_id = $this->session->userdata('user_id');
        //$consumer = $this->user_model->get_user_by_id($user_id);
        //  $consumer_name = $consumer["user_first_name"] . " " . $consumer["user_last_name"];
        //  $consumer_number = $consumer["user_phone"];
        //  $consumer_address = $consumer["user_address_1"];
        // $the_quote = $this->consumer_model->get_quotes($job_id, $user_id);
        if ($response['status'] == "success") {

            // job add
            $order_id = random_string('numeric', 13);
            $date = date('Y-m-d H:i:s');

            $price = 0;
            $test_name_mail=array();
            for ($i = 0; $i < count($test); $i++) {
                $data = $this->user_test_master_model->master_fun_get_tbl_val("test_master", array("status" => 1, 'id' => $test[$i]), array("id", "asc"));
                $price = $price + $data[0]['price'];
                $$test_name_mail[$i]=$data[0]['test_name'];
            }
            $maxid = $this->user_wallet_model->total_wallet($uid);
            $data['total'] = $this->user_wallet_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "id" => $maxid), array("id", "asc"));
            $wallet_amount = $data['total'][0]['total'];
            if (empty($test)) {

                $this->session->set_flashdata("error", array("Please Select Your Test From List"));
                redirect("user_test_master", "refresh");
            }

            $insert = $this->user_test_master_model->master_fun_insert("job_master", array("cust_fk" => $uid, "date" => $date, "order_id" => $order_id, "payment_type" => "PayUMoney","doctor"=>$doct,"other_reference"=>$refe));

            $price = 0;
			
			 $package_list = explode(",", $test1);
                foreach ($package_list as $key) {
                    $testid = explode("-",$key);
                    if($testid[0]=='t'){
                    $this->user_test_master_model->master_fun_insert("job_test_list_master", array("job_fk" => $insert, "test_fk" => $testid[1]));
                    }
                    if($testid[0]=='p'){
                    $this->user_test_master_model->master_fun_insert("book_package_master", array("job_fk" => $insert,"date" => $date,"order_id" => $order_id, "package_fk" => $testid[1],"cust_fk" => $uid,"type"=>"2"));
                    }
                }
			
              $cnt = 0;
        foreach ($test as $key) {
            $ex = explode('-', $key);
            $first_pos = $ex[0];
            $id = $ex[1];
            if ($first_pos == "t") {
                $price1 = $this->user_test_master_model->master_fun_get_tbl_val("test_master", array("status" => 1, "id" => $id), array("test_name", "asc"));
                $price += $price1[0]['price'];
            }
            if ($first_pos == "p") {
                $price1 = $this->user_test_master_model->master_fun_get_tbl_val("package_master", array("status" => 1, "id" => $id), array("title", "asc"));
                $price += $price1[0]['d_price'];
            }
            $cnt++;
        }
			
            $update = $this->user_test_master_model->master_fun_update("job_master", array('id', $insert), array("price" => $price));
            // end job add
				 $query = $this->user_test_master_model->master_fun_get_tbl_val("offer_master", array("status" => 1), array("id", "desc"));
            $caseback_per = $query[0]['caseback_per'];
			
				$caseback_amount = ($price * $caseback_per)/100;

            $query = $this->user_wallet_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $uid), array("id", "desc"));
            $total = $query[0]['total'];

            $data1 = array("payomonyid" => $trnscaton_id,
                "amount" => $amount,
                "paydate" => $paydate,
                "status" => $status,
                "uid" => $uid,
                "type" => "job",
                "job_fk" => $insert,
                "data" => $t,
            );
            $insert1 = $this->user_wallet_model->master_fun_insert("payment", $data1);

            if ($payment_method == "wallet" && $total != 0) {

                $data1 = array(
                    "cust_fk" => $uid,
                    "debit" => $wallet_amount,
                    "total" => $total - $wallet_amount,
                    "job_fk" => $insert,
                    "created_time" => date('Y-m-d H:i:s')
                );
                $insert1 = $this->user_wallet_model->master_fun_insert("wallet_master", $data1);
            }
			$query = $this->user_wallet_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $uid), array("id", "desc"));
			$total = $query[0]['total'];
			$data = array(
                "cust_fk" => $uid,
                "credit" => $caseback_amount,
                "total" => $total + $caseback_amount,
                "type"=> "Case Back",
				"job_fk"=>$insert,
                "created_time" => date('Y-m-d H:i:s')
            );
            $insert = $this->user_test_master_model->master_fun_insert("wallet_master", $data);
			
			$query = $this->user_wallet_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $uid), array("id", "desc"));
			$Current_wallet = $query[0]['total'];
			
        $destail = $this->user_wallet_model->master_fun_get_tbl_val("customer_master", array("status" => 1, "id" => $uid), array("id", "asc"));

        $config['mailtype'] = 'html';
        $this->email->initialize($config);
        
/*$message = "You recently requested to reset your password for your  Patholab Account. Click the button below to reset it.<br/><br/>";
$message .= "<a href='".base_url()."user_get_password/index/".$rs."' style='background-color:#dc4d2f;color:#ffffff;display:inline-block;font-size:15px;line-height:45px;text-align:center;width:200px;border-radius:3px;text-decoration:none;'>Reset your password</a><br/><br/>";
$message .= "If you did not request a password reset, please ignore this email or reply to let us know.<br/><br/>";
$message .= "Thanks <br/> Patholab";*/
$message='<div style="background:#a3a3a3 !important;font-family: "Roboto",sans-serif;">
<script src="https://use.fontawesome.com/44049e3dc5.js"></script>
    <div style="background: #6b6b6b none repeat scroll 0 0;float: left;padding: 7%;">
        <div style="border:1px solid #f1f1f1;border-bottom:1px solid #67B1A3;padding:1.5%;width:100%;float:left;background:#fff;">
            <div style="float:left;width:64%;">
                        <img alt="" src="'.base_url().'user_assets/images/logo.png" style="height: 100px;width:213px;"/>
            </div>
                <div style="float:right;text-align: right;width:33%;padding-top:7px;">

               
                
                </div>
                </div>
        <div style="padding:1.5%;width:100%;float:left;background-color:#fff;border:1px solid #f1f2f3;">
                <div style="padding:0 4%;">
                    <h4 style="color:#67B1A3;text-decoration: underline;">Test Book Successfully</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Test has been book successfully. </p>
                       
<p style="color:#7e7e7e;font-size:13px;"> Rs. '.$price.' Debited From your account. </p>
<p style="color:#7e7e7e;font-size:13px;"> Rs. '.$amount.' Paid using PayUMoney. </p>
		<p style="color:#7e7e7e;font-size:13px;">Your Current balance is Rs. '.$Current_wallet.'</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>
        </div>
        <div style="width:100%;float:left;background:#F5F5F5;padding:1.5%;color:#828282;font-size:12px;border:1px solid #dfdfdf;text-align:center;">
                <a href="'.base_url().'user_master/about_us" style="color:#515151;text-decoration:none;">About Us</a> |
                <a href="'.base_url().'user_master/contact_us" style="color:#515151;text-decoration:none;">Contact Us</a> |
                <a href="'.base_url().'user_login" style="color:#515151;text-decoration:none;">Login</a> |
                <a href="'.base_url().'user_master/collection" style="color:#515151;text-decoration:none;">Home-collection</a> |
                <a href="https://www.facebook.com/airmedpathlabs" style="color:#515151;text-decoration:none;"><i aria-hidden="true"><img src="'.base_url().'user_assets/images/face_book_icon1.jpeg" style="height:15px;width:15px;"></i></a>                  
        </div>
        <div style="width:100%;float:left;background:#333333;padding:1.5%;color:#ccc;font-size:12px;border:1px solid #323232;text-align:center;">
                Copyright @ 2016 Airmed PATH LABS. All rights reserved
        </div>
    </div>
</div>';
        $this->email->to($destail[0]['email']);
        $this->email->from('donotreply@airmed.com', 'Airmed PATH LAB');
        $this->email->subject('Test Book Successfully');
        $this->email->message($message);
        $this->email->send();
		
	// Case Back Email start
	
	$message='<div style="background:#a3a3a3 !important;font-family: "Roboto",sans-serif;">
<script src="https://use.fontawesome.com/44049e3dc5.js"></script>
    <div style="background: #6b6b6b none repeat scroll 0 0;float: left;padding: 7%;">
        <div style="border:1px solid #f1f1f1;border-bottom:1px solid #67B1A3;padding:1.5%;width:100%;float:left;background:#fff;">
            <div style="float:left;width:64%;">
                        <img alt="" src="'.base_url().'user_assets/images/logo.png" style="height: 100px;width:213px;"/>
            </div>
                <div style="float:right;text-align: right;width:33%;padding-top:7px;">

                
                
                </div>
                </div>
        <div style="padding:1.5%;width:100%;float:left;background-color:#fff;border:1px solid #f1f2f3;">
                <div style="padding:0 4%;">
                    <h4 style="color:#67B1A3;text-decoration: underline;">Test Book Successfully</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Cashback Credited in your Wallet. </p>
                       
<p style="color:#7e7e7e;font-size:13px;"> Rs. '.$caseback_amount.' Credited in your account. </p>
		<p style="color:#7e7e7e;font-size:13px;">Your Current Wallet Amount is Rs. '.$Current_wallet.'</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>
        </div>
        <div style="width:100%;float:left;background:#F5F5F5;padding:1.5%;color:#828282;font-size:12px;border:1px solid #dfdfdf;text-align:center;">
                <a href="'.base_url().'user_master/about_us" style="color:#515151;text-decoration:none;">About Us</a> |
                <a href="'.base_url().'user_master/contact_us" style="color:#515151;text-decoration:none;">Contact Us</a> |
                <a href="'.base_url().'user_login" style="color:#515151;text-decoration:none;">Login</a> |
                <a href="'.base_url().'user_master/collection" style="color:#515151;text-decoration:none;">Home-collection</a> |
                <a href="https://www.facebook.com/airmedpathlabs" style="color:#515151;text-decoration:none;"><i aria-hidden="true"><img src="'.base_url().'user_assets/images/face_book_icon1.jpeg" style="height:15px;width:15px;"></i></a>                  
        </div>
        <div style="width:100%;float:left;background:#333333;padding:1.5%;color:#ccc;font-size:12px;border:1px solid #323232;text-align:center;">
                Copyright @ 2016 Airmed PATH LABS. All rights reserved
        </div>
    </div>
</div>';
        $this->email->to($destail[0]['email']);
        $this->email->from('donotreply@airmed.com', 'Airmed PATH LAB');
        $this->email->subject('CashBack');
        $this->email->message($message);
        $this->email->send();
	
	// Case Back Email end

            $this->session->set_flashdata('payment_success', array("Thank You!..Your Test has been Booked Successfully"));
            $url = "/";
            redirect($url);
        }
    }

    function book_test1() {

        if (!is_userloggedin()) {
            redirect('user_login');
        }

        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];

        $test1 = $this->uri->segment(3);
        if ($test1 != "") {
            $test = explode(',', $test1);
        }
 $doctor = $this->input->post('doctor');
 $refe = $this->input->post('reference');
//die();
        //print_r($test1); die();
        $order_id = random_string('numeric', 13);
        $date = date('Y-m-d H:i:s');
                $price = 0;

$test_name_mail=array();

 


          /*  for ($i = 0; $i < count($test); $i++) {
                $data = $this->user_test_master_model->master_fun_get_tbl_val("test_master", array("status" => 1, 'id' => $test[$i]), array("id", "asc"));
                $price = $price + $data[0]['price'];
                $test_name_mail[$i]=$data[0]['test_name'];
            }*/
      
        $maxid = $this->user_wallet_model->total_wallet($uid);
        $data['total'] = $this->user_wallet_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "id" => $maxid), array("id", "asc"));
        $wallet_amount = $data['total'][0]['total'];
        if (empty($test)) {

            $this->session->set_flashdata("error", array("Please Select Your Test From List"));
            redirect("user_test_master", "refresh");
        }


        $insert = $this->user_test_master_model->master_fun_insert("job_master", array("cust_fk" => $uid, "date" => $date, "order_id" => $order_id, "payment_type" => "Wallet","doctor"=>$doctor,"other_reference"=>$refe));

		
		$package_list = explode(",", $test1);
                foreach ($package_list as $key) {
                    $testid = explode("-",$key);
                    if($testid[0]=='t'){
                    $this->user_test_master_model->master_fun_insert("job_test_list_master", array("job_fk" => $insert, "test_fk" => $testid[1]));
                    }
                    if($testid[0]=='p'){
                    $this->user_test_master_model->master_fun_insert("book_package_master", array("job_fk" => $insert,"date" => $date,"order_id" => $order_id, "package_fk" => $testid[1],"cust_fk" => $uid,"type"=>"2"));
                    }
                }
				
 $cnt = 0;
        foreach ($test as $key) {
            $ex = explode('-', $key);
            $first_pos = $ex[0];
            $id = $ex[1];
            if ($first_pos == "t") {
                $price1 = $this->user_test_master_model->master_fun_get_tbl_val("test_master", array("status" => 1, "id" => $id), array("test_name", "asc"));
                $price += $price1[0]['price'];
            }
            if ($first_pos == "p") {
                $price1 = $this->user_test_master_model->master_fun_get_tbl_val("package_master", array("status" => 1, "id" => $id), array("title", "asc"));
                $price += $price1[0]['d_price'];
            }
            $cnt++;
        }
        $update = $this->user_test_master_model->master_fun_update("job_master", array('id', $insert), array("price" => $price));
        if ($update) {
            $query = $this->user_test_master_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $uid), array("id", "desc"));
            $total = $query[0]['total'];
				
				 $query = $this->user_test_master_model->master_fun_get_tbl_val("offer_master", array("status" => 1), array("id", "desc"));
            $caseback_per = $query[0]['caseback_per'];
				$caseback_amount = ($price * $caseback_per)/100;
				
            $data = array(
                "cust_fk" => $uid,
                "debit" => $price,
                "total" => $total - $price,
                "job_fk" => $insert,
                "created_time" => date('Y-m-d H:i:s')
            );
            $insert1 = $this->user_test_master_model->master_fun_insert("wallet_master", $data);
			$query = $this->user_test_master_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $uid), array("id", "desc"));
			$total = $query[0]['total'];
			$data = array(
                "cust_fk" => $uid,
                "credit" => $caseback_amount,
                "total" => $total + $caseback_amount,
                "type"=> "Case Back",
				"job_fk"=>$insert,
                "created_time" => date('Y-m-d H:i:s')
            );
            $insert = $this->user_test_master_model->master_fun_insert("wallet_master", $data);
			
			$query = $this->user_test_master_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $uid), array("id", "desc"));
			$Current_wallet = $query[0]['total'];
			
              $destail = $this->user_wallet_model->master_fun_get_tbl_val("customer_master", array("status" => 1, "id" => $uid), array("id", "asc"));

        $config['mailtype'] = 'html';
        $this->email->initialize($config);
        
/*$message = "You recently requested to reset your password for your  Patholab Account. Click the button below to reset it.<br/><br/>";
$message .= "<a href='".base_url()."user_get_password/index/".$rs."' style='background-color:#dc4d2f;color:#ffffff;display:inline-block;font-size:15px;line-height:45px;text-align:center;width:200px;border-radius:3px;text-decoration:none;'>Reset your password</a><br/><br/>";
$message .= "If you did not request a password reset, please ignore this email or reply to let us know.<br/><br/>";
$message .= "Thanks <br/> Patholab";*/
$message='<div style="background:#a3a3a3 !important;font-family: "Roboto",sans-serif;">
<script src="https://use.fontawesome.com/44049e3dc5.js"></script>
    <div style="background: #6b6b6b none repeat scroll 0 0;float: left;padding: 7%;">
        <div style="border:1px solid #f1f1f1;border-bottom:1px solid #67B1A3;padding:1.5%;width:100%;float:left;background:#fff;">
            <div style="float:left;width:64%;">
                        <img alt="" src="'.base_url().'user_assets/images/logo.png" style="height: 100px;width:213px;"/>
            </div>
                <div style="float:right;text-align: right;width:33%;padding-top:7px;">

                
                
                </div>
                </div>
        <div style="padding:1.5%;width:100%;float:left;background-color:#fff;border:1px solid #f1f2f3;">
                <div style="padding:0 4%;">
                    <h4 style="color:#67B1A3;text-decoration: underline;">Test Book Successfully</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Test has been book successfully. </p>
                        
		<p style="color:#7e7e7e;font-size:13px;"> Rs. '.$price.' Debited From your account. </p>
		<p style="color:#7e7e7e;font-size:13px;">Your Current balance is Rs. '.$total.'</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>
        </div>
        <div style="width:100%;float:left;background:#F5F5F5;padding:1.5%;color:#828282;font-size:12px;border:1px solid #dfdfdf;text-align:center;">
                <a href="'.base_url().'user_master/about_us" style="color:#515151;text-decoration:none;">About Us</a> |
                <a href="'.base_url().'user_master/contact_us" style="color:#515151;text-decoration:none;">Contact Us</a> |
                <a href="'.base_url().'user_login" style="color:#515151;text-decoration:none;">Login</a> |
                <a href="'.base_url().'user_master/collection" style="color:#515151;text-decoration:none;">Home-collection</a> |
                <a href="https://www.facebook.com/airmedpathlabs" style="color:#515151;text-decoration:none;"><i aria-hidden="true"><img src="'.base_url().'user_assets/images/face_book_icon1.jpeg" style="height:15px;width:15px;"></i></a>                  
        </div>
        <div style="width:100%;float:left;background:#333333;padding:1.5%;color:#ccc;font-size:12px;border:1px solid #323232;text-align:center;">
                Copyright @ 2016 Airmed PATH LABS. All rights reserved
        </div>
    </div>
</div>';
        $this->email->to($destail[0]['email']);
        $this->email->from('donotreply@airmed.com', 'Airmed PATH LAB');
        $this->email->subject('Test Book Successfully');
        $this->email->message($message);
        $this->email->send();
		
		// Case Back Email start
	
	$message='<div style="background:#a3a3a3 !important;font-family: "Roboto",sans-serif;">
<script src="https://use.fontawesome.com/44049e3dc5.js"></script>
    <div style="background: #6b6b6b none repeat scroll 0 0;float: left;padding: 7%;">
        <div style="border:1px solid #f1f1f1;border-bottom:1px solid #67B1A3;padding:1.5%;width:100%;float:left;background:#fff;">
            <div style="float:left;width:64%;">
                        <img alt="" src="'.base_url().'user_assets/images/logo.png" style="height: 100px;width:213px;"/>
            </div>
                <div style="float:right;text-align: right;width:33%;padding-top:7px;">

                
                
                </div>
                </div>
        <div style="padding:1.5%;width:100%;float:left;background-color:#fff;border:1px solid #f1f2f3;">
                <div style="padding:0 4%;">
                    <h4 style="color:#67B1A3;text-decoration: underline;">Cashback Credited Successfully</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Cashback Credited in your Wallet. </p>
                       
<p style="color:#7e7e7e;font-size:13px;"> Rs. '.$caseback_amount.' Credited in your account. </p>
		<p style="color:#7e7e7e;font-size:13px;">Your Current Wallet Amount is Rs. '.$Current_wallet.'</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>
        </div>
        <div style="width:100%;float:left;background:#F5F5F5;padding:1.5%;color:#828282;font-size:12px;border:1px solid #dfdfdf;text-align:center;">
                <a href="'.base_url().'user_master/about_us" style="color:#515151;text-decoration:none;">About Us</a> |
                <a href="'.base_url().'user_master/contact_us" style="color:#515151;text-decoration:none;">Contact Us</a> |
                <a href="'.base_url().'user_login" style="color:#515151;text-decoration:none;">Login</a> |
                <a href="'.base_url().'user_master/collection" style="color:#515151;text-decoration:none;">Home-collection</a> |
                <a href="https://www.facebook.com/airmedpathlabs" style="color:#515151;text-decoration:none;"><i aria-hidden="true"><img src="'.base_url().'user_assets/images/face_book_icon1.jpeg" style="height:15px;width:15px;"></i></a>                  
        </div>
        <div style="width:100%;float:left;background:#333333;padding:1.5%;color:#ccc;font-size:12px;border:1px solid #323232;text-align:center;">
                Copyright @ 2016 Airmed PATH LABS. All rights reserved
        </div>
    </div>
</div>';
        $this->email->to($destail[0]['email']);
        $this->email->from('donotreply@airmed.com', 'Airmed PATH LAB');
        $this->email->subject('CashBack');
        $this->email->message($message);
        $this->email->send();
	
	// Case Back Email end

           $this->session->set_flashdata("payment_success",array("Your Test Book Successfully.Our Representative will Call You shortly"));
                redirect ("/");
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
        $this->load->helper('string');
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
       // $address = $this->input->get_post('address');
		 $mobile = $this->input->get_post('mobile');
       // $city = $this->input->get_post('city');
      //  $state = $this->input->get_post('state');
       // $pin = $this->input->get_post('pin');
        $select_tests = $this->input->get_post('test_ids');
        $total = $this->input->get_post('price');
		 $method = $this->input->get_post('method');
		 $method = str_replace(' ', '', $method);

        $order_id = random_string('numeric', 13);
        $date = date('Y-m-d H:i:s');
        if ($uid != NULL && $select_tests != Null && $total != Null) {

            $data = array(
                "order_id" => $order_id,
                "cust_fk" => $uid,
                "date" => $date,
                "price" => $total,
                "status" => '1',
                "payment_type" => "Cash On Delivery",
				"mobile"=>$mobile,
               // "address" => $address,
                //"city" => $city,
               // "state" => $state,
               // "pin" => $pin
            );

            $insert = $this->user_test_master_model->master_fun_insert("job_master", $data);
            if ($insert) {
				
				// Referral amount for first test book//
				$data = $this->user_test_master_model->master_fun_get_tbl_val("job_master", array("cust_fk" => $uid), array("id", "asc"));
				 $count = count($data); 
				if($count==1){
					
					$refdata = $this->user_test_master_model->master_fun_get_tbl_val("refer_code_master", array("status" => 1, "cust_fk" => $uid), array("id", "asc"));
					
				  $userdcode = $refdata[0]['used_code']; 
				  if($userdcode!=null)
					  {
				$refdata1 = $this->user_test_master_model->master_fun_get_tbl_val("refer_code_master", array("status" => 1, "refer_code" =>$userdcode), array("id", "asc"));
				 $usedcust_fk = $refdata1[0]['cust_fk'];
				$query = $this->user_test_master_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $usedcust_fk), array("id", "desc"));
		 $waltotal = $query[0]['total'];
			//die();
				$datacust = $this->user_test_master_model->master_fun_get_tbl_val("customer_master", array("id" =>$usedcust_fk), array("id", "asc"));
						  $refemail = $datacust[0]['email'];
						  $ref_name = $datacust[0]['full_name'];
						  
						   $data = array(
                "cust_fk" => $usedcust_fk,
                "credit" => 100,
                "total" =>$waltotal+100,
                "type"=> "referral code",
                "created_time" => date('Y-m-d H:i:s')
            );
            $insert1 = $this->user_test_master_model->master_fun_insert("wallet_master", $data);
				
				 $config['mailtype'] = 'html';
                $this->email->initialize($config);

$message='<div style="background:#a3a3a3 !important;font-family: "Roboto",sans-serif;">
<script src="https://use.fontawesome.com/44049e3dc5.js"></script>
    <div style="background: #6b6b6b none repeat scroll 0 0;float: left;padding: 7%;">
        <div style="border:1px solid #f1f1f1;border-bottom:1px solid #67B1A3;padding:1.5%;width:100%;float:left;background:#fff;">
            <div style="float:left;width:64%;">
                        <img alt="" src="'.base_url().'user_assets/images/logo.png" style="height: 100px;width:213px;"/>
            </div>
                <div style="float:right;text-align: right;width:33%;padding-top:7px;">

                
                
                </div>
                </div>
        <div style="padding:1.5%;width:100%;float:left;background-color:#fff;border:1px solid #f1f2f3;">
                <div style="padding:0 4%;">
                    <h4><b>You have one more refferal</b></h4>
                        <p>Dear, '.ucfirst($ref_name) .'</p>
                        <p style="color:#7e7e7e;font-size:13px;">Congratulation You have One more refferal  </p>
                        <p style="color:#7e7e7e;font-size:13px;">Rs.100 has been Credited in your wallet</p>
                </div>
        </div>
        <div style="width:100%;float:left;background:#F5F5F5;padding:1.5%;color:#828282;font-size:12px;border:1px solid #dfdfdf;text-align:center;">
                <a href="'.base_url().'user_master/about_us" style="color:#515151;text-decoration:none;">About Us</a> |
                <a href="'.base_url().'user_master/contact_us" style="color:#515151;text-decoration:none;">Contact Us</a> |
                <a href="'.base_url().'user_login" style="color:#515151;text-decoration:none;">Login</a> |
                <a href="'.base_url().'user_master/collection" style="color:#515151;text-decoration:none;">Home-collection</a> |
                <a href="https://www.facebook.com/airmedpathlabs" style="color:#515151;text-decoration:none;"><i aria-hidden="true"><img src="'.base_url().'user_assets/images/face_book_icon1.jpeg" style="height:15px;width:15px;"></i></a>                  
        </div>
        <div style="width:100%;float:left;background:#333333;padding:1.5%;color:#ccc;font-size:12px;border:1px solid #323232;text-align:center;">
                Copyright @ 2016 Airmed PATH LAB. All rights reserved
        </div>
    </div>
</div>';	

                $this->email->to($refemail);
                $this->email->from("donotreply@airmed.com");
                $this->email->subject("Refferal Amount Credited");
                $this->email->message($message);
                $this->email->send();
					
					
				}
			}
				
				
				
				
				//end referral for first test book
				
				$test = explode(',',$select_tests);
				$test_package_name = array();
				foreach ($test as $key) {
            $ex = explode('-', $key);
            $first_pos = $ex[0];
            $id = $ex[1];
            if ($first_pos == "t") {
                $price1 = $this->user_test_master_model->master_fun_get_tbl_val("test_master", array("status" => 1, "id" => $id), array("test_name", "asc"));
                //$price += $price1[0]['price'];
				$test_package_name[]=$price1[0]['test_name'];
            }
            if ($first_pos == "p") {
                $price1 = $this->user_test_master_model->master_fun_get_tbl_val("package_master", array("status" => 1, "id" => $id), array("title", "asc"));
               // $price += $price1[0]['d_price'];
			   $test_package_name[]=$price1[0]['title'];
            }
            //$cnt++;
        }
	//	print_r($test_package_name); die();
				
				$query = $this->user_test_master_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $uid), array("id", "desc"));
        $wallettotal = $query[0]['total'];
       $payfrom_wallet = '';
		$payemnttype = "Cash on blood Collection";
            if ($method == "yes") {
				 if ($wallettotal > 0) {
					if($wallettotal >= $total){
						
						$data1 = array(
                    "cust_fk" => $uid,
                    "debit" => $total,
                    "total" => $wallettotal - $total,
                    "job_fk" => $insert,
                    "created_time" => date('Y-m-d H:i:s')
                );
				
						$payable = 0 ;
						$payfrom_wallet ='<p style="color:#7e7e7e;font-size:13px;"> You paid From Your Wallet Rs. '.$total.'  </p>
						<p style="color:#7e7e7e;font-size:13px;"> Your Payable Amount Rs. '.$payable.'  </p> 
						';
						$payemnttype = "Paid From Wallet";
					}else{
						
						$data1 = array(
                    "cust_fk" => $uid,
                    "debit" => $wallettotal,
                    "total" => 0,
                    "job_fk" => $insert,
                    "created_time" => date('Y-m-d H:i:s')
                );
				$payable = $total-$wallettotal;
				$payfrom_wallet ='<p style="color:#7e7e7e;font-size:13px;"> You paid From Your Wallet Rs. '.$wallettotal.'  </p>
						<p style="color:#7e7e7e;font-size:13px;"> Your Payable Amount Rs. '.$payable.'  </p> 
						';
						$payemnttype = "Cash on blood Collection";
						
					}
                
                $insert12 = $this->user_test_master_model->master_fun_insert("wallet_master", $data1);
				$update = $this->service_model->master_fun_update1("job_master", array("id" => $insert),array("payable_amount"=>$payable));
            }
        }else{
			
			$update = $this->service_model->master_fun_update1("job_master", array("id" => $insert),array("payable_amount"=>$total));
		}
				
				
                $package_list = explode(",", $select_tests);
                foreach ($package_list as $key) {
                    $test = explode("-",$key);
                    if($test[0]=='t'){
                    $this->user_test_master_model->master_fun_insert("job_test_list_master", array("job_fk" => $insert, "test_fk" => $test[1]));
                    }
                    if($test[0]=='p'){
                    $this->user_test_master_model->master_fun_insert("book_package_master", array("job_fk" => $insert,"date" => $date,"order_id" => $order_id, "package_fk" => $test[1],"cust_fk" => $uid,"type"=>"2"));
                    }
                }
                
						$destail = $this->user_test_master_model->master_fun_get_tbl_val("customer_master", array("status" => 1, "id" => $uid), array("id", "asc"));

        $config['mailtype'] = 'html';
        $this->email->initialize($config);
       
$message='<div style="background:#a3a3a3 !important;font-family: "Roboto",sans-serif;">
<script src="https://use.fontawesome.com/44049e3dc5.js"></script>
    <div style="background: #6b6b6b none repeat scroll 0 0;float: left;padding: 7%;">
        <div style="border:1px solid #f1f1f1;border-bottom:1px solid #67B1A3;padding:1.5%;width:100%;float:left;background:#fff;">
            <div style="float:left;width:64%;">
                        <img alt="" src="'.base_url().'user_assets/images/logo.png" style="height: 100px;width:213px;"/>
            </div>
                <div style="float:right;text-align: right;width:33%;padding-top:7px;">

                
                
                </div>
                </div>
        <div style="padding:1.5%;width:100%;float:left;background-color:#fff;border:1px solid #f1f2f3;">
                <div style="padding:0 4%;">
                    <h4><b>Booked Successfully</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Booking successfully. </p>
                     <p style="color:#7e7e7e;font-size:13px;"> You Booked : . '.implode($test_package_name,',').'  </p>  
<p style="color:#7e7e7e;font-size:13px;"> Your Booked Amount is Rs. '.$total.'  </p>
'.$payfrom_wallet.'
        <p style="color:#7e7e7e;font-size:13px;"> Payment Type : '.$payemnttype.'</p>
		<p style="color:#7e7e7e;font-size:13px;"> Mobile : '.$destail[0]['mobile'].'</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>
        </div>
        <div style="width:100%;float:left;background:#F5F5F5;padding:1.5%;color:#828282;font-size:12px;border:1px solid #dfdfdf;text-align:center;">
                <a href="'.base_url().'user_master/about_us" style="color:#515151;text-decoration:none;">About Us</a> |
                <a href="'.base_url().'user_master/contact_us" style="color:#515151;text-decoration:none;">Contact Us</a> |
                <a href="'.base_url().'user_login" style="color:#515151;text-decoration:none;">Login</a> |
                <a href="'.base_url().'user_master/collection" style="color:#515151;text-decoration:none;">Home-collection</a> |
                <a href="https://www.facebook.com/airmedpathlabs" style="color:#515151;text-decoration:none;"><i aria-hidden="true"><img src="'.base_url().'user_assets/images/face_book_icon1.jpeg" style="height:15px;width:15px;"></i></a>                  
        </div>
        <div style="width:100%;float:left;background:#333333;padding:1.5%;color:#ccc;font-size:12px;border:1px solid #323232;text-align:center;">
                Copyright @ 2016 Airmed PATH LAB. All rights reserved
        </div>
    </div>
</div>';
        $this->email->to($destail[0]['email']);
		$this->email->cc("booking.airmed@gmail.com");
        $this->email->from('donotreply@airmedpathlabs.com', 'Airmed PATH LAB');
        $this->email->subject('Test Book Successfully');
        $this->email->message($message);
        $this->email->send();
				
				
                $this->session->set_flashdata("payment_success",array("Cash on delivery request successfully received."));
                redirect ("/");
            }
        } else {
            echo "<script>alert('Oops somthing wrong Try again!');</script>";
            redirect ("/");
        }
    }
	
	function price_cal(){
		
		$test = $this->input->post('id');
		$test1 = explode(",", $test);
		foreach($test1 as $key){
		$ex = explode('-',$key);
	 $first_pos = $ex[0];
	 $id = $ex[1];
		if($first_pos=="t"){
		$price1 = $this->user_test_master_model->master_fun_get_tbl_val("test_master", array("status" => 1,"id"=>$id),array("test_name","asc"));
$price += $price1[0]['price'];		
		}
		if($first_pos=="p"){
			$price1 = $this->user_test_master_model->master_fun_get_tbl_val("package_master", array("status" => 1,"id"=>$id),array("title","asc"));
			$price += $price1[0]['d_price'];
		}
		
			//echo $price1[0]['price'];die();
			
		$cnt++;
		}
		echo $price;
	}

}

?>
