<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Hiten_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('user_model');
        $this->load->model('user_test_master_model');
        $this->load->model('job_model');
        $this->load->model('test_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('pushserver');
        $this->load->library('email');
        $this->load->model('registration_admin_model');
        $this->load->helper('string');
        $data["login_data"] = logindata();
        $this->app_track();
    }
    function index(){
    
    }

    function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }
    function sss(){
        $this->benchmark->mark('code_start');

// Some code happens here

$this->benchmark->mark('code_end');

echo $this->benchmark->elapsed_time('code_start', 'code_end');
        
    }

    function pending_list2() {
        
    $this->benchmark->mark('code_start');
        
           $search_data = array();
        $user = $data['user2'] = $search_data["user"] = $this->input->get('user');
        $date = $data['date2'] = $search_data["date"] = $this->input->get('date');
        $end_date = $data['end_date'] = $search_data["end_date"] = $this->input->get("end_date");
        $p_oid = $data['p_oid'] = $search_data["p_oid"] = $this->input->get('p_oid');
        $p_ref = $data['p_ref'] = $search_data["p_ref"] = $this->input->get('p_ref');
        $mobile = $data['mobile'] = $search_data["mobile"] = $this->input->get('mobile');
        $referral_by = $data['referral_by'] = $search_data["referral_by"] = $this->input->get('referral_by');
        $status = $data['statusid'] = $search_data["status"] = $this->input->get('status');
        $branch = $data['branch'] = $search_data["branch"] = $data["branch"] = $this->input->get('branch');
        $payment = $data['payment2'] = $search_data["payment"] = $data["payment"] = $this->input->get('payment');
        $test_pack = $data['test_pack'] = $search_data["test_pack"] = $this->input->get('test_package');
        $city = $data['tcity'] = $search_data["city"] = $this->input->get('city');

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['branchlist'] = $this->registration_admin_model->get_val("SELECT * from branch_master where status='1'");
        $cntr_arry = array();
        if (!empty($data["login_data"]['branch_fk'])) {
            foreach ($data["login_data"]['branch_fk'] as $key) {
                $cntr_arry[] = $key["branch_fk"];
            }
//print_r($cntr_arry); die();
            $data['branchlist'] = $this->registration_admin_model->get_val("SELECT * from branch_master where status='1' and id in (" . implode(",", $cntr_arry) . ")");
        }
        $data['test_cities'] = $this->registration_admin_model->get_val("SELECT * from test_cities where status='1'");
        $test_packages = explode("_", $test_pack);
        $alpha = $test_packages[0];
        $tp_id = $test_packages[1];
        if ($alpha == 't') {
            $t_id = $tp_id;
        }
        if ($alpha == 'p') {
            $p_id = $tp_id;
        }
        $data['success'] = $this->session->flashdata("success");

        $search_data['cntr_arry'] = $cntr_arry;
        $total_row = $this->job_model->num_srch_job_list($cntr_arry);
        
        $total_row = 500;
        $config["base_url"] = base_url() . "job-master/pending-list";
        $config["total_rows"] = $total_row;
        $config["per_page"] = 100;
        $config['page_query_string'] = TRUE;
        $config['cur_tag_open'] = '<span>';
        $config['cur_tag_close'] = '</span>';
        $config['next_link'] = 'Next &rsaquo;';
        $config['prev_link'] = '&lsaquo; Previous';
        $this->pagination->initialize($config);
        $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;


        $res = $this->job_model->srch_job_list_get_id($config["per_page"], $page, $search_data);

        $datapass = array();
        foreach ($res as $r) {
            $datapass[] = $r['id'];
        }
        $search_data['idofdata'] = $datapass;
        $data['query'] = $this->job_model->srch_job_list($config["per_page"], $page, $search_data);
        
        $data["links"] = $this->pagination->create_links();
        $data["pages"] = $page;

        $data['customer'] = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1), array("full_name", "asc"));
                $data['city'] = $this->job_model->master_fun_get_tbl_val("city", array('status' => 1), array("id", "asc"));

        $cnt = 0;
        foreach ($data['query'] as $key) {
            $w_prc = 0;
            /* Count booked test price */
            $booked_tests = $this->job_model->master_fun_get_tbl_val("wallet_master", array('job_fk' => $key["id"]), array("id", "asc"));
            $emergency_tests = $this->job_model->master_fun_get_tbl_val("booking_info", array('id' => $key["booking_info"]), array("id", "asc"));
            $f_data = $this->job_model->master_fun_get_tbl_val("customer_family_master", array('id' => $emergency_tests[0]["family_member_fk"]), array("id", "asc"));
            $f_data1 = $this->job_model->master_fun_get_tbl_val("relation_master", array('id' => $f_data[0]["relation_fk"]), array("id", "asc"));
            $doctor_data = $this->job_model->master_fun_get_tbl_val("doctor_master", array('id' => $key["doctor"]), array("id", "asc"));
            $relation = "Self";
            if (!empty($f_data1)) {
                $relation = ucfirst($f_data[0]["name"] . " (" . $f_data1[0]["name"] . ")");
                $data['query'][$cnt]["rphone"] = $f_data[0]["phone"];
            }
            $data['query'][$cnt]["relation"] = $relation;
            foreach ($booked_tests as $tkey) {
                if ($tkey["debit"]) {
                    $w_prc = $w_prc + $tkey["debit"];
                }
            }
            $upload_data = $this->job_model->master_fun_get_tbl_val("report_master", array('job_fk' => $key["id"]), array("id", "asc"));
            $data['query'][$cnt]["report"] = $upload_data[0]["original"];
            $data['query'][$cnt]["emergency"] = $emergency_tests[0]["emergency"];
            $data['query'][$cnt]["cut_from_wallet"] = $w_prc;
            $data['query'][$cnt]["doctor_name"] = $doctor_data[0]["full_name"];
            $data['query'][$cnt]["doctor_mobile"] = $doctor_data[0]["mobile"];
            $package_ids = $this->job_model->get_job_booking_package($key["id"]);
            if (trim($package_ids) != '') {
                $data['query'][$cnt]["packagename"] = $package_ids;
            }
            $cnt++;
        }
           $url = "http://" . $_SERVER['HTTP_HOST'] . ":" . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
        $this->session->set_userdata("job_master_r", $url);
     
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('pending_job_list', $data);
        $this->load->view('footer');

$this->benchmark->mark('code_end');

echo "<h1>".$this->benchmark->elapsed_time('code_start', 'code_end')."</h1>";
    }

}