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
            Outsource Jobs
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
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">?</button>
                                    <?php echo $success['0']; ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="box-body">
                            <div class="widget">                           
                            </div>
                            <div class="widget">
                                <div class="col-sm-12">
                                    <div class="form-group pull-right">
                                        <div class="col-sm-12" style="margin-bottom:10px;">
<!--    <a style="float:right;margin-left:3px" href='javascript:void(0);' id="job_search_btn" class="btn btn-sm btn-primary" ><i class="fa fa-download" ></i><strong > Export To CSV</strong></a>-->
                                        </div>
                                    </div>
                                </div>
                            </div>
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
<!--        <th>Doctor</th>-->
        <th>Test/Package Name</th>
        <th>Date</th>
<!--        <th>Payable Amount / Price</th>-->
<!--        <th>Job Status</th>-->
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
            <td style="color:#d73925;">
                <?php 
                echo  $row["order_id"]; 
                ?>
            
            </td>
            <?php
            if ($row['relation'] == '') {
                $row['relation'] = 'Self';
            }
            ?>
            <td>
                <?php if ($row['relation'] == 'Self') { ?>
                    <span style="color:#d73925;"><?php echo ucwords($row['full_name']); ?></span> <?php echo "(".$row['age']." ".$row['age_type']."/".$row['gender'] .")"; ?>
                    &nbsp;<?= $row['mobile'] ?>
                <?php } else { ?>
                    <span style="color:#d73925;"><?= ucfirst($row['relation']); ?></span> <?php echo "(".$row['age']." ".$row['age_type']."/".$row['gender'] .")"; ?>
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
                /*if($branch['portal'] == 'web'){*/
                foreach ($branchlist as $branch) {
                    if ($row['branch_fk'] == $branch['id']) {
                        echo '[' . $branch['branch_name'] . ']';
                    }
                }
                /*}else{
                    foreach ($branchlist as $branch) {
                        if ($row['branch_fk'] == $branch['branch_code']) {
                            echo '[' . $branch['branch_name'] . ']';
                        }
                    }    
                }*/
                ?>
<!--                <br><small><b>Added By- </b>
                    <?php if (!empty($row["added_by"])) {
                        echo ucfirst($row["added_by"]);
                    } else {
                        echo "Online Booking";
                    } ?>
                </small>-->
            </td>
<!--            <td><?php if (!empty($row["doctor"])) { ?><?= ucfirst($row["doctor_name"]) . " - " . $row["doctor_mobile"]; ?> <?php
                } else {
                    echo "-";
                }
                ?></td>-->
            <td><?php
                //$testname = explode(",", $row['testname']);
                foreach ($row["job_test_list"] as $test) {
                    $is_panel = '';
                    if($test['is_panel']==1){
                    $is_panel = '(Panel)';    
                    }
                        echo "<span id='test_" . $row["id"] . "_" . $test['test_fk'] . "'>" . ucwords($test['test_name']) ." <b>".$is_panel. "</b></span>"; ?>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" id='btn_<?= $row["id"] ?>_<?= $test['test_fk'] ?>' style="display:none;" data-toggle="tooltip" title="Approve Test" onclick="approveTest('<?= $row["id"] ?>', '<?= $test['test_fk'] ?>');"><i class="fa fa-check"></i></a> <a href="<?= base_url(); ?>job_master/delete_approved_outsource/<?= $row["id"] ?>/<?= $test['test_fk'] ?>" id='dbtn_<?= $row["id"] ?>_<?= $test['test_fk'] ?>' style="display:none;" data-toggle="tooltip" onclick="return confirm('Are you sure?');" title="Disapprove Test"><b>X</b></a> <br>	
                <?php } $packagename = explode(",", $row['packagename']);
                foreach ($packagename as $package) {
                    echo "<span id='test_" . $row["id"] . "'>" . ucwords($package) . "</span><br>";
                }
                ?>
            </td>
            <td><?php echo ucwords($row['date']); ?></td>

<!--            <td><?php
                $payable_amount = 0;
                /*Nishit code start*/
                $color_code = '#00A65A';
                if($row['payable_amount']>0){
                    $color_code = '#D33724';
                }
                /*END*/
                if ($row['payable_amount'] == "") {
                    echo "<spam style='color:white;background:".$color_code.";padding:2px'>Rs. 0";
                } else {
                    $payable_amount = $row['payable_amount'];
                    echo "<spam style='color:white;background:".$color_code.";padding:2px'>Rs. " . number_format((float) $row['payable_amount'], 2, '.', '');
                }
                ?> /<?= " Rs." . number_format((float) $row["price"], 2, '.', '')."</spam>"; ?>
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
            </td>-->

<!--            <td>

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

            </td>-->
            <td>
                <a href='javascript:void(0);' data-toggle="tooltip" data-original-title="Manage Result" onclick="add_Result('<?= $row['id'] ?>');"> <span class=""><i class="fa fa-eye"> </i></span> </a>
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
        <tr style="display:none;">
            <td colspan="10" id="search_test_ids"><?php echo implode(",",$test_ids); ?></td>
        </tr>
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

/*    $.ajax({
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
    }); */
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
    $approved_test_cnt = 0;
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
                    var add_result_array = [];
                    $.each(job_data, function (index, value) {
                        var job_fk = value.job_test;
                        if (value.details) {
                            $.each(value.details, function (index1, value1) {
                                var tst_fk = value1.test_fk;
                                var tst_parameter = value1.parameter;
                                var tst_result = value1.result;
                                if (tst_result.length > 0 && tst_parameter.length > 0) {
                                    if ($("#test_" + job_fk + "_" + tst_fk)) {
                                        add_result_array.push(tst_fk);
                                        $("#test_" + job_fk + "_" + tst_fk).attr("style", "background:#f7f574;");
                                        $("#btn_" + job_fk + "_" + tst_fk).attr("style", "");
                                    }
                                }
                                var tst_graph = value1.graph;
                                if (tst_graph.length > 0) {
                                    if ($("#test_" + job_fk + "_" + tst_fk)) {
                                        add_result_array.push(tst_fk);
                                        $("#test_" + job_fk + "_" + tst_fk).attr("style", "background:#f7f574;");
                                        $("#btn_" + job_fk + "_" + tst_fk).attr("style", "");
                                    }
                                }
                                var tst_approve_result = value1.approve;
                                if (tst_approve_result.length > 0) {
                                    $.each(tst_approve_result, function (index2, value2) {
                                        if ($("#test_" + value2.job_fk + "_" + value2.test_fk)) {
                                            $("#test_" + value2.job_fk + "_" + value2.test_fk).attr("style", "background:green;color:white;");
                                            $("#btn_" + job_fk + "_" + tst_fk).attr("style", "display:none;");
                                            $("#dbtn_" + job_fk + "_" + tst_fk).attr("style", "");
                                            removeA(add_result_array, tst_fk);
                                            $approved_test_cnt++;
                                        }
                                    });
                                }
                                var tst_outsource_result = value1.outsource;
                                if (tst_outsource_result.length > 0) {
                                    $.each(tst_outsource_result, function (index2, value2) {
                                        if ($("#test_" + value2.job_fk + "_" + value2.test_fk)) {
                                            $("#test_" + value2.job_fk + "_" + value2.test_fk).attr("style", "background:blue;color:white;");
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
    $approved_test_cnt = 0;
    function get_test_rslt() {
        var ids = $("#search_test_ids").html();
        $.ajax({
            url: '<?php echo base_url(); ?>job_master/check_test_parameter_val',
            type: 'post',
            data: {job_id: ids},
            success: function (data) {
                var json_data = JSON.parse(data);
                if (json_data.data) {
                    var job_data = json_data.data;
                    var add_result_array = [];
                    $.each(job_data, function (index, value) {
                        var job_fk = value.job_test;
                        if (value.details) {
                            $.each(value.details, function (index1, value1) {
                                var tst_fk = value1.test_fk;
                                var tst_parameter = value1.parameter;
                                var tst_result = value1.result;
                                if (tst_result.length > 0 && tst_parameter.length > 0) {
                                    if ($("#test_" + job_fk + "_" + tst_fk)) {
                                        add_result_array.push(tst_fk);
                                        $("#test_" + job_fk + "_" + tst_fk).attr("style", "background:#f7f574;");
                                        $("#btn_" + job_fk + "_" + tst_fk).attr("style", "");
                                    }
                                }
                                var tst_graph = value1.graph;
                                if (tst_graph.length > 0) {
                                    if ($("#test_" + job_fk + "_" + tst_fk)) {
                                        add_result_array.push(tst_fk);
                                        $("#test_" + job_fk + "_" + tst_fk).attr("style", "background:#f7f574;");
                                        $("#btn_" + job_fk + "_" + tst_fk).attr("style", "");
                                    }
                                }
                                var tst_approve_result = value1.approve;
                                if (tst_approve_result.length > 0) {
                                    $.each(tst_approve_result, function (index2, value2) {
                                        if ($("#test_" + value2.job_fk + "_" + value2.test_fk)) {
                                            $("#test_" + value2.job_fk + "_" + value2.test_fk).attr("style", "background:green;color:white;");
                                            $("#btn_" + job_fk + "_" + tst_fk).attr("style", "display:none;");
                                            $("#dbtn_" + job_fk + "_" + tst_fk).attr("style", "");
                                            removeA(add_result_array, tst_fk);
                                            $approved_test_cnt++;
                                        }
                                    });
                                }
                                var tst_outsource_result = value1.outsource;
                                if (tst_outsource_result.length > 0) {
                                    $.each(tst_outsource_result, function (index2, value2) {
                                        if ($("#test_" + value2.job_fk + "_" + value2.test_fk)) {
                                            $("#test_" + value2.job_fk + "_" + value2.test_fk).attr("style", "background:blue;color:white;");
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
    }
    sub_form();
    //pinkesh code start
    function dispatched_job_test(jid,status) {
        var test = confirm('Are you sure?');
        if(test == true) {
            $.ajax({
                url: '<?php echo base_url(); ?>job_master/job_dispatch',
                type: 'post',
                data: {job_id: jid,status:status},
                success: function (data) {
                    if(status == '0') {
                        $("#dispatch_1_"+jid).hide();
                        $("#dispatch_0_"+jid).show();
                    } else if(status == '1') {
                        $("#dispatch_1_"+jid).show();
                        $("#dispatch_0_"+jid).hide();
                    }
                },
                complete: function () {
                    if(status == '0') {
                        alert("Dispatch Remove successfully");
                    } else if(status == '1') {
                        alert("Dispatched Successfully");
                    }
                },
            });
        }
    }
    </script>
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
    function add_Result(jid) {
        jq.fancybox.open({
            href: '<?= base_url(); ?>add_result/test_outsource/' + jid,
            type: 'iframe',
            padding: 5,
            width: '100%',
        });
    }
    function approveTest(jid, tid) {
        jq.fancybox.open({
            href: '<?= base_url(); ?>add_result/test_approve_details/' + jid + '/' + tid,
            type: 'iframe',
            padding: 5,
            width: '100%',
        });
    }
    function close_popup1() {
        jq.fancybox.close();
        get_test_rslt();
    }
    //pinkesh code end
</script>