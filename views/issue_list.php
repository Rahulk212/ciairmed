<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
Queries/Issues
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>customer-master/customer-list"><i class="fa fa-users"></i>Customer List</a></li>

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
					
					<div class="form-group">
                   						<select class="form-control" name="user">
                              <option value="">Select Customer</option>
								  <?php foreach($customer as $cat){ ?>
                              <option value="<?php echo $cat['id']; ?>" <?php if($customerfk == $cat['id']) { echo "selected"; }?> ><?php echo ucwords($cat['full_name']); ?></option>
						<?php } ?>
                              </select>
                    </div>
					</div>
					
					<div class="col-xs-3">
					<div class="form-group">
					<input type="submit" name="search" class="btn btn-success" value="Search" />
                    </div>
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
                            <table id="example2" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Customer Name</th>
										<th>Subject</th>
										<th>Massage</th>
										
										<th>Date</th>
                                      
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cnt = 1;
                                    foreach ($query as $row) {
                                        ?>

                                        <tr>
                                            <td><?php echo $cnt; ?></td>
											 <td><?php echo ucwords($row['full_name']); ?></td>
											  <td><?php echo ucwords($row['subject']); ?></td>
											  
											   <td><?php echo ucwords($row['massage']); ?></td>
                                            <td><?php echo ucwords($row['created_date']); ?></td>
                                            
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
