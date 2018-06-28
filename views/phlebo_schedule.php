<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="http://cdn.rawgit.com/davidstutz/bootstrap-multiselect/master/dist/css/bootstrap-multiselect.css"
      rel="stylesheet" type="text/css" />
<script src="http://cdn.rawgit.com/davidstutz/bootstrap-multiselect/master/dist/js/bootstrap-multiselect.js"
type="text/javascript"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />

<style>
    #city_wise_test .chosen-container {width: 100% !important;}
    .chosen-container .chosen-results li.active-result {width: 100% !important;}
    #test_chosen.chosen-container{width:400px;}
    #all_packages.col-md-2 :nth-child(even){
        background-color: #dcdcdc;
    }
    #all_packages.col-md-2 :nth-child(odd){
        background-color: #aaaaaa;
    }
</style>

<!-- Page Heading -->
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>

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
<!--Nishit code start-->
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
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- Custom Tabs -->
            <div class="nav-tabs-custom">
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1">
                        <section class="content-header">
                            <h1>
                                Manage Phlebotomy Schedule
                                <small></small>
                            </h1>
                        </section>
                        <section class="content">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box box-primary">

                                        <?php echo form_open("Phlebo_schedule/index", array("role" => "form", "method" => "POST")); ?>
                                        <div class="box-body">
                                            <div class="widget">
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

                                            </div>

                                            <div class="form-group col-sm-6  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Phlebotomy :<span style="color:red;">*</span></label>
                                                <div class="col-sm-7 pdng_0">
                                                    <select class="form-control" name="phlebo" onchange="search_phlebo_schedule(this.value);">
                                                        <option value="">--Select--</option>
                                                        <?php foreach ($phlebo as $key) { ?>
                                                            <option value="<?= $key["id"] ?>" <?php
                                                            if ($selected_phlebo == $key["id"]) {
                                                                echo "selected";
                                                            }
                                                            ?>><?= $key["name"] ?></option>
                                                                <?php } ?>
                                                    </select>
                                                </div>

                                            </div>
                                            <script>
                                                function search_phlebo_schedule(val) {
                                                    window.location = '<?= base_url(); ?>Phlebo_schedule/index?phlebo=' + val;
                                                }
                                            </script>
                                            <?php if($selected_phlebo!=''): ?>
                                            <div class="col-md-12" id="all_packages">
                                                <?php foreach ($time_slot as $key) { ?>
                                                    <div class="col-md-2">
                                                        <div class="checkbox">
                                                            <label><input type="checkbox" name="cal[]" class="checkbox2" <?php
                                                                if (!empty(phlebo)) {
                                                                    foreach ($phlebo_schedule as $ps_key) {
                                                                        if ($ps_key["time_slot_fk"] == $key["id"]) {
                                                                            echo " checked";
                                                                        }
                                                                    }
                                                                }
                                                                ?> value="<?= $key["id"] ?>"/> <?= $key["start_time"] . " To " . $key["end_time"] ?> </label>
                                                        </div>
                                                    </div> 
                                                <?php } ?>
                                                <div class="col-md-12">
                                                    <div class="checkbox">
                                                        <label><input type="checkbox"  id="select_all" class="checkbox1"/> Check all</label>
                                                    </div>
                                                </div>
                                            </div><!-- /.box -->
                                            <div class="form-group col-sm-6  pdng_0">
                                                <br><b>OR</b></br><br>
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Center :<span style="color:red;">*</span></label>
                                                <div class="col-sm-7 pdng_0">
                                                    <select class="form-control" name="center">
                                                        <option value="">--Select--</option>
                                                        <?php foreach ($branch as $key) { ?>
                                                            <option value="<?= $key["id"] ?>" <?php if($phlebo_assign_branch[0]["branch_fk"]==$key["id"]){ echo "selected"; } ?>><?= $key["branch_name"] ?></option>
                                                                <?php } ?>
                                                    </select>
                                                </div>

                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <?php if($selected_phlebo!=''): ?>
                                        <div class="box-footer">
                                            <input type="submit" class="btn btn-primary" value="Update"/> 
                                        </div>
                                        <?php endif; ?>
                                        <?php echo form_close(); ?>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!--Model end-->
                    </div><!-- /.tab-pane -->
                </div><!-- /.tab-content -->
            </div><!-- nav-tabs-custom -->
        </div><!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<script>
    $("#select_all").change(function () {  //"select all" change
        $(".checkbox2").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
    });

//".checkbox" change
    $('.checkbox2').change(function () {
        //uncheck "select all", if one of the listed checkbox item is unchecked
        if (false == $(this).prop("checked")) { //if this item is unchecked
            $("#select_all").prop('checked', false); //change "select all" checked status to false
        }
        //check "select all" if all checkbox items are checked
        if ($('.checkbox2:checked').length == $('.checkbox2').length) {
            $("#select_all").prop('checked', true);
        }
    });
</script>
<!--Nishit code end-->