<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Sample
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>b2b/Logistic/sample_list"><i class="fa fa-users"></i>Sample List</a></li>
        </ol>
    </section>
    <style>
        .chosen-container {
            display: inline-block;
            font-size: 14px;
            position: relative;
            vertical-align: middle;
            width: 100%;
        }
        .full_bg{background: rgba(0,0,0,0.3); width:100%; height:100%; float:left; padding:350px; position:fixed; z-index:9; top:0; bottom:0;}
        .full_bg .loader img{width:70px; height:70px;}
    </style>
    <div class="full_bg" style="display:none;" id="loader_div">
        <div class="loader">
            <center><img src="http://static.heart.org/riskcalc/app/assets/img/loading.gif"></center>
        </div>
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Sample List</h3>
<!--                        <a style="float:right;margin-right:5px;" href='<?php echo base_url(); ?>test-master/test-add' class="btn btn-primary btn-sm" ><i class="fa fa-plus-circle" ></i><strong > Add</strong></a>
                        <a style="float:right;margin-right:5px;" href='<?php echo base_url(); ?>test_master/test_csv?city=<?= $city ?>' class="btn btn-primary btn-sm" ><strong > Export</strong></a>
                        <a style="float:right;margin-right:5px;" data-toggle="modal"  data-target="#import"  class="btn btn-primary btn-sm" ><strong > Import</strong></a>-->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <?php echo form_open("b2b/Lab/sample_list/", array("method" => "GET")); ?>
                        <div class="tableclass">
                            <table id="example4" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Barcode</th>
                                        <th>Logistic Name</th>
                                        <th>Scan Date</th>
                                        <?php if ($login_data["type"] == 3) { ?>
                                            <th>Collect From</th>
                                        <?php } ?>
                                        <th>Status</th>
                                        <th style="width: 10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td><input type="text" class="form-control" name="barcode" placeholder="Barcode" value="<?= $barcode ?>"/></td>
                                        <td><input type="text" class="form-control" name="name" placeholder="Name" value="<?= $name ?>"/></td>
                                        <td><input type="text" class="form-control" name="date" placeholder="Date" value="<?= $date ?>"/></td>
                                        <?php if ($login_data["type"] == 3) { ?>
                                            <td><input type="text" class="form-control" name="from" placeholder="From" value="<?= $from ?>"/></td>
                                        <?php } ?>
                                        <td></td>
                                        <td style="widtd: 10%"><input type="submit" value="Search" class="btn btn-primary btn-md"></td>
                                    </tr>
                                    <?php

                                    $cnt = 0;
										if($_REQUEST['debug']==1){
										print_r($query);
									}
                                    foreach ($query as $row) {
                                        $cnt++;
                                        ?>
                                        <tr> <td><?php echo $cnt; ?></td>
                                            <td>
                                                <?php echo ucwords($row['barcode']); ?> </br><?php echo ucwords($row['customer_name']).' / '.ucfirst($row['customer_gender']); ?>
                                            </td>
                                            <td><?php echo ucwords($row['name']); ?></td>
                                            <td><?php echo ucwords($row['scan_date']); ?></td>
                                            <?php if ($login_data["type"] == 3) { ?>
                                                <td><?php echo ucwords($row['c_name']); ?></td>
                                            <?php } ?>
                                            <td>
                                                <?php
                                                /* if ($row['job_details'][0]["original"]) {
                                                    echo '&nbsp;&nbsp;<span class="label label-success">Report uploaded</span>';
                                                } else {
                                                    echo '&nbsp;&nbsp;<span class="label label-warning">Report pending</span>';
                                                } */
												if ($row['jobsstatus'] == 0) {
                                                    echo '&nbsp;&nbsp;<span class="label label-warning">Enroute</span>';
                                                }else if($row['jobsstatus'] == 2){
													
													 echo '&nbsp;&nbsp;<span class="label label-info">Processing</span>';
													
												} if ($row['jobsstatus'] == 1) {
                                                    echo '&nbsp;&nbsp;<span class="label label-success">Completed</span>';
                                                }
                                                ?>
                                            </td>

                                            <td>
                                                <a  href='<?php echo base_url(); ?>b2b/Lab/details/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="details"><i class="fa fa-eye"></i></a>
                                            <?php if ($row['jobsstatus'] == 1) { ?> <a  href='javascript:void(0)' class="showreport" id="pdfreport_<?= $row['id']; ?>"  data-toggle="tooltip" data-original-title="Download Report"><i class="fa fa-arrow-down "></i> Download Report</a><?php } ?>
                                        </tr>
                                    <?php }if (empty($query)) {
                                        ?>
                                        <tr>
                                            <td colspan="4">No records found</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <?= form_close(); ?>
                        </div>
                        <div style="text-align:right;" class="box-tools">
                            <ul class="pagination pagination-sm no-margin pull-right">
                                <?php echo $links; ?>
                            </ul>
                        </div>
                    </div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<div id="myModalreport" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Uploaded Reports</h4>
            </div>

            <div id="reportcontain" class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>

        </div>

    </div>
</div>

<script type="text/javascript">

    $(document).on("click", ".showreport", function () {

        var id = this.id;
        var splitid = id.split("_")
        var jid = splitid[1];
        $('#reportcontain').html('<div style="height:50px"><span id="searching_spinner_center" style="position: absolute;left: 50%;"><img src="<?= base_url() . "img/ajax-loader.gif" ?>" /></span></div>');
        $('#myModalreport').modal('show');
//$("#"+id).prop('disabled', true);
        $.ajax({url: "<?php echo base_url() . "b2b/lab/getreportpdf"; ?>",
            type: "POST",
            data: {pid: jid},
            error: function (jqXHR, error, code) {
            },
            success: function (data) {
                $('#reportcontain').html(data);
            }
        });

    });
</script>
<script type="text/javascript">
    $(function () {
        $("#example1").dataTable();
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
    function assigntotyrocare(id) {

        $.ajax({
            url: '<?php echo base_url(); ?>b2b/Logistic/sendToThyrocare/' + id,
            type: 'get',
            beforeSend: function () {
                $("#loader_div").attr("style", "");
            },
            success: function (data) {

                var result = JSON.parse(data);
                if (result.status == "SUCCESS") {
                    alert("Sample forwared to Thyrocare. Job Id is " + result.barcode_patient_id);
                    window.location.reload();
                } else {
                    alert("Error on sample forwared to Thyrocare. Mesage  " + result.message);
                }
            },
            error: function (jqXhr) {
                alert("error");
            },
            complete: function () {
                $("#loader_div").attr("style", "display:none;");
                //$("#send_opt_1").removeAttr("disabled");
                // alert("complete");

            },
        });
    }
</script>
