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
        Mail Chimp
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>home"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li class="active">Mail Chimp</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-sm-6">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Start Campaigning </h3>
                    </div>
                    <p class="help-block" style="color:red;"><?php
                        if (isset($error)) {
                            echo $error;
                        }
                        ?></p>

                    <!-- form start -->
                    <form role="form" action="<?php echo base_url(); ?>time_slot_master/site_setting" method="post" enctype="multipart/form-data">

                        <div class="box-body">
                            <div class="col-md-12">
                                <?= validation_errors('<div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>', '</div>'); ?>

                                <div class="form-group">
                                    <label for="exampleInputFile">Select List</label><span style="color:red">*</span>
                                    <select id="" class="form-control" name="list">
                                        <option value="1">Customer</option>
                                        <option value="2">Phlebotomy</option>
                                        <option value="3">Labs</option>
                                        <option value="4">Doctors</option>
                                        <option value="5">Admin</option>

                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Select Template</label><span style="color:red">*</span>
                                    <select id="" class="form-control" name="template">
                                        <option value="1">--Select--</option>
                                        <?php foreach ($templete as $key) { ?>
                                            <option value="<?= $key["id"] ?>"><?= $key["title"] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div><!-- /.box-body -->
                        </div>
                        <div class="box-footer">
                            <div class="col-md-6">
                                <button class="btn btn-sm btn-primary" type="submit">Send Mail</button>
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
                </script>
            </div>
             
          <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Create List </h3>
                    </div>
                    <!-- form start -->
                    <form role="form" action="<?php echo base_url(); ?>job_master/add_slot" method="post" enctype="multipart/form-data">

                        <div class="box-body">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="exampleInputFile">Import Csv</label><span style="color:red">*</span>
                                    <input type="file" name="import_list" />
                                </div>
                            </div><!-- /.box-body -->
                        </div>
                        <div class="box-footer">
                            <div class="col-md-6">
                                <button class="btn btn-sm btn-primary" type="submit">Upload</button>
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
                </script>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Create Template </h3>
                    </div>
                    <?php if (isset($success) != NULL) { ?>
                        <div class="widget">
                            <div class="alert alert-success alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                <?php echo $success['0']; ?>
                            </div>
                        </div>
                    <?php } ?>

                    <!-- form start -->
                    <form role="form" action="<?php echo base_url(); ?>job_master/add_slot" method="post" enctype="multipart/form-data">

                        <div class="box-body">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="exampleInputFile">Title</label><span style="color:red">*</span>
                                    <input type="text" class="form-control" name="title" value="<?= set_value("title"); ?>" required=""/>
                                    <span style="color:red;"><?= form_error("title"); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Content</label><span style="color:red">*</span>
                                    <textarea id="editor1" class="form-control" name="content" required=""><?= set_value("content"); ?></textarea>
                                    <span style="color:red;"><?= form_error("content"); ?></span>
                                </div>
                            </div><!-- /.box-body -->
                        </div>
                        <div class="box-footer">
                            <div class="col-md-6">
                                <button class="btn btn-sm btn-primary" type="submit">Add</button>
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
                </script>
            </div>
             
        </div>

    </div>
</section>
</div>
</div>