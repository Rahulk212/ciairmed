<?php

class Expense_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('Expense_category_model');
        $this->load->model('Expense_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');

        $data["login_data"] = logindata();
    }

    public function expense_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
   
        $type = $data["login_data"]['type'];
        $id = $data["login_data"]['id'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");

        $start_date = $this->input->get('start_date');

        $description = $this->input->get('description');
        $amount = $this->input->get('amount');
        $category_fk = $this->input->get('expense_category_fk');
        $end_date = $this->input->get('end_date');

        $branch_fk = $this->input->get('branch_fk');

        if ($description != '' || $amount != '' || $start_date != '' || $category_fk != '' || $end_date != '' || $branch_fk != '') {
            $srchdata = array("end_date" => $end_date, "description" => $description, "amount" => $amount, "start_date" => $start_date, "expense_category_fk" => $category_fk, "branch_fk" => $branch_fk);

            $data['description'] = $description;
            $data['amount'] = $amount;
            $data['start_date'] = $start_date;

            $data['end_date'] = $end_date;

            $data['expense_category_fk'] = $expense_category_fk;
            $data['branch_fk'] = $branch_fk;
            $totalRows = $this->Expense_model->manage_condition_view($srchdata);

            $config = array();
            $config["base_url"] = base_url() . "expense_master/expense_list/";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 50;
            $config["uri_segment"] = 3;
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            if($type == 1 || $type == 2){
               $data["query"] = $this->Expense_model->expenselist_list($srchdata, $config["per_page"], $page);
            }
            else{
            
              $temp = "";
        if ($amount != "") {
            $amount = $data['amount'];
       
            $temp .= " AND amount LIKE '%$amount' ";
        }

        if ($data['start_date'] != "" && $data['end_date'] != "") {
            $start = explode("/", $data['start_date']);
            $test = $start[2] . '-' . $start[1] . '-' . $start[0];

            $end_date = explode("/", $data['end_date']);
            $new_date = $end_date[2] . '-' . $end_date[1] . '-' . $end_date[0];

            $temp .= " AND expense_date >= '$test' and expense_date <= '$new_date'";
            //$temp .= " AND DATE_FORMAT(expense_date, '%d-%m-%Y') >='$expense_date'";
        }


        if ($data['description'] != "") {
            $description = $data['description'];
            //echo "<prE>";print_r($id);die;
            $temp .= " AND description LIKE '%$description' ";
        }
        if ($data['expense_category_fk'] != "") {
            $expense_category_fk = $data['expense_category_fk'];
            //echo "<prE>";print_r($id);die;
            $temp .= " AND expense_category_fk LIKE '%$expense_category_fk' ";
        }

        if ($data['branch_fk'] != "") {

            $branch_fk = $data['branch_fk'];
         
            $temp .= " AND c.branch_fk = '$branch_fk' ";
        }
           $id = $data['login_data']['id'];
        
                $data["query"] = $this->Expense_model->get_val("SELECT c.*,brn.branch_name,admin.name as AdminName,s.name as CategoryName FROM expense_master c LEFT JOIN expense_category_master s ON c.expense_category_fk=s.id LEFT JOIN admin_master as admin on admin.id = c.created_by LEFT JOIN branch_master as brn on brn.id = c.branch_fk  WHERE s.status=1 AND c.status=1 AND c.created_by='$id' $temp");
        // echo "<pre>";print_r($data["query"]);die;
            }
            
            //  print_r($data["query"]);die;
            $data["links"] = $this->pagination->create_links();
        } else {

            //$user_branch = $this->Expense_model->get_val("SELECT * FROM expense_master WHERE branch_fk IN (SELECT branch_fk AS branch FROM `user_branch` WHERE `status`='1' AND user_fk='" . $id . "') AND `status`='1' ");
          

            //$total_row = $this->Expense_model->get_where('expense_master', array('status' => 1));

            $config = array();
            $config["base_url"] = base_url() . "expense_master/expense_list/";
            $config["total_rows"] = count($user_branch);
            $config["per_page"] = 50;
            $config["uri_segment"] = 3;
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            
            if($type ==1 || $type ==2){
                           $data["query"] = $this->Expense_model->get_val("SELECT *,admin_master.name as AdminName,`branch_master`.`branch_name`,expense_category_master.name as CategoryName FROM expense_master LEFT JOIN `branch_master` ON expense_master.`branch_fk`=`branch_master`.id LEFT JOIN expense_category_master ON expense_master.expense_category_fk = expense_category_master.id LEFT JOIN admin_master ON admin_master.id = expense_master.created_by WHERE expense_master.branch_fk IN (SELECT branch_fk AS branch FROM `user_branch` WHERE `status`='1' ) AND expense_master.`status`='1' order by expense_master.id desc limit " . $page . "," . $config["per_page"]);
 
            }else{
            $data["query"] = $this->Expense_model->get_val("SELECT *,admin_master.name as AdminName,`branch_master`.`branch_name`,expense_category_master.name as CategoryName FROM expense_master LEFT JOIN `branch_master` ON expense_master.`branch_fk`=`branch_master`.id LEFT JOIN expense_category_master ON expense_master.expense_category_fk = expense_category_master.id LEFT JOIN admin_master ON admin_master.id = expense_master.created_by WHERE expense_master.branch_fk IN (SELECT branch_fk AS branch FROM `user_branch` WHERE `status`='1' AND user_fk='".$id."') AND expense_master.`status`='1' order by expense_master.id desc limit " . $page . "," . $config["per_page"]);
            }
            // $data["links"] = $this->pagination->create_links();
        }
        $data['expensecate'] = $this->Expense_model->get_master_get_data("expense_category_master", array('status' => '1'), array("name"));

        if ($type == 1 || $type == 2) {
            $data['branch'] = $this->Expense_model->get_val("SELECT uc.*,bc.branch_name FROM `user_branch` AS uc INNER JOIN branch_master AS bc ON uc.`branch_fk` = bc.id WHERE uc.status = 1 GROUP BY uc.branch_fk");
        } else {
            $data['branch'] = $this->Expense_model->get_val("SELECT uc.*,bc.branch_name FROM `user_branch` as uc INNER JOIN branch_master as bc ON uc.`branch_fk` = bc.id WHERE uc.status = 1 and uc.user_fk = '$id'");
            // echo "<pre>"; print_r($data['branch']);die;
        }

        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('expense_list', $data);
        $this->load->view('footer');
    }

    public function expense_add() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $data['success'] = $this->session->flashdata("success");

        $this->load->library('form_validation');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required|xss_clean|numeric|max_length[8]');
        $this->form_validation->set_rules('expense_category_fk', 'Expense Category', 'required');
        $this->form_validation->set_rules('expense_date', 'Expense Date', 'required');
        $this->form_validation->set_rules('branch_fk', 'Branch', 'required');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

        if (!empty($_FILES['upload_receipt']['name'])) {
            $config['upload_path'] = './upload/expense_master/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif|doc|pdf|txt';
            $config['file_name'] = $_FILES['upload_receipt']['name'];

            //Load upload library and initialize configuration
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('upload_receipt')) {
                $uploadData = $this->upload->data();
                $picture = $uploadData['file_name'];
            } else {
                $picture = '';
            }
        } else {

            $picture = '';
        }

        $id = $data["login_data"]['id'];
        $type = $data["login_data"]['type'];
        if ($type == 1 || $type == 2) {

            $data['branch'] = $this->Expense_model->get_val("SELECT uc.*,bc.branch_name FROM `user_branch` AS uc INNER JOIN branch_master AS bc ON uc.`branch_fk` = bc.id WHERE uc.status = 1 GROUP BY uc.branch_fk");
        } else {
            $data['branch'] = $this->Expense_model->get_val("SELECT uc.*,bc.branch_name FROM `user_branch` as uc INNER JOIN branch_master as bc ON uc.`branch_fk` = bc.id WHERE uc.status = 1 and uc.user_fk = '$id'");
        }

        if ($this->form_validation->run() != FALSE) {

            //$duplicate = $this->Expense_model->duplicate_val("SELECT * from expense_master where name='" . $name . "'");
            //  if($duplicate){
            // $this->session->set_flashdata("duplicate", "Expense Category allready Exist .");
            //redirect("Expense_category_master/expense_category_add", "refresh");
            //  }
            //else{ 
            $old_date = explode("/", $this->input->post('expense_date'));

            $new_date = $old_date[2] . "-" . $old_date[1] . "-" . $old_date[0];
            $data = array(
                'expense_date' => $new_date,
                "description" => $this->input->post('description'),
                "amount" => $this->input->post('amount'),
                "expense_category_fk" => $this->input->post('expense_category_fk'),
                "branch_fk" => $this->input->post('branch_fk'),
                "upload_receipt" => $picture,
                "status" => 1,
                "created_date" => date("Y/m/d H:i:s"),
                "created_by" => $data["login_data"]['id']
            );

            $data['query'] = $this->Expense_model->master_get_insert("expense_master", $data);

            $this->session->set_flashdata("success", array("Expense  Successfull Added."));
            redirect("Expense_master/expense_list", "refresh");
            // }
        } else {
            $data['exp_cate'] = $this->Expense_model->master_get_tbl_val("expense_category_master", array("status" => 1), array("id", "desc"));

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('expense_add', $data);
            $this->load->view('footer');
        }
    }

    public function expense_edit() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["eid"] = $this->uri->segment('3');
        $ids = $data["eid"];
        $this->load->library('form_validation');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required|xss_clean|numeric');
        $this->form_validation->set_rules('expense_category_fk', 'Expense Category', 'trim|required|xss_clean');

        $this->form_validation->set_rules('expense_date', 'Expense Date', 'required');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

        if (!empty($_FILES['upload_receipt']['name'])) {
            $config['upload_path'] = './upload/expense_master/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif|doc|pdf|txt';
            $config['file_name'] = $_FILES['upload_receipt']['name'];

            //Load upload library and initialize configuration
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('upload_receipt')) {
                $uploadData = $this->upload->data();
                $picture = $uploadData['file_name'];
            } else {
                $picture = '';
            }
        } else {

            $picture = '';
        }

        $id = $data["login_data"]['id'];
        $type = $data["login_data"]['type'];

        if ($type == 1 || $type == 2) {

            $data['branch'] = $this->Expense_model->get_val("SELECT uc.*,bc.branch_name FROM `user_branch` AS uc INNER JOIN branch_master AS bc ON uc.`branch_fk` = bc.id WHERE uc.status = 1 GROUP BY uc.branch_fk");
        } else {
            $data['branch'] = $this->Expense_model->get_val("SELECT uc.*,bc.branch_name FROM `user_branch` as uc INNER JOIN branch_master as bc ON uc.`branch_fk` = bc.id WHERE uc.status = 1 and uc.user_fk = '$id'");
        }
        if ($this->form_validation->run() != FALSE) {
            // $post['name'] = $this->input->post('name');
            //$duplicate = $this->Expense_category_model->duplicate_val("SELECT * from expense_category_master where name='" . $post['name'] . "' AND id != $ids");
            //if($duplicate){
            // $this->session->set_flashdata("duplicate", "Expense Category allready Exist .");
            //redirect("Expense_category_master/expense_category_edit/$ids", "refresh");
            //}
            //else{ 
            $old_date = date("Y-m-d", strtotime($this->input->post('expense_date')));


//            $new_date = $old_date[2]."-".$old_date[1]."-".$old_date[0];

            $post['expense_date'] = $old_date;

            $post['description'] = $this->input->post('description');
            $post['amount'] = $this->input->post('amount');
            $post['upload_receipt'] = $picture;

            $post['expense_category_fk'] = $this->input->post('expense_category_fk');
            $post['branch_fk'] = $this->input->post('branch_fk');
            $post['status'] = 1;

            $post['edited_by'] = $data["login_data"]['id'];

            $data['query'] = $this->Expense_model->master_get_update("expense_master", array('id' => $_POST['id']), $post);

            $cnt = 0;
            $this->session->set_flashdata("success", array("Expense Successfull Updated."));
            redirect("Expense_master/expense_list", "refresh");
            //  }       
        } else {

            $data['query'] = $this->Expense_model->master_get_tbl_val("expense_master", array("id" => $data["eid"]), array("id", "desc"));

            $data['exp_cate'] = $this->Expense_model->master_get_tbl_val("expense_category_master", array("status" => 1), array("id", "desc"));
            if ($type == 1 || $type == 2) {
                $data['branch'] = $this->Expense_model->get_val("SELECT uc.*,bc.branch_name FROM `user_branch` AS uc INNER JOIN branch_master AS bc ON uc.`branch_fk` = bc.id WHERE uc.status = 1 GROUP BY uc.branch_fk");
            } else {
                $data['branch'] = $this->Expense_model->get_val("SELECT uc.*,bc.branch_name FROM `user_branch` as uc INNER JOIN branch_master as bc ON uc.`branch_fk` = bc.id WHERE uc.status = 1 AND uc.user_fk='$id'");
            }
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('expense_edit', $data);
            $this->load->view('footer');
        }
    }

    public function expense_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $id = $data["login_data"]['id'];
        $eid = $this->uri->segment('3');
        $data['query'] = $this->Expense_model->master_get_update("expense_master", array("id" => $eid), array("status" => "0", "deleted_by" => $id), array("id", "desc"));

        $this->session->set_flashdata("success", array("Expense  Successfull Deleted."));
        redirect("Expense_master/expense_list", "refresh");
    }

    public function expense_export() {

        $result = $this->Expense_model->csv_report();
       
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"Report .csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("Expense Date", "Amount", "Description", "Branch Name","Expense Category","Added By"));

        foreach ($result as $val) {

            fputcsv($handle, array($val["expense_date"], $val["amount"], $val["description"], $val["branch_name"],$val["name"],$val["AdminName"]));
        }
        fclose($handle);
        exit;
    }

    public function print_report() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();

        $data["details"] = $this->Expense_model->csv_report();

        //  $this->load->view('expense_report',$data);
    }

    public function expense_report() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $id = $data["login_data"]['id'];
        $data['start_date'] = $this->input->get("start_date");
        $data['end_date'] = $this->input->get("end_date");
        $data['branch'] = $this->input->get("branch");
        $data['city'] = $this->input->get("city");
       
        $start_date = null;
        $end_date = null;
        if ($data['start_date'] != "") {
            $start_date = $data['start_date'];
        }

        if ($data['end_date'] != "") {
            $end_date = $data['end_date'];
        }

        if ($data['branch'] != "") {
            $branch_data = $data['branch'];
        }

    $data['city_list'] = $this->Expense_model->master_get_tbl_val("test_cities", array("status" => 1), array("name", "asc"));
        $city_id = $this->input->get('city');
       $data['branch_list'] = $this->Expense_model->get_val('SELECT bm.*,test_cities.name FROM branch_master as bm LEFT JOIN test_cities ON bm.`city` = test_cities.`id` WHERE bm.status=1 AND bm.city="'.$city_id.'"');
        $data['query'] = $this->Expense_model->diffrent_report($start_date, $id, $end_date, $branch_data,$data['city']);
       // echo "<pre>"; print_r($data['query']);die;
        
//      / echo "<prE>";print_r($data['branch_list']);
        $this->load->view('header', $data);
        $this->load->view('nav', $data);
        $this->load->view('expense_report', $data);
        $this->load->view('footer');
    }

    public function expense_report1() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();

        // $id = $data["login_data"]['id'];
        //echo "<prE>";print_r($_GET);die;
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");

        $start_date = $this->input->get('start_date');

        $end_date = $this->input->get('end_date');
        $branch_data = $this->input->get('branch');

        $data['query'] = $this->Expense_model->diffrent_report($start_date, $end_date, $branch_data);

        $data['city_list'] = $this->Expense_model->master_get_tbl_val("test_cities", array("status" => 1), array("id", "desc"));

        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('expense_report', $data);
        $this->load->view('footer');
    }

    public function view_expense($id = null) { 
       
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();

        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
   
        $data['query'] = $this->Expense_model->manage_view_list($id);

        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('view_expense', $data);
        $this->load->view('footer');
    }

}

?>