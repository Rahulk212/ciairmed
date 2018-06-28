<!-- Start main-content -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<div class="main-content">
    <style>
        .zindex{
            --   z-index:-1;
        }
    </style>
    <link href="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />
    <!-- Section: home -->
    <section>
        <div class="container pdng_top_20px pdng_btm_30px">
            <div class="row">
                <div class="col-sm-12 pdng_0">
                    <div class="col-sm-7 pdng_0">
                        <div class="edt_prfl_main"> 
                            <form role="form" action="<?php echo base_url(); ?>user_master/edit_profile" method="post" enctype="multipart/form-data">
                                <div class="login_light_back edt_prfl_set_icon">
                                    <div class="col-sm-12 pdng_0 mrgn_btm_25px">
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
                                        <div class="col-sm-12">
                                            <h1 class="txt_green_clr res_txt_grn">Edit Profile</h1>
                                        </div>

                                        <div class="col-md-6 col-xs-12">
                                            <div class="edt_usr_div">
                                                <?php if ($user->pic != "") { ?>
                                                    <?php
                                                    $check_pc = substr($user->pic, 0, 6);
                                                    if ($check_pc == "https:") {
                                                        ?>
                                                        <img src="<?php echo $user->pic; ?>"/>
                                                    <?php } else { ?>
                                                        <img src="<?php echo base_url(); ?>upload/<?php echo $user->pic; ?>"/>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <img src="<?php echo base_url(); ?>user_assets/images/no_user.png"/>
                                                <?php } ?>
                                            </div>
                                            <?php if ($type != "2") { ?>
                                                <div class="file_btn">
                                                    <span class="file-input btn btn-primary btn-file btn_red">
                                                        Browse&hellip; <input type="file" name="userfile">
                                                    </span>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 pdng_0">
                                        <div class="col-sm-6 mrgn_btm_25px">
                                            <div class="input-group">
                                                <span class="input-group-addon" style=""><i class="fa fa-user"></i></span>
                                                <input type="text"  placeholder="Enter Full Name" class="form-control zindex" name="name" value="<?php echo $user->full_name; ?>">
                                            </div>
                                            <span style="color:red;"><?php echo form_error('name'); ?></span>
                                        </div>
                                        <div class="col-sm-6 mrgn_btm_25px">
                                            <div class="input-group">
                                                <span class="input-group-addon set_icon_addon_spn" style=""><i class="fa fa-mobile iclass_fnt_size"></i></span>
                                                <input type="text"  placeholder="Enter Mobile Number" class="form-control zindex edit_prfl_numbr" disabled id="user_mobile" name="mobile" value="<?php echo $user->mobile; ?>">
                                                <span class="input-group-addon set_icon_addon_spn pull-right" style="background:#fff;">
                                                    <a class="icls_red_a" href="javascript:void(0);" onclick="$('#ChangeMobileNo').modal('show');" style=""><i class="fa fa-edit iclass_fnt_size"></i></a></span>
                                            </div>
                                            <span style="color:red;"><?php echo form_error('mobile'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 pdng_0">
                                        <div class="col-sm-6 mrgn_btm_25px">
                                            <div class="input-group">
                                                <span class="input-group-addon" style=""><i class="fa fa-envelope"></i></span>
                                                <input type="text" placeholder="Email Address" class="form-control no_back_edt_inpt zindex" name="email" <?php
                                                if ($type == "2") {
                                                    echo "readonly";
                                                }
                                                ?> value="<?php echo $user->email; ?>">
                                            </div>
                                            <span style="color:red;"><?php echo form_error('email'); ?></span>
                                        </div>
                                        <div class="col-sm-6 mrgn_btm_25px">
                                            <div class="form-group edt_prfl_gendr_full">
                                                <div class="regstr_male">
                                                    <input type="radio" value="male" <?php
                                                    if ($user->gender == "male") {
                                                        echo "checked";
                                                    }
                                                    ?> name="gender"/>Male
                                                </div>
                                                <div class="regstr_male">
                                                    <input type="radio" value="female" <?php
                                                    if ($user->gender == "female") {
                                                        echo "checked";
                                                    }
                                                    ?> name="gender"/>Female
                                                </div>
                                            </div>
                                            <span style="color:red;"><?php echo form_error('gender'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 pdng_0">
                                        <div class="col-sm-6 mrgn_btm_25px">
                                            <div class="input-group rgstr_bod_age_wdth">
                                                <input type="text" placeholder="Date of birth" class="datepicker form-control" name="birth_date" value="<?= $user->dob; ?>">
                                            </div>
                                            <span style="color:red;"><?php echo form_error('birth_date'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 pdng_0">
                                        <div class="col-sm-12 pdng_0">
                                            <?php /* <div class="col-xs-12 pdng_0 mrgn_btm_25px">
                                              <div class="input-group edt_full">
                                              <div class="edt_slct">
                                              <select class="select_style" name="country" onchange="get_state(this.value);">
                                              <option value="">Select Country</option>
                                              <?php foreach ($country as $key) { ?>
                                              <option value="<?php echo $key['id']; ?>" <?php
                                              if ($key['id'] == $user->country) {
                                              echo "selected";
                                              }
                                              ?>><?php echo ucfirst($key['country_name']); ?></option>
                                              <?php } ?>
                                              </select>
                                              </div>
                                              <span style="color:red;"><?php echo form_error('country'); ?></span>
                                              </div>
                                              </div> */ ?>
                                            <div class="col-sm-6 mrgn_btm_25px">
                                                <div class="input-group edt_full">
                                                    <div class="edt_slct" id="get_state">
                                                        <select class="select_style zindex" onchange="get_city(this.value);" name="state">
                                                            <option value="">Select State</option>
                                                            <?php foreach ($state as $key) { ?>
                                                                <option value="<?php echo $key['id']; ?>" <?php
                                                                if ($key['id'] == $user->state) {
                                                                    echo "selected";
                                                                }
                                                                ?>><?php echo ucfirst(strtolower($key['state_name'])); ?></option>
                                                                    <?php } ?>
                                                        </select>
                                                    </div>
                                                    <span style="color:red;"><?php echo form_error('state'); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 mrgn_btm_25px">
                                                <div class="input-group edt_full">
                                                    <div class="edt_slct" id="get_city">
                                                        <select class="select_style zindex" name="city">
                                                            <option value="">Select City</option>
                                                            <?php foreach ($city as $key) { ?>
                                                                <option value="<?php echo $key['id']; ?>" <?php
                                                                if ($key['id'] == $user->city) {
                                                                    echo "selected";
                                                                }
                                                                ?>><?php echo ucfirst($key['city_name']); ?></option>
                                                                    <?php } ?>
                                                        </select>
                                                    </div>
                                                    <span style="color:red;"><?php echo form_error('city'); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-xs-12">
                                            <div class="input-group edt_prfl_full_txtara">
                                                <textarea class="form-control zindex"  id="contact_message2"  placeholder="Enter Address" rows="7" name="address"><?php echo $user->address; ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 mrgn_top_25px">
                                        <div class="input-group">
                                            <button type="submit" class="btn btn-dark btn-theme-colored btn-flat pull-right" data-loading-text="Please wait...">Update</button>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                    </form>
                    <div class="col-sm-5 col-xs-12">
                        <img class="edt_prfl_rgt_img" src="<?php echo base_url(); ?>user_assets/images/Profile.png"/>
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
    </section>
    <script>
        function get_state(val) {

            $.ajax({
                url: "<?php echo base_url(); ?>user_master/get_state/" + val,
                error: function (jqXHR, error, code) {
                    // alert("not show");
                },
                success: function (data) {
                    //     console.log("data"+data);
                    document.getElementById('get_state').innerHTML = "";
                    document.getElementById('get_state').innerHTML = data;

                }
            });

        }
        function get_city(val) {

            $.ajax({
                url: "<?php echo base_url(); ?>user_master/get_city/" + val,
                error: function (jqXHR, error, code) {
                    // alert("not show");
                },
                success: function (data) {
                    //     console.log("data"+data);
                    document.getElementById('get_city').innerHTML = "";
                    document.getElementById('get_city').innerHTML = data;

                }
            });

        }
    </script>
    <!--Nishit change phone start-->
    <div class="modal fade" id="ChangeMobileNo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="exampleModalLabel">Change Mobile No.</h4>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="col-sm-6" id="mb_no">
                            <div class="input-group">
                                <span class="input-group-addon pkgdtl_spn_91">+91</span>
                                <input type="text"  placeholder="Mobile No." id="mb_val" class="form-control" name="otp">
                            </div>
                            <span style="color:red;float:left;width:100%;" id="mb_error" class="col-sm-6"></span>
                        </div>
                        <div class="col-sm-6 mrgn_btm_25px" id="check_otp_div" style="display:none;">
                            <div class="input-group">
                                <span class="input-group-addon" style=""><i class="fa fa-key"></i></span>
                                <input type="text"  placeholder="OTP" id="otp_val" class="form-control" name="otp">
                            </div>
                        </div>
                        <span style="color:red;float:left;width:100%;padding-left:15px" id="otp_error"></span>
                        <span style="color:green;float:left;width:100%;padding-left:15px" id="otp_success"></span>
                    </form>
                </div>
                <div class="modal-footer">
                    <a href="javascript:void(0);" id="resend_opt" style="display:none;" onclick="resend_otp();">Resend OTP</a>
                    <a href="javascript:void(0);" id="mycounter"></a>
                    <button type="button" onclick="resend_otp();" id="send_opt_1" class="btn btn-dark btn-theme-colored btn-flat pull-right" data-loading-text="Please wait...">Send OTP</button>
                    <button type="button" onclick="check_otp();" id="check_otp" style="display:none;" class="btn btn-dark btn-theme-colored btn-flat pull-right" data-loading-text="Please wait...">Verify</button>
                </div>
            </div>
        </div>
    </div>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
    <script>
//new counter for 2.00min															
                        function countdown(elementName, minutes, seconds)
                        {
                            var element, endTime, hours, mins, msLeft, time;

                            function twoDigits(n)
                            {
                                return (n <= 9 ? "0" + n : n);
                            }

                            function updateTimer()
                            {
                                msLeft = endTime - (+new Date);
                                if (msLeft < 1000) {
                                    //element.innerHTML = "countdown's over!";
                                    $("#mycounter").attr("style", "display:none;");
                                    $("#resend_opt").attr("style", "");
                                    element.innerHTML = '';
                                } else {
                                    $("#resend_opt").attr("style", "display:none;");
                                    time = new Date(msLeft);
                                    hours = time.getUTCHours();
                                    mins = time.getUTCMinutes();
                                    element.innerHTML = 'Resend OTP after <b>' + (hours ? hours + ':' + twoDigits(mins) : mins) + ':' + twoDigits(time.getUTCSeconds()) + ' </b>';
                                    setTimeout(updateTimer, time.getUTCMilliseconds() + 500);
                                }
                            }

                            element = document.getElementById(elementName);
                            endTime = (+new Date) + 1000 * (60 * minutes + seconds) + 500;
                            updateTimer();
                        }

//end new counter	

                        i = 120;
                        function onTimer() {
                            $("#resend_opt").attr("style", "display:none;");
                            if (i < 2) {
                                document.getElementById('mycounter').innerHTML = "Resend OTP after <b>" + i + " Second</b>";
                            } else {
                                document.getElementById('mycounter').innerHTML = "Resend OTP after <b>" + i + " Seconds</b>";
                            }
                            i--;
                            if (i < 0) {
                                $("#mycounter").attr("style", "display:none;");
                                $("#resend_opt").attr("style", "");
                                i = 120;
                            } else {
                                setTimeout(onTimer, 1000);
                            }
                        }


                        function check_otp() {
                            $("#otp_success").html("");
                            $("#otp_error").html("");
                            var otp = $("#otp_val").val();
                            var mb_no = $("#mb_val").val();
                            if (otp.trim() != '') {
                                $.ajax({
                                    url: '<?= base_url(); ?>api/check_otp',
                                    type: 'post',
                                    data: {otp: otp, id: <?= $id; ?>},
                                    success: function (data) {
                                        var jsondata = JSON.parse(data);
                                        if (jsondata.status == 0) {
                                            $("#otp_error").html(jsondata.error_msg);
                                        } else {
                                            setTimeout(function () {
                                                $("#otp_success").html("Verified.");
                                                var new_phone = jsondata.data[0].mobile;
                                                $("#user_mobile").val();
                                                $("#mb_no").attr("style", "");
                                                $("#check_otp_div").attr("style", "display:none;");
                                                $("#otp_error").html("");
                                                $("#otp_success").html("");
                                                $("#user_mobile").val("");
                                                $("#otp_val").val("");
                                                $("#resend_opt").attr("style", "display:none;");
                                                $("#resend_opt").val("");
                                                $("#mycounter").attr("style", "");
                                                $("#send_opt_1").attr("style", "");
                                                $("#check_otp").attr("style", "display:none;");
                                                $("#mb_val").val("");
                                                $("#user_mobile").val(new_phone);
                                                $("#resend_opt").attr("style", "display:none;");
                                                i = 1;
                                                $("#ChangeMobileNo").modal('hide');
                                            }, 1000);

                                        }
                                        //$("#city_wiae_price").html(data);
                                    },
                                    error: function (jqXhr) {
                                        alert('Oops somthing wrong Tryagain!.');
                                    }
                                });
                            } else {
                                $("#otp_error").html("OTP field is required.");
                            }
                        }

                        function resend_otp() {
                            $("#otp_success").html("");
                            $("#resend_opt").attr("style", "display:none;");
                            $("#mycounter").attr("style", "");
                            $("#otp_error").html("");
                            var mb_no = $("#mb_val").val();
                            if (checkmobile(mb_no) == true) {
                                $.ajax({
                                    url: '<?= base_url(); ?>api/add_opt',
                                    type: 'post',
                                    data: {mobile: mb_no, id: <?= $id; ?>},
                                    success: function (data) {
                                        var jsondata = JSON.parse(data);
                                        if (jsondata.status == 0) {
                                            $("#otp_error").html(jsondata.error_msg);
                                        } else {
                                            $("#mb_no").attr("style", "display:none;");
                                            $("#check_otp_div").attr("style", "");
                                            //$("#resend_opt").attr("style", "");
                                            //$("#mycounter").attr("style", "");
                                            countdown("mycounter", 2, 0);
                                            //onTimer();
                                            $("#send_opt_1").attr("style", "display:none;");
                                            $("#check_otp").attr("style", "");
                                            $("#otp_success").html("OTP successfully send.");
                                            //$("#resend_opt").attr("style", "");
                                        }
                                        //$("#city_wiae_price").html(data);
                                    },
                                    error: function (jqXhr) {
                                        $("#resend_opt").attr("style", "");
                                        alert('Oops somthing wrong Tryagain!.');
                                    }
                                });
                            } else {
                                $("#otp_error").html("Invalid mobile no.");
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
                        $('.datepicker').datepicker({
                            format: 'yyyy-mm-dd'
                        });
    </script>
    <!--Nishit change phone end-->
    <!-- end main-content -->
    <script src="<?php echo base_url(); ?>user_assets/js/jquery-2.2.0.min.js"></script>
    <script src="<?php echo base_url(); ?>user_assets/js/jquery-ui.min.js"></script>
    <script src="<?php echo base_url(); ?>user_assets/js/bootstrap.min.js"></script>