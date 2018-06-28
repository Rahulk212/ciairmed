<?php

Class Customer_model extends CI_Model {

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

    public function citylist() {
        $query = $this->db->query("SELECT c.*,s.state_name,co.country_name FROM city c LEFT JOIN state s ON c.state_fk=s.id LEFT JOIN country co  ON c.`country_fk`=co.id  WHERE s.status=1 AND c.status=1");
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    public function statelist() {
        $query = $this->db->query("SELECT s.*,c.country_name FROM state s LEFT JOIN country c ON c.id=s.`country_fk` WHERE s.status=1 AND c.status=1");
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    public function contactlist() {
        $query = $this->db->query("SELECT c.*,p.`title`,DATE_FORMAT(c.created_date, '%d %b %y %h:%i %p') AS date FROM instant_contact c LEFT JOIN package_master p ON p.id=c.`package` WHERE c.`status` IN (1,2) order by c.id desc");
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    public function num_row_srch_contact_list($mobile = null, $package = null, $date = null, $status = null) {

        $query = "SELECT c.*,p.`title`,DATE_FORMAT(c.created_date, '%d %b %y %h:%i %p') AS date FROM instant_contact c LEFT JOIN package_master p ON p.id=c.`package` WHERE c.`status` IN (1,2) ";

        if ($mobile != "") {

            $query .= " AND c.mobile like '%$mobile%'";
        }
        if ($package != "") {

            $query .= " AND c.package='$package'";
        }
        if ($date != "") {

            $query .= " AND DATE_FORMAT(c.created_date, '%d/%m/%Y')='$date'";
        }
        if ($status != "") {

            $query .= " AND c.status='$status'";
        }

        $query .= " order by c.id desc";

        $result = $this->db->query($query);
        return $result->num_rows();
    }

    public function row_srch_contact_list($mobile = null, $package = null, $date = null, $status = null, $limit, $start) {

        $query = "SELECT c.*,p.`title`,DATE_FORMAT(c.created_date, '%d %b %y %h:%i %p') AS date FROM instant_contact c LEFT JOIN package_master p ON p.id=c.`package` WHERE c.`status` IN (1,2) ";

        if ($mobile != "") {

            $query .= " AND c.mobile like '%$mobile%'";
        }
        if ($package != "") {

            $query .= " AND c.package='$package'";
        }
        if ($date != "") {

            $query .= " AND DATE_FORMAT(c.created_date, '%d/%m/%Y')='$date'";
        }
        if ($status != "") {

            $query .= " AND c.status='$status'";
        }

        $query .= " order by c.id desc LIMIT $start , $limit";

        $result = $this->db->query($query);
        return $result->result_array();
    }

    public function srch_contact_list($limit, $start) {

        $query = "SELECT c.*,p.`title`,DATE_FORMAT(c.created_date, '%d %b %y %h:%i %p') AS date FROM instant_contact c LEFT JOIN package_master p ON p.id=c.`package` WHERE c.`status` IN (1,2) order by c.id desc LIMIT $start , $limit ";

        $result = $this->db->query($query);
        return $result->result_array();
    }

    public function num_srch_contact_list() {

        $query = "SELECT c.*,p.`title`,DATE_FORMAT(c.created_date, '%d %b %y %h:%i %p') AS date FROM instant_contact c LEFT JOIN package_master p ON p.id=c.`package` WHERE c.`status` IN (1,2) order by c.id desc";

        $result = $this->db->query($query);
        return $result->num_rows();
    }

    function get_all_job($user_fk) {

        $query = $this->db->query("SELECT j.id,j.order_id,DATE_FORMAT(j.date, '%d %b %y %h:%i %p') AS date,GROUP_CONCAT(tm.`test_name`) test ,(SELECT 
    GROUP_CONCAT(p.title) 
  FROM
    package_master p 
    INNER JOIN book_package_master pm 
      ON pm.package_fk = p.id 
  WHERE pm.job_fk = j.`id` 
  GROUP BY pm.`job_fk`) AS packge_name,j.`price`,j.`status` FROM job_master j LEFT JOIN job_test_list_master t ON j.id=t.`job_fk` LEFT JOIN test_master tm ON tm.id=t.`test_fk`  WHERE j.`status`!='0' AND j.`cust_fk`='$user_fk' GROUP BY j.`id` ORDER BY j.id desc");

        return $query->result_array();
    }

    public function num_row_srch($data) {

        $query = "SELECT *  FROM `customer_master` where `status` = '1'";

        if ($data['name'] != "") {
            $name = $data['name'];
            $query .= " AND lower(full_name) LIKE '%" .strtolower($name) . "%'";
        }
        if ($data['email'] != "") {
            $email = $data['email'];
            $query .= " AND email LIKE '%" . $email . "%'";
        }
        if ($data['mobile'] != "") {
            $mobile = $data['mobile'];
            $query .= " AND mobile LIKE '%" . $mobile . "%'";
        }
        if ($data['date'] != "") {
            $date = $data['date'];
            $query .= " AND DATE_FORMAT(created_date, '%d/%m/%Y')='$date'";
        }
        $query .= " ORDER BY id DESC";
        $result = $this->db->query($query)->num_rows();
        return $result;
    }

    public function row_srch($data, $limit, $start) {

        $query = "SELECT *  FROM `customer_master` where `status` = '1'";

        if ($data['name'] != "") {
            $name = $data['name'];
           // $query .= " AND full_name  LIKE '%" . $name . "%'";  
			   $query .= " AND lower(full_name) LIKE '%" .strtolower($name) . "%'";
        }
        if ($data['email'] != "") {
            $email = $data['email'];
            $query .= " AND email LIKE '%" . $email . "%'";
        }
        if ($data['mobile'] != "") {
            $mobile = $data['mobile'];
            $query .= " AND mobile LIKE '%" . $mobile . "%'";
        }
        if ($data['date'] != "") {
            $date = $data['date'];
            $query .= " AND DATE_FORMAT(created_date, '%d/%m/%Y')='$date'";
        }
        $query .= " ORDER BY id DESC LIMIT $start , $limit";
        $result = $this->db->query($query)->result_array();
        return $result;
    }

    public function num_row_srch_partener($data) {
        $query = "SELECT *  FROM `partner_with_us` where `status` = '1'";
        $result = $this->db->query($query)->num_rows();
        return $result;
    }

    public function row_srch_partener($limit, $start) {

        $query = "SELECT *  FROM `partner_with_us` where `status` = '1'  LIMIT $start , $limit";
        $result = $this->db->query($query)->result_array();
        return $result;
    }

    function get_user_family_member($uid) {
        $query = $this->db->query("SELECT 
  `customer_family_master`.*,
  `relation_master`.`name` AS relation_name 
FROM
  `customer_family_master` 
  INNER JOIN `relation_master` 
    ON `customer_family_master`.`relation_fk` = `relation_master`.`id` 
WHERE `customer_family_master`.`status` = '1' 
  AND `relation_master`.`status` = '1' 
  AND `customer_family_master`.`user_fk` = '" . $uid . "'");
        $query1 = $query->result_array();
        return $query1;
    }
    function get_user_family_member1($uid,$limit, $start) {
        $query = $this->db->query("SELECT 
  `customer_family_master`.*,
  `relation_master`.`name` AS relation_name 
FROM
  `customer_family_master` 
  INNER JOIN `relation_master` 
    ON `customer_family_master`.`relation_fk` = `relation_master`.`id` 
WHERE `customer_family_master`.`status` = '1' 
  AND `relation_master`.`status` = '1' 
  AND `customer_family_master`.`user_fk` = '" . $uid . "' ORDER BY `customer_family_master`.`id` DESC LIMIT $start , $limit");
        $query1 = $query->result_array();
        return $query1;
    }

}

?>
