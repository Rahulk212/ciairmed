<?php

class Phlebo_punchin_punchout_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function num_row($fullname=null,$date=null,$end_date=null) {
        $qry = "SELECT phlabo_timer.*,`phlebo_master`.`name` FROM `phlabo_timer` INNER JOIN `phlebo_master` ON `phlabo_timer`.`user_fk`=`phlebo_master`.`id` WHERE 1=1 ";
        if ($fullname != "") {
            $qry .= " AND name LIKE '%" . $fullname . "%' ";
        }
        if ($date != "") {
            $qry .= " AND start_date>='" . $date . "' ";
        }
        if ($end_date != "") {
            $qry .= " AND start_date<='" . $end_date . "' ";
        }
        $qry .= " AND `phlabo_timer`.status='1' ORDER BY `phlabo_timer`.id DESC";
        $query = $this->db->query($qry);
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    public function insert($data) {

        $this->db->insert('package_category', $data);
        return true;
        return $this->db->insert_id();
    }

    public function delete_pc($pcid, $data) {
        $this->db->where(array('id' => $pcid));
        $this->db->update('package_category', $data);
        return true;
    }

    public function get_pc($pcid) {
        $query = $this->db->get_where('package_category', array('id' => $pcid));
        $data = $query->row();
        return $data;
    }

    public function update($pcid, $data) {
        $this->db->set($data);
        $this->db->where("id", $pcid);
        $this->db->update("package_category", $data);
        return true;
    }

    public function search($fullname,$date,$end_date, $one, $two) {
        $qry = "SELECT phlabo_timer.*,`phlebo_master`.`name` FROM `phlabo_timer` INNER JOIN `phlebo_master` ON `phlabo_timer`.`user_fk`=`phlebo_master`.`id` WHERE 1=1 ";
        if ($fullname != "") {
            $qry .= " AND phlebo_master.name LIKE '%" . $fullname . "%' ";
        }
        if ($date != "") {
            $qry .= " AND start_date>='" . $date . "' ";
        }
        if ($end_date != "") {
            $qry .= " AND start_date<='" . $end_date . "' ";
        }
        $qry .= " AND `phlabo_timer`.status='1' ORDER BY `phlabo_timer`.id DESC LIMIT $two,$one";
        $query = $this->db->query($qry);
        $data['user'] = $query->result_array();
        return $data['user'];
    }
        public function get_details($id) {
        $qry = "SELECT phlabo_timer.*,`phlebo_master`.`name`,`phlebo_master`.`mobile` FROM `phlabo_timer` INNER JOIN `phlebo_master` ON `phlabo_timer`.`user_fk`=`phlebo_master`.`id` WHERE 1=1 ";
        
        $qry .= " AND `phlabo_timer`.status='1' AND phlabo_timer.id='".$id."' ORDER BY `phlabo_timer`.id DESC";
        $query = $this->db->query($qry);
        $data['user'] = $query->result_array();
        return $data['user'];
    }
        public function csv_search($fullname,$date,$end_date) {
        $qry = "SELECT phlabo_timer.*,`phlebo_master`.`name`,`phlebo_master`.`id` as pid FROM `phlabo_timer` INNER JOIN `phlebo_master` ON `phlabo_timer`.`user_fk`=`phlebo_master`.`id` WHERE 1=1 ";
        if ($fullname != "") {
            $qry .= " AND phlebo_master.name LIKE '%" . $fullname . "%' ";
        }
        if ($date != "") {
            $qry .= " AND start_date>='" . $date . "' ";
        }
        if ($end_date != "") {
            $qry .= " AND start_date<='" . $end_date . "' ";
        }
        $qry .= " AND `phlabo_timer`.status='1' ORDER BY `phlabo_timer`.id DESC";
        $query = $this->db->query($qry);
        $data['user'] = $query->result_array();
        return $data['user'];
    }
    function create_unique_slug($string, $table, $field = 'slug', $key = NULL, $value = NULL) {
        $t = & get_instance();
        $slug = url_title($string);
        $slug = strtolower($slug);
        $i = 0;
        $params = array();
        $params[$field] = $slug;
        if ($key)
            $params["$key !="] = $value;
        while ($t->db->where($params)->get($table)->num_rows()) {
            if (!preg_match('/-{1}[0-9]+$/', $slug))
                $slug .= '-' . ++$i;
            else
                $slug = preg_replace('/[0-9]+$/', ++$i, $slug);
            $params [$field] = $slug;
        } return $slug;
    }
    function get_val($query1 = null) {
        $query = $this->db->query($query1);
        $data['user'] = $query->result_array();
        return $data['user'];
    }
}

?>