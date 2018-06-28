<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

insert into `test_parameter_master` (`center`,`test_fk`,`code`,`parameter_name`,`parameter_range`,`parameter_unit`,`formula`,`description`,`created_by`,`modified_by`,`created_date`,`modified_date`,`status`,`order`,`is_group`,`multiply_by`,`ref_test_fk` )

(select '11',test_parameter_copy2.`test_fk`,test_parameter_copy2.`code`,test_parameter_copy2.`parameter_name`,test_parameter_copy2.`parameter_range`,test_parameter_copy2.`parameter_unit`,test_parameter_copy2.`formula`,test_parameter_copy2.`description`,test_parameter_copy2.`created_by`,test_parameter_copy2.`modified_by`,test_parameter_copy2.`created_date`,test_parameter_copy2.`modified_date`,test_parameter_copy2.`status`,test_parameter_copy2.`order`,test_parameter_copy2.`is_group`,test_parameter_copy2.`multiply_by`,test_parameter_copy2.`id` from test_parameter_copy2)





insert into `test_parameter` (`test_fk`,`center`,`parameter_fk`,`status`,`created_by`,`created_date`,`order`,`ref_id` )

(select `test_fk`,'11',`parameter_fk`,`status`,`created_by`,`created_date`,`order` ,id from `test_parameter_copy21`)


insert  into  `parameter_referance_range` (center,`gender`,`no_period`,`type_period`,`normal_remarks`,`ref_range_low`,`low_remarks`,`ref_range_high`,`high_remarks`,`critical_low`,`critical_low_remarks`,`critical_high`,`critical_high_remarks`,`critical_low_sms`,`critical_high_sms`,`repeat_low`,`repeat_low_remarks`,`repeat_high`,`repeat_high_remarks`,`absurd_low`,`absurd_high`,`ref_range`,`parameter_fk`,`created_by`,`created_date`,`status`,`modified_by`,`modified_date`,`ref_id`)
(select '11',`gender`,`no_period`,`type_period`,`normal_remarks`,`ref_range_low`,`low_remarks`,`ref_range_high`,`high_remarks`,`critical_low`,`critical_low_remarks`,`critical_high`,`critical_high_remarks`,`critical_low_sms`,`critical_high_sms`,`repeat_low`,`repeat_low_remarks`,`repeat_high`,`repeat_high_remarks`,`absurd_low`,`absurd_high`,`ref_range`,`parameter_fk`,`created_by`,`created_date`,`status`,`modified_by`,`modified_date`,`id` from`parameter_referance_range_copy`)
 

 
 

 insert into `test_result_status` (center,`parameter_code`,`parameter_name`,`result_status`,`critical_status`,`remarks`,`parameter_fk`,`created_by`,`created_date`,`status`,`modified_by`,`modified_date`,ref_id)
(select '11',`parameter_code`,`parameter_name`,`result_status`,`critical_status`,`remarks`,`parameter_fk`,`created_by`,`created_date`,`status`,`modified_by`,`modified_date`,id  from `test_result_status_copy`)



UPDATE `test_parameter` SET `parameter_fk` =
 (SELECT test_parameter_master.`id` FROM `test_parameter_master` WHERE test_parameter_master.`ref_test_fk`=test_parameter.`parameter_fk` AND  test_parameter_master.`center`='11' ) WHERE test_parameter.`center`='11'
  
 
  
 
 
UPDATE `parameter_referance_range` SET parameter_referance_range.`parameter_fk`=
 (SELECT test_parameter_master.`id` FROM `test_parameter_master` WHERE test_parameter_master.`ref_test_fk`=parameter_referance_range.`parameter_fk` AND  test_parameter_master.`center`='11'  ) WHERE parameter_referance_range.`center`='11'
  
 
 UPDATE `test_result_status` SET test_result_status.`parameter_fk`=
 (SELECT test_parameter_master.`id` FROM `test_parameter_master` WHERE test_parameter_master.`ref_test_fk`=test_result_status.`parameter_fk` AND test_parameter_master.`center`='11' ) WHERE test_result_status.`center`='11'
