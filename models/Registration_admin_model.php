<?php
Class Registration_admin_model extends CI_Model {
    public function master_fun_update($tablename, $cid, $data) {
        $this->db->where($cid[0], $cid[1]);
        $this->db->update($tablename, $data);
        return 1;
    }

    public function master_fun_get_tbl_val($dtatabase, $condition, $order) {
        $this->db->order_by($order[0], $order[1]);
        $query = $this->db->get_where($dtatabase, $condition);
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    function get_val($query1 = null) {
        $query = $this->db->query($query1);
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    public function master_fun_insert($table, $data) {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function master_num_rows($table, $condition) {
        $query1 = $this->db->get_where($table, $condition);
        return $query1->num_rows();
    }

    public function all_inquiry_count() {
        $query = "select count(*) total from book_without_login where status='1'";
        $query = $this->db->query($query);
        $data['user'] = $query->row();
        return $data['user'];
    }

    public function instant_contact_count() {
        $query = "select count(*) total from instant_contact where status='1'";
        $query = $this->db->query($query);
        $data['user'] = $query->row();
        return $data['user'];
    }

    public function pending_job_count() {
        $query = "select count(*) total from job_master where status='1'";
        $query = $this->db->query($query);
        $data['user'] = $query->row();
        return $data['user'];
    }

}