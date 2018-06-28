<?php
Class Wallet_model extends CI_Model {

    function get_master_get_data($name, $condition, $order) {
        $this->db->order_by($order[0], $order[1]);
        $query = $this->db->get_where($name, $condition);
        return $query->result_array();
    }

    function master_update_data($name, $condition, $order) {
		//print_r($condition); die();
        $this->db->order_by($order[0], $order[1]);
        $query = $this->db->get_where($name, $condition);
        return $query->result_array();
    }
    
        public function master_fun_get_tbl_val($dtatabase, $condition, $order) {
        $this->db->order_by($order[0], $order[1]);
        $query = $this->db->get_where($dtatabase, $condition);
        $data['user'] = $query->result_array();
        return $data['user'];
    }
    
        public function master_fun_insert($table, $data) {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }
    
        public function master_fun_update($tablename, $cid, $data) {
        $this->db->where($cid[0], $cid[1]);
        $this->db->update($tablename, $data);
        return 1;
    }
public function contact_master($table_name, $data) {

        $this->db->insert($table_name, $data);
        return $this->db->insert_id();
    }
	
	public function wallet_history($user=null,$credit=null,$debit=null,$total=null,$date=null){
		$query ="SELECT  w.*,c.`full_name` FROM wallet_master w LEFT JOIN customer_master c ON c.id=w.`cust_fk` WHERE c.`status`=1";
		
		if($user != ""){
			
			$query .=" AND c.id='$user'"; 
		}
		if($credit != ""){
			
			$query .=" AND w.credit='$credit'"; 
		}
		if($debit != ""){
			
			$query .=" AND w.debit='$debit'"; 
		}
		if($total != ""){
			
			$query .=" AND w.total='$total'"; 
		}
		if ($date != "") {

            $query .= " AND DATE_FORMAT(w.created_date, '%d/%m/%Y') ='$date'";
        }
		$query = $this->db->query($query);
		 $data['user'] = $query->result_array();
        return $data['user'];
	}
	function payment_history($user_fk=null){
	//	$query = $this->db->query("SELECT id,debit,credit, DATE_FORMAT(DATE(created_time) ,'%d %b %y') AS date FROM `wallet_master` where cust_fk='$user_fk' and status=1");
		$query = "SELECT 
  p.* , GROUP_CONCAT(t.`test_name`) testname,c.full_name

FROM
  `payment` p
  LEFT JOIN job_master j 
  ON j.`id`=p.`job_fk`
 LEFT JOIN job_test_list_master jt
  ON j.`id`=jt.`job_fk` LEFT JOIN test_master t ON t.id=jt.`test_fk` LEFT JOIN customer_master c ON c.id=p.uid";

  if($cust_fk != ""){
			
			$query .=" WHERE p.uid = '$user_fk'"; 
		}
		
  $query .=" GROUP BY p.id";
   
    
		$query = $this->db->query($query);
		$query1 = $query->result_array();
		return $query1;	
	}
	public function num_row_srch($data){
		$query ="SELECT  w.*,c.`full_name` FROM wallet_master w LEFT JOIN customer_master c ON c.id=w.`cust_fk` WHERE c.`status`=1";
		if($data['user'] != ""){
			$user = $data['user'];
			$query .=" AND c.id='$user'"; 
		}
		if($data['credit'] != ""){
			$credit = $data['credit'];
			$query .=" AND w.credit='$credit'"; 
		}
		if($data['debit'] != ""){
			$debit = $data['debit'];
			$query .=" AND w.debit='$debit'"; 
		}
		if($data['total'] != ""){
			$total = $data['total'];
			$query .=" AND w.total='$total'"; 
		}
		if ($data['date'] != "") {
			$date = $data['date'];
            $query .= " AND DATE_FORMAT(w.created_time, '%d/%m/%Y') ='$date'";
        }
		$query = $this->db->query($query);
		 $data['user'] = $query->num_rows();
        return $data['user'];
	}
	public function row_srch($data, $limit, $start){
		$query ="SELECT  w.*,c.`full_name`,a.name as added_by_name FROM wallet_master w LEFT JOIN customer_master c ON c.id=w.`cust_fk` left join admin_master a on w.added_by=a.id WHERE c.`status`=1";
		if($data['user'] != ""){
			$user = $data['user'];
			$query .=" AND c.id='$user'"; 
		}
		if($data['credit'] != ""){
			$credit = $data['credit'];
			$query .=" AND w.credit='$credit'"; 
		}
		if($data['debit'] != ""){
			$debit = $data['debit'];
			$query .=" AND w.debit='$debit'"; 
		}
		if($data['total'] != ""){
			$total = $data['total'];
			$query .=" AND w.total='$total'"; 
		}
		if ($data['date'] != "") {
			$date = $data['date'];
            $query .= " AND DATE_FORMAT(w.created_time, '%d/%m/%Y') ='$date' ";
        }
		$query .= " order by w.id desc LIMIT $start , $limit";
		$query = $this->db->query($query);
		 $data['user'] = $query->result_array();
        return $data['user'];
	}
	


}

?>
