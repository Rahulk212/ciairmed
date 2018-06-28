<html>
    <head>
        <meta charset="utf-8">
		<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
		<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <style>
			body {font-family: 'Roboto', sans-serif;}
            .pdf_container {width: 970px; margin: 0 auto;}
			.main_set_pdng_div {width: 100%; float: left; padding: 50px 0;}
			.brdr_full_div {border: 2px solid #444444; float: left; padding: 10px; width: 100%;}
			.full_div {width: 100%; float: left;}
			.header_full_div {float: left; padding: 20px 40px; width: 92%;}
			.set_logo {width: 180px; float: right;}
			.testreport_full {width: 100%; float: left;}
			.tst_rprt {border-bottom: 2px solid #000000; border-top: 2px solid #000000; margin: 0; padding: 5px 0; text-align: center; text-transform: uppercase;}
			.tbl_full {width: 100%; font-size: 14px;}
			.mdl_tbl_full_div {width: 100%; float: left;}
			.mdl_tbl_full {width: 100%; font-size: 14px; margin-top: 20px;}
			.mdl_tbl_big_titl {border-bottom: 1px solid #000000; font-size: 25px; font-weight: bold; text-align: center; margin-top: 10px; margin-bottom: 10px; display: inline-block;}
			.mdl_tbl_tr_brdr {border-bottom: 2px solid #000000; border-top: 2px solid #000000; /*display: table; width: 100%;*/}
			.brdr_btm {border-bottom: 1px solid #000;}
			.end_rprt {text-align: center; float: left; width: 100%;}
			.rslt_p_brdr {border-bottom: 1px solid #000000; float: left; margin: 0; padding-bottom: 5px; text-align: center; width: 100%;}
			.this_p {float: left; margin-top: 5px;}
			.lst_sign_div_main {width: 100%; float: left; margin-top: 22px;}
			.lst_sign_pathologist {width: 50%; float: left; text-align: center;}
			.lst_sign_mdl_sign {width: 25%; float: left;}
			.lst_sign_lst_sign {width: 25%; float: left;}
			.foot_num_div {width: 100%; float: left; padding-bottom: 10px; border-bottom: 3px dotted #E30026;}
			.foot_num_p {text-align: center; margin-bottom: 10px;}
			.foot_num_p span {background-color: #E30026; border-radius: 25px; padding: 3px 15px; color: #fff;}
			.foot_lab_p {margin: 0; text-align: center; text-transform: uppercase;}
			.lst_ison_ul {display: inline-block; padding: 0; text-align: center; width: 100%; amrgin-top: 5px;}
			.lst_ison_ul li {display: inline-block; margin-right: 15px;}
			.lst_icon_spn_back {background-color: #e30026; border-radius: 50%; float: left; height: 16px; margin-right: 9px; padding: 4px; width: 16px;}
			.lst_icon_spn_back .fa {color: #fff;}
			.lst_airmed_mdl {float: left; margin-bottom: 0; margin-top: 9px; text-align: center; width: 100%;}
			.lst_31_addrs_mdl {float: left; margin: 0; text-align: center; width: 100%;}
        </style>
    </head>
    <body>
        <?php
                                            $ts = explode('#', $query[0]['testname']);
                                $tid = explode(",", $query[0]['testid']);
                                $cnt = 0;
                                foreach ($tid as $testidp) {
                                        ?>
        <div class="pdf_container" <?php if($cnt != 0) { ?> style="page-break-before: always;" <?php } ?>>
			<div class="main_set_pdng_div">
				<div class="brdr_full_div">
					<div class="header_full_div">
						<img class="set_logo" src="<?php echo base_url(); ?>user_assets/images/logo.png"/>
					</div>
					<div class="testreport_full">
						<h3 class="tst_rprt">test report</h3>
						<div class="full_div">
							<table class="tbl_full">
								<tr>
									<td><b>Reg. No. :</b></td>
									<td>1611400215</td>
									<td><b>Reg. Date :</b></td>
									<td>23-Nov-2016 14:12</td>
									<td></td>
									<td><b>Collected On :</b></td>
									<td>23-Nov-2016 13:41</td>
								</tr>
								<tr>
									<td><b>Name :</b></td>
									<td>ASHVINBHAI</td>
									<td></td>
									<td></td>
									<td></td>
									<td><b>Report Date :</b></td>
									<td>23-Nov-2016</td>
								</tr>
								<tr>
									<td><b>Age  :</b></td>
									<td>24 Years</td>
									<td><b>Sex :</b> Male</td>
									<td></td>
									<td></td>
									<td><b>Dispatch At :</b></td>
									<td></td>
								</tr>
								<tr>
									<td><b>Ref. By :</b></td>
									<td>DR. Self</td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td><b>Location :</b></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td><b>Tele No :</b></td>
									<td>9727441584</td>
								</tr>
							</table>
						</div>
					</div>
					<div class="mdl_tbl_full_div">
						<table class="mdl_tbl_full">
							<tr class="mdl_tbl_tr_brdr">
								<td><b>Parameter</b></td>
								<td><b>Result</b></td>
								<td><b>Unit</b></td>
								<td><b>Reference Interval</b></td>
							</tr>
                                                        <tr style="text-align: center;">
								<td colspan="4"><span class="mdl_tbl_big_titl"><?php echo ucfirst($ts[$cnt]); ?></span></td>
							</tr>
                                                        <?php
                                        foreach ($parameter_list[$cnt] as $parameter) {
                                            if ($parameter['test_fk'] == $testidp) {
                                                    ?>
							
							<tr>
								<td><?php echo ucfirst($parameter['parameter_name']); ?></td>
								<td><?php echo $parameter['value']; ?></td>
								<td><?php echo $parameter['parameter_unit']; ?></td>
								<td><?php echo $parameter['parameter_range']; ?></td>
							</tr>
                                                   <?php } } $cnt++; ?>
                                                        </table>
                                            
<!--							<tr>
								<td>Hemoglobin</td>
								<td>13.8</td>
								<td>gm%</td>
								<td>13.0 - 17.0</td>
							</tr>
							<tr>
								<td colspan="4"><b class="brdr_btm">Blood Indices</b></td>
							</tr>
							<tr>
								<td>H.CT</td>
								<td>13.8</td>
								<td>gm%</td>
								<td>13.0 - 17.0</td>
							</tr>
							<tr>
								<td>H.CT</td>
								<td>13.8</td>
								<td>gm%</td>
								<td>13.0 - 17.0</td>
							</tr>
							<tr>
								<td colspan="4"><b class="brdr_btm">Differential WBC Count</b></td>
							</tr>
							<tr>
								<td>Polymorphs</td>
								<td>28</td>
								<td>%</td>
								<td>40 - 70</td>
							</tr>
							<tr>
								<td>Polymorphs</td>
								<td>28</td>
								<td>%</td>
								<td>40 - 70</td>
							</tr>
							<tr>
								<td>Polymorphs</td>
								<td>28</td>
								<td>%</td>
								<td>40 - 70</td>
							</tr>
							<tr>
								<td>Platelets Count</td>
								<td>44000</td>
								<td>/cmm</td>
								<td>150000 - 400000</td>
							</tr>
							<tr>
								<td>Smear Study - RBC</td>
								<td colspan="3">RBC's are predominantly Macrocytic & Normochromic.</td>
							</tr>
							<tr>
								<td>Smear Study - WBC</td>
								<td colspan="3">There is leucopenia with lymphocytosis seen.</td>
							</tr>
							<tr>
								<td>Smear Study - Platelets</td>
								<td colspan="3">There is thrombocytopenia</td>
							</tr>
							<tr>
								<td>Smear Study - PS for MP</td>
								<td colspan="3">No Blood Parasites are seen.</td>
							</tr>
							<tr>
								<td>ESR</td>
								<td>08</td>
								<td>mm</td>
								<td>1 - 7</td>
							</tr>-->
						
					</div>
					<p class="end_rprt">- - - - - - End Of Report - - - - - -</p>
					<p class="rslt_p_brdr">Result relate only to the sample as received</p>
					<p class="this_p">This is an electronically authenticated report.</p>
					<div class="lst_sign_div_main">
						<div class="lst_sign_pathologist">
							<p><b>Pathologist :</b></p>
						</div>
						<div class="lst_sign_lst_sign">
							<p><b>Dr. Aradhana Gupta <br/> (M.D. Path.)</b></p>
						</div>
					</div>
					<div class="foot_num_div">
						<p class="foot_num_p"><span>8101-161616</span></p>
						<p class="foot_lab_p">lab at your doorstap</p>
					</div>
					<p class="lst_airmed_mdl">Airmed Pathology Labs</p>
					<p class="lst_31_addrs_mdl">31, Ambika Society, Next to Nabard Bank, Opp. Usmanpura, Ahmedabad, Gujarat - 380 013.</p>
					<ul class="lst_ison_ul">
						<li>
							<span><span class="lst_icon_spn_back"><i class="fa fa-phone"></i></span> +91 79 27552277</span>
						</li>
						<li>
							<span><span class="lst_icon_spn_back"><i style="font-size: 14px;" class="fa fa-envelope"></i></span> info@airmedlabs.com</span>
						</li>
						<li>
							<span><span class="lst_icon_spn_back"><i class="fa fa-globe"></i></span> www.airmedlabs.com</span>
						</li>
					</ul>
				</div>
			</div>
		</div>
        <?php } ?>
    </body>
</html>
