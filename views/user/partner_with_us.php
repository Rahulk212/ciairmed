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
        <div class="container pdng_top_20px" style="padding-top: 15px;">
            <div class="col-sm-12 pdng_0">
                <h1 class="pageTitleCommon txt_green_clr cntct_title">Business Partnerships</h1>
            </div>
            <div class="row">
                <div class="col-sm-12 abt_addrs_pdng" style="margin-bottom: 5%;">
                    <div class="col-md-12 col-sm-12">
                        <form id="contact_form" name="contact_form" class=""  method="post">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="alert alert-success alert-dismissable" id="cron_success" style="display:none">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        <?php echo "Message Successfully Send."; ?>
                                    </div>
                                    <div class="alert alert-danger alert-dismissable" id="cron_error" style="display:none">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        <?php echo "Oops somthing wrong Try again."; ?>
                                    </div>
                                </div>
                                                                <div class="full_div">
                                    <div class="col-sm-6">
                                        <div class="form-group prtner_slct_box">
                                            <label>Please select your Domain  <small style="color:red;">*</small></label>
                                            <select class="form-control" name="domain" id="domain">
                                                <option value="">Select Your Domain</option>
                                                <option value="Pharmacy Retailers">Pharmacy Retailers</option>
                                                <option value="Insurance Providers">Insurance Providers</option> 
                                                <option value="Pharmaceutical Companies">Pharmaceutical Companies</option>
                                                <option value="Ad/Media Agencies">Ad/Media Agencies</option> 
                                                <option value="Diagnostic Labs">Diagnostic Labs</option> 
                                                <option value="Others">Others</option>
                                            </select>
                                            <span id="error_domain" style="color:red"></span>
                                        </div>
										<div class="form-group">
                                            <label>Name of the Organization <small style="color:red;">*</small></label>
                                            <input name="name" class="form-control" type="text" placeholder="Enter Name" id="cname">
                                            <span id="error_name" style="color:red"></span>
                                        </div>
                                        <div class="form-group">
                                            <label>Email Address <small style="color:red;">*</small></label>
                                            <input name="email" class="form-control required email" type="email" placeholder="Enter Email" id="cemail">
                                            <span id="error_email" style="color:red"></span>
                                        </div>
										<div class="form-group">
                                            <label>Contact Number <small style="color:red;">*</small></label>
                                            <div class="input-group">
                                                <span class="input-group-addon" style="">+91</span>
                                                 <input id="cphone" name="phone" class="form-control" type="text" placeholder="Enter Phone">
											</div>
                                                <span id="error_phone" style="color:red"></span>
                                        </div>
                                       
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Please share a brief about your query/proposal  <small style="color:red;">*</small></label>
                                            <textarea name="message" class="form-control required res_prtnr_prpsl_txtara_768" rows="5" placeholder="Enter Message" id="cmessage" ></textarea>
                                            <span id="error_message" style="color:red"></span>
                                        </div>
										 <div class="form-group">
                                            <label>Address  <small style="color:red;">*</small></label>
                                            <textarea name="address" class="form-control required res_prtnr_adrs_txtara_768" rows="5" placeholder="Enter Address" id="address" ></textarea>
                                            <span id="error_address" style="color:red"></span>
                                        </div>
                                    </div>
                                
                                </div>
                            </div>
                            <script src='https://www.google.com/recaptcha/api.js'></script>
                            <div class="g-recaptcha" data-callback="recaptchaCallback" data-sitekey="6Ld5_x8UAAAAAPoCzraL5sfQ8nzvvk3e5EIC1Ljr"></div>
                            <spam id="captch_error" style="color:red;"></spam><br>
                            <div class="form-group">
                                <input name="form_botcheck" class="form-control" type="hidden" value="" />
                                <button type="button" id="send_btn" disabled="disbled" class="btn btn-dark btn-theme-colored btn-flat" data-loading-text="Please wait..." onclick="send_mail();">Send your message</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


        </div>



    </section>
    <script>
        is_send=0;
        function recaptchaCallback() {
            $('#send_btn').removeAttr('disabled');
            is_send=1;
        }
        function send_mail() {
            var name = $("#cname").val();
            var email = $("#cemail").val();
            var phone = $("#cphone").val();
            var domain = $("#domain").val();
            var message = $("#cmessage").val();
            var address = $("#address").val();
            var ecnt = 0;
            $("#error_name").html("")
            $("#error_email").html("")
            $("#error_phone").html("")
            $("#error_domain").html("")
            $("#error_message").html("")
            $("#error_address").html("")

            if (name == "") {
                $("#error_name").html("The name field required");
                //   ecnt = 1;
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
            if (domain == "") {
                $("#error_domain").html("The Domain field required");
                ecnt = 1;
            }
            if (message == "") {
                $("#error_message").html("The query/proposal field required");
                ecnt = 1;
            }
            if (address == "") {
                $("#error_address").html("Address field required");
                ecnt = 1;
            }
            if (!(checkemail(email))) {
                $("#error_email").html("Please Enter Valid Email");
                ecnt = 1;
            }

            if (ecnt == 1&&is_send==0) {
                return false;
            }
            var fields = $("#contact_form").serializeArray();
            $.ajax({
                url: "<?php echo base_url(); ?>user_master/partner_with_send_mail",
                data: fields,
                type: "POST",
                success: function (data) {
                    if (data.trim() == 1) {
                        $("#cron_success").show();
                        $("#cron_error").hide();
                        $("#cname").val("");
                        $("#cemail").val("");
                        $("#cphone").val("");
                        $("#domain").val("");
                        $("#cmessage").val("");
                        $("#address").val("");
                        setTimeout(function(){window.location.reload();},1000);
                    } else {
                        $("#cron_success").hide();
                        $("#cron_error").show();
                        grecaptcha.reset();
                    }

                }
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