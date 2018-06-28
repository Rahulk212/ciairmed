<?php

Class Parameter_model extends CI_Model {

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

    public function fetchdatarow($selact, $table, $array) {
        $this->db->select($selact);
        $query = $this->db->get_where($table, $array);
        return $query->row();
    }

    public function num_row($table, $condition) {
        $query = $this->db->get_where($table, $condition);
        return $query->num_rows();
    }

    public function contact_master($table_name, $data) {

        $this->db->insert($table_name, $data);
        return $this->db->insert_id();
    }

    public function citylist() {
        $query = $this->db->query("SELECT c.*,s.state_name,co.country_name FROM city c LEFT JOIN state s ON c.state_fk=s.id LEFT JOIN country co  ON c.`country_fk`=co.id  WHERE s.status=1 AND c.status=1");
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    public function statelist() {
        $query = $this->db->query("SELECT s.*,c.country_name FROM state s LEFT JOIN country c ON c.id=s.`country_fk` WHERE s.status=1 AND c.status=1");
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    function get_city() {
        $query = $this->db->query("SELECT city.*,state.`state_name` FROM city INNER JOIN state ON city.`state_fk`=state.`id` WHERE city.`status`='1' AND state.`status`='1' ORDER BY city_name ASC");
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    function get_city_edit($id) {
        $query = $this->db->query("SELECT `test_master_city_price`.*,`test_cities`.`name` AS `city_name` FROM `test_master_city_price` 
INNER JOIN `test_cities` ON `test_master_city_price`.`city_fk`=`test_cities`.`id` 
WHERE `test_master_city_price`.`test_fk`='" . $id . "' 
AND `test_master_city_price`.`status`='1' 
AND `test_cities`.`status`='1' order by `test_cities`.`name` asc");
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    function get_city_edit1($id) {
        $query = $this->db->query("SELECT `test_master_city_price`.city_fk as `id`,`test_master_city_price`.`price`,`test_cities`.`name` FROM `test_master_city_price` 
INNER JOIN `test_cities` ON `test_master_city_price`.`city_fk`=`test_cities`.`id` 
WHERE `test_master_city_price`.`test_fk`='" . $id . "' 
AND `test_master_city_price`.`status`='1' 
AND `test_cities`.`status`='1'");
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    public function master_fun_delete($tablename, $cid) {
        $this->db->where($cid[0], $cid[1]);
        $this->db->delete($tablename);
        return 1;
    }

    function parameter_list($one, $two) {

        $query = $this->db->query("SELECT * FROM `test_parameter_master` WHERE STATUS='1' ORDER BY parameter_name ASC LIMIT $two,$one");

        return $query->result_array();
    }

    function parameter_list_search($search, $one, $two) {
        $temp = "";
        if ($search != "") {
            $temp .= " AND parameter_name like '%$search%'";
        }
        $query = $this->db->query("SELECT * FROM `test_parameter_master` WHERE STATUS='1' $temp ORDER BY parameter_name ASC LIMIT $two,$one");
        return $query->result_array();
    }

    function parameter_list_search_num($search, $test) {
        $temp = "";
        if ($search != "" || $test != "") {
            $temp = " AND ( ";
        }
        if ($search != "") {
            $temp .= "parameter_name like '%$search%'";
            if ($test) {
                $temp .= "  and p.test_fk = '$test' ";
            }
        } else if ($test) {
            $temp .= " p.test_fk = '$test' ";
        }
        if ($search != "" || $test != "") {
            $temp .= " ) ";
        }
        $query = $this->db->query("SELECT * FROM `test_parameter_master` WHERE STATUS='1' $temp ORDER BY parameter_name ASC");

        return $query->num_rows();
    }

    function count_list() {
        $query = $this->db->query("SELECT * FROM `test_parameter_master` WHERE STATUS='1' order by parameter_name asc");
        return $query->num_rows();
    }

    function get_edit_val($pid, $gid = null) {
        if ($gid != "") {
            $query = "SELECT p.id,p.parameter_name,p.parameter_range,p.parameter_unit,p.test_fk,g.id as g_id,g.parameter_fk,g.subparameter_name,g.subparameter_range,g.subparameter_unit FROM `test_parameter_master` p LEFT JOIN `parameter_group_master` g ON p.`id`=g.`parameter_fk` where p.id=$pid and g.id=$gid";
        } else {
            $query = "SELECT p.id,p.parameter_name,p.parameter_range,p.parameter_unit,p.test_fk FROM `test_parameter_master` p where p.id=$pid";
        }
        $query = $this->db->query($query);
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    function delete_subparameter($table, $gid) {
        $this->db->delete($table, array('id' => $gid));
        return 1;
    }

}

?>
