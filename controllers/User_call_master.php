<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User_call_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('user_model');
        $this->load->model('user_call_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->helper('string');
        $this->load->library('email');
        $data["login_data"] = logindata();
       // $this->app_track();
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
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $data['unsuccess'] = $this->session->flashdata("unsuccess");
        /* 		$totalRows = $this->user_call_model->num_row('exotel_calls',array('status' => 1));
          $config = array();
          $config["base_url"] = base_url() . "user_call_master/index/";
          $config["total_rows"] = $totalRows;
          $config["per_page"] = 10;
          $config["uri_segment"] = 3;
          $this->pagination->initialize($config);
          $sort = $this->input->get("sort");
          $by = $this->input->get("by");
          $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
          $data["query"] = $this->user_call_model->user_list($config["per_page"], $page);
          $data["links"] = $this->pagination->create_links(); */
        /* New pagination with search start */
        $caller = $this->input->get('caller');
        $call_from = $this->input->get('call_from');
        $call_to = $this->input->get('call_to');
        $direction = $this->input->get('direction');
        $start_date = $this->input->get('start_date');
        $duration = $this->input->get('duration');
        $call_type = $this->input->get('call_type');
        $call_date = $this->input->get('call_date');
        $agent = $this->input->get('agent');
        $agent_number = $this->input->get('agent_number');
        $data['caller_fk'] = $caller;
        $data['call_from'] = $call_from;
        $data['call_to'] = $call_to;
        $data['direction'] = $direction;
        $data['start_date'] = $start_date;
        $data['duration'] = $duration;
        $data['call_type'] = $call_type;
        $data['call_date'] = $call_date;
        $data['agent'] = $agent;
        $data['agent_number'] = $agent_number;
        if ($caller != "" || $call_from != "" || $call_to != "" || $direction != "" || $start_date != "" || $duration != "" || $call_type != "" || $call_date != "" || $agent != "" || $agent_number != "") {
            $total_row = $this->user_call_model->num_row_srch_call_list($caller, $call_from, $call_to, $direction, $start_date, $duration, $call_type, $call_date, $agent, $agent_number);
            $config = array();
            $get = $_GET;
            unset($get['offset']);
            $config["base_url"] = base_url() . "user_call_master/index?" . http_build_query($get);
            $config["total_rows"] = $total_row;
            $config["per_page"] = 50;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->user_call_model->row_srch_call_list($caller, $call_from, $call_to, $direction, $start_date, $duration, $call_type, $call_date, $agent, $agent_number, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        } else {
            $total_row = $this->user_call_model->num_row('exotel_calls', array('status' => 1));
            $config["base_url"] = base_url() . "user_call_master/index";
            $config["total_rows"] = $total_row;
            $config["per_page"] = 50;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->user_call_model->srch_call_list($config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        }
        /* New pagination with search end */
        $data['customer_list'] = $this->user_call_model->master_fun_get_tbl_val('customer_master', array('status' => 1, 'active' => 1), array('id', 'asc'));
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('user_call_list', $data);
        $this->load->view('footer');
    }

    function send_quote_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $data['unsuccess'] = $this->session->flashdata("unsuccess");
        /* 		$totalRows = $this->user_call_model->num_row('exotel_calls',array('status' => 1));
          $config = array();
          $config["base_url"] = base_url() . "user_call_master/index/";
          $config["total_rows"] = $totalRows;
          $config["per_page"] = 10;
          $config["uri_segment"] = 3;
          $this->pagination->initialize($config);
          $sort = $this->input->get("sort");
          $by = $this->input->get("by");
          $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
          $data["query"] = $this->user_call_model->user_list($config["per_page"], $page);
          $data["links"] = $this->pagination->create_links(); */
        /* New pagination with search start */
        $caller = $this->input->get('caller');
        $call_from = $this->input->get('call_from');
        $call_to = $this->input->get('call_to');
        $direction = $this->input->get('direction');
        $start_date = $this->input->get('start_date');
        $duration = $this->input->get('duration');
        $call_type = $this->input->get('call_type');
        $call_date = $this->input->get('call_date');
        $agent = $this->input->get('agent');
        $agent_number = $this->input->get('agent_number');
        $data['caller_fk'] = $caller;
        $data['call_from'] = $call_from;
        $data['call_to'] = $call_to;
        $data['direction'] = $direction;
        $data['start_date'] = $start_date;
        $data['duration'] = $duration;
        $data['call_type'] = $call_type;
        $data['call_date'] = $call_date;
        $data['agent'] = $agent;
        $data['agent_number'] = $agent_number;
        if ($caller != "" || $call_from != "" || $call_to != "" || $direction != "" || $start_date != "" || $duration != "" || $call_type != "" || $call_date != "" || $agent != "" || $agent_number != "") {
            $total_row = $this->user_call_model->num_row_srch_quote_list($caller, $call_from, $call_to, $direction, $start_date, $duration, $call_type, $call_date, $agent, $agent_number);
            $config = array();
            $get = $_GET;
            unset($get['offset']);
            $config["base_url"] = base_url() . "user_call_master/index?" . http_build_query($get);
            $config["total_rows"] = $total_row;
            $config["per_page"] = 50;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->user_call_model->row_srch_call_list($caller, $call_from, $call_to, $direction, $start_date, $duration, $call_type, $call_date, $agent, $agent_number, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        } else {
            $total_row = $this->user_call_model->srch_quote_num();
            $config["base_url"] = base_url() . "user_call_master/send_quote_list";
            $config["total_rows"] = count($total_row);
            $config["per_page"] = 10;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->user_call_model->srch_quote_list($config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        }
        /* New pagination with search end */
        $new_array = array();
        foreach ($data['query'] as $q_key) {
            $test = explode(",", $q_key["test"]);
            $test_details = array();
            foreach ($test as $key) {
                $tn = explode("-", $key);
                if ($tn[0] == 't') {
                    $result = $this->user_call_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $q_key["test_city"] . "' AND `test_master`.`id`='" . $tn[1] . "'");
                    $test_details[] = $result[0];
                }
            }
            $test_city = $this->user_call_model->get_val("select name from test_cities where id='" . $q_key["test_city"] . "'");
            $q_key["test_city_name"] = $test_city[0]["name"];
            $q_key["test_details"] = $test_details;
            $new_array[] = $q_key;
        }
        $data["query"] = $new_array;
        //echo "<pre>";print_R($data['query']);die();
        $data['customer_list'] = $this->user_call_model->master_fun_get_tbl_val('customer_master', array('status' => 1, 'active' => 1), array('id', 'asc'));
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('send_quote_list', $data);
        $this->load->view('footer');
    }

    function delete_quote($id = null) {
        if ($id != null) {
            $insert = $this->user_call_model->master_fun_update1('tele_caller_send_quote', array("id" => $id), array("status" => "0"));
            $this->session->set_flashdata("success", array("Record successfully deleted."));
        } else {
            $this->session->set_flashdata("unsuccess", array("Try again!"));
        }
        redirect("user_call_master/send_quote_list");
    }

    function call_details() {
        $running_call = $this->user_call_model->user_running_call();
        if ($running_call != NULL) {
            echo "<div class='col-md-6'>";
            echo "<p><h4> Call Details</h4></p>";
            echo "<p><b> Mobile Number </b> <span><strong>:</strong></span>" . $running_call[0]["CallFrom"] . "</p>";
            echo "</div>";
        } else {
            echo "";
        }
    }

    function show_call_detail_popup() {
        $data["login_data"] = logindata();
        $html = '';
        $running_call = $this->user_call_model->user_running_call($data["login_data"]["email"]);
        //$numbers = '"' . substr($running_call[0]->CallFrom, 1) . '"';
        if (substr($running_call[0]->CallFrom, 0, 3) === '079') {
            $org_number = $running_call[0]->CallFrom;
            $numbers = '"' . $running_call[0]->CallFrom . '"';
        } else {
            $org_number = substr($running_call[0]->CallFrom, 1);
            $numbers = '"' . substr($running_call[0]->CallFrom, 1) . '"';
        }
        /* Nishit code start */
        $cities = $this->user_call_model->master_fun_get_tbl_val("test_cities", array("status" => 1), array("name", "asc"));
        /* Nishit code end */
        $data["is_no"] = 0;
        $calls = $this->user_call_model->master_fun_get_tbl_val('exotel_calls', array('status' => 1, 'CallFrom' => $running_call[0]->CallFrom), array('id', 'asc'));
        if ($running_call[0]->maxid != "") {
            $register = $this->user_call_model->num_row('customer_master', array('status' => 1, 'mobile' => $org_number));
            if ($register == 0) {
                $con_info = $this->user_call_model->num_row('contact_information', array('status' => 1, 'mobile' => $org_number));
                if ($con_info == 0) {
                    /* Nishit changes start */
                    $welcome_msg = $this->user_call_model->master_fun_get_tbl_val('sms_master', array('status' => 1, 'title' => "welcome_message"), array('id', 'asc'));
                    $welcome_msg_cnt = $this->user_call_model->num_row('admin_alert_sms', array('mobile_no' => $running_call[0]->CallFrom));
                    if ($welcome_msg_cnt == 0) {
                        $this->user_call_model->master_fun_insert('admin_alert_sms', array('mobile_no' => $running_call[0]->CallFrom, 'message' => $welcome_msg[0]["message"], "created_date" => date("y-m-d H:i:s")));
                    }
                    /* Nishit changes end */
                    $html .= "<h3 class='control-sidebar-heading'>Calling Details</h3><ul class='control-sidebar-menu'>
              <li>
                <a href='javascript::;'>
				<i class='menu-icon fa fa-mobile bg-light-blue'></i>
                  <div class='menu-info'>
                    <h4 class='control-sidebar-subheading'>Mobile Number</h4>
                    <p id='call_number'>" . $running_call[0]->CallFrom . "</p>
					<input type='hidden' id='call_number1' value='" . $org_number . "' >
                                            <input type='hidden' id='caller_id' value='" . $running_call[0]->id . "' >
                  </div>
                </a>
              </li>
              <li>
                <a href='javascript::;'>
				<i class='menu-icon fa fa-clock-o bg-light-blue'></i>
                  <div class='menu-info'>
                    <h4 class='control-sidebar-subheading'>Calling Time</h4>
                    <p>" . date("H:i:s", strtotime($running_call[0]->StartTime)) . "</p>
                  </div>
                </a>
              </li>
            </ul>";
                    $html .= '<h3 class="control-sidebar-heading">Send Quote</h3>';
                    $html .= '<div id="alert_msg"></div>';
                    $html .= '<div class="form-group">
                <label class="control-sidebar-subheading">Select City<select class="pull-right" onchange="get_packages123(this.value);" id="send_quote_test_city">';
                    $html .= '<option value="">--Select--</option>';
                    foreach ($cities as $c_key) {
                        $html .= '<option value="' . $c_key["id"] . '">' . ucfirst($c_key["name"]) . '</option>';
                    }
                    $html .= '</select></label>
              </div> <div id="telicaller_test_list"></div>';
                    $html .= '<button type="button" class="btn btn-sm btn-primary pull-right" id="send_quotte_btn" onclick="get_select_value1();" disabled>Send</button>';
                    $html .= '<span id="loader_div2" style="display:none;"><img src="' . base_url() . 'upload/opc-ajax-loader.gif" style="height:28px;margin-left:10px"></span>';
                    $html .= "<h3 class='control-sidebar-heading'>User Information</h3><div class='form-group'>
                <label class='control-sidebar-subheading'>
                  Name
				  <input type='text' class='pull-right'
                      id='name12'/>
                </label>
              </div>
              <div class='form-group'>
                <label class='control-sidebar-subheading'>
                  Mobile
                  <input type='text' class='pull-right' id='mobile' value='" . $org_number . "' disabled/>
                      <input type='hidden' class='pull-right' id='popup_mobile' value='" . $org_number . "' />
                </label>
              </div>
              <div class='form-group'>
                <label class='control-sidebar-subheading'>
                  Email
                  <input type='text' class='pull-right' id='email123'/>
				  
                </label>
              </div>
              <div class='form-group'>
                <label class='control-sidebar-subheading'>
                  <button type='button' class='btn btn-sm btn-primary pull-right' onclick='contact_info();'>Submit</button>
                </label>                
              </div>";

                    if (count($calls) > 0) {
                        $html .= "<h3 class='control-sidebar-heading'>Call History</h3>";
                        $cnt = 1;
                        foreach ($calls as $call) {
                            if (substr($call['CallFrom'], 0, 3) === '079') {
                                $org_number3 = $call['CallFrom'];
                            } else {
                                $org_number3 = substr($call['CallFrom'], 1);
                            }
                            $html .= "<ul class='control-sidebar-menu'>
              <li>
		<a href='" . base_url() . "user_call_master/index?call_from=" . $org_number3 . "' target='blank_'>
				<i class='menu-icon bg-light-blue'>" . $cnt . "</i>
                  <div class='menu-info'>
                    <h4 class='control-sidebar-subheading'>" . date("l jS \of F Y h:i:s A", strtotime($call['StartTime'])) . "</h4>
                    <p> (Agent Number) " . $call['DialWhomNumber'] . "</p>
                  </div>
                </a>
              </li>
            </ul>";
                            $cnt++;
                        }
                    }
                } else {
                    $contact_info = $this->user_call_model->master_fun_get_tbl_val('contact_information', array('status' => 1, 'mobile' => $org_number), array('id', 'asc'));
                    $html .= "<h3 class='control-sidebar-heading'>Calling Details(From Contact DB)</h3><ul class='control-sidebar-menu'>
              <li>
                <a href='javascript::;'>
				<i class='menu-icon fa fa-mobile bg-light-blue'></i>
                  <div class='menu-info'>
                    <h4 class='control-sidebar-subheading'>Mobile Number</h4>
                    <p>" . $running_call[0]->CallFrom . "</p>
                        <input type='hidden' class='pull-right' id='popup_mobile' value='" . $running_call[0]->CallFrom . "' />
					<input type='hidden' id='call_number1' value='" . $org_number . "' >
                                            <input type='hidden' id='caller_id' value='" . $running_call[0]->id . "' >
                  </div>
                </a>
              </li>
              <li>
                <a href='javascript::;'>
				<i class='menu-icon fa fa-clock-o bg-light-blue'></i>
                  <div class='menu-info'>
                    <h4 class='control-sidebar-subheading'>Calling Time</h4>
                    <p>" . date("H:i:s", strtotime($running_call[0]->StartTime)) . "</p>
                  </div>
                </a>
              </li>
			  <li>
                <a href='javascript::;'>
				<i class='menu-icon fa fa-user bg-light-blue'></i>
                  <div class='menu-info'>
                    <h4 class='control-sidebar-subheading'>Caller Name</h4>
                    <p>" . $contact_info[0]["name"] . "</p>
                  </div>
                </a>
              </li>";
                    if ($contact_info[0]["email"] != NULL) {
                        $html .= "<li>
                <a href='javascript::;'>
				<i class='menu-icon fa fa-envelope-o bg-light-blue'></i>
                  <div class='menu-info'>
                    <h4 class='control-sidebar-subheading'>Email</h4>
                    <p>" . $contact_info[0]["email"] . "</p>
                  </div>
                </a>
              </li>";
                    }
                    $html .= "<li>
                <a href='javascript:void(0);' class='fillInfoClass' onclick=\"setFormValue('" . $contact_info[0]["name"] . "','" . $contact_info[0]["email"] . "','" . $contact_info[0]["mobile"] . "');\">
				Fill Details
				</a>
              </li>";

                    $html .= "</ul><div class='form-group'>
                <label class='control-sidebar-subheading'>
                  <button type='button' class='btn btn-sm btn-primary pull-right' onclick='show_user_register(" . $numbers . ");'>Register</button>
                </label>                
              </div>";
                    $html .= '<h3 class="control-sidebar-heading">Send Quote</h3>';
                    $html .= '<div id="alert_msg"></div>';
                    $html .= '<div class="form-group">
                <label class="control-sidebar-subheading">Select City<select class="pull-right" onchange="get_packages123(this.value);" id="send_quote_test_city">';
                    $html .= '<option value="">--Select--</option>';
                    foreach ($cities as $c_key) {
                        $html .= '<option value="' . $c_key["id"] . '">' . ucfirst($c_key["name"]) . '</option>';
                    }
                    $html .= '</select></label>
              </div> <div id="telicaller_test_list"></div>';
                    $html .= '<button type="button" class="btn btn-sm btn-primary pull-right" id="send_quotte_btn" onclick="get_select_value1();" disabled>Send</button>';
                    $html .= '<span id="loader_div2" style="display:none;"><img src="' . base_url() . 'upload/opc-ajax-loader.gif" style="height:28px;margin-left:10px"></span>';
                    if (count($calls) > 0) {
                        $html .= "<h3 class='control-sidebar-heading'>Call History</h3>";
                        $cnt = 1;
                        foreach ($calls as $call) {
                            if (substr($call['CallFrom'], 0, 3) === '079') {
                                $org_number2 = $call['CallFrom'];
                            } else {
                                $org_number2 = substr($call['CallFrom'], 1);
                            }
                            $html .= "<ul class='control-sidebar-menu'>
              <li>
                <a href='" . base_url() . "user_call_master/index?call_from=" . $org_number2 . "' target='blank_'>
				<i class='menu-icon bg-light-blue'>" . $cnt . "</i>
                  <div class='menu-info'>
                    <h4 class='control-sidebar-subheading'>" . date("l jS \of F Y h:i:s A", strtotime($call['StartTime'])) . "</h4>
                    <p> (Agent Number) " . $call['DialWhomNumber'] . "</p>
                  </div>
                </a>
              </li>
            </ul>";
                            $cnt++;
                        }
                    }
                }
            } else {
                $customer_details = $this->user_call_model->master_fun_get_tbl_val('customer_master', array('status' => 1, 'mobile' => $org_number), array('id', 'asc'));
                $orders = $this->user_call_model->master_fun_get_tbl_val('job_master', array('cust_fk' => $customer_details[0]["id"]), array('id', 'asc'));
                $prescriptions = $this->user_call_model->master_fun_get_tbl_val('prescription_upload', array('cust_fk' => $customer_details[0]["id"]), array('id', 'asc'));
                $html .= "<h3 class='control-sidebar-heading'>Calling Details (Registered User)</h3><ul class='control-sidebar-menu'>
              <li>
                <a href='javascript::;'>
				<i class='menu-icon fa fa-mobile bg-light-blue'></i>
                  <div class='menu-info'>
                    <h4 class='control-sidebar-subheading'>Mobile Number</h4>
                    <p>" . $running_call[0]->CallFrom . "</p>
					<input type='hidden' id='call_number1' value='" . $org_number . "' >
                                            <input type='hidden' class='pull-right' id='popup_mobile' value='" . $org_number . "' />
                                            <input type='hidden' id='caller_id' value='" . $running_call[0]->id . "' >
                  </div>
                </a>
              </li>
              <li>
                <a href='javascript::;'>
				<i class='menu-icon fa fa-clock-o bg-light-blue'></i>
                  <div class='menu-info'>
                    <h4 class='control-sidebar-subheading'>Calling Time</h4>
                    <p>" . date("H:i:s", strtotime($running_call[0]->StartTime)) . "</p>
                  </div>
                </a>
              </li>
			  <li>
                <a href='javascript::;' onclick='show_caller_reg_details(" . $numbers . ");'>
				<i class='menu-icon fa fa-user bg-light-blue'></i>
                  <div class='menu-info'>
                    <h4 class='control-sidebar-subheading'>Caller Name</h4>
                    <p id='caller_name_print'>" . $customer_details[0]["full_name"] . "</p>
                  </div>
                </a>
              </li>
			  <li>
                <a href='javascript::;'>
				<i class='menu-icon fa fa-envelope-o bg-light-blue'></i>
                  <div class='menu-info'>
                    <h4 class='control-sidebar-subheading'>Email</h4>
                    <p>" . $customer_details[0]["email"] . "</p>
                  </div>
                </a>
              </li>
			  <li>
                <a href='javascript:void(0);' class='fillInfoClass' onclick=\"document.getElementById('existingCustomer').value=" . $customer_details[0]['id'] . ";$('#existingCustomer').trigger('chosen:updated');getDetailsByid(cid);\">
				Fill Details
				</a>
              </li>
            </ul>";
                $html .= '<h3 class="control-sidebar-heading">Send Quote</h3>';
                $html .= '<div id="alert_msg"></div>';
                $html .= '<div class="form-group">
                <label class="control-sidebar-subheading">Select City<select class="pull-right" onchange="get_packages123(this.value);" id="send_quote_test_city">';
                $html .= '<option value="">--Select--</option>';
                foreach ($cities as $c_key) {
                    $html .= '<option value="' . $c_key["id"] . '">' . ucfirst($c_key["name"]) . '</option>';
                }
                $html .= '</select></label>
              </div> <div id="telicaller_test_list"></div>';
                $html .= '<button type="button" class="btn btn-sm btn-primary pull-right" id="send_quotte_btn" onclick="get_select_value1();" disabled>Send</button>';
                $html .= '<span id="loader_div2" style="display:none;"><img src="' . base_url() . 'upload/opc-ajax-loader.gif" style="height:28px;margin-left:10px"></span>';
                if (count($orders) > 0) {
                    $html .= "<h3 class='control-sidebar-heading'>Order History</h3>";
                    $cnt = 1;
                    foreach ($orders as $order) {
                        $details = $this->get_job_details($order['id']);
                        $html .= "<ul class='control-sidebar-menu'>
              <li>
                <a href='" . base_url() . "job-master/job-details/" . $order['id'] . "' target='blank_'>
				<i class='menu-icon bg-light-blue'>" . $cnt . "</i>
                  <div class='menu-info'>
                    <h4 class='control-sidebar-subheading'>" . $order['order_id'] . "<span class='pull-right'> Rs. " . $order['payable_amount'] . "</span></h4>
                    <p>" . date("d-m-Y", strtotime($order['date'])) . "</p>";
                        if (!empty($details[0]['book_test'])) {
                            foreach ($details[0]['book_test'] as $test_pr) {
                                $html .= "<p>" . $test_pr['test_name'] . "<span class='pull-right'> Rs. " . $test_pr['price'] . "</span></p>";
                            }
                        }
                        if (!empty($details[0]['book_package'])) {
                            foreach ($details[0]['book_package'] as $package_pr) {
                                $html .= "<p>" . $package_pr['title'] . "<span class='pull-right'> Rs. " . $package_pr['d_price'] . "</span></p>";
                            }
                        }
                        $html .= "</div>
                </a>
              </li>
            </ul>";
                        $cnt++;
                    }
                }
                if (count($prescriptions) > 0) {
                    $html .= "<h3 class='control-sidebar-heading'>Prescription History</h3>";
                    $cnt = 1;
                    foreach ($prescriptions as $prescription) {
                        $html .= "<ul class='control-sidebar-menu'>
              <li>
                <a href='" . base_url() . "job-master/prescription-details/" . $prescription['id'] . "' target='blank_'>
				<i class='menu-icon bg-light-blue'>" . $cnt . "</i>
                  <div class='menu-info'>
                    <h4 class='control-sidebar-subheading'>" . $prescription['order_id'] . "</h4>
                    <p>" . date("d-m-Y", strtotime($prescription['created_date'])) . "</p>
                  </div>
                </a>
              </li>
            </ul>";
                        $cnt++;
                    }
                }
                if (count($calls) > 0) {
                    $html .= "<h3 class='control-sidebar-heading'>Call History</h3>";
                    $cnt = 1;
                    foreach ($calls as $call) {
                        if (substr($call['CallFrom'], 0, 3) === '079') {
                            $org_number1 = $call['CallFrom'];
                        } else {
                            $org_number1 = substr($call['CallFrom'], 1);
                        }
                        $html .= "<ul class='control-sidebar-menu'>
              <li>
                <a href='" . base_url() . "user_call_master/index?call_from=" . $org_number1 . "' target='blank_'>
				<i class='menu-icon bg-light-blue'>" . $cnt . "</i>
                  <div class='menu-info'>
                    <h4 class='control-sidebar-subheading'>" . date("l jS \of F Y h:i:s A", strtotime($call['StartTime'])) . "</h4>
                    <p> (Agent Number) " . $call['DialWhomNumber'] . "</p>
                  </div>
                </a>
              </li>
            </ul>";
                        $cnt++;
                    }
                }
            }
        } else {
            $data["is_no"] = 1;
            $html .= "no";
        }

        $data['html'] = $html;
        $this->load->view("show_call_detail_popup", $data);
    }

    function tele_caller_send_quote() {
        $tid = $this->input->post("id");
        $test_city = $this->input->post("test_city");
        $total_price = $this->input->post("total_price");
        $mobile_no = $this->input->post("mobile_no");
        $caller_id = $this->input->post("caller_id");
        $data["login_data"] = logindata();
        $data = array("mobile_no" => $mobile_no, "test" => implode(",", $tid), "test_city" => $test_city, "caller_id" => $caller_id, "send_by" => $data["login_data"]["id"], "price" => $total_price, "createddate" => date("Y-m-d H:i:s"), "is_Send" => "1");
        $insert = $this->user_call_model->master_fun_insert('tele_caller_send_quote', $data);
        $test_name = array();
        foreach ($tid as $t_key) {
            $test_id = explode("-", $t_key);
            $tst_prc = $this->user_call_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $test_city . "' AND `test_master`.`id`='" . $test_id[1] . "'");
            $test_name[] = $tst_prc[0]["test_name"] . " Rs." . $tst_prc[0]["price"];
        }
        /* Nishit send sms code start */
        $cname = "Customer";
        $register = $this->user_call_model->master_fun_get_tbl_val('customer_master', array('status' => 1, 'mobile' => $mobile_no), array("id", "desc"));
        if (count($register) == 0) {
            $con_info = $this->user_call_model->master_fun_get_tbl_val('contact_information', array('status' => 1, 'mobile' => $mobile_no), array("id", "desc"));
            if (count($con_info) == 0) {
                $cname = "Customer";
            } else {
                $cname = $con_info[0]["name"];
            }
        } else {
            $cname = $register[0]["full_name"];
        }
        $sms_message = $this->user_call_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "send_quoe"), array("id", "asc"));
        $sms_message = preg_replace("/{{TEST}}/", implode(",", $test_name), $sms_message[0]["message"]);
        $sms_message = preg_replace("/{{CNAME}}/", $cname, $sms_message);
        $sms_message = preg_replace("/{{PRICE}}/", "Rs." . $total_price, $sms_message);
        $this->load->helper("sms");
        $notification = new Sms();
        $notification::send($mobile_no, $sms_message);
        /* Nishit send sms code end */
        if ($insert) {
            echo json_encode(array("status" => "1"));
        } else {
            echo json_encode(array("status" => "0"));
        }
    }

    function save_quote_chages() {
        $tid = $this->input->post("id");
        $test_city = $this->input->post("test_city");
        $total_price = $this->input->post("total_price");
        $mobile_no = $this->input->post("mobile_no");
        $caller_id = $this->input->post("caller_id");
        $data["login_data"] = logindata();
        $data = array("mobile_no" => $mobile_no, "test" => implode(",", $tid), "test_city" => $test_city, "caller_id" => $caller_id, "send_by" => $data["login_data"]["id"], "price" => $total_price, "createddate" => date("Y-m-d H:i:s"), "is_Send" => "0");
        $check = $this->user_call_model->master_fun_get_tbl_val("tele_caller_send_quote", array('status' => 1, "mobile_no" => $mobile_no, "caller_id" => $caller_id, "is_Send" => "0"), array("id", "asc"));

        if (count($check) == 0) {
            $insert = $this->user_call_model->master_fun_insert('tele_caller_send_quote', $data);
        } else {
            $insert = $this->user_call_model->master_fun_update1('tele_caller_send_quote', array("caller_id" => $caller_id, "is_Send" => "0"), $data);
        }
        if ($insert) {
            echo json_encode(array("status" => "1"));
        } else {
            echo json_encode(array("status" => "0"));
        }
    }

    function package_list1() {
        $city = $this->input->get_post("city");
        if ($city == '') {
            $city = 1;
        }
        /* Nishit code start */
        $data["data"] = $this->user_call_model->master_fun_get_tbl_val("package_master", array("status" => 1), array("title", "asc"));
        $data['test'] = $this->user_call_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $city . "'");
        $data['package'] = $this->user_call_model->master_fun_get_tbl_val("package_master", array("status" => 1), array("title", "asc"));
        $data['popular'] = $this->user_call_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $data["test_city_session"] . "' AND test_master.`popular`='1'");
        /* 25-10 */
        $this->load->view("HomePageSearch", $data);
        /* 25-10 */
    }

    function insert_contact_info() {
        $name = $this->input->post("namen");
        $mobile = $this->input->post("mobile");
        $email = $this->input->post("emaile");
        $data = array("name" => $name, "mobile" => $mobile, "email" => $email);
        $insert = $this->user_call_model->master_fun_insert('contact_information', $data);
        if ($insert) {
            echo $insert;
        }
    }

    function show_user_detail_popup() {
        $mobile = $this->input->post("mobile");
        $user_detail = $this->user_call_model->master_fun_get_tbl_val('customer_master', array('status' => 1, 'mobile' => $mobile), array('id', 'asc'));
        $city = $this->user_call_model->master_fun_get_tbl_val("test_cities", array("status" => 1, "id" => $user_detail[0]["city"]), array("id", "desc"));
        echo "<div class='col-md-12'>";
        echo "<div class='col-md-6'>";
        echo "<p><b> Name </b><span><strong>:</strong></span>" . ucfirst($user_detail[0]["full_name"]) . "</p>";
        echo "<p><b> Gender </b><span><strong>:</strong></span>" . ucfirst($user_detail[0]["gender"]) . "</p>";
        echo "<p><b> Mobile </b><span><strong>:</strong></span>" . $user_detail[0]["mobile"] . "</p>";
        if (!empty($city)) {
            echo "<p><b> City </b><span><strong>:</strong></span>" . ucfirst($city[0]["name"]) . "</p>";
        }
        echo "<p><b> Email </b><span><strong>:</strong></span>" . $user_detail[0]["email"] . "</p>";
        echo "</div><div class='col-md-6'>";
        if ($user_detail[0]['pic'] != NULL) {
            echo "<p><b> Profile </b><span><strong>:</strong></span><br>";
            if ($user_detail[0]['password'] == NULL || $user_detail[0]['type'] == '2') {
                echo "<img src='" . $user_detail[0]['pic'] . "' height='200px' width='200px'/></p>";
            } else {
                echo "<img src='" . base_url() . "upload/" . $query[0]['pic'] . "' height='200px' width='200px'/></p>";
            }
        }
        echo "</div></div>";
    }

    function show_user_register_modal() {
        $mobile = $this->input->post("mobile");
        $citys = $this->user_call_model->master_fun_get_tbl_val("test_cities", array("status" => 1), array("id", "desc"));
        $contact_details = $this->user_call_model->master_fun_get_tbl_val("contact_information", array("status" => 1, "mobile" => $mobile), array("id", "desc"));
        echo "<div class='login_light_back'>
			<div class='col-sm-12 pdng_0 mrgn_btm_25px'>
				<div class='input-group'>
					<span style='' class='input-group-addon'><i class='fa fa-user'></i></span>
					<input type='text' name='re_name' id='re_name_call' class='form-control' value='" . $contact_details[0]['name'] . "'>
				</div>
				<span class='spn_red' id='reg_name_call_error'></span>
			</div>
			<div class='col-sm-12 pdng_0 mrgn_btm_25px'>
				<div class='input-group'>
					<span style='' class='input-group-addon'><i class='fa fa-envelope'></i></span>
					<input type='text' name='re_email' id='re_email_call' class='form-control'  value='" . $contact_details[0]['email'] . "'>
				</div>
				<span class='spn_red' id='reg_email_call_error'></span>
			</div>
			<div class='col-sm-12 pdng_0 mrgn_btm_25px'>
				<div class='input-group'>
					<span class='input-group-addon pkgdtl_spn_91'>+91</span>
					<input type='text' class='form-control' name='re_mobile' id='re_mobile_call' value='" . $contact_details[0]['mobile'] . "'>
				</div>
				<span class='spn_red' id='reg_mobile_call_error'></span>
			</div>
			<div class='col-sm-12 pdng_0 mrgn_btm_25px'>
				<div class='input-group rgstr_slct'>
					<select name='re_city' class='form-control' id='re_city_call'>
						<option value=''>--Select Test City--</option>";
        foreach ($citys as $city) {
            echo "<option value='" . $city["id"] . "'>" . $city["name"] . "</option>";
        }

        echo "</select>
				</div>
				<span class='spn_red' id='reg_city_call_error'></span>
			</div>
			<div class='col-sm-12 pdng_0 mrgn_btm_25px'>
				<div class='input-group'>
					<span style='' class='input-group-addon'><i class='fa fa-pencil'></i></span>
					<input type='text' placeholder='Enter Refer Code' class='form-control' name='re_refer_code' id='re_refer_code_call'>
				</div>
			</div>
			<div class='col-sm-12 pdng_0 mrgn_btm_25px'>
				<div class='form-group mrgn_0'>
					<div class='form-group mrgn_0 regstr_gendr_full'>
						<div class='regstr_male'>
							<input type='radio' value='male' name='re_gender' id='re_gender_call'>Male
						</div>
						<div class='regstr_male'>
							<input type='radio' value='female' name='re_gender' id='re_gender_call'>Female
						</div>
					</div>
				</div>
				<span class='spn_red' id='reg_gender_call_error'></span>
			</div>
		</div>";
    }

    function insert_user_register_info() {
        $this->load->helper("Email");
        $email_cnt = new Email;

        $name = $this->input->post("name");
        $email = $this->input->post("email");
        $mobile = $this->input->post("mobile");
        $city = $this->input->post("city");
        $gender = $this->input->post("gender");
        $refercode = $this->input->post("refercode");
        $confirm_code = random_string('alnum', 6);
        $password = mt_rand(11111111, 99999999);
        $data = array("full_name" => $name, "email" => $email, "mobile" => $mobile, "city" => $city, "gender" => $gender, "password" => $password, "status" => '1');
        $insert = $this->user_call_model->master_fun_insert('customer_master', $data);
        if ($refercode != NULL) {
            $rendom_code = random_string('alnum', 6);
            $data1 = array("cust_fk" => $insert, "used_code" => $refercode, "refer_code" => $rendom_code, "status" => '1');
            $this->user_call_model->master_fun_insert('refer_code_master', $data1);
        }
        if ($insert) {
            $config['mailtype'] = 'html';

            $this->email->initialize($config);
            $message = '<div style="padding:0 4%;">
                    <h4><b>Confirm Your Register</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;font-weight: bold;">Dear ' . $name . ',</p>
                        <p style="color:#7e7e7e;font-size:13px;">Dear ' . $name . ' your Password is ' . $password . '.</p>
                        <p style="color:#7e7e7e;font-size:13px;">Please confirm Your Email to get all services provided by Airmed PATH LAB</p>
								<a href="' . base_url() . 'register/confirm_email/' . $confirm_code . '" style="background: #D01130;color: #f9f9f9;padding: 1%;text-decoration: none;">Confirm</a>

                </div>';
            $message = $email_cnt->get_design($message);
            $this->email->to($email);
            $this->email->from("donotreply@airmedpathlabs.com", 'AirmedLabs');
            $this->email->subject("Please Confirm Your Register for AirmedLabs");
            $this->email->message($message);
            echo $insert;
        }
    }

    function change_call_status() {
        echo $ids = $this->input->get_post("ids");
        $this->user_call_model->master_fun_update('exotel_calls', array('id', $ids), array("AgentStatus" => "free"));
        return true;
    }

    function export_csv() {
        $result = $this->user_call_model->call_list();
        $data['city'] = $this->user_call_model->master_fun_get_tbl_val("city", array('status' => 1), array("id", "asc"));
        $cnt = 0;
        foreach ($result as $key) {
            $customer_info = $this->user_call_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "mobile" => substr($key["CallFrom"], 1)), array("id", "asc"));
            if (!empty($customer_info)) {
                $result[$cnt]["c_name"] = $customer_info[0]["full_name"];
            } else {
                $result[$cnt]["c_name"] = "";
            }
            $cnt++;
        }
        //echo "<pre>"; print_R($result); die();
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"Caller_Report-" . date('d-M-Y') . ".csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("Caller Name", "Call From"));

        foreach ($result as $key) {
            fputcsv($handle, array($key["CallFrom"], $key["c_name"]));
        }
        fclose($handle);
        exit;
    }

    function get_job_details($job_id) {
        $this->load->model('job_model');
        $job_details = $this->job_model->master_fun_get_tbl_val("job_master", array("status !=" => 0, "id" => $job_id), array("id", "asc"));
        if (!empty($job_details)) {
            $book_test = $this->job_model->master_fun_get_tbl_val("job_test_list_master", array("job_fk" => $job_id), array("id", "desc"));
            $book_package = $this->job_model->master_fun_get_tbl_val("book_package_master", array("job_fk" => $job_id, "status" => "1"), array("id", "desc"));
            $test_name = array();
            foreach ($book_test as $key) {
                $price1 = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $job_details[0]['test_city'] . "' AND `test_master`.`id`='" . $key["test_fk"] . "'");
                $test_name[] = $price1[0];
            }
            $job_details[0]["book_test"] = $test_name;
            $package_name = array();
            foreach ($book_package as $key) {

                $price1 = $this->job_model->get_val("SELECT
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

    function send_quotation() {
        $data["caller_id"] = $this->uri->segment(3);
        if ($data["caller_id"] != null) {
            $quote_list = $this->user_call_model->master_fun_get_tbl_val("tele_caller_send_quote", array("caller_id" => $data["caller_id"], "status" => 1), array("id", "desc"));
            $new_array = array();
            foreach ($quote_list as $q_key) {
                $test = explode(",", $q_key["test"]);
                $test_details = array();
                foreach ($test as $key) {
                    $tn = explode("-", $key);
                    if ($tn[0] == 't') {
                        $result = $this->user_call_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $q_key["test_city"] . "' AND `test_master`.`id`='" . $tn[1] . "'");
                        $test_details[] = $result[0];
                    }
                }
                $test_city = $this->user_call_model->get_val("select name from test_cities where id='" . $q_key["test_city"] . "'");
                $q_key["test_city_name"] = $test_city[0]["name"];
                $q_key["test_details"] = $test_details;
                $new_array[] = $q_key;
            }
            $data["query"] = $new_array;
            $data["test_city"] = $this->user_call_model->get_val("select id,name from test_cities where status='1'");
            $data["pending_quotation"] = $this->user_call_model->get_val("select * from tele_caller_send_quote where status='1' AND caller_id='" . $data["caller_id"] . "' and is_send='0'");
            $data["cller_details"] = $this->user_call_model->get_val("select * from exotel_calls where id='" . $data["caller_id"] . "'");
            //print_r($data); die();
            $this->load->view("send_quotation", $data);
        } else {
            echo show_error("Oops somthing wrong Try again.");
        }
    }

    function send_quotation1() {
        $tid = $this->input->post("test");
        if (!empty($tid)) {
            $test_city = $this->input->post("test_city");
            $total_price = $this->input->post("total_price");
            $mobile_no = $this->input->post("mobile_no");
            $caller_id = $this->input->post("caller_id");
            $data["login_data"] = logindata();
            $data = array("mobile_no" => $mobile_no, "test" => implode(",", $tid), "test_city" => $test_city, "caller_id" => $caller_id, "send_by" => $data["login_data"]["id"], "price" => $total_price, "createddate" => date("Y-m-d H:i:s"), "is_send" => "1");
            $insert = $this->user_call_model->master_fun_insert('tele_caller_send_quote', $data);
            $this->user_call_model->master_fun_update1('tele_caller_send_quote', array("caller_id" => $caller_id, "is_send" => "0"), array("status" => "0"));
            $test_name = array();
            foreach ($tid as $t_key) {
                $test_id = explode("-", $t_key);
                $tst_prc = $this->user_call_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $test_city . "' AND `test_master`.`id`='" . $test_id[1] . "'");
                $test_name[] = $tst_prc[0]["test_name"] . " Rs." . $tst_prc[0]["price"];
            }
            /* Nishit send sms code start */
            $cname = "Customer";
            $register = $this->user_call_model->master_fun_get_tbl_val('customer_master', array('status' => 1, 'mobile' => $mobile_no), array("id", "desc"));
            if (count($register) == 0) {
                $con_info = $this->user_call_model->master_fun_get_tbl_val('contact_information', array('status' => 1, 'mobile' => $mobile_no), array("id", "desc"));
                if (count($con_info) == 0) {
                    $cname = "Customer";
                } else {
                    $cname = $con_info[0]["name"];
                }
            } else {
                $cname = $register[0]["full_name"];
            }
            $sms_message = $this->user_call_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "send_quoe"), array("id", "asc"));
            $sms_message = preg_replace("/{{TEST}}/", implode(",", $test_name), $sms_message[0]["message"]);
            $sms_message = preg_replace("/{{CNAME}}/", $cname, $sms_message);
            $sms_message = preg_replace("/{{PRICE}}/", "Rs." . $total_price, $sms_message);

            $this->load->helper("sms");
            $notification = new Sms();
            $notification::send($mobile_no, $sms_message);
            echo $sms_message . "-" . $mobile_no;
            $this->db->close();
            die();
            /* Nishit send sms code end */
            $this->session->set_flashdata("success", array("Quote successfully send."));
        } else {
            $this->session->set_flashdata("unsuccess", array("Try again."));
        }
        redirect("user_call_master");
    }

}

?>
