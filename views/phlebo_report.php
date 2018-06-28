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
            Phlebo Report
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li>Phlebo Report</li>

        </ol>
    </section>
    <?php
    $start_date = $_GET['start_date'];
    $end_date = $_GET['end_date'];
    $phlebo = $_GET['phlebo_name'];
    $city = $_GET['city'];
    ?>
    <?php //echo "<pre>";print_r($phlebo);die;?>
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
                        <?php echo form_open("phlebo_report/phlebo_report", array("method" => "GET", "role" => "form")); ?>
                        <div class="row">
                            <div class="col-sm-2">
                                <input type="text" name="start_date" placeholder="Select date" class="form-control datepicker-input" id="date"  value="<?= $start_date ?>" style="width:190px;" />
                            </div>

                            <div class="col-sm-2">
                                <input type="text" name="end_date" placeholder="End date" class="form-control datepicker-input" id="date1"  value="<?= $end_date ?>" style="width:190px;"/>
                            </div>

                            <div class="col-sm-2"> 
                                <select class="form-control chosen-select" id="city_id" data-placeholder="Select city" tabindex="-1" name="city" onchange="get_branch1();" style="width:190px;">
                                    <option value="" >All City</option>
                                    <?php foreach ($test_city as $cities) { ?>
                                        <option value="<?php echo $cities['id']; ?>" <?php
                                        if ($city == $cities['id']) {
                                            echo " selected ";
                                        } if ($city == '') {
                                            if ($cities['id'] == $city) {
                                                echo "selected";
                                            }
                                        }
                                        ?>><?php echo ucwords($cities['name']); ?></option>
                                            <?php } ?>
                                </select>
                            </div>
                            <?php //echo "<prE>";print_r($branch_list);die;?>
                            <div class="col-sm-2"> 
                                <select class="form-control chosen" data-placeholder="Select Branch" tabindex="-1" name="phlebo_name" id="branch1">
                                    <option value="" >All Phlebo</option>
                                    <?php
                                    foreach ($phlebo_list as $new_phlebo_list) {
                                        if (!empty($phlebo)) {
                                            if (in_array(new_phlebo_list['id'], $phlebo)) {
                                                ?>
                                                <option value="<?php echo $$new_phlebo_list['id']; ?>" <?php
                                                if ($phlebo == $branchh['id']) {
                                                    echo "<pre>";
                                                    print_r($phlebo);
                                                    echo " selected ";
                                                }
                                                ?>><?php echo ucwords($branchh['PhleboName']) ?></option>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <option value="<?php echo $new_phlebo_list['id']; ?>" <?php
                                            if ($phlebo == $new_phlebo_list['id']) {
                                                echo " selected ";
                                            }
                                            ?>><?php echo ucwords($new_phlebo_list['PhleboName']); ?></option>
    <?php }
}
?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <input type="submit" name="search" class="btn btn-success" value="Search" />
                            </div>
                        </div>
                        <br>
                            <?php echo form_close(); ?>
                        <!--Search end-->

                        <div class="tableclass"> 
                                <?php if ($start_date != '' || $end_date != '' || $phlebo != '' || $city != '') { ?>
                                <a href="<?php echo base_url(); ?>phlebo_report/export?start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>&phlebo=<?php echo $phlebo; ?>&city=<?php echo $city; ?>" class="btn-sm btn-primary">Export</a><?php } ?>

                            <div class="table-responsive" id="prnt_rpt">
<?php if ($start_date != '' || $end_date != '' || $phlebo != '' || $city != '') { ?>
                                    <table id="example4" class="table table-bordered table-striped">
                                        <h2><?php if (trim($start_date) != '' || trim($end_date) != '') { ?><?= $start_date ?> To <?= $end_date ?><?php } ?></h2>

                                        <thead>
                                            <tr>
                                                <th width="5%"><h4>No.</h4></th>
                                                <th width="10%"><h4>Phlebo Name</h4></th>
                                                <th width="13%"><h4>Reg No</h4></th>
                                                <th width="15%"><h4>Patient Name</h4></th>
                                                <th width="17%"><h4>Date Time</h4></th>
                                                <th width="20%"><h4>Address</h4></th>
                                                <th width="15%"><h4>Note</h4></th>
                                                <th width="5%"><h4>Status</h4></th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $cnt = 1;
                                            foreach ($view_report as $am_br) {
                                                if ($am_br['start_time'] != '' && $am_br['end_time'] != '') {
                                                    $all_time = $am_br['start_time'] . '&nbsp;&nbsp;To&nbsp;&nbsp;' . $am_br['end_time'];
                                                } else {
                                                    $all_time = $am_br['time'];
                                                }
                                                if ($am_br['note'] != '') {
                                                    $note = $am_br['note'];
                                                } else {
                                                    $note = "N/A";
                                                }
                                                $old_date = $am_br['date'];
                                                $new_date = date('d-m-Y', strtotime($old_date));
                                                $first_name = strtolower($am_br['full_name']);
                                                $new_phlebo_name = strtolower($am_br['PhleboName']);
                                                $new_address = strtolower($am_br['address']);
                                                ?>
                                                <tr>
                                                    <td><?php echo $cnt; ?></td>
                                                    <td><?php echo ucwords($new_phlebo_name); ?></td>
                                                    <td><a href="<?php echo base_url(); ?>job-master/job-details/<?php echo $am_br['jid']; ?>" target="_blank"><?php echo $am_br['jid'] . '&nbsp;(' . $am_br['order_id'] . ')'; ?>  
                                                    </td>
                                                    <td><?php echo ucwords($first_name); ?></td>


                                                    <td><?php echo $new_date . '&nbsp;&nbsp;&nbsp;' . $all_time; ?></td>
                                                    <td><?php echo ucwords($new_address); ?></td>
                                                    <td><?php echo $note; ?></td>
                                                    <td><?php
                                                        if ($am_br['is_accept'] == 1) {
                                                            echo "<span class='label label-success '>Accepted</span>";
                                                        } else {
                                                            echo "<span class='label label-warning '>Pending</span>";
                                                        }
                                                        ?></td>

                                                </tr>

                                                <?php
                                                $cnt++;
                                            }
                                            ?>
                                            <?php
                                            foreach ($new_report as $new_view_report) {
                                                if ($new_view_report['address'] != '') {
                                                    $address = ucwords($new_view_report['address']);
                                                } else {
                                                    $address = "N/A";
                                                }
                                                $sub_first_name = strtolower($new_view_report['full_name']);
                                                $sub_new_phlebo_name = strtolower($new_view_report['PhleboName']);
                                                $sub_new_address = strtolower($new_view_report['address']);
                                                ?>
                                                <tr><td><?php echo $cnt; ?></td>
                                                    <td><?php echo ucwords($sub_new_phlebo_name); ?></td>
                                                    <td style="max-width:30px;"><a href="<?php echo base_url(); ?>job-master/job-details/<?php echo $new_view_report['jid']; ?>" target="_blank"><?php echo $new_view_report['order_id']; ?>  
                                                    </td>
                                                    <td><?php echo ucwords($sub_first_name); ?></td>


                                                    <td><?php echo $new_view_report['createddate']; ?></td>
                                                    <td><?php echo ucwords($sub_new_address); ?></td>
                                                    <td></td>
                                                    <td><?php echo "<span class='label label-success '>Accepted</span>"; ?></td>
                                                </tr>
                                        <?php $cnt++;
                                    }
                                    ?>
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
    function get_branch1() {
        var city_id = $('#city_id').val();

        $.ajax({
            url: '<?php echo base_url(); ?>Phlebo_report/getPhleboName',
            type: 'post',
            data: {city_id: city_id},
            success: function (data) {

                $("#branch1").html(data);
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