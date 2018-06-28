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
            Sample Collected Jobs
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li>Sample Collected Jobs</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <div class="col-xs-12">
                            <div class="col-xs-2 pull-right">
                                <div class="form-group">
                                </div>
                            </div>
                        </div>
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
                        <div class="tableclass">
                            <?php echo form_open("add_result/sample_collected_list", array("method" => "GET", "role" => "form")); ?>
                            <div class="table-responsive">
                                <table id="example4" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Customer Name</th>
                                            <th>Mobile No.</th>
                                            <th>Test/Package Name</th>
                                            <th>Date</th>
                                            <th>Payable Amount / Price</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><span style="color:red;">*</span></td>
                                            <td>
                                                <select class="form-control chosen-select" tabindex="-1" data-placeholder="Select Customer" name="user">
                                                    <option value="">Select Customer</option>
                                                    <?php foreach ($customer as $cat) { ?>
                                                        <option value="<?php echo $cat['id']; ?>" <?php
                                                        if ($user == $cat['id']) {
                                                            echo "selected";
                                                        }
                                                        ?> ><?php echo ucwords($cat['full_name']); ?>(<?php echo $cat['mobile']; ?>)</option>
                                                            <?php } ?>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="mobile" class="form-control" id="date"  value="<?php
                                                if (isset($mobile)) {
                                                    echo $mobile;
                                                }
                                                ?>" />
                                            </td>
                                            <td></td>
                                            <td>
<!--                                                <input type="text" name="date" placeholder="select date" class="form-control datepicker-input" id="date"  value="<?php
                                                if (isset($date)) {
                                                    echo $date;
                                                }
                                                ?>" />-->
                                            </td>
                                            <td>
<!--                                                <input type="text" placeholder="Amount" class="form-control" name="p_amount" value="<?php echo $p_amount; ?>"/>-->
                                            </td>
                                            <td style="width:7%">
                                                <input type="submit" name="search" class="btn btn-success" value="Search" />
                                            </td>
                                        </tr>
                                        <?php
                                        $cnt = 1;
                                        foreach ($query as $row) {
                                            ?>
                                            <tr>
                                                <td><?php
                                                    echo $cnt + $pages . " ";
                                                    if ($row['views'] == '0') {
                                                        echo '<span class="round round-sm blue"> </span>';
                                                    }
                                                    ?> 
                                                </td>
                                                <td><a href="<?php echo base_url(); ?>customer-master/customer-all-details/<?php echo $row['cid']; ?>"><?php echo ucwords($row['full_name']); ?></a><br><?php echo '[' . $row['relation'] . ']'; ?></td>
                                                <td><?= $row['mobile'] ?></td>
                                                <td><?php
                                                    $testname = explode(",", $row['testname']);
                                                    foreach ($testname as $test) {
                                                        echo ucwords($test) . "<br>";
                                                    } $packagename = explode(",", $row['packagename']);
                                                    foreach ($packagename as $package) {
                                                        echo ucwords($package) . "<br>";
                                                    }
                                                    ?>
                                                </td>
                                                <td><?php echo ucwords($row['date']); ?></td>
                                                <td><?php
                                                    if ($row['payable_amount'] == "") {
                                                        echo "Rs. 0";
                                                    } else {
                                                        echo "Rs. " . $row['payable_amount'];
                                                    }
                                                    ?> /<?= " Rs." . $row["price"] ?>
                                                    <?php
                                                    if ($row["cut_from_wallet"] != 0) {
                                                        echo "<br><small>(Rs." . $row["cut_from_wallet"] . " Debited from wallet)</small>";
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <a  href='<?php echo base_url(); ?>add_result/test_details/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="View Test Details" > <span class="label label-primary"><i class="fa fa-eye"> </i></span> </a>
                                                </td>
                                            </tr>
                                            <?php
                                            $cnt++;
                                        }if (empty($query)) {
                                            ?>
                                            <tr>
                                                <td colspan="8">No records found</td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php echo form_close(); ?>
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