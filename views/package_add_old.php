<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<section class="content-header">
    <h1>
        Add Package
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>home"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>slider/slider_list">Package</a></li>
        <li class="active">Add Package</li>
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
                <form role="form" action="<?php echo base_url(); ?>package_master" method="post" enctype="multipart/form-data">
                    <!--<?= validation_errors('<div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>', '</div>'); ?>-->

                    <div class="box-body">
                        <div class="col-md-6">
                            <?php if (isset($unsuccess) != NULL) { ?>
                                <div class="alert alert-danger alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $unsuccess['0']; ?>
                                </div>
                            <?php } ?>
                            <div class="form-group">
                                <label for="exampleInputFile">Title</label><span style="color:red">*</span>
                                <input type="text"  name="title" class="form-control"  value="<?php echo set_value('tile'); ?>" >
                                <span style="color: red;"><?= form_error('title'); ?></span>
                            </div>
                            <!--<div class="form-group">
                                <label for="exampleInputFile">Actual Price</label><span style="color:red">*</span>
                                <input type="text"  name="aprice" class="form-control"  value="<?php echo set_value('aprice'); ?>" >

                            </div>-->
                            <!--Nishit city wise price start-->
                            <?php foreach ($citys as $key) { ?>
                                <div class="form-group">
                                    <label for="exampleInputFile"><?= ucfirst($key["name"]); ?> Price</label><span style="color:red">*</span>
                                    <input type="text"  name="aprice[]" class="form-control"  value="<?php echo set_value('aprice[]'); ?>">
                                    <input type="hidden" name="city[]" value="<?= $key["id"] ?>"/>
                                    <span style="color: red;"><?= form_error('aprice[]'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile"><?= ucfirst($key["name"]); ?> Discount Price</label><span style="color:red">*</span>
                                    <input type="text"  name="dprice[]" class="form-control"  value="<?php echo set_value('dprice[]'); ?>" >
                                    <span style="color: red;"><?= form_error('dprice[]'); ?></span>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputFile">Image</label>
                                <input type="file" id="exampleInputFile" name="sliderfile">
                                <span style="color: red;"><?= form_error('sliderfile'); ?></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputFile">Back Image</label>
                                <input type="file" id="exampleInputFile" name="homefile">
                                <span style="color: red;"><?= form_error('homefile'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Doctor Exclusive Package</label>
                                <input type="checkbox" value="1" name="is_view"/>
                            </div>
                            <!--<div class="form-group">
                                <label for="exampleInputFile">Discount Price</label><span style="color:red">*</span>
                                <input type="text"  name="dprice" class="form-control"  value="<?php echo set_value('dprice'); ?>" >
                            </div>-->
                            <div class="form-group">
                                <label for="exampleInputFile">User Validity (in days)</label>
                                <input type="text" class="form-control" id="" placeholder="Ex.365" name="validity">
                                <small><b>Note-</b>If package is simple then validity is 0 day</small>
                                <span style="color: red;"><?= form_error('validity'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">How Many Times Book?</label>
                                <input type="text" class="form-control" id="" placeholder="Ex.10" name="book">
                                <small><b>Note-</b>If package is simple then book 0 time</small>
                                <span style="color: red;"><?= form_error('validity'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Description for Website</label><span style="color:red">*</span>
                                <textarea id="editor1" name="desc_web"> </textarea>
                                <span style="color: red;"><?= form_error('editor1'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Description for Application</label><span style="color:red">*</span>
                                <textarea id="editor2"  name="desc_app"> </textarea>
                                <span style="color: red;"><?= form_error('editor2'); ?></span>
                            </div>
                        </div>
                    </div><!-- /.box-body -->

                    <div class="box-footer">
                        <div class="col-md-6">
                            <button class="btn btn-primary" type="submit">ADD</button>
                        </div>
                    </div>

                </form>
            </div><!-- /.box -->
        </div>
    </div>
</section>

