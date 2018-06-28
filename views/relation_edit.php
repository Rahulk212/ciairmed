
<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<section class="content-header">
    <h1>
        Relation Update
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>relation_master/relation_list"><i class="fa fa-users"></i>Relation List</a></li>
        <li class="active">Edit Relation</li>
    </ol>
</section>
					
<section class="content">
    <div class="row">
        <div class="col-md-12">
<?php 
					
					foreach($query as $data)
						$id = $data['id'];
					{
				?>
            <div class="box box-primary">
                <p class="help-block" style="color:red;"><?php
                    if (isset($error)) {
                        echo $error;
                    }
					
					
                    ?></p>
		<div class="box-body">
				<div class="col-md-6">
                <!-- form start -->
                <form role="form" action="<?php echo base_url(); ?>relation_master/relation_edit/<?= $id ?>" method="post" enctype="multipart/form-data">
					
		
					<div class="form-group">
					
						<label for="name">Relation</label><span style="color:red">*</span>
						<input type="text"  name="name" class="form-control" value="<?php echo $data['name']; ?>" >
						<?php echo form_error('name');?>
						
					</div>
					
                    <button class="btn btn-primary" type="submit">Update</button>
					<input type="hidden"  name="id" class="form-control" value="<?php echo $id; ?>" >
				</div>
				
                </form>
			<?php 
				}
			?>
            </div><!-- /.box -->
	
        </div>
    </div>
</section>
