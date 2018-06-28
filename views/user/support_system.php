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
        .res_table td:nth-of-type(2):before { content: "Job Order"; }
        .res_table td:nth-of-type(3):before { content: "Test Name"; }
        .res_table td:nth-of-type(4):before { content: "Job Title"; }
        .res_table td:nth-of-type(5):before { content: "Amount(Rs)"; }
        .res_table td:nth-of-type(6):before { content: "Date"; }
        .res_table td:nth-of-type(7):before { content: "Cancel Request";}
    }



</style>
<!-- Start main-content -->
<div class="main-content">
    <!-- Section: home -->
    <section>
        <div class="container pdng_top_20px pdng_btm_30px">
            <div class="row">
                <!-- <div class="col-sm-12">
                
                <p class="login_title_p">Report</p>
                
                </div> -->
                <div class="col-md-12">
                    <div class="col-sm-12 pdng_0">
                        <div class="login_dark_back">
                            <h1 class="txt_green_clr res_txt_grn">Support/Help</h1>			
                            <?php if ($ticket != NULL) { ?>
                                <a href="<?php echo base_url(); ?>user_master/add_ticket" class="btn btn-dark btn-theme-colored btn-flat pull-right">Add</a>
                            <?php } ?>

                        </div>
                        <div class="widget">
                            <?php if (isset($success) != NULL) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $success['0']; ?>
                                </div>
                            <?php } ?>

                        </div>   
                    </div>
                    <?php if ($ticket != NULL) { ?><div class="res_table">
                            <table class="table payment_tbl">
                                <thead>
                                    <tr>
                                        <th><b>Ticket Number</b></th>
                                        <th><b>Subject</b></th>
                                        <th><b>Status</b></th>
                                        <th><b>Action</b></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ctn = 0;
                                    foreach ($ticket as $key) {
                                        ?>
                                        <tr>
                                            <td><?php echo $key['ticket']; ?></td>
                                            <td><?php echo $key['title']; ?></td>
                                            <td><?php
                                                if ($key['status'] == 1) {
                                                    echo "Open";
                                                } else {
                                                    echo "Closed";
                                                }
                                                ?></td>
                                            <td><a title="" data-toggle="tooltip" href="<?php echo base_url(); ?>user_master/view_ticket_details/<?php echo $key['ticket']; ?>" data-original-title="View more!"><i class="fa fa-external-link"></i></a>
                                                <a title="" data-toggle="tooltip" onclick="return confirm('Are you sure?');" href="<?php echo base_url(); ?>user_master/delete_ticket/<?php echo $key['id']; ?>" data-original-title="Delete!"><i class="fa fa-trash-o"></i></a></td>
                                        </tr>
                                        <?php
                                        $ctn++;
                                    }
                                    ?>
                                    <?php if ($ctn == 0) { ?>
                                        <tr>
                                            <td colspan="4"> Not Data Available</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div><?php } else { ?>
                        <div class="box-tools col-sm-12">
                            <p class="sprt_clk_hr_p"><i style="line-height: 1.5;" class="fa fa-hand-o-right fa_red_hand"></i> <span class="sprt_clk_hr_spn1">If you have any Query OR issue <span class="sprt_clk_hr_spn2"><a href="<?php echo base_url(); ?>user_master/add_ticket">Register a Query</a></span> we will get back to you.</span> </p>
                        </div>
                    <?php } ?>

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
</div>
<script src="<?php echo base_url(); ?>user_assets/js/jquery-2.2.0.min.js"></script>
<script src="<?php echo base_url(); ?>user_assets/js/jquery-ui.min.js"></script>
<script src="<?php echo base_url(); ?>user_assets/js/bootstrap.min.js"></script>