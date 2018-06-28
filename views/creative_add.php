<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="http://cdn.rawgit.com/davidstutz/bootstrap-multiselect/master/dist/css/bootstrap-multiselect.css"
        rel="stylesheet" type="text/css" />
    <script src="http://cdn.rawgit.com/davidstutz/bootstrap-multiselect/master/dist/js/bootstrap-multiselect.js"
        type="text/javascript"></script>
    <script type="text/javascript">
        $(function () {
            $('#lstFruits').multiselect({
                includeSelectAllOption: true
            });
            $('#btnSelected').click(function () {
                var selected = $("#lstFruits option:selected");
                var message = "";
                selected.each(function () {
                    message += $(this).text() + " " + $(this).val() + "\n";
                });
                alert(message);
            });
        });
    </script>
          <section class="content-header">
            <h1>
              Add Creative
              <small></small>
            </h1>
            <ol class="breadcrumb">
				<li><a href="<?php echo base_url(); ?>home"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>creative_master/creative_list">Creative</a></li>
              <li class="active">Add Creative</li>
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
                <form role="form" action="<?php echo base_url(); ?>creative_master" method="post" enctype="multipart/form-data">
						<!--<?= validation_errors('<div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>', '</div>'); ?>-->
						
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
						<input type="text"  name="title" class="form-control"  value="<?php echo set_value('tile'); ?>" >
						<span style="color: red;"><?=form_error('title');?></span>
                    </div>
					<div class="form-group">
                      <label for="exampleInputFile">Price</label><span style="color:red">*</span>
						<input type="text"  name="price" class="form-control"  value="<?php echo set_value('aprice'); ?>" >
						<span style="color: red;"><?=form_error('price');?></span>
                    </div>
					<div class="form-group">
                      <label for="exampleInputFile">Description for Website</label><span style="color:red">*</span>
					<textarea id="editor1" name="desc_web"> </textarea>
						<span style="color: red;"><?=form_error('desc_web');?></span>
					</div>
					
						
                    </div>
					  <div class="col-md-6">
					  <div class="form-group">
                      <label for="exampleInputFile">Image</label>
                      <input type="file" id="exampleInputFile" name="sliderfile">
                      <span style="color: red;"><?=form_error('sliderfile');?></span>
                    </div>
					  
					<div class="form-group">
                      <label for="exampleInputFile">Choose Tests</label><span style="color:red">*</span>
						<select id="lstFruits" multiple="multiple" name="test[]">
					  
        <?php foreach($test as $ts){ ?>
		<option value="<?php echo $ts['id']; ?>"> <?php echo ucfirst($ts['test_name']); ?></option>
		<?php } ?>
       
    </select>
						<span style="color: red;"><?=form_error('test[]');?></span>
                    </div>
					<div class="form-group">
                      <label for="exampleInputFile">Description for Application</label><span style="color:red">*</span>
					<textarea id="editor2"  name="desc_app"> </textarea>
					<span style="color: red;"><?=form_error('desc_app');?></span>
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
			 
