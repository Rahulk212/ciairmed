<!-- Start main-content -->
  <div class="main-content">
    <!-- Section: home -->
    <section>
		<div class="container pdng_btm_30px">
		<div class="col-md-12 col-sm-12">
			<div class="row">
				<div class="col-sm-9">
					<div class="col-md-12 col-sm-12 helth_feed">
						<div class="main_helth_feed helth_feed_content">
							<div class="col-md-12 col-sm-12 pdng_0">
								<h3 class="mrgn_top_0 hlth_feed_title"><?php echo ucfirst($health_feed[0]['title']); ?></h3>
							</div>
							<div class="col-md-12 col-sm-12 helth_feed pdng_0">
								<img src="<?php echo base_url();?>upload/health_feed/<?php echo $health_feed[0]['image']; ?>">
							</div>
							<div class="col-md-12 col-sm-12 start_quiz pdng_0">
								<?php echo $health_feed[0]['desc']; ?>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="hlth_rgt_full">
						<?php foreach($package as $key) { ?>
						<div class="team-member bg-white-fa maxwidth400 indx_six_back innr_six_set_fnt" style="margin-bottom:30px;">
							<a href="<?php echo base_url(); ?>user_master/package_details/<?php echo $key['id']; ?>">
								<div class="thumb">
									<img class="img-fullwidth" src="<?php echo base_url(); ?>upload/package/<?php echo $key['image']; ?>" alt=""/>
									<div class="indx_six_overlay"></div>
									<div class="info p-15 pb-10 text-center">
										<h3 class="name m-0 six_part_name"><?php echo ucfirst($key['title']); ?></h3>
										<h5 class="occupation font-weight-400 letter-space-1 mt-0 six_part_price">
											<span class="no_price"><?php echo $key['a_price']; ?>/-</span>
											<span><?php echo $key['d_price']; ?>/-</span>
										</h5>
									</div>
								</div>
							</a>
						</div>
						<?php } ?>
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