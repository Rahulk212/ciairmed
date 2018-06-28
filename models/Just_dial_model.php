<?php

Class Just_dial_model extends CI_Model {

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

    public function master_fun_update($tablename, $cid, $data) {
        $this->db->where($cid[0], $cid[1]);
        $this->db->update($tablename, $data);
        return 1;
    }

    public function phlebocount_list($srch) {
        $temp = "";
        if ($srch['name'] != "") {
            $name = $srch['name'];
            $temp .= " AND name LIKE '%$name%' ";
        }
        if ($srch['email'] != "") {
            $name = $srch['email'];
            $temp .= " AND email LIKE '%$name%' ";
        }
        if ($srch['mobile'] != "") {
            $name = $srch['mobile'];
            $temp .= " AND phone LIKE '%$name%' ";
        }
        if ($srch['gender'] != "") {
            $name = $srch['gender'];
            $temp .= " AND gender = '$name' ";
        }
        $query = $this->db->query("SELECT * from just_dial_data where status = '1' $temp order by id desc");
        return $query->num_rows();
    }

    public function phlebolist_list($srch, $one, $two) {
        $temp = "";
        if ($srch['name'] != "") {
            $name = $srch['name'];
            $temp .= " AND name LIKE '%$name%' ";
        }
        if ($srch['email'] != "") {
            $name = $srch['email'];
            $temp .= " AND email LIKE '%$name%' ";
        }
        if ($srch['mobile'] != "") {
            $name = $srch['mobile'];
            $temp .= " AND phone LIKE '%$name%' ";
        }
        if ($srch['gender'] != "") {
            $name = $srch['gender'];
            $temp .= " AND gender = '$name' ";
        }
        $query = $this->db->query("SELECT * from just_dial_data where status ='1' $temp order by id desc LIMIT $two,$one");
        $data['user'] = $query->result_array();
        return $data['user'];
    }
    public function phlebolist_list_csv($srch) {
        $temp = "";
        if ($srch['name'] != "") {
            $name = $srch['name'];
            $temp .= " AND name LIKE '%$name%' ";
        }
        if ($srch['email'] != "") {
            $name = $srch['email'];
            $temp .= " AND email LIKE '%$name%' ";
        }
        if ($srch['mobile'] != "") {
            $name = $srch['mobile'];
            $temp .= " AND phone LIKE '%$name%' ";
        }
        if ($srch['gender'] != "") {
            $name = $srch['gender'];
            $temp .= " AND gender = '$name' ";
        }
        $query = $this->db->query("SELECT * from just_dial_data where status ='1' $temp order by id desc");
        $data['user'] = $query->result_array();
        return $data['user'];
    }
}

?>
