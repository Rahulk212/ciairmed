<?php
Class User_wallet_model extends CI_Model {

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
	public function total_wallet($user_fk){
		$this->db->select_max('id');
$result = $this->db->get_where('wallet_master',array('cust_fk'=>$user_fk))->row();  
return $result->id;	
	}
	public function wallet_history($cust_fk){
		$query ="SELECT 
  w.id,
  w.debit,
  w.credit,w.total,w.type,
  DATE_FORMAT(w.created_time, '%d %b %y %h:%i %p') AS DATE, GROUP_CONCAT(t.`test_name`) testname,j.order_id,p.payomonyid

FROM
  `wallet_master` w 
  LEFT JOIN job_master j 
  ON j.`id`=w.`job_fk`
 LEFT JOIN job_test_list_master jt
  ON j.`id`=jt.`job_fk` LEFT JOIN test_master t ON t.id=jt.`test_fk` LEFT JOIN payment p ON p.id=w.payment_id
WHERE w.cust_fk ='$cust_fk'
  AND w.status = 1 GROUP BY w.id ORDER BY w.id DESC";
		$query = $this->db->query($query);
		 $data['user'] = $query->result_array();
        return $data['user'];
	}
	public function wallet_history1($cust_fk,$one, $two){
		$query ="SELECT 
  w.id,
  w.debit,
  w.credit,w.total,w.type,
  DATE_FORMAT(w.created_time, '%d %b %y %h:%i %p') AS DATE, GROUP_CONCAT(t.`test_name`) testname,j.order_id,p.payomonyid

FROM
  `wallet_master` w 
  LEFT JOIN job_master j 
  ON j.`id`=w.`job_fk`
 LEFT JOIN job_test_list_master jt
  ON j.`id`=jt.`job_fk` LEFT JOIN test_master t ON t.id=jt.`test_fk` LEFT JOIN payment p ON p.id=w.payment_id
WHERE w.cust_fk ='$cust_fk'
  AND w.status = 1 GROUP BY w.id ORDER BY w.id DESC LIMIT $two,$one ";
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

  if($user_fk != ""){
			
			$query .=" WHERE p.uid = '$user_fk'"; 
		}
		
  $query .=" GROUP BY p.id";
   
    
		$query = $this->db->query($query);
		$query1 = $query->result_array();
		return $query1;	
	}
	
	


}

?>
