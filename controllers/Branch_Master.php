<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Branch_Master extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('user_model');
        $this->load->model('Branch_Model');
        $this->load->model('registration_admin_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('pushserver');
        $this->load->library('email');
        $this->load->helper('string');
        //echo current_url(); die();

        $data["login_data"] = logindata();
    }

    function Branch_list() {
        $data['branch_name'] = $branchid = $this->input->get('branch_name');
        $data['test_city'] = $test_city = $this->input->get('test_city');
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        if ($branchid != "" || $test_city != '') {
            $totalRows = $this->Branch_Model->num_row($branchid, $test_city);
            $config = array();
            $get = $_GET;
            unset($get['offset']);
            $config["base_url"] = base_url() . "Branch_Master/Branch_list?" . http_build_query($get);
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 50;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->Branch_Model->master_get_search($branchid, $config["per_page"], $page, $test_city);
            $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;
            //echo "hiii";
            $cnt = 0;
        } else {
            $totalRows = $this->Branch_Model->num_row($branchid);
            $config = array();
            $get = $_GET;
            $config["base_url"] = base_url() . "Branch_Master/Branch_list/";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 50;
            $config["uri_segment"] = 3;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next <span class="icon-text">&#59230;</span>';
            $config['prev_link'] = '<span class="icon-text">&#59229;</span> Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $data['query'] = $this->Branch_Model->master_get_search($branchid, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            //echo "bye";
            $cnt = 0;
        }
        $data['city'] = $this->Branch_Model->list_city();
        $data['branch'] = $this->Branch_Model->master_get_branch();
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('Branch_list', $data);
        $this->load->view('footer');
    }

    function user_branch($userid) {
        $branchid = $this->input->get('branch_name');
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["id"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['userid'] = $userid;
        $data['branch_list'] = $this->registration_admin_model->get_val("SELECT * from branch_master where  status='1'");
        $data['list'] = $this->registration_admin_model->get_val("SELECT user_branch.id,`branch_master`.`branch_name`,user_branch.test_parameter FROM   `user_branch`    LEFT JOIN branch_master      ON `branch_master`.`id` = `user_branch`.`branch_fk`  WHERE user_branch.`status`=1 AND user_branch.`user_fk`=$userid");
        $config = array();
        $get = $_GET;
        $config["base_url"] = base_url() . "Branch_Master/Branch_list/";
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
        $data['query'] = $this->Branch_Model->master_get_search($branchid, $config["per_page"], $page);
        $data['city'] = $this->Branch_Model->list_city();
        /*  echo "<pre>";
          print_r($data['city']); */
        //echo "bye";
        $cnt = 0;
        $data['branch'] = $this->Branch_Model->master_get_branch();
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('user_branch', $data);
        $this->load->view('footer');
    }

    function user_city($userid) {
        $branchid = $this->input->get('branch_name');
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["id"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['userid'] = $userid;
        $data['branch_list'] = $this->registration_admin_model->get_val("SELECT * from branch_master where  status='1'");
        $data['list'] = $this->registration_admin_model->get_val("SELECT user_branch.id,`branch_master`.`branch_name`,user_branch.test_parameter FROM   `user_branch`    LEFT JOIN branch_master      ON `branch_master`.`id` = `user_branch`.`branch_fk`  WHERE user_branch.`status`=1 AND user_branch.`user_fk`=$userid");
        $config = array();
        $get = $_GET;
        $config["base_url"] = base_url() . "Branch_Master/Branch_list/";
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
        $data['query'] = $this->Branch_Model->master_get_search($branchid, $config["per_page"], $page);
        $data['city'] = $this->Branch_Model->list_city();
        /*  echo "<pre>";
          print_r($data['city']); */
        //echo "bye";
        $cnt = 0;
        $data['branch'] = $this->Branch_Model->master_get_branch();
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('user_branch', $data);
        $this->load->view('footer');
    }

    function Branch_add() {

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['city'] = $this->Branch_Model->list_city();
        $data['success'] = $this->session->flashdata("success");

        $this->load->library('form_validation');
        $this->form_validation->set_rules('branch_code', 'Branch Code', 'trim|required|xss_clean');
        $this->form_validation->set_rules('branch_name', 'Branch Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('city', 'City', 'trim|required|xss_clean');
        $this->form_validation->set_rules('address', 'Address', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

        if ($this->form_validation->run() != FALSE) {
            //echo "<pre>"; print_r($_POST); die();
            $post['branch_code'] = $this->input->post('branch_code');
            $post['branch_name'] = $this->input->post('branch_name');
            $post['city'] = $this->input->post('city');
            $post['address'] = $this->input->post('address');
            $post['status'] = 1;
            $post['createddate'] = date('d-m-y g:i:s');
            $post['created_by'] = $data['user']->id;
            $post['updated_by'] = $data['user']->id;
            /* print_r($post);
              exit; */
            $data['query'] = $this->Branch_Model->master_get_insert("branch_master", $post);



            $this->session->set_flashdata("success", 'Branch Successfull Added.');
            redirect("Branch_Master/Branch_list", "refresh");
        } else {

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('Branch_add', $data);
            $this->load->view('footer');
        }
    }

    function user_branch_add($userid) {

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $parameter = $this->input->post("parameter");
        if ($parameter == null) {
            $parameter = 0;
        }
        $post = array();
        $post['branch_fk'] = $this->input->post('branch');
        $post['test_parameter'] = $parameter;
        $post['user_fk'] = $userid;
        $post['status'] = 1;
        $branch = $this->Branch_Model->master_get_where_condtion("user_branch", $post, array("id", "desc"));
        if (count($branch) == 0) {
            $data['query'] = $this->Branch_Model->master_get_insert("user_branch", $post);
        }

        $this->session->set_flashdata("success", 'Branch Successfull Deleted.');
        redirect("Branch_Master/user_branch/" . $userid, "refresh");
    }

    function user_branch_delete($userid, $id) {

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');

        $data['query'] = $this->Branch_Model->master_tbl_update("user_branch", $id, array("status" => "0"));

        $this->session->set_flashdata("success", 'Branch Successfull .');
        redirect("Branch_Master/user_branch/" . $userid, "refresh");
    }

    function Branch_delete() {

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');

        $data['query'] = $this->Branch_Model->master_tbl_update("branch_master", $cid, array("status" => "0"));

        $this->session->set_flashdata("success", 'Branch Successfull Deleted.');
        redirect("Branch_Master/Branch_list", "refresh");
    }

    /* public function JobDoc_Delete()
      {
      $cid = $this->uri->segment('3');

      $data['query'] = $this->Branch_model->master_get_spam($cid);

      $this->session->set_flashdata('success','Branch Successfull Deleted');
      redirect('Branch_Master/Branch_list','refresh');
      } */

    function Branch_edit() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data['user'] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['cid'] = $this->uri->segment('3');
        $ids = $data['cid'];
        $data['view_data'] = $this->Branch_Model->master_get_view($ids);
        $data['city'] = $this->Branch_Model->list_city();


        $this->load->library('form_validation');
        $this->form_validation->set_rules('branch_code', 'Branch Code', 'trim|required|xss_clean');
        $this->form_validation->set_rules('branch_name', 'Branch Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('city', 'City', 'trim|required|xss_clean');
        $this->form_validation->set_rules('address', 'Address', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

        if ($this->form_validation->run() != FALSE) {

            $post['branch_code'] = $this->input->post('branch_code');
            $post['branch_name'] = $this->input->post('branch_name');
            $post['city'] = $this->input->post('city');
            $post['address'] = $this->input->post('address');
            $post['status'] = 1;
            $post['created_by'] = $data['user']->id;
            $post['updated_by'] = $data['user']->id;

            $data['query'] = $this->Branch_Model->master_tbl_update("branch_master", $ids, $post);

            $cnt = 0;

            $this->session->set_flashdata("success", 'Branch Successfull Updated');

            redirect("Branch_Master/Branch_list", "refresh");
        } else {
            $data['query'] = $this->Branch_Model->master_get_where_condtion("branch_master", array("id" => $data["cid"]), array("id", "desc"));

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('Branch_edit', $data);
            $this->load->view('footer');
        }
    }

}

?>
