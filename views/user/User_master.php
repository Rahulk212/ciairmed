<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class User_master extends CI_Controller {
    function __construct() {
        parent::__construct();
       $this->load->model('home_model');
	    $this->load->model('user_master_model');
		$this->load->model('user_wallet_model');
		$this->load->helper('string');
		 $this->load->library('email');
	   $data["login_data"] = loginuser();
       $uid = $data["login_data"]['id'];
	   if($uid!=0){
		   $maxid = $this->user_wallet_model->total_wallet($uid);
	$data['total'] = $this->user_wallet_model->master_fun_get_tbl_val("wallet_master", array("status" => 1,"id"=>$maxid),array("id","asc"));
	$this->data['wallet_amount']=$data['total'][0]['total'];
		   
	   }
	
    }
	function index(){
		//echo "here"; die();
		
	if($this->session->flashdata("error")) {
		$data["error"] = $this->session->flashdata("error");
		}
		$data["login_data"] = loginuser(); 
	//	print_r($data["login_data"]); die();
	$data['package'] = $this->user_master_model->master_fun_get_tbl_val("package_master", array("status" => 1),array("title","asc"));
	$data['health_feed'] = $this->user_master_model->master_fun_get_tbl_val("health_feed_master", array("status" => 1),array("title","asc"));
	$data['test'] = $this->user_master_model->master_fun_get_tbl_val("test_master", array("status" => 1),array("test_name","asc"));
	$data['set_setting'] = $this->user_master_model->master_fun_get_tbl_val("patholab_home_master", array("status" => 1),array("id","asc"));
	$data['testimonial'] = $this->user_master_model->master_fun_get_tbl_val("testimonials_master", array("status" => 1),array("id","asc"));
	$data['active_class'] = "home";
		$this->load->view('user/header',$data);
		//$this->load->view('nav',$data);
		$this->load->view('user/home',$data);
		$this->load->view('user/footer');
	}
	function searching_test(){
		$ids = $this->input->post('id'); 
		$names = $this->input->post('name'); 
		//print_r($names);
		//die();
		$this->session->set_userdata('search_test_id', $ids);
		$this->session->set_userdata('search_test_name', $names);
		
	}
	function order_search(){
		
		$data["test_ids"] = $this->session->userdata("search_test_id");
		
		$data["test_names"] = $this->session->userdata("search_test_name");
		$price = 0;
	//	print_r($data["test_ids"]);
		$cnt = 0;
		foreach($data["test_ids"] as $key){
		$ex = explode('-',$key);
	 $first_pos = $ex[0];
	 $id = $ex[1];
		if($first_pos=="t"){
		$price1 = $this->user_master_model->master_fun_get_tbl_val("test_master", array("status" => 1,"id"=>$id),array("test_name","asc"));
$price += $price1[0]['price'];		
		}
		if($first_pos=="p"){
			$price1 = $this->user_master_model->master_fun_get_tbl_val("package_master", array("status" => 1,"id"=>$id),array("title","asc"));
			$price += $price1[0]['d_price'];
		}
		
			//echo $price1[0]['price'];die();
			
		$cnt++;
		}
		//echo $price;
		//die();
$data['package'] = $this->user_master_model->master_fun_get_tbl_val("package_master", array("status" => 1),array("title","asc"));
		$data['total_price'] = $price; 
		$this->load->view('user/header',$data);
		//$this->load->view('nav',$data);
		$this->load->view('user/search_after',$data);
		$this->load->view('user/footer');
	}
	
	function send_serch_test_email(){
		
		$name = $this->input->post('name'); 
		$email = $this->input->post('email');
		$mobile = $this->input->post('mobile'); 
		$age = $this->input->post('age'); 
		$add = $this->input->post('address'); 
$test = $this->input->post('testname'); 		
		
		$config['mailtype'] = 'html';
      
	  $this->email->initialize($config);
		
		$message='<body style="background:#f1f2f3;padding:2%;width:100%;">
    <div style="background:#fff;width:80%;margin-left:10%;border:1px solid #ccc">
        <div style="background: lightsteelblue none repeat scroll 0 0;border-bottom: 1px solid #ddd;padding: 20px;text-align: center;">
            <h1><img src="'.base_url().'user_assets/images/logo.png" style="width: 55%"/></h1>
        </div>
        <div style="padding:2%;color:#232552">
            <p>Hello </p> </br>
			<p> Customer Name: '.$name.' </p> </br>
			<p>  Email : '.$email.' </p> </br>
			<p> Mobile: '.$mobile.' </p> </br>
			<p> Age: '.$age.' </p> </br>
			<p> Address: '.$add.' </p> </br>
			<p> Requested for : '.$test.' </p> </br>
			
            
        </div>
       <div style=" background:#f3f3dd;border-top: 1px solid #ccc;color: #7e7e7e;font-size: 12px;padding: 7px;text-align: center;">
            report@airmedpathlabs.com<br>
            
        </div>
    </div>
</body>';
		
        $this->email->to("nimesh@virtualheight.com,hiten@virtualheight.com");
        $this->email->from($email,$name);
        $this->email->subject("Requested for Test");
        $this->email->message($message);
        $this->email->send();
		
		
	}
	function home_upload_prescription(){
		
		$name = $this->input->post('name');
		$mobile = $this->input->post('mobile');
		$email = $this->input->post('email');
		$desc = $this->input->post('desc');
		
		$files = $_FILES;
	$data = array();
	$this->load->library('upload');
	$config['allowed_types'] = 'png|jpg|gif|jpeg';
	if ($files['userfile']['name'] != "") {
		$config['upload_path'] = './upload/';
		$config['file_name'] = $files['userfile']['name'];
		$this->upload->initialize($config);
		if (!is_dir($config['upload_path'])){
			mkdir($config['upload_path'], 0755, TRUE);
		}
		if (!$this->upload->do_upload('userfile')) {
			$error = $this->upload->display_errors();
			$error = str_replace("<p>","",$error);
			 $error = str_replace("</p>","",$error); 
				
		}else {
			$doc_data = $this->upload->data();
			$filename = $doc_data['file_name'];
			$config['mailtype'] = 'html';
      
	  $this->email->initialize($config);
		
		$message='<body style="background:#f1f2f3;padding:2%;width:100%;">
    <div style="background:#fff;width:80%;margin-left:10%;border:1px solid #ccc">
        <div style="background: lightsteelblue none repeat scroll 0 0;border-bottom: 1px solid #ddd;padding: 20px;text-align: center;">
  <h1><img src="'.base_url().'user_assets/images/logo.png" style="width: 55%"/></h1>
        </div>
        <div style="padding:2%;color:#232552">
            <p>Hello </p> </br>
			<p> Customer Name: '.$name.' </p> </br>
			<p>  Email : '.$email.' </p> </br>
			<p> Mobile: '.$mobile.' </p> </br>
			
			<p> Description: '.$desc.' </p> </br>
			<p> Uploaded file : '.base_url().'upload/'.$filename.' </p> </br>
			
            
        </div>
       <div style=" background:#f3f3dd;border-top: 1px solid #ccc;color: #7e7e7e;font-size: 12px;padding: 7px;text-align: center;">
            report@airmedpathlabs.com<br>
            
        </div>
    </div>
</body>';
		
        $this->email->to("nimesh@virtualheight.com,hiten@virtualheight.com");
        $this->email->from($email,$name);
        $this->email->subject("Prescription");
        $this->email->message($message);
        $this->email->send();
			
			redirect("user_master", "refresh");
		}
		
	}
	
	}
	
	function package_details($id){
		//echo "here"; die();
		
	if($this->session->flashdata("error")) {
		$data["error"] = $this->session->flashdata("error");
		}
		$data["login_data"] = loginuser(); 
	//	print_r($data["login_data"]); die();
	$data['package'] = $this->user_master_model->master_fun_get_tbl_val("package_master", array("status" => 1,'id'=>$id),array("title","asc"));
	$data['active_class'] = "home";
		$this->load->view('user/header',$data);
		//$this->load->view('nav',$data);
		$this->load->view('user/package_details',$data);
		$this->load->view('user/footer');
	}
	
	function edit_profile() {
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
        $data["user"] = $this->user_master_model->getUser($data["login_data"]["id"]);
	//	print_r($data["user"]);die();
	$countryid =  $data["user"]->country;
	 $stateid =  $data["user"]->state; 
		$id = $data["login_data"]["id"];
		 $data['type'] = $data["login_data"]["type"];
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
		 $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		  $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|min_length[10]|max_length[13]');
		   $this->form_validation->set_rules('country', 'Country', 'trim|required');
		    $this->form_validation->set_rules('state', 'state', 'trim|required');
		    $this->form_validation->set_rules('city', 'city', 'trim|required');
		
        if ($this->form_validation->run() != FALSE) {
            $name = $this->input->post('name');
		$email = $this->input->post('email');
		$mobile = $this->input->post('mobile');
		$address = $this->input->post('address');
		$gender = $this->input->post('gender');
		$country = $this->input->post('country');
		$state = $this->input->post('state');
		$city = $this->input->post('city');
		//print_r($this->input->post()); die();
		$data1 = array(
			"full_name"=>$name,
			"email"=>$email,
			"mobile"=>$mobile,
			"address"=>$address,
			"gender"=>$gender,
			"country"=>$country,
			"state"=>$state,
			"city"=>$city,	
			);
			$files = $_FILES;
	$data = array();
	$this->load->library('upload');
	$config['allowed_types'] = 'png|jpg|gif|jpeg';
	//$config['encrypt_name'] = true;
	//$config['max_size'] = '2000'; // 2MB
	//$config['max_width'] = '3000'; // 3000px
//	$config['max_height'] = '3000'; // 3000px
//echo $files['userfile']['name'];
	if (isset($files['userfile']) && $files['userfile']['name'] != "") {
		$config['upload_path'] = './upload/';
		$config['file_name'] = $files['userfile']['name'];
		$this->upload->initialize($config);
		if (!is_dir($config['upload_path'])){
			mkdir($config['upload_path'], 0755, TRUE);
		}
		if (!$this->upload->do_upload('userfile')) {
			$error = $this->upload->display_errors();
			$error = str_replace("<p>","",$error);
			 $error = str_replace("</p>","",$error); 
				$this->session->set_flashdata("error", array($error));
            redirect("user_master/edit_profile", "refresh");
				
				
		}else {
			$doc_data = $this->upload->data();
			$filename = $doc_data['file_name'];
			$data1['pic']=$filename;
		}
	}
		//	print_r($data1); die();
			$update = $this->user_master_model->master_fun_update("customer_master",array("id",$id),$data1);
			
            $this->session->set_flashdata("success", array("Profile Updated Successfully."));
            redirect("user_master/edit_profile", "refresh");
        } else {
           // $data['query'] = $this->test_model->master_fun_get_tbl_val("test_master", array("id" => $data["cid"]), array("id", "desc"));
$data['country'] = $this->user_master_model->master_fun_get_tbl_val("country", array("status" => 1),array("country_name","asc"));

$data['state'] = $this->user_master_model->master_fun_get_tbl_val("state", array("country_fk"=>$countryid,"status" => 1),array("state_name","asc"));
$data['city'] = $this->user_master_model->master_fun_get_tbl_val("city", array("state_fk"=>$stateid,"status" => 1),array("city_name","asc"));
$data['country'] = $this->user_master_model->master_fun_get_tbl_val("country", array("status" => 1),array("country_name","asc"));
		   $data['success']=$this->session->flashdata("success");
  $data['error']=$this->session->flashdata("error");
            $this->load->view('user/header',$data);
            
            $this->load->view('user/edit_profile', $data);
            $this->load->view('user/footer');
        }
    }
	
	function get_state($cid){
		
			$data = $this->user_master_model->master_fun_get_tbl_val("state", array("country_fk"=>$cid,"status" => 1),array("state_name","asc"));
		//print_r($data);
		
		echo "<select name='state' class='input-group select_style' onchange='get_city(this.value)'>";
		echo "<option value=''> Select State</option>";
		foreach($data as $key){
		 $state = ucfirst($key['state_name']);
		  $value= $key['id'];
		echo "<option value='$value'> $state </option>";
			
		}
		echo "</select>";
		
		
	}
	function get_city($cid){
		
			$data = $this->user_master_model->master_fun_get_tbl_val("city", array("state_fk"=>$cid,"status" => 1),array("city_name","asc"));
		//print_r($data);
		
		echo "<select name='city' class='input-group select_style'>";
		echo "<option value=''> Select city</option>";
		foreach($data as $key){
		 $state = ucfirst($key['city_name']);
		 $value= $key['id'];
		echo "<option value='$value'> $state </option>";
			
		}
		echo "</select>";

	}
	
	function change_password() {
      
		if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
       $uid = $data["login_data"]['id'];
	
	  $this->load->library('form_validation');
		$this->form_validation->set_rules('password', 'Old Password', 'trim|required');
		$this->form_validation->set_rules('newpassword', 'New password', 'trim|required|matches[cpassword]');
		$this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required');
		
        if ($this->form_validation->run() != FALSE) {
           
		$password = $this->input->post('newpassword');
		$oldpassword = $this->input->post('password');
		
	     $data = $this->user_master_model->master_fun_get_tbl_val("customer_master", array("id"=>$uid,"status" => 1),array("id","asc"));
		$oldpass = $data[0]['password'];
		if($oldpass==$oldpassword){
			$data1 = array("password"=>$password);
		$update = $this->user_master_model->master_fun_update("customer_master",array("id",$uid),$data1);
        if($update){
			$this->session->set_flashdata("success", array("Password Changed Successfully"));
			redirect("user_master/change_password", "refresh");
				}
		}else{
			
		$this->session->set_flashdata("error", array("Old Password Not Matach"));
        redirect("user_master/change_password", "refresh");	
			
		}
		
        } else {
			$data['success'] = $this->session->flashdata("success");
			$data['error'] = $this->session->flashdata("error");
            $this->load->view('user/header',$data);
           
            $this->load->view('user/change_password',$data);
            $this->load->view('user/footer');
        }
		
    }
	
	function my_job(){
		//echo "here"; die();
		if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
       $uid = $data["login_data"]['id'];
	if($this->session->flashdata("error")) {
		$data["error"] = $this->session->flashdata("error");
		}
		$data["success"] = $this->session->flashdata("success");
		  $data['completed'] = $this->user_master_model->completed_job($uid);
		 $data['active_class'] = "myjob";
		  $data['pending'] = $this->user_master_model->pending_job($uid);
		 //  print_r($data['pending']); die();
		  $data['cancle'] = $this->user_master_model->cancle_job($uid);
		$this->load->view('user/header',$data);
		//$this->load->view('nav',$data);
		$this->load->view('user/my_job',$data);
		$this->load->view('user/footer');
	}
	function cancle_job($job_fk){
		if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
       $uid = $data["login_data"]['id'];
		$date = date('d/m/Y');
			$update = $this->user_master_model->master_fun_update("job_master",array("id",$job_fk),array("status"=>4));	
			
	if($update){
		$query = $this->user_master_model->master_fun_get_tbl_val("wallet_master", array("status" =>1,"cust_fk"=>$uid), array("id", "desc"));
		$total = $query[0]['total'];
		$query1 = $this->user_master_model->master_fun_get_tbl_val("job_master", array("id"=>$job_fk), array("id", "desc"));
		//print_r($query1); die();
		$amount = $query1[0]['price'];
		
		$data = array(
						 "cust_fk"=>$uid,
						 "credit"=>$amount,
						 "total"=>$total+$amount,
						 "job_fk"=>$job_fk,
						 "created_time"=>date('Y-m-d H:i:s')
						 );
				$insert = $this->user_master_model->master_fun_insert("wallet_master",$data);
		//echo $this->json_data("1","",array(array("msg"=>"Money Credited Successfully")));
	
	//echo $this->json_data("1","",array(array("id"=>$insert)));
		$this->session->set_flashdata("success", array("Job Request Cancled Suucessfully..Your Payment has been Credited In your Wallet"));
        redirect("user_master/my_job", "refresh");	
	}
	
	}
	
	function upload_prescription() {
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
         $uid = $data["login_data"]['id'];
        $this->load->library('form_validation');
        $this->form_validation->set_rules('desc', 'Description', 'trim|required');
		       // $this->form_validation->set_rules('price', 'Price', 'trim|required|numeric');
		
        if ($this->form_validation->run() != FALSE) {
            $desc = $this->input->post('desc');
		$order_id =  random_string('numeric',13);
		$date = date('Y-m-d H:i:s');
		
			$data1 = array("cust_fk" => $uid,"description"=>$desc,"created_date"=>$date,"order_id"=>$order_id);
			$files = $_FILES;
	$data = array();
	$this->load->library('upload');
	$config['allowed_types'] = 'png|jpg|gif|jpeg';
	//$config['encrypt_name'] = true;
	//$config['max_size'] = '2000'; // 2MB
	//$config['max_width'] = '3000'; // 3000px
//	$config['max_height'] = '3000'; // 3000px
//echo $files['userfile']['name'];
	if (isset($files['userfile']) && $files['userfile']['name'] != "") {
		$config['upload_path'] = './upload/';
		$config['file_name'] = $files['userfile']['name'];
		$this->upload->initialize($config);
		if (!is_dir($config['upload_path'])){
			mkdir($config['upload_path'], 0755, TRUE);
		}
		if (!$this->upload->do_upload('userfile')) {
			$error = $this->upload->display_errors();
			$error = str_replace("<p>","",$error);
			 $error = str_replace("</p>","",$error); 
				$this->session->set_flashdata("error", array($error));
redirect("user_master/upload_prescription", "refresh");
				
				
		}else {
			$doc_data = $this->upload->data();
			$filename = $doc_data['file_name'];
			$data1['image']=$filename;
		}
	}else{
		 $this->session->set_flashdata("error", array("Please Choose any Image"));
            redirect("user_master/upload_prescription", "refresh");
		
	}
		
	$insert = $this->user_master_model->master_fun_insert("prescription_upload",$data1);				
           
			
			
            $this->session->set_flashdata("success", array("Thank You ! we will analysis and create Test list for you"));
            redirect("user_master/upload_prescription", "refresh");
        } else {
           // $data['query'] = $this->test_model->master_fun_get_tbl_val("test_master", array("id" => $data["cid"]), array("id", "desc"));
		   $data['success']=$this->session->flashdata("success");
  $data['error']=$this->session->flashdata("error");
$data['active_class'] = "upload_prescription";
			$data['prescription'] = $this->user_master_model->master_fun_get_tbl_val("prescription_upload", array("status" =>1,"cust_fk"=>$uid), array("id", "desc"));
		  $this->load->view('user/header',$data);
            
            $this->load->view('user/prescription_upload', $data);
            $this->load->view('user/footer');
        }
    }
	
	function suggested_test($pid){
		//echo "here"; die();
		if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
       $uid = $data["login_data"]['id'];
	if($this->session->flashdata("error")) {
		$data["error"] = $this->session->flashdata("error");
		}
		$data["success"] = $this->session->flashdata("success");
		$data['prescription'] = $this->user_master_model->master_fun_get_tbl_val("prescription_upload", array("status" =>1,"id"=>$pid), array("id", "desc"));
		  $data['test'] = $this->user_master_model->suggest_test($pid);
		//print_r($data['test']);
		$this->load->view('user/header',$data);
		
		//$this->load->view('nav',$data);
		$this->load->view('user/suggested_test',$data);
		
		$this->load->view('user/footer');
		 //print_r($data['test']);die();
	}
	
	
	function submit_issue() {
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
         $uid = $data["login_data"]['id'];
        $this->load->library('form_validation');
        $this->form_validation->set_rules('subject', 'subject', 'trim|required');
		   $this->form_validation->set_rules('message', 'Message', 'trim|required');
		
        if ($this->form_validation->run() != FALSE) {
			$subject = $this->input->post('subject');
			$message1 = $this->input->post('message');
            $data = $this->user_master_model->master_fun_get_tbl_val("customer_master", array("status" => 1,'id'=>$uid),array("id","asc"));
			$email = $data[0]['email'];
			$name = $data[0]['full_name'];
		$data1 = array("cust_fk"=>$uid,"subject"=>$subject,"massage"=>$message1,"created_date"=>date('d/m/Y'));
		$insert = $this->user_master_model->master_fun_insert("issue_master",$data1);
		$config['mailtype'] = 'html';
      
	  $this->email->initialize($config);
		
		$message='<body style="background:#f1f2f3;padding:2%;width:100%;">
    <div style="background:#fff;width:80%;margin-left:10%;border:1px solid #ccc">
        <div style="background: lightsteelblue none repeat scroll 0 0;border-bottom: 1px solid #ddd;padding: 20px;text-align: center;">
            <h1>LOGO HERE</h1>
        </div>
        <div style="padding:2%;color:#232552">
            <p>Hello '.$message1 .' </p>
            
        </div>
       <div style=" background:#f3f3dd;border-top: 1px solid #ccc;color: #7e7e7e;font-size: 12px;padding: 7px;text-align: center;">
            hello@pathology.com<br>
            
        </div>
    </div>
</body>';
		
        $this->email->to("nimesh@virtualheight.com");
        $this->email->from($email,$name);
        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->send();
		
            $this->session->set_flashdata("success", array("Thank You ! Your Query Submited Successfully"));
            redirect("user_master/submit_issue", "refresh");
        } else {
           // $data['query'] = $this->test_model->master_fun_get_tbl_val("test_master", array("id" => $data["cid"]), array("id", "desc"));
		   $data['success']=$this->session->flashdata("success");
  $data['error']=$this->session->flashdata("error");

			
		  $this->load->view('user/header',$data);
            
            $this->load->view('user/submit_issue', $data);
            $this->load->view('user/footer');
        }
    }
	
	function view_report($job_fk){
		//echo "here"; die();
		if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
       $uid = $data["login_data"]['id'];
	if($this->session->flashdata("error")) {
		$data["error"] = $this->session->flashdata("error");
		}
		$data["success"] = $this->session->flashdata("success");
		$data['report'] = $this->user_master_model->master_fun_get_tbl_val("report_master", array("job_fk"=>$job_fk,"status" => 1),array("id","asc"));
		  $data['job'] = $this->user_master_model->detail_job($uid,$job_fk);
		//print_r($data['test']);
		$this->load->view('user/header',$data);
		
		//$this->load->view('nav',$data);
		$this->load->view('user/view_report',$data);
		
		$this->load->view('user/footer');
		 //print_r($data['test']);die();
	}
	
	function payment_history(){
		//echo "here"; die();
		if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
       $uid = $data["login_data"]['id'];
	if($this->session->flashdata("error")) {
		$data["error"] = $this->session->flashdata("error");
		}
		$data["success"] = $this->session->flashdata("success");
		  $data['history'] = $this->user_master_model->payment_history($uid);
		  $data['active_class'] = "payment_history";
		$this->load->view('user/header',$data);
		//$this->load->view('nav',$data);
		$this->load->view('user/payment_history',$data);
		$this->load->view('user/footer');
	}
	function faq(){
		//echo "here"; die();
		
        $data["login_data"] = loginuser();
       $uid = $data["login_data"]['id'];
	if($this->session->flashdata("error")) {
		$data["error"] = $this->session->flashdata("error");
		}
		
		$this->load->view('user/header',$data);
		//$this->load->view('nav',$data);
		$this->load->view('user/faq',$data);
		$this->load->view('user/footer');
	}
	function terms_condition(){
		//echo "here"; die();
		
        $data["login_data"] = loginuser();
       $uid = $data["login_data"]['id'];
	if($this->session->flashdata("error")) {
		$data["error"] = $this->session->flashdata("error");
		}
		
		$this->load->view('user/header',$data);
		//$this->load->view('nav',$data);
		$this->load->view('user/terms_condition',$data);
		$this->load->view('user/footer');
	}
	function privacy_policy(){
		//echo "here"; die();
		
        $data["login_data"] = loginuser();
       $uid = $data["login_data"]['id'];
	if($this->session->flashdata("error")) {
		$data["error"] = $this->session->flashdata("error");
		}
		
		$this->load->view('user/header',$data);
		//$this->load->view('nav',$data);
		$this->load->view('user/privacy_policy',$data);
		$this->load->view('user/footer');
	}
	function collection(){
		//echo "here"; die();
		
        $data["login_data"] = loginuser();
       $uid = $data["login_data"]['id'];
	if($this->session->flashdata("error")) {
		$data["error"] = $this->session->flashdata("error");
		}
		
		$this->load->view('user/header',$data);
		//$this->load->view('nav',$data);
		$this->load->view('user/collection',$data);
		$this->load->view('user/footer');
	}
	function contact_us(){
		//echo "here"; die();
		
        $data["login_data"] = loginuser();
       $uid = $data["login_data"]['id'];
	if($this->session->flashdata("error")) {
		$data["error"] = $this->session->flashdata("error");
		}
		
		$this->load->view('user/header',$data);
		//$this->load->view('nav',$data);
		$this->load->view('user/contact_us',$data);
		$this->load->view('user/footer');
	}
	
	function health_feed(){
		//echo "here"; die();
		
        $data["login_data"] = loginuser();
       $uid = $data["login_data"]['id'];
	if($this->session->flashdata("error")) {
		$data["error"] = $this->session->flashdata("error");
		}
		$data['health_feed'] = $this->user_master_model->master_fun_get_tbl_val("health_feed_master", array("status" => 1),array("id","asc"));
		$data['package'] = $this->user_master_model->master_fun_get_tbl_val("package_master", array("status" => 1),array("title","asc"));
		$this->load->view('user/header',$data);
		//$this->load->view('nav',$data);
		$this->load->view('user/health_feed',$data);
		$this->load->view('user/footer');
	}
	function health_feed_details($id){
		//echo "here"; die();
		
	if($this->session->flashdata("error")) {
		$data["error"] = $this->session->flashdata("error");
		}
		$data["login_data"] = loginuser(); 
	//	print_r($data["login_data"]); die();
		$data['health_feed'] = $this->user_master_model->master_fun_get_tbl_val("health_feed_master", array("status" => 1,'id'=>$id),array("id","asc"));
		$data['package'] = $this->user_master_model->master_fun_get_tbl_val("package_master", array("status" => 1),array("title","asc"));
	$data['active_class'] = "home";
		$this->load->view('user/header',$data);
		//$this->load->view('nav',$data);
		$this->load->view('user/inner_health_feed',$data);
		$this->load->view('user/footer');
	}
	
	function support_system(){
		//echo "here"; die();
		if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
       $uid = $data["login_data"]['id'];
	if($this->session->flashdata("error")) {
		$data["error"] = $this->session->flashdata("error");
		}
		$data["success"] = $this->session->flashdata("success");
		$data['ticket'] = $this->user_master_model->master_fun_get_tbl_val("ticket_master", array("user_id"=>$uid,"status" => 1),array("id","desc"));
		$this->load->view('user/header',$data);
		//$this->load->view('nav',$data);
		$this->load->view('user/support_system',$data);
		$this->load->view('user/footer');
	}
	function add_ticket() {
		if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
       $uid = $data["login_data"]['id'];
	
	  $this->load->library('form_validation');
		$this->form_validation->set_rules('title', 'Subject', 'trim|required');
		$this->form_validation->set_rules('message', 'Message', 'trim|required');
		$data["success"] = $this->session->flashdata("success");
        if ($this->form_validation->run() != FALSE) {
           
		$message = $this->input->post('message');
		$title = $this->input->post('title');
		$this->load->helper('string');

	$ticket= random_string('alnum',10);
	     
			$data1 = array(
			"ticket"=>$ticket,
			"user_id"=>$uid,
			"title"=>$title,
			);
			
		$insert = $this->user_master_model->master_fun_insert("ticket_master",$data1);
        
		$data2 = array(
		"ticket_fk"=>$insert,
		"message"=>$message,
		"type"=>1,
		"created_date"=>date('Y-m-d h:i:s')
		);
		$insert1 = $this->user_master_model->master_fun_insert("message_master",$data2);
		if($insert1){
			
			$this->session->set_flashdata("success", array("Ticket Created Successfully"));
			redirect("user_master/support_system", "refresh");
			
		}
        } else {
			$data['success'] = $this->session->flashdata("success");
			$data['error'] = $this->session->flashdata("error");
            $this->load->view('user/header',$data);
           
            $this->load->view('user/create_ticket',$data);
            $this->load->view('user/footer');
        }
		
    }
	function view_ticket_details() {
		if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
       $uid = $data["login_data"]['id'];
	
	  $this->load->library('form_validation');
		
		$this->form_validation->set_rules('message', 'Message', 'trim|required');
		$ticketfk = $this->uri->segment(3);
		$data['ticket'] = $ticketfk; 
		$data['allmassage'] = $this->user_master_model->ticket_details($ticketfk);
		$data["success"] = $this->session->flashdata("success");
        if ($this->form_validation->run() != FALSE) {
           
		$message = $this->input->post('message');
		
		$tc = $this->user_master_model->master_fun_get_tbl_val("ticket_master", array("ticket"=>$ticketfk,"status" => 1),array("id","desc"));
		
		 $ticket = $tc[0]['id']; 
		 
		$this->load->helper('string');

		$data2 = array(
		"ticket_fk"=>$ticket,
		"message"=>$message,
		"type"=>1,
		"created_date"=>date('Y-m-d h:i:s')
		
		);
		$insert1 = $this->user_master_model->master_fun_insert("message_master",$data2);
		if($insert1){
			
			$this->session->set_flashdata("success", array("Message Send Successfully"));
			redirect("user_master/view_ticket_details/".$ticketfk, "refresh");
			
		}
        } else {
			$data['success'] = $this->session->flashdata("success");
			$data['error'] = $this->session->flashdata("error");
            $this->load->view('user/header',$data);
           
            $this->load->view('user/show_message',$data);
            $this->load->view('user/footer');
        }
		
    }
	
	function delete_ticket(){
		if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
       $uid = $data["login_data"]['id'];
		$cid = $this->uri->segment('3');
            $data = array(
                "status" => '0'
            );
            //$delete=$this->admin_model->delete($cid,$data);
            $delete = $this->user_master_model->master_fun_update("ticket_master",array("id",$this->uri->segment('3')), $data);
            if ($delete) {
                $this->session->set_flashdata("success", array("Ticket Deleted Successfully"));
			redirect("user_master/support_system", "refresh");
            }
	}
	function pdf_report(){
		
 $data["login_data"] = loginuser();
       $uid = $data["login_data"]['id'];
		date_default_timezone_set("UTC");
        $new_time = date("Y-m-d H:i:s", strtotime('+3 hours'));
// As PDF creation takes a bit of memory, we're saving the created file in /downloads/reports/
			$filename = "payment_history_".time().'.pdf';
	   $pdfFilePath = FCPATH . "/download/$filename";
        $data['page_title'] = 'Powers for Investments Co.'; // pass data to the view
      //  ini_set('memory_limit', '32M'); // boost the memory limit if it's low <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="😉" draggable="false" class="emoji">
     
		  $data['history'] = $this->user_master_model->payment_history($uid);
	 $html = $this->load->view('user/payment_pdf', $data, true); // render the view into HTML
       
	   //die();
	   $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->autoScriptToLang = true;
        $pdf->baseScript = 1; // Use values in classes/ucdn.php  1 = LATIN
        $pdf->autoVietnamese = true;
        $pdf->autoArabic = true;
        $pdf->autoLangToFont = true;
        $pdf->WriteHTML($html);
        //$pdf->debug = true
        $pdf->SetFooter('www.' . $_SERVER['HTTP_HOST'] . '||' . $new_time); // Add a footer for good measure <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="😉" draggable="false" class="emoji">
        // $pdf->WriteHTML($html); // write the HTML into the PDF
        $pdf->Output($pdfFilePath, 'F'); // save to file because we can
        redirect("/download/$filename");
	}

	
}
?>