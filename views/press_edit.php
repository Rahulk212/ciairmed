<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>

<script>
	$(document).ready(function(){
		var date_input=$('input[name="pressdate"]'); //our date input has the name "date"
		var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
		date_input.datepicker({
			format: 'dd-mm-yyyy',
			container: container,
			todayHighlight: true,
			autoclose: true,
		})
	})
</script>
<section class="content-header">
    <h1>
        Package Category Edit
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>press_media_master/press_list/"><i class="fa fa-users"></i>Package Category List</a></li>

        <li class="active">Edit Package Category</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="col-md-6">
                        <!-- form start -->
                        <?php $success = $this->session->flashdata('success'); ?>
                        <?php if (isset($success) != NULL) { ?>
                            <div class="alert alert-success alert-autocloseable-success">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                <?php echo $this->session->userdata('success'); ?>
                            </div>
                        <?php } ?>
                        <?php
                        foreach ($view_data as $data)
                            $id =$view_data->id;
                        {
                            ?> 
                            <!-- form start -->
                            <?php
                            echo form_open_multipart('press_media_master/press_edit/' . $id . '', ['method' => 'post', 'class' => 'form-horizontal',
                                'id' => 'target', 'enctype' => 'multipart/form-data']);
                            ?>
                            <div class="form-group">
                                <label for="name">Press Media Title<span style="color:red;">*</span></label>
                                <?php
                                echo form_input(['name' => 'ptitle', 'class' => 'form-control', 'placeholder' => 'Package Category Name',
                                    'value' => $view_data->title]);
                                ?>
                             <?php echo form_error('pcname'); ?>
                            </div>
						    
							 <div class="form-group">
                                <label for="name">Package Category Link<span style="color:red;">*</span></label>
                                <?php
                                echo form_input(['name' => 'plink', 'class' => 'form-control', 'placeholder' => 'Package Category Name',
                                    'value' => $view_data->link]);
                                ?>
                             <?php echo form_error('pcname'); ?>
                            </div>
							
							<div class="form-group">
                                <label for="exampleInputFile">Package Category Image</label><span style="color:red">*</span>
                                <input type="file"  name="open">
                                <?php //echo form_error('open', '<div class="error" style="color:red;">', '</div>'); ?>
                            </div>
							
								<div class="form-group">
						<?php 
						if($view_data-> date =='0000-00-00')
						{
							$dob="00-00-0000";
						}else{
							$dob = date_format(date_create_from_format('Y-m-d',
									$view_data->date), 'd-m-Y');
						}
						?>
							<label for="recipient-name" class="control-label">Date:</label>
                            <input type="text" id="dob1" name="pressdate" placeholder='MM-DD-YYYY' 
							class="datepicker form-control" value="<?php echo $dob; ?>" />
                        </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button  class="btn btn-primary" name="button" type="submit">Update</button>
                        
                    </div>
                </div>
                <?php echo form_close(); ?>
                <?php
            }
            ?>
            <script type="text/javascript">
                window.setTimeout(function () {
                    $(".alert").fadeTo(500, 0).slideUp(500, function () {
                        $(this).remove();
                    });
                }, 4000);
            </script>
        </div>
    </div>
            </section>
