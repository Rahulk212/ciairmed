<!-- Start main-content -->
<div class="main-content">
    <!-- Section: home -->
    <section>
        <div class="container pdng_top_20px pdng_btm_30px">
            <div class="row">
                <div class="col-sm-12 pdng_0">
                    <div class="col-sm-6">
                        <div class="login_main">
                            <div class="login_dark_back">
                                <h1 class="txt_green_clr res_txt_grn">Mobile Number Verification</h1>
                                <?php /* if (isset($msg) != NULL) { ?>
                                  <div class="alert alert-success alert-dismissable">
                                  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                  <?= $msg; ?>
                                  </div>
                                  <?php } */ ?>
                            </div>
                            <div class="login_light_back">
                                <div class="col-sm-12 pdng_0 mrgn_btm_25px" id="mb_no">
                                    <div class="input-group">
                                        <span class="input-group-addon pkgdtl_spn_91">+91</span>
                                        <input type="text"  placeholder="Mobile No." id="mb_val" class="form-control" name="otp">
                                    </div>
                                    <span style="color:red;" id="mb_error"></span>
                                </div>
                                <div class="col-sm-12 pdng_0 mrgn_btm_25px" id="check_otp_div" style="display:none;">
                                    <div class="input-group">
                                        <span class="input-group-addon" style=""><i class="fa fa-key"></i></span>
                                        <input type="text"  placeholder="OTP" id="otp_val" class="form-control" name="otp">
                                    </div>
                                </div>
                                <span style="color:red;" id="otp_error"></span>
                                <span style="color:green;" id="otp_success"></span>
                                <div class="col-sm-12 pdng_0 mrgn_btm_25px">
                                    <div class="input-group">
                                        <a href="javascript:void(0);" id="mycounter"></a>
                                        <a href="javascript:void(0);" id="resend_opt" style="display:none;" onclick="resend_otp();">Resend OTP</a>
                                        <button type="button" onclick="resend_otp();" id="send_opt_1" class="btn btn-dark btn-theme-colored btn-flat pull-right" data-loading-text="Please wait...">Send OTP</button>
                                        <button type="button" onclick="check_otp();" id="check_otp" style="display:none;" class="btn btn-dark btn-theme-colored btn-flat pull-right" data-loading-text="Please wait...">Verify</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 pull-left">
                        <img class="regi_rgt_img" src="<?php echo base_url(); ?>user_assets/images/shutterstock_113144242.jpg"/>
                    </div>
                </div>



            </div>
            <!--<button onclick="onTimer()">Clickme</button>
            <div id="mycounter"></div>-->
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
                    console.log(otp + " nishit");
                    $("#check_otp").prop("disabled", true);
                    if (otp.trim() != '') {
                        $.ajax({
                            url: '<?= base_url(); ?>api_v2/check_otp',
                            type: 'post',
                            data: {otp: otp, id: <?= $id; ?>},
                            success: function (data) {
                                var jsondata = JSON.parse(data);
                                if (jsondata.status == 0) {
                                    $("#check_otp").prop("disabled", false);
                                    $("#otp_error").html(jsondata.error_msg);
                                } else {
                                    $("#otp_success").html("Verified.");
                                    setTimeout(function () {
                                        window.location = '<?= base_url() ?>Register/login/<?= $id; ?>';
                                                                }, 3000);
                                                            }
                                                            //$("#city_wiae_price").html(data);
                                                        },
                                                        error: function (jqXhr) {
                                                            $("#check_otp").prop("disabled", false);
                                                            alert('Oops somthing wrong Try again!.');
                                                        }
                                                    });
                                                } else {
                                                    $("#check_otp").prop("disabled", false);
                                                    $("#otp_error").html("OTP field is required.");
                                                }
                                            }

                                            /*function resend_otp() {
                                             $("#otp_success").html("");
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
                                             $("#resend_opt").attr("style", "");
                                             $("#send_opt_1").attr("style", "display:none;");
                                             $("#check_otp").attr("style", "");
                                             $("#otp_success").html("OTP successfully send.");
                                             $("#resend_opt").attr("style", "");
                                             }
                                             //$("#city_wiae_price").html(data);
                                             },
                                             error: function (jqXhr) {
                                             alert('Oops somthing wrong Tryagain!.'); 
                                             }
                                             });
                                             } else {
                                             $("#otp_error").html("Invalid mobile no.");
                                             }
                                             }*/
                                            function resend_otp() {
                                                $("#otp_success").html("");
                                                $("#resend_opt").attr("style", "display:none;");
                                                $("#mycounter").attr("style", "");
                                                $("#otp_error").html("");
                                                var mb_no = $("#mb_val").val();
                                                if (checkmobile(mb_no) == true) {
                                                    $.ajax({
                                                        url: '<?= base_url(); ?>api_v2/add_opt',
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
                                                            alert('Oops somthing wrong Try again!.');
                                                        }
                                                    });
                                                } else {
                                                    $("#otp_error").html("Invalid mobile no.");
                                                }
                                            }
                                            function checkmobile(mobile) {
                                                var filter = /^[0-9-+]+$/;
                                                var pattern = /^\d{10}$/;
                                                if (filter.test(mobile)) {
                                                    if (pattern.test(mobile)) {
                                                        return true;
                                                    } else {
                                                        return false;
                                                    }
                                                } else {
                                                    return false;
                                                }
                                            }
            </script>
        </div>
    </section>