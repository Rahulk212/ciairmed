<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            User type Permission
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>home"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>"><i class="fa fa-users"></i>type</a></li>

        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        
						 <?php $attributes = array('class' => 'form-horizontal', 'method' => 'POST'); ?>
                        <?php
                        echo form_open('user_type/user_type_permission', $attributes);
                       
                        ?>
                                <div class="col-md-3">
                                <select class="form-control" name="role">
                                <option >Select Type</option>
								<?php foreach($query as $types) {?>
                                <option value="<?php echo $types['id']; ?>" <?php if($rid==$types['id']) { echo "selected"; } ?>><?php echo ucfirst($types['type']);?></option>
                                <?php } ?>
                                </select>
                                </div>
                                
                                <div class="col-md-2">
                                    <input type="submit" class="btn btn-primary" value="Search">
                                </div>
                            </form>
<?php $attributes = array('class' => 'form-horizontal', 'method' => 'POST'); ?>
                        <?php
                        echo form_open('user_type/permission_update', $attributes);
                        
                        ?>
						
                            <?php if(isset($rid)){ ?>
                        <input type="submit" class="btn btn-primary" value="Update">
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
                        
                        <div class="tableclass">
                            <table id="" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
												<th> Menu </th>
                                                <th>Read</th>
                                                <th>Create</th>
                                                <th>Update</th>
                                                <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   
                                    <?php if(!empty($permission)){ foreach ($permission as $permission1){ ?>
                                            <tr>
                                                <td><?php echo $permission1['menu_name']; ?></td>
                                                <td><input type="checkbox" value="1" <?php echo $permission1['read']; ?> <?php echo ($permission1['read']==1)?"checked":""; ?> name="read_<?php echo $permission1['mid']?>" ></td>
                                                <td><input type="checkbox" value="1" <?php echo$permission1['add']; ?> <?php echo ($permission1['add']==1)?"checked":""; ?> name="add_<?php echo $permission1['mid']?>" ></td>
                                                <td><input type="checkbox" value="1" <?php echo $permission1['update']; ?> <?php echo ($permission1['update']==1)?"checked":""; ?> name="update_<?php echo $permission1['mid']?>" ></td>
                                                <td><input type="checkbox" value="1" <?php echo $permission1['delete']; ?> <?php echo ($permission1['delete']==1)?"checked":""; ?> name="delete_<?php echo $permission1['mid']?>" ></td>
                                            </tr>
									<?php } }else { ?>
										
											<?php foreach($menu as $key) { ?>
											<tr>
												<td><?php echo $key['menu_name'];?></td>
                                                <td><input type="checkbox" value="1"  name="read_<?php echo $key['id']; ?>" ></td>
                                                <td><input type="checkbox" value="1"  name="add_<?php echo $key['id']; ?>" ></td>
                                                <td><input type="checkbox" value="1" name="update_<?php echo $key['id']; ?>" ></td>
                                                <td><input type="checkbox" value="1" name="delete_<?php echo $key['id']; ?>" ></td>
									</tr>
									<?php } } ?>
											
											

                                </tbody>
                            </table>
							<?php }?>
							
							
							
							<input type="hidden" value="<?=$rid?>" name="rid">
							
                        </div>
                       </form>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
