<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Customer
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>customer-master/customer-list"><i class="fa fa-users"></i>Customer</a></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Customer List</h3>
                                                 <!-- <a style="float:right;" href='<?php echo base_url(); ?>customer_master/export_csv' class="btn btn-primary btn-sm" ><i class="fa fa-download" ></i><strong > Export To CSV</strong></a>-->
                        <a style="float:right;margin-right: 10px;" href='<?php echo base_url(); ?>customer-master/customer-add' class="btn btn-primary btn-sm" ><i class="fa fa-plus-circle" ></i><strong > Add</strong></a>
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
                            <form role="form" action="<?php echo base_url(); ?>customer-master/customer-list" method="get" enctype="multipart/form-data">
                                <table id="example3" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Mobile</th>
                                            <th>Portal</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><span style="color:red;">*</span></td>
                                            <td><input type="text" placeholder="Name" class="form-control" name="name" value="<?php echo $name; ?>"/></td>
                                            <td><input type="text" name="email" placeholder="email" class="form-control" value="<?php echo $email; ?>" /></td>
                                            <td><input type="text" name="mobile" placeholder="Mobile" class="form-control" value="<?php echo $mobile; ?>" /></td>
                                            <td></td>
                                            <td><input type="text" name="date" id="date" placeholder="Date" class="form-control" value="<?php echo $date; ?>" /></td>
                                            <td><input type="submit" name="search" class="btn btn-success" value="Search" /></td>
                                        </tr>
                                        <?php
                                        $cnt = 0;
                                        foreach ($query as $row) {
                                            $cnt++;
                                            ?>

                                            <tr>
                                                <td><?php echo $cnt + $page; ?></td>
                                                <td><?php echo ucwords($row['full_name']); ?> </td>
                                                <td><?php echo $row['email']; ?></td>
                                                <td><?php echo ucwords($row['mobile']); ?></td>
                                                <td><?php if($row['device_type']==''){ echo "Web";  }if($row['device_type']=='ios'){ echo "Ios";  }if($row['device_type']=='android'){ echo "Android";  } ?></td>
                                                <td><?php echo $row['created_date']; ?></td>
                                                <td>

                                                    <a href='<?php echo base_url(); ?>customer-master/customer-edit/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-edit"></i></a> 

                                                    <a  href='<?php echo base_url(); ?>customer_master/customer_delete/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i class="fa fa-trash-o"></i></a>   
                                                    <a href='<?php echo base_url(); ?>customer-master/customer-all-details/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="View"><i class="fa fa-eye"></i></a> 
                                                    <?php if ($row['active'] == "1") { ?>
                                                        <a  href='<?php echo base_url(); ?>customer_master/customer_deactive/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Deactive" ><span class="label label-success">Active</span></a>   
                                                    <?php } else { ?>
                                                        <a  href='<?php echo base_url(); ?>customer_master/customer_active/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="active" ><span class="label label-danger">Deactive</span> </a>   
                                                    <?php } ?>

                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        if ($cnt == '0') {
                                            ?>
                                            <tr>
                                                <td colspan="5">No records found</td>
                                            </tr>
                                        <?php }
                                        ?>

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
