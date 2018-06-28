<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<section class="content-header">
    <h1>
        Branch Edit
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
		 <li><a href="<?php echo base_url(); ?>Branch_master/Branch_list"><i class="fa fa-users"></i>Branch List</a></li>
        <li class="active">Edit Branch</li>
    </ol>
</section>
<section class="content">
<div class="row">
    <div class="col-md-12">
		<div class="box box-primary">  
			<div class="box-body">
				<div class="col-md-6">
					<!-- form start -->
		
					<?php $success = $this->session->flashdata('success'); ?>
			
					<?php if (isset($success) != NULL) { ?>
						<div class="alert alert-success alert-autocloseable-success">
							<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
						   <?php echo $this->session->userdata('success') ; ?>
						</div>
					<?php } ?>
				
					   <?php 
						
						foreach($view_data as $data)
						   /*   echo "<pre>";
							print_r($data);
							exit; */  
							$id = $data['id'];
						{
					?> 
				
				
                <!-- form start -->
              <?php   echo form_open_multipart('Branch_Master/Branch_edit/'.$id.'',['method' =>'post','class'=>'form-horizontal',
                        'id'=>'target','enctype'=>'multipart/form-data']);?>
				
					<div class="form-group">
						<label for="name">Branch Code</label><span style="color:red">*</span>

						
							<?php  echo form_input(['name'=>'branch_code','class'=>'form-control','placeholder'=>'Branch Code',
							'value'=>$data['branch_code']]);?>
							<?php echo form_error('branch_code');?>
							
						
					</div>
				
					<div class="form-group">
						<label for="name">Branch Name</label><span style="color:red">*</span>
						
						
							<?php  echo form_input(['name'=>'branch_name','class'=>'form-control','placeholder'=>'Branch Name',
							'value'=>$data['branch_name']]);?>
							<?php echo form_error('branch_name');?>
							
					
					</div>


					<div class="form-group">
						<label for="type">City</label><span style="color:red">*</span>
						

						<select class="form-control" id="city" onchange="test(this.value)" name="city">
					 
								
							<option value ="<?php echo $data['cid'] ?>"><?php echo $data['city_name'] ?></option>
							<?php 
							foreach($city as $row){
							if($row > 0 )
							{ ?>
									   <option value="<?php echo $row['cid']; ?>"><?php echo ucwords($row['city_name']); ?></option>
							<?php
							}
							else
							{
								echo '<option value="">city is Not Avaliable</option>';
							}
							}
							?> 
							
							</select>
						
					</div>
					
				
					
					<div class="form-group">
							<label for="type">Address</label><span style="color:red">*</span>
						
							<?php echo form_textarea(['class'=>'form-control','rows'=>'3','cols'=>'4','name'=>'address','value'=>urldecode($data['address']) ]); ?>
				
							<?php echo form_error('address');?>
						
					</div>
					
				 </div>
			</div>	
					
					<div class="box-footer">
						
							<button  class="btn btn-primary" name="button" type="submit">Update</button>
							
							<input type="hidden" id="idh" name="idh" value="<?php echo $id ;?>">			
					</div>
					
				
		</div>
	</div>
		
               <?php echo form_close(); ?>
			<?php 
				}
			?>
          
		  
	<script type="text/javascript">
	
			window.setTimeout(function() {
			$(".alert").fadeTo(500, 0).slideUp(500, function(){
				$(this).remove(); 
			});
		}, 4000);
</script>





<script type="text/javascript">

	
	  $('#state').on('change',function(){
        var stateID = $(this).val();
		//alert(stateID);
        if(stateID){
            $.ajax({
                type:'POST',
                url:'<?php echo base_url(); ?>Doctor_master/cities/'+stateID,
                data:{state_id:stateID},
                success:function(html){
                    $('#city').html(html);
                   
                }
            }); 
        }else{
           
            $('#city').html('<option value="">Select state first</option>'); 
        }
    });
	 
	
	/* function test(stateID){
	 	alert(stateID); 
		       $.ajax({
				    type:'POST',
                    url: "<?php echo base_url(); ?>Doctor_master/cities/"+stateID,
					data:'state_id='+stateID,
				    error: function(jqXHR, error, code) {
                        alert("not show");
                    },
                    success: function(data) {
                        $('#city').html(html);
					}
	 });
	}  */
</script> 	  
		  
		  
	
           
            <script  type="text/javascript">
                $(document).ready(function () {
                    $("#showHide").click(function () {
                        if ($("#password").attr("type") == "password") {
                            $("#password").attr("type", "text");
                        } else {
                            $("#password").attr("type", "password");
                        }

                    });
                });
            </script>
			<script type="text/javascript">
			window.setTimeout(function(){
			 $(".alert").fadeTo(500, 0).slideUp(500, function(){
				 $(this).remove();
			 });
			 }, 4000);
			</script>
	
       
</section>
