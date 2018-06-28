
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Relation
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>relation_master/relation_list"><i class="fa fa-users"></i>Relation List</a></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">

        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Relation List</h3>

                        <a style="float:right;" href='<?php echo base_url(); ?>relation_master/relation_add' class="btn btn-primary btn-sm" ><i class="fa fa-plus-circle" ></i><strong > Add</strong></a>
                        <!--<a style="float:right;" href='<?php echo base_url(); ?>
</div><!-- /.box-header -->
                        <div class="box-body">
                            <div class="widget">
                                <?php if (isset($success) != NULL) { ?>
                                    <div class="alert alert-success alert-dissmissable">
                                        <?php echo $success[0]; ?>
                                    </div>
                                <?php } ?>

                            </div>


                            <div class="tableclass">
                                <?php echo form_open('Relation_master/relation_list', array('method' => 'GET')); ?>
                                <table id="example3" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Relation</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php /*<tr>
                                            <td><span style="color:red;">#</span></td>
                                            <td>
                                                <select class="form-control chosen-select" tabindex="-1" data-placeholder="Select Relation" name="relation">
                                                    <option value="">Select Relation</option>
                                                    <?php foreach ($query as $row) { ?>
                                                        <option value="<?php echo $row['id']; ?>" <?php
                                                        ?> ><?php echo $row['name']; ?></option>
                                                            <?php } ?>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="submit" name="search" class="btn btn-success" value="Search">
                                            </td>
                                        </tr> */?>
                                        <?php
                                        $cnt = 1;

                                        foreach ($query as $row) {
                                            ?>

                                            <tr>
                                                <td><?php echo $cnt+$page; ?></td>
                                                <td><?php echo ucwords($row['name']); ?></td>

                                        <style>
                                            .fa
                                            {
                                                margin-right:10px;
                                            }
                                        </style>

                                        <td>
                                            <a href='<?php echo base_url(); ?>relation_master/relation_edit/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-edit"></i></a> 
                                            <a  href='<?php echo base_url(); ?>relation_master/relation_delete/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i class="fa fa-trash-o"></i></a>      
                                        </td>
                                        </tr>
                                        <?php
                                        $cnt++;
                                    }
                                    ?>
                                    </tbody>

                                </table>
                                <?php echo form_close(); ?>
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
    /*  $(function () {
     $("#example1").dataTable();
     $('#example3').dataTable({
     "bPaginate": false,
     "bLengthChange": false,
     "bFilter": true,
     "bSort": false,
     "bInfo": false,
     "bAutoWidth": false,
     "iDisplayLength": 10
     });
     });  */
</script>

<script type="text/javascript">
    window.setTimeout(function () {
        $(".alert").fadeTo(500, 0).slideUp(500, function () {
            $(this).remove();
        });
    }, 4000);
</script>