<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Collection extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper(array('form', 'url'));
        $this->load->model('master_model');
        $this->load->library('form_validation');
        $this->app_track();
    }

    function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }

    function todays_collection() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $uid = $this->input->get_post("uid");
        $data = $this->master_model->get_val("SELECT SUM(amount) AS total FROM `job_master_receiv_amount` WHERE `status`='1' AND `added_by`='" . $data["login_data"]["id"] . "' AND createddate > '" . date("Y-m-d") . " 00:00:00' AND createddate < '" . date("Y-m-d") . " 23:59:59'");
        if (empty($data[0]["total"])) {
            $data[0]["total"] = 0;
        }
        echo "Rs." . number_format((float) $data[0]["total"], 2, '.', '');
    }

    function todays_collection1() {
        if (!is_loggedin()) {
            redirect('login');
        }
        echo "SELECT SUM(amount) AS total FROM `job_master_receiv_amount` WHERE `status`='1' AND `added_by`='" . $data["login_data"]["id"] . "' AND createddate > '" . date("Y-m-d") . " 00:00:00' AND createddate < '" . date("Y-m-d") . " 23:59:59'"; die();
        $data["login_data"] = logindata();
        $uid = $this->input->get_post("uid");
        $data = $this->master_model->get_val("SELECT SUM(amount) AS total FROM `job_master_receiv_amount` WHERE `status`='1' AND `added_by`='" . $data["login_data"]["id"] . "' AND createddate > '" . date("Y-m-d") . " 00:00:00' AND createddate < '" . date("Y-m-d") . " 23:59:59'");
        if (empty($data[0]["total"])) {
            $data[0]["total"] = 0;
        }
        echo "Rs." . number_format((float) $data[0]["total"], 2, '.', '');
    }
}
