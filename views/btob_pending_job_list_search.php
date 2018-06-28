<thead>
    <tr>
        <th>No</th>
        <th>Reg. No</th>
        <th>Order Id</th>
        <th>Barcode</th>

        <th>Customer Name</th>
        
        <th>Test/Package Name</th>
        <th>Date</th>
        <!--<th>Payment Type</th>-->
        <!--<th>Total Amount(Rs.)</th>-->
       
        <!--<th>Blood Sample Collation Status</th>-->
        <th>Job Status</th>
        <th width="112px;">Action</th>
    </tr>
</thead>
<tbody>
    <?php
    $cnt = 1;
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
 <td><?= $row["barcode"]; ?></td>
                      
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
                ?>
            </td>
            <td><?php echo ucwords($row['date']); ?></td>


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
</tbody>