<style>
    @media 
    only screen and (max-width: 767px),
    (min-device-width: 360px) and (max-device-width: 640px)  {

        /* Force table to not be like tables anymore */
        .res_table thead, .res_table tbody, .res_table th, .res_table td,.res_table tr { 
            display: block; 
        }

        /* Hide table headers (but not display: none;, for accessibility) */
        .res_table thead tr { 
            position: absolute;
            top: -9999px;
            left: -9999px;
        }

        .res_table tr { border: 1px solid #ccc; }

        .res_table td { 

            border: none;
            border-bottom: 1px solid #eee; 
            position: relative;
            padding-left: 50% !important; 
        }

        .res_table td:before { 
            /* Now like a table header */
            position: absolute; 
            /* Top/left values mimic padding */
            <!-- top: 6px;
            left: 6px; -->
            width: 45%; 
            <!-- padding-right: 10px;  -->
            white-space: nowrap;
        }


        .res_table td:nth-of-type(1):before { content: "No."; }
        .res_table td:nth-of-type(2):before { content: "Order Id"; }
        .res_table td:nth-of-type(3):before { content: "Test Name"; }
        .res_table td:nth-of-type(4):before { content: "Amount(Rs)"; }
        .res_table td:nth-of-type(5):before { content: "Date"; }
    }
</style>
<style>
    @media 
    only screen and (max-width: 767px),
    (min-device-width: 360px) and (max-device-width: 640px)  {

        /* Force table to not be like tables anymore */
        .res_table_cmpltjob thead, .res_table_cmpltjob tbody, .res_table_cmpltjob th, .res_table_cmpltjob td,.res_table_cmpltjob tr { 
            display: block; 
        }

        /* Hide table headers (but not display: none;, for accessibility) */
        .res_table_cmpltjob thead tr { 
            position: absolute;
            top: -9999px;
            left: -9999px;
        }

        .res_table_cmpltjob tr { border: 1px solid #ccc; }

        .res_table_cmpltjob td { 

            border: none;
            border-bottom: 1px solid #eee; 
            position: relative;
            padding-left: 50% !important; 
        }

        .res_table_cmpltjob td:before { 
            /* Now like a table header */
            position: absolute; 
            /* Top/left values mimic padding */
            <!-- top: 6px;
            left: 6px; -->
            width: 45%; 
            <!-- padding-right: 10px;  -->
            white-space: nowrap;
        }


        .res_table_cmpltjob td:nth-of-type(1):before { content: "No."; }
        .res_table_cmpltjob td:nth-of-type(2):before { content: "Job Order"; }
        .res_table_cmpltjob td:nth-of-type(3):before { content: "Test Name"; }
        .res_table_cmpltjob td:nth-of-type(4):before { content: "Amount(Rs)"; }
        .res_table_cmpltjob td:nth-of-type(5):before { content: "Date"; }
        .res_table_cmpltjob td:nth-of-type(6):before { content: "View Reports"; }
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
                        <h1 class="txt_green_clr res_txt_grn">My Tests</h1>
                    </div>
                    <div class="col-sm-12 pdng_0">
                        <div id="exTab3" class="container">	
                            <ul  class="nav nav-pills">

                                <li><a href="<?php echo base_url(); ?>user_master/my_job">Completed Report</a>
                                </li>
                                <li class="active">
                                    <a  href="#1b" class="tb1" >Pending Report</a>
                                </li>
                                <!--<li><a href="#3b" data-toggle="tab">Cancled Job</a>
                                </li>-->
                            </ul>
                            <div class="tab-content clearfix tb">
                                <div class="alert alert-warning">
                                    <strong>Note!</strong> You can delete job before sample collect.
                                </div>
                                <?php if (isset($success) != NULL) { ?>
                                    <div class="alert alert-success alert-dismissable">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        <?php echo $success['0']; ?>
                                    </div>
                                <?php } ?>
                                <div class="tab-pane active" id="1b">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <?php
                                            $cn = 1;
                                            foreach ($pending as $key) {
                                                ?>
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="my_job_report_main_div">
                                                        <div class="my_job_ordr_id_title">
                                                            <div class="full_div">
                                                                <div class=" col-sm-3">
                                                                    <span>Order Id :</span>
                                                                </div>
                                                                <span>#<?php echo $key['order_id']; ?></span>
                                                            </div>
                                                        </div>
                                                        <?php /* <a href="javascript:void(0)" onclick="delete_job('<?php echo $key['id']; ?>');">Delete</a> */ ?>
                                                        <div class="my_test_cmplt_top_div">
                                                            <div class="col-sm-3 pdng_0">
                                                                <div class="my_test_cmplt_report_lft_div">
                                                                    <img src="<?= base_url(); ?>user_assets/images/reporting-icon.png">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-9 pdng_0">
                                                                <div class="my_test_cmplt_report_mdl_div">

                                                                    <div class="full_div">
                                                                        <div class="col-sm-4">
                                                                            <span>Test Name :</span>
                                                                        </div>
                                                                        <div class="col-sm-8">
                                                                            <span><?php echo trim($key['test'], ","); ?></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="full_div">
                                                                        <div class="col-sm-4">
                                                                            <span>Amount :</span>
                                                                        </div>
                                                                        <div class="col-sm-8">
                                                                            <span><?php if ($key['added_by']) { ?>
                                                                                    <td><?php
                                                                                        if ($key['payable_amount'] != '') {
                                                                                            echo "Rs." . $key['price'];
                                                                                        } else {
                                                                                            echo "Rs." . $key['price'];
                                                                                        }
                                                                                        ?></td>
                                                                                <?php } else { ?>
                                                                                    <td><span data-toggle="tooltip" title="Cashback available credited to your wallet after collection of blood."><?php
                                                                                            if ($key['payable_amount'] != '') {
                                                                                                echo "Rs." . $key['price'];
                                                                                            } else {
                                                                                                echo "Rs." . $key['price'];
                                                                                            }
                                                                                            ?></span></td>
                                                                                <?php } ?></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="full_div">
                                                                        <div class="col-sm-4">
                                                                            <span>Date :</span>
                                                                        </div>
                                                                        <div class="col-sm-8">
                                                                            <span><?= $key['date'] ?></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="btm_prcssing_view_btn_div">
                                                            <div class="col-sm-9">
                                                                <div class="my_test_cmplt_report_prcssing">
                                                                    <div class="full_div"> 
                                                                        <?php if ($key['status'] == "1") { ?>
                                                                            <img src="<?= base_url(); ?>user_assets/report_img/1.png">
                                                                        <?php } ?>
                                                                        <?php if ($key['status'] == "6") { ?>
                                                                            <img src="<?= base_url(); ?>user_assets/report_img/2.png">
                                                                        <?php } ?>
                                                                        <?php if ($key['status'] == "7") { ?>
                                                                            <img src="<?= base_url(); ?>user_assets/report_img/3.png">
                                                                        <?php } ?>
                                                                        <?php if ($key['status'] == "8") { ?>
                                                                            <img src="<?= base_url(); ?>user_assets/report_img/4.png">
                                                                        <?php } ?>
                                                                        <?php if ($key['status'] == "2") { ?>
                                                                            <img src="<?= base_url(); ?>user_assets/report_img/5.png">
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <?php if ($key['status'] == "1" || $key['status'] == "6") { ?>
                                                                    <input type="button" id="note_save_btn_<?= $key["id"] ?>" onclick="delete_job('<?= $key["id"] ?>', '<?= $login_data["id"] ?>');" class="btn btn-dark btn-theme-colored btn-flat view_rpt_btn" value="Delete"/>
                                                                    <span id="loader_div2_<?= $key["id"] ?>" style="display:none;"><img src="<?= base_url(); ?>upload/opc-ajax-loader.gif" style="height:28px;margin-left:10px"></span>
                                                                <?php } ?>
                                                            </div> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            if (empty($pending)) {
                                                ?>
                                                <center>Data not available.</center>

                                            <?php } ?>

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

                <?php /* Nishit change payment status start */ ?>
                <!-- Modal -->
                <div class="modal fade" id="details_model" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Job Details</h4>
                            </div>
                            <div class="modal-body" id="modal_body">

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php /* Nishit change payment status end */ ?>

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

                                                                        /*function delete_job(jid, uid) {
                                                                         alert("hiii"); 
                                                                         var cnfrm = cinfirm("Are you sure?");
                                                                         if (cnfrm == true) {
                                                                         $.ajax({
                                                                         url: "<?php echo base_url(); ?>api_v4/job_delete",
                                                                         type: 'post',
                                                                         data: {uid: uid, jid: jid},
                                                                         beforeSend: function () {
                                                                         $("#loader_div2_" + jid).attr("style", "");
                                                                         $("#note_save_btn_" + jid).attr("disabled", "disabled");
                                                                         },
                                                                         success: function (data) {
                                                                         //window.location.reload(); 
                                                                         }, complete: function () {
                                                                         $("#loader_div2_" + jid).attr("style", "display:none;");
                                                                         $("#note_save_btn_" + jid).removeAttr("disabled");
                                                                         }
                                                                         });
                                                                         }
                                                                         }*/

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
                                                                        function delete_job(jid, uid) {
                                                                            var cnfg = confirm("Are you sure?");
                                                                            if (cnfg == true) {
                                                                                $.ajax({
                                                                                    url: "<?php echo base_url(); ?>api_v4/job_delete",
                                                                                    type: 'post',
                                                                                    data: {uid: uid, jid: jid},
                                                                                    beforeSend: function () {
                                                                                        $("#loader_div2").attr("style", "");
                                                                                        $("#note_save_btn").attr("disabled", "disabled");
                                                                                    },
                                                                                    success: function (data) {
                                                                                        //window.location.reload();
                                                                                    }, complete: function () {
                                                                                        $("#loader_div2").attr("style", "display:none;");
                                                                                        $("#note_save_btn").removeAttr("disabled");
                                                                                    }
                                                                                });
                                                                            }
                                                                        }
        </script>