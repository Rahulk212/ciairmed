<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="http://cdn.rawgit.com/davidstutz/bootstrap-multiselect/master/dist/css/bootstrap-multiselect.css"
      rel="stylesheet" type="text/css" />
<script src="http://cdn.rawgit.com/davidstutz/bootstrap-multiselect/master/dist/js/bootstrap-multiselect.js"
type="text/javascript"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />

<style>
.prscrptndtl_taly_addtst_popup .btn-group.bootstrap-select {width: 100% !important;}
.prscrptndtl_taly_addtst_popup .btn-group.bootstrap-select .dropdown-menu {width: 100%; overflow-x: hidden;}
.prscrptndtl_taly_addtst_popup .btn-group.bootstrap-select .dropdown-menu ul.dropdown-menu.inner.selectpicker li a {white-space: normal;}
.modal-backdrop {z-index: -1;}
</style>

<!-- Page Heading -->
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>

<!-- Add jQuery library -->
<script type="text/javascript" src="<?php echo base_url(); ?>lib/jquery-1.10.2.min.js"></script>

<!-- Add mousewheel plugin (this is optional) -->
<script type="text/javascript" src="<?php echo base_url(); ?>lib/jquery.mousewheel.pack.js?v=3.1.3"></script>

<!-- Add fancyBox main JS and CSS files -->
<script type="text/javascript" src="<?php echo base_url(); ?>source/jquery.fancybox.pack.js?v=2.1.5"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>source/jquery.fancybox.css?v=2.1.5" media="screen" />

<!-- Add Button helper (this is optional) -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>source/helpers/jquery.fancybox-buttons.css?v=1.0.5" />
<script type="text/javascript" src="<?php echo base_url(); ?>source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>

<!-- Add Thumbnail helper (this is optional) -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" />
<script type="text/javascript" src="<?php echo base_url(); ?>source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>

<!-- Add Media helper (this is optional) -->
<script type="text/javascript" src="<?php echo base_url(); ?>source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>
<!--Nishit code start-->
<script type="text/javascript">
    var jq = $.noConflict();
    jq(document).ready(function () {
        /*
         *  Simple image gallery. Uses default settings
         */

        jq(".fancybox-effects-a").fancybox({
            'type': 'iframe',
            helpers: {
                title: {
                    type: 'outside'
                },
                overlay: {
                    speedOut: 0
                }
            }
        });
    });
</script>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- Custom Tabs -->
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li><a href="javascript:void(0);" onclick="window.location = '<?= base_url(); ?>Admin/Telecaller'" data-toggle="tab">All Jobs(Tests) <span id="pending_count_1" class="label label-danger">0</span> </a></li>
                    <li class="active"><a href="javascript:void(0);" onclick="window.location = '<?= base_url(); ?>Admin/TelecallerPriscription'" data-toggle="tab">Prescription <span class="label label-danger"><?= count($unread); ?></span></a></li>
                    <li><a href="javascript:void(0);" data-toggle="tab" onclick="window.location = '<?= base_url(); ?>Admin/TelecallerCallBooking'">On Call Booking</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1">
                        <section class="content-header">
                            <h1>
                                Job Details (<?php echo $query[0]['order_id']; ?>)
                                <small></small>
                            </h1>
                            <ol class="breadcrumb">
                                <span style="margin-right:30px; font-size:20px;"><?php echo $query[0]['date'] . "  "; ?>  </span>
                            </ol>
                        </section>
                        <section class="content">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box box-primary">
                                        <div class="widget">
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

                                        </div>
                                        <div class="box-header">
                                            <!-- form start -->
                                            <h3 class="box-title">User Details</h3>
                                        </div>

                                        <div class="box-body">
                                            <?php echo form_open("Admin/prescription_details/" . $cid, array("role" => "form", "method" => "POST", "id" => "user_form")); ?>
                                            <input type="hidden" name="prescription_fk" value="<?= $cid ?>"/>
                                            <div id="hidden_test">
                                                <?php
                                                $cnt_test = 0;
                                                foreach ($test_info as $ts1) {
                                                    ?>
                                                    <input id="tr1_<?= $cnt_test; ?>" type="hidden" name="test[]" value="t-<?= $ts1["test_id"] ?>"/>
                                                    <?php
                                                    $cnt_test++;
                                                }
                                                ?>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="col-md-5">
                                                    <?php if ($query[0]['pic'] != NULL) { ?>
                                                        <div class="form-group">
                                                            <label for="exampleInputFile">Profile :</label><br>
                                                            <?php
                                                            $check_pc = substr($query[0]['pic'], 0, 6);
                                                            if ($check_pc == "https:") {
                                                                ?>
                                                                <img src="<?php echo $query[0]['pic']; ?>" class="img-circle" style="height: auto;max-width: 130px;min-width: 80px;width: auto;"/>
                                                            <?php } else { ?>
                                                                <img class="img-circle" src="<?php echo base_url(); ?>upload/<?php echo $query[0]['pic']; ?>" style="height: auto;max-width: 130px;min-width: 80px;width: auto;"/>
                                                            <?php } ?>

                                                        </div>
                                                    <?php } ?>

                                                    <?php if (empty($user_info)) { ?>
                                                        <span class='label label-warning'>New Customer</span>
                                                        <div class="form-group">
                                                            <input type="hidden" class="" id="create_cust" name="customer" value="0"/>
                                                        </div>
                                                    <?php } else { ?>
                                                        <input type="hidden" class="" id="create_cust" name="customer" value="1"/>
                                                        <input type="hidden" class="" id="create_cust" name="userid" value="<?= $query[0]['cust_fk'] ?>"/>
                                                    <?php } ?>
                                                    <div class="form-group">
                                                        <label for="exampleInputFile">Customer Name :<span style="color:red;">*</span></label> <input type="text" id="name" name="name" value="<?php echo ucfirst($user_info[0]['full_name']); ?>" class="form-control"/>
                                                        <span style="color:red;" id="name_error"></span>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleInputFile">Mobile :<span style="color:red;">*</span></label><input type="text" id="phone" placeholder="Ex.9879879870" onblur="check_phn(this.value)" name="phone" value="<?php echo ucwords($user_info[0]['mobile']); ?>" class="form-control"/>
                                                        <span style="color:red;" id="phone_error"></span>
                                                        <span style="color:red;" id="phone_error1"></span>
                                                        <input type="hidden" id="phone_check" value="0"/>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleInputFile">Email :<span style="color:red;">*</span></label><input type="text" onblur="check_email(this.value);" id="email" class="form-control" name="email" value="<?php echo ucwords($user_info[0]['email']); ?>"/> 
                                                        <span style="color:red;" id="email_error"></span>
                                                        <span style="color:red;" id="email_error1"></span>
                                                        <input type="hidden" id="email_check" value="0"/>
                                                    </div>
                                                    <script>
                                                        function check_phn(val) {
                                                            $("#phone_error1").html("");
                                                            if (checkmobile(val) == true) {
                                                                $.ajax({
                                                                    url: "<?php echo base_url(); ?>Admin/check_phone",
                                                                    type: 'post',
                                                                    data: {phone: val, cust_id: '<?= $query[0]['cust_fk'] ?>'},
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
                                                                    data: {email: val, cust_id: '<?= $query[0]['cust_fk'] ?>'},
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
                                                    <?php if (empty($user_info)) { ?>
                                                        <div class="form-group">
                                                            <label for="exampleInputFile">Password :<span style="color:red;">*</span></label><input id="password" type="password" class="form-control" name="password" value=""/> 
                                                            <span style="color:red;" id="password_error"></span>
                                                        </div>
                                                    <?php } ?>
                                                    <div class="form-group">
                                                        <label for="exampleInputFile">Gender :</label> 
                                                        <select name="gender" class="form-control">
                                                            <option value="">--Select--</option>
                                                            <option <?php
                                                            if (ucfirst($user_info[0]['gender']) == 'Male') {
                                                                echo "Selected";
                                                            }
                                                            ?>>Male</option>
                                                            <option <?php
                                                            if (ucfirst($user_info[0]['gender']) == 'Female') {
                                                                echo "Selected";
                                                            }
                                                            ?>>Female</option>
                                                        </select> 
                                                    </div>

                                                    <!--<div class="form-group">
                                                        <label for="exampleInputFile">State :</label> 
                                                        <select name="state" class="form-control">
                                                            <option value="">--Select--</option>
                                                    <?php
                                                    foreach ($state as $st) {
                                                        echo '<option value="' . $st['id'] . '" ';
                                                        if ($st['id'] == $user_info[0]['state']) {

                                                            echo "selected";
                                                        }
                                                        echo '>' . ucwords($st['state_name']) . '</option>';
                                                    }
                                                    ?>
                                                        </select>
                        
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleInputFile">City :</label>
                                                        <select name="city" class="form-control">
                                                            <option value="">--Select--</option>
                                                    <?php
                                                    foreach ($city as $ct) {
                                                        echo '<option  value="' . $ct['id'] . '"';
                                                        if ($ct['id'] == $user_info[0]['city']) {

                                                            echo "selected";
                                                        }
                                                        echo '>' . ucwords($ct['city_name']) . '</option>';
                                                    }
                                                    ?>
                                                        </select>
                        
                                                    </div>-->
                                                    <div class="form-group">
                                                        <label for="exampleInputFile">Test city:<span style="color:red;">*</span></label> 
                                                        <select name="test_city" id="test_city" class="form-control" onchange="get_test(this);">
                                                            <option value="">--Select--</option>
                                                            <?php
                                                            foreach ($test_cities as $ct) {
                                                                echo '<option value="' . $ct['id'] . '"';
                                                                if ($ct['id'] == $query[0]['city']) {
                                                                    echo "selected";
                                                                }
                                                                echo '>' . ucwords($ct['name']) . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                        <span style="color:red;" id="test_city_error"></span>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleInputFile">Address :</label> <textarea name="address" class="form-control"><?php echo ucwords($query[0]['address']); ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <br>
                                                        <label for="exampleInputFile">Prescription :</label><br> <a class="fancybox-effects-a" title="<?= base_url(); ?>upload/<?= $query[0]['image']; ?>" href="<?= base_url(); ?>upload/<?= $query[0]['image']; ?>">

                                                            <?php
                                                            $check_file_type = explode(".", $query[0]['image']);
                                                            $f_cnt = count($check_file_type);
                                                            $file_type = $check_file_type[$f_cnt - 1];
                                                            if (strtoupper($file_type) == "PDF") {
                                                                ?>
                                                                <i class="fa fa-file-pdf-o" style="font-size:40px;"></i>
                                                            <?php } else { ?>
                                                                <img style="height:150px;width:auto;"  src="<?= base_url(); ?>upload/<?= $query[0]['image']; ?>"/>
                                                            <?php } ?>
                                                        </a>
                                                    </div>
                                                    <div class="form-group">
                                                        <br>
                                                        <label for="exampleInputFile">Description :</label><textarea class="form-control" name="note"><?php echo $query[0]['description']; ?></textarea>
                                                    </div>
                                                    <label for="exampleInputFile">Note :</label> <textarea class="form-control" name="note"><?php echo $query[0]['note']; ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="box box-primary">
                                        <div class="box-header">
                                            <h3 class="box-title">Suggest Test</h3>
                                        </div>
                                        <div class="box-body">
                                            <div class="col-md-12" id="all_packages">
                                                <button class="btn btn-primary pull-right" onclick="show_test_model();" type="button" style="margin-right:20px"><i class="fa fa-plus-square" style="font-size:20px;"></i> Add Test</button>
                                                <table class="table table-striped" id="city_wiae_price">
                                                    <thead>
                                                        <tr>
                                                            <th>Test Name</th>
                                                            <th>Price</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="t_body">
                                                        <?php
                                                        $total_price = 0;
                                                        $cnt = 0;
                                                        foreach ($test_info as $ts1) {
                                                            //array_push($pids, $ts1['test_id']);
                                                            ?>
                                                            <tr id="tr_<?= $cnt ?>">
                                                                <td><?= $ts1["test_name"]; ?></td>
                                                                <td>Rs.<?= $ts1["price"]; ?><textarea style="display:none;" name="price[]"><?= $ts1["price"]; ?></textarea></td>
                                                                <td><a href="javascript:void(0);" onclick="delete_city_price('<?= $cnt ?>', '<?= $ts1["price"]; ?>')"> Delete</a></td>
                                                            </tr>
                                                            <?php
                                                            $total_price = $total_price + $ts1["price"];
                                                            $cnt++;
                                                        }
                                                        ?>
                                                        <?php
                                                        foreach ($package_info as $ts1) {
                                                            //array_push($pids, $ts1['test_id']);
                                                            ?>
                                                            <tr id="tr_<?= $cnt ?>">
                                                                <td><?= $ts1[0]["title"]; ?><input type="hidden" class="hidden_test" name="test[]" value="p-<?= $ts1[0]["id"]; ?>"/></td>
                                                                <td>Rs.<?= $ts1[0]["d_price"]; ?><textarea style="display:none;" name="price[]"><?= $ts1[0]["d_price"]; ?></textarea></td>
                                                                <td><a href="javascript:void(0);" onclick="delete_city_price('<?= $cnt ?>', '<?= $ts1[0]["d_price"]; ?>')"> Delete</a></td> 
                                                            </tr>
                                                            <?php
                                                            $cnt++;
                                                        }
                                                        /*if($book_test_details[0]["discount"]){
                                                        $discount = $book_test_details[0]["discount"];
                                                        }else{
                                                            $discount=0;
                                                        }*/
                                                        $discount=$n_discount;
                                                        if ($discount != '' && $discount != 0) {
                                                            $payable_amount = $total_price - ($total_price * $discount / 100);
                                                        } else {
                                                            $payable_amount = $total_price;
                                                        }
                                                        ?>

                                                    </tbody>
                                                </table>
                                                <span style="color:red;" id="test_error"></span>
                                                <br>
                                                <div class="col-xs-12">
                                                    <div class="col-xs-12 col-md-6" style="padding:0">
                                                        <p class="lead">Amount</p>
                                                        <div class="table-responsive">
                                                            <table class="table">
                                                                <tr>
                                                                    <th>Discount(%) :</th>
                                                                    <td><input type="text" onkeyup="get_discount_price(this.value);" value="<?= $discount; ?>" name="discount" id="discount" class="form-control"/></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Payable Amount :</th>
                                                                    <td><input type="text" name="payable" id="payable" value="<?= $payable_amount; ?>" readonly="" class="form-control"/></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Total Amount: Rs. </th>
                                                                    <th><input type="text" name="total_amount" id="total_id" value="<?= $total_price; ?>" readonly="" class="form-control"/></th>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div><!-- /.col -->
                                                </div><!-- /.row -->
                                            </div><!-- /.box -->
                                        </div>
                                        <input type="hidden" name="submit_type" id="submit_type" value="0"/>
                                        <script>
                                            function get_test(val) {

                                                if (val.value.trim()) {
                                                    $.ajax({
                                                        url: "<?php echo base_url(); ?>Admin/get_city_test",
                                                        type: 'post',
                                                        data: {city: val.value},
                                                        success: function (data) {
                                                            $("#t_body").html("");
                                                            $("#hidden_test").html("");
                                                            $("#hidden_test").html("");
                                                            $("#discount").html("0");
                                                            $("#payable").val("0");
                                                            $("#total_id").val("0");
                                                            $("#city_wise_test").html("");
                                                            $("#city_wise_test").html(data);
                                                        }
                                                    });
                                                }
                                            }
                                            function submit_type1(val) {
                                                var $cust = $("#create_cust").val();
                                                console.log($cust);
                                                if ($cust.trim() == '0') {
                                                    var cnt = 0;
                                                    var name = $("#name").val();
                                                    var email = $("#email").val();
                                                    var phone = $("#phone").val();
                                                    var password = $("#password").val();
                                                    var test_city = $("#test_city").val();
                                                    $("#name_error").html("");
                                                    $("#email_error").html("");
                                                    $("#phone_error").html("");
                                                    $("#password_error").html("");
                                                    $("#test_city_error").html("");
                                                    if (name == '') {
                                                        cnt = cnt + 1;
                                                        $("#name_error").html("Required");
                                                    }
                                                    if (test_city == '') {
                                                        cnt = cnt + 1;
                                                        $("#test_city_error").html("Required");
                                                    }
                                                    if (checkemail(email) == false) {
                                                        cnt = cnt + 1;
                                                        $("#email_error").html("Invalid");
                                                    }
                                                    if (checkmobile(phone) == false) {
                                                        cnt = cnt + 1;
                                                        $("#phone_error").html("Invalid");
                                                    }
                                                    if (password == '') {
                                                        cnt = cnt + 1;
                                                        $("#password_error").html("Required");
                                                    }
                                                    var val = $("#email_check").val();
                                                    if (val > 0) {
                                                        cnt = cnt + 1;
                                                    }
                                                    var val = $("#phone_check").val();
                                                    if (val > 0) {
                                                        cnt = cnt + 1;
                                                    }
                                                    if (cnt > 0) {
                                                        return false;
                                                    }
                                                }
                                                $("#submit_type").val(val);
                                                setTimeout(function () {
                                                    var val = $("#email_check").val();
                                                    if (val > 0) {
                                                        return false;
                                                    }
                                                    var val = $("#phone_check").val();
                                                    if (val > 0) {
                                                        return false;
                                                    }
                                                    $("#user_form").submit();
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
                                            
                                                <?php /* ?><input type="button" onclick="submit_type1('1');" class="btn btn-primary" value="Book Test"/><?php */ ?>
                                                <input type="button" onclick="submit_type1('0');" class="btn btn-primary" value="Suggest Test"/>
                                           
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
                                                                    $("#city_wiae_price").append('<tr id="tr_' + $city_cnt + '"><td>' + prc[0] + '</td><td>Rs.' + prc1[0] + '</td><td><a href="javascript:void(0);" onclick="delete_city_price(\'' + $city_cnt + '\',\'' + prc1[0] + '\')">Delete</a></td></tr>');
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
                                                                    $("#test").val("");
                                                                    $("#desc").val("");
                                                                    $('#exampleModal').modal('hide');

                                                                }
                                                                function delete_city_price(id, prc) {
                                                                    var tst = confirm('Are you sure?');
                                                                    if (tst == true) {
                                                                        /*Total price calculate start*/
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
                                                                            $("#payable").val(payableamount);
                                                                        } else {
                                                                            var ttl = $("#total_id").val();
                                                                            $("#payable").val(ttl);
                                                                        }
                                                                    }, 1000);
                                                                }
                                    </script>
                                    <!--Model start-->
                                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="exampleModalLabel">Add Test</h4>
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
                                                        <label for="recipient-name" class="control-label">Test:</label>
                                                        <br>
                                                        <div id="city_wise_test">
                                                            <select class="selectpicker" data-live-search="true" id="test" data-placeholder="Select Test">
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
                                    <!--Model end-->
                                </div><!-- /.tab-pane -->
                            </div><!-- /.tab-content -->
                    </div><!-- nav-tabs-custom -->
                </div><!-- /.col -->
            </div>
            <!-- /.row -->
            </section>
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
                function show_test_model() {
                    var values = $("input[name='test[]']").map(function () {
                        return $(this).val();
                    }).get();
                    var ctid = $('#test_city').val();
                    $.ajax({
                        url: "<?php echo base_url(); ?>job_master/get_test_list/",
                        type: "POST",
                        data: {ids: values, pid: <?php echo $cid; ?>, ctid: ctid},
                        error: function (jqXHR, error, code) {
                        },
                        success: function (data) {
                            $("#city_wise_test").empty();
                            $("#city_wise_test").append(data);
                            // console.log(data);
                        }
                    });
                    $('#exampleModal').modal('show');
                    console.log(values);
                }
                get_pending_count2();
            </script>
            <!--Nishit code end-->