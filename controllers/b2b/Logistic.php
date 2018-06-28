<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Logistic extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('b2b/Logistic_model');
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

    function dashboard() {
        if (!is_loggedin()) {
            redirect('login');
        }
		
        $data["login_data"] = logindata();
        $data["user"] = $this->Logistic_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
		$todaay=date("Y-m-d");
		$revanu=$this->Logistic_model->todayrevanu($todaay);
		if($revanu != ""){ $data['revanu']=$revanu; }else{ $revanu="0"; }
		
		$data['totalsample']=$this->Logistic_model->master_num_rows('logistic_log',array("DATE_FORMAT(scan_date,'%Y-%m-%d')"=>$todaay,"status"=>'1'));
		$totaltest=$this->Logistic_model->todaytotaltest($todaay);
		if($totaltest != ""){ $data['totaltest']=$totaltest; }else{ $data['totaltest']='0'; }
        
		$this->load->view('b2b/header');
        $this->load->view('b2b/nav_logistic', $data);
        $this->load->view('b2b/logistic_dashboard', $data);
        $this->load->view('b2b/footer');
    }

    /* Sample list start */

    function sample_list() {
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
			
           $total_row = $this->Logistic_model->samplelist_numrow($data["login_data"], $data['name'], $data['barcode'], $data['date2'],$data['todate2'],$data['from'],$data['patientsname'],$data['salesperson'],$data['sendto'],$data['city'],$data['status']);
		  
		    $config = array();
            $config["base_url"] = base_url() . "b2b/Logistic/sample_list?search=$search&name=".$data['name']."&barcode=" . $data['barcode'] . "&date=" . $data['date']."&from=" . $data['from']."&patientsname=".$data['patientsname']."&salesperson=".$data['salesperson']."&sendto=".$data['sendto']."&todate=".$data['todate']."&city=".$data['city']."&status=".$data['status'];
            $config["total_rows"] = $total_row;
            $config["per_page"] = 1;
            $config["uri_segment"] = 4;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';


            $config['page_query_string'] = TRUE;
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->Logistic_model->sample_list_num($data["login_data"], $data['name'], $data['barcode'], $data['date2'],$data['todate2'],$data['from'],$data['patientsname'],$data['salesperson'],$data['sendto'],$data['city'],$data['status'],$config["per_page"], $page);
			
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        
		
		} else {
            $totalRows = $this->Logistic_model->sample_list($data["login_data"]);
            $config = array();
            $config["base_url"] = base_url() . "b2b/Logistic/sample_list/";
            $config["total_rows"] = count($totalRows);
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
            $data["query"] = $this->Logistic_model->sample_list($data["login_data"], $config["per_page"], $page);
//echo  $this->db->last_query(); die();
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        }
        $cnt = 0;
        foreach ($data["query"] as $key) {

            $data["query"][$cnt]["job_details"] = $this->Logistic_model->get_val("SELECT `sample_report_master`.`original` FROM `sample_job_master` left JOIN `sample_report_master` ON `sample_job_master`.`id`=`sample_report_master`.`job_fk` WHERE `sample_job_master`.`status`!='0' AND `sample_job_master`.`barcode_fk`='" . $key["id"] . "'");

            /* $data["query"][$cnt]["desti_lab1"] = $this->Logistic_model->get_val("SELECT * FROM `admin_master` where id='" . $key["desti_lab"] . "'"); */
            //$data["query"][$cnt]["report_details"] = $this->job_model->master_fun_get_tbl_val("sample_report_master", array('status' => "1", "job_fk" => $this->uri->segment(3)), array("id", "asc"));
            $cnt++;
        }
        $data["desti_lab"] = $this->Logistic_model->master_fun_get_tbl_val("admin_master", array("type" => "4", 'status' => 1), array("id", "asc"));
		 $data["salesall"] = $this->Logistic_model->master_fun_get_tbl_val("sales_user_master", array('status' => 1), array("id", "asc"));

        $url = "http://" . $_SERVER['HTTP_HOST'] . ":" . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
        $this->session->set_userdata("test_master_r", $url);
        $data['citys'] = $this->Logistic_model->master_fun_get_tbl_val("test_cities", array("status" => '1'), array("name", "asc"));
		$data["laball"]=$this->Logistic_model->master_fun_get_tbl_val("collect_from",array( 'status' => '1'),array("name", "asc"));
        $this->load->view('b2b/header');
        $this->load->view('b2b/nav_logistic', $data);
        $this->load->view('b2b/sample_list', $data);
        $this->load->view('b2b/footer');
    }

    function sample_delete($id) {
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();

        $update_data = array("status" => "0", "deleted_by" => $data["login_data"]["id"]);
        $update = $this->Logistic_model->master_fun_update("logistic_log", array("id" => $id), $update_data);

        $getjobsid = $this->Logistic_model->getjobsid($id);
        if ($getjobsid != "") {

            $payable = $getjobsid->payable_amount;
            $crditdetis = $this->Logistic_model->creditget_last($getjobsid->id);


            if ($crditdetis != "") {

                $total = ($crditdetis->total + $payable);
                $labid = $crditdetis->lab_fk;
            } else {
                $total = (0 + $payable);
                $labid = "";
            }
            /* echo "<pre>";
              print_r(array("lab_fk"=>$labid,"job_fk" =>$getjobsid->id,"credit" => $payable,"transaction"=>'Credited',"note"=>'delete jobs',"total" => $total, "created_date" => date("Y-m-d H:i:s")));
              die(); */

            $this->Logistic_model->master_fun_insert("sample_credit", array("lab_fk" => $labid, "job_fk" => $getjobsid->id, "credit" => $payable, "transaction" => 'Credited', "note" => 'delete jobs', "total" => $total, "created_date" => date("Y-m-d H:i:s")));
        }


        if ($update) {
            $this->session->set_flashdata("success", array("Record successfully deleted."));
        }
        redirect("b2b/Logistic/sample_list");
    }
function details() {
		
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->library("util");
        $util = new Util;
        $this->load->helper("Email");
        $email_cnt = new Email;
        $this->load->model('job_model');
        $data["login_data"] = logindata();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('abc', 'abc', 'trim|required');

        if ($this->form_validation->run() != FALSE) {
			
            $customer_name = $this->input->post("customer_name");
            $customer_mobile = $this->input->post("customer_mobile");
            $customer_email = $this->input->post("customer_email");
            if ($customer_email == '') {
                $customer_email = 'noreply@airmedlabs.com';
            }
            $customer_gender = $this->input->post("customer_gender");
            $dob = $this->input->post("dob");
            $address = $this->input->post("address");
            $note = $this->input->post("note");
            $discount = $this->input->post("discount");
            $payable = $this->input->post("payable");
            $test= $this->input->post("test");
            $referby = $this->input->post("referby");
            $total_amount = $this->input->post("total_amount");
            $order_id = $this->get_job_id();
            $date = date('Y-m-d H:i:s');
			$jobid=$this->uri->segment(4);
			$logdetils = $this->Logistic_model->fetchdatarow('collect_from','logistic_log',array("id"=>$jobid,"status"=>'1'));
			$lab_id=$logdetils->collect_from;
			
            $data = array(
                "barcode_fk" => $this->uri->segment(4),
                "customer_name" => $customer_name,
                "customer_mobile" => $customer_mobile,
                "customer_email" => $customer_email,
                "customer_gender" => $customer_gender,
                "customer_dob" => $dob,
                "customer_address" => $address,
                "doctor" => $referby,
                "price" => $total_amount,
                "status" => '1',
                "discount" => $discount,
                "payable_amount" => $payable,
                "added_by" => $data["login_data"]["id"],
                "note" => $note
            );
            $check_barcode = $this->job_model->master_fun_get_tbl_val("sample_job_master", array("barcode_fk" => $this->uri->segment(4), "status" => 1), array("id", "asc"));
            if (count($check_barcode) == 0) {
                $insert = $this->job_model->master_fun_insert("sample_job_master", $data);
            } else {
                $insert = $check_barcode[0]["id"];
                $this->Logistic_model->master_fun_update("sample_job_master", array("id" => $insert), $data);
                $this->Logistic_model->master_fun_update("sample_job_test", array("job_fk" => $insert), array("status" => "0"));
                $this->Logistic_model->master_fun_update("sample_book_package", array("job_fk" => $insert), array("status" => "0"));
            }
		$labdetils=$this->Logistic_model->fetchdatarow('test_discount,city','collect_from',array("id"=>$lab_id,"status"=>1));
	   $city=$labdetils->city;
		
            foreach ($test as $key) {
                $tn = explode("-", $key);
                if ($tn[0] == 't') {
					
                    /* $result = $this->job_model->get_val("SELECT `sample_test_master`.`id`,`sample_test_master`.`test_name`,`sample_test_master`.`price`,`sample_test_master`.`status` FROM `sample_test_master` WHERE `sample_test_master`.`status`='1' AND `sample_test_master`.`id`='" . $tn[1] . "'");
                      $this->job_model->master_fun_insert("sample_job_test", array('job_fk' => $insert, "test_fk" => $tn[1], "price" => $result[0]["price"])); */

                    $result = $this->job_model->get_val("select p.price,(SELECT price_special FROM b2b_testspecial_price s  WHERE  s.test_fk=t.id AND s.status='1' AND s.lab_id='$lab_id') AS specialprice from test_master t LEFT JOIN test_master_city_price p ON t.id = p.test_fk  where t.status='1' AND p.city_fk='$city' and t.id='".$tn[1]."' GROUP BY t.id");
				   
				   if($result[0]['specialprice'] != ""){
				   $price1 =$result[0]['specialprice'];  
				   }else{ $discount1=$labdetils->test_discount; 
							$discountprice=($result[0]['price']*$discount1/100);
							$price1 =$result[0]['price']-$discountprice;
						}
						
                    $this->job_model->master_fun_insert("sample_job_test", array('job_fk' => $insert, "test_fk" => $tn[1], "price" => $price1));
                }
                /* if ($tn[0] == 'p') {
                    $this->job_model->master_fun_insert("sample_book_package", array("cust_fk" => $uid, "package_fk" => $tn[1], 'job_fk' => $insert, "status" => "1", "type" => "2"));
                } */
            }
			
            /* $crditdetis = $this->job_model->creditget_last($lab_id);
            if ($crditdetis != "") {
                $total = ($crditdetis->total - $payable);
            } else {
				$total = (0 - $payable);
            }
            $this->job_model->master_fun_insert("sample_credit", array("lab_fk" => $lab_id, "job_fk" => $insert, "debit" => $payable, "total" => $total, "created_date" => date("Y-m-d H:i:s"))); */
			
			
			$this->session->set_flashdata("success", array("Test successfully Booked."));
            redirect("b2b/Logistic/details/" . $this->uri->segment(4));
			
        } else {
			
			
            $data["id"] = $this->uri->segment(4);
            $data['barcode_detail']=$this->job_model->get_val("SELECT `logistic_log`.*,`phlebo_master`.`name`,`phlebo_master`.`mobile`,`collect_from`.`name` AS `c_name` FROM `logistic_log` 
left JOIN `phlebo_master` ON `logistic_log`.`phlebo_fk`=`phlebo_master`.`id` 
left JOIN `collect_from` ON `logistic_log`.`collect_from`=`collect_from`.`id`
left join sample_job_master on sample_job_master.barcode_fk = logistic_log.id
WHERE  `logistic_log`.`status`='1' and `logistic_log`.`id`='" . $data["id"] . "'");


$labid=$data['barcode_detail'][0]['collect_from'];
$labdetils=$this->Logistic_model->fetchdatarow('test_discount,city','collect_from',array("id"=>$labid,"status"=>1));
$city=$labdetils->city;
			
            $data['job_details'] = $this->job_model->master_fun_get_tbl_val("sample_job_master", array("barcode_fk" => $this->uri->segment(4), 'status' => 1), array("id", "asc"));
            $age = $util->get_age($data['job_details'][0]["customer_dob"]);

            if ($age[0] != 0) {
                $data['job_details'][0]["age"] = $age[0];
                $data['job_details'][0]["age_type"] = 'Y';
            }
            if ($age[0] == 0 && $age[1] != 0) {
                $data['job_details'][0]["age"] = $age[1];
                $data['job_details'][0]["age_type"] = 'M';
            }
            if ($age[0] == 0 && $age[1] == 0 && $age[2] != 0) {
                $data['job_details'][0]["age"] = $age[2];
                $data['job_details'][0]["age_type"] = 'D';
            }
            if ($age[0] == 0 && $age[1] == 0 && $age[2] == 0) {
                $data['job_details'][0]["age"] = '0';
                $data['job_details'][0]["age_type"] = 'D';
            }
            $cnt = 0;
            foreach ($data['job_details'] as $key) {
                $job_test = $this->job_model->master_fun_get_tbl_val("sample_job_test", array("job_fk" => $key["id"], 'status' => 1), array("id", "asc"));
				
                $tst_name = array();
				 $tkey["info"] = array();
                 foreach ($job_test as $tkey) {
					 
					  $test_info = $this->job_model->get_val("select t.test_name,p.price,(SELECT price_special FROM b2b_testspecial_price s  WHERE  s.test_fk=t.id AND s.status='1' AND s.lab_id='$labid') AS specialprice from test_master t inner JOIN test_master_city_price p ON t.id = p.test_fk  AND p.city_fk='$city' where t.status='1'  and t.id='".$tkey["test_fk"]."' GROUP BY t.id");
					  
                    $tkey["info"] =$test_info;
                    $tst_name[] = $tkey;
                } 
                $data['job_details'][0]["test_list"] = $tst_name;
                $job_packages = $this->job_model->master_fun_get_tbl_val("sample_book_package", array("job_fk" => $key["id"], 'status' => 1), array("id", "asc"));
                $data['job_details'][0]["package_list"] = $job_packages;
                $cnt++;
            }
            $data['success'] = $this->session->userdata("success");
            if ($this->session->userdata("error") != '') {
                $data["error"] = $this->session->userdata("error");
                $this->session->unset_userdata("error");
            }
			/* echo "<pre>";print_r($data['job_details'][0]); 
			die(); */
            $data['jobspdf'] = $this->job_model->master_fun_get_tbl_val("b2b_jobspdf", array("job_fk" => $data["id"], 'status' => 1), array("id", "asc"));
            $data['jobsdocks'] = $this->job_model->master_fun_get_tbl_val("documentall", array("jobsid" => $data["id"], 'status' => 1, "type" => '1'), array("id", "asc"));
			$data['labdetils']=$labdetils;
		
            $this->load->view('b2b/header');
            $this->load->view('b2b/nav_logistic', $data);
            $this->load->view('b2b/sample_register', $data);
            $this->load->view('b2b/footer');
        }
 }
public function pdfapprove($id = null){
	    if (!is_loggedin()) {
            redirect('login');
        }
        $login_data = logindata();

        if ($login_data["type"] == 3) {
            if ($id != "") {

                $update = $this->Logistic_model->master_fun_update('b2b_jobspdf', array("job_fk" => $id, "status" => '1'), array("approve" => '1'));

                if ($update) {
                    $this->Logistic_model->master_fun_update("logistic_log", array("id" => $id), array("jobsstatus" => '1'));
                    $this->session->set_flashdata("success", array("Report successfully Approved."));
                }

                redirect("b2b/Logistic/details/" . $id);
            } else {

                show_404();
            }
        } else {
            show_404();
        }
    }

    function sample_add() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->model('job_model');
        $data["login_data"] = logindata();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('abc', 'abc', 'trim|required');
		if ($this->form_validation->run() != FALSE) {
			
			$barcode = $this->input->post("barcode");
            $lab_id = $this->input->post("lab_id");
            $logistic_id = $this->input->post("logistic_id");
            $customer_name = $this->input->post("customer_name");
            $customer_mobile = $this->input->post("customer_mobile");
            $customer_email = $this->input->post("customer_email");
            if ($customer_email == '') {
                $customer_email = 'noreply@airmedlabs.com';
            }
            $customer_gender = $this->input->post("customer_gender");
            $dob = $this->input->post("dob");
            $address = $this->input->post("address");
            $note = $this->input->post("note");
            $discount = $this->input->post("discount");
            $payable = $this->input->post("payable");
            $test = $this->input->post("test");
            $referby = $this->input->post("referby");
            $total_amount = $this->input->post("total_amount");
            $order_id = $this->get_job_id();
            $date = date('Y-m-d H:i:s');
            $files = $_FILES;
            $this->load->library('upload');
            $config['allowed_types'] = 'jpg|gif|png|jpeg';
            if ($files['upload_pic']['name'] != "") {
                $config['upload_path'] = './upload/barcode/';
                $config['file_name'] = time() . $files['upload_pic']['name'];
                $this->upload->initialize($config);
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0755, TRUE);
                }
                if (!$this->upload->do_upload('upload_pic')) {
                    $error = $this->upload->display_errors();
                    $error = str_replace("<p>", "", $error);
                    $error = str_replace("</p>", "", $error);
                    $this->session->set_flashdata("error", $error);
                    redirect("b2b/logistic/sample_add", "refresh");
                } else {
                    $doc_data = $this->upload->data();
                    $photo = $doc_data['file_name'];
                }
            }
            $c_data = array(
                "phlebo_fk" => $logistic_id,
                "barcode" => $barcode,
                "collect_from" => $lab_id,
                "status" => '1',
                "pic" => $photo,
                "created_by" => $data["login_data"]["id"],
                "createddate" => date('Y-m-d H:i:s'),
                "scan_date" => date('Y-m-d H:i:s')
            );
            $barcd = $this->job_model->master_fun_insert("logistic_log", $c_data);
            $data = array(
                "barcode_fk" => $barcd,
                "order_id" => $order_id,
                "customer_name" => $customer_name,
                "customer_mobile" => $customer_mobile,
                "customer_email" => $customer_email,
                "customer_gender" => $customer_gender,
                "customer_dob" => $dob,
                "customer_address" => $address,
                "doctor" => $referby,
                "price" => $total_amount,
                "status" => '1',
                "discount" => $discount,
                "payable_amount" => $payable,
                "added_by" => $data["login_data"]["id"],
                "note" => $note,
                "date" => $date
            );

            $insert = $this->job_model->master_fun_insert("sample_job_master", $data);
			
			$labdetils=$this->Logistic_model->fetchdatarow('test_discount,city','collect_from',array("id"=>$lab_id,"status"=>1));
			$city=$labdetils->city;
			
            foreach ($test as $key) {
                $tn = explode("-", $key);

                if ($tn[0] == 't') {

                    /* $result = $this->job_model->get_val("SELECT `sample_test_master`.`id`,`sample_test_master`.`test_name`,`sample_test_master`.`price`,`sample_test_master`.`status` FROM `sample_test_master` WHERE `sample_test_master`.`status`='1' AND `sample_test_master`.`id`='" . $tn[1] . "'"); 
                    $result = $this->job_model->get_val("SELECT price,b2b_price,test_fk FROM sample_test_city_price where status='1' and id='" . $tn[1] . "';");
                    if ($result[0]["b2b_price"] != "") {
                        $price1 = $result[0]["b2b_price"];
                    } else {
                        $price1 = $result[0]["price"];
                    }
					
					*/
					$result = $this->job_model->get_val("select p.price,(SELECT price_special FROM b2b_testspecial_price s  WHERE  s.test_fk=t.id AND s.status='1' AND s.lab_id='$lab_id') AS specialprice from test_master t LEFT JOIN test_master_city_price p ON t.id = p.test_fk  where t.status='1' AND p.city_fk='$city' and t.id='".$tn[1]."' GROUP BY t.id");
				   
				   if($result[0]['specialprice'] != ""){ 
				   $price1 =$result[0]['specialprice'];  
				   }else{ $discount1=$labdetils->test_discount; 
							$discountprice=($result[0]['price']*$discount1/100);
							$price1 =$result[0]['price']-$discountprice;
						}
					$this->job_model->master_fun_insert("sample_job_test", array('job_fk' => $insert, "test_fk" => $tn[1], "price" => $price1));
                
				}
                /* if ($tn[0] == 'p') {
                    $this->job_model->master_fun_insert("sample_book_package", array("cust_fk" => $uid, "package_fk" => $tn[1], 'job_fk' => $insert, "status" => "1", "type" => "2"));
                } */
            }
            $crditdetis = $this->job_model->creditget_last($lab_id);
            if ($crditdetis != "") {
                $total = ($crditdetis->total - $payable);
            } else {
				$total = (0 - $payable);
            }
            $this->job_model->master_fun_insert("sample_credit", array("lab_fk" => $lab_id, "job_fk" => $insert, "debit" => $payable, "total" => $total, "created_date" => date("Y-m-d H:i:s")));
            $this->session->set_flashdata("success", array("Test successfully Booked."));
            redirect("b2b/Logistic/sample_list");
        } else {

            $data['lab_list'] = $this->job_model->master_fun_get_tbl_val("collect_from", array('status' => 1), array("id", "asc"));
            $data['phlebo_list'] = $this->job_model->master_fun_get_tbl_val("phlebo_master", array('status' => 1), array("id", "asc"));
            $this->load->view('b2b/header');
            $this->load->view('b2b/nav_logistic', $data);
            $this->load->view('b2b/sample_add', $data);
            $this->load->view('b2b/footer');
			
        }
    }
function lab_list() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $data["user"] = $this->Logistic_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $state_serch = $this->input->get('state_search');
        $email = $this->input->get('email');
        $data['state_search'] = $state_serch;
        $data["email"] = $email;
        if ($state_serch != "" || $data["email"] != "") {
            $total_row = $this->Logistic_model->lab_num_rows($state_serch, $data["email"]);
            $config = array();
            $get = $_GET;
            unset($get['offset']);
            $config["base_url"] = base_url() . "b2b/logistic/lab_list?state_search=$state_serch&email=$email";
            $config["total_rows"] = $total_row;
            $config["per_page"] = 100;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';


            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->Logistic_model->lab_data($state_serch, $data["email"], $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        } else {
            $total_row = $this->Logistic_model->lab_num_rows();
            $config["base_url"] = base_url() . "b2b/logistic/lab_list";
            $config["total_rows"] = $total_row;
            $config["per_page"] = 100;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';

            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->Logistic_model->srch_lab_list($config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        }
        $data["page"] = $page;
        $this->load->view('b2b/header');
        $this->load->view('b2b/nav_logistic', $data);
        $this->load->view('b2b/lab_list1', $data);
        $this->load->view('b2b/footer');
    }

    function check_email() {
        $email = $this->input->post('email');
        $result = $this->Logistic_model->master_num_rows('collect_from', array("email" => $email, "status" => 1));
        if ($result == 0) {
            return true;
        } else {
            $this->form_validation->set_message('check_email', 'Email Already Exists.');
            return false;
        }
    }

    function lab_add() {

        if (!is_loggedin()) {
            redirect('login');
        }
        //echo "<pre>"; print_r($_POST); die();
        $data["login_data"] = logindata();
        $data["user"] = $this->Logistic_model->getUser($data["login_data"]["id"]);
        $this->load->library('form_validation');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_check_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('lab_name', 'Lab Name', 'trim|required');
        $this->form_validation->set_rules('person_name', 'Contact Person Name', 'trim|required');
        $this->form_validation->set_rules('mobile_number', 'Mobile Number', 'trim|required');
        $this->form_validation->set_rules('city', 'City name', 'trim|required');
        $this->form_validation->set_rules('address', 'Address', 'trim|required');
        $this->form_validation->set_rules('sales_fk', 'Activated By', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $lab_name = $this->input->post('lab_name');
            $person_name = $this->input->post('person_name');
            $mobile_number = $this->input->post('mobile_number');
            $alternate_number = $this->input->post('alternate_number');
            $address = $this->input->post('address');
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $city = $this->input->post('city');
            $sales_fk = $this->input->post('sales_fk');
            $data1 = array(
                "name" => $lab_name,
                "contact_person_name" => $person_name,
                "city" => $city,
                "mobile_number" => $mobile_number,
                "alternate_number" => $alternate_number,
                "address" => $address,
                "email" => $email,
                "password" => $password,
                "createddate" => date("Y-m-d H:i:s"),
                "sales_fk" => $sales_fk
            );
            $data['query'] = $this->Logistic_model->master_fun_insert("collect_from", $data1);
            $this->session->set_flashdata("success", array("Lab successfully added."));
            redirect("b2b/Logistic/lab_list", "refresh");
        } else {

            $data['cityall'] = $this->Logistic_model->master_fun_get_tbl_val("b2b_test_cities", array('status' => '1'), array("name", "asc"));
            $data['sales'] = $this->Logistic_model->master_fun_get_tbl_val("sales_user_master", array('status' => '1'), array("first_name", "asc"));
            $this->load->view('b2b/header');
            $this->load->view('b2b/nav_logistic', $data);
            $this->load->view('b2b/lab_add', $data);
            $this->load->view('b2b/footer');
        }
    }

    function lab_delete($id) {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $this->Logistic_model->master_fun_update("collect_from", array("id" => $id), array("status" => "0"));
        $this->session->set_flashdata("success", array("Lab successfully deleted."));
        redirect("b2b/Logistic/lab_list", "refresh");
    }

    function check_email1($id) {
        $labid = $this->uri->segment(4);
        $email = $this->input->post('email');
        $result = $this->Logistic_model->master_num_rows('collect_from', array("email" => $email, "id !=" => $labid, "status" => 1));
        if ($result == 0) {
            return true;
        } else {
            $this->form_validation->set_message('check_email1', 'Email Already Exists.');
            return false;
        }
    }

    function lab_edit($id) {

        if (!is_loggedin()) {
            redirect('login');
        }
        $data["id"] = $id;
        $data["login_data"] = logindata();
        $data["user"] = $this->Logistic_model->getUser($data["login_data"]["id"]);
        $this->load->library('form_validation');

        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_check_email1');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('lab_name', 'Lab Name', 'trim|required');
        $this->form_validation->set_rules('person_name', 'Contact Person Name', 'trim|required');
        $this->form_validation->set_rules('mobile_number', 'Mobile Number', 'trim|required');
        $this->form_validation->set_rules('address', 'Address', 'trim|required');
        $this->form_validation->set_rules('city', 'City name', 'trim|required');
        $this->form_validation->set_rules('sales_fk', 'Activated By', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $lab_name = $this->input->post('lab_name');
            $person_name = $this->input->post('person_name');
            $mobile_number = $this->input->post('mobile_number');
            $alternate_number = $this->input->post('alternate_number');
            $address = $this->input->post('address');
            $email = $this->input->post('email');
            $city = $this->input->post('city');
            $password = $this->input->post('password');
            $sales_fk = $this->input->post('sales_fk');
            $data1 = array(
                "name" => $lab_name,
                "contact_person_name" => $person_name,
                "mobile_number" => $mobile_number,
                "city" => $city,
                "alternate_number" => $alternate_number,
                "address" => $address,
                "email" => $email,
                "password" => $password,
                "sales_fk"=>$sales_fk
            );
            $this->Logistic_model->master_fun_update("collect_from", array("id" => $id), $data1);
            $this->session->set_flashdata("success", array("Lab successfully updated."));
            redirect("b2b/Logistic/lab_list", "refresh");
        } else {
            $data['query'] = $this->Logistic_model->master_fun_get_tbl_val("collect_from", array('id' => $id), array("id", "asc"));
            $data['cityall'] = $this->Logistic_model->master_fun_get_tbl_val("b2b_test_cities", array('status' => '1'), array("name", "asc"));
            $data['sales'] = $this->Logistic_model->master_fun_get_tbl_val("sales_user_master", array('status' => '1'), array("first_name", "asc"));
            $this->load->view('b2b/header');
            $this->load->view('b2b/nav_logistic', $data);
            $this->load->view('b2b/lab_edit', $data);
            $this->load->view('b2b/footer');
        }
    }

    function get_city_test() {

        $city = $this->input->get_post("city");
        if ($city) {
            $data['test'] = $this->Logistic_model->get_val("SELECT `sample_test_master`.`id`,`sample_test_master`.`test_name`,`sample_test_master`.`test_name`,`sample_test_master`.`status`,`sample_test_city_price`.`price` FROM `sample_test_master` INNER JOIN `sample_test_city_price` ON `sample_test_master`.`id`=`sample_test_city_price`.`test_fk` WHERE `sample_test_master`.`status`='1' AND `sample_test_city_price`.`status`='1' AND `sample_test_city_price`.`city_fk`='" . $city . "'");
        } else {
            $data['test'] = $this->Logistic_model->get_val("SELECT `sample_test_master`.`id`,`sample_test_master`.`test_name`,`sample_test_master`.`test_name`,`sample_test_master`.`status`,`sample_test_city_price`.`price` FROM `sample_test_master` INNER JOIN `sample_test_city_price` ON `sample_test_master`.`id`=`sample_test_city_price`.`test_fk` WHERE `sample_test_master`.`status`='1' AND `sample_test_city_price`.`status`='1' AND `sample_test_city_price`.`city_fk`='1'");
        }
        $this->load->view("b2b/get_city_test_reg", $data);
    }

    function get_lab_tests() {
        $lab = $this->input->get_post("lab");
        
        /* $data['test'] = $this->Logistic_model->get_val("SELECT l.id,l.`price`,l.`special_price`,t.`test_name`,l.b2b_price,t.thyrocare_code FROM sample_test_city_price l LEFT JOIN sample_test_master t ON t.`id`=l.`test_fk` WHERE l.`status`='1' and l.lab_fk='$lab' ORDER BY l.id desc"); 
		*/
		$data['labdetils']=$this->Logistic_model->fetchdatarow('test_discount,city','collect_from',array("id"=>$lab,"status"=>1));
		$city=$data['labdetils']->city;
			
			$data['test'] = $this->Logistic_model->get_val("select t.test_name,t.id,p.price,(SELECT price_special FROM b2b_testspecial_price s  WHERE  s.test_fk=t.id AND s.status='1' AND s.lab_id='$lab_id') AS specialprice from test_master t inner JOIN test_master_city_price p ON t.id = p.test_fk AND p.city_fk='$city'  where t.status='1'  GROUP BY t.id");
		 
        $this->load->view("b2b/get_city_test_reg", $data);
    
	
	}

    function upload_report($cid = "") {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $files = $_FILES;
        $this->load->library('upload');
        $file_upload = array();
        /* if (!empty($_FILES['common_report']['name'])) {
          $desc = $this->input->post('desc_common_report');
          $type_common_report = $this->input->post('type_common_report');
          $_FILES['userfile']['name'] = $files['common_report']['name'];
          $_FILES['userfile']['type'] = $files['common_report']['type'];
          $_FILES['userfile']['tmp_name'] = $files['common_report']['tmp_name'];
          $_FILES['userfile']['error'] = $files['common_report']['error'];
          $_FILES['userfile']['size'] = $files['common_report']['size'];
          $config['upload_path'] = './upload/business_report/';
          $config['allowed_types'] = '*';
          $config['file_name'] = time() . $files['common_report']['name'];
          $config['file_name'] = str_replace(' ', '_', $config['file_name']);
          $config['overwrite'] = FALSE;
          $this->load->library('upload', $config);
          $this->upload->initialize($config);
          if (!$this->upload->do_upload()) {
          $error = $this->upload->display_errors();
          $this->session->set_flashdata("error", array($error));
          redirect('b2b/Logistic/details/' . $cid);
          } else {
          $file_upload[] = array("job_fk" => $cid, "report" => $config['file_name'], "original" => $_FILES['common_report']['name'], "test_fk" => "", "description" => $desc, "type" => $type_common_report);
          }
          } */
        if (!empty($_FILES['common_report']['name'])) {
            $filesCount = count($_FILES['common_report']['name']);
            $type_common_report = $this->input->post('type_common_report');
            $desc = $this->input->post('desc_common_report');
            $upcount = 1;
            for ($i = 0; $i < $filesCount; $i++) {

                $_FILES['userFile']['name'] = $_FILES['common_report']['name'][$i];
                $_FILES['userFile']['type'] = $_FILES['common_report']['type'][$i];
                $_FILES['userFile']['tmp_name'] = $_FILES['common_report']['tmp_name'][$i];
                $_FILES['userFile']['error'] = $_FILES['common_report']['error'][$i];
                $_FILES['userFile']['size'] = $_FILES['common_report']['size'][$i];

                $config['upload_path'] = './upload/business_report/';
                $config['allowed_types'] = '*';
                $config['file_name'] = time() . "_" . $upcount . $_FILES['userFile']['name'];
                $config['file_name'] = str_replace(' ', '_', $config['file_name']);
                $config['overwrite'] = FALSE;

                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if ($this->upload->do_upload('userFile')) {

                    $fileData = $this->upload->data();
                    $clientname = $fileData['client_name'];
                    $filename = $fileData['file_name'];
                    $upcount++;
                    $file_upload = array("job_fk" => $cid, "report" => $filename, "original" => $clientname, "description" => $desc, "type" => $type_common_report);

                    $delete = $this->Logistic_model->master_fun_insert("b2b_jobspdf", $file_upload);
                }
            }
        }

        /* foreach ($file_upload as $f_key) {
          $insetdata=array("barcode_fk" => $cid,"report" => $f_key["report"], "report_orignal" => $f_key["original"], "report_description" => $desc);
          $delete = $this->Logistic_model->master_fun_insert("sample_job_master",$insetdata);
          } */
        $this->session->set_flashdata("success", array("$upcount Report Upload successfully."));
        redirect('b2b/Logistic/details/' . $cid);
    }

    function delete_downloadfile($path) {
        $this->load->helper('file');
        unlink($path);
    }

    function genrate_report($cid = "") {

        if (!is_loggedin()) {
            redirect('login');
        }
        if ($cid != "") {
            if (!empty($_FILES['common_report']['name'])) {

                $data["login_data"] = logindata();
                $data['cid'] = $cid;
                ini_set('max_execution_time', 300);
                $data['page_title'] = 'AirmedLabs'; // pass data to the view
                ini_set('memory_limit', '512M'); // boost the memory limit if it's low <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="?" draggable="false" class="emoji">

                $filesCount = count($_FILES['common_report']['name']);
                $type_common_report = $this->input->post('type_common_report');
                $desc = $this->input->post('desc_common_report');
                $latterpad = $this->input->post('latterpad');

                $upcount = 1;
                $file_upload = array();

                for ($i = 0; $i < $filesCount; $i++) {

                    $_FILES['userFile']['name'] = $_FILES['common_report']['name'][$i];
                    $_FILES['userFile']['type'] = $_FILES['common_report']['type'][$i];
                    $_FILES['userFile']['tmp_name'] = $_FILES['common_report']['tmp_name'][$i];
                    $_FILES['userFile']['error'] = $_FILES['common_report']['error'][$i];
                    $_FILES['userFile']['size'] = $_FILES['common_report']['size'][$i];

                    $config['upload_path'] = './upload/business_report/';
                    $config['allowed_types'] = '*';
                    $config['file_name'] = time() . "_" . $upcount . $_FILES['userFile']['name'];
                    $config['file_name'] = str_replace(' ', '_', $config['file_name']);
                    $config['overwrite'] = FALSE;

                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if ($this->upload->do_upload('userFile')) {

                        $fileData = $this->upload->data();
                        $clientname = $fileData['client_name'];
                        $filename = $fileData['file_name'];
                        $upcount++;
                        $file_upload[] = $filename;
                        /* $file_upload = array("job_fk" => $cid, "report" => $filename, "original" => $clientname, "description" => $desc, "type" => $type_common_report);

                          $delete = $this->Logistic_model->master_fun_insert("b2b_jobspdf", $file_upload); */
                    }
                }
                if ($file_upload != null) {

                    $getdetils = $this->Logistic_model->getsampledetils($cid);
                    if ($getdetils != "") {

                        $pdfFilePath = FCPATH . "/upload/business_report/" . $getdetils->id . "_result.pdf";
                        if (file_exists($pdfFilePath)) {
                            $this->delete_downloadfile($pdfFilePath);
                            $namerepor = $getdetils->id . "_result.pdf";
                            $this->Logistic_model->master_fun_update('b2b_jobspdf', array("report" => $namerepor), array("status" => '0'));
                            /* $detilslaterped=$this->Logistic_model->fetchdatarow('id','b2b_jobspdf',array('status'=>1,'report'=>$namerepor));
                              if($detilslaterped != ""){ $this->Logistic_model->master_fun_update('b2b_jobspdf',array("id"=>$detilslaterped->id),array("status"=>0));  } */
                        }

                        $data['fileuplode'] = $file_upload;
                        $html = $this->load->view('b2b/b2b_result_pdf', $data, true);
                        //echo $html; die(); 
                        $this->load->library('pdf');
                        $pdf = $this->pdf->load();
                        $pdf->autoScriptToLang = true;
                        $pdf->baseScript = 1; // Use values in classes/ucdn.php  1 = LATIN
                        $pdf->autoVietnamese = true;
                        $pdf->autoArabic = true;
                        $pdf->autoLangToFont = true;
                        //echo "<pre>"; print_r($getdetils); die();
                        $content = $this->Logistic_model->master_fun_get_tbl_val("pdf_design", array('branch_fk' => '0'), array("id", "asc"));
                        /* echo "<pre>";print_r($file_upload); die(); */
                        $data["content"] = $content;
                        $this->load->library("util");
                        $util = new Util;
                        $age = $util->get_age($getdetils->customer_dob);
                        $ageinDays = 0;
                        if ($age[0] != 0) {
                            $ageinDays += ($age[0] * 365);
                            $age1 = $age[0] . " Year";
                            $age_type = 'Year';
                        }
                        if ($age[0] == 0 && $age[1] != 0) {
                            $ageinDays += ($age[1] * 30);
                            $age1 = $age[1] . " Month";
                            $age_type = 'Month';
                        }
                        if ($age[0] == 0 && $age[1] == 0 && $age[2] != 0) {
                            $ageinDays += ($age[2]);
                            $age1 = $age[2] . " Day";
                            $age_type = 'Day';
                        }

                        $find = array(
                            '/{{BARCODE}}/',
                            '/{{CUSTID}}/',
                            '/{{REGDATE}}/',
                            '/{{NAME}}/',
                            '/{{sex}}/',
                            '/{{age}}/',
                            '/{{REPORTDATE}}/',
                            '/{{TELENO}}/',
                            '/{{address}}/',
                            '/{{referred}}/'
                        );
                        $base_url = base_url();
                        $barecode_url = $base_url . 'user_assets/images/pdf_barcode.png';
                        $logo_url = $base_url . 'user_assets/images/logoaastha.png';
                        $barecode_url = $base_url . 'user_assets/images/pdf_barcode.png';
                        $sign_url = $base_url . 'user_assets/images/dr_gupta_sign.png';
                        $phone_url = $base_url . 'user_assets/images/pdf_phn_btn.png';

                        /* if($latterpad==2){ $laterheder=$content[0]["without_header"]; $laterfoter=$content[0]["without_footer"]; }else{ $laterheder=$content[0]["header"]; $laterfoter=$content[0]["footer"]; } */
                        $replace = array(
                            'pdf_barcode.png',
                            $cid,
                            date("d-M-Y g:i", strtotime($getdetils->scan_date)),
                            strtoupper($getdetils->customer_name),
                            strtoupper($getdetils->customer_gender),
                            $age1,
                            date('d-M-Y'),
                            $getdetils->mobile, $getdetils->address, strtoupper($getdetils->doctor)
                        );
                        $header = preg_replace($find, $replace, $content[0]["without_header"]);
                        $pdf->SetHTMLHeader($header);
                        $pdf->AddPage('p', // L - landscape, P - portrait
                                '', '', '', '', 5, // margin_left
                                5, // margin right
                                70, // margin top
                                72, // margin bottom
                                2, // margin header 
                                2);
                        $sign_url = $base_url . 'user_assets/images/dr_gupta_sign.png';
                        $phone_url = $base_url . 'user_assets/images/pdf_phn_btn.png';
                        $emailimg = $base_url . 'user_assets/images/email-icon.png';
                        $webimg = $base_url . 'user_assets/images/web-icon.png';
                        $lastimg = $base_url . 'user_assets/images/lastimg.png';
                        $pdf->SetHTMLFooter($content[0]["without_footer"]);
                        $pdf->WriteHTML($html);
                        $pdf->Output($pdfFilePath, 'F'); // save to file because we can
                        $filename = $getdetils->id . "_result.pdf";


                        $file_upload = array("job_fk" => $cid, "report" => $filename, "original" => $filename, "description" => $desc, "type" => $type_common_report);

                        $b2f2 = $this->Logistic_model->master_fun_insert("b2b_jobspdf", $file_upload);

                        $header1 = preg_replace($find, $replace, $content[0]["header"]);
                        $footer1 = $content[0]["footer"];
                        $this->with_approve_report($cid, $getdetils->id, $header1, $html, $footer1);

                        $this->session->set_flashdata("success", array("Report Upload successfully."));
                        redirect('b2b/Logistic/details/' . $cid);
                    } else {
                        show_404();
                    }
                } else {
                    $this->session->set_flashdata("success", array("This is not an allowed file type."));
                    redirect('b2b/Logistic/details/' . $cid);
                }
            } else {
                show_404();
            }
        } else {
            show_404();
        }
    }

    function with_approve_report($cid, $ordeid, $header, $html, $footer) {

        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '512M');

        $pdfFilePath1 = FCPATH . "/upload/business_report/" . $ordeid . "_result_wlpd.pdf";
        if (file_exists($pdfFilePath1)) {
            $this->delete_downloadfile($pdfFilePath1);
            $namerepor = $ordeid . "_result_wlpd.pdf";
            $this->Logistic_model->master_fun_update('b2b_jobspdf', array("report" => $namerepor), array("status" => '0'));
        }
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->SetHTMLHeader($header);
        $pdf->AddPage('p', // L - landscape, P - portrait
                '', '', '', '', 5, // margin_left
                5, // margin right
                72, // margin top
                72, // margin bottom
                2, // margin header 
                2);
        $sign_url = $base_url . 'user_assets/images/dr_gupta_sign.png';
        $phone_url = $base_url . 'user_assets/images/pdf_phn_btn.png';
        $emailimg = $base_url . 'user_assets/images/email-icon.png';
        $webimg = $base_url . 'user_assets/images/web-icon.png';
        $lastimg = $base_url . 'user_assets/images/lastimg.png';
        $pdf->SetHTMLFooter($footer);
        $pdf->WriteHTML($html);
        $pdf->Output($pdfFilePath1, 'F'); // save to file because we can
        $filename1 = $ordeid . "_result_wlpd.pdf";


        $file_upload1 = array("job_fk" => $cid, "report" => $filename1, "original" => $filename1, "type" => 'c');

        $b2f2 = $this->Logistic_model->master_fun_insert("b2b_jobspdf", $file_upload1);
        return true;
    }

    function sendToThyrocare($id) {
        $this->load->model('job_model');
        $this->load->library('Thyrocare');
        $data['job_details'] = $this->job_model->master_fun_get_tbl_val("sample_job_master", array("barcode_fk" => $id, 'status' => 1), array("id", "asc"));
        $cnt = 0;

        foreach ($data['job_details'] as $key) {
            $job_test = $this->job_model->master_fun_get_tbl_val("sample_job_test", array("job_fk" => $key["id"], 'status' => 1), array("id", "asc"));
            $tst_name = array();
            foreach ($job_test as $tkey) {
                //echo "SELECT sample_test_master.`id`,`sample_test_master`.`TEST_CODE`,`sample_test_master`.`test_name`,`sample_test_master`.`test_name`,`sample_test_master`.`status`,`sample_test_city_price`.`price` FROM `sample_test_master` INNER JOIN `sample_test_city_price` ON `sample_test_master`.`id`=`sample_test_city_price`.`test_fk` WHERE `sample_test_master`.`status`='1' AND `sample_test_city_price`.`status`='1' AND `sample_test_city_price`.`city_fk`='" . $data['job_details'][0]["test_city"] . "' AND `sample_test_master`.`id`='" . $tkey["test_fk"] . "'"; die();
                $test_info = $this->job_model->get_val("SELECT sample_test_master.`id`,`sample_test_master`.`test_name`,`sample_test_master`.`thyrocare_code`,`sample_test_master`.`test_name`,`sample_test_master`.`status`,`sample_test_master`.`price` ,`sample_test_master`.`sample`  FROM  `sample_test_city_price`   LEFT JOIN   `sample_test_master`   ON `sample_test_master`.`id`=sample_test_city_price.`test_fk` WHERE `sample_test_master`.`status` = '1' AND  `sample_test_city_price`.`id`='" . $tkey["test_fk"] . "'");

                $tkey["info"] = $test_info;

                $tst_name[] = $tkey;
            }
            $data['job_details'][0]["test_list"] = $tst_name;
            $job_packages = $this->job_model->master_fun_get_tbl_val("sample_book_package", array("job_fk" => $key["id"], 'status' => 1), array("id", "asc"));
            $data['job_details'][0]["package_list"] = $job_packages;
            $c_details = $this->job_model->master_fun_get_tbl_val("sample_customer_master", array("id" => $key["cust_fk"], 'status' => 1), array("id", "asc"));
            $data['job_details'][0]["cusomer_details"] = $c_details;
            $cnt++;
        }
        $data['barcode_detail'] = $this->job_model->get_val("SELECT `logistic_log`.*,`phlebo_master`.`name`,`phlebo_master`.`mobile`,`collect_from`.`name` AS `c_name` FROM `logistic_log` 
INNER JOIN `phlebo_master` ON `logistic_log`.`phlebo_fk`=`phlebo_master`.`id` 
INNER JOIN `collect_from` ON `logistic_log`.`collect_from`=`collect_from`.`id`
WHERE `phlebo_master`.`status`='1' AND `logistic_log`.`status`='1' and `logistic_log`.`id`='" . $id . "'");
        $c_details = $c_details[0];
        $this->load->library("util");
        $util = new Util;
        $age = $util->get_age($data['job_details'][0]["customer_dob"]);
        $woe = array();
        $woe["PATIENT_NAME"] = $data['job_details'][0]["customer_name"];
        $woe["AGE"] = $age[0];
        $woe["GENDER"] = ($data['job_details'][0]["customer_gender"] == 'male') ? "M" : "F";
        $woe["EMAIL_ID"] = $data['job_details'][0]["customer_email"];
        $woe["CONTACT_NO"] = $data['job_details'][0]["customer_mobile"];
        $woe["SPECIMEN_COLLECTION_TIME"] = $data['job_details'][0]["date"];
        $woe["TOTAL_AMOUNT"] = $data['job_details'][0]["payable_amount"];
        $woe["AMOUNT_COLLECTED"] = 0;
        $woe["AMOUNT_DUE"] = $data['job_details'][0]["payable_amount"];
        $woe["LAB_ADDRESS"] = "usman pura ahmedabad,gujarat";

        $testname = array();
        foreach ($data['job_details'][0]["test_list"] as $test) {

            $testname[] = $test["info"]["0"]["thyrocare_code"];
        }


        $barcodelist = array(array(
                'BARCODE' => $data['barcode_detail'][0]["barcode"],
                'TESTS' => implode(',', $testname),
                'SAMPLE_TYPE' => $test["info"]["0"]["sample"],
                'Vial_Image' => '',
        ));
        $APIDATA = $this->Logistic_model->master_fun_get_tbl_val("thyrocare_api", array("id" => "1"), array("id" => "asc"));
        $thyrocare = new thyrocare();
        $result = $thyrocare->getKey($APIDATA[0]["login_code"], $APIDATA[0]["password"]);
        $resultObj = json_decode($result);

        if ($resultObj->RESPONSE == "SUCCESS") {
            $apikey = $resultObj->API_KEY;
            $thyrocare->loadkey($apikey);
        }

        $result = $thyrocare->saveWOE($woe, $barcodelist);

        // $result='{"RES_ID":"RES0000","barcode_patient_id":"GUJ86185934039873312","message":"WORK ORDER ENTRY DONE SUCCESSFULLY","status":"SUCCESS"}';
        $resultObj = json_decode($result);
        if ($resultObj->status == "INVALID API KEY") {
            $result = $thyrocare->getKey($APIDATA[0]["login_code"], $APIDATA[0]["password"]);
            $resultObj = json_decode($result);

            if ($resultObj->RESPONSE == "SUCCESS") {
                $apikey = $resultObj->API_KEY;
                $thyrocare->loadkey($apikey);
                $this->Logistic_model->master_fun_update("thyrocare_api", array("id" => 1), array("api_key" => $apikey, "last_api_key_time" => date("Y-m-d H:i:s")));
            }
            $result = $thyrocare->saveWOE($woe, $barcodelist);
            $resultObj = json_decode($result);
        }

        if ($resultObj->status == "SUCCESS") {
            $this->Logistic_model->master_fun_update("logistic_log", array("id" => $id), array("thyrocare_job_id" => $resultObj->barcode_patient_id));
        } else {
            
        }
        echo $result;
    }

    function fetchresult($id) {
        $this->load->model('job_model');
        $this->load->library('Thyrocare');
        $data['job_details'] = $this->job_model->master_fun_get_tbl_val("logistic_log", array("id" => $id, 'status' => 1), array("id", "asc"));
        $cnt = 0;
        //echo $data['job_details'][0]["scan_date"];
        $dated = explode(" ", $data['job_details'][0]["scan_date"]);
        //print_r($dated); die();
        $APIDATA = $this->Logistic_model->master_fun_get_tbl_val("thyrocare_api", array("id" => "1"), array("id" => "asc"));
        $thyrocare = new thyrocare();
        $thyrocare->loadkey($APIDATA[0]["api_key"]);

        $result = $thyrocare->getRESULT($dated[0]);

        // $result='{"RES_ID":"RES0000","barcode_patient_id":"GUJ86185934039873312","message":"WORK ORDER ENTRY DONE SUCCESSFULLY","status":"SUCCESS"}';
        $resultObj = json_decode($result);
        if ($resultObj->response == "INVALID API KEY") {
            $result = $thyrocare->getKey($APIDATA[0]["login_code"], $APIDATA[0]["password"]);
            $resultObj = json_decode($result);

            if ($resultObj->RESPONSE == "SUCCESS") {
                $apikey = $resultObj->API_KEY;
                $thyrocare->loadkey($apikey);
                $this->Logistic_model->master_fun_update("thyrocare_api", array("id" => 1), array("api_key" => $apikey, "last_api_key_time" => date("Y-m-d H:i:s")));
            }
            $result = $thyrocare->getRESULT($dated[0]);
            $resultObj = json_decode($result);
        }

        if ($resultObj->status == "SUCCESS") {
            $this->Logistic_model->master_fun_update("logistic_log", array("id" => $id), array("thyrocare_job_id" => $resultObj->barcode_patient_id));
        } else {
            
        }

        $result_link = "";
        $dbdata = array();
        foreach ($resultObj->patients as $res) {

            if ($res->patient_id == $data['job_details'][0]["thyrocare_job_id"]) {
                $result_link = $res->pdflink;
            }
            $dbdata[] = array("thyrocare_job_id" => $res->patient_id, "thyrocare_report" => $res->pdflink);
        }
        $this->db->update_batch('logistic_log', $dbdata, 'thyrocare_job_id');
        $data['job_details'] = $this->job_model->master_fun_get_tbl_val("logistic_log", array("id" => $id, 'status' => 1), array("id", "asc"));
        if ($data['job_details'][0]['thyrocare_report'] != "") {
            header('Location: ' . $data['job_details'][0]['thyrocare_report']);
            die();
        }
        echo "<center><h3>No RESULT FOUND. TRY AGAIN SOME TIME</h3></center>";
        die();
        echo $result;
    }

    function fetchresultbydate($date) {
        $this->load->model('job_model');
        $this->load->library('Thyrocare');
        $cnt = 0;

        $APIDATA = $this->Logistic_model->master_fun_get_tbl_val("thyrocare_api", array("id" => "1"), array("id" => "asc"));
        $thyrocare = new thyrocare();
        $thyrocare->loadkey($APIDATA[0]["api_key"]);

        $result = $thyrocare->getRESULT($date);

        // $result='{"RES_ID":"RES0000","barcode_patient_id":"GUJ86185934039873312","message":"WORK ORDER ENTRY DONE SUCCESSFULLY","status":"SUCCESS"}';
        $resultObj = json_decode($result);
        if ($resultObj->response == "INVALID API KEY") {
            $result = $thyrocare->getKey($APIDATA[0]["login_code"], $APIDATA[0]["password"]);
            $resultObj = json_decode($result);

            if ($resultObj->RESPONSE == "SUCCESS") {
                $apikey = $resultObj->API_KEY;
                $thyrocare->loadkey($apikey);
                $this->Logistic_model->master_fun_update("thyrocare_api", array("id" => 1), array("api_key" => $apikey, "last_api_key_time" => date("Y-m-d H:i:s")));
            }
            $result = $thyrocare->getRESULT($date);
            $resultObj = json_decode($result);
        }

        if ($resultObj->status == "SUCCESS") {
            $this->Logistic_model->master_fun_update("logistic_log", array("id" => $id), array("thyrocare_job_id" => $resultObj->barcode_patient_id));
        } else {
            
        }

        $result_link = "";
        $dbdata = array();
        foreach ($resultObj->patients as $res) {

            $dbdata[] = array("thyrocare_job_id" => $res->patient_id, "thyrocare_report" => $res->pdflink);
        }
        $this->db->update_batch('logistic_log', $dbdata, 'thyrocare_job_id');
        echo "<pre>";
        print_R($result);
        die();
        echo $result;
    }

    function testme() {
        $url = "https://www.thyrocare.com/API/b2b/MASTER.svc/Z98AceJzS51rYIwHP)RLrz3M5WU4NaGo)bsELEn8lFY=/ALL/getproducts";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = "";
        if (($response = curl_exec($ch)) === false) {
            echo die('Curl error: ' . curl_error($ch));
        } else {
            //echo $response;
        }
        curl_close($ch);
        echo "test<pre>";
        $response = json_decode($response);
        $tests = $response->B2B_MASTERS->TESTS;
        $OUTLAB_TESTLIST = $response->B2B_MASTERS->OUTLAB_TESTLIST;
        $POP = $response->B2B_MASTERS->POP;
        $PROFILE = $response->B2B_MASTERS->PROFILE;

        $sms_data = array();
        foreach ($tests as $t) {
            $barcode = $t->barcodes[0];
            $code = $t->code;
            $name = $t->name;
            $product = $t->product;
            $rate = $t->rate;
            $type = $t->type;
            $tB2B = $rate->b2b;
            $tB2C = $rate->b2c;
            $res = array("code" => $code, "name" => $name, "product" => $product, "type" => $type, "b2b" => $tB2B, "b2c" => $tB2C, "specimen_type" => $barcode->specimen_type);
            print_R($res);
            $sms_data[] = $res;
        }
        foreach ($OUTLAB_TESTLIST as $t) {
            $barcode = $t->barcodes[0];
            $code = $t->code;
            $name = $t->name;
            $product = $t->product;
            $rate = $t->rate;
            $type = $t->type;
            $tB2B = $rate->b2b;
            $tB2C = $rate->b2c;
            $res = array("code" => $code, "name" => $name, "product" => $product, "type" => $type, "b2b" => $tB2B, "b2c" => $tB2C, "specimen_type" => $barcode->specimen_type);
            print_R($res);
            $sms_data[] = $res;
        }
        foreach ($PROFILE as $t) {
            $barcode = $t->barcodes[0];
            $code = $t->code;
            $name = $t->name;
            $product = $t->product;
            $rate = $t->rate;
            $type = $t->type;
            $tB2B = $rate->b2b;
            $tB2C = $rate->b2c;
            $res = array("code" => $code, "name" => $name, "product" => $product, "type" => $type, "b2b" => $tB2B, "b2c" => $tB2C, "specimen_type" => $barcode->specimen_type);
            print_R($res);
            $sms_data[] = $res;
        }

        foreach ($POP as $t) {
            $barcode = $t->barcodes[0];
            $code = $t->code;
            $name = $t->name;
            $product = $t->product;
            $rate = $t->rate;
            $type = $t->type;
            $tB2B = $rate->b2b;
            $tB2C = $rate->b2c;
            $res = array("code" => $code, "name" => $name, "product" => $product, "type" => $type, "b2b" => $tB2B, "b2c" => $tB2C, "specimen_type" => $barcode->specimen_type);
            print_R($res);
            $sms_data[] = $res;
        }
        $this->db->insert_batch('thyrocare_b2b_test', $sms_data);
    }

    public function getcopeytest() {
        set_time_limit(0);
        $testall = $this->Logistic_model->master_fun_get_tbl_val("test_master", array('status' => 1), array("id", "asc"));

        foreach ($testall as $test) {

            $data1 = array("test_name" => $test['test_name'], "TEST_CODE" => $test['TEST_CODE'], "description" => $test['description'], "department" => $test['department_fk'], "sample" => $test['sample_for_analysis'], "container" => $test['container_for_primary_sampling'], "methodology" => $test['method'], "temp" => $test['temp'], "cutt_off" => $test['cut_off'], "schedule" => $test['schedule'], "reporting" => $test['reporting'], "spacial_inst" => $test['special_instruction'], "price" => $test['price']);


            $insert = $this->Logistic_model->master_fun_insert("sample_test_master", $data1);
        }
    }

    function desti_assign() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $desti_lab = $this->input->post("desti_lab");
        $job_fk = $this->input->post("job_fk");
        // print_r($_POST);
        $get_b2c_lab1 = $this->Logistic_model->master_fun_get_tbl_val("admin_master", array("id" => $desti_lab), array("id", "asc"));
        /* Nishit add job in b2c */
        if ($get_b2c_lab1[0]["assign_branch"] > 0) {
            $logistic = $this->Logistic_model->master_fun_get_tbl_val("logistic_log", array("id" => $job_fk), array("id", "asc"));
            $test_city = $this->Logistic_model->master_fun_get_tbl_val("branch_master", array("id" => $get_b2c_lab1[0]["assign_branch"]), array("id", "asc"));
            $this->load->model("service_model");
            $get_b2c_lab = $this->Logistic_model->master_fun_get_tbl_val("sample_job_master", array("barcode_fk" => $job_fk), array("id", "asc"));
            // print_r($get_b2c_lab); die();
            $name = $get_b2c_lab[0]["customer_name"];
            $phone = $get_b2c_lab[0]["customer_mobile"];
            $email = $get_b2c_lab[0]["customer_email"];
            $note = $get_b2c_lab[0]["note"];
            if ($email == '') {
                $email = 'noreply@airmedlabs.com';
            }
            $gender = $get_b2c_lab[0]["customer_gender"];
            $dob = $get_b2c_lab[0]["customer_dob"];
            $test_city = $test_city[0]["city"];
            $address = $get_b2c_lab[0]["customer_address"];
            $discount = $get_b2c_lab[0]["discount"];
            //$payable = $this->input->get_post("payable");
            $test = $this->Logistic_model->get_val("SELECT   sample_test_city_price.`test_fk` FROM  `sample_job_test`   LEFT JOIN  sample_test_city_price ON `sample_test_city_price`.`id` = sample_job_test.`test_fk` WHERE sample_job_test.job_fk = '" . $get_b2c_lab[0]["id"] . "' AND sample_job_test.`status`=1");
            //echo $this->db->last_query(); 

            $referral_by = 0;
            $source = 0;
            $pid = 0;
            $relation_fk = 0;
            $applyCollectionCharge = 0;
            $noify_cust = 0;
            //$phlebo = $this->input->get_post("phlebo");
            //$phlebo_date = $this->input->get_post("phlebo_date");
            // $phlebo_time = $this->input->get_post("phlebo_time");
            $advance_collection = 0;
            //print_r($get_b2c_lab1); die();
            $branch = $get_b2c_lab1[0]["assign_branch"];
            if ($branch == '') {
                $branch = 1;
            }

            $order_id = $this->get_job_id($test_city);

            if ($name == "") {
                $name = "AM-" . $job_fk;
            }
            $date = date('Y-m-d H:i:s');
            $result1 = $this->service_model->get_val("SELECT * FROM `customer_master` WHERE `status`='1' AND active='1' AND `email`='" . $email . "' ORDER BY id ASC");
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
                //       print_r($c_data); die();
                $customer = $this->service_model->master_fun_insert("customer_master", $c_data);
            } else {
                $customer = $result1[0]["id"];
            }
            //  echo $customer;
            //  die();
            $price = 0;
            $test_package_name = array();
            $price = 0;
            foreach ($test as $key) {
                $result = $this->service_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $test_city . "' AND `test_master`.`id`='" . $key["test_fk"] . "'");
//echo $this->db->last_query();              
                $price += $result[0]["price"];
                $test_package_name[] = $result[0]["test_name"];
            }
            //		print_R($test);die();

            /* Nishit book phlebo start */
            $test_for = $this->input->get_post("test_for");
            $testforself = "self";
            $family_mem_id = 0;
            $booking_fk = $this->service_model->master_fun_insert("booking_info", array("user_fk" => $customer, "type" => $testforself, "family_member_fk" => $family_mem_id, "address" => $address, "emergency" => 0, "status" => "1", "createddate" => date("Y-m-d H:i:s")));
            /* Nishit book phlebo end */
            $data = array(
                "order_id" => $order_id,
                "cust_fk" => $customer,
                "doctor" => $referral_by,
                "other_reference" => $source,
                "date" => $date,
                "price" => $price,
                "status" => '8',
                "payment_type" => "Cash On Delivery",
                "discount" => $discount,
                "payable_amount" => $price - $advance_collection,
                "address" => $address,
                "branch_fk" => $branch,
                "test_city" => $test_city,
                "note" => $note,
                "added_by" => $data["login_data"]["id"],
                "booking_info" => $booking_fk,
                "notify_cust" => $noify_cust,
                "portal" => "web",
                "collection_charge" => $collecion_charge,
                "date" => date("Y-m-d H:i:s"),
                "barcode" => $logistic[0]["barcode"],
                "model_type" => 2,
                "b2b_id" => $job_fk
            );
            //  print_r($data); die();
            $insert = $this->service_model->master_fun_insert("job_master", $data);
            /* foreach ($test as $key) {
              $this->service_model->master_fun_insert("job_test_list_master", array('job_fk' => $insert, "test_fk" => $key));
              } */
//            $this->service_model->master_fun_insert("job_master_receiv_amount", array("job_fk" => $insert, "phlebo_fk" => $pid, "amount" => $advance_collection, "createddate" => date("Y-m-d H:i:s"), "payment_type" => "CASH"));
            foreach ($test as $key) {
                $this->service_model->master_fun_insert("job_test_list_master", array('job_fk' => $insert, "test_fk" => $key["test_fk"], "is_panel" => "0"));
                $tst_price = $this->service_model->get_val("select price from test_master_city_price where test_fk='" . $key["test_fk"] . "' and city_fk='" . $test_city . "' and status='1'");
                $this->service_model->master_fun_insert("booked_job_test", array("job_fk" => $insert, "test_city" => $test_city, "test_fk" => "t-" . $key["test_fk"], "price" => $tst_price[0]["price"]));
            }
        }
        /* END */
        $data = array(
            "lab_fk" => $desti_lab,
            "job_fk" => $job_fk,
            "status" => "1",
            "createddate" => date("Y-m-d H:i:s")
        );
        $insert = $this->Logistic_model->master_fun_insert("sample_destination_lab", $data);
        $this->Logistic_model->master_fun_update("logistic_log", array("id" => $job_fk), array("jobsstatus" => '2'));

        $this->session->set_flashdata("success", array("Destination lab successfullly assigned.  "));
        redirect("/b2b/Logistic/sample_list");
    }

    public function pdfdelete() {
        $pid = $this->input->post("pid");
        $insert = $this->Logistic_model->master_fun_update("b2b_jobspdf", array("id" => $pid), array("status" => '0'));
        if ($insert) {
            echo "1";
        } else {
            echo "0";
        }
    }

    public function getreportpdf() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $pid = $this->input->post("pid");

        $jobspdf = $this->Logistic_model->master_fun_get_tbl_val("b2b_jobspdf", array("job_fk" => $pid, 'status' => 1), array("id", "asc"));
        ?>
        <table class="table table-striped" id="city_wiae_price123">
            <?php /* <thead>
              <tr><th>file Name</th><th></th></tr>
              </thead> */ ?>
            <tbody id="t_body123">
                <?php
                if ($jobspdf != null) {
                    foreach ($jobspdf as $pdf) {
                        if (!empty($pdf['report'])) {
                            ?>
                            <tr id="pdf_<?= $pdf['id']; ?>">
                                <td><a href="<?php echo base_url(); ?>upload/business_report/<?php echo $pdf['report']; ?>" target="_blank"> <?php echo $pdf['original']; ?> </a></td>
                                <td><a href="<?= base_url() . "b2b/logistic/downlodepdf/" . $pdf['report']; ?>" class="pdfdelete" ><i class='fa fa-arrow-down'></i> Download</a></td>
                            </tr>
                            <?php
                        }
                    }
                } else {
                    echo "<tr><td colspan='2'>no data available</td></tr>";
                }
                ?>
            </tbody>
        </table>



        <?php
    }

    public function downlodepdf($file) {
        if (!is_loggedin()) {
            redirect('login');
        }

        if ($file != "") {
            $filename = base_url() . "upload/business_report/" . $file;
            header('Pragma: public');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Cache-Control: private', false); // required for certain browsers
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . basename($filename) . '";');
            header('Content-Transfer-Encoding: binary');
            header('Content-Length: ' . filesize($filename));

            readfile($filename);

            exit;
        } else {
            show_404();
        }
    }

    public function getjobs_track() {

        if (!is_loggedin()) {
            redirect('login');
        }
        $pid = $this->input->post("pid");
        $sample_track = $this->Logistic_model->get_val("SELECT logistic_sample_arrive.*,`phlebo_master`.`name` FROM logistic_sample_arrive INNER JOIN `phlebo_master` ON logistic_sample_arrive.`phlebo_fk`=`phlebo_master`.`id` WHERE `phlebo_master`.`status`='1' AND logistic_sample_arrive.`status`='1' AND logistic_sample_arrive.`barcode_fk`='" . $pid . "' order by logistic_sample_arrive.id asc");
        if ($sample_track != null) {
            $cnt1 = 0;
            foreach ($sample_track as $key) {
                if ($cnt1 != 0) {
                    echo "<br><i class='fa fa-arrow-down'></i><br>";
                } echo "<b>" . ucfirst($key["name"]) . "</b>" . " (<small>" . date("d-m-Y g:i A", strtotime($key["scan_date"])) . "</small>)";
                $cnt1++;
            }
        } else {
            echo "";
        }
    }

    function get_job_id($city = null) {
        $this->load->library("Util");
        $util = new Util();
        $new_id = $util->get_job_id($city);
        return $new_id;
    }

    function uplode_document($cid = "") {

        if (!is_loggedin()) {
            redirect('login');
        }
        $login_data = logindata();

        $filename = $_FILES['docks_uplode']['name'];
        $docksname = $this->input->post("docksname");

        if ($cid != "" && $filename != "") {
            $this->load->library('upload');

            $config['allowed_types'] = 'jpg|gif|png|jpeg';
            $config['upload_path'] = './upload/labducuments/';
            $config['file_name'] = time() . $_FILES['docks_uplode']['name'];
            $config['file_name'] = str_replace(' ', '_', $config['file_name']);
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('docks_uplode')) {
                $error = $this->upload->display_errors();
                $this->session->set_flashdata("error", $error);
                redirect("b2b/logistic/details/" . $cid, "refresh");
            } else {
                $doc_data = $this->upload->data();
                $photo = $doc_data['file_name'];
                $this->Logistic_model->master_fun_insert("documentall", array("jobsid" => $cid, "name" => $docksname, "document" => $photo, "creted_by" => $login_data["id"], "creteddate" => date("Y-m-d H:i:s")));
                $this->session->set_flashdata("success", array("successfully uploaded document"));
                redirect("b2b/logistic/details/" . $cid, "refresh");
            }
        } else {
            show_404();
        }
    }

    public function documentdelete() {
        $pid = $this->input->post("pid");
        $insert = $this->Logistic_model->master_fun_update("documentall", array("id" => $pid), array("status" => '0'));
        if ($insert) {
            echo "1";
        } else {
            echo "0";
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
function sampledescti_export() {
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
		fputcsv($handle, array("No","Patientid","PatientName","Barcode","LogisticName","Scan Date","Testname","Price"));
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
				
		fputcsv($handle,array($i,ucfirst($row["id"]),ucfirst($row["customer_name"]),$row["barcode"],$row["name"],$row["scan_date"],$testname,$row["payable_amount"])); 
}
 	fclose($handle);
        exit;
    
	}	
}
