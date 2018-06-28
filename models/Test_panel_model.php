<?php

Class Test_panel_model extends CI_Model {

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

    public function fetchdatarow($selact, $table, $array) {
        $this->db->select($selact);
        $query = $this->db->get_where($table, $array);
        return $query->row();
    }

    public function num_row($table, $condition) {
        $query = $this->db->get_where($table, $condition);
        return $query->num_rows();
    }

    public function contact_master($table_name, $data) {

        $this->db->insert($table_name, $data);
        return $this->db->insert_id();
    }
	
	
	 public function test_panel_list($name,$one,$two)
	{
		
		$this->db->select("*");
		$this->db->from('test_panel');
		$this->db->where('status','1');
		
		
		if ($name!= "") {
			
            $this->db->LIKE('name',$name);
        }
		
		
		$this->db->limit($one,$two);
		$query = $this->db->get(); 
		return $query->result();
		
		
	}
	
	
	
	 public function test_panel_list_num($name)
	{
		
		$this->db->select("*");
		$this->db->from('test_panel');
		$this->db->where('status','1');
		
		
		if ($name!= "") {
			
            $this->db->LIKE('name',  $name);
        }
		
		
		$query = $this->db->get(); 
		return $query->num_rows();
		
		
	}



}

?>
