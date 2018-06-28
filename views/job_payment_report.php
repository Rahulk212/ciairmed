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
            Jobs Payment Report
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
<!--                        <a style="float:right;margin-right:5px;" href='<?php echo base_url(); ?>job_report/mypayment_with_type' class="btn btn-primary btn-sm" ><strong> My Collection</strong></a>-->
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
                        <?php echo form_open("job_report/jobpayment", array("method" => "GET", "role" => "form")); ?>
                        <div class="row">
                            <div class="col-sm-2">
                                <input type="text" name="start_date" placeholder="Start date" class="form-control datepicker-input" id="date"  value="<?= $start_date ?>" />
                            </div>

                            <div class="col-sm-2">
                                <input type="text" name="end_date" placeholder="End date" class="form-control datepicker-input" id="date1"  value="<?= $end_date ?>" />
                            </div>

                            <div class="col-sm-3"> 
                                <select class="form-control chosen-select" data-placeholder="Select City" tabindex="-1" name="city">
                                    <?php foreach ($city_list as $cities) { ?>
                                        <option value="<?php echo $cities['id']; ?>" <?php
                                        if ($city == $cities['id']) {
                                            echo " selected ";
                                        } if($city == '') {
                                            if($cities['id'] == 1) {
                                                echo "selected";
                                            }
                                        }
                                        ?>><?php echo ucwords($cities['name']); ?></option>
                                            <?php } ?>
                                </select>
                            </div>

                            <div class="col-sm-3">
                                <select class="form-control chosen-select" data-placeholder="Select Type" tabindex="-1" name="type">
                                    <option value="" selected>All Payment Type</option>
                                    <?php
                                    foreach ($payment_type as $type) {
                                        ?>
                                        <option value="<?php echo $type['name']; ?>" <?php
                                        if ($types == $type['name']) {
                                            echo "selected";
                                        }
                                        ?>><?php echo ucwords($type['name']); ?></option>

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
                            <?php if (trim($start_date) != '' || trim($end_date) != '') { ?>
                                <a href="<?php echo base_url(); ?>job_report/job_export?start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>&city=<?php echo $city; ?>&type=<?php echo $types; ?>" class="btn-sm btn-primary">Export</a><?php } ?>
                            <div class="table-responsive">
                                <table id="example4" class="table table-bordered table-striped">

                                    <?php
                                    $temparray = array();
                                    $temptotal = '0';
                                    $test = '0';
                                    $all_total = '0';
                                    foreach ($collecting_amount_branch as $am_br) { //if($am_br['bid'] == $branch['id']) {  
                                        ?>
                                        <?php
                                        
                                        if (!in_array($am_br['bid'], $temparray)) {
                                            $temptotal ='0';
                                            array_push($temparray, $am_br['bid']);
                                            $users = array();
                                            if($test == "1"){
                                                ?>
                                                     <tr>
                                                <td></td>
                                                <td>Sub Total</td>
                                                <td><?php echo "Rs. ".$total; $all_total += $total; ?></td>
                                            </tr><?php 
                                            }
                                            $test = 0;
                                            ?>
                                            <thead>
                                                
                                                <tr>
                                                    <th colspan="3"><h3><?php echo $am_br['branch_name']; ?></h3></th>
                                                </tr>

                                                <tr>
                                                    <th><h4>Name</h4></th>
                                                    <th><h4>type</h4></th>
                                                    <th><h4>cash</h4></th>
                                                </tr>
                                            </thead>
                                        <?php } ?>
                                            
                                        <tbody>
                                            <?php if(!in_array($am_br['aid'], $users) && $test =='1'){ ?>
                                                <tr>
                                                <td></td>
                                                <td>Sub Total</td>
                                                <td><?php echo "Rs. ".$total; $all_total += $total; ?></td>
                                            </tr>
                                        <?php $temptotal ='0'; }  $test='1';  ?>
                                            <tr>
                                                <?php
                                                if (!in_array($am_br['aid'], $users)) {
                                                    $total = 0;
                                                    array_push($users, $am_br['aid']);
                                                    ?>
                                                    <td><b><?php echo $am_br['name']; ?></b></td>
                                                <?php } else {  ?>
                                                    <td></td>
                                                <?php } ?>
                                                <td><?php echo $am_br['payment_type']; ?></td>
                                                <td><?php echo "Rs. " . $am_br['price'];   $total = $total + $am_br['price'];  ?></td>
                                            </tr>
                                            
                                        <?php } if(!empty($collecting_amount_branch)) { ?>
                                            <tr>
                                                <td></td>
                                                <td>Sub Total</td>
                                                <td><?php echo "Rs. ".$total; $all_total += $total; ?></td>
                                            </tr>
                                        
                                            <tr style="background-color: lightcoral; color: white;">
                                                <td>Total</td>
                                                <td></td>
                                                <td><?php echo "Rs. ".$all_total; ?></td>
                                            </tr>
                                            <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!--                        <div style="text-align:right;" class="box-tools">
                                                    <ul class="pagination pagination-sm no-margin pull-right">
                        <?php echo $links; ?>
                                                    </ul>
                                                </div>-->

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

