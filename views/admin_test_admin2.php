<section class="content-header">
    <h1>
        Dashboard             
    </h1>

    <ol class="breadcrumb">
        <li><a href=""><i class="fa fa-dashboard"></i>Home</a></li>
        <li class="active">Dashboard</li>
    </ol>
</section>
<?php if ($login_data["type"] == 1 || $login_data["type"] == 5 || $login_data["type"] == 6 || $login_data["type"] == 7 || $login_data["type"] == 2) { ?>
    <section class="content">
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-olive">
                    <span class="info-box-icon"><i class="fa fa-money"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Today's Total Revenue</span>
                        <span class="info-box-number">Rs. <?php
                            if ($total_revenue[0]['revenue'] == "") {
                                echo "0";
                            } else {
                                echo number_format((float) $total_revenue[0]['revenue'], 2, '.', '');
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
                <div class="info-box bg-yellow">
                    <span class="info-box-icon"><i class="fa fa-money"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Today's Total Due Amount</span>
                        <span class="info-box-number">Rs. <?php
                            if ($total_due_amount[0]['due_amount'] == "") {
                                echo "0";
                            } else {
                                echo number_format((float) $total_due_amount[0]['due_amount'], 2, '.', '');
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
                        <span class="info-box-number"><?php echo $total_sample[0]['test']; ?></span>
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
        </div>
        <!-- /.row -->
    </section>
    <?php if ($login_data["type"] == 1 || $login_data["type"] == 2) { ?>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <?php /*  <div class="col-md-6">
                      <!-- AREA CHART -->
                      <div class="box box-primary">
                      <div class="box-header with-border">
                      <h3 class="box-title">Sample and Test Chart (Last week)</h3><br><br>
                      <div style="width:20%;float:left;"><div style="height: 13px;width: 13px;background-color: #D2D6DE;float: left;margin:5px 5px 5px 5px;"></div>Total Test</div>
                      <div style="width:20%;float:left;"><div style="height: 13px;width: 13px;background-color: #4B94BF;float: left;margin:5px 5px 5px 5px;"></div>Total Sample</div>
                      <div class="box-tools pull-right">
                      <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                      <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                      </div>
                      </div>
                      <div class="box-body">
                      <div class="chart">
                      <canvas id="areaChart" height="250"></canvas>
                      </div>
                      </div><!-- /.box-body -->
                      </div><!-- /.box -->
                      </div> */ ?>
                    <!-- DONUT CHART -->

                    <!-- LINE CHART -->
                    <?php /* <div class="col-md-6">
                      <div class="box box-info">
                      <div class="box-header with-border">
                      <h3 class="box-title">Revenue Chart (Last week)</h3><br><br>
                      <div style="width:20%;float:left;"><div style="height: 13px;width: 13px;background-color: #D2D6DE;float: left;margin:5px 5px 5px 5px;"></div>Total Revenue</div>
                      <div class="box-tools pull-right">
                      <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                      <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                      </div>
                      </div>
                      <div class="box-body">
                      <div class="chart">
                      <canvas id="lineChart" height="250"></canvas>
                      </div>
                      </div><!-- /.box-body -->
                      </div><!-- /.box -->
                      </div> */ ?>
                    <div class="col-md-6">
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">Revenue Chart (<?php
                                    if ($date == 0) {
                                        echo "Today";
                                    }if ($date == '-1') {
                                        echo "YesterDay";
                                    }if ($date == -2) {
                                        echo "Last Two Day";
                                    }if ($date == -7) {
                                        echo "Last Week";
                                    }if ($date == -30) {
                                        echo "Last Month";
                                    }
                                    ?>)</h3><br>

                                <div class="box-tools pull-right">
                                    <div class="btn-group">
                                        <a class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
                                            Action
                                            <span class="caret"></span>
                                        </a>
                                        <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
                                            <li><a tabindex="-1" href="<?= base_url(); ?>/Dashboard?date=0">Today</a></li>
                                            <li><a tabindex="-1" href="<?= base_url(); ?>/Dashboard?date=-1">Last Two Days</a></li>
                                            <li><a tabindex="-1" href="<?= base_url(); ?>/Dashboard?date=-7">Last Seven Days</a></li>
                                            <li><a tabindex="-1" href="<?= base_url(); ?>/Dashboard?date=-30">Last Month</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="box-body">
                                <div class="chart">
                                    <div id="columnchart_material" style="width: 610px; height: 270px;"></div>
                                </div>
                            </div><!-- /.box-body -->
                        </div><!-- /.box -->
                    </div>

                    <div class="col-md-6">
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">Job Status Chart (<?php
                                    if(empty($date1)){
                                        echo "Today";
                                    }
                                    if ($date1 == 0) {
                                        echo "Today";
                                    }if ($date1 == '-1') {
                                        echo "YesterDay";
                                    }if ($date1 == -2) {
                                        echo "Last Two Day";
                                    }if ($date1 == -7) {
                                        echo "Last Week";
                                    }if ($date1 == -30) {
                                        echo "Last Month";
                                    }
                                    ?>)</h3><br>

                                <div class="box-tools pull-right">
                                    <div class="btn-group">
                                        <a class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
                                            Action
                                            <span class="caret"></span>
                                        </a>
                                        <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
                                            <li><a tabindex="-1" href="<?= base_url(); ?>/Dashboard?j_date=0">Today</a></li>
                                            <li><a tabindex="-1" href="<?= base_url(); ?>/Dashboard?j_date=-1">Last Two Days</a></li>
                                            <li><a tabindex="-1" href="<?= base_url(); ?>/Dashboard?j_date=-7">Last Seven Days</a></li>
                                            <li><a tabindex="-1" href="<?= base_url(); ?>/Dashboard?j_date=-30">Last Month</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="box-body">
                                <div class="chart">
                                    <div id="chart_div" style="width: 610px; height: 270px;"></div>
                                </div>
                            </div><!-- /.box-body -->
                        </div><!-- /.box -->
                    </div>

                    <div class="col-md-6">
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">Collected Amount (<?php
                                    if (!empty($date2)) {
                                        echo $date2;
                                    } else {
                                        echo date("Y-m-d");
                                    }
                                    ?>)</h3><br>

                                <div class="box-tools pull-right">
                                    <input type="text" name="collect_date" readonly="" value="<?php
                                    if (!empty($date2)) {
                                        echo $date2;
                                    } else {
                                        echo date("d/m/Y");
                                    }
                                    ?>" onchange="get_date(this.value);" id="collection_date" class="from-control datepicker-input"/>
                                </div>
                            </div>
                            <div class="box-body">
                                <div class="chart">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Date</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $price1=0; foreach ($collecting_amount as $key) { ?>
                                                <tr>
                                                    <td><?= ucfirst($key["name"]) ?></td>
                                                    <td><?php
                                                        if (!empty($date2)) {
                                                            echo $date2;
                                                        } else {
                                                            echo date("d/m/Y");
                                                        }
                                                        ?></td>
                                                    <td>Rs.<?php  $price1 += number_format((float) round($key["SUM"]), 2, '.', ''); echo number_format((float) round($key["SUM"]), 2, '.', ''); ?></td>
                                                    <td><?php if ($key["admin_fk"] == '') { ?><span class="label label-warning ">Pending</span><?php } else { ?><span class="label label-success">Collected</span><?php } ?></td>
                                                    <td><?php if ($key["admin_fk"] == '') { ?><a href="<?= base_url(); ?>Admin/collect_amount?date=<?= $date2 ?>&amount=<?= ucfirst($key["sum"]) ?>&user=<?= $key["user_fk"] ?>" onclick="return confirm('Are you sure?');"><i class="fa fa-check"></i></a><?php } ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php
                                            if (!empty($phlebo_collect)) {
                                                $prc = 0;
                                                foreach ($phlebo_collect as $d_key) {
                                                    $d_price = $d_key["amount"];
                                                    $prc += $d_price;
                                                }
                                                ?>
                                                <tr>
                                                    <td>Phlebo Collect</td>
                                                    <td><?php
                                                        if (!empty($date2)) {
                                                            echo $date2;
                                                        } else {
                                                            echo date("d/m/Y");
                                                        }
                                                        ?></td>
                                                    <td>Rs.<?= $price2= number_format((float) round($prc), 2, '.', ''); ?></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if (!empty($online_pay)) { ?>
                                                <tr>
                                                    <td>Online Payment</td>
                                                    <td><?php
                                                        if (!empty($date2)) {
                                                            echo $date2;
                                                        } else {
                                                            echo date("d/m/Y");
                                                        }
                                                        ?></td>
                                                    <td>Rs.<?= $price3= number_format((float) round($online_pay[0]["amount"]), 2, '.', ''); ?></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if (!empty($cut_from_wallet)) { ?>
                                                <tr>
                                                    <td>Wallet Cut</td>
                                                    <td><?php
                                                        if (!empty($date2)) {
                                                            echo $date2;
                                                        } else {
                                                            echo date("d/m/Y");
                                                        }
                                                        ?></td>
                                                    <td>Rs.<?= $price4= number_format((float) round($cut_from_wallet[0]["amount"]), 2, '.', ''); ?></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            <?php } ?>
                                            <?php
                                            if (!empty($discount)) {
                                                $prc = 0;
                                                foreach ($discount as $d_key) {
                                                    $d_price = $d_key["price"];
                                                    $d_discount = $d_key["discount"];
                                                    $discount_price = $d_price * $d_discount / 100;
                                                    $prc += $discount_price;
                                                }
                                                ?>
                                                <tr>
                                                    <td>Discount Price</td>
                                                    <td><?php
                                                        if (!empty($date2)) {
                                                            echo $date2;
                                                        } else {
                                                            echo date("d/m/Y");
                                                        }
                                                        ?></td>
                                                    <td>Rs.<?= $price5= number_format((float) round($prc), 2, '.', ''); ?></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            <?php } ?>
                                            <tr>
                                                <td colspan="2"><b>Total Amount</b></td>
                                                <td colspan="3">Rs.<?php $ttl = $price1+$price2+$price3+$price4+$price5; echo number_format((float) round($ttl), 2, '.', ''); ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div><!-- /.box-body -->
                        </div><!-- /.box -->
                    </div>
                </div><!-- /.col (RIGHT) -->
            </div><!-- /.row -->

        </section><!-- /.content -->

        <!-- jQuery 2.1.4 -->
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
                                                    function get_date(val) {
                                                        if (val != '') {
                                                            window.location.href = "<?= base_url() ?>/Dashboard?c_date=" + val;
                                                        }
                                                    }
                                                    google.charts.load('current', {packages: ['corechart', 'bar']});
                                                    google.charts.setOnLoadCallback(drawChart);

                                                    function drawChart() {
                                                        var data = google.visualization.arrayToDataTable([
                                                            ['Date', 'Revenue(Rs)', 'Due(Rs)', 'Paid(Rs)'],
                                                            ['<?php
                                            if ($date == 0) {
                                                echo "Today";
                                            }if ($date == '-1') {
                                                echo "Last Two Days";
                                            }if ($date == -7) {
                                                echo "Last Seven Days";
                                            }if ($date == -30) {
                                                echo "Last Month";
                                            }
                                            ?>', <?php echo (!empty($total_revenue[0]["revenue"])) ? $total_revenue[0]["revenue"] : 0; ?>, <?php echo (!empty($total_due_amount[0]["due_amount"])) ? $total_due_amount[0]["due_amount"] : 0; ?>, <?php echo ((!empty($total_revenue[0]["revenue"])) ? $total_revenue[0]["revenue"] : 0) - ((!empty($total_due_amount[0]["due_amount"])) ? $total_due_amount[0]["due_amount"] : 0); ?>]
                                                        ]);

                                                        var options = {
                                                            hAxis: {format: 'decimal'},
                                                            vAxis: {format: 'decimal'},
                                                            chart: {
                                                                //   title: 'Company Performance',
        //            subtitle: 'Sales, Expenses, and Profit: 2014-2017',
                                                            }
                                                        };

                                                        var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

                                                        chart.draw(data, google.charts.Bar.convertOptions(options));
                                                    }

                                                    google.charts.setOnLoadCallback(drawAnnotations);

                                                    function drawAnnotations() {
        //var data = new google.visualization.DataTable();
                                                        var data = google.visualization.arrayToDataTable([
                                                        ['Number', 'Job Status', {role: 'style'}],
        <?php
        $status1 = 0;
        foreach ($job_status as $key) {
            $status1 = $status1 + $key["count"];
        }
        echo "['Total Jobs', " . $status1 . ", 'color: #4285f4'],";
        ?>
        <?php
        $status1 = 0;
        foreach ($job_status as $key) {
            if ($key["status"] == 1) {
                echo "['Waiting For Approval', " . $key["count"] . ", 'color: #db4437'],";
                $status1 = 1;
            }
        } if ($status1 == 0) {
            echo "['Waiting For Approval', 0, 'color: #db4437'],";
        }
        ?>
        <?php
        $status1 = 0;
        foreach ($job_status as $key) {
            if ($key["status"] == 6) {
                echo "['Approved', " . $key["count"] . ", 'color: #f4b400'],";
                $status1 = 1;
            }
        } if ($status1 == 0) {
            echo "['Approved', 0, 'color: #f4b400'],";
        }
        ?>
        <?php
        $status1 = 0;
        foreach ($job_status as $key) {
            if ($key["status"] == 7) {
                echo "['Sample Collected', " . $key["count"] . ", 'color: #f4b400'],";
                $status1 = 1;
            }
        } if ($status1 == 0) {
            echo "['Sample Collected', 0, 'color: #f4b400'],";
        }
        ?>
        <?php
        $status1 = 0;
        foreach ($job_status as $key) {
            if ($key["status"] == 8) {
                echo "['Processing', " . $key["count"] . ", 'color: #f4b400'],";
                $status1 = 1;
            }
        } if ($status1 == 0) {
            echo "['Processing', 0, 'color: #f4b400'],";
        }
        ?>
        <?php
        $status1 = 0;
        foreach ($job_status as $key) {
            if ($key["status"] == 2) {
                echo "['Completed', " . $key["count"] . ", 'color: green']";
                $status1 = 1;
            }
        } if ($status1 == 0) {
            echo "['Completed', 0, 'color: green']";
        }
        ?>
                                                        ]);

                                                        var options = {
                                                            hAxis: {format: 'decimal'},
                                                            vAxis: {format: 'decimal'}
                                                            //  title: 'Motivation and Energy Level Throughout the Day'
                                                        };

                                                        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
                                                        chart.draw(data, options);
                                                    }
        </script>


        <!-- page script -->
        <script>
            $(function () {
                /* ChartJS
                 * -------
                 * Here we will create a few charts using ChartJS
                 */

                //--------------
                //- AREA CHART -
                //--------------

                // Get context with jQuery - using jQuery's .get() method.
                var areaChartCanvas = $("#areaChart").get(0).getContext("2d");
                // This will get the first returned node in the jQuery collection.
                var areaChart = new Chart(areaChartCanvas);
                var areaChartData = {
                    labels: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
                    datasets: [
                        {
                            label: "Total Tests",
                            fillColor: "rgba(210, 214, 222, 1)",
                            strokeColor: "rgba(210, 214, 222, 1)",
                            pointColor: "rgba(210, 214, 222, 1)",
                            pointStrokeColor: "#c1c7d1",
                            pointHighlightFill: "#fff",
                            pointHighlightStroke: "rgba(220,220,220,1)",
                            data: [<?php
        if ($test_sunday[0]['total'] == "") {
            echo "0";
        } else {
            echo $test_sunday[0]['total'];
        }
        ?>, <?php
        if ($test_monday[0]['total'] == "") {
            echo "0";
        } else {
            echo $test_monday[0]['total'];
        }
        ?>, <?php
        if ($test_tuesday[0]['total'] == "") {
            echo "0";
        } else {
            echo $test_tuesday[0]['total'];
        }
        ?>, <?php
        if ($test_wednesday[0]['total'] == "") {
            echo "0";
        } else {
            echo $test_wednesday[0]['total'];
        }
        ?>, <?php
        if ($test_thrusday[0]['total'] == "") {
            echo "0";
        } else {
            echo $test_thrusday[0]['total'];
        }
        ?>, <?php
        if ($test_friday[0]['total'] == "") {
            echo "0";
        } else {
            echo $test_friday[0]['total'];
        }
        ?>, <?php
        if ($test_saturday[0]['total'] == "") {
            echo "0";
        } else {
            echo $test_saturday[0]['total'];
        }
        ?>]
                        },
                        {
                            label: "Total Sample Collection",
                            fillColor: "rgba(60,141,188,0.9)",
                            strokeColor: "rgba(60,141,188,0.8)",
                            pointColor: "#3b8bba",
                            pointStrokeColor: "rgba(60,141,188,1)",
                            pointHighlightFill: "#fff",
                            pointHighlightStroke: "rgba(60,141,188,1)",
                            data: [<?php
        if ($sample_sunday[0]['total'] == "") {
            echo "0";
        } else {
            echo $sample_sunday[0]['total'];
        }
        ?>, <?php
        if ($sample_monday[0]['total'] == "") {
            echo "0";
        } else {
            echo $sample_monday[0]['total'];
        }
        ?>, <?php
        if ($sample_tuesday[0]['total'] == "") {
            echo "0";
        } else {
            echo $sample_tuesday[0]['total'];
        }
        ?>, <?php
        if ($sample_wednesday[0]['total'] == "") {
            echo "0";
        } else {
            echo $sample_wednesday[0]['total'];
        }
        ?>, <?php
        if ($sample_thrusday[0]['total'] == "") {
            echo "0";
        } else {
            echo $sample_thrusday[0]['total'];
        }
        ?>, <?php
        if ($sample_friday[0]['total'] == "") {
            echo "0";
        } else {
            echo $sample_friday[0]['total'];
        }
        ?>, <?php
        if ($sample_saturday[0]['total'] == "") {
            echo "0";
        } else {
            echo $sample_saturday[0]['total'];
        }
        ?>]
                        }
                    ],
                    options: {
                        scales: {
                            yAxes: [{
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'probability'
                                    }
                                }]

                        }

                    }
                };
                var areaChartData1 = {
                    labels: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
                    datasets: [
                        {
                            label: "Total Amount",
                            fillColor: "rgba(210, 214, 222, 1)",
                            strokeColor: "rgba(210, 214, 222, 1)",
                            pointColor: "rgba(210, 214, 222, 1)",
                            pointStrokeColor: "#c1c7d1",
                            pointHighlightFill: "#fff",
                            pointHighlightStroke: "rgba(220,220,220,1)",
                            data: [<?php
        if ($amount_sunday[0]['total'] == "") {
            echo "0";
        } else {
            echo $amount_sunday[0]['total'];
        }
        ?>, <?php
        if ($amount_monday[0]['total'] == "") {
            echo "0";
        } else {
            echo $amount_monday[0]['total'];
        }
        ?>, <?php
        if ($amount_tuesday[0]['total'] == "") {
            echo "0";
        } else {
            echo $amount_tuesday[0]['total'];
        }
        ?>, <?php
        if ($amount_wednesday[0]['total'] == "") {
            echo "0";
        } else {
            echo $amount_wednesday[0]['total'];
        }
        ?>, <?php
        if ($amount_thrusday[0]['total'] == "") {
            echo "0";
        } else {
            echo $amount_thrusday[0]['total'];
        }
        ?>, <?php
        if ($amount_friday[0]['total'] == "") {
            echo "0";
        } else {
            echo $amount_friday[0]['total'];
        }
        ?>, <?php
        if ($amount_saturday[0]['total'] == "") {
            echo "0";
        } else {
            echo $amount_saturday[0]['total'];
        }
        ?>]
                        },
                                /*{
                                 label: "Cashback",
                                 fillColor: "rgba(60,141,188,0.9)",
                                 strokeColor: "rgba(60,141,188,0.8)",
                                 pointColor: "#3b8bba",
                                 pointStrokeColor: "rgba(60,141,188,1)",
                                 pointHighlightFill: "#fff",
                                 pointHighlightStroke: "rgba(60,141,188,1)",
                                 data: [<?php
        if ($cashback_sunday[0]['total'] == "") {
            echo "0";
        } else {
            echo $cashback_sunday[0]['total'];
        }
        ?>, <?php
        if ($acashback_monday[0]['total'] == "") {
            echo "0";
        } else {
            echo $cashback_monday[0]['total'];
        }
        ?>, <?php
        if ($cashback_tuesday[0]['total'] == "") {
            echo "0";
        } else {
            echo $cashback_tuesday[0]['total'];
        }
        ?>, <?php
        if ($cashback_wednesday[0]['total'] == "") {
            echo "0";
        } else {
            echo $cashback_wednesday[0]['total'];
        }
        ?>, <?php
        if ($cashback_thrusday[0]['total'] == "") {
            echo "0";
        } else {
            echo $cashback_thrusday[0]['total'];
        }
        ?>, <?php
        if ($cashback_friday[0]['total'] == "") {
            echo "0";
        } else {
            echo $cashback_friday[0]['total'];
        }
        ?>, <?php
        if ($cashback_saturday[0]['total'] == "") {
            echo "0";
        } else {
            echo $cashback_saturday[0]['total'];
        }
        ?>]
                                 }, {
                                 label: "Digital Goods",
                                 fillColor: "rgba(60,141,188,0.9)",
                                 strokeColor: "rgba(60,141,188,0.8)",
                                 pointColor: "#3b8a",
                                 pointStrokeColor: "rgba(60,141,188,1)",
                                 pointHighlightFill: "#fff",
                                 pointHighlightStroke: "rgba(60,141,188,1)",
                                 data: [40, 60, 52, 31, 98, 39, 102]
                                 }*/
                    ]
                };
                var areaChartOptions = {
                    //Boolean - If we should show the scale at all
                    showScale: true,
                    //Boolean - Whether grid lines are shown across the chart
                    scaleShowGridLines: false,
                    //String - Colour of the grid lines
                    scaleGridLineColor: "rgba(0,0,0,.05)",
                    //Number - Width of the grid lines
                    scaleGridLineWidth: 1,
                    //Boolean - Whether to show horizontal lines (except X axis)
                    scaleShowHorizontalLines: true,
                    //Boolean - Whether to show vertical lines (except Y axis)
                    scaleShowVerticalLines: true,
                    //Boolean - Whether the line is curved between points
                    bezierCurve: true,
                    //Number - Tension of the bezier curve between points
                    bezierCurveTension: 0.3,
                    //Boolean - Whether to show a dot for each point
                    pointDot: true,
                    //Number - Radius of each point dot in pixels
                    pointDotRadius: 4,
                    //Number - Pixel width of point dot stroke
                    pointDotStrokeWidth: 1,
                    //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
                    pointHitDetectionRadius: 20,
                    //Boolean - Whether to show a stroke for datasets
                    datasetStroke: true,
                    //Number - Pixel width of dataset stroke
                    datasetStrokeWidth: 2,
                    //Boolean - Whether to fill the dataset with a color
                    datasetFill: true,
                    //String - A legend template
                    //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
                    maintainAspectRatio: false,
                    //Boolean - whether to make the chart responsive to window resizing
                    responsive: true
                };
                //Create the line chart
                areaChart.Line(areaChartData, areaChartOptions);
                /*     var chartInstance = new Chart(ctx, {
                 type: 'line',
                 data: lineChartCanvas,
                 options: {
                 title: {
                 display: true,
                 text: 'Custom Chart Title'
                 }
                 }
                 }); */


                //-------------
                //- LINE CHART -
                //--------------
                var lineChartCanvas = $("#lineChart").get(0).getContext("2d");
                var lineChart = new Chart(lineChartCanvas);
                var lineChartOptions = areaChartOptions;
                lineChartOptions.datasetFill = false;
                lineChart.Line(areaChartData1, lineChartOptions);
            });
        </script>
    <?php } else { ?>

        <section class="content">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12 text-center" style="background-color:#dd4b39">
                    <img src="<?php echo base_url(); ?>user_assets/images/logo1.png" class="" style="  " alt="User Image" />
                </div>
            </div>
        </section>
    <?php } ?>
    <!-- jQuery 2.1.4 -->
<?php } ?>

