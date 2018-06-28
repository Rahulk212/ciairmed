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
            Package Category List
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li>Package Category List</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Package Category List</h3>
                        <a style="float:right;" href='<?php echo base_url(); ?>package_category/package_category_add' class="btn btn-primary btn-sm" ><center><i class="fa fa-plus-circle" ></i>&nbsp <strong>Add</strong></center></a>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="widget">
                            <?php $success = $this->session->flashdata('success'); ?>
                            <?php if (isset($success) != NULL) { ?>
                                <div class="alert alert-success alert-autocloseable-success">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $this->session->userdata('success'); ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="tableclass">
                            <?php echo form_open("package_category/package_category_list", array("method" => 'GET')); ?>
                            <table id="example3" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Package Category Name</th>
                                        <th>Package Category Image</th>

                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><span style="color:red;">*</span></td>
                                        <td colspan="2">
                                            <?php echo form_input(['name' => 'cfname', 'class' => 'form-control', 'placeholder' => 'Name', 'value' => $cfname]); ?>
                                        </td>

                                        <td>
                                            <input type="submit" name="search" class="btn btn-success" value="Search" />
                                        </td>
                                    </tr>
                                    <?php
                                    $cnt = $pages;
                                    foreach ($query as $row) {
                                        $cnt++;
                                        ?>
                                        <tr>
                                            <td><?php echo $cnt; ?> </td>
                                            <td><?php echo $row['name']; ?></td>
                                            <td><img src="<?= base_url(); ?>upload/package_category/<?php echo $row['pic']; ?>" height="35px" width="35px" alt="No Pic"/></td>
                                            <td>  
                                                <a  href='<?php echo base_url(); ?>package_category/package_category_edit/<?php echo $row['id']; ?>' data-original-title="edit" data-toggle="tooltip" <i style="margin-left:3px; "class="fa fa-edit"> </i> </a>  
                                                <a  href='<?php echo base_url(); ?>package_category/package_category_delete/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i style="margin-left:10px; " class="fa fa-trash-o"></i></a>  
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    if ($cnt == '0') {
                                        ?>
                                        <tr>
                                            <td colspan="3"><center>No records found</center></td>
                                    </tr>
                                <?php }
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