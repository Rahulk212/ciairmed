<?php
class service_model6 extends CI_Model {
    public function __construct() {
        $this->load->database();
    }
public function master_num_rows($table, $condition) {
        $query1 = $this->db->get_where($table, $condition);
        return $query1->num_rows();
    }
    function login($email, $password) {

        $this->db->select('id');
        $this->db->from('sales_user_master');
        $this->db->where('email', $email);
        $this->db->where('password', $password);
        $this->db->where('status', '1');
        $query = $this->db->get();
        $query1 = $query->row();
        // print_r($query1);
        $id = $query1->id;
        if ($id) {
            $res = $this->clock($id);
            // print_r($res);die();
            if ($res) {
                //$result = $this->checkin($id);
                return $res;
            } else {
                return $id;
            }
        } else {

            return $id;
        }

    }
	public function insertQ($data) {
        $this->db->insert('sales_speciality_master', $data);
        return $this->db->insert_id();
    }
	public function insert_master($data,$table) {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }
    function getlocation($id,$date){
          $query = "select * from schedule where user_fk = $id and date = date_format('$date','%Y-%m-%d') AND schedule.`id` NOT IN (SELECT sales_checkin.`schedule_fk` from sales_checkin WHERE sales_checkin.`user_fk` = $id)";
         $query = $this->db->query($query);
         $res = $query->result_array();
         return $res;
    }
    function gettestcity(){
          $query = "select * from test_cities where status = 1";
         $query = $this->db->query($query);
         $res = $query->result_array();
         return $res;
    }
	function getspeciality(){
          $query = "select name,id from sales_speciality_master where status='1' order by name";
         $query = $this->db->query($query);
         $res = $query->result_array();
         return $res;
    }
    public function clock($id) {
        $this->db->select("MAX(id) AS id");

        $query = $this->db->get_where("sales_timer", array("user_fk" => $id));
        $res = $query->row();
       // print_R($res);
        $query2 = $this->db->get_where('sales_timer', array("id" => $res->id));
        $res = $query2->row();
        return $res;
    }
    function checkin($id){
         $this->db->select("MAX(id) AS id");

        $query = $this->db->get_where("sales_checkin", array("user_fk" => $id));
        $res = $query->row();

        $query2 = $this->db->get_where('sales_checkin', array("id" => $res->id,"checkout " => NULL));
        $res = $query2->row();
        return $res;
    }
    function insertCheckin($data){
         $this->db->insert('sales_checkin', $data);
         $insert_id = $this->db->insert_id();

        return $insert_id;
         
    }
    function code($code) {
        $this->db->select('id');
        $this->db->from('sales_user_master');
        $this->db->where('code', $code);
        $this->db->where('status', '1');
        $query = $this->db->get();
        $query1 = $query->row();

        $id = $query1->id;
        if ($query->num_rows() == 1) {
            $query = $this->db->query("select max(id) as maxid from sales_timer where user_fk='$id'");
            $data = $query->row();
            $maxid = $data->maxid;
            $query1 = $this->db->query("select sales_sales_timer.*,sales_sales_user_master.first_name,sales_sales_user_master.last_name,sales_sales_user_master.add_edit_user,sales_sales_user_master.add_edit_project,sales_sales_user_master.add_edit_task,sales_sales_user_master.own_log,sales_sales_user_master.edit_other_time,sales_sales_user_master.real_time_log,sales_sales_user_master.custome_time_log,sales_sales_user_master.manage_other_user_access,project.id as pid,task.id as tid,project.name,task.task_name,sales_sales_user_master.id as uid from sales_timer JOIN sales_user_master ON sales_sales_timer.user_fk=sales_sales_user_master.id JOIN project ON project.id=sales_sales_timer.project_id JOIN task ON task.id=sales_sales_timer.task_id where sales_timer.id='$maxid'");
            $data1 = $query1->row();
            if ($data1) {
                return $data1;
            } else {
                $query1 = $this->db->query("select *,id as uid from sales_user_master where id='$id'");
                $data1 = $query1->row();
                return $data1;
            }
        } else {
            return false;
        }
    }
    function timeadd($data) {
        $this->db->insert('sales_timer', $data);
        return $this->db->insert_id();
    }
    function time_status($id) {
        $query1 = $this->db->query("select MAX(id) as id from sales_timer where user_fk='$id' AND stop_date IS NULL");
        $user = $query1->result_array();
        foreach ($user as $user1) {
           
        }
        if ($user1 != NULL) {
            return $user1['id'];
        } else {
            return false;
        }
    }
    function check_in_status($id){
        $query1 = $this->db->query("select MAX(id) as id from sales_checkin where user_fk='$id' AND checkout IS NULL");
        $user = $query1->result_array();
		
        foreach ($user as $user1) {
            
        }
        if ($user1 != NULL) {
            return $user1['id'];
        } else {
            return false;
        }
    }
    function get_check_in($id){
         $query1 = $this->db->query("select * from sales_checkin where id='$id'");
        $userlist = $query1->row_array();
//        echo"<pre>";
//        print_R($userlist);
//        die();
        return $userlist;
    }
    function get_start_time($id) {

        $query1 = $this->db->query("select * from sales_timer where id='$id'");
        $userlist = $query1->row_array();
//        echo"<pre>";
//        print_R($userlist);
//        die();
        return $userlist;
    }

    public function updatetime($did, $data) {
//        print_r($data);
//        echo $did;    
//        die();

        $this->db->where('id', $did);
        $this->db->update('sales_timer', $data);
        return 1;
    }
    public function updatecheckin($did, $data){
        $this->db->where('id', $did);
        $this->db->update('sales_checkin', $data);
        return 1;
    }
  

    function gettime($did) {
        $query1 = $this->db->query("select sales_timer.*,task.task_name,project.name from sales_timer JOIN project ON project.id=sales_timer.project_id JOIN task ON task.id=sales_timer.task_id where sales_timer.user_fk='$did' AND stop_date !='' ORDER BY id DESC");
        $data['user'] = $query1->result_array();
        return $data['user'];
    }

   

    function time_detail($id) {
        if ($id != NULL) {
            $query1 = $this->db->query("select sales_timer.*,task.task_name,project.name,sales_sales_user_master.first_name,sales_sales_user_master.last_name from sales_timer JOIN project ON project.id=sales_timer.project_id JOIN sales_user_master ON sales_sales_user_master.id=sales_timer.user_fk JOIN task ON task.id=sales_timer.task_id  where sales_timer.id='$id' ");
            $user = $query1->result_array();
            return $user;
        }
    }

   
    public function project_time($pid, $data) {

        $query1 = $this->db->query("SELECT sales_timer.*,sales_sales_user_master.first_name,sales_sales_user_master.last_name,project.name FROM sales_timer JOIN sales_user_master ON sales_sales_user_master.id=sales_timer.user_fk JOIN project ON project.id=sales_timer.project_id WHERE project_id='$pid' AND stop_date!='' AND start_date > DATE(NOW()) - INTERVAL 7 DAY AND FIND_IN_SET(sales_timer.user_fk,'$data')  ORDER BY sales_sales_user_master.id");
//        echo "SELECT sales_timer.*,sales_sales_user_master.first_name,sales_sales_user_master.last_name,project.name  FROM sales_timer JOIN sales_user_master ON sales_sales_user_master.id=sales_timer.user_fk JOIN project ON project.id=sales_timer.project_id WHERE project_id='$pid' AND stop_date!='' AND start_date > DATE(NOW()) - INTERVAL 7 DAY AND FIND_IN_SET(sales_timer.user_fk,'$data')  ORDER BY sales_sales_user_master.id";
        $project = $query1->result_array();
//        print_r($project);
        return $project;
    }

   
    public function forgot($email) {
        //$this->db->where('email', $email);
        $query = $this->db->get_where('sales_user_master', array("status" => 1, "email" => $email));
        $res = $query->num_rows();
        $id = $query->row();
        //  print_r($id->id);
        return $id->id;
    }

    public function getSchedule($id) {

        $query = $this->db->query("SELECT schedule.*,DATE_FORMAT(game_time,'%h:%i %p') AS time1 FROM schedule WHERE date>=DATE_FORMAT(CURDATE(),'%Y-%m-%d') AND user_fk = $id AND schedule.status=1  AND schedule.`id` NOT IN (SELECT sales_checkin.`schedule_fk` from sales_checkin WHERE sales_checkin.`user_fk` = $id)");
        $res = $query->result_array();
        // print_r($res);die();
        return $res;
    }

    
    public function getSchedule1($id,$date) {

        $query = $this->db->query("SELECT schedule.*,DATE_FORMAT(game_time,'%h:%i %p') AS time1 FROM schedule WHERE date >= '$date' AND user_fk = $id AND schedule.status=1 AND schedule.`id` NOT IN (SELECT sales_checkin.`schedule_fk` from sales_checkin WHERE sales_checkin.`user_fk` = $id)");
        $res = $query->result_array();
        // print_r($res);die();
        return $res;
    }
    
    
    public function getMessages($id) {
        $query = "SELECT
    `messages`.`id`
    , `messages`.`to`
    , `messages`.`from`
    , `messages`.`text`
    ,CONCAT(UCASE(LEFT(subject, 1)), SUBSTRING(subject, 2)) AS subject
    , DATE_FORMAT(`messages`.`datetime`,'%y-%m-%d') As date
     , DATE_FORMAT(`messages`.`datetime`,'%h:%i') AS time
    , `sales_sales_user_master`.`first_name` AS to_first_name
    , `sales_sales_user_master`.`last_name` AS to_last_name
    , `sales_user_master_1`.`first_name` AS from_first_name
    , `sales_user_master_1`.`last_name` AS from_last_name
FROM
    `messages`
    INNER JOIN `sales_user_master` 
        ON (`messages`.`to` = `sales_sales_user_master`.`id`)
    INNER JOIN `sales_user_master` AS `sales_user_master_1`
        ON (`messages`.`from` = `sales_user_master_1`.`id`) WHERE (messages.`to` =$id OR messages.`from` = $id) AND messages.mob_status=1 order by messages.id desc ";
        $query1 = $this->db->query($query);
        $res = $query1->result_array();
        return $res;
    }

    public function insertMessage($data) {
        $this->db->insert('messages', $data);
        $insert_id = $this->db->insert_id();

        return $insert_id;
    }
    
    public function getUser($id){
        $query = $this->db->get_where("sales_user_master",array("id"=>$id));
        $res = $query->row();
        return $res;
    }
    public function delMessage($id,$data){
           $this->db->where('id', $id);
   $this->db->update('messages', $data);
    //print_r($data);    
    //die();    
    return 1;    
    }
    
    public function delallMessage($id,$data){
      // echo $uqery = "upadte messages set status=0 where from = $id or to = $id";
        
        $this->db->where('to', $id);
         $this->db->or_where('from', $id);
         //$this->db->where('from', $id);
   $this->db->update('messages', $data,array("from"=>$id));
    //print_r($data);    
    //die();    
    return 1;    
    }
    
    public function checkDevice($regId){
        $query = $this->db->get_where("sales_registerdevice",array("device_uuid"=>$regId));
        $number = $query->num_rows();
        return $number;
    }
    public function updateDevice($regId,$data){
       // echo $regId;
        $this->db->where('device_uuid', $regId);
   $this->db->update('sales_registerdevice', $data);
   return 1;
    }
     public function insertDevice($data){
        //$this->db->where('device_uuid', $regId);
   $this->db->insert('sales_registerdevice', $data);
   $id = $this->db->insert_id();
   return $id;
    }
    
    public function logout($device,$data){
         $query = $this->db->update('sales_registerdevice', $data,array("device_uuid"=>$device));
        if($query){
             return 1;
        }
        else{
            return 0;
        }
        
    }
    public function updateclock($data1,$userid){
         $this->db->where("stop_time IS NULL");
        $query = $this->db->update('timer', $data1,array("user_fk"=>$userid));
        if($query){
             return 1;
        }
        else{
            return 0;
        }
    }
    public function updateclockout($data,$userid){
        $this->db->where("stop_time IS NULL");
        $query = $this->db->update('timer', $data,array("user_fk"=>$userid));
        if($query){
             return 1;
        }
        else{
            return 0;
        }
    }
   function user_status($id) {
        $query1 = $this->db->query("select id from sales_user_master where id='$id' AND status='1'");
        return $query1->num_rows();
    }
	function get_user_id($id) {
        $query1 = $this->db->query("select user_fk FROM sales_timer where id='$id'");
        return $query1->row();
    }
    /////////////////////
	function doctor_num($mobile,$email){
		$query1 = $this->db->query("SELECT * FROM doctor_master WHERE (mobile = '$mobile' OR email = '$email') AND STATUS ='1'");
        return $query1->num_rows();
	}
	function lab_num($mobile,$email){
		$query1 = $this->db->query("SELECT * FROM sales_laboratory WHERE (mobile = '$mobile' OR email = '$email') AND STATUS ='1'");
        return $query1->num_rows();
	}

function doctor_num2($mobile,$email){
		$query1 = $this->db->query("SELECT * FROM doctor_master WHERE (mobile = '$mobile' OR email = '$email') and !isnull(password)  AND STATUS ='1'");
        return $query1->num_rows();
	}
	function doctor_num_monile($mobile){
		$query1 = $this->db->query("SELECT * FROM doctor_master WHERE (mobile = '$mobile') AND STATUS ='1'");
        return $query1->num_rows();
	}
	function doctor_num_monile2($mobile){
		$query1 = $this->db->query("SELECT * FROM doctor_master WHERE (mobile = '$mobile') and !isnull(password) AND STATUS ='1'");
        return $query1->num_rows();
	}
	public function master_fun_insert($table, $data) {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }
	function getdoctorlist($mobile,$city=""){
		$cnd_str="";
		if($city != ""){
			$cnd_str = " AND dm.city = '$city' ";
		}
         //$query = "SELECT id,full_name as name,mobile,email,address,city,state,speciality,gender,area,hospital_name,alternate_number FROM doctor_master WHERE (mobile = '$mobile' OR email = '$mobile') AND STATUS ='1' $cnd LIMIT 1";
		 $query = "SELECT dm.id, dm.full_name AS name, dm.mobile, dm.email, dm.address, dm.city, dm.state, dm.speciality, dm.gender, dm.area, dm.hospital_name, dm.alternate_number , sales_area_master.`name` AS area_name FROM doctor_master dm LEFT JOIN sales_area_master ON sales_area_master.id = dm.`area` WHERE (dm.mobile LIKE '%$mobile%' OR dm.full_name LIKE '%$mobile%')  AND dm.STATUS ='1' $cnd_str LIMIT 1";
         $query = $this->db->query($query);
         $res = $query->result();
         return $res;
    }
	function getlablist($mobile,$city=""){
		$cnd_str="";
		if($city != ""){
			$cnd_str = " AND dm.city = '$city' ";
		}
		 $query = "SELECT dm.id, dm.full_name AS name, dm.mobile, dm.email, dm.address, dm.city, dm.state,  dm.area, dm.alternate_number , sales_area_master.`name` AS area_name FROM sales_laboratory dm LEFT JOIN sales_area_master ON sales_area_master.id = dm.`area` WHERE (dm.mobile LIKE '%$mobile%' OR dm.full_name LIKE '%$mobile%')  AND dm.STATUS ='1' $cnd_str LIMIT 1";
         $query = $this->db->query($query);
         $res = $query->result();
         return $res;
    }
	function getdoctor_list($mobile,$state="",$city=""){
		$cnd = "";
		if($city != ""){
			$cnd .= " AND city='$city' ";
		}
		if($state != ""){
			$cnd .= " AND state='$state' ";
		} 
         $query = "SELECT dm.id, dm.full_name AS name, dm.mobile, dm.email, dm.address, dm.city, dm.state, dm.speciality, dm.gender, dm.area, dm.hospital_name, dm.alternate_number , sales_area_master.`name` AS area_name FROM doctor_master dm LEFT JOIN sales_area_master ON sales_area_master.id = dm.`area` WHERE (dm.mobile LIKE '%$mobile%' OR dm.full_name LIKE '%$mobile%')  AND dm.STATUS ='1' $cnd";
         $query = $this->db->query($query);
         $res = $query->result();
         return $res;
    }
	function getlab_list($mobile,$state="",$city=""){
		$cnd = "";
		if($city != ""){
			$cnd .= " AND city='$city' ";
		}
		if($state != ""){
			$cnd .= " AND state='$state' ";
		} 
         $query = "SELECT dm.id, dm.full_name AS name, dm.mobile, dm.email, dm.address, dm.city, dm.state, dm.area, dm.alternate_number , sales_area_master.`name` AS area_name FROM sales_laboratory dm LEFT JOIN sales_area_master ON sales_area_master.id = dm.`area` WHERE (dm.mobile LIKE '%$mobile%' OR dm.full_name LIKE '%$mobile%')  AND dm.STATUS ='1' $cnd";
         $query = $this->db->query($query);
         $res = $query->result();
         return $res;
    }
	public function master_fun_update($tablename, $cid, $data) {
        $this->db->where('id', $cid);
        $this->db->update($tablename, $data);
        return 1;
    }
	function getcity($id){
          $query = "select city_name as name,id from city where status='1' and state_fk = '$id' order by city_name ";
         $query = $this->db->query($query);
         $res = $query->result_array();
         return $res;
    }
	function getallcity(){
          $query = "select city_name as name,id,state_fk from city where status='1' order by city_name ";
         $query = $this->db->query($query);
         $res = $query->result_array();
         return $res;
    }
	function getstate(){
          $query = "select state_name as name,id from state where status='1' order by state_name";
         $query = $this->db->query($query);
         $res = $query->result_array();
         return $res;
    }
	function getarea($id=""){
		$temp ="";
		if($id != ""){
			$temp = " and city_fk = '$id'";
		}
          $query = "select name,id from sales_area_master where status='1' group by name order by name";
         $query = $this->db->query($query);
         $res = $query->result_array();
         return $res;
    }
	function getpitch(){
          $query = "select name,id from sales_pitch where status='1' ";
         $query = $this->db->query($query);
         $res = $query->result_array();
         return $res;
    }
	function getwhy(){
          $query = "select name,id from sales_why where status='1' ";
         $query = $this->db->query($query);
         $res = $query->result_array();
         return $res;
    }
	public function insert($tbl,$data) {
        $this->db->insert($tbl, $data);
        return $this->db->insert_id();
    }
	public function checkinreport($id){
	/*	$query = "SELECT 
sales_checkin.id,
doctor_master.`id` as did,
		DATE_FORMAT(checkin, '%d-%m-%Y') AS DATE,
  doctor_master.full_name AS DOCTOR,
  GROUP_CONCAT(sales_speciality_master.`name`) AS SPECIALITY,
  doctor_master.`mobile` AS DOCTORNO,
  doctor_master.`address` AS DOTORADDRESS,
  doctor_master.hospital_name AS HOSPITAL_NURSING,
  sales_area_master.`name`,
CASE meeting
   WHEN '0'  THEN 'N'
   WHEN '1'  THEN 'Y'
END AS MEETING,
  sales_why.`name` AS WHY,
  ref_no,
  remark AS REMARK,
  cut_offer AS CUTOFFER,
  current_cut AS CURRENTCUT,
  DATE_FORMAT(checkin, '%d-%m-%Y %h:%i:%p') AS CHECKIN,
  DATE_FORMAT(checkout, '%d-%m-%Y %h:%i:%p') AS CHECKOUT,
  TIMEDIFF(checkout,checkin) AS TIME,
  sales_checkin.address AS ADDRESS_As_Per_Location,
  longitude,
  latitude
FROM
  sales_checkin 
  LEFT JOIN doctor_master 
  ON doctor_master.`id`=sales_checkin.`doctor_id`
  LEFT JOIN sales_speciality_master
    ON FIND_IN_SET(sales_speciality_master.`id`, doctor_master.`speciality`)
  LEFT JOIN sales_area_master
    ON sales_area_master.id=doctor_master.`area`  
  LEFT JOIN sales_why
    ON sales_why.`id`=sales_checkin.`why`
WHERE clock_in_fk IN($id) GROUP BY sales_checkin.id";*/
$query = "SELECT 
  sc.id,sc.`type`,dm.`id` AS did,DATE_FORMAT(sc.checkin, '%d-%m-%Y') AS DATE,dm.full_name AS DOCTOR,GROUP_CONCAT(ssm.`name`) AS SPECIALITY,
  dm.`mobile` AS DOCTORNO,dm.`address` AS DOTORADDRESS,dm.hospital_name AS HOSPITAL_NURSING, samd.`name` AS doc_area,
  CASE
    sc.meeting 
    WHEN '0' 
    THEN 'N' 
    WHEN '1' 
    THEN 'Y' 
  END AS MEETING,
  sw.`name` AS WHY,sc.ref_no,sc.remark AS REMARK,sc.cut_offer AS CUTOFFER,sc.current_cut AS CURRENTCUT,
  DATE_FORMAT(sc.checkin, '%d-%m-%Y %h:%i:%p') AS CHECKIN,DATE_FORMAT(sc.checkout,'%d-%m-%Y %h:%i:%p') AS CHECKOUT,TIMEDIFF(sc.checkout, checkin) AS TIME,
  sc.address AS ADDRESS_As_Per_Location,sc.longitude,sc.latitude,sl.`full_name` AS lab_name,saml.name AS lab_area,sl.mobile AS lab_mobile,sl.address AS lab_address
FROM
  sales_checkin sc 
  LEFT JOIN doctor_master dm 
    ON dm.`id` = sc.`doctor_id` 
  LEFT JOIN sales_laboratory sl 
    ON sl.id = sc.`doctor_id` 
  LEFT JOIN sales_speciality_master ssm 
    ON FIND_IN_SET(ssm.`id`, dm.`speciality`) 
  LEFT JOIN sales_area_master samd 
    ON samd.id = dm.`area` 
  LEFT JOIN sales_area_master saml 
    ON saml.id = sl.`area` 
  LEFT JOIN sales_why sw 
    ON sw.`id` = sc.`why` 
WHERE sc.clock_in_fk IN ($id) 
GROUP BY sc.id ";
		$query = $this->db->query($query);
		$res = $query->result();
		return $res;
		
	}
	public function get_pitch_list($id){
		$query = "SELECT sales_pitch.`name`,sales_pitch_checkin.`interested` FROM sales_pitch_checkin 
JOIN sales_pitch ON sales_pitch.`id`=sales_pitch_checkin.`pitch_id`
WHERE 
checkin_id='$id'";
		$query = $this->db->query($query);
		$res = $query->result();
		return $res;
	}
	function row_delete($id,$fld,$tbl)
	{
		$this->db->where($fld, $id);
		$this->db->delete($tbl); 
		return '1';
	}
	function master_fun_get_tbl_val($dtatabase,$select, $condition) {
        $this->db->select($select);
        $query = $this->db->get_where($dtatabase, $condition);
        $data['user'] = $query->result();
        return $data['user'];
    }
		public function num_row($table,$condition){
        $query= $this->db->get_where($table,$condition);
        return $query->num_rows(); 
    }
	public function get_todo_list($user,$date){
		 //$sql = "SELECT dm.`full_name`,dm.`mobile`,sc.`checkin` as test,TIME_FORMAT( sc.`checkin`, '%h:%i:%p' ) AS checkin,TIME_FORMAT( sc.`checkout`, '%h:%i:%p' ) AS checkout FROM sales_checkin sc JOIN doctor_master dm ON dm.id=sc.`doctor_id`  WHERE sc.checkin >= '$date' AND sc.user_fk = '$user'";
		 $sql = "SELECT sc.id,sc.type,dm.`full_name`,dm.`mobile`,sl.`full_name` AS lab_name,sl.`mobile` AS labmobil,sc.`checkin` as test,TIME_FORMAT( sc.`checkin`, '%h:%i:%p' ) AS checkin,TIME_FORMAT( sc.`checkout`, '%h:%i:%p' ) AS checkout FROM sales_checkin sc LEFT JOIN doctor_master dm ON dm.id=sc.`doctor_id` LEFT JOIN sales_laboratory sl ON sl.id = sc.`doctor_id`  WHERE DATE_FORMAT(sc.checkin,'%Y-%m-%d') = '$date' AND sc.user_fk = '$user' GROUP BY sc.id ORDER BY sc.`id` desc";
		$query = $this->db->query($sql);
		$res = $query->result();
		return $res;
	}
	
	
	public function getpratikdata(){
		 $sql = " 
     SELECT sales_timer.id FROM sales_timer
LEFT JOIN       `sales_checkin` ON sales_checkin.`clock_in_fk` = `sales_timer`.`id`
LEFT JOIN `doctor_master` ON `doctor_master`.id=`sales_checkin`.`doctor_id` 
      WHERE sales_timer.user_fk=20  AND sales_timer.start_date >=' 2017-04-01' AND sales_timer.start_date <='2017-05-20' AND doctor_master.`city`=345";
		$query = $this->db->query($sql);
		$res = $query->result();
		return $res;
	}
	public function getpratikdata_new($to="",$from="",$user="",$city=""){
		 $sql = " 
     SELECT sales_timer.id FROM sales_timer
LEFT JOIN       `sales_checkin` ON sales_checkin.`clock_in_fk` = `sales_timer`.`id`
LEFT JOIN `doctor_master` ON `doctor_master`.id=`sales_checkin`.`doctor_id` 
      WHERE ";
		if($to != "" || $from!="" || $user!="" || $city!=""){
			$temp = "1";
			if($to != ""){
				$temp = "0";
				$sql .= " sales_timer.start_date >='$to' ";
			}
			if($from != ""){
				if( $temp == "0" ){
					$sql .=" AND ";
				}
				$temp = "0";
				$sql .= " sales_timer.start_date <='$from' ";
			}
			if($user != ""){
				if( $temp == "0" ){
					$sql .=" AND ";
				}
				$temp = "0";
				$sql .= " sales_timer.user_fk ='$user' ";
			}
			if($city != ""){
				if( $temp == "0" ){
					$sql .=" AND ";
				}
				$sql .= " doctor_master.`city` ='$city' ";
			}
		}
		$query = $this->db->query($sql);
		$res = $query->result();
		return $res;
	}
	public function checkinreport_new($id,$city=""){
		$query = "SELECT 
sales_checkin.id,
doctor_master.`id` as did,
		DATE_FORMAT(checkin, '%d-%m-%Y') AS DATE,
  doctor_master.full_name AS DOCTOR,
  GROUP_CONCAT(sales_speciality_master.`name`) AS SPECIALITY,
  doctor_master.`mobile` AS DOCTORNO,
  doctor_master.`address` AS DOTORADDRESS,
  doctor_master.hospital_name AS HOSPITAL_NURSING,
  sales_area_master.`name`,
CASE meeting
   WHEN '0'  THEN 'N'
   WHEN '1'  THEN 'Y'
END AS MEETING,
  sales_why.`name` AS WHY,
  ref_no,
  remark AS REMARK,
  cut_offer AS CUTOFFER,
  current_cut AS CURRENTCUT,
  DATE_FORMAT(checkin, '%d-%m-%Y %h:%i:%p') AS CHECKIN,
  DATE_FORMAT(checkout, '%d-%m-%Y %h:%i:%p') AS CHECKOUT,
  TIMEDIFF(checkout,checkin) AS TIME,
  sales_checkin.address AS ADDRESS_As_Per_Location,
  sales_checkin.longitude,
  sales_checkin.latitude,
  sales_user_master.`first_name`,
  sales_user_master.`last_name`
FROM
  sales_checkin 
  LEFT JOIN doctor_master 
	ON doctor_master.`id`=sales_checkin.`doctor_id`
  LEFT JOIN sales_speciality_master
    ON FIND_IN_SET(sales_speciality_master.`id`, doctor_master.`speciality`)
  LEFT JOIN sales_area_master
    ON sales_area_master.id=doctor_master.`area`  
  LEFT JOIN sales_why
    ON sales_why.`id`=sales_checkin.`why`
  JOIN sales_timer
ON sales_timer.id=sales_checkin.`clock_in_fk`
JOIN sales_user_master
ON sales_user_master.id=sales_timer.`user_fk`
WHERE clock_in_fk IN($id) ";
if($city != ""){
	$query .= " doctor_master.`city` ='$city' ";
}
$query .=" GROUP BY  sales_checkin.id order by sales_user_master.id ";
		$query = $this->db->query($query);
		$res = $query->result();
		return $res;
		
	}
	function error_list(){
		$sql ="select * from sales_app_report order by id desc";
		$query = $this->db->query($sql);
		$res = $query->result();
		return $res;
	}
	function getarea_list($area){
		$cnd= "";
		if($area != ""){
			$cnd = " AND name LIKE '$area%' ";
		}
         $query = "select name,id from sales_area_master where status='1' $cnd order by name";
         $query = $this->db->query($query);
         $res = $query->result_array();
         return $res;
    }
	function getfindcity($city){
          $query = "select city_name as name,id,state_fk from city where status='1' AND city_name LIKE '%$city%' order by city_name ";
         $query = $this->db->query($query);
         $res = $query->result_array();
         return $res;
    }
	function getfindtest($test){
          $query = "select test_name,TEST_CODE,id from test_master where status='1' AND test_name LIKE '%$test%' order by test_name ";
         $query = $this->db->query($query);
         $res = $query->result_array();
         return $res;
    }
}
