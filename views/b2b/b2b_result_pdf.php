<html>
    <head>
        <meta charset="utf-8">
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <style>
            body {font-family: 'Roboto', sans-serif;}
            .pdf_container {width: 970px; margin: 0 auto;}
            .main_set_pdng_div {width: 100%; float: left; padding: 0 0;}
            .brdr_full_div { float: left; padding: 10px; width: 100%;}
            .full_div {width: 100%; float: left;}
            .header_full_div {float: left; padding: 0px 10px 5px 10px; width: 92%; height:80px;}
            .set_logo {width: 180px; float: right;}
            .testreport_full {width: 100%; float: left;}
            .tst_rprt {border-bottom: 1px solid #000000; border-top: 1px solid #000000; margin: 0; padding: 5px 0; text-transform: uppercase;width: 100%; float:left;}
            .tst_rprt > img {}
            .tst_rprt h3 {margin: 0; text-align: center;}
            .tbl_full {width: 100%; font-size: 12px;}
            .mdl_tbl_full_div {width: 100%; float: left; min-height: 500px;margin: 0px 5px 0px 5px;}
            .btm_tbl_full_div {width: 100%; float: left;}

            .mdl_tbl_full {width: 100%; font-size: 12px; margin-top: 5px; border-top:1px solid #000;border-bottom:1px solid #000;}
            .mdl_tbl_full1 {width: 100%; font-size: 12px; margin-top: 20px; margin-left: 10px;}
            .btm_tbl_full {width: 100%; font-size: 12px; margin-top: 20px;}
            .mdl_tbl_big_titl {border-bottom: 1px solid #000000; font-size: 12px; font-weight: bold; text-align: center; margin-top: 20px; margin-bottom: 20px; display: inline-block;}
            .mdl_tbl_tr_brdr {border-bottom: 2px solid #000000; border-top: 2px solid #000000; /*display: table; width: 100%;*/}
            .brdr_btm {border-bottom: 1px solid #000;}
            .end_rprt {text-align: center; float: left; width: 100%;}
            .rslt_p_brdr {border-bottom: 1px solid #000000; float: left; margin: 0; padding-bottom: 5px; text-align: center; width: 100%;}
            .this_p {float: left; margin-top: 5px;}
            .lst_sign_div_main {width: 100%; float: left; margin-top: 22px;}
            .lst_sign_pathologist {float: left;margin-right: 19%;padding-left: 10%;width: 44%;}
            .lst_sign_mdl_sign {width: 29%; float: left;}
            .lst_sign_lst_sign {width: 25%; float: left;}
            .foot_num_div {width: 100%; float: left; padding-bottom: 2px; height: 60px;}
            .foot_num_p {text-align: center; margin-bottom: 10px;}
            .foot_num_p span {background-color: #E30026; border-radius: 25px; padding: 3px 15px; color: #fff;}
            .foot_lab_p {margin: 0; text-align: center; text-transform: uppercase;  border-bottom: 3px dotted #9D0902; padding-bottom: 15px;}
            .lst_ison_ul {display: inline-block; padding: 0; text-align: center; width: 100%; amrgin-top: 5px;}
            .lst_ison_ul li {display: inline-block; margin-right: 15px;}
            .lst_icon_spn_back {background-color: #e30026; border-radius: 50%; float: left; height: 16px; margin-right: 9px; padding: 4px; width: 16px;}
            .lst_icon_spn_back .fa {color: #fff;}
            .lst_airmed_mdl {float: left; margin-bottom: 0; margin-top: 0px; text-align: center; width: 100%;}
            .lst_31_addrs_mdl {float: left; margin: 0; text-align: center; width: 100%;}
            .tbl_btm_mdl_txt {width: 80%; float: left; padding: 0 98px; font-weight: bold;}
            .btm_tbl_full b {float: left; width: 100%;}
            .mdl_tbl_td_title{text-align:center; width:100%; border-top:1px solid #000;}
            .tst_rprt_title{width:40%;float:left;}
            .brcd_div{width:30%; float:left;}
            tr {
                line-height: 0.8;

            }
        </style>
    </head>
    <body>
        <hr>
                <table class="mdl_tbl_full1" repeat_header="1">
                    <thead>
            </thead>
          <?php foreach($fileuplode as $file){ ?>
       
                <tr style="text-align: center; width: 100%;">
                    <td>
                <center>
                    <img src="<?= FCPATH; ?>upload/business_report/<?= $file; ?>" height="auto" width="100%"/> 
                   
                </center>
                </td>
                </tr>
		  <?php } ?>
               
        </table>
   

</body>
</html>
