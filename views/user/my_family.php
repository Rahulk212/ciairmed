<style>
    .res_table {float: left; width: 100%;}
    @media 
    only screen and (max-width: 767px) {

        /* Force table to not be like tables anymore */
        .res_table thead, .res_table tbody, .res_table th, .res_table td,.res_table tr { 
            display: block; 
        }

        /* Hide table headers (but not display: none;, for accessibility) */
        .res_table thead tr { 
            position: absolute;
            top: -9999px;
            left: -9999px;
        }

        .res_table tr { border: 1px solid #ccc; }

        .res_table td { 

            border: none;
            border-bottom: 1px solid #eee; 
            position: relative;
            padding-left: 50% !important; 
        }

        .res_table td:before { 
            /* Now like a table header */
            position: absolute; 
            /* Top/left values mimic padding */
            <!-- top: 6px;
            left: 6px; -->
            width: 45%; 
            <!-- padding-right: 10px;  -->
            white-space: nowrap;
        }
        .res_my_family_tbl_scrl{float: left; overflow-x: scroll; width: 100%;}

        .res_table td:nth-of-type(1):before { content: "No."; }
        .res_table td:nth-of-type(2):before { content: "Order Id"; }
        .res_table td:nth-of-type(3):before { content: "Test Name"; }
        .res_table td:nth-of-type(4):before { content: "Amount(Rs)"; }
        .res_table td:nth-of-type(5):before { content: "Date"; }
    }
    .select2.select2-container.select2-container--default {
        border: 1px solid #ccc;
        border-radius: 4px;
        height: 100%;
    }
    .select2-container .select2-selection--single .select2-selection__rendered {
        padding-left: 5px;
    }
</style>
<style>
    @media 
    only screen and (max-width: 767px),
    (min-device-width: 360px) and (max-device-width: 640px)  {

        /* Force table to not be like tables anymore */
        .res_table_cmpltjob thead, .res_table_cmpltjob tbody, .res_table_cmpltjob th, .res_table_cmpltjob td,.res_table_cmpltjob tr { 
            display: block; 
        }

        /* Hide table headers (but not display: none;, for accessibility) */
        .res_table_cmpltjob thead tr { 
            position: absolute;
            top: -9999px;
            left: -9999px;
        }

        .res_table_cmpltjob tr { border: 1px solid #ccc; }

        .res_table_cmpltjob td { 

            border: none;
            border-bottom: 1px solid #eee; 
            position: relative;
            padding-left: 50% !important; 
        }

        .res_table_cmpltjob td:before { 
            /* Now like a table header */
            position: absolute; 
            /* Top/left values mimic padding */
            <!-- top: 6px;
            left: 6px; -->
            width: 45%; 
            <!-- padding-right: 10px;  -->
            white-space: nowrap;
        }


        .res_table_cmpltjob td:nth-of-type(1):before { content: "No."; }
        .res_table_cmpltjob td:nth-of-type(2):before { content: "Job Order"; }
        .res_table_cmpltjob td:nth-of-type(3):before { content: "Test Name"; }
        .res_table_cmpltjob td:nth-of-type(4):before { content: "Amount(Rs)"; }
        .res_table_cmpltjob td:nth-of-type(5):before { content: "Date"; }
        .res_table_cmpltjob td:nth-of-type(6):before { content: "View Reports"; }
    }
</style>
<link href="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />

<!-- Start main-content -->
<div class="main-content">
    <!-- Section: home -->
    <section>
        <div class="container pdng_top_20px pdng_btm_30px">
            <div class="row">
                <div class="alert alert-success alert-dismissable" style="display:none;">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">X</button>
                    <span id="success_alert"></span>
                </div>
                <div class="col-sm-12">
                    <h1 class="txt_green_clr res_txt_grn">My Family</h1>
                </div>
                <div class="col-sm-12 pdng_0">
                    <div id="exTab3" class="container">	
                        <div class="tab-content clearfix tb">
                         <?php /*   <div class="tab-pane" id="1b">
                                <div class="col-sm-12">
                                    <div class="col-sm-6">

                                    </div>
                                    <div class="col-sm-6"></div>
                                </div>
                            </div>
                            <div style="text-align:right;" class="box-tools">
                                <ul class="pagination pagination-sm no-margin pull-right">
                                    <?php echo $links2; ?>
                                </ul>
                            </div> */ ?>
                            <div class="tab-pane active" id="2b">
                                <div class="row">
                                    <div class="col-sm-12 res_my_family_tbl_scrl">
                                        <table id="example4" class="table table-bordered table-striped">
                                            <button class="btn btn-dark btn-theme-colored btn-flat pull-right" data-toggle="modal" data-target="#myModal_4" type="button" style="    margin-bottom: 15px;">Add</button>
                                            <thead>
                                                <tr style="color:#fff;">
                                                    <th>Name</th>
                                                    <th>Relation</th>
                                                    <th>Gender</th>
                                                    <th>Email</th>
                                                    <th>Phone</th>
                                                    <th>Birth date</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $cnt = 1;
                                                $temp = 1;
                                                foreach ($family_member as $r_key) {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo ucwords($r_key["name"]); ?></td>
                                                        <td><?php echo ucwords($r_key["relation_name"]); ?></td>
                                                        <td><?php echo ucwords($r_key["gender"]); ?></td>
                                                        <td><?php echo $r_key["email"]; ?></td>
                                                        <td><?php echo ucwords($r_key["phone"]); ?></td>
                                                        <td><?php
                                                            if ($r_key["dob"] != '0000-00-00') {
                                                                echo ucwords($r_key["dob"]);
                                                            }
                                                            ?></td>
                                                        <td>
                                                            <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal_2" onclick="edit_details(<?php echo $r_key["id"]; ?>);" data-original-title="Edit Family Member"> <span class="label label-primary"><i class="fa fa-edit"> </i></span> </a>
                                                            <a onclick="delete_member(<?php echo $r_key['id']; ?>);" href="javascript:void(0)" data-toggle="tooltip"> <span class="label label-danger"><i class="fa fa-trash"></i></span> </a>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                                if (empty($family_member)) {
                                                    ?>
                                                    <tr>
                                                        <td colspan="5"><center>Data not available.</center></td>
                                                </tr>
<?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div style="text-align:right;" class="box-tools">
                                <ul class="pagination pagination-sm no-margin pull-right">
<?php echo $links1; ?>
                                </ul>
                            </div>
                        </div>

                        <div class="tab-pane" id="3b">

                        </div>

                    </div>
                </div>

            </div>
            <div class="row">
                <div class="full_div pdng_top_35px">
                    <div class="col-sm-6">
                        <h1 class="all_pg_lst_btns">An App for simplified pathology experience.</h1>
                    </div>
                    <div class="col-sm-6">
                        <div class="col-sm-12 pdng_0">
                            <div class="col-sm-6">
                                <a href="https://play.google.com/store/apps/details?id=com.patholab&hl=en" target="_blank"><img class="mbl_googl_res_mrgn app_full_img" src="<?php echo base_url(); ?>user_assets/images/google_play.png"/></a>
                            </div>
                            <div class="col-sm-6">
                                <a href="https://itunes.apple.com/in/app/airmed-pathlabs/id1152367695?mt=8" target="_blank"><img class="app_full_img" src="<?php echo base_url(); ?>user_assets/images/apple_appstore_big.png"/></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
        <div class="modal fade" id="myModal_2" role="dialog">
            <div class="modal-dialog aftr_srch_fill_info_sm_popup">
                <!-- Modal content-->
                <div class="modal-content srch_popup_full">
                    <div class="modal-header srch_popup_full srch_head_clr">
                        <button type="button" id="close_model" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title clr_fff">Edit Family Member</h4>
                    </div>
                    <div class="modal-body srch_popup_full">
                        <div class="srch_popup_full">
                            <div class="col-sm-12 pdng_0 full_div">
                                <div id="contener_1">
                                    <div id="family_div">
                                        <div class="col-sm-12 pdng_0 aftr_srch_family_info_div" id="edit_data" style="margin-bottom: 10px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer srch_popup_full">
                            <button type="button" id="final_book" class="btn btn-dark btn-theme-colored btn-flat pull-right" onclick='update_member();' >Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="myModal_4" role="dialog">
            <div class="modal-dialog aftr_srch_fill_info_sm_popup">
                <!-- Modal content-->
                <div class="modal-content srch_popup_full">
                    <div class="modal-header srch_popup_full srch_head_clr">
                        <button type="button" id="close_model_add" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title clr_fff">Add Family Member</h4>
                    </div>
                    <div class="modal-body srch_popup_full">
                        <div class="srch_popup_full">
                            <div class="col-sm-12 pdng_0 full_div">
                                <div id="contener_1">
                                    <div id="family_div">
                                        <div class="col-sm-12 pdng_0 aftr_srch_family_info_div" style="margin-bottom: 10px;">
                                            <label>Name:<span style="color:red;  font-size: 15px;">*</span></label>
                                            <input type="hidden" id="user_id" value="<?php echo $cid; ?>">
                                            <input type="text" class="form-control" id="amname" placeholder='Name'/>
                                            <span style="color:red;" id="amname_error"></span>
                                            <label>Relation: <span style="color:red;  font-size: 15px;">*</span></label>
                                            <select id="amrelation" class="form-control" style="margin-top: 10px; height:auto; width: 100%; padding-left: 0px !important;">
                                                <option value="">--Select Relation--</option>
                                                <?php foreach ($relation as $rkey): ?>
                                                    <option value="<?php echo $rkey["id"]; ?>"><?php echo ucwords($rkey["name"]); ?></option>
<?php endforeach; ?>
                                            </select>
                                            <span style="color:red;" id="amrelation_error"></span><br>
                                            <label>Phone:</label>
                                            <input placeholder='Phone Number' type="text" id="amphone" class="form-control" value=""/>
                                            <span style="color:red;" id="amphone_error"></span>
                                            <label>Email:</label>
                                            <input type="text" id="amemail" placeholder='Email Address' class="form-control" value=""/>
                                            <label>Gender: <span style="color:red;  font-size: 15px;">*</span></label>
                                            <select id="gender" class="form-control" style="margin-top: 10px; height:auto; width: 100%; padding-left: 0px !important;">
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                            </select>
                                            <label>Birth date:<span style="color:red;  font-size: 15px;">*</span></label>
                                            <input type="text" id="dob" placeholder='mm/dd/yyyy' class="datepicker form-control" value=""/>
                                            <span style="color:red;" id="dob_error"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer srch_popup_full">
                            <button type="button" id="final_book" class="btn btn-dark btn-theme-colored btn-flat pull-right" onclick='add_member();' >Add</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>



</div>

</section>
<script src="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
<script>
                                $('.datepicker').datepicker({
                                    dateFormat: 'dd/mm/yyyy'
                                });
                                function bid_datepicker() {
                                    $('.datepicker1').datepicker({
                                        dateFormat: 'dd/mm/yy'
                                    });
                                    //$('#datepicker').datepicker({ dateFormat: 'dd-mm-yy' }).val();
                                }
</script>
<script>
    function edit_details(val) {
        $.ajax({
            url: '<?php echo base_url(); ?>service/phlebo_service_v4/user_family_member_edit',
            type: 'post',
            data: {mid: val},
            success: function (data) {
                var json_data = JSON.parse(data);
                if (json_data.status == 1) {
                    $("#edit_data").empty();
                    var dob1 = json_data.data[0].dob;
                    var dob2 = dob1.split("-");
                    var gender = json_data.data[0].gender;
                    var $html_strng = '';
                    if (gender.toUpperCase() == 'MALE') {
                        $html_strng = "<option value='male' selected>Male</option><option value='female'>Female</option>";
                    } else if (gender.toUpperCase() == 'FEMALE') {
                        $html_strng = "<option value='male'>Male</option><option value='female' selected>Female</option>";
                    } else {
                        $html_strng = "<option value='male'>Male</option><option value='female'>Female</option>";
                    }
                    $("#edit_data").append("<label>Name:<span style='color:red;  font-size: 15px;'>*</span></label><input type='hidden' value='" + json_data.data[0].id + "' id='mid'/><input placeholder='Name' type='text' class='form-control' id='mname' value='" + json_data.data[0].name + "'/><span style='color:red;' id='name_error'></span><label>Relation: <span style='color:red;  font-size: 15px;'>*</span></label><select id='mrelation' class='form-control' style='margin-top: 10px; height:auto; width: 100%; padding-left: 0px !important;'><option value=''>--Select Relation--</option><?php foreach ($relation as $rkey): ?><option value='<?php echo $rkey['id']; ?>' " + (json_data.data[0].relation_fk == <?php echo $rkey['id']; ?> ? 'selected' : '') + "><?php echo ucwords($rkey['name']); ?></option><?php endforeach; ?></select><span style='color:red;' id='relation_error'></span><br><label>Phone:</label><input type='text' placeholder='Phone Number' id='mphone' class='form-control' value='" + json_data.data[0].phone + "'/><label>Email:</label><input type='text' placeholder='Email Address' id='memail' class='form-control' value='" + json_data.data[0].email + "'/><label>Gender:</label><select id='gender1' class='form-control' style='margin-top: 10px; height:auto; width: 100%; padding-left: 0px !important;'>"+$html_strng+"</select><label>Birth date:<span style='color:red;  font-size: 15px;'>*</span></label><input type='text' id='dob' placeholder='Birth date' value='" + dob2[2] + "/" + dob2[1] + "/" + dob2[0] + "' class='datepicker1 form-control' value=''/><span style='color:red;' id='dob_error'></span>");
                            bid_datepicker();
                } else {
                    if (json_data.error_msg == 'Data Not Available.') {
                        $("#edit_data").empty();
                        $("#edit_data").append('<div class="form-group"><label for="message-text" class="form-control-label">Data Not Available</label><input type="checkbox" value="emergency" onclick="check_emergency(this);" value="emergency" id="as_emergency"></div>' + "<span style='color:red;'>" + json_data.error_msg + "</span>");
                    } else {
                        $("#edit_data").html("<span style='color:red;'>" + json_data.error_msg + "</span>");
                    }
                }
            }
        });
    }
    function update_member() {
        var mid = $('#mid').val();
        var mname = $('#mname').val();
        var mrelation = $('#mrelation').val();
        var mphone = $('#mphone').val();
        var memail = $('#memail').val();
        var gender = $('#gender1').val();
        var dob = $("#dob").val();
        $("#name_error").html("");
        $("#relation_error").html("");
        if (mname == '') {
            $("#name_error").html("Please Enter Name.<br>");
            return false;
        }
        if (mrelation == '') {
            $("#relation_error").html("Please Select Relation.");
            return false;
        }
        if (dob == '') {
            $("#dob_error").html("Please Select Birth date.");
            return false;
        }
        $.ajax({
            url: '<?php echo base_url(); ?>service/phlebo_service_v4/user_family_member_update',
            type: 'post',
            data: {mid: mid, mname: mname, mrelation: mrelation, mphone: mphone, memail: memail, dob: dob,gender:gender},
            success: function (data) {
                var json_data = JSON.parse(data);
                if (json_data.status == 1) {
                    $('#close_model').click();
                    window.location.href = "<?php echo base_url(); ?>user_master/my_family";
                    window.setTimeout(function () {
                        $(".alert").fadeTo(500, 0).slideUp(500, function () {
                            $("#success_alert").html("Family Member Update Successfully.");
                            $(this).remove();
                        });
                    }, 4000);
                }
            }
        });
    }
    function add_member() {
        var uid = $('#user_id').val();
        var amname = $('#amname').val();
        var amrelation = $('#amrelation').val();
        var amphone = $('#amphone').val();
        var amemail = $('#amemail').val();
        var gender = $('#gender').val();
        var dob = $("#dob").val();
        $("#amname_error").html("");
        $("#amrelation_error").html("");
        var temp = 0;
        if (amname == '') {
            temp = 1;
            $("#amname_error").html("Please Enter Name.<br>");
        }
        if (amrelation == '') {
            temp = 1;
            $("#amrelation_error").html("Please Select Relation.");
        }
        if (dob == '') {
            temp = 1;
            $("#dob_error").html("Please Select Birth date.");
        }
        if (amphone != '') {
            if (amphone.length != 10) {
                temp = 1;
                $("#amphone_error").html("Enter Valid Number.<br>");
            }
        }
        if (temp != 1) {
            $.ajax({
                url: '<?php echo base_url(); ?>service/phlebo_service_v4/add_family_member',
                type: 'post',
                data: {uid: uid, name: amname, relation_fk: amrelation, phone: amphone, email: amemail, dob: dob, gender: gender},
                success: function (data) {
                    var json_data = JSON.parse(data);
                    if (json_data.status == 1) {
                        $('#close_model_add').click();
                        window.location.href = "<?php echo base_url(); ?>user_master/my_family";
                        window.setTimeout(function () {
                            $(".alert").fadeTo(500, 0).slideUp(500, function () {
                                $("#success_alert").html("Family Member Add Successfully.");
                                $(this).remove();
                            });
                        }, 4000);
                    }
                }
            });
        }
    }
    function delete_member(val) {
        var delete_c = confirm('Are you sure you want to spam this Family member ?');
        if (delete_c == true) {
            $.ajax({
                url: '<?php echo base_url(); ?>service/phlebo_service_v2/user_family_member_delete',
                type: 'post',
                data: {mid: val},
                success: function (data) {
                    var json_data = JSON.parse(data);
                    if (json_data.status == 1) {
                        window.location.href = "<?php echo base_url(); ?>user_master/my_family";
                        window.setTimeout(function () {
                            $(".alert").fadeTo(500, 0).slideUp(500, function () {
                                $("#success_alert").html("Family Member Delete Successfully.");
                                $(this).remove();
                            });
                        }, 4000);
                    }
                }
            });
        } else {
            return delete_c;
        }
    }
</script>
<!-- end main-content -->
<script src="<?php echo base_url(); ?>user_assets/js/jquery-2.2.0.min.js"></script>
<script src="<?php echo base_url(); ?>user_assets/js/jquery-ui.min.js"></script>
<script src="<?php echo base_url(); ?>user_assets/js/bootstrap.min.js"></script>
