<?php

Class User_master_model extends CI_Model {

    function getmenu() {
        // $this->db->order_by("id", "desc");
        $query = $this->db->get_where("bmh_menu_master", array("status" => "1"));
        return $query->result_array();
    }

    function getUser($user_id) {
        $query = $this->db->get_where("customer_master", array("id" => $user_id, "status" => "1"));
        return $query->row();
    }

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

    function update_user1($data, $user_id) {
        $this->db->update("admin_master", $data, array("id" => $user_id));
        return 1;
    }

    function completed_job($user_fk) {
        $query = $this->db->query("SELECT j.id,j.order_id,DATE_FORMAT(j.date, '%d %b %y %h:%i %p') AS date,GROUP_CONCAT(tm.`test_name`) test ,j.`price`,j.`status` FROM job_master j LEFT JOIN job_test_list_master t ON j.id=t.`job_fk` LEFT JOIN test_master tm ON tm.id=t.`test_fk`  WHERE j.`status`=2 AND j.`cust_fk`='$user_fk' GROUP BY t.`job_fk` ORDER BY j.id desc ");
        $query1 = $query->result_array();
        return $query1;
    }

    function pending_job($user_fk) {
        $query = $this->db->query("SELECT j.id,j.order_id,DATE_FORMAT(j.date, '%d %b %y %h:%i %p') AS date,GROUP_CONCAT(tm.`test_name`) test ,j.`price`,j.`status` FROM job_master j LEFT JOIN job_test_list_master t ON j.id=t.`job_fk` LEFT JOIN test_master tm ON tm.id=t.`test_fk`  WHERE j.`status`=1 AND j.`cust_fk`='$user_fk' GROUP BY t.`job_fk` ORDER BY j.id desc");
        //	echo "SELECT j.id,j.order_id,DATE_FORMAT(NOW(), '%d %b %y %h:%i %p') AS date,GROUP_CONCAT(tm.`test_name`) test ,j.`price`,j.`status` FROM job_master j LEFT JOIN job_test_list_master t ON j.id=t.`job_fk` LEFT JOIN test_master tm ON tm.id=t.`test_fk`  WHERE j.`status`=1 AND j.`cust_fk`='$user_fk' GROUP BY t.`job_fk` ORDER BY j.id desc";
        //die();
        $query1 = $query->result_array();
        return $query1;
    }

    function get_pending_job1($user_fk, $one, $two) {

        $query = $this->db->query("SELECT j.id,j.order_id,j.payable_amount,j.added_by,DATE_FORMAT(j.date, '%d %b %y %h:%i %p') AS date,GROUP_CONCAT(tm.`test_name`) test ,(SELECT 
    GROUP_CONCAT(p.title) 
  FROM
    package_master p 
    INNER JOIN book_package_master pm 
      ON pm.package_fk = p.id 
  WHERE pm.job_fk = j.`id` 
  GROUP BY pm.`job_fk`) AS packge_name,j.`price`,j.`status` FROM job_master j LEFT JOIN job_test_list_master t ON j.id=t.`job_fk` LEFT JOIN test_master tm ON tm.id=t.`test_fk`  WHERE j.`status`!=2 AND j.`status`!=0 AND j.`cust_fk`='$user_fk' GROUP BY j.`id` ORDER BY j.id desc LIMIT $two,$one ");

        return $query->result_array();
    }

    function get_pending_job($user_fk) {
        $query = $this->db->query("SELECT j.id,j.order_id,DATE_FORMAT(j.date, '%d %b %y %h:%i %p') AS date,GROUP_CONCAT(tm.`test_name`) test ,j.`price`,j.`status` FROM job_master j LEFT JOIN job_test_list_master t ON j.id=t.`job_fk` LEFT JOIN test_master tm ON tm.id=t.`test_fk`  WHERE j.`status`=1 AND j.`cust_fk`='$user_fk' GROUP BY t.`job_fk` ORDER BY j.id desc");
        return $query->result_array();
    }

    function get_prescription($user_fk, $one, $two) {

        $query = $this->db->query("SELECT * FROM `prescription_upload` WHERE `status` != '0' AND `cust_fk`='$user_fk' ORDER BY id DESC LIMIT $two,$one ");

        return $query->result_array();
    }

    function get_support_system($user_fk, $one, $two) {

        $query = $this->db->query("SELECT * FROM `ticket_master` WHERE `status` = '1' AND `user_id`='$user_fk' ORDER BY id DESC LIMIT $two,$one ");

        return $query->result_array();
    }

    function get_payment_history1($user_fk, $one, $two) {

        $query = $this->db->query("SELECT 
  p.* ,DATE_FORMAT(DATE(p.paydate), '%d %b %y %h:%i %p') as date, GROUP_CONCAT(t.`test_name`) testname

FROM
  `payment` p
  LEFT JOIN job_master j 
  ON j.`id`=p.`job_fk`
 LEFT JOIN job_test_list_master jt
  ON j.`id`=jt.`job_fk` LEFT JOIN test_master t ON t.id=jt.`test_fk`
WHERE j.`status`!='0' AND p.uid = '$user_fk' 
   GROUP BY p.id ORDER BY p.id desc LIMIT $two,$one ");

        return $query->result_array();
    }

    function get_payment_history($user_fk) {
        $query = $this->db->query("SELECT 
  p.* ,DATE_FORMAT(p.paydate, '%d %b %y %h:%i %p') as date, GROUP_CONCAT(t.`test_name`) testname

FROM
  `payment` p
  LEFT JOIN job_master j 
  ON j.`id`=p.`job_fk`
 LEFT JOIN job_test_list_master jt
  ON j.`id`=jt.`job_fk` LEFT JOIN test_master t ON t.id=jt.`test_fk`
WHERE j.`status`!='0' AND p.uid = '$user_fk' 
   GROUP BY p.id ORDER BY p.id desc");
        return $query->result_array();
    }

    /* function completed_job($user_fk){
      $query = $this->db->query("SELECT j.id,j.order_id,DATE_FORMAT(DATE(j.date), '%d %b %y %h:%i %p') AS date,GROUP_CONCAT(tm.`test_name`) test ,j.`price`,j.`status` FROM job_master j LEFT JOIN job_test_list_master t ON j.id=t.`job_fk` LEFT JOIN test_master tm ON tm.id=t.`test_fk`  WHERE j.`status`=2 AND j.`cust_fk`='$user_fk' GROUP BY t.`job_fk` ORDER BY j.id desc ");
      $query1 = $query->result_array();
      return $query1;
      } */

    function get_completed_job1($user_fk, $one, $two) {

        $query = $this->db->query("SELECT j.id,j.order_id,DATE_FORMAT(j.date, '%d %b %y %h:%i %p') AS date,GROUP_CONCAT(tm.`test_name`) test ,(SELECT 
    GROUP_CONCAT(p.title) 
  FROM
    package_master p 
    INNER JOIN book_package_master pm 
      ON pm.package_fk = p.id 
  WHERE pm.job_fk = j.`id` 
  GROUP BY pm.`job_fk`) AS packge_name,j.`price`,j.`status` FROM job_master j LEFT JOIN job_test_list_master t ON j.id=t.`job_fk` LEFT JOIN test_master tm ON tm.id=t.`test_fk`  WHERE j.`status`=2 AND j.`cust_fk`='$user_fk' GROUP BY j.`id` ORDER BY j.id desc LIMIT $two,$one ");

        return $query->result_array();
    }

    function get_completed_job($user_fk) {
        $query = $this->db->query("SELECT j.id,j.order_id,DATE_FORMAT(j.date, '%d %b %y %h:%i %p') AS date,GROUP_CONCAT(tm.`test_name`) test ,j.`price`,j.`status` FROM job_master j LEFT JOIN job_test_list_master t ON j.id=t.`job_fk` LEFT JOIN test_master tm ON tm.id=t.`test_fk`  WHERE j.`status`=2 AND j.`cust_fk`='$user_fk' GROUP BY t.`job_fk` ORDER BY j.id desc ");
        return $query->result_array();
    }

    function cancle_job($user_fk) {
        $query = $this->db->query("SELECT j.id,j.order_id,DATE_FORMAT(j.date, '%d %b %y %h:%i %p') AS date,GROUP_CONCAT(tm.`test_name`) test ,j.`price`,j.`status` FROM job_master j LEFT JOIN job_test_list_master t ON j.id=t.`job_fk` LEFT JOIN test_master tm ON tm.id=t.`test_fk`  WHERE j.`status`=4 AND j.`cust_fk`='$user_fk' GROUP BY t.`job_fk` ORDER BY j.id desc ");
        $query1 = $query->result_array();
        return $query1;
    }

    function detail_job($user_fk, $job_fk) {
        $query = $this->db->query("SELECT j.id,j.order_id,DATE_FORMAT(j.date, '%d %b %y %h:%i %p') AS date,GROUP_CONCAT(tm.`test_name`) test ,j.`price`,j.`status` FROM job_master j LEFT JOIN job_test_list_master t ON j.id=t.`job_fk` LEFT JOIN test_master tm ON tm.id=t.`test_fk`  WHERE  j.id='$job_fk' AND j.`cust_fk`='$user_fk'");

        $query1 = $query->result_array();
        return $query1;
    }

    function suggest_test($pid, $ctid) {

        $query = $this->db->query("SELECT s.*,t.id testid,t.test_name,tc.price,t.description testdescription,p.`created_date`,p.`image`,p.`order_id` FROM `suggested_test` s LEFT JOIN test_master_city_price tc ON tc.test_fk=s.`test_id` LEFT JOIN test_master t ON t.id=tc.test_fk LEFT JOIN prescription_upload p ON p.id=s.p_id WHERE s.p_id='$pid' AND s.status=1 and tc.city_fk='$ctid'");
        $query1 = $query->result_array();
        return $query1;
    }

    function payment_history($user_fk) {
        //	$query = $this->db->query("SELECT id,debit,credit, DATE_FORMAT(DATE(created_time) ,'%d %b %y') AS date FROM `wallet_master` where cust_fk='$user_fk' and status=1");
        $query = $this->db->query("SELECT 
  p.* ,DATE_FORMAT(p.paydate, '%d %b %y %h:%i %p') as date, GROUP_CONCAT(t.`test_name`) testname

FROM
  `payment` p
  LEFT JOIN job_master j 
  ON j.`id`=p.`job_fk`
 LEFT JOIN job_test_list_master jt
  ON j.`id`=jt.`job_fk` LEFT JOIN test_master t ON t.id=jt.`test_fk`
WHERE p.uid = '$user_fk' 
   GROUP BY p.id ORDER BY p.id desc");
        $query1 = $query->result_array();
        return $query1;
    }

    public function ticket_details($ticket) {
        $query = "SELECT m.message,m.type,DATE_FORMAT((m.created_date), '%d %b %Y %h:%i %p') AS date,t.*,c.`full_name`,c.pic FROM message_master m LEFT JOIN ticket_master t ON t.id=m.`ticket_fk` LEFT JOIN customer_master c ON c.id=t.`user_id` WHERE t.`ticket`='$ticket'";


        $query = $this->db->query($query);
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    public function health_feed() {
        $query = "select *,DATE_FORMAT((created_date), '%d %b %Y %h:%i %p') AS date from health_feed_master where status=1 order by id asc";
        $query = $this->db->query($query);
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    public function num_row($table, $condition) {
        $query = $this->db->get_where($table, $condition);
        return $query->num_rows();
    }

    function view_test_report($jobfk) {
        $tstquery = $this->db->query("SELECT * FROM `job_master` WHERE id='" . $jobfk . "'");
        $tstquery1 = $tstquery->result_array();
        
        $test_city = $tstquery1[0]["test_city"];
        //$query = $this->db->query("SELECT r.*,t.test_name,t.price FROM report_master r LEFT JOIN test_master t ON t.id=r.`test_fk` WHERE r.job_fk='$jobfk' AND r.status='1' ");
        /*   $query = $this->db->query("SELECT 
          r.*,
          t.test_name,
          t.price,
          jm.*
          FROM `job_test_list_master` jm

          LEFT JOIN test_master t
          ON t.id = jm.`test_fk`
          LEFT JOIN report_master r
          ON jm.`test_fk` = r.`test_fk`  AND  r.`job_fk`= jm.`job_fk`

          WHERE jm.`job_fk` = '$jobfk' AND r.`type`='t'
          "); */
        $query = $this->db->query("SELECT * FROM `report_master` WHERE job_fk='" . $jobfk . "' AND STATUS='1'");
        $query1 = $query->result_array();
        $new_array = array();
        foreach ($query1 as $key) {
            if ($key["type"] == 't') {
                $test_query = $this->db->query("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master`.`id`='" . $key["test_fk"] . "'");
                $query1 = $test_query->result_array();
                $key["test_name"] = $query1[0]["test_name"];
                $key["price"] = $query1[0]["price"];
                $new_array[] = $key;
            }
            if ($key["type"] == 'p') {
                $test_query = $this->db->query("SELECT 
    `package_master`.*,
    `package_master_city_price`.`a_price` AS `a_price1`,
    `package_master_city_price`.`d_price` AS `d_price1`
  FROM
    `package_master` 
    INNER JOIN `package_master_city_price` 
      ON `package_master`.`id` = `package_master_city_price`.`package_fk` 
  WHERE `package_master`.`status` = '1' 
    AND `package_master_city_price`.`status` = '1' AND `package_master_city_price`.`city_fk` = '" . $test_city . "' AND `package_master`.`id` ='" . $key["test_fk"] . "'");
                $query1 = $test_query->result_array();
                $key["test_name"] = $query1[0]["title"];
                $key["price"] = $query1[0]["d_price1"];
                $new_array[] = $key;
            }
        }
        return $new_array;
    }

    public function get_val($query) {
        $query = $this->db->query($query);
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    function checkemail($mobile) {
        $query = $this->db->query("SELECT * FROM customer_master WHERE mobile='" . $mobile . "' AND `status`='1'");
        return $query->num_rows();
    }

    function checkemail1($mobile) {
        $query = $this->db->query("SELECT * FROM customer_master WHERE email='" . $mobile . "' AND `status`='1'");
        return $query->num_rows();
    }

}

?>