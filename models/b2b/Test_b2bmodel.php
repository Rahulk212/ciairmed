<?php

Class Test_b2bmodel extends CI_Model {

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

    function get_city_edit1($id) {
        $query = $this->db->query("SELECT `test_master_city_price`.city_fk as `id`,`test_master_city_price`.`price`,`test_cities`.`name` FROM `test_master_city_price` 
INNER JOIN `test_cities` ON `test_master_city_price`.`city_fk`=`test_cities`.`id` 
WHERE `test_master_city_price`.`test_fk`='" . $id . "' 
AND `test_master_city_price`.`status`='1' 
AND `test_cities`.`status`='1'");
        $data['user'] = $query->result_array();
        return $data['user'];
    }

   

    function test_list($one, $two) {

        $query = $this->db->query("SELECT  id,test_name,thyrocare_code FROM sample_test_master WHERE status='1' ORDER BY id desc LIMIT $two,$one");

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
        $query = $this->db->query("SELECT t.id,t.test_name,t.description,tc.price,c.`name` AS city FROM `sample_test_master` t LEFT JOIN `test_master_city_price` tc ON tc.`test_fk`=t.`id` LEFT JOIN `test_cities` c ON tc.`city_fk`=c.`id` WHERE t.`status`='1' and tc.`city_fk`=$id ORDER BY t.id desc ");
        return $query->result_array();
    }

    function test_list_search($search, $city, $one, $two) {
        $temp = "";
        if ($search != "") {
            $temp .= "AND t.test_name like '%$search%'";
        }
        /* if ($city != "") {
            $temp .= " AND tc.city_fk='".$city."'";
        } */

        /* $query = $this->db->query("SELECT  t.id,t.test_name,t.thyrocare_code,t.description AS city FROM  `sample_test_master` t  LEFT JOIN test_master_city_price tc ON tc.test_fk=t.id  LEFT JOIN `test_cities` c ON tc.`city_fk`=c.`id` WHERE t.status = '1' $temp ORDER BY t.id desc LIMIT $two,$one"); */
		$query = $this->db->query("SELECT  t.id,t.test_name,t.thyrocare_code FROM  `sample_test_master` t  WHERE t.status = '1' $temp ORDER BY t.id desc LIMIT $two,$one");
        return $query->result_array();
    }
function test_list_search_num($search, $city) {
        $temp = "";
        /* if ($search != "" || $city != "") {
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
        } */
        $query = $this->db->query("SELECT DISTINCT t.id FROM `sample_test_master` t LEFT JOIN `test_master_city_price` tc ON tc.`test_fk`=t.`id` LEFT JOIN `test_cities` c ON tc.`city_fk`=c.`id` WHERE t.`status`='1' And t.test_name like '%$search%' ORDER BY t.id desc");

        return $query->num_rows();
    }

}

?>
