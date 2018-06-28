<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Employee extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('user_model');
        $this->load->model('hrm/employee_model');
        $this->load->helper('string');
        //echo current_url(); die();

        $data["login_data"] = is_hrmlogin();
    }

    function index() {
        if (!is_hrmlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_hrmlogin();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $this->load->library('pagination');
        $search = $this->input->get('search');
        $data['search'] = $search;
        if ($search != "") {
            $total_row = $this->employee_model->search_num($search);
            $config = array();
            $config["base_url"] = base_url() . "hrm/employee/index?" . http_build_query($get);
            $config["total_rows"] = $total_row;
            $config["per_page"] = 10;
            $config["uri_segment"] = 3;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $config['page_query_string'] = TRUE;
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->employee_model->list_search($search, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        } else {
            $search = "";
            $totalRows = $this->employee_model->search_num($search);
            $config = array();
            $config["base_url"] = base_url() . "hrm/employee/index/";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 10;
            $config["uri_segment"] = 3;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data["query"] = $this->employee_model->list_search($search, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        }
        $this->load->view('hrm/header');
        $this->load->view('hrm/nav', $data);
        $this->load->view('hrm/employee_list', $data);
        $this->load->view('hrm/footer');
    }

    function add() {
        if (!is_hrmlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_hrmlogin();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $data['error'] = $this->session->flashdata("error");
        $data['error_doc'] = $this->session->flashdata("error_doc");
        $data['department_list'] = $this->employee_model->get_all('hrm_department', array("status" => 1));
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('employee_id', 'Employee ID', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $name = $this->input->post('name');
            $father = $this->input->post('father');
            $dob = $this->input->post('dob');
            $gender = $this->input->post('gender');
            $phone = $this->input->post('phone');
            $local_address = $this->input->post('local_address');
            $permanent_address = $this->input->post('permanent_address');
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $employee_id = $this->input->post('employee_id');
            $department = $this->input->post('department');
            $designation = $this->input->post('designation');
            $date_joining = $this->input->post('date_joining');
            $joinnig_salary = $this->input->post('joinnig_salary');
            $holder_name = $this->input->post('holder_name');
            $account_number = $this->input->post('account_number');
            $bank_name = $this->input->post('bank_name');
            $ifsc_code = $this->input->post('ifsc_code');
            $pan_number = $this->input->post('pan_number');
            $branch = $this->input->post('branch');
            $company_email = $this->input->post('company_email');
            $company_mobile = $this->input->post('company_mobile');
            //photo
            $files = $_FILES;
            $this->load->library('upload');
            $config['allowed_types'] = 'jpg|gif|png|jpeg';
            if ($files['photo']['name'] != "") {
                $config['upload_path'] = './upload/employee/';
                $config['file_name'] = time() . $files['photo']['name'];
                $this->upload->initialize($config);
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0755, TRUE);
                }
                if (!$this->upload->do_upload('photo')) {
                    $error = $this->upload->display_errors();
                    $error = str_replace("<p>", "", $error);
                    $error = str_replace("</p>", "", $error);
                    $this->session->set_flashdata("error", $error);
                    redirect("hrm/employee/add", "refresh");
                } else {
                    $doc_data = $this->upload->data();
                    $photo = $doc_data['file_name'];
                }
            }
            //photo
            //docs
            $files = $_FILES;
            $this->load->library('upload');
            $config['allowed_types'] = 'pdf|PDF|doc|DOC';
            if ($files['resume']['name'] != "") {
                $config['upload_path'] = './upload/employee/';
                $config['file_name'] = time() . $files['resume']['name'];
                $this->upload->initialize($config);
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0755, TRUE);
                }
                if (!$this->upload->do_upload('resume')) {
                    $error = $this->upload->display_errors();
                    $error = str_replace("<p>", "", $error);
                    $error = str_replace("</p>", "", $error);
                    $this->session->set_flashdata("error_doc", $error);
                    redirect("hrm/employee/add", "refresh");
                } else {
                    $doc_data = $this->upload->data();
                    $resume = $doc_data['file_name'];
                }
            }
            $files = $_FILES;
            $this->load->library('upload');
            $config['allowed_types'] = 'pdf|PDF|doc|DOC';
            if ($files['offer_letter']['name'] != "") {
                $config['upload_path'] = './upload/employee/';
                $config['file_name'] = time() . $files['offer_letter']['name'];
                $this->upload->initialize($config);
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0755, TRUE);
                }
                if (!$this->upload->do_upload('offer_letter')) {
                    $error = $this->upload->display_errors();
                    $error = str_replace("<p>", "", $error);
                    $error = str_replace("</p>", "", $error);
                    $this->session->set_flashdata("error_doc", $error);
                    redirect("hrm/employee/add", "refresh");
                } else {
                    $doc_data = $this->upload->data();
                    $offer_letter = $doc_data['file_name'];
                }
            }
            $files = $_FILES;
            $this->load->library('upload');
            $config['allowed_types'] = 'pdf|PDF|doc|DOC';
            if ($files['joining_letter']['name'] != "") {
                $config['upload_path'] = './upload/employee/';
                $config['file_name'] = time() . $files['joining_letter']['name'];
                $this->upload->initialize($config);
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0755, TRUE);
                }
                if (!$this->upload->do_upload('joining_letter')) {
                    $error = $this->upload->display_errors();
                    $error = str_replace("<p>", "", $error);
                    $error = str_replace("</p>", "", $error);
                    $this->session->set_flashdata("error_doc", $error);
                    redirect("hrm/employee/add", "refresh");
                } else {
                    $doc_data = $this->upload->data();
                    $joining_letter = $doc_data['file_name'];
                }
            }
            $files = $_FILES;
            $this->load->library('upload');
            $config['allowed_types'] = 'pdf|PDF|doc|DOC';
            if ($files['contract_agreement']['name'] != "") {
                $config['upload_path'] = './upload/employee/';
                $config['file_name'] = time() . $files['contract_agreement']['name'];
                $this->upload->initialize($config);
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0755, TRUE);
                }
                if (!$this->upload->do_upload('contract_agreement')) {
                    $error = $this->upload->display_errors();
                    $error = str_replace("<p>", "", $error);
                    $error = str_replace("</p>", "", $error);
                    $this->session->set_flashdata("error_doc", $error);
                    redirect("hrm/employee/add", "refresh");
                } else {
                    $doc_data = $this->upload->data();
                    $contract_agreement = $doc_data['file_name'];
                }
            }
            $files = $_FILES;
            $this->load->library('upload');
            $config['allowed_types'] = 'pdf|PDF|doc|DOC';
            if ($files['id_proof']['name'] != "") {
                $config['upload_path'] = './upload/employee/';
                $config['file_name'] = time() . $files['id_proof']['name'];
                $this->upload->initialize($config);
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0755, TRUE);
                }
                if (!$this->upload->do_upload('id_proof')) {
                    $error = $this->upload->display_errors();
                    $error = str_replace("<p>", "", $error);
                    $error = str_replace("</p>", "", $error);
                    $this->session->set_flashdata("error_doc", $error);
                    redirect("hrm/employee/add", "refresh");
                } else {
                    $doc_data = $this->upload->data();
                    $id_proof = $doc_data['file_name'];
                }
            }
            //docs
            $data1 = array(
                "name" => $name,
                "father_name" => $father,
                "photo" => $photo,
                "date_of_birth" => $dob,
                "gender" => $gender,
                "phone" => $phone,
                "address" => $local_address,
                "permanent_address" => $permanent_address,
                "email" => $email,
                "password" => $password,
                "employee_id" => $employee_id,
                "department" => $department,
                "designation" => $designation,
                "date_of_joining" => $date_joining,
                "joining_salary" => $joinnig_salary,
                "company_email" => $company_email,
                "company_mobile" => $company_mobile,
                "bank_holder_name" => $holder_name,
                "bank_account_number" => $account_number,
                "bank_name" => $bank_name,
                "ifsc_code" => $ifsc_code,
                "pan_number" => $pan_number,
                "branch" => $branch,
                "resume" => $resume,
                "offer_letter" => $offer_letter,
                "joining_letter" => $joining_letter,
                "contract_agreement" => $contract_agreement,
                "id_proof" => $id_proof,
                "active" => 1,
                "created_by" => $data["login_data"]["id"],
                "created_date" => date("Y-m-d H:i:s")
            );
            $insert = $this->employee_model->insert("hrm_employees", $data1);
            $this->session->set_flashdata("success", "Employee successfully added.");
            redirect("hrm/employee", "refresh");
        } else {
            $this->load->view('hrm/header', $data);
            $this->load->view('hrm/nav', $data);
            $this->load->view('hrm/employee_add', $data);
            $this->load->view('hrm/footer', $data);
        }
    }

    function delete($cid) {
        if (!is_hrmlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_hrmlogin();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['query'] = $this->employee_model->update("hrm_employees", array("id" => $cid), array("status" => '0'));
        $this->session->set_flashdata("success", "Employee successfully deleted.");
        redirect("hrm/employee", "refresh");
    }

    function edit($cid) {
        if (!is_hrmlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_hrmlogin();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['cid'] = $cid;
        $data['error'] = $this->session->flashdata("error");
        $data['error_doc'] = $this->session->flashdata("error_doc");
        $data['query'] = $this->employee_model->get_one("hrm_employees", array("id" => $cid, "status" => 1));
        $data['department_list'] = $this->employee_model->get_all('hrm_department', array("status" => 1));
        $data['designation_list'] = $this->employee_model->get_all('hrm_designation', array("status" => 1, "department_fk" => $data['query']->department));
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('employee_id', 'Employee ID', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $name = $this->input->post('name');
            $father = $this->input->post('father');
            $dob = $this->input->post('dob');
            $gender = $this->input->post('gender');
            $phone = $this->input->post('phone');
            $local_address = $this->input->post('local_address');
            $permanent_address = $this->input->post('permanent_address');
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $employee_id = $this->input->post('employee_id');
            $department = $this->input->post('department');
            $designation = $this->input->post('designation');
            $date_joining = $this->input->post('date_joining');
            $joinnig_salary = $this->input->post('joinnig_salary');
            $holder_name = $this->input->post('holder_name');
            $account_number = $this->input->post('account_number');
            $bank_name = $this->input->post('bank_name');
            $ifsc_code = $this->input->post('ifsc_code');
            $pan_number = $this->input->post('pan_number');
            $branch = $this->input->post('branch');
            $company_email = $this->input->post('company_email');
            $company_mobile = $this->input->post('company_mobile');
            //photo
            $files = $_FILES;
            $this->load->library('upload');
            $config['allowed_types'] = 'jpg|gif|png|jpeg';
            if ($files['photo']['name'] != "") {
                $config['upload_path'] = './upload/employee/';
                $config['file_name'] = time() . $files['photo']['name'];
                $this->upload->initialize($config);
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0755, TRUE);
                }
                if (!$this->upload->do_upload('photo')) {
                    $error = $this->upload->display_errors();
                    $error = str_replace("<p>", "", $error);
                    $error = str_replace("</p>", "", $error);
                    $this->session->set_flashdata("error", $error);
                    redirect("hrm/employee/edit/" . $cid, "refresh");
                } else {
                    $doc_data = $this->upload->data();
                    $photo = $doc_data['file_name'];
                }
            }
            if (empty($photo)) {
                $photo = $data['query']->photo;
            }
            //photo
            //docs
            $files = $_FILES;
            $this->load->library('upload');
            $config['allowed_types'] = 'pdf|PDF|doc|DOC';
            if ($files['resume']['name'] != "") {
                $config['upload_path'] = './upload/employee/';
                $config['file_name'] = time() . $files['resume']['name'];
                $this->upload->initialize($config);
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0755, TRUE);
                }
                if (!$this->upload->do_upload('resume')) {
                    $error = $this->upload->display_errors();
                    $error = str_replace("<p>", "", $error);
                    $error = str_replace("</p>", "", $error);
                    $this->session->set_flashdata("error_doc", $error);
                    redirect("hrm/employee/edit/" . $cid, "refresh");
                } else {
                    $doc_data = $this->upload->data();
                    $resume = $doc_data['file_name'];
                }
            }
            if (empty($resume)) {
                $resume = $data['query']->resume;
            }
            $files = $_FILES;
            $this->load->library('upload');
            $config['allowed_types'] = 'pdf|PDF|doc|DOC';
            if ($files['offer_letter']['name'] != "") {
                $config['upload_path'] = './upload/employee/';
                $config['file_name'] = time() . $files['offer_letter']['name'];
                $this->upload->initialize($config);
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0755, TRUE);
                }
                if (!$this->upload->do_upload('offer_letter')) {
                    $error = $this->upload->display_errors();
                    $error = str_replace("<p>", "", $error);
                    $error = str_replace("</p>", "", $error);
                    $this->session->set_flashdata("error_doc", $error);
                    redirect("hrm/employee/edit/" . $cid, "refresh");
                } else {
                    $doc_data = $this->upload->data();
                    $offer_letter = $doc_data['file_name'];
                }
            }
            if (empty($offer_letter)) {
                $offer_letter = $data['query']->offer_letter;
            }
            $files = $_FILES;
            $this->load->library('upload');
            $config['allowed_types'] = 'pdf|PDF|doc|DOC';
            if ($files['joining_letter']['name'] != "") {
                $config['upload_path'] = './upload/employee/';
                $config['file_name'] = time() . $files['joining_letter']['name'];
                $this->upload->initialize($config);
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0755, TRUE);
                }
                if (!$this->upload->do_upload('joining_letter')) {
                    $error = $this->upload->display_errors();
                    $error = str_replace("<p>", "", $error);
                    $error = str_replace("</p>", "", $error);
                    $this->session->set_flashdata("error_doc", $error);
                    redirect("hrm/employee/edit/" . $cid, "refresh");
                } else {
                    $doc_data = $this->upload->data();
                    $joining_letter = $doc_data['file_name'];
                }
            }
            if (empty($joining_letter)) {
                $joining_letter = $data['query']->joining_letter;
            }
            $files = $_FILES;
            $this->load->library('upload');
            $config['allowed_types'] = 'pdf|PDF|doc|DOC';
            if ($files['contract_agreement']['name'] != "") {
                $config['upload_path'] = './upload/employee/';
                $config['file_name'] = time() . $files['contract_agreement']['name'];
                $this->upload->initialize($config);
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0755, TRUE);
                }
                if (!$this->upload->do_upload('contract_agreement')) {
                    $error = $this->upload->display_errors();
                    $error = str_replace("<p>", "", $error);
                    $error = str_replace("</p>", "", $error);
                    $this->session->set_flashdata("error_doc", $error);
                    redirect("hrm/employee/edit/" . $cid, "refresh");
                } else {
                    $doc_data = $this->upload->data();
                    $contract_agreement = $doc_data['file_name'];
                }
            }
            if (empty($contract_agreement)) {
                $contract_agreement = $data['query']->contract_agreement;
            }
            $files = $_FILES;
            $this->load->library('upload');
            $config['allowed_types'] = 'pdf|PDF|doc|DOC';
            if ($files['id_proof']['name'] != "") {
                $config['upload_path'] = './upload/employee/';
                $config['file_name'] = time() . $files['id_proof']['name'];
                $this->upload->initialize($config);
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0755, TRUE);
                }
                if (!$this->upload->do_upload('id_proof')) {
                    $error = $this->upload->display_errors();
                    $error = str_replace("<p>", "", $error);
                    $error = str_replace("</p>", "", $error);
                    $this->session->set_flashdata("error_doc", $error);
                    redirect("hrm/employee/edit/" . $cid, "refresh");
                } else {
                    $doc_data = $this->upload->data();
                    $id_proof = $doc_data['file_name'];
                }
            }
            if (empty($id_proof)) {
                $id_proof = $data['query']->id_proof;
            }
            //docs
            $data1 = array(
                "name" => $name,
                "father_name" => $father,
                "photo" => $photo,
                "date_of_birth" => $dob,
                "gender" => $gender,
                "phone" => $phone,
                "address" => $local_address,
                "permanent_address" => $permanent_address,
                "email" => $email,
                "password" => $password,
                "employee_id" => $employee_id,
                "department" => $department,
                "designation" => $designation,
                "date_of_joining" => $date_joining,
                "joining_salary" => $joinnig_salary,
                "company_email" => $company_email,
                "company_mobile" => $company_mobile,
                "bank_holder_name" => $holder_name,
                "bank_account_number" => $account_number,
                "bank_name" => $bank_name,
                "ifsc_code" => $ifsc_code,
                "pan_number" => $pan_number,
                "branch" => $branch,
                "resume" => $resume,
                "offer_letter" => $offer_letter,
                "joining_letter" => $joining_letter,
                "contract_agreement" => $contract_agreement,
                "id_proof" => $id_proof,
                "updated_by" => $data["login_data"]["id"],
                "updated_date" => date("Y-m-d H:i:s")
            );
            $update = $this->employee_model->update("hrm_employees", array("id" => $cid), $data1);
            $this->session->set_flashdata("success", "Employee successfully added.");
            redirect("hrm/employee", "refresh");
        } else {
            $this->load->view('hrm/header', $data);
            $this->load->view('hrm/nav', $data);
            $this->load->view('hrm/employee_edit', $data);
            $this->load->view('hrm/footer', $data);
        }
    }

    function get_designation() {
        $did = $this->input->post('did');
        $result = $this->employee_model->get_all('hrm_designation', array("status" => 1, "department_fk" => $did));
        echo '<select class="form-control" name="designation" id="designation"><option value="">--Select--</option>';
        foreach ($result as $res) {
            echo '<option value="' . $res->id . '">' . ucwords($res->name) . '</option>';
        }
        echo '</select>';
    }

    function personal_edit($cid) {
        if (!is_hrmlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_hrmlogin();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['cid'] = $cid;
        $name = $this->input->post('name');
        $father = $this->input->post('father');
        $dob = $this->input->post('dob');
        $gender = $this->input->post('gender');
        $phone = $this->input->post('phone');
        $local_address = $this->input->post('local_address');
        $permanent_address = $this->input->post('permanent_address');
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        //photo
        $files = $_FILES;
        $this->load->library('upload');
        $config['allowed_types'] = 'jpg|gif|png|jpeg';
        if ($files['photo']['name'] != "") {
            $config['upload_path'] = './upload/employee/';
            $config['file_name'] = time() . $files['photo']['name'];
            $this->upload->initialize($config);
            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0755, TRUE);
            }
            if (!$this->upload->do_upload('photo')) {
                $error = $this->upload->display_errors();
                $error = str_replace("<p>", "", $error);
                $error = str_replace("</p>", "", $error);
                $this->session->set_flashdata("error", $error);
                redirect("hrm/employee/edit/" . $cid, "refresh");
            } else {
                $doc_data = $this->upload->data();
                $photo = $doc_data['file_name'];
            }
        }
        if (empty($photo)) {
            $photo = $data['query']->photo;
        }
        //photo
        $data1 = array(
            "name" => $name,
            "father_name" => $father,
            "photo" => $photo,
            "date_of_birth" => $dob,
            "gender" => $gender,
            "phone" => $phone,
            "address" => $local_address,
            "permanent_address" => $permanent_address,
            "email" => $email,
            "password" => $password,
            "updated_by" => $data["login_data"]["id"],
            "updated_date" => date("Y-m-d H:i:s")
        );
        $update = $this->employee_model->update("hrm_employees", array("id" => $cid), $data1);
        echo 1;
    }

    function company_edit($cid) {
        if (!is_hrmlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_hrmlogin();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $employee_id = $this->input->post('employee_id');
        $department = $this->input->post('department');
        $designation = $this->input->post('designation');
        $date_joining = $this->input->post('date_joining');
        $joinnig_salary = $this->input->post('joinnig_salary');
        $company_email = $this->input->post('company_email');
        $company_mobile = $this->input->post('company_mobile');
        $data1 = array(
            "employee_id" => $employee_id,
            "department" => $department,
            "designation" => $designation,
            "company_email" => $company_email,
            "company_mobile" => $company_mobile,
            "date_of_joining" => $date_joining,
            "joining_salary" => $joinnig_salary,
            "updated_by" => $data["login_data"]["id"],
            "updated_date" => date("Y-m-d H:i:s")
        );
        $update = $this->employee_model->update("hrm_employees", array("id" => $cid), $data1);
        echo 1;
    }

    function bank_account_edit($cid) {
        if (!is_hrmlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_hrmlogin();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $holder_name = $this->input->post('holder_name');
        $account_number = $this->input->post('account_number');
        $bank_name = $this->input->post('bank_name');
        $ifsc_code = $this->input->post('ifsc_code');
        $pan_number = $this->input->post('pan_number');
        $branch = $this->input->post('branch');
        $data1 = array(
            "bank_holder_name" => $holder_name,
            "bank_account_number" => $account_number,
            "bank_name" => $bank_name,
            "ifsc_code" => $ifsc_code,
            "pan_number" => $pan_number,
            "branch" => $branch,
            "updated_by" => $data["login_data"]["id"],
            "updated_date" => date("Y-m-d H:i:s")
        );
        $update = $this->employee_model->update("hrm_employees", array("id" => $cid), $data1);
        echo 1;
    }

    function document_edit($cid) {
        if (!is_hrmlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_hrmlogin();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['query'] = $this->employee_model->get_one("hrm_employees", array("id" => $cid, "status" => 1));
        //docs
        $files = $_FILES;
        $this->load->library('upload');
        $config['allowed_types'] = 'pdf|PDF|doc|DOC';
        if ($files['resume']['name'] != "") {
            $config['upload_path'] = './upload/employee/';
            $config['file_name'] = time() . $files['resume']['name'];
            $this->upload->initialize($config);
            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0755, TRUE);
            }
            if (!$this->upload->do_upload('resume')) {
                $error = $this->upload->display_errors();
                $error = str_replace("<p>", "", $error);
                $error = str_replace("</p>", "", $error);
                $this->session->set_flashdata("error_doc", $error);
                redirect("hrm/employee/edit/" . $cid, "refresh");
            } else {
                $doc_data = $this->upload->data();
                $resume = $doc_data['file_name'];
            }
        }
        if (empty($resume)) {
            $resume = $data['query']->resume;
        }
        $files = $_FILES;
        $this->load->library('upload');
        $config['allowed_types'] = 'pdf|PDF|doc|DOC';
        if ($files['offer_letter']['name'] != "") {
            $config['upload_path'] = './upload/employee/';
            $config['file_name'] = time() . $files['offer_letter']['name'];
            $this->upload->initialize($config);
            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0755, TRUE);
            }
            if (!$this->upload->do_upload('offer_letter')) {
                $error = $this->upload->display_errors();
                $error = str_replace("<p>", "", $error);
                $error = str_replace("</p>", "", $error);
                $this->session->set_flashdata("error_doc", $error);
                redirect("hrm/employee/edit/" . $cid, "refresh");
            } else {
                $doc_data = $this->upload->data();
                $offer_letter = $doc_data['file_name'];
            }
        }
        if (empty($offer_letter)) {
            $offer_letter = $data['query']->offer_letter;
        }
        $files = $_FILES;
        $this->load->library('upload');
        $config['allowed_types'] = 'pdf|PDF|doc|DOC';
        if ($files['joining_letter']['name'] != "") {
            $config['upload_path'] = './upload/employee/';
            $config['file_name'] = time() . $files['joining_letter']['name'];
            $this->upload->initialize($config);
            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0755, TRUE);
            }
            if (!$this->upload->do_upload('joining_letter')) {
                $error = $this->upload->display_errors();
                $error = str_replace("<p>", "", $error);
                $error = str_replace("</p>", "", $error);
                $this->session->set_flashdata("error_doc", $error);
                redirect("hrm/employee/edit/" . $cid, "refresh");
            } else {
                $doc_data = $this->upload->data();
                $joining_letter = $doc_data['file_name'];
            }
        }
        if (empty($joining_letter)) {
            $joining_letter = $data['query']->joining_letter;
        }
        $files = $_FILES;
        $this->load->library('upload');
        $config['allowed_types'] = 'pdf|PDF|doc|DOC';
        if ($files['contract_agreement']['name'] != "") {
            $config['upload_path'] = './upload/employee/';
            $config['file_name'] = time() . $files['contract_agreement']['name'];
            $this->upload->initialize($config);
            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0755, TRUE);
            }
            if (!$this->upload->do_upload('contract_agreement')) {
                $error = $this->upload->display_errors();
                $error = str_replace("<p>", "", $error);
                $error = str_replace("</p>", "", $error);
                $this->session->set_flashdata("error_doc", $error);
                redirect("hrm/employee/edit/" . $cid, "refresh");
            } else {
                $doc_data = $this->upload->data();
                $contract_agreement = $doc_data['file_name'];
            }
        }
        if (empty($contract_agreement)) {
            $contract_agreement = $data['query']->contract_agreement;
        }
        $files = $_FILES;
        $this->load->library('upload');
        $config['allowed_types'] = 'pdf|PDF|doc|DOC';
        if ($files['id_proof']['name'] != "") {
            $config['upload_path'] = './upload/employee/';
            $config['file_name'] = time() . $files['id_proof']['name'];
            $this->upload->initialize($config);
            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0755, TRUE);
            }
            if (!$this->upload->do_upload('id_proof')) {
                $error = $this->upload->display_errors();
                $error = str_replace("<p>", "", $error);
                $error = str_replace("</p>", "", $error);
                $this->session->set_flashdata("error_doc", $error);
                redirect("hrm/employee/edit/" . $cid, "refresh");
            } else {
                $doc_data = $this->upload->data();
                $id_proof = $doc_data['file_name'];
            }
        }
        if (empty($id_proof)) {
            $id_proof = $data['query']->id_proof;
        }
        //docs
        $data1 = array(
            "resume" => $resume,
            "offer_letter" => $offer_letter,
            "joining_letter" => $joining_letter,
            "contract_agreement" => $contract_agreement,
            "id_proof" => $id_proof,
            "updated_by" => $data["login_data"]["id"],
            "updated_date" => date("Y-m-d H:i:s")
        );
        $update = $this->employee_model->update("hrm_employees", array("id" => $cid), $data1);
        if($update) {
            $rsm = "'resume'";
        $offr = "'offer'";
        $jng = "'joining'";
        $cntct = "'contract'";
        $prf = "'proof'";
            $query = $this->employee_model->get_one("hrm_employees", array("id" => $cid, "status" => 1));
            echo '<div class="form-group col-sm-12  pdng_0">';
                                                    if ($query->resume != '') {
                                                        echo '<a class="btn btn-primary" style="background-color:#bf2d37;" href="'.base_url().'upload/employee/'.$query->resume.'" target="_blank">View Resume</a>
                                                        <a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Remove" onclick="document_delete_data('.$rsm.');" class="btn btn-primary" style="background-color:#bf2d37;"><i class="fa fa-trash-o"></i></a>';
                                                    }
                                                echo '</div>
                                                <div class="form-group col-sm-12  pdng_0">';
                                                    if ($query->offer_letter != '') {
                                                        echo '<a class="btn btn-primary" style="background-color:#bf2d37;" href="'.base_url().'upload/employee/'.$query->offer_letter.'" target="_blank">Offer Letter</a>
                                                            <a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Remove" onclick="document_delete_data('.$offr.');" class="btn btn-primary" style="background-color:#bf2d37;"><i class="fa fa-trash-o"></i></a>';
                                                    }
                                                echo '</div>
                                                <div class="form-group col-sm-12  pdng_0">';
                                                    if ($query->joining_letter != '') {
                                                        echo '<a class="btn btn-primary" style="background-color:#bf2d37;" href="'.base_url().'upload/employee/'.$query->joining_letter.'" target="_blank">Joining Letter</a>
                                                            <a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Remove" onclick="document_delete_data('.$jng.');" class="btn btn-primary" style="background-color:#bf2d37;"><i class="fa fa-trash-o"></i></a>';
                                                    }
                                                echo '</div>
                                                <div class="form-group col-sm-12  pdng_0">';
                                                    if ($query->contract_agreement != '') {
                                                        echo '<a class="btn btn-primary" style="background-color:#bf2d37;" href="'.base_url().'upload/employee/'.$query->contract_agreement.'" target="_blank">View Contract</a>
                                                            <a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Remove" onclick="document_delete_data('.$cntct.');" class="btn btn-primary" style="background-color:#bf2d37;"><i class="fa fa-trash-o"></i></a>';
                                                    }
                                                echo '</div>
                                                <div class="form-group col-sm-12  pdng_0">';
                                                    if ($query->id_proof != '') {
                                                        echo '<a class="btn btn-primary" style="background-color:#bf2d37;" href="'.base_url().'upload/employee/'.$query->id_proof.'" target="_blank">View ID Proof</a>
                                                            <a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Remove" onclick="document_delete_data('.$prf.');" class="btn btn-primary" style="background-color:#bf2d37;"><i class="fa fa-trash-o"></i></a>';
                                                    }
                                                echo '</div>';
        }
    }
    function document_delete($cid) {
        if (!is_hrmlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_hrmlogin();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $which = $this->input->post('types');
        if($which == 'resume') {
            $update = $this->employee_model->update("hrm_employees", array("id" => $cid), array("resume" => ""));
        } else if($which == 'offer') {
            $update = $this->employee_model->update("hrm_employees", array("id" => $cid), array("offer_letter" => ""));
        } else if($which == 'joining') {
            $update = $this->employee_model->update("hrm_employees", array("id" => $cid), array("joining_letter" => ""));
        } else if($which == 'contract') {
            $update = $this->employee_model->update("hrm_employees", array("id" => $cid), array("contract_agreement" => ""));
        } else if($which == 'proof') {
            $update = $this->employee_model->update("hrm_employees", array("id" => $cid), array("id_proof" => ""));
        }
        if($update) {
            $rsm = "'resume'";
        $offr = "'offer'";
        $jng = "'joining'";
        $cntct = "'contract'";
        $prf = "'proof'";
            $query = $this->employee_model->get_one("hrm_employees", array("id" => $cid, "status" => 1));
            echo '<div class="form-group col-sm-12  pdng_0">';
                                                    if ($query->resume != '') {
                                                        echo '<a class="btn btn-primary" style="background-color:#bf2d37;" href="'.base_url().'upload/employee/'.$query->resume.'" target="_blank">View Resume</a>
                                                        <a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Remove" onclick="document_delete_data('.$rsm.');" class="btn btn-primary" style="background-color:#bf2d37;"><i class="fa fa-trash-o"></i></a>';
                                                    }
                                                echo '</div>
                                                <div class="form-group col-sm-12  pdng_0">';
                                                    if ($query->offer_letter != '') {
                                                        echo '<a class="btn btn-primary" style="background-color:#bf2d37;" href="'.base_url().'upload/employee/'.$query->offer_letter.'" target="_blank">Offer Letter</a>
                                                            <a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Remove" onclick="document_delete_data('.$offr.');" class="btn btn-primary" style="background-color:#bf2d37;"><i class="fa fa-trash-o"></i></a>';
                                                    }
                                                echo '</div>
                                                <div class="form-group col-sm-12  pdng_0">';
                                                    if ($query->joining_letter != '') {
                                                        echo '<a class="btn btn-primary" style="background-color:#bf2d37;" href="'.base_url().'upload/employee/'.$query->joining_letter.'" target="_blank">Joining Letter</a>
                                                            <a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Remove" onclick="document_delete_data('.$jng.');" class="btn btn-primary" style="background-color:#bf2d37;"><i class="fa fa-trash-o"></i></a>';
                                                    }
                                                echo '</div>
                                                <div class="form-group col-sm-12  pdng_0">';
                                                    if ($query->contract_agreement != '') {
                                                        echo '<a class="btn btn-primary" style="background-color:#bf2d37;" href="'.base_url().'upload/employee/'.$query->contract_agreement.'" target="_blank">View Contract</a>
                                                            <a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Remove" onclick="document_delete_data('.$cntct.');" class="btn btn-primary" style="background-color:#bf2d37;"><i class="fa fa-trash-o"></i></a>';
                                                    }
                                                echo '</div>
                                                <div class="form-group col-sm-12  pdng_0">';
                                                    if ($query->id_proof != '') {
                                                        echo '<a class="btn btn-primary" style="background-color:#bf2d37;" href="'.base_url().'upload/employee/'.$query->id_proof.'" target="_blank">View ID Proof</a>
                                                            <a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Remove" onclick="document_delete_data('.$prf.');" class="btn btn-primary" style="background-color:#bf2d37;"><i class="fa fa-trash-o"></i></a>';
                                                    }
                                                echo '</div>';
        }
    }

}

?>