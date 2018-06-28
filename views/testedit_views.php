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
        <li><a href="<?php echo base_url(); ?>b2b/Logistic_test_master/test_list"><i class="fa fa-users"></i>Test List</a></li>
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
                <form role="form" action="<?php echo base_url(); ?>b2b/Logistic_test_master/test_edit/<?= $cid ?>" method="post" enctype="multipart/form-data">

                    <div class="box-body">
                        <div class="col-md-6">
                            <?= validation_errors('<div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>', '</div>'); ?>
                           <div class="form-group">
                                <label for="exampleInputFile">Test</label><span style="color:red">*</span>
                                 <select class="form-control" name="test">
                                <option value="">--Select Test--</option>
								<?php foreach($test as $tesval){ if($tesval['test_name'] != ""){ ?>
								<option value="<?= $tesval['id']; ?>" <?php if($query[0]["test_fk"]==$tesval['id']){ echo "selected"; } ?> > <?= $tesval['test_name']; ?></option>
								<?php } } ?>
                                
                            </select>
                                <span style="color: red;"><?= form_error('test'); ?></span>
                         </div>

                            
                            <div class="form-group">
                                <label for="exampleInputFile">Description</label>
                                <textarea name="desc" class="form-control"><?php echo $query[0]["description"]; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Price</label><span style="color:red">*</span>
                                <input type="text"  name="price" class="form-control"  value="<?php echo $query[0]["price"]; ?>" >
                                <span style="color: red;"><?= form_error('price'); ?></span>
                            </div>
							<div class="form-group">
                                <label for="exampleInputFile">B2b Price</label><span style="color:red">*</span>
                                <input type="text"  name="b2bprice" class="form-control"  value="<?php echo $query[0]["b2b_price"]; ?>" >
                                <span style="color: red;"><?= form_error('b2bprice'); ?></span>
                            </div>
							
							<div class="form-group">
                                <label for="exampleInputFile">Special Price</label><span style="color:red">*</span>
                                <input type="text"  name="sprice" class="form-control"  value="<?php echo $query[0]["special_price"]; ?>" >
                                <span style="color: red;"><?= form_error('sprice'); ?></span>
                            </div>
<!--                            <div class="form-group">
                                <label for="exampleInputFile">Fasting Required</label><span style="color:red"></span>
                                <input type="checkbox" value="1" name="fasting" <?php
                                if ($query[0]["fasting_requird"] == 1) {
                                    echo "checked";
                                }
                                ?>/>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Is view</label>
                                <input type="checkbox" value="1" <?php
                                if ($query[0]["is_view"] == 1) {
                                    echo "checked";
                                }
                                ?> name="is_view"/>
                            </div>-->
<!--                            <p><b>Set Price:</b></p>            
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
                            <span style="color: red;"><?= form_error('price[]'); ?></span>-->

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
