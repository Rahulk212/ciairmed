<thead>
    <tr>
        <th>No</th>
        <th>Reg. No</th>
        <th>Order Id</th>
        <th>Customer Name</th>
        <th>Doctor</th>
        <th>Test/Package Name</th>
        <th>Date</th>
        <!--<th>Payment Type</th>-->
        <!--<th>Total Amount(Rs.)</th>-->
        <th>Payable Amount / Price</th>
        <!--<th>Blood Sample Collation Status</th>-->
        <th>Job Status</th>
        <th width="112px;">Action</th>
    </tr>
</thead>
<tbody>
    <?php
    $cnt = 1 + $page;
    $test_ids = array();
    foreach ($query as $row) {
        $test_ids[] = $row['id'];
        ?>
        <?php
        $style = '';
        if ($row['emergency'] == '1') {
            $style = "background-color:red;color:white;";
        }
        if ($row['status'] == 2) {
            $style = "background-color:#008D4C;color:white;";
        }
        ?>
        <tr>
            <td style="<?= $style; ?>"><?php
                echo $cnt + $pages . " ";
                if ($row['views'] == '0') {
                    echo '<span class="round round-sm blue"> </span>';
                }
                ?> 
            </td>
            <td><?= $row["id"]; ?></td>
            <td style="color:#d73925;">
                <?php
                echo $row["order_id"];
                ?>

            </td>
            <?php
            if ($row['relation'] == '') {
                $row['relation'] = 'Self';
            }
            ?>
            <td>
                <?php if ($row['relation'] == 'Self') { ?>
                    <span style="color:#d73925;"><?php echo ucwords($row['full_name']); ?></span> <?php echo "(" . $row['age'] . " " . $row['age_type'] . "/" . $row['gender'] . ")"; ?>
                    &nbsp;<?= $row['mobile'] ?>
                <?php } else { ?>
                    <span style="color:#d73925;"><?= ucfirst($row['relation']); ?></span> <?php echo "(" . $row['age'] . " " . $row['age_type'] . "/" . $row['gender'] . ")"; ?>
                    &nbsp;<?= $row['rphone'] ?>
                    <br>
                    <span title="Account holder">AC-</span><a style="margin-left:0;" href="<?php echo base_url(); ?>customer-master/customer-all-details/<?php echo $row['cid']; ?>"><?php echo ucwords($row['full_name']); ?></a>&nbsp;<?= $row['mobile'] ?>
                <?php } ?>

                <br>
                <?php
                if ($row["test_city"] == 1) {
                    echo "Ahmedabad";
                } else if ($row["test_city"] == 2) {
                    echo "Surat";
                } else if ($row["test_city"] == 3) {
                    echo "Vadodara";
                } else if ($row["test_city"] == 4) {
                    echo "Gurgaon";
                } else if ($row["test_city"] == 5) {
                    echo "Delhi";
                } else if ($row["test_city"] == 6) {
                    echo "Gandhinagar";
                }
                /* if($branch['portal'] == 'web'){ */
                foreach ($branchlist as $branch) {
                    if ($row['branch_fk'] == $branch['id']) {
                        echo '[' . $branch['branch_name'] . ']';
                    }
                }
                /* }else{
                  foreach ($branchlist as $branch) {
                  if ($row['branch_fk'] == $branch['branch_code']) {
                  echo '[' . $branch['branch_name'] . ']';
                  }
                  }
                  } */
                ?>
                <br><small><b>Added By- </b>
                    <?php
                    if (!empty($row["phlebo_added_by"])) {
                        echo ucfirst($row["phlebo_added_by"]) . " (Phlebo)";
                    } else if (!empty($row["added_by"])) {
                        echo ucfirst($row["added_by"]);
                    } else {
                        echo "Online Booking";
                    }
                    ?>
                </small>
            </td>
            <td><?php if (!empty($row["doctor"])) { ?><?= ucfirst($row["doctor_name"]) . " - " . $row["doctor_mobile"]; ?> <?php
                } else {
                    echo "-";
                }
                ?></td>
            <td><?php
                //$testname = explode(",", $row['testname']);
                //print_R($row["job_test_list"]); die();
                foreach ($row["job_test_list"] as $test) {
                    $is_panel = '';
                    if ($test['is_panel'] == 1) {
                        $is_panel = '(Panel)';
                    }
                    echo "<span id='test_" . $row["id"] . "_" . $test['test_fk'] . "'>" . ucwords($test['test_name']) . " <b>" . $is_panel . "</b></span>" . "<br>";
                    foreach ($test["sub_test"] as $kt_key) {
                        $kt_key["test_name"];
                        if (!in_array($kt_key["sub_test"], $test_list)) {
                            ?>
                            <i style="margin-left:20px">-</i><span id='test_<?= $row['id']; ?>_<?= $kt_key["sub_test"] ?>'><?= $kt_key["test_name"] ?></span><br>
                            <?php
                            $test_list[] = $kt_key["test_fk"];
                        }
                    }
                }
                $test_list = array();
                if (!empty($row["package"])) {
                    foreach ($row["package"] as $key3) {
                        ?>
                        <?php echo ucfirst($key3["name"]); ?><br><?php
                        foreach ($key3["test"] as $kt_key) {
                            $kt_key["test_name"];
                            if (!in_array($kt_key["test_fk"], $test_list)) {
                                ?>
                                <i style="margin-left:20px">-</i><span id='test_<?= $row['id']; ?>_<?= $kt_key["test_fk"] ?>'><?= $kt_key["test_name"] ?></span><br>
                                <?php
                                $test_list[] = $kt_key["test_fk"];
                            }
                        }
                    }
                }
                ?>
            </td>
            <td><?php echo ucwords($row['date']); ?></td>

            <td><?php
                $payable_amount = 0;
                /* Nishit code start */
                $color_code = '#00A65A';
                if ($row['payable_amount'] > 0) {
                    $color_code = '#D33724';
                }
                /* END */
                if ($row['payable_amount'] == "") {
                    echo "<spam style='color:white;background:" . $color_code . ";padding:2px'>Rs. 0";
                } else {
                    $payable_amount = $row['payable_amount'];
                    echo "<spam style='color:white;background:" . $color_code . ";padding:2px'>Rs. " . number_format((float) $row['payable_amount'], 2, '.', '');
                }
                ?> /<?= " Rs." . number_format((float) $row["price"], 2, '.', '') . "</spam>"; ?>
                <?php
                if ($row["cut_from_wallet"] != 0) {
                    echo "<br><small>(Rs." . number_format((float) $row["cut_from_wallet"], 2, '.', '') . " Debited from wallet)</small>";
                }
                if ($row["discount"] != '' && $row["discount"] != '0') {
                    $dprice1 = $row["discount"] * $row["price"] / 100;
                    $discount_amount = $discount_amount + $dprice1;
                    echo "<br><small>( Rs." . number_format((float) $dprice1, 2, '.', '') . " Discount)</small>";
                }
                if ($row["collection_charge"] == 1) {
                    echo "<br><small>( Rs." . number_format((float) 100, 2, '.', '') . " Collection charge)</small>";
                }
                ?>
            </td>

            <td>

                <?php
                if ($row['status'] == 1) {
                    echo "<span class='label label-danger '>Waiting For Approval</span>";
                }
                ?>
                <?php
                if ($row['status'] == 6) {
                    echo "<span class='label label-warning '>Approved</span>";
                }
                ?>
                <?php
                if ($row['status'] == 7) {
                    echo "<span class='label label-warning '>Sample Collected</span>";
                }
                ?>
                <?php
                if ($row['status'] == 8) {
                    echo "<span class='label label-warning '>Processing</span>";
                }
                ?>
                <?php
                if ($row['status'] == 2) {
                    echo "<span class='label label-success '>Completed</span>";
                }
                ?>
                <?php
                if ($row['status'] == 0) {
                    echo "<span class='label label-danger '>User Deleted</span>";
                }
                ?>
                <br><?php
                if ($row['emergency'] == '1') {
                    echo "<span class='label label-danger '>Emergency</span><br>";
                }
                if ($row["dispatch"] == 1) {
                    echo "<span class='label label-success '>Dispatched</span>";
                }
                ?>
                <?php /* if($row['status']=="1" && $row['sample_collection']=="0" ){ echo "<span class='label label-danger'>Pending</span>"; }else if($row['status']=="2"){ echo "<span class='label label-success'>Completed</span>"; }else if($row['status']=="3"){ echo "<span class='label label-danger'>Spam</span>"; }else if($row['sample_collection']==1){ echo "<span class='label label-warning'>Processing</span>";} 
                 */
                ?> 

            </td>
            <td>
                <!--<a  href='<?php echo base_url(); ?>job_master/job_mark_spam/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Mark as Spam" ><span class="label label-danger">Mark as Spam</span></a>                                                                                                                                                                                                                                                                                                                                                                      <a  href='<?php echo base_url(); ?>job_master/job_mark_completed/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Mark as completed" ><span class="label label-success">Mark as completed</span> </a>  --> 
                <a  href='<?php echo base_url(); ?>job-master/job-details/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="View Job Details" target="_blank"> <span class=""><i class="fa fa-eye"> </i></span> </a>

                <?php if ($login_data['type'] == 1 || $login_data['type'] == 2) { ?>

                    <a  onclick="return confirm('Are you sure you want to spam this job?');" href='<?php echo base_url(); ?>job_master/changing_spam/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Spam Job" > <span class=""><i class="fa fa-trash"> </i></span> </a>
                <?php } ?>                                                 
    <!--                <a  href='<?php echo base_url(); ?>job_master/job_dispatch/<?php echo $row['id']; ?>/<?php
                if ($row["dispatch"] == 1) {
                    echo "0";
                } else {
                    echo "1";
                }
                ?>' data-toggle="tooltip" data-original-title="<?php
                if ($row["dispatch"] == 1) {
                    echo "Dispatched";
                } else {
                    echo "Mark As Dispatched";
                }
                ?>" onclick="return confirm('Are you sure?');"> <span class="<?php
                if ($row["dispatch"] == 1) {
                    echo "success";
                } else {
                    echo "warning";
                }
                ?>"><i class="fa fa-truck"> </i></span> </a>-->
                <a id="dispatch_1_<?php echo $row['id']; ?>" style="display:<?php
                if ($row["dispatch"] == 0) {
                    echo "none";
                }
                ?>" href='javascript:void(0)' data-toggle="tooltip" data-original-title="Dispatched" onclick="dispatched_job_test('<?php echo $row['id']; ?>', '0');"> <span class="success"><i class="fa fa-truck"> </i></span> </a>
                <a id="dispatch_0_<?php echo $row['id']; ?>" style="display:<?php
                if ($row["dispatch"] == 1) {
                    echo "none";
                }
                ?>" href='javascript:void(0)' data-toggle="tooltip" data-original-title="Mark As Dispatched" onclick="dispatched_job_test('<?php echo $row['id']; ?>', '1');"> <span class="warning"><i class="fa fa-truck"> </i></span> </a>
                   <?php if (!empty($row["report"])) { ?>
                       <?php
                       if ($row["is_print"] == 0 && $row["panel_test_count"] == 0) {
                           if ($payable_amount <= 0) {
                               ?>
                            <a href='javascript:void(0);' data-toggle="tooltip" data-original-title="Print Report" onclick="$('#print_model').modal('show');
                                    $('#print_simple').attr('href', '<?= base_url() ?>job_master/open_report/<?= $row["id"] ?>/1');
                                    $('#print_wlpd').attr('href', '<?= base_url() ?>job_master/open_report/<?= $row["id"] ?>/2');"><span class=""><i class="fa fa-print"><?php
                               if ($row["print_cnt"][0]["cnt"] > 0) {
                                   echo "(" . $row["print_cnt"][0]["cnt"] . ")";
                               }
                               ?></i></span></a>
                            <a href='javascript:void(0);' onclick="<?php if ($payable_amount <= 0 || $login_data['type'] == 5) { ?>sms_popup('<?= $row["id"] ?>');<?php } else { ?>alert('Please collect due amount.');<?php } ?>" data-toggle="tooltip" data-original-title="Send Report Via SMS" <?php if (!empty($row["send_repor_sms"])) { ?> style="background:green;color:white;width:auto;padding:0px 5px 3px 5px;border-radius:3px;" <?php } ?>><span class=""><i class="fa fa-comment-o"></i></span></a>
                            <a href='javascript:void(0);' onclick="<?php if ($payable_amount <= 0 || $login_data['type'] == 5) { ?>mail_popup('<?= $row["id"] ?>', '<?= $row["cid"] ?>');<?php } else { ?>alert('Please collect due amount.');<?php } ?>" data-toggle="tooltip" data-original-title="Send Report Via Mail" <?php if (!empty($row["send_report_mail"])) { ?> style="background:green;color:white;width:auto;padding:0px 5px 3px 5px;border-radius:3px;" <?php } ?>><span class=""><i class="fa fa-envelope-o"></i></span></a>
                        <?php } ?>
                    <?php } else { ?>
                        <a href='javascript:void(0);' data-toggle="tooltip" data-original-title="Print Report" onclick="$('#print_model').modal('show');
                                $('#print_simple').attr('href', '<?= base_url() ?>job_master/open_report/<?= $row["id"] ?>/1');
                                $('#print_wlpd').attr('href', '<?= base_url() ?>job_master/open_report/<?= $row["id"] ?>/2');"><span class=""><i class="fa fa-print"><?php
                           if ($row["print_cnt"][0]["cnt"] > 0) {
                               echo "(" . $row["print_cnt"][0]["cnt"] . ")";
                           }
                           ?></i></span></a>
                        <a href='javascript:void(0);' onclick="<?php if ($payable_amount <= 0 || $login_data['type'] == 5) { ?>sms_popup('<?= $row["id"] ?>');<?php } else { ?>alert('Please collect due amount.');<?php } ?>" data-toggle="tooltip" data-original-title="Send Report Via SMS" <?php if (!empty($row["send_repor_sms"])) { ?> style="background:green;color:white;width:auto;padding:0px 5px 3px 5px;border-radius:3px;" <?php } ?>><span class=""><i class="fa fa-comment-o"></i></span></a>
                        <a href='javascript:void(0);' onclick="<?php if ($payable_amount <= 0) { ?>mail_popup('<?= $row["id"] ?>', '<?= $row["cid"] ?>');<?php } else { ?>alert('Please collect due amount.');<?php } ?>" data-toggle="tooltip" data-original-title="Send Report Via Mail" <?php if (!empty($row["send_report_mail"])) { ?> style="background:green;color:white;width:auto;padding:0px 5px 3px 5px;border-radius:3px;" <?php } ?>><span class=""><i class="fa fa-envelope-o"></i></span></a>
                    <?php } ?>
                <?php } ?>

                <?php if ($payable_amount > 0) { ?>
                    <a onclick="return confirm('Are you sure?');" href='<?= base_url(); ?>/job_master/send_sms_due_payment/<?= $row["id"] ?>' target="_blank" data-toggle="tooltip" data-original-title="Send SMS" ><span class=""><i class="fa fa-inr fa-6"></i></span></a>
                <?php } ?>
                <a href='<?php if (!empty($row['invoice'])) { ?><?= base_url(); ?>upload/result/<?php echo $query[0]['invoice']; ?><?php } else { ?><?= base_url(); ?>job_master/pdf_invoice/<?= $row["id"] ?><?php } ?>' target="_blank" data-toggle="tooltip" data-original-title="Download Invoice"><span class=""><i class="fa fa-download fa-6"></i></span></a>
                <a href='<?= base_url(); ?>job_master/ack/<?= $row["id"] ?>' data-toggle="tooltip" data-original-title="Download ACK" target="_blank"><span class=""><i class="fa fa-download fa-6"></i></span></a>
                <a href='javascript:voic();' onclick="open_barcode_popup('<?= $row["id"] ?>');" data-toggle="tooltip" data-original-title="Print Barcode"><span class=""><i class="fa fa-barcode"></i></span></a>
            </td>
        </tr>
        <?php
        $cnt++;
    }if (empty($query)) {
        ?>
        <tr>
            <td colspan="10">No records found</td>
        </tr>
    <?php } ?>
    <tr style="display:none;">
        <td colspan="10" id="search_test_ids"><?php echo implode(",", $test_ids); ?></td>
    </tr>
    <tr>
        <td colspan="10" style="text-align:right;"><?= $link; ?></td>
    </tr>
</tbody>