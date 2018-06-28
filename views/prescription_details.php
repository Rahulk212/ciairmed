<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="http://cdn.rawgit.com/davidstutz/bootstrap-multiselect/master/dist/css/bootstrap-multiselect.css"
      rel="stylesheet" type="text/css" />
<script src="http://cdn.rawgit.com/davidstutz/bootstrap-multiselect/master/dist/js/bootstrap-multiselect.js"
type="text/javascript"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />

<!-- Page Heading -->
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<!-- Add fancyBox main JS and CSS files -->
<script type="text/javascript" src="<?=base_url();?>fancybox/jquery.fancybox.pack.js?v=2.1.5"></script>
<link rel="stylesheet" type="text/css" href="<?=base_url();?>fancybox/jquery.fancybox.css?v=2.1.5" media="screen" />

<section class="content-header">
    <h1>
        Prescription Details
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>job-master/prescription-report"><i class="fa fa-users"></i>Prescription List</a></li>
        <li class="active">Prescription Details</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">

            <div class="box box-primary">
                <div class="widget">
                    <?php if (isset($success) != NULL) { ?>
                        <div class="alert alert-success alert-dismissable">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            <?php echo $success['0']; ?>
                        </div>
                    <?php } ?>

                </div>

                <p class="help-block" style="color:red;"><?php
                    if (isset($error)) {
                        echo $error;
                    }
                    ?></p>

                <!-- form start -->
                <div class="box-body">
                    <div class="col-md-6">
                        <?= validation_errors('<div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>', '</div>'); ?>

                        <div class="form-group">
                            <label for="exampleInputFile">Customer Name :</label> <a href="<?php echo base_url(); ?>customer-master/customer-details/<?php echo $query[0]['cid']; ?>"> <?php echo ucfirst($query[0]['full_name']); ?></a>


                        </div>
                        <div class="form-group">
                            <label for="exampleInputFile">Description :</label> <?php echo ucwords($query[0]['description']); ?>


                        </div>

                        <div class="form-group">
                            <label for="exampleInputFile">Date :</label> <?php echo $query[0]['created_date']; ?>


                        </div>
                        <div class="form-group">
                            <label for="exampleInputFile">State :</label>
                            <select  class="form-control chosen-select" tabindex="-1"  data-placeholder="Select State">
                                <option value="">--Select--</option> 
                                <?php foreach ($state as $ts) { ?>
                                    <option value="<?php echo $ts['id']; ?>" <?php
                                    if ($query[0]['state'] != '' && $ts['id'] == $query[0]['state']) {
                                        echo "selected";
                                    }
                                    ?>> <?php echo ucfirst($ts['state_name']); ?></option>
                                        <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputFile">City :</label>
                            <select  class="form-control chosen-select" tabindex="-1"  data-placeholder="Select City">
                                <option value="">--Select--</option>
                                <?php foreach ($city as $ts) { ?>
                                    <option value="<?php echo $ts['id']; ?>" <?php
                                    if ($query[0]['city'] != '' && $ts['id'] == $query[0]['city']) {
                                        echo "selected";
                                    }
                                    ?>> <?php echo ucfirst($ts['city_name']); ?></option>
                                        <?php } ?>

                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputFile">Address :</label> <textarea name="desc" class="form-control"> <?php echo $test_check[0]['address']; ?> </textarea>

                        </div>




                    </div>
                    <div class="col-md-6">

                        <img style="width:500px;"  src="<?php echo base_url(); ?>upload/<?php echo $query[0]['image']; ?>"/>
                        <!--<a class="fancybox" href="<?php echo base_url(); ?>upload/<?php echo $query[0]['image']; ?>" data-fancybox-group="gallery" title=""><img src="<?php echo base_url(); ?>upload/<?php echo $query[0]['image']; ?>" alt="" /></a>-->
                    </div>
                </div><!-- /.box-body -->

                <!-- Other BOX-->

                <div class="box box-primary">

                    <!-- form start -->
                    <form role="form" action="<?php echo base_url(); ?>job_master/suggest_test/<?= $cid ?>" method="post" enctype="multipart/form-data">

                        <div class="box-body">
                            <div class="col-md-6">

                                <?php /* <!--<div class="form-group">
                                  <label for="exampleInputFile">Suggested Test : </label><span style="color:red">*</span>
                                  <?php
                                  $pids = array();
                                  foreach ($test_check as $ts1) {
                                  array_push($pids, $ts1['test_id']);
                                  }
                                  ?>
                                  <select id="multipletest" class="form-control chosen-select" tabindex="-1"  data-placeholder="Select Test" multiple name="test[]" onchange="get_price();" >
                                  <?php foreach ($test as $ts) { ?>
                                  <option value="<?php echo $ts['id']; ?>" <?php
                                  if (in_array($ts['id'], $pids)) {
                                  echo "selected";
                                  }
                                  ?>> <?php echo ucfirst($ts['test_name']); ?> (Rs.<?php echo $ts['price']; ?>)</option>
                                  <?php } ?>
                                  </select>
                                  </div>

                                  <div class="form-group">
                                  <label for="exampleInputFile">Description : </label>
                                  <textarea name="desc" class="form-control"> <?php echo $test_check[0]['description']; ?> </textarea>

                                  </div>--> */ ?>
                                <!--Nishit city wise price start-->
                                <h3>Suggested Test</h3>
                                <table class="table table-bordered" id="city_wiae_price">
                                    <tbody><tr>
                                            <th style="width:40%">Test</th>
                                            <th>Description</th>
                                            <th>Action</th>
                                        </tr>
                                        <?php
                                        //$pids = array();
                                        $cnt = 0;
                                        foreach ($test_check as $ts1) {
                                            //array_push($pids, $ts1['test_id']);
                                            ?>
                                            <tr id="tr_<?= $cnt ?>">
                                                <td><?= $ts1["test_name"] . " (Rs." . $ts1["price"] . ")"; ?><input type="hidden" class="hidden_test" name="test[]" value="<?= $ts1["test_id"]; ?>"/></td>
                                                <td><?= $ts1["description"]; ?><textarea style="display:none;" name="desc[]"><?= $ts1["description"]; ?></textarea></td>
                                                <td><a href="javascript:void(0);" onclick="delete_city_price('<?= $cnt ?>')">Delete</a></td>
                                            </tr>
                                            <?php
                                            $cnt++;
                                        }
                                        ?>
                                        <!-- Default -->
                                    </tbody></table>
                                <a href="javascript:void(0);" onclick="$('#exampleModal').modal('show');"><i class="fa fa-plus-square" style="font-size:20px;"></i></a>
                                <!--Nishit city wise price end-->
                                <div class="box-footer">
                                    <div class="col-md-6">
                                        <button class="btn btn-primary" type="submit">Update</button>
                                    </div>
                                </div>
                                </form>


                            </div>
                            <div class="col-md-6">
                                <form role="form" action="<?php echo base_url(); ?>job_master/book_from_prescription/<?= $cid ?>" method="post" enctype="multipart/form-data">
                                    <input type="hidden"  id="" name="userid" value="<?php echo $query[0]['cid'] ?>"  class="form-control"/>
                                    <input type="hidden"  id="testid_split" name="testids"  class="form-control"/>
                                    <div class="form-group">
                                        <label for="exampleInputFile">Total Price : </label>
                                        Rs. <span id="spn_total_id">0</span>
                                        <input type="hidden"  id="txt_total"  class="form-control"/>

                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputFile">Discount(%) : </label>
                                        <input type="text" onkeyup="get_discount_price(this.value);" name="discount" id="discount" class="form-control"/>

                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputFile">Payable Amount : </label>
                                        <input type="text" name="payable" id="payable" class="form-control"/>

                                    </div>
                                    <div class="col-md-6">
                                        <button class="btn btn-primary" type="submit">Book</button>
                                    </div>
                                </form>
                            </div>
                        </div>



                        <!-- END Other-->

                        <!--Model start-->
                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="exampleModalLabel">Add price</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="recipient-name" class="control-label">Test:</label>
                                            <br>
                                            <select class="selectpicker" data-live-search="true" id="test">
                                                <option value="">--Select--</option>
                                                <?php foreach ($test as $ts) { ?>
                                                    <option value="<?php echo $ts['id']; ?>" <?php
                                                    ?>> <?php echo ucfirst($ts['test_name']); ?> (Rs.<?php echo $ts['price']; ?>)</option>
                                                        <?php } ?>
                                            </select>
                                            <br><span style="color:red;" id="test_error"></span>
                                        </div>
                                        <div class="form-group">
                                            <label for="message-text" class="control-label">Description</label>
                                            <textarea id="desc" class="form-control"></textarea>
                                            <span style="color:red;" id="desc_error"></span>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary" onclick="get_test_price();">Add</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Model end-->

                </div><!-- /.box -->

                <script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
                <script  type="text/javascript">

                                            jQuery(".chosen-select").chosen({
                                                search_contains: true
                                            });
                                            //  $(".chosen-select-deselect").chosen({ allow_single_deselect: true });
                                            // $("#cid").chosen('refresh');

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
                    function get_state(val) {

                        $.ajax({
                            url: "<?php echo base_url(); ?>location_master/get_state/" + val,
                            error: function (jqXHR, error, code) {
// alert("not show");
                            },
                            success: function (data) {
//     console.log("data"+data);
                                document.getElementById('state').innerHTML = "";
                                document.getElementById('state').innerHTML = data;

                            }
                        });

                    }



                    function get_price() {

                        var foo = [];
                        //$('#multipletest :selected').each(function (i, selected) {
                        //    foo[i] = $(selected).val();
                        //});
                        /*Nishit code start*/
                        /*var values = $('input[name="test[]"]').map(function () {
                         //return this.value
                         foo[] = this.value;
                         }).get();*/
                        /*var values = document.getElementsByClassName("hidden_test");
                         for (var i = 0; values.length < i; ++i) {
                         foo[i] = values[i].value;
                         }*/

                        var elements = document.getElementsByClassName('hidden_test');
                        for (var i = 0; i < elements.length; ++i) {
                            foo[i] = elements[i].value;
                        }
                        /*Nishit code end*/
                        $.ajax({
                            url: "<?php echo base_url(); ?>job_master/get_price/",
                            type: "POST",
                            data: {ids: foo},
                            error: function (jqXHR, error, code) {

                            },
                            success: function (data) {

                                document.getElementById('spn_total_id').innerHTML = "";
                                document.getElementById('spn_total_id').innerHTML = data;
                                var idsall = foo.toString();
                                $("#testid_split").val(idsall);
                                $("#txt_total").val('');
                                $("#discount").val('');
                                $("#txt_total").val(data);
                                $("#payable").val(data);


                            }
                        });

                    }

                    function get_discount_price(val) {

                        var total = $("#txt_total").val();
                        var dis = val;

                        var discountpercent = val / 100;
                        var discountprice = (total * discountpercent);
                        var payableamount = total - discountprice;
                        $("#payable").val(payableamount);

                    }
                </script>


                <script>
                    $city_cnt = <?= $cnt ?>;
                    function get_test_price() {
                        var test_val = $("#test").val();
                        $("#test_error").html("");
                        $("#desc_error").html("");
                        var cnt = 0;
                        if (test_val.trim() == '') {
                            $("#test_error").html("Test is required.");
                            cnt = cnt + 1;
                        }
                        var desc_val = $("#desc").val();
                        if (desc_val.trim() == '') {
                            $("#desc_error").html("Desscription is required.");
                            cnt = cnt + 1;
                        }
                        if (cnt > 0) {
                            return false;
                        }
                        var skillsSelect = document.getElementById("test");
                        var selectedText = skillsSelect.options[skillsSelect.selectedIndex].text;
                        $("#city_wiae_price").append('<tr id="tr_' + $city_cnt + '"><td>' + selectedText + '<input type="hidden" class="hidden_test" name="test[]" value="' + skillsSelect.value + '"/></td><td>' + desc_val + '<textarea style="display:none;" name="desc[]">' + desc_val + '</textarea></td><td><a href="javascript:void(0);" onclick="delete_city_price(\'' + $city_cnt + '\')">Delete</a></td></tr>');
                        $city_cnt = $city_cnt + 1;
                        $("#test").val("");
                        $("#desc").val("");
                        setTimeout(function () {
                            get_price();
                        }, 1000);
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
                        setTimeout(function () {
                            get_price();
                        }, 1000);
                    }
                    setTimeout(function () {
                        get_price();
                    }, 1000);
                </script>
            </div>
        </div>
</section>
