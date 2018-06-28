<?php

class Invoice_model extends CI_Model {

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

    
	 public function fetchdatarow($selact,$table,$array){
		          $this->db->select($selact); 
        $query = $this->db->get_where($table,$array);
        return $query->row();
    }
function sample_listinvoce($date = null,$todate=null, $from = null) {
		
$this->db->select("l.id,DATE_FORMAT(l.scan_date,'%d-%m-%Y') as regdate,l.collect_from as labid,j.customer_name as patientname,j.payable_amount as amt,(SELECT GROUP_CONCAT(DISTINCT t.test_name) FROM sample_job_test jt LEFT JOIN sample_test_city_price c ON c.id=jt.`test_fk` LEFT JOIN sample_test_master t ON t.`id`=c.test_fk WHERE jt.`job_fk`=j.id AND jt.status='1') as testname");
$this->db->from('logistic_log AS l');
$this->db->join('phlebo_master p','p.id=l.phlebo_fk and p.status="1"','left');
$this->db->join('collect_from c','c.id=l.collect_from','left');
$this->db->join('b2b_test_cities tc','c.city=tc.id','left');
$this->db->join('sample_job_master j','j.barcode_fk=l.id','left');

if (!empty($date)) {

$this->db->where("DATE_FORMAT(l.scan_date,'%Y-%m-%d') >=", date("Y-m-d", strtotime($date)));	
        }
if (!empty($todate)) {
	
$this->db->where("DATE_FORMAT(l.scan_date,'%Y-%m-%d') <=",date("Y-m-d", strtotime($todate)));	
        }
if (!empty($from)) {
$this->db->where("l.collect_from",$from);	
        }
$this->db->where('l.status','1');
$this->db->GROUP_BY('l.id');
$this->db->order_by('l.id','desc');
$query = $this->db->get();
 return $query->result_array();
     }
function sample_listinvoce_csv($date = null,$todate=null, $from = null) {
	
$this->db->select("l.id,DATE_FORMAT(l.scan_date,'%d-%m-%Y') as regdate,l.collect_from as labid,j.customer_name as patientname,(SELECT GROUP_CONCAT(DISTINCT t.test_name) FROM sample_job_test jt LEFT JOIN sample_test_city_price c ON c.id=jt.`test_fk` LEFT JOIN sample_test_master t ON t.`id`=c.`test_fk` WHERE jt.`job_fk`=j.id AND jt.status='1') as Investigation,j.payable_amount as amt");
$this->db->from('logistic_log AS l');
$this->db->join('phlebo_master p','p.id=l.phlebo_fk and p.status="1"','left');
$this->db->join('collect_from c','c.id=l.collect_from','left');
$this->db->join('b2b_test_cities tc','c.city=tc.id','left');
$this->db->join('sample_job_master j','j.barcode_fk=l.id','left');

if (!empty($date)) {

$this->db->where("DATE_FORMAT(l.scan_date,'%Y-%m-%d') >=", date("Y-m-d", strtotime($date)));	
        }
if (!empty($todate)) {
	
$this->db->where("DATE_FORMAT(l.scan_date,'%Y-%m-%d') <=",date("Y-m-d", strtotime($todate)));	
        }
if (!empty($from)) {
$this->db->where("l.collect_from",$from);	
        }
$this->db->where('l.status','1');		
$this->db->GROUP_BY('l.id');
$this->db->order_by('l.id','desc');
$query = $this->db->get();
 return $query;
     }
function getclient_detils($lab_fk) {
	
	$this->db->select("c.*,t.name as cityname,CONCAT(s.first_name,' ',s.last_name) as salesname"); 
	$this->db->from('collect_from c');
	$this->db->join('test_cities t','t.id=c.city','left');
	$this->db->join('sales_user_master s','s.id=c.sales_fk','left');
	$this->db->where('c.id',$lab_fk);
	$this->db->where('c.status','1');
	$query = $this->db->get();
        return $query->row();
	
} 
}

?>
