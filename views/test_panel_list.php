<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Panel
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>test-master/test-list"><i class="fa fa-users"></i>Panel</a></li>
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
                        echo form_open_multipart('test_panel/import_list', $attributes);
                        ?>

                        <div class="form-group">

                            <label>Upload</label>
                            <input type="file" name="id_browes" class="form-controll">
                            <div style='color:red;' id='admin_name_add_alert'></div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" id="model_close" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <input type="submit" id="add_admin_submit" name="add_menu" class="btn btn-primary" value="Upload"/>
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
					
					
                        <h3 class="box-title">Test Panel List</h3>

                        <a style="float:right;margin-right:5px;" href='<?php echo base_url(); ?>Test_panel/panel_add' class="btn btn-primary btn-sm" ><i class="fa fa-plus-circle" ></i><strong > Add</strong></a>
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
                            <div class="alert alert-success" id="profile_msg_suc" style="display: none;">
                                <span id="msg_success"></span>
                            </div>
                        </div>
                        <?php $attributes = array('class' => 'form-horizontal', 'method' => 'get', 'role' => 'form'); ?>
                        <?php echo form_open('Test_panel/test_panel_list', $attributes); ?>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="name" placeholder="Name" value="<?php
                            if (isset($name) != NULL) {
                                echo $name;
                            }
                            ?>" />
                        </div>
                        

                        <input type="submit" value="Search" class="btn btn-primary btn-md">
                        </form>
                        <br> 
                        <div class="tableclass">
                            <table id="example4" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th> No </th>
                                        <th> Plan Name </th>
                                        <th> Created By </th>
                                        <th> Created date </th>
                                        <th> Updated By </th>
                                        <th> Updated date</th>
                                        <th style="width: 10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cnt = $counts;
									if (!empty($query)) {
                                    foreach ($query as $row) {
                                        $cnt++;
                                        ?>
                                        <tr> <td><?php echo $cnt; ?></td>
                                            <td><?php echo ($row->name); ?></td>
                                            <td><?php if($row->created_by == $user->id){ echo ucwords($user->name); } ?></td>
                                            <td><?php echo date('d-m-Y H:i:s',strtotime($row->created_date)); ?></td>
                                            <td><?php if($row->updated_by == $user->id){ echo ucwords($user->name); } ?></td>
                                            <td><?php if($row->updated_date != NULL && !empty($row->updated_date)){echo date('d-m-Y H:i:s',strtotime($row->updated_date)); } ?></td>
                                            
                                            <td>
									<a href='<?php echo base_url(); ?>Test_panel/panel_test_add/<?php echo $row->id; ?>' data-toggle="tooltip" data-original-title="Add test"><i class="fa fa-plus"></i></a>
                                                <a href='<?php echo base_url(); ?>Test_panel/panel_edit/<?php echo $row->id; ?>' data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-edit"></i></a> 
                                                <a  href='<?php echo base_url(); ?>Test_panel/panel_delete/<?php echo $row->id; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i class="fa fa-trash-o"></i></a>      
                                            </td>
                                        </tr>
                                    <?php }}else{
                                        ?>
                                        <tr>
                                            <td colspan="7">No records found</td>
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
            "bFilter": false,
            "bSort": false,
            "bInfo": false,
            "bAutoWidth": false,
            "iDisplayLength": 10,
            "searching": false
        });
    });
    function department_change(tid, did) {
        var con = confirm('Are you sure you want to change department?');
        if (con == true) {
            var url = "<?php echo base_url(); ?>Panel_panel/change_department";
            $.ajax({
                type: "POST",
                url: url,
                data: {"tid": tid, "did": did}, // serializes the form's elements.
                success: function (data)
                {
                    $("#msg_success").html("");
                    $("#msg_success").html("Department Changed Successfully.");
                    $("#profile_msg_suc").show();
                    timeount();
                }
            });
        }
    }
    function timeount() {
        setTimeout(function () {
            $("#profile_msg_suc").hide();
        }, 5000);
    }
</script>
