<?php

Class Unit_model extends CI_Model {

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
        $this->db->select('ID,PARAMETER_CODE,PARAMETER_NAME');
        if ($fullname != "") {
            $this->db->like('PARAMETER_NAME', $fullname);
        }
        $this->db->where('status', '1');
        $this->db->where('PARAMETER_CODE', 'UOM');

        $this->db->ORDER_BY('ID', 'desc');
        $this->db->limit($one, $two);
        $query = $this->db->get('CTMS_PARAMETER_MST');


        return $query->result_array();
    }

    public function num_row($fullname) {

        $this->db->select('ID,PARAMETER_CODE,PARAMETER_NAME');
        if ($fullname != "") {
            $this->db->like('PARAMETER_NAME', $fullname);
        }
        $this->db->where('status', '1');
        $this->db->where('PARAMETER_CODE', 'UOM');
        $this->db->ORDER_BY('ID', 'desc');
        $query = $this->db->get('CTMS_PARAMETER_MST');


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
