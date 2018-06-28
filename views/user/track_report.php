<script src='https://www.google.com/recaptcha/api.js'></script>
<!-- Start main-content -->
<div class="main-content">
    <!-- Section: home -->
    <section>
        <div class="container pdng_top_20px pdng_btm_30px">
            <div class="row">
                <div class="col-sm-12 pdng_0">
                    <div class="col-sm-6">
                        <div class="login_main">
                            <?php if (isset($getmsg1) != NULL) { ?>
                            <div class="alert alert-danger fade in" style="padding: 13px 0 12px 13px;">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                                    <?php echo $getmsg1['0']; ?>
                                </div>
                            <?php } ?>
                                <?php if (isset($getmsg) != NULL) { ?>
                                <div class="alert alert-success fade in" style="padding: 13px 0 12px 13px;">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                                    <?php echo $getmsg['0']; ?>
                                </div>
                            <?php } ?>
                                <span id="success_msg"></span>
                            <form action="<?php echo base_url(); ?>user_track_report/index" method="post">
                                <div class="login_dark_back">
                                    <h1 class="txt_green_clr res_txt_grn">Track Your Report</h1>
                                </div>
                                <div class="login_light_back">
                                    <div class="col-sm-12 pdng_0">
                                        <div id="login_div">
                                        <div class="input-group">
                                            <span class="input-group-addon" style="">
                                                <i class="fa fa-file"></i>
                                            </span>

                                            <input class="form-control" type="text" name="reg_no" id="reg_no" placeholder="Enter Your Reg NO" ></br>
                                            <spam id="regno_error" style="color:red;"><?php echo form_error('reg_no'); ?></spam>
                                        </div> 
                                        <div class="input-group">
                                            <span class="input-group-addon" style="">
                                                <i class="fa fa-phone"></i>
                                            </span>

                                            <input class="form-control" type="text" name="mobile" id="mobile" placeholder="Enter Your Mobile " ></br>
                                            <spam id="mobile_error" style="color:red;"><?php echo form_error('mobile'); ?></spam>
                                        </div>
                                            <div class="input-group">
                                                <div class="g-recaptcha" data-callback="recaptchaCallback" data-sitekey="6Ld5_x8UAAAAAPoCzraL5sfQ8nzvvk3e5EIC1Ljr" style="width:300px;float:left;"></div>
                                        <spam id="captch_error" style="color:red;"><?php echo form_error('g-recaptcha-response'); ?></spam>
                                            </div>
                                    </div>
                                        <div id="otp_div" style="display:none;">
                                            <div class="form-group has-feedback">
                                                <div class="input-group">
                                                    <span class="input-group-addon" style=""><i class="fa fa-key"></i></span>
                                                    <input type="text"  placeholder="OTP" id="otp_val" class="form-control" name="otp">
                                                </div>
                                                <span style="color:red;" id="otp_error"></span>
                                                <span style="color:green;" id="otp_success"></span>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-xs-8">    
                                            <span id="loader_div" style="display:none;"><img src="<?= base_url(); ?>upload/opc-ajax-loader.gif" style="height:28px;margin-left:10px"></span>
                                            <a href="javascript:void(0);" id="resend_opt" style="display:none;" onclick="check_data();">Resend OTP</a>
                                            <a href="javascript:void(0);" id="mycounter" style=""></a>
                                        </div><!-- /.col -->
                                        <div class="col-xs-4">
                                            <button type="button" onclick="check_data();" disabled="disbled" id="send_btn" class="btn btn-primary btn-block btn-flat">Submit</button>
                                            <button type="button" id="verify_btn" style="display:none;" onclick="check_otp();" class="btn btn-primary btn-block btn-flat">Verify</button>

                                        </div><!-- /.col -->
                                    </div>

                                    <!--                                    <div class="col-sm-3 pdng_0 mrgn_btm_25px pull-right">
                                    
                                                                            <div class="input-group">
                                    
                                                                                <button type="submit" id="send_btn"  class="btn btn-dark btn-theme-colored btn-flat pull-right" data-loading-text="Please wait...">Submit</button>
                                                                            </div>
                                                                        </div>-->
                                                                        


                                </div>
                                <?php
                                //$captcha = $this->session->userdata('captcha2');
                                //echo $captcha;
                                ?> 
                            </form>
                            <div class="login_face_gplus">
                                <div class="col-sm-4 col-xs-6 pdng_lft_0">

                                </div>
                                <div class="col-sm-4 col-xs-6 pdng_lft_0">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 pull-left">
                        <img class="regi_rgt_img" src="<?php echo base_url(); ?>user_assets/images/forgot1.jpg">
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

    <!-- end main-content -->
    <script src="<?php echo base_url(); ?>user_assets/js/jquery-2.2.0.min.js"></script>
    <script src="<?php echo base_url(); ?>user_assets/js/jquery-ui.min.js"></script>
    <script src="<?php echo base_url(); ?>user_assets/js/bootstrap.min.js"></script>



    <script>
                                                function recaptchaCallback() {
                                                    $('#send_btn').removeAttr('disabled');
                                                }
                                                ;
    </script>
    <script src="<?php echo base_url(); ?>plugins/iCheck/icheck.min.js" type="text/javascript"></script>
    <script>
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
                                                            element.innerHTML = 'Resend OTP after <b>' + (hours ? hours + ':' + twoDigits(mins) : mins) + ':' + twoDigits(time.getUTCSeconds()) + ' </b> second';
                                                            setTimeout(updateTimer, time.getUTCMilliseconds() + 500);
                                                        }
                                                    }
                                                    element = document.getElementById(elementName);
                                                    endTime = (+new Date) + 1000 * (60 * minutes + seconds) + 500;
                                                    updateTimer();
                                                }

//end new counter
                                                i = 120;
                                                //onTimer();
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
                                                $(function () {
                                                    $('input').iCheck({
                                                        checkboxClass: 'icheckbox_square-blue',
                                                        radioClass: 'iradio_square-blue',
                                                        increaseArea: '20%' // optional
                                                    });
                                                });
                                                function check_data() {
                                                    ecnt = 0;
                                                    $("#regno_error").html("");
                                                    $("#mobile_error").html("");
                                                    $("#success_msg").html("");
                                                    var reg_no = $("#reg_no").val();
                                                    var mobile = $("#mobile").val();
                                                    if (reg_no == "") {
                                                        $("#regno_error").html("Reg No. Or Booking ID field required");
                                                        ecnt = 1;
                                                    }
                                                    if (mobile == "") {
                                                        $("#mobile_error").html("Mobile No. field required");
                                                        ecnt = 1;
                                                    }
                                                    if (ecnt == 1) {
                                                        return false;
                                                    }
                                                    $.ajax({
                                                        url: "<?php echo base_url(); ?>user_track_report/verify_data",
                                                        data: {
                                                            reg_no: reg_no, mobile: mobile
                                                        },
                                                        type: "POST",
                                                        beforeSend: function () {
                                                            $("#loader_div").attr("style", "");
                                                            $("#send_btn").attr("disabled", "disabled");
                                                        },
                                                        success: function (data) {
                                                            var json_data = JSON.parse(data);
                                                            if (json_data.status == '1') {
                                                                $("#login_div").attr("style", "display:none;");
                                                                $("#send_btn").attr("style", "display:none;");
                                                                $("#forgot_pass").attr("style", "display:none;");
                                                                $("#otp_div").attr("style", "");
                                                                $("#mycounter").attr("style", "");
                                                                $("#resend_opt").attr("style", "");
                                                                $("#verify_btn").attr("style", "");
                                                                $("#success_msg").html('<div class="alert alert-success" style="padding: 13px 0 12px 13px;">We will send you OTP on your mobile number.</div>');
                                                                countdown("mycounter", 1, 0);
                                                                $("#otp_val").focus();
                                                            }
                                                            if (json_data.status == '0') {
                                                                $("#mobile_error").html(json_data.msg);
                                                                $("#mobile").focus();
                                                            }
                                                            if (json_data.status == '2') {
                                                                $("#regno_error").html(json_data.msg);
                                                                $("#reg_no").focus();
                                                            }
                                                            if (json_data.status == '3') {
                                                                $("#mobile_error").html(json_data.msg);
                                                                $("#reg_no").focus();
                                                            }
                                                        },
                                                        complete: function () {
                                                            $("#loader_div").attr("style", "display:none;");
                                                            $("#send_btn").removeAttr("disabled");
                                                        },
                                                    });
                                                }
                                                function check_otp() {
                                                    $("#otp_success").html("");
                                                    $("#otp_error").html("");
                                                    $("#verify_btn").prop("disabled", true);
                                                    var reg_no = $("#reg_no").val();
                                                    var otp = $("#otp_val").val();
                                                    if (otp.trim() != '') {
                                                        $.ajax({
                                                            url: '<?= base_url(); ?>user_track_report/check_otp',
                                                            type: 'post',
                                                            data: {otp: otp},
                                                            success: function (data) {
                                                                var jsondata = JSON.parse(data);
                                                                if (jsondata.status == 0) {
                                                                    $("#verify_btn").prop("disabled", false);
                                                                    $("#otp_error").html(jsondata.msg);
                                                                }
                                                                if (jsondata.status == 1) {
                                                                    $("#otp_success").html(jsondata.msg);
                                                                    setTimeout(function () {
                                                                        window.location = '<?= base_url() ?>user_track_report/test_report/'+reg_no;
                                                                                                }, 1000);
                                                                                            }
                                                                                        },
                                                                                        error: function (jqXhr) {
                                                                                            $("#verify_btn").prop("disabled", false);
                                                                                            alert('Oops somthing wrong Try Again!.');
                                                                                        }
                                                                                    });
                                                                                } else {
                                                                                    $("#verify_btn").prop("disabled", false);
                                                                                    $("#otp_error").html("OTP field is required.");
                                                                                }
                                                                            }
                                                                            function checkemail(mail) {
                                                                                var filter = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
                                                                                if (filter.test(mail)) {
                                                                                    return true;
                                                                                } else {
                                                                                    return false;
                                                                                }
                                                                            }
                                                                            $("#password").keyup(function (event) {
                                                                                var email = $("#email").val();
                                                                                var password = $("#password").val();
                                                                                if (event.keyCode == 13 && email != '' && password != '') {
                                                                                    $("#send_btn").click();
                                                                                }
                                                                            });
                                                                            $("#password").keydown(function (e) {
                                                                                if (e.which == 9) {
                                                                                    $("#send_btn").focus();
                                                                                    e.preventDefault();
                                                                                }
                                                                            });
                                                                            $("#email").keyup(function (event) {
                                                                                var email = $("#email").val();
                                                                                var password = $("#password").val();
                                                                                if (event.keyCode == 13 && email != '' && password != '') {
                                                                                    $("#send_btn").click();
                                                                                }
                                                                            });
                                                                            $("#otp_val").keyup(function (event) {
                                                                                if (event.keyCode == 13) {
                                                                                    $("#verify_btn").click();
                                                                                }
                                                                            });
                                                                            $("#email").focus();
    </script>