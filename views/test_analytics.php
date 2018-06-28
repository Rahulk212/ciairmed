<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="<?=base_url();?>user_assets/analytics/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link href="<?=base_url();?>user_assets/analytics/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
           Test Analytics
          </h1>
          <ol class="breadcrumb">
				<div class="btn-group">
                      <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i>March 10, 2017 - March 10, 2017</button>
                      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                      </button>
					  <div class="dropdown-menu finance_tgl_drpdwn" role="menu">
						  <ul>
							<li class="active"><a href="#">Today</a></li>
							<li><a href="#">Yesterday</a></li>
							<li><a href="#">This Week</a></li>
							<li><a href="#">Last Week</a></li>
							<li><a href="#">This Month</a></li>
							<li><a href="#">Last Month</a></li>
						  </ul>
						  <div class="aply_cncl_btn">
							<a class="btn btn-success" href="#">Apply</a>
							<a class="btn btn-default" href="#">Cancel</a>
						  </div>
					  </div>
                 </div>
          </ol>
        </section>
		<section class="content">	
			<div class="row">
				<div class="col-sm-12">
					<div class="paid_due_chart">
						<div class="box box-primary">
							<div class="box-header with-border">
							  <h3 class="box-title">Chart</h3>
							  <div class="box-tools pull-right">
								<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
								<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
							  </div>
							</div>
							<div class="box-body chart-responsive">
								<div class="chart" id="bar-chart" style="height: 300px;"></div>
								<div class="col-sm-offset-5 col-sm-6">
									<div class="show_test_dscrptn_clr" style="background:#00A65A;"></div>
									<div class="paid_due_txtclr">
										<h4>Pathology</h4>
									</div>
									<!-- <div class="show_test_dscrptn_clr" style="background:#F56954;"></div>
									<div class="paid_due_txtclr">
										<h4>Due</h4>
									</div> -->
								</div>
							</div><!-- /.box-body -->
						</div>
					</div>
				</div>
				
			</div>
			
			<div class="row">
				<div class="col-sm-12 ">
					<div class="box box-primary top_rfrl">
						<div class="rfrl_chart_rgt">
							<div class="box-body">
								<canvas id="pieChart" height="250"></canvas>                  
							</div><!-- /.box-body -->
						</div>
						<div class="test_dscrptn_outr_div">
							<div class="test_decrptn_inr_div">
								<div class="show_test_dscrptn_clr" style="background:#F56954;"></div>
								<div class="show_test_dscrptn_name">
									<h4>CBC</h4>
								</div>
							</div>
							<div class="test_decrptn_inr_div">
								<div class="show_test_dscrptn_clr" style="background:#00A65A;"></div>
								<div class="show_test_dscrptn_name">
									<h4>Fever Profile</h4>
								</div>
							</div>
							<div class="test_decrptn_inr_div">
								<div class="show_test_dscrptn_clr" style="background:#F39C12;"></div>
								<div class="show_test_dscrptn_name">
									<h4>Bone Health Blood Test</h4>
								</div>
							</div>
							<div class="test_decrptn_inr_div">
								<div class="show_test_dscrptn_clr" style="background:#00C0EF;"></div>
								<div class="show_test_dscrptn_name">
									<h4>Hair Loss Blood Test</h4>
								</div>
							</div>
							<div class="test_decrptn_inr_div">
								<div class="show_test_dscrptn_clr" style="background:#3C8DBC;"></div>
								<div class="show_test_dscrptn_name">
									<h4>Liver Health Blood Test </h4>
								</div>
							</div>
							<div class="test_decrptn_inr_div">
								<div class="show_test_dscrptn_clr" style="background:#D2D6DE;"></div>
								<div class="show_test_dscrptn_name">
									<h4>Swasthya Profile A </h4>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="rfrl_btn">
						<ul class="inline-block">
							<li><a class="btn btn-block btn-primary btn-lg" href="#">Test Group</a></li>
							<li><a class="btn btn-block btn-default btn-lg" href="#">Referral Group</a></li>
							<li>
								<div class="btn-group export_pdf_btn pull-right">
									<button type="button" class="btn btn-info"><i class="fa fa-fw fa-file-pdf-o"></i>Export PDF</button>
									<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
										<span class="caret"></span>
										<span class="sr-only">Toggle Dropdown</span>
									</button>
									<ul class="dropdown-menu" role="menu">
										<li><a href="#">Total Collection 1</a></li>
										<li><a href="#">Total Collection 2</a></li>
										<li><a href="#">Total Collection 3</a></li>
										<li><a href="#">Total Collection 4</a></li>
									</ul>
								</div>
							</li>
							<li><a class="btn btn-block btn-primary btn-lg pull-right" href="#"><i class="fa fa-fw fa-file-excel-o"></i>Export Excel</a></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12 ">
					<div class="box box-primary top_rfrl">
						<div class="full_tbl">
							<div class="box-header tbl_head">
								<h3 class="box-title">Test:</h3>
							</div>
							<div class="box-body table-responsive">
								<table class="table table-hover">
									<tr>
									  <th>No.</th>
									  <th>Test</th>
									  <th>Count</th>
									  <th>Amount</th>
									</tr>
									<tr>
									  <td>1</td>
									  <td>CBC</td>
									  <td>2</td>
									  <td>1000</td>
									</tr>
									<tr>
									  <td>2</td>
									  <td>Bone Health Blood Test</td>
									  <td>2</td>
									  <td>1000</td>
									</tr>
									<tr>
									  <td>3</td>
									  <td>Swasthya Profile A</td>
									  <td>2</td>
									  <td>1000</td>
									</tr>
									<tr>
									  <td>4</td>
									  <td>Hair Loss Blood Test</td>
									  <td>2</td>
									  <td>1000</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
        </section>
        <!-- Main content -->
       
      </div>
	   <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- Slimscroll -->
    <script src="plugins/chartjs/Chart.min.js" type="text/javascript"></script>
    <!-- FastClick -->
    <script src='plugins/fastclick/fastclick.min.js'></script>
    <!-- AdminLTE App -->
    <script src="dist/js/app.min.js" type="text/javascript"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js" type="text/javascript"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="<?=base_url();?>user_assets/analytics/plugins/morris/morris.min.js" type="text/javascript"></script>
	  <script>
      $(function () {
        /* ChartJS
         * -------
         * Here we will create a few charts using ChartJS
         */

        //- PIE CHART -
        //-------------
        // Get context with jQuery - using jQuery's .get() method.
        var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
        var pieChart = new Chart(pieChartCanvas);
        var PieData = [
          {
            value: 700,
            color: "#f56954",
            highlight: "#f56954",
            label: ""
          },
          {
            value: 500,
            color: "#00a65a",
            highlight: "#00a65a",
            label: ""
          },
          {
            value: 400,
            color: "#f39c12",
            highlight: "#f39c12",
            label: ""
          },
          {
            value: 600,
            color: "#00c0ef",
            highlight: "#00c0ef",
            label: ""
          },
          {
            value: 300,
            color: "#3c8dbc",
            highlight: "#3c8dbc",
            label: ""
          },
          {
            value: 100,
            color: "#d2d6de",
            highlight: "#d2d6de",
            label: ""
          }
        ];
        var pieOptions = {
          //Boolean - Whether we should show a stroke on each segment
          segmentShowStroke: true,
          //String - The colour of each segment stroke
          segmentStrokeColor: "#fff",
          //Number - The width of each segment stroke
          segmentStrokeWidth: 2,
          //Number - The percentage of the chart that we cut out of the middle
          percentageInnerCutout: 50, // This is 0 for Pie charts
          //Number - Amount of animation steps
          animationSteps: 100,
          //String - Animation easing effect
          animationEasing: "easeOutBounce",
          //Boolean - Whether we animate the rotation of the Doughnut
          animateRotate: true,
          //Boolean - Whether we animate scaling the Doughnut from the centre
          animateScale: false,
          //Boolean - whether to make the chart responsive to window resizing
          responsive: true,
          // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
          maintainAspectRatio: false,
          //String - A legend template
          legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"
        };
        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        pieChart.Doughnut(PieData, pieOptions);

        //-------------
       
      });
    </script>
	 <script type="text/javascript">
      $(function () {
        "use strict";
        //BAR CHART
        var bar = new Morris.Bar({
          element: 'bar-chart',
          resize: true,
          data: [
            {y: '5 March', a: 20},
            
           
          ],
          barColors: ['#00a65a'],
          xkey: 'y',
          ykeys: ['a'],
          labels: ['CPU'],
          hideHover: 'auto'
        });
      });
    </script>