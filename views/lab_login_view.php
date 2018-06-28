<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>AirmedLabs</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- Bootstrap 3.3.4 -->
        <link href="<?php echo base_url(); ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- Font Awesome Icons -->
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="<?php echo base_url(); ?>dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url(); ?>user_assets/images/fav_icon.ico" />
        <!-- iCheck -->
        <link href="<?php echo base_url(); ?>plugins/iCheck/square/blue.css" rel="stylesheet" type="text/css" />

    </head>
    <style>
        .bg {
            background-image:url("<?php echo base_url(); ?>img/login_bg.jpg");
            background-repeat: no-repeat;
            background-size: cover;

        }
    </style>
    <body class="bg">
        <div class="login-box">
            <div class="login-logo">



            </div><!-- /.login-logo -->
            <div class="login-box-body">

                <p style="color:red">
                    <?php
                    if (isset($error)) {
                        echo $error;
                    }
                    ?>      
                </p>
                <h1><img src="<?php echo base_url(); ?>user_assets/images/logo.png" alt="" style=" margin-left: 57px;" width="200px"></h1>
                <p class="login-box-msg">Sign in to start your session</p>
                <span id="success_msg"></span>
                <?php if (isset($getmsg) != NULL) { ?>

                    <div class="alert alert-success">
                        <?php echo $getmsg[0]; ?>
                    </div> 
                <?php } ?>
                <?php if (isset($getmsg1) != NULL) { ?>
                    <div class="alert alert-danger">
                        <?php echo $getmsg1[0]; ?>
                    </div> 
                <?php } ?>
                <form action="<?php echo base_url(); ?>Lab_login/index" method="post">
                    <div id="login_div">
                        <div class="form-group has-feedback">
                            <input type="email" class="form-control" placeholder="Email" id="email" name="email" value="<?php echo set_value('email'); ?>"/>
                            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                            <span style="color:red" id="error_email"><?=form_error("email");?></span>
                        </div>
                        <div class="form-group has-feedback">
                            <input type="password" class="form-control" placeholder="Password" id="password" name="password"/>
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                            <span style="color:red" id="error_password"><?=form_error("password");?></span>
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
                    <div class="row">
                        <div class="col-xs-8">    
                            <span id="loader_div" style="display:none;"><img src="<?= base_url(); ?>upload/opc-ajax-loader.gif" style="height:28px;margin-left:10px"></span>
                            <a href="javascript:void(0);" id="resend_opt" style="display:none;" onclick="check_admin_login();">Resend OTP</a>
                            <a href="javascript:void(0);" id="mycounter" style=""></a>
                        </div><!-- /.col -->
                        <div class="col-xs-4">
                            <button type="button" onclick="check_admin_login();"  style="display:none;" id="send_btn" class="btn btn-primary btn-block btn-flat">Sign In</button>
                            <button type="button" id="verify_btn" style="display:none;" onclick="check_otp();" class="btn btn-primary btn-block btn-flat">Verify</button>
                            <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>

                        </div><!-- /.col -->
                    </div>
                </form>

<!--                <a href="<?php echo base_url(); ?>forget" id="forgot_pass">I forgot my password</a><br>-->
            </div><!-- /.login-box-body -->
        </div><!-- /.login-box -->

        <!-- jQuery 2.1.4 -->
        <script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
        <!-- Bootstrap 3.3.2 JS -->
        <script src="<?php echo base_url(); ?>bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <!-- iCheck -->
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
                                function check_admin_login() {
                                    ecnt = 0;
                                    $("#error_email").html("");
                                    $("#error_password").html("");
                                    $("#success_msg").html("");
                                    var email = $("#email").val();
                                    var password = $("#password").val();
                                    if (email == "") {
                                        $("#error_email").html("The email field required");
                                        ecnt = 1;
                                    } else {
                                        if (!(checkemail(email))) {
                                            $("#error_email").html("Please Enter Valid Email");
                                            ecnt = 1;
                                        }
                                    }
                                    if (password == "") {
                                        $("#error_password").html("The password field required");
                                        ecnt = 1;
                                    }
                                    if (ecnt == 1) {
                                        return false;
                                    }
                                    $.ajax({
                                        url: "<?php echo base_url(); ?>Login/verify_login",
                                        data: {
                                            email: email, password: password
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
                                                $("#success_msg").html('<div class="alert alert-success">We will send you OTP on your mobile number.</div>');
                                                countdown("mycounter", 1, 0);
                                                $("#otp_val").focus();
                                            }
                                            if (json_data.status == '0') {
                                                $("#error_password").html(json_data.msg);
                                                $("#password").focus();
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
                                    var otp = $("#otp_val").val();
                                    console.log(otp + " nishit");
                                    if (otp.trim() != '') {
                                        $.ajax({
                                            url: '<?= base_url(); ?>Login/check_otp',
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
                                                        window.location = '<?= base_url() ?>Dashboard';
                                                    }, 1000);
                                                }
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
    </body>
</html>