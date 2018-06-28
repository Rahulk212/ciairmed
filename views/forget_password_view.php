<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>AirmedLabs</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.4 -->
    <link href="<?php echo base_url(); ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="<?php echo base_url(); ?>dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url(); ?>user_assets/images/fav_icon.ico" />
    <!-- iCheck -->
    <link href="<?php echo base_url(); ?>plugins/iCheck/square/blue.css" rel="stylesheet" type="text/css" />

  </head>
  <style>
  .bg {
	   background-image:url("<?php echo base_url();?>img/login_bg.jpg");
	   background-repeat: no-repeat;
	   background-size: cover;
	   
  }
  </style>
  <body class="bg">
    <div class="login-box">
      <div class="login-logo">
       
      </div><!-- /.login-logo -->
      <div class="login-box-body">
      
      <p style="color:red">
			<?php if(isset($error)){
				echo $error;
				}?>      
      </p>
	  <h1><img src="<?php echo base_url(); ?>user_assets/images/logo.png" alt="" style=" margin-left: 57px;" width="200px"></h1>
        <p class="login-box-msg">Forgot Password</p>
		  <?php  if(isset($getmsg1) != NULL){ ?>
                                        <div class="alert alert-danger">
                                        <?php echo $getmsg1['0']; ?>
                                        </div> 
					 				<?php } ?>
											 <?= validation_errors('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>', '</div>'); ?>

        <form action="<?php echo base_url(); ?>forget/index" method="post">
          <div class="form-group has-feedback">
            <input type="email" class="form-control" placeholder="Email" name="email" value="<?php echo set_value('email'); ?>"/>
            
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          
          <div class="row">
            <div class="col-xs-8">    
                                 
            </div><!-- /.col -->
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Submit</button>
            </div><!-- /.col -->
          </div>
        </form>
     
       </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

    <!-- jQuery 2.1.4 -->
    <script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="<?php echo base_url(); ?>bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- iCheck -->
    <script src="<?php echo base_url(); ?>plugins/iCheck/icheck.min.js" type="text/javascript"></script>
    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });
    </script>
  </body>
</html>