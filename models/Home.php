<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    function __construct() {
        parent::__construct();
       $this->load->model('home_model');
	    $this->load->model('user_model');
	  //$menu = $this->user_model->master_fun_get_tbl_val("menu_master",array('status'=>1),array("id","asc"));
		//print_r($menu); die();
		//$this->data['menu']=$menu;
		//$path = uri_string(); 
		 $data["login_data"] = logindata();
		// $this->data['usertype'] = $data["login_data"]["type"];
		
		//$data['query'] = $this->user_model->set_user_permission($this->data['usertype'],$path);
	//	print_r($data['query']); die();
		//$this->data['user_permission']=$data['query'];
    }
    
	function index(){
		if(!is_loggedin()){
			redirect('login');
		}
	if($this->session->flashdata("error")) {
		$data["error"] = $this->session->flashdata("error");
		
		}
		$data["login_data"] = logindata(); 
		//$data["ongoing"] = $this->home_model->getongoing();
		//$data["complete"] = $this->home_model->getcomplete();
		//$data["totalsubcategory"] = $this->home_model->getsubcategory();
		$data1 = $this->user_model->master_fun_get_tbl_val("employee_master", array("status"=>1), array("id", "desc"));
		$data['employee'] = count($data1);
		$this->load->view('header');
		$this->load->view('nav',$data);
		$this->load->view('home_view',$data);
		$this->load->view('footer');
	}
}?>