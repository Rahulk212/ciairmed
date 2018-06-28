<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<section class="content-header">
    <h1>
        Edit Press Release
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>home"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="">Master</a></li>
        <li class="active">Edit Slider</li>
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
                <form role="form" action="<?php echo base_url(); ?>press_release/edit/<?=$id?>" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="col-md-6">
                            <?= validation_errors('<div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>', '</div>'); ?>
                            <?php if (isset($unsuccess) != NULL) { ?>
                                <div class="alert alert-danger alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $unsuccess['0']; ?>
                                </div>
                            <?php } ?>
                            <div class="form-group">
                                <label for="exampleInputFile">Title</label><span style="color:red">*</span>
                                <input type="text" name="title" class="form-control" value="<?=$query[0]["title"]?>"/>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Date</label><span style="color:red">*</span>
                                <input type="text" name="date" class="form-control" value="<?=$query[0]["date"]?>"/>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">News Logo</label><span style="color:red">*</span><br>
                                <img src="<?=base_url()?>upload/<?=$query[0]["news_logo"]?>" style="height: 50px;"/>
                                <input type="file" id="exampleInputFile" name="news">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Pic</label><span style="color:red">*</span><br>
                                <img src="<?=base_url()?>upload/<?=$query[0]["pic"]?>" style="height: 50px;"/>
                                <input type="file" id="exampleInputFile" name="pic">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">NEWS Link</label><span style="color:red">*</span>
                                <input type="text" name="link" class="form-control" value="<?=$query[0]["link"]?>"/>
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

