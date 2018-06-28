<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Doctor_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('user_model');
        $this->load->model('doctor_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $data["login_data"] = logindata();
        $this->app_track();
    }

    function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }

    function doctor_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $name = $this->input->get('name');
        $mobile = $this->input->get('mobile');
        $email = $this->input->get('email');
        if ($name != "" || $email != "" || $mobile != "") {
            $srchdata = array("name" => $name, "email" => $email, "mobile" => $mobile);
            $data['name'] = $name;
            $data['mobile'] = $mobile;
            $data['email'] = $email;
            $totalRows = $this->doctor_model->doctorcount_list($srchdata);
            $config = array();
            $config["base_url"] = base_url() . "doctor_master/doctor_list/";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 50;
            $config["uri_segment"] = 3;
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $data["page"] = $page;
            $data["query"] = $this->doctor_model->doctorlist_list($srchdata, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        } else {
            $srchdata = array();
            $totalRows = $this->doctor_model->doctorcount_list($srchdata);
            $config = array();
            $config["base_url"] = base_url() . "doctor_master/doctor_list/";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 50;
            $config["uri_segment"] = 3;
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $data["page"] = $page;
            $data["query"] = $this->doctor_model->doctorlist_list($srchdata, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        }
        
                $data['state'] = $this->doctor_model->master_fun_get_tbl_val("state", array("status" => 1), array("id", "asc"));
            $data['city'] = $this->doctor_model->master_fun_get_tbl_val("city", array("status" => 1), array("id", "asc"));
    
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('doctor_list', $data);
        $this->load->view('footer');
    }

     function doctor_add() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['state_list'] = $this->doctor_model->master_fun_get_tbl_val("state", array("status" => 1), array("state_name", "asc"));
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        //$this->form_validation->set_rules('password', 'Password', 'trim|required');
        //$this->form_validation->set_rules('email', 'Email', 'trim|required');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required');
        //$this->form_validation->set_rules('address', 'Address', 'trim|required');
        //$this->form_validation->set_rules('city', 'City', 'trim|required');
        //$this->form_validation->set_rules('state', 'State', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $name = $this->input->post('name');
            $email = $this->input->post('email');
            $pass = $this->input->post('password');
            $mobile = $this->input->post('mobile');
            $mobile1 = $this->input->post('mobile1');
            $mobile2 = $this->input->post('mobile2');
            $add = $this->input->post('address');
            $city = $this->input->post('city');
            $state = $this->input->post('state');
            $notify = $this->input->post('notify');
            if($notify != 1) {
                $notify = 0;
            }
            $data['query'] = $this->doctor_model->master_fun_insert("doctor_master", array("city"=>$city,"state" =>$state,"full_name" => $name, "email" => $email, "mobile" => $mobile,"mobile1" => $mobile1,"mobile2" => $mobile2, "address" => $add, "password" => $pass,"notify" => $notify));
            $this->session->set_flashdata("success", array("Doctor successfully added."));
            redirect("doctor_master/doctor_list", "refresh");
        } else {
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('doctor_add', $data);
            $this->load->view('footer');
        }
    }

    function doctor_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->user_model->master_fun_update("doctor_master", array("id", $cid), array("status" => "0"));
        $this->session->set_flashdata("success", array("Doctor successfully deleted."));
        redirect("doctor_master/doctor_list", "refresh");
    }

    function doctor_active() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->user_model->master_fun_update("doctor_master", array("id", $cid), array("status" => "1"));
        $this->session->set_flashdata("success", array("Doctor successfully Activited."));
        redirect("doctor_master/doctor_list", "refresh");
    }

    function doctor_deactive() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->user_model->master_fun_update("doctor_master", array("id", $cid), array("status" => "2"));
        $this->session->set_flashdata("success", array("Doctor successfully Deactivited."));
        redirect("doctor_master/doctor_list", "refresh");
    }

    function doctor_edit() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["cid"] = $this->uri->segment('3');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        //$this->form_validation->set_rules('password', 'Password', 'trim|required');
        //$this->form_validation->set_rules('email', 'Email', 'trim|required');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required');
        //$this->form_validation->set_rules('address', 'Address', 'trim|required');
        //$this->form_validation->set_rules('city', 'City', 'trim|required');
        //$this->form_validation->set_rules('state', 'State', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $name = $this->input->post('name');
            $email = $this->input->post('email');
            $mobile = $this->input->post('mobile');
            $mobile1 = $this->input->post('mobile1');
            $mobile2 = $this->input->post('mobile2');
            $pass = $this->input->post('password');
            $add = $this->input->post('address');
            $city = $this->input->post('city');
            $state = $this->input->post('state');
            $notify = $this->input->post('notify');
            if($notify != 1) {
                $notify = 0;
            }
            $data['query'] = $this->doctor_model->master_fun_update("doctor_master", array("id", $data["cid"]), array("city"=>$city,"state" =>$state,"full_name" => $name, "email" => $email, "mobile" => $mobile,"mobile1" => $mobile1,"mobile2" => $mobile2, "address" => $add, "password" => $pass,"notify" => $notify));
            $this->session->set_flashdata("success", array("Doctor successfully updated."));
            redirect("doctor_master/doctor_list", "refresh");
        } else {
            $data['query'] = $this->doctor_model->master_fun_get_tbl_val("doctor_master", array("id" => $data["cid"]), array("id", "desc"));
            $data['state_list'] = $this->doctor_model->master_fun_get_tbl_val("state", array("status" => 1), array("state_name", "asc"));
            $data['city_list'] = $this->doctor_model->master_fun_get_tbl_val("city", array("state_fk" => $data['query'][0]['state']), array("city_name", "asc"));
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('doctor_edit', $data);
            $this->load->view('footer');
        }
    }
    function city_state_list() {
        $cid = $this->input->post('cid');
        $data = $this->doctor_model->master_fun_get_tbl_val("city", array("state_fk" => $cid), array("city_name", "asc"));
        echo '<option value="">--Select--</option>';
        foreach ($data as $all) {
            echo "<option value='".$all['id']."'>".$all['city_name']."</option>";
        }
    }
    
    public function import_list() {
        $cnt = 0;
        $this->load->library('csvimport');
          $state = $this->input->post('state');
            $city = $this->input->post('city');
        $file = $_FILES['id_browes']['name'];
        if ($file != "") {
            $file = $_FILES['id_browes']['name'];
            $filename = $_FILES['id_browes']['name'];
            $filename = md5(time()) . $filename;
            $output['status'] = FALSE;
            set_time_limit(0);
            $config['upload_path'] = "./upload/";
            $output['image_medium2'] = $config['upload_path'];
            $config['allowed_types'] = '*';
            $config['file_name'] = $filename;
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('id_browes')) {
                $error = array($this->upload->display_errors());
                $this->session->set_flashdata('success', array($error));
                if (!empty($this->session->userdata("test_master_r"))) {
                    redirect($this->session->userdata("test_master_r"), "refresh");
                } else {
                    redirect("b2b/logistic_test_master/test_list/" . $lid, "refresh");
                }
            } else {
                $file_data = $this->upload->data();
                $file_path = './upload/' . $file_data['file_name'];

                if ($this->csvimport->get_array($file_path)) {
                    $csv_array = $this->csvimport->get_array($file_path);
                    $cnt = 0;
                   $cnt2 = 0;
                   $cnt3 = 0;
                    $cnt4 = 0;
                 
                    foreach ($csv_array as $row) {
                        $cnt3++;
                        $old_test = $this->doctor_model->master_fun_get_tbl_val("doctor_master", array("status" => 1, "mobile" => $row['Mobile']), array("id", "desc"));
                        if (empty($old_test)) {
                           
                            if($row['Mobile']!=""){
                                 $cnt++;
                        $data['query'] = $this->doctor_model->master_fun_insert("doctor_master", array("full_name" => $row['Name'], "email" => $row['Email'], "mobile" => $row['Mobile'], "address" => $row['Address'], "state" => $state, "city" => $city));
                            } else {
                            $cnt2++;
                            }
                        } else {
                           $cnt4++;
                        }
                    }
                }
            }
        }
        if ($cnt == '0') {
           echo $ses = "Error occured";
            $this->session->set_flashdata('success', array($ses));
            if (!empty($this->session->userdata("test_master_r"))) {
            //    redirect($this->session->userdata("test_master_r"), "refresh");
            } else {
            //    redirect("b2b/logistic_test_master/test_list/" . $lid, "refresh");
            }
        } else {
         echo   $ses = array($cnt . "Doctor Added Successfully");
         echo "added".$cnt."<br> total".$cnt3."<br> non mobile".$cnt2."<br> allready".$cnt4;
            $this->session->set_flashdata('success', $ses);
            if (!empty($this->session->userdata("test_master_r"))) {
               // redirect($this->session->userdata("test_master_r"), "refresh");
            } else {
              //  redirect("b2b/logistic_test_master/test_list/" . $lid, "refresh");
            }
        }
    }


}

?>