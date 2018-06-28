<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<section class="content-header">
    <h1>
        Doctor Edit
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>doctor_master/doctor_list"><i class="fa fa-users"></i>Doctor List</a></li>
        <li class="active">Edit Doctor</li>
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

                <!-- form start -->
                <form role="form" action="<?php echo base_url(); ?>doctor_master/doctor_edit/<?= $cid ?>" method="post" enctype="multipart/form-data">

                    <div class="box-body">
                        <div class="col-md-6">
                            <!--<?= validation_errors('<div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>', '</div>'); ?>-->

                            <div class="form-group">
                                <label for="exampleInputFile">Doctor Name</label><span style="color:red">*</span>
                                <input type="text"  name="name" class="form-control"  value="<?php echo $query[0]["full_name"]; ?>" >
                                <span style="color: red;"><?= form_error('name'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Email</label><span style="color:red">*</span>
                                <input type="text"  name="email" class="form-control"  value="<?php echo $query[0]["email"]; ?>" >
                                <span style="color: red;"><?= form_error('email'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Password</label><span style="color:red">*</span>
                                <input type="text"  name="password" class="form-control"  value="<?php echo $query[0]["password"]; ?>" >
                                <span style="color: red;"><?= form_error('password'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Mobile</label><span style="color:red">*</span>
                                <input type="text"  name="mobile" class="form-control"  value="<?php echo $query[0]["mobile"]; ?>" >
                                <span style="color: red;"><?= form_error('mobile'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Second Mobile</label>
                                <input type="text"  name="mobile1" class="form-control"  value="<?php echo $query[0]["mobile1"]; ?>" >
                                <span style="color: red;"><?= form_error('mobile1'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Reception Number</label>
                                <input type="text"  name="mobile2" class="form-control" value="<?php echo $query[0]["mobile2"]; ?>" >
                                <span style="color: red;"><?= form_error('mobile2'); ?></span>
                            </div>
                            <div class="form-group">
                      <label for="exampleInputFile">State</label><span style="color:red">*</span>
                      <select class="form-control" name="state" onchange="state_list(this.value)">
                          <option value="">--Select--</option>
                          <?php foreach ($state_list as $state) { ?>
                          <option value="<?php echo $state['id']; ?>" <?php if($state['id'] == $query[0]['state']) { echo 'selected'; } ?>><?php echo $state['state_name'] ?></option>
                          <?php } ?>
                      </select>
                      <span style="color: red;"><?=form_error('state');?></span>
                    </div>
                                                <div class="form-group">
                      <label for="exampleInputFile">City</label><span style="color:red">*</span>
                      <select class="form-control" name="city" id="city_list">
                          <option value="">--Select--</option>
                          <?php foreach ($city_list as $city) { ?>
                          <option value="<?php echo $city['id']; ?>" <?php if($city['id'] == $query[0]['city']) { echo 'selected'; } ?>><?php echo $city['city_name'] ?></option>
                          <?php } ?>
                      </select>
                      <span style="color: red;"><?=form_error('city');?></span>
                    </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Address</label><span style="color:red">*</span>
                                <textarea name="address" class="form-control"> <?php echo $query[0]['address']; ?></textarea>
                                <span style="color: red;"><?= form_error('address'); ?></span>
                            </div>

<div class="form-group">
                                <label for="exampleInputFile">Notify Report &nbsp;</label>
                                <input type="checkbox" name="notify" value="1" <?php if($query[0]['notify'] == 1) { echo "checked"; } ?>>
                            </div>


                        </div>
                    </div><!-- /.box-body -->

                    <div class="box-footer">
                        <div class="col-md-6">
                            <button class="btn btn-primary" type="submit">Update</button>
                        </div>
                    </div>

                </form>
            </div><!-- /.box -->
            <script  type="text/javascript">
                $(document).ready(function () {
                    $("#showHide").click(function () {
                        if ($("#password").attr("type") == "password") {
                            $("#password").attr("type", "text");
                        } else {
                            $("#password").attr("type", "password");
                        }

                    });
                });
                function state_list(cid) {
     $.ajax({
            url: '<?php echo base_url(); ?>doctor_master/city_state_list',
            type: 'post',
            data: {cid: cid},
            success: function (data) {
                $("#city_list").html(data);
            }
    });
 }
            </script>
        </div>
    </div>
</section>
