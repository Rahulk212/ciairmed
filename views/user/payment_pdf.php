
<style>
@media 
	only screen and (max-width: 760px),
	(min-device-width: 768px) and (max-device-width: 1024px)  {
	
		/* Force table to not be like tables anymore */
	.table-bordered thead, tbody, th, td, tr { 
			display: block; 
		}
		
		/* Hide table headers (but not display: none;, for accessibility) */
	.table-bordered thead tr { 
			position: absolute;
			top: -9999px;
			left: -9999px;
		}
		
	.table-bordered tr { border: 1px solid #ccc; }
		
	.table-bordered td { 
			/* Behave  like a "row" */
			border: none;
			border-bottom: 1px solid #eee; 
			position: relative;
			padding-left: 50%; 
		}
		
		.table-bordered td:before { 
			/* Now like a table header */
			<!-- position: absolute; -->
			/* Top/left values mimic padding */
			<!-- top: 6px;
			left: 6px; -->
			width: 45%; 
			<!-- padding-right: 10px;  -->
			white-space: nowrap;
		}
		
		
		.table-bordered td:nth-of-type(1):before { content: "Order Id"; }
		.table-bordered td:nth-of-type(2):before { content: "Payment For"; }
		.table-bordered td:nth-of-type(3):before { content: "Amount(Rs)"; }
		.table-bordered td:nth-of-type(4):before { content: "Date"; }
		.table-bordered td:nth-of-type(5):before { content: "Status"; }
		
		}
	
	
	
</style>
<table class="table table-bordered">
  <thead style="background:#0077A6;color:#fff;">
    <tr>
      <th>No.</th>
      <th>Order Id</th>
      <th>Payment For</th>
      <th>Amount(Rs)</th>
      <th>Date</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
  <?php $cn=1; foreach($history as $key ) { ?>
  <tr>
     
      <th scope="row"><?php echo $cn; ?></th>
      <td>#<?php echo $key['payomonyid'];  ?></td>
      <td><?php if($key['type']=="wallet") { echo "Added To Wallet"; }else { echo $key['testname'];}  ?></td>
      <td><?php echo $key['amount'];  ?></td>
      <td><?php echo $key['date'];  ?></td>
      <td style="<?php if($key['status']=="success") { echo "color:green";} else { echo "color:red";}  ?>"><?php echo $key['status'];  ?></td>
      
    </tr>
  <?php $cn++; } ?>
    
   
  </tbody>
</table>