<!-- Start main-content -->
  <div class="main-content">
    <!-- Section: home -->
    <section>
		<div class="container">
		<div class="col-md-12 col-sm-12">
			
			<div class="row">
				<div class="col-sm-9">
					<div class="aftr_srch_full">
						<div class="srch_title">
							<div class="col-sm-6">
								<h2 class="mrgn_0">Your Exact Search</h2>
							</div>
							<div class="col-sm-6"></div>
						</div>
						<div class="srch_long_div">
							<div class="col-sm-7">
								<div class="srch_incld_div">
									<p class="srch_frst_p"><b>Includes:</b></p>
									<ul class="">
									<?php foreach($test_names as $key){ ?>
										<li> <?php echo $key; ?>,</li>
										
										<?php } ?>
									</ul>
								</div>
								<?php  $testname = implode($test_names,',');
								
								?>
								<input type="hidden" value="<?php echo $testname; ?>" id="testname"/>
								<!--<div class="srch_incld_div">
									<p class="srch_frst_p"><b>Also includes:</b></p>
									<ul class="srch_ul_also">
										<li>Liver Function Test, </li>
										<li>Thyroid Profile-Total </li>
									</ul>
								</div>-->
							</div>
							<div class="col-sm-5">
								<p class="srch_high_p col-sm-offset-2">High Quality Certified Labs only</p>
								<ul class="srch_high_ul">
									<li>
										<p class="srch_res_sml_p">Total Tests</p>
										<p class="srch_grey_p"><?php echo count($test_names);?></p>
									</li>
									<!--<li>
										<p class="srch_res_sml_p">MRP</p>
										<p class="srch_grey_p srch_p_line">2</p>
									</li>-->
									<li>
										<p class="srch_res_sml_p">Total Amount</p>
										<p class="srch_grey_p srch_clr_price">rs.<?php echo $total_price; ?></p>
									</li>
								</ul>
								<div class="col-sm-4 pdng_0 pull-right">
									<a href="#" class="slct_a btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">SELECT</a>

									  <!-- Modal -->
									  <div class="modal fade" id="myModal" role="dialog">
										<div class="modal-dialog">
										
										  <!-- Modal content-->
										  <div class="modal-content srch_popup_full">
											<div class="modal-header srch_popup_full srch_head_clr">
											  <button type="button" class="close" data-dismiss="modal">&times;</button>
											  <h4 class="modal-title clr_fff">Please provide your details here</h4>
											</div>
											<span id="error_msg" style="color:red; font-size: 20px;
    margin-left: 37px;"> </span>
											<span id="success_msg" style="color:green; font-size: 20px;
    margin-left: 37px;"> </span>
											<div class="modal-body srch_popup_full">
											  <div class="srch_popup_mdl">
												<div class="col-sm-12 pdng_0">
													<input class="srch_pop_inpt" type="text" id="name" placeholder="Name" value="<?php if(isset($user->full_name)) { echo $user->full_name; }?>"/>
												</div>
												<!--<div class="col-sm-12 pdng_0 srch_slct_div">
													<select>
														<option>Relation</option>
														<option>Child</option>
														<option>Parent</option>
													</select>
												</div>-->
												<div class="col-sm-12 pdng_0">
													<input class="srch_pop_inpt" id="email" type="text" placeholder="Email" value="<?php if(isset($user->email)) { echo $user->email; }?>"/>
												</div>
												<div class="col-sm-12 pdng_0">
													<input class="srch_pop_inpt" id="mobile" type="text" style= placeholder="Mobile" value="<?php if(isset($user->mobile)) { echo $user->mobile; }?>"/>
												</div>
												<div class="col-sm-12 pdng_0">
													<input class="srch_pop_inpt" id="age" type="text" placeholder="Age (Years)" />
												</div>
												<div class="col-sm-12 pdng_0">
													<textarea class="srch_pop_inpt" id="add" placeholder="Address"><?php if(isset($user->full_name)) { echo $user->address; }?></textarea>
												</div>
												
											  </div>
											</div>
											<div class="modal-footer srch_popup_full">
											  <button type="button" class="btn btn-default" onclick="send();">Add</button>
											</div>
										  </div>
										  
										</div>
									  </div>
								</div>
							</div>
						</div>
					</div>
					
				</div>
				<div class="col-sm-3">
					<div class="hlth_rgt_full">
					<?php foreach($package as $hf){ ?>
						<div class="indx_six_part">
							<div class="item">
							  <div class="bg-lighter indx_six_back_1">
								<img class="img-fullwidth" alt="" src="<?php echo base_url();?>/upload/package/<?php echo $hf['image'];?>">
								<div class="inpt_six_name_part">
								   <a href="swasthya-profile.html"><h4 class="mt-0 six_part_name"><?php echo $hf['title'];?></h4></a>
								  <p class="six_part_price"><?php echo $hf['d_price'];?>/-</p>
								  <div class="six_rdmr">
								  <a href="<?php echo base_url();?>user_master/package_details/<?php echo $hf['id'];?>" class="btn-read-more font-13 six_part_link_1">Read more</a>
								  </div>
								</div>
							  </div>
							</div>
						</div>
					<?php } ?>
						
						
						
						
						
					</div>
				</div>
			</div>
			</div>	
		</div>
    </section>
	<script src="<?php echo base_url();?>user_assets/js/jquery-2.2.0.min.js"></script>
<script src="<?php echo base_url();?>user_assets/js/jquery-ui.min.js"></script>
<script src="<?php echo base_url();?>user_assets/js/bootstrap.min.js"></script>

<script>

function send(){
	
	var name = document.getElementById('name').value;
	var email = document.getElementById('email').value;
	var mobile = document.getElementById('mobile').value;
	var add = document.getElementById('add').value;
	var age = document.getElementById('age').value;
	var test = document.getElementById('testname').value;
	
	if(name !="" && email !="" && mobile !="" && add !="" && age!="" && test !=""){
	
	$.ajax({
   url:"<?php echo base_url();?>user_master/send_serch_test_email",
  type:'post',
  data:{name:name,email:email,mobile:mobile,address:add,age:age,testname:test},
  success: function(data) {
                        //     console.log("data"+data);
						document.getElementById('error_msg').innerHTML = "";
						document.getElementById('success_msg').innerHTML = "Thank You ! Your Request Sent..Our Representative will call you Shortly";
						document.getElementById('name').value ="";
	document.getElementById('email').value = "";
	document.getElementById('mobile').value = "";
	document.getElementById('add').value = "";
	document.getElementById('age').value = "";
	
					}
	
});
	}else{
		
		document.getElementById('error_msg').innerHTML = "Please Insert All Fields";
		document.getElementById('success_msg').innerHTML =" ";
		
	}
}

</script>