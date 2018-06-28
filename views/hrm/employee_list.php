<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Employee<small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><i class="fa fa-users"></i> Employee</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-primary" style="border-color:#bf2d37;">
                    <div class="panel-heading" style="background-color:#bf2d37;">
                        <h3 class="panel-title">Employee List</h3>

                    </div><!-- /.box-header -->
                    <div class="panel-body">
                        <a style="float:right;" href='<?php echo base_url(); ?>hrm/employee/add' class="btn btn-primary btn-sm" ><i class="fa fa-plus-circle" ></i> <strong> Add</strong></a>
                        <?php $attributes = array('class' => 'form-horizontal', 'method' => 'get', 'role' => 'form'); ?>
                        <?php echo form_open('hrm/employee/index', $attributes); ?>
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
                                        <th>Employee-ID</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Dept/Designation</th>
                                        <th>At Work</th>
                                        <th>Phone</th>
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
                                        <tr>
                                            <td><?php echo $row->employee_id; ?></td>
                                            <td><?php if ($row->photo != '') { ?><img src="<?php echo base_url(); ?>upload/employee/<?php echo $row->photo; ?>" alt="ProfileImage" height="80px"> <?php } else { ?><img src="<?php echo base_url(); ?>upload/employee/default_iamge.png" height="80px"><?php } ?></td>
                                            <td><?php echo ucwords($row->name); ?></td>
                                            <td><span>Department: <b><?php echo ucwords($row->department); ?></b></span><br><span>Designation: <b><?php echo ucwords($row->designation); ?></b></span></td>
                                            <td><?php 
                                                $start = $row->date_of_joining;
                                                if($start != "") {
                                                $expirationDate = strtotime($start);
                                                $toDay = strtotime(date('d-m-Y'));
                                                $difference = abs($toDay - $expirationDate);


                                                $years = floor($difference / 31207680);
                                                $months = floor(($difference - $years * 31207680) / 2600640);
                                                $days = floor(($difference - $years * 31207680 - $months * 2600640) / 86400);
                                                echo $years . " year " . $months . " month " . $days . " day"; } else { echo "-"; };
                                                ?></td>
                                            <td><?php echo $row->phone; ?></td>
                                            <td><?php if ($row->active == 1) { ?><span class="label label-success">Active</span><?php } else { ?><span class="label label-danger ">Deactive</span><?php } ?></td>
                                            <td><a href='<?php echo base_url(); ?>hrm/employee/edit/<?php echo $row->id; ?>' data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-edit"></i></a> 
                                                <a href='<?php echo base_url(); ?>hrm/employee/delete/<?php echo $row->id; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i class="fa fa-trash-o"></i></a>      
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