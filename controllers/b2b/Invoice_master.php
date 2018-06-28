<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Invoice_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('b2b/invoice_model');
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
    function generate_invoice() {
		if (!is_loggedin()) {
            redirect('login');
        }
		$data['success'] = $this->session->flashdata("success");
        $data["login_data"] = logindata();
        $data["user"] = $this->invoice_model->getUser($data["login_data"]["id"]);
        $data['date'] = $this->input->get('date');
        $data['from'] = $this->input->get('from');
		$data['todate'] = $this->input->get('todate');
		 
        $cquery = "";
        if($data['date'] != '' || $data['from'] != '' || $data['todate']){
			if ($data['date'] != "") {
                $data['date1'] = explode('/', $data['date']);
                $data['date2'] = $data['date1'][2] . "-" . $data['date1'][1] . "-" . $data['date1'][0];
            } else {
                $data['date2'] = "";
            }
			if ($data['todate'] != "") {
                $data['todate1'] = explode('/', $data['todate']);
                $data['todate2'] = $data['todate1'][2] . "-" . $data['todate1'][1] . "-" . $data['todate1'][0];
            } else {
                $data['todate2'] = "";
            }
			
			$data['query'] = $this->invoice_model->sample_listinvoce($data['date2'],$data['todate2'],$data['from']);
			
        } else {
			
			$data['query'] =array();
        }
        
        $data["laball"]=$this->invoice_model->master_fun_get_tbl_val("collect_from",array( 'status' => '1'),array("name", "asc"));
		 
        $this->load->view('b2b/header');
        $this->load->view('b2b/nav_logistic', $data);
        $this->load->view('b2b/invoice_list_view', $data);
        $this->load->view('b2b/footer');
    }
function invoice_pdf(){
        if (!is_loggedin()) {
            redirect('login');
        }
		
        $data["login_data"] = logindata();
        $data['date'] = $this->input->get('date');
        $data['from'] = $this->input->get('from');
		$data['todate'] = $this->input->get('todate');
		 
        $cquery = "";
        if($data['date'] != '' || $data['from'] != '' || $data['todate']){
			
			if ($data['date'] != "") {
                $data['date1'] = explode('/', $data['date']);
                $data['date2'] = $data['date1'][2] . "-" . $data['date1'][1] . "-" . $data['date1'][0];
            } else {
                $data['date2'] = "";
            }
			if ($data['todate'] != "") {
                $data['todate1'] = explode('/', $data['todate']);
                $data['todate2'] = $data['todate1'][2] . "-" . $data['todate1'][1] . "-" . $data['todate1'][0];
            } else {
                $data['todate2'] = "";
            }
			
		$data['start_date']= $data['from'];
		$data['end_date']=$data['todate'];
		$data['query']=$this->invoice_model->getclient_detils($data['from']);	
		$data['query1']=$this->invoice_model->sample_listinvoce($data['date2'],$data['todate2'],$data['from']);	
		$totalamount = 0;
		foreach($data['query1'] as $key){ 
		$totalamount +=$key['amt'];
		}
		$data['totalamount']=$totalamount;
		//$data['amountstaring']=$this->convert_number_to_words($totalamount);
		$data['amountstaring']="";
		$data['totalamount']=$totalamount;
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
                5, // margin top
                5, // margin bottom
                2, // margin header
                2); // margin footer
		$pdf->SetHTMLFooter('<table class="tbl_full" style="margin-bottom:20px; text-align:right;">
					<tr>
						<td><b>AIRRMED PATHOLOGY PVT.LTD.</b></td>
					</tr>
				</table>

<div style="height:40px;"><p class="rslt_p_brdr">Result relate only to the sample as received</p>
</div>');		
        $pdf->WriteHTML($html);
		$pdf->debug = true;
        $pdf->allow_output_buffering = TRUE;
		if (file_exists($pdfFilePath) == true) {
            $this->load->helper('file');
            unlink($path);
        }
        $pdf->Output($pdfFilePath, 'F');
	
	$base_url=base_url()."upload/b2binvoice/invoice.pdf";
$filename=FCPATH."/upload/b2binvoice/invoice.pdf";	
header('Pragma: public');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Cache-Control: private', false); // required for certain browsers 
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="'. basename($filename) . '";');
header('Content-Transfer-Encoding: binary');
header('Content-Length: ' . filesize($filename));
readfile($filename);
exit;
		
} else {
			show_404();
        }
    }
public function convert_number_to_words($number) {

    $hyphen      = '-';
    $conjunction = ' and ';
    $separator   = ', ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $dictionary  = array(
        0                   => 'zero',
        1                   => 'one',
        2                   => 'two',
        3                   => 'three',
        4                   => 'four',
        5                   => 'five',
        6                   => 'six',
        7                   => 'seven',
        8                   => 'eight',
        9                   => 'nine',
        10                  => 'ten',
        11                  => 'eleven',
        12                  => 'twelve',
        13                  => 'thirteen',
        14                  => 'fourteen',
        15                  => 'fifteen',
        16                  => 'sixteen',
        17                  => 'seventeen',
        18                  => 'eighteen',
        19                  => 'nineteen',
        20                  => 'twenty',
        30                  => 'thirty',
        40                  => 'fourty',
        50                  => 'fifty',
        60                  => 'sixty',
        70                  => 'seventy',
        80                  => 'eighty',
        90                  => 'ninety',
        100                 => 'hundred',
        1000                => 'thousand',
        1000000             => 'million',
        1000000000          => 'billion',
        1000000000000       => 'trillion',
        1000000000000000    => 'quadrillion',
        1000000000000000000 => 'quintillion'
    );

    if (!is_numeric($number)) {
        return false;
    }

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }

    return $string;
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
function invoice_csv(){
        if (!is_loggedin()) {
            redirect('login');
        }
		
        $data["login_data"] = logindata();
        $data['date'] = $this->input->get('date');
        $data['from'] = $this->input->get('from');
		$data['todate'] = $this->input->get('todate');
		 
        $cquery = "";
        if($data['date'] != '' || $data['from'] != '' || $data['todate']){
			if ($data['date'] != "") {
                $data['date1'] = explode('/', $data['date']);
                $data['date2'] = $data['date1'][2] . "-" . $data['date1'][1] . "-" . $data['date1'][0];
            } else {
                $data['date2'] = "";
            }
			if ($data['todate'] != "") {
                $data['todate1'] = explode('/', $data['todate']);
                $data['todate2'] = $data['todate1'][2] . "-" . $data['todate1'][1] . "-" . $data['todate1'][0];
            } else {
                $data['todate2'] = "";
            }
			
		header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"invoice.csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
		fputcsv($handle, array("No","Reg.Date","LabId","Patientsname","Investigation","Amount"));
		
	$query=$this->invoice_model->sample_listinvoce($data['date2'],$data['todate2'],$data['from']);
			$i=0;
    foreach($query as $row){
		$i++;
		fputcsv($handle,array($i,$row["regdate"],$row['labid'],ucwords($row['patientname']),$row['testname'],$row['amt'])); 
	}
	fclose($handle);
    exit;		
	 
} else {
			
			show_404();
        }
        
        
    }
function sample_export() {
        if (!is_loggedin()) {
            redirect('login');
        }
		

        /* $this->output->enable_profiler(TRUE); */
        $data["login_data"] = logindata();
        $data["user"] = $this->Logistic_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $search = $this->input->get('search');
        $data['name'] = $this->input->get('name');
        $data['barcode'] = $this->input->get('barcode');
        $data['date'] = $this->input->get('date');
        $data['from'] = $this->input->get('from');
		 
		 
		 $data['patientsname']=$this->input->get('patientsname');
		 $data['salesperson']=$this->input->get('salesperson');
		 $data['sendto'] = $this->input->get('sendto');
		 $data['todate'] = $this->input->get('todate');
		 $data['city'] = $this->input->get('city');
		 $data['status'] = $this->input->get('status');
		 

		 
        $cquery = "";
        if ($data['name'] != "" || $data['barcode'] != '' || $data['date'] != '' || $data['from'] != '' || $data['patientsname'] || $data['salesperson'] || $data['sendto'] || $data['todate'] || $data['city'] || $data['status']) {
			
		

            if ($data['date'] != "") {
                $data['date1'] = explode('/', $data['date']);
                $data['date2'] = $data['date1'][2] . "-" . $data['date1'][1] . "-" . $data['date1'][0];
            } else {
                $data['date2'] = "";
            }
			if ($data['todate'] != "") {
                $data['todate1'] = explode('/', $data['todate']);
                $data['todate2'] = $data['todate1'][2] . "-" . $data['todate1'][1] . "-" . $data['todate1'][0];
            } else {
                $data['todate2'] = "";
            }

            $query=$this->Logistic_model->sample_list_num($data["login_data"], $data['name'], $data['barcode'], $data['date2'],$data['todate2'],$data['from'],$data['patientsname'],$data['salesperson'],$data['sendto'],$data['city'],$data['status']);
           
		   
        } else {
			
		
		
      $query = $this->Logistic_model->sample_list($data["login_data"]);
   }
		header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"SampleReport.csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
		fputcsv($handle, array("No","Patientid","PatientName","Barcode","LogisticName","Scan Date","CollectFrom","salesperson","Testname","Price"));
   $i=0;
    foreach($query as $row){
	
		$i++;
		$job_test = $this->Logistic_model->master_fun_get_tbl_val("sample_job_test", array("job_fk" => $row["id"],'status' => 1),array("id", "asc"));
				
                $tst_name= array();
				 $tkey["info"] = array();
                 foreach ($job_test as $tkey) {
					 $test_info = $this->Logistic_model->get_val("SELECT  sample_test_master.test_name FROM `sample_test_master` WHERE `sample_test_master`.`status` = '1'   AND `sample_test_master`.`id` = '".$tkey["test_fk"]."'");
                    $tst_name[] =$test_info[0]['test_name'];
                }
				
				$testname=implode(",",$tst_name);
				
		fputcsv($handle,array($i,ucfirst($row["id"]),ucfirst($row["customer_name"]),$row["barcode"],$row["name"],$row["scan_date"],$row["c_name"],$row["salesname"],$testname,$row["payable_amount"])); 
	}
 
	fclose($handle);
        exit;
    
	}	
}
