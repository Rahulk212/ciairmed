<style>
    .vw_rprt_cntr {text-align: center;}
    @media 
    only screen and (max-width: 767px),
    (min-device-width: 360px) and (max-device-width: 640px)  {
        .vw_rprt_cntr {text-align: left;}
        /* Force table to not be like tables anymore */
        .res_table_actv_pkg_dtl thead, .res_table_actv_pkg_dtl tbody, .res_table_actv_pkg_dtl th, .res_table_actv_pkg_dtl td,.res_table_actv_pkg_dtl tr { 
            display: block; 
        }

        /* Hide table headers (but not display: none;, for accessibility) */
        .res_table_actv_pkg_dtl thead tr { 
            position: absolute;
            top: -9999px;
            left: -9999px;
        }

        .res_table_actv_pkg_dtl tr { border: 1px solid #ccc; }

        .res_table_actv_pkg_dtl td { 

            border: none;
            border-bottom: 1px solid #eee; 
            position: relative;
            padding-left: 50% !important; 
        }

        .res_table_actv_pkg_dtl td:before { 
            /* Now like a table header */
            position: absolute; 
            /* Top/left values mimic padding */
            <!-- top: 6px;
            left: 6px; -->
            width: 45%; 
            <!-- padding-right: 10px;  -->
            white-space: nowrap;
        }


        .res_table_actv_pkg_dtl td:nth-of-type(1):before { content: "Package"; }
        .res_table_actv_pkg_dtl td:nth-of-type(2):before { content: "Booked Date"; }
        .res_table_actv_pkg_dtl td:nth-of-type(3):before { content: "Valid Upto"; }
        .res_table_actv_pkg_dtl td:nth-of-type(4):before { content: "For"; }
        .res_table_actv_pkg_dtl td:nth-of-type(5):before { content: "Action"; }
    }


    /* Example tokeninput style #2: Facebook style */

    #contener_1{padding:15px 0px; float:left; width:100%;}
    #family_div {display: inline-block; width:100%; float:left;}
    #family_div .input-group .select2.select2-container.select2-container--default {width: 370px !important; text-align:left; padding-left:10px;}
    /*.input-group.date.tym_slot_date_wdth_50{width:50%;}*/
    /* #contener_2 .input-group .select2.select2-container.select2-container--default {width: 370px !important; text-align:left; padding-left:10px;} */
    .select2.select2-container.select2-container--default{ border: 1px solid #ccc; border-radius: 4px; height: 40px; padding-left: 10px;}
    .select2-selection__rendered {padding-left: 0 !important;}
    .set_book_btnpopup.btn_flt_lft{float:left;}
    #phlebo_shedule {height: 250px; overflow-y: scroll; width:100%; margin-top:10px;}
    #phlebo_shedule > a {border: 1px solid #ccc; float: left; margin-right: 10px; margin-top: 10px;padding: 10px 10px 0; width: 47%;}
    #phlebo_shedule label{margin-right:10px; float:left;}
    #phlebo_shedule input{margin-top: 6px;}
</style> 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script>
<!-- Start main-content -->
<div class="main-content">
    <!-- Section: home -->
    <section>
        <div class="container pdng_top_20px pdng_btm_30px">
            <div class="row">

                <div class="col-sm-12">
                    <h1 class="txt_green_clr res_txt_grn">Active package Details</h1>
                </div>
                <div class="col-sm-12 pdng_0">
                    <div id="exTab3" class="container">	
                        <div class=""> 
                            <?php if (isset($success) != NULL) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $success['0']; ?>
                                </div>
                            <?php } ?>
                            <div class="tab-pane active" id="1b">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <?php if (date("Y-m-d") <= $active_package[0]["due_to"]) { ?>
                                            <a href="#" class="btn btn-dark btn-theme-colored btn-flat pull-right mb-10" data-toggle="modal" data-target="#myModal_2">Book</a> <?php } ?>
                                        <table class="table table-bordered res_table_actv_pkg_dtl">
                                            <thead>
                                                <tr style="color:white">
                                                    <th>Order Id</th>
                                                    <th>Booked date</th>
                                                    <th>Address</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($active_package as $key) { ?>
                                                    <tr>
                                                        <td>#<?= ucfirst($key["job_details"][0]["order_id"]); ?></td>
                                                        <td><?= ucfirst($key["book_date"]); ?></td>
                                                        <td><?= ucfirst($key["job_details"][0]["address"]); ?></td>
                                                        <td><?php if ($key["job_details"][0]['status'] == "1") { ?>
                                                                <img src="<?= base_url(); ?>user_assets/report_img/1.png">
                                                            <?php } ?>
                                                            <?php if ($key["job_details"][0]['status'] == "6") { ?>
                                                                <img src="<?= base_url(); ?>user_assets/report_img/2.png">
                                                            <?php } ?>
                                                            <?php if ($key["job_details"][0]['status'] == "7") { ?>
                                                                <img src="<?= base_url(); ?>user_assets/report_img/3.png">
                                                            <?php } ?>
                                                            <?php if ($key["job_details"][0]['status'] == "8") { ?>
                                                                <img src="<?= base_url(); ?>user_assets/report_img/4.png">
                                                            <?php } ?>
                                                            <?php if ($key["job_details"][0]['status'] == "2") { ?>
                                                                <img src="<?= base_url(); ?>user_assets/report_img/5.png">
                                                            <?php } ?></td> 
                                                        <td class="vw_rprt_cntr" style="vertical-align: middle;"><?php if ($key["job_details"][0]['status'] == "2") { ?>
                                                                <a href="<?= base_url(); ?>upload/report/<?= $key["report"][0]["report"] ?>" target="_blank" class="btn btn-sm btn-primary">View Report</a>
                                                            <?php } else {
                                                                echo "-";
                                                            } ?>
                                                        </td>
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

<?php /* Nishit popup code start */ ?>
            <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script>
            <!--pinkesh code -->
            <div class="modal fade" id="myModal_2" role="dialog"> 
                <div class="modal-dialog aftr_srch_fill_info_sm_popup">
                    <!-- Modal content-->
<?php echo form_open("user_test_master/book_test/" . $ids, array("role" => "form", "method" => "POST", "id" => "address_login")); ?>
                    <div class="modal-content srch_popup_full">
                        <div class="modal-header srch_popup_full srch_head_clr">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title clr_fff">Fill Information</h4>
                        </div>
                        <div class="modal-body srch_popup_full">
                            <div class="srch_popup_full">
                                <div class="col-sm-12 pdng_0 full_div">
                                    <input type="hidden" value="<?php echo $uid; ?>" name="book_user_id"/>
                                    <div id="contener_1">
                                        <div class="col-sm-12 pdng_0 full_div">
                                            <label style="margin-top: 0;">Test For: <?= $active_package[0]["family"]; ?></label> 
                                            <input type="hidden" id="family" value="<?= $active_package[0]["family_fk"]; ?>"/>
                                        </div>
                                        <span id="contener_1_error" style="color:red; width:100%; float:left;"></span>
                                    </div>
                                    <div id="contener_2" style="display:none;">
                                        <div class="col-sm-12 pdng_0" style="margin-bottom: 10px;">
                                            <div class="input-group resp_cntnr_2_adrs">
                                                <label style="float:left; width:100%;">Address:<span style="color:red;  font-size: 15px;">*</span></label>
                                                <textarea class="form-control" name="edit_Address" id="user_address"><?= $customer_info[0]["address"]; ?></textarea>
                                            </div>
                                            <div class="input-group resp_cntnr_2_adrs">
                                                <label style="float:left; width:100%;">Landmark :</label>
                                                <input class="form-control" type="text" name="landmark" id="landmark"/>
                                            </div>
                                        </div>
                                        <script>
                                            function initialize() {

                                                var input = document.getElementById('user_address');
                                                var autocomplete = new google.maps.places.Autocomplete(input);
                                            }

                                            // google.maps.event.addDomListener(window, 'load', initialize);
                                        </script>
                                        <div class="aftr_srch_or_div">OR</div>
                                        <div class="col-sm-12 pdng_0" style="margin-bottom: 10px;  margin-top: 10px;">
                                            <div class="input-group resp_cntnr_2_slct" style="display:inline-block;">
                                                <select name="job_address" id="job_address" onchange="user_address.value = this.value.trim();" class="form-control   ">
                                                    <option value="">--Select--</option>
                                                    <?php if (trim($customer_info[0]["address"]) != '') { ?><option><?= $customer_info[0]["address"]; ?></option><?php } ?>
                                                    <?php
                                                    $job_address_list1 = array();
                                                    foreach ($job_address_list as $job_address) {
                                                        if (!in_array($job_address['address'], $job_address_list1)) {
                                                            ?>
                                                            <option><?php echo $job_address['address']; ?></option>
    <?php } $job_address_list1[] = $job_address['address'];
}
?>
                                                </select>
                                            </div>
                                        </div>
                                        <span id="contener_2_error" style="color:red; width:100%; float:left;"></span>
                                    </div>
                                    <div id="contener_3" style="display:none;">
                                        <div class="col-sm-12 pdng_0" style="margin-bottom: 10px;">
                                            <label>Choose Time Slot:<span style="color:red;  font-size: 15px;">*</span></label>
                                            <div class="input-group date tym_slot_date_wdth_50" data-provide="">
                                                <input type="text" readonly="" value="" id="phlebo_shedule_date" class="form-control">
                                                <div class="input-group-addon">
                                                    <span class="glyphicon glyphicon-th"></span>
                                                </div>
                                            </div>
                                            <div id="phlebo_shedule"></div>
                                            <input type="hidden" id="booking_slot" value="0"/>
                                        </div>
                                        <span id="contener_3_error" style="color:red;"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer srch_popup_full">
                                <button type="button" id="book_back" style="display:none;" class="btn btn-default set_book_btnpopup btn_flt_lft" onclick='back_info_validation();' >Back</button>
                                <button type="button" id="final_book" class="btn btn-default set_book_btnpopup" onclick='info_validation();' >Next</button>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
            <script> function base_url() {
                    return '<?= base_url(); ?>';
                }
                book_test_id = 'p-<?= $key["package_fk"]; ?>';
                uid = '<?= $key["user_fk"]; ?>';
                t_date = '<?= date("m/d/Y"); ?>';
                typ = '<?= $key["family_fk"]; ?>';</script>
            <script src="<?php echo base_url(); ?>js/custome/active_packages.js"></script>
<?php /* Nishit popup code end */ ?>

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