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
            Job Received Report
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
                        <?php echo form_open("report_master/job_received", array("method" => "GET", "role" => "form")); ?>
                        <div class="row">
                            <div class="col-sm-3">
                                <input type="text" name="start_date" placeholder="Start date" class="form-control datepicker-input" id="date"  value="<?= $start_date ?>" />
                            </div>

                            <div class="col-sm-3">
                                <input type="text" name="end_date" placeholder="End date" class="form-control datepicker-input" id="date1"  value="<?= $end_date ?>" />
                            </div>
<!--                            <div class="col-sm-2"> 
                                <select class="form-control chosen" data-placeholder="Select Type" tabindex="-1" name="type">
                                    <option value="branch" <?php if($type == "branch") { echo "selected"; } ?>>Branch</option>
                                    <option value="client" <?php if($type == "client") { echo "selected"; } ?>>Client</option>
                                    <option value="doctor"  <?php if($type == "doctor") { echo "selected"; } ?>>Doctor</option>
                                </select>
                            </div>-->
                            <div class="col-sm-3"> 
                                <select class="form-control chosen" data-placeholder="Select Branch" tabindex="-1" name="branch">
                                    <option value="" >All Branch</option>
                                    <?php foreach ($branch_list as $branch1) { if(!empty($branch_list_select)) { if(in_array($branch1['id'],$branch_list_select)) { ?>
                                        <option value="<?php echo $branch1['id']; ?>" <?php 
                                            if ($branch == $branch1['id']) {
                                                echo "selected";
                                            }
                                        ?>><?php echo $branch1['branch_name']; ?></option>
                                        <?php } } else { ?>
                                        <option value="<?php echo $branch1['id']; ?>" <?php 
                                            if ($branch == $branch1['id']) {
                                                echo "selected";
                                            }
                                        ?>><?php echo $branch1['branch_name']; ?></option>
                                    <?php } } ?>
                                </select>
                            </div>
<!--                            <div class="col-sm-2"> 
                                <select class="form-control chosen" data-placeholder="Select Wise" tabindex="-1" name="wise">
                                    <option value="day" <?php if($wise == "day") { echo "selected"; } ?>>Day wise</option>
                                    <option value="month" <?php if($wise == "month") { echo "selected"; } ?>>Month wise</option>
                                    <option value="year" <?php if($wise == "year") { echo "selected"; } ?>>Year wise</option>
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
                            <?php if($start_date != '' || $end_date != '' || $branch != '') { ?>
                                <a href="<?php echo base_url(); ?>report_master/job_received_export?start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>&branch=<?php echo $branch; ?>" class="btn-sm btn-primary">Export</a><?php } ?>
                            <div class="table-responsive" id="prnt_rpt">
                                <?php if($start_date != '' || $end_date != '' || $branch != '') { ?>
                                <table id="example4" class="table table-bordered table-striped">
                                    <h2><?php if (trim($start_date) != '' || trim($end_date) != '') { ?><?= $start_date ?> To <?= $end_date ?><?php } ?></h2>
                                    <?php
                                    $temparray = array();
                                    $users = array();
                                    $tep = 0;
                                    $cnt = 1;
                                    foreach ($view_all_data as $am_br) { 
                                        ?>
                                        <?php
                                        
                                        if (!in_array($am_br['bid'], $temparray)) {
                                            if($tep == '1'){ ?>
                                                <tr style="background-color: lightcoral; color: white;">
                                                    <td colspan="6">Total</td>
                                                    <td><?php echo "Rs.".$gross; ?></td>
                                                    <td><?php echo "Rs.".$dis; ?></td>
                                                    <td><?php echo "Rs.".$neta; ?></td>
                                                    <td><?php echo "Rs.".$rec; ?></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><?php echo "Rs.".$due; ?></td>
                                                </tr>
                                            <?php }
                                            $tep ='1';
                                            array_push($temparray, $am_br['bid']);  $gross=0; $dis=0; $neta=0; $rec=0; $due=0; ?>
                                            <thead>
                                                
                                                <tr>
                                                    <th colspan="3"><h3><?php echo $am_br['branch']; ?></h3></th>
                                                </tr>

                                                <tr>
                                                    <th><h4>No.</h4></th>
                                                    <th><h4>Patient Name</h4></th>
                                                    <th><h4>Order Id</h4></th>
                                                    <th><h4>Added By</h4></th>
                                                    <th><h4>Doctor</h4></th>
                                                    <th><h4>Order Date</h4></th>
                                                    <th><h4>Gross Amt</h4></th>
                                                    <th><h4>Discount</h4></th>
                                                    <th><h4>Net Amt</h4></th>
                                                    <th><h4>Received Amt</h4></th>
                                                    <th><h4>Received Type</h4></th>
                                                    <th><h4>Received By</h4></th>
                                                    <th><h4>Received Date</h4></th>
                                                    <th><h4>Due Amt</h4></th>
                                                </tr>
                                            </thead>
                                        <?php } ?>
                                            <?php if (!in_array($am_br['jid'], $users)) {  
                                                $gross += round($am_br['gross_amt']);
                                                $dis += round($am_br["discount"]);
                                                $neta += round($am_br['net_amt']);
                                                $due += round($am_br["due_amt"]);
                                                array_push($users, $am_br['jid']); }
                                                $rec += round($am_br["received_amt"]);
                                            ?>
                                        <tbody>
                                            <tr>
                                                <td><?php echo $cnt; ?></td>
                                                <td><?php echo $am_br['patient']; ?></td>
                                                <td><a href="<?php echo base_url(); ?>job-master/job-details/<?php echo $am_br['jid']; ?>" target="_blank"><?php echo $am_br['order_id']; ?></a></td>
                                                <td><?php echo $am_br['added_name']; ?></td>
                                                <td><?php echo $am_br['doctor_name']; ?></td>
                                                <td><?php echo $am_br['added_date']; ?></td>
                                                <td><?php echo "Rs." .$am_br['gross_amt']; ?></td>
                                                <td><?php echo "Rs." .$am_br['discount']; ?></td>
                                                <td><?php echo "Rs." .$am_br['net_amt']; ?></td>
                                                <td><?php echo "Rs." .$am_br['received_amt']; ?></td>
                                                <td><?php echo $am_br['received_type']; ?></td>
                                                <td><?php echo $am_br['received_name']; ?></td>
                                                <td><?php echo $am_br['received_date']; ?></td>
                                                <td><?php echo "Rs." .$am_br['due_amt']; ?></td>
                                            </tr>
                                            
                                        <?php $cnt++; } if(!empty($view_all_data)) { ?>
                                                <tr style="background-color: lightcoral; color: white;">
                                                    <td colspan="6">Total</td>
                                                    <td><?php echo "Rs.".$gross; ?></td>
                                                    <td><?php echo "Rs.".$dis; ?></td>
                                                    <td><?php echo "Rs.".$neta; ?></td>
                                                    <td><?php echo "Rs.".$rec; ?></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><?php echo "Rs.".$due; ?></td>
                                                </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <?php } ?>
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