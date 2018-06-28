<?php

class Doctor_app_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function master_fun_get_tbl_val($dtatabase, $condition, $order, $select = null) {
        $this->db->order_by($order[0], $order[1]);
        if ($select != null) {
            $this->db->select(implode(",", $select));
        }
        $query = $this->db->get_where($dtatabase, $condition);
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    public function master_fun_insert($table, $data) {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function master_fun_update($tablename, $cid, $data) {
        $this->db->where('id', $cid);
        $this->db->update($tablename, $data);
        return 1;
    }

    public function master_fun_delete($tablename, $id) {
        $this->db->where($id[0], $id[1]);
        $this->db->delete($tablename);
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

    public function master_fun_update1($tablename, $condition, $data) {
        $this->db->where($condition);
        $this->db->update($tablename, $data);
        return 1;
    }

    public function master_fun_update_new($tablename, $cid, $data) {
        $this->db->where($cid[0], $cid[1]);
        $this->db->update($tablename, $data);
        return 1;
    }

    function forgot_password($data, $phone) {
        $this->db->update("bmh_registration", $data, array("phone" => $phone));
        return 1;
    }

    function get_quote_count($user_fk) {

        $query = $this->db->query("select id,user_fk,price from bmh_user_quotation where user_fk='$user_fk' and status=1");
        $query1 = $query->result_array();
        return $query1;
    }

    function search_test($testname) {

        $query = $this->db->query("select * from test_master where test_name like '%$testname%' and status=1");
        $query1 = $query->result_array();
        return $query1;
    }

    function my_job($user_fk) {
        $query = $this->db->query("SELECT j.id,j.order_id,DATE_FORMAT((j.date), '%d %b %y %h:%i %p') AS date,GROUP_CONCAT(tm.`test_name`) test ,(SELECT 
    GROUP_CONCAT( p.title ) 
  FROM
    package_master p 
    INNER JOIN book_package_master pm 
      ON pm.package_fk = p.id 
  WHERE pm.job_fk = j.`id` 
  GROUP BY pm.`job_fk`) AS packge_name,j.`price`,j.`status` FROM job_master j LEFT JOIN job_test_list_master t ON j.id=t.`job_fk` LEFT JOIN test_master tm ON tm.id=t.`test_fk`  WHERE j.`status` IN (1,2,3,4,6,7,8) AND j.`cust_fk`='$user_fk' GROUP BY j.id order by j.id desc  ");
        $query1 = $query->result_array();
        return $query1;
    }

    function pending_job($user_fk) {
        $query = $this->db->query("SELECT j.id,j.order_id,DATE_FORMAT((j.date), '%d %b %y %h:%i %p') AS date,GROUP_CONCAT(tm.`test_name`) test ,(SELECT 
    GROUP_CONCAT( p.title ) 
  FROM
    package_master p 
    INNER JOIN book_package_master pm 
      ON pm.package_fk = p.id 
  WHERE pm.job_fk = j.`id` 
  GROUP BY pm.`job_fk`) AS packge_name,j.`price`,j.`status` FROM job_master j LEFT JOIN job_test_list_master t ON j.id=t.`job_fk` LEFT JOIN test_master tm ON tm.id=t.`test_fk`  WHERE j.`status`=1 AND j.`cust_fk`='$user_fk' GROUP BY j.id  order by j.id desc");
        $query1 = $query->result_array();
        return $query1;
    }

    function joblist_by_status($user_fk, $status) {
        $query = $this->db->query("SELECT j.id,j.order_id,DATE_FORMAT((j.date), '%d %b %y %h:%i %p') AS date,GROUP_CONCAT(tm.`test_name`) test ,(SELECT 
    GROUP_CONCAT( p.title ) 
  FROM
    package_master p 
    INNER JOIN book_package_master pm 
      ON pm.package_fk = p.id 
  WHERE pm.job_fk = j.`id` 
  GROUP BY pm.`job_fk`) AS packge_name,j.`price`,j.`status` FROM job_master j LEFT JOIN job_test_list_master t ON j.id=t.`job_fk` LEFT JOIN test_master tm ON tm.id=t.`test_fk`  WHERE j.`status`=$status AND j.`cust_fk`='$user_fk' GROUP BY j.id  order by j.id desc");
        $query1 = $query->result_array();
        return $query1;
    }

    function completed_job($user_fk) {
        $query = $this->db->query("SELECT j.id,j.order_id,DATE_FORMAT((j.date), '%d %b %y %h:%i %p') AS date,GROUP_CONCAT(tm.`test_name`) test ,(SELECT 
    GROUP_CONCAT( p.title ) 
  FROM
    package_master p 
    INNER JOIN book_package_master pm 
      ON pm.package_fk = p.id 
  WHERE pm.job_fk = j.`id` 
  GROUP BY pm.`job_fk`) AS packge_name,j.`price`,j.`status` FROM job_master j LEFT JOIN job_test_list_master t ON j.id=t.`job_fk` LEFT JOIN test_master tm ON tm.id=t.`test_fk`  WHERE j.`status`=2 AND j.`cust_fk`='$user_fk' GROUP BY j.id  order by j.id desc");
        $query1 = $query->result_array();
        return $query1;
    }

    function cancle_job($user_fk) {
        $query = $this->db->query("SELECT j.id,j.order_id,DATE_FORMAT((j.date), '%d %b %y %h:%i %p') AS date,GROUP_CONCAT(tm.`test_name`) test ,j.`price`,j.`status` FROM job_master j LEFT JOIN job_test_list_master t ON j.id=t.`job_fk` LEFT JOIN test_master tm ON tm.id=t.`test_fk`  WHERE j.`status`=4 AND j.`cust_fk`='$user_fk' GROUP BY t.`job_fk` ");
        $query1 = $query->result_array();
        return $query1;
    }

    function view_profile($user_fk) {
        $query = $this->db->query("SELECT c.id,CONCAT(UCASE(LEFT(full_name, 1)), 
                             SUBSTRING(full_name, 2)) as full_name,c.gender,c.email,c.mobile,c.address,c.pic,c.country,c.state,c.city,c.fbid,co.`country_name`,s.`state_name`,ci.`city_name`,re.refer_code,c.refer_price FROM customer_master c LEFT JOIN country co ON co.id=c.`country` LEFT JOIN state s ON s.id=c.`state` LEFT JOIN city ci ON ci.id=c.city LEFT JOIN refer_code_master re ON c.id=re.cust_fk WHERE c.`status`=1 AND c.id=$user_fk");
        $query1 = $query->result_array($user_fk);
        return $query1;
    }

    function doctor_view_profile($user_fk) {
        $query = $this->db->query("SELECT c.id,CONCAT(UCASE(LEFT(full_name, 1)), 
                             SUBSTRING(full_name, 2)) as full_name,c.gender,c.email,c.mobile,c.address,c.pic,c.state,c.city,s.`state_name`,ci.`city_name` FROM doctor_master c LEFT JOIN state s ON s.id=c.`state` LEFT JOIN city ci ON ci.id=c.city WHERE c.`status`=1 AND c.id=$user_fk");
        $query1 = $query->result_array($user_fk);
        return $query1;
    }

    public function total_wallet($user_fk) {
        $this->db->select_max('id');
        $result = $this->db->get_where('wallet_master', array('cust_fk' => $user_fk))->row();
        return $result->id;
    }

    function wallet_history($user_fk) {
        //	$query = $this->db->query("SELECT id,debit,credit, DATE_FORMAT(DATE(created_time) ,'%d %b %y') AS date FROM `wallet_master` where cust_fk='$user_fk' and status=1");
        $query = $this->db->query("SELECT 
  w.id,
  w.debit,
  w.credit,
  w.credit,pb.`order_id` AS package_order_id,w.type,
  DATE_FORMAT((w.created_time), '%d %b %y %h:%i %p') AS DATE, GROUP_CONCAT(t.`test_name`) testname,j.order_id,p.payomonyid

FROM
  `wallet_master` w 
  LEFT JOIN job_master j 
  ON j.`id`=w.`job_fk`
 LEFT JOIN job_test_list_master jt
  ON j.`id`=jt.`job_fk` LEFT JOIN test_master t ON t.id=jt.`test_fk` LEFT JOIN payment p ON p.id=w.payment_id LEFT JOIN book_package_master pb ON pb.id=w.`package_fk`
WHERE w.cust_fk ='$user_fk'
  AND w.status = 1 GROUP BY w.id order by w.id desc");
        $query1 = $query->result_array();
        return $query1;
    }

    function payment_history($user_fk) {
        //	$query = $this->db->query("SELECT id,debit,credit, DATE_FORMAT(DATE(created_time) ,'%d %b %y') AS date FROM `wallet_master` where cust_fk='$user_fk' and status=1");
        $query = $this->db->query("SELECT 
  p.* ,DATE_FORMAT((p.paydate), '%d %b %y %h:%i %p') AS DATE, GROUP_CONCAT(t.`test_name`) testname,j.`order_id`,pb.`order_id` package_order_id,title AS packagename

FROM
  `payment` p
  LEFT JOIN job_master j 
  ON j.`id`=p.`job_fk`
 LEFT JOIN job_test_list_master jt
  ON j.`id`=jt.`job_fk` LEFT JOIN test_master t ON t.id=jt.`test_fk` LEFT JOIN book_package_master pb ON pb.`id`=p.`package_fk`
  LEFT JOIN package_master pm ON pm.`id`=pb.`package_fk`
WHERE p.uid = '$user_fk' 
   GROUP BY p.id ORDER BY p.id DESC");
        $query1 = $query->result_array();
        return $query1;
    }

    function prescription_report($user_fk) {

        $query = $this->db->query("SELECT c.`full_name`,p.*,DATE_FORMAT((p.created_date), '%d %b %y %h:%i %p') as date   FROM `prescription_upload` p LEFT JOIN customer_master c ON c.id=p.`cust_fk` WHERE p.`cust_fk`='$user_fk' order by id desc");
        $query1 = $query->result_array();
        return $query1;
    }

    function suggest_test($pid) {

        /* $query = $this->db->query("SELECT s.*,t.id testid,t.test_name,t.price,t.description testdescription FROM `suggested_test` s LEFT JOIN test_master t ON t.id=s.`test_id`  WHERE s.p_id='$pid' AND s.status=1"); */
        $query = $this->db->query("SELECT s.*,t.id testid,t.test_name,`test_master_city_price`.`price`,t.description testdescription FROM `suggested_test` s 
LEFT JOIN test_master t ON t.id=s.`test_id` 
INNER JOIN `test_master_city_price` ON `test_master_city_price`.`test_fk`=`s`.`test_id`
 WHERE s.p_id='" . $pid . "' AND s.status=1");
        $query1 = $query->result_array();
        return $query1;
    }

    function health_line($uid) {
        $query = $this->db->query("SELECT id as id,image,text,type,DATE_FORMAT((created_date), '%d %b %Y %h:%i %p') AS date FROM health_line WHERE user_id='$uid' AND status=1 ");
        $query1 = $query->result_array();
        return $query1;
    }

    function package_list() {
        $query = $this->db->query("SELECT id,title,a_price,d_price,image,desc_app FROM package_master WHERE STATUS=1 ORDER BY title asc ");
        $query1 = $query->result_array();
        return $query1;
    }

    function health_feed() {
        $query = $this->db->query("SELECT *,DATE_FORMAT((created_date), '%d %b %Y %h:%i %p') AS date FROM health_feed_master WHERE status=1 order by id desc");
        $query1 = $query->result_array();
        return $query1;
    }

    function my_package($cust_fk) {
        $query = $this->db->query("SELECT p.*,DATE_FORMAT((pb.`date`), '%d %b %Y %h:%i %p') AS date FROM book_package_master pb LEFT JOIN package_master p ON p.id=pb.`package_fk` WHERE pb.`cust_fk`='$cust_fk' order by pb.id desc");
        $query1 = $query->result_array();
        return $query1;
    }

    function medical_id_view($cust_fk) {
        $query = $this->db->query("SELECT m.*,c.`full_name`,c.`gender`,c.`pic` FROM medical_id m LEFT JOIN customer_master c ON c.`id`=m.`cust_fk` WHERE m.`status`=1 and m.cust_fk='$cust_fk'");
        $query1 = $query->result_array();
        return $query1;
    }

    function search_doctor($name) {

        $query = $this->db->query("select * from doctor_master where full_name like '%$name%' and status=1");
        $query1 = $query->result_array();
        return $query1;
    }

    function view_package_report($job_fk) {
        $query = $this->db->query("SELECT 
      r.job_fk,
      r.test_fk,
      r.report,
      r.original,
      r.description,
      r.created_date,
      r.updated_date,
      t.title test_name,
      t.d_price price,
      j.status,
	  j.order_id,
      jm.id,jm.cust_fk,jm.package_fk,jm.date,jm.order_id,jm.type,jm.job_fk
    FROM `book_package_master` jm

      LEFT JOIN package_master t 
        ON t.id = jm.`package_fk`
      LEFT JOIN report_master r 
        ON  r.`job_fk`= jm.`job_fk`  LEFT JOIN job_master j ON j.id=jm.`job_fk`
    WHERE jm.`job_fk` = '$job_fk'");
        $query1 = $query->result_array();
        return $query1;
    }

    function view_test_report($jobfk) {

        //$query = $this->db->query("SELECT r.*,t.test_name,t.price FROM report_master r LEFT JOIN test_master t ON t.id=r.`test_fk` WHERE r.job_fk='$jobfk' AND r.status='1' ");
        /* $query = $this->db->query("SELECT 
          r.*,
          t.test_name,
          t.price,
          jm.*
          FROM `job_test_list_master` jm

          LEFT JOIN test_master t
          ON t.id = jm.`test_fk`
          LEFT JOIN report_master r
          ON jm.`test_fk` = r.`test_fk`  AND  r.`job_fk`= jm.`job_fk`

          WHERE jm.`job_fk` = '$jobfk'
          "); */
        /* Nishit change start */
        $query = $this->db->query("SELECT 
      r.job_fk,
      r.test_fk,
      r.report,
      r.original,
      r.description,
      r.created_date,
      r.updated_date,
      t.test_name,
      t.price,
      j.status,
	  j.order_id,
      jm.* 
    FROM `job_test_list_master` jm

      LEFT JOIN test_master t 
        ON t.id = jm.`test_fk`
      LEFT JOIN report_master r 
        ON jm.`test_fk` = r.`test_fk`  AND  r.`job_fk`= jm.`job_fk`  LEFT JOIN job_master j ON j.id=jm.`job_fk`

    WHERE jm.`job_fk` = '$jobfk' 
");
        /* Nishit chnage end */
        $query1 = $query->result_array();
        return $query1;
    }

    function doctor_customer_job($doctor) {
        $query = $this->db->query("SELECT 
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
WHERE j.`status` IN (1, 2, 3, 4) 
  AND j.`doctor` = '$doctor' 
GROUP BY t.job_fk 
ORDER BY j.id DESC ");
        $query1 = $query->result_array();
        //	echo $this->db->last_query();;
        return $query1;
    }

    function doctor_customer_job2($doctor, $from = null, $to = null, $type = null) {

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
WHERE j.`status` !='0' 
  AND j.`doctor` = '$doctor' ";
        if ($from != null) {
            $sql = $sql . " AND DATE(j.`date`) BETWEEN '$from 00:00:00' AND '$to 23:59:59' ";
        }
        if ($type != null) {
            if ($type == 0) {
                $sql = $sql . " AND (j.`report_status` IS NULL OR j.`report_status`='')  ";
            } else {
                $sql = $sql . " AND j.`report_status`= $type ";
            }
        }
        $sql = $sql . " 
GROUP BY j.id 
ORDER BY j.id DESC ";
        $query = $this->db->query($sql);
        $query1 = $query->result_array();
        //	echo $this->db->last_query();;
        return $query1;
    }

    function last_7_doctor_customer_job($doctor) {
        $query = $this->db->query("SELECT 
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
WHERE j.`status` IN (1, 2, 3, 4) 
  AND j.`doctor` = '$doctor' AND  j.date >= DATE(NOW()) - INTERVAL 4 DAY
GROUP BY t.job_fk 
ORDER BY j.id DESC");
        $query1 = $query->result_array();
        return $query1;
    }

    function today_doctor_customer_job($doctor) {
        /* $query = $this->db->query("SELECT 
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
          WHERE j.`status` IN (1, 2, 3, 4)
          AND j.`doctor` = '$doctor' AND  DATE(j.date) = CURDATE()
          GROUP BY t.job_fk
          ORDER BY j.id DESC ");
         */
        $query = $this->db->query("SELECT 
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
WHERE j.`doctor` = '$doctor' AND  DATE(j.date) = CURDATE()
GROUP BY t.job_fk 
ORDER BY j.id DESC ");
        $query1 = $query->result_array();
        return $query1;
    }

    function doctor_view_report($jobfk) {
        $query = $this->db->query("SELECT 
      r.job_fk,
      r.test_fk,
      r.report,
      r.original,
      r.description,
      r.created_date,
      r.updated_date,
      t.test_name,
      t.price,
      j.status,
	  j.order_id,
      jm.* 
    FROM `job_test_list_master` jm

      LEFT JOIN test_master t 
        ON t.id = jm.`test_fk`
      LEFT JOIN report_master r 
        ON jm.`test_fk` = r.`test_fk`  AND  r.`job_fk`= jm.`job_fk`  LEFT JOIN job_master j ON j.id=jm.`job_fk`

    WHERE jm.`job_fk` = '$jobfk' 
");

        $query1 = $query->result_array();
        //echo $this->db->last_query();
        return $query1;
    }

    function doctor_get_year_month_count($did) {
        $query = $this->db->query("SELECT 
	DISTINCT (DATE_FORMAT((j.date), '%b %Y')) AS yearmotnth,
  COUNT(j.id) AS total, DATE_FORMAT((j.date), '%c') AS MONTH,DATE_FORMAT((j.date), '%Y') AS YEAR
FROM
  job_master j
WHERE j.`status` IN (1, 2, 3, 4) 
  AND j.`doctor` = '$did'
GROUP BY DATE_FORMAT((j.date), '%b %Y')
ORDER BY j.id DESC");
        $query1 = $query->result_array();
        return $query1;
    }

    function doctor_stat($drid, $month, $year) {
        $query = $this->db->query("SELECT 
  j.id,
  j.order_id,
  DATE_FORMAT((j.date), '%d %b %y %h:%i %p') AS DATE,
  GROUP_CONCAT(tm.`test_name`) test,
  j.`price`,
  j.`status`,
  IF(b.`family_member_fk`=0,c.`full_name`,cf.name) AS full_name,
  c.`mobile`,
  c.`gender`,
  j.`doctor`,
  j.date 
FROM
  job_master j 
  LEFT JOIN job_test_list_master t 
    ON j.id = t.`job_fk` 
  LEFT JOIN test_master tm 
    ON tm.id = t.`test_fk` 
  LEFT JOIN customer_master c 
    ON c.id = j.`cust_fk` 
    LEFT JOIN `booking_info`  b
    ON b.id=j.`booking_info`
    LEFT JOIN `customer_family_master` cf
    ON b.`family_member_fk`=cf.`id`
WHERE j.`status` IN (1,2,3,4,6,7,8)
  AND j.`doctor` = '$drid' AND j.date >= '$year-$month-01' 
AND j.date <= '$year-$month-31'
GROUP BY t.job_fk 
ORDER BY j.id DESC");
        $query1 = $query->result_array();
        return $query1;
    }
    function doctor_stat1($drid, $month, $year) {
        $query = $this->db->query("SELECT 
  j.id,
  j.order_id,
  DATE_FORMAT((j.date), '%d %b %y %h:%i %p') AS DATE,
    CONCAT_WS('-', GROUP_CONCAT(tm.`test_name`), GROUP_CONCAT(pm.title)) AS test ,
  j.`price`,
  j.`status`,
  IF(b.`family_member_fk`=0,c.`full_name`,cf.name) AS full_name,
  c.`mobile`,
  c.`gender`,
  j.`doctor`,
  j.date ,
  bp.id AS bid,
  GROUP_CONCAT(pm.title) AS package
FROM
  job_master j 
  LEFT JOIN job_test_list_master t 
    ON j.id = t.`job_fk` 
  LEFT JOIN test_master tm 
    ON tm.id = t.`test_fk` 
  LEFT JOIN customer_master c 
    ON c.id = j.`cust_fk` 
    LEFT JOIN `booking_info`  b
    ON b.id=j.`booking_info`
    LEFT JOIN `customer_family_master` cf
    ON b.`family_member_fk`=cf.`id`
    LEFT JOIN `book_package_master` bp
    ON bp.job_fk=j.id
    LEFT JOIN `package_master` pm
    ON pm.id=bp.package_fk
WHERE j.`status` IN (1,2,3,4,6,7,8)
  AND j.`doctor` = '$drid' AND j.date >= '$year-$month-01' 
AND j.date <= '$year-$month-31'
GROUP BY j.id 
ORDER BY j.id DESC");
        $query1 = $query->result_array();
        return $query1;
    }
    function doctor_date_vise_report($did, $from, $to) {
        $query = $this->db->query("SELECT 
  j.id,
  j.order_id,
  DATE_FORMAT((j.date), '%d %b %y %h:%i %p') AS DATE,
  GROUP_CONCAT(tm.`test_name`) test,
  j.`price`,
  j.`status`,
  c.`full_name`,
  c.`mobile`,
  c.`gender`,
  j.`doctor`,
  j.date 
FROM
  job_master j 
  LEFT JOIN job_test_list_master t 
    ON j.id = t.`job_fk` 
  LEFT JOIN test_master tm 
    ON tm.id = t.`test_fk` 
  LEFT JOIN customer_master c 
    ON c.id = j.`cust_fk` 
WHERE j.`status` IN (1, 2, 3, 4) 
  AND j.`doctor` = '$did' 
 AND j.date BETWEEN  STR_TO_DATE('$from','%d-%m-%Y') AND STR_TO_DATE('$to','%d-%m-%Y')
GROUP BY t.job_fk 
ORDER BY j.id DESC ");
        $query1 = $query->result_array();
        return $query1;
    }

    function doctor_date_vise_report1($doctor = null, $from = null, $to = null, $type = null) {

        $sql = "SELECT 
  j.id,
  j.order_id,
  DATE_FORMAT((j.date), '%d %b %y %h:%i %p') AS DATE,
    CONCAT_WS('-', GROUP_CONCAT(tm.`test_name`), GROUP_CONCAT(pm.title)) AS test ,
  j.`price`,
  j.`status`,
  IF(b.`family_member_fk`=0,c.`full_name`,cf.name) AS full_name,
  c.`mobile`,
  c.`gender`,
  j.`doctor`,
  j.date ,
  bp.id AS bid,
  GROUP_CONCAT(pm.title) AS package
FROM
  job_master j 
  LEFT JOIN job_test_list_master t 
    ON j.id = t.`job_fk` 
  LEFT JOIN test_master tm 
    ON tm.id = t.`test_fk` 
  LEFT JOIN customer_master c 
    ON c.id = j.`cust_fk` 
    LEFT JOIN `booking_info`  b
    ON b.id=j.`booking_info`
    LEFT JOIN `customer_family_master` cf
    ON b.`family_member_fk`=cf.`id`
    LEFT JOIN `book_package_master` bp
    ON bp.job_fk=j.id
    LEFT JOIN `package_master` pm
    ON pm.id=bp.package_fk
WHERE j.`status` !='0' 
  AND j.`doctor` = '$doctor' ";
        if ($from != null) {
            $sql = $sql . " AND DATE(j.`date`) BETWEEN '$from 00:00:00' AND '$to 23:59:59' ";
        }
        if ($type != null) {
            if ($type == 0) {
                $sql = $sql . " AND (j.`report_status` IS NULL OR j.`report_status`='')  ";
            } else {
                $sql = $sql . " AND j.`report_status`= $type ";
            }
        }
        $sql = $sql . " 
GROUP BY j.id 
ORDER BY j.id DESC ";
        if($_GET['debug']==1){
            echo $sql; die();
        }
        $query = $this->db->query($sql);
        $query1 = $query->result_array();
        //	echo $this->db->last_query();;
        return $query1;
    }

    function creative_show() {
        $query = $this->db->query("SELECT  a.*,DATE_FORMAT((a.`created_date`), '%d %b %Y %h:%i %p') AS date,
        GROUP_CONCAT(b.test_name ORDER BY b.id) testname
FROM    creative_master a
        INNER JOIN test_master b
            ON FIND_IN_SET(b.id, a.test) > 0
GROUP   BY a.id");
        $query1 = $query->result_array();
        return $query1;
    }

    function get_result($qry) {
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }

    function get_job_test_name($id) {
        $query = $this->db->query("SELECT `job_test_list_master`.*,`test_master`.`test_name` FROM `job_test_list_master` INNER JOIN `test_master` ON `job_test_list_master`.`test_fk`=`test_master`.`id` WHERE `job_test_list_master`.`job_fk`='" . $id . "'");
        $query1 = $query->result_array();
        return $query1;
    }

    /* Nishit Phlebo start */

    function phlebo_time_slot_on_day($id, $date) {
        $query = $this->db->query("SELECT 
  `phlebo_calender`.*,
  `phlebo_day_master`.`date_no`,
  `phlebo_day_master`.`name`,
  TIME_FORMAT(
    `phlebo_time_slot`.`start_time`,
    '%l:%i %p'
  ) AS `start_time`,
  TIME_FORMAT(
    `phlebo_time_slot`.`end_time`,
    '%l:%i %p'
  ) AS `end_time`,
  `phlebo_time_slot`.`start_time` AS real_time 
FROM
  `phlebo_calender` 
  INNER JOIN `phlebo_day_master` 
    ON `phlebo_calender`.`day_fk` = `phlebo_day_master`.`id` 
  INNER JOIN `phlebo_time_slot` 
    ON `phlebo_calender`.`time_slot_fk` = `phlebo_time_slot`.`id` 
WHERE `phlebo_calender`.`status` = '1' 
  AND `phlebo_day_master`.`status` = '1' 
  AND `phlebo_time_slot`.`status` = '1' 
  AND `phlebo_day_master`.`date_no` = '" . $id . "'");
        $query1 = $query->result_array();
        $get_time = date("H:i:s");
        $new_ary = array();
        foreach ($query1 as $key) {
            if ($date == date("m/d/Y")) {
                if ($get_time < $key["real_time"]) {
                    $new_ary[] = $key;
                }
            } else {
                $new_ary[] = $key;
            }
        }
        return $new_ary;
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

    function get_random_phlebo($booking_info) {
        $query = "select * from phlebo_assign_job where time_fk='" . $booking_info["time_slot_fk"] . "' AND date='" . $booking_info["date"] . "' AND status=1";
        $query = $this->db->query($query);
        $get_booked_details = $query->result_array();
        if (!empty($get_booked_details)) {
            $assign_phlebo = array();
            foreach ($get_booked_details as $key) {
                $assign_phlebo[] = $key["phlebo_fk"];
            }
            $query = "select * from phlebo_master where id not in(" . implode(",", $assign_phlebo) . ") AND status='1' ORDER by rand() LIMIT 1";
            $query = $this->db->query($query);
            $query = $query->result_array();
        } else {
            $query = "select * from phlebo_master where status='1' ORDER by rand() LIMIT 1";
            $query = $this->db->query($query);
            $query = $query->result_array();
        }
        return $query;
    }

    function get_val($query1 = null) {
        $query = $this->db->query($query1);
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    /* Nishit Phlebo end */

    function get_user_family_member_data($uid) {
        $query = $this->db->query("SELECT 
  `customer_family_master`.*,
  `relation_master`.`name` AS relation_name 
FROM
  `customer_family_master` 
  INNER JOIN `relation_master` 
    ON `customer_family_master`.`relation_fk` = `relation_master`.`id` 
WHERE `customer_family_master`.`status` = '1' 
  AND `relation_master`.`status` = '1' 
  AND `customer_family_master`.`id` = '" . $uid . "'");
        $query1 = $query->result_array();
        return $query1;
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

    function get_today_dashbaord($uid) {
        $query = $this->db->query("SELECT 
  (SELECT     COUNT(job_master.id) FROM    `job_master`   WHERE doctor = doctor_master.id   AND job_master.`date`>='" . date("Y-m-d") . " 00:00:00' AND job_master.`date`<='" . date("Y-m-d") . " 23:59:59' AND status!='0' AND `report_status` = 1)AS critical ,       
  (SELECT     COUNT(job_master.id) FROM    `job_master`   WHERE doctor = doctor_master.id  AND job_master.`date`>='" . date("Y-m-d") . " 00:00:00' AND job_master.`date`<='" . date("Y-m-d") . " 23:59:59' AND status!='0'  AND `report_status` = 2)AS semicritical ,       
  (SELECT     COUNT(job_master.id) FROM    `job_master`   WHERE doctor = doctor_master.id  AND job_master.`date`>='" . date("Y-m-d") . " 00:00:00' AND job_master.`date`<='" . date("Y-m-d") . " 23:59:59' AND status!='0' AND `report_status` = 3)AS normal,       
  (SELECT     COUNT(job_master.id) FROM    `job_master`   WHERE doctor = doctor_master.id  AND job_master.`date`>='" . date("Y-m-d") . " 00:00:00' AND job_master.`date`<='" . date("Y-m-d") . " 23:59:59' AND status!='0' )AS total,
       (SELECT     SUM(job_master.price) FROM    `job_master`   WHERE doctor = doctor_master.id  AND job_master.`date`>='" . date("Y-m-d") . " 00:00:00' AND job_master.`date`<='" . date("Y-m-d") . " 23:59:59' AND status!='0' )AS total_amount
    FROM `doctor_master` WHERE id='" . $uid . "'");
        $query1 = $query->result_array();
        return $query1;
    }

    function get_yesterday_dashbaord($uid) {
        $query = $this->db->query("SELECT 
  (SELECT     COUNT(job_master.id) FROM    `job_master`   WHERE doctor = doctor_master.id   AND job_master.`date`>='" . date("Y-m-d", strtotime("-1 days")) . " 00:00:00' AND job_master.`date`<='" . date("Y-m-d") . " 23:59:59' AND status!='0' AND `report_status` = 1)AS critical ,       
  (SELECT     COUNT(job_master.id) FROM    `job_master`   WHERE doctor = doctor_master.id  AND job_master.`date`>='" . date("Y-m-d", strtotime("-1 days")) . " 00:00:00' AND job_master.`date`<='" . date("Y-m-d") . " 23:59:59' AND status!='0'  AND `report_status` = 2)AS semicritical ,       
  (SELECT     COUNT(job_master.id) FROM    `job_master`   WHERE doctor = doctor_master.id  AND job_master.`date`>='" . date("Y-m-d", strtotime("-1 days")) . " 00:00:00' AND job_master.`date`<='" . date("Y-m-d") . " 23:59:59' AND status!='0' AND `report_status` = 3)AS normal,       
  (SELECT     COUNT(job_master.id) FROM    `job_master`   WHERE doctor = doctor_master.id  AND DATE(job_master.`date`) BETWEEN CURDATE() - INTERVAL 1 DAY AND CURDATE()   )AS total,
       (SELECT     SUM(job_master.price) FROM    `job_master`   WHERE doctor = doctor_master.id  AND job_master.`date`>='" . date("Y-m-d", strtotime("-1 days")) . " 00:00:00' AND job_master.`date`<='" . date("Y-m-d") . " 23:59:59' AND status!='0' )AS total_amount
    FROM `doctor_master` WHERE id='" . $uid . "'");
        $query1 = $query->result_array();
        //echo $this->db->last_query();
        return $query1;
    }

}

?>
