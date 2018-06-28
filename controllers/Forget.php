<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Forget extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper(array('form', 'url'));
        $this->load->model('master_model');
        $this->load->library('form_validation');
        $this->app_track();
        $this->load->library('email');
    }

    function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }

    function index() {
        $this->load->library('email');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');


        if ($this->form_validation->run() == FALSE) {
            $data = '';
            if ($this->session->userdata('getmsg1') != null) {
                $data['getmsg1'] = $this->session->userdata("getmsg1");
                $this->session->unset_userdata('getmsg1');
            }
            $this->load->view('forget_password_view', $data);
        } else {
            $email = $this->input->post('email');
            $check_email = $this->master_model->master_num_rows("admin_master", array("email" => $email, 'status' => '1'));
            if ($check_email == '0') {
                $this->session->set_userdata('getmsg1', array("Invalid email Please Check It!"));
                redirect('forget', 'refresh');
            }
            $this->load->helper('string');
            $rs = random_string('alnum', 5);
            $data = array(
                'rs' => $rs
            );
            $this->db->where('email', $email);
            $this->db->where('status', '1');
            // $this->db->where('type', '1');   
            $this->db->update('admin_master', $data);
            //now we will send an email
            $config['mailtype'] = 'html';
            $this->email->initialize($config);
            $message = "You recently requested to reset your password for your  Patholab Account. Click the button below to reset it.<br/><br/>";
            $message .= "<a href='" . base_url() . "get_password/index/" . $rs . "' style='background-color:#dc4d2f;color:#ffffff;display:inline-block;font-size:15px;line-height:45px;text-align:center;width:200px;border-radius:3px;text-decoration:none;'>Reset your password</a><br/><br/>";
            $message .= "If you did not request a password reset, please ignore this email or reply to let us know.<br/><br/>";
            $message .= "Thanks <br/> Patholab";
            $this->email->to($email);
            $this->email->from('support@virtualheight.com', 'Airmed PathLabs');
            $this->email->subject('Patholab Reset your forgotten Password');
            $this->email->message($message);
            $this->email->send();
            // echo $this->email->print_debugger();
            //  echo "<script>alert('Please check your email address')</script>";
            $this->session->set_userdata('getmsg', array("We have Sent you a link to change your password Please Check It!"));
            redirect('login', 'refresh');
        }
    }

    function manage_test_master() {
        $check_email = $this->master_model->get_val("SELECT * FROM `test_master_old` WHERE STATUS='1' ORDER BY test_name ASC");
        $cnt = 0;
        $qry = "insert  into `test_master`(`id`,`TEST_CODE`,`test_name`,`PRINTING_NAME`,`description`,`SECTION_CODE`,`LAB_COST`,`price`,`status`,`popular`,`fasting_requird`) VALUES ";
        $tmcp_qry = "insert  into `test_master_city_price`(`test_fk`,`city_fk`,`price`,`status`) VALUES ";
        $pre_test_name = "";
        foreach ($check_email as $key) {
            if ($pre_test_name != trim($key["test_name"])) {
                $tid = $key["id"];
                $qry .= ",(" . $key["id"] . ",NULL,'" . $key["test_name"] . "',NULL,NULL,'" . $key["SECTION_CODE"] . "',NULL,900," . $key["status"] . ",NULL," . $key["fasting_requird"] . ")";
                $check_email1 = $this->master_model->get_val("SELECT * FROM `test_master_city_price_old` WHERE STATUS='1' AND test_fk='" . $key["id"] . "'");
                $tmcp_qry .= ",(" . $key["id"] . ",'" . $check_email1[0]["city_fk"] . "','" . $check_email1[0]["price"] . "','1')";
            } else {
                $check_email1 = $this->master_model->get_val("SELECT * FROM `test_master_city_price_old` WHERE STATUS='1' AND test_fk='" . $key["id"] . "'");
                $tmcp_qry .= ",(" . $tid . ",'" . $check_email1[0]["city_fk"] . "','" . $check_email1[0]["price"] . "','1')";
            }
            $cnt++;
            $pre_test_name = trim($key["test_name"]);
        }
        echo $qry;
        echo "<br><br><br>";
        echo $tmcp_qry;
        //echo "<pre>";
        //print_r($check_email);
    }

    function delete_test_deblicate() {
        $check_email = $this->master_model->get_val("SELECT * FROM `test_master_city_price` WHERE STATUS='1' and city_fk='1' ORDER BY test_fk ASC");
        $cnt = 0;
        $pre_test_name = "";
        $tmcp_qry = "insert  into `test_master_city_price`(`test_fk`,`city_fk`,`price`,`status`) VALUES ";
        $id_ary = array();
        foreach ($check_email as $key) {
            $tmcp_qry .= ",('" . $key["test_fk"] . "','6','" . $key["price"] . "','1')";
        }
        echo $tmcp_qry;
    }

    function varify_captcha() {
        $recaptchaResponse = trim($this->input->get_post('g-recaptcha-response'));
        $google_url = "https://www.google.com/recaptcha/api/siteverify";
        $secret = '6Ld5_x8UAAAAAGn_AV4406lg29xu2hpQQJMaD2BC';
        $ip = $_SERVER['REMOTE_ADDR'];
        $url = $google_url . "?secret=" . $secret . "&response=" . $recaptchaResponse . "&remoteip=" . $ip;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
        $res = curl_exec($curl);
        curl_close($curl);
        $res = json_decode($res, true);
        if ($res['success'] == true) {
            return 1;
        } else {
            return 0;
        }
    }

    function test_email() {
        $config['mailtype'] = 'html';

        $this->email->initialize($config);
        $config['mailtype'] = 'html';
        $this->email->initialize($config);

        $message = '<div style="padding:0 4%;">
                    <h4><b>Dear </b>Nishit</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Cashback Credited in your Wallet. </p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
        $this->email->to('pinkesh@virtualheight.com,nishit@virtualheight.com');
        $this->email->from('donotreply@airmedpathlabs.com', 'Test AirmedLabs');
        $this->email->subject('CashBack');
        $this->email->message($message);
        $this->email->send();
        echo "Done";
    }

}

?>