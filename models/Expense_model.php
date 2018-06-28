<?php

Class Expense_model extends CI_Model {

    public function manage_condition_view($srchdata) {

        $temp = "";
        if ($srchdata['amount'] != "") {
            $amount = $srchdata['amount'];
            //echo "<prE>";print_r($id);die;
            $temp .= " AND amount LIKE '%$amount' ";
        }
 
        if ($srchdata['start_date'] != "" && $srchdata['end_date'] != "") {
            $start = explode("/", $srchdata['start_date']);

            $test = $start[2] . '-' . $start[1] . '-' . $start[0];

            $expense_date = explode("/", $srchdata['end_date']);
            $new_date = $expense_date[2] . '-' . $expense_date[1] . '-' . $expense_date[0];

            $temp .= " AND expense_date >= '$test' and expense_date <= '$new_date'";
            //$temp .= " AND DATE_FORMAT(expense_date, '%d-%m-%Y') >='$expense_date'";
        }


        if ($srchdata['description'] != "") {
            $amount = $srchdata['description'];
            //echo "<prE>";print_r($id);die;
            $temp .= " AND description LIKE '%$description' ";
        }
        if ($srchdata['expense_category_fk'] != "") {
            $expense_category_fk = $srchdata['expense_category_fk'];
            //echo "<prE>";print_r($id);die;
            $temp .= " AND expense_category_fk LIKE '%$expense_category_fk' ";
        }

        if ($srchdata['branch_fk'] != "") {

            $branch_fk = $srchdata['branch_fk'];
           
            //echo "<prE>";print_r($id);die;
            $temp .= " AND c.branch_fk = '%$branch_fk' ";
        }
        $query = $this->db->query("SELECT c.*,s.name FROM expense_master c LEFT JOIN expense_category_master s ON c.expense_category_fk=s.id  WHERE s.status=1 AND c.status=1 $temp");

        return $query->num_rows();
    }

    public function manage_view_list($id) {
     // $qry ="SELECT exp.*,us.name,br.branch_name,ep.name AS CategoryName FROM expense_master AS EXP LEFT JOIN expense_category_master AS ep ON exp.expense_category_fk = ep.id LEFT JOIN branch_master AS br ON exp.branch_fk = br.id LEFT JOIN admin_master AS us ON exp.created_by = us.id WHERE exp.status = 1 AND exp.id = '$id'";
     
      $query = $this->db->query("SELECT exp.*,us.name,br.branch_name,ep.name AS CategoryName FROM expense_master AS EXP LEFT JOIN expense_category_master AS ep ON exp.expense_category_fk = ep.id LEFT JOIN branch_master AS br ON exp.branch_fk = br.id LEFT JOIN admin_master AS us ON exp.created_by = us.id WHERE exp.status = 1 AND exp.id = '$id'");

        return $query->result_array();
    }

    public function master_get_tbl_val($dtatabase, $condition, $order) {
        $this->db->order_by($order[0], $order[1]);
        $query = $this->db->get_where($dtatabase, $condition);
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    public function master_get_insert($table, $data) {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function master_get_update($tablename, $cid, $data) {
        $this->db->where($cid);
        $this->db->update($tablename, $data);
        return 1;
    }

    public function num_row($table, $condition) {
        $query = $this->db->get_where($table, $condition);
        return $query->num_rows();
    }

    public function num_rows($table) {
        $query = $this->db->get($table);
        return $query->num_rows();
    }

    public function master_get_delete($tablename, $cid) {
        $this->db->where($cid[0], $cid[1]);
        $this->db->delete($tablename);
        return 1;
    }

    public function master_get_relation($table, $condition) {
        $query = $this->db->get_where($table, $condition);
        $query = $this->db->query($query);
        return $query->result_array();
    }

    function duplicate_val($duplicate) {
        $query = $this->db->query($duplicate);
        return $query->result_array();
    }

    public function expenselist_list($srchdata, $one, $two) { 
      
        $temp = "";

        if ($srchdata['expense_category_fk'] != "") {
            $expense_category_fk = $srchdata['expense_category_fk'];

            $temp .= " AND ep.expense_category_fk LIKE '%$expense_category_fk%' ";
        }
        if ($srchdata['amount'] != "") {
            $amount = $srchdata['amount'];
            $temp .= " AND ep.amount LIKE '%$amount%' ";
        }

        if ($srchdata['description'] != "") {
            $description = $srchdata['description'];
            $temp .= " AND ep.description LIKE '%$description%' ";
        }
        if ($srchdata['start_date'] != "" && $srchdata['end_date'] != "") {
            $start = explode("/", $srchdata['start_date']);

            $test = $start[2] . '-' . $start[1] . '-' . $start[0];

            $old_date = explode("/", $srchdata['end_date']);
            $new_date = $old_date[2] . '-' . $old_date[1] . '-' . $old_date[0];

            $temp .= " AND expense_date >= '$test' AND expense_date <= '$new_date'";
            //$temp .= " AND DATE_FORMAT(expense_date, '%d-%m-%Y') >='$expense_date'";
        }
        if ($srchdata['branch_fk'] != "") {

            $yyy = $srchdata['branch_fk'];
          
            //echo "<prE>";print_r($id);die;
            $temp .= " AND ep.branch_fk ='$yyy' ";
        }
     
        
                   $query = $this->db->query("SELECT ep.*,ec.name as CategoryName ,admin.name as AdminName,brn.branch_name FROM expense_master ep LEFT JOIN expense_category_master ec ON ep.expense_category_fk=ec.id LEFT JOIN admin_master as admin on admin.id = ep.created_by LEFT JOIN branch_master as brn on brn.id = ep.branch_fk WHERE ec.status=1 AND ep.status=1 $temp order by ep.id DESC  LIMIT $two,$one");

        //s$query = $this->db->query("SELECT ep.*,ec.name as CategoryName ,admin.name as AdminName,brn.branch_name FROM expense_master ep LEFT JOIN expense_category_master ec ON ep.expense_category_fk=ec.id LEFT JOIN admin_master as admin on admin.id = ep.created_by LEFT JOIN branch_master as brn on brn.id = ep.branch_fk WHERE ec.status=1 AND ep.status=1 $temp order by ep.id DESC  LIMIT $two,$one");
        
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    function get_master_get_data($name, $condition, $order) {
        $this->db->order_by($order[0], $order[1]);
        $query = $this->db->get_where($name, $condition);
        return $query->result_array();
    }

    public function csv_report() {

        $query = "SELECT ep.*,ec.name,br.branch_name,am.name as AdminName   FROM `expense_master`ep LEFT JOIN expense_category_master ec ON ep.expense_category_fk = ec.`id` left join branch_master as br on br.id = ep.branch_fk left join admin_master as am on am.id = ep.created_by  where ep.status = 1";
      //  print_r($query);die;
        $query .= " ORDER BY ep.id DESC ";
        $result = $this->db->query($query)->result_array();

        return $result;
    }

    function get_val($query1 = null) {
        $query = $this->db->query($query1);

        $data['user'] = $query->result_array();
        return $data['user'];
    }

    function diffrent_report($start_date = null, $id = null, $end_date = null,$branch_data = null,$city=null) {
 
        $date1 = explode("/", $start_date);

        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date2 = explode("/", $end_date);
    //print_r($date2);die;
        $ed = $date2[2] . "-" . $date2[1] . "-" . $date2[0];
    
       $qry = "select exp.*,sum(exp.amount) as total,us.name,br.branch_name,ep.name as CategoryName from expense_master as exp LEFT JOIN expense_category_master as ep on exp.expense_category_fk = ep.id LEFT JOIN branch_master as br on exp.branch_fk = br.id LEFT JOIN admin_master as us ON exp.created_by = us.id left join test_cities tc on tc.id=br.city where exp.status = 1 ";

    if($start_date != "" && $end_date != "") {
            $qry .= " AND exp.expense_date >= '" . $sd . "' AND exp.expense_date <= '" . $ed . "'";
        }
        if ($branch_data != "") {
        
            $qry .= " AND exp.branch_fk = '".$branch_data."' ";
        }
        if ($city != "") {
            $qry .= " AND tc.id  = '" . $city . "' ";
        }
        
        $qry .= " group by exp.branch_fk,exp.created_by order by exp.`branch_fk`";
  
        $query = $this->db->query($qry);
     //echo "<pre>";print_r($query);
        $query1 = $query->result_array();
        return $query1;
    }

}

?>
