<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Testimonials
            <small></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>home"><i class="fa fa-dashboard"></i>Dashboard</a></li>
           
            <li class="active">Testimonials</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title">Testimonials List</h3>
					
                        <a style="float:right;" href='<?php echo base_url(); ?>testimonials_master/add' class="btn btn-primary btn-sm" ><i class="fa fa-plus-circle" ></i><strong > Add</strong></a>
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
                  <table id="example4" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>No</th>
						<th>Name</th>
						<th>Address</th>  
						<th>Image</th>
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
						<td><?php echo $row['name']; ?></td>
						<td><?php echo $row['address']; ?></td>  
                        <td><img src="<?php echo base_url(); ?>upload/<?php echo $row['image']; ?>" alt="Profile Pic" style="width:50px; height:40px;"/>
</td>
                        <td>
						  <a href='<?php echo base_url(); ?>testimonials_master/edit/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-edit"></i></a>
                                                        
							<a  href='<?php echo base_url(); ?>testimonials_master/delete/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i class="fa fa-trash-o"></i></a>        
						  </td>
                      </tr>
						<?php $cnt++; } ?>

                    </tbody>
                  </table>
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
