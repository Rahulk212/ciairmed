<?php

Class Logistic_test_modal extends CI_Model {

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
        $query = $this->db->query("SELECT `sample_test_city_price`.*,`test_cities`.`name` AS `city_name` FROM `sample_test_city_price` 
INNER JOIN `test_cities` ON `sample_test_city_price`.`city_fk`=`test_cities`.`id` 
WHERE `sample_test_city_price`.`test_fk`='" . $id . "' 
AND `sample_test_city_price`.`status`='1' 
AND `test_cities`.`status`='1' order by `test_cities`.`name` asc");
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    function get_city_edit1($id) {
        $query = $this->db->query("SELECT `sample_test_city_price`.city_fk as `id`,`sample_test_city_price`.`price`,`test_cities`.`name` FROM `sample_test_city_price` 
INNER JOIN `test_cities` ON `sample_test_city_price`.`city_fk`=`test_cities`.`id` 
WHERE `sample_test_city_price`.`test_fk`='" . $id . "' 
AND `sample_test_city_price`.`status`='1' 
AND `test_cities`.`status`='1'");
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    public function master_fun_delete($tablename, $cid) {
        $this->db->where($cid[0], $cid[1]);
        $this->db->delete($tablename);
        return 1;
    }

    function test_list($one, $two) {

        $query = $this->db->query("SELECT * FROM sample_test_master WHERE STATUS='1' ORDER BY test_name ASC LIMIT $two,$one");

        return $query->result_array();
    }

    function get_val($squery) {
        $query = $this->db->query($squery);
        return $query->result_array();
    }

    function price_test_list($id = "") {

        if ($id == "") {
            $id = 1;
        }
        $data['id'] = $id;
        $query = $this->db->query("SELECT t.id,t.test_name,tc.price,c.`name` AS city FROM `sample_test_master` t LEFT JOIN `sample_test_city_price` tc ON tc.`test_fk`=t.`id` LEFT JOIN `test_cities` c ON tc.`city_fk`=c.`id` WHERE t.`status`='1' and tc.`city_fk`=$id ORDER BY t.test_name ASC ");
        return $query->result_array();
    }

    function test_list_search($search, $city, $one, $two) {
        $temp = "";
        if ($search != "") {
            $temp .= "AND t.test_name like '%$search%'";
        }
        if ($city != "") {
            $temp .= " AND tc.city_fk='".$city."'";
        }
        $query = $this->db->query("SELECT DISTINCT t.id,t.test_name FROM  `sample_test_master` t  LEFT JOIN sample_test_city_price tc ON tc.test_fk=t.id  LEFT JOIN `test_cities` c ON tc.`city_fk`=c.`id` WHERE t.status = '1' $temp ORDER BY t.test_name ASC LIMIT $two,$one");
        return $query->result_array();
    }

    function test_list_search_num($search, $city) {
        $temp = "";
        if ($search != "" || $city != "") {
            $temp = " AND ( ";
        }
        if ($search != "") {
            $temp .= "t.test_name like '%$search%'";
            if ($city) {
                $temp .= "  and tc.city_fk = '$city' ";
            }
        } else if ($city) {
            $temp .= " tc.city_fk = '$city' ";
        }
        if ($search != "" || $city != "") {
            $temp .= " ) ";
        }
        //echo "SELECT t.id,t.test_name,t.description,t.popular,tc.price,c.`name` AS city FROM `sample_test_master` t LEFT JOIN `sample_test_city_price` tc ON tc.`test_fk`=t.`id` LEFT JOIN `test_cities` c ON tc.`city_fk`=c.`id` WHERE t.`status`='1' $temp ORDER BY t.test_name ASC"; die();
        $query = $this->db->query("SELECT DISTINCT t.id,t.test_name FROM `sample_test_master` t LEFT JOIN `sample_test_city_price` tc ON tc.`test_fk`=t.`id` LEFT JOIN `test_cities` c ON tc.`city_fk`=c.`id` WHERE t.`status`='1' $temp ORDER BY t.test_name ASC");

        return $query->num_rows();
    }

}

?>
