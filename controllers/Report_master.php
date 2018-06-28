<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Report_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('job_model');
        $this->load->model('user_model');
        $this->load->model('report_model');

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

    function export() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $start = $this->input->get("start_date");
        $end = $this->input->get("end_date");
        $branch = $this->input->get("branch");
        $type = $this->input->get("type");
        $wise = $this->input->get("wise");
        $date1 = explode("/", $start);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $end);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $delimiter = ",";
        $newline = "\r\n";
        $filename = "Payment_report.csv";
        $qry = "select
            bm.branch_name as `Branch Name`,";
        if ($type == "client") {
            $qry .= "am.name as `Client Name`,";
        } else if ($type == "doctor") {
            $qry .= "dm.full_name as `Doctor Name`,";
        }
        if ($wise == "day") {
            $qry .= "DATE_FORMAT(jm.date,'%d-%M-%Y') as `Registration Date`,";
        } else if ($wise == "month") {
            $qry .= "CONCAT(MONTH(jm.date),'-',YEAR(jm.date)) as `Registration Date`,";
        } else if ($wise == "year") {
            $qry .= "YEAR(jm.date) as `Registration Date`,";
        }
        $qry .= "COUNT(jm.id) as `Sample Count`,
            SUM(Round(jm.`price`)) as `Total Amount`,
            SUM(Round((if(jm.`discount` != 'NULL',jm.`discount`,0) * if(jm.price != 'NULL',jm.price,0)) / 100)) as `Discount Amount`,
            SUM(Round(if(jm.price != 'NULL',jm.price,0) - ((if(jm.`discount` != 'NULL',jm.`discount`,0) * if(jm.price != 'NULL',jm.price,0)) / 100))) as `Net Amount`,
            SUM(Round(if(jm.price != 'NULL',jm.price,0) - ((if(jm.`discount` != 'NULL',jm.`discount`,0) * if(jm.price != 'NULL',jm.price,0)) / 100) - if(jm.payable_amount != 'NULL',jm.payable_amount,0))) as `Received Amount`,
            SUM(jm.payable_amount) as `Due Amount`
                from job_master jm join branch_master bm on bm.id=jm.branch_fk";
        if ($type == "client") {
            $qry .= " join admin_master am on am.id=jm.added_by ";
        } else if ($type == "doctor") {
            $qry .= " join doctor_master dm on dm.id=jm.doctor ";
        }
        $qry .= " where jm.status != '0' and bm.status = '1' and jm.`model_type`='1'  ";
        if ($start != "" || $end != "") {
            $qry .= " AND jm.date >= '" . $start_date . "' AND jm.date <= '" . $end_date . "'";
        }
        if ($branch != "") {
            $qry .= " AND jm.branch_fk = '" . $branch . "' ";
        }
        $qry .= " group by jm.branch_fk";
        if ($type == "client") {
            $qry .= ",am.id";
        } else if ($type == "doctor") {
            $qry .= ",dm.id";
        }
        if ($wise == "day") {
            $qry .= ",YEAR(jm.date),MONTH(jm.date),DAY(jm.date)";
        } else if ($wise == "month") {
            $qry .= ",MONTH(jm.date),YEAR(jm.date)";
        } else if ($wise == "year") {
            $qry .= ",YEAR(jm.date)";
        }
        $qry .= " order by bm.`id`,jm.date ASC";
        $result = $this->db->query($qry);
        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
        force_download($filename, $data);
    }

    function index() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data['start_date'] = $this->input->get("start_date");
        $data['end_date'] = $this->input->get("end_date");
        $data['branch'] = $this->input->get("branch");
        $data['type'] = $this->input->get("type");
        $data['wise'] = $this->input->get("wise");
        $start_date = null;
        $end_date = null;
        if ($data['start_date'] != "") {
            $start_date = $data['start_date'];
        }

        if ($data['end_date'] != "") {
            $end_date = $data['end_date'];
        }
        if ($data["login_data"]['type'] == 6 || $data["login_data"]['type'] == 5) {
            $user_branch = $this->user_model->master_fun_get_tbl_val("user_branch", array('status' => 1, "user_fk" => $data["login_data"]['id']), array("id", "asc"));
            $data['branch_list_se'] = $user_branch;
            $branch = array();
            foreach ($user_branch as $key1) {
                $branch[] = $key1["branch_fk"];
            }
            $data['branch_list_select'] = $branch;
        }
        $data['branch_list'] = $this->user_model->master_fun_get_tbl_val("branch_master", array('status' => 1), array("id", "asc"));
        $data['view_all_data'] = $this->report_model->diffrent_report($start_date, $end_date, $data['type'], $data['wise'], $data['branch'], $branch);
        if(isset($_REQUEST['debug2'])){
			echo $this->db->last_query(); die();
		}
		$this->load->view('header', $data);
        $this->load->view('nav', $data);
        $this->load->view('report_all', $data);
        $this->load->view('footer');
    }

    function panel() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data['start_date'] = $this->input->get("start_date");
        $data['end_date'] = $this->input->get("end_date");
        $data['branch'] = $this->input->get("branch");
        $data['wise'] = $this->input->get("wise");
        $start_date = null;
        $end_date = null;
        if ($data['start_date'] != "") {
            $start_date = $data['start_date'];
        }

        if ($data['end_date'] != "") {
            $end_date = $data['end_date'];
        }
        $data['view_all_data'] = $this->report_model->panel_report($start_date, $end_date, $data['wise'], $data['branch']);
        $data['branch_list'] = $this->user_model->master_fun_get_tbl_val("branch_master", array('status' => 1), array("id", "asc"));
        $this->load->view('header', $data);
        $this->load->view('nav', $data);
        $this->load->view('panel_report', $data);
        $this->load->view('footer');
    }

    function creditors($cid) {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["cid"] = $cid;
        $data['start_date'] = $this->input->get("start_date");
        $data['end_date'] = $this->input->get("end_date");
        $start_date = null;
        $end_date = null;
        if ($data['start_date'] != "") {
            $start_date = $data['start_date'];
        }

        if ($data['end_date'] != "") {
            $end_date = $data['end_date'];
        }
        $data['view_all_data'] = $this->report_model->creditors_report($start_date, $end_date, $cid);
        $new_array = array();
        foreach ($data['view_all_data'] as $key) {
            $dta = array();
            if ($key["paid"] > 0) {
                $dta = $this->report_model->creditors_report_id($key["paid"]);
            }
            $key["paid_by"] = $dta;
            $new_array[] = $key;
        }
        $data['view_all_data'] = array();
        $data['view_all_data'] = $new_array;
        //echo "<pre>";print_R($new_array); die();
        $this->load->view('header', $data);
        $this->load->view('nav', $data);
        $this->load->view('creditors_report', $data);
        $this->load->view('footer');
    }

    function creditors_all() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $assign_branch = array();

        if ($data["login_data"]["type"] != 1 && $data["login_data"]["type"] != 2) {
            //echo "SELECT GROUP_CONCAT(branch_fk) AS bid FROM `user_branch` WHERE `status`='1' AND user_fk='" . $data["login_data"]["id"] . "' GROUP BY user_fk"; die();
            $uid = $this->report_model->get_val("SELECT GROUP_CONCAT(branch_fk) AS bid FROM `user_branch` WHERE `status`='1' AND user_fk='" . $data["login_data"]["id"] . "' GROUP BY user_fk");
            //echo $uid[0]["bid"]; die();
            $view_all_data = $this->report_model->creditors_list($uid[0]["bid"]);
        } else {

            $view_all_data = $this->report_model->creditors_list();
        }
        $data['view_all_data'] = array();
        foreach ($view_all_data as $key) {
            $dta = $this->report_model->creditors_report_count($key["id"]);
            $key["job_count"] = count($dta);
            $data['view_all_data'][] = $key;
        }
        //print_R($data['view_all_data']); die(); 
        $this->load->view('header', $data);
        $this->load->view('nav', $data);
        $this->load->view('creditors_shows', $data);
        $this->load->view('footer');
    }

    function add_credits() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $amount = $this->input->post("amount");
        $creditor = $this->input->post("creditor");
        $remark = $this->input->post("remarks");
        $job_fk = $this->input->post("job_fk");
        //print_R(array('job_id' => $job_fk)); die();
        $creditor_report = $this->user_model->master_fun_get_tbl_val("creditors_balance", array('job_id' => $job_fk), array("id", "asc"));
        $insert = $this->user_model->master_fun_insert("creditors_balance", array("remark" => $remark, "job_id" => $job_fk, "creditors_fk" => $creditor, "credit" => $amount, "created_by" => $data["login_data"]['id']));
        $this->report_model->master_fun_update("creditors_balance", array("id", $creditor_report[0]["id"]), array("paid" => $insert));
        echo $insert;
    }

    function test_report() {
        if (!is_loggedin()) {
            redirect('login');
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
        if ($data["login_data"]['type'] == 6 || $data["login_data"]['type'] == 5) {
            $user_branch = $this->user_model->master_fun_get_tbl_val("user_branch", array('status' => 1, "user_fk" => $data["login_data"]['id']), array("id", "asc"));
            $data['branch_list_se'] = $user_branch;
            $branch = array();
            foreach ($user_branch as $key1) {
                $branch[] = $key1["branch_fk"];
            }
            $data['branch_list_select'] = $branch;
        }
        $data['branch_list'] = $this->user_model->master_fun_get_tbl_val("branch_master", array('status' => 1), array("id", "asc"));
        $data['view_all_data'] = $this->report_model->test_report($start_date, $end_date, $data['branch'], $branch);
        $this->load->view('header', $data);
        $this->load->view('nav', $data);
        $this->load->view('test_report', $data);
        $this->load->view('footer');
    }

    function doctor_test_report() {
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
        $data['city'] = $this->input->get("city");
        $user_branch = $this->user_model->master_fun_get_tbl_val("user_branch", array('status' => 1, "user_fk" => $data["login_data"]['id']), array("id", "asc"));
        $branch = array();
        foreach ($user_branch as $key1) {
            $branch[] = $key1["branch_fk"];
        }
        $data['branchs'] = $branch;
        $start_date = null;
        $end_date = null;
        if ($data['start_date'] != "") {
            $start_date = $data['start_date'];
        }

        if ($data['end_date'] != "") {
            $end_date = $data['end_date'];
        }
        $data['city_list'] = $this->user_model->master_fun_get_tbl_val("test_cities", array('status' => 1), array("id", "asc"));
        $data['branch_list'] = $this->user_model->master_fun_get_tbl_val("branch_master", array('status' => 1), array("branch_name", "asc"));
        $data['collecting_amount_branch'] = $this->report_model->testdoctorReport($start_date, $end_date, $data['branch'], $data['branchs'], $data['city']);
        $this->load->view('header', $data);
        $this->load->view('nav', $data);
        $this->load->view('doctor_test_report', $data);
        $this->load->view('footer');
    }

    function doctor_test_export() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $start = $this->input->get("start_date");
        $end = $this->input->get("end_date");
        $branch = $this->input->get("branch");
        $date1 = explode("/", $start);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $end);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $delimiter = ",";
        $newline = "\r\n";
        $filename = "Doctor_test_report.csv";
        $qry = "SELECT 
            dm.id as `Doctor ID`,
            dm.full_name as `Doctor`,
            DATE_FORMAT(jm.date,'%e-%b-%y') as `Date`,
            jm.id as `R.ID`,
            jm.order_id as `Order ID`,
            cm.full_name as `Patient`,
            tm.test_name as `Test`,
            tmc.price as `Price`,
            Round(jm.price) as `Job Price`,
            Round((if(jm.`discount` != 'NULL',jm.`discount`,0) * if(jm.price != 'NULL',jm.price,0)) / 100) as `Discount`,
            IF(
    `jm`.`phlebo_added` != '',
    CONCAT(pm.`name`,'','(Phlebotomy)'),`am`.`name`
  ) AS `Added By`,
  b.branch_name as `Branch`
FROM
  job_master jm 
LEFT JOIN `job_master_receiv_amount` jmr 
    ON jm.id = jmr.`job_fk`
    join job_test_list_master jtl
    on jtl.job_fk = jm.id
    left join test_master tm
    on tm.id = jtl.test_fk
    left join test_master_city_price tmc
    on tmc.test_fk = tm.id
 LEFT JOIN `admin_master` am 
    ON jm.added_by = am.`id` 
    LEFT JOIN `phlebo_master` pm
    ON jm.phlebo_added = pm.id
    JOIN doctor_master dm
    ON dm.id = jm.doctor
    left join customer_master cm
    on cm.id = jm.cust_fk
  JOIN branch_master b 
    ON b.id = jm.branch_fk 
   join test_cities tc 
    on tc.id = b.city
 WHERE   jm.`status` != '0' ";
        $qry .= " AND jm.date >= '" . $start_date . "' AND jm.date <= '" . $end_date . "'";
        $qry .= " AND b.id  = '" . $branch . "'";
        $qry .= " group by dm.id,cm.id,tm.id order by dm.`id` ASC";
        $result = $this->db->query($qry);
        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
        force_download($filename, $data);
    }

    function job_received() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data['start_date'] = $this->input->get("start_date");
        $data['end_date'] = $this->input->get("end_date");
        $data['branch'] = $this->input->get("branch");
        $data['type'] = $this->input->get("type");
        $data['wise'] = $this->input->get("wise");
        $start_date = null;
        $end_date = null;
        if ($data['start_date'] != "") {
            $start_date = $data['start_date'];
        }

        if ($data['end_date'] != "") {
            $end_date = $data['end_date'];
        }
        if ($data["login_data"]['type'] == 6 || $data["login_data"]['type'] == 5) {
            $user_branch = $this->user_model->master_fun_get_tbl_val("user_branch", array('status' => 1, "user_fk" => $data["login_data"]['id']), array("id", "asc"));
            $data['branch_list_se'] = $user_branch;
            $branch = array();
            foreach ($user_branch as $key1) {
                $branch[] = $key1["branch_fk"];
            }
            $data['branch_list_select'] = $branch;
        }
        $data['branch_list'] = $this->user_model->master_fun_get_tbl_val("branch_master", array('status' => 1), array("id", "asc"));
        if ($start_date != '' || $end_date != '') {
            $data['view_all_data'] = $this->report_model->job_received_report($start_date, $end_date, $data['branch'], $branch);
        }
		//echo $this->db->last_query(); die();
        //echo "<pre>"; print_r($data['view_all_data']); die();
        $this->load->view('header', $data);
        $this->load->view('nav', $data);
        $this->load->view('job_received_report', $data);
        $this->load->view('footer');
    }

    function job_received_export() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $start = $this->input->get("start_date");
        $end = $this->input->get("end_date");
        $branch = $this->input->get("branch");
        $date1 = explode("/", $start);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $end);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $delimiter = ",";
        $newline = "\r\n";
        $filename = "Job_received_report.csv";
        $qry = "select
            bm.branch_name as branch,
            bm.id as bid,
              IF(`booking_info`.`family_member_fk`>0,`customer_family_master`.`name`,cm.full_name) AS patient,
            dm.full_name as doctor_name,
            jm.order_id as order_id,
            jm.id as jid,
            IF(
    `jm`.`phlebo_added` != '',
    CONCAT(cbp.`name`,'','(Phlebotomy)'),`cb`.`name`
  ) as added_name,
            jm.date as added_date,
            Round(jm.`price`) as gross_amt,
            Round((if(jm.`discount` != 'NULL',jm.`discount`,0) * if(jm.price != 'NULL',jm.price,0)) / 100) as discount,
            Round(if(jm.price != 'NULL',jm.price,0) - ((if(jm.`discount` != 'NULL',jm.`discount`,0) * if(jm.price != 'NULL',jm.price,0)) / 100)) as net_amt,
            Round(jr.amount) as received_amt,
            jr.payment_type as received_type,
            IF(
    `jr`.`phlebo_fk` != '',
    CONCAT(rcp.`name`,'','(Phlebotomy)'),`rc`.`name`
  ) as received_name,
            jr.createddate as received_date,
            Round(jm.payable_amount) as due_amt
                from job_master jm join branch_master bm on bm.id=jm.branch_fk left JOIN `booking_info` 
    ON `booking_info`.`id` = jm.`booking_info` 
    left JOIN `customer_family_master` 
    ON `customer_family_master`.`id`=`booking_info`.`family_member_fk` left join doctor_master dm on dm.id = jm.doctor left join admin_master cb on cb.id=jm.added_by left join phlebo_master cbp on cbp.id=jm.phlebo_added join customer_master cm on cm.id=jm.cust_fk left join job_master_receiv_amount jr on jr.job_fk=jm.id and jr.status='1' left join admin_master rc on rc.id=jr.added_by left join phlebo_master rcp on rcp.id=jr.phlebo_fk where jm.model_type=1 and jm.status != '0' and bm.status = '1' ";
        $qry .= " AND jm.date >= '" . $start_date . "' AND jm.date <= '" . $end_date . "'";
        if ($branch != "") {
            $qry .= " AND jm.branch_fk = '" . $branch . "' ";
        }
        $qry .= " group by jm.id,jr.added_by,jr.phlebo_fk order by bm.`id`,jm.id ASC";
        $result = $this->db->query($qry);
        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
        force_download($filename, $data);
    }

}

?>