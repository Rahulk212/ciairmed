<style>
    .admin_job_dtl_img {border: 4px solid #8d8d8d; height: 160px; max-width: 160px; min-width: 80px; width: 180px;}
</style>
<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<link href="<?php echo base_url(); ?>plugins/timepick/css/timepicki.css" rel="stylesheet">
<section class="content-header">
    <h1>
        Just Dial
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <span style="margin-right:30px; font-size:20px;"></span>
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li class="active">Just Dial Details</li>
    </ol>
</section>
<!-- Add fancyBox main JS and CSS files -->
<script type="text/javascript" src="<?php echo base_url(); ?>source/jquery.fancybox.pack.js?v=2.1.5"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>source/jquery.fancybox.css?v=2.1.5" media="screen" />
<!-- Add Media helper (this is optional) -->
<script type="text/javascript" src="<?php echo base_url(); ?>source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>
<!--Nishit code start-->
<script type="text/javascript">
    var jq = $.noConflict();
    jq(document).ready(function () {
        jq('.fancybox').fancybox();
    });
</script>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header">
                    <!-- form start -->
                    <h3 class="box-title">Details</h3>
                    <!--Add job start-->
                    <hr>
                    <!--Add job end-->
                </div>
                <div class="box-body">
                    <div class="col-sm-4">
                    <div class="form-group">
                        <label for="exampleInputFile">Name :</label> <?= ucfirst($query[0]['name']); ?>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputFile">Email :</label> <?= $query[0]['email']; ?>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputFile">Phone :</label> <?= ucfirst($query[0]['phone']); ?>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputFile">Gender :</label> <?= ucfirst($query[0]['gender']); ?>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputFile">Date Of Birth :</label> <?= ucfirst($query[0]['date_of_birth']); ?>
                    </div>
                    </div>
                    <div class="col-sm-4">
                    <div class="form-group">
                        <label for="exampleInputFile">Age :</label> <?php if(!empty($query[0]['age'])){ echo ucfirst($query[0]['age']." Y");} ?>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputFile">Address :</label> <?= ucfirst($query[0]['address']); ?>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputFile">Note :</label> <?= ucfirst($query[0]['note']); ?>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputFile">City :</label> <?= ucfirst($query[0]['city']); ?>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputFile">Search Date Time :</label> <?= ucfirst($query[0]['search_date_time']); ?>
                    </div>
                        </div>
                    <div class="col-sm-4">
                    <div class="form-group">
                        <label for="exampleInputFile">Created Date :</label> <?= ucfirst($query[0]['created_date']); ?>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputFile">Requirement :</label> <?= ucfirst($query[0]['requirement']); ?>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputFile">Branch Info. :</label> <?= ucfirst($query[0]['branch_info']); ?>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputFile">From :</label> <?= ucfirst($query[0]['from']); ?>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
    </div>

</section>
</div>
</div>
<script src="<?php echo base_url(); ?>plugins/timepick/js/timepicki.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>

