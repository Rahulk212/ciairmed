<!-- Page Heading -->
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<section class="content-header">
    <h1>
        Update Wallet
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>wallet_master/account_history"><i class="fa fa-users"></i>History</a></li>
        <li class="active">Update Wallet</li>
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
                <form role="form" action="<?php echo base_url(); ?>wallet_master/wallet_update" method="post" enctype="multipart/form-data">

                    <div class="box-body">
                        <div class="col-md-6">
                            <?= validation_errors('<div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>', '</div>'); ?>
                            <div class="form-group">
                                <label for="exampleInputFile">Customer</label><span style="color:red">*</span>
                                <select name="user" class="chosen">
                                    <option value="">Select Customer</option>
                                    <?php foreach ($customer as $cat) { ?>
                                        <option value="<?php echo $cat['id']; ?>" <?php echo set_select('user', $cat['id']); ?>><?php echo ucwords($cat['full_name']); ?> - <?php echo $cat['mobile']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Type</label><span style="color:red">*</span>
                                <select class="form-control" name="type" onchange="change_name(this.value);">
                                    <option value="">Select Type</option>
                                    <option value="1">Credit</option>
                                    <option value="2">Debit</option>

                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Amount</label><span style="color:red">*</span>
                                <input type="text"  name="amount" class="form-control"  value="<?php echo set_value('amount'); ?>" >

                            </div>


                            <script>
                            function change_name(val){
                                if(val==1){
                                    $("#submit_btn").html("Credit");
                                }else if(val==2){
                                    $("#submit_btn").html("Debit");
                                }else{
                                    $("#submit_btn").html("Add");
                                }
                            }
                            </script>

                        </div>
                    </div><!-- /.box-body -->

                    <div class="box-footer">
                        <div class="col-md-6">
                            <button class="btn btn-primary" id="submit_btn" type="submit">Add</button>
                        </div>
                    </div>

                </form>
            </div><!-- /.box -->
            <script  type="text/javascript">
                $(document).ready(function () {
                    $("#showHide").click(function () {
                        if ($("#password").attr("type") == "password") {
                            $("#password").attr("type", "text");
                        } else {
                            $("#password").attr("type", "password");
                        }

                    });
                });
            </script>
            <script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
            <script  type="text/javascript">
                $(function () {
                    $('.chosen').chosen();
                });

            </script> 
        </div>
    </div>
</section>
