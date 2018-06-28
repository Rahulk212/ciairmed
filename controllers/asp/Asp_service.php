<?php

class Asp_service extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->library('email');
        $this->load->model('asp/service_model');
        $this->load->helper('security');
        $this->load->helper('string');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST');
        header("Access-Control-Allow-Headers: X-Requested-With");
        $this->app_tarce();
    }

    function app_tarce() {
        $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $ipaddress = $_SERVER['REMOTE_ADDR'];
        $page = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}";
        if (!empty($_SERVER['QUERY_STRING'])) {
            $page = $_SERVER['QUERY_STRING'];
        } else {
            $page = "";
        }
        if (!empty($_POST)) {
            $user_post_data = $_POST;
        } else {
            $user_post_data = array();
        }
        $user_post_data = json_encode($user_post_data);
        $useragent = $_SERVER['HTTP_USER_AGENT'];
        $remotehost = @getHostByAddr($ipaddress);
        $user_info = json_encode(array("Ip" => $ipaddress, "Page" => $page, "UserAgent" => $useragent, "RemoteHost" => $remotehost));
        $user_track_data = array("url" => $actual_link, "user_details" => $user_info, "data" => $user_post_data, "createddate" => date("Y-m-d H:i:s"), "type" => "service");
        //print_R($user_track_data);
        $app_info = $this->service_model->master_fun_insert("user_track", $user_track_data);
        //return true;
    }

    public function generateToken($userId) {
        $static_str = 'ARMD';
        $currenttimeseconds = date("mdY_His");
        $token_id = $static_str . $userId . $currenttimeseconds;
        $data = array(
            'active_token' => sha1($token_id)
        );
        $this->service_model->master_fun_update1('admin_master', array("id" => $userId), $data);
        return sha1($token_id);
    }

    function check_token($user_id = null, $token = null, $time_stamp = null) {
        return 1;
        $date_a = new DateTime($time_stamp);
        $date_b = new DateTime(date("Y-m-d H:i:s"));
        $interval = date_diff($date_a, $date_b);
        $interval = explode(":", $interval->format('%h:%i:%s'));
        //print_r($interval); die();
        if ($interval[0] == 0 && $interval[1] == 0 && $interval[2] != 0 && !empty($time_stamp)) {
            $row = $this->service_model->num_row("admin_master", array("id" => $user_id, "active_token" => $token, "status" => "1", "type" => "5"));
            if ($row == 1) {
                return 1;
            } else {
                echo $this->json_data("0", "Invalid token.", "");
                die();
            }
        } else {
            echo $this->json_data("0", "Request time out.Try again.", "");
            die();
        }
    }

    function customer_list() {
        $data = $this->service_model->master_fun_get_tbl_val("customer_master", "full_name,id,mobile", array("active" => 1,"status" => 1), array("full_name","asc"));
        echo $this->json_data("1", "", $data);
    }
    
    function search_customer() {
        $mobile = $this->input->get_post("mobile");
        $data = $this->service_model->fetchdatarow("full_name,id,mobile,gender,email,dob,address,test_city", "customer_master", array("active" => 1,"status" => 1,"mobile" =>$mobile));
        
        echo $this->json_data("1", "", $data);
    }
    
    function get_customer() {
        $id = $this->input->get_post("cid");
        $data = $this->service_model->fetchdatarow("full_name,id,mobile,gender,email,dob,address,test_city", "customer_master", array("active" => 1,"status" => 1,"id" =>$id));
        
        echo $this->json_data("1", "", $data);
    }
    
    function city_list() {
        $data = $this->service_model->master_fun_get_tbl_val("test_cities", "name,id,city_fk", array("status" => 1), array("id","asc"));
        echo $this->json_data("1", "", $data);
    }
    
    function branch_list() {
        $data = $this->service_model->master_fun_get_tbl_val("branch_master", "branch_name,id", array("status" => 1), array("id","asc"));
        echo $this->json_data("1", "", $data);
    }
    
    function get_branch() {
        $city = $this->input->get_post("city_id");
        $data = $this->service_model->master_fun_get_tbl_val("branch_master", "branch_name,id", array("status" => 1,"city" =>$city), array("id","asc"));
        echo $this->json_data("1", "", $data);
    }
    
    function get_doctors() {
        $city = $this->input->get_post("city_id");
        $test_city = $this->service_model->fetchdatarow("city_fk", "test_cities", array("status" => 1,"id" =>$city));
        $data = $this->service_model->master_fun_get_tbl_val("doctor_master", "full_name,id,mobile", array("status" => 1,"city" => $test_city->city_fk), array("id","asc"));
        echo $this->json_data("1", "", $data);
    }
    
    function source_list() {
        $data = $this->service_model->master_fun_get_tbl_val("source_master", "name,id", array("status" => 1), array("id","asc"));
        echo $this->json_data("1", "", $data);
    }
    
    function sample_from_list() {
        $data = $this->service_model->master_fun_get_tbl_val("sample_from", "name,id", array("status" => 1), array("id","asc"));
        echo $this->json_data("1", "", $data);
    }
    
    function test_package_list() {
        $city = $this->input->get_post("city_id");
        $test = $this->service_model->test_list($city);
        $package = $this->service_model->package_list($city);
        $data = array();
        $temp = array();
        foreach ($test as $list) {
            $temp['id']="t-".$list->id;
            $temp['name']=$list->test_name." (Rs.".$list->price.")";
            $temp['price']=$list->price;
            array_push($data,$temp);
        }
        $temp1 = array();
        foreach ($package as $list) {
            $temp1['id']="p-".$list->id;
            $temp1['name']=$list->title." (Rs.".$list->d_price.")";
            $temp1['price']=$list->d_price;
            array_push($data,$temp1);
        }
        echo $this->json_data("1", "", $data);
    }

    function json_data($status, $error_msg, $data = NULL) {
        if ($data == NULL) {
            $data = array();
        }
        $final = array("status" => $status, "error_msg" => $error_msg, "data" => $data);
        return str_replace("null", '" "', json_encode($final));
    }

}
