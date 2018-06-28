<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User_master extends CI_Controller {
    var $cash_back;
    function __construct() {
        parent::__construct();
        $this->load->model('home_model');
        $this->load->model('user_master_model');
        $this->load->model('user_wallet_model');
        $this->load->model('job_model');
        $this->load->model('register_model');
        $this->load->helper('string');
        $this->load->library('form_validation');
        $this->load->library('pushserver');
        $this->load->library('pagination');
        $this->load->library('email');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST');
        header("Access-Control-Allow-Headers: X-Requested-With");
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        if ($uid != 0) {
            $maxid = $this->user_wallet_model->total_wallet($uid);
            $data['total'] = $this->user_wallet_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "id" => $maxid), array("id", "asc"));
            if (!empty($data['total'])) {
                $this->data['wallet_amount'] = $data['total'][0]['total'];
            } else {
                $this->data['wallet_amount'] = 0;
            }
        }
        /* pinkesh code start */
        $data['links'] = $this->user_master_model->master_fun_get_tbl_val("patholab_home_master", array("status" => 1), array("id", "asc"));
        $this->data['all_links'] = $data['links'];
        /* pinkesh code end */
        $this->app_track();
        $offer_master = $this->user_master_model->master_fun_get_tbl_val("offer_master", array("status" => "1"), array("id", "asc"));
        $this->cash_back = $offer_master;
    }
	function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }

    function index() {
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data["test_city_session"] = $this->session->userdata("test_city");
        if ($data["test_city_session"] == '') {
            $data["test_city_session"] = 1;
            $this->session->set_userdata("test_city", "1");
        }
        $data["login_data"] = loginuser();
        $data["user"] = $this->user_master_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $data['unsuccess'] = $this->session->flashdata("unsuccess");
        $data['payment_success'] = $this->session->flashdata("payment_success");
        $data['payment_unsuccess'] = $this->session->flashdata("payment_unsuccess");
        $data['package_cat'] = $this->user_master_model->master_fun_get_tbl_val("package_category", array("status" => '1'), array("name", "asc"));
        $data['health_feed'] = $this->user_master_model->master_fun_get_tbl_val("health_feed_master", array("status" => 1), array("title", "asc"));
        $data['set_setting'] = $this->user_master_model->master_fun_get_tbl_val("patholab_home_master", array("status" => 1), array("id", "asc"));
        $data['testimonial'] = $this->user_master_model->master_fun_get_tbl_val("testimonials_master", array("status" => 1), array("id", "asc"));
        $data['test_cities'] = $this->user_master_model->master_fun_get_tbl_val("test_cities", array("status" => 1), array("name", "asc"));
        $data['active_class'] = "home";
        $this->load->view('user/header', $data);
        $this->load->view('user/home', $data);
        $this->load->view('user/footer');
    }

    function index_new() {
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data["test_city_session"] = $this->session->userdata("test_city");
        if ($data["test_city_session"] == '') {
            $data["test_city_session"] = 1;
            $this->session->set_userdata("test_city", "1");
        }
        $data["login_data"] = loginuser();
        $data["user"] = $this->user_master_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $data['payment_success'] = $this->session->flashdata("payment_success");
        $data['payment_unsuccess'] = $this->session->flashdata("payment_unsuccess");
        //$data['package'] = $this->user_master_model->master_fun_get_tbl_val("package_master", array("status" => 1), array("title", "asc"));
        $data['health_feed'] = $this->user_master_model->master_fun_get_tbl_val("health_feed_master", array("status" => 1), array("title", "asc"));
        $data['set_setting'] = $this->user_master_model->master_fun_get_tbl_val("patholab_home_master", array("status" => 1), array("id", "asc"));
        $data['testimonial'] = $this->user_master_model->master_fun_get_tbl_val("testimonials_master", array("status" => 1), array("id", "asc"));
        $data['test_cities'] = $this->user_master_model->master_fun_get_tbl_val("test_cities", array("status" => 1), array("name", "asc"));
        $data['active_class'] = "home";
        $this->load->view('user/header1', $data);
        $this->load->view('user/home1', $data);
        $this->load->view('user/footer');
    }

    function package_list() {
        $city = $this->input->get_post("city");
        $this->session->set_userdata("test_city", $city);
        /* Nishit code start */
        $data = $this->user_master_model->master_fun_get_tbl_val("package_master", array("status" => 1, "is_view" => "1"), array("order", "asc"));
        $cnt = 0;
        foreach ($data as $key) {
            if ($cnt < 6) {
                $pkg_price = $this->user_master_model->master_fun_get_tbl_val("package_master_city_price", array("status" => "1", "package_fk" => $key["id"], "city_fk" => $city), array("id", "asc"));
                $output_data = '<a href="' . base_url() . 'user_master/package_details/' . $key['id'] . '"> <div class="col-md-4 col-sm-4 pdng_0">
                                <div class="box-package">
                                <div class="red-label"><p>Rs.' . $pkg_price[0]["d_price"] . '</p></div>
                                    <div class="img-box">
                                        <img src="' . base_url() . 'upload/package/' . $key['image'] . '">
                                    </div>
                                    <div class="txt-box"> 
                                        <h2 style="text-transform:uppercase;">' . ucfirst($key['title']) . '</h2>
                                        <!--<p>Your text here </p>-->
                                    </div>
                                    </div>
                                </div>
                            </div>
                          </a>';
                $cnt++;
                echo $output_data;
            }
        }
    }

    function package_list1() {
        $city = $this->input->get_post("city");
        $data["test_city_session"] = $this->session->userdata("test_city");
        if ($data["test_city_session"] == '') {
            $data["test_city_session"] = 1;
        }
        $this->session->set_userdata("test_city", $city);
        /* Nishit code start */
        $data["data"] = $this->user_master_model->master_fun_get_tbl_val("package_master", array("status" => 1), array("title", "asc"));
        $data['test'] = $this->user_master_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND test_master.is_view='1' AND `test_master_city_price`.`city_fk`='" . $city . "'");
        $data['package'] = $this->user_master_model->master_fun_get_tbl_val("package_master", array("status" => 1, "is_view" => "1"), array("title", "asc"));
        $data['popular'] = $this->user_master_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1'AND test_master.is_view='1' AND `test_master_city_price`.`city_fk`='" . $data["test_city_session"] . "' AND test_master.`popular`='1'");
        /* 25-10 */
        $this->load->view("user/HomePageSearch", $data);
        /* 25-10 */
    }

    function searching_test() {
        $ids = $this->input->post('id');
        $names = $this->input->post('name');
        $all = $this->input->post('all');
        $this->session->set_userdata('search_test_id', $ids);
        $this->session->set_userdata('search_test_name', $names);
        $this->session->set_userdata('search_all', $all);
    }

    function prescription_book_test() {
        $ids = $this->input->post('id');
        $t_id = array();
        $t_name = array();
        foreach ($ids as $key) {
            $t_info = explode("#@t@#", $key);
            $t_id[] = $t_info[0];
            $t_name[] = $t_info[1];
        }
        //$names = $this->input->post('name');
        $this->session->set_userdata('search_test_id', $t_id);
        $this->session->set_userdata('search_test_name', $t_name);
        redirect("user_master/order_search");
    }

    function order_search() {
        $data["login_data"] = loginuser();
        $data['uid'] = $data["login_data"]['id'];
        $data["user"] = $this->user_master_model->getUser($data["login_data"]["id"]);
        $data["user"] = $this->user_master_model->getUser($data["login_data"]["id"]);
        $data["test_ids"] = $this->session->userdata("search_test_id");
        $data["test_city_session"] = $this->session->userdata("test_city");
        if ($data["test_city_session"] == '') {
            $data["test_city_session"] = 1;
            $this->session->set_userdata("test_city", "1");
        }
        $data["test_names"] = $this->session->userdata("search_test_name");
        $data["test_city_name"] = $this->user_master_model->master_fun_get_tbl_val("test_cities", array("id" => $data["test_city_session"]), array("id", "asc"));
        $price = 0;
        //print_r($data["test_ids"]);
        $cnt = 0;
        $data["test_city_session"] = $this->session->userdata("test_city");
        foreach ($data["test_ids"] as $key) {
            $ex = explode('-', $key);
            $first_pos = $ex[0];
            $id = $ex[1];
            if ($first_pos == "t") {
                $result = $this->user_master_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`fasting_requird`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master`.`id`='" . $id . "' AND test_master_city_price.city_fk='" . $data["test_city_session"] . "'");
                $price += $result[0]["price"];
            } else {
                $query = $this->db->get_where('package_master_city_price', array('package_fk' => $id, "status" => "1", "city_fk" => $data["test_city_session"]));
                $result = $query->result();
                $price += $result[0]->d_price;
            }
            $cnt++;
        }
        $data['all_search'] = $this->session->userdata('all');
        $data["package"] = $this->user_master_model->get_val("SELECT 
    `package_master`.*,
    `package_master_city_price`.`a_price` AS `a_price1`,
    `package_master_city_price`.`d_price` AS `d_price1`
  FROM
    `package_master` 
    INNER JOIN `package_master_city_price` 
      ON `package_master`.`id` = `package_master_city_price`.`package_fk` 
  WHERE `package_master`.`status` = '1' 
    AND `package_master_city_price`.`status` = '1' AND `package_master`.is_view='1' AND `package_master_city_price`.`city_fk` = '" . $data["test_city_session"] . "' ");
        $data['relation_list'] = $this->user_master_model->get_val("SELECT 
  `customer_family_master`.*,
  `relation_master`.`name` AS relation_name 
FROM
  `customer_family_master` 
  INNER JOIN `relation_master` 
    ON `customer_family_master`.`relation_fk` = `relation_master`.`id` 
WHERE `customer_family_master`.`status` = '1' 
  AND `relation_master`.`status` = '1' 
  AND `customer_family_master`.`user_fk` = '" . $data['uid'] . "'");
        $data['job_address_list'] = $this->user_master_model->master_fun_get_tbl_val("job_master", array("status" => 1, "address !=" => "", "cust_fk" => $data['uid']), array("address", "asc"));
        $data['customer_info'] = $this->user_master_model->master_fun_get_tbl_val("customer_master", array("id" => $data['uid']), array("address", "asc"));
        $data['relation'] = $this->user_master_model->master_fun_get_tbl_val("relation_master", array("status" => "1"), array("name", "asc"));
        $data['test'] = $this->user_master_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`fasting_requird`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master`.is_view='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $data["test_city_session"] . "'");
        $data['total_price'] = $price;
        $this->load->view('user/header', $data);
        $this->load->view('user/search_after', $data);
        $this->load->view('user/footer');
    }

    function send_serch_test_email() {
        /* pinkesh code start */
        $this->load->model('user_login_model');
        $this->load->model('register_model');
        $mobile = $this->input->post('mobile');
        $email = $this->input->post('email');
        $address = $this->input->post('address');
        $login_pass = $this->input->post('login_pass');
        $reg_name = $this->input->post('reg_name');
        $reg_pass = $this->input->post('reg_pass');
        $reg_gender = $this->input->post('reg_gender');
        $testids = $this->input->post('testid');
        $count = $this->user_master_model->num_row('customer_master', array("email" => $email, "status" => '1'));
        if ($count != 0) {
            $result = $this->user_login_model->checklogin($email, $login_pass);
            if ($result) {
                $sess_array = array();
                foreach ($result as $row) {
                    $sess_array = array(
                        'id' => $row->id,
                        'name' => $row->full_name,
                        'type' => $row->type,
                    );
                    $this->session->set_userdata('logged_in_user', $sess_array);
                }
            }
        } else {

            $insert = $this->register_model->master_fun_insert("customer_master", array("full_name" => $reg_name, "gender" => $reg_gender, "email" => $email, "password" => $reg_pass, "mobile" => $mobile, "active" => 1, "address" => $address));
            if ($insert) {
                $result = $this->user_login_model->checklogin($email, $login_pass);
                if ($result) {
                    $sess_array = array();
                    foreach ($result as $row) {
                        $sess_array = array(
                            'id' => $row->id,
                            'name' => $row->full_name,
                            'type' => $row->type,
                        );
                        $this->session->set_userdata('logged_in_user', $sess_array);
                    }
                }
            }
        }
        $data["login_data"] = loginuser();
        $data["user"] = $this->user_master_model->getUser($data["login_data"]["id"]);
        $id = $data["login_data"]["id"];
        redirect("user_test_master/book_test/" . $testids, "refresh");
    }

    function home_upload_prescription() {
        /* pinkesh code start */
        $this->load->helper("Email");
        $email_cnt = new Email;
        $this->load->model('user_login_model');
        $this->load->model('register_model');
        $mobile = $this->input->post('mobile');
        $city = $this->input->post('city');
        $city = $this->session->userdata("test_city");
        if ($city == '') {
            $city = 1;
            $this->session->set_userdata("test_city", "1");
        }
        $email = $this->input->post('email');
        $desc = $this->input->post('desc');
        $login_pass = $this->input->post('login_pass');
        $reg_name = $this->input->post('reg_name');
        $reg_pass = $this->input->post('reg_pass');
        $reg_gender = $this->input->post('reg_gender');
        $captcha = $this->varify_captcha();
        if ($mobile != '' && $desc != '' && $captcha == 1) {
            $count = $this->user_master_model->num_row('customer_master', array("email" => $email, "status" => '1'));
            $check = $this->user_master_model->num_row('customer_master', array("mobile" => $mobile, "status" => '1', "active" => '1'));
            if ($check == 0) {
                $data["login_data"] = loginuser();
                $data["user"] = $this->user_master_model->getUser($data["login_data"]["id"]);
                $id = $data["login_data"]["id"];
                $name = $data['user']->full_name;
            } else {
                $user_dtls = $this->user_master_model->master_fun_get_tbl_val('customer_master', array("mobile" => $mobile, "status" => '1', "active" => '1'), array("id", "asc"));
                $id = $user_dtls[0]['id'];
                $name = $user_dtls[0]['full_name'];
            }
            //$email=$data['user']->email;
            $files = $_FILES;
            $data = array();
            $this->load->library('upload');
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            if ($files['userfile']['name'] != "") {
                $config['upload_path'] = './upload/';
                $config['file_name'] = $files['userfile']['name'];
                $this->upload->initialize($config);
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0755, TRUE);
                }
                if (!$this->upload->do_upload('userfile')) {
                    $error = $this->upload->display_errors();
                    $error = str_replace("<p>", "", $error);
                    $error = str_replace("</p>", "", $error);
                    $this->session->set_flashdata("unsuccess", array($error));
                    redirect('/');
                } else {
                    $doc_data = $this->upload->data();
                    $filename = $doc_data['file_name'];
                    $order_id = random_string('numeric', 13);
                    $date = date('Y-m-d H:i:s');
                    $insert = $this->user_master_model->master_fun_insert("prescription_upload", array("cust_fk" => $id, "image" => $filename, "description" => $desc, "mobile" => $mobile, "created_date" => $date, "order_id" => $order_id, "city" => $city));
                    $insert12 = $this->user_master_model->master_fun_insert("notification_master", array("title" => "Prescription Upload Successfully.", "url" => "user_master", "user_fk" => $id, "status" => '1'));
                    $sms_message = $this->job_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "upload_presc"), array("id", "asc"));
                    $sms_message = $sms_message[0]["message"];
                    $this->load->helper("sms");
                    $notification = new Sms();
                    $notification->send($mobile, $sms_message);
                    //$configmobile = '9725567516';
                    $mb_length = strlen($configmobile);
                    $sms_message = $this->job_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "prescription_info"), array("id", "asc"));

                    $sms_message = $sms_message[0]["message"];
                    if ($name == "") {

                        $sms_message = str_replace("{{NAME}} ({{MOBILE}})", $mobile, $sms_message);
                    } else {
                        $sms_message = preg_replace("/{{NAME}}/", ucfirst($name), $sms_message);
                        $sms_message = preg_replace("/{{MOBILE}}/", ucfirst($mobile), $sms_message);
                    }


                    $configmobile = $this->config->item('admin_alert_phone');
                    foreach ($configmobile as $p_key) {
                        //$notification::send($configmobile, $sms_message);
                        $this->job_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $p_key, "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
                    }

                    //vishal
                    /* pinkesh code end */
                    $config['mailtype'] = 'html';
                    $this->email->initialize($config);
                    $message = '<div style="padding:0 4%;">
                    <h4 style="color:#67B1A3;text-decoration: underline;">Contact Detail</h4>
                        <p style="color:#7e7e7e;font-size:13px;font-weight: bold;">Follow information shows Contact Person Detail :</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Customer Name : </b> ' . ucfirst($name) . '</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Email : </b> ' . $email . '</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Mobile : </b> ' . $mobile . '</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Description : </b> ' . ucfirst($desc) . '</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Uploaded file : </b> ' . base_url() . 'upload/' . $filename . '</p>
                </div>';
                    $message = $email_cnt->get_design($message);
                    $pathToUploadedFile = base_url() . 'upload/' . $filename;
                    $this->email->to($this->config->item('admin_booking_email'));
                    $this->email->from("donotreply@airmedpathlabs.com", "AirmedLabs");
                    $this->email->subject("New Prescription Uploaded");
                    $this->email->message($message);
                    $this->email->attach($pathToUploadedFile);
                    $this->email->send();
                    $this->session->set_flashdata("payment_success", array("Thank You ! we will analysis and create Test list for you"));
                    redirect('user_master');
                }
            }
        } else {
            $this->session->set_flashdata("payment_unsuccess", array("Invalid request try again."));
            redirect('user_master');
        }
    }

    function package_details($id) {
        //echo "here"; die();
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data["success"] = $this->session->flashdata("success");
        $data["login_data"] = loginuser();
        /* Nishit code start */
        $data["test_city_session"] = $this->session->userdata("test_city");
        $query = $this->db->get_where('package_master_city_price', array('package_fk' => $id, "city_fk" => $data["test_city_session"], "status" => 1));
        $result = $query->result();
        $data["d_price"] = $result[0]->d_price;
        $data["a_price"] = $result[0]->a_price;
        /* Nishit code end */
        $data['package'] = $this->user_master_model->master_fun_get_tbl_val("package_master", array("status" => 1, 'id' => $id), array("title", "asc"));
        $data['active_class'] = "home";
        $data['pid'] = $id;

        /* Show package start */
        $city = $this->session->userdata("test_city");
        $this->session->set_userdata("test_city", $city);
        /* Nishit code start */
        $data1 = $this->user_master_model->master_fun_get_tbl_val("package_master", array("status" => 1), array("order", "asc"));
        $cnt = 0;
        $package_array = array();
        foreach ($data1 as $key) {
            if ($cnt < 6 && $id != $key["id"]) {
                $pkg_price = $this->user_master_model->master_fun_get_tbl_val("package_master_city_price", array("status" => "1", "package_fk" => $key["id"], "city_fk" => $city), array("id", "asc"));
                $package_array[] = array($key, $pkg_price);
                $cnt++;
            }
        }
        $data["package_array"] = $package_array;
        /* Show package end */

        $this->load->view('user/header', $data);
        $this->load->view('user/package_details', $data);
        $this->load->view('user/footer');
    }

    function edit_profile() {
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
        $data["user"] = $this->user_master_model->getUser($data["login_data"]["id"]);
        $countryid = $data["user"]->country;
        $stateid = $data["user"]->state;
        $id = $data["login_data"]["id"];
        $data['type'] = $data["login_data"]["type"];
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('state', 'state', 'trim|required');
        $this->form_validation->set_rules('birth_date', 'Date of birth', 'trim|required');
        $this->form_validation->set_rules('gender', 'Gender', 'trim|required');
        $this->form_validation->set_rules('city', 'city', 'trim|required');

        if ($this->form_validation->run() != FALSE) {
            $name = $this->input->post('name');
            $email = $this->input->post('email');
            $birth_date = $this->input->post('birth_date');
            $address = $this->input->post('address');
            $gender = $this->input->post('gender');

            $state = $this->input->post('state');
            $city = $this->input->post('city');
            //print_r($this->input->post()); die();
            $data1 = array(
                "full_name" => $name,
                "email" => $email,
                "address" => $address,
                "gender" => $gender,
                "state" => $state,
                "city" => $city,
                "dob" => $birth_date
            );
            //print_R($data1); die();
            $files = $_FILES;
            $data = array();
            $this->load->library('upload');
            $config['allowed_types'] = 'png|jpg|gif|jpeg';
            if (isset($files['userfile']) && $files['userfile']['name'] != "") {
                $config['upload_path'] = './upload/';
                $config['file_name'] = $files['userfile']['name'];
                $this->upload->initialize($config);
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0755, TRUE);
                }
                if (!$this->upload->do_upload('userfile')) {
                    $error = $this->upload->display_errors();
                    $error = str_replace("<p>", "", $error);
                    $error = str_replace("</p>", "", $error);
                    $this->session->set_flashdata("error", array($error));
                    redirect("user_master/edit_profile", "refresh");
                } else {
                    $doc_data = $this->upload->data();
                    $filename = $doc_data['file_name'];
                    $data1['pic'] = $filename;
                }
            }
            $update = $this->user_master_model->master_fun_update("customer_master", array("id", $id), $data1);

            $this->session->set_flashdata("payment_success", array("Profile Updated Successfully."));
            redirect("user_master", "refresh");
        } else {
            $data['country'] = $this->user_master_model->master_fun_get_tbl_val("country", array("status" => 1), array("country_name", "asc"));
            $data["id"] = $id;
            $data['state'] = $this->user_master_model->master_fun_get_tbl_val("state", array("status" => 1), array("state_name", "asc"));
            $data['city'] = $this->user_master_model->master_fun_get_tbl_val("city", array("state_fk" => $stateid, "status" => 1), array("city_name", "asc"));
            $data['country'] = $this->user_master_model->master_fun_get_tbl_val("country", array("status" => 1), array("country_name", "asc"));
            $data['success'] = $this->session->flashdata("success");
            $data['error'] = $this->session->flashdata("error");
            $this->load->view('user/header', $data);
            $this->load->view('user/edit_profile', $data);
            $this->load->view('user/footer');
        }
    }

    function get_state($cid) {
        $data = $this->user_master_model->master_fun_get_tbl_val("state", array("country_fk" => $cid, "status" => 1), array("state_name", "asc"));
        echo "<select name='state' class='input-group select_style' onchange='get_city(this.value)'>";
        echo "<option value=''> Select State</option>";
        foreach ($data as $key) {
            $state = ucfirst($key['state_name']);
            $value = $key['id'];
            echo "<option value='$value'> $state </option>";
        }
        echo "</select>";
    }

    function get_city($cid) {
        $data = $this->user_master_model->master_fun_get_tbl_val("city", array("state_fk" => $cid, "status" => 1), array("city_name", "asc"));
        echo "<select name='city' class='input-group select_style'>";
        echo "<option value=''> Select city</option>";
        foreach ($data as $key) {
            $state = ucfirst($key['city_name']);
            $value = $key['id'];
            echo "<option value='$value'> $state </option>";
        }
        echo "</select>";
    }

    function change_password() {
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $this->load->helper("Email");
        $email_cnt = new Email;
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        $this->load->library('form_validation');
        $this->form_validation->set_rules('password', 'Old Password', 'trim|required');
        $this->form_validation->set_rules('newpassword', 'New password', 'trim|required|matches[cpassword]|min_length[6]');
        $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required');

        if ($this->form_validation->run() != FALSE) {
            $password = $this->input->post('newpassword');
            $oldpassword = $this->input->post('password');
            $data = $this->user_master_model->master_fun_get_tbl_val("customer_master", array("id" => $data["login_data"]['id'], "status" => 1), array("id", "asc"));
            $oldpass = $data[0]['password'];
            if ($oldpass == $oldpassword) {
                $update = $this->user_master_model->master_fun_update("customer_master", array("id", $uid), array("password" => $password));
                if ($update) {
                    $insert = $this->user_master_model->master_fun_insert("notification_master", array("title" => "Password Change Successfully", "url" => "user_login", "user_fk" => $uid, "status" => '1'));
                    $config['mailtype'] = 'html';
                    $this->email->initialize($config);
                    $message = '<div style="padding:0 4%;">
                    <h4><b>Dear</b> ' . $data[0]['full_name'] . '</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Password Successfully Change. </p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
                    $message = $email_cnt->get_design($message);
                    $this->email->to($data[0]['email']);
                    $this->email->from($this->config->item('admin_booking_email'), 'AirmedLabs');
                    $this->email->subject("Password changed for AirmedLabs");
                    $this->email->message($message);
                    $this->email->send();
                    $this->session->set_flashdata("payment_success", array("Password Changed Successfully"));
                    redirect("user_master", "refresh");
                }
            } else {

                $this->session->set_flashdata("error", array("Old Password Not Matach"));
                redirect("user_master/change_password", "refresh");
            }
        } else {
            $data['success'] = $this->session->flashdata("success");
            $data['error'] = $this->session->flashdata("error");
            $this->load->view('user/header', $data);

            $this->load->view('user/change_password', $data);
            $this->load->view('user/footer');
        }
    }

    function my_job() {
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data["success"] = $this->session->flashdata("success");
        $data["count_com"] = $this->user_master_model->get_completed_job($uid);
        $totalRows = count($data["count_com"]);
        $config1 = array();
        $config1["base_url"] = base_url() . "user_master/my_job/";
        $config1["total_rows"] = $totalRows;
        $config1["per_page"] = 10;
        $config1["uri_segment"] = 3;
        $this->pagination->initialize($config1);
        $sort = $this->input->get("sort");
        $by = $this->input->get("by");
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data1 = $this->user_master_model->get_completed_job1($uid, $config1["per_page"], $page);
        $newdata = array();
        foreach ($data1 as $key) {
            $key['test'] = (($key['test'] == "") ? "" : $key['test'] . ',' ) . $key['packge_name'];
            $newdata[] = $key;
        }
        $data['completed'] = $newdata;
        $data["links1"] = $this->pagination->create_links($config1);
        $data['active_class'] = "myjob";
        $data['cancle'] = $this->user_master_model->cancle_job($uid);
        $data['report'] = $this->user_master_model->master_fun_get_tbl_val("report_master", array("status" => 1), array("id", "desc"));
        $this->load->view('user/header', $data);
        $this->load->view('user/my_job', $data);
        $this->load->view('user/footer');
    }

    function my_job_pending() {
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data["success"] = $this->session->flashdata("success");
        $data['active_class'] = "myjob";
        $data["count_pen"] = $this->user_master_model->get_pending_job($uid);
        $totalRows = count($data["count_pen"]);
        $config = array();
        $config["base_url"] = base_url() . "user_master/my_job_pending/";
        $config["total_rows"] = $totalRows;
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $sort = $this->input->get("sort");
        $by = $this->input->get("by");
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data1 = $this->user_master_model->get_pending_job1($uid, $config["per_page"], $page);
        $newdata = array();
        foreach ($data1 as $key) {
            $key['test'] = (($key['test'] == "") ? "" : $key['test'] . ',' ) . $key['packge_name'];
            $newdata[] = $key;
        }
        $data["pending"] = $newdata;
        $data["links2"] = $this->pagination->create_links($config);
        $this->load->view('user/header', $data);
        $this->load->view('user/my_job_pending', $data);
        $this->load->view('user/footer');
    }

    function cancle_job($job_fk) {
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        $date = date('d/m/Y');
        $update = $this->user_master_model->master_fun_update("job_master", array("id", $job_fk), array("status" => 4));

        if ($update) {
            $query = $this->user_master_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $uid), array("id", "desc"));
            $total = $query[0]['total'];
            $query1 = $this->user_master_model->master_fun_get_tbl_val("job_master", array("id" => $job_fk), array("id", "desc"));
            //print_r($query1); die();
            $amount = $query1[0]['price'];

            $data = array(
                "cust_fk" => $uid,
                "credit" => $amount,
                "total" => $total + $amount,
                "job_fk" => $job_fk,
                "created_time" => date('Y-m-d H:i:s')
            );
            $insert = $this->user_master_model->master_fun_insert("wallet_master", $data);
            $this->session->set_flashdata("success", array("Job Request Cancled Successfully..Your Payment has been Credited In your Wallet"));
            redirect("user_master/my_job", "refresh");
        }
    }

    function upload_prescription() {
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        $this->load->library('form_validation');
        $this->form_validation->set_rules('desc', 'Description', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $desc = $this->input->post('desc');
            $order_id = random_string('numeric', 13);
            $date = date('Y-m-d H:i:s');

            $data1 = array("cust_fk" => $uid, "description" => $desc, "created_date" => $date, "order_id" => $order_id);
            $files = $_FILES;
            $data = array();
            $this->load->library('upload');
            $config['allowed_types'] = 'png|jpg|gif|jpeg';
            if (isset($files['userfile']) && $files['userfile']['name'] != "") {
                $config['upload_path'] = './upload/';
                $config['file_name'] = $files['userfile']['name'];
                $this->upload->initialize($config);
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0755, TRUE);
                }
                if (!$this->upload->do_upload('userfile')) {
                    $error = $this->upload->display_errors();
                    $error = str_replace("<p>", "", $error);
                    $error = str_replace("</p>", "", $error);
                    $this->session->set_flashdata("error", array($error));
                    redirect("user_master/upload_prescription", "refresh");
                } else {
                    $doc_data = $this->upload->data();
                    $filename = $doc_data['file_name'];
                    $data1['image'] = $filename;
                }
            } else {
                $this->session->set_flashdata("error", array("Please Choose any Image"));
                redirect("user_master/upload_prescription", "refresh");
            }
            $insert = $this->user_master_model->master_fun_insert("prescription_upload", $data1);
            $this->session->set_flashdata("success", array("Thank You ! we will analysis and create Test list for you"));
            redirect("user_master/upload_prescription", "refresh");
        } else {
            $data['success'] = $this->session->flashdata("success");
            $data['error'] = $this->session->flashdata("error");
            $data['active_class'] = "upload_prescription";
            $data['query'] = $this->user_master_model->master_fun_get_tbl_val("prescription_upload", array("status !=" => 0, "cust_fk" => $uid), array("id", "desc"));
            $totalRows = count($data["query"]);
            $config = array();
            $config["base_url"] = base_url() . "user_master/upload_prescription/";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 10;
            $config["uri_segment"] = 3;
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $data["prescription"] = $this->user_master_model->get_prescription($uid, $config["per_page"], $page);
            /* $cnt = 0;
              foreach ($data["prescription"] as $j_key) {
              if (!empty($j_key["job_fk"])) {
              $j_details = $this->user_master_model->master_fun_get_tbl_val("job_master", array("status !=" => 0, "id" => $j_key["job_fk"]), array("id", "desc"));
              $data["prescription"][$cnt]["order_id"] = $j_details[0]["order_id"];
              }
              $cnt++;
              } */
            $data["links"] = $this->pagination->create_links();
            $this->load->view('user/header', $data);
            $this->load->view('user/prescription_upload', $data);
            $this->load->view('user/footer');
        }
    }

    function suggested_test($pid) {
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
        $data["test_city_session"] = $this->session->userdata("test_city");
        $uid = $data["login_data"]['id'];
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data["success"] = $this->session->flashdata("success");
        $data['prescription'] = $this->user_master_model->master_fun_get_tbl_val("prescription_upload", array("status !=" => 0, "id" => $pid), array("id", "desc"));
        $data['test'] = $this->user_master_model->suggest_test($pid, $data["test_city_session"]);
        /* $cnt = 0;
          foreach ($data["prescription"] as $j_key) {
          if (!empty($j_key["job_fk"])) {
          $j_details = $this->user_master_model->master_fun_get_tbl_val("job_master", array("status !=" => 0, "id" => $j_key["job_fk"]), array("id", "desc"));
          $data["prescription"][$cnt]["order_id"] = $j_details[0]["order_id"];
          }
          $cnt++;
          } */
        $this->load->view('user/header', $data);
        $this->load->view('user/suggested_test', $data);
        $this->load->view('user/footer');
    }

    function submit_issue() {
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $this->load->helper("Email");
        $email_cnt = new Email;

        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        $this->load->library('form_validation');
        $this->form_validation->set_rules('subject', 'subject', 'trim|required');
        $this->form_validation->set_rules('message', 'Message', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $subject = $this->input->post('subject');
            $message1 = $this->input->post('message');
            $data = $this->user_master_model->master_fun_get_tbl_val("customer_master", array("status" => 1, 'id' => $uid), array("id", "asc"));
            $email = $data[0]['email'];
            $name = $data[0]['full_name'];
            $data1 = array("cust_fk" => $uid, "subject" => $subject, "massage" => $message1, "created_date" => date('d/m/Y'));
            $insert = $this->user_master_model->master_fun_insert("issue_master", $data1);
            $config['mailtype'] = 'html';
            $this->email->initialize($config);
            $message = '<div style="padding:0 4%;">
                    <h4 style="color:#67B1A3;text-decoration: underline;">Confirm Your Email</h4>
                        <p style="color:#7e7e7e;font-size:13px;font-weight: bold;">>Hello ,' . $message1 . ',</p>
                   
                </div>';
            $message = $email_cnt->get_design($message);
            $this->email->to($this->config->item('admin_booking_email'));
            $this->email->from('donotreply@airmedpathlabs.com', "AirmedLabs");
            $this->email->subject($subject);
            $this->email->message($message);
            $this->email->send();
            $this->session->set_flashdata("success", array("Thank You ! Your Query Submited Successfully"));
            redirect("user_master/submit_issue", "refresh");
        } else {
            $data['success'] = $this->session->flashdata("success");
            $data['error'] = $this->session->flashdata("error");
            $this->load->view('user/header', $data);
            $this->load->view('user/submit_issue', $data);
            $this->load->view('user/footer');
        }
    }

    function view_report($job_fk) {
        //echo "here"; die();
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data["success"] = $this->session->flashdata("success");
        $data['report'] = $this->user_master_model->view_test_report($job_fk);
        $data['job'] = $this->user_master_model->detail_job($uid, $job_fk);
        $this->load->view('user/header', $data);
        $this->load->view('user/view_report', $data);
        $this->load->view('user/footer');
    }

    function payment_history() {
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data["success"] = $this->session->flashdata("success");
        $data['history'] = $this->user_master_model->get_payment_history($uid);
        $data['query'] = $this->user_master_model->get_payment_history($uid);
        $totalRows = count($data["query"]);
        $config = array();
        $config["base_url"] = base_url() . "user_master/payment_history/";
        $config["total_rows"] = $totalRows;
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $sort = $this->input->get("sort");
        $by = $this->input->get("by");
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["prescription"] = $this->user_master_model->get_payment_history1($uid, $config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();
        $data['active_class'] = "payment_history";
        $this->load->view('user/header', $data);
        $this->load->view('user/payment_history', $data);
        $this->load->view('user/footer');
    }

    function faq() {
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $this->load->view('user/header', $data);
        $this->load->view('user/faq', $data);
        $this->load->view('user/footer');
    }

    function terms_condition() {
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $this->load->view('user/header', $data);
        $this->load->view('user/terms_condition', $data);
        $this->load->view('user/footer');
    }

    function privacy_policy() {
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $this->load->view('user/header', $data);
        $this->load->view('user/privacy_policy', $data);
        $this->load->view('user/footer');
    }

    function collection() {
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data['active_class'] = "collection";
        $this->load->view('user/header', $data);
        $this->load->view('user/collection', $data);
        $this->load->view('user/footer');
    }

    function contact_us() {

        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data['active_class'] = "contact";
        $this->load->view('user/header', $data);
        $this->load->view('user/contact_us', $data);
        $this->load->view('user/footer');
    }

    function partner_with_us() {
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data['active_class'] = "partner";
        $this->load->view('user/header', $data);
        $this->load->view('user/partner_with_us', $data);
        $this->load->view('user/footer');
    }

    function varify_captcha() {
        $recaptchaResponse = trim($this->input->get_post('g-recaptcha-response'));
        $google_url = "https://www.google.com/recaptcha/api/siteverify";
        $secret = '6Ld5_x8UAAAAAGn_AV4406lg29xu2hpQQJMaD2BC';
        $ip = $_SERVER['REMOTE_ADDR'];
        $url = $google_url . "?secret=" . $secret . "&response=" . $recaptchaResponse . "&remoteip=" . $ip;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
        $res = curl_exec($curl);
        curl_close($curl);
        $res = json_decode($res, true);
        //var_dump($res);
        if ($res['success'] == true) {
            return 1;
        } else {
            return 0;
        }
    }

    function contact_send_mail() {
        $this->load->helper("Email");
        $email_cnt = new Email;
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required|numeric');
        $this->form_validation->set_rules('subject', 'Subject', 'trim|required');
        $this->form_validation->set_rules('message', 'Message', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $captch = $this->varify_captcha();
            if ($captch == 1) {
                $name = $this->input->post('name');
                $email = $this->input->post('email');
                $mobile = $this->input->post('phone');
                $subject = $this->input->post('subject');
                $message1 = $this->input->post('message');
                $config['mailtype'] = 'html';
                $this->email->initialize($config);
                $data = array(
                    "name" => $name,
                    "email" => $email,
                    "subject" => $subject,
                    "phone" => $mobile,
                    "message" => $message1
                );
                $this->register_model->master_fun_insert("contact_us", $data);
                $message = '<div style="padding:0 4%;">
                    <h4 style="color:#67B1A3;text-decoration: underline;">Contact Detail</h4>
                        <p style="color:#7e7e7e;font-size:13px;font-weight: bold;">Follow information shows Contact Person Detail :</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Name : </b> ' . ucfirst($name) . '</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Email : </b> ' . $email . '</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Phone : </b> ' . $mobile . '</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Subject : </b> ' . ucfirst($subject) . '</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Message : </b> ' . ucfirst($message1) . '</p>
                </div>';
                $message = $email_cnt->get_design($message);
                $this->email->to($this->config->item('admin_booking_email'));
                $this->email->from("donotreply@airmedpathlabs.com", 'AirmedLabs');
                $this->email->subject("Contact From for $subject");
                $this->email->message($message);
                $this->email->send();

                $message = '<div style="padding:0 4%;">
<p style="font-weight: bold;">Thank you for contact us.we will contact you as soon as possible. </p>                        
<p style="font-weight: bold;">Your details</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Name : </b> ' . ucfirst($name) . '</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Email : </b> ' . $email . '</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Phone : </b> ' . $mobile . '</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Subject : </b> ' . ucfirst($subject) . '</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Message : </b> ' . ucfirst($message1) . '</p>
                </div>';
                $message = $email_cnt->get_design($message);
                $this->email->to($email);
                $this->email->from("donotreply@airmedpathlabs.com", 'AirmedLabs');
                $this->email->subject("Contact From for $subject");
                $this->email->message($message);
                $this->email->send();
                echo 1;
            } else {
                echo 0;
            }
        }
    }

    function partner_with_send_mail() {
        $this->load->helper("Email");
        $email_cnt = new Email;
        $name = $this->input->post('name');
        $email = $this->input->post('email');
        $mobile = $this->input->post('phone');
        $domain = $this->input->post('domain');
        $message1 = $this->input->post('message');
        $address = $this->input->post('address');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required|numeric');
        $this->form_validation->set_rules('domain', 'domain', 'trim|required');
        $this->form_validation->set_rules('message', 'Message', 'trim|required');
        $this->form_validation->set_rules('address', 'address', 'trim|required');
        $this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'required|trim');
        $captcha = $this->varify_captcha();
        //echo $captcha;die();
        if ($this->form_validation->run() != FALSE && $captcha == 1) {
            $data1 = array("domain" => $domain, "query" => $message1, "name" => $name, "email" => $email, "mobile" => $mobile, "address" => $address);
            $insert = $this->user_master_model->master_fun_insert("partner_with_us", $data1);
            $config['mailtype'] = 'html';
            $this->email->initialize($config);
            $message = '<div style="padding:0 4%;">
                    <h4 style="color:#67B1A3;text-decoration: underline;">Business Partnerships</h4>
                        <p style="color:#7e7e7e;font-size:13px;font-weight: bold;">Follow information shows Contact Person Detail :</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Name : </b> ' . ucfirst($name) . '</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Email : </b> ' . $email . '</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Phone : </b> ' . $mobile . '</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Domain : </b> ' . ucfirst($domain) . '</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Query/Proposal : </b> ' . ucfirst($message1) . '</p>AddressQuery/Proposal : </b> ' . ucfirst($address) . '</p>
                </div>';
            $message = $email_cnt->get_design($message);
            $this->email->to($this->config->item('admin_booking_email'));
            $this->email->cc('nishit@virtualheight.com');
            $this->email->from("donotreply@airmedpathlabs.com", 'AirmedLabs');
            $this->email->subject("Airmed Partner with us");
            $this->email->message($message);
            $this->email->send();
            echo 1;
        } else {
            echo 0;
        }
    }

    function health_feed() {
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data['health_feed'] = $this->user_master_model->health_feed();
        $data['package'] = $this->user_master_model->master_fun_get_tbl_val("package_master", array("status" => 1), array("title", "asc"));
        $data['active_class'] = "health";
        $this->load->view('user/header', $data);
        $this->load->view('user/health_feed', $data);
        $this->load->view('user/footer');
    }

    function health_feed_details($id) {
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data["login_data"] = loginuser();
        $data['health_feed'] = $this->user_master_model->master_fun_get_tbl_val("health_feed_master", array("status" => 1, 'id' => $id), array("id", "asc"));
        $data['package'] = $this->user_master_model->master_fun_get_tbl_val("package_master", array("status" => 1), array("title", "asc"));
        $data['active_class'] = "health";
        $this->load->view('user/header', $data);
        $this->load->view('user/inner_health_feed', $data);
        $this->load->view('user/footer');
    }

    function support_system() {
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data["success"] = $this->session->flashdata("success");
        $data['query'] = $this->user_master_model->master_fun_get_tbl_val("ticket_master", array("user_id" => $uid, "status" => 1), array("id", "desc"));
        $totalRows = count($data["query"]);
        $config = array();
        $config["base_url"] = base_url() . "user_master/support_system/";
        $config["total_rows"] = $totalRows;
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $sort = $this->input->get("sort");
        $by = $this->input->get("by");
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["ticket"] = $this->user_master_model->get_support_system($uid, $config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();
        $this->load->view('user/header', $data);
        $this->load->view('user/support_system', $data);
        $this->load->view('user/footer');
    }

    function add_ticket() {
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $this->load->helper("Email");
        $email_cnt = new Email;

        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        $this->load->library('form_validation');
        $this->form_validation->set_rules('title', 'Subject', 'trim|required');
        $this->form_validation->set_rules('message', 'Message', 'trim|required');
        $data["success"] = $this->session->flashdata("success");
        if ($this->form_validation->run() != FALSE) {
            $message = $this->input->post('message');
            $title = $this->input->post('title');
            $this->load->helper('string');
            $ticket = substr(number_format(time() * rand(), 0, '', ''), 0, 7);
            $data1 = array(
                "ticket" => $ticket,
                "user_id" => $uid,
                "title" => $title,
            );
            $insert = $this->user_master_model->master_fun_insert("ticket_master", $data1);
            $data2 = array(
                "ticket_fk" => $insert,
                "message" => $message,
                "type" => 1,
                "created_date" => date('Y-m-d H:i:s')
            );
            $insert1 = $this->user_master_model->master_fun_insert("message_master", $data2);
            if ($insert1) {
                $customer = $this->user_master_model->master_fun_get_tbl_val("customer_master", array("id" => $uid), array("id", "desc"));
                $config['mailtype'] = 'html';
                $this->email->initialize($config);
                $message = '<div style="padding:0 4%;">
                    <h4 style="color:#67B1A3;text-decoration: underline;">Customer Report & Issue</h4>
                        <p style="color:#7e7e7e;font-size:13px;font-weight: bold;">Query Details :</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Name : </b> ' . ucfirst($customer[0]['full_name']) . '</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Phone : </b> ' . $customer[0]['mobile'] . '</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Subject : </b> ' . ucfirst($title) . '</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Message : </b> ' . ucfirst($message) . '</p>
                </div>';
                $message = $email_cnt->get_design($message);
                $this->email->to($this->config->item('admin_booking_email'));
                $this->email->from("donotreply@airmedpathlabs.com", 'AirmedLabs');
                $this->email->subject("Airmedlabs Support System");
                $this->email->message($message);
                $this->email->send();
                $this->session->set_flashdata("success", array("Ticket Created Successfully"));
                redirect("user_master/support_system", "refresh");
            }
        } else {
            $data['success'] = $this->session->flashdata("success");
            $data['error'] = $this->session->flashdata("error");
            $this->load->view('user/header', $data);
            $this->load->view('user/create_ticket', $data);
            $this->load->view('user/footer');
        }
    }

    function view_ticket_details() {
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        $data['type'] = $data["login_data"]["type"];
        $this->load->library('form_validation');
        $this->form_validation->set_rules('message', 'Message', 'trim|required');
        $ticketfk = $this->uri->segment(3);
        $data['ticket'] = $ticketfk;
        $data['allmassage'] = $this->user_master_model->ticket_details($ticketfk);
        $data["success"] = $this->session->flashdata("success");
        if ($this->form_validation->run() != FALSE) {
            $message = $this->input->post('message');
            $tc = $this->user_master_model->master_fun_get_tbl_val("ticket_master", array("ticket" => $ticketfk, "status" => 1), array("id", "desc"));
            $ticket = $tc[0]['id'];
            $this->load->helper('string');
            $data2 = array(
                "ticket_fk" => $ticket,
                "message" => $message,
                "type" => 1,
                "created_date" => date('Y-m-d H:i:s')
            );
            $insert1 = $this->user_master_model->master_fun_insert("message_master", $data2);
            if ($insert1) {
                $this->session->set_flashdata("success", array("Message Send Successfully"));
                redirect("user_master/view_ticket_details/" . $ticketfk, "refresh");
            }
        } else {
            $data['success'] = $this->session->flashdata("success");
            $data['error'] = $this->session->flashdata("error");
            $this->load->view('user/header', $data);
            $this->load->view('user/show_message', $data);
            $this->load->view('user/footer');
        }
    }

    function delete_ticket() {
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        $cid = $this->uri->segment('3');
        $data = array(
            "status" => '0'
        );
        $delete = $this->user_master_model->master_fun_update("ticket_master", array("id", $this->uri->segment('3')), $data);
        if ($delete) {
            $this->session->set_flashdata("success", array("Ticket Deleted Successfully"));
            redirect("user_master/support_system", "refresh");
        }
    }

    function pdf_report() {
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        date_default_timezone_set("UTC");
        $new_time = date("Y-m-d H:i:s", strtotime('+3 hours'));
        $filename = "payment_history_" . time() . '.pdf';
        $pdfFilePath = FCPATH . "/download/$filename";
        $data['page_title'] = 'Powers for Investments Co.'; // pass data to the view
        $data['history'] = $this->user_master_model->payment_history($uid);
        $html = $this->load->view('user/payment_pdf', $data, true); // render the view into HTML
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->autoScriptToLang = true;
        $pdf->baseScript = 1; // Use values in classes/ucdn.php  1 = LATIN
        $pdf->autoVietnamese = true;
        $pdf->autoArabic = true;
        $pdf->autoLangToFont = true;
        $pdf->WriteHTML($html);
        //$pdf->debug = true
        $pdf->SetFooter('www.' . $_SERVER['HTTP_HOST'] . '||' . $new_time); // Add a footer for good measure <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="😉" draggable="false" class="emoji">
        // $pdf->WriteHTML($html); // write the HTML into the PDF
        $pdf->Output($pdfFilePath, 'F'); // save to file because we can
        redirect("/download/$filename");
    }

    function about_us() {
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data['active_class'] = "about";
        $this->load->view('user/header', $data);
        $this->load->view('user/about_us', $data);
        $this->load->view('user/footer');
    }

    function check_email() {
        $email = $this->input->post('email');
        $count = $this->user_master_model->num_row('customer_master', array("email" => $email, "status" => '1'));
        if ($count != 0) {
            echo "login";
        } else {
            echo "register";
        }
    }

    function testimonials() {
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data['testimonial'] = $this->user_master_model->master_fun_get_tbl_val("testimonials_master", array("status" => 1), array("id", "asc"));
        $this->load->view('user/header', $data);
        $this->load->view('user/testimonials', $data);
        $this->load->view('user/footer');
    }

    function package_inquiry() {
        $this->load->helper("Email");
        $email_cnt = new Email;

        $mobile = $this->input->post('mobile');
        $package = $this->input->post('package');
        $data1 = array(
            "mobile" => '+91' . $mobile,
            "package" => $package,
        );
        $pck = $this->user_master_model->master_fun_get_tbl_val("package_master", array("id" => $package, "status" => 1), array("id", "desc"));
        $packagename = $pck[0]['title'];
        $insert = $this->user_master_model->master_fun_insert("instant_contact", $data1);
        if ($insert) {
            $config['mailtype'] = 'html';
            $this->email->initialize($config);
            $message = '<div style="padding:0 4%;">
                    <h4 style="color:#67B1A3;text-decoration: underline;">Inquiry Detail</h4>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Mobile : </b> ' . $mobile . '</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Package Name : </b> ' . $packagename . '</p> 
                </div>';
            $message = $email_cnt->get_design($message);
            $this->email->to($this->config->item('admin_booking_email'));
            $this->email->from("donotreply@airmedpathlabs.com", 'AirmedLabs');
            $this->email->subject("Inquiry for Package");
            $this->email->message($message);
            $this->email->send();
            $this->session->set_flashdata("success", array("Thank you for inquiry. We will respond to you as soon as possible."));
            redirect("user_master/package_details/" . $package);
        }
    }

    function book_without_login() {
        $this->load->helper("Email");
        $email_cnt = new Email;
        $mobile = $this->input->post('mobile');
        $package = $this->input->post('test_package_id');
        $test_package = $this->input->post('test_package_name');
        $date = date('Y-m-d H:i:s');
        $ids = explode(',', $package);
        foreach ($ids as $key) {
            $ex = explode('-', $key);
            $first_pos = $ex[0];
            if ($first_pos == "t") {
                $testid[] = $ex[1];
            }
            if ($first_pos == "p") {
                $packageid[] = $ex[1];
            }
        }
        $code = random_string('alnum', 6);
        $confirm_code = random_string('alnum', 6);
        $pid = implode($packageid, ',');
        $tid = implode($testid, ',');
        $OTP = rand(11111, 99999);
        $data1 = array(
            "mobile" => $mobile,
            "test_fk" => $tid,
            "package_fk" => $pid,
            "date" => $date
        );
        $insert = $this->user_master_model->master_fun_insert("book_without_login", $data1);
        if ($insert) {
            $sms_message = $this->job_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "book_not_login"), array("id", "asc"));
            $sms_message = preg_replace("/{{MOBILE}}/", $mobile, $sms_message[0]["message"]);
            if ($pid != '' && $tid != '') {
                $sms_message = preg_replace("/{{TESTPACK}}/", 'Test/Package', $sms_message);
            } else if ($pid != '') {
                $sms_message = preg_replace("/{{TESTPACK}}/", 'Package', $sms_message);
            } else {
                $sms_message = preg_replace("/{{TESTPACK}}/", 'Test', $sms_message);
            }

            $this->load->helper("sms");
            $notification = new Sms();
            $mb_length = strlen($mobile);
            if ($mb_length == 10) {
                $notification::send($mobile, $sms_message);
            }
            if ($mb_length == 11 || $mb_length == 12 || $mb_length == 13) {
                $check_phone = substr($mobile, 0, 2);
                $check_phone1 = substr($mobile, 0, 1);
                $check_phone2 = substr($mobile, 0, 3);
                if ($check_phone2 == '+91') {
                    $get_phone = substr($mobile, 3);
                    $notification::send($get_phone, $sms_message);
                }
                if ($check_phone == '91') {
                    $get_phone = substr($mobile, 2);
                    $notification::send($get_phone, $sms_message);
                }
                if ($check_phone1 == '0') {
                    $get_phone = substr($mobile, 1);
                    $notification::send($get_phone, $sms_message);
                }
            }
            //vishal
            //vishal

            $sms_message = $this->job_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "test_info"), array("id", "asc"));
            $sms_message = preg_replace("/{{MOBILE}}/", $mobile, $sms_message[0]["message"]);
            if ($pid != '' && $tid != '') {
                $sms_message = preg_replace("/{{TESTPACK}}/", 'Test/Package', $sms_message);
            } else if ($pid != '') {
                $sms_message = preg_replace("/{{TESTPACK}}/", 'Package', $sms_message);
            } else {
                $sms_message = preg_replace("/{{TESTPACK}}/", 'Test', $sms_message);
            }
            $sms_message = preg_replace("/{{TESTPACKLIST}}/", $test_package, $sms_message);

            $configmobile = $this->config->item('admin_alert_phone');
            foreach ($configmobile as $p_key) {
                //$notification::send($configmobile, $sms_message);
                $this->job_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $p_key, "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
            }
            //vishal
            /*
              $sms_message = $this->user_master_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "OTP"), array("id", "asc"));
              $sms_message = preg_replace("/{{NAME}}/", ucfirst($name), $sms_message[0]["message"]);
              $sms_message = preg_replace("/{{OTP}}/", $OTP, $sms_message);
              $sms_message = preg_replace("/{{PRICE}}/", "", $sms_message);
              $this->load->helper("sms");
              $notification = new Sms();
              $mb_length = strlen($mobile);
              if ($mb_length == 10) {
              $notification->send($mobile, $sms_message);
              }
              if ($mb_length == 11 || $mb_length == 12 || $mb_length == 13) {
              $check_phone = substr($mobile, 0, 2);
              $check_phone1 = substr($mobile, 0, 1);
              $check_phone2 = substr($mobile, 0, 3);
              if ($check_phone2 == '+91') {
              $get_phone = substr($mobile, 3);
              $notification::send($get_phone, $sms_message);
              }
              if ($check_phone == '91') {
              $get_phone = substr($mobile, 2);
              $notification::send($get_phone, $sms_message);
              }
              if ($check_phone1 == '0') {
              $get_phone = substr($mobile, 1);
              $notification::send($get_phone, $sms_message);
              }
              } */
            $config['mailtype'] = 'html';
            $this->email->initialize($config);
            $message = '<div style="padding:0 4%;">
                    <h4 style="color:#67B1A3;text-decoration: underline;">Inquiry Detail</h4>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Mobile : </b> ' . $mobile . '</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Test/Package Name : </b> ' . $test_package . '</p>
                </div>';
            $message = $email_cnt->get_design($message);
            $this->email->to($this->config->item('admin_booking_email'));
            $this->email->from("donotreply@airmedpathlabs.com", 'AirmedLabs');
            $this->email->subject("Inquiry for Test and Package book");
            $this->email->message($message);
            $this->email->send();
            $this->session->set_flashdata("payment_success", array("Thank you for inquiry. Our Representative will call you shortly"));
            redirect("user_master");
        }
    }

    function varify_phone_test($id = null) {
        $data["user_info"] = $this->register_model->master_fun_get_tbl_val("book_without_login", array("id" => $id), array("id", "asc"));
        if (empty($data["user_info"]) && $id == null) {
            $this->session->set_flashdata("unsuccess", array("Oops somthing is wromg. Please try again."));
            redirect("register/index");
        }
        $data["id"] = $id;
        $data["msg"] = "We'll send you OTP, Please verify here.It's take a few minutes.";
        $this->load->view('user/header');
        $this->load->view('user/varify_phone_test', $data);
        $this->load->view('user/footer');
    }

    function my_family() {
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $this->load->model('customer_model');
        $data["login_data"] = loginuser();
        $data['cid'] = $uid = $data["login_data"]['id'];
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data["success"] = $this->session->flashdata("success");
        //$data["family_member"] = $this->customer_model->get_user_family_member($uid);
        $data['relation'] = $this->user_master_model->master_fun_get_tbl_val("relation_master", array("status" => "1"), array("name", "asc"));
        $data["count_com"] = $this->customer_model->get_user_family_member($uid);
        $totalRows = count($data["count_com"]);
        $config1 = array();
        $config1["base_url"] = base_url() . "user_master/my_family/";
        $config1["total_rows"] = $totalRows;
        $config1["per_page"] = 10;
        $config1["uri_segment"] = 3;
        $this->pagination->initialize($config1);
        $sort = $this->input->get("sort");
        $by = $this->input->get("by");
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["family_member"] = $this->customer_model->get_user_family_member1($uid, $config1["per_page"], $page);
        $data["links1"] = $this->pagination->create_links($config1);
        $data['active_class'] = "family";
        $this->load->view('user/header', $data);
        $this->load->view('user/my_family', $data);
        $this->load->view('user/footer');
    }

    function user_job_invoice() {
        $this->load->view('user/header');
        $this->load->view("user/user_job_invoice");
        $this->load->view('user/footer');
    }

    function all_packages() {
        $data["login_data"] = loginuser();
        $data["user"] = $this->user_master_model->getUser($data["login_data"]["id"]);
        /* Show package start */
        $city = $this->session->userdata("test_city");
        if (empty($city)) {
            $city = 1;
        }
        $this->session->set_userdata("test_city", $city);
        /* Nishit code start */
        $data1 = $this->user_master_model->master_fun_get_tbl_val("package_master", array("status" => 1, "is_view" => 1), array("order", "asc"));
        $cnt = 0;
        $package_array = array();
        foreach ($data1 as $key) {
            $pkg_price = $this->user_master_model->master_fun_get_tbl_val("package_master_city_price", array("status" => "1", "package_fk" => $key["id"], "city_fk" => $city), array("id", "asc"));
            $package_array[] = array($key, $pkg_price);
            $cnt++;
        }
        $data["package_array"] = $package_array;
        /* Show package end */
        /* Package category start */
        $suggest_package = array();
        $package_category = $this->user_master_model->master_fun_get_tbl_val("package_category", array("status" => '1'), array("name", "asc"));
        foreach ($package_category as $key) {
            $package_list = $this->user_master_model->get_val("SELECT p.id,p.title,pr.`d_price` FROM package_master as p left join package_master_city_price as pr on pr.package_fk=p.id WHERE p.status='1' AND pr.status='1' AND p.is_view='1' AND p.category_fk='" . $key["id"] . "' AND pr.city_fk='" . $city . "' order by p.title ASC");
            $cnt = 0;
            foreach ($package_list as $p_key) {
                $p_key["desc_web"] = '';
                $p_key["desc_app"] = '';
                $package_test = $this->user_master_model->get_val("SELECT GROUP_CONCAT(`package_test`.`test_fk`) AS test_fk FROM `package_test` WHERE package_fk='" . $p_key["id"] . "' AND `package_test`.`status`='1' GROUP BY `package_test`.`package_fk`");
                if (!empty($package_test)) {
                    $tst_prc = $this->user_master_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $city . "' AND `test_master`.`id` in (" . $package_test[0]["test_fk"] . ")");
                    $p_key['test_list'] = $tst_prc;
                }
                $package_list[$cnt] = $p_key;
                $cnt++;
            }
            $key["package"] = $package_list;
            $suggest_package[] = $key;
        }
        $data["suggest_package"] = $suggest_package;
        /* Package category end */
        $this->load->view('user/header', $data);
        $this->load->view("user/all_package1", $data);
        $this->load->view('user/footer', $data);
    }

    function sub_package() {

        $package_slug = $this->uri->segment(2);
        $data["login_data"] = loginuser();
        $data["user"] = $this->user_master_model->getUser($data["login_data"]["id"]);
        /* Show package start */
        $city = $this->session->userdata("test_city");
        if (empty($city)) {
            $city = 1;
        }
        $this->session->set_userdata("test_city", $city);
        /* Nishit code start */
        $package_cat1 = $this->user_master_model->master_fun_get_tbl_val("package_category", array("status" => '1', "slug" => $package_slug), array("id", "asc"));
        $package_cat = $package_cat1[0]["id"];
        $data1 = $this->user_master_model->master_fun_get_tbl_val("package_master", array("status" => 1, "is_view" => 1, "category_fk" => $package_cat), array("order", "asc"));
        $cnt = 0;
        $package_array = array();
        foreach ($data1 as $key) {
            $pkg_price = $this->user_master_model->master_fun_get_tbl_val("package_master_city_price", array("status" => "1", "package_fk" => $key["id"], "city_fk" => $city), array("id", "asc"));
            $package_array[] = array($key, $pkg_price);
            $cnt++;
        }
        $data["cat_name"] = $this->user_master_model->master_fun_get_tbl_val("package_category", array("status" => '1', "id" => $package_cat), array("id", "asc"));
        $data["package_array"] = $package_array;
        /* Show package end */
        /* Package category start */
        $suggest_package = array();
        $package_category = $this->user_master_model->master_fun_get_tbl_val("package_category", array("status" => '1'), array("name", "asc"));
        foreach ($package_category as $key) {
            $package_list = $this->user_master_model->get_val("SELECT p.id,p.title,pr.`d_price` FROM package_master as p left join package_master_city_price as pr on pr.package_fk=p.id WHERE p.status='1' AND pr.status='1' AND p.is_view='1' AND p.category_fk='" . $key["id"] . "' AND pr.city_fk='" . $city . "' order by p.title ASC");
            $cnt = 0;
            foreach ($package_list as $p_key) {
                $p_key["desc_web"] = '';
                $p_key["desc_app"] = '';
                $package_test = $this->user_master_model->get_val("SELECT GROUP_CONCAT(`package_test`.`test_fk`) AS test_fk FROM `package_test` WHERE package_fk='" . $p_key["id"] . "' AND `package_test`.`status`='1' GROUP BY `package_test`.`package_fk`");
                if (!empty($package_test)) {
                    $tst_prc = $this->user_master_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $city . "' AND `test_master`.`id` in (" . $package_test[0]["test_fk"] . ")");
                    $p_key['test_list'] = $tst_prc;
                }
                $package_list[$cnt] = $p_key;
                $cnt++;
            }
            $key["package"] = $package_list;
            $suggest_package[] = $key;
        }
        $data["suggest_package"] = $suggest_package;
        /* Package category end */
        $this->load->view('user/header', $data);
        $this->load->view("user/sub_package", $data);
        $this->load->view('user/footer', $data);
    }

    function job_details($job_id) {
        $job_details = $this->user_master_model->master_fun_get_tbl_val("job_master", array("status !=" => "0", "id" => $job_id), array("id", "asc"));
        if (!empty($job_details)) {
            $book_test = $this->user_master_model->master_fun_get_tbl_val("job_test_list_master", array("job_fk" => $job_id), array("id", "desc"));
            $book_package = $this->user_master_model->master_fun_get_tbl_val("book_package_master", array("job_fk" => $job_id, "status" => "1"), array("id", "desc"));
            $test_name = array();
            foreach ($book_test as $key) {
                $price1 = $this->user_master_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $job_details[0]["test_city"] . "' AND `test_master`.`id`='" . $key["test_fk"] . "'");
                $test_name[] = $price1[0];
            }
            $job_details[0]["book_test"] = $test_name;
            $package_name = array();
            foreach ($book_package as $key) {

                $price1 = $this->user_master_model->get_val("SELECT 
          `package_master`.*,
          `package_master_city_price`.`a_price` AS `a_price`,
          `package_master_city_price`.`d_price` AS `d_price`
          FROM
          `package_master`
          INNER JOIN `package_master_city_price`
          ON `package_master`.`id` = `package_master_city_price`.`package_fk`
          WHERE `package_master`.`status` = '1'
          AND `package_master_city_price`.`status` = '1' AND `package_master_city_price`.`city_fk` = '" . $job_details[0]["test_city"] . "' AND `package_master`.`id`='" . $key["package_fk"] . "'");
                $package_name[] = $price1[0];
            }
            $job_details[0]["book_package"] = $package_name;
        }
        return $job_details;
    }

    function get_job_details() {
        $jid = $this->input->get_post("jid");
        $job_details = $this->job_details($jid);

        $html = '<ul class="">';
        foreach ($job_details[0]["book_test"] as $key) {
            $html .= '<li id="li_id_t-' . $key["id"] . '">  <a href="javascript:void(0);" class="myAvailableTest" id="remove_t-' . $key["id"] . '" title="' . $key["test_name"] . '" onclick="remove_test(\'t-' . $key["id"] . '\',\'' . $jid . '\');"><i class="fa fa-trash" style="color:red;"> </i></a>  ' . $key["test_name"] . ',</li> <li>Price : Rs. ' . $key["price"] . '</li>';
        }
        foreach ($job_details[0]["book_package"] as $key) {
            $html .= '<li id="li_id_p-' . $key["id"] . '">  <a href="javascript:void(0);" class="myAvailableTest" id="remove_p-' . $key["id"] . '" title="' . $key["title"] . '" onclick="remove_test(\'p-' . $key["id"] . '\',\'' . $jid . '\');"><i class="fa fa-trash" style="color:red;"> </i></a>  ' . $key["title"] . ',</li> <li>Price : Rs. ' . $key["d_price"] . '</li>';
        }
        echo $html .= '</ul>';
        //print_R($job_details);
    }

    function delete_job_test() {
        $jid = $this->input->get_post("jid");
        $tid = $this->input->get_post("tid");
        $job_details = $this->job_details($jid);
        $new_tid = explode("-", $tid);
        $price = 0;
        if ($new_tid[0] == 't') {
            $delete = $this->user_master_model->master_fun_update("job_test_list_master", array("id", $new_tid[1]), array("status" => "0"));
            $price1 = $this->user_master_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $job_details[0]["test_city"] . "' AND `test_master`.`id`='" . $new_tid[1] . "'");
            $price = $price + $price1[0]["price"];
        }
        if ($new_tid[0] == 'p') {
            $delete = $this->user_master_model->master_fun_update("book_package_master", array("id", $new_tid[1]), array("status" => "0"));
            $price1 = $this->user_master_model->get_val("SELECT 
          `package_master`.*,
          `package_master_city_price`.`a_price` AS `a_price`,
          `package_master_city_price`.`d_price` AS `d_price`
          FROM
          `package_master`
          INNER JOIN `package_master_city_price`
          ON `package_master`.`id` = `package_master_city_price`.`package_fk`
          WHERE `package_master`.`status` = '1'
          AND `package_master_city_price`.`status` = '1' AND `package_master_city_price`.`city_fk` = '" . $job_details[0]["test_city"] . "' AND `package_master`.`id`='" . $new_tid[1] . "'");
            $price = $price + $price1[0]["d_price"];
        }
        $j_price = $job_details[0]["price"] - $price;
        if ($job_details[0]["payable_amount"] > $price) {
            $j_payable_price = $job_details[0]["payable_amount"] - $price;
        } else {
            
        }
        $this->user_master_model->master_fun_update("job_master", array("id", $jid), array("price" => "0"));
    }

    function delete_job() {
        $jid = $this->input->get_post("jid");
        $this->user_master_model->master_fun_update("job_master", array("id", $jid), array("status" => "9"));
        $this->session->set_flashdata("success", array("Job successfully deleted."));
        echo 1;
    }

    function sub_news() {
        $email = $this->input->post("email");
        $chk = $this->user_master_model->master_fun_get_tbl_val("newslatter", array("email" => $email, "status" => 1), array("id", "desc"));
        if (empty($chk)) {
            $insert = $this->user_master_model->master_fun_insert("newslatter", array("email" => $email, "createddate" => date("Y-m-d H:i:s")));
            echo 1;
        } else {
            echo 0;
        }
    }

    function packages() {
        $this->load->model('customer_model');
        $data["error"] = $this->session->flashdata("error");
        $data["success"] = $this->session->flashdata("success");
        //$data["family_member"] = $this->customer_model->get_user_family_member($uid);
        $data['relation'] = $this->user_master_model->master_fun_get_tbl_val("relation_master", array("status" => "1"), array("name", "asc"));
        $data["count_com"] = $this->customer_model->get_user_family_member($uid);
        $totalRows = count($data["count_com"]);
        $config1 = array();
        $config1["base_url"] = base_url() . "user_master/my_family/";
        $config1["total_rows"] = $totalRows;
        $config1["per_page"] = 10;
        $config1["uri_segment"] = 3;
        $this->pagination->initialize($config1);
        $sort = $this->input->get("sort");
        $by = $this->input->get("by");
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["family_member"] = $this->customer_model->get_user_family_member1($uid, $config1["per_page"], $page);
        $data["links1"] = $this->pagination->create_links($config1);
        $data['active_class'] = "family";
        $this->load->view('user/header', $data);
        $this->load->view("user/all_package", $data);
        $this->load->view('user/footer');
    }

    function commercial() {
        $data["login_data"] = loginuser();
        $data['query'] = $this->user_master_model->master_fun_get_tbl_val("press_release", array("status" => "1"), array("id", "desc"));
        $this->load->view('user/header', $data);
        $this->load->view("user/commercial", $data);
        $this->load->view('user/footer');
    }
    function check_login() {
        $u_email = $this->input->get_post("u_email");
        $u_pass = $this->input->get_post("u_pass");
        
        $recaptchaResponse = trim($this->input->get_post('token'));
        $google_url = "https://www.google.com/recaptcha/api/siteverify";
        $secret = '6Ld5_x8UAAAAAGn_AV4406lg29xu2hpQQJMaD2BC';
        $ip = $_SERVER['REMOTE_ADDR'];
        $url = $google_url . "?secret=" . $secret . "&response=" . $recaptchaResponse . "&remoteip=" . $ip;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
        $res = curl_exec($curl);
        curl_close($curl);
        $res = json_decode($res, true);
        //var_dump($res);
        if ($res['success'] == true) {
            $captcha = 1;
        } else {
            $captcha = 0;
        }
        $count = $this->user_master_model->num_row('customer_master', array("email" => $u_email, "password" => $u_pass, "status" => '1'));
        if ($count > 0 && $captcha == 1) {
            $count1 = $this->user_master_model->num_row('customer_master', array("email" => $u_email, "password" => $u_pass, "status" => '1', "mobile !=" => ""));
            $data['query'] = $this->user_master_model->master_fun_get_tbl_val("customer_master", array("email" => $u_email, "password" => $u_pass, "status" => '1'), array("id", "desc"));
            if ($count1 > 0) {
                $sess_array = array(
                    'id' => $data['query'][0]["id"],
                    'name' => $data['query'][0]["full_name"],
                    'type' => $data['query'][0]["type"],
                );
                $this->session->set_userdata('logged_in_user', $sess_array);
                echo json_encode(array("status" => "1", "message" => "success."));
            } else {
                echo json_encode(array("status" => "0", "message" => "Your mobile number is not varify <a href='" . base_url() . "Register/varify_phone1/" . $data['query'][0]["id"] . "'>Click here</a> for varify."));
            }
        } else {
            echo json_encode(array("status" => "0", "message" => "Invalid email or password."));
        }
    }

    function advertisement() {
        $data["login_data"] = loginuser();
        $data['query'] = $this->user_master_model->master_fun_get_tbl_val("press_release", array("status" => "1"), array("id", "desc"));
        $this->load->view('user/header', $data);
        $this->load->view("user/advertisement", $data);
        $this->load->view('user/footer');
    }

    function my_team() {
        $data["login_data"] = loginuser();
        $data['query'] = $this->user_master_model->master_fun_get_tbl_val("press_release", array("status" => "1"), array("id", "desc"));
        $this->load->view('user/header', $data);
        $this->load->view("user/our_team", $data);
        $this->load->view('user/footer');
    }

}

?>