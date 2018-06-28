<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
          <section class="content-header">
            <h1>
             Send  Notification
              <small></small>
            </h1>
            <ol class="breadcrumb">
             <li><a href="<?php echo base_url(); ?>home"><i class="fa fa-dashboard"></i>Dashboard</a></li>
          
              <li class="active"> Send  Notification</li>
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
				  <form role="form" action="<?php echo base_url(); ?>job_master/all_pushnotification" method="post" enctype="multipart/form-data">
                   
                      <div class="box-body">
                    <div class="col-md-6">
						
                            <?php if (isset($unsuccess) != NULL) { ?>
                                <div class="alert alert-danger alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $unsuccess['0']; ?>
                                </div>
                            <?php } ?>
							<?php if (isset($success) != NULL) { ?>
                                <div class="alert alert-success alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $success['0']; ?>
                                </div>
                            <?php } ?>
							
							
					
					
					
					<div class="form-group">
                      <label for="exampleInputFile">Message</label><span style="color:red">*</span>
					<textarea class="form-control" name="desc">  </textarea>
					<span style="color:red;" > <?php echo form_error('desc'); ?></span>
					</div>
                    	
                    </div>
					
                  </div><!-- /.box-body -->

                  <div class="box-footer">
					  <div class="col-md-6">
                    <button class="btn btn-primary" type="submit">Send</button>
                  </div>
				  </div>
                </form>
              </div><!-- /.box -->  
            </div>
          	</div>
			 </section>
			 
