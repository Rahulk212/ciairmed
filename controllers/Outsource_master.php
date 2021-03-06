<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Outsource_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('user_model');
        $this->load->model('outsource_model');
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

    function outsource_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $name = $this->input->get('name');
        $email = $this->input->get('email_id');
        if ($name != "" || $email != '') {
            $srchdata = array("name" => $name, "email" => $email);
            $data['name'] = $name;
            $data['email_id'] = $email;
            $totalRows = $this->outsource_model->outcount_list($srchdata);
            $config = array();
            $config["base_url"] = base_url() . "outsource_master/outsource_list/";
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
            $data["query"] = $this->outsource_model->outslist_list($srchdata, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        } else {
            $srchdata = array();
            $totalRows = $this->outsource_model->outcount_list($srchdata);
            $config = array();
            $config["base_url"] = base_url() . "outsource_master/outsource_list/";
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
            $data["query"] = $this->outsource_model->outslist_list($srchdata, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        }

        $data['state'] = $this->outsource_model->master_fun_get_tbl_val("state", array("status" => 1), array("id", "asc"));
        $data['city'] = $this->outsource_model->master_fun_get_tbl_val("city", array("status" => 1), array("id", "asc"));

        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('outsource_list', $data);
        $this->load->view('footer');
    }

    function outsource_add() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['state_list'] = $this->outsource_model->master_fun_get_tbl_val("state", array("status" => 1), array("state_name", "asc"));
        $data['branch_list'] = $this->outsource_model->master_fun_get_tbl_val("branch_master", array("status" => 1), array("branch_name", "asc"));
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        //$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        //$this->form_validation->set_rules('password', 'Password', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $name = $this->input->post('name');
            $add = $this->input->post('address');
            $city = $this->input->post('city');
            $state = $this->input->post('state');
            $branch = $this->input->post('branch');
            $data['query'] = $this->outsource_model->master_fun_insert("outsource_master", array("city_fk" => $city, "state_fk" => $state, "name" => $name, "address" => $add, "created_by" => $data["login_data"]["id"], "created_date" => date("Y-m-d H:i:s"),"email" => $email,"password" => $password,"branch_fk" => $branch));
            $this->session->set_flashdata("success", array("Outsource successfully added."));
            redirect("outsource_master/outsource_list", "refresh");
        } else {
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('outsource_add', $data);
            $this->load->view('footer');
        }
    }

    function outsource_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->user_model->master_fun_update("outsource_master", array("id", $cid), array("status" => "0"));
        $this->session->set_flashdata("success", array("Outsource successfully deleted."));
        redirect("outsource_master/outsource_list", "refresh");
    }

    function outsource_edit() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["cid"] = $this->uri->segment('3');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        //$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        //$this->form_validation->set_rules('password', 'Password', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $name = $this->input->post('name');
            $address = $this->input->post('address');
            $city = $this->input->post('city');
            $state = $this->input->post('state');
            $branch = $this->input->post('branch');
            $data['query'] = $this->outsource_model->master_fun_update("outsource_master", array("id", $data["cid"]), array("city_fk" => $city, "state_fk" => $state, "name" => $name, "address" => $address, "updated_by" => $data["login_data"]["id"], "updated_date" => date('Y-m-d H:i:s'),"email" => $email,"password" => $password,"branch_fk" => $branch));
            $this->session->set_flashdata("success", array("Outsource successfully updated."));
            redirect("outsource_master/outsource_list", "refresh");
        } else {
            $data['query'] = $this->outsource_model->master_fun_get_tbl_val("outsource_master", array("id" => $data["cid"]), array("id", "desc"));
            $data['state_list'] = $this->outsource_model->master_fun_get_tbl_val("state", array("status" => 1), array("state_name", "asc"));
            $data['city_list'] = $this->outsource_model->master_fun_get_tbl_val("city", array("state_fk" => $data['query'][0]['state_fk']), array("city_name", "asc"));
            $data['branch_list'] = $this->outsource_model->master_fun_get_tbl_val("branch_master", array("status" => 1), array("branch_name", "asc"));
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('outsource_edit', $data);
            $this->load->view('footer');
        }
    }

    function city_state_list() {
        $cid = $this->input->post('cid');
        $data = $this->outsource_model->master_fun_get_tbl_val("city", array("state_fk" => $cid), array("city_name", "asc"));
        echo '<option value="">--Select--</option>';
        foreach ($data as $all) {
            echo "<option value='" . $all['id'] . "'>" . $all['city_name'] . "</option>";
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
                        $old_test = $this->outsource_model->master_fun_get_tbl_val("doctor_master", array("status" => 1, "mobile" => $row['Mobile']), array("id", "desc"));
                        if (empty($old_test)) {

                            if ($row['Mobile'] != "") {
                                $cnt++;
                                $data['query'] = $this->outsource_model->master_fun_insert("doctor_master", array("full_name" => $row['Name'], "email" => $row['Email'], "mobile" => $row['Mobile'], "address" => $row['Address'], "state" => $state, "city" => $city));
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
            echo $ses = array($cnt . "Doctor Added Successfully");
            echo "added" . $cnt . "<br> total" . $cnt3 . "<br> non mobile" . $cnt2 . "<br> allready" . $cnt4;
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