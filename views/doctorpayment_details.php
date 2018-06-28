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
            Doctor Ref. Report
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
                        <!--Search end-->
                        <div class="tableclass">
                            <a href="javascript:void(0);" onclick="printData();" class="btn-sm btn-primary">Print Report<br></a><br>
                            <div class="table-responsive" id="prnt_rpt">
                                <table id="example4" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th><h4>Reg. Id</h4></th>
                                            <th><h4>Patient Name</h4></th>
                                            <th><h4>Gross Amt</h4></th>
                                            <th><h4>Discount</h4></th>
                                            <th><h4>Net Amt</h4></th>
                                            <th><h4>Due Amt</h4></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <tr>
                                            <td colspan="7"><b>Referred By : <?php echo $doctor_name[0]['full_name']; ?></b></td>
                                        </tr>
                                        <?php
                                        $i = 1;
                                        $gross=0; $dis=0; $neta=0; $cas=0; $due = 0;
                                        foreach ($collecting_amount_branch as $am_br) {
                                            $gross += $am_br['price'];
                                            $dis += $am_br["discount"];
                                            $neta += $am_br['price']-$am_br["discount"]-$am_br["due"];
                                            $due +=$am_br['due'];
                                            ?>
                                            <tr>
                                                <td><?= $i++ ?></td>
                                                <td><a href="<?php echo base_url(); ?>job-master/job-details/<?= $am_br["id"] ?>"><?= ucfirst($am_br["id"]) ?></a></td>
                                                <td><?= ucfirst($am_br["full_name"]) ?></td>
                                                <td><?php if($am_br['price'] == '') { echo "Rs. 0"; } else { echo "Rs. " . $am_br['price']; } ?></td>
                                                <td>Rs.<?= ($am_br["discount"] == "") ? "0.0" : round($am_br["discount"],2) ?></td>
                                                <td><?php echo "Rs.".round(($am_br['price']-$am_br["discount"]-$am_br['due']),2); ?></td>
                                                <td><?php echo "Rs.".round(($am_br['due']),2); ?></td>
                                            </tr>
                                        <?php } ?>
                                            <tr style="background-color: lightcoral; color: white;">
                                                <td colspan="3">Total</td>
                                                <td><?php echo " Rs." . number_format((float) $gross, 2, '.', ''); ?></td>
                                                <td><?php echo " Rs." . number_format((float) $dis, 2, '.', ''); ?></td>
                                                <td><?php echo " Rs." . number_format((float) $neta, 2, '.', ''); ?></td>
                                                <td><?php echo " Rs." . number_format((float) $due, 2, '.', ''); ?></td>
                                            </tr>
                                        <?php if (empty($collecting_amount_branch)) { ?>
                                            <tr><td colspan="7"><center>Data not available.</center></td></tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
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
</script>

