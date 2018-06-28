<?php
Class Outsource_model extends CI_Model {

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
	
	public function citylist(){
		$query = $this->db->query("SELECT c.*,s.state_name,co.country_name FROM city c LEFT JOIN state s ON c.state_fk=s.id LEFT JOIN country co  ON c.`country_fk`=co.id  WHERE s.status=1 AND c.status=1");
		 $data['user'] = $query->result_array();
        return $data['user'];
	}
	
	public function statelist(){
		$query = $this->db->query("SELECT s.*,c.country_name FROM state s LEFT JOIN country c ON c.id=s.`country_fk` WHERE s.status=1 AND c.status=1");
		 $data['user'] = $query->result_array();
        return $data['user'];
	}
	public function doctorlist($one, $two){
		$query = $this->db->query("SELECT * from doctor_master where status IN (1,2) order by id LIMIT $two,$one");
		 $data['user'] = $query->result_array();
        return $data['user'];
	}
	public function doctorcount(){
		$query = $this->db->query("SELECT * from doctor_master where status IN (1,2)");
		 return $query->num_rows();
	}
	public function outcount_list($srch){
		$temp = "";
		if($srch['name'] != "" ){
			$name = $srch['name'];
			$temp .= " AND name LIKE '%$name%' ";
		}
                if($srch['email'] != "" ){
			$email = $srch['email'];
			$temp .= " AND email LIKE '%$email%' ";
		}
		$query = $this->db->query("SELECT * from outsource_master where status = '1' $temp");
		return $query->num_rows();
	}
	public function outslist_list($srch,$one, $two){
		$temp = "";
		if($srch['name'] != "" ){
			$name = $srch['name'];
			$temp .= " AND o.name LIKE '%$name%' ";
		}
                if($srch['email'] != "" ){
			$email = $srch['email'];
			$temp .= " AND o.email LIKE '%$email%' ";
		}
		$query = $this->db->query("SELECT o.*,c.city_name as city,s.state_name as state,b.branch_name from outsource_master o left join city c on c.id=o.city_fk left join state s on s.id=o.state_fk left join branch_master b on b.id=o.branch_fk where o.status = '1' $temp order by o.id LIMIT $two,$one");
		$data['user'] = $query->result_array();
        return $data['user'];
	}
}

?>
