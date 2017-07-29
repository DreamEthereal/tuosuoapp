#
# 1.31ÐÞ¶©
#

ALTER TABLE `eq_question` ADD `maxSize` INT( 2 ) UNSIGNED DEFAULT '2' NOT NULL AFTER `length` ,
ADD `allowType` VARCHAR( 255 ) BINARY DEFAULT '' NOT NULL AFTER `maxSize` ;
ALTER TABLE `eq_survey` CHANGE `theme` `theme` VARCHAR( 16 ) BINARY DEFAULT 'Standard' NOT NULL ;
ALTER TABLE `eq_archiving` CHANGE `archivingFile` `archivingFile` VARCHAR( 100 ) BINARY DEFAULT '' NOT NULL ;
ALTER TABLE `eq_survey` ADD `isLogicAnd` INT( 1 ) UNSIGNED DEFAULT '0' NOT NULL AFTER `isViewResult` ;

ALTER TABLE `eq_question_checkbox` ADD `optionCoeff` FLOAT( 4, 2 ) DEFAULT '0.00' NOT NULL AFTER `optionName` ;
ALTER TABLE `eq_question_yesno` ADD `optionCoeff` FLOAT( 4, 2 ) DEFAULT '0.00' NOT NULL AFTER `optionName` ;
ALTER TABLE `eq_question_radio` ADD `optionCoeff` FLOAT( 4, 2 ) DEFAULT '0.00' NOT NULL AFTER `optionName` ;
ALTER TABLE `eq_question_range_answer` ADD `optionCoeff` FLOAT( 4, 2 ) DEFAULT '0.00' NOT NULL AFTER `optionAnswer` ;
ALTER TABLE `eq_question` ADD `optionCoeff` FLOAT( 4, 2 ) DEFAULT '0.00' NOT NULL AFTER `otherText` ;