<?php

Class Client_model extends CI_Model {

 public function lab_num_rows($state_serch = null, $email = null,$mobile=null,$date=null) {

        $query = "SELECT id FROM collect_from WHERE status !='0' and type='2'";

        if ($state_serch != "") {

            $query .= " AND name like '%$state_serch%'";
        }
        if ($email != "") {

            $query .= " AND email like '%$email%'";
        }
		if ($mobile != "") {
				$query .= " AND mobile_number='$mobile'";
        }
		if ($date != "") {
			
			$date1 = explode('/',$date);
                $date2 = $date1[2]."-".$date1[1]."-".$date1[0];
			$dateco=date("Y-m-d", strtotime($date2));
			$query .= " AND STR_TO_DATE(createddate,'%Y-%m-%d')='$dateco'";
        }
        $query.=" ORDER BY name asc";

        $result = $this->db->query($query);
        return $result->num_rows();
    }
public function srch_lab_list($limit,$start) {

        $query = $this->db->query("SELECT id,name,email,mobile_number,createddate,status FROM collect_from WHERE status !='0' and type='2' ORDER BY name ASC LIMIT $start , $limit ");
        return $query->result_array();
    }
public function lab_data($state_serch = null, $email = null,$mobile=null,$date=null,$limit, $start) {

        $query = "SELECT id,name,email,mobile_number,createddate,status FROM collect_from WHERE status !='0' and type='2' ";
        if ($state_serch != "") {

            $query .= " AND name like '%$state_serch%'";
        }
        if ($email != "") {
            $query .= " AND email like '%$email%'";
        }
		if ($mobile != "") {
				$query .= " AND mobile_number like '$mobile'";
        }
		if ($date != "") {
			
			$date1 = explode('/',$date);
                $date2 = $date1[2]."-".$date1[1]."-".$date1[0];
			$dateco=date("Y-m-d", strtotime($date2));
			$query .= " AND STR_TO_DATE(createddate,'%Y-%m-%d')='$dateco'";
        }
        $query .= " ORDER BY name asc LIMIT $start,$limit";

        $result = $this->db->query($query);
        return $result->result_array();
   }
function test_list($lab_fk,$city) {
	
        /* $query = $this->db->query("SELECT l.id,l.`price`,l.`special_price`,t.`test_name`,l.b2b_price FROM sample_test_city_price l LEFT JOIN sample_test_master t ON t.`id`=l.`test_fk` WHERE l.`status`='1' and l.lab_fk='$lab_fk'  ORDER BY l.id desc LIMIT 5"); */
		
		$query = $this->db->query("SELECT s.price_special as special_price ,t.test_name,p.price,s.test_fk FROM b2b_testspecial_price s LEFT JOIN `test_master` t ON t.`id`=s.`test_fk` inner JOIN test_master_city_price p ON t.id=p.test_fk and p.city_fk='$city'  WHERE  s.status='1' AND s.lab_id='$lab_fk' GROUP BY s.`id`");
        return $query->result_array();
    }
function getclient_detils($lab_fk) {
	
	$this->db->select('c.*,t.name as cityname'); 
	$this->db->from('collect_from c');
	$this->db->join('test_cities t','t.id=c.city','left');
	$this->db->where('c.id',$lab_fk);
	$this->db->where('c.status !=','0');
	$query = $this->db->get();
        return $query->result_array();
	
}	

}

?>
