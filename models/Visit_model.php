<?php

Class Visit_model extends CI_Model {

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

    public function master_fun_delete($tablename, $cid) {
        $this->db->where($cid[0], $cid[1]);
        $this->db->delete($tablename);
        return 1;
    }

    public function master_fun_update_multi($tablename, $condition, $data) {
        $this->db->where($condition);
        $this->db->update($tablename, $data);
        return 1;
    }

    public function master_num_rows($table, $condition) {
        $query1 = $this->db->get_where($table, $condition);
        return $query1->num_rows();
    }

    public function contact_master($table_name, $data) {

        $this->db->insert($table_name, $data);
        return $this->db->insert_id();
    }

    public function pending_job() {
        $query = $this->db->query("SELECT j.id,GROUP_CONCAT(t.test_name) testname,j.date,j.sample_collection,c.full_name,j.status,j.`payable_amount`,j.price FROM job_master j   LEFT JOIN job_test_list_master jtl ON jtl.job_fk=j.`id` LEFT JOIN customer_master c ON c.id=j.`cust_fk` LEFT JOIN test_master t ON t.id=jtl.test_fk WHERE j.`status` IN (1,2,3) GROUP BY j.id ORDER BY j.id DESC");
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    public function pending_job_search($user = null, $date = null, $city = null, $status = null, $mobile = null, $t_id = null, $p_id = null, $p_amount = null) {

        $query = "SELECT j.id,GROUP_CONCAT(distinct t.test_name) testname,GROUP_CONCAT(distinct p.title) packagename,j.date,j.views,j.`payment_type`,j.sample_collection,c.full_name,c.mobile,j.`payable_amount`,j.status,j.price,c.id as cid FROM job_master j   LEFT JOIN job_test_list_master jtl ON jtl.job_fk=j.`id` LEFT JOIN customer_master c ON c.id=j.`cust_fk` LEFT JOIN test_master t ON t.id=jtl.test_fk LEFT JOIN  book_package_master pb 
    ON pb.job_fk = j.id 
  LEFT JOIN package_master p 
    ON p.id = pb.package_fk where j.id!=' ' ";

        if ($user != "") {

            $query .= " AND j.cust_fk='$user'";
        }
        if ($date != "") {

            $query .= " AND DATE_FORMAT(j.date, '%d/%m/%Y') ='$date'";
        }
        if ($city != "") {

            $query .= " AND c.city='$city'";
        }
        if ($status != "") {

            $query .= " AND j.status='$status'";
        }
        if ($mobile != "") {

            $query .= " AND c.mobile='$mobile'";
        }
        if ($t_id != "") {

            $query .= " AND t.id='$t_id'";
        }
        if ($p_id != "") {

            $query .= " AND p.id='$p_id'";
        }
        if ($p_amount != "") {

            $query .= " AND j.payable_amount='$p_amount'";
        }

        $query .= " GROUP BY j.id ORDER BY j.id DESC";
        $query = $this->db->query($query);
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    public function num_row_srch_job_list($user = null, $date = null, $city = null, $status = null, $mobile = null, $t_id = null, $p_id = null, $p_amount = null) {

        $query = "SELECT j.id,j.order_id,GROUP_CONCAT(distinct t.test_name) testname,GROUP_CONCAT(distinct p.title) packagename,j.date,j.views,j.test_city,j.discount,j.`payment_type`,j.sample_collection,c.full_name,c.mobile,j.`payable_amount`,j.status,j.price,c.id as cid FROM job_master j   LEFT JOIN job_test_list_master jtl ON jtl.job_fk=j.`id` INNER JOIN customer_master c ON c.id=j.`cust_fk` LEFT JOIN test_master t ON t.id=jtl.test_fk LEFT JOIN  book_package_master pb 
    ON pb.job_fk = j.id 
  LEFT JOIN package_master p 
    ON p.id = pb.package_fk where j.id!=' ' and j.status != '0' ";

        if ($user != "") {

            $query .= " AND j.cust_fk='$user'";
        }
        if ($date != "") {

            $query .= " AND DATE_FORMAT(j.date, '%d/%m/%Y') ='$date'";
        }
        if ($city != "") {

            $query .= " AND c.city='$city'";
        }
        if ($status != "") {

            $query .= " AND j.status='$status'";
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

        $query .= " GROUP BY j.id ORDER BY j.id DESC";

        $result = $this->db->query($query);
        return $result->num_rows();
    }

    public function row_srch_job_list($user = null, $date = null, $city = null, $status = null, $mobile = null, $t_id = null, $p_id = null, $p_amount = null, $limit, $start) {

        $query = "SELECT j.id,j.order_id,GROUP_CONCAT(distinct t.test_name) testname,GROUP_CONCAT(distinct p.title) packagename,j.date,j.booking_info,j.views,j.discount,j.`payment_type`,j.sample_collection,c.full_name,c.mobile,j.`payable_amount`,j.status,j.price,c.id as cid FROM job_master j LEFT JOIN job_test_list_master jtl ON jtl.job_fk=j.`id` INNER JOIN customer_master c ON c.id=j.`cust_fk` LEFT JOIN test_master t ON t.id=jtl.test_fk LEFT JOIN  book_package_master pb 
    ON pb.job_fk = j.id 
  LEFT JOIN package_master p 
    ON p.id = pb.package_fk where j.id!=' ' and  j.status != '0' ";

        if ($user != "") {

            $query .= " AND j.cust_fk='$user'";
        }
        if ($date != "") {

            $query .= " AND DATE_FORMAT(j.date, '%d/%m/%Y') ='$date'";
        }
        if ($city != "") {

            $query .= " AND c.city='$city'";
        }
        if ($status != "") {

            $query .= " AND j.status='$status'";
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

        $query .= " GROUP BY j.id ORDER BY j.id DESC LIMIT $start , $limit";

        $result = $this->db->query($query);
        return $result->result_array();
    }

    public function srch_job_list($limit, $start) {

        $query = "SELECT j.id,j.order_id,GROUP_CONCAT(distinct t.test_name) testname,GROUP_CONCAT(distinct p.title) packagename,j.date,j.booking_info,j.discount,j.test_city,j.views,j.`payment_type`,j.sample_collection,c.full_name,c.mobile,j.`payable_amount`,j.status,j.price,c.id as cid FROM job_master j LEFT JOIN job_test_list_master jtl ON jtl.job_fk=j.`id` INNER JOIN customer_master c ON c.id=j.`cust_fk` LEFT JOIN test_master t ON t.id=jtl.test_fk LEFT JOIN  book_package_master pb 
    ON pb.job_fk = j.id 
  LEFT JOIN package_master p 
    ON p.id = pb.package_fk where j.id!=' ' and j.status != '0' GROUP BY j.id ORDER BY j.id DESC LIMIT $start , $limit ";
        $result = $this->db->query($query);
        return $result->result_array();
    }

    public function num_srch_job_list() {

        $query = "SELECT j.id,j.order_id,GROUP_CONCAT(distinct t.test_name) testname,GROUP_CONCAT(distinct p.title) packagename,j.date,j.views,j.`payment_type`,j.sample_collection,c.full_name,c.mobile,j.`payable_amount`,j.status,j.price,c.id as cid FROM job_master j LEFT JOIN job_test_list_master jtl ON jtl.job_fk=j.`id` INNER JOIN customer_master c ON c.id=j.`cust_fk` LEFT JOIN test_master t ON t.id=jtl.test_fk LEFT JOIN  book_package_master pb 
    ON pb.job_fk = j.id 
  LEFT JOIN package_master p 
    ON p.id = pb.package_fk where j.id !=' ' and j.status != '0' GROUP BY j.id ORDER BY j.id DESC";
        $result = $this->db->query($query);
        return $result->num_rows();
    }

    public function csv_job_list() {
        $query = "SELECT j.id,GROUP_CONCAT(distinct t.test_name) testname,GROUP_CONCAT(distinct p.title) packagename,j.date,j.order_id,j.test_city,j.address,j.portal,j.views,j.`payment_type`,j.sample_collection,c.full_name,c.mobile,j.`payable_amount`,j.status,j.price,c.id as cid FROM job_master j LEFT JOIN job_test_list_master jtl ON jtl.job_fk=j.`id` INNER JOIN customer_master c ON c.id=j.`cust_fk` LEFT JOIN test_master t ON t.id=jtl.test_fk LEFT JOIN  book_package_master pb 
    ON pb.job_fk = j.id 
  LEFT JOIN package_master p 
    ON p.id = pb.package_fk where j.id!=' ' and j.status != '0' GROUP BY j.id ORDER BY j.id DESC";
        $result = $this->db->query($query);
        return $result->result_array();
    }

    public function pending_job_search_telecaller($user = null, $date = null, $city = null, $status = null, $mobile = null, $t_id = null, $p_id = null, $p_amount = null) {

        $query = "SELECT j.id,GROUP_CONCAT(distinct t.test_name) testname,GROUP_CONCAT(distinct p.title) packagename,j.date,j.views,j.`payment_type`,j.sample_collection,c.full_name,c.mobile AS mobile1,j.`payable_amount`,j.status,j.price,c.id as cid FROM job_master j   LEFT JOIN job_test_list_master jtl ON jtl.job_fk=j.`id` INNER JOIN customer_master c ON c.id=j.`cust_fk` LEFT JOIN test_master t ON t.id=jtl.test_fk LEFT JOIN  book_package_master pb 
    ON pb.job_fk = j.id 
  LEFT JOIN package_master p 
    ON p.id = pb.package_fk where j.id!=' ' ";
        if ($user != "") {
            $query .= " AND j.cust_fk='$user'";
        }
        if ($date != "") {
            $query .= " AND DATE_FORMAT(j.date, '%d/%m/%Y') ='$date'";
        }
        if ($city != "") {
            $query .= " AND c.city='$city'";
        }
        if ($status != "") {
            $query .= " AND j.status='$status'";
        }
        if ($mobile != "") {
            $query .= " AND c.mobile='$mobile'";
        }
        if ($t_id != "") {
            $query .= " AND t.id='$t_id'";
        }
        if ($p_id != "") {
            $query .= " AND p.id='$p_id'";
        }
        if ($p_amount != "") {
            $query .= " AND j.payable_amount='$p_amount'";
        }
        $query .= " And (j.status = '1') OR (j.views = '0') AND j.status!='0' GROUP BY j.id ORDER BY j.sample_collection ASC , j.id DESC";
        //echo $query; die();

        $query = $this->db->query($query);
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    public function completed_job_search($user = null, $date = null, $city = null) {

        $query = "SELECT j.id,GROUP_CONCAT(t.test_name) testname,GROUP_CONCAT(p.title) packagename,j.date,c.full_name FROM job_master j   LEFT JOIN job_test_list_master jtl ON jtl.job_fk=j.`id` LEFT JOIN customer_master c ON c.id=j.`cust_fk` LEFT JOIN test_master t ON t.id=jtl.test_fk LEFT JOIN job_package jp ON jp.job_fk=j.id LEFT JOIN package_master p ON p.id=jp.package_fk WHERE j.`status`=2";

        if ($user != "") {

            $query .= " AND j.cust_fk='$user'";
        }
        if ($date != "") {

            $query .= " AND DATE_FORMAT(j.date, '%d/%m/%Y')='$date'";
        }
        if ($city != "") {

            $query .= " AND c.city='$city'";
        }

        $query .= " GROUP BY j.id ORDER BY j.id DESC";


        $query = $this->db->query($query);
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    public function spam_job_search($user = null, $date = null, $city = null) {

        $query = "SELECT j.id,GROUP_CONCAT(t.test_name) testname,j.date,c.full_name FROM job_master j   LEFT JOIN job_test_list_master jtl ON jtl.job_fk=j.`id` LEFT JOIN customer_master c ON c.id=j.`cust_fk` LEFT JOIN test_master t ON t.id=jtl.test_fk WHERE j.`status`=3";

        if ($user != "") {

            $query .= " AND j.cust_fk='$user'";
        }
        if ($date != "") {

            $query .= " AND DATE_FORMAT(j.date, '%d/%m/%Y')='$date'";
        }
        if ($city != "") {

            $query .= " AND c.city='$city'";
        }

        $query .= " GROUP BY j.id ORDER BY j.id DESC";


        $query = $this->db->query($query);
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    public function spam_job() {
        $query = $this->db->query("SELECT j.id,GROUP_CONCAT(t.test_name) testname,j.date,c.full_name  FROM job_master j   LEFT JOIN job_test_list_master jtl ON jtl.job_fk=j.`id` LEFT JOIN customer_master c ON c.id=j.`cust_fk` LEFT JOIN test_master t ON t.id=jtl.test_fk WHERE j.`status`=3 GROUP BY j.id ORDER BY j.id DESC");
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    public function completed_job() {
        $query = $this->db->query("SELECT j.id,GROUP_CONCAT(t.test_name) testname,j.date,c.full_name  FROM job_master j   LEFT JOIN job_test_list_master jtl ON jtl.job_fk=j.`id` LEFT JOIN customer_master c ON c.id=j.`cust_fk` LEFT JOIN test_master t ON t.id=jtl.test_fk WHERE j.`status`=2 GROUP BY j.id ORDER BY j.id DESC");
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    public function job_details($id) {
        $query = $this->db->query("SELECT dm.full_name as dname,j.status,j.portal,j.report_status,j.invoice,j.note,j.sample_collection,j.booking_info,j.payment_type,j.payable_amount,j.test_city,j.id,j.order_id,j.price,j.discount,GROUP_CONCAT(distinct t.test_name SEPARATOR  '#') testname,GROUP_CONCAT(distinct p.title SEPARATOR  '@') packagename,GROUP_CONCAT(distinct p.id SEPARATOR '%') packageid,GROUP_CONCAT(t.id) testid,j.date,j.status,j.sample_collection,c.id custid ,c.full_name ,c.mobile,c.gender,c.email,c.address,c.country,c.state,c.city,c.pic,c.type,c.password FROM job_master j   LEFT JOIN job_test_list_master jtl ON jtl.job_fk=j.`id` LEFT JOIN customer_master c ON c.id=j.`cust_fk` LEFT JOIN test_master t ON t.id=jtl.test_fk LEFT JOIN  book_package_master pb 
    ON pb.job_fk = j.id 
  LEFT JOIN package_master p 
    ON p.id = pb.package_fk
LEFT JOIN doctor_master dm ON dm.id=j.`doctor`	where j.id=$id GROUP BY j.id ORDER BY j.id DESC");
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    public function statelist() {
        $query = $this->db->query("SELECT s.*,c.country_name FROM state s LEFT JOIN country c ON c.id=s.`country_fk` WHERE s.status=1 AND c.status=1");
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    public function prescription_report_search($user = null, $date = null, $mobile = null, $status = null) {

        $query = "SELECT c.`full_name`,c.id cid,DATE_FORMAT(DATE(p.created_date) ,'%d %b %y') AS date ,p.*  FROM `prescription_upload` p LEFT JOIN customer_master c ON c.id=p.`cust_fk` WHERE p.`status` IN (1,2) ";

        if ($user != "") {

            $query .= " AND p.cust_fk='$user'";
        }
        if ($date != "") {

            $query .= " AND DATE_FORMAT(p.created_date, '%d/%m/%Y')='$date'";
        }
        if ($mobile != "") {

            $query .= " AND p.mobile='$mobile'";
        }
        if ($status != "") {

            $query .= " AND p.status='$status'";
        }

        $query .= " ORDER BY p.id DESC";


        $query = $this->db->query($query);
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    public function num_row_srch_prescription($user = null, $date = null, $mobile = null, $status = null) {

        $query = "SELECT c.`full_name`,c.id cid,DATE_FORMAT(DATE(p.created_date) ,'%d %b %y') AS date ,p.*  FROM `prescription_upload` p LEFT JOIN customer_master c ON c.id=p.`cust_fk` WHERE p.`status` IN (1,2) ";

        if ($user != "") {

            $query .= " AND p.cust_fk='$user'";
        }
        if ($date != "") {

            $query .= " AND DATE_FORMAT(p.created_date, '%d/%m/%Y')='$date'";
        }
        if ($mobile != "") {

            $query .= " AND p.mobile='$mobile'";
        }
        if ($status != "") {

            $query .= " AND p.status='$status'";
        }

        $query .= " ORDER BY p.id DESC";


        $result = $this->db->query($query)->num_rows();
        return $result;
    }

    public function row_srch_prescription($user = null, $date = null, $mobile = null, $status = null, $limit, $start) {

        $query = "SELECT c.`full_name`,c.id cid,DATE_FORMAT(DATE(p.created_date) ,'%d %b %y') AS date ,p.*  FROM `prescription_upload` p LEFT JOIN customer_master c ON c.id=p.`cust_fk` WHERE p.`status` IN (1,2) ";

        if ($user != "") {

            $query .= " AND p.cust_fk='$user'";
        }
        if ($date != "") {

            $query .= " AND DATE_FORMAT(p.created_date, '%d/%m/%Y')='$date'";
        }
        if ($mobile != "") {

            $query .= " AND p.mobile='$mobile'";
        }
        if ($status != "") {

            $query .= " AND p.status='$status'";
        }

        $query .= " ORDER BY p.id DESC LIMIT $start , $limit";


        $result = $this->db->query($query)->result_array();
        return $result;
    }

    public function srch_prescription($limit, $start) {

        $query = "SELECT c.`full_name`,c.id cid,DATE_FORMAT(DATE(p.created_date) ,'%d %b %y') AS date ,p.*  FROM `prescription_upload` p LEFT JOIN customer_master c ON c.id=p.`cust_fk` WHERE p.`status` IN (1,2) ";
        $query .= " ORDER BY p.id DESC LIMIT $start , $limit";
        $result = $this->db->query($query)->result_array();
        return $result;
    }

    public function prescription_report() {

        $query = "SELECT c.`full_name`,c.id cid,p.created_date AS date ,p.*  FROM `prescription_upload` p LEFT JOIN customer_master c ON c.id=p.`cust_fk` WHERE p.`status` IN (1,2) ";
        $query .= " ORDER BY p.id DESC ";
        $result = $this->db->query($query)->result_array();
        return $result;
    }

    public function prescription_report_search_talycaller($user = null, $date = null, $mobile = null, $status = null) {

        $query = "SELECT c.`full_name`,c.id cid,DATE_FORMAT(DATE(p.created_date) ,'%d %b %y') AS date ,p.*  FROM `prescription_upload` p LEFT JOIN customer_master c ON c.id=p.`cust_fk` WHERE p.`status` IN (1,2) ";

        if ($user != "") {

            $query .= " AND p.cust_fk='$user'";
        }
        if ($date != "") {

            $query .= " AND DATE_FORMAT(p.created_date, '%d/%m/%Y')='$date'";
        }
        if ($mobile != "") {

            $query .= " AND p.mobile='$mobile'";
        }
        if ($status != "") {

            $query .= " AND p.status='1'";
        }
        $query .= " AND p.status='1'";
        $query .= " ORDER BY p.status ASC,p.id DESC";
        $query = $this->db->query($query);
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    public function prescription_details($id) {

        $query = "SELECT c.`full_name`,c.id cid,c.state,c.city as ucity,c.pic,c.address,c.password,p.job_fk,DATE_FORMAT(DATE(p.created_date) ,'%d %b %y') AS date ,p.*  FROM `prescription_upload` p LEFT JOIN customer_master c ON c.id=p.`cust_fk` WHERE p.`status` IN (1,2) and p.id='$id'";


        $query = $this->db->query($query);
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    public function package_test_inquiry() {
        $query = "SELECT  a.* , a.`test_fk`,a.`package_fk`,
        GROUP_CONCAT(DISTINCT b.test_name) testname, GROUP_CONCAT( DISTINCT p.`title`) packagename,c.`full_name`,c.id uid
FROM    book_without_login a
        LEFT JOIN test_master b
            ON FIND_IN_SET(b.id, a.test_fk) 
            LEFT JOIN package_master p 
            ON FIND_IN_SET(p.id, a.`package_fk`) 
            LEFT JOIN customer_master c ON c.`mobile`=a.`mobile`
			where a.status = '1'
GROUP   BY a.id ORDER BY a.id DESC";
        $query = $this->db->query($query);
        return $query->result_array();
    }

    public function num_row_srch_pti_list($mobile = null, $package = null, $test = null, $user = null, $status = null, $date = null) {

        $query = "SELECT  a.* , a.`test_fk`,a.`package_fk`,
        GROUP_CONCAT(DISTINCT b.test_name) testname, GROUP_CONCAT( DISTINCT p.`title`) packagename,c.`full_name`,c.id uid
FROM    book_without_login a
        LEFT JOIN test_master b
            ON FIND_IN_SET(b.id, a.test_fk) 
            LEFT JOIN package_master p 
            ON FIND_IN_SET(p.id, a.`package_fk`) 
            LEFT JOIN customer_master c ON c.`mobile`=a.`mobile` where a.status != '0' and c.status = '1'  ";

        if ($mobile != "") {

            $query .= " AND a.mobile like '%$mobile%'";
        }
        if ($package != "") {

            $query .= " AND FIND_IN_SET($package, a.package_fk)";
        }
        if ($test != "") {

            $query .= " AND FIND_IN_SET($test, a.test_fk)";
        }
        if ($status != "") {

            $query .= " AND a.status='$status'";
        }
        if ($user != "") {

            $query .= " AND c.id='$user'";
        }
        if ($date != "") {

            $query .= " AND DATE_FORMAT(a.date, '%d/%m/%Y')='$date'";
        }

        $query .= " GROUP BY a.id ORDER BY a.id DESC";

        $result = $this->db->query($query);
        return $result->num_rows();
    }

    public function row_srch_pti_list($mobile = null, $package = null, $test = null, $user = null, $status = null, $date = null, $limit, $start) {

        $query = "SELECT  a.* , a.`test_fk`,a.`package_fk`,
        GROUP_CONCAT(DISTINCT b.test_name) testname, GROUP_CONCAT( DISTINCT p.`title`) packagename,c.`full_name`,c.id uid
FROM    book_without_login a
        LEFT JOIN test_master b
            ON FIND_IN_SET(b.id, a.test_fk) 
            LEFT JOIN package_master p 
            ON FIND_IN_SET(p.id, a.`package_fk`) 
            LEFT JOIN customer_master c ON c.`mobile`=a.`mobile` where a.status != '0'  ";

        if ($mobile != "") {

            $query .= " AND a.mobile like '%$mobile%'";
        }
        if ($package != "") {

            $query .= " AND FIND_IN_SET($package, a.`package_fk`)";
        }
        if ($test != "") {

            $query .= " AND FIND_IN_SET($test, a.test_fk)";
        }
        if ($status != "") {

            $query .= " AND a.status='$status'";
        }
        if ($user != "") {

            $query .= " AND c.id='$user'";
        }
        if ($date != "") {

            $query .= " AND DATE_FORMAT(a.date, '%d/%m/%Y')='$date'";
        }

        $query .= " GROUP BY a.id ORDER BY a.id DESC LIMIT $start , $limit";

        $result = $this->db->query($query);
        return $result->result_array();
    }

    public function srch_pti_list($limit, $start) {

        /* $query = "SELECT  a.* , a.`test_fk`,a.`package_fk`,
          GROUP_CONCAT(DISTINCT b.test_name) testname, GROUP_CONCAT( DISTINCT p.`title`) packagename,c.`full_name`,c.id uid
          FROM    book_without_login a
          LEFT JOIN test_master b
          ON FIND_IN_SET(b.id, a.test_fk)
          LEFT JOIN package_master p
          ON FIND_IN_SET(p.id, a.`package_fk`)
          LEFT JOIN customer_master c ON c.`mobile`=a.`mobile` where c.status='1' AND a.status != '0' GROUP BY a.id ORDER BY a.id DESC LIMIT $start , $limit ";
         */
        $query = "SELECT * from book_without_login where status in (1,2) ORDER BY id DESC LIMIT $start , $limit ";
        $result = $this->db->query($query);
        $inquiry_data = $result->result_array();
        $new_data = array();
        foreach ($inquiry_data as $key) {
            if ($key["test_fk"]) {
                $tst_id = explode(",", $key["test_fk"]);
                $b_tst_name = array();
                foreach ($tst_id as $t_key) {
                    $price1 = $this->db->query("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master`.`id`='" . $t_key . "'");
                    $tst_name = $price1->result_array();
                    $b_tst_name[] = $tst_name[0]["test_name"];
                }
                if (!empty($b_tst_name)) {
                    $key["testname"] = implode("<br>", $b_tst_name);
                } else {
                    $key["testname"] = "";
                }
            } else {
                $key["testname"] = "";
            }
            if ($key["package_fk"]) {
                $b_tst_name = array();
                $tst_id = explode(",", $key["package_fk"]);
                foreach ($tst_id as $t_key) {
                    $price1 = $this->db->query("SELECT title FROM package_master where id='" . $t_key . "'");
                    $tst_name = $price1->result_array();
                    $b_tst_name[] = $tst_name[0]["title"];
                }
                if (!empty($b_tst_name)) {
                    $key["packagename"] = implode("<br>", $b_tst_name);
                } else {
                    $key["packagename"] = "";
                }
            } else {
                $key["packagename"] = "";
            }
            $price1 = $this->db->query("SELECT id,full_name FROM customer_master where mobile='" . $key["mobile"] . "' AND status='1'");
            $tst_name = $price1->result_array();
            $key["uid"] = $tst_name[0]["id"];
            $key["full_name"] = $tst_name[0]["full_name"];
            $new_data[] = $key;
        }
        return $new_data;
    }

    public function inquiry_csv_report() {

        $query = "SELECT * from book_without_login where status in (1,2) ORDER BY id DESC";
        $result = $this->db->query($query);
        return $result->result_array();
    }

    public function num_srch_pti_list() {

        $query = "SELECT  a.* , a.`test_fk`,a.`package_fk`,
        GROUP_CONCAT(DISTINCT b.test_name) testname, GROUP_CONCAT( DISTINCT p.`title`) packagename,c.`full_name`,c.id uid
FROM    book_without_login a
        LEFT JOIN test_master b
            ON FIND_IN_SET(b.id, a.test_fk) 
            LEFT JOIN package_master p 
            ON FIND_IN_SET(p.id, a.`package_fk`) 
            LEFT JOIN customer_master c ON c.`mobile`=a.`mobile` where a.status != '0' GROUP BY a.id ORDER BY a.id DESC";

        $result = $this->db->query($query);
        return $result->num_rows();
    }

    public function pending_job_count() {

        $query = "select count(*) total from job_master where status='1'";
        //die();

        $query = $this->db->query($query);
        $data['user'] = $query->row();
        return $data['user'];
    }

    public function instant_contact_count() {

        $query = "select count(*) total from instant_contact where status='1'";
        //die();

        $query = $this->db->query($query);
        $data['user'] = $query->row();
        return $data['user'];
    }

    public function all_inquiry_count() {
        $query = "select count(*) total from book_without_login where status='1'";
        $query = $this->db->query($query);
        $data['user'] = $query->row();
        return $data['user'];
    }

    function get_suggested_test($pid) {
        $qry = "SELECT `suggested_test`.*,`test_master`.`test_name`,`test_master`.`price` FROM `suggested_test` 
          INNER JOIN `test_master` ON `test_master`.`id`=`suggested_test`.`test_id`
          WHERE `suggested_test`.`status`='1' AND `test_master`.`status`='1' AND `suggested_test`.`p_id`='$pid'";
        $query = $this->db->query($qry);
        $data['user'] = $query->result_array();
        $cnt = 0;
        foreach ($data['user'] as $key) {
            $data['test'] = $this->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`test_fk`='" . $key["test_id"] . "'");
            //$key["price"]=$data['test'][0]["price"];
            $data['user'][$cnt]["price"] = $data['test'][0]["price"];
            $cnt++;
        }
        return $data['user'];
    }

    public function get_testval($cityfk) {

        $query = $this->db->query("SELECT  tp.`test_fk`,tp.`price`,t.`test_name`,c.`name` AS cityname FROM  test_master_city_price tp LEFT JOIN test_master t ON t.id=tp.`test_fk` LEFT JOIN `test_cities` c ON c.`id`=tp.`city_fk` WHERE tp.`city_fk` ='$cityfk' AND tp.`status` ='1' AND t.`status`='1'");
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    function get_val($query1 = null) {
        $query = $this->db->query($query1);
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    public function test_with_city() {

        $query = $this->db->query("SELECT test_master.*,test_cities.name from test_master left join test_master_city_price on test_master.id=test_master_city_price.test_fk left join test_cities on test_cities.id=test_master_city_price.city_fk where test_cities.status='1' and test_master_city_price.status='1' and test_master.status='1' order by test_master.test_name ASC");
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    function get_family_member_name($jid) {
        $query = "SELECT `job_master`.`id`,`job_master`.`booking_info`,`booking_info`.`type`,`booking_info`.`family_member_fk` FROM `job_master` INNER JOIN `booking_info` ON `booking_info`.`id`=`job_master`.`booking_info` WHERE `job_master`.`status`!='0' AND `job_master`.`id`='" . $jid . "'";
        $query = $this->db->query($query);
        $query = $query->result_array();
        if ($query[0]["type"] == "family") {
            $fquery = "SELECT name,phone,email from customer_family_master where id='" . $query[0]["family_member_fk"] . "'";
            $fquery = $this->db->query($fquery);
            return $query = $fquery->result_array();
        } else {
            return 0;
        }
    }

}

?>
