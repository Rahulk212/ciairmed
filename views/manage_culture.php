<!-- Page Heading -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<!-- Add fancyBox main JS and CSS files -->
<script src="//cdn.ckeditor.com/4.5.8/full/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>source/jquery.fancybox.pack.js?v=2.1.5"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>source/jquery.fancybox.css?v=2.1.5" media="screen" />
<!-- Add Media helper (this is optional) -->
<script type="text/javascript" src="<?php echo base_url(); ?>source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!--Nishit code start-->
<script type="text/javascript">
    var jq = $.noConflict();
    jq(document).ready(function () {
        jq('.fancybox').fancybox();
    });
</script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content">
        <div class="row">

            <div class="col-md-12">
                <div class="box box-primary">

                    <?php echo form_open_multipart("add_result/manage_culture?test_fk=" . $test_fk . "&branch=" . $branch_fk . "&edit=" . $edit, array("id" => "add_result", "method" => "post")); ?>
                    <input type="hidden" name="test_fk" value="<?= $test_fk ?>"/>
                    <input type="hidden" name="center" value="<?= $processing_center ?>"/>
                    <input type="hidden" name="branch_fk" value="<?= $branch_fk ?>"/>
                    <div class="box-header">
                        <h3 class="box-title">Manage Culture Result</h3>
                    </div>
                    <div class="col-sm-12">
                        <div class="col-sm-12" style="background:#EFF0F1;padding:14px 0px 0px 0px;">
                            <div class="form-group col-sm-12">
                                <label for="exampleInputFile">Test Name :-</label>
                                <?= ucfirst($test_name[0]['test_name']); ?>
                            </div>

                            <div class="form-group col-sm-12">
                                <label for="exampleInputFile">Center Name :-</label>
                                <?= ucfirst($branch_name[0]['branch_name']); ?>  
                            </div>
                        </div>
                    </div>
                    <br />
                    <div class="box-body">
                        <div class="col-sm-12">
                            <h2 class="page-header">
                                <i class="fa fa-list-alt"></i> Manage
                            </h2>
                            <div class="col-mg-12">
                                <?php if ($success[0] != '') { ?>
                                    <div class="widget">
                                        <div class="alert alert-success alert-dismissable">
                                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
                                            <?php echo $success['0']; ?>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="form-group col-sm-3">
                                    <label for="exampleInputFile">Title :-</label>
                                    <input type="text" placeholder="Title" name="title" class="form-control" required="" value="<?= $edit_query[0]["title"] ?>"/>
                                    <span style="color:red;"><?php echo form_error("title"); ?></span>
                                </div>
                                <div class="form-group col-sm-12">
                                    <label for="exampleInputFile">Design :-</label>
                                    <textarea class="ckeditor" name="culture_design" required=""><?= $edit_query[0]["html"] ?></textarea>
                                    <span style="color:red;"><?php echo form_error("culture_design"); ?></span>
                                </div>
                            </div>
                            <div class="box-footer">
                                <input style="float:right;" class="btn btn-sm btn-primary" value="<?php if (empty($edit)) { ?>Save<?php
                                } else {
                                    echo "Update";
                                }
                                ?>" type="submit" >
                            </div>
                            <?= form_close(); ?>

                            <h2 class="page-header">
                                <i class="fa fa-list-alt"></i> List
                            </h2>
                            <table id="example4" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="4%">No.</th>
                                        <th width="4%">Title</th>
                                        <th width="10%">action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cnt = 1;
                                    $preview_html = array();
                                    foreach ($query as $key) {
                                        $preview_html[] = $key["html"];
                                        ?>
                                        <tr>
                                            <td><?= $cnt; ?></td>
                                            <td><?= $key["title"] ?></td>
                                            <td>
                                                <a href='javascript:void(0);' onclick="open_modal('<?= $cnt - 1; ?>');" data-toggle="tooltip" data-original-title="Preview" title="Preview"><span class=""><i class="fa fa-eye"></i></span></a>
                                                <a href="<?= base_url(); ?>add_result/manage_culture?test_fk=<?= $test_fk ?>&branch=<?= $branch_fk ?>&edit=<?= $key["id"] ?>" title="Edit"><i class="fa fa-pencil-square-o"></i></a> 
                                                <a href="<?= base_url(); ?>add_result/delete_manage_culture?test_fk=<?= $test_fk ?>&branch=<?= $branch_fk ?>&delete=<?= $key["id"] ?>" title="Delete" onclick="return confirm('Are you sure?');"><i     class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        <?php
                                        $cnt++;
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button onclick="parent.close_box();" class="btn btn-primary pull-right">Done</button>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>
<a data-toggle="modal" href="#myModal" style="display: none;" id="open_modal1">Open Modal</a>
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Preview</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <div id="preview_div"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<script  type="text/javascript">
    CKEDITOR.replace('#description');
    $(function () {
        $('.chosen').chosen();
    });

</script> 
<script>
    $html_preview_data = JSON.parse(JSON.stringify(<?php echo json_encode($preview_html); ?>));
//    alert($html_preview_data[0]);
    function open_modal(val) {
        document.getElementById("preview_div").innerHTML = $html_preview_data[val];
        document.getElementById("open_modal1").click();
    }
</script>
