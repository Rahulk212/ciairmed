<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Expense
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>expense_master/expense_list"><i class="fa fa-users"></i>City</a></li>

        </ol>
    </section>
<?php $category = $_GET['expense_category_fk'];?>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Expense List</h3>

                        <a style="float:right;" href='<?php echo base_url(); ?>expense_master/expense_add' class="btn btn-primary btn-sm"><i class="fa fa-plus-circle" ></i><strong > Add</strong></a>
                                    <a style="float:right;" href='<?php echo base_url(); ?>expense_master/expense_export' class="btn btn-primary btn-sm" ><i class="fa fa-download" ></i><strong > Excel</strong></a>
                                    <a style="float:right;" href="javascript:void(0);" onclick="printData();" class="btn btn-primary btn-sm"><i class="fa fa-print" aria-hidden="true"></i>
Print Report</a>

                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="widget">
                            <?php if (isset($success) != NULL) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $success['0']; ?>
                                </div>
                            <?php } ?>

                        </div>
                        <?php /*
                          <?php $attributes = array('class' => 'form-horizontal', 'method' => 'get', 'role' => 'form'); ?>

                          <?php echo form_open('admin_master/admin_list', $attributes); ?>

                          <div class="col-md-3">
                          <input type="text" class="form-control" name="user" placeholder="Username" value="<?php if(isset($username) != NULL){ echo $username; } ?>" />
                          </div>
                          <div class="col-md-3">
                          <input type="text" class="form-control" name="email" placeholder="Email" value="<?php if(isset($email) != NULL){ echo $email; } ?>"/>
                          </div>
                          <input type="submit" value="Search" class="btn btn-primary btn-md">


                          </form>

                          <br>
                         */ ?>
                        <div class="tableclass">
                            <form role="form" action="<?php echo base_url(); ?>expense_master/expense_list" method="get" enctype="multipart/form-data">
                                <div class="col-md-3" style="width:150px;">
                                    <input type="text" placeholder="Expense Date" class="form-control datepicker-input" name="start_date" value="<?php echo $start_date; ?>"/>
                                  
                                </div>
                                <div class="col-md-3" style="width:150px;">
                                    <input type="text" placeholder="Expense Date" class="form-control datepicker-input" name="end_date" value="<?php echo $end_date; ?>"/>
                                  
                                </div>
                               
                                <div class="col-md-3" style="width:250px;">
<select class="form-control chosen-select" tabindex="-1"  data-placeholder="Select Customer" name="branch_fk">
 
                                                    <option value="">All</option>
                                                    <?php foreach ($branch as $branches) { ?>
                                                        <option value="<?php echo $branches['branch_fk']; ?>" <?php if ($branch_fk == $branches['branch_fk']) {
                                                        echo "selected";
                                                    } ?> ><?php echo ucwords(strtolower($branches['branch_name'])); ?></option>
<?php } ?>
                                                </select>                                
                                </div>
                                
                             <div class="col-md-3" style="width:250px;">
<select class="form-control chosen-select" tabindex="-1"  data-placeholder="Select Customer" name="expense_category_fk">
                                                    <option value="">Select Category</option>
                                                    <?php foreach ($expensecate as $cate) { ?>
                                                        <option value="<?php echo $cate['id']; ?>" <?php if ($category == $cate['id']) {
                                                        echo "selected";
                                                    } ?> ><?php echo ucwords(strtolower($cate['name'])); ?></option>
<?php } ?>
                                                </select>                                
                                </div>
                              
                                
                                <input type="submit" name="search" class="btn btn-success" value="Search" />
                                 <div id="prnt_rpt">
                                <table id="example3" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Branch</th>
                                            <th>Category</th>
                                            <th>Added By</th>
                                            <th>Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       
                                 
                                        <?php
                                        $cnt = 1;
                                        foreach ($query as $row) {
                                            ?>

                                            <tr>
                                                <td style="text-align: center;"><?php echo $cnt; ?></td>
                                                 <td style="text-align: center;"><?php $old_date = $row['expense_date']; $new_date = date('d-m-Y',strtotime($old_date));  echo $new_date; ?></td>
                                                    <td style="text-align: center;"><?php echo $row['amount']; ?></td>
                                                    <td style="text-align: center;"><?php echo $row['branch_name']; ?></td>
                                                 <td style="text-align: center;"><?php echo ucwords($row['CategoryName']); ?></td>
                                                 <td style="text-align: center;"><?php echo ucwords($row['AdminName']); ?></td>
                                                <td style="text-align: center;"><?php echo ucwords($row['description']); ?></td>
                                                <td>

<!--                                                    <a href='<?php echo base_url(); ?>Expense_master/expense_edit/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-edit"></i></a> 

                                                    <a  href='<?php echo base_url(); ?>Expense_master/expense_delete/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i class="fa fa-trash-o"></i></a>      -->
                                                </td>
                                            </tr>
                                            
                                            <?php
                                            $cnt++;
                                        }if (empty($query)) {
                                            ?>
                                            <tr>
                                                <td colspan="12">No records found</td>
                                            </tr>
<?php } ?>
                                    
                                    </tbody>
                                </table>
                                 </div>
                            </form>
                        </div>
                        <div style="text-align:right;" class="box-tools">
                            <ul class="pagination pagination-sm no-margin pull-right">
<?php echo $links; ?>
                            </ul>
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<script type="text/javascript">
    $(function () {
        $("#example1").dataTable();
        $('#example3').dataTable({
            "bPaginate": false,
            "bLengthChange": false,
            "bFilter": true,
            "bSort": false,
            "bInfo": false,
            "bAutoWidth": false
        });
    });
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
<script type="text/javascript">
function printData()
                                {
                                    var divToPrint = document.getElementById("prnt_rpt");
                                    newWin = window.open("");
                                    newWin.document.write(divToPrint.outerHTML);
                                    newWin.print();
                                    newWin.close();
                                }
</script>