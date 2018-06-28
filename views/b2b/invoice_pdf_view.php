<html><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"></head><body><div class="pdf_container">
 <meta charset="utf-8">
<style>
            body {font-family: 'Roboto', sans-serif;}
            .pdf_container {width: 970px; margin: 0 auto;}
            .main_set_pdng_div {width: 100%; float: left; padding: 0 0;}
            .brdr_full_div { float: left; padding: 10px; width: 100%;}
            .full_div {width: 100%; float: left;}
            .header_full_div {float: left; padding: 0px 10px 5px 10px; width: 98%; height:auto;}
            .set_logo {width: 100%; float: right;}
            .testreport_full {width: 100%; float: left;}
            .tst_rprt {border-bottom: 1px solid #000000; border-top: 1px solid #000000; margin: 0; padding: 5px 0; text-transform: uppercase;width: 100%; float:left;}
            .tst_rprt > img {}
            .tst_rprt h3 {margin: 0; text-align: center;}
            .tbl_full {width: 100%; font-size: 12px;}
            .mdl_tbl_full_div {width: 100%; float: left; min-height: 500px;margin: 0px 5px 0px 5px;}
            .btm_tbl_full_div {width: 100%; float: left;}
			.brdr_btm {border-bottom: 1px solid #000;}
			.header_full_div .hdr_lft{ width: 75%; float: left; }
			.header_full_div .hdr_rgt{ width: 25%; float: left; }
			.header_full_div h2 {background: #333; color: #fff; font-size: 16px;font-weight: 800; text-transform: uppercase; padding: 10px 0 10px 30px;margin-bottom:0;}
			.tbl_half{width:48%; float:left;font-size: 12px;}
			 tr {line-height: 1.3;}
			 .tbl_full.with_brdr{margin-bottom:40px;}
			 .tbl_full.with_brdr thead th{border-bottom:1px solid #000; text-align-last: start; }
			 .tbl_full.with_brdr tbody td{border-bottom:1px solid #000;}
			.rslt_p_brdr {border-bottom: 1px solid #000000; float: left; margin: 0; padding-bottom: 5px; text-align: center; width: 100%;}
			.half_div{width:50%; float:left;}
			
			.header_full_div .hdr_top h2 {font-size: 16px;font-weight: 600; text-transform: uppercase; margin-bottom:5px; text-align:center; background:none; color:#000; padding:0;}
			.lst_airmed_mdl {float: left; margin-bottom: 0; margin-top: 0px; text-align: center; width: 100%;}
            .lst_31_addrs_mdl {float: left; margin: 0; text-align: center; width: 100%;margin-bottom: 5px;}
            .tbl_btm_mdl_txt {width: 80%; float: left; padding: 0 98px; font-weight: bold;}
			.txt_align_lft{text-align:left;}
			.brdr_rgt{border-right:1px solid #000;}
        </style>

	<div class="main_set_pdng_div">
	    <div class="brdr_full_div">
		<div class="header_full_div">
			<div class="hdr_lft">
				<h2><b>Airmed Pathology Private Limited</b></h2>
				<div class="full_div" style="margin-bottom: 10px;">
					<table class="tbl_full">
						<tbody>
							<tr><td><b>Airmed Pathology Pvt. Ltd.</b> </td></tr>
							<tr><td>31, Ambika Society, Next to Nabard Bank, Opp. Usmanpura, Ahmedabad, Gujarat - 380 013.</td></tr>
							<tr><td>info@airmedlabs.com | www.airmedlabs.com</td></tr>
						</tbody>
					</table>
				</div>
			</div>
		<div class="hdr_rgt">
			<img class="set_logo" src="http://websitedemo.co.in/phpdemoz/patholab/user_assets/images/logo.png" style="margin-top:15px;">
		</div>
		</div>
		<div class="testreport_full">
		    <div class="tst_rprt">
			<div class="tst_rprt_title" style="width:100%;float:left;text-align:center;">
			    <h3>Invoice</h3>
			</div>
		    </div>
			<div class="half_div" style="margin-bottom: 10px;">
				<table class="tbl_full">
					<tbody>
						
						<tr>
							<td>To,</td>
						</tr>
						<tr>
							<td><b><?= ucwords($query->name); ?></b></td>
						</tr>
						<tr>
							<td><?= ucwords($query->address); ?></td>
						</tr>
						<tr>
							<td>Mobile: <?= $query->mobile_number; ?></td>
						</tr>
						<tr>
							<td>Phone: <?= $query->alternate_number; ?></td>
						</tr>
						<tr>
							<td><?= ucwords($query->cityname); ?></td>
						</tr>
					</tbody>
				</table>
				</div>
				<div class="half_div" style="margin-bottom: 10px;">
				<table class="tbl_full" style="border:1px solid #000; margin-top:30px; float:right;">
					<tbody>
						<tr>
							<td>Bill No. :</td>
							<td><?= $query->id; ?></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>Bill Date :</td>
							<td><?= date("d-m-Y"); ?></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>From :</td>
							<td><?php if($date){ echo date("d-m-Y",strtotime($date)); } ?></td>
							<td>To :</td>
							<td><?php if($todate2){ echo date("d-m-Y",strtotime($todate2)); } ?></td> 
						</tr>
					</tbody>
				</table>
			</div>
			
		    <div class="full_div">
				<table class="tbl_full with_brdr" style="border:1px solid #000; border-spacing: 0;">
					<thead>
						<tr>
							<th class="txt_align_lft brdr_btm" style="width:80%; padding:5px;">Description</th>
							<th class="brdr_btm" style="width:20%;border-left:1px solid #000; text-align:right;padding:5px;">Amount (Rs.)</th>
						</tr>
					<thead>
                     <tbody>
						<tr>
                             <td class="brdr_btm" style="padding:5px;"><?= ucwords($query->name); ?></td>
                             <td class="brdr_btm" style="border-left:1px solid #000; text-align:right;padding:5px;">Rs.<?= $totalamount ?></td>                   
                        </tr>
						<!--- <tr>
							<td class="brdr_btm" style="height:30px;"></td>                   
                             <td  class="brdr_btm"style="border-left:1px solid #000;text-align:right;"> </td>  
						</tr> --->
						<tr>
                             <td class="brdr_btm" style="padding:5px;"><b>Total</b></td>                   
                             <td class="brdr_btm" style="border-left:1px solid #000;text-align:right;padding:5px;">Rs.<?= $totalamount ?></td>                   
                        </tr>
						
                     </tbody>
				</table>
		    </div>
			
			<div class="full_div">
				<table class="tbl_full" style="margin-bottom:20px;">
					<tr>
						<td><b>Cheque drawn in favour of - Airmed Pathology Pvt.Ltd</b></td>
					</tr>
					<tr>
						<td><b>PAN - AAVCS1174P</b></td>
					</tr>
				</table>
				
				<table class="tbl_half" style="margin-bottom:20px;">
					<tr>
						<td><b><h3>Bank Details<h3></b></td>
						<td></td>
					</tr>
					<tr>
						<td><b>Bank Name :</b></td>
						<td>Kotak Mahindra Bank Ltd.</td>
					</tr>
					<tr>
						<td><b>Branch :</b></td>
						<td>Drive In Road</td>
					</tr>
					<tr>
						<td><b>A/C No. :</b></td>
						<td>4211515759</td>
					</tr>
					<tr>
						<td><b>IFSC Code :</b></td>
						<td>KKBK0000825</td>
					</tr>
					<tr>
						<td><b>MICRNO :</b></td>
						<td>380485007</td>
					</tr>
					<tr>
						<td><b>GSTIN :</b></td>
						<td>24AAHCP7550N1ZY</td>
					</tr>
				</table>
				<table class="tbl_full" style="margin-bottom:20px;">
					<tr>
						<td><b>Computer generated invoice and requires no signature.</b></td>
					</tr>
					<tr>
						<td><b>Please contact on 079-49030000- accounts@airmedlabs.com</b></td>
					</tr>
					<tr>
						<td><b>for billing related queries.</b></td>
					</tr>
					<tr>
						<td><b>Thanks For Your Trust in Airmed Pathology Pvt.Ltd.</b></td>
					</tr>
					<tr>
						<td><b>Please Collect recept of against your payment</b></td>
					</tr>
				</table>
			
			</div>
		</div>

<pagebreak>
	<div class="header_full_div">
			<div class="hdr_top">
				<h2>Airmed Pathology Private Limited</h2>
				<p class="lst_31_addrs_mdl">31, Ambika Society, Next to Nabard Bank, Opp. Usmanpura, Ahmedabad, Gujarat - 380 013.</p>
				<p class="lst_31_addrs_mdl"><b>info@airmedlabs.com | www.airmedlabs.com</b></p>
			</div>
		
		</div>
		<div class="testreport_full">
		    <div class="tst_rprt">
			<div class="tst_rprt_title" style="width:100%;float:left;text-align:center;">
			    <h3>Invoice Details</h3>
			</div>
		    </div>
			<div class="full_div" style="margin-bottom: 10px;">
				<table class="tbl_half">
					<tbody>
						<tr>
							<td style="width:25%;"><b>Sales Person</b></td>
							<td><?= ucwords($query->salesname); ?></td>
						</tr>
						<tr>
							<td style="width:25%;"><b>Account Name :</b></td>
							<td><?= ucwords($query->name); ?></td>
						</tr>
					</tbody>
				</table>
				<table class="tbl_full">
					<tbody>
						<tr>
							<td><b>Bill No. :</b></td>
							<td><?= $query->id; ?></td>
							<td><b>Bill Date :</b></td>
							<td><?= date("d-m-Y"); ?></td>
							<td><b>Bill Period :</b></td>
							<td><?php if($date){ echo date("d-m-Y",strtotime($date)); } ?> - <?php if($todate2){ echo date("d-m-Y",strtotime($todate2)); } ?> </td>
							
						</tr>
					</tbody>
				</table>
			</div>
			
		    <div class="full_div">
				<table class="tbl_full with_brdr" style="border:1px solid #000; border-spacing: 0;">
					<thead>
						<tr>
							<th class="txt_align_lft brdr_btm brdr_rgt" style="width:42px;padding:5px;">Sr No.</th>
							<th class="txt_align_lft brdr_btm brdr_rgt" style="width:90px;padding:5px;">Reg.Date</th>
							 <th class="txt_align_lft brdr_btm brdr_rgt" style="width:100px;padding:5px;">Lab ID</th>  
							<th class="txt_align_lft brdr_btm brdr_rgt" style="width:27%;padding:5px;">Patient's Name</th>
							<?php /* <th class="txt_align_lft brdr_btm brdr_rgt" style="padding:5px;width:90px;">Ref. ID</th> */ ?>
							<th class="txt_align_lft brdr_btm brdr_rgt" style="width:27%;padding:5px;">Investigation</th>
							<th class="brdr_btm" style="border-right:none; width:60px; text-align:right;padding:5px;">Amt.</th>
						</tr>
					<thead>
                     <tbody>
					 <?php $amount_all=0; $i=0; foreach($query1 as $key){ $i++; $amount_all += $key['amt']; ?>
						<tr>
                            <td class="brdr_btm brdr_rgt" style="padding:5px;"><?= $i; ?></td>                
                            <td class="brdr_btm brdr_rgt" style="padding:5px;"><?= $key['regdate']; ?></td>                
                                            
                            <td class="brdr_btm brdr_rgt" style="text-transform:uppercase;padding:5px;"><?=$key['labid']; ?></td>
                            <td class="brdr_btm brdr_rgt" style="padding:5px;"><?= ucwords($key['patientname']); ?> </td>                
                            <td class="brdr_btm brdr_rgt" style="padding:5px;"><?= ucwords($key['testname']); ?></td>
                            <td class="brdr_btm" style="text-align:right;padding:5px;"><?= $key['amt'];	?></td>                
                        </tr>
						<?php 
						
						
						} ?>
						<tr><td></td><td colspan='4'><b>Total</b></td><td>Rs.<?=round($amount_all);?></td></tr>
                     </tbody>
				</table>
		    </div>
			
			
		</div>
</pagebreak>
</div></div></body></html>