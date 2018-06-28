<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
          <section class="content-header">
            <h1>
              Add Slider
              <small></small>
            </h1>
            <ol class="breadcrumb">
				<li><a href="<?php echo base_url(); ?>home"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>slider/slider_list">Slider</a></li>
              <li class="active">Add Slider</li>
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
                <form role="form" action="<?php echo base_url(); ?>slider" method="post" enctype="multipart/form-data">
						<?= validation_errors('<div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>', '</div>'); ?>
						
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
								  <?php foreach($query as $cat){ ?>
                              <option value="<?php echo $cat['id']; ?>" <?php echo set_select('group', $cat['id']); ?>><?php echo ucwords($cat['group_name']); ?></option>
						<?php } ?>
                              </select>
                    </div>
					<div class="form-group">
                      <label for="exampleInputFile">Slider Picture</label>
                      <input type="file" id="exampleInputFile" name="sliderfile">
                      
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
			 
