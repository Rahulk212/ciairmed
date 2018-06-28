  <!-- Start main-content -->
  <div class="main-content">
    <!-- Section: home -->
    <section>
		<div class="container pdng_top_20px">
			<div class="row">
				<!-- <div class="col-sm-12">
				
				<p class="login_title_p">Report</p>
				
				</div> -->
				<div class="col-md-12">
				<div class="col-sm-12">
				<h1 class="txt_green_clr res_txt_grn">Report</h1>
				
				</div>
				<div class="col-sm-3">
				
				<div class="custm">
					<p><b>Order ID</b></p>
					<p><b>Test Name:</b></p>
					<p><b>Amount:</b></p>
					<p><b>Date:</b></p>
					</div>
					</div>
					<div class="col-sm-9">
					<div class="custm1">
					<p>#<?php echo $job[0]['order_id'];?></p>
					<p> <?php echo $job[0]['test'];?></p>
					<p><?php echo $job[0]['price'];?></p>
					<p><?php echo $job[0]['date'];?></p>
					</div>
					</div>
					
				</div>
				
				
				
				
			</div>
			
			<div class="row">
				<div class="col-md-12">
			
				<div class="col-sm-12 dwn_title">
			
			
                <h5 class="dwn_title1">Download</h5>
				</div>
				
                <?php foreach($report as $key)  { ?>
                 <div class="col-sm-12 dwn_back">
                    <div class="col-sm-11">
                      
                        <p style="font-size:17px;"><?php echo $key['original']; ?></p>                        
                   
					</div>
					<div class="col-sm-1">
					 <a class="media-left" href="<?php echo base_url();?>upload/report/<?php echo $key['report'];?>" target="_blank"><i class="fa fa-file-pdf-o dwn_font" aria-hidden="true"></i>
</a>
					
                  </div>
				  </div>
				  <?php } ?>
                   
				 </div>
                  
					
					
					
				
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