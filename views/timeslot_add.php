<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<link href="<?php echo base_url(); ?>plugins/timepick/css/timepicki.css" rel="stylesheet">
<!-- Custom styles for this template -->
<link href="<?php echo base_url(); ?>/plugins/TimePicki-master/css/timepicki.css" rel="stylesheet">
<section class="content-header">
    <h1>
        Time Slot Add
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>Timeslot_master/"><i class="fa fa-users"></i>Time Slot List</a></li>
        <li class="active">Add Time Slot</li>
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
                <div class="box-body">
                    <div class="col-md-6">
                        <!-- form start -->
                        <form role="form" action="<?php echo base_url(); ?>Timeslot_master/timeslot_add" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="start_time">Start Time</label><span style="color:red">*</span>
                                <input type="text"  name="start_time" class="form-control" id="timepicker1" placeholder="Start Time" onblur="OnBlurInput(id)" value="<?php echo set_value('start_time'); ?>" >
                                <?php echo form_error('start_time'); ?>
                            </div>
                            <div class="form-group">
                                <label for="end_time">End Time</label><span style="color:red">*</span>
                                <input type="text"  name="end_time" class="form-control" id="timepicker2" placeholder="End Time" onblur="OnBlurInput(id)" value="<?php echo set_value('end_time'); ?>" >
                                <?php echo form_error('end_time'); ?>
                            </div>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="col-md-6">
                        <button class="btn btn-primary" type="submit">Add</button>
                    </div>
                </div>
                </form>
            </div><!-- /.box -->
            <script src="<?php echo base_url(); ?>plugins/timepick/js/timepicki.js"></script>
            <script>
                                    $('#timepicker1').timepicki({
                                        show_meridian: false,
                                        min_hour_value: 0,
                                        max_hour_value: 23,
                                        overflow_minutes: true,
                                        increase_direction: 'up',
                                        disable_keyboard_mobile: true});
            </script>

            <script>
                $('#timepicker2').timepicki({
                    show_meridian: false,
                    min_hour_value: 0,
                    max_hour_value: 23,
                    overflow_minutes: true,
                    increase_direction: 'up',
                    disable_keyboard_mobile: true});
            </script>
        </div>
    </div>
</section>
