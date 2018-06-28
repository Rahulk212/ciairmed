<!-- Add jQuery library -->
<script type="text/javascript" src="<?php echo base_url(); ?>lib/jquery-1.10.2.min.js"></script>

<!-- Add mousewheel plugin (this is optional) -->
<script type="text/javascript" src="<?php echo base_url(); ?>lib/jquery.mousewheel.pack.js?v=3.1.3"></script>

<!-- Add fancyBox main JS and CSS files -->
<script type="text/javascript" src="<?php echo base_url(); ?>source/jquery.fancybox.pack.js?v=2.1.5"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>source/jquery.fancybox.css?v=2.1.5" media="screen" />

<!-- Add Button helper (this is optional) -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>source/helpers/jquery.fancybox-buttons.css?v=1.0.5" />
<script type="text/javascript" src="<?php echo base_url(); ?>source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>

<!-- Add Thumbnail helper (this is optional) -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" />
<script type="text/javascript" src="<?php echo base_url(); ?>source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>

<!-- Add Media helper (this is optional) -->
<script type="text/javascript" src="<?php echo base_url(); ?>source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>
<!--Pinkesh code start-->
<script type="text/javascript">
    var jq = $.noConflict();
    jq(document).ready(function () {
        /*
         *  Simple image gallery. Uses default settings
         */

        jq(".fancybox-effects-a").fancybox({
            'type': 'iframe',
            helpers: {
                title: {
                    type: 'outside'
                },
                overlay: {
                    speedOut: 0
                }
            }
        });
    });
</script>
 <!-- Start main-content -->
  <div class="main-content">
    <!-- Section: home -->
    <section>
		<div class="container pdng_top_20px pdng_btm_30px">
			<div class="row">
				<!-- <div class="col-sm-12">
				
				<p class="login_title_p">Report</p>
				
				</div> -->
				<div class="col-sm-12">
						<h1 class="txt_green_clr res_txt_grn">Report</h1>
					</div>
				<div class="col-md-12 pdng_0">
					<div class="col-sm-2">
						<div class="custm">
							<p class="report_datail"><b>Order ID:</b></p>
							<p class="report_datail"><b>Date:</b></p>
							<p class="report_datail"><b>Total Price:</b></p>
						</div>
					</div>
					<div class="col-sm-10">
						<div class="custm1">
						<p class="report_datail">#<?php echo $job[0]['order_id'];?></p>
						<p class="report_datail"> <?php echo $job[0]['date'];?></p>
						<p class="report_datail"> Rs. <?php echo $job[0]['price'];?></p>
						</div>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-12">
				 <?php foreach($report as $key)  { ?>
					<div class="">         
						  <table class="table table-bordered set_vw_rprt_tbl">
							<tbody>
							  <tr>
								<td style="width:15%"><b>Test Name :</b></td>
								<td><?php echo $key['test_name']; ?></td>
							  </tr>
							  <tr>
								<td><b>Description :</b></td>
								<td><?php if($key['description'] == NULL) { echo "Not Available"; } else { echo $key['description']; }  ?></td>
							  </tr>
							  <tr>
								<td><b>Report :</b></td>
								<td>
								<a class="media-left psd_gry_fnt_clr fancybox-effects-a" title="<?php echo base_url();?>upload/report/<?php echo $key['report'];?>" href="<?php echo base_url();?>upload/report/<?php echo $key['report'];?>"> View Report
</a></td>
							  </tr>
							  <tr>
								<td><b>Price :</b></td>
								<td>Rs. <?php echo $key['price'];?></td>
							  </tr>
							</tbody>
						  </table>
						<!--<div class="col-sm-12 pdng_0">
							<div class="col-sm-2 pdng_0">
								<p class="nw_rpt_lft_p">Test Name :</p>
							</div>
							<div class="col-sm-10 pdng_0">
								<p><?php echo $key['test_name']; ?></p>
							</div>
						</div>
						<div class="col-sm-12 pdng_0">
							<div class="col-sm-2 pdng_0">
								<p class="nw_rpt_lft_p">Description :</p>
							</div>
							<div class="col-sm-10 pdng_0">
								<p><?php echo $key['description']; ?></p>
							</div>
						</div>
						<div class="col-sm-12 pdng_0">
							<div class="col-sm-2 pdng_0">
								<p class="nw_rpt_lft_p">Report :</p>
							</div>
							<div class="col-sm-10 pdng_0">
								<p><a class="media-left" href="<?php echo base_url();?>upload/report/<?php echo $key['report'];?>" target="_blank">View Report<?php //echo $key['original'];?>
</a></p>
							</div>
						</div>
						<div class="col-sm-12 pdng_0">
							<div class="col-sm-2 pdng_0">
								<p class="nw_rpt_lft_p">Price :</p>
							</div>
							<div class="col-sm-10 pdng_0">
								<p>Rs. <?php echo $key['price'];?></p>
							</div>
						</div>-->
					</div>
					
				 <?php } ?>
					
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
				
			</div>
				</div>
				
			</div>
		</div>
    </section>
<script src="<?php echo base_url(); ?>user_assets/js/jquery-2.2.0.min.js"></script>
<script src="<?php echo base_url(); ?>user_assets/js/jquery-ui.min.js"></script>
<script src="<?php echo base_url(); ?>user_assets/js/bootstrap.min.js"></script>
