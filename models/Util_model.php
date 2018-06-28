<?php

Class Util_model extends CI_Model {

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

    function get_val($query1 = null) {
        $query = $this->db->query($query1);
        $data['user'] = $query->result_array();
        return $data['user'];
    }



    function check_parent_package($jid, $pid) {
        $query = "SELECT `job_master`.`id`,`job_master`.`cust_fk`,`job_master`.`booking_info`,`booking_info`.`type`,`booking_info`.`family_member_fk` FROM `job_master` INNER JOIN `booking_info` ON `booking_info`.`id`=`job_master`.`booking_info` WHERE `job_master`.`status`!='0' AND `job_master`.`id`='" . $jid . "'";
        $query = $this->db->query($query);
        $query = $query->result_array();
        $fquery = "SELECT * FROM `active_package` WHERE `user_fk`='" . $query[0]["cust_fk"] . "' AND `package_fk`='" . $pid . "' AND `family_fk`='" . $query[0]["family_member_fk"] . "' AND `status`='1' AND due_to >= '" . date("Y-m-d") . "' AND parent=0";
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
