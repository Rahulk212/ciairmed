<!-- Page Heading -->
<style>
    #table_body td:nth-child(2n+1) {
        width: 100px;
    }
</style>
<div class="content-wrapper">
    <section class="content-header">
        <h1>Employee<small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>hrm/employee"><i class="fa fa-users"></i> Employee List</a></li>
            <li class="active"> Add Employee</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
        </div>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
                <form role="form" id="department_form" action="<?php echo base_url(); ?>hrm/employee/add" method="post" enctype="multipart/form-data">
                    <section class="content">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="panel panel-primary" style="border-color:#bf2d37;">
                                    <div class="panel-heading" style="background-color:#bf2d37;">
                                        <!-- form start -->
                                        <h3 class="panel-title">Personal Details</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="widget">
                                            <?php if ($error) { ?>
                                                <div class="alert alert-danger alert-dismissable">
                                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                                    <?php echo $error; ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Photo </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <img src="<?php echo base_url(); ?>upload/employee/default_iamge.png" height="150px" width="150px" id="show_photo">
                                                    <br>
                                                    <a class="label label-danger" href="javascript:void(0);" id="remove_button" onclick="remove_photo();" style="display:none;">Remove</a><br>
                                                    <input type="file" name="photo" id="photo" class="upload_image">
                                                    <p><span class="label label-danger"> NOTE! </span>
                                                        Image Size must be (872px by 724px) </p>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Name <span style="color:red;">*</span></label> 
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" id="name" name="name" placeholder="Employee Name" class="form-control" value="<?php echo set_value('name'); ?>"/>
                                                    <span style="color:red;"><?php echo form_error('name'); ?></span>
                                                </div>
                                                <span style="color:red;" id="name_error"></span>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Father's Name </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" id="father" name="father" placeholder="Father Name" value="<?php echo set_value('father'); ?>" class="form-control"/>
                                                </div>
                                                <span style="color:red;" id="father_error"></span>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Date of Birth </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" id="dob" class="form-control datepicker-input" name="dob" value="<?php echo set_value('dob'); ?>"/> 
                                                </div>
                                                <span style="color:red;" id="dob_error"></span>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Gender </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <select name="gender" id="gender" class="form-control">
                                                        <option value="">--Select--</option>
                                                        <option value="male">Male</option>
                                                        <option value="female">Female</option>
                                                    </select> 
                                                    <span style="color:red;" id="gender_error"></span>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0"> Phone </label> 
                                                <div class="col-sm-9 pdng_0">
                                                    <input class="form-control" name="phone" id="phone" type="text" value="<?php echo set_value('phone'); ?>">
                                                </div>
                                                <span style="color:red;" id="phone_error"></span>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Local Address </label> 
                                                <div class="col-sm-9 pdng_0">
                                                    <textarea id="local_address" name="local_address" class="form-control"><?php echo set_value('local_address'); ?></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Permanent Address </label> 
                                                <div class="col-sm-9 pdng_0">
                                                    <textarea id="permanent_address" name="permanent_address" class="form-control"><?php echo set_value('permanent_address'); ?></textarea>
                                                </div>
                                            </div>
                                            <h3>Account Login</h3>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Email <span style="color:red;">*</span></label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input class="form-control" name="email" id="email" value="<?php echo set_value('email'); ?>" type="email">
                                                    <span style="color:red;"><?php echo form_error('email'); ?></span>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Password <span style="color:red;">*</span></label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="password" name="password" id="password" class="form-control">
                                                    <span style="color:red;"><?php echo form_error('password'); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="panel panel-primary" style="border-color:#bf2d37;">
                                    <div class="panel-heading" style="background-color:#bf2d37;">
                                        <h3 class="panel-title">Company Details</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-md-12">
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Employee ID <span style="color:red;">*</span></label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="employee_id" id="employee_id" placeholder="Employee ID" value="<?php echo set_value('employee_id'); ?>" class="form-control">
                                                    <span style="color:red;"><?php echo form_error('employee_id'); ?></span>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Company Email</label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="company_email" id="company_email" placeholder="Company Email" value="<?php echo set_value('company_email'); ?>" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Company Mobile</label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="company_mobile" id="company_mobile" placeholder="Company Mobile" value="<?php echo set_value('company_mobile'); ?>" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Department </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <select class="form-control" name="department" id="department" onchange="designation_list(this.value);">
                                                        <option value="">--Select--</option>
                                                        <?php foreach ($department_list as $department) { ?>
                                                            <option value="<?php echo $department->id; ?>"><?php echo ucwords($department->name); ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Designation </label>
                                                <div class="col-sm-9 pdng_0" id="designation_div">
                                                    <select class="form-control" name="designation" id="designation">
                                                        <option value="">--Select--</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Date of Joining </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="date_joining" id="date_joining" value="<?php echo set_value('date_joining'); ?>" class="form-control datepicker-input">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Joining Salary</label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="joinnig_salary" id="joinnig_salary" placeholder="Current Salary" value="<?php echo set_value('joinnig_salary'); ?>" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="panel panel-primary" style="border-color:#bf2d37;">
                                    <div class="panel-heading" style="background-color:#bf2d37;">
                                        <h3 class="panel-title">Bank Account Details</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-md-12">
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Account Holder Name </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="holder_name" id="holder_name" placeholder="Account Holder Name" value="<?php echo set_value('holder_name'); ?>" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Account Number </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="account_number" id="account_number" placeholder="Account Number" value="<?php echo set_value('account_number'); ?>" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Bank Name </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="bank_name" id="bank_name" placeholder="BANK Name" value="<?php echo set_value('bank_name'); ?>" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">IFSC Code </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="ifsc_code" id="ifsc_code" placeholder="IFSC Code" value="<?php echo set_value('ifsc_code'); ?>" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">PAN Number </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="pan_number" id="pan_number" placeholder="PAN Number" value="<?php echo set_value('pan_number'); ?>" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Branch </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="branch" id="branch" placeholder="BRANCH" value="<?php echo set_value('branch'); ?>" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="panel panel-primary" style="border-color:#bf2d37;">
                                    <div class="panel-heading" style="background-color:#bf2d37;">
                                        <h3 class="panel-title">Documents</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="widget">
                                            <?php if ($error_doc) { ?>
                                                <div class="alert alert-danger alert-dismissable">
                                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                                    <?php echo $error_doc; ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Resume </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="file" name="resume" id="resume">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Offer Letter </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="file" name="offer_letter" id="offer_letter">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Joining Latter </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="file" name="joining_letter" id="joining_letter">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Contract and Agreement </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="file" name="contract_agreement" id="contract_agreement">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">ID Proof </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="file" name="id_proof" id="id_proof">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-footer">
                                        <button class="btn btn-primary" type="submit"><i class="fa fa-plus"></i> ADD</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </form>
            </div><!-- /.tab-pane -->
        </div><!-- /.tab-content -->
        <!-- /.row -->
    </section>
</div>
<script>
    function remove_photo() {
        var url = "<?php echo base_url(); ?>upload/employee/default_iamge.png";
        $('#show_photo').attr('src', url);
        $("#remove_button").hide();
        $(".upload_image").val('');
    }
    function designation_list(value) {
        var url = "<?php echo base_url(); ?>hrm/employee/get_designation";
        $.ajax({
            type: "POST",
            url: url,
            data: {"did": value}, // serializes the form's elements.
            success: function (data)
            {
                $("#designation_div").empty();
                $("#designation_div").html(data);
            }
        });
    }
    function readURL(input) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {

                $('#show_photo').attr('src', e.target.result);
                $("#remove_button").show();
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $(".upload_image").change(function () {
        readURL(this);
    });
</script>
<script>
    $(function () {
        setTimeout(function () {
            $(".alert").hide('blind', {}, 500)
        }, 5000);
    });
</script>