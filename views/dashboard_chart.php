 <div class="row">
            <div class="col-md-12">
                <div class="col-md-6">
                    <!-- AREA CHART -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Sample and Test Chart</h3>
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
                </div>
                <!-- DONUT CHART -->

                <!-- LINE CHART -->
                <div class="col-md-6">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Revenue Chart</h3>
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
                </div>
            </div><!-- /.col (RIGHT) -->
        </div><!-- /.row -->
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
                labels: ["Sunday", "Monday", "Tuesday", "Thrusday", "Wenusday", "Friday", "Saturday"],
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
                labels: ["Sunday", "Monday", "Tuesday", "Thrusday", "Wenusday", "Friday", "Saturday"],
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
                    {
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
                    }
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