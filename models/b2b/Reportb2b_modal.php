<?php

class Reportb2b_modal extends CI_Model {

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

    public function reportnumget($labs = null, $start_date = null, $end_date = null) {

        $this->db->select("l.id");
        $this->db->from('logistic_log AS l');
        $this->db->join('collect_from c', 'c.id=l.collect_from', 'left');
        $this->db->join('sample_job_master j', 'j.barcode_fk=l.id', 'left');
        $this->db->where('l.status', '1');
        if ($start_date != "") {
            $this->db->where("STR_TO_DATE(l.createddate,'%Y-%m-%d') >=", date("Y-m-d", strtotime($start_date)));
        }
        if ($end_date != "") {
            $this->db->where("STR_TO_DATE(l.createddate,'%Y-%m-%d') <=", date("Y-m-d", strtotime($end_date)));
        }

        if ($labs != "") {
            $this->db->where('c.id', $labs);
        }
        $this->db->GROUP_BY('l.id');
        $query = $this->db->get();

        return $query->num_rows();
    }

    public function reportget_result($one, $two, $labs = null, $start_date = null, $end_date = null) {

        $this->db->select("l.id,c.name,j.price,j.payable_amount,j.`discount`,j.payable_amount,j.`date`,(SELECT COUNT(s.id) FROM sample_job_test s WHERE s.job_fk=j.`id` AND s.status='1' ) AS totaltest");
        $this->db->from('logistic_log AS l');
        $this->db->join('collect_from c', 'c.id=l.collect_from', 'left');
        $this->db->join('sample_job_master j', 'j.barcode_fk=l.id', 'left');
        $this->db->where('l.status', '1');
        if ($labs != "") {
            $this->db->where('c.id', $labs);
        }
        if ($start_date != "") {
            $this->db->where("STR_TO_DATE(l.createddate,'%Y-%m-%d') >=", date("Y-m-d", strtotime($start_date)));
        }
        if ($end_date != "") {
            $this->db->where("STR_TO_DATE(l.createddate,'%Y-%m-%d') <=", date("Y-m-d", strtotime($end_date)));
        }
        $this->db->GROUP_BY('l.id');
        $this->db->order_by('l.id', 'desc');
        $this->db->limit($one, $two);
        $query = $this->db->get();
        $data['user'] = $query->result();
        return $data['user'];
    }

    public function payreportnumget($labs = null, $start_date = null, $end_date = null) {

        $this->db->select("s.id,l.barcode,c.name,s.credit,s.debit,s.total,s.note,s.created_date,(SELECT COUNT(s1.id) FROM sample_job_test s1 WHERE s1.job_fk=j.`id` AND s1.status='1' ) AS totaltest");
        $this->db->from('sample_credit s');
        $this->db->join('collect_from c', 'c.id=s.lab_fk', 'left');
        $this->db->join('sample_job_master j', 'j.barcode_fk=s.job_fk', 'left');
        $this->db->join('logistic_log l', 'l.id=s.job_fk', 'left');
        $this->db->where('s.status', '1');
        if ($labs != "") {
            $this->db->where('s.lab_fk', $labs);
        }
        if ($start_date != "") {
            $this->db->where("STR_TO_DATE(s.created_date,'%Y-%m-%d') >=", date("Y-m-d", strtotime($start_date)));
        }
        if ($end_date != "") {
            $this->db->where("STR_TO_DATE(s.created_date,'%Y-%m-%d') <=", date("Y-m-d", strtotime($end_date)));
        }
        $this->db->GROUP_BY('s.id');
        $query = $this->db->get();

        return $query->num_rows();
    }

    public function paymentreport_get($one, $two, $labs = null, $start_date = null, $end_date = null) {

        $this->db->select("s.id,l.barcode,c.name,s.credit,s.debit,s.total,s.note,s.created_date,(SELECT COUNT(s1.id) FROM sample_job_test s1 WHERE s1.job_fk=j.`id` AND s1.status='1' ) AS totaltest");
        $this->db->from('sample_credit s');
        $this->db->join('collect_from c', 'c.id=s.lab_fk', 'left');
        $this->db->join('sample_job_master j', 'j.barcode_fk=s.job_fk', 'left');
        $this->db->join('logistic_log l', 'l.id=s.job_fk', 'left');
        $this->db->where('s.status', '1');
        if ($labs != "") {
            $this->db->where('s.lab_fk', $labs);
        }
        if ($start_date != "") {
            $this->db->where("STR_TO_DATE(s.created_date,'%Y-%m-%d') >=", date("Y-m-d", strtotime($start_date)));
        }
        if ($end_date != "") {
            $this->db->where("STR_TO_DATE(s.created_date,'%Y-%m-%d') <=", date("Y-m-d", strtotime($end_date)));
        }
        $this->db->GROUP_BY('s.id');
        $this->db->order_by('s.id', 'desc');
        $this->db->limit($one, $two);
        $query = $this->db->get();

        return $query->result();
    }

    public function gettotalreport() {
        $this->db->select("SUM(credit) AS credit,SUM(debit) AS debit");
        $this->db->from('sample_credit');
        $this->db->where('status', '1');
        $query = $this->db->get();
        return $query->row();
    }

    function get_job_report($labs = null, $start_date = null, $end_date = null) {
      if($start_date != ""){  $nd = explode("/",$start_date);
        $sd = $nd[2]."-".$nd[1]."-".$nd[0];
	  $sestartdate="AND `sample_job_master`.`date`>='$sd 00:00:00'";  }else{ $sestartdate=""; }
        
       if($end_date != ""){  $nd = explode("/",$end_date);
	   $ed = $nd[2]."-".$nd[1]."-".$nd[0];
$seenddate="AND `sample_job_master`.`date`<='$ed 23:59:59'";	   
	   }else{ $seenddate=""; }
		
		
        $qry = "SELECT 
  `logistic_log`.barcode,
  collect_from.`name` AS lab_name,
  `sample_job_master`.id,
  sample_job_master.`order_id`,
  sample_job_master.`doctor`,
  sample_job_master.`price`,
  sample_job_master.`customer_name`,
  sample_job_master.`customer_gender`,
  `sample_job_master`.`date` ,
  sample_job_master.barcode_fk
FROM
  `logistic_log` 
  LEFT JOIN sample_job_master 
    ON `logistic_log`.`id` = `sample_job_master`.`barcode_fk` 
    LEFT JOIN `collect_from` ON `collect_from`.`id`=`logistic_log`.`collect_from`
WHERE `logistic_log`.`status` = '1' 
  AND `sample_job_master`.`status` != '0' 
  AND `logistic_log`.`collect_from` = '$labs' $sestartdate $seenddate";
        $query = $this->db->query($qry);
        return $query->result_array();
    }
function getclient_detils($lab_fk) {
	
	$this->db->select('c.*,t.name as cityname'); 
	$this->db->from('collect_from c');
	$this->db->join('test_cities t','t.id=c.city','left');
	$this->db->where('c.id',$lab_fk);
	$this->db->where('c.status','1');
	$query = $this->db->get();
        return $query->row();
	
}
}

?>
