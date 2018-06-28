<div class="main-content">
    <!-- Section: home -->
    <section>
        <div class="container pdng_top_20px pdng_btm_30px">
            <div class="row">
                <!-- <div class="col-sm-12">
                
                <p class="login_title_p">Report</p>
                
                </div> -->
                <div class="col-md-8 col-sm-8">
                    <div class="col-sm-12 pdng_0">
                        <h1 class="txt_green_clr res_txt_grn">Support System</h1>
                    </div>
                    <form role="form" action="<?php echo base_url(); ?>user_master/add_ticket" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6 margin-15 send_msg">
                                <div class="form-group ">
                                    <label>Subject<i style="color:red;">*</i></label>
                                    <input type="text" placeholder="Enter subject" class="form-control input-lg support_add" value="" name="title" id="name">
                                    <span style="color:red"><?=form_error("title");?></span>
                                </div>
                                <label>Message<i style="color:red;">*</i></label>
                                <textarea placeholder="Enter message" class="form-control input-lg support_add" name="message" id="comments" rows="5" cols="6"></textarea>
                                <span style="color:red"><?=form_error("message");?></span>
                                <input type="submit" value="Add" class="btn btn-primary btn-lg btn-block send_msg" name="submit" id="submit">

                            </div>

                        </div></form>

                    <div class="clearfix"></div>
                    <div id="message"></div>
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
</div>
<script src="<?php echo base_url(); ?>user_assets/js/jquery-2.2.0.min.js"></script>
<script src="<?php echo base_url(); ?>user_assets/js/jquery-ui.min.js"></script>
<script src="<?php echo base_url(); ?>user_assets/js/bootstrap.min.js"></script>