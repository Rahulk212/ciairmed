<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Time_slot_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('user_model');
		 $this->load->model('time_slot_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
		//echo current_url(); die();
		
		 $data["login_data"] = logindata();
		
    }

    function slot_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
		//print_r($data["login_data"]); die();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $data['success'] = $this->session->flashdata("success");
        $data['query'] = $this->time_slot_model->master_fun_get_tbl_val("slot_master",array('status'=>1),array("id","asc"));
        //$this->load->view('admin/state_list_view', $data);
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('slot_list', $data);
        $this->load->view('footer');
    }
    function slot_add() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $this->load->library('form_validation');
        $this->form_validation->set_rules('day', 'Day', 'trim|required');
		 $this->form_validation->set_rules('from', 'From time', 'trim|required');
		  $this->form_validation->set_rules('to', 'To Time', 'trim|required');
		   $this->form_validation->set_rules('spot', 'Spot', 'trim|required');
		    
		  

        if ($this->form_validation->run() != FALSE) {
            $day = $this->input->post('day');
			            $from = $this->input->post('from');
						 $to = $this->input->post('to');
						  $spot = $this->input->post('spot');
			
            $data['query'] = $this->time_slot_model->master_fun_insert("slot_master", array("week_day" => $day,"from"=>$from,"to"=>$to,"spot"=>$spot));
            $this->session->set_flashdata("success", array("Slot successfull added."));
            redirect("time_slot_master/slot_list", "refresh");
        } else {
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('slot_add', $data);
            $this->load->view('footer');
        }
    }
    function slot_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->user_model->master_fun_update("slot_master", array("id", $cid), array("status" => "0"));

        $this->session->set_flashdata("success", array("Slot successfull deleted."));
         redirect("time_slot_master/slot_list", "refresh");
    }
    function slot_edit() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["cid"] = $this->uri->segment('3');

        $this->load->library('form_validation');
        $this->form_validation->set_rules('day', 'Day', 'trim|required');
		 $this->form_validation->set_rules('from', 'From time', 'trim|required');
		  $this->form_validation->set_rules('to', 'To Time', 'trim|required');
		   $this->form_validation->set_rules('spot', 'Spot', 'trim|required');
		
        if ($this->form_validation->run() != FALSE) {
              $day = $this->input->post('day');
			            $from = $this->input->post('from');
						 $to = $this->input->post('to');
						  $spot = $this->input->post('spot');
            $data['query'] = $this->time_slot_model->master_fun_update("slot_master", array("id", $data["cid"]), array("week_day" => $day,"from"=>$from,"to"=>$to,"spot"=>$spot));
            $this->session->set_flashdata("success", array("Slot successfull updated."));
            redirect("time_slot_master/slot_list", "refresh");
        } else {
            $data['query'] = $this->time_slot_model->master_fun_get_tbl_val("slot_master", array("id" => $data["cid"]), array("id", "desc"));

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('slot_edit', $data);
            $this->load->view('footer');
        }
    }
	function get_shift(){
		
		$day = $this->uri->segment(3);
		  $data = $this->time_slot_model->master_fun_get_tbl_val("slot_master", array("week_day" =>$day,"status"=>1), array("id", "desc"));
	//print_r($data); die();
	echo "<option value=''> Select Shift </option>" ; 
		for($i=0; $i < count($data); $i++){
//echo $i;
		$id= $data[$i]['id'];
		$name = ucfirst($data[$i]['from']) .' To '.ucfirst($data[$i]['to']);
			
		echo "<option value='$id'> $name </option>" ; 
			
		}
		
	}
	function book_spot() {
		
		if (!is_userloggedin()) {
            redirect('login_employee');
        }
         $this->load->library('email');
        $data["login_data"] = loginuser();
	//	print_r($data["login_data"]); die();
	$employeeid = $data["login_data"]["id"];
        $data["user"] = $this->time_slot_model->getUser($data["login_data"]["id"]);
//print_r($data["user"]);die();
 $email = $data["user"]->email;
        $this->load->library('form_validation');
$data1 = $this->time_slot_model->master_fun_get_tbl_val("site_setting", array("status"=>1), array("id", "desc"));
		//print_r($data1 );die();
		 $days = $data1[0]['days'];
		$daysarray = explode(',',$days);
		///print_r($daysarray); die();
		$currentDayNumber =  date('w', strtotime('today'));
		if(!in_array($currentDayNumber,$daysarray) ){
		$this->load->view('booking_end');
		}else{
		/*       $this->form_validation->set_rules('name', 'Name', 'trim|required');
		 $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required');
	*/	  $this->form_validation->set_rules('shift_id', 'Shift time', 'trim|required');
		  
		    
		  $data['success'] = $this->session->flashdata("success");
		  $data['error'] = $this->session->flashdata("error");
		  //print_r($data['success']); die();

        if ($this->form_validation->run() != FALSE) {
            
			            
						$shift = $this->input->post('shift_id');
						 $date = $this->input->post('date');
		$data = $this->time_slot_model->master_fun_get_tbl_val("slot_master", array("id" =>$shift,"status"=>1), array("id", "desc"));
			 $spot = $data[0]['spot'];
			 $shifttimefrom = $data[0]['from'];
			  $shifttimeto = $data[0]['to'];
			  $weekday = $data[0]['week_day'];
			
			$data1 = $this->time_slot_model->master_fun_get_tbl_val("book_shift_master", array("shift_fk" =>$shift,"date"=>$date,"status"=>1), array("id", "desc"));
			
			
			//-- same  time frame new code--------------------------------------
			
			$data2 = $this->time_slot_model->master_fun_get_tbl_val("book_shift_master", array("name" =>$employeeid,"date"=>$date,"status"=>1), array("id", "desc"));
			//print_r($data2);
		 $booked_shift_fk = $data2[0]['shift_fk'];
			$data3 = $this->time_slot_model->master_fun_get_tbl_val("slot_master", array("id" =>$booked_shift_fk,"status"=>1), array("id", "desc"));
			//print_r($data3);
			$dfrom = str_replace(" ","",$data3[0]['from']);
			$dto = str_replace(" ","",$data3[0]['to']);
			$bfrom = str_replace(" ","",$shifttimefrom);
			$to = str_replace(" ","",$shifttimeto);
			  $dfrom24  = date("H:i", strtotime($dfrom));
			   $dto24  = date("H:i", strtotime($dto));
			    $bfrom24  = date("H:i", strtotime($bfrom));
			    $bto24  = date("H:i", strtotime($bto));
			//echo date("H:i", strtotime("01:00 PM"));
			
			 $bookedfrom = strtotime($dfrom24); 
			   $bookedto = strtotime($dto24);
			  $strfrom = strtotime($bfrom24);
			  $strto = strtotime($bto24);
			 
			 
			if($bookedfrom < $strfrom && $strfrom < $bookedto && $bookedto > $strfrom ){
				
				$this->session->set_flashdata("error", "Spots has been full");
            redirect("time_slot_master/time_frame_booked", "refresh");
			}
			//die();
			
			
			// same  timeframe new code -----------------------------------------------
			
			
			$totalbooked = count($data1); 
			
			if($spot > $totalbooked){
				
				 $insert = $this->time_slot_model->master_fun_insert("book_shift_master", array("shift_fk"=>$shift,"date"=>$date,"name"=>$employeeid));
				 if($insert){
					 $config['mailtype'] = 'html';
        $this->email->initialize($config);
        
$message = "Su turno ha sido confirmado.<br/><br/>";
$message .="El horario seleccionado es el $weekday de $shifttimefrom a $shifttimeto <br/><br/>";
$message .= "Muchas gracias! <br/> -Cabify Panamá";
        $this->email->to($email);
        $this->email->from('partners.pty@cabify.com', 'Cabify Panamá');
        $this->email->subject('Confirmación de turno');
        $this->email->message($message);
        $this->email->send();
		//echo $this->email->print_debugger();
		//die();
					  $this->session->set_flashdata("success","Slot successfull added.");
            redirect("time_slot_master/thank_you", "refresh");
				 }
           
			
			}else{
				$this->session->set_flashdata("error", "Spots has been full");
            redirect("time_slot_master/sorry", "refresh");
			}
           
        } else {
           
		   $data['monday'] = $this->time_slot_model->master_fun_get_tbl_val("slot_master", array("week_day" =>"monday","status"=>1), array("id", "desc"));
		  
		   $refdate = date('n/j/Y');
$timestamp = strtotime($refdate);
$data['mondaydate']= date('n/j/Y',
    strtotime("next Monday " . date('H:i:s', $timestamp), $timestamp)
);
$data['tuesdaydate']= date('n/j/Y',
    strtotime("next Tuesday " . date('H:i:s', $timestamp), $timestamp)
);
$data['weddate']= date('n/j/Y',
    strtotime("next Wednesday " . date('H:i:s', $timestamp), $timestamp)
);
$data['thudate']= date('n/j/Y',
    strtotime("next Thrusday " . date('H:i:s', $timestamp), $timestamp)
);
$data['fridate']= date('n/j/Y',
    strtotime("next Friday " . date('H:i:s', $timestamp), $timestamp)
);
$data['satdate']= date('n/j/Y',
    strtotime("next Saturday " . date('H:i:s', $timestamp), $timestamp)
);
$data['sundate']= date('n/j/Y',
    strtotime("next Sunday " . date('H:i:s', $timestamp), $timestamp)
);
          // print_r($data['monday']); die();
		   $data['tuesday'] = $this->time_slot_model->master_fun_get_tbl_val("slot_master", array("week_day" =>"tuesday","status"=>1), array("id", "desc"));
			 $data['thrusday'] = $this->time_slot_model->master_fun_get_tbl_val("slot_master", array("week_day" =>"thursday","status"=>1), array("id", "desc"));
			  $data['wednesday'] = $this->time_slot_model->master_fun_get_tbl_val("slot_master", array("week_day" =>"wednesday","status"=>1), array("id", "desc"));
			   $data['friday'] = $this->time_slot_model->master_fun_get_tbl_val("slot_master", array("week_day" =>"friday","status"=>1), array("id", "desc"));
			    $data['saturday'] = $this->time_slot_model->master_fun_get_tbl_val("slot_master", array("week_day" =>"saturday","status"=>1), array("id", "desc"));
				 $data['sunday'] = $this->time_slot_model->master_fun_get_tbl_val("slot_master", array("week_day" =>"sunday","status"=>1), array("id", "desc"));
				  $data['maintext'] = $this->time_slot_model->master_fun_get_tbl_val("site_setting", array("status"=>1), array("id", "desc"));
			$this->load->view('book_spot_view',$data);
            
        }
		}
    }
	
	function thank_you(){
		$this->load->view('thank_you');
	}
	function sorry(){
		$this->load->view('sorry');
	}
	function time_frame_booked(){
		$this->load->view('time_frame_book');
	}
	
	function booking_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
		//print_r($data["login_data"]); die();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
 $name = $this->input->get("search_name");
 $data['name']=$name;
$mobile = $this->input->get("search_mo");
$data['mobile']=$mobile;
$date = $this->input->get("date");
$data['date']=$date;
$shift = $this->input->get("shift");
$data['shift']=$shift;
$n = date('w', strtotime($date));
// die();
if($n==1){
	$val ="monday";
}
if($n==2){
	$val ="tuesday";
}
if($n==3){
	$val ="wednesday";
}
if($n==4){
	$val ="thrusday";
}
if($n==5){
	$val ="friday";
}
if($n==6){
	$val ="saturday";
}
if($n==0){
	$val ="sunday";
}
$data['shifts'] = $this->time_slot_model->master_fun_get_tbl_val("slot_master", array("status"=>1,"week_day"=>$val), array("id", "asc"));

        $data['success'] = $this->session->flashdata("success");
        $data['query'] = $this->time_slot_model->booking_list($name,$mobile,$date);
        //$this->load->view('admin/state_list_view', $data);
		$data['employee'] = $this->time_slot_model->master_fun_get_tbl_val("employee_master", array("status"=>1), array("username", "asc"));
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('booking_list', $data);
        $this->load->view('footer');
    }
	
	function booking_list_by_name() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
		//print_r($data["login_data"]); die();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
 $name = $this->input->get("search_name");
 $data['name']=$name;
$mobile = $this->input->get("search_mo");
$data['mobile']=$mobile;
$date = $this->input->get("date");
$data['date']=$date;
$date1 = $this->input->get("date1");
$data['date1']=$date1;
$date2 = $this->input->get("date2");
$data['date2']=$date2;
$shift = $this->input->get("shift");
$data['shift']=$shift;
$n = date('w', strtotime($date));
// die();
if($n==1){
	$val ="monday";
}
if($n==2){
	$val ="tuesday";
}
if($n==3){
	$val ="wednesday";
}
if($n==4){
	$val ="thrusday";
}
if($n==5){
	$val ="friday";
}
if($n==6){
	$val ="saturday";
}
if($n==0){
	$val ="sunday";
}
$data['shifts'] = $this->time_slot_model->master_fun_get_tbl_val("slot_master", array("status"=>1,"week_day"=>$val), array("id", "asc"));

        $data['success'] = $this->session->flashdata("success");
	if($name!="" || $mobile!= "" || $date != "" || $shift !="" || $date1 !="" || $date2!=""){
		 $data['query'] = $this->time_slot_model->booking_list($name,$mobile,$date,$shift,$date1,$date2);
	}else{
		 $data['query'] = array();
	}
       
        //$this->load->view('admin/state_list_view', $data);
		$data['employee'] = $this->time_slot_model->master_fun_get_tbl_val("employee_master", array("status"=>1), array("username", "asc"));
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('booking_list_by_name', $data);
        $this->load->view('footer');
    }
	
	function export_csv(){
		
		$name=$this->uri->segment(3);
		$mobile=$this->uri->segment(4);
		$date=$this->uri->segment(5);
		$shift=$this->uri->segment(6);
		$data1 = $this->time_slot_model->master_fun_get_tbl_val("employee_master", array("status"=>1,"id"=>$name), array("id", "asc"));
		
		$this->load->dbutil();
		 
        $this->load->helper('file');
        $this->load->helper('download');
        $delimiter = ",";
        $newline = "\r\n";
       
       $query = "SELECT e.username,e.mobile,e.email,b.date,s.`from`,s.`to` FROM book_shift_master b LEFT JOIN slot_master s ON s.id=b.`shift_fk` LEFT JOIN employee_master e ON e.id=b.name WHERE b.status=1";
		
		if($name!=""){
			$employeename = $data1[0]['username'];
			$query .= " AND b.name='$name'";
		}
		if($mobile!=""){
			$query .= " AND e.mobile like '%$mobile%'";
		}
		if($date!=""){
			$query .= " AND b.date='$date'";
		}
		if($shift!=""){
			$query .= " AND b.shift_fk`='$shift'";
		}
		 $filename = "$employeename.csv";
        $result = $this->db->query($query);
        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
        force_download($filename, $data);
		
		
	}
	function export_csv_by_shift(){
		
		
		
		$shift=$this->uri->segment(3);
		$data['shifts'] = $this->time_slot_model->master_fun_get_tbl_val("slot_master", array("status"=>1,"id"=>$shift), array("id", "asc"));
		
		$this->load->dbutil();
		 
        $this->load->helper('file');
        $this->load->helper('download');
        $delimiter = ",";
        $newline = "\r\n";
        
       $query = "SELECT e.username,e.mobile,e.email,b.date,s.`from`,s.`to` FROM book_shift_master b LEFT JOIN slot_master s ON s.id=b.`shift_fk` LEFT JOIN employee_master e ON e.id=b.name WHERE b.status=1";
		
		if($shift!=""){
			$shiftname = $data['shifts'][0]['from'] .'_to_'. $data['shifts'][0]['to'];
			$query .= " AND b.shift_fk=$shift";
		}
		//echo $query; die();
		$filename = "$shiftname.csv";
        $result = $this->db->query($query);
        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
        force_download($filename, $data);
		
		
	}
	
	 function booking_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->user_model->master_fun_update("book_shift_master", array("id", $cid), array("status" => "0"));

        $this->session->set_flashdata("success", array("booking successfull deleted."));
        redirect("time_slot_master/booking_list", "refresh");
    }
	
	function site_setting(){
		if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
		
        $this->load->library('form_validation');
        $this->form_validation->set_rules('days[]', 'Days', 'trim|required');
		if ($this->form_validation->run() != FALSE) {
             $days = $this->input->post('days');
			  $txt = $this->input->post('main_txt');
			//print_r($days); die();
			$alldays = implode($days,','); 
            $data['query'] = $this->user_model->master_fun_update("site_setting", array("id",1),array("days" => $alldays,"main_text"=>$txt));
            $this->session->set_flashdata("success", array("Employee successfull updated."));
            redirect("time_slot_master/site_setting", "refresh");
        } else {
            $data['query'] = $this->user_model->master_fun_get_tbl_val("site_setting", array("status" =>1), array("id", "desc"));
			$days = $data['query'][0]['days'];
			$data['days'] = explode(',',$days);
 //print_r($data['days']); die();
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('site_setting', $data);
            $this->load->view('footer');
        }
		
	}
	
	function delete_all(){
		
		$allids = $this->input->post('ids');
		
		foreach($allids as $id){
			//echo $i;
			$delete = $this->user_model->master_fun_update("book_shift_master", array("id",$id), array("status" => "0"));
		}
		if($delete){
			echo 1;
		}
		
		
		
		
	}

}
?>