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
            Lead List
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li> Lead List</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title"> Lead List</h3>
                        <a style="float:right;" href='<?php echo base_url(); ?>lead_manage_master/prescription_csv_report?name=<?= $sname; ?>&email=<?= $semail; ?>&mobile=<?= $smobile; ?>' class="btn btn-primary btn-sm" ><i class="fa fa-download" ></i><strong > Export To CSV</strong></a>
                        <a style="float:right;margin-right: 10px;" href='<?php echo base_url(); ?>lead_manage_master/lead_manage_add' class="btn btn-primary btn-sm" ><center><i class="fa fa-plus-circle" ></i>&nbsp <strong>Add</strong></center></a>
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
                            <?php echo form_open("lead_manage_master", array("method" => 'GET')); ?>
                            <table id="example3" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Mobile No</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><span style="color:red;">*</span></td>
                                        <td><input type="text" placeholder="Name" class="form-control" name="sname" value="<?php echo $sname ?>"/></td>
                                        <td><input type="text" placeholder="Email" class="form-control" name="semail" value="<?php echo $semail ?>"/></td>
                                        <td><input type="text" placeholder="Mobile No" class="form-control" name="smobile" value="<?php echo $smobile ?>"/></td>
                                        <td></td>
                                        <td><input type="submit" name="search" class="btn btn-success" value="Search"/></td>

                                    </tr>
                                    <?php
                                    $cnt = $pages;
                                    foreach ($records as $row) {
                                        $cnt++;
                                        ?>
                                        <tr>
                                            <td><?php echo $cnt; ?> </td>
                                            <td><?php echo ucfirst($row->name); ?></td>
                                            <td><?php echo $row->email; ?></td>
                                            <td><?php echo $row->mobile_no; ?></td>
                                            <td>
                                                <?php if ($row->mobile == $row->mobile_no) { ?>
                                                    <a  data-toggle="tooltip" data-original-title="Converted" ><span class="label label-success">Converted</span></a>
                                                <?php } else { ?>
                                                    <a  data-toggle="tooltip" data-original-title="Not Converted" ><span class="label label-danger">Not Converted</span></a>
                                                <?php } ?>

                                            </td>

                                            <td>  
                                                <a  href='<?php echo base_url(); ?>lead_manage_master/lead_manage_edit/<?php echo $row->id; ?>' data-toggle="tooltip" data-original-title="Edit" <i style="margin-left:3px;" class="fa fa-edit"></i></a>  
                                                <a  href='<?php echo base_url(); ?>lead_manage_master/lead_manage_delete/<?php echo $row->id; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i style="margin-left:10px; " class="fa fa-trash-o"></i></a>  
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
                                    <?php
                                    foreach ($links as $link) {
                                        echo "<li>" . $link . "</li>";
                                    }
                                    ?>
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