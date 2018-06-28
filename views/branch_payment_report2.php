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
            Daily Collection Report
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li>Payment Report</li>

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
                        <?php echo form_open("job_report/branchpaymentnew2", array("method" => "GET", "role" => "form")); ?>
                        <div class="row">
                            <div class="col-sm-3">
                                <input type="text" name="start_date" placeholder="Select date" required="" class="form-control datepicker-input" id="date"  value="<?= $start_date ?>" />
                            </div>

                            <!--   <div class="col-sm-2">
                                                            <input type="text" name="end_date" placeholder="End date" class="form-control datepicker-input" id="date1"  value="<?= $end_date ?>" />
                                                        </div>-->
                            <div class="col-sm-3"> 
                                <select class="form-control chosen-select" data-placeholder="Select city" tabindex="-1" name="city" onchange="get_branch1(this.value);">
                                    <option value="" >All City</option>
                                    <?php foreach ($city_list as $cities) { ?>
                                        <option value="<?php echo $cities['id']; ?>" <?php
                                        if ($city == $cities['id']) {
                                            echo " selected ";
                                        } if ($city == '') {
                                            if ($cities['id'] == 1) {
                                                echo "selected";
                                            }
                                        }
                                        ?>><?php echo ucwords($cities['name']); ?></option>
                                            <?php } ?>
                                </select>
                            </div>

                            <div class="col-sm-3"> 
                                <select class="form-control chosen" data-placeholder="Select Branch" tabindex="-1" name="branch" id="branch1">
                                    <option value="" >All Branch</option>
                                    <?php
                                    foreach ($branch_list as $branchh) {
                                        if (!empty($branchs)) {
                                            if (in_array($branchh['id'], $branchs)) {
                                                ?>
                                                <option value="<?php echo $branchh['id']; ?>" <?php
                                                if ($branch == $branchh['id']) {
                                                    echo " selected ";
                                                }
                                                ?>><?php echo ucwords($branchh['branch_name']) . " - " . $branchh['branch_code']; ?></option>
                                                    <?php } ?>
                                                <?php } else { ?>
                                            <option value="<?php echo $branchh['id']; ?>" <?php
                                            if ($branch == $branchh['id']) {
                                                echo " selected ";
                                            }
                                            ?>><?php echo ucwords($branchh['branch_name']) . " - " . $branchh['branch_code']; ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
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
                            <?php if (trim($start_date) != '' || trim($end_date) != '') { ?>
                                <a href="javascript:void(0);" onclick="printData();" class="btn-sm btn-primary">Print Report</a><?php } ?>
                            <?php if (trim($start_date) != '' || trim($end_date) != '') { ?>
                                <a href="<?php echo base_url(); ?>job_report/daily_export2?start_date=<?php echo $start_date; ?>&city=<?php echo $city; ?>&branch=<?php echo $branch; ?>" class="btn-sm btn-primary">Export</a><?php } ?>
                            <div class="table-responsive" id="prnt_rpt">
                                <?php
                                foreach ($dailyCollectionData as $brachData) {
                                    if (!empty($brachData['dailyCollectionData'])) {
                                        ?>

                                        <table id="example4" class="table table-bordered table-striped">
                                            <h2><?php if (trim($start_date) != '') { ?><?= $start_date ?><?php } ?></h2>
                                            <?php
                                            $temparray = array();
                                            $tep = 0;

                                            foreach ($brachData["dailyCollectionData"] as $am_br) { //if($am_br['bid'] == $branch['id']) {  
                                                ?>
                                                <?php
                                                if (!in_array($am_br['bid'], $temparray)) {
                                                    $new_branch = 0;
                                                    if ($tep == '1') {
                                                        ?>
                                                        <tr style="background-color: lightcoral; color: white;">
                                                            <td>Total</td>
                                                            <td><?php echo "Rs." . $gross; ?></td>
                                                            <td><?php echo "Rs." . $dis; ?></td>
                                                            <td><?php echo "Rs." . $neta; ?></td>
                                                            <td><?php echo "Rs." . $cas; ?></td>
                                                            <td><?php echo "Rs." . $cheq; ?></td>
                                                            <td><?php echo "Rs." . $cre; ?></td>
                                                            <td><?php echo "Rs." . $panl; ?></td>
                                                            <td><?php echo "Rs." . $sm; ?></td>
                                                            <td><?php echo "Rs." . $bk; ?></td>
                                                            <td><?php echo "Rs." . $netb; ?></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    $tep = '1';
                                                    array_push($temparray, $am_br['bid']);
                                                    $gross = 0;
                                                    $dis = 0;
                                                    $neta = 0;
                                                    $cas = 0;
                                                    $cheq = 0;
                                                    $cre = 0;
                                                    $panl = 0;
                                                    $sm = 0;
                                                    $bk = 0;
                                                    $netb = 0;
                                                    $new_branch = 1;
                                                    ?>
                                                    <thead>

                                                        <tr>
                                                            <th colspan="12"><h3><?php echo $brachData['branch_name']; ?></h3></th>
                                                        </tr>

                                                        <tr>
                                                            <th><h4>Name</h4></th>
                                                            <th><h4>Gross Amt</h4></th>
                                                            <th><h4>Discount</h4></th>
                                                            <th><h4>Net Amt</h4></th>
                                                            <th><h4>Cash</h4></th>
                                                            <th><h4>Cheque</h4></th>
                                                            <th><h4>Credit/Debit Card</h4></th>
                                                            <th><h4>Other Credit</h4></th>
                                                            <th><h4>Same day Settlement</h4></th>
                                                            <th><h4>Back day Settlement</h4></th>
                                                            <th><h4>Total Creditor</h4></th>
                                                            <th><h4>Received Creditor</h4></th>
                                                            <th><h4>Net/Due Balance</h4></th>
                                                        </tr>
                                                    </thead>

                                                <?php } ?>
                                                <?php
                                                $gross += round($am_br['gross_amount']);
                                                $dis += round($am_br["discount"]);
                                                $neta += round($am_br['price']);
                                                $cas += round($am_br["cash_total"]);
                                                $cheq += round($am_br["cheque_total"]);
                                                $cre += round($am_br["credit_total"]);
                                                $panl += round($am_br["other_total"]);
                                                $sm += round($am_br["same_day"]);
                                                $bk += round($am_br["back_day"]);
                                                $crd_total += round($am_br["creditors_add"][0]["debit"]);
                                                $crd_due += round($am_br["creditors_add"][0]["credit"]);
                                                $new_net += $am_br["payable_amount"]; 
                                                ?>
                                                <tbody>
                                                    <?php
                                                    if ($new_branch == 1) {
                                                        $new_branch = 0;

                                                        foreach ($online_payment as $bop_key) {

                                                            if ($am_br['bid'] == $bop_key["branch_fk"]) {
                                                                $gross += $bop_key["price"];
                                                                $neta += $bop_key["price"];
                                                                ?>
                                                                <tr>
                                                                    <td><a href="<?= base_url(); ?>job_report/branchpayment_details_online?start_date=<?php echo $start_date; ?>&branch=<?= $am_br["bid"] ?>" target="_blank"><b>Online Payment</b></a></td>
                                                                    <td>Rs.<?= $bop_key["price"] ?></td>
                                                                    <td>Rs.0</td>
                                                                    <td>Rs.<?= $bop_key["price"] ?></td>
                                                                    <td>Rs.0</td>
                                                                    <td>Rs.0</td>
                                                                    <td>Rs.0</td>
                                                                    <td>Rs.0</td>
                                                                    <td>Rs.0</td>
                                                                    <td>Rs.0</td>
                                                                    <td>Rs.0</td>
                                                                    <td>Rs.0</td>
                                                                    <td>Rs.0</td>
                                                                </tr>
                                                                <?php
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td> <?php if ($am_br['type'] == "online") { ?>
                                                                <a href="<?= base_url(); ?>job_report/branchpayment_details_online?start_date=<?php echo $start_date; ?>&branch=<?= $brachData['branch'] ?>" target="_blank"><b>Online Payment</b></a>												
                                                            <?php } else if ($am_br['type'] == "phlebo") {
                                                                ?>
                                                                <a href="<?= base_url(); ?>job_report/branchpayment_details?start_date=<?php echo $start_date; ?>&phlebo=<?= $am_br['username']['id'] ?>&branch=<?= $brachData['branch'] ?>" target="_blank"><b><?php echo ucwords($am_br['username']['name']); ?>
                                                                        <?= ($am_br['type'] == "phlebo") ? "(Phlebotomy)" : "" ?>
                                                                    </b></a> <?php } else { ?><a href="<?= base_url(); ?>job_report/branchpayment_details?start_date=<?php echo $start_date; ?>&user=<?= $am_br['username']['id'] ?>&branch=<?= $brachData['branch'] ?>" target="_blank"><b><?php echo ucwords($am_br['username']['name']); ?>
                                                                        <?= ($am_br['type'] == "phlebo") ? "(Phlebotomy)" : "" ?>
                                                                    </b></a> 
                                                            <?php } ?>
                                                        </td>
                                                        <td><?php
                                                            if ($am_br['gross_amount'] != '') {
                                                                echo "Rs. " . round($am_br['gross_amount']);
                                                            } else {
                                                                echo "Rs. 0";
                                                            }
                                                            ?></td>
                                                        <td>Rs.<?= ($am_br["discount"] == "") ? "0.0" : round($am_br["discount"]) ?></td>
                                                        <td><?php echo "Rs." . round($am_br['price']); ?></td>
                                                        <td><?php
                                                            if ($am_br["cash_total"] != '') {
                                                                echo "Rs." . round($am_br["cash_total"]);
                                                            } else {
                                                                echo "Rs.0.0";
                                                            }
                                                            ?></td>
                                                        <td><?php
                                                            if ($am_br["cheque_total"] != '') {
                                                                echo "Rs." . round($am_br["cheque_total"]);
                                                            } else {
                                                                echo "Rs.0.0";
                                                            }
                                                            ?></td>
                                                        <td><?php
                                                            if ($am_br["credit_total"] != '') {
                                                                echo "Rs." . round($am_br["credit_total"]);
                                                            } else {
                                                                echo "Rs.0.0";
                                                            }
                                                            ?></td>
                                                        <td><?php
                                                            if ($am_br["other_total"] != '') {
                                                                echo "Rs." . round($am_br["other_total"]);
                                                            } else {
                                                                echo "Rs.0.0";
                                                            }
                                                            ?></td>
                                                        <td><?php
                                                            if ($am_br["same_day"] != '') {
                                                                echo "Rs." . round($am_br["same_day"]);
                                                            } else {
                                                                echo "Rs.0.0";
                                                            }
                                                            ?></td>
                                                        <td><?php
                                                            if ($am_br["back_day"] != '') {
                                                                echo "Rs." . round($am_br["back_day"]);
                                                            } else {
                                                                echo "Rs.0.0";
                                                            }
                                                            ?></td>
                                                        <?php
                                                        if ($am_br['price'] != '') {
                                                            $price = $am_br['price'];
                                                        } else {
                                                            $price = "0.0";
                                                        }
                                                        if ($am_br['discount'] != '') {
                                                            $discount = $am_br['discount'];
                                                        } else {
                                                            $discount = "0.0";
                                                        }
                                                        $net = ($price - $discount);
                                                        if ($am_br["cash_total"] != '') {
                                                            $cash = $am_br["cash_total"];
                                                        } else {
                                                            $cash = "0.0";
                                                        }
                                                        if ($am_br["cheque_total"] != '') {
                                                            $cheque = $am_br["cheque_total"];
                                                        } else {
                                                            $cheque = "0.0";
                                                        }
                                                        if ($am_br["credit_total"] != '') {
                                                            $credit = $am_br["credit_total"];
                                                        } else {
                                                            $credit = "0.0";
                                                        }
                                                        if ($am_br["same_day"] != '') {
                                                            $same = $am_br["same_day"];
                                                        } else {
                                                            $same = "0.0";
                                                        }
                                                        $netbelance = $am_br["net"];
                                                        if ($netbelance < 0) {
                                                            $netbelance = 0.0;
                                                        }
                                                        $netb += $netbelance;
                                                        ?>
                                                        <td><?php
                                                            if (round($am_br["creditors_add"][0]["debit"]) != '') {
                                                                $same = round($am_br["creditors_add"][0]["debit"]);
                                                            } else {
                                                                $same = "0.0";
                                                            } echo "Rs.".$same;
                                                            ?></td>
                                                        <td><?php $c_due = round($am_br["creditors_add"][0]["credit"]);
                                                            if (round($c_due) != '') {
                                                                $same = round($c_due);
                                                            } else {
                                                                $same = "0.0";
                                                            } echo "Rs.".$same;
                                                            ?></td>
                                                        <td><?php echo "Rs."; echo ($am_br["payable_amount"])?$am_br["payable_amount"]:0.0; ?></td>
                                                    </tr>

                                                <?php } ?>
                                                <tr style="background-color: lightcoral; color: white;">
                                                    <td>Total</td>
                                                    <td><?php echo "Rs." . $gross; ?></td>
                                                    <td><?php echo "Rs." . $dis; ?></td>
                                                    <td><?php echo "Rs." . $neta; ?></td>
                                                    <td><?php echo "Rs." . $cas; ?></td>
                                                    <td><?php echo "Rs." . $cheq; ?></td>
                                                    <td><?php echo "Rs." . $cre; ?></td>
                                                    <td><?php echo "Rs." . $panl; ?></td>
                                                    <td><?php echo "Rs." . $sm; ?></td>
                                                    <td><?php echo "Rs." . $bk; ?></td>
                                                    <td><?php echo "Rs." . $crd_total; ?></td>
                                                    <td><?php echo "Rs." . $crd_due; ?></td>
                                                    <td><?php echo "Rs." . $new_net; ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
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
                                    $(function () {
                                        $('.chosen').chosen();
                                    });
                                    //  $(".chosen-select-deselect").chosen({ allow_single_deselect: true });
                                    // $("#cid").chosen('refresh');
                                    function printData()
                                    {
                                        var divToPrint = document.getElementById("prnt_rpt");
                                        newWin = window.open("");
                                        newWin.document.write(divToPrint.outerHTML);
                                        newWin.print();
                                        newWin.close();
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
    function get_branch1(val) {
        $.ajax({
            url: '<?php echo base_url(); ?>job_master/get_branch',
            type: 'post',
            data: {city: val},
            success: function (data) {
                var test = "<option value=''>Select Branch</option>" + data;
                $("#branch1").html(test);
                $('.chosen').trigger("chosen:updated");

            },
            error: function (jqXhr) {
                $("#branch1").html("");
            },
            complete: function () {
            },
        });
    }
</script>