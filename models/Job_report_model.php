<?php

Class Job_report_model extends CI_Model {

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

    function collecting_amount_branch($center = null, $date = null) {
        $qry = "SELECT SUM(jmr.amount) AS SUM,jmr.`added_by` AS user_fk,am.`name`,jmr.`createddate`,b.branch_name FROM `job_master_receiv_amount` jmr LEFT JOIN `admin_received_amount` ON `admin_received_amount`.`admin_fk` = jmr.`added_by` INNER JOIN `admin_master` am ON jmr.added_by = am.`id` join user_branch ub on ub.user_fk=am.id join branch_master b on b.id=ub.branch_fk  WHERE added_by != '' AND jmr.`status` = '1' and ub.status = '1'";
        if (!empty($center)) {
            $qry .= " AND ub.branch_fk in (" . implode(",", $center) . ")";
        }
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
        $qry .= "  GROUP BY jmr.`added_by`";
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }

    function getPaymentReport($center = null, $from = null, $to = null, $branch = null, $type = null) {
        $qry = "SELECT 
jm.id AS job_id,
jmr.payment_type as type,
jm.`order_id`,
  jmr.amount,
  jmr.`added_by` AS user_fk,
  jmr.`createddate`,
  b.branch_name ,
  IF(
    `jmr`.`phlebo_fk` > 0,
    CONCAT(phlebo_master.`name`,'','(Phlebo)'),`am`.`name`
  ) AS `name`
FROM
  `job_master_receiv_amount` jmr 
LEFT JOIN job_master jm 
    ON jm.id = jmr.`job_fk` 
  LEFT JOIN `admin_received_amount` 
    ON `admin_received_amount`.`admin_fk` = jmr.`added_by` 
  LEFT JOIN `admin_master` am 
    ON jmr.added_by = am.`id` 
  LEFT JOIN `phlebo_master` 
    ON `phlebo_master`.`id`=`jmr`.`phlebo_fk`
  LEFT JOIN `admin_master` u 
    ON u.id = jmr.`added_by` 
  LEFT JOIN branch_master b 
    ON b.id = jm.branch_fk 
    INNER JOIN `job_master`
    ON `job_master`.`id`=`jmr`.`job_fk` AND `job_master`.`status`!='0'
    WHERE  jmr.`status` = '1'";
        if (!empty($center)) {
            $qry .= " AND b.id in (" . implode(",", $center) . ")";
        }
        $date1 = explode("/", $from);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $to);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];

        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $qry .= " AND jmr.createddate >= '" . $start_date . "' AND jmr.createddate <= '" . $end_date . "'";
        if ($branch != "") {
            $qry .= " AND b.id  = '" . $branch . "' ";
        }
        if ($type != '') {
            $qry .= " AND jmr.payment_type  = '" . $type . "' ";
        }
        $qry .= "  order by jmr.`createddate`";
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }

    /* END */

    function getMyPaymentReport($from = null, $to = null, $id = null, $type = null) {
        $qry = "SELECT 
  jm.id AS job_id,

  jm.`order_id`,
jm.`payable_amount`,
jm.`price`,
jm.`discount`,
  jm.`date`,
  b.branch_name,
  am.`name`
FROM
  job_master jm 
 
  
  LEFT JOIN `admin_master` am 
    ON jm.added_by = am.`id` 
  LEFT JOIN branch_master b 
    ON b.id = jm.branch_fk 
 
WHERE   jm.`status` != '0' and jm.added_by='" . $id . "'";

        $date1 = explode("/", $from);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $to);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];

        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $qry .= " AND jm.date >= '" . $start_date . "' AND jm.date <= '" . $end_date . "'";

        $qry .= "  order by jm.`date`";
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }

    function getMyPaymentReportNew($from = null, $to = null, $id = null, $type = null) {
        $qry = "SELECT 
  jm.id AS job_id,
  jm.`order_id`,
  jm.`payable_amount`,
  jm.`price`,
  jmr.`amount`,
  jm.`discount`,
  jmr.`createddate` AS `date`,
  b.branch_name,
  am.`name` 
FROM
  job_master jm 
 
  
  
  LEFT JOIN branch_master b 
    ON b.id = jm.branch_fk 
  LEFT JOIN `job_master_receiv_amount` jmr ON jmr.`job_fk`=jm.id
  LEFT JOIN `admin_master` am 
    ON jmr.added_by = am.`id` 
WHERE   jm.`status` != '0' AND jmr.added_by='" . $id . "'";
        $date1 = explode("/", $from);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $to);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];

        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $qry .= " AND jmr.createddate >= '" . $start_date . "' AND jmr.createddate <= '" . $end_date . "'";
        $qry .= "  order by jm.`date`";
        if ($_REQUEST["debug"] == 1) {
            echo $qry;
            die();
        }
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }

    function getPaymentReport_type($center = null, $from = null, $to = null, $branch = null, $type = null, $id) {
        $qry = "SELECT 
jm.id AS job_id,
jmr.payment_type as type,
jm.`order_id`,
  jmr.amount,
  jmr.`added_by` AS user_fk,
  jmr.`createddate`,
  b.branch_name ,
  IF(
    `jmr`.`phlebo_fk` > 0,
    CONCAT(phlebo_master.`name`,'','(Phlebo)'),`am`.`name`
  ) AS `name`
FROM
  `job_master_receiv_amount` jmr 
LEFT JOIN job_master jm 
    ON jm.id = jmr.`job_fk` 
  LEFT JOIN `admin_received_amount` 
    ON `admin_received_amount`.`admin_fk` = jmr.`added_by` 
  LEFT JOIN `admin_master` am 
    ON jmr.added_by = am.`id` 
  LEFT JOIN `phlebo_master` 
    ON `phlebo_master`.`id`=`jmr`.`phlebo_fk`
  LEFT JOIN `admin_master` u 
    ON u.id = jmr.`added_by` 
  LEFT JOIN branch_master b 
    ON b.id = jm.branch_fk 
    INNER JOIN `job_master`
    ON `job_master`.`id`=`jmr`.`job_fk` AND `job_master`.`status`!='0'
    WHERE  jmr.`status` = '1' and jmr.added_by='$id' ";
        if (!empty($center)) {
            $qry .= " AND b.id in (" . implode(",", $center) . ")";
        }
        $date1 = explode("/", $from);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $to);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];

        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $qry .= " AND jmr.createddate >= '" . $start_date . "' AND jmr.createddate <= '" . $end_date . "'";
        if ($branch != "") {
            $qry .= " AND b.id  = '" . $branch . "' ";
        }
        if ($type != '') {
            $qry .= " AND jmr.payment_type  = '" . $type . "' ";
        }
        $qry .= "  order by jmr.`createddate`";
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }

    function getPaymentReport_details($center = null, $from = null, $type = null, $id = null, $phlebo = null) {
        $qry = "SELECT 
jm.id AS job_id,
jm.date as job_date,
cm.full_name as patient,
jmr.payment_type as type,
jm.`order_id`,
  jmr.amount,
  jmr.`added_by` AS user_fk,
  jmr.`createddate`,
  b.branch_name ,
  IF(
    `jmr`.`phlebo_fk` > 0,
    CONCAT(phlebo_master.`name`,'','(Phlebo)'),`am`.`name`
  ) AS `name`
FROM
  `job_master_receiv_amount` jmr 
LEFT JOIN job_master jm 
    ON jm.id = jmr.`job_fk` 
    LEFT JOIN customer_master cm
    ON cm.id = jm.cust_fk
  LEFT JOIN `admin_received_amount` 
    ON `admin_received_amount`.`admin_fk` = jmr.`added_by` 
  LEFT JOIN `admin_master` am 
    ON jmr.added_by = am.`id` 
  LEFT JOIN `phlebo_master` 
    ON `phlebo_master`.`id`=`jmr`.`phlebo_fk`
  LEFT JOIN `admin_master` u 
    ON u.id = jmr.`added_by` 
  LEFT JOIN branch_master b 
    ON b.id = jm.branch_fk 
    INNER JOIN `job_master`
    ON `job_master`.`id`=`jmr`.`job_fk` AND `job_master`.`status`!='0'
    WHERE  jmr.`status` = '1' ";
        if ($id != "") {
            $qry .= " AND jmr.added_by = '$id'";
        }
        if ($phlebo != '') {
            $qry .= " AND jmr.phlebo_fk = '$phlebo'";
        }
        if (!empty($center)) {
            $qry .= " AND b.id in (" . implode(",", $center) . ")";
        }
        $date1 = explode("/", $from);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];

        $start_date = $sd . " 00:00:00";
        $end_date = $sd . " 23:59:59";
        $qry .= " AND jmr.createddate >= '" . $start_date . "' AND jmr.createddate <= '" . $end_date . "'";
        if ($branch != "") {
            //$qry .= " AND b.id  = '" . $branch . "' ";
        }
        if ($type != '') {
            $qry .= " AND jmr.payment_type  = '" . $type . "' ";
        }
        $qry .= "  order by jmr.`createddate`";
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }

    function jobPaymentReport_old($from = null, $to = null, $id = null, $type = null) {
        $qry = "SELECT 
  jm.id AS job_id,
  jmr.payment_type,
  jm.`order_id`,
jm.`payable_amount`,
jm.`price`,
jm.`discount`,
  jm.`date`,
  b.branch_name,
  am.`name`
FROM
  job_master jm 
LEFT JOIN `job_master_receiv_amount` jmr 
    ON jm.id = jmr.`job_fk`
  LEFT JOIN `admin_master` am 
    ON jmr.added_by = am.`id` 
  LEFT JOIN branch_master b 
    ON b.id = jm.branch_fk 
 
WHERE   jm.`status` != '0'";

        $date1 = explode("/", $from);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $to);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];

        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $qry .= " AND jm.date >= '" . $start_date . "' AND jm.date <= '" . $end_date . "'";

        $qry .= " order by jm.`date`";
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }

    function jobPaymentReport($from = null, $to = null, $id = null, $type = null, $city = null) {
        $qry = "SELECT 
  if(isnull(jmr.payment_type),'Payu Money' ,jmr.payment_type) as payment_type ,
  b.branch_name,
   if(isnull(jmr.`phlebo_fk`),am.`name`,pm.`name`) as name,
 IF(ISNULL(jmr.`phlebo_fk`),CONCAT(am.`id`,'a'),CONCAT(pm.`id`,'p')) as aid,
   am.id as aid2,
  SUM(jmr.amount) as price,

  b.id as bid
FROM
  job_master jm 
  LEFT JOIN `job_master_receiv_amount` jmr 
    ON jm.id = jmr.`job_fk` 
  LEFT JOIN `admin_master` am 
    ON jmr.added_by = am.`id` 
LEFT JOIN `phlebo_master` pm 
    ON pm.`id`= jmr.`phlebo_fk`
  JOIN branch_master b 
    ON b.id = jm.branch_fk 
    join test_cities t
    on t.id = b.city
 
WHERE   jm.`status` != '0' and jmr.status = '1'   ";

        $date1 = explode("/", $from);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $to);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];

        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $qry .= " AND jmr.createddate >= '" . $start_date . "' AND jmr.createddate <= '" . $end_date . "'";
        if ($city != "") {
            $qry .= " AND t.id  = '" . $city . "' ";
        }
        if ($type != '') {
            $qry .= " AND jmr.payment_type  = '" . $type . "' ";
        }
  
        $qry .= " group by b.id,if(isnull(jmr.`phlebo_fk`),concat(am.`id`,'a'),concat(pm.`id`,'p')),jmr.payment_type order by b.`id`,am.`id` ASC";
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }

    function branchPaymentReport($from = null, $id = null, $branch = null, $cid = null) {
        $date1 = explode("/", $from);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
//        $date1 = explode("/", $to);
//        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];

        $start_date = $sd . " 00:00:00";
        $end_date = $sd . " 23:59:59";
        $bd = date('Y-m-d', strtotime($sd . ' -1 day'));
        $back_date = $bd . " 00:00:00";
        $back_date1 = $bd . " 23:59:59";
        $qry = "SELECT 
  jmr.createddate,
  b.branch_name,
  IF(
    `jm`.`phlebo_added` != '',
    CONCAT(pm.`name`,'','(Phlebotomy)'),`am`.`name`
  ) AS `name`,
  jm.phlebo_added as phlebo_id,
  am.id as aid,
  b.id as bid,
  (SELECT SUM(jb.`payable_amount`) from job_master jb where (jb.added_by=am.id OR jb.phlebo_added=pm.id) AND jb.branch_fk = jm.`branch_fk` and jb.`status` != '0' AND jb.date >= '" . $start_date . "' AND jb.date <= '" . $end_date . "') as payable_amount,
      (SELECT SUM(jb1.`price`) from job_master jb1 where (jb1.added_by=am.id OR jb1.phlebo_added=pm.id) AND jb1.branch_fk = jm.`branch_fk` and jb1.`status` != '0' AND jb1.date >= '" . $start_date . "' AND jb1.date <= '" . $end_date . "') as price,
          (SELECT SUM(Round((jb2.`discount` * jb2.`price`) / 100)) from job_master jb2 where (jb2.added_by=am.id OR jb2.phlebo_added=pm.id) AND jb2.branch_fk = jm.`branch_fk`  and jb2.`status` != '0' AND jb2.date >= '" . $start_date . "' AND jb2.date <= '" . $end_date . "') as discount,
  jm.`date`,
  (SELECT SUM(c.amount) from job_master_receiv_amount c LEFT JOIN job_master jb ON jb.id = c.job_fk where (c.added_by=am.id or c.phlebo_fk=pm.id) AND jb.branch_fk = jm.`branch_fk` and c.payment_type='CASH' AND c.createddate >= '" . $start_date . "' AND c.status='1' AND c.createddate <= '" . $end_date . "') as cash_total,
  (SELECT SUM(ch.amount) from job_master_receiv_amount ch LEFT JOIN job_master jb ON jb.id = ch.job_fk where (ch.added_by=am.id or ch.phlebo_fk=pm.id) AND jb.branch_fk = jm.`branch_fk` and ch.payment_type='CHEQUE' AND ch.createddate >= '" . $start_date . "' AND ch.status='1' AND ch.createddate <= '" . $end_date . "') as cheque_total,
  (SELECT SUM(cr.amount) from job_master_receiv_amount cr LEFT JOIN job_master jb ON jb.id = cr.job_fk where (cr.added_by=am.id or cr.phlebo_fk=pm.id) AND jb.branch_fk = jm.`branch_fk` and (cr.payment_type='CREDIT CARD' or cr.payment_type='CREDIT CARD swiped thru ICICI' or cr.payment_type='CREDIT CARD swiped thru MSWIP' or cr.payment_type='DEBIT CARD swiped thru ICICI' or cr.payment_type='DEBIT CARD swiped thru MSWIP' or cr.payment_type='Swipe thru HDFC' or cr.payment_type='Swipe thru AXIS' or cr.payment_type='DEBIT CARD') AND cr.createddate >= '" . $start_date . "' AND cr.status='1' AND cr.createddate <= '" . $end_date . "') as credit_total,
      (SELECT SUM(cr.amount) from job_master_receiv_amount cr LEFT JOIN job_master jb ON jb.id = cr.job_fk where (cr.added_by=am.id or cr.phlebo_fk=pm.id) AND jb.branch_fk = jm.`branch_fk` and (cr.payment_type='ONLINE' or cr.payment_type='SALARY ACCOUNT' or cr.payment_type='WALLET DEBIT' or cr.payment_type='WEB ONLINE' or cr.payment_type='PayTm') AND cr.createddate >= '" . $start_date . "' AND cr.status='1' AND cr.createddate <= '" . $end_date . "') as other_total,
      (select SUM(s.amount) from job_master_receiv_amount s join job_master jbs on jbs.id=s.job_fk where (s.added_by=am.id or s.phlebo_fk=pm.id) AND jbs.branch_fk = jm.`branch_fk` and s.createddate >='" . $start_date . "' and s.createddate <='" . $end_date . "' and s.status='1' AND jbs.date >='" . $start_date . "' AND jbs.date <='" . $end_date . "') as same_day,
      (select SUM(b.amount) from job_master_receiv_amount b join job_master jbc on jbc.id=b.job_fk where (b.added_by=am.id or b.phlebo_fk=pm.id) AND jbc.branch_fk = jm.`branch_fk` and b.createddate<='" . $end_date . "' and b.createddate>='" . $start_date . "' and b.status='1' AND jbc.date <='" . $back_date1 . "') as back_day
FROM
  job_master jm 
LEFT JOIN `job_master_receiv_amount` jmr 
    ON jm.id = jmr.`job_fk`
 LEFT JOIN `admin_master` am 
    ON jmr.added_by = am.`id` 
    LEFT JOIN `phlebo_master` pm
    ON jm.phlebo_added = pm.id
  JOIN branch_master b 
    ON b.id = jm.branch_fk 
   join test_cities tc 
    on tc.id = b.city
 
WHERE   jm.`status` != '0' and jmr.status = '1' ";

        $date1 = explode("/", $from);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];

        $start_date = $sd . " 00:00:00";
        $end_date = $sd . " 23:59:59";
        $qry .= " AND jmr.createddate >= '" . $start_date . "' AND jmr.createddate <= '" . $end_date . "'";
        if (!empty($branch)) {
            $qry .= " AND b.id  in (" . implode(",", $branch) . ")";
        }
        if (!empty($id)) {
            $qry .= " AND b.id  = '" . $id . "'";
        }
        if (!empty($cid)) {
            $qry .= " AND tc.id  = '" . $cid . "'";
        }
        $qry .= " group by jm.branch_fk,jmr.`added_by`,jm.`phlebo_added` order by b.`id` ASC";

        if ($_REQUEST["debug"] == 1) {
            echo $qry;
            die();
        }

        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }

}

?>