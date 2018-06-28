<style>
    .res_table {float: left; width: 100%;}
    @media 
    only screen and (max-width: 767px)  {

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
        .res_table td:nth-of-type(3):before { content: "Payment For"; }
        .res_table td:nth-of-type(4):before { content: "Amount(Rs)"; }
        .res_table td:nth-of-type(5):before { content: "Date"; }
        .res_table td:nth-of-type(6):before { content: "Status"; color: #333; }
    }



</style>
<!-- Start main-content -->
<div class="main-content">
    <!-- Section: home -->
    <section>
        <div class="container pdng_top_20px pdng_btm_30px">
            <div class="row">
                <div class="col-sm-12">
                    <div class="login_main">
                        <div class="login_dark_back">
                            <h1 class="txt_green_clr res_txt_grn">Payment History</h1>
                            <?php /* if (!empty($history)) { ?>
                            <a href="<?php echo base_url(); ?>user_master/pdf_report" target="_blank" class="btn btn-dark btn-theme-colored btn-flat pull-right" data-loading-text="Please wait...">Create PDF Report</a>
                            <?php } */?>
                        </div>
                    </div>
                </div>
                <!-- <div class="wtable"> -->
                <div class="col-sm-12">
                    <div class="res_table">
                        <table class="table table-bordered">
                            <thead style="background:#0077A6;color:#fff;">
                                <tr>
                                    <th>No.</th>
                                    <th>Order Id</th>
                                    <th>Payment For</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $cn = 1;
                                foreach ($history as $key) {
                                    ?>
                                    <tr>

                                        <td><?php echo $cn; ?></td>
                                        <td>#<?php echo $key['payomonyid']; ?></td>
                                        <td><?php
                                            if ($key['type'] == "wallet") {
                                                echo "Added To Wallet";
                                            } else {
                                                echo $key['testname'];
                                            }
                                            ?></td>
                                        <td>Rs.<?php echo number_format($key['amount']); ?></td>
                                        <td><?php echo $key['date']; ?></td>
                                        <td style="<?php
                                        if ($key['status'] == "success") {
                                            echo "color:green";
                                        } else {
                                            echo "color:red";
                                        }
                                        ?>"><?php echo $key['status']; ?></td>

                                    </tr>
                                    <?php
                                    $cn++;
                                }
                                if (empty($history)) {
                                    ?>
                                    <tr>
                                        <td colspan="6"><center>Data not available.</center></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div style="text-align:right;" class="box-tools col-sm-12">
                    <ul class="pagination pagination-sm no-margin pull-right">
                        <?php echo $links; ?>
                    </ul>
                </div>

                <!-- </div> -->

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