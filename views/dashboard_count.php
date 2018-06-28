<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-yellow">
            <span class="info-box-icon"><i class="fa fa-money"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Today's Total Revenue</span>
                <span class="info-box-number">Rs. <?php
                    if ($total_revenue[0]['revenue'] == "") {
                        echo "0";
                    } else {
                        echo $total_revenue[0]['revenue'];
                    }
                    ?></span>
                <div class="progress">
                    <div style="width: 100%" class="progress-bar"></div>
                </div>
                <a href="#" style="color:white;"><span class="progress-description">
                        View More
                    </span></a>
            </div><!-- /.info-box-content -->
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-teal">
            <span class="info-box-icon"><i class="fa fa-eyedropper"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Today's Total Samples</span>
                <span class="info-box-number"><?php echo $total_sample[0]['sample']; ?></span>
                <div class="progress">
                    <div style="width: 100%" class="progress-bar"></div>
                </div>
                <a href="#" style="color:white;"><span class="progress-description">
                        View More
                    </span></a>
            </div><!-- /.info-box-content -->
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-olive">
            <span class="info-box-icon"><i class="fa fa-flask"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Today's Total Test</span>
                <span class="info-box-number"><?php echo $total_test[0]['test']; ?></span>
                <div class="progress">
                    <div style="width: 100%" class="progress-bar"></div>
                </div>
                <a href="#" style="color:white;"><span class="progress-description">
                        View More
                    </span></a>
            </div><!-- /.info-box-content -->
        </div>
    </div>