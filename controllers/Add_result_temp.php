<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Add_result_temp extends CI_Controller {

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
        echo "start-" . date("H:i:sa");
        ;


        if ($data["login_data"]["id"] == "12") {

            echo "start-" . date("H:i:sa");
            ;
        }
        if ($this->session->userdata("closeFancyBox")) {
            $data["closeFancyBox"] = $this->session->userdata("closeFancyBox");
            $this->session->unset_userdata("closeFancyBox");
        }
        $data["login_data"] = logindata();
        $data['cid'] = $this->uri->segment(3);
        $data['query'] = $this->add_result_model->job_details($data['cid']);
        $data['processing_center'] = $this->add_result_model->master_fun_get_tbl_val("processing_center", array('status' => 1, 'lab_fk' => $data['query'][0]["branch_fk"]), array("id", "asc"));
        if (empty($data['processing_center'])) {
            $processing_center = '1';
        } else {
            $processing_center = $data['processing_center'][0]["branch_fk"];
        }
        $this->load->library("util");
        $util = new Util;
        $data["age"] = $util->get_age($data['query'][0]["dob"]);
        $data['user_booking_info'] = $this->add_result_model->master_fun_get_tbl_val("booking_info", array('status' => 1, 'id' => $data['query'][0]["booking_info"]), array("id", "asc"));
        $data['user_data'] = $this->add_result_model->master_fun_get_tbl_val("customer_master", array('status' => 1, 'id' => $data['query'][0]["custid"]), array("id", "asc"));
        if (empty($data['user_data'][0]["gender"]) && empty($data['user_data'][0]["age"])) {
            $data['user_data'][0]["gender"] = 'male';
            $data['user_data'][0]["age"] = 24;
            $data['user_data'][0]["age_type"] = 'Y';
        }

        if ($data['user_booking_info'][0]["family_member_fk"] != 0) {
            $data['user_family_info'] = $this->add_result_model->master_fun_get_tbl_val("customer_family_master", array('status' => 1, 'id' => $data['user_booking_info'][0]["family_member_fk"]), array("id", "asc"));
            $data['user_data'][0]["gender"] = $data['user_family_info'][0]["gender"];
            $data['user_data'][0]["age"] = $data['user_family_info'][0]["age"];
            $data['user_data'][0]["age_type"] = $data['user_family_info'][0]["age_type"];
            $data['user_data'][0]["full_name"] = $data['user_family_info'][0]["name"];
            $data['user_data'][0]["email"] = $data['user_family_info'][0]["email"];
            $data['user_data'][0]["phone"] = $data['user_family_info'][0]["phone"];
            $data['user_data'][0]["dob"] = $data['user_family_info'][0]["dob"];
        }
        if (empty($data['user_data'][0]["dob"])) {
            $data['user_data'][0]["dob"] = '1992-09-30';
        }
        /* Check bitrth date start */
        $this->load->library("util");
        $util = new Util;

        $age = $util->get_age($data['user_data'][0]["dob"]);

        if ($data["login_data"]["id"] == "12") {
            print_r($age);
        }
        $ageinDays = 0;
        if ($age[0] != 0) {
            $ageinDays += ($age[0] * 365);
            $data['user_data'][0]["age"] = $age[0];
            $data['user_data'][0]["age_type"] = 'Y';
        }
        if ($age[0] == 0 && $age[1] != 0) {
            $ageinDays += ($age[1] * 30);
            $data['user_data'][0]["age"] = $age[1];
            $data['user_data'][0]["age_type"] = 'M';
        }
        if ($age[0] == 0 && $age[1] == 0 && $age[2] != 0) {
            $ageinDays += ($age[2]);
            $data['user_data'][0]["age"] = $age[2];
            $data['user_data'][0]["age_type"] = 'D';
        }

        if ($data["login_data"]["id"] == "12") {
            print_r($age);
            echo $ageinDays;
        }

        /* Check birth date end */
        $tid = array();
        $data['parameter_list'] = array();
        if (trim($data['query'][0]['testid']) == null && $data['query'][0]["packageid"] != null) {
            $package_id = $data['query'][0]["packageid"];
            $pid = explode("%", $data['query'][0]['packageid']);
            foreach ($pid as $pkey) {
                $p_test = $this->add_result_model->get_val("SELECT `package_test`.`test_fk` FROM `package_test` INNER JOIN `test_master` ON `package_test`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `package_test`.`status`='1' AND `package_test`.`package_fk`='" . $pkey . "'");
                foreach ($p_test as $tp_key) {
                    $tid[] = $tp_key["test_fk"];
                }
            }
        } else if (trim($data['query'][0]['testid']) != null && $data['query'][0]["packageid"] != null) {

            $tid = explode(",", $data['query'][0]['testid']);
            $package_id = $data['query'][0]["packageid"];
            $pid = explode("%", $data['query'][0]['packageid']);
            foreach ($pid as $pkey) {
                $p_test = $this->add_result_model->get_val("SELECT `package_test`.`test_fk` FROM `package_test` INNER JOIN `test_master` ON `package_test`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `package_test`.`status`='1' AND `package_test`.`package_fk`='" . $pkey . "'");

                foreach ($p_test as $tp_key) {
                    $tid[] = $tp_key["test_fk"];
                }
            }
        } else {
            $tid = explode(",", $data['query'][0]['testid']);
        }
        //print_R($tid); die();
        foreach ($tid as $t_key) {
            $p_test = $this->add_result_model->get_val("SELECT sub_test_master.* FROM `sub_test_master` INNER JOIN `test_master` ON `sub_test_master`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `sub_test_master`.`status`='1' AND `sub_test_master`.`test_fk`='" . $t_key . "'");
            foreach ($p_test as $tp_key) {
                $tid[] = $tp_key["sub_test"];
            }
        }
        $tid = array_unique($tid);
        $cnt = 0;
        $new_data_array = array();
        foreach ($tid as $tst_id) {
            $get_test_parameter = $this->add_result_model->get_val("SELECT `test_parameter`.*,`test_master`.`test_name` FROM `test_parameter` INNER JOIN `test_master` ON `test_parameter`.`test_fk`=`test_master`.`id` WHERE `test_parameter`.`status`='1' AND `test_master`.`status`='1' AND `test_parameter`.`test_fk`='" . $tst_id . "' and `test_parameter`.`center`='" . $processing_center . "' order by `test_parameter`.order asc");


            $pid = array();
            foreach ($get_test_parameter as $tp_key) {
                $pid[] = $tp_key["parameter_fk"];
            }
            if (!empty($pid)) {
                $para = $this->add_result_model->get_val("SELECT * FROM `test_parameter_master` WHERE `status`='1' AND id IN (" . implode(",", $pid) . ") ORDER BY FIELD(id," . implode(",", $pid) . ")");
                if (!empty($para)) {
                    $cnt_1 = 0;
                    foreach ($para as $para_key) {
                        $formula = $this->add_result_model->get_val("SELECT * from use_formula where test_fk='" . $tst_id . "' and job_fk='" . $data['cid'] . "' and status='1'");
                        $get_test_parameter[$cnt_1]['use_formula'] = $formula[0]["use_formula"];
                        $get_test_parameter[$cnt_1]['on_new_page'] = $formula[0]["on_new_page"];
                        $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                        $get_test_parameter[$cnt_1]['graph'] = $graph_pic;
                        $get_test_parameter[$cnt_1]['graph_id'] = $formula[0]["id"];
                        $get_test_parameter1 = $get_test_parameter[$cnt_1];
//print_R($get_test_parameter1); die();
//echo "SELECT * from user_test_result where test_id='".$tst_id."' and parameter_id='" . $para_key["id"] . "' and job_id='".$data['cid']."' and  status='1'";
                        $para_user_val = $this->add_result_model->get_val("SELECT * from user_test_result where test_id='" . $tst_id . "' and parameter_id='" . $para_key["id"] . "' and job_id='" . $data['cid'] . "' and  status='1'");
                        $para[$cnt_1]["user_val"] = $para_user_val;
                        //   $para_ref_rng = $this->add_result_model->get_val("SELECT * FROM `parameter_referance_range` WHERE `status`='1' AND parameter_fk='" . $para_key["id"] . "' order by gender asc");
                        $final_qry = "SELECT * FROM `parameter_referance_range` WHERE STATUS='1' AND `parameter_fk`='" . $para_key["id"] . "'";

                        if ($data['user_data'][0]["age"] == '') {
                            $data['user_data'][0]["age"] = 0;
                        }
                        /* if ($data['user_data'][0]["age_type"] == 'D') {
                          $final_qry .= " AND gender='N'  AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='D'";
                          } else if ($data['user_data'][0]["age_type"] == 'M') {
                          $final_qry .= " AND gender='C' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='M'";
                          $data["common"] = 0;
                          } else if (strtoupper($data['user_data'][0]["gender"]) == 'MALE' && $data['user_data'][0]["age_type"] == 'Y') {
                          $final_qry .= " AND gender='M' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } else if (strtoupper($data['user_data'][0]["gender"]) == 'FEMALE' && $data['user_data'][0]["age_type"] == 'Y') {
                          $final_qry .= " AND gender='F' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          }
                          else if ($para_ref_rng[0]["gender"] == 'B' && $data['user_data'][0]["age_type"] == 'Y') {
                          $final_qry .= " AND gender='B' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } */
                        if (strtoupper($data['user_data'][0]["gender"]) == 'MALE') {
                            $final_qry .= " AND gender='M' AND (CASE WHEN (type_period= 'Y') THEN (no_period*365) ELSE (CASE WHEN (type_period= 'M') THEN (no_period*30) ELSE no_period END) END )>$ageinDays ";
                            $data["common"] = 0;
                        } else if (strtoupper($data['user_data'][0]["gender"]) == 'FEMALE' && $data['user_data'][0]["age_type"] == 'Y') {
                            $final_qry .= " AND gender='F' AND  (CASE WHEN (type_period= 'Y') THEN (no_period*365) ELSE (CASE WHEN (type_period= 'M') THEN (no_period*30) ELSE no_period END) END )>$ageinDays";
                            $data["common"] = 0;
                        }
                        $final_qry = $final_qry . " ORDER BY (
    CASE
      WHEN (type_period = 'Y') 
      THEN (no_period * 365) 
      ELSE (
        CASE
          WHEN (type_period = 'M') 
          THEN (no_period * 30) 
          ELSE no_period 
        END
      ) 
    END
  ) ASC limit 0,1";
                        //   if($para_key["id"]=="117"){ echo "<pre>"; print_r( $data['user_data'][0]); echo "</pre>"; echo    $final_qry; };
                        $final_qry1 = "SELECT * FROM `test_result_status` WHERE STATUS='1' AND `parameter_fk`='" . $para_key["id"] . "'";
                        $data["common"] = 1;
                        $data["para_ref_rng"] = $this->add_result_model->get_val($final_qry);
                        $data["para_ref_rng"][0]["common"] = "1";
                        $data["para_ref_rng"][0]["tst_id"] = $tst_id;
                        $para[$cnt_1]['para_ref_rng'] = $data["para_ref_rng"];

                        $data["para_ref_status"] = $this->add_result_model->get_val($final_qry1);
                        $para[$cnt_1]['para_ref_status'] = $data["para_ref_status"];
                        $para[$cnt_1]["test_parameter_id"] = $get_test_parameter1["id"];
                        $para[$cnt_1]["new_order"] = $get_test_parameter1["order"];
                        $cnt_1++;
                        //	if($para_key["id"]=="117"){ print_r($data['user_data'][0]); die(); };
                    }
                    $get_test_parameter1[0]['parameter'] = $para;
                    $new_data_array[] = $get_test_parameter1;
                } else {
                    $get_test_parameter1 = $this->add_result_model->get_val("SELECT id as test_fk,test_name FROM `test_master` WHERE id='" . $tst_id . "'");
                    $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                    $get_test_parameter1[0]['graph'] = $graph_pic;
                    $new_data_array[] = $get_test_parameter1[0];
                }
            } else {
                $get_test_parameter1 = $this->add_result_model->get_val("SELECT id as test_fk,test_name FROM `test_master` WHERE id='" . $tst_id . "'");
                $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                $get_test_parameter1[0]['graph'] = $graph_pic;
                $new_data_array[] = $get_test_parameter1[0];
            }

            $cnt++;
        }
        $data["new_data_array"] = $new_data_array;
        $data['result_list'] = $this->add_result_model->master_fun_get_tbl_val("user_test_result", array('status' => 1, 'job_id' => $data['cid']), array("id", "asc"));

        if ($data["login_data"]["id"] == "12") {
            
        } echo "stop1-" . date("H:i:sa");


//echo "<pre>";
//print_r($data["new_data_array"]);
//die();
//$data['unit_list'] = $this->add_result_model->unit_list();
        $this->load->view('header');
        //$this->load->view('nav', $data);
        $this->load->view('view_collected_temp', $data);
        //$this->load->view('footer');
    }

    function test_approve_details() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data['cid'] = $this->uri->segment(3);
        $data['tid1'] = $this->uri->segment(4);
        $data['query'] = $this->add_result_model->job_details($data['cid']);
        $data['processing_center'] = $this->add_result_model->master_fun_get_tbl_val("processing_center", array('status' => 1, 'lab_fk' => $data['query'][0]["branch_fk"]), array("id", "asc"));
        if (empty($data['processing_center'])) {
            $processing_center = '1';
        } else {
            $processing_center = $data['processing_center'][0]["branch_fk"];
        }
        $this->load->library("util");
        $util = new Util;
        $data["age"] = $util->get_age($data['query'][0]["dob"]);
        //echo "<pre>";print_R($data['query']); die();
        $data['user_booking_info'] = $this->add_result_model->master_fun_get_tbl_val("booking_info", array('status' => 1, 'id' => $data['query'][0]["booking_info"]), array("id", "asc"));
        $data['user_data'] = $this->add_result_model->master_fun_get_tbl_val("customer_master", array('status' => 1, 'id' => $data['query'][0]["custid"]), array("id", "asc"));
        if (empty($data['user_data'][0]["gender"]) && empty($data['user_data'][0]["age"])) {
            $data['user_data'][0]["gender"] = 'male';
            $data['user_data'][0]["age"] = 24;
            $data['user_data'][0]["age_type"] = 'Y';
        }

        if ($data['user_booking_info'][0]["family_member_fk"] != 0) {
            $data['user_family_info'] = $this->add_result_model->master_fun_get_tbl_val("customer_family_master", array('status' => 1, 'id' => $data['user_booking_info'][0]["family_member_fk"]), array("id", "asc"));
            $data['user_data'][0]["gender"] = $data['user_family_info'][0]["gender"];
            $data['user_data'][0]["age"] = $data['user_family_info'][0]["age"];
            $data['user_data'][0]["age_type"] = $data['user_family_info'][0]["age_type"];
            $data['user_data'][0]["full_name"] = $data['user_family_info'][0]["name"];
            $data['user_data'][0]["email"] = $data['user_family_info'][0]["email"];
            $data['user_data'][0]["phone"] = $data['user_family_info'][0]["phone"];
            $data['user_data'][0]["dob"] = $data['user_family_info'][0]["dob"];
        }
        if (empty($data['user_data'][0]["dob"])) {
            $data['user_data'][0]["dob"] = '1992-09-30';
        }
        /* Check bitrth date start */
        $this->load->library("util");
        $util = new Util;
        $age = $util->get_age($data['user_data'][0]["dob"]);
        $ageinDays = 0;
        if ($age[0] != 0) {
            $ageinDays += ($age[0] * 365);
            $data['user_data'][0]["age"] = $age[0];
            $data['user_data'][0]["age_type"] = 'Y';
        }
        if ($age[0] == 0 && $age[1] != 0) {
            $ageinDays += ($age[1] * 30);
            $data['user_data'][0]["age"] = $age[1];
            $data['user_data'][0]["age_type"] = 'M';
        }
        if ($age[0] == 0 && $age[1] == 0 && $age[2] != 0) {
            $ageinDays += ($age[2]);
            $data['user_data'][0]["age"] = $age[2];
            $data['user_data'][0]["age_type"] = 'D';
        }
        /* Check birth date end */

        $tid = array($data['tid1']);
        $cnt = 0;
        $new_data_array = array();
        foreach ($tid as $tst_id) {
            $get_test_parameter = $this->add_result_model->get_val("SELECT `test_parameter`.*,`test_master`.`test_name` FROM `test_parameter` INNER JOIN `test_master` ON `test_parameter`.`test_fk`=`test_master`.`id` WHERE `test_parameter`.`status`='1' AND `test_master`.`status`='1' AND `test_parameter`.`test_fk`='" . $tst_id . "' and `test_parameter`.`center`='" . $processing_center . "' order by `test_parameter`.order asc");
//echo "SELECT `test_parameter`.*,`test_master`.`test_name` FROM `test_parameter` INNER JOIN `test_master` ON `test_parameter`.`test_fk`=`test_master`.`id` WHERE `test_parameter`.`status`='1' AND `test_master`.`status`='1' AND `test_parameter`.`test_fk`='" . $tst_id . "' order by `test_parameter`.order asc"; die();
            $pid = array();
            foreach ($get_test_parameter as $tp_key) {
                $pid[] = $tp_key["parameter_fk"];
            }
            if (!empty($pid)) {
                $para = $this->add_result_model->get_val("SELECT * FROM `test_parameter_master` WHERE `status`='1' AND id IN (" . implode(",", $pid) . ") ORDER BY FIELD(id," . implode(",", $pid) . ")");
                if (!empty($para)) {
                    $cnt_1 = 0;
                    foreach ($para as $para_key) {
                        $formula = $this->add_result_model->get_val("SELECT * from use_formula where test_fk='" . $tst_id . "' and job_fk='" . $data['cid'] . "' and status='1'");
                        $get_test_parameter[$cnt_1]['use_formula'] = $formula[0]["use_formula"];
                        $get_test_parameter[$cnt_1]['on_new_page'] = $formula[0]["on_new_page"];
                        $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                        $get_test_parameter[$cnt_1]['graph'] = $graph_pic;
                        $get_test_parameter[$cnt_1]['graph_id'] = $formula[0]["id"];
                        $get_test_parameter1 = $get_test_parameter[$cnt_1];
//print_R($get_test_parameter1); die();
//echo "SELECT * from user_test_result where test_id='".$tst_id."' and parameter_id='" . $para_key["id"] . "' and job_id='".$data['cid']."' and  status='1'";
                        $para_user_val = $this->add_result_model->get_val("SELECT * from user_test_result where test_id='" . $tst_id . "' and parameter_id='" . $para_key["id"] . "' and job_id='" . $data['cid'] . "' and  status='1'");
                        $para[$cnt_1]["user_val"] = $para_user_val;
                        $para_ref_rng = $this->add_result_model->get_val("SELECT * FROM `parameter_referance_range` WHERE `status`='1' AND parameter_fk='" . $para_key["id"] . "' order by gender asc");
                        $final_qry = "SELECT * FROM `parameter_referance_range` WHERE STATUS='1' AND `parameter_fk`='" . $para_key["id"] . "'";
                        /* if ($data['user_data'][0]["age"] > 0 && $data['user_data'][0]["age"] < 6 && $data['user_data'][0]["age_type"] == 'D') {
                          $final_qry .= " AND gender='N'  AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='D'";
                          } else if ($data['user_data'][0]["age"] > 5 && $data['user_data'][0]["age_type"] == 'D') {
                          $final_qry .= " AND gender='C' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='D'";
                          $data["common"] = 0;
                          } else if ($data['user_data'][0]["age"] > 0 && $data['user_data'][0]["age"] < 8 && $data['user_data'][0]["age_type"] == 'M') {
                          $final_qry .= " AND gender='C' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='M'";
                          $data["common"] = 0;
                          } else if ($para_ref_rng[0]["gender"] == 'B' && $data['user_data'][0]["age_type"] == 'Y') {
                          //$get_both_age = $this->add_result_model->get_val("SELECT * FROM `parameter_referance_range` WHERE STATUS='1' AND `parameter_fk`='117' AND gender='B' AND `no_period` > ".$data['user_data'][0]["age"]." AND `type_period`='Y' ORDER BY id ASC");
                          $final_qry .= " AND gender='B' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } else if (strtoupper($data['user_data'][0]["gender"]) == 'MALE' && $data['user_data'][0]["age_type"] == 'Y') {
                          //$get_male_age = $this->add_result_model->get_val("SELECT * FROM `parameter_referance_range` WHERE STATUS='1' AND `parameter_fk`='117' AND gender='M' AND `no_period` > ".$data['user_data'][0]["age"]." AND `type_period`='Y' ORDER BY id ASC");
                          //print_r($get_male_age);
                          $final_qry .= " AND gender='M' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } else if (strtoupper($data['user_data'][0]["gender"]) == 'FEMALE' && $data['user_data'][0]["age_type"] == 'Y') {
                          $final_qry .= " AND gender='F' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } */
                        if ($data['user_data'][0]["age"] == '') {
                            $data['user_data'][0]["age"] = 0;
                        }
                        /* if ($data['user_data'][0]["age_type"] == 'D') {
                          $final_qry .= " AND gender='N'  AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='D'";
                          } else if ($data['user_data'][0]["age_type"] == 'M') {
                          $final_qry .= " AND gender='C' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='M'";
                          $data["common"] = 0;
                          } else if (strtoupper($data['user_data'][0]["gender"]) == 'MALE' && $data['user_data'][0]["age_type"] == 'Y') {
                          $final_qry .= " AND gender='M' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } else if (strtoupper($data['user_data'][0]["gender"]) == 'FEMALE' && $data['user_data'][0]["age_type"] == 'Y') {
                          $final_qry .= " AND gender='F' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          }
                          else if ($para_ref_rng[0]["gender"] == 'B' && $data['user_data'][0]["age_type"] == 'Y') {
                          $final_qry .= " AND gender='B' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } */
                        if (strtoupper($data['user_data'][0]["gender"]) == 'MALE') {
                            $final_qry .= " AND gender='M' AND (CASE WHEN (type_period= 'Y') THEN (no_period*365) ELSE (CASE WHEN (type_period= 'M') THEN (no_period*30) ELSE no_period END) END )>$ageinDays ";
                            $data["common"] = 0;
                        } else if (strtoupper($data['user_data'][0]["gender"]) == 'FEMALE' && $data['user_data'][0]["age_type"] == 'Y') {
                            $final_qry .= " AND gender='F' AND  (CASE WHEN (type_period= 'Y') THEN (no_period*365) ELSE (CASE WHEN (type_period= 'M') THEN (no_period*30) ELSE no_period END) END )>$ageinDays";
                            $data["common"] = 0;
                        }
                        $final_qry = $final_qry . " ORDER BY (
    CASE
      WHEN (type_period = 'Y') 
      THEN (no_period * 365) 
      ELSE (
        CASE
          WHEN (type_period = 'M') 
          THEN (no_period * 30) 
          ELSE no_period 
        END
      ) 
    END
  ) ASC limit 0,1";
                        $final_qry1 = "SELECT * FROM `test_result_status` WHERE STATUS='1' AND `parameter_fk`='" . $para_key["id"] . "'";
                        $data["common"] = 1;
                        $data["para_ref_rng"] = $this->add_result_model->get_val($final_qry);
                        $data["para_ref_rng"][0]["common"] = "1";
                        $data["para_ref_rng"][0]["tst_id"] = $tst_id;
                        $para[$cnt_1]['para_ref_rng'] = $data["para_ref_rng"];

                        $data["para_ref_status"] = $this->add_result_model->get_val($final_qry1);
                        $para[$cnt_1]['para_ref_status'] = $data["para_ref_status"];
                        $para[$cnt_1]["test_parameter_id"] = $get_test_parameter1["id"];
                        $para[$cnt_1]["new_order"] = $get_test_parameter1["order"];
                        $cnt_1++;
                    }
                    $get_test_parameter1[0]['parameter'] = $para;
                    $new_data_array[] = $get_test_parameter1;
                } else {
                    $get_test_parameter1 = $this->add_result_model->get_val("SELECT id as test_fk,test_name FROM `test_master` WHERE id='" . $tst_id . "'");
                    $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                    $get_test_parameter1[0]['graph'] = $graph_pic;
                    $new_data_array[] = $get_test_parameter1[0];
                }
            } else {
                $get_test_parameter1 = $this->add_result_model->get_val("SELECT id as test_fk,test_name FROM `test_master` WHERE id='" . $tst_id . "'");
                $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                $get_test_parameter1[0]['graph'] = $graph_pic;
                $new_data_array[] = $get_test_parameter1[0];
            }

            $cnt++;
        }
        $data["new_data_array"] = $new_data_array;
        $data['result_list'] = $this->add_result_model->master_fun_get_tbl_val("user_test_result", array('status' => 1, 'job_id' => $data['cid']), array("id", "asc"));
//echo "<pre>";
//print_r($data["new_data_array"]);
//die();
//$data['unit_list'] = $this->add_result_model->unit_list();
        $this->load->view('header');
        //$this->load->view('nav', $data);
        $this->load->view('view_collected_test', $data);
        //$this->load->view('footer');
    }

    function all_test_approve_details() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data['cid'] = $this->input->get("jid");
        $data['tid1'] = $this->input->get("tid");
        ;
        $data['query'] = $this->add_result_model->job_details($data['cid']);
        $data['processing_center'] = $this->add_result_model->master_fun_get_tbl_val("processing_center", array('status' => 1, 'lab_fk' => $data['query'][0]["branch_fk"]), array("id", "asc"));
        if (empty($data['processing_center'])) {
            $processing_center = '1';
        } else {
            $processing_center = $data['processing_center'][0]["branch_fk"];
        }
        $this->load->library("util");
        $util = new Util;
        $data["age"] = $util->get_age($data['query'][0]["dob"]);
        //echo "<pre>";print_R($data['query']); die();
        $data['user_booking_info'] = $this->add_result_model->master_fun_get_tbl_val("booking_info", array('status' => 1, 'id' => $data['query'][0]["booking_info"]), array("id", "asc"));
        $data['user_data'] = $this->add_result_model->master_fun_get_tbl_val("customer_master", array('status' => 1, 'id' => $data['query'][0]["custid"]), array("id", "asc"));
        if (empty($data['user_data'][0]["gender"]) && empty($data['user_data'][0]["age"])) {
            $data['user_data'][0]["gender"] = 'male';
            $data['user_data'][0]["age"] = 24;
            $data['user_data'][0]["age_type"] = 'Y';
        }

        if ($data['user_booking_info'][0]["family_member_fk"] != 0) {
            $data['user_family_info'] = $this->add_result_model->master_fun_get_tbl_val("customer_family_master", array('status' => 1, 'id' => $data['user_booking_info'][0]["family_member_fk"]), array("id", "asc"));
            $data['user_data'][0]["gender"] = $data['user_family_info'][0]["gender"];
            $data['user_data'][0]["age"] = $data['user_family_info'][0]["age"];
            $data['user_data'][0]["age_type"] = $data['user_family_info'][0]["age_type"];
            $data['user_data'][0]["full_name"] = $data['user_family_info'][0]["name"];
            $data['user_data'][0]["email"] = $data['user_family_info'][0]["email"];
            $data['user_data'][0]["phone"] = $data['user_family_info'][0]["phone"];
            $data['user_data'][0]["dob"] = $data['user_family_info'][0]["dob"];
        }
        if (empty($data['user_data'][0]["dob"])) {
            $data['user_data'][0]["dob"] = '1992-09-30';
        }
        /* Check bitrth date start */
        $this->load->library("util");
        $util = new Util;
        $age = $util->get_age($data['user_data'][0]["dob"]);
        $ageinDays = 0;
        if ($age[0] != 0) {
            $ageinDays += ($age[0] * 365);
            $data['user_data'][0]["age"] = $age[0];
            $data['user_data'][0]["age_type"] = 'Y';
        }
        if ($age[0] == 0 && $age[1] != 0) {
            $ageinDays += ($age[1] * 30);
            $data['user_data'][0]["age"] = $age[1];
            $data['user_data'][0]["age_type"] = 'M';
        }
        if ($age[0] == 0 && $age[1] == 0 && $age[2] != 0) {
            $ageinDays += ($age[2]);
            $data['user_data'][0]["age"] = $age[2];
            $data['user_data'][0]["age_type"] = 'D';
        }
        /* Check birth date end */

        $tid = explode(",", $data['tid1']);
        $cnt = 0;
        $new_data_array = array();
        foreach ($tid as $tst_id) {
            $get_test_parameter = $this->add_result_model->get_val("SELECT `test_parameter`.*,`test_master`.`test_name` FROM `test_parameter` INNER JOIN `test_master` ON `test_parameter`.`test_fk`=`test_master`.`id` WHERE `test_parameter`.`status`='1' AND `test_master`.`status`='1' AND `test_parameter`.`test_fk`='" . $tst_id . "' and `test_parameter`.`center`='" . $processing_center . "' order by `test_parameter`.order asc");
//echo "SELECT `test_parameter`.*,`test_master`.`test_name` FROM `test_parameter` INNER JOIN `test_master` ON `test_parameter`.`test_fk`=`test_master`.`id` WHERE `test_parameter`.`status`='1' AND `test_master`.`status`='1' AND `test_parameter`.`test_fk`='" . $tst_id . "' order by `test_parameter`.order asc"; die();
            $pid = array();
            foreach ($get_test_parameter as $tp_key) {
                $pid[] = $tp_key["parameter_fk"];
            }
            if (!empty($pid)) {
                $para = $this->add_result_model->get_val("SELECT * FROM `test_parameter_master` WHERE `status`='1' AND id IN (" . implode(",", $pid) . ") ORDER BY FIELD(id," . implode(",", $pid) . ")");
                if (!empty($para)) {
                    $cnt_1 = 0;
                    foreach ($para as $para_key) {
                        $formula = $this->add_result_model->get_val("SELECT * from use_formula where test_fk='" . $tst_id . "' and job_fk='" . $data['cid'] . "' and status='1'");
                        $get_test_parameter[$cnt_1]['use_formula'] = $formula[0]["use_formula"];
                        $get_test_parameter[$cnt_1]['on_new_page'] = $formula[0]["on_new_page"];
                        $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                        $get_test_parameter[$cnt_1]['graph'] = $graph_pic;
                        $get_test_parameter[$cnt_1]['graph_id'] = $formula[0]["id"];
                        $get_test_parameter1 = $get_test_parameter[$cnt_1];
//print_R($get_test_parameter1); die();
//echo "SELECT * from user_test_result where test_id='".$tst_id."' and parameter_id='" . $para_key["id"] . "' and job_id='".$data['cid']."' and  status='1'";
                        $para_user_val = $this->add_result_model->get_val("SELECT * from user_test_result where test_id='" . $tst_id . "' and parameter_id='" . $para_key["id"] . "' and job_id='" . $data['cid'] . "' and  status='1'");
                        $para[$cnt_1]["user_val"] = $para_user_val;
                        $para_ref_rng = $this->add_result_model->get_val("SELECT * FROM `parameter_referance_range` WHERE `status`='1' AND parameter_fk='" . $para_key["id"] . "' order by gender asc");
                        $final_qry = "SELECT * FROM `parameter_referance_range` WHERE STATUS='1' AND `parameter_fk`='" . $para_key["id"] . "'";
                        /* if ($data['user_data'][0]["age"] > 0 && $data['user_data'][0]["age"] < 6 && $data['user_data'][0]["age_type"] == 'D') {
                          $final_qry .= " AND gender='N'  AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='D'";
                          } else if ($data['user_data'][0]["age"] > 5 && $data['user_data'][0]["age_type"] == 'D') {
                          $final_qry .= " AND gender='C' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='D'";
                          $data["common"] = 0;
                          } else if ($data['user_data'][0]["age"] > 0 && $data['user_data'][0]["age"] < 8 && $data['user_data'][0]["age_type"] == 'M') {
                          $final_qry .= " AND gender='C' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='M'";
                          $data["common"] = 0;
                          } else if ($para_ref_rng[0]["gender"] == 'B' && $data['user_data'][0]["age_type"] == 'Y') {
                          //$get_both_age = $this->add_result_model->get_val("SELECT * FROM `parameter_referance_range` WHERE STATUS='1' AND `parameter_fk`='117' AND gender='B' AND `no_period` > ".$data['user_data'][0]["age"]." AND `type_period`='Y' ORDER BY id ASC");
                          $final_qry .= " AND gender='B' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } else if (strtoupper($data['user_data'][0]["gender"]) == 'MALE' && $data['user_data'][0]["age_type"] == 'Y') {
                          //$get_male_age = $this->add_result_model->get_val("SELECT * FROM `parameter_referance_range` WHERE STATUS='1' AND `parameter_fk`='117' AND gender='M' AND `no_period` > ".$data['user_data'][0]["age"]." AND `type_period`='Y' ORDER BY id ASC");
                          //print_r($get_male_age);
                          $final_qry .= " AND gender='M' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } else if (strtoupper($data['user_data'][0]["gender"]) == 'FEMALE' && $data['user_data'][0]["age_type"] == 'Y') {
                          $final_qry .= " AND gender='F' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } */
                        if ($data['user_data'][0]["age"] == '') {
                            $data['user_data'][0]["age"] = 0;
                        }
                        /* if ($data['user_data'][0]["age_type"] == 'D') {
                          $final_qry .= " AND gender='N'  AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='D'";
                          } else if ($data['user_data'][0]["age_type"] == 'M') {
                          $final_qry .= " AND gender='C' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='M'";
                          $data["common"] = 0;
                          } else if (strtoupper($data['user_data'][0]["gender"]) == 'MALE' && $data['user_data'][0]["age_type"] == 'Y') {
                          $final_qry .= " AND gender='M' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } else if (strtoupper($data['user_data'][0]["gender"]) == 'FEMALE' && $data['user_data'][0]["age_type"] == 'Y') {
                          $final_qry .= " AND gender='F' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          }
                          else if ($para_ref_rng[0]["gender"] == 'B' && $data['user_data'][0]["age_type"] == 'Y') {
                          $final_qry .= " AND gender='B' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } */
                        if (strtoupper($data['user_data'][0]["gender"]) == 'MALE') {
                            $final_qry .= " AND gender='M' AND (CASE WHEN (type_period= 'Y') THEN (no_period*365) ELSE (CASE WHEN (type_period= 'M') THEN (no_period*30) ELSE no_period END) END )>$ageinDays ";
                            $data["common"] = 0;
                        } else if (strtoupper($data['user_data'][0]["gender"]) == 'FEMALE' && $data['user_data'][0]["age_type"] == 'Y') {
                            $final_qry .= " AND gender='F' AND  (CASE WHEN (type_period= 'Y') THEN (no_period*365) ELSE (CASE WHEN (type_period= 'M') THEN (no_period*30) ELSE no_period END) END )>$ageinDays";
                            $data["common"] = 0;
                        }
                        $final_qry = $final_qry . " ORDER BY (
    CASE
      WHEN (type_period = 'Y') 
      THEN (no_period * 365) 
      ELSE (
        CASE
          WHEN (type_period = 'M') 
          THEN (no_period * 30) 
          ELSE no_period 
        END
      ) 
    END
  ) ASC limit 0,1";
                        $final_qry1 = "SELECT * FROM `test_result_status` WHERE STATUS='1' AND `parameter_fk`='" . $para_key["id"] . "'";
                        $data["common"] = 1;
                        $data["para_ref_rng"] = $this->add_result_model->get_val($final_qry);
                        $data["para_ref_rng"][0]["common"] = "1";
                        $data["para_ref_rng"][0]["tst_id"] = $tst_id;
                        $para[$cnt_1]['para_ref_rng'] = $data["para_ref_rng"];

                        $data["para_ref_status"] = $this->add_result_model->get_val($final_qry1);
                        $para[$cnt_1]['para_ref_status'] = $data["para_ref_status"];
                        $para[$cnt_1]["test_parameter_id"] = $get_test_parameter1["id"];
                        $para[$cnt_1]["new_order"] = $get_test_parameter1["order"];
                        $cnt_1++;
                    }
                    $get_test_parameter1[0]['parameter'] = $para;
                    $new_data_array[] = $get_test_parameter1;
                } else {
                    $get_test_parameter1 = $this->add_result_model->get_val("SELECT id as test_fk,test_name FROM `test_master` WHERE id='" . $tst_id . "'");
                    $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                    $get_test_parameter1[0]['graph'] = $graph_pic;
                    $new_data_array[] = $get_test_parameter1[0];
                }
            } else {
                $get_test_parameter1 = $this->add_result_model->get_val("SELECT id as test_fk,test_name FROM `test_master` WHERE id='" . $tst_id . "'");
                $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                $get_test_parameter1[0]['graph'] = $graph_pic;
                $new_data_array[] = $get_test_parameter1[0];
            }

            $cnt++;
        }
        $data["new_data_array"] = $new_data_array;
        $data['result_list'] = $this->add_result_model->master_fun_get_tbl_val("user_test_result", array('status' => 1, 'job_id' => $data['cid']), array("id", "asc"));
//echo "<pre>";
//print_r($data["new_data_array"]);
//die();
//$data['unit_list'] = $this->add_result_model->unit_list();
        $this->load->view('header');
        //$this->load->view('nav', $data);
        $this->load->view('view_collected_test1', $data);
        //$this->load->view('footer');
    }

    function add_parameter_data() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $count = $this->input->post('count_par');
        $test_id = $this->input->post('test_id');
        $job_id = $this->input->post('job_id');
        $test_description = $this->input->post('desc_test');
        $this->add_result_model->master_fun_update('test_master', array('id', $test_id), array("description" => $test_description));
        for ($i = 1; $i <= $count; $i++) {
            $data["login_data"] = logindata();
            $parname = $this->input->post('par_name_' . $i);
            $parmin = $this->input->post('par_min_' . $i);
            $parmax = $this->input->post('par_max_' . $i);
            $parunit = $this->input->post('par_unit_' . $i);
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
            if ($insert) {
                $data["login_data"] = logindata();
                $parvalue = $this->input->post('par_value_' . $i);
                $parcondi = $this->input->post('par_condi_' . $i);
                $data = array(
                    "job_id" => $job_id,
                    "parameter_id" => $insert,
                    "value" => $parvalue,
                    "condition" => $parcondi,
                    "created_by" => $data["login_data"]["id"],
                    "created_date" => date("Y-m-d H:i:s")
                );
                $val_add = $this->add_result_model->master_fun_insert("user_test_result", $data);
            }
        }
        if ($val_add) {
            redirect('add_result/test_details/' . $job_id);
        }
    }

    function sample_collected_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
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
        $data['branch_permission'] = $this->add_result_model->master_fun_get_tbl_val("user_branch", array('user_fk' => $data["login_data"]['id'], 'status' => 1), array("id", "asc"));
        $cntr_arry = array();
        if (!empty($data["login_data"]['branch_fk'])) {
            foreach ($data['branch_permission'] as $key) {
                //if ($key["test_parameter"] == 1) {
                $cntr_arry[] = $key["branch_fk"];
                // }
            }
        }
        //print_r($cntr_arry); die();
        $data['success'] = $this->session->flashdata("success");
        $user = $data["user"] = $this->input->get_post("user");
        $mobile = $data["mobile"] = $this->input->get_post("mobile");
        $date = $data["date"] = $this->input->get_post("date");
        $p_amount = $data["p_amount"] = $this->input->get_post("p_amount");
        if ($user != "" || $date != "" || $city != "" || $status != "" || $mobile != "" || $t_id != "" || $p_id != "" || $p_amount != "") {
            $total_row = $this->add_result_model->num_row_srch_job_list($user, $date, $city, $status, $mobile, $t_id, $p_id, $p_amount, $cntr_arry);
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
            $data['query'] = $this->add_result_model->row_srch_job_list($user, $date, $city, $status, $mobile, $t_id, $p_id, $p_amount, $config["per_page"], $page, $cntr_arry);
            $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;
        } else {
            $total_row = $this->add_result_model->num_srch_job_list($cntr_arry);
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
            $data['query'] = $this->add_result_model->srch_job_list($config["per_page"], $page, $cntr_arry);
            $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;
        }
        $data['customer'] = $this->add_result_model->master_fun_get_tbl_val("customer_master", array('status' => 1), array("full_name", "asc"));
        $data['city'] = $this->add_result_model->master_fun_get_tbl_val("city", array('status' => 1), array("id", "asc"));
        if (empty($cntr_arry) && ($data["login_data"] == 5 || $data["login_data"] == 6 || $data["login_data"] == 7)) {
            $data['query'] = array();
            $data["links"] = '';
        }
        $cnt = 0;
        foreach ($data['query'] as $key) {
            $w_prc = 0;
            /* Count booked test price */
            $booked_tests = $this->add_result_model->master_fun_get_tbl_val("wallet_master", array('job_fk' => $key["id"]), array("id", "asc"));
            $emergency_tests = $this->add_result_model->master_fun_get_tbl_val("booking_info", array('id' => $key["booking_info"]), array("id", "asc"));
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
            $data['query'][$cnt]["emergency"] = $emergency_tests[0]["emergency"];
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
        $files = $_FILES;
        $this->load->library('upload');
        //   echo "<pre>"; print_r($_POST); die();
        $data["login_data"] = logindata();
        $count = $this->input->post('count');
        $para_job_id = $this->input->post('para_job_id');
        $this->add_result_model->master_fun_update("use_formula", array("job_fk", $para_job_id), array("status" => 0));
        $test_id_array = array();
        for ($i = 0; $i < $count; $i++) {
            $para_id = $this->input->post('parameter_id_' . $i);
            $test_id = $this->input->post('test_id_' . $i);
            $value = $this->input->post('parameter_value_' . $i);
            $this->add_result_model->master_fun_update1("user_test_result", array('job_id' => $para_job_id, "parameter_id" => $para_id, "test_id" => $test_id), array("status" => "0"));
            if ($value != '') {
                $check_val = $this->add_result_model->master_fun_get_tbl_val("user_test_result", array('job_id' => $para_job_id, "parameter_id" => $para_id, "test_id" => $test_id, "status" => 1), array("id", "asc"));
                if (empty($check_val)) {
                    $data = array(
                        "job_id" => $para_job_id,
                        "parameter_id" => $para_id,
                        "value" => $value,
                        "test_id" => $test_id,
                        "created_by" => $data["login_data"]["id"],
                        "created_date" => date("Y-m-d H:i:s"),
                    );
                    $val_add = $this->add_result_model->master_fun_insert("user_test_result", $data);
                } else {
                    $data = array("value" => $value, "status" => "1");
                    $val_add = $this->add_result_model->master_fun_update("user_test_result", array("id", $check_val[0]['id']), $data);
                }
            }
        }
        $para = $this->input->post("para");
        $order = $this->input->post("order");
        $p_cnt = 0;
        foreach ($para as $value1) {
            $this->add_result_model->master_fun_update('test_parameter', array('id', $value1), array("order" => $order[$p_cnt]));
            $p_cnt++;
        }
        if ($value != '') {
            $check_val = $this->add_result_model->master_fun_get_tbl_val("user_test_result", array('job_id' => $para_job_id, "parameter_id" => $para_id, "test_id" => $test_id, "status" => 1), array("id", "asc"));
            if (empty($check_val)) {
                $data = array(
                    "job_id" => $para_job_id,
                    "parameter_id" => $para_id,
                    "value" => $value,
                    "test_id" => $test_id,
                    "created_by" => $data["login_data"]["id"],
                    "created_date" => date("Y-m-d H:i:s"),
                );
                $val_add = $this->add_result_model->master_fun_insert("user_test_result", $data);
            } else {
                $data = array("value" => $value);
                $val_add = $this->add_result_model->master_fun_update("user_test_result", array("id", $check_val[0]['id']), $data);
            }
        }

        $test_fk = $this->input->post('test_fk');
        foreach ($test_fk as $key) {
            $formula = $this->input->post('use_formula_' . $key);
            $on_new_page = $this->input->post('on_new_page_' . $key);
            if ($formula == '') {
                $formula = 0;
            }
            if ($on_new_page == '') {
                $on_new_page = 0;
            }
            /* Nishit Graph Upload start */
            /* $graph_name = '';
              if ($files['graph_' . $key]['name'] != '') {
              $_FILES['userfile']['name'] = $files['graph_' . $key]['name'];
              $_FILES['userfile']['type'] = $files['graph_' . $key]['type'];
              $_FILES['userfile']['tmp_name'] = $files['graph_' . $key]['tmp_name'];
              $_FILES['userfile']['error'] = $files['graph_' . $key]['error'];
              $_FILES['userfile']['size'] = $files['graph_' . $key]['size'];
              $config['upload_path'] = './upload/report/graph/';
              $config['allowed_types'] = 'gif|jpg|png';
              $config['file_name'] = time() . $files['graph_' . $key]['name'];
              $config['file_name'] = str_replace(' ', '_', $config['file_name']);
              $config['overwrite'] = FALSE;
              $this->load->library('upload', $config);
              $this->upload->initialize($config);
              if (!$this->upload->do_upload()) {

              } else {
              $graph_name = $config['file_name'];
              }
              }
             */


            //print_r($_FILES['graph_' . $key]['name'][0]);
            $file_loop = count($_FILES['graph_' . $key]['name']);
            $file_upload = array();
            if (!empty($_FILES['graph_' . $key]['name'])) {
                for ($i = 0; $i < $file_loop; $i++) {
                    $_FILES['userfile']['name'] = $files['graph_' . $key]['name'][$i];
                    $_FILES['userfile']['type'] = $files['graph_' . $key]['type'][$i];
                    $_FILES['userfile']['tmp_name'] = $files['graph_' . $key]['tmp_name'][$i];
                    $_FILES['userfile']['error'] = $files['graph_' . $key]['error'][$i];
                    $_FILES['userfile']['size'] = $files['graph_' . $key]['size'][$i];
                    $config['upload_path'] = './upload/report/graph/';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $config['file_name'] = time() . $files['graph_' . $key]['name'][$i];
                    $config['file_name'] = str_replace(' ', '_', $config['file_name']);
                    $config['overwrite'] = FALSE;
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload()) {
                        $error = $this->upload->display_errors();
                        $this->session->set_flashdata("error", array($error));
                        //redirect('job-master/job-details/' . $cid);
                    } else {
                        $file_upload[] = array("test_fk" => $key, "pic" => $config['file_name']);
                    }
                }
            }
            //print_R($file_upload); die();
            /* Nishit Graph Upload end */
            if (!in_array($key, $test_id_array)) {
                if ($graph_name == '') {
                    $graph_name = $this->input->post('current_graph_' . $key);
                    ;
                }
                $data12 = array(
                    "job_fk" => $para_job_id,
                    "test_fk" => $key,
                    "use_formula" => $formula,
                    "on_new_page" => $on_new_page,
                    //"graph" => $graph_name,
                    "status" => 1
                );
                $test_id_array[] = $key;
                $val_add = $this->add_result_model->master_fun_insert("use_formula", $data12);
            }
            foreach ($file_upload as $file_key) {
                $data12 = array(
                    "job_fk" => $para_job_id,
                    "test_fk" => $key,
                    "pic" => $file_key["pic"],
                    "status" => 1,
                    "createddate" => date("Y-m-d H:i:s")
                );
                //print_r($data12); die();
                $val_add = $this->add_result_model->master_fun_insert("user_formula_pic", $data12);
            }
        }

        $this->add_result_model->master_fun_insert("job_log", array("job_fk" => $para_job_id, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => '', "message_fk" => "15", "date_time" => date("Y-m-d H:i:s")));
        $this->session->set_userdata("closeFancyBox", array('parent.close_popup1();'));
        redirect('add_result/test_details/' . $para_job_id);
    }

    function add_value_exists1() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $tid = $this->input->get_post("tid");
        $files = $_FILES;
        $this->load->library('upload');
        // echo "<pre>"; print_r($_POST); die();
        $data["login_data"] = logindata();
        $count = $this->input->post('count');
        $para_job_id = $this->input->post('para_job_id');
        $this->add_result_model->master_fun_update("use_formula", array("job_fk", $para_job_id), array("status" => 0));
        $test_id_array = array();
        for ($i = 0; $i < $count; $i++) {
            $para_id = $this->input->post('parameter_id_' . $i);
            $test_id = $this->input->post('test_id_' . $i);
            $value = $this->input->post('parameter_value_' . $i);

            if ($value != '') {
                $check_val = $this->add_result_model->master_fun_get_tbl_val("user_test_result", array('job_id' => $para_job_id, "parameter_id" => $para_id, "test_id" => $test_id, "status" => 1), array("id", "asc"));
                if (empty($check_val)) {
                    $data = array(
                        "job_id" => $para_job_id,
                        "parameter_id" => $para_id,
                        "value" => $value,
                        "test_id" => $test_id,
                        "created_by" => $data["login_data"]["id"],
                        "created_date" => date("Y-m-d H:i:s"),
                    );
                    $val_add = $this->add_result_model->master_fun_insert("user_test_result", $data);
                } else {
                    $data = array("value" => $value);
                    $val_add = $this->add_result_model->master_fun_update("user_test_result", array("id", $check_val[0]['id']), $data);
                }
            }
        }
        $para = $this->input->post("para");
        $order = $this->input->post("order");
        $p_cnt = 0;
        foreach ($para as $value) {
            $this->add_result_model->master_fun_update('test_parameter', array('id', $value), array("order" => $order[$p_cnt]));
            $p_cnt++;
        }
        if ($value != '') {
            $check_val = $this->add_result_model->master_fun_get_tbl_val("user_test_result", array('job_id' => $para_job_id, "parameter_id" => $para_id, "test_id" => $test_id, "status" => 1), array("id", "asc"));
            if (empty($check_val)) {
                $data = array(
                    "job_id" => $para_job_id,
                    "parameter_id" => $para_id,
                    "value" => $value,
                    "test_id" => $test_id,
                    "created_by" => $data["login_data"]["id"],
                    "created_date" => date("Y-m-d H:i:s"),
                );
                $val_add = $this->add_result_model->master_fun_insert("user_test_result", $data);
            } else {
                $data = array("value" => $value);
                $val_add = $this->add_result_model->master_fun_update("user_test_result", array("id", $check_val[0]['id']), $data);
            }
        }

        $test_fk = $this->input->post('test_fk');
        foreach ($test_fk as $key) {
            $formula = $this->input->post('use_formula_' . $key);
            $on_new_page = $this->input->post('on_new_page_' . $key);
            if ($formula == '') {
                $formula = 0;
            }
            if ($on_new_page == '') {
                $on_new_page = 0;
            }
            /* Nishit Graph Upload start */
            /* $graph_name = '';
              if ($files['graph_' . $key]['name'] != '') {
              $_FILES['userfile']['name'] = $files['graph_' . $key]['name'];
              $_FILES['userfile']['type'] = $files['graph_' . $key]['type'];
              $_FILES['userfile']['tmp_name'] = $files['graph_' . $key]['tmp_name'];
              $_FILES['userfile']['error'] = $files['graph_' . $key]['error'];
              $_FILES['userfile']['size'] = $files['graph_' . $key]['size'];
              $config['upload_path'] = './upload/report/graph/';
              $config['allowed_types'] = 'gif|jpg|png';
              $config['file_name'] = time() . $files['graph_' . $key]['name'];
              $config['file_name'] = str_replace(' ', '_', $config['file_name']);
              $config['overwrite'] = FALSE;
              $this->load->library('upload', $config);
              $this->upload->initialize($config);
              if (!$this->upload->do_upload()) {

              } else {
              $graph_name = $config['file_name'];
              }
              }
             */
            //print_r($_FILES['graph_' . $key]['name'][0]);
            $file_loop = count($_FILES['graph_' . $key]['name']);
            $file_upload = array();
            if (!empty($_FILES['graph_' . $key]['name'])) {
                for ($i = 0; $i < $file_loop; $i++) {
                    $_FILES['userfile']['name'] = $files['graph_' . $key]['name'][$i];
                    $_FILES['userfile']['type'] = $files['graph_' . $key]['type'][$i];
                    $_FILES['userfile']['tmp_name'] = $files['graph_' . $key]['tmp_name'][$i];
                    $_FILES['userfile']['error'] = $files['graph_' . $key]['error'][$i];
                    $_FILES['userfile']['size'] = $files['graph_' . $key]['size'][$i];
                    $config['upload_path'] = './upload/report/graph/';
                    $config['allowed_types'] = 'gif|jpg|png';
                    $config['file_name'] = time() . $files['graph_' . $key]['name'][$i];
                    $config['file_name'] = str_replace(' ', '_', $config['file_name']);
                    $config['overwrite'] = FALSE;
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload()) {
                        $error = $this->upload->display_errors();
                        $this->session->set_flashdata("error", array($error));
                        //redirect('job-master/job-details/' . $cid);
                    } else {
                        $file_upload[] = array("test_fk" => $key, "pic" => $config['file_name']);
                    }
                }
            }
            //print_R($file_upload); die();
            /* Nishit Graph Upload end */
            if (!in_array($key, $test_id_array)) {
                if ($graph_name == '') {
                    $graph_name = $this->input->post('current_graph_' . $key);
                    ;
                }
                $data12 = array(
                    "job_fk" => $para_job_id,
                    "test_fk" => $key,
                    "use_formula" => $formula,
                    "on_new_page" => $on_new_page,
                    // "graph" => $graph_name,
                    "status" => 1
                );
                $test_id_array[] = $key;
                $val_add = $this->add_result_model->master_fun_insert("use_formula", $data12);
            }
            foreach ($file_upload as $file_key) {
                $data12 = array(
                    "job_fk" => $para_job_id,
                    "test_fk" => $key,
                    "pic" => $file_key["pic"],
                    "status" => 1,
                    "createddate" => date("Y-m-d H:i:s")
                );
                //print_r($data12); die();
                $val_add = $this->add_result_model->master_fun_insert("user_formula_pic", $data12);
            }
        }

        $this->add_result_model->master_fun_insert("job_log", array("job_fk" => $para_job_id, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => '', "message_fk" => "15", "date_time" => date("Y-m-d H:i:s")));
        //echo 'add_result/test_approve_details/' . $para_job_id . "/" . $tid; die();
        $check_is_approved = $this->add_result_model->master_fun_get_tbl_val("approve_job_test", array('job_fk' => $para_job_id, "test_fk" => $key, "status" => "1"), array("id", "asc"));
        //print_r($check_is_approved); die();
        if (empty($check_is_approved)) {
            $insert = $this->add_result_model->master_fun_insert("approve_job_test", array('job_fk' => $para_job_id, "test_fk" => $key, "approve_by" => $data["login_data"]["id"], "created_date" => date("Y-m-d H:i:s")));
        }
        redirect('add_result/test_approve_details/' . $para_job_id . "/" . $tid);
    }

    function add_value_exists2() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $files = $_FILES;
        $this->load->library('upload');
        //   echo "<pre>"; print_r($_POST); die();
        $data["login_data"] = logindata();
        $count = $this->input->post('count');
        $para_job_id = $this->input->post('para_job_id');
        //$this->add_result_model->master_fun_update("use_formula", array("job_fk", $para_job_id), array("status" => 0));
        $test_id_array = array();
        for ($i = 0; $i < $count; $i++) {
            $para_id = $this->input->post('parameter_id_' . $i);
            $test_id = $this->input->post('test_id_' . $i);
            $value = $this->input->post('parameter_value_' . $i);
            $this->add_result_model->master_fun_update1("user_test_result", array('job_id' => $para_job_id, "parameter_id" => $para_id, "test_id" => $test_id), array("status" => "0"));
            if ($value != '') {
                $check_val = $this->add_result_model->master_fun_get_tbl_val("user_test_result", array('job_id' => $para_job_id, "parameter_id" => $para_id, "test_id" => $test_id, "status" => 1), array("id", "asc"));
                if (empty($check_val)) {
                    $data = array(
                        "job_id" => $para_job_id,
                        "parameter_id" => $para_id,
                        "value" => $value,
                        "test_id" => $test_id,
                        "created_by" => $data["login_data"]["id"],
                        "created_date" => date("Y-m-d H:i:s"),
                    );
                    $val_add = $this->add_result_model->master_fun_insert("user_test_result", $data);
                } else {
                    $data = array("value" => $value, "status" => "1");
                    $val_add = $this->add_result_model->master_fun_update("user_test_result", array("id", $check_val[0]['id']), $data);
                }
            }
            $check_is_approved = $this->add_result_model->master_fun_get_tbl_val("approve_job_test", array('job_fk' => $para_job_id, "test_fk" => $test_id, "status" => "1"), array("id", "asc"));
            if (empty($check_is_approved)) {
                $insert = $this->add_result_model->master_fun_insert("approve_job_test", array('job_fk' => $para_job_id, "test_fk" => $test_id, "approve_by" => $data["login_data"]["id"], "created_date" => date("Y-m-d H:i:s")));
            }
        }
        if ($value != '') {
            $check_val = $this->add_result_model->master_fun_get_tbl_val("user_test_result", array('job_id' => $para_job_id, "parameter_id" => $para_id, "test_id" => $test_id, "status" => 1), array("id", "asc"));
            if (empty($check_val)) {
                $data = array(
                    "job_id" => $para_job_id,
                    "parameter_id" => $para_id,
                    "value" => $value,
                    "test_id" => $test_id,
                    "created_by" => $data["login_data"]["id"],
                    "created_date" => date("Y-m-d H:i:s"),
                );
                $val_add = $this->add_result_model->master_fun_insert("user_test_result", $data);
            } else {
                $data = array("value" => $value);
                $val_add = $this->add_result_model->master_fun_update("user_test_result", array("id", $check_val[0]['id']), $data);
            }
        }



        $this->add_result_model->master_fun_insert("job_log", array("job_fk" => $para_job_id, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => '', "message_fk" => "15", "date_time" => date("Y-m-d H:i:s")));
        //$this->session->set_userdata("closeFancyBox", array('parent.close_popup1();'));
        redirect('add_result/all_test_approve_details?jid=' . $para_job_id . '&tid=' . $this->input->post('tid'));
    }

    function change_order() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $para_job_id = $this->input->post('para_job_id');
        $para = $this->input->post("para");
        $order = $this->input->post("order");
        $p_cnt = 0;
        foreach ($para as $value) {
            $this->add_result_model->master_fun_update('test_parameter', array('id', $value), array("order" => $order[$p_cnt]));
            $p_cnt++;
        }
        $this->add_result_model->master_fun_insert("job_log", array("job_fk" => $para_job_id, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => '', "message_fk" => "16", "date_time" => date("Y-m-d H:i:s")));

        redirect('add_result/test_details/' . $para_job_id);
    }

    function pdf_report($id) {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data['cid'] = $this->uri->segment(3);
        $data["type"] = $this->input->get("type");
        $data['query'] = $this->add_result_model->job_details($data['cid']);
        $branch_fk = $data['query'][0]["branch_fk"];
        $data['user_booking_info'] = $this->add_result_model->master_fun_get_tbl_val("booking_info", array('status' => 1, 'id' => $data['query'][0]["booking_info"]), array("id", "asc"));
        $data['user_data'] = $this->add_result_model->master_fun_get_tbl_val("customer_master", array('status' => 1, 'id' => $data['query'][0]["custid"]), array("id", "asc"));
//        if (empty($data['user_data'][0]["gender"]) && empty($data['user_data'][0]["age"])) {
//            $data['user_data'][0]["gender"] = 'male';
//            $data['user_data'][0]["age"] = 24;
//        }
        if ($data['user_booking_info'][0]["family_member_fk"] != 0) {
            $data['user_family_info'] = $this->add_result_model->master_fun_get_tbl_val("customer_family_master", array('status' => 1, 'id' => $data['user_booking_info'][0]["family_member_fk"]), array("id", "asc"));
            $data['user_data'][0]["gender"] = $data['user_family_info'][0]["gender"];
            $data['user_data'][0]["age"] = $data['user_family_info'][0]["age"];
            $data['user_data'][0]["age_type"] = $data['user_family_info'][0]["age_type"];
            $data['user_data'][0]["full_name"] = $data['user_family_info'][0]["name"];
            $data['user_data'][0]["email"] = $data['user_family_info'][0]["email"];
            $data['user_data'][0]["phone"] = $data['user_family_info'][0]["phone"];
            $data['user_data'][0]["dob"] = $data['user_family_info'][0]["dob"];
        }
        $get_approved_test_list = $this->add_result_model->master_fun_get_tbl_val("approve_job_test", array('status' => '1', 'job_fk' => $data['cid']), array("id", "asc"));
        $approved_test = array();
        foreach ($get_approved_test_list as $at_key) {
            $approved_test[] = $at_key["test_fk"];
        }
//        if (empty($data['user_data'][0]["dob"])) {
//            $data['user_data'][0]["dob"] = '1992-09-30';
//        }
        /* Check bitrth date start */
        if ($data['user_data'][0]["dob"] != '') {
            $this->load->library("util");
            $util = new Util;
            $age = $util->get_age($data['user_data'][0]["dob"]);
            if ($age[0] != 0) {
                $data['user_data'][0]["age"] = $age[0];
                $data['user_data'][0]["age_type"] = 'Y';
            }
            if ($age[0] == 0 && $age[1] != 0) {
                $data['user_data'][0]["age"] = $age[1];
                $data['user_data'][0]["age_type"] = 'M';
            }
            if ($age[0] == 0 && $age[1] == 0 && $age[2] != 0) {
                $data['user_data'][0]["age"] = $age[2];
                $data['user_data'][0]["age_type"] = 'D';
            }
        } else {
            $data['user_data'][0]["age"] = '-';
            $data['user_data'][0]["age_type"] = '';
        }
        /* Check birth date end */
        $tid = array();
        $data['parameter_list'] = array();
        if (trim($data['query'][0]['testid']) == null && $data['query'][0]["packageid"] != null) {
            $package_id = $data['query'][0]["packageid"];
            $pid = explode("%", $data['query'][0]['packageid']);
            foreach ($pid as $pkey) {
                $p_test = $this->add_result_model->get_val("SELECT `package_test`.`test_fk` FROM `package_test` INNER JOIN `test_master` ON `package_test`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `package_test`.`status`='1' AND `package_test`.`package_fk`='" . $pkey . "'");
                foreach ($p_test as $tp_key) {
                    $tid[] = $tp_key["test_fk"];
                }
            }
        } else if (trim($data['query'][0]['testid']) != null && $data['query'][0]["packageid"] != null) {

            $tid = explode(",", $data['query'][0]['testid']);
            $package_id = $data['query'][0]["packageid"];
            $pid = explode("%", $data['query'][0]['packageid']);
            foreach ($pid as $pkey) {
                $p_test = $this->add_result_model->get_val("SELECT `package_test`.`test_fk` FROM `package_test` INNER JOIN `test_master` ON `package_test`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `package_test`.`status`='1' AND `package_test`.`package_fk`='" . $pkey . "'");
                foreach ($p_test as $tp_key) {
                    $tid[] = $tp_key["test_fk"];
                }
            }
        } else {
            $tid = explode(",", $data['query'][0]['testid']);
        }
        foreach ($tid as $t_key) {
            $p_test = $this->add_result_model->get_val("SELECT sub_test_master.* FROM `sub_test_master` INNER JOIN `test_master` ON `sub_test_master`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `sub_test_master`.`status`='1' AND `sub_test_master`.`test_fk`='" . $t_key . "'");
            foreach ($p_test as $tp_key) {
                $tid[] = $tp_key["sub_test"];
            }
        }
        $tid = array_unique($tid);
        $cnt = 0;
        $new_data_array = array();
        foreach ($tid as $tst_id) {
            if (in_array($tst_id, $approved_test)) {
                $get_test_parameter = $this->add_result_model->get_val("SELECT `test_parameter`.*,`test_master`.`test_name` FROM `test_parameter` INNER JOIN `test_master` ON `test_parameter`.`test_fk`=`test_master`.`id` WHERE `test_parameter`.`status`='1' AND `test_master`.`status`='1' AND `test_parameter`.`test_fk`='" . $tst_id . "' order by `test_parameter`.order asc");
                //print_R($get_test_parameter); die();
                $pid = array();
                foreach ($get_test_parameter as $tp_key) {
                    $pid[] = $tp_key["parameter_fk"];
                }
                if (!empty($pid)) {
                    $para = $this->add_result_model->get_val("SELECT * FROM `test_parameter_master` WHERE `status`='1' AND id IN (" . implode(",", $pid) . ") ORDER BY FIELD(id," . implode(",", $pid) . ")");
                    $cnt_1 = 0;
                    foreach ($para as $para_key) {
                        $formula = $this->add_result_model->get_val("SELECT * from use_formula where test_fk='" . $tst_id . "' and job_fk='" . $data['cid'] . "' and status='1'");
                        $get_test_parameter[$cnt_1]['use_formula'] = $formula[0]["use_formula"];
                        $get_test_parameter[$cnt_1]['on_new_page'] = $formula[0]["on_new_page"];
                        $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                        $get_test_parameter[$cnt_1]['graph'] = $graph_pic;
                        $get_test_parameter1 = $get_test_parameter[$cnt_1];
//echo "SELECT * from user_test_result where test_id='".$tst_id."' and parameter_id='" . $para_key["id"] . "' and job_id='".$data['cid']."' and  status='1'";
                        $para_user_val = $this->add_result_model->get_val("SELECT * from user_test_result where test_id='" . $tst_id . "' and parameter_id='" . $para_key["id"] . "' and job_id='" . $data['cid'] . "' and  status='1'");
                        $para[$cnt_1]["user_val"] = $para_user_val;
                        $para_ref_rng = $this->add_result_model->get_val("SELECT * FROM `parameter_referance_range` WHERE `status`='1' AND parameter_fk='" . $para_key["id"] . "' order by gender asc");
                        $final_qry = "SELECT * FROM `parameter_referance_range` WHERE STATUS='1' AND `parameter_fk`='" . $para_key["id"] . "'";
                        /* if ($data['user_data'][0]["age"] > 0 && $data['user_data'][0]["age"] < 6 && $data['user_data'][0]["age_type"] == 'D') {
                          $final_qry .= " AND gender='N'  AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='D'";
                          } else if ($data['user_data'][0]["age"] > 5 && $data['user_data'][0]["age_type"] == 'D') {
                          $final_qry .= " AND gender='C' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='D'";
                          $data["common"] = 0;
                          } else if ($data['user_data'][0]["age"] > 0 && $data['user_data'][0]["age"] < 8 && $data['user_data'][0]["age_type"] == 'Y') {
                          $final_qry .= " AND gender='C' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } else if ($para_ref_rng[0]["gender"] == 'B' && $data['user_data'][0]["age_type"] == 'Y') {
                          //$get_both_age = $this->add_result_model->get_val("SELECT * FROM `parameter_referance_range` WHERE STATUS='1' AND `parameter_fk`='117' AND gender='B' AND `no_period` > ".$data['user_data'][0]["age"]." AND `type_period`='Y' ORDER BY id ASC");
                          $final_qry .= " AND gender='B' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } else if (strtoupper($data['user_data'][0]["gender"]) == 'MALE' && $data['user_data'][0]["age_type"] == 'Y') {
                          //$get_male_age = $this->add_result_model->get_val("SELECT * FROM `parameter_referance_range` WHERE STATUS='1' AND `parameter_fk`='117' AND gender='M' AND `no_period` > ".$data['user_data'][0]["age"]." AND `type_period`='Y' ORDER BY id ASC");
                          //print_r($get_male_age);
                          $final_qry .= " AND gender='M' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } else if (strtoupper($data['user_data'][0]["gender"]) == 'FEMALE' && $data['user_data'][0]["age_type"] == 'Y') {
                          $final_qry .= " AND gender='F' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } */
                        if ($data['user_data'][0]["age_type"] == 'D') {
                            $final_qry .= " AND gender='N'  AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='D'";
                        } else if ($data['user_data'][0]["age_type"] == 'M') {
                            $final_qry .= " AND gender='C' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='M'";
                            $data["common"] = 0;
                        } else if ($para_ref_rng[0]["gender"] == 'B' && $data['user_data'][0]["age_type"] == 'Y') {
                            $final_qry .= " AND gender='B' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                            $data["common"] = 0;
                        } else if (strtoupper($data['user_data'][0]["gender"]) == 'MALE' && $data['user_data'][0]["age_type"] == 'Y') {
                            $final_qry .= " AND gender='M' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                            $data["common"] = 0;
                        } else if (strtoupper($data['user_data'][0]["gender"]) == 'FEMALE' && $data['user_data'][0]["age_type"] == 'Y') {
                            $final_qry .= " AND gender='F' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                            $data["common"] = 0;
                        }
                        //$final_qry = $final_qry . " AND is_group='1' ";
                        $final_qry = $final_qry . " ORDER BY `type_period` ASC limit 0,1";
                        $final_qry1 = "SELECT * FROM `test_result_status` WHERE STATUS='1' AND `parameter_fk`='" . $para_key["id"] . "'";
                        $data["common"] = 1;
                        $data["para_ref_rng"] = $this->add_result_model->get_val($final_qry);
                        $data["para_ref_rng"][0]["common"] = "1";
                        $data["para_ref_rng"][0]["tst_id"] = $tst_id;
                        $para[$cnt_1]['para_ref_rng'] = $data["para_ref_rng"];

                        $data["para_ref_status"] = $this->add_result_model->get_val($final_qry1);
                        $para[$cnt_1]['para_ref_status'] = $data["para_ref_status"];
                        $cnt_1++;
                    }
                    $get_test_parameter1[0]['parameter'] = $para;
                    $new_data_array[] = $get_test_parameter1;
                } else {
                    $get_test_parameter1 = $this->add_result_model->get_val("SELECT id as test_fk,test_name FROM `test_master` WHERE id='" . $tst_id . "'");
                    $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                    $get_test_parameter1[0]['graph'] = $graph_pic;
                    $new_data_array[] = $get_test_parameter1[0];
                }

                $cnt++;
            }
        }
//echo "<pre>"; print_r($new_data_array); die();
        $data["new_data_array"] = $new_data_array;
        $data['result_list'] = $this->add_result_model->master_fun_get_tbl_val("user_test_result", array('status' => 1, 'job_id' => $data['cid']), array("id", "asc"));
//print_r($data['result_list']); die();
//$data['unit_list'] = $this->add_result_model->unit_list();
//$new_time = date("Y-m-d H:i:s", strtotime('+3 hours'));
        if ($data['type'] == 'wlp') {
            $pdfFilePath = FCPATH . "/upload/report/" . $data['query'][0]['order_id'] . "_result_wlpd.pdf";
        } else {
            $pdfFilePath = FCPATH . "/upload/report/" . $data['query'][0]['order_id'] . "_result.pdf";
        }
        $data['page_title'] = 'AirmedLabs'; // pass data to the view
        ini_set('memory_limit', '128M'); // boost the memory limit if it's low <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="?" draggable="false" class="emoji">
        $html = $this->load->view('result_pdf', $data, true); // render the view into HTML 
//$param = '"en-GB-x","A4","","",10,10,0,10,6,3,"P"'; // Landscape
//$lorem = utf8_encode($html); // render the view into HTML
//$html = "<!DOCTYPE html>                         <html><body>\u0627\u0644\u0643\u0647\u0631\u0628\u0627\u0621 \u0648 \u0627\u0644\u0633\u0628\u0627\u0643\u0629</body></html>      ";
        if (file_exists($pdfFilePath)) {
            $this->delete_downloadfile($pdfFilePath);
        }
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->autoScriptToLang = true;
        $pdf->baseScript = 1; // Use values in classes/ucdn.php  1 = LATIN
        $pdf->autoVietnamese = true;
        $pdf->autoArabic = true;

        $pdf->autoLangToFont = true;

        $name = "DR. Self";
        if ($data["query"][0]['dname'] != "") {
            $name = ucfirst($data["query"][0]['dname']);
        }
        $base_url = base_url();
        if ($data['type'] == 'wlp') {
            $content = $this->add_result_model->master_fun_get_tbl_val("pdf_design", array('branch_fk' => $branch_fk), array("id", "asc"));
            //print_r($content); die();
            $find = array(
                '/{{BARCODE}}/',
                '/{{CUSTID}}/',
                '/{{REGDATE}}/',
                '/{{COLLECTIONON}}/',
                '/{{NAME}}/',
                '/{{REPORTDATE}}/',
                '/{{AGE}}/',
                '/{{GENDER}}/',
                '/{{REFFERBY}}/',
                '/{{LOCATION}}/',
                '/{{TELENO}}/'
            );
            $barecode_url = $base_url . 'user_assets/images/pdf_barcode.png';
            $logo_url = $base_url . 'user_assets/images/logoaastha.png';
            $barecode_url = $base_url . 'user_assets/images/pdf_barcode.png';
            $sign_url = $base_url . 'user_assets/images/dr_gupta_sign.png';
            $phone_url = $base_url . 'user_assets/images/pdf_phn_btn.png';
            $replace = array(
                'pdf_barcode.png',
                $id,
                date("d-M-Y g:i", strtotime($data["query"][0]['regi_date'])),
                date("d-M-Y g:i", strtotime($data["query"][0]['date'])),
                strtoupper($data["user_data"][0]['full_name']),
                date('d-M-Y'),
                $data["user_data"][0]['age'] . " " . $data['user_data'][0]["age_type"],
                strtoupper($data["user_data"][0]['gender']),
                strtoupper($name),
                strtoupper($data["query"][0]['test_city_name']),
                $data["user_data"][0]['mobile']
            );
            $header = preg_replace($find, $replace, $content[0]["header"]);


            $pdf->SetHTMLHeader($header);
            $pdf->AddPage('p', // L - landscape, P - portrait
                    '', '', '', '', 5, // margin_left
                    5, // margin right
                    72, // margin top
                    72, // margin bottom
                    2, // margin header 
                    2); // margin footer
            $sign_url = $base_url . 'user_assets/images/dr_gupta_sign.png';
            $phone_url = $base_url . 'user_assets/images/pdf_phn_btn.png';
            $emailimg = $base_url . 'user_assets/images/email-icon.png';
            $webimg = $base_url . 'user_assets/images/web-icon.png';
            $lastimg = $base_url . 'user_assets/images/lastimg.png';
            $pdf->SetHTMLFooter($content[0]["footer"]);
        } else {
            if ($branch_fk == 1 || $branch_fk == 2 || $branch_fk == 6 || $branch_fk == 7 || $branch_fk == 8 || $branch_fk == 9) {
                $pdf_id = 1;
            } else {
                $pdf_id = 11;
            }
            $content = $this->add_result_model->master_fun_get_tbl_val("pdf_design", array('id' => $pdf_id), array("id", "asc"));
            $find = array(
                '/{{BARCODE}}/',
                '/{{CUSTID}}/',
                '/{{REGDATE}}/',
                '/{{COLLECTIONON}}/',
                '/{{NAME}}/',
                '/{{REPORTDATE}}/',
                '/{{AGE}}/',
                '/{{GENDER}}/',
                '/{{REFFERBY}}/',
                '/{{LOCATION}}/',
                '/{{TELENO}}/'
            );
            $replace = array(
                'pdf_barcode.png',
                $id,
                date("d-M-Y g:i", strtotime($data["query"][0]['regi_date'])),
                date("d-M-Y g:i", strtotime($data["query"][0]['date'])),
                strtoupper($data["user_data"][0]['full_name']),
                date('d-M-Y'),
                $data["user_data"][0]['age'] . " " . $data['user_data'][0]["age_type"],
                strtoupper($data["user_data"][0]['gender']),
                strtoupper($name),
                strtoupper($data["query"][0]['test_city_name']),
                $data["user_data"][0]['mobile']
            );
            $header = preg_replace($find, $replace, $content[0]["header"]);

            $pdf->SetHTMLHeader($header);
            $pdf->AddPage('p', // L - landscape, P - portrait
                    '', '', '', '', 5, // margin_left
                    5, // margin right
                    72, // margin top
                    72, // margin bottom
                    2, // margin header 
                    2); // margin footer
            $pdf->SetHTMLFooter($content[0]["footer"]);
        }
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
        //echo $html; die();
        $pdf->WriteHTML($html);
//nishit index end
//   $pdf->debug = true;
//$pdf->SetFooter('www.' . $_SERVER['HTTP_HOST'] . '||' . $new_time); // Add a footer for good measure <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="?" draggable="false" class="emoji">
// $pdf->WriteHTML($html); // write the HTML into the PDF
        $pdf->Output($pdfFilePath, 'F'); // save to file because we can
        if ($data['type'] == 'wlp') {
            $name = $data['query'][0]['order_id'] . "_result_wlpd.pdf";
        } else {
            $name = $data['query'][0]['order_id'] . "_result.pdf";
        }
        $count = $this->add_result_model->master_fun_get_tbl_val("report_master", array('job_fk' => $cid), array("id", "asc"));
        if (!empty($count)) {
            $data1 = array('job_fk' => $cid, 'report' => $name, 'status' => 1, "original" => $name, "type" => "c", "updated_date" => date("Y-m-d H:i:s"));
//$this->add_result_model->master_fun_update('report_master', array('job_fk', $cid), $data1);
        } else {
            $data1 = array('job_fk' => $cid, 'report' => $name, 'status' => 1, "original" => $name, "type" => "c", "created_date" => date("Y-m-d H:i:s"));
//$this->add_result_model->master_fun_insert("report_master", $data1);
        }
        if ($data['type'] == 'wlp') {
            $downld = $this->_push_file($pdfFilePath, $data['query'][0]['order_id'] . "_result_wlpd.pdf");
            $this->delete_downloadfile($pdfFilePath);
            redirect("/upload/report/" . $data['query'][0]['order_id'] . "_result_wlpd.pdf");
        } else {
            $downld = $this->_push_file($pdfFilePath, $data['query'][0]['order_id'] . "_result.pdf");
            $this->delete_downloadfile($pdfFilePath);
            redirect("/upload/report/" . $data['query'][0]['order_id'] . "_result.pdf");
        }
    }

    function approve_report_old($id) {
        ini_set('max_execution_time', 300); //300 seconds = 5 minutes
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data['cid'] = $id;
        $data["type"] = $this->input->get("type");
        $data['query'] = $this->add_result_model->job_details($data['cid']);
        $branch_fk = $data["branch_fk"] = $data['query'][0]["branch_fk"];
        $data['user_booking_info'] = $this->add_result_model->master_fun_get_tbl_val("booking_info", array('status' => 1, 'id' => $data['query'][0]["booking_info"]), array("id", "asc"));
        $data['user_data'] = $this->add_result_model->master_fun_get_tbl_val("customer_master", array('status' => 1, 'id' => $data['query'][0]["custid"]), array("id", "asc"));
        /* if (empty($data['user_data'][0]["gender"]) && empty($data['user_data'][0]["age"])) {
          $data['user_data'][0]["gender"] = 'male';
          $data['user_data'][0]["age"] = 24;
          } */
        $get_approved_test_list = $this->add_result_model->master_fun_get_tbl_val("approve_job_test", array('status' => '1', 'job_fk' => $data['cid']), array("id", "asc"));
        $approved_test = array();
        foreach ($get_approved_test_list as $at_key) {
            $approved_test[] = $at_key["test_fk"];
        }
        if ($data['user_booking_info'][0]["family_member_fk"] != 0) {
            $data['user_family_info'] = $this->add_result_model->master_fun_get_tbl_val("customer_family_master", array('status' => 1, 'id' => $data['user_booking_info'][0]["family_member_fk"]), array("id", "asc"));
            $data['user_data'][0]["gender"] = $data['user_family_info'][0]["gender"];
            $data['user_data'][0]["age"] = $data['user_family_info'][0]["age"];
            $data['user_data'][0]["age_type"] = $data['user_family_info'][0]["age_type"];
            $data['user_data'][0]["full_name"] = $data['user_family_info'][0]["name"];
            $data['user_data'][0]["email"] = $data['user_family_info'][0]["email"];
            $data['user_data'][0]["phone"] = $data['user_family_info'][0]["phone"];
            $data['user_data'][0]["dob"] = $data['user_family_info'][0]["dob"];
        }

        /* Check bitrth date start */
        $var = 0;
        if ($data['user_data'][0]["dob"] != '') {
            $var = 1;
            $this->load->library("util");
            $util = new Util;
            $age = $util->get_age($data['user_data'][0]["dob"]);
            if ($age[0] != 0) {
                $data['user_data'][0]["age"] = $age[0];
                $data['user_data'][0]["age_type"] = 'Y';
            }
            if ($age[0] == 0 && $age[1] != 0) {
                $data['user_data'][0]["age"] = $age[1];
                $data['user_data'][0]["age_type"] = 'M';
            }
            if ($age[0] == 0 && $age[1] == 0 && $age[2] != 0) {
                $data['user_data'][0]["age"] = $age[2];
                $data['user_data'][0]["age_type"] = 'D';
            }
        } else {
            $data['user_data'][0]["age"] = '-';
            $data['user_data'][0]["age_type"] = '';
        }
        /* Check birth date end */
        $tid = array();
        $data['parameter_list'] = array();
        if (trim($data['query'][0]['testid']) == null && $data['query'][0]["packageid"] != null) {
            $package_id = $data['query'][0]["packageid"];
            $pid = explode("%", $data['query'][0]['packageid']);
            foreach ($pid as $pkey) {
                $p_test = $this->add_result_model->get_val("SELECT `package_test`.`test_fk` FROM `package_test` INNER JOIN `test_master` ON `package_test`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `package_test`.`status`='1' AND `package_test`.`package_fk`='" . $pkey . "'");
                foreach ($p_test as $tp_key) {
                    $tid[] = $tp_key["test_fk"];
                }
            }
        } else if (trim($data['query'][0]['testid']) != null && $data['query'][0]["packageid"] != null) {

            $tid = explode(",", $data['query'][0]['testid']);
            $package_id = $data['query'][0]["packageid"];
            $pid = explode("%", $data['query'][0]['packageid']);
            foreach ($pid as $pkey) {
                $p_test = $this->add_result_model->get_val("SELECT `package_test`.`test_fk` FROM `package_test` INNER JOIN `test_master` ON `package_test`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `package_test`.`status`='1' AND `package_test`.`package_fk`='" . $pkey . "'");
                foreach ($p_test as $tp_key) {
                    $tid[] = $tp_key["test_fk"];
                }
            }
        } else {
            $tid = explode(",", $data['query'][0]['testid']);
        }
        foreach ($tid as $t_key) {
            $p_test = $this->add_result_model->get_val("SELECT sub_test_master.* FROM `sub_test_master` INNER JOIN `test_master` ON `sub_test_master`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `sub_test_master`.`status`='1' AND `sub_test_master`.`test_fk`='" . $t_key . "'");
            foreach ($p_test as $tp_key) {
                $tid[] = $tp_key["sub_test"];
            }
        }
        $tid = array_unique($tid);
        $cnt = 0;
        $new_data_array = array();
        foreach ($tid as $tst_id) {
            if (in_array($tst_id, $approved_test)) {
                //$get_test_parameter = $this->add_result_model->get_val("SELECT `test_parameter`.*,`test_master`.`test_name` FROM `test_parameter` INNER JOIN `test_master` ON `test_parameter`.`test_fk`=`test_master`.`id` WHERE `test_parameter`.`status`='1' AND `test_master`.`status`='1' AND `test_parameter`.`test_fk`='" . $tst_id . "' order by `test_parameter`.order asc");
                $get_test_parameter = $this->add_result_model->get_val("SELECT `test_parameter`.*,`test_master`.`test_name`,test_department_master.`name` AS 'department_name' FROM `test_parameter` INNER JOIN `test_master` ON `test_parameter`.`test_fk`=`test_master`.`id`     LEFT JOIN  `test_department_master` ON test_department_master.`id`=`test_master`.`department_fk`  WHERE `test_parameter`.`status`='1' AND `test_master`.`status`='1' AND `test_parameter`.`test_fk`='" . $tst_id . "' order by `test_parameter`.order asc");


                $pid = array();
                foreach ($get_test_parameter as $tp_key) {
                    $pid[] = $tp_key["parameter_fk"];
                }
                if (!empty($pid)) {
                    $para = $this->add_result_model->get_val("SELECT * FROM `test_parameter_master` WHERE `status`='1' AND id IN (" . implode(",", $pid) . ") ORDER BY FIELD(id," . implode(",", $pid) . ")");
                    $cnt_1 = 0;
                    foreach ($para as $para_key) {
                        $formula = $this->add_result_model->get_val("SELECT * from use_formula where test_fk='" . $tst_id . "' and job_fk='" . $data['cid'] . "' and status='1'");
                        $get_test_parameter[$cnt_1]['use_formula'] = $formula[0]["use_formula"];
                        $get_test_parameter[$cnt_1]['on_new_page'] = $formula[0]["on_new_page"];
                        $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                        $get_test_parameter[$cnt_1]['graph'] = $graph_pic;
                        $get_test_parameter1 = $get_test_parameter[$cnt_1];
//echo "SELECT * from user_test_result where test_id='".$tst_id."' and parameter_id='" . $para_key["id"] . "' and job_id='".$data['cid']."' and  status='1'";
                        $para_user_val = $this->add_result_model->get_val("SELECT * from user_test_result where test_id='" . $tst_id . "' and parameter_id='" . $para_key["id"] . "' and job_id='" . $data['cid'] . "' and  status='1'");
                        $para[$cnt_1]["user_val"] = $para_user_val;
                        $para_ref_rng = $this->add_result_model->get_val("SELECT * FROM `parameter_referance_range` WHERE `status`='1' AND parameter_fk='" . $para_key["id"] . "' order by gender asc");
                        $final_qry = "SELECT * FROM `parameter_referance_range` WHERE STATUS='1' AND `parameter_fk`='" . $para_key["id"] . "'";
                        if ($data['user_data'][0]["age"] == '') {
                            $data['user_data'][0]["age"] = 0;
                        }
                        if ($data['user_data'][0]["age_type"] == 'D') {
                            $final_qry .= " AND gender='N'  AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='D'";
                        } else if ($data['user_data'][0]["age_type"] == 'M') {
                            $final_qry .= " AND gender='C' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='M'";
                            $data["common"] = 0;
                        } else if (strtoupper($data['user_data'][0]["gender"]) == 'MALE' && $data['user_data'][0]["age_type"] == 'Y') {
                            $final_qry .= " AND gender='M' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                            $data["common"] = 0;
                        } else if (strtoupper($data['user_data'][0]["gender"]) == 'FEMALE' && $data['user_data'][0]["age_type"] == 'Y') {
                            $final_qry .= " AND gender='F' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                            $data["common"] = 0;
                        } else if ($para_ref_rng[0]["gender"] == 'B' && $data['user_data'][0]["age_type"] == 'Y') {
                            $final_qry .= " AND gender='B' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                            $data["common"] = 0;
                        }
                        $final_qry = $final_qry . " ORDER BY `type_period` ASC limit 0,1";
                        $final_qry1 = "SELECT * FROM `test_result_status` WHERE STATUS='1' AND `parameter_fk`='" . $para_key["id"] . "'";
                        $data["common"] = 1;
                        $data["para_ref_rng"] = $this->add_result_model->get_val($final_qry);
                        $data["para_ref_rng"][0]["common"] = "1";
                        $data["para_ref_rng"][0]["tst_id"] = $tst_id;
                        $para[$cnt_1]['para_ref_rng'] = $data["para_ref_rng"];

                        $data["para_ref_status"] = $this->add_result_model->get_val($final_qry1);
                        $para[$cnt_1]['para_ref_status'] = $data["para_ref_status"];
                        $cnt_1++;
                    }
                    $get_test_parameter1[0]['parameter'] = $para;
                    $new_data_array[] = $get_test_parameter1;
                } else {
                    $get_test_parameter1 = $this->add_result_model->get_val("SELECT id as test_fk,test_name FROM `test_master` WHERE id='" . $tst_id . "'");
                    $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                    $get_test_parameter1[0]['graph'] = $graph_pic;
                    $new_data_array[] = $get_test_parameter1[0];
                }

                $cnt++;
            }
        }
        //print_R($new_data_array); die();
        $data["new_data_array"] = $new_data_array;
        $data['result_list'] = $this->add_result_model->master_fun_get_tbl_val("user_test_result", array('status' => 1, 'job_id' => $data['cid']), array("id", "asc"));
        $pdfFilePath = FCPATH . "/upload/report/" . $data['query'][0]['order_id'] . "_result_wlpd.pdf";
        $pdfFilePath1 = FCPATH . "/upload/report/" . $data['query'][0]['order_id'] . "_result.pdf";
        $data['page_title'] = 'AirmedLabs'; // pass data to the view
        ini_set('memory_limit', '512M'); // boost the memory limit if it's low <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="?" draggable="false" class="emoji">
        $html = $this->load->view('result_pdf', $data, true); // render the view into HTML 
        if (file_exists($pdfFilePath)) {
            $this->delete_downloadfile($pdfFilePath);
        }
        if (file_exists($pdfFilePath1)) {
            $this->delete_downloadfile($pdfFilePath1);
        }
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->autoScriptToLang = true;
        $pdf->baseScript = 1; // Use values in classes/ucdn.php  1 = LATIN
        $pdf->autoVietnamese = true;
        $pdf->autoArabic = true;
        $pdf->autoLangToFont = true;

        $name = "DR. Self";
        if ($data["query"][0]['dname'] != "") {
            $name = ucfirst($data["query"][0]['dname']);
        }
        $base_url = base_url();

        $content = $this->add_result_model->master_fun_get_tbl_val("pdf_design", array('branch_fk' => $branch_fk), array("id", "asc"));
        //print_r($content); die();
        $find = array(
            '/{{BARCODE}}/',
            '/{{CUSTID}}/',
            '/{{REGDATE}}/',
            '/{{COLLECTIONON}}/',
            '/{{NAME}}/',
            '/{{REPORTDATE}}/',
            '/{{AGE}}/',
            '/{{GENDER}}/',
            '/{{REFFERBY}}/',
            '/{{LOCATION}}/',
            '/{{TELENO}}/'
        );
        $barecode_url = $base_url . 'user_assets/images/pdf_barcode.png';
        $logo_url = $base_url . 'user_assets/images/logoaastha.png';
        $barecode_url = $base_url . 'user_assets/images/pdf_barcode.png';
        $sign_url = $base_url . 'user_assets/images/dr_gupta_sign.png';
        $phone_url = $base_url . 'user_assets/images/pdf_phn_btn.png';
        $replace = array(
            'pdf_barcode.png',
            $id . " ( " . $data["query"][0]['order_id'] . " ) ",
            date("d-M-Y g:i", strtotime($data["query"][0]['regi_date'])),
            date("d-M-Y g:i", strtotime($data["query"][0]['date'])),
            strtoupper($data["user_data"][0]['full_name']),
            date('d-M-Y'),
            $data["user_data"][0]['age'] . " " . $data['user_data'][0]["age_type"],
            strtoupper($data["user_data"][0]['gender']),
            strtoupper($name),
            strtoupper($data["query"][0]['test_city_name']),
            $data["user_data"][0]['mobile']
        );
        $header = preg_replace($find, $replace, $content[0]["header"]);


        $pdf->SetHTMLHeader($header);
        $pdf->AddPage('p', // L - landscape, P - portrait
                '', '', '', '', 5, // margin_left
                5, // margin right
                72, // margin top
                72, // margin bottom
                2, // margin header 
                2); // margin footer
        $sign_url = $base_url . 'user_assets/images/dr_gupta_sign.png';
        $phone_url = $base_url . 'user_assets/images/pdf_phn_btn.png';
        $emailimg = $base_url . 'user_assets/images/email-icon.png';
        $webimg = $base_url . 'user_assets/images/web-icon.png';
        $lastimg = $base_url . 'user_assets/images/lastimg.png';
        $pdf->SetHTMLFooter($content[0]["footer"]);
        $pdf->WriteHTML($html);
        $pdf->Output($pdfFilePath, 'F'); // save to file because we can
        $name1 = $this->without_approve_report($data);

        $name = $data['query'][0]['order_id'] . "_result_wlpd.pdf";
        $name_orgnl = $data['user_data'][0]["full_name"] . "_result_with_latterhead.pdf";
        $name_orgnl1 = $data['user_data'][0]["full_name"] . "_result.pdf";
        $count = $this->add_result_model->master_fun_get_tbl_val("report_master", array('job_fk' => $id), array("id", "asc"));
        if (!empty($count)) {
            $data1 = array('job_fk' => $id, 'report' => $name, 'status' => 1, "original" => $name_orgnl, 'without_laterpad' => $name1, "without_laterpad_original" => $name_orgnl1, "type" => "c", "updated_date" => date("Y-m-d H:i:s"));
            $this->add_result_model->master_fun_update('report_master', array('job_fk', $id), $data1);
        } else {
            $data1 = array('job_fk' => $id, 'report' => $name, 'status' => 1, "original" => $name_orgnl, "type" => "c", 'without_laterpad' => $name1, "without_laterpad_original" => $name_orgnl1, "created_date" => date("Y-m-d H:i:s"));
            $this->add_result_model->master_fun_insert("report_master", $data1);
        }
        $this->add_result_model->master_fun_update('job_master', array('id', $id), array("report_approve_by" => $data["login_data"]["id"]));
        //print_R($data1); die();
        $this->session->set_flashdata("success", array("Report successfully attached."));
        $this->add_result_model->master_fun_insert("job_log", array("job_fk" => $id, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => '', "message_fk" => "17", "date_time" => date("Y-m-d H:i:s")));

        redirect("job-master/job-details/" . $id);
    }

    function approve_report($id) {
        ini_set('max_execution_time', 300); //300 seconds = 5 minutes
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data['cid'] = $id;
        $data["type"] = $this->input->get("type");
        $data['query'] = $this->add_result_model->job_details($data['cid']);
        $data['processing_center'] = $this->add_result_model->master_fun_get_tbl_val("processing_center", array('status' => 1, 'lab_fk' => $data['query'][0]["branch_fk"]), array("id", "asc"));
        if (empty($data['processing_center'])) {
            $processing_center = '1';
        } else {
            $processing_center = $data['processing_center'][0]["branch_fk"];
        }
        $branch_fk = $data["branch_fk"] = $data['query'][0]["branch_fk"];
        $data['user_booking_info'] = $this->add_result_model->master_fun_get_tbl_val("booking_info", array('status' => 1, 'id' => $data['query'][0]["booking_info"]), array("id", "asc"));
        $data['user_data'] = $this->add_result_model->master_fun_get_tbl_val("customer_master", array('status' => 1, 'id' => $data['query'][0]["custid"]), array("id", "asc"));
        /* if (empty($data['user_data'][0]["gender"]) && empty($data['user_data'][0]["age"])) {
          $data['user_data'][0]["gender"] = 'male';
          $data['user_data'][0]["age"] = 24;
          } */
        $get_approved_test_list = $this->add_result_model->master_fun_get_tbl_val("approve_job_test", array('status' => '1', 'job_fk' => $data['cid']), array("id", "asc"));
        $approved_test = array();
        foreach ($get_approved_test_list as $at_key) {
            $approved_test[] = $at_key["test_fk"];
        }
        if ($data['user_booking_info'][0]["family_member_fk"] != 0) {
            $data['user_family_info'] = $this->add_result_model->master_fun_get_tbl_val("customer_family_master", array('status' => 1, 'id' => $data['user_booking_info'][0]["family_member_fk"]), array("id", "asc"));
            $data['user_data'][0]["gender"] = $data['user_family_info'][0]["gender"];
            $data['user_data'][0]["age"] = $data['user_family_info'][0]["age"];
            $data['user_data'][0]["age_type"] = $data['user_family_info'][0]["age_type"];
            $data['user_data'][0]["full_name"] = $data['user_family_info'][0]["name"];
            $data['user_data'][0]["email"] = $data['user_family_info'][0]["email"];
            $data['user_data'][0]["phone"] = $data['user_family_info'][0]["phone"];
            $data['user_data'][0]["dob"] = $data['user_family_info'][0]["dob"];
        }
        /* Check bitrth date start */
        $this->load->library("util");
        $util = new Util;
        $age = $util->get_age($data['user_data'][0]["dob"]);
        $ageinDays = 0;
        if ($age[0] != 0) {
            $ageinDays += ($age[0] * 365);
            $data['user_data'][0]["age"] = $age[0];
            $data['user_data'][0]["age_type"] = 'Y';
        }
        if ($age[0] == 0 && $age[1] != 0) {
            $ageinDays += ($age[1] * 30);
            $data['user_data'][0]["age"] = $age[1];
            $data['user_data'][0]["age_type"] = 'M';
        }
        if ($age[0] == 0 && $age[1] == 0 && $age[2] != 0) {
            $ageinDays += ($age[2]);
            $data['user_data'][0]["age"] = $age[2];
            $data['user_data'][0]["age_type"] = 'D';
        }

        if (isset($_REQUEST['debug'])) {
            print_r($age);
            $this->db->close();
            echo $ageinDays;
            die();
        }

        /* Check birth date end */
        $tid = array();
        $data['parameter_list'] = array();
        if (trim($data['query'][0]['testid']) == null && $data['query'][0]["packageid"] != null) {
            $package_id = $data['query'][0]["packageid"];
            $pid = explode("%", $data['query'][0]['packageid']);
            foreach ($pid as $pkey) {
                $p_test = $this->add_result_model->get_val("SELECT `package_test`.`test_fk` FROM `package_test` INNER JOIN `test_master` ON `package_test`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `package_test`.`status`='1' AND `package_test`.`package_fk`='" . $pkey . "'");
                foreach ($p_test as $tp_key) {
                    $tid[] = $tp_key["test_fk"];
                }
            }
        } else if (trim($data['query'][0]['testid']) != null && $data['query'][0]["packageid"] != null) {

            $tid = explode(",", $data['query'][0]['testid']);
            $package_id = $data['query'][0]["packageid"];
            $pid = explode("%", $data['query'][0]['packageid']);
            foreach ($pid as $pkey) {
                $p_test = $this->add_result_model->get_val("SELECT `package_test`.`test_fk` FROM `package_test` INNER JOIN `test_master` ON `package_test`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `package_test`.`status`='1' AND `package_test`.`package_fk`='" . $pkey . "'");
                foreach ($p_test as $tp_key) {
                    $tid[] = $tp_key["test_fk"];
                }
            }
        } else {
            $tid = explode(",", $data['query'][0]['testid']);
        }
        foreach ($tid as $t_key) {
            $p_test = $this->add_result_model->get_val("SELECT sub_test_master.* FROM `sub_test_master` INNER JOIN `test_master` ON `sub_test_master`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `sub_test_master`.`status`='1' AND `sub_test_master`.`test_fk`='" . $t_key . "'");
            foreach ($p_test as $tp_key) {
                $tid[] = $tp_key["sub_test"];
            }
        }
        $tid = array_unique($tid);
        $cnt = 0;
        $new_data_array = array();
        foreach ($tid as $tst_id) {
            if (in_array($tst_id, $approved_test)) {
                $get_test_parameter = $this->add_result_model->get_val("SELECT `test_parameter`.*,`test_master`.`test_name`,test_department_master.`name` AS 'department_name' FROM `test_parameter` INNER JOIN `test_master` ON `test_parameter`.`test_fk`=`test_master`.`id`     LEFT JOIN  `test_department_master` ON test_department_master.`id`=`test_master`.`department_fk`  WHERE `test_parameter`.`status`='1' AND `test_master`.`status`='1' AND `test_parameter`.`test_fk`='" . $tst_id . "' and test_parameter.center='" . $processing_center . "' order by `test_parameter`.order asc");
                // echo $this->db->last_query();

                $pid = array();
                foreach ($get_test_parameter as $tp_key) {
                    $pid[] = $tp_key["parameter_fk"];
                }
                if (!empty($pid)) {
                    $para = $this->add_result_model->get_val("SELECT * FROM `test_parameter_master` WHERE `status`='1' AND id IN (" . implode(",", $pid) . ") ORDER BY FIELD(id," . implode(",", $pid) . ")");
                    $cnt_1 = 0;
                    foreach ($para as $para_key) {
                        $formula = $this->add_result_model->get_val("SELECT * from use_formula where test_fk='" . $tst_id . "' and job_fk='" . $data['cid'] . "' and status='1'");
                        $get_test_parameter[$cnt_1]['use_formula'] = $formula[0]["use_formula"];
                        $get_test_parameter[$cnt_1]['on_new_page'] = $formula[0]["on_new_page"];
                        $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                        $get_test_parameter[$cnt_1]['graph'] = $graph_pic;
                        $get_test_parameter1 = $get_test_parameter[$cnt_1];
//echo "SELECT * from user_test_result where test_id='".$tst_id."' and parameter_id='" . $para_key["id"] . "' and job_id='".$data['cid']."' and  status='1'";
                        $para_user_val = $this->add_result_model->get_val("SELECT * from user_test_result where test_id='" . $tst_id . "' and parameter_id='" . $para_key["id"] . "' and job_id='" . $data['cid'] . "' and  status='1'");
                        $para[$cnt_1]["user_val"] = $para_user_val;
                        $para_ref_rng = $this->add_result_model->get_val("SELECT * FROM `parameter_referance_range` WHERE `status`='1' AND parameter_fk='" . $para_key["id"] . "' order by gender asc");
                        $final_qry = "SELECT * FROM `parameter_referance_range` WHERE STATUS='1' AND `parameter_fk`='" . $para_key["id"] . "'";
                        /* if ($data['user_data'][0]["age_type"] == 'D') {
                          $final_qry .= " AND gender='N'  AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='D'";
                          } else if ($data['user_data'][0]["age_type"] == 'M') {
                          $final_qry .= " AND gender='C' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='M'";
                          $data["common"] = 0;
                          } else if (strtoupper($data['user_data'][0]["gender"]) == 'MALE' && $data['user_data'][0]["age_type"] == 'Y') {
                          $final_qry .= " AND gender='M' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } else if (strtoupper($data['user_data'][0]["gender"]) == 'FEMALE' && $data['user_data'][0]["age_type"] == 'Y') {
                          $final_qry .= " AND gender='F' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } else if ($para_ref_rng[0]["gender"] == 'B' && $data['user_data'][0]["age_type"] == 'Y') {
                          $final_qry .= " AND gender='B' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } */
                        if (strtoupper($data['user_data'][0]["gender"]) == 'MALE') {
                            $final_qry .= " AND gender='M' AND (CASE WHEN (type_period= 'Y') THEN (no_period*365) ELSE (CASE WHEN (type_period= 'M') THEN (no_period*30) ELSE no_period END) END )>$ageinDays ";
                            $data["common"] = 0;
                        } else if (strtoupper($data['user_data'][0]["gender"]) == 'FEMALE' && $data['user_data'][0]["age_type"] == 'Y') {
                            $final_qry .= " AND gender='F' AND  (CASE WHEN (type_period= 'Y') THEN (no_period*365) ELSE (CASE WHEN (type_period= 'M') THEN (no_period*30) ELSE no_period END) END )>$ageinDays";
                            $data["common"] = 0;
                        }
                        $final_qry = $final_qry . " ORDER BY (
    CASE
      WHEN (type_period = 'Y') 
      THEN (no_period * 365) 
      ELSE (
        CASE
          WHEN (type_period = 'M') 
          THEN (no_period * 30) 
          ELSE no_period 
        END
      ) 
    END
  ) ASC limit 0,1";
                        if (isset($_REQUEST['debug'])) {
                            $this->db->close();
                            echo $final_qry;
                            die();
                        }
                        $final_qry1 = "SELECT * FROM `test_result_status` WHERE STATUS='1' AND `parameter_fk`='" . $para_key["id"] . "'";
                        $data["common"] = 1;
                        $data["para_ref_rng"] = $this->add_result_model->get_val($final_qry);
                        $data["para_ref_rng"][0]["common"] = "1";
                        $data["para_ref_rng"][0]["tst_id"] = $tst_id;
                        $para[$cnt_1]['para_ref_rng'] = $data["para_ref_rng"];

                        $data["para_ref_status"] = $this->add_result_model->get_val($final_qry1);
                        $para[$cnt_1]['para_ref_status'] = $data["para_ref_status"];
                        $cnt_1++;
                    }
                    $get_test_parameter1[0]['parameter'] = $para;
                    $new_data_array[] = $get_test_parameter1;
                } else {
                    $get_test_parameter1 = $this->add_result_model->get_val("SELECT id as test_fk,test_name FROM `test_master` WHERE id='" . $tst_id . "'");
                    $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                    $get_test_parameter1[0]['graph'] = $graph_pic;
                    $new_data_array[] = $get_test_parameter1[0];
                }

                $cnt++;
            }
        }
        //print_R($new_data_array); die();
        $data["new_data_array"] = $new_data_array;
        $data['result_list'] = $this->add_result_model->master_fun_get_tbl_val("user_test_result", array('status' => 1, 'job_id' => $data['cid']), array("id", "asc"));
        $pdfFilePath = FCPATH . "/upload/report/" . $data['query'][0]['order_id'] . "_result_wlpd.pdf";
        $pdfFilePath1 = FCPATH . "/upload/report/" . $data['query'][0]['order_id'] . "_result.pdf";
        $data['page_title'] = 'AirmedLabs'; // pass data to the view
        ini_set('memory_limit', '512M'); // boost the memory limit if it's low <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="?" draggable="false" class="emoji">
        $html = $this->load->view('result_pdf', $data, true); // render the view into HTML 
        //die();
        if (file_exists($pdfFilePath)) {
            $this->delete_downloadfile($pdfFilePath);
        }
        if (file_exists($pdfFilePath1)) {
            $this->delete_downloadfile($pdfFilePath1);
        }
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->autoScriptToLang = true;
        $pdf->baseScript = 1; // Use values in classes/ucdn.php  1 = LATIN
        $pdf->autoVietnamese = true;
        $pdf->autoArabic = true;
        $pdf->autoLangToFont = true;

        $name = "DR. Self";
        if ($data["query"][0]['dname'] != "") {
            $name = ucfirst($data["query"][0]['dname']);
        }
        $base_url = base_url();

        $content = $this->add_result_model->master_fun_get_tbl_val("pdf_design", array('branch_fk' => $branch_fk), array("id", "asc"));
        $data["content"] = $content;
        //print_r($content); die();
        $find = array(
            '/{{BARCODE}}/',
            '/{{CUSTID}}/',
            '/{{REGDATE}}/',
            '/{{COLLECTIONON}}/',
            '/{{NAME}}/',
            '/{{REPORTDATE}}/',
            '/{{AGE}}/',
            '/{{GENDER}}/',
            '/{{REFFERBY}}/',
            '/{{LOCATION}}/',
            '/{{TELENO}}/'
        );
        $barecode_url = $base_url . 'user_assets/images/pdf_barcode.png';
        $logo_url = $base_url . 'user_assets/images/logoaastha.png';
        $barecode_url = $base_url . 'user_assets/images/pdf_barcode.png';
        $sign_url = $base_url . 'user_assets/images/dr_gupta_sign.png';
        $phone_url = $base_url . 'user_assets/images/pdf_phn_btn.png';
        $replace = array(
            'pdf_barcode.png',
            $id . " ( " . $data["query"][0]['order_id'] . " ) ",
            date("d-M-Y g:i", strtotime($data["query"][0]['regi_date'])),
            date("d-M-Y g:i", strtotime($data["query"][0]['date'])),
            strtoupper($data["user_data"][0]['full_name']),
            date('d-M-Y'),
            $data["user_data"][0]['age'] . " " . $data['user_data'][0]["age_type"],
            strtoupper($data["user_data"][0]['gender']),
            strtoupper($name),
            strtoupper($data["query"][0]['test_city_name']),
            $data["user_data"][0]['mobile']
        );
        $header = preg_replace($find, $replace, $content[0]["header"]);
        $pdf->SetHTMLHeader($header);
        $pdf->AddPage('p', // L - landscape, P - portrait
                '', '', '', '', 5, // margin_left
                5, // margin right
                72, // margin top
                72, // margin bottom
                2, // margin header 
                2); // margin footer
        $sign_url = $base_url . 'user_assets/images/dr_gupta_sign.png';
        $phone_url = $base_url . 'user_assets/images/pdf_phn_btn.png';
        $emailimg = $base_url . 'user_assets/images/email-icon.png';
        $webimg = $base_url . 'user_assets/images/web-icon.png';
        $lastimg = $base_url . 'user_assets/images/lastimg.png';
        $pdf->SetHTMLFooter($content[0]["footer"]);
        $pdf->WriteHTML($html);
        $pdf->Output($pdfFilePath, 'F'); // save to file because we can

        $name1 = $this->without_approve_report($data);

        $name = $data['query'][0]['order_id'] . "_result_wlpd.pdf";
        $name_orgnl = $data['user_data'][0]["full_name"] . "_result_with_latterhead.pdf";
        $name_orgnl1 = $data['user_data'][0]["full_name"] . "_result.pdf";
        $count = $this->add_result_model->master_fun_get_tbl_val("report_master", array('job_fk' => $id), array("id", "asc"));
        if (!empty($count)) {
            $data1 = array('job_fk' => $id, 'report' => $name, 'status' => 1, "original" => $name_orgnl, 'without_laterpad' => $name1, "without_laterpad_original" => $name_orgnl1, "type" => "c", "updated_date" => date("Y-m-d H:i:s"));
            $this->add_result_model->master_fun_update('report_master', array('job_fk', $id), $data1);
        } else {
            $data1 = array('job_fk' => $id, 'report' => $name, 'status' => 1, "original" => $name_orgnl, "type" => "c", 'without_laterpad' => $name1, "without_laterpad_original" => $name_orgnl1, "created_date" => date("Y-m-d H:i:s"));
            $this->add_result_model->master_fun_insert("report_master", $data1);
        }
        $this->add_result_model->master_fun_update('job_master', array('id', $id), array("report_approve_by" => $data["login_data"]["id"]));
        //print_R($data1); die();
        $this->session->set_flashdata("success", array("Report successfully attached."));
        $this->add_result_model->master_fun_insert("job_log", array("job_fk" => $id, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => '', "message_fk" => "17", "date_time" => date("Y-m-d H:i:s")));

        redirect("job-master/job-details/" . $id);
    }

    function without_approve_report($data) {
        ini_set('max_execution_time', 300); //300 seconds = 5 minutes
        $pdfFilePath = FCPATH . "/upload/report/" . $data['query'][0]['order_id'] . "_result.pdf";
        $data['page_title'] = 'AirmedLabs'; // pass data to the view
        ini_set('memory_limit', '512M'); // boost the memory limit if it's low <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="?" draggable="false" class="emoji">
        $html = $this->load->view('result_pdf', $data, true); // render the view into HTML 
        if (file_exists($pdfFilePath)) {
            $this->delete_downloadfile($pdfFilePath);
        }
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->autoScriptToLang = true;
        $pdf->baseScript = 1; // Use values in classes/ucdn.php  1 = LATIN
        $pdf->autoVietnamese = true;
        $pdf->autoArabic = true;
        $pdf->autoLangToFont = true;
        $name = "DR. Self";
        if ($data["query"][0]['dname'] != "") {
            $name = ucfirst($data["query"][0]['dname']);
        }
        $base_url = base_url();
        /* $branch_fk = $data["branch_fk"];
          if ($branch_fk == 1 || $branch_fk == 2 || $branch_fk == 6 || $branch_fk == 7 || $branch_fk == 8 || $branch_fk == 9 || $branch_fk == 12) {
          $pdf_id = 1;
          } else {
          $pdf_id = 11;
          }
          if ($branch_fk == 8) {
          $pdf_id = 14;
          }
          if ($branch_fk == 11 || $branch_fk == 13) {
          $pdf_id = 16;
          }
          if ($branch_fk == 14) {
          $pdf_id = 19;
          } */
        $content = $data['content'];
        $find = array(
            '/{{BARCODE}}/',
            '/{{CUSTID}}/',
            '/{{REGDATE}}/',
            '/{{COLLECTIONON}}/',
            '/{{NAME}}/',
            '/{{REPORTDATE}}/',
            '/{{AGE}}/',
            '/{{GENDER}}/',
            '/{{REFFERBY}}/',
            '/{{LOCATION}}/',
            '/{{TELENO}}/'
        );
        $replace = array(
            'pdf_barcode.png',
            $data['cid'] . " ( " . $data["query"][0]['order_id'] . " ) ",
            date("d-M-Y g:i", strtotime($data["query"][0]['regi_date'])),
            date("d-M-Y g:i", strtotime($data["query"][0]['date'])),
            strtoupper($data["user_data"][0]['full_name']),
            date('d-M-Y'),
            $data["user_data"][0]['age'] . " " . $data['user_data'][0]["age_type"],
            strtoupper($data["user_data"][0]['gender']),
            strtoupper($name),
            strtoupper($data["query"][0]['test_city_name']),
            $data["user_data"][0]['mobile']
        );
        $header = preg_replace($find, $replace, $content[0]["without_header"]);
        $pdf->SetHTMLHeader($header);
        if ($pdf_id == 11) {
            $pdf->AddPage('p', // L - landscape, P - portrait
                    '', '', '', '', 5, // margin_left
                    5, // margin right
                    72, // margin top
                    80, // margin bottom
                    2, // margin header
                    2); // margin footer
        } else if ($pdf_id == 14) {
            $pdf->AddPage('p', // L - landscape, P - portrait
                    '', '', '', '', 5, // margin_left
                    5, // margin right
                    72, // margin top
                    80, // margin bottom
                    2, // margin header
                    2); // margin footer
        } else {
            $pdf->AddPage('p', // L - landscape, P - portrait
                    '', '', '', '', 5, // margin_left
                    5, // margin right
                    72, // margin top
                    72, // margin bottom
                    2, // margin header
                    2); // margin footer
        }
        $pdf->SetHTMLFooter($content[0]["without_footer"]);
        $pdf->WriteHTML($html);
        $pdf->Output($pdfFilePath, 'F'); // save to file because we can
        return $data['query'][0]['order_id'] . "_result.pdf";
    }

    function preview_report($id) {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data['cid'] = $this->uri->segment(3);
        $data["type"] = $this->input->get("type");
        $data['query'] = $this->add_result_model->job_details($data['cid']);
        //print_r($data['query']); die();
        $branch_fk = $data['query'][0]["branch_fk"];
        $data['user_booking_info'] = $this->add_result_model->master_fun_get_tbl_val("booking_info", array('status' => 1, 'id' => $data['query'][0]["booking_info"]), array("id", "asc"));
        $data['user_data'] = $this->add_result_model->master_fun_get_tbl_val("customer_master", array('status' => 1, 'id' => $data['query'][0]["custid"]), array("id", "asc"));
        if ($data['user_booking_info'][0]["family_member_fk"] != 0) {
            $data['user_family_info'] = $this->add_result_model->master_fun_get_tbl_val("customer_family_master", array('status' => 1, 'id' => $data['user_booking_info'][0]["family_member_fk"]), array("id", "asc"));
            $data['user_data'][0]["gender"] = $data['user_family_info'][0]["gender"];
            $data['user_data'][0]["age"] = $data['user_family_info'][0]["age"];
            $data['user_data'][0]["age_type"] = $data['user_family_info'][0]["age_type"];
            $data['user_data'][0]["full_name"] = $data['user_family_info'][0]["name"];
            $data['user_data'][0]["email"] = $data['user_family_info'][0]["email"];
            $data['user_data'][0]["phone"] = $data['user_family_info'][0]["phone"];
            $data['user_data'][0]["dob"] = $data['user_family_info'][0]["dob"];
        }

        /* Check bitrth date start */
        $this->load->library("util");
        $util = new Util;
        $age = $util->get_age($data['user_data'][0]["dob"]);
        $ageinDays = 0;
        if ($age[0] != 0) {
            $ageinDays += ($age[0] * 365);
            $data['user_data'][0]["age"] = $age[0];
            $data['user_data'][0]["age_type"] = 'Y';
        }
        if ($age[0] == 0 && $age[1] != 0) {
            $ageinDays += ($age[1] * 30);
            $data['user_data'][0]["age"] = $age[1];
            $data['user_data'][0]["age_type"] = 'M';
        }
        if ($age[0] == 0 && $age[1] == 0 && $age[2] != 0) {
            $ageinDays += ($age[2]);
            $data['user_data'][0]["age"] = $age[2];
            $data['user_data'][0]["age_type"] = 'D';
        }
        /* Check birth date end */
        $tid = array();
        $data['parameter_list'] = array();
        if (trim($data['query'][0]['testid']) == null && $data['query'][0]["packageid"] != null) {
            $package_id = $data['query'][0]["packageid"];
            $pid = explode("%", $data['query'][0]['packageid']);
            foreach ($pid as $pkey) {
                $p_test = $this->add_result_model->get_val("SELECT `package_test`.`test_fk` FROM `package_test` INNER JOIN `test_master` ON `package_test`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `package_test`.`status`='1' AND `package_test`.`package_fk`='" . $pkey . "'");
                foreach ($p_test as $tp_key) {
                    $tid[] = $tp_key["test_fk"];
                }
            }
        } else if (trim($data['query'][0]['testid']) != null && $data['query'][0]["packageid"] != null) {

            $tid = explode(",", $data['query'][0]['testid']);
            $package_id = $data['query'][0]["packageid"];
            $pid = explode("%", $data['query'][0]['packageid']);
            foreach ($pid as $pkey) {
                $p_test = $this->add_result_model->get_val("SELECT `package_test`.`test_fk` FROM `package_test` INNER JOIN `test_master` ON `package_test`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `package_test`.`status`='1' AND `package_test`.`package_fk`='" . $pkey . "'");
                foreach ($p_test as $tp_key) {
                    $tid[] = $tp_key["test_fk"];
                }
            }
        } else {
            $tid = explode(",", $data['query'][0]['testid']);
        }
        foreach ($tid as $t_key) {
            $p_test = $this->add_result_model->get_val("SELECT sub_test_master.* FROM `sub_test_master` INNER JOIN `test_master` ON `sub_test_master`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `sub_test_master`.`status`='1' AND `sub_test_master`.`test_fk`='" . $t_key . "'");
            foreach ($p_test as $tp_key) {
                $tid[] = $tp_key["sub_test"];
            }
        }
        $tid = array_unique($tid);
        $cnt = 0;
        $new_data_array = array();
        foreach ($tid as $tst_id) {
            $get_test_parameter = $this->add_result_model->get_val("SELECT `test_parameter`.*,`test_master`.`test_name` FROM `test_parameter` INNER JOIN `test_master` ON `test_parameter`.`test_fk`=`test_master`.`id` WHERE `test_parameter`.`status`='1' AND `test_master`.`status`='1' AND `test_parameter`.`test_fk`='" . $tst_id . "' order by `test_parameter`.order asc");
            $pid = array();
            foreach ($get_test_parameter as $tp_key) {
                $pid[] = $tp_key["parameter_fk"];
            }
            if (!empty($pid)) {
                $para = $this->add_result_model->get_val("SELECT * FROM `test_parameter_master` WHERE `status`='1' AND id IN (" . implode(",", $pid) . ") ORDER BY FIELD(id," . implode(",", $pid) . ")");
                $cnt_1 = 0;
                foreach ($para as $para_key) {
                    $formula = $this->add_result_model->get_val("SELECT * from use_formula where test_fk='" . $tst_id . "' and job_fk='" . $data['cid'] . "' and status='1'");
                    $get_test_parameter[$cnt_1]['use_formula'] = $formula[0]["use_formula"];
                    $get_test_parameter[$cnt_1]['on_new_page'] = $formula[0]["on_new_page"];
                    $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                    $get_test_parameter[$cnt_1]['graph'] = $graph_pic;
                    $get_test_parameter1 = $get_test_parameter[$cnt_1];
//echo "SELECT * from user_test_result where test_id='".$tst_id."' and parameter_id='" . $para_key["id"] . "' and job_id='".$data['cid']."' and  status='1'";
                    $para_user_val = $this->add_result_model->get_val("SELECT * from user_test_result where test_id='" . $tst_id . "' and parameter_id='" . $para_key["id"] . "' and job_id='" . $data['cid'] . "' and  status='1'");
                    $para[$cnt_1]["user_val"] = $para_user_val;
                    $para_ref_rng = $this->add_result_model->get_val("SELECT * FROM `parameter_referance_range` WHERE `status`='1' AND parameter_fk='" . $para_key["id"] . "' order by gender asc");
                    $final_qry = "SELECT * FROM `parameter_referance_range` WHERE STATUS='1' AND `parameter_fk`='" . $para_key["id"] . "'";
                    /* if ($data['user_data'][0]["age"] > 0 && $data['user_data'][0]["age"] < 6 && $data['user_data'][0]["age_type"] == 'D') {
                      $final_qry .= " AND gender='N'  AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='D'";
                      } else if ($data['user_data'][0]["age"] > 5 && $data['user_data'][0]["age_type"] == 'D') {
                      $final_qry .= " AND gender='C' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='D'";
                      $data["common"] = 0;
                      } else if ($data['user_data'][0]["age"] > 0 && $data['user_data'][0]["age"] < 8 && $data['user_data'][0]["age_type"] == 'Y') {
                      $final_qry .= " AND gender='C' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                      $data["common"] = 0;
                      } else if ($para_ref_rng[0]["gender"] == 'B' && $data['user_data'][0]["age_type"] == 'Y') {
                      //$get_both_age = $this->add_result_model->get_val("SELECT * FROM `parameter_referance_range` WHERE STATUS='1' AND `parameter_fk`='117' AND gender='B' AND `no_period` > ".$data['user_data'][0]["age"]." AND `type_period`='Y' ORDER BY id ASC");
                      $final_qry .= " AND gender='B' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                      $data["common"] = 0;
                      } else if (strtoupper($data['user_data'][0]["gender"]) == 'MALE' && $data['user_data'][0]["age_type"] == 'Y') {
                      //$get_male_age = $this->add_result_model->get_val("SELECT * FROM `parameter_referance_range` WHERE STATUS='1' AND `parameter_fk`='117' AND gender='M' AND `no_period` > ".$data['user_data'][0]["age"]." AND `type_period`='Y' ORDER BY id ASC");
                      //print_r($get_male_age);
                      $final_qry .= " AND gender='M' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                      $data["common"] = 0;
                      } else if (strtoupper($data['user_data'][0]["gender"]) == 'FEMALE' && $data['user_data'][0]["age_type"] == 'Y') {
                      $final_qry .= " AND gender='F' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                      $data["common"] = 0;
                      } */
                    /* if ($data['user_data'][0]["age_type"] == 'D') {
                      $final_qry .= " AND gender='N'  AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='D'";
                      } else if ($data['user_data'][0]["age_type"] == 'M') {
                      $final_qry .= " AND gender='C' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='M'";
                      $data["common"] = 0;
                      }  else if (strtoupper($data['user_data'][0]["gender"]) == 'MALE' && $data['user_data'][0]["age_type"] == 'Y') {
                      $final_qry .= " AND gender='M' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                      $data["common"] = 0;
                      } else if (strtoupper($data['user_data'][0]["gender"]) == 'FEMALE' && $data['user_data'][0]["age_type"] == 'Y') {
                      $final_qry .= " AND gender='F' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                      $data["common"] = 0;
                      }
                      else if ($para_ref_rng[0]["gender"] == 'B' && $data['user_data'][0]["age_type"] == 'Y') {
                      $final_qry .= " AND gender='B' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                      $data["common"] = 0;
                      } */
                    if (strtoupper($data['user_data'][0]["gender"]) == 'MALE') {
                        $final_qry .= " AND gender='M' AND (CASE WHEN (type_period= 'Y') THEN (no_period*365) ELSE (CASE WHEN (type_period= 'M') THEN (no_period*30) ELSE no_period END) END )>$ageinDays ";
                        $data["common"] = 0;
                    } else if (strtoupper($data['user_data'][0]["gender"]) == 'FEMALE' && $data['user_data'][0]["age_type"] == 'Y') {
                        $final_qry .= " AND gender='F' AND (CASE WHEN (type_period= 'Y') THEN (no_period*365) ELSE (CASE WHEN (type_period= 'M') THEN (no_period*30) ELSE no_period END) END )>$ageinDays";
                        $data["common"] = 0;
                    }
                    //$final_qry = $final_qry . " AND is_group='1' ";
                    $final_qry = $final_qry . " ORDER BY (
    CASE
      WHEN (type_period = 'Y') 
      THEN (no_period * 365) 
      ELSE (
        CASE
          WHEN (type_period = 'M') 
          THEN (no_period * 30) 
          ELSE no_period 
        END
      ) 
    END
  ) ASC limit 0,1";
                    $final_qry1 = "SELECT * FROM `test_result_status` WHERE STATUS='1' AND `parameter_fk`='" . $para_key["id"] . "'";
                    $data["common"] = 1;
                    $data["para_ref_rng"] = $this->add_result_model->get_val($final_qry);
                    $data["para_ref_rng"][0]["common"] = "1";
                    $data["para_ref_rng"][0]["tst_id"] = $tst_id;
                    $para[$cnt_1]['para_ref_rng'] = $data["para_ref_rng"];

                    $data["para_ref_status"] = $this->add_result_model->get_val($final_qry1);
                    $para[$cnt_1]['para_ref_status'] = $data["para_ref_status"];
                    $cnt_1++;
                }
                $get_test_parameter1[0]['parameter'] = $para;
                $new_data_array[] = $get_test_parameter1;
            } else {
                $get_test_parameter1 = $this->add_result_model->get_val("SELECT id as test_fk,test_name FROM `test_master` WHERE id='" . $tst_id . "'");
                $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                $get_test_parameter1[0]['graph'] = $graph_pic;
                $new_data_array[] = $get_test_parameter1[0];
            }

            $cnt++;
        }
        $data["new_data_array"] = $new_data_array;
        $data['result_list'] = $this->add_result_model->master_fun_get_tbl_val("user_test_result", array('status' => 1, 'job_id' => $data['cid']), array("id", "asc"));
        if ($data['type'] == 'wlp') {
            $pdfFilePath = FCPATH . "/upload/report/" . $data['query'][0]['order_id'] . "_result_wlpd.pdf";
        } else {
            $pdfFilePath = FCPATH . "/upload/report/" . $data['query'][0]['order_id'] . "_result.pdf";
        }
        $data['page_title'] = 'AirmedLabs'; // pass data to the view
        ini_set('memory_limit', '128M'); // boost the memory limit if it's low <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="?" draggable="false" class="emoji">
        $html = $this->load->view('result_pdf', $data, true); // render the view into HTML 
        if (file_exists($pdfFilePath)) {
            $this->delete_downloadfile($pdfFilePath);
        }
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->autoScriptToLang = true;
        $pdf->baseScript = 1; // Use values in classes/ucdn.php  1 = LATIN
        $pdf->autoVietnamese = true;
        $pdf->autoArabic = true;

        $pdf->autoLangToFont = true;
        //$pdf->SetWatermarkImage('airmedlogo_not_auth.png');
        $pdf->SetWatermarkText("This is preview copy and its unauthorised");
        $pdf->showWatermarkText = true;
        //$pdf->showWatermarkImage = true;
        $name = "DR. Self";
        if ($data["query"][0]['dname'] != "") {
            $name = ucfirst($data["query"][0]['dname']);
        }
        $base_url = base_url();
        if ($data['type'] == 'wlp') {
            $content = $this->add_result_model->master_fun_get_tbl_val("pdf_preview_design", array('branch_fk' => $branch_fk), array("id", "asc"));
            //print_r($content); die();
            $find = array(
                '/{{BARCODE}}/',
                '/{{CUSTID}}/',
                '/{{REGDATE}}/',
                '/{{COLLECTIONON}}/',
                '/{{NAME}}/',
                '/{{REPORTDATE}}/',
                '/{{AGE}}/',
                '/{{GENDER}}/',
                '/{{REFFERBY}}/',
                '/{{LOCATION}}/',
                '/{{TELENO}}/'
            );
            $barecode_url = $base_url . 'user_assets/images/pdf_barcode.png';
            $logo_url = $base_url . 'user_assets/images/logoaastha.png';
            $barecode_url = $base_url . 'user_assets/images/pdf_barcode.png';
            $sign_url = $base_url . 'user_assets/images/dr_gupta_sign.png';
            $phone_url = $base_url . 'user_assets/images/pdf_phn_btn.png';
            $replace = array(
                'pdf_barcode.png',
                $id,
                date("d-M-Y g:i", strtotime($data["query"][0]['regi_date'])),
                date("d-M-Y g:i", strtotime($data["query"][0]['date'])),
                strtoupper($data["user_data"][0]['full_name']),
                date('d-M-Y'),
                $data["user_data"][0]['age'] . " " . $data['user_data'][0]["age_type"],
                strtoupper($data["user_data"][0]['gender']),
                strtoupper($name),
                strtoupper($data["query"][0]['test_city_name']),
                $data["user_data"][0]['mobile']
            );
            $header = preg_replace($find, $replace, $content[0]["header"]);

            $pdf->SetHTMLHeader($header);
            $pdf->AddPage('p', // L - landscape, P - portrait
                    '', '', '', '', 5, // margin_left
                    5, // margin right
                    72, // margin top
                    72, // margin bottom
                    2, // margin header 
                    2); // margin footer
            $sign_url = $base_url . 'user_assets/images/dr_gupta_sign.png';
            $phone_url = $base_url . 'user_assets/images/pdf_phn_btn.png';
            $emailimg = $base_url . 'user_assets/images/email-icon.png';
            $webimg = $base_url . 'user_assets/images/web-icon.png';
            $lastimg = $base_url . 'user_assets/images/lastimg.png';
            $pdf->SetHTMLFooter($content[0]["footer"]);
        } else {
            if ($branch_fk == 1 || $branch_fk == 2 || $branch_fk == 6 || $branch_fk == 7 || $branch_fk == 8 || $branch_fk == 9 || $branch_fk == 12) {
                $pdf_id = 1;
            } else {
                $pdf_id = 11;
            }
            if ($branch_fk == 8) {
                $pdf_id = 14;
            }
            if ($branch_fk == 11) {
                $pdf_id = 16;
            }
            $content = $this->add_result_model->master_fun_get_tbl_val("pdf_preview_design", array('id' => $pdf_id), array("id", "asc"));
            $find = array(
                '/{{BARCODE}}/',
                '/{{CUSTID}}/',
                '/{{REGDATE}}/',
                '/{{COLLECTIONON}}/',
                '/{{NAME}}/',
                '/{{REPORTDATE}}/',
                '/{{AGE}}/',
                '/{{GENDER}}/',
                '/{{REFFERBY}}/',
                '/{{LOCATION}}/',
                '/{{TELENO}}/'
            );
            $replace = array(
                'pdf_barcode.png',
                $id,
                date("d-M-Y g:i", strtotime($data["query"][0]['regi_date'])),
                date("d-M-Y g:i", strtotime($data["query"][0]['date'])),
                strtoupper($data["user_data"][0]['full_name']),
                date('d-M-Y'),
                $data["user_data"][0]['age'] . " " . $data['user_data'][0]["age_type"],
                strtoupper($data["user_data"][0]['gender']),
                strtoupper($name),
                strtoupper($data["query"][0]['test_city_name']),
                $data["user_data"][0]['mobile']
            );
            $header = preg_replace($find, $replace, $content[0]["header"]);

            $pdf->SetHTMLHeader($header);
            $pdf->AddPage('p', // L - landscape, P - portrait
                    '', '', '', '', 5, // margin_left
                    5, // margin right 
                    72, // margin top
                    72, // margin bottom
                    2, // margin header 
                    2); // margin footer
            $pdf->SetHTMLFooter($content[0]["footer"]);
        }
        $pdf->WriteHTML($html);
        $pdf->Output($pdfFilePath, 'F'); // save to file because we can
        if ($data['type'] == 'wlp') {
            $name = $data['query'][0]['order_id'] . "_result_wlpd.pdf";
        } else {
            $name = $data['query'][0]['order_id'] . "_result.pdf";
        }
        $count = $this->add_result_model->master_fun_get_tbl_val("report_master", array('job_fk' => $cid), array("id", "asc"));
        if (!empty($count)) {
            $data1 = array('job_fk' => $cid, 'report' => $name, 'status' => 1, "original" => $name, "type" => "c", "updated_date" => date("Y-m-d H:i:s"));
        } else {
            $data1 = array('job_fk' => $cid, 'report' => $name, 'status' => 1, "original" => $name, "type" => "c", "created_date" => date("Y-m-d H:i:s"));
        }
        $this->add_result_model->master_fun_insert("job_log", array("job_fk" => $id, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => '', "message_fk" => "18", "date_time" => date("Y-m-d H:i:s")));

        if ($data['type'] == 'wlp') {
//            $downld = $this->_push_file($pdfFilePath, $data['query'][0]['order_id'] . "_result_wlpd.pdf");
//            $this->delete_downloadfile($pdfFilePath);
            redirect("/upload/report/" . $data['query'][0]['order_id'] . "_result_wlpd.pdf?" . rand(11111, 99999));
        } else {
//            $downld = $this->_push_file($pdfFilePath, $data['query'][0]['order_id'] . "_result.pdf");
//             $this->delete_downloadfile($pdfFilePath);
            redirect("/upload/report/" . $data['query'][0]['order_id'] . "_result.pdf?" . rand(11111, 99999));
        }
    }

    private function _push_file($path, $name) {
        // make sure it's a file before doing anything!
        if (is_file($path)) {
            // required for IE
            if (ini_get('zlib.output_compression')) {
                ini_set('zlib.output_compression', 'Off');
            }
            // get the file mime type using the file extension
            $this->load->helper('file');

            $mime = get_mime_by_extension($path);

            // Build the headers to push out the file properly.
            header('Pragma: public');     // required
            header('Expires: 0');         // no cache
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($path)) . ' GMT');
            header('Cache-Control: private', false);
            header('Content-Type: ' . $mime);  // Add the mime type from Code igniter.
            header('Content-Disposition: attachment; filename="' . basename($name) . '"');  // Add the file name
            header('Content-Transfer-Encoding: binary');
            header('Content-Length: ' . filesize($path)); // provide file size
            header('Connection: close');
            readfile($path); // push it out
//    exit(); 
        }
    }

    function delete_downloadfile($path) {
        $this->load->helper('file');
        unlink($path);
    }

    function delete_parameter($pid, $jid, $tid) {
        $data["login_data"] = logindata();
        $data1 = array('status' => 0);
        $update = $this->add_result_model->master_fun_update12('test_parameter', array('parameter_fk', $pid, "test_fk", $tid), $data1);
//$this->add_result_model->master_fun_update('test_parameter_master', array('id', $pid), $data1);
        if ($update) {
            redirect("add_result/test_details/" . $jid);
        }
    }

    function delete_graph() {
        $data["login_data"] = logindata();
        $id = $this->uri->segment(3);
        $jid = $this->uri->segment(4);
        $data1 = array('status' => '0');
        $update = $this->add_result_model->master_fun_update('user_formula_pic', array('id', $id), $data1);
        if ($update) {
            redirect("add_result/test_details/" . $jid);
        }
    }

    function edit_parameter() {
        $para_id = $this->input->post('para_id');
        $unit_list = $this->add_result_model->unit_list();
        $details = $this->add_result_model->master_fun_get_tbl_val("test_parameter_master", array('id' => $para_id), array("id", "asc"));
        $range = explode("-", $details[0]['parameter_range']);
        echo '<tr><input type="hidden" value="' . $para_id . '" name="update_pid">
                                <td>
                                    <input type="text" name="update_par_name" id="update_par_name" class="form-control" value="' . $details[0]['parameter_name'] . '">
                                        <span id="update_par_name_error" style="color:red;"></span>
                                </td>
                                <td>
                                    <input type="text" name="update_par_min" id="update_par_min" class="form-control" value="' . $range[0] . '">
                                        <span id="update_par_min_error" style="color:red;"></span>
                                </td>
                                <td>
                                    <input type="text" name="update_par_max" id="update_par_max" class="form-control" value="' . $range[1] . '">
                                        <span id="update_par_max_error" style="color:red;"></span>
                                </td>
                                <td>
                                    <select class="form-control" name="update_par_unit" id="update_par_unit">
                                        <option value="">--Select--</option>';
        foreach ($unit_list as $unit) {
            echo '<option value="' . $unit['PARAMETER_NAME'] . '"';
            if ($unit['PARAMETER_NAME'] == $details[0]['parameter_unit']) {
                echo 'selected';
            }
            echo '>' . $unit['PARAMETER_NAME'] . '</option>';
        }
        echo '</select><span id="update_par_unit_error" style="color:red;"></span>
                                </td>
                            </tr>';
    }

    function update_parameter() {
        $job_id = $this->input->post('jobu_id');
        $update_par_name = $this->input->post('update_par_name');
        $update_par_min = $this->input->post('update_par_min');
        $update_par_max = $this->input->post('update_par_max');
        $update_par_unit = $this->input->post('update_par_unit');
        $update_par_id = $this->input->post('update_pid');
        $range = $update_par_min . "-" . $update_par_max;
        $data1 = array("parameter_name" => $update_par_name, "parameter_range" => $range, "parameter_unit" => $update_par_unit, "modified_by" => $data["login_data"]["id"], "modified_date" => date("Y-m-d H:i:s"));
        $update = $this->add_result_model->master_fun_update('test_parameter_master', array('id', $update_par_id), $data1);
        if ($update) {
            redirect("add_result/test_details/" . $job_id);
        }
    }

    function testdescription() {
        $id = $this->input->post('test_id');
        $details = $this->add_result_model->master_fun_get_tbl_val("test_master", array('id' => $id), array("id", "asc"));
        echo $details[0]['description'];
    }

    function tst($id) {
        echo $id . " Hiiii";
        echo "<a href='javascript:void(0)' onclick='parent.close_box();'>Close</a>";
    }

    function add_parameter() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data['tid'] = $this->uri->segment(3);
        $data['event'] = $this->uri->segment(4);
        if ($data['event'] != 'add') {
            $data['edit_p_tid'] = $this->uri->segment(4);
        }
        $data['branch_fk'] = $this->uri->segment(5);
        if (empty($data['branch_fk'])) {
            $data['branch_fk'] = 1;
        }
        $data['processing_center'] = $this->add_result_model->master_fun_get_tbl_val("processing_center", array('status' => 1, 'lab_fk' => $data['branch_fk']), array("id", "asc"));
        if (empty($data['processing_center'])) {
            $processing_center = '1';
        } else {
            $processing_center = $data['processing_center'][0]["branch_fk"];
        }
        $data['test_desc'] = $this->add_result_model->master_fun_get_tbl_val("test_master", array('id' => $data['tid']), array("id", "asc"));
        $data['parameter_list'] = $this->add_result_model->master_fun_get_tbl_val("test_parameter_master", array('status' => 1, "center" => $processing_center, "parameter_name !=" => ''), array("parameter_name", "asc"));
        $data['unit_list'] = $this->add_result_model->unit_list();
        $this->load->view('header');
//$this->load->view('nav', $data);
        $this->load->view('add_test_parameter', $data);
//$this->load->view('footer');
    }

    function add_value_all($tid) {
        if (!is_loggedin()) {
            redirect('login');
        }
        //echo "<pre>";
        //print_r($this->input->post());
        //die();
        $data["login_data"] = logindata();
        $par_name = $this->input->post('par_name');
        $par_unit = $this->input->post('par_unit');
        $par_formula = $this->input->post('par_formula');
        $parameter_code = $this->input->post('parameter_code');
        $count_par = $this->input->post('count_par');
        $count_ref = $this->input->post('count_ref');
        $desc_test = $this->input->post('desc_test');
        $par_id = $this->input->post('exist_para');
        $test_id = $this->input->post('test_id');
        $old_test_id = $this->input->post('old_test_id');
        $group = $this->input->post('group');
        $branch = $this->input->post('branch');
        $data['processing_center'] = $this->add_result_model->master_fun_get_tbl_val("processing_center", array('status' => 1, 'lab_fk' => $branch), array("id", "asc"));
        if (empty($data['processing_center'])) {
            $branch = '1';
        } else {
            $branch = $data['processing_center'][0]["branch_fk"];
        }

//$this->add_result_model->master_fun_update('test_master', array('id', $tid), array("description" => $desc_test));
        if (trim($group) != '') {
            $test_count = $this->add_result_model->master_fun_get_tbl_val("test_parameter", array("test_fk" => $tid, "center" => $branch, "parameter_fk" => $par_id, "status" => 1), array("id", "asc"));
            //  print_r($test_count);
            if (count($test_count) == 0 && $par_id == '') {
                $insert = $this->add_result_model->master_fun_insert("test_parameter_master", array("center" => $branch, "parameter_name" => $group, "created_by" => $data["login_data"]["id"], "is_group" => '1', "created_date" => date("Y-m-d H:i:s")));
                $val_add1 = $this->add_result_model->master_fun_insert("test_parameter", array("test_fk" => $tid, "center" => $branch, "parameter_fk" => $insert));
            } else {
                $this->add_result_model->master_fun_update('test_parameter_master', array('id', $par_id), array("parameter_name" => $group, "modified_by" => $data["login_data"]["id"], "is_group" => '1', "modified_date" => date("Y-m-d H:i:s")));
            }
        } else {
            if ($par_id == "") {
                $insert = $this->add_result_model->master_fun_insert("test_parameter_master", array("center" => $branch, "parameter_name" => $par_name, "parameter_unit" => $par_unit, "formula" => $par_formula, "created_by" => $data["login_data"]["id"], "description" => $desc_test, "created_date" => date("Y-m-d H:i:s")));
                $this->add_result_model->master_fun_insert("test_parameter", array("test_fk" => $tid, "center" => $branch, "parameter_fk" => $insert));
            } else {
                $this->add_result_model->master_fun_update('test_parameter_master', array('id', $par_id), array("center" => $branch, "parameter_name" => $par_name, "parameter_unit" => $par_unit, "code" => $parameter_code, "formula" => $par_formula, "modified_by" => $data["login_data"]["id"], "description" => $desc_test, "modified_date" => date("Y-m-d H:i:s")));
                $test_count = $this->add_result_model->master_fun_get_tbl_val("test_parameter", array("test_fk" => $tid, "parameter_fk" => $par_id, "status" => 1), array("id", "asc"));
                if (count($test_count) == 0) {
                    $this->add_result_model->master_fun_insert("test_parameter", array("test_fk" => $tid, "center" => $branch, "parameter_fk" => $par_id));
                }
                if ($old_test_id != $par_id) {
                    $this->add_result_model->master_fun_update1('test_parameter', array("test_fk" => $test_id, "center" => $branch, "parameter_fk" => $old_test_id), array("status" => 0));
                }
            }
            if ($_REQUEST["debug"] == 1) {
                echo "<pre>";
                print_r($_POST);
                die();
            }
            if ($par_id != '') {
                $edit_count_par = $this->input->post('edit_count_par');
                $edit_count_ref = $this->input->post('edit_count_ref');
                for ($k = 1; $k < $edit_count_par; $k++) {
                    $data["login_data"] = logindata();
                    $edit_par_gender = $this->input->post('edit_par_gender_' . $k);
                    $edit_par_no_period = $this->input->post('edit_par_no_period_' . $k);
                    $edit_par_type_period = $this->input->post('edit_par_type_period_' . $k);
                    $edit_par_normal_remark = $this->input->post('edit_par_normal_remark_' . $k);
                    $edit_par_ref_range_low = $this->input->post('edit_par_ref_range_low_' . $k);
                    $edit_par_low_remark = $this->input->post('edit_par_low_remark_' . $k);
                    $edit_par_ref_range_high = $this->input->post('edit_par_ref_range_high_' . $k);
                    $edit_par_high_remark = $this->input->post('edit_par_high_remark_' . $k);
                    $edit_par_critical_low = $this->input->post('edit_par_critical_low_' . $k);
                    $edit_par_critical_low_remark = $this->input->post('edit_par_critical_low_remark_' . $k);
                    $edit_par_critical_high = $this->input->post('edit_par_critical_high_' . $k);
                    $edit_par_critical_high_remark = $this->input->post('edit_par_critical_high_remark_' . $k);
                    $edit_par_critical_low_sms = $this->input->post('edit_par_critical_low_sms_' . $k);
                    $edit_par_critical_high_sms = $this->input->post('edit_par_critical_high_sms_' . $k);
                    $edit_par_repeat_low = $this->input->post('edit_par_repeat_low_' . $k);
                    $edit_par_repeat_low_remark = $this->input->post('edit_par_repeat_low_remark_' . $k);
                    $edit_par_repeat_high = $this->input->post('edit_par_repeat_high_' . $k);
                    $edit_par_repeat_high_remark = $this->input->post('edit_par_repeat_high_remark_' . $k);
                    $edit_par_absurd_low = $this->input->post('edit_par_absurd_low_' . $k);
                    $edit_par_absurd_high = $this->input->post('edit_par_absurd_high_' . $k);
                    $edit_par_ref_range = $this->input->post('edit_par_ref_range_' . $k);
                    $edit_par_ref_id = $this->input->post('edit_par_ref_id_' . $k);
                    if ($edit_par_gender != "") {
                        $data4 = array(
                            "center" => $branch,
                            "gender" => $edit_par_gender,
                            "no_period" => $edit_par_no_period,
                            "type_period" => $edit_par_type_period,
                            "normal_remarks" => $edit_par_normal_remark,
                            "ref_range_low" => $edit_par_ref_range_low,
                            "low_remarks" => $edit_par_low_remark,
                            "ref_range_high" => $edit_par_ref_range_high,
                            "high_remarks" => $edit_par_high_remark,
                            "critical_low" => $edit_par_critical_low,
                            "critical_low_remarks" => $edit_par_critical_low_remark,
                            "critical_high" => $edit_par_critical_high,
                            "critical_high_remarks" => $edit_par_critical_high_remark,
                            "critical_low_sms" => $edit_par_critical_low_sms,
                            "critical_high_sms" => $edit_par_critical_high_sms,
                            "repeat_low" => $edit_par_repeat_low,
                            "repeat_low_remarks" => $edit_par_repeat_low_remark,
                            "repeat_high" => $edit_par_repeat_high,
                            "repeat_high_remarks" => $edit_par_repeat_high_remark,
                            "absurd_low" => $edit_par_absurd_low,
                            "absurd_high" => $edit_par_absurd_high,
                            "ref_range" => $edit_par_ref_range,
                            "parameter_fk" => $par_id,
                            "modified_by" => $data["login_data"]["id"],
                            "modified_date" => date("Y-m-d H:i:s")
                        );

                        $val_add4 = $this->add_result_model->master_fun_update('parameter_referance_range', array('id', $edit_par_ref_id), $data4);
                    }
                }
                for ($l = 1; $l < $edit_count_ref; $l++) {
                    $data["login_data"] = logindata();
                    $edit_pari_code = $this->input->post('edit_par_code_' . $l);
                    $edit_pari_name = $this->input->post('edit_par_name_' . $l);
                    $edit_pari_result = $this->input->post('edit_par_result_' . $l);
                    $edit_pari_critical = $this->input->post('edit_par_critical_' . $l);
                    $edit_pari_remarks = $this->input->post('edit_par_remarks_' . $l);
                    $edit_pari_status_id = $this->input->post('edit_par_status_id_' . $l);
                    if ($edit_pari_name != "") {
                        $data5 = array(
                            "parameter_code" => $edit_pari_code,
                            "parameter_name" => $edit_pari_name,
                            "result_status" => $edit_pari_result,
                            "critical_status" => $edit_pari_critical,
                            "remarks" => $edit_pari_remarks,
                            "parameter_fk" => $par_id,
                            "modified_by" => $data["login_data"]["id"],
                            "modified_date" => date("Y-m-d H:i:s")
                        );
                        $val_add5 = $this->add_result_model->master_fun_update('test_result_status', array('id', $edit_pari_status_id), $data5);
                    }
                }
            }
            for ($i = 1; $i <= $count_par; $i++) {
                $data["login_data"] = logindata();
                $par_gender = $this->input->post('par_gender_' . $i);
                $par_no_period = $this->input->post('par_no_period_' . $i);
                $par_type_period = $this->input->post('par_type_period_' . $i);
                $par_normal_remark = $this->input->post('par_normal_remark_' . $i);
                $par_ref_range_low = $this->input->post('par_ref_range_low_' . $i);
                $par_low_remark = $this->input->post('par_low_remark_' . $i);
                $par_ref_range_high = $this->input->post('par_ref_range_high_' . $i);
                $par_high_remark = $this->input->post('par_high_remark_' . $i);
                $par_critical_low = $this->input->post('par_critical_low_' . $i);
                $par_critical_low_remark = $this->input->post('par_critical_low_remark_' . $i);
                $par_critical_high = $this->input->post('par_critical_high_' . $i);
                $par_critical_high_remark = $this->input->post('par_critical_high_remark_' . $i);
                $par_critical_low_sms = $this->input->post('par_critical_low_sms_' . $i);
                $par_critical_high_sms = $this->input->post('par_critical_high_sms_' . $i);
                $par_repeat_low = $this->input->post('par_repeat_low_' . $i);
                $par_repeat_low_remark = $this->input->post('par_repeat_low_remark_' . $i);
                $par_repeat_high = $this->input->post('par_repeat_high_' . $i);
                $par_repeat_high_remark = $this->input->post('par_repeat_high_remark_' . $i);
                $par_absurd_low = $this->input->post('par_absurd_low_' . $i);
                $par_absurd_high = $this->input->post('par_absurd_high_' . $i);
                $par_ref_range = $this->input->post('par_ref_range_' . $i);
                if ($par_gender != "") {
                    $data = array(
                        "center" => $branch,
                        "gender" => $par_gender,
                        "no_period" => $par_no_period,
                        "type_period" => $par_type_period,
                        "normal_remarks" => $par_normal_remark,
                        "ref_range_low" => $par_ref_range_low,
                        "low_remarks" => $par_low_remark,
                        "ref_range_high" => $par_ref_range_high,
                        "high_remarks" => $par_high_remark,
                        "critical_low" => $par_critical_low,
                        "critical_low_remarks" => $par_critical_low_remark,
                        "critical_high" => $par_critical_high,
                        "critical_high_remarks" => $par_critical_high_remark,
                        "critical_low_sms" => $par_critical_low_sms,
                        "critical_high_sms" => $par_critical_high_sms,
                        "repeat_low" => $par_repeat_low,
                        "repeat_low_remarks" => $par_repeat_low_remark,
                        "repeat_high" => $par_repeat_high,
                        "repeat_high_remarks" => $par_repeat_high_remark,
                        "absurd_low" => $par_absurd_low,
                        "absurd_high" => $par_absurd_high,
                        "ref_range" => $par_ref_range,
                        "created_by" => $data["login_data"]["id"],
                        "created_date" => date("Y-m-d H:i:s")
                    );
                    if ($par_id != "") {
                        $data["parameter_fk"] = $par_id;
                    } else {
                        $data["parameter_fk"] = $insert;
                    }
                    $val_add = $this->add_result_model->master_fun_insert("parameter_referance_range", $data);
                }
            }
            for ($j = 1; $j <= $count_ref; $j++) {
                $data["login_data"] = logindata();
                $pari_code = $this->input->post('par_code_' . $j);
                $pari_name = $this->input->post('par_name_' . $j);
                $pari_result = $this->input->post('par_result_' . $j);
                $pari_critical = $this->input->post('par_critical_' . $j);
                $pari_remarks = $this->input->post('par_remarks_' . $j);
                if ($pari_name != "") {
                    $data1 = array(
                        "parameter_code" => $pari_code,
                        "parameter_name" => $pari_name,
                        "result_status" => $pari_result,
                        "critical_status" => $pari_critical,
                        "remarks" => $pari_remarks,
                        "created_by" => $data["login_data"]["id"],
                        "created_date" => date("Y-m-d H:i:s")
                    );
                    if ($par_id != "") {
                        $data1["parameter_fk"] = $par_id;
                    } else {
                        $data1["parameter_fk"] = $insert;
                    }
                    $val_add1 = $this->add_result_model->master_fun_insert("test_result_status", $data1);
                }
            }
        }
        if ($val_add1 || $val_add5) {
            echo 1;
        } else {
            echo 1;
        }
    }

    function get_value() {
        //print_r();
        $data['pid'] = $pid = $this->input->post('pid');
        $data['tid'] = $tid = $this->input->post('tid');
        $data['brnch_fk'] = $bid = $this->input->post('brnch_fk');

        $data['processing_center'] = $this->add_result_model->master_fun_get_tbl_val("processing_center", array('status' => 1, 'lab_fk' => $data['brnch_fk']), array("id", "asc"));
        if (empty($data['processing_center'])) {
            $processing_center = '1';
        } else {
            $processing_center = $data['processing_center'][0]["branch_fk"];
        }
        $data['parameter_list'] = $this->add_result_model->master_fun_get_tbl_val("test_parameter_master", array('status' => 1, "center" => $processing_center, "parameter_name !=" => ''), array("parameter_name", "asc"));
        $data['unit_list'] = $this->add_result_model->unit_list();
        $data['parameter_details'] = $this->add_result_model->master_fun_get_tbl_val("test_parameter_master", array('id' => $pid, 'status' => 1), array("parameter_name", "asc"));
        $data['reference_details'] = $this->add_result_model->master_fun_get_tbl_val("parameter_referance_range", array('parameter_fk' => $pid, 'status' => 1), array("id", "asc"));
        $data['status_details'] = $this->add_result_model->master_fun_get_tbl_val("test_result_status", array('parameter_fk' => $pid, 'status' => 1), array("id", "asc"));
        $html = $this->load->view('edit_test_parameter', $data);
        echo $html;
    }

    function remove_reference() {
        $rid = $this->input->post('reference_id');
        $update = $this->add_result_model->master_fun_update('parameter_referance_range', array('id', $rid), array("status" => 0));
        if ($update) {
            echo 1;
        }
    }

    function remove_status() {
        $sid = $this->input->post('status_id');
        $update = $this->add_result_model->master_fun_update('test_result_status', array('id', $sid), array("status" => 0));
        if ($update) {
            echo 1;
        }
    }

    function test() {
        $rand = rand(111, 99999);
        echo '<a href="' . base_url() . '/upload/report/' . $rand . '_result.pdf" target="_blank">Show Pdf</a>';
        $pdfFilePath = FCPATH . "/upload/report/" . $rand . "_result.pdf";
        $data['page_title'] = 'AirmedLabs'; // pass data to the view
        ini_set('memory_limit', '32M'); // boost the memory limit if it's low <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="?" draggable="false" class="emoji">
        $html = $this->load->view('test_result_pdf', $data, true); // render the view into HTML 
//echo $html=$this->html12($html); 
//$html = str_split($html, 3);
//print_r($html);
//die();
//$param = '"en-GB-x","A4","","",10,10,0,10,6,3,"P"'; // Landscape
//$lorem = utf8_encode($html); // render the view into HTML
//$html = "<!DOCTYPE html>                         <html><body>\u0627\u0644\u0643\u0647\u0631\u0628\u0627\u0621 \u0648 \u0627\u0644\u0633\u0628\u0627\u0643\u0629</body></html>      ";
        if (file_exists($pdfFilePath)) {
            $this->delete_downloadfile($pdfFilePath);
        }
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->autoScriptToLang = true;
        $pdf->baseScript = 1; // Use values in classes/ucdn.php  1 = LATIN
        $pdf->autoVietnamese = true;
        $pdf->autoArabic = true;

        $pdf->autoLangToFont = true;

        //$pdf->SetHTMLHeader('<div style="text-align: right; font-weight: bold;">My document</div>');
        $pdf->AddPage('p', // L - landscape, P - portrait
                100, '', '', '', 5, // margin_left
                5, // margin right
                80, // margin top
                80, // margin bottom
                2, // margin header
                2); // margin footer
        /*    $pdf->SetHTMLFooter('

          <table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;"><tr>

          <td width="33%"><span style="font-weight: bold; font-style: italic;">{DATE j-m-Y}</span></td>

          <td width="33%" align="center" style="font-weight: bold; font-style: italic;">{PAGENO}/{nbpg}</td>

          <td width="33%" style="text-align: right; ">My document</td>

          </tr></table>

          '); */
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
        //$html="<div style='background:red;height:2100px'>htientest</div>";
        $pdf->WriteHTML($html);
//nishit index end
//   $pdf->debug = true;
//$pdf->SetFooter('www.' . $_SERVER['HTTP_HOST'] . '||' . $new_time); // Add a footer for good measure <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="?" draggable="false" class="emoji">
// $pdf->WriteHTML($html); // write the HTML into the PDF
        $pdf->Output($pdfFilePath, 'F'); // save to file because we can
//redirect("/upload/report/" . $rand . "_result.pdf");
    }

    function html12($name) {
        $html = strip_tags(preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', "", preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $name)));
        return $html;
        $contant_ary = str_word_count($content);
    }

    function formula_calculation() {
        $para_id = $this->input->post("para_id");
        $result_num = $this->input->post("result_num");
        $new_array = array();
        foreach ($para_id as $key) {
            $val1 = explode("%&%", $key);
            $new_array[$val1[0]] = $val1[1];
        }



        $result_array = array();
        $array_with_val = array();
        $formulaarr = array();

        $para = array();
        $needpara = array();
        $resultarray = array();
        foreach ($new_array as $key => $ar) {
            if ($ar != "") {
                $para[] = $key;
                $resultarray[$key] = $ar;
            } else {
                $needpara[] = $key;
            }
        }



        foreach ($para_id as $key) {
            $val1 = explode("%&%", $key);
            $formula = $this->add_result_model->master_fun_get_tbl_val("test_parameter_master", array('id' => $val1[0], 'status' => 1), array("id", "desc"));

            if (!empty($formula[0]["formula"])) {
                $p_formula = $formula[0]["formula"];

                preg_match_all("/'([^']+)'/", $p_formula, $matches);
                $value_array = array();
                $is_valid = 0;
                foreach ($matches[1] as $value_key) {
                    if ($new_array[$value_key] == '') {
                        $is_valid = 1;
                    }
                    $value_array[] = $new_array[$value_key];
                }
                $formulaarr[] = array("pid" => $formula[0]['id'], "formula" => $p_formula, "associate" => $matches, "val" => $value_array, "is_calculate" => 0);
            } else {
                $formulaarr[] = array("pid" => $val1[0], "formula" => " ", "associate" => array(array('23123'), array(23123)), "val" => array(3213213), "is_calculate" => 0);
            }
        }


        $nw_ary = array();

        $temp = 100;
        $runnagain = true;
        while (count($needpara) > 0 && $runnagain) {
            $runnagain = 0;
            $tempformulaarr = $formulaarr;

            $formulaarr = array();

            foreach ($tempformulaarr as $r_key) {

                $temp--;
                $target = $r_key["associate"][1];
                if (count(array_intersect((array) $para, $target)) == count($target) && $r_key['is_calculate'] == 0) {

                    $runnagain = 1;

                    $find = $r_key["associate"][0];

                    $ansarray = array();
                    foreach ($r_key["associate"][1] as $as) {
                        foreach ($resultarray as $key => $value) {
                            if ($key == $as) {
                                $ansarray[] = $value;
                            }
                        }
                    }

                    $replace = $r_key['val'];
                    $f = $r_key["formula"];
                    //     $f= str_replace("/","//",$r_key["formula"]);
                    $result = preg_replace($find, $ansarray, $f);
                    $final_calculation = str_replace("'", "", $result);

                    $p = eval("return " . $final_calculation . ";");


                    $p = round($p, 2);
                    $r_key["ans"] = $p;
                    $nw_ary[] = $r_key;
                    $para[] = $r_key["pid"];
                    $resultarray[$r_key["pid"]] = $p;


                    $needpara = array_diff($needpara, array($r_key["pid"]));

                    $r_key['is_calculate'] = 1;
                } else {
                    $formulaarr[] = $r_key;
                }
            }
        }
        foreach ($resultarray as $key => $value) {
            $result_array[] = array("pid" => $key, "val" => $result_num, "res" => $value);
        }

        if (!empty($result_array)) {
            echo json_encode(array("status" => "1", "data" => $result_array));
        } else {
            echo json_encode(array("status" => "0", "data" => $array_with_val, "num" => $result_num));
        }
    }

    function send_result() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data['cid'] = $this->uri->segment(3);
        $data['query'] = $this->add_result_model->job_details($data['cid']);

        $data['user_booking_info'] = $this->add_result_model->master_fun_get_tbl_val("booking_info", array('status' => 1, 'id' => $data['query'][0]["booking_info"]), array("id", "asc"));
        $data['user_data'] = $this->add_result_model->master_fun_get_tbl_val("customer_master", array('status' => 1, 'id' => $data['query'][0]["custid"]), array("id", "asc"));
        if (empty($data['user_data'][0]["gender"]) && empty($data['user_data'][0]["age"])) {
            $data['user_data'][0]["gender"] = 'male';
            $data['user_data'][0]["age"] = 24;
            $data['user_data'][0]["age_type"] = 'Y';
        }

        if ($data['user_booking_info'][0]["family_member_fk"] != 0) {
            $data['user_family_info'] = $this->add_result_model->master_fun_get_tbl_val("customer_family_master", array('status' => 1, 'id' => $data['user_booking_info'][0]["family_member_fk"]), array("id", "asc"));
            $data['user_data'][0]["gender"] = $data['user_family_info'][0]["gender"];
            $data['user_data'][0]["age"] = $data['user_family_info'][0]["age"];
            $data['user_data'][0]["age_type"] = $data['user_family_info'][0]["age_type"];
            $data['user_data'][0]["full_name"] = $data['user_family_info'][0]["name"];
            $data['user_data'][0]["email"] = $data['user_family_info'][0]["email"];
            $data['user_data'][0]["phone"] = $data['user_family_info'][0]["phone"];
            $data['user_data'][0]["dob"] = $data['user_family_info'][0]["dob"];
        }
        if (empty($data['user_data'][0]["dob"])) {
            $data['user_data'][0]["dob"] = '1992-09-30';
        }
        /* Check bitrth date start */
        $this->load->library("util");
        $util = new Util;
        $age = $util->get_age($data['user_data'][0]["dob"]);
        $ageinDays = 0;
        if ($age[0] != 0) {
            $ageinDays += ($age[0] * 365);
            $data['user_data'][0]["age"] = $age[0];
            $data['user_data'][0]["age_type"] = 'Y';
        }
        if ($age[0] == 0 && $age[1] != 0) {
            $ageinDays += ($age[1] * 30);
            $data['user_data'][0]["age"] = $age[1];
            $data['user_data'][0]["age_type"] = 'M';
        }
        if ($age[0] == 0 && $age[1] == 0 && $age[2] != 0) {
            $ageinDays += ($age[2]);
            $data['user_data'][0]["age"] = $age[2];
            $data['user_data'][0]["age_type"] = 'D';
        }
        $tid = array();
        $data['parameter_list'] = array();
        if (trim($data['query'][0]['testid']) == null && $data['query'][0]["packageid"] != null) {
            $package_id = $data['query'][0]["packageid"];
            $pid = explode("%", $data['query'][0]['packageid']);
            foreach ($pid as $pkey) {
                $p_test = $this->add_result_model->get_val("SELECT `package_test`.`test_fk` FROM `package_test` INNER JOIN `test_master` ON `package_test`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `package_test`.`status`='1' AND `package_test`.`package_fk`='" . $pkey . "'");
                foreach ($p_test as $tp_key) {
                    $tid[] = $tp_key["test_fk"];
                }
            }
        } else if (trim($data['query'][0]['testid']) != null && $data['query'][0]["packageid"] != null) {

            $tid = explode(",", $data['query'][0]['testid']);
            $package_id = $data['query'][0]["packageid"];
            $pid = explode("%", $data['query'][0]['packageid']);
            foreach ($pid as $pkey) {
                $p_test = $this->add_result_model->get_val("SELECT `package_test`.`test_fk` FROM `package_test` INNER JOIN `test_master` ON `package_test`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `package_test`.`status`='1' AND `package_test`.`package_fk`='" . $pkey . "'");

                foreach ($p_test as $tp_key) {
                    $tid[] = $tp_key["test_fk"];
                }
            }
        } else {
            $tid = explode(",", $data['query'][0]['testid']);
        }
        //print_R($tid); die();
        foreach ($tid as $t_key) {
            $p_test = $this->add_result_model->get_val("SELECT sub_test_master.* FROM `sub_test_master` INNER JOIN `test_master` ON `sub_test_master`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `sub_test_master`.`status`='1' AND `sub_test_master`.`test_fk`='" . $t_key . "'");
            foreach ($p_test as $tp_key) {
                $tid[] = $tp_key["sub_test"];
            }
        }
        $tid = array_unique($tid);
        $cnt = 0;
        $new_data_array = array();
        foreach ($tid as $tst_id) {
            $get_test_parameter = $this->add_result_model->get_val("SELECT `test_parameter`.*,`test_master`.`test_name` FROM `test_parameter` INNER JOIN `test_master` ON `test_parameter`.`test_fk`=`test_master`.`id` WHERE `test_parameter`.`status`='1' AND `test_master`.`status`='1' AND `test_parameter`.`test_fk`='" . $tst_id . "' order by `test_parameter`.order asc");
//echo "SELECT `test_parameter`.*,`test_master`.`test_name` FROM `test_parameter` INNER JOIN `test_master` ON `test_parameter`.`test_fk`=`test_master`.`id` WHERE `test_parameter`.`status`='1' AND `test_master`.`status`='1' AND `test_parameter`.`test_fk`='" . $tst_id . "' order by `test_parameter`.order asc"; die();
            $pid = array();
            foreach ($get_test_parameter as $tp_key) {
                $pid[] = $tp_key["parameter_fk"];
            }
            if (!empty($pid)) {
                $para = $this->add_result_model->get_val("SELECT * FROM `test_parameter_master` WHERE `status`='1' AND id IN (" . implode(",", $pid) . ") ORDER BY FIELD(id," . implode(",", $pid) . ")");
                if (!empty($para)) {
                    $cnt_1 = 0;
                    foreach ($para as $para_key) {
                        $formula = $this->add_result_model->get_val("SELECT * from use_formula where test_fk='" . $tst_id . "' and job_fk='" . $data['cid'] . "' and status='1'");
                        $get_test_parameter[$cnt_1]['use_formula'] = $formula[0]["use_formula"];
                        $get_test_parameter[$cnt_1]['on_new_page'] = $formula[0]["on_new_page"];
                        $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                        $get_test_parameter[$cnt_1]['graph'] = $graph_pic;
                        $get_test_parameter[$cnt_1]['graph_id'] = $formula[0]["id"];
                        $get_test_parameter1 = $get_test_parameter[$cnt_1];
//print_R($get_test_parameter1); die();
//echo "SELECT * from user_test_result where test_id='".$tst_id."' and parameter_id='" . $para_key["id"] . "' and job_id='".$data['cid']."' and  status='1'";
                        $para_user_val = $this->add_result_model->get_val("SELECT * from user_test_result where test_id='" . $tst_id . "' and parameter_id='" . $para_key["id"] . "' and job_id='" . $data['cid'] . "' and  status='1'");
                        $para[$cnt_1]["user_val"] = $para_user_val;
                        $para_ref_rng = $this->add_result_model->get_val("SELECT * FROM `parameter_referance_range` WHERE `status`='1' AND parameter_fk='" . $para_key["id"] . "' order by gender asc");
                        $final_qry = "SELECT * FROM `parameter_referance_range` WHERE STATUS='1' AND `parameter_fk`='" . $para_key["id"] . "'";
                        /* if ($data['user_data'][0]["age"] > 0 && $data['user_data'][0]["age"] < 6 && $data['user_data'][0]["age_type"] == 'D') {
                          $final_qry .= " AND gender='N'  AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='D'";
                          } else if ($data['user_data'][0]["age"] > 5 && $data['user_data'][0]["age_type"] == 'D') {
                          $final_qry .= " AND gender='C' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='D'";
                          $data["common"] = 0;
                          } else if ($data['user_data'][0]["age"] > 0 && $data['user_data'][0]["age"] < 8 && $data['user_data'][0]["age_type"] == 'M') {
                          $final_qry .= " AND gender='C' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='M'";
                          $data["common"] = 0;
                          } else if ($para_ref_rng[0]["gender"] == 'B' && $data['user_data'][0]["age_type"] == 'Y') {
                          //$get_both_age = $this->add_result_model->get_val("SELECT * FROM `parameter_referance_range` WHERE STATUS='1' AND `parameter_fk`='117' AND gender='B' AND `no_period` > ".$data['user_data'][0]["age"]." AND `type_period`='Y' ORDER BY id ASC");
                          $final_qry .= " AND gender='B' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } else if (strtoupper($data['user_data'][0]["gender"]) == 'MALE' && $data['user_data'][0]["age_type"] == 'Y') {
                          //$get_male_age = $this->add_result_model->get_val("SELECT * FROM `parameter_referance_range` WHERE STATUS='1' AND `parameter_fk`='117' AND gender='M' AND `no_period` > ".$data['user_data'][0]["age"]." AND `type_period`='Y' ORDER BY id ASC");
                          //print_r($get_male_age);
                          $final_qry .= " AND gender='M' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } else if (strtoupper($data['user_data'][0]["gender"]) == 'FEMALE' && $data['user_data'][0]["age_type"] == 'Y') {
                          $final_qry .= " AND gender='F' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          }
                          if ($data['user_data'][0]["age"] == '') {
                          $data['user_data'][0]["age"] = 0;
                          }
                          if ($data['user_data'][0]["age_type"] == 'D') {
                          $final_qry .= " AND gender='N'  AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='D'";
                          } else if ($data['user_data'][0]["age_type"] == 'M') {
                          $final_qry .= " AND gender='C' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='M'";
                          $data["common"] = 0;
                          } else if ($para_ref_rng[0]["gender"] == 'B' && $data['user_data'][0]["age_type"] == 'Y') {
                          $final_qry .= " AND gender='B' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } else if (strtoupper($data['user_data'][0]["gender"]) == 'MALE' && $data['user_data'][0]["age_type"] == 'Y') {
                          $final_qry .= " AND gender='M' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } else if (strtoupper($data['user_data'][0]["gender"]) == 'FEMALE' && $data['user_data'][0]["age_type"] == 'Y') {
                          $final_qry .= " AND gender='F' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          }
                          $final_qry = $final_qry . " ORDER BY `type_period` ASC limit 0,1";
                         */
                        if (strtoupper($data['user_data'][0]["gender"]) == 'MALE') {
                            $final_qry .= " AND gender='M' AND (CASE WHEN (type_period= 'Y') THEN (no_period*365) ELSE (CASE WHEN (type_period= 'M') THEN (no_period*30) ELSE no_period END) END )>$ageinDays ";
                            $data["common"] = 0;
                        } else if (strtoupper($data['user_data'][0]["gender"]) == 'FEMALE' && $data['user_data'][0]["age_type"] == 'Y') {
                            $final_qry .= " AND gender='F' AND  (CASE WHEN (type_period= 'Y') THEN (no_period*365) ELSE (CASE WHEN (type_period= 'M') THEN (no_period*30) ELSE no_period END) END )>$ageinDays";
                            $data["common"] = 0;
                        }
                        $final_qry = $final_qry . " ORDER BY (
    CASE
      WHEN (type_period = 'Y') 
      THEN (no_period * 365) 
      ELSE (
        CASE
          WHEN (type_period = 'M') 
          THEN (no_period * 30) 
          ELSE no_period 
        END
      ) 
    END
  ) ASC limit 0,1";
                        $final_qry1 = "SELECT * FROM `test_result_status` WHERE STATUS='1' AND `parameter_fk`='" . $para_key["id"] . "'";
                        $data["common"] = 1;
                        $data["para_ref_rng"] = $this->add_result_model->get_val($final_qry);
                        $data["para_ref_rng"][0]["common"] = "1";
                        $data["para_ref_rng"][0]["tst_id"] = $tst_id;
                        $para[$cnt_1]['para_ref_rng'] = $data["para_ref_rng"];

                        $data["para_ref_status"] = $this->add_result_model->get_val($final_qry1);
                        $para[$cnt_1]['para_ref_status'] = $data["para_ref_status"];
                        $para[$cnt_1]["test_parameter_id"] = $get_test_parameter1["id"];
                        $para[$cnt_1]["new_order"] = $get_test_parameter1["order"];
                        $cnt_1++;
                    }
                    $get_test_parameter1[0]['parameter'] = $para;
                    $new_data_array[] = $get_test_parameter1;
                } else {
                    $get_test_parameter1 = $this->add_result_model->get_val("SELECT id as test_fk,test_name FROM `test_master` WHERE id='" . $tst_id . "'");
                    $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                    $get_test_parameter1[0]['graph'] = $graph_pic;
                    $new_data_array[] = $get_test_parameter1[0];
                }
            } else {
                $get_test_parameter1 = $this->add_result_model->get_val("SELECT id as test_fk,test_name FROM `test_master` WHERE id='" . $tst_id . "'");
                $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                $get_test_parameter1[0]['graph'] = $graph_pic;
                $new_data_array[] = $get_test_parameter1[0];
            }

            $cnt++;
        }
        $data["new_data_array"] = $new_data_array;
        $data['result_list'] = $this->add_result_model->master_fun_get_tbl_val("user_test_result", array('status' => 1, 'job_id' => $data['cid']), array("id", "asc"));
        /* Nishit add result SMS start */
        $txt_cnt = 0;
        $sms_text = "";
        $sms_text .= "Patient Name:  " . strtoupper($data['user_data'][0]["full_name"]) . " (" . $data['query'][0]["order_id"] . ") \n\n";
        foreach ($new_data_array as $testidp) {
            $parameter_cnt = 0;
            if (!empty($testidp[0]["parameter"])) {
                $parameter_val_cnt = 0;
                foreach ($testidp[0]["parameter"] as $parameter) {
                    if (!empty($parameter['user_val'])) {
                        $parameter_val_cnt++;
                    }
                }
                if ($parameter_val_cnt != 0) {
                    //if($txt_cnt>0){ $sms_text .='\n'; }
                    if ($txt_cnt > 0) {
                        $sms_text .= "\n " . $testidp["test_name"] . " \n";
                    } else {
                        $sms_text .= $testidp["test_name"] . " \n";
                    }

                    $txt_cnt++;
                    $temp = '1';
                    $cn = 0;
                    foreach ($testidp[0]["parameter"] as $parameter) {
                        if ($parameter["is_group"] != '1') {
                            if (!empty($parameter['parameter_name']) && !empty($parameter['user_val'])) {
                                if (count($parameter['user_val']) > 0) {
                                    $status = "Normal";
                                    if ($parameter["para_ref_rng"][0]['absurd_low'] > $parameter['user_val'][0]["value"]) {
                                        $status = "Emergency";
                                    }
                                    if ($parameter["para_ref_rng"][0]['ref_range_low'] > $parameter['user_val'][0]["value"]) {
                                        $status = $parameter["para_ref_rng"][0]['low_remarks'];
                                    }
                                    if ($parameter["para_ref_rng"][0]['critical_low'] > $parameter['user_val'][0]["value"]) {
                                        $status = $parameter["para_ref_rng"][0]['critical_low_remarks'];
                                    }
                                    if ($parameter["para_ref_rng"][0]['ref_range_high'] < $parameter['user_val'][0]["value"]) {
                                        $status = $parameter["para_ref_rng"][0]['high_remarks'];
                                    }
                                    if ($parameter["para_ref_rng"][0]['critical_high'] < $parameter['user_val'][0]["value"]) {
                                        $status = $parameter["para_ref_rng"][0]['critical_high_remarks'];
                                    }
                                } else {
                                    $status = "";
                                }

                                $sms_text .= $parameter['parameter_name'] . " :- ";

                                $res = '';
                                $is_text = 0;
                                if (isset($parameter["para_ref_rng"][0]['id'])) {

                                    $sms_text .= " " . $parameter['user_val'][0]["value"];
                                    $status;
                                } else {

                                    if (!empty($parameter["para_ref_status"])) {
                                        foreach ($parameter["para_ref_status"] as $kky) {
                                            if ($parameter['user_val'][0]["value"] == $kky["id"]) {
                                                $is_text = 1;
                                                $sms_text .= " " . $kky["parameter_name"] . " \n ";
                                            }
                                        }
                                    } else {
                                        $sms_text .= $parameter['user_val'][0]["value"];
                                    }
                                }
                                //$sms_text .= "   ".$res;
                                if ($is_text == 0) {
                                    //$sms_text .= $parameter['parameter_unit'];
                                    if (!empty(trim($parameter["para_ref_rng"][0]["ref_range"]))) {
                                        $sms_text .= " [" . $parameter["para_ref_rng"][0]["ref_range"] . "] \n ";
                                    } else {
                                        if ($parameter["para_ref_rng"][0]['ref_range_low'] != '' || $parameter["para_ref_rng"][0]['ref_range_high'] != '') {
                                            $sms_text .= " [" . $parameter["para_ref_rng"][0]['ref_range_low'];
                                            $sms_text .= " - " . $parameter["para_ref_rng"][0]['ref_range_high'] . "] \n ";
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        /* Nishit add result SMS end */
        if (trim($sms_text) != '') {
            $mobile = array();
            $mobile[] = $data['query'][0]["mobile"];

            $data['send_sms_no'] = $this->add_result_model->get_val("SELECT `send_report_sms`.*,`admin_master`.`name` FROM `send_report_sms` INNER JOIN `admin_master` ON `send_report_sms`.`send_by`=`admin_master`.id WHERE `send_report_sms`.`status`='1' AND send_report_sms.`job_fk`='" . $data['cid'] . "'");
            echo json_encode(array("status" => "1", "sms" => $sms_text, "mobile" => $mobile, "history" => $data['send_sms_no']));
        } else {
            echo json_encode(array("status" => "0"));
        }
        //$this->load->helper("sms");
        //$notification = new Sms();
        //$notification::send("8980119072", $sms_text);
        //$notification::send("9879572294", $sms_text);
    }

    function approve_test() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $jid = $this->uri->segment(3);
        $tid = $this->uri->segment(4);
        $check_is_approved = $this->add_result_model->master_fun_get_tbl_val("approve_job_test", array('job_fk' => $jid, "test_fk" => $tid, "status" => "1"), array("id", "asc"));
        //print_r($check_is_approved); die();
        if (empty($check_is_approved)) {
            $insert = $this->add_result_model->master_fun_insert("approve_job_test", array('job_fk' => $jid, "test_fk" => $tid, "approve_by" => $data["login_data"]["id"], "created_date" => date("Y-m-d H:i:s")));
        } else {
            
        }
        redirect("add_result/test_approve_details/" . $jid . "/" . $tid);
    }

}

?>