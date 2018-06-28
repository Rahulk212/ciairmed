<style>
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

    .aftr_srch_family_info_div{ }
    .aftr_srch_family_info_div label{width:20%; float:left; font-weight: normal;}
    .aftr_srch_family_info_div input{width:80%; margin-bottom: 20px; height: 33px;}
    .aftr_srch_family_info_div select{width:80%; padding:5px 0; border-radius:4px;}
    .select2-container--default .select2-selection--single{ border-right: medium none;}
    .aftr_srch_or_div{ float: left; font-size: 18px; font-weight: bold; margin-bottom: 10px;  position: relative; text-align: center; width: 100%;}
    .aftr_srch_or_div::after {border: 1px solid #0077a6; content: "";left: 15px; position:absolute; top: 15px; width: 40%;}
    .aftr_srch_or_div::before {border: 1px solid #0077a6; content: ""; height: 2px; position: absolute; right: 15px; top: 15px; width: 40%;}
    .btn.btn-default.set_book_btnpopup.pull-right {padding: 5px 10px;}
    ul.token-input-list-facebook {
        overflow: hidden;
        height: auto !important;
        height: 1%;
        width: 100%;
        cursor: text;
        font-size: 12px;
        min-height: 1px;
        z-index: 999;
        margin: 0;
        padding: 7px 12px;
        background-color: #fff;
        list-style-type: none;
        clear: left; border-radius: 20px;
    }

    ul.token-input-list-facebook li input {
        border: 0;
        width: 100px;
        padding: 3px 8px;
        background-color: white;
        margin: 2px 0;
        -webkit-appearance: caret;
    }

    li.token-input-token-facebook {
        overflow: hidden;
        height: auto !important;
        height: 15px;
        margin: 3px;
        padding: 1px 3px;
        background-color: #eff2f7;
        color: #000;
        cursor: default;
        border: 1px solid #ccd5e4;
        font-size: 11px;
        border-radius: 5px;
        -moz-border-radius: 5px;
        -webkit-border-radius: 5px;
        float: left;
        /*white-space: nowrap;*/
    }

    li.token-input-token-facebook p {
        display: inline;
        padding: 0;
        margin: 0;
    }

    li.token-input-token-facebook span {
        color: #a6b3cf;
        margin-left: 5px;
        font-weight: bold;
        cursor: pointer;
    }

    li.token-input-selected-token-facebook {
        background-color: #5670a6;
        border: 1px solid #3b5998;
        color: #fff;
    }

    li.token-input-input-token-facebook {
        float: left;
        margin: 0;
        padding: 0;
        list-style-type: none;
    }

    div.token-input-dropdown-facebook {
        position: absolute;
        width: 567px;
        background-color: #fff;
        overflow: hidden;
        border-left: 1px solid #ccc;
        border-right: 1px solid #ccc;
        border-bottom: 1px solid #ccc;
        cursor: default;
        font-size: 11px;
        font-family: Verdana;
        z-index: 9999;
        height: 200px; overflow-y: scroll;
    }

    div.token-input-dropdown-facebook p {
        margin: 0;
        padding: 5px;
        font-weight: bold;
        color: #777;
    }

    div.token-input-dropdown-facebook ul {
        margin: 0;
        padding: 0;
    }

    div.token-input-dropdown-facebook ul li {
        background-color: #fff;
        padding: 3px;
        margin: 0;
        list-style-type: none;
    }

    div.token-input-dropdown-facebook ul li.token-input-dropdown-item-facebook {
        background-color: #fff;
    }

    div.token-input-dropdown-facebook ul li.token-input-dropdown-item2-facebook {
        background-color: #fff;
    }

    div.token-input-dropdown-facebook ul li em {
        font-weight: bold;
        font-style: normal;
    }

    div.token-input-dropdown-facebook ul li.token-input-selected-dropdown-item-facebook {
        background-color: #3b5998;
        color: #fff;
    }
    #searchbar{padding-left:0;}
    .srch_sftr_popup_inpt ul.token-input-list-facebook {padding: 2px 2px; border-radius: 4px; box-shadow: 0 0 8px #e4e4e4 inset;}


    @media only screen and (max-width: 767px) {
        ul.token-input-list-facebook {background: transparent;}
    }

    @media (min-width: 320px) and (max-width: 359px) {
        div.token-input-dropdown-facebook {width: 267px !important;}
    }

    @media (min-width: 360px) and (max-width: 374px) {
        div.token-input-dropdown-facebook {width: 305px !important;}
    }

    @media (min-width: 375px) and (max-width: 383px) {
        div.token-input-dropdown-facebook {width: 320px !important;}
    }

    @media (min-width: 384px) and (max-width: 413px) {
        div.token-input-dropdown-facebook {width: 330px !important;}
    }

    @media (min-width: 414px) and (max-width: 435px) {
        div.token-input-dropdown-facebook {width: 360px !important;}
    }

    @media (min-width: 436px) and (max-width: 479px) {
        div.token-input-dropdown-facebook {width: 383px !important;}
    }

    @media (min-width: 480px) and (max-width: 500px) {
        div.token-input-dropdown-facebook {width: 425px !important;}
    }

    @media (min-width: 501px) and (max-width: 567px) {
        div.token-input-dropdown-facebook {width: 446px !important;}
    }

    @media (min-width: 568px) and (max-width: 599px) {
        div.token-input-dropdown-facebook {width: 513px !important;}
    }

    @media (min-width: 600px) and (max-width: 666px) {
        div.token-input-dropdown-facebook {width: 545px !important;}
    }

    @media (min-width: 667px) and (max-width: 735px) {
        div.token-input-dropdown-facebook {width: 615px !important;}
    }

    @media (min-width: 736px) and (max-width: 767px) {
        div.token-input-dropdown-facebook {width: 680px !important;}
    }

    @media (min-width: 320px) and (max-width: 479px) {
        .navMenuSecWrapper.set_populr_div {width: 86%;}
        #phlebo_shedule > a{width:100%;}
    }

    @media (min-width: 480px) and (max-width: 574px) {
        .navMenuSecWrapper.set_populr_div {width: 91.7%;}
    }

    @media (min-width: 575px) and (max-width: 636px) {
        .navMenuSecWrapper.set_populr_div {width: 93.5%;}
    }

    @media (min-width: 637px) and (max-width: 767px) {
        .navMenuSecWrapper.set_populr_div {width: 94%;}
    }

    @media (min-width: 768px) and (max-width: 799px) {
        div.token-input-dropdown-facebook {width: 569px !important;}
        .navMenuSecWrapper.set_populr_div {width: 96%;}
        .inpt_lft_div {margin-right: 12px !important;}
        .srch_view_a {width: 100%; float: left;}
        .srch_high_img_p {background-size: contain; width: 100%;}
    }

    @media (min-width: 800px) and (max-width: 979px) {
        div.token-input-dropdown-facebook {width: 569px !important;}
        .navMenuSecWrapper.set_populr_div {width: 96%;}
        .inpt_lft_div {margin-right: 12px !important;}
        .srch_view_a {width: 100%; float: left;}
        .srch_high_img_p {background-size: contain; width: 100%;}
    }

    @media (min-width: 980px) and (max-width: 1024px) {
        div.token-input-dropdown-facebook {width: 569px !important;}
        .navMenuSecWrapper.set_populr_div {width: 96%;}
        .inpt_lft_div {margin-right: 12px !important;}
        .srch_view_a {width: 100%; float: left;}
        .srch_high_img_p {background-size: contain; width: 100%;}
    }
    @media (min-width: 1260px){
        <!----.modal-dialog.aftr_srch_fill_info_sm_popup{width:400px; margin:0 auto;}---->
        #contener_2 textarea{width:370px; border-radius: 4px;}
        #contener_2 .input-group .select2.select2-container.select2-container--default {width: 370px !important; text-align:left; padding-left:10px;}
    }
    @media (max-width:1024px){
        .input-group.resp_cntnr_2_adrs{width:100%; float:left;}
        .input-group.resp_cntnr_2_adrs textarea{width:100%; float:left;}
        #contener_2 textarea{border-radius: 4px;}
        .input-group.resp_cntnr_2_slct{width:100%;}
        .select2.select2-container.select2-container--default{width:100% !important;}
        .select2-container .select2-selection--single .select2-selection__rendered{padding-left: 0 !important;}
        .aftr_srch_family_info_div {float: left; width: 100%;}
        #family_div .input-group .select2.select2-container.select2-container--default{width:100% !important;}
        #family_div .select2.select2-container.select2-container--default{width:80% !important;}
    }
    #loader_div {
        float: right;
        margin-top: 6px;

    }
    .pac-container{z-index:3000}
</style>
<link href="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />
<!-- Start main-content -->
<div class="main-content">
    <!-- Section: home -->
    <section>
        <div class="container pdng_btm_30px">
            <div class="col-md-12 col-sm-12">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="aftr_srch_full">
                            <div class="srch_title">
                                <div class="col-sm-12">
                                    <h2 class="mrgn_0">Your Selected Test (<?= ucfirst($test_city_name[0]["name"]); ?>)</h2>
                                </div>

                            </div>
                            <div class="srch_long_div">
                                <div class="col-sm-6 col-md-8">
                                    <div class="srch_incld_div">
                                        <?php /* <p class="srch_frst_p"><b>Includes:</b></p> */ ?>
                                        <style>
                                            .searchresult li{border-bottom: 1px dotted #888;display: block;float: left; width: 100%; vertical-align:middle; padding:5px 0;}
                                            .searchresult li:last-child{border-bottom:0;}
                                            .searchresult h3, .searchresult h2 {margin: 0;}
                                            .searchresult p {margin: 0;}
                                            .searchresult div:first-child{text-align:left}
                                            .searchresult div {border-right: 1px dotted #888;}
                                            .searchresult div:last-child {border-right: none}
                                            .txt-yellow{background:yellow; padding:10px 30px; text-align:center; color:#d01130;text-transform:uppercase;}
                                        </style>
                                        <ul class="searchresult">
                                            <?php
                                            $p_prc = 0;
                                            $loop_cnt = 0;
                                            $new_test_names = array();
                                            foreach ($test_names as $key) {
                                                if ($key != '') {
                                                    $new_test_names[] = $key;
                                                }
                                            }
                                            $check = array();
                                            $test_names = $new_test_names;
                                            $fasting = 0;
                                            foreach ($test_names as $key) {
                                                $ts = explode('-', $test_ids[$loop_cnt]);
                                                $id = $ts[1];
                                                $check[] = $ts[0];
                                                if ($ts[0] == "t") {
                                                    $result = $this->user_master_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`fasting_requird`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master`.`id`='" . $id . "' AND test_master_city_price.city_fk='" . $test_city_session . "'");
                                                    $price = $result[0]["price"];
                                                    if ($result[0]["fasting_requird"] == 1) {
                                                        $fasting = 1;
                                                    }
                                                } else {
                                                    $query = $this->db->get_where('package_master_city_price', array('package_fk' => $id,"status"=>'1', "city_fk" => $test_city_session));
                                                    $result = $query->result();
                                                    $price = $result[0]->d_price;
                                                    $p_prc = $p_prc + $result[0]->d_price;
                                                }
                                                ?>
                                                <li id="li_id_<?php echo $test_ids[$loop_cnt]; ?>">  
                                                    <div class="col-md-8 col-sm-6 pdng_0">
                                                        <h2> <?php echo $key; ?></h2>
                                                    </div>
                                                    <div class="col-md-3 col-sm-4 res_pdng_0">
                                                        <h2> Rs.<?php echo $price; ?></h2>
                                                    </div>

                                                    <div class="col-md-1 col-sm-1 res_pdng_0">
                                                        <a href="javascript:void(0);" class="myAvailableTest" id="remove_<?php echo $test_ids[$loop_cnt]; ?>" title="<?php echo $key; ?>" onclick="remove_test('<?php echo $test_ids[$loop_cnt]; ?>');"><i class="fa fa-trash" style="color:red;"> </i></a> 
                                                    </div>
                                                </li> 
                                                <?php
                                                $loop_cnt++;
                                            }
                                            if (empty($test_names)) {
                                                ?>
                                                <span style="color:red;width:100%; float:left;">Please Add Your Tests </span>
                                            <?php }
                                            ?>
                                        </ul>
                                        <?php if ($total_price < 300) { ?>
                                            <span style="width:100%; float:left;background: #FDE4E1;color: #B2524F;font-size: 17px;font-weight: 600; text-align: center;">Sample Collection Charge will be Apply Below Total Amount of RS. 300</span>
                                        <?php } ?>
                                        <h1 class="txt-yellow">Fasting <?php
                                            if ($fasting == 1) {
                                                echo"Required";
                                            } else {
                                                echo "Not required";
                                            }
                                            ?></h1>
                                        <div class="srch_view_dtl">
<!-- <img src="<?= base_url(); ?>user_assets/images/fasting-icon.png">Fasting :<strong> <?php
                                            if ($fasting == 1) {
                                                echo"Required";
                                            } else {
                                                echo "Not required";
                                            }
                                            ?></strong>-->
                                            <a class="srch_view_a" href="#" data-toggle="modal" data-target="#myModal_view"><i class="fa fa-hand-o-right mrgn_lft_10px"></i> View Details</a>
                                            <a class="srch_view_a" href="#" data-toggle="modal" data-target="#myModal_view1"><i class="fa fa-plus-square"></i> Add test / Packages</a>
                                            <div class="modal fade" id="myModal_view" role="dialog">
                                                <div class="modal-dialog">

                                                    <!-- Modal content-->
                                                    <div class="modal-content srch_popup_full">
                                                        <div class="modal-header srch_popup_full srch_head_clr">
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title clr_fff">Your Selected Test</h4>
                                                        </div>
                                                        <div class="modal-body srch_popup_full">
                                                            <div class="srch_popup_full srch_popup_acco">
                                                                <div id="accordion1" class="panel-group accordion transparent">
                                                                    <?php
                                                                    $ctn = 0;
                                                                    $new_test_ids = array();
                                                                    foreach ($test_ids as $key) {
                                                                        if ($key != '') {
                                                                            $new_test_ids[] = $key;
                                                                        }
                                                                    }
                                                                    $test_ids = $new_test_ids;
                                                                    foreach ($test_ids as $key) {
                                                                        //$this->db->from('test_master');
                                                                        //echo $key;
                                                                        $ts = explode('-', $key);
                                                                        $id = $ts[1];
                                                                        if ($ts[0] == "t") {

                                                                            $query = $this->db->get_where('test_master', array('id' => $id));
                                                                            $result = $query->result();
                                                                            $name = $result[0]->test_name;
                                                                            $desc = $result[0]->description;
                                                                            /* Nishit code start */
                                                                            $query = $this->db->get_where('test_master_city_price', array('test_fk' => $id,'status'=>"1", "city_fk" => $test_city_session));
                                                                            $result = $query->result();
                                                                            $price = $result[0]->price;
                                                                            /* Nishit code end */
                                                                            //$price = $result[0]->price;
                                                                        } else {
                                                                            $query = $this->db->get_where('package_master', array('id' => $id));
                                                                            $result = $query->result();
                                                                            $name = $result[0]->title;
                                                                            $desc = $result[0]->desc_web;
                                                                            /* Nishit code start */
                                                                            $query = $this->db->get_where('package_master_city_price', array('package_fk' => $id,"status"=>'1', "city_fk" => $test_city_session));
                                                                            $result = $query->result();
                                                                            $price = $result[0]->d_price;
                                                                            /* Nishit code end */
                                                                            //$price = $result[0]->d_price;
                                                                        }
                                                                        ?>
                                                                        <div class="panel">
                                                                            <div class="panel-title"> <a data-parent="#accordion_1<?php echo $ctn; ?>" data-toggle="collapse" href="#accordion_1<?php echo $ctn; ?>" class="active" aria-expanded="true"> <span class="open-sub"></span> <strong><?php echo $name; ?></strong></a> </div>
                                                                            <div id="accordion_1<?php echo $ctn; ?>" class="panel-collapse collapse in" role="tablist" aria-expanded="true">
                                                                                <div class="panel-content">
                                                                                    <p>Price : Rs. <?php echo $price; ?></p>
                                                                                    <p>Description :  <?php echo $desc; ?></p>

                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <?php
                                                                        $ctn++;
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--<div class="modal-footer srch_popup_full">
                                                          <button type="button" class="btn btn-default" data-dismiss="modal">Add</button>
                                                        </div>-->
                                                    </div>

                                                </div>
                                            </div>

                                            <!-- Add test model --->
                                            <div class="modal fade" id="myModal_view1" role="dialog">
                                                <div class="modal-dialog">

                                                    <!-- Modal content-->
                                                    <div class="modal-content srch_popup_full">
                                                        <div class="modal-header srch_popup_full srch_head_clr">
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title clr_fff">Add New Test</h4>
                                                        </div>
                                                        <div class="modal-body srch_popup_full">
                                                            <div class="srch_popup_full srch_popup_acco">

                                                                <div id="searchbar" style="" class="srch_sftr_popup_inpt menuBtn">
                                                                    <input type="text" id="vidyagames1" /> 
                                                                </div>								
                                                                <div class="col-sm-2 pull-right pdng_0">
                                                                    <a href="#" style="margin-top: 10px;" class="btn btn-dark btn-theme-colored btn-flat pull-right" onclick="add_new_test();">Add</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--<div class="modal-footer srch_popup_full">
                                                          <button type="button" class="btn btn-default" data-dismiss="modal">Add</button>
                                                        </div>-->
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- add test model end -->
                                        </div>
                                    </div>
                                    <?php $testname = implode($test_names, ',');
                                    ?>

                                 <!--   <script>
                                        function remove_test(val) {
                                            $("#li_id_" + val).remove();
                                            //alert(val
                                            setTimeout(function () {
                                                var hidden_sting = '';
                                                var hidden_string_name = '';
                                                var elems = document.getElementsByClassName('myAvailableTest');
                                                for (var i = 0; i < elems.length; i++) {
                                                    var new_id = elems[i].id;
                                                    new_id = new_id.split("_");
                                                    var tag_name = elems[i].title;
                                                    if (i == 0) {
                                                        hidden_sting = hidden_sting + new_id[1];
                                                        hidden_string_name = hidden_string_name + tag_name;
                                                    } else {
                                                        hidden_sting = hidden_sting + "," + new_id[1];
                                                        hidden_string_name = hidden_string_name + "," + tag_name;
                                                    }
                                                }
                                                //console.log(hidden_sting+" final string");
                                                //console.log(hidden_string_name+" final string name");
                                                $('#selectedid').val(hidden_sting);
                                                $('#selectedname').val(hidden_string_name);
                                            }, 1000);
                                        }
                                    </script>
                                    <p class="set_srch_after_mdl_p">
                                    <?php $ttl_price = $total_price - $p_prc; ?>
                                    <?php if (in_array('t', $check) && in_array('p', $check)) { ?>
                                                                                                                                                                        Promo Code Applied. <span class="set_srch_after_mdl_spn_red">Rs <?php echo $ttl_price * 0.30; ?></span> cashback will be added to your wallet in <span class="set_srch_after_mdl_spn_green"><i class="fa fa-clock-o"></i> 24 hours</span> of successful transaction.
                                                                                                                                                                        <br>Cashback is not available for Package.
                                    <?php } else if (in_array('p', $check)) { ?>
                                                                                                                                                                        Cashback is not available for Package.
                                    <?php } else { ?>
                                                                                                                                                                        Promo Code Applied. <span class="set_srch_after_mdl_spn_red">Rs <?php echo $ttl_price * 0.30; ?></span> cashback will be added to your wallet in <span class="set_srch_after_mdl_spn_green"><i class="fa fa-clock-o"></i> 24 hours</span> of successful transaction.
                                    <?php } ?>
                                    </p>

                                    <input type="hidden" value="<?php echo $testname; ?>" id="testname"/>
                                    <div class="srch_incld_div">
                                            <p class="srch_frst_p"><b>Also includes:</b></p>
                                            <ul class="srch_ul_also">
                                                    <li>Liver Function Test, </li>
                                                    <li>Thyroid Profile-Total </li>
                                            </ul>
                                    </div>-->
                                </div>
                                <style>
                                    .middle-div{padding:0 55px; width:100%;float:left}
                                    .srch_high_ul li{border-bottom:1px dotted #888;margin:5px 0;width:100%;float:left;}
                                    .srch_high_ul li div{border-right:1px dotted #888;}
                                    .srch_high_ul li div:last-child{border-right:none;}
                                    .srch_incld_div > h1 {float: left;width: 100%;}
                                    .label-txt h3{text-align:left;margin:0}
                                    .label-txt  p{text-align:left;text-transform:capitalize}
                                    .label-txt h3{font-size:18px;}
                                    .set_srch_after_mdl_spn_green{font-size:13px;}
                                    .srch_high_ul p.srch_res_sml_p{ font-size: 12px;line-height: 35px;margin-bottom: 0 !important;}
                                </style>
                                <div class="col-sm-6 col-md-4">
                                    <!--<p class="srch_high_img_p"><img src="<?php echo base_url(); ?>user_assets/images/new/ribbon_2.png"/></p>-->
<!--                                    <p class="srch_high_img_p"><span class="srch_high_spn">High Quality Certified Lab</span></p>-->
                                    <ul class="srch_high_ul">
                                        <!--<li>
                                                <p class="srch_res_sml_p">MRP</p>
                                                <p class="srch_grey_p srch_p_line">2</p>
                                        </li>-->
                                        <li>
                                            <div class="col-md-6 col-sm-6 res_pdng_0">
                                                <p class="srch_res_sml_p">Total Tests</p>
                                            </div>
                                            <div class="col-md-6 col-sm-6 res_pdng_0">
                                                <p class="srch_grey_p"><?php echo count($test_names); ?></p>
                                            </div>
                                        </li>
                                        <?php if ($total_price < 300) { ?>
                                            <li>
                                                <div class="col-md-6 col-sm-6 res_pdng_0">
                                                    <p class="srch_res_sml_p">Total Test Amount</p>
                                                </div>
                                                <div class="col-md-6 col-sm-6 res_pdng_0">
                                                    <p class="srch_grey_p srch_clr_price">rs.<?php echo $total_price; ?></p>
                                                </div>

                                            </li>
                                            <li>
                                                <div class="col-md-6 col-sm-6 res_pdng_0">
                                                    <p class="srch_res_sml_p">Sample Collection Charge</p>
                                                </div>
                                                <div class="col-md-6 col-sm-6 res_pdng_0">
                                                    <p class="srch_grey_p srch_clr_price">Rs.<?php
                                                        if ($total_price < 300) {
                                                            $scc = 100;
                                                        } else {
                                                            $scc = 0;
                                                        } echo $scc;
                                                        ?></p>
                                                </div>

                                            </li>
                                        <?php } ?>
                                        <li>
                                            <div class="col-md-6 col-sm-6 res_pdng_0">
                                                <p class="srch_res_sml_p">Total Amount</p>
                                            </div>
                                            <div class="col-md-6 col-sm-6 res_pdng_0">
                                                <p class="srch_grey_p srch_clr_price">rs.<?php echo $total_price + $scc; ?></p>
                                            </div>

                                        </li>
                                        <li>
                                            <div class="col-md-6 col-sm-6 res_pdng_0">
                                                <p class="srch_res_sml_p">Cash Back</p>
                                            </div>
                                            <div class="col-md-6 col-sm-6 res_pdng_0">
                                                <p class="srch_grey_p srch_clr_price">Rs.<?php echo $ttl_price * 0.30; ?></p>
                                            </div>

                                        </li>

                                        <li>
                                            <div class="col-md-12 label-txt pdng_0">
                                                <p class="set_srch_after_mdl_p">
                                                    <?php $ttl_price = $total_price - $p_prc; ?>
                                                    <?php if (in_array('t', $check) && in_array('p', $check)) { ?>
                                                        Promo Code Applied. <span class="set_srch_after_mdl_spn_red">Rs <?php echo $ttl_price * 0.30; ?></span> cashback will be added to your wallet in <span class="set_srch_after_mdl_spn_green"><i class="fa fa-clock-o"></i> 24 hours</span> of successful transaction.
                                                        <br><br>Cashback is not available for Package.
                                                    <?php } else if (in_array('p', $check)) { ?>
                                                        Cashback is not available for Package.
                                                    <?php } else { ?>
                                                        Promo Code Applied. <span class="set_srch_after_mdl_spn_red">Rs <?php echo $ttl_price * 0.30; ?></span> cashback will be added to your wallet in <span class="set_srch_after_mdl_spn_green"><i class="fa fa-clock-o"></i> 24 hours</span> of successful transaction.
                                                    <?php } ?>
                                                </p>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="col-sm-4 pdng_0 pull-right">
                                        <?php $ids = implode($test_ids, ','); ?>
                                        <?php $tname = implode($test_names, '#'); ?>
                                        <input type="hidden" value="<?php echo $ids; ?>" id="selectedid"/>
                                        <input type="hidden" value="<?php echo $tname; ?>" id="selectedname"/>
                                        <?php if (!empty($test_names)) { ?>
                                            <?php if (isset($login_data['id'])) { ?>
                                                <?php /* <a href="<?php echo base_url(); ?>user_test_master/book_test/<?php echo $ids; ?>" class="btn btn-dark btn-theme-colored btn-flat pull-right">Book</a> */ ?>
                                                <!--<a href="#" class="btn btn-dark btn-theme-colored btn-flat pull-right" data-toggle="modal" data-target="#myModal_2">Book</a>-->
                                                <a href="#" class="btn btn-dark btn-theme-colored btn-flat pull-right" data-toggle="modal" data-target="#myModal_2">Book</a>
                                            <?php } else { ?>
                                                <!-- <a href="#" class="btn btn-dark btn-theme-colored btn-flat pull-right" data-toggle="modal" data-target="#myModal">Book</a> -->
                                                <a href="#" class="btn btn-dark btn-theme-colored btn-flat pull-right" data-toggle="modal" data-target="#myModal_1">Book</a>
                                                <?php
                                            }
                                        }
                                        ?>

                                        <div class="modal fade" id="myModal_1" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- Modal content-->
                                                <?php echo form_open("user_master/book_without_login/", array("role" => "form", "method" => "POST", "id" => "without_login")); ?>
                                                <div class="modal-content srch_popup_full">
                                                    <div class="modal-header srch_popup_full srch_head_clr">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title clr_fff">Book Now</h4>
                                                    </div>
                                                    <div class="modal-body srch_popup_full">
                                                        <div class="srch_popup_full">
                                                            <div class="col-sm-12 pdng_0 full_div">
                                                                <?php /* <?php $ids = implode($test_ids, ','); ?>
                                                                  <?php $test_names_1 = implode($test_names, ','); ?>
                                                                  <input type="hidden" value="<?php echo $ids; ?>" name="test_package_id" id="test_package_id"/>
                                                                  <input type="hidden" value="<?php echo $test_names_1; ?>" name="test_package_name"/>
                                                                  <center><b>Just share your number, We will do the rest.</b></center>
                                                                  <label class="pull-left full_div text-left">Mobile Number:</label>
                                                                  <div class="col-sm-12 pdng_0" style="margin-bottom: 10px;">
                                                                  <div class="input-group">
                                                                  <span class="input-group-addon pkgdtl_spn_91">+91</span>
                                                                  <input class="srch_pop_inpt nobrdr_rds_tplft decimal" id="mobile_id" type="text" placeholder="Mobile Number" name="mobile"/>
                                                                  </div>
                                                                  </div>
                                                                  <div id="mobile_error" style="color:red;"></div>
                                                                  <div class="col-sm-12 pdng_0" id="check_otp_div" style="display:none;">
                                                                  <div class="input-group">
                                                                  <span id="keyid" class="input-group-addon" style=""><i class="fa fa-key"></i></span>
                                                                  <input type="text"  placeholder="OTP" id="otp_val" class="form-control" name="otp">
                                                                  </div>
                                                                  </div>
                                                                  <div class="full_div pdng_0 mrgn_btm_13px">
                                                                  <div class="col-sm-12 pdng_0">
                                                                  <span style="color:red;" id="otp_error"></span>
                                                                  </div>
                                                                  <div class="col-sm-4 pdng_0" id="send_opt_2">
                                                                  <div class="input-group">
                                                                  <a href="javascript:void(0);" id="mycounter"></a><div id="countdown"></div>
                                                                  <a href="javascript:void(0);" id="resend_opt" class="btn btn-dark btn-theme-colored btn-flat" style="display:none;" onclick="resend_otp();">Resend OTP</a>
                                                                  <button type="button" onclick="resend_otp();" id="send_opt_1" class="btn btn-dark btn-theme-colored btn-flat" data-loading-text="Please wait...">Send OTP</button>
                                                                  <span id="loader_div" style="display:none;"><img src="<?= base_url(); ?>upload/opc-ajax-loader.gif" style="height:28px;margin-left:10px"></span>
                                                                  <span style="color:green;" id="otp_success"></span>
                                                                  </div>
                                                                  </div>
                                                                  </div>
                                                                  <div class="col-sm-12 pdng_0 full_div">
                                                                  <label class="pull-left full_div text-left">Payment Mode:</label>
                                                                  <input style="margin-right: 5px;" type="radio" id="cod_radio" checked>Cash on blood collection
                                                                  </div>
                                                                  <div id="cod_error" style="color:red;"></div>
                                                                  </div>
                                                                  </div> */ ?>
                                                                <label class="pull-left full_div text-left">Login</label>
                                                                <div class="col-sm-12 pdng_0" id="check_otp_div">
                                                                    <div class="input-group">
                                                                        <span id="keyid" class="input-group-addon" style=""><i class="fa fa-user"></i></span>
                                                                        <input type="text"  placeholder="Email" id="login_email" class="form-control" name="email">
                                                                    </div>
                                                                    <br>
                                                                </div> 
                                                                <div class="col-sm-12 pdng_0" id="check_otp_div">
                                                                    <div class="input-group">
                                                                        <span id="keyid" class="input-group-addon" style=""><i class="fa fa-key"></i></span>
                                                                        <input type="password"  placeholder="*******" id="login_pass" class="form-control" name="password">
                                                                    </div>
                                                                    <span id="login_error" style="color:red;"></span>
                                                                    <span id="login_loader_div" style="display:none;"><img src="<?= base_url(); ?>upload/opc-ajax-loader.gif" style="height:28px;margin-left:10px"></span>
                                                                    <br>
                                                                </div>

                                                                <a href="<?= base_url(); ?>register">Create a new account</a>
                                                            </div>
                                                            <div class="modal-footer srch_popup_full">
                                                                <?php /* <button type="button" id="final_book" class="btn btn-default set_book_btnpopup" onclick='Validation();' disabled="true" style="display:none;" >Book Now</button>
                                                                  <button type="button" onclick="test();" disabled="true" id="check_otp" style="" class="btn btn-dark btn-theme-colored btn-flat pull-right" data-loading-text="Please wait...">Verify</button> */ ?>
                                                                <button type="button" onclick="check_user_login();" id="check_login" style="" class="btn btn-dark btn-theme-colored btn-flat pull-right" data-loading-text="Please wait...">Login</button>
                                                            </div>
                                                        </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
        <!--                                        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCB5tjuFaH8pO08b5iD7T2_O0JBN7amWWU&libraries=places&callback=initAutocomplete"></script>-->

                                        <!--pinkesh code -->
                                        <div class="modal fade" id="myModal_2" role="dialog">
                                            <div class="modal-dialog aftr_srch_fill_info_sm_popup">
                                                <!-- Modal content-->
                                                <?php echo form_open("user_test_master/book_test/" . $ids, array("role" => "form", "method" => "POST", "id" => "address_login")); ?>
                                                <div class="modal-content srch_popup_full">
                                                    <div class="modal-header srch_popup_full srch_head_clr">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title clr_fff">Book</h4>
                                                    </div>
                                                    <div class="modal-body srch_popup_full">
                                                        <div class="srch_popup_full">
                                                            <div class="col-sm-12 pdng_0 full_div">
                                                                <input type="hidden" value="<?php echo $uid; ?>" name="book_user_id"/>
                                                                <div id="contener_1">
                                                                    <div class="col-sm-12 pdng_0 full_div">
                                                                        <div class="row bs-wizard" style="border-bottom:0;">
                                                                            <div class="col-xs-3 bs-wizard-step active">
                                                                                <div class="text-center bs-wizard-stepnum">Test For </div>
                                                                                <div class="progress"><div class="progress-bar"></div></div>
                                                                                <a href="#" class="bs-wizard-dot"></a>

                                                                            </div>
                                                                            <div class="col-xs-3 bs-wizard-step disabled"><!-- complete -->
                                                                                <div class="text-center bs-wizard-stepnum">Select Address</div>
                                                                                <div class="progress"><div class="progress-bar"></div></div>
                                                                                <a href="#" class="bs-wizard-dot"></a>

                                                                            </div>
                                                                            <div class="col-xs-3 bs-wizard-step disabled"><!-- complete -->
                                                                                <div class="text-center bs-wizard-stepnum">Schedule</div>
                                                                                <div class="progress"><div class="progress-bar"></div></div>
                                                                                <a href="#" class="bs-wizard-dot"></a>

                                                                            </div>
                                                                            <div class="col-xs-3 bs-wizard-step disabled"><!-- active -->
                                                                                <div class="text-center bs-wizard-stepnum">Payment</div>
                                                                                <div class="progress"><div class="progress-bar"></div></div>
                                                                                <a href="#" class="bs-wizard-dot"></a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="middle-div">
                                                                        <label style="margin-top: 0;">Test For:<span style="color:red; margin:3px;  font-size: 15px;">*</span></label>
                                                                        <input style="margin-right: 10px;  margin-left: 10px;" type="radio" name="type_user_family" id="type_user_self" value="self" checked><label for="type_user_self">Self</label>
                                                                        <input style="margin-right: 10px; margin-left:10px;" type="radio" name="type_user_family" id="type_user_family" value="family"><label for="type_user_family">Family</label>
                                                                        <div id="family_div" style="display:none;">
                                                                            <div class="col-sm-12 pdng_0" style="margin-bottom: 10px; text-align:center;">
                                                                                <div class="input-group resp_cntnr_2_adrs">
                                                                                    <select name="family_per" id="family_per" class="form-control">
                                                                                        <option value="" selected>--Select--</option>
                                                                                        <?php foreach ($relation_list as $relation1) { ?>
                                                                                            <option value="<?php echo $relation1['id']; ?>"><?php echo $relation1['name']; ?> (<?= $relation1['relation_name'] ?>)</option>
                                                                                        <?php } ?>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                            <div class="aftr_srch_or_div">OR</div>
                                                                            <div class="col-sm-12 pdng_0 aftr_srch_family_info_div" style="margin-bottom: 10px;">
                                                                                <label>Name:<span style="color:red;  font-size: 15px;">*</span></label> <input type="text" class="form-control" id="f_name" value=""/>
                                                                                <label>Relation: <span style="color:red;  font-size: 15px;">*</span></label>
                                                                                <select id="f_relation" class="form-control" style="margin-bottom: 15px; width: 80%;  padding-left: 10px;">
                                                                                    <option value="">--Select--</option>
                                                                                    <?php foreach ($relation as $rkey): ?>
                                                                                        <option value="<?php echo $rkey["id"]; ?>"><?php echo ucwords($rkey["name"]); ?></option>
                                                                                    <?php endforeach; ?>
                                                                                </select>
                                                                                <label style="margin-top:15px;">Phone:</label>
                                                                                <input style="margin-top:20px;" type="text" id="f_phone" class="form-control" value=""/>
                                                                                <label>Email:</label><input type="text" id="f_email" class="form-control" value=""/>
                                                                                <label>Gender: <span style="color:red;  font-size: 15px;">*</span></label>
                                                                                <select id="f_gender" class="form-control" style="margin-bottom: 15px; width: 80%;  padding-left: 10px;">
                                                                                    <option value="">--Select--</option>
                                                                                    <option value="male">Male</option>
                                                                                    <option value="female">Female</option>
                                                                                </select>
                                                                                <label style="margin-top:15px;">Birth date:<span style="color:red;  font-size: 15px;">*</span></label><input style="margin-top:20px;" type="text" class="form-control" name="birth_date" id="f_dob" value="">
                                                                                <span style="color:red;" id="f_error"></span>
                                                                                <center><a href="javascript:void(0);" class=" set_book_btnpopup pull-right" id="add_family_member"><i class="fa fa-plus-circle"></i>
                                                                                        Add New </a></center>
                                                                            </div> 
                                                                        </div>
                                                                        <span id="contener_1_error" style="color:red; width:100%; float:left;"></span>
                                                                    </div>
                                                                </div>
                                                                <div id="contener_2" style="display:none;">
                                                                    <div class="col-sm-12 pdng_0" style="margin-bottom: 10px;">
                                                                        <div class="row bs-wizard" style="border-bottom:0;">

                                                                            <div class="col-xs-3 bs-wizard-step complete">
                                                                                <div class="text-center bs-wizard-stepnum"><b>Test For </b></div>
                                                                                <div class="progress"><div class="progress-bar"></div></div>
                                                                                <a href="#" class="bs-wizard-dot"></a>

                                                                            </div>

                                                                            <div class="col-xs-3 bs-wizard-step active"><!-- complete -->
                                                                                <div class="text-center bs-wizard-stepnum">Select Address</div>
                                                                                <div class="progress"><div class="progress-bar"></div></div>
                                                                                <a href="#" class="bs-wizard-dot"></a>

                                                                            </div>

                                                                            <div class="col-xs-3 bs-wizard-step disabled"><!-- complete -->
                                                                                <div class="text-center bs-wizard-stepnum">Schedule</div>
                                                                                <div class="progress"><div class="progress-bar"></div></div>
                                                                                <a href="#" class="bs-wizard-dot"></a>

                                                                            </div>

                                                                            <div class="col-xs-3 bs-wizard-step disabled"><!-- active -->
                                                                                <div class="text-center bs-wizard-stepnum">Payment</div>
                                                                                <div class="progress"><div class="progress-bar"></div></div>
                                                                                <a href="#" class="bs-wizard-dot"></a>

                                                                            </div>
                                                                        </div>
                                                                        <div class="middle-div">
                                                                            <div class="input-group resp_cntnr_2_adrs">
                                                                                <label style="float:left; width:100%;">Address:<span style="color:red;  font-size: 15px;">*</span></label>
                                                                                <textarea class="form-control" name="edit_Address" id="user_address"><?= $customer_info[0]["address"]; ?></textarea>
                                                                            </div>
                                                                            <div class="input-group resp_cntnr_2_adrs">
                                                                                <label style="float:left; width:100%;">Landmark :</label>
                                                                                <input class="form-control" onFocus="geolocate()" type="text" name="landmark" id="landmark"/>
                                                                            </div>


                                                                            <div class="aftr_srch_or_div">OR</div>
                                                                            <div class="col-sm-12 pdng_0" style="margin-bottom: 10px;  margin-top: 10px;">
                                                                                <div class="input-group resp_cntnr_2_slct" style="display:inline-block;">
                                                                                    <select name="job_address" id="job_address" class="form-control   ">
                                                                                        <option value="">--Select--</option>
                                                                                        <?php if (trim($customer_info[0]["address"]) != '') { ?><option><?= $customer_info[0]["address"]; ?></option><?php } ?>
                                                                                        <?php
                                                                                        $job_address_list1 = array();
                                                                                        foreach ($job_address_list as $job_address) {
                                                                                            if (!in_array($job_address['address'], $job_address_list1)) {
                                                                                                ?>
                                                                                                <option><?php echo $job_address['address']; ?></option>
                                                                                                <?php
                                                                                            } $job_address_list1[] = $job_address['address'];
                                                                                        }
                                                                                        ?>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <span id="contener_2_error" style="color:red; width:100%; float:left;"></span>
                                                                </div>
                                                                <div id="contener_3" style="display:none;">
                                                                    <div class="col-sm-12 pdng_0" style="margin-bottom: 10px;">
                                                                        <div class="row bs-wizard" style="border-bottom:0;">

                                                                            <div class="col-xs-3 bs-wizard-step complete">
                                                                                <div class="text-center bs-wizard-stepnum"><b>Test For </b></div>
                                                                                <div class="progress"><div class="progress-bar"></div></div>
                                                                                <a href="#" class="bs-wizard-dot"></a>

                                                                            </div>

                                                                            <div class="col-xs-3 bs-wizard-step complete"><!-- complete -->
                                                                                <div class="text-center bs-wizard-stepnum"><b>Select Address</b></div>
                                                                                <div class="progress"><div class="progress-bar"></div></div>
                                                                                <a href="#" class="bs-wizard-dot"></a>

                                                                            </div>

                                                                            <div class="col-xs-3 bs-wizard-step active"><!-- complete -->
                                                                                <div class="text-center bs-wizard-stepnum">Schedule</div>
                                                                                <div class="progress"><div class="progress-bar"></div></div>
                                                                                <a href="#" class="bs-wizard-dot"></a>

                                                                            </div>

                                                                            <div class="col-xs-3 bs-wizard-step disabled"><!-- active -->
                                                                                <div class="text-center bs-wizard-stepnum">Payment</div>
                                                                                <div class="progress"><div class="progress-bar"></div></div>
                                                                                <a href="#" class="bs-wizard-dot"></a>

                                                                            </div>
                                                                        </div>
                                                                        <div class="middle-div">
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

                                        <!-- pinkesh code end -->
                                        <!-- Modal -->
                                        <div class="modal fade" id="myModal" role="dialog">
                                            <div class="modal-dialog">

                                                <!-- Modal content-->
                                                <div class="modal-content srch_popup_full">
                                                    <form action="<?php echo base_url(); ?>user_master/send_serch_test_email" method="post" enctype="multipart/form-data" id="uploadform"/>
                                                    <div class="modal-header srch_popup_full srch_head_clr">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title clr_fff">Register/Login</h4>
                                                    </div>
                                                    <div class="modal-body srch_popup_full">
                                                        <div class="srch_popup_full">
                                                            <!--<div class="col-sm-12 pdng_0">
                                                                    <input class="srch_pop_inpt" type="text" id="name" placeholder="Name" value="<?php //if(isset($user->full_name)) { echo $user->full_name; }                                                                                         ?>"/>
                                                            </div>-->
                                                            <!--<div class="col-sm-12 pdng_0 srch_slct_div">
                                                                    <select>
                                                                            <option>Relation</option>
                                                                            <option>Child</option>
                                                                            <option>Parent</option>
                                                                    </select>
                                                            </div>-->

                                                            <?php $ids = implode($test_ids, ',');
                                                            ?>
                                                            <input type="hidden" value="<?php echo $ids; ?>" name="testid"/>
                                                            <div class="col-sm-12 pdng_0">
                                                                <label class="pull-left full_div text-left">Email :</label>
                                                                <input class="srch_pop_inpt" id="email" type="text" placeholder="Email" name="email" value="<?php
                                                                if (isset($user->email)) {
                                                                    echo $user->email;
                                                                }
                                                                ?>" onchange="uploads()"/>
                                                                <div id="error_email" style="color:red;"></div>
                                                            </div>
                                                            <div class='col-sm-12 pdng_0' id="login_password" style="display:none;">
                                                                <label class="pull-left full_div text-left">Password :</label>
                                                                <input class='srch_pop_inpt' id="logpass" type='password' name="login_pass" placeholder='Password'/>
                                                                <div id="error_logpass" style="color:red;"></div>
                                                            </div>
                                                            <div id="register" style="display:none;">
                                                                <div class="col-sm-12 pdng_0">
                                                                    <label class="pull-left full_div text-left">Mobile No. :</label>
                                                                    <input class="srch_pop_inpt" id="mobile" type="text" placeholder="Mobile" name="mobile" value="<?php
                                                                    if (isset($user->mobile)) {
                                                                        echo $user->mobile;
                                                                    }
                                                                    ?>" onchange="validmobile()"/>
                                                                    <div id="error_mobile" style="color:red;"></div>
                                                                </div>
                                                                <div class="col-sm-12 pdng_0">
                                                                    <label class="pull-left full_div text-left">Address :</label>
                                                                    <textarea class="srch_pop_inpt" id="add" placeholder="Address" name="address"><?php
                                                                        if (isset($user->full_name)) {
                                                                            echo $user->address;
                                                                        }
                                                                        ?></textarea>
                                                                    <div id="error_add" style="color:red;"></div>
                                                                </div>
                                                                <div class='col-sm-12 pdng_0'>
                                                                    <label class="pull-left full_div text-left">Full Name :</label>
                                                                    <input class='srch_pop_inpt' type='text' name='reg_name' placeholder='Full Name' id="regname"/>
                                                                    <div id="error_regname" style="color:red;"></div>
                                                                </div>
                                                                <div class='col-sm-12 pdng_0'>
                                                                    <label class="pull-left full_div text-left">Password :</label>
                                                                    <input class='srch_pop_inpt' type='password' name="reg_pass" id="regpass" placeholder='Enter Password'/>
                                                                    <div id="error_regpass" style="color:red;"></div>
                                                                </div>
                                                                <div class='col-sm-12 pdng_0'>
                                                                    <label class="pull-left full_div text-left">Conform Password :</label>
                                                                    <input class='srch_pop_inpt' type='password' name="reg_conf_pass" id="regconpass" placeholder='Enter Conform Password'/>
                                                                    <div id="error_regconpass" style="color:red;"></div>
                                                                </div>
                                                                <div class='col-sm-12 pdng_0'><input type='radio' value='male' name='reg_gender'/>Male<input type='radio' value='female' name='reg_gender'/>Female</div>
                                                                <div id="error_reggen" style="color:red;"></div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="modal-footer srch_popup_full">
                                                        <button type="button" class="btn btn-default" onclick='vlidation_btn();'>Login/Register</button>
                                                    </div>
                                                    </form></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                </div>
                            </div>
                        </div>

                    </div>
                    <!--                    <div class="col-md-3 col-sm-4">
                                            <div class="hlth_rgt_full">
                    <?php foreach ($package as $key) { ?>
                                                                                                                                                                                <div class="team-member bg-white-fa maxwidth400 indx_six_back innr_six_set_fnt" style="margin-bottom:30px;">
                                                                                                                                                                                    <a href="<?php echo base_url(); ?>user_master/package_details/<?php echo $key['id']; ?>">
                                                                                                                                                                                        <div class="thumb">
                                                                                                                                                                                            <img class="img-fullwidth" src="<?php echo base_url(); ?>upload/package/<?php echo $key['image']; ?>" alt=""/>
                                                                                                                                                                                            <div class="indx_six_overlay"></div>
                                                                                                                                                                                            <div class="info p-15 pb-10 text-center">
                                                                                                                                                                                                <h3 class="name m-0 six_part_name"><?php echo ucfirst($key['title']); ?></h3>
                                                                                                                                                                                                <h5 class="occupation font-weight-400 letter-space-1 mt-0 six_part_price">
                                                                                                                                                                                                    <span class="no_price">Rs.<?php echo $key['a_price1']; ?>/-</span>
                                                                                                                                                                                                    <span>Rs.<?php echo $key['d_price1']; ?>/-</span>
                                                                                                                                                                                                </h5>
                                                                                                                                                                                            </div>
                                                                                                                                                                                        </div>
                                                                                                                                                                                    </a>
                                                                                                                                                                                </div>
                    <?php } ?>
                                            </div>
                                        </div>-->
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

    <script type="text/javascript" src="<?php echo base_url(); ?>user_assets/js/auto_input/jquery.tokeninput.js"></script>
    <script type="text/javascript">
                                                            var $j = jQuery.noConflict();
                                                            $j(function(){
                                                            $j('#vidyagames').tokenInput(
<?php
$new_ary = array();
$cnt12 = 0;
foreach ($test as $ts) {
    $new_ary[$cnt12]["id"] = "t-" . $ts['id'];
    $new_ary[$cnt12]["name"] = $ts['test_name'];
    ?>

    <?php
    $cnt12++;
} echo json_encode($new_ary);
?>

                                                            , {
                                                            theme: "facebook",
                                                                    noResultsText: "Nothing' found.",
                                                                    preventDuplicates: true,
                                                                    onAdd: function (item) {
                                                                    console.log("add ok");
//return false;
                                                                    var id = item.id;
                                                                    console.log(id + " add id1");
                                                                    setTimeout(function(){
                                                                    document.getElementById('li_id_' + id).setAttribute("onClick", "remove_token('" + id + "', '" + item.name + "');");
                                                                    }, 500);
                                                                    $('#' + id + '_add').css("display", "none");
                                                                    $('#' + id + '_close').css("display", "block");
                                                                    $('#btn_search').html('Book');
                                                                    return false;
                                                                    },
                                                                    onDelete: function (item) {
                                                                    var delete_id = item.id;
//remove_token(item.id, item.name);
                                                                    console.log(delete_id + " delete id1");
                                                                    setTimeout(function(){
                                                                    document.getElementById('li_id_' + item.id).setAttribute("onClick", "add_token('" + item.id + "', '" + item.name + "');");
                                                                    }, 500);
                                                                    document.getElementById(delete_id + "_close").setAttribute("style", "display:none;");
                                                                    document.getElementById(delete_id + "_add").setAttribute("style", "");
                                                                    return false;
                                                                    }, });
                                                            /*Nishit code start*/
                                                            $j("#myButton").click(function(){
//alert('hiii');
                                                            $j(".token-input-list-facebook").remove();
                                                            $j("#vidyagames").tokenInput("http://mysite.com?name=Adam");
                                                            });
                                                            /*Nishit code end*/

                                                            });
                                                            $j(function(){
                                                            $j('#vidyagames1').tokenInput(
<?php
$new_ary = array();
$cnt12 = 0;
foreach ($test as $ts) {
    $new_ary[$cnt12]["id"] = "t-" . $ts['id'];
    $new_ary[$cnt12]["name"] = $ts['test_name'];
    ?>

    <?php
    $cnt12++;
}
?>
<?php if (isset($package)) { ?>
    <?php
    foreach ($package as $p_key) {
        $new_ary[$cnt12]["id"] = "p-" . $p_key['id'];
        $new_ary[$cnt12]["name"] = $p_key['title'];
        ?>
        <?php
        $cnt12++;
    }
    ?>
<?php } echo json_encode($new_ary); ?>
                                                            , {
                                                            theme: "facebook",
                                                                    noResultsText: "Nothing' found.",
                                                                    preventDuplicates: true,
                                                                    onAdd: function (item) {
                                                                    console.log(item + " add");
//return false;
// alert('hi');
                                                                    },
                                                                    onDelete: function (item) {
                                                                    var delete_id = item.id;
                                                                    var len = item.lenght;
                                                                    if (len == 0){
                                                                    $('#btn_search').html('Search');
                                                                    }
//document.getElementById(delete_id+"_close").setAttribute("style","display:none;");
//document.getElementById(delete_id+"_add").setAttribute("style","");
                                                                    },
                                                            });
                                                            });
                                                            function add_new_test(){

                                                            var selectedValues = $j('#vidyagames1').tokenInput("get");
//console.log(selectedValues);
//alert(selectedValues);
                                                            var ids = [];
                                                            var pid = [];
                                                            var names = [];
                                                            for (var key in selectedValues) {
                                                            var value12 = selectedValues[key];
                                                            console.log(value12);
//var value1 = Object.values(value12);
                                                            var value1 = $.map(value12, function(value, index) {
                                                            return [value];
                                                            });
//console.log(array);

                                                            var id = value1[0];
                                                            var name = value1[1];
                                                            var type = value1[2];
//alert(id);
                                                            console.log(id + " ids");
                                                            ids.push(id);
                                                            names.push(name);
                                                            var oldids = $('#selectedid').val();
                                                            var oldname = $('#selectedname').val();
                                                            var x = oldids.concat(',', id);
                                                            var y = oldname.concat('#', name);
                                                            $('#selectedid').val(x);
                                                            $('#test_package_id').val(x);
                                                            $('#selectedname').val(y);
                                                            }
                                                            var x1 = x.split(',');
                                                            var y1 = y.split('#');
                                                            console.log(y1);
                                                            console.log(x1);
                                                            var x11 = [];
                                                            $.each(x1, function(i, el){
                                                            if ($.inArray(el, x11) === - 1) x11.push(el);
                                                            });
                                                            var y11 = [];
                                                            $.each(y1, function(i, el){
                                                            if ($.inArray(el, y11) === - 1) y11.push(el);
                                                            });
                                                            var newArray1 = x11.filter(function(v){return v !== ''});
                                                            var newArray2 = y11.filter(function(v){return v !== ''});
                                                            $.ajax({
                                                            url:"<?php echo base_url(); ?>user_master/searching_test",
                                                                    type:'post',
                                                                    data:{id:newArray1, name:newArray2},
                                                                    success: function(data) {
//     console.log("data"+data);
                                                                    window.location = "<?php echo base_url(); ?>user_master/order_search";
                                                                    }
                                                            });
                                                            }



    </script>
    <script>
        function check_user_login(){
        var u_email = $("#login_email").val();
        var u_pass = $("#login_pass").val();
        $("#login_error").html("");
        $("#login_loader_div").attr("style", "");
        $.ajax({
        url:"<?php echo base_url(); ?>user_master/check_login",
                type:'post',
                data:{u_email:u_email, u_pass:u_pass},
                success: function(data) {
                var json_data = JSON.parse(data);
                if (json_data.status == '1'){
                window.location.reload();
                } else{
                $("#login_error").html(json_data.message);
                }
                $("#login_loader_div").attr("style", "display:none;");
                }
        });
//$("#login_loader_div").attr("style","display:none;");
        }
    </script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
    <script src="<?php echo base_url(); ?>user_assets/js/jquery-2.2.0.min.js"></script>        
    <script src="<?php echo base_url(); ?>user_assets/js/jquery-ui.min.js"></script>
    <script src="<?php echo base_url(); ?>user_assets/js/bootstrap.min.js"></script>
    <script> function base_url(){ return '<?= base_url(); ?>'; } book_test_id = '<?php echo $ids; ?>'; uid = '<?= $uid; ?>'; t_date = '<?= date("m/d/Y"); ?>'; b_city = '<?= $test_city_session ?>';</script>
    <script src="<?php echo base_url(); ?>js/custome/search_after.js"></script>
    <script src="<?= base_url(); ?>js/jquery.mask.min.js"></script>
    <?php /* Nishit changes start */ ?>


    <script>
        $('.datepicker').datepicker({
        format: 'yyyy-mm-dd'
        });
        $('#f_dob').mask("00/00/0000", {placeholder: "DD/MM/YYYY"});
    </script>
    <script>
        var placeSearch, autocomplete;
        function initAutocomplete() {
        // Create the autocomplete object, restricting the search to geographical
        // location types.
        autocomplete = new google.maps.places.Autocomplete(
                /** @type {!HTMLInputElement} */(document.getElementById('landmark')),
        {types: ['geocode']});
        // When the user selects an address from the dropdown, populate the address
        // fields in the form.
        // autocomplete.addListener('place_changed', fillInAddress);
        }
        function geolocate() {
        var autocomplete = new google.maps.places.Autocomplete($("#landmark")[0], {});
        google.maps.event.addListener(autocomplete, 'place_changed', function() {
        var place = autocomplete.getPlace();
        console.log(place.address_components);
        });
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBvz0gUCG3we0zmL0gaMartvVFzCGP7uU8&libraries=places"
    async defer></script>
    <?php /* Nishit changes end */ ?>