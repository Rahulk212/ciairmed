<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Package_Category extends CI_Controller {

    function __construct() {
        parent::__construct();
		$this->load->model('user_model');
        $this->load->model('package_category_model');
        $this->load->model('registration_admin_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
		$this->load->library('session');
        //$this->load->library('pushserver');
        //$this->load->library('email');
        //$this->load->helper('string');
        //echo current_url(); die();

        $data["login_data"] = logindata();
    }
	
	
	public function package_category_list()
	{
		$data["login_data"] = logindata();
		$cfname = $this->input->get('cfname');
		$data['cfname'] = $cfname;
		 if ($cfname != "") {
            $totalRows = $this->package_category_model->num_row($cfname);
			//echo $totalRows;die();
            $config = array();
            $get = $_GET;
			//print_r($get);die();
            unset($get['offset']);
            $config["base_url"] = base_url() . "package_category/package_category_list?" . http_build_query($get);
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 2;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->package_category_model->search($cfname, $config["per_page"], $page);
			//print_r($data);die();
            $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;
        } else{
			$totalRows = $this->package_category_model->num_row($cfname);
            $config = array();
            $get = $_GET;
			//print_r($get);die();
            $config["base_url"] = base_url() . "package_category/package_category_list";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 5;
            $config['page_query_string'] = TRUE;
            $config["uri_segment"] = 3;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
           // $sort = $this->input->get("sort");
            //$by = $this->input->get("by");
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->package_category_model->search($cfname, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;
		}
		$this->load->view('header');
		$this->load->view('nav',$data);
		$this->load->view('package_category_list',$data);
        $this->load->view('footer');
	}
	public function package_category_add()
	{
		$data["login_data"] = logindata();
		$this->form_validation->set_rules('pcname', ' PACKAGE CATEGORY NAME', 'trim|required');
		 if(empty($_FILES['open']['name'])){
		$this->form_validation->set_rules('open', ' IMAGE', 'trim|required');
		 }
		$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
		
		
		     if(!empty($_FILES['open']['name'])){
                $config['upload_path'] = './upload/package_category/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['file_name'] = $_FILES['open']['name'];
                
                //Load upload library and initialize configuration
                $this->load->library('upload',$config);
                $this->upload->initialize($config);
                
                if($this->upload->do_upload('open')){
                    $uploadData = $this->upload->data();
                    $picture = $uploadData['file_name'];
                }else{
                    $picture = '';
                }
            }else{
				$one = "empty";
                $picture = '';
            }
		
		
		if ($this->form_validation->run() != FALSE) {
			$name = $this->input->post('pcname');
			$slug = $this->package_category_model->create_unique_slug($name,"package_category","slug", "", "");
            
			$data=array(
			'name'=>$this->input->post('pcname'),
			'pic' => $picture,
			'slug' => $slug,
            'status' => '1');

            $data['query'] = $this->package_category_model->insert($data);
            $this->session->set_flashdata('success', "Package Category Successfully Added");
            redirect("package_category/package_category_list");
        }else{		
			$this->load->view('header');
			$this->load->view('nav',$data);
			$this->load->view('package_category_add');
			$this->load->view('footer');
		}
	}
	public function package_category_delete()
	{
		$pcid = $this->uri->segment('3');
		$data=array(
            'status' => '0');
        
        $data['query'] = $this->package_category_model->delete_pc($pcid, $data);
        $this->session->set_flashdata('success', 'package category Successfully Deleted');
        redirect("package_category/package_category_list");
	}
	public function package_category_edit()
	{
		 $data["login_data"] = logindata();
       // $data["cid"] = $this->uri->segment('3');
        $ccid = $this->uri->segment('3');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('pcname', 'PACKAGE CATEGORY NAME', 'trim|required');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
		
		  if(!empty($_FILES['open']['name'])){
                $config['upload_path'] = './upload/package_category/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['file_name'] = $_FILES['open']['name'];
                
                //Load upload library and initialize configuration
                $this->load->library('upload',$config);
                $this->upload->initialize($config);
                
                if($this->upload->do_upload('open')){
                    $uploadData = $this->upload->data();
                    $picture = $uploadData['file_name'];
                }else{
                    $picture = '';
                }
            }else{
                $picture = '';
            }
		
		
		
        if ($this->form_validation->run() == TRUE) {
			
	    if($picture)
		{	
			
            $data=array('name'=>$this->input->post('pcname'),'pic' => $picture);
            
            $data['view_data'] = $this->package_category_model->update($ccid, $data);
            $this->session->set_flashdata("success", "package category Successfully Updated.");
            redirect("package_category/package_category_list");
		}
       else
       {
	
	        $data=array('name'=>$this->input->post('pcname'));
            
            $data['view_data'] = $this->package_category_model->update($ccid, $data);
            $this->session->set_flashdata("success", "package category Successfully Updated.");
            redirect("package_category/package_category_list");
        }	
        } else {
            $data['view_data'] = $this->package_category_model->get_pc($ccid);
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('package_category_edit', $data, FALSE);
            $this->load->view('footer');
        }
	}
}
?>