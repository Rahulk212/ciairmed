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
            Branch Collection Report
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
                        <?php echo form_open("branch_report/report", array("method" => "GET", "role" => "form")); ?>
                        <div class="row">
                            <div class="col-sm-3">
                                <input type="text" name="start_date" placeholder="Start date" class="form-control datepicker-input" id="date"  value="<?= $start_date ?>" />
                            </div>

                            <div class="col-sm-3">
                                <input type="text" name="end_date" placeholder="End date" class="form-control datepicker-input" id="date1"  value="<?= $end_date ?>" />
                            </div>
                            <div class="col-sm-3"> 
                                <select class="form-control chosen" data-placeholder="Select City" tabindex="-1" name="city">
                                    <?php foreach ($city_list as $cities) { ?>
                                        <option value="<?php echo $cities['id']; ?>" <?php
                                            if ($city == $cities['id']) {
                                                echo "selected";
                                            } if($city == '') { if($cities['id'] == 1) {
                                                    echo "selected";
                                                }
                                            }
                                        ?>><?php echo $cities['name']; ?></option>
                                            <?php } ?>
                                </select>
                            </div>

<!--                            <div class="col-sm-3"> 
                                <select class="form-control chosen" data-placeholder="Select Branch" tabindex="-1" name="branch">
                                    <option value="" >All Branch</option>
                                    <?php foreach ($branch_list as $branch1) { ?>
                                        <option value="<?php echo $branch1['id']; ?>" <?php
                                            if ($branch == $branch1['id']) {
                                                echo "selected";
                                            }
                                        ?>><?php echo $branch1['branch_name']; ?></option>
                                            <?php } ?>
                                </select>
                            </div>-->
                            <div class="col-sm-3">
                                <input type="submit" name="search" class="btn btn-success" value="Search" />
                            </div>
                        </div>
                        <br>
                        <?php echo form_close(); ?>
                        <!--Search end-->
                        <div class="tableclass">
                            <?php if (trim($start_date) != '' || trim($end_date) != '') { ?>
                                <a href="<?php echo base_url(); ?>branch_report/export?start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>&city=<?php echo $city; ?>" class="btn-sm btn-primary">Export</a><?php } ?>
                            <div class="table-responsive" id="prnt_rpt">
                                <table id="example4" class="table table-bordered table-striped">
                                    <h2><?php if (trim($start_date) != '' || trim($end_date) != '') { ?><?= $start_date ?> To <?= $end_date ?><?php } ?></h2>
                                    <?php
                                    $temparray = array();
                                    $tep = 0;
                                    foreach ($collecting_amount_branch as $am_br) { //if($am_br['bid'] == $branch['id']) {  
                                        ?>
                                        <?php
                                        if (!in_array($am_br['tid'], $temparray)) {
                                            if ($tep == '1') {
                                                ?>
                                                <tbody>
                                                    <tr style="background-color: lightcoral; color: white;">
                                                        <td>Total</td>
                                                        <td><?php echo "Rs." . $gross; ?></td>
                                                        <td><?php echo "Rs." . round($dis, 2); ?></td>
                                                        <td><?php echo "Rs." . $neta; ?></td>
                                                        <td><?php echo "Rs." . round($due, 2); ?></td>
                                                    </tr>
                                                </tbody>
                                                <?php
                                            }
                                            $tep = '1';
                                            array_push($temparray, $am_br['tid']);
                                            $gross = 0;
                                            $dis = 0;
                                            $neta = 0;
                                            $cas = 0;
                                            $due = 0;
                                            $cash = 0;
                                            $cheque = 0;
                                            $debit = 0;
                                            $credit = 0;
                                            $ahswipe = 0;
                                            $online = 0;
                                            $wallet = 0;
                                            $payum = 0;
                                            $paytm = 0;
                                            $same = 0;
                                            $back = 0;
                                            ?>
                                            <thead>

                                                <tr>
                                                    <th colspan="16"><h3><?php echo $am_br['name']; ?></h3></th>
                                                </tr>

                                                <tr>
                                                    <th><h4>Branch Name</h4></th>
                                                    <th><h4>Gross Amt</h4></th>
                                                    <th><h4>Discount</h4></th>
                                                    <th><h4>Net Amt</h4></th>
                                                    <th><h4>Due Amt</h4></th>
                                                    <th><h4>Cash Amt</h4></th>
                                                    <th><h4>Cheque Amt</h4></th>
                                                    <th><h4>Debit Amt</h4></th>
                                                    <th><h4>Credit Amt</h4></th>
                                                    <th><h4>Swipe AXIS/HDFC Amt</h4></th>
                                                    <th><h4>Online Amt</h4></th>
                                                    <th><h4>Wallet Amt</h4></th>
                                                    <th><h4>PayUmoney Amt</h4></th>
                                                    <th><h4>PayTm Amt</h4></th>
                                                    <th><h4>Same day Amt</h4></th>
                                                    <th><h4>Back day Amt</h4></th>
                                                </tr>
                                            </thead>
                                        <?php } ?>
                                        <?php
                                        $gross += $am_br['price'];
                                        $dis += $am_br["discount"];
                                        $neta += $am_br['price'] - $am_br["discount"];
                                        $due += $am_br['due'];
                                        $cash += $am_br['cash_total'];
                                        $cheque += $am_br['cheque_total'];
                                        $debit += $am_br['debit_total'];
                                        $credit += $am_br['credit_total'];
                                        $ahswipe += $am_br['axis_hdfc'];
                                        $online += $am_br['online_total'];
                                        $wallet += $am_br['wallet_total'];
                                        $payum += $am_br['pau_total'];
                                        $paytm += $am_br['paytm_total'];
                                        $same += $am_br['same_day'];
                                        $back += $am_br['back_day'];
                                        ?>
                                        <tbody>
                                            <tr>
                                                <td><b><?php echo $am_br['branch_name']; ?></b></a></td>
                                                <td><?php echo "Rs." . round($am_br['price']); ?></td>
                                                <td><?php echo "Rs." . round($am_br['discount']); ?></td>
                                                <td><?php echo "Rs." . round(($am_br['price'] - $am_br["discount"])); ?></td>
                                                <td><?php echo "Rs." . round(($am_br['due'])); ?></td>
                                                <td><?php echo "Rs." . round(($am_br['cash_total'])); ?></td>
                                                <td><?php echo "Rs." . round(($am_br['cheque_total'])); ?></td>
                                                <td><?php echo "Rs." . round(($am_br['debit_total'])); ?></td>
                                                <td><?php echo "Rs." . round(($am_br['credit_total'])); ?></td>
                                                <td><?php echo "Rs." . round(($am_br['axis_hdfc'])); ?></td>
                                                <td><?php echo "Rs." . round(($am_br['online_total'])); ?></td>
                                                <td><?php echo "Rs." . round(($am_br['wallet_total'])); ?></td>
                                                <td><?php echo "Rs." . round(($am_br['pau_total'])); ?></td>
                                                <td><?php echo "Rs." . round(($am_br['paytm_total'])); ?></td>
                                                <td><?php echo "Rs." . round(($am_br['same_day'])); ?></td>
                                                <td><?php echo "Rs." . round(($am_br['back_day'])); ?></td>
                                                <?php
                                                /* if ($am_br['price'] != '') {
                                                    $price = $am_br['price'];
                                                } else {
                                                    $price = "0.0";
                                                }
                                                if ($am_br['discount'] != '') {
                                                    $discount = $am_br['discount'];
                                                } else {
                                                    $discount = "0.0";
                                                }
                                                $net = ($price - $discount); */
                                                ?>
                                            </tr>

                                        <?php } if (!empty($collecting_amount_branch)) { ?>
                                            <tr style="background-color: lightcoral; color: white;">
                                                <td>Total</td>
                                                <td><?php echo "Rs." . round($gross); ?></td>
                                                <td><?php echo "Rs." . round($dis); ?></td>
                                                <td><?php echo "Rs." . round($neta); ?></td>
                                                <td><?php echo "Rs." . round($due); ?></td>
                                                
                                                <td><?php echo "Rs." . round($cash); ?></td>
                                                <td><?php echo "Rs." . round($cheque); ?></td>
                                                <td><?php echo "Rs." . round($debit); ?></td>
                                                <td><?php echo "Rs." . round($credit); ?></td>
                                                <td><?php echo "Rs." . round($ahswipe); ?></td>
                                                <td><?php echo "Rs." . round($online); ?></td>
                                                <td><?php echo "Rs." . round($wallet); ?></td>
                                                <td><?php echo "Rs." . round($payum); ?></td>
                                                <td><?php echo "Rs." . round($paytm); ?></td>
                                                <td><?php echo "Rs." . round($same); ?></td>
                                                <td><?php echo "Rs." . round($back); ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
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
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bSort": true,
            "bInfo": false,
            "bAutoWidth": false,
            "iDisplayLength": 100
        });
    });
    var cities = $("#city_id").val();
    $.ajax({
        url: '<?php echo base_url(); ?>Admin/get_refered_by',
        type: 'post',
        data: {val: cities, selected: '<?= $doctor ?>'},
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
    function change_citys(value) {
        $.ajax({
            url: '<?php echo base_url(); ?>Admin/get_refered_by',
            type: 'post',
            data: {val: value},
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
    }
    function cut_doctor_save(did) {
        var cut_doc = $("#doctor_cut_" + did).val();
        $.ajax({
            url: '<?php echo base_url(); ?>doctor_report/doctor_cut',
            type: 'post',
            data: {did: did, cut_val: cut_doc},
            success: function (data) {
                location.reload();
            },
            error: function (jqXhr) {
                alert("Please Try Again!");
            },
        });
    }
    function doctors_handover(did, amount) {
        if (amount == '0') {
            alert("Amount is 0.");
        } else {
            var confo = confirm("Are you sure ?");
            if (confo == true) {
                $("#remark_model").modal('show');
                $("#doctor_id").val(did);
                $("#doctor_amount").val(amount);
            }
        }
    }
    function pay_doctors() {
        var did = $("#doctor_id").val();
        var amount = $("#doctor_amount").val();
        var remark = $("#remark").val();
        $.ajax({
            url: '<?php echo base_url(); ?>doctor_report/doctor_pay',
            type: 'post',
            data: {did: did, amount: amount, remarks: remark},
            success: function (data) {
                location.reload();
            },
            error: function (jqXhr) {
                alert("Please Try Again!");
            },
        });
    }
</script>