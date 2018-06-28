<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Test_panel extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('job_model');
        $this->load->model('test_model');

        $this->load->model('user_model');
        $this->load->model('test_panel_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $data["login_data"] = logindata();
        $this->app_track();
    }

    function app_track() {
        $this->load->library("Util");
        $util = new Util();
        //$util->app_track();
    }

    function test_panel_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");

        $name = strtolower(trim($this->input->get('name')));
        $data['name'] = $name;


        if ($name != "") {

            $totalRows = $this->test_panel_model->test_panel_list_num($name);
            $config = array();
            $config["base_url"] = base_url() . "Test_panel/test_panel_list/?name=$name";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 100;
            $config["uri_segment"] = 3;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $config['page_query_string'] = TRUE;
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->test_panel_model->test_panel_list($name, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        } else {
            $totalRows = $this->test_panel_model->test_panel_list_num($name);
            $config = array();
            $config["base_url"] = base_url() . "test_panel/test_panel_list/";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 100;
            $config["uri_segment"] = 3;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $config['page_query_string'] = TRUE;
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data["query"] = $this->test_panel_model->test_panel_list($name, $config["per_page"], $page);
            /* echo "<pre>"; print_r($data["query"] ); die(); */
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        }
        $cnt = 0;

        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('test_panel_list', $data);
        $this->load->view('footer');
    }

    function panel_add() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

        if ($this->form_validation->run() != FALSE) {

            $post['name'] = ($this->input->post('name'));
            $post['created_date'] = date('Y-m-d H:i:s');
            $post['created_by'] = $data["login_data"]["id"];
            $data['query'] = $this->user_model->master_fun_insert("test_panel", $post);

            $this->session->set_flashdata("success", array("Test successfully added."));
            redirect('Test_panel/test_panel_list', 'refresh');
        } else {

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('panel_add', $data);
            $this->load->view('footer');
        }
    }

    function panel_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $id = $this->uri->segment('3');
        $data['query'] = $this->test_panel_model->master_fun_update("test_panel", array("id", $id), array("status" => "0"));

        $this->session->set_flashdata("success", array("Test successfully deleted."));

        redirect("Test_panel/test_panel_list", "refresh");
    }

    //panel_test_delete
    function panel_test_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $id = $this->uri->segment('3');
        $data['query'] = $this->test_panel_model->master_fun_update("panel_tests", array("id", $id), array("status" => "0"));
        $pid = $this->test_panel_model->fetchdatarow('panel_fk', 'panel_tests', array('id' => $id));
        $this->session->set_flashdata("success", array("Test successfully deleted."));

        redirect("Test_panel/panel_test_add/" . $pid->panel_fk, "refresh");
    }

    function panel_edit() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data['id'] = $this->uri->segment(3);
        $id = $this->uri->segment(3);

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["query"] = $this->test_panel_model->fetchdatarow('*', 'test_panel', array('id' => $id));
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        if ($this->form_validation->run() != FALSE) {
            $post['name'] = strtolower($this->input->post('name'));
            $post['updated_date'] = date('Y-m-d H:i:s');
            $post['updated_by'] = $data["login_data"]["id"];
            $data['query'] = $this->test_panel_model->master_fun_update("test_panel", array("id", $id), $post);

            $this->session->set_flashdata("success", array("Test panel successfully updated."));
            redirect("Test_panel/test_panel_list", "refresh");
        } else {

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('panel_edit', $data);
            $this->load->view('footer');
        }
    }

    function get_panel_test_list() {

        $panelid = $this->input->get_post("panelid");
        $test_city = $this->input->get_post("test_city");
        $selected_test = array();
        $selected_package = array();



        $test = $this->job_model->get_val("SELECT 
  test_master.`id`,
  `test_master`.`TEST_CODE`,
  `test_master`.`test_name`,
  `test_master`.`test_name`,
  `test_master`.`PRINTING_NAME`,
  `test_master`.`description`,
  `test_master`.`SECTION_CODE`,
  `test_master`.`LAB_COST`,
  `test_master`.`status`,
  `panel_tests`.`price`,
  t_tst AS sub_test 
FROM
   `test_master` 
  INNER JOIN `panel_tests` 
    ON `test_master`.`id` = `panel_tests`.`test_fk`
  LEFT JOIN 
    (SELECT 
      GROUP_CONCAT(tmm.`test_name` SEPARATOR '%@%') AS t_tst,
      tm.`id` 
    FROM
      `sub_test_master` 
      LEFT JOIN `test_master` tm 
        ON `sub_test_master`.`test_fk` = tm.`id` 
      LEFT JOIN test_master tmm 
        ON `sub_test_master`.`sub_test` = tmm.id 
    WHERE `sub_test_master`.`status` = '1' 
    GROUP BY tm.`id`) AS tst 
    ON tst.id = `test_master`.`id` 
WHERE `test_master`.`status` = '1' 
  AND `panel_tests`.`status` = '1' 
  AND `panel_tests`.`city_fk` = '" . $test_city . "' 
  AND `panel_tests`.`panel_fk` = '" . $panelid . "' 
GROUP BY `test_master`.`id`");
        /* $package = $this->test_panel_model->get_val("SELECT 
          `package_master`.*,
          `package_master_city_price`.`a_price` AS `a_price1`,
          `package_master_city_price`.`d_price` AS `d_price1`
          FROM
          `package_master`
          INNER JOIN `package_master_city_price`
          ON `package_master`.`id` = `package_master_city_price`.`package_fk`
          WHERE `package_master`.`status` = '1'
          AND `package_master_city_price`.`status` = '1' AND `package_master_city_price`.`city_fk` = '$city_fk' "); */
        $test_list = '<option value="">--Select Test--</option>';
//        $test_list = '<select class="chosen-select" data-live-search="true" id="test" data-placeholder="Select Test" onchange="get_test_price();">
//			<option value="">--Select Test--</option>';
        foreach ($test as $ts) {
            if (!in_array($ts['id'], $selected_test)) {
                $test_list .= ' <option value="pt-' . $ts['id'] . '">' . ucfirst($ts['test_name']) . ' (Rs.' . $ts['price'] . ')</option>';
            }
        }/*
          foreach ($package as $pk) {
          if (!in_array($pk['id'], $selected_package)) {
          $test_list .= '<option value="p-' . $pk['id'] . '">' . ucfirst($pk['title']) . ' (Rs.' . $pk['d_price1'] . ')</option>';
          }
          } */
        //$test_list .= '</select>';

        echo json_encode(array("refer" => $refer, "test_list" => $test_list, "customer" => $customer_data, "test_ary" => $test));
    }

    function panel_test_add($tid) {
        if (!is_loggedin() || empty($tid)) {
            redirect('login');
        }
        $data["tid"] = $tid;
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $userid = $data["login_data"]["id"];

        if ($this->session->userdata('success') != null) {
            $data['success'] = $this->session->userdata("success");
            $this->session->unset_userdata('success');
        }
        if ($this->session->userdata('unsuccess') != null) {
            $data['unsuccess'] = $this->session->userdata("unsuccess");
            $this->session->unset_userdata('unsuccess');
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('test', 'Test', 'trim|required');

        if ($this->form_validation->run() != FALSE) {

            $test = $this->input->post('test');
            $city_fk = $this->input->post('city');
            $price = $this->input->post('price');
            $add_all = $this->input->post("add_all");
            $tn = explode("-", $test);
            $test_fk = $tn[1];
            $data1 = array(
                "test_fk" => $test_fk,
                "city_fk" => $city_fk,
                "panel_fk" => $tid,
                "price" => $price,
                "createddate" => date("Y-m-d H:i:s"),
                "created_by" => $data["login_data"]['id']
            );
            $check = $this->test_model->master_fun_get_tbl_val("panel_tests", array("test_fk" => $test_fk, "city_fk" => $city_fk, "panel_fk" => $tid, "status" => "1"), array("id", "asc"));

            if (empty($check)) {
                if ($add_all != 1) {
                    $insert = $this->test_model->master_fun_insert('panel_tests', $data1);
                } else {
                    $test_panel = $this->job_model->master_fun_get_tbl_val("test_panel", array('status' => 1), array("id", "asc"));
                    foreach ($test_panel as $pky) {
                        $data12 = array(
                            "test_fk" => $test_fk,
                            "city_fk" => $city_fk,
                            "panel_fk" => $pky["id"],
                            "price" => $this->input->post('price'),
                            "createddate" => date("Y-m-d H:i:s"),
                            "created_by" => $data["login_data"]['id']
                        );
                        //echo "<pre>"; print_R($data12); die();
                        $insert = $this->test_model->master_fun_insert('panel_tests', $data12);
                    }
                }
                $ses = array("Test Successfully Inserted!");
                $this->session->set_userdata('success', $ses);
            } else {
                $ses = array("Test already exists!");
                $this->session->set_userdata('unsuccess', $ses);
            }
            redirect('test_panel/panel_test_add/' . $this->uri->segment(3));
        } else {
            $data['test'] = $this->test_model->master_fun_get_tbl_val("test_master", array("status" => 1), array("test_name", "desc"));
            $data['test_info'] = $this->test_model->master_fun_get_tbl_val("test_panel", array("id" => $this->uri->segment(3)), array("id", "desc"));
            $data['sub_test'] = $this->test_model->get_val("SELECT  panel_tests.`id`,`panel_tests`.`price`,`test_name`,test_cities.`name` as city
FROM `panel_tests`  LEFT JOIN `test_master` ON `panel_tests`.`test_fk`=`test_master`.`id` LEFT JOIN `test_cities` 
 ON `test_cities`.`id`= panel_tests.`city_fk` WHERE `panel_tests`.`status`=1 AND `panel_tests`.`panel_fk`='" . $this->uri->segment(3) . "'");
            $data['test_cities'] = $this->job_model->master_fun_get_tbl_val("test_cities", array('status' => 1), array("id", "asc"));
            $data['citys'] = $this->test_model->master_fun_get_tbl_val("test_cities", array("status" => '1'), array("name", "asc"));
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('panel_test_add');
            $this->load->view('footer');
        }
    }

    public function import_list() {
        $cnt = 0;
        $this->load->library('csvimport');
        $file = $_FILES['id_browes']['name'];
        if ($file != "") {
            $file = $_FILES['id_browes']['name'];
            $filename = $_FILES['id_browes']['name'];
            $filename = md5(time()) . $filename;
            $output['status'] = FALSE;
            set_time_limit(0);
            $config['upload_path'] = "./upload/";
            $output['image_medium2'] = $config['upload_path'];
            $config['allowed_types'] = '*';
            $config['file_name'] = $filename;
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('id_browes')) {
                $error = array($this->upload->display_errors());
                $this->session->set_flashdata('success', array($error));
                if (!empty($this->session->userdata("test_master_r"))) {
                    
                } else {
                    
                }
            } else {
                $file_data = $this->upload->data();
                $file_path = './upload/' . $file_data['file_name'];

                if ($this->csvimport->get_array($file_path)) {
                    $csv_array = $this->csvimport->get_array($file_path);
                    $cnt = 0;
                    $cnt2 = 0;
                    $cnt3 = 0;
                    $cnt4 = 0;

                    foreach ($csv_array as $row) {
                        $cnt3++;

                        if ($row['panel_price'] != "" && $row['test_id'] != "") {
                            $cnt++;
                            $data['query'] = $this->test_panel_model->master_fun_insert("panel_tests", array("panel_fk" => '17', "city_fk" => '8', "test_fk" => $row['test_id'], "price" => $row['panel_price'], "status" => '1'));
                        } else {
                            $cnt2++;
                        }
                    }
                }
            }
        }
        if ($cnt == '0') {
            echo $ses = "Error occured";
            $this->session->set_flashdata('success', array($ses));
            if (!empty($this->session->userdata("test_master_r"))) {
                
            } else {
                
            }
        } else {
            echo $ses = array($cnt . "panel Added Successfully");
            echo "added" . $cnt . "<br> total" . $cnt3 . "<br> non mobile" . $cnt2 . "<br> allready" . $cnt4;
            $this->session->set_flashdata('success', $ses);
            if (!empty($this->session->userdata("test_master_r"))) {
                
            } else {
                
            }
        }
    }

}

?>
