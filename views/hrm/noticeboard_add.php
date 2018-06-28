<!-- Page Heading -->
<style>
    #table_body td:nth-child(2n+1) {
        width: 100px;
    }
</style>
<div class="content-wrapper">
    <section class="content-header">
        <h1>Notice Board<small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>hrm/notice_board/"><i class="fa fa-clipboard"></i> Notice List</a></li>
            <li class="active"> Add Notice</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary" style="border-color:#bf2d37;">
                    <div class="panel-heading" style="background-color:#bf2d37;">
                        <!-- form start -->
                        <h3 class="panel-title">Add Notice</h3>
                    </div>
                    <form role="form" id="award_form" action="<?php echo base_url(); ?>hrm/notice_board/add" method="post" enctype="multipart/form-data">
                        <div class="panel-body">
                            <div class="col-md-12">
                                <div class="form-group col-sm-12  pdng_0">
                                    <label for="exampleInputFile" class="col-sm-3 pdng_0">Title <span style="color:red;">*</span></label> 
                                    <div class="col-sm-6 pdng_0">
                                        <input type="text" id="title" name="title" placeholder="Title" class="form-control" value="<?php echo set_value('title'); ?>"/>
                                        <span style="color:red;"><?php echo form_error('title'); ?></span>
                                    </div>
                                </div>
                                <div class="form-group col-sm-12  pdng_0">
                                    <label for="exampleInputFile" class="col-sm-3 pdng_0">Description <span style="color:red;">*</span></label> 
                                    <div class="col-sm-6 pdng_0">
                                        <textarea id="editor1" name="description"><?php echo set_value('description'); ?></textarea>
                                        <span style="color:red;"><?php echo form_error('description'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <button class="btn btn-primary" type="submit">Add</button>
                        </div>
                    </form>
                </div><!-- /.box -->
            </div>
        </div>
    </section>
</div>