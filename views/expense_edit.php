<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<section class="content-header">
    <h1>
        Expense Update
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>Expense_master/expense_list"><i class="fa fa-users"></i>Expense List</a></li>
        <li class="active">Edit Expense</li>
    </ol>
</section>
<?php //echo "<pre>";print_r($branch);die;?>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?php
            
            foreach ($query as $data)
                $id = $data['id']; {
                ?>
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
                            <form role="form" action="<?php echo base_url(); ?>Expense_master/expense_edit/<?= $id ?>" method="post" enctype="multipart/form-data">

                                <div class="form-group">
                                <label for="name">Expense Date</label><span style="color:red">*</span>
                                <input type="text" id="date" name="expense_date" class="form-control datepicker-input" value="<?php 
                                
                                $old =$data['expense_date'];
                             
                                
                                    $new_date  = date('d-m-Y',strtotime($old));
                                
                                    echo $new_date;
                                    ?>" readonly=""/>

                                <?php echo form_error('expense_date'); ?>
                            </div>
                            <div class="form-group">
                                <label for="name">Expense Description</label>
                                <textarea  name="description" class="form-control" value="">
<?php echo $data['description'];?>
                                </textarea>  
                            </div>
                            
                             <div class="form-group">
                                <label for="name">Amount</label><span style="color:red">*</span>
                                <input type="text"  name="amount" class="form-control" value="<?php echo $data['amount'];?>">
                                <?php echo form_error('amount'); ?>
                            </div>
                            
                            <div class="form-group">
                                <label for="name">Expense Category</label><span style="color:red">*</span>
                               <select name="expense_category_fk" class="form-control chosen">
                                
                                    <option value="">Select Expense Category</option>
                                    <?php foreach($exp_cate as $val){ ?>
                                        
                              <option value="<?php echo $val['id']; ?>" <?php if($val['id'] == $data["expense_category_fk"]){ echo "selected"; } ?>><?php echo ucwords($val['name']); ?></option>
						                <?php   } ?>
                                </select>
                                <?php echo form_error('expense_category_fk'); ?>
                            </div>
                                
                                <div class="form-group">
                                <label for="name">Branch</label>
                               <select name="branch_fk" class="form-control chosen">
                                
                                    <option value="">Select Branch</option>
                                    <?php foreach($branch as $val1){ ?>
                                        
                              <option value="<?php echo $val1['branch_fk']; ?>" <?php if($val1['branch_fk'] == $data["branch_fk"]){ echo "selected"; } ?>><?php echo ucwords($val1['branch_name']); ?></option>
						                <?php   } ?>
                                </select>
                              
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Upload Receipt</label>
                                <input type="file" id="exampleInputFile" name="upload_receipt">
                               
                                <?php $image = $data['upload_receipt'];
                                    if($image !=''){ ?>
                                <img src="<?= base_url(); ?>upload/expense_master/<?php echo $image; ?>" height="100px" width="200px" alt="No Pic"/>
                                    <?php }else{ ?>
                                    <?php } ?>
                                 
                            </div>
                                

                                <button class="btn btn-primary" type="submit">Update</button>
                                <input type="hidden"  name="id" class="form-control" value="<?php echo $id; ?>" >
                                </div>

                            </form>
    <?php
}
?>
                    </div><!-- /.box -->

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
            format: 'dd-mm-yyyy',
            container: container,
            todayHighlight: true,
            autoclose: true
        });
    });
</script>