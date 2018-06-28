<section class="content-header">
    <h1>
        Dashboard             
    </h1>
    <ol class="breadcrumb">
        <li><a href=""><i class="fa fa-dashboard"></i>Home</a></li>
        <li class="active">Dashboard</li>
    </ol>
</section>
<?php if ($login_data["type"] == 1) { ?>
    <section class="content" id="count_section">
        <span id="loader_div" style="display:none;"><img src="<?= base_url(); ?>upload/opc-ajax-loader.gif" style="height:28px;margin-left:10px"></span>
        <!-- /.row -->
    </section>
    <section class="content" id="chart_section">
        <span id="loader_div1" style="display:none;"><img src="<?= base_url(); ?>upload/opc-ajax-loader.gif" style="height:28px;margin-left:10px"></span>
    </section><!-- /.content -->
    <!-- jQuery 2.1.4 -->

    <!-- page script -->
    <script>
        setTimeout(function () {
            $.ajax({
                url: "<?php echo base_url(); ?>Admin/get_dashboard",
                data: {
                    type: "count"
                },
                type: "POST",
                beforeSend: function () {
                    $("#loader_div").attr("style", "");
                    //$("#send_btn").attr("disabled", "disabled");
                },
                success: function (data) {
                    $("#count_section").html("");
                    $("#count_section").html(data);

                },
                complete: function () {
                    $("#loader_div").attr("style", "display:none;");
                    //$("#send_btn").removeAttr("disabled");
                },
            });


            $.ajax({
                url: "<?php echo base_url(); ?>Admin/get_dashboard",
                data: {
                    type: "chart"
                },
                type: "POST",
                beforeSend: function () {
                    $("#loader_div1").attr("style", "");
                    //$("#send_btn").attr("disabled", "disabled");
                },
                success: function (data) {
                    $("#chart_section").html("");
                    $("#chart_section").html(data);

                },
                complete: function () {
                    $("#loader_div1").attr("style", "display:none;");
                    //$("#send_btn").removeAttr("disabled");
                },
            });
        }, 1000);
    </script>
    <!-- jQuery 2.1.4 -->
<?php } ?>

