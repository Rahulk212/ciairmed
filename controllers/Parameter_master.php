<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Parameter_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('user_model');
        $this->load->model('add_result_model');
        $this->load->model('parameter_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $data["login_data"] = logindata();
        $this->app_track();
    }

    function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }

    function parameter_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $search = $this->input->get('search');
        $data['search'] = $search;
        if ($search != "") {
            $total_row = $this->parameter_model->parameter_list_search_num($search);
            $config = array();
            $config["base_url"] = base_url() . "parameter_master/parameter_list/?search=$search";
            $config["total_rows"] = $total_row;
            $config["per_page"] = 50;
            $config["uri_segment"] = 3;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $config['page_query_string'] = TRUE;
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->parameter_model->parameter_list_search($search, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        } else {
            $totalRows = $this->parameter_model->count_list();
            $config = array();
            $config["base_url"] = base_url() . "parameter_master/parameter_list/";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 50;
            $config["uri_segment"] = 3;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $data["query"] = $this->parameter_model->parameter_list($config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        }
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('parameter_list', $data);
        $this->load->view('footer');
    }

    function parameter_add() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $this->load->library('form_validation');
        $this->form_validation->set_rules('test', 'Select Test', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $par_name = $this->input->post('par_name');
            $test = $this->input->post('test');
            $par_range_minimum = $this->input->post('par_range_minimum');
            $par_range_maximum = $this->input->post('par_range_maximum');
            $par_unit = $this->input->post('par_unit');
            $group_per = $this->input->post('group_per');
            $group_name = $this->input->post('group_name');
            $new_group_name = $this->input->post('new_group_name');
            if ($par_range_minimum != '') {
                $range = $par_range_minimum . "-" . $par_range_maximum;
            }
            if ($group_per == 0) {
                $data1 = array(
                    "test_fk" => $test,
                    "parameter_name" => $par_name,
                    "parameter_range" => $range,
                    "parameter_unit" => $par_unit,
                    "created_by" => $data["login_data"]["id"],
                    "created_date" => date("Y-m-d H:i:s")
                );
                $insert = $this->parameter_model->master_fun_insert("test_parameter_master", $data1);
            } else if ($group_per == 1) {
                if ($group_name != '' && $new_group_name == '') {
                    $data4 = array(
                        "parameter_fk" => $group_name,
                        "subparameter_name" => $par_name,
                        "subparameter_range" => $range,
                        "subparameter_unit" => $par_unit,
                        "created_by" => $data["login_data"]["id"],
                        "created_date" => date("Y-m-d H:i:s")
                    );
                    $insert = $this->parameter_model->master_fun_insert("parameter_group_master", $data4);
                } else if ($group_name == '' && $new_group_name != '') {
                    $data2 = array(
                        "test_fk" => $test,
                        "parameter_name" => $new_group_name,
                        "created_by" => $data["login_data"]["id"],
                        "created_date" => date("Y-m-d H:i:s")
                    );
                    $insert1 = $this->parameter_model->master_fun_insert("test_parameter_master", $data2);
                    $data3 = array(
                        "parameter_fk" => $insert1,
                        "subparameter_name" => $par_name,
                        "subparameter_range" => $range,
                        "subparameter_unit" => $par_unit,
                        "created_by" => $data["login_data"]["id"],
                        "created_date" => date("Y-m-d H:i:s")
                    );
                    $insert = $this->parameter_model->master_fun_insert("parameter_group_master", $data3);
                }
            }

            if ($insert) {
                $this->session->set_flashdata("success", array("Parameter successfully added."));
                redirect("parameter_master/parameter_list", "refresh");
            }
        } else {
            $data['test_list'] = $this->parameter_model->master_fun_get_tbl_val("test_master", array("status" => '1'), array("test_name", "asc"));
            $data['parameter_list'] = $this->parameter_model->master_fun_get_tbl_val("test_parameter_master", array("status" => '1', "parameter_range" => ''), array("parameter_name", "asc"));
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('parameter_add_new', $data);
            $this->load->view('footer');
        }
    }

    function parameter_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $pid = $this->uri->segment('3');
        $gid = $this->uri->segment('4');
        if ($gid != '') {
            $delete = $this->parameter_model->delete_subparameter('parameter_group_master', $gid);
        } else {
            $delete = $this->user_model->master_fun_update("test_parameter_master", array("id", $pid), array("status" => "0"));
        }
        $this->session->set_flashdata("success", array("Parameter successfully deleted."));
        redirect("parameter_master/parameter_list", "refresh");
    }

    function parameter_edit() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["pid"] = $this->uri->segment('3');
        $data["gid"] = $this->uri->segment('4');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('test', 'Select Test', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $par_name = $this->input->post('par_name');
            $test = $this->input->post('test');
            $par_range_minimum = $this->input->post('par_range_minimum');
            $par_range_maximum = $this->input->post('par_range_maximum');
            $par_unit = $this->input->post('par_unit');
            $group_per = $this->input->post('group_per');
            $group_name = $this->input->post('group_name');
            $new_group_name = $this->input->post('new_group_name');
            if ($par_range_minimum != '') {
                $range = $par_range_minimum . "-" . $par_range_maximum;
            }
            if ($group_per == 0) {
                $data1 = array(
                    "test_fk" => $test,
                    "parameter_name" => $par_name,
                    "parameter_range" => $range,
                    "parameter_unit" => $par_unit,
                    "modified_by" => $data["login_data"]["id"],
                    "modified_date" => date("Y-m-d H:i:s")
                );
                $update = $this->parameter_model->master_fun_update("test_parameter_master", array("id", $data["pid"]), $data1);
                ;
            } else if ($group_per == 1) {
                if ($group_name != '' && $new_group_name == '') {
                    $data4 = array(
                        "parameter_fk" => $group_name,
                        "subparameter_name" => $par_name,
                        "subparameter_range" => $range,
                        "subparameter_unit" => $par_unit,
                        "modified_by" => $data["login_data"]["id"],
                        "modified_date" => date("Y-m-d H:i:s")
                    );
                    $update = $this->parameter_model->master_fun_update("parameter_group_master", array("id", $data["gid"]), $data4);
                } else if ($group_name == '' && $new_group_name != '') {
                    $data2 = array(
                        "test_fk" => $test,
                        "parameter_name" => $new_group_name,
                        "created_by" => $data["login_data"]["id"],
                        "created_date" => date("Y-m-d H:i:s")
                    );
                    $insert1 = $this->parameter_model->master_fun_insert("test_parameter_master", $data2);
                    $data3 = array(
                        "parameter_fk" => $insert1,
                        "subparameter_name" => $par_name,
                        "subparameter_range" => $range,
                        "subparameter_unit" => $par_unit,
                        "modified_by" => $data["login_data"]["id"],
                        "modified_date" => date("Y-m-d H:i:s")
                    );
                    $update = $this->parameter_model->master_fun_update("parameter_group_master", array("id", $data["gid"]), $data3);
                }
            }

            if ($update) {
                $this->session->set_flashdata("success", array("Parameter successfully updated."));
                redirect("parameter_master/parameter_list", "refresh");
            }
        } else {
            $data['query'] = $this->parameter_model->get_edit_val($data['pid'], $data['gid']);
            $data['test_list'] = $this->parameter_model->master_fun_get_tbl_val("test_master", array("status" => '1'), array("test_name", "asc"));
            $data['parameter_list'] = $this->parameter_model->master_fun_get_tbl_val("test_parameter_master", array("status" => '1', "parameter_range" => NULL), array("parameter_name", "asc"));
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('parameter_edit_new', $data);
            $this->load->view('footer');
        }
    }

    function parameter_csv() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $delimiter = ",";
        $newline = "\r\n";
        $filename = "parameter.csv";
        $query = "SELECT  t.`test_name` as testname ,p.`parameter_name` as parametername,p.`parameter_range` as parameterrange,p.`parameter_unit` as parameterunit FROM  `test_parameter_master` p  LEFT JOIN test_master t ON p.test_fk=t.id WHERE p.status = '1' OR (t.`status`='1') ORDER BY p.id DESC";
        $result = $this->db->query($query);
        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
        force_download($filename, $data);
    }

    function importparameter_csv() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->library('csvimport');
        $this->load->library('csvimport');
        if (empty($_FILES['pareximport']['name'])) {
            $this->form_validation->set_rules('pareximport', 'Upload', 'required');
        }
        if ($this->form_validation->run() == FALSE) {
            $config['upload_path'] = './upload/csv/';
            $config['allowed_types'] = 'csv';
            $config['file_name'] = time() . $_FILES['pareximport']['name'];
            $config['file_name'] = str_replace(' ', '_', $config['file_name']);
            $_FILES['pareximport']['name'];
            $file1 = $config['file_name'];
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('pareximport')) {
                $error = $this->upload->display_errors();
                $ses = array($error);
                $this->session->set_flashdata("success", $ses);
                redirect("parameter_master/parameter_list", "refresh");
            } else {
                $file_data = $this->upload->data();
                $file_path = './upload/csv/' . $file_data['file_name'];
                $cnt = 0;
                if ($this->csvimport->get_array($file_path)) {
                    $csv_array = $this->csvimport->get_array($file_path);
                    $countall = count($csv_array);
                    foreach ($csv_array as $row) {
                        $test_fk = $row['testname'];
                        $cityname = $row['cityname'];
                        $price = $row['price'];
                        if ($test_name != '') {
                            $testrow = $this->parameter_model->num_row('test_master', array("test_name" => $test_name, "status" => '1'));
                            if ($testrow == '0') {
                                $testinid = $this->parameter_model->master_fun_insert('test_master', array("test_name" => $test_name, "status" => '1'));
                                $testrow = $this->parameter_model->num_row('test_cities', array("name" => $cityname, "status" => '1'));
                                if ($testrow != '0') {
                                    $citydeatils = $this->parameter_model->fetchdatarow('id', 'test_cities', array("name" => $cityname));
                                    $cityid = $citydeatils->id;
                                    $this->parameter_model->master_fun_insert('test_master_city_price', array("test_fk" => $testinid, "city_fk" => $cityid));
                                }
                            } else {
                                $testdeatils = $this->parameter_model->fetchdatarow('id,test_name', 'test_master', array("test_name" => $test_name, "status" => '1'));
                                $testid = $testdeatils->id;
                                $testrow = $this->parameter_model->num_row('test_master_city_price', array("test_fk" => $testid, "status" => '1'));
                                if ($testrow == '0') {
                                    $testrow = $this->parameter_model->num_row('test_cities', array("name" => $cityname, "status" => '1'));
                                    if ($testrow != '0') {
                                        $citydeatils = $this->parameter_model->fetchdatarow('id', 'test_cities', array("name" => $cityname, "status" => '1'));
                                        $cityid = $citydeatils->id;
                                        $this->parameter_model->master_fun_insert('test_master_city_price', array("test_fk" => $testid, "city_fk" => $cityid));
                                    }
                                }
                            }
                        }
                    }
                }
                if ($cnt == '0') {
                    $ses = "Error occured";
                    $this->session->set_flashdata('success', array($ses));
                    redirect("parameter_master/parameter_list", "refresh");
                } else {
                    $ses = array($cnt . "Parameter Added Successfully");
                    $this->session->set_flashdata('success', $ses);
                    redirect("parameter_master/parameter_list", "refresh");
                }
            }
        } else {
            show_404();
        }
    }

    function add_parameter() {
        $data["login_data"] = logindata();
        $par_name = $this->input->post('par_name');
        $test = $this->input->post('test');
        $par_range_minimum = $this->input->post('par_range_minimum');
        $par_range_maximum = $this->input->post('par_range_maximum');
        $par_unit = $this->input->post('par_unit');
        $group_per = $this->input->post('group_per');
        $group_name = $this->input->post('par_group_name');
        $new_group_name = $this->input->post('par_new_group_name');
        if ($par_range_minimum != '') {
            $range = $par_range_minimum . "-" . $par_range_maximum;
        }
        if ($group_per == 0) {
            $data1 = array(
                "test_fk" => $test,
                "parameter_name" => $par_name,
                "parameter_range" => $range,
                "parameter_unit" => $par_unit,
                "created_by" => $data["login_data"]["id"],
                "created_date" => date("Y-m-d H:i:s")
            );
            $insert = $this->parameter_model->master_fun_insert("test_parameter_master", $data1);
        } else if ($group_per == 1) {
            if ($group_name != '' && $new_group_name == '') {
                $data4 = array(
                    "parameter_fk" => $group_name,
                    "subparameter_name" => $par_name,
                    "subparameter_range" => $range,
                    "subparameter_unit" => $par_unit,
                    "created_by" => $data["login_data"]["id"],
                    "created_date" => date("Y-m-d H:i:s")
                );
                $insert = $this->parameter_model->master_fun_insert("parameter_group_master", $data4);
            } else if ($group_name == '' && $new_group_name != '') {
                $data2 = array(
                    "test_fk" => $test,
                    "parameter_name" => $new_group_name,
                    "created_by" => $data["login_data"]["id"],
                    "created_date" => date("Y-m-d H:i:s")
                );
                $insert1 = $this->parameter_model->master_fun_insert("test_parameter_master", $data2);
                $data3 = array(
                    "parameter_fk" => $insert1,
                    "subparameter_name" => $par_name,
                    "subparameter_range" => $range,
                    "subparameter_unit" => $par_unit,
                    "created_by" => $data["login_data"]["id"],
                    "created_date" => date("Y-m-d H:i:s")
                );
                $insert = $this->parameter_model->master_fun_insert("parameter_group_master", $data3);
            }
        }

        if ($insert) {
            echo $insert;
        } else {
            echo 0;
        }
    }

    function add() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data['parameter_list'] = $this->add_result_model->master_fun_get_tbl_val("test_parameter_master", array('status' => 1), array("parameter_name", "asc"));
        $data['unit_list'] = $this->add_result_model->unit_list();
        $this->load->view('header');
        //$this->load->view('nav', $data);
        $this->load->view('add_parameter', $data);
        //$this->load->view('footer');
    }

    function add_value_all() {
        if (!is_loggedin()) {
            redirect('login');
        }
//        echo "<pre>";
//        print_r($this->input->post());
//        die();
        $data["login_data"] = logindata();
        $par_name = $this->input->post('par_name');
        $par_unit = $this->input->post('par_unit');
        $par_formula = $this->input->post('par_formula');
        $count_par = $this->input->post('count_par');
        $count_ref = $this->input->post('count_ref');
        $par_id = $this->input->post('exist_para');
        if ($par_id == "") {
            $insert = $this->add_result_model->master_fun_insert("test_parameter_master", array("parameter_name" => $par_name, "parameter_unit" => $par_unit, "formula" => $par_formula, "created_by" => $data["login_data"]["id"], "created_date" => date("Y-m-d H:i:s")));
        } else {
            $this->add_result_model->master_fun_update('test_parameter_master', array('id', $par_id), array("parameter_name" => $par_name, "parameter_unit" => $par_unit, "formula" => $par_formula, "modified_by" => $data["login_data"]["id"], "modified_date" => date("Y-m-d H:i:s")));
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
        if ($val_add1 || $val_add5) {
            echo 1;
        } else {
            echo 0;
        }
    }

    function get_value() {
        $data['pid'] = $pid = $this->input->post('pid');
        $data['parameter_list'] = $this->add_result_model->master_fun_get_tbl_val("test_parameter_master", array('status' => 1), array("parameter_name", "asc"));
        $data['unit_list'] = $this->add_result_model->unit_list();
        $data['parameter_details'] = $this->add_result_model->master_fun_get_tbl_val("test_parameter_master", array('id' => $pid), array("parameter_name", "asc"));
        $data['reference_details'] = $this->add_result_model->master_fun_get_tbl_val("parameter_referance_range", array('parameter_fk' => $pid), array("id", "asc"));
        $data['status_details'] = $this->add_result_model->master_fun_get_tbl_val("test_result_status", array('parameter_fk' => $pid), array("id", "asc"));
        $html = $this->load->view('edit_parameter', $data);
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

}

?>
