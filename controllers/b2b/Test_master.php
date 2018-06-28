<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Test_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('b2b/Test_b2bmodel');
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
    function test_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data['success'] = $this->session->flashdata("success");
        $search = $this->input->get('search');
        $city = $this->input->get('city'); 
        $data['search'] = $search;
        /* $data['city'] = $city; */
        $cquery = "";
        if ($search != "") {
            /* if (!empty($city)) {
                $cquery = "AND `test_master_city_price`.`city_fk`='" . $city . "'";
            } */
            $total_row = $this->Test_b2bmodel->test_list_search_num($search, $city);
            $config = array();
            $config["base_url"] = base_url() . "b2b/Test_master/test_list?search=$search&city=$city";
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
            $data['query'] = $this->Test_b2bmodel->test_list_search($search, $city, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        } else {

            $totalRows = $this->Test_b2bmodel->num_row('sample_test_master', array('status' => 1));
            $config = array();
            $config["base_url"] = base_url()."b2b/Test_master/test_list/";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 50;
            $config["uri_segment"] = 4;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
			

            $this->pagination->initialize($config);
            $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
			
            $data["query"] = $this->Test_b2bmodel->test_list($config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        }
		 /* echo "<pre>"; print_r($data['query']); die();  */
        $cnt = 0;
		$key["city_wise_price"] = array();
        $new_array = array();
        /* foreach ($data["query"] as $key) {
            $city_price = $this->Test_b2bmodel->get_val("SELECT `test_master_city_price`.*,`test_cities`.`name` FROM `test_master_city_price` INNER JOIN `test_cities` ON `test_master_city_price`.`city_fk`=`test_cities`.`id` WHERE `test_master_city_price`.`status`='1' AND `test_cities`.`status`='1' AND `test_master_city_price`.`test_fk`='" . $key["id"] . "' $cquery ORDER BY `test_cities`.`name` asc");
            if (!empty($city_price)) {
                $key["city_wise_price"] = $city_price;
                $new_array[] = $key;
                $cnt++;
            }
        } */
		
        $data["query"] = $data['query'];
        
		$url = "http://" . $_SERVER['HTTP_HOST'] . ":" . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
        $this->session->set_userdata("test_master_r", $url);
        $data['citys'] = $this->Test_b2bmodel->master_fun_get_tbl_val("test_cities", array("status" => '1'), array("name", "asc"));
        $data['departmnet_list'] = $this->Test_b2bmodel->master_fun_get_tbl_val("test_department_master", array("status" => '1'), array("name", "asc"));
		
        $this->load->view('b2b/header');
        $this->load->view('b2b/nav_logistic',$data);
        $this->load->view('b2b/test_list',$data);
        $this->load->view('b2b/footer');
    
	}

    function price_list($id = "") {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();

        if ($id == "") {
            $id = 1;
        }
        $data['id'] = $id;
        $data['query'] = $this->Test_b2bmodel->price_test_list($id);
        $data['citys'] = $this->Test_b2bmodel->master_fun_get_tbl_val("test_cities", array("status" => '1'), array("name", "asc"));
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('city_test_list', $data);
        $this->load->view('footer');
    }

    function test_add() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
		//$this->form_validation->set_rules('price', 'Price', 'trim|required|numeric');
		$this->form_validation->set_rules('desc', 'Description', 'trim');
		
        if ($this->form_validation->run() != FALSE) {
			
			$name = $this->input->post('name');
			$thyrocare_code = $this->input->post('thyrocare_code');
			$sample = $this->input->post('sample');

                //$price1 = $this->input->post('price1');
               //$price = $this->input->post('price');
                //$city = $this->input->post('city');
                $desc = $this->input->post('desc');
				$data['query'] = $this->Test_b2bmodel->master_fun_insert("sample_test_master", array("test_name" => $name,"thyrocare_code" => $thyrocare_code, "description" => $desc, "sample" => $sample));
				
				 $this->session->set_flashdata("success", array("Test successfully added."));
                redirect("b2b/test_master/test_list", "refresh");
				
			
        } else {
			
            $data['citys'] = $this->Test_b2bmodel->master_fun_get_tbl_val("test_cities", array("status" => '1'), array("name", "asc"));
            $data['departmnet_list'] = $this->Test_b2bmodel->master_fun_get_tbl_val("test_department_master", array("status" => '1'), array("name", "asc"));
			
			$this->load->view('b2b/header');
           $this->load->view('b2b/nav_logistic',$data);
           $this->load->view('b2b/testadd_views',$data);
           $this->load->view('b2b/footer');
        }
    
}
function test_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $cid = $this->uri->segment('4');
        $data['query'] = $this->user_model->master_fun_update("sample_test_master", array("id", $cid), array("status" => "0"));
        $this->session->set_flashdata("success", array("Test successfully deleted."));
        if (!empty($this->session->userdata("test_master_r"))) {
            redirect($this->session->userdata("test_master_r"), "refresh");
        } else {
            redirect("test-master/test-list", "refresh");
        }
    }

    function test_edit() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["cid"] = $this->uri->segment('4');
        $this->load->library('form_validation');
        
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
        //$this->form_validation->set_rules('price', "Price", 'trim|required|numeric');
        $this->form_validation->set_rules('desc', 'Description', 'trim');
		if ($this->form_validation->run() != FALSE) {
            
			$name = $this->input->post('name');
            //$price = $this->input->post('price');
			$thyrocare_code = $this->input->post('thyrocare_code');
		$sample = $this->input->post('sample');
                
    $desc = $this->input->post('desc');
            $this->Test_b2bmodel->master_fun_update("sample_test_master", array("id", $data["cid"]), array("test_name" => $name, "description" => $desc,"thyrocare_code"=>$thyrocare_code,"sample"=>$sample));
			
			$this->session->set_flashdata("success", array("Test successfully updated."));
            if (!empty($this->session->userdata("test_master_r"))) {
                redirect($this->session->userdata("test_master_r"), "refresh");
            } else {
                redirect("b2b/Test_master/test_list", "refresh");
            }
			
        } else {
			
            $data['departmnet_list'] = $this->Test_b2bmodel->master_fun_get_tbl_val("test_department_master", array("status" => '1'), array("name", "asc"));
            $data['query'] = $this->Test_b2bmodel->master_fun_get_tbl_val("sample_test_master", array("id" => $data["cid"]), array("id", "desc"));
            $data['city_price'] = $this->Test_b2bmodel->get_city_edit1($data["cid"]);
            $data['test_city'] = $this->Test_b2bmodel->master_fun_get_tbl_val("test_cities", array("status" => '1'), array("name", "asc"));
			
			$this->load->view('b2b/header');
           $this->load->view('b2b/nav_logistic',$data);
           $this->load->view('b2b/testeditb2b_views',$data);
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

        $delimiter = ",";
        $newline = "\r\n";
        $filename = "test.csv";
        $query = "SELECT  t.id,t.`test_name` as testname ,c.`name` as cityname,tc.`price` FROM  `sample_test_master` t  LEFT JOIN sample_test_city_price tc ON tc.test_fk=t.id  LEFT JOIN `test_cities` c ON tc.`city_fk`=c.`id` WHERE t.status = '1' ";
        $result = $this->db->query($query);
        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
        force_download($filename, $data);
    }

    function test_csv1() {
        if ($_GET["pw"] == 161616) {
            $search_data = array();
            $test_ids = array();
            $tests = $this->Test_b2bmodel->master_fun_get_tbl_val("sample_test_master", array("status" => '1'), array("test_name", "asc"));
            foreach ($tests as $key) {
                $t1 = $this->Test_b2bmodel->master_fun_get_tbl_val("test_master_city_price", array("status" => '1', "test_fk" => $key["id"], "city_fk" => "1"), array("id", "desc"));
                $t2 = $this->Test_b2bmodel->master_fun_get_tbl_val("test_master_city_price", array("status" => '1', "test_fk" => $key["id"], "city_fk" => "2"), array("id", "desc"));
                $t3 = $this->Test_b2bmodel->master_fun_get_tbl_val("test_master_city_price", array("status" => '1', "test_fk" => $key["id"], "city_fk" => "3"), array("id", "desc"));
                $t4 = $this->Test_b2bmodel->master_fun_get_tbl_val("test_master_city_price", array("status" => '1', "test_fk" => $key["id"], "city_fk" => "4"), array("id", "desc"));
                $t5 = $this->Test_b2bmodel->master_fun_get_tbl_val("test_master_city_price", array("status" => '1', "test_fk" => $key["id"], "city_fk" => "5"), array("id", "desc"));
                $t6 = $this->Test_b2bmodel->master_fun_get_tbl_val("test_master_city_price", array("status" => '1', "test_fk" => $key["id"], "city_fk" => "6"), array("id", "desc"));
                //if ($t1[0]["price"] == '' && $t2[0]["price"] == '' && $t3[0]["price"] == '' && $t4[0]["price"] == '' && $t5[0]["price"] == '' && $t6[0]["price"] == '') {
                $test_ids[] = $key["id"];
                $search_data[] = array(
                    "test_code" => $key["id"],
                    "test" => $key["test_name"],
                    "ahmedabad_price" => $t1[0]["price"],
                    "surat_price" => $t2[0]["price"],
                    "vadodra_price" => $t3[0]["price"],
                    "gurgaon_price" => $t4[0]["price"],
                    "delhi_price" => $t5[0]["price"],
                    "gandhinagar_price" => $t6[0]["price"],
                );
                // }
            }

            // echo implode(",",$test_ids); die();
            //$result = $this->job_model->csv_job_list1($search_data);
            //print_r($result); die();

            header("Content-type: application/csv");
            header("Content-Disposition: attachment; filename=\"All_Test_Report-" . date('d-M-Y') . ".csv\"");
            header("Pragma: no-cache");
            header("Expires: 0");
            $handle = fopen('php://output', 'w');
            fputcsv($handle, array(
                "Test Code.",
                "Test Name",
                "Ahmedabad Price",
                "Surat Price",
                "Vadodra Price",
                "Gurgaon Price",
                "Delhi Price",
                "Gandhinagar Price"
            ));

            foreach ($search_data as $key) {
                fputcsv($handle, array(
                    $key["test_code"],
                    $key["test"],
                    $key["ahmedabad_price"],
                    $key["surat_price"],
                    $key["vadodra_price"],
                    $key["gurgaon_price"],
                    $key["delhi_price"],
                    $key["gandhinagar_price"]
                ));
            }
            fclose($handle);
            exit;
        } else {
            echo show_error("Oops somthing wrong.");
        }
    }

   function importtest_csv() {
        if (!is_loggedin()) {
            redirect('login');
        }
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
                            $testrow = $this->Test_b2bmodel->num_row('sample_test_master', array("test_name" => $test_name, "status" => '1'));
                            if ($testrow == '0') {
                                $testinid = $this->Test_b2bmodel->master_fun_insert('sample_test_master', array("test_name" => $test_name, "status" => '1'));
                                $testrow = $this->Test_b2bmodel->num_row('test_cities', array("name" => $cityname, "status" => '1'));
                                if ($testrow != '0') {
                                    $citydeatils = $this->Test_b2bmodel->fetchdatarow('id', 'test_cities', array("name" => $cityname));
                                    $cityid = $citydeatils->id;
                                    $this->Test_b2bmodel->master_fun_insert('sample_test_city_price', array("test_fk" => $testinid, "city_fk" => $cityid));
                                }
                            } else {
                                $testdeatils = $this->Test_b2bmodel->fetchdatarow('id,test_name', 'sample_test_master', array("test_name" => $test_name, "status" => '1'));
                                $testid = $testdeatils->id;
                                $testrow = $this->Test_b2bmodel->num_row('sample_test_city_price', array("test_fk" => $testid, "status" => '1'));
                                if ($testrow == '0') {
                                    $testrow = $this->Test_b2bmodel->num_row('test_cities', array("name" => $cityname, "status" => '1'));
                                    if ($testrow != '0') {
                                        $citydeatils = $this->Test_b2bmodel->fetchdatarow('id', 'test_cities', array("name" => $cityname, "status" => '1'));
                                        $cityid = $citydeatils->id;
                                        $this->Test_b2bmodel->master_fun_insert('sample_test_city_price', array("test_fk" => $testid, "city_fk" => $cityid));
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
                        redirect("b2b/test_master/test-list", "refresh");
                    }
                } else {
                    $ses = array($cnt . "Test Added Successfully");
                    $this->session->set_flashdata('success', $ses);
                    if (!empty($this->session->userdata("test_master_r"))) {
                        redirect($this->session->userdata("test_master_r"), "refresh");
                    } else {
                        redirect("b2b/test_master/test_list", "refresh");
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
            $check = $this->Test_b2bmodel->master_fun_get_tbl_val("sub_test_master", array("test_fk" => $tid, "sub_test" => $test, "status" => "1"), array("id", "asc"));
            if (empty($check)) {
                $insert = $this->Test_b2bmodel->master_fun_insert('sub_test_master', $data1);
                $ses = array("Test Successfully Inserted!");
                $this->session->set_userdata('success', $ses);
            } else {
                $ses = array("Test already exists!");
                $this->session->set_userdata('unsuccess', $ses);
            }
            redirect('test_master/sub_test_add/' . $this->uri->segment(3));
        } else {
			
            $data['test'] = $this->Test_b2bmodel->master_fun_get_tbl_val("test_master", array("status" => 1), array("test_name", "desc"));
            $data['test_info'] = $this->Test_b2bmodel->master_fun_get_tbl_val("test_master", array("id" => $this->uri->segment(3)), array("id", "desc"));
            $data['sub_test'] = $this->Test_b2bmodel->get_val("SELECT `sub_test_master`.*,`test_master`.`test_name` FROM `sub_test_master` INNER JOIN `test_master` ON `sub_test_master`.`sub_test`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `sub_test_master`.`status`='1' AND `sub_test_master`.`test_fk`='" . $this->uri->segment(3) . "'");
            $data['citys'] = $this->Test_b2bmodel->master_fun_get_tbl_val("test_cities", array("status" => '1'), array("name", "asc"));
			
			$this->load->view('b2b/header');
           $this->load->view('b2b/nav_logistic',$data);
           $this->load->view('b2b/sub_test_add',$data);
           $this->load->view('b2b/footer');
        
		}
    
	}

    function sub_test_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $userid = $data["login_data"]["id"];

        $pid = $this->uri->segment(3);
        $tid = $this->uri->segment(4);
        $data = array(
            "status" => '0',
            "deleted_by" => $userid
        );
        $delete = $this->Test_b2bmodel->master_fun_update("sub_test_master", array("id", $this->uri->segment('4')), $data);
        if ($delete) {
            $ses = array("Test Successfully Deleted!");
            $this->session->set_userdata('success', $ses);
            redirect('test_master/sub_test_add/' . $pid, 'refresh');
        }
    }
    function change_department() {
        $tid = $this->input->post('tid');
        $did = $this->input->post('did');
        $data['query'] = $this->Test_b2bmodel->master_fun_update("sample_test_master", array("id",$tid), array("department" => $did));
    }

}

?>
