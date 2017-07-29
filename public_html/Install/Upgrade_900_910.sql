#
# 9.10 ÐÞ¶©
#

ALTER TABLE `eq_survey` ADD  `isDataSource` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `isCache` ;
ALTER TABLE `eq_survey` ADD  `forbidAppId` TEXT NOT NULL AFTER  `forbidViewId` ;
ALTER TABLE `eq_question` ADD  `isUnkown` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `otherCode` ;
ALTER TABLE `eq_question_yesno` ADD  `isUnkown` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `itemCode` ;
ALTER TABLE `eq_question_radio` ADD  `isUnkown` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `itemCode` ;
ALTER TABLE `eq_question_range_answer` ADD  `isUnkown` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `itemCode` ;

### 2014-07-22
ALTER TABLE `eq_question` ADD  `isNA` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `isUnkown` ;
ALTER TABLE `eq_question_yesno` ADD  `isNA` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `isUnkown` ;
ALTER TABLE `eq_question_radio` ADD  `isNA` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `isUnkown` ;
ALTER TABLE `eq_question_range_answer` ADD  `isNA` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `isUnkown` ;
ALTER TABLE `eq_question_checkbox` ADD  `isNA` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `isExclusive` ;