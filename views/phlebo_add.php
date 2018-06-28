<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<section class="content-header">
    <h1>
        Phlebo
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>phlebo_master/phlebo_list"> Phlebo List</a></li>
        <li class="active">Add Phlebo</li>
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
                <form role="form" action="<?php echo base_url(); ?>phlebo_master/phlebo_add" method="post" enctype="multipart/form-data">

                    <div class="box-body">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputFile">Phlebo Name</label><span style="color:red">*</span>
                                <input type="text"  name="name" class="form-control"  value="<?php echo set_value('name'); ?>" >
                                <span style="color: red;"><?= form_error('name'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Email</label><span style="color:red">*</span>
                                <input type="text"  name="email" class="form-control"  value="<?php echo set_value('email'); ?>" >
                                <span style="color: red;"><?= form_error('email'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Mobile</label><span style="color:red">*</span>
                                <input type="text"  name="mobile" class="form-control"  value="<?php echo set_value('mobile'); ?>" >
                                <span style="color: red;"><?= form_error('mobile'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Password</label><span style="color:red">*</span>
                                <input type="password"  name="password" class="form-control"  value="<?php echo set_value('password'); ?>" >
                                <span style="color: red;"><?= form_error('password'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Confirm Password</label><span style="color:red">*</span>
                                <input type="password"  name="confirm_password" class="form-control"  value="<?php echo set_value('confirm_password'); ?>" >
                                <span style="color: red;"><?= form_error('confirm_password'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Type</label><span style="color:red">*</span>
                                <select name="type" class="form-control">
                                    <option value="1">Phlebo</option>
                                    <option value="2">Logistic</option>
                                </select>
                                <span style="color: red;"><?= form_error('type'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Test City</label><span style="color:red">*</span>
                                <select name="test_city" class="form-control">
                                    <option value="">--Select--</option>
                                    <?php foreach($test_city as $key){ ?>
                                    <option value="<?=$key["id"]?>"><?= ucfirst($key["name"])?></option>
                                    <?php } ?>
                                </select>
                                <span style="color: red;"><?= form_error('test_city'); ?></span>
                            </div>
                        </div>
                    </div><!-- /.box-body -->

                    <div class="box-footer">
                        <div class="col-md-6">
                            <button class="btn btn-primary" type="submit">Add</button>
                        </div>
                    </div>
            </div>	
            </form>
        </div><!-- /.box -->
    </div>
</div>
</div>
</section>
