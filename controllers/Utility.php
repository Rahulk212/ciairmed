<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Utility extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('user_master_model');
        $this->load->helper('string');
        $this->load->helper('url');
        $this->load->model('patholab_api_model');
        //	$this->load->library('Barcode39');
        //	$this->load->library('Barcode128');
        //$this->load->helper('Barcode128');
    }

    function index($barcode = null) {
        /* 	
          $bc = new Barcode39("123456798");
          $filename="upload/barcode/123456798.jpeg";
          $bc->draw($filename);

          $data['barcode'] = base_url($filename);

          $this->load->view('barcode_view', $data);
         */
        /* 	
          echo $data['barcode']= bar128(stripslashes("1234567"));
          n
          $this->load->view('barcode_view', $data);
         */
        /* $bc = new Barcode128(); 
          echo '<div style="border:1px double #333; padding:5px;margin:5px auto;width:100%;">';
          echo $bc->bar128(stripslashes("123456798"));
          echo '</div>'; */
        $data['barcode'] = $this->bar128("123456");


        $this->load->view('barcode_view', $data);
    }

    function getBarcode() {
        
    }

    private $char128asc = ' !"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}~';
    private $char128wid = array(
        '212222', '222122', '222221', '121223', '121322', '131222', '122213', '122312', '132212', '221213', // 0-9 
        '221312', '231212', '112232', '122132', '122231', '113222', '123122', '123221', '223211', '221132', // 10-19 
        '221231', '213212', '223112', '312131', '311222', '321122', '321221', '312212', '322112', '322211', // 20-29 			
        '212123', '212321', '232121', '111323', '131123', '131321', '112313', '132113', '132311', '211313', // 30-39 
        '231113', '231311', '112133', '112331', '132131', '113123', '113321', '133121', '313121', '211331', // 40-49 
        '231131', '213113', '213311', '213131', '311123', '311321', '331121', '312113', '312311', '332111', // 50-59 
        '314111', '221411', '431111', '111224', '111422', '121124', '121421', '141122', '141221', '112214', // 60-69 
        '112412', '122114', '122411', '142112', '142211', '241211', '221114', '413111', '241112', '134111', // 70-79 
        '111242', '121142', '121241', '114212', '124112', '124211', '411212', '421112', '421211', '212141', // 80-89 
        '214121', '412121', '111143', '111341', '131141', '114113', '114311', '411113', '411311', '113141', // 90-99
        '114131', '311141', '411131', '211412', '211214', '211232', '23311120');        // 100-106

////Define Function

    function bar128($text) {      // Part 1, make list of widths
        $char128asc = ' !"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}~';
        $char128wid = array(
            '212222', '222122', '222221', '121223', '121322', '131222', '122213', '122312', '132212', '221213', // 0-9 
            '221312', '231212', '112232', '122132', '122231', '113222', '123122', '123221', '223211', '221132', // 10-19 
            '221231', '213212', '223112', '312131', '311222', '321122', '321221', '312212', '322112', '322211', // 20-29 			
            '212123', '212321', '232121', '111323', '131123', '131321', '112313', '132113', '132311', '211313', // 30-39 
            '231113', '231311', '112133', '112331', '132131', '113123', '113321', '133121', '313121', '211331', // 40-49 
            '231131', '213113', '213311', '213131', '311123', '311321', '331121', '312113', '312311', '332111', // 50-59 
            '314111', '221411', '431111', '111224', '111422', '121124', '121421', '141122', '141221', '112214', // 60-69 
            '112412', '122114', '122411', '142112', '142211', '241211', '221114', '413111', '241112', '134111', // 70-79 
            '111242', '121142', '121241', '114212', '124112', '124211', '411212', '421112', '421211', '212141', // 80-89 
            '214121', '412121', '111143', '111341', '131141', '114113', '114311', '411113', '411311', '113141', // 90-99
            '114131', '311141', '411131', '211412', '211214', '211232', '23311120');        // 100-106

        $w = $char128wid[$sum = 104];       // START symbol
        $onChar = 1;

        for ($x = 0; $x < strlen($text); $x++)        // GO THRU TEXT GET LETTERS
            if (!( ($pos = strpos($char128asc, $text[$x])) === false )) { // SKIP NOT FOUND CHARS
                $w .= $char128wid[$pos];
                $sum += $onChar++ * $pos;
            }
        $w .= $char128wid[$sum % 103] . $char128wid[106];    //Check Code, then END

        $html = "<table cellpadding=0 cellspacing=0><tr>";
        for ($x = 0; $x < strlen($w); $x += 2)         // code 128 widths: black border, then white space
            $html .= "<td><div class=\"b128\" style=\"border-left-width:{$w[$x]};width:{$w[$x + 1]}\"></div>";
        return "$html<tr><td  colspan=" . strlen($w) . " align=center><font family=arial >$text</table>";
    }

    function getJobData() {
        $barcode = trim($this->input->get_post('barcode'));
        if (!empty($barcode)) {
            $job_data = $this->patholab_api_model->get_val("SELECT id,cust_fk,doctor,booking_info FROM job_master WHERE STATUS!='0' AND `barcode`='" . $barcode . "'");
            $emergency_tests = $this->patholab_api_model->master_fun_get_tbl_val("booking_info", array('id' => $job_data[0]["booking_info"]), array("id", "asc"));
            $f_data1 = array();
            if (!empty($emergency_tests[0]["family_member_fk"])) {
                $f_data = $this->patholab_api_model->master_fun_get_tbl_val("customer_family_master", array('id' => $emergency_tests[0]["family_member_fk"]), array("id", "asc"));
                $f_data1 = $this->patholab_api_model->master_fun_get_tbl_val("relation_master", array('id' => $f_data[0]["relation_fk"]), array("id", "asc"));
            }
            $account_holder_data = $this->patholab_api_model->master_fun_get_tbl_val("customer_master", array('id' => $job_data[0]["cust_fk"]), array("id", "asc"));
            $doctor_data = $this->patholab_api_model->master_fun_get_tbl_val("doctor_master", array('id' => $job_data[0]["doctor"]), array("id", "asc"));
            $relation = "Self";
            if (!empty($f_data1)) {
                $patient_name = ucfirst($f_data[0]["name"]);
                $patient_age = ucfirst($f_data[0]["dob"]);
                $patient_gender = ucfirst($f_data[0]["gender"]);
            } else {
                $patient_name = ucfirst($account_holder_data[0]["full_name"]);
                $patient_gender = ucfirst($account_holder_data[0]["gender"]);
                $patient_age = ucfirst($account_holder_data[0]["dob"]);
            }
            $this->load->library("util");
            $util = new Util;
            $data["age"] = $util->get_age($patient_age);
            if (!empty($patient_age)) {
                $p_age = $data["age"][0];
            } else {
                $p_age = "NA";
            }
            $p_gender = "";
            if (strtoupper($patient_gender) == "MALE") {
                $p_gender = "M";
            }
            if (strtoupper($patient_gender) == "FEMALE") {
                $p_gender = "F";
            }
            /*  echo $this->json_data("1", "", array(array("patient_name" => $patient_name, "patient_age" => $p_age, "patient_gender" => $p_gender, "patient_doctor" => $doctor_data[0]["full_name"]))); */
            $data['patient_name'] = $patient_name;
            $data['patient_age'] = $p_age;
            $data['patient_gender'] = $patient_gender;
            $data['barcode'] = $this->bar128($barcode);
            $this->load->view('barcode_view', $data);
        } else {
            //echo $this->json_data("0", "Barcode is required.", "");
        }
    }

    function getJobBarcode() {
        $id = trim($this->input->get_post('id'));
        if (!empty($id)) {
            $job_data = $this->patholab_api_model->get_val("SELECT id,branch_fk,cust_fk,doctor,booking_info,barcode,test_city FROM job_master WHERE STATUS!='0' AND `id`='" . $id . "'");
            /* Check Barcode Start */
            if (empty($job_data[0]["barcode"])) {
                $b_data = $this->patholab_api_model->master_fun_get_tbl_val("branch_master", array('id' => $job_data[0]["branch_fk"]), array("id", "asc"));
                $barcode = $job_data[0]["test_city"] . $b_data[0]["branch_code"] . $id;
                $this->patholab_api_model->master_fun_update("job_master", array("id" => $id), array("barcode" => $barcode));
            } else {
                $barcode = $job_data[0]["barcode"];
            }
            /* END */
            $emergency_tests = $this->patholab_api_model->master_fun_get_tbl_val("booking_info", array('id' => $job_data[0]["booking_info"]), array("id", "asc"));
            $f_data1 = array();
            if (!empty($emergency_tests[0]["family_member_fk"])) {
                $f_data = $this->patholab_api_model->master_fun_get_tbl_val("customer_family_master", array('id' => $emergency_tests[0]["family_member_fk"]), array("id", "asc"));
                $f_data1 = $this->patholab_api_model->master_fun_get_tbl_val("relation_master", array('id' => $f_data[0]["relation_fk"]), array("id", "asc"));
            }
            $account_holder_data = $this->patholab_api_model->master_fun_get_tbl_val("customer_master", array('id' => $job_data[0]["cust_fk"]), array("id", "asc"));
            $doctor_data = $this->patholab_api_model->master_fun_get_tbl_val("doctor_master", array('id' => $job_data[0]["doctor"]), array("id", "asc"));
            $relation = "Self";
            if (!empty($f_data1)) {
                $patient_name = ucfirst($f_data[0]["name"]);
                $patient_age = ucfirst($f_data[0]["dob"]);
                $patient_gender = ucfirst($f_data[0]["gender"]);
            } else {
                $patient_name = ucfirst($account_holder_data[0]["full_name"]);
                $patient_gender = ucfirst($account_holder_data[0]["gender"]);
                $patient_age = ucfirst($account_holder_data[0]["dob"]);
            }
            $this->load->library("util");
            $util = new Util; 
            $data["age"] = $util->get_age($patient_age);
            $data["age_type"] = 'Y';
            if (!empty($patient_age)) {
                $p_age = $data["age"][0];
                if ($data["age"][0] != 0) {
                    $data["age_type"] = 'Y';
                } else if ($data["age"][1] != 0) {
                    $data["age_type"] = 'M';
                } else if ($data["age"][2] != 0) {
                    $data["age_type"] = 'D';
                }
            } else {
                $p_age = "NA";
            }
            $p_gender = "";
            if (strtoupper($patient_gender) == "MALE") {
                $p_gender = "M";
            }
            if (strtoupper($patient_gender) == "FEMALE") {
                $p_gender = "F";
            }
            /*  echo $this->json_data("1", "", array(array("patient_name" => $patient_name, "patient_age" => $p_age, "patient_gender" => $p_gender, "patient_doctor" => $doctor_data[0]["full_name"]))); */
            $data['patient_name'] = $patient_name;
            $data['patient_age'] = $p_age;
            $data['patient_gender'] = $p_gender;
            $data['barcode'] = $this->bar128($barcode);
            $data["doctor"] = $doctor_data[0]["full_name"];
            $this->load->view('barcode_view', $data);
        } else {
            show_error("Invalid request.");
        }
    }

}
