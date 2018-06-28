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
            <li class="active"> Edit Employee</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
        </div>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
                <section class="content">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel panel-primary" style="border-color:#bf2d37;">
                                <form role="form" id="personal_form" action="<?php echo base_url(); ?>hrm/employee/personal_edit/<?php echo $cid; ?>" method="post" enctype="multipart/form-data">
                                    <div class="panel-heading" style="background-color:#bf2d37; width:100%; float:left; padding:0 15px;">
                                        <!-- form start -->
                                        <h3 class="panel-title" style="width:auto; float:left; margin-bottom: 10px; margin-top: 10px;">Personal Details
                                        </h3>
                                        <button onclick="personal_data();" id="personal_button" type="button" data-loading-text="Updating..." class="btn btn-default pull-right" style="display: inline-block;  margin-top: 5px; margin-bottom: 5px;">
                                            <i class="fa fa-save"></i> Save </button>
                                    </div>
                                    <div class="panel-body" style="display:inline-block;">
                                        <div class="widget">
                                            <div class="alert alert-success" style="display:none;" id="personal_alert">
                                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                                <span>Personal Details Successfully updated.</span>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Photo </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <?php if ($query->photo == "") { ?>
                                                        <img src="<?php echo base_url(); ?>upload/employee/default_iamge.png" height="150px" width="150px" id="show_photo">
                                                    <?php } else { ?>
                                                        <img src="<?php echo base_url(); ?>upload/employee/<?php echo $query->photo; ?>" height="150px" width="150px" id="show_photo">
                                                    <?php } ?>
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
                                                    <input type="text" id="name" name="name" placeholder="Employee Name" class="form-control" value="<?php echo $query->name; ?>"/>
                                                    <span style="color:red;"><?php echo form_error('name'); ?></span>
                                                </div>
                                                <span style="color:red;" id="name_error"></span>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Father's Name </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" id="father" name="father" placeholder="Father Name" value="<?php echo $query->father_name; ?>" class="form-control"/>
                                                </div>
                                                <span style="color:red;" id="father_error"></span>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Date of Birth </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" id="dob" class="form-control" name="dob" value="<?php echo $query->date_of_birth; ?>"/> 
                                                </div>
                                                <span style="color:red;" id="dob_error"></span>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Gender </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <select name="gender" id="gender" class="form-control">
                                                        <option value="">--Select--</option>
                                                        <option value="male" <?php
                                                        if ($query->gender == "male") {
                                                            echo "selected";
                                                        }
                                                        ?>>Male</option>
                                                        <option value="female" <?php
                                                        if ($query->gender == "female") {
                                                            echo "selected";
                                                        }
                                                        ?>>Female</option>
                                                    </select> 
                                                    <span style="color:red;" id="gender_error"></span>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0"> Phone </label> 
                                                <div class="col-sm-9 pdng_0">
                                                    <input class="form-control" name="phone" id="phone" type="text" value="<?php echo $query->phone; ?>">
                                                </div>
                                                <span style="color:red;" id="phone_error"></span>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Local Address </label> 
                                                <div class="col-sm-9 pdng_0">
                                                    <textarea id="local_address" name="local_address" class="form-control"><?php echo $query->address; ?></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Permanent Address </label> 
                                                <div class="col-sm-9 pdng_0">
                                                    <textarea id="permanent_address" name="permanent_address" class="form-control"><?php echo $query->permanent_address; ?></textarea>
                                                </div>
                                            </div>
                                            <h3>Account Login</h3>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Email <span style="color:red;">*</span></label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input class="form-control" name="email" id="email" value="<?php echo $query->email; ?>" type="email">
                                                    <span style="color:red;"><?php echo form_error('email'); ?></span>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Password <span style="color:red;">*</span></label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="password" name="password" id="password" class="form-control" value="<?php echo $query->password; ?>">
                                                    <span style="color:red;"><?php echo form_error('password'); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="panel panel-primary" style="border-color:#bf2d37;">
                                <form role="form" id="company_form" action="<?php echo base_url(); ?>hrm/employee/company_edit/<?php echo $cid; ?>" method="post" enctype="multipart/form-data">
                                    <div class="panel-heading" style="background-color:#bf2d37; width:100%; float:left; padding:0 15px;">
                                        <!-- form start -->
                                        <h3 class="panel-title" style="width:auto; float:left; margin-bottom: 10px; margin-top: 10px;">Company Details
                                        </h3>
                                        <button onclick="company_data();" id="company_button" type="button" data-loading-text="Updating..." class="btn btn-default pull-right" style="display: inline-block;  margin-top: 5px; margin-bottom: 5px;">
                                            <i class="fa fa-save"></i> Save </button>
                                    </div>
                                    <div class="panel-body" style="display:inline-block;">
                                        <div class="widget">
                                            <div class="alert alert-success" style="display:none;" id="company_alert">
                                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                                <span>Company Details Successfully updated</span>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Employee ID <span style="color:red;">*</span></label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="employee_id" id="employee_id" placeholder="Employee ID" value="<?php echo $query->employee_id; ?>" class="form-control" readonly>
                                                    <span style="color:red;"><?php echo form_error('employee_id'); ?></span>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Company Email</label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="company_email" id="company_email" placeholder="Company Email" value="<?php echo $query->company_email; ?>" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Company Mobile</label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="company_mobile" id="company_mobile" placeholder="Company Mobile" value="<?php echo $query->company_mobile; ?>" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Department </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <select class="form-control" name="department" id="department" onchange="designation_list(this.value);">
                                                        <option value="">--Select--</option>
                                                        <?php foreach ($department_list as $department) { ?>
                                                            <option value="<?php echo $department->id; ?>" <?php
                                                            if ($query->department == $department->id) {
                                                                echo "selected";
                                                            }
                                                            ?>><?php echo ucwords($department->name); ?></option>
                                                                <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Designation </label>
                                                <div class="col-sm-9 pdng_0" id="designation_div">
                                                    <select class="form-control" name="designation" id="designation">
                                                        <option value="">--Select--</option>
                                                        <?php foreach ($designation_list as $designation) { ?>
                                                            <option value="<?php echo $designation->id; ?>" <?php
                                                            if ($query->designation == $designation->id) {
                                                                echo "selected";
                                                            }
                                                            ?>><?php echo ucwords($designation->name); ?></option>
                                                                <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Date of Joining </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="date_joining" id="date_joining" value="<?php echo $query->date_of_joining; ?>" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Joining Salary</label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="joinnig_salary" id="joinnig_salary" placeholder="Current Salary" value="<?php echo $query->joining_salary; ?>" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="panel panel-primary" style="border-color:#bf2d37;">
                                <form role="form" id="bank_account_form" action="<?php echo base_url(); ?>hrm/employee/bank_account_edit/<?php echo $cid; ?>" method="post" enctype="multipart/form-data">
                                    <div class="panel-heading" style="background-color:#bf2d37; width:100%; float:left; padding:0 15px;">
                                        <!-- form start -->
                                        <h3 class="panel-title" style="width:auto; float:left; margin-bottom: 10px; margin-top: 10px;">Bank Account Details
                                        </h3>
                                        <button onclick="bank_account_data();" id="bank_account_button" type="button" data-loading-text="Updating..." class="btn btn-default pull-right" style="display: inline-block;  margin-top: 5px; margin-bottom: 5px;">
                                            <i class="fa fa-save"></i> Save </button>
                                    </div>
                                    <div class="panel-body" style="display:inline-block;">
                                        <div class="widget">
                                            <div class="alert alert-success" style="display:none;" id="bank_account_alert">
                                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                                <span>Bank Details Successfully updated</span>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Account Holder Name </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="holder_name" id="holder_name" placeholder="Account Holder Name" value="<?php echo $query->bank_holder_name; ?>" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Account Number </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="account_number" id="account_number" placeholder="Account Number" value="<?php echo $query->bank_account_number; ?>" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Bank Name </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="bank_name" id="bank_name" placeholder="BANK Name" value="<?php echo $query->bank_name; ?>" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">IFSC Code </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="ifsc_code" id="ifsc_code" placeholder="IFSC Code" value="<?php echo $query->ifsc_code; ?>" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">PAN Number </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="pan_number" id="pan_number" placeholder="PAN Number" value="<?php echo $query->pan_number; ?>" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Branch </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="branch" id="branch" placeholder="BRANCH" value="<?php echo $query->branch; ?>" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="panel panel-primary" style="border-color:#bf2d37;">
                                <form role="form" id="document_form" action="<?php echo base_url(); ?>hrm/employee/document_edit/<?php echo $cid; ?>" method="post" enctype="multipart/form-data">
                                    <div class="panel-heading" style="background-color:#bf2d37; width:100%; float:left; padding:0 15px;">
                                        <!-- form start -->
                                        <h3 class="panel-title" style="width:auto; float:left; margin-bottom: 10px; margin-top: 10px;">Documents
                                        </h3>
                                        <button onclick="document_data();" id="document_button" type="button" data-loading-text="Updating..." class="btn btn-default pull-right" style="display: inline-block;  margin-top: 5px; margin-bottom: 5px;">
                                            <i class="fa fa-save"></i> Save </button>
                                    </div>
                                    <div class="panel-body" style="display:inline-block;">
                                        <div class="widget">
                                            <div class="alert alert-success" style="display:none;" id="document_alert">
                                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                                <span>Documents Successfully updated</span>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
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
                                            <div class="col-md-6" id="document_div">
                                                <div class="form-group col-sm-12  pdng_0">
                                                    <?php if ($query->resume != '') { ?>
                                                        <a class="btn btn-primary" style="background-color:#bf2d37;" href="<?php echo base_url(); ?>upload/employee/<?php echo $query->resume; ?>" target="_blank">View Resume</a>
                                                        <a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Remove" onclick="document_delete_data('resume');" class="btn btn-primary" style="background-color:#bf2d37;"><i class="fa fa-trash-o"></i></a>
                                                    <?php } ?>
                                                </div>
                                                <div class="form-group col-sm-12  pdng_0">
                                                    <?php if ($query->offer_letter != '') { ?>
                                                        <a class="btn btn-primary" style="background-color:#bf2d37;" href="<?php echo base_url(); ?>upload/employee/<?php echo $query->offer_letter; ?>" target="_blank">Offer Letter</a>
                                                        <a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Remove" onclick="document_delete_data('offer');" class="btn btn-primary" style="background-color:#bf2d37;"><i class="fa fa-trash-o"></i></a>
                                                    <?php } ?>
                                                </div>
                                                <div class="form-group col-sm-12  pdng_0">
                                                    <?php if ($query->joining_letter != '') { ?>
                                                        <a class="btn btn-primary" style="background-color:#bf2d37;" href="<?php echo base_url(); ?>upload/employee/<?php echo $query->joining_letter; ?>" target="_blank">Joining Letter</a>
                                                        <a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Remove" onclick="document_delete_data('joining');" class="btn btn-primary" style="background-color:#bf2d37;"><i class="fa fa-trash-o"></i></a>
                                                    <?php } ?>
                                                </div>
                                                <div class="form-group col-sm-12  pdng_0">
                                                    <?php if ($query->contract_agreement != '') { ?>
                                                        <a class="btn btn-primary" style="background-color:#bf2d37;" href="<?php echo base_url(); ?>upload/employee/<?php echo $query->contract_agreement; ?>" target="_blank">View Contract</a>
                                                        <a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Remove" onclick="document_delete_data('contract');" class="btn btn-primary" style="background-color:#bf2d37;"><i class="fa fa-trash-o"></i></a>
                                                    <?php } ?>
                                                </div>
                                                <div class="form-group col-sm-12  pdng_0">
                                                    <?php if ($query->id_proof != '') { ?>
                                                        <a class="btn btn-primary" style="background-color:#bf2d37;" href="<?php echo base_url(); ?>upload/employee/<?php echo $query->id_proof; ?>" target="_blank">View ID Proof</a>
                                                        <a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Remove" onclick="document_delete_data('proof');" class="btn btn-primary" style="background-color:#bf2d37;"><i class="fa fa-trash-o"></i></a>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--                                    <div class="panel-footer">
                                                                            <button class="btn btn-primary" type="submit"> UPDATE</button>
                                                                        </div>-->
                            </div>
                        </div>
                    </div>
                </section>
            </div><!-- /.tab-pane -->
        </div><!-- /.tab-content -->
        <!-- /.row -->
    </section>
</div>
<script>
    function remove_photo() {
        var img = "<?php echo $query->photo; ?>";
        if (img == "") {
            var url = "<?php echo base_url(); ?>upload/employee/default_iamge.png";
        } else {
            var url = "<?php echo base_url(); ?>upload/employee/<?php echo $query->photo; ?>";
                    }
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
                function personal_data() {
                    $("#personal_button").attr("disabled", true);
                    var data = new FormData($('#personal_form')[0]);
                    var path = "<?php echo base_url(); ?>hrm/employee/personal_edit/<?php echo $cid; ?>";
                            //alert(path);
                            $.ajax({
                                type: "POST",
                                url: path,
                                data: data,
                                mimeType: "multipart/form-data",
                                contentType: false,
                                cache: false,
                                processData: false,
                                success: function (data)
                                {
                                    $("#personal_alert").show();
                                    $("#personal_button").attr("disabled", false);
                                }
                            });

                        }
                        function company_data() {
                            $("#company_button").attr("disabled", true);
                            var data = new FormData($('#company_form')[0]);
                            var path = "<?php echo base_url(); ?>hrm/employee/company_edit/<?php echo $cid; ?>";
                                    //alert(path);
                                    $.ajax({
                                        type: "POST",
                                        url: path,
                                        data: data,
                                        mimeType: "multipart/form-data",
                                        contentType: false,
                                        cache: false,
                                        processData: false,
                                        success: function (data)
                                        {
                                            $("#company_alert").show();
                                            $("#company_button").attr("disabled", false);
                                        }
                                    });

                                }
                                function bank_account_data() {
                                    $("#bank_account_button").attr("disabled", true);
                                    var data = new FormData($('#bank_account_form')[0]);
                                    var path = "<?php echo base_url(); ?>hrm/employee/bank_account_edit/<?php echo $cid; ?>";
                                            //alert(path);
                                            $.ajax({
                                                type: "POST",
                                                url: path,
                                                data: data,
                                                mimeType: "multipart/form-data",
                                                contentType: false,
                                                cache: false,
                                                processData: false,
                                                success: function (data)
                                                {
                                                    $("#bank_account_alert").show();
                                                    $("#bank_account_button").attr("disabled", false);
                                                }
                                            });

                                        }
                                        function document_data() {
                                            $("#document_button").attr("disabled", true);
                                            var data = new FormData($('#document_form')[0]);
                                            var path = "<?php echo base_url(); ?>hrm/employee/document_edit/<?php echo $cid; ?>";
                                                    //alert(path);
                                                    $.ajax({
                                                        type: "POST",
                                                        url: path,
                                                        data: data,
                                                        mimeType: "multipart/form-data",
                                                        contentType: false,
                                                        cache: false,
                                                        processData: false,
                                                        success: function (data)
                                                        {
                                                            $("#resume").val("");
                                                            $("#offer_letter").val("");
                                                            $("#joining_letter").val("");
                                                            $("#id_proof").val("");
                                                            $("#contract_agreement").val("");
                                                            $("#document_alert").show();
                                                            $("#document_button").attr("disabled", false);
                                                            $("#document_div").empty();
                                                            $("#document_div").html(data);
                                                        }
                                                    });

                                                }
                                                function document_delete_data(type) {
                                                    var test = confirm('Are you sure you want to remove this data?');
                                                    if(test == true) {
                                                    var path = "<?php echo base_url(); ?>hrm/employee/document_delete/<?php echo $cid; ?>";
                                                    //alert(path);
                                                    $.ajax({
                                                        type: "POST",
                                                        url: path,
                                                        data: {types:type},
                                                        success: function (data)
                                                        {
                                                            $("#document_alert").show();
                                                            $("#document_button").attr("disabled", false);
                                                            $("#document_div").empty();
                                                            $("#document_div").html(data);
                                                        }
                                                    });
                                                }
                                                }

</script>
<script>
    $(function () {
        setTimeout(function () {
            $(".alert").hide('blind', {}, 500)
        }, 5000);
    });
</script>