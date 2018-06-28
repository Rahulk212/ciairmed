<?php

Class Patholab_api_model extends CI_Model {

    public function master_fun_insert($table, $data) {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    function get_job_status($center = null) {
        $qry = "SELECT `status`,COUNT(*) AS `count`,CASE `status` WHEN '1' THEN ' Waiting For Approval' WHEN '6' THEN ' Approved' WHEN '7' THEN '  Sample Collected' WHEN '8' THEN ' Processing' WHEN '2' THEN ' Completed'  ELSE NULL  END AS 'name'FROM  job_master  WHERE `status`!='0'";
        $end_date = date("Y-m-d");
        $start_date = date('Y-m-d', strtotime(date('Y-m-d') . ' -30 day'));
        //$start_date = date('Y-m-d', strtotime($date . ' day', strtotime(date("Y-m-d")))) . " 00:00:00";
        $end_date = date("Y-m-d") . " 23:59:59";
        $qry .= " AND DATE>='" . $start_date . " 00:00:00' AND date<='" . $end_date . " 23:59:59'";
        if (!empty($center)) {
            $qry .= " AND branch_fk in (" . implode(",", $center) . ")";
        }
        $qry .= " GROUP BY `status` order by `status` asc";
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }

    function get_val($qry = null) {
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }

    public function master_num_rows($table, $condition) {
        $query1 = $this->db->get_where($table, $condition);
        return $query1->num_rows();
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

    public function master_fun_update($tablename, $cid, $data) {
        $this->db->where($cid);
        $this->db->update($tablename, $data);
        return 1;
    }

    function patient_data($from = null, $to = null, $type = null, $branch = null) {

        $sql = "SELECT 
  j.id,
  j.order_id,
  DATE_FORMAT((j.date), '%d %b %y %h:%i %p') AS DATE,
  GROUP_CONCAT(tm.`test_name`) test,
  j.`price`,
  j.`status`,c.`full_name`,c.`mobile`,c.`gender`,j.`doctor`
FROM
  job_master j 
  LEFT JOIN job_test_list_master t 
    ON j.id = t.`job_fk` 
  LEFT JOIN test_master tm 
    ON tm.id = t.`test_fk` 
   LEFT JOIN customer_master c ON c.id=j.`cust_fk`
WHERE j.`status` in (7,8) ";
        if ($from != null) {
            $sql = $sql . " AND DATE(j.`date`) BETWEEN '$to 00:00:00' AND '$from 23:59:59' ";
        }
        if (!empty($branch)) {
            $sql = $sql . " AND j.`branch_fk` in (" . implode(",", $branch) . ") ";
        }
        if ($type != null) {
            $sql = $sql . " AND j.`status`= $type ";
        }
        $sql = $sql . " 
GROUP BY j.id 
ORDER BY j.id DESC ";
        //echo $sql; die();
        $query = $this->db->query($sql);
        $query1 = $query->result_array();
        //	echo $this->db->last_query();;
        return $query1;
    }
    function completed_patient_data($from = null, $to = null, $type = null, $branch = null) {
        $sql = "SELECT 
  j.id,
  j.test_city
FROM
  job_master j 
WHERE j.`status` in (7,8,2) ";
        if ($from != null) {
            $sql = $sql . " AND DATE(j.`date`) BETWEEN '$to 00:00:00' AND '$from 23:59:59' ";
        }
        if (!empty($branch)) {
            $sql = $sql . " AND j.`branch_fk` in (" . implode(",", $branch) . ") ";
        }
        if ($type != null) {
            $sql = $sql . " AND j.`status`= $type ";
        }
        $sql = $sql . " 
GROUP BY j.id 
ORDER BY j.id DESC ";
        if($_GET["debug"]==1){
            echo $sql; die();
        }
        //echo $sql; die();
        $query = $this->db->query($sql);
        $query1 = $query->result_array();
        return $query1;
    }
    public function job_details($id) {
        $query = $this->db->query("SELECT dm.full_name as dname, j.status,j.branch_fk, tc.name as test_city_name, j.address as address1, j.invoice, j.portal, j.note, j.sample_collection, j.booking_info, j.payment_type, j.payable_amount, j.test_city, j.id, j.order_id, j.price, j.discount, GROUP_CONCAT(distinct t.test_name SEPARATOR '#') testname, GROUP_CONCAT(distinct p.title SEPARATOR '@') packagename, GROUP_CONCAT(distinct p.id SEPARATOR '%') packageid, GROUP_CONCAT(distinct t.id) testid, j.date, j.status, j.sample_collection, c.id custid, c.created_date regi_date, c.age,c.dob, c.full_name, c.mobile, c.gender, c.email, c.address, c.country, c.state, c.city, c.pic, c.type, c.password FROM job_master j LEFT JOIN job_test_list_master jtl ON jtl.job_fk = j.`id` LEFT JOIN customer_master c ON c.id = j.`cust_fk` LEFT JOIN test_master t ON t.id = jtl.test_fk LEFT JOIN book_package_master pb
                ON pb.job_fk = j.id
                LEFT JOIN package_master p
                ON p.id = pb.package_fk
                LEFT JOIN doctor_master dm ON dm.id = j.`doctor` inner join test_cities as tc on j.test_city = tc.id where j.id = '" . $id . "' GROUP BY j.id ORDER BY j.id DESC");
        $data['user'] = $query->result_array();
        return $data['user'];
    }

}

?>