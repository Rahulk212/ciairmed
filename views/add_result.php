<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>AirmedLabs</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link href="<?php echo base_url(); ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url(); ?>user_assets/images/fav_icon.ico" />
        <link href="<?php echo base_url(); ?>css/style.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>plugins/iCheck/all.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
    </head>
    <body class="skin-blue layout-top-nav">
        <div class="content-wrapper">
            <div class="">
                <style>
                    .admin_job_dtl_img {border: 4px solid #8d8d8d; height: 160px; max-width: 160px; min-width: 80px; width: 180px;}
                </style>
                <script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
                <link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
                <link href="<?php echo base_url(); ?>plugins/timepick/css/timepicki.css" rel="stylesheet">
                <section class="content-header">
                    <h1> Add Results </h1>
                </section>
                <section class="content">
                    <div class="row">




                        <!--Add Parameter Start-->

                        <div class="col-md-12">
                            <div class="box box-primary">
                                <div class="box-header">
                                    <h3 class="box-title">Add Parameter</h3>
                                </div>
                                <div class="box-body">
                                    <div class="col-sm-12">
                                        <?php
                                        $ts = explode('#', $query[0]['testname']);
                                        $tid = explode(",", $query[0]['testid']);
                                        $cnt = 0;
                                        foreach ($tid as $testidp) {
                                            ?>
                                            <h3>Add Parameter For <?php echo ucfirst($ts[$cnt]); ?></h3>
                                            <table id="example4" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Parameter Name</th>
                                                        <th>Minimum Range</th>
                                                        <th>Maximum Range</th>
                                                        <th>Parameter Unit</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <td><input type="text" id="name_<?php echo $testidp; ?>" class="form-control"></td>
                                                <td><input type="text" id="minrange_<?php echo $testidp; ?>" class="form-control"></td>
                                                <td><input type="text" id="maxrange_<?php echo $testidp; ?>" class="form-control"></td>
                                                <td><input type="text" id="unit_<?php echo $testidp; ?>" class="form-control"></td>
                                                <td><input type="button" value="Add" onclick="add_parameter('<?php echo $testidp; ?>');" class="btn btn-primary"></td>
                                                </tbody>
                                            </table>
                                            <?php
                                            $cnt++;
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="box-footer">
                                </div>
                            </div>
                        </div>

                        <!--Add parameter end-->


                        <div class="col-md-12">
                            <div class="box box-primary">
                                <div class="box-header">
                                    <h3 class="box-title">Add Result</h3>
                                </div>
                                <div class="box-body">
                                    <div class="alert alert-danger alert-dismissable" id="msg_cancel" style="display:none;">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        Results Not Add
                                    </div>
                                    <div class="alert alert-success alert-dismissable" id="msg_success" style="display:none;">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        Results Add Successfully
                                    </div>
                                    <div class="col-sm-12">
                                        <?php
                                        $ts = explode('#', $query[0]['testname']);
                                        $tid = explode(",", $query[0]['testid']);
                                        $cnt = 0;
                                        foreach ($tid as $testidp) {
                                            if ($parameter_list[$cnt][0]['test_fk'] == $testidp) {
                                                ?>
                                                <h3>Add Result For <?php echo ucfirst($ts[$cnt]); ?></h3>
                                                <table id="example4" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Parameter Name</th>
                                                            <th>Value</th>
                                                            <th>Parameter Value</th>
                                                            <th>Parameter Unit</th>
                                                    <input type="hidden" id="para_job_id" value="<?php echo $cid; ?>">
                                                    </tr>
                                                    </thead>
                                                    <?php
                                                } $cn = 1;
                                                foreach ($parameter_list[$cnt] as $parameter) {
                                                    if ($parameter['test_fk'] == $testidp) {
                                                            ?>
                                                            <tbody>
                                                            <td><?php echo $parameter['parameter_name']; ?></td>
                                                            <td>
                                                                <input type="text" id="value_add_<?php echo $cnt; ?>_<?php echo $cn; ?>" class="form-control">
                                                                <input type="hidden" id="para_id_<?php echo $cnt; ?>_<?php echo $cn; ?>" value="<?php echo $parameter['pid']; ?>">
                                                            </td>
                                                            <td><?php echo $parameter['parameter_range']; ?></td>
                                                            <td><?php echo $parameter['parameter_unit']; ?></td>
                                                            <?php
                                                    }
                                                    $cn++;
                                                    ?>
                                                    <input type="hidden" id="para_count_<?php echo $cnt; ?>" value="<?php echo $cn; ?>">
                                                    <input type="hidden" id="test_count" value="<?php echo $cnt; ?>">
                                                    <input type="hidden" id="test_id_<?php echo $cnt; ?>" value="<?php echo $testidp; ?>">
                                                <?php }$cnt++;
                                                ?>
                                                </tbody>
                                            </table>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <span id="loader_div" style="display:none;"><img src="<?= base_url(); ?>upload/opc-ajax-loader.gif" style="height:28px;margin-left:10px"></span>
                                    <input onclick="add_results();" id="add_result" style="float:right;" class="btn btn-primary" value="Add Result" type="button">
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <script src="<?= base_url(); ?>plugins/chartjs/Chart.min.js" type="text/javascript"></script>
            <script src="<?php echo base_url(); ?>bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
            <script src="<?php echo base_url(); ?>plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
            <script src="<?php echo base_url(); ?>plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
            <script src='<?php echo base_url(); ?>plugins/fastclick/fastclick.min.js'></script>
            <script src="<?php echo base_url(); ?>dist/js/app.min.js" type="text/javascript"></script>
            <script src="<?php echo base_url(); ?>dist/js/demo.js" type="text/javascript"></script>
            <script src="<?php echo base_url(); ?>js/admin.js" type="text/javascript"></script>
            <script src="<?= base_url(); ?>assets/summernote/summernote.js"></script>
            <script src="<?= base_url(); ?>assets/js/pages/forms.js"></script>
            <script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap-multiselect.js"></script>
            <link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap-multiselect.css" type="text/css"/>
            <script src="<?php echo base_url(); ?>plugins/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
            <script src="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap.min.js" type="text/javascript"></script>
            <script type="text/javascript">
                                        $(function () {
                                            $("#example1").dataTable();
                                            $('#example2').dataTable({
                                                "bPaginate": true,
                                                "bLengthChange": false,
                                                "bFilter": true,
                                                "bSort": false,
                                                "bInfo": false,
                                                "bAutoWidth": false,
                                                "iDisplayLength": 10
                                            });
                                        });
            </script>
    </body>
</html>
<script>
    function add_results() {
        var test_count = $('#test_count').val();
        var para_job_id = $('#para_job_id').val();
        var i;
        var j;
        var data = [];
        for (i = 0; i <= test_count; i++) {
            var para_count = $('#para_count_' + i).val();
            var test_id = $('#test_id_' + i).val();
            var data2 = [];
            for (j = 1; j < para_count; j++) {
                var value_add = $('#value_add_' + i + '_' + j).val();
                var para_id = $('#para_id_' + i + '_' + j).val();
                var subpara_id = $('#subpara_id_' + i + '_' + j).val();
                var myObject = [];
                myObject['job_id'] = para_job_id;
                myObject['test_id'] = test_id;
                myObject['value'] = value_add;
                myObject['parameter_id'] = para_id;
                myObject['subpar_id'] = subpara_id;
                alert(myObject);
                data2.push(myObject);
            }
        }
        data.push(data2);
        console.log(data);
        return false;
    }
    function add_parameter(val) {
        var name = $('#name_' + val).val();
        var minrange = $('#minrange_' + val).val();
        var maxrange = $('#maxrange_' + val).val();
        var unit = $('#unit_' + val).val();
        $.ajax({
            url: "<?php echo base_url(); ?>add_result/add_parameter_data",
            type: 'post',
            data: {paraname: name, minrange: minrange, maxrange: maxrange, unit: unit, test_id: val},
            success: function (data) {
                if (data == 1) {
                    $('#name_' + val).val('');
                    $('#minrange_' + val).val('');
                    $('#maxrange_' + val).val('');
                    $('#unit_' + val).val('');
                }
            }
        });
    }
</script>