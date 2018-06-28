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

    span.multiselect-native-select {
        position: relative
    }
    span.multiselect-native-select select {
        border: 0!important;
        clip: rect(0 0 0 0)!important;
        height: 1px!important;
        margin: -1px -1px -1px -3px!important;
        overflow: hidden!important;
        padding: 0!important;
        position: absolute!important;
        width: 1px!important;
        left: 50%;
        top: 30px
    }
    .multiselect-container {
        position: absolute;
        list-style-type: none;
        margin: 0;
        padding: 0
    }
    .multiselect-container .input-group {
        margin: 5px
    }
    .multiselect-container>li {
        padding: 0
    }
    .multiselect-container>li>a.multiselect-all label {
        font-weight: 700
    }
    .multiselect-container>li.multiselect-group label {
        margin: 0;
        padding: 3px 20px 3px 20px;
        height: 100%;
        font-weight: 700
    }
    .multiselect-container>li.multiselect-group-clickable label {
        cursor: pointer
    }
    .multiselect-container>li>a {
        padding: 0
    }
    .multiselect-container>li>a>label {
        margin: 0;
        height: 100%;
        cursor: pointer;
        font-weight: 400;
        padding: 3px 0 3px 30px
    }
    .multiselect-container>li>a>label.radio, .multiselect-container>li>a>label.checkbox {
        margin: 0
    }
    .multiselect-container>li>a>label>input[type=checkbox] {
        margin-bottom: 5px
    }
    .btn-group>.btn-group:nth-child(2)>.multiselect.btn {
        border-top-left-radius: 4px;
        border-bottom-left-radius: 4px
    }
    .form-inline .multiselect-container label.checkbox, .form-inline .multiselect-container label.radio {
        padding: 3px 20px 3px 40px
    }
    .form-inline .multiselect-container li a label.checkbox input[type=checkbox], .form-inline .multiselect-container li a label.radio input[type=radio] {
        margin-left: -20px;
        margin-right: 0
    }
    /* pending_job_detail responsive table */
    .pending_job_list_tbl {width: 100%; float: left;}
    .pending_job_list_tbl table {width: 100%; border-collapse: collapse; float: left;}
    .pending_job_list_tbl th {background-color: #e5e5e5; color: #3e3e3e; font-size: 16px; font-weight: 600; text-align: center; vertical-align: middle; border: 1px solid #b1b1b1;}
    .pending_job_list_tbl td, th {padding:2px 6px; border: 1px solid #ccc; text-align: left;}
    .pending_job_list_tbl td {padding: 4px 4px; font-size: 13px; color: #505050;} 
    @media (max-width: 980px) {
        .pending_job_list_tbl table, .pending_job_list_tbl thead, .pending_job_list_tbl tbody, .pending_job_list_tbl th, .pending_job_list_tbl td, .pending_job_list_tbl tr {display: block;}
        .pending_job_list_tbl thead tr {position: absolute; top: -9999px; left: -9999px;}
        .pending_job_list_tbl tr {border: 1px solid #ccc !important;}
        .pending_job_list_tbl td {border: none; border-bottom: 1px solid #eee; position: relative; padding-left: 60%; text-align: left;}
        .pending_job_list_tbl td:before {position: absolute; top: 6px; left: 6px; width: 45%; padding-right: 10px; white-space: nowrap;}
        .pending_job_list_tbl tr{margin-bottom:15px;}
        .table-responsive.pending_job_list_tbl{border:none !important;}

        .pending_job_list_tbl td:nth-of-type(1):before {content: "No";}
        .pending_job_list_tbl td:nth-of-type(2):before {content: "Reg No";}
        .pending_job_list_tbl td:nth-of-type(3):before {content: "Customer Name";}
        .pending_job_list_tbl td:nth-of-type(4):before {content: "Doctor";}
        .pending_job_list_tbl td:nth-of-type(5):before {content: "Test/Package Name";}
        .pending_job_list_tbl td:nth-of-type(6):before {content: "Date";}
        .pending_job_list_tbl td:nth-of-type(7):before {content: "Payable Amount / Price";}
        .pending_job_list_tbl td:nth-of-type(8):before {content: "Job Status";}
        .pending_job_list_tbl td:nth-of-type(9):before {content: "Action";}
    }
    /* End pending_job_detail responsive table */
    .box.box-primary button i{margin-right:5px;}
    .morecontent span {
        display: none;
    }
    .morelink {
        display: block;
    }
</style>
<style>
    .chosen-container {
        display: inline-block;
        font-size: 14px;
        position: relative;
        vertical-align: middle;
        width: 100%;
    }
    .full_bg{background: rgba(0,0,0,0.3); width:100%; height:100%; float:left; padding:350px; position:fixed; z-index:9; top:0; bottom:0;}
    .full_bg .loader img{width:70px; height:70px;}
</style>
<div class="full_bg" style="display:none;" id="loader_div">
    <div class="loader">
        <center><img src="http://static.heart.org/riskcalc/app/assets/img/loading.gif"></center>
    </div>
</div>
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            All Jobs
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
                <div class="box box-primary" style="min-height: 500px;">
                    <!------ <div class="box-header">
                         <div class="col-xs-12">
                             <div class="col-xs-2 pull-right">
                                 <div class="form-group">
                    <?php if ($login_data['type'] != 5) { ?>
                                                                                                                                                                         
                                                                                                                                                                                                                 <a style="float:right; display:none;" href='<?php echo base_url(); ?>job_master/export_csv/1' class="btn btn-primary btn-sm" ><i class="fa fa-download" ></i><strong > Export To CSV</strong></a>
                    <?php } ?>
                                 </div>
                             </div>
                         </div>
                     </div>------><!-- /.box-header -->
                    <div class="box-body">
                        <div class="widget">
                            <?php if (isset($success) != NULL) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $success['0']; ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="box-body">
                            <div class="widget">
                                <?php echo form_open("job-master/pending-list", array("method" => "GET", "role" => "form")); ?>
                                <div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;">
                                        <input type="text" placeholder="Ref No." class="form-control" name="p_ref" value="<?php echo $p_ref; ?>"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;">
                                        <input type="text" placeholder="Order Id" class="form-control" name="p_oid" value="<?php echo $p_oid; ?>"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;">
                                        <input type="text" name="mobile" class="form-control" style="" placeholder="Mobile No." id="date"  value="<?php
                                        if (isset($mobile)) {
                                            echo $mobile;
                                        }
                                        ?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;">
                                        <select name="user" id="customer_list" class="chosen">
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;">
                                        <input type="text" name="date" placeholder="Start date" readonly="" class="form-control datepicker-input" id="date"  value="<?php
                                        if (isset($date2)) {
                                            echo $date2;
                                        }else{ echo date("d/m/Y"); }
                                        ?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;">
                                        <input type="text" name="end_date" readonly="" placeholder="End date" class="form-control datepicker-input" id="date"  value="<?php
                                        if (isset($end_date)) {
                                            echo $end_date;
                                        }else{ echo date("d/m/Y"); }
                                        ?>" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;">
                                        <select name="referral_by" id="referral_by" class="chosen">
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;">
                                        <input type="text" placeholder="Test/Package" class="form-control" name="test_package" value="<?php echo $test_pack; ?>"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;">
                                        <select class="form-control chosen-select" data-placeholder="Select Status" tabindex="-1" name="status">
                                            <option value="" <?php
                                            if ($statusid == '') {
                                                echo "selected";
                                            }
                                            ?>>All Status </option>
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
                                </div>
                                <?php if ($login_data['type'] == 1 || $login_data['type'] == 2) { ?>
                                    <div class="form-group">
                                        <div class="col-sm-3" style="margin-bottom:10px;">

                                            <select class="form-control chosen-select" onchange="get_branch(this.value);" data-placeholder="Select Test city" tabindex="-1" name="city">
                                                <option value="">--All city--</option>
                                                <?php foreach ($test_cities as $bkey) { ?>
                                                    <option value="<?= $bkey["id"] ?>" <?php
                                                    if ($tcity == $bkey["id"]) {
                                                        echo "selected";
                                                    }
                                                    ?>><?= $bkey["name"] ?></option>
                                                        <?php } ?>
                                            </select>

                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;">
                                        <select class="multiselect-ui form-control" id="branch" title="Select Branch" multiple="multiple" name="branch[]">
                                            <?php
                                            foreach ($branchlist as $bkey) {
                                                if (isset($tcity) && $tcity == $bkey["city"]) {
                                                    ?>
                                                    <option value="<?= $bkey["id"] ?>" <?php
                                                    if (in_array($bkey["id"], $branch)) {
                                                        echo "selected";
                                                    }
                                                    ?>><?= $bkey["branch_name"] ?></option>
                                                        <?php } else {
                                                            ?><option value="<?= $bkey["id"] ?>" <?php
                                                    if (in_array($bkey["id"], $branch)) {
                                                        echo "selected";
                                                    }
                                                    ?>><?= $bkey["branch_name"] ?></option><?php
                                                        }
                                                    }
                                                    ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="widget">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div class="col-md-3 col-sm-5 pdng_0" style="margin-bottom:10px;">
                                            <label>Payment</label>
                                            <label class="radio-inline"><input type="radio" value="due" name="payment" <?php
                                                if ($payment2 == 'due') {
                                                    echo "checked";
                                                }
                                                ?>>Due</label>
                                            <label class="radio-inline"><input type="radio" value="paid" name="payment" <?php
                                                if ($payment2 == 'paid') {
                                                    echo "checked";
                                                }
                                                ?>>Paid</label>
                                            <label class="radio-inline"><input type="radio" value="all" name="payment" <?php
                                                if ($payment2 == 'all') {
                                                    echo "checked";
                                                }
                                                ?><?php
                                                if ($payment2 == '') {
                                                    echo "checked";
                                                }
                                                ?>>All</label> 
                                        </div>
                                    </div>

                                    <div class="form-group pull-right">
                                        <div class="col-sm-12" style="margin-bottom:10px;">
                                            <button type="submit" name="search" class="btn btn-sm btn-success" value="Search" /><i class="fa fa-search"></i>Search</button>
                                            <button type="button" class="btn btn-sm btn-primary" onclick="window.location = '<?= base_url(); ?>job-master/pending-list'" value="Reset"/><i class="fa fa-refresh"></i>Reset</button>
                                            <?php if ($_SERVER['QUERY_STRING'] != '') { ?>
                                                <a style="float:right;margin-left:3px" href='<?php echo base_url(); ?>job_master/export_csv?<?= $_SERVER['QUERY_STRING'] ?>' class="btn btn-sm btn-primary" ><i class="fa fa-download" ></i><strong > Export To CSV</strong></a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                        <script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
                        <script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap-multiselect.js"></script>
                        <Script>
                                                $(function () {
                                                    $('.multiselect-ui').multiselect({
                                                        includeSelectAllOption: true,
                                                        nonSelectedText: 'Select Branch'
                                                    });
                                                });
                                                $(function () {
                                                    $('.chosen').chosen();
                                                });
                                                jQuery(".chosen-select").chosen({
                                                    search_contains: true
                                                });
                        </script>
                        <div class="tableclass">

                            <div class="table-responsive pending_job_list_tbl">
                                <table id="example4" class="table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Reg. No</th>
                                            <th>Order Id</th>
                                            <th>Customer Name</th>
                                            <th>Doctor</th>
                                            <th>Test/Package Name</th>
                                            <th>Date</th>
                                            <!--<th>Payment Type</th>-->
                                            <!--<th>Total Amount(Rs.)</th>-->
                                            <th>Payable Amount / Price</th>
                                            <!--<th>Blood Sample Collation Status</th>-->
                                            <th>Job Status</th>
                                            <th width="112px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $cnt = 1;
                                        $test_ids = array();
                                        foreach ($query as $row) {
                                            $test_ids[] = $row['id'];
                                            ?>
                                            <?php
                                            $style = '';
                                            if ($row['emergency'] == '1') {
                                                $style = "background-color:red;color:white;";
                                            }
                                            if ($row['status'] == 2) {
                                                $style = "background-color:#008D4C;color:white;";
                                            }
                                            ?>
                                            <tr>
                                                <td style="<?= $style; ?>"><?php
                                                    echo $cnt + $pages . " ";
                                                    if ($row['views'] == '0') {
                                                        echo '<span class="round round-sm blue"> </span>';
                                                    }
                                                    ?> 
                                                </td>
                                                <td><?= $row["id"]; ?></td>
                                                <td style="color:#d73925;"><?= $row["order_id"]; ?></td>
                                                <?php
                                                if ($row['relation'] == '') {
                                                    $row['relation'] = 'Self';
                                                }
                                                ?>
                                                <td>
                                                    <?php if ($row['relation'] == 'Self') { ?>
                                                        <span style="color:#d73925;"><?php echo ucwords($row['full_name']); ?></span>
                                                        &nbsp;<?= $row['mobile'] ?>
                                                    <?php } else { ?>
                                                        <span style="color:#d73925;"><?= ucfirst($row['relation']); ?></span>
                                                        &nbsp;<?= $row['rphone'] ?>
                                                        <br>
                                                        <span title="Account holder">AC-</span><a style="margin-left:0;" href="<?php echo base_url(); ?>customer-master/customer-all-details/<?php echo $row['cid']; ?>"><?php echo ucwords($row['full_name']); ?></a>&nbsp;<?= $row['mobile'] ?>
                                                    <?php } ?>

                                                    <br>
                                                    <?php
                                                    if ($row["test_city"] == 1) {
                                                        echo "Ahmedabad";
                                                    } else if ($row["test_city"] == 2) {
                                                        echo "Surat";
                                                    } else if ($row["test_city"] == 3) {
                                                        echo "Vadodara";
                                                    } else if ($row["test_city"] == 4) {
                                                        echo "Gurgaon";
                                                    } else if ($row["test_city"] == 5) {
                                                        echo "Delhi";
                                                    } else if ($row["test_city"] == 6) {
                                                        echo "Gandhinagar";
                                                    }
                                                    foreach ($branchlist as $branch) {
                                                        if ($row['branch_fk'] == $branch['id']) {
                                                            echo '[' . $branch['branch_name'] . ']';
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                                <td><?php if (!empty($row["doctor"])) { ?><?= ucfirst($row["doctor_name"]) . " - " . $row["doctor_mobile"]; ?> <?php
                                                    } else {
                                                        echo "-";
                                                    }
                                                    ?></td>
                                                <td><?php
                                                    //$testname = explode(",", $row['testname']);
                                                    foreach ($row["job_test_list"] as $test) {
                                                        echo "<span id='test_" . $row["id"] . "_" . $test['test_fk'] . "'>" . ucwords($test['test_name']) . "</span>" . "<br>";
                                                    } $packagename = explode(",", $row['packagename']);
                                                    foreach ($packagename as $package) {
                                                        echo "<span id='test_" . $row["id"] . "'>" . ucwords($package) . "</span><br>";
                                                    }
                                                    ?>
                                                </td>
                                                <td><?php echo ucwords($row['date']); ?></td>

                                                <td><?php
                                                    $payable_amount = 0;
                                                    if ($row['payable_amount'] == "") {
                                                        echo "Rs. 0";
                                                    } else {
                                                        $payable_amount = $row['payable_amount'];
                                                        echo "Rs. " . number_format((float) $row['payable_amount'], 2, '.', '');
                                                    }
                                                    ?> /<?= " Rs." . number_format((float) $row["price"], 2, '.', ''); ?>
                                                    <?php
                                                    if ($row["cut_from_wallet"] != 0) {
                                                        echo "<br><small>(Rs." . number_format((float) $row["cut_from_wallet"], 2, '.', '') . " Debited from wallet)</small>";
                                                    }
                                                    if ($row["discount"] != '' && $row["discount"] != '0') {
                                                        $dprice1 = $row["discount"] * $row["price"] / 100;
                                                        $discount_amount = $discount_amount + $dprice1;
                                                        echo "<br><small>( Rs." . number_format((float) $dprice1, 2, '.', '') . " Discount)</small>";
                                                    }
                                                    if ($row["collection_charge"] == 1) {
                                                        echo "<br><small>( Rs." . number_format((float) 100, 2, '.', '') . " Collection charge)</small>";
                                                    }
                                                    ?>
                                                </td>

                                                <td>

                                                    <?php
                                                    if ($row['status'] == 1) {
                                                        echo "<span class='label label-danger '>Waiting For Approval</span>";
                                                    }
                                                    ?>
                                                    <?php
                                                    if ($row['status'] == 6) {
                                                        echo "<span class='label label-warning '>Approved</span>";
                                                    }
                                                    ?>
                                                    <?php
                                                    if ($row['status'] == 7) {
                                                        echo "<span class='label label-warning '>Sample Collected</span>";
                                                    }
                                                    ?>
                                                    <?php
                                                    if ($row['status'] == 8) {
                                                        echo "<span class='label label-warning '>Processing</span>";
                                                    }
                                                    ?>
                                                    <?php
                                                    if ($row['status'] == 2) {
                                                        echo "<span class='label label-success '>Completed</span>";
                                                    }
                                                    ?>
                                                    <?php
                                                    if ($row['status'] == 0) {
                                                        echo "<span class='label label-danger '>User Deleted</span>";
                                                    }
                                                    ?>
                                                    <br><?php
                                                    if ($row['emergency'] == '1') {
                                                        echo "<span class='label label-danger '>Emergency</span><br>";
                                                    }
                                                    if ($row["dispatch"] == 1) {
                                                        echo "<span class='label label-success '>Dispatched</span>";
                                                    }
                                                    ?>
                                                    <?php /* if($row['status']=="1" && $row['sample_collection']=="0" ){ echo "<span class='label label-danger'>Pending</span>"; }else if($row['status']=="2"){ echo "<span class='label label-success'>Completed</span>"; }else if($row['status']=="3"){ echo "<span class='label label-danger'>Spam</span>"; }else if($row['sample_collection']==1){ echo "<span class='label label-warning'>Processing</span>";} 
                                                     */
                                                    ?> 

                                                </td>
                                                <td>
                                                    <!--<a  href='<?php echo base_url(); ?>job_master/job_mark_spam/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Mark as Spam" ><span class="label label-danger">Mark as Spam</span></a>                                                                                                                                                                                                                                                                                                                                                                      <a  href='<?php echo base_url(); ?>job_master/job_mark_completed/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Mark as completed" ><span class="label label-success">Mark as completed</span> </a>  --> 
                                                    <a  href='<?php echo base_url(); ?>job-master/job-details/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="View Job Details" target="_blank"> <span class=""><i class="fa fa-eye"> </i></span> </a>

                                                    <?php if ($login_data['type'] != 7) { ?>

                                                        <a  onclick="return confirm('Are you sure you want to spam this job?');" href='<?php echo base_url(); ?>job_master/changing_spam/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Spam Job" > <span class=""><i class="fa fa-trash"> </i></span> </a>
                                                    <?php } ?>                                                 
                                                    <a  href='<?php echo base_url(); ?>job_master/job_dispatch/<?php echo $row['id']; ?>/<?php
                                                    if ($row["dispatch"] == 1) {
                                                        echo "0";
                                                    } else {
                                                        echo "1";
                                                    }
                                                    ?>' data-toggle="tooltip" data-original-title="<?php
                                                        if ($row["dispatch"] == 1) {
                                                            echo "Dispatched";
                                                        } else {
                                                            echo "Mark As Dispatched";
                                                        }
                                                        ?>" onclick="return confirm('Are you sure?');"> <span class="<?php
                                                            if ($row["dispatch"] == 1) {
                                                                echo "success";
                                                            } else {
                                                                echo "warning";
                                                            }
                                                            ?>"><i class="fa fa-truck"> </i></span> </a>
                                                                                                        <?php if (!empty($row["report"])) { ?>
                                                                                                            <?php
                                                                                                            if ($login_data['type'] == 6 || $login_data['type'] == 7) {
                                                                                                                if ($payable_amount == 0) {
                                                                                                                    ?>
                                                                <a href='javascript:void(0);' data-toggle="tooltip" data-original-title="Print Report" onclick="$('#print_model').modal('show');
                                                                        $('#print_simple').attr('href', '<?= base_url() ?>job_master/open_report/<?= $row["id"] ?>/1');
                                                                        $('#print_wlpd').attr('href', '<?= base_url() ?>job_master/open_report/<?= $row["id"] ?>/2');"><span class=""><i class="fa fa-print"></i></span></a>
                                                                        <a href='javascript:void(0);' onclick="<?php if($payable_amount==0){ ?>sms_popup('<?= $row["id"] ?>');<?php }else{ ?>alert('Please collect due amount.');<?php } ?>" data-toggle="tooltip" data-original-title="Send Report Via SMS" <?php if (!empty($row["send_repor_sms"])) { ?> style="background:green;color:white;width:auto;padding:0px 5px 3px 5px;border-radius:3px;" <?php } ?>><span class=""><i class="fa fa-comment-o"></i></span></a>
                                                            <a href='javascript:void(0);' onclick="<?php if($payable_amount==0){ ?>mail_popup('<?= $row["id"] ?>', '<?= $row["cid"] ?>');<?php }else{ ?>alert('Please collect due amount.');<?php } ?>" data-toggle="tooltip" data-original-title="Send Report Via Mail" <?php if (!empty($row["send_report_mail"])) { ?> style="background:green;color:white;width:auto;padding:0px 5px 3px 5px;border-radius:3px;" <?php } ?>><span class=""><i class="fa fa-envelope-o"></i></span></a>
                                                               <?php } ?>
                                                           <?php } else { ?>
                                                            <a href='javascript:void(0);' data-toggle="tooltip" data-original-title="Print Report" onclick="$('#print_model').modal('show');
                                                                    $('#print_simple').attr('href', '<?= base_url() ?>job_master/open_report/<?= $row["id"] ?>/1');
                                                                    $('#print_wlpd').attr('href', '<?= base_url() ?>job_master/open_report/<?= $row["id"] ?>/2');"><span class=""><i class="fa fa-print"></i></span></a>
                                                            <a href='javascript:void(0);' onclick="<?php if($payable_amount==0){ ?>sms_popup('<?= $row["id"] ?>');<?php }else{ ?>alert('Please collect due amount.');<?php } ?>" data-toggle="tooltip" data-original-title="Send Report Via SMS" <?php if (!empty($row["send_repor_sms"])) { ?> style="background:green;color:white;width:auto;padding:0px 5px 3px 5px;border-radius:3px;" <?php } ?>><span class=""><i class="fa fa-comment-o"></i></span></a>
                                                            <a href='javascript:void(0);' onclick="<?php if($payable_amount==0){ ?>mail_popup('<?= $row["id"] ?>', '<?= $row["cid"] ?>');<?php }else{ ?>alert('Please collect due amount.');<?php } ?>" data-toggle="tooltip" data-original-title="Send Report Via Mail" <?php if (!empty($row["send_report_mail"])) { ?> style="background:green;color:white;width:auto;padding:0px 5px 3px 5px;border-radius:3px;" <?php } ?>><span class=""><i class="fa fa-envelope-o"></i></span></a>
                                                        <?php } ?>
                                                    <?php } ?>

                                                    <?php if ($payable_amount > 0) { ?>
                                                        <a onclick="return confirm('Are you sure?');" href='<?= base_url(); ?>/job_master/send_sms_due_payment/<?= $row["id"] ?>' data-toggle="tooltip" data-original-title="Send SMS" ><span class=""><i class="fa fa-inr fa-6"></i></span></a>
                                                    <?php } ?>
                                                    <a href='<?php if (!empty($row['invoice'])) { ?><?= base_url(); ?>upload/result/<?php echo $query[0]['invoice']; ?><?php } else { ?><?= base_url(); ?>job_master/pdf_invoice/<?= $row["id"] ?><?php } ?>' target="_blank" data-toggle="tooltip" data-original-title="Download Invoice"><span class=""><i class="fa fa-download fa-6"></i></span></a>
                                                    <a href='<?= base_url(); ?>job_master/ack/<?= $row["id"] ?>' data-toggle="tooltip" data-original-title="Download ACK" target="_blank"><span class=""><i class="fa fa-download fa-6"></i></span></a>
                                                </td>
                                            </tr>
                                            <?php
                                            $cnt++;
                                        }if (empty($query)) {
                                            ?>
                                            <tr>
                                                <td colspan="10">No records found</td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php /* Nishit doctor model start */ ?>
                        <div class="modal fade" id="print_model" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Print Report</h4>
                                    </div>
                                    <div class="modal-body">
                                        <h5>Do you want to print with letterhead?</h5>
                                    </div>
                                    <div class="modal-footer">
                                        <a class="btn btn-primary" href="http://websitedemo.co.in/phpdemoz/patholab/add_result/pdf_report/3" target="_blank" onclick="$('#print_model').modal('hide');" id="print_simple">Yes</a>
                                        <a class="btn btn-primary" href="http://websitedemo.co.in/phpdemoz/patholab/add_result/pdf_report/3?type=wlp" target="_blank" onclick="$('#print_model').modal('hide');" id="print_wlpd">No</a>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <?php /* Nishit doctor model end */ ?>
                        <div class="modal fade" id="sms_modal" role="dialog">
                            <div class="modal-dialog modal-lg">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <?php echo form_open("job_master/send_report_sms", array("method" => "post", "role" => "form")); ?>
                                    <input type="hidden" id="job_fk" name="job_fk" value=""/>
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Send Report Via SMS</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div id="all_mobile_no">
                                            <div class="form-group">
                                                <input type="text" placeholder="Mobile No.1" id="mobile_no0" name="mobile_no[]" class="form-control"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <a href="javascript:void(0);" class="btn btn-sm btn-primary" onclick="append_num();"><i class="fa fa-plus"></i> Add</a>
                                        </div>
                                        <div class="form-group">
                                            <textarea class="form-control" id="sms_report" rows="20" placeholder="SMS..." name="sms" required=""></textarea>
                                        </div>
                                        <div class="pull-right"><button type="submit" class="btn btn-primary" id="amount_submit_btn">Send</button></div><br>
                                        <h3>Send SMS History</h3>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Mobile No</th>
                                                    <th style="width:70%">SMS</th>
                                                    <th>Send By</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody id="result_sms_history">
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                    <?= form_close(); ?>
                                </div>

                            </div>
                        </div>
                        <div class="modal fade" id="mail_modal" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <?php echo form_open("job_master/send_report_email", array("method" => "post", "role" => "form")); ?>
                                    <input type="hidden" id="job_fk1" name="job_fk" value=""/>
                                    <input type="hidden" id="cust_fk1" name="cust_fk" value=""/>
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Send Report Via Mail</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div id="all_mobile_no1">
                                            <div class="form-group">
                                                <input type="email" placeholder="Email-1" id="email_no0" name="email[]" class="form-control"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <a href="javascript:void(0);" class="btn btn-sm btn-primary" onclick="append_num1();"><i class="fa fa-plus"></i> Add</a>
                                        </div>
                                        <div class="pull-right"><button type="submit" class="btn btn-primary" id="amount_submit_btn">Send</button></div><br>
                                        <h3>Send Email History</h3>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Email</th>
                                                    <th>Send By</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody id="result_email_history">
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                    <?= form_close(); ?>
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
<script  type="text/javascript">
    $sms_cnt = 1;
    $num_cnt = 2;
    function append_num() {
        $("#all_mobile_no").append('<div class="form-group" id="add_num_' + $sms_cnt + '" style="width:93%;height:40px;"><input type="text" placeholder="Mobile No.' + $num_cnt + '" id="mobile_no' + $sms_cnt + '" name="mobile_no[]" class="form-control" style="width:94%;float:left;margin-right:16px"/><a href="javascript:void(0);" onclick="add_num_' + $sms_cnt + '.remove();$sms_cnt--;$num_cnt--;"><i class="fa fa-trash"></i></a></div>');
        $sms_cnt++;
        $num_cnt++;
    }
    $sms_cnt1 = 1;
    $num_cnt1 = 2;
    function append_num1() {
        $("#all_mobile_no1").append('<div class="form-group" id="add_num1_' + $sms_cnt1 + '" style="width:93%;height:40px;"><input type="email" placeholder="Email-' + $num_cnt1 + '" id="mobile_no1' + $sms_cnt1 + '" name="email[]" class="form-control" style="width:94%;float:left;margin-right:16px"/><a href="javascript:void(0);" onclick="add_num1_' + $sms_cnt1 + '.remove();$sms_cnt1--;$num_cnt1--;"><i class="fa fa-trash"></i></a></div>');
        $sms_cnt1++;
        $num_cnt1++;
    }
    $.ajax({
        url: '<?php echo base_url(); ?>Admin/get_refered_by',
        type: 'post',
        data: {val: 1, selected: '<?= $referral_by ?>'},
        success: function (data) {
            var json_data = JSON.parse(data);
            $("#referral_by").html(json_data.refer);
            jQuery('.chosen').trigger("chosen:updated");
        },
        error: function (jqXhr) {
            $("#referral_by").html("");
        },
        complete: function () {
        },
    });

    $.ajax({
        url: '<?php echo base_url(); ?>job_master/get_customer',
        type: 'post',
        data: {val: 1, selected: '<?= $user2 ?>'},
        success: function (data) {
            var json_data = JSON.parse(data);
            $("#customer_list").html(json_data.customer);
            jQuery('.chosen').trigger("chosen:updated");
        },
        error: function (jqXhr) {
            $("#customer_list").html("");
        },
        complete: function () {
        },
    });
    function sms_popup(jid) {
        $("#result_sms_history").empty();
        $.ajax({
            url: '<?php echo base_url(); ?>add_result/send_result/' + jid,
            beforeSend: function () {
                $("#loader_div").attr("style", "");
            },
            success: function (data) {
                var json_data = JSON.parse(data);
                if (json_data.status == 1) {
                    $("#sms_report").val(json_data.sms);
                    for (var i = 0; json_data.mobile.length > i; i++) {
                        $("#mobile_no" + i).val(json_data.mobile[i]);
                    }
                    for (var j = 0; json_data.history.length > j; j++) {
                        var numbr = json_data.history[j].mobile;
                        var new_no = numbr.split(",");
                        var txt = '';
                        for (var nbr = 0; new_no.length > nbr; nbr++) {
                            txt += new_no[nbr] + '<br/>';
                        }
                        $("#result_sms_history").append('<tr> <td>' + txt + '</td> <td><span class="more">' + json_data.history[j].sms + '</span></td> <td>' + json_data.history[j].name + '</td> <td>' + json_data.history[j].created_date + '</td></tr>');
                    }
                    if (json_data.history.length == 0) {
                        $("#result_sms_history").append('<tr> <td colspan="4"><center>Data not available.</center></td></tr>');
                    }
                    more_less();
                    $("#job_fk").val(jid);
                    $("#sms_modal").modal("show");
                } else {
                    alert("Data not available.");
                    $("#job_fk").val("");
                }
            },
            error: function (jqXhr) {
                alert("Try again.");
            },
            complete: function () {
                $("#loader_div").attr("style", "display:none;");
            },
        });
    }
    function mail_popup(jid, cid) {
        $("#result_email_history").empty();
        $.ajax({
            url: '<?php echo base_url(); ?>job_master/get_user_info/' + cid + "/" + jid,
            beforeSend: function () {
                $("#loader_div").attr("style", "");
            },
            success: function (data) {
                var json_data = JSON.parse(data);
                if (json_data.status == 1) {
                    $("#email_no0").val(json_data.data[0].email);
                    for (var j = 0; json_data.history.length > j; j++) {
                        var numbr = json_data.history[j].email;
                        var new_no = numbr.split(",");
                        var txt = '';
                        for (var nbr = 0; new_no.length > nbr; nbr++) {
                            txt += new_no[nbr] + '<br/>';
                        }
                        $("#result_email_history").append('<tr> <td>' + txt + '</td> <td>' + json_data.history[j].name + '</td> <td>' + json_data.history[j].created_date + '</td></tr>');
                    }
                    if (json_data.history.length == 0) {
                        $("#result_email_history").append('<tr> <td colspan="3"><center>Data not available.</center></td></tr>');
                    }
                    $("#job_fk1").val(jid);
                    $("#cust_fk1").val(cid);
                    $("#mail_modal").modal("show");
                } else {
                    alert("Data not available.");
                    $("#job_fk1").val("");
                }
            },
            error: function (jqXhr) {
                alert("Try again.");
            },
            complete: function () {
                $("#loader_div").attr("style", "display:none;");
            },
        });
    }
    function get_branch(val) {
        $.ajax({
            url: '<?php echo base_url(); ?>job_master/get_branch',
            type: 'post',
            data: {city: val},
            success: function (data) {
                $("#branch").html(data);
                $('.multiselect-ui').multiselect("rebuild");
                if (data.trim() == '') {
                    setTimeout(function () {
                        $("ul.multiselect-container.dropdown-menu").html("Data not available.");
                    }, 1000);
                }
            },
            error: function (jqXhr) {
                $("#branch").html("");
            },
            complete: function () {
            },
        });
    }
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
<?php if (!isset($_GET["city"]) || $_GET["city"] == '') { ?>
        setTimeout(function () {
            // $("ul.multiselect-container.dropdown-menu").html("Select City");
        }, 3000);
<?php } ?>
    $(document).ready(function () {
        // Configure/customize these variables.
        var showChar = 100;  // How many characters are shown by default
        var ellipsestext = "...";
        var moretext = "Show more";
        var lesstext = "Show less";


        $('.more').each(function () {
            var content = $(this).html();

            if (content.length > showChar) {

                var c = content.substr(0, showChar);
                var h = content.substr(showChar, content.length - showChar);

                var html = c + '<span class="moreellipses">' + ellipsestext + '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';

                $(this).html(html);
            }

        });

        $(".morelink").click(function () {
            if ($(this).hasClass("less")) {
                $(this).removeClass("less");
                $(this).html(moretext);
            } else {
                $(this).addClass("less");
                $(this).html(lesstext);
            }
            $(this).parent().prev().toggle();
            $(this).prev().toggle();
            return false;
        });
    });
    function more_less() {
        var showChar = 100;  // How many characters are shown by default
        var ellipsestext = "...";
        var moretext = "Show more";
        var lesstext = "Show less";


        $('.more').each(function () {
            var content = $(this).html();

            if (content.length > showChar) {

                var c = content.substr(0, showChar);
                var h = content.substr(showChar, content.length - showChar);

                var html = c + '<span class="moreellipses">' + ellipsestext + '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';

                $(this).html(html);
            }

        });

        $(".morelink").click(function () {
            if ($(this).hasClass("less")) {
                $(this).removeClass("less");
                $(this).html(moretext);
            } else {
                $(this).addClass("less");
                $(this).html(lesstext);
            }
            $(this).parent().prev().toggle();
            $(this).prev().toggle();
            return false;
        });
    }
    /*Check test result start*/
    $(document).ready(function () {
        $.ajax({
            url: '<?php echo base_url(); ?>job_master/check_test_parameter_val',
            type: 'post',
            data: {job_id: '<?= implode(",", $test_ids); ?>'},
            success: function (data) {
                var json_data = JSON.parse(data);
                if (json_data.data) {
                    var job_data = json_data.data;
                    $.each(job_data, function (index, value) {
                        var job_fk = value.job_test;
                        if (value.details) {
                            $.each(value.details, function (index1, value1) {
                                var tst_fk = value1.test_fk;
                                var tst_parameter = value1.parameter;
                                var tst_result = value1.result;
                                /*if (tst_parameter.length <= tst_result.length && tst_parameter.length > 0) {*/
                                if (tst_result.length > 0 && tst_parameter.length > 0) {
                                    if ($("#test_" + job_fk + "_" + tst_fk)) {
                                        $("#test_" + job_fk + "_" + tst_fk).attr("style", "background:#f7f574;");
                                        $("#btn_" + job_fk + "_" + tst_fk).attr("style", "");
                                    }
                                }
                                var tst_graph = value1.graph;
                                if (tst_graph.length > 0) {
                                    if ($("#test_" + job_fk + "_" + tst_fk)) {
                                        $("#test_" + job_fk + "_" + tst_fk).attr("style", "background:#f7f574;");
                                        $("#btn_" + job_fk + "_" + tst_fk).attr("style", "");
                                    }
                                }
                                var tst_approve_result = value1.approve;
                                if (tst_approve_result.length > 0) {
                                    $.each(tst_approve_result, function (index2, value2) {
                                        if ($("#test_" + value2.job_fk + "_" + value2.test_fk)) {
                                            $("#test_" + value2.job_fk + "_" + value2.test_fk).attr("style", "background:green;color:white;");
                                        }
                                    });
                                }
                            });
                        }
                    });
                }
            },
            error: function (jqXhr) {
                $("#branch").html("");
            },
            complete: function () {
            },
        });

    });
    /*End*/
</script>