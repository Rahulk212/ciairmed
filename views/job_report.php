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
    .chosen-container {width: 100% !important;}
</style>
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Job Report
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li>All Jobs</li>

        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">


                    </div><!-- /.box-header -->

                    <div class="box-body">

                        <div class="widget">
                            <?php if (isset($success) != NULL) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $success['0']; ?>
                                </div>
                            <?php } ?>

                        </div>
                        <!-- Search start-->
                        <?php echo form_open("job_master/job_report", array("method" => "GET", "role" => "form")); ?>
                        <div class="row">
                            <div class="col-sm-3">
                                <input type="text" name="start_date" placeholder="Start date" class="form-control datepicker-input" id="date"  value="<?= $start_date ?>" />
                            </div>

                            <div class="col-sm-3">
                                <input type="text" name="end_date" placeholder="End date" class="form-control datepicker-input" id="date1"  value="<?= $end_date ?>" />
                            </div>

                            <div class="col-sm-3">
                                <select class="form-control chosen-select" data-placeholder="Select Status" tabindex="-1" name="statusid">
                                    <option value="" selected>All Status </option>
                                    <option value="1" <?php
                                    if ($statusid == 1) {
                                        echo "selected";
                                    }
                                    ?>> Waiting For Approval </option>
                                    <option value="6" <?php
                                    if ($statusid == 6) {
                                        echo "selected";
                                    }
                                    ?>> Approved </option>
                                    <option value="7" <?php
                                    if ($statusid == 7) {
                                        echo "selected";
                                    }
                                    ?>> Sample Collected </option>
                                    <option value="8" <?php
                                    if ($statusid == 8) {
                                        echo "selected";
                                    }
                                    ?>> Processing </option>
                                    <option value="2" <?php
                                    if ($statusid == 2) {
                                        echo "selected";
                                    }
                                    ?>> Completed </option>
                                    <option value="0" <?php
                                    if (isset($deleted_selected)) {
                                        echo "selected";
                                    }
                                    ?>> User Deleted </option>

                                </select>
                            </div>

                            <div class="col-sm-3">
                                <input type="submit" name="search" class="btn btn-success" value="Search" />
                            </div>
                        </div>
                        <br>
                        <?php echo form_close(); ?>
                        <!--Search end-->
                        <div class="tableclass">
                            <div class="table-responsive">
                                <table id="example4" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Customer Name</th>
                                            <th>Mobile No.</th>
                                            <th>Test/Package Name</th>
                                            <th>Date</th>
                                            <!--<th>Payment Type</th>-->
                                                                                    <!--<th>Total Amount(Rs.)</th>-->
                                            <th>Payable Amount / Price</th>
                                            <!--<th>Blood Sample Collation Status</th>-->
                                            <th>Job Status</th>
<!--                                            <th>Action</th>-->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $total_amount = 0;
                                        $payable_amount = 0;
                                        $discount_amount = 0;
                                        $wallet_amount = 0;
                                        $total_test = 0;
                                        $total_package = 0;
                                        $cnt = 1;
                                        $collected_amount = 0;
                                        foreach ($query as $row) {
                                            ?>

                                            <tr>
                                                <td><?php
                                                    echo $cnt + $pages . " ";
                                                    if ($row['views'] == '0') {
                                                        echo '<span class="round round-sm blue"> </span>';
                                                    }
                                                    ?> 
                                                </td>

                                                <td><a href="<?php echo base_url(); ?>customer-master/customer-all-details/<?php echo $row['cid']; ?>"><?php echo ucwords($row['full_name']); ?></a><br><?php echo '[' . $row['relation'] . ']'; ?></td>
                                                <td><?= $row['mobile'] ?></td>
                                                <td><?php
                                                    $testname = explode(",", $row['testname']);
                                                    foreach ($testname as $test) {
                                                        $total_test = $total_test + 1;
                                                        echo ucwords($test) . "<br>";
                                                    } $packagename = explode(",", $row['packagename']);
                                                    foreach ($packagename as $package) {
                                                        if(!empty($package)){
                                                        $total_package = $total_package + 1;
                                                        echo ucwords($package) . "<br>";
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                                <td><?php echo ucwords($row['date']); ?></td>

                                                <td><?php
                                                    if ($row['payable_amount'] == "") {
                                                        echo "Rs. 0.00";
                                                    } else {
                                                        echo " Rs." . number_format((float) $row['payable_amount'], 2, '.', '');
                                                    }
                                                    ?> /<?php echo " Rs." . number_format((float) $row["price"], 2, '.', ''); ?>
                                                    <?php
                                                    if ($row["cut_from_wallet"] != 0) {
                                                        $wallet_amount = $wallet_amount + $row["cut_from_wallet"];
                                                        echo "<br><small>(Rs." . $row["cut_from_wallet"] . " Debited from wallet)</small>";
                                                    }
                                                    if ($row["discount"] != '' && $row["discount"] != '0') {
                                                        $dprice1 = $row["discount"] * $row["price"] / 100;
                                                        $discount_amount = $discount_amount + $dprice1;
                                                        echo "<br><small>( Rs." . number_format((float) $dprice1, 2, '.', '') . " Discount)</small>";
                                                    }
                                                    ?>
                                                </td>

                                                <td>
                                                    <?php
                                                    if ($row['status'] == 1) {
                                                        echo "<span class='label label-danger'>Waiting For Approval</span>";
                                                    }
                                                    ?>
                                                    <?php
                                                    if ($row['status'] == 6) {
                                                        echo "<span class='label label-warning'>Approved</span>";
                                                    }
                                                    ?>
                                                    <?php
                                                    if ($row['status'] == 7) {
                                                        echo "<span class='label label-warning'>Sample Collected</span>";
                                                    }
                                                    ?>
                                                    <?php
                                                    if ($row['status'] == 8) {
                                                        echo "<span class='label label-warning'>Processing</span>";
                                                    }
                                                    ?>
                                                    <?php
                                                    if ($row['status'] == 2) {
                                                        echo "<span class='label label-success'>Completed</span>";
                                                    }
                                                    ?>
                                                    <?php
                                                    if ($row['status'] == 0) {
                                                        echo "<span class='label label-danger'>User Deleted</span>";
                                                    }
                                                    ?>
                                                    <br><?php
                                                    if ($row['emergency'] == '1') {
                                                        echo "<span class='label label-danger'>Emergency</span>";
                                                    }
                                                    ?>
                                                    <?php /* if($row['status']=="1" && $row['sample_collection']=="0" ){ echo "<span class='label label-danger'>Pending</span>"; }else if($row['status']=="2"){ echo "<span class='label label-success'>Completed</span>"; }else if($row['status']=="3"){ echo "<span class='label label-danger'>Spam</span>"; }else if($row['sample_collection']==1){ echo "<span class='label label-warning'>Processing</span>";} 
                                                     */
                                                    ?> 
                                                </td>
                                            </tr>
                                            <?php
                                            $total_amount = $total_amount + $row["price"];
                                            $payable_amount = $payable_amount + $row['payable_amount'];
                                            $collected_amount = $collected_amount + $row["collected_amount"];
                                            $cnt++;
                                        }if (empty($query)) {
                                            ?>
                                            <tr>
                                                <td colspan="8">Select date.</td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <div class="col-sm-6"> 
                                    <?php if ($start_date != '' || $end_date != '' || $statusid != '' || $deleted_selected != '') { ?>
                                        <table id="example4" class="table table-bordered table-striped">
                                            <h2>Report From <?= $start_date ?> To <?= $end_date ?></h2>
                                            <tr>
                                                <td><b>Collected Amount </b></td>
                                                <td><?php echo " Rs." . number_format((float) $collected_amount, 2, '.', ''); ?></td>
                                            </tr>
                                            <tr>
                                                <td><b>Payable Amount-(Due Amount) </b></td>
                                                <td><?php echo " Rs." . number_format((float) $payable_amount, 2, '.', ''); ?></td>
                                            </tr>
                                            <tr>
                                                <td><b>Total Discount Amount</b></td>
                                                <td><?php echo " Rs." . number_format((float) $discount_amount, 2, '.', ''); ?></td>
                                            </tr>
                                            <tr>
                                                <td><b>Total Debited from Wallet</b></td>
                                                <td><?php echo " Rs." . number_format((float) $wallet_amount, 2, '.', ''); ?></td>
                                            </tr>
                                            <tr>
                                                <td><b>Total Amount-(Total Revenue)</b></td>
                                                <td><b><?php echo " Rs." . number_format((float) $total_amount, 2, '.', ''); ?></b></td>
                                            </tr>
                                            <tr>
                                                <td><b>Total Sample</b></td>
                                                <td><b><?php echo $cnt - 1; ?></b></td>
                                            </tr>
                                            <tr>
                                                <td><b>Total Tests</b></td>
                                                <td><b><?php echo $total_test; ?></b></td>
                                            </tr>
                                            <tr>
                                                <td><b>Total Packages</b></td>
                                                <td><b><?php echo $total_package; ?></b></td>
                                            </tr>
                                        </table>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div style="text-align:right;" class="box-tools">
                            <ul class="pagination pagination-sm no-margin pull-right">
                                <?php echo $links; ?>
                            </ul>
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