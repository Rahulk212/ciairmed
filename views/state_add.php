<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
          <section class="content-header">
            <h1>
            State
              <small></small>
            </h1>
            <ol class="breadcrumb">
				<li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>location-master/state-list"><i class="fa fa-users"></i>State List</a></li>
              <li class="active">Add State</li>
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
                <form role="form" action="<?php echo base_url(); ?>location-master/state-add" method="post" enctype="multipart/form-data">
					
                  <div class="box-body">
                    <div class="col-md-6">
						<!--<?= validation_errors('<div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>', '</div>'); ?>-->
					<div class="form-group">
                      <label for="exampleInputFile">Country</label><span style="color:red">*</span>
						<select class="form-control" name="country">
                              <option value="">Select Country</option>
								  <?php foreach($country as $cat){ ?>
                              <option value="<?php echo $cat['id']; ?>" <?php echo set_select('country', $cat['id']); ?>><?php echo ucwords($cat['country_name']); ?></option>
						<?php } ?>
                              </select>
							  <span style="color: red;"><?=form_error('country');?></span>
                    </div>
						<div class="form-group">
                      <label for="exampleInputFile">State Name</label><span style="color:red">*</span>
                      <input type="text"  name="name" class="form-control"  value="<?php echo set_value('name'); ?>" >
                      <span style="color: red;"><?=form_error('name');?></span>
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
</script>
            </div>
          	</div>
			 </section>
			 