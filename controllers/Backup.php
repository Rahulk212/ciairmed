<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Backup extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('user_model');
        $this->load->model('customer_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        //echo current_url(); die();

        $data["login_data"] = logindata();
        $this->app_track();
    }

    function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }

    function backup_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        //print_r($data["login_data"]); die();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $data['success'] = $this->session->flashdata("success");
        $data['query'] = $this->customer_model->master_fun_get_tbl_val("database_backup", array('status' => 1), array("id", "asc"));
        //$this->load->view('admin/state_list_view', $data);
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('backup_list', $data);
        $this->load->view('footer');
    }

    function download_backup() {
        $this->load->dbutil();
        $prefs = array('format' => 'sql', // gzip, zip, txt 
            'filename' => 'patholabdatabase_backup' . date('Y-m-d_H-i') . 'sql',
            // File name - NEEDED ONLY WITH ZIP FILES 
            'add_drop' => TRUE,
            // Whether to add DROP TABLE statements to backup file
            'add_insert' => TRUE,
            // Whether to add INSERT data to backup file 
            'newline' => "\n"
                // Newline character used in backup file 
        );
        $backup = $this->dbutil->backup($prefs);
        $this->load->helper('file');
        $path = $this->uri->segment('4');
        $file = write_file($path, $backup);
        $this->load->helper('download');
        force_download($path, $backup);
    }

    function database_backup() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $this->load->dbutil();

// Backup your entire database and assign it to a variable
        $prefs = array('format' => 'sql', // gzip, zip, txt 
            'filename' => 'dentaldatabase_backup' . date('Y-m-d_H-i') . 'sql',
            // File name - NEEDED ONLY WITH ZIP FILES 
            'add_drop' => TRUE,
            // Whether to add DROP TABLE statements to backup file
            'add_insert' => TRUE,
            // Whether to add INSERT data to backup file 
            'newline' => "\n"
                // Newline character used in backup file 
        );
        $backup = $this->dbutil->backup($prefs);

        $this->load->helper('file');
        $path = './backup/patholabdatabase_backup' . date('Y-m-d_H-i') . '.sql';
        $file = write_file($path, $backup);

        $data = array(
            "file_name" => $path,
            "backup_date" => date('Y-m-d'),
            "backup_time" => date('h:i:sa'),
        );

        $insert = $this->customer_model->master_fun_insert("database_backup", $data);
        if ($insert) {

            redirect('backup/backup_list', 'refresh');
        }
    }

}

?>