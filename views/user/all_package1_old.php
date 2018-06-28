

<style>
    .border-search{ background-color: #fff;
                    border: 1px solid #000;
                    border-radius: 0;
                    -- border-top: 1px solid #d01130;
                    box-shadow: 1px 1px rgba(0, 0, 0, 0.4);
                    clear: left;}
    .indx_big_img {
        width: 100%;
        background-image: url(../images/new/home_1.png);
        background-attachment: fixed;
        background-size: cover;
        height: 480px !important;
        top: 10% !important;
        background-position: 0 80px;
    }
    #team{
        background:url(<?php echo base_url(); ?>user_assets/images/new/back-package.png); background-size:cover; padding:30px 0;background-repeat:no-repeat;
    }
    <!------.box-package{width:100%;float:left;position:relative; min-height:300px;	}----->

    
    .txt-box{width:100%;float:left;text-align:center; margin-bottom:15px;min-height:100px;max-height:150px;overflow:hidden;}
    .txt-box h3{margin: 10px 0 0 0;font-size: 17px;text-transform: uppercase;font-weight: 600;}
    .txt-box p{margin: 0; text-transform:uppercase;font-size: 18px;}
    .pdng_lft_0{padding-left:0}
    .pdng_rgt_0{padding-right:0}
    .red-label{position:absolute; right:3px;top:-5px;   z-index: 1;}
    .red-label p{background:#d01130;padding:2px;color:#fff;width:100px; text-align:center; font-size:18px;font-weight:600}
    .hover-over:hover{opacity:0.8;}
    section, footer{width:100%;float:left;}
</style>
<!-- Start main-content -->
<div class="main-content">
    <section id="about" class="package-div">


        <div class="gray-overlay" style="">

        </div>
        <div class="container" style="padding:0">
            <div class="row">
                <div class="col-md-4 col-sm-4">
                    <center>
                        <h1 class="font-40 font-w" style="line-height:38px; color:#BF2D37;">PACKAGES </h1>
                        <div class="border-btn">
                            <span></span>
                        </div>
                    </center>

                </div>
                <div class="col-sm-8">
                    <img src="<?= base_url(); ?>user_assets/images/new/inner-package.jpg">
                </div>

            </div>
        </div>


    </section>
    <section id="team_back_none" class="sctn_full">
        <div class="diff_box_containr">
            <div class="section-content">
                <div class="container">
                    <div class="col-sm-12 pdng_0">
                        <div class="col-md-9 col-sm-7">
                            <h1 class="subtitle txt_blue_clr" style="margin-bottom: 25px;COLOR:#000"><span class="brdr_btm_clr">ALL PACKAGES</span></h1>
                            <div id="packages">

                            </div>

                            <div class="col-md-12 pdng_0">

                                <div class="row">
                                    <?php foreach ($package_array as $key1) { ?>
                                        <div class="col-md-4 pdng_0 ">
                                            <a href="<?= base_url(); ?>user_master/package_details/<?= $key1[0]["id"] ?>" class="hover-over">
                                                <div class="box-package ">
                                                    <div class="red-label"><p>Rs.<?= $key1[1][0]["d_price"] ?></p></div>
                                                    <div class="img-box">
                                                        <img src="<?php echo base_url(); ?>upload/package/<?= $key1[0]["image"] ?>">
                                                    </div>
                                                    <div class="txt-box">
                                                        <h3><?= $key1[0]["title"] ?></h3>
            <!--                                            <p>Your text here </p>-->

                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    <?php } ?>
                                </div>

                            </div>

                        </div>
                        <div class="col-md-3 col-sm-5">
                            <h2 class="subtitle text-center txt_blue_clr" style="margin-bottom: 25px;COLOR:#000">TESTS SUGGESTED BY RISK AREAS</h2>
                           
                            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                <div class="panel panel-default">
                                    <div class="panel-heading pt_pb_0 all_pckgs_test_prz" role="tab" id="headingOne">
                                        <h4 class="panel-title plus_sign_none">
                                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                ANEMIA PROFILE 660 / <span>1600</span>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseOne" class="panel-collapse collapse all_pckgs_test_prz" role="tabpanel" aria-labelledby="headingOne">
                                        <div class="panel-body">
                                            <ul class="inner-line">
                                                <li>- CBC 110  /  <span>200</span></li>
                                                <li>- IRON 150 / <span>200</span></li>
                                                <li>- FERRITIN 200  /  <span>500</span></li>
                                                <li>- VITAMIN B12 200  /  <span>700</span></li>
                                            </ul>
                                        </div>
                                        <!---- <center>
                                                <a href="#" class="btn btn-dark btn-theme-colored btn-flat mb-30 ">Book</a>
                                        </center> ---->
                                    </div>
                                </div>

                                <div class="panel panel-default">
                                    <div class="panel-heading pt_pb_0 all_pckgs_test_prz" role="tab" id="headingTwo">
                                        <h4 class="panel-title plus_sign_none">
                                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                PREGNANCY DETECTION TEST 320  / <span> 550</span>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseTwo" class="panel-collapse collapse all_pckgs_test_prz" role="tabpanel" aria-labelledby="headingTwo">
                                        <div class="panel-body">
                                            <ul class="inner-line">
                                                <li>- UPT BY CARD 120  /  <span>150</span></li>
                                                <li>- B.HCG 200  /  <span>400</span></li>
                                            </ul>
                                        </div>
                                        <!---- <center>
                                                <a href="#" class="btn btn-dark btn-theme-colored btn-flat mb-30 ">Book</a>
                                        </center> ---->
                                    </div>
                                </div>

                                <div class="panel panel-default">
                                    <div class="panel-heading pt_pb_0 all_pckgs_test_prz" role="tab" id="headingThree">
                                        <h4 class="panel-title plus_sign_none">
                                            <a class="collapsed title-red"" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                INFECTION 410  /  <span>680</span>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseThree" class="panel-collapse collapse all_pckgs_test_prz" role="tabpanel" aria-labelledby="headingThree">
                                        <div class="panel-body">
                                            <ul class="inner-line">
                                                <li>- CBC 110  /  <span>200</span></li>
                                                <li>- ESR 50  /  <span>80</span></li>
                                                <li>- CRP 200  /  <span>300</span></li>
                                                <li>- URINE R/M 50  / <span> 100</span></li>
                                            </ul>
                                        </div>

                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading pt_pb_0 all_pckgs_test_prz" role="tab" id="headingFour">
                                        <h4 class="panel-title plus_sign_none">
                                            <a class="collapsed title-red"" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                                HEPATITIS PROFILE 1500  /  <span>2250</span>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseFour" class="panel-collapse collapse all_pckgs_test_prz" role="tabpanel" aria-labelledby="headingFour">
                                        <div class="panel-body">
                                            <ul class="inner-line">
                                                <li>- HBsAG 150  / <span> 250</span></li>
                                                <li>- HCV 350  /  <span>650</span></li>
                                                <li>- HAV Igm 400  /  <span>600</span></li>
                                                <li>- LFT  600  /  <span>750</span></li>
                                            </ul>
                                        </div>

                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading pt_pb_0 all_pckgs_test_prz" role="tab" id="headingFive">
                                        <h4 class="panel-title plus_sign_none">
                                            <a class="collapsed title-red"" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                                JOINT PROFILE 830/<span>1410</span>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseFive" class="panel-collapse collapse all_pckgs_test_prz" role="tabpanel" aria-labelledby="headingFive">
                                        <div class="panel-body">
                                            <ul class="inner-line">
                                                <li>- CBC WITH ESR 110/<span>200</span></li>
                                                <li>- RA 200  /  <span>300</span></li>
                                                <li>- CRP 200  /  <span>300 </span></li>
                                                <li>- ASO 200 / <span>400</span></li>
                                                <li>- RBS 40/<span>60</span></li>
                                                <li>- URIC ACID 80/<span>150</span></li>
                                            </ul>
                                        </div>

                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading pt_pb_0 all_pckgs_test_prz" role="tab" id="headingSix">
                                        <h4 class="panel-title plus_sign_none">
                                            <a class="collapsed title-red"" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                                HEART  PROFILE 1620  /  <span>3000</span>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseSix" class="panel-collapse collapse all_pckgs_test_prz" role="tabpanel" aria-labelledby="headingSix">
                                        <div class="panel-body">
                                            <ul class="inner-line">
                                                <li>- LIPID PROFILE 180  /  <span>400</span></li>
                                                <li>- HOMOCYSTEINE 500  /  <span>1000</span></li>
                                                <li>- APOLIPOPROTEIN – A 180  /  <span>300</span></li>
                                                <li>- APOLIPOPROTEIN –B 80  /  <span>300</span></li>
                                                <li>- HS – CRP 300  /  <span>400</span></li>
                                                <li>- HBA1C 200  /  <span>400</span></li>
                                                <li>- CREATININE 80  /  <span>150 </span></li>
                                            </ul>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <h2 class="subtitle text-center txt_blue_clr" style="margin-bottom: 25px;COLOR:#000">PROFILE SUGGESTED BY HABITS</h2>
                            <div class="panel-group" id="accordion1" role="tablist" aria-multiselectable="true">
                                <div class="panel panel-default">
                                    <div class="panel-heading pt_pb_0 all_pckgs_test_prz" role="tab" id="headingSeven">
                                        <h4 class="panel-title plus_sign_none">
                                            <a role="button" data-toggle="collapse" data-parent="#accordion1" href="#collapseSeven" aria-expanded="true" aria-controls="collapseSeven">
                                                ZERO EXERCISE 1020  /  <span>2110</span>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseSeven" class="panel-collapse collapse all_pckgs_test_prz" role="tabpanel" aria-labelledby="headingSeven">
                                        <div class="panel-body">
                                            <ul class="inner-line">
                                                <li>- LIPID PROFILE 180  /  <span>450</span></li>
                                                <li>- HBA1C 200  /  <span>400</span></li>
                                                <li>- FBS 40  /  <span>60</span></li>
                                                <li>- VITAMIN D 600  /  <span>1200</span></li>
                                            </ul>
                                        </div>
                                        <!---- <center>
                                                <a href="#" class="btn btn-dark btn-theme-colored btn-flat mb-30 ">Book</a>
                                        </center> ---->
                                    </div>
                                </div>

                                <div class="panel panel-default">
                                    <div class="panel-heading pt_pb_0 all_pckgs_test_prz" role="tab" id="headingEight">
                                        <h4 class="panel-title plus_sign_none">
                                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion1" href="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                                                STRESS / ANGER 220  /  <span>460</span>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseEight" class="panel-collapse collapse all_pckgs_test_prz" role="tabpanel" aria-labelledby="headingEight">
                                        <div class="panel-body">
                                            <ul class="inner-line">
                                                <li>- LIPID PROFILE 180  /  <span>400</span></li>
                                                <li>- FBS 40  /  <span>60</span></li>
                                            </ul>
                                        </div>
                                        <!---- <center>
                                                <a href="#" class="btn btn-dark btn-theme-colored btn-flat mb-30 ">Book</a>
                                        </center> ---->
                                    </div>
                                </div>

                                <div class="panel panel-default">
                                    <div class="panel-heading pt_pb_0 all_pckgs_test_prz" role="tab" id="headingNine">
                                        <h4 class="panel-title plus_sign_none">
                                            <a class="collapsed title-red"" role="button" data-toggle="collapse" data-parent="#accordion1" href="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
                                                JUNK FOOD 420  /  <span>910</span>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseNine" class="panel-collapse collapse all_pckgs_test_prz" role="tabpanel" aria-labelledby="headingNine">
                                        <div class="panel-body">
                                            <ul class="inner-line">
                                                <li>- LIPID PROFILE 180  /  <span>400</span></li>
                                                <li>- FBS 40  /  <span>60</span></li>
                                                <li>- HBA1C 200  /  <span>400</span></li>

                                            </ul>
                                        </div>

                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading pt_pb_0 all_pckgs_test_prz" role="tab" id="headingTen">
                                        <h4 class="panel-title plus_sign_none">
                                            <a class="collapsed title-red"" role="button" data-toggle="collapse" data-parent="#accordion1" href="#collapseTen" aria-expanded="false" aria-controls="collapseTen">
                                                POOR NUTRITION 1600  /  <span>3500</span>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseTen" class="panel-collapse collapse all_pckgs_test_prz" role="tabpanel" aria-labelledby="headingTen">
                                        <div class="panel-body">
                                            <ul class="inner-line">
                                                <li>- CBC 110  / <span>200</li>
                                                <li>- LIPID PROFILE 180  /  <span>400</span></li>
                                                <li>-  IRON 150  /  <span>200</span></li>
                                                <li>- VITAMIN B12 200  /  <span>700</span></li>
                                                <li>- VITAMIN D 600  /  <span>1200</span></li>
                                                <li>- CALCIUM 80  / <span> 150</span></li>
                                                <li>- PROTEIN 80  /  <span>150</span></li>
                                                <li>- FERRITIN 200  / <span> 500</span></li>
                                            </ul>
                                        </div>

                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading pt_pb_0 all_pckgs_test_prz" role="tab" id="headingEleven">
                                        <h4 class="panel-title plus_sign_none">
                                            <a class="collapsed title-red"" role="button" data-toggle="collapse" data-parent="#accordion1" href="#collapseEleven" aria-expanded="false" aria-controls="collapseEleven">
                                                SMOKER  1720  /  <span>3110</span>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseEleven" class="panel-collapse collapse all_pckgs_test_prz" role="tabpanel" aria-labelledby="headingEleven">
                                        <div class="panel-body">
                                            <ul class="inner-line">
                                                <li>- CEA 300  /  <span>600</span></li>
                                                <li>- RA 200  /  <span>300 </span></li>
                                                <li>- HBA1C 200  /  <span>400</span></li>
                                                <li>- CALCIUM 80  / <span> 150</span></li>
                                                <li>- VITAMIN D 600  /  <span>1200</span></li>
                                                <li>- HS – CRP 300  /  <span>400</span></li>
                                                <li>- FBS 40  /  <span>60</span></li>
                                            </ul>
                                        </div>

                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading pt_pb_0 all_pckgs_test_prz" role="tab" id="headingTwelve">
                                        <h4 class="panel-title plus_sign_none">
                                            <a class="collapsed title-red"" role="button" data-toggle="collapse" data-parent="#accordion1" href="#collapseTwelve" aria-expanded="false" aria-controls="collapseTwelve">
                                                OBESITY  1070  /  <span>1860 </span>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseTwelve" class="panel-collapse collapse all_pckgs_test_prz" role="tabpanel" aria-labelledby="headingTwelve">
                                        <div class="panel-body">
                                            <ul class="inner-line">
                                                <li>- LIPID PROFILE 180  / <span> 400</span></li>
                                                <li>- TSH 50  / <span> 250</span></li>
                                                <li>- FBS 40  /  <span>60</span></li>
                                                <li>- HBA1C 200  /  <span>400</span></li>
                                                <li>- LFT 600  / <span>750</span></li>
                                            </ul>
                                        </div>

                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading pt_pb_0 all_pckgs_test_prz" role="tab" id="headingThirteen">
                                        <h4 class="panel-title plus_sign_none">
                                            <a class="collapsed title-red"" role="button" data-toggle="collapse" data-parent="#accordion1" href="#collapseThirteen" aria-expanded="false" aria-controls="collapseThirteen">
                                                OVER  USE OF MEDICATION  900  / <span> 1350</span> 
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseThirteen" class="panel-collapse collapse all_pckgs_test_prz" role="tabpanel" aria-labelledby="headingThirteen">
                                        <div class="panel-body">
                                            <ul class="inner-line">
                                                <li>- LFT 600  / <span> 750</span></li>
                                                <li>- RFT 300  /  <span>600</span></li>
                                            </ul>
                                        </div>

                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading pt_pb_0 all_pckgs_test_prz" role="tab" id="headingFourteen">
                                        <h4 class="panel-title plus_sign_none">
                                            <a class="collapsed title-red"" role="button" data-toggle="collapse" data-parent="#accordion1" href="#collapseFourteen" aria-expanded="false" aria-controls="collapseFourteen">
                                                ALCOHOLISM  1120  /  <span>1810</span>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseFourteen" class="panel-collapse collapse all_pckgs_test_prz" role="tabpanel" aria-labelledby="headingFourteen">
                                        <div class="panel-body">
                                            <ul class="inner-line">
                                                <li>- LIPID PROFILE 180  /  <span>400</span></li>
                                                <li>- LIVER FUNCTION 600  /  <span>750</span></li>
                                                <li>- RENAL  FUNCTION 300  / <span>600</span></li>
                                                <li>- FBS 40  /  60</li>

                                            </ul>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="border-btn mt-30"><span style="background-color:black"></span></div>
                </div>
            </div>
        </div>
    </section>
    <section class="indx_mbl_ovrlay " style="padding-bottom:0;margin-bottom:0;">
        <div class="container mbl_containr" style="padding-bottom:0">
            <div class="row">
                <div class="col-sm-12 pdng_0">
                    <div class="indx_mbl_mdl">
                        <!--  <h1 class="mbl_title center">App Communication Space</h1>-->

                        <div class="col-sm-4  col-xs-4">
                            <img src="<?php echo base_url(); ?>user_assets/images/new/book-test.png"/> 
                        </div>
                        <div class="col-sm-4 col-xs-4">
                            <img src="<?php echo base_url(); ?>user_assets/images/new/manage-report.png"/> 
                        </div>
                        <div class="col-sm-4 col-xs-4">
                            <img src="<?php echo base_url(); ?>user_assets/images/new/share-report.png"/> 
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="indx_mbl_ovrlay" style="margin-bottom:0; background:#d7d7d7; background-repeat:no-repeat; ">
        <div class="container mbl_containr">
            <div class="row">
                <div class="col-sm-12" style="text-align:center;">
                    <div class="col-sm-1 col-xs-3 pdng_0 col-sm-offset-2 ">
                        <img src="<?php echo base_url(); ?>user_assets/images/new/icon-a.png"/> 
                    </div>
                    <div class="col-sm-7  col-xs-9 pdng_0 ">    <h1 class="mbl_title center" style="margin-top:0px; margin-bottom:0px;">DOWNLOAD AIRMED MOBILE APP<br/> & GET <b style="font-family: 'Montserrat', sans-serif;">30% CASH BACK</B> </h1>

                    </div>
                    <div class="clearfix"></div><br/>
                    <div class="col-sm-6  pdng_0 col-sm-offset-4">
                        <div class="col-sm-4">
                            <a href="https://itunes.apple.com/in/app/airmed-pathlabs/id1152367695?mt=8" target="_blank"><img class="app_full_img" src="<?php echo base_url(); ?>thumb_helper.php?h=53&w=173&src=user_assets/images/apple_appstore_big.png"/></a>
                        </div>
                        <div class="col-sm-4">
                            <a href="https://play.google.com/store/apps/details?id=com.patholab&hl=en" target="_blank"><img class="mbl_googl_res_mrgn app_full_img" src="<?php echo base_url(); ?>thumb_helper.php?h=54&w=173&src=user_assets/images/google_play.png"/></a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>


</div>
<!-- end main-content -->
<!--<script src="<?php echo base_url(); ?>user_assets/js/jquery-2.2.0.min.js"></script>--> 
<script src="<?php echo base_url(); ?>user_assets/js/jquery-ui.min.js"></script>
<script src="<?php echo base_url(); ?>user_assets/js/bootstrap.min.js"></script>
<script>

    function toggleIcon(e) {
        $(e.target)
                .prev('.panel-heading')
                .find(".short-full")
                .toggleClass('glyphicon-plus glyphicon-minus');
    }
    $('.panel-group').on('hidden.bs.collapse', toggleIcon);
    $('.panel-group').on('shown.bs.collapse', toggleIcon);
</script>
