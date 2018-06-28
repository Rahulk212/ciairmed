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


</style>

<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Users  Branch Access List
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li>Users  Branch Access List</li>

        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Users  Branch Access List</h3>
                        <a style="float:right;"  href="javascript:void(0);" onclick="$('#branch_model').modal('show');" class="btn btn-primary btn-sm" ><i class="fa fa-plus-circle" ></i><strong > Add</strong></a>
                                                <!--<a style="float:right;" href='<?php echo base_url(); ?>manage_master/manage_csv' class="btn btn-primary btn-sm" ><strong > Export</strong></a>
                                                <a style="float:right;" data-toggle="modal"  data-target="#import"  class="btn btn-primary btn-sm" ><strong > Import</strong></a>-->

                    </div><!-- /.box-header -->



                    <div class="box-body">
                        <?php $session = $this->session->userdata('success'); ?>
                        <div class="widget">
                            <?php if (isset($success) != NULL) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $success; ?>
                                </div>
                            <?php } ?>

                        </div>


                        <div class="tableclass">
                            <?php echo form_open("Branch_Master/Branch_list", array("method" => 'GET')); ?>
                            <table id="example3" class="table table-bordered table-striped">
                                <thead>
                                    <tr>

                                        <th>No</th>
                                        <th>Branch Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>



                                    <?php
                                    $cnt = 1;

                                    foreach ($list as $row) {
                                        /*  echo "<pre>";
                                          print_r($row['test_name']);
                                          exit; */
                                        ?>
                                        <tr>
                                            <td><?php echo $cnt; ?> </td>

                                            <td><?php echo $row['branch_name']; ?></td>

                                    <style>
                                        .fa
                                        {
                                            margin-right:10px;
                                        }
                                    </style>
                                    <td>
                                        <a  href='<?php echo base_url(); ?>Branch_Master/user_branch_delete/<?php echo $userid . '/' . $row['id']; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i class="fa fa-trash-o"></i></a>      
                                    </td>

                                    </tr>
                                    <?php
                                    $cnt++;
                                }
                                ?>

                                </tbody>

                            </table>
                            <div style="text-align:right;" class="box-tools">
                                <ul class="pagination pagination-lm no-margin pull-right">
                                    <?php echo $links; ?>
                                </ul>
                            </div>

                            <?php echo form_close(); ?>
                        </div>





                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<div class="modal fade" id="branch_model" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Change Branch</h4>
            </div>
            <?php echo form_open("Branch_Master/user_branch_add/" . $userid, array("method" => "POST", "role" => "form")); ?>
            <div class="modal-body">
                <?php if (!empty($amount_history_success)) { ?>
                    <div class="widget">
                        <div class="alert alert-success alert-dismissable">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            <?php echo $amount_history_success['0']; ?>
                        </div>
                    </div>
                <?php } ?>
                <?php if (!empty($family_error)) { ?>
                    <div class="widget">
                        <div class="alert alert-danger alert-dismissable">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            <?php echo $family_error['0']; ?>
                        </div>
                    </div>
                <?php } ?>
                <div class="form-group">
                    <label for="recipient-name" class="control-label">Select Branch:</label>

                    <select id="branch" name="branch" class="chosen" style="width: 100%" required="">
                        <option value="">--Select--</option>
                        <?php foreach ($branch_list as $branch) {
                            ?>
                            <option value="<?php echo $branch['id']; ?>"><?php echo ucwords($branch['branch_name']) . " - " . $branch['branch_code']; ?></option>

                            <?php
                        }
                        ?>
                    </select>
                    <span style="color:red;" id="amount_error"></span>
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="amount_submit_btn">Update</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            <?= form_close(); ?>
        </div>

    </div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
<script  type="text/javascript">

                                        jQuery(".chosen-select").chosen({
                                            search_contains: true
                                        });
                                        //  $(".chosen-select-deselect").chosen({ allow_single_deselect: true });
                                        // $("#cid").chosen('refresh');

</script> 
<script type="text/javascript">
    /*  $(function () {
     
     $('#example3').dataTable({
     //"bPaginate": false,
     "bLengthChange": false,
     "bFilter": false,
     "bSort": false,
     "bInfo": false,
     "bAutoWidth": false
     });
     }); */
</script>

<script type="text/javascript">
    window.setTimeout(function () {
        $(".alert").fadeTo(500, 0).slideUp(500, function () {
            $(this).remove();
        });
    }, 4000);</script>