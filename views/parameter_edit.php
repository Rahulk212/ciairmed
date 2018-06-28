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
        <li class="active">Edit Parameter</li>
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
                <form role="form" action="<?php echo base_url(); ?>parameter_master/parameter_edit/<?= $cid ?>" method="post" enctype="multipart/form-data">

                    <div class="box-body">
                        <div class="col-md-6">
                            <?php // echo validation_errors('<div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>', '</div>'); ?>
                            <div class="form-group">
                                <label for="exampleInputFile">Test</label><span style="color:red">*</span>
                                <select class="chosen" name="test">
                                    <option value="">--Select Test--</option>
                                    <?php foreach ($test_list as $test) { ?>
                                        <option value="<?php echo $test['id']; ?>" <?php
                                        if ($query[0]['test_fk'] == $test['id']) {
                                            echo "selected";
                                        }
                                        ?>><?php echo $test['test_name']; ?></option>
                                            <?php } ?>
                                </select>
                                <span style="color: red;"><?php echo form_error('test'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Parameter Name</label><span style="color:red">*</span>
                                <input type="text"  name="par_name" class="form-control"  value="<?php echo $query[0]["parameter_name"]; ?>" >
                                <span style="color: red;"><?php echo form_error('par_name'); ?></span>
                            </div>
                            <?php if($query[0]['parameter_range'] != '') { ?>
                            <div class="form-group">
                                <div class="col-md-6">
                                    <button class="btn btn-primary" type="button" onclick="simple_parameter();">Simple Parameter</button>
                                </div> <br><br>
                            </div>
                            <div id="show_value">
                            <div class="form-group">
                                <label for="exampleInputFile">Parameter Range</label><span style="color:red">*</span>
                                <?php
                                $range = $query[0]["parameter_range"];
                                $rg_div = explode("-",$query[0]["parameter_range"]);
                                ?>
                                <input type="text"  name="par_range_minimum" class="form-control" placeholder="Minimun Range" value="<?php echo $rg_div[0]; ?>" >
                                <span style="color: red;"><?php echo form_error('par_range_minimum'); ?></span>
                                <input type="text"  name="par_range_maximum" class="form-control" placeholder="Maximum Range" value="<?php echo $rg_div[1]; ?>" >
                                <span style="color: red;"><?php echo form_error('par_range_maximum'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Parameter Unit</label><span style="color:red">*</span>
                                <input type="text"  name="par_unit" class="form-control"  value="<?php echo $query[0]["parameter_unit"]; ?>" >
                                <span style="color: red;"><?php echo form_error('par_unit'); ?></span>
                            </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div><!-- /.box-body -->

                    <div class="box-footer">
                        <div class="col-md-6">
                            <button class="btn btn-primary" type="submit">Update</button>
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
                function simple_parameter() {
                    $("#show_value").hide();
                }
                $city_cnt = <?= $cnt; ?>;
                function get_city_price() {
                    var city_val = $("#city").val();
                    $("#city_error").html("");
                    $("#price_error").html("");
                    var cnt = 0;
                    if (city_val.trim() == '') {
                        $("#city_error").html("City is required.");
                        cnt = cnt + 1;
                    }
                    var price_val = $("#price").val();
                    if (!CheckNumber(price_val)) {
                        $("#price_error").html("Invalid price.");
                        cnt = cnt + 1;
                    }
                    if (cnt > 0) {
                        return false;
                    }
                    var skillsSelect = document.getElementById("city");
                    var selectedText = skillsSelect.options[skillsSelect.selectedIndex].text;
                    $("#city_wiae_price").append('<tr id="tr_' + $city_cnt + '"><td>' + selectedText + '<input type="hidden" name="city[]" value="' + skillsSelect.value + '"/></td><td>' + price_val + '<input type="hidden" name="price[]" value="' + price_val + '"/></td><td><a href="javascript:void(0);" onclick="delete_city_price(\'' + $city_cnt + '\')">Delete</a></td></tr>');
                    $city_cnt = $city_cnt + 1;
                    $("#price").val("");
                    $('#exampleModal').modal('hide');

                }

                function CheckNumber(nmbr) {
                    var filter = /^[0-9-+]+$/;
                    if (filter.test(nmbr)) {
                        return true;
                    } else {
                        return false;
                    }
                }

                function delete_city_price(id) {
                    var tst = confirm('Are you sure?');
                    if (tst == true) {
                        $("#tr_" + id).remove();
                    }
                }
            </script>
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
</section>
