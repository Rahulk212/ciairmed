<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Justdial extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('justdial_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('email');
        $this->load->helper('string');
        $this->load->library('PhpImap/Mailbox');
        $this->load->library('PhpImap/IncomingMail');
        $this->load->library('JustDialEmail');
    }

    function cron() {

        $mailbox = new Mailbox('{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX', 'airmedpathologylabs@gmail.com', '12345678airmed');

        $data_mail = array("from" => "", "name" => "", "search_date_time" => "", "city" => "", "phone" => "", "email" => "");
        $mailsIds = $mailbox->searchMailbox('UNSEEN');
        /* print_R($mailsIds);
          $mailsIds = array(39786);
         * 
         */
        if (!$mailsIds) {
            die('Mailbox is empty');
            $this->db->close();
            die();
        } else {
            $this->load->helper("Email");
            $email_cnt = new Email;
            $this->load->helper("sms");
            $notification = new Sms();
            foreach ($mailsIds as $email_number) {
                $mail = $mailbox->getMail($email_number);
                $email = $mail->fromAddress;
                $email = "ahmfeedback@justdial.com";
                $input = ($mail->textHtml);
                //              $_SESSION['input'] = $input = ($mail->textHtml);
//                echo "<textarea>" . $input . "</textarea>";

                $mailBody = new JustDialEmail($mail->textHtml);
                $object = $mailBody->getAhmGeedbackData();
                $object = $mailBody->getDBdetails();
                $object["from"] = $email;
                $object["mailId"] = $email_number;
                $object["html"] = $mail->textHtml;
                $data['query'] = $this->justdial_model->save($object);
                /* Nishit send mail and sms start */
                $condition = array();
                //$object["email"] = "nishit@virtualheight.com";
                $condition["name"] = $object["name"];
                $condition["email"] = $object["email"];
                $condition["phone"] = $object["phone"];
                if (!empty($condition["email"])) {
                    $this->load->helper("Email");
                    $email_cnt = new Email;
                    $message = '<div style="padding:0 4%;">
                        <p>Welcome to Airmed pathology labs. The Lab At Your Doorstep. Our blood collection guy will reach you 24x7 with free sample collection. We provide service across Ahmedabad and Gandhinagar.
Book through the mobile app. www.AirmedLabs.com For booking a test you can also call : <a href="tel:8101161616">8101 16 16 16<a>.
Download App : https://goo.gl/F2vOau</p>
                </div>';
                    $message = $email_cnt->get_design($message);
                    $config['mailtype'] = 'html';
                    $this->email->initialize($config);
                    $this->email->to($condition["email"]);
                    $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
                    $this->email->subject('Welcome');
                    $this->email->message($message);
                    $this->email->send();
                }

                if (!empty($condition["phone"])) {
                    $sms_message = $this->justdial_model->master_fun_get_tbl_val("sms_master", array('status' => '1', "title" => "welcome_message"), array("id", "asc"));
                    $sms_message = $sms_message[0]["message"];

                    $muli_mobile = explode(",", $condition["phone"]);
                    foreach ($muli_mobile as $sms_key) {
                        //$this->justdial_model->master_fun_insert("admin_alert_sms", array("mobile_no" => trim($sms_key), "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
                        $notification::send(trim($sms_key), $sms_message);
                    }
                }
                /* Nishit send mail and sms end */
            }
        }
        //  $mailbox->disconnect();
        echo "test";
    }

    function testmail() {

                    $this->load->helper("Email");
                    $email_cnt = new Email;
                    $message = '<div style="padding:0 4%;">
                        <p>Welcome to Airmed pathology labs. The Lab At Your Doorstep. Our blood collection guy will reach you 24x7 with free sample collection. We provide service across Ahmedabad and Gandhinagar.
Book through the mobile app. www.AirmedLabs.com For booking a test you can also call : <a href="tel:8101161616">8101 16 16 16<a>.
Download App : <a href="https://goo.gl/F2vOau">https://goo.gl/F2vOau</a></p>
                </div>';
                  echo   $message = $email_cnt->get_design($message);
                    $config['mailtype'] = 'html';
                    $this->email->initialize($config);
                    $this->email->to('hiten@virtualheight.com');
                    $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
                    $this->email->subject('Welcome');
                    $this->email->message($message);
                    $this->email->send();
					 $this->load->helper("sms");
					/* $notification = new Sms(); 
					  $sms_message = $this->justdial_model->master_fun_get_tbl_val("sms_master", array('status' => '1', "title" => "welcome_message"), array("id", "asc"));
                   echo $sms_message = $sms_message[0]["message"];
					   $notification::send(trim("9979774646"), $sms_message);*/
    }

}
