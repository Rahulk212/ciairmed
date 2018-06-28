<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
          <section class="content-header">
            <h1>
             Offer
              <small></small>
            </h1>
            <ol class="breadcrumb">
             <li><a href="<?php echo base_url(); ?>home"><i class="fa fa-dashboard"></i>Dashboard</a></li>
          
              <li class="active">Offer</li>
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
				  <form role="form" action="<?php echo base_url(); ?>offer_master" method="post" enctype="multipart/form-data">
                   
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
                      <label for="exampleInputFile">Image</label>
                      <input type="file" id="exampleInputFile" name="sliderfile">
                      <img src="<?php echo base_url(); ?>upload/<?php echo $query[0]['image']; ?>" alt="Profile Pic" style="width:200px; height:100px;"/>
                    </div>
					<div class="form-group">
                      <label for="exampleInputFile">CaseBack Percentage</label><span style="color:red">*</span>
					<input type="text" value="<?php echo $query[0]['caseback_per']; ?>" class="form-control" name="caseback_per"/>
					
					</div>
					
					<div class="form-group">
                      <label for="exampleInputFile">Description</label><span style="color:red">*</span>
					<textarea id="editor1" name="desc"> <?php echo $query[0]['description']; ?> </textarea>
					
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
			 
