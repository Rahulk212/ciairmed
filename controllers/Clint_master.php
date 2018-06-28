<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Clint_master extends CI_Controller {

    function __construct() {
        parent::__construct();
       
        $this->load->model('user_model');
        $this->load->model('client_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('email');
        $data["login_data"] = logindata();
        $this->app_track();
    }

    function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }

    function client_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        
        $name = $this->input->get('name');
        $email = $this->input->get('email');
		 $mobile = $this->input->get('mobile');
		 $date = $this->input->get('date');
      
      if ($name != "" || $email != "" || $mobile != "" ||  $date != "") {
		  
           $total_row = $this->client_model->lab_num_rows($name,$email,$mobile,$date);
            $config = array();
            $get = $_GET;
            unset($get['offset']);
            $config["base_url"] = base_url()."clint_master/client_list?name=$name&email=$email&mobile=$mobile&date=$date";
            $config["total_rows"] = $total_row;
            $config["per_page"] = 50;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->client_model->lab_data($name,$email,$mobile,$date,$config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        } else {
			
			$total_row = $this->client_model->lab_num_rows();
            $config["base_url"] = base_url()."clint_master/client_list";
            $config["total_rows"] = $total_row;
            $config["per_page"] = 50;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->client_model->srch_lab_list($config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        }
$data["counts"] = $page;
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('client_list_view', $data);
        $this->load->view('footer');
    
	}

    

    function clint_delete() {
		
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
      
        $cid = $this->uri->segment('3');
        $data['query'] = $this->user_model->master_fun_update("collect_from", array("id", $cid), array("status" => "0"));
        //	$data['query'] = $this->customer_model->master_fun_update("customer_master", array("id", $cid), array("mobile" => "" ));
        $this->session->set_flashdata("success", array("clint successfully deleted."));
        redirect("clint_master/client_list","refresh");
    }

 function client_deactive() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->user_model->master_fun_update("collect_from", array("id", $cid), array("status" => "0"));

        $this->session->set_flashdata("success", array("Clint successfully deactivated."));
        redirect("clint_master/client_list", "refresh");
    }
function client_active() {
		
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->user_model->master_fun_update("collect_from", array("id", $cid), array("status" => "1"));

        $this->session->set_flashdata("success", array("Clint successfully activated."));
        redirect("clint_master/client_list", "refresh");
    
}
    function client_view() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["lab_fk"] = $this->uri->segment('3');
        $lab_fk=$this->uri->segment('3');
        
        
        $data['credit_list'] = $this->user_model->master_fun_get_tbl_val("sample_credit", array('status' => 1, "lab_fk" => $lab_fk), array("id", "desc"), array(5, 0));
		
		$data['labdocks'] = $this->user_model->master_fun_get_tbl_val("lab_document",array('status' => 1,"type"=>'identity',"lab_id" => $lab_fk), array("id", "desc"), array(10, 0));
		
		$data['labdocksadd'] = $this->user_model->master_fun_get_tbl_val("lab_document",array('status' => 1,"type"=>'address',"lab_id" => $lab_fk), array("id", "desc"), array(10, 0));
		
        $data['lab_details']=$this->client_model->getclient_detils($lab_fk);
		$city=$data['lab_details'][0]['city'];
		$data['test_list']=$this->client_model->test_list($lab_fk,$city);
		/* master_fun_get_tbl_val("collect_from", array('status !=' => 0, "id" => $lab_fk), array("id", "asc")); */
		/* echo "<pre>";
		print_r($data['lab_details']); 
		die(); */
		
		$this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('client_details_view', $data);
        $this->load->view('footer');
	
	}


}

?>
