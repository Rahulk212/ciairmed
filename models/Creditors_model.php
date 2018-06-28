<?php

Class Creditors_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function get_one($dtatabase, $condition) {
        $query = $this->db->get_where($dtatabase, $condition);
        $result = $query->row();
        return $result;
    }

    public function get_all($dtatabase, $condition) {
        $query = $this->db->get_where($dtatabase, $condition);
        $result = $query->result();
        return $result;
    }

    public function update($tablename, $cid, $data) {
        $this->db->where($cid);
        $this->db->update($tablename, $data);
        return 1;
    }

    public function check_email($email, $table) {
        $this->db->select('*');
        $this->db->from($table);
        $this->db->where('email', $email);
        $this->db->where('status', '1');
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function insert($table, $data) {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function creditors_srch($name, $email, $mobile, $limit, $start) {

        $query = "SELECT *  FROM `creditors_master` where `status` = '1'";

        if ($name != "") {
            $query .= " AND name  LIKE '%" . $name . "%'";
        }
        if ($email != "") {
            $query .= " AND email LIKE '%" . $email . "%'";
        }
        if ($mobile != "") {
            $query .= " AND mobile LIKE '%" . $mobile . "%'";
        }

        $query .= " ORDER BY id DESC LIMIT $start , $limit";
        $result = $this->db->query($query)->result();
        return $result;
    }

    public function creditors_row_num($name, $email, $mobile) {
        $query = "SELECT *  FROM `creditors_master` where `status` = '1'";

        if ($name != "") {
            $query .= " AND name  LIKE '%" . $name . "%'";
        }
        if ($email != "") {
            $query .= " AND email LIKE '%" . $email . "%'";
        }
        if ($mobile != "") {
            $query .= " AND mobile LIKE '%" . $mobile . "%'";
        }

        $query .= " ORDER BY id DESC";
        $result = $this->db->query($query)->num_rows();
        return $result;
    }

    public function search_num() {

        $this->db->select('up.*,cm.name');
        $this->db->from('user_profile up');
        $this->db->join('customer_master cm', 'cm.id = up.user_fk', 'left');
        //$this->db->where('cm.id', $cid);
        $this->db->where('cm.login_type', '3');
        $this->db->where('cm.status', '1');

        $query = $this->db->get();

        return $query->num_rows();
    }

    public function view_search($id) {

        $this->db->select('cm.id as cid,cm.*,ujp.*,cat.name as category,com.name as country_name,sub.name as subc,GROUP_CONCAT(s.name) as skillname,GROUP_CONCAT(s.pr_name) as skillprname');
        $this->db->from('customer_master as cm');
        $this->db->join('user_job_post ujp', 'cm.id = ujp.user_fk', 'left');
        $this->db->join('user_send_proposal usp', 'usp.user_fk = cm.id', 'left');
        $this->db->join('approve_proposal ap', 'ap.freelancer_fk = usp.user_fk', 'left');
        $this->db->join('country_master as com', 'com.id = cm.country', 'left');
        $this->db->join('category_master as cat', 'cat.id = ujp.category_fk', 'left');
        $this->db->join('subcategory_master as sub', 'sub.id = ujp.subcategory_fk', 'left');
        $this->db->join('skill_master s', 'FIND_IN_SET(s.id, ujp.skills)', 'left');
        $this->db->where('cm.status', '1');
        $this->db->where('cm.active', '1');
        $this->db->where('cm.login_type', '3');
        $this->db->where('cm.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function job_view_details($id, $one, $two) {

        $this->db->select('ap.status as approve_status,ujp.job_posting,ap.created_date,ap.updated_date,cm.name as freelancer_name,cc.name as client_name,cc.login_type,cc.type,cc.social,usp.rate,usp.type_rate,s.name as skillname,s.pr_name as skillprname');
        $this->db->from('approve_proposal ap');
        $this->db->join('user_send_proposal usp', 'ap.job_posting_fk = usp.job_posting_fk ', 'left');
        $this->db->join('user_job_post ujp', 'ujp.id = ap.job_posting_fk', 'left');
        $this->db->join('customer_master as cm', 'cm.id = ap.freelancer_fk', 'left');
        $this->db->join('customer_master as cc', 'cc.id = ap.client_fk', 'left');
        $this->db->join('skill_master s', 'FIND_IN_SET(s.id, ujp.skills)', 'left');
        $this->db->where('ap.status!=', '0');
        $this->db->where('ap.freelancer_fk', $id);
        $this->db->GROUP_BY('usp.job_posting_fk');
        $this->db->GROUP_BY('ap.job_posting_fk');


        $this->db->LIMIT($one, $two);
        $query = $this->db->get();
        return $query->result();
        /*  $query->result();
          echo "<pre>"; print_r($query); die(); */
    }

    public function job_view_num_row($id) {

        $this->db->select('ap.status as approve_status,ujp.job_posting,ap.created_date,ap.updated_date,cm.name as freelancer_name,cc.name as client_name,usp.rate,usp.type_rate,s.name as skillname,s.pr_name as skillprname');
        $this->db->from('approve_proposal ap');
        $this->db->join('user_send_proposal usp', 'ap.job_posting_fk = usp.job_posting_fk ', 'left');
        $this->db->join('user_job_post ujp', 'ujp.id = ap.job_posting_fk', 'left');
        $this->db->join('customer_master as cm', 'cm.id = ap.freelancer_fk', 'left');
        $this->db->join('customer_master as cc', 'cc.id = ap.client_fk', 'left');
        $this->db->join('skill_master s', 'FIND_IN_SET(s.id, ujp.skills)', 'left');
        $this->db->where('ap.status!=', '0');
        $this->db->where('ap.freelancer_fk', $id);
        $this->db->GROUP_BY('usp.job_posting_fk');
        $this->db->GROUP_BY('ap.job_posting_fk');
        $query = $this->db->get();
        return $query->num_rows();
        //echo "<pre>"; print_r($query); die();
    }

    // 26-05-2017 Dharmik Changes
    public function view_login_details($id) {

        $this->db->select('cm.id,cm.name,cm.password,cm.email,cm.password,cm.login_type,cm.type');
        $this->db->from('customer_master cm');
        $this->db->where('cm.status ', '1');
        $this->db->where('cm.id', $id);
        $this->db->LIMIT(1);
        $query = $this->db->get();
        return $query->row();
        /*  $query->row();
          echo "<pre>"; print_r($query); die(); */
    }

    function checklogin($cid, $username, $password) {
        $this->db->select('cm.*,up.profile_image');
        $this->db->from('customer_master as cm');
        $this->db->join('user_profile as up', 'cm.id = up.user_fk', 'left');
        $this->db->where('cm.email', $username);
        $this->db->where('cm.password', $password);
        $this->db->where('cm.id', $cid);
        $this->db->where('cm.status', '1');
        $this->db->where('type!=', '4');
        $this->db->where('cm.active', '1');

        $this->db->limit(1);
        $query = $this->db->get();
        // print_R($query);
        if ($query->num_rows() == 1) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function edit_creditors($cid) {

        $this->db->select('cb.branch_fk as branch,cm.*');
        $this->db->from('creditors_master cm');
        $this->db->join('creditors_branch cb', 'cb.creditors_fk = cm.id');
        $this->db->where('cb.status', '1');
        $this->db->where('cm.status', '1');
        $this->db->where('cm.id', $cid);
        $query = $this->db->get();
        return $query->row();
    }

    function delete_record($tbl, $data) {
        $this->db->where($data);
        $this->db->delete($tbl);
    }

}
