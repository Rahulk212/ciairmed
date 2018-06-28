<style>
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
        .res_table td:nth-of-type(3):before { content: "Description"; }
        .res_table td:nth-of-type(4):before { content: "Date"; }
        .res_table td:nth-of-type(5):before { content: "Suggested Test"; }
    }



</style>
<!-- Start main-content -->
<div class="main-content">
    <!-- Section: home -->
    <section>
        <div class="container pdng_top_20px pdng_btm_30px">
            <div class="row">
                <form role="form" action="<?php echo base_url(); ?>user_master/upload_prescription" method="post" enctype="multipart/form-data">
                    <?php if (isset($success) != NULL) { ?>
                        <div class="alert alert-success alert-dismissable">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            <?php echo $success['0']; ?>
                        </div>
                    <?php } ?>
                    <?php if (isset($error) != NULL) { ?>
                        <div class="alert alert-danger alert-dismissable">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            <?php echo $error['0']; ?>
                        </div>
                    <?php } ?>
                    <div class="col-sm-12 pdng_0">
                        <div class="col-sm-6">
                            <div class="login_main">
                                <div class="login_dark_back">
                                    <h1 class="txt_green_clr res_txt_grn">Your Prescription</h1>
                                </div>
                                <!--<div class="login_light_back">
                                        <div class="col-sm-12 pdng_0 mrgn_btm_25px">
                                        <div class="col-md-6 col-xs-12">
                                                                        <div class="edt_usr_div">
                                                                                <img src="<?php echo base_url(); ?>user_assets/images/prescription_icon.jpg"/>
                                                                        </div>
                                                                        <div class="file_btn">
                                                                                <span class="file-input btn btn-primary btn-file">
                                                                                        Browse&hellip; <input type="file" name="userfile">
                                                                                </span>
                                                                        </div>
                                                                </div>
                                        </div>
                                        <div class="col-sm-12 pdng_0 mrgn_btm_25px">
                                                <div class="input-group" style="width:100%;">
                                                        <label>Message</label>
                                                        <textarea class="form-control required" placeholder="Enter Message" rows="5" name="desc" aria-required="true"></textarea>
                                                </div>
                                                <span style="color:red;"> <?php echo form_error('desc'); ?></span>
                                        </div>-->
                            </div>
                        </div>
                    </div>
                    <!--<div class="col-sm-6">
                            <img class="regi_rgt_img" src="<?php echo base_url(); ?>user_assets/images/prescriptions_2.jpg"/>
                    </div>-->
            </div>

            <div class="wtable wtbl_no_mrgn">
                <div class="col-sm-12">
                    <div class="res_table">
                        <table class="table table-bordered">
                            <thead class="wth">
                                <tr>
                                    <th>No.</th>
                                    <th>Order Id</th>
                                    <th class="prscptn_upld_dscrptn_head_wdth">Description</th>
                                    <th>Date</th>
                                    <th> Suggested Test </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $cn = 1;
                                foreach ($prescription as $key) {
                                    ?>
                                    <tr>
                                        <td><?php echo $cn; ?></td>
                                        <td>#<?php echo $key['order_id']; ?></td>
                                        <td class="ur_prscrptn_descrptn_hgt"><?php echo $key['description']; ?></td>
                                        <td><?php echo $key['created_date']; ?></td>

                                        <td><a class="psd_gry_fnt_clr" href="<?php echo base_url(); ?>user_master/suggested_test/<?php echo $key['id']; ?>">View Test </a></td>
                                    </tr>
                                    <?php
                                    $cn++;
                                }
                                if (empty($prescription)) {
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