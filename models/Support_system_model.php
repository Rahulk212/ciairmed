<?php
Class Support_system_model extends CI_Model {

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
	function updateRowWhere($table, $where, $data) {
    $this->db->where($where);
    $this->db->update($table, $data);
	return 1;
}
    
        public function master_fun_insert($table, $data) {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }
    
        public function master_fun_update($tablename, $cid, $data) {
        $this->db->where('id', $cid);
        $this->db->update($tablename, $data);
        return 1;
    }
public function contact_master($table_name, $data) {

        $this->db->insert($table_name, $data);
        return $this->db->insert_id();
    }
	
	public function issue_list(){
		$query ="SELECT  t.*,c.full_name from ticket_master t left join customer_master c on c.id=t.user_id order by t.id desc";
		
		$query = $this->db->query($query);
		 $data['user'] = $query->result_array();
        return $data['user'];
	}
	public function ticket_details($ticket){
		$query ="SELECT m.message,m.type,t.*,c.`full_name` FROM message_master m LEFT JOIN ticket_master t ON t.id=m.`ticket_fk` LEFT JOIN customer_master c ON c.id=t.`user_id` WHERE t.`ticket`='$ticket'";
		
		
		$query = $this->db->query($query);
		 $data['user'] = $query->result_array();
        return $data['user'];
	}
	public function num_row_srch($cnd){
		if($cnd['ticket'] != ""){
			$ticket = $cnd['ticket'];
            $query1 .= " AND t.ticket  LIKE '%".$ticket."%'";
		}
		if($cnd['subject'] != ""){
			$ticket = $cnd['subject'];
            $query1 .= " AND t.title  LIKE '%".$ticket."%'";
		}
		if($cnd['user'] != ""){
			$ticket = $cnd['user'];
            $query1 .= " AND t.user_id = '$ticket'";
		}
		if($cnd['status'] != ""){
			$ticket = $cnd['status'];
            $query1 .= " AND t.status = '$ticket'";
		}
		$query ="SELECT  t.*,c.full_name from ticket_master t left join customer_master c on c.id=t.user_id  where t.ticket != '' and c.status = '1' and c.active = '1' $query1  order by t.id desc";	
		$query = $this->db->query($query);
		$data['user'] = $query->num_rows();
        return $data['user'];
	}
	public function row_srch($cnd, $limit, $start){
		$query = '';
		if($cnd['ticket'] != ""){
			$ticket = $cnd['ticket'];
            $query1 .= " AND t.ticket  LIKE '%".$ticket."%'";
		}
		if($cnd['subject'] != ""){
			$ticket = $cnd['subject'];
            $query1 .= " AND t.title  LIKE '%".$ticket."%'";
		}
		if($cnd['user'] != ""){
			$ticket = $cnd['user'];
            $query1 .= " AND t.user_id = '$ticket'";
		}
		if($cnd['status'] != ""){
			$ticket = $cnd['status'];
            $query1 .= " AND t.status = '$ticket'";
		}
		$query ="SELECT  t.*,c.full_name,c.id as cust_id from ticket_master t left join customer_master c on c.id=t.user_id where t.ticket != '' and c.status = '1' and c.active = '1' $query1 order by t.id desc LIMIT $start , $limit";	
		$query = $this->db->query($query);
		$data['user'] = $query->result_array();
        return $data['user'];
	}


}

?>
