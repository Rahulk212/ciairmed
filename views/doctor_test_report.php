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
            Doctor Test Report
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
                        <?php echo form_open("report_master/doctor_test_export", array("method" => "GET", "role" => "form")); ?>
                        <div class="row">
                            <div class="col-sm-3">
                                <input type="text" name="start_date" placeholder="Start date" class="form-control datepicker-input" id="date"  value="<?= $start_date ?>" />
                            </div>

                            <div class="col-sm-3">
                                <input type="text" name="end_date" placeholder="End date" class="form-control datepicker-input" id="date1"  value="<?= $end_date ?>" />
                            </div>
                            
<!--                            <div class="col-sm-3"> 
                                <select class="form-control chosen-select" data-placeholder="Select city" tabindex="-1" name="city" onchange="get_branch1(this.value);" id="cities">
                                    <option value="" >All City</option>
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
                            </div>-->

                            <div class="col-sm-3"> 
                                <select class="form-control chosen" data-placeholder="Select Branch" tabindex="-1" name="branch" id="branch1">
                                    <option value="" >All Branch</option>
                                    <?php foreach ($branch_list as $branchh) { if(!empty($branchs)) { if(in_array($branchh['id'],$branchs)) { ?>
                                    <option value="<?php echo $branchh['id']; ?>" <?php
                                        if ($branch == $branchh['id']) {
                                            echo " selected ";
                                        } if($branch == '') {
                                            if($branchh['id'] == 2) {
                                                echo "selected";
                                            }
                                        }
                                        ?>><?php echo ucwords($branchh['branch_name']) . " - " . $branchh['branch_code']; ?></option>
                                    <?php } ?>
                                    <?php } else { ?>
                                        <option value="<?php echo $branchh['id']; ?>" <?php
                                        if ($branch == $branchh['id']) {
                                            echo " selected ";
                                        } if($branch == '') {
                                            if($branchh['id'] == 2) {
                                                echo "selected";
                                            }
                                        }
                                        ?>><?php echo ucwords($branchh['branch_name']) . " - " . $branchh['branch_code']; ?></option>
                                    <?php } } ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <input type="submit" name="search" class="btn btn-success" value="Export" />
                            </div>
                        </div>
                        <br>
                        <?php echo form_close(); ?>
                        <!--Search end-->
                        <div class="tableclass">
                            <?php if (trim($start_date) != '' || trim($end_date) != '') { ?>
                                <a href="javascript:void(0);" onclick="printData();" class="btn-sm btn-primary">Print Report</a><?php } ?>
                            <div class="table-responsive" id="prnt_rpt">
                                <table id="example4" class="table table-bordered table-striped">
                                    <h2><?php if (trim($start_date) != '' || trim($end_date) != '') { ?><?= $start_date ?> To <?= $end_date ?><?php } ?></h2>
                                    <?php
                                    $temparray = array();
                                    $tep = 0;
                                    $cnt =1;
                                    foreach ($collecting_amount_branch as $am_br) { //if($am_br['bid'] == $branch['id']) {  
                                        ?>
                                        <?php
                                        
                                        if (!in_array($am_br['did'], $temparray)) {
                                            array_push($temparray, $am_br['did']); ?>
                                            <thead>
                                                
                                                <tr>
                                                    <th colspan="3"><h3><?php echo $am_br['dname']; ?></h3></th>
                                                </tr>

                                                <tr>
                                                    <th><h4>Id</h4></th>
                                                    <th><h4>Date</h4></th>
                                                    <th><h4>Order Id</h4></th>
                                                    <th><h4>Patient</h4></th>
                                                    <th><h4>Test</h4></th>
                                                    <th><h4>Price</h4></th>
                                                    <th><h4>Employee</h4></th>
                                                    <th><h4>Branch</h4></th>
                                                </tr>
                                            </thead>
                                        <?php } ?>
                                        <tbody>
                                            <tr>
                                                <td><?php echo $cnt; ?></td>
                                                <td><?php echo date("d-M-Y",strtotime($am_br['date'])); ?></td>
                                                <td><?php echo $am_br["order_id"]; ?></td>
                                                <td><?php echo $am_br['cname']; ?></td>
                                                <td><?php echo $am_br["tname"]; ?></td>
                                                <td><?php echo "Rs. ".$am_br["price"]; ?></td>
                                                <td><?php echo $am_br["aname"]; ?></td>
                                                <td><?php echo $am_br["bname"]; ?></td>
                                            </tr>
                                            
                                        <?php $cnt++; } ?>
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
                var test = "<option value=''>Select Branch</option>"+data;
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