<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Timeslot_Master extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('user_model');
        $this->load->model('timeslot_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('pushserver');
        $this->load->library('email');
        $this->load->helper('string');
        $this->load->helper('date');
        //echo current_url(); die();

        $data["login_data"] = logindata();
        $this->app_track();
    }

    function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }

    function index() {
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $totalRows = $this->timeslot_model->num_row();
        $config = array();
        $get = $_GET;
        $config["base_url"] = base_url() . "Timeslot_master/timeslot_list/";
        $config["total_rows"] = $totalRows;
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $config['cur_tag_open'] = '<span>';
        $config['cur_tag_close'] = '</span>';
        $config['next_link'] = 'Next <span class="icon-text">&#59230;</span>';
        $config['prev_link'] = '<span class="icon-text">&#59229;</span> Previous';
        $this->pagination->initialize($config);
        $sort = $this->input->get("sort");
        $by = $this->input->get("by");
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['query'] = $this->timeslot_model->master_get_list($config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();
        $cnt = 0;
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('timeslot_list', $data);
        $this->load->view('footer');
    }

    public function checkTime() {
        $start = $this->input->post('start_time');
        $start1 = str_replace(' ', '', $start);
        $start2 = strtotime($start1);
        $end = $this->input->post('end_time');
        $end1 = str_replace(' ', '', $end);
        $end2 = strtotime($end1);
        if ($start2 > $end2) {
            $this->form_validation->set_message('checkTime', 'End Time is less than Start Time.');
            return false;
        } else {
            return True;
        }
    }

    function timeslot_add() {

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");

        $this->load->library('form_validation');


        $this->form_validation->set_rules('start_time', 'Start Time', 'trim|required|xss_clean');
        $this->form_validation->set_rules('end_time', 'End Time', 'trim|required|xss_clean|callback_checkTime');


        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

        if ($this->form_validation->run() != FALSE) {

            $start = $this->input->post('start_time');
            $start1 = str_replace(' ', '', $start);
            $start2 = strtotime($start1);
            $starttime = date("H:i:s", $start2);


            $end = $this->input->post('end_time');
            $end1 = str_replace(' ', '', $end);
            $end2 = strtotime($end1);
            $endtime = date("H:i:s", $end2);

            $post['start_time'] = $starttime;
            $post['end_time'] = $endtime;
            $post['status'] = 1;
            $post['createddate'] = date('d-m-y g:i:s');
            $post['created_by'] = $data['user']->id;
            $post['updated_by'] = $data['user']->id;

            $data['query'] = $this->timeslot_model->master_get_insert("phlebo_time_slot", $post);



            $this->session->set_flashdata("success", 'Time Successfully Added.');
            redirect("Timeslot_master", "refresh");
        } else {

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('timeslot_add', $data);
            $this->load->view('footer');
        }
    }

    function timeslot_delete() {

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');

        $data['query'] = $this->timeslot_model->master_tbl_update("phlebo_time_slot", $cid, array("status" => "0"));

        $this->session->set_flashdata("success", 'Time Successfully Deleted.');
        redirect("Timeslot_master", "refresh");
    }

    /* public function JobDoc_Delete()
      {
      $cid = $this->uri->segment('3');

      $data['query'] = $this->Branch_model->master_get_spam($cid);

      $this->session->set_flashdata('success','Branch Successfull Deleted');
      redirect('branch_master/branch_list','refresh');
      } */

    function timeslot_edit() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data['user'] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['cid'] = $this->uri->segment('3');
        $ids = $data['cid'];
        $data['view_data'] = $this->timeslot_model->master_get_view($ids);



        $this->load->library('form_validation');
        $this->form_validation->set_rules('start_time', 'Start Time', 'trim|required|xss_clean');
        $this->form_validation->set_rules('end_time', 'End Time', 'trim|required|xss_clean|callback_checkTime');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

        if ($this->form_validation->run() != FALSE) {
            $start = $this->input->post('start_time');
            $start1 = str_replace(' ', '', $start);
            $start2 = strtotime($start1);
            $starttime = date("H:i:s", $start2);


            $end = $this->input->post('end_time');
            $end1 = str_replace(' ', '', $end);
            $end2 = strtotime($end1);
            $endtime = date("H:i:s", $end2);

            $post['start_time'] = $starttime;
            $post['end_time'] = $endtime;
            $post['status'] = 1;
            $post['updated_by'] = $data['user']->id;


            $data['query'] = $this->timeslot_model->master_tbl_update("phlebo_time_slot", $ids, $post);

            $cnt = 0;

            $this->session->set_flashdata("success", 'Time Successfully Updated');

            redirect("Timeslot_master", "refresh");
        } else {
            $data['query'] = $this->timeslot_model->master_get_where_condtion("phlebo_time_slot", array("id" => $data["cid"]), array("id", "desc"));

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('timeslot_edit', $data);
            $this->load->view('footer');
        }
    }

}

?>
