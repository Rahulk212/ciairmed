 <style>
 h1.txt_green_clr {
    border-bottom: 2px solid;
    color: #378170;
    margin: 10px 0 20px;
    padding-bottom: 10px;
    text-align: center;
}
.msg_content_div{border-radius:3px;}
.msg_big_div.design_msg .col-sm-2
{text-align: center;}
.msg_big_div.design_msg .col-sm-2 img{float:none;width:70px;height:70px}
 .msg_big_div.design_msg:nth-child(2n+0) {
    float: right;
    text-align: right;
}
.msg_big_div.design_msg:nth-child(2n+0) > .col-sm-10 {
    float: right;
}
.msg_big_div.design_msg:nth-child(2n+0) > .col-sm-10 .msg_content_div{border:1px solid rgba(104,177,160,0.3); background:rgba(104,177,160,0.05); border-radius:3px;}

.msg_big_div.design_msg:nth-child(2n+0) > .col-sm-10 .msg_content_div:after {
	border-color: transparent transparent transparent rgba(104, 177, 160, 0.4);
    border-style: solid;
    border-width: 7px;
    content: "";
    display: block;
    height: 0;
    left: 97.5%;
    margin-top: -5px;
    position: absolute;
    top: 50%;
    width: 0;
}
.msg_big_div.design_msg .col-sm-10 .msg_content_div:after {
	border-color: transparent #eee transparent transparent;
    border-style: solid;
    border-width: 7px;
    content: "";
    display: block;
    height: 0;
    right: 97.5%;
    margin-top: -5px;
    position: absolute;
    top: 50%;
    width: 0;
}
.msg_big_div.design_msg:nth-child(2n+0) > .col-sm-2 {
    float: right;
    text-align: center;
}
.msg_big_div.design_msg:nth-child(2n+0) > .col-sm-2 img{float:none;width:70px;height:70px}
 </style>
 <!-- Start main-content -->
  <div class="main-content">
    <!-- Section: home -->
    <section>
		<div class="container pdng_top_20px pdng_btm_30px">
			<div class="row">
				<!-- <div class="col-sm-12">
				
				<p class="login_title_p">Report</p>
				
				</div> -->
				<div class="col-md-8 col-md-offset-2">
                    <div class="block-heading">
                        <div class="col-sm-12 pdng_0">
							<h1 class="txt_green_clr res_txt_grn">Support System</h1>
						 </div>
                    </div>
                    <div class="flash-message">
					
                                            </div> <!-- end .flash-message -->
                    <div class="msg_full_show_msg">
                                   <div class="widget set_showmsg_wdgt_mrgn">
                            <?php if (isset($success) != NULL) { ?>
                                <div class="alert alert-success alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $success['0']; ?>
                                </div>
                            <?php } ?>
                        
					</div>   
									  <?php foreach($allmassage as $key) {
										  ?>
										 <?php
date_default_timezone_set('Asia/Calcutta'); 
$datetime1 = date("Y-m-d H:i:s");
$datetime1 = new DateTime($datetime1);
$datetime2 = new DateTime($key['date']);
$interval = $datetime1->diff($datetime2);
if ($interval->format('%d') < 1) {
if ($interval->format('%h') > 1) {
$elapsed = $interval->format(' %h hours');
} else if ($interval->format('%i') > 1) {
$elapsed = $interval->format(' %i minutes');
} else {
$elapsed = "Just Now";
}
} else {
$elapsed = date("Y-m-d", strtotime($key['date']));
}
?> 
										  
							<div class="msg_big_div design_msg">
                                    <div class="col-xs-4 col-sm-2 pdng_0">
										<div style="width: 100%; float: left;text-align:center;">
                                        <?php if($key['type']==1){?>
										<?php if($type==2) {?>
										<img src="<?php echo $key['pic']; ?>" class="msg_round_img">
										<?php } else{
											if($key['pic']==""){ ?>
											<img src="<?php echo base_url();?>user_assets/images/user.png" class="msg_round_img">
											<?php }else{ ?>
										<img src="<?php echo base_url();?>upload/<?php echo $key['pic']; ?>" class="msg_round_img">
											<?php }} }else {?>
										<img src="<?php echo base_url();?>user_assets/images/user.png" class="msg_round_img">
										<?php }?>
										<div style="width: 100%; float: left; font-weight:bold">
										<?php if($key['type']==1){ echo ucfirst($key['full_name']);} else{ echo "Admin";}?>
										</div>
										</div>
										
                                    </div>
                                    <div class="col-xs-8 col-sm-10">
									<p style=" font-size: 11px;line-height: 5px;margin: 0;">  <?php echo $key['date'];?></p>
                                        <div class="msg_content_div">
										
                                            <p> <?php echo $key['message'];?></p>
											
                                        </div>
                                    </div>
                            </div>
									  <?php } ?>
              
<?php if($allmassage[0]['status']==1) {?>			  </div>
          <form role="form" action="<?php echo base_url(); ?>user_master/view_ticket_details/<?php echo $ticket; ?>" method="post" enctype="multipart/form-data">                    <div class="msg_full">
                        <div class="form-group">
                            <input type="hidden" value="30" name="ticket_fk">
                            <textarea placeholder="Message" class="form-control input-lg" name="message" id="comments" rows="5" cols="6"></textarea>
                            <span style="color:red"></span>
                        </div>
                        <div class="msg_full">
                            <div class="col-xs-3 col-sm-2 pull-right pdng_0">
                                <input type="submit" value="Send" class="btn btn-primary btn-lg btn-block" name="submit" id="submit">
                            </div>
                        </div>
                    </div>
                    </form>
<?php }else {?>
<h1 style="color:red;"> Closed</h1>
<?php } ?>
                                                            <ul class="pagination pagination-sm no-margin pull-right">
                         
                    </ul>

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