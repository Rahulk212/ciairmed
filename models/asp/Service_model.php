<?php

Class Service_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function master_fun_insert($table, $data) {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function master_fun_update($tablename, $cid, $data) {
        $this->db->where('id', $cid);
        $this->db->update($tablename, $data);
        return 1;
    }

    function updateRowWhere($table, $where, $data) {
        $this->db->where($where);
        $this->db->update($table, $data);
        return 1;
    }

    public function master_fun_update1($tablename, $cid, $data) {
        $this->db->where($cid[0], $cid[1]);
        $this->db->update($tablename, $data);
        return 1;
    }

    public function num_row($table, $condition) {
        $query = $this->db->get_where($table, $condition);
        return $query->num_rows();
    }

    public function master_fun_get_tbl_val($dtatabase, $select, $condition, $order) {
        $this->db->order_by($order[0], $order[1]);
        $this->db->select($select);
        $query = $this->db->get_where($dtatabase, $condition);
        $data['user'] = $query->result();
        return $data['user'];
    }

    public function master_get_vallimt($dtatabase, $select, $condition, $order, $limit) {

        $this->db->order_by($order[0], $order[1]);
        $this->db->select($select);
        $this->db->limit($limit);
        $query = $this->db->get_where($dtatabase, $condition);
        $data['user'] = $query->result();
        return $data['user'];
    }

    public function master_get_vallimt1($dtatabase, $select, $condition, $order, $limit, $start) {

        $this->db->order_by($order[0], $order[1]);
        $this->db->select($select);
        $this->db->limit($limit, $start);
        $query = $this->db->get_where($dtatabase, $condition);
        $data['user'] = $query->result();
        return $data['user'];
    }

    public function fetchdatarow($selact, $table, $array) {
        $this->db->select($selact);
        $query = $this->db->get_where($table, $array);
        return $query->row();
    }

    public function master_num_rows($table, $condition) {

        $query1 = $this->db->get_where($table, $condition);
        return $query1->num_rows();
    }

    public function master_fun_update_my($tablename, $cid, $data) {
        $this->db->where($cid[0], $cid[1]);
        $this->db->where($cid[2], $cid[3]);
        $this->db->update($tablename, $data);
        return 1;
    }

    public function get_one($dtatabase, $condition) {
        $query = $this->db->get_where($dtatabase, $condition);
        $result = $query->row();
        return $result;
    }

    public function get_one_data($dtatabase, $select, $condition) {
        $this->db->select($select);
        $query = $this->db->get_where($dtatabase, $condition);
        $result = $query->row();
        return $result;
    }

    public function update($tablename, $cid, $data) {
        $this->db->where($cid);
        $this->db->update($tablename, $data);
        return 1;
    }

    public function insert($table, $data) {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function get_all($dtatabase, $condition) {
        $query = $this->db->get_where($dtatabase, $condition);
        $result = $query->result();
        return $result;
    }

    public function test_list($city) {
        $query = $this->db->query("SELECT 
  test_master.`id`,
  `test_master`.`test_name`,
  `test_master_city_price`.`price`
FROM
  `test_master` 
  INNER JOIN `test_master_city_price` 
    ON `test_master`.`id` = `test_master_city_price`.`test_fk` 
  LEFT JOIN 
    (SELECT 
      GROUP_CONCAT(tmm.`test_name` SEPARATOR '%@%') AS t_tst,
      tm.`id` 
    FROM
      `sub_test_master` 
      LEFT JOIN `test_master` tm 
        ON `sub_test_master`.`test_fk` = tm.`id` 
      LEFT JOIN test_master tmm 
        ON `sub_test_master`.`sub_test` = tmm.id 
    WHERE `sub_test_master`.`status` = '1' 
    GROUP BY tm.`id`) AS tst 
    ON tst.id = `test_master`.`id` 
WHERE `test_master`.`status` = '1' 
  AND `test_master_city_price`.`status` = '1' 
  AND `test_master_city_price`.`city_fk` = '" . $city . "' 
GROUP BY `test_master`.`id`");
        $result = $query->result();
        return $result;
    }
    
    public function package_list($city) {
        $query = $this->db->query("SELECT 
              `package_master`.id,
              `package_master`.title,
              `package_master_city_price`.`d_price` AS `d_price`
              FROM
              `package_master`
              INNER JOIN `package_master_city_price`
              ON `package_master`.`id` = `package_master_city_price`.`package_fk`
              WHERE `package_master`.`status` = '1'
              AND `package_master_city_price`.`status` = '1' AND `package_master_city_price`.`city_fk` = '$city'");
        $result = $query->result();
        return $result;
    }

}

?>