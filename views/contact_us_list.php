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
            Contact Us
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><i class="fa fa-user"></i>Contact Us</li>

        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Contact Us List</h3>

						  <!--<a style="float:right;" href='<?php echo base_url(); ?>customer_master/export_csv' class="btn btn-primary btn-sm" ><i class="fa fa-download" ></i><strong > Export To CSV</strong></a>-->
                        
						

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
						<form role="form" action="<?php echo base_url(); ?>contact_us_master" method="get" enctype="multipart/form-data">
                            <table id="example4" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
										<th>Email</th>
										<th>Mobile</th>
										<th>Subject</th>
										<th>Message</th>
										<th>Action</th>
                                       
                                    </tr>
                                </thead>
                                <tbody>
									<tr>
										<td><span style="color:red;">*</span></td>
										<td><input type="text" placeholder="Name" class="form-control" name="name" value="<?php echo $name; ?>"/></td>
										<td><input type="text" name="email" placeholder="email" class="form-control" value="<?php echo $email; ?>" /></td>
										<td><input type="text" name="mobile" placeholder="Mobile" class="form-control" value="<?php echo $mobile; ?>" /></td>
										<td><input type="text" name="subject" placeholder="Subject" class="form-control" value="<?php echo $subject; ?>" /></td>
										<td><input type="text" name="message" placeholder="Message" class="form-control" value="<?php echo $message; ?>" /></td>
										<td><input type="submit" name="search" class="btn btn-success" value="Search" /></td>
									</tr>
                                    <?php
                                    $cnt = 0;
                                    foreach ($query as $row) { $cnt++;
                                        ?>

                                        <tr>
                                            <td><?php echo $cnt ." "; if($row['views']=='0'){ echo '<span class="round round-sm blue"> </span>'; } ?> </td>
											<td><?php echo ucwords($row['name']); ?></td>
											  <td><?php echo $row['email']; ?></td>
                                            <td><?php echo $row['phone']; ?></td>
											<td><?php echo ucwords($row['subject']); ?></td>
											<td><?php echo ucwords($row['message']); ?></td>
											 <td><a  href='<?php echo base_url(); ?>contact_us_master/delete/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i class="fa fa-trash-o"></i></a></td>
                                            
                                        </tr>
                                        <?php 
                                    }
										if($cnt == 0){?>
										<tr>
											<td colspan = "7">No recored found</td>
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
<div class="example-modal" id="model" style="display:none;">
            <div class="modal">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Contact Details</h4>
                  </div>
                  <div class="modal-body" id="body_value">
                    <p>One fine body&hellip;</p>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                  </div>
                </div><!-- /.modal-content -->
              </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
          </div><!-- /.example-modal -->
<script>
	function view_details(val) {
		$("#model").show;
	}
</script>