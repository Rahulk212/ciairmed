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

<!-- Add jQuery library -->
<script type="text/javascript" src="<?php echo base_url(); ?>lib/jquery-1.10.2.min.js"></script>

<!-- Add mousewheel plugin (this is optional) -->
<script type="text/javascript" src="<?php echo base_url(); ?>lib/jquery.mousewheel.pack.js?v=3.1.3"></script>

<!-- Add fancyBox main JS and CSS files -->
<script type="text/javascript" src="<?php echo base_url(); ?>source/jquery.fancybox.pack.js?v=2.1.5"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>source/jquery.fancybox.css?v=2.1.5" media="screen" />

<!-- Add Button helper (this is optional) -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>source/helpers/jquery.fancybox-buttons.css?v=1.0.5" />
<script type="text/javascript" src="<?php echo base_url(); ?>source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>

<!-- Add Thumbnail helper (this is optional) -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" />
<script type="text/javascript" src="<?php echo base_url(); ?>source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>

<!-- Add Media helper (this is optional) -->
<script type="text/javascript" src="<?php echo base_url(); ?>source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>

<script type="text/javascript">
    var jq = $.noConflict();
    jq(document).ready(function () {
        /*
         *  Simple image gallery. Uses default settings
         */

        jq(".fancybox-effects-a").fancybox({
            helpers: {
                title: {
                    type: 'outside'
                },
                overlay: {
                    speedOut: 0
                }
            }
        });
    });
</script>
<style>
    .desc {border: 1px solid #eee;height: 100px;overflow-y: scroll;padding: 10px;width: 100%;}
    .invoice .page-header > img {height: 50px; width:auto;}
    .bootstrap-select:not([class*="col-"]):not([class*="form-control"]):not(.input-group-btn) {width:100% !important;} .bootstrap-select.btn-group .dropdown-menu{width:100%;}
</style>

<div class="widget">
</div>

<p class="help-block" style="color:red;"><?php
    if (isset($error)) {
        echo $error;
    }
    ?></p>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Prescription
            <!--<small>#007612</small>-->
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Examples</a></li>
            <li class="active">Invoice</li>
        </ol>
    </section>

    <!-- <div class="pad margin no-print">
       <div class="callout callout-info" style="margin-bottom: 0!important;">												
         <h4><i class="fa fa-info"></i> Note:</h4>
         This page has been enhanced for printing. Click the print button at the bottom of the invoice to test.
       </div>
     </div>-->

    <!-- Main content -->
    <section class="invoice">
        <!-- title row -->
        <div class="row">
            <?php if (isset($success) != NULL) { ?>
                <div class="alert alert-success alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <?php echo $success['0']; ?>
                </div>
            <?php } ?>
            <div class="col-xs-12">
                <h2 class="page-header">
                    <?php if ($query[0]['pic'] != NULL) { ?>
                        <?php if ($query[0]['password'] == NULL || $query[0]['type'] == '2') { ?>
                            <img src="<?php echo $query[0]['pic']; ?>"/>
                        <?php } else { ?>
                            <img src="<?php echo base_url(); ?>upload/<?php echo $query[0]['pic']; ?>"/>
                            <?php
                        }
                    } else {
                        ?>
                        <i class="fa fa-user"></i>
                    <?php } ?>
                    <a href="<?php echo base_url(); ?>customer-master/customer-details/<?php echo $query[0]['cid']; ?>"> <?php echo ucfirst($query[0]['full_name']); ?></a>
                    <small class="pull-right">Date: <?php echo $query[0]['created_date']; ?></small>
                </h2>
            </div><!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">

                <strong>Description</strong><br>
                <p class="desc"> <?php echo ucwords($query[0]['description']); ?></p>
            </div><!-- /.col -->
            <div class="col-sm-4 invoice-col">
                <strong><?php echo ucfirst($query[0]['full_name']); ?></strong><br>
                <?php foreach ($state as $ts) { ?>
                    <?php
                    if ($query[0]['state'] != '' && $ts['id'] == $query[0]['state']) {
                        echo "<b>State: </b>";
                        echo ucfirst($ts['state_name']);
                    }
                    ?> <?php ?>
                <?php } ?><br/>


                <?php   foreach ($city1 as $ts) { ?>
                    <?php
					
                    if ($query[0]['ucity'] != '' && $ts['id'] == $query[0]['ucity']) {
                        echo "<b>City: </b> ";
                        echo ucfirst($ts['city_name']);
                    }
                    ?> <?php ?>
                <?php } ?>

                <br>
                <?php
                if ($query[0]['address']) {
                    echo "<b>Address:</b>";
                    echo $query[0]['address'];
                }
                ?><br/>
                <!-- <b>Payment Due:</b> 2/22/2014<br/>
                 <b>Account:</b> 968-34567 -->
            </div><!-- /.col -->
            <div class="col-sm-4 invoice-col">
                <?php /* <img style="height:130px;width:130px;;border-radius: 50%;"  src="<?php echo base_url(); ?>upload/<?php if($query[0]['pic'] != ''){ echo $query[0]['pic']; }else{ echo "01461246201nobody_m_original.jpg"; } ?>"/> */ ?>

            </div><!-- /.row -->
            <div style="clear:both"></div>
            <!-- Table row -->
            <div class="">
                <form role="form" action="<?php echo base_url(); ?>job_master/suggest_test/<?= $cid ?>" method="post" enctype="multipart/form-data">
                    <div class="col-xs-8 table-responsive">
                        <br><p class="lead">Suggested Test: <button class="btn btn-primary pull-right" onclick="show_test_model();" type="button" style="margin-right:20px"><i class="fa fa-plus-square" style="font-size:20px;"></i>   Add Test</button></p>

                        <table class="table table-striped" id="city_wiae_price">
                            <thead>
                                <tr>
                                    <th>Test</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                //$pids = array();
                                $cnt = 0;
                                foreach ($test_check as $ts1) {
                                    //array_push($pids, $ts1['test_id']);
                                    ?>
                                    <tr id="tr_<?= $cnt ?>">
                                        <td><?= $ts1["test_name"] . " (Rs." . $ts1["price"] . ")"; ?><input type="hidden" class="hidden_test" name="test[]" value="<?= $ts1["test_id"]; ?>"/></td>
                                        <td><?= $ts1["description"]; ?><textarea style="display:none;" name="desc[]"><?= $ts1["description"]; ?></textarea></td>
                                        <td><a href="javascript:void(0);" onclick="delete_city_price('<?= $cnt ?>')"> Delete</a></td>
                                    </tr>
                                    <?php
                                    $cnt++;
                                }
                                ?>

                            </tbody>
                        </table>
                    </div><!-- /.col -->
                    <div class="col-xs-4 table-responsive">
                        <br><p class="lead">Prescription Image:</p>
                        <a class="fancybox-effects-a" title="<?php echo $query[0]['image']; ?>" href="<?php echo base_url(); ?>upload/<?php echo $query[0]['image']; ?>"><img style="height:200px;width:auto;"  src="<?php echo base_url(); ?>upload/<?php echo $query[0]['image']; ?>"/></a>
                    </div>  
                    <div class="col-xs-12">
                        <button class="btn btn-primary" id="suggst_btn" <?php if($query[0]['status'] == "1") { ?> disabled="true" <?php } ?> style="margin-bottom:20px"><i class="fa fa-check-square-o"></i> Suggest</button><br/>
                    </div>
                </form>
            </div><!-- /.row -->

            <div class="">

                <!-- accepted payments column -->
                <?php /* <div class="col-xs-6">
                  <p class="lead">Payment Methods:</p>
                  <img src="../../dist/img/credit/visa.png" alt="Visa"/>
                  <img src="../../dist/img/credit/mastercard.png" alt="Mastercard"/>
                  <img src="../../dist/img/credit/american-express.png" alt="American Express"/>
                  <img src="../../dist/img/credit/paypal2.png" alt="Paypal"/>
                  <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                  Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles, weebly ning heekya handango imeem plugg dopplr jibjab, movity jajah plickers sifteo edmodo ifttt zimbra.
                  </p>
                  </div><!-- /.col --> */ ?>
                <form role="form" action="<?php echo base_url(); ?>job_master/book_from_prescription/<?= $cid ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden"  id="" name="userid" value="<?php echo $query[0]['cid'] ?>"  class="form-control"/>
                    <input type="hidden"  id="testid_split" name="testids"  class="form-control"/>

                    <div class="col-xs-12">
                        <div class="col-xs-12 col-md-6" style="padding:0">
                            <p class="lead">Amount</p>
                            <div class="table-responsive">
                                <table class="table">
                                    <tr>
                                        <th>Discount(%) :</th>
                                        <td><input type="text" onkeyup="get_discount_price(this.value);" name="discount" id="discount" class="form-control"/></td>
                                    </tr>
                                    <tr>
                                        <th>Payable Amount :</th>
                                        <td><input type="text" name="payable" id="payable" class="form-control"/></td>
                                    </tr>
                                    <tr>
                                        <th>Total Amount: Rs. <span id="spn_total_id">0</span></th>
                                        <th><input type="hidden"  id="txt_total"  class="form-control"/></th>
                                    </tr>
                                </table>
                            </div>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
            </div>

            <!-- this row will not appear when printing -->
            <div class=" no-print">
                <div class="col-xs-12">
                    <button class="btn btn-primary" type="submit" <?php if($query[0]['status'] == "1") { ?> disabled="true" <?php } ?> id="book_btn"><i class="fa fa-check-square-o"></i> Book</button>
                </div>
            </div>
            </form>
            <!--Model start-->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="exampleModalLabel">Add Test</h4>
                        </div>
                        <div class="modal-body">
                            <?php /*
                              <div class="form-group">
                              <label for="recipient-name" class="control-label">City:</label>
                              <br>
                              <select class="selectpicker" data-live-search="true" id="cityget">
                              <option value="">--Select--</option>
                              <?php foreach ($city as $ci) { ?>
                              <option value="<?= $ci['id']; ?>"><?= $ci['name']; ?></option>
                              <?php } ?>
                              </select>
                              <br><span style="color:red;" id="city_error"></span>
                              </div> */ ?>
                            <div class="form-group">
                                <label for="recipient-name" class="control-label">Test:</label>
                                <br>
								<div id="tlist">
                                <select class="selectpicker" data-live-search="true" id="test" data-placeholder="Select Test">
                                    <option value="">--Select Test--</option>
                                    <?php foreach ($test as $ts) { ?>
                                        <option value="<?php echo $ts['id']; ?>" <?php ?>> <?php echo ucfirst($ts['test_name']); ?> (Rs.<?php echo $ts['price']; ?>)</option>
                                    <?php } ?>
                                </select>
								</div>
                                <br><span style="color:red;" id="test_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="message-text" class="control-label">Description</label>
                                <textarea id="desc" class="form-control"></textarea>
                                <!--<span style="color:red;" id="desc_error"></span>-->
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
    </section><!-- /.content -->
    <div class="clearfix"></div>
</div><!-- /.content-wrapper -->
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
<script  type="text/javascript">

                                jQuery(".chosen-select").chosen({
                                    search_contains: true
                                });
                                //  $(".chosen-select-deselect").chosen({ allow_single_deselect: true });
                                // $("#cid").chosen('refresh');

</script> 

<script  type="text/javascript">

    $("#cityget").chosen().change(function () {
        var id = $(this).val();

        $.ajax({url: "<?php echo base_url(); ?>job_master/get_test/" + id,
            error: function (jqXHR, error, code) {
            }, success: function (data) {

                var jsonrespone = JSON.parse(data);
                var len = jsonrespone.length;
                $('#test').empty();
                var text1 = $('#test');
                for (var i = 0; i < len; i++) {
                    var testid = jsonrespone[i].testid;
                    var testname = jsonrespone[i].testname;
                    var testprice = jsonrespone[i].testprice;

                    text1.append("<option val=" + testid + ">" + testname + "(RS." + testprice + ")</otion>");

                }



            }
        });

    });

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
            },
            success: function (data) {

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
        //$("#desc_error").html("");
        var cnt = 0;
        if (test_val.trim() == '') {
            $("#test_error").html("Test is required.");
            cnt = cnt + 1;
        }
        var desc_val = $("#desc").val();
        if (desc_val.trim() == '') {
            //$("#desc_error").html("Desscription is required.");
            //cnt = cnt + 1;
        }
        if (cnt > 0) {
            return false;
        }
        var skillsSelect = document.getElementById("test");
        var selectedText = skillsSelect.options[skillsSelect.selectedIndex].text;
        $("#city_wiae_price").append('<tr id="tr_' + $city_cnt + '"><td>' + selectedText + '<input type="hidden" class="hidden_test" name="test[]" value="' + skillsSelect.value + '"/></td><td>' + desc_val + '<textarea style="display:none;" name="desc[]">' + desc_val + '</textarea></td><td><a href="javascript:void(0);" onclick="delete_city_price(\'' + $city_cnt + '\')">Delete</a></td></tr>');
        $("#test option[value='1']").remove();
        $city_cnt = $city_cnt + 1;
        $("#test").val("");
        $("#desc").val("");
        setTimeout(function () {
            get_price();
        }, 1000);
        $('#exampleModal').modal('hide');
		$("#suggst_btn").prop("disabled", false);
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
	function show_test_model(){
		var values = $("input[name='test[]']").map(function(){
			return $(this).val();
		}).get();
		$.ajax({
            url: "<?php echo base_url(); ?>job_master/get_test_list/",
            type: "POST",
            data: {ids: values,pid: <?php echo $cid;?>},
            error: function (jqXHR, error, code) {
            },
            success: function (data) {
			console.log(data);	
            }
        });
		$('#exampleModal').modal('show');
			console.log(values);
	}
</script>