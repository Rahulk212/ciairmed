<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
          <section class="content-header">
            <h1>
            Customer
              <small></small>
            </h1>
            <ol class="breadcrumb">
				<li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>location-master/city-list"><i class="fa fa-users"></i>City List</a></li>
              <li class="active">Add Customer</li>
            </ol>
          </section>
          <section class="content">
          	<div class="row">
          		<div class="col-md-12">
             
              <div class="box box-primary">
                <p class="help-block" style="color:red;"><?php if(isset($error)){
                echo $error;
                	} ?></p>
                	
                <!-- form start -->
                <form role="form" action="<?php echo base_url(); ?>customer-master/customer-add" method="post" enctype="multipart/form-data">
					
                  <div class="box-body">
                    <div class="col-md-6">
						<?= validation_errors('<div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>', '</div>'); ?>
					
					<div class="form-group">
                      <label for="exampleInputFile">Full Name</label><span style="color:red">*</span>
                      <input type="text"  name="fname" class="form-control"  value="<?php echo set_value('fname'); ?>" >
                      
                    </div>
					<!--<div class="form-group">
                      <label for="exampleInputFile">Last Name</label><span style="color:red">*</span>
                      <input type="text"  name="lname" class="form-control"  value="<?php echo set_value('lname'); ?>" >
                      
                    </div>-->
					<div class="form-group">
                      <label for="exampleInputFile">Gender</label><span style="color:red">*</span>
						<select class="form-control" name="gender" >
                              <option value="">Select Gender</option>
							   <option value="male">Male</option>
							    <option value="female">Female</option>
								  
                              </select>
                    </div>
					<div class="form-group">
                      <label for="exampleInputFile">Email</label><span style="color:red">*</span>
                      <input type="text"  name="email" class="form-control"  value="<?php echo set_value('email'); ?>" >
                      
                    </div>
					<div class="form-group">
                      <label for="exampleInputFile">Mobile</label><span style="color:red">*</span>
                      <input type="text"  name="mobile" class="form-control"  value="<?php echo set_value('mobile'); ?>" >
                      
                    </div>
					
					</div>
					                    <div class="col-md-6">
					<!--<div class="form-group">
                      <label for="exampleInputFile">Country</label><span style="color:red">*</span>
						<select class="form-control" name="country" onchange="get_state(this.value);">
                              <option value="">Select Country</option>
								  <?php foreach($country as $cat){ ?>
                              <option value="<?php echo $cat['id']; ?>" <?php echo set_select('country', $cat['id']); ?>><?php echo ucwords($cat['country_name']); ?></option>
						<?php } ?>
                              </select>
                    </div>-->
					<div class="form-group">
                      <label for="exampleInputFile">State</label><span style="color:red">*</span>
						<select class="form-control" name="state" id="state" onchange="get_city(this.value);">
                              <option value="">Select State</option>
								  
                              </select>
                    </div>
					
					<div class="form-group">
                      <label for="exampleInputFile">City</label><span style="color:red">*</span>
						<select class="form-control" name="city" id="city">
                              <option value="">Select City</option>
								  
                              </select>
                    </div>
					
					<div class="form-group">
                      <label for="exampleInputFile">Address</label><span style="color:red">*</span>
                      <textarea name="address" class="form-control"> <?php echo set_value('fname'); ?> </textarea>
                      
                    </div>
						
					
                   
                   
				
                    </div>
                  </div><!-- /.box-body -->

                  <div class="box-footer">
					  <div class="col-md-6">
                    <button class="btn btn-primary" type="submit">Add</button>
                  </div>
					</div>
					
                </form>
              </div><!-- /.box -->
					<script  type="text/javascript">
 $(document).ready(function () {
 $("#showHide").click(function () {
 if ($("#password").attr("type")=="password") {
 $("#password").attr("type", "text");
 }
 else{
 $("#password").attr("type", "password");
 }
 
 });
 });
 
 
 function get_state(val){
	 
	  $.ajax({
                    url: "<?php echo base_url(); ?>customer_master/get_state/"+val,
                    error: function(jqXHR, error, code) {
                       // alert("not show");
                    },
                    success: function(data) {
                        //     console.log("data"+data);
						document.getElementById('state').innerHTML = "";
						document.getElementById('state').innerHTML = data;

					}
	 });
	 
 }
 function get_city(val){
	 
	  $.ajax({
                    url: "<?php echo base_url(); ?>customer_master/get_city/"+val,
                    error: function(jqXHR, error, code) {
                       // alert("not show");
                    },
                    success: function(data) {
                        //     console.log("data"+data);
						document.getElementById('city').innerHTML = "";
						document.getElementById('city').innerHTML = data;

					}
	 });
	 
 }
</script>
            </div>
          	</div>
			 </section>
			 