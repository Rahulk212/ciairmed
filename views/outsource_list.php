<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Outsource
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li class="active">Outsource List</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
<!--        <div class="modal fade" id="import" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="H4">Import List</h4>
                    </div>
                    <div class="modal-body"> 
                        <?php $attributes = array('class' => 'form-horizontal', 'method' => 'POST'); ?>
                        <?php
                        echo form_open_multipart('doctor_master/import_list', $attributes);
                        ?>

                        <div class="form-group">

                            <label>Upload</label>
                            <input type="file" name="id_browes" class="form-controll">
                            <div style='color:red;' id='admin_name_add_alert'></div>

                        </div>
                          <div class="form-group">
                                <label for="exampleInputFile">State</label>
                                <select class="form-control" name="state" id="state">
                                    <option value="">Select State</option>
                                    <?php foreach ($state as $cat) { ?>

                                        <option value="<?php echo $cat['id']; ?>" <?php
                                        if ($cat['id'] == $query[0]["state"]) {
                                            echo "selected";
                                        }
                                        ?>><?php echo ucwords($cat['state_name']); ?></option>
                                            <?php } ?>
                                </select>

                            </div>

                            <div class="form-group">
                                <label for="exampleInputFile">City</label>
                                <select class="form-control" name="city" id="city">
                                    <option value="">Select City</option>
                                    <?php foreach ($city as $cat) { ?>

                                        <option value="<?php echo $cat['id']; ?>" <?php
                                        if ($cat['id'] == $query[0]["city"]) {
                                            echo "selected";
                                        }
                                        ?>><?php echo ucwords($cat['city_name']); ?></option>
                                            <?php } ?>
                                </select>

                            </div>

                        <div class="modal-footer">
                            <button type="button" id="model_close" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <input type="submit" id="add_admin_submit" name="add_menu" class="btn btn-primary" value="Upload"/>
                                                        <button type="button" id="add_admin_submit"  data-dismiss="modal"  onclick="sub('admin_add');" name="add_menu" class="btn btn-primary" disabled=''> Add </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>-->
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Outsource List</h3>

                        <a style="float:right;" href='<?php echo base_url(); ?>outsource_master/outsource_add' class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i><strong> Add</strong></a>
<!--                        <a style="float:right;margin-right:5px;" data-toggle="modal"  data-target="#import"  class="btn btn-primary btn-sm" ><strong > Import</strong></a>-->

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
                            <form role="form" action="<?php echo base_url(); ?>outsource_master/outsource_list" method="get" enctype="multipart/form-data">
                                <table id="example4" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th width="30%">Address</th>
                                            <th>State</th>
                                            <th>City</th>
                                            <th>Branch</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><span style="color:red;">*</span></td>
                                            <td><input type="text" placeholder="Name" class="form-control" name="name" value="<?php echo $name; ?>"/></td>
                                            <td><input type="text" placeholder="Email" class="form-control" name="email_id" value="<?php echo $email_id; ?>"/></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td><input type="submit" name="search" class="btn btn-success" value="Search" /></td>
                                        </tr>
                                        <?php
                                        $cnt = 1;
                                        foreach ($query as $row) {
                                            ?>

                                            <tr>
                                                <td><?php echo $cnt+$page; ?></td>
                                                <td><?php echo ucwords($row['name']); ?></td>
                                                <td><?php echo ucwords($row['email']); ?></td>
                                                <td><?php echo ucwords($row['address']); ?></td>
                                                <td><?php echo ucwords($row['state']); ?></td>
                                                <td><?php echo ucwords($row['city']); ?></td>
                                                <td><?php echo ucwords($row['branch_name']); ?></td>
                                                <td>
                                                    <a href='<?php echo base_url(); ?>outsource_master/outsource_edit/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-edit"></i></a> 
                                                    <a  href='<?php echo base_url(); ?>outsource_master/outsource_delete/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i class="fa fa-trash-o"></i></a>
                                                    <?php // if ($row['status'] == "1") { ?>
<!--                                                        <a  href='<?php echo base_url(); ?>doctor_master/doctor_deactive/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Deactive" ><span class="label label-success">Active</span></a>   -->
                                                    <?php // } else { ?>
<!--                                                        <a  href='<?php echo base_url(); ?>doctor_master/doctor_active/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="active" ><span class="label label-danger">Deactive</span> </a>   -->
                                                    <?php // } ?>      
                                                </td>
                                            </tr>
                                            <?php $cnt++;
                                        }if (empty($query)) {
                                            ?>
                                            <tr>
                                                <td colspan="7">No records found</td>
                                            </tr>
<?php } ?>

                                    </tbody>
                                </table>
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
            "bAutoWidth": false,
            "iDisplayLength": 10
        });
    });
</script>