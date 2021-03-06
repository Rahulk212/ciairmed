<?php

Class User_test_master_model extends CI_Model {

    function getmenu() {
        // $this->db->order_by("id", "desc");
        $query = $this->db->get_where("bmh_menu_master", array("status" => "1"));
        return $query->result_array();
    }

    function getUser($user_id) {
        $query = $this->db->get_where("customer_master", array("id" => $user_id, "status" => "1"));
        return $query->row();
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

    public function contact_master($table_name, $data) {

        $this->db->insert($table_name, $data);
        return $this->db->insert_id();
    }

    function update_user1($data, $user_id) {
        $this->db->update("admin_master", $data, array("id" => $user_id));
        return 1;
    }

    function search_test($testname = null) {

        $query = "select * from test_master where status=1";

        if ($testname != "") {

            $query .= " AND test_name LIKE '%$testname%'";
        }
        $query = $this->db->query($query);
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    function get_val($query1 = null) {
        $query = $this->db->query($query1);
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    function get_random_phlebo($booking_info) {
        $query = "select * from phlebo_assign_job where time_fk='" . $booking_info["time_slot_fk"] . "' AND date='" . $booking_info["date"] . "' AND status=1";
        $query = $this->db->query($query);
        $get_booked_details = $query->result_array();
        if (!empty($get_booked_details)) {
            $assign_phlebo = array();
            foreach ($get_booked_details as $key) {
                $assign_phlebo[] = $key["phlebo_fk"];
            }
            $query = "select * from phlebo_master where id not in(" . implode(",", $assign_phlebo) . ") AND status='1' ORDER by rand() LIMIT 1";
            $query = $this->db->query($query);
            $query = $query->result_array();
        } else {
            $query = "select * from phlebo_master where status='1' ORDER by rand() LIMIT 1";
            $query = $this->db->query($query);
            $query = $query->result_array();
        }
        return $query;
    }

    function get_family_member_name($jid) {
        $query = "SELECT `job_master`.`id`,`job_master`.`booking_info`,`booking_info`.`type`,`booking_info`.`family_member_fk` FROM `job_master` INNER JOIN `booking_info` ON `booking_info`.`id`=`job_master`.`booking_info` WHERE `job_master`.`status`!='0' AND `job_master`.`id`='" . $jid . "'";
        $query = $this->db->query($query);
        $query = $query->result_array();
        if ($query[0]["type"] == "family") {
            $fquery = "SELECT name,phone,email from customer_family_master where id='" . $query[0]["family_member_fk"] . "'";
            $fquery = $this->db->query($fquery);
            return $query = $fquery->result_array();
        } else {
            return 0;
        }
    }

    function check_parent_package($jid, $pid) {
        $query = "SELECT `job_master`.`id`,`job_master`.`cust_fk`,`job_master`.`booking_info`,`booking_info`.`type`,`booking_info`.`family_member_fk` FROM `job_master` INNER JOIN `booking_info` ON `booking_info`.`id`=`job_master`.`booking_info` WHERE `job_master`.`status`!='0' AND `job_master`.`id`='" . $jid . "'";
        $query = $this->db->query($query);
        $query = $query->result_array();
        //$fquery = "SELECT * FROM `active_package` WHERE `user_fk`='" . $query[0]["cust_fk"] . "' AND `package_fk`='" . $pid . "' AND `family_fk`='" . $query[0]["family_member_fk"] . "' AND `status`='1' AND due_to >= '" . date("Y-m-d") . "' AND parent=0";
        $fquery = "SELECT `job_master`.`order_id`,`active_package`.* FROM `active_package` INNER JOIN `job_master` ON `job_master`.`id`=`active_package`.`job_fk` WHERE `job_master`.`status`!='0' AND `active_package`.`user_fk`='" . $query[0]["cust_fk"] . "' AND `active_package`.`package_fk`='" . $pid . "' AND `active_package`.`family_fk`='" . $query[0]["family_member_fk"] . "' AND `active_package`.`status`='1' AND `active_package`.due_to >= '" . date("Y-m-d") . "' AND `active_package`.parent=0";
        $fquery = $this->db->query($fquery);
        $fquery = $fquery->result_array();
        if (!empty($fquery)) {
            $parent_fk = $fquery[0]["id"];
        } else {
            $parent_fk = 0;
        }
        return $parent_fk;
    }

}

?>
