<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<section class="content-header">
    <h1>
        Admin User Edit
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>Admin_manage/user_list"><i class="fa fa-users"></i>Amdin User List</a></li>
        <li class="active">Edit User</li>
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
                <form role="form" action="<?php echo base_url(); ?>Admin_manage/user_edit/<?=$cid;?>" method="post" enctype="multipart/form-data">

                    <div class="box-body">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputFile">Name</label><span style="color:red">*</span>
                                <input type="text"  name="name" class="form-control"  value="<?php echo $query[0]["name"]; ?>" >
                                <span style="color: red;"><?=form_error('name');?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Email</label><span style="color:red">*</span>
                                <input type="text"  name="email" class="form-control"  value="<?php echo $query[0]["email"]; ?>" >
                                <span style="color: red;"><?=form_error('email');?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Phone No.</label><span style="color:red">*</span>
                                <input type="text"  name="phone" class="form-control"  value="<?php echo $query[0]["phone"]; ?>" >
                                <span style="color: red;"><?=form_error('phone');?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Password</label><span style="color:red">*</span>
                                <input type="password"  name="password" class="form-control"  value="<?php echo $query[0]["password"]; ?>" >
                                <span style="color: red;"><?=form_error('password');?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Type</label><span style="color:red">*</span>
                                <select name="type" class="form-control">
                                    <option value="1" <?php if($query[0]["type"]=='1'){ echo "selected"; } ?>>Pro+</option>
                                    <option value="2" <?php if($query[0]["type"]=='2'){ echo "selected"; } ?>>Pro</option>
                                    <option value="3" <?php if($query[0]["type"]=='3'){ echo "selected"; } ?>>Source Lab</option>
                                    <option value="4" <?php if($query[0]["type"]=='4'){ echo "selected"; } ?>>Destination Lab</option>
                                    <option value="5" <?php if($query[0]["type"]=='5'){ echo "selected"; } ?>>Super Admin</option>
                                    <option value="6" <?php if($query[0]["type"]=='6'){ echo "selected"; } ?>>Admin</option>
                                    <option value="7" <?php if($query[0]["type"]=='7'){ echo "selected"; } ?>>User</option>
                                </select>
                                <span style="color: red;"><?=form_error('type');?></span>
                            </div>
							
							<div class="form-group">
                                <label for="exampleInputFile">City</label>
                                <select name="cityname" class="form-control" id="">
								<option value="">Select</option>
								<?php foreach($getcity as $citylist){ ?>
                                    <option value="<?= $citylist['id']; ?>" <?php if($query[0]["city_fk"]==$citylist['id']){echo "selected";} ?>><?= $citylist['name']; ?></option>
									<?php } ?>
                                </select>
                                <span style="color: red;"><?= form_error('cityname'); ?></span>
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
            </script>
        </div>
    </div>
</section>
