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
            Payment Report
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
                        <?php echo form_open("job_report/payment", array("method" => "GET", "role" => "form")); ?>
                        <div class="row">
                            <div class="col-sm-2">
                                <input type="text" name="start_date" placeholder="Start date" class="form-control datepicker-input" id="date"  value="<?= $start_date ?>" />
                            </div>

                            <div class="col-sm-2">
                                <input type="text" name="end_date" placeholder="End date" class="form-control datepicker-input" id="date1"  value="<?= $end_date ?>" />
                            </div>

                            <div class="col-sm-3"> 
                                <select class="form-control chosen-select" data-placeholder="Select Branch" tabindex="-1" name="branch">
                                    <option value="" >All Branch</option>
                                    <?php
                                    foreach ($branch_list as $branchb) {
                                        foreach ($branchlist as $b) {
                                            if ($b['id'] == $branchb['branch_fk']) {
                                                ?>
                                                <option value="<?php echo $b['id']; ?>" <?php if($branch == $b['id']) { echo " selected "; } ?>><?php echo ucwords($b['branch_name']) . " - " . $b['branch_code']; ?></option>

                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-sm-3">
                                <select class="form-control chosen-select" data-placeholder="Select Type" tabindex="-1" name="type">
                                    <option value="" selected>All Payment Type</option>
                                    <?php
                                    foreach ($payment_type as $type) {
                                                ?>
                                                <option value="<?php echo $type['name']; ?>" <?php if($types==$type['name']) { echo "selected"; } ?>><?php echo ucwords($type['name']); ?></option>

                                                <?php
                                            }
                                    ?>
                                </select>
                            </div>
                            
                            <div class="col-sm-2">
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
                                            <th>Date</th>
                                            <th>Job No</th>
                                            <th>Amount</th>
                                            <th>Received type</th>
                                            <th>Received By</th>
                                            <th>Branch</th>

                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        $i=1;
                                        $total = 0.0;
                                        foreach ($collecting_amount_branch as $am_br) {
                                            $total +=$am_br["amount"];
                                            ?>
                                            <tr>
                                                <td><?= $i++ ?></td>
                                                <td><?= ucfirst($am_br["createddate"]) ?></td>
                                                <td><a href="<?php echo base_url("job-master/job-details/" . $am_br['job_id']); ?>" ><?= ucfirst($am_br["order_id"]) ?></a></td>
                                                <td>Rs.<?= ucfirst($am_br["amount"]) ?></td>
                                                <td><?= ucfirst($am_br["type"]) ?></td>
                                                <td><?php echo ucfirst($am_br['name']); ?></td>
                                                <td><?php echo ucfirst($am_br['branch_name']); ?></td>
                                            </tr>
                                        <?php } ?>
                                        <?php if (empty($collecting_amount_branch)) { ?>
                                            <tr><td colspan="7"><center>Data not available.</center></td></tr>
                                    <?php } ?>


                                    </tbody>


                                </table>
                                <div class="col-sm-6"> 
                                    <table id="example4" class="table table-bordered table-striped">
                                        <h2>Report From <?= $start_date ?> To <?= $end_date ?></h2>
                                        <tr>
                                            <td><b>Collected Amount </b></td>
                                            <td><?php echo " Rs." . number_format((float) $total, 2, '.', ''); ?></td>
                                        </tr>
                                    </table>
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

