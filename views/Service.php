<?php class Service extends CI_Controller {
    public function __construct() {
        parent::__construct();
	$this->load->helper('form');
	$this->load->helper('url');
	 $this->load->model('service_model');
	$this->load->helper('security');
	$this->load->helper('string');
	header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST');
        header("Access-Control-Allow-Headers: X-Requested-With");
    }
    public function index() {}
	function check_refer_code(){	
		$usercode = $this->input->get_post('refer_code');
		if($usercode != ""){	
			$row = $this->service_model->master_num_rows("refer_code_master", array("refer_code"=>$usercode,"status" => 1));	
			if($row == 1){	
				echo $this->json_data("1","",array(array("msg"=>"Your Code has been Matched")));
            }else{
				echo $this->json_data("0","Invalid Refere Code","");
			}
		}else{	
			echo $this->json_data("0","Parameter not passed","");	
		}
	}
	public function register(){
		$email = $this->input->get_post('email');
		$name = $this->input->get_post('name');
		$password = $this->input->get_post('password');
		$mobile = $this->input->get_post('mobile');
		$gender= $this->input->get_post('gender');
		$usedcode= $this->input->get_post('refer_code');
	   $code =  random_string('alnum', 6);
if ($mobile != NULL && $email != null && $password != null && $gender != null) {
		$row = $this->service_model->master_num_rows("customer_master", array("email"=>$email,"status" => 1));
		if($row == 0){
				$insert = $this->service_model->master_fun_insert("customer_master", array("full_name" => $name,"gender"=>$gender,"email"=>$email,"password"=>$password,"mobile"=>$mobile));	
					$insert_code = $this->service_model->master_fun_insert("refer_code_master", array("cust_fk" => $insert,"refer_code"=>$code,"used_code"=>$usedcode));
				echo $this->json_data("1","",array(array("id"=>$insert)));
            }else{
				echo $this->json_data("0","Email Allready Registered","");
			}
		}else {
				echo $this->json_data("0","Parameter not passed","");
            }
	}
	function login(){
		$email = $this->input->get_post('email');
		$password = $this->input->get_post('password');
		//$device_id = $this->input->get_post('device_id');
		if ($email != NULL && $password != NULL) {
			 $row = $this->service_model->master_num_rows("customer_master", array("email"=>$email,"password"=>$password,"status" => 1,"active"=>1));
			$data = $this->service_model->master_fun_get_tbl_val("customer_master", array("email"=>$email,"password"=>$password,"status" => 1),array("id","asc"));
			if($row == 1){
				//$update = $this->service_model->master_fun_update("customer_master",$data[0]['id'],array("device_id"=>$device_id));
				$data = $this->service_model->master_fun_get_tbl_val("customer_master", array("email"=>$email,"password"=>$password,"status" => 1),array("id","asc"));
				echo $this->json_data("1","",$data);
            }else{
				echo $this->json_data("0","Mobile Number or Password is Wrong","");
			}
		}else {
				echo $this->json_data("0","Parameter not passed","");
            }
	}
	function gcm(){
		$user_id = $this->input->get_post('user_id');
		$device_id = $this->input->get_post('device_id');
		if ($user_id != NULL && $device_id != NULL) {
			
				$update = $this->service_model->master_fun_update("customer_master",$user_id,array("device_id"=>$device_id));
				//$data = $this->service_model->master_fun_get_tbl_val("customer_master", array("email"=>$email,"password"=>$password,"status" => 1),array("id","asc"));
				echo $this->json_data("1","",array(array("msg"=>"Your Device Id Saved")));
            
		}else {
				echo $this->json_data("0","Parameter not passed","");
            }
	}
	function logout(){
		$user_id = $this->input->get_post('user_id');
		//$device_id = $this->input->get_post('device_id');
		if ($user_id != NULL) {
			
				$update = $this->service_model->master_fun_update("customer_master",$user_id,array("device_id"=>""));
				//$data = $this->service_model->master_fun_get_tbl_val("customer_master", array("email"=>$email,"password"=>$password,"status" => 1),array("id","asc"));
				echo $this->json_data("1","",array(array("msg"=>"Logout Successfully")));
            
		}else {
				echo $this->json_data("0","Parameter not passed","");
            }
	}
	function forget_password(){
		 $this->load->library('email');
		$email= $this->input->get_post('email');
		if ($email != NULL) {
			  $row = $this->service_model->master_fun_get_tbl_val("customer_master", array("email"=>$email,"status" => 1,"active"=>1),array("id","asc"));
		 $password = $row[0]['password']; 
	
	  $config['mailtype'] = 'html';
      $this->email->initialize($config);
$message = "You recently requested to reset your password for your  Patholab Account.<br/><br/>";
$message .= "Your Password id <strong>". $password ."</strong><br/><br/> ";
$message .= "If you did not request a password reset, please ignore this email or reply to let us know.<br/><br/>";
$message .= "Thanks <br/> Patholab";
        $this->email->to($email);
        $this->email->from('support@virtualheight.com', 'Patholab');
        $this->email->subject('Patholab forgotten Password');
        $this->email->message($message);
        $this->email->send();
					echo $this->json_data("1","",array(array("msg"=>"Password has been Sent on Your Email")));   
		}else {

				echo $this->json_data("0","Parameter not passed","");
            }
	}
	function get_country(){
		$data = $this->service_model->master_fun_get_tbl_val("country", array("status" => 1),array("id","asc"));
		if(empty($data)){
			echo $this->json_data("0","no data available","");
		}else{
		echo $this->json_data("1","",$data);
		}
	}
	function view_profile(){
		$userid = $this->input->get_post('user_id');
		if($userid != null){
				$data = $this->service_model->view_profile($userid);
		if(empty($data)){
			echo $this->json_data("0","no data available","");
		}else{
		echo $this->json_data("1","",$data);
		}
		}else{
			
			echo $this->json_data("0","Parameter not passed","");	
		}
	}
	function get_state(){
		$cid = $this->input->get_post('country_id');
		if($cid !=null){
			$data = $this->service_model->master_fun_get_tbl_val("state", array("country_fk"=>$cid,"status" => 1),array("id","asc"));
		if(empty($data)){
			echo $this->json_data("0","no data available","");
		}else{
		echo $this->json_data("1","",$data);
		}
		}else{
			echo $this->json_data("0","Parameter not passed","");	
		}
	}	
	function get_city(){
		$cid = $this->input->get_post('state_id');
		if($cid != null){	
			$data = $this->service_model->master_fun_get_tbl_val("city", array("state_fk"=>$cid,"status" => 1),array("id","asc"));
		if(empty($data)){
			echo $this->json_data("0","no data available","");
		}else{
		echo $this->json_data("1","",$data);
		}
		}else {
			echo $this->json_data("0","Parameter not passed","");	
		}
	}
	function edit_profile(){
		$user_fk = $this->input->get_post('user_id');
		$name = $this->input->get_post('name');
		$email = $this->input->get_post('email');
		$mobile = $this->input->get_post('mobile');
		$address = $this->input->get_post('address');
		$gender = $this->input->get_post('gender');
		$country = $this->input->get_post('country');
		$state = $this->input->get_post('state');
		$city = $this->input->get_post('city');
		$pic = $this->input->get_post('pic');
		if($user_fk != "" && $name!="" && $email != "" && $mobile != " "){
			$data = array(
			"pic"=>$pic,
			"full_name"=>$name,
			"email"=>$email,
			"mobile"=>$mobile,
			"address"=>$address,
			"gender"=>$gender,
			"country"=>$country,
			"state"=>$state,
			"city"=>$city,	
			);
			$update = $this->service_model->master_fun_update("customer_master",$user_fk,$data);
			if($update){
				echo $this->json_data("1","",array(array("msg"=>"Your Profile has been Updated")));
			}
		}else{
			echo $this->json_data("0","Parameter not passed","");
		}
	}
	function change_password(){
		$user_fk = $this->input->get_post('user_id');
		$oldpassword = $this->input->get_post('oldpassword');
		$password = $this->input->get_post('password');
		if($user_fk != "" && $password != ""){
			 $row = $this->service_model->master_fun_get_tbl_val("customer_master", array("id"=>$user_fk,"status" => 1),array("id","asc"));
			$oldpass = $row[0]['password'];
			if($oldpassword == $oldpass){
				$data = array(
			"password"=>$password,
			);
			$update = $this->service_model->master_fun_update("customer_master",$user_fk,$data);
			if($update){
				echo $this->json_data("1","",array(array("msg"=>"Your Password has been Updated")));
			}
			}else{
				echo $this->json_data("0","old password not match","");
			}
		}else{
			echo $this->json_data("0","Parameter not passed","");
		}
	}
	function test_list(){
		$data = $this->service_model->master_fun_get_tbl_val("test_master", array("status" => 1),array("id","asc"));
		if(empty($data)){
			echo $this->json_data("0","no data available","");
		}else{
			echo $this->json_data("1","",$data);
		}
		
	}
	function my_job(){
		$user_fk = $this->input->get_post('user_id');
		if($user_fk!=""){
			$data = $this->service_model->my_job($user_fk);
		if(empty($data)){
			echo $this->json_data("0","no data available","");
		}else{
			
		
		echo $this->json_data("1","",$data);
			
		}
		}else{
			
			echo $this->json_data("0","Parameter not passed","");
		}
		
	}
	function total_wallet(){
		$user_fk = $this->input->get_post('user_id');
		if($user_fk!=""){
		$maxid = $this->service_model->total_wallet($user_fk);
		$data = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" => 1,"id"=>$maxid),array("id","asc"));
		if(empty($data)){
			echo $this->json_data("1","",array(array("total"=>"0")));
		}else{
			echo $this->json_data("1","",$data);
		}
		
			
		}else{
			
			echo $this->json_data("0","Parameter not passed","");
		}
		
	}
	 function add_wallet(){
		$user_fk = $this->input->get_post('user_id');
		 $amount = $this->input->get_post('amount'); 
		if($user_fk!="" && $amount!=""){
			$query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" =>1,"cust_fk"=>$user_fk), array("id", "desc"));
		$total = $query[0]['total'];
		$data = array(
						 "cust_fk"=>$user_fk,
						 "credit"=>$amount,
						 "total"=>$total+$amount,
						 "created_time"=>date('Y-m-d H:i:s')
						 );
				$insert = $this->service_model->master_fun_insert("wallet_master",$data);
				if($insert){
$query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" =>1,"cust_fk"=>$user_fk), array("id", "desc"));
		$total = $query[0]['total'];					
					echo $this->json_data("1","",array(array("msg"=>"Money Added Successfully","total"=>$total)));
				}
		}else{
			echo $this->json_data("0","Parameter not passed","");
		}
	}
	function job_report(){
		$jid = $this->input->get_post('job_id');
		if($jid != null){	
			$data = $this->service_model->master_fun_get_tbl_val("report_master", array("job_fk"=>$jid,"status" => 1),array("id","asc"));
		if(empty($data)){
			echo $this->json_data("0","no data available","");
		}else{
		echo $this->json_data("1","",$data);
		}
		}else {
			echo $this->json_data("0","Parameter not passed","");	
		}
	}
	function search_test(){
			$test = $this->input->get_post('testname');
			if($test!=""){	
				$data = $this->service_model->search_test($test);
				if(empty($data)){
					echo $this->json_data("0","no data available","");
				}else{
		echo $this->json_data("1","",$data);	
				}
			}else{
				echo $this->json_data("0","Parameter not passed","");
			}
	}
	function add_job(){
		$user_fk = $this->input->get_post('user_id');
		$testfk = $this->input->get_post('test_id');
		$amount = $this->input->get_post('total');
		$date = date('d/m/Y');
		if($user_fk !="" && $testfk !=""){
			$insert = $this->service_model->master_fun_insert("job_master", array("cust_fk" => $user_fk,"date"=>$date));	
			$test = explode(',',$testfk);
			$price = 0 ;
			for($i=0 ; $i < count($test) ; $i++){
			$insert_code = $this->service_model->master_fun_insert("job_test_list_master", array("job_fk" => $insert,"test_fk"=>$test[$i]));
			$data = $this->service_model->master_fun_get_tbl_val("test_master", array("status" => 1,'id'=>$test[$i]),array("id","asc"));
				$price = $price + $data[0]['price'];
			}
	$update = $this->service_model->master_fun_update("job_master",$insert,array("price"=>$price));
	if($update){
		$query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" =>1,"cust_fk"=>$user_fk), array("id", "desc"));
		$total = $query[0]['total'];
		if($total >= $amount){	
		$data = array(
						 "cust_fk"=>$user_fk,
						 "debit"=>$amount,
						 "total"=>$total-$amount,
						 "created_time"=>date('Y-m-d H:i:s')
						 );
				$insert = $this->service_model->master_fun_insert("wallet_master",$data);
		echo $this->json_data("1","",array(array("msg"=>"Money Debited Successfully")));
		}
	//echo $this->json_data("1","",array(array("id"=>$insert)));
		
	}
	}else{
			echo $this->json_data("0","Parameter not passed","");
		}
	}
	function cancle_job(){
		$user_fk = $this->input->get_post('user_id');
		$job_fk = $this->input->get_post('job_id');
		
		$date = date('d/m/Y');
		if($user_fk !="" && $job_fk !=""){
			$update = $this->service_model->master_fun_update("job_master",$job_fk,array("status"=>4));	
			
	if($update){
		$query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" =>1,"cust_fk"=>$user_fk), array("id", "desc"));
		$total = $query[0]['total'];
		$query1 = $this->service_model->master_fun_get_tbl_val("job_master", array("id"=>$job_fk), array("id", "desc"));
		//print_r($query1); die();
		$amount = $query1[0]['price'];
		
		$data = array(
						 "cust_fk"=>$user_fk,
						 "credit"=>$amount,
						 "total"=>$total+$amount,
						 "created_time"=>date('Y-m-d H:i:s')
						 );
				$insert = $this->service_model->master_fun_insert("wallet_master",$data);
		echo $this->json_data("1","",array(array("msg"=>"Money Credited Successfully")));
	
	//echo $this->json_data("1","",array(array("id"=>$insert)));
		
	}
	}else{
			echo $this->json_data("0","Parameter not passed","");
		}
	}
	
	function dashboard_banner(){
		$data = $this->service_model->master_fun_get_tbl_val("banner_master", array("status" => 1,"group"=>3),array("id","asc"));
		echo $this->json_data("1","",$data);
	}
	function test_upload(){	
	echo form_open_multipart("api/img_upload/",array("method"=>"post","role"=>"form"));
		echo "<input type='file' name='userfile' />";
		echo "<input type='submit' name='test' value='upload'/>";
		echo "</form>";
	}
	public function upload_pic() {
	$files = $_FILES;
	$data = array();
	$this->load->library('upload');
	$config['allowed_types'] = 'png|jpg|gif|jpeg';
	//$config['encrypt_name'] = true;
	$config['max_size'] = '2000'; // 2MB
	$config['max_width'] = '3000'; // 3000px
	$config['max_height'] = '3000'; // 3000px
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
				echo $this->json_data("0",$error,"");
		}else {
			$doc_data = $this->upload->data();
			$filename = $doc_data['file_name'];
			$uploads = array('upload_data' => $this->upload->data("identity"));
			echo $this->json_data("1","",array(array("filename"=>$filename)));
		}
	}
	else{
		echo $this->json_data("0","You did not select a file to upload","");			
	}
	}
	
	function img_upload() {
        //print_R($_FILES);
        if (isset($_FILES['userfile']['name'])) {
            $config['upload_path'] = './upload/';
            $config['allowed_types'] = '*';
            $config['file_name'] = time() . $_FILES['userfile']['name'];
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if (!$this->upload->do_upload('userfile')) {
                $error = array('error' => $this->upload->display_errors());
                echo $this->json_data("0",$error, "");
            } else {
                $data = array('upload_data' => $this->upload->data());
                echo $this->json_data("1", "", array(array("file_name" => $config['file_name'])));
            }
        } else {
            echo $this->json_data("0", "You did not select a file to upload", "");
        }
    }
	function check_balance(){
		$user_fk = $this->input->get_post('user_id');
	 $amount = $this->input->get_post('total');
		if($user_fk!="" && $amount!=""){
			$query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" =>1,"cust_fk"=>$user_fk), array("id", "desc"));
		 $total = $query[0]['total'];
				if($total >= $amount){	
					echo $this->json_data("1","",array(array("msg"=>"Wallet amount is enoght","wallet"=>$total)));
				}else{
					
					echo $this->json_data("0",array(array("msg"=>"Please Upgread your Wallet","wallet"=>$$total),"");
				}
		}else{
			echo $this->json_data("0","Parameter not passed","");
		}
	}
	function debit_amount(){
		$user_fk = $this->input->get_post('user_id');
		$amount = $this->input->get_post('total');
		if($user_fk!="" && $amount!=""){
			$query = $this->service_model->master_fun_get_tbl_val("wallet_master", array("status" =>1,"cust_fk"=>$user_fk), array("id", "desc"));
		$total = $query[0]['total'];
		if($total >= $amount){	
		$data = array(
						 "cust_fk"=>$user_fk,
						 "debit"=>$amount,
						 "total"=>$total-$amount,
						 "created_time"=>date('Y-m-d H:i:s')
						 );
				$insert = $this->service_model->master_fun_insert("wallet_master",$data);
				if($insert){	
					echo $this->json_data("1","",array(array("msg"=>"Money Debited Successfully")));
				}
		}else{
			echo $this->json_data("0","Upgread Your Wallet","");
		}
		}else{
			echo $this->json_data("0","Parameter not passed","");
		}
		
	}
	function prescription_upload(){
		$image = $this->input->get_post('image');
		$desc = $this->input->get_post('description');
		$user_id = $this->input->get_post('user_id');
		if ($image != NULL && $desc != NULL && $user_id != Null) {
			
			$insert = $this->service_model->master_fun_insert("prescription_upload",array("cust_fk" => $user_id,"image"=>$image,"description"=>$desc,"created_date"=>date('d/m/Y')));				
           if($insert){
			   echo $this->json_data("1","",array(array("msg"=>"Presrciption Uploaded Successfully")));
		   }
		}else {
				echo $this->json_data("0","Parameter not passed","");
            }
	}
	function show_upload_prescription(){
		
		$userid = $this->input->get_post('user_id');
			if($userid!=""){	
				$data = $this->service_model->Prescription_report($userid);
				if(empty($data)){
					echo $this->json_data("0","no data available","");
				}else{
		echo $this->json_data("1","",$data);
				}		
			}else{
				echo $this->json_data("0","Parameter not passed","");
			}
		
		
	}
	
	function wallet_history(){
		$userid = $this->input->get_post('user_id');
		if($userid != null){
				$data = $this->service_model->wallet_history($userid);
				if(empty($data)){
					echo $this->json_data("0","no data available","");
				}else{
		echo $this->json_data("1","",$data);	
				}
		}else{
			
			echo $this->json_data("0","Parameter not passed","");	
		}
	}
	function suggest_test(){
		$userid = $this->input->get_post('pid');
		if($userid != null){
				$data = $this->service_model->suggest_test($userid);
				if(empty($data)){
					
					echo $this->json_data("0","no data available","");
				}else{
		echo $this->json_data("1","",$data);	
				}
		}else{
			
			echo $this->json_data("0","Parameter not passed","");	
		}
	}
	public function fb_login(){
		$email = $this->input->get_post('email');
		$name = $this->input->get_post('name');
		$device_id = $this->input->get_post('device_id');
		$fb_id = $this->input->get_post('fb_id');
		$pic = $this->input->get_post('profile_pic');
	   $code =  random_string('alnum', 6);
if ($email != null && $name != null) {
		$row = $this->service_model->master_num_rows("customer_master", array("fbid"=>$fb_id,"status" => 1));
		if($row == 0){
				$insert = $this->service_model->master_fun_insert("customer_master", array("full_name" => $name,"email"=>$email,"device_id"=>$device_id,"fbid"=>$fb_id,"pic"=>$pic));	
					$insert_code = $this->service_model->master_fun_insert("refer_code_master", array("cust_fk" => $insert,"refer_code"=>$code));
				$data = $this->service_model->master_fun_get_tbl_val("customer_master", array("id"=>$insert,"status" => 1),array("id","asc"));
				echo $this->json_data("1","",$data);
            }else{
				
				$data = $this->service_model->master_fun_get_tbl_val("customer_master", array("email"=>$email,"status" => 1),array("id","asc"));
				$update = $this->service_model->master_fun_update("customer_master",$data[0]['id'],array("full_name" => $name,"email"=>$email,"device_id"=>$device_id,"fbid"=>$fb_id,"pic"=>$pic));
				echo $this->json_data("1","",$data);
			}
		}else {
				echo $this->json_data("0","Parameter not passed","");
            }
	}
	
	function payuresponce(){
		
		$status = $this->input->post('status');
$data=array("status"=>$status);
echo json_encode($data);
$this->load->view('payu_success');
		
	}
	
function payu_success(){
$t=json_encode($_POST);  
//echo "this page is success page".$t; die();
$payumonyid = $_POST['txnid']; 
$paydate = $_POST['addedon'];
$amount = $_POST['net_amount_debit'];
$status = $_POST['status']; 
$uid = "";

$data = array(	"payomonyid"=>$payumonyid,
				 "amount"=>$amount,
				 "paydate"=>$paydate,
				 "status"=>$status,
				 "uid"=>$uid,
				 "data"=>$t,
				);
				$insert = $this->service_model->master_fun_insert("payment",$data);
//$query2="insert into payment (payomonyid,amount,paydate,status,uid,data) values('$payumonyid','$amount','$paydate','$status','$uid','$t')" or die(mysql_error()); 
//echo $query2; die();
//$result2=$conn->query($query2);	
$this->load->view('success1');
	}
	
	
    function json_data($status,$error_msg,$data=NULL){
		if($data==NULL){
			$data=array();
		}
        $final = array("status"=>$status,"error_msg"=>$error_msg,"data"=>$data);
       return  str_replace("null",'" "',json_encode($final));
    }
}
?>