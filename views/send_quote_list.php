<!--<script type="text/javascript" src="<?= base_url(); ?>fencybox1/source/jquery.fancybox.js?v=2.1.5"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>fencybox1/source/jquery.fancybox.css?v=2.1.5" media="screen" />-->
<!-- Add jQuery library -->
<script type="text/javascript" src="<?php echo base_url(); ?>lib/jquery-1.10.2.min.js"></script>

<!-- Add mousewheel plugin (this is optional) -->
<script type="text/javascript" src="<?php echo base_url(); ?>lib/jquery.mousewheel.pack.js?v=3.1.3"></script>

<!-- Add fancyBox main JS and CSS files -->
<script type="text/javascript" src="<?php echo base_url(); ?>source/jquery.fancybox.pack.js?v=2.1.5"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>source/jquery.fancybox.css?v=2.1.5" media="screen" />

<!-- Add Button helper (this is optional) -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>source/helpers/jquery.fancybox-buttons.css?v=1.0.5" />
<script type="text/javascript" src="<?php echo base_url(); ?>source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>

<!-- Add Thumbnail helper (this is optional) -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" />
<script type="text/javascript" src="<?php echo base_url(); ?>source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>

<!-- Add Media helper (this is optional) -->
<script type="text/javascript" src="<?php echo base_url(); ?>source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>
<script type="text/javascript">
    var jq = $.noConflict();
    jq(document).ready(function () {
        /*
         *  Simple image gallery. Uses default settings
         */

        jq(".fancybox-effects-a").fancybox({
            'type': 'iframe',
            'width': 800,
            helpers: {
                title: {
                    type: 'outside'
                },
                overlay: {
                    speedOut: 0
                }
            }
        });
    });
</script>
<style>

</style>
<style>
    tr.odd input.form-control {
        width: 100% !important;
    }
</style>
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            User Send Quotation List
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li class="active">User Call</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Quotation List </h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="widget">
                            <?php if (isset($success) != NULL) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $success['0']; ?>
                                </div>
                            <?php } ?>
                            <?php if (isset($unsuccess) != NULL) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $unsuccess['0']; ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="tableclass">
                            <form role="form" action="<?php echo base_url(); ?>user_call_master/index" method="get" enctype="multipart/form-data">
                                <table id="example4" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Caller Name</th>
                                            <th>Mobile No.</th>
                                            <th>Test</th>
                                            <th>Test City</th>
                                            <th>Total Price</th>
                                            <th>Call Date & Time</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        $cnt = 1;
                                        foreach ($query as $row) {
                                            ?>
                                            <tr>
                                                <td><?php echo $_GET["per_page"]+$cnt; ?></td>
                                                <td><?php
                                                    foreach ($customer_list as $customer) {
                                                        if (substr($row['CallFrom'], 1) == $customer['mobile']) {
                                                            echo ucwords($customer['full_name']);
                                                            break;
                                                        }
                                                    }
                                                    ?></td>
                                                <td><?php echo $row['mobile_no']; ?></td>
                                                <td><?php
                                                    foreach ($row["test_details"] as $t_key) {
                                                        echo $t_key["test_name"] . "(Rs." . $t_key["price"] . "),";
                                                    }
                                                    ?></td>
                                                <td><?php echo $row['test_city_name']; ?></td>
                                                <td>Rs.<?php echo $row['price']; ?></td>

                                                <td><?php echo $row['createddate']; ?></td>

                                                                     <td><!--<?php if ($row['RecordingUrl'] != NULL) { ?><a class="branch_select" rel="group" href='<?php echo $row['RecordingUrl']; ?>' data-toggle="tooltip" data-original-title="Play"><?php } else { ?><a href="#" data-toggle="tooltip" data-original-title="Not Available"><?php } ?><i class="fa fa-play-circle-o"></i></a>-->

                                                    <a data-toggle="tooltip" data-original-title="Delete" onclick="return confirm('Are you sure?');" href="<?= base_url(); ?>User_call_master/delete_quote/<?php echo $row['cid']; ?>"><i class="fa fa-trash-o"></i></a>
                                                </td>
                                            </tr>
                                            <?php
                                            $cnt++;
                                        }if (empty($query)) {
                                            ?>
                                            <tr>
                                                <td colspan="12">No records found</td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </form>
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
            "bPaginate": false,
            "bLengthChange": false,
            "bFilter": false,
            "bSort": false,
            "bInfo": false,
            "bAutoWidth": false,
            "iDisplayLength": 10,
            "searching": false
        });
    });
</script>
<script>
    /*	$( document ).ready(function() {
     $.ajax({
     url: "<?php echo base_url(); ?>user_call_master/call_details",
     error: function (jqXHR, error, code) {
     // alert("not show");
     },
     success: function (data) {
     if(data != '') {
     document.getElementById('content').innerHTML = "";
     document.getElementById('content').innerHTML = data;
     $("#myModal").modal('show');
     } else {
     $("#myModal").modal('hide');
     }
     }
     });
     }); */
    $fncybx = $(".branch_select").click(function () {
        $.fancybox.open({
            href: 'add_fancybox?branch=' + old_branch,
            type: 'iframe',
            padding: 5,
            'width': 800,
            'height': 600
        });
    });
</script>
<script>
    $(document).ready(function () {
        var date_input = $('input[name="start_date"]'); //our date input has the name "date"
        var date_input1 = $('input[name="call_date"]'); //our date input has the name "date"

        var container = $('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";
        date_input.datepicker({
            format: 'dd/mm/yyyy',
            container: container,
            todayHighlight: true,
            autoclose: true,
        })
        date_input1.datepicker({
            format: 'dd/mm/yyyy',
            container: container,
            todayHighlight: true,
            autoclose: true,
        })
    });
    function close_frame() {
        jq.fancybox.close();
        window.location.reload();
    }
</script>