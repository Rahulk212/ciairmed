<style>
    .pdng_0 {padding: 0;}
    .round {
        display: inline-block;
        height: 30px;
        width: 30px;
        line-height: 30px;
        -moz-border-radius: 15px;
        border-radius: 15px;
        background-color: #222;    
        color: #FFF;
        text-align: center;  
    }
    .round.round-sm {
        height: 10px;
        width: 10px;
        line-height: 10px;
        -moz-border-radius: 10px;
        border-radius: 10px;
        font-size: 0.7em;
    }
    .round.blue {
        background-color: #3EA6CE;
    }
    .fillInfoClass{display:block !important; }
    .chosen-container .chosen-results li.active-result {width: 100% !important;}
    #order_table {height: 500px; overflow: scroll;}
</style>
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<link href="<?php echo base_url(); ?>plugins/timepick/css/timepicki.css" rel="stylesheet">
<style>
    .chosen-container {
        display: inline-block;
        font-size: 14px;
        position: relative;
        vertical-align: middle;
        width: 100%;
    }
    .full_bg{background: rgba(0,0,0,0.3); width:100%; height:100%; float:left; padding:350px; position:fixed; z-index:9; top:0; bottom:0;}
    .full_bg .loader img{width:70px; height:70px;}
</style>
<div class="full_bg" style="display:none;" id="loader_div">
    <div class="loader">
        <center><img src="http://static.heart.org/riskcalc/app/assets/img/loading.gif"></center>
    </div>
</div>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- Custom Tabs -->
            <div class="nav-tabs-custom">
                <?php if ($login_data['type'] != 5 && $login_data['type'] != 6 && $login_data['type'] != 7) { ?>
                    <ul class="nav nav-tabs">
    <!--                    <li><a href="javascript:void(0);" onclick="window.location = '<?= base_url(); ?>Admin/Telecaller'" data-toggle="tab">All Jobs(Tests) <span id="pending_count_1" class="label label-danger">0</span> </a></li>
                        <li><a href="javascript:void(0);" onclick="window.location = '<?= base_url(); ?>Admin/TelecallerPriscription'" data-toggle="tab">Prescription <span class="label label-danger"><?= count($unread); ?></span></a></li>-->
                        <li class="active"><a href="javascript:void(0);" data-toggle="tab">On Call Booking</a></li>
                        <li style="float: right;"><button class="btn btn-primary" onclick="save_user_data();"/>Save</button></li>
                    </ul>
                <?php } ?>
            </div>
            <?php if ($success[0] != '') { ?>
                <div class="alert alert-success alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">X</button>
                    <?php echo $success['0']; ?>
                </div>
            <?php } ?>
            <?php if (isset($error) != NULL) { ?>
                <div class="alert alert-danger alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">X</button>
                    <?php echo $error['0']; ?>
                </div>
            <?php } ?>
            <div class="callout callout-info" id="show_number_print" style="margin-bottom: 0!important;  display:none;">                                                
                <h4><i class='fa fa-phone'> </i>  Incoming Call :</h4>
                <p id="number_print"></p>
            </div>
            <div class="callout callout-danger" id="show_number_print1" style="margin-bottom: 0!important;  display:none;">                                                
                <h4><i class='fa fa-phone'> </i>  Incoming Call :</h4>
                <p id="number_print1"></p>
            </div>
        </div>
    </div>
    <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
            <section class="content">
                <div class="row">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="box box-primary">

                                <div class="box-header">
                                    <!-- form start -->
                                    <h3 class="box-title">User Details</h3>
                                </div>

                                <div class="box-body">
                                    <?php echo form_open_multipart("Admin/TelecallerCallBooking1", array("role" => "form", "method" => "POST", "id" => "user_form")); ?>

                                    <div id="hidden_test"></div>
                                    <div class="col-md-12">
                                        <div class="form-group col-sm-12  pdng_0">
                                            <label class="col-sm-3 pdng_0" for="exampleInputFile">Select Customer :</label>
                                            <div class="col-sm-9 pdng_0">
                                                <select id="existingCustomer12" name="customer" class="chosen" onchange="get_user_info(this.value);">

                                                </select> 
                                            </div>
                                        </div>
                                        <span><b>OR</b></span>
                                        <div class="form-group col-sm-12  pdng_0">
                                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Mobile :<span style="color:red;">*</span></label>
                                            <div class="col-sm-9 pdng_0">
                                                <input type="text" id="phone" name="phone" placeholder="Ex.9879879870" onkeyup="DelayedCallMe(200, this.value);" onblur="DelayedCallMe(200, this.value);" value="<?php echo ucwords($user_info[0]['mobile']); ?>" class="form-control"/>
                                                <span style="color:red;" id="phone_error"></span>
                                                <span style="color:red;" id="phone_error1"></span>
                                                <input type="hidden" id="phone_check" value="0"/>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-12  pdng_0">
                                            <label for="exampleInputFile" class="col-sm-3 pdng_0">New Customer Name :<span style="color:red;">*</span></label> 
                                            <div class="col-sm-9 pdng_0">
                                                <input type="text" id="name" name="name" value="<?php echo ucfirst($user_info[0]['full_name']); ?>" class="form-control"/>
                                                <span style="color:red;" id="name_error"></span>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-12  pdng_0">
                                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Test For :</label>
                                            <div class="col-sm-9 pdng_0">
                                                <select name="test_for" id="test_for" class="form-control" onchange="show_add_new_btn(this.value);" style="width:79%;float:left;margin-right:10px;">
                                                    <option value="">Self</option>
                                                    <option value="new">--Add New--</option>
                                                </select> 
                                                <input type="button" class="btn btn-lm" id="add_new_btn" style="display:none;" onclick="check_new_user('new');" value="Add New" >
                                            </div>
                                        </div>
                                        <?php /* Nishit change payment status start */ ?>
                                        <script>
                                            $email_varify=1;
                                            function show_add_new_btn(val) {
                                                if (val == 'new') {
                                                    $("#add_new_btn").attr("style", "");
                                                } else {
                                                    $("#add_new_btn").attr("style", "display:none;");
                                                }
                                            }
                                            function check_new_user(val) {
                                                if (val == 'new') {
                                                    $("#family_model").modal("show");
                                                }
                                            }
                                            function add_new_member() {
                                                var f_name = $("#f_name").val();
                                                var family_relation = $("#family_relation").val();
                                                var dob1 = $("#dob1").val();
                                                $("#f_name_error").html("");
                                                $("#f_relation_error").html("");
                                                var cnt = 0;
                                                if (f_name.trim() == '') {
                                                    cnt = 1;
                                                    $("#f_name_error").html("Required.");
                                                }
                                                if (dob1.trim() == '') {
                                                    cnt = 1;
                                                    $("#dob1_error").html("Required.");
                                                }
                                                if (family_relation.trim() == '') {
                                                    cnt = 1;
                                                    $("#f_relation_error").html("Required.");
                                                }
                                                if (cnt == 1) {
                                                    return false;
                                                }
                                                //$("#test_for").append("<option value='new' selected>" + f_name + "</option>");
                                                $("#family_model").modal("hide");
                                            }
                                        </script>
                                        <!-- Modal -->
                                        <div class="modal fade" id="family_model" role="dialog">
                                            <div class="modal-dialog">

                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title">Add Family Member</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="recipient-name" class="control-label">Name<i style="color:red;">*</i>:</label>
                                                            <input type="text" name="f_name" id="f_name" class="form-control"/>
                                                            <span style="color:red;" id="f_name_error"></span>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="recipient-name" class="control-label">Relation<i style="color:red;">*</i>:</label>
                                                            <select name="family_relation" id="family_relation" class="form-control">
                                                                <option value="">--Select--</option>
                                                                <?php foreach ($relation1 as $fkey) { ?>
                                                                    <option value="<?= $fkey["id"] ?>"><?= ucfirst($fkey["name"]); ?></option>
                                                                <?php } ?>
                                                            </select>
                                                            <span style="color:red;" id="f_relation_error"></span>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="recipient-name" class="control-label">Birth date:</label>
                                                            <input type="text" id="dob1" name="f_dob" placeholder='Birth date' class="datepicker form-control" value="" style="width:70%"/>OR<input type="text" class="form-control" style="width:20%" onkeyup="calculate_age1(this.value);"/>
                                                            <span style="color:red;" id="dob1_error"></span>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="recipient-name" class="control-label">Gender<i style="color:red;">*</i>:</label>
                                                            <select name="family_gender" class="form-control">
                                                                <option value="male">Male</option>
                                                                <option value="female">Female</option>
                                                            </select>
                                                        </div>
                                                        <script>
                                                            function calculate_age1(val)
                                                            {
                                                                var today_date = '<?= date("Y-m-d"); ?>';
                                                                var get_date_data = today_date.split("-");

                                                                var new_date = get_date_data[0] - val;
                                                                new_date = new_date + "-" + get_date_data[1] + "-" + get_date_data[2];
                                                                $("#dob1").val(new_date);
                                                            }
                                                        </script>
                                                        <div class="form-group">
                                                            <label for="recipient-name" class="control-label">Phone:</label>
                                                            <input type="text" name="f_phone" class="form-control"/>
                                                            <span style="color:red;" id="f_phone_error"></span>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="recipient-name" class="control-label">Email:</label>
                                                            <input type="text" name="f_email" class="form-control"/>
                                                            <span style="color:red;" id="f_email_error"></span>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-primary" id="family_add_btn" onclick="add_new_member();">Add</button>
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php /* Nishit change payment status end */ ?>
                                        <div class="modal fade" id="doctor_add" role="dialog">
                                            <div class="modal-dialog">

                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title">Add Doctor</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="recipient-name" class="control-label">Name<i style="color:red;">*</i>:</label>
                                                            <input type="text" name="d_name" id="d_name" class="form-control"/>
                                                            <span style="color:red;" id="d_name_error"></span>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="recipient-name" class="control-label">Mobile:</label>
                                                            <input type="text" name="d_mobile" id="d_mobile" class="form-control"/>
                                                            <span style="color:red;" id="d_mobile_error"></span>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-primary" id="family_add_btn" onclick="add_doctor();">Add</button>
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <script>
                                            function add_doctor() {
                                                var d_name = $("#d_name").val();
                                                var d_mobile = $("#d_mobile").val();
                                                $("#d_name_error").html("");
                                                $("#d_mobile_error").html("");
                                                if ($("#test_city").val() == "1") {
            var testcity = 333;
        }
        if ($("#test_city").val() == "5") {
            var testcity = 1510;
        }

        if ($("#test_city").val() == "6") {
            var testcity = 345;
        }
        if ($("#test_city").val() == "8") {
            var testcity = 476;
        }
                                                var cnt = 0;
                                                if (d_name.trim() == '') {
                                                    cnt = 1;
                                                    $("#d_name_error").html("Required.");
                                                }
                                                if (d_mobile.trim() == '') {
                                                    cnt = 1;
                                                    $("#d_mobile_error").html("Required.");
                                                }
                                                if(cnt == 0) {
                                                    $.ajax({
                                                        url: "<?php echo base_url(); ?>Admin/add_doctor",
                                                        type: 'post',
                                                        data: {d_mobile: d_mobile, d_name: d_name,city:testcity},
                                                        success: function (data) {
                                                            $.ajax({
            url: '<?php echo base_url(); ?>Admin/get_refered_by',
            type: 'post',
            data: {val: testcity,selected:data},
            success: function (data) {
                var json_data = JSON.parse(data);
                $("#referral_by").html(json_data.refer);
                //$("#test").html(json_data.test_list);
                //$("#existingCustomer12").html(json_data.customer);
                $('.chosen').trigger("chosen:updated");
            },
            error: function (jqXhr) {
                $("#referral_by").html("");
                //$("#test").html("");
                //$("#existingCustomer12").html("");
            },
            complete: function () {
                //$("#shedule_loader_div").attr("style", "display:none;");
                //$("#send_opt_1").removeAttr("disabled");
            },
        });
                                                            $("#doctor_add").modal("hide");
                                                        }
                                                    });
                                                }
                                                
                                            }
                                        </script>

                                        <div class="form-group col-sm-12  pdng_0">
                                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Email :</label>
                                            <div class="col-sm-9 pdng_0">
                                                <?php /* <input type="text" onblur="check_email(this.value);" id="email" class="form-control" name="email" value="<?php echo ucwords($user_info[0]['email']); ?>"/> */ ?>
                                                <input type="text" id="email" onblur="check_email(this.value);" class="form-control" name="email" value="<?php echo ucwords($user_info[0]['email']); ?>"/> 
                                                <small>(if email is not exist then use <b><a href="javascript:void(0);" onclick="email.value = 'noreply@airmedlabs.com'">noreply@airmedlabs.com</a></b>)</small>
                                                <span style="color:red;" id="email_error"></span>
                                                <span style="color:red;" id="email_error1"></span>
                                                <input type="hidden" id="email_check" value="0"/>
                                            </div>
                                        </div>

                                        <script>
                                            var _timer = 0;
                                            function DelayedCallMe(num, val) {
                                                if (_timer)
                                                    window.clearTimeout(_timer);
                                                _timer = window.setTimeout(function () {
                                                    check_phn(val);
                                                }, 500);
                                            }
                                            cust_id = "";
                                            function check_phn(val) {
                                                $("#phone_error1").html("");
                                                $("#phone_error").html("");
                                                if (checkmobile(val) == true) {
                                                    $.ajax({
                                                        url: "<?php echo base_url(); ?>Admin/check_phone",
                                                        type: 'post',
                                                        data: {phone: val, cust_id: cust_id},
                                                        success: function (data) {
                                                            var json_data = JSON.parse(data);
                                                            if (json_data.data[0].count > 0) {
                                                                $('#existingCustomer12 option[value="' + json_data.data[0].id + '"]').prop('selected', true);
                                                                $('.chosen').trigger("chosen:updated");
                                                                get_user_info(json_data.data[0].id);
                                                                $email_varify = 1;
                                                                //$("#phone_error1").html("This phone number already used, Try different.");
                                                            } else {
                                                                $('#existingCustomer12 option[value=""]').prop('selected', true);
                                                                $('.chosen').trigger("chosen:updated");
                                                            }
                                                            $("#phone_check").val(data.trim());
                                                        }
                                                    });
                                                } else if (val == '') {
                                                    $("#phone_error1").html("Please enter phone number.");
                                                } else {
                                                    $("#phone_error1").html("Invalid phone number.");
                                                }
                                            }
                                            //$email_varify = 0;
                                            function check_email(val) {
                                                $("#email_error1").html("");
                                                if (val == '' || val == 'noreply@airmedlabs.com') {
                                                    $email_varify = 1;
                                                    return false;
                                                }
                                                if (checkemail(val) == true) {
                                                    $.ajax({
                                                        url: "<?php echo base_url(); ?>Admin/check_email",
                                                        type: 'post',
                                                        data: {email: val, cust_id: cust_id},
                                                        success: function (data) {
                                                            if (data.trim() > 0 && $email != val) {
                                                                $("#email_error1").html("This email address already used, Try different.");
                                                            } else {
                                                                $email_varify = 1;
                                                            }
                                                            $("#email_check").val(data.trim());
                                                        }
                                                    });
                                                } else {
                                                    $("#email_error1").html("Invalid email.");
                                                    $email_varify = 0;
                                                }
                                            }
                                        </script>
                                        <div class="form-group col-sm-12  pdng_0">
                                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Gender :<span style="color:red;  font-size: 15px;">*</span></label>
                                            <div class="col-sm-9 pdng_0">
                                                <select name="gender" id="gender" class="form-control">
                                                    <option value="">--Select--</option>
                                                    <option value="male">Male</option>
                                                    <option value="female">Female</option>
                                                </select> 
                                                <span style="color:red;" id="gender_error"></span>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-group col-sm-12  pdng_0">
                                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Birth date:<span style="color:red;  font-size: 15px;">*</span></label>
                                            <div class="col-sm-9 pdng_0">
                                                <input type="text" id="dob" placeholder='Birth date' name="dob" class="datepicker form-control" value="" style="width:70%"/>OR<input type="text" class="form-control" style="width:20%" id="age_1" onkeyup="calculate_age(this.value);"/>
                                                <span style="color:red;" id="dob_error"></span>
                                            </div>
                                        </div>
                                        <script>

                                            function calculate_age(val)
                                            {
                                                var today_date = '<?= date("Y-m-d"); ?>';
                                                var get_date_data = today_date.split("-");

                                                var new_date = get_date_data[0] - val;
                                                new_date = new_date + "-" + get_date_data[1] + "-" + get_date_data[2];
                                                $("#dob").val(new_date);
                                            }
                                            function _calculateAge(birthday) { // birthday is a date
                                                var ageDifMs = Date.now() - birthday.getTime();
                                                var ageDate = new Date(ageDifMs); // miliseconds from epoch
                                                return Math.abs(ageDate.getUTCFullYear() - 1970);
                                            }
                                            //alert("1");
                                            //var aag = _calculateAge(date('1992-09-30');
                                            //alert(aag);

                                        </script>
                                        <br>
                                        <div class="form-group col-sm-12  pdng_0">
                                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Test city :<span style="color:red;">*</span></label> 
                                            <div class="col-sm-9 pdng_0">
                                                <select name="test_city" id="test_city" onchange="get_test(this);
                                                        get_branch(this.value);" class="form-control">
                                                        <?php
                                                        foreach ($test_cities as $ct) {
                                                            if ($login_data['type'] == 5 || $login_data['type'] == 6 || $login_data['type'] == 7) {
                                                                if (in_array($ct["id"], $branch_city_arry)) {
                                                                    echo '<option  value="' . $ct['id'] . '"';
                                                                    if (!isset($tst)) {
                                                                        echo " selected";
                                                                        $tst = '';
                                                                    }
                                                                    echo '>' . ucwords($ct['name']) . '</option>';
                                                                }
                                                            } else {
                                                                echo '<option value="' . $ct['id'] . '"';
                                                                echo '>' . ucwords($ct['name']) . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                </select>
                                            </div>
                                            <span style="color:red;" id="test_city_error"></span>
                                        </div> 

                                        <div class="form-group col-sm-12  pdng_0">
                                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Address :</label> 
                                            <div class="col-sm-9 pdng_0">
                                                <textarea id="address" name="address" class="form-control"><?php echo ucwords($query[0]['address']); ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-12  pdng_0">
                                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Branch :</label> 
                                            <div class="col-sm-9 pdng_0">
                                                <select id="branch" name="branch" class="form-control" style="width: 100%">
                                                    <option value="">--Select--</option>
                                                    <?php
                                                    foreach ($branch_list as $branch) {
                                                        if ($login_data['type'] == 5 || $login_data['type'] == 6 || $login_data['type'] == 7) {
                                                            if (in_array($branch['id'], $cntr_arry)) {
                                                                ?>
                                                                <option value="<?php echo $branch['id']; ?>" selected><?php echo ucwords($branch['branch_name']) . " - " . $branch['branch_code']; ?></option>
                                                                <?php
                                                            }
                                                        } else {
                                                            ?>
                                                            <option value="<?php echo $branch['id']; ?>"><?php echo ucwords($branch['branch_name']) . " - " . $branch['branch_code']; ?></option>

                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-12  pdng_0">
                                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Referral By :</label>
                                            <div class="col-sm-9 pdng_0">
                                                <select name="referral_by" id="referral_by" class="chosen">

                                                </select>
                                                <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#doctor_add" >ADD</button>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-12  pdng_0">
                                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Source :<span style="color:red;">*</span></label>
                                            <div class="col-sm-9 pdng_0">
                                                <select name="source" id="source" class="chosen">
                                                    <option value="">--Select--</option>
                                                    <?php
                                                    foreach ($source_list as $source) {
                                                        echo '<option value="' . $source['id'] . '"';
                                                        echo '>' . ucwords($source['name']) . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                                <span style="color:red" id="source_error"></span>
                                            </div>
                                        </div>

                                        <div class="form-group col-sm-12  pdng_0">
                                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Sample From :</label>
                                            <div class="col-sm-9 pdng_0">
                                                <select name="sample_from" id="sample_from" class="chosen">
                                                    <option value="">--Select--</option>
                                                    <?php
                                                    foreach ($sample_from as $s_key) {
                                                        echo '<option value="' . $s_key['id'] . '"';
                                                        echo '>' . ucwords($s_key['name']) . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                                <span style="color:red" id="sample_from_error"></span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="box box-primary">
                                <div class="box-header">
                                    <h3 class="box-title">Book Test/Package</h3>
                                    <button class="btn btn-sm btn-primary pull-right" style="display:none;" id="book_Active_package_btn" onclick="$('#book_active_package').modal('show');" type="button" style="margin-right:20px">Click here to book active package</button> 
                                </div>
                                <div class="box-body">
                                    <div class="col-md-12" id="all_packages">
                                        <div class="col-md-12">
                                            <div class="col-md-12">
                                                <div id="search_test">
                                                    <select class="chosen chosen-select" data-live-search="true" id="test" data-placeholder="Select Test" onchange="get_test_price();">

                                                    </select>
                                                </div>
                                                <span style="color:red;" id="test_error"></span>
                                            </div>
                                            <!--                                                <a href="javascript:void(0);" onclick="get_test_price();" style="margin-left:5px;" class="btn btn-primary"> Add</a>-->
                                            <span id="loader_div" style="display:none;"><img src="<?= base_url(); ?>upload/opc-ajax-loader.gif" style="height:28px;margin-right:10px"> </span> 
                                            <button class="btn btn-primary" id="show_test_btn" onclick="show_test_model();" type="button" style="display:none;">Add</button> 

                                        </div>
                                        <br><br>
                                        <table class="table table-striped" id="city_wiae_price">
                                            <thead>
                                                <tr>
                                                    <th>Test/Package Name</th>
                                                    <th>Price</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="t_body">
                                                <?php
                                                $cnt = 0;
                                                ?>
                                            </tbody>
                                        </table>

                                        <br>
                                        <div class="col-md-12">
                                            <div class="col-md-12" style="padding:0">
                                                <p class="lead">Amount</p>
                                                <div class="table-responsive">
                                                    <div class="col-sm-7 pdng_1" style="float:right;">
                                                        <div class="form-group">
                                                            <label for="collection_charge">Apply Sample collection Charge </label>
                                                            <input type="checkbox" value="1" name="collection_charge" onclick="ApplyCollectionCharge(this);" id="collection_charge" />
                                                        </div>
                                                    </div>
                                                    <script>
                                                        function ApplyCollectionCharge(val) {

                                                            var ttl_price = $("#total_id").val();
                                                            if (ttl_price == null) {
                                                                ttl_price = 0;
                                                            }
                                                            if (val.checked == true) {
                                                                $("#total_id").val(+ttl_price + 100);
                                                            }
                                                            if (val.checked == false) {
                                                                var ttl_price = ttl_price - 100;
                                                                if (ttl_price < 0) {
                                                                    ttl_price = 0;
                                                                }
                                                                $("#total_id").val(ttl_price);
                                                            }
                                                            var dscnt = $("#discount").val();
                                                            get_discount_price(dscnt,'3');
                                                        }
                                                    </script>
                                                    <table class="table">
                                                        <tr>
                                                            <th>Receive Amount: RS.</th>
                                                            <td><input type="text" onkeyup="get_discount_price(this.value, '1');" style="width:50%;float: left" value="0" name="received_amount" id="received_amount" class="form-control"/>
                                                                <select name="payment_via" class="form-control" style="width:50%;float: left">
                                                                    <?php foreach ($payment_type as $key){ ?>
                                                                    <option value="<?= strtoupper($key["name"])?>"><?=strtoupper($key["name"])?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Discount:</th>
                                                            <td><input style="width:50%;float: left;" type="text" onkeyup="get_discount_price(this.value, '2');" value="0" name="discount" id="discount" class="form-control"/>
                                                                <div style="width:40%;float: left;margin-left: 7px;"> % <input type="radio" onchange="get_discount_price(this.value, '3');" id="price_per_discount" name="discount_type" checked="checked" value="per" style="margin-right:5px;"> Flat <input type="radio" name="discount_type" style="margin-left:5px;" onchange="get_discount_price(this.value, '4');" value="flat">  </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Payable Amount: Rs.</th>
                                                            <td><div id="payable_div"><input type="text" name="payable" id="payable_val" value="0" readonly="" class="form-control"/></div></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Total Amount: Rs. </th>
                                                            <th><div id="total_id_div"><input type="text" name="total_amount" id="total_id" value="0" readonly="" class="form-control"/></div></th>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <label for="exampleInputFile">Note :</label> <textarea class="form-control" name="note" id="note"><?php echo $query[0]['note']; ?></textarea>
                                                <br><div class="form-group col-sm-12  pdng_0">
                                                    <div class="col-sm-9 pdng_0" style="float:right;">
                                                        <input type="checkbox" id="noify_cust" name="noify_cust" value="1" checked=""> <label for="noify_cust">Notify Customer?</label>
                                                    </div>
                                                </div>
                                            </div><!-- /.col -->
                                        </div><!-- /.row -->
                                    </div><!-- /.box -->
                                </div>
                                <input type="hidden" name="submit_type" id="submit_type" value="0"/>
                                <script>
                                    $(function () {
                                        $('.chosen').chosen();
                                        getActiveCall();
                                    });
                                    setInterval(function () {
                                        getActiveCall();
                                    }, 3000);
                                    ajaxCall = null;
                                    function getActiveCall() {

                                        if (ajaxCall != null) {
                                            ajaxCall.abort();
                                        }
                                        $ajaxCall = $.ajax({
                                            url: "<?php echo base_url(); ?>Admin/get_call_user_data/",
                                            error: function (jqXHR, error, code) {
                                            },
                                            success: function (data) {

                                                d = JSON.parse(data);
                                                if (d.number) {
                                                    if (d.customer) {
                                                        $("#show_number_print").show();

                                                        $('.chosen').val(d.customer['id']);

                                                        get_user_info({value: d.customer['id'] + ""});
                                                        $('.chosen').trigger("chosen:updated");
                                                        $("#number_print").html("New Incoming Call Number <b>" + d.number +
                                                                ".</b>" + " Registered user name " + d.customer['full_name']);
//                                                        $("#show_number_print").show();
                                                        $("#show_number_print1").hide();

                                                    } else {
                                                        $("#name").val('');
                                                        $("#phone").val('');
                                                        //$("#email").val('');
                                                        var elements = document.getElementById("gender").options;
                                                        for (var i = 0; i < elements.length; i++) {
                                                            elements[i].removeAttribute("selected");
                                                            elements[i].selected = false;
                                                        }
                                                        $("#address").val('');
                                                        $("#existingCustomer12").val('').trigger('chosen:updated');
                                                        $("#number_print1").html("New Incoming Call Number <b>" + d.number +
                                                                ".</b>");
                                                        $('#phone').val(d.number);
                                                        $("#show_number_print1").show();
                                                        $("#show_number_print").hide();
                                                        $("#order_details").hide();
                                                    }

                                                } else {
//                                                    $("#name").val('');
//                                            $("#phone").val('');
//                                            $("#email").val('');
//                                            var elements = document.getElementById("gender").options;
//                                            for (var i = 0; i < elements.length; i++) {
//                                                elements[i].removeAttribute("selected");
//                                                elements[i].selected = false;
//                                            }
//                                            $("#address").val('');
                                                    $("#show_number_print").hide();
                                                    $("#show_number_print1").hide();
                                                }


                                            }
                                        });
                                    }
                                    function get_test(val) {

                                        if (val.value.trim()) {
                                            $.ajax({
                                                url: '<?php echo base_url(); ?>Admin/get_test_list/' + val.value,
                                                type: 'post',
                                                data: {val: val.value},
                                                success: function (data) {
                                                    var json_data = JSON.parse(data);
                                                    $("#t_body").html("");
                                                    $("#hidden_test").html("");
                                                    $("#discount").val("0");
                                                    $("#payable_val").val("0");
                                                    $("#total_id").val("0");
                                                    $("#received_amount").val("0");
                                                    $("#test").html("");
                                                    $("#collection_charge").removeAttr("checked");
                                                    $("#test").html(json_data.test_list);
                                                    tst_details = json_data.test_ary;
                                                    $('.chosen').trigger("chosen:updated");
                                                },
                                                complete: function () {
                                                },
                                            });
                                        }
                                    }
                                    $email = '';
                                    function get_user_info(val) {
                                        if (val.trim() != '') {
                                            $.ajax({
                                                url: "<?php echo base_url(); ?>Admin/get_user_info",
                                                type: 'post',
                                                data: {user_id: val},
                                                success: function (data) {
                                                    var json_data = JSON.parse(data);
                                                    cust_id = json_data.user_info.id.trim();
                                                    if (json_data.user_info.full_name.trim()) {
                                                        $("#name").val(json_data.user_info.full_name);
                                                    } else {
                                                        $("#name").val("");
                                                    }
                                                    if (json_data.user_info.mobile.trim()) {
                                                        $("#phone").val(json_data.user_info.mobile);
                                                    } else {
                                                        $("#phone").val("");
                                                    }
                                                    if (json_data.user_info.email.trim()) {
                                                        $("#email").val(json_data.user_info.email);
                                                        $email = json_data.user_info.email;
                                                    } else {
                                                        $("#email").val("");
                                                    }
                                                    if (json_data.user_info.dob.trim()) {
                                                        $("#dob").val(json_data.user_info.dob);
                                                        bid_datepicker();
                                                    } else {
                                                        $("#dob").val("");
                                                    }
                                                    if (json_data.user_info.gender.trim()) {
                                                        //$("#gender").val(json_data.gender);
                                                        $("#gender:selected").removeAttr("selected");
                                                        var elements = document.getElementById("gender").options;
                                                        for (var i = 0; i < elements.length; i++) {
                                                            elements[i].removeAttribute("selected");
                                                            elements[i].selected = false;
                                                        }
                                                        for (var i = 0; i < elements.length; i++) {
                                                            /*elements[i].selected = false;*/
                                                            if (elements[i].value.toUpperCase() == json_data.user_info.gender.toUpperCase()) {
                                                                elements[i].setAttribute("selected", "selected");
                                                            }
                                                        }
                                                    }
                                                    var active_pkg = json_data.active_package;
                                                    $("#user_active_package_list").empty();
                                                    if (active_pkg.length > 0) {
                                                        $("#book_Active_package_btn").attr("style", "");
                                                        $("#user_active_package_list").append("<option value=''>--Select--</option>");
                                                        for (var ap = 0; ap < active_pkg.length; ap++) {
                                                            //alert(active_pkg[ap].title);
                                                            var tst_for = 'Self';
                                                            if (active_pkg[ap].name != '' && active_pkg[ap].name != null) {
                                                                tst_for = active_pkg[ap].name;
                                                            }
                                                            $("#user_active_package_list").append("<option value='" + active_pkg[ap].id + "'>" + active_pkg[ap].title + " (" + tst_for + ")</option>");
                                                        }
                                                        $('.chosen').trigger("chosen:updated");
                                                    } else {
                                                        $("#book_Active_package_btn").attr("style", "display:none;");
                                                    }
                                                    $("#test_for").html(json_data.user_info.family);
                                                    if (json_data.user_info.address.trim()) {
                                                        $("#address").val(json_data.user_info.address);
                                                        $("#address111").val(json_data.user_info.address);
                                                    } else {
                                                        $("#address").val("");
                                                        $("#address111").val("");
                                                    }
                                                }
                                            });
                                            $email_varify = 1;
                                            $.ajax({
                                                url: "<?php echo base_url(); ?>Admin/get_user_orders",
                                                type: 'post',
                                                data: {user_id: val},
                                                error: function (jqXHR, error, code) {
                                                },
                                                success: function (data) {
                                                    if (data != '0') {
                                                        $("#order_table").empty();
                                                        $("#order_table").append(data);
                                                        $("#order_details").show();
                                                    } else {
                                                        $("#order_details").hide();
                                                    }
                                                }
                                            });
                                        } else {
                                            $("#name").val('');
                                            $("#phone").val('');
                                            $("#email").val('');
                                            var elements = document.getElementById("gender").options;
                                            for (var i = 0; i < elements.length; i++) {
                                                elements[i].removeAttribute("selected");
                                                elements[i].selected = false;
                                            }
                                            $("#address").val('');
                                            $("#order_details").attr("style", "display:none;");
                                        }
                                    }
                                    function submit_type1(val) {
                                        var cnt = 0;
                                        var name = $("#name").val();
                                        var email = $("#email").val();
                                        var phone = $("#phone").val();
                                        var gender = $("#gender").val();
                                        var dob = $("#dob").val();
                                        var source1 = $("#source").val();
                                        $("#phone_error1").html("");
                                        var test_city = $("#test_city").val();
                                        var time_slot = $("#time_slot").val();
                                        var date1 = $("#date").val();
                                        $("#name_error").html("");
                                        $("#email_error").html("");
                                        $("#phone_error").html("");
                                        $("#test_city_error").html("");
                                        $("#test_error").html("");
                                        $("#gender_error").html("");
                                        $("#dob_error").html("");
                                        $("#time_slot_error").html("");
                                        $("#date_error").html("");
                                        $("#source_error").html("");
                                        if (name == '') {
                                            cnt = cnt + 1;
                                            $("#name_error").html("Required");
                                        }
                                        if (source1 == '') {
                                            cnt = cnt + 1;
                                            $("#source_error").html("Required");
                                        }
                                        if (gender == '') {
                                            cnt = cnt + 1;
                                            $("#gender_error").html("Required");
                                        }
                                        if (dob == '') {
                                            cnt = cnt + 1;
                                            $("#dob_error").html("Required");
                                        }
                                        if (document.getElementById("emergency_req").checked == false) {
                                            if (time_slot == '') {
                                                cnt = cnt + 1;
                                                $("#time_slot_error").html("Required");
                                            }
                                            if (date1 == '') {
                                                cnt = cnt + 1;
                                                $("#date_error").html("Required");
                                            }
                                        }
                                        if (test_city == '') {
                                            cnt = cnt + 1;
                                            $("#test_city_error").html("Required");
                                        }
                                        if (checkmobile(phone) == false) {
                                            cnt = cnt + 1;
                                            $("#phone_error").html("Invalid");
                                        }
                                        if ($("#hidden_test").html().trim() == '') {
                                            cnt = cnt + 1;
                                            if (cnt == 1) {
                                                $("#test_error").html("Please select test.");
                                            }
                                        }
                                        if ($email_varify != 1) {
                                            cnt = cnt + 1;
                                        }
                                        if (cnt > 0) {
                                            return false;
                                        }

                                        $("#submit_type").val(val);
                                        setTimeout(function () {

                                            var val = $("#phone_check").val();
                                            if (val > 0) {
                                                return false;
                                            }
                                            $("#user_form").submit();
                                            //alert("ok");
                                        }, 500);
                                    }
                                    function checkmobile(mobile) {
                                        var filter = /^[789]\d{9}$/;
                                        if (filter.test(mobile)) {
                                            if (mobile.length == 10) {
                                                return true;
                                            } else {
                                                return false;
                                            }
                                        } else {
                                            return false;
                                        }
                                    }
                                    function checkemail(mail) {
                                        //var str=document.validation.emailcheck.value
                                        var filter = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i
                                        if (filter.test(mail)) {
                                            return true;
                                        } else {
                                            return false;
                                        }
                                    }
                                </script>
                                <div class="box-footer">
                                    <input type="button" onclick="submit_type1('1');" class="btn btn-primary" value="Book Test"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="col-sm-6"> 
                                <div class="box box-primary">
                                    <div class="box-header">
                                        <!-- form start -->
                                        <h3 class="box-title">Clinical History:<span style="color:red;">*</span> </h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="col-sm-12">

                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Clinical History:</label>
                                                <div class="col-sm-9 pdng_0 checkbox">
                                                    <label>
                                                        <input type="checkbox" value="1"  id="chk1" name="desc">Yes
                                                    </label>
                                                </div>

                                            </div>
                                        </div>
                                        <script type="text/javascript">
                                            $dr_priscription_check = 0;
                                            $(document).ready(function () {
                                                $('#chk1').change(function () {
                                                    if (!this.checked) {
                                                        $('.msg').hide();
                                                        $dr_priscription_check = 0;
                                                    } else {
                                                        $('.msg').show();
                                                        $dr_priscription_check = 1;
                                                    }
                                                });
                                            });
                                        </script>
                                        <div class="col-sm-12 msg" style="display:none">    
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Message:<span style="color:red;">*</span></label>
                                                <div class="col-sm-9 pdng_0">
                                                    <textarea type="text" name="message" class="form-control" id="ch_msg" ><?php echo set_value('message'); ?></textarea>
                                                    <span style="color:red;" class="msg_error" id="ch_msg_error"></span>
                                                </div>

                                            </div>

                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Prescription Upload:<span style="color:red;">*</span></label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="file" name="file" id="ch_file" /> 
                                                    <span style="color:red;" id="ch_file_error"></span>

                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>	
                        <div class="col-sm-12">
                            <div class="col-sm-6">
                                <div class="box box-primary">
                                    <div class="box-header">
                                        <!-- form start -->
                                        <h3 class="box-title">Schedule </h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="col-sm-12">
                                            <?php /*  <div class="form-group col-sm-12  pdng_0">
                                              <label class="col-sm-3 pdng_0" for="exampleInputFile">Select Phlebo :</label>
                                              <div class="col-sm-9 pdng_0">
                                              <select id="phlebo"  name="phlebo" class="chosen">
                                              <option value="">--Select--</option>
                                              <?php foreach ($phlebo_list as $phlebo) { ?>
                                              <option value="<?php echo $phlebo['id']; ?>"><?= ucfirst($phlebo["name"]) . "-" . $phlebo["mobile"]; ?></option>
                                              <?php } ?>
                                              </select>
                                              </div>
                                              </div> */ ?>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Date :<span style="color:red;">*</span></label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" id="date" name="phlebo_date" class="form-control datepicker-input" onchange="get_time(this.value);"/>
                                                    <span style="color:red;" id="date_error"></span>
                                                </div>

                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Time :<span style="color:red;">*</span></label>
                                                <div class="col-sm-9 pdng_0">
                                                    <select id="time_slot"  name="phlebo_time" class="chosen">
                                                        <option value="">--Select--</option>
                                                    </select>
                                                    <span style="color:red;" id="time_slot_error"></span>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <div class="col-sm-9 pdng_0" style="float:right;">
                                                    <input type="checkbox" id="emergency_req" name="emergency" value="1"> <label for="emergency_req">Mark as Emergency</label>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0" style="display:none;">
                                                <div class="col-sm-9 pdng_0" style="float:right;">
                                                    <input type="checkbox" id="notify" name="notify" value="1" checked> Notify Customer
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6" id="order_details" style="display: none;">
                                <div class="box box-primary">
                                    <div class="box-header">
                                        <!-- form start -->
                                        <h3 class="box-title">Order Details</h3>
                                    </div>
                                    <div class="box-body" id="order_table">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                        </section>

                        <script>
                            $city_cnt = <?= $cnt ?>;
                            function Validation() {
                                var cnt = 0;
                                var pm = 1;
                                var count = $("#count").val();
                                for (cnt = 0; cnt <= count; cnt++) {
                                    $("#descerror_" + cnt).html('');
                                    $("#reporterror_" + cnt).html('');
                                    var desc = $("#desc_" + cnt).val();
                                    var report = $("#report_" + cnt).val();
                                    if (desc == '') {
                                        pm = 1;
                                        $("#descerror_" + cnt).html('Description is required.');
                                    } else {
                                        pm = 0;
                                        $("#descerror_" + cnt).html('');
                                    }
                                    if (report == '') {
                                        pm = 1;
                                        $("#reporterror_" + cnt).html('Report is required.');
                                    } else {
                                        pm = 0;
                                        $("#reporterror_" + cnt).html('');
                                    }
                                }
                                if (pm == 0) {
                                    $("#submit_report").submit();
                                }
                            }
                            function change_status() {
                                var status = $("#status").val();
                                var job_id = $("#job_id").val();
                                if (status == "") {
                                    alert("Please Select Status!");
                                } else {
                                    $.ajax({
                                        url: "<?php echo base_url(); ?>job_master/changing_status_job",
                                        type: 'post',
                                        data: {status: status, jobid: job_id},
                                        success: function (data) {
                                            if (data == 1) {
                                                //     console.log("data"+data);
                                                window.location = "<?php echo base_url(); ?>job-master/job-details/<?php echo $cid; ?>";
                                                                    }
                                                                }
                                                            });
                                                        }
                                                    }
                        </script>
                        <script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
                        <script  type="text/javascript">
                                                    function get_test_price() {
                                                        var test_val = $("#test").val();
                                                        $("#test_error").html("");
                                                        //$("#desc_error").html("");
                                                        var cnt = 0;
                                                        if (test_val.trim() == '') {
                                                            $("#test_error").html("Test is required.");
                                                            cnt = cnt + 1;
                                                        }
                                                        if (cnt > 0) {
                                                            return false;
                                                        }
                                                        var skillsSelect = document.getElementById("test");
                                                        var selectedText = skillsSelect.options[skillsSelect.selectedIndex].text;
                                                        var prc = selectedText.split('(Rs.');
                                                        var prc1 = prc[1].split(')');
                                                        //console.log(prc);
                                                        var pm = skillsSelect.value;
                                                        var explode = pm.split('-');
                                                        /*Nishit code start*/
                                                        var selected_tst_id = test_val.split("-");
                                                        var is_sub_test = 0;
                                                        for (var st = 0; st < tst_details.length; st++) {
                                                            if (tst_details[st].id == selected_tst_id[1]) {
                                                                if (tst_details[st].sub_test != null) {
                                                                    var sub_tst_lits = tst_details[st].sub_test.split("%@%");
                                                                    var modal_txt = '';
                                                                    for (var mt = 0; mt < sub_tst_lits.length; mt++) {
                                                                        modal_txt += sub_tst_lits[mt] + "<br/>";
                                                                    }
                                                                    $("#sub_test").html(modal_txt);
                                                                    is_sub_test = 1;
                                                                }
                                                            }
                                                        }
                                                        /*Nihit code end*/
                                                        if (explode[0] == 'p') {
                                                            show_details(explode[1]);
                                                            var clic = "'" + explode[1] + "'";
                                                            $("#city_wiae_price").append('<tr id="tr_' + $city_cnt + '"><td><a href="#" data-toggle="modal" data-target="#myModal_view" onclick="show_details(' + clic + ');">' + prc[0] + '</a></td><td>Rs.' + prc1[0] + '</td><td><a href="javascript:void(0);" onclick="delete_city_price(\'' + $city_cnt + '\',\'' + prc1[0] + '\',\'' + prc[0] + '\',\'' + skillsSelect.value + '\')">Delete</a></td></tr>');
                                                        } else {
                                                            if (is_sub_test != 1) {
                                                                $("#city_wiae_price").append('<tr id="tr_' + $city_cnt + '"><td>' + prc[0] + '</td><td>Rs.' + prc1[0] + '</td><td><a href="javascript:void(0);" onclick="delete_city_price(\'' + $city_cnt + '\',\'' + prc1[0] + '\',\'' + prc[0] + '\',\'' + skillsSelect.value + '\')">Delete</a></td></tr>');
                                                            }
                                                            if (is_sub_test == 1) {
                                                                $("#city_wiae_price").append('<tr id="tr_' + $city_cnt + '"><td><a href="#" data-toggle="modal" onclick="$(\'#sub_test_modal\'\).modal(\'show\'\);">' + prc[0] + '</a></td><td>Rs.' + prc1[0] + '</td><td><a href="javascript:void(0);" onclick="delete_city_price(\'' + $city_cnt + '\',\'' + prc1[0] + '\',\'' + prc[0] + '\',\'' + skillsSelect.value + '\')">Delete</a></td></tr>');
                                                            }
                                                        }
                                                        $("#test option[value='1']").remove();
                                                        var old_dv_txt = $("#hidden_test").html();
                                                        /*Total price calculate start*/
                                                        var old_price = $("#total_id").val();
                                                        $("#total_id").val(+old_price + +prc1[0]);
                                                        $("#total_id").val(+old_price + +prc1[0]);
                                                        var dscnt = $("#discount").val();
                                                        get_discount_price(dscnt, '5');
                                                        /*Total price calculate end*/
                                                        $("#hidden_test").html(old_dv_txt + '<input id="tr1_' + $city_cnt + '" type="hidden" name="test[]" value="' + skillsSelect.value + '"/>');
                                                        $city_cnt = $city_cnt + 1;
                                                        //$("#test").val("");
                                                        $("#desc").val("");
                                                        $("#test option[value='" + skillsSelect.value + "']").remove();
                                                        $("#test").val('').trigger('chosen:updated');
                                                        $('#exampleModal').modal('hide');


                                                    }
                                                    function show_package_details(val) {
                                                    }
                                                    function delete_city_price(id, prc, name, value) {
                                                        var tst = confirm('Are you sure?');
                                                        if (tst == true) {
                                                            /*Total price calculate start*/
                                                            $('#test').append('<option value="' + value + '">' + name + ' (Rs.' + prc + ')</option>').trigger("chosen:updated");
                                                            var old_price = $("#total_id").val();
                                                            $("#total_id").val(old_price - prc);
                                                            var dscnt = $("#discount").val();
                                                            get_discount_price(dscnt,'4');
                                                            /*Total price calculate end*/
                                                            $("#tr_" + id).remove();
                                                            $("#tr1_" + id).remove();
                                                            get_test($("#test_city"));
                                                        }
                                                        setTimeout(function () {
                                                            get_price();
                                                        }, 1000);
                                                    }
                                                    function get_discount_price(val, typ = null) {
                                                        setTimeout(function () {
                                                            if (val != '' || val != '0') {
                                                                var check_discount = 0;
                                                                if ($("#price_per_discount").is(':checked')) {
                                                                    check_discount = 1;
                                                                }
                                                                 if (typ == '1' || typ == '2' || typ == '3' || typ == '4') {
                                                                    var total = $("#total_id").val();
                                                                    var received_amount = $("#received_amount").val();
                                                                    var discount1 = $("#discount").val();
                                                                    //var payable_val = $("#payable_val").val();
                                                                    var dis = val;
                                                                    if (check_discount == 1 && dscn != '' && dscn != '0') {
                                                                        var discountpercent = discount1 / 100;
                                                                        var discountprice = total - (total * discountpercent) - received_amount;
                                                                    } else {
                                                                        var discountprice = total - received_amount - discount1;
                                                                    }
                                                                    /*if($("#collection_charge").is(":checked")){
                                                                        discountprice = discountprice + 100;
                                                                    }*/
                                                                    //var payableamount = payable_val - discountprice;
                                                                    $("#payable_val").val(discountprice);
                                                                } else if (typ == '5') {
                                                                    var total1 = $("#total_id").val();
                                                                    var dscn = $("#discount").val();
                                                                    var received_amount = $("#received_amount").val();
                                                                    if (check_discount == 1 && dscn != '' && dscn != '0') {
                                                                        var discountpercent = dscn / 100;
                                                                        var discountprice = (total1 * discountpercent) - received_amount;
                                                                    } else {
                                                                        var discountprice = total1 - received_amount;
                                                                    }
                                                                    //$("#total_id").val(payableamount_total);
                                                                    $("#payable_val").val(discountprice);
                                                                }
                                                            } else {
                                                                var ttl = $("#total_id").val();
                                                                $("#payable_val").val(ttl);
                                                            }
                                                        }, 1000);
                                                    }
                                                    function show_details(val) {
                                                        $.ajax({
                                                            url: "<?php echo base_url(); ?>service/service_v2/package_details",
                                                            type: "POST",
                                                            data: {pid: val},
                                                            error: function (jqXHR, error, code) {
                                                            },
                                                            success: function (data) {
                                                                var json_data = JSON.parse(data);
                                                                $("#description").empty();
                                                                $("#description").append('<p>' + json_data.data + '</p>');
                                                                // console.log(data);
                                                            }
                                                        });
                                                    }
                        </script>

                    </div><!-- /.tab-pane -->
                </div><!-- /.tab-content -->
        </div><!-- nav-tabs-custom -->
    </div>
    <!-- /.row -->
</section>
<div id="myModal_view" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title clr_fff">Package Detail</h4>
            </div>
            <div class="modal-body">
                <div class="panel-content" id="description">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<div id="sub_test_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title clr_fff">Test Detail</h4>
            </div>
            <div class="modal-body">
                <div class="panel-content" id="sub_test">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<div class="modal fade" id="book_active_package" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <?php echo form_open("admin/book_active_package", array("method" => "POST", "role" => "form")); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Book Active Package</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="recipient-name" class="control-label">Select Package<i style="color:red;">*</i>:</label>
                    <select name="acttive_package" id="user_active_package_list" class="form-control" required="">
                    </select>
                    <span style="color:red;" id="f_relation_error1"></span>
                </div>
                <div class="form-group">
                    <label for="exampleInputFile" class="col-sm-3 pdng_0">Test city :<span style="color:red;">*</span></label> 

                    <select name="test_city" id="test_city1" onchange="get_branch1(this.value);" class="form-control" required="">
                        <?php
                        foreach ($test_cities as $ct) {
                            if ($login_data['type'] == 5 || $login_data['type'] == 6 || $login_data['type'] == 7) {
                                if (in_array($ct["id"], $branch_city_arry)) {
                                    echo '<option  value="' . $ct['id'] . '"';
                                    if (!isset($tst)) {
                                        echo " selected";
                                        $tst = '';
                                    }
                                    echo '>' . ucwords($ct['name']) . '</option>';
                                }
                            } else {
                                echo '<option value="' . $ct['id'] . '"';
                                echo '>' . ucwords($ct['name']) . '</option>';
                            }
                        }
                        ?>
                    </select>
                    <span style="color:red;" id="test_city1_error"></span>
                </div> 
                <div class="form-group">
                    <label for="exampleInputFile" class="col-sm-3 pdng_0">Branch :</label> 
                    <select id="branch1" name="branch" class="form-control" style="width: 100%">
                        <option value="">--Select--</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleInputFile" class="col-sm-3 pdng_0">Date :<span style="color:red;">*</span></label>
                    <input type="text" id="date1" name="active_phlebo_date" class="form-control datepicker" onchange="get_time1(this.value);" readonly="" required=""/>
                    <span style="color:red;" id="date_error1"></span>
                </div>
                <div class="form-group">
                    <label for="exampleInputFile" class="col-sm-3 pdng_0">Time :<span style="color:red;">*</span></label>
                    <select id="time_slot1"  name="phlebo_time" class="form-control" required="">
                        <option value="">--Select--</option>
                    </select>
                    <span style="color:red;" id="time_slot_error1"></span>
                </div>
                <div class="form-group">
                    <label for="exampleInputFile" class="col-sm-3 pdng_0">Address :</label> 
                    <textarea id="address111" name="active_address" class="form-control" required=""></textarea>
                </div>
                <div class="form-group">
                    <input type="checkbox" id="active_package_notify" name="notify1" value="1" checked> <label for="active_package_notify">Notify Customer</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="family_add_btn" onclick="">Book</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
<script  type="text/javascript">
                        $(function () {

                            $('.chosen-select').chosen();

                        });

</script> 
<script src="<?php echo base_url(); ?>plugins/timepick/js/timepicki.js"></script>
<script>
                        //        $('#time').timepicki();
</script>
<script type="text/javascript">
    function get_pending_count2() {

        $.ajax({
            url: "<?php echo base_url(); ?>job_master/pending_count/",
            error: function (jqXHR, error, code) {
                // alert("not show");
            },
            success: function (data) {
                //     console.log("data"+data);
                //var jsonparse = JSON.Parse(data);
                var obj = $.parseJSON(data);
                console.log(obj.job_count);
                //document.getElementById('pending_count').innerHTML = "";
                //document.getElementById('pending_count').innerHTML = obj.job_count;
                document.getElementById('pending_count_1').innerHTML = obj.job_count;
                document.getElementById('pending_count_2').innerHTML = obj.package_count;
                document.getElementById('test_package_count').innerHTML = obj.all_inquiry;
                if (obj.tickepanding != '0') {
                    document.getElementById('supportpanding').innerHTML = obj.tickepanding;
                }
                if (obj.job_count != '0') {
                    document.getElementById('pending_count').innerHTML = obj.job_count;
                }
                if (obj.contact_us_count != '0') {
                    document.getElementById('contact_us').innerHTML = obj.contact_us_count;
                }

            }
        });

    }

    get_pending_count2();

    /*window.setInterval(function() {
     $('.chosen-select').trigger('chosen:updated');
     }, 1000); */
    function setCustomerValue(cid) {
        getDetailsByid(cid);

        $("#existingCustomer").val(cid);
        $('#existingCustomer').trigger('chosen:updated');
    }
    function setFormValue(name, email, mobile) {
        $("#name").val(name);
        $("#email").val(email);
        $("#phone").val(mobile);
    }
    function getDetailsByid(val) {
        $.ajax({
            url: "<?php echo base_url(); ?>Admin/get_user_info",
            type: 'post',
            data: {user_id: val},
            success: function (data) {
                var json_data = JSON.parse(data);
                cust_id = json_data.id.trim();
                if (json_data.full_name.trim()) {
                    $("#name").val(json_data.full_name);
                } else {
                    $("#name").val("");
                }
                if (json_data.mobile.trim()) {
                    $("#phone").val(json_data.mobile);
                } else {
                    $("#phone").val("");
                }
                if (json_data.email.trim()) {
                    $("#email").val(json_data.email);
                } else {
                    $("#email").val("");
                }
                if (json_data.gender.trim()) {
                    //$("#gender").val(json_data.gender);
                    $("#gender:selected").removeAttr("selected");
                    var elements = document.getElementById("gender").options;
                    for (var i = 0; i < elements.length; i++) {
                        elements[i].removeAttribute("selected");
                        elements[i].selected = false;
                    }
                    for (var i = 0; i < elements.length; i++) {
                        /*elements[i].selected = false;*/
                        if (elements[i].value.toUpperCase() == json_data.gender.toUpperCase()) {
                            elements[i].setAttribute("selected", "selected");
                        }
                    }
                }
                if (json_data.address.trim()) {
                    $("#address").val(json_data.address);
                } else {
                    $("#address").val("");
                }
            }
        });
    }
    function show_test_model1() {
        //$("#show_test_btn").attr('disabled',true);
        var values = $("input[name='test[]']").map(function () {
            return $(this).val();
        }).get();
        var ctid = $('#test_city').val();
        $.ajax({
            url: "<?php echo base_url(); ?>job_master/get_test_list1/",
            type: "POST",
            data: {ids: values, ctid: ctid},
            error: function (jqXHR, error, code) {
            },
            beforeSend: function () {
                $("#loader_div").attr("style", "");
                $("#show_test_btn").attr("disabled", "disabled");
            },
            success: function (data) {
                $("#city_wise_test").empty();
                $("#city_wise_test").append(data);
                // console.log(data);
            },
            complete: function () {
                $("#loader_div").attr("style", "display:none;");
                $("#show_test_btn").removeAttr("disabled");
            },
        });
        //$('#exampleModal').modal('show');
        console.log(values);
    }
    function save_user_data() {
        var customer_test = $("input[name='test[]']").map(function () {
            return $(this).val();
        }).get();
        var customer_fk = $('#existingCustomer12').val();
        var customer_name = $('#name').val();
        var customer_mobile = $('#phone').val();
        var customer_email = $('#email').val();
        var customer_gender = $('#gender').val();
        var customer_city = $('#test_city').val();
        var customer_address = $('#address').val();
        var pay_discount = $('#discount').val();
        var payable_amount = $('#payable_val').val();
        var referral_by = $('#referral_by').val();
        var phlebo = $('#phlebo').val();
        var phlebo_date = $('#date').val();
        var phlebo_time = $('#time_slot').val();
        var source = $('#source').val();
        if ($('#notify').prop('checked')) {
            var notify = 1;
        } else {
            var notify = 0;
        }
        var total_amount = $('#total_id').val();
        var note = $('#note').val();
        $.ajax({
            url: "<?php echo base_url(); ?>Admin/get_telecaller_remain/",
            type: "POST",
            data: {cid: customer_fk, cname: customer_name, cmobile: customer_mobile, cemail: customer_email, cgender: customer_gender, ctestcity: customer_city, caddress: customer_address, cbooktest: customer_test, cdis: pay_discount, cpayableamo: payable_amount, ctotalamo: total_amount, cnote: note, referral_by: referral_by, source: source, phlebo: phlebo, phlebo_date: phlebo_date, phlebo_time: phlebo_time, notify: notify},
            error: function (jqXHR, error, code) {
            },
            success: function (data) {
                if (data != 0) {
                    $("#t_body").empty();
                    $('#existingCustomer12').val('').trigger('chosen:updated');
                    $('#referral_by').val('').trigger('chosen:updated');
                    $('#source').val('').trigger('chosen:updated');
                    $('#phlebo').val('').trigger('chosen:updated');
                    $('#date').val('');
                    $('#time_slot').val('').trigger('chosen:updated');
                    $('#name').val('');
                    $('#phone').val('');
                    $('#email').val('');
                    $('#gender').val('');
                    $('#test_city').val('');
                    $('#address').val('');
                    $('#test').val('').trigger('chosen:updated');
                    $('#discount').val('0');
                    $('#payable_val').val('0');
                    $('#total_id').val('0');
                    $('#note').val('');
                }
            },
//            complete: function () {
//                $("#loader_div").attr("style", "display:none;");
//                $("#show_test_btn").removeAttr("disabled");
//            },
        });
    }
    function get_time(val) {
        $.ajax({
            url: '<?php echo base_url(); ?>phlebo-api_v4/get_phlebo_schedule',
            type: 'post',
            data: {bdate: val},
            success: function (data) {
                var json_data = JSON.parse(data);
                if (json_data.status == 1) {
                    for (var i = 0; i < json_data.data.length; i++) {
                        if (json_data.data[i].booking_status == 'Available') {
                            //$("#phlebo_shedule").append("<a href='javascript:void(0);' id='time_slot_" + json_data.data[i].id + "' onclick='get_select_time(this," + json_data.data[i].time_slot_fk + ");'><p>" + json_data.data[i].start_time + " TO " + json_data.data[i].end_time + "<br>(" + json_data.data[i].booking_status + ")</p></a>");
                            $('#time_slot').append('<option value="' + json_data.data[i].time_slot_fk + '">' + json_data.data[i].start_time + ' TO ' + json_data.data[i].end_time + ' (Available)</option>').trigger("chosen:updated");
                        } else {
                            //$("#phlebo_shedule").append("<a href='javascript:void(0);'><p>" + json_data.data[i].start_time + " TO " + json_data.data[i].end_time + "<br>(" + json_data.data[i].booking_status + ")</p></a>");
                            $('#time_slot').append('<option disabled>' + json_data.data[i].start_time + ' TO ' + json_data.data[i].end_time + ' (Unavailable)</option>').trigger("chosen:updated");
                        }
                    }
                } else {
                    if (json_data.error_msg == 'Time slot unavailable.') {
                        //$("#phlebo_shedule").empty();
                        //$("#phlebo_shedule").append('<div class="form-group"><label for="message-text" class="form-control-label">Request to consider as emergency:-</label><input type="checkbox" value="emergency" onclick="check_emergency(this);" value="emergency" id="as_emergency"></div>'+"<span style='color:red;'>" + json_data.error_msg + "</span>");
                    } else {
                        //$("#phlebo_shedule").html("<span style='color:red;'>" + json_data.error_msg + "</span>");
                    }
                }
            },
            error: function (jqXhr) {
                $("#phlebo_shedule").html("");
            },
            complete: function () {
                //$("#shedule_loader_div").attr("style", "display:none;");
                //$("#send_opt_1").removeAttr("disabled");
            },
        });
    }
    function get_time1(val) {
        $.ajax({
            url: '<?php echo base_url(); ?>phlebo-api_v4/get_phlebo_schedule',
            type: 'post',
            data: {bdate: val},
            success: function (data) {
                $('#time_slot1').empty();
                var json_data = JSON.parse(data);
                if (json_data.status == 1) {
                    for (var i = 0; i < json_data.data.length; i++) {
                        if (json_data.data[i].booking_status == 'Available') {
                            //$("#phlebo_shedule").append("<a href='javascript:void(0);' id='time_slot_" + json_data.data[i].id + "' onclick='get_select_time(this," + json_data.data[i].time_slot_fk + ");'><p>" + json_data.data[i].start_time + " TO " + json_data.data[i].end_time + "<br>(" + json_data.data[i].booking_status + ")</p></a>");
                            $('#time_slot1').append('<option value="' + json_data.data[i].time_slot_fk + '">' + json_data.data[i].start_time + ' TO ' + json_data.data[i].end_time + ' (Available)</option>').trigger("chosen:updated");
                        } else {
                            //$("#phlebo_shedule").append("<a href='javascript:void(0);'><p>" + json_data.data[i].start_time + " TO " + json_data.data[i].end_time + "<br>(" + json_data.data[i].booking_status + ")</p></a>");
                            $('#time_slot1').append('<option disabled>' + json_data.data[i].start_time + ' TO ' + json_data.data[i].end_time + ' (Unavailable)</option>').trigger("chosen:updated");
                        }
                    }
                } else {
                    if (json_data.error_msg == 'Time slot unavailable.') {
                        //$("#phlebo_shedule").append('<div class="form-group"><label for="message-text" class="form-control-label">Request to consider as emergency:-</label><input type="checkbox" value="emergency" onclick="check_emergency(this);" value="emergency" id="as_emergency"></div>'+"<span style='color:red;'>" + json_data.error_msg + "</span>");
                    } else {
                        //$("#phlebo_shedule").html("<span style='color:red;'>" + json_data.error_msg + "</span>");
                    }
                    $('#time_slot1').html("");
                }
            },
            error: function (jqXhr) {
                $("#phlebo_shedule").html("");
            },
            complete: function () {
                //$("#shedule_loader_div").attr("style", "display:none;");
                //$("#send_opt_1").removeAttr("disabled");
            },
        });

    }
</script> 
<!--Nishit code end-->
<script>
    $(document).ready(function () {
        var date_input = $('input[name="phlebo_date"]'); //our date input has the name "date"

        var container = $('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";
        date_input.datepicker({
            format: 'yyyy-mm-dd',
            container: container,
            todayHighlight: true,
            autoclose: true
        });
    });
</script>
<script type="text/javascript">
    window.setTimeout(function () {
        $(".alert").fadeTo(500, 0).slideUp(500, function () {
            $(this).remove();
        });
    }, 4000);</script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>

<script>
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        todayHighlight: true,
        autoclose: true
    });
    function bid_datepicker() {
//        $('.datepicker').datepicker({
//            format: 'yyyy-mm-dd'
//        });
    }
    tst_details = '';
    setTimeout(function () {
       
         if($("#test_city").val()=="1"){
            testcity=333
        }
        if($("#test_city").val()=="5"){
            testcity=1510
        }
         
          if($("#test_city").val()=="6"){
            testcity=345
        }
        if($("#test_city").val()=="8"){
            testcity=476
        }
        $.ajax({
            url: '<?php echo base_url(); ?>Admin/get_refered_by',
            type: 'post',
            data: {val: testcity},
            success: function (data) {
                var json_data = JSON.parse(data);
                $("#referral_by").html(json_data.refer);
                //$("#test").html(json_data.test_list);
                //$("#existingCustomer12").html(json_data.customer);
                $('.chosen').trigger("chosen:updated");
            },
            error: function (jqXhr) {
                $("#referral_by").html("");
                //$("#test").html("");
                //$("#existingCustomer12").html("");
            },
            complete: function () {
                //$("#shedule_loader_div").attr("style", "display:none;");
                //$("#send_opt_1").removeAttr("disabled");
            },
        });
        var tst_cty = $("#test_city").val();
        $.ajax({
            url: '<?php echo base_url(); ?>Admin/get_test_list/' + tst_cty,
            type: 'post',
            data: {val: tst_cty},
            success: function (data) {
                var json_data = JSON.parse(data);
                $("#t_body").html("");
                $("#hidden_test").html("");
                $("#discount").html("0");
                $("#payable_val").val("0");
                $("#total_id").val("0");
                $("#test").html("");
                $("#test").html(json_data.test_list);
                tst_details = json_data.test_ary;
                $('.chosen').trigger("chosen:updated");
            },
            complete: function () {
            },
        });
        $.ajax({
            url: '<?php echo base_url(); ?>Admin/get_customer_list',
            type: 'post',
            data: {val: 1},
            beforeSend: function () {
                $("#loader_div").attr("style", "");
            },
            success: function (data) {
                var json_data = JSON.parse(data);
                //$("#referral_by").html(json_data.refer);
                //$("#test").html(json_data.test_list);
                $("#existingCustomer12").html(json_data.customer);
                $('.chosen').trigger("chosen:updated");
            },
            error: function (jqXhr) {
                //$("#referral_by").html("");
                //$("#test").html("");
                $("#existingCustomer12").html("");
            },
            complete: function () {
                $("#loader_div").attr("style", "display:none;");
                //$("#send_opt_1").removeAttr("disabled");
            },
        });
    }, 500);
    function get_branch(val) {
        $.ajax({
            url: '<?php echo base_url(); ?>job_master/get_branch',
            type: 'post',
            data: {city: val},
            success: function (data) {
                $("#branch").html(data);
                $('.chosen').trigger("chosen:updated");

            },
            error: function (jqXhr) {
                $("#branch").html("");
            },
            complete: function () {
            },
        });
    }
    function get_branch1(val) {
        $.ajax({
            url: '<?php echo base_url(); ?>job_master/get_branch',
            type: 'post',
            data: {city: val},
            success: function (data) {
                $("#branch1").html(data);
                $('.chosen').trigger("chosen:updated");

            },
            error: function (jqXhr) {
                $("#branch1").html("");
            },
            complete: function () {
            },
        });
    }
    get_branch($("#test_city").val());
    get_branch1($("#test_city1").val());

</script>