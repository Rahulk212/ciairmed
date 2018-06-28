<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<section class="content-header">
    <h1>
        Expense Category Add
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>Expense_category_master/expense_category_list"><i class="fa fa-users"></i>Expense List</a></li>
        <li class="active">Add Expense Category</li>
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
                <?php if ($this->session->flashdata('duplicate')) { ?>
                                <div class="alert alert-danger alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $this->session->flashdata('duplicate'); ?>
                                </div>
                            <?php } ?>
                <div class="box-body">

                    <div class="col-md-6">
                        <!-- form start -->
                        <form role="form" action="<?php echo base_url(); ?>Expense_master/expense_add" method="post" enctype="multipart/form-data">


                            <div class="form-group">
                                <label for="name">Expense Date</label><span style="color:red">*</span>
                                <input type="text" id="date" name="expense_date" class="form-control datepicker-input" readonly="" value="<?php echo set_value('expense_date'); ?>"/>

                                <?php echo form_error('expense_date'); ?>
                            </div>
                            <div class="form-group">
                                <label for="name">Expense Description</label>
                                <textarea name="description" class="form-control" value=""><?php echo set_value('description'); ?></textarea>
                              
                            </div>
                            
                             <div class="form-group">
                                <label for="name">Amount</label><span style="color:red">*</span>
                                <input type="text"  name="amount" class="form-control" value="<?php echo set_value('amount'); ?>">
                                <?php echo form_error('amount'); ?>
                            </div>
                            
                            <div class="form-group">
                                <label for="name">Expense Category</label><span style="color:red">*</span>
                               <select name="expense_category_fk" class="form-control chosen" >
                                
                                    <option value="">Select Expense Category</option>
                                    <?php foreach($exp_cate as $val){ ?>
                                    <option value="<?php echo $val['id']; ?>" <?php echo set_select('expense_category_fk', $val['id']); ?>><?php echo ucwords($val['name']); ?></option>
                                   <?php  } ?>
                                </select>
                                <?php echo form_error('expense_category_fk'); ?>
                            </div>
                            
                            <div class="form-group">
                                <label for="name">Branch</label><span style="color:red">*</span>
                               <select name="branch_fk" class="form-control chosen">
                                
                                    <option value="" >Select Branch</option>
                                    <?php foreach($branch as $total){ ?>
                                  <option value="<?php echo $total['branch_fk']; ?>" <?php echo set_select('branch_fk', $total['branch_fk']); ?>><?php echo ucwords($total['branch_name']); ?></option>

                                       
                                   <?php  } ?>
                                </select>
                                <?php echo form_error('branch_fk'); ?>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Upload Receipt</label>
                                <input type="file" id="exampleInputFile" name="upload_receipt">
                              
                            </div>

                            <button class="btn btn-primary" type="submit">Add</button>

                        </form>
                    </div><!-- /.box -->
                </div>	
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    window.setTimeout(function () {
        $(".alert").fadeTo(500, 0).slideUp(500, function () {
            $(this).remove();
        });
    }, 4000);
</script>
<script>
    $(document).ready(function () {
      
        var date_input = $('input[name="expense_date"]'); //our date input has the name "date"

        var container = $('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";
        date_input.datepicker({
            format: 'dd/mm/yyyy',
            container: container,
            todayHighlight: true,
            autoclose: true
        });
    });
</script>

