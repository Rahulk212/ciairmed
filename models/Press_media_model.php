<?php

class Press_media_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }
	public function num_row($fullname) {

        $this->db->select('*');
        if ($fullname != "") {
            $this->db->like('title', $fullname);
        }
        $this->db->where('status', '1');
        $this->db->ORDER_BY('id', 'desc');
        $query = $this->db->get('press_media_master');


        return $query->num_rows();
    }
	
	public function insert($data) { 
	  
		$this->db->insert('press_media_master',$data);
		return true;
        return $this->db->insert_id();		
		 
	  }
	public function delete_pc($pcid,$data){
		$this->db->where(array('id' => $pcid));
        $this->db->update('press_media_master', $data);
        return true;
	}
	public function get_pc($pcid){
		$query = $this->db->get_where('press_media_master',array('id'=> $pcid));
		$data=$query->row();
        return $data;
	}
	public function update($pcid,$data){
		$this->db->set($data);
		$this->db->where("id",$pcid);
		$this->db->update("press_media_master", $data);
		return true;
	}
	public function search($fullname, $one, $two) {
        $this->db->select('*');
        if ($fullname != "") {
            $this->db->like('title', $fullname);
        }
        $this->db->where('status', '1');
       

        $this->db->ORDER_BY('ID', 'desc');
        $this->db->limit($one, $two);
        $query = $this->db->get('press_media_master');


        return $query->result_array();
    }
}
?>