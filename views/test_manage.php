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
<script src="//cdn.ckeditor.com/4.5.8/full/ckeditor.js"></script>
<style>
    .chosen-container .chosen-results li.active-result{width:auto;}
    .chosen-container{width:100%;}

</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Test Manage</h3>
                    </div>
                    <div class="box-body">
                        <div class="col-sm-12">
                            <div id="edit_div">
                                <div class="col-sm-12" style="padding-left:0">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <select name="add_test" id="add_test" class="chosen" style="width:500px;" required="" onchange="get_test_price();">
                                            </select>
                                            <span style="color:red;" id="test_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <select class="chosen chosen-select" data-live-search="true" name="panellist" id="panels" data-placeholder="Select Test" onchange="get_panel_test();">
                                                <option value='0'>Select Panel</option>
                                                <?php foreach ($panel_list as $panel) { ?>
                                                    <option value="<?= $panel["id"] ?>"><?= ucfirst($panel["name"]); ?></option>
                                                <?php } ?>
                                            </select>
                                            <div id="search_test_panel">
                                                <select class="chosen chosen-select" data-live-search="true" id="test_panel"  onchange="get_test_price1();" data-placeholder="Select Test" onchange="get_panel_price();">

                                                </select>
                                            </div>
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
                                    <tbody>
                                        <?php
                                        $cnt = 0;
                                        $selected_test = array();
                                        $main_price = 0;
                                        //$job_details[0]["test_city"]
                                        foreach ($job_details[0]["book_test"] as $value) {
                                            $is_panel = 0;
                                            if (empty($value["panel_fk"])) {
                                                $selected_test[] = array("cnt" => $cnt, "id" => "t-" . $value["id"]);
                                            } else {
                                                $is_panel = 1;
                                                $selected_test[] = array("cnt" => $cnt, "id" => "pt-" . $value["id"] . "-" . $value["panel_fk"]);
                                            }
                                            $main_price = $main_price + $value["price"];
                                            ?>
                                            <tr id="tr_<?= $cnt ?>">
                                                <td>
                                                    <?php
                                                    echo ucfirst($value["test_name"]);
                                                    if ($is_panel == 1) {
                                                        echo " <b>(Panel)</b>";
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    Rs.<?= ucfirst($value["price"]); ?>
                                                </td>
                                                <td>
                                                    <a href="javascript:void(0);" onclick="delete_city_price('<?= $cnt ?>', '<?= ucfirst($value["price"]); ?>', '<?= ucfirst($value["test_name"]); ?>', '<?= $value["id"]; ?>')">Delete</a>
                                                </td>
                                            </tr>
                                            <?php
                                            $cnt++;
                                        }
                                        ?>
                                        <?php
                                        foreach ($job_details[0]["book_package"] as $value) {
                                            $selected_test[] = array("cnt" => $cnt, "id" => "p-" . $value["id"]);
                                            $main_price = $main_price + $value["d_price"];
                                            ?>
                                            <tr id="tr_<?= $cnt ?>">
                                                <td>
                                                    <?= ucfirst($value["title"]); ?>
                                                </td>
                                                <td>
                                                    Rs.<?= ucfirst($value["d_price"]); ?>
                                                </td>
                                                <td>
                                                    <a href="javascript:void(0);" onclick="delete_city_price('<?= $cnt ?>', '<?= ucfirst($value["d_price"]); ?>', '<?= ucfirst($value["title"]); ?>', '<?= $value["id"]; ?>')">Delete</a>
                                                </td>
                                            </tr>
                                            <?php
                                            $cnt++;
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
                        <input style="float:right;" class="btn btn-primary" value="Update" id="add_sub_parameter" type="button" onclick="UpdateJobTest();">
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript">
    $main_price = <?= $main_price ?>;
    $new_price = <?= $main_price ?>;
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
                data: {selected: exist_test_ids, jid: '<?php echo $jid; ?>',bid:'<?php echo $job_details[0]["branch_fk"]; ?>'},
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
    var exist_test_ids = $("input[name='test[]']")
            .map(function () {
                return $(this).val();
            }).get();
    $.ajax({
        url: '<?php echo base_url(); ?>Admin/get_test_list/<?php echo $job_details[0]["test_city"]; ?>/<?php echo $job_details[0]["branch_fk"]; ?>',
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
            jQuery('.chzn-drop').css({"width": "300px"});

            function get_panel_test() {

                var panels = $("#panels").val();
                $.ajax({
                    url: '<?php echo base_url(); ?>test_panel/get_panel_test_list/' + panels,
                    type: 'post',
                    data: {panelid: panels, test_city: '<?php echo $job_details[0]["test_city"]; ?>'},
                    success: function (data) {
                        var json_data = JSON.parse(data);
                        $("#panelt_body").html("");
                        //$("#hidden_test").html("");
                        //$("#discount").html("0");
                        //$("#payable_val").val("0");
                        /// $("#total_id").val("0");
                        $("#test_panel").html("");
                        $("#test_panel").html(json_data.test_list);
                        paneltst_details = json_data.test_ary;
                        $('.chosen').trigger("chosen:updated");
                    },
                    complete: function () {
                    },
                });

            }

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
                if (explode[0] == 'p') {
                    /*show_details(explode[1]);
                     var clic = "'" + explode[1] + "'";
                     $("#city_wiae_price").append('<tr id="tr_' + $city_cnt + '"><td><a href="#" data-toggle="modal" data-target="#myModal_view" onclick="show_details(' + clic + ');">' + prc[0] + '</a></td><td>Rs.' + prc1[0] + '</td><td><a href="javascript:void(0);" onclick="delete_city_price(\'' + $city_cnt + '\',\'' + prc1[0] + '\',\'' + prc[0] + '\',\'' + skillsSelect.value + '\')">Delete</a></td></tr>');
                     */
                    $("#city_wiae_price").append('<tr id="tr_' + $city_cnt + '"><td>' + prc[0] + '</td><td>Rs.' + prc1[0] + '</td><td><a href="javascript:void(0);" onclick="delete_city_price(\'' + $city_cnt + '\',\'' + prc1[0] + '\',\'' + prc[0] + '\',\'' + skillsSelect.value + '\')">Delete</a></td></tr>');
                } else {
                    $("#city_wiae_price").append('<tr id="tr_' + $city_cnt + '"><td>' + prc[0] + '</td><td>Rs.' + prc1[0] + '</td><td><a href="javascript:void(0);" onclick="delete_city_price(\'' + $city_cnt + '\',\'' + prc1[0] + '\',\'' + prc[0] + '\',\'' + skillsSelect.value + '\')">Delete</a></td></tr>');
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
            function get_test_price1() {
                var panel_fk = $("#panels").val();
                var test_val = $("#test_panel").val();
                $("#test_error").html("");
                var cnt = 0;
                if (test_val.trim() == '') {
                    $("#test_error").html("Test is required.");
                    cnt = cnt + 1;
                }
                if (cnt > 0) {
                    return false;
                }
                var skillsSelect = document.getElementById("test_panel");
                var selectedText = skillsSelect.options[skillsSelect.selectedIndex].text;
                var prc = selectedText.split('(Rs.');
                var prc1 = prc[1].split(')');
                var pm = skillsSelect.value;
                var explode = pm.split('-');
                if (explode[0] == 'pt') {
                    /*show_details(explode[1]);
                     var clic = "'" + explode[1] + "'";
                     $("#city_wiae_price").append('<tr id="tr_' + $city_cnt + '"><td><a href="#" data-toggle="modal" data-target="#myModal_view" onclick="show_details(' + clic + ');">' + prc[0] + '</a></td><td>Rs.' + prc1[0] + '</td><td><a href="javascript:void(0);" onclick="delete_city_price(\'' + $city_cnt + '\',\'' + prc1[0] + '\',\'' + prc[0] + '\',\'' + skillsSelect.value + '\')">Delete</a></td></tr>');
                     */
                    $("#city_wiae_price").append('<tr id="tr_' + $city_cnt + '"><td>' + prc[0] + '</td><td>Rs.' + prc1[0] + '</td><td><a href="javascript:void(0);" onclick="delete_city_price(\'' + $city_cnt + '\',\'' + prc1[0] + '\',\'' + prc[0] + '\',\'' + skillsSelect.value + '\')">Delete</a></td></tr>');
                } else {
                    $("#city_wiae_price").append('<tr id="tr_' + $city_cnt + '"><td>' + prc[0] + '</td><td>Rs.' + prc1[0] + '</td><td><a href="javascript:void(0);" onclick="delete_city_price(\'' + $city_cnt + '\',\'' + prc1[0] + '\',\'' + prc[0] + '\',\'' + skillsSelect.value + '\')">Delete</a></td></tr>');
                }
                $("#test_panel option[value='1']").remove();
                var old_dv_txt = $("#hidden_test").html();
                $new_price = parseInt($new_price) + parseInt(prc1[0]);
                /*Total price calculate start*/
                var old_price = $("#total_id").val();
                $("#total_id").val(+old_price + +prc1[0]);
                var dscnt = $("#discount").val();
                //get_discount_price(dscnt);
                /*Total price calculate end*/
                $("#hidden_test").html(old_dv_txt + '<input id="tr1_' + $city_cnt + '" type="hidden" name="test[]" value="' + skillsSelect.value + '-' + panel_fk + '"/>');
                $city_cnt = $city_cnt + 1;
                $("#desc").val("");
                $("#test_panel option[value='" + skillsSelect.value + "']").remove();
                $("#test_panel").val('').trigger('chosen:updated');
            }
            function delete_city_price(id, prc, name, value) {
                var tst = confirm('Are you sure?');
                if (tst == true) {
                    /*Total price calculate start*/
                    $('#add_test').append('<option value="' + value + '">' + name + ' (Rs.' + prc + ')</option>').trigger("chosen:updated");
                    var old_price = $("#total_id").val();
                    $("#total_id").val(old_price - prc);
                    var dscnt = $("#discount").val();
                    //get_discount_price(dscnt);
                    /*Total price calculate end*/
                    $("#tr_" + id).remove();
                    $("#tr1_" + id).remove();

                    var exist_test_ids = $("input[name='test[]']")
                            .map(function () {
                                return $(this).val();
                            }).get();
                    $.ajax({
                        url: '<?php echo base_url(); ?>Admin/get_test_list/<?php echo $job_details[0]["test_city"]; ?>',
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
