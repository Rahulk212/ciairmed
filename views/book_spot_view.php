<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title><?php echo $this->config->item('title');?></title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.4 -->
    <link href="<?php echo base_url(); ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="<?php echo base_url(); ?>dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- iCheck -->
    <link href="<?php echo base_url(); ?>plugins/iCheck/square/blue.css" rel="stylesheet" type="text/css" />

  </head>
 <body class="skin-blue sidebar-mini">
    <div class="wrapper">
      
     
      <!-- Left side column. contains the logo and sidebar -->
      
      <!-- Content Wrapper. Contains page content -->
      <div class="container">
        <!-- Content Header (Page header) -->
        <section class="content-header">
			<div class="col-sm-9">
				<h1 class="logo_cls"><img src="<?php echo base_url();?>/img/logo.png" style="width: 120px;"></h1>
			</div>
			<div class="col-sm-3">
				<a class="pull-right" href="<?php echo base_url(); ?>login_employee/logout"/> cerrar sesión </a>
			</div>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
		 <?php if (isset($success) != NULL) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $success; ?>
                                </div>
                            <?php } ?>

                        </div>
						<div class="widget">
                            <?php if (isset($error) != NULL) { ?>
                                <div class="alert alert-danger alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $error; ?>
                                </div>
                            <?php } ?>

                        </div>
		  <div class="col-md-12" id="week_hide">
			<div class="box box-primary">
				<div class="box-body book_box_bdy">
					<div class="col-sm-12 pdng_0">
						<div class="col-sm-7 pdng_0">
							<div class="col-sm-3 pdng_0 text-center">
								<label> Lunes <br/><span id="monday_date"> </span></label>
								<?php foreach($monday as $key) {
									$data1 = $this->time_slot_model->master_fun_get_tbl_val("book_shift_master", array("shift_fk" =>$key['id'],"date"=>$mondaydate,"status"=>1), array("id", "desc"));
									//print_r($data1);
			  $totalbooked  = count($data1); 
			 if($totalbooked < $key['spot']){
									?>
								
								<a href="#" onclick="get_date('<?php echo $key['id']?>','1');" ><span id="shift<?php echo $key['id']?>" class="btn btn-primary btn-xs"> <?php echo  $key['from'] .' To <br/>'. $key['to'];?></span></a></br>
								<?php } } ?>
								
							</div>
							<div class="col-sm-3 pdng_0 text-center">
								<label> Martes <br/><span id="tue_date"> </span></label>
								<?php foreach($tuesday as $key) {
									$data1 = $this->time_slot_model->master_fun_get_tbl_val("book_shift_master", array("shift_fk" =>$key['id'],"date"=>$tuesdaydate,"status"=>1), array("id", "desc"));
									//print_r($data1);
			  $totalbooked  = count($data1); 
			 if($totalbooked < $key['spot']){
									?>
								<a href="#" onclick="get_date('<?php echo $key['id']?>','2');" ><span  id="shift<?php echo $key['id']?>" class="btn btn-primary btn-xs"> <?php echo  $key['from'] .' To <br/>'. $key['to'];?></span></a>
								<?php } } ?>
							</div>
							<div class="col-sm-3 pdng_0 text-center">
								<label> Miércoles <br/><span id="wed_date"> </span></label>
								<?php foreach($wednesday as $key) {
									$data1 = $this->time_slot_model->master_fun_get_tbl_val("book_shift_master", array("shift_fk" =>$key['id'],"date"=>$weddate,"status"=>1), array("id", "desc"));
									//print_r($data1);
			  $totalbooked  = count($data1); 
			 if($totalbooked < $key['spot']){
									?>
								<a href="#" onclick="get_date('<?php echo $key['id']?>','3');" ><span id="shift<?php echo $key['id']?>" class="btn btn-primary btn-xs"> <?php echo  $key['from'] .' To <br/>'. $key['to'];?></span></a>
								<?php } }?>
							</div>
							<div class="col-sm-3 pdng_0 text-center">
								<label> Jueves <br/><span id="thu_date"> </span></label>
								<?php foreach($thrusday as $key) {
									
									$data1 = $this->time_slot_model->master_fun_get_tbl_val("book_shift_master", array("shift_fk" =>$key['id'],"date"=>$thudate,"status"=>1), array("id", "desc"));
									//print_r($data1);
			  $totalbooked  = count($data1); 
			 if($totalbooked < $key['spot']){
									?>
								<a href="#" onclick="get_date('<?php echo $key['id']?>','4');" ><span id="shift<?php echo $key['id']?>" class="btn btn-primary btn-xs"> <?php echo  $key['from'] .' To <br/>'. $key['to'];?></span></a>
								<?php } }?>
							</div>
						</div>
						<div class="col-sm-5 pdng_0">
							<div class="col-sm-4 pdng_0 text-center">
								<label> Viernes <br/><span id="fri_date"> </span></label>
								<?php foreach($friday as $key) {
									$data1 = $this->time_slot_model->master_fun_get_tbl_val("book_shift_master", array("shift_fk" =>$key['id'],"date"=>$fridate,"status"=>1), array("id", "desc"));
									//print_r($data1);
			  $totalbooked  = count($data1); 
			 if($totalbooked < $key['spot']){
									?>
								<a href="#" onclick="get_date('<?php echo $key['id']?>','5');" ><span  id="shift<?php echo $key['id']?>" class="btn btn-primary btn-xs"> <?php echo  $key['from'] .' To <br/>'. $key['to'];?></span></a>
								<?php } }?>
							</div>
							<div class="col-sm-4 pdng_0 text-center">
								<label> Sábado <br/><span id="sat_date"> </span></label>
								<?php foreach($saturday as $key) {
									$data1 = $this->time_slot_model->master_fun_get_tbl_val("book_shift_master", array("shift_fk" =>$key['id'],"date"=>$satdate,"status"=>1), array("id", "desc"));
									//print_r($data1);
			  $totalbooked  = count($data1); 
			 if($totalbooked < $key['spot']){
									
									?>
								<a href="#" onclick="get_date('<?php echo $key['id']?>','6');" ><span id="shift<?php echo $key['id']?>" class="btn btn-primary btn-xs"> <?php echo  $key['from'] .' To <br/>'. $key['to'];?></span></a>
								<?php } } ?>
							</div>
							<div class="col-sm-4 pdng_0 text-center">
								<label> Domingo <br/><span id="sunday_date"> </span></label>
								<?php foreach($sunday as $key) {
									$data1 = $this->time_slot_model->master_fun_get_tbl_val("book_shift_master", array("shift_fk" =>$key['id'],"date"=>$sundate,"status"=>1), array("id", "desc"));
									//print_r($data1);
			  $totalbooked  = count($data1); 
			 if($totalbooked < $key['spot']){
									?>
								<a href="#" onclick="get_date('<?php echo $key['id']?>','0');" ><span id="shift<?php echo $key['id']?>" class="btn btn-primary btn-xs"> <?php echo  $key['from'] .' To <br/>'. $key['to'];?></span></a>
								<?php } } ?>
							</div>
						</div>
					</div>
			  </div>
		  </div>
		  <div class="box box-primary">
		  <?php echo $maintext[0]['main_text'];?>
		  </div>
		  </div>
		  
            <!-- /.col (left) -->
			 <?= validation_errors('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>', '</div>'); ?>
                    
        <form action="<?php echo base_url(); ?>time_slot_master/book_spot" method="post">
<input type="hidden" class="form-control"  readonly name="date" id="date" value="<?php echo set_value('name'); ?>"/>
            <input type="hidden" class="form-control"  readonly name="shift_id" id="shift_id" value="<?php echo set_value('name'); ?>"/>
                       
		   <div class="col-md-12" style="display:none" id="next_div">
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title">Selección de Turno</h3>
                </div>
                <div class="box-body">
                  <!-- Date range -->
				  
				  <div class="form-group has-feedback"><label > Fecha </label> : <span id="confirmDate"> 18/4/2014 </span>
            </div>
          	  <div class="form-group has-feedback"><label > Selección </label> : <span id="confirmshift"> 18/4/2014 </span>
            </div>
                <!--<div class="form-group has-feedback">
            <input type="text" class="form-control" placeholder="Enter Your Name" name="name" value="<?php echo set_value('name'); ?>"/>
            
            
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="text" class="form-control" placeholder="Enter Your Mobile" name="mobile"/>

            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div> -->
<div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Confirmar</button>
	        </div>
    		<div class="col-xs-4">  <button type="button" onclick="backtolist()" class="btn btn-danger btn-block btn-flat">Regresar</button>
			  
            </div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->

			  
			  
              
            </div><!-- /.col (right) -->
          </div><!-- /.row -->
</form>
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      
      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class='control-sidebar-bg'></div>
    </div>
    <!-- jQuery 2.1.4 -->
    <script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="<?php echo base_url(); ?>bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- iCheck -->
    <script src="<?php echo base_url(); ?>plugins/iCheck/icheck.min.js" type="text/javascript"></script>
    <script>
	function backtolist(){
	$("#next_div").hide();
	$("#week_hide").show();
		
	}
	function get_date(val,dayOfWeek){
			 //alert(dayOfWeek);
			var dayOfWeek =  parseInt(dayOfWeek);
			 var d = new Date();
 d.setDate(d.getDate() + (dayOfWeek + 7 - d.getDay()) % 7);
console.log($("#shift"+val));
var date = (d.getMonth() + 1) + '/' + d.getDate() + '/' + d.getFullYear();

	document.getElementById('date').value=date;
	document.getElementById('shift_id').value=val;
console.log($("#shift"+val).html());	

	$("#confirmshift").html($("#shift"+val).html());
	$("#confirmDate").html($("#date").val());
	$("#next_div").show();
	$("#week_hide").hide();
	}
	
function getNextDayOfWeek() {
var s = new Date();
s.setDate(s.getDate() + (0 + 7 - s.getDay()) % 7);
var sunday_date = (s.getMonth() + 1) + '/' + s.getDate() + '/' + s.getFullYear();

var m = new Date();
m.setDate(m.getDate() + (1 + 7 - m.getDay()) % 7);
var monday_date = (m.getMonth() + 1) + '/' + m.getDate() + '/' + m.getFullYear();

var t = new Date();
t.setDate(t.getDate() + (2 + 7 - t.getDay()) % 7);
var tue_date = (t.getMonth() + 1) + '/' + t.getDate() + '/' + t.getFullYear();

var w = new Date();
w.setDate(w.getDate() + (3 + 7 - w.getDay()) % 7);
var wed_date = (w.getMonth() + 1) + '/' + w.getDate() + '/' + w.getFullYear();

var th = new Date();
th.setDate(th.getDate() + (4 + 7 - th.getDay()) % 7);
var thu_date = (th.getMonth() + 1) + '/' + th.getDate() + '/' + th.getFullYear();

var fr = new Date();
fr.setDate(fr.getDate() + (5 + 7 - fr.getDay()) % 7);
var fri_date = (fr.getMonth() + 1) + '/' + fr.getDate() + '/' + fr.getFullYear();

var sa = new Date();
sa.setDate(sa.getDate() + (6 + 7 - sa.getDay()) % 7);
var sat_date = (sa.getMonth() + 1) + '/' + sa.getDate() + '/' + sa.getFullYear();

//document.getElementById('sunday_date').innerHTML=sunday_date;
//document.getElementById('monday_date').innerHTML=monday_date;
//document.getElementById('tue_date').innerHTML=tue_date;
//document.getElementById('wed_date').innerHTML=wed_date;
//document.getElementById('thu_date').innerHTML=thu_date;
//document.getElementById('fri_date').innerHTML=fri_date;
//document.getElementById('sat_date').innerHTML=sat_date;

}

	function get_shift(val){
	 var dayOfWeek;
	 if(val=="monday"){
		 dayOfWeek=1;
	 }else if(val=="tuesday"){
		  dayOfWeek=2;
	 }else if(val=="wednesday"){
		  dayOfWeek=3;
	 }else if(val=="thursday"){
		  dayOfWeek=4;
	 }else if(val=="friday"){
		  dayOfWeek=5;
	 }else if(val=="saturday"){
		  dayOfWeek=6;
	 }
	 else if(val=="sunday"){
		  dayOfWeek=0;
	 }
	 
	 var d = new Date();
 d.setDate(d.getDate() + (dayOfWeek + 7 - d.getDay()) % 7);
//console.log(d);
var date = (d.getMonth() + 1) + '/' + d.getDate() + '/' + d.getFullYear();
if(val!=""){
	document.getElementById('date').value=date;
}else{
	document.getElementById('date').value="";
}

	 
	  $.ajax({
                    url: "<?php echo base_url(); ?>time_slot_master/get_shift/"+val,
                    error: function(jqXHR, error, code) {
                       // alert("not show");
                    },
                    success: function(data) {
                        //     console.log("data"+data);
						document.getElementById('shift').innerHTML = "";
						document.getElementById('shift').innerHTML = data;

					}
	 });
	 
 }
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });
	  getNextDayOfWeek();
    </script>
  </body>
</html>