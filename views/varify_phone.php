
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
                                <?php if (isset($msg) != NULL) { ?>
                                    <div class="alert alert-success alert-dismissable">
                                        <?= $msg; ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="login_light_back">
                                <div class="col-sm-12 pdng_0 mrgn_btm_25px">
                                    <div class="input-group">
                                        <span class="input-group-addon" style=""><i class="fa fa-key"></i></span>
                                        <input type="text"  placeholder="OTP" id="otp_val" class="form-control" name="otp">
                                    </div>
                                    <span style="color:red;" id="otp_error"></span>
                                    <span style="color:green;" id="otp_success"></span>
                                </div>
								
                                <div class="col-sm-12 pdng_0 mrgn_btm_25px">
                                    <div class="input-group">
                                        <!--<a href="javascript:void(0);" onclick="resend_otp();">Resend OTP</a>--> 
                                        <a href="javascript:void(0);" id="resend_opt" style="display:none;" onclick="resend_otp();">Resend OTP</a>
                                        <a href="javascript:void(0);" id="mycounter"></a>
                                        <button type="button" id="verify_btn" onclick="check_otp();" class="btn btn-dark btn-theme-colored btn-flat pull-right" data-loading-text="Please wait...">Verify</button>
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
function countdown( elementName, minutes, seconds )
{
    var element, endTime, hours, mins, msLeft, time;

    function twoDigits( n )
    {
        return (n <= 9 ? "0" + n : n);
    }

    function updateTimer()
    {
        msLeft = endTime - (+new Date);
        if ( msLeft < 1000 ) {
            //element.innerHTML = "countdown's over!";
			$("#mycounter").attr("style", "display:none;");
			$("#resend_opt").attr("style", "");
			 element.innerHTML = '';
        } else {
			$("#resend_opt").attr("style", "display:none;");
            time = new Date( msLeft );
            hours = time.getUTCHours();
            mins = time.getUTCMinutes();
            element.innerHTML = 'Resend OTP after <b>' +(hours ? hours + ':' + twoDigits( mins ) : mins) + ':' + twoDigits( time.getUTCSeconds() ) + ' </b>';
            setTimeout( updateTimer, time.getUTCMilliseconds() + 500 );
        }
    }

    element = document.getElementById( elementName );
    endTime = (+new Date) + 1000 * (60*minutes + seconds) + 500;
    updateTimer();
}
countdown( "mycounter", 2, 0 );	
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

                function check_otp() {
                    $("#otp_success").html("");
                    $("#otp_error").html("");
					$("#verify_btn").prop("disabled", true);
                    var otp = $("#otp_val").val();
                    console.log(otp + " nishit");
                    if (otp.trim() != '') {
                        $.ajax({
                            url: '<?= base_url(); ?>api_v2/check_otp',
                            type: 'post',
                            data: {otp: otp, id: <?= $id; ?>},
                            success: function (data) {
                                var jsondata = JSON.parse(data);
                                if (jsondata.status == 0) {
									$("#verify_btn").prop("disabled", false);
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
															$("#verify_btn").prop("disabled", false);
                                                            alert('Oops somthing wrong Tryagain!.');
                                                        }
                                                    });
                                                } else {
													$("#verify_btn").prop("disabled", false);
                                                    $("#otp_error").html("OTP field is required.");
                                                }
                                            }

                                            /*  function resend_otp() {
                                             $("#otp_success").html("");
                                             $("#otp_error").html("");
                                             $.ajax({
                                             url: '<?= base_url(); ?>api/add_opt',
                                             type: 'post',
                                             data: {mobile: "<?= $user_mb[0]['mobile'] ?>", id: <?= $id; ?>},
                                             success: function (data) {
                                             var jsondata = JSON.parse(data);
                                             if (jsondata.status == 0) {
                                             $("#otp_error").html(jsondata.error_msg);
                                             } else {
                                             $("#otp_success").html("OTP successfully send.");
                                             }
                                             //$("#city_wiae_price").html(data);
                                             },
                                             error: function (jqXhr) {
                                             alert('Oops somthing wrong Tryagain!.');
                                             }
                                             });
                                             }*/

                                            function resend_otp() {
                                                $("#otp_success").html("");
                                                $("#resend_opt").attr("style", "display:none;");
                                                $("#otp_error").html("");
                                                //var mb_no = $("#mb_val").val();
                                                //if (checkmobile(mb_no) == true) {
                                                    $.ajax({
                                                        url: '<?= base_url(); ?>api_v2/resend_opt_newuser',
                                                        type: 'post',
                                                        data: {id: <?= $id; ?>},
                                                        success: function (data) {
                                                            var jsondata = JSON.parse(data);
                                                            if (jsondata.status == 0) {
                                                                $("#otp_error").html(jsondata.error_msg);
                                                            } else {
																$("#mycounter").attr("style", "");
                                                                // $("#mb_no").attr("style", "display:none;");
                                                                // $("#check_otp_div").attr("style", "");
																countdown( "mycounter", 2, 0 );	
                                                                //onTimer();
                                                                //$("#send_opt_1").attr("style", "display:none;");
                                                                //$("#check_otp").attr("style", "");
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
                                                //} else {
                                                //    $("#otp_error").html("Invalid mobile no.");
                                                //}
                                            }

            </script>
        </div>
    </section>