<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
          <section class="content-header">
            <h1>
              State
              <small></small>
            </h1>
            <ol class="breadcrumb">
				<li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
           <li><a href="<?php echo base_url(); ?>location-master/city-list"><i class="fa fa-users"></i>State List</a></li>
              <li class="active">Edit City</li>
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
                <form role="form" action="<?php echo base_url(); ?>location-master/city-edit/<?=$cid?>" method="post" enctype="multipart/form-data">
					
                  <div class="box-body">
                    <div class="col-md-6">
						<!--<?= validation_errors('<div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>', '</div>'); ?>-->
						
						<div class="form-group">
                      <label for="exampleInputFile">Country</label><span style="color:red">*</span>
						<select class="form-control" name="country" onchange="get_state(this.value);">
                              <option value="">Select Type</option>
								  <?php foreach($country as $cat){ ?>
                              <option value="<?php echo $cat['id']; ?>" <?php if($cat['id'] == $query[0]["country_fk"]){ echo "selected"; } ?>><?php echo ucwords($cat['country_name']); ?></option>
						<?php } ?>
                              </select>
							  <span style="color: red;"><?=form_error('country');?></span>
                    </div>	
					
					<div class="form-group">
                      <label for="exampleInputFile">State</label><span style="color:red">*</span>
						<select class="form-control" name="state" id="state">
													  <option value="">Select State</option>
                               <?php foreach($state as $cat){ ?>

							   <option value="<?php echo $cat['id']; ?>" <?php if($cat['id'] == $query[0]["state_fk"]){ echo "selected"; } ?>><?php echo ucwords($cat['state_name']); ?></option>
								  							  <?php } ?>
                              </select>
							<span style="color: red;"><?=form_error('state');?></span>
                    </div>
					
					
						<div class="form-group">
                      <label for="exampleInputFile">City</label><span style="color:red">*</span>
                      <input type="text"  name="name" class="form-control"  value="<?php echo $query[0]["city_name"]; ?>" >
                      <span style="color: red;"><?=form_error('name');?></span>
                    </div>
					
                   
                   
				
                    </div>
                  </div><!-- /.box-body -->

                  <div class="box-footer">
					  <div class="col-md-6">
                    <button class="btn btn-primary" type="submit">Update</button>
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
			 