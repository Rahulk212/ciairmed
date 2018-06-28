<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Get_server_data extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('upload_server');
        $this->load->library('form_validation');
        $this->load->library('email');
        $this->load->helper('string');
        $data["login_data"] = logindata();
        $this->app_track();
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '512M');
    }

    function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }

    function saveLocalData() {
        ini_set('max_input_vars', '2000');
        $status = $this->input->post("status");
        $this->load->library("Util");
        $util = new Util();
        //echo "<pre>"; print_r($_POST);
        if ($status == 1) {
            $data = $this->input->post("data");
            /* Upload data start */
            foreach ($data as $key) {
                //echo "<pre>";print_R($key);
                $job_details = $key["job_details"];
                $booking_info = $key["booking_info"];
                $book_test = $key["book_test"];
                $book_package = $key["book_package"];
                $booked = $key["booked"];
                $receiv_payment = $key["receiv_payment"];
                $approve_job = $key["approve_job"];
                $job_creditors = $key["job_creditors"];
                $deleted_test_package = $key["deleted_test_package"];
                $phlebo_assign_job = $key["phlebo_assign_job"];
                $customer = $key["customer"];
                $customer_family = $key['customer_family'];
                $payment = $key["payment"];
                $job_log = $key["job_log"];

                $job_check = $this->master_model->get_val("select id from job_master where branch_fk='" . $job_details[0]["branch_fk"] . "' AND local_fk='" . $job_details[0]["id"] . "' and status !='0'");
                if (empty($job_check)) {
                    $customer_check = $this->master_model->get_val("select id from customer_master where id='" . $customer[0]["id"] . "' and mobile='" . $customer[0]["mobile"] . "'");
                    if (!empty($customer_check)) {
                        $customer_info_add = array(
                            "full_name" => $customer[0]["full_name"],
                            "gender" => $customer[0]["gender"],
                            "email" => $customer[0]["email"],
                            "dob" => $customer[0]["dob"],
                            "address" => $customer[0]["address"]
                        );
                        $this->master_model->master_fun_update1("customer_master", array("id" => $customer[0]["id"]), $customer_info_add);
                        $new_cusomer_fk = $customer_check[0]["id"];
                    } else {
                        $customer_info_add = array(
                            "full_name" => $customer[0]["full_name"],
                            "gender" => $customer[0]["gender"],
                            "email" => $customer[0]["email"],
                            "password" => $customer[0]["password"],
                            "age" => $customer[0]["age"],
                            "age_type" => $customer[0]["age_type"],
                            "dob" => $customer[0]["dob"],
                            "mobile" => $customer[0]["mobile"],
                            "address" => $customer[0]["address"],
                            "country" => $customer[0]["country"],
                            "state" => $customer[0]["state"],
                            "city" => $customer[0]["city"],
                            "pic" => $customer[0]["pic"],
                            "active" => $customer[0]["active"],
                            "device_id" => $customer[0]["device_id"],
                            "status" => $customer[0]["status"],
                            "type" => $customer[0]["type"],
                            "test_city" => $customer[0]["test_city"],
                            "created_date" => $customer[0]["created_date"]
                        );
                        $new_cusomer_fk = $this->master_model->master_fun_insert("customer_master", $customer_info_add);
                    }

                    $customer_family_check = $this->master_model->get_val("select id from customer_family_master where id='" . $customer_family[0]["id"] . "' and user_fk='" . $customer_family[0]["user_fk"] . "'");
                    if (!empty($customer_family_check)) {
                        $customer_family_add = array(
                            "user_fk" => $new_cusomer_fk,
                            "name" => $customer_family[0]["name"],
                            "relation_fk" => $customer_family[0]["relation_fk"],
                            "dob" => $customer_family[0]["dob"],
                            "gender" => $customer_family[0]["gender"]
                        );
                        $this->master_model->master_fun_update1("customer_family_master", array("id" => $customer[0]["id"]), $customer_family_add);
                        $customer_family_fk = $customer_family_check[0]["id"];
                    }
                    $order_id = $util->get_job_id($job_details[0]["test_city"]);
                    $job_details_add = array(
                        "branch_fk" => $job_details[0]["branch_fk"],
                        "order_id" => $order_id,
                        "cust_fk" => $new_cusomer_fk,
                        "date" => $job_details[0]["date"],
                        "price" => $job_details[0]["price"],
                        "doctor" => $job_details[0]["doctor"],
                        "other_reference" => $job_details[0]["other_reference"],
                        "status" => $job_details[0]["status"],
                        "sample_collection" => $job_details[0]["sample_collection"],
                        "payment_type" => $job_details[0]["payment_type"],
                        "mobile" => $job_details[0]["mobile"],
                        "address" => $job_details[0]["address"],
                        "landmark" => $job_details[0]["landmark"],
                        "city" => $job_details[0]["city"],
                        "state" => $job_details[0]["state"],
                        "pin" => $job_details[0]["pin"],
                        "payable_amount" => $job_details[0]["payable_amount"],
                        "discount" => $job_details[0]["discount"],
                        "discount_note" => $job_details[0]["discount_note"],
                        "test_city" => $job_details[0]["test_city"],
                        "views" => $job_details[0]["views"],
                        "otp" => $job_details[0]["otp"],
                        "note" => $job_details[0]["note"],
                        "added_by" => $job_details[0]["added_by"],
                        "portal" => $job_details[0]["portal"],
                        "booking_info" => $job_details[0][""],
                        "invoice" => $job_details[0][""],
                        "report_status" => $job_details[0]["report_status"],
                        "deleted_by" => $job_details[0]["deleted_by"],
                        "dispatch" => $job_details[0]["dispatch"],
                        "collection_charge" => $job_details[0]["collection_charge"],
                        "report_approve_by" => $job_details[0]["report_approve_by"],
                        "notify_cust" => $job_details[0]["notify_cust"],
                        "payment_type_fk" => $job_details[0]["payment_type_fk"],
                        "ack" => $job_details[0][""],
                        "barcode" => $job_details[0]["barcode"],
                        "sample_from" => $job_details[0]["sample_from"],
                        "clinical_history" => $job_details[0]["clinical_history"],
                        "prescription_message" => $job_details[0]["prescription_message"],
                        "prescription_file" => $job_details[0]["prescription_file"],
                        "document" => $job_details[0]["document"],
                        "is_new" => $job_details[0]["is_new"],
                        "scan_img" => $job_details[0]["scan_img"],
                        "local_fk" => $job_details[0]["id"]
                    );
                    $new_job_fk = $this->master_model->master_fun_insert("job_master", $job_details_add);
                    $booking_info_add = array(
                        "user_fk" => $new_cusomer_fk,
                        "type" => $booking_info[0]["type"],
                        "family_member_fk" => $customer_family_fk,
                        "address" => $booking_info[0]["address"],
                        "landmark" => $booking_info[0]["landmark"],
                        "date" => $booking_info[0]["date"],
                        "time_slot_fk" => $booking_info[0]["time_slot_fk"],
                        "emergency" => $booking_info[0]["emergency"],
                        "status" => $booking_info[0]["status"],
                        "createddate" => $booking_info[0]["createddate"]
                    );
                    $new_booking_info_fk = $this->master_model->master_fun_insert("booking_info", $booking_info_add);

                    $this->master_model->master_fun_update1("job_master", array("id" => $new_job_fk), array("booking_info" => $new_booking_info_fk));

                    if (!empty($book_test)) {
                        foreach ($book_test as $t_key) {
                            $book_test_add = array(
                                "job_fk" => $new_job_fk,
                                "test_fk" => $t_key["test_fk"],
                                "cust_fk" => $t_key["cust_fk"],
                                "status" => $t_key["status"],
                                "is_approve" => $t_key["is_approve"],
                                "price" => $t_key["price"],
                                "is_panel" => $t_key["is_panel"]
                            );
                            $this->master_model->master_fun_insert("job_test_list_master", $book_test_add);
                        }
                    }
                    if (!empty($book_package)) {
                        foreach ($book_package as $t_key) {
                            $book_package_add = array(
                                "job_fk" => $new_job_fk,
                                "package_fk" => $t_key["package_fk"],
                                "cust_fk" => $t_key["cust_fk"],
                                "status" => $t_key["status"],
                                "date" => $t_key["date"],
                                "price" => $t_key["price"]
                            );
                            $this->master_model->master_fun_insert("book_package_master", $book_package_add);
                        }
                    }
                    if (!empty($booked)) {
                        foreach ($booked as $t_key) {
                            $booked_package_add = array(
                                "job_fk" => $new_job_fk,
                                "test_city" => $t_key["test_city"],
                                "test_fk" => $t_key["test_fk"],
                                "panel_fk" => $t_key["panel_fk"],
                                "price" => $t_key["price"],
                                "status" => $t_key["status"],
                                "created_date" => $t_key["created_date"],
                                "updated_date" => $t_key["updated_date"],
                                "approve_by" => $t_key["approve_by"]
                            );
                            $this->master_model->master_fun_insert("booked_job_test", $booked_package_add);
                        }
                    }
                    if (!empty($receiv_payment)) {
                        foreach ($receiv_payment as $t_key) {
                            $receiv_payment_add = array(
                                "job_fk" => $new_job_fk,
                                "amount" => $t_key["amount"],
                                "createddate" => $t_key["createddate"],
                                "status" => $t_key["status"],
                                "added_by" => $t_key["added_by"],
                                "type" => $t_key["type"],
                                "paypal_log_fk" => $t_key["paypal_log_fk"],
                                "remark" => $t_key["remark"],
                                "payment_type" => $t_key["payment_type"],
                                "phlebo_fk" => $t_key["phlebo_fk"]
                            );
                            $this->master_model->master_fun_insert("job_master_receiv_amount", $receiv_payment_add);
                        }
                    }
                    if (!empty($approve_job)) {
                        foreach ($approve_job as $t_key) {
                            $approve_job_add = array(
                                "job_fk" => $new_job_fk,
                                "test_fk" => $t_key["test_fk"],
                                "approve_by" => $t_key["approve_by"],
                                "status" => $t_key["status"],
                                "created_date" => $t_key["created_date"],
                                "deleted_by" => $t_key["deleted_by"]
                            );
                            $this->master_model->master_fun_insert("approve_job_test", $approve_job_add);
                        }
                    }
                    if (!empty($job_creditors)) {
                        $job_creditors_add = array(
                            "job_fk" => $new_job_fk,
                            "creditors_fk" => $job_creditors[0]["creditors_fk"],
                            "created_date" => $job_creditors[0]["created_date"],
                            "added_by" => $job_creditors[0]["added_by"],
                            "updated_by" => $job_creditors[0]["updated_by"],
                            "deleted_by" => $job_creditors[0]["deleted_by"],
                            "status" => $job_creditors[0]["status"],
                            "amount" => $job_creditors[0]["amount"],
                            "otp" => $job_creditors[0]["otp"]
                        );
                        $job_creditors_fk = $this->master_model->master_fun_insert("creditors_master", $job_creditors_add);
                    }
                    if (!empty($deleted_test_package)) {
                        foreach ($deleted_test_package as $t_key) {
                            $deleted_test_package_add = array(
                                "job_fk" => $new_job_fk,
                                "test_package" => $t_key["test_package"],
                                "createddate" => $t_key["createddate"],
                                "status" => $t_key["status"]
                            );
                            $this->master_model->master_fun_insert("job_deleted_test_package", $deleted_test_package_add);
                        }
                    }
                    if (!empty($phlebo_assign_job)) {
                        foreach ($phlebo_assign_job as $t_key) {
                            $phlebo_assign_job_add = array(
                                "job_fk" => $new_job_fk,
                                "time_fk" => $t_key["time_fk"],
                                "phlebo_fk" => $t_key["phlebo_fk"],
                                "date" => $t_key["date"],
                                "time" => $t_key["time"],
                                "address" => $t_key["address"],
                                "notify_cust" => $t_key["notify_cust"],
                                "status" => $t_key["status"],
                                "created_date" => $t_key["created_date"],
                                "created_by" => $t_key["created_by"],
                                "updated_by" => $t_key["updated_by"],
                                "is_accept" => $t_key["is_accept"],
                                "note" => $t_key["note"]
                            );
                            $this->master_model->master_fun_insert("phlebo_assign_job", $phlebo_assign_job_add);
                        }
                    }
                    if (!empty($payment)) {
                        foreach ($payment as $t_key) {
                            $payment_add = array(
                                "job_fk" => $new_job_fk,
                                "payomonyid" => $t_key["payomonyid"],
                                "amount" => $t_key["amount"],
                                "paydate" => $t_key["paydate"],
                                "status" => $t_key["status"],
                                "uid" => $new_cusomer_fk,
                                "type" => $t_key["type"],
                                "package_fk" => $t_key["package_fk"]
                            );
                            $this->master_model->master_fun_insert("payment", $payment_add);
                        }
                    }
                    if (!empty($job_log)) {
                        foreach ($job_log as $t_key) {
                            $job_log_add = array(
                                "job_fk" => $new_job_fk,
                                "created_by" => $t_key["created_by"],
                                "updated_by" => $t_key["updated_by"],
                                "deleted_by" => $t_key["deleted_by"],
                                "message_fk" => $t_key["message_fk"],
                                "job_status" => $t_key["job_status"],
                                "date_time" => $t_key["date_time"],
                                "status" => $t_key["status"]
                            );
                            $this->master_model->master_fun_insert("job_log", $job_log_add);
                        }
                    }
                    echo "DoN";
                } else {
                    echo "Not DON";
                }
            }
            /* END */
        }
    }

    function getLocalReport() {
        $data = $this->input->get_post("data");
        //echo "<pre>"; print_r($data); die("OK");
        $final_array = array();
        foreach ($data as $key) {
            $job_fk = $report_data = $this->master_model->get_val("");
            $report_data = $this->master_model->master_fun_get_tbl_val("report_master", array("job_fk" => $key), array("id", "desc"));
            if (!empty($report_data)) {
                $final_array[] = $report_data[0];
            }
        }
        print_r($final_array);
    }

}

?>