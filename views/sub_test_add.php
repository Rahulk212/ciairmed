<!-- Page Heading -->
<style>
    /* Example tokeninput style #2: Facebook style */

    #contener_1{padding:15px 0px; float:left; width:100%;}
    #family_div {display: inline-block; width:100%; float:left;}
    #family_div .input-group .select2.select2-container.select2-container--default {width: 370px !important; text-align:left; padding-left:10px;}
    /*.input-group.date.tym_slot_date_wdth_50{width:50%;}*/
    /* #contener_2 .input-group .select2.select2-container.select2-container--default {width: 370px !important; text-align:left; padding-left:10px;} */
    .select2.select2-container.select2-container--default{ border: 1px solid #ccc; border-radius: 4px; height: 33px; padding-left: 10px;}
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
    .btn.btn-default.set_book_btnpopup:focus {background:#0077a6; border-color:#0077a6; color:#fff;}


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
        z-index: 9;
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
        .modal-dialog.aftr_srch_fill_info_sm_popup{width:400px; margin:0 auto;}
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
    .chosen-container.chosen-container-single{width:90%;}
</style>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/tokeninput/styles/token-input.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>user_assets/tokeninput/styles/token-input-facebook.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>user_assets/tokeninput/styles/token-input-mac.css" rel="stylesheet" type="text/css" />
<section class="content-header">
    <h1>
        Add Test For <?=ucfirst($test_info[0]["test_name"]);?>
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>home"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>test-master/test-list">Test List</a></li>
        <li class="active">Add Sub-Test</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">

            <div class="box box-primary">
                <p class="help-block" style="color:red;"><?php
                    if (isset($error)) {
                        echo $error;
                    }
                    ?></p>
                <!-- form start -->
                <form role="form" action="<?php echo base_url(); ?>test_master/sub_test_add/<?= $tid ?>" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="col-md-6">
                            <?php if (isset($unsuccess) != NULL) { ?>
                                <div class="alert alert-danger alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $unsuccess['0']; ?>
                                </div>
                            <?php } ?>
                            <?php if (isset($success) != NULL) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $success['0']; ?>
                                </div>
                            <?php } ?>
                            <div class="form-group">
                                <?php /*
                                  <label for="exampleInputFile">Title</label><span style="color:red">*</span>
                                  <div id="searchbar" style="" class="srch_sftr_popup_inpt menuBtn">
                                  <input type="text" id="vidyagames1" />
                                  </div>
                                 */ ?>
                                <select class="form-control chosen-select" name="test" style="width: 90%;float:left;margin-right:10px;">
                                    <option value="">--Select--</option>
                                    <?php foreach ($test as $ts) { ?>
                                        <option value="<?= $ts["id"] ?>"><?= $ts["test_name"] ?></option>
                                    <?php } ?>
                                </select>
                                <span style="color: red;"><?= form_error('test'); ?></span>
                                <button class="btn btn-primary" type="submit" >ADD</button>
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width:70%;">Test</th>
                                        <th style="width:30%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($sub_test as $key){ ?>
                                    <tr>
                                        <td><?=ucfirst($key["test_name"]);?></td>
                                        <td><a href="<?=base_url();?>test_master/sub_test_delete/<?= $tid ?>/<?=$key["id"]?>" onclick="confirm('Are you sure?');">Delete</a></td>
                                    </tr> 
                                    <?php } ?>
                                    <?php if(empty($sub_test)){ ?>
                                    <tr><td colspan="2"><center>Data not available.</center></td></tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <!--<div class="form-group">
                                <label for="exampleInputFile">Actual Price</label><span style="color:red">*</span>
                                <input type="text"  name="aprice" class="form-control"  value="<?php echo set_value('aprice'); ?>" >

                            </div>-->
                            <!--Nishit city wise price start-->
                        </div>
                    </div><!-- /.box-body -->

                    <div class="box-footer">
                        <div class="col-md-6">

                        </div>
                    </div>

                </form>
            </div><!-- /.box -->
        </div>
    </div>
</section>
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/tokeninput/src/jquery.tokeninput.js"></script>
<script>
    var $j = jQuery.noConflict();
    $j(function () {
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
} echo json_encode($new_ary);
?>

        , {
            theme: "facebook",
            noResultsText: "Nothing found.",
            preventDuplicates: true});
        /*Nishit code start*/
        $j("#myButton").click(function () {
            //alert('hiii');
            $j(".token-input-list-facebook").remove();
            $j("#vidyagames").tokenInput("http://mysite.com?name=Adam");
        });
        /*Nishit code end*/

    });

    function add_new_test() {

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
            var value1 = $.map(value12, function (value, index) {
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
        $.each(x1, function (i, el) {
            if ($.inArray(el, x11) === -1)
                x11.push(el);
        });

        var newArray1 = x11.filter(function (v) {
            return v !== ''
        });

        alert(newArray1);
        return false;
        $.ajax({
            url: "<?php echo base_url(); ?>user_master/searching_test",
            type: 'post',
            data: {id: newArray1, name: newArray2},
            success: function (data) {
                //     console.log("data"+data);
                window.location = "<?php echo base_url(); ?>user_master/order_search";
            }
        });
    }
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
<script  type="text/javascript">

                                                                jQuery(".chosen-select").chosen({
                                                                    search_contains: true
                                                                });
                                                                //  $(".chosen-select-deselect").chosen({ allow_single_deselect: true });
                                                                // $("#cid").chosen('refresh');

</script>
