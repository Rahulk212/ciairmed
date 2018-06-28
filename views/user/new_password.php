
  <!-- Start main-content -->
  <div class="main-content">
    <!-- Section: home -->
    <section>
		<div class="container pdng_top_20px pdng_btm_30px">
			<div class="row">
				<div class="col-sm-12 pdng_0">
					<div class="col-sm-6">
						<div class="login_main">
            <form action="<?php echo base_url(); ?>user_get_password/index" method="post">
							<div class="login_dark_back">
								<h1 class="txt_green_clr res_txt_grn">Reset Password</h1>
							</div>
							<div class="login_light_back">
								<div class="login_light_back">
						<div class="col-sm-12 pdng_0 mrgn_btm_25px">
						  <div class="input-group">
							<span class="input-group-addon" style=""><i class="fa fa-lock"></i></span>
								<input type="password"  placeholder="New Password" class="form-control" name="password">
								<span style="color:red;"><?=form_error('password');?></span>			
						  </div>
						</div>
						<div class="col-sm-12 pdng_0 mrgn_btm_25px">
							<div class="input-group">
								<span class="input-group-addon" style=""><i class="fa fa-lock"></i></span>
								<input type="password"  placeholder="Confirm Password" class="form-control" name="passconf">
								<span style="color:red;"><?=form_error('passconf');?></span>
							</div>
						</div>
						
						
						
						<div class="col-sm-12 pdng_0 mrgn_btm_25px">
							<div class="input-group">
								<button type="submit" class="btn btn-dark btn-theme-colored btn-flat pull-right" data-loading-text="Please wait...">Change Password</button>
							</div>
						</div>
					</div>
							</form>
							<div class="login_face_gplus">
								<div class="col-sm-4 col-xs-6 pdng_lft_0">
									
								</div>
								<div class="col-sm-4 col-xs-6 pdng_lft_0">
									
								</div>
							</div>
						</div>
					</div>
					</div>
					<div class="col-sm-6 only_res_full_div">
						<img class="regi_rgt_img" src="<?php echo base_url();?>user_assets/images/forgot1.jpg">
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
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  
  <!-- end main-content -->
<script src="<?php echo base_url(); ?>user_assets/js/jquery-2.2.0.min.js"></script>
<script src="<?php echo base_url(); ?>user_assets/js/jquery-ui.min.js"></script>
<script src="<?php echo base_url(); ?>user_assets/js/bootstrap.min.js"></script>