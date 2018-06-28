<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="<?=base_url();?>dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link href="<?=base_url();?>user_assets/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />

	
	
	
	
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap/3/css/bootstrap.css" />
 
<!-- Include Date Range Picker -->
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
	 <?php echo form_open_multipart('Logistic/Chart_data/', ['method' => 'post', 'id' => 'target',  'enctype' => 'multipart/form-data']); ?>	
	<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Finance Analytics
            
          </h1>
		 
          <ol class="breadcrumb">
				<div id="reportrange" name="rangedate"  style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
					<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
					<span id="dates" onclick="find_data(this.id);"></span>
					<input type="hidden" name="pdates" id="pdates">
				</div>
				  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>


		</ol>
	
		<?php echo form_close(); ?>
        </section>
		
		<section class="content">	
			<div class="row">
				<div class="col-sm-12">
					<div class="paid_due_chart">
						<div class="box box-primary">
							<div class="box-header with-border">
							  <h3 class="box-title">Chart</h3>
							  <div class="box-tools pull-right">
								<button class="btn btn-box-tool" data-widget="collapse"></button>
								<!--<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>-->
							  </div>
							</div>
							<div class="box-body chart-responsive">
							  <div class="chart" id="bar-chart" style="height: 300px;"></div>
							  <div class="col-sm-offset-5 col-sm-6">
							  <div class="show_test_dscrptn_clr" style="background:#00A65A;"></div>
								<div class="paid_due_txtclr">
									<h4>Paid</h4>
								</div>
							
								<div class="show_test_dscrptn_clr" style="background:#F56954;"></div>
								<div class="paid_due_txtclr">
									<h4>Due</h4>
								</div>
							</div>
							</div><!-- /.box-body -->
							  <div id="chart_div"></div>
						</div>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="paid_due_chart_lwrsctn">
						<div class="col-sm-4">
							<label>Paid: <span><?php if($gtotal_paid->grosstotal_paid != " "){ echo $gtotal_paid->grosstotal_paid; }else{ echo '0';}  ?></span></label>
						</div>
						<div class="col-sm-4">
							<label>Total: <span><?php if($gtotal->grosstotal != " "){ echo $gtotal->grosstotal; }else{ echo '0';}  ?></span></label>
						</div>
						<div class="col-sm-4">
							 <div class="btn-group filtrtn_pdf_btn pull-right">
								<button type="button" class="btn btn-info"><i class="fa fa-fw fa-file-pdf-o"></i>Total Collection (PDF)</button>
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
						</div>
					</div>
				
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="rfrl_btn">
						<ul class="inline-block">
							<li><a class="btn btn-block btn-primary btn-lg" href="<?php echo base_url(); ?>logistic/finance_analytics">Referral Doctor Wise</a></li>
							<li><a class="btn btn-block btn-default btn-lg" href="<?php echo base_url(); ?>logistic/rfrl_salesperson_wise">Referral SalesParson Wise</a></li>
							<li><a class="btn btn-block btn-primary btn-lg pull-right" href="#"><i class="fa fa-fw fa-file-excel-o"></i>Export Excel</a></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12 ">
					<div class="box box-primary top_rfrl">
						<div class="rfrl_tbl">
								<div class="box-header tbl_head">
									<h3 class="box-title">Top 5 Referrals :</h3>
									<div class="btn-group filtrtn_pdf_btn pull-right">
										<button type="button" class="btn btn-info">March 2017</button>
										<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
											<span class="caret"></span>
											<span class="sr-only">Toggle Dropdown</span>
										</button>
										<ul class="dropdown-menu" role="menu">
											<li><a href="#">Today</a></li>
											<li><a href="#">Yesterday</a></li>
											<li><a href="#">This Week</a></li>
											<li><a href="#">Last Week</a></li>
										</ul>
									</div>
								</div>
								<div class="box-body table-responsive">
									<table class="table table-hover">
										<tr>
										  <th>No.</th>
										  <th>Name</th>
										  <th>Amount</th>
										</tr>
										<tr>
										  <td>1</td>
										  <td>Name Here</td>
										  <td>1000</td>
										  
										</tr>
										<tr>
										  <td>2</td>
										  <td>Name Here</td>
										  <td>1000</td>
										  
										</tr>
										<tr>
										  <td>3</td>
										  <td>Name Here</td>
										  <td>1000</td>
										 
										</tr>
										<tr>
										  <td>4</td>
										  <td>Name Here</td>
										  <td>1000</td>
										  
										</tr>
									</table>
								</div>
						</div>
						<div class="rfrl_chart_rgt">
							<div class="box-body">
								<canvas id="pieChart" height="250"></canvas>                  
							</div><!-- /.box-body -->
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
    <script src="<?=base_url();?>dist/js/Chart.min.js" type="text/javascript"></script>
    <!-- FastClick -->
    <script src='<?=base_url();?>user_assets/dist/js/fastclick.min.js'></script>
    <!-- AdminLTE App -->
    <script src="<?=base_url();?>dist/js/app.min.js" type="text/javascript"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="<?=base_url();?>dist/js/demo.js" type="text/javascript"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="<?=base_url();?>dist/js/morris.min.js" type="text/javascript"></script>
	

	
	
	  
	  
	  
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
		  
            {y: 'Test', a: 10000, b: 12000},
            {y: '12:001 PM', a: 2000, b: 1000},
            {y: '02:002 PM', a: 5000, b: 4000},
            {y: '03:003 PM', a: 7500, b: 6500},
            {y: '04:004 PM', a: 15000, b: 11000},
            {y: '08:005 PM', a: 15000, b: 6500},
			{y: 'Test6', a: 10000, b: 12000},
            {y: '12:070 PM', a: 2000, b: 1000},
            {y: '02:008 PM', a: 5000, b: 4000},
            {y: '03:009 PM', a: 7500, b: 6500},
            {y: '04:000 PM', a: 15000, b: 11000},
            {y: '08:0098 PM', a: 15000, b: 6500},
			{y: 'Testtr', a: 10000, b: 12000},
            {y: '12:0try0 PM', a: 2000, b: 1000},
            {y: '02:0try0 PM', a: 5000, b: 4000},
            {y: '03:0try0 PM', a: 7500, b: 6500},
            {y: '04:0rty0 PM', a: 15000, b: 11000},
            {y: '08:0rty0 PM', a: 15000, b: 6500},
			{y: 'Testryt', a: 10000, b: 12000},
            {y: '12:0ty0 PM', a: 2000, b: 1000},
            {y: '02:0rty0 PM', a: 5000, b: 4000},
            {y: '03:00try PM', a: 7500, b: 6500},
            {y: '04:0try0 PM', a: 15000, b: 11000},
            {y: '08:00 rtyrtyPM', a: 15000, b: 6500},
			{y: 'Testryt', a: 10000, b: 12000},
            {y: '12:try00 PM', a: 2000, b: 1000},
            {y: '02:0trtryy0 PM', a: 5000, b: 4000},
            {y: '03:00567 PM', a: 7500, b: 6500},
            {y: '04:00try PM', a: 15000, b: 11000},
            {y: '08:00 PstM', a: 15000, b: 6500},
			
           
          ],
          barColors: ['#00a65a', '#f56954'],
          xkey: 'y',
          ykeys: ['a', 'b'],
          labels: ['CPU', 'DISK'],
          hideHover: 'auto'
        });
      });
    </script>
	
	
	<script type="text/javascript">
	var q = $.noConflict();
q(function() {

    var start = moment().subtract(29, 'days');
    var end = moment();
	
    function cb(start, end) {
		//alert(start);
		//alert(end);
        var dates = q('#dates').html(start.format('DD / MM / YYYY') + '-' + end.format('DD / MM / YYYY'));
        var postdates = q('#pdates').val(start.format('DD / MM / YYYY') + '-' + end.format('DD / MM / YYYY'));
        
	}

    q('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
		
    }, cb);

	
	
    cb(start, end);
	
    
});



</script>





<script>

q('#reportrange').on('apply.daterangepicker', function(ev, picker) {
	// start = picker.startDate.format('YYYY-MM-DD');
	// end = picker.endDate.format('YYYY-MM-DD');
	// alert(q("#dates").html());
	alert(q("#dates").html());
	q("#pdates").html();
	 $("#target").submit();
	
	});
	
	google.charts.load('current', {packages: ['corechart', 'bar']});
google.charts.setOnLoadCallback(drawAnnotations);

function drawAnnotations() {
      var data = new google.visualization.DataTable();
      data.addColumn('timeofday', 'Time of Day');
      data.addColumn('number', 'Motivation Level');
      data.addColumn({type: 'string', role: 'annotation'});
      data.addColumn('number', 'Energy Level');
      data.addColumn({type: 'string', role: 'annotation'});

      data.addRows([
	  
         [{v: [8, 0, 0], f: '8 am'},   1, '1',  .25, '.2'],
        [{v: [9, 0, 0], f: '9 am'},   2, '2',   .5, '.5'],
        [{v: [10, 0, 0], f:'10 am'},  3, '3',    1,  '1'],
        [{v: [11, 0, 0], f: '11 am'}, 4, '4', 2.25,  '2'],
        [{v: [12, 0, 0], f: '12 pm'}, 5, '5', 2.25,  '2'],
        [{v: [13, 0, 0], f: '1 pm'},  6, '6',    3,  '3'],
        [{v: [14, 0, 0], f: '2 pm'},  7, '7', 3.25,  '3'],
        [{v: [15, 0, 0], f: '3 pm'},  8, '8',    5,  '5'],
        [{v: [16, 0, 0], f: '4 pm'},  9, '9',  6.5,  '6'],
        [{v: [17, 0, 0], f: '5 pm'}, 10, '10',  10, '10'], 
		
        
      ]);

      var options = {
        title: 'Motivation and Energy Level Throughout the Day',
        annotations: {
          alwaysOutside: true,
          textStyle: {
            fontSize: 14,
            color: '#000',
            auraColor: 'none'
          }
        },
        hAxis: {
          title: 'Time of Day',
          format: 'h:mm a',
          viewWindow: {
            min: [7, 30, 0],
            max: [17, 30, 0]
          }
        },
        vAxis: {
          title: 'Rating (scale of 1-10)'
        }
      };

      var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
      chart.draw(data, options);
    }
</script>



<script>

   

</script>




