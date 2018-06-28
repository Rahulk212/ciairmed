<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Business Partnerships
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>customer-master/customer-list"><i class="fa fa-users"></i>Business Partnerships</a></li>

        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Business Partnerships List</h3>

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
                            <table id="example4" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
										<th>Domain</th>
                                        <th>Name</th>
										<th>Email</th>
										<th>Mobile</th>
										<th>Address</th>
										<th>Query/proposal</th>
                                       
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cnt = 1;
                                    foreach ($query as $row) {
                                        ?>

                                        <tr>
                                            <td><?php echo $cnt; ?></td>
											 <td><?php echo ucwords($row['domain']); ?> </td>
											 <td><?php echo ucwords($row['name']); ?> </td>
											  <td><?php echo $row['email']; ?></td>
                                            <td><?php echo ucwords($row['mobile']); ?></td>
											<td><?php echo ucwords($row['address']); ?></td>
											 <td><?php echo ucwords($row['query']); ?> </td>
                                            
                                        </tr>
                                        <?php $cnt++;
                                    } ?>

                                </tbody>
                            </table>
                        </div>
                       <ul class="pagination pagination-sm no-margin pull-right">
								<?php echo $links;?>
							</ul>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
