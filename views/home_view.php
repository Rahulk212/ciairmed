<section class="content-header">
            <h1>
              Dashboard             
            </h1>
			  
            <ol class="breadcrumb">
              <li><a href=""><i class="fa fa-dashboard"></i>Home</a></li>
              <li class="active">Dashboard</li>
            </ol>
          </section>

               <section class="content">
               <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-user fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge">Total Customer</div>
                                        <h1><?php echo $totalcustomer; ?> </h1>
                                    </div>
                                </div>
                            </div>
                            <a href="<?php echo base_url();?>customer-master/customer-list">
                                <div class="panel-footer">
                                    <span class="pull-left">View Details</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="info-box bg-green">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-tasks fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge">Total Test</div>
                                         <h1><?php echo $totaltest; ?> </h1>
                                    </div>
                                </div>
                            </div>
                              <a href="<?php echo base_url();?>test-master/test-list">
                                <div class="panel-footer">
                                    <span class="pull-left">View Details</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
					
					 <div class="col-lg-3 col-md-6">
                        <div class="info-box bg-yellow">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-flask fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge">Total Pending Job</div>
                                         <h1><?php echo $pending; ?> </h1>
                                    </div>
                                </div>
                            </div>
                              <a href="<?php echo base_url();?>job-master/pending-list">
                                <div class="panel-footer">
                                    <span class="pull-left">View Details</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
					<div class="col-lg-3 col-md-6">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-eyedropper  fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge">Total Completed Job</div>
                                         <h1><?php echo $complete; ?> </h1>
                                    </div>
                                </div>
                            </div>
                              <a href="<?php echo base_url();?>job-master/completed-list">
                                <div class="panel-footer">
                                    <span class="pull-left">View Details</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
					
                    
                    
                </div>
                <!-- /.row -->
					</section>
  <!-- jQuery 2.1.4 -->
 