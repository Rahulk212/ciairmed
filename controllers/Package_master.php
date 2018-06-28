<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Package_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('package_model');
        $this->load->model('user_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
    }

    function index() {
        if (!is_loggedin()) {
            redirect('login');
        }
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
        $this->form_validation->set_rules('title', 'Title', 'trim|required');
        $this->form_validation->set_rules('aprice[]', 'Actual Price', 'trim|required');
        $this->form_validation->set_rules('dprice[]', 'Discount Price', 'trim|required');
        $this->form_validation->set_rules('desc_web', 'Description for Website', 'trim|required');
        $this->form_validation->set_rules('desc_app', 'Description for Application', 'trim|required');


        if ($this->form_validation->run() != FALSE) {

            $title = $this->input->post('title');
            $aprice = $this->input->post('aprice');
            $dprice = $this->input->post('dprice');
            $desc_web = $this->input->post('desc_web');
            $desc_app = $this->input->post('desc_app');
            $validity = $this->input->post('validity');
            $book = $this->input->post('book');
            $city = $this->input->post('city');
            $p_type = $this->input->post('p_type');
            $is_view = $this->input->post('is_view');

            if ($_FILES["sliderfile"]["name"]) {
                $config['upload_path'] = './upload/package/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['file_name'] = time() . $_FILES["sliderfile"]["name"];
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload("sliderfile")) {
                    $error = array('error' => $this->upload->display_errors());
                    $this->load->view('header');
                    $this->load->view('nav', $data);
                    $this->load->view('package_add', $error);
                    $this->load->view('footer');
                } else {
                    $data = array('upload_data' => $this->upload->data());
                    $file_name = $data["upload_data"]["file_name"];
                }
            }
            if ($_FILES["homefile"]["name"]) {
                $config['upload_path'] = './upload/package/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['file_name'] = time() . $_FILES["homefile"]["name"];
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload("homefile")) {
                    $error = array('error' => $this->upload->display_errors());
                    $this->load->view('header');
                    $this->load->view('nav', $data);
                    $this->load->view('package_add', $error);
                    $this->load->view('footer');
                } else {
                    $data = array('upload_data' => $this->upload->data());
                    $file_name1 = $data["upload_data"]["file_name"];
                }
            }

            $time = $this->package_model->get_server_time();
            $data1 = array(
                "title" => $title,
                "image" => $file_name,
                "back_image" => $file_name1,
                //"a_price" => $aprice,
                //"d_price" => $dprice,
                "desc_web" => $desc_web,
                "desc_app" => $desc_app,
                "validity" => $validity,
                "booking_time" => $book,
                "is_view"=>$is_view,
                "category_fk"=>$p_type
            );
            $insert = $this->package_model->master_fun_insert('package_master', $data1);

            $cnt = 0;
            foreach ($aprice as $key) {
                //$test_id = $this->package_model->master_fun_insert('test_master', array("test_name" => $title, "price" => $dprice[$cnt]));
                //$this->package_model->master_fun_insert('test_master_city_price', array("test_fk" => $test_id, "city_fk" => $city[$cnt], "price" => $dprice[$cnt]));
                $this->package_model->master_fun_insert('package_master_city_price', array("package_fk" => $insert, "city_fk" => $city[$cnt], "a_price" => $aprice[$cnt], "d_price" => $dprice[$cnt]));
                $cnt++;
            }

            $ses = array("Package Successfully Inserted!");
            $this->session->set_userdata('success', $ses);
            redirect('package_master/package_list');
        } else {
            $data['type'] = $this->package_model->master_fun_get_tbl_val("package_category", array("status" =>'1'), array("name", "asc"));
            $data['citys'] = $this->package_model->master_fun_get_tbl_val("test_cities", array("status" => '1'), array("name", "asc"));
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('package_add');
            $this->load->view('footer');
        }
    }
    function package_edit() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $id = $data["id"] = $this->uri->segment('3');
        $title = $this->input->post('title');
        $aprice = $this->input->post('aprice');
        $dprice = $this->input->post('dprice');
        $test = $this->input->post('test');
        $desc_web = $this->input->post('desc_web');
        $desc_app = $this->input->post('desc_app');
        $is_view = $this->input->post('is_view');
        $p_type = $this->input->post('p_type');
        if ($this->session->userdata('unsuccess') != null) {
            $data['unsuccess'] = $this->session->userdata("unsuccess");
            $this->session->unset_userdata('unsuccess');
        }
        $this->form_validation->set_rules('id', 'Id', 'required');
        $this->form_validation->set_rules('title', 'Title', 'trim|required');
        $this->form_validation->set_rules('aprice[]', 'Actual Price', 'trim|required');
        $this->form_validation->set_rules('dprice[]', 'Discount Price', 'trim|required');
        $this->form_validation->set_rules('desc_web', 'Description for Website', 'trim|required');
        $this->form_validation->set_rules('desc_app', 'Description for Application', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $data['type'] = $this->package_model->master_fun_get_tbl_val("package_category", array("status" =>'1'), array("name", "asc"));
            $data['query'] = $this->package_model->master_fun_get_tbl_val("package_master", array("id" => $this->uri->segment('3')), array("id", "desc"));
            $data['citys'] = $this->package_model->master_fun_get_tbl_val("test_cities", array("status" => '1'), array("name", "asc"));
            $data['city_price'] = $this->package_model->master_fun_get_tbl_val("package_master_city_price", array("status" => '1', "package_fk" => $this->uri->segment('3')), array("id", "asc"));
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('package_edit', $data);
            $this->load->view('footer');
        } else {
            $first_file_name = array();
            if ($_FILES["homefile"]["name"]) {
                $config['upload_path'] = './upload/package/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['file_name'] = time() . $_FILES["homefile"]["name"];
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload("homefile")) {
                    $data['error'] = array('error' => $this->upload->display_errors());
                    $this->load->view('header');
                    $this->load->view('nav', $data);
                    $this->load->view('package_edit', $data);
                    $this->load->view('footer');
                } else {
                    $data = array('upload_data' => $this->upload->data());
                    $file_name = $data["upload_data"]["file_name"];
                    $first_file_name["back_image"] = $file_name;
                }
            }
            $second_file_name = array();
            if ($_FILES["sliderfile"]["name"]) {
                $config['upload_path'] = './upload/package/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['file_name'] = time() . $_FILES["sliderfile"]["name"];
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload("sliderfile")) {
                    $data['error'] = array('error' => $this->upload->display_errors());
                    $this->load->view('header');
                    $this->load->view('nav', $data);
                    $this->load->view('package_edit', $data);
                    $this->load->view('footer');
                } else {
                    $data = array('upload_data' => $this->upload->data());
                    $file_name1 = $data["upload_data"]["file_name"];
                    $second_file_name["image"] = $file_name1;
                }
            }
            $validity = $this->input->post('validity');
            $book = $this->input->post('book');
            $data1 = array(
                "title" => $title,
                "desc_web" => $desc_web,
                "desc_app" => $desc_app,
                "validity" => $validity,
                "category_fk"=>$p_type,
                "booking_time" => $book,
                "is_view"=>$is_view,
            );
            $city = $this->input->post("city");
            $data2 = $data1 + $first_file_name + $second_file_name;
            $update = $this->package_model->master_fun_update("package_master", $this->uri->segment('3'), $data2);
            /* Nishit code start */
            $this->package_model->master_fun_update1("package_master_city_price", array("package_fk", $this->uri->segment('3')), array("status" => "0"));
            $cnt = 0;
            foreach ($aprice as $key) {

                //$this->package_model->master_fun_update1("test_master", array("id", $test[$cnt]), array("test_name" => $title,"price"=>$dprice[$cnt]));
                //$this->package_model->master_fun_update1('test_master_city_price', array("test_fk",$test[$cnt]),array("price"=>$dprice[$cnt]));
                $this->package_model->master_fun_insert('package_master_city_price', array("package_fk" => $this->uri->segment('3'), "city_fk" => $city[$cnt], "a_price" => $aprice[$cnt], "d_price" => $dprice[$cnt], "refrance_test_fk" => $test[$cnt], "status" => "1"));
                //$this->package_model->master_fun_insert('package_master_city_price', array("package_fk" => $this->uri->segment('3'), "city_fk" => $city[$cnt], "a_price" => $aprice[$cnt], "d_price" => $dprice[$cnt]));
                $cnt++;
            }
            /* Nishit code end */
            $ses = array("Package Successfully Updated!");
            $this->session->set_userdata('success', $ses);
            redirect('package_master/package_list');
        }
    }
    function package_test_add($pid) {
        if (!is_loggedin() || empty($pid)) {
            redirect('login');
        }
        $data["pid"] = $pid;
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

            $time = $this->package_model->get_server_time();
            $data1 = array(
                "package_fk" => $pid,
                "test_fk" => $test,
                "createddate" => date("Y-m-d H:i:s")
            );
            $check = $this->package_model->master_fun_get_tbl_val("package_test", array("package_fk" => $pid, "test_fk" => $test, "status" => "1"), array("id", "asc"));
            if (empty($check)) {
                $insert = $this->package_model->master_fun_insert('package_test', $data1);
                $ses = array("Test Successfully Inserted!");
                $this->session->set_userdata('success', $ses);
            } else {
                $ses = array("Test already exists!");
                $this->session->set_userdata('unsuccess', $ses);
            }

            redirect('package_master/package_test_add/' . $this->uri->segment(3));
        } else {
            $data['test'] = $this->package_model->master_fun_get_tbl_val("test_master", array("status" => 1), array("test_name", "desc"));
            $data['package_info'] = $this->package_model->master_fun_get_tbl_val("package_master", array("id" => $this->uri->segment(3)), array("id", "desc"));
            $data['package_test'] = $this->package_model->get_val("SELECT package_test.*,`test_master`.`test_name` FROM `package_test` INNER JOIN `test_master` ON `package_test`.`test_fk`=`test_master`.id WHERE `package_test`.status='1' AND `test_master`.`status`='1' AND `package_test`.`package_fk`='" . $pid . "'");
            //print_R($data['package_test']); die();
            $data['citys'] = $this->package_model->master_fun_get_tbl_val("test_cities", array("status" => '1'), array("name", "asc"));
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('package_test_add');
            $this->load->view('footer');
        }
    }

    function package_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
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

        $data["query"] = $this->package_model->get_active_record();
        $totalRows = count($data["query"]);
        $config = array();
        $config["base_url"] = base_url() . "package_master/package_list/";
        $config["total_rows"] = $totalRows;
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $sort = $this->input->get("sort");
        $by = $this->input->get("by");
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["query"] = $this->package_model->get_active_record1($config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();
        $cnt = 0;
        foreach ($data["query"] as $key) {
            $pkg_price = $this->package_model->get_package_price($key["id"]);
            $data["query"][$cnt]["price1"] = $pkg_price;
            $cnt++;
        }
        //echo "<pre>"; print_R($data["query"]); die();
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('package_view', $data);
        $this->load->view('footer');
    }



    function package_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $userid = $data["login_data"]["id"];

        $cid = $this->uri->segment('3');
        $data = array(
            "status" => '0'
        );
        $package_test = $this->package_model->master_fun_get_tbl_val("package_master_city_price", array("package_fk" => $this->uri->segment('3'), "status" => "1"), array("id", "asc"));
        /* foreach($package_test as $key){
          $this->package_model->master_fun_update("test_master", $key["refrance_test_fk"], array("status"=>"0"));
          } */
        $delete = $this->package_model->master_fun_update("package_master", $this->uri->segment('3'), $data);
        if ($delete) {
            $ses = array("Package Successfully Deleted!");
            $this->session->set_userdata('success', $ses);
            redirect('package_master/package_list', 'refresh');
        }
    }

    function package_test_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $userid = $data["login_data"]["id"];

        $pid = $this->uri->segment(3);
        $tid = $this->uri->segment(4);
        $data = array(
            "status" => '0'
        );
        $delete = $this->package_model->master_fun_update("package_test", $this->uri->segment('4'), $data);
        if ($delete) {
            $ses = array("Test Successfully Deleted!");
            $this->session->set_userdata('success', $ses);
            redirect('package_master/package_test_add/' . $pid, 'refresh');
        }
    }

    function export_csv() {
        $result = $this->user_model->get_val("SELECT `id`,`title`,`a_price`,`d_price` FROM `package_master` WHERE `status`='1'");
        $new_data = array();
        foreach ($result as $p_key) {
            $packge_price_data = $this->user_model->get_val("SELECT package_master_city_price.*,`test_cities`.`name` FROM `package_master_city_price` INNER JOIN `test_cities` ON `package_master_city_price`.`city_fk`=`test_cities`.`id` WHERE `package_master_city_price`.`status`='1' AND `package_master_city_price`.`package_fk`='" . $p_key["id"] . "'");
            $p_key["city_price"] = $packge_price_data;
            $packge_price_data = $this->user_model->get_val("SELECT `package_test`.*,`test_master`.`test_name` FROM `package_test` INNER JOIN `test_master` ON `package_test`.`test_fk`=`test_master`.id WHERE `package_test`.`status`='1' AND `package_test`.`package_fk`='" . $p_key["id"] . "'");
            $p_key["package_test"] = $packge_price_data;
            $new_data[] = $p_key;
        }
        $cnt = 0;
        $csv_array = array();
        $data_array = array();
        for ($i = 0; $i < count($new_data); $i++) {
            $data_array[] = $new_data[$i]["title"];
        }
        $csv_array[] = $data_array;

        $data_array = array();
        for ($i = 0; $i < count($new_data); $i++) {
            $p_test = array();
            foreach ($new_data[$i]['package_test'] as $key) {
                $p_test[] = "\r\n".$key["test_name"];
            }
            $data_array[] = implode(",", $p_test);
        }
        $csv_array[] = $data_array;

        $data_array = array();
        for ($i = 0; $i < count($new_data); $i++) {
            $p_test = array();
            foreach ($new_data[$i]['city_price'] as $key) {
                $p_test[] = "\r\n".$key["name"] . "-Rs." . $key["a_price"] . "(Actual),Rs." . $key["d_price"] . "(Discount)";
            }
            $data_array[] = implode(" ", $p_test);
        }
        $csv_array[] = $data_array;

        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"Package_details-" . date('d-M-Y') . ".csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
        fputcsv($handle, $csv_array[0]);
        fputcsv($handle, $csv_array[1]);
        fputcsv($handle, $csv_array[2]);
        fclose($handle);
        exit;
    }

}

?>