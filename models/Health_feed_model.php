<?php

class Health_feed_model extends CI_Model {

	    public function __construct() {
        $this->load->database();
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
        $this->db->where('id', $cid);
        $this->db->update($tablename, $data);
        return 1;
    }
  public function get_server_time() {
        $query = $this->db->query("SELECT UTC_TIMESTAMP()");
        $data['user'] = $query->result_array();
        return $data['user'][0];
    }
	
	
	function get_active_record(){
        $query = $this->db->query("SELECT * FROM `health_feed_master` WHERE `status`='1' ORDER BY id desc");
        return $query->result_array();
    }
        function get_active_record1($one,$two){
       
    $query = $this->db->query("SELECT * FROM `health_feed_master` WHERE `status`='1' ORDER BY id desc LIMIT $two,$one ");
       
        return $query->result_array();
    }
}
?>
