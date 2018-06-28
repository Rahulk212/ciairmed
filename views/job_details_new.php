<style>
    .admin_job_dtl_img {border: 4px solid #8d8d8d; height: 160px; max-width: 160px; min-width: 80px; width: 180px;}
</style>
<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<link href="<?php echo base_url(); ?>plugins/timepick/css/timepicki.css" rel="stylesheet">
<section class="content-header">
    <h1>
        Job Details (<?php echo $query[0]['order_id']; ?>)
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <span style="margin-right:30px; font-size:20px;"><?php echo $query[0]['date'] . "  "; ?>  </span>
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>job-master/pending-list"><i class="fa fa-users"></i>Job List</a></li>
        <li class="active">Job Details</li>
    </ol>
</section>
<!-- Add fancyBox main JS and CSS files -->
<script type="text/javascript" src="<?php echo base_url(); ?>source/jquery.fancybox.pack.js?v=2.1.5"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>source/jquery.fancybox.css?v=2.1.5" media="screen" />
<!-- Add Media helper (this is optional) -->
<script type="text/javascript" src="<?php echo base_url(); ?>source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>
<!--Nishit code start-->
<script type="text/javascript">
    var jq = $.noConflict();
    jq(document).ready(function () {
        jq('.fancybox').fancybox();
    });
</script>
<style>
    .chosen-container {
        display: inline-block;
        font-size: 14px;
        position: relative;
        vertical-align: middle;
        width: 100%;
    }
    .full_bg{background: rgba(0,0,0,0.3); width:100%; height:100%; float:left; padding:350px; position:fixed; z-index:9; top:0; bottom:0;}
    .full_bg .loader img{width:70px; height:70px;}
</style>
<div class="full_bg" style="display:none;" id="loader_div">
    <div class="loader">
        <center><img src="http://static.heart.org/riskcalc/app/assets/img/loading.gif"></center>
    </div>
</div>
<section class="content">
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header">
                    <!-- form start -->
                    <h3 class="box-title">Job Details</h3>
                    <!--Add job start-->

                    <div class="btn-group manage_group_btns">
                        <?php if ($query[0]["model_type"] == 1) {
                            ?>
                            <button class="btn btn-default btn-sm" onclick="test_outsource('<?= $cid ?>');" data-toggle="tooltip" title="Outsource Test">Outsource Test</button>

                            <button class="btn btn-default btn-sm" onclick="test_manage('<?= $cid ?>');" data-toggle="tooltip" title="Manage Test"><i class="fa fa-tag"></i>&nbsp;Manage Test</button>
                            <?php
                        }
                        ?>
                        <?php if ($query[0]['status'] == 8 || $query[0]['status'] == 2) { ?>
                            <?php if ($login_data['type'] != 7) { ?>    
                                <button class="btn btn-default btn-sm" data-toggle="tooltip" title="Manage Result" onclick="add_Result('<?= $cid ?>');"><i class="fa fa-list-alt"></i>&nbsp;Manage Result</button>
                            <?php } ?>
                            <?php if ($login_data['type'] != 6 && $login_data['type'] != 7) { ?>
                                <a href="javascript:void(0)" onclick="approved();" class="btn btn-default btn-sm" data-toggle="tooltip" title="Generate Report"><i class="fa  fa-check"></i>&nbsp;Generate Report</a>
								
								<div id="myModalreport" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Generate Report</h4>
            </div>
 <?php echo form_open("add_result/approve_reporttest/".$cid, array("method" => "POST", "role" => "form")); ?>
            <div id="reportcontain" class="modal-body">

            </div>
			<div id="info"></div>
            <div class="modal-footer">
			 <input type="submit"  class="btn btn-primary" id="genbutton" value="Generate Report">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
 <?php echo form_close(); ?>
        </div>

    </div>
</div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                    <hr>
                    <!--Add job end-->
                </div>
                <Script>
                    function approved() {
                        if ($approved_test_cnt > 0) {
                            /* var cnf = confirm('Are you sure?');
                            return cnf; */
							$('#reportcontain').html('<div style="height:50px"><span id="searching_spinner_center" style="position: absolute;left: 50%;"><img src="<?= base_url()."img/ajax-loader.gif" ?>" /></span></div>');
						$('#myModalreport').modal('show');	
						//$("#genbutton").a
						$.ajax({url: "<?php echo base_url()."job_master/getjobs_bookedpack"; ?>",
            type: "GET",
            data: {jobs: "<?= $cid ?>"},
            error: function (jqXHR, error, code) {
            },
            success: function (data) {
				if(data != "0"){$('#reportcontain').html(data);  
				
				$(".up,.down").click(function(){
        var row = $(this).parents("tr:first");
        if ($(this).is(".up")) {
            row.insertBefore(row.prev());
        } else {
            row.insertAfter(row.next());
        }
    });
				
	$(".newadd").click(function(){
        var checkid=this.id;
		var splitchack=checkid.split("_")
		var chtestid=splitchack[1];
if ($('#'+checkid).is(':checked')) {
		$("#checkvalue_"+chtestid).val("1");
}else{ $("#checkvalue_"+chtestid).val(""); }	
    });			
				}
            }
        });
						return false;
                        } else {
                            alert("Please add result and approve test.");
                            return false;
                        }
                    }
                </script>
                <div class="box-body">
                    <div class="col-sm-12 res_pdng_0">
                        <label>Booked Test/Package</label><br>
                        <?php
                        $ts = explode('#', $query[0]['testname']);
                        $tid = explode(",", $query[0]['testid']);
                        $cnt = 1;
                        $completed_cnt = 0;
                        $test_list = array();
                        if ($query[0]['testname'] != '') {
                            foreach ($query[0]["job_test_list"] as $key) {
                                ?>
                                <?php
                                if (!in_array($key["test_fk"], $test_list)) {
                                    echo "(" . $cnt . ") <span id='test_" . $cid . "_" . $key["test_fk"] . "'>" . $key["test_name"] . "</span>";
                                    ?>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" id='btn_<?= $cid ?>_<?= $key["test_fk"] ?>' style="display:none;" data-toggle="tooltip" title="Approve Test" onclick="approveTest('<?= $cid ?>', '<?= $key["test_fk"] ?>');"><i class="fa fa-check"></i></a> <a href="<?= base_url(); ?>job_master/delete_approved/<?= $cid ?>/<?= $key["test_fk"] ?>" id='dbtn_<?= $cid ?>_<?= $key["test_fk"] ?>' style="display:none;" data-toggle="tooltip" onclick="return confirm('Are you sure?');" title="Disapprove Test"><b>X</b></a> <br>	
                                    <?php
                                    foreach ($key["sub_test"] as $kt_key) {
                                        if (!in_array($kt_key["sub_test"], $test_list)) {
                                            ?>
                                            <i style="margin-left:20px">-</i><span id='test_<?= $cid; ?>_<?= $kt_key["sub_test"] ?>'><?= $kt_key["test_name"] ?></span>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" id='btn_<?= $cid ?>_<?= $kt_key["sub_test"] ?>' style="display:none;" data-toggle="tooltip" title="Approve Test" onclick="approveTest('<?= $cid ?>', '<?= $kt_key["sub_test"] ?>');"><i class="fa fa-check"></i></a><a href="<?= base_url(); ?>job_master/delete_approved/<?= $cid ?>/<?= $kt_key["sub_test"] ?>" onclick="return confirm('Are you sure?');" id='dbtn_<?= $cid ?>_<?= $kt_key["sub_test"] ?>' style="display:none;" data-toggle="tooltip" title="Disapprove Test"><b>X</b></a><br>
                                            <?php
                                            $test_list[] = $kt_key["sub_test"];
                                        }
                                    }
                                    $test_list[] = $key["test_fk"];
                                    //print_r($test_list);
                                }
                                ?>
                                <?php
                                $cnt++;
                            }
                        }
                        $pk = explode('@', $query[0]['packagename']);
                        foreach ($selected_package as $key) {
                            //   print_r($key); die();
                            ?>
                                <?php echo "(" . $cnt . ") " . ucfirst($key["title"]); ?><br><?php
                                foreach ($key["test_list"] as $kt_key) {
                                    $kt_key["test_name"];
                                    if (!in_array($kt_key["test_fk"], $test_list)) {
                                        ?>
                                    <i style="margin-left:20px">-</i><span id='test_<?= $cid; ?>_<?= $kt_key["test_fk"] ?>'><?= $kt_key["test_name"] ?></span>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" id='btn_<?= $cid ?>_<?= $kt_key["test_fk"] ?>' style="display:none;" data-toggle="tooltip" title="Approve Test" onclick="approveTest('<?= $cid ?>', '<?= $kt_key["test_fk"] ?>');"><i class="fa fa-check"></i></a><a href="<?= base_url(); ?>job_master/delete_approved/<?= $cid ?>/<?= $kt_key["test_fk"] ?>" onclick="return confirm('Are you sure?');" id='dbtn_<?= $cid ?>_<?= $kt_key["test_fk"] ?>' style="display:none;" data-toggle="tooltip" title="Disapprove Test"><b>X</b></a><br>
                                    <?php
                                    $test_list[] = $kt_key["test_fk"];
                                }
                            }
                            $cnt++;
                        }
                        ?>
                        <a href="javascript:void(0);" id="approved_all_btn" style="display:none"  data-toggle="tooltip" title="Approve All Test" onclick="approve_all_test();" class="btn btn-sm btn-primary"><i class="fa fa-check"></i>Approve All Test</a>
                        <form action="<?= base_url(); ?>/job_master/approve_all_test?jid=<?php echo $cid; ?>" method="POST" id="approve_all_test_form">
                            <input type="hidden" name="id" id="result_added_test"/>
                        </form>
                        <script>
                            function approve_all_test() {
                                var result_added = $("#result_added_test").val();
                                approveAllTest('<?= $cid ?>', result_added);
                            }
                        </script>
                        <form role="form" action="<?php echo base_url(); ?>job_master/upload_report/<?= $cid ?>" method="post" enctype="multipart/form-data" id="submit_report">
                            <?= validation_errors('<div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">?</button>', '</div>'); ?>
                            <div class="box-body">
                                <?php if (!empty($error)) { ?> 
                                    <div class="alert alert-danger alert-dismissable">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">?</button>
                                        <?php echo $error['0']; ?>
                                    </div>
                                <?php } ?>

                                <div class="form-group">
                                    <label for="exampleInputFile">Upload Reports</label><span style="color:red">*</span><br>	
                                    <input type="file" id="common_report" name="common_report" <?php if ($query[0]['status'] == "2") { ?> style="display:none;" <?php } ?>><small>(with letterhead)</small>
                                    <input type="file" id="common_report1" name="common_report1" <?php if ($query[0]['status'] == "2") { ?> style="display:none;" <?php } ?>><small>(without letterhead)</small>
                                    <small>(Allow only .pdf file)</small><br>
                                    <?php if (!empty($common_report)) { ?>
                                        <?php
                                        if ($query[0]["is_print"] == 0 && $query[0]["panel_test_count"] == 0) {
                                            if ($query[0]['payable_amount'] <= 0 || $query[0]['payable_amount'] == '') {
                                                ?>
                                                <a href="<?php echo base_url(); ?>upload/report/<?php echo$common_report[0]['report'] . "?" . rand(00000, 99999); ?>" target="_blank"> <?php echo $common_report[0]['original']; ?> </a>,<a href="<?php echo base_url(); ?>upload/report/<?php echo$common_report[0]['without_laterpad'] . "?" . rand(00000, 99999); ?>" target="_blank"> <?php echo $common_report[0]['without_laterpad_original']; ?> </a> &nbsp; <?php if ($query[0]['status'] != "2") { ?><a href="<?php echo base_url(); ?>job_master/remove_report/<?php echo $common_report[0]['id']; ?>/<?php echo $common_report[0]['job_fk']; ?>" onclick="return confirm('Are you sure you want to remove this data?');" style="color:red;"> X </a> <?php } ?><br>
                                                <a href='javascript:void(0);' onclick="sms_popup('<?= $cid ?>');" data-toggle="tooltip" data-original-title="Send Report Via SMS" <?php if (!empty($row["send_repor_sms"])) { ?> style="background:green;color:white;width:auto;padding:0px 5px 3px 5px;border-radius:3px;" <?php } ?>><span class=""><i class="fa fa-comment-o"></i></span></a> &nbsp;&nbsp;&nbsp;
                                                <a href='javascript:void(0);' onclick="mail_popup('<?= $cid ?>', '<?= $query[0]['custid'] ?>');" data-toggle="tooltip" data-original-title="Send Report Via Mail" <?php if (!empty($row["send_report_mail"])) { ?> style="background:green;color:white;width:auto;padding:0px 5px 3px 5px;border-radius:3px;" <?php } ?>><span class=""><i class="fa fa-envelope-o"></i></span></a>
                                                <?php
                                                $completed_cnt++;
                                            } else {
                                                ?>
                                                <span class="label label-warning">
                                                    Please collect due amount.
                                                </span>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <a href = "<?php echo base_url(); ?>upload/report/<?php echo$common_report[0]['report'] . "?" . rand(00000, 99999); ?>" target = "_blank"> <?php echo $common_report[0]['original'];
                                            ?> </a>,<a href="<?php echo base_url(); ?>upload/report/<?php echo$common_report[0]['without_laterpad'] . "?" . rand(00000, 99999); ?>" target="_blank"> <?php echo $common_report[0]['without_laterpad_original']; ?> </a> &nbsp; <?php if ($query[0]['status'] != "2") { ?><a href="<?php echo base_url(); ?>job_master/remove_report/<?php echo $common_report[0]['id']; ?>/<?php echo $common_report[0]['job_fk']; ?>" onclick="return confirm('Are you sure you want to remove this data?');" style="color:red;"> X </a> <?php } ?><br>
                                            <a href='javascript:void(0);' onclick="sms_popup('<?= $cid ?>');" data-toggle="tooltip" data-original-title="Send Report Via SMS" <?php if (!empty($row["send_repor_sms"])) { ?> style="background:green;color:white;width:auto;padding:0px 5px 3px 5px;border-radius:3px;" <?php } ?>><span class=""><i class="fa fa-comment-o"></i></span></a> &nbsp;&nbsp;&nbsp;
                                            <a href='javascript:void(0);' onclick="mail_popup('<?= $cid ?>', '<?= $query[0]['custid'] ?>');" data-toggle="tooltip" data-original-title="Send Report Via Mail" <?php if (!empty($row["send_report_mail"])) { ?> style="background:green;color:white;width:auto;padding:0px 5px 3px 5px;border-radius:3px;" <?php } ?>><span class=""><i class="fa fa-envelope-o"></i></span></a>
                                            <?php
                                            $completed_cnt++;
                                        }
                                    }
                                    ?>
                                    <input type="hidden" name="type_common_report" value="c"/>
                                </div>
                                <span style="color:red;" id="reporterror_<?php echo $cnt; ?>"></span>
                                <div class="form-group">
                                    <label for="exampleInputFile">Description </label>
                                    <textarea id="desc_<?php echo $cnt; ?>" name="desc_common_report" class="form-control" <?php if ($query[0]['status'] == "2") { ?> disabled <?php } ?>><?php
                                        if (!empty($common_report)) {
                                            echo $common_report[0]['description'];
                                        }
                                        ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputFile">Report Status</label>
                                    <select class="form-control" name="report_status">
                                        <option value="">--Select--</option>
                                        <option value="1" <?php
                                        if ($query[0]["report_status"] == 1) {
                                            echo "selected";
                                        }
                                        ?>>Critical</option>
                                        <option value="2" <?php
                                        if ($query[0]["report_status"] == 2) {
                                            echo "selected";
                                        }
                                        ?>>Semi-Critical</option>
                                        <option value="3" <?php
                                        if ($query[0]["report_status"] == 3) {
                                            echo "selected";
                                        }
                                        ?>>Normal</option>
                                    </select>
                                </div>
                                <span style="color:red;" id="descerror_<?php echo $cnt; ?>"></span>
                                <hr/>
                                <?php /* Form common report end */ ?>
                                <input type="hidden" id="upload_report" value="<?= $completed_cnt; ?>"/>
                                <input type="hidden" value="<?php echo $cnt; ?>" id="count">
                                <div class="form-group">
                                    <?php if (!empty($report) and false) { ?>
                                        <label for="exampleInputFile">Uploaded Report </label><br>
                                        <?php foreach ($report as $re) { ?>
                                            <a href="<?php echo base_url(); ?>job_master/download_report/<?php echo $re['report']; ?>"> <?php echo $re['original']; ?> </a> &nbsp; <a href="<?php echo base_url(); ?>job_master/remove_report/<?php echo $re['id']; ?>/<?php echo $re['job_fk']; ?>" onclick="return confirm('Are you sure you want to remove this data?');" style="color:red;"> X </a><br>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            </div><!-- /.box-body --></form>
                        <?php if ($query[0]['sample_collection'] == "1" && $login_data['type'] != 7) { ?>
                            <div class="box-footer">
                                <?php
                                if ($query[0]['testname'] != '' || $query[0]['packagename'] != '') {
                                    if (empty($common_report)) {
                                        if ($login_data['type'] == 1 || $login_data['type'] == 2 || $login_data['type'] == 5) {
                                            ?>
                                            <div class="col-md-6">
                                                <button class="btn btn-primary" type="button" onclick="Validation();">Upload</button>
                                            </div>
                                            <?php
                                        }
                                    }
                                }
                                ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php if ($login_data['type'] != 7) { ?> 
                <div class="box box-primary">
                    <div class="box-header">
                        <!-- form start -->
                        <h3 class="box-title">Assign Phlebo</h3>
                    </div>
                    <div class="box-body">
                        <div class="alert alert-danger alert-dismissable" id="msg_cancel" style="display:none;">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">?</button>
                            Phlebo Not Assign Try Again
                        </div>
                        <div class="alert alert-success alert-dismissable" id="msg_success" style="display:none;">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">?</button>
                            Phlebo Assign Successfully
                        </div>
                        <div class="col-sm-12 res_pdng_0">
                            <div class="form-group col-sm-12  pdng_0">
                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Select Phlebo <i style="color:red">*</i>:</label>
                                <div class="col-sm-9 pdng_0">
                                    <select id="phlebo"  name="phlebo" class="chosen">
                                        <option value="">--Select--</option>
                                        <?php foreach ($phlebo_list as $phlebo) { ?>
                                            <option value="<?= $phlebo["id"] ?>" <?php
                                            if ($phlebo_assign_job[0]["phlebo_fk"] == $phlebo["id"]) {
                                                echo "selected";
                                            }
                                            ?>><?= ucfirst($phlebo["name"]); ?> (<?= $phlebo["mobile"] ?>)</option>
                                                <?php } ?>
                                    </select> 
                                </div>
                            </div>
                            <div class="form-group col-sm-12  pdng_0">
                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Date <i style="color:red">*</i>:</label>
                                <div class="col-sm-9 pdng_0">
                                    <input type="text" id="date" name="date" onchange="get_time(this.value);" value="<?php
                                    if (!empty($phlebo_assign_job)) {
                                        echo $phlebo_assign_job[0]["date"];
                                    } else {
                                        echo $emergency_tests[0]["date"];
                                    }
                                    ?>" class="form-control datepicker-input"/>
                                </div>
                                <span style="color:red;" id="phone_error"></span>
                            </div>
                            <div class="form-group col-sm-12  pdng_0">
                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Time <i style="color:red">*</i>:</label>
                                <div class="col-sm-9 pdng_0">
                                    <select id="time_slot"  name="time" class="chosen">
                                        <option value="">--Select--</option>
                                        <?php $emergency_tests[0]["time_slot_fk"] ?>
                                    </select>
                                    <input type="hidden" id="get_time1" value="<?php
                                    if ($phlebo_assign_job[0]["time_fk"] != '') {
                                        echo $phlebo_assign_job[0]["time_fk"];
                                    }
                                    ?>">
                                    <br>OR <br><input type="text" placeholder="Exact time" name="sharp_time" id="sharp_time" value="<?= $phlebo_assign_job[0]["time1"]; ?>"/>
                                    <input type="hidden" id="as_job_fk" value="<?php echo $cid; ?>"/>
                                </div>
                                <span style="color:red;" id="email_error"></span>
                            </div>
                            <div class="form-group col-sm-12  pdng_0">
                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Address <i style="color:red">*</i>:</label> 
                                <div class="col-sm-9 pdng_0">
                                    <textarea id="address" name="address" class="form-control"><?php
                                        if ($phlebo_assign_job[0]["address"]) {
                                            echo $phlebo_assign_job[0]["address"];
                                        } else {
                                            echo $emergency_tests[0]["address"];
                                        }
                                        ?></textarea>
                                </div>
                            </div>
                            <div class="form-group col-sm-12  pdng_0">
                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Note :</label> 
                                <div class="col-sm-9 pdng_0">
                                    <textarea id="p_note" name="note" class="form-control"></textarea>
                                    <span style="color:red;" id="p_note_error"></span>
                                    <span style="color:red;" id="assign_phlebo_error"></span>
                                </div>
                            </div>
                            <div class="form-group col-sm-12  pdng_0">
                                <div class="col-sm-9 pdng_0" style="float:right;">
                                    <input type="checkbox" id="notify" name="notify" value="1" checked style="display:none;">
                                </div>
                            </div>
                            <?php /* if (!empty($phlebo_assign_job[0])) { ?>
                              <div class="form-group col-sm-12  pdng_0">
                              <div class="col-sm-9 pdng_0" style="float:right;">
                              <span class="label label-success">Phlebo already assigned.</span>
                              </div>
                              </div>
                              <?php } ?>
                              <?php
                              if (!empty($phlebo_assign_job[0])) {
                              if (!empty($phlebo_assign_job[0]["is_accept"] == 1)) {
                              ?>
                              <div class="form-group col-sm-12  pdng_0">
                              <div class="col-sm-9 pdng_0" style="float:right;">
                              <span class="label label-success">Phlebo Accept Job.</span>
                              </div>
                              </div>
                              <?php } else { ?>
                              <div class="form-group col-sm-12  pdng_0">
                              <div class="col-sm-9 pdng_0" style="float:right;">
                              <span class="label label-warning">Waiting for phlebo accept the job.</span>
                              </div>
                              </div>
                              <?php
                              }
                              } */
                            ?>
                            <div class="box-footer">
                                <input onclick="assign_phlebo();" id="send_btn" style="float:right;" class="btn btn-primary" value="Assign" type="button">
                            </div>
                            <?php /* if ($query[0]['status'] != 8) { ?>
                              <div class="box-footer">
                              <span id="loader_div" style="display:none;"><img src="<?= base_url(); ?>upload/opc-ajax-loader.gif" style="height:28px;margin-left:10px"></span>
                              <?php if (!empty($phlebo_assign_job[0])) { ?>
                              <input onclick="update_phlebo();" id="send_btn" style="float:right;" class="btn btn-primary" value="Update" type="button">
                              <?php } else { ?>
                              <input onclick="assign_phlebo();" id="send_btn" style="float:right;" class="btn btn-primary" value="Assign" type="button">
                              <?php }  ?>
                              <script>
                              function update_phlebo() {
                              var phlb = confirm("Are you sure?");
                              if (phlb == true) {
                              assign_phlebo();
                              }
                              }
                              </script>
                              </div>
                              <?php } */ ?>
                            <h3>Phlebo Schedule</h3>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Schedule</th>
                                        <th>Address</th>
                                        <th>Note</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($phlebo_assign_job as $key) { ?>
                                        <tr>
                                            <td><?php foreach ($phlebo_list as $phlebo) { ?>
                                                    <?php
                                                    if ($key["phlebo_fk"] == $phlebo["id"]) {
                                                        echo ucfirst($phlebo["name"]) . "-" . $phlebo["mobile"];
                                                    }
                                                    ?>
                                                <?php } ?></td>
                                            <td><?= $key["date"] . '-' . $key["time"]; ?></td>
                                            <td><?= ucfirst($key["address"]) ?></td>
                                            <td><?= ucfirst($key["note"]) ?></td>
                                            <td><?php
                                                if ($key["is_accept"] == 1) {
                                                    echo '<span class="label label-success">Phlebo Accept Job.</span>';
                                                } else {
                                                    echo '<span class="label label-warning">Waiting for phlebo accept the job.</span>';
                                                }
                                                ?></td>
                                            <td>
                                                <?php if ($key["is_accept"] == 0) { ?>
                                                    <a href="<?= base_url(); ?>job_master/accep_assign_phlebo_job/<?= $cid; ?>/<?= $key["id"] ?>" data-toggle="tooltip" data-original-title="Accept" onclick="return confirm('Are you sure?');"><i class="fa fa-check"></i></a>
                                                <?php } ?>
                                                <a href="<?= base_url(); ?>job_master/delete_assign_phlebo/<?= $cid; ?>/<?= $key["id"] ?>" data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure?');"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <?php if (empty($phlebo_assign_job)) { ?>
                                        <tr><td colspan="4"><center>Phlebo not assign.</center></td></tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="box-header">
                        <!-- form start -->
                        <h3 class="box-title">Phlebotomy schedule for <?php echo $booking_info[0]['date']; ?></h3>
                    </div>
                    <div class="box-body" id="phlebo_schedule">

                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="col-md-6">
            <div class="box box-primary">
                <?php if (isset($success) != NULL) { ?>
                    <div class="widget">
                        <div class="alert alert-success alert-dismissable">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">?</button>
                            <?php echo $success['0']; ?>
                        </div>
                    </div>
                <?php } ?>
                <div class="box-header">
                    <?php
                    if ($query[0]['sample_collection'] == "0") {
                        if ($query[0]['status'] == "1" || $query[0]['status'] == "2") {
                            ?>			 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <!--<a onclick="return confirm('Are you sure?');" style="float:right; margin-top:10px; margin-right:10px;" href='<?php echo base_url(); ?>job_master/job_mark_spam/<?php echo $query[0]['id']; ?>' data-toggle="tooltip" data-original-title="Mark as Spam" ><span class="label label-danger">Mark as Spam</span></a>   -->
                            <?php
                        }
                    }
                    ?>
                    <?php
                    if ($query[0]['sample_collection'] == "1") {
                        if ($query[0]['status'] == "1" || $query[0]['status'] == "3") {
                            ?>		
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <!--<a onclick="return confirm('Are you sure?');"  style="float:right; margin-right:10px; margin-top:10px;" href='<?php echo base_url(); ?>job_master/job_mark_completed/<?php echo $query[0]['id']; ?>' data-toggle="tooltip" data-original-title="Mark as completed" ><span class="label label-success">Mark as completed</span> </a>   -->
                            <?php
                        }
                    }
                    ?>
                    <!-- form start -->
                    <h3 class="box-title">Customer Details</h3>

                    <div class="btn-group" style="float:right;">
                        <?php if ($query[0]['status'] == 7 || $query[0]['status'] == 8 || $query[0]['status'] == 2) { ?>
                            <a class="btn btn-default btn-sm" href="<?php echo base_url(); ?>add_result/preview_report/<?php echo $cid; ?>?type=wlp" data-toggle="tooltip" title="Preview Report" target="_blank"><i class="fa fa-search"></i> Preview</a>
                        <?php } ?>
                        <button class="btn btn-default btn-sm" onclick="$('#print_model').modal('show');" data-toggle="tooltip" title="Print Report"><i class="fa fa-print"></i>&nbsp;Print Report</button>
                        <div class="modal fade" id="print_model" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Print Report</h4>
                                    </div>
                                    <div class="modal-body">
                                        <?php
                                        if ($query[0]["is_print"] == 0 && $query[0]["panel_test_count"] == 0) {
                                            if ($query[0]['payable_amount'] <= 0 || $query[0]['payable_amount'] == '') {
                                                ?>
                                                <?php if (!empty($common_report[0]['report']) || !empty($common_report[0]['without_laterpad'])) { ?>
                                                    <h5>Do you want to print with letterhead?</h5>
                                                <?php } else { ?>
                                                    <h5 style="color:red;">Please upload report!</h5>
                                                    <?php
                                                }
                                            } else {
                                                echo '<h5 style="color:red;">Please collect due amount.</h5>';
                                            }
                                        } else {
                                            if (!empty($common_report[0]['report']) || !empty($common_report[0]['without_laterpad'])) {
                                                ?>
                                                <h5>Do you want to print with letterhead?</h5>
                                            <?php } else { ?>
                                                <h5 style="color:red;">Please upload report!</h5>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                    <div class="modal-footer">
                                        <?php
                                        if ($query[0]["is_print"] == 0 && $query[0]["panel_test_count"] == 0) {
                                            if ($query[0]['payable_amount'] <= 0 || $query[0]['payable_amount'] == '') {
                                                ?>
                                                <?php if (!empty($common_report[0]['report'])) { ?>
                                                    <a class="btn btn-primary" href="<?php echo base_url(); ?>job_master/open_report/<?php echo $cid; ?>/1" target="_blank" onclick="$('#print_model').modal('hide');" id="print_simple">Yes</a>
                                                <?php } ?>
                                                <?php if (!empty($common_report[0]['without_laterpad'])) { ?>
                                                    <a class="btn btn-primary" href="<?php echo base_url(); ?>job_master/open_report/<?php echo $cid; ?>/2" target="_blank" onclick="$('#print_model').modal('hide');" id="print_wlpd">No</a>
                                                <?php } ?>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <?php if (!empty($common_report[0]['report'])) { ?>
                                                <a class="btn btn-primary" href="<?php echo base_url(); ?>job_master/open_report/<?php echo $cid; ?>/1" target="_blank" onclick="$('#print_model').modal('hide');" id="print_simple">Yes</a>
                                            <?php } ?>
                                            <?php if (!empty($common_report[0]['without_laterpad'])) { ?>
                                                <a class="btn btn-primary" href="<?php echo base_url(); ?>job_master/open_report/<?php echo $cid; ?>/2" target="_blank" onclick="$('#print_model').modal('hide');" id="print_wlpd">No</a>
                                            <?php } ?>
                                        <?php } ?>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <hr>
                </div>
                <div class="box-body">
                    <?php if ($query[0]["model_type"] == 1) {
                        ?>
                        <div class="col-md-6 res_pdng_0" style="border-right:1px solid #ccc;">
                            <?php if ($relation == 'Self') { ?>
                                <div class="form-group">
                                    <label for="exampleInputFile">Patient Name :</label> <?php echo ucfirst($query[0]['full_name']); ?> (Self)&nbsp;<a href="javascript:void(0)" onclick="$('#family_model12').modal('show');"> <i class="fa fa-edit"></i></a> &nbsp;<a href="javascript:void(0)" onclick="$('#family_model').modal('show');"> Change</a>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Notify Customer :</label> <?php
                                    if ($query[0]['notify_cust'] == 1) {
                                        echo '<span class="label label-success">Yes</span>';
                                    } else {
                                        echo '<span class="label label-danger">No</span>';
                                    }
                                    ?>
                                    <a href="javascript:void(0);" onclick="$('#notify_model').modal('show');"><i class="fa fa-edit"></i></a>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Mobile :</label> <?php echo ucwords($query[0]['mobile']); ?>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Email :</label> <?php echo $query[0]['email']; ?>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">DOB :</label> <?php echo $query[0]['dob']; ?>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Age :</label> <?php
                                    if ($age[0] != 0) {
                                        echo $age[0] . " Y";
                                    }
                                    if ($age[0] == 0 && $age[1] != 0) {
                                        echo $age[1] . " M";
                                    }
                                    if ($age[0] == 0 && $age[1] == 0 && $age[2] != 0) {
                                        echo $age[2] . " D";
                                    }
                                    ?>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputFile">Gender :</label> <?php echo ucwords($query[0]['gender']); ?>
                                </div>
                            <?php } else { ?>
                                <div class="form-group">
                                    <label for="exampleInputFile">Patient Name:</label> <?php echo $relation; ?>&nbsp;<a href="javascript:void(0)" onclick="$('#family_model1').modal('show');"> <i class="fa fa-edit"></i></a> &nbsp;<a href="javascript:void(0)" onclick="$('#family_model').modal('show');"> Change</a>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Mobile :</label> <?php echo ucwords($family_data[0]['phone']); ?>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Email :</label> <?php echo $family_data[0]['email']; ?>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">DOB :</label> <?php echo $family_data[0]['dob']; ?>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Age :</label> <?php
                                    if ($f_age[0] == '0') {
                                        echo $f_age[1] . " M";
                                    } else if ($f_age[0] == '0' && $f_age[1] == '0') {
                                        echo $f_age[2] . " D";
                                    } else {
                                        echo $f_age[0] . " Y";
                                    }
                                    ?>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputFile">Gender :</label> <?php echo ucwords($family_data[0]['gender']); ?>
                                </div>
                            <?php } ?>

                            <?php if ($query[0]['state'] != NULL) { ?>
                                <div class="form-group">
                                    <label for="exampleInputFile">State :</label> <?php
                                    foreach ($state as $st) {
                                        if ($st['id'] == $query[0]['state']) {
                                            echo ucwords($st['state_name']);
                                        }
                                    }
                                    ?>
                                </div>
                            <?php } ?>
                            <?php if ($query[0]['city'] != NULL) { ?>
                                <div class="form-group">
                                    <label for="exampleInputFile">City :</label> <?php
                                    foreach ($city as $ct) {
                                        if ($ct['id'] == $query[0]['city']) {
                                            echo ucwords($ct['city_name']);
                                        }
                                    }
                                    ?>
                                </div>
                            <?php } ?>
                            <div class="form-group">
                                <label for="exampleInputFile">Address :</label> <?php echo $booking_info[0]['address']; ?>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Doctor :</label> <?php echo ucwords($query[0]['dname']) . " (" . $query[0]['dmobile'] . ")"; ?>
                                <a href="javascript:void(0);" onclick="$('#doctor_model').modal('show');"><i class="fa fa-edit"></i></a>
                            </div>


                            <div class="form-group">
                                <label for="exampleInputFile">Test City :</label> <?php
                                if ($query[0]["test_city"] == 1) {
                                    echo "Ahmedabad";
                                } else if ($query[0]["test_city"] == 2) {
                                    echo "Surat";
                                } else if ($query[0]["test_city"] == 3) {
                                    echo "Vadodara";
                                } else if ($query[0]["test_city"] == 4) {
                                    echo "Gurgaon";
                                } else if ($query[0]["test_city"] == 5) {
                                    echo "Delhi";
                                } else if ($query[0]["test_city"] == 6) {
                                    echo "Gandhinagar";
                                } else if ($query[0]["test_city"] == 7) {
                                    echo "Kolkata";
                                } else if ($query[0]["test_city"] == 8) {
                                    echo "Sonepat";
                                } else if ($query[0]["test_city"] == 9) {
                                    echo "Assam";
                                }
                                ?>

                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Branch :</label>
                                <?php
                                foreach ($branch_list as $branch) {
                                    if ($query[0]['branch_fk'] == $branch['id']) {
                                        echo ucwords($branch['branch_name']);
                                    }
                                }
                                ?>
                                <?php if (($login_data['type'] != 5 && $login_data['type'] != 6 && $login_data['type'] != 7) || $login_data['id'] == 17) { ?>
                                    <a href="javascript:void(0);" onclick="$('#branch_model').modal('show');"><i class="fa fa-edit"></i></a>
                                <?php } ?>
                                <?php /* if ($login_data['type'] == 5) { ?>
                                  <a href="javascript:void(0);" onclick="$('#branch_model').modal('show');"><i class="fa fa-edit"></i></a>
                                  <?php } */ ?>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Creditors :</label>
                                <?php
                                if (!empty($selected_creditor)) {
                                    echo ucfirst($selected_creditor[0]["name"]) . " - " . $selected_creditor[0]["mobile"];
                                } else {
                                    echo "NA";
                                }
                                ?>
                                <a href="javascript:void(0);" onclick="$('#creditor_model').modal('show');"><i class="fa fa-edit"></i></a>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Sample Collection Date :</label>
                                <?php echo $booking_info[0]['date']; ?>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Choose Time Slot :</label>
                                <?php echo $booking_info[0]['start_time'] . " To " . $booking_info[0]['end_time']; ?>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputFile">Invoice download :</label>
                                <a href="<?php if (!empty($query[0]['invoice'])) { ?><?= base_url(); ?>upload/result/<?php echo $query[0]['invoice']; ?><?php } else { ?><?= base_url(); ?>job_master/pdf_invoice/<?= $cid ?><?php } ?>" target="_blank"><i class="fa fa-download"></i></a>
                                &nbsp;&nbsp;<a href="<?= base_url(); ?>job_master/pdf_invoice/<?= $cid ?>" target="_blank">Regenerate</a>
                                &nbsp;&nbsp;<a href="<?= base_url(); ?>job_master/pdf_invoice_without/<?= $cid ?>" target="_blank">Regenerate without letterhead</a>
                                <iframe id='print_iframe' name='print-frame-name' src="<?= base_url(); ?>upload/result/<?php echo $query[0]['invoice']; ?>" style="display:none;"></iframe>
                                &nbsp;&nbsp;<a href="javascript:void(0);" onclick="printDocument('print_iframe');"><i class="fa fa-print"></i></a>
                                <script>
                                    function printDocument(documentId) {
                                        document.getElementById(documentId).contentWindow.print();
                                    }
                                </script>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Acknowledgment download :</label>
                                <a href="<?= base_url(); ?>job_master/ack/<?php echo $query[0]['id']; ?>" target="_blank"><i class="fa fa-print"></i>  Regenerate</a>
                                &nbsp;&nbsp;<a href="<?= base_url(); ?>job_master/ack_wtlpd/<?php echo $query[0]['id']; ?>" target="_blank">  Regenerate without letterhead <i class="fa fa-print"></i></a>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Note :</label> <textarea id="job_note" class="form-control"><?php echo ucwords($query[0]['note']); ?></textarea>
                                <span id="note_success"></span>
                            </div>
                            <button class="btn-sm btn-primary res_mrgn_btm_15px" type="button" id="note_save_btn" onclick="update_note();">Save</button>
                            <span id="loader_div2" style="display:none;"><img src="<?= base_url(); ?>upload/opc-ajax-loader.gif" style="height:28px;margin-left:10px"></span>
                        </div>
                        <script>
                            function update_note() {
                                var job_note = $("#job_note").val();
                                var as_job_fk = "<?= $cid; ?>";
                                $("#note_success").html("");
                                if (job_note.trim() != '') {
                                    $.ajax({
                                        url: "<?php echo base_url(); ?>job_master/job_note_update",
                                        type: 'post',
                                        data: {job_note: job_note, job_id: as_job_fk},
                                        beforeSend: function () {
                                            $("#loader_div2").attr("style", "");
                                            $("#note_save_btn").attr("disabled", "disabled");
                                        },
                                        success: function (data) {
                                            var json_data = JSON.parse(data);
                                            if (json_data.status == 1) {
                                                $("#note_success").html(json_data.msg);
                                            } else {
                                                $("#note_success").html(json_data.msg);
                                            }
                                            setTimeout(function () {
                                                $("#note_success").html("");
                                            }, 3000);
                                        }, complete: function () {
                                            $("#loader_div2").attr("style", "display:none;");
                                            $("#note_save_btn").removeAttr("disabled");
                                        }
                                    });
                                }
                            }
                        </script>
                        <div class="col-md-6 res_pdng_0">
                            <div class="form-group">
                                <?php if ($login_data['type'] != 7) { ?>
                                    <select class="form-control chosen-select" data-placeholder="Select Status" tabindex="-1" name="status" id="status" <?php
                                    if ($query[0]['status'] == 2) {
                                        echo "disabled";
                                    }
                                    ?>>
                                        <option value="">Select Status </option>
                                        <option value="1" <?php
                                        if ($query[0]['status'] == 1) {
                                            echo "selected";
                                        }
                                        ?>> Waiting For Approval </option>
                                        <option value="6" <?php
                                        if ($query[0]['status'] == 6) {
                                            echo "selected";
                                        }
                                        ?>> Approved </option>
                                        <option value="7" <?php
                                        if ($query[0]['status'] == 7) {
                                            echo "selected";
                                        }
                                        ?>> Sample Collected </option>
                                        <option value="8" <?php
                                        if ($query[0]['status'] == 8) {
                                            echo "selected";
                                        }
                                        ?>> Processing </option>
                                        <option value="2" <?php
                                        if ($query[0]['status'] == 2) {
                                            echo "selected";
                                        }
                                        ?>> Completed </option>
                                    </select>
                                <?php } ?>
                                <?php if ($login_data['type'] != 7) { ?>
                                    <button class="btn-sm btn-primary res_mrgn_top_15px" type="button" id="change" onclick="change_status();" <?php
                                    if ($query[0]['status'] == 2) {
                                        echo "disabled";
                                    }
                                    ?>>Save</button>
                                        <?php } ?>
                                <span id="loader_div1" style="display:none;"><img src="<?= base_url(); ?>upload/opc-ajax-loader.gif" style="height:28px;margin-left:10px"></span>

                            </div>
                            <div class="form-group">
                                <?php
                                if ($emergency_tests[0]["emergency"] == '1') {
                                    echo '<span class="label label-danger">Emergency</span>';
                                } else {
                                    echo '<span class="label label-warning">Normal</span>';
                                }
                                ?>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputFile">Total Amount:</label> <?php echo "Rs." . $query[0]['price']; ?>  
                            </div>
                            <?php if ($cut_from_wallet != 0) { ?>
                                <div class="form-group">
                                    <label for="exampleInputFile">Debited From Wallet:</label> <?php echo "Rs." . $cut_from_wallet; ?>  
                                </div>
                            <?php } ?>
                            <div class="form-group">
                                <label for="exampleInputFile">Due/Payable Amount:</label> <?php
                                $j_payable_price = 0;
                                if ($query[0]['payable_amount'] == "") {
                                    echo "Rs." . "0";
                                } else {
                                    $j_payable_price = $query[0]['payable_amount'];
                                    echo "Rs." . $query[0]['payable_amount'];
                                }
                                ?>  <a href="javascript:void(0)" onclick="$('#payment_model').modal('show');"> <i class="fa fa-edit"></i></a>
                            </div>
                            <input type="hidden" value="<?php echo $cid; ?>" id="job_id">
                            <div class="form-group">
                                <label for="exampleInputFile">Discount:</label> <?php
                                echo $query[0]['discount'] . "%";
                                ?>  <?php if ($login_data['type'] != 7 && $login_data['type'] != 6) { ?> <a href="javascript:void(0)" onclick="$('#discount_model').modal('show');"> <i class="fa fa-edit"></i></a> <?php } ?>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Payment Type :</label> <?php echo $query[0]['payment_type']; ?>  
                            </div>
                            <!--                        <div class="form-group">
                                                        <label for="exampleInputFile">Payment Via :</label> <?php
                            foreach ($payment_type_list as $key) {
                                if ($query[0]['payment_type_fk'] == $key["id"]) {
                                    echo $key["name"];
                                }
                            }
                            ?>  
                                                        <a href="javascript:void(0)" onclick="$('#payment_model1').modal('show');"> <i class="fa fa-edit"></i></a>
                                                    </div>-->

                            <div class="form-group">
                                <label for="exampleInputFile">Blood Sample Collection Status :</label> <?php
                                if ($query[0]['sample_collection'] == "1") {
                                    echo "Yes";
                                } else {
                                    echo "No";
                                }
                                ?>  
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Collection Charge :</label> <?php
                                if ($query[0]['collection_charge'] == 1) {
                                    echo '<span class="label label-success">Yes</span>';
                                } else {
                                    echo '<span class="label label-danger">No</span>';
                                }
                                ?>
                                <?php if ($login_data['type'] == 1 || $login_data['type'] == 2) { ?>
                                    <a href="javascript:void(0);" onclick="$('#colection_charge_model').modal('show');"><i class="fa fa-edit"></i></a>
                                <?php } ?>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Job Status :</label>   <?php
                                if ($query[0]['status'] == 1) {
                                    echo "<span class='label label-danger'>Waiting For Approval</span>";
                                }
                                ?>
                                <?php
                                if ($query[0]['status'] == 6) {
                                    echo "<span class='label label-warning'>Approved</span>";
                                }
                                ?>
                                <?php
                                if ($query[0]['status'] == 7) {
                                    echo "<span class='label label-warning'>Sample Collected</span>";
                                }
                                ?>
                                <?php
                                if ($query[0]['status'] == 8) {
                                    echo "<span class='label label-warning'>Processing</span>";
                                }
                                ?>
                                <?php
                                if ($query[0]['status'] == 2) {
                                    echo "<span class='label label-success'>Completed</span>";
                                }
                                ?>
                                <?php
                                if ($query[0]['status'] == 0) {
                                    echo "<span class='label label-danger'>Spam</span>";
                                }
                                ?>
                            </div>
                            <?php if ($common_report[0]['report'] != '') { ?>
                                <div class="form-group">
                                    <label for="exampleInputFile">Report Status :</label>
                                    <?php
                                    if ($query[0]["report_status"] == 1) {
                                        echo '<span class="label label-danger">Critical</span>';
                                    } else if ($query[0]["report_status"] == 2) {
                                        echo '<span class="label label-warning">Semi-Critical</span>';
                                    } else if ($query[0]["report_status"] == 3) {
                                        echo '<span class="label label-success">Normal</span>';
                                    } else {
                                        echo '<span class="label label-danger">NA</span>';
                                    }
                                    ?>
                                    <?php if ($login_data['type'] != 6 && $login_data['type'] != 7) { ?>
                                        <a href="javascript:void(0);" onclick="$('#report_status_modal').modal('show');"><i class="fa fa-edit"></i></a>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                            <div class="form-group">
                                <label for="exampleInputFile">Barcode :</label>
                                <?php echo $query[0]['barcode']; ?>
                                <?php if ($login_data['type'] != 6 && $login_data['type'] != 7) { ?>
                                    <a href="javascript:void(0);" onclick="$('#barcode_model').modal('show');"><i class="fa fa-edit"></i></a>
                                <?php } ?>
                            </div>
                            <?php if ($query[0]['portal']) { ?>
                                <div class="form-group">
                                    <label for="exampleInputFile">Book Portal :</label> <?php
                                    echo $query[0]['portal'];
                                    ?>  
                                </div>
                            <?php } ?>
                            <div class="form-group">
                                <label for="exampleInputFile">Reference :</label> <?php
                                echo $query[0]['other_reference'];
                                ?>  
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Sample From :</label> <?php
                                if (!empty($query[0]['sample_from_name'])) {
                                    echo ucfirst($query[0]['sample_from_name']);
                                } else {
                                    echo "NA";
                                }
                                ?>  
                            </div>
                            <?php if ($query[0]['payment_type'] == 'PayUMoney') { ?>
                                <div class="form-group">
                                    <label for="exampleInputFile">Transaction ID :</label> <?php
                                    echo $payumoney_details[0]['payomonyid'];
                                    ?>  
                                </div>

                            <?php } ?>
                            <?php /* Nishit booking details start */ ?>
                            <h3 class="box-title">Clinical History</h3>
                            <hr style="margin-top:10px;">
                            <div class="form-group">
                                <label for="exampleInputFile"><?php
                                    if ($query[0]["clinical_history"] == 1) {
                                        echo "Yes";
                                    } else {
                                        echo "No";
                                    }
                                    ?></label> 
                            </div>
                            <?php if ($query[0]["clinical_history"] == 1) { ?>
                                <div class="form-group">
                                    <label for="exampleInputFile">Message :</label> <?php echo ucfirst($query[0]["prescription_message"]); ?>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Prescription :</label> <a href="<?php echo base_url(); ?>upload/doctor_description/<?php echo $query[0]["prescription_file"]; ?>" target="_blank"> View</a>
                                </div>
                            <?php } ?>
                            <?php if ($query[0]["model_type"] == 1) {
                                ?>
                                <h3 class="box-title">Account Holder Information</h3>
                                <hr style="margin-top:10px;">
                                <div class="form-group">
                                    <?php
                                    $check_pc = substr($query[0]['pic'], 0, 6);
                                    if ($check_pc == "https:") {
                                        ?>
                                        <img src="<?php echo $query[0]['pic']; ?>" onerror="this.src='<?= base_url(); ?>upload/avatar/profile_avatar.png'" class="img-circle admin_job_dtl_img"  style="height: 70px;width:auto"/>
                                    <?php } else { ?>
                                        <img class="img-circle admin_job_dtl_img" onerror="this.src='<?= base_url(); ?>upload/avatar/profile_avatar.png'" src="<?php echo base_url(); ?>upload/<?php echo $query[0]['pic']; ?>" style="height: 70px;width:auto"/>
                                    <?php } ?>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Name :</label> <a href="<?php echo base_url(); ?>customer-master/customer-all-details/<?php echo $query[0]['custid']; ?>"> <?php echo ucfirst($query[0]['full_name']); ?></a>&nbsp;<a href="javascript:void(0)" onclick="$('#family_model12').modal('show');"> <i class="fa fa-edit"></i></a>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Mobile :</label> <?php echo ucwords($query[0]['mobile']); ?>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Email :</label> <?php echo $query[0]['email']; ?>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">DOB :</label> <?php echo $query[0]['dob']; ?>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Age :</label> <?php
                                    if ($age[0] == '0' && $age[2] == '0') {
                                        echo $age[1] . " M";
                                    } else if ($age[0] == '0' && $age[1] == '0') {
                                        echo $age[2] . " D";
                                    } else {
                                        echo $age[0] . " Y";
                                    }
                                    ?>
                                </div>
                            <?php } /* Nishit booking details end */ ?>
                        </div>
                    <?php } else { ?>
                        <div class="form-group">
                                <?php
                                if ($query[0]['status'] != 2) {
                                    if (!empty($common_report[0]['report']) || !empty($common_report[0]['without_laterpad'])) {
                                        ?>
                                        <a class="btn-sm btn-primary res_mrgn_top_15px" href="<?= base_url() . '/btob_job_master/mark_complete/' . $cid ?>"> Mark As Complete</a>

                                    <?php } else {
                                        echo "<label>Please Genereate report for complete.</label>";
                                    }
                                    
                                    } else {
                                        
                                        ?>
                                        <label > Report Completed</label>
                                        <? }
                                    ?>

        

                                <span id="loader_div1" style="display:none;"><img src="<?= base_url(); ?>upload/opc-ajax-loader.gif" style="height:28px;margin-left:10px"></span>

                            </div>
        <?php if ($relation == 'Self') { ?>

                                <div class="form-group">
                                    <label for="exampleInputFile">Patient Name :</label> <?php echo ucfirst($query[0]['full_name']); ?> 
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputFile">Mobile :</label> <?php echo ucwords($query[0]['mobile']); ?>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Email :</label> <?php echo $query[0]['email']; ?>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">DOB :</label> <?php echo $query[0]['dob']; ?>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Age :</label> <?php
                                    if ($age[0] != 0) {
                                        echo $age[0] . " Y";
                                    }
                                    if ($age[0] == 0 && $age[1] != 0) {
                                        echo $age[1] . " M";
                                    }
                                    if ($age[0] == 0 && $age[1] == 0 && $age[2] != 0) {
                                        echo $age[2] . " D";
                                    }
                                    ?>
                                </div>



                                <div class="form-group">
                                    <label for="exampleInputFile">Gender :</label> <?php echo ucwords($query[0]['gender']); ?>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputFile">Barcode :</label> <?php echo $query[0]['barcode']; ?>
                                </div>

        <?php }
    }
    ?> 
                        <div class="col-sm-12 res_pdng_0"><div id="job_tracking"></div></div>
                    </div>
                </div>
            </div>
    <?php if ($login_data['type'] != 5 && $login_data['type'] != 6 && $login_data['type'] != 7) { ?>
                <div class="col-md-6">
                    <div class="box box-primary">

                        <div class="box-header">

                            <!-- form start -->
                            <h3 class="box-title">Phlebo OTP</h3>
                        </div>
                        <div class="box-body">
                            <div class="col-md-6 res_pdng_0">
                                    <?php /* if ($query[0]['pic'] != NULL) { */ ?>
                                <div class="form-group">
                                    <label for="exampleInputFile">Phlebo Blood Collect OTP :</label>
                                    <?php
                                    if ($query[0]['status'] == 7 || $query[0]['status'] == 8 || $query[0]['status'] == 2) {
                                        echo $phlebo_rate[0]["otp"];
                                    } else {
                                        echo "Sample not collect.";
                                    }
                                    ?>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

    <?php } /* Nishit change payment status start */ ?>
            <!-- Modal -->
            <div class="modal fade" id="payment_model" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Payment Status</h4>
                        </div>
    <?php echo form_open("job_master/payment_received/" . $cid, array("onsubmit" => "return confirm('Are you sure?');", "method" => "POST", "role" => "form", "id" => "payment_receiv_form_id")); ?>
                        <div class="modal-body">
                                    <?php if (!empty($amount_history_success)) { ?>
                                <div class="widget">
                                    <div class="alert alert-success alert-dismissable">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
        <?php echo $amount_history_success['0']; ?>
                                    </div>
                                </div>
    <?php } ?>
                            <div class="form-group">
                                <label for="recipient-name" class="control-label">Receive Amount<span style="color:red;">*</span>:</label>
                                <input type="number" class="form-control" id="j_amount" name="amount" required="">
                                <input type="hidden" id="ttl_amount" name="ttl_amount" value="<?= $query[0]['payable_amount']; ?>"/>
                                <span style="color:red;" id="amount_error"></span>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" placeholder="Remark" name="remark"></textarea>
                            </div>
                            <div class="form-group">
                                <select id="branch" name="p_type" class="form-control" style="width: 100%" required="">
                                    <option value="">--Select Type--</option>
                                    <?php foreach ($payment_type_list as $key) {
                                        ?>
                                        <option value="<?php echo $key['name']; ?>"><?php echo ucwords($key['name']); ?></option>

        <?php
    }
    ?>
                                </select>
                                <span style="color:red;" id="amount_error"></span>
                            </div>
                            <script>
                                $("#j_amount").keyup(function () {
                                    $("#amount_error").html("");
                                    var val = parseInt(this.value);
                                    var ttl_amt = '<?= $query[0]['payable_amount']; ?>';
                                    ttl_amt = parseInt(ttl_amt);
                                    if (val != null) {
                                        console.log("ttl" + ttl_amt + " input" + val);
                                        if (ttl_amt >= val) {
                                            $("#amount_submit_btn").removeAttr("disabled");
                                        } else {
                                            $("#amount_submit_btn").attr("disabled", "");
                                            $("#amount_error").html("Given amount is more than payable amount.");
                                        }
                                    } else {
                                        $("#amount_submit_btn").attr("disabled", "");
                                        $("#amount_error").html("Invalid amount.");
                                    }
                                });
                            </script>
                            <h3>Received Amount History</h3>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Remark</th>
                                        <th>Pay Via</th>
                                        <th>Added By</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

    <?php
    $ttl = 0;
    if ($phlebo_collect[0]["amount"] > 0) {
        ?>
                                        <tr>
                                            <td><?= $phlebo_collect[0]["createddate"] ?></td>
                                            <td>Rs.<?php
                                        $ttl = $ttl + round($phlebo_collect[0]["amount"]);
                                        echo $phlebo_collect[0]["amount"];
                                        ?></td>
                                            <td>Phlebo Collected</td>
                                            <td>Cash</td>
                                            <td><?= $phlebo_collect[0]["name"] ?></td>
                                            <td></td>
                                        </tr>
    <?php } ?>
    <?php
    if ($online_pay[0]["amount"] > 0) {
        ?>
                                        <tr>
                                            <td><?= $query[0]["date"] ?></td>
                                            <td>Rs.<?php
                                        echo round($online_pay[0]["amount"]);
                                        $ttl = $ttl + round($online_pay[0]["amount"]);
                                        ?></td>
                                            <td>Online pay (PayUmoney)</td>
                                            <td>PayUmoney</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
    <?php } ?>
    <?php
    if ($creditors_pay[0]["amount"] > 0) {
        ?>
                                        <tr>
                                            <td><?= $creditors_pay[0]["created_date"] ?></td>
                                            <td>Rs.<?php
                                        echo round($creditors_pay[0]["amount"]);
                                        $ttl = $ttl + round($creditors_pay[0]["amount"]);
                                        ?></td>
                                            <td></td>
                                            <td>Creditors</td>
                                            <td><?php echo $creditors_pay[0]['name']; ?></td>
                                            <td></td>
                                        </tr>
    <?php } ?>
    <?php
    if ($cut_from_wallet > 0) {
        ?>
                                        <tr>
                                            <td><?= $query[0]["date"] ?></td>
                                            <td>Rs.<?php
                                        echo $cut_from_wallet;
                                        $ttl = $ttl + $cut_from_wallet;
                                        ?></td>
                                            <td>Cut from wallet</td>
                                            <td>Wallet</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
    <?php } ?>
    <?php
    if ($query[0]["discount"] > 0) {
        ?>
                                        <tr>
                                            <td><?= $query[0]["date"] ?></td>
                                            <td>Rs.<?php
                                        echo round(($query[0]["price"] * $query[0]["discount"]) / 100);
                                        $ttl = $ttl + round(($query[0]["price"] * $query[0]["discount"]) / 100);
                                        ?></td>
                                            <td>Discount</td>
                                            <td></td>
                                            <td><?= $discount_added_by[0]["name"] ?></td>
                                            <td></td>
                                        </tr>
                                    <?php } ?>
    <?php
    foreach ($job_master_receiv_amount as $rakey): $ttl = $ttl + $rakey["amount"];
        if ($rakey["amount"] != '') {
            ?>
                                            <tr>
                                                <td><?= $rakey["createddate"] ?></td>
                                                <td>Rs.<?= $rakey["amount"] ?></td>
                                                <td><?= ucfirst($rakey["remark"]) ?></td>
                                                <td><?php if ($rakey["type"] == "User Pay") { ?>PayUmoney<?php } ?><?= ucfirst($rakey["payment_type"]) ?></td>
                                                <td><?php if ($rakey["type"] != "User Pay") { ?><?= ucfirst($rakey["name"]); ?><?php } ?></td>
                                                <td>
                                            <?php if ($rakey["type"] != "User Pay" && $login_data['type'] == 1 || $login_data['type'] == 2) { ?>
                                                        <a href="javascript:void(0);" onclick="delete_received_payment('<?= $rakey["id"] ?>', '<?= $rakey["amount"] ?>');"><i class="fa fa-trash"></i></a>
            <?php } ?>
                                                </td>
                                            </tr>
        <?php } endforeach; ?>
                                    <tr style="border-top:2px solid black">
                                        <td><b>Total Received Amount</b></td>
                                        <td colspan="4"><b>Rs.<?= $ttl; ?></b></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">

                            <!--                            <button type="button" class="btn btn-primary" id="amount_submit_btn" disabled="">Add</button>-->
                            <input type="submit" value="Add" class="btn btn-primary" id="amount_submit_btn"/>
                            <script>
                                function send_receiv_amount() {
                                    $("#amount_submit_btn").attr('disabled', 'disabled');
                                    //$("#payment_receiv_form_id").submit();
                                    document.getElementById("frm_sb").click();
                                    return true;
                                    //$("#frm_sb").click();
                                }
                            </script>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
    <?= form_close(); ?>
                    </div>

                </div>
            </div>



    <?php /* Nishit change payment status end */ ?>
    <?php /* Nishit change payment status start */ ?>
            <!-- Modal -->
            <div class="modal fade" id="family_model" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Change Family Member</h4>
                        </div>
    <?php echo form_open("job_master/change_family_member/" . $cid, array("method" => "POST", "role" => "form")); ?>
                        <div class="modal-body">
                                    <?php if (!empty($amount_history_success)) { ?>
                                <div class="widget">
                                    <div class="alert alert-success alert-dismissable">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">?</button>
                                <?php echo $amount_history_success['0']; ?>
                                    </div>
                                </div>
                                    <?php } ?>
                                    <?php if (!empty($family_error)) { ?>
                                <div class="widget">
                                    <div class="alert alert-danger alert-dismissable">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">?</button>
        <?php echo $family_error['0']; ?>
                                    </div>
                                </div>
                                    <?php } ?>
                            <div class="form-group">
                                <label for="recipient-name" class="control-label">Select:</label>
                                <select name="family_mem" class="form-control">
                                    <option value="0">Self</option>
                                    <?php foreach ($relation_list as $fkey) { ?>
                                        <option value="<?= $fkey["id"] ?>" <?php
                                                if ($booking_info[0]['family_member_fk'] == $fkey["id"]) {
                                                    echo "selected";
                                                }
                                                ?>><?= $fkey["name"] ?></option>
    <?php } ?>
                                </select>
                                <span style="color:red;" id="amount_error"></span>
                            </div>
                            <b>OR (Create new)</b>
                            <div class="form-group">
                                <label for="recipient-name" class="control-label">Name<i style="color:red;">*</i>:</label>
                                <input type="text" name="f_name" class="form-control" />
                                <span style="color:red;" id="amount_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="control-label">Relation<i style="color:red;">*</i>:</label>
                                <select name="family_relation" class="form-control" >
                                    <option value="">--Select--</option>
    <?php foreach ($relation1 as $fkey) { ?>
                                        <option value="<?= $fkey["id"] ?>"><?= $fkey["name"] ?></option>
    <?php } ?>
                                </select>
                                <span style="color:red;" id="amount_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="control-label">Date Of Birth<i style="color:red;">*</i>:</label>
                                <input type="text" name="f_dob" id='f_dob1' readonly="" class="form-control datepicker-input" />
                                OR<input type="text" class="form-control" style="width:20%" onkeyup="calculate_age1(this.value, 'f_dob1');"/>
                                <span style="color:red;" id="f_dob_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="control-label">Gender<i style="color:red;">*</i>:</label>
                                <select name="f_gender" class="form-control" id="f_gender" >
                                    <option value="male">Male</option>
                                    <option value="male">Female</option>
                                </select>
                                <span style="color:red;" id="f_gender_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="control-label">Phone:</label>
                                <input type="text" name="f_phone" class="form-control"/>
                                <span style="color:red;" id="amount_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="control-label">Email:</label>
                                <input type="text" name="f_email" class="form-control"/>
                                <span style="color:red;" id="amount_error"></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="amount_submit_btn">Change</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
    <?= form_close(); ?>
                    </div>

                </div>
            </div>

            <div class="modal fade" id="family_model1" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Update Family Member Info.</h4>
                        </div>
    <?php echo form_open("job_master/update_family_member/" . $family_data[0]["id"] . "/" . $cid, array("method" => "POST", "role" => "form")); ?>
                        <input type="hidden" name="uid" value="<?= $query[0]["custid"] ?>"/>
                        <div class="modal-body">
                                    <?php if (!empty($amount_history_success)) { ?>
                                <div class="widget">
                                    <div class="alert alert-success alert-dismissable">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">?</button>
                                <?php echo $amount_history_success['0']; ?>
                                    </div>
                                </div>
                                    <?php } ?>
                                    <?php if (!empty($family_error)) { ?>
                                <div class="widget">
                                    <div class="alert alert-danger alert-dismissable">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">?</button>
        <?php echo $family_error['0']; ?>
                                    </div>
                                </div>
    <?php } ?>

                            <div class="form-group">
                                <label for="recipient-name" class="control-label">Name<i style="color:red;">*</i>:</label>
                                <input type="text" name="f_name" class="form-control" value="<?= $family_data[0]["name"] ?>" required=""/>
                                <span style="color:red;" id="amount_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="control-label">Relation<i style="color:red;">*</i>:</label>
                                <select name="family_relation" class="form-control" required="">
                                    <option value="">--Select--</option>
                                    <?php foreach ($relation1 as $fkey) { ?>
                                        <option value="<?= $fkey["id"] ?>" <?php
                                                if ($family_data[0]["relation_fk"] == $fkey["id"]) {
                                                    echo "selected";
                                                }
                                                ?>><?= $fkey["name"] ?></option>
    <?php } ?>
                                </select>
                                <span style="color:red;" id="amount_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="control-label">Date Of Birth<i style="color:red;">*</i>:</label>
                                <input type="text" name="f_dob" id='f_dob3' readonly="" value="<?php
    $bdate = explode("-", $family_data[0]["dob"]);
    echo $bdate[2] . "/" . $bdate[1] . "/" . $bdate[0];
    ?>" class="form-control datepicker-input" required=""/>
                                OR<input type="text" class="form-control" style="width:20%" onkeyup="calculate_age1(this.value, 'f_dob3');"/>
                                <span style="color:red;" id="f_dob_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="control-label">Gender<i style="color:red;">*</i>:</label>
                                <select name="f_gender" class="form-control" id="f_gender" required="">
                                    <option value="male" <?php
    if ($family_data[0]["gender"] == 'male') {
        echo "selected";
    }
    ?>>Male</option>
                                    <option value="female" <?php
                                if ($family_data[0]["gender"] == 'female') {
                                    echo "selected";
                                }
                                ?>>Female</option>
                                </select>
                                <span style="color:red;" id="f_gender_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="control-label">Phone:</label>
                                <input type="text" name="f_phone" class="form-control" value="<?= $family_data[0]["phone"] ?>"/>
                                <span style="color:red;" id="amount_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="control-label">Email:</label>
                                <input type="text" name="f_email" class="form-control" value="<?= $family_data[0]["email"] ?>"/>
                                <span style="color:red;" id="amount_error"></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="amount_submit_btn">Update</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
    <?= form_close(); ?>
                    </div>

                </div>
            </div>
            <div class="modal fade" id="family_model12" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Update Patient Info.</h4>
                        </div>
    <?php echo form_open("job_master/update_user_info/" . $query[0]["custid"] . "/" . $cid, array("method" => "POST", "role" => "form")); ?>
                        <input type="hidden" name="uid" value="<?= $query[0]["custid"] ?>"/>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="recipient-name" class="control-label">Name<i style="color:red;">*</i>:</label>
                                <input type="text" name="f_name" class="form-control" value="<?= $customer_info[0]["full_name"] ?>" required=""/>
                                <span style="color:red;" id="amount_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="control-label">Date Of Birth<i style="color:red;">*</i>:</label>
                                <input type="text" name="f_dob" id='f_dob' readonly="" value="<?php
    $bdate = explode("-", $customer_info[0]["dob"]);
    echo $bdate[2] . "/" . $bdate[1] . "/" . $bdate[0];
    ?>" class="form-control datepicker-input" required=""/>
                                OR<input type="text" class="form-control" style="width:20%" onkeyup="calculate_age1(this.value, 'f_dob');"/>
                                <span style="color:red;" id="f_dob_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="control-label">Gender<i style="color:red;">*</i>:</label>
                                <select name="f_gender" class="form-control" id="f_gender" required="">
                                    <option value="male" <?php
    if ($customer_info[0]["gender"] == 'male') {
        echo "selected";
    }
    ?>>Male</option>
                                    <option value="female" <?php
                                if ($customer_info[0]["gender"] == 'female') {
                                    echo "selected";
                                }
                                ?>>Female</option>
                                </select>
                                <span style="color:red;" id="f_gender_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="control-label">Phone:</label>
                                <input type="text" name="f_phone" class="form-control" value="<?= $customer_info[0]["mobile"] ?>"/>
                                <span style="color:red;" id="amount_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="control-label">Email:</label>
                                <input type="text" name="f_email" class="form-control" value="<?= $customer_info[0]["email"] ?>"/>
                                <span style="color:red;" id="amount_error"></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="amount_submit_btn">Update</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
            <?= form_close(); ?>
                    </div>

                </div>
            </div>
    <?php /* Nishit doctor model start */ ?>
            <div class="modal fade" id="doctor_model" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Change Doctor</h4>
                        </div>
    <?php echo form_open("job_master/change_doctor/" . $cid, array("method" => "POST", "role" => "form")); ?>
                        <div class="modal-body">
                                    <?php if (!empty($amount_history_success)) { ?>
                                <div class="widget">
                                    <div class="alert alert-success alert-dismissable">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">?</button>
                                <?php echo $amount_history_success['0']; ?>
                                    </div>
                                </div>
                                    <?php } ?>
                                    <?php if (!empty($family_error)) { ?>
                                <div class="widget">
                                    <div class="alert alert-danger alert-dismissable">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">?</button>
        <?php echo $family_error['0']; ?>
                                    </div>
                                </div>
    <?php } ?>
                            <div class="form-group">
                                <label for="recipient-name" class="control-label">Select Doctor:</label>
                                <select name="referral_by" id="referral_by" class="form-control chosen" style="width:100%;" required="">

                                </select>

                                <span style="color:red;" id="amount_error"></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="amount_submit_btn">Update</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
    <?= form_close(); ?>
                    </div>

                </div>
            </div>
            <div class="modal fade" id="notify_model" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Change Notify</h4>
                        </div>
    <?php echo form_open("job_master/change_notify/" . $cid, array("method" => "POST", "role" => "form")); ?>
                        <div class="modal-body">
                                    <?php if (!empty($amount_history_success)) { ?>
                                <div class="widget">
                                    <div class="alert alert-success alert-dismissable">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">?</button>
                                <?php echo $amount_history_success['0']; ?>
                                    </div>
                                </div>
                                    <?php } ?>
                                    <?php if (!empty($family_error)) { ?>
                                <div class="widget">
                                    <div class="alert alert-danger alert-dismissable">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">?</button>
        <?php echo $family_error['0']; ?>
                                    </div>
                                </div>
    <?php } ?>
                            <div class="form-group">
                                <label for="recipient-name" class="control-label">Select:</label>
                                <select name="notify" class="form-control chosen" style="width:100%;" required="">
                                    <option value="">--Select--</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>

                                <span style="color:red;" id="amount_error"></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="amount_submit_btn">Update</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
    <?= form_close(); ?>
                    </div>

                </div>
            </div>
            <div class="modal fade" id="colection_charge_model" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Change Collection Charge</h4>
                        </div>
    <?php echo form_open("job_master/change_cc/" . $cid, array("method" => "POST", "role" => "form")); ?>
                        <div class="modal-body">
                                    <?php if (!empty($amount_history_success)) { ?>
                                <div class="widget">
                                    <div class="alert alert-success alert-dismissable">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">?</button>
                                <?php echo $amount_history_success['0']; ?>
                                    </div>
                                </div>
                                    <?php } ?>
                                    <?php if (!empty($family_error)) { ?>
                                <div class="widget">
                                    <div class="alert alert-danger alert-dismissable">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">?</button>
        <?php echo $family_error['0']; ?>
                                    </div>
                                </div>
    <?php } ?>
                            <div class="form-group">
                                <label for="recipient-name" class="control-label">Select:</label>
                                <select name="notify" class="form-control chosen" style="width:100%;" required="">
                                    <option value="">--Select--</option>
                                    <option value="1" <?php
    if ($query[0]["collection_charge"] == 1) {
        echo " selected";
    }
    ?>>Yes</option>
                                    <option value="0" <?php
                                if ($query[0]["collection_charge"] == 0) {
                                    echo " selected";
                                }
                                ?>>No</option>
                                </select>
                                <span style="color:red;" id="amount_error"></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="amount_submit_btn">Update</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
            <?= form_close(); ?>
                    </div>

                </div>
            </div>
    <?php /* Nishit doctor model end */ ?> 
    <?php /* Hiten Branch model start */ ?>
            <div class="modal fade" id="branch_model" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Change Branch</h4>
                        </div>
    <?php echo form_open("job_master/change_branch/" . $cid, array("method" => "POST", "role" => "form")); ?>
                        <div class="modal-body">
                                    <?php if (!empty($amount_history_success)) { ?>
                                <div class="widget">
                                    <div class="alert alert-success alert-dismissable">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">?</button>
                                <?php echo $amount_history_success['0']; ?>
                                    </div>
                                </div>
                                    <?php } ?>
                                    <?php if (!empty($family_error)) { ?>
                                <div class="widget">
                                    <div class="alert alert-danger alert-dismissable">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">?</button>
        <?php echo $family_error['0']; ?>
                                    </div>
                                </div>
    <?php } ?>
                            <div class="form-group">
                                <label for="recipient-name" class="control-label">Select Branch:</label>

                                <select id="branch" name="branch" class="chosen" style="width: 100%" required="">
                                    <option value="">--Select--</option>
                                    <?php foreach ($branch_list as $branch) {
                                        ?>
                                        <option value="<?php echo $branch['id']; ?>"><?php echo ucwords($branch['branch_name']) . " - " . $branch['branch_code']; ?></option>

        <?php
    }
    ?>
                                </select>
                                <span style="color:red;" id="amount_error"></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="amount_submit_btn">Update</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
    <?= form_close(); ?>
                    </div>

                </div>
            </div>

            <div class="modal fade" id="creditor_model" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Change Creditors</h4>
                        </div>
    <?php echo form_open("job_master/change_credential/" . $cid, array("method" => "POST", "role" => "form")); ?>
                        <div class="modal-body">
                            <div id="creditor_div">
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">Select Creditors:</label>
                                    <select id="creditor_id" name="creditor" class="chosen" style="width: 100%" required="">
                                        <option value="">--Select--</option>
                                        <?php foreach ($creditors as $branch) {
                                            ?>
                                            <option value="<?php echo $branch['id']; ?>" <?php
                                            if ($selected_creditor[0]["creditors_fk"] == $branch["id"]) {
                                                echo "selected";
                                            }
                                            ?>><?php echo ucwords($branch['name']) . " - " . $branch['mobile']; ?></option>

        <?php
    }
    ?>
                                    </select>
                                    <span style="color:red;" id="creditor_error"></span>
                                </div>
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">Amount:</label>
                                    <input type="number" name="creditor_amount" id="creditor_amount" class="form-control" value="<?php echo $selected_creditor[0]["amount"]; ?>">
                                    <span style="color:red;" id="creditor_amount_error"></span>
                                </div>
                            </div>
                            <div id="otp_creditor_div" style="display:none;">
                                <div class="form-group has-feedback">
                                    <div class="input-group">
                                        <span class="input-group-addon" style=""><i class="fa fa-key"></i></span>
                                        <input type="text"  placeholder="OTP" id="otp_val" class="form-control" name="otp">
                                    </div>
                                    <span style="color:red;" id="otp_error"></span>
                                    <span style="color:green;" id="otp_success"></span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="col-xs-8">    
                                <span id="loader_creditor_div" style="display:none;"><img src="<?= base_url(); ?>upload/opc-ajax-loader.gif" style="height:28px;margin-left:10px"></span>
                                <a href="javascript:void(0);" id="resend_opt" style="display:none;" onclick="credit_resend();">Resend OTP</a>
                                <a href="javascript:void(0);" id="mycounter" style=""></a>
                            </div><!-- /.col -->
                            <div class="col-xs-4">
                                <button type="button" class="btn btn-primary" id="creditor_amount_submit_btn" onclick="creditor_otp();" <?php
    if ($selected_creditor[0]["creditors_fk"] != '') {
        echo "disabled";
    }
    ?>>Update</button>
                                <button type="button" class="btn btn-primary" id="creditor_amount_submit_btn_otp" style="display:none;" onclick="creditor_otp_check();">Update</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
    <?= form_close(); ?>
                    </div>

                    <script>
                        function countdown(elementName, minutes, seconds)
                        {
                            var element, endTime, hours, mins, msLeft, time;

                            function twoDigits(n)
                            {
                                return (n <= 9 ? "0" + n : n);
                            }

                            function updateTimer()
                            {
                                msLeft = endTime - (+new Date);
                                if (msLeft < 1000) {
                                    //element.innerHTML = "countdown's over!";
                                    $("#mycounter").attr("style", "display:none;");
                                    $("#resend_opt").attr("style", "");
                                    $("#resend_opt").attr("style", "margin-bottom:10px");
                                    element.innerHTML = '';
                                } else {
                                    $("#resend_opt").attr("style", "display:none;");
                                    time = new Date(msLeft);
                                    hours = time.getUTCHours();
                                    mins = time.getUTCMinutes();
                                    element.innerHTML = 'Resend Code after <b>' + (hours ? hours + ':' + twoDigits(mins) : mins) + ':' + twoDigits(time.getUTCSeconds()) + ' </b> second';
                                    setTimeout(updateTimer, time.getUTCMilliseconds() + 500);
                                }
                            }
                            element = document.getElementById(elementName);
                            endTime = (+new Date) + 1000 * (60 * minutes + seconds) + 500;
                            updateTimer();
                        }
                        $("#creditor_amount").keyup(function () {
                            $("#creditor_amount_error").html("");
                            var val = parseInt(this.value);
                            var ttl_amt = '<?= $query[0]['payable_amount']; ?>';
                            ttl_amt = parseInt(ttl_amt);
                            if (val != null) {
                                console.log("ttl" + ttl_amt + " input" + val);
                                if (ttl_amt >= val) {
                                    $("#creditor_amount_submit_btn").removeAttr("disabled");
                                } else {
                                    $("#creditor_amount_submit_btn").attr("disabled", "");
                                    $("#creditor_amount_error").html("Given amount is more than payable amount.");
                                }
                            } else {
                                $("#creditor_amount_submit_btn").attr("disabled", "");
                                $("#creditor_amount_error").html("Invalid amount.");
                            }
                        });
                        function creditor_otp() {
                            var creditor_fk = $("#creditor_id").val();
                            var amount = $("#creditor_amount").val();
                            var job_fk = "<?php echo $cid; ?>";
                            $("#creditor_error").html("");
                            $("#creditor_amount_error").html("");
                            var temp = 1;
                            if (creditor_fk == "") {
                                $("#creditor_error").html("Please select Creditors.");
                                temp = 0;
                            }
                            if (amount == "") {
                                $("#creditor_amount_error").html("Add Amount.");
                                temp = 0;
                            }
                            if (temp == 1) {
                                $.ajax({
                                    url: "<?php echo base_url(); ?>job_master/creditors_add",
                                    type: 'post',
                                    data: {creditor_id: creditor_fk, amount: amount, job_id: job_fk},
                                    beforeSend: function () {
                                        $("#loader_creditor_div").attr("style", "");
                                        $("#creditor_amount_submit_btn").attr("disabled", "disabled");
                                    },
                                    success: function (data) {
                                        countdown("mycounter", 1, 0);
                                        $("#creditor_div").attr("style", "display:none;");
                                        $("#creditor_amount_submit_btn").attr("style", "display:none;");
                                        $("#otp_creditor_div").attr("style", "");
                                        $("#mycounter").attr("style", "");
                                        $("#resend_opt").attr("style", "");
                                        $("#creditor_amount_submit_btn_otp").attr("style", "");
                                    }, complete: function () {
                                        $("#loader_creditor_div").attr("style", "display:none;");
                                    }
                                });
                            }
                        }
                        function credit_resend() {
                            var creditor_fk = $("#creditor_id").val();
                            var amount = $("#creditor_amount").val();
                            var job_fk = "<?php echo $cid; ?>";
                            $.ajax({
                                url: "<?php echo base_url(); ?>job_master/creditors_resend",
                                type: 'post',
                                data: {creditor_id: creditor_fk, amount: amount, job_id: job_fk},
                                beforeSend: function () {
                                    $("#loader_creditor_div").attr("style", "");
                                    $("#creditor_amount_submit_btn").attr("disabled", "disabled");
                                },
                                success: function (data) {
                                    countdown("mycounter", 1, 0);
                                    $("#creditor_div").attr("style", "display:none;");
                                    $("#creditor_amount_submit_btn").attr("style", "display:none;");
                                    $("#otp_creditor_div").attr("style", "");
                                    $("#mycounter").attr("style", "");
                                    $("#resend_opt").attr("style", "");
                                    $("#creditor_amount_submit_btn_otp").attr("style", "");
                                }, complete: function () {
                                    $("#loader_creditor_div").attr("style", "display:none;");
                                }
                            });
                        }
                        function creditor_otp_check() {
                            $("#otp_success").html("");
                            $("#otp_error").html("");
                            $("#creditor_amount_submit_btn_otp").prop("disabled", true);
                            var otp = $("#otp_val").val();
                            var job = "<?php echo $cid; ?>";
                            var creditor_fk = $("#creditor_id").val();
                            var amount = $("#creditor_amount").val();
                            if (otp.trim() != '') {
                                $.ajax({
                                    url: '<?= base_url(); ?>job_master/creditor_check_otp',
                                    type: 'post',
                                    data: {otp: otp, job: job, creditor_fk: creditor_fk, amount: amount},
                                    success: function (data) {
                                        var jsondata = JSON.parse(data);
                                        if (jsondata.status == 0) {
                                            $("#creditor_amount_submit_btn_otp").prop("disabled", false);
                                            $("#otp_error").html(jsondata.msg);
                                        }
                                        if (jsondata.status == 1) {
                                            $("#otp_success").html(jsondata.msg);
                                            setTimeout(function () {
                                                window.location.reload();
                                            }, 1000);
                                        }
                                    },
                                    error: function (jqXhr) {
                                        $("#creditor_amount_submit_btn_otp").prop("disabled", false);
                                        alert('Oops somthing wrong Tryagain!.');
                                    }
                                });
                            } else {
                                $("#creditor_amount_submit_btn_otp").prop("disabled", false);
                                $("#otp_error").html("OTP field is required.");
                            }
                        }
                    </script>

                </div>
            </div>

            <div class="modal fade" id="barcode_model" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Change Barcode</h4>
                        </div>
    <?php echo form_open("job_master/change_barcode/" . $cid, array("method" => "POST", "role" => "form")); ?>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="recipient-name" class="control-label">Barcode:</label>
                                <input type="text" name="barcode" value="<?php echo $query[0]['barcode']; ?>" class="form-control"/>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="amount_submit_btn">Update</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
    <?= form_close(); ?>
                    </div>

                </div>
            </div>
            <div class="modal fade" id="report_status_modal" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Change Report Status</h4>
                        </div>
    <?php echo form_open("job_master/change_report_status/" . $cid, array("method" => "POST", "role" => "form")); ?>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="recipient-name" class="control-label">Select Status:</label>
                                <select class="form-control" name="report_status" style="width: 100%" required="">
                                    <option value="">--Select--</option>
                                    <option value="1" <?php
    if ($query[0]["report_status"] == 1) {
        echo "selected";
    }
    ?>>Critical</option>
                                    <option value="2" <?php
                                if ($query[0]["report_status"] == 2) {
                                    echo "selected";
                                }
    ?>>Semi-Critical</option>
                                    <option value="3" <?php
                                if ($query[0]["report_status"] == 3) {
                                    echo "selected";
                                }
                                ?>>Normal</option>
                                </select>
                                <span style="color:red;" id="amount_error"></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="amount_submit_btn">Update</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
    <?= form_close(); ?>
                    </div>

                </div>
            </div>
            <div class="modal fade" id="payment_model1" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Change Payment Via</h4>
                        </div>
    <?php echo form_open("job_master/change_payment_type/" . $cid, array("method" => "POST", "role" => "form")); ?>
                        <div class="modal-body">
                                    <?php if (!empty($amount_history_success)) { ?>
                                <div class="widget">
                                    <div class="alert alert-success alert-dismissable">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">?</button>
                                <?php echo $amount_history_success['0']; ?>
                                    </div>
                                </div>
                                    <?php } ?>
                                    <?php if (!empty($family_error)) { ?>
                                <div class="widget">
                                    <div class="alert alert-danger alert-dismissable">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">?</button>
        <?php echo $family_error['0']; ?>
                                    </div>
                                </div>
    <?php } ?>
                            <div class="form-group">
                                <label for="recipient-name" class="control-label">Select Type:</label>

                                <select id="branch" name="p_type" class="form-control" style="width: 100%" required="">
                                    <option value="">--Select--</option>
                                    <?php foreach ($payment_type_list as $key) {
                                        ?>
                                        <option value="<?php echo $key['id']; ?>" <?php
                                        if ($key["id"] == $query[0]["payment_type_fk"]) {
                                            echo "selected";
                                        }
                                        ?>><?php echo ucwords($key['name']); ?></option>

        <?php
    }
    ?>
                                </select>
                                <span style="color:red;" id="amount_error"></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="amount_submit_btn">Update</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
    <?= form_close(); ?>
                    </div>

                </div>
            </div>

    <?php /* Hiten branch model end */ ?>
            <div class="modal fade" id="discount_model" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Discount</h4>
                        </div>
    <?php echo form_open("job_master/discount_update/" . $cid, array("method" => "POST", "role" => "form", "id" => "payment_receiv_form_id")); ?>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="recipient-name" class="control-label">Discount (%):</label>
                                <input type="text" class="form-control" id="discount_amount_per" onkeyup="calculate_discount('1');" value="<?= $query[0]["discount"] ?>" name="discount_per" required="">
                            </div>
                            OR
                            <div class="form-group">
                                <label for="recipient-name" class="control-label">Discount (flat):</label>
                                <input type="text" class="form-control" id="discount_amount_flat" onkeyup="calculate_discount('2');" name="discount_flat" required="">
                            </div>
                            <input type="hidden" name="price" value="<?= $query[0]['price']; ?>"/>
                            <input type="hidden" name="payable_price" value="<?= $query[0]['payable_amount']; ?>"/>
                            <input type="hidden" name="old_discount" value="<?= $query[0]["discount"] ?>"/>
                            <input type="hidden" name="dabited_from_wallet" value="<?= $cut_from_wallet ?>"/>
                            <div class="form-group">
                                <label for="recipient-name" class="control-label">Note:</label>
                                <textarea class="form-control" name="discount_note"><?= $query[0]["discount_note"] ?></textarea>
                            </div>
                            <span style="color:red;" id="discount_amount_error"></span>
                            <script>
                                function calculate_discount(val) {
                                    var total_price = <?= $query[0]['price']; ?>;
                                    var payable_price = <?= $j_payable_price ?>;
                                    $("#discount_submit_btn").attr("style", "display:none;");
                                    $("#discount_amount_error").html("");
                                    if (val == 1) {
                                        var discount_amount_per = $("#discount_amount_per").val();
                                        if (discount_amount_per != '') {
                                            var flat_price = 0;
                                            if (discount_amount_per != 0) {
                                                flat_price = total_price * discount_amount_per / 100;
                                            }
                                            if (payable_price >= flat_price) {
                                                $("#discount_amount_flat").val(Math.round(flat_price));
                                                $("#discount_submit_btn").attr("style", "");
                                            } else {
                                                $("#discount_amount_error").html("Discount price is more then discount.");
                                            }
                                        }
                                    }
                                    if (val == 2) {
                                        var discount_amount_flat = $("#discount_amount_flat").val();
                                        if (discount_amount_flat != '') {
                                            var per_price = 0;
                                            if (payable_price >= discount_amount_flat) {
                                                if (discount_amount_flat != 0) {
                                                    per_price = 100 * discount_amount_flat / total_price;
                                                }
                                                $("#discount_amount_per").val(parseFloat(per_price).toFixed(2));
                                                $("#discount_submit_btn").attr("style", "");
                                            } else {
                                                $("#discount_amount_error").html("Discount price is more then discount.");
                                            }
                                        }
                                    }
                                }
                            </script>
                        </div>
                        <div class="modal-footer">
                            <input type="submit" value="Update" class="btn btn-primary" style="display:none;" id="discount_submit_btn"/>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
    <?= form_close(); ?>
                    </div>

                </div>
            </div>
        </div>
        <div class="modal fade" id="sms_modal" role="dialog">
            <div class="modal-dialog modal-lg">

                <!-- Modal content-->
                <div class="modal-content">
    <?php echo form_open("job_master/send_report_sms1", array("method" => "post", "role" => "form")); ?>
                    <input type="hidden" id="job_fk" name="job_fk" value=""/>
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Send Report Via SMS</h4>
                    </div>
                    <div class="modal-body">
                        <div id="all_mobile_no">
                            <div class="form-group">
                                <input type="text" placeholder="Mobile No.1" id="mobile_no0" name="mobile_no[]" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <a href="javascript:void(0);" class="btn btn-sm btn-primary" onclick="append_num();"><i class="fa fa-plus"></i> Add</a>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" id="sms_report" rows="20" placeholder="SMS..." name="sms" required=""></textarea>
                        </div>
                        <div class="pull-right"><button type="submit" class="btn btn-primary" id="amount_submit_btn">Send</button></div><br>
                        <h3>Send SMS History</h3>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Mobile No</th>
                                    <th style="width:70%">SMS</th>
                                    <th>Send By</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody id="result_sms_history">
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
    <?= form_close(); ?>
                </div>

            </div>
        </div>
        <div class="modal fade" id="mail_modal" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
    <?php echo form_open("job_master/send_report_email1", array("method" => "post", "role" => "form")); ?>
                    <input type="hidden" id="job_fk1" name="job_fk" value=""/>
                    <input type="hidden" id="cust_fk1" name="cust_fk" value=""/>
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Send Report Via Mail</h4>
                    </div>
                    <div class="modal-body">
                        <div id="all_mobile_no1">
                            <div class="form-group">
                                <input type="email" placeholder="Email-1" id="email_no0" name="email[]" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <a href="javascript:void(0);" class="btn btn-sm btn-primary" onclick="append_num1();"><i class="fa fa-plus"></i> Add</a>
                        </div>
                        <div class="pull-right"><button type="submit" class="btn btn-primary" id="amount_submit_btn">Send</button></div><br>
                        <h3>Send Email History</h3>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Email</th>
                                    <th>Send By</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody id="result_email_history">
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
        <?= form_close(); ?>
                </div>

            </div>
        </div>
        <?php /* Nishit change payment status end */ ?>
        <?php /* <div class="col-md-12">
          <div class="box box-primary">
          <div class="box-header">
          <!-- form start -->
          <h3 class="box-title">Add Result</h3>
          </div>
          <div class="box-body">
          <div class="alert alert-danger alert-dismissable" id="msg_cancel" style="display:none;">
          <button aria-hidden="true" data-dismiss="alert" class="close" type="button">?</button>
          Results Not Add
          </div>
          <div class="alert alert-success alert-dismissable" id="msg_success" style="display:none;">
          <button aria-hidden="true" data-dismiss="alert" class="close" type="button">?</button>
          Results Add Successfully
          </div>
          <div class="col-sm-12">
          <?php
          $ts = explode('#', $query[0]['testname']);
          $tid = explode(",", $query[0]['testid']);
          $cnt=0;
          foreach ($tid as $testidp) {
          if($parameter_list[$cnt][0]['test_fk'] == $testidp) {
          ?>
          <h3>Add Result For <?php echo ucfirst($ts[$cnt]); ?></h3>
          <table id="example4" class="table table-bordered table-striped">
          <thead>
          <tr>
          <th>Parameter Name</th>
          <th>Subparameter Name</th>
          <th>Value</th>
          <th>Parameter Value</th>
          <th>Parameter Unit</th>
          <input type="hidden" id="para_job_id" value="<?php echo $cid; ?>">
          </tr>
          </thead>
          <?php } $cn=1;
          foreach($parameter_list[$cnt] as $parameter) {
          if($parameter['test_fk'] == $testidp) {
          if($parameter['pid'] == $parameter['parameter_fk']) {
          ?>
          <tbody>
          <td><?php echo $parameter['parameter_name']; ?></td>
          <td><?php echo $parameter['subparameter_name']; ?></td>
          <td>
          <input type="text" id="value_add_<?php echo $cnt; ?>_<?php echo $cn; ?>" class="form-control">
          <input type="hidden" id="para_id_<?php echo $cnt; ?>_<?php echo $cn; ?>" value="<?php echo $parameter['pid']; ?>">
          <input type="hidden" id="subpara_id_<?php echo $cnt; ?>_<?php echo $cn; ?>" value="<?php echo $parameter['gid']; ?>">
          </td>
          <td><?php echo $parameter['subparameter_range']; ?></td>
          <td><?php echo $parameter['subparameter_unit']; ?></td>
          <?php } else { ?>
          <tbody>
          <td><?php echo $parameter['parameter_name']; ?></td>
          <td> - </td>
          <td>
          <input type="text" id="value_add_<?php echo $cnt; ?>_<?php echo $cn; ?>" class="form-control">
          <input type="hidden" id="para_id_<?php echo $cnt; ?>_<?php echo $cn; ?>" value="<?php echo $parameter['pid']; ?>">
          </td>
          <td><?php echo $parameter['parameter_range']; ?></td>
          <td><?php echo $parameter['parameter_unit']; ?></td>
          <?php
          }
          }
          $cn++;
          ?>
          <input type="hidden" id="para_count_<?php echo $cnt; ?>" value="<?php echo $cn; ?>">
          <input type="hidden" id="test_count" value="<?php echo $cnt; ?>">
          <input type="hidden" id="test_id_<?php echo $cnt; ?>" value="<?php echo $testidp; ?>">
          <?php
          }$cnt++; ?>
          </tbody>
          </table>
          <?php
          }
          ?>
          </div>
          </div>
          <div class="box-footer">
          <span id="loader_div" style="display:none;"><img src="<?= base_url(); ?>upload/opc-ajax-loader.gif" style="height:28px;margin-left:10px"></span>
          <input onclick="add_results();" id="add_result" style="float:right;" class="btn btn-primary" value="Add" type="button">
          </div>
          </div>
          </div> */ ?>
    </section>
    <script src="<?php echo base_url(); ?>plugins/timepick/js/timepicki.js"></script>
    <script>
                                $('#sharp_time').timepicki({
                                    min_hour_value: 0,
                                    step_size_minutes: 1,
                                    overflow_minutes: true,
                                    increase_direction: 'up',
                                    disable_keyboard_mobile: true});
    </script>
    <script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
    <script  type="text/javascript">
                                $(function () {
                                    $('.chosen').chosen();
                                });

    </script> 
    <script>
        function sms_popup(jid) {
            $("#result_sms_history").empty();
            $.ajax({
                url: '<?php echo base_url(); ?>add_result/send_result/' + jid,
                beforeSend: function () {
                    $("#loader_div").attr("style", "");
                },
                success: function (data) {
                    var json_data = JSON.parse(data);
                    if (json_data.status == 1) {
                        $("#sms_report").val(json_data.sms);
                        for (var i = 0; json_data.mobile.length > i; i++) {
                            $("#mobile_no" + i).val(json_data.mobile[i]);
                        }
                        for (var j = 0; json_data.history.length > j; j++) {
                            var numbr = json_data.history[j].mobile;
                            var new_no = numbr.split(",");
                            var txt = '';
                            for (var nbr = 0; new_no.length > nbr; nbr++) {
                                txt += new_no[nbr] + '<br/>';
                            }
                            $("#result_sms_history").append('<tr> <td>' + txt + '</td> <td><span class="more">' + json_data.history[j].sms + '</span></td> <td>' + json_data.history[j].name + '</td> <td>' + json_data.history[j].created_date + '</td></tr>');
                        }
                        if (json_data.history.length == 0) {
                            $("#result_sms_history").append('<tr> <td colspan="4"><center>Data not available.</center></td></tr>');
                        }
                        more_less();
                        $("#job_fk").val(jid);
                        $("#sms_modal").modal("show");
                    } else {
                        alert("Data not available.");
                        $("#job_fk").val("");
                    }
                },
                error: function (jqXhr) {
                    alert("Try again.");
                },
                complete: function () {
                    $("#loader_div").attr("style", "display:none;");
                },
            });
        }
        function mail_popup(jid, cid) {
            $("#result_email_history").empty();
            $.ajax({
                url: '<?php echo base_url(); ?>job_master/get_user_info/' + cid + "/" + jid,
                beforeSend: function () {
                    $("#loader_div").attr("style", "");
                },
                success: function (data) {
                    var json_data = JSON.parse(data);
                    if (json_data.status == 1) {
                        $("#email_no0").val(json_data.data[0].email);
                        for (var j = 0; json_data.history.length > j; j++) {
                            var numbr = json_data.history[j].email;
                            var new_no = numbr.split(",");
                            var txt = '';
                            for (var nbr = 0; new_no.length > nbr; nbr++) {
                                txt += new_no[nbr] + '<br/>';
                            }
                            $("#result_email_history").append('<tr> <td>' + txt + '</td> <td>' + json_data.history[j].name + '</td> <td>' + json_data.history[j].created_date + '</td></tr>');
                        }
                        if (json_data.history.length == 0) {
                            $("#result_email_history").append('<tr> <td colspan="3"><center>Data not available.</center></td></tr>');
                        }
                        $("#job_fk1").val(jid);
                        $("#cust_fk1").val(cid);
                        $("#mail_modal").modal("show");
                    } else {
                        alert("Data not available.");
                        $("#job_fk1").val("");
                    }
                },
                error: function (jqXhr) {
                    alert("Try again.");
                },
                complete: function () {
                    $("#loader_div").attr("style", "display:none;");
                },
            });
        }
        $sms_cnt = 1;
        $num_cnt = 2;
        function append_num() {
            $("#all_mobile_no").append('<div class="form-group" id="add_num_' + $sms_cnt + '" style="width:93%;height:40px;"><input type="text" placeholder="Mobile No.' + $num_cnt + '" id="mobile_no' + $sms_cnt + '" name="mobile_no[]" class="form-control" style="width:94%;float:left;margin-right:16px"/><a href="javascript:void(0);" onclick="add_num_' + $sms_cnt + '.remove();$sms_cnt--;$num_cnt--;"><i class="fa fa-trash"></i></a></div>');
            $sms_cnt++;
            $num_cnt++;
        }
        $sms_cnt1 = 1;
        $num_cnt1 = 2;
        function append_num1() {
            $("#all_mobile_no1").append('<div class="form-group" id="add_num1_' + $sms_cnt1 + '" style="width:93%;height:40px;"><input type="email" placeholder="Email-' + $num_cnt1 + '" id="mobile_no1' + $sms_cnt1 + '" name="email[]" class="form-control" style="width:94%;float:left;margin-right:16px"/><a href="javascript:void(0);" onclick="add_num1_' + $sms_cnt1 + '.remove();$sms_cnt1--;$num_cnt1--;"><i class="fa fa-trash"></i></a></div>');
            $sms_cnt1++;
            $num_cnt1++;
        }
        function more_less() {
            var showChar = 100;  // How many characters are shown by default
            var ellipsestext = "...";
            var moretext = "Show more";
            var lesstext = "Show less";


            $('.more').each(function () {
                var content = $(this).html();

                if (content.length > showChar) {

                    var c = content.substr(0, showChar);
                    var h = content.substr(showChar, content.length - showChar);

                    var html = c + '<span class="moreellipses">' + ellipsestext + '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';

                    $(this).html(html);
                }

            });

            $(".morelink").click(function () {
                if ($(this).hasClass("less")) {
                    $(this).removeClass("less");
                    $(this).html(moretext);
                } else {
                    $(this).addClass("less");
                    $(this).html(lesstext);
                }
                $(this).parent().prev().toggle();
                $(this).prev().toggle();
                return false;
            });
        }
        function add_results() {
            var test_count = $('#test_count').val();
            var para_job_id = $('#para_job_id').val();
            var i;
            var j;
            var data = [];
            for (i = 0; i <= test_count; i++) {
                var para_count = $('#para_count_' + i).val();
                var test_id = $('#test_id_' + i).val();
                var data2 = [];
                for (j = 1; j < para_count; j++) {
                    var value_add = $('#value_add_' + i + '_' + j).val();
                    var para_id = $('#para_id_' + i + '_' + j).val();
                    var subpara_id = $('#subpara_id_' + i + '_' + j).val();
                    var myObject = [];
                    myObject['job_id'] = para_job_id;
                    myObject['test_id'] = test_id;
                    myObject['value'] = value_add;
                    myObject['parameter_id'] = para_id;
                    myObject['subpar_id'] = subpara_id;
                    alert(myObject);
                    data2.push(myObject);
                }
            }
            data.push(data2);
            console.log(data);
            return false;
        }
        function assign_phlebo() {
            $("#assign_phlebo_error").html("");
            var phlebo = $("#phlebo").val();
            var adate = $("#date").val();
            var atime = $("#time_slot").val();
            if (atime == null) {
                atime = '';
            }
            var address = $("#address").val();
            var p_note = $("#p_note").val();
            var sharp_time = $("#sharp_time").val();
            if ($('#notify').prop('checked')) {
                var notify = 1;
            } else {
                var notify = 0;
            }
            var as_job_fk = $("#as_job_fk").val();
            if (phlebo.trim() != '' && adate.trim() != '' && address.trim() != '') {
                if (atime.trim() != '' || sharp_time.trim() != '') {
                    $.ajax({
                        url: "<?php echo base_url(); ?>job_master/assign_phlebo_job",
                        type: 'post',
                        data: {phlebo_id: phlebo, job_id: as_job_fk, dated: adate, timed: atime, address: address, notify: notify, sharp_time: sharp_time, note: p_note},
                        beforeSend: function () {
                            $("#loader_div").attr("style", "");
                            $("#send_btn").attr("disabled", "disabled");
                        },
                        success: function (data) {
                            if (data == 1) {
                                $("#msg_success").show();
                            } else {
                                $("#msg_cancel").show();
                            }
                            window.location.reload();
                        }, complete: function () {
                            $("#loader_div").attr("style", "display:none;");
                            $("#send_btn").removeAttr("disabled");
                            get_phlebo_schedule();
                        }
                    });
                } else {
                    $("#assign_phlebo_error").html("* fields are required.");
                }
            } else {
                $("#assign_phlebo_error").html("* fields are required.");
            }
        }
        function delete_received_payment(val, d_amount) {
            var cnfg = confirm('Are you sure?');
            if (cnfg == true) {
                var ttl_amount = $("#ttl_amount").val();
                $.ajax({
                    url: "<?php echo base_url(); ?>job_master/delete_assign_payment",
                    type: 'post',
                    data: {id: val, ttl_amount: ttl_amount, jid:<?= $cid; ?>, d_amount: d_amount},
                    beforeSend: function () {
                        $("#amount_error").html('<img src="<?= base_url(); ?>upload/opc-ajax-loader.gif" style="height:28px;margin-left:10px">');
                    },
                    success: function (data) {
                        if (data == 1) {
                            window.location.reload();
                        }
                        $("#amount_error").html('');
                    }, complete: function () {
                        $("#amount_error").html('');
                    }
                });
            }
        }
        function Validation() {
            var cnt = 0;
            var pm = 1;
            var count = $("#count").val();
            var common_report = $("#common_report").val();
            if (common_report == '') {
                for (cnt = 0; cnt < count; cnt++) {
                    $("#descerror_" + cnt).html('');
                    $("#reporterror_" + cnt).html('');
                    var desc = $("#desc_" + cnt).val();
                    var report = $("#report_" + cnt).val();
                    /*if (desc == ''){
                     pm=1;
                     //$("#descerror_"+cnt).html('Description is required.');
                     } else {
                     pm=0;
                     $("#descerror_"+cnt).html('');
                     }*/
                    if (report == '') {
                        pm = 1;
                        $("#reporterror_" + cnt).html('Report is required.');
                    } else {
                        pm = 0;
                        $("#reporterror_" + cnt).html('');
                    }
                }
            } else {
                pm = 0;
            }
            if (pm == 0) {
                $("#submit_report").submit();
            }
        }
        function change_status() {
            var status = $("#status").val();
            var job_id = $("#job_id").val();
            if (status == "") {
                alert("Please Select Status!");
            } else {
                if (status == "2") {
                    // if (parseInt($approved_test_length) != parseInt($approved_test_cnt)) {
                    //     alert("Please complete all tests.");
                    //     return false;
                    //}
                    var completed_tst = $("#upload_report").val();
                    if (completed_tst.trim() == '' || completed_tst.trim() == '0') {
                        alert("Please upload report.");
                        return false;
                    }
                    var txt;
                    var r = confirm("Are you sure want to complete job!");
                    if (r == true) {

                      /*  var completed_tst = $("#upload_report").val();
                        if (completed_tst.trim() == '' || completed_tst.trim() == '0') {
                            $("#descerror_1").html("<br>Please upload report.");
                            return false;
                        }*/
                        $.ajax({
                            url: "<?php echo base_url(); ?>job_master/changing_status_job",
                            type: 'post',
                            data: {status: status, jobid: job_id},
                            beforeSend: function () {
                                $("#change").attr("disabled", "disabled");
                                $("#loader_div1").attr("style", "");
                            },
                            success: function (data) {
                                if (data == 1) {
                                    //     console.log("data"+data);
                                    window.location = "<?php echo base_url(); ?>job-master/job-details/<?php echo $cid; ?>";
                                                                }
                                                            }, complete: function () {
                                                                //$("#change").removeAttr("disabled");
                                                                $("#loader_div1").attr("style", "display:none;");
                                                            }
                                                        });
                                                    } else {

                                                    }
                                                } else {
                                                    $.ajax({
                                                        url: "<?php echo base_url(); ?>job_master/changing_status_job",
                                                        type: 'post',
                                                        data: {status: status, jobid: job_id},
                                                        beforeSend: function () {
                                                            $("#change").attr("disabled", "disabled");
                                                            $("#loader_div1").attr("style", "");
                                                        },
                                                        success: function (data) {
                                                            if (data == 1) {
                                                                //     console.log("data"+data);
                                                                window.location = "<?php echo base_url(); ?>job-master/job-details/<?php echo $cid; ?>";
                                                                                        }
                                                                                    }, complete: function () {
                                                                                        //$("#change").removeAttr("disabled");
                                                                                        $("#loader_div1").attr("style", "display:none;");
                                                                                    }
                                                                                });
                                                                            }
                                                                        }
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
    <script>
        function get_time(val) {
            var time = $('#get_time1').val();

            $.ajax({
                url: '<?php echo base_url(); ?>phlebo-api_v4/get_phlebo_schedule',
                type: 'post',
                data: {bdate: val, city_id: '<?= $query[0]['test_city']; ?>'},
                success: function (data) {
                    var json_data = JSON.parse(data);
                    $('#time_slot').html("");
                    if (json_data.status == 1) {
                        for (var i = 0; i < json_data.data.length; i++) {
                            if (json_data.data[i].booking_status == 'Available') {
                                $('#time_slot').append('<option value="' + json_data.data[i].time_slot_fk + '" ' + (time == json_data.data[i].time_slot_fk ? 'selected' : '') + '>' + json_data.data[i].start_time + ' TO ' + json_data.data[i].end_time + ' (Available)</option>').trigger("chosen:updated");
                            } else {
                                $('#time_slot').append('<option disabled>' + json_data.data[i].start_time + ' TO ' + json_data.data[i].end_time + ' (Unavailable)</option>').trigger("chosen:updated");
                            }
                        }
                    } else {
                        if (json_data.error_msg == 'Time slot unavailable.') {
                            //$("#phlebo_shedule").empty();
                            //$("#phlebo_shedule").append('<div class="form-group"><label for="message-text" class="form-control-label">Request to consider as emergency:-</label><input type="checkbox" value="emergency" onclick="check_emergency(this);" value="emergency" id="as_emergency"></div>'+"<span style='color:red;'>" + json_data.error_msg + "</span>");
                        } else {
                            //$("#phlebo_shedule").html("<span style='color:red;'>" + json_data.error_msg + "</span>");
                        }
                    }
                    $('.chosen').trigger("chosen:updated");
                },
                error: function (jqXhr) {
                    $("#phlebo_shedule").html("");
                },
                complete: function () {
                    //$("#shedule_loader_div").attr("style", "display:none;");
                    //$("#send_opt_1").removeAttr("disabled");
                },
            });
        }
        var date = $('#date').val();
        get_time(date);
        setTimeout(function () {
            var job_id = $("#job_id").val();
            $.ajax({
                url: "<?php echo base_url(); ?>job_master/get_job_log/" + job_id,
                success: function (data) {
                    $("#job_tracking").html(data);
                }
            });
        }, 1000);</script>
    <script>
        $(document).ready(function () {
            var date_input = $('input[name="date"]'); //our date input has the name "date"

            var container = $('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";
            date_input.datepicker({
                format: 'yyyy-mm-dd',
                container: container,
                todayHighlight: true,
                autoclose: true,
            })
        })
    <?php if (!empty($amount_history_success)) { ?>
            setTimeout(function () {
                $('#payment_model').modal('show');
            }, 1000);
    <?php } ?>
    <?php if (!empty($family_error)) { ?>
            setTimeout(function () {
                $('#family_model').modal('show');
            }, 1000);
    <?php } ?>
        setTimeout(function () {
            if ("<?= $query[0]["test_city"] ?>" == "1") {
                testcity = 333;
            }
            if ("<?= $query[0]["test_city"] ?>" == "2") {
                testcity = 396;
            }
            if ("<?= $query[0]["test_city"] ?>" == "3") {
                testcity = 406;
            }
            if ("<?= $query[0]["test_city"] ?>" == "4") {
                testcity = 446;
            }
            if ("<?= $query[0]["test_city"] ?>" == "5") {
                testcity = 1510;
            }
            if ("<?= $query[0]["test_city"] ?>" == "6") {
                testcity = 345;
            }
            if ("<?= $query[0]["test_city"] ?>" == "8") {
                testcity = 476;
            }
            if ("<?= $query[0]["test_city"] ?>" == "7") {
                testcity = 1475;
            }
            $.ajax({
                url: '<?php echo base_url(); ?>Admin/get_refered_by',
                type: 'post',
                data: {val: testcity},
                success: function (data) {
                    var json_data = JSON.parse(data);
                    $("#referral_by").html(json_data.refer);
                    $('.chosen').trigger("chosen:updated");
                },
                error: function (jqXhr) {
                    $("#referral_by").html("");
                },
                complete: function () {
                },
            });
        }, 1000);
        function test_manage(jid) {
            jq.fancybox.open({
                href: '<?= base_url(); ?>job_master/manage_test/' + jid,
                type: 'iframe',
                padding: 5,
                width: '100%',
            });
        }
        function add_Result(jid) {
            jq.fancybox.open({
                href: '<?= base_url(); ?>add_result/test_details/' + jid,
                type: 'iframe',
                padding: 5,
                width: '100%',
            });
        }
        function approveTest(jid, tid) {
            jq.fancybox.open({
                href: '<?= base_url(); ?>add_result/test_approve_details/' + jid + '/' + tid,
                type: 'iframe',
                padding: 5,
                width: '100%',
            });
        }
        function approveAllTest(jid, tid) {
            jq.fancybox.open({
                href: '<?= base_url(); ?>add_result/all_test_approve_details?jid=' + jid + '&tid=' + tid,
                type: 'iframe',
                padding: 5,
                width: '100%',
            });
        }
        function close_popup() {
            jq.fancybox.close();
            setTimeout(function () {
                window.location.reload();
            }, 1000);
        }
        function close_popup1() {
            jq.fancybox.close();
            check_test_result();
        }
        calculate_discount('1');
        function get_phlebo_schedule() {
            var job_id = $("#job_id").val();
            $.ajax({
                url: '<?php echo base_url(); ?>job_master/get_job_phlebo_schedule/' + job_id,
                type: 'post',
                data: {val: 1},
                success: function (data) {
                    $("#phlebo_schedule").html(data);
                },
                error: function (jqXhr) {
                    //  $("#referral_by").html("");
                },
                complete: function () {
                },
            });
        }
        get_phlebo_schedule();
    </script>
    <script>
        function calculate_age1(val, id)
        {
            var today_date = '<?= date("Y-m-d"); ?>';
            var get_date_data = today_date.split("-");

            var new_date = get_date_data[0] - val;
            new_date = get_date_data[2] + "/" + get_date_data[1] + "/" + new_date;
            $("#" + id).val(new_date);
        }

    <?php if (trim($query[0]['note']) != '') { ?>
            //   alert("<?php // echo ucwords(trim($query[0]['note']));                    ?>");
    <?php } ?>
        $approved_test_cnt = 0;
        $approved_test_length = <?php echo count($test_list); ?>;
        /*Check test result start*/
        function check_test_result() {
            $approved_test_cnt = 0;
            $.ajax({
                url: '<?php echo base_url(); ?>job_master/check_test_parameter_val',
                type: 'post',
                data: {job_id: '<?php echo $cid; ?>'},
                success: function (data) {
                    var json_data = JSON.parse(data);
                    if (json_data.data) {
                        var job_data = json_data.data;
                        var add_result_array = [];
                        $.each(job_data, function (index, value) {
                            var job_fk = value.job_test;
                            if (value.details) {
                                $.each(value.details, function (index1, value1) {
                                    var tst_fk = value1.test_fk;
                                    var tst_parameter = value1.parameter;

                                    var tst_result = value1.result;
                                    /*if (tst_parameter.length <= tst_result.length && tst_parameter.length > 0) {*/
                                    if (tst_result.length > 0 && tst_parameter.length > 0) {
                                        if ($("#test_" + job_fk + "_" + tst_fk)) {
                                            add_result_array.push(tst_fk);
                                            $("#test_" + job_fk + "_" + tst_fk).attr("style", "background:#f7f574;");
                                            $("#btn_" + job_fk + "_" + tst_fk).attr("style", "");
                                        }
                                    }
                                    var tst_graph = value1.graph;
                                    if (tst_graph.length > 0) {
                                        if ($("#test_" + job_fk + "_" + tst_fk)) {
                                            add_result_array.push(tst_fk);
                                            $("#test_" + job_fk + "_" + tst_fk).attr("style", "background:#f7f574;");
                                            $("#btn_" + job_fk + "_" + tst_fk).attr("style", "");
                                        }
                                    }
									/*
                                    var tst_outsource_result = value1.outsource;
                                    if (tst_outsource_result.length > 0) {
                                        $.each(tst_outsource_result, function (index2, value2) {
                                            if ($("#test_" + value2.job_fk + "_" + value2.test_fk)) {
                                                $("#test_" + value2.job_fk + "_" + value2.test_fk).attr("style", "background:blue;color:white;");
                                            }
                                        });
                                    } 
									*/
                                    var tst_approve_result = value1.approve;
                                    if (tst_approve_result.length > 0) {
                                        $.each(tst_approve_result, function (index2, value2) {
                                            if ($("#test_" + value2.job_fk + "_" + value2.test_fk)) {
                                                $("#test_" + value2.job_fk + "_" + value2.test_fk).attr("style", "background:green;color:white;");
                                                $("#btn_" + job_fk + "_" + tst_fk).attr("style", "display:none;");
                                                $("#dbtn_" + job_fk + "_" + tst_fk).attr("style", "");
                                                removeA(add_result_array, tst_fk);
                                                $approved_test_cnt++;
                                            }
                                        });
                                    }
                                });
                            }
                        });
                        var selected_ids = JSON.parse(JSON.stringify(add_result_array));
                        $("#result_added_test").val(selected_ids);
                        if (selected_ids != '') {
                            $("#approved_all_btn").removeAttr("style");
                        } else {
                            $("#approved_all_btn").attr("style", "display:none;");
                        }
                    }
                },
                error: function (jqXhr) {
                    $("#branch").html("");
                },
                complete: function () {
                },
            });
        }
        function removeA(arr) {
            var what, a = arguments, L = a.length, ax;
            while (L > 1 && arr.length) {
                what = a[--L];
                while ((ax = arr.indexOf(what)) !== -1) {
                    arr.splice(ax, 1);
                }
            }
            return arr;
        }
        check_test_result();
        /*End*/
        function test_outsource(jid) {
            jq.fancybox.open({
                href: '<?= base_url(); ?>job_master/outsource_test/' + jid,
            type: 'iframe',
            padding: 5,
            width: '100%',
            afterClose: function () {
                return window.location.reload();
            }
        });
    }
</script>
