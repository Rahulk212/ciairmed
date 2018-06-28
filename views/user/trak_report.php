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
                                <div class="alert alert-danger fade in">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                                    <?php echo $getmsg1['0']; ?>
                                </div>
                                <!-- <div class="alert alert-danger">
                                <?php echo $getmsg1['0']; ?>
                                 </div> -->
                            <?php } ?>


                            <form action="<?php echo base_url(); ?>user_forget/index" method="post">
                                <div class="login_dark_back">
                                    <h1 class="txt_green_clr res_txt_grn">Track Your Report</h1>
                                </div>
                                <div class="login_light_back">
                                    <div class="col-sm-12 pdng_0">
                                        <div class="input-group">
                                            <span class="input-group-addon" style="">
                                                <i class="fa fa-file"></i>
                                            </span>
                                           
                                            <input class="form-control" type="text" name="reg_no" placeholder="Enter Your Reg NO" ></br>
                                            <spam id="captch_error" style="color:red;"><?php echo form_error('email'); ?></spam>
                                        </div> 
  <div class="input-group">
                                            <span class="input-group-addon" style="">
                                                <i class="fa fa-phone"></i>
                                            </span>
                                           
                                            <input class="form-control" type="text" name="reg_no" placeholder="Enter Your Mobile " ></br>
                                            <spam id="captch_error" style="color:red;"><?php echo form_error('email'); ?></spam>
                                        </div> 

                                    </div>


                                    <div class="col-sm-3 pdng_0 mrgn_btm_25px pull-right">

                                        <div class="input-group">

                                            <button type="submit" id="send_btn"  class="btn btn-dark btn-theme-colored btn-flat pull-right" data-loading-text="Please wait...">Submit</button>
                                        </div>
                                    </div>
                                    <div class="g-recaptcha" data-callback="recaptchaCallback" data-sitekey="6Ld5_x8UAAAAAPoCzraL5sfQ8nzvvk3e5EIC1Ljr"></div>
                                    <spam id="captch_error" style="color:red;"><?php echo form_error('g-recaptcha-response'); ?></spam>


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