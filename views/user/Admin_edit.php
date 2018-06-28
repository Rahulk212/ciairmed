<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin_edit extends CI_Controller {

    function __construct() {
        parent::__construct();
       $this->load->model('user_model');
	   
		 $data["login_data"] = logindata();
		 	
    }
    
	function index(){
		if(!is_loggedin()){
			redirect('login');
		}
if ($this->session->userdata('success') != null) {
                $data['success'] = $this->session->userdata("success");
                $this->session->unset_userdata('success');
            }
      
		$data["login_data"] = logindata(); 
		 
		$data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
		// echo "here"; die();
		$userid = $data["login_data"]["id"];
		$this->load->library('form_validation');

        $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');
        $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
			if($this->input->post('chng_pwd')){
				$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
			}
        if ($this->form_validation->run() == FALSE) {
		$this->load->view('header');
		$this->load->view('nav',$data);
		$this->load->view('edit_user',$data);
		$this->load->view('footer');
		}else{
			$username = $this->input->post("username");
			$email = $this->input->post("email");
			$password = $this->input->post("password");
				$data1 = array(
					"name" => $username,
					"password" =>$password,
					"email" => $email
 					
				);
		
			$update=$this->user_model->update_user1($data1,$userid);
			if($update)
			{
			$ses = array("Admin Successfully Updated!");
                $this->session->set_userdata('success', $ses);
                
			redirect('admin-profile-update');
	
			}
			$data["login_data"] = logindata(); 
			$data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
		$this->load->view('header');
		$this->load->view('nav',$data);
		$this->load->view('edit_user',$data);
		$this->load->view('footer');
		
	}
	}
	
	
}
?>