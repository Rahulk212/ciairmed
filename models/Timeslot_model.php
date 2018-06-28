<?php

Class Timeslot_model extends CI_Model {

    public function master_get_where_condtion($table, $cid, $order) {
        $this->db->order_by($order[0], $order[1]);
        $query = $this->db->get_where($table, $cid);
        return $query->result_array();
    }

    public function master_tbl_update($tablename, $cid, $data) {
        $this->db->where(array('id' => $cid));
        $this->db->update($tablename, $data);
        return 1;
    }

    public function master_get_list() {
        $this->db->order_by("start_time", "asc ");
        $query = $this->db->get_where("phlebo_time_slot", array("status" => "1"));
        return $query->result_array();
    }

    public function master_get_view($id) {
        $query = "SELECT ts.* from `phlebo_time_slot` as ts where ts.status = 1 AND ts.id = '" . $id . "' ORDER BY ts.id DESC";
        $query = $this->db->query($query);
        return $query->result_array();
    }

    public function num_row() {
        $query = "SELECT ts.* from `phlebo_time_slot` as ts where ts.status = 1  ORDER BY ts.id DESC";
        $query = $this->db->query($query);
        return $query->num_rows();
    }

    public function master_get_insert($table, $data) {
        $this->db->insert($table, $data);
        return true;
        return $this->db->insert_id();
    }

}
