<style>
    .round {
        display: inline-block;
        height: 30px;
        width: 30px;
        line-height: 30px;
        -moz-border-radius: 15px;
        border-radius: 15px;
        background-color: #222;    
        color: #FFF;
        text-align: center;  
    }
    .round.round-sm {
        height: 10px;
        width: 10px;
        line-height: 10px;
        -moz-border-radius: 10px;
        border-radius: 10px;
        font-size: 0.7em;
    }
    .round.blue {
        background-color: #3EA6CE;
    }
</style>
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="http://cdn.rawgit.com/davidstutz/bootstrap-multiselect/master/dist/css/bootstrap-multiselect.css"
      rel="stylesheet" type="text/css" />
<script src="http://cdn.rawgit.com/davidstutz/bootstrap-multiselect/master/dist/js/bootstrap-multiselect.js"
type="text/javascript"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />

<!-- Page Heading -->
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>

<!-- Add jQuery library -->
<script type="text/javascript" src="<?php echo base_url(); ?>lib/jquery-1.10.2.min.js"></script>

<!-- Add mousewheel plugin (this is optional) -->
<script type="text/javascript" src="<?php echo base_url(); ?>lib/jquery.mousewheel.pack.js?v=3.1.3"></script>

<!-- Add fancyBox main JS and CSS files -->
<script type="text/javascript" src="<?php echo base_url(); ?>source/jquery.fancybox.pack.js?v=2.1.5"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>source/jquery.fancybox.css?v=2.1.5" media="screen" />

<!-- Add Button helper (this is optional) -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>source/helpers/jquery.fancybox-buttons.css?v=1.0.5" />
<script type="text/javascript" src="<?php echo base_url(); ?>source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>

<!-- Add Thumbnail helper (this is optional) -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" />
<script type="text/javascript" src="<?php echo base_url(); ?>source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>

<!-- Add Media helper (this is optional) -->
<script type="text/javascript" src="<?php echo base_url(); ?>source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>
<script type="text/javascript">
    var jq = $.noConflict();
    jq(document).ready(function () {
        /*
         *  Simple image gallery. Uses default settings
         */

        jq(".fancybox-effects-a").fancybox({
            //'overlayShow': true,
            //'hideOnContentClick': false,
            'type': 'iframe',
            helpers: {
                title: {
                    type: 'outside'
                },
                overlay: {
                    speedOut: 0
                }
            }
        });
    });
</script>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- Custom Tabs -->
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li><a href="javascript:void(0);" onclick="window.location = '<?= base_url(); ?>Admin/Telecaller'" data-toggle="tab">All Jobs(Tests) <span id="pending_count_1" class="label label-danger">0</span> </a></li>
                        <li class="active"><a href="javascript:void(0);" onclick="window.location = '<?= base_url(); ?>Admin/TelecallerPriscription'" data-toggle="tab">Prescription <span class="label label-danger"><?= count($unread); ?></span></a></li>
                        <li><a href="javascript:void(0);" onclick="window.location = '<?= base_url(); ?>Admin/TelecallerCallBooking'" data-toggle="tab">On Call Booking </a></li>
                    </ul>
					</div>
					<?php if (isset($success) != NULL) { ?>
                                                        <div class="alert alert-success alert-dismissable">
                                                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                                            <?php echo $success['0']; ?>
                                                        </div>
                                                    <?php } ?>
					</div></div>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <section class="content">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="box box-primary">
                                            <div class="box-header">	 
                                            </div><!-- /.box-header -->
                                            <div class="box-body">
                                                <div class="widget">
                                                    

                                                </div>
                                                <div class="tableclass">
                                                    <form role="form" action="<?php echo base_url(); ?>Admin/TelecallerPriscription" method="get" enctype="multipart/form-data">
                                                        <table id="example2" class="table table-bordered table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th>No</th>
                                                                    <th>Customer Name</th>
                                                                    <th>Mobile</th>
                                                                    <th>Date</th>
                                                                    <th>Pic</th>
                                                                    <th> Status</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td><span style="color:red;">*</span></td>
                                                                    <td><select class="form-control chosen-select" tabindex="-1"  data-placeholder="Select Customer" name="user">
                                                                            <option value="">Select Customer</option>
                                                                            <?php foreach ($customer as $cat) { ?>
                                                                                <option value="<?php echo $cat['id']; ?>" <?php
                                                                                if ($customerfk == $cat['id']) {
                                                                                    echo "selected";
                                                                                }
                                                                                ?> ><?php echo ucwords($cat['full_name']); ?>(<?php echo ucwords($cat['mobile']); ?>)</option>
                                                                                    <?php } ?>
                                                                        </select></td>
                                                                    <td><input type="text" placeholder="Mobile" class="form-control" name="mobile" value="<?php
                                                                        if (isset($date)) {
                                                                            echo $mobile;
                                                                        }
                                                                        ?>"/></td>
                                                                    <td><input type="text" name="date" placeholder="Select Date" class="form-control" id="date" value="<?php
                                                                        if (isset($date)) {
                                                                            echo $date;
                                                                        }
                                                                        ?>" /></td> 
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td><input type="submit" name="search" class="btn btn-success" value="Search" /></td>
                                                                </tr>
                                                                <?php
                                                                $cnt = 1;
                                                                foreach ($query as $row) {
                                                                    ?>

                                                                    <tr>
                                                                        <td><?php
                                                                            echo $cnt;
                                                                            if ($row['is_read'] == '0') {
                                                                                echo ' <span class="round round-sm blue"> </span>';
                                                                            }
                                                                            ?></td>
                                                                        <td><?php echo ucwords($row['full_name']); ?> </td>
                                                                         <!--<td><?php echo ucwords($row['description']); ?></td>-->
                                                                        <td><?php echo $row['mobile']; ?></td>
                                                                        <td><?php echo ucwords($row['created_date']); ?></td>
                                                                        <td><a class="fancybox-effects-a" title="<?= base_url(); ?>upload/<?= $row['image']; ?>" href="<?= base_url(); ?>upload/<?= $row['image']; ?>">
                                                                                <?php $check_file_type = explode(".",$row['image']); $f_cnt=count($check_file_type); $file_type = $check_file_type[$f_cnt-1]; 
                                                                                if(strtoupper($file_type)=="PDF"){
                                                                                ?>
                                                                                <i class="fa fa-file-pdf-o" style="font-size:40px;"></i>
                                                                                <?php }else{ ?>
                                                                                <img style="height:50px;width:auto;"  src="<?= base_url(); ?>upload/<?= $row['image']; ?>"/>
                                                                                <?php } ?>
                                                                            </a>
                                                                        </td>
                                                                        <td>
                                                                            <?php if ($row['status'] == "2") { ?>
                                                                                <span class="label label-success">Completed</span>   
        <?php } else { ?>
                                                                                <span class="label label-danger"> Waiting For Approval</span>  
        <?php } ?>										
                                                                        </td>
                                                                        <td>


                                                                            <a  href='<?php echo base_url(); ?>Admin/prescription_details/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="View Job Details" > <span class="label label-primary"><i class="fa fa-eye"> </i> View Details</span> </a>
        <?php /* if ($row['cid'] == "") { ?>  
          <a href="" data-toggle="modal" data-target="#myModal"><span class="label label-success"><i class="fa fa-eye"> </i>Create Account</span></a>
          <?php } */ ?> 


                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                    $cnt++;
                                                                }
                                                                ?>

                                                            </tbody>
                                                        </table>
                                                    </form>
                                                </div>

                                            </div><!-- /.box-body -->
                                        </div><!-- /.box -->
                                    </div><!-- /.col -->
                                </div><!-- /.row -->
                            </section><!-- /.content -->
                        </div><!-- /.content-wrapper -->
                        <script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
                        <script  type="text/javascript">

                            jQuery(".chosen-select").chosen({
                                search_contains: true
                            });
                            //  $(".chosen-select-deselect").chosen({ allow_single_deselect: true });
                            // $("#cid").chosen('refresh');

                        </script> 
                        <script>
                            function validation() {
                                var all = 0;
                                $('#fname').each(function () {
                                    var desc = $(this).val();
                                    $('#error_fname').html("");
                                    if (desc != '') {
                                        $('#error_fname').html(" ");
                                    } else {
                                        all = 1;
                                        $('#error_fname').html("Fullname is required.");
                                    }
                                });
                                $('#custemail').each(function () {
                                    var desc = $(this).val();
                                    $('#error_email').html("");
                                    if (desc != '') {
                                        $('#error_email').html(" ");
                                    } else {
                                        all = 1;
                                        $('#error_email').html("Email is required.");
                                    }
                                });
                                $('#password').each(function () {
                                    var desc = $(this).val();
                                    $('#error_password').html("");
                                    if (desc != '') {
                                        $('#error_password').html(" ");
                                    } else {
                                        all = 1;
                                        $('#error_password').html("Password is required.");
                                    }
                                });
                                $('#mobile').each(function () {
                                    var desc = $(this).val();
                                    $('#error_mobile').html("");
                                    if (desc != '') {
                                        $('#error_mobile').html(" ");
                                    } else {
                                        all = 1;
                                        $('#error_mobile').html("Mobile is required.");
                                    }
                                });
                                if (all != '1') {
                                    $("#addcust").submit();
                                } else {
                                    return false;
                                }

                            }

                        </script>



    <script type="text/javascript">
        function get_pending_count2() {

            $.ajax({
                url: "<?php echo base_url(); ?>job_master/pending_count/",
                error: function (jqXHR, error, code) {
                    // alert("not show");
                },
                success: function (data) {
                    //     console.log("data"+data);
                    //var jsonparse = JSON.Parse(data);
                    var obj = $.parseJSON(data);
                    console.log(obj.job_count);
                    //document.getElementById('pending_count').innerHTML = "";
                    //document.getElementById('pending_count').innerHTML = obj.job_count;
                    document.getElementById('pending_count_1').innerHTML = obj.job_count;
                    document.getElementById('pending_count_2').innerHTML = obj.package_count;
                    document.getElementById('test_package_count').innerHTML = obj.all_inquiry;
                    if (obj.tickepanding != '0') {
                        document.getElementById('supportpanding').innerHTML = obj.tickepanding;
                    }
                    if (obj.job_count != '0') {
                        document.getElementById('pending_count').innerHTML = obj.job_count;
                    }
                    if (obj.contact_us_count != '0') {
                        document.getElementById('contact_us').innerHTML = obj.contact_us_count;
                    }

                }
            });

        }

        get_pending_count2();
    </script>







                    </div><!-- /.tab-pane -->
                </div><!-- /.tab-content -->
            </div><!-- nav-tabs-custom -->
        </div><!-- /.col -->
    </div>
    <!-- /.row -->
    </section>
