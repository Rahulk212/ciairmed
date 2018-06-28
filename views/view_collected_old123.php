<!-- Page Heading -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<!-- Add fancyBox main JS and CSS files -->
    <script src="//cdn.ckeditor.com/4.5.8/full/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>source/jquery.fancybox.pack.js?v=2.1.5"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>source/jquery.fancybox.css?v=2.1.5" media="screen" />
<!-- Add Media helper (this is optional) -->
<script type="text/javascript" src="<?php echo base_url(); ?>source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!--Nishit code start-->
<script type="text/javascript">
    var jq = $.noConflict();
    jq(document).ready(function () {
        jq('.fancybox').fancybox();
    });
</script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content">
        <div class="row">

            <div class="col-md-12">
                <div class="box box-primary">

                    <?php echo form_open_multipart("add_result/add_value_exists", array("id" => "add_result", "method" => "post", "onsubmit" => "return check_hundred()")); ?>

                    <div class="box-header">
                        <h3 class="box-title">Add Result</h3>
<!--                        <a class="btn btn-sm btn-primary" href="<?php echo base_url(); ?>add_result/pdf_report/<?php echo $cid; ?>?type=wlp" style="color:#fff;font-size:14px; float:right;margin-left: 5px;" target="_blank">Generate Pdf with letterhead</a>
                        <a class="btn btn-sm btn-primary" href="<?php echo base_url(); ?>add_result/pdf_report/<?php echo $cid; ?>" style="color:#fff;font-size:14px; float:right;" target="_blank">Generate Pdf</a>-->
                    </div>
                    <div class="col-sm-12">
                        <?php if ($success[0] != '') { ?>
                            <div class="widget">
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
                                    <?php echo $success['0']; ?>
                                </div>
                            </div>
                        <?php } ?>



                        <div class="col-sm-12" style="background:#EFF0F1;padding:14px 0px 0px 0px;">
                            <div class="form-group col-sm-2">
                                <label for="exampleInputFile">Reg NO :-</label>
                                <?= $query[0]['id']; ?>
                            </div>

                            <div class="form-group col-sm-3">
                                <label for="exampleInputFile">Customer Name :-</label>
                                <?= ucfirst($user_data[0]['full_name']); ?>  
                            </div>
                            <div class="form-group col-sm-2">
                                <label for="exampleInputFile">Doctor :-</label>
                                <?= $query[0]['dname']; ?>
                            </div>
                            <div class="form-group col-sm-2">
                                <label for="exampleInputFile">Gender :-</label>
                                <?= $user_data[0]['gender']; ?>(  <?php
                                if ($age[0] != 0) {
                                    echo $age[0] . " Y";
                                }
                                if ($age[0] == 0 && $age[1] != 0) {
                                    echo $age[1] . " M";
                                }
                                if ($age[0] == 0 && $age[1] == 0 && $age[2] != 0) {
                                    echo $age[2] . " D";
                                }
                                ?> )
                            </div>
                            <div class="form-group col-sm-3">
                                <label for="exampleInputFile">Note :-</label>
                                <?= $query[0]['note']; ?>
                            </div>

                        </div>
                    </div>
                    <div class="box-body">
                        <div class="alert alert-danger alert-dismissable" id="msg_cancel" style="display:none;">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            Results Not Add
                        </div>

                        <div class="alert alert-success alert-dismissable" id="msg_success" style="display:none;">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            Results Add Successfully
                        </div>
                        <div class="col-sm-12">
                            <?php
                            $tabindex = 100;
                            $ts = explode('#', $query[0]['testname']);
                            $tid = explode(",", $query[0]['testid']);
                            $last_array = array();
                            $cnt = 0;
                            $cnnt = 0;
                            foreach ($new_data_array as $testidp) {
                                //print_r($testidp); die();
                                // if ($parameter_list[$cnt][0]['test_fk'] == $testidp) {
                                //print_r($testidp); die();
                                if ($cnt != 0) {
                                    echo "</br></br>";
                                }
                                ?>
                                <h2 class="page-header">
                                    <i class="fa fa-list-alt"></i> Add Result For <?php echo ucfirst($testidp["test_name"]); ?>
                                    <?php if ($login_data['type'] == 1 || $login_data['type'] == 2 || $login_data['type'] == 5) { ?>
                                        <small class="pull-right"><input type="button" value="Add Parameter" data-toggle="modal" data-target="#myModal_view" onclick="add_reference('<?php echo $testidp["test_fk"]; ?>', '<?= $query[0]['branch_fk']; ?>', 'add');" class="btn btn-sm btn-primary"></small>
                                    <?php } ?>
                                </h2>
                                <input type="hidden" name="test_fk[]" value="<?= $testidp["test_fk"] ?>"/>
                                <input type="checkbox" id="is_formula_<?php echo $cnnt; ?>" name="use_formula_<?= $testidp["test_fk"] ?>" value="1" <?php
                                if ($testidp['use_formula'] == 1 || $testidp['use_formula'] == '') {
                                    echo "checked";
                                }
                                ?> tabindex="<?php echo $tabindex++; ?>" onchange="DelayedCallMe(200, '<?= $cnnt ?>', '<?php echo $testidp[0]["parameter"][0]['id']; ?>');"/> Use Formula?&nbsp;&nbsp;&nbsp;
                                <input type="checkbox" id="on_new_page_<?php echo $cnnt; ?>" name="on_new_page_<?= $testidp["test_fk"] ?>" value="1" tabindex="<?php echo $tabindex++; ?>" <?php
                                if ($testidp['on_new_page'] == 1 || $testidp['on_new_page'] == '') {
                                    echo "checked";
                                }
                                ?>/> On New Page?
                                <table id="example4" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th width="4%">Order</th>
                                            <th width="4%">Code</th>
                                            <th width="15%">Parameter Name</th>
                                            <?php if ($testidp["report_type"] == 2) { ?>
                                                <th>SENSITIVITY</th>
                                                <th width="12%">ZONE OF INHIBITION</th>
                                            <?php } else { ?>
                                                <th width="12%">Value</th>
                                            <?php } ?>
                                            <th width="20%">Parameter Range</th>
                                            <th width="10%">Parameter Unit</th>
                                            <th width="25%">Condition</th>
                                            <th width="10%">action</th>
                                    <input type="hidden" name="para_job_id" value="<?php echo $cid; ?>">
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $cn = 0;

                                        $para_array = array();
                                        foreach ($testidp[0]["parameter"] as $parameter) {
                                            $para_array[] = $parameter["id"];
                                            if (!empty($parameter['parameter_name'])) {
                                                echo '<input type="hidden" name="para[]" value="' . $parameter['test_parameter_id'] . '"/>';
                                                if (isset($parameter["para_ref_rng"][0]['id'])) {
                                                    /* Start cal */
                                                    if (count($parameter['user_val']) > 0) {
                                                        $status = "Normal";
                                                        if ($parameter["para_ref_rng"][0]['absurd_low'] > $parameter['user_val'][0]["value"]) {
                                                            $status = "Emergency";
                                                        }
                                                        if ($parameter["para_ref_rng"][0]['ref_range_low'] > $parameter['user_val'][0]["value"]) {
                                                            $status = $parameter["para_ref_rng"][0]['low_remarks'];
                                                        }
                                                        if ($parameter["para_ref_rng"][0]['critical_low'] > $parameter['user_val'][0]["value"]) {
                                                            $status = $parameter["para_ref_rng"][0]['critical_low_remarks'];
                                                        }
                                                        if ($parameter["para_ref_rng"][0]['ref_range_high'] < $parameter['user_val'][0]["value"]) {
                                                            $status = $parameter["para_ref_rng"][0]['high_remarks'];
                                                        }
                                                        if ($parameter["para_ref_rng"][0]['critical_high'] < $parameter['user_val'][0]["value"]) {
                                                            $status = $parameter["para_ref_rng"][0]['critical_high_remarks'];
                                                        }
                                                    } else {
                                                        $status = "Emergency";
                                                    }
                                                    if (!empty(trim($parameter["para_ref_rng"][0]["ref_range"]))) {
                                                        $status = $parameter["para_ref_rng"][0]["ref_range"];
                                                    }
                                                    /* End cal */
                                                    ?>

                                                    <tr>
                                                        <td><input type="text" class="number" value="<?php echo $parameter['new_order']; ?>" style="width:100%" name="order[]"/></td>
                                                        <?php if ($parameter["is_group"] != 1) { ?><td><b><?php echo $parameter['id']; ?></b></td><?php } ?>
                                                        <td <?php
                                                        if ($parameter["is_group"] == 1) {
                                                            echo 'colspan="6"';
                                                        }

                                                        //1000
                                                        ?>><?php echo $parameter['parameter_name']; ?></td>
                                                            <?php if ($parameter["is_group"] != 1) { ?>
                                                            <td>
                                                                <input type="hidden" name="test_id_<?php echo $cnt; ?>" value="<?php echo $testidp["test_fk"]; ?>">
                                                                <input type="hidden" name="parameter_id_<?php echo $cnt; ?>" value="<?php echo $parameter['id']; ?>">
                                                                <input type="text" class="number hundread<?php echo $parameter["is_hundred"]; ?>" tabindex="<?php echo $tabindex++; ?>" style="width:83%;float: left;" formula="<?php echo ( $parameter['formula'] != "") ? "true" : "false"; ?>" name="parameter_value_<?php echo $cnt; ?>" onkeyup="DelayedCallMe(200, '<?= $cnnt ?>', '<?php echo $parameter['id']; ?>');" id="<?= $cnnt ?>_<?php echo $parameter['id']; ?>" is_hundred="<?php echo $parameter["is_hundred"]; ?>" value="<?= $parameter['user_val'][0]["value"]; ?>">
                                                                <button type="button" onclick="open_modal('<?= $cnnt ?>_<?php echo $parameter['id']; ?>')" style="width:6px;height:24px;float:left;"></button>
                                                            </td>
                                                            <?php if ($testidp["report_type"] == 2) { ?>
                                                                <td> <input type="text" class="number" tabindex="<?php echo $tabindex++; ?>" style="width:83%;float: left;" name="parameter2_value_<?php echo $cnt; ?>" value="<?= $parameter['user_val'][0]["value2"]; ?>"></td>
                                                            <?php } ?>
                                                    <input type="hidden" name="p_low" id="p_start_<?= $cnnt ?>_<?php echo $parameter['id']; ?>" value="<?php echo $parameter["para_ref_rng"][0]['ref_range_low']; ?>"/>
                                                    <input type="hidden" name="p_high" id="p_end_<?= $cnnt ?>_<?php echo $parameter['id']; ?>" value="<?php echo $parameter["para_ref_rng"][0]['ref_range_high']; ?>"/>
                                                    <td><?php echo $parameter["para_ref_rng"][0]['ref_range_low']; ?>-<?php echo $parameter["para_ref_rng"][0]['ref_range_high']; ?></td>
                                                    <td><?php echo $parameter['parameter_unit']; ?></td>
                                                    <td>
                                                        <?php echo $status; ?>
                                                    </td>
                                                <?php } ?>
                                                <?php $last_array[] = array("cnt" => $cnnt, "pid" => $parameter['id']); ?>
                                                <td>
                                                    <?php /* <a href='javascript:void(0);' onclick="add_reference('<?php echo $parameter['pid']; ?>');" data-toggle="tooltip" data-original-title="Add Reference Range" > <span class="label label-primary"><i class="fa fa-plus"> </i></span> </a>
                                                      <a  href='#' onclick="update_parameter('<?php echo $parameter['pid']; ?>');" data-toggle="modal" data-target="#update_par" data-original-title="Edit"> <span class="label label-primary"><i class="fa fa-edit"> </i></span> </a> */ ?>
                                                    <?php if ($login_data['type'] == 1 && $login_data['id'] != 8) { ?>
                                                        <a href='javascript:void(0);' onclick="edit_parameter('<?php echo $testidp["test_fk"]; ?>', '<?php echo $parameter['id']; ?>', '<?= $query[0]['branch_fk']; ?>');" data-toggle="tooltip" data-original-title="Edit Parameter" > <span class="label label-primary"><i class="fa fa-edit"> </i></span> </a>
                                                        <a  onclick="return confirm('Are you sure you want to spam this parameter?');" href='<?php echo base_url(); ?>add_result/delete_parameter/<?php echo $parameter['id']; ?>/<?php echo $cid; ?>/<?php echo $testidp["test_fk"]; ?>' data-toggle="tooltip" data-original-title="Spam Parameter" > <span class="label label-danger"><i class="fa fa-trash"> </i></span> </a>
                                                    <?php } ?>
                                                </td>
                                                </tr>

                                            <?php } else { ?>
                                                <tr>
                                                    <td><input type="text" class="number" value="<?php echo $parameter['new_order']; ?>" style="width:100%" name="order[]"/></td>
                                                    <?php if ($parameter["is_group"] != 1) { ?><td><b><?php echo $parameter['id']; ?></b></td><?php } ?>
                                                    <td <?php
                                                    if ($parameter["is_group"] == 1) {
                                                        echo "colspan='6'";
                                                    }
                                                    ?>><?php echo $parameter['parameter_name']; ?></td>
                                                        <?php if ($parameter["is_group"] != 1) { ?>
                                                        <td><input type="hidden" name="test_id_<?php echo $cnt; ?>" value="<?php echo $testidp["test_fk"]; ?>">
                                                            <input type="hidden" name="parameter_id_<?php echo $cnt; ?>" value="<?php echo $parameter['id']; ?>">
                                                            <?php if (!empty($parameter["para_ref_status"])) { ?>
                                                                <select  tabindex="<?php echo $tabindex++; ?>"   class="form-control" name="parameter_value_<?php echo $cnt; ?>" <?php
                                                                if (!empty($parameter['user_val'][0]["value"])) {
                                                                    echo "";
                                                                }
                                                                ?>><option value="">--Select--</option>
                                                                             <?php foreach ($parameter["para_ref_status"] as $kky) { ?>
                                                                        <option value="<?= $kky["id"] ?>" <?php
                                                                        if ($parameter['user_val'][0]["value"] == $kky["id"]) {
                                                                            echo "selected";
                                                                        }
                                                                        ?>><?= $kky["parameter_name"]; ?></option>
                                                                            <?php } ?>
                                                                </select>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <input type="hidden" name="test_id_<?php echo $cnt; ?>" value="<?php echo $testidp["test_fk"]; ?>">
                                                                <input type="hidden" name="parameter_id_<?php echo $cnt; ?>" value="<?php echo $parameter['id']; ?>">
                                                                <input type="text" class="number"  tabindex="<?php echo $tabindex++; ?>" style="width: 83%;float: left;" formula="<?php echo ( $parameter['formula'] != "") ? "true" : "false"; ?>"  name="parameter_value_<?php echo $cnt; ?>" onkeyup="DelayedCallMe(200, '<?= $cnnt ?>', '<?php echo $parameter['id']; ?>');" id="<?= $cnnt ?>_<?php echo $parameter['id']; ?>" value="<?= $parameter['user_val'][0]["value"]; ?>" is_hundred="<?php echo $parameter["is_hundred"]; ?>">
                                                                <button type="button" onclick="open_modal('<?= $cnnt ?>_<?php echo $parameter['id']; ?>')" style="width:6px;height:24px;float:left;"></button>
                                                                <?php
                                                            }
                                                            ?>
                                                        </td>
                                                        <?php if ($testidp["report_type"] == 2) { ?>
                                                            <td> <input type="text" class="number" tabindex="<?php echo $tabindex++; ?>" style="width:83%;float: left;" name="parameter2_value_<?php echo $cnt; ?>" value="<?= $parameter['user_val'][0]["value2"]; ?>"></td>
                                                        <?php } ?>
                                                        <td><?php echo $parameter['parameter_range']; ?></td>
                                                        <td><?php echo $parameter['parameter_unit']; ?></td>
                                                        <td>
                                                            <?php
                                                            if (empty($parameter["para_ref_rng"][0]["ref_range"])) {
                                                                foreach ($parameter["para_ref_status"] as $kky) {
                                                                    ?>
                                                                    <?php
                                                                    if ($parameter['user_val'][0]["value"] == $kky["id"]) {
                                                                        echo $kky["parameter_name"];
                                                                    }
                                                                }
                                                            } else {
                                                                echo $parameter["para_ref_rng"][0]["ref_range"];
                                                            }
                                                            ?>
                                                        </td>
                                                    <?php } ?>
                                                    <td>
                                                        <?php /*                        <a  href='#' onclick="update_parameter('<?php echo $parameter['pid']; ?>');" data-toggle="modal" data-target="#update_par"> <span class="label label-primary"><i class="fa fa-edit"> </i></span> </a> */ ?>
                                                        <?php if ($login_data['type'] == 1 && $login_data['id'] != 8) { ?>
                                                            <a href='javascript:void(0);' onclick="edit_parameter('<?php echo $testidp["test_fk"]; ?>', '<?php echo $parameter['id']; ?>', '<?= $query[0]['branch_fk']; ?>');" data-toggle="tooltip" data-original-title="Edit Parameter" > <span class="label label-primary"><i class="fa fa-edit"> </i></span> </a>
                                                            <a  onclick="return confirm('Are you sure you want to spam this parameter?');" href='<?php echo base_url(); ?>add_result/delete_parameter/<?php echo $parameter['id']; ?>/<?php echo $cid; ?>/<?php echo $testidp["test_fk"]; ?>' data-toggle="tooltip" data-original-title="Spam Parameter" > <span class="label label-danger"><i class="fa fa-trash"> </i></span> </a>
                                                        <?php } ?>
                                                    </td>
                                                <tr>

                                                    <?php
                                                }
                                                $para_id_vals = json_encode($para_array);
                                            } /* else { ?>
                                              <tr>
                                              <td colspan="6"><center>Data not available.</center></td>
                                              </tr>
                                              <?php } */ $cnt++;
                                        }
                                        ?>
                                    <div style="display:none;"  id='parameter_ids_<?= $cnnt ?>'><?= $para_id_vals ?></div>
                                    <?php ?>
                                    </tbody>
                                </table>
                                
                                    <?php
                                    if (!empty($testidp['graph'])) { ?>
                                <div class="form-group form-group col-sm-12">
                                <?php        foreach ($testidp['graph'] as $g_key) {
                                            ?>
                                            <div id="graph_dv_<?= $g_key['id'] ?>" style="float:left;">
                                                <img src="<?= base_url(); ?>upload/report/graph/<?= $g_key['pic'] ?>" style="height: 100px;width:auto;border:2px solid #1c1c1c" class="margin">
                                                <a onclick="delete_graph('<?= $g_key['id'] ?>');" href="javascript:void(0);" data-toggle="tooltip" data-original-title="Delete"><i class="fa fa-trash"></i></a>
                                            </div>
                                            <?php
                                        } ?>
                                    </div>
                                   <?php }
                                    ?>
                                
                                <?php if ($testidp["report_type"] == 2) { ?>
                                <div class="form-group col-sm-12">
                                    <select class="form-control" name="design[]" id="design">
                                        <option value="1">One design</option>
                                        <option value="1">two design</option>
                                        <option value="1">three design</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-12">
                                    <textarea class="ckeditor" name="desc_test" id="description"></textarea>
                                </div>
                                <?php } ?>
                                <div class="form-group col-sm-12">
                                    <label for="exampleInputFile">Upload Graph</label>
                                    <input type="file" name="graph_<?php echo $testidp["test_fk"]; ?>[]" multiple=""/>
                                    <p class="help-block"><small>(Only .gif,.jpg,.png are allowed)</small></p>
                                    <input type="hidden" id="old_graph_<?= $testidp['graph_id'] ?>" name="current_graph_<?php echo $testidp["test_fk"]; ?>" value="<?= $testidp['graph'] ?>"/>
                                </div>
                                <?php
                                $cnt++;
                                $cnnt++;
                            }
                            ?>
                            <input type="hidden" name="count" value="<?php echo $cnt; ?>">
                        </div>
                    </div>
                    <div class="box-footer">
                        <input onclick="change_order();" style="float:right;" class="btn btn-primary pull-left" value="Change Order" type="button">
                        <input style="float:right;" class="btn btn-primary" value="Save" type="submit" >
                        <a class="btn btn-primary btn-sm" href="<?php echo base_url(); ?>add_result/preview_report/<?php echo $cid; ?>?type=wlp" style="color:#fff;font-size:14px; float:right;margin: 0px 5px 0px 0px;" target="_blank"><i class="fa fa-search"></i> Preview</a>
                    </div>
                    <?= form_close(); ?>
                </div>
            </div>
        </div>
    </section>
</div>
<a data-toggle="modal" href="#myModal" style="display: none;" id="open_modal1">Open Modal</a>
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Result</h4>
            </div>
            <div class="modal-body">
                <textarea class="form-control" id="big_text" rows="10"></textarea>
            </div>
            <input type="hidden" id="para_id" value=""/>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="save_large_data();" data-dismiss="modal">Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<script  type="text/javascript">
    CKEDITOR.replace('#description');
    $(function () {
        $('.chosen').chosen();
    });

</script> 
<script>

    function check_hundred() {

        var $j_object = $(".hundread1");
        $sum = 0;
        $j_object.each(function (i) {
            if ($j_object[i].value != '') {
                $sum += parseFloat($j_object[i].value);
            }
        });
        if ($sum == 0 || $sum == 100) {

            return true;
        } else {
            alert(" ERROR : CBC parameter value error. Please enter parameter correctly.");
            return false;
        }
    }

    function open_modal(val) {
        $('#para_id').val(val);
        var old_val = $("#" + val).val();
        var old_txt = old_val.split("<br />");
        var txt = '';
        for (var i = 0; i < old_txt.length; i++) {
            txt += old_txt[i] + "\n";
        }
        document.getElementById("big_text").value = txt;
        //$('#big_text').val(old_val);
        document.getElementById("open_modal1").click();

        //$("#myModal").modal('show');
    }
    function save_large_data() {
        var $val_id = $("#para_id").val();
        var xtbx_val = $("#big_text").val();
        xtbx_val = xtbx_val.trim();
        xtbx_val = xtbx_val.replace(/\r?\n/g, '<br />');
        $("#" + $val_id).val(xtbx_val);
        $("#big_text").val("");
    }
</script>
<script type="text/javascript">
    setTimeout(function () {
<?php echo $closeFancyBox[0]; ?>
    }, 1000);

    /*        
     $('.number').keypress(function (event) {
     var $this = $(this);
     if ((event.which != 46 || $this.val().indexOf('.') != -1) &&
     ((event.which < 48 || event.which > 57) &&
     (event.which != 0 && event.which != 8))) {
     event.preventDefault();
     }
     
     var text = $(this).val();
     if ((event.which == 46) && (text.indexOf('.') == -1)) {
     setTimeout(function () {
     if ($this.val().substring($this.val().indexOf('.')).length > 3) {
     $this.val($this.val().substring(0, $this.val().indexOf('.') + 3));
     
     }
     }, 1);
     }
     
     if ((text.indexOf('.') != -1) &&
     (text.substring(text.indexOf('.')).length > 2) &&
     (event.which != 0 && event.which != 8) &&
     ($(this)[0].selectionStart >= text.length - 2)) {
     event.preventDefault();
     }
     });
     
     $('.number').bind("paste", function (e) {
     var text = e.originalEvent.clipboardData.getData('Text');
     if ($.isNumeric(text)) {
     if ((text.substring(text.indexOf('.')).length > 3) && (text.indexOf('.') > -1)) {
     e.preventDefault();
     $(this).val(text.substring(0, text.indexOf('.') + 3));
     }
     } else {
     e.preventDefault();
     }
     });
     */
    function delete_graph(pid) {
        var cnfrm = confirm("Are you sure?");
        if (pid != '' && cnfrm == true) {
            var url = "<?= base_url(); ?>Add_result/delete_graph/" + pid + "/<?= $cid ?>";
            $.ajax({
                url: url,
                success: function (data)
                {
                    $('#graph_dv_' + pid).html("");
                    $("#old_graph_" + pid).val("");
                    alert("Graph successfully deleted.");
                }
            });
        }
    }
    function change_order() {
        $("#add_result").attr("action", "<?php echo base_url(); ?>add_result/change_order");
        setTimeout(function () {
            $("#add_result").submit();
        }, 500);
    }
    var cnt = 1;
    function add_field() {
        cnt++;
        var coun = "'" + cnt + "'";
        $('#table_body').append('<tr id="tr_' + cnt + '"><td><input type="text" class="number" name="par_name_' + cnt + '" id="par_name_' + cnt + '" class="form-control"><span id="par_name_error_' + cnt + '" style="color:red;"></span></td><td><input type="text" class="number" name="par_value_' + cnt + '" id="par_value_' + cnt + '" class="form-control"><span id="par_value_error_' + cnt + '" style="color:red;"></span></td><td><input type="text" class="number" name="par_min_' + cnt + '" id="par_min_' + cnt + '" class="form-control"><span id="par_min_error_' + cnt + '" style="color:red;"></span></td><td><input type="text" class="number" name="par_max_' + cnt + '" id="par_max_' + cnt + '" class="form-control"><span id="par_max_error_' + cnt + '" style="color:red;"></span></td><td><select class="form-control" name="par_unit_' + cnt + '" id="par_unit_' + cnt + '"><option value="">--Select--</option><?php foreach ($unit_list as $unit) { ?><option value="<?php echo $unit['PARAMETER_NAME']; ?>"><?php echo $unit['PARAMETER_NAME']; ?></option><?php } ?></select><span id="par_unit_error_' + cnt + '" style="color:red;"></span></td><td><select class="form-control" name="par_condi_' + cnt + '" id="par_condi_' + cnt + '"><option value="">--Select--</option><option value="Normal">Normal</option><option value="Emergency">Emergency</option></select><span id="par_condi_error_' + cnt + '" style="color:red;"></span></td><td><a href="javascript:void(0)" onclick="row_remove(' + coun + ');"><i style="color:red;" class="fa fa-minus-square"></i></a></td></tr>');
        $('#count_par').val(cnt);
    }
    function row_remove(val) {
        $('#tr_' + val).remove();
        cnt--;
        $('#count_par').val(cnt);
    }
    function parameter_validation() {
        var i;
        var temp = 1;
        for (i = 1; i <= cnt; i++) {
            var name = $('#par_name_' + i).val();
            var value = $('#par_value_' + i).val();
            var min = $('#par_min_' + i).val();
            var max = $('#par_max_' + i).val();
            var unit = $('#par_unit_' + i).val();
            var con = $('#par_condi_' + i).val();
            if (name == '') {
                temp = 1;
                $('#par_name_error_' + i).html('Required.');
            } else {
                temp = 0;
                $('#par_name_error_' + i).html('');
            }
            if (value == '') {
                temp = 1;
                $('#par_value_error_' + i).html('Required.');
            } else {
                temp = 0;
                $('#par_value_error_' + i).html('');
            }
            if (min == '') {
                temp = 1;
                $('#par_min_error_' + i).html('Required.');
            } else {
                temp = 0;
                $('#par_min_error_' + i).html('');
            }
            if (max == '') {
                temp = 1;
                $('#par_max_error_' + i).html('Required.');
            } else {
                temp = 0;
                $('#par_max_error_' + i).html('');
            }
            if (unit == '') {
                temp = 1;
                $('#par_unit_error_' + i).html('Required.');
            } else {
                temp = 0;
                $('#par_unit_error_' + i).html('');
            }
            if (con == '') {
                temp = 1;
                $('#par_condi_error_' + i).html('Required.');
            } else {
                temp = 0;
                $('#par_condi_error_' + i).html('');
            }
        }
        if (temp == 0) {
            $('#add_data').submit();
        }
    }
    $(function () {
        $("#example1").dataTable();
        $('#example2').dataTable({
            "bPaginate": true,
            "bLengthChange": false,
            "bFilter": true,
            "bSort": false,
            "bInfo": false,
            "bAutoWidth": false,
            "iDisplayLength": 10
        });
    });
    function add_results() {

        var frm_data = document.getElementById("add_result");
        $.ajax({
            url: frm_data.action,
            type: 'post',
            data: $("#add_result").serialize(),
            success: function (data) {
                alert("Result successfully saved.");
                setTimeout(function () {
                    //window.location.reload();
                }, 1000);
                //parent.close_popup();

            },
            error: function (jqXhr) {

            },
            complete: function () {
            },
        });

    }
    function add_parameter(val) {
        $('#test_id').val(val);
        var url = "<?php echo base_url(); ?>add_result/testdescription";
        $.ajax({
            type: "POST",
            url: url,
            data: {"test_id": val}, // serializes the form's elements.
            success: function (data)
            {
                CKEDITOR.instances.description.setData(data);
            }
        });
    }
    function update_parameter(pid) {
        if (pid != '') {
            var url = "<?php echo base_url(); ?>add_result/edit_parameter";
            $.ajax({
                type: "POST",
                url: url,
                data: {"para_id": pid}, // serializes the form's elements.
                success: function (data)
                {
                    $('#update_body').html(data);
                }
            });
        }
    }
    function parameter_update() {
        var temp = 0;
        var name = $('#update_par_name').val();
        var min = $('#update_par_min').val();
        var max = $('#update_par_max').val();
        var unit = $('#update_par_unit').val();
        $('#update_par_name_error').html('');
        $('#update_par_min_error').html('');
        $('#update_par_max_error').html('');
        $('#update_par_unit_error').html('');
        if (name == '') {
            temp = 1;
            $('#update_par_name_error').html('Required.');
        }
        if (min == '') {
            temp = 1;
            $('#update_par_min_error').html('Required.');
        }
        if (max == '') {
            temp = 1;
            $('#update_par_max_error').html('Required.');
        }
        if (unit == '') {
            temp = 1;
            $('#update_par_unit_error').html('Required.');
        }
        if (temp == 0) {
            $('#update_data').submit();
        }
    }

    function add_reference(val, id, val1) {
        jq.fancybox.open({
            href: '<?= base_url(); ?>add_result/add_parameter/' + val + '/' + val1 + '/' + id,
            type: 'iframe',
            padding: 5,
            width: '100%',
        });
    }
    function edit_parameter(val, val1, brnch) {
        jq.fancybox.open({
            href: '<?= base_url(); ?>add_result/add_parameter/' + val + '/' + val1 + '/' + brnch,
            type: 'iframe',
            padding: 5,
            width: '100%',
            onComplete: function () {
                // Register your CKeditor here
                CKEDITOR.replace('#description');
            }
        });
        $("#exist_para").attr("style", "display:none;");
    }
    function close_box() {
        jq.fancybox.close();
        setTimeout(function () {
            window.location.reload();
        }, 1000);
    }

    /*Nishit code start*/
    var _timer = 0;

    /*    function DelayedCallMe(num, ids) {
     if (_timer)
     window.clearTimeout(_timer);
     _timer = window.setTimeout(function () {
     calculate_result(ids);
     }, 500);
     }*/
var  formula_ajax;
    function DelayedCallMe(num, ids, paraid) {
    if( formula_ajax!=null){ formula_ajax.abort(); }
        if (_timer)
            window.clearTimeout(_timer);
        _timer = window.setTimeout(function () {
            if (document.getElementById("is_formula_" + ids).checked == true) {
                calculate_result_status(ids, paraid);
                calculate_result(ids);
            }
        }, 2000);
    }
    function calculate_result_status(ids, paraid) {
        var p_start = $("#p_start_" + ids + "_" + paraid).val();
        var p_end = $("#p_end_" + ids + "_" + paraid).val();
        var p_val = $("#" + ids + "_" + paraid).val();
        p_start = parseFloat(p_start);
        p_end = parseFloat(p_end);
        p_val = parseFloat(p_val);
        setTimeout(function () {
            if (p_val != 0 && p_val != '' && p_start != '' && p_end != '') {
                if (p_val >= p_start && p_val <= p_end) {
                    $("#" + ids + "_" + paraid).attr("style", "");
                } else if (p_val < p_start) {
                    $("#" + ids + "_" + paraid).attr("style", "background-color:yellow;");
                } else if (p_val > p_end) {
                    $("#" + ids + "_" + paraid).attr("style", "background-color:red;color:white;");
                }

            } else {
                $("#" + ids + "_" + paraid).attr("style", "");
            }
        }, 1000);
    }
    function calculate_result(ids) {
        var get_parameter_ids = $("#parameter_ids_" + ids).html();
        var id_ary = [];
        var jsondata = JSON.parse(get_parameter_ids);

        for (var j = 0; j < jsondata.length; j++) {
            var parameter_val = $("#" + ids + "_" + jsondata[j]).val();
            console.log($("#" + ids + "_" + jsondata[j]).attr("formula"));

            if ($("#" + ids + "_" + jsondata[j]).attr("formula") == "true") {
                parameter_val = "";
            }

            id_ary.push(jsondata[j] + "%&%" + parameter_val);
        }
        var url = "<?php echo base_url(); ?>add_result/formula_calculation";
        if (jsondata.length > 1) {
            formula_ajax=$.ajax({
                type: "POST",
                url: url,
                data: {"para_id": id_ary, result_num: ids}, // serializes the form's elements.
                success: function (data)
                {
                    var json_data = JSON.parse(data);
                    if (json_data.status == 1) {
                        for (var i = 0; i < json_data.data.length; i++) {
                            $("#" + json_data.data[i].val + "_" + json_data.data[i].pid).val(json_data.data[i].res);
                            calculate_result_status(json_data.data[i].val, json_data.data[i].pid);
                        }
                    }
                    if (json_data.status == 0) {
                        for (var i = 0; i < json_data.data.length; i++) {
                            $("#" + json_data.num + "_" + json_data.data[i].id).val(json_data.data[i].val);
                            calculate_result_status(json_data.num, json_data.data[i].id);
                        }

                    }
                }
            });
        }
    }
<?php foreach ($last_array as $key) { ?>
        calculate_result_status('<?= $key["cnt"] ?>', '<?= $key["pid"] ?>');
<?php } ?>
    /*Nishit code end*/
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
        checkboxClass: 'icheckbox_minimal-red',
        radioClass: 'iradio_minimal-red'
    });
</script>