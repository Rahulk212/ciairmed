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
</style>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- Custom Tabs -->
            <?php if (isset($success) != NULL) { ?>
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
        </div>
    </div>
    <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
            <section class="content">
                <div class="row">
                    <div class="row">
                        <div class="col-md-6">
                            <?php if ($login_data["type"] == 3) { ?>
                                <?php echo form_open_multipart("b2b/Logistic/sample_add", array("role" => "form", "method" => "POST", "id" => "user_form")); ?>
                            <?php } 
							else if(isset($lab_id)){
								echo form_open_multipart("b2b/Lab/sample_add", array("role" => "form", "method" => "POST", "id" => "user_form"));
							}else{
								
							}
							
							?>
                            <div class="box box-primary">

                                <div class="box-header">
                                    <!-- form start -->
                                    <h3 class="box-title">Sample Details</h3>
                                </div>
                                <div class="box-body">
                                    <div class="form-group col-sm-12  pdng_0">
                                        <label for="exampleInputFile" class="col-sm-3 pdng_0">Barcode :</label>
                                        <div class="col-sm-9 pdng_0">
                                            <input type="text" id="barcode" name="barcode" value="<?php echo set_value('barcode'); ?>" class="form-control"/>
                                            <span style="color:red;" id="barcode_error"></span>
                                        </div>
                                    </div>
									
									<?php if(!isset($lab_id)){ ?>
                                    <div class="form-group col-sm-12  pdng_0">
                                        <label for="exampleInputFile" class="col-sm-3 pdng_0">Collect from :</label>
                                        <div class="col-sm-9 pdng_0">
                                            <select class="form-control" name="lab_id" onchange="get_lab_test(this.value);">
                                                <option value="">Select Lab</option>
                                                <?php foreach ($lab_list as $lab) { ?>
                                                    <option value="<?php echo $lab['id']; ?>"><?php echo ucwords($lab['name']); ?></option>
                                                <?php } ?>
                                            </select>
                                            <span style="color:red;" id="lab_error"></span>
                                        </div>
                                    </div> <?php }else { 
									?>
									<input type="hidden" name="lab_id" value="<?php echo $lab_id; ?>" />
																		
									<?php
									} ?>
									<?php if(!isset($lab_id)){ ?>
                                    <div class="form-group col-sm-12  pdng_0">
                                        <label for="exampleInputFile" class="col-sm-3 pdng_0">Logistic name :</label>
                                        <div class="col-sm-9 pdng_0">
                                            <select class="form-control" name="logistic_id">
                                                <option value="">Select Logistic</option>
                                                <?php foreach ($phlebo_list as $phlebo) { ?>
                                                    <option value="<?php echo $phlebo['id']; ?>"><?php echo ucwords($phlebo['name']); ?></option>
                                                <?php } ?>
                                            </select>
                                            <span style="color:red;" id="logistic_error"></span>
                                        </div>
                                    </div>
									<?php } ?>
                                    <div class="form-group col-sm-12  pdng_0">
                                        <label for="exampleInputFile" class="col-sm-3 pdng_0">Upload Pic. :</label>
                                        <div class="col-sm-9 pdng_0">
                                            <input type="file" name="upload_pic">
                                            <span style="color:red;" id="logistic_error"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box box-primary">

                                <div class="box-header">
                                    <!-- form start -->
                                    <h3 class="box-title">Customer Details</h3>
                                </div>
                                <div class="box-body">
                                    <div id="hidden_test"><?php
                                        $cnt = 0;
                                        foreach ($job_details[0]["test_list"] as $ts1) {
                                            ?>
                                            <input id="tr1_<?= $cnt ?>" type="hidden" name="test[]" value="t-<?= $ts1["test_fk"] ?>"/>
                                            <?php
                                            $cnt++;
                                        }
                                        ?></div>
                                    <div class="col-md-12">
                                        <div class="form-group col-sm-12  pdng_0">
                                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Customer Name :</label>
                                            <div class="col-sm-9 pdng_0">
                                                <input type="text" id="customer_name" name="customer_name" value="<?php echo set_value('customer_name'); ?>" class="form-control"/>
                                                <span style="color:red;" id="customer_name_error"></span>
                                            </div>
                                        </div>
                                        <?php /* Nishit change payment status end */ ?>
                                        <div class="form-group col-sm-12  pdng_0">
                                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Mobile :</label>
                                            <div class="col-sm-9 pdng_0">
                                                <input type="text" id="customer_mobile" name="customer_mobile" placeholder="Ex.9879879870" value="<?php echo set_value('customer_mobile'); ?>" class="form-control"/>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-12  pdng_0">
                                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Email :</label>
                                            <div class="col-sm-9 pdng_0">
                                                <input type="text" id="customer_email" class="form-control" name="customer_email" value="<?php echo set_value('customer_email'); ?>"/>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-12  pdng_0">
                                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Gender :</label>
                                            <div class="col-sm-9 pdng_0">
                                                <select name="customer_gender" id="customer_gender" class="form-control">
                                                    <option value="">--Select--</option>
                                                    <option value="male">Male</option>
                                                    <option value="female">Female</option>
                                                </select>
                                                <span style="color:red;" id="gender_error"></span>
                                            </div>
                                        </div>
                                        <br>
                                        <input type="hidden" name="abc" value="12"/>
                                        <div class="form-group col-sm-12  pdng_0">
                                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Age:</label>
                                            <div class="col-sm-9 pdng_0">
                                                <input type="text" id="dob" placeholder='Birth date' name="dob" class="datepicker form-control" style="width:70%"/>OR<input type="text" class="form-control" style="width:20%" id="age_1" onkeyup="calculate_age(this.value);"/>
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
                                        </script>
                                        <br>
                                        <div class="form-group col-sm-12  pdng_0">
                                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Address :</label>
                                            <div class="col-sm-9 pdng_0">
                                                <textarea id="address" name="address" class="form-control"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-12  pdng_0">
                                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Note :</label>
                                            <div class="col-sm-9 pdng_0">
                                                <textarea id="address" name="note" class="form-control"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="box box-primary">
                                <div class="box-header">
                                    <h3 class="box-title">Test/Package</h3>
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
                                            <span id="loader_div" style="display:none;"><img src="<?= base_url(); ?>upload/opc-ajax-loader.gif" style="height:28px;margin-right:10px"> </span>
                                            <button class="btn btn-primary" id="show_test_btn" onclick="show_test_model();" type="button" style="display:none;">Add</button>

                                        </div>
                                        <br><br>
                                        <table class="table table-striped" id="city_wiae_price">
                                            <thead>
                                                <tr>
                                                    <th>Test Name</th>
                                                    <th>Price</th>
													<th>Special Price</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="t_body">
                                            </tbody>
                                        </table>

                                        <br>
                                        <div class="col-md-12">
                                            <div class="col-md-12" style="padding:0">
                                                <p class="lead">Amount</p>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <tr>
                                                            <th>Discount(%):</th>
                                                            <td><input type="text" onkeyup="get_discount_price(this.value);" value="" name="discount" id="discount" class="form-control"/></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Payable Amount: Rs.</th>
                                                            <td><div id="payable_div"><input type="text" name="payable" id="payable_val" value="0" readonly="" class="form-control"/></div></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Total Amount: Rs. </th>
                                                            <th><div id="total_id_div"><input type="text" name="total_amount" id="total_id" value="" readonly="" class="form-control"/></div></th>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div><!-- /.col -->
                                        </div><!-- /.row -->
                                    </div><!-- /.box -->
                                </div>
									
                                <?php if ($login_data["type"] == 3 || (isset($lab_id))) { ?>
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
                                                            $("#email").val('');
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
                                                    url: "<?php echo base_url(); ?>Admin/get_city_test",
                                                    type: 'post',
                                                    data: {city: val.value},
                                                    success: function (data) {
                                                        $("#t_body").html("");
                                                        $("#hidden_test").html("");
                                                        $("#discount").html("0");
                                                        $("#payable_val").val("0");
                                                        $("#total_id").val("0");
                                                        $("#search_test").html("");
                                                        $("#search_test").html(data);
                                                    }
                                                });
                                            }
                                        }

                                        function get_user_info(val) {
                                            if (val.value.trim() != '') {
                                                $.ajax({
                                                    url: "<?php echo base_url(); ?>Admin/get_user_info",
                                                    type: 'post',
                                                    data: {user_id: val.value},
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
                                                        if (json_data.dob.trim()) {
                                                            $("#dob").val(json_data.dob);
                                                            bid_datepicker();
                                                        } else {
                                                            $("#dob").val("");
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
                                                        $("#test_for").html(json_data.family);
                                                        if (json_data.address.trim()) {
                                                            $("#address").val(json_data.address);
                                                        } else {
                                                            $("#address").val("");
                                                        }
                                                    }
                                                });
                                                $.ajax({
                                                    url: "<?php echo base_url(); ?>Admin/get_user_orders",
                                                    type: 'post',
                                                    data: {user_id: val.value},
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
                                            $("#phone_error1").html("");
                                            var test_city = $("#test_city").val();
                                            $("#name_error").html("");
                                            $("#email_error").html("");
                                            $("#phone_error").html("");
                                            $("#test_city_error").html("");
                                            $("#test_error").html("");
                                            $("#gender_error").html("");
                                            $("#dob_error").html("");

                                            /*if (gender == '') {
                                             cnt = cnt + 1;
                                             $("#gender_error").html("Required");
                                             }
                                             if (dob == '') {
                                             cnt = cnt + 1;
                                             $("#dob_error").html("Required");
                                             }*/

                                            if ($("#hidden_test").html().trim() == '') {
                                                cnt = cnt + 1;
                                                if (cnt == 1) {
                                                    $("#test_error").html("Please select test.");
                                                }
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
                                <?php } ?>
                            </div>
                            <?php if ($login_data["type"] == 3) { ?>
                                <?php echo form_close(); ?>
                            <?php }	else if(isset($lab_id)){
								echo form_close();
							}else{
								
							}
						 ?>
                        </div>
                        </section>
                        <?php if ($login_data["type"] == 3 || isset($lab_id)) { ?>
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
															
															var sprc = selectedText.split('(SpecialPrice.');
                                                            var spprice = sprc[1].split(')');
															if(spprice[0] != ""){ var sprc1='<td>Rs.' + spprice[0] + '</td>'; }else{ var sprc1="<td></td>"; }
															
															var pm = skillsSelect.value;
                                                            var explode = pm.split('-');
															if (explode[0] == 'p') {
                                                                show_details(explode[1]);
                                                                var clic = "'" + explode[1] + "'";
                                                                $("#city_wiae_price").append('<tr id="tr_' + $city_cnt + '"><td><a href="#" data-toggle="modal" data-target="#myModal_view" onclick="show_details(' + clic + ');">' + prc[0] + '</a></td><td>Rs.' + prc1[0] + '</td>'+sprc1+'<td><a href="javascript:void(0);" onclick="delete_city_price(\'' + $city_cnt + '\',\'' + prc1[0] + '\',\'' + prc[0] + '\',\'' + skillsSelect.value + '\')">Delete</a></td></tr>');
                                                            } else {
																
                                                                $("#city_wiae_price").append('<tr id="tr_' + $city_cnt + '"><td>' + prc[0] + '</td><td>Rs.' + prc1[0] + '</td>'+sprc1+'<td><a href="javascript:void(0);" onclick="delete_city_price(\'' + $city_cnt + '\',\'' + prc1[0] + '\',\'' + prc[0] + '\',\'' + skillsSelect.value + '\')">Delete</a></td></tr>');
                                                            }
                                                            $("#test option[value='1']").remove();
                                                            var old_dv_txt = $("#hidden_test").html();
                                                            /*Total price calculate start*/
                                                            var old_price = $("#total_id").val();
                                                            $("#total_id").val(+old_price + +prc1[0]);
                                                            var dscnt = $("#discount").val();
                                                            get_discount_price(dscnt);
                                                            /*Total price calculate end*/
                                                            $("#hidden_test").html(old_dv_txt + '<input id="tr1_' + $city_cnt + '" type="hidden" name="test[]" value="' + skillsSelect.value + '"/>');
                                                            $city_cnt = $city_cnt + 1;
                                                            //$("#test").val("");
                                                            $("#desc").val("");
                                                            $("#test option[value='" + skillsSelect.value + "']").remove();
                                                            $("#test").val('').trigger('chosen:updated');
                                                            $('#exampleModal').modal('hide');


                                                        }
                                                        function delete_city_price(id, prc, name, value) {
                                                            var tst = confirm('Are you sure?');
                                                            if (tst == true) {
                                                                /*Total price calculate start*/
                                                                $('#test').append('<option value="' + value + '">' + name + ' (Rs.' + prc + ')</option>').trigger("chosen:updated");
                                                                var old_price = $("#total_id").val();
                                                                $("#total_id").val(old_price - prc);
                                                                var dscnt = $("#discount").val();
                                                                get_discount_price(dscnt);
                                                                /*Total price calculate end*/
                                                                $("#tr_" + id).remove();
                                                                $("#tr1_" + id).remove();
                                                            }
                                                            setTimeout(function () {
                                                                get_price();
                                                            }, 1000);
                                                        }
                                                        function get_discount_price(val) {
                                                            setTimeout(function () {
                                                                if (val != '' || val != '0') {

                                                                    var total = $("#total_id").val();
                                                                    var dis = val;

                                                                    var discountpercent = val / 100;
                                                                    var discountprice = (total * discountpercent);
                                                                    var payableamount = total - discountprice;
                                                                    $("#payable_val").val(payableamount);
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
                            <div class="modal fade" id="myModal_view" role="dialog">
                                <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <div class="modal-content srch_popup_full">
                                        <div class="modal-header srch_popup_full srch_head_clr">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title clr_fff">Package Detail</h4>
                                        </div>
                                        <div class="modal-body srch_popup_full">
                                            <div class="srch_popup_full srch_popup_acco">
                                                <div id="accordion1" class="panel-group accordion transparent">
                                                    <div class="panel">
                                                        <div class="panel-collapse collapse in" role="tablist" aria-expanded="true">
                                                            <div class="panel-content" id="description">

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div><!-- /.tab-pane -->
                </div><!-- /.tab-content -->
        </div><!-- nav-tabs-custom -->
    </div>
    <!-- /.row -->
</section>
<?php if ($login_data["type"] == 3) { ?>
    <script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
    <script  type="text/javascript">
                                                        $(function () {

                                                            $('.chosen-select').chosen();

                                                        });

    </script>
    <script src="<?php echo base_url(); ?>plugins/timepick/js/timepicki.js"></script>
    <script>
                                                        $('#time').timepicki();
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
                url: '<?php echo base_url(); ?>phlebo-api_v2/get_phlebo_schedule',
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
            format: 'yyyy-mm-dd'
        })
    </script>
<?php } 


?>


<script>
<?php 
 if(isset($lab_id)){
 
 echo "get_lab_test(".$lab_id."); ";
 } ?>
    function get_lab_test(lid) {
        $.ajax({
            url: "<?php echo base_url(); ?>b2b/logistic/get_lab_tests",
            type: 'post',
            data: {lab: lid},
            success: function (data) {
                //var json_data = JSON.parse(data);
                //$("#referral_by").html(json_data.refer);
                $("#search_test").html(data);
                //$("#existingCustomer12").html(json_data.customer);
                $('.chosen').trigger("chosen:updated");
            }
        });
    }
</script>