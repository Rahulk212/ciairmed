<!-- Page Heading -->
<style>
    #table_body td:nth-child(2n+1) {
        width: 100px;
    }
</style>
<div class="content-wrapper">
    <section class="content-header">
        <h1>Expense<small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>hrm/expense/"><i class="fa fa-money"></i> Expense List</a></li>
            <li class="active"> Add Expense</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary" style="border-color:#bf2d37;">
                    <div class="panel-heading" style="background-color:#bf2d37;">
                        <!-- form start -->
                        <h3 class="panel-title">Add Expense</h3>
                    </div>
                    <form role="form" id="expense_form" action="<?php echo base_url(); ?>hrm/expense/add" method="post" enctype="multipart/form-data">
                        <div class="panel-body">
                            <?php if ($error) { ?>
                                <div class="alert alert-danger alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $error; ?>
                                </div>
                            <?php } ?>
                            <div class="col-md-12">
                                <div class="form-group col-sm-12  pdng_0">
                                    <label for="exampleInputFile" class="col-sm-3 pdng_0">Item Name <span style="color:red;">*</span></label> 
                                    <div class="col-sm-6 pdng_0">
                                        <input type="text" id="name" name="name" placeholder="Item Name" class="form-control" value="<?php echo set_value('name'); ?>"/>
                                        <span style="color:red;"><?php echo form_error('name'); ?></span>
                                    </div>
                                </div>
                                <div class="form-group col-sm-12  pdng_0">
                                    <label for="exampleInputFile" class="col-sm-3 pdng_0">Purchase From </label> 
                                    <div class="col-sm-6 pdng_0">
                                        <input type="text" id="from" name="from" placeholder="Purchase From" class="form-control" value="<?php echo set_value('from'); ?>"/>
                                        <span style="color:red;"><?php echo form_error('from'); ?></span>
                                    </div>
                                </div>
                                <div class="form-group col-sm-12  pdng_0">
                                    <label for="exampleInputFile" class="col-sm-3 pdng_0">Purchase Date </label> 
                                    <div class="col-sm-6 pdng_0">
                                        <input type="text" id="date" name="date" placeholder="Purchase Date" class="datepicker-input form-control" value="<?php echo set_value('date'); ?>"/>
                                        <span style="color:red;"><?php echo form_error('date'); ?></span>
                                    </div>
                                </div>
                                <div class="form-group col-sm-12  pdng_0">
                                    <label for="exampleInputFile" class="col-sm-3 pdng_0">Amount <i class="fa fa-inr"></i> <span style="color:red;">*</span></label> 
                                    <div class="col-sm-6 pdng_0">
                                        <input type="text" id="amount" name="amount" placeholder="Price of Item" class="form-control" value="<?php echo set_value('amount'); ?>"/>
                                        <span style="color:red;"><?php echo form_error('amount'); ?></span>
                                    </div>
                                </div>
                                <div class="form-group col-sm-12  pdng_0">
                                    <label for="exampleInputFile" class="col-sm-3 pdng_0">Attach Bill </label> 
                                    <div class="col-sm-6 pdng_0">
                                        <input type="file" name="bill">
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
<script  type="text/javascript">
    $(function () {
        setTimeout(function () {
            $(".alert").hide('blind', {}, 500)
        }, 5000);
    });
</script> 