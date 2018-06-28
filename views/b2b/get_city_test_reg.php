<!--<style>
    .chosen-container {
        display: inline-block;
        font-size: 14px;
        position: relative;
        vertical-align: middle;
        width: 100%;
    }
</style>
-->

<select class="chosen-select" data-live-search="true" id="test" data-placeholder="Select Test" onchange="get_test_price();">
    <option value="">--Select Test--</option>
    <?php foreach ($test as $ts) { if($ts['specialprice'] != ""){ $discount=""; $spicelprice=$ts['specialprice'];  }else{ $discount=$labdetils->test_discount; 
	$discountprice=($ts['price']*$discount/100);
$spicelprice=$ts['price']-$discountprice;
	} ?>
    <option value="t-<?php echo $ts['id']; ?>" <?php ?>> <?php echo ucfirst($ts['test_name']); ?> (Rs.<?php echo $ts['price']; ?>) ( Rs.<?php if($spicelprice>0){ echo $spicelprice; } ?> )</option>
    <?php } ?>
</select>
<script  type="text/javascript">
    jQuery(".chosen-select").chosen({
    });
	/* jQuery(".chosen-select").chosen().change(function(){
        var test_val = $(this).val();
		
    var cnt = 0;if (test_val.trim() == '') {
	$("#test_error").html("Test is required."); cnt = cnt + 1; 
	}if (cnt > 0) { return false;}
	
	var selectedText = $(this).find('option:selected').text();
	var prc = selectedText.split('(Rs.');
	var prc1 = prc[1].split(')');
	var prc2 = prc[1].split('( Rs.');
    var prc3 = prc2[1].split(')');
    var pm = test_val;
    var explode = pm.split('-');
                                                           
    }); */
</script>