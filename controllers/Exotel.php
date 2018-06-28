<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Exotel extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('register_model');
        $this->load->helper('string');
        $this->load->helper('url');
        $this->app_track();
    }

    function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }

    function smsclosetime() {

        $calldata = $this->input->get();
        $encode_data = json_encode($calldata);
        $getuserid = $this->register_model->master_fun_insert('exotel_call_data', array("callsid" => $this->input->get('CallSid'), 'data' => $encode_data));
        $mobile = $this->input->get('From');
        $this->load->helper("sms");
        $notification = new Sms();
        $sms_message = "Sorry we cannot take your call right now. You can download AIrmed mobile app or call us after 7 AM. Thank you for calling Airmed Pathology labs. Download Airmed mobile app to book any test 24X7 and get 30% cashback. https://goo.gl/F2vOau";
        $notification::send($mobile, $sms_message);
    }

    function add() {
        $calldata = $this->input->get();
        $encode_data = json_encode($calldata);
        $getuserid = $this->register_model->master_fun_insert('exotel_call_data', array("callsid" => $this->input->get('CallSid'), 'data' => $encode_data));
        $update = $this->register_model->master_fun_get_tbl_val("exotel_calls", array("callsid" => $this->input->get('CallSid')), array("id", "asc"));
        echo count($update) . '-';
        if (count($update) > 0) {
            if (isset($_GET['From']) && !isset($_GET['AgentEmail'])) {
                $data = array(
                    "CallFrom" => $this->input->get('From'),
                    "CallTo" => $this->input->get('To'),
                    "Direction" => $this->input->get('Direction'),
                    "DialCallDuration" => $this->input->get('DialCallDuration'),
                    "StartTime" => $this->input->get('StartTime'),
                    "EndTime" => $this->input->get('EndTime'),
                    "CallType" => $this->input->get('CallType'),
                    "flow_id" => $this->input->get('flow_id'),
                    "tenant_id" => $this->input->get('tenant_id'),
                    "DialWhomNumber" => $this->input->get('DialWhomNumber'),
                    "CurrentTime" => $this->input->get('CurrentTime'),
                    "RecordingUrl" => $this->input->get('RecordingUrl'),
                    "AgentStatus" => "free"
                );
                //print_r($data);
                //print_r(array("callsid" => $this->input->get('CallSid')));
                echo $this->input->get('CallSid') . '-';
                $getuserid = $this->register_model->master_fun_update1('exotel_calls', array("callsid" => $this->input->get('CallSid')), $data);
                /* Pinkesh send sms code start */
                $number = $this->input->get('From');
                $detail = $this->register_model->master_fun_get_tbl_val("customer_master", array("mobile" => substr($this->input->get('From'), 1)), array("id", "asc"));
                $sms_message = "";
                if ($this->input->get('CallType') == 'call-attempt') {
                    $sms_message .= "You have missed a call from " . $number . ".";
                } else {
                    $sms_message .= "You have answered a call of " . $number . ".";
                }
                if ($detail) {
                    $sms_message .= " User name is " . $detail[0]['full_name'];
                }
                echo $sms_message;
                echo $this->input->get('DialWhomNumber');
                $this->load->helper("sms");
                $notification = new Sms();
                if ($this->input->get('DialWhomNumber') == '') {
                    $mobile = '9979774646';
                    //$mobile='9601198035';
                } else {
                    $mobile = $this->input->get('DialWhomNumber');
                }
                $mb_length = strlen($mobile);
                if ($mb_length == 10) {
                    $notification::send($mobile, $sms_message);
                }
                if ($mb_length == 11 || $mb_length == 12 || $mb_length == 13) {
                    $check_phone = substr($mobile, 0, 2);
                    $check_phone1 = substr($mobile, 0, 1);
                    $check_phone2 = substr($mobile, 0, 3);
                    if ($check_phone2 == '+91') {
                        $get_phone = substr($mobile, 3);
                        $notification::send($get_phone, $sms_message);
                    }
                    if ($check_phone == '91') {
                        $get_phone = substr($mobile, 2);
                        $notification::send($get_phone, $sms_message);
                    }
                    if ($check_phone1 == '0') {
                        echo $get_phone = substr($mobile, 1);
                        $notification::send($get_phone, $sms_message);
                    }
                }
            }
            /* if(isset($_GET['RecordingUrl'])){
              $data3=array(
              "RecordingUrl" => $this->input->get('RecordingUrl')
              );
              $getuserid= $this->register_model->master_fun_update1('exotel_calls',array("callsid" => $this->input->get('CallSid')), $data3);
              } */
            if (isset($_GET['AgentEmail'])) {
                $data2 = array(
                    "AgentEmail" => $this->input->get('AgentEmail'),
                    "AgentStatus" => $this->input->get('Status'),
                    "DialWhomNumber" => $this->input->get('DialWhomNumber'),
                    "RecordingUrl" => $this->input->get('RecordingUrl')
                );
                $getuserid = $this->register_model->master_fun_update1('exotel_calls', array("callsid" => $this->input->get('CallSid')), $data2);
            }
        } else {
            if (isset($_GET['From'])) {
                $data = array(
                    "CallSid" => $this->input->get('CallSid'),
                    "CallFrom" => $this->input->get('From'),
                    "CallTo" => $this->input->get('To'),
                    "Direction" => $this->input->get('Direction'),
                    "DialCallDuration" => $this->input->get('DialCallDuration'),
                    "StartTime" => $this->input->get('StartTime'),
                    "EndTime" => $this->input->get('EndTime'),
                    "CallType" => $this->input->get('CallType'),
                    "flow_id" => $this->input->get('flow_id'),
                    "tenant_id" => $this->input->get('tenant_id'),
                    "CurrentTime" => $this->input->get('CurrentTime')
                );
                $getuserid = $this->register_model->master_fun_insert('exotel_calls', $data);
            }
        }

        /* Pinkesh send sms code end */
        echo $getuserid;
        /* $post_data = array(
          'From' => $this->input->get('From'),
          'To' => $this->input->get('To'),
          'CallerId' => $this->input->get('CallSid'),
          'Url' => "http://my.exotel.in/exoml/start/".$this->input->get('flow_id'),
          'CallType' => "trans",
          'StatusCallback' => "http://websitedemo.co.in/phpdemoz/patholab/user_call_master" //This is also also optional
          );
          $exotel_sid = "airmedlabs"; // Your Exotel SID - Get it here: http://my.exotel.in/Exotel/settings/site#exotel-settings
          $exotel_token = "181bede57b8a336634e2a83be37a68c40c174b4f"; // Your exotel token - Get it here: http://my.exotel.in/Exotel/settings/site#exotel-settings

          $url = "https://".$exotel_sid.":".$exotel_token."@twilix.exotel.in/v1/Accounts/".$exotel_sid."/Calls/connect";

          $ch = curl_init();
          curl_setopt($ch, CURLOPT_VERBOSE, 1);
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_FAILONERROR, 0);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
          curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));

          $http_result = curl_exec($ch);
          $error = curl_error($ch);
          $http_code = curl_getinfo($ch ,CURLINFO_HTTP_CODE);

          curl_close($ch);

          print "Response = ".print_r($http_result); */
    }

}

?>
