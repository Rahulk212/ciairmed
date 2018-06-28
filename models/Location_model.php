<?php
Class Location_model extends CI_Model {

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
	
	public function citylist($one, $two){
		$query = $this->db->query("SELECT c.*,s.state_name FROM city c LEFT JOIN state s ON c.state_fk=s.id WHERE s.status=1 AND c.status=1 order by c.id DESC  LIMIT $two,$one");
		 $data['user'] = $query->result_array();
        return $data['user'];
	}
	public function citycount(){
		$query = $this->db->query("SELECT c.*,s.state_name FROM city c LEFT JOIN state s ON c.state_fk=s.id  WHERE s.status=1 AND c.status=1");
		 return $query->num_rows();
	}
	public function citycount_list($srch){
		$temp = "";
		if($srch['state'] != ""){
			$id = $srch['state'];
			$temp .= " AND s.id = $id ";
		}
		if($srch['city'] != ""){
			$id = $srch['city'];
			$temp .= " AND c.city_name LIKE '%$id%'";
		}
		$query = $this->db->query("SELECT c.*,s.state_name FROM city c LEFT JOIN state s ON c.state_fk=s.id  WHERE s.status=1 AND c.status=1 $temp");
		 return $query->num_rows();
	}
	public function citylist_list($srch, $one, $two){
		$temp = "";
		if($srch['state'] != ""){
			$id = $srch['state'];
			$temp .= " AND s.id = $id ";
		}
		if($srch['city'] != ""){
			$id = $srch['city'];
			$temp .= " AND c.city_name LIKE '%$id%'";
		}
		$query = $this->db->query("SELECT c.*,s.state_name FROM city c LEFT JOIN state s ON c.state_fk=s.id WHERE s.status=1 AND c.status=1 $temp order by c.id DESC  LIMIT $two,$one");
		 $data['user'] = $query->result_array();
        return $data['user'];
	}
	public function contact_us_list($data,$one, $two){
		$query = "SELECT * FROM contact_us WHERE status= '1' ";
		if($data['name']){
			$email = $data['name'];
            $query .= " AND name LIKE '%".$email."%'";
		}
		if($data['email']){
			$email = $data['email'];
            $query .= " AND email LIKE '%".$email."%'";
		}
		if($data['mobile']){
			$email = $data['mobile'];
            $query .= " AND mobile LIKE '%".$email."%'";
		}
		if($data['subject']){
			$email = $data['subject'];
            $query .= " AND subject LIKE '%".$email."%'";
		}
		if($data['message']){
			$email = $data['message'];
            $query .= " AND message LIKE '%".$email."%'";
		}
		$query .= " order by id DESC  LIMIT $two,$one";
		$query = $this->db->query($query);
		 $data['user'] = $query->result_array();
        return $data['user'];
	}
	public function contact_us_count($data){
		$query = "SELECT * FROM contact_us WHERE status=1";
		if($data['name']){
			$email = $data['name'];
            $query .= " AND name LIKE '%".$email."%'";
		}
		if($data['email']){
			$email = $data['email'];
            $query .= " AND email LIKE '%".$email."%'";
		}
		if($data['mobile']){
			$email = $data['mobile'];
            $query .= " AND mobile LIKE '%".$email."%'";
		}
		if($data['subject']){
			$email = $data['subject'];
            $query .= " AND subject LIKE '%".$email."%'";
		}
		if($data['message']){
			$email = $data['message'];
            $query .= " AND message LIKE '%".$email."%'";
		}
		$query = $this->db->query($query);
		 return $query->num_rows();
	}
	
	public function statelist(){
		$query = $this->db->query("SELECT s.* FROM state s WHERE s.status=1 order by s.state_name asc");
		 $data['user'] = $query->result_array();
        return $data['user'];
	}
	public function num_row_srch_state_list($state_serch = null) {

        $query = "SELECT * FROM state WHERE status='1' ";

        if ($state_serch != "") {

            $query .= " AND state_name like '%$state_serch%'";
        }
        $query .= " ORDER BY state_name asc";

	$result = $this->db->query($query);
	return $result->num_rows();
    }
	public function row_srch_state_list($state_serch = null, $limit, $start) {

        $query = "SELECT * FROM state WHERE status='1' ";

        if ($state_serch != "") {

            $query .= " AND state_name like '%$state_serch%'";
        }
        $query .= " ORDER BY state_name asc";

	$result = $this->db->query($query);
        return $result->result_array();

    }
	public function srch_state_list($limit, $start) {

	$query = $this->db->query("SELECT * FROM state WHERE status='1' ORDER BY state_name ASC LIMIT $start , $limit ");
        return $query->result_array();
    }
	public function num_row($table,$condition){
        $query= $this->db->get_where($table,$condition);
        return $query->num_rows(); 
    }


}

?>
