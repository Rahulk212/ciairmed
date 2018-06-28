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
            $config["base_url"] = base_url() . "b2b/Logistic/sample_list?search=$search&name=" . $data['name'] . "&barcode=" . $data['barcode'] . "&date=" . $data['date'] . "&from=" . $data['from'];
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
            $test = $this->input->post("test");
			$referby=$this->input->post("referby");
            $order_id = $this->get_job_id();
            $date = date('Y-m-d H:i:s');

            $price = 0;
            foreach ($test as $key) {
                $tn = explode("-", $key);
                if ($tn[0] == 't') {

                    /* $result = $this->job_model->get_val("SELECT `sample_test_master`.`id`,`sample_test_master`.`test_name`,`sample_test_master`.`price`,`sample_test_master`.`status` FROM `sample_test_master` WHERE `sample_test_master`.`status`='1' AND `sample_test_master`.`id`='" . $tn[1] . "'");
                      $price += $result[0]["price"];
                      $test_package_name[] = $result[0]["test_name"]; */

                    $result = $this->job_model->get_val("SELECT price,b2b_price FROM sample_test_city_price where status='1' and id='" . $tn[1] . "';");

                    if ($result[0]["b2b_price"] != "") {
                        $price += $result[0]["b2b_price"];
                    } else {
                        $price += $result[0]["price"];
                    }
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
                "barcode_fk" => $this->uri->segment(4),
                "customer_name" => $customer_name,
                "customer_mobile" => $customer_mobile,
                "customer_email" => $customer_email,
                "customer_gender" => $customer_gender,
                "customer_dob" => $dob,
                "customer_address" => $address,
				"doctor"=>$referby,
                "price" => $price,
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
            foreach ($test as $key) {
                $tn = explode("-", $key);
                if ($tn[0] == 't') {
                    /* $result = $this->job_model->get_val("SELECT `sample_test_master`.`id`,`sample_test_master`.`test_name`,`sample_test_master`.`price`,`sample_test_master`.`status` FROM `sample_test_master` WHERE `sample_test_master`.`status`='1' AND `sample_test_master`.`id`='" . $tn[1] . "'");
                      $this->job_model->master_fun_insert("sample_job_test", array('job_fk' => $insert, "test_fk" => $tn[1], "price" => $result[0]["price"])); */

                    $result = $this->job_model->get_val("SELECT price,b2b_price,test_fk FROM sample_test_city_price where status='1' and id='" . $tn[1] . "';");
                    if ($result[0]["b2b_price"] != "") {
                        $price1 = $result[0]["b2b_price"];
                    } else {
                        $price1 = $result[0]["price"];
                    }
                    $this->job_model->master_fun_insert("sample_job_test", array('job_fk' => $insert, "test_fk" => $tn[1], "price" => $price1));
                }
                if ($tn[0] == 'p') {
                    $this->job_model->master_fun_insert("sample_book_package", array("cust_fk" => $uid, "package_fk" => $tn[1], 'job_fk' => $insert, "status" => "1", "type" => "2"));
                }
            }
            $this->session->set_flashdata("success", array("Test successfully Booked."));
            redirect("b2b/Logistic/details/" . $this->uri->segment(4));
        } else {
            $data["id"] = $this->uri->segment(4);
            $data['barcode_detail'] = $this->job_model->get_val("SELECT `logistic_log`.*,`phlebo_master`.`name`,`phlebo_master`.`mobile`,`collect_from`.`name` AS `c_name` FROM `logistic_log` 
left JOIN `phlebo_master` ON `logistic_log`.`phlebo_fk`=`phlebo_master`.`id` 
left JOIN `collect_from` ON `logistic_log`.`collect_from`=`collect_from`.`id`
left join sample_job_master on sample_job_master.barcode_fk = logistic_log.id
WHERE  `logistic_log`.`status`='1' and `logistic_log`.`id`='" . $data["id"] . "'");
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
                foreach ($job_test as $tkey) {
                    // echo "SELECT sample_test_master.`id`,`sample_test_master`.`test_name`,`sample_test_city_price`.`price`,`sample_test_master`.`status` FROM `sample_test_master` INNER JOIN `sample_test_city_price` ON `sample_test_city_price`.`test_fk`=sample_test_master.id WHERE `sample_test_master`.`status`='1' AND `sample_test_city_price`.`id`='" . $tkey["test_fk"] . "'"; die();
                    $test_info = $this->job_model->get_val("SELECT sample_test_master.`id`,`sample_test_master`.`test_name`,`sample_test_city_price`.`price`,`sample_test_city_price`.`b2b_price`,`sample_test_city_price`.`special_price`,`sample_test_master`.`status` FROM `sample_test_master` INNER JOIN `sample_test_city_price` ON `sample_test_city_price`.`test_fk`=sample_test_master.id WHERE `sample_test_master`.`status`='1' AND `sample_test_city_price`.`id`='" . $tkey["test_fk"] . "'");
                    $tkey["info"] = $test_info;
                    $tst_name[] = $tkey;
                }
                $data['job_details'][0]["test_list"] = $tst_name;
                $job_packages = $this->job_model->master_fun_get_tbl_val("sample_book_package", array("job_fk" => $key["id"], 'status' => 1), array("id", "asc"));
                $data['job_details'][0]["package_list"] = $job_packages;
                $cnt++;
            }
            //echo "<pre>"; print_R($data['job_details']); die();
            $data['success'] = $this->session->userdata("success");
            if ($this->session->userdata("error") != '') {
                $data["error"] = $this->session->userdata("error");
                $this->session->unset_userdata("error");
            }
            $data['jobspdf'] = $this->job_model->master_fun_get_tbl_val("b2b_jobspdf", array("job_fk" => $data["id"], 'status' => 1), array("id", "asc"));
            $this->load->view('b2b/header');
            $this->load->view('b2b/nav_logistic', $data);
            $this->load->view('b2b/sample_register', $data);
            $this->load->view('b2b/footer');
        }
    }

    public function pdfapprove($id = null) {
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
			$referby=$this->input->post("referby");
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
            $price = 0;
            foreach ($test as $key) {
                $tn = explode("-", $key);

                if ($tn[0] == 't') {
                    /* $result = $this->job_model->get_val("SELECT `sample_test_master`.`id`,`sample_test_master`.`test_name`,`sample_test_master`.`price`,`sample_test_master`.`status` FROM `sample_test_master` WHERE `sample_test_master`.`status`='1' AND `sample_test_master`.`id`='" . $tn[1] . "'"); */

                    $result = $this->job_model->get_val("SELECT price,b2b_price FROM sample_test_city_price where status='1' and id='" . $tn[1] . "';");

                    if ($result[0]["b2b_price"] != "") {
                        $price += $result[0]["b2b_price"];
                    } else {
                        $price += $result[0]["price"];
                    }
                    // $test_package_name[] = $result[0]["test_name"];
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
                "barcode_fk" => $barcd,
                "order_id" => $order_id,
                "customer_name" => $customer_name,
                "customer_mobile" => $customer_mobile,
                "customer_email" => $customer_email,
                "customer_gender" => $customer_gender,
                "customer_dob" => $dob,
                "customer_address" => $address,
				"doctor"=>$referby,
                "price" => $price,
                "status" => '1',
                "discount" => $discount,
                "payable_amount" => $payable,
                "added_by" => $data["login_data"]["id"],
                "note" => $note,
                "date" => $date
            );

            $insert = $this->job_model->master_fun_insert("sample_job_master", $data);
            foreach ($test as $key) {
                $tn = explode("-", $key);

                if ($tn[0] == 't') {

                    /* $result = $this->job_model->get_val("SELECT `sample_test_master`.`id`,`sample_test_master`.`test_name`,`sample_test_master`.`price`,`sample_test_master`.`status` FROM `sample_test_master` WHERE `sample_test_master`.`status`='1' AND `sample_test_master`.`id`='" . $tn[1] . "'"); */
                    $result = $this->job_model->get_val("SELECT price,b2b_price,test_fk FROM sample_test_city_price where status='1' and id='" . $tn[1] . "';");
                    if ($result[0]["b2b_price"] != "") {
                        $price1 = $result[0]["b2b_price"];
                    } else {
                        $price1 = $result[0]["price"];
                    }
                    $this->job_model->master_fun_insert("sample_job_test", array('job_fk' => $insert, "test_fk" => $tn[1], "price" => $price1));
                }
                if ($tn[0] == 'p') {
                    $this->job_model->master_fun_insert("sample_book_package", array("cust_fk" => $uid, "package_fk" => $tn[1], 'job_fk' => $insert, "status" => "1", "type" => "2"));
                }
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

    /* Sample list end */

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
            $config["per_page"] = 50;
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
            $config["per_page"] = 50;
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
        if ($this->form_validation->run() != FALSE) {
            $lab_name = $this->input->post('lab_name');
            $person_name = $this->input->post('person_name');
            $mobile_number = $this->input->post('mobile_number');
            $alternate_number = $this->input->post('alternate_number');
            $address = $this->input->post('address');
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $city = $this->input->post('city');
            $data1 = array(
                "name" => $lab_name,
                "contact_person_name" => $person_name,
                "city" => $city,
                "mobile_number" => $mobile_number,
                "alternate_number" => $alternate_number,
                "address" => $address,
                "email" => $email,
                "password" => $password,
                "createddate" => date("Y-m-d H:i:s")
            );
            $data['query'] = $this->Logistic_model->master_fun_insert("collect_from", $data1);
            $this->session->set_flashdata("success", array("Lab successfully added."));
            redirect("b2b/Logistic/lab_list", "refresh");
        } else {

            $data['cityall'] = $this->Logistic_model->master_fun_get_tbl_val("b2b_test_cities", array('status' => '1'), array("name", "asc"));

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

        if ($this->form_validation->run() != FALSE) {
            $lab_name = $this->input->post('lab_name');
            $person_name = $this->input->post('person_name');
            $mobile_number = $this->input->post('mobile_number');
            $alternate_number = $this->input->post('alternate_number');
            $address = $this->input->post('address');
            $email = $this->input->post('email');
            $city = $this->input->post('city');
            $password = $this->input->post('password');
            $data1 = array(
                "name" => $lab_name,
                "contact_person_name" => $person_name,
                "mobile_number" => $mobile_number,
                "city" => $city,
                "alternate_number" => $alternate_number,
                "address" => $address,
                "email" => $email,
                "password" => $password
            );
            $this->Logistic_model->master_fun_update("collect_from", array("id" => $id), $data1);
            $this->session->set_flashdata("success", array("Lab successfully updated."));
            redirect("b2b/Logistic/lab_list", "refresh");
        } else {
            $data['query'] = $this->Logistic_model->master_fun_get_tbl_val("collect_from", array('id' => $id), array("id", "asc"));
            $data['cityall'] = $this->Logistic_model->master_fun_get_tbl_val("b2b_test_cities", array('status' => '1'), array("name", "asc"));

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
        /* $data['test'] = $this->Logistic_model->get_val("SELECT `sample_test_master`.`id`,`sample_test_master`.`test_name`,`sample_test_master`.`price`,`sample_test_master`.`status`,sample_test_master.thyrocare_code FROM `sample_test_master` WHERE `sample_test_master`.`status`='1' AND `sample_test_master`.`price`>'0' AND `sample_test_master`.`lab_fk`='" . $lab . "'"); */
        $data['test'] = $this->Logistic_model->get_val("SELECT l.id,l.`price`,l.`special_price`,t.`test_name`,l.b2b_price,t.thyrocare_code FROM sample_test_city_price l LEFT JOIN sample_test_master t ON t.`id`=l.`test_fk` WHERE l.`status`='1' and l.lab_fk='$lab' ORDER BY l.id desc");
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
                            $getdetils->mobile, $getdetils->address,strtoupper($getdetils->doctor)
                        );
							$header = preg_replace($find, $replace, $content[0]["without_header"]);
                        $pdf->SetHTMLHeader($header);
    