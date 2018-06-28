<?php

class Package_category_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function num_row($fullname) {

        $this->db->select('*');
        if ($fullname != "") {
            $this->db->like('name', $fullname);
        }
        $this->db->where('status', '1');
        $this->db->ORDER_BY('id', 'desc');
        $query = $this->db->get('package_category');


        return $query->num_rows();
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

    public function search($fullname, $one, $two) {
        $this->db->select('*');
        if ($fullname != "") {
            $this->db->like('name', $fullname);
        }
        $this->db->where('status', '1');


        $this->db->ORDER_BY('ID', 'desc');
        $this->db->limit($one, $two);
        $query = $this->db->get('package_category');


        return $query->result_array();
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

}

?>