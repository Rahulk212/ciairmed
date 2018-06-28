<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Just Dial 
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>phlebo_master/phlebo_list"> Just Dial</a></li>

        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Just Dial List</h3>

<!--                        <a style="float:right;" href='<?php echo base_url(); ?>phlebo_master/phlebo_add' class="btn btn-primary btn-sm" ><i class="fa fa-plus-circle" ></i><strong > Add</strong></a>-->
                        <?php if ($_SERVER['QUERY_STRING'] != '') { ?>
                            <a style="float:right;margin-left:3px" href='<?php echo base_url(); ?>Just_dial_master/export_csv?<?= $_SERVER['QUERY_STRING'] ?>' class="btn btn-sm btn-primary" ><i class="fa fa-download" ></i><strong > Export To CSV</strong></a>
                        <?php } ?>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="widget">
                            <?php if (isset($success) != NULL) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">X</button>
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
                            <form role="form" action="<?php echo base_url(); ?>Just_dial_master/index" method="get" enctype="multipart/form-data">
                                <table id="example4" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Gender</th>
                                            <th>Created Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><span style="color:red;">*</span></td>
                                            <td><input type="text" placeholder="Name" class="form-control" name="name" value="<?php echo $name; ?>"/></td>
                                            <td><input type="text" placeholder="Email" class="form-control" name="email" value="<?php echo $email; ?>"/></td>
                                            <td><input type="text" placeholder="Mobile" class="form-control" name="mobile" value="<?php echo $mobile; ?>"/></td>
                                            <?php
                                            $gender_list = array();
                                            $gender_list[] = array("name" => "Male", "id" => "male");
                                            $gender_list[] = array("name" => "Female", "id" => "female");
                                            ?>
                                            <td><select name="gender" class="form-control"><option value="">--All--</option><?php
                                                    foreach ($gender_list as $tkey) {
                                                        echo '<option value="' . $tkey["id"] . '" ';
                                                        if ($gender == $tkey["id"]) {
                                                            echo " selected";
                                                        } echo '>' . ucfirst($tkey["name"]) . '</option>';
                                                    }
                                                    ?></select></td>
                                            
                                            <td></td>
                                            <td>
                                                <input type="submit" name="search" class="btn btn-sm btn-success" value="Search" />
                                                <input type="button" name="Reset" class="btn btn-sm btn-primary" onclick="window.location.href = '<?= base_url(); ?>Just_dial_master/index'" value="Reset" />
                                            </td>
                                        </tr>
                                        <?php
                                        $cnt = 1;
                                        foreach ($query as $row) {
                                            ?>

                                            <tr>
                                                <td><?php echo $cnt+$page; ?></td>
                                                <td><?php echo ucwords($row['name']); ?></td>
                                                <td><?php echo ucwords($row['email']); ?></td>
                                                <td><?php echo ucwords($row['phone']); ?></td>
                                                <td><?php echo ucwords($row['gender']); ?></td>
                                                <td><?php echo ucwords($row['search_date_time']); ?></td>
                                                <td>

                                                    <a href='<?php echo base_url(); ?>Just_dial_master/details/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="View Details"><i class="fa fa-eye"></i></a>
                                                    <a  href='<?php echo base_url(); ?>Just_dial_master/delete/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i class="fa fa-trash-o"></i></a>
                                                    <?php /* if($row['status']=="1"){ ?>
                                                      <a  href='<?php echo base_url(); ?>phlebo_master/phlebo_deactive/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Deactive" ><span class="label label-success">Active</span></a>
                                                      <?php }else {?>
                                                      <a  href='<?php echo base_url(); ?>phlebo_master/phlebo_active/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="active" ><span class="label label-danger">Deactive</span> </a>
                                                      <?php } */ ?>      
                                                </td>
                                            </tr>
                                            <?php
                                            $cnt++;
                                        }if (empty($query)) {
                                            ?>
                                            <tr>
                                                <td colspan="5">No records found</td>
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