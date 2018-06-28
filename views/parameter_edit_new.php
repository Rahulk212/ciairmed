<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<style>
    .chosen-container {
        display: inline-block;
        font-size: 14px;
        position: relative;
        vertical-align: middle;
        width: 100%;
    }
    .chosen-container .chosen-results li.active-result {width: 100% !important;}
</style>
<section class="content-header">
    <h1>
        Parameter
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>parameter_master/parameter_list">Parameter List</a></li>
        <li class="active">Add Parameter</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">

            <div class="box box-primary">
                <div class="widget">
                    <div class="alert alert-success alert-dismissable" id="msg_show" style="display: none;">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">X</button>
                        <span id='success_msg'></span>
                    </div>
                </div>

                <!-- form start -->
                <form role="form" action="<?php echo base_url(); ?>parameter_master/parameter_edit/<?php echo $pid; if($gid) { echo "/".$gid; } ?>" id="par_submit" method="post" enctype="multipart/form-data">

                    <div class="box-body">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputFile">Test <span style="color:red">*</span></label>
                                <select class="chosen" name="test" id="test">
                                    <option value="">--Select Test--</option>
                                    <?php foreach ($test_list as $test) { ?>
                                        <option value="<?php echo $test["id"] ?>" <?php if($query[0]['test_fk'] == $test['id']) { echo "selected"; } ?>><?php echo ucfirst($test["test_name"]); ?></option>
                                    <?php } ?>
                                </select>
                                <span style="color: red;"><?php echo form_error('test'); ?></span>
                                <span style="color:red;" id="test_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Parameter Name <span style="color:red">*</span></label>
                                <input type="text" id="par_name" name="par_name" class="form-control" placeholder="Parameter Name" value="<?php if($query[0]['g_id']) { echo ucfirst($query[0]['subparameter_name']); } else { echo ucfirst($query[0]['parameter_name']); } ?>">
                                <span style="color:red;" id="par_name_error"></span>
                            </div>
                            <?php
                            if($query[0]['g_id']) {
                                $rg_div = explode("-",$query[0]["subparameter_range"]);
                            } else {
                                $rg_div = explode("-",$query[0]["parameter_range"]);
                            }
                                ?>
                            <div class="form-group">
                                <label for="exampleInputFile">Parameter Minimum Range <span style="color:red">*</span></label>
                                <input type="text" id="par_range_minimum" name="par_range_minimum" class="form-control" placeholder="Minimun Range" value="<?php echo $rg_div[0]; ?>">
                                <span style="color:red;" id="par_range_minimum_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Parameter Maximum Range <span style="color:red">*</span></label>
                                <input type="text" id="par_range_maximum" name="par_range_maximum" class="form-control" placeholder="Maximum Range" value="<?php echo $rg_div[1]; ?>">
                                <span style="color:red;" id="par_range_maximum_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Parameter Unit <span style="color:red">*</span></label>
                                <input type="text" id="par_unit" name="par_unit" class="form-control" placeholder="Parameter Unit" value="<?php if($query[0]['g_id']) { echo ucfirst($query[0]['subparameter_unit']); } else { echo ucfirst($query[0]['parameter_unit']); } ?>">
                                <span style="color:red;" id="par_unit_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Add To Group</label>
                                <?php if($query[0]['g_id']) { ?>
                                <input type="radio" name="group_per" value="1" checked>Yes
                                <input type="radio" name="group_per" value="0">No
                                <?php } else { ?>
                                <input type="radio" name="group_per" value="1">Yes
                                <input type="radio" name="group_per" value="0" checked>No
                                <?php } ?>
                            </div>
                            <div id="group_create" <?php if($query[0]['g_id']) { } else { ?> style="display:none;" <?php } ?>>
                                <div class="form-group">
                                    <label for="exampleInputFile">Parameter Group</label>
                                    <select class="chosen" name="group_name" id="group_name">
                                        <option value="">--Select Parameter Group--</option>
                                        <?php foreach ($parameter_list as $parameter) { ?>
                                            <option value="<?php echo $parameter["id"] ?>" <?php if($query[0]['g_id']) { if($query[0]['parameter_fk'] == $parameter['id']) { echo "selected"; } } ?>><?php echo ucfirst($parameter["parameter_name"]); ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <label for="exampleInputFile">Or</label>
                                <div class="form-group">
                                    <label for="exampleInputFile">New Group Name <span style="color:red">*</span></label>
                                    <input type="text" id="new_group_name" name="new_group_name" class="form-control" placeholder="New Group Name">
                                    <span style="color:red;" id="par_group_name_error"></span>
                                </div>
                            </div>
                        </div>
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                        <div class="col-md-6">
                            <button class="btn btn-primary" type="button" onclick="submit_parameter();">submit</button>
                        </div>
                    </div>
                </form>
            </div><!-- /.box -->
            <script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
            <script  type="text/javascript">
                                $(function () {
                                    $('.chosen').chosen();
                                });

            </script>
            <script>
                $('input[type=radio][name=group_per]').change(function () {
                    if (this.value == 0) {
                        $("#group_create").hide();
                    } else if (this.value == 1) {
                        $("#group_create").show();
                    }
                });
                function add_parameter() {
                    var cnt = 0;
                    var test = $("#test").val();
                    var par_name = $("#par_name").val();
                    var par_range_minimum = $("#par_range_minimum").val();
                    var par_range_maximum = $("#par_range_maximum").val();
                    var par_unit = $("#par_unit").val();
                    var group_per = $("input[type=radio][name=group_per]:checked").val();
                    var par_group_name = $("#group_name").val();
                    var par_new_group_name = $("#new_group_name").val();
                    $("#test_error").html("");
                    $("#par_name_error").html("");
                    $("#par_range_minimum_error").html("");
                    $("#par_range_maximum_error").html("");
                    $("#par_unit_error").html("");
                    $("#par_group_name_error").html("");
                    if (group_per == 1) {
                        if (par_group_name == '' && par_new_group_name == '') {
                            cnt = cnt + 1;
                            $("#par_group_name_error").html("Select Group or Add New");
                        }
                    }
                    if (test == '') {
                        cnt = cnt + 1;
                        $("#test_error").html("Select Test");
                    }
                    if (par_name == '') {
                        cnt = cnt + 1;
                        $("#par_name_error").html("Required");
                    }
                    if (par_range_minimum == '') {
                        cnt = cnt + 1;
                        $("#par_range_minimum_error").html("Required");
                    }
                    if (par_range_maximum == '') {
                        cnt = cnt + 1;
                        $("#par_range_maximum_error").html("Required");
                    }
                    if (par_unit == '') {
                        cnt = cnt + 1;
                        $("#par_unit_error").html("Required");
                    }
                    if (cnt > 0) {
                        return false;
                    }
                    alert(group_per);
                    setTimeout(function () {
                        $.ajax({
                            url: "<?php echo base_url(); ?>parameter_master/add_parameter",
                            type: 'post',
                            data: {test: test, par_name: par_name, par_range_minimum: par_range_minimum, par_range_maximum: par_range_maximum, par_unit: par_unit, group_per: group_per, par_group_name: par_group_name, par_new_group_name: par_new_group_name},
                            success: function (data) {
                                if (data > 0) {
                                    $("#success_msg").html("Parameter Added");
                                    $("#msg_show").css("display", "block");
                                    $("#par_name").val('');
                                    $("#test").val('').trigger('chosen:updated');
                                    $("#par_range_minimum").val('');
                                    $("#par_range_maximum").val('');
                                    $("#par_unit").val('');
                                    $("input[type=radio][name=group_per]").val('');
                                    $("#group_name").val('').trigger('chosen:updated');
                                    $("#new_group_name").val('');
                                }
                            }
                        });
                    }, 500);
                }
                setTimeout(function () {
                    $("#msg_show").css("display", "none");
                }, 3000);
                function submit_parameter() {
                    var cnt = 0;
                    var test = $("#test").val();
                    var par_name = $("#par_name").val();
                    var par_range_minimum = $("#par_range_minimum").val();
                    var par_range_maximum = $("#par_range_maximum").val();
                    var par_unit = $("#par_unit").val();
                    var group_per = $("input[type=radio][name=group_per]:checked").val();
                    var par_group_name = $("#group_name").val();
                    var par_new_group_name = $("#new_group_name").val();
                    $("#test_error").html("");
                    $("#par_name_error").html("");
                    $("#par_range_minimum_error").html("");
                    $("#par_range_maximum_error").html("");
                    $("#par_unit_error").html("");
                    $("#par_group_name_error").html("");
                    if (group_per == 1) {
                        if (par_group_name == '' && par_new_group_name == '') {
                            cnt = cnt + 1;
                            $("#par_group_name_error").html("Select Group or Add New");
                        }
                    }
                    if (test == '') {
                        cnt = cnt + 1;
                        $("#test_error").html("Select Test");
                    }
                    if (par_name == '') {
                        cnt = cnt + 1;
                        $("#par_name_error").html("Required");
                    }
                    if (par_range_minimum == '') {
                        cnt = cnt + 1;
                        $("#par_range_minimum_error").html("Required");
                    }
                    if (par_range_maximum == '') {
                        cnt = cnt + 1;
                        $("#par_range_maximum_error").html("Required");
                    }
                    if (par_unit == '') {
                        cnt = cnt + 1;
                        $("#par_unit_error").html("Required");
                    }
                    if (cnt > 0) {
                        return false;
                    }
                    setTimeout(function () {
                        $("#par_submit").submit();
                    }, 500);
                }
            </script>
        </div>
    </div>
</section>
