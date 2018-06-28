<?php

Class User_model extends CI_Model {

    function getmenu() {
        // $this->db->order_by("id", "desc");
        $query = $this->db->get_where("bmh_menu_master", array("status" => "1"));
        return $query->result_array();
    }
    function collecting_amount_branch($center = null, $date = null) {
        $qry = "SELECT 
jmr.type,
  SUM(jmr.amount) AS SUM,
  jmr.`createddate`,
  b.branch_name,
  am.name
FROM
  `job_master_receiv_amount` jmr 
LEFT JOIN job_master jm 
    ON jm.id = jmr.`job_fk` 
  LEFT JOIN `admin_received_amount` 
    ON `admin_received_amount`.`admin_fk` = jmr.`added_by` 
  LEFT JOIN `admin_master` am 
    ON jmr.added_by = am.`id` 
  LEFT JOIN branch_master b 
    ON b.id = jm.branch_fk 
    WHERE  jmr.`status` = '1'";
        if (!empty($center)) {
            $qry .= " AND b.id in (" . implode(",", $center) . ")";
        }
        if (!empty($date)) {
            $date1 = explode("/", $date);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];

        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $qry .= " AND jmr.createddate >= '" . $start_date . "' AND jmr.createddate <= '" . $end_date . "'";
        } else {
            $start_date = date('Y-m-d') . " 00:00:00";
            $end_date = date('Y-m-d') . " 23:59:59";
            $qry .= " AND jmr.createddate >= '" . $start_date . "' AND jmr.createddate <= '" . $end_date . "'";
        }
        $qry .= "  GROUP BY b.id";
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }
    
    function total_amount_branch($center = null, $date = null) {
        $qry = "SELECT 
jmr.type,
  SUM(jmr.amount) AS SUM,
  jmr.`createddate`,
  b.branch_name,
  am.name
FROM
  `job_master_receiv_amount` jmr 
LEFT JOIN job_master jm 
    ON jm.id = jmr.`job_fk` 
  LEFT JOIN `admin_received_amount` 
    ON `admin_received_amount`.`admin_fk` = jmr.`added_by` 
  LEFT JOIN `admin_master` am 
    ON jmr.added_by = am.`id` 
  LEFT JOIN branch_master b 
    ON b.id = jm.branch_fk 
    WHERE  jmr.`status` = '1'";
        if (!empty($center)) {
            $qry .= " AND b.id in (" . implode(",", $center) . ")";
        }
        if (!empty($date)) {
            $date1 = explode("/", $date);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];

        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $qry .= " AND jmr.createddate >= '" . $start_date . "' AND jmr.createddate <= '" . $end_date . "'";
        } else {
            $start_date = date('Y-m-d') . " 00:00:00";
            $end_date = date('Y-m-d') . " 23:59:59";
            $qry .= " AND jmr.createddate >= '" . $start_date . "' AND jmr.createddate <= '" . $end_date . "'";
        }
        $qry .= "  GROUP BY b.id,jmr.type";
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }

    function getUser($user_id) {
        $query = $this->db->get_where("admin_master", array("id" => $user_id, "status" => "1"));
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

    function total_revenue($center = null, $date = null) {
        $qry = "SELECT SUM(price) AS revenue FROM `job_master` WHERE `status`!='0'";
        if (!empty($date)) {
            $start_date = date('Y-m-d', strtotime($date . ' day', strtotime(date("Y-m-d")))) . " 00:00:00";
            $end_date = date("Y-m-d") . " 23:59:59";
            $qry .= " AND DATE>='" . $start_date . "' AND date<='" . $end_date . "'";
        } else {
            $qry .= " AND DATE>='" . date("Y-m-d") . "'";
        }
        if (!empty($center)) {
            $qry .= " AND branch_fk in (" . implode(",", $center) . ")";
        }
        $qry;
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }

    function total_due($center = null, $date = null) {
        $qry = "SELECT SUM(payable_amount) AS due_amount FROM `job_master` WHERE `status`!='0'";
        if (!empty($date)) {
            $start_date = date('Y-m-d', strtotime($date . ' day', strtotime(date("Y-m-d")))) . " 00:00:00";
            $end_date = date("Y-m-d") . " 23:59:59";
            $qry .= " AND DATE>='" . $start_date . "' AND date<='" . $end_date . "'";
        } else {
            $qry .= " AND DATE>='" . date("Y-m-d") . "'";
        }
        if (!empty($center)) {
            $qry .= " AND branch_fk in (" . implode(",", $center) . ")";
        }
        //echo $qry; die();
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }

    function get_job_status($center = null, $date = null) {
        $qry = "SELECT `status`,COUNT(*) as count FROM job_master WHERE `status`!='0'";
        if (!empty($date)) {
            $start_date = date('Y-m-d', strtotime($date . ' day', strtotime(date("Y-m-d")))) . " 00:00:00";
            $end_date = date("Y-m-d") . " 23:59:59";
            $qry .= " AND DATE>='" . $start_date . "' AND date<='" . $end_date . "'";
        } else {
            $qry .= " AND DATE>='" . date("Y-m-d") . "'";
        }
        if (!empty($center)) {
            $qry .= " AND branch_fk in (" . implode(",", $center) . ")";
        }
        $qry .= " GROUP BY `status` order by `status` asc";
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }

    function total_sample($center = null) {
        $qry = "SELECT COUNT(*) AS test FROM `job_master` WHERE `status` IN (7,8,2) AND  `date`>='" . date("Y-m-d") . "'";
        if (!empty($center)) {
            $qry .= " AND branch_fk in (" . implode(",", $center) . ")";
        }
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }

    function collecting_amount($center = null, $date = null) {
        $qry = "SELECT 
  SUM(jmr.amount) AS SUM,
  jmr.`added_by` AS user_fk,
  am.`name`,
  jmr.`createddate`,
  admin_received_amount.`admin_fk` AS admin_fk 
FROM
  `job_master_receiv_amount` jmr 
  INNER JOIN `admin_master` am 
    ON jmr.`added_by` = am.`id` 
  LEFT JOIN `admin_received_amount` 
    ON `admin_received_amount`.`admin_fk` = jmr.`added_by` 
WHERE added_by != '' 
  AND jmr.`status` = '1'";
        if (!empty($date)) {
            $date1 = explode("/", $date);
            $date = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
            $start_date = $date . " 00:00:00";
            $end_date = $date . " 23:59:59";
            $qry .= " AND jmr.createddate >= '" . $start_date . "' AND jmr.createddate <= '" . $end_date . "'";
        } else {
            $start_date = date('Y-m-d') . " 00:00:00";
            $end_date = date('Y-m-d') . " 23:59:59";
            $qry .= " AND jmr.createddate >= '" . $start_date . "' AND jmr.createddate <= '" . $end_date . "'";
        }
        $qry .= "  GROUP BY jmr.added_by";
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }

    function total_test($center = null) { 
        $qry = "SELECT COUNT(*) AS test FROM `job_master` WHERE STATUS!='0' AND  DATE>='" . date("Y-m-d") . "'";
        if (!empty($center)) {
            $qry .= " AND branch_fk in (" . implode(",", $center) . ")";
        }
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }

    function last_7days_test($week) {
        /* $query = $this->db->query("SELECT 
          DAYNAME(DATE) WEEK,
          COUNT(DAYNAME(DATE)) As total
          FROM
          job_master
          WHERE DATE BETWEEN DATE_SUB(NOW(), INTERVAL 7 DAY)
          AND NOW() AND DAYNAME(DATE)='$week'
          GROUP BY DAYNAME(DATE)
          ORDER BY DAY(DATE) DESC "); */
        $query = $this->db->query("SELECT DAYNAME(DATE) WEEK,
  COUNT(DAYNAME(DATE)) AS total FROM job_master
WHERE DATE >= CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+6 DAY
AND DATE < CURDATE() - INTERVAL DAYOFWEEK(CURDATE())-1 DAY 
AND NOW() AND status!='0' AND DAYNAME(DATE)='" . $week . "'");
        $query1 = $query->result_array();
        return $query1;
    }

    function last_7days_Sample($week) {
        /* $query = $this->db->query("SELECT 
          DAYNAME(DATE) WEEK,
          COUNT(DAYNAME(DATE)) As total
          FROM
          job_master
          WHERE DATE BETWEEN DATE_SUB(NOW(), INTERVAL 7 DAY)
          AND NOW() AND DAYNAME(DATE)='$week' AND `sample_collection`=1
          GROUP BY DAYNAME(DATE)
          ORDER BY DAY(DATE) DESC "); */
        $query = $this->db->query("SELECT DAYNAME(DATE) WEEK,
  COUNT(DAYNAME(DATE)) AS total FROM job_master
WHERE DATE >= CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+6 DAY
AND DATE < CURDATE() - INTERVAL DAYOFWEEK(CURDATE())-1 DAY 
AND NOW() AND status IN (2,8,7) AND DAYNAME(DATE)='" . $week . "'");
        $query1 = $query->result_array();
        return $query1;
    }

    function last_7days_amount($week) {
        /* $query = $this->db->query("SELECT 
          DAYNAME(DATE) WEEK,
          SUM(price) total
          FROM
          job_master
          WHERE DATE BETWEEN DATE_SUB(NOW(), INTERVAL 7 DAY)
          AND NOW() AND DAYNAME(DATE)='$week' AND `sample_collection`=1
          GROUP BY DAYNAME(DATE)
          ORDER BY DAY(DATE) DESC "); */
        $query = $this->db->query("SELECT DAYNAME(DATE) WEEK,
 SUM(price) total FROM job_master
WHERE DATE >= CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+6 DAY
AND DATE < CURDATE() - INTERVAL DAYOFWEEK(CURDATE())-1 DAY 
AND NOW() AND status !='0' AND DAYNAME(DATE)='" . $week . "'");
        $query1 = $query->result_array();
        return $query1;
    }

    function last_7days_cashback($week) {
        /* $query = $this->db->query("SELECT 
          DAYNAME(`created_time`) WEEK,
          SUM(credit) total
          FROM
          `wallet_master`
          WHERE `created_time` BETWEEN DATE_SUB(NOW(), INTERVAL 7 DAY)
          AND NOW() AND  `type`='Case Back'  AND DAYNAME(`created_time`)='$week'
          GROUP BY DAYNAME(`created_time`)
          ORDER BY DAY(`created_time`) DESC "); */
        $query = $this->db->query("SELECT DAYNAME(DATE) WEEK,
 SUM(price) total FROM job_master
WHERE DATE >= CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+6 DAY
AND DATE < CURDATE() - INTERVAL DAYOFWEEK(CURDATE())-1 DAY 
AND NOW() AND status IN (2,8,7) AND DAYNAME(DATE)='" . $week . "'");
        $query1 = $query->result_array();
        return $query1;
    }

    function get_val($query) {
        $query = $this->db->query($query);
        $query1 = $query->result_array();
        return $query1;
    }

    /* public function grosstotal() {



      $this->db->select('sum(j.price) as grosstotal');
      $this->db->from('job_master as j');
      //$this->db->join('job_master_receiv_amount as jam','j.id = jam.job_fk','left');
      $this->db->where("j.status !=","0");
      $this->db->ORDER_BY('j.id','desc');
      $query = $this->db->get();

      return $query->row();

      }



      public function grosstotal_paid() {



      $this->db->select('sum(j.amount) as grosstotal_paid');
      $this->db->from('job_master_receiv_amount as j');
      //$this->db->join('job_master_receiv_amount as jam','j.id = jam.job_fk','left');
      $this->db->where("j.status !=","0");
      $this->db->ORDER_BY('j.id','desc');
      $query = $this->db->get();

      return $query->row();

      }



      public function ts_list_search($from_date,$to_date)
      {

      if ($from_date != "") {
      $date = DateTime::createFromFormat('d / m / Y', $from_date);
      $from_date = $date->format('Y-m-d');
      }
      if ($to_date != "") {
      $date = DateTime::createFromFormat('d / m / Y', $to_date);
      $to_date = $date->format('Y-m-d');
      }

      $this->db->select('j.payable_amount,j.price ');
      $this->db->from('job_master as j');
      $this->db->join('job_master_receiv_amount as jma','jma.job_fk = j.id','left');
      // $this->db->join('paypal_payment as pp','pp.user_fk = pgr.client_fk','left');

      if($from_date != "" OR $to_date != "")
      {

      $this->db->where("date_format(j.created_date,'%Y-%m-%d') BETWEEN '".$from_date."' AND '".$to_date."'");

      }


      $this->db->where('j.status !=','0');
      $this->db->ORDER_BY('j.id','desc');

      $this->db->GROUP_BY('j.id');
      $query = $this->db->get();
      return $query->result();

      } */
}

?>
