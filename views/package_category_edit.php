<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<section class="content-header">
    <h1>
        Package Category Edit
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>unit_master/"><i class="fa fa-users"></i>Package Category List</a></li>

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
                            echo form_open_multipart('package_category/package_category_edit/' . $id . '', ['method' => 'post', 'class' => 'form-horizontal',
                                'id' => 'target', 'enctype' => 'multipart/form-data']);
                            ?>
                            <div class="form-group">
                                <label for="name">Package Category Name<span style="color:red;">*</span></label>
                                <?php
                                echo form_input(['name' => 'pcname', 'class' => 'form-control', 'placeholder' => 'Package Category Name',
                                    'value' => $view_data->name]);
                                ?>
                             <?php echo form_error('pcname'); ?>
                            </div>
						    <div class="form-group">
                                <label for="exampleInputFile">Package Category Image</label><span style="color:red">*</span>
                                <input type="file"  name="open">
                                <?php //echo form_error('open', '<div class="error" style="color:red;">', '</div>'); ?>
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
