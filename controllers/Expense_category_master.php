<?php
class Expense_category_master extends CI_Controller { 
   
    function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('Expense_category_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');

        $data["login_data"] = logindata();
    }

    public function expense_category_list() {
        $relid = $this->input->get('search');
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");

        $totalRows = $this->Expense_category_model->num_row('expense_category_master', array('status' => 1));

        $config = array();
        $config["base_url"] = base_url() . "expense_category_master/expense_category_list/";
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
        $data["query"] = $this->Expense_category_model->manage_condition_view($relid, $config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();
        $cnt = 0;

        foreach ($data['query'] as $key) {

            $data['query'][$cnt] = $key;
            $cnt++;
        }

        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('expense_category_list', $data);
        $this->load->view('footer');
    }

    public function expense_category_add() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");

        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Expense Name', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

        if ($this->form_validation->run() != FALSE) {
            $name = $this->input->post('name');
            
        $duplicate = $this->Expense_category_model->duplicate_val("SELECT * from expense_category_master where name='" . $name . "'");
        
            if($duplicate){
             $this->session->set_flashdata("duplicate", "Expense Category allready Exist .");
             redirect("Expense_category_master/expense_category_add", "refresh");
            }
            else{ 
            $data = array(
                "name" => $name,
                "status" => 1,
                "created_date" => date("Y/m/d H:i:s"),
                "created_by" => $data["login_data"]['id']);
            $data['query'] = $this->Expense_category_model->master_get_insert("expense_category_master", $data);
            $this->session->set_flashdata("success", array("Expense Category Successfull Added."));
            redirect("Expense_category_master/expense_category_list", "refresh");
            }
        } else {
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('expense_category_add', $data);
            $this->load->view('footer');
        }
    }
    public function expense_category_edit() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["eid"] = $this->uri->segment('3');
        $ids = $data["eid"];
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Expense Name', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

        if ($this->form_validation->run() != FALSE) {
            $post['name'] = $this->input->post('name');
             $duplicate = $this->Expense_category_model->duplicate_val("SELECT * from expense_category_master where name='" . $post['name'] . "' AND id != $ids");

            if($duplicate){
             $this->session->set_flashdata("duplicate", "Expense Category allready Exist .");
             redirect("Expense_category_master/expense_category_edit/$ids", "refresh");
            }
            else{ 
            $post['name'] = $this->input->post('name');
            $post['status'] = 1;
           
            $post['edited_by'] = $data["login_data"]['id'];
            $data['query'] = $this->Expense_category_model->master_get_update("expense_category_master", array('id' => $_POST['id']), $post);
            $cnt = 0;
            $this->session->set_flashdata("success", array("Expense Category Successfull Updated."));
            redirect("Expense_category_master/expense_category_list", "refresh");
            }       
            } else {
           
            $data['query'] = $this->Expense_category_model->master_get_tbl_val("expense_category_master", array("id" => $data["eid"]), array("id", "desc"));
          
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('expense_category_edit', $data);
            $this->load->view('footer');
        }
    }
    public function expense_category_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $id = $data["login_data"]['id'];
        $eid = $this->uri->segment('3');
       $data['query'] = $this->Expense_category_model->master_get_update("expense_category_master", array("id" => $eid), array("status" => "0","deleted_by"=>$id), array("id", "desc"));
       
        $this->session->set_flashdata("success", array("Expense Category Successfull Deleted."));
        redirect("Expense_category_master/expense_category_list", "refresh");
    }    
}
?>