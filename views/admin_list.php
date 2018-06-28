<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="#"><i class="fa fa-users"></i>Admin User List</a></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">

        <div class="modal fade" id="import" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="H4">Import List</h4>
                    </div>
                    <div class="modal-body"> 
                        <?php $attributes = array('class' => 'form-horizontal', 'method' => 'POST'); ?>
                        <?php
                        echo form_open_multipart('test_master/importtest_csv', $attributes);
                        ?>
                        <div class="form-group">			
                            <label>Upload</label>
                            <input type="file" name="testeximport" class="form-controll">
                            <div style='color:red;' id='admin_name_add_alert'></div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" id="model_close" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <input type="submit" id="add_admin_submit" name="add_menu" class="btn btn-primary" value="Upload"/>
                            <!--                            <button type="button" id="add_admin_submit"  data-dismiss="modal"  onclick="sub('admin_add');" name="add_menu" class="btn btn-primary" disabled=''> Add </button>-->
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Admin User List</h3>

                        <a style="float:right;" href='<?php echo base_url(); ?>Admin_manage/user_add' class="btn btn-primary btn-sm" ><i class="fa fa-plus-circle" ></i><strong > Add</strong></a>
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
                            <table id="example3" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Type</th>
										<th>City</th>
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
                                            <td><?php echo ucwords($row['name']); ?></td>
                                            <td><?php echo $row['email']; ?></td>
                                            <td><?php echo $row['phone']; ?></td>
                                            <td><?php
                                                if ($row['type'] == '1') {
                                                    echo "Pro+";
                                                } if ($row['type'] == '2') {
                                                    echo "Pro";
                                                } if ($row['type'] == '3') {
                                                    echo "Source Lab";
                                                } if ($row['type'] == '4') {
                                                    echo "Destination Lab";
                                                } if ($row['type'] == '5') {
                                                    echo "Super admin";
                                                } if ($row['type'] == '6') {
                                                    echo "Admin";
                                                } if ($row['type'] == '7') {
                                                    echo "User";
                                                }
                                                ?></td>
												<td><?=$row['cityname']; ?></td>
                                            <td>
                                                <a href='<?php echo base_url(); ?>Admin_manage/user_edit/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-edit"></i></a> 
                                                <a  href='<?php echo base_url(); ?>Admin_manage/user_delete/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i class="fa fa-trash-o"></i></a>      
                                                <?php if ($row['type'] == '5' || $row['type'] == '6' || $row['type'] == '7') { ?> <a  href='<?php echo base_url(); ?>Branch_Master/user_branch/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Assign Branch" >Assign Branch</a>      
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php
                                        $cnt++;
                                    }if (empty($query)) {
                                        ?>
                                        <tr>
                                            <td colspan="6">No records found</td>
                                        </tr>
                                    <?php } ?>
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
