<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="#"><i class="fa fa-users"></i>Data Authorisation</a></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-6">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Data Authorisation</h3>

                        <a style="float:right;" href='<?php echo base_url(); ?>data_autorisation' class="btn btn-primary btn-sm" ><strong> Retrieve</strong></a>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="widget">

                        </div>
                        <div class="tableclass">
                            <table id="example3" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Barcode</th>
                                        <th>Patient Name</th>
                                        <th>Test</th>
                                        <th style="width: 10px"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($query as $datas) { ?>
                                        <tr>
                                            <td><?php echo $datas['barcode']; ?></td>
                                            <td><?php echo $datas['full_name']; ?></td>
                                            <td><?php echo $datas['test_name']; ?></td>
                                            <td><button type="button" id="show_more_<?php echo $datas['id']; ?>" class="btn btn-default button_show" onclick="parameter_show(<?php echo $datas['id']; ?>,<?php echo $datas['barcode']; ?>);"><i class="fa fa-chevron-right" aria-hidden="true"></i></button></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <div style="text-align:right;" class="box-tools">
                            <ul class="pagination pagination-sm no-margin pull-right">
                                <?php echo $links; ?>
                            </ul>
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
            <div class="col-xs-6">
                <div class="box box-primary" style="display: none;" id="show_parameters">
                    <div class="box-header">
                        <h3 class="box-title">Parameter Value</h3>
                    </div>
                    <form method="post" action="<?php echo base_url(); ?>data_autorisation/accept_results" id="add_result">
                        <div class="box-body">
                            <div class="widget">
                                <div class="alert alert-success" id="profile_msg_suc" style="display: none;">
                                    <span id="msg_success"></span>
                                </div>
                                <div class="alert alert-danger" id="profile_msg_sucun" style="display: none;">
                                    <span id="msg_success"></span>
                                </div>

                            </div>
                            <div class="tableclass">
                                <table id="example3" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Parameter</th>
                                            <th>Value</th>
                                            <th>Range</th>
                                        </tr>
                                    </thead>

                                    <tbody id="show_parameter_value">

                                    </tbody>

                                </table>
                            </div>
                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <button style="float:right" type="button" onclick="submit_parameter();" class="btn btn-primary btn-sm" >Accept</button>
                        </div>
                    </form>
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<script type="text/javascript">
    $(function () {
        $("#example1").dataTable();
        $('#example3').dataTable({
            "bPaginate": false,
            "bLengthChange": false,
            "bFilter": false,
            "bSort": false,
            "bInfo": false,
            "bAutoWidth": false
        });
    });
    function parameter_show(id, barcode) {

        $(".button_show").removeClass('btn-danger');
        $(".button_show").addClass('btn-default');
        $("#show_more_" + id).removeClass('btn-default');
        $("#show_more_" + id).addClass('btn-danger');
        var url = "<?php echo base_url(); ?>data_autorisation/get_data_parameter";
        $.ajax({
            type: "POST",
            url: url,
            data: {"barcode": barcode}, // serializes the form's elements.
            success: function (data)
            {
                $("#show_parameter_value").empty();
                $("#show_parameter_value").html(data);
                $("#show_parameters").show();
            }
        });
    }
    function submit_parameter() {
        var frm_data = '<?php echo base_url(); ?>data_autorisation/accept_results';
        $.ajax({
            url: frm_data,
            type: 'post',
            data: $("#add_result").serialize(),
            success: function (data) {
            },
            error: function (jqXhr) {
                $("#msg_unsuccess").html("");
                $("#msg_unsuccess").html("Please Try Again.");
                $("#profile_msg_sucun").show();
                timeount();
            },
            complete: function () {
                $("#msg_success").html("");
                $("#msg_success").html("Parameter value successfully Accepted.");
                $("#profile_msg_suc").show();
                timeount();
                setTimeout(function () {
                    location.reload();
                }, 2000);
            },
        });
    }
    function timeount() {
        setTimeout(function () {
            $("#profile_msg_suc").hide();
        }, 1000);
        setTimeout(function () {
            $("#profile_msg_sucun").hide();
        }, 1000);
    }
</script>
