<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Data_autorisation extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('user_model');
        $this->load->model('data_autorize_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('email');
        $this->load->helper('string');
        $data["login_data"] = logindata();
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
        $cntr_arry = array();
        if (!empty($data["login_data"]['branch_fk'])) {
            foreach ($data["login_data"]['branch_fk'] as $key) {
                $cntr_arry[] = $key["branch_fk"];
            }
        }
        $branch = implode(",",$cntr_arry);
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $totalRows = $this->data_autorize_model->autho_list_count($branch);
        $config = array();
        $config["base_url"] = base_url() . "data_autorisation/index/";
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
        $data["query"] = $this->data_autorize_model->autho_list($config["per_page"], $page,$branch);
        $data["links"] = $this->pagination->create_links();
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('data_authorise', $data);
        $this->load->view('footer');
    }
    function get_data_parameter() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $barc = $this->input->post('barcode');
        $branch = $this->input->post('branch');
        $data = $this->data_autorize_model->get_parameter_withvalue($barc,$branch);
        $cnt=1;
        foreach ($data as $all) {
            echo '<tr>
            <td>'.$all->parameter_name.'</td>
            <td><input type="text" value="'.$all->para_value*$all->multiply_by.'" name="paravalue_'.$cnt.'">
                <input type="hidden" name="add_values_id_'.$cnt.'" value="'.$all->id.'"></td>
                <input type="hidden" name="job_id_'.$cnt.'" value="'.$all->job_id.'"></td>
                <input type="hidden" name="parameter_id_'.$cnt.'" value="'.$all->parameter_id.'"></td>
                <input type="hidden" name="count" value="'.$cnt.'"></td>
            </tr>';
            $cnt++;
        } if(empty($data)) {
            echo '<tr><td colspan="3">data not available.</td></tr>';
        }
    }
    
    function accept_results() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->model("add_result_model");
        $data["login_data"] = logindata();
        $count = $this->input->post('count');
        for ($i = 1; $i <= $count; $i++) {
            $paravalue = $this->input->post('paravalue_' . $i);
            $add_values_id = $this->input->post('add_values_id_' . $i);
            $job_id = $this->input->post('job_id_' . $i);
            $parameter_id = $this->input->post('parameter_id_' . $i);
            $this->data_autorize_model->master_fun_update('instument_data_storage_new', array("id", $add_values_id), array("status" => 2));
            /* Nishit code start */
            //$test_list = $this->data_autorize_model->get_master_get_data('job_test_list_master', array("job_fk" => $job_id, "status" => 1), array("id", "desc"));
            $data['query'] = $this->add_result_model->job_details($job_id);
            $data['processing_center'] = $this->add_result_model->master_fun_get_tbl_val("processing_center", array('status' => 1, 'lab_fk' => $data['query'][0]["branch_fk"]), array("id", "asc"));
            if (empty($data['processing_center'])) {
                $processing_center = '1';
            } else {
                $processing_center = $data['processing_center'][0]["branch_fk"];
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
        foreach ($tid as $t_key) {
            $p_test = $this->add_result_model->get_val("SELECT sub_test_master.* FROM `sub_test_master` INNER JOIN `test_master` ON `sub_test_master`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `sub_test_master`.`status`='1' AND `sub_test_master`.`test_fk`='" . $t_key . "'");
            foreach ($p_test as $tp_key) {
                $tid[] = $tp_key["sub_test"];
            }
        }
        $tid = array_unique($tid);
            /* End */
            foreach ($tid as $test) {
                $get_data = $this->data_autorize_model->fetchdatarow('id', 'user_test_result', array("job_id" => $job_id, "test_id" => $test, "parameter_id" => $parameter_id, "status" => 1));
                if (!empty($get_data)) {
                    $this->data_autorize_model->master_fun_update('user_test_result', array("id", $get_data->id), array("value" => $paravalue));
                } else {
                    $count1 = $this->data_autorize_model->num_row('test_parameter', array("test_fk" => $test, "parameter_fk" => $parameter_id, "status" => 1));
                    if ($count1 != 0) {
                        $this->data_autorize_model->master_fun_insert('user_test_result', array("job_id" => $job_id, "test_id" => $test, "parameter_id" => $parameter_id, "value" => $paravalue, "created_by" => $data["login_data"]["id"], "created_date" => date("Y-m-d H:i:s")));
                    }
                }
            }
        }
        $get_barcode = $this->data_autorize_model->fetchdatarow('barcode', 'instument_data_storage_new', array("id" => $add_values_id));
        $this->data_autorize_model->master_fun_update('instument_data_storage_new', array("barcode", $get_barcode->barcode), array("status" => 2));
    }
    function view_details($barcode) {
        $data['all_show'] = $this->data_autorize_model->get_master_get_data('instument_data_storage_new', array("barcode" => $barcode, "status" => '1'), array("id", "desc"));
        $this->load->view('data_authorise_view',$data);
    }

}

?>