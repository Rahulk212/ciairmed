<?php

Class Branch_Model extends CI_Model {

    public function list_city() {
        $query = $this->db->query("SELECT c.id as cid,c.name as city_name FROM test_cities c WHERE status = 1 ");
        return $query->result_array();
        /*  echo "<pre>";
          print_r($query);
          exit; */
    }

    public function master_get_where_condtion($table, $cid, $order) {
        $this->db->order_by($order[0], $order[1]);
        $query = $this->db->get_where($table, $cid);
        return $query->result_array();
    }

    public function master_tbl_update($tablename, $cid, $data) {
        $this->db->where(array('id' => $cid));
        $this->db->update($tablename, $data);
        return 1;
    }

    public function master_get_search($branchid, $one, $two,$test_city=NULL) {

        $query = "SELECT br.*,c.id as cid,c.name as city_name
							FROM branch_master as br 
							LEFT JOIN test_cities as c
							ON br.city = c.id 
							where br.status = 1 AND br.id != ' '";
        if ($branchid != "") {
            $query .= " AND br.branch_name LIKE '%$branchid%'";
        }
        if ($test_city != NULL) {
            $query .= " AND c.id ='$test_city'";
        }

        $query .= " ORDER BY br.id DESC LIMIT $two,$one";

        $query = $this->db->query($query);
        return $query->result_array();
    }

    public function master_get_branch() {

        $query = "SELECT br.*,c.id as cid,c.name as city_name
							FROM branch_master as br 
							LEFT JOIN test_cities as c
							ON br.city = c.id 
							where br.status = 1  ORDER BY br.id DESC";

        $query = $this->db->query($query);
        return $query->result_array();
    }

    public function master_get_view($id) {

        $query = "SELECT br.*,c.id as cid,c.name as city_name
							FROM branch_master as br 
							LEFT JOIN test_cities as c
							ON br.city = c.id 
							where br.status = 1 AND br.id = '" . $id . "' GROUP BY br.id ORDER BY br.id DESC ";

        $query = $this->db->query($query);
        return $query->result_array();
    }

    public function num_row($branchid, $city = null) {
        $query = "SELECT br.*,c.id as cid,c.name as city_name
							FROM branch_master as br 
							LEFT JOIN test_cities as c
							ON br.city = c.id 
							where br.status = 1 AND br.id != ' '";
        if ($branchid != "") {
            $query .= " AND br.branch_name LIKE '%$branchid%'";
        }
        if ($city != NULL) {
            $query .= " AND c.id ='$city'";
        }
        $query .= " GROUP BY br.id ORDER BY br.id DESC ";
        $query = $this->db->query($query);
        return $query->num_rows();
    }

    public function master_get_insert($table, $data) {
        $this->db->insert($table, $data);
        return true;
        return $this->db->insert_id();
    }

    /*  function master_JobDoc_List($one, $two) 
      {

      $query = $this->db->query("SELECT
      job.id,	GROUP_CONCAT(test.test_name) testname,
      GROUP_CONCAT(pm.title) packagename,
      job.date,job.price,job.`payable_amount`,job.status,job.price,
      cust.mobile,cust.full_name, cust.id as customer_id
      FROM `job_master` as job
      LEFT JOIN job_test_list_master as jtl
      ON jtl.job_fk = job.id
      LEFT JOIN `customer_master` as cust
      ON cust.id = job.cust_fk
      LEFT JOIN `test_master` as test
      ON test.id = jtl.test_fk
      LEFT JOIN  book_package_master bpm
      ON  bpm.job_fk = job.id
      LEFT JOIN package_master as pm
      ON pm.id = bpm.package_fk where job.views = 1 AND job.id != ' '
      GROUP BY job.id ORDER BY job.id DESC LIMIT $two,$one" );

      return $query->result_array();

      } */
}

?>
