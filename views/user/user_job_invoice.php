<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="invc_sctn_1">
                <img src="<?= base_url(); ?>img/invoice_right.png">
                <h1>Thank you for your order</h1>
                <h2>Your Booking ID is <span><?php echo $query[0]['order_id']; ?></span></h2>
                <h5>We have sent you an email confirmation at : <span><?php echo $query[0]['email']; ?></span></h5>
                <p>In case of any questions, please call us at: <b>+91 8101 161616</b></p>
            </div>
            <div class="invc_sctn_2">
                <h2>Booking Summary <a href="<?php echo base_url(); ?>job_master/pdf_invoice/<?php echo $query[0]['id']; ?>" target="_blank" style="float:right;" id="print_btn" class="btn btn-dark btn-theme-colored btn-flat pull-right">Print</a></h2>
                <div class="invc_sctn_2_brdr_div">
                    <div class="invc_brdrdiv_titl_div">
                        <span>Booking ID : <?php echo $query[0]['order_id']; ?></span>
                    </div>
                    <div class="invc_sctn2_data_div">
                        <div class="invc_sctn2_full">
                            <div class="col-sm-3">
                                <p>Booking Date :</p>
                            </div>
                            <div class="col-sm-9">
                                <p><?php echo date("l,F d,Y", strtotime($query[0]['date'])); ?> </p>
                            </div>
                        </div>
                        <div class="invc_sctn2_full">
                            <div class="col-sm-3">
                                <p>Sample Collection time :</p>
                            </div>
                            <div class="col-sm-9">
                                <?php
                                $s_time = date('h:i A', strtotime($time[0]["start_time"]));
                                $e_time = date('h:i A', strtotime($time[0]["end_time"]));
                                ?>
                                <p><?php echo $s_time . "-" . $e_time; ?> </p>
                            </div>
                        </div>
                        <div class="invc_sctn2_full">
                            <div class="col-sm-3">
                                <p>Billing Name :</p>
                            </div>
                            <div class="col-sm-9">
                                <p><?php echo ucfirst($query[0]['full_name']); ?> </p>
                            </div>
                        </div>
                        <div class="invc_sctn2_full">
                            <div class="col-sm-3">
                                <p>Registered mobile no. :</p>
                            </div>
                            <div class="col-sm-9">
                                <p><?php echo "(+91) " . $query[0]['mobile']; ?> </p>
                            </div>
                        </div>
                        <div class="invc_sctn2_full">
                            <div class="col-sm-3">
                                <p>Sample collection address :</p>
                            </div>
                            <div class="col-sm-9">
                                <p><?php
                                    if (empty($query[0]['address'])) {
                                        echo $query[0]['address1'];
                                    } else {
                                        echo $query[0]['address'];
                                    }
                                    ?> </p>
                            </div>
                        </div>
                        <div class="invc_sctn2_full">
                            <div class="col-sm-3">
                                <p>Email ID :</p>
                            </div>
                            <div class="col-sm-9">
                                <p><?php if($query[0]['email']!="noreply@airmedlabs.com"){ echo $query[0]['email']; } ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="invc_sctn_3">
                <div class="row">
                    <h2>Order Summary</h2>
                    <div class="col-sm-6">
                        <div class="incv_ordrsmry_full">
                            <div class="invc_ordr_lft_div">
                                <p><?php echo $query[0]['full_name']; ?> <br/> <?php
                                    if (!empty($query[0]['age'])) {
                                        echo $query[0]['age'];
                                        ?> years /<?php } ?> <?php
                                    if ($query[0]['gender'] == 'male') {
                                        echo "M";
                                    } else if ($query[0]['gender'] == 'female') {
                                        echo "F";
                                    }
                                    ?></p>
                            </div>
                            <div class="invc_ordr_rgt_div">
                                <p><?php echo $fasting; ?> </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <td>Test/Package</td>
<!--									<td>market price</td>-->
                                    <td class="invc_our_prc_wdth">our price</td>
                                </tr>
                                <?php
//$cnt=0; foreach($book_list[$cnt] as $book) { 
                                $cou = count($book_list);
                                $ttl_price = 0;
                                for ($cnt = 0; $cnt < $cou; $cnt++) {
                                    $ttl_price += $book_list[$cnt][0]['price'];
                                    ?>
                                    <tr>
                                        <td>
                                            <p><?php echo ucfirst($book_list[$cnt][0]['book_name']); ?><?php if ($query[0]["price"] == 0) {
                                    echo "<br><small style='color:green'>(Active Package)</small>";
                                } ?></p>
    <!--										<p>lipid profile</p>-->
                                        </td>
    <!--									<td>
                                                <p>Rs. 123</p>
                                        </td>-->
                                        <td>Rs. <?php if ($query[0]['price'] != '0') {
                                    echo $book_list[$cnt][0]['price'];
                                } else {
                                    echo "0";
                                } ?></td>
                                    </tr>
                                        <?php } ?>
                                        <?php if ($ttl_price < 300) { ?>
                                    <tr>
                                        <td><p>Sample Collection Charge</p></td>
                                        <td>Rs. <?php
                                        if ($ttl_price < 300) {
                                            echo 100;
                                        } else {
                                            echo 0;
                                        }
                                        ?></td>
                                    </tr>
<?php } ?>
                            </table>
                        </div> 
                    </div>
                </div>
                <div class="invc_sctn_3_full_back">
                    <p>Total Amount <span>Rs. <?php echo $query[0]['price']; ?></span></p>
                </div>
            </div>
            <div class="invc_sctn_4">
                <h2>Payment Summary</h2>
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <td>Payment Due Date</td>
                            <td>Mode of Payment</td>
                            <td>Payment Via</td>
                            <td>Total Amount</td>
                        </tr>
                        <tr>
                            <td>-</td>
                            <td><?php
                                if ($query[0]['price'] != '0') {
                                    if ($query[0]['payment_type'] == 'Cash On Delivery' && $query[0]['price'] == $query[0]['payable_amount']) {
                                        echo 'Cash on Sample Collection';
                                    } else if ($query[0]['payment_type'] == 'Cash On Delivery' && $query[0]['price'] != $query[0]['payable_amount'] && $query[0]['payable_amount'] != 0) {
                                        echo 'Wallet + Cash on Sample Collection';
                                    } else if ($query[0]['payment_type'] == 'Cash On Delivery' && $query[0]['price'] != $query[0]['payable_amount'] && $query[0]['payable_amount'] == 0) {
                                        echo 'Wallet';
                                    } else if ($query[0]['payment_type'] == 'PayUMoney' && $wallet_manage[0]['debit'] > 0) {
                                        echo 'Wallet + Pay Online';
                                    } else if ($query[0]['payment_type'] == 'PayUMoney' && $wallet_manage[0]['debit'] == 0) {
                                        echo "Pay Online";
                                    }
                                } else {
                                    echo "-";
                                }
                                ?> </td>
                            <td><?php
                                if ($query[0]['price'] != '0') {
                                    if ($query[0]['payment_type'] == 'Cash On Delivery') {
                                        echo '-';
                                    } else {
                                        echo "PayUmoney";
                                    }
                                } else {
                                    echo "-";
                                }
                                ?></td>
                            <td>Rs. <?php echo $query[0]['price']; ?></td>
                        </tr>
                    </table>
                </div> 
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function PrintElem()
    {
        $("#print_btn").hide();
        Popup($(".print_invoice").html());
        //  Popup($(elem).append($(elem).clone()).html());
    }

    function Popup(data)
    {
        var mywindow = window.open('', 'InternetAds ', 'height=600,width=800');
        mywindow.document.write(data);

        //mywindow.document.write('</body></html>');

        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10

        //function prin(){

        // }
        //  mywindow.print();
        //   mywindow.close();

        $("#print_btn").show();
        return true;
    }

</script>