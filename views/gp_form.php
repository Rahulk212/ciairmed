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
  <body class="login-page">
    <div class="login-box">
      <div class="login-logo">
        <a href="<?php echo base_url(); ?>index2.html"><?php echo $this->config->item('title');?></a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
      
      <p style="color:red">
			<?php if(isset($error)){
				echo $error;
				}?>      
      </p>
        <p class="login-box-msg">Reset Password</p>
		  	 <?= validation_errors('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>', '</div>'); ?>
 
                         <?php $attributes = array('method'=>'post'); ?>

                                            <?php echo form_open('get_password/index', $attributes); ?>
        
          
          <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="Password" name="password"/>

            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
		  
		  <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="Conform Password" name="passconf"/>

            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
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