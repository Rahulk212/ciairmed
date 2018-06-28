<?php

class Upload_server extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function master_fun_get_tbl_val($dtatabase, $condition, $order, $limit = null) {
        $this->db->order_by($order[0], $order[1]);
        if ($limit != null) {
            $this->db->limit($limit[0], $limit[1]);
        }
        $query = $this->db->get_where($dtatabase, $condition);
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    function master_fun_insert($table, $data) {
        //echo $table; print_R($data); die();
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function master_fun_update($tablename, $cid, $data) {
        $this->db->where($cid);
        $this->db->update($tablename, $data);
        return 1;
    }

    public function master_num_rows($table, $condition) {
        $query1 = $this->db->get_where($table, $condition);
        return $query1->num_rows();
    }

    public function get_server_time() {
        $query = $this->db->query("SELECT UTC_TIMESTAMP()");
        $data['user'] = $query->result_array();
        return $data['user'][0];
    }

    function get_active_record() {
        $query = $this->db->query("SELECT * FROM `bmh_project_master` WHERE `status`='1' ORDER BY id desc");
        return $query->result_array();
    }

    function get_active_record1($one, $two) {

        $query = $this->db->query("SELECT * FROM `press_release` WHERE `status`='1' ORDER BY id desc LIMIT $two,$one ");

        return $query->result_array();
    }

    function get_team_record() {
        $query = $this->db->query("SELECT id,picture,name,designation FROM `bmh_team_master` WHERE `status`='1' ORDER BY id desc");
        return $query->result_array();
    }

    function get_team_record1($one, $two) {

        $query = $this->db->query("SELECT id,picture,name,designation FROM `bmh_team_master` WHERE `status`='1' ORDER BY id desc LIMIT $two,$one ");

        return $query->result_array();
    }

    public function get_data($query) {
        $query1 = $this->db->query($query . " ORDER BY id DESC");
        $data['user'] = $query1->result_array();
        return $data['user'];
    }

    function get_active_record3($query, $one, $two) {
        $query = $this->db->query($query . " ORDER BY id DESC  limit " . $two . "," . $one);
        return $query->result_array();
    }

    public function search_data($query) {
        $query1 = $this->db->query($query . " ORDER BY id DESC");
        $data['user'] = $query1->result_array();
        return $data['user'];
    }

    function search_active_record3($query, $one, $two) {
        $query = $this->db->query($query . " ORDER BY id DESC  limit " . $two . "," . $one);
        return $query->result_array();
    }

    function location_active_record() {
        $query = $this->db->query("SELECT * FROM `location_master` WHERE `status`='1' ORDER BY id DESC");
        return $query->result_array();
    }

    function location_active_record1($one, $two) {

        $query = $this->db->query("SELECT * FROM `location_master` WHERE `status`='1' ORDER BY id DESC LIMIT $two,$one ");

        return $query->result_array();
    }

    function category_active_record() {
        $query = $this->db->query("SELECT * FROM `category_master` WHERE `status`='1' ORDER BY id DESC");
        return $query->result_array();
    }

    function category_active_record1($one, $two) {

        $query = $this->db->query("SELECT * FROM `category_master` WHERE `status`='1' ORDER BY id DESC LIMIT $two,$one ");

        return $query->result_array();
    }

    function subcategory_active_record() {
        $query = $this->db->query("SELECT subcategory_master.*,category_master.category_name,category_master.status FROM `subcategory_master` join `category_master` on subcategory_master.category_id=category_master.id WHERE category_master.status='1' AND subcategory_master.status='1' ORDER BY subcategory_master.id DESC");
        return $query->result_array();
    }

    function subcategory_active_record1($one, $two) {

        $query = $this->db->query("SELECT subcategory_master.*,category_master.category_name,category_master.status FROM `subcategory_master` join `category_master` on subcategory_master.category_id=category_master.id WHERE category_master.status='1' AND subcategory_master.status='1' ORDER BY subcategory_master.id DESC LIMIT $two,$one ");

        return $query->result_array();
    }

    public function master_fun_update1($tablename, $condition, $data) {
        $this->db->where($condition);
        $this->db->update($tablename, $data);
        return 1;
    }

    function get_aboutus_record() {
        $query = $this->db->query("SELECT id,title,description FROM `bmh_aboutus_master` WHERE `status`='1' ORDER BY id desc");
        return $query->result_array();
    }

    function category_list() {

        $query = $this->db->query("SELECT * FROM `bmh_house_cat` WHERE `status`='1'");
        return $query->result_array();
    }

    public function housecat_get_tbl_val() {

        $query1 = $this->db->query("select * from bmh_house_subcat where status='1' order by id desc");
        $data['user'] = $query1->result_array();
        return $data['user'];
    }

    public function get_val($qry) {
        $query1 = $this->db->query($qry);
        $data['user'] = $query1->result_array();
        return $data['user'];
    }

    public function master_fun_update_new($tablename, $cid, $data) {
        $this->db->where($cid[0], $cid[1]);
        $this->db->update($tablename, $data);
        return 1;
    }

    public function finishcat_get_tbl_val() {

        $query1 = $this->db->query("select * from bmh_finishing_work_subcat where status='1' order by id desc");
        $data['user'] = $query1->result_array();
        return $data['user'];
    }

    function finish_list() {

        $query = $this->db->query("SELECT * FROM `bmh_finishing_work_cat` WHERE `status`='1'");
        return $query->result_array();
    }

    public function registercat_get_tbl_val() {

        $query1 = $this->db->query("select * from bmh_registration where status='1' order by id desc");
        $data['user'] = $query1->result_array();
        return $data['user'];
    }
	
	public function listing() {

        $query= $this->db->query("select * from sms_master where status='1' order by id desc");
        return $query->result();
    }

    public function srch_doctor_list($limit, $start) {

        $query = "SELECT 
  `doctor_req_discount`.*,
  `doctor_master`.`full_name` 
FROM
  `doctor_req_discount` 
  INNER JOIN `doctor_master` 
    ON `doctor_master`.`id` = `doctor_req_discount`.`doctor_fk` 
WHERE `doctor_master`.`status` = '1' 
  AND `doctor_req_discount`.`status` = '1' ORDER BY `doctor_req_discount`.`id` DESC LIMIT $start , $limit ";
        $result = $this->db->query($query);
        return $result->result_array();
    }

}

?>
