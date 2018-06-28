<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Add_wallet_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('email');
        $this->load->helper('string');
        $this->load->model('user_wallet_model');
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        $maxid = $this->user_wallet_model->total_wallet($uid);
        $data['total'] = $this->user_wallet_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "id" => $maxid), array("id", "asc"));
        if (!empty($data['total'])) {
            $this->data['wallet_amount'] = $data['total'][0]['total'];
            /* pinkesh code start */
            $this->load->model('user_master_model');
            $data['links'] = $this->user_master_model->master_fun_get_tbl_val("patholab_home_master", array("status" => 1), array("id", "asc"));
            $this->data['all_links'] = $data['links'];
        } else {
            $this->data['all_links'] = array();
        }
        /* pinkesh code start */
                $this->app_track();
    }
	function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }
    function wallet_history() {
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        $data['success'] = $this->session->flashdata("success");
        $data['error'] = $this->session->flashdata("error");
        // $data['pay_success']$this->session->flashdata('success_msg');
        //$data['query'] =  $this->user_wallet_model->wallet_history($uid);
        $maxid = $this->user_wallet_model->total_wallet($uid);
        $data['total'] = $this->user_wallet_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "id" => $maxid), array("id", "asc"));
        $data['count'] = $this->user_wallet_model->wallet_history($uid);
        $totalRows = count($data["count"]);
        $config = array();
        $config["base_url"] = base_url() . "add_wallet_master/wallet_history/";
        $config["total_rows"] = $totalRows;
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $sort = $this->input->get("sort");
        $by = $this->input->get("by");
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["query"] = $this->user_wallet_model->wallet_history1($uid, $config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();
        $this->load->view('user/header', $data);
        $this->load->view('user/add_wallet', $data);
        $this->load->view('user/footer');
    }

    function payumoney() {
        $this->load->library('session');
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data['payumoneydetail'] = $this->config->item('payumoneydetail');
        $data["login_data"] = loginuser();
        //print_r($data["login_data"]); die();
        $uid = $data["login_data"]['id'];
        $data['payamount'] = $this->input->post('amount');
        $data['rndtxn'] = random_string('numeric', 20);
        $destail = $this->user_wallet_model->master_fun_get_tbl_val("customer_master", array("status" => 1, "id" => $uid), array("id", "asc"));
        $data['user_detail'] = $destail;
        //print_r($data); die();
        $this->load->view('user/payumoney', $data);
    }

    function success_payumoney() {
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $this->load->helper("Email");
        $email_cnt = new Email;
        $data["login_data"] = loginuser();
        //print_r($data["login_data"]); die();
        $uid = $data["login_data"]['id'];
        $response = $_REQUEST;
        //print_R($response); die();
        $trnscaton_id = $response['txnid'];
        $amount = $response['amount'];
        $status = $response['status'];
        $paydate = $response['addedon'];
        $this->load->library('session');

        $t = json_encode($response);
        //$user_id = $this->session->userdata('user_id');
        //$consumer = $this->user_model->get_user_by_id($user_id);
        //  $consumer_name = $consumer["user_first_name"] . " " . $consumer["user_last_name"];
        //  $consumer_number = $consumer["user_phone"];
        //  $consumer_address = $consumer["user_address_1"];
        // $the_quote = $this->consumer_model->get_quotes($job_id, $user_id);
        if ($response['status'] == "success") {

            $query = $this->user_wallet_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $uid), array("id", "desc"));
            $total = $query[0]['total'];
            $data1 = array("payomonyid" => $trnscaton_id,
                "amount" => $amount,
                "paydate" => $paydate,
                "status" => $status,
                "uid" => $uid,
                "type" => "wallet",
                "data" => $t,
            );
            $insert = $this->user_wallet_model->master_fun_insert("payment", $data1);
            $data1 = array(
                "cust_fk" => $uid,
                "credit" => $amount,
                "total" => $total + $amount,
                "payment_id" => $insert,
                "created_time" => date('Y-m-d H:i:s')
            );
            $insert = $this->user_wallet_model->master_fun_insert("wallet_master", $data1);
            $destail = $this->user_wallet_model->master_fun_get_tbl_val("customer_master", array("status" => 1, "id" => $uid), array("id", "asc"));
            $config['mailtype'] = 'html';
            $this->email->initialize($config);
            /* $message = "You recently requested to reset your password for your  Patholab Account. Click the button below to reset it.<br/><br/>";
              $message .= "<a href='".base_url()."user_get_password/index/".$rs."' style='background-color:#dc4d2f;color:#ffffff;display:inline-block;font-size:15px;line-height:45px;text-align:center;width:200px;border-radius:3px;text-decoration:none;'>Reset your password</a><br/><br/>";
              $message .= "If you did not request a password reset, please ignore this email or reply to let us know.<br/><br/>";
              $message .= "Thanks <br/> Patholab"; */
            $message = '<div style="padding:0 4%;">
                    <h4 style="color:#67B1A3;text-decoration: underline;">Money Added to wallet</h4>
                        <p style="color:#7e7e7e;font-size:13px;">RS.' . $amount . ' successfully added to Wallet. Total Wallet Amount is RS.' . ($total + $amount) . ' </p>
                        <p style="color:#7e7e7e;font-size:13px;">Your Transaction id is ' . $trnscaton_id . '</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
            $message = $email_cnt->get_design($message);
            $this->email->to($destail[0]['email']);
            $this->email->from('donotreply@airmed.com', 'AirmedLabs');
            $this->email->subject('Money Added To Wallet');
            $this->email->message($message);
            $this->email->send();
            $this->session->set_flashdata('success', "payment");
            $url = "add_wallet_master/wallet_history/";
            redirect($url);
        }
    }

    function fail_payumoney() {
        $this->session->set_flashdata('payment_unsuccess', array("Oops somthing wrong Try again!"));
        $url = "/";
        redirect($url);
    }

    function payumoneydemo() {
        $postUrl = 'https://test.payumoney.com/payment/payment/createPayment?';
        $toSend = 'sourceReferenceId=
5341159&merchantKey=gtKFFx&merchantTransactionId=5341159&totalAmount=5000&customerName=CUSTOMER2&customerEmail=ABC@XYZ.COM
&customerPhone=0123456789&productInfo=SOMEPRODUCT&paymentDate=31-07-2014
&paymentMode=COD&paymentStatus=Success';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $toSend);
        curl_setopt($ch, CURLOPT_URL, $postUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('authorization: 
  tGmNEyYrMX9ZF7C7xpBJBjQ+6Xx5BH8n8t/DVvl1LbI='));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        echo $out = curl_exec($ch);
    }

    function payumoneysub() {
        print_r($_GET);
        $hash = '';
// Hash Sequence
        $hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
        if (empty($posted['hash']) && sizeof($posted) > 0) {
            if (
                    empty($posted['key']) || empty($posted['txnid']) || empty($posted['amount']) || empty($posted['firstname']) || empty($posted['email']) || empty($posted['phone']) || empty($posted['productinfo']) || empty($posted['surl']) || empty($posted['furl']) || empty($posted['service_provider'])
            ) {
                echo $formError = 1;
            } else {
                //$posted['productinfo'] = json_encode(json_decode('[{"name":"tutionfee","description":"","value":"500","isRequired":"false"},{"name":"developmentfee","description":"monthly tution fee","value":"1500","isRequired":"false"}]'));
                $hashVarsSeq = explode('|', $hashSequence);
                $hash_string = '';
                foreach ($hashVarsSeq as $hash_var) {
                    $hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
                    $hash_string .= '|';
                }
                $hash_string .= $SALT;
                echo $hash = strtolower(hash('sha512', $hash_string));
                $this->db->close();
                die();
                $action = $PAYU_BASE_URL . '/_payment';
            }
        } elseif (!empty($posted['hash'])) {
            $hash = $posted['hash'];
            $action = $PAYU_BASE_URL . '/_payment';
        }
    }

    function pdf_report() {

        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        date_default_timezone_set("UTC");
        $new_time = date("Y-m-d H:i:s", strtotime('+3 hours'));
// As PDF creation takes a bit of memory, we're saving the created file in /downloads/reports/
        $filename = "payment_history_" . time() . '.pdf';
        $pdfFilePath = FCPATH . "/download/$filename";
        $data['page_title'] = 'Powers for Investments Co.'; // pass data to the view
        //  ini_set('memory_limit', '32M'); // boost the memory limit if its low <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="😉" draggable="false" class="emoji">
        $data['query'] = $this->user_wallet_model->wallet_history($uid);
        $html = $this->load->view('user/wallet_pdf', $data, true); // render the view into HTML
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->autoScriptToLang = true;
        $pdf->baseScript = 1; // Use values in classes/ucdn.php  1 = LATIN
        $pdf->autoVietnamese = true;
        $pdf->autoArabic = true;
        $pdf->autoLangToFont = true;
        $pdf->WriteHTML($html);
        //$pdf->debug = true
        $pdf->SetFooter('www.' . $_SERVER['HTTP_HOST'] . '||' . $new_time); // Add a footer for good measure <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="😉" draggable="false" class="emoji">
        // $pdf->WriteHTML($html); // write the HTML into the PDF
        $pdf->Output($pdfFilePath, 'F'); // save to file because we can
        redirect("/download/$filename");
    }

}

?>
