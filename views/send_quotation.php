<!-- Add fancyBox main JS and CSS files -->
<script type="text/javascript" src="<?php echo base_url(); ?>source/jquery.fancybox.pack.js?v=2.1.5"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>source/jquery.fancybox.css?v=2.1.5" media="screen" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<!-- Add Button helper (this is optional) -->
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>source/helpers/jquery.fancybox-buttons.css?v=1.0.5" />
<script type="text/javascript" src="<?php echo base_url(); ?>source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>

<!-- Add Thumbnail helper (this is optional) -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" />
<script type="text/javascript" src="<?php echo base_url(); ?>source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>

<!-- Add Media helper (this is optional) -->
<script type="text/javascript" src="<?php echo base_url(); ?>source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script type="text/javascript" src="<?php echo base_url(); ?>source/jquery.fancybox.pack.js?v=2.1.5"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>source/jquery.fancybox.css?v=2.1.5" media="screen" />
<script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css" />

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content"  style="padding: 30px">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <?php echo form_open("User_call_master/send_quotation1/", array("method" => "POST", "id" => "send_to_form")); ?>
                    <div class="box-header">
                        <h3 class="box-title">Quote Manage</h3>
                    </div>
                    <div class="box-body">
                        <div class="col-sm-12">
                            <div id="edit_div">
                                <div class="col-sm-12" style="padding-left:0">
                                    <input type="hidden" name="caller_id" value="<?= $caller_id ?>"/>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <select id="selected_city" name="test_city" class="chosen" style="width:500px;"  onchange="change_test_city(this.value);">
                                                <option value="">--Select City--</option>
                                                <?php foreach ($test_city as $key) { ?>
                                                    <option value="<?= $key["id"] ?>" <?php
                                                    if ($pending_quotation[0]["test_city"] == $key["id"]) {
                                                        echo "selected";
                                                    }
                                                    ?>><?= $key["name"] ?></option>
                                                        <?php } ?>
                                            </select>
                                            <span style="color:red;" id="test_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <select name="add_test" id="add_test" class="chosen" style="width:500px;" onchange="get_test_price();">
                                            </select>
                                            <span style="color:red;" id="test_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <input type="text" value="<?= $cller_details[0]["CallFrom"] ?>" name="mobile_no" style="width:230px;" placeholder="Mobile No." class="form-control"/>
                                            <span style="color:red;" id="test_error"></span>
                                        </div>
                                    </div>
                                </div>
                                <table class="table table-bordered table-striped" id="city_wiae_price">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Price</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="selected_test_list">
                                        <?php
                                        $cnt = 0;
                                        $selected_test = array();
                                        $main_price = 0;
                                        foreach ($query as $value) {
                                            if ($value["is_send"] == 0) {
                                                foreach ($value["test_details"] as $t_key) {
                                                    //echo $t_key["test_name"] . "(Rs." . $t_key["price"] . "),";
                                                    $selected_test[] = array("cnt" => $cnt, "id" => "t-" . $t_key["id"]);
                                                    $main_price = $main_price + $t_key["price"];
                                                    ?>
                                                    <tr id="tr_<?= $cnt ?>">
                                                        <td>
                                                            <?= ucfirst($t_key["test_name"]); ?>
                                                        </td>
                                                        <td>
                                                            Rs.<?= ucfirst($t_key["price"]); ?>
                                                        </td>
                                                        <td>
                                                            <a href="javascript:void(0);" onclick="delete_city_price('<?= $cnt ?>', '<?= ucfirst($t_key["price"]); ?>', '<?= "t-" . $value["id"]; ?>')">Delete</a>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    $cnt++;
                                                }
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <div id="hidden_test">
                                    <?php foreach ($selected_test as $key) { ?>
                                        <input id="tr1_<?= $key["cnt"] ?>" name="test[]" value="<?= $key["id"] ?>" type="hidden">
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <b>Total Price :- </b>Rs.<span id="total_price"><?= $main_price; ?></span>
                        <input type="hidden" name="total_price" id="hidden_total_price" value="<?= $main_price; ?>"/>
                        <input style="float:right;" class="btn btn-primary" value="Send" onclick="submit_form();" id="add_sub_parameter" type="button">
                        <span id="loader_div21" style="display:none;"><img src="<?= base_url(); ?>upload/opc-ajax-loader.gif" style="height:height:28px;float:right;"></span>
                        <br>
                    </div>
                </div>
                <?= form_close(); ?>
                <h3>Send Quote History</h3>
                <table class="table table-bordered table-striped" id="datatable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Mobile No.</th>
                            <th>Test</th>
                            <th>Test City</th>
                            <th>Total Price</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $cnt = 1;
                        foreach ($query as $key) {
                            if ($key["is_send"] == 1) {
                                ?>
                                <tr>
                                    <td><?= $cnt; ?></td>
                                    <td><?= $key["mobile_no"]; ?></td>
                                    <td><?php
                                        foreach ($key["test_details"] as $t_key) {
                                            echo $t_key["test_name"] . "(Rs." . $t_key["price"] . "),";
                                        }
                                        ?></td>
                                    <td><?= $key["test_city_name"]; ?></td>
                                    <td>Rs.<?= $key["price"]; ?></td>
                                </tr>
                                <?php
                                $cnt++;
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#datatable').DataTable();
    });
    $main_price = <?= $main_price ?>;
    $new_price = <?= $main_price ?>;
    $total_price = <?= $main_price ?>;
    function UpdateJobTest() {
        var exist_test_ids = $("input[name='test[]']")
                .map(function () {
                    return $(this).val();
                }).get();
        var prc_variation = parseInt($new_price) - parseInt($main_price);
        console.log($main_price + " main");
        console.log($new_price + " new");
        console.log(prc_variation + " def");
        var cnf = confirm("Price difference is Rs." + prc_variation + ", Are you sure?");
        if (cnf == true) {
            $.ajax({
                url: '<?php echo base_url(); ?>job_master/update_job_test',
                type: 'post',
                data: {selected: exist_test_ids, jid: '<?php echo $jid; ?>'},
                success: function (data) {
                    if (data.trim() == 1) {
                        parent.close_popup();
                    } else {
                        alert("Oops somthing wrong.Try again.");
                    }
                },
                error: function (jqXhr) {
                    $("#add_test").html("");
                },
                complete: function () {
                },
            });
        }

    }
    $city_cnt = <?= $cnt ?>;
    /*  var exist_test_ids = $("input[name='test[]']")
     .map(function () {
     return $(this).val();
     }).get();
     $.ajax({
     url: '<?php echo base_url(); ?>Admin/get_test_list',
     type: 'post',
     data: {selected: exist_test_ids},
     success: function (data) {
     var json_data = JSON.parse(data);
     $("#add_test").html(json_data.test_list);
     $('.chosen').trigger("chosen:updated");
     },
     error: function (jqXhr) {
     $("#add_test").html("");
     },
     complete: function () {
     },
     }); */
    function change_test_city(val) {
        $city_cnt = <?= $cnt ?>;
        var exist_test_ids = $("input[name='test[]']")
                .map(function () {
                    return $(this).val();
                }).get();
        $.ajax({
            url: '<?php echo base_url(); ?>Admin/get_quote_test_list',
            type: 'post',
            data: {selected: exist_test_ids, city_fk: val},
            success: function (data) {
                var json_data = JSON.parse(data);
                $("#add_test").html(json_data.test_list);
                $('.chosen').trigger("chosen:updated");
                $("#selected_test_list").html("");
                $("#hidden_test").html("");
                $("#total_price").html("0");
                $("#hidden_total_price").val("0");
                $total_price = 0;
            },
            error: function (jqXhr) {
                $("#add_test").html("");
            },
            complete: function () {
            },
        });
    }
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
<script  type="text/javascript">

    jQuery(".chosen-select").chosen({
        search_contains: true,
        width: "100%"
    });
    $(function () {
        $('.chosen').chosen({
            search_contains: true,
            width: "100%"
        });
    });
    function get_test_price() {
        var test_val = $("#add_test").val();
        $("#test_error").html("");
        var cnt = 0;
        if (test_val.trim() == '') {
            $("#test_error").html("Test is required.");
            cnt = cnt + 1;
        }
        if (cnt > 0) {
            return false;
        }
        var skillsSelect = document.getElementById("add_test");
        var selectedText = skillsSelect.options[skillsSelect.selectedIndex].text;
        var prc = selectedText.split('(Rs.');

        var prc1 = prc[1].split(')');
        var pm = skillsSelect.value;
        var explode = pm.split('-');
        $total_price = +$total_price + +prc1[0];
        $("#total_price").html($total_price);
        $("#hidden_total_price").val($total_price);
        if (explode[0] == 'p') {
            /*show_details(explode[1]);
             var clic = "'" + explode[1] + "'";
             $("#city_wiae_price").append('<tr id="tr_' + $city_cnt + '"><td><a href="#" data-toggle="modal" data-target="#myModal_view" onclick="show_details(' + clic + ');">' + prc[0] + '</a></td><td>Rs.' + prc1[0] + '</td><td><a href="javascript:void(0);" onclick="delete_city_price(\'' + $city_cnt + '\',\'' + prc1[0] + '\',\'' + prc[0] + '\',\'' + skillsSelect.value + '\')">Delete</a></td></tr>');
             */
            $("#city_wiae_price").append('<tr id="tr_' + $city_cnt + '"><td>' + prc[0] + '</td><td>Rs.' + prc1[0] + '</td><td><a href="javascript:void(0);" onclick="delete_city_price(\'' + $city_cnt + '\',\'' + prc1[0] + '\',\'' + skillsSelect.value + '\')">Delete</a></td></tr>');
        } else {
            $("#city_wiae_price").append('<tr id="tr_' + $city_cnt + '"><td>' + prc[0] + '</td><td>Rs.' + prc1[0] + '</td><td><a href="javascript:void(0);" onclick="delete_city_price(\'' + $city_cnt + '\',\'' + prc1[0] + '\',\'' + skillsSelect.value + '\')">Delete</a></td></tr>');
        }
        $("#add_test option[value='1']").remove();
        var old_dv_txt = $("#hidden_test").html();
        $new_price = parseInt($new_price) + parseInt(prc1[0]);
        /*Total price calculate start*/
        var old_price = $("#total_id").val();
        $("#total_id").val(+old_price + +prc1[0]);
        var dscnt = $("#discount").val();
        //get_discount_price(dscnt);
        /*Total price calculate end*/
        $("#hidden_test").html(old_dv_txt + '<input id="tr1_' + $city_cnt + '" type="hidden" name="test[]" value="' + skillsSelect.value + '"/>');
        $city_cnt = $city_cnt + 1;
        $("#desc").val("");
        $("#add_test option[value='" + skillsSelect.value + "']").remove();
        $("#add_test").val('').trigger('chosen:updated');
    }
    function delete_city_price(id, prc, value) {
        var tst = confirm('Are you sure?');
        if (tst == true) {
            /*Total price calculate start*/
            //$('#add_test').append('<option value="' + value + '">' + name + ' (Rs.' + prc + ')</option>').trigger("chosen:updated");
            var old_price = $("#total_id").val();
            $("#total_id").val(old_price - prc);
            var dscnt = $("#discount").val();
            //get_discount_price(dscnt);
            /*Total price calculate end*/
            $("#tr_" + id).remove();
            $("#tr1_" + id).remove();
            $total_price = $total_price - prc;
            $("#total_price").html($total_price);
            $("#hidden_total_price").val($total_price);
            var exist_test_ids = $("input[name='test[]']")
                    .map(function () {
                        return $(this).val();
                    }).get();
            var tst_cty = $("#selected_city").val();
            $.ajax({
                url: '<?php echo base_url(); ?>Admin/get_test_list/' + tst_cty,
                type: 'post',
                data: {selected: exist_test_ids},
                success: function (data) {
                    var json_data = JSON.parse(data);
                    $("#add_test").html(json_data.test_list);
                    $('.chosen').trigger("chosen:updated");
                },
                error: function (jqXhr) {
                    $("#add_test").html("");
                },
                complete: function () {
                },
            });
        }
        $new_price = parseInt($new_price) - parseInt(prc);
        setTimeout(function () {
            get_price();
        }, 1000);
    }
    function show_details(val) {
        $.ajax({
            url: "<?php echo base_url(); ?>service/service_v2/package_details",
            type: "POST",
            data: {pid: val},
            error: function (jqXHR, error, code) {
            },
            success: function (data) {
                var json_data = JSON.parse(data);
                $("#description").empty();
                $("#description").append('<p>' + json_data.data + '</p>');
                // console.log(data);
            }
        });
    }
    function submit_form() {
        var frm_data = $("#send_to_form");
        $.ajax({
            url: "<?= base_url(); ?>User_call_master/send_quotation1",
            type: "POST",
            data: frm_data.serializeArray(),
            beforeSend: function () {
                $("#loader_div21").attr("style", "");
                $("#add_sub_parameter").attr("disabled", "disabled");
            },
            error: function (jqXHR, error, code) {
            },
            /*headers: {
             'Access-Control-Allow-Origin': '*',
             'Authorization': '95e84166c3097122cb4ef144a68626f6',
             'Origin': 'http://beta.labforsure.com'
             },*/
            success: function (data) {
                parent.close_frame();
            }, complete: function () {
                $("#loader_div21").attr("style", "display:none;");
                $("#add_sub_parameter").removeAttr("disabled");
            }
        });
    }
<?php if ($pending_quotation[0]["test_city"] != '') { ?>

        var exist_test_ids = $("input[name='test[]']")
                .map(function () {
                    return $(this).val();
                }).get();
        $.ajax({
            url: '<?php echo base_url(); ?>Admin/get_quote_test_list',
            type: 'post',
            data: {selected: exist_test_ids, city_fk: <?= $pending_quotation[0]["test_city"] ?>},
            success: function (data) {
                var json_data = JSON.parse(data);
                $("#add_test").html(json_data.test_list);
                $('.chosen').trigger("chosen:updated");
            },
            error: function (jqXhr) {
                $("#add_test").html("");
            },
            complete: function () {
            },
        });
<?php } ?>
</script>
<div class="modal fade" id="myModal_view" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content srch_popup_full">
            <div class="modal-header srch_popup_full srch_head_clr">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title clr_fff">Package Detail</h4>
            </div>
            <div class="modal-body srch_popup_full">
                <div class="srch_popup_full srch_popup_acco">
                    <div id="accordion1" class="panel-group accordion transparent">
                        <div class="panel">
                            <div class="panel-collapse collapse in" role="tablist" aria-expanded="true">
                                <div class="panel-content" id="description">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
