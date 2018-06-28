<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
          <section class="content-header">
            <h1>
              Edit Slider
              <small></small>
            </h1>
            <ol class="breadcrumb">
             <li><a href="<?php echo base_url(); ?>home"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>slider/slider_list">Slider</a></li>
              <li class="active">Edit Slider</li>
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
				  <form role="form" action="<?php echo base_url(); ?>slider/slider_edit/<?php echo $id; ?>" method="post" enctype="multipart/form-data">
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
                      <label for="exampleInputFile">Banner Group</label><span style="color:red">*</span>
						<select class="form-control" name="group">
                              <option value="">Select Group</option>
								  <?php foreach($group as $cat){ ?>
                              <option value="<?php echo $cat['id']; ?>" <?php if($cat['id'] == $query[0]["group"]){ echo "selected"; } ?>><?php echo ucwords($cat['group_name']); ?></option>
						<?php } ?>
                              </select>
                    </div>	
					<div class="form-group">
								<img src="<?php echo base_url(); ?>upload/<?php echo $query[0]['pic']; ?>" alt="Profile Pic" style="width:200px; height:150px"/>
								<input type="file" id="exampleInputFile" name="sliderfile">                    
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
			 
