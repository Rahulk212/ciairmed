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
            Creditors Report
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
                        <?php echo form_open("report_master/creditors/" . $cid, array("method" => "GET", "role" => "form")); ?>
                        <div class="row">
                            <div class="col-sm-2">
                                <input type="text" name="start_date" placeholder="Start date" class="form-control datepicker-input" id="date"  value="<?= $start_date ?>" />
                            </div>

                            <div class="col-sm-2">
                                <input type="text" name="end_date" placeholder="End date" class="form-control datepicker-input" id="date1"  value="<?= $end_date ?>" />
                            </div>
                            <div class="col-sm-2">
                                <input type="submit" name="search" class="btn btn-success" value="Search" />
                            </div>
                        </div>
                        <br>
                        <?php echo form_close(); ?>
                        <!--Search end-->
                        <div class="tableclass">
                            <?php /* if ($start_date != '' || $end_date != '') { ?>
                              <a href="<?php echo base_url(); ?>report_master/export?start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>&branch=<?php echo $branch; ?>&type=<?php echo $type; ?>&wise=<?php echo $wise; ?>" class="btn-sm btn-primary">Export</a><?php } */ ?>
                            <div class="table-responsive" id="prnt_rpt">
                                <table id="example4" class="table table-bordered table-striped">
                                    <h2><?php if (trim($start_date) != '' || trim($end_date) != '') { ?><?= $start_date ?> To <?= $end_date ?><?php } ?></h2>

                                    <thead>

                                        <tr>
                                            <th><h4>No.</h4></th>
                                            <th><h4>Creditors</h4></th>
                                            <th><h4>Job Id</h4></th>
<!--                                            <th><h4>Credit</h4></th>-->
                                            <th><h4>Amount</h4></th>
                                            <th><h4>Added By</h4></th>
                                            <th><h4>Date</h4></th>
                                            <th><h4>Remark</h4></th>
                                            <th><h4>Receive</h4></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $cnt = 1;
                                        $debit = 0;
                                        $credit = 0;
                                        $new_ary = array();
                                        foreach ($view_all_data as $am_br) {
                                            if (empty($am_br["paid_by"])) {
                                                $debit += $am_br['debit'];
                                                $credit += $am_br['credit'];
                                                ?>
                                                <tr>
                                                    <td><?php echo $cnt; ?></td>
                                                    <td><b><?php echo $am_br['name']; ?></b></a></td>
                                                    <td><a href="<?php echo base_url(); ?>job-master/job-details/<?php echo $am_br['job_id']; ?>" target="_blank"><?php echo $am_br['patient_name']; ?>&nbsp;&nbsp;(<?php echo $am_br['order_id']; ?>)</a></td>
        <!--                                                <td><?php
                                                    if ($am_br['credit'] != "") {
                                                        echo "Rs. " . $am_br['credit'];
                                                    } else {
                                                        echo "Rs. 0";
                                                    }
                                                    ?></td>-->
                                                    <td><?php
                                                        if ($am_br['debit'] != "") {
                                                            echo "Rs. " . $am_br['debit'];
                                                        } else {
                                                            echo "Rs. 0";
                                                        }
                                                        ?></td>
                                                    <td><?php echo $am_br['added_by']; ?></td>
                                                    <td><?php echo date("d/m/Y", strtotime($am_br['created_date'])); ?></td>
                                                    <td><?php echo $am_br['remark']; ?></td>
                                                    <td><?php if (empty($am_br["paid_by"])) { ?><button type="button" class="btn btn-sm btn-primary" style="float:right;" onclick="job_payment('<?= $am_br['id'] ?>', '<?= $am_br['debit'] ?>', '<?php echo $am_br['job_id']; ?>');">Receive</button><?php
                                                        } else {
                                                            echo "Paid by " . $am_br["paid_by"][0]["added_by"];
                                                        }
                                                        ?></td>
                                                </tr>

                                                <?php
                                                $cnt++;
                                            } else {
                                                $new_ary[] = $am_br;
                                            }
                                        }
                                        ?>
                                        <tr style="background-color: lightcoral; color: white;">
                                            <td colspan="3">Total</td>
                                            <td><?php echo "Rs. " . round($debit); ?></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr><td colspan="6"></td></tr>
                                        <?php
                                        foreach ($new_ary as $am_br) {
                                            $debit += $am_br['debit'];
                                            $credit += $am_br['credit'];
                                            ?>
                                            <tr>
                                                <td></td>
                                                <td><b><?php echo $am_br['name']; ?></b></a></td>
                                                <td><a href="<?php echo base_url(); ?>job-master/job-details/<?php echo $am_br['job_id']; ?>" target="_blank"><?php echo $am_br['patient_name']; ?>&nbsp;&nbsp;(<?php echo $am_br['order_id']; ?>)</a></td>
    <!--                                                <td><?php
                                                if ($am_br['credit'] != "") {
                                                    echo "Rs. " . $am_br['credit'];
                                                } else {
                                                    echo "Rs. 0";
                                                }
                                                ?></td>-->
                                                <td><?php
                                                    if ($am_br['debit'] != "") {
                                                        echo "Rs. " . $am_br['debit'];
                                                    } else {
                                                        echo "Rs. 0";
                                                    }
                                                    ?></td>
                                                <td><?php echo $am_br['added_by']; ?></td>
                                                <td><?php echo date("d/m/Y", strtotime($am_br['created_date'])); ?></td>
                                                <td><?php echo $am_br['remark']; ?></td>
                                                <td><?php if (empty($am_br["paid_by"])) { ?><button type="button" class="btn btn-sm btn-primary" style="float:right;" onclick="job_payment('<?= $am_br['id'] ?>', '<?= $am_br['debit'] ?>', '<?php echo $am_br['job_id']; ?>');">Receive</button><?php
                                                    } else {
                                                        echo "Paid by " . $am_br["paid_by"][0]["added_by"];
                                                    }
                                                    ?></td>
                                            </tr>

                                            <?php
                                            $cnt++;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
        <div class="modal fade" id="add_credit_modal" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Receive Amount</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="recipient-name" class="control-label">Amount:</label>
                            <input type="number" readonly="" name="amount_credit" id="amount_credit" class="form-control"/>
                            <span style="color:red;" id="amount_credit_error"></span>
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="control-label">Remark:</label>
                            <textarea name="remarks" id="remarks" class="form-control"></textarea>
                            <span style="color:red;" id="remarks_error"></span>
                        </div>
                        <input type="hidden" id="price" name="price"/>
                        <input type="hidden" id="id" name="id"/>
                        <input type="hidden" id="job_fk" name="job_fk"/>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="add_credit" onclick="credit_add_creditors('<?php echo $cid; ?>');">Add</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>

    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>

<script  type="text/javascript">
                            function job_payment(id, price, job_fk) {
                                $("#price").val(price);
                                $("#amount_credit").val(price);
                                $("#id").val(id);
                                $("#job_fk").val(job_fk);
                                $("#add_credit").attr("onclick", "credit_add_creditors('" + id + "','" + job_fk + "')");
                                $('#add_credit_modal').modal('show');
                            }

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
    function credit_add_creditors(cid, job_fk) {
        $("#add_credit").prop("disabled", true);
        var amount = $("#amount_credit").val();
        var remarks = $("#remarks").val();
        $("#amount_credit_error").html("");
        var temp = 1;
        if (amount == "") {
            $("#amount_credit_error").html("Add Amount.");
            temp = 0;
            $("#add_credit").prop("disabled", false);
        }
        if (temp == 1) {
            $("#add_credit").prop("disabled", true);
            $.ajax({
                url: '<?= base_url(); ?>report_master/add_credits',
                type: 'post',
                data: {creditor: cid, amount: amount, remarks: remarks, job_fk: job_fk},
                success: function (data) {
                    if (data != '') {
                        $("#add_credit").prop("disabled", true);
                        setTimeout(function () {
                            window.location.reload();
                        }, 1000);
                    }
                },
                error: function (jqXhr) {
                    $("#add_credit").prop("disabled", false);
                    alert('Oops somthing wrong Tryagain!.');
                }
            });
        }
    }
</script>