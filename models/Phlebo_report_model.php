<?php

Class Phlebo_report_model extends CI_Model {

    public function csv_report($start_date = null, $end_date = null, $phlebo = null, $city = null) {

        $old_date = explode("/", $start_date);
        $new_date = $old_date[2] . '-' . $old_date[1] . '-' . $old_date[0];

        $second_old_date = explode("/", $end_date);

        $second_new_date = $second_old_date[2] . '-' . $second_old_date[1] . '-' . $second_old_date[0];

        $qry = "SELECT paj.id AS sid,paj.*,		
      ph.name AS PhleboName,ph.test_city,		
      job.id,job.cust_fk,job.order_id,	
      cu.full_name,			
      sub.id,sub.start_time ,sub.end_time	
      FROM phlebo_assign_job AS paj 			
      LEFT JOIN phlebo_master AS ph ON ph.id = paj.phlebo_fk 			
      LEFT JOIN job_master AS job ON job.id = paj.job_fk 			
      LEFT JOIN customer_master AS cu ON cu.id = job.cust_fk  LEFT JOIN phlebo_time_slot AS sub ON sub.id = paj.time_fk
                     WHERE paj.status = 1";

        if ($start_date != '' && $end_date != '') {
            $qry .= " AND paj.date >= '" . $new_date . "' AND paj.date <= '" . $second_new_date . "'";
        }
        if ($phlebo != '') {
            $qry .= " AND paj.phlebo_fk = '$phlebo'";
        }
        if ($city != '') {
            $qry .= " AND ph.test_city = '$city'";
        }
        $qry .= " order by paj.job_fk ASC";

        $query = $this->db->query($qry);
        //print_r($query);die;
        $query1 = $query->result_array();
        return $query1;
    }

    function get_val($query1 = null) {
        $query = $this->db->query($query1);

        $data['user'] = $query->result_array();
        return $data['user'];
    }

    function report($start_date = null, $end_date = null, $city = null, $phlebo_name = null) {

        $date1 = explode("/", $start_date);
        $new_date = $date1[2] . '-' . $date1[1] . '-' . $date1[0];


        $date2 = explode("/", $end_date);
        $second_new_date = $date2[2] . '-' . $date2[1] . '-' . $date2[0];
        //print_r($date2);die;

        $qry = "select  paj.id AS sid, paj.*,ph.name as PhleboName,job.order_id,ph.test_city,job.id as jid,job.cust_fk,job.order_id,cu.full_name,sub.id,sub.start_time ,sub.end_time from phlebo_assign_job as paj left join phlebo_master as ph on ph.id = paj.phlebo_fk left join job_master as job on job.id = paj.job_fk left join customer_master as cu on cu.id = job.cust_fk
               LEFT JOIN phlebo_time_slot as sub on sub.id = paj.time_fk LEFT JOIN job_master_receiv_amount AS pra ON job.id = pra.job_fk
               where paj.status ='1' ";

        if ($start_date != "" || $end_date != "") {
            $qry .= " AND paj.date >= '" . $new_date . "' AND paj.date <= '" . $second_new_date . "' ";
        }

        if ($city != "") {
            $qry .= " AND ph.test_city = '$city'";
        }
        if ($phlebo_name != "") {
            $qry .= " AND paj.phlebo_fk = '$phlebo_name'";
        }

        $qry .= " order by paj.job_fk ASC";
        $query = $this->db->query($qry);

        $query1 = $query->result_array();
        return $query1;
    }

    function job_report($job_start_date = null, $job_end_date = null, $phlebo_name = null) {
        $old_date = explode("/", $job_start_date);
        $date1 = $old_date[2] . '-' . $old_date[1] . '-' . $old_date[0];
        $new_date = $date1 . " 00:00:00";

        $sub_old_date = explode("/", $job_end_date);
        $date2 = $sub_old_date[2] . '-' . $sub_old_date[1] . '-' . $sub_old_date[0];
        $sub_new_date = $date2 . " 23:59:59";

        $qry = "SELECT jmra.*,
                  jmra.id AS jid,
                  phl.name AS PhleboName,
                 
                  par.address,
                  job.order_id,
                  job.id as jid,
                  job.cust_fk,
                  cu.full_name FROM job_master_receiv_amount AS jmra 
                  LEFT JOIN phlebo_master AS phl ON jmra.phlebo_fk = phl.id 
                  LEFT JOIN job_master AS job ON job.id = jmra.job_fk
                  LEFT JOIN customer_master AS cu ON cu.id = job.cust_fk 
                  LEFT JOIN phlebo_assign_job AS par ON par.job_fk = job.id 
                  where jmra.status = 1 AND jmra.phlebo_fk  IS NOT NULL  ";


        if ($job_start_date != "") {
            $qry .= " AND jmra.createddate >='" . $new_date . "'";
        }
        if ($job_end_date != "") {
            $qry .= " AND jmra.createddate <='" . $sub_new_date . "'";
        }
        if ($phlebo_name != "") {
            $qry .= " AND jmra.phlebo_fk ='$phlebo_name'";
        }


        $qry .= " order by jmra.id ASC";

        $query = $this->db->query($qry);

        $query1 = $query->result_array();
        return $query1;
    }

}

?>
