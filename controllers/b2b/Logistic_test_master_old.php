<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Logistic_test_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('user_model');
        $this->load->model('b2b/Logistic_test_modal');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $data["login_data"] = logindata();
    }

    function test_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $lab_fk = $data["lab_fk"] = $this->uri->segment(4);
        if (!empty($lab_fk)) {
            $data["login_data"] = logindata();
            $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
            $data['success'] = $this->session->flashdata("success");
            $search = $this->input->get('search');
            $city = $this->input->get('city');
            $data['search'] = $search;
            $data['city'] = $city;
            $cquery = "";
            //die("OK");
            if ($search != "" || $city != '') {
                if (!empty($city)) {
                    $cquery = "AND `sample_test_city_price`.`city_fk`='" . $city . "'";
                }
                $total_row = $this->Logistic_test_modal->test_list_search_num($lab_fk, $search, $city);
                $config = array();
                $config["base_url"] = base_url() . "b2b/logistic_test_master/test_list/?search=$search&city=$city";
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
                $data['query'] = $this->Logistic_test_modal->test_list_search($lab_fk, $search, $city, $config["per_page"], $page);
                $data["links"] = $this->pagination->create_links();
                $data["counts"] = $page;
            } else {
                $totalRows = $this->Logistic_test_modal->num_row('sample_test_master', array('status' => 1, "lab_fk" => $lab_fk));
                $config = array();
                $config["base_url"] = base_url() . "b2b/logistic_test_master/test_list/" . $lab_fk . "/";
                $config["total_rows"] = $totalRows;
                $config["per_page"] = 50;
                $config["uri_segment"] = 5;
                $config['cur_tag_open'] = '<span>';
                $config['cur_tag_close'] = '</span>';
                $config['next_link'] = 'Next &rsaquo;';
                $config['prev_link'] = '&lsaquo; Previous';
                $this->pagination->initialize($config);
                $sort = $this->input->get("sort");
                $by = $this->input->get("by");
                $page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
                $data["query"] = $this->Logistic_test_modal->test_list($lab_fk, $config["per_page"], $page);
                $data["links"] = $this->pagination->create_links();
                $data["counts"] = $page;
            }
            $cnt = 0;
            $new_array = array();
            foreach ($data["query"] as $key) {
                $city_price = $this->Logistic_test_modal->get_val("SELECT `sample_test_city_price`.*,`test_cities`.`name` FROM `sample_test_city_price` INNER JOIN `test_cities` ON `sample_test_city_price`.`city_fk`=`test_cities`.`id` WHERE `sample_test_city_price`.`status`='1' AND `test_cities`.`status`='1' AND `sample_test_city_price`.`test_fk`='" . $key["id"] . "' $cquery ORDER BY `test_cities`.`name` asc");
                if (!empty($city_price)) {
                    $key["city_wise_price"] = $city_price;
                    $new_array[] = $key;
                    $cnt++;
                }
            }
            $data["query"] = $new_array;
            //echo "<pre>"; print_R($data["query"]); die();
            $url = "http://" . $_SERVER['HTTP_HOST'] . ":" . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
            $this->session->set_userdata("test_master_r", $url);
            $data['citys'] = $this->Logistic_test_modal->master_fun_get_tbl_val("test_cities", array("status" => '1'), array("name", "asc"));
            $this->load->view('b2b/header');
            $this->load->view('b2b/nav_logistic', $data);
            $this->load->view('b2b/l_test_list', $data);
            $this->load->view('b2b/footer');
        } else {
            redirect("b2b/Logistic/dashboard", "refresh");
        }
    }

    function price_list($id = "") {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        if ($id == "") {
            $id = 1;
        }
        $data['id'] = $id;
        $data['query'] = $this->Logistic_test_modal->price_test_list($id);
        $data['citys'] = $this->Logistic_test_modal->master_fun_get_tbl_val("test_cities", array("status" => '1'), array("name", "asc"));
        $this->load->view('b2b/header');
        $this->load->view('b2b/nav_logistic', $data);
        $this->load->view('b2b/city_test_list', $data);
        $this->load->view('b2b/footer');
    }

    function test_add() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["city_list"] = $this->Logistic_test_modal->get_city();
        $lab_fk = $data["lab_fk"] = $this->uri->segment(4);
        if (!empty($lab_fk)) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('name', 'Name', 'trim|required');
            if (empty($_POST['price'])) {
                $this->form_validation->set_rules('price', 'Price', 'trim|required|numeric');
            }
            //$this->form_validation->set_rules('price1', "A'bad Price", 'trim|required|numeric');
            //$this->form_validation->set_rules('city', 'City', 'trim|required|numeric');
            $this->form_validation->set_rules('desc', 'Description', 'trim');
            if ($this->form_validation->run() != FALSE) {
                //echo "<pre>"; print_r($_POST); die();
                $name = $this->input->post('name');
                //$price1 = $this->input->post('price1');
                $price = $this->input->post('price');
                $city = $this->input->post('city');
                $desc = $this->input->post('desc');
                $fasting = $this->input->post('fasting');
                $data['query'] = $this->Logistic_test_modal->master_fun_insert("sample_test_master", array("lab_fk" => $lab_fk, "test_name" => $name, "description" => $desc, "fasting_requird" => $fasting,"price" =>$price));
                $cnt = 0;
                $this->Logistic_test_modal->master_fun_insert("sample_test_city_price", array("test_fk" => $data['query'], "city_fk" => $lab_fk, "price" => $price));
                /*
                  $testfk = $this->Logistic_test_modal->master_fun_insert("sample_test_master", array("test_name" => $name, "description" => $desc,"fasting_requird"=>$fasting));
                  $this->Logistic_test_modal->master_fun_insert("sample_test_city_price", array("test_fk" => $testfk, "city_fk" => $city, "price" => $price));
                 */
                $this->session->set_flashdata("success", array("Test successfully added."));
                redirect("b2b/Logistic_test_master/test_list/" . $lab_fk, "refresh");
            } else {
                $data['citys'] = $this->Logistic_test_modal->master_fun_get_tbl_val("test_cities", array("status" => '1'), array("name", "asc"));
                $this->load->view('b2b/header');
                $this->load->view('b2b/nav_logistic', $data);
                $this->load->view('b2b/test_add', $data);
                $this->load->view('b2b/footer');
            }
        } else {
            redirect("b2b/Logistic/dashboard");
        }
    }

    function test_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('4');
        $data['query'] = $this->Logistic_test_modal->master_fun_update("sample_test_master", array("id", $cid), array("status" => "0"));
        $this->session->set_flashdata("success", array("Test successfully deleted."));
        if (!empty($this->session->userdata("test_master_r"))) {
            redirect($this->session->userdata("test_master_r"), "refresh");
        } else {
            redirect("b2b/logistic_test_master/test_list", "refresh");
        }
    }

    function test_edit() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["cid"] = $this->uri->segment('4');
        $data["city_list"] = $this->Logistic_test_modal->get_city();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        //$this->form_validation->set_rules('price[]', 'Price', 'trim|required|numeric');
        //$this->form_validation->set_rules('price1', "A'bad Price", 'trim|required|numeric');
        $this->form_validation->set_rules('city[]', 'City', 'trim|required|numeric');
        $this->form_validation->set_rules('desc', 'Description', 'trim');
        if ($this->form_validation->run() != FALSE) {
            $name = $this->input->post('name');
            $price = $this->input->post('price');
            //$price1 = $this->input->post('price1');
            $city = $this->input->post('city');
            $desc = $this->input->post('desc');
            $fasting = $this->input->post('fasting');
            if (empty($fasting)) {
                $fasting = 0;
            }
            $data['query'] = $this->Logistic_test_modal->master_fun_update("sample_test_master", array("id", $data["cid"]), array("test_name" => $name, "description" => $desc, "fasting_requird" => $fasting));
            $delete = $this->Logistic_test_modal->master_fun_delete("sample_test_city_price", array("test_fk", $data["cid"]));
            $cnt = 0;
            if ($delete) {
                foreach ($city as $frm_price) {
                    if (!empty($price[$cnt])) {
                        $this->Logistic_test_modal->master_fun_insert("sample_test_city_price", array("test_fk" => $data["cid"], "city_fk" => $frm_price, "price" => $price[$cnt]));
                    }
                    $cnt++;
                }
            }
            $this->session->set_flashdata("success", array("Test successfully updated."));
            if (!empty($this->session->userdata("test_master_r"))) {
                redirect($this->session->userdata("test_master_r"), "refresh");
            } else {
                redirect("test-master/test-list", "refresh");
            }
        } else {
            $data['query'] = $this->Logistic_test_modal->master_fun_get_tbl_val("sample_test_master", array("id" => $data["cid"]), array("id", "desc"));
            $data['city_price'] = $this->Logistic_test_modal->get_city_edit1($data["cid"]);
            $data['test_city'] = $this->Logistic_test_modal->master_fun_get_tbl_val("test_cities", array("status" => '1'), array("name", "asc"));
            $this->load->view('b2b/header');
            $this->load->view('b2b/nav_logistic', $data);
            $this->load->view('b2b/test_edit', $data);
            $this->load->view('b2b/footer');
        }
    }

    function test_csv() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $city = $this->input->get("city");
        $delimiter = ",";
        $newline = "\r\n";
        $filename = "test.csv";
        //$query = 'SELECT t.test_name as `Test Name`,c.name as `City Name`,p.price as `Test Price` from sample_test_master as t left join sample_test_city_price as p on t.id=p.test_fk join test_cities as c on p.city_fk=c.id where t.status="1" and c.status="1" and p.status="1" order by t.test_name asc';
        $query = "SELECT  t.id,t.`test_name` as testname ,c.`name` as cityname,tc.`price` FROM  `sample_test_master` t  LEFT JOIN sample_test_city_price tc ON tc.test_fk=t.id  LEFT JOIN `test_cities` c ON tc.`city_fk`=c.`id` WHERE t.status = '1' AND tc.city_fk='" . $city . "'";
        $result = $this->db->query($query);
        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
        force_download($filename, $data);
    }

    function importtest_csv() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->library('csvimport');
        $this->load->library('csvimport');
        if (empty($_FILES['testeximport']['name'])) {
            $this->form_validation->set_rules('testeximport', 'Upload', 'required');
        }
        if ($this->form_validation->run() == FALSE) {
            $config['upload_path'] = './upload/csv/';
            $config['allowed_types'] = 'csv';
            $config['file_name'] = time() . $_FILES['testeximport']['name'];
            $config['file_name'] = str_replace(' ', '_', $config['file_name']);
            $_FILES['testeximport']['name'];
            $file1 = $config['file_name'];
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('testeximport')) {
                $error = $this->upload->display_errors();
                $ses = array($error);
                $this->session->set_flashdata("success", $ses);
                redirect("test-master/test-list", "refresh");
            } else {
                $file_data = $this->upload->data();
                $file_path = './upload/csv/' . $file_data['file_name'];
                $cnt = 0;
                if ($this->csvimport->get_array($file_path)) {
                    $csv_array = $this->csvimport->get_array($file_path);
                    $countall = count($csv_array);
                    foreach ($csv_array as $row) {
                        $test_name = $row['testname'];
                        $cityname = $row['cityname'];
                        $price = $row['price'];
                        if ($test_name != '') {
                            $testrow = $this->Logistic_test_modal->num_row('sample_test_master', array("test_name" => $test_name, "status" => '1'));
                            if ($testrow == '0') {
                                $testinid = $this->Logistic_test_modal->master_fun_insert('sample_test_master', array("test_name" => $test_name, "status" => '1'));
                                $testrow = $this->Logistic_test_modal->num_row('test_cities', array("name" => $cityname, "status" => '1'));
                                if ($testrow != '0') {
                                    $citydeatils = $this->Logistic_test_modal->fetchdatarow('id', 'test_cities', array("name" => $cityname));
                                    $cityid = $citydeatils->id;
                                    $this->Logistic_test_modal->master_fun_insert('sample_test_city_price', array("test_fk" => $testinid, "city_fk" => $cityid));
                                }
                            } else {
                                $testdeatils = $this->Logistic_test_modal->fetchdatarow('id,test_name', 'sample_test_master', array("test_name" => $test_name, "status" => '1'));
                                $testid = $testdeatils->id;
                                $testrow = $this->Logistic_test_modal->num_row('sample_test_city_price', array("test_fk" => $testid, "status" => '1'));
                                if ($testrow == '0') {
                                    $testrow = $this->Logistic_test_modal->num_row('test_cities', array("name" => $cityname, "status" => '1'));
                                    if ($testrow != '0') {
                                        $citydeatils = $this->Logistic_test_modal->fetchdatarow('id', 'test_cities', array("name" => $cityname, "status" => '1'));
                                        $cityid = $citydeatils->id;
                                        $this->Logistic_test_modal->master_fun_insert('sample_test_city_price', array("test_fk" => $testid, "city_fk" => $cityid));
                                    }
                                }
                            }
                        }
                    }
                }
                if ($cnt == '0') {
                    $ses = "Error occured";
                    $this->session->set_flashdata('success', array($ses));
                    if (!empty($this->session->userdata("test_master_r"))) {
                        redirect($this->session->userdata("test_master_r"), "refresh");
                    } else {
                        redirect("b2b/test-master/test-list", "refresh");
                    }
                } else {
                    $ses = array($cnt . "Test Added Successfully");
                    $this->session->set_flashdata('success', $ses);
                    if (!empty($this->session->userdata("test_master_r"))) {
                        redirect($this->session->userdata("test_master_r"), "refresh");
                    } else {
                        redirect("b2b/test-master/test-list", "refresh");
                    }
                }
            }
        } else {
            show_404();
        }
    }

    function sub_test_add($tid) {
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

            $data1 = array(
                "test_fk" => $tid,
                "sub_test" => $test,
                "createddate" => date("Y-m-d H:i:s"),
                "created_by" => $data["login_data"]['id']
            );
            $check = $this->Logistic_test_modal->master_fun_get_tbl_val("sub_test_master", array("test_fk" => $tid, "sub_test" => $test, "status" => "1"), array("id", "asc"));
            if (empty($check)) {
                $insert = $this->Logistic_test_modal->master_fun_insert('sub_test_master', $data1);
                $ses = array("Test Successfully Inserted!");
                $this->session->set_userdata('success', $ses);
            } else {
                $ses = array("Test already exists!");
                $this->session->set_userdata('unsuccess', $ses);
            }
            redirect('b2b/sample_test_master/sub_test_add/' . $this->uri->segment(4));
        } else {
            $data['test'] = $this->Logistic_test_modal->master_fun_get_tbl_val("sample_test_master", array("status" => 1), array("test_name", "desc"));
            $data['test_info'] = $this->Logistic_test_modal->master_fun_get_tbl_val("sample_test_master", array("id" => $this->uri->segment(3)), array("id", "desc"));
            $data['sub_test'] = $this->Logistic_test_modal->get_val("SELECT `sub_test_master`.*,`sample_test_master`.`test_name` FROM `sub_test_master` INNER JOIN `sample_test_master` ON `sub_test_master`.`sub_test`=`sample_test_master`.`id` WHERE `sample_test_master`.`status`='1' AND `sub_test_master`.`status`='1' AND `sub_test_master`.`test_fk`='" . $this->uri->segment(3) . "'");
            $data['citys'] = $this->Logistic_test_modal->master_fun_get_tbl_val("test_cities", array("status" => '1'), array("name", "asc"));
            $this->load->view('b2b/header');
            $this->load->view('b2b/nav_logistic', $data);
            $this->load->view('b2b/sub_test_add');
            $this->load->view('b2b/footer');
        }
    }

    function sub_test_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $userid = $data["login_data"]["id"];

        $pid = $this->uri->segment(4);
        $tid = $this->uri->segment(5);
        $data = array(
            "status" => '0',
            "deleted_by" => $userid
        );
        $delete = $this->Logistic_test_modal->master_fun_update("sub_test_master", array("id", $this->uri->segment('4')), $data);
        if ($delete) {
            $ses = array("Test Successfully Deleted!");
            $this->session->set_userdata('success', $ses);
            redirect('b2b/sample_test_master/sub_test_add/' . $pid, 'refresh');
        }
    }

    function test_export($lid) {
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $lab = $this->Logistic_test_modal->master_fun_get_tbl_val("collect_from", array("status" => 1, "id" => $lid), array("id", "desc"));
        $delimiter = ",";
        $newline = "\r\n";
        $filename = $lab[0]['name'] . '-test_list.csv';
        $query = "SELECT stm.id,stm.test_name,stm.price FROM sample_test_master stm WHERE stm.lab_fk = '$lid' AND stm.status = '1' order by stm.id asc";
        $result = $this->db->query($query);
        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
        force_download($filename, $data);
    }

    public function import_list($lid) {
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
                    redirect($this->session->userdata("test_master_r"), "refresh");
                } else {
                    redirect("b2b/logistic_test_master/test_list/" . $lid, "refresh");
                }
            } else {
                $file_data = $this->upload->data();
                $file_path = './upload/' . $file_data['file_name'];

                if ($this->csvimport->get_array($file_path)) {
                    $csv_array = $this->csvimport->get_array($file_path);
                    $cnt = 0;
                    foreach ($csv_array as $row) {
                        $old_test = $this->Logistic_test_modal->master_fun_get_tbl_val("sample_test_master", array("status" => 1, "id" => $row['id'], "lab_fk" => $lid), array("id", "desc"));
                        if (empty($old_test)) {
                            $cnt++;
                            $this->Logistic_test_modal->master_fun_insert("sample_test_master", array("lab_fk" => $lid, "test_name" => $row['test_name'], "price" => $row['price']));
                        } else {
                            $cnt++;
                            $this->Logistic_test_modal->master_fun_update("sample_test_master", array("id", $row['id']), array("test_name" => $row['test_name'], "lab_fk" => $lid, "price" => $row['price']));
                        }
                    }
                }
            }
        }
        if ($cnt == '0') {
            $ses = "Error occured";
            $this->session->set_flashdata('success', array($ses));
            if (!empty($this->session->userdata("test_master_r"))) {
                redirect($this->session->userdata("test_master_r"), "refresh");
            } else {
                redirect("b2b/logistic_test_master/test_list/" . $lid, "refresh");
            }
        } else {
            $ses = array($cnt . "Test Added Successfully");
            $this->session->set_flashdata('success', $ses);
            if (!empty($this->session->userdata("test_master_r"))) {
                redirect($this->session->userdata("test_master_r"), "refresh");
            } else {
                redirect("b2b/logistic_test_master/test_list/" . $lid, "refresh");
            }
        }
    }

}

?>
