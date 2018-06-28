<?php

class Logistic_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    function getUser($user_id) {
        $query = $this->db->get_where("admin_master", array("id" => $user_id, "status" => "1"));
        return $query->row();
    }

    public function master_fun_get_tbl_val($dtatabase, $condition, $order, $limit = null) {
        $this->db->order_by($order[0], $order[1]);
        if ($limit != null) {
            $this->db->limit($limit[0], $limit[1]);
        }
        $query = $this->db->get_where($dtatabase, $condition);
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    function master_fun_insert($table, $data) {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function master_fun_update($tablename, $condition, $data) {
        $this->db->where($condition);
        $this->db->update($tablename, $data);
        return 1;
    }

    public function master_num_rows($table, $condition) {
        $query1 = $this->db->get_where($table, $condition);
        return $query1->num_rows();
    }

    public function get_server_time() {
        $query = $this->db->query("SELECT UTC_TIMESTAMP()");
        $data['user'] = $query->result_array();
        return $data['user'][0];
    }

    public function get_val($qry) {
        $query1 = $this->db->query($qry);
        $data['user'] = $query1->result_array();
        return $data['user'];
    }

    function sample_list($type = null, $one = null, $two = null) {
       /*  $limit = "";
        if ($one != null && $two != null) {
            $limit = " LIMIT $two,$one";
        }
        $qry = "SELECT `logistic_log`.*,`phlebo_master`.`name`,`phlebo_master`.`mobile`,`collect_from`.`name` AS `c_name`,sample_destination_lab.lab_fk as desti_lab,(select count(b2b_jobspdf.id) from b2b_jobspdf where b2b_jobspdf.job_fk=logistic_log.id and status='1' ) as treport,sample_job_master.customer_name  FROM `logistic_log` 
left JOIN `phlebo_master` ON `logistic_log`.`phlebo_fk`=`phlebo_master`.`id` and `phlebo_master`.`status` = '1' 
INNER JOIN `collect_from` ON `logistic_log`.`collect_from`=`collect_from`.`id`
left join sample_job_master on sample_job_master.barcode_fk = logistic_log.id";
        if ($type["type"] == 4) {
            $qry .= " INNER JOIN `sample_destination_lab` ON `logistic_log`.`id`=`sample_destination_lab`.`job_fk` and sample_destination_lab.lab_fk='" . $type["id"] . "'";
        }
        if ($type["type"] == 3) {
            $qry .= " LEFT JOIN `sample_destination_lab` ON `logistic_log`.`id`=`sample_destination_lab`.`job_fk`";
        }
        $qry .= " WHERE `logistic_log`.`status`='1' ORDER BY logistic_log.id DESC" . $limit;
        //echo $qry; die();
        $query = $this->db->query($qry);
        return $query->result_array(); */
		
$this->db->select("l.*,p.`name`,p.`mobile`,c.`name` AS `c_name`,tc.name as tetst_city_name,d.lab_fk as desti_lab,(select count(b2b_jobspdf.id) from b2b_jobspdf where b2b_jobspdf.job_fk=l.id and status='1' ) as treport,(SELECT name FROM `admin_master` a where a.status='1' and a.id=d.lab_fk) as desti_lab1,j.customer_name,j.payable_amount,CONCAT(s.first_name, ' ',s.last_name) As salesname");
$this->db->from('logistic_log AS l');
$this->db->join('phlebo_master p','p.id=l.phlebo_fk and p.status="1"','left');
$this->db->join('collect_from c','c.id=l.collect_from','left');
$this->db->join('sales_user_master s','s.id=c.sales_fk','left');
$this->db->join('b2b_test_cities tc','c.city=tc.id','left');
$this->db->join('sample_job_master j','j.barcode_fk=l.id','left');
if ($type["type"] == 4) {
$this->db->join('sample_destination_lab d','l.id=d.job_fk and d.lab_fk='.$type["id"].'','INNER');	
}
if ($type["type"] == 3) {
$this->db->join('sample_destination_lab d','l.id=d.job_fk','left');	
}
$this->db->where('l.status','1');
$this->db->GROUP_BY('l.id');
$this->db->order_by('l.id','desc');
$this->db->limit($one,$two);
$query = $this->db->get();
 return $query->result_array();
	
    }
	function samplelist_numrow($type = null, $name = null, $barcode = null, $date = null,$todate=null, $from = null,$patientsname = null,$salesperson=null,$sendto=null,$city=null,$status=null) {
		
        $this->db->select("l.id");
$this->db->from('logistic_log AS l');
$this->db->join('phlebo_master p','p.id=l.phlebo_fk and p.status="1"','left');
$this->db->join('collect_from c','c.id=l.collect_from','left');
$this->db->join('sample_job_master j','j.barcode_fk=l.id','left');
if ($type["type"] == 4) { $this->db->join('sample_destination_lab d','l.id=d.job_fk and d.lab_fk='.$type["id"].'','INNER');	
}
if ($type["type"] == 3) { if (!empty($sendto)){ $this->db->join('sample_destination_lab d',"l.id=d.job_fk and d.lab_fk='$sendto'",'INNER'); }else{ $this->db->join('sample_destination_lab d','l.id=d.job_fk','left'); }	
}
$this->db->where('l.status','1');
if (!empty($name)) { $this->db->like('p.name',$name);    }
if (!empty($barcode)) {	 $this->db->like('l.barcode',$barcode);	}

if (!empty($patientsname)){$this->db->like('j.customer_name',$patientsname);}


if (!empty($date)) {
	/* $this->db->where('DATE_FORMAT(l.scan_date,"%Y-%m-%d")',date("Y-m-d",strtotime($date))); */
$this->db->where("DATE_FORMAT(l.scan_date,'%Y-%m-%d') >=", date("Y-m-d", strtotime($date)));	
        }
if (!empty($todate)) {
	/* $this->db->where('DATE_FORMAT(l.scan_date,"%Y-%m-%d")',date("Y-m-d",strtotime($date))); */
$this->db->where("DATE_FORMAT(l.scan_date,'%Y-%m-%d') <=",date("Y-m-d", strtotime($todate)));	
        }
if (!empty($from)){ $this->db->where('l.collect_from',$from); /* $this->db->like('c.name',$from); */ }
if (!empty($salesperson)){ $this->db->where('c.sales_fk',$salesperson);}

$this->db->GROUP_BY('l.id');
$this->db->order_by('l.id','desc');
$query = $this->db->get();
 return $query->num_rows();
     
	 }

    function sample_list_num($type = null, $name = null, $barcode = null, $date = null,$todate=null, $from = null,$patientsname = null,$salesperson=null,$sendto=null,$city=null,$status=null,$one = null, $two = null) {
		
        /* $qry = "SELECT `logistic_log`.*,`phlebo_master`.`name`,`phlebo_master`.`mobile`,`collect_from`.`name` AS `c_name`,sample_destination_lab.lab_fk as desti_lab,(select count(b2b_jobspdf.id) from b2b_jobspdf where b2b_jobspdf.job_fk=logistic_log.id and status='1' ) as treport,sample_job_master.customer_name FROM `logistic_log` 
left JOIN `phlebo_master` ON `logistic_log`.`phlebo_fk`=`phlebo_master`.`id` and `phlebo_master`.`status` = '1' 
INNER JOIN `collect_from` ON `logistic_log`.`collect_from`=`collect_from`.`id`
left join sample_job_master on sample_job_master.barcode_fk = logistic_log.id";
        if ($type["type"] == 4) {
            $qry .= " INNER JOIN `sample_destination_lab` ON `logistic_log`.`id`=`sample_destination_lab`.`job_fk` and sample_destination_lab.lab_fk='" . $type["id"] . "'";
        }
        if ($type["type"] == 3) {
            $qry .= " LEFT JOIN `sample_destination_lab` ON `logistic_log`.`id`=`sample_destination_lab`.`job_fk` and sample_destination_lab.lab_fk='" . $type["id"] . "'";
        }
        $qry .= "WHERE  `logistic_log`.`status`='1'";
        if (!empty($name)) {
            $qry .= " AND `phlebo_master`.`name` LIKE '%" . $name . "%'";
        }
        if (!empty($barcode)) {
            $qry .= " AND `logistic_log`.`barcode` LIKE '%" . $barcode . "%'";
        }
        if (!empty($date)) {
            $qry .= " AND `logistic_log`.`scan_date`<'" . $date . " 23:59:59' AND `logistic_log`.`scan_date`>'" . $date . " 00:00:00'";
        }
        if (!empty($from)) {
            $qry .= " AND `collect_from`.`name` LIKE '%" . $from . "%'";
        }
        if ($one != null && $two != null) {
            $qry .= " LIMIT $two,$one";
        } */
		$this->db->select("l.*,p.`name`,p.`mobile`,c.`name` AS `c_name`,tc.name as tetst_city_name,d.lab_fk as desti_lab,(select count(b2b_jobspdf.id) from b2b_jobspdf where b2b_jobspdf.job_fk=l.id and status='1' ) as treport,(SELECT name FROM `admin_master` a where a.status='1' and a.id=d.lab_fk) as desti_lab1,j.customer_name,j.payable_amount,CONCAT(s.first_name, ' ',s.last_name) As salesname");
$this->db->from('logistic_log AS l');
$this->db->join('phlebo_master p','p.id=l.phlebo_fk and p.status="1"','left');
$this->db->join('collect_from c','c.id=l.collect_from','left');
$this->db->join('sales_user_master s','s.id=c.sales_fk','left');
$this->db->join('b2b_test_cities tc','c.city=tc.id','left');
$this->db->join('sample_job_master j','j.barcode_fk=l.id','left');
if ($type["type"] == 4) {
$this->db->join('sample_destination_lab d','l.id=d.job_fk and d.lab_fk='.$type["id"].'','INNER');	
}

if ($type["type"] == 3) { if (!empty($sendto)){ $this->db->join('sample_destination_lab d',"l.id=d.job_fk and d.lab_fk='$sendto'",'INNER'); }else{ $this->db->join('sample_destination_lab d','l.id=d.job_fk','left'); } }
$this->db->where('l.status','1');

if (!empty($name)) { $this->db->like('p.name',$name);    }
if (!empty($barcode)) {	 $this->db->like('l.barcode',$barcode);	}
if (!empty($patientsname)){$this->db->like('j.customer_name',$patientsname);}

if (!empty($date)) {
	/* $this->db->where('DATE_FORMAT(l.scan_date,"%Y-%m-%d")',date("Y-m-d",strtotime($date))); */
$this->db->where("DATE_FORMAT(l.scan_date,'%Y-%m-%d') >=", date("Y-m-d", strtotime($date)));	
        }
if (!empty($todate)) {
	
	/* $this->db->where('DATE_FORMAT(l.scan_date,"%Y-%m-%d")',date("Y-m-d",strtotime($date))); */
$this->db->where("DATE_FORMAT(l.scan_date,'%Y-%m-%d') <=",date("Y-m-d", strtotime($todate)));	

}
/* if(!empty($from)){ $this->db->like('c.name',$from); } */

if(!empty($from)){ $this->db->where('l.collect_from',$from); }
if(!empty($salesperson)){ $this->db->where('c.sales_fk',$salesperson);}
		
$this->db->GROUP_BY('l.id');
$this->db->order_by('l.id','desc');
$this->db->limit($one,$two);
$query = $this->db->get();
 return $query->result_array();
     }
function sample_list_export($type = null, $name = null, $barcode = null, $date = null,$todate=null, $from = null,$patientsname = null,$salesperson=null,$sendto=null,$city=null,$status=null) {
	
$this->db->select("l.*,p.`name`,p.`mobile`,c.`name` AS `c_name`,d.lab_fk as desti_lab,(select count(b2b_jobspdf.id) from b2b_jobspdf where b2b_jobspdf.job_fk=l.id and status='1' ) as treport,(SELECT name FROM `admin_master` a where a.status='1' and a.id=d.lab_fk) as desti_lab1,j.customer_name,CONCAT(s.first_name, ' ',s.last_name) As salesname");
$this->db->from('logistic_log AS l');
$this->db->join('phlebo_master p','p.id=l.phlebo_fk and p.status="1"','left');
$this->db->join('collect_from c','c.id=l.collect_from','left');
$this->db->join('sales_user_master s','s.id=c.sales_fk','left');
$this->db->join('sample_job_master j','j.barcode_fk=l.id','left');
if ($type["type"] == 4) {
$this->db->join('sample_destination_lab d','l.id=d.job_fk and d.lab_fk='.$type["id"].'','INNER');	
}
if ($type["type"] == 3) { if (!empty($sendto)){ $this->db->join('sample_destination_lab d',"l.id=d.job_fk and d.lab_fk='$sendto'",'INNER'); }else{ $this->db->join('sample_destination_lab d','l.id=d.job_fk','left'); } }
$this->db->where('l.status','1');
if (!empty($name)) { $this->db->like('p.name',$name);    }
if (!empty($barcode)) {	 $this->db->like('l.barcode',$barcode);	}
if (!empty($patientsname)){$this->db->like('j.customer_name',$patientsname);}

if (!empty($date)) {
	/* $this->db->where('DATE_FORMAT(l.scan_date,"%Y-%m-%d")',date("Y-m-d",strtotime($date))); */
$this->db->where("DATE_FORMAT(l.scan_date,'%Y-%m-%d') >=", date("Y-m-d", strtotime($date)));	
        }
if (!empty($todate)) {
	/* $this->db->where('DATE_FORMAT(l.scan_date,"%Y-%m-%d")',date("Y-m-d",strtotime($date))); */
$this->db->where("DATE_FORMAT(l.scan_date,'%Y-%m-%d') <=",date("Y-m-d", strtotime($todate)));	
        }
/* if(!empty($from)){ $this->db->like('c.name',$from); } */
if(!empty($from)){ $this->db->where('l.collect_from',$from); }
if(!empty($salesperson)){ $this->db->where('c.sales_fk',$salesperson);}
		
$this->db->GROUP_BY('l.id');
$this->db->order_by('l.id','desc');
$query = $this->db->get();
 return $query->result_array();
   }
    public function lab_num_rows($state_serch = null, $email = null) {

        $query = "SELECT id FROM collect_from WHERE status='1' ";

        if ($state_serch != "") {

            $query .= " AND name like '%$state_serch%'";
        }
        if ($email != "") {

            $query .= " AND email like '%$email%'";
        }
        $query .= " ORDER BY name asc";

        $result = $this->db->query($query);
        return $result->num_rows();
    }

    public function lab_data($state_serch = null, $email = null, $limit, $start) {

        $query = "SELECT id,name,email FROM collect_from WHERE status='1' ";

        if ($state_serch != "") {

            $query .= " AND name like '%$state_serch%'";
        }
        if ($email != "") {

            $query .= " AND email like '%$email%'";
        }
        $query .= " ORDER BY name asc LIMIT $start , $limit";

        $result = $this->db->query($query);
        return $result->result_array();
    }

    public function srch_lab_list($limit, $start) {

        $query = $this->db->query("SELECT 
  collect_from.id,
  collect_from.name,
  collect_from.email ,
  CONCAT(sales_user_master.`first_name`,' ',sales_user_master.`last_name`,' (',sales_user_master.`email`,' , ',sales_user_master.`mobile`,' )')AS sales_name
FROM
  collect_from 
  LEFT JOIN `sales_user_master` ON `sales_user_master`.id=`collect_from`.`sales_fk`
WHERE collect_from.status = '1'  ORDER BY collect_from.name ASC LIMIT $start , $limit ");
        return $query->result_array();
    }

    function test_list($lab_fk) {
        $query = $this->db->query("SELECT l.id,l.`price`,l.`special_price`,t.`test_name`,l.b2b_price FROM sample_test_city_price l LEFT JOIN sample_test_master t ON t.`id`=l.`test_fk` WHERE l.`status`='1' and l.lab_fk='$lab_fk'  ORDER BY l.id desc LIMIT 5");
        return $query->result_array();
    }
	public function getsampledetils($id) {
        $query = $this->db->query("SELECT l.id,l.`barcode`,l.scan_date,c.`name`,c.`mobile_number`,s.address,l.`createddate`,s.`customer_name`,s.mobile,s.customer_gender,s.customer_dob,s.doctor FROM logistic_log l LEFT JOIN collect_from c ON c.`id`=l.`collect_from` LEFT JOIN `sample_job_master` s ON s.`barcode_fk`=l.`id` WHERE l.`status`='1' AND l.`id`='$id' ");
        return $query->row();
    }
	public function creditget_last($labfk) {

        $query = $this->db->query("SELECT  id,`total`,lab_fk FROM `sample_credit` WHERE  STATUS='1' AND `job_fk`='$labfk' ORDER BY id DESC");
        $data['user'] = $query->row();
        return $data['user'];
    }
	public function getjobsid($id) {

        $query = $this->db->query("SELECT  id,barcode_fk,payable_amount FROM `sample_job_master` WHERE  status='1' AND barcode_fk='$id' ");
        $data['user'] = $query->row();
        return $data['user'];
     
	 }
	 public function fetchdatarow($selact,$table,$array){
		          $this->db->select($selact); 
        $query = $this->db->get_where($table,$array);
        return $query->row();
    }
	function todayrevanu($date) {
	
    $this->db->select("sum(j.price) as price");
	$this->db->from('sample_job_master AS j');
		$this->db->where('j.status','1');
		$this->db->where("DATE_FORMAT(j.date,'%Y-%m-%d')",$date);
		$query = $this->db->get();
        return $query->row();
    
	}
	function todaytotaltest($date) {
	
    $this->db->select("sum((SELECT count(DISTINCT t.id) FROM sample_job_test jt LEFT JOIN sample_test_city_price c ON c.id=jt.`test_fk` LEFT JOIN sample_test_master t ON t.`id`=c.test_fk WHERE jt.`job_fk`=j.id AND jt.status='1')) as tptaltest");
	$this->db->from('logistic_log AS l');
	$this->db->join('sample_job_master j','j.barcode_fk=l.id','left');
	$this->db->where("DATE_FORMAT(j.date,'%Y-%m-%d')",$date);
	$this->db->where('l.status','1');
	$this->db->GROUP_BY('l.id');
		$query = $this->db->get();
        return $query->row();
    
	}

}

?>
