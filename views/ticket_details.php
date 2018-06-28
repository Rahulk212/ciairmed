<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
          <section class="content-header">
            <h1>
 Ticket Details
              <small></small>
            </h1>
            <ol class="breadcrumb">
				<li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
           <li><a href="<?php echo base_url(); ?>support_system"><i class="fa fa-users"></i>Ticket List</a></li>
              <li class="active">Ticket Details</li>
            </ol>
          </section>
          <section class="content">
          	<div class="row">
          		<div class="col-md-12">
             
              <div class="box box-primary">
			  <div class="widget">
                            <?php if (isset($success) != NULL) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $success['0']; ?>
                                </div>
                            <?php } ?>

                        </div>
		 <?php if($details[0]['status'] != 0) { ?>
			 <a  style="float:right; margin-top:10px; margin-right:10px;" href='<?php echo base_url(); ?>support_system/close/<?php echo $details[0]['id']; ?>' data-toggle="tooltip" data-original-title="mark as close" ><span class="btn btn-danger">Close</span></a>
		 <?php } ?>			 


                <p class="help-block" style="color:red;"><?php if(isset($error)){
                echo $error;
                	} ?></p>
                	
                <!-- form start -->
                <form role="form" action="<?php echo base_url(); ?>support_system/view_details/<?=$ticket?>" method="post" enctype="multipart/form-data">
					
                  <div class="box-body">
                    <div class="col-md-6">
						<?= validation_errors('<div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>', '</div>'); ?>
						
						<div class="form-group">
                      <label for="exampleInputFile">Customer Name :</label> <?php echo ucfirst($details[0]['full_name']); ?>
                      
                      
                    </div>
					<div class="form-group">
                      <label for="exampleInputFile">Ticket Id:</label> <?php echo ucfirst($details[0]['ticket']); ?>
                      
                      
                    </div>
					
					<?php foreach($details as $key) {?>
					<div class="form-group">
                      <label for="exampleInputFile"><?php if($key['type']==0) { echo "Admin";} else { echo ucfirst($key['full_name']); }?></label> <br>
					  <?php echo ucfirst($key['message']); ?>
                      
                      
                    </div>
					<?php } ?>
					
					<?php if($details[0]['status'] != 0) { ?>
					<div class="form-group">
                      <label for="exampleInputFile">Send Message</label>
					  <textarea id="" name="message" class="form-control"> </textarea>
					
                    </div>
					<div class="form-group">
                      <input type="submit" value="send" class="btn btn-success"/>
                    </div>
					<?php }else { ?>
					
					<h1 style="color:red;"> Closed </h1>
					
					<?php } ?>
					</div>

                  </div><!-- /.box-body -->
                  
                </form>
              </div><!-- /.box -->
					
            </div>
          	</div>
			 </section>
			 