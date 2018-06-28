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
            Doctor Payment Report
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li>Payment Report</li>

        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="modal fade" id="remark_model" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Handover Payment</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="recipient-name" class="control-label">Remark :</label>
                            <textarea name="remark" id="remark" class="form-control"></textarea>
                            <input type="hidden" name="doctor_id" id="doctor_id">
                            <input type="hidden" name="doctor_amount" id="doctor_amount">
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="control-label">Sales Person :</label>
                            <select class="form-control" name="sales_person" id="sales_person_id">
                                <option>Select sales person</option>
                                <?php foreach ($sales_person_list as $sales) { ?>
                                <option value="<?php echo $sales['id']; ?>"><?php echo $sales['first_name']." ".$sales['last_name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="pay_doctors();" class="btn btn-primary">Pay</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
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
                        <?php echo form_open("doctor_report/payment", array("method" => "GET", "role" => "form")); ?>
                        <div class="row">
                            <div class="col-sm-2">
                                <input type="text" name="start_date" placeholder="Start date" class="form-control datepicker-input" id="date"  value="<?= $start_date ?>" />
                            </div>

                            <div class="col-sm-2">
                                <input type="text" name="end_date" placeholder="End date" class="form-control datepicker-input" id="date1"  value="<?= $end_date ?>" />
                            </div>

                            <div class="col-sm-2"> 
                                <select class="form-control chosen" data-placeholder="Select City" tabindex="-1" name="city" id="city_id" onchange="change_citys(this.value);">
                                    <option value="" >All City</option>
                                    <?php foreach ($city_list as $city1) { ?>
                                        <option value="<?php echo $city1['id']; ?>" <?php
                                        if ($city != '') {
                                            if ($city == $city1['id']) {
                                                echo "selected";
                                            }
                                        } else if ($city1['id'] == 1) {
                                            echo "selected";
                                        }
                                        ?>><?php echo $city1['name']; ?></option>
                                            <?php } ?>
                                </select>
                            </div>
                            <div class="col-sm-2"> 
                                <select class="form-control chosen" data-placeholder="Select Branch" tabindex="-1" name="branch" id="branch_id">
                                    <option value="" >All Branch</option>
                                    <?php foreach ($branch_list as $branch1) { ?>
                                        <option value="<?php echo $branch1['id']; ?>" <?php
                                            if ($branch == $branch1['id']) {
                                                echo "selected";
                                            }
                                        ?>><?php echo $branch1['branch_name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-sm-2"> 
                                <select class="form-control chosen" data-placeholder="Select Doctor" tabindex="-1" name="doctor" id="referral_by">
                                    <option value="" >All Doctor</option>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <input type="submit" name="search" class="btn btn-success" value="Search" />
                            </div>
                        </div>
                        <br>
                        <?php echo form_close(); ?>
                        <!--Search end-->
                        <div class="tableclass"><?php if (trim($start_date) != '' || trim($end_date) != '') { ?>
                                <a href="<?php echo base_url(); ?>doctor_report/export?start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>&city=<?php echo $city; ?>&branch=<?php echo $branch; ?>" class="btn-sm btn-primary">Export</a><?php } ?>
                            <div class="table-responsive" id="prnt_rpt">
                                <table id="example3" class="table table-bordered table-striped">
                                    <h2><?php if (trim($start_date) != '' || trim($end_date) != '') { ?><?= $start_date ?> To <?= $end_date ?><?php } ?></h2>
                                    <?php
                                    $temparray = array();
                                    $tep = 0;
                                    $cnt = 1;
                                    foreach ($collecting_amount_branch as $am_br) { //if($am_br['bid'] == $branch['id']) {  
                                        ?>
                                        <?php
                                        if (!in_array($am_br['tid'], $temparray)) {
                                            if ($tep == '1') {
                                                ?>
                                                <tbody>
                                                    <tr style="background-color: lightcoral; color: white;">
                                                        <td>Total</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td><?php echo $job_count; ?></td>
                                                <td><?php echo "Rs." . $gross; ?></td>
                                                <td><?php echo "Rs." . round($dis, 2); ?></td>
                                                <td><?php echo "Rs." . $neta; ?></td>
                                                <td><?php echo "Rs." . round($due, 2); ?></td>
                                                <td><?php echo "Rs." . round($pays); ?></td>
                                                <td></td>
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
                                            $pays = 0;
                                            $due = 0;
                                            $job_count = 0;
                                            ?>
                                            <thead>

                                                <tr>
                                                    <th colspan="10"><h3><?php echo $am_br['name']; ?></h3></th>
                                                </tr>

                                                <tr>
                                                    <th><h4>No.</h4></th>
                                                    <th><h4>Doctor Name</h4></th>
                                                    <th><h4>Sales Person</h4></th>
                                                    <th><h4>Doctor Cut(%)</h4></th>
                                                    <th><h4>Total Jobs</h4></th>
                                                    <th><h4>Gross Amt</h4></th>
                                                    <th><h4>Discount</h4></th>
                                                    <th><h4>Net Amt</h4></th>
                                                    <th><h4>Due Amt</h4></th>
                                                    <th><h4>Pay</h4></th>
                                                    <th><h4>Handover</h4></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                        <?php } ?>
                                        <?php
                                        $gross += $am_br['price'];
                                        $dis += $am_br["discount"];
                                        $neta += $am_br['price'] - $am_br["discount"] - $am_br["due"];
                                        $due += $am_br['due'];
                                        $pays += (($am_br['price'] - $am_br["discount"] - $am_br["due"]) * $am_br['cut']) / 100;
                                        $job_count += $am_br['job_count'];
                                        ?>
                                        
                                            <tr>
                                                <td><?php echo $cnt; ?></td>
                                                <td><a href="<?= base_url(); ?>doctor_report/show_all_job?start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>&doctor=<?= $am_br["did"] ?>&branch=<?php echo $am_br['tid'] ?>" target="_blank"><b><?php echo $am_br['full_name'] . " - " . $am_br['mobile']; ?></b></a></td>
                                                <td><?php echo $am_br['sales_name']; ?></td>
                                                <td><input class="form-control" type="text" name="doctor_cut" id="doctor_cut_<?php echo $am_br['did']; ?>" value="<?php echo $am_br['cut']; ?>" style="width:50%; float:left; margin-right:10px; "><input type="button" value="edit" class="btn btn-primary" onclick="cut_doctor_save('<?php echo $am_br['did']; ?>');"></td>
                                                <td><?php echo $am_br['job_count']; ?></td>
                                                <td><?php
                                                    if ($am_br['price'] == '') {
                                                        echo "Rs. 0";
                                                    } else {
                                                        echo "Rs. " . $am_br['price'];
                                                    }
                                                    ?></td>
                                                <td>Rs.<?= ($am_br["discount"] == "") ? "0.0" : round($am_br["discount"], 2) ?></td>
                                                <td><?php echo "Rs." . round(($am_br['price'] - $am_br["discount"] - $am_br['due']), 2); ?></td>
                                                <td><?php if($am_br['due'] != '') { echo "Rs." . round(($am_br['due']), 2); } else { echo "Rs. 0.0"; } ?></td>
                                                <td><?php echo "Rs." . round((($am_br['price'] - $am_br["discount"] - $am_br['due']) * $am_br['cut']) / 100); ?></td>
                                                <td><a href="javascript:void(0)" onclick="doctors_handover('<?php echo $am_br['did'] ?>', '<?php echo round((($am_br['price'] - $am_br["discount"] - $am_br['due']) * $am_br['cut']) / 100); ?>','<?php echo $am_br['sales_id']; ?>')" class="btn btn-primary">PAY</a></td>
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
                                                ?>
                                            </tr>

                                        <?php $cnt++; } if (!empty($collecting_amount_branch)) { ?>
                                            <tr style="background-color: lightcoral; color: white;">
                                                <td>Total</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td><?php echo $job_count; ?></td>
                                                <td><?php echo "Rs." . $gross; ?></td>
                                                <td><?php echo "Rs." . round($dis, 2); ?></td>
                                                <td><?php echo "Rs." . $neta; ?></td>
                                                <td><?php echo "Rs." . round($due, 2); ?></td>
                                                <td><?php echo "Rs." . round($pays); ?></td>
                                                <td></td>
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
            "bLengthChange": false,
            "bFilter": true,
            "bSort": false,
            "bInfo": false,
            "bAutoWidth": false,
            "iDisplayLength": 1000
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
if(value=="333"){
            testcity=1;
        }
        if(value=="1510"){;
            testcity=5;
        }
         
          if(value=="345"){
            testcity=6;
        }
        if(value=="476"){
            testcity=8;
        }
        if(value=="446"){
            testcity=4;
        }
        if(value=="396"){
            testcity=2;
        }
        if(value=="406"){
            testcity=3;
        }
        if(value=="1475"){
            testcity=7;
        }
$.ajax({
            url: '<?php echo base_url(); ?>job_master/get_branch',
            type: 'post',
            data: {city: value},
            success: function (data) {
                $("#branch_id").html("<option value=''>Select Branch</option>"+data);
                jQuery('.chosen').trigger("chosen:updated");
            },
            error: function (jqXhr) {
                $("#branch_id").html("");
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
    function doctors_handover(did, amount,sid) {
        if (amount == '0') {
            alert("Amount is 0.");
        } else {
            $("#sales_person_id").val(sid);
            $("#remark_model").modal('show');
            $("#doctor_id").val(did);
            $("#doctor_amount").val(amount);
        }
    }
    function pay_doctors() {
        var did = $("#doctor_id").val();
        var amount = $("#doctor_amount").val();
        var sales = $("#sales_person_id").val();
        var remark = $("#remark").val();
        var confo = confirm("Are you sure ?");
        if (confo == true) {
            $.ajax({
                url: '<?php echo base_url(); ?>doctor_report/doctor_pay',
                type: 'post',
                data: {did: did, amount: amount, remarks: remark, sales: sales},
                success: function (data) {
                    location.reload();
                },
                error: function (jqXhr) {
                    alert("Please Try Again!");
                },
            });
        }
    }
</script>