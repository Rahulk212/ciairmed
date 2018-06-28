<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
ul.mySection { margin: 16px; list-style: none; }
ul.mySection li {margin-bottom: 15%; display: inline-block; width: 100%;}
ul.mySection input[type=checkbox] { display: none; }
ul.mySection label {
    display: table-cell; cursor: pointer;
    width: 100%; height: 150px; float: left;
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
ul.mySection label h2 {font-size: 12px; line-height: 14px; word-wrap: break-word;}
</style>
  <!-- Start main-content -->
  <div class="main-content">
    <!-- Section: home -->
    <section>
		<div class="container pdng_btm_30px">
			<div class="row">
			<form id="myform" action="<?php echo base_url(); ?>user_test_master" method="post" enctype="multipart/form-data" >
				<div class="col-sm-12 pdng_0">
					<div class="col-sm-10 col-sm-offset-1">
						<div class="test_lst_srch_div">
							<div class="col-xs-8 col-sm-10" style="padding-right: 0;">
								<input type="text" placeholder="Enter text here" class="indx_mdl_inpt" name="testname" value="<?php if(isset($testname)) { echo $testname;}?>"/>
							</div>
							<div class="col-xs-4 col-sm-2" style="padding-left: 0;">
								
								<button type="submit" class="indx_srch_a">Search</button>
							</div>
						</div>
					</div>
				</div>
				</form>
				<div class="col-sm-12 pdng_0">
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
					<form id="myform" action="<?php echo base_url(); ?>user_test_master/book_test" method="post" enctype="multipart/form-data" >
						<ul class="mySection">
							<?php $cn=1 ;foreach($test as $key){ ?>
							<div class="col-sm-2 col-xs-6">
								<li>
									<input type="checkbox" id="r<?php echo $cn;?>" name="test[]" value="<?php echo $key['id'];?>" />  
									<label for="r<?php echo $cn;?>">
										<h2><?php echo $key['test_name'];?></h2>
										<p>Rs.<?php echo $key['price'];?></p>
									</label>
								</li>
							</div>
							<?php $cn++; } ?>
							
						</ul>
						<div class="col-sm-12">
						<div class="col-sm-3 pull-right">
							<button type="submit" class="btn btn-dark btn-theme-colored btn-flat pull-right">Book</button>
						</div>
						</div>
					</form>
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
  
  <!-- Footer -->
<script>
function getOptions() {
    var frm = document.getElementById("myform");
    var chk = frm.querySelectorAll('input[type=checkbox]:checked');
    var vals = [];
    for (var i=0; i<chk.length; i++) {
        vals.push(chk[i].value);
    }
    alert(JSON.stringify(vals));
}
</script>