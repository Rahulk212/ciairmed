
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
                                <h1 class="txt_green_clr res_txt_grn">Login</h1>
                            </div>
                            <form action="<?php echo base_url(); ?>user_login/index" method="post">
                                <div class="login_light_back">

                                    <div class="col-sm-12 pdng_0 ">
                                        <div class="input-group">
                                            <span class="input-group-addon" style=""><i class="fa fa-envelope"></i></span>
                                            <input type="text"  placeholder="Enter Email" class="form-control" value="<?= set_value("email"); ?>" name="email">
											<span style="color:red;"><?php echo form_error('email'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 pdng_0 ">
                                        <div class="input-group">
                                            <span class="input-group-addon" style=""><i class="fa fa-lock"></i></span>
                                            <input type="password"  placeholder="Enter Password" class="form-control" name="password">
											<span style="color:red;"><?php echo form_error('password'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 pdng_0 ">
                                        <script src='https://www.google.com/recaptcha/api.js'></script>
                                        <div class="g-recaptcha" data-callback="recaptchaCallback" data-sitekey="6Ld5_x8UAAAAAPoCzraL5sfQ8nzvvk3e5EIC1Ljr" style="width:300px;float:left;"></div>
                                        <spam id="captch_error" style="color:red;"><?php echo form_error('g-recaptcha-response'); ?></spam>
										<div class="form-group col-sm-4 pdng_0 pull-right">
                                            <div class="">
                                                <a class="login_forgot" href="<?php echo base_url(); ?>user_forget" style="text-align:right;display:block;">Forgot Password?</a>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="col-sm-12 pdng_0">
                                        <div class="col-sm-3 pdng_0 pull-right">
                                            <button type="submit" id="send_btn" disabled="disbled" class="btn btn-dark btn-theme-colored btn-flat pull-right" data-loading-text="Please wait...">Login</button>
                                        </div>
                                    </div>

                                </div>
                                <?php
                                $captcha = $this->session->userdata('captcha2');
                                echo $captcha;
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
                    <div class="col-sm-1 login_or" style="padding-right: 0px;">
                        <div class="login_or_div">
                            <span class="or_spn">OR</span>
                        </div>
                    </div>
                    <div class="col-sm-5">
                            <!--<img class="login_img_mrgn" src="images/login.jpg"/>-->
                        <div class="login_rgt_mdl">
                            <div class="col-sm-12 pdng_0 mrgn_btm_10per">
                                <a class="" href="#" onclick="FBLogin();">
                                    <img src="<?php echo base_url(); ?>user_assets/images/login_with_fb.png"/>
                                </a>

                                </br><div class="g-signin2" data-onsuccess="onSignIn"></div>

                            </div>
                            <div class="col-sm-12 pdng_0">
                                <div class="login_new_acnt">
                                    <a class="" href="<?php echo base_url(); ?>register">
                                        <img src="<?php echo base_url(); ?>user_assets/images/create_new_account.png"/>
                                    </a>
                                </div>
                            </div>
                        </div>
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
    <style>
        .abcRioButtonContents{ font-size: 0px !important;
                               color:#df4a32!important;
                               line-height: 43px !important;}
        .abcRioButtonIcon{padding:15px !important;}
        .abcRioButtonContentWrapper{background:url('<?php echo base_url(); ?>upload/google.png')  no-repeat scroll center center / cover ;}
        .abcRioButton.abcRioButtonLightBlue{
            width:100% !important;
            margin-top: 20px;border-radius:5px;height:45px !important;
        }
        .alert-dismissable .close, .alert-dismissible .close
        {color: inherit;
         position: relative;
         right: 0 !important;
         top: -2px !important;}
        @media only screen and (max-width: 767px){
            .abcRioButton.abcRioButtonLightBlue{height:70px !important;}
            .abcRioButtonSvg{ height: 40px; width: 40px;}
        }
    </style>
    <script>
                                    function recaptchaCallback() {
                                        $('#send_btn').removeAttr('disabled');
                                    }
    </script>