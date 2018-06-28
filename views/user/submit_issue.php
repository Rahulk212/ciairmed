<!-- Start main-content -->
  <div class="main-content">
    <!-- Section: home -->
    <section>
		<div class="container pdng_btm_30px">
			<div class="row">
				<form role="form" action="<?php echo base_url(); ?>user_master/submit_issue" method="post" enctype="multipart/form-data">
				<?php if (isset($success) != NULL) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $success['0']; ?>
                                </div>
                            <?php } ?>
							<?php if (isset($error) != NULL) { ?>
                                <div class="alert alert-danger alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $error['0']; ?>
                                </div>
                            <?php } ?>
					<div class="col-sm-12 pdng_0">
			<div class="col-sm-6">
				<div class="login_main">
					<div class="login_dark_back">
						<p class="login_title_p">Submit Issue</p>
					</div>
				
						<div class="col-sm-12 pdng_0 mrgn_btm_25px">
							<div class="input-group" style="width:100%;">
								<label>Suject</label>
								<input id="form_subject" class="form-control required" type="text" placeholder="Enter Subject" name="subject" aria-required="true">
							</div>
							<span style="color:red;"> <?php echo form_error('subject');?></span>
						</div>
						<div class="col-sm-12 pdng_0 mrgn_btm_25px">
							<div class="input-group" style="width:100%;">
								<label>Message</label>
								<textarea class="form-control required" placeholder="Enter Message" rows="5" name="message" aria-required="true"></textarea>
							</div>
							<span style="color:red;"> <?php echo form_error('message');?></span>
						</div>
						
						
						
						<div class="col-sm-12 pdng_0 mrgn_btm_25px">
							<div class="input-group">
								<button type="submit" class="btn btn-dark btn-theme-colored btn-flat pull-right" data-loading-text="Please wait...">Submit</button>
							</div>
						</div>
					<!-- </div> -->
				</div>
				</form>
			</div>
			<div class="col-sm-6">
				<img class="regi_rgt_img" src="<?php echo base_url(); ?>user_assets/images/prescriptions_2.jpg"/>
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