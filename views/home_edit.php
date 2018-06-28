<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<section class="content-header">
    <h1>
        Edit Home Details
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>home"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li class="active">Edit Home Details</li>
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
                <form role="form" action="<?php echo base_url(); ?>cms_master/index" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="col-md-6">

                            <?php if (isset($unsuccess) != NULL) { ?>
                                <div class="alert alert-danger alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $unsuccess['0']; ?>
                                </div>
                            <?php } ?>


                            <div class="form-group">
                                <label for="exampleInputFile">Email</label>
                                <input type="text"  name="email" class="form-control"  value="<?php echo $query[0]['email']; ?>" >

                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Facebook Link</label>
                                <input type="text"  name="fb_link" class="form-control"  value="<?php echo $query[0]['fb_link']; ?>" >

                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Twitter Link</label>
                                <input type="text"  name="tw_link" class="form-control"  value="<?php echo $query[0]['tw_link']; ?>" >

                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Gmail Link</label>
                                <input type="text"  name="gmail_link" class="form-control"  value="<?php echo $query[0]['gmail_link']; ?>" >

                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Instagram Link</label>
                                <input type="text"  name="insta_link" class="form-control"  value="<?php echo $query[0]['insta_link']; ?>" >

                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Linkedin Link</label>
                                <input type="text"  name="linkedin_link" class="form-control"  value="<?php echo $query[0]['linkedin_link']; ?>" >

                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Home Banner</label><br>
                                <img src="<?php echo base_url(); ?>upload/<?php echo $query[0]['index_banner']; ?>" alt="Profile Pic" style="width:110px; height:90px;"/><br>
                                <input type="file" id="exampleInputFile" name="file">
                            </div>
                            <!--Nishit test city start-->
                            <!--Nishit city wise price start-->
                            <div class="form-group">
                                <label for="exampleInputFile">Test city</label><br>
                                <table class="table table-bordered" id="city_wiae_price">
                                    <tbody><tr>
                                            <th>City</th> 
                                            <th>Action</th>
                                        </tr>
                                        <!-- Default -->
                                    </tbody></table>
                            </div>
                            <a href="javascript:void(0);" onclick="$('#exampleModal').modal('show');"><i class="fa fa-plus-square" style="font-size:20px;"></i></a>
                            <!--Nishit city wise price end-->
                            <!--Nishit test city end-->
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
<!--Model start-->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">Add City</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="message-text" class="control-label">City name:</label>
                    <input type="text" id="name" class="form-control"/>
                    <span style="color:red;" id="name_error"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="get_city_price();">Add</button>
            </div>
        </div>
    </div>
</div>
<!--Model end-->
<!--Model start-->
<div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">Edit City</h4>
            </div>
            <div class="modal-body" id="edit_body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="edit_city();">Update</button>
            </div>
        </div>
    </div>
</div>
<!--Model end-->
<script>
    $city_cnt = 0;
    function get_city_price() {
        var city_val = $("#name").val();
        $("#name_error").html("");
        var cnt = 0;
        if (city_val.trim() == '') {
            $("#name_error").html("City name is required.");
            cnt = cnt + 1;
        }
        if (cnt > 0) {
            return false;
        }

        $.ajax({
            url: '<?= base_url(); ?>cms_master/test_city',
            type: 'post',
            data: {city_val: city_val, action: "add"},
            success: function (data) {
                $("#city_wiae_price").html(data);
            },
            error: function (jqXhr) {
                alert('Oops somthing wrong Tryagain!.');
            }
        });


        $city_cnt = $city_cnt + 1;
        $("#name").val("");
        $('#exampleModal').modal('hide');

    }

    function CheckNumber(nmbr) {
        var filter = /^[0-9-+]+$/;
        if (filter.test(nmbr)) {
            return true;
        } else {
            return false;
        }
    }

    function delete_city_price(id) {
        var tst = confirm('Are you sure?');
        if (tst == true) {
            $.ajax({
                url: '<?= base_url(); ?>cms_master/test_city',
                type: 'post',
                data: {id: id, action: "delete"},
                success: function (data) {
                    $("#city_wiae_price").html(data);
                },
                error: function (jqXhr) {
                    alert('Oops somthing wrong Tryagain!.');
                }
            });
            $("#tr_" + id).remove();
        }
    }

    function edit_city_price(id) {
        $.ajax({
            url: '<?= base_url(); ?>cms_master/test_city',
            type: 'post',
            data: {id: id, action: "edit"},
            success: function (data) {
                $("#edit_body").html(data);
                $('#exampleModal1').modal('show');
            },
            error: function (jqXhr) {
                alert('Oops somthing wrong Tryagain!.');
            }
        });
    }

    function edit_city() {
        var id = $("#edit_id").val();
        var name = $("#edit_name").val();
        var cnt = 0;
        if (name.trim() == '') {
            $("#edit_name_error").html("City name is required.");
            cnt = cnt + 1;
        }
        if (id.trim() == '') {
            alert('Oops somthing wrong Tryagain!.');
            cnt = cnt + 1;
        }
        if (cnt > 0) {
            return false;
        }
        $.ajax({
            url: '<?= base_url(); ?>cms_master/test_city',
            type: 'post',
            data: {action: "edit1",id:id,city_val:name}, 
            success: function (data) {
                console.log(data);
                $("#city_wiae_price").html(data);
                $('#exampleModal1').modal('hide');
            },
            error: function (jqXhr) {
                alert('Oops somthing wrong Tryagain!.');
            }
        });
    }

    $.ajax({
        url: '<?= base_url(); ?>cms_master/test_city',
        type: 'post',
        data: {action: "get"},
        success: function (data) {
            $("#city_wiae_price").html(data);
        },
        error: function (jqXhr) {
            alert('Oops somthing wrong Tryagain!.');
        }
    });
</script>