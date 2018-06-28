<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Package/Test Inquiry 
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><i class="fa fa-users"></i> Package/Test Inquiry</li>
        </ol>
    </section>
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content srch_popup_full">
                <div class="modal-header srch_popup_full srch_head_clr">
                    <button  type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title clr_fff" >Create Account</h4>
                </div>
                <form action="<?php echo base_url(); ?>customer_master/add_by_admin_for_book" method="post" enctype="multipart/form-data" id="addcust"/>
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
                        <input type="text" id="password" name="password" class="form-control" >
                        <span style="color:red;" id="error_password"> </span>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputFile">Mobile</label><span style="color:red">*</span>
                        <input type="text" id="mobile"  name="mobile" readonly="" class="form-control decimal"/>
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
                                    <a style="float:right;" href='<?php echo base_url(); ?>job_master/inquiry_csv_report' class="btn btn-primary btn-sm" ><i class="fa fa-download" ></i><strong > Export To CSV</strong></a>
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
                            <?php echo form_open("job_master/Package_test_inquiry_list", array("method" => "GET", "role" => "form")); ?>
                            <table id="example4" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Mobile</th>
                                        <th>Inquiry Package</th>
                                        <th>Inquiry Test</th>
                                        <th>Customer Name</th>
                                        <th>Date</th>
                                        <th>Book</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><span style="color:red;">*</span></td>
                                        <td>
                                            <input type="text" name="mobile" class="form-control" value="<?php
                                            if (isset($mobile)) {
                                                echo $mobile;
                                            }
                                            ?>" />
                                        </td>
                                        <td>
                                            <?php /* <select class="form-control chosen-select" tabindex="-1" data-placeholder="Select Package" name="package">
                                              <option value="">Select Package</option>
                                              <?php foreach ($package_list as $cat) { ?>
                                              <option value="<?php echo $cat['id']; ?>" <?php
                                              if ($package == $cat['id']) {
                                              echo "selected";
                                              }
                                              ?> ><?php echo ucwords($cat['title']); ?></option>
                                              <?php } ?>
                                              </select> */ ?>
                                        </td>
                                        <td>
                                            <?php /* <select class="form-control chosen-select" tabindex="-1" data-placeholder="Select Test" name="test">
                                              <option value="">Select Test</option>
                                              <?php foreach ($test_list as $cat) { ?>
                                              <option value="<?php echo $cat['id']; ?>" <?php
                                              if ($test == $cat['id']) {
                                              echo "selected";
                                              }
                                              ?> ><?php echo ucwords($cat['test_name']); ?></option>
                                              <?php } ?>
                                              </select> */ ?>
                                        </td> 
                                        <td>
                                            <select class="form-control chosen-select" tabindex="-1" data-placeholder="Select Customer" name="user">
                                                <option value="">Select Customer</option>
                                                <?php foreach ($customer as $cat) { ?>
                                                    <option value="<?php echo $cat['id']; ?>" <?php
                                                    if ($customerfk == $cat['id']) {
                                                        echo "selected";
                                                    }
                                                    ?> ><?php echo ucwords($cat['full_name']); ?>(<?php echo $cat['mobile']; ?>)</option>
                                                        <?php } ?>
                                            </select>
                                        </td>
                                        <td><input type="text" id="date" name="date" class="form-control datepicker-input" value="<?php
                                            if (isset($date)) {
                                                echo $date;
                                            }
                                            ?>" /></td>
                                        <td>
                                            <input type="text" class="form-control" disabled/>
                                        </td>
                                        <td>
                                            <select class="form-control chosen-select" data-placeholder="Select Status" tabindex="-1" name="status">
                                                <option value="">Select Status </option>
                                                <option value="1" <?php
                                                if ($statusid == 1) {
                                                    echo "selected";
                                                }
                                                ?>> Pending </option>
                                                <option value="2" <?php
                                                if ($statusid == 2) {
                                                    echo "selected";
                                                }
                                                ?>> Completed </option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="submit" name="search" class="btn btn-success" value="Search" />
                                        </td>
                                    </tr>
                                    <?php
                                    $cnt = 1;

                                    $mobilearr = array();
                                    foreach ($customer as $key) {

                                        $mobilearr[] = $key['mobile'];
                                    }
                                    foreach ($query as $row) {
                                        ?>

                                        <tr>


                                            <td><?php echo $cnt+$page; ?></td>

                                            <td><?php echo ucwords($row['mobile']); ?></td>
                                            <td><?php echo ucwords($row['packagename']); ?></td>
                                            <td><?php echo ucwords($row['testname']); ?></td>
                                            <td> 
                                                <?php
                                                if (in_array($row['mobile'], $mobilearr, TRUE)) {
                                                    echo "<a href='" . base_url() . "customer-master/customer-all-details/" . $row['uid'] . "'>" . ucwords($row['full_name']) . "</a>";
                                                } else {
                                                    ?>
                                                    <a href="javascript:void(0);" onclick="test('<?= $row['mobile'] ?>');" class="btn btn-primary" > Create Account </a>

                                                <?php }
                                                ?>
                                            </td>
                                            <td><?php echo $row['date']; ?></td>
                                            <td> 
                                                <?php
                                                if (in_array($row['mobile'], $mobilearr) && $row['status'] != 2) {
                                                    ?>
                                                    <a href="<?php echo base_url(); ?>job_master/book_by_admin?test_fk=<?php echo $row['test_fk'] ?>&package_fk=<?php echo $row['package_fk'] ?>&uid=<?php echo $row['uid'] ?>&id=<?php echo $row['id'] ?>" class="btn btn-success" >Book </a>

                                                <?php } else { ?>

                                                <?php }
                                                ?>
                                            </td>
                                            <td>
                                                <?php if ($row['status'] == "2") { ?>
                                                    <span class="label label-success">Completed</span>   
                                                <?php } else { ?>
                                                    <span class="label label-danger">Pending</span>  
                                                <?php } ?>										
                                            </td>
                                            <td>

                                                <a  href='<?php echo base_url(); ?>job_master/contact_delete/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i class="fa fa-trash-o"></i></a>
                                                <?php if ($row['status'] == "2") { ?>
                                                    <a  href='<?php echo base_url(); ?>job_master/contact_pending/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Mark as Pending" onclick="return confirm('Are you sure ?');" ><i class="fa fa-ban"></i></a>   
                                                <?php } else { ?>
                                                    <a  href='<?php echo base_url(); ?>job_master/contact_complete/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Mark as Completed" onclick="return confirm('Are you sure?');"><i class="fa fa-check"></i></a>   
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php
                                        $cnt++;
                                    }if (empty($query)) {
                                        ?>
                                        <tr>
                                            <td colspan="8">No records found</td>
                                        </tr>
                                    <?php } ?>

                                </tbody>
                            </table>
                            <?php echo form_close(); ?>
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
    function test(phone_no) {
        $.ajax({
            url: '<?= base_url(); ?>job_master/check_phone',
            type: 'post',
            data: {mobile: phone_no},
            success: function (data) {
                var json_data = JSON.parse(data);
                if (json_data.status == '0') {
                    all = 1;
                    alert("Mobile Number Already Exists.");
                } else {
                    $("#mobile").val(phone_no);
                    $("#myModal").modal('show');
                }
            },
        });
    }
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
            } else {
                all = 1;
                $('#error_mobile').html("Mobile is required.");
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

</script>
