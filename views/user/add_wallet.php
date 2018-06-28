
<style>
@media 
	only screen and (max-width: 767px)  {
	
		/* Force table to not be like tables anymore */
	 .res_table thead, .res_table tbody, .res_table th, .res_table td,.res_table tr { 
			display: block; 
		}
		
		/* Hide table headers (but not display: none;, for accessibility) */
	.res_table thead tr { 
			position: absolute;
			top: -9999px;
			left: -9999px;
		}
		
	.res_table tr { border: 1px solid #ccc; }
		
	.res_table td { 
			
			border: none;
			border-bottom: 1px solid #eee; 
			position: relative;
			padding-left: 50% !important; 
		}
		
		.res_table td:before { 
			/* Now like a table header */
			position: absolute; 
			/* Top/left values mimic padding */
			<!-- top: 6px;
			left: 6px; -->
			width: 45%; 
			<!-- padding-right: 10px;  -->
			white-space: nowrap;
		}
		
		
	
		.res_table td:nth-of-type(1):before { content: "Order Id"; }
		.res_table td:nth-of-type(2):before { content: "Date"; }
		.res_table td:nth-of-type(3):before { content: "Amount(Rs)"; }
		.res_table td:nth-of-type(4):before { content: "Total Amount"; }
		.res_table td:nth-of-type(5):before { content: "Type"; color: #333; }
		}
	
	
	
</style>
  <!-- Start main-content -->
  <div class="main-content">
    <!-- Section: home -->
    <section>
		<div class="container pdng_top_20px pdng_btm_30px">
			<div class="col-md-12 pdng_0">
					<h1 class="txt_green_clr">Wallet History</h1>
				</div>
			<div class="row">
				<?php if (isset($success) != NULL) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo "Money Added To Wallet Successfully"; ?>
                                </div>
                            <?php } ?>
							<?php if (isset($error) != NULL) { ?>
                                <div class="alert alert-danger alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $error[0]; ?>
                                </div>
                            <?php } ?>
					<div class="col-sm-12">
					<div class="main_wallet">
					<div class="col-sm-4">
					<div class="wimg">
					<img src="<?php echo base_url(); ?>user_assets/images/wallet1.png">
					
					<span class="wtxt1"> Rs. <?php echo number_format($total[0]['total'],2);?></span><br>
					<span class="wtxt2">Your Wallet Balance</span>
					</div>
					</div>
					<form role="form" action="<?php echo base_url(); ?>add_wallet_master/payumoney" method="post" enctype="multipart/form-data">
					<div class="col-sm-4">
					<div class="wamount">
							
							<input type="text" required="" placeholder="Enter Amount to be Added in Wallet" class="form-control" name="amount">
						 </div> 
					</div>
					<div class="col-sm-4">
					<div class="wamount">
					
					<button type="submit" class="btn btn-dark btn-theme-colored btn-flat pull-right" data-loading-text="Please wait...">Add Money to Wallet</button>
					</div>
					</div>
					</div>
					</div>
					</form>
					<div class="col-sm-12">
				<div class="login_main wllt_mrgn">
					<div class="login_dark_back">
						
<!--						<a href="<?php echo base_url();?>add_wallet_master/pdf_report" class="btn btn-dark btn-theme-colored btn-flat pull-right" data-loading-text="Please wait...">Create PDF Report</a>-->
					</div>
					</div>
					</div>
					<div class="wtable wtbl_no_mrgn">
							<div class="col-sm-12">
							<div class="res_table">
			<table class="table table-bordered">
  <thead class="wth">
    <tr>
	  
      
      <th>Date</th>
      <th>Amount</th>
	  <th>Details</th>
	  <th>Total Amount</th>
      <th>Type</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach($query as $row) {?>
    <tr>
	
     
      <td><?php echo $row['DATE']; ?></td>
      <td><?php if($row['credit']=="") { echo $row['debit']; }else { echo $row['credit']; } ?></td>
	   <td><?php if($row['credit']=="" || $row['type']=="Case Back") { echo 'Order Id :'.$row['order_id']; }else if($row['type']!="referral code"){ echo 'Txn Id : '.$row['payomonyid']; } ?>  
	  <?php if( $row['type']=="Case Back") { echo "(CashBack)";}else if($row['type']=="referral code") { echo "Invite Friend Refferal Amount";}?>
	  </td>
       <td><?php echo $row['total']; ?></td>
	  <td style="<?php if($row['credit']=="") { echo "color:red;"; }else { echo "color:green;"; }?>"><?php if($row['credit']=="") { echo "Debited"; }else { echo "Credited"; } ?></td>
    </tr>
  <?php } ?>
  </tbody>
</table>
</div>
<div style="text-align:right;" class="box-tools">
                            <ul class="pagination pagination-sm no-margin pull-right">
                                <?php echo $links; ?>
                            </ul>
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