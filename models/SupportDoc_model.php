<?php

Class SupportDoc_model extends CI_Model {

    public function master_get_update($tablename, $cid, $data) {
        $this->db->where('id',$cid);
        $this->db->update($tablename, $data);
        return 1;
    }

    public function master_get_insert($table, $data) {
        $this->db->insert($table, $data);
        return true;
        return $this->db->insert_id();
    }

    public function master_get_table($table, $where, $order) {

        $this->db->order_by($order[0], $order[1]);
        $query = $this->db->get_where($table, $where);
        $data = $query->result_array();
        return $data;
    }

    public function master_get_search($one, $two) {

        /* $query = $this->db->query("SELECT support_system.*, admin_master.name, admin_master.id as aid FROM support_system LEFT JOIN admin_master ON support_system.created_by = admin_master.id ORDER BY id desc LIMIT $two,$one"); */
		$array =array('am.id as aid','am.name','ss.*') ;
		$this->db->select($array);
		$this->db->from('support_system as ss');
		$this->db->join('admin_master as am','ss.created_by = am.id','left');
                $this->db->where("ss.status !=","0");
		$this->db->limit($one,$two);
		$this->db->ORDER_BY('ss.id','desc');
		$query = $this->db->get();
				
        return $query->result_array();
		
	}
	
    public function num_row() {
        $query = $this->db->query("SELECT * FROM `support_system` where status != 0  ORDER BY id DESC");
        return $query->num_rows();
    }

    public function master_get_where_condtion($table, $where, $order) {
        $this->db->order_by($order[0], $order[1]);
        $query = $this->db->get_where($table, $where);
        return $query->result_array();
    }

    public function master_get_spam($tablename, $cid, $data) {
        $this->db->where(array('id' => $cid));
        $this->db->update($tablename, $data);
        return 1;
    }

}

?>
