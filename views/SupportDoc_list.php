<style>
    .round {
        display: inline-block;
        height: 30px;
        width: 30px;
        line-height: 30px;
        -moz-border-radius: 15px;
        border-radius: 15px;
        background-color: #222;    
        color: #FFF;
        text-align: center;  
    }
    .round.round-sm {
        height: 10px;
        width: 10px;
        line-height: 10px;
        -moz-border-radius: 10px;
        border-radius: 10px;
        font-size: 0.7em;
    }
    .round.blue {
        background-color: #3EA6CE;
    }
</style>

<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />

<div class="content-wrapper" id="extension-settings">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Support System

        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard1"><i class=" fa fa-dashboard"></i>Dashboard</a></li>
            <li>Support System List</li>

        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Support System List</h3>

                        <a style="float:right;" href='<?php echo base_url(); ?>supportDoc_system/supportDoc_add' class="btn btn-primary btn-sm" ><center><i class="fa fa-plus-circle" ></i>&nbsp <strong>Add</strong></center></a>

                    </div><!-- /.box-header -->

                    <div class="box-body">

                        <div class="widget">
                            <?php if (isset($success) != NULL) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $success; ?>
                                </div>
                            <?php } ?>

                        </div>

                        <div class="tableclass">
                            <?php echo form_open("Cust_Family_master", array("method" => 'GET')); ?>
                            <table id="example3" class="table table-bordered table-striped">
                                <thead>
                                    <tr>

                                        <th>No</th>
                                        <th width="60%">Message</th>
                                        <th>Created By</th>
                                        <th>Created Date Time</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>


                                    <?php
                                    $cnt1 = 1;

                                    foreach ($query as $row) {
                                        ?>

                                        <tr>
                                            <td><?php echo $cnt1 + $cnt; ?></td>
                                            <td><?php echo ucfirst($row['message']); ?></td>
                                            <td><?php echo ucfirst($row['name']); ?></td>
                                            <td><?php echo $row['created_date']; ?></td>
                                            <td>
                                                <?php
                                                if ($row['status'] == 1) {
                                                    echo "<span class='label label-warning'>Pending</span>";
                                                }
                                                ?>
                                                <?php
                                                if ($row['status'] == 2) {
                                                    echo "<span class='label label-success'>Completed</span>";
                                                }
                                                ?>

                                            </td>

                                            <td> 
                                                <a  href='<?php echo base_url(); ?>SupportDoc_system/supportDoc_edit/<?php echo $row['id']; ?>' <i style="margin-left:3px; "class="fa fa-edit"> </i> </a>  
                                                <a  href='<?php echo base_url(); ?>SupportDoc_system/supportdoc_delete/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i style="margin-left:10px; " class="fa fa-trash-o"></i></a> 

                                                <?php if ($row['status'] == "2") {
                                                    ?>
                                                    <a  href='<?php echo base_url(); ?>SupportDoc_system/supportdoc_pending/<?php echo $row['id']; ?>'
                                                        data-toggle="tooltip" data-original-title="Mark As Pending" ><span style="margin-left:10px; " class="label label-warning"><i class="fa fa-check"></i></span></a>   

                                                <?php } else { ?>

                                                    <a  href='<?php echo base_url(); ?>SupportDoc_system/supportdoc_complate/<?php echo $row['id']; ?>'
                                                        data-toggle="tooltip" data-original-title="Mark As Complate"><span style="margin-left:10px; " class="label label-success"><i class="fa fa-check"></i></span> </a>   

                                                <?php } ?>


                                            </td>
                                        </tr>
                                        <?php
                                        $cnt1++;
                                    }
                                    ?>

                                </tbody>

                            </table>
                            <div style="text-align:right;" class="box-tools">
                                <ul class="pagination pagination-lm no-margin pull-right">
                                    <?php echo $links; ?>
                                </ul>
                            </div>

                            <?php echo form_close(); ?>
                        </div>





                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
<script  type="text/javascript">

                                                jQuery(".chosen-select").chosen({
                                                    search_contains: true
                                                });
                                                //  $(".chosen-select-deselect").chosen({ allow_single_deselect: true });
                                                // $("#cid").chosen('refresh');

</script> 
<script type="text/javascript">
    /*  $(function () {
     
     $('#example3').dataTable({
     //"bPaginate": false,
     "bLengthChange": false,
     "bFilter": false,
     "bSort": false,
     "bInfo": false,
     "bAutoWidth": false
     });
     }); */
</script>

<script type="text/javascript">
    window.setTimeout(function () {
        $(".alert").fadeTo(500, 0).slideUp(500, function () {
            $(this).remove();
        });
    }, 4000);</script>

































