<style>
    .round {
        display: inline-block;
        height: 30px;
        width: 30px;
        line-height: 30px;
        -moz-border-radius: 15px;
        border-radius: 15px;
        background-color: #222;    
        color: #FFF;
        text-align: center;  
    }
    .round.round-sm {
        height: 10px;
        width: 10px;
        line-height: 10px;
        -moz-border-radius: 10px;
        border-radius: 10px;
        font-size: 0.7em;
    }
    .round.blue {
        background-color: #3EA6CE;
    }
    .chosen-container {width: 100% !important;}
</style>
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Source Lab Report
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li>Payment Report</li>

        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                    </div><!-- /.box-header -->

                    <div class="box-body">

                        

                        <div class="widget">
                            <?php if (isset($success) != NULL) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $success['0']; ?>
                                </div>
                            <?php } ?>

                        </div>
                        <!-- Search start-->
                        <?php echo form_open("b2b/report/get_rpeort", array("method" => "GET", "role" => "form")); ?>
                        <div class="row">
                            <div class="col-sm-2">
                                <input type="text" name="start_date" placeholder="Start date" class="form-control datepicker-input" id="date"  value="<?= $this->input->get('start_date') ?>" />
                            </div>

                            <div class="col-sm-2">
                                <input type="text" name="end_date" placeholder="End date" class="form-control datepicker-input" id="date1"  value="<?= $this->input->get('end_date'); ?>" />
                            </div>
							
							<div class="col-sm-2"> 
                                <?php $selabs = $this->input->get('labs') ?>
                                <select class="form-control chosen" data-placeholder="Select Type" tabindex="-1" name="labs">
                                    <option value="" >Select lab name</option>
                                    <?php foreach ($lablist as $lab) { ?>
                                        <option value="<?= $lab['id']; ?>" <?php
                                        if ($selabs == $lab['id']) {
                                            echo "selected";
                                        }
                                        ?> ><?= $lab['name']; ?></option>
                                            <?php } ?>

                                </select>
                            </div>
							
							<div class="col-sm-2"> 
                                <?php $seacti = $this->input->get('activatedby'); ?>
                                <select class="form-control chosen"  data-placeholder="Select Type" tabindex="-1" name="activatedby">
                                    <option value="" >Activated by</option>
                                    <?php foreach ($activatedby as $lab) { ?>
                                        <option value="<?= $lab['id']; ?>" <?php
                                        if ($seacti == $lab['id']) {
                                            echo "selected";
                                        }
                                        ?> ><?= $lab['first_name']." ".$lab['last_name']; ?></option>
                                            <?php } ?>
									</select>
                            </div>

                            <div class="col-sm-2">
                                <input type="submit" name="search" class="btn btn-success" value="Search" />
                                <a href="<?= base_url() . "b2b/report/payment_report"; ?>" class="btn btn-info" >Clear</a>
								
                            </div>
							
							

                        </div>
                        <br>
                        <?php echo form_close(); ?>
                        <!--Search end-->
                        <div class="tableclass">
                            <div class="table-responsive" id="prnt_rpt">
							
							<?php 
							$start_date = $this->input->get('start_date');
						$end_date = $this->input->get('end_date');
						if($start_date != "" || $end_date != "") { 
						?>
			
                       <a href="<?= base_url()."b2b/report/labreport_export?start_date=$start_date&end_date=$end_date"; ?>" class="btn-sm btn-primary pull-right">Export</a>
	
						<?php } ?>

                                <table id="example4" class="table table-bordered table-striped">
                                    <h2><?php if (trim($this->input->get('start_date')) != '' || trim($this->input->get('end_date')) != '') { ?><?= $this->input->get('start_date'); ?> To <?= $this->input->get('end_date'); ?><?php } ?></h2>

                                    <thead>

                                        <tr>
                                            <th><h4>No.</h4></th>
                                            <th><h4>lab Name</h4></th>
                                            <th>Activated By</th>
                                            <th>Total Sample</th>
                                            <th>Received Amount</th>
                                            <th>Due Amount</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($final_report != null) {
                                            $i = $counts;
                                            foreach ($final_report as $val) {
                                                $i++;
                                                ?>

                                                <tr>
                                                    <td><?= $i; ?></td>
                                                    <td><a href="<?=base_url();?>b2b/report/payment_report?start_date=<?=$this->input->get('start_date')?>&end_date=<?=$this->input->get('end_date')?>&labs=<?=$val["id"]?>&search=Search" target="_blank"><?= ucfirst($val["name"]); ?></a></td>
                                                    <td><?= ucfirst($val["activated_by"]); ?></td>
                                                    <td><?= count($val["job_count"]); ?></td>
                                                    <td>Rs.<?= ucfirst($val["credit"]); ?></td>
                                                    <td>Rs.<?= ucfirst($val["due"]); ?></td>
                                                </tr>

                                                <?php
                                            }
                                        } else {
                                            ?>

                                            <tr>
                                                <td colspan="8">No records found</td>
                                            </tr>

                                        <?php } ?>

                                    </tbody>
                                </table>

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
        </div><!-- /.row -->

    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
<script  type="text/javascript">

    jQuery(".chosen-select").chosen({
        search_contains: true
    });
    $(function () {
        $('.chosen').chosen();
    });
    //  $(".chosen-select-deselect").chosen({ allow_single_deselect: true });
    // $("#cid").chosen('refresh');
    function printData()
    {
        var divToPrint = document.getElementById("prnt_rpt");
        newWin = window.open("");
        newWin.document.write(divToPrint.outerHTML);
        newWin.print();
        newWin.close();
    }
</script> 
<script type="text/javascript">
    $(function () {

        $('#example3').dataTable({
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bSort": true,
            "bInfo": false,
            "bAutoWidth": false,
            "iDisplayLength": 100
        });
    });
</script>