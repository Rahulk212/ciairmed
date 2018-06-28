<?php

class Menu_model extends CI_Model {

	    public function __construct() {
        $this->load->database();
    }
	public function master_fun_insert($table, $data) {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }
	
	function project_active_record1($one,$two){
       
    $query = $this->db->query("SELECT * FROM `bmh_user_project_master` WHERE `status`='1' ORDER BY createddate DESC LIMIT $two,$one ");
       
        return $query->result_array();
    }
	public function master_fun_update1($tablename,$condition, $data) {
        $this->db->where($condition);
        $this->db->update($tablename, $data);
        return 1;
    }
	function project_list($id){
       
    $query = $this->db->query("SELECT * FROM `bmh_user_project_master` WHERE `status`='1' AND `id`='$id'");   
        return $query->result_array();
    }
	function user_list(){
       
    $query = $this->db->query("SELECT * FROM `bmh_registration` WHERE `status`='1'");   
        return $query->result_array();
    }	
	public function get_data($query)
	{
		 $query1 = $this->db->query($query." ORDER BY id DESC");
        $data['user'] = $query1->result_array();
        return $data['user'];
	}
	
	  function get_active_record3($query,$one,$two){
    $query = $this->db->query($query." ORDER BY id DESC  limit ". $two.",".$one);   
        return $query->result_array();
    }
	function status_active_record($id){
        $query = $this->db->query("SELECT * FROM `bmh_project_status_master` WHERE `status`='1' AND `project_fk`='$id' ORDER BY createddate DESC");     
        return $query->result_array();
    }
	function status_active_record1($id,$one,$two){
       
    $query = $this->db->query("SELECT * FROM `bmh_project_status_master` WHERE `status`='1' AND `project_fk`='$id' ORDER BY createddate DESC LIMIT $two,$one");
       
        return $query->result_array();
    }
	function status_list($id){
       
    $query = $this->db->query("SELECT * FROM `bmh_project_status_master` WHERE `status`='1' AND `id`='$id'");   
        return $query->result_array();
    }
	function project_name($id){
       
    $query = $this->db->query("SELECT project_name FROM `bmh_user_project_master` WHERE `status`='1' AND `id`='$id'");   
        return $query->result_array();
    }
	
	
	
	function menu_list(){
        $query = $this->db->query("SELECT * FROM `bmh_menu_master` WHERE `status`='1' ORDER BY id DESC");     
        return $query->result_array();
    }
	function menu_detail($id){
       
    $query = $this->db->query("SELECT * FROM `bmh_menu_master` WHERE `status`='1' AND `id`='$id'");   
        return $query->result_array();
    }
	function sub_menu_list(){
        $query = $this->db->query("SELECT * FROM `bmh_sub_menu_master` WHERE `status`='1' ORDER BY id DESC");     
        return $query->result_array();
    }
	function sub_menu_detail($id){
       
    $query = $this->db->query("SELECT * FROM `bmh_sub_menu_master` WHERE `status`='1' AND `id`='$id'");   
        return $query->result_array();
    }
}
?>
