<?php
class Labcollection_v1 extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->library('email');
        $this->load->model('service_model');
        $this->load->helper('security');
        $this->load->helper('string');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST');
        header("Access-Control-Allow-Headers: X-Requested-With");
        $this->app_tarce();
    }

    function app_tarce() {
        $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $ipaddress = $_SERVER['REMOTE_ADDR'];
        $page = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}";
        if (!empty($_SERVER['QUERY_STRING'])) {
            $page = $_SERVER['QUERY_STRING'];
        } else {
            $page = "";
        }
        if (!empty($_POST)) {
            $user_post_data = $_POST;
        } else {
            $user_post_data = array();
        }
        $user_post_data = json_encode($user_post_data);
        $useragent = $_SERVER['HTTP_USER_AGENT'];
        $remotehost = @getHostByAddr($ipaddress);
        $user_info = json_encode(array("Ip" => $ipaddress, "Page" => $page, "UserAgent" => $useragent, "RemoteHost" => $remotehost));
        if ($actual_link != "http://www.airmedlabs.com/index.php/api/send") {
            $user_track_data = array("url" => $actual_link, "user_details" => $user_info, "data" => $user_post_data, "createddate" => date("Y-m-d H:i:s"), "type" => "service");
        }
        $app_info = $this->service_model->master_fun_insert("user_track", $user_track_data);
        
    }
	function json_data($status, $error_msg, $data = NULL) {
        if ($data == NULL) {
            $data = array();
        }
        $final = array("status" => $status, "error_msg" => $error_msg, "data" => $data);
        return json_encode($final);
    }
	
public function register(){
	 
 $this->load->library('form_validation');
 $this->form_validation->set_rules('name', 'Name','required|trim|callback_alpha_dash_space');
  $this->form_validation->set_rules('email', 'Email','required|trim|valid_email|callback_check_email1');
  $this->form_validation->set_rules('password','password','trim|required');
  $this->form_validation->set_rules('city', 'City', 'trim|required|numeric');
  $this->form_validation->set_rules('address', 'Address','trim');
  $this->form_validation->set_rules('pincode', 'pincode','trim');
  $this->form_validation->set_rules('landline', 'landline telephone no', 'trim');
  $this->form_validation->set_rules('mobile','Mobile no','trim');
  $this->form_validation->set_rules('contact','contact person','trim');
  $this->form_validation->set_rules('pannumbar', 'pan card of clients','trim');
  $this->form_validation->set_rules('busdesc','business description','trim');
  $this->form_validation->set_rules('space','space allocated ','trim');
  $this->form_validation->set_rules('bexpected','business expected','trim');
   
if ($this->form_validation->run() == FALSE) {
 
			if(validation_errors() != ""){	$erro=$this->splitNewLine(validation_errors()); }else{ $erro=""; }
			echo $this->json_data("0","All Parameter are Required!",$erro); die();      
		
		} else {
		$name = $this->input->post('name');
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		$address = $this->input->post('address');
		$pincode = $this->input->post('pincode');
		$landline = $this->input->post('landline');
		$mobile = $this->input->post('mobile');
		$contact = $this->input->post('contact');
		$pannumbar = $this->input->post('pannumbar');
		$busdesc = $this->input->post('busdesc');
		$space = $this->input->post('space');
		$bexpected = $this->input->post('bexpected');
		$city = $this->input->post('city');
		
		$data=array("name"=>$name,"email"=>$email,"password"=>$password,"contact_person_name"=>$contact,"mobile_number"=>$mobile,"alternate_number"=>$landline,"pincode"=>$pincode,"pancard"=>$pannumbar,"bus_desc"=>$busdesc,"space_allocate"=>$space,"bus_expeted"=>$bexpected,"address"=>$address,"createddate"=>date("Y-m-d H:i:s"),"type"=>'2',"city"=>$city,"status"=>'3');
	
	$colletd=$this->service_model->master_fun_insert("collect_from",$data);
		
	 echo $this->json_data("1", "",array(array("id"=>$colletd)));
	
		}
        
    }
function check_email1() {
	
      $email = $this->input->post('email');
	  $result=$this->service_model->master_num_rows('collect_from',array("email"=>$email,"status"=>1));
		if($result != 0) {
    	 echo $this->json_data("0", "Email Already Exists.",""); die();
      	return false;
		}else { return true; }

 }	
function alpha_dash_space($str)
{
if (! preg_match("/^([-a-z_ ])+$/i", $str)) {
 echo $this->json_data("0", "The Name field may only contain alphabetical characters.", "");
die();
} else {
return TRUE;
}

}
public function uplode_document(){
$labid=$this->input->post('labid');
 if($_FILES["images"]["name"][0] != ""){

		$config['upload_path'] ='./upload/labducuments';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
       // $config['max_size']  = '5120';
        $files = $_FILES;
        $cpt = count($_FILES['images']['name']);
		$allducment=array();
		for ($i = 0; $i < $cpt; $i++) {
			
			$_FILES['images']['name'] = $files['images']['name'][$i];
           $_FILES['images']['type'] = $files['images']['type'][$i];
           $_FILES['images']['tmp_name'] = $files['images']['tmp_name'][$i];
           $_FILES['images']['error'] = $files['images']['error'][$i];
           $_FILES['images']['size'] = $files['images']['size'][$i];
		   $_FILES['images']['name']=str_replace(" ","",time().$files['images']['name'][$i]);

            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            $img = "images";
            if (!$this->upload->do_upload($img)) {
             $data['error'] = $this->upload->display_errors();  
             $ses = $data['error']; 
			 echo $this->json_data("0",$ses,"");
					die();
            } else {
				
                $file_data = $this->upload->data();
				
				$profile_pic_size=$file_data['file_size'];
	              if($file_data){
				  $allducment[]=$_FILES['images']['name'];
				 
				 
                }else{
                	echo $this->json_data("0", "sorry your image height width must be 577*390", "");
					die();
		
                }
            }
    }
		
if($allducment != null){ foreach($allducment as $val){ $this->service_model->master_fun_insert("lab_document",array("lab_id"=>$labid,"dock_name"=>$val,"credteddate"=>date("Y-m-d H:i:s"))); } 
 echo $this->json_data("1", "",array());
}

    }else{
		
		echo $this->json_data("0", "All Parameters are required.", "");
		
	} 
}	
public function splitNewLine($text) {
    $code=preg_replace('/\n$/','',preg_replace('/^\n/','',preg_replace('/[\r\n]+/',"\n",$text)));
    return explode("\n",$code);
}
}