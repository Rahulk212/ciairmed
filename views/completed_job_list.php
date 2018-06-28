<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
          Completed Job
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>customer-master/customer-list"><i class="fa fa-users"></i>Completed Job</a></li>

        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                      <form role="form" action="<?php echo base_url(); ?>job-master/completed-list" method="get" enctype="multipart/form-data">
                       <div class="col-xs-12">
					<div class="col-xs-4">
					
					<div class="form-group">
                   						<select class="form-control chosen-select" tabindex="-1"  data-placeholder="Select Customer" name="user">
                              <option value="">Select Customer</option>
								  <?php foreach($customer as $cat){ ?>
                              <option value="<?php echo $cat['id']; ?>" <?php if($customerfk == $cat['id']) { echo "selected"; }?> ><?php echo ucwords($cat['full_name']); ?></option>
						<?php } ?>
                              </select>
                    </div>
					</div>
					<div class="col-xs-4">
					
					<div class="form-group">
                   						<select class="form-control chosen-select" tabindex="-1"  data-placeholder="Select City" name="city">
                              <option value="">Select city</option>
								  <?php foreach($city as $cat){ ?>
                              <option value="<?php echo $cat['id']; ?>" <?php if($cityfk == $cat['id']) { echo "selected"; }?> ><?php echo ucwords($cat['city_name']); ?></option>
						<?php } ?>
                              </select>
                    </div>
					</div>
					<div class="col-xs-2">
					<div class="form-group">
					<input type="text" name="date" class="form-control" id="date" value="<?php if(isset($date)) { echo $date; }?>" />
                    </div>
					</div>
					<div class="col-xs-2">
					<div class="form-group">
					<input type="submit" name="search" class="btn btn-success" value="Search" />
                    </div>
					</div>
					</div>
					</form>

						  <a style="float:right;" href='<?php echo base_url(); ?>job_master/export_csv/2' class="btn btn-primary btn-sm" ><i class="fa fa-download" ></i><strong > Export To CSV</strong></a>

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
                            <table id="example2" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Customer Name</th>
										<th>Tests Name</th>
										<th>Packages Name</th>
										<th>Date</th>
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
											 <td><?php echo ucwords($row['full_name']); ?> </td>
											  <td><?php echo ucwords($row['testname']); ?></td>
											<td><?php if($row['packagename'] != NULL) { echo ucwords($row['testname']); } else { echo "-"; } ?></td>
                                            <td><?php echo ucwords($row['date']); ?></td>
                                            <td>
											
											
<a  href='<?php echo base_url(); ?>job_master/job_mark_spam/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Mark as Spam" ><span class="label label-danger">Mark as Spam</span></a>   
<a  href='<?php echo base_url(); ?>job_master/job_mark_pending/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Mark as Pending" ><span class="label label-success">Mark as Pending</span> </a>   
<a  href='<?php echo base_url(); ?>job-master/job-details/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="View Job Details" > <span class="label label-primary"><i class="fa fa-eye"> </i> View Details</span> </a>   


											</td>
                                        </tr>
                                        <?php $cnt++;
                                    } ?>

                                </tbody>
                            </table>
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
