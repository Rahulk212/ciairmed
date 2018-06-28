
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
ul.mySection { margin: 16px; list-style: none; }
ul.mySection li {margin-bottom: 15%; display: inline-block; width: 100%;}
ul.mySection input[type=checkbox] { display: none; }
ul.mySection label {
    display: table-cell; cursor: pointer;
    width: 100%; height: 100px; float: left;
    vertical-align: middle; text-align: center;
    background-color: #80ACDD;
}
ul.mySection label:hover { 
    background-color: #00A4EF; color: #fff; transition: all 0.25s;
}
ul.mySection label:hover h2 {color: #fff;}
ul.mySection input[type=checkbox]:checked ~ label { 
    background-color: rgba(50, 200, 50, 1); 
}
input#btn { margin: 8px 18px; padding: 8px; }
</style>
  <!-- Start main-content -->
  <div class="main-content">
    <!-- Section: home -->
    <section>
		<div class="container pdng_btm_30px">
			<div class="row">
				<div class="col-sm-12 pdng_0">
					<div class="col-sm-10 col-sm-offset-1">
						<div class="test_lst_srch_div">
							<div class="col-xs-8 col-sm-10" style="padding-right: 0;">
								<input type="text" placeholder="Search question" class="indx_mdl_inpt"/>
							</div>
							<div class="col-xs-4 col-sm-2" style="padding-left: 0;">
								<a href="#" class="indx_srch_a">Search</a>
							</div>
						</div>
					</div>
				</div>
				
			</div>
			
			 <div class="row">
          <div class="col-md-12">
            <div id="accordion1" class="panel-group accordion transparent">
              <div class="panel">
                <div class="panel-title col_que"> <a data-parent="#accordion1" data-toggle="collapse" href="#accordion11" class="active" aria-expanded="true"> <span class="open-sub"></span> <strong>Question1?</strong></a> </div>
                <div id="accordion11" class="panel-collapse collapse in" role="tablist" aria-expanded="true">
                  <div class="panel-content">
                    <p>Any text any text any text Any text any text any text Any text any text any text Any text any text any text Any text any text any text Any text any text any text</p> 
                    <p>Any text any text any text Any text any text any text Any text any text any text Any text any text any text</p>
                  </div>
                </div>
              </div>
              <div class="panel">
                <div class="panel-title col_que"> <a class="collapsed" data-parent="#accordion1" data-toggle="collapse" href="#accordion12" aria-expanded="false"> <span class="open-sub"></span> <strong>Question2?</strong></a> </div>
                <div id="accordion12" class="panel-collapse collapse" role="tablist" aria-expanded="false" style="height: 0px;">
                  <div class="panel-content">
                    <p>Any text any text any text Any text any text any text Any text any text any text Any text any text any text Any text any text any text Any text any text any text</p> 
                    <p>Any text any text any text Any text any text any text Any text any text any text Any text any text any text Any text any text any text</p>
                  </div>
                </div>
              </div>
              <div class="panel">
                <div class="panel-title col_que"> <a data-parent="#accordion1" data-toggle="collapse" href="#accordion13" class="collapsed" aria-expanded="false"> <span class="open-sub"></span> <strong>Question3?</strong></a> </div>
                <div id="accordion13" class="panel-collapse collapse" role="tablist" aria-expanded="false">
                  <div class="panel-content">
                    <p>Any text any text any text Any text any text any text Any text any text any text Any text any text any text Any text any text any text Any text any text any text</p> 
                    <p>Any text any text any text Any text any text any text Any text any text any text Any text any text any text Any text any text any text</p>
                  </div>
                </div>
              </div>
              <div class="panel">
                <div class="panel-title col_que"> <a data-parent="#accordion1" data-toggle="collapse" href="#accordion14" class="collapsed" aria-expanded="false"> <span class="open-sub"></span> <strong>Question4?</strong></a> </div>
                <div id="accordion14" class="panel-collapse collapse" role="tablist" aria-expanded="false">
                  <div class="panel-content">
                    <p>Any text any text any text Any text any text any text Any text any text any text Any text any text any text Any text any text any text Any text any text any text</p> 
                    <p>Any text any text any text Any text any text any text Any text any text any text Any text any text any text Any text any text any text</p>
                  </div>
                </div>
              </div>
              <div class="panel">
                <div class="panel-title col_que"> <a data-parent="#accordion1" data-toggle="collapse" href="#accordion15" class="collapsed" aria-expanded="false"> <span class="open-sub"></span> <strong>Question5?</strong></a> </div>
                <div id="accordion15" class="panel-collapse collapse" role="tablist" aria-expanded="false">
                  <div class="panel-content">
                    <p>Any text any text any text Any text any text any text Any text any text any text Any text any text any text Any text any text any text Any text any text any text</p> 
                    <p>Any text any text any text Any text any text any text Any text any text any text Any text any text any text</p>
                  </div>
                </div>
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
	
	<script src="<?php echo base_url();?>user_assets/js/jquery-2.2.0.min.js"></script>
<script src="<?php echo base_url();?>user_assets/js/jquery-ui.min.js"></script>
<script src="<?php echo base_url();?>user_assets/js/bootstrap.min.js"></script>