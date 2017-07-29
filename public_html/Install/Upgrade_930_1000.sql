#
# 10.0 ÐÞ¶©
#

ALTER TABLE `eq_question` ADD  `negValue` FLOAT( 6, 2 ) NOT NULL DEFAULT  '0.00' AFTER  `optionCoeff` ,
ADD  `optionValue` FLOAT( 6, 2 ) NOT NULL DEFAULT  '0.00' AFTER  `negValue` ;
ALTER TABLE `eq_question_yesno` ADD  `optionValue` FLOAT( 6, 2 ) NOT NULL DEFAULT  '0.00' AFTER  `optionCoeff` ;
ALTER TABLE `eq_question_radio` ADD  `optionValue` FLOAT( 6, 2 ) NOT NULL DEFAULT  '0.00' AFTER  `optionCoeff` ;
ALTER TABLE `eq_question_checkbox` ADD  `optionValue` FLOAT( 6, 2 ) NOT NULL DEFAULT  '0.00' AFTER  `optionCoeff` ;
ALTER TABLE `eq_question_range_answer` ADD  `optionValue` FLOAT( 6, 2 ) NOT NULL DEFAULT  '0.00' AFTER  `optionCoeff` ;

ALTER TABLE `eq_relation` ADD  `qtnID` INT( 20 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `questionID` ,ADD INDEX (  `qtnID` ) ;
ALTER TABLE `eq_relation_list` ADD  `qtnID` INT( 20 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `questionID` ,ADD INDEX (  `qtnID` ) ;
ALTER TABLE `eq_survey` ADD  `isRelZero` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `isCheckStat0` ;
ALTER TABLE `eq_survey` ADD  `isOfflineDele` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `isOfflineModi` ;
ALTER TABLE `eq_base_setting` ADD  `isRealGps` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `baseSettingID` ;
ALTER TABLE `eq_question_radio` ADD  `isRetain` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `isLogicAnd` ;
ALTER TABLE `eq_question_checkbox` ADD  `isRetain` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `isLogicAnd` ;
ALTER TABLE `eq_survey` ADD  `msgImage` VARCHAR(255) NOT NULL DEFAULT  '' AFTER  `custLogo` ;
ALTER TABLE `eq_query_cond` ADD  `opertion` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '1' AFTER  `labelID` ;

