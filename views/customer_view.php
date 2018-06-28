<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
          <section class="content-header">
            <h1>
 Customer Details 
              <small> <a href="<?php echo base_url();?>customer-master/customer-edit/<?php echo $query[0]["id"]; ?>"class="btn btn-primary">Edit<a></small>
            </h1>
            <ol class="breadcrumb">
				<li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
           <li><a href="<?php echo base_url(); ?>customer-master/customer-list"><i class="fa fa-users"></i>Customer List</a></li>
              <li class="active">Customer Details</li>
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
                <form role="form" action="<?php echo base_url(); ?>customer-master/customer-edit/<?=$cid?>" method="post" enctype="multipart/form-data">
					
                  <div class="box-body">
                    <div class="col-md-6">
						<?= validation_errors('<div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>', '</div>'); ?>
						
						<div class="form-group">
                      <label for="exampleInputFile">Full Name</label><span style="color:red">*</span>
                      <input type="text"  disabled name="fname" class="form-control"  value="<?php echo $query[0]["full_name"]; ?>" >
                      
                    </div>
					<!--<div class="form-group">
                      <label for="exampleInputFile">Last Name</label><span style="color:red">*</span>
                      <input type="text" disabled  name="lname" class="form-control"  value="<?php echo $query[0]["last_name"]; ?>" >
                      
                    </div>-->
					<div class="form-group">
                      <label for="exampleInputFile">Gender</label><span style="color:red">*</span>
						<select disabled class="form-control" name="gender" >
                              <option value="">Select Gender</option>
							   <option value="male" <?php if($query[0]["gender"]=="male") { echo "selected"; }?>>Male</option>
							    <option value="female" <?php if($query[0]["gender"]=="female") { echo "selected"; }?>>>Female</option>
								  
                              </select>
                    </div>
					<div class="form-group">
                      <label for="exampleInputFile">Email</label><span style="color:red">*</span>
                      <input type="text" disabled  name="email" class="form-control"  value="<?php echo $query[0]["email"]; ?>" >
                      
                    </div>
					<div class="form-group">
                      <label for="exampleInputFile">Mobile</label><span style="color:red">*</span>
                      <input type="text" disabled name="mobile" class="form-control"  value="<?php echo $query[0]["mobile"]; ?>" >
                      
                    </div>
					
					</div>
					<div class="col-md-6">
						
						<div class="form-group">
                      <label for="exampleInputFile">Country</label><span style="color:red">*</span>
						<select disabled class="form-control" name="country" onchange="get_state(this.value);">
                              <option value="">Select Type</option>
								  <?php foreach($country as $cat){ ?>
                              <option value="<?php echo $cat['id']; ?>" <?php if($cat['id'] == $query[0]["country"]){ echo "selected"; } ?>><?php echo ucwords($cat['country_name']); ?></option>
						<?php } ?>
                              </select>
                    </div>	
					
					<div class="form-group">
                      <label for="exampleInputFile">State</label><span style="color:red">*</span>
						<select disabled class="form-control" name="state" id="state">
													  <option value="">Select State</option>
                               <?php foreach($state as $cat){ ?>

							   <option value="<?php echo $cat['id']; ?>" <?php if($cat['id'] == $query[0]["state"]){ echo "selected"; } ?>><?php echo ucwords($cat['state_name']); ?></option>
								  							  <?php } ?>
                              </select>

                    </div>
					
					<div class="form-group">
                      <label for="exampleInputFile">City</label><span style="color:red">*</span>
						<select disabled class="form-control" name="city" id="city">
													  <option value="">Select State</option>
                               <?php foreach($city as $cat){ ?>

							   <option value="<?php echo $cat['id']; ?>" <?php if($cat['id'] == $query[0]["city"]){ echo "selected"; } ?>><?php echo ucwords($cat['city_name']); ?></option>
								  							  <?php } ?>
                              </select>

                    </div>
					
					
						<div class="form-group">
                      <label for="exampleInputFile">Address</label><span style="color:red">*</span>
                      <textarea disabled name="address" class="form-control"> <?php echo  $query[0]["address"]; ?> </textarea>
                      
                    </div>
                   
                   
				
                    </div>
                  </div><!-- /.box-body -->

                  
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
                    url: "<?php echo base_url(); ?>location_master/get_state/"+val,
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
</script>
            </div>
          	</div>
			 </section>
			 