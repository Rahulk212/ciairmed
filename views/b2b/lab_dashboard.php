<section class="content-header">
    <h1>
        Lab-Dashboard             
    </h1>

    <ol class="breadcrumb">
        <li><a href=""><i class="fa fa-dashboard"></i>Home</a></li>
        <li class="active">Lab-Dashboard</li>
    </ol>
</section>
    <section class="content">
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-yellow">
                    <span class="info-box-icon"><i class="fa fa-eyedropper"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Today's Job</span>
                        <span class="info-box-number"><?=count($today_job)?></span>
                        <div class="progress">
                            <div style="width: 100%" class="progress-bar"></div>
                        </div>
<!--                        <a href="#" style="color:white;"><span class="progress-description">
                                View More
                            </span></a>-->
                    </div><!-- /.info-box-content -->
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-teal">
                    <span class="info-box-icon"><i class="fa fa-eyedropper"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Job</span>
                        <span class="info-box-number"><?=count($total_job)?></span>
                        <div class="progress">
                            <div style="width: 100%" class="progress-bar"></div>
                        </div>
<!--                        <a href="#" style="color:white;"><span class="progress-description">
                                View More
                            </span></a>-->
                    </div><!-- /.info-box-content -->
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-olive">
                    <span class="info-box-icon"><i class="fa fa-money"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Credit Amount</span>
                        <span class="info-box-number">Rs.<?php $credit=0;$due=0; if($credit_job[0]["total"]>0){ $credit=$credit_job[0]["total"]; }else{ $due=$credit_job[0]["total"]; } echo $credit; ?></span> 
                        <div class="progress">
                            <div style="width: 100%" class="progress-bar"></div>
                        </div>
<!--                        <a href="#" style="color:white;"><span class="progress-description">
                                View More
                            </span></a>-->
                    </div><!-- /.info-box-content -->
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-red">
                    <span class="info-box-icon"><i class="fa fa-money"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Due Amount</span>
                        <span class="info-box-number">Rs.<?php echo 0-$due; ?></span> 
                        <div class="progress">
                            <div style="width: 100%" class="progress-bar"></div>
                        </div>
<!--                        <a href="#" style="color:white;"><span class="progress-description">
                                View More
                            </span></a>-->
                    </div><!-- /.info-box-content -->
                </div>
            </div>
        </div>
        <!-- /.row -->
    </section>
</div>
</div> 