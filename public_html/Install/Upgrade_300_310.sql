#
# 3.1ÐÞ¶©
#

ALTER TABLE `eq_question` ADD `questionNotes` TEXT DEFAULT '' NOT NULL AFTER `questionName` ;
ALTER TABLE `eq_question_yesno` ADD `optionSize` INT( 2 ) UNSIGNED DEFAULT '20' NOT NULL AFTER `optionCoeff` ;
ALTER TABLE `eq_question_yesno` ADD `isRequired` INT( 1 ) UNSIGNED DEFAULT '0' NOT NULL AFTER `optionSize` ;
ALTER TABLE `eq_question_yesno` ADD `isCheckType` INT( 1 ) UNSIGNED DEFAULT '0' NOT NULL AFTER `isRequired` ;
ALTER TABLE `eq_question_yesno` ADD `minOption` INT( 2 ) UNSIGNED DEFAULT '0' NOT NULL AFTER `isCheckType` ;
ALTER TABLE `eq_question_yesno` ADD `maxOption` INT( 2 ) UNSIGNED DEFAULT '0' NOT NULL AFTER `minOption` ;
ALTER TABLE `eq_question_yesno` ADD `optionOptionID` INT( 10 ) UNSIGNED DEFAULT '0' NOT NULL AFTER `optionCoeff` ;