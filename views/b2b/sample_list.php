<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Sample
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>b2b/Logistic/sample_list"><i class="fa fa-users"></i>Sample List</a></li>
        </ol>
    </section>
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
    <!-- Main content -->

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Sample List</h3>
                        <?php if ($login_data["type"] == 3) { ?>
                            <a style="float:right;margin-right:5px;" href='<?php echo base_url(); ?>b2b/Logistic/sample_add' class="btn btn-primary btn-sm" ><i class="fa fa-plus-circle" ></i><strong > Add</strong></a>
                        <?php } ?>
                        <!--
                        <a style="float:right;margin-right:5px;" href='<?php echo base_url(); ?>test_master/test_csv?city=<?= $city ?>' class="btn btn-primary btn-sm" ><strong > Export</strong></a>
                        <a style="float:right;margin-right:5px;" data-toggle="modal"  data-target="#import"  class="btn btn-primary btn-sm" ><strong > Import</strong></a>-->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <?php echo form_open("b2b/Logistic/sample_list/", array("method" => "GET")); ?>
                        <div class="tableclass  table-responsive">
						<div class="widget">
                             <?php echo form_open("b2b/Logistic/sample_list/", array("method" => "GET")); ?>
                                <div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;" >
                                        <input type="text" class="form-control" name="barcode" placeholder="Barcode" value="<?= $barcode ?>"/>
                                    </div>
                                </div>
								
								 <div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;">
                                        <input type="text" class="form-control" name="patientsname" placeholder="Patients name" value="<?= $patientsname ?>"/>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;" >
                                        <input type="text" class="form-control" name="name" placeholder="Logistic Name" value="<?= $name ?>"/>
                                    </div>
                                </div>
								
								<?php if ($login_data["type"] == 3) { ?>
								
								<div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;" >
                                      
									   <select name="salesperson" class="form-control"  >
									   <option value="" >Sales person</option>
									   <?php foreach($salesall as $saleds){ ?>
									    <option value="<?= $saleds['id']; ?>" <?php if($salesperson== $saleds['id']){ echo "selected"; } ?>  ><?= $saleds['first_name']." ".$saleds['last_name']; ?></option>
									   <?php } ?>
									   </select>
                                    </div>
                                </div>
								 
								 <div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;" >
                                       
									   <select name="from" class="form-control"  >
									   <option value="" >Collect From</option>
									   <?php foreach($laball as $laba){ ?>
									    <option value="<?= $laba['id']; ?>" <?php if($from== $laba['id']){ echo "selected"; } ?>  ><?= $laba['name']; ?></option>
									   <?php } ?>
									   </select>
									   
									  </div>
                                </div>
								
								 <div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;" >
                                        <select name="sendto" class="form-control"  >
									   <option value="" >Send To</option>
									   <?php foreach($desti_lab as $labdesc){ ?>
									    <option value="<?= $labdesc['id']; ?>" <?php if($sendto== $labdesc['id']){ echo "selected"; } ?>  ><?= $labdesc['name']; ?></option>
									   <?php } ?>
									   </select>
									   
                                    </div>
                                </div>
								 
								 <?php } ?>
								
								
								
								 <div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;" >
                                       <input type="text" class="form-control" name="date" placeholder="from Scan Date" value="<?= $date ?>"/>
                                    </div>
                                </div>
								
								 <div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;" >
                                       <input type="text" class="form-control" id="todatese"  name="todate" placeholder="To Scan Date" value="<?= $todate ?>"/>
                                    </div>
                                </div>
								
								  <div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;" >
                                       <input type="hidden"  class="form-control" name="city" placeholder="City" value="<?= $city ?>"/>
                                    </div>
                                </div>
								
								<?php /* <div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;" >
                                       <select  name="status" class="form-control"  >
									   <option value="" >test</option>
									   </select>
                                    </div>
                                </div> */ ?>
								
								<div class="form-group pull-right">
                                        <div class="col-sm-12" style="margin-bottom:10px;">
										
										 <button type="submit"  class="btn btn-sm btn-primary" >Search</button>
                                           
                                            <a type="button" href="<?= base_url()."b2b/Logistic/sample_list" ?>" class="btn btn-sm btn-primary" ><i class="fa fa-refresh"></i> Reset</a>
											
											<?php if ($login_data["type"] == 3) { ?>
                                            <a style="float:right;margin-left:3px" href="<?= base_url()."b2b/Logistic/sample_export?name=$name&barcode=$barcode&date=$date&from=$from&patientsname=$patientsname&salesperson=$salesperson&sendto=$sendto&todate=$todate&city=$city&status=$status"; ?>" id=""  class="btn btn-sm btn-primary"><i class="fa fa-download"></i><strong> Export To CSV</strong></a>
											<?php }else{ ?>
											 <a style="float:right;margin-left:3px" href="<?= base_url()."b2b/Logistic/sampledescti_export?name=$name&barcode=$barcode&date=$date&from=$from&patientsname=$patientsname&salesperson=$salesperson&sendto=$sendto&todate=$todate&city=$city&status=$status"; ?>" id=""  class="btn btn-sm btn-primary"><i class="fa fa-download"></i><strong> Export To CSV</strong></a>
											<?php } ?>
                                        </div>
                                    </div>
								
								</form>
							</div>	
                            <table id="example4" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Barcode</th>
                                        <th>Logistic Name</th>
                                        <th>Scan Date</th>
                                        <?php if ($login_data["type"] == 3) { ?>
                                            <th>Collect From</th>
                                            <th>Send To</th>
                                            <th>City</th>
                                        <?php } ?>

                                        <th>Status</th>
                                        <th style="width: 10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    <?php
                                    $cnt = $counts;
                                    foreach ($query as $row) {
                                        $cnt++;
                                        ?>
                                        <tr> <td><?php echo $cnt; ?></td>
                                            <td>
                                                <a href="javascript:void(0);" id="sshowtrack_<?= $row['id']; ?>" class="sshowtrack" data-toggle="collapse" data-target="#demo_<?= $row['id']; ?>"><?php echo ucwords($row['barcode']); ?></a>

                                                <div id="demo_<?= $row['id']; ?>" class="collapse">
                                                    <?php
                                                    /* $cnt1 = 0;
                                                      foreach ($row["sample_track"] as $key) {
                                                      ?>
                                                      <?php
                                                      if ($cnt1 != 0) {
                                                      echo "<br><i class='fa fa-arrow-down'></i><br>";
                                                      }
                                                      ?>
                                                      <?= "<b>" . ucfirst($key["name"]) . "</b>" ?><?php echo " (<small>" . date("d-m-Y g:i A", strtotime($key["scan_date"])) . "</small>)"; ?>
                                                      <?php
                                                      $cnt1++;
                                                      } */
                                                    ?>
                                                </div>
                                                <?php
                                                if ($login_data["type"] == 3) {
                                                    echo "<br><a><b>" . ucwords($row['customer_name']) . "</b></a>";
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo ucwords($row['name']); ?></td>
                                            <td><?php echo ucwords($row['scan_date']); ?></td>
                                            <?php if ($login_data["type"] == 3) { ?>
                                                <td><?php echo ucwords($row['c_name']); ?></td>
                                                <td><?php echo ucwords($row["desti_lab1"]); ?></td>
                                                <td><?php echo ucwords($row["tetst_city_name"]); ?></td>
                                            <?php } ?>
                                            <td><?php
                                                if ($login_data["type"] == 3) {
                                                    if ($row['jobsstatus'] == 0) {
                                                        echo '&nbsp;&nbsp;<span class="label label-warning">Enroute</span>';
                                                    } else if ($row['jobsstatus'] == 2) {

                                                        echo '&nbsp;&nbsp;<span class="label label-info">Processing</span>';
                                                    } if ($row['jobsstatus'] == 1) {
                                                        echo '&nbsp;&nbsp;<span class="label label-success">Completed</span>';
                                                    }
                                                    ?>
                                                    <?php
                                                    if ($row['jobsstatus'] != 1) {
                                                        if (!empty($row['job_details'])) {
                                                            echo '&nbsp;&nbsp;<span class="label label-success">Test suggested</span>';
                                                        } else {
                                                            echo '&nbsp;&nbsp;<span class="label label-warning">Test not suggested</span>';
                                                        }

                                                        if (!empty($row['job_details'])) {

                                                            if ($row['thyrocare_job_id'] != "" && $login_data["type"] == 3) {

                                                                echo '&nbsp;&nbsp;<span style="cursor:pointer;" class="label label-warning" onclick="alert(\'Your Booking Id - ' . $row['thyrocare_job_id'] . '\')">Already Assign to Thyrocare</span>';
                                                                if ($row['thyrocare_report'] == "") {
                                                                    ?>
                                                                    <a target="_blank"  href='<?php echo base_url(); ?>b2b/Logistic/fetchresult/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="details"><i class="fa fa-eye"></i> Fetch Result</a>
                                                                    <?php
                                                                } else {
                                                                    ?>
                                                                    <a target="_blank"  href='<?php echo base_url(); ?>b2b/Logistic/fetchresult/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="details"><i class="fa fa-arrow-down"></i> Download Report</a>
                                                                    <?php
                                                                }
                                                            } else {
                                                                /* if ($row['job_details'][0]["original"]) {
                                                                  echo '&nbsp;&nbsp;<span class="label label-success">Report uploaded</span>';
                                                                  } else {
                                                                  echo '&nbsp;&nbsp;<span class="label label-warning">Report pending</span>';
                                                                  } */
                                                            }
                                                        }
                                                    }
                                                } else {
                                                    if (!empty($row['treport'])) {
                                                        echo '&nbsp;&nbsp;<span class="label label-success">Completed</span>';
                                                    } else {
                                                        echo '&nbsp;&nbsp;<span class="label label-info">Processing</span>';
                                                    }
                                                }
                                                ?>
                                            </td>

                                            <td>
                                                <?php
                                                if ($row['jobsstatus'] != 1 && $row["desti_lab"] == "") {
                                                    if ($login_data["type"] == 3) {
                                                        ?>
                                                        <a  href='javascript:void(0);' onclick="$('#myModal').modal('show');$('#job_fk_id').val('<?php echo $row['id']; ?>')">Forward</a>    
                                                        <?php
                                                    }
                                                }
                                                ?>
                                                <a  href='<?php echo base_url(); ?>b2b/Logistic/details/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="details"><i class="fa fa-eye"></i></a>
                                                <?php if ($login_data["type"] == 3) { ?>
                                                    <a  href='<?php echo base_url(); ?>b2b/Logistic/sample_delete/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i class="fa fa-trash-o"></i></a>      
                                                <?php } ?>
                                                <?php if ($row['thyrocare_job_id'] == "" && $login_data["type"] == 3) { ?>
                                                    <a onclick="assigntotyrocare('<?php echo $row['id']; ?>')" href="javascript:void(0);" data-toggle="tooltip" data-original-title="Assign to thyrocare"><i class="fa fa-eyedropper"></i></a>
                                                <?php } ?>
                                                    <?php if ($row['treport']) { ?> <a  href='javascript:void(0)' class="showreport" id="pdfreport_<?= $row['id']; ?>"  data-toggle="tooltip" data-original-title="Download Report"><i class="fa fa-arrow-down "></i> Download Report</a><?php } ?>
                                            </td>

                                        </tr>
                                    <?php }if (empty($query)) {
                                        ?>
                                        <tr>
                                            <td colspan="4">No records found</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <?= form_close(); ?>
                        </div>
                        <div style="text-align:right;" class="box-tools">
                            <ul class="pagination pagination-sm no-margin pull-right">
                                <?php echo $links; ?>
                            </ul>
                        </div>
                    </div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<div id="myModalreport" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Uploaded Reports</h4>
            </div>

            <div id="reportcontain" class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>

        </div>

    </div>
</div>

<script type="text/javascript">

    $(".sshowtrack").click(function () {

        var id = this.id;
        var splitid = id.split("_")
        var jid = splitid[1];
        $('#demo_' + jid).html("Please wait...");

        $.ajax({url: "<?php echo base_url() . "b2b/logistic/getjobs_track"; ?>",
            type: "POST",
            data: {pid: jid},
            error: function (jqXHR, error, code) {
            },
            success: function (data) {
                $('#demo_' + jid).html(data);
            }
        });

    });

    $(document).on("click", ".showreport", function () {

        var id = this.id;
        var splitid = id.split("_")
        var jid = splitid[1];

        $('#reportcontain').html('<div style="height:50px"><span id="searching_spinner_center" style="position: absolute;left: 50%;"><img src="<?= base_url() . "img/ajax-loader.gif" ?>" /></span></div>');
        $('#myModalreport').modal('show');


//$("#"+id).prop('disabled', true);
        $.ajax({url: "<?php echo base_url() . "b2b/logistic/getreportpdf"; ?>",
            type: "POST",
            data: {pid: jid},
            error: function (jqXHR, error, code) {
            },
            success: function (data) {
                $('#reportcontain').html(data);
            }
        });

    });
</script>
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Assign Destination Lab</h4>
            </div>
            <?php echo form_open("b2b/logistic/desti_assign", array("method" => "POST", "role" => "form")); ?>
            <div class="modal-body">
                <div>
                    <label>Select Lab</label>
                    <select class="form-control" name="desti_lab" required="">
                        <option value="">--Select--</option>
                        <?php foreach ($desti_lab as $k_lab) { ?>
                            <option value="<?= $k_lab["id"] ?>"><?= $k_lab["name"] ?></option>
                        <?php } ?>
                    </select>
                    <input type="hidden" name="job_fk" id="job_fk_id" value=""/>
                </div>
            </div>
            <div class="modal-footer">
                <input type="submit" class="btn btn-primary" value="Forward">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            <?php echo form_close(); ?>
        </div>

    </div>
</div>
<script type="text/javascript">
    $(function () {
		
		var date_input = $('#todatese'); //our date input has the name "date"

                        var container = $('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";
                        date_input.datepicker({
                            format: 'dd/mm/yyyy',
                            container: container,
                            todayHighlight: true,
                            autoclose: true,
                        });
						
        $("#example1").dataTable();
        $('#example3').dataTable({
            "bPaginate": false,
            "bLengthChange": false,
            "bFilter": false,
            "bSort": false,
            "bInfo": false,
            "bAutoWidth": false,
            "iDisplayLength": 10,
            "searching": false
        });
    });
    function assigntotyrocare(id) {

        $.ajax({
            url: '<?php echo base_url(); ?>b2b/Logistic/sendToThyrocare/' + id,
            type: 'get',
            beforeSend: function () {
                $("#loader_div").attr("style", "");
            },
            success: function (data) {

                var result = JSON.parse(data);
                if (result.status == "SUCCESS") {
                    alert("Sample forwared to Thyrocare. Job Id is " + result.barcode_patient_id);
                    window.location.reload();
                } else {
                    alert("Error on sample forwared to Thyrocare. Mesage  " + result.message);
                }
            },
            error: function (jqXhr) {
                alert("error");
            },
            complete: function () {
                $("#loader_div").attr("style", "display:none;");
                //$("#send_opt_1").removeAttr("disabled");
                // alert("complete");

            },
        });
    }
</script>
