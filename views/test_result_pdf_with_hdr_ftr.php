<html>
    <head>
        <meta charset="utf-8">
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <style>
            body {font-family: 'Roboto', sans-serif;}
            .pdf_container {width: 970px; margin: 0 auto;}
            .main_set_pdng_div {width: 100%; float: left; padding: 50px 0;}
            .brdr_full_div {border: 1px solid #444444; float: left; padding: 10px; width: 100%;}
            .full_div {width: 100%; float: left;}
            .header_full_div {float: left; padding: 10px 40px 20px 40px; width: 92%;}
            .set_logo {width: 180px; float: right;}
            .testreport_full {width: 100%; float: left;}
            .tst_rprt {border-bottom: 1px solid #000000; border-top: 1px solid #000000; margin: 0; padding: 5px 0; text-align: center; text-transform: uppercase;}
            .tbl_full {width: 100%; font-size: 12px;}
            .mdl_tbl_full_div {width: 100%; float: left;}
            .btm_tbl_full_div {width: 100%; float: left;}
            .mdl_tbl_full {width: 100%; font-size: 14px; margin-top: 20px; border-top:1px solid #000;}
            .btm_tbl_full {width: 100%; font-size: 14px; margin-top: 20px;}
            .mdl_tbl_big_titl {border-bottom: 1px solid #000000; font-size: 16px; font-weight: bold; text-align: center; margin-top: 10px; margin-bottom: 10px; display: inline-block;}
            .mdl_tbl_tr_brdr {border-bottom: 2px solid #000000; border-top: 2px solid #000000; /*display: table; width: 100%;*/}
            .brdr_btm {border-bottom: 1px solid #000;}
            .end_rprt {text-align: center; float: left; width: 100%;}
            .rslt_p_brdr {border-bottom: 1px solid #000000; float: left; margin: 0; padding-bottom: 5px; text-align: center; width: 100%;}
            .this_p {float: left; margin-top: 5px;}
            .lst_sign_div_main {width: 100%; float: left; margin-top: 22px;}
            .lst_sign_pathologist {float: left;margin-right: 19%;padding-left: 10%;width: 44%;}
            .lst_sign_mdl_sign {width: 29%; float: left;}
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
            .tbl_btm_mdl_txt {width: 80%; float: left; padding: 0 98px; font-weight: bold;}
			.btm_tbl_full b {float: left; width: 100%;}
			.mdl_tbl_td_title{text-align:center; width:100%; border-top:1px solid #000;}
        </style>
    </head>
    <body>
        <div class="pdf_container" <?php $cnt=0; if ($cnt != 0) { ?> style="page-break-before: always;" <?php } ?>>
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
                                    <td>11111</td>
                                    <td style="width: 100px;"><b>Reg. Date :</b></td>
                                    <td><?php echo date("d-M-Y g:i", strtotime(date("Y-m-d H:i:s"))); ?></td>
                                    <td><b>Collected On :</b></td>
                                    <td><?php echo date("d-M-Y g:i", strtotime(date("Y-m-d H:i:s"))); ?></td>
                                </tr>
                                <tr>
                                    <td><b>Name :</b></td>
                                    <td>Nishit</td>
                                    <td style="width: 100px;"></td>
                                    <td></td>
                                    <td><b>Report Date :</b></td>
                                    <td><?php echo date('d-M-Y'); ?></td>
                                </tr>
                                <tr>
                                    <td><b>Age  :</b></td>
                                    <td>24 Years</td>
                                    <td style="width: 100px;"><b>Sex :</b>Male</td>
                                    <td></td>
                                    <td><b>Dispatch At :</b></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td><b>Ref. By :</b></td>
                                    <td><?php
                                        echo "DR. Self";
                                        ?></td>
                                    <td style="width: 100px;"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td><b>Location :</b></td>
                                    <td><?php echo "Ahmedabad"; ?></td>
                                    <td style="width: 100px;"></td>
                                    <td></td>
                                    <td><b>Tele No :</b></td>
                                    <td><?php echo "9879572294"; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="mdl_tbl_full_div">
                        <table class="mdl_tbl_full">
                            <tr class="mdl_tbl_tr_brdr">
                                <td style="width: 30%;"><b style="border-bottom: 1px solid #000;">Parameter</b></td>
                                <td style="width: 20%;"><b style="border-bottom: 1px solid #000;">Result</b></td>
                                <td style="width: 20%;"><b style="border-bottom: 1px solid #000;">Unit</b></td>
                                <td style="width: 30%;"><b style="border-bottom: 1px solid #000;">Reference Interval</b></td>
                            </tr>
                            <tr style="text-align: center; width: 100%;">
                               
                                <td colspan="4" class="mdl_tbl_td_title"><p class="mdl_tbl_big_titl"><?php echo ucfirst("COMPLETE BLOOD COUNT"); ?></p></td>
                               
                            </tr>
                            <tr>
                                <td style="width: 200px;">Hemoglobin</td>
                                <td>
                                    11.8
                                </td> 
                                <td>gm%</td>
                                <td>12.0 - 16.0</td>
                            </tr>
							<tr>
                                <td style="width: 200px;">Total RBC Count</td>
                                <td>
                                    4.27
                                </td> 
                                <td>mil/cumm</td>
                                <td>4.2 - 6.2</td>
                            </tr>
							<tr class="mdl_tbl_tr_brdr">
                                <td style="width: 30%;"><b style="border-bottom: 1px solid #000;">Blood Indices</b></td>
                                <td style="width: 20%;"><b></b></td>
                                <td style="width: 20%;"><b></b></td>
                                <td style="width: 30%;"><b></b></td>
                            </tr>
							<tr>
                                <td style="width: 200px;">H.CT</td>
                                <td>37.3</td> 
                                <td>%</td>
                                <td>26 - 50</td>
                            </tr>
							<tr>
                                <td style="width: 200px;">M.C.V.</td>
                                <td>87.4</td> 
                                <td>fL</td>
                                <td>80 - 96</td>
                            </tr>
							<tr>
                                <td style="width: 200px;">Smear Study - RBC</td>
                                <td colspan="4">RBC are Normocytic & Mildly Hypochormic</td> 
                            </tr>
							<tr>
                                <td style="width: 200px;">Smear Study - WBC</td>
                                <td colspan="4">WBC Count is normal.</td> 
                            </tr>
                           <!---- <tr><td colspan="4"><br>Description Description Description Description Description Description Description Description Description </td></tr>----->

                        </table>

                    </div>
                    <?php /* <div class="tbl_btm_mdl_txt"><?php echo $parameter['description']; ?></div> */ ?>
                    <p class="end_rprt">- - - - - - End Of Report - - - - - -</p>
                    <p class="rslt_p_brdr">Result relate only to the sample as received</p>
					<div class="btm_tbl_full_div">
						<table class="btm_tbl_full">
							<tr><td colspan="4">This is an electronically authenticated report.</td></tr>
							<tr>
								<td style="width: 200px;"><b>Pathologist :</b></td>
								<td style="width: 20%;"></td>
								<td style="width: 30%;"></td>
								<td style="width: 20%;">
									<img class="set_sign" src="<?php echo base_url(); ?>user_assets/images/dr_gupta_sign.png"/>
									<b>Dr.Aradhana Gupta <br/> (M.D. Path.)</b>
								</td>
							</tr>
						</table>
					</div>
					
                   <!---- <div class="lst_sign_div_main">
                        <div class="lst_sign_pathologist">
                            <p><b>Pathologist :</b></p>
                        </div>
                        <div class="lst_sign_lst_sign">
							 <img class="set_sign" src="<?php echo base_url(); ?>user_assets/images/dr_gupta_sign.png"/>
                            <p><b>Dr. Aradhana Gupta <br/> (M.D. Path.)</b></p>
                        </div>
                    </div>---->
                    <div class="foot_num_div">
                        <p class="foot_num_p"><img class="set_sign" src="<?php echo base_url(); ?>user_assets/images/pdf_phn_btn.png"/></p>
                        <p class="foot_lab_p">lab at your doorstap</p>
                    </div>
                    <p class="lst_airmed_mdl">Airmed Pathology Labs</p>
                    <p class="lst_31_addrs_mdl">31, Ambika Society, Next to Nabard Bank, Opp. Usmanpura, Ahmedabad, Gujarat - 380 013.</p>
                    <!---<p style="text-align: center;">
                        <span>+91 79 27552277, </span>
                        <span>info@airmedlabs.com, </span>
                        <span>www.airmedlabs.com, </span>
                    </p>---->
                </div>
            </div>
        </div>
    </body>
</html>
