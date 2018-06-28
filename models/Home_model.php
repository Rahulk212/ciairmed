<?php

Class Home_model extends CI_Model 
{
	function getongoing(){
		$query = $this->db->get_where("bmh_project_master",array("status"=>'1',"type" => '0'));	
		return $query->num_rows();
	}
	function getcomplete(){
		$query = $this->db->get_where("bmh_project_master",array("status"=>'1',"type" => '1'));	
		return $query->num_rows();
	}
	function getsubcategory(){
		$this->db->join("category_master","category_master.id=subcategory_master.category_id");
		$query = $this->db->get_where("subcategory_master",array("category_master.status"=>'1',"subcategory_master.status"=>'1'));	
		return $query->num_rows();
	}
}

?>