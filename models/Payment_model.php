<?php

Class Payment_model extends CI_Model {

    public function master_get_update($tablename, $cid, $data) {
        $this->db->where(array('id' => $cid));
        $this->db->update($tablename, $data);
        return 1;
    }

    public function master_get_insert($table, $data) {
        $this->db->insert($table, $data);
        return true;
        return $this->db->insert_id();
    }

    public function master_get_table($table, $where, $order) {

        $this->db->order_by($order[0], $order[1]);
        $query = $this->db->get_where($table, $where);
        $data = $query->result_array();
        return $data;
    }

    public function master_get_search($fullname, $one, $two) {
        $this->db->select('id,name');
        if ($fullname != "") {
            $this->db->like('name', $fullname);
        }
        $this->db->where('status', '1');
        $this->db->ORDER_BY('id', 'desc');
        $this->db->limit($one, $two);
        $query = $this->db->get('payment_type_master');


        return $query->result_array();
    }

    public function num_row($fullname) {

        $this->db->select('id,name');
        if ($fullname != "") {
            $this->db->like('name', $fullname);
        }
        $this->db->where('status', '1');
        $this->db->ORDER_BY('id', 'desc');
        $query = $this->db->get('payment_type_master');
        return $query->num_rows();
    }

    public function master_get_where_condtion($table, $where, $order) {
        $this->db->order_by($order[0], $order[1]);
        $query = $this->db->get_where($table, $where);
        return $query->result_array();
    }

    public function master_get_spam($tablename, $cid, $data) {
        $this->db->where(array('id' => $cid));
        $this->db->update($tablename, $data);
        return 1;
    }

}

?>
