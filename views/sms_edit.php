<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<section class="content-header">
    <h1>
        Edit Sms Details
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>home"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li class="active">Edit Sms Details</li>
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
                <form role="form" action="<?php echo base_url(); ?>cms_master/sms" method="post" enctype="multipart/form-data">
                    <div class="box-body">
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
                        <!--sms div start-->
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-4 text-right">
                                    <label for="exampleInputFile">Sample collection</label><span style="color:red">*</span>
                                </div>
                                <textarea name="Sample_collection_Sms" class="form-control" style="width:60%" rows="5"><?php echo $query[0]['message']; ?></textarea>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4 text-right">
                                    <label for="exampleInputFile">Spam Report</label><span style="color:red">*</span>
                                </div>
                                <textarea name="Span_report" class="form-control" style="width:60%" rows="5"><?php echo $query[1]['message']; ?></textarea>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4 text-right">
                                    <label for="exampleInputFile">Pending Report</label><span style="color:red">*</span>
                                </div>
                                <textarea name="Pending_Report" class="form-control" style="width:60%" rows="5"><?php echo $query[2]['message']; ?></textarea>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4 text-right">
                                    <label for="exampleInputFile">Completed Report</label><span style="color:red">*</span>
                                </div>
                                <textarea name="Completed_Report" class="form-control" style="width:60%" rows="5"><?php echo $query[3]['message']; ?></textarea>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4 text-right">
                                    <label for="exampleInputFile">Suggested Test Generated</label><span style="color:red">*</span>
                                </div>
                                <textarea name="Suggested_Test_Generated" class="form-control" style="width:60%" rows="5"><?php echo $query[4]['message']; ?></textarea>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4 text-right">
                                    <label for="exampleInputFile">Mobile OTP Message</label><span style="color:red">*</span>
                                </div>
                                <textarea name="OTP" class="form-control" style="width:60%" rows="5"><?php echo $query[5]['message']; ?></textarea>
                            </div>
							<div class="form-group">
                                <div class="col-md-4 text-right">
                                    <label for="exampleInputFile">Test Book Without Login Message</label><span style="color:red">*</span>
                                </div>
                                <textarea name="tbwlm" class="form-control" style="width:60%" rows="5"><?php echo $query[6]['message']; ?></textarea>
                            </div>
							<div class="form-group">
                                <div class="col-md-4 text-right">
                                    <label for="exampleInputFile">Upload Prescription Message</label><span style="color:red">*</span>
                                </div>
                                <textarea name="upload_pres" class="form-control" style="width:60%" rows="5"><?php echo $query[7]['message']; ?></textarea>
                            </div>
							<div class="form-group">
                                <div class="col-md-4 text-right">
                                    <label for="exampleInputFile">Test Book With Login Message</label><span style="color:red">*</span>
                                </div>
                                <textarea name="tblm" class="form-control" style="width:60%" rows="5"><?php echo $query[8]['message']; ?></textarea>
                            </div>
							<div class="form-group">
                                <div class="col-md-4 text-right">
                                    <label for="exampleInputFile">Prescription</label><span style="color:red">*</span>
                                </div>
                                <textarea name="prescription_info" class="form-control" style="width:60%" rows="5"><?php echo $query[9]['message']; ?></textarea>
                            </div>
							<div class="form-group">
                                <div class="col-md-4 text-right">
                                    <label for="exampleInputFile">Test</label><span style="color:red">*</span>
                                </div>
                                <textarea name="test_info" class="form-control" style="width:60%" rows="5"><?php echo $query[10]['message']; ?></textarea>
                            </div>
                        </div>
                        <!--sms div end-->
                        <!--notification div start-->
                        <?php /* <div class="col-md-6">
                          <div class="form-group">
                          <label for="exampleInputFile">Sample collection Notification</label><span style="color:red">*</span>
                          <textarea name="Sample_collection_notification"><?php echo $query[0]['notification']; ?></textarea>
                          </div>
                          </div>
                          <!--notification div end-->
                         */ ?>
                        <div class="form-group">
                            <h4>suggestion</h4>
                            <small>
                                Name - {{NAME}}<br>
                                Order Id - {{OID}}<br>
                                Price - {{PRICE}}<br>
                            </small>
                        </div>
                    </div><!-- /.box-body -->

                    <div class="box-footer">
                        <div class="col-md-6">
                            <button class="btn btn-primary" type="submit">Update</button>
                        </div>
                    </div>
                </form>
            </div><!-- /.box -->  
        </div>
    </div>
</section>

