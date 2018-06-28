<?php

class Lead_manage_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function insert($data) {

        $this->db->insert('lead_manage_master', $data);
        return true;
        return $this->db->insert_id();
    }

    public function record_count($sname, $semail, $smobile) {
        $this->db->select('*');
        if ($sname != '') {
            $this->db->like('name', $sname);
        }
        if ($semail != '') {
            $this->db->like('email', $semail);
        }
        if ($smobile != '') {
            $this->db->like('mobile_no', $smobile);
        }
        $this->db->where('status', '1');
        $this->db->ORDER_BY('id', 'desc');
        $query = $this->db->get('lead_manage_master');

        return $query->num_rows();
    }

    public function list_lead_manage($sname, $semail, $smobile, $limit, $id) {
        $this->db->select('a.*,c.mobile');
        $this->db->from('lead_manage_master as a');
        $this->db->join('customer_master as c', 'c.mobile = a.mobile_no', 'left');


        if ($sname != '') {
            $this->db->like('a.name', $sname);
        }
        if ($semail != '') {
            $this->db->like('a.email', $semail);
        }
        if ($smobile != '') {
            $this->db->like('a.mobile_no', $smobile);
        }
        $this->db->where('a.status', '1');
        $this->db->limit($limit, $id);
        $this->db->order_by("a.id", "desc");
        $query = $this->db->get();
        return $query->result();
        /* $query->result();
          echo"<pre>"; print_r($query); die(); */
    }

    public function update($lmid, $data) {
        $this->db->set($data);
        $this->db->where("id", $lmid);
        $this->db->update("lead_manage_master", $data);
        return true;
    }

    public function updates($tablename, $cid, $data) {
        $this->db->where($cid);
        $this->db->update($tablename, $data);
        return 1;
    }

    public function get_lm($lmid) {
        $query = $this->db->get_where('lead_manage_master', array('id' => $lmid));
        $data = $query->row();
        //print_r($data);exit;
        return $data;
    }

    public function delete_lm($lmid, $data) {
        $this->db->where(array('id' => $lmid));
        $this->db->update('lead_manage_master', $data);
        return true;
    }

    public function prescription_report() {
        $name = $_GET['name'];
        $email = $_GET['email'];
        $mobile = $_GET['mobile'];
        if ($name != "") {
            $query = "SELECT `a`.*, `c`.`mobile` FROM `lead_manage_master` as `a` LEFT JOIN `customer_master` as `c`
							ON `c`.`mobile` = `a`.`mobile_no` 
							WHERE `name` LIKE '%" . $name . "%' AND `a`.`status` = '1' ORDER BY `a`.`id` DESC LIMIT 5";
        } else if ($email != "") {
            $query = "SELECT `a`.*, `c`.`mobile` FROM `lead_manage_master` as `a` LEFT JOIN `customer_master` as `c`
							ON `c`.`mobile` = `a`.`mobile_no` 
							WHERE `email` LIKE '%" . $email . "%' AND `a`.`status` = '1' ORDER BY `a`.`id` DESC LIMIT 5";
        } else if ($mobile != "") {
            $query = "SELECT `a`.*, `c`.`mobile` FROM `lead_manage_master` as `a` LEFT JOIN `customer_master` as `c`
							ON `c`.`mobile` = `a`.`mobile_no` 
							WHERE `mobile` LIKE '%" . $mobile . "%'  OR `a`.`status` = '1' ORDER BY `a`.`id` DESC LIMIT 5";
        } else if ($name != "" && $email != "") {
            $query = "SELECT `a`.*, `c`.`mobile` FROM `lead_manage_master` as `a` LEFT JOIN `customer_master` as `c`
							ON `c`.`mobile` = `a`.`mobile_no` 
							WHERE `name` LIKE '%" . $name . "%'  AND 
								  `email` LIKE '%" . $email . "%'  AND 
								  `status` = '1' ORDER BY `a`.`id` DESC LIMIT 5";
        } else if ($name != "" && $mobile != "") {
            $query = "SELECT `a`.*, `c`.`mobile` FROM `lead_manage_master` as `a` LEFT JOIN `customer_master` as `c`
							ON `c`.`mobile` = `a`.`mobile_no` 
							WHERE `name` LIKE '%" . $name . "%'  AND
								  `mobile_no` LIKE '%" . $mobile . "%' AND 
								  `status` = '1' ORDER BY `a`.`id` DESC LIMIT 5";
        } else if ($email != "" && $mobile != "") {
            $query = "SELECT `a`.*, `c`.`mobile` FROM `lead_manage_master` as `a` LEFT JOIN `customer_master` as `c`
							ON `c`.`mobile` = `a`.`mobile_no` 
							WHERE 
								  `email` LIKE '%" . $email . "%'  AND
								  `mobile_no` LIKE '%" . $mobile . "%' AND 
								  `status` = '1' ORDER BY `a`.`id` DESC LIMIT 5";
        } else if ($name != "" && $email != "" && $mobile != "") {
            $query = "SELECT `a`.*, `c`.`mobile` FROM `lead_manage_master` as `a` LEFT JOIN `customer_master` as `c`
							ON `c`.`mobile` = `a`.`mobile_no` 
							WHERE `name` LIKE '%" . $name . "%'  AND
								  `email` LIKE '%" . $email . "%'  AND 
								  `mobile_no` LIKE '%" . $mobile . "%' AND 
								  `status` = '1' ORDER BY `a`.`id` DESC LIMIT 5";
        } else {
            $query = "SELECT `a`.*, `c`.`mobile` FROM `lead_manage_master` as `a` LEFT JOIN `customer_master` as `c`
							ON `c`.`mobile` = `a`.`mobile_no` 
							WHERE `a`.`status` = '1' ORDER BY `a`.`id` DESC LIMIT 5";
        }
        return $result = $this->db->query($query)->result_array();
        // $result = $this->db->query($query)->result_array();
        // echo "<pre>";print_r($result); die();
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
        //echo $table; print_R($data); die();
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function master_fun_update($tablename, $cid, $data) {
        $this->db->where('id', $cid);
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

}

?>