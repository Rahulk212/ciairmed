<!-- Start main-content -->
  <div class="main-content">
    <!-- Section: home -->
	
	<section class="pdng_all_container">
      <div class="container pb-30 pdng_btm_30px">
	      <div class="">
			<h1 class="txt_green_clr res_txt_grn mrgn_btm_25px">Testimonials</h1>
			<div class="row">
				<div class="col-md-12 col-sm-12">
					<?php foreach ($testimonial as $key) { ?>
					<div class="col-md-6 col-sm-6 dr_cmnt">
						<div class="col-md-2 col-sm-4 dr_cmnt">
							<img class="hlt_feed_img" src="<?php echo base_url(); ?>upload/<?php echo $key['image']; ?>">
						</div>
						<div class="col-md-10 col-sm-8 dr_cmnt">
							<a href=""><span><b><?php echo $key['name']; ?></b></span></a>
							<p><?php echo $key['address']; ?></p>
						</div>
						<div class="col-md-12 col-sm-12 dr_cmnt">
						<p class=""><?php echo $key['description']; ?> </p>
						</div>
					</div>
					<?php } ?>
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
  </div>
  <!-- end main-content -->
<script src="<?php echo base_url(); ?>user_assets/js/jquery-2.2.0.min.js"></script>
<script src="<?php echo base_url(); ?>user_assets/js/jquery-ui.min.js"></script>
<script src="<?php echo base_url(); ?>user_assets/js/bootstrap.min.js"></script>