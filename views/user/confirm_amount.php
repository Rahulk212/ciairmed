<!-- Start main-content -->
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<link href="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />
<div class="main-content">
    <!-- Section: home -->
    <style>
        .select2-selection.select2-selection--single {
            border: 1px solid #ccc;
            height: 40px;
            margin-top: 0;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered{ line-height: 38px;padding-left: 15px;}
        .select2-container--default .select2-selection--single .select2-selection__arrow{top:7px;}
		.select2-container.select2-container--default.select2-container--open{z-index:1;}
                .use_wallet_img {position: absolute;top: 14px; right: -10px;}
		.use_wallet_text{position: absolute; top: 55px;}
                @media (max-width:767px){
                    .use_wallet_img {display:none !important;}

                }
    </style>
    <section>
        <div class="container pdng_btm_30px" style="padding-top:20px">
            <div class="row bs-wizard" style="border-bottom:0;">

                <div class="col-xs-3 bs-wizard-step complete">
                    <div class="text-center bs-wizard-stepnum"><b>Test For </b></div>
                    <div class="progress"><div class="progress-bar"></div></div>
                    <a href="#" class="bs-wizard-dot"></a>
                </div>

                <div class="col-xs-3 bs-wizard-step complete"><!-- complete -->
                    <div class="text-center bs-wizard-stepnum"><b>Select Address</b></div>
                    <div class="progress"><div class="progress-bar"></div></div>
                    <a href="#" class="bs-wizard-dot"></a>
                </div>

                <div class="col-xs-3 bs-wizard-step complete"><!-- complete -->
                    <div class="text-center bs-wizard-stepnum"><b>Schedule</b></div>
                    <div class="progress"><div class="progress-bar"></div></div>
                    <a href="#" class="bs-wizard-dot"></a>
                </div>
                <div class="col-xs-3 bs-wizard-step active"><!-- active -->
                    <div class="text-center bs-wizard-stepnum">Payment</div>
                    <div class="progress"><div class="progress-bar"></div></div>
                    <a href="#" class="bs-wizard-dot"></a>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="checkbox">
                        <?php
                        if ($test_exist == 0) {
                            $checked = "style='display:none;'";
                        } else if ($wallet != 0) {
                            $checked = "checked";
                        } else if ($wallet == 0) {
                            $checked = "disabled";
                        }
                        ?>
                        <label <?php
                        if ($test_exist == 0) {
                            echo "style='display:none;'";
                        }
                        ?>><input type="checkbox"  onclick="wallet_show();" <?= $checked; ?> id="myCheck" >Use Wallet Amount (Rs <?php
                            if (isset($login_data['id'])) {
                                echo number_format($this->data['wallet_amount'], 2);
                            } else {
                                echo "0.00";
                            }
                            ?>)</label>
                    </div>
                </div>
            </div>
            <?php
            $user_Active_package = array();
            foreach ($user_active_package as $p_key) {
                if (!empty($p_key[0]["package_fk"])) {
                    $user_Active_package[] = $p_key[0]["package_fk"];
                }
            }
            ?>
            <div class="row">
                <div class="col-sm-12">
                    <div class="main_wallet" style="background:#F5F8F9
                         <div class="main_wallet_div">
                        <div class="col-sm-12 pdng_0">
                            <div class="col-md-3 col-sm-5">
                                <b class="cnfrm_amnt_title_mrgn_btm">Payment To be made</b>
                                <?php if ($price != 0) { ?>
                                    <div class="wbalance">
                                        <p>Test Price Rs.<?php echo $price; ?></p>
                                    </div>
                                <?php } ?>
                                <?php
                                $cnt = 0;
                                $r_package_price = 0;
                                $rp_msg = "";
                                foreach ($user_active_package as $p_key) {
                                    if (!empty($p_key[0]["title"])) {
                                        if ($cnt != 0) {
                                            echo ",";
                                        }
                                        $r_package_price += $p_key[0]["price"];
                                        $rp_msg .= "<b>" . $p_key[0]["title"] . " (Rs." . $p_key[0]["price"] . ")</b>";
                                        $cnt++;
                                    }
                                }
                                ?>
                                <?php if ($package_price != 0 || $is_active_package == 1) { ?>
                                    <div class="wbalance">
                                        <p>Package Price Rs.<?php echo $package_price + $r_package_price; ?></p>
                                        <?php if (!empty($rp_msg)) { ?>
                                            <small><?= $rp_msg; ?> is your active packages.</small>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                                <div class="wbalance" id="first_total" <?php
                                if ($wallet == 0) {
                                    echo "";
                                } else {
                                    echo 'style="display:none;"';
                                }
                                ?> >  
                                    <p style="font-weight:bold;">Total Rs.<?php echo $price + $package_price; ?></p>
                                </div>
                            </div>
                            <input type="hidden" id="prc1" value="<?php echo $price + $package_price; ?>"/>
                            <div class="col-sm-9 cmfrm_res_cl9_pdng" id="hide_div" style="<?php
                            if ($wallet == 0) {
                                echo "display:none";
                            }
                            ?>" >
                                <div class="col-sm-1">
                                    <span style="font-size:50px; float:left; margin-top:15px;">-</span>
                                </div>
                                <div class="col-sm-5">
                                    <b class="cnfrm_amnt_title_mrgn_btm">Your Wallet Balance</b>
                                    <?php if ($price != 0) { ?>
                                        <div class="wbalance">  
                                            <p>Rs <?php echo $wallet; ?></p>
                                        </div> 
                                    <?php } ?>
                                </div>
                                <div class="col-sm-1">
                                    <span style="font-size:50px; float:left; margin-top:15px;">=</span>
                                </div>
                                <div class="col-sm-5">
                                    <b class="cnfrm_amnt_title_mrgn_btm">Payable Amount</b>
                                    <?php if ($test_exist != 0) { ?>
                                        <div class="wbalance">  
                                            <p style="font-weight:bold;">Rs <?php echo $payamount; ?> (for test)</p>
                                        </div>
                                    <?php } ?>
                                    <?php if ($package_exist != 0) { ?>
                                        <div class="wbalance">  
                                            <p style="font-weight:bold;">Rs.<?php echo $package_price; ?> (for package)</p>
                                        </div>
                                    <?php } ?>
                                    <div class="wbalance">  
                                        <p style="font-weight:bold;">Total Rs.<?php echo $payamount + $package_price; ?></p>
                                    </div>
                                </div>
                                <input type="hidden" id="ttl_price" value="<?php echo $payamount + $package_price; ?>"/>
                            </div>

                        </div>
                        <div class="col-sm-3">
                            <select class="form-control" id="source_by" onchange="reffer_by(this.value)" name="source_by">
                                <option value="">--Select Reference--</option>
                                <?php foreach ($refrence as $key) { ?>
                                    <option value="<?= $key["id"] ?>"><?= ucwords($key["name"]); ?></option>
                                <?php } ?>
                            </select>
                            <script>
                                function reffer_by(val) {
                                    $("#hidden_source_by").val(val);
                                    $("#hidden_source_by1").val(val);
                                }
                            </script>
                            <sapn style="color:red;" id="error_source_by"></sapn>
                        </div>
                         <div class="col-sm-9 use_wallet_img" id="use_wallet_hide_div">
								<img src="<?= base_url(); ?>user_assets/images/select_wallet_amount.png">
								<div class="col-md-offset-4 col-md-6 use_wallet_text">
									<h3>Select Wallet Amount</h3>
								</div>
						</div>
                         </div>
                        <input type="hidden" id="prc2" value="<?php echo $payamount + $package_price; ?>"/>
                        <div class="col-sm-12 pdng_0">
                            <div class="col-sm-12">


                                <form id="frm" role="form" action="<?php echo base_url(); ?>user_test_master/payumoney/<?php echo $testid; ?>/wallet/<?= $booking_info; ?>"  method="post" enctype="multipart/form-data">
                                    <?php if ($test_exist != 0) { ?>
                                        <input type="hidden" name="amount" value="<?php echo $payamount + $package_price; ?>" id="frm_wallet" />
                                    <?php } else { ?>
                                        <input type="hidden" name="amount" value="<?php echo $payamount + $package_price; ?>" />
                                    <?php } ?>
                                    <input type="hidden" name="source_by" id="hidden_source_by" value=""/>
                                    <input type="hidden" name="ptype" value="wallet" id="frm_type" />
                                    <div class="col-md-6 col-sm-12 col-xs-12" style="margin-top:20px; float: left;">
                                        <div id="online_payment_btn" <?php
                                        $tttl_prc = $price + $package_price;
                                        if ($tttl_prc == 0) {
                                            echo "style='display:none;'";
                                        }
                                        ?>>
                                            <button type="button" onclick="check_user_details('1');" class="cnfrm_btn_noback pay_blood_clctn pull-left"><i class="fa fa-credit-card"></i> <span>Online Payment</span></button>
                                            <span class="cnfrm_amnt_spn col-xs-1">OR</span>
                                        </div>
                                        <button type="button" onclick="check_user_details('2');" class="cnfrm_btn_noback pay_blood_clctn pull-left"><i class="fa fa-handshake-o"></i> <span id="pay_during_blood_c_btn"><?php
                                                $tttl_prc = $price + $package_price;
                                                if ($tttl_prc == 0) {
                                                    echo "Book";
                                                } else {
                                                    echo "Pay During Blood Collection";
                                                }
                                                ?></span></button>
<!--<span class="cnfrm_amnt_spn col-xs-1">OR</span>-->
<!--<button class="cnfrm_btn_noback col-xs-5 col-sm-4 pdng_0" id="crop_model" data-target="#myModal1" data-keyboard="false" data-backdrop="static" data-toggle="modal" type="button"><img class="cnfrm_two_img" src="<?php echo base_url(); ?>user_assets/images/new/cash_on_dlvry.png"/></button>-->
                                    </div>
                                    <!--<div class="col-sm-6 pdng_0" style="margin-top:20px; float: left;">                              
                                    </div>-->
                                    <div role="dialog" id="myModal2" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content srch_popup_full">
                                                <div class="modal-header srch_popup_full srch_head_clr">
                                                    <button type="button" class="close pay_blood_close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title clr_fff">Online Payment Details</h4>
                                                </div>
                                                <div class="modal-body srch_popup_full">
                                                    <?php
                                                    $wallet_price = 0;
                                                    if ($price < $wallet) {
                                                        $wallet_price = $price;
                                                    } else {
                                                        $wallet_price = $wallet;
                                                    }
                                                    ?>
                                                    <input type="hidden" id="method_id1" name="method" value="<?php
                                                    if ($wallet != 0) {
                                                        echo "yes";
                                                    }
                                                    ?> <?php
                                                           if ($wallet == 0) {
                                                               echo "no";
                                                           }
                                                    ?>"/>
                                                    <div class="form-group">
                                                        <label for="recipient-name" class="form-control-label">Mobile:</label>
                                                        <div class="input-group">
                                                            <span class="input-group-addon pkgdtl_spn_91">+91</span>
                                                            <input type="text" class="form-control" id="mobile_id1" value="<?= $user_info[0]["mobile"]; ?>" disabled="" name="mobile1">
                                                        </div>
                                                        <span style="color:red" id="mobile1_error"><?= form_error('mobile'); ?></span>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="recipient-name" class="form-control-label">Birth date:</label>
                                                        <div class="input-group">
                                                            <input type="text" class="datepicker form-control" id="dob1" value="<?= $user_info[0]["dob"]; ?>" name="dob">
                                                        </div>
                                                        <span style="color:red" id="dob1_error"><?= form_error('dob'); ?></span>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="recipient-name" class="form-control-label">Gender:</label>
                                                        <div class="input-group">
                                                            Male:-<input type="radio" name="gender1" value="male" id="male1" <?php
                                                           if (strtoupper($user_info[0]["gender"]) == 'MALE') {
                                                               echo "checked";
                                                           }
                                                    ?>>
                                                            Female:-<input type="radio" name="gender1" value="female" id="female1" <?php
                                                            if (strtoupper($user_info[0]["gender"]) == 'FEMALE') {
                                                                echo "checked";
                                                            }
                                                    ?>>
                                                        </div>
                                                        <span style="color:red" id="gender1_error"><?= form_error('dob'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="modal-footer uplod_prec_full">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    <button type="button" class="btn btn-dark btn-theme-colored btn-flat pull-right" id="note_save_btn" onclick="Validation1();">Submit</button>
                                                    <span id="loader_div2" style="display:none;"><img src="<?= base_url(); ?>upload/opc-ajax-loader.gif" style="height:28px;margin-left:10px"></span>
                                                </div>
                                            </div>
                                            <script>
                                                function Validation1() {
                                                    var cnt = 0;
                                                    var mobile_id = $("#mobile_id1").val();
                                                    mobile_id = mobile_id.trim();
                                                    if (mobile_id == '') {
                                                        cnt = cnt + 1;
                                                        $("#mobile1_error").html('Mobile is required.');
                                                    }
                                                    var dob = $("#dob1").val();
                                                    dob = dob.trim();
                                                    if (dob == '') {
                                                        cnt = cnt + 1;
                                                        $("#dob1_error").html('Birth date is required.');
                                                    }
                                                    if ($("input[name='gender1']:checked").length > 0) {
                                                        var typ = $("input[name='gender1']:checked").val();
                                                    } else {
                                                        cnt = cnt + 1;
                                                        $("#gender1_error").html('Gender is required.');
                                                    }
                                                    if (cnt == 0) {
                                                        $.ajax({
                                                            url: "<?php echo base_url(); ?>User_test_master/save_user_data",
                                                            type: 'post',
                                                            data: {mobile1: mobile_id, dob: dob, gender1: typ},
                                                            beforeSend: function () {
                                                                $("#loader_div2").attr("style", "");
                                                                $("#note_save_btn").attr("disabled", "disabled");
                                                            },
                                                            success: function (data) {
                                                                $("#frm").submit();
                                                            }, complete: function () {
                                                                $("#loader_div2").attr("style", "display:none;");
                                                                $("#note_save_btn").removeAttr("disabled");
                                                            }
                                                        });
                                                    }
                                                }
                                            </script>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div role="dialog" id="myModal1" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content srch_popup_full">
                        <div class="modal-header srch_popup_full srch_head_clr">
                            <button type="button" class="close pay_blood_close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title clr_fff">Cash On Delivery Details</h4>
                        </div>
                        <div class="modal-body srch_popup_full">
                            <?php
                            $wallet_price = 0;
                            if ($price < $wallet) {
                                $wallet_price = $price;
                            } else {
                                $wallet_price = $wallet;
                            }
                            ?>
<?php echo form_open("User_test_master/cash_on_delivery/" . $booking_info, array("role" => "form", "method" => "POST", "id" => "cash_on_delivery_id")); ?>
                            <input type="hidden" name="test_ids" value="<?= $test_ids ?>"/>
                            <input type="hidden" name="price" value="<?php echo $payamount + $package_price; ?>"/>
                            <input type="hidden" name="wallet_price" value="<?php echo $wallet_price; ?>"/>
                            <input type="hidden" name="source_by" id="hidden_source_by1" value=""/>
                            <input type="hidden" id="method_id" name="method" value="<?php
                            if ($wallet != 0) {
                                echo "yes";
                            }
                            ?> <?php
                                   if ($wallet == 0) {
                                       echo "no";
                                   }
                            ?>"/>
                            <div class="form-group">
                                <label for="recipient-name" class="form-control-label">Mobile:</label>
                                <div class="input-group">
                                    <span class="input-group-addon pkgdtl_spn_91">+91</span>
                                    <input type="text" class="form-control" id="mobile_id" value="<?= $user_info[0]["mobile"]; ?>" disabled="" name="mobile1">
                                    <input type="hidden" name="mobile" value="<?= $user_info[0]["mobile"]; ?>"/>
                                    <input type="hidden" name="test_city" value="<?= $test_city_session; ?>"/>
                                </div>
                                <span style="color:red" id="mobile_error"><?= form_error('mobile'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="form-control-label">Birth date:</label>
                                <div class="input-group">
                                    <input type="text" class="datepicker form-control" id="dob" value="<?= $user_info[0]["dob"]; ?>" name="dob">
                                </div>
                                <span style="color:red" id="dob_error"><?= form_error('dob'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="form-control-label">Gender:</label>
                                <div class="input-group">
                                    Male:-<input type="radio" name="gender" value="male" id="male" <?php
                                   if (strtoupper($user_info[0]["gender"]) == 'MALE') {
                                       echo "checked";
                                   }
                            ?>>
                                    Female:-<input type="radio" name="gender" value="female" id="female" <?php
                                    if (strtoupper($user_info[0]["gender"]) == 'FEMALE') {
                                        echo "checked";
                                    }
                            ?>>
                                </div>
                                <span style="color:red" id="gender_error"><?= form_error('dob'); ?></span>
                            </div>
                            </form>
                        </div>
                        <div class="modal-footer uplod_prec_full">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-dark btn-theme-colored btn-flat pull-right" id="cash_on_delivery_sub" onclick="Validation();">Submit</button>
                        </div>
                    </div>
                    <script>
                        function Validation() {
                            var cnt = 0;
                            var mobile_id = $("#mobile_id").val();
                            mobile_id = mobile_id.trim();
                            if (mobile_id == '') {
                                cnt = cnt + 1;
                                $("#mobile_error").html('Mobile is required.');
                            }
                            var dob = $("#dob").val();
                            dob = dob.trim();
                            if (dob == '') {
                                cnt = cnt + 1;
                                $("#dob_error").html('Birth date is required.');
                            }
                            if ($("input[name='gender']:checked").length > 0) {
                                var typ = $("input[name='gender']:checked").val();
                            } else {
                                cnt = cnt + 1;
                                $("#gender_error").html('Gender is required.');
                            }

                            if (cnt == 0) {
                                $("#cash_on_delivery_id").submit();
                            }

                        }
                    </script>
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
    </section>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
    <script>
                        tststs = $('.datepicker').datepicker({
                            format: 'yyyy-mm-dd'
                        });
                        function bid_datepicker() {
                            $('.datepicker1').datepicker({
                                dateFormat: 'dd/mm/yy'
                            });
                            //$('#datepicker').datepicker({ dateFormat: 'dd-mm-yy' }).val();
                        }

                        function check_user_details(val) {
                            var cnt = 0;
                            var source_by = $("#source_by").val();
                            $("#error_source_by").html("");
                            if (source_by == '') {
                                $("#error_source_by").html("Required.");
                                return false;
                            }
                            if (val == '1') {
                                var mobile_id = $("#mobile_id1").val();
                                mobile_id = mobile_id.trim();
                                if (mobile_id == '') {
                                    cnt = cnt + 1;
                                    $("#mobile1_error").html('Mobile is required.');
                                }
                                var dob = $("#dob1").val();
                                dob = dob.trim();
                                if (dob == '') {
                                    cnt = cnt + 1;
                                    $("#dob1_error").html('Birth date is required.');
                                }
                                if ($("input[name='gender1']:checked").length > 0) {
                                    var typ = $("input[name='gender1']:checked").val();
                                } else {
                                    cnt = cnt + 1;
                                    $("#gender1_error").html('Gender is required.');
                                }
                                if (cnt == 0) {
                                    var source_by_id = $("#source_by").val();
                                    var w_url = $("#frm").attr('action');
                                    //alert(w_url + "/" + source_by_id);
                                    $("#frm").attr("action", w_url + "-" + source_by_id);
                                    setTimeout(function () {
                                        $("#frm").submit();
                                    }, 100);
                                } else {
                                    $("#myModal2").modal("show");
                                }
                            }
                            if (val == '2') {
                                var mobile_id = $("#mobile_id").val();
                                mobile_id = mobile_id.trim();
                                if (mobile_id == '') {
                                    cnt = cnt + 1;
                                    $("#mobile_error").html('Mobile is required.');
                                }
                                var dob = $("#dob").val();
                                dob = dob.trim();
                                if (dob == '') {
                                    cnt = cnt + 1;
                                    $("#dob_error").html('Birth date is required.');
                                }
                                if ($("input[name='gender']:checked").length > 0) {
                                    var typ = $("input[name='gender']:checked").val();
                                } else {
                                    cnt = cnt + 1;
                                    $("#gender_error").html('Gender is required.');
                                }

                                if (cnt == 0) {
                                    //$("#cash_on_delivery_id").submit();
                                    $("#cash_on_delivery_sub").click();
                                    //alert("ok");
                                } else {
                                    $("#myModal1").modal("show");
                                }
                            }
                        }
    </script>

    <script src="<?php echo base_url(); ?>user_assets/js/jquery-2.2.0.min.js"></script>
    <script src="<?php echo base_url(); ?>user_assets/js/jquery-ui.min.js"></script>
    <script src="<?php echo base_url(); ?>user_assets/js/bootstrap.min.js"></script>
    <script>

                        function wallet_show() {
                            var source_by_id = $("#source_by").val();
                            if (document.getElementById("myCheck").checked == true) {
                                var ttl_prc = $("#ttl_price").val();
                                ttl_prc = parseInt(ttl_prc);
                                if (ttl_prc == 0) {
                                    $("#online_payment_btn").attr("style", "display:none;");
                                    $("#pay_during_blood_c_btn").html("Book Now");
                                } else {
                                    $("#online_payment_btn").attr("style", "");
                                    $("#pay_during_blood_c_btn").html("Pay During Blood Collection");
                                }
                                document.getElementById("hide_div").setAttribute('style', 'display:block');
                                document.getElementById("use_wallet_hide_div").setAttribute('style', 'display:none');
                                var prc2 = $("#prc2").val();
                                document.getElementById("frm_wallet").setAttribute('value', prc2);
                                document.getElementById("frm_type").setAttribute('value', 'wallet');
                                document.getElementById("method_id").value = 'yes';
                                document.getElementById("method_id1").value = 'yes';
                                document.getElementById("first_total").setAttribute('style', 'display:none;');
                                document.getElementById("frm").setAttribute('action', '<?php echo base_url(); ?>user_test_master/payumoney/<?php echo $testid; ?>/wallet/<?php echo $booking_info; ?>/' + source_by_id);

                            } else if (document.getElementById("myCheck").checked == false) {

                                var prc1 = $("#prc1").val();
                                prc1 = parseInt(prc1);
                                if (prc1 == 0) {
                                    $("#online_payment_btn").attr("style", "display:none;");
                                } else {
                                    $("#online_payment_btn").attr("style", "");
                                }
                                $("#pay_during_blood_c_btn").html("Pay During Blood Collection");
                                document.getElementById("hide_div").setAttribute('style', 'display:none');
                                document.getElementById("use_wallet_hide_div").setAttribute('style', 'display:block');
                                document.getElementById("first_total").setAttribute('style', '');
                                document.getElementById("frm_wallet").setAttribute('value', prc1);
                                document.getElementById("frm_type").setAttribute('value', 'price');
                                document.getElementById("frm").setAttribute('action', '<?php echo base_url(); ?>user_test_master/payumoney/<?php echo $testid; ?>/price/<?php echo $booking_info; ?>/' + source_by_id);
                                document.getElementById("method_id").value = 'no';
                                document.getElementById("method_id1").value = 'no';
                            }


                        }

                        function get_city(val) {
                            var val = val.value;
                            val = val.trim();
                            if (val != '') {
                                $.ajax({
                                    url: "<?= base_url(); ?>User_test_master/get_city/" + val,
                                    success: function (data) {
                                        $("#city_id").html(data);
                                    },
                                });
                            } else {
                                $("#city_id").html('<option value="">--Select--</option>');
                            }
                        }
<?php if ($wallet != 0) { ?>
                            wallet_show();
<?php } ?>
    </script>