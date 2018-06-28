<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<link href="<?php echo base_url(); ?>plugins/timepick/css/timepicki.css" rel="stylesheet">
<section class="content-header">
    <h1>
        Time Slot Edit
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>Timeslot_master/"><i class="fa fa-users"></i>Time Slot List</a></li>
        <li class="active">Time Slot </li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">  
                <div class="box-body">
                    <div class="col-md-6">
                        <!-- form start -->
                        <?php $success = $this->session->flashdata('success'); ?>
                        <?php if (isset($success) != NULL) { ?>
                            <div class="alert alert-success alert-autocloseable-success">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                <?php echo $this->session->userdata('success'); ?>
                            </div>
                        <?php } ?>
                        <?php
                        foreach ($view_data as $data)
                        /*   echo "<pre>";
                          print_r($data);
                          exit; */
                            $id = $data['id']; {
                            ?> 
                            <!-- form start -->
                            <?php
                            echo form_open_multipart('Timeslot_master/timeslot_edit/' . $id . '', ['method' => 'post', 'class' => 'form-horizontal',
                                'id' => 'target', 'enctype' => 'multipart/form-data']);
                            ?>
                            <div class="form-group">
                                <label for="name">Start Time</label><span style="color:red">*</span>
                                <?php
                                echo form_input(['name' => 'start_time', 'class' => 'form-control', 'placeholder' => 'Start Time', 'id' => 'timepicker1',
                                    'value' => $data['start_time']]);
                                ?>
                                <?php echo form_error('start_time'); ?>
                            </div>
                            <div class="form-group">
                                <label for="name">End Time</label><span style="color:red">*</span>
                                <?php
                                echo form_input(['name' => 'end_time', 'class' => 'form-control', 'placeholder' => 'End Time', 'id' => 'timepicker2',
                                    'value' => $data['end_time']]);
                                ?>
                                <?php echo form_error('end_time'); ?>
                            </div>
                        </div>
                    </div>	
                    <div class="box-footer">
                        <button  class="btn btn-primary" name="button" type="submit">Update</button>
                        <input type="hidden" id="idh" name="idh" value="<?php echo $id; ?>">			
                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>
            <?php
        }
        ?>
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
        <script src="<?php echo base_url(); ?>/plugins/TimePicki-master/js/bootstrap.min.js"></script>
</section>
