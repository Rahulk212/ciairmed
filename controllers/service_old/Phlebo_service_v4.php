<?php

class Phlebo_service_v4 extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->library('email');
        $this->load->model('service_model');
        $this->load->model('user_master_model');
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

    /*    function get_phlebo_schedule() {
      $usercode = $this->input->get_post('bdate');
      $type = $this->input->get_post('type');
      $cityfk = $this->input->get_post('city_id');
      if ($type) {
      $usercode1 = explode("/", $usercode);
      $usercode = $usercode1[2] . "-" . $usercode1[1] . "-" . $usercode1[0];
      } else {
      $usercode = date('Y-m-d', strtotime(str_replace('-', '/', $usercode)));
      }
      if ($cityfk == "4" || $cityfk == "5") {
      if ($usercode == date("Y-m-d")) {
      echo $this->json_data("0", "Time slot unavailable.", "");
      die();
      }
      }
      $day_of_the_week = date('w', strtotime($usercode));
      $row = $this->service_model->phlebo_time_slot_on_day($day_of_the_week, $usercode);
      if ($usercode >= date("Y-m-d")) {
      if (!empty($row)) {
      $new_ary = array();
      $phone_array = array();
      $cnt = 0;
      foreach ($row as $key) {
      $phlebo_count = $this->service_model->master_fun_get_tbl_val("phlebo_master", array("status" => "1"), array("id", "desc"));
      $today_booking = $this->service_model->master_fun_get_tbl_val("phlebo_assign_job", array("status" => "1", "time_fk" => $key["time_slot_fk"], "date" => $usercode), array("id", "desc"));
      if (count($phlebo_count) > count($today_booking)) {
      $key["booking_status"] = "Available";
      } else {
      $key["booking_status"] = "Unavailable";
      }
      if ($usercode == date("Y-m-d") && $key["real_time"] > date("H:i:s")) {
      if ($cnt != 0) {
      $new_ary[] = $key;
      }
      if (!empty($type)) {
      if (count($phlebo_count) > count($today_booking)) {
      if ($cnt != 0) {
      $phone_array[] = $key;
      }
      }
      }
      $cnt++;
      } else if ($usercode != date("Y-m-d")) {
      //  if ($cnt != 0) {
      $new_ary[] = $key;
      // }
      if (!empty($type)) {
      if ($cityfk == "4" || $cityfk == "5") {
      $phone_array[] = $key;
      } else {
      if (count($phlebo_count) > count($today_booking)) {
      if ($cnt != 0) {
      $phone_array[] = $key;
      }
      }
      }
      }
      $cnt++;
      }
      }
      if (!empty($new_ary)) {
      if (empty($type)) {
      echo $this->json_data("1", "", $new_ary);
      } else {
      if (!empty($phone_array)) {
      echo $this->json_data("1", "", $phone_array);
      } else {
      echo $this->json_data("0", "Time slot unavailable.", "");
      }
      }
      } else {
      echo $this->json_data("0", "Time slot unavailable.", "");
      }
      } else {
      echo $this->json_data("0", "Time slot unavailable.", "");
      }
      } else {
      echo $this->json_data("0", "Invalid date.", "");
      }
      } */

    function get_phlebo_schedule() {
        $usercode = $this->input->get_post('bdate');
        $type = $this->input->get_post('type');
        $cityfk = $this->input->get_post('city_id');
        if ($type) {
            $usercode1 = explode("/", $usercode);
            $usercode = $usercode1[2] . "-" . $usercode1[1] . "-" . $usercode1[0];
        } else {
            $usercode = date('Y-m-d', strtotime(str_replace('-', '/', $usercode)));
        }
        if ($cityfk == "4" || $cityfk == "5") {
            if ($usercode == date("Y-m-d")) {
                echo $this->json_data("0", "Time slot unavailable.", "");
                die();
            }
        }
        $bkng_date = null;
        $user_time = date("H:i");
        //$user_time = "23:00";
        if ($user_time > "22:59" && $user_time < "23:59") {
            $bkng_date = '1';
        }
        if ($user_time > "00:00" && $user_time < "06:00") {
            $bkng_date = '0';
        }
        $cut_slot_date = date('Y-m-d', strtotime(date("Y-m-d") . ' + ' . $bkng_date . ' days'));
        $day_of_the_week = date('w', strtotime($usercode));
        $row = $this->service_model->phlebo_time_slot_on_day($day_of_the_week, $usercode);
        if ($usercode >= date("Y-m-d")) {
            if (!empty($row)) {
                $new_ary = array();
                $phone_array = array();
                $cnt = 0;
                foreach ($row as $key) {
                    $phlebo_count = $this->service_model->master_fun_get_tbl_val("phlebo_master", array("status" => "1"), array("id", "desc"));
                    $today_booking = $this->service_model->master_fun_get_tbl_val("phlebo_assign_job", array("status" => "1", "time_fk" => $key["time_slot_fk"], "date" => $usercode), array("id", "desc"));
                    if (count($phlebo_count) > count($today_booking)) {
                        $key["booking_status"] = "Available";
                    } else {
                        $key["booking_status"] = "Unavailable";
                    }
                    if ($usercode == date("Y-m-d") && $key["real_time"] > date("H:i:s")) {
                        if ($cnt != 0) {
                            $new_ary[] = $key;
                        }
                        if (!empty($type)) {
                            if (count($phlebo_count) > count($today_booking)) {
                                if ($cnt != 0) {
                                    $phone_array[] = $key;
                                }
                            }
                        }
                        $cnt++;
                    } else if ($usercode != date("Y-m-d")) {
                        //  if ($cnt != 0) {
                        $new_ary[] = $key;
                        // }
                        if (!empty($type)) {
                            if ($cityfk == "4" || $cityfk == "5") {
                                $phone_array[] = $key;
                            } else {
                                if (count($phlebo_count) > count($today_booking)) {
                                    if ($cnt != 0) {
                                        $phone_array[] = $key;
                                    }
                                }
                            }
                        }
                        $cnt++;
                    }
                }
                if (!empty($new_ary)) {
                    if (empty($type)) {
                        if ($bkng_date != null && $usercode == $cut_slot_date) {
                            unset($new_ary[0], $new_ary[1], $new_ary[2], $new_ary[3], $new_ary[4], $new_ary[5]);
                            $new_ary = array_values($new_ary);
                        }
                        echo $this->json_data("1", "", $new_ary);
                    } else {
                        if (!empty($phone_array)) {
                            if ($bkng_date != null && $usercode == $cut_slot_date) {
                                unset($phone_array[0], $phone_array[1], $phone_array[2], $phone_array[3], $phone_array[4], $new_ary[5]);
                                $phone_array = array_values($phone_array);
                            }
                            echo $this->json_data("1", "", $phone_array);
                        } else {
                            echo $this->json_data("0", "Time slot unavailable.", "");
                        }
                    }
                } else {
                    echo $this->json_data("0", "Time slot unavailable.", "");
                }
            } else {
                echo $this->json_data("0", "Time slot unavailable.", "");
            }
        } else {
            echo $this->json_data("0", "Invalid date.", "");
        }
    }

    function get_phlebo_schedule1() {
        $usercode = $this->input->get_post('bdate');
        $type = $this->input->get_post('type');
        $usercode1 = explode("/", $usercode);
        $cityfk = $this->input->get_post('city_id');
        $usercode = $usercode1[2] . "-" . $usercode1[1] . "-" . $usercode1[0];
        if ($cityfk == "4" || $cityfk == "5") {
            if ($usercode == date("Y-m-d")) {
                echo $this->json_data("0", "Time slot unavailable.", "");
                die();
            }
        }
        $bkng_date = null;
        $user_time = date("H:i");
        //$user_time = "23:00";
        if ($user_time > "22:59" && $user_time < "23:59") {
            $bkng_date = '1';
        }
        if ($user_time > "00:00" && $user_time < "06:00") {
            $bkng_date = '0';
        }
        $cut_slot_date = date('Y-m-d', strtotime(date("Y-m-d") . ' + ' . $bkng_date . ' days'));
        $day_of_the_week = date('w', strtotime($usercode));
        $row = $this->service_model->phlebo_time_slot_on_day($day_of_the_week, $usercode);
        if ($usercode >= date("Y-m-d")) {
            if (!empty($row)) {
                $new_ary = array();
                $phone_array = array();
                $cnt = 0;
                foreach ($row as $key) {
                    $phlebo_count = $this->service_model->master_fun_get_tbl_val("phlebo_master", array("status" => "1"), array("id", "desc"));
                    $today_booking = $this->service_model->master_fun_get_tbl_val("phlebo_assign_job", array("status" => "1", "time_fk" => $key["time_slot_fk"], "date" => $usercode), array("id", "desc"));
                    if (count($phlebo_count) > count($today_booking)) {
                        $key["booking_status"] = "Available";
                    } else {
                        $key["booking_status"] = "Unavailable";
                    }
                    if ($usercode == date("Y-m-d") && $key["real_time"] > date("H:i:s")) {
                        if ($cnt != 0) {
                            $new_ary[] = $key;
                        }
                        if (!empty($type)) {
                            if (count($phlebo_count) > count($today_booking)) {
                                $phone_array[] = $key;
                            }
                        }
                        $cnt++;
                    } else if ($usercode != date("Y-m-d")) {
                        $new_ary[] = $key;
                        if (!empty($type)) {
                            if (count($phlebo_count) > count($today_booking)) {
                                $phone_array[] = $key;
                            }
                        }
                        $cnt++;
                    }
                }
                if (!empty($new_ary)) {
                    if (empty($type)) {
                        if ($bkng_date != null && $usercode == $cut_slot_date) {
                            unset($new_ary[0], $new_ary[1], $new_ary[2], $new_ary[3], $new_ary[4], $new_ary[5]);
                            $new_ary = array_values($new_ary);
                        }
                        echo $this->json_data("1", "", $new_ary);
                    } else {
                        if (!empty($phone_array)) {
                            if ($bkng_date != null && $usercode == $cut_slot_date) {
                                unset($phone_array[0], $phone_array[1], $phone_array[2], $phone_array[3], $phone_array[4], $new_ary[5]);
                                $phone_array = array_values($phone_array);
                            }
                            echo $this->json_data("1", "", $phone_array);
                        } else {
                            echo $this->json_data("0", "Time slot unavailable.", "");
                        }
                    }
                } else {
                    echo $this->json_data("0", "Time slot unavailable.", "");
                }
            } else {
                echo $this->json_data("0", "Time slot unavailable.", "");
            }
        } else {
            echo $this->json_data("0", "Invalid date.", "");
        }
    }

    function user_family_member() {
        $uid = $this->input->get_post('uid');
        $relation_list = $this->service_model->get_user_family_member($uid);
        if (!empty($relation_list)) {
            echo $this->json_data("1", "", $relation_list);
        } else {
            echo $this->json_data("0", "Add your family member in your profile.", "");
        }
    }

    function user_adderss() {
        $uid = $this->input->get_post('uid');
        $job_address = $this->service_model->master_fun_get_tbl_val("job_master", array("status !=" => "0", "address !=" => "", "cust_fk" => $uid), array("address", "asc"));
        $customer_info = $this->service_model->master_fun_get_tbl_val("customer_master", array("id" => $uid), array("address", "asc"));
        $cnt = 0;
        $address = array();
        $add_array = array();
        if (trim($customer_info[0]["address"])) {
            if (!in_array($customer_info[0]["address"], $add_array)) {
                $address[$cnt]["address"] = $customer_info[0]["address"];
                $add_array[] = $customer_info[0]["address"];
                $cnt++;
            }
        }
        foreach ($job_address as $key) {
            if (trim($key["address"])) {
                if (!in_array($key["address"], $add_array)) {
                    $address[$cnt]["address"] = $key["address"];
                    $add_array[] = $key["address"];
                    $cnt++;
                }
            }
        }
        if (!empty($address)) {
            //$address = array_unique($address);
            echo $this->json_data("1", "", $address);
        } else {
            echo $this->json_data("0", "Add your adderss in your profile.", "");
        }
    }

    function store_booking_info() {
        $typ = $this->input->get_post('typ');
        $crelation = $this->input->get_post('crelation');
        $uaddress = $this->input->get_post('uaddress');
        $bookdate = $this->input->get_post('bookdate');
        $select_slot = $this->input->get_post('select_slot');

        $data = array(
            "type" => $typ,
            "family_member_fk" => $crelation,
            "address" => $uaddress,
            "date" => $bookdate,
            "time_slot_fk" => $select_slot,
            "createddate" => date("Y-m-d H:i:s")
        );
        $customer_info = $this->service_model->master_fun_insert("booking_info", $data);
        return $customer_info;
    }

    function json_data($status, $error_msg, $data = NULL) {
        if ($data == NULL) {
            $data = array();
        }
        $final = array("status" => $status, "error_msg" => $error_msg, "data" => $data);
        return str_replace("null", '" "', json_encode($final));
    }

    function relation() {
        $data = $this->service_model->master_fun_get_tbl_val("relation_master", array("status" => 1), array("name", "asc"));
        if (!empty($data)) {
            echo $this->json_data("1", "", $data);
        } else {
            echo $this->json_data("0", "Data not available", "");
        }
    }

    function add_family_member() {
        $uid = $this->input->get_post('uid');
        $name = $this->input->get_post('name');
        $relation_fk = $this->input->get_post('relation_fk');
        $email = $this->input->get_post('email');
        $phone = $this->input->get_post('phone');
        $gender = $this->input->get_post('gender');
        $date1 = $this->input->get_post('dob');
        $date1 = explode("/", $date1);
        $date1 = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        if ($uid != '' && $name != '' && $relation_fk != '') {
            $data = array(
                "user_fk" => $uid,
                "name" => $name,
                "relation_fk" => $relation_fk,
                "email" => $email,
                "phone" => $phone,
                "dob" => $date1,
                "gender" => $gender,
                "created_date" => date("Y-m-d H:i:s")
            );
            $customer_info = $this->service_model->master_fun_insert("customer_family_master", $data);
            $R_data = $this->service_model->master_fun_get_tbl_val("relation_master", array("id" => $relation_fk), array("name", "asc"));
            echo $this->json_data("1", "", array(array("id" => $customer_info, "msg" => "Successfully", "relation_name" => $R_data[0]["name"])));
        } else {
            echo $this->json_data("0", "Name and relation is required.", "");
        }
    }

    function add_family_member1() {
        $uid = $this->input->get_post('uid');
        $name = $this->input->get_post('name');
        $relation_fk = $this->input->get_post('relation_fk');
        $email = $this->input->get_post('email');
        $phone = $this->input->get_post('phone');
        $gender = $this->input->get_post('gender');
        $date1 = $this->input->get_post('f_dob');
        $date1 = explode("/", $date1);
        $date1 = $date1[2] . "-" . $date1[0] . "-" . $date1[1];
        if ($uid != '' && $name != '' && $relation_fk != '') {
            $R_data = $this->service_model->master_fun_get_tbl_val("relation_master", array("id" => $relation_fk), array("name", "asc"));
            $data = array(
                "user_fk" => $uid,
                "name" => $name,
                "relation_fk" => $relation_fk,
                "email" => $email,
                "phone" => $phone,
                "dob" => $date1,
                "gender" => $gender,
                "created_date" => date("Y-m-d H:i:s")
            );
            $customer_info = $this->service_model->master_fun_insert("customer_family_master", $data);

            echo $this->json_data("1", "", array(array("id" => $customer_info, "msg" => "Successfully", "relation_name" => $R_data[0]["name"])));
        } else {
            echo $this->json_data("0", "Name and relation is required.", "");
        }
    }

    function save_booking_data() {
//        echo "<pre>"; print_r($_POST); die();
        $uid = $this->input->get_post('uid');
        $type = $this->input->get_post('type');
        $crelation = $this->input->get_post('crelation');
        $house_no = $this->input->get_post('house_no');
        $apartment_name = $this->input->get_post('apartment_name');
        $near_by = $this->input->get_post('near_by');
        $area = $this->input->get_post('area');
        $job_address = $this->input->get_post('job_address');
        $uaddress = $this->input->get_post('uaddress');
        $bookdate = $this->input->get_post('bookdate');
        $select_slot = $this->input->get_post('select_slot');
        $landmark = $this->input->get_post('landmark');
        $address = $this->get_address();
        $bookdate = date('Y-m-d', strtotime(str_replace('-', '/', $bookdate)));
        $cal_data = $this->service_model->master_fun_get_tbl_val("phlebo_calender", array("status" => 1, "id" => $select_slot), array("id", "asc"));
        $booking_slot = $this->service_model->master_fun_get_tbl_val("phlebo_time_slot", array("status" => "1", "id" => $cal_data[0]["time_slot_fk"]), array("id", "asc"));

        if ($bookdate == date("Y-m-d") && $booking_slot[0]["start_time"] >= date("H:i:s")) {
            $data = array(
                "user_fk" => $uid,
                "type" => $type,
                "family_member_fk" => $crelation,
                "landmark" => $landmark,
                "address" => $address,
                "date" => $bookdate,
                "time_slot_fk" => $select_slot,
                "createddate" => date("Y-m-d H:i:s")
            );
        } else if ($bookdate >= date("Y-m-d")) {
            $data = array(
                "user_fk" => $uid,
                "type" => $type,
                "family_member_fk" => $crelation,
                "landmark" => $landmark,
                "address" => $address,
                "date" => $bookdate,
                "time_slot_fk" => $select_slot,
                "createddate" => date("Y-m-d H:i:s")
            );
        } else {
            echo $this->json_data("0", "Invalid date or time.", "");
            die();
        }
        if ($select_slot == 'emergency') {
            $data = $data + array("emergency" => "1");
        }
        $customer_info = $this->service_model->master_fun_insert("booking_info", $data);
        if ($customer_info) {
            echo $this->json_data("1", "", array(array("id" => $customer_info, "msg" => "Successfully")));
        } else {
            echo $this->json_data("0", "Oops somthing wrong.Try again.", "");
        }
    }
    function get_address() {
        $uid = $this->input->get_post('uid');
        $address = $this->input->get_post('job_address');
        $landmark = $this->input->get_post('landmark');
        $house_no = $this->input->get_post('house_no');
        $house_name = $this->input->get_post('apartment_name');
        $nearby = $this->input->get_post('near_by');
        $area = $this->input->get_post('area');
        if ($address != '') {
            $address=$this->service_model->master_num_rows("user_address", array("id" => $address));
            return $address[0]["address"];
        } else {
            $adr = '';
            if (trim($house_no) != '') {
                $adr .= $house_no . ",";
            }
            if (trim($house_name) != '') {
                $adr .= $house_name . ",";
            }
            if (trim($nearby) != '') {
                $adr .= $nearby . ",";
            }
            if (trim($area) != '') {
                $adr .= $area;
            }
            if (trim($landmark) != '') {
                $adr .= $landmark . ",";
            }
            $row = $this->service_model->master_num_rows("user_address", array("user_fk" => $uid, "address" => $adr, "status" => 1));
        if ($row == 0) {
            $this->service_model->master_fun_insert("user_address", array(
                "user_fk" => $uid,
                "address" => $adr,
                "house_no" => $house_no,
                "house_name" => $house_name,
                "nearby" => $nearby,
                "landmark" => $landmark,
                "area" => $area
            ));
        }
            return $adr;
        }
    }
    function save_booking_data1() {
        $uid = $this->input->get_post('uid');
        $type = $this->input->get_post('type');
        $crelation = $this->input->get_post('crelation');
        $uaddress = $this->input->get_post('uaddress');
        $bookdate = $this->input->get_post('bookdate');
        $select_slot = $this->input->get_post('select_slot');
        $bookdate = explode("/", $bookdate);
        $bookdate = $bookdate[2] . "-" . $bookdate[1] . "-" . $bookdate[0];
        $cal_data = $this->service_model->master_fun_get_tbl_val("phlebo_calender", array("status" => 1, "id" => $select_slot), array("id", "asc"));
        $booking_slot = $this->service_model->master_fun_get_tbl_val("phlebo_time_slot", array("status" => "1", "id" => $cal_data[0]["time_slot_fk"]), array("id", "asc"));

        if ($bookdate == date("Y-m-d") && $booking_slot[0]["start_time"] >= date("H:i:s")) {
            $data = array(
                "user_fk" => $uid,
                "type" => $type,
                "family_member_fk" => $crelation,
                "address" => $uaddress,
                "date" => $bookdate,
                "time_slot_fk" => $select_slot,
                "createddate" => date("Y-m-d H:i:s")
            );
        } else if ($bookdate >= date("Y-m-d")) {
            $data = array(
                "user_fk" => $uid,
                "type" => $type,
                "family_member_fk" => $crelation,
                "address" => $uaddress,
                "date" => $bookdate,
                "time_slot_fk" => $select_slot,
                "createddate" => date("Y-m-d H:i:s")
            );
        } else {
            echo $this->json_data("0", "Invalid date or time.", "");
            die();
        }
        if ($select_slot == 'emergency') {
            $data = $data + array("emergency" => "1");
        }
        $customer_info = $this->service_model->master_fun_insert("booking_info", $data);
        if ($customer_info) {
            echo $this->json_data("1", "", array(array("id" => $customer_info, "msg" => "Successfully")));
        } else {
            echo $this->json_data("0", "Oops somthing wrong.Try again.", "");
        }
    }

    function user_family_member_edit() {
        $uid = $this->input->get_post('mid');
        $member_details = $this->service_model->get_user_family_member_data($uid);
        if ($uid != "") {
            if (!empty($member_details)) {
                echo $this->json_data("1", "", $member_details);
            } else {
                echo $this->json_data("0", "Data Not Available.", "");
            }
        } else {
            echo $this->json_data("0", "Parameter Not Passed.", "");
        }
    }

    function user_family_member_update() {
        $uid = $this->input->get_post('mid');
        $name = $this->input->get_post('mname');
        $relation = $this->input->get_post('mrelation');
        $phone = $this->input->get_post('mphone');
        $email = $this->input->get_post('memail');
        $gender = $this->input->get_post('gender');
        $date1 = $this->input->get_post('dob');
        $date1 = explode("/", $date1);
        $date1 = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $data = array("name" => $name, "relation_fk" => $relation, "email" => $email, "phone" => $phone, "dob" => $date1, "gender" => $gender);
        $member_details = $this->service_model->master_fun_update('customer_family_master', $uid, $data);
        if ($uid != "") {
            if ($member_details == 1) {
                echo $this->json_data("1", "Family Member Successfully Updated.", "");
            } else {
                echo $this->json_data("0", "Data Not Available.", "");
            }
        } else {
            echo $this->json_data("0", "Parameter Not Passed.", "");
        }
    }

    function user_family_member_delete() {
        $uid = $this->input->get_post('mid');
        $member_details = $this->service_model->master_fun_update('customer_family_master', $uid, array('status' => 0));
        if ($uid != "") {
            if ($member_details == 1) {
                echo $this->json_data("1", "Family Member Successfully Deleted.", "");
            } else {
                echo $this->json_data("0", "Data Not Available.", "");
            }
        } else {
            echo $this->json_data("0", "Parameter Not Passed.", "");
        }
    }

    function pending_job_list() {
        $userid = $this->input->get_post('userid');
        $check_checkin = $this->service_model->get_val("SELECT id FROM `phlabo_timer` WHERE STATUS='1' AND user_fk = '$userid' and stop_time IS NULL ");

        if ($check_checkin) {
            $status = "punchin";
        } else {
            $status = "punchout";
        }
        $phlebo_assign_job = $this->service_model->get_val("SELECT job_fk FROM `phlebo_assign_job` WHERE status='1'");
        $p_jid = array();
        foreach ($phlebo_assign_job as $key) {
            $p_jid[] = $key["job_fk"];
        }
        if (!empty($p_jid)) {
            $job_list = $this->service_model->get_val("SELECT * from job_master where status in (6,1) and id not in (" . implode(",", $p_jid) . ") ORDER BY `job_master`.`id` DESC");
        } else {
            $job_list = $this->service_model->get_val("SELECT * from job_master where status in (6,1) ORDER BY `job_master`.`id` DESC");
        }
        if (!empty($job_list)) {
            $data = array();
            foreach ($job_list as $key) {
                $details = $this->get_job_details($key["id"]);
                $booking_details = $this->service_model->get_val("SELECT 
  `booking_info`.*,
  `customer_family_master`.`name`,
  `phlebo_time_slot`.`start_time`,
  `phlebo_time_slot`.`end_time`,
  `customer_master`.`full_name`,
  `customer_master`.`mobile`
FROM 
  `booking_info` 
  LEFT JOIN `customer_family_master` 
    ON `customer_family_master`.`id` = `booking_info`.`family_member_fk` 
  INNER JOIN `customer_master` 
    ON `booking_info`.`user_fk` = `customer_master`.`id` 
  LEFT JOIN `phlebo_time_slot` 
    ON `booking_info`.`time_slot_fk` = `phlebo_time_slot`.`id` 
WHERE `booking_info`.`id` = '" . $key["booking_info"] . "' ");
                $tcname = $this->service_model->master_fun_get_tbl_val('test_cities', array("id" => $job_list[0]["test_city"]), array("id", "desc"));
                if ($booking_details[0]["time_slot_fk"] != '0') {
                    $originalDate = $booking_details[0]["date"];
                    $newDate = date("d-m-Y", strtotime($originalDate));
                    $booking_details[0]["date"] = $newDate;

                    $originalDate = $booking_details[0]["start_time"];
                    $newDate = date("g:i A", strtotime($originalDate));
                    $booking_details[0]["start_time"] = $newDate;

                    $originalDate = $booking_details[0]["end_time"];
                    $newDate = date("g:i A", strtotime($originalDate));
                    $booking_details[0]["end_time"] = $newDate;
                } else {
                    $originalDate = $booking_details[0]["date"];
                    if ($booking_details[0]["date"] != '0000-00-00') {
                        $newDate = date("d-m-Y", strtotime($originalDate));
                        $booking_details[0]["date"] = $newDate . " (Emergency)";
                    } else {
                        $booking_details[0]["date"] = "(Emergency)";
                    }

                    $booking_details[0]["start_time"] = "";

                    $booking_details[0]["end_time"] = "";
                }
                $details[0]["test_city"] = $tcname[0]["name"];
                $details[0]["booking_info"] = $booking_details;
                $data[] = $details[0];
            }

            //echo "<pre>";print_r($data); die();
            echo $this->json_data1("1", "", $data, $status);
        } else {
            echo $this->json_data1("0", "New job not available.", "", status);
        }
    }

    function json_data1($status, $error_msg, $data = NULL, $status1) {
        if ($data == NULL) {
            $data = array();
        }
        $final = array("status" => $status, "error_msg" => $error_msg, "data" => $data, "punchIn" => $status1);
        return str_replace("null", '" "', json_encode($final));
    }

    function get_job_details($job_id) {
        $job_details = $this->service_model->master_fun_get_tbl_val("job_master", array("status !=" => "0", "id" => $job_id), array("id", "asc"));
        $user_details = $this->service_model->master_fun_get_tbl_val("customer_master", array("status !=" => "0", "id" => $job_details[0]["cust_fk"]), array("id", "asc"));
        $job_details[0]["full_name"] = $user_details[0]["full_name"];
        $job_details[0]["mobile"] = $user_details[0]["mobile"];
        if (!empty($job_details)) {
            $book_test = $this->service_model->master_fun_get_tbl_val("job_test_list_master", array("job_fk" => $job_id), array("id", "desc"));
            $book_package = $this->service_model->master_fun_get_tbl_val("book_package_master", array("job_fk" => $job_id, "status" => "1"), array("id", "desc"));
            $test_name = array();
            foreach ($book_test as $key) {
                $price1 = $this->service_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $job_details[0]["test_city"] . "' AND `test_master_city_price`.`status`='1' AND `test_master`.`id`='" . $key["test_fk"] . "'");
                if (count($price1) > 0) {
                    $test_name[] = $price1[0];
                }
            }
            if (empty($test_name)) {
                $test_name = array();
            }
            if (count($test_name) == 0) {
                $test_name = array();
            }
            $job_details[0]["book_test"] = $test_name;
            $package_name = array();
            foreach ($book_package as $key) {
                $price1 = $this->service_model->get_val("SELECT 
          `package_master`.*,
          `package_master_city_price`.`a_price` AS `a_price`,
          `package_master_city_price`.`d_price` AS `d_price`
          FROM
          `package_master`
          INNER JOIN `package_master_city_price`
          ON `package_master`.`id` = `package_master_city_price`.`package_fk`
          WHERE `package_master`.`status` = '1'
          AND `package_master_city_price`.`status` = '1' AND `package_master_city_price`.`city_fk` = '" . $job_details[0]["test_city"] . "' AND `package_master`.`id`='" . $key["package_fk"] . "'");
                if (count($price1) > 0) {
                    $package_name[] = $price1[0];
                }
            }
            if (empty($package_name)) {
                $package_name = array();
            }
            if (count($package_name) == 0) {
                $package_name = array();
            }
            $job_details[0]["book_package"] = $package_name;
        }
        return $job_details;
    }

    function phlebo_login() {
        $email = $this->input->get_post('email');
        $password = $this->input->get_post('password');
        //$device_id = $this->input->get_post('device_id');
        if ($email != NULL && $password != NULL) {
            $row = $this->service_model->master_num_rows("phlebo_master", array("email" => $email, "password" => $password, "status" => 1));
            if ($row == 1) {
                $data = $this->service_model->master_fun_get_tbl_val("phlebo_master", array("email" => $email, "password" => $password, "status" => 1), array("id", "asc"));
                echo $this->json_data("1", "", $data);
            } else {
                echo $this->json_data("0", "Email Or Password Not match", "");
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", "");
        }
    }

    function get_phlebo_did() {
        $cid = $this->input->get_post('pid');
        $did = $this->input->get_post('did');
        $type = $this->input->get_post('type');
        $member_details = $this->service_model->master_fun_update('phlebo_master', $cid, array('device_id' => $did, "device_type" => $type));
        if ($cid != "") {
            if ($member_details == 1) {
                echo $this->json_data("1", "Successfully", "");
            } else {
                echo $this->json_data("0", "Oops somthing wrong.", "");
            }
        } else {
            echo $this->json_data("0", "Phlebo id is required.", "");
        }
    }

    function accept_job() {
        $job_id = $this->input->get_post("job_id");
        $phlebo_fk = $this->input->get_post("phlebo_fk");
        $new_job_details = $this->service_model->master_fun_get_tbl_val("job_master", array('id' => $job_id), array("id", "asc"));
        $booking_info = $this->service_model->master_fun_get_tbl_val("booking_info", array('id' => $new_job_details[0]["booking_info"]), array("id", "asc"));
        $check_job_status = $this->service_model->master_num_rows("phlebo_assign_job", array('job_fk' => $job_id, "status" => "1"), array("id", "asc"));
        if ($check_job_status == 0) {
            if (!empty($phlebo_fk)) {
                $data = array("job_fk" => $job_id, "phlebo_fk" => $phlebo_fk, "date" => $booking_info[0]["date"], "time_fk" => $booking_info[0]["time_slot_fk"], "address" => $booking_info[0]["address"], "notify_cust" => 1, "created_date" => date("Y-m-d H:i:s"));
                $insert = $this->service_model->master_fun_insert("phlebo_assign_job", $data);
                //$this->user_test_master_model->master_fun_insert("job_log", array("job_fk" => $job_id, "created_by" => "", "updated_by" => $login_id, "deleted_by" => "", "message_fk" => "8", "date_time" => date("Y-m-d H:i:s")));
                //$update = $this->job_model->master_fun_update("phlebo_master", array("id", $phlebo_id), $data);
                $phlebo_details = $this->service_model->master_fun_get_tbl_val("phlebo_master", array('id' => $phlebo_fk, "type" => 1), array("id", "asc"));
                $phlebo_job_details = $this->service_model->master_fun_get_tbl_val("phlebo_assign_job", array('job_fk' => $job_id), array("id", "asc"));
                $p_time = $this->service_model->master_fun_get_tbl_val("phlebo_time_slot", array('id' => $phlebo_job_details[0]["time_fk"]), array("id", "asc"));
                $job_details = $this->service_model->master_fun_get_tbl_val("job_master", array('id' => $job_id), array("id", "asc"));
                $customer_details = $this->service_model->master_fun_get_tbl_val("customer_master", array('id' => $job_details[0]['cust_fk']), array("id", "asc"));
                if ($insert) {
                    $family_member_name = $this->service_model->get_family_member_name($job_id);
                    if (!empty($family_member_name)) {
                        $c_name = $family_member_name[0]["name"];
                        $cmobile = $family_member_name[0]["phone"];
                        if (empty($cmobile)) {
                            $cmobile = $customer_details[0]["mobile"];
                        }
                    } else {
                        $c_name = $customer_details[0]["full_name"];
                        $cmobile = $customer_details[0]["mobile"];
                    }
                    $s_time = date('h:i a', strtotime($p_time[0]["start_time"]));
                    $e_time = date('h:i a', strtotime($p_time[0]["end_time"]));
                    $sms_message = $this->service_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "phlebo_msg"), array("id", "asc"));
                    $sms_message = preg_replace("/{{NAME}}/", ucfirst($phlebo_details[0]["name"]), $sms_message[0]["message"]);
                    $sms_message = preg_replace("/{{MOBILE}}/", $phlebo_details[0]["mobile"], $sms_message);
                    $sms_message = preg_replace("/{{ORDERID}}/", $job_details[0]["order_id"], $sms_message);
                    $sms_message = preg_replace("/{{CNAME}}/", $c_name, $sms_message);
                    $sms_message = preg_replace("/{{CMOBILE}}/", $cmobile, $sms_message);
                    $sms_message = preg_replace("/{{CADDRESS}}/", $phlebo_job_details[0]["address"], $sms_message);
                    $sms_message = preg_replace("/{{DATE}}/", $phlebo_job_details[0]["date"], $sms_message);
                    $sms_message = preg_replace("/{{TIME}}/", $s_time . " To " . $e_time, $sms_message);
                    $job_details = $this->get_job_details($job_id);
                    $b_details = array();
                    $collectable_amount = 0;
                    foreach ($job_details[0]["book_test"] as $bkey) {
                        $b_details[] = $bkey["test_name"] . " Rs." . $bkey["price"];
                        $collectable_amount = $collectable_amount + $bkey["price"];
                    }
                    foreach ($job_details[0]["book_package"] as $bkey) {
                        $b_details[] = $bkey["title"] . " Rs." . $bkey["d_price"];
                        $collectable_amount = $collectable_amount + $bkey["d_price"];
                    }
                    $sms_message = preg_replace("/{{BOOKINFO}}/", implode(",", $b_details) . " Payable amount: Rs." . $new_job_details[0]["payable_amount"], $sms_message);
                    //$this->user_test_master_model->master_fun_insert("test", array("test"=>$sms_message."-".json_encode($job_details)));
                    //$sms_message="done";
                    $mobile = $phlebo_details[0]['mobile'];
                    $this->load->helper("sms");
                    $notification = new Sms();
                    //$notification->send($mobile, $sms_message);
                    // if ($notify == 1) {
                    $sms_message = $this->service_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "phlebo_msg_cust"), array("id", "asc"));
                    $sms_message = preg_replace("/{{NAME}}/", ucfirst($c_name), $sms_message[0]["message"]);
                    $sms_message = preg_replace("/{{MOBILE}}/", $customer_details[0]["mobile"], $sms_message);
                    $sms_message = preg_replace("/{{ORDERID}}/", $job_details[0]["order_id"], $sms_message);
                    $sms_message = preg_replace("/{{PNAME}}/", ucfirst($phlebo_details[0]["name"]), $sms_message);
                    $sms_message = preg_replace("/{{PMOBILE}}/", $phlebo_details[0]["mobile"], $sms_message);
                    $sms_message = preg_replace("/{{DATE}}/", $phlebo_job_details[0]["date"], $sms_message);
                    $sms_message = preg_replace("/{{TIME}}/", $s_time . " To " . $e_time, $sms_message);
                    $sms_message = preg_replace("/{{BOOKINFO}}/", implode(",", $b_details) . " Payable amount: Rs." . $new_job_details[0]["payable_amount"], $sms_message);
                    $mobile = $customer_details[0]['mobile'];
                    //$sms_message="done";
                    //$notification->send($cmobile, $sms_message);
                    if (!empty($job_details[0]["address"])) {
                        $this->service_model->master_fun_update("customer_master", array('id', $job_details[0]["cust_fk"]), array("address" => $job_details[0]["address"]));
                    }
                    if (!empty($family_member_name)) {
                        $this->service_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $customer_details[0]["mobile"], "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
                    }
                    $sms_number = $this->config->item('phlebo_accept_job_alert');
                    $sms_message = '';
                    $sms_message = $this->service_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "phlebo_accept_job_alert"), array("id", "asc"));
                    $sms_message = preg_replace("/{{NAME}}/", ucfirst($c_name), $sms_message[0]["message"]);
                    $sms_message = preg_replace("/{{MOBILE}}/", $customer_details[0]["mobile"], $sms_message);
                    $sms_message = preg_replace("/{{ORDERID}}/", $job_details[0]["order_id"], $sms_message);
                    $sms_message = preg_replace("/{{PNAME}}/", ucfirst($phlebo_details[0]["name"]), $sms_message);
                    $sms_message = preg_replace("/{{PMOBILE}}/", $phlebo_details[0]["mobile"], $sms_message);
                    $sms_message = preg_replace("/{{DATE}}/", $phlebo_job_details[0]["date"], $sms_message);
                    $sms_message = preg_replace("/{{TIME}}/", $s_time . " To " . $e_time, $sms_message);
                    $sms_message = preg_replace("/{{CADDRESS}}/", $phlebo_job_details[0]["address"], $sms_message);
                    $sms_message = preg_replace("/{{BOOKINFO}}/", implode(",", $b_details) . " Payable amount: Rs." . $new_job_details[0]["payable_amount"], $sms_message);
                    //echo $sms_message;die();
                    foreach ($sms_number as $p_key) {
                        $mb_length = strlen($p_key);
                        $configmobile = $p_key;
                        $this->service_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $configmobile, "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
                    }
                    echo $this->json_data("1", "", array());
                    //}
                } else {
                    echo $this->json_data("0", "Invalid parameter.", "");
                }
            } else {
                echo $this->json_data("0", "Invalid parameter.", "");
            }
        } else {
            echo $this->json_data("0", "This job already assign.", "");
        }
    }

    function live_job() {
        $pid = $this->input->get_post('pid');
        $checkin_job_list = $this->service_model->get_val("SELECT job_fk FROM `phlebo_checkin` WHERE STATUS='1' AND `phlebo_fk`='" . $pid . "' order by checkin_time desc");
        $checkin_job_ids = array();
        foreach ($checkin_job_list as $key) {
            $checkin_job_ids[] = $key["job_fk"];
        }
        //print_r($checkin_job_ids);
        if (!empty($checkin_job_ids)) {
            //   echo "SELECT `phlebo_assign_job`.*,`job_master`.`booking_info` FROM `phlebo_assign_job` INNER JOIN `job_master` ON `phlebo_assign_job`.`job_fk`=`job_master`.`id` WHERE `phlebo_assign_job`.`status`='1' AND `job_master`.`status`!='0' AND `phlebo_assign_job`.phlebo_fk='" . $pid . "' AND `phlebo_assign_job`.`job_fk` NOT IN (" . implode(",", $checkin_job_ids) . ")"; die();
            $job_list = $this->service_model->get_val("SELECT `phlebo_assign_job`.*,`job_master`.`booking_info` FROM `phlebo_assign_job` INNER JOIN `job_master` ON `phlebo_assign_job`.`job_fk`=`job_master`.`id` WHERE `phlebo_assign_job`.`status`='1' AND `job_master`.`status` in (1,6) AND `phlebo_assign_job`.phlebo_fk='" . $pid . "' AND `phlebo_assign_job`.`job_fk` NOT IN (" . implode(",", $checkin_job_ids) . ") order by `phlebo_assign_job`.`job_fk` desc");
        } else {
            $job_list = $this->service_model->get_val("SELECT `phlebo_assign_job`.*,`job_master`.`booking_info` FROM `phlebo_assign_job` INNER JOIN `job_master` ON `phlebo_assign_job`.`job_fk`=`job_master`.`id` WHERE `phlebo_assign_job`.`status`='1' AND `job_master`.`status` in (1,6) AND `phlebo_assign_job`.phlebo_fk='" . $pid . "'  order by `phlebo_assign_job`.`job_fk` desc");
        }
        if (!empty($job_list)) {
            $data = array();
            foreach ($job_list as $key) {
                //      echo "0<br>";
                $details = $this->get_job_details($key["job_fk"]);
                $booking_details = $this->service_model->get_val("SELECT 
  `booking_info`.*,
  `customer_family_master`.`name`,
  `phlebo_time_slot`.`start_time`,
  `phlebo_time_slot`.`end_time`,
  `customer_master`.`full_name`,
   `customer_master`.`mobile` 
FROM 
  `booking_info` 
  LEFT JOIN `customer_family_master` 
    ON `customer_family_master`.`id` = `booking_info`.`family_member_fk` 
  INNER JOIN `customer_master` 
    ON `booking_info`.`user_fk` = `customer_master`.`id` 
  LEFT JOIN `phlebo_time_slot` 
    ON `booking_info`.`time_slot_fk` = `phlebo_time_slot`.`id` 
WHERE `booking_info`.`id` = '" . $key["booking_info"] . "' ");
                $tcname = $this->service_model->master_fun_get_tbl_val('test_cities', array("id" => $job_list[0]["test_city"]), array("id", "desc"));
                if ($booking_details[0]["time_slot_fk"] != '0') {
                    $originalDate = $booking_details[0]["date"];
                    $newDate = date("d-m-Y", strtotime($originalDate));
                    $booking_details[0]["date"] = $newDate;

                    $originalDate = $booking_details[0]["start_time"];
                    $newDate = date("g:i A", strtotime($originalDate));
                    $booking_details[0]["start_time"] = $newDate;

                    $originalDate = $booking_details[0]["end_time"];
                    $newDate = date("g:i A", strtotime($originalDate));
                    $booking_details[0]["end_time"] = $newDate;
                } else {
                    $originalDate = $booking_details[0]["date"];
                    if ($booking_details[0]["date"] != '0000-00-00') {
                        $newDate = date("d-m-Y", strtotime($originalDate));
                        $booking_details[0]["date"] = $newDate . " (Emergency)";
                    } else {
                        $booking_details[0]["date"] = "(Emergency)";
                    }

                    $booking_details[0]["start_time"] = "";

                    $booking_details[0]["end_time"] = "";
                }
                $details[0]["booking_info"] = $booking_details;
                $data[] = $details[0];
            }
            //echo "<pre>";print_r($data); die();
            echo $this->json_data("1", "", $data);
        } else {
            echo $this->json_data("0", "New job not available.", "");
        }
    }

    function phlebo_checkin() {
        $pid = $this->input->get_post('pid');
        $jid = $this->input->get_post('jid');
        $lat = $this->input->get_post('lat');
        $lag = $this->input->get_post('lag');

        $data = array("job_fk" => $jid, "phlebo_fk" => $pid, "checkin_lat" => $lat, "checkin_lag" => $lag, "checkin_time" => date("Y-m-d H:i:s"));
        if ($pid != "" && $jid != '' && $lat != '' && $lag != '') {
            $check_checkin = $row = $this->service_model->master_num_rows("phlebo_checkin", array("job_fk" => $jid, "phlebo_fk" => $pid, "status" => "1"));
            if ($check_checkin == 0) {
                $member_details = $this->service_model->master_fun_insert('phlebo_checkin', $data);
            } else {
                $check_details = $this->service_model->master_fun_get_tbl_val('phlebo_checkin', array('job_fk' => $jid), array("id", "desc"));
                $this->service_model->master_fun_update("phlebo_checkin", $check_details[0]["id"], $data);
                $member_details = $check_details[0]["id"];
            }
            /* Job details start */
            $data["id"] = $member_details . "";
            $key = $data;
            $key["job_details"] = $this->get_job_details($jid);
            $bkng_info = $this->service_model->get_val("SELECT `booking_info`.*,`customer_family_master`.`name` AS family_member_name,TIME_FORMAT(
    `phlebo_time_slot`.`start_time`,
    '%l:%i %p'
  ) AS `start_time`,TIME_FORMAT(
    `phlebo_time_slot`.`end_time`,
    '%l:%i %p'
  ) AS `end_time` FROM `booking_info` LEFT JOIN `customer_family_master` ON `customer_family_master`.`id`=`booking_info`.`family_member_fk` LEFT JOIN `phlebo_time_slot` ON `phlebo_time_slot`.`id`=`booking_info`.`time_slot_fk` where booking_info.id='" . $key["job_details"][0]["booking_info"] . "'");
            $key["booking_info"] = $bkng_info;
            /* Job details end */
            echo $this->json_data("1", "", $key);
        } else {
            echo $this->json_data("0", "All Parameters are required.", "");
        }
    }

    function phlebo_checkin_list() {
        $pid = $this->input->get_post('pid');
        $data = $this->service_model->master_fun_get_tbl_val('phlebo_checkin', array("phlebo_fk" => $pid, "status" => "1", "checkout_time" => null, "barcode" => null), array("id", "desc"));
        if (!empty($data)) {
            $new_data = array();
            foreach ($data as $key) {
                $key["job_details"] = $this->get_job_details($key["job_fk"]);
                $bkng_info = $this->service_model->get_val("SELECT `booking_info`.*,`customer_family_master`.`name` AS family_member_name,TIME_FORMAT(
    `phlebo_time_slot`.`start_time`,
    '%l:%i %p'
  ) AS `start_time`,TIME_FORMAT(
    `phlebo_time_slot`.`end_time`,
    '%l:%i %p'
  ) AS `end_time` FROM `booking_info` LEFT JOIN `customer_family_master` ON `customer_family_master`.`id`=`booking_info`.`family_member_fk` LEFT JOIN `phlebo_time_slot` ON `phlebo_time_slot`.`id`=`booking_info`.`time_slot_fk` where booking_info.id='" . $key["job_details"][0]["booking_info"] . "'");
                $key["booking_info"] = $bkng_info;
                $new_data[] = $key;
            }
            echo $this->json_data("1", "", $new_data);
        } else {
            echo $this->json_data("0", "Data not available.", "");
        }
    }

    function phlebo_checkout() {
        $id = $this->input->get_post('id');
        $jid = $this->input->get_post('jid');
        $lat = $this->input->get_post('lat');
        $lag = $this->input->get_post('lag');
        $note = $this->input->get_post('note');
        $barcode = $this->input->get_post("barcode");
        $blood_collect = $this->input->get_post("blood_collect");
        if (!empty($id) && !empty($lat) && !empty($lag)) {
            /* if (!empty($id) && !empty($lat) && !empty($lag) && !empty($barcode)) { */
            $data = array("checkout_lat" => $lat, "checkout_lag" => $lag, "blood_collect" => $blood_collect, "checkout_time" => date("Y-m-d H:i:s"), "barcode" => $barcode, "note" => $note);
            $update = $this->service_model->master_fun_update1("phlebo_checkin", array("id" => $id), $data);
            $this->service_model->master_fun_update1("job_master", array("id" => $jid), array("status" => "7", "sample_collection" => "1"));
            $details = $this->get_job_details($jid);
            $phlebo_details = $this->service_model->master_fun_get_tbl_val("phlebo_assign_job", array("job_fk" => $jid), array("id", "asc"));
            $pidd = $phlebo_details[0]['phlebo_fk'];
            if ($blood_collect == 0) {


                $phlebo_details = $this->service_model->master_fun_get_tbl_val("phlebo_master", array("id" => $phlebo_details[0]['phlebo_fk']), array("id", "asc"));
                $booking_details = $this->service_model->get_val("SELECT `booking_info`.*,`customer_family_master`.`name` AS family_member_name,TIME_FORMAT(
    `phlebo_time_slot`.`start_time`,
    '%l:%i %p'
  ) AS `start_time`,TIME_FORMAT(
    `phlebo_time_slot`.`end_time`,
    '%l:%i %p'
  ) AS `end_time` FROM `booking_info` LEFT JOIN `customer_family_master` ON `customer_family_master`.`id`=`booking_info`.`family_member_fk` LEFT JOIN `phlebo_time_slot` ON `phlebo_time_slot`.`id`=`booking_info`.`time_slot_fk` where booking_info.id='" . $details[0]["booking_info"] . "' ");

                $sms_message = $this->service_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "sample_not_collect"), array("id", "asc"));
                $sms_message = preg_replace("/{{ORDERID}}/", $details[0]["order_id"], $sms_message[0]["message"]);
                $sms_message = preg_replace("/{{CUSTOMER}}/", $details[0]["full_name"], $sms_message);
                $sms_message = preg_replace("/{{ADDRESS}}/", $details[0]["address"], $sms_message);
                $sms_message = preg_replace("/{{PHLEBO}}/", $phlebo_details[0]["name"], $sms_message);
                $sms_message = preg_replace("/{{DATE}}/", $booking_details[0]["date"], $sms_message);
                $sms_message = preg_replace("/{{TIME}}/", $booking_details[0]["start_time"] . "-" . $booking_details[0]["end_time"], $sms_message);
                $sms_number = $this->config->item('sample_not_collect_alert');
                foreach ($sms_number as $p_key) {
                    $mb_length = strlen($p_key);
                    $configmobile = $p_key;
                    //$notification->send($configmobile, $sms_message);
                    $this->service_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $configmobile, "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
                }
                $update = $this->service_model->master_fun_update1("phlebo_checkin", array("id" => $id), array("status" => '0'));
                $this->service_model->master_fun_update1("phlebo_assign_job", array("job_fk" => $jid), array("status" => 0));
                $this->service_model->master_fun_update1("job_master", array("id" => $jid), array("status" => "6", "sample_collection" => "0"));
            } else {
                $sms_message = $this->service_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "share_otp"), array("id", "asc"));
                $OTP = rand(1111, 9999);
                $this->load->helper("sms");
                $notification = new Sms();
                $sms_message = preg_replace("/{{OTP}}/", $OTP, $sms_message[0]["message"]);
                $notification->send($details[0]["mobile"], $sms_message);
                $this->service_model->master_fun_insert("phlebo_rate", array("job_fk" => $this->input->get_post('jid'), "phlebo_fk" => $pidd, "check_in_fk" => $this->input->get_post('id'), "otp" => $OTP));
            }
            if (!empty($update)) {
                echo $this->json_data("1", "", array());
            } else {
                echo $this->json_data("0", "All Parameters are required.", "");
            }
        } else {
            echo $this->json_data("0", "All Parameters are required.", "");
        }
    }

    function pending_job_history() {
        $pid = $this->input->get_post("pid");
        $phlebo_assign_job = $this->service_model->get_val("SELECT job_fk FROM `phlebo_checkin` WHERE STATUS='1' AND checkin_time!='' and checkout_time!='' and phlebo_fk='" . $pid . "' limit 0,100");
        $p_jid = array();
        foreach ($phlebo_assign_job as $key) {
            $p_jid[] = $key["job_fk"];
        }
        if (!empty($p_jid)) {
            $job_list = $this->service_model->get_val("SELECT * from job_master where status!='0' and id in (" . implode(",", $p_jid) . ") ORDER BY `job_master`.`id` DESC limit 0,100");
        } else {
            $job_list = array();
        }
        if (!empty($job_list)) {
            $data = array();
            foreach ($job_list as $key) {
                $details = $this->get_job_details($key["id"]);
                $booking_details = $this->service_model->get_val("SELECT 
  `booking_info`.*,
  `customer_family_master`.`name`,
  `phlebo_time_slot`.`start_time`,
  `phlebo_time_slot`.`end_time`,
  `customer_master`.`full_name`,
  `customer_master`.`mobile`
FROM 
  `booking_info` 
  LEFT JOIN `customer_family_master` 
    ON `customer_family_master`.`id` = `booking_info`.`family_member_fk` 
  INNER JOIN `customer_master` 
    ON `booking_info`.`user_fk` = `customer_master`.`id` 
  LEFT JOIN `phlebo_time_slot` 
    ON `booking_info`.`time_slot_fk` = `phlebo_time_slot`.`id` 
WHERE `booking_info`.`id` = '" . $key["booking_info"] . "' ");
                $tcname = $this->service_model->master_fun_get_tbl_val('test_cities', array("id" => $job_list[0]["test_city"]), array("id", "desc"));
                if ($booking_details[0]["time_slot_fk"] != '0') {
                    $originalDate = $booking_details[0]["date"];
                    $newDate = date("d-m-Y", strtotime($originalDate));
                    $booking_details[0]["date"] = $newDate;

                    $originalDate = $booking_details[0]["start_time"];
                    $newDate = date("g:i A", strtotime($originalDate));
                    $booking_details[0]["start_time"] = $newDate;

                    $originalDate = $booking_details[0]["end_time"];
                    $newDate = date("g:i A", strtotime($originalDate));
                    $booking_details[0]["end_time"] = $newDate;
                } else {
                    $originalDate = $booking_details[0]["date"];
                    if ($booking_details[0]["date"] != '0000-00-00') {
                        $newDate = date("d-m-Y", strtotime($originalDate));
                        $booking_details[0]["date"] = $newDate . " (Emergency)";
                    } else {
                        $booking_details[0]["date"] = "(Emergency)";
                    }

                    $booking_details[0]["start_time"] = "";

                    $booking_details[0]["end_time"] = "";
                }

                $details[0]["test_city"] = $tcname[0]["name"];
                $details[0]["booking_info"] = $booking_details;
                $data[] = $details[0];
            }
            //echo "<pre>";print_r($data); die();
            echo $this->json_data("1", "", $data);
        } else {
            echo $this->json_data("0", "Record not found.", "");
        }
    }

    function phlebo_track() {
        $pid = $this->input->get_post('pid');
        $lat = $this->input->get_post('lat');
        $lag = $this->input->get_post('lag');
        $member_details = $this->service_model->master_fun_insert('phlebo_track', array("phlebo_fk" => $pid, "lat" => $lat, "lang" => $lag, "created_date" => date("Y-m-d H:i:s")));
        echo $this->json_data("1", "", array());
    }

    function add_logistic_log() {
        $pid = $this->input->get_post('pid');
        $barcode = $this->input->get_post('barcode');
        $data_ary = json_decode($barcode);
        //$data_ary = array_unique($data_ary);
        //$barcode = implode(",", $data_ary);
        if ($pid != '' && !empty($data_ary)) {
            /* Pic upload start */
            $files = $_FILES;
            $data = array();
            $this->load->library('upload');
            $config['allowed_types'] = 'png|jpg|gif|jpeg';
            if (isset($files['userfile']) && $files['userfile']['name'] != "") {
                $config['upload_path'] = './upload/barcode/';
                $config['file_name'] = $files['userfile']['name'];
                $this->upload->initialize($config);
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0755, TRUE);
                }
                if (!$this->upload->do_upload('userfile')) {
                    $error = $this->upload->display_errors();
                    $error = str_replace("<p>", "", $error);
                    $error = str_replace("</p>", "", $error);
                    echo $this->json_data("0", $error, "");
                    die();
                } else {
                    $doc_data = $this->upload->data();
                    $filename = $doc_data['file_name'];
                    $uploads = array('upload_data' => $this->upload->data("identity"));
                    //echo $this->json_data("1", "", array(array("filename" => $filename)));
                }
            }
            /* Pic upload end */
            foreach ($data_ary as $b_key) {
                $row = $this->service_model->master_num_rows("logistic_log", array("barcode" => $b_key->barcode, "status" => 1));
                if ($row > 0) {
                    $barcode_data = $this->service_model->master_fun_get_tbl_val("logistic_log", array("barcode" => $b_key->barcode, "status" => 1), array("id", "asc"));
                    $b_id = $barcode_data[0]["id"];
                } else {

                    $data = array(
                        "phlebo_fk" => $pid,
                        "barcode" => $b_key->barcode,
                        "collect_from" => $b_key->id,
                        "scan_date" => date("Y-m-d H:i:s"),
                        "status" => 1,
                        "createddate" => date("Y-m-d H:i:s"),
                        "created_by" => $pid,
                        "pic" => $filename
                    );
                    //$this->service_model->master_fun_update1("logistic_log", array("barcode" => $b_key->barcode, "status" => 1), array("sample_status" => "0"));
                    $customer_info = $this->service_model->master_fun_insert("logistic_log", $data);
                    $b_id = $customer_info;
                }
                $data = array(
                    "barcode_fk" => $b_id,
                    "phlebo_fk" => $pid,
                    "collect_from" => $b_key->id,
                    "scan_date" => date("Y-m-d H:i:s"),
                    "status" => 1,
                    "createddate" => date("Y-m-d H:i:s"),
                    "created_by" => $pid,
                    "pic" => $filename
                );
                //print_R($data); die();
                $this->service_model->master_fun_update1("logistic_sample_arrive", array("barcode_fk" => $b_id, "status" => 1), array("sample_status" => "0"));
                $customer_info = $this->service_model->master_fun_insert("logistic_sample_arrive", $data);
            }
            echo $this->json_data("1", "", array(array("msg" => "Successfully")));
        } else {
            echo $this->json_data("0", "All fields are required.", "");
        }
    }

    function my_sample() {
        $pid = $this->input->get_post("pid");
        $phlebo_job = $this->service_model->get_val("SELECT DISTINCT 
  `logistic_log`.*,
  `phlebo_master`.`name` 
FROM
  `logistic_log` 
  INNER JOIN `phlebo_master` 
    ON `logistic_log`.`phlebo_fk` = `phlebo_master`.`id` 
     INNER JOIN `logistic_sample_arrive`
     ON `logistic_log`.id=`logistic_sample_arrive`.`barcode_fk`
WHERE `logistic_log`.`status` = '1' 
  AND `phlebo_master`.`status` = '1' 
  AND `logistic_sample_arrive`.`phlebo_fk` = '" . $pid . "' 
  AND `logistic_sample_arrive`.`sample_status` = '1' 
  AND `logistic_sample_arrive`.`status`='1' 
ORDER BY `logistic_log`.`id` DESC ");
        if (!empty($phlebo_job)) {
            $cnt = 0;
            foreach ($phlebo_job as $key) {
                $phlebo_job[$cnt]["pic"] = base_url() . "upload/barcode/" . $key["pic"];
                $cnt++;
            }
            echo $this->json_data("1", "", $phlebo_job);
        } else {
            echo $this->json_data("0", "Data not available.", "");
        }
    }

    function collect_from() {
        $data = $this->service_model->master_fun_get_tbl_val('collect_from', array("status" => 1), array("name", "asc"));
        if (!empty($data)) {
            echo $this->json_data("1", "", $data);
        } else {
            echo $this->json_data("0", "Data not available.", "");
        }
    }

    function add_business_logistic_log() {
        $pid = $this->input->get_post('pid');
        $barcode = $this->input->get_post('barcode');
        $fromLab = $this->input->get_post('fromLab');
        $collect_by = $this->input->get_post('collect_by');

        //$data_ary = array_unique($data_ary);
        //$barcode = implode(",", $data_ary);
        if ($pid != '' && !empty($barcode)) {
            /* Pic upload start */
            $files = $_FILES;
            $data = array();
            $this->load->library('upload');
            $config['allowed_types'] = 'png|jpg|gif|jpeg';
            if (isset($files['userfile']) && $files['userfile']['name'] != "") {
                $config['upload_path'] = './upload/barcode/';
                $config['file_name'] = $files['userfile']['name'];
                $this->upload->initialize($config);
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0755, TRUE);
                }
                if (!$this->upload->do_upload('userfile')) {
                    $error = $this->upload->display_errors();
                    $error = str_replace("<p>", "", $error);
                    $error = str_replace("</p>", "", $error);
                    echo $this->json_data("0", $error, "");
                    die();
                } else {
                    $doc_data = $this->upload->data();
                    $filename = $doc_data['file_name'];
                    $uploads = array('upload_data' => $this->upload->data("identity"));
                    //echo $this->json_data("1", "", array(array("filename" => $filename)));
                }
            }
            /* Pic upload end */
            $collected = '';
            if ($fromLab == 'true') {
                $collected = $collect_by;
            }
            $row = $this->service_model->master_num_rows("logistic_log", array("barcode" => $barcode, "status" => 1));
            if ($row > 0) {
                $barcode_data = $this->service_model->master_fun_get_tbl_val("logistic_log", array("barcode" => $barcode, "status" => 1), array("id", "asc"));
                $b_id = $barcode_data[0]["id"];
            } else {

                $data = array(
                    "phlebo_fk" => $pid,
                    "barcode" => $barcode,
                    "collect_from" => $collected,
                    "scan_date" => date("Y-m-d H:i:s"),
                    "status" => 1,
                    "createddate" => date("Y-m-d H:i:s"),
                    "created_by" => $pid,
                    "pic" => $filename
                );
                //$this->service_model->master_fun_update1("logistic_log", array("barcode" => $b_key->barcode, "status" => 1), array("sample_status" => "0"));
                $customer_info = $this->service_model->master_fun_insert("logistic_log", $data);
                $b_id = $customer_info;
            }
            $data = array(
                "barcode_fk" => $b_id,
                "phlebo_fk" => $pid,
                "collect_from" => $collected,
                "scan_date" => date("Y-m-d H:i:s"),
                "status" => 1,
                "createddate" => date("Y-m-d H:i:s"),
                "created_by" => $pid,
                "pic" => $filename
            );
            //print_R($data); die();
            $this->service_model->master_fun_update1("logistic_sample_arrive", array("barcode_fk" => $b_id, "status" => 1), array("sample_status" => "0"));
            $customer_info = $this->service_model->master_fun_insert("logistic_sample_arrive", $data);

            echo $this->json_data("1", "", array(array("msg" => "Successfully")));
        } else {
            echo $this->json_data("0", "All fields are required.", "");
        }
    }

    function phlebo_add_job() {
        $this->load->helper("Email");
        $email_cnt = new Email;
        $customer = $this->input->get_post("customer");
        $name = $this->input->get_post("name");
        $phone = $this->input->get_post("phone");
        $email = $this->input->get_post("email");
        $noify_cust = 1;
        if ($email == '') {
            $email = 'noreply@airmedlabs.com';
        }
        $gender = $this->input->get_post("gender");
        $dob = $this->input->get_post("dob");
        $test_city = $this->input->get_post("test_city");
        $address = $this->input->get_post("address");
        $note = $this->input->get_post("note");
        $discount = $this->input->get_post("discount");
        //$payable = $this->input->get_post("payable");
        $test = $this->input->get_post("test");
        $test = explode(",", $test);
        $referral_by = $this->input->get_post("referral_by");
        $source = $this->input->get_post("source");
        $pid = $this->input->get_post("pid");
        //$phlebo = $this->input->get_post("phlebo");
        //$phlebo_date = $this->input->get_post("phlebo_date");
        // $phlebo_time = $this->input->get_post("phlebo_time");
        $type = 'android';
        $notify = 1;
        $branch = $this->input->get_post("branch");
        if ($branch == '') {
            $branch = 1;
        }

        $order_id = $this->get_job_id($this->input->get_post("test_city"));
        $date = date('Y-m-d H:i:s');
        if ($customer != '') {
            $c_data = array(
                "full_name" => $name,
                "gender" => $gender,
                "email" => $email,
                "mobile" => $phone,
                "address" => $address,
                "dob" => $dob
            );
            $this->service_model->master_fun_update("customer_master", array("id", $customer), $c_data);

            $price = 0;
            foreach ($test as $key) {
                $tn = explode("-", $key);
                if ($tn[0] == 't') {
                    $result = $this->service_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $test_city . "' AND `test_master`.`id`='" . $tn[1] . "'");
                    $price += $result[0]["price"];
                    $test_package_name[] = $result[0]["test_name"];
                }
                if ($tn[0] == 'p') {
                    //print_r(array('package_fk' => $tn[1], "city_fk" => $test_city)); die();
                    $query = $this->db->get_where('package_master_city_price', array('package_fk' => $tn[1], "city_fk" => $test_city, "status" => "1"));
                    $result = $query->result();
                    $query1 = $this->db->get_where('package_master', array('id' => $tn[1]));
                    $result1 = $query1->result();
                    $price += $result[0]->d_price;
                    $test_package_name[] = $result1[0]->title;
                }
            }
            /* Nishit book phlebo start */
            $test_for = $this->input->get_post("test_for");
            $testforself = "self";
            $family_mem_id = 0;
            if ($test_for == "new") {
                $f_name = $this->input->get_post("f_name");
                $family_relation = $this->input->get_post("family_relation");
                $relation_details = $this->service_model->get_val("SELECT * from relation_master where id='" . $family_relation . "'");
                $f_phone = $this->input->get_post("f_phone");
                $f_email = $this->input->get_post("f_email");
                $f_dob = $this->input->get_post("f_dob");
                $family_gender = $this->input->get_post("family_gender");
                if ($f_name != '' && $family_relation != '') {
                    $family_mem_id = $this->service_model->master_fun_insert("customer_family_master", array("user_fk" => $customer, "gender" => $family_gender, "dob" => $f_dob, "name" => $f_name, "relation_fk" => $family_relation, "email" => $f_email, "phone" => $f_phone, "status" => "1", "created_date" => date("Y-m-d H:i:s")));
                    $testforself = "family";
                }
            } else if ($test_for != '') {
                $family_mem_id = $test_for;
                $testforself = "family";
            }
            $address = $this->input->get_post("address");
            $date1 = $this->input->get_post("phlebo_date");
            $time_slot_id = $this->input->get_post("phlebo_time");
            $emergency = $this->input->get_post("emergency");
            $collection_charge = $this->input->get_post("collection_charge");
            if ($emergency == 1) {
                $date1 = date("Y-m-d");
                $time_slot_id = '';
                $emergency = 1;
            } else {
                $emergency = 0;
            }
            $booking_fk = $this->service_model->master_fun_insert("booking_info", array("user_fk" => $customer, "type" => $testforself, "family_member_fk" => $family_mem_id, "address" => $address, "date" => $date1, "time_slot_fk" => $time_slot_id, "emergency" => $emergency, "status" => "1", "createddate" => date("Y-m-d H:i:s")));
            /* Nishit book phlebo end */
            $data = array(
                "order_id" => $order_id,
                "cust_fk" => $customer,
                "doctor" => $referral_by,
                "other_reference" => $source,
                "date" => $date,
                "price" => $price,
                "branch_fk" => $branch,
                "status" => '6',
                "payment_type" => "Cash On Delivery",
                "discount" => $discount,
                "payable_amount" => $price,
                "test_city" => $test_city,
                "note" => $this->input->get_post('note'),
                "added_by" => $pid,
                "booking_info" => $booking_fk,
                "collection_charge" => $collection_charge,
                "notify_cust" => $noify_cust,
                "date" => date("Y-m-d H:i:s")
            );
            $insert = $this->service_model->master_fun_insert("job_master", $data);
            foreach ($test as $key) {
                $tn = explode("-", $key);
                if ($tn[0] == 't') {
                    $this->service_model->master_fun_insert("job_test_list_master", array('job_fk' => $insert, "test_fk" => $tn[1]));
                }
                if ($tn[0] == 'p') {
                    $this->service_model->master_fun_insert("book_package_master", array("cust_fk" => $uid, "package_fk" => $tn[1], 'job_fk' => $insert, "status" => "1", "type" => "2"));
                }
            }
        } else {
            $password = rand(11111111, 9999999);
            $c_data = array(
                "full_name" => $name,
                "gender" => $gender,
                "email" => $email,
                "mobile" => $phone,
                "address" => $address,
                "password" => $password,
                "dob" => $dob
            );
            $customer = $this->service_model->master_fun_insert("customer_master", $c_data);
            $config['mailtype'] = 'html';
            $this->email->initialize($config);

            $message = '<div style="padding:0 4%;">
                    <h4><b>Create Account</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your account successfully created. </p>
                        <p style="color:#7e7e7e;font-size:13px;"> Username/Email : . ' . $email . '  </p>  
                        <p style="color:#7e7e7e;font-size:13px;"> Password : ' . $password . '  </p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
            $message = $email_cnt->get_design($message);
            $this->email->to($email);
            $this->email->from($this->config->item('admin_booking_email'), 'Airmed PathLabs');
            $this->email->subject('Account Created Successfully');
            $this->email->message($message);
            if ($noify_cust == 1) {
                //$this->email->send();
            }
            $price = 0;
            $test_package_name = array();
            foreach ($test as $key) {
                $result = $this->service_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $test_city . "' AND `test_master`.`id`='" . $key . "'");
                $price += $result[0]["price"];
                $test_package_name[] = $result[0]["test_name"];
            }
            $collecion_charge = 0;
            if ($price < 300) {
                $collecion_charge = 1;
                $price = $price + 100;
            }
            /* Nishit book phlebo start */
            $test_for = $this->input->get_post("test_for");
            $testforself = "self";
            $family_mem_id = 0;
            $address = $this->input->get_post("address");
            $booking_fk = $this->service_model->master_fun_insert("booking_info", array("user_fk" => $customer, "type" => $testforself, "family_member_fk" => $family_mem_id, "address" => $address, "emergency" => 0, "status" => "1", "createddate" => date("Y-m-d H:i:s")));
            /* Nishit book phlebo end */
            $data = array(
                "order_id" => $order_id,
                "cust_fk" => $customer,
                "doctor" => $referral_by,
                "other_reference" => $source,
                "date" => $date,
                "price" => $price,
                "status" => '6',
                "payment_type" => "Cash On Delivery",
                "discount" => $discount,
                "payable_amount" => $price,
                "address" => $address,
                "branch_fk" => $branch,
                "test_city" => $test_city,
                "note" => $this->input->get_post('note'),
                "added_by" => $pid,
                "booking_info" => $booking_fk,
                "notify_cust" => $noify_cust,
                "portal" => "android",
                "collection_charge" => $collecion_charge,
                "date" => date("Y-m-d H:i:s")
            );
            // print_r($data); die();
            $insert = $this->service_model->master_fun_insert("job_master", $data);
            foreach ($test as $key) {
                $this->service_model->master_fun_insert("job_test_list_master", array('job_fk' => $insert, "test_fk" => $key));
            }
        }
        //$this->load->model('service_model');
        $file = $this->pdf_invoice($insert);
        $this->service_model->master_fun_update1("job_master", array("id" => $insert), array("invoice" => $file));
        /* Nishit code end */
        echo $this->json_data("1", "", array(array("msg" => "success")));
        //echo $this->json_data("1","",array(array("id"=>$insert)));
    }

    function pdf_invoice($id) {
        //$data["login_data"] = loginuser();
        $this->load->model('add_result_model');
        $data['query'] = $this->service_model->job_details($id);
        $data['book_list'] = array();
        $tid = explode(",", $data['query'][0]['testid']);
        $fast = array();
        if ($data['query'][0]['testid'] != '') {
            foreach ($tid as $tst_id) {
                $para = $this->service_model->get_val("SELECT t.test_name as book_name,t.id as tid,p.price as price FROM test_master as t left join test_master_city_price as p  on p.test_fk=t.id WHERE t.status='1' AND p.status='1' AND t.id='" . $tst_id . "' AND p.city_fk='" . $data['query'][0]['test_city'] . "' order by t.test_name ASC");
                array_push($data['book_list'], $para);
                $test_fast = $this->service_model->get_val("SELECT fasting_requird FROM test_master WHERE status='1' AND id='" . $tst_id . "'");
                array_push($fast, $test_fast[0]['fasting_requird']);
            }
        }
        $pid = explode("%", $data['query'][0]['packageid']);
        if ($data['query'][0]['packageid'] != '') {
            foreach ($pid as $pack_id) {
                $para = $this->service_model->get_val("SELECT p.id as pid,p.title as book_name,pr.d_price as price FROM package_master as p left join package_master_city_price as pr on pr.package_fk=p.id WHERE p.status='1' AND pr.status='1' AND p.id='" . $pack_id . "' AND pr.city_fk='" . $data['query'][0]['test_city'] . "' order by p.title ASC");
                array_push($data['book_list'], $para);
            }
        }
        if (in_array("1", $fast)) {
            $data['fasting'] = 'Fasting required for 12 hours.';
        } else {
            $data['fasting'] = 'Fasting not required for 12 hours.';
        }
        $data['time'] = $this->service_model->get_val("SELECT ts.start_time,ts.end_time FROM booking_info as b left join phlebo_time_slot as ts on b.time_slot_fk=ts.id WHERE ts.status='1' AND b.status='1' AND b.id='" . $data['query'][0]['booking_info'] . "'");
        //echo "<pre>"; print_r($data['parameter_list']); die();
        $pdfFilePath = FCPATH . "/upload/result/" . $data['query'][0]['order_id'] . "_invoice.pdf";
        $data['page_title'] = 'AirmedLabs'; // pass data to the view
        ini_set('memory_limit', '32M'); // boost the memory limit if it's low <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="?" draggable="false" class="emoji">
        $html = $this->load->view('user/user_job_invoice_pdf', $data, true); // render the view into HTML
        //$param = '"en-GB-x","A4","","",10,10,0,10,6,3,"P"'; // Landscape
        //$lorem = utf8_encode($html); // render the view into HTML
        //$html = "<!DOCTYPE html>                         <html><body>\u0627\u0644\u0643\u0647\u0631\u0628\u0627\u0621 \u0648 \u0627\u0644\u0633\u0628\u0627\u0643\u0629</body></html>      ";
        ob_end_flush();
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->autoScriptToLang = true;
        $pdf->baseScript = 1; // Use values in classes/ucdn.php  1 = LATIN
        $pdf->autoVietnamese = true;
        $pdf->autoArabic = true;
        $pdf->autoLangToFont = true;
        $pdf->AddPage('p', // L - landscape, P - portrait
                '', '', '', '', 5, // margin_left
                5, // margin right
                2, // margin top
                2, // margin bottom
                2, // margin header
                2); // margin footer
        //$pdf->AddPage('P', '', 1, 'i', 'on');
        //$pdf->SetDirectionality('rtl');
        /* $pdf->AddPage('P', // L - landscape, P - portrait
          '', '', '', '', 00, // margin_left
          0, // margin right
          0, // margin top
          0, // margin bottom
          0, // margin header
          0); */

        //$pdf->SetDisplayMode('fullpage');
        //$pdf=new mPDF('utf-8','A4');
        //$pdf->debug = true;
        // $pdf->h2toc = array('H2' => 0);
        // $html = '';
        // Split $lorem into words
        $pdf->WriteHTML($html);
        $pdf->debug = true;
        $pdf->allow_output_buffering = TRUE;
        //$pdf->SetFooter('www.' . $_SERVER['HTTP_HOST'] . '||' . $new_time); // Add a footer for good measure <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="?" draggable="false" class="emoji">
        // $pdf->WriteHTML($html); // write the HTML into the PDF
        $pdf->Output($pdfFilePath, 'F'); // save to file because we can
        $name = $data['query'][0]['order_id'] . "_invoice.pdf";
        $this->service_model->master_fun_update('job_master', $id, array("invoice" => $name));
        //redirect("/upload/result/" . $data['query'][0]['order_id'] . "_invoice.pdf");
        return $name;
    }

    function get_job_id($city = null) {
        $this->load->library("Util");
        $util = new Util();
        $new_id = $util->get_job_id($city);
        return $new_id;
    }

    function test_list() {
        $city = $this->input->get_post("city");
        /* if ($city == 333 || $city == '') {
          $data = $this->service_model->master_fun_get_tbl_val("test_master", array("status" => 1), array("test_name", "asc"));
          if (empty($data)) {
          echo $this->json_data("0", "no data available", "");
          } else {
          echo $this->json_data("1", "", $data);
          }
          } else {
          $qry1 = "SELECT * FROM test_master WHERE STATUS='1' order by test_name asc";
          $data1 = $this->service_model->get_result($qry1);
          $cnt = 0;
          foreach ($data1 as $key) {
          $data2 = $this->service_model->get_result("select * from test_master_city_price where test_fk='" . $key["id"] . "' and city_fk='" . $city . "'");
          if (!empty($data2)) {
          $data1[$cnt]["price"] = $data2[0]["price"];
          } else {
          $data1[$cnt]["price"] = "0";
          }
          $cnt++;
          }
          echo $this->json_data("1", "", $data1);
          } */
        if ($city) {
            //$qry = "select * from test_master where status='1'";
            $qry = "SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master`.`is_view`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $city . "'";
            $data2 = $this->service_model->get_result($qry);
            /* $cnt = 0;
              foreach ($data2 as $key) {
              //$tst = $this->service_model->get_result("select * from test_master_city_price where test_fk='" . $key["id"] . "' and city_fk='" . $city . "' and status='1'");
              if (!empty($tst)) {
              $data2[$cnt]["price"] = $tst[0]["price"];
              } else {
              $data2[$cnt]["price"] = "0";
              }
              $cnt++;
              } */
        } else {
            $qry = "SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master`.`is_view`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='1'";
            $data2 = $this->service_model->get_result($qry);
            /* $cnt = 0;
              foreach ($data2 as $key) {
              $data2[$cnt]["price"] = "0";
              $cnt++;
              } */
        }
        echo $this->json_data("1", "", $data2);
    }

    function get_phlebo_details() {

        $pid = $this->input->get_post("pid");
        $phlebo_data = $this->service_model->get_val("SELECT 
  `phlebo_assign`.*,
  test_cities.`name` AS city_name,
  `branch_master`.`branch_name`,
  doctor_master.`full_name` AS doctor_name 
FROM
  `phlebo_assign` 
  LEFT JOIN `test_cities` 
    ON `test_cities`.`id` = `phlebo_assign`.`city_fk` 
    LEFT JOIN `branch_master` 
    ON `branch_master`.`id`=`phlebo_assign`.`branch_fk` 
    LEFT JOIN `doctor_master`
    ON `doctor_master`.`id`=`phlebo_assign`.`doctor_fk`
     WHERE `phlebo_assign`.`status`='1' AND test_cities.`status`='1' and phlebo_assign.phlebo_fk='" . $pid . "'");
        echo $this->json_data("1", "", $phlebo_data);
    }

    function find_customer() {
        $mobile = $this->input->get_post("mobile");
        $data1 = $this->service_model->master_fun_get_tbl_val("customer_master", array("mobile" => $mobile, "status" => "1", "active" => "1"), array("id", "asc"));
        if (!empty($data1)) {
            echo $this->json_data("1", "", $data1);
        } else {
            echo $this->json_data("0", "no data available.", "");
        }
    }

    function start() {
        $arr_json = array('clock_in_id' => 0);
        $status = '0';
        $userid = $this->input->get_post('user_id');
        $start_time = $this->input->get_post('start_time');
        $start_date = $this->input->get_post('start_date');
        $longitude = $this->input->get_post('longitude');
        $latitude = $this->input->get_post('latitude');
        $ip = $this->input->get_post('ip');
        $lat = $latitude; //latitude
        $lng = $longitude; //longitude

        $address = $this->getaddress($lat, $lng);

        if ($userid != '' AND $start_time != '' AND $start_date != '') {
            $data = array(
                "user_fk" => $userid,
                "start_time" => $start_time,
                "start_date" => $start_date,
                "longitude" => $longitude,
                "latitude" => $latitude,
                "ip" => $ip,
                "address" => $address
            );
            $insert = $this->service_model->master_fun_insert("phlabo_timer", $data);
            //$insert = $this->service_model->timeadd($data);
        }
        $retVal = "{\"data\":";
        if ($insert) {
            $sess_array = array();
            $status = '1';
            $arr_json['clock_in_id'] = $insert;
        } else {
            $sess_array = array('clock_in_id' => 0);
        }

        $retVal = "{\"status\":\"" . $status . "\",";
        $retVal1 = "}";
        echo $retVal . "\"data\":" . json_encode($arr_json) . $retVal1;
    }

    function getaddress($lat, $lng) {

        $url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng=' . trim($lat) . ',' . trim($lng) . '&sensor=false';
        $json = @file_get_contents($url);
        $data = json_decode($json);
//print_R($data);
        $status = $data->status;
        if ($status == "OK")
            return $data->results[0]->formatted_address;
        else
            return false;
    }

    function stop() {
        $arr_json = array('success' => 0);
        $userid = $this->input->get_post('user_id');
        $stop_time = $this->input->get_post('stop_time');
        $stop_date = $this->input->get_post('stop_date');
        $longitude = $this->input->get_post('longitude');
        $latitude = $this->input->get_post('latitude');
        $clock_in_id = $this->input->get_post('clock_in_id');
        $status = $this->service_model->time_status($userid);
        $start_time = $this->service_model->get_start_time($status);
        if (!empty($start_time)) {
            $time = $start_time['start_time'];
            $date = $start_time['start_date'];
            $dat = $date . " " . $time;
            $st = $stop_date . " " . $stop_time;
            $datetime1 = new DateTime($dat);
            $datetime2 = new DateTime($st);
            $interval = $datetime1->diff($datetime2);
            $time = $interval->format('%H:%i:%S');
            $curl_handle = curl_init();
            curl_setopt($curl_handle, CURLOPT_URL, 'https://maps.googleapis.com/maps/api/distancematrix/json?origins=' . $start_time["latitude"] . ',' . $start_time["longitude"] . '&destinations=' . $latitude . ',' . $longitude . '&language=eng');
            curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
            curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
            $buffer = curl_exec($curl_handle);
            curl_close($curl_handle);
            if (empty($buffer)) {
                print "Nothing returned from url.<p>";
            } else {
                
            }
            $data1 = json_decode($buffer);
            $distance1 = $data1->rows;
            $distance = $distance1[0]->elements[0]->distance->text;
            $data = array(
                "stop_time" => $stop_time,
                "stop_date" => $stop_date,
                "time" => $time,
                "outlongitude" => $longitude,
                "outlatitude" => $latitude,
                "distance" => $distance
            );
            $update = $this->service_model->master_fun_update('phlabo_timer', $status, $data);
            //$update = $this->service_model->updatetime($status, $data);
        } else {
            $arr_json['success'] = "0";
        }
        $retVal = "{\"data\":";
        if ($update) {
            $sess_array = array();
            $arr_json['success'] = "1";
        } else {
            $sess_array = array('success' => 0);
            $arr_json['success'] = "1";
        }
        $retVal = "{\"status\":\"" . $arr_json['success'] . "\",";
        $retVal1 = "}";
        echo $retVal . "\"data\":" . json_encode($arr_json) . $retVal1;
        if ($clock_in_id != "") {
            $this->csvreport($clock_in_id);
        }
    }

    function city_list() {
        $data1 = $this->service_model->master_fun_get_tbl_val("test_cities", array("status" => "1"), array("name", "asc"));
        if (!empty($data1)) {
            echo $this->json_data("1", "", $data1);
        } else {
            echo $this->json_data("0", "no data available.", "");
        }
    }

    function branch_list() {
        $city = $this->input->get_post("city");
        $data1 = $this->service_model->master_fun_get_tbl_val("branch_master", array("city" => $city, "status" => "1"), array("branch_name", "asc"));
        if (!empty($data1)) {
            echo $this->json_data("1", "", $data1);
        } else {
            echo $this->json_data("0", "no data available.", "");
        }
    }

}
