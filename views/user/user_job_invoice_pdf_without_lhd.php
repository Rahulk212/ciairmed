<html>
    <head>
        <meta charset="utf-8">
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <style>
            body {font-family: 'Roboto', sans-serif;}
            .pdf_container {width: 970px; margin: 0 auto;}
            .main_set_pdng_div {width: 100%; float: left; padding: 0px 0;}
            .brdr_full_div { float: left; padding: 10px; width: 100%;}
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
        </style>
    </head>
    <body>
        <div class="pdf_container">
            <div class="main_set_pdng_div">
                <div class="brdr_full_div">
                   <?php /* <div class="header_full_div">
                        <img class="set_logo" src="logo.png"/>
                    </div> */ ?>

                    <div class="rgtsign_img_divfull">
                        <h1>thank you for your order</h1>
                        <h2>Your Booking ID is <span><?php echo $query[0]['order_id']; ?></span></h2>
                        <h5>We have sent you an email confirmation at : <span><?php echo $query[0]['email']; ?></span></h5>
                        <p>In case of any questions, please call us at: <b>+91 8101 161616</b></p>
                    </div>

                    <h2 class="bookng_smry_titl">Booking Summary </h2>
                    <div class="invc_brdrdiv_titl_div">
                        <span class="invc_brdrdiv_titl_div_span">Booking ID : <?php echo $query[0]['order_id']; ?></span>
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
                          
                            
                            
                               <?php if($test_for=='Self'){ ?>
                       <tr>
                                <td>Billing Name :</td>
                                <td><b><?php echo ucfirst($query[0]['full_name']); ?> </b></td>
                            </tr>
                        <?php } else { ?>
                              <tr>
                                <td>Billing Name :</td>
                                <td><b><?php echo  ucfirst($relation[0]['name']); ?> </b></td>
                            </tr>
                              <tr>
                                <td>Care of:</td>
                                <td><b><?php echo ucfirst($query[0]['full_name']); ?> </b></td>
                            </tr>
                      
                        <?php
                        } ?>
                            <tr>
                                <td>Registered mobile no. :</td>
                                <td><b><?php echo "(+91) " . $query[0]['mobile']; ?> </b></td>
                            </tr>
                            <tr>
                                <td>Sample collection address :</td>
                                <td><b><?php
                                        if (empty($query[0]['address'])) {
                                            echo ucfirst($query[0]['address1']);
                                        } else {
                                            echo ucfirst($query[0]['address']);
                                        }
                                        ?></b></td>
                            </tr>
                            <tr>
                                <td>Email ID :</td>
                                <td><b><?php if($query[0]['email']!="noreply@airmedlabs.com"){ echo $query[0]['email']; } ?></b></td>
                            </tr>
                        </tbody>
                    </table>

                    <h2 class="bookng_smry_titl">Order Summary</h2>
                    <div class="ordr_smry_full">
                        <div class="ordr_smry_cl6_lft">
                            <div class="ordrsmry_cl6_back">
                                <?php /* <p><?php echo $query[0]['full_name']; ?> <br> <?php echo $query[0]['age']; ?> years / <?php
                                  if ($query[0]['gender'] == 'male') {
                                  echo "M";
                                  } else {
                                  echo "F";
                                  }
                                  ?></p> */ ?>
                                <p><?php echo $query[0]['full_name']; ?> <br/> <?php
                                    if (!empty($query[0]['age'])) {
                                        echo $query[0]['age'];
                                        ?> years /<?php } ?> <?php
                                    if ($query[0]['gender'] == 'male') {
                                        echo "M";
                                    } else if ($query[0]['gender'] == 'male') {
                                        echo "F";
                                    }
                                    ?></p>
                            </div>
                            <div class="ordrsmry_cl6_dotted_brdr">
                                <p><?php echo $fasting; ?> </p>
                            </div>
                        </div>
                        <div class="ordr_smry_cl6_rgt">
                            <table class="ordrsmry_cl6_tbl">
                                <tbody>
                                    <tr>
                                        <td><b>test/package</b></td>
                                        <td><b>our price</b></td>
                                    </tr>
                                    <?php
                                    $cou = count($book_list);
                                    $ttl_price = 0;
                                    for ($cnt = 0; $cnt < $cou; $cnt++) {
                                        $ttl_price += $book_list[$cnt][0]['price'];
                                        ?>
                                        <tr>
                                            <td><?php echo ucfirst($book_list[$cnt][0]['book_name']); ?><?php
                                                if ($query[0]["price"] == 0) {
                                                    echo "<br><small style='color:green'>(Active Package)</small>";
                                                }
                                                ?></td>
                                            <td>Rs. <?php
                                                if ($query[0]['price'] != '0') {
                                                    echo $book_list[$cnt][0]['price'];
                                                } else {
                                                    echo "0";
                                                }
                                                ?></td>
                                        </tr>
                                            <?php } ?>
                                            <?php if ($ttl_price < 300 && $query[0]["collection_charge"]==1) { ?>
                                        <tr>
                                            <td>Sample Collection Charge</td>
                                            <td>Rs. <?php
                                            if ($ttl_price < 300) {
                                                echo 100;
                                            } else {
                                                echo 0;
                                            }
                                            ?></td>
                                        </tr>
<?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="invc_sctn_3_full_back">
                        <p>Total Amount <span>Rs. <?php echo $query[0]['price']; ?></span></p>
                    </div>

                    <h2 class="bookng_smry_titl">Payment Summary</h2>
                    <table class="pymntsmry_tbl">
                        <tbody>
                            <tr>
                                <td><b>Payment Due Date</b></td>
                                <td><b>Mode of Payment</b></td>
                                <td><b>Payment Via</b></td>
                                <td><b>Total Amount</b></td>
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
                                    ?></td> 
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
                        </tbody>
                    </table>

                  <?php /*  <div class="foot_num_div">
                        <p class="foot_num_p"><img class="set_sign" src="pdf_phn_btn.png"/></p> 
                        <p class="foot_lab_p">lab at your doorstep</p>
                    </div>
                    <p class="lst_airmed_mdl">Airmed Pathology Pvt Ltd.</p>
                    <p class="lst_31_addrs_mdl"><b>Corporate Office</b> : 31, Ambika Society, Next to Nabard Bank, Opp. Usmanpura, Ahmedabad,</p><p class="lst_31_addrs_mdl"> Gujarat - 380 013.</p>
                    <p class="lst_31_addrs_mdl"><b>Branch Office</b> : Kings Mall,Opp. Rohini West Metro Station,Rohini,Delhi-110085</p>
                    <p style="text-align: center;"><span> +91 79 27552277, </span>
                        <span>info@airmedlabs.com, </span>
                        <span> www.airmedlabs.com</span></p> */?>
                </div>
            </div>
        </div>
    </body>
</html>
