<style>
    tr.odd input.form-control {
        width: 100% !important;
    }
</style>
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            User Call
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li class="active">Remains Booking</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Remains User Book List</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="widget">
                            <?php if (isset($success) != NULL) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">X</button>
                                    <?php echo $success['0']; ?>
                                </div>
                            <?php } ?>

                        </div>
                        <div class="tableclass">
                            <form role="form" action="<?php echo base_url(); ?>user_call_master/index" method="get" enctype="multipart/form-data">
                                <table id="example4" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Name</th>
                                            <th>Mobile</th>
                                            <th>Email</th>
                                            <th>Book Date & Time</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
<!--                                        <tr>
                                            <td><span style="color:red;">*</span></td>
                                            <td><select class="form-control chosen-select" tabindex="-1"  data-placeholder="Select Customer" name="caller">
                                                    <option value="">Select Customer</option>
                                        <?php foreach ($customer_list as $cat) { ?>
                                                                <option value="<?php echo $cat['id']; ?>" <?php
                                            if ($caller_fk == $cat['id']) {
                                                echo "selected";
                                            }
                                            ?> ><?php echo ucwords($cat['full_name']); ?></option>
                                        <?php } ?>
                                                </select></td>
                                            <td><input type="text" placeholder="Call From" class="form-control" name="call_from" value="<?php
                                        if (isset($call_from)) {
                                            echo $call_from;
                                        }
                                        ?>"/></td>
                                            <td><input type="text" placeholder="Call To" class="form-control" name="call_to" value="<?php
                                        if (isset($call_to)) {
                                            echo $call_to;
                                        }
                                        ?>"/></td>
                                            <td><input type="text" placeholder="Direction" class="form-control" name="direction" value="<?php
                                        if (isset($direction)) {
                                            echo $direction;
                                        }
                                        ?>"/></td>
                                            <td><input type="submit" name="search" class="btn btn-success" value="Search" /></td>
                                        </tr>-->
                                        <?php
                                        $cnt = 1;
                                        foreach ($query as $row) {
                                            ?>
                                            <tr>
                                                <td><?php echo $cnt; ?></td>
                                                <td><?php echo $row['customer_name']; ?></td>
                                                <td><?php echo $row['customer_mobile']; ?></td>
                                                <td><?php echo $row['customer_email']; ?></td>
                                                <td><?php echo $row['created_date']; ?></td>
                                                <td>
                                                    <a  href='<?php echo base_url(); ?>remains_book_master/add_book_job/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="View Remains Book Details" > <span class="label label-primary"><i class="fa fa-eye"> </i></span> </a>
                                                    <a  onclick="return confirm('Are you sure you want to spam this booking Details ?');" href='<?php echo base_url(); ?>remains_book_master/remains_spam/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Spam book details" > <span class="label label-danger"><i class="fa fa-trash"></i></span> </a>
                                                </td>

                                            </tr>
                                            <?php
                                            $cnt++;
                                        }if (empty($query)) {
                                            ?>
                                            <tr>
                                                <td colspan="12">No records found</td>
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

                                                    jQuery(".chosen-select").chosen({
                                                        search_contains: true
                                                    });
//  $(".chosen-select-deselect").chosen({ allow_single_deselect: true });
// $("#cid").chosen('refresh');

</script>
<script type="text/javascript">
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
</script>
<script>
    /*	$( document ).ready(function() {
     $.ajax({
     url: "<?php echo base_url(); ?>user_call_master/call_details",
     error: function (jqXHR, error, code) {
     // alert("not show");
     },
     success: function (data) {
     if(data != '') {
     document.getElementById('content').innerHTML = "";
     document.getElementById('content').innerHTML = data;
     $("#myModal").modal('show');
     } else {
     $("#myModal").modal('hide');
     }
     }
     });
     }); */
    $(".branch_select").click(function () {
        $.fancybox.open({
            href: 'add_fancybox?branch=' + old_branch,
            type: 'iframe',
            padding: 5,
            'width': 530,
            'height': 300
        });
    });
</script>
<script>
    $(document).ready(function () {
        var date_input = $('input[name="start_date"]'); //our date input has the name "date"
        var date_input1 = $('input[name="call_date"]'); //our date input has the name "date"

        var container = $('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";
        date_input.datepicker({
            format: 'dd/mm/yyyy',
            container: container,
            todayHighlight: true,
            autoclose: true,

        })
        date_input1.datepicker({
            format: 'dd/mm/yyyy',
            container: container,
            todayHighlight: true,
            autoclose: true,

        })
    })
</script>