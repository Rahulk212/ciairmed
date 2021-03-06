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

</style>
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Find Jobs
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li>Find Jobs</li>
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
                                <?php echo form_open("job_master/find_job_list", array("method" => "GET", "role" => "form")); ?>
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
                                        }
                                        ?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;">
                                        <input type="text" name="end_date" readonly="" placeholder="End date" class="form-control datepicker-input" id="date"  value="<?php
                                        if (isset($end_date)) {
                                            echo $end_date;
                                        }
                                        ?>" />
                                    </div>
                                </div>

                                <div class="form-group">
                                        <div class="col-sm-3" style="margin-bottom:10px;">

                                            <select class="form-control chosen-select" onchange="get_branch(this.value);" data-placeholder="Select Test city" tabindex="-1" name="city" id="citiess">
                                                <option value="">--All city--</option>
                                                <?php foreach ($test_cities as $bkey) { if(!empty($search_c)) { if(in_array($bkey['id'],$search_c)) { ?>
                                                    <option value="<?= $bkey["id"] ?>" <?php
                                                    if ($tcity == $bkey["id"]) {
                                                        echo "selected";
                                                } if(!empty($search_c)) { if($search_c[0] == $bkey['id']) { echo "selected"; } }
                                                    ?>><?= $bkey["name"] ?></option>
                                <?php } } else { ?>
                                                    <option value="<?= $bkey["id"] ?>" <?php
                                                    if ($tcity == $bkey["id"]) {
                                                        echo "selected";
                                                    }
                                                    ?>><?= $bkey["name"] ?></option>
 <?php } } ?>
                                            </select>

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
                                <?php /* if ($login_data['type'] == 1 || $login_data['type'] == 2) { ?>
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
                            */?>
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
                                            <button type="button" class="btn btn-sm btn-primary" onclick="window.location = '<?= base_url(); ?>job_master/find_job_list'" value="Reset"/><i class="fa fa-refresh"></i>Reset</button>
                                            <?php if ($_SERVER['QUERY_STRING'] != '') { ?>
<!--                                                <a style="float:right;margin-left:3px" href='<?php echo base_url(); ?>job_master/export_csv?<?= $_SERVER['QUERY_STRING'] ?>' class="btn btn-sm btn-primary" ><i class="fa fa-download" ></i><strong > Export To CSV</strong></a>-->
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                        <div class="tableclass">

                            <div class="table-responsive pending_job_list_tbl">
                                <table id="example4" class="table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Reg No</th>
                                            <th>Customer Name</th>
                                            <th>Doctor</th>
                                            <th>Test/Package Name</th>
                                            <th>Date</th>
                                            <!--<th>Payment Type</th>-->
                                            <!--<th>Total Amount(Rs.)</th>-->
                                            <th>Payable Amount / Price</th>
                                            <!--<th>Blood Sample Collation Status</th>-->
                                            <th>Job Status</th>
                                            <th width="150px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $cnt = 1;
                                        foreach ($query as $row) {
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
                                                    $testname = explode(",", $row['testname']);
                                                    foreach ($testname as $test) {
                                                        echo ucwords($test) . "<br>";
                                                    } $packagename = explode(",", $row['packagename']);
                                                    foreach ($packagename as $package) {
                                                        echo ucwords($package);
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
                                                        echo "Rs. " . $row['payable_amount'];
                                                    }
                                                    ?> /<?= " Rs." . $row["price"] ?>
                                                    <?php
                                                    if ($row["cut_from_wallet"] != 0) {
                                                        echo "<br><small>(Rs." . $row["cut_from_wallet"] . " Debited from wallet)</small>";
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
                                                    <?php if (!empty($row["report"])) { ?>
                                                        <?php
                                                        if ($login_data['type'] == 6 || $login_data['type'] == 7) {
                                                            if ($payable_amount == 0) {
                                                                ?>
                                                                <a href='javascript:void(0);' data-toggle="tooltip" data-original-title="Print Report" onclick="$('#print_model').modal('show');$('#print_simple').attr('href', '<?= base_url() ?>job_master/open_report/<?= $row["id"] ?>/1');$('#print_wlpd').attr('href', '<?= base_url() ?>job_master/open_report/<?= $row["id"] ?>/2');"><span class=""><i class="fa fa-print"></i></span></a>
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                            <a href='javascript:void(0);' data-toggle="tooltip" data-original-title="Print Report" onclick="$('#print_model').modal('show');$('#print_simple').attr('href', '<?= base_url() ?>job_master/open_report/<?= $row["id"] ?>/1');$('#print_wlpd').attr('href', '<?= base_url() ?>job_master/open_report/<?= $row["id"] ?>/2');"><span class=""><i class="fa fa-print"></i></span></a>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php
                                            $cnt++;
                                        }if (empty($query)) {
                                            ?>
                                            <tr>
                                                <td colspan="9">Search and show result</td>
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
<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap-multiselect.js"></script>
<script  type="text/javascript">
                                            $(function () {
                                                $('.chosen').chosen();
                                            });
                                            jQuery(".chosen-select").chosen({
                                                search_contains: true
                                            });
                                            var citiess= $("#citiess").val();
    if(citiess=="1"){
            testcity=333;
        }
        if(citiess=="5"){;
            testcity=1510;
        }
         
          if(citiess=="6"){
            testcity=345;
        }
        if(citiess=="8"){
            testcity=476;
        }
    $.ajax({
        url: '<?php echo base_url(); ?>Admin/get_refered_by',
        type: 'post',
        data: {val: testcity, selected: '<?= $referral_by ?>'},
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

                                            function get_branch(val) {
                                                if(val=="1"){
            testcity=333;
        }
        if(val=="5"){;
            testcity=1510;
        }
         
          if(val=="6"){
            testcity=345;
        }
        if(val=="8"){
            testcity=476;
        }
		
		 $.ajax({
        url: '<?php echo base_url(); ?>Admin/get_refered_by',
        type: 'post',
        data: {val: testcity, selected: '<?= $referral_by ?>'},
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
</script>
<script type="text/javascript">
    $(function () {
        $('.multiselect-ui').multiselect({
            includeSelectAllOption: true,
            nonSelectedText: 'Select Branch'
        });
    });
<?php if (!isset($_GET["city"]) || $_GET["city"] == '') { ?>
        setTimeout(function () {
            // $("ul.multiselect-container.dropdown-menu").html("Select City");
        }, 3000);
<?php } ?>
</script>