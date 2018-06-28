<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<!-- Custom styles for this template -->
<link href="<?php echo base_url(); ?>/plugins/TimePicki-master/css/timepicki.css" rel="stylesheet">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>

<script>
    $(document).ready(function () {
        var date_input = $('input[name="dob"]'); //our date input has the name "date"
        var container = $('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";
        date_input.datepicker({
            format: 'dd-mm-yyyy',
            container: container,
            todayHighlight: true,
            autoclose: true,
        })
    })
</script>

<section class="content-header">
    <h1>
        Lead Edit
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>lead_manage_master"><i class="fa fa-users"></i>Lead List</a></li>
        <li class="active">Edit Lead Manage</li>
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
                    <?php $success = $this->session->flashdata('success'); ?>
                    <?php if (isset($success) != NULL) { ?>
                        <div class="alert alert-danger alert-autocloseable-success">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            <?php echo $this->session->userdata('success'); ?>
                        </div>
                    <?php } ?>
                    <div class="col-md-6">
                        <!-- form start -->

                        <?php
                        foreach ($view_data as $data)
                            $id = $view_data->id; {
                            ?> 
                            <!-- form start -->
                            <?php
                            echo form_open_multipart('lead_manage_master/lead_manage_edit/' . $id . '', ['method' => 'post', 'class' => 'form-horizontal',
                                'id' => 'target', 'enctype' => 'multipart/form-data']);
                            ?>
                            <div class="form-group">
                                <label for="exampleInputFile">Name :</label> <input type="text" id="name" name="name" placeholder='Name' value="<?php echo $view_data->name; ?>" class="form-control"/>

                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Email :</label><input type="email" id="email" placeholder='Email Id' class="form-control" name="email" value="<?php echo $view_data->email; ?>"/> 
                                <?php echo form_error('email', '<span class="error" style="color : red">', '</span>'); ?>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Mobile :</label><input type="text" id="mobile" placeholder='Mobile No' name="mobile" value="<?php echo $view_data->mobile_no; ?>" class="form-control"/>
                                <?php echo form_error('mobile', '<span class="error" style="color : red">', '</span>'); ?>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Gender :</label> 
                                <select name="gender" class="form-control">
                                    <option value="">--Select--</option>
                                    <option <?php if ($view_data->gender == 'male') { ?> Selected <?php } ?>value="male">Male</option>
                                    <option <?php if ($view_data->gender == 'female') { ?> Selected <?php } ?>value="female">Female</option>
                                </select> 
                            </div>
                            <div class="form-group">
                                <?php
                                if ($view_data->date_of_birth == '0000-00-00') {
                                    $dob = "00-00-0000";
                                } else {
                                    $dob = date_format(date_create_from_format('Y-m-d', $view_data->date_of_birth), 'd-m-Y');
                                }
                                ?>
                                <label for="recipient-name" class="control-label">Birth date:</label>
                                <input type="text" id="dob1" name="dob" placeholder='MM-DD-YYYY' class="datepicker form-control" value="<?php echo $dob; ?>" />
                            </div>
                        </div>
                        <div class="col-md-6">	
                            <div class="form-group">
                                <label for="exampleInputFile">Age :</span></label><input type="text" id="age" name="age" placeholder='Age' value="<?php echo $view_data->age; ?>" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Address :</span></label> <textarea name="address" placeholder='Address' class="form-control"><?php echo $view_data->address; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Note :</span></label> <textarea class="form-control" placeholder='Note' name="note"><?php echo $view_data->note; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button  class="btn btn-primary"  type="submit" name="submit">Update</button>			
                    </div>
                </div>
                <?php echo form_close(); ?>
                <?php
            }
            ?>
            <script type="text/javascript">
                window.setTimeout(function () {
                    $(".alert").fadeTo(500, 0).slideUp(500, function () {
                        $(this).remove();
                    });
                }, 4000);
            </script>
        </div>
    </div>
</section>
