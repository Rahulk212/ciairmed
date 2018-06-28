<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            User List
            <small></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>home"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>admin_master/admin_list"><i class="fa fa-users"></i>Admin</a></li>
            <li class="active">Admin List</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title">Admin List</h3>
					<?php if(isset($this->data['user_permission'][0]['add'])==1 || $this->data['usertype'] == 0) { ?>
					<a style="float:right;" href='<?php echo base_url(); ?>admin/admin_add' class="btn btn-primary btn-sm" ><i class="fa fa-plus-circle" ></i><strong > Add</strong></a><?php } ?>
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
					
					
				  
	<br>
<div class="tableclass">
                  <table id="example2" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>No</th>
						<th>Admin Name</th>
						
                        <th>Email</th>
						
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
                        
                        <td><?php echo ucwords($row['name']); ?></td>
                        <td><?php echo $row['email']; ?></td>
						
                        <td>
						<?php if(isset($this->data['user_permission'][0]['update'])==1 || $this->data['usertype'] == 0)  { ?>
						<a href='<?php echo base_url(); ?>admin/admin_edit/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-edit"></i></a><?php } ?>
                                <?php if(isset($this->data['user_permission'][0]['delete'])==1 || $this->data['usertype'] == 0) { ?>                        
								<a  href='<?php echo base_url(); ?>admin/admin_delete/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i class="fa fa-trash-o"></i></a>    <?php } ?>    
						  </td>
                      </tr>
						<?php $cnt++; } ?>

                    </tbody>
                  </table>
                  </div>
                <div style="text-align:right;" class="box-tools">
					<ul class="pagination pagination-sm no-margin pull-right">
					
						</ul>
				</div>
				   </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->