<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Location_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('user_model');
        $this->load->model('location_model');
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

    function country_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $data['query'] = $this->location_model->master_fun_get_tbl_val("country", array('status' => 1), array("id", "asc"));
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('country_list', $data);
        $this->load->view('footer');
    }

    function country_add() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Country Name', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $name = $this->input->post('name');
            $data['query'] = $this->location_model->master_fun_insert("country", array("country_name" => $name));
            $this->session->set_flashdata("success", array("Country successfully added."));
            redirect("location-master/country-list", "refresh");
        } else {
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('country_add', $data);
            $this->load->view('footer');
        }
    }

    function country_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->user_model->master_fun_update("country", array("id", $cid), array("status" => "0"));
        $this->session->set_flashdata("success", array("Country successfully deleted."));
        redirect("location-master/country-list", "refresh");
    }

    function country_edit() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["cid"] = $this->uri->segment('3');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Country Name', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $name = $this->input->post('name');
            $data['query'] = $this->location_model->master_fun_update("country", array("id", $data["cid"]), array("country_name" => $name));
            $this->session->set_flashdata("success", array("Country successfully updated."));
            redirect("location-master/country-list", "refresh");
        } else {
            $data['query'] = $this->location_model->master_fun_get_tbl_val("country", array("id" => $data["cid"]), array("id", "desc"));
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('country_edit', $data);
            $this->load->view('footer');
        }
    }

    function state_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $state_serch = $this->input->get('state_search');
        $data['state_search'] = $state_serch;
        if ($state_serch != "") {
            $total_row = $this->location_model->num_row_srch_state_list($state_serch);
            $config = array();
            $get = $_GET;
            unset($get['offset']);
            $config["base_url"] = base_url() . "location-master/state-list?" . http_build_query($get);
            $config["total_rows"] = $total_row;
            $config["per_page"] = 50;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->location_model->row_srch_state_list($state_serch, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        } else {
            $total_row = $this->location_model->num_row('state', array('status' => 1));
            $config["base_url"] = base_url() . "location-master/state-list";
            $config["total_rows"] = $total_row;
            $config["per_page"] = 50;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->location_model->srch_state_list($config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        }
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('state_list', $data);
        $this->load->view('footer');
    }

    function state_add() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'State Name', 'trim|required');
        $this->form_validation->set_rules('country', 'Country', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $name = $this->input->post('name');
            $country = $this->input->post('country');
            $data['query'] = $this->location_model->master_fun_insert("state", array("country_fk" => $country, "state_name" => $name));
            $this->session->set_flashdata("success", array("State successfully added."));
            redirect("location-master/state-list", "refresh");
        } else {
            $data['country'] = $this->user_model->master_fun_get_tbl_val("country", array("status" => 1), array("id", "desc"));
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('state_add', $data);
            $this->load->view('footer');
        }
    }

    function state_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->user_model->master_fun_update("state", array("id", $cid), array("status" => "0"));
        $this->session->set_flashdata("success", array("Country successfully deleted."));
        redirect("location-master/state-list", "refresh");
    }

    function state_edit() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["cid"] = $this->uri->segment('3');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'State Name', 'trim|required');
        $this->form_validation->set_rules('country', 'Country', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $name = $this->input->post('name');
            $country = $this->input->post('country');
            $data['query'] = $this->location_model->master_fun_update("state", array("id", $data["cid"]), array("country_fk" => $country, "state_name" => $name));
            $this->session->set_flashdata("success", array("State successfully updated."));
            redirect("location-master/state-list", "refresh");
        } else {
            $data['query'] = $this->user_model->master_fun_get_tbl_val("state", array("id" => $data["cid"]), array("id", "desc"));
            $data['country'] = $this->location_model->master_fun_get_tbl_val("country", array("status" => 1), array("id", "desc"));
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('state_edit', $data);
            $this->load->view('footer');
        }
    }

    function city_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $state = $this->input->get('state');
        $city = $this->input->get('city');
        if ($state != "" || $city != "") {
            $srchdata = array("state" => $state, "city" => $city);
            $data['state'] = $state;
            $data['city'] = $city;
            $totalRows = $this->location_model->citycount_list($srchdata);
            $config = array();
            $config["base_url"] = base_url() . "location-master/city-list/";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 50;
            $config["uri_segment"] = 3;
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $data["query"] = $this->location_model->citylist_list($srchdata, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        } else {
            $srchdata = array();
            $totalRows = $this->location_model->citycount_list($srchdata);
            $config = array();
            $config["base_url"] = base_url() . "location-master/city-list/";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 50;
            $config["uri_segment"] = 3;
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $data["query"] = $this->location_model->citylist_list($srchdata, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        }
        $data['statelist'] = $this->location_model->get_master_get_data("state", array('status' => '1'), array("state_name"));
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('city_list', $data);
        $this->load->view('footer');
    }

    function city_add() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'City', 'trim|required');
        $this->form_validation->set_rules('country', 'Country', 'trim|required');
        $this->form_validation->set_rules('state', 'State', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $name = $this->input->post('name');
            $country = $this->input->post('country');
            $state = $this->input->post('state');
            $data['query'] = $this->location_model->master_fun_insert("city", array("country_fk" => $country, "state_fk" => $state, "city_name" => $name));
            $this->session->set_flashdata("success", array("State successfully added."));
            redirect("location-master/city-list", "refresh");
        } else {
            $data['country'] = $this->user_model->master_fun_get_tbl_val("country", array("status" => 1), array("id", "desc"));
            $data['state'] = $this->user_model->master_fun_get_tbl_val("state", array("status" => 1), array("id", "desc"));
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('city_add', $data);
            $this->load->view('footer');
        }
    }

    function city_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->user_model->master_fun_update("city", array("id", $cid), array("status" => "0"));
        $this->session->set_flashdata("success", array("City successfully deleted."));
        redirect("location-master/city-list", "refresh");
    }

    function city_edit() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["cid"] = $this->uri->segment('3');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'City', 'trim|required');
        $this->form_validation->set_rules('country', 'Country', 'trim|required');
        $this->form_validation->set_rules('state', 'State', 'trim|required');

        if ($this->form_validation->run() != FALSE) {
            $name = $this->input->post('name');
            $country = $this->input->post('country');
            $state = $this->input->post('state');
            $data['query'] = $this->location_model->master_fun_update("city", array("id", $data["cid"]), array("state_fk" => $state, "country_fk" => $country, "city_name" => $name));
            $this->session->set_flashdata("success", array("State successfully updated."));
            redirect("location-master/city-list", "refresh");
        } else {
            $data['query'] = $this->user_model->master_fun_get_tbl_val("city", array("id" => $data["cid"]), array("id", "desc"));
            $data['country'] = $this->location_model->master_fun_get_tbl_val("country", array("status" => 1), array("id", "desc"));
            $data['state'] = $this->location_model->master_fun_get_tbl_val("state", array("status" => 1), array("id", "desc"));
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('city_edit', $data);
            $this->load->view('footer');
        }
    }

    function get_state() {

        $country = $this->uri->segment(3);
        $data = $this->user_model->master_fun_get_tbl_val("state", array("country_fk" => $country, "status" => 1), array("id", "desc"));
        echo "<option value=''> Select State </option>";
        for ($i = 0; $i < count($data); $i++) {
            $id = $data[$i]['id'];
            $name = $data[$i]['state_name'];
            echo "<option value='$id'> $name </option>";
        }
    }

}

?>
