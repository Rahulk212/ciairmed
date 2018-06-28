<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Doctor_report extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('job_model');
        $this->load->model('user_model');
        $this->load->model('doctor_report_model');

        $this->load->library('email');
        $this->load->helper('string');
        $this->app_tarce();
    }

    function app_tarce() {
        $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $ipaddress = $_SERVER['REMOTE_ADDR'];
        $page = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}";
        if (!empty($_SERVER['QUERY_STRING'])) {
            $page = $_SERVER['QUERY_STRING'];
        } else {
            $page = "";
        }
        if (!empty($_POST)) {
            $user_post_data = $_POST;
        } else {
            $user_post_data = array();
        }
        $user_post_data = json_encode($user_post_data);
        $useragent = $_SERVER['HTTP_USER_AGENT'];
        $remotehost = @getHostByAddr($ipaddress);
        $user_info = json_encode(array("Ip" => $ipaddress, "Page" => $page, "UserAgent" => $useragent, "RemoteHost" => $remotehost));
        if ($actual_link != "http://www.airmedlabs.com/index.php/api/send") {
            $user_track_data = array("url" => $actual_link, "user_details" => $user_info, "data" => $user_post_data, "createddate" => date("Y-m-d H:i:s"), "type" => "service");
        }
        $app_info = $this->user_model->master_fun_insert("user_track", $user_track_data);
        //return true;
    }

    function payment() {
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
        $data['start_date'] = $this->input->get("start_date");
        $data['end_date'] = $this->input->get("end_date");
        $data['city'] = $this->input->get("city");
        $data['branch'] = $this->input->get("branch");
        $data['doctor'] = $this->input->get("doctor");
        $start_date = null;
        $end_date = null;
        if ($data['start_date'] != "") {
            $start_date = $data['start_date'];
        }

        if ($data['end_date'] != "") {
            $end_date = $data['end_date'];
        }
        $data['sales_person_list'] = $this->user_model->master_fun_get_tbl_val("sales_user_master", array('status' => 1,"type"=>'user'), array("id", "asc"));
        $data['city_list'] = $this->user_model->master_fun_get_tbl_val("test_cities", array('status' => 1), array("id", "asc"));
        $data['branch_list'] = $this->user_model->master_fun_get_tbl_val("branch_master", array('status' => 1), array("id", "asc"));
        $data['collecting_amount_branch'] = $this->doctor_report_model->doctorPaymentReport($start_date, $end_date,$data['city'], $data['doctor'],$data['branch']);
//        print_r($data['collecting_amount_branch']); die();
        $this->load->view('header', $data);
        $this->load->view('nav', $data);
        $this->load->view('doctor_payment_report', $data);
        $this->load->view('footer');
    }
    function doctor_cut() {
        $doc_id = $this->input->post('did');
        $doc_cut = $this->input->post('cut_val');
        $update = $this->doctor_report_model->master_fun_update("doctor_master", array("id",$doc_id), array("cut" => $doc_cut));
        echo $update;
    }
    function show_all_job() {
        if (!is_loggedin()) {
            redirect('login');
        }
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data["login_data"] = logindata();
        if ($data["login_data"]['type'] == 3 || $data["login_data"]['type'] == 4) {
            redirect('Logistic/dashboard');
        }
        $data['start_date'] = $this->input->get("start_date");
        $data['end_date'] = $this->input->get("end_date");
        $data['doctor'] = $this->input->get("doctor");
        $data['branch'] = $this->input->get("branch");
        $start_date = null;
        $end_date = null;
        if ($data['start_date'] != "") {
            $start_date = $data['start_date'];
        }

        if ($data['end_date'] != "") {
            $end_date = $data['end_date'];
        }
        $data['doctor_name'] = $this->user_model->master_fun_get_tbl_val("doctor_master", array('status' => 1,'id' => $data['doctor']), array("id", "asc"));
        $data['collecting_amount_branch'] = $this->doctor_report_model->doctorPaymentdetails($start_date, $end_date, $data['doctor'],$data['branch']);
        //print_R($data['collecting_amount_branch']); die();
        $this->load->view('header', $data);
        $this->load->view('nav', $data);
        $this->load->view('doctorpayment_details', $data);
        $this->load->view('footer');
    }
    function doctor_pay() {
        if (!is_loggedin()) {
            redirect('login');
        }
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data["login_data"] = logindata();
        $doc_id = $this->input->post('did');
        $doc_amount = $this->input->post('amount');
        $doc_remark = $this->input->post('remarks');
        $sales = $this->input->post('sales');
        $insert = $this->doctor_report_model->master_fun_insert('doctor_handover', array("doctor_fk" => $doc_id, "amount" => $doc_amount, "pay_by" => $data["login_data"]['id'],"remark" => $doc_remark,"sales_person_fk" => $sales));
        echo $insert;
    }
    
    function export() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $start = $this->input->get("start_date");
        $end = $this->input->get("end_date");
        $city = $this->input->get("city");
        $branch = $this->input->get("branch");
        $date1 = explode("/", $start);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $end);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $delimiter = ",";
        $newline = "\r\n";
        $filename = "Doctor_report.csv";
        $query = "select
            tc.name as `City`,
                dm.full_name as `Doctor`,
                dm.mobile as `Mobile`,
                count(jm.id) as `Sample`,
                SUM(jm.`price`) as `Gross Amt`,
                SUM((jm.`discount` * jm.`price`) / 100) as `Discount`,
                SUM(jm.price - ((jm.`discount` * jm.`price`) / 100) - jm.payable_amount) as `Net Amt`,
                SUM(jm.payable_amount) as `due Amt`
                from job_master jm join doctor_master dm on dm.id=jm.doctor left join test_cities tc on tc.id=jm.test_city where jm.status != '0' AND jm.date >= '" . $start_date . "' AND jm.date <= '" . $end_date . "' AND tc.id = '" .$city. "' AND jm.branch_fk = '" .$branch. "' group by tc.id,jm.doctor order by tc.`id`,dm.`id` ASC";
        $result = $this->db->query($query);
        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
        force_download($filename, $data);
    }
}

?>