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
              Site Setting
              <small></small>
            </h1>
            <ol class="breadcrumb">
				<li><a href="<?php echo base_url(); ?>home"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>admin_master/admin_list"><i class="fa fa-users"></i>Admin</a></li>
              <li class="active">Site Setting</li>
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
                <form role="form" action="<?php echo base_url(); ?>time_slot_master/site_setting" method="post" enctype="multipart/form-data">
					
                  <div class="box-body">
                    <div class="col-md-6">
						<?= validation_errors('<div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>', '</div>'); ?>

                    <div class="form-group">
                      <label for="exampleInputFile">Select Site Open Days</label><span style="color:red">*</span>
                      <select id="lstFruits" multiple="multiple" name="days[]">
					  
					  <option value="1" <?php if(in_array(1,$days)) { echo "selected"; }?>>Monday</option>
					  <option value="2" <?php if(in_array(2,$days)) { echo "selected"; }?>>Tuesday</option>
					  <option value="3" <?php if(in_array(3,$days)) { echo "selected"; }?>>Wednesday</option>
					  <option value="4" <?php if(in_array(4,$days)) { echo "selected"; }?>>Thrusday</option>
					  <option value="5" <?php if(in_array(5,$days)) { echo "selected"; }?>>Friday</option>
					  <option value="6" <?php if(in_array(6,$days)) { echo "selected"; }?>>Saturday</option>
					  <option value="0" <?php if(in_array(0,$days)) { echo "selected"; }?>>sunday</option>
       
		
       
    </select>
                    </div>
					                    <div class="form-group">
										  <label for="exampleInputFile">Text For main Page </label><span style="color:red">*</span>
										  <textarea name="main_txt" class="form-control"> <?php echo $query[0]['main_text']; ?> </textarea> 
										</div>
                    
                  </div><!-- /.box-body -->
</div>
                  <div class="box-footer">
					  <div class="col-md-6">
                    <button class="btn btn-primary" type="submit">Save</button>
                  </div>
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
			 