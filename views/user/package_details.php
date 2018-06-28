<?php /* <section id="home">
  <div class="row">
  <div class="col-sm-12">
  <div class="awsth_inr_img_div" style="background-image: url('<?php echo base_url(); ?>upload/package/<?php echo $package[0]['back_image']; ?>'); background-repeat: no-repeat; background-size: 100% auto; height: 473px; background-size: cover; background-position: 100% center;">
  <div class="container">
  <div class="col-md-4 col-sm-6 pull-right pkg_dtl_cl4_mrgnrgt">
  <div class="pkgdetail_full">
  <div class="pkgdetail_full_titl">
  <h2 class="pkgdetail_titl">Health Checkup</h2>
  </div>
  <div class="pkgdetail_inpt_div">
  <div class="pkgdetail_full_in">
  <p class="pkgdtl_txt_p">Just give your Phone Number for Home Collection</p>
  </div>
  <?php echo form_open("user_master/package_inquiry/", array("role" => "form", "method" => "POST", "id" => "package_id")); ?>
  <div class="pkgdetail_full_in">
  <div class="input-group">
  <span class="input-group-addon pkgdtl_spn_91">+91</span>
  <input type="text" class="form-control" placeholder="Phone Number" name="mobile" id="mobile_id">
  <input type="hidden" class="form-control" value="<?php echo $pid; ?>" name="package">

  </div>
  <span style="color:red" id="mobile_error"><?= form_error('mobile'); ?></span>
  <!--<input type="text" placeholder="Phone Number" class="srch_pop_inpt"/>-->
  </div>
  <div class="pkgdetail_full_in">
  <div class="col-sm-3 pdng_0 set_pkgdtl_cl4_offset">
  <!--<a class="btn btn-dark btn-theme-colored btn-flat" href="#">Get a call back</a>-->
  <button type="button" class="btn btn-dark btn-theme-colored btn-flat" onclick="Validation();">Get a call back</button>
  </div>
  </div>
  </form>
  </div>
  </div>
  </div>
  </div>
  </div>
  </div>
  </div>
  <div role="dialog" id="myModal_payment" class="modal fade">
  <div class="modal-dialog">

  <!-- Modal content-->
  <div class="modal-content">

  <div class="modal-body">
  <div class="col-sm-12">
  <h4 style="width:-moz-max-content; float: left; text-align: center; width: 100%;" class="modal-title"><?= $success[0]; ?></h4>
  </div>
  <div id="model_body">
  <div style="width:100%;text-align:center;"><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQTV2P4sfl7-R0VRshYncNf7dJvh-FzqpokC4jaUBP4C6afbB9r"></div>
  </div>
  </div>
  <button style="display:none;" id="close_model" data-dismiss="modal" class="btn btn-primary" type="button"></button>
  <div style="display:none;" id="save_btn" class="modal-footer">
  <button onclick="frm_sbmt.click();" data-dismiss="modal" class="btn btn-primary" type="button">Save</button>
  <button id="close_model" data-dismiss="modal" class="btn btn-danger" type="button">Cancel</button>

  </div>
  </div>

  </div>
  </div>


  <script>
  <?php if ($success[0] != '') { ?>
  setTimeout(function () {
  $("#myModal_payment").modal('show');
  }, 3000);
  setTimeout(function () {
  $("#myModal_payment").modal('hide');
  }, 8000);
  <?php } ?>
  </script>
  <script>
  function Validation() {
  var cnt = 0;
  $('#mobile_error').html('');


  var mobile_id = $("#mobile_id").val();
  if(mobile_id == '') {
  $('#mobile_error').html('Please enter mobile number!');
  } else if (checkmobile(mobile_id) == true) {
  $("#package_id").submit();
  } else {
  $('#mobile_error').html('Invalid Number');
  }

  }
  function checkmobile(mobile) {
  var filter = /^[0-9-+]+$/;
  var pattern = /^\d{10}$/;
  if (filter.test(mobile)) {
  if(pattern.test(mobile)) {
  return true;
  } else {
  return false;
  }
  } else {
  return false;
  }
  }
  </script>
  </section>
  <!-- Start main-content -->
  <div class="main-content">
  <!-- Section: home -->
  <section>
  <div class="container pdng_top_20px pdng_btm_30px">
  <div class="col-md-12 col-sm-12 mrgn_btm_13px">
  <div class="pkg_dtl_price">
  <div class="col-sm-9 pdng_0">
  <span class="pkg_price_div">
  <span class="clr_pric_spn">Price </span>
  <i class="fa fa-play"></i>
  </span>
  <span class="pkg_dtl_spn_1">Rs.<?php echo $a_price; ?>/-</span>
  <span class="pkg_dtl_spn_2">Rs.<?php echo $d_price; ?>/-</span>
  </div>
  <div class="col-sm-2 pull-right">
  <a href="javascript:void(0);" onclick="get_book();" class="mrgn_top_6px pull-right" style="display:block;width:100%;">
  <img class="pkg_bk_now_btn" src="<?php echo base_url() ?>user_assets/images/book_now.png"/>
  </a>
  </div>
  </div>
  </div>
  <input type="hidden" id="package_name_id" value="<?php echo $package[0]['title']; ?>" />
  <input type="hidden" id="package_id_id" value="<?php echo $package[0]['id']; ?>" />
  <div class="col-md-12 col-sm-12 main_swas_profile">
  <?php echo $package[0]['desc_web']; ?>

  <!--<div class="row">

  <div class="col-md-4 col-sm-4">
  <div class="inner_swas_profile">
  <div id="accordion1" class="panel-group accordion transparent">
  <div class="panel">
  <div class="panel-title"> <a data-parent="#accordion1" data-toggle="collapse" href="#accordion11" class="collapsed" aria-expanded="false"> <span class="open-sub"></span> <strong>
  Cardiac Risk Markers [6]
  </strong></a></div>
  <div id="accordion11" class="panel-collapse collapse" role="tablist" aria-expanded="false">
  <ul class="panel-content">
  <li>
  Homocysterine
  </li>
  <li>
  Upoprotein-A
  </li>
  <li>
  Apolipoprotein-A1
  </li>
  <li>
  Apolipoprotein-B
  </li>
  <li>
  C-Reactive Protein [hs-CRP]
  </li>
  <li>
  Apo B/ Apo A1 Ratio
  </li>
  </ul>
  </div>
  </div>
  </div>
  </div>
  </div>
  <div class="col-md-4 col-sm-4">
  <div class="inner_swas_profile">
  <div id="accordion1" class="panel-group accordion transparent">
  <div class="panel">
  <div class="panel-title"> <a data-parent="#accordion1" data-toggle="collapse" href="#accordion12" class="collapsed" aria-expanded="false"> <span class="open-sub"></span> <strong>
  Pancreas Profile [2]
  </strong></a></div>
  <div id="accordion12" class="panel-collapse collapse" role="tablist" aria-expanded="false">
  <ul class="panel-content">
  <li>
  Serum Amylase
  </li>
  <li>
  Serum Lipase
  </li>
  </ul>
  </div>
  </div>
  </div>
  </div>
  </div>
  <div class="col-md-4 col-sm-4">
  <div class="inner_swas_profile">
  <div id="accordion1" class="panel-group accordion transparent">
  <div class="panel">
  <div class="panel-title"><a data-parent="#accordion1" data-toggle="collapse" href="#accordion13" class="collapsed" aria-expanded="false"> <span class="open-sub"></span> <strong>Electrolytes Profile [2]</strong></a></div>
  <div id="accordion13" class="panel-collapse collapse" role="tablist" aria-expanded="false">
  <ul class="panel-content">
  <li>
  Sodium
  </li>
  <li>
  Cloride
  </li>

  </ul>
  </div>
  </div>
  </div>
  </div>
  </div>
  </div>
  <div class="row">
  <div class="col-md-4 col-sm-4">
  <div class="inner_swas_profile">
  <div id="accordion1" class="panel-group accordion transparent">
  <div class="panel">
  <div class="panel-title"><a data-parent="#accordion1" data-toggle="collapse" href="#accordion14" class="collapsed" aria-expanded="false"> <span class="open-sub"></span> <strong>
  Vitamin Profile [2]
  </strong></a></div>
  <div id="accordion14" class="panel-collapse collapse" role="tablist" aria-expanded="false">
  <ul class="panel-content">
  <li>Vitami D Total</li>
  <li>Vitamin B 12</li>
  </ul>
  </div>
  </div>
  </div>
  </div>
  </div>


  <div class="col-md-4 col-sm-4">
  <div class="inner_swas_profile">
  <div id="accordion1" class="panel-group accordion transparent">
  <div class="panel">
  <div class="panel-title"><a data-parent="#accordion1" data-toggle="collapse" href="#accordion15" class="collapsed" aria-expanded="false"> <span class="open-sub"></span> <strong>
  Thyroid Profile [3]
  </strong></a></div>
  <div id="accordion15" class="panel-collapse collapse" role="tablist" aria-expanded="false">
  <ul class="panel-content">
  <li>Total Triodothyronine [T3]</li>
  <li>Total Thyroxine [T4]</li>
  <li>Thytoid Stimulating Hormone [T5H]</li>
  </ul>
  </div>
  </div>
  </div>
  </div>
  </div>
  <div class="col-md-4 col-sm-4">
  <div class="inner_swas_profile">
  <div id="accordion1" class="panel-group accordion transparent">
  <div class="panel">
  <div class="panel-title"><a data-parent="#accordion1" data-toggle="collapse" href="#accordion16" class="collapsed" aria-expanded="false"> <span class="open-sub"></span><strong>
  Diabetic Screen [2]</strong></a></div>
  <div id="accordion16" class="panel-collapse collapse" role="tablist" aria-expanded="false">
  <ul class="panel-content">
  <li> HbA1c </li>
  <li> Average Blood Glucose </li>

  </ul>
  </div>
  </div>
  </div>
  </div>
  </div>
  </div>
  <div class="row">
  <div class="col-md-4 col-sm-4">
  <div class="inner_swas_profile">
  <div id="accordion1" class="panel-group accordion transparent">
  <div class="panel">
  <div class="panel-title"><a data-parent="#accordion1" data-toggle="collapse" href="#accordion17" class="collapsed" aria-expanded="false"> <span class="open-sub"></span>
  <strong>
  Iron Deficiency Profile [3]
  </strong></a></div>
  <div id="accordion17" class="panel-collapse collapse" role="tablist" aria-expanded="false">
  <ul class="panel-content">
  <li>  Serum Iron</li>
  <li>  total Iron Binding Capacity </li>
  <li>  % Transferrin Saturation</li>
  </ul>
  </div>
  </div>
  </div>
  </div>
  </div>
  <div class="col-md-4 col-sm-4">
  <div class="inner_swas_profile">
  <div id="accordion1" class="panel-group accordion transparent">
  <div class="panel">
  <div class="panel-title"><a data-parent="#accordion1" data-toggle="collapse" href="#accordion18" class="collapsed" aria-expanded="false"> <span class="open-sub"></span>
  <strong>
  Cholesterol Profile [8]
  </strong></a></div>
  <div id="accordion18" class="panel-collapse collapse" role="tablist" aria-expanded="false">
  <ul class="panel-content">
  <li>  Total Cholesterol</li>
  <li>  HDL Cholesterol </li>
  <li> Non - HDL Cholesterol</li>
  <li> Triglycerides</li>
  <li> LDL Cholesterol</li>
  <li> VLDL Cholesterol</li>
  <li> TC / HDL Cholesterol Ratio</li>
  <li> LDL / HDL Cholesterol Ratio </li>
  </ul>
  </div>
  </div>
  </div>
  </div>
  </div>


  <div class="col-md-4 col-sm-4">
  <div class="inner_swas_profile">
  <div id="accordion1" class="panel-group accordion transparent">
  <div class="panel">
  <div class="panel-title"><a data-parent="#accordion1" data-toggle="collapse" href="#accordion19" class="collapsed" aria-expanded="false"> <span class="open-sub"></span>
  <strong>
  Liver Profile [11]
  </strong></a></div>
  <div id="accordion19" class="panel-collapse collapse" role="tablist" aria-expanded="false">
  <ul class="panel-content">
  <li>  Gamma Glutamyl Transferase</li>
  <li>  Alkaline Phosphrase </li>
  <li> NBilirubin - Direct</li>
  <li> Bilirubin - Total</li>
  <li> Bilirubin - Indirect</li>
  <li> Protein - Total</li>
  <li> Serum Albumin</li>
  <li> Serum Globulin </li>
  <li> SGOT [AST] </li>
  <li> AGPT [ALT] </li>
  <li> Serum Albumin/Globulin Ratio </li>
  </ul>
  </div>
  </div>
  </div>
  </div>
  </div>
  </div>
  <div class="row">
  <div class="col-md-4 col-sm-4">
  <div class="inner_swas_profile">
  <div id="accordion1" class="panel-group accordion transparent">
  <div class="panel">
  <div class="panel-title"><a data-parent="#accordion1" data-toggle="collapse" href="#accordion20" class="collapsed" aria-expanded="false"> <span class="open-sub"></span>
  <strong>
  Kidney Profile [5]
  </strong></a></div>
  <div id="accordion20" class="panel-collapse collapse" role="tablist" aria-expanded="false">
  <ul class="panel-content">
  <li>  Calciuym</li>
  <li>  Uric Acid</li>
  <li> Blood Urea Nitrogen</li>
  <li> Seru Creatinine</li>
  <li> BUN / Serum Creatinine Ratio</li>

  </ul>
  </div>
  </div>
  </div>
  </div>
  </div>
  <div class="col-md-4 col-sm-4">
  <div class="inner_swas_profile">
  <div id="accordion1" class="panel-group accordion transparent">
  <div class="panel">
  <div class="panel-title"><a data-parent="#accordion1" data-toggle="collapse" href="#accordion21" class="collapsed" aria-expanded="false"> <span class="open-sub"></span>
  <strong>
  Testosterone
  </strong></a></div>
  <div id="accordion21" class="panel-collapse collapse" role="tablist" aria-expanded="false">
  <ul class="panel-content">
  </ul>
  </div>
  </div>
  </div>
  </div>
  </div>
  <div class="col-md-4 col-sm-4">
  <div class="inner_swas_profile">
  <div id="accordion1" class="panel-group accordion transparent">
  <div class="panel">
  <div class="panel-title"><a data-parent="#accordion1" data-toggle="collapse" href="#accordion22" class="collapsed" aria-expanded="false"> <span class="open-sub"></span>
  <strong>
  Complete Hemogram [28]
  </strong></a></div>
  <div id="accordion22" class="panel-collapse collapse" role="tablist" aria-expanded="false">
  <ul class="panel-content">


  </ul>
  </div>
  </div>
  </div>
  </div>
  </div>
  </div>-->
  </div><br>
  <div class="col-sm-12" style="margin-top:20px;">
  <div class="col-sm-3 pull-right">
  <!--<a href="<?php echo base_url(); ?>user_master"><button type="submit" class="btn btn-dark btn-theme-colored btn-flat pull-right">Book</button></a>-->
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

  <script>
  function get_book() {
  var ids = [];

  var names = [];

  var id = 'p-' + $("#package_id_id").val();
  var name = $("#package_name_id").val();

  ids.push(id);
  names.push(name);

  $.ajax({
  url: "<?php echo base_url(); ?>user_master/searching_test",
  type: 'post',
  data: {id: ids, name: names},
  success: function (data) {
  //     console.log("data"+data);
  window.location = "<?php echo base_url(); ?>user_master/order_search";
  }
  });

  }

  </script>


 */ ?>


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
    .box-package{width:100%;float:left;position:relative}

    .img-box{width:96%;margin:2%;float:left;  vertical-align: middle; height:200px;background:#ddd;min-height:200px;max-height:230px;overflow:hidden; margin-bottom:5px;text-align:center}
    .img-box img{height:100%; width:100%; max-width:inherit}
    .txt-box{width:100%;float:left;text-align:center; margin-bottom:15px;min-height:100px;max-height:150px;overflow:hidden;}
    .txt-box h3{font-size: 20px;}
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
    <!-- Section: home -->


    <section id="about" class="package-div">


        <div class="gray-overlay" style="">
            
        </div>
        <div class="container" style="padding:0">
            <div class="row">
                <div class="col-md-4 col-sm-4">
                    <center>
                        <h1 class="font-62 font-w" style="color:#4d4d4f;line-height:30px"> </h1> 
                        <p class="font-28"><?php echo $package[0]['title']; ?> <!--<span class="font-20 clr-red">63 PARAMETERS</span>--></p>
                    </center>
                    <center>
                        <p><span class="back-red font-24 font-w pdng_10" style="margin:0 10px 0 0;color:#fff"><img src="<?php echo base_url(); ?>user_assets/images/new/rupee.png" style="width:12px"> <?php echo $d_price; ?>/- </span>
                            <!--<span class="font-24" style="text-decoration: line-through;text-decoration-color:red;color:#4d4d4f;"><img src="https://cdn3.iconfinder.com/data/icons/indian-rupee-symbol/800/Indian_Rupee_symbol.png" style="width:12px"><?php echo $a_price; ?>/- </span>--></p>
                    </center>
                </div>
                <div class="col-md-8 col-sm-8">
                    <img src="<?php echo base_url(); ?>upload/package/<?php echo $package[0]['back_image']; ?>">
                </div>

            </div>
        </div>


    </section>

    <!-- Section: Departments  -->
    <section id="depertments" class="bg-white">
        <div class="container indx_4_img">
            <div class="section-content "> <!--col-md-10 col-md-offset-1-->
                <div class="row indx_round_div border-gray" style="">
                    <div class="col-md-8 col-sm-7 col-xs-12">
                        <h1 class="font-32 font-w cust-mrg"><?php echo $package[0]['title']; ?> <!--<span class="font-22 clr-red">63 PARAMETERS</span>--></h1>
                    </div>
                    <div class="col-md-4 col-sm-5 col-xs-12 extra-div-right">
                        <a href="javascript:void(0);" onclick="get_book();" class="btn-book mb-30 font-30 txt-transform-upp pull-xs-left">Book Test Online</a><br/>
                        <p class="pull-xs-left">OR <img src="<?= base_url(); ?>user_assets/images/new/phone.png" style="width:32px;"> <b>+91 8101-161616</b></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="border-btn mt-30 bg-white"><span></span></div>

    <section class="bg-white">
        <div class="container mbl_containr" style="padding-bottom:0">
            <div class="row">
                <?php echo $package[0]['desc_web']; ?>
                <?php /*
                  <div class="col-md-4 col-xs-12 pdng_0">

                  <h1 class="title-red">Thyroid Profile (3)</h1>
                  <ul class="inner-line">
                  <li>Total Triiodothyronine [T3]</li>
                  <li>Total Thyroxine [T4]</li>
                  <li>Thyroid Stimulating Hormone [TSH]</li>
                  </ul>

                  <h1 class="title-red">Lipid Profile (8)</h1>
                  <ul class="inner-line">
                  <li>Total Cholesterol</li>
                  <li>HDL Cholesterol</li>
                  <li> Non-HDL Cholesterol</li>
                  <li>Triglycerides</li>
                  <li>LDL Cholesterol</li>
                  <li>VLDL Cholesterol</li>
                  <li>TC / HDL Cholesterol Ratio</li>
                  <li>LDL / HDL Ratio</li>
                  </ul>

                  <h1 class="title-red">Vitamin Profile (2)</h1>
                  <ul class="inner-line">
                  <li>Vitamin D Total</li>
                  <li>Vitamin B12</li>
                  </ul>



                  </div>

                  <div class="col-md-4 col-xs-12 pdng_0">

                  <h1 class="title-red">Liver Profile (11)</h1>
                  <ul class="inner-line">
                  <li>Gamma Glutamyl Transferase</li>
                  <li>Alkaline Phosphatase</li>
                  <li>Bilirubin - Direct</li>
                  <li>Bilirubin - Total</li>
                  <li>Bilirubin - Indirect</li>
                  <li>Protein - Total</li>
                  <li>Serum Albumin</li>
                  <li>Serum Globulin</li>
                  <li>SGOT [AST]</li>
                  <li> SGPT [ALT]</li>
                  <li>Serum Albumin/Globulin Ratio</li>
                  </ul>
                  <h1 class="title-red">Diabetic Screen (2)</h1>
                  <ul class="inner-line">
                  <li>HbA1c</li>
                  <li>Average Blood Glucose</li>
                  </ul>
                  </div>

                  <div class="col-md-4 col-xs-12 pdng_0">

                  <h1 class="title-red">Iron Deficiency Profile (3)</h1>
                  <ul class="inner-line">
                  <li> Gamma Glutamyl Transferase
                  Serum Iron</li>
                  <li> Total Iron Binding Capacity
                  % Transferrin Saturation</li>
                  </ul>

                  <h1 class="title-red">Kidney Profile (5)</h1>
                  <ul class="inner-line">
                  <li>Calcium</li>
                  <li>Uric Acid</li>
                  <li>Blood Urea Nitrogen</li>
                  <li>Serum Creatinine</li>
                  <li>BUN/ Serum Creatinine Ratio</li>
                  </ul>
                  <h1 class="title-red">Complete Hemogram (28)</h1>



                  </div> */ ?>

            </div>
        </div>
    </section>

    <div class="border-btn mt-30"><span></span></div>
    <input type="hidden" id="package_name_id" value="<?php echo $package[0]['title']; ?>" />
    <input type="hidden" id="package_id_id" value="<?php echo $package[0]['id']; ?>" />
    <section id="team" class="sctn_full">
        <div class="diff_box_containr">
            <div class="section-content">
                <div class="container">
                    <div class="col-sm-12 pdng_0">
                        <h1 class="subtitle text-center txt_blue_clr" style="margin-bottom: 25px;COLOR:#000"><span class="brdr_btm_clr">SIMILAR PACKAGES</span></h1>
                        <div id="packages">

                        </div>

                        <div class="col-md-12 pdng_0">

                            <?php foreach($package_array as $key1){ ?>
                            <div class="col-md-4 col-sm-4 pdng_0 ">
                                <a href="<?=base_url();?>user_master/package_details/<?=$key1[0]["id"]?>" class="hover-over">
                                    <div class="box-package ">
                                        <div class="red-label"><p>Rs.<?=$key1[1][0]["d_price"]?></p></div>
                                        <div class="img-box">
                                            <img src="<?php echo base_url(); ?>upload/package/<?=$key1[0]["image"]?>">
                                        </div>
                                        <div class="txt-box">
                                            <h3><?=$key1[0]["title"]?></h3>
<!--                                            <p>Your text here </p>-->

                                        </div>
                                    </div>
                                </a>
                            </div>
                            <?php } ?>

                        </div>
                        <center>
                            <a href="<?= base_url(); ?>User_master/all_packages" class="btn btn-dark btn-theme-colored btn-flat mb-30 ">View More</a>
                        </center>
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
                    <div class="col-sm-7  col-xs-9 pdng_0 ">    <h1 class="mbl_title center" style="margin-top:0px; margin-bottom:0px;">DOWNLOAD AIRMED MOBILE APP<br/> & GET <b style="font-family: 'Montserrat', sans-serif;"><?php echo $this->cash_back[0]["caseback_per"]; ?>% CASH BACK</B> </h1>

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
                            function get_book() {
                                var ids = [];

                                var names = [];

                                var id = 'p-' + $("#package_id_id").val();
                                var name = $("#package_name_id").val();

                                ids.push(id);
                                names.push(name);

                                $.ajax({
                                    url: "<?php echo base_url(); ?>user_master/searching_test",
                                    type: 'post',
                                    data: {id: ids, name: names},
                                    success: function (data) {
                                        //     console.log("data"+data);
                                        window.location = "<?php echo base_url(); ?>user_master/order_search";
                                    }
                                });

                            }

</script>
