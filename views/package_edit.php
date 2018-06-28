<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<section class="content-header">
    <h1>
        Edit Package
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>home"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>package_master/package_list">Package</a></li>
        <li class="active">Edit Package</li>
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
                <form role="form" action="<?php echo base_url(); ?>package_master/package_edit/<?php echo $id; ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $id ?>"/>
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
                                <input type="text"  name="title" class="form-control"  value="<?php echo $query[0]['title']; ?>" >
                                <span style="color: red;"><?= form_error('title'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Type</label>
                                <select name="p_type" class="form-control">
                                    <option value="">--Select--</option>
                                    <?php foreach($type as $key){ ?>
                                    <option value="<?=$key["id"]?>" <?php if($query[0]['category_fk']==$key["id"]){ echo "selected"; } ?>><?=$key["name"]?></option>
                                    <?php } ?>
                                </select>
                                <span style="color: red;"><?= form_error('p_type'); ?></span>
                            </div>
                            <!--Nishit city wise price start-->
                            <!--<div class="form-group">
                                <label for="exampleInputFile">Actual Price</label><span style="color:red">*</span>
                                <input type="text"  name="aprice" class="form-control"  value="<?php echo $query[0]['a_price']; ?>" >
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Discount Price</label><span style="color:red">*</span>
                                <input type="text"  name="dprice" class="form-control"  value="<?php echo $query[0]['d_price']; ?>" >
                            </div>-->
                            <?php
                            foreach ($citys as $key) {
                                $a_price = '';
                                $d_price = '';
                                if (!empty($city_price)) {
                                    foreach ($city_price as $p_key) {
                                        if ($key["id"] == $p_key["city_fk"]) {
                                            $a_price = $p_key["a_price"];
                                            $d_price = $p_key["d_price"];
                                            $test = $p_key["refrance_test_fk"];
                                        }
                                    }
                                }
                                ?>
                                <div class="form-group">
                                    <label for="exampleInputFile"><?= ucfirst($key["name"]); ?> Price</label><span style="color:red">*</span>
                                    <input type="text"  name="aprice[]" class="form-control"  value="<?= $a_price; ?>">
                                    <input type="hidden" name="city[]" value="<?= $key["id"] ?>"/>
                                    <input type="hidden" name="test[]" value="<?= $test ?>"/>
                                    <span style="color: red;"><?= form_error('aprice[]'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile"><?= ucfirst($key["name"]); ?> Discount Price</label><span style="color:red">*</span>
                                    <input type="text"  name="dprice[]" class="form-control"  value="<?= $d_price; ?>" >
                                    <span style="color: red;"><?= form_error('dprice[]'); ?></span>
                                </div>
                            <?php } ?>
                            <!--Nishit city wise price list end-->
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputFile">Image</label><br>
                                <img src="<?php echo base_url(); ?>upload/package/<?php echo $query[0]['image']; ?>" alt="Pic not upload" style="width:50px; height:40px;"/><br>
                                <input type="file" id="exampleInputFile" name="sliderfile">
                                <span style="color: red;"><?= form_error('sliderfile'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Back Image</label><br>
                                <img src="<?php echo base_url(); ?>upload/package/<?php echo $query[0]['back_image']; ?>" alt="Pic not upload" style="width:50px; height:40px;"/><br>
                                <input type="file" id="exampleInputFile" name="homefile">
                                <span style="color: red;"><?= form_error('homefile'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Doctor Exclusive Package</label>
                                <input type="checkbox" value="1" <?php if($query[0]['is_view']==1){ echo "checked"; } ?> name="is_view"/>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">User Validity (in days)</label>
                                <input type="text" class="form-control" id="" placeholder="Ex.365" name="validity" value="<?php echo $query[0]['validity']; ?>">
                                <small><b>Note-</b>If package is simple then validity is 0 day</small>
                                <span style="color: red;"><?= form_error('validity'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">How Many Times Book?</label>
                                <input type="text" class="form-control" id="" placeholder="Ex.10" name="book" value="<?php echo $query[0]['booking_time']; ?>">
                                <small><b>Note-</b>If package is simple then book 0 time</small>
                                <span style="color: red;"><?= form_error('validity'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Description for Website</label><span style="color:red">*</span>
                                <textarea id="editor1" name="desc_web"> <?php echo $query[0]['desc_web']; ?> </textarea>
                                <span style="color: red;"><?= form_error('desc_web'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Description for Application</label><span style="color:red">*</span>
                                <textarea id="editor2"  name="desc_app"><?php echo $query[0]['desc_app']; ?> </textarea>
                                <span style="color: red;"><?= form_error('desc_app'); ?></span>
                            </div>
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

