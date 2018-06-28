<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<section class="content-header">
    <h1>
        Lab
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>b2b/Logistic/lab_list"><i class="fa fa-users"></i>Lab List</a></li>
        <li class="active">Add Edit</li>
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
                <form role="form" action="<?php echo base_url(); ?>b2b/Logistic/lab_edit/<?= $id; ?>" method="post" enctype="multipart/form-data">

                    <div class="box-body">
                        <div class="col-md-6">
                            <!--<?= validation_errors('<div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>', '</div>'); ?>-->
                            <div class="form-group">
                                <label for="exampleInputFile">Lab Name</label><span style="color:red">*</span>
                                <input type="text"  name="lab_name" class="form-control"  value="<?php echo $query[0]["name"]; ?>" >
                                <span style="color: red;"><?= form_error('lab_name'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Contact Person Name</label><span style="color:red">*</span>
                                <input type="text"  name="person_name" class="form-control"  value="<?php echo $query[0]["contact_person_name"]; ?>" >
                                <span style="color: red;"><?= form_error('person_name'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Mobile Number</label><span style="color:red">*</span>
                                <input type="text"  name="mobile_number" class="form-control"  value="<?php echo $query[0]["mobile_number"]; ?>" >
                                <span style="color: red;"><?= form_error('mobile_number'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Alternate Number</label>
                                <input type="text"  name="alternate_number" class="form-control"  value="<?php echo $query[0]["alternate_number"]; ?>" >
                                <span style="color: red;"><?= form_error('alternate_number'); ?></span>
                            </div>
							<div class="form-group">
                                <label for="exampleInputFile">City</label><span style="color:red">*</span>
                               <select class="form-control" name="city"  >
							   <option value="">--Select--</option>
							   <?php foreach($cityall  as $city){ ?>
							    
							   <option value="<?= $city['id']; ?>" <?php if($city['id']==$query[0]["city"]){ echo "selected"; } ?>  ><?= $city['name']; ?></option>
							   <?php } ?>
							   </select>
                                <span style="color: red;"><?= form_error('city'); ?></span>
                            </div>
							
                            <div class="form-group">
                                <label for="exampleInputFile">Address</label><span style="color:red">*</span>
                                <textarea name="address" class="form-control" ><?php echo $query[0]["address"]; ?></textarea>
                                <span style="color: red;"><?= form_error('address'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Email</label><span style="color:red">*</span>
                                <input type="text"  name="email" class="form-control"  value="<?php echo $query[0]["email"]; ?>" >
                                <span style="color: red;"><?= form_error('email'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Password</label><span style="color:red">*</span>
                                <input type="password"  name="password" class="form-control"  value="<?php echo $query[0]["password"]; ?>" >
                                <span style="color: red;"><?= form_error('password'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Activated By</label><span style="color:red">*</span>
                                <select class="form-control" name="sales_fk">
                                    <option value="">--Select--</option>
                                    <?php foreach($sales as $skey){ ?>
                                    <option value="<?=$skey["id"];?>" <?php if($query[0]["sales_fk"]==$skey["id"]){ echo " selected"; } ?>><?=$skey["first_name"]." ".$skey["last_name"];?></option>
                                    <?php } ?>
                                </select>
                                <span style="color: red;"><?= form_error('sales_fk'); ?></span>
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
</div>
</div>
