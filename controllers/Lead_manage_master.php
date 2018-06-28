<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Lead_manage_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('lead_manage_model');
        $this->load->model('registration_admin_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('session');
        $this->load->library('email');
        //	$this->load->helper('csv');
        $data["login_data"] = logindata();
        $this->app_track();
    }

    function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }

    public function index() {
        $data["login_data"] = logindata();
        $sname = $this->input->get('sname');
        $data['sname'] = $sname;
        $semail = $this->input->get('semail');
        $data['semail'] = $semail;
        $smobile = $this->input->get('smobile');
        $data['smobile'] = $smobile;
        if ($sname != '' || $semail != '' || $smobile != '') {
            $config = array();
            $get = $_GET;
            //print_r($get);die();
            unset($get['offset']);
            $config["base_url"] = base_url() . "lead_manage_master?" . http_build_query($get);
            $total_row = $this->lead_manage_model->record_count($sname, $semail, $smobile);
            $config["total_rows"] = $total_row;
            //echo $total_row;exit;

            $config["per_page"] = 5;
            $config['page_query_string'] = TRUE;
            $config['num_links'] = $total_row;
            $config['cur_tag_open'] = '&nbsp;<a class="current">';
            $config['cur_tag_close'] = '</a>';
            $config['next_link'] = 'Next';
            $config['prev_link'] = 'Previous';

            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data["pages"] = $page;
            $data['records'] = $this->lead_manage_model->list_lead_manage($sname, $semail, $smobile, $config["per_page"], $page);

            $str_links = $this->pagination->create_links();
            $data["links"] = explode('&nbsp;', $str_links);
            //print_r($data);exit;
        } else {
            $config = array();
            $get = $_GET;
            //print_r($get);die();
            $config["base_url"] = base_url() . "lead_manage_master";
            $total_row = $this->lead_manage_model->record_count($sname, $semail, $smobile);
            $config["total_rows"] = $total_row;
            //echo $total_row;exit;

            $config["per_page"] = 10;
            $config['page_query_string'] = TRUE;
            $config['num_links'] = $total_row;
            $config['cur_tag_open'] = '&nbsp;<a class="current">';
            $config['cur_tag_close'] = '</a>';
            $config['next_link'] = 'Next';
            $config['prev_link'] = 'Previous';

            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data["pages"] = $page;
            $data['records'] = $this->lead_manage_model->list_lead_manage($sname, $semail, $smobile, $config['per_page'], $page);

            $str_links = $this->pagination->create_links();
            $data["links"] = explode('&nbsp;', $str_links);
        }
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('lead_manage_list', $data);
        $this->load->view('footer');
    }

    public function lead_manage_add() {
        $this->load->helper("Email");
        $email_cnt = new Email;
        $data["login_data"] = logindata();
        $mobile = $this->input->post('mobile');
        $email = $this->input->post('email');
        if (!empty($email)) {
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim');
        } else {
            $this->form_validation->set_rules('mobile', 'Mobile No', 'numeric|required|trim');
        }
        if ($this->form_validation->run() != FALSE) {
            if ($_POST['dob'] != "") {
                $test = new DateTime($this->input->post('dob'));
                $dob = date_format($test, 'Y-m-d');
            } else {
                $dob = "";
            }
            $data = array(
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'mobile_no' => $this->input->post('mobile'),
                'gender' => $this->input->post('gender'),
                'date_of_birth' => $dob,
                'age' => $this->input->post('age'),
                'address' => htmlspecialchars_decode($this->input->post('address')),
                'note' => htmlspecialchars_decode($this->input->post('note')),
                'created_date' => date('Y-m-d H:i:s'),
                'status' => '1'
            );
            /* Nishit changes start */
            if (!empty($mobile)) {
                $welcome_msg = $this->lead_manage_model->master_fun_get_tbl_val('sms_master', array('status' => 1, 'title' => "welcome_message"), array('id', 'asc'));
                $this->lead_manage_model->master_fun_insert('admin_alert_sms', array('mobile_no' => $mobile, 'message' => $welcome_msg[0]["message"], "created_date" => date("y-m-d H:i:s")));
            } else if (!empty($email)) {
                $config['mailtype'] = 'html';
                $this->email->initialize($config);
                $message = 'Welcome to Airmed pathology labs. The Lab At Your Doorstep. Our blood collection guy will reach you 24x7 with free sample collection. We provide service across Ahmedabad and Gandhinagar.
Book through the mobile app. www.AirmedLabs.com For booking a test you can also call : 8101161616.
Download App : https://goo.gl/F2vOau';
                $message = $email_cnt->get_design($message);
                $this->email->to($email);
                $this->email->from($this->config->item('admin_booking_email'), 'AirmedLabs');
                $this->email->subject('Welcome');
                $this->email->message($message);
                $this->email->send();
            }
            /* Nishit changes end */
            $data['query'] = $this->lead_manage_model->insert($data);
            $this->session->set_flashdata('success', "Lead Manage Successfully Added");
            redirect("lead_manage_master");
        } else {
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('lead_manage_add');
            $this->load->view('footer');
        }
    }

    public function lead_manage_edit() {
        $data["login_data"] = logindata();
        // $data["cid"] = $this->uri->segment('3');
        $lmid = $this->uri->segment('3');
        $this->form_validation->set_rules('mobile', 'Mobile No', 'numeric');
        if ($this->form_validation->run() == TRUE) {
            if ($_POST['name'] != "" || $_POST['email'] != "" || ($_POST['mobile'] != "0" && $_POST['mobile'] != "") || $_POST['gender'] != "" || ($_POST['dob'] != "0000-00-00" && $_POST['dob'] != "") || ($_POST['age'] != "0" && $_POST['age'] != "" ) || $_POST['address'] != "" || $_POST['note'] != "") {
                if ($_POST['dob'] != "") {
                    $test = new DateTime($this->input->post('dob'));
                    $dob = date_format($test, 'Y-m-d');
                } else {
                    $dob = "";
                }
                $data = array(
                    'name' => $this->input->post('name'),
                    'email' => $this->input->post('email'),
                    'mobile_no' => $this->input->post('mobile'),
                    'gender' => $this->input->post('gender'),
                    'date_of_birth' => $dob,
                    'age' => $this->input->post('age'),
                    'address' => htmlspecialchars_decode($this->input->post('address')),
                    'note' => htmlspecialchars_decode($this->input->post('note'))
                );

                $data['view_data'] = $this->lead_manage_model->update($lmid, $data);
                $this->session->set_flashdata("success", "Lead Manage Successfully Updated.");
                redirect("lead_manage_master");
            }

            $this->session->set_flashdata('success', "Please fill atleast one field");
            $data['view_data'] = $this->lead_manage_model->get_lm($lmid);

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('lead_manage_edit', $data, FALSE);
            $this->load->view('footer');
        } else {
            $data['view_data'] = $this->lead_manage_model->get_lm($lmid);

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('lead_manage_edit', $data, FALSE);
            $this->load->view('footer');
        }
    }

    /* public function lead_manage_delete()
      {
      $lmid = $this->uri->segment('3');
      $data=array(
      'status' => '0');

      $data['query'] = $this->lead_manage_model->delete_lm($lmid, $data);
      $this->session->set_flashdata('success', 'Lead Manage Successfully Deleted');
      redirect("lead_manage_master");
      } */

    function lead_manage_delete($cid) {
        $data['query'] = $this->lead_manage_model->updates("lead_manage_master", array("id" => $cid), array("status" => '0'));

        $this->session->set_flashdata("success", "Lead Manage successfully deleted.");
        redirect("lead_manage_master");
    }

    function prescription_csv_report() {


        $result = $this->lead_manage_model->prescription_report();

        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"All_Prescription_Report-" . date('d-M-Y') . ".csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');


        fputcsv($handle, array("Name", "Email", "Mobile Number", "Gender", "DOB", "Note", "Address", "Status"));

        foreach ($result as $res) {
            if ($res["mobile"] == $res["mobile_no"]) {
                fputcsv($handle, array($res["name"], $res["email"], $res["mobile_no"], $res["gender"], $res["date_of_birth"], $res["note"], $res["address"], "Converted"));
            } else {
                fputcsv($handle, array($res["name"], $res["email"], $res["mobile_no"], $res["gender"], $res["date_of_birth"], $res["note"], $res["address"], "Not Converted"));
            }
        }

        fclose($handle);
        exit;
    }

}

?>