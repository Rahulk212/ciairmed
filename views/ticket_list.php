<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<style>
.round {
    display: inline-block;
    height: 30px;
    width: 30px;
    line-height: 30px;
    -moz-border-radius: 15px;
    border-radius: 15px;
    background-color: #222;    
    color: #FFF;
    text-align: center;  
}
.round.round-sm {
    height: 10px;
    width: 10px;
    line-height: 10px;
    -moz-border-radius: 10px;
    border-radius: 10px;
    font-size: 0.7em;
}
.round.blue {
    background-color: #3EA6CE;
}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
Ticket List
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li>Ticket List</li>

        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
					<form role="form" action="<?php echo base_url(); ?>issue_master/index" method="get" enctype="multipart/form-data">
                       <div class="col-xs-12">
					<div class="col-xs-3">
					
					
					</div>
					
					
					</div>
					</form>

						 
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
						<form role="form" action="<?php echo base_url(); ?>support_system/index" method="get" enctype="multipart/form-data">
                            <table id="example3" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
										<th>Ticket</th>
										<th>Subject</th>
										<th>status</th>
										<th>User Name</th>
										<th>Action</th>
										
                                    </tr>
                                </thead>
                                <tbody>
									<tr>
										<td><span style="color:red;">*</span></td>
										<td><input type="text" placeholder="Ticket" class="form-control" name="ticket" value="<?php echo $ticket; ?>"/></td>
										<td><input type="text" name="subject" placeholder="Subject" class="form-control" value="<?php echo $subject; ?>" /></td>
										<td>
											<select class="form-control" name="status">
												<option value="" >SELECT STATUS</option>
												<option value="1" <?php if($status == '1'){ echo "selected"; }?> >Open</option>
												<option value="0" <?php if($status == '0'){ echo "selected"; }?> >Close</option>
											</select>
										</td>
										<td>
											<select class="form-control chosen-select" tabindex="-1"  data-placeholder="Select Customer" name="user">
												<option value="">Select Customer</option>
												<?php foreach($customer as $cat){ ?>
													<option value="<?php echo $cat['id']; ?>" <?php if($user == $cat['id']) { echo "selected"; }?> ><?php echo ucwords($cat['full_name']); ?></option>
												<?php } ?>
										  </select>
										</td>
										<td><input type="submit" name="search" class="btn btn-success" value="Search" /></td>
									</tr>
                                    <?php
                                    $cnt = 0;
                                    foreach ($query as $row) { $cnt++;
                                        ?>

                                        <tr>
                                            <td><?php echo $cnt; if($row['views']=='0'){ echo '<span class="round round-sm blue"> </span>'; } ?> </td>
											 <td><?php echo ucwords($row['ticket']); ?></td>
											  <td><?php echo ucwords($row['title']); ?></td>
											  <td><?php if($row['status']==0){?> <span class="label label-danger">Close</span> <?php }else{ ?> <span class="label label-success">Open</span> <?php } ?></td>
                                                                                          <td><a href="<?php echo base_url(); ?>customer-master/customer-all-details/<?php echo $row['cust_id']; ?>"><?= ucwords($row['full_name']); ?></a></td>
											  <td><a href='<?php echo base_url(); ?>support_system/view_details/<?php echo $row['ticket']; ?>' data-toggle="tooltip" data-original-title="view"><i class="fa fa-eye"></i></a></td>
                                        </tr>
                                        <?php 
                                    }if($cnt == '0'){ ?>
										<tr>
											<td colspan="6">No records found</td>
										</tr>
									<?php } ?>
                                </tbody>
                            </table>
							</form>
                        </div>
						<div style="text-align:right;" class="box-tools">
							<ul class="pagination pagination-sm no-margin pull-right">
								<?php echo $links;?>
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