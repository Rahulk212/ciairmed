<style>
    input[disabled], select[disabled], textarea[disabled], input[readonly], select[readonly], textarea[readonly]
    {
        background-color: none;
        border: 1px none;
        padding: 6px;
    }
</style>
<?php
// Merchant key here as provided by Payu
$MERCHANT_ID = $payumoneydetail['MERCHANT_ID'];
$MERCHANT_KEY = $payumoneydetail['MERCHANT_KEY'];
$SALT = $payumoneydetail['SALT'];
$PAYU_BASE_URL = $payumoneydetail['URL'];
$service_provider = $payumoneydetail["service_provider"];
// Merchant product info.
// Populate name, merchantId, description, value, commission parameters as per your code logic; in case of multiple splits pass multiple json objects in paymentParts
if ($service_provider == "payu_paisa") {
    $firstSplitArr = array("name" => "splitID1", "value" => "6", "merchantId" => $MERCHANT_ID, "description" => "AirmedLabs booking", "commission" => "2", "service_provider" => "payu_paisa");
} else {
    $firstSplitArr = array("name" => "splitID1", "value" => "6", "merchantId" => $MERCHANT_ID, "description" => "AirmedLabs booking", "commission" => "2");
}
//print_r($firstSplitArr); die();
$paymentPartsArr = array($firstSplitArr);
$finalInputArr = array("paymentParts" => $paymentPartsArr);
$Prod_info = json_encode($finalInputArr);
//var_dump($Prod_info);
// Merchant Salt as provided by Payu
// End point - change to https://secure.payu.in for LIVE mode
$action = '';

$posted = array();
if (!empty($_POST)) {
    //print_r($_POST);
    foreach ($_POST as $key => $value) {
        $posted[$key] = $value;
    }
}

$formError = 0;

if (empty($posted['txnid'])) {
    // Generate random transaction id
    // $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 13);
    $txnid = random_string('numeric', 3) . time();
} else {
    $txnid = $posted['txnid'];
}
$hash = '';
// Hash Sequence
$hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
if (empty($posted['hash']) && sizeof($posted) > 0) {
    if (
            empty($posted['key']) || empty($posted['txnid']) || empty($posted['amount']) || empty($posted['firstname']) || empty($posted['email']) || empty($posted['phone']) || empty($posted['productinfo']) || empty($posted['surl']) || empty($posted['furl']) || empty($posted['service_provider'])
    ) {
        $formError = 1;
    } else {
        //$posted['productinfo'] = json_encode(json_decode('[{"name":"tutionfee","description":"","value":"500","isRequired":"false"},{"name":"developmentfee","description":"monthly tution fee","value":"1500","isRequired":"false"}]'));
        $hashVarsSeq = explode('|', $hashSequence);
        $hash_string = '';
        foreach ($hashVarsSeq as $hash_var) {
            $hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
            $hash_string .= '|';
        }

        $hash_string .= $SALT;


        $hash = strtolower(hash('sha512', $hash_string));
        $action = $PAYU_BASE_URL . '/_payment';
    }
} elseif (!empty($posted['hash'])) {
    $hash = $posted['hash'];
    $action = $PAYU_BASE_URL . '/_payment';
}
?>
<html>
    <head>
        <script>
            var hash = '<?php echo $hash ?>';
            function submitPayuForm() {
                document.getElementById("submit1").click();
                //$("#submit1").click();
                if (hash == '') {
                    return;
                }

                //document.payuForm.submit();
                //var payuForm = document.forms.payuForm;
                //  payuForm.submit();

            }
        </script>
    </head>

    <body onload="submitPayuForm()">
    <center style="display:none;">

        <h3>Please wait, your order is being processed and you will be redirected to the payumoney website.</h3>
        <img src="<?php echo base_url(); ?>img/ajax_loader.gif"/>
        <br/>
        if not redirecting please click here
        <br/>
        <input type="button" name="btn" class="submit1" value="click here" onclick="submitPayuForm()"/>
    </center>
    <br/>
<?php if ($formError) { ?>
         <!-- <span style="color:red">Please fill all mandatory fields.</span>-->
        <br/>
        <br/>
<?php } ?>
    <center>
        Please Wait....
    </center>

    <form action="<?php echo $action; ?>" method="post" name="payuForm" id='payuf' class="form-horizontal" style="display:none;">

        <input type="hidden" name="key" value="<?php echo $MERCHANT_KEY ?>" />
        <input type="hidden" name="productinfo" value="<?php echo htmlspecialchars($Prod_info); ?>" />
        <input type="hidden" name="hash" value="<?php echo $hash ?>"/>
        <input type="hidden" name="txnid" value="<?php echo $txnid ?>" />

<?php $payment_method = $method; ?> 


        <input type="hidden" name="surl" value="<?= base_url(); ?>add_wallet_master/success_payumoney" size="64" />
        <td colspan="3"><input name="furl" type="hidden" value="<?= base_url(); ?>add_wallet_master/fail_payumoney" size="64" />
        <td colspan="3"><input name="service_provider" type="hidden" value="<?= base_url(); ?>add_wallet_master/success_payumoney" size="64" />
            <?php if ($service_provider == "payu_paisa") { ?>
                <input type="hidden" name="service_provider" value="payu_paisa" size="64" />
            <?php } ?>

            <div class="control-group">
                <label class="control-label" for="email">TxnId:</label> 
                <div class="controls">

                    <input name="txnid"  id="txnid" value="<?php echo $txnid; ?>" readonly />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="email">Amount</label> 
                <div class="controls">

                    <input name="amount"  value="<?php echo $payamount; ?>" readonly  />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="email">First Name:</label> 
                <div class="controls">
                    <input name="firstname"  id="firstname" value="<?php echo $user_detail[0]['full_name']; ?>" readonly  />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="email">Email : </label> 

                <div class="controls">

                    <input name="email" id="email" value="<?php echo $user_detail[0]['email']; ?>"  readonly  />
                </div>
            </div>


            <div class="control-group">
                <label class="control-label" for="email">Phone:</label> 
                <div class="controls">

                    <input name="phone"  value="<?php echo $user_detail[0]['mobile']; ?>" readonly   />
                </div>
            </div>

            <div class="control-group">
                <div class="controls">
<?php //if(!$hash) {  ?>
                    <input type="submit" name="submit" value="Confirm Your Payment" class="btn btn-primary" id="submit1" style="display:none;"/> 
<?php // }  ?>
                </div>
            </div>
    </form>

</body>
</html>
<?php /*
  <input type="hidden" name="surl" value="<?php echo base_url() . 'add_wallet_master/success_payumoney/' . $testid . '/' . $payment_method; ?>" size="64" />
  <td colspan="3"><input name="furl" type="hidden" value="<?php echo base_url() . 'add_wallet_master/fail_payumoney'; ?>" size="64" />
  <td colspan="3"><input name="service_provider" type="hidden" value="<?php echo base_url() . 'add_wallet_master/success_payumoney/' . $testid . '/' . $payment_method; ?>" size="64" />
 */ ?>