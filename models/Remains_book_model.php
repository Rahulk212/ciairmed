<?php

Class Remains_book_model extends CI_Model {

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

    function user_list1($one, $two) {

        $query = $this->db->query("SELECT exotel_calls.*,customer_master.full_name FROM exotel_calls LEFT JOIN customer_master ON customer_master.mobile=exotel_calls.CallFrom WHERE exotel_calls.status='1' ORDER BY exotel_calls.id DESC LIMIT $two,$one ");

        return $query->result_array();
    }

    function user_list($one, $two) {

        $query = $this->db->query("SELECT * FROM exotel_calls WHERE status='1' ORDER BY id DESC LIMIT $two,$one ");

        return $query->result_array();
    }

    function user_running_call($email) {

        $query = $this->db->query("SELECT max(id) as maxid,id,CallFrom,CallTo,StartTime,DialCallDuration FROM exotel_calls WHERE status='1' and AgentEmail='$email' and AgentStatus ='busy'");

        return $query->result();
    }

    public function num_row_srch_call_list($caller = null, $call_from = null, $call_to = null, $direction = null, $start_date = null, $duration = null, $call_type = null, $call_date = null, $agent = null, $agent_number = null) {

        $query = "SELECT *  FROM oncall_booking_data WHERE status='1' ";

//        if ($caller != "") {
//
//            $query .= " AND customer_master.id='$caller'";
//        }
//	if ($call_from != "") {
//
//            $query .= " AND exotel_calls.CallFrom LIKE '%$call_from%'";
//        }
//	if ($call_to != "") {
//
//            $query .= " AND exotel_calls.CallTo LIKE '%$call_to%'";
//        }
//	if ($direction != "") {
//
//            $query .= " AND exotel_calls.Direction LIKE '%$direction%'";
//        }
//        if ($start_date != "") {
//
//            $query .= " AND DATE_FORMAT(exotel_calls.StartTime, '%d/%m/%Y')='$start_date'";
//        }
//        if ($duration != "") {
//
//            $query .= " AND exotel_calls.DialCallDuration LIKE '%$duration%'";
//        }
//        if ($call_type != "") {
//
//            $query .= " AND exotel_calls.CallType LIKE '%$call_type%'";
//        }
//        if ($call_date != "") {
//
//            $query .= " AND DATE_FORMAT(exotel_calls.CurrentTime, '%d/%m/%Y')='$call_date'";
//        }
//        if ($agent != "") {
//
//            $query .= " AND exotel_calls.AgentEmail LIKE '%$agent%'";
//        }
//        if ($agent_number != "") {
//
//            $query .= " AND exotel_calls.DialWhomNumber LIKE '%$agent_number%'";
//        }
        $query .= " ORDER BY id DESC";
        $result = $this->db->query($query);
        return $result->num_rows();
    }

    public function row_srch_call_list($caller = null, $call_from = null, $call_to = null, $direction = null, $start_date = null, $duration = null, $call_type = null, $call_date = null, $agent = null, $agent_number = null, $limit, $start) {

        $query = "SELECT *  FROM oncall_booking_data WHERE status='1' ";

//        if ($caller != "") {
//
//            $query .= " AND customer_master.id='$caller'";
//        }
//	if ($call_from != "") {
//
//            $query .= " AND exotel_calls.CallFrom LIKE '%$call_from%'";
//        }
//	if ($call_to != "") {
//
//            $query .= " AND exotel_calls.CallTo LIKE '%$call_to%'";
//        }
//	if ($direction != "") {
//
//            $query .= " AND exotel_calls.Direction LIKE '%$direction%'";
//        }
//        if ($start_date != "") {
//
//            $query .= " AND DATE_FORMAT(exotel_calls.StartTime, '%d/%m/%Y')='$start_date'";
//        }
//        if ($duration != "") {
//
//            $query .= " AND exotel_calls.DialCallDuration LIKE '%$duration%'";
//        }
//        if ($call_type != "") {
//
//            $query .= " AND exotel_calls.CallType LIKE '%$call_type%'";
//        }
//        if ($call_date != "") {
//
//            $query .= " AND DATE_FORMAT(exotel_calls.CurrentTime, '%d/%m/%Y')='$call_date'";
//        }
//        if ($agent != "") {
//
//            $query .= " AND exotel_calls.AgentEmail LIKE '%$agent%'";
//        }
//        if ($agent_number != "") {
//
//            $query .= " AND exotel_calls.DialWhomNumber LIKE '%$agent_number%'";
//        }

        $query .= " ORDER BY id DESC LIMIT $start , $limit";

        $result = $this->db->query($query);
        return $result->result_array();
    }

    public function srch_call_list($limit, $start) {

        $query = $this->db->query("SELECT *  FROM oncall_booking_data WHERE status='1' ORDER BY id DESC LIMIT $start , $limit ");
        return $query->result_array();
    }
    public function get_all_data($dtatabase,$select, $condition, $order) {
        $this->db->order_by($order[0], $order[1]);
        $this->db->select($select);
        $query = $this->db->get_where($dtatabase, $condition);
        $data['user'] = $query->result_array();
        return $data['user'];
    }

}

?>
