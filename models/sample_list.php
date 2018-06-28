<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Sample
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>Logistic/sample_list"><i class="fa fa-users"></i>Sample List</a></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Sample List</h3>
<!--                        <a style="float:right;margin-right:5px;" href='<?php echo base_url(); ?>test-master/test-add' class="btn btn-primary btn-sm" ><i class="fa fa-plus-circle" ></i><strong > Add</strong></a>
                        <a style="float:right;margin-right:5px;" href='<?php echo base_url(); ?>test_master/test_csv?city=<?= $city ?>' class="btn btn-primary btn-sm" ><strong > Export</strong></a>
                        <a style="float:right;margin-right:5px;" data-toggle="modal"  data-target="#import"  class="btn btn-primary btn-sm" ><strong > Import</strong></a>-->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <?php echo form_open("Logistic/sample_list/", array("method" => "GET")); ?>
                        <div class="tableclass">
                            <table id="example4" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Logisttic Name</th>
                                        <th>Barcode</th>
                                        <th>Scan Date</th>
                                        <th>Collect From</th>
                                        <th>Status</th>
                                        <th style="width: 10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td><input type="text" class="form-control" name="name" placeholder="Name" value="<?= $name ?>"/></td>
                                        <td><input type="text" class="form-control" name="barcode" placeholder="Barcode" value="<?= $barcode ?>"/></td>
                                        <td><input type="text" class="form-control" name="date" placeholder="Date" value="<?= $date ?>"/></td>
                                        <td><input type="text" class="form-control" name="from" placeholder="From" value="<?= $from ?>"/></td>
                                        <td><input type="text" class="form-control" name="status" placeholder="Status" value="<?= $status ?>"/></td>
                                        <td style="widtd: 10%"><input type="submit" value="Search" class="btn btn-primary btn-md"></td>
                                    </tr>
                                    <?php
                                    $cnt = 0;
                                    foreach ($query as $row) {
                                        $cnt++;
                                        ?>
                                        <tr> <td><?php echo $cnt; ?></td>
                                            <td><?php echo ucwords($row['name']); ?></td>
                                            <td><?php echo ucwords($row['barcode']); ?></td>
                                            <td><?php echo ucwords($row['scan_date']); ?></td>
                                            <td><?php echo ucwords($row['c_name']); ?></td>
                                            <td><?php if($row['status']==1){ echo '<span class="label label-warning">Pending</span>'; } if($row['status']==2){ echo '<span class="label label-success">Completed</span>'; } ?></td>
                                            <td>
                                                <a  href='<?php echo base_url(); ?>Logistic/details/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="details"><i class="fa fa-eye"></i></a>
                                                <a  href='<?php echo base_url(); ?>Logistic/sample_delete/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i class="fa fa-trash-o"></i></a>      
                                            </td>
                                        </tr>
                                    <?php }if (empty($query)) {
                                        ?>
                                        <tr>
                                            <td colspan="4">No records found</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <?= form_close(); ?>
                        </div>
                        <div style="text-align:right;" class="box-tools">
                            <ul class="pagination pagination-sm no-margin pull-right">
                                <?php echo $links; ?>
                            </ul>
                        </div>
                    </div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<script type="text/javascript">
    $(function () {
        $("#example1").dataTable();
        $('#example3').dataTable({
            "bPaginate": false,
            "bLengthChange": false,
            "bFilter": false,
            "bSort": false,
            "bInfo": false,
            "bAutoWidth": false,
            "iDisplayLength": 10,
            "searching": false
        });
    });
</script>
