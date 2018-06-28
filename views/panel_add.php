<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<section class="content-header">
    <h1>
        Panel
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>Test_panel/test_panel_list"><i class="fa fa-users"></i>Panel List</a></li>
        <li class="active">Add Panel</li>
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
                <form role="form" action="<?php echo base_url(); ?>Test_panel/panel_add" method="post" enctype="multipart/form-data">

				  <div class="box-body">
                        <div class="col-md-6">
                           <div class="form-group">
                                <label for="exampleInputFile">Test Panel Name</label><span style="color:red">*</span>
                                <input type="text"  name="name" class="form-control"  placeholder ="Test Panel Name" value="<?php echo set_value('name'); ?>" >
                                <?php echo form_error('name'); ?>
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
            <script>
                $city_cnt = 0;
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
    </div>
</section>
