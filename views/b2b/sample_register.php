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
			
			 <?php if ($this->session->flashdata("error") != NULL) { ?>
                <div class="alert alert-danger alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">X</button>
                    <?php echo $this->session->flashdata("error") ?>
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
                                <?php echo form_open("b2b/Logistic/details/" . $id, array("role" => "form", "method" => "POST", "id" => "user_form")); ?>
                            <?php } ?>
                            <div class="box box-primary">

                                <div class="box-header">
                                    <!-- form start -->
                                    <h3 class="box-title">Sample Details</h3>
                                </div>
                                <div class="box-body">
                                    <div class="col-md-12">
                                        <div class="form-group col-sm-12  pdng_0 mrgn_0">
                                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Barcode : </label><?php echo $barcode_detail[0]["barcode"]; ?>

                                        </div>
                                        <div class="form-group col-sm-12  pdng_0 mrgn_0">
                                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Scan date : </label><?php echo $barcode_detail[0]["scan_date"]; ?> 

                                        </div>
                                        <div class="form-group col-sm-12  pdng_0 mrgn_0">
                                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Patient Name: </label><?php echo $job_details[0]["customer_name"]; ?> 

                                        </div>
                                        <div class="form-group col-sm-12  pdng_0 mrgn_0">
                                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Age : </label><?php echo $job_details[0]["age"] . "/" . $job_details[0]["age_type"]; ?> 

                                        </div>
                                        <div class="form-group col-sm-12  pdng_0 mrgn_0">
                                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Gender : </label><?php echo ucfirst($job_details[0]["customer_gender"]); ?> 

                                        </div>
									<?php if ($login_data["type"] == 3) { ?>	
                                        <div class="form-group col-sm-12  pdng_0 mrgn_0">
                                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Collect From : </label><?php echo ucfirst($barcode_detail[0]["c_name"]); ?>
                                        </div>
									<?php } ?>	
                                        <!--                                        <div class="form-group col-sm-12  pdng_0 mrgn_0">
                                                                                    <label for="exampleInputFile" class="col-sm-3 pdng_0">Logistic Name : </label><?php echo ucfirst($barcode_detail[0]["name"]); ?> 
                                        
                                                                                </div>-->
                                    </div>

                                </div>
                            </div>
                            <?php if ($login_data["type"] == 3) { ?>
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
                                                    <input type="text" id="customer_name" name="customer_name" value="<?= $job_details[0]["customer_name"]; ?>" class="form-control"/>
                                                    <span style="color:red;" id="name_error"></span>
                                                </div>
                                            </div>
                                            <?php /* Nishit change payment status end */ ?>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Mobile :</label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" id="customer_mobile" name="customer_mobile" placeholder="Ex.9879879870" value="<?= $job_details[0]["customer_mobile"]; ?>" class="form-control"/>
                                                    <span style="color:red;" id="phone_error"></span>
                                                    <span style="color:red;" id="phone_error1"></span>
                                                    <input type="hidden" id="phone_check" value="0"/>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Email :</label>
                                                <div class="col-sm-9 pdng_0">
                                                    <?php /* <input type="text" onblur="check_email(this.value);" id="email" class="form-control" name="email" value="<?php echo ucwords($user_info[0]['email']); ?>"/> */ ?>
                                                    <input type="text" id="customer_email" class="form-control" name="customer_email" value="<?= $job_details[0]["customer_email"]; ?>"/> 
                                                    <span style="color:red;" id="email_error"></span>
                                                    <span style="color:red;" id="email_error1"></span>
                                                    <input type="hidden" id="email_check" value="0"/>
                                                </div>
                                            </div>

                                            <script>
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
                                                                if (data.trim() > 0) {
                                                                    $("#phone_error1").html("This phone number already used, Try different.");
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
                                                function check_email(val) {
                                                    $("#email_error1").html("");
                                                    if (checkemail(val) == true) {
                                                        $.ajax({
                                                            url: "<?php echo base_url(); ?>Admin/check_email",
                                                            type: 'post',
                                                            data: {email: val, cust_id: cust_id},
                                                            success: function (data) {
                                                                if (data.trim() > 0) {
                                                                    $("#email_error1").html("This email address already used, Try different.");
                                                                }
                                                                $("#email_check").val(data.trim());
                                                            }
                                                        });
                                                    } else {
                                                        $("#email_error1").html("Invalid email.");
                                                    }
                                                }
                                            </script>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Gender :</label>
                                                <div class="col-sm-9 pdng_0">
                                                    <select name="customer_gender" id="gender" class="form-control">
                                                        <option value="">--Select--</option>
                                                        <option value="male" <?php
                                                        if ($job_details[0]["customer_gender"] == "male") {
                                                            echo "selected";
                                                        }
                                                        ?>>Male</option>
                                                        <option value="female" <?php
                                                        if ($job_details[0]["customer_gender"] == "female") {
                                                            echo "selected";
                                                        }
                                                        ?>>Female</option>
                                                    </select> 
                                                    <span style="color:red;" id="gender_error"></span>
                                                </div>
                                            </div>
                                            <br>
                                            <input type="hidden" name="abc" value="12"/>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Age:</label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" id="dob" placeholder='Birth date' name="dob" class="datepicker form-control" value="<?= $job_details[0]["customer_dob"]; ?>" style="width:70%"/>OR<input type="text" class="form-control" style="width:20%" id="age_1" onkeyup="calculate_age(this.value);"/>
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
                                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Referred By :</label>
                                            <div class="col-sm-9 pdng_0">
                                                <input type="text" id="" name="referby" value="<?= $job_details[0]["doctor"]; ?>" class="form-control"/>
                                                <span style="color:red;" id=""></span>
                                            </div>
                                        </div>
										    <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Address :</label> 
                                                <div class="col-sm-9 pdng_0">
                                                    <textarea id="address" name="address" class="form-control"><?= $job_details[0]["customer_address"]; ?></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Note :</label> 
                                                <div class="col-sm-9 pdng_0">
                                                    <textarea id="address" name="note" class="form-control"><?= $job_details[0]["note"]; ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                </div> <?php } ?>
                            <div class="box box-primary">
                                <div class="box-header">
                                    <h3 class="box-title">Test/Package</h3>
                                </div>
                                <div class="box-body">
                                    <div class="col-md-12" id="all_packages">
    <!--                                        <button class="btn btn-primary pull-right" id="show_test_btn" onclick="show_test_model1();" type="button" style="margin-right:20px"><i class="fa fa-plus-square" style="font-size:20px;"></i> Add Test/Package</button> 
                                        <span id="loader_div" style="display:none;" class="pull-right"><img src="<?= base_url(); ?>upload/opc-ajax-loader.gif" style="height:28px;margin-right:10px"> </span> -->
                                        <?php if ($login_data["type"] == 3) { ?>
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
                                        <?php } ?>
                                        <table class="table table-striped" id="city_wiae_price">
                                            <thead>
                                                <tr>
                                                    <th>Test Name</th>
                                                    <?php if ($login_data["type"] == 3) { ?>
                                                        <th>MRP</th>
                                                        <th>B2B Price</th>
                                                        <th>Action</th>
                                                    <?php } ?>
                                                </tr>
                                            </thead>
                                            <tbody id="t_body">
                                                <?php
                                                $cnt = 0;
                                                $total_price = 0;
                                                foreach ($job_details[0]["test_list"] as $ts1) {
													
                                                    //array_push($pids, $ts1['test_id']);
                                                    /* if ($ts1["info"][0]["special_price"] > 0) {
                                                        $prc1 = $ts1["info"][0]["special_price"];
                                                    } else if ($ts1["info"][0]["b2b_price"] > 0) {
                                                        $prc1 = $ts1["info"][0]["b2b_price"];
                                                    } else {
                                                        $prc1 = $ts1["info"][0]["price"];
                                                    } */
													
													if($ts1["info"][0]['specialprice'] != ""){ $discount=""; $spicelprice=$ts1["info"][0]['specialprice'];  }else{ $discount=$labdetils->test_discount; 
	 $discountprice=($ts1["info"][0]['price']*$discount/100);
 $spicelprice=$ts1["info"][0]['price']-$discountprice; 
	}
                                                    ?>
                                                    <tr id="tr_<?= $cnt ?>">
                                                        <td><?= $ts1["info"][0]["test_name"]; ?></td> 
                                                        <?php if ($login_data["type"] == 3) { ?>
                                                            <td>Rs.<?= $ts1["info"][0]['price']; ?></td>
                                                            <td>Rs.<?= $ts1['price']; ?></td>
                                                            <td><a href="javascript:void(0);" onclick="delete_city_price('<?= $cnt ?>', '<?= $ts1["info"][0]['price'];  ?>','<?= $ts1['price']; ?>','<?= $ts1["info"][0]["test_name"]; ?>','<?= "t-".$ts1["id"]; ?>')"> Delete</a></td>
                                                        <?php } ?>
                                                    </tr>
                                                    <?php
                                                    $total_price = $total_price + $ts1['price'];
                                                    $cnt++;
                                                }
                                                ?>
                                            </tbody>
                                        </table>

                                        <br>
                                        <?php if ($login_data["type"] == 3) { ?>
                                            <div class="col-md-12">
                                                <div class="col-md-12" style="padding:0">
                                                    <p class="lead">Amount</p>
                                                    <div class="table-responsive">
                                                        <table class="table">
                                                           <?php /* <tr>
                                                                <th>Discount(%):</th>
                                                                <td><input type="text" onkeyup="get_discount_price(this.value);" value="<?= $job_details[0]["discount"] ?>" name="discount" id="discount" class="form-control"/></td>
                                                            </tr> */ ?>
															<input type="hidden" onkeyup="get_discount_price(this.value);" value="0" name="discount" id="discount" class="form-control"/>
                                                            <tr>
                                                                <th>Payable Amount: Rs.</th>
                                                                <td><div id="payable_div"><input type="text" name="payable" id="payable_val" value="0" readonly="" class="form-control"/></div></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Total Amount: Rs. </th>
                                                                <th><div id="total_id_div"><input type="text" name="total_amount" id="total_id" value="<?= $total_price; ?>" readonly="" class="form-control"/></div></th>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div><!-- /.col -->
                                            </div><!-- /.row -->
                                        <?php } ?>
                                    </div><!-- /.box -->
                                </div>
                                <?php if ($login_data["type"] == 3) { ?>
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

                                            if (gender == '') {
                                             cnt = cnt + 1;
                                             $("#gender_error").html("The gender field is required");
                                             }
                                             if (dob == '') {
                                             cnt = cnt + 1;
                                             $("#dob_error").html("Required");
                                             }

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
                            <?php } ?>
                            <div class="box box-primary">

                                <div class="box-header">
                                    <!-- form start -->
                                    <h3 class="box-title">Upload Report</h3>
                                </div>
								<?php if ($login_data["type"] == 3) { ?>
                                <a href="javascript:void(0)" id="reportgenrate" class="btn btn-primary pull-right">Generate report</a>
								<?php } ?>

                                <form role="form" action="<?php echo base_url(); ?>b2b/Logistic/upload_report/<?= $id ?>" method="post" enctype="multipart/form-data" id="submit_report">
                                    <?php echo form_open_multipart("b2b/Logistic/upload_report/$id", array("role" => 'form', "id" => 'submit_report')); ?>
                                    <div class="box-body">
                                        <div class="form-group">
                                            <input type="file" multiple id="common_report" name="common_report[]" required="">
                                            <small>(Allow only .pdf file)</small><br>

                                            <?php
                                            if ($login_data["type"] == 3) {
                                                if ($jobspdf != null) {
                                                    ?>	<a href="<?= base_url() . "b2b/Logistic/pdfapprove/" . $id; ?>" data-toggle="tooltip" data-original-title="Approve" onclick="return confirm('Are you sure you want to Approve this report?');" class="btn btn-primary pull-right">Approve report</a><?php
                                                }
                                            }
                                            ?>

                                            <table class="table table-striped" id="city_wiae_price123">
                                                <tbody id="t_body123">
                                                    <?php foreach ($jobspdf as $pdf) { ?>
                                                        <tr id="pdf_<?= $pdf['id']; ?>">
                                                            <td><a href="<?php echo base_url(); ?>upload/business_report/<?php echo $pdf['report'] . '?' . time(); ?>" target="_blank"> <?php echo $pdf['original']; ?> </a></td>
                                                            <td><?php
                                                                if ($pdf['approve'] == 1) {
                                                                    echo "Approve";
                                                                } else {
                                                                    echo "Pending";
                                                                }
                                                                ?></td>
                                                            <td><a href="javascript:void(0);" id="dpdf_<?= $pdf['id']; ?>" class="pdfdelete" >Delete</a></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>

                                            <?php /* foreach($jobspdf as $pdf) { ?>
                                              <a href="<?php echo base_url(); ?>upload/business_report/<?php echo $pdf['report']; ?>" target="_blank"> <?php echo $pdf['original']; ?> </a> &nbsp; <br>
                                              <?php } */ ?>
                                            <input type="hidden" name="type_common_report" value="c"/>
                                        </div>
                                        <div class="form-group">
                                            <textarea class="form-control" name="desc_common_report"><?php echo $job_details[0]['report_description']; ?></textarea>
                                        </div>
                                        <div class="box-footer">
                                            <input class="btn btn-primary" value="Upload" type="submit">
                                        </div>
                                    </div>
                                </form>
                            </div>
						<?php /* <div class="box box-primary">

                                <div class="box-header">
                                    <!-- form start -->
                                    <h3 class="box-title">Upload documents</h3>
                                </div>
                                <a href="javascript:void(0)" id="reportgenrate" data-toggle="modal" data-target="#documentuplode" class="btn btn-primary pull-right">Upload documents</a>

                                    <div class="box-body">
                                       

                                            <table class="table table-striped" id="city_wiae_price123">
                                                <tbody id="t_body123">
                                                    <?php if($jobsdocks != null){ foreach ($jobsdocks as $docks) { ?>
                                                        <tr id="docksr_<?= $docks['id']; ?>">
                                                            <td><a href="<?php echo base_url(); ?>upload/labducuments/<?php echo $docks['document'].'?' . time(); ?>" target="_blank"> <?php echo $docks['name']; ?> </a></td>
                                                            
                                                            <td><a href="javascript:void(0);" id="docks_<?= $docks['id']; ?>" class="docksdelete" >Delete</a></td>
                                                        </tr>
                                                    <?php } }else{ echo "<tr><td colspan='2'>No documentation available</td></tr>"; } ?>
                                                </tbody>
                                            </table>
										</div>
										<script>
	 $(document).on("click", ".docksdelete", function () {

                                                            var id = this.id;
                                                            var splitid = id.split("_")
                                                            var pdfid = splitid[1];
															
                                                            $("#" + id).prop('disabled', true);

                                                             $.ajax({url: "<?php echo base_url() . "b2b/logistic/documentdelete"; ?>",
                                                                type: "POST",
                                                                data: {pid: pdfid},
                                                                error: function (jqXHR, error, code) {
                                                                },
                                                                success: function (data) {
																	
                                                                    if (data == 1) {
                                                                        $("#docksr_" + pdfid).remove();
                                                                    }
                                                                }
                                                            }); 

                                                        });
	</script>
                            </div> */ ?>
							
                        </div>
                        <?php if ($login_data["type"] == 3) { ?>
                            <div class="col-md-6">
                                <a href="<?= base_url(); ?>upload/barcode/<?php echo $barcode_detail[0]["pic"]; ?>" target="_blank"><img src="<?= base_url(); ?>upload/barcode/<?php echo $barcode_detail[0]["pic"]; ?>" class="col-sm-12" alt="Image not available."/></a>
                            </div>
                        <?php } ?>
                        <?php /*      <div class="col-sm-12">
                          <div class="col-sm-6">
                          <div class="box box-primary">
                          <div class="box-header">
                          <!-- form start -->
                          <h3 class="box-title">Assign Phlebo</h3>
                          </div>
                          <div class="box-body">
                          <div class="col-sm-12">
                          <div class="form-group col-sm-12  pdng_0">
                          <label class="col-sm-3 pdng_0" for="exampleInputFile">Select Phlebo :</label>
                          <div class="col-sm-9 pdng_0">
                          <select id="phlebo"  name="phlebo" class="chosen">
                          <option value="">--Select--</option>
                          <?php foreach ($phlebo_list as $phlebo) { ?>
                          <option value="<?php echo $phlebo['id']; ?>"><?= ucfirst($phlebo["name"]) . "-" . $phlebo["mobile"]; ?></option>
                          <?php } ?>
                          </select>
                          </div>
                          </div>
                          <div class="form-group col-sm-12  pdng_0">
                          <label for="exampleInputFile" class="col-sm-3 pdng_0">Date : </label>
                          <div class="col-sm-9 pdng_0">
                          <input type="text" id="date" name="phlebo_date" class="form-control datepicker-input" onchange="get_time(this.value);"/>
                          </div>
                          </div>
                          <div class="form-group col-sm-12  pdng_0">
                          <label for="exampleInputFile" class="col-sm-3 pdng_0">Time :</label>
                          <div class="col-sm-9 pdng_0">
                          <select id="time_slot"  name="phlebo_time" class="chosen">
                          <option value="">--Select--</option>
                          </select>
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
                          </div> */ ?>
                        </section>
                        <?php if ($login_data["type"] == 3) { ?>
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
                                                        $(document).on("click", ".pdfdelete", function () {


                                                            var id = this.id;
                                                            var splitid = id.split("_")
                                                            var pdfid = splitid[1];
                                                            $("#" + id).prop('disabled', true);

                                                            $.ajax({url: "<?php echo base_url() . "b2b/logistic/pdfdelete"; ?>",
                                                                type: "POST",
                                                                data: {pid: pdfid},
                                                                error: function (jqXHR, error, code) {
                                                                },
                                                                success: function (data) {
                                                                    if (data == 1) {
                                                                        $("#pdf_" + pdfid).remove();
                                                                    }
                                                                }
                                                            });

                                                        });
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
                                                            var prc2 = prc[1].split('( Rs.');
                                                            var prc3 = prc2[1].split(')');
                                                            var pm = skillsSelect.value;
                                                            var explode = pm.split('-');
															
                                                            var pm = skillsSelect.value;
                                                            var explode = pm.split('-');
                                                            $("#city_wiae_price").append('<tr id="tr_' + $city_cnt + '"><td>' + prc[0] + '</td><td>Rs.' + prc1[0] + '</td><td>Rs.' + prc3[0] + '</td><td><a href="javascript:void(0);" onclick="delete_city_price(\'' + $city_cnt + '\',\'' + prc1[0] + '\',\'' + prc3[0] + '\',\'' + prc[0] + '\',\'' + skillsSelect.value + '\')">Delete</a></td></tr>');
                                                            
                                                            $("#test option[value='1']").remove();
                                                            var old_dv_txt = $("#hidden_test").html();
                                                            /*Total price calculate start*/
                                                            var old_price = $("#total_id").val();
                                                            $("#total_id").val(+old_price + +prc3[0]);
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
                                                        function show_package_details(val) {
                                                        }
                                                        function delete_city_price(id,tprice, prc, name, value) {
                                                            var tst = confirm('Are you sure?');
                                                            if (tst == true) {
                                                                /*Total price calculate start*/
                                                                $('#test').append('<option value="' + value + '">' + name + '(Rs.'+ tprice + ') ( Rs.'+ prc +')</option>').trigger("chosen:updated");
                                                                var old_price = $("#total_id").val();
                                                                $("#total_id").val(old_price - prc);
                                                                var dscnt = $("#discount").val();
                                                                get_discount_price(dscnt);
                                                                $("#tr_" + id).remove();
                                                                $("#tr1_" + id).remove();
                                                            
															}
                                                            setTimeout(function () {
                                                                get_price();
                                                            }, 1000);
															$('#test').trigger("chosen:updated");
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


<div class="modal fade" id="documentuplode" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content srch_popup_full">
                <div class="modal-header srch_popup_full srch_head_clr">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title clr_fff">Upload document</h4>
                </div>
                <div class="modal-body srch_popup_full">

                    <?php echo form_open_multipart("b2b/Logistic/uplode_document/$id", array("role" => 'form', "id" => 'uplodedocks', "method" => 'post')); ?>
                    <div class="box-body">
					 <div class="form-group">
					  <label class="control-label">Name:</label>
                            <input type="test"  id="docksname" class="form-control" name="docksname" required="">
                            <small></small><br>
                        </div>
						
                        <div class="form-group">
                            <input type="file"  id="docks_report" name="docks_uplode" required="">
                            <small></small><br>
                          
                        </div>
                        <div class="box-footer">
                            <input class="btn btn-primary" value="Upload" type="submit">
                        </div>
                    </div>
                    </form>

                </div>
            </div>
        </div>
    </div>	
	

<?php if ($login_data["type"] == 3) { ?>

    <div class="modal fade" id="genreatereport" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content srch_popup_full">
                <div class="modal-header srch_popup_full srch_head_clr">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title clr_fff">Generate report</h4>
                </div>
                <div class="modal-body srch_popup_full">

                    <?php echo form_open_multipart("b2b/Logistic/genrate_report/$id", array("role" => 'form', "id" => '', "method" => 'post')); ?>
                    <div class="box-body">
                        <div class="form-group">
                            <input type="file" multiple id="common_report" name="common_report[]" required="">
                            <small></small><br>
                            <input type="hidden" name="type_common_report" value="c"/>
                        </div>
                        <?php /*  <div class="form-group">
                          <textarea class="form-control" name="desc_common_report"><?php echo $job_details[0]['report_description']; ?></textarea>
                          </div>
                          <div class="form-group">
                          <label class="radio-inline">
                          <input type="radio" checked value="1" name="latterpad">with letterhead
                          </label>
                          <label class="radio-inline">
                          <input type="radio" value="2" name="latterpad"> without letterhead
                          </label>
                          </div> */ ?>

                        <div class="box-footer">
                            <input class="btn btn-primary" value="Upload" type="submit">
                        </div>
                    </div>
                    </form>

                </div>
            </div>
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
                                                        $('#time').timepicki();
    </script>
    <script type="text/javascript">

        $("#reportgenrate").click(function () {
            $("#genreatereport").modal("show");

        });
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
        });
        function bid_datepicker() {
    //        $('.datepicker').datepicker({
    //            format: 'yyyy-mm-dd'
    //        });
        }
        setTimeout(function () {
            $.ajax({
                url: '<?php echo base_url(); ?>Admin/get_refered_by',
                type: 'post',
                data: {val: 1},
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
            var test_city = $("#test_city").val();
    //        $.ajax({
    //            url: '<?php echo base_url(); ?>Admin/get_test_list',
    //            type: 'post',
    //            data: {val: test_city},
    //            success: function (data) {
    //                var json_data = JSON.parse(data);
    //                //$("#referral_by").html(json_data.refer);
    //                $("#test").html(json_data.test_list);
    //                //$("#existingCustomer12").html(json_data.customer);
    //                $('.chosen').trigger("chosen:updated");
    //            },
    //            error: function (jqXhr) {
    //                //$("#referral_by").html("");
    //                $("#test").html("");
    //                //$("#existingCustomer12").html("");
    //            },
    //            complete: function () {
    //                //$("#shedule_loader_div").attr("style", "display:none;");
    //                //$("#send_opt_1").removeAttr("disabled");
    //            },
    //        });
            $.ajax({
                url: '<?php echo base_url(); ?>Admin/get_customer_list',
                type: 'post',
                data: {val: 1},
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
                    //$("#shedule_loader_div").attr("style", "display:none;");
                    //$("#send_opt_1").removeAttr("disabled");
                },
            });
            var lid = "<?php echo $barcode_detail[0]['collect_from'] ?>";
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
            get_discount_price(<?php
                    if (empty($job_details[0]["discount"])) {
                        echo 0;
                    } else {
                        echo $job_details[0]["discount"];
                    }
                    ?>);
        }, 500);

    </script>
<?php } ?>