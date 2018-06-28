<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('job_model');
        $this->load->model('user_model');
        $this->load->model('user_call_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('pushserver');
        $this->load->library('email');
        $data["login_data"] = logindata();
        $this->load->helper('string');
        if (!is_loggedin()) {
            redirect('login');
        }
    }

    function admin_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $data['query'] = $this->user_model->master_fun_get_tbl_val("admin_master", array('status' => 1, 'type!=' => 0), array("id", "asc"));
        //$this->load->view('admin/state_list_view', $data);
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('admin_view', $data);
        $this->load->view('footer');
    }

    function admin_add() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $this->load->library('form_validation');
        $this->form_validation->set_rules('username', 'Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required');
        $this->form_validation->set_rules('type', 'type', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $name = $this->input->post('username');
            $email = $this->input->post('email');
            $type = $this->input->post('type');
            $password = $this->input->post('password');
            $data['query'] = $this->user_model->master_fun_insert("admin_master", array("name" => $name, "status" => "1", "email" => $email, "type" => $type, "password" => $password));
            $this->session->set_flashdata("success", array("Exterior successfull added."));
            redirect("admin/admin_list", "refresh");
        } else {
            $data['type'] = $this->user_model->master_fun_get_tbl_val("user_type_master", array("status" => 1), array("id", "desc"));
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('admin_add', $data);
            $this->load->view('footer');
        }
    }

    function admin_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->user_model->master_fun_update("admin_master", array("id", $cid), array("status" => "0"));

        $this->session->set_flashdata("success", array("Exterior successfull deleted."));
        redirect("admin/admin_list", "refresh");
    }

    function admin_edit() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["cid"] = $this->uri->segment('3');

        $this->load->library('form_validation');
        $this->form_validation->set_rules('username', 'Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required');
        $this->form_validation->set_rules('type', 'type', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        if ($this->form_validation->run() != FALSE) {
            $name = $this->input->post('username');
            $email = $this->input->post('email');
            $type = $this->input->post('type');
            $password = $this->input->post('password');
            $data['query'] = $this->user_model->master_fun_update("admin_master", array("id", $data["cid"]), array("name" => $name, "status" => "1", "email" => $email, "type" => $type, "password" => $password));
            $this->session->set_flashdata("success", array("House successfull updated."));
            redirect("admin/admin_list", "refresh");
        } else {
            $data['query'] = $this->user_model->master_fun_get_tbl_val("admin_master", array("id" => $data["cid"]), array("id", "desc"));
            $data['type'] = $this->user_model->master_fun_get_tbl_val("user_type_master", array("status" => 1), array("id", "desc"));
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('admin_edit', $data);
            $this->load->view('footer');
        }
    }

    /*    function test() {
      if (!is_loggedin()) {
      redirect('login');
      }
      if ($this->session->flashdata("error")) {
      $data["error"] = $this->session->flashdata("error");
      }
      $data["login_data"] = logindata();
      if ($data["login_data"]['type'] == 2) {
      //   redirect('Admin/Telecaller');
      }
      $data["login_data"] = logindata();
      if ($data["login_data"]['type'] == 3 || $data["login_data"]['type'] == 4) {
      redirect('Logistic/dashboard');
      }
      $cust = $this->user_model->master_fun_get_tbl_val("customer_master", array('status' => 1), array("id", "asc"));
      $data['totalcustomer'] = count($cust);
      $test = $this->user_model->master_fun_get_tbl_val("test_master", array('status' => 1), array("id", "asc"));
      $data['totaltest'] = count($test);
      $pending = $this->user_model->master_fun_get_tbl_val("job_master", array('status' => 1), array("id", "asc"));
      $data['pending'] = count($pending);
      $complete = $this->user_model->master_fun_get_tbl_val("job_master", array('status' => 2), array("id", "asc"));
      $data['complete'] = count($complete);
      $data["all_jobs"] = $this->user_model->master_fun_get_tbl_val("job_master", array('status!=' => 0), array("id", "asc"));
      $data["prescription_upload"] = $this->user_model->master_fun_get_tbl_val("prescription_upload", array('status!=' => 0), array("id", "asc"));
      if ($data["login_data"]['type'] == 1 || $data["login_data"]['type'] == 2) {
      $data['total_sample'] = $this->user_model->total_sample();
      $data['total_test'] = $this->user_model->total_test();

      $date = $this->input->get("date");
      $data["date1"] = $this->input->get("j_date");
      $data["date2"] = $this->input->get("c_date");
      $data["date"] = $date;
      $data['total_revenue'] = $this->user_model->total_revenue($branch, $date);
      $data['total_due_amount'] = $this->user_model->total_due($branch, $date);
      $data['job_status'] = $this->user_model->get_job_status($branch, $data["date1"]);
      $data['collecting_amount'] = $this->user_model->collecting_amount($branch, $data["date2"]);
      //print_r($data['collecting_amount']); die();
      } else if ($data["login_data"]['type'] == 5 || $data["login_data"]['type'] == 6 || $data["login_data"]['type'] == 7) {
      $user_branch = $this->user_model->master_fun_get_tbl_val("user_branch", array('status' => 1, "user_fk" => $data["login_data"]['id']), array("id", "asc"));
      $branch = array();
      foreach ($user_branch as $key1) {
      $branch[] = $key1["branch_fk"];
      }
      if ($branch != 0 && $branch != '') {
      $data['total_revenue'] = $this->user_model->total_revenue($branch);
      $data['total_due_amount'] = $this->user_model->total_due($branch);
      $data['total_sample'] = $this->user_model->total_sample($branch);
      $data['total_test'] = $this->user_model->total_test($branch);
      }
      }
      $data['test_sunday'] = $this->user_model->last_7days_test('Sunday');
      $data['test_monday'] = $this->user_model->last_7days_test('Monday');
      $data['test_tuesday'] = $this->user_model->last_7days_test('Tuesday');
      $data['test_wednesday'] = $this->user_model->last_7days_test('Wednesday');
      $data['test_thrusday'] = $this->user_model->last_7days_test('Thrusday');
      $data['test_friday'] = $this->user_model->last_7days_test('Friday');
      $data['test_saturday'] = $this->user_model->last_7days_test('Saturday');

      $data['sample_sunday'] = $this->user_model->last_7days_sample('Sunday');
      $data['sample_monday'] = $this->user_model->last_7days_sample('Monday');
      $data['sample_tuesday'] = $this->user_model->last_7days_sample('Tuesday');
      $data['sample_wednesday'] = $this->user_model->last_7days_sample('Wednesday');
      $data['sample_thrusday'] = $this->user_model->last_7days_sample('Thrusday');
      $data['sample_friday'] = $this->user_model->last_7days_sample('Friday');
      $data['sample_saturday'] = $this->user_model->last_7days_sample('Saturday');

      $data['amount_sunday'] = $this->user_model->last_7days_amount('Sunday');
      $data['amount_monday'] = $this->user_model->last_7days_amount('Monday');
      $data['amount_tuesday'] = $this->user_model->last_7days_amount('Tuesday');
      $data['amount_wednesday'] = $this->user_model->last_7days_amount('Wednesday');
      $data['amount_thrusday'] = $this->user_model->last_7days_amount('Thrusday');
      $data['amount_friday'] = $this->user_model->last_7days_amount('Friday');
      $data['amount_saturday'] = $this->user_model->last_7days_amount('Saturday');

      $data['cashback_sunday'] = $this->user_model->last_7days_cashback('Sunday');
      $data['cashback_monday'] = $this->user_model->last_7days_cashback('Monday');
      $data['cashback_tuesday'] = $this->user_model->last_7days_cashback('Tuesday');
      $data['cashback_wednesday'] = $this->user_model->last_7days_cashback('Wednesday');
      $data['cashback_thrusday'] = $this->user_model->last_7days_cashback('Thrusday');
      $data['cashback_friday'] = $this->user_model->last_7days_cashback('Friday');
      $data['cashback_saturday'] = $this->user_model->last_7days_cashback('Saturday');
      $this->load->view('header', $data);
      $this->load->view('nav', $data);
      $this->load->view('admin_test_admin', $data);
      $this->load->view('footer');
      } */

    function test() {
        if (!is_loggedin()) {
            redirect('login');
        }
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data["login_data"] = logindata();
        if ($data["login_data"]['type'] == 2) {
            //   redirect('Admin/Telecaller');
        }
        $data["login_data"] = logindata();
        if ($data["login_data"]['type'] == 3 || $data["login_data"]['type'] == 4) {
            redirect('b2b/Logistic/dashboard');
        }
        //$data["ongoing"] = $this->home_model->getongoing();
        //$data["complete"] = $this->home_model->getcomplete();
        //$data["totalsubcategory"] = $this->home_model->getsubcategory();
        $cust = $this->user_model->master_fun_get_tbl_val("customer_master", array('status' => 1), array("id", "asc"));
        $data['totalcustomer'] = count($cust);
        $test = $this->user_model->master_fun_get_tbl_val("test_master", array('status' => 1), array("id", "asc"));
        $data['totaltest'] = count($test);
        $pending = $this->user_model->master_fun_get_tbl_val("job_master", array('status' => 1), array("id", "asc"));
        $data['pending'] = count($pending);
        $complete = $this->user_model->master_fun_get_tbl_val("job_master", array('status' => 2), array("id", "asc"));
        $data['complete'] = count($complete);
        $data["all_jobs"] = $this->user_model->master_fun_get_tbl_val("job_master", array('status!=' => 0), array("id", "asc"));
        $data["prescription_upload"] = $this->user_model->master_fun_get_tbl_val("prescription_upload", array('status!=' => 0), array("id", "asc"));
        if ($data["login_data"]['type'] == 1 || $data["login_data"]['type'] == 2) {
            $data['total_sample'] = $this->user_model->total_sample();
            $data['total_test'] = $this->user_model->total_test();

            /* Nishit code start */
            $date = $this->input->get("date");
            $data["date1"] = $this->input->get("j_date");
            $data["date2"] = $this->input->get("c_date");
            $data["date"] = $date;
            $data['total_revenue'] = $this->user_model->total_revenue($branch, $date);
            $data['total_due_amount'] = $this->user_model->total_due($branch, $date);
            $data['job_status'] = $this->user_model->get_job_status($branch, $data["date1"]);
            $data['collecting_amount'] = $this->user_model->collecting_amount($branch, $data["date2"]);
            if (empty($data["date1"])) {
                $data["date1"] = date("d/m/Y");
            }
            $new_cdate = explode("/", $data["date1"]);
            $data["phlebo_collect"] = $this->user_model->get_val("SELECT SUM(`phlebo_collect_amount`.amount) AS amount,`phlebo_master`.`name` FROM `phlebo_collect_amount` INNER JOIN phlebo_master ON `phlebo_collect_amount`.`phlebo_fk`= `phlebo_master`.`id` WHERE `phlebo_collect_amount`.`status`='1' AND `phlebo_collect_amount`.`createddate`>'" . $new_cdate[2] . "-" . $new_cdate[1] . "-" . $new_cdate[0] . " 00:00:00' AND `phlebo_collect_amount`.`createddate`<'" . $new_cdate[2] . "-" . $new_cdate[1] . "-" . $new_cdate[0] . "  23:59:59' GROUP BY `phlebo_master`.`name`");
            $data["online_pay"] = $this->user_model->get_val("SELECT SUM(amount) AS amount FROM payment WHERE `status`='success' AND paydate >'" . $new_cdate[2] . "-" . $new_cdate[1] . "-" . $new_cdate[0] . " 00:00:00' AND `paydate`<'" . $new_cdate[2] . "-" . $new_cdate[1] . "-" . $new_cdate[0] . " 23:59:59'");
            $data["cut_from_wallet"] = $this->user_model->get_val("SELECT SUM(debit) AS amount FROM wallet_master WHERE `status`='1' AND `created_time` >'" . $new_cdate[2] . "-" . $new_cdate[1] . "-" . $new_cdate[0] . " 00:00:00' AND `created_time`<'" . $new_cdate[2] . "-" . $new_cdate[1] . "-" . $new_cdate[0] . " 23:59:59'");
            $data["discount"] = $this->user_model->get_val("SELECT price,discount FROM `job_master` WHERE `status`!='0' AND `date` >'" . $new_cdate[2] . "-" . $new_cdate[1] . "-" . $new_cdate[0] . " 00:00:00' AND `date` <'" . $new_cdate[2] . "-" . $new_cdate[1] . "-" . $new_cdate[0] . " 23:59:59'");
// echo "SELECT SUM(amount) AS amount FROM payment WHERE `status`='success' AND paydate >'" . $new_cdate[2] . "-" . $new_cdate[1] . "-" . $new_cdate[0] . " 00:00:00' AND `paydate`<'" . $new_cdate[2] . "-" . $new_cdate[1] . "-" . $new_cdate[0] . "  23:59:59'";die();
            //print_r($data["phlebo_collect"]); 
            //die();
            /* Nishit code end */
        } else if ($data["login_data"]['type'] == 5 || $data["login_data"]['type'] == 6 || $data["login_data"]['type'] == 7) {
            $user_branch = $this->user_model->master_fun_get_tbl_val("user_branch", array('status' => 1, "user_fk" => $data["login_data"]['id']), array("id", "asc"));
            $branch = array();
            foreach ($user_branch as $key1) {
                $branch[] = $key1["branch_fk"];
            }
            if ($branch != 0 && $branch != '') {
                $data['total_revenue'] = $this->user_model->total_revenue($branch);
                $data['total_due_amount'] = $this->user_model->total_due($branch);
                $data['total_sample'] = $this->user_model->total_sample($branch);
                $data['total_test'] = $this->user_model->total_test($branch);
            }
        }
        if ($data["login_data"]['type'] == 5) {
            $data['date4'] = $this->input->get("date4");
            $data['date5'] = $this->input->get("date5");
            $user_branch = $this->user_model->master_fun_get_tbl_val("user_branch", array('status' => 1, "user_fk" => $data["login_data"]['id']), array("id", "asc"));
            $branch = array();
            foreach ($user_branch as $key1) {
                $branch[] = $key1["branch_fk"];
            }
            $data['collecting_amount_branch'] = $this->user_model->collecting_amount_branch($branch, $data["date4"]);
            $data['total_amount_branch'] = $this->user_model->total_amount_branch($branch, $data["date5"]);
        }
        $data['test_sunday'] = $this->user_model->last_7days_test('Sunday');
        $data['test_monday'] = $this->user_model->last_7days_test('Monday');
        $data['test_tuesday'] = $this->user_model->last_7days_test('Tuesday');
        $data['test_wednesday'] = $this->user_model->last_7days_test('Wednesday');
        $data['test_thrusday'] = $this->user_model->last_7days_test('Thrusday');
        $data['test_friday'] = $this->user_model->last_7days_test('Friday');
        $data['test_saturday'] = $this->user_model->last_7days_test('Saturday');

        $data['sample_sunday'] = $this->user_model->last_7days_sample('Sunday');
        $data['sample_monday'] = $this->user_model->last_7days_sample('Monday');
        $data['sample_tuesday'] = $this->user_model->last_7days_sample('Tuesday');
        $data['sample_wednesday'] = $this->user_model->last_7days_sample('Wednesday');
        $data['sample_thrusday'] = $this->user_model->last_7days_sample('Thrusday');
        $data['sample_friday'] = $this->user_model->last_7days_sample('Friday');
        $data['sample_saturday'] = $this->user_model->last_7days_sample('Saturday');

        $data['amount_sunday'] = $this->user_model->last_7days_amount('Sunday');
        $data['amount_monday'] = $this->user_model->last_7days_amount('Monday');
        $data['amount_tuesday'] = $this->user_model->last_7days_amount('Tuesday');
        $data['amount_wednesday'] = $this->user_model->last_7days_amount('Wednesday');
        $data['amount_thrusday'] = $this->user_model->last_7days_amount('Thrusday');
        $data['amount_friday'] = $this->user_model->last_7days_amount('Friday');
        $data['amount_saturday'] = $this->user_model->last_7days_amount('Saturday');

        $data['cashback_sunday'] = $this->user_model->last_7days_cashback('Sunday');
        $data['cashback_monday'] = $this->user_model->last_7days_cashback('Monday');
        $data['cashback_tuesday'] = $this->user_model->last_7days_cashback('Tuesday');
        $data['cashback_wednesday'] = $this->user_model->last_7days_cashback('Wednesday');
        $data['cashback_thrusday'] = $this->user_model->last_7days_cashback('Thrusday');
        $data['cashback_friday'] = $this->user_model->last_7days_cashback('Friday');
        $data['cashback_saturday'] = $this->user_model->last_7days_cashback('Saturday');
        $this->load->view('header', $data);
        $this->load->view('nav', $data);
        $this->load->view('admin_test_admin', $data);
        $this->load->view('footer');
    }

    function get_dashboard() {
        if (!is_loggedin()) {
            redirect('login');
        }
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST');
        header("Access-Control-Allow-Headers: X-Requested-With");
        $data["login_data"] = logindata();
        $type = $this->input->post("type");
        switch ($type) {
            case "count":
                $test = $this->user_model->master_fun_get_tbl_val("test_master", array('status' => 1), array("id", "asc"));
                $data['totaltest'] = count($test);
                $data['total_sample'] = $this->user_model->total_sample();
                $data['total_revenue'] = $this->user_model->total_revenue();
                $this->load->view("dashboard_count", $data);
                break;
            case "chart":
                $cust = $this->user_model->master_fun_get_tbl_val("customer_master", array('status' => 1), array("id", "asc"));
                $data['totalcustomer'] = count($cust);
                $pending = $this->user_model->master_fun_get_tbl_val("job_master", array('status' => 1), array("id", "asc"));
                $data['pending'] = count($pending);
                $complete = $this->user_model->master_fun_get_tbl_val("job_master", array('status' => 2), array("id", "asc"));
                $data['complete'] = count($complete);
                $data["all_jobs"] = $this->user_model->master_fun_get_tbl_val("job_master", array('status!=' => 0), array("id", "asc"));
                $data["prescription_upload"] = $this->user_model->master_fun_get_tbl_val("prescription_upload", array('status!=' => 0), array("id", "asc"));
                $data['total_test'] = $this->user_model->total_test();
                $data['test_sunday'] = $this->user_model->last_7days_test('Sunday');
                $data['test_monday'] = $this->user_model->last_7days_test('Monday');
                $data['test_tuesday'] = $this->user_model->last_7days_test('Tuesday');
                $data['test_wednesday'] = $this->user_model->last_7days_test('Wednesday');
                $data['test_thrusday'] = $this->user_model->last_7days_test('Thrusday');
                $data['test_friday'] = $this->user_model->last_7days_test('Friday');
                $data['test_saturday'] = $this->user_model->last_7days_test('Saturday');

                $data['sample_sunday'] = $this->user_model->last_7days_sample('Sunday');
                $data['sample_monday'] = $this->user_model->last_7days_sample('Monday');
                $data['sample_tuesday'] = $this->user_model->last_7days_sample('Tuesday');
                $data['sample_wednesday'] = $this->user_model->last_7days_sample('Wednesday');
                $data['sample_thrusday'] = $this->user_model->last_7days_sample('Thrusday');
                $data['sample_friday'] = $this->user_model->last_7days_sample('Friday');
                $data['sample_saturday'] = $this->user_model->last_7days_sample('Saturday');


                $data['amount_sunday'] = $this->user_model->last_7days_amount('Sunday');
                $data['amount_monday'] = $this->user_model->last_7days_amount('Monday');
                $data['amount_tuesday'] = $this->user_model->last_7days_amount('Tuesday');
                $data['amount_wednesday'] = $this->user_model->last_7days_amount('Wednesday');
                $data['amount_thrusday'] = $this->user_model->last_7days_amount('Thrusday');
                $data['amount_friday'] = $this->user_model->last_7days_amount('Friday');
                $data['amount_saturday'] = $this->user_model->last_7days_amount('Saturday');

                $data['cashback_sunday'] = $this->user_model->last_7days_cashback('Sunday');
                $data['cashback_monday'] = $this->user_model->last_7days_cashback('Monday');
                $data['cashback_tuesday'] = $this->user_model->last_7days_cashback('Tuesday');
                $data['cashback_wednesday'] = $this->user_model->last_7days_cashback('Wednesday');
                $data['cashback_thrusday'] = $this->user_model->last_7days_cashback('Thrusday');
                $data['cashback_friday'] = $this->user_model->last_7days_cashback('Friday');
                $data['cashback_saturday'] = $this->user_model->last_7days_cashback('Saturday');
                $this->load->view("dashboard_chart", $data);
                break;
            default:
                break;
        }
    }

    function Telecaller() {
        if (!is_loggedin()) {
            redirect('login');
        }
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $this->load->model('job_model');
        $user = $this->input->get('user');
        $test_pack = $this->input->get('test_package');
        $p_mobile = $this->input->get('p_mobile');
        $date = $this->input->get('date');
        $city = $this->input->get('city');
        $status = $this->input->get('status');
        $mobile = $this->input->get('mobile');
        $data['customerfk'] = $user;
        $data['test_pac'] = $test_pack;
        $data['mobile'] = $p_mobile;
        $data['date'] = $date;
        $data['cityfk'] = $city;
        $data['statusid'] = $status;
        //$data['mobile'] = $mobile;
        $data["login_data"] = logindata();
        //print_r($data["login_data"]); die();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $p_amount = '';
        $test_packages = explode("_", $test_pack);
        if ($test_pack) {
            $alpha = $test_packages[0];
            $tp_id = $test_packages[1];
        }
        if ($alpha == 't') {
            $t_id = $tp_id;
        }
        if ($alpha == 'p') {
            $p_id = $tp_id;
        }
        $data['success'] = $this->session->flashdata("success");
        $data['query'] = $this->job_model->pending_job_search_telecaller($user, $date, $city, $status, $p_mobile, $t_id, $p_id, $p_amount);
        //print_r($data['query']); die();
        $data['customer'] = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1), array("full_name", "asc"));
        $data['test_list'] = $this->job_model->master_fun_get_tbl_val("test_master", array('status' => 1), array("test_name", "asc"));
        $data['package_list'] = $this->job_model->master_fun_get_tbl_val("package_master", array('status' => 1), array("title", "asc"));
        $data['city'] = $this->job_model->master_fun_get_tbl_val("city", array('status' => 1), array("id", "asc"));
        $data['unread'] = $this->job_model->master_fun_get_tbl_val("prescription_upload", array('status' => 1, "is_read" => "0"), array("id", "asc"));

        $data["login_data"] = logindata();
        $this->load->view('header', $data);
        $this->load->view('nav', $data);
        $this->load->view('telecaller', $data);
        $this->load->view('footer');
    }

    function TelecallerPriscription() {
        if (!is_loggedin()) {
            redirect('login');
        }
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data["login_data"] = logindata();
        $this->load->model('job_model');
        $user = $this->input->get('user');
        $date = $this->input->get('date');
        $mobile = $this->input->get('mobile');
        $status = $this->input->get('status');
        //$city = $this->input->get('city');
        $data['customerfk'] = $user;
        $data['date'] = $date;
        $data['mobile'] = $mobile;
        $data['status'] = $status;
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $data['query'] = $this->job_model->prescription_report_search_talycaller($user, $date, $mobile, $status);
        $data['customer'] = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1), array("id", "asc"));
        $data['city'] = $this->job_model->master_fun_get_tbl_val("city", array('status' => 1), array("id", "asc"));
        $data['unread'] = $this->job_model->master_fun_get_tbl_val("prescription_upload", array('status' => 1, "is_read" => "0"), array("id", "asc"));
        $data["login_data"] = logindata();
        $this->load->view('header', $data);
        $this->load->view('nav', $data);
        $this->load->view('telecaller_prescription', $data);
        $this->load->view('footer');
    }

    function job_details() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $this->load->model('job_model');
        $data['cid'] = $this->uri->segment(3);
        $this->load->library('form_validation');
        $this->form_validation->set_rules('cust_fk', 'Job Id', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            //echo "<pre>"; print_r($_POST); die();
            $cust_fk = $this->input->post("cust_fk");
            $email = $this->input->post("email");
            $gender = $this->input->post("gender");
            $state = $this->input->post("state");
            $city = $this->input->post("city");
            $test_city = $this->input->post("test_city");
            $address = $this->input->post("address");
            $note = $this->input->post("note");
            $note = $this->input->post("note");
            $test = $this->input->post("test");
            $payable = $this->input->post("payable");
            $total_amount = $this->input->post("total_amount");
            $user_data = array(
                "email" => $email,
                "gender" => $gender,
                "address" => $address,
                "state" => $state,
                "city" => $city,
            );
            $this->job_model->master_fun_update("customer_master", array('id', $cust_fk), $user_data);
            $this->job_model->master_fun_delete("job_test_list_master", array('job_fk', $data['cid']));
            $this->job_model->master_fun_delete("book_package_master", array('job_fk', $data['cid']));
            $user_details = $this->job_model->master_fun_get_tbl_val("customer_master", array('id' => $cust_fk), array("id", "asc"));
            $job_details = $this->job_model->master_fun_get_tbl_val("job_master", array('id' => $data['cid']), array("id", "asc"));
            $diff = $job_details[0]["price"] - $job_details[0]["payable_amount"];
            $price = 0;
            foreach ($test as $key) {
                $tn = explode("-", $key);
                if ($tn[0] == 't') {
                    $this->job_model->master_fun_insert("job_test_list_master", array('job_fk' => $data['cid'], "test_fk" => $tn[1]));
                    $result = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $job_details[0]["test_city"] . "' AND `test_master`.`id`='" . $tn[1] . "'");
                    $price += $result[0]["price"];
                }
                if ($tn[0] == 'p') {
                    $this->job_model->master_fun_insert("book_package_master", array("cust_fk" => $cust_fk, "package_fk" => $tn[1], 'job_fk' => $data['cid'], "status" => "1", "type" => "2"));
                    $query = $this->db->get_where('package_master_city_price', array('package_fk' => $tn[0], "city_fk" => $job_details[0]["test_city"]));
                    $result = $query->result();
                    $price += $result[0]->d_price;
                }
            }

            if ($diff < $price) {
                $p_price = $price - $diff;
            } else {
                $p_price = 0;
            }
            $job_data = array("test_city" => $test_city, "note" => $note, "status" => "6", "price" => $total_amount, "payable_amount" => $payable, "added_by" => $data["login_data"]["id"]);
            $this->job_model->master_fun_update("job_master", array('id', $data['cid']), $job_data);
            $this->job_model->master_fun_insert("job_log", array("job_fk" => $data['cid'], "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "message_fk" => "2", "date_time" => date("Y-m-d H:i:s")));
            $this->session->set_flashdata("success", array("Test successfully approved."));
            redirect("Admin/Telecaller");
        } else {
            $data["login_data"] = logindata();
            $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
            $data['success'] = $this->session->flashdata("success");
            $data['error'] = $this->session->flashdata("error");
            $data['query'] = $this->job_model->job_details($data['cid']);
            $data['report'] = $this->job_model->master_fun_get_tbl_val("report_master", array('status' => 1, "job_fk" => $data['cid']), array("id", "asc"));
            $data['city'] = $this->job_model->master_fun_get_tbl_val("city", array('status' => 1), array("id", "asc"));
            $data['state'] = $this->job_model->master_fun_get_tbl_val("state", array('status' => 1), array("id", "asc"));
            $data['country'] = $this->job_model->master_fun_get_tbl_val("country", array('status' => 1), array("id", "asc"));
            $data['test_cities'] = $this->job_model->master_fun_get_tbl_val("test_cities", array('status' => 1), array("id", "asc"));
            $test = $this->job_model->master_fun_get_tbl_val("job_test_list_master", array('job_fk' => $data['query'][0]["id"]), array("id", "asc"));
            $package = $this->job_model->master_fun_get_tbl_val("book_package_master", array('job_fk' => $data['query'][0]["id"]), array("id", "asc"));
            foreach ($test as $t_key) {
                $data['test_info'][] = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $data['query'][0]["test_city"] . "' AND `test_master`.`id`='" . $t_key["test_fk"] . "'");
            }
            $update = $this->job_model->master_fun_update("job_master", array('id', $data['cid']), array("views" => "1"));
            $this->job_model->master_fun_insert("job_log", array("job_fk" => $data['cid'], "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "message_fk" => "2", "date_time" => date("Y-m-d H:i:s")));
            foreach ($package as $p_key) {
                $data['package_info'][] = $this->job_model->get_val("SELECT `package_master`.`id`,`package_master`.`title`,`package_master`.`image`,`package_master`.`back_image`,`package_master`.`desc_web`,`package_master`.`status`,`package_master_city_price`.`a_price`,`package_master_city_price`.`d_price` FROM `package_master` INNER JOIN `package_master_city_price` ON `package_master`.`id`=`package_master_city_price`.`package_fk`
WHERE `package_master`.`id`='" . $p_key["package_fk"] . "' AND `package_master_city_price`.`city_fk`='" . $data['query'][0]["test_city"] . "'");
            }
            if (!$data['query'][0]["test_city"]) {
                $data['query'][0]["test_city"] = '1';
            }
            $data['test'] = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $data['query'][0]["test_city"] . "'");
            $data["package"] = $this->job_model->get_val("SELECT 
    `package_master`.*,
    `package_master_city_price`.`a_price` AS `a_price1`,
    `package_master_city_price`.`d_price` AS `d_price1`
  FROM
    `package_master` 
    INNER JOIN `package_master_city_price` 
      ON `package_master`.`id` = `package_master_city_price`.`package_fk` 
  WHERE `package_master`.`status` = '1' 
    AND `package_master_city_price`.`status` = '1' AND `package_master_city_price`.`city_fk` = '" . $data['query'][0]["test_city"] . "' ");
            $data['unread'] = $this->job_model->master_fun_get_tbl_val("prescription_upload", array('status' => 1, "is_read" => "0"), array("id", "asc"));

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('job_details_telecaller', $data);
            $this->load->view('footer');
        }
    }

    function check_email() {
        $this->load->model('job_model');
        $email = $this->input->get_post("email");
        $cust_id = $this->input->get_post("cust_id");
        $cnt = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "id !=" => $cust_id, "email" => $email), array("id", "asc"));
        echo count($cnt);
    }
	function check_email1() {
		
        $this->load->model('job_model');
        $email = $this->input->get_post("email");
        $cust_id = $this->input->get_post("cust_id");
		if(strtolower($email) != strtolower("noreply@airmedlabs.com")){
        $cnt = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "id !=" => $cust_id, "email" => $email), array("id", "asc"));
        echo count($cnt);
		}else{ echo 0; }
    }

    function check_phone() {
        $this->load->model('job_model');
        $phone = $this->input->get_post("phone");
        $cust_id = $this->input->get_post("cust_id");
        if ($cust_id != '') {
            $cnt = $this->job_model->get_val("SELECT count(*) as count,id from customer_master where status='1' AND mobile='" . $phone . "' AND id not in (" . $cust_id . ")");
        } else {
            $cnt = $this->job_model->get_val("SELECT count(*) as count,id from customer_master where status='1' AND mobile='" . $phone . "'");
        }
        //print_r($cnt);
        $data = array("status" => $cnt[0]["count"], "data" => $cnt);
        echo json_encode($data);
//echo $cnt[0]["count"];
    }

    function prescription_details() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->helper("Email");
        $email_cnt = new Email;
        $this->load->model('job_model');
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['cid'] = $this->uri->segment(3);
        $data['success'] = $this->session->flashdata("success");
        $data['error'] = $this->session->flashdata("error");

        $this->load->model('job_model');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('prescription_fk', 'Job Id', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $test_city = $this->input->post("test_city");
            //echo "<pre>";
            //print_r($_POST);
            //die();

            $customer = $this->input->post("customer");
            if ($customer == 0) {
                $name = $this->input->post("name");
                $phone = $this->input->post("phone");
                $email = $this->input->post("email");
                $password = $this->input->post("password");
                $gender = $this->input->post("gender");
                $address = $this->input->post("address");
                $total_amount = $this->input->post("total_amount");
                $payable = $this->input->post("payable");
                $note = $this->input->post("note");
                $discount = $this->input->post('discount');
                $c_data = array("full_name" => $name, "gender" => $gender, "email" => $email, "password" => $password, "mobile" => $phone, "address" => $address, "test_city" => $test_city);
                $uid = $this->job_model->master_fun_insert("customer_master", $c_data);
                $config['mailtype'] = 'html';
                $this->email->initialize($config);

                $message = '<div style="padding:0 4%;">
                    <h4><b>Create Account</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your account successfully created. </p>
                        <p style="color:#7e7e7e;font-size:13px;"> Username/Email : . ' . $email . '  </p>  
                        <p style="color:#7e7e7e;font-size:13px;"> Password : ' . $password . '  </p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
                $message = $email_cnt->get_design($message);
                $this->email->to($email);
                $this->email->from($this->config->item('admin_booking_email'), 'Airmed PathLabs');
                $this->email->subject('Account Created Successfully');
                $this->email->message($message);
                $this->email->send();
                $this->job_model->master_fun_update("prescription_upload", array("id", $data['cid']), array("cust_fk" => $uid));
            }
            if ($customer == 1) {
                $uid = $this->input->post("userid");
            }
            $upd = $this->job_model->master_fun_update("prescription_upload", array("id", $this->uri->segment(3)), array("status" => "2"));
            $submit_type = $this->input->post("submit_type");
            if ($submit_type == '1') {
                $id = $this->uri->segment(3);
                $test = $this->input->post('test');
                //$uid = $this->input->post('userid');
                $payable = $this->input->post('payable');
                $discount = $this->input->post('discount');
                $order_id = $this->get_job_id($test_city);
                $date = date('Y-m-d H:i:s');
                //$test = explode(',', $test);
                $test_package_name = array();
                /* foreach ($test as $key) {
                  $price1 = $this->job_model->master_fun_get_tbl_val("test_master", array("status" => 1, "id" => $key), array("test_name", "asc"));
                  $data['test'] = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $data['query'][0]["test_city"] . "'");

                  $price += $price1[0]['price'];
                  $test_package_name[] = $price1[0]['test_name'];
                  } */
                $price = 0;
                $cid = $this->uri->segment('3');
                $this->job_model->master_fun_update_multi("suggested_test", array('p_id' => $cid), array("status" => 0));
                foreach ($test as $key) {
                    $tn = explode("-", $key);
                    if ($tn[0] == 't') {
                        $result = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $test_city . "' AND `test_master`.`id`='" . $tn[1] . "'");
                        $price += $result[0]["price"];
                        $test_package_name[] = $result[0]["test_name"];
                        $this->job_model->master_fun_insert("suggested_test", array('status' => 1, 'p_id' => $cid, 'test_id' => $tn[1]));
                    }
                    if ($tn[0] == 'p') {
                        //print_r(array('package_fk' => $tn[1], "city_fk" => $test_city)); die();
                        $query = $this->db->get_where('package_master_city_price', array('package_fk' => $tn[1], "city_fk" => $test_city));
                        $result = $query->result();
                        $query1 = $this->db->get_where('package_master', array('id' => $tn[1]));
                        $result1 = $query1->result();
                        $price += $result[0]->d_price;
                        $test_package_name[] = $result1[0]->title;
                    }
                }
                $data = array(
                    "order_id" => $order_id,
                    "cust_fk" => $uid,
                    "date" => $date,
                    "price" => $this->input->post("total_amount"),
                    "status" => '6',
                    "payment_type" => "Cash On Delivery",
                    "discount" => $this->input->post('discount'),
                    "payable_amount" => $payable,
                    "test_city" => $test_city,
                    "note" => $this->input->post('note'),
                    "added_by" => $data["login_data"]["id"],
                    "date" => date("Y-m-d H:i:s")
                );
                $insert = $this->job_model->master_fun_insert("job_master", $data);
                $this->job_model->master_fun_update("prescription_upload", array("id", $this->uri->segment(3)), array("job_fk" => $insert));
                $testid = array();
                $packageid = array();
                foreach ($test as $key) {
                    $tn = explode("-", $key);
                    if ($tn[0] == 't') {
                        $testid[] = $tn[1];
                        $tst_price = $this->job_model->get_val("select price from test_master_city_price where test_fk='" . $tn[1] . "' and city_fk='" . $test_city . "' and status='1'");
                        $this->job_model->master_fun_insert("job_test_list_master", array('job_fk' => $insert, "test_fk" => $tn[1], "price" => $tst_price[0]["price"]));
                        //$this->job_model->master_fun_insert("booked_job_test", array("job_fk" => $insert, "test_city" => $test_city, "test_fk" => "t-" . $tn[1], "price" => $tst_price[0]["price"]));
                    }
                    if ($tn[0] == 'p') {
                        $packageid[] = $tn[1];
                        $tst_price = $this->job_model->get_val("select d_price from package_master_city_price where package_fk='" . $tn[1] . "' and city_fk='" . $test_city . "' and status='1'");
                        $this->job_model->master_fun_insert("book_package_master", array("cust_fk" => $uid, "package_fk" => $tn[1], 'job_fk' => $insert, "status" => "1", "type" => "2", "price" => $tst_price[0]["d_price"]));
                        //$this->job_model->master_fun_insert("booked_job_test", array("job_fk" => $insert, "test_city" => $test_city, "test_fk" => "p-" . $tn[1], "price" => $tst_price[0]["d_price"]));
                    }
                }


                /* Nishit send sms start */
                $pid = implode($packageid, ',');
                $tid = implode($testid, ',');
                $sms_message = $this->job_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "book_login"), array("id", "asc"));
                $user = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "id" => $uid), array("id", "asc"));
                $sms_message = preg_replace("/{{NAME}}/", ucfirst($user[0]['full_name']), $sms_message[0]["message"]);
                $sms_message = preg_replace("/{{MOBILE}}/", ucfirst($user[0]['mobile']), $sms_message);
                if ($pid != '' && $tid != '') {
                    $sms_message = preg_replace("/{{TESTPACK}}/", 'Test/Package', $sms_message);
                } else if ($pid != '') {
                    $sms_message = preg_replace("/{{TESTPACK}}/", 'Package', $sms_message);
                } else {
                    $sms_message = preg_replace("/{{TESTPACK}}/", 'Test', $sms_message);
                }
                $mobile = $user[0]['mobile'];
                $this->load->helper("sms");
                $notification = new Sms();
                if ($mobile != NULL) {
                    $mb_length = strlen($mobile);
                    //echo $mobile."<br>".$test_package."<br>".$sms_message; die();
                    if ($mb_length == 10) {
                        $notification::send($mobile, $sms_message);
                    }
                    if ($mb_length == 11 || $mb_length == 12 || $mb_length == 13) {
                        $check_phone = substr($mobile, 0, 2);
                        $check_phone1 = substr($mobile, 0, 1);
                        $check_phone2 = substr($mobile, 0, 3);
                        if ($check_phone2 == '+91') {
                            $get_phone = substr($mobile, 3);
                            $notification::send($get_phone, $sms_message);
                        }
                        if ($check_phone == '91') {
                            $get_phone = substr($mobile, 2);
                            $notification::send($get_phone, $sms_message);
                        }
                        if ($check_phone1 == '0') {
                            $get_phone = substr($mobile, 1);
                            $notification::send($get_phone, $sms_message);
                        }
                    }
                }
                // Referral amount for first test book//
                /* Nishit send sms end */

                $destail = $this->job_model->master_fun_get_tbl_val("customer_master", array("status" => 1, "id" => $uid), array("id", "asc"));
                $config['mailtype'] = 'html';
                $this->email->initialize($config);

                $message = '<div style="padding:0 4%;">
                    <h4><b>Dear </b>' . $destail[0]['full_name'] . '</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Booking successfully. </p>
                     <p style="color:#7e7e7e;font-size:13px;"> You Booked : . ' . implode($test_package_name, ', ') . '  </p>  
<p style="color:#7e7e7e;font-size:13px;"> Your Booked Total Amount is Rs. ' . $this->input->post("total_amount") . '  </p>
    <p style="color:#7e7e7e;font-size:13px;"> Discount is ' . $this->input->post('discount') . '%  </p>
        <p style="color:#7e7e7e;font-size:13px;"> Your Booked Payable Amount is Rs. ' . $payable . '  </p>
        <p style="color:#7e7e7e;font-size:13px;"> Payment Type : Cash on Blood Collection</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
                $message = $email_cnt->get_design($message);
                $this->email->to($destail[0]['email']);
                $this->email->from($this->config->item('admin_booking_email'), 'AirmedLabs');
                $this->email->subject('Test Book Successfully');
                $this->email->message($message);
                $this->email->send();

                $this->session->set_flashdata("success", array("Booked Successfully"));
                redirect("Admin/TelecallerPriscription", "refresh");
            }
            if ($submit_type == '0') {
                $data["login_data"] = logindata();
                $this->load->model("user_model");
                $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
                $cid = $this->uri->segment('3');
                $test = $this->input->post('test');
                $desc = $this->input->post('desc');
                $discount = $this->input->post('discount');
                $this->job_model->master_fun_update("prescription_upload", array("id", $this->uri->segment(3)), array("discount" => $discount));
                $update = $this->job_model->master_fun_update_multi("suggested_test", array('p_id' => $cid), array("status" => 0));
                /* for ($i = 0; $i < count($test); $i++) {
                  $data = array(
                  "p_id" => $cid,
                  "test_id" => $test[$i],
                  "description" => $desc[$i]
                  );
                  $chk = $this->job_model->master_fun_get_tbl_val("suggested_test", array('status' => 1, 'p_id' => $cid, 'test_id' => $test[$i]), array("id", "asc"));
                  $test_check = $this->job_model->master_fun_get_tbl_val("suggested_test", array('status' => 1, 'p_id' => $cid), array("id", "asc"));
                  $insert = $this->job_model->master_fun_insert("suggested_test", $data);
                  $upd = $this->job_model->master_fun_update("prescription_upload", array("id", $cid), array("status" => "2"));
                  }

                  for ($i = 0; $i < count($test); $i++) {
                  print_R($test);
                  $data = $this->job_model->master_fun_get_tbl_val("test_master", array("status" => 1, 'id' => $test[$i]), array("id", "asc"));
                  $price = $price + $data[0]['price'];
                  $test_name_mail[$i] = $data[0]['test_name'];
                  } */


                $price = 0;
                $test_name_mail = array();
                foreach ($test as $key) {
                    $tn = explode("-", $key);
                    if ($tn[0] == 't') {
                        //$this->job_model->master_fun_insert("job_test_list_master", array('job_fk' => $insert, "test_fk" => $tn[1]));
                        $result = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $test_city . "' AND `test_master`.`id`='" . $tn[1] . "'");
                        $price += $result[0]["price"];
                        $test_name_mail[] = $result[0]["test_name"];
                        $data = array(
                            "p_id" => $cid,
                            "test_id" => $tn[1]
                                //"description" => $desc[$i]
                        );
                        $chk = $this->job_model->master_fun_get_tbl_val("suggested_test", array('status' => 1, 'p_id' => $cid, 'test_id' => $tn[1]), array("id", "asc"));
                        $test_check = $this->job_model->master_fun_get_tbl_val("suggested_test", array('status' => 1, 'p_id' => $cid), array("id", "asc"));
                        $insert = $this->job_model->master_fun_insert("suggested_test", $data);
                        $upd = $this->job_model->master_fun_update("prescription_upload", array("id", $cid), array("status" => "2"));
                    }
                    if ($tn[0] == 'p') {
                        $this->job_model->master_fun_insert("book_package_master", array("cust_fk" => $uid, "package_fk" => $tn[1], 'job_fk' => $insert, "status" => "1", "type" => "2"));
                        $query = $this->db->get_where('package_master_city_price', array('package_fk' => $tn[1], "city_fk" => $test_city));
                        $result = $query->result();
                        $query1 = $this->db->get_where('package_master', array('id' => $tn[1]));
                        $result1 = $query1->result();
                        $price += $result[0]->d_price;
                        $test_name_mail[] = $result1[0]->title;
                    }
                }


                $data = $this->job_model->master_fun_get_tbl_val("prescription_upload", array("id" => $cid), array("id", "asc"));
                $cust_fk = $data[0]['cust_fk'];
                $img = $data[0]['image'];
                $desc = $data[0]['description'];
                $orderid = $data[0]['order_id'];
                $created_date = $data[0]['created_date'];
                $data1 = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "id" => $cust_fk), array("id", "asc"));

                $device_id = $data1[0]['device_id'];
                $mobile = $data1[0]['mobile'];
                $name = $data1[0]['full_name'];
                $email = $data1[0]['email'];
                $device_type = $data1[0]['device_type'];
                $message = "Your Suggested Test has been Generated";

                if ($device_type == 'android') {
                    $notification_data = array("title" => "Airmed Path Lab", "message" => $message, "type" => "suggested_test", "id" => $cid, "img" => $img, "desc" => $desc, "created_date" => $created_date, "order_id" => $orderid);
                    //print_r($notification_data); die();
                    $pushServer = new PushServer();
                    $pushServer->pushToGoogle($device_id, $notification_data);
                    //print_r($result);
                }
                if ($device_type == 'ios') {
                    $url = 'http://website-demo.in/patholabpush/push.php?device_id=' . $device_id . '&msg=' . $message . '&type=suggested_test&testid=&testname=&testprice=&testdate=&id=' . $cid . '&desc=' . $desc . '&date=' . $created_date . '&orderid=' . $orderid . '&img=' . $img;
                    $url = str_replace(" ", "%20", $url);
                    $data = $this->get_content($url);

                    $data2 = json_decode($data);
                }
                /* Nishit send sms code start */
                $sms_message = $this->job_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "Suggested_Test_Generated"), array("id", "asc"));
                $sms_message = preg_replace("/{{NAME}}/", ucfirst($data1[0]["full_name"]), $sms_message[0]["message"]);
                $sms_message = preg_replace("/{{OID}}/", $orderid, $sms_message);
                $sms_message = preg_replace("/{{PRICE}}/", '', $sms_message);
                $this->load->helper("sms");
                $notification = new Sms();
                $mb_length = strlen($mobile);
                if ($mb_length == 10) {
                    $notification::send($mobile, $sms_message);
                }
                if ($mb_length == 11 || $mb_length == 12 || $mb_length == 13) {
                    $check_phone = substr($mobile, 0, 2);
                    $check_phone1 = substr($mobile, 0, 1);
                    $check_phone2 = substr($mobile, 0, 3);
                    if ($check_phone2 == '+91') {
                        $get_phone = substr($mobile, 3);
                        $notification::send($get_phone, $sms_message);
                    }
                    if ($check_phone == '91') {
                        $get_phone = substr($mobile, 2);
                        $notification::send($get_phone, $sms_message);
                    }
                    if ($check_phone1 == '0') {
                        $get_phone = substr($mobile, 1);
                        $notification::send($get_phone, $sms_message);
                    }
                }
                /* Nishit send sms code end */
                $config['mailtype'] = 'html';
                $pathToUploadedFile = base_url() . "upload/" . $img;
                $this->email->initialize($config);
                $message1 = '<div style="padding:0 4%;">
                    <h4><b>Dear, ' . $name . '</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Suggested Test has been Generated. </p>
						 <p style="color:#7e7e7e;font-size:13px;">Your Suggested Test are ' . implode($test_name_mail, ',') . ' </p>
                        <p style="color:#7e7e7e;font-size:13px;">Your Order ID : ' . $orderid . '</p>    
		<p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
                $message1 = $email_cnt->get_design($message1);
                $this->email->to($email);
                $this->email->from($this->config->item('admin_booking_email'), "Airmed PathLabs");
                $this->email->subject("Suggested Test has been Generated");
                $this->email->message($message1);
                $this->email->send();

                $this->session->set_flashdata("success", array("Test Suggested Succesfully"));
                redirect("Admin/TelecallerPriscription", "refresh");
            }
        }



        $data['query'] = $this->job_model->prescription_details($data['cid']);
        $data['test'] = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $data['query'][0]["city"] . "'");
        $data['test_check'] = $this->job_model->get_suggested_test($data['cid']);
        $data['state'] = $this->job_model->master_fun_get_tbl_val("state", array('status' => 1), array("state_name", "asc"));
        $data['city'] = $this->job_model->master_fun_get_tbl_val("test_cities", array('status' => 1), array("name", "asc"));
        $data['user_info'] = array();
        if ($data['query'][0]["cust_fk"] != '') {
            $data['user_info'] = $this->job_model->master_fun_get_tbl_val("customer_master", array('id' => $data['query'][0]["cust_fk"]), array("id", "asc"));
        }
        $data['test_cities'] = $this->job_model->master_fun_get_tbl_val("test_cities", array('status' => 1), array("id", "asc"));
        if ($data['query'][0]["city"] == null) {
            $data['query'][0]["city"] = 1;
        }
        $data['test'] = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $data['query'][0]["city"] . "'");
        /* $data["package"] = $this->job_model->get_val("SELECT 
          `package_master`.*,
          `package_master_city_price`.`a_price` AS `a_price1`,
          `package_master_city_price`.`d_price` AS `d_price1`
          FROM
          `package_master`
          INNER JOIN `package_master_city_price`
          ON `package_master`.`id` = `package_master_city_price`.`package_fk`
          WHERE `package_master`.`status` = '1'
          AND `package_master_city_price`.`status` = '1' AND `package_master_city_price`.`city_fk` = '" . $data['query'][0]["city"] . "' "); */
        $this->job_model->master_fun_update("prescription_upload", array("id", $this->uri->segment(3)), array("is_read" => "1"));
        $data['unread'] = $this->job_model->master_fun_get_tbl_val("prescription_upload", array('status' => 1, "is_read" => "0"), array("id", "asc"));
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('prescription_details_talycaller', $data);
        $this->load->view('footer');
    }

    function TelecallerCallBooking_old() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->helper("Email");
        $email_cnt = new Email;
        $this->load->model('job_model');
        $data["login_data"] = logindata();
        $added_by = $data["login_data"]["id"];
        $data['branchlist'] = $this->job_model->get_val("SELECT * from branch_master where status='1'");
        $data['sample_from'] = $this->job_model->get_val("SELECT * from sample_from where status='1'");
        $data['payment_type'] = $this->job_model->get_val("SELECT * from payment_type_master where status='1'");
        $data["cntr_arry"] = array();
        $data["branch_city_arry"] = array();
        if (!empty($data["login_data"]['branch_fk'])) {
            foreach ($data["login_data"]['branch_fk'] as $key) {
                $data["cntr_arry"][] = $key["branch_fk"];
                $b_data = $this->job_model->get_val("SELECT * from branch_master where id='" . $key["branch_fk"] . "'");
                $data["branch_city_arry"][] = $b_data[0]["city"];
            }
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required');
        $this->form_validation->set_rules('test_city', 'Test city', 'trim|required');

        if ($this->form_validation->run() != FALSE) {

            $customer = $this->input->post("customer");
            $name = $this->input->post("name");
            $phone = $this->input->post("phone");
            $email = $this->input->post("email");
            $noify_cust = $this->input->post("noify_cust");
            if ($noify_cust == null) {
                $noify_cust = 0;
            }
            if ($email == '') {
                $email = 'noreply@airmedlabs.com';
            }
            $gender = $this->input->post("gender");
            $dob = $this->input->post("dob");
            $test_city = $this->input->post("test_city");
            $address = $this->input->post("address");
            $note = $this->input->post("note");
            $discount = $this->input->post("discount");
            $payable = $this->input->post("payable");
            $test = $this->input->post("test");
            $referral_by = $this->input->post("referral_by");
            $source = $this->input->post("source");
            $phlebo = $this->input->post("phlebo");
            $phlebo_date = $this->input->post("phlebo_date");
            $phlebo_time = $this->input->post("phlebo_time");
            $notify = $this->input->post("notify");
            $branch = $this->input->post("branch");
            $total_amount = $this->input->post("total_amount");
            if ($branch == '') {
                $branch = 1;
            }
            $discount_type = $this->input->post("discount_type");
            $payment_via = $this->input->post("payment_via");
            $received_amount = $this->input->post("received_amount");
            /* Check discount start */
            if ($discount_type == 'flat') {
                $f_payable_amount = $payable + $received_amount;
                $discount = 100 - ($f_payable_amount * 100 / $total_amount);
                $discount = number_format((float) $discount, 2, '.', '');
            }
            /* End */

            $sample_from = $this->input->post("sample_from");
            //echo "<pre>"; print_r($_POST); die();
            $order_id = $this->get_job_id($test_city);
            //$barcode = $this->util_lib->barcode_generate($branch);
            $barcode = '';
            $date = date('Y-m-d H:i:s');
            if ($customer != '') {
                $c_data = array(
                    "full_name" => $name,
                    "gender" => $gender,
                    "email" => $email,
                    "mobile" => $phone,
                    "address" => $address,
                    "dob" => $dob
                );
                $this->job_model->master_fun_update("customer_master", array("id", $customer), $c_data);

                $price = 0;
                foreach ($test as $key) {
                    $tn = explode("-", $key);
                    if ($tn[0] == 't') {
                        $result = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $test_city . "' AND `test_master`.`id`='" . $tn[1] . "'");
                        $price += $result[0]["price"];
                        $test_package_name[] = $result[0]["test_name"];
                    }
                    if ($tn[0] == 'p') {
                        //print_r(array('package_fk' => $tn[1], "city_fk" => $test_city)); die();
                        $query = $this->db->get_where('package_master_city_price', array('package_fk' => $tn[1], "city_fk" => $test_city, "status" => "1"));
                        $result = $query->result();
                        $query1 = $this->db->get_where('package_master', array('id' => $tn[1]));
                        $result1 = $query1->result();
                        $price += $result[0]->d_price;
                        $test_package_name[] = $result1[0]->title;
                    }
                }
                /* Nishit book phlebo start */
                $test_for = $this->input->post("test_for");
                $testforself = "self";
                $family_mem_id = 0;
                if ($test_for == "new") {
                    $f_name = $this->input->post("f_name");
                    $family_relation = $this->input->post("family_relation");
                    $relation_details = $this->job_model->get_val("SELECT * from relation_master where id='" . $family_relation . "'");
                    $f_phone = $this->input->post("f_phone");
                    $f_email = $this->input->post("f_email");
                    $f_dob = $this->input->post("f_dob");
                    $family_gender = $this->input->post("family_gender");
                    if ($f_name != '' && $family_relation != '') {
                        $family_mem_id = $this->job_model->master_fun_insert("customer_family_master", array("user_fk" => $customer, "gender" => $family_gender, "dob" => $f_dob, "name" => $f_name, "relation_fk" => $family_relation, "email" => $f_email, "phone" => $f_phone, "status" => "1", "created_date" => date("Y-m-d H:i:s")));
                        $testforself = "family";
                    }
                } else if ($test_for != '') {
                    $family_mem_id = $test_for;
                    $testforself = "family";
                }
                $address = $this->input->get_post("address");
                $date1 = $this->input->get_post("phlebo_date");
                $time_slot_id = $this->input->get_post("phlebo_time");
                $emergency = $this->input->post("emergency");
                $collection_charge = $this->input->post("collection_charge");
                if ($emergency == 1) {
                    $date1 = date("Y-m-d");
                    $time_slot_id = '';
                    $emergency = 1;
                } else {
                    $emergency = 0;
                }
                /* $emergency_req = $this->input->get_post("emergency_req");
                  if ($emergency_req == "true") {
                  $emergency_req = 1;
                  $time_slot_id = 0;
                  } else {
                  $emergency_req = 0;
                  } */
                //echo "<pre>";print_r(array("user_fk" => $uid, "type" => $b_type, "family_member_fk" => $family_mem_id, "address" => $address, "date" => $date1, "time_slot_fk" => $time_slot_id, "emergency" => $emergency_req, "status" => "1", "createddate" => date("Y-m-d H:i:s"))); die();
                $booking_fk = $this->job_model->master_fun_insert("booking_info", array("user_fk" => $customer, "type" => $testforself, "family_member_fk" => $family_mem_id, "address" => $address, "date" => $date1, "time_slot_fk" => $time_slot_id, "emergency" => $emergency, "status" => "1", "createddate" => date("Y-m-d H:i:s")));
                /* Nishit book phlebo end */
                if ($collection_charge == 1) {
                    $price = $price + 100;
                }
                /* Clinical history Code Start  */
                $check = $this->input->post("desc");
                //echo "<pre>"; print_R($_FILES); die();
                if ($check == '1') {
                    $message = $this->input->post('message');
                    $path = 'upload/doctor_description/';
                    if (!file_exists($path)) {
                        mkdir($path, 0777, true);
                    }
                    $file_loop = count($_FILES['file']['name']);
                    $files = $_FILES;
                    if (!empty($_FILES['file']['name'])) {

                        for ($i = 0; $i < $file_loop; $i++) {
                            $_FILES['file']['name'] = $files['file']['name'];
                            $_FILES['file']['type'] = $files['file']['type'];
                            $_FILES['file']['tmp_name'] = $files['file']['tmp_name'];
                            $_FILES['file']['error'] = $files['file']['error'];
                            $_FILES['file']['size'] = $files['file']['size'];
                            $config['upload_path'] = $path;
                            $config['allowed_types'] = '*';
                            $config['file_name'] = $files['file']['name'][$i];
                            $org[] = $config['file_name'];
                            $config['file_name'] = $i . time() . "_" . $files['file']['name'];
                            //$config['file_name'] = $files['file']['name'][$i];
                            $config['file_name'] = str_replace(' ', '_', $config['file_name']);
                            $config['overwrite'] = FALSE;
                            $this->load->library('upload', $config);
                            $this->upload->initialize($config);
                            $this->upload->do_upload("file");
                            $uploads[] = $config['file_name'];
                            //print_r($uploads); die();
                        }
                        $file = implode(',', $uploads);
                    }
                } else {
                    $check = '0';
                }
                /* END */
                $data = array(
                    "order_id" => $order_id,
                    "cust_fk" => $customer,
                    "doctor" => $referral_by,
                    "other_reference" => $source,
                    "date" => $date,
                    "price" => $price,
                    "branch_fk" => $branch,
                    "status" => '6',
                    "payment_type" => "Cash On Delivery",
                    "discount" => $discount,
                    "payable_amount" => $payable,
                    "test_city" => $test_city,
                    "note" => $this->input->post('note'),
                    "added_by" => $data["login_data"]["id"],
                    "booking_info" => $booking_fk,
                    "collection_charge" => $collection_charge,
                    "notify_cust" => $noify_cust,
                    "date" => date("Y-m-d H:i:s"),
                    "barcode" => $barcode,
                    "sample_from" => $sample_from,
                    "clinical_history" => $check,
                    "prescription_message" => $message,
                    "prescription_file" => $file
                );
                //print_r($data); die();
                $insert = $this->job_model->master_fun_insert("job_master", $data);
                /* foreach ($test as $key) {
                  $tn = explode("-", $key);
                  if ($tn[0] == 't') {
                  $this->job_model->master_fun_insert("job_test_list_master", array('job_fk' => $insert, "test_fk" => $tn[1]));
                  }
                  if ($tn[0] == 'p') {
                  $this->job_model->master_fun_insert("book_package_master", array("cust_fk" => $uid, "package_fk" => $tn[1], 'job_fk' => $insert, "status" => "1", "type" => "2"));
                  $this->check_active_package($tn[1], $insert, $customer);
                  }
                  } */

                foreach ($test as $key) {
                    $tn = explode("-", $key);
                    if ($tn[0] == 't') {
                        $this->job_model->master_fun_insert("job_test_list_master", array('job_fk' => $insert, "test_fk" => $tn[1]));
                        $tst_price = $this->job_model->get_val("select price from test_master_city_price where test_fk='" . $tn[1] . "' and city_fk='" . $test_city . "' and status='1'");
                        $this->job_model->master_fun_insert("booked_job_test", array("job_fk" => $insert, "test_city" => $test_city, "test_fk" => "t-" . $tn[1], "price" => $tst_price[0]["price"]));
                    }
                    if ($tn[0] == 'p') {
                        $this->job_model->master_fun_insert("book_package_master", array("cust_fk" => $uid, "package_fk" => $tn[1], 'job_fk' => $insert, "status" => "1", "type" => "2"));
                        $tst_price = $this->job_model->get_val("select d_price from package_master_city_price where package_fk='" . $tn[1] . "' and city_fk='" . $test_city . "' and status='1'");
                        $this->job_model->master_fun_insert("booked_job_test", array("job_fk" => $insert, "test_city" => $test_city, "test_fk" => "p-" . $tn[1], "price" => $tst_price[0]["d_price"]));
                        $this->check_active_package($tn[1], $insert, $customer);
                    }
                }

                //echo "<pre>";print_r($data);
                //die();
            } else {

                $result = $this->job_model->get_val("SELECT * FROM `customer_master` WHERE `status`='1' AND active='1' AND `mobile`='" . $phone . "' ORDER BY id ASC");
                if (empty($result)) {
                    $result1 = $this->job_model->get_val("SELECT * FROM `customer_master` WHERE `status`='1' AND active='1' AND `email`='" . $email . "' ORDER BY id ASC");
                    if (empty($result1) || $email = "noreply@airmedlabs.com") {
                        $password = rand(11111111, 9999999);
                        $c_data = array(
                            "full_name" => $name,
                            "gender" => $gender,
                            "email" => $email,
                            "mobile" => $phone,
                            "address" => $address,
                            "password" => $password,
                            "dob" => $dob,
                            "created_date" => date("Y-m-d H:i:s")
                        );
                        $customer = $this->job_model->master_fun_insert("customer_master", $c_data);
                        $config['mailtype'] = 'html';
                        $this->email->initialize($config);

                        $message = '<div style="padding:0 4%;">
                    <h4><b>Create Account</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your account successfully created. </p>
                        <p style="color:#7e7e7e;font-size:13px;"> Username/Email : . ' . $email . '  </p>  
                        <p style="color:#7e7e7e;font-size:13px;"> Password : ' . $password . '  </p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
                        $message = $email_cnt->get_design($message);
                        $this->email->to($email);
                        $this->email->from($this->config->item('admin_booking_email'), 'Airmed PathLabs');
                        $this->email->subject('Account Created Successfully');
                        $this->email->message($message);
                        if ($noify_cust == 1) {
                            $this->email->send();
                        }
                    } else {
                        $customer = $result1[0]["id"];
                    }
                } else {
                    $customer = $result[0]["id"];
                }
                //echo $customer;
                // die("OK");
                $price = 0;
                foreach ($test as $key) {
                    $tn = explode("-", $key);
                    if ($tn[0] == 't') {
                        $result = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $test_city . "' AND `test_master`.`id`='" . $tn[1] . "'");
                        $price += $result[0]["price"];
                        $test_package_name[] = $result[0]["test_name"];
                    }
                    if ($tn[0] == 'p') {
                        //print_r(array('package_fk' => $tn[1], "city_fk" => $test_city)); die();
                        $query = $this->db->get_where('package_master_city_price', array('package_fk' => $tn[1], "city_fk" => $test_city, "status" => "1"));
                        $result = $query->result();
                        $query1 = $this->db->get_where('package_master', array('id' => $tn[1]));
                        $result1 = $query1->result();
                        $price += $result[0]->d_price;
                        $test_package_name[] = $result1[0]->title;
                    }
                }
                /* Nishit book phlebo start */
                $test_for = $this->input->post("test_for");
                $testforself = "self";
                $family_mem_id = 0;
                if ($test_for == "new") {
                    $f_name = $this->input->post("f_name");
                    $family_relation = $this->input->post("family_relation");
                    $f_phone = $this->input->post("f_phone");
                    $f_email = $this->input->post("f_email");
                    $f_dob = $this->input->post("f_dob");
                    $family_gender = $this->input->post("family_gender");
                    if ($f_name != '' && $family_relation != '') {
                        $family_mem_id = $this->job_model->master_fun_insert("customer_family_master", array("user_fk" => $customer, "dob" => $f_dob, "gender" => $family_gender, "name" => $f_name, "relation_fk" => $family_relation, "email" => $f_email, "phone" => $f_phone, "status" => "1", "created_date" => date("Y-m-d H:i:s")));
                        $testforself = "family";
                    }
                } else if ($test_for != '') {
                    $family_mem_id = $test_for;
                    $testforself = "family";
                }
                $address = $this->input->get_post("address");
                $date1 = $this->input->get_post("phlebo_date");
                $time_slot_id = $this->input->get_post("phlebo_time");
                /* $emergency_req = $this->input->get_post("emergency_req");
                  if ($emergency_req == "true") {
                  $emergency_req = 1;
                  $time_slot_id = 0;
                  } else {
                  $emergency_req = 0;
                  } */
                //echo "<pre>";print_r(array("user_fk" => $uid, "type" => $b_type, "family_member_fk" => $family_mem_id, "address" => $address, "date" => $date1, "time_slot_fk" => $time_slot_id, "emergency" => $emergency_req, "status" => "1", "createddate" => date("Y-m-d H:i:s"))); die();
                $booking_fk = $this->job_model->master_fun_insert("booking_info", array("user_fk" => $customer, "type" => $testforself, "family_member_fk" => $family_mem_id, "address" => $address, "date" => $this->input->get_post("phlebo_date"), "time_slot_fk" => $this->input->get_post("phlebo_time"), "emergency" => 0, "status" => "1", "createddate" => date("Y-m-d H:i:s")));
                /* Nishit book phlebo end */
                if ($collection_charge == 1) {
                    $price = $price + 100;
                }
                /* Clinical history Code Start  */
                $check = $this->input->post("desc");
                if ($check == '1') {
                    $message = $this->input->post('message');
                    $path = 'upload/doctor_description/';
                    if (!file_exists($path)) {
                        mkdir($path, 0777, true);
                    }
                    $file_loop = count($_FILES['file']['name']);
                    $files = $_FILES;
                    if (!empty($_FILES['file']['name'])) {

                        for ($i = 0; $i < $file_loop; $i++) {
                            $_FILES['file']['name'] = $files['file']['name'];
                            $_FILES['file']['type'] = $files['file']['type'];
                            $_FILES['file']['tmp_name'] = $files['file']['tmp_name'];
                            $_FILES['file']['error'] = $files['file']['error'];
                            $_FILES['file']['size'] = $files['file']['size'];
                            $config['upload_path'] = $path;
                            $config['allowed_types'] = '*';
                            $config['file_name'] = $files['file']['name'][$i];
                            $org[] = $config['file_name'];
                            $config['file_name'] = $i . time() . "_" . $files['file']['name'];
                            //$config['file_name'] = $files['file']['name'][$i];
                            $config['file_name'] = str_replace(' ', '_', $config['file_name']);
                            $config['overwrite'] = FALSE;
                            $this->load->library('upload', $config);
                            $this->upload->initialize($config);
                            $this->upload->do_upload("file");
                            $uploads[] = $config['file_name'];
                            //print_r($uploads); die();
                        }
                        $file = implode(',', $uploads);
                    }
                } else {
                    $check = '0';
                }
                /* END */
                $data = array(
                    "order_id" => $order_id,
                    "cust_fk" => $customer,
                    "doctor" => $referral_by,
                    "other_reference" => $source,
                    "date" => $date,
                    "price" => $price,
                    "status" => '6',
                    "payment_type" => "Cash On Delivery",
                    "discount" => $discount,
                    "payable_amount" => $payable,
                    "branch_fk" => $branch,
                    "test_city" => $test_city,
                    "note" => $this->input->post('note'),
                    "added_by" => $data["login_data"]["id"],
                    "booking_info" => $booking_fk,
                    "notify_cust" => $noify_cust,
                    "collection_charge" => $collection_charge,
                    "date" => date("Y-m-d H:i:s"),
                    "sample_from" => $sample_from,
                    "clinical_history" => $check,
                    "prescription_message" => $message,
                    "prescription_file" => $file
                );
                $insert = $this->job_model->master_fun_insert("job_master", $data);
                /* foreach ($test as $key) {
                  $tn = explode("-", $key);
                  if ($tn[0] == 't') {
                  $this->job_model->master_fun_insert("job_test_list_master", array('job_fk' => $insert, "test_fk" => $tn[1]));
                  }
                  if ($tn[0] == 'p') {
                  $this->job_model->master_fun_insert("book_package_master", array("cust_fk" => $uid, "package_fk" => $tn[1], 'job_fk' => $insert, "status" => "1", "type" => "2"));
                  $this->check_active_package($tn[1], $insert, $customer);
                  }
                  } */
                foreach ($test as $key) {
                    $tn = explode("-", $key);
                    if ($tn[0] == 't') {
                        $this->job_model->master_fun_insert("job_test_list_master", array('job_fk' => $insert, "test_fk" => $tn[1]));
                        $tst_price = $this->job_model->get_val("select price from test_master_city_price where test_fk='" . $tn[1] . "' and city_fk='" . $test_city . "' and status='1'");
                        $this->job_model->master_fun_insert("booked_job_test", array("job_fk" => $insert, "test_city" => $test_city, "test_fk" => "t-" . $tn[1], "price" => $tst_price[0]["price"]));
                    }
                    if ($tn[0] == 'p') {
                        $this->job_model->master_fun_insert("book_package_master", array("cust_fk" => $uid, "package_fk" => $tn[1], 'job_fk' => $insert, "status" => "1", "type" => "2"));
                        $tst_price = $this->job_model->get_val("select d_price from package_master_city_price where package_fk='" . $tn[1] . "' and city_fk='" . $test_city . "' and status='1'");
                        $this->job_model->master_fun_insert("booked_job_test", array("job_fk" => $insert, "test_city" => $test_city, "test_fk" => "p-" . $tn[1], "price" => $tst_price[0]["d_price"]));
                        $this->check_active_package($tn[1], $insert, $customer);
                    }
                }
                /* if ($discount > 0) {
                  $this->job_model->master_fun_insert("job_log", array("job_fk" => $insert, "created_by" => "", "updated_by" => $added_by, "deleted_by" => "", "job_status" => '', "message_fk" => "24", "date_time" => date("Y-m-d H:i:s")));
                  }
                  $received_amount = $_POST["received_amount"];
                  if ($received_amount > 0) {
                  $this->job_model->master_fun_insert("job_master_receiv_amount", array("job_fk" => $insert, "added_by" => $added_by, "payment_type" => $payment_via, "amount" => $received_amount, "createddate" => date("Y-m-d H:i:s")));
                  $this->job_model->master_fun_insert("job_log", array("job_fk" => $insert, "created_by" => "", "updated_by" => $added_by, "deleted_by" => "", "job_status" => '', "message_fk" => "22", "date_time" => date("Y-m-d H:i:s")));
                  } */
                //$this->session->set_flashdata("success", array("Test successfully Booked."));
                //redirect("Admin/TelecallerCallBooking", "refresh");
            }
            /* Receive amount start */
            $discount_type = $this->input->post("discount_type");
            $payment_via = $this->input->post("payment_via");
            $received_amount = $this->input->post("received_amount");
            if ($received_amount > 0) {
                $this->job_model->master_fun_insert("job_master_receiv_amount", array("job_fk" => $insert, "added_by" => $added_by, "payment_type" => $payment_via, "amount" => $received_amount, "createddate" => date("Y-m-d H:i:s")));
                $this->job_model->master_fun_insert("job_log", array("job_fk" => $insert, "created_by" => "", "updated_by" => $added_by, "deleted_by" => "", "job_status" => '', "message_fk" => "22", "date_time" => date("Y-m-d H:i:s")));
            }
            /* END */
            $file = $this->pdf_invoice($insert);
            $this->job_model->master_fun_update("job_master", array("id" => $insert), array("invoice" => $file));
            /*
              if($phlebo != NULL) {
              $job_cnt = $this->job_model->master_num_rows("phlebo_assign_job", array("status" => "1", "job_fk" => $insert));
              if ($job_cnt == 0) {
              $data = array("job_fk" => $insert, "phlebo_fk" => $phlebo, "address" => $address, "date" =>$phlebo_date, "time" =>$phlebo_time, "created_date" => date("Y-m-d H:i:s"), "created_by" => $data["login_data"]["id"]);
              $this->job_model->master_fun_insert("phlebo_assign_job", $data);
              $this->job_model->master_fun_insert("job_log", array("job_fk" => $insert, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "message_fk" => "8", "date_time" => date("Y-m-d H:i:s")));
              } else {
              $data = array("job_fk" => $insert, "phlebo_fk" => $phlebo, "address" => $address, "date" =>$phlebo_date, "time" =>$phlebo_time, "updated_by" => $data["login_data"]["id"]);
              $this->job_model->master_fun_update("phlebo_assign_job", array("job_fk", $insert), $data);
              $this->job_model->master_fun_insert("job_log", array("job_fk" => $insert, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "message_fk" => "9", "date_time" => date("Y-m-d H:i:s")));
              }

              $phlebo_details = $this->job_model->master_fun_get_tbl_val("phlebo_master", array('id' => $phlebo), array("id", "asc"));
              $phlebo_job_details = $this->job_model->master_fun_get_tbl_val("phlebo_assign_job", array('job_fk' => $insert), array("id", "asc"));
              $job_details = $this->job_model->master_fun_get_tbl_val("job_master", array('id' => $insert), array("id", "asc"));
              $customer_details = $this->job_model->master_fun_get_tbl_val("customer_master", array('id' => $job_details[0]['cust_fk']), array("id", "asc"));

              $sms_message = $this->job_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "phlebo_msg"), array("id", "asc"));
              $sms_message = preg_replace("/{{NAME}}/", ucfirst($phlebo_details[0]["name"]), $sms_message[0]["message"]);
              $sms_message = preg_replace("/{{MOBILE}}/", $phlebo_details[0]["mobile"], $sms_message);
              $sms_message = preg_replace("/{{ORDERID}}/", $job_details[0]["order_id"], $sms_message);
              $sms_message = preg_replace("/{{CNAME}}/", $customer_details[0]["full_name"], $sms_message);
              $sms_message = preg_replace("/{{CMOBILE}}/", $customer_details[0]["mobile"], $sms_message);
              $sms_message = preg_replace("/{{CADDRESS}}/", $phlebo_job_details[0]["address"], $sms_message);
              $sms_message = preg_replace("/{{DATE}}/", $phlebo_job_details[0]["date"], $sms_message);
              $sms_message = preg_replace("/{{TIME}}/", $phlebo_job_details[0]["time"], $sms_message);
              //$sms_message="done";
              $mobile = $phlebo_details[0]['mobile'];
              $this->load->helper("sms");
              $notification = new Sms();
              $notification::send($mobile, $sms_message);
              if ($notify == 1) {
              $sms_message = $this->job_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "phlebo_msg_cust"), array("id", "asc"));
              $sms_message = preg_replace("/{{NAME}}/", ucfirst($customer_details[0]["full_name"]), $sms_message[0]["message"]);
              $sms_message = preg_replace("/{{MOBILE}}/", $customer_details[0]["mobile"], $sms_message);
              $sms_message = preg_replace("/{{ORDERID}}/", $job_details[0]["order_id"], $sms_message);
              $sms_message = preg_replace("/{{PNAME}}/", ucfirst($phlebo_details[0]["name"]), $sms_message);
              $sms_message = preg_replace("/{{PMOBILE}}/", $phlebo_details[0]["mobile"], $sms_message);
              $sms_message = preg_replace("/{{DATE}}/", $phlebo_job_details[0]["date"], $sms_message);
              $sms_message = preg_replace("/{{TIME}}/", $phlebo_job_details[0]["time"], $sms_message);
              $mobile = $customer_details[0]['mobile'];
              //$sms_message="done";
              $notification::send($mobile, $sms_message);
              }
              } */
            //$this->assign_phlebo_job($insert, $phlebo); 
            if ($discount > 0) {
                $this->job_model->master_fun_insert("job_log", array("job_fk" => $insert, "created_by" => "", "updated_by" => $added_by, "deleted_by" => "", "job_status" => '', "message_fk" => "24", "date_time" => date("Y-m-d H:i:s")));
            }
            $this->session->set_flashdata("success", array("Test successfully Booked."));
            redirect("job_master/ack/" . $insert);
            redirect("Admin/TelecallerCallBooking_old");
        } else {
			
            $data['success'] = $this->session->flashdata("success");
            $data['phlebo_list'] = $this->job_model->master_fun_get_tbl_val("phlebo_master", array('status' => 1, "type" => 1), array("name", "asc"));
            $data['source_list'] = $this->job_model->master_fun_get_tbl_val("source_master", array('status' => 1), array("name", "asc"));
            // $data['referral_list'] = $this->job_model->master_fun_get_tbl_val("doctor_master", array('status' => 1), array("full_name", "asc"));
            //$data['customer'] = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "full_name !=" => ""), array("full_name", "asc"));
            $data['test_cities'] = $this->job_model->master_fun_get_tbl_val("test_cities", array('status' => 1), array("id", "asc"));
            $data['unread'] = $this->job_model->master_fun_get_tbl_val("prescription_upload", array('status' => 1, "is_read" => "0"), array("id", "asc"));
            $data["relation1"] = $this->job_model->master_fun_get_tbl_val("relation_master", array('status' => "1"), array("name", "asc"));
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('talycaller_callbooking1', $data);
            $this->load->view('footer');
        }
    }

    /*   function TelecallerCallBooking() {
      if (!is_loggedin()) {
      redirect('login');
      }
      $this->load->helper("Email");
      $email_cnt = new Email;
      $this->load->model('job_model');
      $data["login_data"] = logindata();
      $added_by = $data["login_data"]["id"];
      $data['branchlist'] = $this->job_model->get_val("SELECT * from branch_master where status='1'");
      $data['sample_from'] = $this->job_model->get_val("SELECT * from sample_from where status='1'");
      $data['payment_type'] = $this->job_model->get_val("SELECT * from payment_type_master where status='1'");
      $data["cntr_arry"] = array();
      $data["branch_city_arry"] = array();
      if (!empty($data["login_data"]['branch_fk'])) {
      foreach ($data["login_data"]['branch_fk'] as $key) {
      $data["cntr_arry"][] = $key["branch_fk"];
      $b_data = $this->job_model->get_val("SELECT * from branch_master where id='" . $key["branch_fk"] . "'");
      $data["branch_city_arry"][] = $b_data[0]["city"];
      }
      }
      $this->load->library('form_validation');
      $this->form_validation->set_rules('name', 'Name', 'trim|required');
      $this->form_validation->set_rules('phone', 'Phone', 'trim|required');
      $this->form_validation->set_rules('test_city', 'Test city', 'trim|required');

      if ($this->form_validation->run() != FALSE) {

      $customer = $this->input->post("customer");
      $name = $this->input->post("name");
      $phone = $this->input->post("phone");
      $email = $this->input->post("email");
      $noify_cust = $this->input->post("noify_cust");
      if ($noify_cust == null) {
      $noify_cust = 0;
      }
      if ($email == '') {
      $email = 'noreply@airmedlabs.com';
      }
      $gender = $this->input->post("gender");
      $dob = $this->input->post("dob");
      if ($data["login_data"]["id"] == "121") {
      die($dob);
      }
      $test_city = $this->input->post("test_city");
      $address = $this->input->post("address");
      $note = $this->input->post("note");
      $discount = $this->input->post("discount");
      $payable = $this->input->post("payable");
      $test = $this->input->post("test");
      $referral_by = $this->input->post("referral_by");
      $source = $this->input->post("source");
      $phlebo = $this->input->post("phlebo");
      $phlebo_date = $this->input->post("phlebo_date");
      $phlebo_time = $this->input->post("phlebo_time");
      $notify = $this->input->post("notify");
      $branch = $this->input->post("branch");
      $total_amount = $this->input->post("total_amount");
      if ($branch == '') {
      $branch = 1;
      }
      $discount_type = $this->input->post("discount_type");
      $payment_via = $this->input->post("payment_via");
      $received_amount = $this->input->post("received_amount");
      $panel_fk = $this->input->post("panellist");


      if ($discount_type == 'flat') {
      $f_payable_amount = $payable + $received_amount;
      $discount = 100 - ($f_payable_amount * 100 / $total_amount);
      $discount = number_format((float) $discount, 2, '.', '');
      }


      $sample_from = $this->input->post("sample_from");
      //echo "<pre>"; print_r($_POST); die();
      $order_id = $this->get_job_id($test_city);
      //$barcode = $this->util_lib->barcode_generate($branch);
      $barcode = '';
      $date = date('Y-m-d H:i:s');
      if ($customer != '') {
      $c_data = array(
      "full_name" => $name,
      "gender" => $gender,
      "email" => $email,
      "mobile" => $phone,
      "address" => $address,
      "dob" => $dob
      );
      $this->job_model->master_fun_update("customer_master", array("id", $customer), $c_data);

      $price = 0;
      //print_r($test );

      foreach ($test as $key) {
      $tn = explode("-", $key);
      if ($tn[0] == 't') {
      $result = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $test_city . "' AND `test_master`.`id`='" . $tn[1] . "'");
      $price += $result[0]["price"];
      $test_package_name[] = $result[0]["test_name"];
      }
      if ($tn[0] == 'p') {
      //print_r(array('package_fk' => $tn[1], "city_fk" => $test_city)); die();
      $query = $this->db->get_where('package_master_city_price', array('package_fk' => $tn[1], "city_fk" => $test_city, "status" => "1"));
      $result = $query->result();
      $query1 = $this->db->get_where('package_master', array('id' => $tn[1]));
      $result1 = $query1->result();
      $price += $result[0]->d_price;
      $test_package_name[] = $result1[0]->title;
      }
      if ($tn[0] == 'pt') {
      $result = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`panel_tests`.`price` FROM
      `test_master`
      INNER JOIN `panel_tests`
      ON `test_master`.`id` = `panel_tests`.`test_fk` WHERE `test_master`.`status`='1' AND `panel_tests`.`status`='1' AND `panel_tests`.`city_fk`='" . $test_city . "'  AND `panel_tests`.`panel_fk` = '" . $panel_fk . "'   AND `test_master`.`id`='" . $tn[1] . "'");
      $price += $result[0]["price"];
      $test_package_name[] = $result[0]["test_name"];
      }
      }
      //                print_R($test_package_name);
      //              die($price."1");
      $test_for = $this->input->post("test_for");
      $testforself = "self";
      $family_mem_id = 0;
      if ($test_for == "new") {
      $f_name = $this->input->post("f_name");
      $family_relation = $this->input->post("family_relation");
      $relation_details = $this->job_model->get_val("SELECT * from relation_master where id='" . $family_relation . "'");
      $f_phone = $this->input->post("f_phone");
      $f_email = $this->input->post("f_email");
      $f_dob = $this->input->post("f_dob");
      $family_gender = $this->input->post("family_gender");
      if ($f_name != '' && $family_relation != '') {
      $family_mem_id = $this->job_model->master_fun_insert("customer_family_master", array("user_fk" => $customer, "gender" => $family_gender, "dob" => $f_dob, "name" => $f_name, "relation_fk" => $family_relation, "email" => $f_email, "phone" => $f_phone, "status" => "1", "created_date" => date("Y-m-d H:i:s")));
      $testforself = "family";
      }
      } else if ($test_for != '') {
      $family_mem_id = $test_for;
      $testforself = "family";
      }
      $address = $this->input->get_post("address");
      $date1 = $this->input->get_post("phlebo_date");
      $time_slot_id = $this->input->get_post("phlebo_time");
      $emergency = $this->input->post("emergency");
      $collection_charge = $this->input->post("collection_charge");
      if ($emergency == 1) {
      $date1 = date("Y-m-d");
      $time_slot_id = '';
      $emergency = 1;
      } else {
      $emergency = 0;
      }

      //echo "<pre>";print_r(array("user_fk" => $uid, "type" => $b_type, "family_member_fk" => $family_mem_id, "address" => $address, "date" => $date1, "time_slot_fk" => $time_slot_id, "emergency" => $emergency_req, "status" => "1", "createddate" => date("Y-m-d H:i:s"))); die();
      $booking_fk = $this->job_model->master_fun_insert("booking_info", array("user_fk" => $customer, "type" => $testforself, "family_member_fk" => $family_mem_id, "address" => $address, "date" => $date1, "time_slot_fk" => $time_slot_id, "emergency" => $emergency, "status" => "1", "createddate" => date("Y-m-d H:i:s")));
      if ($collection_charge == 1) {
      $price = $price + 100;
      }
      $check = $this->input->post("desc");
      //echo "<pre>"; print_R($_FILES); die();
      if ($check == '1') {
      $message = $this->input->post('message');
      $path = 'upload/doctor_description/';
      if (!file_exists($path)) {
      mkdir($path, 0777, true);
      }
      $file_loop = count($_FILES['file']['name']);
      $files = $_FILES;
      if (!empty($_FILES['file']['name'])) {

      for ($i = 0; $i < $file_loop; $i++) {
      $_FILES['file']['name'] = $files['file']['name'];
      $_FILES['file']['type'] = $files['file']['type'];
      $_FILES['file']['tmp_name'] = $files['file']['tmp_name'];
      $_FILES['file']['error'] = $files['file']['error'];
      $_FILES['file']['size'] = $files['file']['size'];
      $config['upload_path'] = $path;
      $config['allowed_types'] = '*';
      $config['file_name'] = $files['file']['name'][$i];
      $org[] = $config['file_name'];
      $config['file_name'] = $i . time() . "_" . $files['file']['name'];
      //$config['file_name'] = $files['file']['name'][$i];
      $config['file_name'] = str_replace(' ', '_', $config['file_name']);
      $config['overwrite'] = FALSE;
      $this->load->library('upload', $config);
      $this->upload->initialize($config);
      $this->upload->do_upload("file");
      $uploads[] = $config['file_name'];
      //print_r($uploads); die();
      }
      $file = implode(',', $uploads);
      }
      } else {
      $check = '0';
      }
      if ($_FILES["panel_document_file"]["name"]) {
      $config['upload_path'] = './upload/';
      $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf|doc';
      $config['file_name'] = time() . $_FILES["panel_document_file"]["name"];
      $this->load->library('upload', $config);
      if (!$this->upload->do_upload("panel_document_file")) {
      $error = array('error' => $this->upload->display_errors());
      } else {
      $data = array('upload_data' => $this->upload->data());
      $file_name = $data["upload_data"]["file_name"];
      $file_doc = $file_name;
      }
      }

      $data = array(
      "order_id" => $order_id,
      "cust_fk" => $customer,
      "doctor" => $referral_by,
      "other_reference" => $source,
      "date" => $date,
      "price" => $price,
      "branch_fk" => $branch,
      "status" => '6',
      "payment_type" => "Cash On Delivery",
      "discount" => $discount,
      "payable_amount" => $payable,
      "test_city" => $test_city,
      "note" => $this->input->post('note'),
      "added_by" => $data["login_data"]["id"],
      "booking_info" => $booking_fk,
      "collection_charge" => $collection_charge,
      "notify_cust" => $noify_cust,
      "date" => date("Y-m-d H:i:s"),
      "barcode" => $barcode,
      "sample_from" => $sample_from,
      "clinical_history" => $check,
      "prescription_message" => $message,
      "prescription_file" => $file,
      "document" => $file_doc
      );
      $insert = $this->job_model->master_fun_insert("job_master", $data);

      foreach ($test as $key) {
      $tn = explode("-", $key);
      if ($tn[0] == 't') {
      $this->job_model->master_fun_insert("job_test_list_master", array('job_fk' => $insert, "test_fk" => $tn[1], "is_panel" => "0"));
      $tst_price = $this->job_model->get_val("select price from test_master_city_price where test_fk='" . $tn[1] . "' and city_fk='" . $test_city . "' and status='1'");
      $this->job_model->master_fun_insert("booked_job_test", array("job_fk" => $insert, "test_city" => $test_city, "test_fk" => "t-" . $tn[1], "price" => $tst_price[0]["price"]));
      }
      if ($tn[0] == 'p') {
      $this->job_model->master_fun_insert("book_package_master", array("cust_fk" => $uid, "package_fk" => $tn[1], 'job_fk' => $insert, "status" => "1", "type" => "2"));
      $tst_price = $this->job_model->get_val("select d_price from package_master_city_price where package_fk='" . $tn[1] . "' and city_fk='" . $test_city . "' and status='1'");
      $this->job_model->master_fun_insert("booked_job_test", array("job_fk" => $insert, "test_city" => $test_city, "test_fk" => "p-" . $tn[1], "price" => $tst_price[0]["d_price"]));
      $this->check_active_package($tn[1], $insert, $customer);
      }
      if ($tn[0] == 'pt') {
      $this->job_model->master_fun_insert("job_test_list_master", array('job_fk' => $insert, "test_fk" => $tn[1], "is_panel" => "1"));

      $tst_price = $this->job_model->get_val("select price from panel_tests where panel_fk='" . $panel_fk . "' and  test_fk='" . $tn[1] . "' and city_fk='" . $test_city . "' and status='1'");
      $this->job_model->master_fun_insert("booked_job_test", array("job_fk" => $insert, "test_city" => $test_city, "panel_fk" => $panel_fk, "test_fk" => "pt-" . $tn[1], "price" => $tst_price[0]["price"]));
      //               echo $this->db->last_query();
      }
      }
      } else {

      $result = $this->job_model->get_val("SELECT * FROM `customer_master` WHERE `status`='1' AND active='1' AND `mobile`='" . $phone . "' ORDER BY id ASC");
      if (empty($result)) {
      $result1 = $this->job_model->get_val("SELECT * FROM `customer_master` WHERE `status`='1' AND active='1' AND `email`='" . $email . "' ORDER BY id ASC");
      if (empty($result1) || $email = "noreply@airmedlabs.com") {
      $password = rand(11111111, 9999999);
      $c_data = array(
      "full_name" => $name,
      "gender" => $gender,
      "email" => $email,
      "mobile" => $phone,
      "address" => $address,
      "password" => $password,
      "dob" => $dob,
      "created_date" => date("Y-m-d H:i:s")
      );
      $customer = $this->job_model->master_fun_insert("customer_master", $c_data);
      $config['mailtype'] = 'html';
      $this->email->initialize($config);

      $message = '<div style="padding:0 4%;">
      <h4><b>Create Account</b></h4>
      <p style="color:#7e7e7e;font-size:13px;">Your account successfully created. </p>
      <p style="color:#7e7e7e;font-size:13px;"> Username/Email : . ' . $email . '  </p>
      <p style="color:#7e7e7e;font-size:13px;"> Password : ' . $password . '  </p>
      <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
      </div>';
      $message = $email_cnt->get_design($message);
      $this->email->to($email);
      $this->email->from($this->config->item('admin_booking_email'), 'Airmed PathLabs');
      $this->email->subject('Account Created Successfully');
      $this->email->message($message);
      if ($noify_cust == 1) {
      $this->email->send();
      }
      } else {
      $customer = $result1[0]["id"];
      }
      } else {
      $customer = $result[0]["id"];
      }
      //echo $customer;
      // die("OK");
      $price = 0;

      foreach ($test as $key) {
      $tn = explode("-", $key);
      if ($tn[0] == 't') {
      $result = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $test_city . "' AND `test_master`.`id`='" . $tn[1] . "'");
      $price += $result[0]["price"];
      $test_package_name[] = $result[0]["test_name"];
      }
      if ($tn[0] == 'p') {
      //print_r(array('package_fk' => $tn[1], "city_fk" => $test_city)); die();
      $query = $this->db->get_where('package_master_city_price', array('package_fk' => $tn[1], "city_fk" => $test_city, "status" => "1"));
      $result = $query->result();
      $query1 = $this->db->get_where('package_master', array('id' => $tn[1]));
      $result1 = $query1->result();
      $price += $result[0]->d_price;
      $test_package_name[] = $result1[0]->title;
      }
      if ($tn[0] == 'pt') {
      $result = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`panel_tests`.`price` FROM
      `test_master`
      INNER JOIN `panel_tests`
      ON `test_master`.`id` = `panel_tests`.`test_fk` WHERE `test_master`.`status`='1' AND `panel_tests`.`status`='1' AND `panel_tests`.`city_fk`='" . $test_city . "'  AND `panel_tests`.`panel_fk` = '" . $panel_fk . "'   AND `test_master`.`id`='" . $tn[1] . "'");
      $price += $result[0]["price"];
      $test_package_name[] = $result[0]["test_name"];
      }
      }
      $test_for = $this->input->post("test_for");
      $testforself = "self";
      $family_mem_id = 0;
      if ($test_for == "new") {
      $f_name = $this->input->post("f_name");
      $family_relation = $this->input->post("family_relation");
      $f_phone = $this->input->post("f_phone");
      $f_email = $this->input->post("f_email");
      $f_dob = $this->input->post("f_dob");
      $family_gender = $this->input->post("family_gender");
      if ($f_name != '' && $family_relation != '') {
      $family_mem_id = $this->job_model->master_fun_insert("customer_family_master", array("user_fk" => $customer, "dob" => $f_dob, "gender" => $family_gender, "name" => $f_name, "relation_fk" => $family_relation, "email" => $f_email, "phone" => $f_phone, "status" => "1", "created_date" => date("Y-m-d H:i:s")));
      $testforself = "family";
      }
      } else if ($test_for != '') {
      $family_mem_id = $test_for;
      $testforself = "family";
      }
      $address = $this->input->get_post("address");
      $date1 = $this->input->get_post("phlebo_date");
      $time_slot_id = $this->input->get_post("phlebo_time");

      //echo "<pre>";print_r(array("user_fk" => $uid, "type" => $b_type, "family_member_fk" => $family_mem_id, "address" => $address, "date" => $date1, "time_slot_fk" => $time_slot_id, "emergency" => $emergency_req, "status" => "1", "createddate" => date("Y-m-d H:i:s"))); die();
      $booking_fk = $this->job_model->master_fun_insert("booking_info", array("user_fk" => $customer, "type" => $testforself, "family_member_fk" => $family_mem_id, "address" => $address, "date" => $this->input->get_post("phlebo_date"), "time_slot_fk" => $this->input->get_post("phlebo_time"), "emergency" => 0, "status" => "1", "createddate" => date("Y-m-d H:i:s")));
      if ($collection_charge == 1) {
      $price = $price + 100;
      }
      $check = $this->input->post("desc");
      if ($check == '1') {
      $message = $this->input->post('message');
      $path = 'upload/doctor_description/';
      if (!file_exists($path)) {
      mkdir($path, 0777, true);
      }
      $file_loop = count($_FILES['file']['name']);
      $files = $_FILES;
      if (!empty($_FILES['file']['name'])) {

      for ($i = 0; $i < $file_loop; $i++) {
      $_FILES['file']['name'] = $files['file']['name'];
      $_FILES['file']['type'] = $files['file']['type'];
      $_FILES['file']['tmp_name'] = $files['file']['tmp_name'];
      $_FILES['file']['error'] = $files['file']['error'];
      $_FILES['file']['size'] = $files['file']['size'];
      $config['upload_path'] = $path;
      $config['allowed_types'] = '*';
      $config['file_name'] = $files['file']['name'][$i];
      $org[] = $config['file_name'];
      $config['file_name'] = $i . time() . "_" . $files['file']['name'];
      //$config['file_name'] = $files['file']['name'][$i];
      $config['file_name'] = str_replace(' ', '_', $config['file_name']);
      $config['overwrite'] = FALSE;
      $this->load->library('upload', $config);
      $this->upload->initialize($config);
      $this->upload->do_upload("file");
      $uploads[] = $config['file_name'];
      //print_r($uploads); die();
      }
      $file = implode(',', $uploads);
      }
      } else {
      $check = '0';
      }
      if ($_FILES["panel_document_file"]["name"]) {
      $config['upload_path'] = './upload/';
      $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf|doc';
      $config['file_name'] = time() . $_FILES["panel_document_file"]["name"];
      $this->load->library('upload', $config);
      if (!$this->upload->do_upload("panel_document_file")) {
      $error = array('error' => $this->upload->display_errors());
      } else {
      $data = array('upload_data' => $this->upload->data());
      $file_name = $data["upload_data"]["file_name"];
      $file_doc = $file_name;
      }
      }
      $data = array(
      "order_id" => $order_id,
      "cust_fk" => $customer,
      "doctor" => $referral_by,
      "other_reference" => $source,
      "date" => $date,
      "price" => $price,
      "status" => '6',
      "payment_type" => "Cash On Delivery",
      "discount" => $discount,
      "payable_amount" => $payable,
      "branch_fk" => $branch,
      "test_city" => $test_city,
      "note" => $this->input->post('note'),
      "added_by" => $data["login_data"]["id"],
      "booking_info" => $booking_fk,
      "notify_cust" => $noify_cust,
      "collection_charge" => $collection_charge,
      "date" => date("Y-m-d H:i:s"),
      "sample_from" => $sample_from,
      "clinical_history" => $check,
      "prescription_message" => $message,
      "prescription_file" => $file,
      "document" => $file_doc
      );
      $insert = $this->job_model->master_fun_insert("job_master", $data);
      foreach ($test as $key) {
      $tn = explode("-", $key);
      if ($tn[0] == 't') {
      $this->job_model->master_fun_insert("job_test_list_master", array('job_fk' => $insert, "test_fk" => $tn[1], "is_panel" => "0"));
      $tst_price = $this->job_model->get_val("select price from test_master_city_price where test_fk='" . $tn[1] . "' and city_fk='" . $test_city . "' and status='1'");
      $this->job_model->master_fun_insert("booked_job_test", array("job_fk" => $insert, "test_city" => $test_city, "test_fk" => "t-" . $tn[1], "price" => $tst_price[0]["price"]));
      }
      if ($tn[0] == 'p') {
      $this->job_model->master_fun_insert("book_package_master", array("cust_fk" => $uid, "package_fk" => $tn[1], 'job_fk' => $insert, "status" => "1", "type" => "2"));
      $tst_price = $this->job_model->get_val("select d_price from package_master_city_price where package_fk='" . $tn[1] . "' and city_fk='" . $test_city . "' and status='1'");
      $this->job_model->master_fun_insert("booked_job_test", array("job_fk" => $insert, "test_city" => $test_city, "test_fk" => "p-" . $tn[1], "price" => $tst_price[0]["d_price"]));
      $this->check_active_package($tn[1], $insert, $customer);
      }
      if ($tn[0] == 'pt') {
      $this->job_model->master_fun_insert("job_test_list_master", array('job_fk' => $insert, "test_fk" => $tn[1], "is_panel" => "1"));

      $tst_price = $this->job_model->get_val("select price from panel_tests where panel_fk='" . $panel_fk . "' and  test_fk='" . $tn[1] . "' and city_fk='" . $test_city . "' and status='1'");
      $this->job_model->master_fun_insert("booked_job_test", array("job_fk" => $insert, "test_city" => $test_city, "panel_fk" => $panel_fk, "test_fk" => "pt-" . $tn[1], "price" => $tst_price[0]["price"]));
      //               echo $this->db->last_query();
      }
      }
      if ($discount > 0) {
      $this->job_model->master_fun_insert("job_log", array("job_fk" => $insert, "created_by" => "", "updated_by" => $added_by, "deleted_by" => "", "job_status" => '', "message_fk" => "24", "date_time" => date("Y-m-d H:i:s")));
      }
      $received_amount = $_POST["received_amount"];
      if ($received_amount > 0) {
      $this->job_model->master_fun_insert("job_master_receiv_amount", array("job_fk" => $insert, "added_by" => $added_by, "payment_type" => $payment_via, "amount" => $received_amount, "createddate" => date("Y-m-d H:i:s")));
      $this->job_model->master_fun_insert("job_log", array("job_fk" => $insert, "created_by" => "", "updated_by" => $added_by, "deleted_by" => "", "job_status" => '', "message_fk" => "22", "date_time" => date("Y-m-d H:i:s")));
      }


      $this->session->set_flashdata("success", array("Test successfully Booked."));
      redirect("Admin/TelecallerCallBooking", "refresh");
      }
      $received_amount = $_POST["received_amount"];
      if ($received_amount > 0) {
      $this->job_model->master_fun_insert("job_master_receiv_amount", array("job_fk" => $insert, "added_by" => $added_by, "payment_type" => $payment_via, "amount" => $received_amount, "createddate" => date("Y-m-d H:i:s")));
      $this->job_model->master_fun_insert("job_log", array("job_fk" => $insert, "created_by" => "", "updated_by" => $added_by, "deleted_by" => "", "job_status" => '', "message_fk" => "22", "date_time" => date("Y-m-d H:i:s")));
      }
      $file = $this->pdf_invoice($insert);
      $this->job_model->master_fun_update("job_master", array("id" => $insert), array("invoice" => $file));

      //$this->assign_phlebo_job($insert, $phlebo);
      if ($discount > 0) {
      $this->job_model->master_fun_insert("job_log", array("job_fk" => $insert, "created_by" => "", "updated_by" => $added_by, "deleted_by" => "", "job_status" => '', "message_fk" => "24", "date_time" => date("Y-m-d H:i:s")));
      }
      $this->session->set_flashdata("success", array("Test successfully Booked."));
      redirect("Admin/TelecallerCallBooking");
      } else {
      $data['success'] = $this->session->flashdata("success");
      $data['panel_list'] = $this->job_model->master_fun_get_tbl_val("test_panel", array('status' => 1), array("name", "asc"));

      $data['phlebo_list'] = $this->job_model->master_fun_get_tbl_val("phlebo_master", array('status' => 1, "type" => 1), array("name", "asc"));
      $data['source_list'] = $this->job_model->master_fun_get_tbl_val("source_master", array('status' => 1), array("name", "asc"));
      // $data['referral_list'] = $this->job_model->master_fun_get_tbl_val("doctor_master", array('status' => 1), array("full_name", "asc"));
      //$data['customer'] = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "full_name !=" => ""), array("full_name", "asc"));
      $data['test_cities'] = $this->job_model->master_fun_get_tbl_val("test_cities", array('status' => 1), array("id", "asc"));
      $data['unread'] = $this->job_model->master_fun_get_tbl_val("prescription_upload", array('status' => 1, "is_read" => "0"), array("id", "asc"));
      $data["relation1"] = $this->job_model->master_fun_get_tbl_val("relation_master", array('status' => "1"), array("name", "asc"));
      $this->load->view('header');
      $this->load->view('nav', $data);
      $this->load->view('talycaller_callbooking_with_panel', $data);
      $this->load->view('footer');
      }
      } */

    function TelecallerCallBooking() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->helper("Email");
        $email_cnt = new Email;
        $this->load->model('job_model');
        $data["login_data"] = logindata();
        $added_by = $data["login_data"]["id"];
        $data['branchlist'] = $this->job_model->get_val("SELECT * from branch_master where status='1'");
        $data['sample_from'] = $this->job_model->get_val("SELECT * from sample_from where status='1'");
        $data['payment_type'] = $this->job_model->get_val("SELECT * from payment_type_master where status='1'");
        $data["cntr_arry"] = array();
        $data["branch_city_arry"] = array();
        if (!empty($data["login_data"]['branch_fk'])) {
            foreach ($data["login_data"]['branch_fk'] as $key) {
                $data["cntr_arry"][] = $key["branch_fk"];
                $b_data = $this->job_model->get_val("SELECT * from branch_master where id='" . $key["branch_fk"] . "'");
                $data["branch_city_arry"][] = $b_data[0]["city"];
            }
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required');
        $this->form_validation->set_rules('test_city', 'Test city', 'trim|required');

        if ($this->form_validation->run() != FALSE) {
			
            $customer = $this->input->post("customer");
            $name = $this->input->post("name");
            $phone = $this->input->post("phone");
            $email = $this->input->post("email");
            $noify_cust = $this->input->post("noify_cust");
            if ($noify_cust == null) {
                $noify_cust = 0;
            }
            if ($email == '') {
                $email = 'noreply@airmedlabs.com';
            }
            $gender = $this->input->post("gender");
            $dob = $this->input->post("dob");
            $test_city = $this->input->post("test_city");
            $address = $this->input->post("address");
            $note = $this->input->post("note");
            $discount = $this->input->post("discount");
            $payable = $this->input->post("payable");
            $test = $this->input->post("test");
            $referral_by = $this->input->post("referral_by");
            $source = $this->input->post("source");
            $phlebo = $this->input->post("phlebo");
            $phlebo_date = $this->input->post("phlebo_date");
            $phlebo_time = $this->input->post("phlebo_time");
            $notify = $this->input->post("notify");
            $branch = $this->input->post("branch");
            $total_amount = $this->input->post("total_amount");
            if ($branch == '') {
                $branch = 1;
            }
            $collection_charge = $this->input->post("collection_charge");
            $discount_type = $this->input->post("discount_type");
            $payment_via = $this->input->post("payment_via");
            $received_amount = $this->input->post("received_amount");
            $panel_fk = $this->input->post("panellist");
            $barcode = $this->input->post("barcode");
            /* Check discount start */
            if ($discount_type == 'flat') {
                $f_payable_amount = $payable + $received_amount;
                $discount = 100 - ($f_payable_amount * 100 / $total_amount);
                $discount = number_format((float) $discount, 2, '.', '');
            }
            /* End */
            /* Branch Cut start */
            if ($branch > 0) {
                $test_cut = $this->job_model->get_val("select IF(cut>0,cut,0) as cut from branch_master where id='" . $branch . "'");
                $cut = $test_cut[0]["cut"];
            } else {
                $cut = 0;
            }
            /* END */
            $sample_from = $this->input->post("sample_from");
            //echo "<pre>"; print_r($_POST); die();
            $order_id = $this->get_job_id($test_city);
            //$barcode = $this->util_lib->barcode_generate($branch);
            //$barcode = '';
            $date = date('Y-m-d H:i:s');
            if ($customer != '') {
                $c_data = array(
                    "full_name" => $name,
                    "gender" => $gender,
                    "email" => $email,
                    "mobile" => $phone,
                    "address" => $address,
                    "dob" => $dob
                );
                $this->job_model->master_fun_update("customer_master", array("id", $customer), $c_data);

                $price = 0;
                //print_r($test );

                foreach ($test as $key) {
                    $tn = explode("-", $key);
                    if ($tn[0] == 't') {
                        $result = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $test_city . "' AND `test_master`.`id`='" . $tn[1] . "'");
                        if ($cut > 0) {
                            $new_price = $result[0]["price"] - ($cut * $result[0]["price"] / 100);
                        } else {
                            $new_price = $result[0]["price"];
                        }
                        $new_price = round($new_price);
                        $price += $new_price;
                        $test_package_name[] = $result[0]["test_name"];
                    }
                    if ($tn[0] == 'p') {
                        //print_r(array('package_fk' => $tn[1], "city_fk" => $test_city)); die();
                        $query = $this->db->get_where('package_master_city_price', array('package_fk' => $tn[1], "city_fk" => $test_city, "status" => "1"));
                        $result = $query->result();
                        $query1 = $this->db->get_where('package_master', array('id' => $tn[1]));
                        $result1 = $query1->result();
                        $price += $result[0]->d_price;
                        $test_package_name[] = $result1[0]->title;
                    }
                    if ($tn[0] == 'pt') {
                        $result = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`panel_tests`.`price` FROM 
  `test_master` 
  INNER JOIN `panel_tests` 
    ON `test_master`.`id` = `panel_tests`.`test_fk` WHERE `test_master`.`status`='1' AND `panel_tests`.`status`='1' AND `panel_tests`.`city_fk`='" . $test_city . "'  AND `panel_tests`.`panel_fk` = '" . $panel_fk . "'   AND `test_master`.`id`='" . $tn[1] . "'");
                        $price += $result[0]["price"];
                        $test_package_name[] = $result[0]["test_name"];
                    }
                }
//                print_R($test_package_name);
                //              die($price."1");
                /* Nishit book phlebo start */
                $test_for = $this->input->post("test_for");
                $testforself = "self";
                $family_mem_id = 0;
                if ($test_for == "new") {
                    $f_name = $this->input->post("f_name");
                    $family_relation = $this->input->post("family_relation");
                    $relation_details = $this->job_model->get_val("SELECT * from relation_master where id='" . $family_relation . "'");
                    $f_phone = $this->input->post("f_phone");
                    $f_email = $this->input->post("f_email");
                    $f_dob = $this->input->post("f_dob");
                    $family_gender = $this->input->post("family_gender");
                    if ($f_name != '' && $family_relation != '') {
                        $family_mem_id = $this->job_model->master_fun_insert("customer_family_master", array("user_fk" => $customer, "gender" => $family_gender, "dob" => $f_dob, "name" => $f_name, "relation_fk" => $family_relation, "email" => $f_email, "phone" => $f_phone, "status" => "1", "created_date" => date("Y-m-d H:i:s")));
                        $testforself = "family";
                    }
                } else if ($test_for != '') {
                    $family_mem_id = $test_for;
                    $testforself = "family";
                }
                $address = $this->input->get_post("address");
                $date1 = $this->input->get_post("phlebo_date");
                $time_slot_id = $this->input->get_post("phlebo_time");
                $emergency = $this->input->post("emergency");
                if ($emergency == 1) {
                    $date1 = date("Y-m-d");
                    $time_slot_id = '';
                    $emergency = 1;
                } else {
                    $emergency = 0;
                }
                /* $emergency_req = $this->input->get_post("emergency_req");
                  if ($emergency_req == "true") {
                  $emergency_req = 1;
                  $time_slot_id = 0;
                  } else {
                  $emergency_req = 0;
                  } */
                //echo "<pre>";print_r(array("user_fk" => $uid, "type" => $b_type, "family_member_fk" => $family_mem_id, "address" => $address, "date" => $date1, "time_slot_fk" => $time_slot_id, "emergency" => $emergency_req, "status" => "1", "createddate" => date("Y-m-d H:i:s"))); die();
                $booking_fk = $this->job_model->master_fun_insert("booking_info", array("user_fk" => $customer, "type" => $testforself, "family_member_fk" => $family_mem_id, "address" => $address, "date" => $date1, "time_slot_fk" => $time_slot_id, "emergency" => $emergency, "status" => "1", "createddate" => date("Y-m-d H:i:s")));
                /* Nishit book phlebo end */
                if ($collection_charge == 1) {
                    $price = $price + 100;
                }
                /* Clinical history Code Start  */
                $check = $this->input->post("desc");
                //echo "<pre>"; print_R($_FILES); die();
                if ($check == '1') {
                    $message = $this->input->post('message');
                    $path = 'upload/doctor_description/';
                    if (!file_exists($path)) {
                        mkdir($path, 0777, true);
                    }
                    $file_loop = count($_FILES['file']['name']);
                    $files = $_FILES;
                    if (!empty($_FILES['file']['name'])) {

                        for ($i = 0; $i < $file_loop; $i++) {
                            $_FILES['file']['name'] = $files['file']['name'];
                            $_FILES['file']['type'] = $files['file']['type'];
                            $_FILES['file']['tmp_name'] = $files['file']['tmp_name'];
                            $_FILES['file']['error'] = $files['file']['error'];
                            $_FILES['file']['size'] = $files['file']['size'];
                            $config['upload_path'] = $path;
                            $config['allowed_types'] = '*';
                            $config['file_name'] = $files['file']['name'][$i];
                            $org[] = $config['file_name'];
                            $config['file_name'] = $i . time() . "_" . $files['file']['name'];
                            //$config['file_name'] = $files['file']['name'][$i];
                            $config['file_name'] = str_replace(' ', '_', $config['file_name']);
                            $config['overwrite'] = FALSE;
                            $this->load->library('upload', $config);
                            $this->upload->initialize($config);
                            $this->upload->do_upload("file");
                            $uploads[] = $config['file_name'];
                            //print_r($uploads); die();
                        }
                        $file = implode(',', $uploads);
                    }
                } else {
                    $check = '0';
                }
                /* END */
                if ($_FILES["panel_document_file"]["name"]) {
                    $config['upload_path'] = './upload/';
                    $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf|doc';
                    $config['file_name'] = time() . $_FILES["panel_document_file"]["name"];
                    $this->load->library('upload', $config);
                    if (!$this->upload->do_upload("panel_document_file")) {
                        $error = array('error' => $this->upload->display_errors());
                    } else {
                        $data = array('upload_data' => $this->upload->data());
                        $file_name = $data["upload_data"]["file_name"];
                        $file_doc = $file_name;
                    }
                }

                $data = array(
                    "order_id" => $order_id,
                    "cust_fk" => $customer,
                    "doctor" => $referral_by,
                    "other_reference" => $source,
                    "date" => $date,
                    "price" => $price,
                    "branch_fk" => $branch,
                    "status" => '6',
                    "payment_type" => "Cash On Delivery",
                    "discount" => $discount,
                    "payable_amount" => $payable,
                    "test_city" => $test_city,
                    "note" => $this->input->post('note'),
                    "added_by" => $data["login_data"]["id"],
                    "booking_info" => $booking_fk,
                    "collection_charge" => $collection_charge,
                    "notify_cust" => $noify_cust,
                    "date" => date("Y-m-d H:i:s"),
                    "barcode" => $barcode,
                    "sample_from" => $sample_from,
                    "clinical_history" => $check,
                    "prescription_message" => $message,
                    "prescription_file" => $file,
                    "document" => $file_doc,
                    "is_online" => "0"
                );
                $insert = $this->job_model->master_fun_insert("job_master", $data);
                /* foreach ($test as $key) {
                  $tn = explode("-", $key);
                  if ($tn[0] == 't') {
                  $this->job_model->master_fun_insert("job_test_list_master", array('job_fk' => $insert, "test_fk" => $tn[1]));
                  }
                  if ($tn[0] == 'p') {
                  $this->job_model->master_fun_insert("book_package_master", array("cust_fk" => $uid, "package_fk" => $tn[1], 'job_fk' => $insert, "status" => "1", "type" => "2"));
                  $this->check_active_package($tn[1], $insert, $customer);
                  }
                  } */

                foreach ($test as $key) {
                    $tn = explode("-", $key);

                    if ($tn[0] == 't') {
                        $this->job_model->master_fun_insert("job_test_list_master", array('job_fk' => $insert, "test_fk" => $tn[1], "is_panel" => "0"));
                        $tst_price = $this->job_model->get_val("select price from test_master_city_price where test_fk='" . $tn[1] . "' and city_fk='" . $test_city . "' and status='1'");
                        if ($cut > 0) {
                            $new_price = $tst_price[0]["price"] - ($cut * $tst_price[0]["price"] / 100);
                        } else {
                            $new_price = $tst_price[0]["price"];
                        }
                        $new_price = round($new_price);
                        $this->job_model->master_fun_insert("booked_job_test", array("job_fk" => $insert, "test_city" => $test_city, "test_fk" => "t-" . $tn[1], "price" => $new_price));
                    }
                    if ($tn[0] == 'p') {
                        $this->job_model->master_fun_insert("book_package_master", array("cust_fk" => $uid, "package_fk" => $tn[1], 'job_fk' => $insert, "status" => "1", "type" => "2"));
                        $tst_price = $this->job_model->get_val("select d_price from package_master_city_price where package_fk='" . $tn[1] . "' and city_fk='" . $test_city . "' and status='1'");
                        $this->job_model->master_fun_insert("booked_job_test", array("job_fk" => $insert, "test_city" => $test_city, "test_fk" => "p-" . $tn[1], "price" => $tst_price[0]["d_price"]));
                        $this->check_active_package($tn[1], $insert, $customer);
                    }
                    if ($tn[0] == 'pt') {
                        $this->job_model->master_fun_insert("job_test_list_master", array('job_fk' => $insert, "test_fk" => $tn[1], "is_panel" => "1"));
                        $tst_price = $this->job_model->get_val("select price from panel_tests where panel_fk='" . $panel_fk . "' and  test_fk='" . $tn[1] . "' and city_fk='" . $test_city . "' and status='1'");
                        $this->job_model->master_fun_insert("booked_job_test", array("job_fk" => $insert, "test_city" => $test_city, "panel_fk" => $panel_fk, "test_fk" => "pt-" . $tn[1], "price" => $tst_price[0]["price"]));
                        //               echo $this->db->last_query();
                    }
                }
            } else {

                $result = $this->job_model->get_val("SELECT * FROM `customer_master` WHERE `status`='1' AND active='1' AND `mobile`='" . $phone . "' ORDER BY id ASC");
                if (empty($result)) {
                    $result1 = $this->job_model->get_val("SELECT * FROM `customer_master` WHERE `status`='1' AND active='1' AND `email`='" . $email . "' ORDER BY id ASC");
                    if (empty($result1) || $email = "noreply@airmedlabs.com") {
                        $password = rand(11111111, 9999999);
                        $c_data = array(
                            "full_name" => $name,
                            "gender" => $gender,
                            "email" => $email,
                            "mobile" => $phone,
                            "address" => $address,
                            "password" => $password,
                            "dob" => $dob,
                            "created_date" => date("Y-m-d H:i:s")
                        );

                        $customer = $this->job_model->master_fun_insert("customer_master", $c_data);
                        
                        $config['mailtype'] = 'html';
                        $this->email->initialize($config);

                        $message = '<div style="padding:0 4%;">
                    <h4><b>Create Account</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your account successfully created. </p>
                        <p style="color:#7e7e7e;font-size:13px;"> Username/Email : . ' . $email . '  </p>  
                        <p style="color:#7e7e7e;font-size:13px;"> Password : ' . $password . '  </p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
                        $message = $email_cnt->get_design($message);
                        $this->email->to($email);
                        $this->email->from($this->config->item('admin_booking_email'), 'Airmed PathLabs');
                        $this->email->subject('Account Created Successfully');
                        $this->email->message($message);
                        if ($noify_cust == 1) {
                            $this->email->send();
                        }
                    } else {
                        $customer = $result1[0]["id"];
                    }
                } else {
                 
					$customer = $result[0]["id"];
					$c1_data = array("full_name" => $name,"gender" => $gender,"email" => $email,"address" => $address,"dob" => $dob);
                    $this->job_model->master_fun_update("customer_master", array("id", $customer), $c1_data);
                 
				 }
                //echo $customer;
                // die("OK");
                $price = 0;
                foreach ($test as $key) {
                    $tn = explode("-", $key);
                    if ($tn[0] == 't') {
                        $result = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $test_city . "' AND `test_master`.`id`='" . $tn[1] . "'");
                        if ($cut > 0) {
                            $new_price = $result[0]["price"] - ($cut * $result[0]["price"] / 100);
                        } else {
                            $new_price = $result[0]["price"];
                        }
                        $new_price = round($new_price);
                        $price += $new_price;
                        $test_package_name[] = $result[0]["test_name"];
                    }
                    if ($tn[0] == 'p') {
                        //print_r(array('package_fk' => $tn[1], "city_fk" => $test_city)); die();
                        $query = $this->db->get_where('package_master_city_price', array('package_fk' => $tn[1], "city_fk" => $test_city, "status" => "1"));
                        $result = $query->result();
                        $query1 = $this->db->get_where('package_master', array('id' => $tn[1]));
                        $result1 = $query1->result();
                        $price += $result[0]->d_price;
                        $test_package_name[] = $result1[0]->title;
                    }
                    if ($tn[0] == 'pt') {
                        $result = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`panel_tests`.`price` FROM 
  `test_master` 
  INNER JOIN `panel_tests` 
    ON `test_master`.`id` = `panel_tests`.`test_fk` WHERE `test_master`.`status`='1' AND `panel_tests`.`status`='1' AND `panel_tests`.`city_fk`='" . $test_city . "'  AND `panel_tests`.`panel_fk` = '" . $panel_fk . "'   AND `test_master`.`id`='" . $tn[1] . "'");
                        $price += $result[0]["price"];
                        $test_package_name[] = $result[0]["test_name"];
                    }
                }
                /* Nishit book phlebo start */
                $test_for = $this->input->post("test_for");
                $testforself = "self";
                $family_mem_id = 0;
                if ($test_for == "new") {
                    $f_name = $this->input->post("f_name");
                    $family_relation = $this->input->post("family_relation");
                    $f_phone = $this->input->post("f_phone");
                    $f_email = $this->input->post("f_email");
                    $f_dob = $this->input->post("f_dob");
                    $family_gender = $this->input->post("family_gender");
                    if ($f_name != '' && $family_relation != '') {
                        $family_mem_id = $this->job_model->master_fun_insert("customer_family_master", array("user_fk" => $customer, "dob" => $f_dob, "gender" => $family_gender, "name" => $f_name, "relation_fk" => $family_relation, "email" => $f_email, "phone" => $f_phone, "status" => "1", "created_date" => date("Y-m-d H:i:s")));
                        $testforself = "family";
                    }
                } else if ($test_for != '') {
                    $family_mem_id = $test_for;
                    $testforself = "family";
                }
                $address = $this->input->get_post("address");
                $date1 = $this->input->get_post("phlebo_date");
                $time_slot_id = $this->input->get_post("phlebo_time");
                /* $emergency_req = $this->input->get_post("emergency_req");
                  if ($emergency_req == "true") {
                  $emergency_req = 1;
                  $time_slot_id = 0;
                  } else {
                  $emergency_req = 0;
                  } */
                //echo "<pre>";print_r(array("user_fk" => $uid, "type" => $b_type, "family_member_fk" => $family_mem_id, "address" => $address, "date" => $date1, "time_slot_fk" => $time_slot_id, "emergency" => $emergency_req, "status" => "1", "createddate" => date("Y-m-d H:i:s"))); die();
                $booking_fk = $this->job_model->master_fun_insert("booking_info", array("user_fk" => $customer, "type" => $testforself, "family_member_fk" => $family_mem_id, "address" => $address, "date" => $this->input->get_post("phlebo_date"), "time_slot_fk" => $this->input->get_post("phlebo_time"), "emergency" => 0, "status" => "1", "createddate" => date("Y-m-d H:i:s")));
                /* Nishit book phlebo end */
                if ($collection_charge == 1) {
                    $price = $price + 100;
                }
                /* Clinical history Code Start  */
                $check = $this->input->post("desc");
                if ($check == '1') {
                    $message = $this->input->post('message');
                    $path = 'upload/doctor_description/';
                    if (!file_exists($path)) {
                        mkdir($path, 0777, true);
                    }
                    $file_loop = count($_FILES['file']['name']);
                    $files = $_FILES;
                    if (!empty($_FILES['file']['name'])) {

                        for ($i = 0; $i < $file_loop; $i++) {
                            $_FILES['file']['name'] = $files['file']['name'];
                            $_FILES['file']['type'] = $files['file']['type'];
                            $_FILES['file']['tmp_name'] = $files['file']['tmp_name'];
                            $_FILES['file']['error'] = $files['file']['error'];
                            $_FILES['file']['size'] = $files['file']['size'];
                            $config['upload_path'] = $path;
                            $config['allowed_types'] = '*';
                            $config['file_name'] = $files['file']['name'][$i];
                            $org[] = $config['file_name'];
                            $config['file_name'] = $i . time() . "_" . $files['file']['name'];
                            //$config['file_name'] = $files['file']['name'][$i];
                            $config['file_name'] = str_replace(' ', '_', $config['file_name']);
                            $config['overwrite'] = FALSE;
                            $this->load->library('upload', $config);
                            $this->upload->initialize($config);
                            $this->upload->do_upload("file");
                            $uploads[] = $config['file_name'];
                            //print_r($uploads); die();
                        }
                        $file = implode(',', $uploads);
                    }
                } else {
                    $check = '0';
                }
                /* END */
                if ($_FILES["panel_document_file"]["name"]) {
                    $config['upload_path'] = './upload/';
                    $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf|doc';
                    $config['file_name'] = time() . $_FILES["panel_document_file"]["name"];
                    $this->load->library('upload', $config);
                    if (!$this->upload->do_upload("panel_document_file")) {
                        $error = array('error' => $this->upload->display_errors());
                    } else {
                        $data = array('upload_data' => $this->upload->data());
                        $file_name = $data["upload_data"]["file_name"];
                        $file_doc = $file_name;
                    }
                }
                $data = array(
                    "order_id" => $order_id,
                    "cust_fk" => $customer,
                    "doctor" => $referral_by,
                    "other_reference" => $source,
                    "date" => $date,
                    "price" => $price,
                    "status" => '6',
                    "payment_type" => "Cash On Delivery",
                    "discount" => $discount,
                    "payable_amount" => $payable,
                    "branch_fk" => $branch,
                    "test_city" => $test_city,
                    "note" => $this->input->post('note'),
                    "added_by" => $data["login_data"]["id"],
                    "booking_info" => $booking_fk,
                    "notify_cust" => $noify_cust,
                    "collection_charge" => $collection_charge,
                    "date" => date("Y-m-d H:i:s"),
                    "sample_from" => $sample_from,
                    "clinical_history" => $check,
                    "prescription_message" => $message,
                    "prescription_file" => $file,
                    "barcode" => $barcode,
                    "document" => $file_doc,
                    "is_online" => "0"
                );
                $insert = $this->job_model->master_fun_insert("job_master", $data);
                /* foreach ($test as $key) {
                  $tn = explode("-", $key);
                  if ($tn[0] == 't') {
                  $this->job_model->master_fun_insert("job_test_list_master", array('job_fk' => $insert, "test_fk" => $tn[1]));
                  }
                  if ($tn[0] == 'p') {
                  $this->job_model->master_fun_insert("book_package_master", array("cust_fk" => $uid, "package_fk" => $tn[1], 'job_fk' => $insert, "status" => "1", "type" => "2"));
                  $this->check_active_package($tn[1], $insert, $customer);
                  }
                  } */
                foreach ($test as $key) {
                    $tn = explode("-", $key);
                    if ($tn[0] == 't') {
                        $this->job_model->master_fun_insert("job_test_list_master", array('job_fk' => $insert, "test_fk" => $tn[1], "is_panel" => "0"));
                        $tst_price = $this->job_model->get_val("select price from test_master_city_price where test_fk='" . $tn[1] . "' and city_fk='" . $test_city . "' and status='1'");
                        if ($cut > 0) {
                            $new_price = $tst_price[0]["price"] - ($cut * $tst_price[0]["price"] / 100);
                        } else {
                            $new_price = $tst_price[0]["price"];
                        }
                        $new_price = round($new_price);
                        $this->job_model->master_fun_insert("booked_job_test", array("job_fk" => $insert, "test_city" => $test_city, "test_fk" => "t-" . $tn[1], "price" => $new_price));
                    }
                    if ($tn[0] == 'p') {
                        $this->job_model->master_fun_insert("book_package_master", array("cust_fk" => $uid, "package_fk" => $tn[1], 'job_fk' => $insert, "status" => "1", "type" => "2"));
                        $tst_price = $this->job_model->get_val("select d_price from package_master_city_price where package_fk='" . $tn[1] . "' and city_fk='" . $test_city . "' and status='1'");
                        $this->job_model->master_fun_insert("booked_job_test", array("job_fk" => $insert, "test_city" => $test_city, "test_fk" => "p-" . $tn[1], "price" => $tst_price[0]["d_price"]));
                        $this->check_active_package($tn[1], $insert, $customer);
                    }
                    if ($tn[0] == 'pt') {
                        $this->job_model->master_fun_insert("job_test_list_master", array('job_fk' => $insert, "test_fk" => $tn[1], "is_panel" => "1"));

                        $tst_price = $this->job_model->get_val("select price from panel_tests where panel_fk='" . $panel_fk . "' and  test_fk='" . $tn[1] . "' and city_fk='" . $test_city . "' and status='1'");
                        $this->job_model->master_fun_insert("booked_job_test", array("job_fk" => $insert, "test_city" => $test_city, "panel_fk" => $panel_fk, "test_fk" => "pt-" . $tn[1], "price" => $tst_price[0]["price"]));
                        //               echo $this->db->last_query();
                    }
                }
                if ($discount > 0) {
                    $this->job_model->master_fun_insert("job_log", array("job_fk" => $insert, "created_by" => "", "updated_by" => $added_by, "deleted_by" => "", "job_status" => '', "message_fk" => "24", "date_time" => date("Y-m-d H:i:s")));
                }
                $received_amount = $_POST["received_amount"];
                if ($received_amount > 0) {
                    $this->job_model->master_fun_insert("job_master_receiv_amount", array("job_fk" => $insert, "added_by" => $added_by, "payment_type" => $payment_via, "amount" => $received_amount, "createddate" => date("Y-m-d H:i:s")));
                    $this->job_model->master_fun_insert("job_log", array("job_fk" => $insert, "created_by" => "", "updated_by" => $added_by, "deleted_by" => "", "job_status" => '', "message_fk" => "22", "date_time" => date("Y-m-d H:i:s")));
                }
				if($phlebo != ""){ $this->assign_phlebo_job($insert,$phlebo);}
                if (empty($barcode)) {
                    $barcode = $insert;
                }
                //print_r(array("barcode" => $barcode)); die();
                $this->job_model->master_fun_update("job_master", array("id", $insert), array("barcode" => $barcode));
                $this->session->set_flashdata("success", array("Test successfully Booked."));
                redirect("Admin/Telecallerinvoice/" . $insert);
            }
            /* Receive amount start */
            $received_amount = $_POST["received_amount"];
            if ($received_amount > 0) {
                $this->job_model->master_fun_insert("job_master_receiv_amount", array("job_fk" => $insert, "added_by" => $added_by, "payment_type" => $payment_via, "amount" => $received_amount, "createddate" => date("Y-m-d H:i:s")));
                $this->job_model->master_fun_insert("job_log", array("job_fk" => $insert, "created_by" => "", "updated_by" => $added_by, "deleted_by" => "", "job_status" => '', "message_fk" => "22", "date_time" => date("Y-m-d H:i:s")));
            }
            /* END */
			if (empty($barcode)) {
                $barcode = $insert;
            }
            $file = $this->pdf_invoice($insert);
            $this->job_model->master_fun_update("job_master", array("id", $insert), array("invoice" => $file, "barcode" => $barcode));
			if($phlebo != "" && $insert != ""){$this->assign_phlebo_job($insert,$phlebo);}
            /*
              if($phlebo != NULL) {
              $job_cnt = $this->job_model->master_num_rows("phlebo_assign_job", array("status" => "1", "job_fk" => $insert));
              if ($job_cnt == 0) {
              $data = array("job_fk" => $insert, "phlebo_fk" => $phlebo, "address" => $address, "date" =>$phlebo_date, "time" =>$phlebo_time, "created_date" => date("Y-m-d H:i:s"), "created_by" => $data["login_data"]["id"]);
              $this->job_model->master_fun_insert("phlebo_assign_job", $data);
              $this->job_model->master_fun_insert("job_log", array("job_fk" => $insert, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "message_fk" => "8", "date_time" => date("Y-m-d H:i:s")));
              } else {
              $data = array("job_fk" => $insert, "phlebo_fk" => $phlebo, "address" => $address, "date" =>$phlebo_date, "time" =>$phlebo_time, "updated_by" => $data["login_data"]["id"]);
              $this->job_model->master_fun_update("phlebo_assign_job", array("job_fk", $insert), $data);
              $this->job_model->master_fun_insert("job_log", array("job_fk" => $insert, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "message_fk" => "9", "date_time" => date("Y-m-d H:i:s")));
              }

              $phlebo_details = $this->job_model->master_fun_get_tbl_val("phlebo_master", array('id' => $phlebo), array("id", "asc"));
              $phlebo_job_details = $this->job_model->master_fun_get_tbl_val("phlebo_assign_job", array('job_fk' => $insert), array("id", "asc"));
              $job_details = $this->job_model->master_fun_get_tbl_val("job_master", array('id' => $insert), array("id", "asc"));
              $customer_details = $this->job_model->master_fun_get_tbl_val("customer_master", array('id' => $job_details[0]['cust_fk']), array("id", "asc"));

              $sms_message = $this->job_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "phlebo_msg"), array("id", "asc"));
              $sms_message = preg_replace("/{{NAME}}/", ucfirst($phlebo_details[0]["name"]), $sms_message[0]["message"]);
              $sms_message = preg_replace("/{{MOBILE}}/", $phlebo_details[0]["mobile"], $sms_message);
              $sms_message = preg_replace("/{{ORDERID}}/", $job_details[0]["order_id"], $sms_message);
              $sms_message = preg_replace("/{{CNAME}}/", $customer_details[0]["full_name"], $sms_message);
              $sms_message = preg_replace("/{{CMOBILE}}/", $customer_details[0]["mobile"], $sms_message);
              $sms_message = preg_replace("/{{CADDRESS}}/", $phlebo_job_details[0]["address"], $sms_message);
              $sms_message = preg_replace("/{{DATE}}/", $phlebo_job_details[0]["date"], $sms_message);
              $sms_message = preg_replace("/{{TIME}}/", $phlebo_job_details[0]["time"], $sms_message);
              //$sms_message="done";
              $mobile = $phlebo_details[0]['mobile'];
              $this->load->helper("sms");
              $notification = new Sms();
              $notification::send($mobile, $sms_message);
              if ($notify == 1) {
              $sms_message = $this->job_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "phlebo_msg_cust"), array("id", "asc"));
              $sms_message = preg_replace("/{{NAME}}/", ucfirst($customer_details[0]["full_name"]), $sms_message[0]["message"]);
              $sms_message = preg_replace("/{{MOBILE}}/", $customer_details[0]["mobile"], $sms_message);
              $sms_message = preg_replace("/{{ORDERID}}/", $job_details[0]["order_id"], $sms_message);
              $sms_message = preg_replace("/{{PNAME}}/", ucfirst($phlebo_details[0]["name"]), $sms_message);
              $sms_message = preg_replace("/{{PMOBILE}}/", $phlebo_details[0]["mobile"], $sms_message);
              $sms_message = preg_replace("/{{DATE}}/", $phlebo_job_details[0]["date"], $sms_message);
              $sms_message = preg_replace("/{{TIME}}/", $phlebo_job_details[0]["time"], $sms_message);
              $mobile = $customer_details[0]['mobile'];
              //$sms_message="done";
              $notification::send($mobile, $sms_message);
              }
              } */
            //$this->assign_phlebo_job($insert, $phlebo);
            if ($discount > 0) {
                $this->job_model->master_fun_insert("job_log", array("job_fk" => $insert, "created_by" => "", "updated_by" => $added_by, "deleted_by" => "", "job_status" => '', "message_fk" => "24", "date_time" => date("Y-m-d H:i:s")));
            }
            $this->session->set_flashdata("success", array("Test successfully Booked."));
            redirect("Admin/Telecallerinvoice/" . $insert);
        } else {
            $data['success'] = $this->session->flashdata("success");
            $data['panel_list'] = $this->job_model->master_fun_get_tbl_val("test_panel", array('status' => 1), array("name", "asc"));
            $data['phlebo_list'] = $this->job_model->master_fun_get_tbl_val("phlebo_master", array('status' => 1, "type" => 1), array("name", "asc"));
            $data['source_list'] = $this->job_model->master_fun_get_tbl_val("source_master", array('status' => 1), array("name", "asc"));
            // $data['referral_list'] = $this->job_model->master_fun_get_tbl_val("doctor_master", array('status' => 1), array("full_name", "asc"));
            //$data['customer'] = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "full_name !=" => ""), array("full_name", "asc"));
            $data['test_cities'] = $this->job_model->master_fun_get_tbl_val("test_cities", array('status' => 1), array("id", "asc"));
            $data['unread'] = $this->job_model->master_fun_get_tbl_val("prescription_upload", array('status' => 1, "is_read" => "0"), array("id", "asc"));
            $data["relation1"] = $this->job_model->master_fun_get_tbl_val("relation_master", array('status' => "1"), array("name", "asc"));
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('talycaller_callbooking_with_panel', $data);
            $this->load->view('footer');
        }
    }

    function Telecallerinvoice($jid) {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data['job_id'] = $jid;
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('invoice_telecaller', $data);
        $this->load->view('footer');
    }

    function TelecallerCallBooking1() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->helper("Email");
        $email_cnt = new Email;
        $this->load->model('job_model');
        $data["login_data"] = logindata();
        $added_by = $data["login_data"]["id"];
        $data['branchlist'] = $this->job_model->get_val("SELECT * from branch_master where status='1'");
        $data['sample_from'] = $this->job_model->get_val("SELECT * from sample_from where status='1'");
        $data['payment_type'] = $this->job_model->get_val("SELECT * from payment_type_master where status='1'");
        $data["cntr_arry"] = array();
        $data["branch_city_arry"] = array();
        if (!empty($data["login_data"]['branch_fk'])) {
            foreach ($data["login_data"]['branch_fk'] as $key) {
                $data["cntr_arry"][] = $key["branch_fk"];
                $b_data = $this->job_model->get_val("SELECT * from branch_master where id='" . $key["branch_fk"] . "'");
                $data["branch_city_arry"][] = $b_data[0]["city"];
            }
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required');
        $this->form_validation->set_rules('test_city', 'Test city', 'trim|required');

        if ($this->form_validation->run() != FALSE) {
            //print_r($_POST); die();
            $customer = $this->input->post("customer");
            $name = $this->input->post("name");
            $phone = $this->input->post("phone");
            $email = $this->input->post("email");
            $noify_cust = $this->input->post("noify_cust");
            if ($noify_cust == null) {
                $noify_cust = 0;
            }
            if ($email == '') {
                $email = 'noreply@airmedlabs.com';
            }
            $gender = $this->input->post("gender");
            $dob = $this->input->post("dob");
            $test_city = $this->input->post("test_city");
            $address = $this->input->post("address");
            $note = $this->input->post("note");
            $discount = $this->input->post("discount");
            $payable = $this->input->post("payable");
            $test = $this->input->post("test");
            $referral_by = $this->input->post("referral_by");
            $source = $this->input->post("source");
            $phlebo = $this->input->post("phlebo");
            $phlebo_date = $this->input->post("phlebo_date");
            $phlebo_time = $this->input->post("phlebo_time");
            $notify = $this->input->post("notify");
            $branch = $this->input->post("branch");
            $total_amount = $this->input->post("total_amount");
            if ($branch == '') {
                $branch = 1;
            }
            $discount_type = $this->input->post("discount_type");
            $payment_via = $this->input->post("payment_via");
            $received_amount = $this->input->post("received_amount");
            /* Check discount start */
            if ($discount_type == 'flat') {
                $f_payable_amount = $payable + $received_amount;
                $discount = 100 - ($f_payable_amount * 100 / $total_amount);
                $discount = number_format((float) $discount, 2, '.', '');
            }
            /* End */

            $sample_from = $this->input->post("sample_from");
            //echo "<pre>"; print_r($_POST); die();
            $order_id = $this->get_job_id($test_city);
            //$barcode = $this->util_lib->barcode_generate($branch);
            $barcode = '';
            $date = date('Y-m-d H:i:s');
            if ($customer != '') {
                $c_data = array(
                    "full_name" => $name,
                    "gender" => $gender,
                    "email" => $email,
                    "mobile" => $phone,
                    "address" => $address,
                    "dob" => $dob
                );
                $this->job_model->master_fun_update("customer_master", array("id", $customer), $c_data);

                $price = 0;
                foreach ($test as $key) {
                    $tn = explode("-", $key);
                    if ($tn[0] == 't') {
                        $result = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $test_city . "' AND `test_master`.`id`='" . $tn[1] . "'");
                        $price += $result[0]["price"];
                        $test_package_name[] = $result[0]["test_name"];
                    }
                    if ($tn[0] == 'p') {
                        //print_r(array('package_fk' => $tn[1], "city_fk" => $test_city)); die();
                        $query = $this->db->get_where('package_master_city_price', array('package_fk' => $tn[1], "city_fk" => $test_city, "status" => "1"));
                        $result = $query->result();
                        $query1 = $this->db->get_where('package_master', array('id' => $tn[1]));
                        $result1 = $query1->result();
                        $price += $result[0]->d_price;
                        $test_package_name[] = $result1[0]->title;
                    }
                }
                /* Nishit book phlebo start */
                $test_for = $this->input->post("test_for");
                $testforself = "self";
                $family_mem_id = 0;
                if ($test_for == "new") {
                    $f_name = $this->input->post("f_name");
                    $family_relation = $this->input->post("family_relation");
                    $relation_details = $this->job_model->get_val("SELECT * from relation_master where id='" . $family_relation . "'");
                    $f_phone = $this->input->post("f_phone");
                    $f_email = $this->input->post("f_email");
                    $f_dob = $this->input->post("f_dob");
                    $family_gender = $this->input->post("family_gender");
                    if ($f_name != '' && $family_relation != '') {
                        $family_mem_id = $this->job_model->master_fun_insert("customer_family_master", array("user_fk" => $customer, "gender" => $family_gender, "dob" => $f_dob, "name" => $f_name, "relation_fk" => $family_relation, "email" => $f_email, "phone" => $f_phone, "status" => "1", "created_date" => date("Y-m-d H:i:s")));
                        $testforself = "family";
                    }
                } else if ($test_for != '') {
                    $family_mem_id = $test_for;
                    $testforself = "family";
                }
                $address = $this->input->get_post("address");
                $date1 = $this->input->get_post("phlebo_date");
                $time_slot_id = $this->input->get_post("phlebo_time");
                $emergency = $this->input->post("emergency");
                $collection_charge = $this->input->post("collection_charge");
                if ($emergency == 1) {
                    $date1 = date("Y-m-d");
                    $time_slot_id = '';
                    $emergency = 1;
                } else {
                    $emergency = 0;
                }
                /* $emergency_req = $this->input->get_post("emergency_req");
                  if ($emergency_req == "true") {
                  $emergency_req = 1;
                  $time_slot_id = 0;
                  } else {
                  $emergency_req = 0;
                  } */
                //echo "<pre>";print_r(array("user_fk" => $uid, "type" => $b_type, "family_member_fk" => $family_mem_id, "address" => $address, "date" => $date1, "time_slot_fk" => $time_slot_id, "emergency" => $emergency_req, "status" => "1", "createddate" => date("Y-m-d H:i:s"))); die();
                $booking_fk = $this->job_model->master_fun_insert("booking_info", array("user_fk" => $customer, "type" => $testforself, "family_member_fk" => $family_mem_id, "address" => $address, "date" => $date1, "time_slot_fk" => $time_slot_id, "emergency" => $emergency, "status" => "1", "createddate" => date("Y-m-d H:i:s")));
                /* Nishit book phlebo end */
                if ($collection_charge == 1) {
                    $price = $price + 100;
                }
                /* Clinical history Code Start  */
                $check = $this->input->post("desc");
                //echo "<pre>"; print_R($_FILES); die();
                if ($check == '1') {
                    $message = $this->input->post('message');
                    $path = 'upload/doctor_description/';
                    if (!file_exists($path)) {
                        mkdir($path, 0777, true);
                    }
                    $file_loop = count($_FILES['file']['name']);
                    $files = $_FILES;
                    if (!empty($_FILES['file']['name'])) {

                        for ($i = 0; $i < $file_loop; $i++) {
                            $_FILES['file']['name'] = $files['file']['name'];
                            $_FILES['file']['type'] = $files['file']['type'];
                            $_FILES['file']['tmp_name'] = $files['file']['tmp_name'];
                            $_FILES['file']['error'] = $files['file']['error'];
                            $_FILES['file']['size'] = $files['file']['size'];
                            $config['upload_path'] = $path;
                            $config['allowed_types'] = '*';
                            $config['file_name'] = $files['file']['name'][$i];
                            $org[] = $config['file_name'];
                            $config['file_name'] = $i . time() . "_" . $files['file']['name'];
                            //$config['file_name'] = $files['file']['name'][$i];
                            $config['file_name'] = str_replace(' ', '_', $config['file_name']);
                            $config['overwrite'] = FALSE;
                            $this->load->library('upload', $config);
                            $this->upload->initialize($config);
                            $this->upload->do_upload("file");
                            $uploads[] = $config['file_name'];
                            //print_r($uploads); die();
                        }
                        $file = implode(',', $uploads);
                    }
                } else {
                    $check = '0';
                }
                /* END */
                $data = array(
                    "order_id" => $order_id,
                    "cust_fk" => $customer,
                    "doctor" => $referral_by,
                    "other_reference" => $source,
                    "date" => $date,
                    "price" => $price,
                    "branch_fk" => $branch,
                    "status" => '6',
                    "payment_type" => "Cash On Delivery",
                    "discount" => $discount,
                    "payable_amount" => $payable,
                    "test_city" => $test_city,
                    "note" => $this->input->post('note'),
                    "added_by" => $data["login_data"]["id"],
                    "booking_info" => $booking_fk,
                    "collection_charge" => $collection_charge,
                    "notify_cust" => $noify_cust,
                    "date" => date("Y-m-d H:i:s"),
                    "barcode" => $barcode,
                    "sample_from" => $sample_from,
                    "clinical_history" => $check,
                    "prescription_message" => $message,
                    "prescription_file" => $file
                );
                //print_r($data); die();
                $insert = $this->job_model->master_fun_insert("job_master", $data);
                /* foreach ($test as $key) {
                  $tn = explode("-", $key);
                  if ($tn[0] == 't') {
                  $this->job_model->master_fun_insert("job_test_list_master", array('job_fk' => $insert, "test_fk" => $tn[1]));
                  }
                  if ($tn[0] == 'p') {
                  $this->job_model->master_fun_insert("book_package_master", array("cust_fk" => $uid, "package_fk" => $tn[1], 'job_fk' => $insert, "status" => "1", "type" => "2"));
                  $this->check_active_package($tn[1], $insert, $customer);
                  }
                  } */

                foreach ($test as $key) {
                    $tn = explode("-", $key);
                    if ($tn[0] == 't') {
                        $this->job_model->master_fun_insert("job_test_list_master", array('job_fk' => $insert, "test_fk" => $tn[1]));
                        $tst_price = $this->job_model->get_val("select price from test_master_city_price where test_fk='" . $tn[1] . "' and city_fk='" . $test_city . "' and status='1'");
                        $this->job_model->master_fun_insert("booked_job_test", array("job_fk" => $insert, "test_city" => $test_city, "test_fk" => "t-" . $tn[1], "price" => $tst_price[0]["price"]));
                    }
                    if ($tn[0] == 'p') {
                        $this->job_model->master_fun_insert("book_package_master", array("cust_fk" => $uid, "package_fk" => $tn[1], 'job_fk' => $insert, "status" => "1", "type" => "2"));
                        $tst_price = $this->job_model->get_val("select d_price from package_master_city_price where package_fk='" . $tn[1] . "' and city_fk='" . $test_city . "' and status='1'");
                        $this->job_model->master_fun_insert("booked_job_test", array("job_fk" => $insert, "test_city" => $test_city, "test_fk" => "p-" . $tn[1], "price" => $tst_price[0]["d_price"]));
                        $this->check_active_package($tn[1], $insert, $customer);
                    }
                }

                //echo "<pre>";print_r($data);
                //die();
            } else {

                $result = $this->job_model->get_val("SELECT * FROM `customer_master` WHERE `status`='1' AND active='1' AND `mobile`='" . $phone . "' ORDER BY id ASC");
                if (empty($result)) {
                    $result1 = $this->job_model->get_val("SELECT * FROM `customer_master` WHERE `status`='1' AND active='1' AND `email`='" . $email . "' ORDER BY id ASC");
                    if (empty($result1) || $email = "noreply@airmedlabs.com") {
                        $password = rand(11111111, 9999999);
                        $c_data = array(
                            "full_name" => $name,
                            "gender" => $gender,
                            "email" => $email,
                            "mobile" => $phone,
                            "address" => $address,
                            "password" => $password,
                            "dob" => $dob,
                            "created_date" => date("Y-m-d H:i:s")
                        );
                        $customer = $this->job_model->master_fun_insert("customer_master", $c_data);
                        $config['mailtype'] = 'html';
                        $this->email->initialize($config);

                        $message = '<div style="padding:0 4%;">
                    <h4><b>Create Account</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your account successfully created. </p>
                        <p style="color:#7e7e7e;font-size:13px;"> Username/Email : . ' . $email . '  </p>  
                        <p style="color:#7e7e7e;font-size:13px;"> Password : ' . $password . '  </p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
                        $message = $email_cnt->get_design($message);
                        $this->email->to($email);
                        $this->email->from($this->config->item('admin_booking_email'), 'Airmed PathLabs');
                        $this->email->subject('Account Created Successfully');
                        $this->email->message($message);
                        if ($noify_cust == 1) {
                            $this->email->send();
                        }
                    } else {
                        $customer = $result1[0]["id"];
                    }
                } else {
                    $customer = $result[0]["id"];
                }
                //echo $customer;
                // die("OK");
                $price = 0;
                foreach ($test as $key) {
                    $tn = explode("-", $key);
                    if ($tn[0] == 't') {
                        $result = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $test_city . "' AND `test_master`.`id`='" . $tn[1] . "'");
                        $price += $result[0]["price"];
                        $test_package_name[] = $result[0]["test_name"];
                    }
                    if ($tn[0] == 'p') {
                        //print_r(array('package_fk' => $tn[1], "city_fk" => $test_city)); die();
                        $query = $this->db->get_where('package_master_city_price', array('package_fk' => $tn[1], "city_fk" => $test_city, "status" => "1"));
                        $result = $query->result();
                        $query1 = $this->db->get_where('package_master', array('id' => $tn[1]));
                        $result1 = $query1->result();
                        $price += $result[0]->d_price;
                        $test_package_name[] = $result1[0]->title;
                    }
                }
                /* Nishit book phlebo start */
                $test_for = $this->input->post("test_for");
                $testforself = "self";
                $family_mem_id = 0;
                if ($test_for == "new") {
                    $f_name = $this->input->post("f_name");
                    $family_relation = $this->input->post("family_relation");
                    $f_phone = $this->input->post("f_phone");
                    $f_email = $this->input->post("f_email");
                    $f_dob = $this->input->post("f_dob");
                    $family_gender = $this->input->post("family_gender");
                    if ($f_name != '' && $family_relation != '') {
                        $family_mem_id = $this->job_model->master_fun_insert("customer_family_master", array("user_fk" => $customer, "dob" => $f_dob, "gender" => $family_gender, "name" => $f_name, "relation_fk" => $family_relation, "email" => $f_email, "phone" => $f_phone, "status" => "1", "created_date" => date("Y-m-d H:i:s")));
                        $testforself = "family";
                    }
                } else if ($test_for != '') {
                    $family_mem_id = $test_for;
                    $testforself = "family";
                }
                $address = $this->input->get_post("address");
                $date1 = $this->input->get_post("phlebo_date");
                $time_slot_id = $this->input->get_post("phlebo_time");
                /* $emergency_req = $this->input->get_post("emergency_req");
                  if ($emergency_req == "true") {
                  $emergency_req = 1;
                  $time_slot_id = 0;
                  } else {
                  $emergency_req = 0;
                  } */
                //echo "<pre>";print_r(array("user_fk" => $uid, "type" => $b_type, "family_member_fk" => $family_mem_id, "address" => $address, "date" => $date1, "time_slot_fk" => $time_slot_id, "emergency" => $emergency_req, "status" => "1", "createddate" => date("Y-m-d H:i:s"))); die();
                $booking_fk = $this->job_model->master_fun_insert("booking_info", array("user_fk" => $customer, "type" => $testforself, "family_member_fk" => $family_mem_id, "address" => $address, "date" => $this->input->get_post("phlebo_date"), "time_slot_fk" => $this->input->get_post("phlebo_time"), "emergency" => 0, "status" => "1", "createddate" => date("Y-m-d H:i:s")));
                /* Nishit book phlebo end */
                if ($collection_charge == 1) {
                    $price = $price + 100;
                }
                /* Clinical history Code Start  */
                $check = $this->input->post("desc");
                if ($check == '1') {
                    $message = $this->input->post('message');
                    $path = 'upload/doctor_description/';
                    if (!file_exists($path)) {
                        mkdir($path, 0777, true);
                    }
                    $file_loop = count($_FILES['file']['name']);
                    $files = $_FILES;
                    if (!empty($_FILES['file']['name'])) {

                        for ($i = 0; $i < $file_loop; $i++) {
                            $_FILES['file']['name'] = $files['file']['name'];
                            $_FILES['file']['type'] = $files['file']['type'];
                            $_FILES['file']['tmp_name'] = $files['file']['tmp_name'];
                            $_FILES['file']['error'] = $files['file']['error'];
                            $_FILES['file']['size'] = $files['file']['size'];
                            $config['upload_path'] = $path;
                            $config['allowed_types'] = '*';
                            $config['file_name'] = $files['file']['name'][$i];
                            $org[] = $config['file_name'];
                            $config['file_name'] = $i . time() . "_" . $files['file']['name'];
                            //$config['file_name'] = $files['file']['name'][$i];
                            $config['file_name'] = str_replace(' ', '_', $config['file_name']);
                            $config['overwrite'] = FALSE;
                            $this->load->library('upload', $config);
                            $this->upload->initialize($config);
                            $this->upload->do_upload("file");
                            $uploads[] = $config['file_name'];
                            //print_r($uploads); die();
                        }
                        $file = implode(',', $uploads);
                    }
                } else {
                    $check = '0';
                }
                /* END */
                $data = array(
                    "order_id" => $order_id,
                    "cust_fk" => $customer,
                    "doctor" => $referral_by,
                    "other_reference" => $source,
                    "date" => $date,
                    "price" => $price,
                    "status" => '6',
                    "payment_type" => "Cash On Delivery",
                    "discount" => $discount,
                    "payable_amount" => $payable,
                    "branch_fk" => $branch,
                    "test_city" => $test_city,
                    "note" => $this->input->post('note'),
                    "added_by" => $data["login_data"]["id"],
                    "booking_info" => $booking_fk,
                    "notify_cust" => $noify_cust,
                    "collection_charge" => $collection_charge,
                    "date" => date("Y-m-d H:i:s"),
                    "sample_from" => $sample_from,
                    "clinical_history" => $check,
                    "prescription_message" => $message,
                    "prescription_file" => $file
                );
                $insert = $this->job_model->master_fun_insert("job_master", $data);
                /* foreach ($test as $key) {
                  $tn = explode("-", $key);
                  if ($tn[0] == 't') {
                  $this->job_model->master_fun_insert("job_test_list_master", array('job_fk' => $insert, "test_fk" => $tn[1]));
                  }
                  if ($tn[0] == 'p') {
                  $this->job_model->master_fun_insert("book_package_master", array("cust_fk" => $uid, "package_fk" => $tn[1], 'job_fk' => $insert, "status" => "1", "type" => "2"));
                  $this->check_active_package($tn[1], $insert, $customer);
                  }
                  } */
                foreach ($test as $key) {
                    $tn = explode("-", $key);
                    if ($tn[0] == 't') {
                        $this->job_model->master_fun_insert("job_test_list_master", array('job_fk' => $insert, "test_fk" => $tn[1]));
                        $tst_price = $this->job_model->get_val("select price from test_master_city_price where test_fk='" . $tn[1] . "' and city_fk='" . $test_city . "' and status='1'");
                        $this->job_model->master_fun_insert("booked_job_test", array("job_fk" => $insert, "test_city" => $test_city, "test_fk" => "t-" . $tn[1], "price" => $tst_price[0]["price"]));
                    }
                    if ($tn[0] == 'p') {
                        $this->job_model->master_fun_insert("book_package_master", array("cust_fk" => $uid, "package_fk" => $tn[1], 'job_fk' => $insert, "status" => "1", "type" => "2"));
                        $tst_price = $this->job_model->get_val("select d_price from package_master_city_price where package_fk='" . $tn[1] . "' and city_fk='" . $test_city . "' and status='1'");
                        $this->job_model->master_fun_insert("booked_job_test", array("job_fk" => $insert, "test_city" => $test_city, "test_fk" => "p-" . $tn[1], "price" => $tst_price[0]["d_price"]));
                        $this->check_active_package($tn[1], $insert, $customer);
                    }
                }
                if ($discount > 0) {
                    $this->job_model->master_fun_insert("job_log", array("job_fk" => $insert, "created_by" => "", "updated_by" => $added_by, "deleted_by" => "", "job_status" => '', "message_fk" => "24", "date_time" => date("Y-m-d H:i:s")));
                }
                $received_amount = $_POST["received_amount"];
                if ($received_amount > 0) {
                    $this->job_model->master_fun_insert("job_master_receiv_amount", array("job_fk" => $insert, "added_by" => $added_by, "payment_type" => $payment_via, "amount" => $received_amount, "createddate" => date("Y-m-d H:i:s")));
                    $this->job_model->master_fun_insert("job_log", array("job_fk" => $insert, "created_by" => "", "updated_by" => $added_by, "deleted_by" => "", "job_status" => '', "message_fk" => "22", "date_time" => date("Y-m-d H:i:s")));
                }
                $this->session->set_flashdata("success", array("Test successfully Booked."));
                redirect("Admin/TelecallerCallBooking", "refresh");
            }
            /* Receive amount start */
            $received_amount = $_POST["received_amount"];
            if ($received_amount > 0) {
                $this->job_model->master_fun_insert("job_master_receiv_amount", array("job_fk" => $insert, "added_by" => $added_by, "payment_type" => $payment_via, "amount" => $received_amount, "createddate" => date("Y-m-d H:i:s")));
                $this->job_model->master_fun_insert("job_log", array("job_fk" => $insert, "created_by" => "", "updated_by" => $added_by, "deleted_by" => "", "job_status" => '', "message_fk" => "22", "date_time" => date("Y-m-d H:i:s")));
            }
            //die("OK");
            /* END */
            $file = $this->pdf_invoice($insert);
            $this->job_model->master_fun_update("job_master", array("id" => $insert), array("invoice" => $file));
            /*
              if($phlebo != NULL) {
              $job_cnt = $this->job_model->master_num_rows("phlebo_assign_job", array("status" => "1", "job_fk" => $insert));
              if ($job_cnt == 0) {
              $data = array("job_fk" => $insert, "phlebo_fk" => $phlebo, "address" => $address, "date" =>$phlebo_date, "time" =>$phlebo_time, "created_date" => date("Y-m-d H:i:s"), "created_by" => $data["login_data"]["id"]);
              $this->job_model->master_fun_insert("phlebo_assign_job", $data);
              $this->job_model->master_fun_insert("job_log", array("job_fk" => $insert, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "message_fk" => "8", "date_time" => date("Y-m-d H:i:s")));
              } else {
              $data = array("job_fk" => $insert, "phlebo_fk" => $phlebo, "address" => $address, "date" =>$phlebo_date, "time" =>$phlebo_time, "updated_by" => $data["login_data"]["id"]);
              $this->job_model->master_fun_update("phlebo_assign_job", array("job_fk", $insert), $data);
              $this->job_model->master_fun_insert("job_log", array("job_fk" => $insert, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "message_fk" => "9", "date_time" => date("Y-m-d H:i:s")));
              }

              $phlebo_details = $this->job_model->master_fun_get_tbl_val("phlebo_master", array('id' => $phlebo), array("id", "asc"));
              $phlebo_job_details = $this->job_model->master_fun_get_tbl_val("phlebo_assign_job", array('job_fk' => $insert), array("id", "asc"));
              $job_details = $this->job_model->master_fun_get_tbl_val("job_master", array('id' => $insert), array("id", "asc"));
              $customer_details = $this->job_model->master_fun_get_tbl_val("customer_master", array('id' => $job_details[0]['cust_fk']), array("id", "asc"));

              $sms_message = $this->job_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "phlebo_msg"), array("id", "asc"));
              $sms_message = preg_replace("/{{NAME}}/", ucfirst($phlebo_details[0]["name"]), $sms_message[0]["message"]);
              $sms_message = preg_replace("/{{MOBILE}}/", $phlebo_details[0]["mobile"], $sms_message);
              $sms_message = preg_replace("/{{ORDERID}}/", $job_details[0]["order_id"], $sms_message);
              $sms_message = preg_replace("/{{CNAME}}/", $customer_details[0]["full_name"], $sms_message);
              $sms_message = preg_replace("/{{CMOBILE}}/", $customer_details[0]["mobile"], $sms_message);
              $sms_message = preg_replace("/{{CADDRESS}}/", $phlebo_job_details[0]["address"], $sms_message);
              $sms_message = preg_replace("/{{DATE}}/", $phlebo_job_details[0]["date"], $sms_message);
              $sms_message = preg_replace("/{{TIME}}/", $phlebo_job_details[0]["time"], $sms_message);
              //$sms_message="done";
              $mobile = $phlebo_details[0]['mobile'];
              $this->load->helper("sms");
              $notification = new Sms();
              $notification::send($mobile, $sms_message);
              if ($notify == 1) {
              $sms_message = $this->job_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "phlebo_msg_cust"), array("id", "asc"));
              $sms_message = preg_replace("/{{NAME}}/", ucfirst($customer_details[0]["full_name"]), $sms_message[0]["message"]);
              $sms_message = preg_replace("/{{MOBILE}}/", $customer_details[0]["mobile"], $sms_message);
              $sms_message = preg_replace("/{{ORDERID}}/", $job_details[0]["order_id"], $sms_message);
              $sms_message = preg_replace("/{{PNAME}}/", ucfirst($phlebo_details[0]["name"]), $sms_message);
              $sms_message = preg_replace("/{{PMOBILE}}/", $phlebo_details[0]["mobile"], $sms_message);
              $sms_message = preg_replace("/{{DATE}}/", $phlebo_job_details[0]["date"], $sms_message);
              $sms_message = preg_replace("/{{TIME}}/", $phlebo_job_details[0]["time"], $sms_message);
              $mobile = $customer_details[0]['mobile'];
              //$sms_message="done";
              $notification::send($mobile, $sms_message);
              }
              } */
            //$this->assign_phlebo_job($insert, $phlebo);
            if ($discount > 0) {
                $this->job_model->master_fun_insert("job_log", array("job_fk" => $insert, "created_by" => "", "updated_by" => $added_by, "deleted_by" => "", "job_status" => '', "message_fk" => "24", "date_time" => date("Y-m-d H:i:s")));
            }
            $this->session->set_flashdata("success", array("Test successfully Booked."));
            redirect("Admin/TelecallerCallBooking");
        } else {
            $data['success'] = $this->session->flashdata("success");
            $data['phlebo_list'] = $this->job_model->master_fun_get_tbl_val("phlebo_master", array('status' => 1, "type" => 1), array("name", "asc"));
            $data['source_list'] = $this->job_model->master_fun_get_tbl_val("source_master", array('status' => 1), array("name", "asc"));
            // $data['referral_list'] = $this->job_model->master_fun_get_tbl_val("doctor_master", array('status' => 1), array("full_name", "asc"));
            //$data['customer'] = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "full_name !=" => ""), array("full_name", "asc"));
            $data['test_cities'] = $this->job_model->master_fun_get_tbl_val("test_cities", array('status' => 1), array("id", "asc"));
            $data['unread'] = $this->job_model->master_fun_get_tbl_val("prescription_upload", array('status' => 1, "is_read" => "0"), array("id", "asc"));
            $data["relation1"] = $this->job_model->master_fun_get_tbl_val("relation_master", array('status' => "1"), array("name", "asc"));
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('talycaller_callbooking11', $data);
            $this->load->view('footer');
        }
    }

    function get_refered_by() {
        $selected = $this->input->get_post("selected");
        $city = $this->input->get_post("val");
        $referral_list = $this->job_model->master_fun_get_tbl_val("doctor_master", array('status' => 1, "city" => $city), array("full_name", "asc"));
        $refer = '<option value="">--Select Doctor--</option>';
        foreach ($referral_list as $referral) {
            $refer .= '<option value="' . $referral['id'] . '"';
            if ($selected == $referral['id']) {
                $refer .= ' selected';
            }
            $refer .= '>' . ucwords($referral['full_name']) . ' - ' . $referral['mobile'] . '</option>';
        }

        //$customer = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "full_name !=" => ""), array("full_name", "asc"));
        $customer_data = '<option value="">--Select--</option>';
        foreach ($customer as $key) {
            $customer_data .= '<option value="' . $key["id"] . '">' . ucfirst($key["full_name"]) . ' - ' . $key["mobile"] . '</option>';
        }


        //$test = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='1' AND `test_master_city_price`.`city_fk`='1'");
        /* $package = $this->job_model->get_val("SELECT 
          `package_master`.*,
          `package_master_city_price`.`a_price` AS `a_price1`,
          `package_master_city_price`.`d_price` AS `d_price1`
          FROM
          `package_master`
          INNER JOIN `package_master_city_price`
          ON `package_master`.`id` = `package_master_city_price`.`package_fk`
          WHERE `package_master`.`status` = '1'
          AND `package_master_city_price`.`status` = '1' AND `package_master_city_price`.`city_fk` = '1' "); */
        $test_list = '<option value="">--Select Test--</option>';
//        $test_list = '<select class="chosen-select" data-live-search="true" id="test" data-placeholder="Select Test" onchange="get_test_price();">
//			<option value="">--Select Test--</option>';
        foreach ($test as $ts) {
            $test_list .= ' <option value="t-' . $ts['id'] . '"> ' . ucfirst($ts['test_name']) . ' (Rs.' . $ts['price'] . ')</option>';
        }
        foreach ($package as $pk) {
            $test_list .= '<option value="p-' . $pk['id'] . '"> ' . ucfirst($pk['title']) . ' (Rs.' . $pk['d_price1'] . ')</option>';
        }
        //$test_list .= '</select>';

        echo json_encode(array("refer" => $refer, "test_list" => $test_list, "customer" => $customer_data));
    }

    function get_customer() {
        $selected = $this->input->get_post("selected");
        $referral_list = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1), array("full_name", "asc"));
        $refer = '<option value="">--Select Customer--</option>';
        foreach ($referral_list as $referral) {
            $refer .= '<option value="' . $referral['id'] . '"';
            if ($selected == $referral['id']) {
                $refer .= ' selected';
            }
            $refer .= '>' . ucwords($referral['full_name']) . ' - ' . $referral['mobile'] . '</option>';
        }
        echo json_encode(array("customer" => $refer));
    }

    function get_test_list($city_fk = 1) {
        $city_fk = $this->uri->segment(3);
        $branch_fk = $this->uri->segment(4);
        if ($branch_fk > 0) {
			
            $test = $this->job_model->get_val("select IF(cut>0,cut,0) as cut,jobdiscount from branch_master where id='" . $branch_fk . "'");
            $cut = $test[0]["cut"];
			$discount=$test[0]["jobdiscount"];
			
        } else {
            $cut = 0;
			$discount=1;
        }
		
        $selected = $this->input->get_post("selected");
        /* $referral_list = $this->job_model->master_fun_get_tbl_val("doctor_master", array('status' => 1), array("full_name", "asc"));
          $refer = '<option value="">--Select--</option>';
          foreach ($referral_list as $referral) {
          $refer .= '<option value="' . $referral['id'] . '"';
          $refer .= '>' . ucwords($referral['full_name']) . '-' . $referral['mobile'] . '</option>';
          }

          $customer = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "full_name !=" => ""), array("full_name", "asc"));
          $customer_data = '<option value="">--Select--</option>';
          foreach ($customer as $key) {
          $customer_data .= '<option value="' . $key["id"] . '">' . ucfirst($key["full_name"]) . ' - ' . $key["mobile"] . '</option>';
          }
         */
        $selected_test = array();
        $selected_package = array();
        foreach ($selected as $key) {
            $a = explode("-", $key);
            if ($a[0] == 'p') {
                $selected_package[] = $a[1];
            } else {
                $selected_test[] = $a[1];
            }
        }
        //print_r($selected_test);print_r($selected_package); die();
        //echo "SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='1' AND `test_master_city_price`.`city_fk`='$city_fk'";
        $test = $this->job_model->get_val("SELECT 
  test_master.`id`,
  `test_master`.`TEST_CODE`,
  `test_master`.`test_name`,
  `test_master`.`test_name`,
  `test_master`.`PRINTING_NAME`,
  `test_master`.`description`,
  `test_master`.`SECTION_CODE`,
  `test_master`.`LAB_COST`,
  `test_master`.`status`,
  `test_master_city_price`.`price`,
  t_tst AS sub_test 
FROM
  `test_master` 
  INNER JOIN `test_master_city_price` 
    ON `test_master`.`id` = `test_master_city_price`.`test_fk` 
  LEFT JOIN 
    (SELECT 
      GROUP_CONCAT(tmm.`test_name` SEPARATOR '%@%') AS t_tst,
      tm.`id` 
    FROM
      `sub_test_master` 
      LEFT JOIN `test_master` tm 
        ON `sub_test_master`.`test_fk` = tm.`id` 
      LEFT JOIN test_master tmm 
        ON `sub_test_master`.`sub_test` = tmm.id 
    WHERE `sub_test_master`.`status` = '1' 
    GROUP BY tm.`id`) AS tst 
    ON tst.id = `test_master`.`id` 
WHERE `test_master`.`status` = '1' 
  AND `test_master_city_price`.`status` = '1' 
  AND `test_master_city_price`.`city_fk` = '" . $city_fk . "' 
GROUP BY `test_master`.`id`");
        $package = $this->job_model->get_val("SELECT 
              `package_master`.*,
              `package_master_city_price`.`a_price` AS `a_price1`,
              `package_master_city_price`.`d_price` AS `d_price1`
              FROM
              `package_master`
              INNER JOIN `package_master_city_price`
              ON `package_master`.`id` = `package_master_city_price`.`package_fk`
              WHERE `package_master`.`status` = '1'
              AND `package_master_city_price`.`status` = '1' AND `package_master_city_price`.`city_fk` = '$city_fk' ");
        $test_list = '<option value="">--Select Test--</option>';
//        $test_list = '<select class="chosen-select" data-live-search="true" id="test" data-placeholder="Select Test" onchange="get_test_price();">
//			<option value="">--Select Test--</option>';
        foreach ($test as $ts) {
            if (!in_array($ts['id'], $selected_test)) {
                if ($cut > 0) {
                    $new_price = $ts['price'] - ($cut * $ts['price'] / 100);
                } else {
                    $new_price = $ts['price'];
                }
                $new_price = round($new_price);
                $test_list .= ' <option value="t-' . $ts['id'] . '">' . ucfirst($ts['test_name']) . ' (Rs.' . $new_price . ')</option>';
            }
        }
        foreach ($package as $pk) {
            if (!in_array($pk['id'], $selected_package)) {
                $test_list .= '<option value="p-' . $pk['id'] . '">' . ucfirst($pk['title']) . ' (Rs.' . $pk['d_price1'] . ')</option>';
            }
        }
        //$test_list .= '</select>';

        echo json_encode(array("refer" => $refer, "test_list" => $test_list, "customer" => $customer_data, "test_ary" => $test,"discount"=>$discount));
    }

    function get_quote_test_list() {
        $selected = $this->input->get_post("selected");
        $city_fk = $this->input->get_post("city_fk");
        if ($city_fk == '') {
            $city_fk = 1;
        }
        $selected_test = array();
        $selected_package = array();
        foreach ($selected as $key) {
            $a = explode("-", $key);
            if ($a[0] == 'p') {
                $selected_package[] = $a[1];
            } else {
                $selected_test[] = $a[1];
            }
        }
        //print_r($selected_test);print_r($selected_package); die();
        $test = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $city_fk . "'");
        $package = $this->job_model->get_val("SELECT 
              `package_master`.*,
              `package_master_city_price`.`a_price` AS `a_price1`,
              `package_master_city_price`.`d_price` AS `d_price1`
              FROM
              `package_master`
              INNER JOIN `package_master_city_price`
              ON `package_master`.`id` = `package_master_city_price`.`package_fk`
              WHERE `package_master`.`status` = '1'
              AND `package_master_city_price`.`status` = '1' AND `package_master_city_price`.`city_fk` = '" . $city_fk . "' ");
        $test_list = '<option value="">--Select Test--</option>';
//        $test_list = '<select class="chosen-select" data-live-search="true" id="test" data-placeholder="Select Test" onchange="get_test_price();">
//			<option value="">--Select Test--</option>';
        foreach ($test as $ts) {
            if (!in_array($ts['id'], $selected_test)) {
                $test_list .= ' <option value="t-' . $ts['id'] . '"> ' . ucfirst($ts['test_name']) . ' (Rs.' . $ts['price'] . ')</option>';
            }
        }
        foreach ($package as $pk) {
            if (!in_array($pk['id'], $selected_package)) {
                $test_list .= '<option value="p-' . $pk['id'] . '"> ' . ucfirst($pk['title']) . ' (Rs.' . $pk['d_price1'] . ')</option>';
            }
        }
        //$test_list .= '</select>';

        echo json_encode(array("refer" => $refer, "test_list" => $test_list, "customer" => $customer_data));
    }

    function get_customer_list() {
        /* $referral_list = $this->job_model->master_fun_get_tbl_val("doctor_master", array('status' => 1), array("full_name", "asc"));
          $refer = '<option value="">--Select--</option>';
          foreach ($referral_list as $referral) {
          $refer .= '<option value="' . $referral['id'] . '"';
          $refer .= '>' . ucwords($referral['full_name']) . '-' . $referral['mobile'] . '</option>';
          }
         */
        //$customer = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "full_name !=" => ""), array("full_name", "asc"));
        $customer = array();
        $customer_data = '<option value="">--Select--</option>';
        foreach ($customer as $key) {
            $customer_data .= '<option value="' . $key["id"] . '">' . ucfirst($key["full_name"]) . ' - ' . $key["mobile"] . '</option>';
        }


        /* $test = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='1' AND `test_master_city_price`.`city_fk`='1'");
          $package = $this->job_model->get_val("SELECT
          `package_master`.*,
          `package_master_city_price`.`a_price` AS `a_price1`,
          `package_master_city_price`.`d_price` AS `d_price1`
          FROM
          `package_master`
          INNER JOIN `package_master_city_price`
          ON `package_master`.`id` = `package_master_city_price`.`package_fk`
          WHERE `package_master`.`status` = '1'
          AND `package_master_city_price`.`status` = '1' AND `package_master_city_price`.`city_fk` = '1' "); */
        $test_list = '<option value="">--Select Test--</option>';
//        $test_list = '<select class="chosen-select" data-live-search="true" id="test" data-placeholder="Select Test" onchange="get_test_price();">
//			<option value="">--Select Test--</option>';
        foreach ($test as $ts) {
            $test_list .= ' <option value="t-' . $ts['id'] . '"> ' . ucfirst($ts['test_name']) . ' (Rs.' . $ts['price'] . ')</option>';
        }
        foreach ($package as $pk) {
            $test_list .= '<option value="p-' . $pk['id'] . '"> ' . ucfirst($pk['title']) . ' (Rs.' . $pk['d_price1'] . ')</option>';
        }
        //$test_list .= '</select>';

        echo json_encode(array("refer" => $refer, "test_list" => $test_list, "customer" => $customer_data));
    }

    function get_user_info() {
        $this->load->model('job_model');
        $user_id = $this->input->get_post("user_id");
        if ($user_id != ull) {
            $customer = $this->job_model->master_fun_get_tbl_val("customer_master", array("id" => $user_id), array("full_name", "asc"));
			$getjobcity=$this->job_model->getjobcity($customer[0]['id']);
			
            $data['relation_list'] = $this->job_model->get_val("SELECT 
  `customer_family_master`.*,
  `relation_master`.`name` AS relation_name 
FROM
  `customer_family_master` 
  INNER JOIN `relation_master` 
    ON `customer_family_master`.`relation_fk` = `relation_master`.`id` 
WHERE `customer_family_master`.`status` = '1' 
  AND `relation_master`.`status` = '1' 
  AND `customer_family_master`.`user_fk` = '" . $user_id . "'");
            $cnt = 0;
            $nw_ary = array();
            foreach ($customer as $key) {
                if ($key["full_name"] == '' || $key["full_name"] == null) {
                    $key["full_name"] = "";
                }
                if ($key["gender"] == '' || $key["gender"] == null) {
                    $key["gender"] = '';
                }
                if ($key["email"] == '' || $key["email"] == null) {
                    $key["email"] = "";
                }
                $key["password"] = "";
                if ($key["mobile"] == '' || $key["mobile"] == null) {
                    $key["mobile"] = "";
                }
                if ($key["address"] == '' || $key["address"] == null) {
                    $key["address"] = "";
                }
                if ($key["country"] == '' || $key["country"] == null) {
                    $key["country"] = "";
                }
                if ($key["state"] == '' || $key["state"] == null) {
                    $key["state"] = "";
                }
                if ($key["city"] == '' || $key["city"] == null) {
                    $key["city"] = "";
                }
                /* if ($key["test_city"] == '' || $key["test_city"] == null) {
                    $key["test_city"] = "";
                } */
                if ($key["dob"] == '0000-00-00' || $key["dob"] == null) {
                    $key["dob"] = "";
                }
				if($getjobcity != ""){ $key["test_city"]=$getjobcity->test_city; }
                //print_r($key); die();
                $nw_ary[$cnt] = $key;
                $cnt++;
            }
            $family_html = "";
            $family_html .= "<option value=''>Self</option>";
            //print_R($data['relation_list']); die();
            foreach ($data['relation_list'] as $key) {
                $family_html .= "<option value='" . $key["id"] . "'>" . $key["name"] . " (" . $key["relation_name"] . ")</option>";
            }
            $family_html .= "<option value='new'>--Add New--</option>";
            $nw_ary[0]["family"] = $family_html;
            /* Customer active package details start */
            $active_package = $this->job_model->get_val("SELECT 
  `active_package`.*,
  `customer_family_master`.`name`,
  `package_master`.`title` 
FROM
  `active_package` 
  LEFT JOIN `customer_family_master` 
    ON `active_package`.`family_fk` = `customer_family_master`.`id` 
  LEFT JOIN `package_master` 
    ON `package_master`.`id` = `active_package`.`package_fk` 
WHERE `active_package`.`status` = '1' 
  AND `due_to` >= '" . date("Y-m-d") . "' 
  AND `active_package`.`user_fk` = '" . $user_id . "' AND `active_package`.`parent`='0'");
            /* Customer active package details end */
            echo json_encode(array("user_info" => $nw_ary[0], "active_package" => $active_package));
        }
    }

    function get_city_test() {

        $city = $this->input->get_post("city");
        if ($city) {
            $data['test'] = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $city . "'");
        } else {
            $data['test'] = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='1'");
        }
        $this->load->view("get_city_test_reg", $data);
    }

    function get_call_user_data() {
        $data["login_data"] = logindata();
        $this->load->model("user_call_model");
        $result = array();
        $running_call = $this->user_call_model->user_running_call($data["login_data"]["email"]);
        //print_r($running_call);
        //$numbers = '"' . substr($running_call[0]->CallFrom, 1) . '"';
        if (substr($running_call[0]->CallFrom, 0, 3) === '079') {
            $org_number = $running_call[0]->CallFrom;
            $numbers = '"' . $running_call[0]->CallFrom . '"';
        } else {
            $org_number = substr($running_call[0]->CallFrom, 1);
            $numbers = '"' . substr($running_call[0]->CallFrom, 1) . '"';
        }
        $calls = $this->user_call_model->master_fun_get_tbl_val('exotel_calls', array('status' => 1, 'CallFrom' => $running_call[0]->CallFrom), array('id', 'asc'));

        if ($running_call[0]->maxid != "") {
            $register = $this->user_call_model->master_fun_get_tbl_val('customer_master', array('status' => '1', 'mobile' => $org_number), array("id", "desc"));
            $result["number"] = $org_number;
            if (count($register) > 0) {

                $result["customer"] = $register[0];
            }
        }

        echo json_encode($result);
    }

    function get_user_name() {
        $id = $this->input->post("id");
        $get_val = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => '1', "active" => '1', "id" => $id), array("id", "asc"));
        if (count($get_val) > 0) {


            echo "New Incoming Call Number " . $get_val[0]['mobile'] . ".  Registered Name: <b> " . $get_val[0]['full_name'] . "</b>";
        }
    }

    function test_email() {

        $message = '<div style="background:#a3a3a3 !important;font-family: "Roboto",sans-serif;">
<script src="https://use.fontawesome.com/44049e3dc5.js"></script>
    <div style="background: #6b6b6b none repeat scroll 0 0;float: left;padding: 7%;">
        <div style="border:1px solid #f1f1f1;border-bottom:1px solid #67B1A3;padding:1.5%;width:100%;float:left;background:#fff;">
            <div style="float:left;width:64%;">
                        <img alt="" src="' . base_url() . 'user_assets/images/logo.png" style="height: 100px;width:213px;"/>
            </div>
                <div style="float:right;text-align: right;width:33%;padding-top:7px;">

               
                
                </div>
                </div>
        <div style="padding:1.5%;width:100%;float:left;background-color:#fff;border:1px solid #f1f2f3;">
                <div style="padding:0 4%;">
                    <h4><b>Create Account</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your account successfully created. </p>
                     <p style="color:#7e7e7e;font-size:13px;"> Username/Email : . test  </p>  
<p style="color:#7e7e7e;font-size:13px;"> Password : test  </p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>
        </div>
        <div style="width:100%;float:left;background:#F5F5F5;padding:1.5%;color:#828282;font-size:12px;border:1px solid #dfdfdf;text-align:center;">
                <a href="' . base_url() . 'user_master/about_us" style="color:#515151;text-decoration:none;">About Us</a> |
                <a href="' . base_url() . 'user_master/contact_us" style="color:#515151;text-decoration:none;">Contact Us</a> |
                <a href="' . base_url() . 'user_login" style="color:#515151;text-decoration:none;">Login</a> |
                <a href="' . base_url() . 'user_master/collection" style="color:#515151;text-decoration:none;">Home-collection</a> |
                <a href="https://www.facebook.com/airmedpathlabs" style="color:#515151;text-decoration:none;"><i aria-hidden="true"><img src="' . base_url() . 'user_assets/images/face_book_icon1.jpeg" style="height:15px;width:15px;"></i></a>                  
        </div>
        <div style="width:100%;float:left;background:#333333;padding:1.5%;color:#ccc;font-size:12px;border:1px solid #323232;text-align:center;">
                Copyright @ 2016 Airmed PATH LAB. All rights reserved
        </div>
    </div>
</div>';
        $this->load->helper("email");
        $email = new Email();
        $email->send(array("nishit@virtualheight.com"), "nishit@virtualheight.com", "Account Created Successfully", $message);
        echo "OK";
        /* $this->email->to($email);
          $this->email->from($this->config->item('admin_booking_email'), 'Airmed PathLabs');
          $this->email->subject('Account Created Successfully');
          $this->email->message($message);
          $this->email->send(); */
    }

    function get_telecaller_remain() {
        $data["login_data"] = logindata();
        $cid = $this->input->post("cid");
        $cname = $this->input->post("cname");
        $cmobile = $this->input->post("cmobile");
        $cemail = $this->input->post("cemail");
        $cgender = $this->input->post("cgender");
        $ctestcity = $this->input->post("ctestcity");
        $caddress = $this->input->post("caddress");
        $cbooktest = $this->input->post("cbooktest");
        $cdis = $this->input->post("cdis");
        $cpayableamo = $this->input->post("cpayableamo");
        $ctotalamo = $this->input->post("ctotalamo");
        $cnote = $this->input->post("cnote");
        $phlebo = $this->input->post("phlebo");
        $phlebo_date = $this->input->post("phlebo_date");
        $phlebo_time = $this->input->post("phlebo_time");
        $notify = $this->input->post("notify");
        $source = $this->input->post("source");
        $referral_by = $this->input->post("referral_by");
        $testid = array();
        $packageid = array();
        foreach ($cbooktest as $key) {
            $tn = explode("-", $key);
            if ($tn[0] == 't') {
                $testid[$cnt] = $tn[1];
            }
            if ($tn[0] == 'p') {
                $packageid[$cnt] = $tn[1];
            }
            $cnt++;
        }
        if (!empty(testid)) {
            $id = implode(",", $testid);
        } else {
            $id = '';
        }
        if (!empty($packageid)) {
            $pids = implode(",", $packageid);
        } else {
            $pids = '';
        }
        $data1 = array("customer_fk" => $cid,
            "customer_name" => $cname,
            "customer_mobile" => $cmobile,
            "customer_email" => $cemail,
            "customer_gender" => $cgender,
            "customer_testcity" => $ctestcity,
            "customer_address" => $caddress,
            "book_test_id" => $id,
            "book_package_id" => $pids,
            "payment_discount" => $cdis,
            "payable_amount" => $cpayableamo,
            "total_amount" => $ctotalamo,
            "booktest_note" => $cnote,
            "phlebo" => $phlebo,
            "phlebo_date" => $phlebo_date,
            "phlebo_time" => $phlebo_time,
            "notify" => $notify,
            "source" => $source,
            "referral_by" => $referral_by,
            "created_date" => date('Y-m-d H:i:s'),
            "created_by" => $data["login_data"]["id"]
        );
        $insert = $this->job_model->master_fun_insert("oncall_booking_data", $data1);
        if ($insert) {
            echo $insert;
        } else {
            echo "0";
        }
    }

    function get_user_orders() {
        $user_id = $this->input->post("user_id");
        $this->load->model('customer_model');
        $data['query'] = $this->customer_model->master_fun_get_tbl_val("customer_master", array("id" => $user_id), array("id", "desc"));
        $data1 = $this->customer_model->get_all_job($user_id);
        $newdata = array();
        foreach ($data1 as $key) {
            $key['test'] = (($key['test'] == "") ? "" : $key['test'] . ',' ) . $key['packge_name'];
            $newdata[] = $key;
        }
        $data['job'] = $newdata;
        if (!empty($data['job'])) {
            echo '<table id="example4" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Order Id</th>
                                <th style="width:48%">Test/package Name</th>
                                <th>Price</th>
                                <th>Booking Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>';
            $cnt = 1;
            $temp = 1;
            foreach ($data['job'] as $job_details) {
                echo '<tr>
                                    <td><a target="_blank" href="' . base_url() . 'job-master/job-details/' . $job_details["id"] . '">' . $job_details["order_id"] . '</a></td>
                                    <td>';
                if ($job_details["test"] != NULL) {
                    echo str_replace(",", ", ", $job_details["test"]);
                }
                if ($job_details["package_name"] != NULL) {
                    echo ' ,' . $job_details["package_name"];
                }
                echo '</td><td>';
                if ($job_details["price"] != NULL) {
                    echo 'Rs. ' . $job_details["price"];
                }
                echo '</td><td>';
                echo $job_details["date"] . '</td><td>';
                if ($job_details["status"] == 1) {
                    echo '<span class="label label-danger">Waiting For Approval</span>';
                }
                if ($job_details["status"] == 6) {
                    echo '<span class="label label-warning">Approved</span>';
                }
                if ($job_details["status"] == 7) {
                    echo '<span class="label label-warning">Sample Collected</span>';
                }
                if ($job_details["status"] == 8) {
                    echo '<span class="label label-warning">Processing</span>';
                }
                if ($job_details["status"] == 2) {
                    echo '<span class="label label-success">Completed</span>';
                }
                echo '</td></tr>';
            }
            echo '</tbody></table>';
        } else {
            echo "0";
        }
    }

    function assign_phlebo_job($job_id, $phlebo) {
        $this->load->model("service_model");
        $new_job_details = $this->service_model->master_fun_get_tbl_val("job_master", array('id' => $job_id), array("id", "asc"));
        $booking_info = $this->service_model->master_fun_get_tbl_val("booking_info", array('id' => $new_job_details[0]["booking_info"]), array("id", "asc"));
        if ($booking_info[0]["emergency"] == "0" || $booking_info[0]["emergency"] == null) {
            $get_random_phlebo = $this->service_model->get_random_phlebo($booking_info[0]);
            if (!empty($get_random_phlebo)) {
                /* Nishit code start */
                $data = array("job_fk" => $job_id, "phlebo_fk" => $phlebo, "date" => $booking_info[0]["date"], "time_fk" => $booking_info[0]["time_slot_fk"], "address" => $booking_info[0]["address"], "notify_cust" => 1, "created_date" => date("Y-m-d H:i:s"), "created_by" => $data["login_data"]["id"]);
                $insert = $this->service_model->master_fun_insert("phlebo_assign_job", $data);
                //$this->user_test_master_model->master_fun_insert("job_log", array("job_fk" => $job_id, "created_by" => "", "updated_by" => $login_id, "deleted_by" => "", "message_fk" => "8", "date_time" => date("Y-m-d H:i:s")));

                /* Nishit code end */
                //$update = $this->job_model->master_fun_update("phlebo_master", array("id", $phlebo_id), $data);
                $phlebo_details = $this->service_model->master_fun_get_tbl_val("phlebo_master", array('id' => $phlebo, "type" => 1), array("id", "asc"));
                $phlebo_job_details = $this->service_model->master_fun_get_tbl_val("phlebo_assign_job", array('job_fk' => $job_id), array("id", "asc"));
                $p_time = $this->service_model->master_fun_get_tbl_val("phlebo_time_slot", array('id' => $phlebo_job_details[0]["time_fk"]), array("id", "asc"));
                $job_details = $this->service_model->master_fun_get_tbl_val("job_master", array('id' => $job_id), array("id", "asc"));
                $customer_details = $this->service_model->master_fun_get_tbl_val("customer_master", array('id' => $job_details[0]['cust_fk']), array("id", "asc"));
                if ($insert) {
                    /* Pinkesh send sms code start */
                    $s_time = date('h:i a', strtotime($p_time[0]["start_time"]));
                    $e_time = date('h:i a', strtotime($p_time[0]["end_time"]));
                    $sms_message = $this->service_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "phlebo_msg"), array("id", "asc"));
                    $sms_message = preg_replace("/{{NAME}}/", ucfirst($phlebo_details[0]["name"]), $sms_message[0]["message"]);
                    $sms_message = preg_replace("/{{MOBILE}}/", $phlebo_details[0]["mobile"], $sms_message);
                    $sms_message = preg_replace("/{{ORDERID}}/", $job_details[0]["order_id"], $sms_message);
                    $sms_message = preg_replace("/{{CNAME}}/", $customer_details[0]["full_name"], $sms_message);
                    $sms_message = preg_replace("/{{CMOBILE}}/", $customer_details[0]["mobile"], $sms_message);
                    $sms_message = preg_replace("/{{CADDRESS}}/", $phlebo_job_details[0]["address"], $sms_message);
                    $sms_message = preg_replace("/{{DATE}}/", $phlebo_job_details[0]["date"], $sms_message);
                    $sms_message = preg_replace("/{{TIME}}/", $s_time . " To " . $e_time, $sms_message);
                    $job_details = $this->get_job_details($job_id);
                    $b_details = array();
                    $total_price = 0;
                    foreach ($job_details[0]["book_test"] as $bkey) {
                        $b_details[] = $bkey["test_name"] . " Rs." . $bkey["price"];
                        $total_price = $total_price + $bkey["price"];
                    }
                    foreach ($job_details[0]["book_package"] as $bkey) {
                        $b_details[] = $bkey["title"] . " Rs." . $bkey["d_price"];
                        $total_price = $total_price + $bkey["d_price"];
                    }
                    $sms_message = preg_replace("/{{BOOKINFO}}/", implode(",", $b_details) . " Total Price-Rs." . $total_price, $sms_message);
                    //$this->user_test_master_model->master_fun_insert("test", array("test"=>$sms_message."-".json_encode($job_details)));
                    //$sms_message="done";
                    $mobile = $phlebo_details[0]['mobile'];
                    $this->load->helper("sms");
                    $notification = new Sms();
                    $notification->send($mobile, $sms_message);
                    /* Pinkesh send sms code end */

                    // if ($notify == 1) {
                    /* Pinkesh send sms code start */
                    $sms_message = $this->service_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "phlebo_msg_cust"), array("id", "asc"));
                    $sms_message = preg_replace("/{{NAME}}/", ucfirst($customer_details[0]["full_name"]), $sms_message[0]["message"]);
                    $sms_message = preg_replace("/{{MOBILE}}/", $customer_details[0]["mobile"], $sms_message);
                    $sms_message = preg_replace("/{{ORDERID}}/", $job_details[0]["order_id"], $sms_message);
                    $sms_message = preg_replace("/{{PNAME}}/", ucfirst($phlebo_details[0]["name"]), $sms_message);
                    $sms_message = preg_replace("/{{PMOBILE}}/", $phlebo_details[0]["mobile"], $sms_message);
                    $sms_message = preg_replace("/{{DATE}}/", $phlebo_job_details[0]["date"], $sms_message);
                    $sms_message = preg_replace("/{{TIME}}/", $s_time . " To " . $e_time, $sms_message);
                    $sms_message = preg_replace("/{{BOOKINFO}}/", implode(",", $b_details) . " Total Price-Rs." . $total_price, $sms_message);
                    $mobile = $customer_details[0]['mobile'];
                    //$sms_message="done";
                    $notification->send($mobile, $sms_message);
                    /* Pinkesh send sms code end */
                    //}
                } else {
                    
                }
            }
        }
    }

    function get_job_details($job_id) {
        $this->load->model("service_model");
        $job_details = $this->job_model->master_fun_get_tbl_val("job_master", array("status !=" => "0", "id" => $job_id), array("id", "asc"));
        if (!empty($job_details)) {
            $book_test = $this->job_model->master_fun_get_tbl_val("job_test_list_master", array("job_fk" => $job_id), array("id", "desc"));
            $book_package = $this->job_model->master_fun_get_tbl_val("book_package_master", array("job_fk" => $job_id, "status" => "1"), array("id", "desc"));
            $test_name = array();
            foreach ($book_test as $key) {
                $price1 = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $job_details[0]['test_city'] . "' AND `test_master`.`id`='" . $key["test_fk"] . "'");
                $test_name[] = $price1[0];
            }
            $job_details[0]["book_test"] = $test_name;
            $package_name = array();
            foreach ($book_package as $key) {

                $price1 = $this->job_model->get_val("SELECT
          `package_master`.*,
          `package_master_city_price`.`a_price` AS `a_price`,
          `package_master_city_price`.`d_price` AS `d_price`
          FROM
          `package_master`
          INNER JOIN `package_master_city_price`
          ON `package_master`.`id` = `package_master_city_price`.`package_fk`
          WHERE `package_master`.`status` = '1'
          AND `package_master_city_price`.`status` = '1' AND `package_master_city_price`.`city_fk` = '" . $job_details[0]["test_city"] . "' AND `package_master`.`id`='" . $key["package_fk"] . "'");
                $package_name[] = $price1[0];
            }
            $job_details[0]["book_package"] = $package_name;
        }
        return $job_details;
    }

    function get_job_id($city = null) {
        $this->load->library("Util");
        $util = new Util();
        $new_id = $util->get_job_id($city);
        return $new_id;
    }

    function pdf_invoice($id) {
        $data["login_data"] = loginuser();
        $this->load->model('add_result_model');
        $data['query'] = $this->add_result_model->job_details($id);
        $data['book_list'] = array();
        $tid = explode(",", $data['query'][0]['testid']);
        $fast = array();
        if ($data['query'][0]['testid'] != '') {
            foreach ($tid as $tst_id) {
                $para = $this->add_result_model->get_val("SELECT t.test_name as book_name,t.id as tid,p.price as price FROM test_master as t left join test_master_city_price as p  on p.test_fk=t.id WHERE t.status='1' AND p.status='1' AND t.id='" . $tst_id . "' AND p.city_fk='" . $data['query'][0]['test_city'] . "' order by t.test_name ASC");
                array_push($data['book_list'], $para);
                $test_fast = $this->add_result_model->get_val("SELECT fasting_requird FROM test_master WHERE status='1' AND id='" . $tst_id . "'");
                array_push($fast, $test_fast[0]['fasting_requird']);
            }
        }
        $pid = explode("%", $data['query'][0]['packageid']);
        if ($data['query'][0]['packageid'] != '') {
            foreach ($pid as $pack_id) {
                $para = $this->add_result_model->get_val("SELECT p.id as pid,p.title as book_name,pr.d_price as price FROM package_master as p left join package_master_city_price as pr on pr.package_fk=p.id WHERE p.status='1' AND pr.status='1' AND p.id='" . $pack_id . "' AND pr.city_fk='" . $data['query'][0]['test_city'] . "' order by p.title ASC");
                array_push($data['book_list'], $para);
            }
        }
        if (in_array("1", $fast)) {
            $data['fasting'] = 'Fasting required for 12 hours.';
        } else {
            $data['fasting'] = 'Fasting not required for 12 hours.';
        }
        $data['time'] = $this->add_result_model->get_val("SELECT ts.start_time,ts.end_time FROM booking_info as b left join phlebo_time_slot as ts on b.time_slot_fk=ts.id WHERE ts.status='1' AND b.status='1' AND b.id='" . $data['query'][0]['booking_info'] . "'");
        //echo "<pre>"; print_r($data['parameter_list']); die();
        $pdfFilePath = FCPATH . "/upload/result/" . $data['query'][0]['order_id'] . "_invoice.pdf";
        $data['page_title'] = 'AirmedLabs'; // pass data to the view
        ini_set('memory_limit', '32M'); // boost the memory limit if it's low <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="?" draggable="false" class="emoji">
        $html = $this->load->view('user/user_job_invoice_pdf', $data, true); // render the view into HTML
        //$param = '"en-GB-x","A4","","",10,10,0,10,6,3,"P"'; // Landscape
        //$lorem = utf8_encode($html); // render the view into HTML
        //$html = "<!DOCTYPE html>                         <html><body>\u0627\u0644\u0643\u0647\u0631\u0628\u0627\u0621 \u0648 \u0627\u0644\u0633\u0628\u0627\u0643\u0629</body></html>      ";
        ob_end_flush();
        $this->load->library('pdf');

        $pdf = $this->pdf->load();
        $pdf->autoScriptToLang = true;
        $pdf->baseScript = 1; // Use values in classes/ucdn.php  1 = LATIN
        $pdf->autoVietnamese = true;
        $pdf->autoArabic = true;

        $pdf->autoLangToFont = true;
        $pdf->AddPage('p', // L - landscape, P - portrait
                '', '', '', '', 5, // margin_left
                5, // margin right
                2, // margin top
                2, // margin bottom
                2, // margin header
                2); // margin footer
        //$pdf->AddPage('P', '', 1, 'i', 'on');
        //$pdf->SetDirectionality('rtl');
        /* $pdf->AddPage('P', // L - landscape, P - portrait
          '', '', '', '', 00, // margin_left
          0, // margin right
          0, // margin top
          0, // margin bottom
          0, // margin header
          0); */

        //$pdf->SetDisplayMode('fullpage');
        //$pdf=new mPDF('utf-8','A4');
        //$pdf->debug = true;
        // $pdf->h2toc = array('H2' => 0);
        // $html = '';
        // Split $lorem into words
        $pdf->WriteHTML($html);
        $pdf->debug = true;
        $pdf->allow_output_buffering = TRUE;
        //$pdf->SetFooter('www.' . $_SERVER['HTTP_HOST'] . '||' . $new_time); // Add a footer for good measure <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="?" draggable="false" class="emoji">
        // $pdf->WriteHTML($html); // write the HTML into the PDF
        $pdf->Output($pdfFilePath, 'F'); // save to file because we can
        $name = $data['query'][0]['order_id'] . "_invoice.pdf";
        $this->add_result_model->master_fun_update('job_master', array('id', $id), array("invoice" => $name));
        //redirect("/upload/result/" . $data['query'][0]['order_id'] . "_invoice.pdf");
        return $name;
    }

    function book_active_package() {
        $data["login_data"] = loginuser();
        $active_package = $this->input->get_post("acttive_package");
        $phlebo_time = $this->input->get_post("phlebo_time");
        $active_address = $this->input->get_post("active_address");
        $test_city = $this->input->get_post("test_city");
        $branch = $this->input->get_post("branch");
        $notify = $this->input->get_post("notify1");
        $get_active_package_data = $this->job_model->master_fun_get_tbl_val("active_package", array("id" => $active_package), array("id", "desc"));
        $test_for = 'self';
        if ($get_active_package_data[0]["family_fk"] > 0) {
            $test_for = 'family';
        }
        $booking_fk = $this->job_model->master_fun_insert("booking_info", array("user_fk" => $get_active_package_data[0]["user_fk"], "type" => $test_for, "family_member_fk" => $get_active_package_data[0]["family_fk"], "address" => $active_address, "date" => $active_phlebo_date, "time_slot_fk" => $phlebo_time, "emergency" => 0, "status" => "1", "createddate" => date("Y-m-d H:i:s")));
        /* Nishit book phlebo end */
        $order_id = $this->get_job_id($test_city);
        $barcode = $this->util_lib->barcode_generate($branch);
        $data = array(
            "order_id" => $order_id,
            "cust_fk" => $get_active_package_data[0]["user_fk"],
            "doctor" => '',
            "other_reference" => '',
            "date" => date("Y-m-d H:i:s"),
            "price" => '0',
            "branch_fk" => $branch,
            "status" => '6',
            "payment_type" => "Cash On Delivery",
            "discount" => '0',
            "payable_amount" => '0',
            "test_city" => $test_city,
            "note" => '',
            "added_by" => $data["login_data"]["id"],
            "booking_info" => $booking_fk,
            "collection_charge" => 0,
            "notify_cust" => $notify,
            "date" => date("Y-m-d H:i:s"),
            "barcode" => $barcode
        );

        $insert = $this->job_model->master_fun_insert("job_master", $data);
        $this->job_model->master_fun_insert("book_package_master", array("cust_fk" => $get_active_package_data[0]["user_fk"], "package_fk" => $get_active_package_data[0]["package_fk"], 'job_fk' => $insert, "status" => "1", "type" => "2", "price" => "0"));

//        $this->job_model->master_fun_insert("booked_job_test", array("job_fk" => $insert, "test_city" => $test_city, "test_fk" => "p-" . $get_active_package_data[0]["package_fk"], "price" => 0));

        $this->job_model->master_fun_insert("active_package", array(
            "user_fk" => $get_active_package_data[0]["user_fk"],
            "package_fk" => $get_active_package_data[0]["package_fk"],
            "job_fk" => $insert,
            "family_fk" => $get_active_package_data[0]["family_fk"],
            "book_date" => date("Y-m-d"),
            "parent" => $get_active_package_data[0]["id"],
            "status" => "1",
            "created_date" => date("Y-m-d H:i:s")
        ));
        $this->session->set_flashdata("success", array("Active package successfully Booked."));
        redirect("Admin/TelecallerCallBooking");
    }

    function check_active_package($pid, $jid, $uid) {
        /* Nishit active package start */
        $this->load->library("util");
        $util = new Util;
        $util->check_active_package($pid, $jid, $uid);
        /* Nishit active package end */
    }

    function collect_amount() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $date = $this->input->get_post("date");
        $amount = $this->input->get_post("amount");
        $user = $this->input->get_post("user");
        if (!empty($data["login_data"]["id"])) {
            $new_date = explode('/', $date);
            $date1 = $new_date[2] . "-" . $new_date[1] . "-" . $new_date[0];
            $data = array(
                "admin_fk" => $user,
                "amount" => $amount,
                "date" => $date1,
                "created_by" => $data["login_data"]["id"],
                "createddate" => date("Y-m-d H:i:s")
            );
            $insert = $this->job_model->master_fun_insert("admin_received_amount", $data);
        }
        redirect("Dashboard?c_date=" . $this->input->get_post("date"));
    }

    function add_doctor() {
        $name = $this->input->post("d_name");
        $mobile = $this->input->get_post("d_mobile");
        $city = $this->input->get_post("city");
        $state = $this->job_model->master_fun_get_tbl_val("city", array("id" => $city), array("id", "desc"));
        $data = array(
            "full_name" => $name,
            "mobile" => $mobile,
            "city" => $city,
            "state" => $state[0]['state_fk'],
            "created_date" => date("Y-m-d H:i:s")
        );
        $insert = $this->job_model->master_fun_insert("doctor_master", $data);
        echo $insert;
    }
	function get_phlebo() {
		$cityfk = $this->input->post("val");
        $phlebo_list = $this->job_model->master_fun_get_tbl_val("phlebo_master", array('status' => 1, "test_city" =>$cityfk), array("id", "asc"));
		 $refer = '<option value="">--Select--</option>';
        foreach ($phlebo_list as $list) {
			$phlebodid=$list['id'];
			$phlebodname=$list['name'];
            $refer .= "<option value='$phlebodid' >$phlebodname</option>";
            
        }
        echo json_encode(array("phlebolist" => $refer));
     }

} 
