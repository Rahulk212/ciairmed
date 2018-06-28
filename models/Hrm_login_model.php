<?php
Class Hrm_login_model extends CI_Model {

    function checklogin($username, $password) {
        $this->db->select('*');
        $this->db->from('hrm_admin_master');
        $this->db->where('email', $username);
        $this->db->where('password', $password);
        $this->db->where('status', '1');
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }
        function lab_checklogin($username, $password) {
        $this->db->select('*');
        $this->db->from('collect_from');
        $this->db->where('email', $username);
        $this->db->where('password', $password);
        $this->db->where('status', '1');
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }
    public function login_time($did, $data) {
        $this->db->where('id', $did);
        $this->db->update('hrm_admin_master', $data);
        return 1;
    }
    public function master_fun_get_tbl_val($dtatabase, $condition, $order) {
        $this->db->order_by($order[0], $order[1]);
        $query = $this->db->get_where($dtatabase, $condition);
        $data['user'] = $query->result_array();
        return $data['user'];
    } 
    function master_fun_insert($table, $data) {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }
    public function master_fun_update($tablename, $cid, $data) {
        $this->db->where($cid[0], $cid[1]);
        $this->db->update($tablename, $data);
        return 1;
    }
    public function master_num_rows($table, $condition) {
        $query1 = $this->db->get_where($table, $condition);
        return $query1->num_rows();
    }
}

?>