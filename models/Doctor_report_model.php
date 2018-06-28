<?php

Class Doctor_report_model extends CI_Model {

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
    function getPaymentReport_details($center = null, $from = null, $to = null, $branch = null, $type = null, $id) {
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

    function jobPaymentReport($from = null, $to = null, $id = null, $type = null, $branch = null) {
        $qry = "SELECT 
  jmr.payment_type,
  b.branch_name,
  am.`name`,
  am.id as aid,
  SUM(jmr.amount) as price,
  b.id as bid
FROM
  job_master jm 
JOIN `job_master_receiv_amount` jmr 
    ON jm.id = jmr.`job_fk`
  JOIN `admin_master` am 
    ON jmr.added_by = am.`id` 
  JOIN branch_master b 
    ON b.id = jm.branch_fk 
 
WHERE   jm.`status` != '0' and jmr.status = '1' ";

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

        $qry .= " group by b.id,jmr.added_by,jmr.payment_type order by b.`id`,am.`id` ASC";
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }

    function doctorPaymentReport($from = null, $to = null, $city = null, $doctor = null, $branch = null) {
        $date1 = explode("/", $from);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $to);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];

        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $bd = date('Y-m-d', strtotime($sd . ' -1 day'));
        $back_date = $bd . " 00:00:00";
        $back_date1 = $bd . " 23:59:59";
        $qry="select
                dm.full_name,
                dm.mobile,
                dm.cut,
                dm.id as did,
                bm.branch_name as name, 
                sm.id as sales_id,
                CONCAT(sm.first_name,' ',sm.last_name) as sales_name,
                bm.id as tid,
                SUM(jm.`price`) as price,
                SUM(jm.payable_amount) as due,
                count(jm.id) as job_count,
                SUM((jm.`discount` * jm.`price`) / 100) as discount
                from job_master jm join doctor_master dm on dm.id=jm.doctor join branch_master bm on bm.id=jm.branch_fk join test_cities tc on tc.id=jm.test_city left join doctor_handover dh on dh.doctor_fk=dm.id left join sales_user_master sm on sm.id=dh.sales_person_fk where jm.status != '0' ";
        $date1 = explode("/", $from);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $to);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];

        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $qry .= " AND jm.date >= '" . $start_date . "' AND jm.date <= '" . $end_date . "'";
        if($branch != "") {
            $qry .=" AND jm.branch_fk = '" .$branch. "' ";
        }
        if($city != "") {
            $qry .=" AND jm.test_city = '" .$city. "' ";
        }
        if($doctor != "") {
            $qry .=" AND jm.doctor = '" .$doctor. "' ";
        }
        $qry .= " group by jm.branch_fk,jm.doctor order by jm.branch_fk,dm.`id` ASC";
        //echo $qry; die();
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }
    
    function doctorPaymentdetails($from = null, $to = null, $doctor = null, $branch = null) {
        $date1 = explode("/", $from);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $to);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];

        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $bd = date('Y-m-d', strtotime($sd . ' -1 day'));
        $back_date = $bd . " 00:00:00";
        $back_date1 = $bd . " 23:59:59";
        $qry="select
                cm.full_name,
                jm.id,
                dm.cut,
                dm.id as did,
                jm.`price`,
                jm.payable_amount as due,
                ((jm.`discount` * jm.`price`) / 100) as discount
                from job_master jm join doctor_master dm on dm.id=jm.doctor left join customer_master cm on cm.id=jm.cust_fk join branch_master bm on bm.id=jm.branch_fk where jm.status != '0' ";
        $date1 = explode("/", $from);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $to);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];

        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $qry .= " AND jm.date >= '" . $start_date . "' AND jm.date <= '" . $end_date . "'";
        if($doctor != "") {
            $qry .=" AND jm.doctor = '" .$doctor. "' ";
        }
        if($branch != "") {
            $qry .=" AND jm.branch_fk = '" .$branch. "' ";
        }
        $qry .= " order by bm.`id`,dm.`id` ASC";
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