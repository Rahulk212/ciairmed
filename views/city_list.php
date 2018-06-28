<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            City
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>location-master/city-list"><i class="fa fa-users"></i>City</a></li>

        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">City List</h3>

                        <a style="float:right;" href='<?php echo base_url(); ?>location-master/city-add' class="btn btn-primary btn-sm" ><i class="fa fa-plus-circle" ></i><strong > Add</strong></a>

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
						<form role="form" action="<?php echo base_url(); ?>location-master/city-list" method="get" enctype="multipart/form-data">
                            <table id="example4" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
										<th>State</th>
										<th>City</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
								<tr>
									<td><span style="color:red;">*</span></td>
									<td><select class="form-control chosen-select" tabindex="-1"  data-placeholder="Select Customer" name="state">
										  <option value="">Select State</option>
											  <?php foreach($statelist as $stated){ ?>
										  <option value="<?php echo $stated['id']; ?>" <?php if($state == $stated['id']) { echo "selected"; }?> ><?php echo ucwords(strtolower($stated['state_name'])); ?></option>
									<?php } ?>
										  </select></td>
							<td><input type="text" placeholder="City" class="form-control" name="city" value="<?php echo $city; ?>"/></td>
									<td><input type="submit" name="search" class="btn btn-success" value="Search" /></td>
								</tr>
                                    <?php
                                    $cnt = 1;
                                    foreach ($query as $row) {
                                        ?>

                                        <tr>
                                            <td><?php echo $cnt; ?></td>
											  <td><?php echo ucwords($row['state_name']); ?></td>
                                            <td><?php echo ucwords($row['city_name']); ?></td>
                                            <td>
											
											<a href='<?php echo base_url(); ?>location-master/city-edit/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-edit"></i></a> 

<a  href='<?php echo base_url(); ?>location_master/city_delete/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i class="fa fa-trash-o"></i></a>      
                                            </td>
                                        </tr>
                                        <?php
                                        
                                        $cnt++;
                                     
                                    }if(empty($query)){ ?>
										<tr>
											<td colspan="12">No records found</td>
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
<script type="text/javascript">
      $(function () {
        $("#example1").dataTable();
        $('#example3').dataTable({
          "bPaginate": false,
          "bLengthChange": false,
          "bFilter": true,
          "bSort": false,
          "bInfo": false,
          "bAutoWidth": false
        });
      });
    </script>