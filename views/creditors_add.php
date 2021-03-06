<!-- Page Heading -->
<style>
    .multiselect-container {
        height: 120px; 
        overflow: auto;
    }
</style>
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<section class="content-header">
    <h1>
        Creditors
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>Creditors_master/"><i class="fa fa-users"></i>Creditors List</a></li>
        <li class="active">Add Creditors</li>
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

                <!-- form start -->
                <form role="form" action="<?php echo base_url(); ?>Creditors_master/add" method="post" enctype="multipart/form-data">

                    <div class="box-body">
                        <div class="col-md-6">
                            <!--<?= validation_errors('<div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>', '</div>'); ?>-->
                            <div class="form-group">
                                <label for="exampleInputFile">Creditors Name</label><span style="color:red">*</span>
                                <input type="text"  name="name" class="form-control"  value="<?php echo set_value('name'); ?>" >
                                <span style="color: red;"><?= form_error('name'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Email</label>
                                <input type="text"  name="email" class="form-control"  value="<?php echo set_value('email'); ?>" >
                                <span style="color: red;"><?= form_error('email'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Mobile</label><span style="color:red">*</span>
                                <input type="text"  name="mobile" class="form-control"  value="<?php echo set_value('mobile'); ?>" >
                                <span style="color: red;"><?= form_error('mobile'); ?></span>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputFile">Address</label>
                                <textarea name="address" class="form-control" rows=5><?php echo set_value('address'); ?></textarea>
                                <span style="color: red;"><?= form_error('address'); ?></span>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputFile">Branch</label>
                                <select class="multiselect-ui" name="branch[]" placeholder="Select Branch" multiple="">
                                    <?php foreach ($branch_list as $branch) { ?>
                                        <option value="<?php echo $branch->id; ?>"><?php echo $branch->branch_name; ?></option>
                                    <?php } ?>
                                </select>
                                <span style="color: red;"><?= form_error('branch'); ?></span>
                            </div>
                        </div>
                    </div><!-- /.box-body -->

                    <div class="box-footer">
                        <div class="col-md-6">
                            <button class="btn btn-primary" type="submit">Add</button>
                        </div>
                    </div>

                </form>
            </div><!-- /.box -->
            <script  type="text/javascript">
                $(document).ready(function () {
                    $("#showHide").click(function () {
                        if ($("#password").attr("type") == "password") {
                            $("#password").attr("type", "text");
                        } else {
                            $("#password").attr("type", "password");
                        }

                    });
                });
                function state_list(cid) {
                    $.ajax({
                        url: '<?php echo base_url(); ?>Creditors_master/city_state_list',
                        type: 'post',
                        data: {cid: cid},
                        success: function (data) {
                            $("#city_list").html(data);
                        }
                    });
                }
            </script>
            <script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap-multiselect.js"></script>
            <Script>
                $(function () {
                    $('.multiselect-ui').multiselect({
                        includeSelectAllOption: true,
                        nonSelectedText: 'Select Branch'
                    });
                });
            </script>
        </div>
    </div>
</section>
