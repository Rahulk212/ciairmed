<!-- Page Heading -->
<style>
    #table_body td:nth-child(2n+1) {
        width: 100px;
    }
</style>
<div class="content-wrapper">
    <section class="content-header">
        <h1>Department<small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>hrm/department/"><i class="fa fa-briefcase"></i> Department List</a></li>
            <li class="active"> Add Department</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary" style="border-color:#bf2d37;">
                    <div class="panel-heading" style="background-color:#bf2d37;">
                        <!-- form start -->
                        <h3 class="panel-title">Add Department</h3>
                    </div>
                    <form role="form" id="department_form" action="<?php echo base_url(); ?>hrm/department/add_all" method="post" enctype="multipart/form-data">
                        <div class="panel-body">
                            <div class="col-md-6">
                                <table id="example4" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Department Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <input type="text" name="department_name" id="department_name" class="form-control">
                                                <span id="department_name_error" style="color:red;"></span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table id="example4" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Add/Remove</th>
                                            <th>Designation</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table_body">
                                        <tr>
                                            <td>
                                                <a class="srch_view_a" href="javascript:void(0)" onclick="add_field();"><i class="fa fa-plus-square"></i></a>
                                            </td>
                                            <td>
                                                <input type="hidden" name="count_designation" id="count_designation" value="1">
                                                <input type="text" name="designation_1" id="designation_1" class="form-control">
                                                <span id="designation_error_1" style="color:red;"></span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <button class="btn btn-primary" type="button" onclick="submit_button();">Add</button>
                        </div>
                    </form>
                </div><!-- /.box -->
            </div>
        </div>
    </section>
</div>
<script>
    var cnt = 1;
    function add_field() {
        cnt++;
        var coun = "'" + cnt + "'";
        $('#table_body').append('<tr id="tr_' + cnt + '"><td><a href="javascript:void(0)" onclick="row_remove(' + coun + ');"><i style="color:red;" class="fa fa-minus-square"></i></a></td><td><input type="text" name="designation_' + cnt + '" id="designation_' + cnt + '" class="form-control"><span id="designation_error_' + cnt + '" style="color:red;"></span></td></tr>');
        $('#count_designation').val(cnt);
    }
    function row_remove(val) {
        $('#tr_' + val).remove();
        cnt--;
        $('#count_designation').val(cnt);
    }
    function submit_button() {
        var name = $('#department_name').val();
        var count = $('#count_designation').val();
        $("#department_name_error").html("");
        var temp = 1;
        if (name == "") {
            $("#department_name_error").html("Department Name Required.");
            temp = 0;
        }
        var j = 1;
        for (j = 1; j <= count; j++) {
            var name = $('#designation_' + j).val();
            $("#designation_error_" + j).html("");
            if (name == "") {
                $("#designation_error_" + j).html("Designation Required.");
                temp = 0;
            }
        }
        if (temp == 1) {
            $("#department_form").submit();
        }
    }
</script>