<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<section class="content-header">
    <h1>
        Test
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>test-master/test-list"><i class="fa fa-users"></i>Test List</a></li>
        <li class="active">Add Test</li>
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
                <form role="form" action="<?php echo base_url(); ?>test-master/test-add" method="post" enctype="multipart/form-data">

                    <div class="box-body">
                        <div class="col-md-6">
                            <!--<?= validation_errors('<div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>', '</div>'); ?>-->
                            <div class="form-group">
                                <label for="exampleInputFile">Test Name</label><span style="color:red">*</span>
                                <input type="text"  name="name" class="form-control"  value="<?php echo set_value('name'); ?>" >
                                <span style="color: red;"><?= form_error('name'); ?></span>
                            </div>
                            
                            <!--Nishit city wise price start-->
                            <div class="form-group">
                                <label for="exampleInputFile">Description</label><span style="color:red"></span>
                                <textarea name="desc"  class="form-control"> <?php echo set_value('desc'); ?></textarea>
                            </div>
                            <div class="form-group">
                                <br>
                                <label for="exampleInputFile">Fasting Required</label><span style="color:red"></span>
                                <input type="checkbox" value="1" name="fasting"/>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Is view</label>
                                <input type="checkbox" value="1" name="is_view"/>
                            </div>
                            <?php /*
                              <div class="form-group">
                              <label for="exampleInputFile">A'bad Price</label><span style="color:red">*</span>
                              <input type="text"  name="price1" class="form-control"  value="<?php echo set_value('price1'); ?>" >
                              </div>
                              <table class="table table-bordered" id="city_wiae_price">
                              <tbody><tr>
                              <th>City</th>
                              <th>Price (INR)</th>
                              <th>Action</th>
                              </tr>
                              <!-- Default -->
                              </tbody></table>
                              <a href="javascript:void(0);" onclick="$('#exampleModal').modal('show');"><i class="fa fa-plus-square" style="font-size:20px;"></i></a>
                              <div class="form-group">
                              <label for="exampleInputFile"><?= ucfirst($key["name"]); ?> City</label><span style="color:red">*</span>
                              <select class="form-control" name="city">
                              <option value="">SELECT</option><
                              <?php foreach ($citys as $key) { ?>
                              <option value="<?= $key["id"] ?>"><?= ucfirst($key["name"]); ?></option>
                              <?php } ?></select>
                              <span style="color: red;"><?= form_error('city'); ?></span>
                              </div>
                              <div class="form-group">
                              <label for="exampleInputFile"> Price</label><span style="color:red">*</span>
                              <input type="text"  name="price" class="form-control"  value="">
                              <span style="color: red;"><?= form_error('price'); ?></span>
                              </div> */ ?>
                            <p><b>Set Price:</b></p>            
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>City</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($citys as $key) { ?>
                                        <tr>
                                            <td><?= $key["name"]; ?></td>
                                            <td><input type="text" name="price[]" id="price_<?= $key['id'] ?>" value=""><input type="hidden" name="city[]" value="<?= $key['id'] ?>"/></td> 
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <span style="color: red;"><?= form_error('price[]'); ?></span>
                            <!--Nishit city wise price end-->
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputFile">Test Department</label><span style="color:red">*</span>
                                <select class="form-control" id="department_ids" name="department">
                                    <option value="">Select Department</option>
                                    <?php foreach ($departmnet_list as $department) { ?>
                                        <option value="<?php echo $department['id']; ?>"><?php echo ucwords($department['name']); ?></option>
                                    <?php } ?>
                                </select>
                                <span style="color: red;"><?= form_error('department'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Sample for Analysis</label>
                                <textarea name="sample_analysis"  class="form-control"><?php echo set_value('sample_analysis'); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">ECD/Container for Primary Sampling</label>
                                <textarea name="primary_sampling"  class="form-control"><?php echo set_value('primary_sampling'); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Method</label>
                                <textarea name="method"  class="form-control"><?php echo set_value('method'); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Temp</label>
                                <input type="text"  name="temp" class="form-control"  value="<?php echo set_value('temp'); ?>" >
                                <span style="color: red;"><?= form_error('temp'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Cut off (HRS)</label>
                                <input type="text"  name="cut_off" class="form-control"  value="<?php echo set_value('cut_off'); ?>" >
                                <span style="color: red;"><?= form_error('cut_off'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Schedule</label>
                                <input type="text"  name="schedule" class="form-control"  value="<?php echo set_value('schedule'); ?>" >
                                <span style="color: red;"><?= form_error('schedule'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Reporting</label>
                                <input type="text"  name="reporting" class="form-control"  value="<?php echo set_value('reporting'); ?>" >
                                <span style="color: red;"><?= form_error('reporting'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Special Instructions</label><span style="color:red"></span>
                                <textarea name="special_instuction"  class="form-control"><?php echo set_value('special_instuction'); ?></textarea>
                            </div>
                        </div>
                    </div><!-- /.box-body -->
                    <?php /*  <!--Model start-->
                      <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
                      <div class="modal-dialog" role="document">
                      <div class="modal-content">
                      <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <h4 class="modal-title" id="exampleModalLabel">Add price</h4>
                      </div>
                      <div class="modal-body">
                      <div class="form-group">
                      <label for="recipient-name" class="control-label">City:</label>
                      <br>
                      <select class="selectpicker" data-live-search="true" id="city">
                      <option value="">--Select--</option>
                      <?php foreach ($city_list as $key) { if($key["id"]!='333'){ ?>
                      <option value="<?= $key["id"]; ?>"><?= ucfirst($key["city_name"]) . " (" . ucfirst($key["state_name"]) . ")"; ?></option>
                      <?php } } ?>
                      </select>
                      <br><span style="color:red;" id="city_error"></span>
                      </div>
                      <div class="form-group">
                      <label for="message-text" class="control-label">Price (INR):</label>
                      <input type="text" id="price" class="form-control"/>
                      <span style="color:red;" id="price_error"></span>
                      </div>
                      </div>
                      <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-primary" onclick="get_city_price();">Add</button>
                      </div>
                      </div>
                      </div>
                      </div>
                      <!--Model end-->
                     */ ?>
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
</section>
