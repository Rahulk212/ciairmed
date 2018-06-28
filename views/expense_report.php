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
            Expense Report
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li>Payment Report</li>

        </ol>
    </section>
 <?php $start_date = $_GET['start_date'];
            $end_date = $_GET['end_date'];
            $branch_data= $_GET['branch'];
            $city= $_GET['city'];
           
   ?>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
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
                        <!-- Search start-->
                        <?php echo form_open("expense_master/expense_report", array("method" => "GET", "role" => "form")); ?>
                        <div class="row">
                            <div class="col-sm-2">
                                <input type="text" name="start_date" placeholder="Select date" class="form-control datepicker-input" id="date"  value="<?= $start_date ?>" style="width:190px;" />
                            </div>

                            <div class="col-sm-2">
                                <input type="text" name="end_date" placeholder="End date" class="form-control datepicker-input" id="date1"  value="<?= $end_date ?>" style="width:190px;"/>
                            </div>
                            
                            <div class="col-sm-2"> 
                                <select class="form-control chosen-select" data-placeholder="Select city" tabindex="-1" name="city" onchange="get_branch1(this.value);" style="width:190px;">
                                    <option value="" >All City</option>
                                    <?php foreach ($city_list as $cities) { ?>
                                        <option value="<?php echo $cities['id']; ?>" <?php
                                        if ($city == $cities['id']) {
                                            echo " selected ";
                                        } if($city == '') {
                                            if($cities['id'] ==$city) {
                                                echo "selected";
                                            }
                                        }
                                        ?>><?php echo ucwords($cities['name']); ?></option>
                                    <?php } ?>
                                </select>
                            </div>
<?php //echo "<prE>";print_r($branch_list);die;?>
                            <div class="col-sm-2"> 
                                <select class="form-control chosen" data-placeholder="Select Branch" tabindex="-1" name="branch" id="branch1">
                                    <option value="" >All Branch</option>
                                    <?php foreach ($branch_list as $branchh) { if(!empty($branchs)) { if(in_array($branchh['id'],$branchs)) { ?>
                                    <option value="<?php echo $branchh['id']; ?>" <?php
                                    
                                        if ($branch_data == $branchh['id']) {
                                            echo " selected ";
                                        }
                                        ?>><?php echo ucwords($branchh['branch_name']) ?></option>
                                    <?php } ?>
                                    <?php } else { ?>
                                        <option value="<?php echo $branchh['id']; ?>" <?php
                                        if ($branch_data == $branchh['id']) {
                                            echo " selected ";
                                        }
                                        ?>><?php echo ucwords($branchh['branch_name']); ?></option>
                                    <?php } } ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <input type="submit" name="search" class="btn btn-success" value="Search" />
                            </div>
                        </div>
                        <br>
                        <?php echo form_close(); ?>
                        <!--Search end-->
                        
<div class="tableclass"> 
      
                            <div class="table-responsive" id="prnt_rpt">
                                <?php if($start_date != '' || $end_date !='' || $branch_data !='' || $city !='' ){ ?>
                                <table id="example4" class="table table-bordered table-striped">
                                    <h2><?php if (trim($start_date) != '' || trim($end_date) != '') { ?><?= $start_date ?> To <?= $end_date ?><?php } ?></h2>
                                    <?php
                                    $temp = array();
                                    $tep = 0;
                                    foreach ($query as $val) { //if($val['bid'] == $branch['id']) {  
                                   // echo "<pre>";print_r($query);die;
                                        ?>
                                        <?php
                                        
                                        if (!in_array($val['branch_fk'], $temp)) {
                                            
                                            if($tep == '1'){ ?>
                                                <tr style="background-color: lightcoral; color: white;">
                                                    <td>Total</td>
                                                     <td> </td>
                                                      <td></td>
                                                       <td></td>
                                                        <td></td>
                                                     <td><?php  echo "Rs.". $gross;?> </td>
                                                    
                                                </tr>
                                            <?php }
                                            $tep ='1';
                                            array_push($temp, $val['branch_fk']); $gross=0; ?>
                                            <thead>
                                                
                                                <tr>
                                                    <th colspan="3"><h3><?php echo $val['branch_name']; ?></h3></th>
                                                </tr>

                                                <tr>
                                                    <th><h4>No</h4></th>
                                                    <th><h4>Name</h4></th>
                                                    <th><h4>Category Name</h4></th>
                                                    <th><h4>Description</h4></th>
                                                    <th><h4>Expense Date</h4></th>
                                                    
                                                    <th><h4>Amount</h4></th>
                                                
                                                </tr>
                                            </thead>
                                       <?php }     $gross += $val['total'];                                       ?>
                                           
                                        <tbody>
                                            <tr>
                                              <td><?php echo $val['id']; ?></td>

                                                <td><?php echo $val['name']; echo $name;?></td>
                                                <td><?php echo $val['CategoryName'];?></td>
                                                <td><?php echo $val['description'];?></td>
                                                <td><?php $old_date =$val['expense_date'];$new_date = date('d-m-Y',strtotime($old_date)); echo $new_date;?></td>
                                                 <td><?php echo "Rs.". $val['total'];?></td>
                                                 
                                               
                                            </tr>
                                            
                                        <?php } if(!empty($query)) { ?>
                                            <tr style="background-color: lightcoral; color: white;">
                                                    <td>Total</td>
                                                   
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                 <td><?php echo "Rs.".$gross;?></td>
                                                
                                                </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <?php } ?>
                            </div>
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
    $(function () {
                                            $('.chosen').chosen();
                                        });

</script> 
<script type="text/javascript">
    $(function () {

        $('#example3').dataTable({
            //"bPaginate": false,
            "bLengthChange": false,
            "bFilter": false,
            "bSort": false,
            "bInfo": false,
            "bAutoWidth": false
        });
    });
    function get_branch1(val) {
        $.ajax({
            url: '<?php echo base_url(); ?>job_master/get_branch',
            type: 'post',
            data: {city: val},
            success: function (data) {
                var test = "<option value=''>Select Branch</option>"+data;
                $("#branch1").html(test);
                $('.chosen').trigger("chosen:updated");

            },
            error: function (jqXhr) {
                $("#branch1").html("");
            },
            complete: function () {
            },
        });
    }
</script>