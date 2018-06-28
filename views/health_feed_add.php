<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
          <section class="content-header">
            <h1>
              Add Health Feed
              <small></small>
            </h1>
            <ol class="breadcrumb">
				<li><a href="<?php echo base_url(); ?>home"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>health_feed_master/health_feed_list">Health Feed</a></li>
              <li class="active">Add Health Feed</li>
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
                <form role="form" action="<?php echo base_url(); ?>health_feed_master" method="post" enctype="multipart/form-data">
						
                  <div class="box-body">
                    <div class="col-md-6">
						<?php if (isset($unsuccess) != NULL) { ?>
                                <div class="alert alert-danger alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $unsuccess['0']; ?>
                                </div>
                            <?php } ?>
							
							<div class="form-group">
                      <label for="exampleInputFile">Title</label><span style="color:red">*</span>
						<input type="text"  name="title" class="form-control"  value="<?php echo set_value('title'); ?>" >
                      <span style="color:red;"><?php echo form_error('title'); ?></span>
                    </div>
					 <div class="form-group">
                      <label for="exampleInputFile">Image</label>
                      <input type="file" id="exampleInputFile" name="sliderfile">
                      
                    </div>
					<div class="form-group">
                      <label for="exampleInputFile">Description</label><span style="color:red">*</span>
					<textarea id="editor1" name="desc"></textarea>
					<span style="color:red;"><?php echo form_error('desc'); ?></span>
					
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
            </div>
          	</div>
			 </section>
			 
