<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<section class="content-header">
    <h1>
        Unit Edit
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>unit_master/"><i class="fa fa-users"></i>Unit List</a></li>

        <li class="active">Edit Unit</li>
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
                            $id = $data['ID'];
                        {
                            ?> 
                            <!-- form start -->
                            <?php
                            echo form_open_multipart('unit_master/unit_edit/' . $id . '', ['method' => 'post', 'class' => 'form-horizontal',
                                'id' => 'target', 'enctype' => 'multipart/form-data']);
                            ?>
                            <div class="form-group">
                                <label for="name"> Unit Name<span style="color:red;">*</span></label>
                                <?php
                                echo form_input(['name' => 'pname', 'class' => 'form-control', 'placeholder' => 'Unit Name',
                                    'value' => $data['PARAMETER_NAME']]);
                                ?>
    <?php echo form_error('pname'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button  class="btn btn-primary" name="button" type="submit">Update</button>
                        <input type="hidden" id="idh" name="idh" value="<?php echo $id; ?>">
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
