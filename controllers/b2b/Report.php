<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Report extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('b2b/reportb2b_modal');

        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('pushserver');
        $this->load->library('email');
        $data["login_data"] = logindata();
        $this->load->helper('string');
        $this->app_track();
    }

    function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }

    function index() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->reportb2b_modal->getUser($data["login_data"]["id"]);
        $labs = $this->input->get('labs');
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $cquery = "";
        if ($labs != "" || $start_date != "" || $end_date != "") {

            $start_date = str_replace('/', '-', $start_date);
            $end_date = str_replace('/', '-', $end_date);

            $total_row = $this->reportb2b_modal->reportnumget($labs, $start_date, $end_date);
            $config = array();
            $config["base_url"] = base_url() . "b2b/report/index?labs=$labs&start_date=$start_date&end_date=$end_date";
            $config["total_rows"] = $total_row;
            $config["per_page"] = 50;
            $config["uri_segment"] = 4;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';


            $config['page_query_string'] = TRUE;
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->reportb2b_modal->reportget_result($config["per_page"], $page, $labs, $start_date, $end_date);
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        } else {

            $totalRows = $this->reportb2b_modal->reportnumget();
            $config = array();
            $config["base_url"] = base_url() . "b2b/Report/index";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 50;
            $config["uri_segment"] = 4;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';


            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
            $data["query"] = $this->reportb2b_modal->reportget_result($config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        }
        $data['lablist'] = $this->reportb2b_modal->master_fun_get_tbl_val("collect_from", array('status' => 1), array("id", "asc"));


        $this->load->view('b2b/header');
        $this->load->view('b2b/nav_logistic', $data);
        $this->load->view('b2b/reportresult_view', $data);
        $this->load->view('b2b/footer');
    }

    public function payment_report() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->reportb2b_modal->getUser($data["login_data"]["id"]);
        $labs = $this->input->get('labs');
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $cquery = "";
		
        if ($labs != "" || $start_date != "" || $end_date != "") {

            /*$start_date = str_replace('/', '-', $start_date);
            $end_date = str_replace('/', '-', $end_date);
            $total_row = $this->reportb2b_modal->payreportnumget($labs, $start_date, $end_date);
            $config = array();
            $config["base_url"] = base_url() . "b2b/report/payment_report?labs=$labs&start_date=$start_date&end_date=$end_date";
            $config["total_rows"] = $total_row;
            $config["per_page"] = 5000;
            $config["uri_segment"] = 4;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';


            $config['page_query_string'] = TRUE;
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->reportb2b_modal->paymentreport_get($config["per_page"], $page, $labs, $start_date, $end_date);
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page; */
            $data['query'] = $this->reportb2b_modal->get_job_report($labs, $start_date, $end_date);
          }
        $data['lablist'] = $this->reportb2b_modal->master_fun_get_tbl_val("collect_from", array('status' => 1), array("id", "asc"));
        $data['payreport'] = $this->reportb2b_modal->gettotalreport();
        $this->load->view('b2b/header');
        $this->load->view('b2b/nav_logistic', $data);
        $this->load->view('b2b/paymentreport_view', $data);
        $this->load->view('b2b/footer');
 }
function get_rpeort() {
		if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->reportb2b_modal->getUser($data["login_data"]["id"]);
        $labs = $this->input->get('labs');
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
		$labid =$this->input->get('labs');
		$activatedby=$this->input->get('activatedby');
        $cquery = "";
		if($labid != "" || $activatedby != ""){
			$searcharry=array('status' => 1);
			if($labid != ""){ $searcharry['id']=$labid; }
			if($activatedby != ""){ $searcharry['sales_fk']=$activatedby; }
			
		$data['lablist'] = $this->reportb2b_modal->master_fun_get_tbl_val("collect_from",$searcharry ,array("name", "asc")); }else{
			
        $data['lablist'] = $this->reportb2b_modal->master_fun_get_tbl_val("collect_from", array('status' => 1), array("name", "asc"));
		 }
        if ($labid != "" || $activatedby != "" || $start_date != "" || $end_date != "" ) {
			
            $data["final_report"] = array();
            foreach ($data['lablist'] as $key1){
				
                $total_row = $this->reportb2b_modal->get_job_report($key1["id"], $start_date, $end_date);
                $credit_list=$this->reportb2b_modal->master_fun_get_tbl_val("sample_credit", array('status' => 1, "lab_fk" => $key1["id"]), array("id", "desc"), array(5, 0));
                
                    $credited = 0;
                    $due = 0;
                    if ($credit_list[0]["total"] > 0) {
                        $credited = $credit_list[0]["total"];
                    } else {
                        $due = $credit_list[0]["total"];
                    }
                     
                $key1["credit"] = $credited;
                $key1["due"] = 0-$due;
                $key1["job_count"] = $total_row;
                $sales_details = $this->reportb2b_modal->get_val('SELECT 
  collect_from.id,
  collect_from.name, 
  collect_from.email ,
  CONCAT(sales_user_master.`first_name`," ",sales_user_master.`last_name`," (",sales_user_master.`email`," , ",sales_user_master.`mobile`," )")AS sales_name
FROM
  collect_from 
  LEFT JOIN `sales_user_master` ON `sales_user_master`.id=`collect_from`.`sales_fk`
WHERE collect_from.status = "1" and `collect_from`.id="'.$key1["id"].'"');
                $key1["activated_by"] = $sales_details[0]['sales_name'];
                $data["final_report"][] = $key1;
            }
            if($_REQUEST["debug"]==1){
            echo "<pre>";print_r($data["final_report"]); die();
            }
        }
		
        $data['payreport'] = $this->reportb2b_modal->gettotalreport();
		
		$data['lablist'] = $this->reportb2b_modal->master_fun_get_tbl_val("collect_from", array('status' => 1), array("id", "asc"));
		
		$data['activatedby'] = $this->reportb2b_modal->master_fun_get_tbl_val("sales_user_master",array('status' => 1), array("first_name","asc"));
		
		
        $this->load->view('b2b/header');
        $this->load->view('b2b/nav_logistic', $data);
        $this->load->view('b2b/labreport_view', $data);
        $this->load->view('b2b/footer');
    }
function labreport_export() {
	
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
		$labid =$this->input->get('labs');
		$activatedby=$this->input->get('activatedby');
		 if($start_date != "" || $end_date != "") {
			 
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $cquery = "";
        if($labid != "" || $activatedby != ""){
			$searcharry=array('status' => 1);
			if($labid != ""){ $searcharry['id']=$labid; }
			if($activatedby != ""){ $searcharry['sales_fk']=$activatedby; }
			
		$data['lablist'] = $this->reportb2b_modal->master_fun_get_tbl_val("collect_from",$searcharry ,array("name", "asc")); }else{
			
        $data['lablist'] = $this->reportb2b_modal->master_fun_get_tbl_val("collect_from", array('status' => 1), array("name", "asc"));
		 }
		$final_report = array();
            foreach ($data['lablist'] as $key1){ 
                $total_row = $this->reportb2b_modal->get_job_report($key1["id"],$start_date,$end_date);
                $credit_list = $this->reportb2b_modal->master_fun_get_tbl_val("sample_credit", array('status' => 1, "lab_fk" => $key1["id"]), array("id", "desc"), array(5, 0));
                
                    $credited = 0;
                    $due = 0;
                    if ($credit_list[0]["total"] > 0) {
                        $credited = $credit_list[0]["total"];
                    } else {
                        $due = $credit_list[0]["total"];
                    }
					
					 /* $credit_list= $this->reportb2b_modal->getlab_creditor($key1["id"]);
				
				$credited=0;
				if($credit_list->credit != ""){  $credited=$credit_list->credit; } */
                     
                $key1["credit"] = $credited;
                $key1["due"] = 0-$due;
                $key1["job_count"] = $total_row;
				$sales_details = $this->reportb2b_modal->get_val('SELECT 
  CONCAT(sales_user_master.`first_name`," ",sales_user_master.`last_name`," (",sales_user_master.`email`," , ",sales_user_master.`mobile`," )")AS sales_name
FROM
  collect_from 
  LEFT JOIN `sales_user_master` ON `sales_user_master`.id=`collect_from`.`sales_fk`
WHERE collect_from.status = "1" and `collect_from`.id="'.$key1["id"].'"');
                $key1["activated_by"] = $sales_details[0]['sales_name'];
                $final_report[] = $key1;
            }
			
		header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"labReport.csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("No","labName","ActivatedBy","TotalSample","ReceivedAmount", "DueAmount"));
			$i=0;
		 foreach ($final_report as $val) {
			 $i++;
		 fputcsv($handle, array($i,$val['name'],$val['activated_by'],count($val['job_count']),$val['credit'],$val['due']));
		 }
		 fclose($handle);
        exit;
		 
		 }else{ show_404(); }
    }
 public function payment_reportexport(){
        if (!is_loggedin()) {
            redirect('login');
        }
       
        $labs = $this->input->get('labs');
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $cquery = "";
        if ($labs != "" || $start_date != "" || $end_date != "") {
			
			header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"labReport.csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("No","PatientName","labName","Price","Date"));
		$query = $this->reportb2b_modal->get_job_report($labs, $start_date, $end_date);
			$i=0;
			$price=0;
		 foreach ($query as $val) {
			 $i++;
			 
		 fputcsv($handle, array($i,ucfirst($val["customer_name"]),ucfirst($val["lab_name"]),$val["price"],$val["date"]));
		 }
		 fclose($handle);
        exit;

		  
		  }else{ show_404(); }
        
    }
public function print_report(){
        if (!is_loggedin()) {
            redirect('login');
        }
       
        $labs = $this->input->get('labs');
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $cquery = "";
		$data['query']=array();
        if ($labs != "" || $start_date != "" || $end_date != "") {
		
			$data['start_date']=$start_date;
			$data['end_date']=$end_date;
		$data['query']=$this->reportb2b_modal->getclient_detils($labs);	
		$data['query1']=$this->reportb2b_modal->get_job_report($labs, $start_date, $end_date);		
		$pdfFilePath=FCPATH."/upload/b2binvoice/invoice.pdf";	
		$html = $this->load->view('b2b/invoice_pdf_view',$data,true); 
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
                30, // margin top
                40, // margin bottom
                2, // margin header
                2); // margin footer
        $pdf->WriteHTML($html);
		$pdf->debug = true;
        $pdf->allow_output_buffering = TRUE;
		if (file_exists($pdfFilePath) == true) {
            $this->load->helper('file');
            unlink($path);
        }
        $pdf->Output($pdfFilePath, 'F');	
		
		  }else{ show_404(); 
		  
		  
		  }
        
    }	

}
