<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Customer_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('user_model');
        $this->load->model('customer_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('email');
        $data["login_data"] = logindata();
    }

    function partner_with_us() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        //print_r($data["login_data"]); die();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $data['success'] = $this->session->flashdata("success");

        $total_row = $this->customer_model->num_row_srch_partener();
        $config["base_url"] = base_url() . "customer_master/partner_with_us";
        $config["total_rows"] = $total_row;
        $config["per_page"] = 50;
        $config['page_query_string'] = TRUE;
        $config['cur_tag_open'] = '<span>';
        $config['cur_tag_close'] = '</span>';
        $config['next_link'] = 'Next &rsaquo;';
        $config['prev_link'] = '&lsaquo; Previous';
        $this->pagination->initialize($config);
        $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
        $data['query'] = $this->customer_model->row_srch_partener($config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('partner_with_us_list', $data);
        $this->load->view('footer');
    }

    function customer_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        //print_r($data["login_data"]); die();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        //print_r($data['success']);
        $name = $this->input->get('name');
        $email = $this->input->get('email');
        $mobile = $this->input->get('mobile');
        $date = $this->input->get('date');
        if ($name != "" || $email != "" || $mobile != "" || $date != "") {
            $srchdata = array("name" => $name, "email" => $email, "mobile" => $mobile, "date" => $date);
            $total_row = $this->customer_model->num_row_srch($srchdata);
            $data['name'] = $name;
            $data['email'] = $email;
            $data['mobile'] = $mobile;
            $data['date'] = $date;
            if ($_GET['debug'] == "1") {
                echo $this->db->last_query();
                die();
            }
            $config = array();
            $get = $_GET;
            unset($get['offset']);
            $config["base_url"] = base_url() . "customer-master/customer-list?" . http_build_query($get);
            $config["total_rows"] = $total_row;
            $config["per_page"] = 50;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->customer_model->row_srch($srchdata, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["page"]=$page;
        } else {
            $srchdata = array();
            $total_row = $this->customer_model->num_row_srch($srchdata);
            $config["base_url"] = base_url() . "customer-master/customer-list";
            $config["total_rows"] = $total_row;
            $config["per_page"] = 50;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->customer_model->row_srch($srchdata, $config["per_page"], $page);
            $data["page"]=$page;
            $data["links"] = $this->pagination->create_links();
        }
        //$data['query'] = $this->customer_model->master_fun_get_tbl_val("customer_master",array('status'=>1),array("id","asc"));
        //$this->load->view('admin/state_list_view', $data);
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('customer_list', $data);
        $this->load->view('footer');
    }

    function customer_add() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $this->load->library('form_validation');

        $this->form_validation->set_rules('fname', 'Full Name', 'trim|required');

        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[customer_master.email]');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|min_length[10]|max_length[13]');
        //$this->form_validation->set_rules('gender', 'Gender', 'trim|required');
        //$this->form_validation->set_rules('country', 'Country', 'trim|required');
        //$this->form_validation->set_rules('state', 'State', 'trim|required');
        //$this->form_validation->set_rules('city', 'City', 'trim|required');
        //$this->form_validation->set_rules('address', 'Address', 'trim|required');



        if ($this->form_validation->run() != FALSE) {
            $fname = $this->input->post('fname');

            $email = $this->input->post('email');
            $mobile = $this->input->post('mobile');
            $gender = $this->input->post('gender');
            $country = $this->input->post('country');
            $state = $this->input->post('state');
            $city = $this->input->post('city');
            $address = $this->input->post('address');


            $data = array(
                "full_name" => $fname,
                "email" => $email,
                "mobile" => $mobile,
                "gender" => $gender,
                "country" => $country,
                "state" => $state,
                "city" => $city,
                "address" => $address,
            );


            $data['query'] = $this->customer_model->master_fun_insert("customer_master", $data);
            $this->session->set_flashdata("success", array("Customer successfull added."));
            redirect("customer-master/customer-list", "refresh");
        } else {
            $data['country'] = $this->user_model->master_fun_get_tbl_val("country", array("status" => 1), array("id", "desc"));
            $data['state'] = $this->user_model->master_fun_get_tbl_val("state", array("status" => 1), array("id", "desc"));
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('customer_add', $data);
            $this->load->view('footer');
        }
    }

    function customer_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->customer_model->master_fun_update("customer_master", array("id", $cid), array("status" => "0", "active" => "0", "mobile" => ""));
        //	$data['query'] = $this->customer_model->master_fun_update("customer_master", array("id", $cid), array("mobile" => "" ));
        $this->session->set_flashdata("success", array("Customer successfully deleted."));
        redirect("customer-master/customer-list", "refresh");
    }

    function customer_deactive() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->customer_model->master_fun_update("customer_master", array("id", $cid), array("active" => "0"));

        $this->session->set_flashdata("success", array("Customer successfully deactivated."));
        redirect("customer-master/customer-list", "refresh");
    }

    function customer_active() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->customer_model->master_fun_update("customer_master", array("id", $cid), array("active" => "1"));

        $this->session->set_flashdata("success", array("Customer successfully activated."));
        redirect("customer-master/customer-list", "refresh");
    }

    function customer_edit() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["cid"] = $this->uri->segment('3');
        $ccid = $this->uri->segment('3');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('fname', 'Full Name', 'trim|required');

        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|min_length[10]|max_length[13]');
        //$this->form_validation->set_rules('gender', 'Gender', 'trim|required');
        //$this->form_validation->set_rules('country', 'Country', 'trim|required');
        //$this->form_validation->set_rules('state', 'State', 'trim|required');
        //$this->form_validation->set_rules('city', 'City', 'trim|required');
        //$this->form_validation->set_rules('address', 'Address', 'trim|required');


        if ($this->form_validation->run() != FALSE) {

            $fname = $this->input->post('fname');
            $lname = $this->input->post('lname');
            $email = $this->input->post('email');
            $mobile = $this->input->post('mobile');
            $gender = $this->input->post('gender');
            $country = $this->input->post('country');
            $state = $this->input->post('state');
            $city = $this->input->post('city');
            $address = $this->input->post('address');

            $data = array(
                "full_name" => $fname,
                "email" => $email,
                "mobile" => $mobile,
                "gender" => $gender,
                "country" => $country,
                "state" => $state,
                "city" => $city,
                "address" => $address,
            );


            $data['query'] = $this->customer_model->master_fun_update("customer_master", array("id", $ccid), $data);
            $this->session->set_flashdata("success", array("Customer successfully updated."));
            redirect("customer-master/customer-list", "refresh");
        } else {
            $data['query'] = $this->customer_model->master_fun_get_tbl_val("customer_master", array("id" => $data["cid"]), array("id", "desc"));
            $data['country'] = $this->customer_model->master_fun_get_tbl_val("country", array("status" => 1), array("id", "asc"));
            $data['state'] = $this->customer_model->master_fun_get_tbl_val("state", array("status" => 1), array("id", "asc"));
            $data['city'] = $this->customer_model->master_fun_get_tbl_val("city", array("status" => 1), array("id", "asc"));

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('customer_edit', $data);
            $this->load->view('footer');
        }
    }

    function customer_view() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["cid"] = $this->uri->segment('3');
        $ccid = $this->uri->segment('3');
        $this->load->library('form_validation');

        $data['query'] = $this->customer_model->master_fun_get_tbl_val("customer_master", array("id" => $data["cid"]), array("id", "desc"));
        $data['country'] = $this->customer_model->master_fun_get_tbl_val("country", array("status" => 1), array("id", "desc"));
        $data['state'] = $this->customer_model->master_fun_get_tbl_val("state", array("status" => 1), array("id", "desc"));
        $data['city'] = $this->customer_model->master_fun_get_tbl_val("city", array("status" => 1), array("id", "desc"));

        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('customer_view', $data);
        $this->load->view('footer');
    }

    function get_state() {

        $country = $this->uri->segment(3);
        $data = $this->customer_model->master_fun_get_tbl_val("state", array("country_fk" => $country, "status" => 1), array("id", "desc"));
        //print_r($data); die();
        echo "<option value=''> Select State </option>";
        for ($i = 0; $i < count($data); $i++) {
//echo $i;
            $id = $data[$i]['id'];
            $name = ucfirst($data[$i]['state_name']);

            echo "<option value='$id'> $name </option>";
        }
    }

    function get_city() {

        $state = $this->uri->segment(3);
        $data = $this->customer_model->master_fun_get_tbl_val("city", array("state_fk" => $state, "status" => 1), array("id", "desc"));
        //print_r($data); die();
        echo "<option value=''> Select State </option>";
        for ($i = 0; $i < count($data); $i++) {
//echo $i;
            $id = $data[$i]['id'];
            $name = ucfirst($data[$i]['city_name']);

            echo "<option value='$id'> $name </option>";
        }
    }

    function export_csv() {

        $this->load->dbutil();

        $this->load->helper('file');
        $this->load->helper('download');
        $delimiter = ",";
        $newline = "\r\n";
        $filename = "filename_you_wish.csv";
        $query = "SELECT c.first_name,c.last_name,c.email,c.mobile,c.gender,c.address,country.country_name,city.city_name,state.state_name FROM  customer_master c LEFT JOIN city ON city.id=c.city LEFT JOIN state ON state.id=c.state LEFT JOIN country ON country.id=c.country  WHERE c.status=1";
        $result = $this->db->query($query);
        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
        force_download($filename, $data);
    }

    function account_history() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        //print_r($data["login_data"]); die();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $data['success'] = $this->session->flashdata("success");

        $data['customer'] = $this->customer_model->master_fun_get_tbl_val("customer_master", array('status' => 1), array("id", "asc"));
        //$this->load->view('admin/state_list_view', $data);
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('account_history', $data);
        $this->load->view('footer');
    }

    function Package_inquiry_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        //print_r($data["login_data"]); die();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $data['success'] = $this->session->flashdata("success");
        //$data['query'] = $this->customer_model->contactlist();
        $mobile = $this->input->get('mobile');
        $package = $this->input->get('package');
        $date = $this->input->get('date');
        $status = $this->input->get('status');
        $data['mobile'] = $mobile;
        $data['package'] = $package;
        $data['date'] = $date;
        $data['status'] = $status;
        if ($mobile != "" || $package != "" || $date != "" || $status != "") {
            $total_row = $this->customer_model->num_row_srch_contact_list($mobile, $package, $date, $status);
            $config = array();
            $get = $_GET;
            unset($get['offset']);
            $config["base_url"] = base_url() . "customer_master/Package_inquiry_list?" . http_build_query($get);
            $config["total_rows"] = $total_row;
            $config["per_page"] = 10;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->customer_model->row_srch_contact_list($mobile, $package, $date, $status, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        } else {
            $total_row = $this->customer_model->num_srch_contact_list();
            $config["base_url"] = base_url() . "customer_master/Package_inquiry_list";
            $config["total_rows"] = $total_row;
            $config["per_page"] = 10;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next <span class="icon-text">&#59230;</span>';
            $config['prev_link'] = '<span class="icon-text">&#59229;</span> Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->customer_model->srch_contact_list($config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        }
        $data["page"]=$page;
        $cnt=0;
        foreach($data['query'] as $key){
            $check_phone2 = substr($key["mobile"], 0, 3);
            if ($check_phone2 == '+91') {
                $get_phone = substr($key["mobile"], 3);
                $configmobile = $get_phone;
            }else{
                $configmobile = $key["mobile"];
            }
            $data['customer'] = $this->customer_model->master_fun_get_tbl_val("customer_master", array("status" => '1',"mobile"=>$configmobile), array("id", "asc"));
            $data['query'][$cnt]["c_name"]=$data['customer'][0]["full_name"];
            $data['query'][$cnt]["cid"]=$data['customer'][0]["id"];
            
            $cnt++;}
            //echo"<pre>";print_r($data['query']);die();
        $data['customer'] = $this->customer_model->master_fun_get_tbl_val("customer_master", array("status" => '1'), array("id", "asc"));
        $data['package_list'] = $this->customer_model->master_fun_get_tbl_val("package_master", array("status" => '1'), array("id", "asc"));
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('contact_list', $data);
        $this->load->view('footer');
    }

    function contact_pending() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->customer_model->master_fun_update("instant_contact", array("id", $cid), array("status" => "1"));

        $this->session->set_flashdata("success", array("Pending Successfully"));
        redirect("customer_master/Package_inquiry_list", "refresh");
    }

    function contact_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->customer_model->master_fun_update("instant_contact", array("id", $cid), array("status" => "0"));

        $this->session->set_flashdata("success", array("Contact Deleted Successfully"));
        redirect("customer_master/Package_inquiry_list", "refresh");
    }

    function contact_complete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->customer_model->master_fun_update("instant_contact", array("id", $cid), array("status" => "2"));

        $this->session->set_flashdata("success", array("Completed Successfully"));
        redirect("customer_master/Package_inquiry_list", "refresh");
    }

    function add_by_admin_for_book() {

        $fname = $this->input->post('fname');
        $mobile = $this->input->post('mobile');
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        $data = array(
            "full_name" => $fname,
            "email" => $email,
            "mobile" => $mobile,
            "password" => $password,
            "active" => '1'
        );

        $data['query'] = $this->customer_model->master_fun_insert("customer_master", $data);

        $config['mailtype'] = 'html';
        $this->email->initialize($config);

        $message = '<div style="background:#a3a3a3 !important;font-family: "Roboto",sans-serif;">
<script src="https://use.fontawesome.com/44049e3dc5.js"></script>
    <div style="background: #6b6b6b none repeat scroll 0 0;float: left;padding: 7%;">
        <div style="border:1px solid #f1f1f1;border-bottom:1px solid #c7c7c7;padding:1.5%;width:100%;float:left;background:#fff;">
            <div style="float:left;width:64%;">
                        <img alt="" src="' . base_url() . 'user_assets/images/logo.png" style="height: 100px;width:213px;"/>
            </div>
                <div style="float:right;text-align: right;width:33%;padding-top:7px;">

                <p style=" width:100%;color: #111111;font-weight: 500;float:right;margin:0px 0px 5px 0px;padding:0;"><img src="' . base_url() . 'user_assets/images/message.png" style="height:10px;width:10px;"> <a href="mailto:info@airmedpathlabs.com" style="text-decoration:none;color:#7e7e7e;">info@airmedpathlabs.com</p>
                <p style=" width:100%;color: #111111;font-weight: 500;float:right;margin:0;padding:0;"><img src="' . base_url() . 'user_assets/images/fa-phone-2x.png" style="height:15px;width:15px;"> +917043215052 </p>
                
                </div>
                </div>
        <div style="padding:1.5%;width:100%;float:left;background-color:#fff;border:1px solid #f1f2f3;">
                <div style="padding:0 4%;">
                    <h4><b>Your Account Created Successfully</b></h4>
                       
                     <p style="color:#7e7e7e;font-size:13px;"> Your Email is: . ' . $email . '  </p>  
<p style="color:#7e7e7e;font-size:13px;"> Your Password is: ' . $password . '  </p>
        
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>
        </div>
        <div style="width:100%;float:left;background:#F5F5F5;padding:1.5%;color:#828282;font-size:12px;border:1px solid #dfdfdf;text-align:center;">
                <a href="' . base_url() . 'user_master/about_us" style="color:#515151;text-decoration:none;">About Us</a> |
                <a href="' . base_url() . 'user_master/contact_us" style="color:#515151;text-decoration:none;">Contact Us</a> |
                <a href="' . base_url() . 'user_login" style="color:#515151;text-decoration:none;">Login</a> |
                <a href="' . base_url() . 'user_master/collection" style="color:#515151;text-decoration:none;">Home-collection</a> |
                <a href="https://www.facebook.com/airmedpathlabs" style="color:#515151;text-decoration:none;"><i aria-hidden="true"><img src="' . base_url() . 'user_assets/images/face_book_icon1.jpeg" style="height:15px;width:15px;"></i></a>                  
        </div>
        <div style="width:100%;float:left;background:#333333;padding:1.5%;color:#ccc;font-size:12px;border:1px solid #323232;text-align:center;">
                Copyright @ 2016 AirmedLabs. All rights reserved
        </div>
    </div>
</div>';
        $this->email->to($email);
        $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
        $this->email->subject('Account Created Successfully');
        $this->email->message($message);
        $this->email->send();

        $this->session->set_flashdata("success", array("Customer successfully added."));
        redirect("job_master/Package_test_inquiry_list", "refresh");
    }

    function add_by_admin_for_book1() {

        $fname = $this->input->post('fname');
        $mobile = $this->input->post('mobile');
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $tmobile = $mobile;

        $data = array(
            "full_name" => $fname,
            "email" => $email,
            "mobile" => $mobile,
            "password" => $password,
            "active" => '1'
        );
//						 echo "<pre>"; print_r($data); die();
        $data['query'] = $this->customer_model->master_fun_insert("customer_master", $data);
        $data123 = $this->customer_model->master_fun_update("prescription_upload", array("mobile", $tmobile), array("cust_fk" => $data['query']));
        $config['mailtype'] = 'html';
        $this->email->initialize($config);

        $message = '<div style="background:#a3a3a3 !important;font-family: "Roboto",sans-serif;">
<script src="https://use.fontawesome.com/44049e3dc5.js"></script>
    <div style="background: #6b6b6b none repeat scroll 0 0;float: left;padding: 7%;">
        <div style="border:1px solid #f1f1f1;border-bottom:1px solid #c7c7c7;padding:1.5%;width:100%;float:left;background:#fff;">
            <div style="float:left;width:64%;">
                        <img alt="" src="' . base_url() . 'user_assets/images/logo.png" style="height: 100px;width:213px;"/>
            </div>
                <div style="float:right;text-align: right;width:33%;padding-top:7px;">

                <p style=" width:100%;color: #111111;font-weight: 500;float:right;margin:0px 0px 5px 0px;padding:0;"><img src="' . base_url() . 'user_assets/images/message.png" style="height:10px;width:10px;"> <a href="mailto:info@airmedpathlabs.com" style="text-decoration:none;color:#7e7e7e;">info@airmedpathlabs.com</p>
                <p style=" width:100%;color: #111111;font-weight: 500;float:right;margin:0;padding:0;"><img src="' . base_url() . 'user_assets/images/fa-phone-2x.png" style="height:15px;width:15px;"> +917043215052 </p>
                
                </div>
                </div>
        <div style="padding:1.5%;width:100%;float:left;background-color:#fff;border:1px solid #f1f2f3;">
                <div style="padding:0 4%;">
                    <h4><b>Your Account Created Successfully</b></h4>
                       
                     <p style="color:#7e7e7e;font-size:13px;"> Your Email is: . ' . $email . '  </p>  
<p style="color:#7e7e7e;font-size:13px;"> Your Password is: ' . $password . '  </p>
        
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>
        </div>
        <div style="width:100%;float:left;background:#F5F5F5;padding:1.5%;color:#828282;font-size:12px;border:1px solid #dfdfdf;text-align:center;">
                <a href="' . base_url() . 'user_master/about_us" style="color:#515151;text-decoration:none;">About Us</a> |
                <a href="' . base_url() . 'user_master/contact_us" style="color:#515151;text-decoration:none;">Contact Us</a> |
                <a href="' . base_url() . 'user_login" style="color:#515151;text-decoration:none;">Login</a> |
                <a href="' . base_url() . 'user_master/collection" style="color:#515151;text-decoration:none;">Home-collection</a> |
                <a href="https://www.facebook.com/airmedpathlabs" style="color:#515151;text-decoration:none;"><i aria-hidden="true"><img src="' . base_url() . 'user_assets/images/face_book_icon1.jpeg" style="height:15px;width:15px;"></i></a>                  
        </div>
        <div style="width:100%;float:left;background:#333333;padding:1.5%;color:#ccc;font-size:12px;border:1px solid #323232;text-align:center;">
                Copyright @ 2016 AirmedLabs. All rights reserved
        </div>
    </div>
</div>';
        $this->email->to($email);
        $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
        $this->email->subject('Account Created Successfully');
        $this->email->message($message);
        $this->email->send();

        $this->session->set_flashdata("successnewuser", "Customer successfully added.");
        redirect("job-master/prescription-report", "refresh");
    }

    function customer_all_details() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["cid"] = $this->uri->segment('3');
        $ccid = $this->uri->segment('3');
        $this->load->library('form_validation');

        $data['query'] = $this->customer_model->master_fun_get_tbl_val("customer_master", array("id" => $data["cid"]), array("id", "desc"));
        $data1 = $this->customer_model->get_all_job($data["cid"]);
        $newdata = array();
        foreach ($data1 as $key) {
            $key['test'] = (($key['test'] == "") ? "" : $key['test'] . ',' ) . $key['packge_name'];
            $newdata[] = $key;
        }
        $data['job'] = $newdata;
        $data['country'] = $this->customer_model->master_fun_get_tbl_val("country", array("status" => 1), array("id", "desc"));
        $data['state'] = $this->customer_model->master_fun_get_tbl_val("state", array("status" => 1), array("id", "desc"));
        $data['city'] = $this->customer_model->master_fun_get_tbl_val("city", array("status" => 1), array("id", "desc"));
        $data["family_member"]=$this->customer_model->get_user_family_member($data["cid"]);
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('customer_all_details', $data);
        $this->load->view('footer');
    }

}

?>
