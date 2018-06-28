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
        <?php
        $ts = explode('#', $query[0]['testname']);
        $tid = explode(",", $query[0]['testid']);
        $cnt = 0;
        $page_break = 0;
        foreach ($new_data_array1 as $testidp) {
            $parameter_cnt = 0;
            if (!empty($testidp[0]["parameter"])) {
                $parameter_val_cnt = 0;
                foreach ($testidp[0]["parameter"] as $parameter) {
                    if (!empty($parameter['user_val'])) {
                        $parameter_val_cnt++;
                    }
                }
                if ($parameter_val_cnt != 0) {
                    // if ($parameter_list[$cnt][0]['test_fk'] == $testidp) {
                    ?>
                    <?php if ($cnt != 0 && $testidp["on_new_page"] == 1) { ?>
                    <pagebreak />
                <?php } $parameter_cnt++; ?>
                    <style>
                      //  div.header_tbl{display:none;}
                    </style>
                    <table class="mdl_tbl_full">
                        <thead>
                            <tr class="mdl_tbl_tr_brdr">
                                <td style="width: 30%;"><b style="border-bottom: 1px solid #000;">ANTIBIOTIC NAME</b></td>
                                <td style="width: 20%;"><b style="border-bottom: 1px solid #000;">SENSITIVITY</b></td>
                                <td style="width: 20%;"><b style="border-bottom: 1px solid #000;"></b></td>
                                <td style="width: 30%;"><b style="border-bottom: 1px solid #000;">ZONE OF INHIBITION</b></td>
                            </tr>
                        </thead>
                    </table>

                <table class="mdl_tbl_full1" repeat_header="1">
                    <thead>

                        <tr style=" width: 100%;">
                            <td colspan="4" class=""><p class="mdl_tbl_big_titl"><center><?php echo ucwords(($testidp["department_name"])); ?></center></p></td>
            </tr>
            <tr style=" width: 100%;">
                <td colspan="4" class=""><p class="mdl_tbl_big_titl"><?php echo ucwords(($testidp["test_name"])); ?></p></td>
            </tr>
            </thead>
            <?php
            $temp = '1';
            $cn = 0;
            $is_group_cnt = 0;
            $main_cnt = 0;
            foreach ($testidp[0]["parameter"] as $parameter) {
                if ($parameter["is_group"] != '1') {
                    if (!empty($parameter['parameter_name']) && !empty($parameter['user_val'])) {
                        if (count($parameter['user_val']) > 0) {
                            $status = "Normal";
                            if ($parameter["para_ref_rng"][0]['absurd_low'] > $parameter['user_val'][0]["value"]) {
                                $status = "Emergency";
                            }
                            if ($parameter["para_ref_rng"][0]['ref_range_low'] > $parameter['user_val'][0]["value"]) {
                                $status = $parameter["para_ref_rng"][0]['low_remarks'];
                            }
                            if ($parameter["para_ref_rng"][0]['critical_low'] > $parameter['user_val'][0]["value"]) {
                                $status = $parameter["para_ref_rng"][0]['critical_low_remarks'];
                            }
                            if ($parameter["para_ref_rng"][0]['ref_range_high'] < $parameter['user_val'][0]["value"]) {
                                $status = $parameter["para_ref_rng"][0]['high_remarks'];
                            }
                            if ($parameter["para_ref_rng"][0]['critical_high'] < $parameter['user_val'][0]["value"]) {
                                $status = $parameter["para_ref_rng"][0]['critical_high_remarks'];
                            }
                        } else {
                            $status = "";
                        }
                        $is_group_cnt++;
                        ?>
                        <tr>
                            <td style="width: 230px;"><?php echo $parameter['parameter_name']; ?></td>
                            <?php
                            $res = '';
                            $is_text = 0;
                            if (isset($parameter["para_ref_rng"][0]['id'])) {
                                ?>
                                <?php $res = $parameter['user_val'][0]["value"]; ?><?php $status; ?>
                            <?php } else { ?>
                                <?php
                                if (!empty($parameter["para_ref_status"])) {
                                    foreach ($parameter["para_ref_status"] as $kky) {
                                        if ($parameter['user_val'][0]["value"] == $kky["id"]) {
                                            $is_text = 1;
                                            $res = $kky["parameter_name"];
                                        }
                                    }
                                } else {
                                    $res = $parameter['user_val'][0]["value"];
                                }
                                ?>
                            <?php } ?>
                            <td <?php
                            if ($is_text == 1 || strlen($res) > 20) {
                                echo 'colspan="3"';
                            }
                            ?> style="width: 150px;">
                                <?php
                                if (!empty(trim($parameter["para_ref_rng"][0]["ref_range"]))) {
                                    echo $res;
                                } else {
                                    if (( $parameter["para_ref_rng"][0]['ref_range_low'] <= $res ) && ( $res <= $parameter["para_ref_rng"][0]['ref_range_high'] ) || ($parameter["para_ref_rng"][0]['ref_range_low'] == "")) {
                                        //      if (( 100<=4400 ) && ( 4400<=200 )) {
                                        echo "" . $res;
                                    } else {
                                        echo "<div style='font-weight: bold;'>" . $res . "</div>";
                                    }
                                }
                                ?>
                            </td> 
                            <?php if ($is_text == 0 && strlen($res) <= 20) { ?>
                                <td  style="width: 140px;"><?php echo $parameter['parameter_unit']; ?></td>
                                <td>  <?php
                                    if (!empty(trim($parameter["para_ref_rng"][0]["ref_range"]))) {
                                        echo "<div style='font-size:9px;'>" . $parameter["para_ref_rng"][0]["ref_range"] . "</div>";
                                    } else {
                                        echo "<div style=''>" . $parameter["para_ref_rng"][0]['ref_range_low'] . " - " . $parameter["para_ref_rng"][0]['ref_range_high'] . "</div>";
                                    }
                                    ?></td>
                            <?php } ?>
                        </tr>
                        <?php if (!empty(trim($parameter['description']))) { ?>
                            <tr><td colspan="5"><br><?= "<div style='font-weight: bold;font-size:9px;'>" . $parameter['description'] . "</div>" ?><br/><br></td></tr>
                        <?php } ?>

                        <?php
                    } $cnt++;
                    ?>
                    <?php
                } else {
                    /* if ($testidp[0]["parameter"][$cnt+1][]) { */
                    if (!empty($testidp[0]["parameter"][$main_cnt + 1]['parameter_name']) && !empty($testidp[0]["parameter"][$main_cnt + 1]['user_val'])) {
                        ?>
                        <tr style="">
                            <td colspan="4"><div style="border-bottom: 1px solid black;font-weight: bold;"><?php echo $parameter['parameter_name']; ?></div></td>
                        </tr>
                        <?php
                        $is_group_cnt = 0;
                    }
                }
                $main_cnt++;
            }
            ?>
            <?php if (empty($testidp["graph"])) { ?>
                <tr style="text-align: center; width: 100%;">
                    <td colspan="4"><center><br/>- - - - - - End Of Report - - - - - -</center></td>
                </tr>
            <?php } ?>
            </table>
            <?php
        }
    }
    ?>
    <?php
    if (!empty($testidp["graph"])) {
        echo '<table class="mdl_tbl_full1">';
        if ($parameter_cnt == 0) {
            ?><thead>
                <tr style="text-align: center; width: 100%;">
                    <td colspan="4" class=""><p class="mdl_tbl_big_titl"><center><?php echo ucfirst($testidp["test_name"]); ?></center></p></td>
            </tr>
            </thead><?php
        }
        $itest = 0;
        foreach ($testidp['graph'] as $g_key) {
            if (!empty($g_key["pic"])) {
                $itest++;
                ?>
                <tr style="text-align: center; width: 100%;">
                    <td>
                <center>
                    <img src="<?= FCPATH; ?>upload/report/graph/<?= $g_key["pic"] ?>" height="auto" width="100%"/> 
                    <?php if (count($testidp['graph']) == $itest) { ?>
                        <br> - - - - - - End Of Report - - - - - -
                    <?php } ?>
                </center>
                </td>
                </tr>
                <?php
            }
        }
        ?>
        </table>
        <?php
    }
}
?>

</body>
</html>
