<style>
    .cust_all_dtl_in {border-bottom: 1px solid #cccccc; float: left; margin-bottom: 15px; width: 100%;}
    .cust_alldtl_h {margin-top: 0; font-size: 20px;}
    .cust_all_dtl_in .form-group {padding-left: 53px; position: relative;}
    .cust_all_dtl_in label {left: 0; position: absolute;}
</style>
<style>
    .admin_job_dtl_img {border: 4px solid #8d8d8d; height: 160px; max-width: 160px; min-width: 80px; width: 180px;}
</style>

<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<section class="content-header">
    <h1>
        Customer Information 
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>customer-master/customer-list"><i class="fa fa-users"></i>Customer List</a></li>
        <li class="active"> Customer Information </li>
    </ol>
</section>
<div class="row">
    <section class="content">
        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Order Details</h3>
                </div>
                <div class="box-body">
                    <?php /* <table class="col-sm-12">
                      <?php
                      $cnt = 1;
                      $temp = 1;
                      foreach ($job as $job_details) {
                      ?>
                      <?php if ($temp == 1) { ?>
                      <tr>
                      <?php } ?>
                      <td class="col-sm-6">
                      <h4 class="cust_alldtl_h">Order- <?php echo $cnt; ?> (<a href="<?= base_url() ?>job-master/job-details/<?php echo $job_details["id"]; ?>"><?php echo $job_details["order_id"]; ?></a>) </h4>
                      <div class="cust_all_dtl_in">
                      <?php if ($job_details["test"] != NULL) { ?>
                      <div class="form-group">
                      <label for="exampleInputFile">Test : </label>
                      <?php echo str_replace(",", ", ", $job_details["test"]); ?>
                      </div>
                      <?php } ?>
                      <?php if ($job_details["package_name"] != NULL) { ?>
                      <div class="form-group">
                      <label for="exampleInputFile">Package : </label>
                      <?php echo $job_details["package_name"]; ?>
                      </div>
                      <?php } ?>
                      <?php if ($job_details["price"] != NULL) { ?>
                      <div class="form-group">
                      <label for="exampleInputFile">Price : </label>
                      <?php echo "Rs.", $job_details["price"]; ?>
                      </div>
                      <?php } ?>
                      <div class="form-group">
                      <label for="exampleInputFile">Date : </label>
                      <?php echo $job_details["date"]; ?>
                      </div>
                      <div class="form-group">
                      <label for="exampleInputFile">Status : </label>
                      <?php
                      if ($job_details["status"] == 1) {
                      echo "<span class='label label-danger'>Waiting For Approval</span>";
                      }
                      ?>
                      <?php
                      if ($job_details["status"] == 6) {
                      echo "<span class='label label-warning'>Approved</span>";
                      }
                      ?>
                      <?php
                      if ($job_details["status"] == 7) {
                      echo "<span class='label label-warning'>Sample Collected</span>";
                      }
                      ?>
                      <?php
                      if ($job_details["status"] == 8) {
                      echo "<span class='label label-warning'>Processing</span>";
                      }
                      ?>
                      <?php
                      if ($job_details["status"] == 2) {
                      echo "<span class='label label-success'>Completed</span>";
                      }
                      ?>
                      </div>
                      </div>
                      </td>
                      <?php if ($temp == 2) { ?>
                      </tr>
                      <?php
                      } $temp ++;
                      if ($temp == 3) {
                      $temp = 1;
                      }
                      ?>

                      <?php
                      $cnt++;
                      }
                      ?>
                      </table> */ ?>
                    <table id="example4" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Order Id</th>
                                <th style="width:48%">Test/package Name</th>
                                <th>Price</th>
                                <th>Booking Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $cnt = 1;
                            $temp = 1;
                            foreach ($job as $job_details) {
                                ?>
                                <tr>
                                    <td><a href="<?= base_url() ?>job-master/job-details/<?php echo $job_details["id"]; ?>"><?php echo $job_details["order_id"]; ?></a></td>
                                    <td><?php if ($job_details["test"] != NULL) { ?>
                                            <?php echo str_replace(",", ", ", $job_details["test"]); ?>
                                        <?php } ?>
                                        <?php if ($job_details["package_name"] != NULL) { ?>
                                            ,<?php echo $job_details["package_name"]; ?>
                                        <?php } ?></td>
                                    <td><?php if ($job_details["price"] != NULL) { ?>
                                            <?php echo "Rs.", $job_details["price"]; ?>
                                        <?php } ?></td>
                                    <td>
                                        <?php echo $job_details["date"]; ?>
                                    </td>
                                    <td><?php
                                        if ($job_details["status"] == 1) {
                                            echo "<span class='label label-danger'>Waiting For Approval</span>";
                                        }
                                        ?>
                                        <?php
                                        if ($job_details["status"] == 6) {
                                            echo "<span class='label label-warning'>Approved</span>";
                                        }
                                        ?>
                                        <?php
                                        if ($job_details["status"] == 7) {
                                            echo "<span class='label label-warning'>Sample Collected</span>";
                                        }
                                        ?>
                                        <?php
                                        if ($job_details["status"] == 8) {
                                            echo "<span class='label label-warning'>Processing</span>";
                                        }
                                        ?>
                                        <?php
                                        if ($job_details["status"] == 2) {
                                            echo "<span class='label label-success'>Completed</span>";
                                        }
                                        ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Customer Details</h3>
                </div>
                <div class="box-body">
                    <div class="col-md-12">
                        <?php /* if ($query[0]['pic'] != NULL) { */ ?>
                        <div class="form-group">
                            <label for="exampleInputFile">Profile :</label><br>
                            <?php
                            $check_pc = substr($query[0]['pic'], 0, 6);
                            if ($check_pc == "https:") {
                                ?>
                                <img src="<?php echo $query[0]['pic']; ?>" onerror="this.src='<?= base_url(); ?>upload/avatar/profile_avatar.png'" class="img-circle admin_job_dtl_img"/>
                            <?php } else { ?>
                                <img class="img-circle admin_job_dtl_img" onerror="this.src='<?= base_url(); ?>upload/avatar/profile_avatar.png'" src="<?php echo base_url(); ?>upload/<?php echo $query[0]['pic']; ?>"/>
                            <?php } ?>
                        </div>
                        <?php /* } */ ?>
                        <div class="form-group">
                            <label for="exampleInputFile">Full Name : </label>
                            <?php echo ucfirst($query[0]["full_name"]); ?>

                        </div>

                        <div class="form-group">
                            <label for="exampleInputFile">Gender : </label>
                            <?php echo ucfirst($query[0]["gender"]); ?>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputFile">Email : </label>
                            <?php echo $query[0]["email"]; ?>

                        </div>
                        <div class="form-group">
                            <label for="exampleInputFile">Mobile : </label>
                            <?php echo $query[0]["mobile"]; ?>

                        </div>
                        <div class="form-group">
                            <label for="exampleInputFile">Date Of Birth : </label>
                            <?php echo $query[0]["dob"]; ?>

                        </div>
                        <div class="form-group">
                            <label for="exampleInputFile">Age : </label>
                            <?php echo $query[0]["age"]; ?>

                        </div>
                        <div class="form-group">
                            <label for="exampleInputFile">Booking-Portal : </label>
                            <?php if ($query[0]["device_type"] == 'android') {
                                echo "Android" . " (" . $query[0]["app_version"] . ")";
                            } if ($query[0]["device_type"] == 'ios') {
                                echo "Ios" . " (" . $query[0]["app_version"] . ")";
                            } if ($query[0]["device_type"] == '') {
                                echo "Web";
                            } ?>

                        </div>
                            <?php if ($query[0]["country"] != NULL) { ?>
                            <div class="form-group">
                                <label for="exampleInputFile">Country : </label>
                                <?php
                                foreach ($country as $cat) {
                                    if ($cat['id'] == $query[0]["country"]) {
                                        echo ucwords($cat['country_name']);
                                    }
                                }
                                ?>
                            </div>
                            <?php } ?>
                            <?php if ($query[0]["state"] != NULL) { ?>
                            <div class="form-group">
                                <label for="exampleInputFile">State : </label>
                                <?php
                                foreach ($state as $cat) {
                                    if ($cat['id'] == $query[0]["state"]) {
                                        echo ucwords($cat['state_name']);
                                    }
                                }
                                ?>
                            </div>
                            <?php } ?>
                            <?php if ($query[0]["city"] != NULL) { ?>
                            <div class="form-group">
                                <label for="exampleInputFile">City : </label>
                                <?php
                                foreach ($city as $cat) {
                                    if ($cat['id'] == $query[0]["city"]) {
                                        echo ucwords($cat['city_name']);
                                    }
                                }
                                ?>
                            </div>
<?php } ?>

                        <div class="form-group">
                            <label for="exampleInputFile">Address : </label><?php echo ucfirst($query[0]["address"]); ?>
                        </div>
                        <small> <a href="<?php echo base_url(); ?>customer-master/customer-edit/<?php echo $query[0]["id"]; ?>"class="btn btn-primary">Edit</a></small>
                        <div class="table-responsive">
                        <table id="example4" class="table table-bordered table-striped">
                            <h3>Family Member</h3>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Relation</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                </tr>
                            </thead>
                            <tbody>
<?php
$cnt = 1;
$temp = 1;
foreach ($family_member as $r_key) {
    ?>
                                    <tr>
                                        <td><?php echo ucwords($r_key["name"]); ?></td>
                                        <td><?php echo ucwords($r_key["relation_name"]); ?></td>
                                        <td><?php echo $r_key["email"]; ?></td>
                                        <td><?php echo ucwords($r_key["phone"]); ?></td>
                                    </tr>
<?php } ?>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div><!-- /.box-body -->


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
                function get_state(val) {

                    $.ajax({
                        url: "<?php echo base_url(); ?>location_master/get_state/" + val,
                        error: function (jqXHR, error, code) {
// alert("not show");
                        },
                        success: function (data) {
//     console.log("data"+data);
                            document.getElementById('state').innerHTML = "";
                            document.getElementById('state').innerHTML = data;

                        }
                    });
                }
            </script>
        </div>



    </section>
</div>