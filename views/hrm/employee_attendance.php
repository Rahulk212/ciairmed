<!-- Page Heading -->
<style>
    #table_body td:nth-child(2n+1) {
        width: 100px;
    }
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    .switch input {display:none;}

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked + .slider {
        background-color: #2196F3;

    }

    input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }
</style>
<link href="<?php echo base_url(); ?>switch/bootstrap-switch.css" rel="stylesheet">
<script src="<?php echo base_url(); ?>switch/bootstrap-switch.js"></script>
<div class="content-wrapper">
    <section class="content-header">
        <h1>Attendance<small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active"><i class="fa fa-check"></i> Attendance</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary" style="border-color:#bf2d37;">
                    <div class="panel-heading" style="background-color:#bf2d37;">
                        <!-- form start -->
                        <h3 class="panel-title">Add Attendance</h3>
                    </div>

                    <div class="panel-body">
                        <form role="form" id="holiday_form" action="<?php echo base_url(); ?>hrm/attendance/another_date" method="post" enctype="multipart/form-data">
                            <div class="col-md-3">
                                <input type="text" name="chose_date" id="chose_date" class="datepicker-input form-control" placeholder="Select another date" value="<?php echo $today; ?>">
                            </div>
                            <input type="submit" value="Edit" class="btn btn-primary btn-md">
                        </form><br>
                        <div class="widget">
                            <?php if ($this->session->flashdata('unsuccess')) { ?>
                                <div class="alert alert-danger alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $this->session->flashdata('unsuccess'); ?>
                                </div>
                            <?php } ?>
                            <?php if ($this->session->flashdata('success')) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $this->session->flashdata('success'); ?>
                                </div>
                            <?php } ?>
                        </div>
                        <br>
                        <form role="form" id="attendance_form" action="<?php echo base_url(); ?>hrm/attendance/do_attendance" method="post" enctype="multipart/form-data">
                            <div class="col-md-12">
                                <h3>Date <?php echo $today; ?><input type="hidden" value="<?php echo $today; ?>" name="attendance_date"></h3>
                                <table id="example4" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Employee ID</th>
                                            <th style="width:250px;">Name</th>
                                            <th style="width:250px;">Status</th>
                                            <th style="width:250px;">Type of leave</th>
                                            <th style="width:250px;">Reason</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table_body">
                                        <?php foreach ($employee_list as $employee) { ?>
                                            <tr>
                                                <td><?php echo $employee->employee_id; ?><input type="hidden" name="employee_fk_<?php echo $employee->id; ?>" value="<?php echo $employee->employee_id; ?>"></td>
                                                <td><?php echo ucwords($employee->name); ?></td>
                                                <td><input type="checkbox" class="check_att" name="attend_status_<?php echo $employee->id; ?>" value="1" <?php if($employee->attend_status == '1') { echo "checked"; } ?> onchange="give_reason('<?php echo $employee->id; ?>');"></td>
                                                <td id="type_td_<?php echo $employee->id; ?>" <?php if($employee->attend_status == '1') { ?>  style="display:none;" <?php } ?>>
                                                    <select class="form-control" name="type_<?php echo $employee->id; ?>" id="type_<?php echo $employee->id; ?>">
                                                        <option value="">Select Type of leave</option>
                                                        <?php foreach ($type_list as $type) { ?>
                                                            <option value="<?php echo $type->id; ?>" <?php if($employee->type_of_leave == $type->id) { echo "selected"; } ?>><?php echo ucwords($type->leave); ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td id="reason_td_<?php echo $employee->id; ?>" <?php if($employee->attend_status == '1') { ?>  style="display:none;" <?php } ?>>
                                                    <input type="text" name="reason_<?php echo $employee->id; ?>" id="reason_<?php echo $employee->id; ?>" class="form-control" placeholder="Absent Reason" value="<?php echo $employee->reason; ?>">
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                    <div class="panel-footer">
                        <button class="btn btn-primary" type="button" onclick="submit_button();">Submit</button>
                    </div>

                </div><!-- /.box -->
            </div>
        </div>
    </section>
</div>
<script>
    $(".check_att").bootstrapSwitch();
    function give_reason(values) {
        var attend = $('input[name=attend_status_'+values+']:checked').val();
        if(attend == 1) {
            $("#type_td_"+values).hide();
            $("#reason_td_"+values).hide();
        } else {
            $("#type_td_"+values).show();
            $("#reason_td_"+values).show();
        }
    }
    function submit_button() {
        $("#attendance_form").submit();
    }
</script>