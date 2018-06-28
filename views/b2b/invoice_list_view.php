<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Invoice
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>b2b/invoice_master/generate_invoice"><i class="fa fa-files-o"></i>Invoice</a></li>
        </ol>
    </section>
    <style>
        .chosen-container {
            display: inline-block;
            font-size: 14px;
            position: relative;
            vertical-align: middle;
            width: 100%;
        }
        .full_bg{background: rgba(0,0,0,0.3); width:100%; height:100%; float:left; padding:350px; position:fixed; z-index:9; top:0; bottom:0;}
        .full_bg .loader img{width:70px; height:70px;}
    </style>
    <div class="full_bg" style="display:none;" id="loader_div">
        <div class="loader">
            <center><img src="http://static.heart.org/riskcalc/app/assets/img/loading.gif"></center>
        </div>
    </div>
    <!-- Main content -->

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Invoice</h3>
                        
                        <!--
                        <a style="float:right;margin-right:5px;" href='<?php echo base_url(); ?>test_master/test_csv?city=<?= $city ?>' class="btn btn-primary btn-sm" ><strong > Export</strong></a>
                        <a style="float:right;margin-right:5px;" data-toggle="modal"  data-target="#import"  class="btn btn-primary btn-sm" ><strong > Import</strong></a>-->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                    
                        <div class="tableclass  table-responsive">
						<div class="widget">
                             <?php echo form_open("b2b/invoice_master/generate_invoice",array("method" => "GET")); ?>
                              
								
								
								 <div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;" >
                                       <input type="text"  class="form-control" name="date" placeholder="from Scan Date" value="<?= $date ?>"/>
                                    </div>
                                </div>
								
								 <div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;" >
                                       <input type="text" id="todatese" class="form-control" name="todate" placeholder="To Scan Date" value="<?= $todate ?>"/>
                                    </div>
                                </div>
								
								 <div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;" >
                                       
									   <select name="from" required class="form-control"  >
									   <option value="" >Collect From</option>
									   <?php foreach($laball as $key){ ?>
									    <option value="<?= $key['id']; ?>" <?php if($from== $key['id']){ echo "selected"; } ?>  ><?= $key['name']; ?></option>
									   <?php } ?>
									   </select>
									   
                                    </div>
                                </div>
								
								  
								<div class="form-group pull-right">
                                        <div class="col-sm-12" style="margin-bottom:10px;">
										
										 <button type="submit"  class="btn btn-sm btn-primary" >Search</button>
                                           
                                            <a type="button" href="<?= base_url()."b2b/Logistic/sample_list" ?>" class="btn btn-sm btn-primary" ><i class="fa fa-refresh"></i> Reset</a>
											<?php if($from != ""){ ?>
											<a  style="float:right;margin-left:3px" href="<?= base_url()."b2b/invoice_master/invoice_pdf?date=$date&from=$from&todate=$todate"; ?>" id=""  class="btn btn-sm btn-primary"><i class="fa fa-print"></i><strong>Print</strong></a>
											
                                            <a style="float:right;margin-left:3px" href="<?= base_url()."b2b/invoice_master/invoice_csv?date=$date&from=$from&todate=$todate"; ?>" id=""  class="btn btn-sm btn-primary"><i class="fa fa-download"></i><strong> Export To CSV</strong></a>
											<?php } ?>
											
                                        </div>
                                    </div>
								
								</form>
							</div>	
                            <table id="example4" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Reg.Date</th>
                                        <th>Lab Id</th>
                                        <th>Patient's Name</th>
                                        <th>Investigation</th>
                                        <th>Amt</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    <?php
                                    $cnt =0;
									 $price=0;
                                    foreach ($query as $row) {
                                        $cnt++;
                                        ?>
                                        <tr> 
											<td><?php echo $cnt; ?></td>
                                            <td><?php echo ucwords($row['regdate']); ?></td>
											<td><?=  $row['labid']; ?></td>
											<td><?= ucwords($row['patientname']); ?></td>
											<td><?= $row['testname']; ?></td>
											<td><?= $row['amt']; ?></td>
                                           
                                        </tr>
                                    <?php $price +=$row['amt'];; }
									if (empty($query)) {
                                        ?>
                                        <tr>
                                            <td colspan="8">No records found</td>
                                        </tr>
                                    <?php }else{ ?> 
									
									<tr style="border-top: 3px solid black;">
                                                    <td colspan="5"><b>Total</b></td>
                                                    <td colspan="1"><b>Rs.<?=$price?></b></td>
                                                </tr>
									
									<?php }?>
                                </tbody>
                            </table>
                            
                        </div>
                       
                    </div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script type="text/javascript">
    $(function () {
		 var date_input = $('#todatese'); //our date input has the name "date"

                        var container = $('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";
                        date_input.datepicker({
                            format: 'dd/mm/yyyy',
                            container: container,
                            todayHighlight: true,
                            autoclose: true,
                        });
        $("#example1").dataTable();
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
