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
        <li class="active">Edit Test</li>
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
                <form role="form" action="<?php echo base_url(); ?>test-master/test-edit/<?= $cid ?>" method="post" enctype="multipart/form-data">

                    <div class="box-body">
                        <div class="col-md-6">
                            <?= validation_errors('<div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>', '</div>'); ?>
                            <div class="form-group">
                                <label for="exampleInputFile">Test Name</label><span style="color:red">*</span>
                                <input type="text"  name="name" class="form-control"  value="<?php echo $query[0]["test_name"]; ?>" >
                            </div>
                            

                            <?php /*
                              <!--Nishit city wise price start-->
                              <div class="form-group">
                              <label for="exampleInputFile">A'bad Price</label><span style="color:red">*</span>
                              <input type="text"  name="price1" class="form-control"  value="<?php echo $query[0]["price"]; ?>" >
                              </div>
                              <table class="table table-bordered" id="city_wiae_price">
                              <tbody><tr>
                              <th>City</th>
                              <th>Price (INR)</th>
                              <th>Action</th>
                              </tr>
                              <?php $cnt=0; foreach($city_price as $key){ ?>
                              <tr id="tr_<?=$cnt;?>">
                              <td><?=$key["city_name"];?><input type="hidden" name="city[]" value="<?=$key["city_fk"];?>"/></td>
                              <td><?=$key["price"];?><input type="hidden" name="price[]" value="<?=$key["price"];?>"/></td>
                              <td><a href="javascript:void(0);" onclick="delete_city_price('<?=$cnt;?>')">Delete</a></td>
                              </tr>
                              <?php $cnt++; } ?>
                              <!-- Default -->
                              </tbody></table>
                              <a href="javascript:void(0);" onclick="$('#exampleModal').modal('show');"><i class="fa fa-plus-square" style="font-size:20px;"></i></a>
                              <!--Nishit city wise price end-->
                             * 
                             */ ?>
                            <div class="form-group">
                                <label for="exampleInputFile">Description</label>
                                <textarea name="desc" class="form-control"> <?php echo $query[0]["description"]; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Fasting Required</label><span style="color:red"></span>
                                <input type="checkbox" value="1" name="fasting" <?php
                                if ($query[0]["fasting_requird"] == 1) {
                                    echo "checked";
                                }
                                ?>/>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Is view</label>
                                <input type="checkbox" value="1" <?php if ($query[0]["is_view"] == 1) {
                                           echo "checked";
                                       } ?> name="is_view"/>
                            </div>
                            <p><b>Set Price:</b></p>            
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>City</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
<?php foreach ($test_city as $key) { ?>
                                        <tr>
                                            <td><?= $key["name"]; ?></td>
                                            <td>
                                                <?php
                                                $cnt = 0;
                                                foreach ($city_price as $tkey) {
                                                    if ($key["id"] == $tkey["id"]) {
                                                        ?> 
                                                        Rs. <input type="text" name="price[]" id="price_<?= $key['id'] ?>" value="<?= $tkey["price"] ?>">
                                                        <input type="hidden" name="city[]" value="<?= $key['id'] ?>"/>
                                                        <?php
                                                        $cnt++;
                                                    }
                                                    ?>
                                                <?php }
                                                ?>
                                                <?php if ($cnt == 0) { ?>
                                                    Rs. <input type="text" name="price[]" id="price_<?= $key['id'] ?>" value="">
                                                    <input type="hidden" name="city[]" value="<?= $key['id'] ?>"/>
                                        <?php } ?>
                                            </td> 
                                        </tr>
<?php } ?>
                                </tbody>
                            </table>
                            <span style="color: red;"><?= form_error('price[]'); ?></span>

                            <?php /* <!--Model start-->
                              <?php foreach ($city_price as $key) { ?>
                              <div class="form-group">
                              <label for="exampleInputFile"><?= ucfirst($key["name"]); ?> Price</label><span style="color:red">*</span>
                              <input type="text"  name="price[]" class="form-control"  value="<?= $key["price"] ?>">
                              <input type="hidden" name="city[]" value="<?= $key["id"] ?>"/>
                              </div>
                              <?php } ?>
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

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputFile">Test Department</label><span style="color:red">*</span>
                                <select class="form-control" id="department_ids" name="department">
                                    <option value="">Select Department</option>
                                    <?php foreach ($departmnet_list as $department) { ?>
                                        <option value="<?php echo $department['id']; ?>" <?php if($department['id'] == $query[0]['department_fk']) { echo "selected"; } ?>><?php echo ucwords($department['name']); ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Sample for Analysis</label>
                                <textarea name="sample_analysis"  class="form-control"><?php echo $query[0]["sample_for_analysis"]; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">ECD/Container for Primary Sampling</label>
                                <textarea name="primary_sampling"  class="form-control"><?php echo $query[0]["container_for_primary_sampling"]; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Method</label>
                                <textarea name="method"  class="form-control"><?php echo $query[0]["method"]; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Temp</label>
                                <input type="text"  name="temp" class="form-control"  value="<?php echo $query[0]["temp"]; ?>" >
                                <span style="color: red;"><?= form_error('temp'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Cut off (HRS)</label>
                                <input type="text"  name="cut_off" class="form-control"  value="<?php echo $query[0]["cut_off"]; ?>" >
                                <span style="color: red;"><?= form_error('cut_off'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Schedule</label>
                                <input type="text"  name="schedule" class="form-control"  value="<?php echo $query[0]["schedule"]; ?>" >
                                <span style="color: red;"><?= form_error('schedule'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Reporting</label>
                                <input type="text"  name="reporting" class="form-control"  value="<?php echo $query[0]["reporting"]; ?>" >
                                <span style="color: red;"><?= form_error('reporting'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Special Instructions</label><span style="color:red"></span>
                                <textarea name="special_instuction"  class="form-control"><?php echo $query[0]["special_instruction"]; ?></textarea>
                            </div>
                        </div>
                    </div><!-- /.box-body -->

                    <div class="box-footer">
                        <div class="col-md-6">
                            <button class="btn btn-primary" type="submit">Update</button>
                        </div>
                    </div>

                </form>
            </div><!-- /.box -->
            <script>
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
