<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="http://cdn.rawgit.com/davidstutz/bootstrap-multiselect/master/dist/css/bootstrap-multiselect.css"
      rel="stylesheet" type="text/css" />
<script src="http://cdn.rawgit.com/davidstutz/bootstrap-multiselect/master/dist/js/bootstrap-multiselect.js"
type="text/javascript"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />

<style>
.jobdtl_taly_addtst_popup .btn-group.bootstrap-select {width: 100% !important;}
.jobdtl_taly_addtst_popup .btn-group.bootstrap-select .dropdown-menu {width: 100%; overflow-x: hidden;}
.jobdtl_taly_addtst_popup .btn-group.bootstrap-select .dropdown-menu ul.dropdown-menu.inner.selectpicker li a {white-space: normal;}
.pdng_0 {padding: 0;}
.tale_full_div {width: 100%; float: left;}
.tele_job_adrs_note {height: 108px !important; resize: none;}
.modal-backdrop {z-index: -1;}
</style>

<!-- Page Heading -->
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- Custom Tabs -->
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="javascript:void(0);" onclick="window.location = '<?= base_url(); ?>Admin/Telecaller'" data-toggle="tab">All Jobs(Tests) <span id="pending_count_1" class="label label-danger">0</span> </a></li>
                    <li><a href="javascript:void(0);" onclick="window.location = '<?= base_url(); ?>Admin/TelecallerPriscription'" data-toggle="tab">Prescription <span class="label label-danger"><?= count($unread); ?></span></a></li>
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

                                            <h3 class="box-title">Customer Details</h3>
                                        </div>

                                        <div class="box-body">
                                            <?php echo form_open("Admin/job_details/" . $cid, array("role" => "form", "method" => "POST", "id" => "user_form")); ?>
                                            <input type="hidden" name="cust_fk" value="<?= $query[0]['custid']; ?>"/>
                                            <div id="hidden_test"></div>
                                            <div class="col-md-12 pdng_0">
												<div class="col-md-4">
													<div class="form-group">
                                                        <label for="exampleInputFile">Customer Name : </label> <?php echo ucfirst($query[0]['full_name']); ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleInputFile">Mobile : </label> <?php echo ucwords($query[0]['mobile']); ?>
                                                    </div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
                                                        <label for="exampleInputFile">Total Amount(Rs.) : </label> <?php echo $query[0]['price']; ?>  
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleInputFile">Payable Amount(Rs.) : </label> <?php
                                                        if ($query[0]['payable_amount'] == "") {
                                                            echo "0";
                                                        } else {
                                                            echo $query[0]['payable_amount'];
                                                        }
                                                        ?>  
                                                    </div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
                                                        <label for="exampleInputFile">Payment Type : </label> <?php echo $query[0]['payment_type']; ?>  
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleInputFile">Blood Sample Collation Status : </label> <?php
                                                        if ($query[0]['sample_collection'] == "1") {
                                                            echo "Yes";
                                                        } else {
                                                            echo "No";
                                                        }
                                                        ?>  
                                                    </div>
													<div class="form-group">
                                                        <label for="exampleInputFile">Job Status : </label>   <?php
                                                        if ($query[0]['status'] == 1) {
                                                            echo "<span class='label label-danger'>Waiting For Approval</span>";
                                                        }
                                                        ?>
                                                        <?php
                                                        if ($query[0]['status'] == 5) {
                                                            echo "<span class='label label-warning'>Approval</span>";
                                                        }
                                                        ?>
                                                        <?php
                                                        if ($query[0]['status'] == 6) {
                                                            echo "<span class='label label-warning'>Sample Collected</span>";
                                                        }
                                                        ?>
                                                        <?php
                                                        if ($query[0]['status'] == 3) {
                                                            echo "<span class='label label-warning'>Processing</span>";
                                                        }
                                                        ?>
                                                        <?php
                                                        if ($query[0]['status'] == 2) {
                                                            echo "<span class='label label-success'>Completed</span>";
                                                        }
                                                        ?>
                                                    </div>
												</div>
												<div class="col-sm-12 pdng_0">
													<div class="col-sm-4">
														<?php if ($query[0]['pic'] != NULL) { ?>
															<div class="form-group">
																<label for="exampleInputFile">Profile : </label><br>
																<?php if ($query[0]['password'] == NULL) { ?>
																	<img src="<?php echo $query[0]['pic']; ?>" class="img-circle" style="height: auto;max-width: 130px;min-width: 80px;width: auto;"/>
																<?php } else { ?>
																	<img class="img-circle" src="<?php echo base_url(); ?>upload/<?php echo $query[0]['pic']; ?>" style="height: auto;max-width: 130px;min-width: 80px;width: auto;"/>
																<?php } ?>

															</div>
														<?php } ?>
														<div class="form-group">
															<label for="exampleInputFile">Email :</label><input type="text" class="form-control" name="email" onblur="check_email(this.value);" value="<?php echo ucwords($query[0]['email']); ?>"/> 
															<span id="email_error" style="color:red;"></span>
															<input type="hidden" value="0" id="email_check"/>
														</div>
														<Script>
															function check_email(val) {
																$("#email_error").html("");
																if (checkemail(val) == true) {
																	$.ajax({
																		url: "<?php echo base_url(); ?>Admin/check_email",
																		type: 'post',
																		data: {email: val,cust_id:'<?= $query[0]['custid']; ?>'},
																		success: function (data) {
																			if(data.trim()>0){
																				$("#email_error").html("This email address already used, Try different.");
																			}
																			$("#email_check").val(data.trim());
																		}
																	});
																} else {
																	$("#email_error").html("Invalid email.");
																}
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
													</div>
													<div class="col-sm-4">
														<div class="form-group">
															<label for="exampleInputFile">City :</label>
															<select name="city" class="form-control">
																<option value="">--Select--</option>
																<?php
																foreach ($city as $ct) {
																	echo '<option  value="' . $ct['id'] . '"';
																	if ($ct['id'] == $query[0]['city']) {

																		echo "selected";
																	}
																	echo '>' . ucwords($ct['city_name']) . '</option>';
																}
																?>
															</select>
														</div>
													</div>
													<div class="col-sm-4">
														<div class="form-group">
															<label for="exampleInputFile">Gender :</label> 
															<select name="gender" class="form-control">
																<option value="">--Select--</option>
																<option <?php
																if (ucfirst($query[0]['gender']) == 'Male') {
																	echo "Selected";
																}
																?>>Male</option>
																<option <?php
																if (ucfirst($query[0]['gender']) == 'Female') {
																	echo "Selected";
																}
																?>>Female</option>
															</select> 
														</div>
													</div>
												</div>
												<div class="col-sm-12 pdng_0">
													<div class="col-sm-4">
														<div class="tale_full_div">
															<div class="form-group">
																<label for="exampleInputFile">Test city :</label> 
																<select name="test_city" class="form-control" onchange="get_test(this);">
																	<?php
																	foreach ($test_cities as $ct) {
																		echo '<option value="' . $ct['id'] . '"';
																		if ($ct['id'] == $query[0]['test_city']) {

																			echo "selected";
																		}
																		echo '>' . ucwords($ct['name']) . '</option>';
																	}
																	?>
																</select>
															</div>
														</div>
														<div class="tale_full_div">
															<div class="form-group">
																<label for="exampleInputFile">State :</label> 
																<select name="state" class="form-control">
																	<option value="">--Select--</option>
																	<?php
																	foreach ($state as $st) {
																		echo '<option value="' . $st['id'] . '" ';
																		if ($st['id'] == $query[0]['state']) {

																			echo "selected";
																		}
																		echo '>' . ucwords($st['state_name']) . '</option>';
																	}
																	?>
																</select>
															</div>
														</div>
													</div>
													<div class="col-sm-4">
														<div class="form-group">
															<label for="exampleInputFile">Address :</label> 
															<textarea name="address" class="form-control tele_job_adrs_note"><?php echo ucwords($query[0]['address']); ?></textarea>
														</div>
													</div>
													<div class="col-sm-4">
														<label for="exampleInputFile">Note :</label> 
														<textarea class="form-control tele_job_adrs_note" name="note"><?php echo $query[0]['note']; ?></textarea>
													</div>
												</div>

                                            </div>

                                        </div>
                                    </div>
                                    <div class="box box-primary">
                                        <div class="box-header">
                                            <h3 class="box-title">Booked Information</h3>
                                        </div>
                                        <div class="box-body">
                                            <div class="col-sm-12 pdng_0" id="all_packages">
                                                <button class="btn btn-primary pull-right" onclick="$('#exampleModal').modal('show');" type="button" style="margin-right:20px"><i class="fa fa-plus-square" style="font-size:20px;"></i>   Add Test</button>
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
                                                        //$pids = array();
                                                        $cnt = 0;
                                                        $tst_price = 0;
                                                        foreach ($test_info as $ts1) {
                                                            //array_push($pids, $ts1['test_id']);
                                                            ?>
                                                            <tr id="tr_<?= $cnt ?>">
                                                                <td><?= $ts1[0]["test_name"]; ?><input type="hidden" class="hidden_test" name="test[]" value="t-<?= $ts1[0]["id"]; ?>"/></td>
                                                                <td>Rs.<?= $ts1[0]["price"]; ?><textarea style="display:none;" name="price[]"><?= $ts1[0]["price"]; ?></textarea></td>
                                                                <td><a href="javascript:void(0);" onclick="delete_city_price('<?= $cnt ?>','<?= $ts1[0]["price"]; ?>')"> Delete</a></td>
                                                            </tr>
                                                            <?php
                                                            $cnt++;
                                                            $tst_price = $tst_price + $ts1[0]["price"];
                                                        }
                                                        ?>
                                                        <?php
                                                        foreach ($package_info as $ts1) {
                                                            //array_push($pids, $ts1['test_id']);
                                                            ?>
                                                            <tr id="tr_<?= $cnt ?>">
                                                                <td><?= $ts1[0]["title"]; ?><input type="hidden" class="hidden_test" name="test[]" value="p-<?= $ts1[0]["id"]; ?>"/></td>
                                                                <td>Rs.<?= $ts1[0]["d_price"]; ?><textarea style="display:none;" name="price[]"><?= $ts1[0]["d_price"]; ?></textarea></td>
                                                                <td><a href="javascript:void(0);" onclick="delete_city_price('<?= $cnt ?>','<?= $ts1[0]["d_price"]; ?>')"> Delete</a></td> 
                                                            </tr>
                                                            <?php
                                                            $cnt++;
                                                        }
                                                        ?>

                                                    </tbody>
                                                </table>
                                                <span style="color:red;" id="test_error"></span>
                                                <br>
                                                <div class="col-sm-12 pdng_0">
                                                    <div class="col-xs-12 col-md-6" style="padding:0">
                                                        <p class="lead">Amount</p>
                                                        <div class="table-responsive">
                                                            <table class="table">
                                                                <tr>
                                                                    <th>Discount(%) :</th>
                                                                    <td><input type="text" onkeyup="get_discount_price(this.value);" value="0" name="discount" id="discount" class="form-control"/></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Payable Amount :</th>
                                                                    <td><div id="payable_div"><input type="text" name="payable" id="payable" value="<?= $tst_price; ?>" readonly="" class="form-control"/></div></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Total Amount: Rs. </th>
                                                                    <th><div id="total_id_div"><input type="text" name="total_amount" id="total_id" value="<?= $tst_price; ?>" readonly="" class="form-control"/></div></th>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div><!-- /.col -->
                                                </div><!-- /.row -->
                                            </div><!-- /.box -->
                                        </div>
                                        <div class="box-footer">
                                            <input type="button" onclick="chck_validation();" class="btn btn-primary" value="Approve"/>
                                        </div>
                                    </div>

                                    </section>
                                    <?php echo form_close(); ?>
                                    <script>
                                        function chck_validation(){
                                            var val = $("#email_check").val();
                                            if(val==0){
                                                $("#user_form").submit();
                                            }
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
                                                        $("#payable").val("0");
                                                        $("#total_id").val("0");
                                                        $("#city_wise_test").html("");
                                                        $("#city_wise_test").html(data);
                                                    }
                                                });
                                            }
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
                                                                /*function get_test_price() {
                                                                 var test_val = $("#test").val();
                                                                 $("#test_error").html("");
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
                                                                 $("#city_wiae_price").append('<tr id="tr_' + $city_cnt + '"><td>' + prc[0] + '</td><td>Rs.' + prc1[0] + '</td><td><a href="javascript:void(0);" onclick="delete_city_price(\'' + $city_cnt + '\')">Delete</a></td></tr>');
                                                                 $("#test option[value='1']").remove();
                                                                 var old_dv_txt = $("#hidden_test").html();
                                                                 $("#hidden_test").html(old_dv_txt + '<input id="tr1_' + $city_cnt + '" type="hidden" name="test[]" value="' + skillsSelect.value + '"/>');
                                                                 $city_cnt = $city_cnt + 1;
                                                                 $("#test").val("");
                                                                 $("#desc").val("");
                                                                 $('#exampleModal').modal('hide');
                                                                 
                                                                 }*/
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
                                                                    $("#city_wiae_price").append('<tr id="tr_' + $city_cnt + '"><td>' + prc[0] + '</td><td>Rs.' + prc1[0] + '</td><td><a href="javascript:void(0);" onclick="delete_city_price(\'' + $city_cnt + '\',\''+prc1[0]+'\')">Delete</a></td></tr>');
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
                                                                function delete_city_price(id,prc) {
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
                                    </script>
                                    <!--Model start-->
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
                                                    <div class="form-group jobdtl_taly_addtst_popup">
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

        get_pending_count2();
    </script>