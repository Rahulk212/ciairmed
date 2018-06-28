<!-- Page Heading -->
<style>
    #table_body td:nth-child(2n+1) {
        width: 100px;
    }
    .chosen-container {
        display: inline-block;
        font-size: 14px;
        position: relative;
        vertical-align: middle;
        width: 100%;
    }
    .chosen-container .chosen-results li.active-result {width: 100% !important;}
</style>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<div class="content-wrapper">
    <section class="content-header">
        <h1>Award<small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>hrm/award/"><i class="fa fa-trophy"></i> Award List</a></li>
            <li class="active"> Add Award</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary" style="border-color:#bf2d37;">
                    <div class="panel-heading" style="background-color:#bf2d37;">
                        <!-- form start -->
                        <h3 class="panel-title">Add Award</h3>
                    </div>
                    <form role="form" id="award_form" action="<?php echo base_url(); ?>hrm/award/add" method="post" enctype="multipart/form-data">
                        <div class="panel-body">
                            <div class="col-md-12">
                                <div class="form-group col-sm-12  pdng_0">
                                    <label for="exampleInputFile" class="col-sm-3 pdng_0">Award Name <span style="color:red;">*</span></label> 
                                    <div class="col-sm-6 pdng_0">
                                        <input type="text" id="name" name="name" placeholder="Award Name" class="form-control" value="<?php echo set_value('name'); ?>"/>
                                        <span style="color:red;"><?php echo form_error('name'); ?></span>
                                    </div>
                                </div>
                                <div class="form-group col-sm-12  pdng_0">
                                    <label for="exampleInputFile" class="col-sm-3 pdng_0">Gift Item <span style="color:red;">*</span></label> 
                                    <div class="col-sm-6 pdng_0">
                                        <input type="text" id="gift" name="gift" placeholder="Gift" class="form-control" value="<?php echo set_value('gift'); ?>"/>
                                        <span style="color:red;"><?php echo form_error('gift'); ?></span>
                                    </div>
                                </div>
                                <div class="form-group col-sm-12  pdng_0">
                                    <label for="exampleInputFile" class="col-sm-3 pdng_0">Cash Price  <i class="fa fa-inr"></i></label> 
                                    <div class="col-sm-6 pdng_0">
                                        <input type="text" id="cash" name="cash" placeholder="Cash Price" class="form-control" value="<?php echo set_value('cash'); ?>"/>
                                        <span style="color:red;"><?php echo form_error('cash'); ?></span>
                                    </div>
                                </div>
                                <div class="form-group col-sm-12  pdng_0">
                                    <label for="exampleInputFile" class="col-sm-3 pdng_0">Employee Name  </label> 
                                    <div class="col-sm-6 pdng_0">
                                        <select id="employee_id" class="chosen-select" name="employee_id">
                                            <option value="">--Select--</option>
                                            <?php foreach ($employee_list as $employee) { ?>
                                                <option value="<?php echo $employee->employee_id; ?>"><?php echo ucwords($employee->name) . " (EmpID: " . $employee->employee_id . ")"; ?></option>
                                            <?php } ?>
                                        </select>
                                        <span style="color:red;"><?php echo form_error('employee_id'); ?></span>
                                    </div>
                                </div>
                                <div class="form-group col-sm-12  pdng_0">
                                    <label for="exampleInputFile" class="col-sm-3 pdng_0">Month/Year </label> 
                                    <div class="col-sm-6 pdng_0">
                                        <div class="col-sm-5 pdng_0">
    <!--                                        <select class="chosen-select">
                                                <option value="">--Select--</option>
                                            <?php foreach ($month_list as $month) { ?>
                                                                <option value="<?php echo $month; ?>"><?php echo ucwords($month); ?></option>
                                            <?php } ?>
                                            </select>-->
                                            <input type="text" class="month form-control" name="month" id="month" value="<?php echo set_value('month'); ?>" placeholder="Month">
                                            <span style="color:red;"><?php echo form_error('month'); ?></span>
                                        </div>
                                        <div class="col-sm-2 pdng_0">
                                        </div>
                                        <div class="col-sm-5 pdng_0">
                                            <input type="text" class="year form-control" name="year" id="year" value="<?php echo set_value('year'); ?>" placeholder="Year">
                                            <span style="color:red;"><?php echo form_error('year'); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <button class="btn btn-primary" type="submit">Add</button>
                        </div>
                    </form>
                </div><!-- /.box -->
            </div>
        </div>
    </section>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
<script  type="text/javascript">
                                $(function () {
                                    $('.chosen-select').chosen();
                                });
</script> 