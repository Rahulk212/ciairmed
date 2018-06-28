
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    ul.mySection { margin: 16px; list-style: none; }
    ul.mySection li {margin-bottom: 15%; display: inline-block; width: 100%;}
    ul.mySection input[type=checkbox] { display: none; }
    ul.mySection label {
        display: table-cell; cursor: pointer;
        width: 100%; height: 100px; float: left;
        vertical-align: middle; text-align: center;
        background-color: #80ACDD;
    }
    ul.mySection label:hover { 
        background-color: #00A4EF; color: #fff; transition: all 0.25s;
    }
    ul.mySection label:hover h2 {color: #fff;}
    ul.mySection input[type=checkbox]:checked ~ label { 
        background-color: rgba(50, 200, 50, 1); 
    }
    input#btn { margin: 8px 18px; padding: 8px; }

    .sugsttext_botm_brdr {border: 5px solid #eeeeee; width: 100%; float: left; padding: 10px 10px 0 45px; position: relative; margin-bottom: 20px;}
    .sgsttst_checkbx_inpt {left: 15px; position: absolute; top:20px;}
    .sugsttext_botm_brdr h2 {margin-top: 0; font-size: 15px; margin-bottom: 0;}
</style>
<!-- Add jQuery library -->
<script type="text/javascript" src="<?php echo base_url(); ?>lib/jquery-1.10.2.min.js"></script>

<!-- Add mousewheel plugin (this is optional) -->
<script type="text/javascript" src="<?php echo base_url(); ?>lib/jquery.mousewheel.pack.js?v=3.1.3"></script>

<!-- Add fancyBox main JS and CSS files -->
<script type="text/javascript" src="<?php echo base_url(); ?>source/jquery.fancybox.pack.js?v=2.1.5"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>source/jquery.fancybox.css?v=2.1.5" media="screen" />

<!-- Add Button helper (this is optional) -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>source/helpers/jquery.fancybox-buttons.css?v=1.0.5" />
<script type="text/javascript" src="<?php echo base_url(); ?>source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>

<!-- Add Thumbnail helper (this is optional) -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" />
<script type="text/javascript" src="<?php echo base_url(); ?>source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>

<!-- Add Media helper (this is optional) -->
<script type="text/javascript" src="<?php echo base_url(); ?>source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>
<!--Pinkesh code start-->
<script type="text/javascript">
    var jq = $.noConflict();
    jq(document).ready(function () {
        /*
         *  Simple image gallery. Uses default settings
         */

        jq(".fancybox-effects-a").fancybox({
            'type': 'iframe',
            helpers: {
                title: {
                    type: 'outside'
                },
                overlay: {
                    speedOut: 0
                }
            }
        });
    });
</script>
<!-- Start main-content -->
<div class="main-content">
    <!-- Section: home -->
    <section>
        <div class="container pdng_btm_30px">
            <div class="row">
                <?php if (isset($error) != NULL) { ?>
                    <div class="alert alert-danger alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <?php echo $error['0']; ?>
                    </div>
                <?php } ?>
                <div class="col-md-6">
                    <div class="col-sm-12">
                        <p class="login_title_p">Prescription Details</p></div>
                    <div class="sgst_tst_full">
                        <div class="col-sm-2 col-xs-4">
                            <p class="psd_gry_fnt_clr">Order Id:</p>
                        </div>
                        <div class="col-sm-10 col-xs-8">
                            <p>#<?php echo $prescription[0]['order_id']; ?></p>
                        </div>
                    </div>
                    <div class="sgst_tst_full">
                        <div class="col-sm-2 col-xs-4">
                            <p class="psd_gry_fnt_clr">Date:</p>
                        </div>
                        <div class="col-sm-10 col-xs-8">
                            <p><?php echo $prescription[0]['created_date']; ?></p>
                        </div>
                    </div>
                    <div class="sgst_tst_full">
                        <div class="col-sm-2 col-xs-4">
                            <p class="psd_gry_fnt_clr">Status:</p>
                        </div>
                        <div class="col-sm-10 col-xs-8">
                            <p><?php if (!empty($prescription[0]["job_fk"])) { ?>
                                    <span class="label label-success">Completed</span>
                                <?php } else { ?>
                                    <span class="label label-warning">Pending</span>
                                <?php } ?></p>
                        </div>
                    </div>
                    <div class="sgst_tst_full">
                        <div class="col-sm-2 col-xs-4">
                            <p class="psd_gry_fnt_clr">Prescription:</p>
                        </div>
                        <div class="col-sm-10 col-xs-8">
                            <p><a class="fancybox-effects-a" title="<?php echo base_url(); ?>upload/<?php echo $prescription[0]['image']; ?>" href="<?php echo base_url(); ?>upload/<?php echo $prescription[0]['image']; ?>">
                                    <?php
                                    $check_file_type = explode(".", $prescription[0]['image']);
                                    $f_cnt = count($check_file_type);
                                    $file_type = $check_file_type[$f_cnt - 1];
                                    if (strtoupper($file_type) == "PDF") {
                                        ?>
                                        <i class="fa fa-file-pdf-o" style="font-size:40px; color:red;margin-top:-16px;"></i>
                                    <?php } else { ?>

                                        <i class="fa fa-file-image-o" style="font-size:40px; color:red;margin-top:-16px;"></i>
                                    <?php } ?>
                                </a></p>
                        </div>
                    </div>


                </div>
                <div class="col-md-6 res_sgst_pdng0">

                </div>



            </div>
            <?php if (!empty($test)) { ?>
                <!--<div class="col-sm-12 pdng_0">
                <h5 style="font-size: 25px;">Description</h5>
                <div class="bg-img-box border-10px p-20">
                        <p><?php
                if (!empty($test)) {
                    echo $test[0]['description'];
                }
                ?> </p>
                        </div>
        </div>-->

                <div class="col-sm-12 pdng_0">
                    <h5 style="font-size: 25px;">Suggested Tests : </h5>
                    <form id="myform" action="<?php echo base_url(); ?>user_master/prescription_book_test" method="post" enctype="multipart/form-data" >
                        <?php /* <input type="hidden" name="pid1" value="<?php echo $pid; ?>"> */ ?>
                        <?php
                        $cn = 1;
                        foreach ($test as $key) {
                            ?>
							<label class="sugst_test_full_checkbx" for="r<?php echo $cn; ?>">
                            <div class="sugsttext_botm_brdr">
                                <input class="sgsttst_checkbx_inpt" <?php
                                if (!empty($prescription[0]["job_fk"])) {
                                    echo "disabled";
                                }
                                ?> type="checkbox" id="r<?php echo $cn; ?>" name="id[]" value="t-<?php echo $key['testid']; ?>#@t@#<?php echo $key['test_name']; ?>" />
                                       <?php /* <input type="hidden" name="name[]" value="<?php echo $key['test_name']; ?>"> */ ?>
                                <h2><b><?php echo $key['test_name']; ?></b></h2>
                                <p><b><?php echo "Rs." . $key['price']; ?></b></p>
                                <!--<p><b>Description:</b> <?php echo $key['description']; ?></p>-->
                            </div>
							</label>
                        <?php $cn++; } ?>
                        <div class="full_div">
                            <?php /* $cn=1 ;foreach($test as $key){ ?>

                              <div class="col-sm-3 set_4_checkbx">
                              <div class="col-xs-1 pdng_0">
                              <input type="checkbox" id="r<?php echo $cn;?>" name="test[]" value="t-<?php echo $key['testid'];?>" />
                              </div>
                              <div class="col-xs-11 pdng_0">
                              <h2><?php echo $key['test_name'];?></h2>
                              <p>Rs. <?php echo $key['price'];?></p>
                              </div>
                              </div>

                              <?php  $cn++; } */ ?>
                        </div>

                        <?php if (!empty($test) && empty($prescription[0]["job_fk"])) { ?>
                            <div class="col-sm-12 pdng_0">
                                <div class="col-sm-3 pdng_0 pull-right">
                                    <button type="submit" id="book_btn" disabled="disabled" class="btn btn-dark btn-theme-colored btn-flat pull-right">Book</button>
                                </div>
                            </div>
                        <?php } ?>
                    </form>
                </div>

            <?php } else { ?>

                <h1> Test Not Suggested Yet! </h1>
            <?php } ?>
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
</div>
</div>
</section>

<!-- end main-content -->
<script>
    function getOptions() {
        var frm = document.getElementById("myform");
        var chk = frm.querySelectorAll('input[type=checkbox]:checked');
        var vals = [];
        for (var i = 0; i < chk.length; i++) {
            vals.push(chk[i].value);
        }
        alert(JSON.stringify(vals));
    }

    $("input[type='checkbox']").change(function () {
        if ($("input[type='checkbox']:checked").length) {
            $("#book_btn").removeAttr("disabled");
        } else {
            $("#book_btn").attr("disabled", "disabled");
        }
    })
</script>
<script src="<?php echo base_url(); ?>user_assets/js/jquery-2.2.0.min.js"></script>
<script src="<?php echo base_url(); ?>user_assets/js/jquery-ui.min.js"></script>
<script src="<?php echo base_url(); ?>user_assets/js/bootstrap.min.js"></script>