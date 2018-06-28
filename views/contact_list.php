<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Package Inquiry 
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><i class="fa fa-users"></i> Package Inquiry</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title"> Package Inquiry List</h3>
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
                            <?php echo form_open("customer_master/Package_inquiry_list", array("method" => "GET", "role" => "form")); ?>
                            <table id="example4" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Mobile</th>
                                        <th>Customer Name</th>
                                        <th>Inquiry Package</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><span style="color:red;">*</span></td>
                                        <td>
                                            <input type="text" name="mobile" class="form-control" id="date"  value="<?php
                                            if (isset($mobile)) {
                                                echo $mobile;
                                            }
                                            ?>" />
                                        </td>
                                        <td></td>
                                        <td>
                                            <select class="form-control chosen-select" tabindex="-1" data-placeholder="Select Package" name="package">
                                                <option value="">Select Package</option>
                                                <?php foreach ($package_list as $cat) { ?>
                                                    <option value="<?php echo $cat['id']; ?>" <?php
                                                    if ($package == $cat['id']) {
                                                        echo "selected";
                                                    }
                                                    ?> ><?php echo ucwords($cat['title']); ?></option>
                                                        <?php } ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="date" placeholder="select date" class="form-control datepicker-input" id="date"  value="<?php
                                            if (isset($date)) {
                                                echo $date;
                                            }
                                            ?>" />
                                        </td>
                                        <td>
                                            <select class="form-control chosen-select" data-placeholder="Select Status" tabindex="-1" name="status">
                                                <option value="">Select Status </option>
                                                <option value="1" <?php
                                                if ($statusid == 1) {
                                                    echo "selected";
                                                }
                                                ?>> Pending </option>
                                                <option value="2" <?php
                                                if ($statusid == 2) {
                                                    echo "selected";
                                                }
                                                ?>> Completed </option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="submit" name="search" class="btn btn-success" value="Search" />
                                        </td>
                                    </tr>
                                    <?php
                                    $cnt = 1;
                                    foreach ($query as $row) {
                                        
                                        ?>
                                    
                                        <tr>
                                            <td><?php echo $cnt+$page; ?></td>
                                            <td><?php echo ucwords($row['mobile']); ?></td>
                                            <td><a href="<?=base_url();?>customer-master/customer-all-details/<?=$row["cid"]?>"><?php echo ucwords($row['c_name']); ?></a></td>
                                            <td><?php echo ucwords($row['title']); ?></td>
                                            <td><?php echo $row['date']; ?></td>
                                            <td>
                                                <?php if ($row['status'] == "2") { ?>
                                                    <span class="label label-success">Completed</span>   
                                                <?php } else { ?>
                                                    <span class="label label-danger">Pending</span>  
                                                <?php } ?>										
                                            </td>
                                            <td>

                                                <a  href='<?php echo base_url(); ?>customer_master/contact_delete/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i class="fa fa-trash-o"></i></a>
                                                <?php if ($row['status'] == "2") { ?>
                                                    <a  href='<?php echo base_url(); ?>customer_master/contact_pending/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Mark as Pending" onclick="return confirm('Are you sure ?');" ><i class="fa fa-ban"></i></a>   
                                                <?php } else { ?>
                                                    <a  href='<?php echo base_url(); ?>customer_master/contact_complete/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Mark as Completed" onclick="return confirm('Are you sure?');"><i class="fa fa-check"></i></a>   
                                                    <?php } ?>
                                            </td>
                                        </tr>
                                        <?php $cnt++;
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
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
<script  type="text/javascript">

    jQuery(".chosen-select").chosen({
        search_contains: true
    });
    //  $(".chosen-select-deselect").chosen({ allow_single_deselect: true });
    // $("#cid").chosen('refresh');

</script> 