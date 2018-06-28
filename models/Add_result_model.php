<?php

Class Add_result_model extends CI_Model {

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
    public function master_fun_update1($tablename, $cid, $data) {
        $this->db->where($cid);
        $this->db->update($tablename, $data);
        return 1;
    }
    public function master_fun_update12($tablename, $cid, $data) {
        $this->db->where($cid[0], $cid[1]);
        $this->db->where($cid[2], $cid[3]);
        $this->db->update($tablename, $data);
        return 1;
    }

    public function num_row_srch_job_list($user = null, $date = null, $city = null, $mobile = null, $t_id = null, $p_id = null, $p_amount = null, $branch = null) {

        $query = "SELECT j.id,GROUP_CONCAT(distinct t.test_name) testname,GROUP_CONCAT(distinct p.title) packagename,j.date,j.views,j.test_city,j.discount,j.`payment_type`,j.sample_collection,c.full_name,c.mobile,j.`payable_amount`,j.status,j.price,c.id as cid FROM job_master j   LEFT JOIN job_test_list_master jtl ON jtl.job_fk=j.`id` INNER JOIN customer_master c ON c.id=j.`cust_fk` LEFT JOIN test_master t ON t.id=jtl.test_fk LEFT JOIN  book_package_master pb 
    ON pb.job_fk = j.id 
  LEFT JOIN package_master p 
    ON p.id = pb.package_fk where j.id!='' and j.status in (7,8) ";

        if ($user != "") {

            $query .= " AND j.cust_fk='$user'";
        }
        if ($date != "") {

            $query .= " AND DATE_FORMAT(j.date, '%d/%m/%Y') ='$date'";
        }
        if ($city != "") {

            $query .= " AND c.city='$city'";
        }
        if ($mobile != "") {

            $query .= " AND c.mobile LIKE '%$mobile%'";
        }
        if ($t_id != "") {

            $query .= " AND t.id='$t_id'";
        }
        if ($p_id != "") {

            $query .= " AND p.id='$p_id'";
        }
        if ($p_amount != "") {

            $query .= " AND j.payable_amount >=$p_amount";
        }
        if (!empty($branch)) {

            $query .= " AND j.branch_fk  in (" . implode(",", $branch) . ")";
        }
        $query .= " GROUP BY j.id ORDER BY j.id DESC";

        $result = $this->db->query($query);
        return $result->num_rows();
    }

    public function row_srch_job_list($user = null, $date = null, $city = null, $status = null, $mobile = null, $t_id = null, $p_id = null, $p_amount = null, $limit = 0, $start = 0, $branch = null) {

        $query = "SELECT j.id,GROUP_CONCAT(distinct t.test_name) testname,GROUP_CONCAT(distinct p.title) packagename,j.date,j.booking_info,j.views,j.discount,j.`payment_type`,j.sample_collection,c.full_name,c.mobile,j.`payable_amount`,j.status,j.price,c.id as cid FROM job_master j LEFT JOIN job_test_list_master jtl ON jtl.job_fk=j.`id` INNER JOIN customer_master c ON c.id=j.`cust_fk` LEFT JOIN test_master t ON t.id=jtl.test_fk LEFT JOIN  book_package_master pb 
    ON pb.job_fk = j.id 
  LEFT JOIN package_master p 
    ON p.id = pb.package_fk where j.id!=' ' and  j.status in (7,8) ";

        if ($user != "") {

            $query .= " AND j.cust_fk='$user'";
        }
        if ($date != "") {

            $query .= " AND DATE_FORMAT(j.date, '%d/%m/%Y') ='$date'";
        }
        if ($city != "") {

            $query .= " AND c.city='$city'";
        }
        if ($mobile != "") {

            $query .= " AND c.mobile LIKE '%$mobile%'";
        }
        if ($t_id != "") {

            $query .= " AND t.id='$t_id'";
        }
        if ($p_id != "") {

            $query .= " AND p.id='$p_id'";
        }
        if ($p_amount != "") {

            $query .= " AND j.payable_amount>=$p_amount";
        }
        if (!empty($branch)) {

            $query .= " AND j.branch_fk  in (" . implode(",", $branch) . ")";
        }
        $query .= " GROUP BY j.id ORDER BY j.id DESC LIMIT $start , $limit";
        $result = $this->db->query($query);
        return $result->result_array();
    }

    public function srch_job_list($limit, $start, $branch = null) {

        $query = "SELECT j.id,GROUP_CONCAT(distinct t.test_name) testname,GROUP_CONCAT(distinct p.title) packagename,j.date,j.booking_info,j.discount,j.test_city,j.views,j.`payment_type`,j.sample_collection,c.full_name,c.mobile,j.`payable_amount`,j.status,j.price,c.id as cid FROM job_master j LEFT JOIN job_test_list_master jtl ON jtl.job_fk=j.`id` INNER JOIN customer_master c ON c.id=j.`cust_fk` LEFT JOIN test_master t ON t.id=jtl.test_fk LEFT JOIN  book_package_master pb 
    ON pb.job_fk = j.id 
  LEFT JOIN package_master p 
    ON p.id = pb.package_fk where";
        if (!empty($branch)) {

            $query .= " j.branch_fk  in (" . implode(",", $branch) . ") AND ";
        }
        $query .= " j.id != ' ' and j.status in (7, 8) GROUP BY j.id ORDER BY j.id DESC LIMIT $start, $limit ";
        $result = $this->db->query($query);
        return $result->result_array();
    } 

    public function num_srch_job_list($center = null) {

        $query = "SELECT j.id, GROUP_CONCAT(distinct t.test_name) testname, GROUP_CONCAT(distinct p.title) packagename, j.date, j.views, j.`payment_type`, j.sample_collection, c.full_name, c.mobile, j.`payable_amount`, j.status, j.price, c.id as cid FROM job_master j LEFT JOIN job_test_list_master jtl ON jtl.job_fk = j.`id` INNER JOIN customer_master c ON c.id = j.`cust_fk` LEFT JOIN test_master t ON t.id = jtl.test_fk LEFT JOIN book_package_master pb
                ON pb.job_fk = j.id
                LEFT JOIN package_master p
                ON p.id = pb.package_fk where ";
        if (!empty($center)) {
            $query .= " j.branch_fk in (" . implode(", ", $center) . ") AND";
        }
        $query .= " j.id != ' ' and j.status in (7, 8) GROUP BY j.id ORDER BY j.id DESC";
        $result = $this->db->query($query);
        return $result->num_rows();
    }

     public function job_details($id) {
        $query = $this->db->query("SELECT dm.full_name as dname, j.status,j.collection_charge,j.model_type,j.b2b_id,j.branch_fk, tc.name as test_city_name, j.address as address1, j.invoice, j.portal, j.note, j.sample_collection, j.booking_info, j.payment_type, j.payable_amount, j.test_city, j.id, j.order_id, j.price, j.discount, GROUP_CONCAT(distinct t.test_name SEPARATOR '#') testname, GROUP_CONCAT(distinct p.title SEPARATOR '@') packagename, GROUP_CONCAT(distinct p.id SEPARATOR '%') packageid, GROUP_CONCAT(distinct t.id) testid, j.date, j.status, j.sample_collection, c.id custid, c.created_date regi_date, c.age,c.dob, c.full_name, c.mobile, c.gender, c.email, c.address, c.country, c.state, c.city, c.pic, c.type, c.password FROM job_master j LEFT JOIN job_test_list_master jtl ON jtl.job_fk = j.`id` LEFT JOIN customer_master c ON c.id = j.`cust_fk` LEFT JOIN test_master t ON t.id = jtl.test_fk LEFT JOIN book_package_master pb
                ON pb.job_fk = j.id
                LEFT JOIN package_master p
                ON p.id = pb.package_fk
                LEFT JOIN doctor_master dm ON dm.id = j.`doctor` inner join test_cities as tc on j.test_city = tc.id where j.id = '" . $id . "' GROUP BY j.id ORDER BY j.id DESC");
        $data['user'] = $query->result_array();
        return $data['user'];
    } 
	public function job_details1($id) {
        $query = $this->db->query("SELECT dm.full_name as dname, j.status,j.collection_charge,j.model_type,j.b2b_id,j.branch_fk, tc.name as test_city_name, j.address as address1, j.invoice, j.portal, j.note, j.sample_collection, j.booking_info, j.payment_type, j.payable_amount, j.test_city, j.id, j.order_id, j.price, j.discount, GROUP_CONCAT(distinct t.test_name SEPARATOR '#') testname, GROUP_CONCAT(distinct p.title SEPARATOR '@') packagename, GROUP_CONCAT(distinct p.id SEPARATOR '%') packageid, GROUP_CONCAT(distinct t.id ORDER BY jtl.position ASC) testid, j.date, j.status, j.sample_collection, c.id custid, c.created_date regi_date, c.age,c.dob, c.full_name, c.mobile, c.gender, c.email, c.address, c.country, c.state, c.city, c.pic, c.type, c.password FROM job_master j LEFT JOIN approve_job_test jtl ON jtl.job_fk = j.`id` LEFT JOIN customer_master c ON c.id = j.`cust_fk` LEFT JOIN test_master t ON t.id = jtl.test_fk LEFT JOIN book_package_master pb
                ON pb.job_fk = j.id
                LEFT JOIN package_master p
                ON p.id = pb.package_fk
                LEFT JOIN doctor_master dm ON dm.id = j.`doctor` inner join test_cities as tc on j.test_city = tc.id where j.id = '" . $id . "' GROUP BY j.id ORDER BY j.id DESC");
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    function get_val($query1 = null) {
        $query = $this->db->query($query1);
        $data['user'] = $query->result_array();
        return $data[
                'user'];
    }

    public function unit_list() {

        $query = $this->db->query("SELECT PARAMETER_NAME from CTMS_PARAMETER_MST where status = '1' AND PARAMETER_CODE = 'UOM' order by PARAMETER_NAME ASC");
        $data['user'] = $query->result_array();
        return $data[
                'user'];
    }

    public function master_num_rows($table, $condition) {
        $query1 = $this->db->get_where($table, $condition);
        return $query1->num_rows();
    }
    
    public function job_details_outsource($id) {
        $query = $this->db->query("SELECT dm.full_name as dname, j.status,j.collection_charge,j.branch_fk, tc.name as test_city_name, j.address as address1, j.invoice, j.portal, j.note, j.sample_collection, j.booking_info, j.payment_type, j.payable_amount, j.test_city, j.id, j.order_id, j.price, j.discount, GROUP_CONCAT(distinct t.test_name SEPARATOR '#') testname, GROUP_CONCAT(distinct p.title SEPARATOR '@') packagename, GROUP_CONCAT(distinct p.id SEPARATOR '%') packageid, GROUP_CONCAT(distinct t.id) testid, j.date, j.status, j.sample_collection, c.id custid, c.created_date regi_date, c.age,c.dob, c.full_name, c.mobile, c.gender, c.email, c.address, c.country, c.state, c.city, c.pic, c.type, c.password FROM job_master j LEFT JOIN job_test_list_master jtl ON jtl.job_fk = j.`id` LEFT JOIN customer_master c ON c.id = j.`cust_fk` LEFT JOIN test_master t ON t.id = jtl.test_fk left join user_test_outsource os on os.test_fk = t.id LEFT JOIN book_package_master pb
                ON pb.job_fk = j.id
                LEFT JOIN package_master p
                ON p.id = pb.package_fk
                LEFT JOIN doctor_master dm ON dm.id = j.`doctor` inner join test_cities as tc on j.test_city = tc.id where j.id = '" . $id . "' and os.status='1' GROUP BY j.id ORDER BY j.id DESC");
        $data['user'] = $query->result_array();
        return $data['user'];
    }
	public function getjobstest($jobid,$outid=null){

		$query = $this->db->query("SELECT GROUP_CONCAT(test_fk) as testfk  FROM user_test_outsource WHERE job_fk='$jobid' AND status='1'");
        $data['user'] = $query->row();
        return $data['user'];
    }
	function updateRowWhere($table, $where, $data) {
		
    $this->db->where($where);
    $this->db->update($table, $data);
	
	return 1;
}

}

?>
