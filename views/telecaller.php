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
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- Custom Tabs -->
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="javascript:void(0);" data-toggle="tab">All Jobs(Tests) <span id="pending_count_1" class="label label-danger">0</span> </a></li>
                        <li><a href="javascript:void(0);" onclick="window.location = '<?= base_url(); ?>Admin/TelecallerPriscription'" data-toggle="tab">Prescription <span class="label label-danger"><?=count($unread);?></span></a></li>
                        <li><a href="javascript:void(0);" onclick="window.location = '<?= base_url(); ?>Admin/TelecallerCallBooking'" data-toggle="tab">On Call Booking</a></li>
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
                            <!-- Main content -->
                            <section class="content">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="box box-primary">
                                            <div class="box-header">

                                                <div class="col-xs-12">

                                                    <div class="col-xs-2 pull-right">
                                                        <div class="form-group">
                                                            <!--<a style="float:right;" href='<?php echo base_url(); ?>job_master/export_csv/1' class="btn btn-primary btn-sm" ><i class="fa fa-download" ></i><strong > Export To CSV</strong></a>-->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- /.box-header -->
                                            <div class="box-body">
                                                <div class="widget">
                                                    
                                                </div>
                                                <div class="tableclass">
                                                    <?php echo form_open("admin/Telecaller", array("method" => "GET", "role" => "form")); ?>
                                                    <table id="example3" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Customer Name</th>
                                                                <th>Mobile</th>
                                                                <th>Test/Package Name</th>
                                                                <th>Date</th>
                                                                <th>Job Status</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td><span style="color:red;">*</span></td>
                                                                <td>
                                                                    <select class="form-control chosen-select" tabindex="-1" data-placeholder="Select Customer" name="user">
                                                                        <option value="">Select Customer</option>
                                                                        <?php foreach ($customer as $cat) { ?>
                                                                            <option value="<?php echo $cat['id']; ?>" <?php
                                                                            if ($customerfk == $cat['id']) {
                                                                                echo "selected";
                                                                            }
                                                                            ?> ><?php echo ucwords($cat['full_name']); ?>(<?php echo $cat['mobile']; ?>)</option>
                                                                                <?php } ?>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="text" placeholder="Mobile" class="form-control" name="p_mobile" value="<?php echo $mobile; ?>"/>
                                                                </td>
                                                                <td>
                                                                    <!--<select class="form-control chosen-select" tabindex="-1" data-placeholder="Select Test/Package" name="test_package">
                                                                        <option value="">Select Test/Package</option>
                                                                        <?php foreach ($test_list as $test) { ?>
                                                                            <option value="<?php echo "t_" . $test['id']; ?>" <?php
                                                                            if ($test_pac == "t_" . $test['id']) {
                                                                                echo "selected";
                                                                            }
                                                                            ?> ><?php echo ucwords($test['test_name']); ?></option>
                                                                                <?php } ?>
                                                                                <?php foreach ($package_list as $package) { ?>
                                                                            <option value="<?php echo "p_" . $package['id']; ?>" <?php
                                                                            if ($test_pac == "p_" . $package['id']) {
                                                                                echo "selected";
                                                                            }
                                                                            ?> ><?php echo ucwords($package['title']); ?></option>
                                                                                <?php } ?>
                                                                    </select>-->
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="date" placeholder="select date" class="form-control datepicker-input" id="date"  value="<?php
                                                                    if (isset($date)) {
                                                                        echo $date;
                                                                    }
                                                                    ?>" />
                                                                </td>
                                                                <td>
                                                               
                                                                </td>
                                                                <td>
                                                                    <input type="submit" name="search" class="btn btn-success" value="Search" />
                                                                </td>
                                                            </tr>
                                                            <?php
                                                            $cnt = 1;
                                                            foreach ($query as $row) {
                                                                ?>

                                                                <tr>
                                                                    <td><?php
                                                                        echo $cnt . " ";
                                                                        if ($row['views'] == '0') {
                                                                            echo '<span class="round round-sm blue"> </span>';
                                                                        }
                                                                        ?> </td>
                                                                    <td><?php echo ucwords($row['full_name']); ?></td>
                                                                    <td><?php echo $row['mobile1']; ?></td>
                                                                    <td><?php
                                                                        $testname = explode(",", $row['testname']);
                                                                        foreach ($testname as $test) {
                                                                            echo ucwords($test) . "<br>";
                                                                        } $packagename = explode(",", $row['packagename']);
                                                                        foreach ($packagename as $package) {
                                                                            echo ucwords($package) . "<br>";
                                                                        }
                                                                        ?></td>
                                                                    <td><?php echo ucwords($row['date']); ?></td>
                                                                    <td><?php
                                                                        if ($row['status'] == "1") {
                                                                            echo "<span class='label label-danger'>Waiting For Approval</span>";
                                                                        } else if ($row['status'] == "2") {
                                                                            echo "<span class='label label-success'>Completed</span>";
                                                                        } else if ($row['status'] == "3") {
                                                                            echo "<span class='label label-danger'>Spam</span>";
                                                                        } else if ($row['status'] == "4") {
                                                                            echo "<span class='label label-danger'>Canceled</span>";
                                                                        } else if ($row['status'] == "5") {
                                                                            echo "<span class='label label-danger'>Deleted</span>";
                                                                        } else if ($row['status'] == "6") {
                                                                            echo "<span class='label label-success'>Sample Collected</span>";
                                                                        } else if ($row['status'] == "3") {
                                                                            echo "<span class='label label-primary'>Procesing</span>";
                                                                        }
                                                                        ?></td>
                                                                    <td>
                                                                        <a  href='<?php echo base_url(); ?>Admin/job_details/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="View Job Details" > <span class="label label-primary"><i class="fa fa-eye"> </i> View Details</span> </a>  
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                                $cnt++;
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                    <?php echo form_close(); ?>
                                                </div>

                                            </div><!-- /.box-body -->
                                        </div><!-- /.box -->
                                    </div><!-- /.col -->
                                </div><!-- /.row -->
                            </section><!-- /.content -->
                            <script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
                            <script  type="text/javascript">

                                jQuery(".chosen-select").chosen({
                                    search_contains: true
                                });
                            </script> 
                            <script type="text/javascript">
                                $(function () {

                                    $('#example3').dataTable({
                                        //"bPaginate": false,
                                        "bLengthChange": false,
                                        "bFilter": false,
                                        "bSort": false,
                                        "bInfo": false,
                                        "bAutoWidth": false
                                    });
                                });
                            </script>
                        </div><!-- /.tab-pane -->
                    </div><!-- /.tab-content -->
                </div><!-- nav-tabs-custom -->
            </div><!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
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
