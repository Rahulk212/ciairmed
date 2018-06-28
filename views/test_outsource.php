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
<style>
    .chosen-container {
        display: inline-block;
        font-size: 14px;
        position: relative;
        vertical-align: middle;
        width: 100%;
    }
    .full_bg{background: rgba(0,0,0,0.3); width:100%; height:100%; float:left; padding:350px; position:fixed; z-index:9; top:0; bottom:0;}
    .full_bg .loader img{width:70px; height:70px;}
</style>
<div class="full_bg" style="display:none;" id="loader_div">
    <div class="loader">
        <center><img src="http://static.heart.org/riskcalc/app/assets/img/loading.gif"></center>
    </div>
</div>
<div class="content-wrapper"  style="height:600px">
    <!-- Content Header (Page header) -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Test Outsource</h3>
                    </div>
                    <div class="box-body">
                        <div class="col-sm-12">
                            <div id="edit_div">
                                <div class="col-sm-5">
                                    <div class="form-group">
                                        <label for="exampleInputFile">User Test</label><span style="color:red">*</span><br>
                                        <select name="user_test[]" id="user_test" class="chosen" required="" multiple="">
                                            <?php foreach ($job_details[0]['book_test'] as $tests) { ?>
                                                <option value="<?php echo $tests['id']; ?>"><?php echo $tests['test_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                        <span style="color:red;" id="user_test_error"></span>

                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div class="form-group">
                                        <label for="exampleInputFile">Outsource</label><span style="color:red">*</span><br>
                                        <select name="out_id" id="out_id" class="chosen" required="">
                                            <option value="">--Select Outsource--</option>
                                            <?php foreach ($outsource_list as $outsource) { ?>
                                                <option value="<?php echo $outsource->id; ?>"><?php echo $outsource->name." (".$outsource->branch_name.")"; ?></option>
                                            <?php } ?>
                                        </select>
                                        <span style="color:red;" id="out_id_error"></span>

                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label for="exampleInputFile">   &nbsp;</label><br>
                                        <input class="btn btn-primary" value="Add" id="add_outs" type="button" onclick="outsource_test();">
                                    </div>
                                </div>
                                <table class="table table-bordered table-striped" id="city_wiae_price">
                                    <thead>
                                        <tr>
                                            <th>Test</th>
                                            <th>Outsource</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="list_test_outsource">
                                        <?php foreach ($test_outsource as $list) { ?>
                                            <tr id="table_tab_<?php echo $list['id']; ?>">
                                                <td><?php echo $list['test_name']; ?></td>
                                                <td><?php echo $list['name']." (".$list['branch_name'].")"; ?></td>
                                                <td><a href="javascript:void(0);" onclick="delete_test_outsource('<?php echo $list['id']; ?>')">Delete</a></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript">
    function delete_test_outsource(id) {
        var test = confirm("Are you sure ?");
        if (test == true) {
            $.ajax({
                url: "<?php echo base_url(); ?>job_master/delete_outsource",
                type: 'post',
                data: {id: id},
                beforeSend: function () {
                    $("#loader_div").attr("style", "");
                },
                success: function (data) {
                    if (data) {
                        $("#table_tab_" + data).remove();
						location.reload();
                    }
                }, complete: function () {
                    $("#loader_div").attr("style", "display:none;");
                }
            });
        }
    }
    function outsource_test() {
        var test = $("#user_test").val();
        var outsource = $("#out_id").val();
        var job = "<?php echo $jid; ?>";
        var cnt = 0;
        $("#user_test_error").html("");
        $("#out_id_error").html("");
        if (test == '') {
            $("#user_test_error").html("Select test.");
            cnt = 1;
        }
        if (outsource == '') {
            $("#out_id_error").html("Select outsource.");
            cnt = 1;
        }
        if (cnt == 0) {
            $.ajax({
                url: "<?php echo base_url(); ?>job_master/add_outsource",
                type: 'post',
                data: {test_id: test, jobid: job, outsource_id: outsource},
                beforeSend: function () {
                    $("#add_outs").attr("disabled", true);
                    $("#loader_div").attr("style", "");
                },
                success: function (data) {
                    if (data) {
						$("#user_test option[value='"+test+"']").remove();
                        $("#user_test").val('').trigger('chosen:updated');
                        $("#out_id").val('').trigger('chosen:updated');
                        $("#list_test_outsource").html('');
                        $("#list_test_outsource").html(data);
                    }
                }, complete: function () {
                    $("#add_outs").attr("disabled", false);
                    $("#loader_div").attr("style", "display:none;");
                }
            });
        }
    }
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
<script  type="text/javascript">
    jQuery(".chosen-select").chosen({
        search_contains: true,
    });
    $(function () {
        $('.chosen').chosen({
            search_contains: true,
        });
    });
</script>