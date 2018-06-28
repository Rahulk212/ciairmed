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
<section class="content">
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header">
                    <!-- form start -->
                    <h3 class="box-title">Job Details</h3>
                </div>
                <div class="box-body">
                    <div class="col-sm-12">
                        <?php
                        if ($query[0]['packageid'] != NULL) {
                            $pid = explode('%', $query[0]['packageid']);
                            ?>
                            <div class="row">
                                <?php
                                //echo "<pre>";print_R($pid); print_R($package_price); print_r($query); die();
                                $p_cnt = 0;
                                foreach ($pid as $pkgid) {
                                    foreach ($package_price as $pack_prc) {
                                        if (empty($query[0]['test_city'])) {
                                            $query[0]['test_city'] = 1;
                                        }
                                        if ($pack_prc['package_fk'] == $pkgid && $pack_prc['city_fk'] == $query[0]['test_city']) {
                                            ?>
                                            <?php if ($query[0]['packagename'] != NULL) { ?>
                                                <div class="col-md-6" style="border-bottom:1px solid ;  margin-bottom: 10px;">
                                                    <?php
                                                    $pk = explode('@', $query[0]['packagename']);
                                                    //print_r($pk); die();
                                                    $cnt = 0;
                                                    //foreach ($pk as $pkage) {
                                                    ?>
                                                    <div class="form-group">
                                                        <label for="exampleInputFile">Packages Name :</label>
                                                        <?php echo ucwords($pk[$p_cnt]); ?>
                                                    </div>
                                                    <?php //}  ?>
                                                <?php } ?>
                                                <div class="form-group">
                                                    <label for="exampleInputFile">Actual Price :</label>
                                                    <?php echo "Rs." . $pack_prc['a_price']; ?>
                                                </div>
                                                <div class="form-group">
                                                    <label for="exampleInputFile">Discount Price :</label>
                                                    <?php echo "Rs." . $pack_prc['d_price']; ?>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    }
                                    $p_cnt++;
                                }
                                ?>
                            </div>
                        <?php } ?>
                        <form role="form" action="<?php echo base_url(); ?>job_master/upload_report/<?= $cid ?>" method="post" enctype="multipart/form-data" id="submit_report">
                            <?= validation_errors('<div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>', '</div>'); ?>
                            <div class="box-body">
                                <?php if (!empty($error)) { ?> 
                                    <div class="alert alert-danger alert-dismissable">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        <?php echo $error['0']; ?>
                                    </div>
                                <?php } ?>
                                <input type="hidden" name="testids" value="<?php echo $query[0]['testid']; ?>" />
                                <?php
                                $ts = explode('#', $query[0]['testname']);
                                $tid = explode(",", $query[0]['testid']);
                                $cnt = 0;
                                $completed_cnt = 0;
                                if ($query[0]['testname'] != '') {
                                    foreach ($ts as $key) {
                                        ?>
                                        <div class="form-group">
                                            <label for="exampleInputFile">Upload Reports For  <?php echo $key; ?></label><span style="color:red">*</span><br>	
                                            <input type="file" id="report_<?php echo $cnt; ?>" name="userfile[]" <?php if ($query[0]['status'] == "2") { ?> style="display:none;" <?php } ?>>
                                            <small>(Allow only .pdf file)</small><br>
                                            <?php if (!empty($report)) { ?>
                                                <a href="<?php echo base_url(); ?>upload/report/<?php echo$report[$cnt]['report']; ?>" target="_blank"> <?php echo $report[$cnt]['original']; ?> </a> &nbsp; <?php if ($query[0]['status'] != "2") { ?><a href="<?php echo base_url(); ?>job_master/remove_report/<?php echo $report[$cnt]['id']; ?>/<?php echo $report[$cnt]['job_fk']; ?>" onclick="return confirm('Are you sure you want to remove this data?');" style="color:red;"> X </a> <?php } ?><br>
                                                <?php
                                                $completed_cnt++;
                                            }
                                            ?>
                                            <input type="hidden" name="testtype[]" value="t" />
                                            <input type="hidden" name="testids[]" value="<?= $tid["$cnt"]; ?>"/>
                                        </div>
                                        <span style="color:red;" id="reporterror_<?php echo $cnt; ?>"></span>
                                        <div class="form-group">
                                            <label for="exampleInputFile">Description For  <?php echo $key; ?></label>
                                            <textarea id="desc_<?php echo $cnt; ?>" name="desc_<?php echo $cnt; ?>" class="form-control" <?php if ($query[0]['status'] == "2") { ?> disabled <?php } ?>><?php
                                                if (!empty($report)) {
                                                    echo $report[$cnt]['description'];
                                                }
                                                ?></textarea>
                                        </div>
                                        <span style="color:red;" id="descerror_<?php echo $cnt; ?>"></span>
                                        <hr/>
                                        <?php
                                        $cnt++;
                                    }
                                }
                                ?>
                                <?php
                                //echo "<pre>";print_R($pid); print_R($package_price); print_r($query); die();
                                $p_cnt = 0;
                                foreach ($pid as $pkgid) {
                                    foreach ($package_price as $pack_prc) {
                                        if ($pack_prc['package_fk'] == $pkgid && $pack_prc['city_fk'] == $query[0]['test_city']) {
                                            $pk = explode('@', $query[0]['packagename']);
                                            ?>
                                            <?php if ($query[0]['packagename'] != NULL) { ?>
                                                <div class="form-group">
                                                    <label for="exampleInputFile">Upload Reports For <?php echo ucwords($pk[$p_cnt]); ?></label><span style="color:red">*</span><br>	
                                                    <input type="file" id="report_<?php echo $cnt; ?>" name="userfile[]" <?php if ($query[0]['status'] == "2") { ?> style="display:none;" <?php } ?>>
                                                    <small>(Allow only .pdf file)</small><br>
                                                    <?php if (!empty($report)) { ?>
                                                        <a href="<?php echo base_url(); ?>upload/report/<?php echo$report[$cnt]['report']; ?>" target="_blank"> <?php echo $report[$cnt]['original']; ?> </a> &nbsp; <?php if ($query[0]['status'] != "2") { ?><a href="<?php echo base_url(); ?>job_master/remove_report/<?php echo $report[$cnt]['id']; ?>/<?php echo $report[$cnt]['job_fk']; ?>" onclick="return confirm('Are you sure you want to remove this data?');" style="color:red;"> X </a> <?php } ?><br>
                                                        <?php
                                                        $completed_cnt++;
                                                    }
                                                    ?>
                                                    <input type="hidden" name="testtype[]" value="p"/>
                                                    <input type="hidden" name="testids[]" value="<?= $pkgid; ?>"/>
                                                </div>
                                                <span style="color:red;" id="reporterror_<?php echo $cnt; ?>"></span>
                                                <div class="form-group">
                                                    <label for="exampleInputFile">Description For <?php echo ucwords($pk[$p_cnt]); ?></label>
                                                    <textarea id="desc_<?php echo $cnt; ?>" name="desc_<?php echo $cnt; ?>" class="form-control" <?php if ($query[0]['status'] == "2") { ?> disabled <?php } ?>><?php
                                                        if (!empty($report)) {
                                                            echo $report[$cnt]['description'];
                                                        }
                                                        ?></textarea>
                                                </div>
                                                <span style="color:red;" id="descerror_<?php echo $cnt; ?>"></span>
                                                <hr/>
                                                <?php
                                            }
                                        }
                                    }
                                    $p_cnt++;
                                    $cnt++;
                                }
                                ?>
                                <?php /* For common reoport start */ ?>
                                <h2>OR</h2>
                                <div class="form-group">
                                    <label for="exampleInputFile">Upload Common Reports</label><span style="color:red">*</span><br>	
                                    <input type="file" id="common_report" name="common_report" <?php if ($query[0]['status'] == "2") { ?> style="display:none;" <?php } ?>>
                                    <small>(Allow only .pdf file)</small><br>
                                    <?php if (!empty($common_report)) { ?>
                                        <a href="<?php echo base_url(); ?>upload/report/<?php echo$common_report[0]['report']; ?>" target="_blank"> <?php echo $common_report[0]['original']; ?> </a> &nbsp; <?php if ($query[0]['status'] != "2") { ?><a href="<?php echo base_url(); ?>job_master/remove_report/<?php echo $common_report[0]['id']; ?>/<?php echo $common_report[0]['job_fk']; ?>" onclick="return confirm('Are you sure you want to remove this data?');" style="color:red;"> X </a> <?php } ?><br>
                                        <?php
                                        $completed_cnt++;
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
                                        <option value="3" <?php if($query[0]["report_status"]==3){ echo "selected"; } ?>>Critical</option>
                                        <option value="2" <?php if($query[0]["report_status"]==2){ echo "selected"; } ?>>Semi-Critical</option>
                                        <option value="1" <?php if($query[0]["report_status"]==1){ echo "selected"; } ?>>Normal</option>
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
                        <?php if ($query[0]['sample_collection'] == "1") { ?>
                            <div class="box-footer">
                                <?php
                                if ($query[0]['testname'] != '' || $query[0]['packagename'] != '') {
                                    if (empty($common_report)) {
                                        ?>
                                        <div class="col-md-6">
                                            <button class="btn btn-primary" type="button" onclick="Validation();">Upload</button>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="box box-primary">
                <div class="box-header">
                    <!-- form start -->
                    <h3 class="box-title">Assign Phlebo</h3>
                </div>
                <div class="box-body">
                    <div class="alert alert-danger alert-dismissable" id="msg_cancel" style="display:none;">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        Phlebo Not Assign Try Again
                    </div>
                    <div class="alert alert-success alert-dismissable" id="msg_success" style="display:none;">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        Phlebo Assign Successfully
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group col-sm-12  pdng_0"">
                            <label class="col-sm-3 pdng_0" for="exampleInputFile">Select Phlebo :</label>
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
                        <div class="form-group col-sm-12  pdng_0"">
                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Date : </label>
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
                        <div class="form-group col-sm-12  pdng_0"">
                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Time :</label>
                            <div class="col-sm-9 pdng_0">
                                <select id="time_slot"  name="time" class="chosen">
                                    <option value="">--Select--</option>
                                    <?php $$emergency_tests[0]["time_slot_fk"] ?>
                                </select>
                                <input type="hidden" id="get_time1" value="<?php
                                if ($phlebo_assign_job[0]["time_fk"] != '') {
                                    echo $phlebo_assign_job[0]["time_fk"];
                                }
                                ?>">
                                <br>OR <input type="text" placeholder="Exact time" name="sharp_time" id="sharp_time" value="<?= $phlebo_assign_job[0]["time1"]; ?>"/>
                                <input type="hidden" id="as_job_fk" value="<?php echo $cid; ?>"/>
                            </div>
                            <span style="color:red;" id="email_error"></span>
                        </div>
                        <div class="form-group col-sm-12  pdng_0"">
                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Address :</label> 
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
                            <div class="col-sm-9 pdng_0" style="float:right;">
                                <input type="checkbox" id="notify" name="notify" value="1" checked style="display:none;">
                            </div>
                        </div>
                        <?php if (!empty($phlebo_assign_job[0])) { ?>
                            <div class="form-group col-sm-12  pdng_0">
                                <div class="col-sm-9 pdng_0" style="float:right;">
                                    <span class="label label-success">Phlebo already assigned.</span>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="box-footer">
                    <span id="loader_div" style="display:none;"><img src="<?= base_url(); ?>upload/opc-ajax-loader.gif" style="height:28px;margin-left:10px"></span>
                    <?php if (!empty($phlebo_assign_job[0])) { ?>
                        <input onclick="update_phlebo();" id="send_btn" style="float:right;" class="btn btn-primary" value="Update" type="button">
                    <?php } else { ?>
                        <input onclick="assign_phlebo();" id="send_btn" style="float:right;" class="btn btn-primary" value="Assign" type="button">
                    <?php } ?>
                    <script>
                        function update_phlebo() {
                            var phlb = confirm("Are you sure?");
                            if (phlb == true) {
                                assign_phlebo();
                            }
                        }
                    </script>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box box-primary">
                <?php if (isset($success) != NULL) { ?>
                    <div class="widget">
                        <div class="alert alert-success alert-dismissable">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
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
                </div>
                <div class="box-body">
                    <div class="col-md-6">
                        <?php /* if ($query[0]['pic'] != NULL) { */ ?>
                        <div class="form-group">
                            <label for="exampleInputFile">Profile :</label><br>
                            <?php
                            $check_pc = substr($query[0]['pic'], 0, 6);
                            if ($check_pc == "https:") {
                                ?>
                                <img src="<?php echo $query[0]['pic']; ?>" onerror="this.src='<?= base_url(); ?>upload/avatar/profile_avatar.png'" class="img-circle admin_job_dtl_img"/>
                            <?php } else { ?>
                                <img class="img-circle admin_job_dtl_img" onerror="this.src='<?= base_url(); ?>upload/avatar/profile_avatar.png'" src="<?php echo base_url(); ?>upload/<?php echo $query[0]['pic']; ?>"/>
                            <?php } ?>
                        </div>
                        <?php /* } */ ?>
                        <div class="form-group">
                            <label for="exampleInputFile">Customer Name :</label> <a href="<?php echo base_url(); ?>customer-master/customer-all-details/<?php echo $query[0]['custid']; ?>"> <?php echo ucfirst($query[0]['full_name']); ?></a>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputFile">Mobile :</label> <?php echo ucwords($query[0]['mobile']); ?>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputFile">Email :</label> <?php echo $query[0]['email']; ?>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputFile">Gender :</label> <?php echo ucwords($query[0]['gender']); ?>
                        </div>
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
                            <label for="exampleInputFile">Address :</label> <?php echo ucwords($query[0]['address']); ?>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputFile">Doctor :</label> <?php echo ucwords($query[0]['dname']); ?>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputFile">Note :</label> <textarea id="job_note" class="form-control"><?php echo ucwords($query[0]['note']); ?></textarea>
                            <span id="note_success"></span>
                        </div>
                        <button class="btn-sm btn-primary" type="button" id="note_save_btn" onclick="update_note();">Save</button>
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
                    <div class="col-md-6">
                        <div class="form-group">
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
                            <button class="btn-sm btn-primary" type="button" id="change" onclick="change_status();" <?php
                                if ($query[0]['status'] == 2) {
                                    echo "disabled";
                                }
                                ?>>Save</button>
                            <span id="loader_div1" style="display:none;"><img src="<?= base_url(); ?>upload/opc-ajax-loader.gif" style="height:28px;margin-left:10px"></span>
                            <input type="hidden" value="<?php echo $cid; ?>" id="job_id">
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
                            <label for="exampleInputFile">Payable Amount:</label> <?php
                            if ($query[0]['payable_amount'] == "") {
                                echo "Rs." . "0";
                            } else {
                                echo "Rs." . $query[0]['payable_amount'];
                            }
                            ?>  <a href="javascript:void(0)" onclick="$('#payment_model').modal('show');"> <i class="fa fa-edit"></i></a>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputFile">Payment Type :</label> <?php echo $query[0]['payment_type']; ?>  
                        </div>
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
                        </div>
                        <?php if ($query[0]['portal']) { ?>
                            <div class="form-group">
                                <label for="exampleInputFile">Book Portal :</label> <?php
                                echo $query[0]['portal'];
                                ?>  
                            </div>
                        <?php } ?>
                        <?php if ($query[0]['payment_type'] == 'PayUMoney') { ?>
                            <div class="form-group">
                                <label for="exampleInputFile">Transaction ID :</label> <?php
                                echo $payumoney_details[0]['payomonyid'];
                                ?>  
                            </div>
                        <?php } ?>
                        <?php /* Nishit booking details start */ ?>
                        <h3 class="box-title">Booking Information</h3>
                        <div class="form-group">
                            <label for="exampleInputFile">Test For:</label> <?php echo $relation; ?> &nbsp;<a href="javascript:void(0)" onclick="$('#family_model').modal('show');"> <i class="fa fa-edit"></i></a>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputFile">Address :</label>
                            <?php echo $booking_info[0]['address']; ?>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputFile">Booking Date :</label>
                            <?php echo $booking_info[0]['date']; ?>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputFile">Choose Time Slot :</label>
                            <?php echo $booking_info[0]['start_time'] . " To " . $booking_info[0]['end_time']; ?>
                        </div>
                        <?php if (!empty($query[0]['invoice'])) { ?>
                            <div class="form-group">
                                <label for="exampleInputFile">Invoice download :</label>
                                <a href="<?= base_url(); ?>upload/result/<?php echo $query[0]['invoice']; ?>" target="_blank"><i class="fa fa-download"></i></a>
                            </div>
                        <?php } ?>
                        <?php /* Nishit booking details end */ ?>
                    </div>
                </div>
                <div class="col-sm-12"><div id="job_tracking"></div></div>
            </div>
        </div>
        <div class="col-md-6">
        </div>

        <?php /* Nishit change payment status start */ ?>
        <!-- Modal -->
        <div class="modal fade" id="payment_model" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Payment Status</h4>
                    </div>
                    <?php echo form_open("job_master/payment_received/" . $cid, array("method" => "POST", "role" => "form")); ?>
                    <div class="modal-body">
                        <?php if (!empty($amount_history_success)) { ?>
                            <div class="widget">
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $amount_history_success['0']; ?>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="form-group">
                            <?php if ($query[0]['payable_amount'] > 0) { ?>
                                <label for="recipient-name" class="control-label">Receive Amount:</label>
                                <input type="number" class="form-control" id="j_amount" name="amount">
                            <?php } ?>
                            <input type="hidden" id="ttl_amount" name="ttl_amount" value="<?= $query[0]['payable_amount']; ?>"/>
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
                        <h3>Receive Amount History</h3>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Added By</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $ttl = 0;
                                foreach ($job_master_receiv_amount as $rakey): $ttl = $ttl + $rakey["amount"];
                                    ?>
                                    <tr>
                                        <td><?= $rakey["createddate"] ?></td>
                                        <td>Rs.<?= $rakey["amount"] ?></td>
                                        <td><?= ucfirst($rakey["name"]) ?></td>
                                        <td><a href="javascript:void(0);" onclick="delete_received_payment('<?= $rakey["id"] ?>', '<?= $rakey["amount"] ?>');"><i class="fa fa-trash"></i></a></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td><b>Total</b></td>
                                    <td colspan="3"><b>Rs.<?= $ttl; ?></b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <?php if ($query[0]['payable_amount'] > 0) { ?>
                        <button type="submit" class="btn btn-primary" onclick="this.setAttribute('disabled','disabled');" id="amount_submit_btn" disabled="">Add</button>
                        <?php } ?>
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
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $amount_history_success['0']; ?>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if (!empty($family_error)) { ?>
                            <div class="widget">
                                <div class="alert alert-danger alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
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
                            <input type="text" name="f_name" class="form-control"/>
                            <span style="color:red;" id="amount_error"></span>
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="control-label">Relation<i style="color:red;">*</i>:</label>
                            <select name="family_relation" class="form-control">
                                <option value="">--Select--</option>
                                <?php foreach ($relation1 as $fkey) { ?>
                                    <option value="<?= $fkey["id"] ?>"><?= $fkey["name"] ?></option>
                                <?php } ?>
                            </select>
                            <span style="color:red;" id="amount_error"></span>
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
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
      Results Not Add
      </div>
      <div class="alert alert-success alert-dismissable" id="msg_success" style="display:none;">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
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



</div>
</div>
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
    function add_result11() {
        alert('testq');
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
        var phlebo = $("#phlebo").val();
        var adate = $("#date").val();
        var atime = $("#time_slot").val();
        var address = $("#address").val();
        var sharp_time = $("#sharp_time").val();
        if ($('#notify').prop('checked')) {
            var notify = 1;
        } else {
            var notify = 0;
        }
        var as_job_fk = $("#as_job_fk").val();
        $.ajax({
            url: "<?php echo base_url(); ?>job_master/assign_phlebo_job",
            type: 'post',
            data: {phlebo_id: phlebo, job_id: as_job_fk, dated: adate, timed: atime, address: address, notify: notify, sharp_time: sharp_time},
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
            }, complete: function () {
                $("#loader_div").attr("style", "display:none;");
                $("#send_btn").removeAttr("disabled");
            }
        });
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
                var txt;
                var r = confirm("Are you sure want to complete job!");
                if (r == true) {
                    var completed_tst = $("#upload_report").val();
                    if (completed_tst.trim() == '' || completed_tst.trim() == '0') {
                        $("#descerror_1").html("<br>Please upload report.");
                        return false;
                    }
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
            url: '<?php echo base_url(); ?>phlebo-api_v2/get_phlebo_schedule',
            type: 'post',
            data: {bdate: val},
            success: function (data) {
                var json_data = JSON.parse(data);
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
</script>
