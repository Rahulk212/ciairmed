<?php

Class Justdial_model extends CI_Model {

    public function list_city() {
        $query = $this->db->query("SELECT c.id as cid,c.city_name FROM city c WHERE status = 1 ");
        return $query->result_array();
    }

    public function save($data) {
        $this->db->insert("just_dial_data", $data);
        return $this->db->insert_id();
    }

    public function find_data($condition) {
        $query = $this->db->get_where("just_dial_data", $condition);
        $data['user'] = $query->result_array();
        return $data['user'];
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
}
?>