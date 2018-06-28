<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
          <section class="content-header">
            <h1>
              Add Admin
              <small></small>
            </h1>
            <ol class="breadcrumb">
				<li><a href="<?php echo base_url(); ?>home"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>admin_master/admin_list"><i class="fa fa-users"></i>Admin</a></li>
              <li class="active">Add Admin</li>
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
                <form role="form" action="<?php echo base_url(); ?>admin/admin_add" method="post" enctype="multipart/form-data">
					
                  <div class="box-body">
                    <div class="col-md-6">
						<?= validation_errors('<div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>', '</div>'); ?>
						<div class="form-group">
                      <label for="exampleInputFile">Admin Name</label><span style="color:red">*</span>
                      <input type="text"  name="username" class="form-control"  value="<?php echo set_value('username'); ?>" >
                      
                    </div>
					<div class="form-group">
                      <label for="exampleInputFile">Type</label><span style="color:red">*</span>
						<select class="form-control" name="type">
                              <option value="">Select Type</option>
								  <?php foreach($type as $cat){ ?>
                              <option value="<?php echo $cat['id']; ?>" <?php echo set_select('type', $cat['id']); ?>><?php echo ucwords($cat['type']); ?></option>
						<?php } ?>
                              </select>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputFile">Email</label><span style="color:red">*</span>
                      <input type="email"  name="email" class="form-control" value="<?php echo set_value('email'); ?>" >
                      
                    </div>
                    <div class="form-group">
							<label>Password</label><span style="color:red">*</span>
                      <input type="password"  name="password" id="password" class="form-control" >
							<input type="checkbox" id="showHide"> Show Password
                    </div>
					
                    </div>
                  </div><!-- /.box-body -->

                  <div class="box-footer">
					  <div class="col-md-6">
                    <button class="btn btn-primary" type="submit">ADD</button>
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
			 