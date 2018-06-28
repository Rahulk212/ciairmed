<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
          <section class="content-header">
            <h1>
              Edit Admin
              <small></small>
            </h1>
            <ol class="breadcrumb">
              <li><a href=""><i class="fa fa-user"></i>Admin Profile</a></li>
              <li class="active">Edit Admin</li>
            </ol>
          </section>
          <section class="content">
          	<div class="row">
          		<div class="col-md-12">
             
              <div class="box box-primary">
                <p class="help-block" style="color:red;"><?php if(isset($error)){
                echo $error;
                	} ?></p>
                	<div class="box-body">
                            <?php if (isset($success) != NULL) { ?>
                                <div class="alert alert-success alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $success['0']; ?>
                                </div>
                            <?php } ?>
                        
					</div>
                <!-- form start -->
                <form role="form" action="<?php echo base_url(); ?>admin_edit" method="post" enctype="multipart/form-data">
                  <div class="box-body">
                    <div class="col-md-6">
						<?= validation_errors('<div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>', '</div>'); ?>
                    <div class="form-group">
                      <label for="exampleInputFile">User Name</label>
                      <input type="text"  name="username" class="form-control" value="<?php echo $user->name; ?>">
                      
                    </div>
                    <div class="form-group">
                      <label for="exampleInputFile">Email</label>
                      <input type="email"  name="email" class="form-control" value="<?php echo $user->email; ?>">
                      
                    </div>
                    <div class="form-group">
								<input type="checkbox" name="chng_pwd" value="1" class="minimal" id="chngPwd"/> Change Password                    
                    </div>
                    <div class="form-group" style="display:none;" id="change_div">
							<label for="exampleInputFile">Password</label>
                      <input type="password"  name="password" id="password" class="form-control" value="<?php echo $user->password; ?>">
                                 			<input type="checkbox" id="showHide"> Show Password
                    </div>
                    </div>
                  </div><!-- /.box-body -->

                  <div class="box-footer">
                    <button class="btn btn-primary" type="submit">Submit</button>
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
			 