<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<!-- Custom styles for this template -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
<link href="<?php echo base_url(); ?>/plugins/TimePicki-master/css/timepicki.css" rel="stylesheet">
<script>
	$(document).ready(function(){
		var date_input=$('input[name="adverdate"]'); //our date input has the name "date"
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
        Advertisement Add
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>adver_master/adver_list"><i class="fa fa-users"></i>Advertisement List</a></li>
        <li class="active">Advertisement</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <p class="help-block" style="color:red;"><?php
                    if (isset($error)) {
                        echo $error;
                    }
                    ?></p>
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
                        <!-- form start -->
                        <?php
                        echo form_open_multipart('adver_master/adver_add', ['method' => 'post', 'class' => 'form-horizontal',
                            'id' => 'target', 'enctype' => 'multipart/form-data']);
                        ?>
                        <div class="form-group">
                            <label for="start_time">Advertisement Title</label><span style="color:red">*</span>
                            <input type="text" name="ptitle" class="form-control" placeholder="Advertisement title"/>
                            <?php echo form_error('ptitle'); ?>
                        </div>
					    <div class="form-group">
                            <label for="exampleInputFile">Advertisement Logo</label><span style="color:red">*</span>
						    <input type="file" name="open"/>
					        <?php echo form_error('open'); ?>
                        </div>
						<div class="form-group">
							<label for="recipient-name" class="control-label">Date:</label>
                            <input type="text" id="dob1" name="adverdate" placeholder='MM-DD-YYYY' 
							class="datepicker form-control" value="" /> 
                        </div>
					
                    </div>
                </div>
                <div class="box-footer">
                    <button  class="btn btn-primary" name="button" type="submit">Add</button>			
                </div>
            </div>
            <?php echo form_close(); ?>
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
