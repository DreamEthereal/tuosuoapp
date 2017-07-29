
#
# 7.30 ÐÞ¶©
#

ALTER TABLE  `eq_question` ADD  `unitText` VARCHAR( 255 ) BINARY NOT NULL AFTER  `maxOption` ;
ALTER TABLE  `eq_question_checkbox` ADD  `unitText` VARCHAR( 255 ) BINARY NOT NULL AFTER  `maxOption` ;
ALTER TABLE  `eq_question_radio` ADD  `unitText` VARCHAR( 255 ) BINARY NOT NULL AFTER  `maxOption` ;
ALTER TABLE  `eq_question_yesno` ADD  `unitText` VARCHAR( 255 ) BINARY NOT NULL AFTER  `maxOption` ;
ALTER TABLE  `eq_survey` ADD  `apiURL` VARCHAR( 255 ) BINARY NOT NULL AFTER  `dbSize` ,ADD  `apiVarName` VARCHAR( 255 ) BINARY NOT NULL AFTER  `apiURL` ;

ALTER TABLE  `eq_question_yesno` ADD  `itemCode` INT( 5 ) UNSIGNED DEFAULT  '0' NOT NULL AFTER  `optionCoeff` ;
ALTER TABLE  `eq_question_radio` ADD  `itemCode` INT( 5 ) UNSIGNED DEFAULT  '0' NOT NULL AFTER  `optionCoeff` ;
ALTER TABLE  `eq_question_checkbox` ADD  `itemCode` INT( 5 ) UNSIGNED DEFAULT  '0' NOT NULL AFTER  `optionCoeff` ;
ALTER TABLE  `eq_question_range_answer` ADD  `itemCode` INT( 5 ) UNSIGNED DEFAULT  '0' NOT NULL AFTER  `optionCoeff` ;
ALTER TABLE  `eq_question` ADD  `otherCode` INT( 5 ) UNSIGNED DEFAULT  '0' NOT NULL AFTER  `optionCoeff` ,ADD  `negCode` INT( 5 ) UNSIGNED DEFAULT  '0' NOT NULL AFTER  `otherCode` ;

