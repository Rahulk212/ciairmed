<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Branch_report extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('job_model');
        $this->load->model('user_model');
        $this->load->model('branch_report_model');

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
        $data['branch'] = $this->input->get("branch");
        $start_date = null;
        $end_date = null;
        if ($data['start_date'] != "") {
            $start_date = $data['start_date'];
        }

        if ($data['end_date'] != "") {
            $end_date = $data['end_date'];
        }
        $data['branch_list'] = $this->user_model->master_fun_get_tbl_val("branch_master", array('status' => 1), array("id", "asc"));
        $data['collecting_amount_branch'] = $this->branch_report_model->branchPaymentReport($start_date, $end_date, $data['branch']);
//        print_r($data['collecting_amount_branch']); die();
        $this->load->view('header', $data);
        $this->load->view('nav', $data);
        $this->load->view('branch_report', $data);
        $this->load->view('footer');
    }
    function report() {
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
        $start_date = null;
        $end_date = null;
        if ($data['start_date'] != "") {
            $start_date = $data['start_date'];
        }

        if ($data['end_date'] != "") {
            $end_date = $data['end_date'];
        }
        $data['city_list'] = $this->user_model->master_fun_get_tbl_val("test_cities", array('status' => 1), array("id", "asc"));
        $data['branch_list'] = $this->user_model->master_fun_get_tbl_val("branch_master", array('status' => 1), array("id", "asc"));
        $data['collecting_amount_branch'] = $this->branch_report_model->branchPaymentReportdetails($start_date, $end_date, $data['branch'],$data['city']);
//        print_r($data['collecting_amount_branch']); die();
        $this->load->view('header', $data);
        $this->load->view('nav', $data);
        $this->load->view('branch_report_details', $data);
        $this->load->view('footer');
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
        $date1 = explode("/", $start);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $end);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $bd = date('Y-m-d', strtotime($sd . ' -1 day'));
        $back_date = $bd . " 00:00:00";
        $back_date1 = $bd . " 23:59:59";
        $delimiter = ",";
        $newline = "\r\n";
        $filename = "Branch_report.csv";
         $query = "select
            bm.branch_name as `Branch`,
                SUM(Round(jm.`price`)) as `Gross Amt`,
                SUM(Round((jm.`discount` * jm.`price`) / 100)) as `Discount`,
                SUM(Round(jm.`price` - ((if(jm.`discount` != 'NULL',jm.`discount`,0) * jm.`price`) / 100))) as `Net Amt`,
                SUM(Round(jm.payable_amount)) as `Due Amt`,
          (SELECT SUM(Round(c.amount)) from job_master_receiv_amount c left join job_master jci on jci.id=c.job_fk where jci.`model_type`='1' and jci.test_city='$city' and jci.branch_fk=bm.id and c.payment_type='CASH' AND c.createddate >= '" . $start_date . "' AND c.status='1' AND c.createddate <= '" . $end_date . "') as `Cash`,
              
              (SELECT SUM(Round(c.amount)) from job_master_receiv_amount c left join job_master jci on jci.id=c.job_fk where jci.`model_type`='1' and jci.test_city='$city' and jci.branch_fk=bm.id and c.payment_type='CHEQUE' AND c.createddate >= '" . $start_date . "' AND c.status='1' AND c.createddate <= '" . $end_date . "') as `Cheque`,
                (SELECT SUM(Round(c.amount)) from job_master_receiv_amount c left join job_master jci on jci.id=c.job_fk where jci.`model_type`='1' and jci.test_city='$city' and jci.branch_fk=bm.id and (c.payment_type='DEBIT CARD swiped thru ICICI' or c.payment_type='DEBIT CARD swiped thru MSWIP') AND c.createddate >= '" . $start_date . "' AND c.status='1' AND c.createddate <= '" . $end_date . "') as `Debit`,
                (SELECT SUM(Round(c.amount)) from job_master_receiv_amount c left join job_master jci on jci.id=c.job_fk where jci.`model_type`='1' and jci.test_city='$city' and jci.branch_fk=bm.id and (c.payment_type='CREDIT CARD' or c.payment_type='CREDIT CARD swiped thru ICICI' or c.payment_type='CREDIT CARD swiped thru MSWIP') AND c.createddate >= '" . $start_date . "' AND c.status='1' AND c.createddate <= '" . $end_date . "') as `Credit`,
                (SELECT SUM(Round(c.amount)) from job_master_receiv_amount c left join job_master jci on jci.id=c.job_fk where jci.`model_type`='1' and jci.test_city='$city' and jci.branch_fk=bm.id and (c.payment_type='Swipe thru AXIS' or c.payment_type='Swipe thru HDFC') AND c.createddate >= '" . $start_date . "' AND c.status='1' AND c.createddate <= '" . $end_date . "') as `Swipe Axis-Hdfc`,
                (SELECT SUM(Round(c.amount)) from job_master_receiv_amount c left join job_master jci on jci.id=c.job_fk where jci.`model_type`='1' and jci.test_city='$city' and jci.branch_fk=bm.id and (c.payment_type='ONLINE' or c.payment_type='WEB ONLINE') AND c.createddate >= '" . $start_date . "' AND c.status='1' AND c.createddate <= '" . $end_date . "') as `Online`,
                (SELECT SUM(Round(c.debit)) from wallet_master c left join job_master jci on jci.id=c.job_fk where jci.`model_type`='1' and jci.test_city='$city' and jci.branch_fk=bm.id AND c.created_time >= '" . $start_date . "' AND c.status='1' AND c.created_time <= '" . $end_date . "') as `Wallet`,
                (SELECT SUM(Round(c.amount)) from payment c left join job_master jci on jci.id=c.job_fk where jci.`model_type`='1' and jci.test_city='$city' and jci.branch_fk=bm.id AND c.paydate >= '" . $start_date . "' AND c.status='success' AND c.paydate <= '" . $end_date . "') as `PayUmoney`,
                (SELECT SUM(Round(c.amount)) from job_master_receiv_amount c left join job_master jci on jci.id=c.job_fk where jci.`model_type`='1' and jci.test_city='$city' and jci.branch_fk=bm.id and c.payment_type='PayTm' AND c.createddate >= '" . $start_date . "' AND c.status='1' AND c.createddate <= '" . $end_date . "') as `PayTm`,
                (select SUM(Round(s.amount)) from job_master_receiv_amount s left join job_master jci on jci.id=s.job_fk where jci.`model_type`='1' and jci.test_city='$city' and jci.branch_fk=bm.id and s.createddate >='".$start_date."' and s.createddate <='".$end_date."' and s.status='1' AND jci.date >='".$start_date."' AND jci.date <='".$end_date."' and jci.status != '0') as `Same Day Collection`,
                (select SUM(Round(b.amount)) from job_master_receiv_amount b left join job_master jci on jci.id=b.job_fk where jci.`model_type`='1' and jci.test_city='$city' and jci.branch_fk=bm.id and b.createddate<='".$end_date."' and b.createddate>='".$start_date."' and b.status='1' AND jci.date <='".$back_date1."' and jci.status != '0') as `Back Day Collection`
                 from job_master jm left join branch_master bm on bm.id=jm.branch_fk left join test_cities tc on tc.id=bm.city where jm.`model_type`='1' and jm.status != '0' AND jm.date >= '" . $start_date . "' AND jm.date <= '" . $end_date . "' AND tc.id = '" .$city. "' group by tc.id,jm.branch_fk order by tc.`id`,bm.`id` ASC";//echo $query; die();
        $result = $this->db->query($query);
        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
        force_download($filename, $data);
    }
    
    function business_export() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $start = $this->input->get("start_date");
        $end = $this->input->get("end_date");
        $date1 = explode("/", $start);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $end);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $delimiter = ",";
        $newline = "\r\n";
        $filename = "Business_report.csv";
        $qry = "select
            bm.branch_name as Branch,
                SUM(Round(jm.`price`)) as `Gross Amt`,
                SUM(Round((if(jm.`discount` != 'NULL',jm.`discount`,0) * if(jm.`price` != 'NULL',jm.`price`,0)) / 100)) as `Discount`,
                SUM(Round(if(jm.`price` != 'NULL',jm.`price`,0) - ((if(jm.`discount` != 'NULL',jm.`discount`,0) * if(jm.`price` != 'NULL',jm.`price`,0)) / 100))) as `Net Amt`,
                SUM(Round(jm.payable_amount)) as `Due Amt`
                from job_master jm left join branch_master bm on bm.id=jm.branch_fk left join test_cities tc on tc.id=bm.city where jm.status != '0' ";
        $qry .= " AND jm.date >= '" . $start_date . "' AND jm.date <= '" . $end_date . "'";
        $qry .= " group by tc.id,jm.branch_fk order by tc.`id`,bm.`id` ASC";
        $result = $this->db->query($qry);
        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
        force_download($filename, $data);
    }
}

?>