<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= ucfirst($lab_details[0]["name"]) ?> 
            <small>#<?= $lab_details[0]["id"] ?></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Credit Manage</li>
        </ol>
    </section>
    <!--        <div class="pad margin no-print">
              <div class="callout callout-info" style="margin-bottom: 0!important;">												
                <h4><i class="fa fa-info"></i> Note:</h4>
                This page has been enhanced for printing. Click the print button at the bottom of the invoice to test.
              </div>
            </div>-->

    <!-- Main content -->
    <section class="invoice">
        <!-- title row -->
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    <i class="fa fa-eyedropper"></i> <?= ucfirst($lab_details[0]["name"]) ?> 
                    <small class="pull-right">Date: <?= date("d/m/Y"); ?></small>
                </h2>
            </div><!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info">
            <?php /* <div class="col-sm-4 invoice-col">
              From
              <address>
              <strong>Admin, Inc.</strong><br>
              795 Folsom Ave, Suite 600<br>
              San Francisco, CA 94107<br>
              Phone: (804) 123-5432<br/>
              Email: info@almasaeedstudio.com
              </address>
              </div><!-- /.col -->
              <div class="col-sm-4 invoice-col">
              To
              <address>
              <strong>John Doe</strong><br>
              795 Folsom Ave, Suite 600<br>
              San Francisco, CA 94107<br>
              Phone: (555) 539-1037<br/>
              Email: john.doe@example.com
              </address>
              </div><!-- /.col --> */ ?>
            <div class="col-sm-4 invoice-col">
                <b>Lab Id:</b> #<?= $lab_details[0]["id"] ?><br/>
                <b>Payment Due:</b> 2/22/2014<br/>
                <b>Account:</b> 968-34567
            </div><!-- /.col -->
            <div class="col-sm-5 invoice-col">
            </div><!-- /.col -->
            <div class="col-sm-3 invoice-col">
                <h3 style="margin-top:0px"><b style="color:green;">Credit:</b> Rs.<?php
                    $credited = 0;
                    $due = 0;
                    if ($sample_credit[0]["total"] > 0) {
                        $credited = $sample_credit[0]["total"];
                    } else {
                        $due = $sample_credit[0]["total"];
                    } echo $credited;
                    ?><br/></h3>
                <h3 style="margin-top:0px"><b style="color:red;">Due:</b> Rs.<?= 0-$due; ?><br/></h3>
            </div><!-- /.col -->
        </div><!-- /.row -->
        <div class="row no-print">
            <div class="col-xs-12">
            </div>
        </div>
        <!-- Table row -->
        <br/>
        <div class="row">

            <div class="col-xs-10 table-responsive">
                <p class="lead">History</p>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Credit</th>
                            <th>Debit</th>
                            <th>Total</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $cnt = 1;
                        foreach ($sample_credit as $key) {
                            ?>
                            <tr>
                                <td><?= $cnt; ?></td>
                                <td><?= "Rs." . $key["credit"] ?></td>
                                <td><?= "Rs." . $key["debit"] ?></td>
                                <td><?= "Rs." . $key["total"] ?></td>
                                <td><?= $key["transaction"] ?></td>
                                <td><?php echo date("d/m/Y H:i:s", strtotime($key["created_date"])); ?></td>
                                <td><?= ucfirst($key["note"]) ?></td>
                            </tr>
                        <?php $cnt++; } ?>
                    </tbody>
                </table>
            </div><!-- /.col -->
        </div><!-- /.row -->

        <!-- this row will not appear when printing -->

    </section><!-- /.content -->
    <div class="clearfix"></div>
</div><!-- /.content-wrapper -->
<div class="modal fade" id="add_model" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Payment</h4>
            </div>
            <?php echo form_open("b2b/Amount_manage/add_payment/" . $id, array("method" => "POST", "role" => "form")); ?>
            <div class="modal-body">
                <div class="form-group">
                    <label for="recipient-name" class="control-label">Amount:</label>
                    <input type="text" name="amount" value="" required="" class="form-control number"/>
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="control-label">Amount:</label>
                    <select class="form-control" name="type">
                        <option value="credit">Credit</option>
                        <option value="debit">Debit</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="control-label">Note:</label>
                    <textarea class="form-control" name="note"></textarea>
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
<script>
$('.number').keypress(function (event) {
        var $this = $(this);
        if ((event.which != 46 || $this.val().indexOf('.') != -1) &&
                ((event.which < 48 || event.which > 57) &&
                        (event.which != 0 && event.which != 8))) {
            event.preventDefault();
        }

        var text = $(this).val();
        if ((event.which == 46) && (text.indexOf('.') == -1)) {
            setTimeout(function () {
                if ($this.val().substring($this.val().indexOf('.')).length > 3) {
                    $this.val($this.val().substring(0, $this.val().indexOf('.') + 3));
                    
                }
            }, 1);
        }

        if ((text.indexOf('.') != -1) &&
                (text.substring(text.indexOf('.')).length > 2) &&
                (event.which != 0 && event.which != 8) &&
                ($(this)[0].selectionStart >= text.length - 2)) {
            event.preventDefault();
        }
    });

    $('.number').bind("paste", function (e) {
        var text = e.originalEvent.clipboardData.getData('Text');
        if ($.isNumeric(text)) {
            if ((text.substring(text.indexOf('.')).length > 3) && (text.indexOf('.') > -1)) {
                e.preventDefault();
                $(this).val(text.substring(0, text.indexOf('.') + 3));
            }
        } else {
            e.preventDefault();
        }
    });
</script>