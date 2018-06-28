<style>
    .pdng_0 {padding: 0;}
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
    .fillInfoClass{display:block !important; }
    .chosen-container .chosen-results li.active-result {width: 100% !important;}
</style>
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<link href="<?php echo base_url(); ?>plugins/timepick/css/timepicki.css" rel="stylesheet">
<style>
    .chosen-container {
        display: inline-block;
        font-size: 14px;
        position: relative;
        vertical-align: middle;
        width: 100%;
    }
</style>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- Custom Tabs -->
            <?php if (isset($success) != NULL) { ?>
                <div class="alert alert-success alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">X</button>
                    <?php echo $success['0']; ?>
                </div>
            <?php } ?>
            <?php if (isset($error) != NULL) { ?>
                <div class="alert alert-danger alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">X</button>
                    <?php echo $error['0']; ?>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
            <section class="content">
                <div class="row">
                    <div class="row">
                        <div class="col-md-12">
                            <?php echo form_open("Logistic/details/" . $id, array("role" => "form", "method" => "POST", "id" => "user_form")); ?>
                            <div class="col-md-6">
                            <div class="box box-primary">

                                <div class="box-header">
                                    <!-- form start -->
                                    <h3 class="box-title">Sample Details</h3>
                                </div>
                                <div class="box-body">
                                    <div class="col-md-6">
                                        <div class="form-group col-sm-12  pdng_0 mrgn_0">
                                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Barcode : </label><?php echo $barcode_detail[0]["barcode"]; ?>

                                        </div>
                                        <div class="form-group col-sm-12  pdng_0 mrgn_0">
                                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Scan date : </label><?php echo $barcode_detail[0]["scan_date"]; ?> 

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group col-sm-12  pdng_0 mrgn_0">
                                            <label for="exampleInputFile" class="col-sm-5 pdng_0">Collect From : </label><?php echo ucfirst($barcode_detail[0]["c_name"]); ?>

                                        </div>
                                        <div class="form-group col-sm-12  pdng_0 mrgn_0">
                                            <label for="exampleInputFile" class="col-sm-5 pdng_0">Logistic Name : </label><?php echo ucfirst($barcode_detail[0]["name"]); ?> 

                                        </div>
                                        <?php /* Nishit change payment status end */ ?>
                                    </div>
                                </div>
                            </div>
                            <?php echo form_close(); ?>
                            <div class="box box-primary">

                                <div class="box-header">
                                    <!-- form start -->
                                    <h3 class="box-title">Upload Report</h3>
                                </div>
                                
                                    <div class="box-body">
                                        <div class="form-group">
                                            <?php if (!empty($common_report)) { ?>
                                                <a href="<?php echo base_url(); ?>upload/business_report/<?php echo $common_report[0]['report']; ?>" target="_blank"> <?php echo $common_report[0]['original']; ?> </a> &nbsp; <?php if ($query[0]['status'] != "2") { ?><a href="<?php echo base_url(); ?>job_master/remove_report/<?php echo $common_report[0]['id']; ?>/<?php echo $common_report[0]['job_fk']; ?>" onclick="return confirm('Are you sure you want to remove this data?');" style="color:red;"> </a> <?php } ?><br>
                                                <?php
                                                $completed_cnt++;
                                            }else{
                                                echo '<span class="label label-warning">Report pending</span>';
                                            }
                                            ?>
                                        </div>
                                        <div class="form-group">
                                            <textarea class="form-control" disabled="" name="desc_common_report"><?php echo $common_report[0]['description']; ?></textarea>
                                        </div>
                                        
                                    </div>
                            </div>
                            
                            </div>
                            <div class="col-md-6">
                            <div class="box box-primary">
                                <div class="box-header">
                                    <h3 class="box-title">Suggested Test</h3>
                                </div>
                                <div class="box-body">
                                    <div class="col-md-12" id="all_packages">
                                        <table class="table table-striped" id="city_wiae_price">
                                            <thead>
                                                <tr>
                                                    <th>Test Name</th>
                                                </tr>
                                            </thead>
                                            <tbody id="t_body">
                                                <?php
                                                $cnt = 0;
                                                $total_price = 0;
                                                foreach ($job_details[0]["test_list"] as $ts1) {
                                                    //array_push($pids, $ts1['test_id']);
                                                    ?>
                                                    <tr id="tr_<?= $cnt ?>">
                                                        <td><?= $ts1["info"][0]["test_name"]; ?></td> 
                                                    </tr>
                                               <?php }
                                               if(empty($job_details[0]["test_list"])){
                                                   ?><tr>
                                                       <td><center>Test not suggested.</center></td> 
                                                    </tr>
                                                       <?php 
                                               }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div><!-- /.box -->
                                </div>
                            </div>
                            </div>
                            
                        </div>
                        </section>

                        <script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
                    </div><!-- /.tab-pane -->
                </div><!-- /.tab-content -->
        </div><!-- nav-tabs-custom -->
    </div>
    <!-- /.row -->
</section>
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
<script src="<?php echo base_url(); ?>plugins/timepick/js/timepicki.js"></script>
