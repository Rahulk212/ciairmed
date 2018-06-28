<?php

class Phlebo_manage_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function get_phlebo_time_slot() {

        $query1 = $this->db->query("select * from bmh_registration where status='1' order by id desc");
        $data['user'] = $query1->result_array();
        return $data['user'];
    }

}

?>
