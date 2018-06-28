<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Package List
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>home"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li class="active">Package List</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Package List</h3>
                        <a style="float:right;margin-right: 5px;" href='<?php echo base_url(); ?>package_master' class="btn btn-primary btn-sm" ><i class="fa fa-plus-circle" ></i><strong > Add</strong></a>
                        <a style="float:right;margin-right: 5px;" href='<?php echo base_url(); ?>package_master/export_csv' class="btn btn-primary btn-sm"><i class="fa fa-plus-download" ></i><strong > Export CSV</strong></a>
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
                        <br>
                        <div class="tableclass">
                            <table id="example4" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Image</th>
                                        <th>Back Image</th>  
                                        <th>package Name</th>
                                        <th>Actual Price</th>
                                        <th>Discount Price</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cnt = 1;
                                    foreach ($query as $row) {
                                        ?>
                                        <tr>
                                            <td><?php echo $cnt; ?></td>
                                            <td><img src="<?php echo base_url(); ?>upload/package/<?php echo $row['image']; ?>" alt="Profile Pic" style="width:50px; height:40px;"/>
                                            </td>
                                            <td><img src="<?php echo base_url(); ?>upload/package/<?php echo $row['back_image']; ?>" alt="Profile Pic" style="width:50px; height:40px;"/>
                                            </td>
                                            <td><?php echo $row['title']; ?></td>
                                            <td><?php
                                                foreach ($row["price1"] as $key) {
                                                    echo "Rs." . $key["a_price"] . " (" . $key["name"] . ")<br>";
                                                }
                                                ?></td>
                                            <td><?php
                                                foreach ($row["price1"] as $key) {
                                                    echo "Rs." . $key["d_price"] . " (" . $key["name"] . ")<br>";
                                                }
                                                ?></td>
                                            <td>
                                                <a href='<?php echo base_url(); ?>package_master/package_edit/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-edit"></i></a>
                                                <a href='<?php echo base_url(); ?>package_master/package_test_add/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Add Test"><i class="fa fa-plus"></i></a>
                                                <a  href='<?php echo base_url(); ?>package_master/package_delete/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i class="fa fa-trash-o"></i></a>        
                                            </td>
                                        </tr>
    <?php
    $cnt++;
}
?>
                                </tbody>
                            </table>
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
