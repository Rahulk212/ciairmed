<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Btob_job_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('user_model');
        $this->load->model('user_test_master_model');
        $this->load->model('btob_job_model');
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

    function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }

    function sss() {
        $this->benchmark->mark('code_start');

// Some code happens here

        $this->benchmark->mark('code_end');

        echo $this->benchmark->elapsed_time('code_start', 'code_end');
    }

    function pending_list_old() {
        $this->benchmark->mark('code_start');
        if (!is_loggedin()) {
            redirect('login');
        }
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

        if ($user != "" || $date != "" || $end_date != '' || $p_oid != '' || $p_ref != "" || $mobile != "" || $referral_by != "" || $status != "" || $branch != "" || $payment != "" || $test_pack != '' || $city != '') {
            if ($statusid == '0') {
                $data["deleted_selected"] = 1;
            }
            if ($branch != '') {
                $cntr_arry = array();
                $cntr_arry = $branch;
            }
            $search_data['cntr_arry'] = $cntr_arry;
            $search_data['t_id'] = $t_id;
            $search_data['p_id'] = $p_id;
            $total_row = $this->btob_job_model->num_row_srch_job_list($search_data);
            $config = array();
            $get = $_GET;
            unset($get['offset']);
            $config["base_url"] = base_url() . "job-master/pending-list?" . http_build_query($get);
            $config["total_rows"] = $total_row;
            $config["per_page"] = 100;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->btob_job_model->row_srch_job_list($search_data, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;
        } else {
            $search_data['cntr_arry'] = $cntr_arry;
            $total_row = $this->btob_job_model->num_srch_job_list($cntr_arry);
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

            $res = $this->btob_job_model->srch_job_list_get_id($config["per_page"], $page, $search_data);
            $datapass = array();
            foreach ($res as $r) {
                $datapass[] = $r['id'];
            }
            $search_data['idofdata'] = $datapass;
            $data['query'] = $this->btob_job_model->srch_job_list($config["per_page"], $page, $search_data);
            $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;
        }
        $cnt = 0;
        foreach ($data['query'] as $key) {
            $w_prc = 0;
            /* Count booked test price */
            $booked_tests = $this->btob_job_model->master_fun_get_tbl_val("wallet_master", array('job_fk' => $key["id"]), array("id", "asc"));
            $emergency_tests = $this->btob_job_model->master_fun_get_tbl_val("booking_info", array('id' => $key["booking_info"]), array("id", "asc"));
            $f_data = $this->btob_job_model->master_fun_get_tbl_val("customer_family_master", array('id' => $emergency_tests[0]["family_member_fk"]), array("id", "asc"));
            $f_data1 = $this->btob_job_model->master_fun_get_tbl_val("relation_master", array('id' => $f_data[0]["relation_fk"]), array("id", "asc"));
            $doctor_data = $this->btob_job_model->master_fun_get_tbl_val("doctor_master", array('id' => $key["doctor"]), array("id", "asc"));
            $data['query'][$cnt]["send_repor_sms"] = $this->btob_job_model->master_fun_get_tbl_val("send_report_sms", array('job_fk' => $key["id"], "status" => "1"), array("id", "asc"));
            $data['query'][$cnt]["send_report_mail"] = $this->btob_job_model->master_fun_get_tbl_val("send_report_mail", array('job_fk' => $key["id"], "status" => "1"), array("id", "asc"));
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
            $upload_data = $this->btob_job_model->master_fun_get_tbl_val("report_master", array('job_fk' => $key["id"]), array("id", "asc"));
            $job_test_list = $this->btob_job_model->get_val("SELECT `job_test_list_master`.*,`test_master`.`test_name` FROM `job_test_list_master` INNER JOIN `test_master` ON `job_test_list_master`.`test_fk`=`test_master`.`id` WHERE `job_test_list_master`.`job_fk`='" . $key["id"] . "'");
            $data['query'][$cnt]["job_test_list"] = $job_test_list;
            $data['query'][$cnt]["report"] = $upload_data[0]["original"];
            $data['query'][$cnt]["emergency"] = $emergency_tests[0]["emergency"];
            $data['query'][$cnt]["cut_from_wallet"] = $w_prc;
            $data['query'][$cnt]["doctor_name"] = $doctor_data[0]["full_name"];
            $data['query'][$cnt]["doctor_mobile"] = $doctor_data[0]["mobile"];
            $package_ids = $this->btob_job_model->get_job_booking_package($key["id"]);
            if (trim($package_ids) != '') {
                $data['query'][$cnt]["packagename"] = $package_ids;
            }
            $cnt++;
        }
//echo "<pre>"; print_R($data['query']); die();
        $url = "http://" . $_SERVER['HTTP_HOST'] . ":" . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
        $this->session->set_userdata("job_master_r", $url);
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('pending_job_list', $data);
        $this->load->view('footer');

        $this->benchmark->mark('code_end');
        if ($_GET["debug"] == 1) {
            echo "<h1>" . $this->benchmark->elapsed_time('code_start', 'code_end') . "</h1>";
        }
    }

    /* New Job List Start */

    function pending_list() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $search_data = array();

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['branchlist'] = $this->registration_admin_model->get_val("SELECT * from branch_master where status='1'");
        $cntr_arry = array();
        if (!empty($data["login_data"]['branch_fk'])) {
            foreach ($data["login_data"]['branch_fk'] as $key) {
                $cntr_arry[] = $key["branch_fk"];
            }
            $data['branchlist'] = $this->registration_admin_model->get_val("SELECT * from branch_master where status='1' and id in (" . implode(",", $cntr_arry) . ")");
            foreach ($data['branchlist'] as $branchs) {
                $cntr_arry1[] = $branchs["city"];
            }
            $data['citylist'] = $this->registration_admin_model->get_val("SELECT * from test_cities where status='1' and id in (" . implode(",", $cntr_arry1) . ")");
            foreach ($data['citylist'] as $cts) {
                $cntr_arry2[] = $cts["city_fk"];
            }
            $data['search_c'] = $cntr_arry1;
        }
        /* Check test city */
        $allow_test_city = array();
        if ($data["login_data"]["type"] == 1) {
            $u_allow_city = $this->registration_admin_model->get_val("SELECT * from user_test_city where user_fk='" . $data["login_data"]["id"] . "' and status='1'");
            $allow_city = array();
            foreach ($u_allow_city as $a_key) {
                $allow_city[] = $a_key["test_city"];
            }
        }
        /* End */
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
        $search_data['allow_city'] = $allow_city;
        if ($_REQUEST["debug"] == 1) {
            print_r($data['search_c']);
            $this->db->close();
            die();
        }
        $data['success'] = $this->session->flashdata("success");
        $url = "http://" . $_SERVER['HTTP_HOST'] . ":" . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
        $this->session->set_userdata("job_master_r", $url);
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('btob_pending_job_list_new', $data);
        $this->load->view('footer');
    }

    function pending_list_search() {
        $this->benchmark->mark('code_start');
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->library("util");
        $util = new Util;
//        echo "<pre>"; print_r($_POST); die();
        $search_data = array();
        $user = $data['user2'] = $search_data["user"] = $this->input->get_post('user');
        $date = $data['date2'] = $search_data["date"] = $this->input->get_post('date');
        $end_date = $data['end_date'] = $search_data["end_date"] = $this->input->get_post("end_date");
        $p_oid = $data['p_oid'] = $search_data["p_oid"] = $this->input->get_post('p_oid');
        $p_ref = $data['p_ref'] = $search_data["p_ref"] = $this->input->get_post('p_ref');
        $mobile = $data['mobile'] = $search_data["mobile"] = $this->input->get_post('mobile');
        $referral_by = $data['referral_by'] = $search_data["referral_by"] = $this->input->get_post('referral_by');
        $status = $data['statusid'] = $search_data["status"] = $this->input->get_post('status');
        $branch = $data['branch'] = $search_data["branch"] = $data["branch"] = $this->input->get_post('branch');
        $payment = $data['payment2'] = $search_data["payment"] = $data["payment"] = $this->input->get_post('payment');
        $test_pack = $data['test_pack'] = $search_data["test_pack"] = $this->input->get_post('test_package');
        $city = $data['tcity'] = $search_data["city"] = $this->input->get_post('city');
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cntr_arry = array();
        if (!empty($data["login_data"]['branch_fk'])) {
            foreach ($data["login_data"]['branch_fk'] as $key) {
                $cntr_arry[] = $key["branch_fk"];
            }
        }
        $data['branchlist'] = $this->registration_admin_model->get_val("SELECT * from branch_master where status='1'");
        /* Check test city */
        $allow_test_city = array();
        if ($data["login_data"]["type"] == 1) {
            $u_allow_city = $this->registration_admin_model->get_val("SELECT * from user_test_city where user_fk='" . $data["login_data"]["id"] . "' and status='1'");
            $allow_city = array();
            foreach ($u_allow_city as $a_key) {
                $allow_city[] = $a_key["test_city"];
            }
        }
        /* End */

        $search_data['allow_city'] = $allow_city;
        $test_packages = explode("_", $test_pack);
        $alpha = $test_packages[0];
        $tp_id = $test_packages[1];
        if ($alpha == 't') {
            $t_id = $tp_id;
        }
        if ($alpha == 'p') {
            $p_id = $tp_id;
        }

        if ($statusid == '0') {
            $data["deleted_selected"] = 1;
        }
        if ($branch != '') {
            $cntr_arry = array();
            $cntr_arry = $branch;
        }
        $search_data['cntr_arry'] = $cntr_arry;
        $search_data['t_id'] = $t_id;
        $search_data['p_id'] = $p_id;

        $data['query'] = $this->btob_job_model->new_row_srch_job_list($search_data, $config["per_page"], $page);
        $cnt = 0;
        foreach ($data['query'] as $key) {
            $w_prc = 0;
            /* Count booked test price */
              $emergency_tests = $this->btob_job_model->master_fun_get_tbl_val("booking_info", array('id' => $key["booking_info"]), array("id", "asc"));
            $f_data = $this->btob_job_model->master_fun_get_tbl_val("customer_family_master", array('id' => $emergency_tests[0]["family_member_fk"]), array("id", "asc"));
            //$doctor_data = $this->btob_job_model->master_fun_get_tbl_val("doctor_master", array('id' => $key["doctor"]), array("id", "asc"));
             //  $data['query'][$cnt]["send_repor_sms"] = $this->btob_job_model->master_fun_get_tbl_val("send_report_sms", array('job_fk' => $key["id"], "status" => "1"), array("id", "asc"));
            // $data['query'][$cnt]["send_report_mail"] = $this->btob_job_model->master_fun_get_tbl_val("send_report_mail", array('job_fk' => $key["id"], "status" => "1"), array("id", "asc"));
            /* Nishit 11-8 start */
            if ($data["login_data"]["type"] != 1 && $data["login_data"]["type"] != 2) {
             //   $permission = $this->btob_job_model->get_val("SELECT is_print FROM `report_print_permission` WHERE `status`='1' AND `type`='" . $data["login_data"]["type"] . "' AND branch='" . $key["branch_fk"] . "'");
                $data['query'][$cnt]["is_print"] = $permission[0]["is_print"];
            } else {
                $data['query'][$cnt]["is_print"] = 1;
            }
            /* End */
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

            // pinkesh
            if (empty($f_data)) {
                $age = $util->get_age($key["dob"]);
                if ($key['gender'] == 'male') {
                    $data['query'][$cnt]["gender"] = 'M';
                } else if ($key['gender'] == 'female') {
                    $data['query'][$cnt]["gender"] = 'F';
                }
                if ($age[0] != 0) {
                    $data['query'][$cnt]["age"] = $age[0];
                    $data['query'][$cnt]["age_type"] = 'Y';
                }
                if ($age[0] == 0 && $age[1] != 0) {
                    $data['query'][$cnt]["age"] = $age[1];
                    $data['query'][$cnt]["age_type"] = 'M';
                }
                if ($age[0] == 0 && $age[1] == 0 && $age[2] != 0) {
                    $data['query'][$cnt]["age"] = $age[2];
                    $data['query'][$cnt]["age_type"] = 'D';
                }
                if ($age[0] == 0 && $age[1] == 0 && $age[2] == 0) {
                    $data['query'][$cnt]["age"] = '0';
                    $data['query'][$cnt]["age_type"] = 'D';
                }
            } else {
                $age = $util->get_age($f_data[0]["dob"]);
                if ($f_data[0]['gender'] == 'male') {
                    $data['query'][$cnt]["gender"] = 'M';
                } else if ($f_data[0]['gender'] == 'female') {
                    $data['query'][$cnt]["gender"] = 'F';
                }
                if ($age[0] != 0) {
                    $data['query'][$cnt]["age"] = $age[0];
                    $data['query'][$cnt]["age_type"] = 'Y';
                }
                if ($age[0] == 0 && $age[1] != 0) {
                    $data['query'][$cnt]["age"] = $age[1];
                    $data['query'][$cnt]["age_type"] = 'M';
                }
                if ($age[0] == 0 && $age[1] == 0 && $age[2] != 0) {
                    $data['query'][$cnt]["age"] = $age[2];
                    $data['query'][$cnt]["age_type"] = 'D';
                }

                if ($age[0] == 0 && $age[1] == 0 && $age[2] == 0) {
                    $data['query'][$cnt]["age"] = '0';
                    $data['query'][$cnt]["age_type"] = 'D';
                }
            }
            //print_r($age); die();
            // pinkesh code end
           // $upload_data = $this->btob_job_model->master_fun_get_tbl_val("report_master", array('job_fk' => $key["id"]), array("id", "asc"));
           // $prnt_cnt = $this->btob_job_model->get_val("SELECT id FROM `booked_job_test` WHERE job_fk='" . $key["id"] . "' AND status='1' AND `panel_fk` IS NOT NULL");
        //    $data['query'][$cnt]["panel_test_count"] = count($prnt_cnt);
       //     $data['query'][$cnt]["print_cnt"] = $this->btob_job_model->get_val("SELECT COUNT(id) as cnt FROM `print_report_count` WHERE `status`='1' AND job_fk='" . $key["id"] . "'");
            $job_test_list = $this->btob_job_model->get_val("SELECT `job_test_list_master`.*,`test_master`.`test_name` FROM `job_test_list_master` INNER JOIN `test_master` ON `job_test_list_master`.`test_fk`=`test_master`.`id` WHERE `job_test_list_master`.`job_fk`='" . $key["id"] . "'");
            /* Check sub test start */
            $job_tst_lst = array();
            foreach ($job_test_list as $st_key) {
                //echo $st_key['test_fk'];
                $job_sub_test_list = $this->btob_job_model->get_val("SELECT `sub_test_master`.test_fk,`sub_test_master`.`sub_test`,test_master.`test_name` FROM `sub_test_master` INNER JOIN `test_master` ON `sub_test_master`.`sub_test`=`test_master`.`id` WHERE `sub_test_master`.`status`='1' AND `test_master`.`status`='1' AND `sub_test_master`.`test_fk`='" . $st_key['test_fk'] . "'");
                $st_key["sub_test"] = $job_sub_test_list;
                $job_tst_lst[] = $st_key;
            }
            //die("OK");
            /* END */
            $data['query'][$cnt]["job_test_list"] = $job_tst_lst;
            $data['query'][$cnt]["report"] = $upload_data[0]["original"];
            $data['query'][$cnt]["emergency"] = $emergency_tests[0]["emergency"];
            $data['query'][$cnt]["cut_from_wallet"] = $w_prc;
            $data['query'][$cnt]["doctor_name"] = $doctor_data[0]["full_name"];
            $data['query'][$cnt]["doctor_mobile"] = $doctor_data[0]["mobile"];
            $package_ids = $this->btob_job_model->get_job_booking_package($key["id"]);
            $data['query'][$cnt]["package"] = $package_ids;
            if (!empty($package_ids)) {
                $data['query'][$cnt]["packagename"] = "";
            }
            $cnt++;
        }
        //  echo "<pre>";  print_r($data['query']); die();
        $url = "http://" . $_SERVER['HTTP_HOST'] . ":" . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
        $this->session->set_userdata("job_master_r", $url);
        $this->load->view('btob_pending_job_list_search', $data);
    }


    function changing_status_job() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $status = $this->input->post('status');
        $job_id = $this->input->post('jobid');
        $customer_last_job_id = $this->btob_job_model->master_fun_get_tbl_val("job_master", array('id' => $job_id), array("id", "asc"));
        if ($status == 2) {
            $this->job_mark_completed($job_id);
        }
        if ($status == 7) {
            $this->sample_collected_calculation($job_id);
        }
        $status_update = $this->btob_job_model->master_fun_update("job_master", array("id", $job_id), array("status" => $status));
        $this->btob_job_model->master_fun_insert("job_log", array("job_fk" => $job_id, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => $customer_last_job_id[0]["status"] . "-" . $status, "message_fk" => "3", "date_time" => date("Y-m-d H:i:s")));
        if ($status_update) {
            echo 1;
        }
    }


    function export_csv() {

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
        if ($branch != '') {
            $cntr_arry = array();
            $cntr_arry = $branch;
        }
        $search_data['cntr_arry'] = $cntr_arry;
        $search_data['t_id'] = $t_id;
        $search_data['p_id'] = $p_id;

        $result = $this->btob_job_model->csv_job_list($search_data);
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"All_Jobs_Report-" . date('d-M-Y') . ".csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("No.", "Reg No.", "Order Id", "Test City", "Branch", "Date", "Patient Name", "Mobile No.", "Doctor", "Test/Package Name", "Job Status", "Payment Type", "Portal", "Remark", "Added By", "Total Price", "Discount(RS.)", "Collected Cash/Card", "Debited From Wallet", "Due Amount", "Payment Mode"));
        $cnt = 1;
        foreach ($result as $key) {
            if ($key['status'] == 1) {
                $j_status = "Waiting For Approval";
            }
            if ($key['status'] == 6) {
                $j_status = "Approved";
            }
            if ($key['status'] == 7) {
                $j_status = "Sample Collected";
            }
            if ($key['status'] == 8) {
                $j_status = "Processing";
            }
            if ($key['status'] == 2) {
                $j_status = "Completed";
            }
            $sample_collected = 'No';
            if ($key["sample_collection"] == 1) {
                $sample_collected = 'Yes';
            }
            $addr = '';
            if (!empty($key["address"])) {
                $addr = $key["address"];
            } else {
                $addr = $key["address1"];
            }
            if (!$key["payable_amount"]) {
                $key["payable_amount"] = 0;
            }
            /* Nishit 18-08-2017 START */
            $payment_mode = array();
            $discount = 0;
            if ($key["discount"] > 0) {
                $discount = round($key["price"] * $key["discount"] / 100);
            }
            $added_by = "Online";
            if (!empty($key["phlebo_added_by"])) {
                $added_by = $key["phlebo_added_by"] . " (Phlebo)";
            } else if (!empty($key["added_by"])) {
                $added_by = $key["added_by"];
            }
            $collection_type = $this->btob_job_model->get_val("SELECT GROUP_CONCAT(`payment_type`) AS payment_type FROM `job_master_receiv_amount` WHERE `status`='1' AND job_fk='" . $key["id"] . "' GROUP BY payment_type ORDER BY payment_type ASC");
            if (count($collection_type) > 0) {
                if (strtoupper($collection_type[0]["payment_type"]) == "CASH") {
                    $payment_mode[] = "CASH";
                }
                if (strtoupper($collection_type[0]["payment_type"]) != "CASH" && count($collection_type) > 0) {
                    $payment_mode[] = "ONLINE";
                }
                if (strtoupper($collection_type[0]["payment_type"]) == "CASH" && count($collection_type) > 1) {
                    $payment_mode[] = "ONLINE";
                }
            }
            $dabitt_from_wallet = $this->btob_job_model->get_val("SELECT IF(SUM(`debit`)>0,SUM(`debit`),0) AS dabit FROM `wallet_master` WHERE `job_fk`='" . $key["id"] . "' AND `status`='1'");
            $collected_cash_card = round($key["price"] - $key["payable_amount"] - $discount - $dabitt_from_wallet[0]["dabit"]);
            if ($dabitt_from_wallet[0]["dabit"] > 0) {
                $payment_mode[] = "WALLET";
            }
            if (strtoupper($key["payment_type"]) == "PAYUMONEY") {
                $payment_mode[] = "ONLINE";
            }

            /* END */
            if ($key["family_member_fk"] == 0) {
                $patient_name = $key["full_name"];
            } else {
                $patient_name = $key["family_name"];
            }
            fputcsv($handle, array($cnt, $key["id"], $key["order_id"], $key["test_city_name"], $key["branch_name"], $key["date"], $patient_name, $key["mobile"], $key["doctor_name"] . "-" . $key["doctor_mobile"], $key["testname"] . " " . $key["packagename"], $j_status, $key["payment_type"], $key["portal"], $key["note"], $added_by, $key["price"], $discount, $collected_cash_card, $dabitt_from_wallet[0]["dabit"], $key["payable_amount"], implode("+", $payment_mode)));
            $cnt++;
        }
        fclose($handle);
        exit;
    }

    function export_doctor_csv() {

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
        if ($branch != '') {
            $cntr_arry = array();
            $cntr_arry = $branch;
        }
        $search_data['cntr_arry'] = $cntr_arry;
        $search_data['t_id'] = $t_id;
        $search_data['p_id'] = $p_id;

        $result = $this->btob_job_model->csv_job_list2($search_data);
        //print_r($result); die();
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"All_Jobs_Report-" . date('d-M-Y') . ".csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("No.", "Reg No.", "Order Id", "Test City", "Branch", "Date", "Patient Name", "Mobile No.", "Doctor", "Test/Package Name", "Payable Amount", "Debited From Wallet", "Price", "Discount", "Job Status", "Payment Type", "Blood Sample Collected", "Portal", "Remark"));
        $cnt = 1;
        foreach ($result as $key) {
            if ($key['status'] == 1) {
                $j_status = "Waiting For Approval";
            }
            if ($key['status'] == 6) {
                $j_status = "Approved";
            }
            if ($key['status'] == 7) {
                $j_status = "Sample Collected";
            }
            if ($key['status'] == 8) {
                $j_status = "Processing";
            }
            if ($key['status'] == 2) {
                $j_status = "Completed";
            }
            $sample_collected = 'No';
            if ($key["sample_collection"] == 1) {
                $sample_collected = 'Yes';
            }
            $addr = '';
            if (!empty($key["address"])) {
                $addr = $key["address"];
            } else {
                $addr = $key["address1"];
            }
            if (!$key["payable_amount"]) {
                $key["payable_amount"] = 0;
            }
            if ($key["family_member_fk"] == 0) {
                $patient_name = $key["full_name"];
            } else {
                $patient_name = $key["family_name"];
            }
            fputcsv($handle, array($cnt, $key["id"], $key["order_id"], $key["test_city_name"], $key["branch_name"], $key["date"], $patient_name, $key["mobile"], $key["doctor_name"] . "-" . $key["doctor_mobile"], $key["testname"] . " " . $key["packagename"], $key["payable_amount"], $key["cut_from_wallet"], $key["price"], $key["discount"], $j_status, $key["payment_type"], $sample_collected, $key["portal"], $key["note"]));
            $cnt++;
        }
        fclose($handle);
        exit;
    }

 
    function download_report($name) {
        $this->load->helper('download');
        $data = file_get_contents(base_url() . "/upload/" . $name); // Read the file's contents

        force_download($name, $data);
    }

	function mark_complete($cid){
		 if (!is_loggedin()) {
            redirect('login');
        }
		$update = $this->btob_job_model->master_fun_update("job_master", array("id", $cid), array("status" => "2"));
		//$update = $this->btob_job_model->master_fun_update("job_master", array("id", $cid), array("status" => "2"));
		
		$job= $this->btob_job_model->get_val("SELECT * FROM  `job_master`  WHERE   id=$cid");
		$report= $this->btob_job_model->get_val("SELECT * FROM  `report_master`  WHERE STATUS=1 AND job_fk=$cid");
		
 copy (FCPATH . "/upload/report/".$report[0]["report"] ,FCPATH . "/upload/business_report/".$report[0]["report"] );
 copy (FCPATH . "/upload/report/".$report[0]["without_laterpad"] ,FCPATH . "/upload/business_report/".$report[0]["without_laterpad"] );
        	$file_upload = array("job_fk" => $job[0]["b2b_id"], "report" => $report[0]["report"], "original" => $report[0]["report"], "description" => "", "type" => "c");
		$b2f2 = $this->btob_job_model->master_fun_insert("b2b_jobspdf", $file_upload); 
		
		$file_upload = array("job_fk" => $job[0]["b2b_id"], "report" => $report[0]["without_laterpad"], "original" => $report[0]["without_laterpad"], "description" => "", "type" => "c");
		$b2f2 = $this->btob_job_model->master_fun_insert("b2b_jobspdf", $file_upload); 
		$this->session->set_flashdata("success", array("Job successfully mark as completed."));
	         redirect("job-master/job-details/".$cid, "refresh");

	}
    

 
}

?>