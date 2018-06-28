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
            Visitor List
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li>Visitor List</li>

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
<!--                                    <a style="float:right;" href='<?php echo base_url(); ?>job_master/export_csv/1' class="btn btn-primary btn-sm" ><i class="fa fa-download" ></i><strong > Export To CSV</strong></a>-->
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
                            <?php echo form_open("job-master/pending-list", array("method" => "GET", "role" => "form")); ?>
                            <div class="table-responsive">
                            <table id="example4" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Order Id</th>
                                        <th>Customer Name</th>
                                        <th>Area</th>
                                        <th>Sample Date</th>
                                        <th>Sample Time</th>
                                        <th>Phlebo</th>
                                        <th>Booking Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>


                                  <?php /*  <tr>
                                        <td><span style="color:red;">*</span></td>
                                        <td>
                                            <select class="form-control chosen-select" tabindex="-1" data-placeholder="Select Customer" name="user">
                                                <option value="">Select Customer</option>
                                                <?php foreach ($customer as $cat) { ?>
                                                    <option value="<?php echo $cat['id']; ?>" <?php
                                                    if ($customerfk == $cat['id']) {
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
                                        <td>
                                            <?php /* <select class="form-control chosen-select" tabindex="-1" data-placeholder="Select Test/Package" name="test_package">
                                              <option value="">Select Test/Package</option>
                                              <?php foreach ($test_list as $test) { ?>
                                              <option value="<?php echo "t_" . $test['id']; ?>" <?php
                                              if ($test_pac == "t_" . $test['id']) {
                                              echo "selected";
                                              }
                                              ?> ><?php echo ucwords($test['test_name']."(".$test['name'].")"); ?></option>
                                              <?php } ?>
                                              <?php foreach ($package_list as $package) { ?>
                                              <option value="<?php echo "p_" . $package['id']; ?>" <?php
                                              if ($test_pac == "p_" . $package['id']) {
                                              echo "selected";
                                              }
                                              ?> ><?php echo ucwords($package['title']); ?></option>
                                              <?php } ?>
                                              </select> 
                                        </td>
                                        <td>
                                            <input type="text" name="date" placeholder="select date" class="form-control datepicker-input" id="date"  value="<?php
                                            if (isset($date)) {
                                                echo $date;
                                            }
                                            ?>" />
                                        </td>
                                        <td>
                                            <input type="text" placeholder="Amount" class="form-control" name="p_amount" value="<?php echo $amount; ?>"/>
                                        </td>
                                        <td>
                                            <select class="form-control chosen-select" data-placeholder="Select Status" tabindex="-1" name="status">
                                                <option value="">Select Status </option>
                                                <option value="1" <?php
                                                if ($statusid == 1) {
                                                    echo "selected";
                                                }
                                                ?>> Waiting For Approval </option>
                                                <option value="6" <?php
                                                if ($statusid == 6) {
                                                    echo "selected";
                                                }
                                                ?>> Approved </option>
                                                <option value="7" <?php
                                                if ($statusid == 7) {
                                                    echo "selected";
                                                }
                                                ?>> Sample Collected </option>
                                                <option value="8" <?php
                                                if ($statusid == 8) {
                                                    echo "selected";
                                                }
                                                ?>> Processing </option>
                                                <option value="2" <?php
                                                if ($statusid == 2) {
                                                    echo "selected";
                                                }
                                                ?>> Completed </option>
                                            </select>
                                        </td>
                                        <td style="width:7%">
                                            <input type="submit" name="search" class="btn btn-success" value="Search" />
                                        </td>
                                    </tr> */ ?>
                                    <?php
                                    $cnt = 1;
                                    foreach ($query as $row) {
                                        ?>

                                        <tr>
                                            <td><a  href='<?php echo base_url(); ?>job-master/job-details/<?php echo $row['id']; ?>'><?php echo $row['order_id']; ?></a> 
                                            </td>

                                            <td><a href="<?php echo base_url(); ?>customer-master/customer-all-details/<?php echo $row['cid']; ?>"><?php echo ucwords($row['full_name']); ?></a></td>
                                            <td><?php echo $row['address'] ?></td>
                                            <td><?php echo $row['sample_date']; ?></td>
                                            <td><?php echo $row['sample_time']; ?></td>
                                            <td><?php echo $row['phlebo_name']; ?></td>
                                            <td><?php echo ucwords($row['date']); ?></td>
                                            <td>
                                                <?php
                                                if ($row['status'] == 7) {
                                                    echo "<span class='label label-success'>Sample Collected</span>";
                                                } else {
                                                    echo "<span class='label label-danger'>Pending</span>";
                                                }
                                                ?>
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