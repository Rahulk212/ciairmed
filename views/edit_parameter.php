<div id="edit_div">
        <div class="col-sm-8" style="padding-left:0">
            <div class="form-group">
                <label for="exampleInputFile">Exist Parameter</label>
                <select class="form-control" name="exist_para" id="exist_para">
                    <option value="">--Select--</option>
                    <?php foreach ($parameter_list as $parameter) { ?>
                        <option value="<?php echo $parameter['id']; ?>" <?php
                        if ($pid == $parameter['id']) {
                            echo 'selected';
                        }
                        ?>><?php echo $parameter['parameter_name']; ?></option>
                            <?php } ?>
                </select>
                <span style="color:red;" id="copy_parameter"></span>
            </div>
        </div>
        <div class="col-sm-1">
            <div class="form-group">
                <label for="exampleInputFile"> </label>
                <button class="btn btn-primary" type="button" style=" margin-top: 24px;padding: 2px 10px;" onclick="copy_value();">Copy</button>
            </div>
        </div>
    <table id="" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Unit</th>
                <th>Formula</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <input type="text" name="par_name" id="par_name" class="form-control" value="<?php echo $parameter_details[0]['parameter_name']; ?>">
                </td>
                <td>
                    <select class="form-control" name="par_unit" id="par_unit">
                        <option value="">--Select--</option>
                        <?php foreach ($unit_list as $unit) { ?>
                            <option value="<?php echo $unit['PARAMETER_NAME']; ?>" <?php
                            if ($parameter_details[0]['parameter_unit'] == $unit['PARAMETER_NAME']) {
                                echo "selected";
                            }
                            ?>><?php echo $unit['PARAMETER_NAME']; ?></option>
                                <?php } ?>
                    </select>
                </td>
                <td>
                    <input type="text" name="par_formula" id="par_formula" class="form-control" value="<?php echo $parameter_details[0]['formula']; ?>">
                </td>
            </tr>
        </tbody>
    </table>
    <div class="table-responsive set_inpt_wdth">
        <table id="" class="table table-bordered table-striped">
            <h2>Reference Range</h2>
            <thead>
                <tr>
                    <th>Add/Remove</th>
                    <th>Gender</th>
                    <th>No of Period</th>
                    <th>Type of Period</th>
                    <th>Normal Remarks</th>
                    <th>Ref Range Low</th>
                    <th>Low Remarks</th>
                    <th>Ref Range High</th>
                    <th>High Remarks</th>
                    <th>Critical Low</th>
                    <th>Critical Low Remarks</th>
                    <th>Critical High</th>
                    <th>Critical High Remarks</th>
                    <th>Critical Low Sms</th>
                    <th>Critical High Sms</th>
                    <th>Repeat Low</th>
                    <th>Repeat Low Remarks</th>
                    <th>Repeat High</th>
                    <th>Repeat High Remarks</th>
                    <th>Absurd Low</th>
                    <th>Absurd High</th>
                    <th>Ref Range</th>
                </tr>
            </thead>
            <tbody id="table_body1">
                <?php
                $cnt = 1;
                foreach ($reference_details as $refe) {
                    ?>
                    <tr id="edit_tr_<?php echo $cnt; ?>">
                        <td>
                            <a class="srch_view_a" href="javascript:void(0)" onclick="remove_edit_tr('<?php echo $cnt; ?>','<?php echo $refe['id']; ?>');"><i style="color:red;" class="fa fa-minus-square"></i></a>
                        </td>
                        <td><input type="hidden" name="edit_par_ref_id_<?php echo $cnt; ?>" id="edit_par_ref_id_<?php echo $cnt; ?>" value="<?php echo $refe['id']; ?>">
                            <select class="form-control" name="edit_par_gender_<?php echo $cnt; ?>" id="edit_par_gender_<?php echo $cnt; ?>">
                                <option value="">--Select--</option>
                                <option value="M" <?php
                                if ($refe['gender'] == 'M') {
                                    echo "selected";
                                }
                                ?>>Male</option>
                                <option value="F" <?php
                                if ($refe['gender'] == 'F') {
                                    echo "selected";
                                }
                                ?>>Female</option>
                                <option value="B" <?php
                                if ($refe['gender'] == 'B') {
                                    echo "selected";
                                }
                                ?>>Both</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="edit_par_no_period_<?php echo $cnt; ?>" id="edit_par_no_period_<?php echo $cnt; ?>" value="<?php echo $refe['no_period']; ?>" class="form-control">
                        </td>
                        <td>
                            <select class="form-control" name="edit_par_type_period_<?php echo $cnt; ?>" id="edit_par_type_period_<?php echo $cnt; ?>">
                                <option value="">--Select--</option>
                                <option value="D" <?php
                                if ($refe['type_period'] == 'D') {
                                    echo "selected";
                                }
                                ?>>Days</option>
                                <option value="M" <?php
                                if ($refe['type_period'] == 'M') {
                                    echo "selected";
                                }
                                ?>>Months</option>
                                <option value="Y" <?php
                                if ($refe['type_period'] == 'Y') {
                                    echo "selected";
                                }
                                ?>>Years</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="edit_par_normal_remark_<?php echo $cnt; ?>" id="edit_par_normal_remark_<?php echo $cnt; ?>" value="<?php echo $refe['normal_remarks']; ?>" class="form-control">
                        </td>
                        <td>
                            <input type="text" name="edit_par_ref_range_low_<?php echo $cnt; ?>" id="edit_par_ref_range_low_<?php echo $cnt; ?>" value="<?php echo $refe['ref_range_low']; ?>" class="form-control">
                        </td>
                        <td>
                            <input type="text" name="edit_par_low_remark_<?php echo $cnt; ?>" id="edit_par_low_remark_<?php echo $cnt; ?>" value="<?php echo $refe['low_remarks']; ?>" class="form-control">
                        </td>
                        <td>
                            <input type="text" name="edit_par_ref_range_high_<?php echo $cnt; ?>" id="edit_par_ref_range_high_<?php echo $cnt; ?>" value="<?php echo $refe['ref_range_high']; ?>" class="form-control">
                        </td>
                        <td>
                            <input type="text" name="edit_par_high_remark_<?php echo $cnt; ?>" id="edit_par_high_remark_<?php echo $cnt; ?>" value="<?php echo $refe['high_remarks']; ?>" class="form-control">
                        </td>
                        <td>
                            <input type="text" name="edit_par_critical_low_<?php echo $cnt; ?>" id="edit_par_critical_low_<?php echo $cnt; ?>" value="<?php echo $refe['critical_low']; ?>" class="form-control">
                        </td>
                        <td>
                            <input type="text" name="edit_par_critical_low_remark_<?php echo $cnt; ?>" id="edit_par_critical_low_remark_<?php echo $cnt; ?>" value="<?php echo $refe['critical_low_remarks']; ?>" class="form-control">
                        </td>
                        <td>
                            <input type="text" name="edit_par_critical_high_<?php echo $cnt; ?>" id="edit_par_critical_high_<?php echo $cnt; ?>" value="<?php echo $refe['critical_high']; ?>" class="form-control">
                        </td>
                        <td>
                            <input type="text" name="edit_par_critical_high_remark_<?php echo $cnt; ?>" id="edit_par_critical_high_remark_<?php echo $cnt; ?>" value="<?php echo $refe['critical_high_remarks']; ?>" class="form-control">
                        </td>
                        <td>
                            <input type="text" name="edit_par_critical_low_sms_<?php echo $cnt; ?>" id="edit_par_critical_low_sms_<?php echo $cnt; ?>" value="<?php echo $refe['critical_low_sms']; ?>" class="form-control">
                        </td>
                        <td>
                            <input type="text" name="edit_par_critical_high_sms_<?php echo $cnt; ?>" id="edit_par_critical_high_sms_<?php echo $cnt; ?>" value="<?php echo $refe['critical_high_sms']; ?>" class="form-control">
                        </td>
                        <td>
                            <input type="text" name="edit_par_repeat_low_<?php echo $cnt; ?>" id="edit_par_repeat_low_<?php echo $cnt; ?>" value="<?php echo $refe['repeat_low']; ?>" class="form-control">
                        </td>
                        <td>
                            <input type="text" name="edit_par_repeat_low_remark_<?php echo $cnt; ?>" id="edit_par_repeat_low_remark_<?php echo $cnt; ?>" value="<?php echo $refe['repeat_low_remarks']; ?>" class="form-control">
                        </td>
                        <td>
                            <input type="text" name="edit_par_repeat_high_<?php echo $cnt; ?>" id="edit_par_repeat_high_<?php echo $cnt; ?>" value="<?php echo $refe['repeat_high']; ?>" class="form-control">
                        </td>
                        <td>
                            <input type="text" name="edit_par_repeat_high_remark_<?php echo $cnt; ?>" id="edit_par_repeat_high_remark_<?php echo $cnt; ?>" value="<?php echo $refe['repeat_high_remarks']; ?>" class="form-control">
                        </td>
                        <td>
                            <input type="text" name="edit_par_absurd_low_<?php echo $cnt; ?>" id="edit_par_absurd_low_<?php echo $cnt; ?>" value="<?php echo $refe['absurd_low']; ?>" class="form-control">
                        </td>
                        <td>
                            <input type="text" name="edit_par_absurd_high_<?php echo $cnt; ?>" id="edit_par_absurd_high_<?php echo $cnt; ?>" value="<?php echo $refe['absurd_high']; ?>" class="form-control">
                        </td>
                        <td>
                            <textarea name="edit_par_ref_range_<?php echo $cnt; ?>" id="edit_par_ref_range_<?php echo $cnt; ?>" class="form-control"><?php echo $refe['ref_range']; ?></textarea>
                        </td>

                    </tr>
                    <?php
                    $cnt++;
                }
                ?>
            <input type="hidden" name="edit_count_par" id="edit_count_par" value="<?php echo $cnt; ?>">
            <tr>
                <td>
                    <a class="srch_view_a" href="javascript:void(0)" onclick="add_field();"><i class="fa fa-plus-square"></i></a>
                </td>
                <td><input type="hidden" name="count_par" id="count_par" value="1">
                    <select class="form-control" name="par_gender_1" id="par_gender_1">
                        <option value="">--Select--</option>
                        <option value="M">Male</option>
                        <option value="F">Female</option>
                        <option value="B">Both</option>
                    </select>
                </td>
                <td>
                    <input type="text" name="par_no_period_1" id="par_no_period_1" class="form-control">
                </td>
                <td>
                    <select class="form-control" name="par_type_period_1" id="par_type_period_1">
                        <option value="">--Select--</option>
                        <option value="D">Days</option>
                        <option value="M">Months</option>
                        <option value="Y">Years</option>
                    </select>
                </td>
                <td>
                    <input type="text" name="par_normal_remark_1" id="par_normal_remark_1" class="form-control">
                </td>
                <td>
                    <input type="text" name="par_ref_range_low_1" id="par_ref_range_low_1" class="form-control">
                </td>
                <td>
                    <input type="text" name="par_low_remark_1" id="par_low_remark_1" class="form-control">
                </td>
                <td>
                    <input type="text" name="par_ref_range_high_1" id="par_ref_range_high_1" class="form-control">
                </td>
                <td>
                    <input type="text" name="par_high_remark_1" id="par_high_remark_1" class="form-control">
                </td>
                <td>
                    <input type="text" name="par_critical_low_1" id="par_critical_low_1" class="form-control">
                </td>
                <td>
                    <input type="text" name="par_critical_low_remark_1" id="par_critical_low_remark_1" class="form-control">
                </td>
                <td>
                    <input type="text" name="par_critical_high_1" id="par_critical_high_1" class="form-control">
                </td>
                <td>
                    <input type="text" name="par_critical_high_remark_1" id="par_critical_high_remark_1" class="form-control">
                </td>
                <td>
                    <input type="text" name="par_critical_low_sms_1" id="par_critical_low_sms_1" class="form-control">
                </td>
                <td>
                    <input type="text" name="par_critical_high_sms_1" id="par_critical_high_sms_1" class="form-control">
                </td>
                <td>
                    <input type="text" name="par_repeat_low_1" id="par_repeat_low_1" class="form-control">
                </td>
                <td>
                    <input type="text" name="par_repeat_low_remark_1" id="par_repeat_low_remark_1" class="form-control">
                </td>
                <td>
                    <input type="text" name="par_repeat_high_1" id="par_repeat_high_1" class="form-control">
                </td>
                <td>
                    <input type="text" name="par_repeat_high_remark_1" id="par_repeat_high_remark_1" class="form-control">
                </td>
                <td>
                    <input type="text" name="par_absurd_low_1" id="par_absurd_low_1" class="form-control">
                </td>
                <td>
                    <input type="text" name="par_absurd_high_1" id="par_absurd_high_1" class="form-control">
                </td>
                <td>
                    <textarea name="par_ref_range_1" id="par_ref_range_1" class="form-control"></textarea>
                </td>

            </tr>
            </tbody>
        </table>
    </div>
    <table id="example4" class="table table-bordered table-striped">
        <h2>Reference Status</h2>
        <thead>
            <tr>
                <th>Add/Remove</th>
                <th>Parameter Code</th>
                <th>parameter Name</th>
                <th>Result Status</th>
                <th>Critical Status</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody id="table_body">
            <?php
            $cn = 1;
            foreach ($status_details as $status) {
                ?>
                <tr id="edit_tr_sts_<?php echo $cn; ?>">
                    <td>
                        <a class="srch_view_a" href="javascript:void(0)" onclick="remove_edit_tr1('<?php echo $cn; ?>','<?php echo $status['id']; ?>');"><i style="color:red;" class="fa fa-minus-square"></i></a>
                    </td>
                    <td>
                        <input type="text" name="edit_par_code_<?php echo $cn; ?>" id="edit_par_code_<?php echo $cn; ?>" value="<?php echo $status['parameter_code']; ?>" class="form-control">
                    </td>
                    <td>
                        <input type="text" name="edit_par_name_<?php echo $cn; ?>" id="edit_par_name_<?php echo $cn; ?>" value="<?php echo $status['parameter_name']; ?>" class="form-control">
                    </td>
                    <td><input type="hidden" name="edit_par_status_id_<?php echo $cn; ?>" id="edit_par_status_id_<?php echo $cn; ?>" value="<?php echo $status['id']; ?>">
                        <select class="form-control" name="edit_par_result_<?php echo $cn; ?>" id="edit_par_result_<?php echo $cn; ?>">
                            <option value="">--Select--</option>
                            <option value="N" <?php
                            if ($status['result_status'] == 'N') {
                                echo "selected";
                            }
                            ?>>Normal</option>
                            <option value="H" <?php
                            if ($status['result_status'] == 'H') {
                                echo "selected";
                            }
                            ?>>High</option>
                            <option value="L" <?php
                            if ($status['result_status'] == 'L') {
                                echo "selected";
                            }
                            ?>>Low</option>
                            <option value="A" <?php
                            if ($status['result_status'] == 'A') {
                                echo "selected";
                            }
                            ?>>Abnormal</option>
                        </select>
                    </td>
                    <td>
                        <select class="form-control" name="edit_par_critical_<?php echo $cn; ?>" id="edit_par_critical_<?php echo $cn; ?>">
                            <option value="">--Select--</option>
                            <option value="N" <?php
                            if ($status['critical_status'] == 'N') {
                                echo "selected";
                            }
                            ?>>Normal</option>
                            <option value="C" <?php
                            if ($status['critical_status'] == 'C') {
                                echo "selected";
                            }
                            ?>>Critical</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" name="edit_par_remarks_<?php echo $cn; ?>" id="edit_par_remarks_<?php echo $cn; ?>" value="<?php echo $status['remarks']; ?>" class="form-control">
                    </td>
                </tr>
                <?php
                $cn++;
            }
            ?>
        <input type="hidden" name="edit_count_ref" id="edit_count_ref" value="<?php echo $cn; ?>">
        <tr>
            <td>
                <a class="srch_view_a" href="javascript:void(0)" onclick="add_field1();"><i class="fa fa-plus-square"></i></a>
            </td>
            <td>
                <input type="text" name="par_code_1" id="par_code_1" class="form-control">
            </td>
            <td>
                <input type="text" name="par_name_1" id="par_name_1" class="form-control">
            </td>
            <td>
                <input type="hidden" name="count_ref" id="count_ref" value="1">
                <select class="form-control" name="par_result_1" id="par_result_1">
                    <option value="">--Select--</option>
                    <option value="N">Normal</option>
                    <option value="H">High</option>
                    <option value="L">Low</option>
                    <option value="A">Abnormal</option>
                </select>
            </td>
            <td>
                <select class="form-control" name="par_critical_1" id="par_critical_1">
                    <option value="">--Select--</option>
                    <option value="N">Normal</option>
                    <option value="C">Critical</option>
                </select>
            </td>
            <td>
                <input type="text" name="par_remarks_1" id="par_remarks_1" class="form-control">
            </td>
        </tr>
        </tbody>
    </table>
</div>