<style>
.res_table_actv_pkg tbody tr:nth-child(2n) {background-color: #f5f6f7;}
    @media 
    only screen and (max-width: 767px),
    (min-device-width: 360px) and (max-device-width: 640px)  {

        /* Force table to not be like tables anymore */
        .res_table_actv_pkg thead, .res_table_actv_pkg tbody, .res_table_actv_pkg th, .res_table_actv_pkg td,.res_table_actv_pkg tr { 
            display: block; 
        }

        /* Hide table headers (but not display: none;, for accessibility) */
        .res_table_actv_pkg thead tr { 
            position: absolute;
            top: -9999px;
            left: -9999px;
        }

        .res_table_actv_pkg tr { border: 1px solid #ccc; }

        .res_table_actv_pkg td { 

            border: none;
            border-bottom: 1px solid #eee; 
            position: relative;
            padding-left: 50% !important; 
        }

        .res_table_actv_pkg td:before { 
            /* Now like a table header */
            position: absolute; 
            /* Top/left values mimic padding */
            <!-- top: 6px;
            left: 6px; -->
            width: 45%; 
            <!-- padding-right: 10px;  -->
            white-space: nowrap;
        }


        .res_table_actv_pkg td:nth-of-type(1):before { content: "Package"; }
        .res_table_actv_pkg td:nth-of-type(2):before { content: "Booked Date"; }
        .res_table_actv_pkg td:nth-of-type(3):before { content: "Valid Upto"; }
        .res_table_actv_pkg td:nth-of-type(4):before { content: "For"; }
        .res_table_actv_pkg td:nth-of-type(5):before { content: "Action"; }
    }

    @media  (max-width: 320px)  {.res_table td {padding-left: 45% !important;}
    </style>
    <!-- Start main-content -->
    <div class="main-content">
        <!-- Section: home -->
        <section>
            <div class="container pdng_top_20px pdng_btm_30px">
                <div class="row">

                    <div class="col-sm-12">
                        <h1 class="txt_green_clr res_txt_grn">My Active Packages</h1>
                    </div>
                    <div class="col-sm-12 pdng_0">
                        <div id="exTab3" class="container">
                            <div class="" style="width: 100%; float: left; margin-top: 10px;">
                                <?php if (isset($success) != NULL) { ?>
                                    <div class="alert alert-success alert-dismissable">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        <?php echo $success['0']; ?>
                                    </div>
                                <?php } ?>
                                <div class="" id="1b">
                                    <div class="row">
                                        <div class="col-sm-12">

                                            <table class="table table-bordered res_table_actv_pkg">
                                                <thead>
                                                    <tr style="color:white">
                                                        <th>Package</th>
                                                        <th>Booked Date</th>
                                                        <th>Valid Upto</th>
                                                        <th>For</th>
                                                        <th class="text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($active_package as $key){ ?>
                                                    <tr>
                                                        <td><?=ucfirst($key["title"]);?></td>
                                                        <td><?=ucfirst($key["book_date"]);?></td>
                                                        <td><?=ucfirst($key["due_to"]);?></td>
                                                        <td><?=ucfirst($key["family"]);?></td>
                                                        <td class="text-center"><a href="<?=base_url();?>active_packages/package_details/<?=$key["id"]?>" class="btn btn-sm btn-primary">View more & Book</a></td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>


                                        </div>
                                    </div>

                                </div>
                                <div style="text-align:right;" class="box-tools col-sm-12">
                                    <ul class="pagination pagination-sm no-margin pull-right">
                                        <?php echo $links2; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="full_div pdng_top_35px">
                        <div class="col-sm-6">
                            <h1 class="all_pg_lst_btns">An App for simplified pathology experience.</h1>
                        </div>
                        <div class="col-sm-6">
                            <div class="col-sm-12 pdng_0">
                                <div class="col-sm-6">
                                    <a href="https://play.google.com/store/apps/details?id=com.patholab&hl=en" target="_blank"><img class="mbl_googl_res_mrgn app_full_img" src="<?php echo base_url(); ?>user_assets/images/google_play.png"/></a>
                                </div>
                                <div class="col-sm-6">
                                    <a href="https://itunes.apple.com/in/app/airmed-pathlabs/id1152367695?mt=8" target="_blank"><img class="app_full_img" src="<?php echo base_url(); ?>user_assets/images/apple_appstore_big.png"/></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>


        <!-- end main-content -->
        <script src="<?php echo base_url(); ?>user_assets/js/jquery-2.2.0.min.js"></script>
        <script src="<?php echo base_url(); ?>user_assets/js/jquery-ui.min.js"></script>
        <script src="<?php echo base_url(); ?>user_assets/js/bootstrap.min.js"></script>
        <script>
            $(document).ready(function () {
                $('[data-toggle="tooltip"]').tooltip();
            });

            function get_job_details(val) {
                $.ajax({
                    url: "<?php echo base_url(); ?>user_master/get_job_details",
                    type: 'post',
                    data: {jid: val},
                    beforeSend: function () {
                        $("#loader_div2_" + val).attr("style", "");
                        $("#note_save_btn_" + val).attr("disabled", "disabled");
                    },
                    success: function (data) {
                        $("#modal_body").html(data);
                        $("#details_model").modal("show");
                    }, complete: function () {
                        $("#loader_div2_" + val).attr("style", "display:none;");
                        $("#note_save_btn_" + val).removeAttr("disabled");
                    }
                });
            }

            function remove_test(val, jid) {
                var cnfg = confirm("Are you sure?");
                if (cnfg == true) {
                    $.ajax({
                        url: "<?php echo base_url(); ?>user_master/delete_job_test",
                        type: 'post',
                        data: {tid: val, jid: jid},
                        beforeSend: function () {
                            $("#loader_div2_" + val).attr("style", "");
                            $("#note_save_btn_" + val).attr("disabled", "disabled");
                        },
                        success: function (data) {
                            $("#modal_body").html(data);
                            $("#details_model").modal("show");
                        }, complete: function () {
                            $("#loader_div2_" + val).attr("style", "display:none;");
                            $("#note_save_btn_" + val).removeAttr("disabled");
                        }
                    });
                }
            }
            function delete_job(jid) {
                var cnfg = confirm("Are you sure?");
                if (cnfg == true) {
                    $.ajax({
                        url: "<?php echo base_url(); ?>user_master/delete_job",
                        type: 'post',
                        data: {jid: jid},
                        beforeSend: function () {
                            $("#loader_div2").attr("style", "");
                            $("#note_save_btn").attr("disabled", "disabled");
                        },
                        success: function (data) {
                            window.location.reload();
                        }, complete: function () {
                            $("#loader_div2").attr("style", "display:none;");
                            $("#note_save_btn").removeAttr("disabled");
                        }
                    });
                }
            }
        </script>