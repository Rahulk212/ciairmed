<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Add_result extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('user_model');
        $this->load->model('user_test_master_model');
        $this->load->model('add_result_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('pushserver');
        $this->load->library('email');
        $this->load->helper('string');
        $data["login_data"] = logindata();
    }

    function test_details() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data['cid'] = $this->uri->segment(3);
        $data['query'] = $this->add_result_model->job_details($data['cid']);
        $data['parameter_list'] = array();
        $tid = explode(",", $data['query'][0]['testid']);
        foreach ($tid as $tst_id) {
            //$para = $this->add_result_model->get_val("SELECT p.id as pid,p.test_fk,p.parameter_name,p.parameter_range,p.parameter_unit,t.id as tid,t.test_name FROM test_parameter_master as p left join test_master as t on t.id = p.test_fk WHERE p.status='1' AND p.test_fk='" . $tst_id . "' order by p.id ASC");
            $para = $this->add_result_model->get_val("SELECT p.id as pid,p.test_fk,p.parameter_name,p.parameter_range,p.parameter_unit,t.id as tid,t.test_name FROM test_parameter_master as p left join test_master as t on t.id = p.test_fk WHERE p.status='1' AND p.test_fk='" . $tst_id . "' order by p.id ASC");
            array_push($data['parameter_list'], $para);
        }
        $data['result_list'] = $this->add_result_model->master_fun_get_tbl_val("user_test_result", array('status' => 1,'job_id' => $data['cid']), array("id", "asc"));
        //echo "<pre>"; print_r($data['parameter_list']); die();
        $data['unit_list'] = $this->add_result_model->unit_list();
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('view_collected', $data);
        $this->load->view('footer');
    }

    function add_parameter_data() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $count = $this->input->post('count_par');
        $test_id = $this->input->post('test_id');
        $job_id = $this->input->post('job_id');
        for($i=1;$i<=$count;$i++) {
            $data["login_data"] = logindata();
            $parname = $this->input->post('par_name_'.$i);
            $parmin = $this->input->post('par_min_'.$i);
            $parmax = $this->input->post('par_max_'.$i);
            $parunit = $this->input->post('par_unit_'.$i);
            $range = $parmin . "-" . $parmax;
            $data = array(
                "test_fk" => $test_id,
                "parameter_name" => $parname,
                "parameter_range" => $range,
                "parameter_unit" => $parunit,
                "created_by" => $data["login_data"]["id"],
                "created_date" => date("Y-m-d H:i:s")
            );
            $insert = $this->add_result_model->master_fun_insert("test_parameter_master", $data);
            if($insert) {
                $data["login_data"] = logindata();
                $parvalue = $this->input->post('par_value_'.$i);
                $parcondi = $this->input->post('par_condi_'.$i);
                $data = array(
                    "job_id" => $job_id,
                    "parameter_id" => $insert,
                    "value" => $parvalue,
                    "condition" => $parcondi,
                    "created_by" => $data["login_data"]["id"],
                    "created_date" => date("Y-m-d H:i:s")
                );
                $val_add=$this->add_result_model->master_fun_insert("user_test_result", $data);
            }
        }
        if($val_add) {
            redirect('add_result/test_details/'.$job_id);
        }
    }

    function sample_collected_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        //print_r($data["login_data"]); die();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

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

        if ($user != "" || $date != "" || $city != "" || $status != "" || $mobile != "" || $t_id != "" || $p_id != "" || $p_amount != "") {
            $total_row = $this->add_result_model->num_row_srch_job_list($user, $date, $city, $status, $mobile, $t_id, $p_id, $p_amount);
            $config = array();
            $get = $_GET;
            unset($get['offset']);
            $config["base_url"] = base_url() . "add_result/sample_collected_list?" . http_build_query($get);
            $config["total_rows"] = $total_row;
            $config["per_page"] = 10;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->add_result_model->row_srch_job_list($user, $date, $city, $status, $mobile, $t_id, $p_id, $p_amount, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;
        } else {
            $total_row = $this->add_result_model->num_srch_job_list();
            $config["base_url"] = base_url() . "add_result/sample_collected_list";
            $config["total_rows"] = $total_row;
            $config["per_page"] = 10;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->add_result_model->srch_job_list($config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;
        }
        $data['customer'] = $this->add_result_model->master_fun_get_tbl_val("customer_master", array('status' => 1), array("full_name", "asc"));
        $data['city'] = $this->add_result_model->master_fun_get_tbl_val("city", array('status' => 1), array("id", "asc"));
        $cnt = 0;
        foreach ($data['query'] as $key) {
            $w_prc = 0;
            /* Count booked test price */
            $booked_tests = $this->add_result_model->master_fun_get_tbl_val("wallet_master", array('job_fk' => $key["id"]), array("id", "asc"));
            $f_data = $this->add_result_model->master_fun_get_tbl_val("customer_family_master", array('id' => $emergency_tests[0]["family_member_fk"]), array("id", "asc"));
            $f_data1 = $this->add_result_model->master_fun_get_tbl_val("relation_master", array('id' => $f_data[0]["relation_fk"]), array("id", "asc"));
            $relation = "Self";
            if (!empty($f_data1)) {
                $relation = ucfirst($f_data1[0]["name"] . " (" . $f_data[0]["name"] . ")");
            }
            $data['query'][$cnt]["relation"] = $relation;
            foreach ($booked_tests as $tkey) {
                if ($tkey["debit"]) {
                    $w_prc = $w_prc + $tkey["debit"];
                }
            }
            $data['query'][$cnt]["cut_from_wallet"] = $w_prc;
            $cnt++;
        }
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('collected_job_list', $data);
        $this->load->view('footer');
    }
    function add_value_exists() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $test_count = $this->input->post('test_count');
        $para_job_id = $this->input->post('para_job_id');
        for ($i = 0; $i < $test_count; $i++) {
            $cc = $i + 1;
            $para_count = $this->input->post('para_count_'.$cc);
            $test_id = $this->input->post('test_id_'.$cc);
            for ($j = 1; $j <= $para_count; $j++) {
                $data["login_data"] = logindata();
                $value_add = $this->input->post('value_add_'.$i.'_'.$j);
                $para_id = $this->input->post('para_id_'.$i.'_'.$j);
                $para_condition = $this->input->post('para_condition_'.$i.'_'.$j);
                $data = array(
                    "job_id" => $para_job_id,
                    "parameter_id" => $para_id,
                    "value" => $value_add,
                    "condition" => $para_condition,
                    "created_by" => $data["login_data"]["id"],
                    "created_date" => date("Y-m-d H:i:s")
                );
                $val_add=$this->add_result_model->master_fun_insert("user_test_result", $data);
            }
        }
        if($val_add) {
            redirect('add_result/test_details/'.$para_job_id);
        }
    }
    function pdf_report($id) {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $cid=$data['cid'] = $this->uri->segment(3);
        $data['query'] = $this->add_result_model->job_details($data['cid']);
        //echo "<pre>"; print_r($data['query']);
        $data['parameter_list'] = array();
        $tid = explode(",", $data['query'][0]['testid']);
        foreach ($tid as $tst_id) {
            $para = $this->add_result_model->get_val("SELECT p.test_fk,p.parameter_name,p.parameter_range,p.parameter_unit,t.id as tid,t.test_name,r.value,r.condition FROM test_parameter_master as p left join test_master as t on t.id = p.test_fk left join user_test_result as r on r.parameter_id=p.id WHERE p.status='1' AND r.job_id='" . $cid . "' AND p.test_fk='" . $tst_id . "' order by p.id ASC");
            //$para = $this->add_result_model->get_val("SELECT p.id as pid,p.test_fk,p.parameter_name,p.parameter_range,p.parameter_unit,t.id as tid,t.test_name FROM test_parameter_master as p left join test_master as t on t.id = p.test_fk WHERE p.status='1' AND p.test_fk='" . $tst_id . "' order by p.id ASC");
            array_push($data['parameter_list'], $para);
        }
        //echo "<pre>"; print_r($data['parameter_list']); die();
       // date_default_timezone_set("UTC");
        //$new_time = date("Y-m-d H:i:s", strtotime('+3 hours'));
        $pdfFilePath = FCPATH . "/upload/report/".$data['query'][0]['order_id']."_result.pdf";
        $data['page_title'] = 'AirmedLabs'; // pass data to the view
        ini_set('memory_limit', '32M'); // boost the memory limit if it's low <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="?" draggable="false" class="emoji">
        $html = $this->load->view('result_pdf', $data, true); // render the view into HTML
        //$param = '"en-GB-x","A4","","",10,10,0,10,6,3,"P"'; // Landscape
        //$lorem = utf8_encode($html); // render the view into HTML
        //$html = "<!DOCTYPE html>                         <html><body>\u0627\u0644\u0643\u0647\u0631\u0628\u0627\u0621 \u0648 \u0627\u0644\u0633\u0628\u0627\u0643\u0629</body></html>      ";
        $this->load->library('pdf');

        $pdf = $this->pdf->load();
        $pdf->autoScriptToLang = true;
        $pdf->baseScript = 1; // Use values in classes/ucdn.php  1 = LATIN
        $pdf->autoVietnamese = true;
        $pdf->autoArabic = true;

        $pdf->autoLangToFont = true;
        //$pdf->AddPage('L','',1,'i','on'); 
        //$pdf->SetDirectionality('rtl');
        /* $pdf->AddPage('P', // L - landscape, P - portrait
          '', '', '', '', 00, // margin_left
          0, // margin right
          0, // margin top
          0, // margin bottom
          0, // margin header
          0); */

        //  $pdf->SetDisplayMode('fullpage');
        // $pdf->h2toc = array('H2' => 0);
        //nishit index start
        // $html = '';
        // Split $lorem into words
        $pdf->WriteHTML($html);
        //nishit index end
        //   $pdf->debug = true;

        //$pdf->SetFooter('www.' . $_SERVER['HTTP_HOST'] . '||' . $new_time); // Add a footer for good measure <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="?" draggable="false" class="emoji">
        // $pdf->WriteHTML($html); // write the HTML into the PDF
        $pdf->Output($pdfFilePath, 'F'); // save to file because we can
        $name= $data['query'][0]['order_id']."_result.pdf";
        $count = $this->add_result_model->master_fun_get_tbl_val("report_master", array('job_fk' => $cid), array("id", "asc"));
        if(!empty($count)) {
            $data1 = array('job_fk' => $cid,'report' => $name,'status' => 1,"original"=>$name,"type"=>"c","updated_date"=>date("Y-m-d H:i:s"));
            $this->add_result_model->master_fun_update('report_master', array('job_fk',$cid), $data1);
        } else {
            $data1 = array('job_fk' => $cid,'report' => $name,'status' => 1,"original"=>$name,"type"=>"c","created_date"=>date("Y-m-d H:i:s"));
            $this->add_result_model->master_fun_insert("report_master", $data1);
        }
        redirect("/upload/report/".$data['query'][0]['order_id']."_result.pdf");
    }

}

?>