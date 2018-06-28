<style>
    .rgstr_slct .select2.select2-container.select2-container--default {display: none !important;}
    .select2-hidden-accessible {
        border: 1px solid #cccccc !important;
        clip: initial !important;
        height: 39px !important;
        margin: -1px !important;
        overflow: hidden !important;
        padding: 0 !important;
        position: absolute !important;
        width: 100% !important;
    }
</style>
<link href="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />

<!-- Start main-content -->
<div class="main-content">
    <!-- Section: home -->
    <section>
        <div class="container pdng_top_20px pdng_btm_30px">
            <div class="row">
                <div class="col-sm-12 pdng_0">
                    <div class="col-sm-6">
                        <div class="login_main">
                            <?php if (isset($success) != NULL) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $success['0']; ?>
                                </div>
                            <?php } ?>
                            <div class="login_dark_back">
                                <h1 class="txt_green_clr res_txt_grn">Register</h1>
                            </div>
                            <form role="form" action="<?php echo base_url(); ?>register" method="post" enctype="multipart/form-data">
                                <div class="login_light_back">
                                    <div class="col-sm-12 pdng_0">
                                        <div class="input-group">
                                            <span class="input-group-addon" style=""><i class="fa fa-user"></i></span>
                                            <input type="text"  placeholder="Enter Full Name" class="form-control" name="name" value="<?php echo set_value('name'); ?>" tabindex="1">
                                        <span style="color:red;"> <?php echo form_error('name'); ?> </span>
										</div>
                                        
                                    </div>
                                    <div class="col-sm-12 pdng_0">
                                        <div class="input-group">
                                            <span class="input-group-addon" style=""><i class="fa fa-envelope"></i></span>
                                            <input type="text"  placeholder="Enter Email" class="form-control" name="email" value="<?php echo set_value('email'); ?>" tabindex="2">
                                        </div>
                                        <span style="color:red;"> <?php echo form_error('email'); ?> </span>
                                        <?php if (isset($unsuccess) != NULL) { ?><span style="color:red;"> <?php echo $unsuccess['0']; ?> </span><?php } ?>
                                    </div>

                                    <div class="col-sm-12 pdng_0">
                                        <div class="input-group">
                                            <span class="input-group-addon" style=""><i class="fa fa-calendar"></i></span>
                                            <input id="birth_date" name="birth_date" placeholder="Date of birth" value="<?php echo set_value('birth_date'); ?>" class="datepicker form-control" type="text" tabindex="3">
											<span style="color:red;"> <?php echo form_error('birth_date'); ?> </span>
                                        </div>
                                        <input type="text" style="display:none;"/>
                                        <?php /* <div class="col-sm-2">
                                          <div class="rgstr_or_txt_cntr">
                                          OR
                                          </div>
                                          </div>
                                          <div class="col-sm-5 pdng_0">
                                          <div class="input-group rgstr_bod_age_wdth">
                                          <input type="text"  placeholder="Age" id="age" onchange="birth_date.value = '';" class="form-control" name="age" value="">
                                          </div>
                                          </div> */ ?>
                                        
                                    </div>
                                    <div class="col-sm-12 pdng_0">
                                        <div class="input-group">
                                            <span class="input-group-addon" style=""><i class="fa fa-lock"></i></span>
                                            <input type="password"  placeholder="Enter Password" class="form-control" name="password" tabindex="4">
                                        <span style="color:red;"> <?php echo form_error('password'); ?> </span>
										</div>
                                        
                                    </div>
                                    <div class="col-sm-12 pdng_0">
                                        <div class="input-group">
                                            <span class="input-group-addon" style=""><i class="fa fa-lock"></i></span>
                                            <input type="password"  placeholder="Enter Confirm Password" class="form-control" name="cpassword" tabindex="5">
                                        <span style="color:red;"> <?php echo form_error('cpassword'); ?> </span>
										</div>
                                        
                                    </div>
                                    <div class="col-sm-12 pdng_0">
                                        <div class="input-group">
                                            <span class="input-group-addon pkgdtl_spn_91">+91</span>
                                            <input id="form_phone" name="mobile" class="form-control" type="text" placeholder="mobile" value="<?php echo set_value('mobile'); ?>" tabindex="6">
                                        <span style="color:red;"> <?php echo form_error('mobile'); ?> </span>
										</div>
                                        
                                    </div>
                                    <div class="col-sm-12 pdng_0">
                                        <div class="input-group rgstr_slct">
                                            <select class="form-control12" name="test_city" tabindex="7">
                                                <option value="">--Select Test City--</option>
                                                <?php foreach ($test_city as $key) { ?>
                                                    <option value="<?= $key["id"] ?>" <?php echo set_select('test_city', $key['id']); ?> ><?= ucfirst($key["name"]); ?></option>
                                                <?php } ?>      
                                            </select>
                                        <span class="spn_red"> <?php echo form_error('test_city'); ?> </span>
										</div>
                                        
                                    </div>
                                    <div class="col-sm-12 pdng_0">
                                        <div class="input-group">
                                            <span class="input-group-addon" style=""><i class="fa fa-pencil"></i></span>
                                            <input id="form_phone" name="refer_code" class="form-control" type="text" placeholder="Enter Refer Code" value="<?php echo $refcode; ?>" tabindex="8">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 pdng_0">
                                        <div class="form-group mrgn_0">
                                            <div class="form-group mrgn_0 regstr_gendr_full">
                                                <div class="regstr_male">
                                                    <input type="radio" name="gender" value="male"/ tabindex="9">Male
                                                </div>
                                                <div class="regstr_male">
                                                    <input type="radio" name="gender" value="female"/ tabindex="10">Female
                                                </div>
                                            </div>
											<span style="color:red;"> <?php echo form_error('gender'); ?> </span>
                                        </div>
                                        
                                    </div>
                                    <div class="col-sm-12 pdng_0">
                                        <script src='https://www.google.com/recaptcha/api.js'></script>
                                        <div class="g-recaptcha" data-callback="recaptchaCallback" data-sitekey="6Ld5_x8UAAAAAPoCzraL5sfQ8nzvvk3e5EIC1Ljr" tabindex="11"></div>
                                        <spam id="captch_error" style="color:red;"></spam><br>
                                        <span style="color:red;"><?php echo form_error('g-recaptcha-response'); ?></span>
                                    </div>
                                    <div class="col-sm-12 pdng_0">
                                        <div class="aply_terms_cndtn">
                                            <p>Accept <a href="<?= base_url(); ?>user_master/privacy_policy" target="_blank">Terms & Condition</a>
                                                <input name="checkbox" value="1" id="term_and_condition" type="checkbox" tabindex="12">
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 pdng_0">
                                        <div class="input-group">
                                            <button type="submit" id="rg_btn" disabled="" class="btn btn-dark btn-theme-colored btn-flat pull-right" data-loading-text="Please wait..." tabindex="13">Register</button>
                                        </div>
                                    </div>
                                </div> 
                            </form>
                        </div>
                    </div>
                    <div class="col-sm-6 pull-left">
                        <img class="regi_rgt_img" src="<?php echo base_url(); ?>user_assets/images/register_1.jpg"/>
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
    <script src="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
    <script>
        captcha=0;
        function recaptchaCallback() {
            //$('#rg_btn').removeAttr('disabled');
//            captcha=1;
//            if (document.getElementById("term_and_condition").checked == true) {
//                $("#rg_btn").removeAttr("disabled", "");
//            }
//            if (document.getElementById("term_and_condition").checked == false) {
//                $("#rg_btn").attr("disabled", "");
//            }
        }
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd'
        });
        function calculate_age(val)
        {
            $("#age").val("");
            var fullDate = val.split("/");
            birth_month = fullDate[1];
            birth_day = fullDate[0];
            birth_year = fullDate[2];
            today_date = new Date();
            today_year = today_date.getFullYear();
            today_month = today_date.getMonth();
            today_day = today_date.getDate();
            age = today_year - birth_year;
            if (today_month < (birth_month - 1))
            {
                age--;
            }
            if (((birth_month - 1) == today_month) && (today_day < birth_day))
            {
                age--;
            }
            //return age;
            $("#age").val(age);
        }

        $("#term_and_condition").click(function () {
            if (this.checked == true) {
                $("#rg_btn").removeAttr("disabled", "");
            }
            if (this.checked == false) {
                $("#rg_btn").attr("disabled", "");
            }
        });
    </script>
    <!-- end main-content -->
    <script src="<?php echo base_url(); ?>user_assets/js/jquery-2.2.0.min.js"></script>
    <script src="<?php echo base_url(); ?>user_assets/js/jquery-ui.min.js"></script>
    <script src="<?php echo base_url(); ?>user_assets/js/bootstrap.min.js"></script>