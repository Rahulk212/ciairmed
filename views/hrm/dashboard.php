<section class="content-header">
    <h1>
        HRM-Dashboard             
    </h1>

    <ol class="breadcrumb">
        <li><a href=""><i class="fa fa-dashboard"></i>Home</a></li>
        <li class="active">HRM-Dashboard</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-olive">
                <span class="info-box-icon"><i class="fa fa-money"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Department</span>
                    <span class="info-box-number"><?php
                        if ($total_department == "") {
                        echo "0";
                        } else {
                        echo $total_department;
                        }
                        ?></span>
                    <div class="progress">
                        <div style="width: 100%" class="progress-bar"></div>
                    </div>
<!--                    <a href="#" style="color:white;"><span class="progress-description">
                            View More
                        </span></a>-->
                </div><!-- /.info-box-content -->
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-yellow">
                <span class="info-box-icon"><i class="fa fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Employee</span>
                    <span class="info-box-number"><?php
                        if ($total_employee == "") {
                        echo "0";
                        } else {
                        echo $total_employee;
                        }
                        ?></span>
                    <div class="progress">
                        <div style="width: 100%" class="progress-bar"></div>
                    </div>
<!--                    <a href="#" style="color:white;"><span class="progress-description">
                            View More
                        </span></a>-->
                </div><!-- /.info-box-content -->
            </div>
        </div>
    </div>
</div>
</div>
    <!-- /.row -->
</section>


