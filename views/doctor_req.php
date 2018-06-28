<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Doctor Request
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="javascript:void(0);"><i class="fa fa-dashboard"></i>Master</a></li>
            <li><a href="javascript:void(0);"><i class="fa fa-users"></i> Doctor Request</a></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <?php /* <a style="float:right;" href='<?php echo base_url(); ?>wallet_master/export_csv/<?php echo $customerfk; ?>' class="btn btn-primary btn-sm" ><i class="fa fa-download" ></i> <strong > Export To CSV</strong></a>
                        <a style="float:right;margin-right: 10px;" href='<?php echo base_url(); ?>wallet_master/wallet_update' class="btn btn-primary btn-sm" ><i class="fa fa-plus-circle" ></i>  <strong> Update Wallet</strong></a> */ ?>
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
                        <div class="tableclass">
                            <form role="form" action="<?php echo base_url(); ?>wallet_master/account_history" method="get" enctype="multipart/form-data">
                                <table id="example3" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Doctor Name</th>
                                            <th>Patient Name</th>
                                            <th>Mobile</th>
                                            <th>Description</th>
                                            <th>Created Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                  <?php /*      <tr> 
                                            <td><span style="color:red;">*</span></td>
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
                                            <td><input class="form-control" type="text" name="credit" placeholder="Credit" value="<?php
                                                if (isset($credit)) {
                                                    echo $credit;
                                                }
                                                ?>" /></td>
                                            <td></td>
                                            <td><input class="form-control" type="text" name="debit" placeholder="Debit" value="<?php
                                                if (isset($debit)) {
                                                    echo $debit;
                                                }
                                                ?>" /></td>
                                            <td><input class="form-control" type="text" name="total" placeholder="Total" value="<?php
                                                if (isset($total)) {
                                                    echo $total;
                                                }
                                                ?>" /></td>
                                            <td>
                                                <input type="submit" name="search" class="btn btn-success" value="Search" />
                                            </td>
                                        </tr> */ ?>
                                        <?php
                                        $cnt = 0;
                                        foreach ($query as $row) {
                                            $cnt++;
                                            ?> 

                                            <tr>
                                                <td><?php echo $cnt+$pages; ?></td>
                                                <td><?php echo ucwords($row['full_name']); ?></td>
                                                <td><?php echo ucwords($row['patient_name']); ?></td>
                                                <td><?php echo ucwords($row['mobile']); ?></td>
                                                <td><?php echo ucwords($row['desc']); ?></td>
                                                <td><?php echo ucwords($row['createddate']); ?></td>
                                                <td><a href="<?=base_url();?>doctor_req/delete/<?php echo ucwords($row['id']); ?>" onclick="confirm('Are you sure?')">Delete</a></td>
                                            </tr>
                                        <?php } if ($cnt == '0') {
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

    jQuery(".chosen-select").chosen({
        search_contains: true
    });
    //  $(".chosen-select-deselect").chosen({ allow_single_deselect: true });
    // $("#cid").chosen('refresh');

</script>