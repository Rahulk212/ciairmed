<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
          <section class="content-header">
            <h1>
              Edit Health Feed
              <small></small>
            </h1>
            <ol class="breadcrumb">
             <li><a href="<?php echo base_url(); ?>home"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>health_feed_master/health_feed_list">Health Feed</a></li>
              <li class="active">Edit Health Feed</li>
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
				  <form role="form" action="<?php echo base_url(); ?>health_feed_master/health_feed_edit/<?php echo $id; ?>" method="post" enctype="multipart/form-data">
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
                      <label for="exampleInputFile">Title</label><span style="color:red">*</span>
						<input type="text"  name="title" class="form-control"  value="<?php echo $query[0]['title']; ?>" >
                      <span style="color:red;"><?php echo form_error('title'); ?></span>
                    </div>
					<div class="form-group">
                      <label for="exampleInputFile">Image</label>
                      <input type="file" id="exampleInputFile" name="sliderfile">
                      <img src="<?php echo base_url(); ?>upload/health_feed/<?php echo $query[0]['image']; ?>" alt="Profile Pic" style="width:50px; height:40px;"/>
                    </div>
					
					<div class="form-group">
                      <label for="exampleInputFile">Description</label><span style="color:red">*</span>
					<textarea id="editor1" name="desc"><?php echo $query[0]['desc']; ?></textarea>
					<span style="color:red;"><?php echo form_error('desc'); ?></span>
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
			 
