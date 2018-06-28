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
            <!--            <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li><a href="javascript:void(0);" onclick="window.location = '<?= base_url(); ?>Admin/Telecaller'" data-toggle="tab">All Jobs(Tests) <span id="pending_count_1" class="label label-danger">0</span> </a></li>
                                <li><a href="javascript:void(0);" onclick="window.location = '<?= base_url(); ?>Admin/TelecallerPriscription'" data-toggle="tab">Prescription <span class="label label-danger"><?= count($unread); ?></span></a></li>
                                <li class="active"><a href="javascript:void(0);" data-toggle="tab">On Call Booking</a></li>
                                <li style="float: right;"><button class="btn btn-primary" onclick="save_user_data();"/>Save</button></li>
                            </ul>
                        </div>-->
            <?php if (isset($success) != NULL) { ?>
                <div class="alert alert-success alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <?php echo $success['0']; ?>
                </div>
            <?php } ?>
            <?php if (isset($error) != NULL) { ?>
                <div class="alert alert-danger alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
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
                                    <?php echo form_open("remains_book_master/add_book_job/$bid", array("role" => "form", "method" => "POST", "id" => "user_form")); ?>

                                    <div id="hidden_test"></div>
                                    <div class="col-md-12">
                                        <div class="form-group col-sm-12  pdng_0"">
                                            <label class="col-sm-3 pdng_0" for="exampleInputFile">Select Customer :</label>
                                            <div class="col-sm-9 pdng_0">
                                                <select id="existingCustomer12" name="customer" class="chosen" onchange="get_user_info(this);" disabled>
                                                    <option value="">--Select--</option>
                                                    <?php foreach ($customer as $key) { ?>
                                                        <option value="<?= $key["id"] ?>" <?php
                                                        if ($query[0]['customer_fk'] == $key['id']) {
                                                            echo "selected";
                                                        }
                                                        ?>><?= ucfirst($key["full_name"]) . ' - ' . $key["mobile"]; ?></option>
                                                            <?php } ?>
                                                </select> 
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-12  pdng_0"">
                                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Customer Name :<span style="color:red;">*</span></label> 
                                            <div class="col-sm-9 pdng_0">
                                                <input type="text" id="name" name="name" value="<?php echo ucfirst($query[0]['customer_name']); ?>" class="form-control"/>
                                                <input type="hidden" name="customer_fk" value="<?php echo $query[0]['customer_fk']; ?>">
                                            </div>
                                            <span style="color:red;" id="name_error"></span>
                                        </div>
                                        <div class="form-group col-sm-12  pdng_0"">
                                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Mobile :<span style="color:red;">*</span></label>
                                            <div class="col-sm-9 pdng_0">
                                                <input type="text" id="phone" name="phone" placeholder="Ex.9879879870" onblur="check_phn(this.value)" value="<?php echo $query[0]['customer_mobile']; ?>" class="form-control"/>
                                            </div>
                                            <span style="color:red;" id="phone_error"></span>
                                            <span style="color:red;" id="phone_error1"></span>
                                            <input type="hidden" id="phone_check" value="0"/>
                                        </div>
                                        <div class="form-group col-sm-12  pdng_0"">
                                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Email :</label>
                                            <div class="col-sm-9 pdng_0">
                                                <input type="text" id="email" class="form-control" name="email" value="<?php echo $query[0]['customer_email']; ?>"/> 
                                            </div>
                                            <span style="color:red;" id="email_error"></span>
                                            <span style="color:red;" id="email_error1"></span>
                                            <input type="hidden" id="email_check" value="0"/>
                                        </div>

                                        <script>
                                            cust_id = "";
                                            function check_phn(val) {
                                                $("#phone_error1").html("");
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
                                        <div class="form-group col-sm-12  pdng_0"">
                                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Gender :</label>
                                            <div class="col-sm-9 pdng_0">
                                                <select name="gender" id="gender" class="form-control">
                                                    <option value="">--Select--</option>
                                                    <option value="male" <?php
                                                    if ($query[0]['customer_gender'] == 'male') {
                                                        echo "selected";
                                                    }
                                                    ?>>Male</option>
                                                    <option value="female" <?php
                                                    if ($query[0]['customer_gender'] == 'female') {
                                                        echo "selected";
                                                    }
                                                    ?>>Female</option>
                                                </select> 
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-group col-sm-12  pdng_0"">
                                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Test city :<span style="color:red;">*</span></label> 
                                            <div class="col-sm-9 pdng_0">
                                                <select name="test_city" id="test_city" onchange="get_test(this);" class="form-control">
                                                    <?php
                                                    foreach ($test_cities as $ct) {
                                                        echo '<option value="' . $ct['id'] . '"';
                                                        if ($query[0]['customer_testcity'] == $ct['id']) {
                                                            echo 'selected';
                                                        }
                                                        echo '>' . ucwords($ct['name']) . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <span style="color:red;" id="test_city_error"></span>
                                        </div>
                                        <div class="form-group col-sm-12  pdng_0"">
                                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Address :</label> 
                                            <div class="col-sm-9 pdng_0">
                                                <textarea id="address" name="address" class="form-control"><?php echo $query[0]['customer_address']; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-12  pdng_0">
                                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Referral By :</label>
                                            <div class="col-sm-9 pdng_0">
                                                <select name="referral_by" id="referral_by" class="chosen">
                                                    <option value="">--Select--</option>
                                                    <?php
                                                    foreach ($referral_list as $referral) {
                                                        echo '<option value="' . $referral['id'] . '"';
                                                        if ($query[0]['referral_by'] == $referral['id']) {
                                                            echo 'selected';
                                                        }
                                                        echo '>' . ucwords($referral['full_name']) . '-' . $referral['mobile'] . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-12  pdng_0">
                                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Source :</label>
                                            <div class="col-sm-9 pdng_0">
                                                <select name="source" id="source" class="chosen">
                                                    <option value="">--Select--</option>
                                                    <?php
                                                    foreach ($source_list as $source) {
                                                        echo '<option value="' . $source['id'] . '"';
                                                        if ($query[0]['source'] == $source['id']) {
                                                            echo 'selected';
                                                        }
                                                        echo '>' . ucwords($source['name']) . '</option>';
                                                    }
                                                    ?>
                                                </select>
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
                                </div>
                                <div class="box-body">
                                    <div class="col-md-12" id="all_packages">
<!--                                        <button class="btn btn-primary pull-right" id="show_test_btn" onclick="show_test_model1();" type="button" style="margin-right:20px"><i class="fa fa-plus-square" style="font-size:20px;"></i> Add Test/Package</button> 
                                        <span id="loader_div" style="display:none;" class="pull-right"><img src="<?= base_url(); ?>upload/opc-ajax-loader.gif" style="height:28px;margin-right:10px"> </span> -->
                                        <div class="col-md-12">
                                            <div class="col-md-12">
                                                <div id="search_test">
                                                    <?php
                                                    echo '<select class="chosen-select" data-live-search="true" id="test" data-placeholder="Select Test" onchange="get_test_price();">
			<option value="">--Select Test--</option>';
                                                    $tests = explode(",", $query[0]['book_test_id']);
                                                    foreach ($test as $ts) {
                                                        if (!in_array($ts['id'], $tests)) {
                                                            echo ' <option value="t-' . $ts['id'] . '"> ' . ucfirst($ts['test_name']) . ' (Rs.' . $ts['price'] . ')</option>';
                                                        }
                                                    }
                                                    $pac = explode(",", $query[0]['book_package_id']);
                                                    foreach ($package as $pk) {
                                                        if (!in_array($pk['id'], $pac)) {
                                                            echo ' <option value="p-' . $pk['id'] . '"> ' . ucfirst($pk['title']) . ' (Rs.' . $pk['d_price1'] . ')</option>';
                                                        }
                                                    }
                                                    echo '</select>';
                                                    ?>
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
                                                $tests = explode(",", $query[0]['book_test_id']);
                                                foreach ($test as $ts1) {
                                                    if (in_array($ts1['id'], $tests)) {
                                                        ?>
                                                        <tr id="tr_<?= $cnt ?>">
                                                            <td><?= $ts1["test_name"]; ?><input type="hidden" class="hidden_test" name="test[]" value="t-<?= $ts1["id"]; ?>"/></td>
                                                            <td>Rs.<?= $ts1["price"]; ?><textarea style="display:none;" name="price[]"><?= $ts1["price"]; ?></textarea></td>
                                                            <td><a href="javascript:void(0);" onclick="delete_city_price('<?= $cnt ?>', '<?= $ts1["price"]; ?>', '<?= $ts1["test_name"]; ?>', '<?= "t-" . $ts1["id"]; ?>')"> Delete</a></td>
                                                        </tr>
                                                        <?php
                                                        $cnt++;
                                                    }
                                                }
                                                ?>
                                                <?php
                                                $pac = explode(",", $query[0]['book_package_id']);
                                                foreach ($package as $ts1) {
                                                    if (in_array($ts1['id'], $pac)) {
                                                        ?>
                                                        <tr id="tr_<?= $cnt ?>">
                                                            <td><a a href="#" data-toggle="modal" data-target="#myModal_view" onclick="show_details(<?php echo $ts1['id']; ?>);"><?= $ts1["title"]; ?><input type="hidden" class="hidden_test" name="test[]" value="p-<?= $ts1["id"]; ?>"/></a></td>
                                                            <td>Rs.<?= $ts1["d_price1"]; ?><textarea style="display:none;" name="price[]"><?= $ts1["d_price1"]; ?></textarea></td>
                                                            <td><a href="javascript:void(0);" onclick="delete_city_price('<?= $cnt ?>', '<?= $ts1["d_price1"]; ?>, '<?= $ts1["title"]; ?>', '<?= "p-" . $ts1["id"]; ?>'')"> Delete</a></td> 
                                                        </tr>
                                                        <?php
                                                        $cnt++;
                                                    }
                                                }
                                                ?>
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
                                                            <td><input type="text" onkeyup="get_discount_price(this.value);" value="<?php echo $query[0]['payment_discount']; ?>" name="discount" id="discount" class="form-control"/></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Payable Amount: Rs.</th>
                                                            <td><div id="payable_div"><input type="text" name="payable" id="payable_val" value="<?php echo $query[0]['payable_amount']; ?>" readonly="" class="form-control"/></div></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Total Amount: Rs. </th>
                                                            <th><div id="total_id_div"><input type="text" name="total_amount" id="total_id" value="<?php echo $query[0]['total_amount']; ?>" readonly="" class="form-control"/></div></th>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <label for="exampleInputFile">Note :</label> <textarea class="form-control" name="note" id="note"><?php echo $query[0]['booktest_note']; ?></textarea>
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
                                                        $("#show_number_print").show();

                                                    } else {
                                                        $("#number_print1").html("New Incoming Call Number <b>" + d.number +
                                                                ".</b>");
                                                        $('#phone').val(d.number);
                                                        $("#show_number_print1").show();
                                                    }

                                                } else {
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

                                        if (val.value.trim()) {
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
                                        } else {
                                            $("#name").val();
                                            $("#phone").val();
                                            $("#email").val();
                                            var elements = document.getElementById("gender").options;
                                            for (var i = 0; i < elements.length; i++) {
                                                elements[i].removeAttribute("selected");
                                                elements[i].selected = false;
                                            }
                                            $("#address").val();
                                        }
                                    }
                                    function submit_type1(val) {
                                        var cnt = 0;
                                        var name = $("#name").val();
                                        var email = $("#email").val();
                                        var phone = $("#phone").val();
                                        var test_city = $("#test_city").val();
                                        $("#name_error").html("");
                                        $("#email_error").html("");
                                        $("#phone_error").html("");
                                        $("#test_city_error").html("");
                                        if (name == '') {
                                            cnt = cnt + 1;
                                            $("#name_error").html("Required");
                                        }
                                        if (test_city == '') {
                                            cnt = cnt + 1;
                                            $("#test_city_error").html("Required");
                                        }
                                        if (checkmobile(phone) == false) {
                                            cnt = cnt + 1;
                                            $("#phone_error").html("Invalid");
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
                                                        <option value="<?= $phlebo["id"] ?>" <?php
                                                        if ($query[0]['phlebo'] == $phlebo['id']) {
                                                            echo "selected";
                                                        }
                                                        ?>><?= ucfirst($phlebo["name"]) . "-" . $phlebo["mobile"]; ?></option>
                                                            <?php } ?>
                                                </select> 
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-12  pdng_0">
                                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Date : </label>
                                            <div class="col-sm-9 pdng_0">
                                                <input type="text" id="date" name="phlebo_date" value="<?php
                                                if ($query[0]['phlebo_date'] != '') {
                                                    echo $query[0]['phlebo_date'];
                                                }
                                                ?>" class="form-control datepicker-input" onchange="get_time(this.value);"/>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-12  pdng_0">
                                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Time :</label>
                                            <div class="col-sm-9 pdng_0">
                                                <select id="time_slot"  name="phlebo_time" class="chosen">
                                                    <option value="">--Select--</option>
                                                </select>
                                                <input type="hidden" id="get_time1" value="<?php
                                                if ($query[0]['phlebo_time'] != '') {
                                                    echo $query[0]['phlebo_time'];
                                                }
                                                ?>">
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-12  pdng_0" style="display: none;">
                                            <div class="col-sm-9 pdng_0" style="float:right;">
                                                <input type="checkbox" id="notify" name="notify" value="1" <?php
                                                       if ($query[0]['notify'] == 1) {
                                                           echo "checked";
                                                       }
                                                       ?>> Notify Customer
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </section>
                        <?php echo form_close(); ?>
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
                                                        console.log(prc);
                                                        var pm = skillsSelect.value;
                                                        var explode = pm.split('-');
                                                        if (explode[0] == 'p') {
                                                            show_details(explode[1]);
                                                            var clic = "'"+explode[1]+"'";
                                                            $("#city_wiae_price").append('<tr id="tr_' + $city_cnt + '"><td><a href="#" data-toggle="modal" data-target="#myModal_view" onclick="show_details('+clic+');">' + prc[0] + '</a></td><td>Rs.' + prc1[0] + '</td><td><a href="javascript:void(0);" onclick="delete_city_price(\'' + $city_cnt + '\',\'' + prc1[0] + '\',\'' + prc[0] + '\',\'' + skillsSelect.value + '\')">Delete</a></td></tr>');
                                                        } else {
                                                            $("#city_wiae_price").append('<tr id="tr_' + $city_cnt + '"><td>' + prc[0] + '</td><td>Rs.' + prc1[0] + '</td><td><a href="javascript:void(0);" onclick="delete_city_price(\'' + $city_cnt + '\',\'' + prc1[0] + '\',\'' + prc[0] + '\',\'' + skillsSelect.value + '\')">Delete</a></td></tr>');
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
                        <!--                            Model start
                                                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                    <h4 class="modal-title" id="exampleModalLabel">Add Test/Package</h4>
                                                                </div>
                                                                <div class="modal-body">
                        <?php /*
                          <div class="form-group">
                          <label for="recipient-name" class="control-label">City:</label>
                          <br>
                          <select class="selectpicker" data-live-search="true" id="cityget">
                          <option value="">--Select--</option>
                          <?php foreach ($city as $ci) { ?>
                          <option value="<?= $ci['id']; ?>"><?= $ci['name']; ?></option>
                          <?php } ?>
                          </select>
                          <br><span style="color:red;" id="city_error"></span>
                          </div> */ ?>
                                                                    <div class="form-group">
                                                                        <label for="recipient-name" class="control-label">Test/Package:</label>
                                                                        <br>
                                                                        <div id="city_wise_test">
                                                                            <select class="chosen-select" data-live-search="true" id="test" data-placeholder="Select Test">
                                                                                <option value="">--Select Test--</option>
                        <?php foreach ($test as $ts) { ?>
                                                                                                        <option value="t-<?php echo $ts['id']; ?>" <?php ?>> <?php echo ucfirst($ts['test_name']); ?> (Rs.<?php echo $ts['price']; ?>)</option>
                        <?php } ?>
                        <?php foreach ($package as $p_key) { ?>
                                                                                                        <option value="p-<?php echo $p_key['id']; ?>" <?php ?>> <?php echo ucfirst($p_key['title']); ?> (Rs.<?php echo $p_key['d_price']; ?>)</option>
                        <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                        <br><span style="color:red;" id="test_error"></span>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                    <button type="button" class="btn btn-primary" onclick="get_test_price();">Add</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    Model end-->
                    </div><!-- /.tab-pane -->
                </div><!-- /.tab-content -->
        </div><!-- nav-tabs-custom -->
    </div>
    <!-- /.row -->
</section>
<script src="<?php echo base_url(); ?>plugins/timepick/js/timepicki.js"></script>
<script>
                                                    $('#time').timepicki();
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
<script  type="text/javascript">
                                                    $(function () {

                                                        $('.chosen-select').chosen();

                                                    });

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
        var total_amount = $('#total_id').val();
        var note = $('#note').val();
        $.ajax({
            url: "<?php echo base_url(); ?>Admin/get_telecaller_remain/",
            type: "POST",
            data: {cid: customer_fk, cname: customer_name, cmobile: customer_mobile, cemail: customer_email, cgender: customer_gender, ctestcity: customer_city, caddress: customer_address, cbooktest: customer_test, cdis: pay_discount, cpayableamo: payable_amount, ctotalamo: total_amount, cnote: note},
            error: function (jqXHR, error, code) {
            },
            success: function (data) {
                if (data != 0) {
                    $("#t_body").empty();
                    $('#existingCustomer12').val('').trigger('chosen:updated');
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
        var time = $('#get_time1').val();
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
                            $('#time_slot').append('<option value="' + json_data.data[i].time_slot_fk + '" ' + (time == json_data.data[i].time_slot_fk ? 'selected' : '') + '>' + json_data.data[i].start_time + ' TO ' + json_data.data[i].end_time + ' (Available)</option>').trigger("chosen:updated");
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
    var date = $('#date').val();
    get_time(date);
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