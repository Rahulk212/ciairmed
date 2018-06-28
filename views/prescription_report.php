<style>
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
    .chosen-container {width: 100% !important;}
</style>
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Customer Prescriptions
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>customer-master/customer-list"><i class="fa fa-users"></i>Completed Job</a></li>

        </ol>
    </section>

    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content srch_popup_full">
                <div class="modal-header srch_popup_full srch_head_clr">
                    <button  type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title clr_fff" >Create Account</h4>
                </div>
                <form action="<?php echo base_url(); ?>customer_master/add_by_admin_for_book1" method="post" enctype="multipart/form-data" id="addcust"/>
                <div class="modal-body srch_popup_full">
                    <div class="form-group">
                        <label for="exampleInputFile">Full Name</label><span style="color:red">*</span>
                        <input type="text" id="fname" name="fname" class="form-control"  >

                        <span style="color:red;" id="error_fname"> </span>

                    </div>
                    <div class="form-group">
                        <label for="exampleInputFile">Email</label><span style="color:red">*</span>
                        <input type="text" id="custemail" name="email" class="form-control" >
                        <span style="color:red;" id="error_email"> </span>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputFile">Password</label><span style="color:red">*</span>
                        <input type="password" id="password" name="password" class="form-control" >
                        <span style="color:red;" id="error_password"> </span>

                    </div>
                    <div class="form-group">
                        <label for="exampleInputFile">Mobile</label><span style="color:red">*</span>
                        <input type="text" id="mobile" readonly name="mobile" class="form-control decimal" >
                        <span style="color:red;" id="error_mobile"> </span>

                    </div>
                </div>
                <div class="modal-footer uplod_prec_full">
                    <button type="button" onclick='validation();' class="btn btn-default" >Create Account</button>
                </div>
            </div>
        </div>
        </form>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <div class="col-xs-12">
                            <div class="col-xs-2 pull-right">
                                <div class="form-group">
                                    <a style="float:right;" href='<?php echo base_url(); ?>job_master/prescription_csv_report' class="btn btn-primary btn-sm" ><i class="fa fa-download" ></i><strong > Export To CSV</strong></a>
                                </div>
                            </div>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="widget">
                            <?php if (isset($success) != NULL) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $success['0']; ?>
                                </div>
                            <?php } ?>
                            <?php if ($this->session->flashdata("successnewuser")) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $this->session->flashdata("successnewuser"); ?>
                                </div>
                            <?php } ?>
                        </div>
                        <?php /*
                          <?php $attributes = array('class' => 'form-horizontal', 'method' => 'get', 'role' => 'form'); ?>

                          <?php echo form_open('admin_master/admin_list', $attributes); ?>

                          <div class="col-md-3">
                          <input type="text" class="form-control" name="user" placeholder="Username" value="<?php if(isset($username) != NULL){ echo $username; } ?>" />
                          </div>
                          <div class="col-md-3">
                          <input type="text" class="form-control" name="email" placeholder="Email" value="<?php if(isset($email) != NULL){ echo $email; } ?>"/>
                          </div>
                          <input type="submit" value="Search" class="btn btn-primary btn-md">


                          </form>

                          <br>
                         */ ?>
                        <div class="tableclass">
                            <form role="form" action="<?php echo base_url(); ?>job-master/prescription-report" method="get" enctype="multipart/form-data">
                                <table id="example4" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Customer Name</th>
                                            <!--<th>Description</th>-->
                                            <th>Mobile</th>
                                            <th>Date</th>
                                            <th> Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><span style="color:red;">*</span></td>
                                            <td><select class="form-control chosen-select" tabindex="-1"  data-placeholder="Select Customer" name="user">
                                                    <option value="">Select Customer</option>
                                                    <?php foreach ($customer as $cat) { ?>
                                                        <option value="<?php echo $cat['id']; ?>" <?php
                                                        if ($customerfk == $cat['id']) {
                                                            echo "selected";
                                                        }
                                                        ?> ><?php echo ucwords($cat['full_name']); ?></option>
                                                            <?php } ?>
                                                </select></td>
                                            <td><input type="text" placeholder="Mobile" class="form-control" name="mobile" value="<?php
                                                if (isset($date)) {
                                                    echo $mobile;
                                                }
                                                ?>"/></td>
                                            <td><input type="text" name="date" placeholder="Select Date" class="form-control" id="date" value="<?php
                                                if (isset($date)) {
                                                    echo $date;
                                                }
                                                ?>" /></td>
                                            <td><select class="form-control chosen-select" data-placeholder="Select Status" tabindex="-1" name="status">
                                                    <option value="">Select Status </option>
                                                    <option value="1" <?php
                                                    if ($status == 1) {
                                                        echo "selected";
                                                    }
                                                    ?>> Pending </option>
                                                    <option value="2" <?php
                                                    if ($status == 2) {
                                                        echo "selected";
                                                    }
                                                    ?>> Completed </option>
                                                </select></td>
                                            <td><input type="submit" name="search" class="btn btn-success" value="Search" /></td>
                                        </tr>
                                        <?php
                                        $cnt = 1;
                                        foreach ($query as $row) {
                                            ?>
                                            <tr>
                                                <td><?php
                                                    echo $cnt+$per_page . " ";
                                                    if ($row['is_read'] == '0') {
                                                        echo '<span class="round round-sm blue"> </span>';
                                                    }
                                                    ?> 
                                                </td>
                                                <td><a href="<?php echo base_url(); ?>job-master/prescription-details/<?php echo $row['id']; ?>"><?php echo ucwords($row['full_name']); ?> </a></td>
                                                 <!--<td><?php echo ucwords($row['description']); ?></td>-->
                                                <td><?php echo $row['mobile']; ?></td>
                                                <td><?php echo ucwords($row['created_date']); ?></td>
                                                <td>
                                                    <?php if ($row['status'] == "2") { ?>
                                                        <span class="label label-success">Completed</span>   
                                                    <?php } else { ?>
                                                        <span class="label label-danger">Pending</span>  
                                                    <?php } ?>										
                                                </td>
                                                <td>


<!--                                                    <a  href='<?php echo base_url(); ?>job-master/prescription-details/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="View Job Details" > <span class="label label-primary"><i class="fa fa-eye"> </i> View Details</span> </a>-->
                                                    <a  href='<?php echo base_url(); ?>job-master/prescription-details/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="View Prescription Details" > <span class="label label-primary"><i class="fa fa-eye"> </i></span> </a>  
                                                    <a  onclick="return confirm('Are you sure you want to spam this prescription ?');" href='<?php echo base_url(); ?>job_master/prescription_spam/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Spam Prescription" > <span class="label label-danger">&nbsp;  <i class="fa fa-trash">  </i></span> </a>
                                                    <?php if ($row['cid'] == "") { ?>  
                                                        <a href="" data-toggle="modal" onclick="set_mobile('<?php echo $row['mobile']; ?>');" data-target="#myModal"><span class="label label-success">Create Account</span></a>
                                                    <?php } ?> 


                                                </td>
                                            </tr>
                                            <?php
                                            $cnt++;
                                        }if (empty($query)) {
                                            ?>
                                            <tr>
                                                <td colspan="6">No records found</td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>

                            </form>
                        </div>
                        <div style="text-align:right;" class="box-tools">
                            <ul class="pagination pagination-sm no-margin pull-right">
                                <?php echo $links; ?>
                            </ul>
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
<script  type="text/javascript">
                                                    $('.decimal').keyup(function () {
                                                        var val = $(this).val();
                                                        if (isNaN(val)) {
                                                            val = val.replace(/[^0-9\.]/g, '');
                                                            if (val.split('.').length > 2)
                                                                val = val.replace(/\.+$/, "");
                                                        }
                                                        $(this).val(val);
                                                    })
                                                    jQuery(".chosen-select").chosen({
                                                        search_contains: true
                                                    });
                                                    //  $(".chosen-select-deselect").chosen({ allow_single_deselect: true });
                                                    // $("#cid").chosen('refresh');

</script> 
<script>
    function validation() {
        var all = 0;
        $('#fname').each(function () {
            var desc = $(this).val();
            $('#error_fname').html("");
            if (desc != '') {
                $('#error_fname').html(" ");
            } else {
                all = 1;
                $('#error_fname').html("Fullname is required.");
            }
        });
        $('#custemail').each(function () {
            var desc = $(this).val();
            $('#error_email').html("");
            if (desc != '') {
                $('#error_email').html(" ");
            } else {
                all = 1;
                $('#error_email').html("Email is required.");
            }
        });
        $('#password').each(function () {
            var desc = $(this).val();
            $('#error_password').html("");
            if (desc != '') {
                $('#error_password').html(" ");
            } else {
                all = 1;
                $('#error_password').html("Password is required.");
            }
        });
        $('#mobile').each(function () {
            var desc = $(this).val();
            $('#error_mobile').html("");
            if (desc != '') {
                $('#error_mobile').html(" ");
                $.ajax({
                    url: '<?= base_url(); ?>register/checkmobilebyadmin',
                    type: 'post',
                    data: {mobile: desc},
                    success: function (data) {
                        if (data == '0') {
                            all = 1;
                            $('#error_mobile').html("Mobile Number Already Exists.");
                        } else {
                            $('#error_mobile').html("");
                        }
                    },
                });
            }
        });
        if (all != '1') {
            var mobile = $('#mobile').val();
            $('#error_mobile').html(" ");
            if (mobile.length == 10) {
                $.ajax({
                    url: '<?= base_url(); ?>register/checkmobilebyadmin',
                    type: 'post',
                    data: {mobile: mobile},
                    success: function (data) {
                        if (data == '0') {
                            all = 1;
                            $('#error_mobile').html("Mobile Number Already Exists.");
                        } else {
                            $('#error_mobile').html("");
                            //email
                            var email = $('#custemail').val();
                            $('#error_email').html(" ");
                            $.ajax({
                                url: '<?= base_url(); ?>register/checkemailbyadmin',
                                type: 'post',
                                data: {email: email},
                                success: function (data) {
                                    if (data == '0') {
                                        all = 1;
                                        $('#error_email').html("Email Already Exists.");
                                    } else {
                                        $('#error_email').html("");
                                        $("#addcust").submit();
                                    }
                                },
                            });
                        }
                    },
                });
            } else {
                $('#error_mobile').html("Enter Valid Number.");
            }
        } else {
            return false;
        }

    }
    $(function () {
        $('#example3').dataTable({
            "bPaginate": false,
            "bLengthChange": false,
            "bFilter": false,
            "bSort": false,
            "bInfo": false,
            "bAutoWidth": false,
            "iDisplayLength": 10,
            "searching": false
        });
    });
    function set_mobile(vl) {
        $('#mobile').val(vl);
    }

</script>
