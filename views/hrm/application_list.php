<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Leave Application<small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><i class="fa fa-rocket"></i> Application</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-primary" style="border-color:#bf2d37;">
                    <div class="panel-heading" style="background-color:#bf2d37;">
                        <h3 class="panel-title">Application List</h3>

                    </div><!-- /.box-header -->
                    <div class="panel-body">
                        <?php $attributes = array('class' => 'form-horizontal', 'method' => 'get', 'role' => 'form'); ?>
                        <?php echo form_open('hrm/leave_application/index', $attributes); ?>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="search" placeholder="search" value="<?php
                            if (isset($search) != NULL) {
                                echo $search;
                            }
                            ?>" />
                        </div>
                        <input type="submit" value="Search" class="btn btn-primary btn-md">
                        </form><br>
                        <div class="widget">
                            <?php if ($this->session->flashdata('unsuccess')) { ?>
                                <div class="alert alert-danger alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $this->session->flashdata('unsuccess'); ?>
                                </div>
                            <?php } ?>
                            <?php if ($this->session->flashdata('success')) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $this->session->flashdata('success'); ?>
                                </div>
                            <?php } ?>
                        </div>
                        <br> 
                        <div class="tableclass">
                            <table id="example4" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Date</th>
                                        <th>Leave Type</th>
                                        <th>Reason</th>
                                        <th>Applied on</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cnt = $counts;
                                    foreach ($query as $row) {
                                        $cnt++;
                                        ?>
                                        <tr> <td><?php echo $cnt; ?></td>
                                            <td><?php echo ucwords($row->employee_name); ?></td>
                                            <td><?php echo date("d-M-Y", strtotime($row->date)); ?></td>
                                            <td><?php echo ucwords($row->leave_type); ?></td>
                                            <td><?php echo ucwords($row->reason); ?></td>
                                            <td><?php echo date("d-M-Y", strtotime($row->created_date)); ?></td>
                                    <td><?php if ($row->leave_status == 1) { ?><span class="label label-success">Approved</span><?php } else if ($row->leave_status == 0) { ?><span class="label label-warning ">Pending</span><?php } else if($row->leave_status == 2) { ?><span class="label label-danger ">Rejected</span><?php } ?></td>
                                            <td>
                                                <a href='<?php echo base_url(); ?>hrm/leave_application/edit/<?php echo $row->id; ?>' data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-edit"></i></a> 
                                                <a href='<?php echo base_url(); ?>hrm/leave_application/delete/<?php echo $row->id; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i class="fa fa-trash-o"></i></a>      
                                            </td>
                                        </tr>
                                    <?php }if (empty($query)) {
                                        ?>
                                        <tr>
                                            <td colspan="8">No records found</td>
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
<script>
    $(function () {
        setTimeout(function () {
            $(".alert").hide('blind', {}, 500)
        }, 5000);
    });
</script>