<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Excotel extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->app_track();
    }

    function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }

    function set_lang($lang) {
        echo $lang;
        //$lang = $this->input->post('value');
        $this->session->set_userdata('lang_session', $lang);
        $this->session->userdata('lang_session');
    }

    function index() {
        //	echo "here"; die();
        $data["login_data"] = array();
        $data["login_user"] = array();
        if ($this->session->userdata("logged_user")) {
            $data["login_data"] = $this->session->userdata("logged_user");
            $data["login_user"] = $this->session->userdata("logged_user");
        }
        $data["dynamic"] = $this->user_model->headfoot();
        $data["home"] = $this->user_model->home_data();
        $data["slider"] = $this->user_model->slider_data();
//echo "<pre>"; print_r($data['dynamic']); die();
        $data["ongoing"] = $this->user_model->getonprojectdata();

        $lan = $this->session->userdata('lang_session');

        if ($lan == "") {

            $lan = "en";
        }
        if ($lan == "en") {
            $this->load->view('user/header', $data);
            $this->load->view('user/home', $data);
            $this->load->view('user/footer', $data);
        } else if ($lan == "ar") {

            $this->load->view('user_ar/header', $data);
            $this->load->view('user_ar/home', $data);
            $this->load->view('user_ar/footer', $data);
        }
    }

    function on_going() {

        $data["data"] = $this->user_model->get_project("1");
        $master = array();
        foreach ($data["data"] as $key) {
            $bannerdata = $this->user_model->get_banner($key["id"]);
            array_push($master, array($key, $bannerdata));
        }
        $this->db->close();
        print_R($master);
        die();
        $this->load->view('user/header');
        $this->load->view('user/on_going', $data);
        $this->load->view('user/footer');
    }

    function completed() {

        $data["dynamic"] = $this->user_model->headfoot();
        $this->load->view('user/header', $data);
        $this->load->view('user/home');
        $this->load->view('user/footer', $data);
    }

    function about_us() {
        $data["login_data"] = array();
        $data["login_user"] = array();
        if ($this->session->userdata("logged_user")) {
            $data["login_data"] = $this->session->userdata("logged_user");
            $data["login_user"] = $this->session->userdata("logged_user");
        }
        $data["dynamic"] = $this->user_model->headfoot();
        $data['about'] = $this->user_model->getaboutdata();

        $lan = $this->session->userdata('lang_session');

        if ($lan == "") {

            $lan = "en";
        }

        if ($lan == "en") {
            $this->load->view('user/header', $data);
            $this->load->view('user/about_us', $data);
            $this->load->view('user/footer', $data);
        } else if ($lan == "ar") {

            $this->load->view('user_ar/header', $data);
            $this->load->view('user_ar/about_us', $data);
            $this->load->view('user_ar/footer', $data);
        }
    }

    function our_team() {
        $data["login_data"] = array();
        $data["login_user"] = array();
        if ($this->session->userdata("logged_user")) {
            $data["login_data"] = $this->session->userdata("logged_user");
            $data["login_user"] = $this->session->userdata("logged_user");
        }
        $data["dynamic"] = $this->user_model->headfoot();
        $data['team'] = $this->user_model->getteamdata();

        $lan = $this->session->userdata('lang_session');

        if ($lan == "") {

            $lan = "en";
        }

        if ($lan == "en") {
            $this->load->view('user/header', $data);
            $this->load->view('user/our_team', $data);
            $this->load->view('user/footer', $data);
        } else if ($lan == "ar") {

            $this->load->view('user_ar/header', $data);
            $this->load->view('user_ar/our_team', $data);
            $this->load->view('user_ar/footer', $data);
        }
    }

    function ongoing_project() {
        $data["login_data"] = array();
        $data["login_user"] = array();
        if ($this->session->userdata("logged_user")) {
            $data["login_data"] = $this->session->userdata("logged_user");
            $data["login_user"] = $this->session->userdata("logged_user");
        }
        $data["dynamic"] = $this->user_model->headfoot();
        $data["ongoing"] = $this->user_model->getonprojectdata();

        $lan = $this->session->userdata('lang_session');

        if ($lan == "") {

            $lan = "en";
        }

        if ($lan == "en") {

            $this->load->view('user/header', $data);
            $this->load->view('user/ongoing_project', $data);
            $this->load->view('user/footer', $data);
        } else if ($lan == "ar") {

            $this->load->view('user_ar/header', $data);
            $this->load->view('user_ar/ongoing_project', $data);
            $this->load->view('user_ar/footer', $data);
        }
    }

    function completed_project() {
        $data["login_data"] = array();
        $data["login_user"] = array();
        if ($this->session->userdata("logged_user")) {
            $data["login_data"] = $this->session->userdata("logged_user");
            $data["login_user"] = $this->session->userdata("logged_user");
        }
        $data["dynamic"] = $this->user_model->headfoot();
        $data["completed"] = $this->user_model->getcomprojectdata();

        $lan = $this->session->userdata('lang_session');
        if ($lan == "") {

            $lan = "en";
        }

        if ($lan == "en") {
            $this->load->view('user/header', $data);
            $this->load->view('user/completed_project', $data);
            $this->load->view('user/footer', $data);
        } else if ($lan == "ar") {

            $this->load->view('user_ar/header', $data);
            $this->load->view('user_ar/completed_project', $data);
            $this->load->view('user_ar/footer', $data);
        }
    }

    function read_more() {
        $data["login_data"] = array();
        $data["login_user"] = array();
        if ($this->session->userdata("logged_user")) {
            $data["login_data"] = $this->session->userdata("logged_user");
            $data["login_user"] = $this->session->userdata("logged_user");
        }
        $data["dynamic"] = $this->user_model->headfoot();
        $id = $data["id"] = $this->uri->segment('3');
        $data["banner"] = $this->user_model->getbannerdata($id);
        $data["detail"] = $this->user_model->getdetaildata($id);
        $data["ongoing"] = $this->user_model->getonprojectdata();
        $data["completed"] = $this->user_model->getcomprojectdata();

        $lan = $this->session->userdata('lang_session');
        if ($lan == "") {

            $lan = "en";
        }

        if ($lan == "en") {

            $this->load->view('user/header', $data);
            $this->load->view('user/detail_project', $data);
            $this->load->view('user/footer', $data);
        } else if ($lan == "ar") {

            $this->load->view('user_ar/header', $data);
            $this->load->view('user_ar/detail_project', $data);
            $this->load->view('user_ar/footer', $data);
        }
    }

    function contact_us() {
        $data["login_data"] = array();
        $data["login_user"] = array();
        if ($this->session->userdata("logged_user")) {
            $data["login_data"] = $this->session->userdata("logged_user");
            $data["login_user"] = $this->session->userdata("logged_user");
        }
        if ($this->session->userdata('success') != null) {
            $data['success'] = $this->session->userdata("success");
            $this->session->unset_userdata('success');
        }
        $lan = $this->session->userdata('lang_session');
        $data["dynamic"] = $this->user_model->headfoot();
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('subject', 'Subject', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('comment', 'Comment', 'trim|required');
        $this->form_validation->set_rules('number', 'Contact Number', 'trim|required|min_length[10]|max_length[12]|numeric');

        if ($this->form_validation->run() == FALSE) {
            if ($lan == "") {

                $lan = "en";
            }

            if ($lan == "en") {

                $this->load->view('user/header', $data);
                $this->load->view('user/contact_us', $data);
                $this->load->view('user/footer', $data);
            } else if ($lan == "ar") {

                $this->load->view('user_ar/header', $data);
                $this->load->view('user_ar/contact_us', $data);
                $this->load->view('user_ar/footer', $data);
            }
        } else {
            $name = $this->input->post("name");
            $email = $this->input->post("email");
            $number = $this->input->post("number");
            $comment = $this->input->post("comment");
            $subject = $this->input->post("subject");

            $data = array(
                "name" => $name,
                "email" => $email,
                "contact_number" => $number,
                "comment" => $comment,
                "subject" => $subject,
                "status" => '1'
            );
            $insert = $this->user_model->contact_master("bmh_contact_us_master", $data);
            if ($insert) {

                $data['name'] = $name;
                $data['email'] = $email;
                $data['number'] = $number;
                $data['detail'] = $comment;
                $data['subject'] = $subject;
                $message = "Thank you For contact us!";
                $body = $this->load->view('user/contact_email.php', $data, true);
                //  send_mail('pinkesh@virtualheight.com, $email', 'Powers for Investants Contact Us', $body);
                // send_mail($email, 'Powers for Investants Contact Us', $message);
                $config['mailtype'] = 'html';
                $this->email->initialize($config);
                $this->email->to('info@powersforinvestments.com');
                $this->email->from('support@virtualheight.com', 'Powers for Investants');
                $this->email->subject('Powers for Investants Contact Us');
                $this->email->message($body);
                $this->email->send();
                $this->session->set_userdata('success', array("Thank you for contact us!"));
                redirect("user/contact_us");
            }
        }
    }

    function organization() {
        $data["login_data"] = array();
        $data["login_user"] = array();
        if ($this->session->userdata("logged_user")) {
            $data["login_data"] = $this->session->userdata("logged_user");
            $data["login_user"] = $this->session->userdata("logged_user");
        }
        $data["dynamic"] = $this->user_model->headfoot();
        $lan = $this->session->userdata('lang_session');

        if ($lan == "") {

            $lan = "en";
        }

        if ($lan == "en") {

            $this->load->view('user/header', $data);
            $this->load->view('user/organization');
            $this->load->view('user/footer', $data);
        } else if ($lan == "ar") {

            $this->load->view('user_ar/header', $data);
            $this->load->view('user_ar/organization');
            $this->load->view('user_ar/footer', $data);
        }
    }

    function view_more() {
        $data["login_data"] = array();
        $data["login_user"] = array();
        if ($this->session->userdata("logged_user")) {
            $data["login_data"] = $this->session->userdata("logged_user");
            $data["login_user"] = $this->session->userdata("logged_user");
        }
        $id = $this->uri->segment(3);
        $data["dynamic"] = $this->user_model->headfoot();
        $data["data"] = $this->user_model->getprojectdata($id);
        $data["completed"] = $this->user_model->get_project("1");
        $data["on_going"] = $this->user_model->get_project("0");
        $data["banner"] = $this->user_model->get_banner($id);



        $this->load->view('user/header', $data);
        $this->load->view('user/project_view_more', $data);
        $this->load->view('user/footer', $data);
    }

    function estimate() {
        $data["login_data"] = array();
        $data["login_user"] = array();
        if ($this->session->userdata("logged_user")) {
            $data["login_data"] = $this->session->userdata("logged_user");
            $data["login_user"] = $this->session->userdata("logged_user");
        }

        $data["dynamic"] = $this->user_model->headfoot();
        $data["types_of_hous"] = $this->user_model->get_master_get_data("bmh_house_cat", array("status" => "1"), array("name", "desc"));
        $data["region"] = $this->user_model->get_master_get_data("bmh_region_cat", array("status" => "1"), array("name", "asc"));
        $data["land_size"] = $this->user_model->get_master_get_data("bmh_land_size", array("status" => "1"), array("value", "asc"));
        $data["land_size_cat"] = $this->user_model->get_master_get_data("bmh_structural_works_cat", array("status" => "1"), array("name", "asc"));
        $data["land_size_subcat"] = $this->user_model->get_master_get_data("bmh_structural_works_subcat", array("status" => "1"), array("order", "asc"));


        $data["finishing_cat"] = $this->user_model->get_master_get_data("bmh_finishing_work_cat", array("status" => "1"), array("name", "asc"));
        $data["finishing_subcat"] = $this->user_model->get_master_get_data("bmh_finishing_work_subcat", array("status" => "1"), array("order", "asc"));
        $data["partially_completed"] = $this->user_model->get_master_get_data("bmh_partially_completed", array("status" => "1"), array("complete_persentage", "asc"));
        $data["quality"] = $this->user_model->get_master_get_data("bmh_quality", array("status" => "1"), array("name", "desc"));
        $data["extirior"] = $this->user_model->get_master_get_data("bmh_exterior", array("status" => "1"), array("name", "asc"));

        $this->load->view('user/header', $data);
        $this->load->view('user/estimate', $data);
        $this->load->view('user/footer', $data);
    }

    function get_house_subcat() {
        $id = $this->uri->segment(3);
        $types_of_hous = $this->user_model->get_master_get_data("bmh_house_subcat", array("status" => "1", "cat_fk" => $id), array("name", "asc"));
        echo "<select name='house_sub_cat'  id='house_sub_cat'>";
        $cnt = 0;
        foreach ($types_of_hous as $key) {
            if ($cnt == 0) {
                echo "<option value=''>--Select--</option>";
            }
            echo '<option value="' . $key['id'] . '">' . ucfirst($key['name']) . '</option>';
            $cnt++;
        }
        if (empty($types_of_hous)) {
            echo '<option value="">--Not available--</option>';
        }
        echo "</select>";
    }

    function get_town_cat() {
        $id = $this->uri->segment(3);
        $types_of_hous = $this->user_model->get_master_get_data("bmh_region_subcat", array("status" => "1", "cat_fk" => $id), array("name", "asc"));
        echo "<label for='region'>Town</label><select name='town' id='town1' onchange='get_town_subcat(this.value);'>";
        $cnt = 0;
        foreach ($types_of_hous as $key) {
            if ($cnt == 0) {
                echo "<option value=''>--Select--</option>";
            }
            echo '<option value="' . $key['id'] . '">' . ucfirst($key['name']) . '</option>';
            $cnt++;
        }
        if (empty($types_of_hous)) {
            echo '<option value="">--Not available--</option>';
        }
        echo "</select>";
    }

    function get_town_subcat() {
        $id = $this->uri->segment(3);
        $types_of_hous = $this->user_model->get_master_get_data("bmh_region_sub_subcat", array("status" => "1", "sub_cat_fk" => $id), array("name", "asc"));
        echo "<label for='region'>Neighbourhood</label><select name='nabour' id='Neighbourhood1'>";
        $cnt = 0;
        foreach ($types_of_hous as $key) {
            if ($cnt == 0) {
                echo "<option value=''>--Select--</option>";
            }
            echo '<option value="' . $key['id'] . '">' . ucfirst($key['name']) . '</option>';
            $cnt++;
        }
        if (empty($types_of_hous)) {
            echo '<option value="">--Not available--</option>';
        }
        echo "</select>";
    }

    function calculation() {
        $data["login_data"] = array();
        $data["login_user"] = array();
        if ($this->session->userdata("logged_user")) {
            $data["login_data"] = $this->session->userdata("logged_user");
            $data["login_user"] = $this->session->userdata("logged_user");
        }
        $data["dynamic"] = $this->user_model->headfoot();
        $post = $this->input->post();
        //print_R($post);
        //die();
        $types_of_hous = $this->input->post('types_of_hous');
        $data['types_of_hous1'] = $this->user_model->get_master_get_data("bmh_house_cat", array("id" => $types_of_hous[0]), array("name", "asc"));
        $house_sub_cat_post = $this->input->post('house_sub_cat');
        $data["house_sub_cat_old"] = $this->user_model->get_master_get_data("bmh_house_subcat", array("id" => $house_sub_cat_post), array("name", "asc"));
        $quality = $this->input->post('quality');
        $data["quality"] = $this->user_model->get_master_get_data("bmh_quality", array("id" => $quality), array("name", "asc"));
        $house_sub_cat = $data["house_sub_cat_old"][0]["value"];
        if ($types_of_hous) {
            $region = $this->input->post('region');
            $data["region"] = $this->user_model->get_master_get_data("bmh_region_cat", array("id" => $region), array("name", "asc"));
            $town = $this->input->post('town');
            $data["town"] = $this->user_model->get_master_get_data("bmh_region_subcat", array("id" => $town), array("name", "asc"));
            $nabour = $this->input->post('nabour');
            $data["nabour"] = $this->user_model->get_master_get_data("bmh_region_sub_subcat", array("id" => $nabour), array("name", "asc"));
            if ($nabour != '') {
                $data["land_price"] = $this->user_model->get_master_get_data("bmh_region_sub_subcat", array("id" => $nabour), array("name", "asc"));
            } else {
                $data["land_price"] = $this->user_model->get_master_get_data("bmh_region_subcat", array("id" => $town), array("name", "asc"));
            }
            if ($types_of_hous[0] != 3) {
                $land_size_post = $this->input->post('land_size');
                $data["land_size_new"] = $this->user_model->get_master_get_data("bmh_land_size", array("id" => $land_size_post), array("id", "asc"));
                $land_size = $data["land_size_new"][0]["area"];
            } else {
                $land_size = $house_sub_cat;
            }
            $structrual_work = $this->input->post('structrual_work');
            $finishing_work = $this->input->post('finishing_work');
            //  if (empty($this->session->userdata("logged_user"))) {
            //      $username = $this->input->post('username');
            //      $phonenumber = $this->input->post('phonenumber');
            //      $l_name = $this->input->post('l_name');
            //  }
            $extirior_work = $this->input->post('extirior_work');
            $partially_completed_post = $this->input->post('partially_completed');
            $partially_completed_new = $this->user_model->get_master_get_data("bmh_partially_completed", array("id" => $partially_completed_post[0]), array("id", "asc"));
            $partially_completed = $partially_completed_new[0]["value"];
            $stru = $this->input->post('stru');
            $data["stru"] = $stru;
            if ($types_of_hous[0] != 3) {
                $land_price = $this->land_price($land_size, $data["land_price"][0]["price"]);
            } else {
                $land_price = 0;
            }
            if ($types_of_hous[0] != 3) {
                $total_buildup_area = $this->total_buildup_area($land_size, $house_sub_cat);
            } else {
                $total_buildup_area = $house_sub_cat;
            }
            if ($stru == '0') {
                $structural_work = $this->structural_work('600', $total_buildup_area);
            } else {
                $structural_work = 0;
            }
            $data1 = array();
            if ($stru == '2') {
                /* foreach ($finishing_work as $dt) {
                  $data1[] = array('1400', //price
                  $total_buildup_area, //total
                  "20", //margin in %
                  );
                  } */
                $data1 = array(array('1400', //price
                        $total_buildup_area, //total
                        "20", //margin in %
                ));
                $finishing_work = $this->finishing_work($data1);
            } else {
                $finishing_work = 0;
            }

            $data2 = array();
            if ($stru == '1') {
                foreach ($partially_completed as $dt) {
                    $data2[] = array($dt, //price
                        $total_buildup_area, //total
                        "20", //margin in %
                    );
                }
                $partially = $this->finishing_work($data2);
            } else {
                $partially = 0;
            }

            if ($types_of_hous[0] != 3) {
                $data1 = array();

                foreach ($extirior_work as $dt) {
                    $data1[] = array($dt, //price
                        $total_buildup_area, //total
                        "20", //margin in %
                    );
                }

                $extirior_work = $this->extirior_work($data1);
            } else {
                $extirior_work = 0;
            }
            $data["price"] = $land_price + $structural_work + $extirior_work + $finishing_work + $partially;
            $data["max_price"] = ($data["price"] * 10 / 100) + $data["price"];
            $data["min_price"] = $data["price"] - ($data["price"] * 10 / 100);
            if (!$this->session->userdata("logged_user")) {
                $insert = $this->user_model->master_fun_insert("bmh_registration", array("first_name" => $username, "last_name" => $l_name, "phone" => $phonenumber, "password" => $this->input->post('otp_code'), "status" => "1", "createddate" => date("Y-m-d h:i:s")));
                $sess_array = array(
                    'id' => $insert,
                    'name' => $username,
                    'phone' => $phonenumber
                );
                $this->session->set_userdata('logged_user', $sess_array);
                if ($this->session->userdata("logged_user")) {
                    $data["login_data"] = $this->session->userdata("logged_user");
                    $data["login_user"] = $this->session->userdata("logged_user");
                }
            } else {
                $insert = $data["login_data"]["id"];
            }

            $last_id = $this->user_model->master_fun_insert("bmh_user_quotation", array("user_fk" => $insert, "price" => $data["price"], "data" => json_encode($post), "status" => "1", "createddate" => date("Y-m-d h:i:s")));
            $data['id'] = $last_id;

            $to = "info@powersforinvestments.com";
            $subject = "Build my house";
            $txt = "<b>Name</b>: " . ucfirst($username) . "<br><b>Phone number</b>: " . $phonenumber;
            $headers = "From: info@powersforinvestments.com" . "\r\n";

            //mail($to, $subject, $txt, $headers);

            $this->load->view('user/header', $data);
            $this->load->view('user/calculation', $data);
            $this->load->view('user/footer', $data);
        } else {
            redirect('user/estimate');
        }
    }

    function land_price($land_size, $price) {
        /* $land = 1;
          $land_size = 300;
          $price = 2000; */
        $margin = 20; //in %
        $cost = $land_size * $price;
        $margin_total = $cost * 20 / 100;
        $sub_total = $cost + $margin_total;
        return $sub_total;
    }

    function total_buildup_area($land_size, $number_of_floor) {
        /* $land_size = 300;
          $number_of_floor = 2; */
        $criteria = 60; //in %
        $additional_build = 10; //in %
        $buid_up_area = ($land_size * $criteria / 100) * $number_of_floor;
        $total_build_area = ($buid_up_area * $additional_build / 100) + $buid_up_area;
        return $total_build_area;
    }

    function structural_work($per_meter_price, $total_build_area) {
        /* $per_meter_price = 700;
          $total_build_area = 396; */
        $cost = $per_meter_price * $total_build_area;
        $margin = 20; //in %
        return $buid_up_area = ($cost * $margin / 100) + $cost;
    }

    function finishing_work($data) {
        $total = 0;
        $sub_cost_total = 0;
        $margin_sub_total = 0;
        foreach ($data as $key) {
            $price = $key[0] * $key[1];
            $sub_cost_total += $price;
            $margin_sub_total += $price * $key[2] / 100;
            $total += ($price * $key[2] / 100) + $price;
        }
        //echo $sub_cost_total."<br>".$margin_sub_total."<br>".$total;
        return $total;
    }

    function extirior_work($data) {
        $total = 0;
        $sub_cost_total = 0;
        $margin_sub_total = 0;
        foreach ($data as $key) {
            $price = $key[0] * $key[1];
            $sub_cost_total += $price;
            $margin_sub_total += $price * $key[2] / 100;
            $total += ($price * $key[2] / 100) + $price;
        }
        //echo $sub_cost_total."<br>".$margin_sub_total."<br>".$total;
        return $total;
    }

    function construction_progress() {
        $data["login_data"] = array();
        $data["login_user"] = array();
        if ($this->session->userdata("logged_user")) {
            $data["login_data"] = $this->session->userdata("logged_user");
            $data["login_user"] = $this->session->userdata("logged_user");
        }
        $data["dynamic"] = $this->user_model->headfoot();
        $data["completed"] = $this->user_model->get_project("1");
        $data["ongoing"] = $this->user_model->get_project("0");

        $lan = $this->session->userdata('lang_session');
        if ($lan == "") {

            $lan = "en";
        }
        if ($lan == "en") {
            $data["dynamic"] = $this->user_model->headfoot();
            $this->load->view('user/header', $data);
            $this->load->view('user/construction_progress', $data);
            $this->load->view('user/footer', $data);
        } else if ($lan == "ar") {
            $this->load->view('user_ar/header', $data);
            $this->load->view('user_ar/construction_progress', $data);
            $this->load->view('user_ar/footer', $data);
        }
    }

    function all_project() {
        $data["login_data"] = array();
        $data["login_user"] = array();
        if ($this->session->userdata("logged_user")) {
            $data["login_data"] = $this->session->userdata("logged_user");
            $data["login_user"] = $this->session->userdata("logged_user");
        }
        $data["dynamic"] = $this->user_model->headfoot();
        $data["completed"] = $this->user_model->get_project("1");
        $data["ongoing"] = $this->user_model->get_project("0");

        $lan = $this->session->userdata('lang_session');
        if ($lan == "") {

            $lan = "en";
        }

        if ($lan == "en") {

            $this->load->view('user/header', $data);
            $this->load->view('user/all_project', $data);
            $this->load->view('user/footer', $data);
        } else if ($lan == "ar") {

            $this->load->view('user_ar/header', $data);
            $this->load->view('user_ar/all_projects', $data);
            $this->load->view('user_ar/footer', $data);
        }
    }

    function login() {
        $this->session->set_userdata("logged_user", array("id" => "1"));
    }

    function logout() {
        $this->session->unset_userdata("logged_user");
    }

    function check_phone() {
        $phone = $this->input->post("phone");
        $check_phone = $this->user_model->get_master_get_data("bmh_registration", array("phone" => $phone, "status" => "1"), array("name", "asc"));
        if (empty($check_phone)) {
            echo "0";
        } else {
            $check_quote = $this->user_model->get_master_get_data("bmh_user_quotation", array("user_fk" => $check_phone[0]["id"], "status" => "1"), array("price", "asc"));
            $quote_count = count($check_quote);
            if ($quote_count >= 3) {
                echo '2';
            } else {
                echo "1";
            }
        }
    }

    function quotation_login() {
        $phone = $this->input->post("phonenumber");
        $password = $this->input->post("password");
        $check_phone = $this->user_model->get_master_get_data("bmh_registration", array("phone" => $phone, "password" => $password, "status" => "1"), array("name", "asc"));
        if (empty($check_phone)) {
            echo "0";
        } else {
            $sess_array = array(
                'id' => $check_phone[0]["id"],
                'name' => $check_phone[0]["name"],
                'phone' => $check_phone[0]["phone"]
            );
            $this->session->set_userdata('logged_user', $sess_array);
            echo "1";
        }
    }

    function message() {
        if (!is_userloggedin()) {
            redirect('user_master');
        }
        $data["login_user"] = loginuser();
        $data["login_data"] = array();
        if ($this->session->userdata("logged_user")) {
            $data["login_data"] = $this->session->userdata("logged_user");

            if ($this->session->userdata('success') != null) {
                $data['success'] = $this->session->userdata("success");
                $this->session->unset_userdata('success');
            }
            $data["dynamic"] = $this->user_model->headfoot();
            $this->form_validation->set_rules('message', 'Message', 'trim|required');
            if ($this->form_validation->run() == FALSE) {

                $data["dynamic"] = $this->user_model->headfoot();
                $data["completed"] = $this->user_model->get_project("1");
                $data["ongoing"] = $this->user_model->get_project("0");
                $this->load->view('user/header', $data);
                $this->load->view('user/message', $data);
                $this->load->view('user/footer', $data);
            } else {
                $message = $this->input->post("message");

                $data = array(
                    "user_fk" => $data["login_data"]["id"],
                    "message" => $message,
                    "status" => '1'
                );
                $insert1 = $this->user_model->master_fun_insert("bmh_user_msg", $data);
                if ($insert1) {
                    // $this->session->set_userdata('success', array("Thank you for inquiry, we'll contact you in short time.!"));
                    redirect("user/thankyou");
                }
            }
        } else {
            redirect("user");
        }
    }

    function thankyou() {
        if (!is_userloggedin()) {
            redirect('user_master');
        }
        $data["login_user"] = loginuser();
        $data["dynamic"] = $this->user_model->headfoot();
        $this->load->view('user/header', $data);
        $this->load->view('user/thank_you');
        $this->load->view('user/footer', $data);
    }

    function test() {
        if (!is_userloggedin()) {
            //  redirect('user_master');
        }
        $data["login_user"] = loginuser();
        $data["dynamic"] = $this->user_model->headfoot();
        $this->load->view('user/header', $data);
        $this->load->view('user/calculation', $data);
        $this->load->view('user/footer', $data);
    }

    function pdf_test() {
        if (!is_userloggedin()) {
            //  redirect('user_master');
        }
        $data["login_user"] = loginuser();
        $data["dynamic"] = $this->user_model->headfoot();
        //$this->load->view('user/header', $data);
        $this->load->view('user/calculation_pdf', $data);
        //$this->load->view('user/footer', $data);
    }

    function pdf() {
        //    if ($this->session->userdata('logged_user')) {
        //pdf start
        // As PDF creation takes a bit of memory, we're saving the created file in /downloads/reports/
        // reference link https://davidsimpson.me/2013/05/19/using-mpdf-with-codeigniter/    
        $file_name = "test.pdf";
        $pdfFilePath = FCPATH . "upload/$file_name";
        //$data['page_title'] = 'Hello world'; // pass data to the view

        if (file_exists($pdfFilePath) == FALSE) {
//                $param = '"en-GB-x","A4","","",10,10,10,10,6,3'; // Defaults to portrait
            //$param = '"en-GB-x","A4-L","","",10,10,10,10,6,3,"P"'; // Portrait
            $param = '"en-GB-x","A4","","",10,10,10,10,6,3,"P"'; // Landscape
            //               $param = array("en-GB-x","A4-L","","",10,10,10,10,6,3);

            ini_set('memory_limit', '64M'); // boost the memory limit if it's low <img src="https://davidsimpson.me/wp-includes/images/smilies/icon_wink.gif" alt=";)" class="wp-smiley">
            //$lorem = utf8_encode($this->load->view('pdf_report', $data, true)); // render the view into HTML
            $lorem = utf8_encode("test"); // render the view into HTML
            //     die();
            $this->load->library('pdf');
            $pdf = $this->pdf->load($param);
            $pdf->AddPage('L', // L - landscape, P - portrait
                    '', '', '', '', 15, // margin_left
                    10, // margin right
                    15, // margin top
                    15, // margin bottom
                    6, // margin header
                    3);

            $pdf->SetDisplayMode('fullpage');


            $pdf->SetFooter('|<a href="http://www.apiltd.nz/" style="color:#E36C0A;font-size:15px;">www.apiltd.nz</a>|' . '{PAGENO}'); // Add a footer for good measure <img src="https://davidsimpson.me/wp-includes/images/smilies/icon_wink.gif" alt=";)" class="wp-smiley">
            $pdf->h2toc = array('H2' => 0);

            // Split $lorem into words
            $words = preg_split('/([\s,\.]+)/', $lorem, -1, PREG_SPLIT_DELIM_CAPTURE);

            $pdf->WriteHTML($lorem);

            //nishit index end
            $pdf->debug = true;
            $pdf->Output($pdfFilePath, 'D'); // save to file because we can
            //window.open($pdfFilePath, '_blank'); 
        }
        $downld = $this->_push_file($pdfFilePath, $file_name);

        // $this->delete_downloadfile($pdfFilePath);
        //pdf end
        //} else {
        //    redirect('login', 'refresh');
        //  }
    }

    function _push_file($path, $name) {
        // make sure it's a file before doing anything!
        if (is_file($path)) {
            // required for IE
            if (ini_get('zlib.output_compression')) {
                ini_set('zlib.output_compression', 'Off');
            }
            // get the file mime type using the file extension
            $this->load->helper('file');

            $mime = get_mime_by_extension($path);

            // Build the headers to push out the file properly.
            header('Pragma: public');     // required
            header('Expires: 0');         // no cache
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($path)) . ' GMT');
            header('Cache-Control: private', false);
            header('Content-Type: ' . $mime);  // Add the mime type from Code igniter.
            header('Content-Disposition: attachment; filename="' . basename($name) . '"');  // Add the file name
            header('Content-Transfer-Encoding: binary');
            header('Content-Length: ' . filesize($path)); // provide file size
            header('Connection: close');
            readfile($path); // push it out
//    exit();
        }
    }

    function pdf_report($id) {
        $this->load->model('user_quotation_model');
        $data['query'] = $this->user_model->pdf_details($id);
        $value = json_decode($data['query']['0']['data']);
        $types_of_hous = $value->types_of_hous;
        $data['types_of_hous1'] = $this->user_model->get_master_get_data("bmh_house_cat", array("id" => $types_of_hous[0]), array("name", "asc"));
        $house_sub_cat_post = $value->house_sub_cat;
        $data["house_sub_cat_old"] = $this->user_model->get_master_get_data("bmh_house_subcat", array("id" => $house_sub_cat_post), array("name", "asc"));
        $quality = $value->quality;
        $data["quality"] = $this->user_model->get_master_get_data("bmh_quality", array("id" => $quality), array("name", "asc"));
        $house_sub_cat = $data["house_sub_cat_old"][0]["value"];
        if ($types_of_hous) {
            $region = $value->region;
            $data["region"] = $this->user_model->get_master_get_data("bmh_region_cat", array("id" => $region), array("name", "asc"));
            $town = $value->town;
            $data["town"] = $this->user_model->get_master_get_data("bmh_region_subcat", array("id" => $town), array("name", "asc"));
            $nabour = $value->nabour;
            $data["nabour"] = $this->user_model->get_master_get_data("bmh_region_sub_subcat", array("id" => $nabour), array("name", "asc"));
            if ($nabour != '') {
                $data["land_price"] = $this->user_model->get_master_get_data("bmh_region_sub_subcat", array("id" => $nabour), array("name", "asc"));
            } else {
                $data["land_price"] = $this->user_model->get_master_get_data("bmh_region_subcat", array("id" => $town), array("name", "asc"));
            }
            if ($types_of_hous[0] != 3) {
                $land_size_post = $value->land_size;
                $data["land_size_new"] = $this->user_model->get_master_get_data("bmh_land_size", array("id" => $land_size_post), array("id", "asc"));
                $land_size = $data["land_size_new"][0]["area"];
            } else {
                $land_size = $house_sub_cat;
            }
            $partially_completed_post = $data['partially_completed_post'] = $value->partially_completed;
            $partially_completed_new = $this->user_model->get_master_get_data("bmh_partially_completed", array("id" => $partially_completed_post[0]), array("id", "asc"));
            $partially_completed = $partially_completed_new[0]["value"];
            $stru = $value->stru;
            $data["stru"] = $stru;
        }
        $data['structure'] = $this->user_quotation_model->structure();
        $data['partially'] = $this->user_quotation_model->partially();
        $data['finish'] = $this->user_quotation_model->finish();
        $data["price"] = $data['query']['0']['price'];
        $data["max_price"] = ($data["price"] * 10 / 100) + $data["price"];
        $data["min_price"] = $data["price"] - ($data["price"] * 10 / 100);




// As PDF creation takes a bit of memory, we're saving the created file in /downloads/reports/
        $pdfFilePath = FCPATH . "/download/powers_for_investments_quotation_report.pdf";
        $data['page_title'] = 'Powers for Investments Co.'; // pass data to the view


        ini_set('memory_limit', '32M'); // boost the memory limit if it's low <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="😉" draggable="false" class="emoji">
        $html = $this->load->view('user/calculation_pdf', $data, true); // render the view into HTML
        $param = '"en-GB-x","A4","0","0","P"'; // Landscape
        $lorem = utf8_encode($html); // render the view into HTML
        $this->load->library('pdf');
        $pdf = $this->pdf->load($param);
        $pdf->AddPage('P', // L - landscape, P - portrait
                '', '', '', '', 00, // margin_left
                0, // margin right
                0, // margin top
                0, // margin bottom
                0, // margin header
                0);

        $pdf->SetDisplayMode('fullpage');
        $pdf->h2toc = array('H2' => 0);
        //nishit index start
        $html = '';
        // Split $lorem into words
        $pdf->WriteHTML($lorem);
        //nishit index end
        $pdf->debug = true;

        $pdf->SetFooter($_SERVER['HTTP_HOST'] . '|{PAGENO}|' . date(DATE_RFC822)); // Add a footer for good measure <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="😉" draggable="false" class="emoji">
        // $pdf->WriteHTML($html); // write the HTML into the PDF
        $pdf->Output($pdfFilePath, 'F'); // save to file because we can

        redirect("/download/powers_for_investments_quotation_report.pdf");
    }

}

?>
