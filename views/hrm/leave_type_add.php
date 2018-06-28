<!-- Page Heading -->
<style>
    #table_body td:nth-child(2n+1) {
        width: 100px;
    }
</style>
<div class="content-wrapper">
    <section class="content-header">
        <h1>Leave Type<small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>hrm/attendance/leave_types"><i class="fa fa-sitemap"></i> Leave Type List</a></li>
            <li class="active"> Add Leave Type</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary" style="border-color:#bf2d37;">
                    <div class="panel-heading" style="background-color:#bf2d37;">
                        <!-- form start -->
                        <h3 class="panel-title">Add Leave Type</h3>
                    </div>
                    <form role="form" id="holiday_form" action="<?php echo base_url(); ?>hrm/attendance/leave_type_add" method="post" enctype="multipart/form-data">
                        <div class="panel-body">
                            <div class="col-md-12">
                                <div class="form-group col-sm-12  pdng_0">
                                    <label for="exampleInputFile" class="col-sm-3 pdng_0">Leave Type <span style="color:red;">*</span></label> 
                                    <div class="col-sm-6 pdng_0">
                                        <input type="text" id="leave_type" name="leave_type" placeholder="Leave Type" class="form-control" value="<?php echo set_value('leave_type'); ?>"/>
                                        <span style="color:red;"><?php echo form_error('leave_type'); ?></span>
                                    </div>
                                </div>
                                <div class="form-group col-sm-12  pdng_0">
                                    <label for="exampleInputFile" class="col-sm-3 pdng_0">Number of leaves in a year <span style="color:red;">*</span></label> 
                                    <div class="col-sm-6 pdng_0">
                                        <input type="text" id="number_leaves" name="number_leaves" placeholder="Number of leaves in a year" class="form-control" value="<?php echo set_value('number_leaves'); ?>"/>
                                        <span style="color:red;"><?php echo form_error('number_leaves'); ?></span>
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