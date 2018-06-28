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
<style>
    .admin_job_dtl_img {border: 4px solid #8d8d8d; height: 160px; max-width: 160px; min-width: 80px; width: 180px;}
    .table-responsive.set_inpt_wdth .form-control {width: auto;}
	.table-bordered{margin-bottom:10px;}
	label{font-size:11px;}
	.form-control{height:28px;padding:0 2px; font-size:12px;}
	.table > caption + thead > tr:first-child > td, .table > caption + thead > tr:first-child > th, .table > colgroup + thead > tr:first-child > td, .table > colgroup + thead > tr:first-child > th, .table > thead:first-child > tr:first-child > td, .table > thead:first-child > tr:first-child > th{font-size: 11px;
    padding: 3px;}
	h2{margin:0 0 10px 0;font-size:18px;}
	.table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th{padding:1px;}
	textarea.form-control{height:30px;}
        #example4 td:first-child{width:10px;}
	#example4 td:nth-child(2){width:125px;}
</style>
<script type="text/javascript" src="<?php echo base_url(); ?>source/jquery.fancybox.pack.js?v=2.1.5"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>source/jquery.fancybox.css?v=2.1.5" media="screen" />
<script src="//cdn.ckeditor.com/4.5.8/full/ckeditor.js"></script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <form method="post" action="<?php echo base_url(); ?>parameter_master/add_value_all" id="add_parameters_ref">
                        <div class="box-header">
                            <h3 class="box-title">Add Parameter</h3>
                        </div>
                        <div class="box-body">
                            <div class="alert alert-danger alert-dismissable" id="msg_cancel" style="display:none;">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                Results Not Add
                            </div>
                            <div class="alert alert-success alert-dismissable" id="msg_success" style="display:none;">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                Results Add Successfully
                            </div>
                            <div class="col-sm-12">
                                <div id="edit_div">
                                        <div class="col-sm-8" style="padding-left:0">
                                            <div class="form-group">
                                                <label for="exampleInputFile">Exist Parameter</label>
                                                <select class="form-control" name="exist_para" id="exist_para">
                                                    <option value="">--Select--</option>
                                                    <?php foreach ($parameter_list as $parameter) { ?>
                                                        <option value="<?php echo $parameter['id']; ?>"><?php echo $parameter['parameter_name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <span style="color:red;" id="copy_parameter"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-1">
                                            <div class="form-group">
                                                <label for="exampleInputFile"> </label>
                                                <button class="btn btn-primary" type="button" style=" margin-top: 24px;padding: 2px 10px;" onclick="copy_value();">Copy</button>
                                            </div>
                                        </div>
                                    <table id="" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Unit</th>
                                                <th>Formula</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <input type="text" name="par_name" id="par_name" class="form-control">
                                                </td>
                                                <td>
                                                    <select class="form-control" name="par_unit" id="par_unit">
                                                        <option value="">--Select--</option>
                                                        <?php foreach ($unit_list as $unit) { ?>
                                                            <option value="<?php echo $unit['PARAMETER_NAME']; ?>"><?php echo $unit['PARAMETER_NAME']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" name="par_formula" id="par_formula" class="form-control">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="table-responsive set_inpt_wdth">
                                        <table id="" class="table table-bordered table-striped">
                                            <h2>Reference Range</h2>
                                            <thead>
                                                <tr>
                                                    <th>Add/Remove</th>
                                                    <th>Gender</th>
                                                    <th>No of Period</th>
                                                    <th>Type of Period</th>
                                                    <th>Normal Remarks</th>
                                                    <th>Ref Range Low</th>
                                                    <th>Low Remarks</th>
                                                    <th>Ref Range High</th>
                                                    <th>High Remarks</th>
                                                    <th>Critical Low</th>
                                                    <th>Critical Low Remarks</th>
                                                    <th>Critical High</th>
                                                    <th>Critical High Remarks</th>
                                                    <th>Critical Low Sms</th>
                                                    <th>Critical High Sms</th>
                                                    <th>Repeat Low</th>
                                                    <th>Repeat Low Remarks</th>
                                                    <th>Repeat High</th>
                                                    <th>Repeat High Remarks</th>
                                                    <th>Absurd Low</th>
                                                    <th>Absurd High</th>
                                                    <th>Ref Range</th>
                                                </tr>
                                            </thead>
                                            <tbody id="table_body1">
                                                <tr>
                                                    <td>
                                                        <a class="srch_view_a" href="javascript:void(0)" onclick="add_field();"><i class="fa fa-plus-square"></i></a>
                                                    </td>
                                                    <td><input type="hidden" name="count_par" id="count_par" value="1">
                                                        <select class="form-control" name="par_gender_1" id="par_gender_1">
                                                            <option value="">--Select--</option>
                                                            <option value="M">Male</option>
                                                            <option value="F">Female</option>
                                                            <option value="B">Both</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="par_no_period_1" id="par_no_period_1" class="form-control">
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="par_type_period_1" id="par_type_period_1">
                                                            <option value="">--Select--</option>
                                                            <option value="D">Days</option>
                                                            <option value="M">Months</option>
                                                            <option value="Y">Years</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="par_normal_remark_1" id="par_normal_remark_1" class="form-control">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="par_ref_range_low_1" id="par_ref_range_low_1" class="form-control">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="par_low_remark_1" id="par_low_remark_1" class="form-control">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="par_ref_range_high_1" id="par_ref_range_high_1" class="form-control">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="par_high_remark_1" id="par_high_remark_1" class="form-control">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="par_critical_low_1" id="par_critical_low_1" class="form-control">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="par_critical_low_remark_1" id="par_critical_low_remark_1" class="form-control">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="par_critical_high_1" id="par_critical_high_1" class="form-control">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="par_critical_high_remark_1" id="par_critical_high_remark_1" class="form-control">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="par_critical_low_sms_1" id="par_critical_low_sms_1" class="form-control">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="par_critical_high_sms_1" id="par_critical_high_sms_1" class="form-control">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="par_repeat_low_1" id="par_repeat_low_1" class="form-control">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="par_repeat_low_remark_1" id="par_repeat_low_remark_1" class="form-control">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="par_repeat_high_1" id="par_repeat_high_1" class="form-control">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="par_repeat_high_remark_1" id="par_repeat_high_remark_1" class="form-control">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="par_absurd_low_1" id="par_absurd_low_1" class="form-control">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="par_absurd_high_1" id="par_absurd_high_1" class="form-control">
                                                    </td>
                                                    <td>
                                                        <textarea name="par_ref_range_1" id="par_ref_range_1" class="form-control"></textarea>
                                                    </td>

                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <table id="example4" class="table table-bordered table-striped">
                                        <h2>Reference Status</h2>
                                        <thead>
                                            <tr>
                                                <th>Add/Remove</th>
                                                <th>Parameter Code</th>
                                                <th>parameter Name</th>
                                                <th>Result Status</th>
                                                <th>Critical Status</th>
                                                <th>Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody id="table_body">
                                            <tr>
                                                <td>
                                                    <a class="srch_view_a" href="javascript:void(0)" onclick="add_field1();"><i class="fa fa-plus-square"></i></a>
                                                </td>
                                                <td>
                                                    <input type="text" name="par_code_1" id="par_code_1" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="text" name="par_name_1" id="par_name_1" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="hidden" name="count_ref" id="count_ref" value="1">
                                                    <select class="form-control" name="par_result_1" id="par_result_1">
                                                        <option value="">--Select--</option>
                                                        <option value="N">Normal</option>
                                                        <option value="H">High</option>
                                                        <option value="L">Low</option>
                                                        <option value="A">Abnormal</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control" name="par_critical_1" id="par_critical_1">
                                                        <option value="">--Select--</option>
                                                        <option value="N">Normal</option>
                                                        <option value="C">Critical</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" name="par_remarks_1" id="par_remarks_1" class="form-control">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <input style="float:right;" class="btn btn-primary" value="Add Parameter" type="button" onclick="sub_form();">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript">
    function sub_form() {
        var form = $('#add_parameters_ref');
        $.ajax({
            type: "POST",
            url: form.attr('action'),
            data: form.serialize(),
            success: function (response) {
                if (response.trim() == 1) {
                    parent.close_box();
                } else {
                    alert("Oops somthing wrong. Try again.");
                }
            }
        });
        //$("#add_parameters_ref").submit();

    }
    var cnt = 1;
    function add_field() {
        cnt++;
        var coun = "'" + cnt + "'";
        $('#table_body1').append('<tr id="tr_' + cnt + '"><td><a href="javascript:void(0)" onclick="row_remove(' + coun + ');"><i style="color:red;" class="fa fa-minus-square"></i></a></td><td><select class="form-control" name="par_gender_' + cnt + '" id="par_gender_' + cnt + '"><option value="">--Select--</option><option value="M">Male</option><option value="F">Female</option><option value="B">Both</option></select></td><td><input type="text" name="par_no_period_' + cnt + '" id="par_no_period_' + cnt + '" class="form-control"></td><td><select class="form-control" name="par_type_period_' + cnt + '" id="par_type_period_' + cnt + '"><option value="">--Select--</option><option value="D">Days</option><option value="M">Months</option><option value="Y">Years</option></select></td><td><input type="text" name="par_normal_remark_' + cnt + '" id="par_normal_remark_' + cnt + '" class="form-control"></td><td><input type="text" name="par_ref_range_low_' + cnt + '" id="par_ref_range_low_' + cnt + '" class="form-control"></td><td><input type="text" name="par_low_remark_' + cnt + '" id="par_low_remark_' + cnt + '" class="form-control"></td><td><input type="text" name="par_ref_range_high_' + cnt + '" id="par_ref_range_high_' + cnt + '" class="form-control"></td><td><input type="text" name="par_high_remark_' + cnt + '" id="par_high_remark_' + cnt + '" class="form-control"></td><td><input type="text" name="par_critical_low_' + cnt + '" id="par_critical_low_' + cnt + '" class="form-control"></td><td><input type="text" name="par_critical_low_remark_' + cnt + '" id="par_critical_low_remark_' + cnt + '" class="form-control"></td><td><input type="text" name="par_critical_high_' + cnt + '" id="par_critical_high_' + cnt + '" class="form-control"></td><td><input type="text" name="par_critical_high_remark_' + cnt + '" id="par_critical_high_remark_' + cnt + '" class="form-control"></td><td><input type="text" name="par_critical_low_sms_' + cnt + '" id="par_critical_low_sms_' + cnt + '" class="form-control"></td><td><input type="text" name="par_critical_high_sms_' + cnt + '" id="par_critical_high_sms_' + cnt + '" class="form-control"></td><td><input type="text" name="par_repeat_low_' + cnt + '" id="par_repeat_low_' + cnt + '" class="form-control"></td><td><input type="text" name="par_repeat_low_remark_' + cnt + '" id="par_repeat_low_remark_' + cnt + '" class="form-control"></td><td><input type="text" name="par_repeat_high_' + cnt + '" id="par_repeat_high_' + cnt + '" class="form-control"></td><td><input type="text" name="par_repeat_high_remark_' + cnt + '" id="par_repeat_high_remark_' + cnt + '" class="form-control"></td><td><input type="text" name="par_absurd_low_' + cnt + '" id="par_absurd_low_' + cnt + '" class="form-control"></td><td><input type="text" name="par_absurd_high_' + cnt + '" id="par_absurd_high_' + cnt + '" class="form-control"></td><td><textarea name="par_ref_range_' + cnt + '" id="par_ref_range_' + cnt + '" class="form-control"></textarea></td></tr>');
        $('#count_par').val(cnt);
    }
    function row_remove(val) {
        $('#tr_' + val).remove();
        cnt--;
        $('#count_par').val(cnt);
    }
    var cnt1 = 1;
    function add_field1() {
        cnt1++;
        var coun = "'" + cnt1 + "'";
        $('#table_body').append('<tr id="tr1_' + cnt1 + '"><td><a href="javascript:void(0)" onclick="row_remove1(' + coun + ');"><i style="color:red;" class="fa fa-minus-square"></i></a></td><td><input type="text" name="par_code_' + cnt1 + '" id="par_code_' + cnt1 + '" class="form-control"></td><td><input type="text" name="par_name_' + cnt1 + '" id="par_name_' + cnt1 + '" class="form-control"></td><td><select class="form-control" name="par_result_' + cnt1 + '" id="par_result_' + cnt1 + '"><option value="">--Select--</option><option value="N">Normal</option><option value="H">High</option><option value="L">Low</option><option value="A">Abnormal</option></select></td><td><select class="form-control" name="par_critical_' + cnt1 + '" id="par_critical_' + cnt1 + '"><option value="">--Select--</option><option value="N">Normal</option><option value="c">Critical</option></select></td><td><input type="text" name="par_remarks_' + cnt1 + '" id="par_remarks_' + cnt1 + '" class="form-control"></td></tr>');
        $('#count_ref').val(cnt1);
    }
    function row_remove1(val) {
        $('#tr1_' + val).remove();
        cnt1--;
        $('#count_ref').val(cnt1);
    }
    function remove_edit_tr(val,rid) {
        $('#edit_tr_' + val).remove();
        var url = "<?php echo base_url(); ?>add_result/remove_reference";
            $.ajax({
                type: "POST",
                url: url,
                data: {"reference_id": rid}, // serializes the form's elements.
                success: function (data)
                {
                }
            });
    }
    function remove_edit_tr1(val,sid) {
        $('#edit_tr_sts_' + val).remove();
        var url = "<?php echo base_url(); ?>add_result/remove_status";
            $.ajax({
                type: "POST",
                url: url,
                data: {"status_id": sid}, // serializes the form's elements.
                success: function (data)
                {
                }
            });
    }
    function parameter_validation() {
        var i;
        var temp = 1;
        for (i = 1; i <= cnt; i++) {
            var name = $('#par_name_' + i).val();
            var value = $('#par_value_' + i).val();
            var min = $('#par_min_' + i).val();
            var max = $('#par_max_' + i).val();
            var unit = $('#par_unit_' + i).val();
            var con = $('#par_condi_' + i).val();
            if (name == '') {
                temp = 1;
                $('#par_name_error_' + i).html('Required.');
            } else {
                temp = 0;
                $('#par_name_error_' + i).html('');
            }
            if (value == '') {
                temp = 1;
                $('#par_value_error_' + i).html('Required.');
            } else {
                temp = 0;
                $('#par_value_error_' + i).html('');
            }
            if (min == '') {
                temp = 1;
                $('#par_min_error_' + i).html('Required.');
            } else {
                temp = 0;
                $('#par_min_error_' + i).html('');
            }
            if (max == '') {
                temp = 1;
                $('#par_max_error_' + i).html('Required.');
            } else {
                temp = 0;
                $('#par_max_error_' + i).html('');
            }
            if (unit == '') {
                temp = 1;
                $('#par_unit_error_' + i).html('Required.');
            } else {
                temp = 0;
                $('#par_unit_error_' + i).html('');
            }
            if (con == '') {
                temp = 1;
                $('#par_condi_error_' + i).html('Required.');
            } else {
                temp = 0;
                $('#par_condi_error_' + i).html('');
            }
        }
        if (temp == 0) {
            $('#add_data').submit();
        }
    }

    function add_parameter(val) {
        $('#test_id').val(val);
        var url = "<?php echo base_url(); ?>add_result/testdescription";
        $.ajax({
            type: "POST",
            url: url,
            data: {"test_id": val}, // serializes the form's elements.
            success: function (data)
            {
                CKEDITOR.instances.description.setData(data);
            }
        });
    }
    function update_parameter(pid) {
        if (pid != '') {
            var url = "<?php echo base_url(); ?>add_result/edit_parameter";
            $.ajax({
                type: "POST",
                url: url,
                data: {"para_id": pid}, // serializes the form's elements.
                success: function (data)
                {
                    $('#update_body').html(data);
                }
            });
        }
    }
    function parameter_update() {
        var temp = 0;
        var name = $('#update_par_name').val();
        var min = $('#update_par_min').val();
        var max = $('#update_par_max').val();
        var unit = $('#update_par_unit').val();
        $('#update_par_name_error').html('');
        $('#update_par_min_error').html('');
        $('#update_par_max_error').html('');
        $('#update_par_unit_error').html('');
        if (name == '') {
            temp = 1;
            $('#update_par_name_error').html('Required.');
        }
        if (min == '') {
            temp = 1;
            $('#update_par_min_error').html('Required.');
        }
        if (max == '') {
            temp = 1;
            $('#update_par_max_error').html('Required.');
        }
        if (unit == '') {
            temp = 1;
            $('#update_par_unit_error').html('Required.');
        }
        if (temp == 0) {
            $('#update_data').submit();
        }
    }
    function copy_value() {
        var par_id = $("#exist_para").val();
        if (par_id == '') {
            $("#copy_parameter").html('Please Select Parameter.');
        } else {
            var url = "<?php echo base_url(); ?>parameter_master/get_value";
            $.ajax({
                type: "POST",
                url: url,
                data: {"pid": par_id}, // serializes the form's elements.
                success: function (data)
                {
                    $('#edit_div').empty();
                    $('#edit_div').append(data);
                }
            });
        }
    }
</script>
