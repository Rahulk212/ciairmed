<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
          <section class="content-header">
            <h1>
              Edit Testimonials
              <small></small>
            </h1>
            <ol class="breadcrumb">
             <li><a href="<?php echo base_url(); ?>home"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>Testimonials_master/index">Testimonials</a></li>
              <li class="active">Edit Testimonials</li>
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
				  <form role="form" action="<?php echo base_url(); ?>testimonials_master/edit/<?php echo $id; ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $id ?>"/>
                      <div class="box-body">
                    <div class="col-md-6">
						
                            <?php if (isset($unsuccess) != NULL) { ?>
                                <div class="alert alert-danger alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $unsuccess['0']; ?>
                                </div>
                            <?php } ?>
							
							
					<div class="form-group">
                      <label for="exampleInputFile">Name</label><span style="color:red">*</span>
						<input type="text"  name="name" class="form-control"  value="<?php echo $query[0]['name']; ?>" >
                      <span style="color:red;"><?php echo form_error('name'); ?></span>
                    </div>
						<div class="form-group">
                      <label for="exampleInputFile">Address</label><span style="color:red">*</span>
						<input type="text"  name="address" class="form-control"  value="<?php echo $query[0]['address']; ?>" >
                      <span style="color:red;"><?php echo form_error('address'); ?></span>
                    </div>
					<div class="form-group">
                      <label for="exampleInputFile">Image</label>
                      <input type="file" id="exampleInputFile" name="file">
                      <img src="<?php echo base_url(); ?>upload/<?php echo $query[0]['image']; ?>" alt="Profile Pic" style="width:50px; height:40px;"/>
                    </div>
					
					<div class="form-group">
                      <label for="exampleInputFile">Description</label><span style="color:red">*</span>
					<textarea id="editor1" name="description"> <?php echo $query[0]['description']; ?> </textarea>
					<span style="color:red;"><?php echo form_error('description'); ?></span>
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
            </div>
          	</div>
			 </section>
			 
