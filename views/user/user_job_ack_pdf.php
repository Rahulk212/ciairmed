<html>
    <head>
        <meta charset="utf-8">
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <style>
            body {font-family: 'Roboto', sans-serif;}
            .pdf_container {width: 970px; margin: 0 auto;}
            .main_set_pdng_div {width: 100%; float: left; padding: 50px 0;}
            .brdr_full_div {float: left; padding: 10px; width: 100%;}
            .full_div {width: 100%; float: left;}
            .header_full_div {float: left; padding: 6px 40px; width: 92%; border-bottom: 1px solid #000000;}
            .set_logo {width: 180px; float: right;}
            .rgtsign_img_divfull {width: 100%; float: left; text-align: center;}
            .rgtsign_img_divfull h1 { font-size: 20px; color: #3b3b3b; font-weight: bold; margin-bottom: 5px; text-transform: uppercase; margin-top: 15px;}
            .rgtsign_img_divfull h2 {color: #3b3b3b; font-size: 16px; margin: 0; font-weight: normal;}
            .rgtsign_img_divfull h2 span {color: #046604;}
            .rgtsign_img_divfull h5 {color: #3b3b3b; font-size: 14px; font-weight: normal; margin-bottom: 0; margin-top: 5px;}
            .rgtsign_img_divfull h5 span {color: #046604;}
            .rgtsign_img_divfull p {color: #3b3b3b; font-size: 12px; margin-top: 5px;}
            .invc_brdrdiv_titl_div {background-color: #f6f6f6; border: 1px solid #cccccc; float: left; padding: 10px 15px; width: 96.8%;}
            .invc_brdrdiv_titl_div_span {font-size: 14px; color: #000; font-weight: bold; padding: 10px 15px;}
            .mdl_tbl_full { margin-bottom: 5px; padding: 3px 15px; width: 100%; border-bottom: 1px solid #cccccc; display: table; border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc; border-right: 1px solid #cccccc;}
            .mdl_tbl_full tr td {padding: 3px 0; font-size: 12px;}
            .mdl_tbl_full tr td:nth-of-type(1) {width: 300px; color: #777777; text-transform: uppercase;}
            .mdl_tbl_full tr {margin-bottom: 10px;}
            .bookng_smry_titl {color: #373737; float: left; font-size: 16px; font-weight: bold; margin: 0 0 10px 0;}
            .ordr_smry_full {width: 100%; float: left;}
            .ordr_smry_cl6_lft {border: 1px solid #f4f4f4; display: table; float: left; margin-right: 15px; width: 46.2%;}
            .ordrsmry_cl6_back {background-color: #f4f4f4; float: left; padding: 8px; text-align: center; vertical-align: middle; width: 125px;}
            .ordrsmry_cl6_back p {font-size: 14px;}
            .ordrsmry_cl6_dotted_brdr {float: left; padding: 5px; vertical-align: middle;}
            .ordrsmry_cl6_dotted_brdr p {border: 1px dotted #ededed; margin: 0; min-height: 55px; text-align: center; font-size: 14px; padding-top: 4px;}
            .ordr_smry_cl6_rgt {border: 1px solid #f4f4f4; float: left; margin-left: 15px; width: 48.2%;}
            .ordrsmry_cl6_tbl {float: left; width: 100%; border-collapse: collapse;}
            .ordrsmry_cl6_tbl tr td {padding: 5px 10px; font-size: 12px;}
            .ordrsmry_cl6_tbl tr td {border-right: 1px solid #f4f4f4; border-bottom: 1px solid #f4f4f4;}
            .ordrsmry_cl6_tbl tr td:last-child {border-right: none;}
            .ordrsmry_cl6_tbl tr:last-child td {border-bottom: none;}
            .ordrsmry_cl6_tbl tr td:nth-of-type(1) {text-transform: uppercase;}
            .invc_sctn_3_full_back {background-color: #838383; float: right; margin-top: 20px; padding: 10px 15px; text-align: right; width: 95%; margin-bottom: 5px;}
            .invc_sctn_3_full_back p {color: #ffffff; font-weight: bold; margin: 0;}
            .invc_sctn_3_full_back p span {color: #ffe439; margin-left: 20px;}
            .pymntsmry_tbl {display: table; width: 100%; border-collapse: collapse; margin-bottom: 1px;}
            .pymntsmry_tbl tr {border: 1px solid #f4f4f4;}
            .pymntsmry_tbl tr td {border: 1px solid #f4f4f4;}
            .pymntsmry_tbl tr:nth-of-type(1) {background-color: #f4f4f4;}
            .pymntsmry_tbl tr:nth-of-type(1) td {font-weight: bold;}
            .pymntsmry_tbl tr td {padding: 8px;}
            .pymntsmry_tbl tr:last-child td:last-child {color: #d01130; font-size: 18px; font-weight: bold;}
            .foot_num_div {width: 100%; float: left; padding-bottom: 10px; border-bottom: 2px dotted #E30026;}
            .foot_num_p {text-align: center; margin-bottom: 10px;}
            .foot_num_p span {background-color: #E30026; border-radius: 25px; padding: 3px 15px; color: #fff;}
            .foot_lab_p {margin: 0; text-align: center; text-transform: uppercase;}
            .lst_ison_ul {display: inline-block; padding: 0; text-align: center; width: 100%; amrgin-top: 5px;}
            .lst_ison_ul li {display: inline-block; margin-right: 15px;}
            .lst_icon_spn_back {background-color: #e30026; border-radius: 50%; float: left; height: 16px; margin-right: 9px; padding: 4px; width: 16px;}
            .lst_icon_spn_back .fa {color: #fff;}
            .lst_airmed_mdl {float: left; margin-bottom: 0; margin-top: 9px; text-align: center; width: 100%;}
            .lst_31_addrs_mdl {float: left; margin: 0 0 0 0; text-align: center; width: 100%;}
            .main_set_pdng_div_panel {width: 100%; float: left; padding: 20px 0;}
            .header_full_div_panel {float: left;text-align: center; padding: 6px 40px; width: 92%; border-bottom: 1px solid #000000;}

        </style>
    </head>


    <div class="rgtsign_img_divfull">
        <h1>Acknowledgment</h1>
        <h2>Your Booking ID is <span><?php echo $jid . "(" . $query[0]['order_id'] . ")"; ?></span></h2>
        <p>In case of any questions, please call us at: <b>+91 8101 161616</b></p>
    </div>

    <h2 class="bookng_smry_titl">Booking Summary </h2>
    <div class="invc_brdrdiv_titl_div">
        <span class="invc_brdrdiv_titl_div_span">Booking ID : <?php echo $jid . "(" . $query[0]['order_id'] . ")"; ?></span>
    </div>
    <table class="mdl_tbl_full">
        <tbody>
            <tr>
                <td>Booking Date :</td>
                <td><b><?php echo date("l,F d,Y", strtotime($query[0]['date'])); ?> </b></td>
            </tr>
            <tr>
                <td>Sample Collection time :</td>
                <td><b><?php
                        $s_time = date('h:i A', strtotime($time[0]["start_time"]));
                        $e_time = date('h:i A', strtotime($time[0]["end_time"]));
                        ?>
                        <?php echo $s_time . "-" . $e_time; ?></b></td>
            </tr>
            <?php if ($test_for == 'Self') { ?>
                <tr>
                    <td>Billing Name :</td>
                    <td><b><?php echo ucfirst($query[0]['full_name']); ?> </b></td>
                </tr>
            <?php } else { ?>
                <tr>
                    <td>Billing Name :</td>
                    <td><b><?php echo ucfirst($relation[0]['name']); ?> </b></td>
                </tr>
                <tr>
                    <td>Care of:</td>
                    <td><b><?php echo ucfirst($query[0]['full_name']); ?> </b></td>
                </tr>

            <?php }
            ?> 
            <tr>
                <td>Referred By :</td>
                <td><b><?php echo ucfirst($query[0]['dname']); ?> </b></td>
            </tr>
            <tr>
                <td>Age/Gender :</td>
                <td><b><?php
                        if (!empty($query[0]['f_age'])) {
                            echo $query[0]['f_age'];
                        } else if (!empty($query[0]['age'])) {
                            echo $query[0]['age'];
                            ?> <?php } ?><?php
                        if (strtoupper($query[0]['gender']) == 'MALE') {
                            echo "/M";
                        } else if (strtoupper($query[0]['gender']) == 'FEMALE') {
                            echo "/F";
                        }
                        ?> </b></td>
            </tr>
            <tr>
                <td>Registered mobile no. :</td>
                <td><b><?php echo "(+91) " . $query[0]['mobile']; ?> </b></td>
            </tr>
            <tr>
                <td>Sample collection address :</td>
                <td><b><?php
                        if (empty($query[0]['address'])) {
                            echo $query[0]['address1'];
                        } else {
                            echo $query[0]['address'];
                        }
                        ?></b></td>
            </tr>
            <tr>
                <td>Email ID :</td>
                <td><b><?php
                        if ($query[0]['email'] != "noreply@airmedlabs.com") {
                            echo $query[0]['email'];
                        }
                        ?></b></td>
            </tr>
        </tbody>
    </table>

    <h2 class="bookng_smry_titl">Order Summary</h2>
    <div class="ordr_smry_full">

        <div class="ordr_smry_cl6_rgt">
            <table class="ordrsmry_cl6_tbl">
                <tbody>
                    <tr>
                        <td><b>Test/Package</b></td>
                        <td><b>Our Price</b></td>
                    </tr>
                    <?php
                    //echo "<pre>"; print_r($book_list); die();
                    $cou = count($book_list);
                    $ttl_price = 0;
                    for ($cnt = 0; $cnt < $cou; $cnt++) {
                        $ttl_price += $book_list[$cnt][0]['price'];
                        ?>
                        <tr>
                            <td><?php echo ucfirst($book_list[$cnt][0]['book_name']); ?><?php
                                if ($book_list[$cnt][0]['panel_fk'] > 0) {
                                    echo " (Panel)";
                                }
                                ?><?php
                                if ($query[0]["price"] == 0) {
                                    echo "<br><small style='color:green'>(Active Package)</small>";
                                }
                                ?></td>
                            <td>Rs. <?php
                                if ($query[0]['price'] != '0') {
                                    echo number_format((float) $book_list[$cnt][0]['price'], 2, '.', '');
                                } else {
                                    echo "0.00";
                                }
                                ?></td>
                        </tr>
                    <?php } ?>
                    <?php if ($query[0]["collection_charge"] == 1) { ?>
                        <tr>
                            <td>SAMPLE COLLECTION CHARGE</td>
                            <td>Rs.100</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <h2 class="bookng_smry_titl"><br>Payment Summary</h2>
    <table class="pymntsmry_tbl">
        <tbody>
            <tr>
                <td><b>Received Date</b></td>
                <td><b>Mode of Payment</b></td>
                <td><b>Payment Via</b></td>
                <td><b>Amount</b></td>
            </tr>
            <?php $received_amount = 0; ?>
            <?php
            $ttl = 0;
            if ($phlebo_collect[0]["amount"] > 0) {
                ?>
                <tr>
                    <td><?= $phlebo_collect[0]["createddate"] ?></td>
                    <td>Cash</td>
                    <td></td>
                    <td>Rs.<?= $phlebo_collect[0]["amount"] ?><?php $received_amount = $received_amount + $phlebo_collect[0]["amount"]; ?></td>
                </tr>
            <?php } ?>
            <?php if ($query[0]['discount'] > '0') { ?>
                <tr>
                    <td><?php echo date('d-m-Y h:i A', strtotime($query[0]['date'])); ?></td>
                    <td>-</td> 
                    <td>Discount</td>
                    <?php $d_price = $query[0]['price'] * $query[0]['discount'] / 100; ?>
                    <td>Rs.<?= number_format((float) $d_price, 2, '.', ''); ?><?php $received_amount = $received_amount + $d_price; ?></td>
                </tr>
            <?php } ?>
            <?php foreach ($online_pay as $key) { ?>
                <tr>
                    <td><?php echo date('d-m-Y h:i A', strtotime($key["paydate"])); ?></td>
                    <td>Online</td> 
                    <td>PayuMoney(Transaction Id:<?= $key["payomonyid"]; ?>)</td>
                    <td>Rs.<?= number_format((float) $key['amount'], 2, '.', ''); ?><?php $received_amount = $received_amount + $key["amount"]; ?></td>
                </tr>
            <?php } ?>
            <?php foreach ($wallet_manage as $key) { ?>
                <tr>
                    <td><?php echo date('d-m-Y h:i A', strtotime($key["created_time"])); ?></td>
                    <td>Wallet</td> 
                    <td>-</td>
                    <td>Rs.<?= number_format((float) $key['debit'], 2, '.', ''); ?><?php $received_amount = $received_amount + $key["debit"]; ?></td>
                </tr>
            <?php } ?>
            <?php foreach ($job_master_receiv_amount as $key) { ?>
                <tr>
                    <td><?php echo date('d-m-Y h:i A', strtotime($key["createddate"])); ?></td>
                    <td><?php
                        if ($key["type"] == 'User Pay') {
                            echo "Online";
                        } else {
                            echo ucfirst($key["payment_type"]);
                        }
                        ?></td>  
                    <td><?php
                        if (!empty($key["transaction_id"])) {
                            echo "PayuMoney (Transaction Id: " . $key["transaction_id"] . ")";
                        } else {
                            echo "-";
                        }
                        ?></td>
                    <td>Rs.<?= number_format((float) $key['amount'], 2, '.', ''); ?><?php $received_amount = $received_amount + $key["amount"]; ?></td>
                </tr>
            <?php } ?>
            <?php /*
              <tr style="border-top:3px solid black;">
              <td colspan="3"><b><center>Total Amount</center></b></td>
              <td>Rs.<?= number_format((float) $query[0]['price'], 2, '.', ''); ?></td>
              </tr>
              <tr>
              <td colspan="3"><b><center>Total Received Amount</center></b></td>
              <td>Rs.<?= number_format((float) $received_amount, 2, '.', ''); ?></td>
              </tr>
             */ ?>
            <?php foreach ($query as $key) { ?>
                <tr style="border:none;">
                    <td colspan="3" style="border: none;"><b style="color:#d01130;"><center>Due Amount</center></b></td> 
                    <td style="border: none;">Rs.<?= number_format((float) $key["payable_amount"], 2, '.', ''); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>