  <!-- Start main-content -->
  <div class="main-content">
    <!-- Section: home -->
    <section id="home">
		<div class="indx_big_img">
			<div class="container">
				<div class="col-sm-12" style="margin-top: 13%;">
					<div class="col-sm-10 col-sm-offset-1">
						<p class="indx_mdl_big_p">Add tests here...</p>
						<div class="col-xs-8 col-sm-10" style="padding-right: 0;">
							<input type="text" placeholder="Enter text here" class="indx_mdl_inpt"/>
						</div>
						<div class="col-xs-4 col-sm-2" style="padding-left: 0;">
							<a href="#" class="indx_srch_a">Search</a>
						</div>
					</div>
				</div>
			</div>
		</div>
    </section>
    
    <!-- Section: Services -->
    <section class="pdng_all_container">
      <div class="container">
        <div class="section-content">
          <div class="row">
            <div class="col-md-12">
             <h1 class="subtitle text-center" style="margin-bottom: 25px;"><span class="brdr_btm">About Us</span></h1>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <p>With the focus on providing quality care to our patients, best in class diagnostic support to our doctors, we are a team of dedicated pathologists supported by a highly professional team of business and clinical experts. We strive to attain highest levels of service quality to strongly influence patient satisfaction.</p>
            </div>
			<div class="col-md-6">
              <p>Non-clinical attributes, be it hygiene, waiting areas and level of communication with patients, play a vital role in ensuring patient satisfaction. Unfortunately, the non-clinical aspects are often ignored by most of healthcare providers. At Airmed, we strive to achieve highest levels of patient satisfaction, and at the same time, maintaining foremost levels of clinical standards.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Section: Departments  -->
    <section id="depertments" class="bg-lighter parlx_back">
	<div class="prlx_absolute_back"></div>
      <div class="container" style="padding-bottom: 30px !important;">
        <div class="section-content">
          <div class="row indx_round_div">
            <div class="col-md-3 col-sm-3">
              <div class="icon-box text-center">
			  <div class="col-md-3 col-sm-3 icon-content focus">
                <a href="#" class="icon bg-theme-colored icon-circled icon-border-effect effect-circled icon-lg">
                  <img src="<?php echo base_url();?>user_assets/images/open-247.png"></img>
                </a>
				</div>
				<div class="col-md-9 col-sm-9 icon-content focus">
                <h3 class="icon-box-title"><a href="#">Focussed on you</a></h3>
                 <span>24 x 7</span>
				</div>
              </div>
            </div>
            <div class="col-md-3 col-sm-3">
              <div class="icon-box text-center">
				<div class="col-md-3 col-sm-3 icon-content blood">
                <a href="#" class="icon bg-theme-colored icon-circled icon-border-effect effect-circled icon-lg">
                 <img src="<?php echo base_url();?>user_assets/images/location.png"></img>
                </a>
				</div>
				<div class="col-md-9 col-sm-9 icon-content blood">
                <h3 class="icon-box-title"><a href="#">Blood collection @ your</a></h3>
                <span>location</span>
				</div>
              </div>
            </div>
            <div class="col-md-3 col-sm-3">
              <div class="icon-box text-center">
			  <div class="col-md-3 col-sm-3 icon-content weare">
                <a href="#" class="icon bg-theme-colored icon-circled icon-border-effect effect-circled icon-lg">
                  <img src="<?php echo base_url();?>user_assets/images/logo_img.png"></img>
                </a>
				</div>
				<div class="col-md-9 col-sm-9 icon-content weare">
                <h3 class="icon-box-title"><a href="#">we are able to process</a></h3>
              <span>15000</span><p>samples hour</p>
				</div>
              </div>
            </div>
			<div class="col-md-3 col-sm-3">
              <div class="icon-box text-center">
			  <div class="col-md-3 col-sm-3 icon-content forbook">
                <a href="#" class="icon bg-theme-colored icon-circled icon-border-effect effect-circled icon-lg">
                 <img src="<?php echo base_url();?>user_assets/images/phone.png"></img>
                </a>
				</div>
				<div class="col-md-9 col-sm-9 icon-content forbook">
                <h3 class="icon-box-title"><a href="#">For booking call</a></h3>
                 <span>+91 70 43 21 50 52</span>
				</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Section: Pricing List  -->
    <section class="pdng_all_container">
      <div class="container">
        <div class="section-content">
          <div class="row">
            <div class="col-md-12">
			<h1 class="subtitle text-center" style="margin-bottom: 25px;"><span class="brdr_btm">Our health check up packages</span></h1>
			<div class="owl-carousel-4col" data-nav="true">
                <?php foreach($package as $key){?>
				<div class="item">
                  <div class="bg-lighter">
                    <img class="img-fullwidth" alt="" src="<?php echo base_url();?>upload/package/<?php echo $key['image'];?>">
                    <div class="p-20 profile">
                       <a href="swasthya-profile.html"><h4 class="mt-0 package_name"><?php echo ucfirst($key['title']);?></h4></a>
                      <p><?php echo $key['d_price'];?>/-</p>
                      <a href="<?php echo base_url();?>user_master/package_details/<?php echo $key['id'];?>" class="btn-read-more font-13">Read more</a>
                    </div>
                  </div>
                </div>
				
				<?php } ?>
            
              </div><br>
			  </div>
			   
				
            </div>
          </div>
        </div>
      </div>
    </section>
	
	<section class="pdng_all_container grey_section_back">
      <div class="container pb-30">
	      <div class="">
			<h1 class="subtitle text-center" style="margin-bottom: 25px;"><span class="brdr_btm">Your Title is Here</h1>
			<div class="row">
				<div class="col-md-12 col-sm-12">
					<!------<p>Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext </p>----->
					<div class="col-md-6 col-sm-6 dr_cmnt">
						<div class="col-md-2 col-sm-2 dr_cmnt">
							<img src="<?php echo base_url();?>user_assets/images/no_user.png">
						</div>
						<div class="col-md-10 col-sm-10 dr_cmnt">
							<a href="health_feed.html"><span>Title Here</span></a>
							<p>Ant text</p>
						</div>
						<div class="col-md-12 col-sm-12 dr_cmnt">
						<p>"Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext." </p>
						<a class="btn-read-more font-13 dr_cmnt" href="health_feed.html">Read more</a>
						</div>
					</div>
					<div class="col-md-6 col-sm-6 dr_cmnt">
						<div class="col-md-2 col-sm-2 dr_cmnt">
						<img src="<?php echo base_url();?>user_assets/images/no_user.png">
						</div>
						<div class="col-md-10 col-sm-10 dr_cmnt">
						<a href="health_feed.html"><span>Title Here</span></a>
							<p>Ant text</p>
						</div>
						<div class="col-md-12 col-sm-12 dr_cmnt">
						<p>"Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext." </p>
						<a class="btn-read-more font-13 dr_cmnt" href="health_feed.html">Read more</a>
						</div>
					</div>
					<div class="col-md-6 col-sm-6 dr_cmnt">
						<div class="col-md-2 col-sm-2 dr_cmnt">
						<img src="<?php echo base_url();?>user_assets/images/no_user.png">
						</div>
						<div class="col-md-10 col-sm-10 dr_cmnt">
							<a href="health_feed.html"><span>Title Here</span></a>
							<p>Ant text</p>
						</div>
						<div class="col-md-12 col-sm-12 dr_cmnt">
						<p>"Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext." </p>
						<a class="btn-read-more font-13 dr_cmnt" href="health_feed.html">Read more</a>
						</div>
					</div>
					<div class="col-md-6 col-sm-6 dr_cmnt">
						<div class="col-md-2 col-sm-2 dr_cmnt">
						<img src="<?php echo base_url();?>user_assets/images/no_user.png">
						</div>
						<div class="col-md-10 col-sm-10 dr_cmnt">
							<a href="health_feed.html"><span>Title Here</span></a>
							<p>Ant text</p>
						</div>
						<div class="col-md-12 col-sm-12 dr_cmnt">
						<p>"Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext Anytext." </p>
						<a class="btn-read-more font-13 dr_cmnt" href="health_feed.html">Read more</a>
						</div>
					</div>
					<a class="btn-read-more font-18" href="health_feed.html">View All</a>
				</div>
			  </div>
		  </div>
      </div>
    </section>
  </div>
  <!-- end main-content -->

<script src="<?php echo base_url();?>user_assets/js/jquery-2.2.0.min.js"></script>
<script src="<?php echo base_url();?>user_assets/js/jquery-ui.min.js"></script>
<script src="<?php echo base_url();?>user_assets/js/bootstrap.min.js"></script>
<!-- JS | jquery plugin collection for this theme -->