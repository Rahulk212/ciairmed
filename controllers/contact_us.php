<style>
    #map-canvas {
        margin: 0;
        padding: 0;
        width: 100%;
        height:409px;
    }
</style>
<!-- Start main-content -->
<div class="main-content">
    <!-- Section: home -->
    <section>
        <div class="cntct_us_bg">
            <div class="container pdng_top_20px" style="padding-top: 15px;">

                <div class="row">
                    <div class="col-md-offset-2 col-md-8 abt_addrs_pdng cntct_us_blog " style="margin-bottom: 5%;">
                        <div class="col-md-12 col-sm-12 res_pdng_0">
                            <h1 class="text-center txt_green_clr cntct_title">Contact Us</h1>
                            <form id="contact_form" name="contact_form" class=""  method="post">

                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="alert alert-success alert-dismissable" id="cron_success" style="display:none">
                                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                            <?php echo "Mail Send Successfully"; ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">

                                        <div class="form-group">
                                            <label>NAME <small style="color:red;">*</small></label>
                                            <input name="name" class="form-control" type="text" placeholder="Enter Name" id="cname">
                                            <span id="error_name" style="color:red"></span>
                                        </div>

                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>EMAIL <small style="color:red;">*</small></label>
                                            <input name="email" class="form-control required email" type="email" placeholder="Enter Email" id="cemail">
                                            <span id="error_email" style="color:red"></span>
                                        </div>

                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>SUBJECT <small style="color:red;">*</small></label>
                                            <input id="csubject" name="subject" class="form-control required" type="text" placeholder="Enter Subject">
                                            <span id="error_subject" style="color:red"></span>
                                        </div>

                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>PHONE <small style="color:red;">*</small></label>
                                            <div class="input-group">
                                                <span class="input-group-addon" style="">+91</span>
                                                <input id="cphone" name="phone" class="form-control" type="text" placeholder="Enter Phone"></div>
                                            <span id="error_phone" style="color:red"></span>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>MESSAGE <small style="color:red;">*</small></label>
                                    <textarea name="message" class="form-control required" rows="5" placeholder="Enter Message" id="cmessage" ></textarea>
                                    <span id="error_message" style="color:red"></span>
                                </div>
                                <script src='https://www.google.com/recaptcha/api.js'></script>
                                <div class="g-recaptcha" data-callback="recaptchaCallback" data-sitekey="6Lcr9h8UAAAAAMpYIZNYqhg7zv-xrGd3gWGy1ZOV"></div>
                                <spam id="captch_error" style="color:red;"></spam>
                                <div class="form-group">
                                    <input name="form_botcheck" class="form-control" type="hidden" value="" />
                                    <button type="button" id="send_btn" disabled="disbled" class="btn btn-dark btn-theme-colored btn-flat pull-right mb_15" data-loading-text="Please wait..." onclick="send_mail();">SEND</button>
                                    <span id="loader_div" style="display:none;"><img src="<?= base_url(); ?>upload/opc-ajax-loader.gif" style="height:28px;margin-left:10px"></span>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="col-sm-offset-1 col-sm-10">
                            <div class="cntct_us_india_map">
                                <img src="<?php echo base_url(); ?>user_assets/images/india_map.png"/>
                            </div>
                            <div class="map_rgtside_div">
                                <div class="border-btn mt-30"><span></span></div>
                                <h1 class="subtitle text-center txt_blue_clr mb_0" style="margin-bottom: 25px;COLOR:#000"><span class="brdr_btm_clr">LAB AT YOUR DOORSTEP</span></h1>
                                <h2 class="subtitle text-center txt_blue_clr mt_0" style="margin-bottom: 25px;COLOR:#000"><span class="brdr_btm_clr">PAINLESS EXPERIENCE</span></h2>
                                <p class="text-center" style="font-size:17px;">Our stringent quality control and standardization measures ensure that you are sufficiently equipped to make accurate clinical decisions and thereby provide optimal care to your patients.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="container mbl_containr" style="padding-bottom:0; padding-top:10%;">
                <div class="row">
                    <div class="col-sm-12 pdng_0">
                        <div class="indx_mbl_mdl">
                            <!--  <h1 class="mbl_title center">App Communication Space</h1>-->

                            <div class="col-sm-4  col-xs-4">
                                <img src="<?php echo base_url(); ?>user_assets/images/new/book-test.png"/> 
                            </div>
                            <div class="col-sm-4 col-xs-4">
                                <img src="<?php echo base_url(); ?>user_assets/images/new/manage-report.png"/> 
                            </div>
                            <div class="col-sm-4 col-xs-4">
                                <img src="<?php echo base_url(); ?>user_assets/images/new/share-report.png"/> 
                            </div>


                        </div>
                    </div>
                </div>
            </div>
    </section>

    <section class="indx_mbl_ovrlay" style="margin-bottom:0; background:#d7d7d7; background-repeat:no-repeat; ">
        <div class="container mbl_containr">
            <div class="row">
                <div class="col-sm-12" style="text-align:center;">
                    <div class="col-sm-1 col-xs-3 pdng_0 col-sm-offset-2 ">
                        <img src="<?php echo base_url(); ?>user_assets/images/new/icon-a.png"/> 
                    </div>
                    <div class="col-sm-7  col-xs-9 pdng_0 ">    <h1 class="mbl_title center" style="margin-top:0px; margin-bottom:0px;">DOWNLOAD AIRMED MOBILE APP<br/> & GET <b style="font-family: 'Montserrat', sans-serif;">30% CASH BACK</B> </h1>

                    </div>
                    <div class="clearfix"></div><br/>
                    <div class="col-sm-6  pdng_0 col-sm-offset-4">
                        <div class="col-sm-4">
                            <a href="https://itunes.apple.com/in/app/airmed-pathlabs/id1152367695?mt=8" target="_blank"><img class="app_full_img" src="<?php echo base_url(); ?>thumb_helper.php?h=53&w=173&src=user_assets/images/apple_appstore_big.png"/></a>
                        </div>
                        <div class="col-sm-4">
                            <a href="https://play.google.com/store/apps/details?id=com.patholab&hl=en" target="_blank"><img class="mbl_googl_res_mrgn app_full_img" src="<?php echo base_url(); ?>thumb_helper.php?h=54&w=173&src=user_assets/images/google_play.png"/></a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>
    <script>
        function recaptchaCallback() {
            $('#send_btn').removeAttr('disabled');
        }
        ;
        function send_mail() {
            var name = $("#cname").val();
            var email = $("#cemail").val();
            var phone = $("#cphone").val();
            var subject = $("#csubject").val();
            var message = $("#cmessage").val();
            var ecnt = 0;
            $("#error_name").html("")
            $("#error_email").html("")
            $("#error_phone").html("")
            $("#error_subject").html("")
            $("#error_message").html("")

            if (name == "") {
                $("#error_name").html("The name field required");
                ecnt = 1;
            }
            if (email == "") {
                $("#error_email").html("The email field required");
                ecnt = 1;
            } else {
                if (!(checkemail(email))) {
                    $("#error_email").html("Please Enter Valid Email");
                    ecnt = 1;
                }
            }
            if (phone == "") {
                $("#error_phone").html("The phone field required");
                ecnt = 1;
            } else {
                if (!(checkmobile(phone))) {
                    $("#error_phone").html("Please Enter Valid Phone no");
                    ecnt = 1;
                }
            }
            if (subject == "") {
                $("#error_subject").html("The subject field required");
                ecnt = 1;
            }
            if (message == "") {
                $("#error_message").html("The message field required");
                ecnt = 1;
            }
            if (!(checkemail(email))) {
                $("#error_email").html("Please Enter Valid Email");
                ecnt = 1;
            }

            if (ecnt == 1) {
                return false;
            }
            var fields = $("#contact_form").serializeArray();
            $.ajax({
                url: "<?php echo base_url(); ?>user_master/contact_send_mail",
                data: fields,
                type: "POST",
                beforeSend: function () {
                    $("#loader_div").attr("style", "");
                    $("#send_btn").attr("disabled", "disabled");
                },
                success: function (data) {
                    if(data.trim()==1){
                    $("#cron_success").show();
                    $("#cname").val("");
                    $("#cemail").val("");
                    $("#cphone").val("");
                    $("#csubject").val("");
                    $("#cmessage").val("");
                    setTimeout(function(){window.location.reload();},1000);
                }else{
                    $("#captch_error").html("Varify captcha.");
                }
                },
                complete: function () {
                    $("#loader_div").attr("style", "display:none;");
                    $("#send_btn").removeAttr("disabled");
                },
            });


        }
        function checkemail(mail) {
            var filter = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
            if (filter.test(mail)) {
                return true;
            } else {
                return false;
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
    </script>
    <script>

        function initMap() {
            var myLatLng = {lat: 23.0333294, lng: 72.5553886};

            var map = new google.maps.Map(document.getElementById('map-canvas'), {
                zoom: 17,
                center: myLatLng
            });

            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
                title: 'Airmed Pink'
            });
        }
    </script>

<?php /* <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCa2wM_XskXSiKnaDgBu8TvHo4XZkh9ePs &callback=initMap"> */ ?>
    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

    <script>
        $(document).ready(function () {
            initMap();
        });
    </script>
    <!-- end main-content -->
    <script src="<?php echo base_url(); ?>user_assets/js/jquery-2.2.0.min.js"></script>
    <script src="<?php echo base_url(); ?>user_assets/js/jquery-ui.min.js"></script>
    <script src="<?php echo base_url(); ?>user_assets/js/bootstrap.min.js"></script>