<style>
    .admin_job_dtl_img {border: 4px solid #8d8d8d; height: 160px; max-width: 160px; min-width: 80px; width: 180px;}
</style>
<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<link href="<?php echo base_url(); ?>plugins/timepick/css/timepicki.css" rel="stylesheet">
<section class="content-header">
    <h1>
        Phlebotomy Details (<?php echo date("d-M-Y", strtotime($view_data[0]['start_date'])); ?>)
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <span style="margin-right:30px; font-size:20px;"><?php echo $query[0]['date'] . "  "; ?>  </span>
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li class="active">Phlebotomy Details</li>
    </ol>
</section>
<!-- Add fancyBox main JS and CSS files -->
<script type="text/javascript" src="<?php echo base_url(); ?>source/jquery.fancybox.pack.js?v=2.1.5"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>source/jquery.fancybox.css?v=2.1.5" media="screen" />
<!-- Add Media helper (this is optional) -->
<script type="text/javascript" src="<?php echo base_url(); ?>source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>
<!--Nishit code start-->
<script type="text/javascript">
    var jq = $.noConflict();
    jq(document).ready(function () {
        jq('.fancybox').fancybox();
    });
</script>
<section class="content">
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header">
                    <!-- form start -->
                    <h3 class="box-title">Details</h3>
                    <!--Add job start-->
                    <hr>
                    <!--Add job end-->
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label for="exampleInputFile">Phlebotomy Name :</label> <?= ucfirst($view_data[0]['name']); ?>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputFile">Punch In Date :</label> <?php echo date("d-M-Y", strtotime($view_data[0]['start_date'])); ?>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputFile">Punch In Time :</label> <?php echo date("g:i A", strtotime($view_data[0]['start_time'])); ?>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputFile">Punch Out Date :</label> <?php
                        if (!empty($view_data[0]['stop_date'])) {
                            echo date("d-M-Y", strtotime($view_data[0]['stop_date']));
                        } else {
                            echo "-";
                        }
                        ?>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputFile">Punch Out Time :</label> <?php
                        if (!empty($view_data[0]['stop_time'])) {
                            echo date("g:i A", strtotime($view_data[0]['stop_time']));
                        } else {
                            echo "-";
                        }
                        ?>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputFile">Time :</label> <?php
                        if ($view_data[0]['time'] != '00:00:00') {
                            echo ucfirst($view_data[0]['time']);
                        } else {
                            echo "-";
                        }
                        ?>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputFile">Travel Distance :</label> <?= ucfirst($view_data[0]['distance']); ?>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputFile">Address :</label> <?= ucfirst($view_data[0]['address']); ?>
                    </div>

                    <hr>
                    <h3><?php echo date("d-M-Y", strtotime($view_data[0]['start_date'])); ?>'s visit</h3>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Customer Name</th>
                                <th>Order Id</th>
                                <th>Check In Time</th>
                                <th>Check Out Time</th>
                                <th>Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $cnt=1; foreach($checkin_data as $key){  ?>
                            <tr>
                                <td><?=$cnt;?></td>
                                <td><?=ucfirst($key["full_name"])?></td>
                                <td><a href="<?=base_url();?>job-master/job-details/<?=ucfirst($key["jid"])?>" target="_blank"><?=ucfirst($key["order_id"])?></a></td>
                                <td><?=ucfirst($key["checkin_time"])?></td>
                                <td><?=ucfirst($key["checkout_time"])?></td>
                                <td><?=ucfirst($key["note"])?></td>
                            </tr>
                            <?php $cnt++; } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header">
                    <!-- form start -->
                    <h3 class="box-title">Map</h3>
                    <hr>
                </div>
                <div class="box-body">
                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
                    <script src="//maps.googleapis.com/maps/api/js?key=AIzaSyAhkj8R0P3As0LqOmSw_awCYE-kzpAfA10&sensor=false"
                    type="text/javascript"></script>
                    <div id="map" style="width: 100%; height: 632px;"></div>

                    <script type="text/javascript">

    var locations = [
        ['Punch In', <?= $view_data[0]['latitude'] ?>, <?= $view_data[0]['longitude'] ?>,<?= ucfirst($view_data[0]['mobile']) ?>, '<?= ucfirst($view_data[0]['name']) ?>'],
<?php if (!empty($view_data[0]['stop_date'])) { ?>
            ['Punch Out',<?= $view_data[0]['outlatitude'] ?>, <?= $view_data[0]['outlongitude'] ?>,<?= ucfirst($view_data[0]['mobile']) ?>, '<?= ucfirst($view_data[0]['name']) ?>'],
<?php } ?>
    ];

    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 10,
        center: new google.maps.LatLng(locations[0][1], locations[0][2]),
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var infowindow = new google.maps.InfoWindow();

    var marker, i;
    console.log(locations);
    for (i = 0; i < locations.length; i++) {
        marker = new google.maps.Marker({

            //if(i != 0){
            //	  icon: '<?php echo base_url(); ?>assets/location_red.png',
            /// }else{
            icon: locations[i][10],
            //}
            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
            map: map
        });

        google.maps.event.addListener(marker, 'click', (function (marker, i) {
            return function () {
                //    if(locations[i][4] == ''){
                infowindow.setContent("<div style=''>" + "<b>" + locations[i][0] + " Point</b><br/>" + "<span><b>Name:</b> " + locations[i][4] + "</span><br/>" + "<b>Mobile No: </b>" + locations[i][3] + "<br/>" + "</div>");
                //            }else{
                //                infowindow.setContent("<div style=''>"+"<b>"+locations[i][5]+" Point</b><br/>"+"<span><b>Name:</b> "+locations[i][0]+"</span><br/>"+"<b>Mobile No: </b>"+locations[i][3]+"<br/>"+"<b>Check In:</b> "+locations[i][4]+"<br/>"+"<b>Check Out:</b> "+locations[i][5]+"<br/>"+"</div>");
                //            }

                infowindow.open(map, marker);
            }
        })(marker, i));
    }
                    </script>
                </div>
            </div>
        </div>

        <!-- Modal -->
    </div>

</section>
</div>
</div>
<script src="<?php echo base_url(); ?>plugins/timepick/js/timepicki.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>

