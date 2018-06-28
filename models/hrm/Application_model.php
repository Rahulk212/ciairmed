<?php

Class Application_model extends CI_Model {

    function search_num($search) {
        $temp = "";
        if ($search != "") {
            $temp = " AND title LIKE '%" . $search . "%' ";
        }
        $query = $this->db->query("SELECT * FROM `hrm_leave_application` where status ='1' and type='notice' $temp ");
        return $query->num_rows();
    }

    function list_search($search, $one, $two) {
        $temp = "";
        if ($search != "") {
            $temp = " AND la.title LIKE '%" . $search . "%' ";
        }
        $query = $this->db->query("SELECT la.*,e.name as employee_name,lt.leave as leave_type FROM `hrm_leave_application` la left join hrm_employees e on e.employee_id=la.employee_id left join hrm_leave_type lt on lt.id=la.type where la.status ='1' $temp  ORDER BY la.id desc LIMIT $two,$one");
        return $query->result();
    }

    public function insert($table, $data) {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
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

    public function master_fun_update($tablename, $cid, $data) {
        $this->db->where($cid[0], $cid[1]);
        $this->db->update($tablename, $data);
        return 1;
    }

    public function fetchdatarow($selact, $table, $array) {
        $this->db->select($selact);
        $query = $this->db->get_where($table, $array);
        return $query->row();
    }

    public function num_row($table, $condition) {
        $query = $this->db->get_where($table, $condition);
        return $query->num_rows();
    }

}

?>