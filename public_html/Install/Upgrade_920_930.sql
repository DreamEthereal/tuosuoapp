#
# 9.30 ÐÞ¶©
#

ALTER TABLE `eq_conditions` ADD  `logicMode` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '1';
ALTER TABLE `eq_associate` ADD  `logicMode` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '1';
ALTER TABLE `eq_question_checkbox` ADD  `groupNum` INT( 2 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `optionName` ;
ALTER TABLE `eq_survey` ADD  `isAllowIPMode` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '1' AFTER  `isAllowIP` ;
ALTER TABLE `eq_survey` ADD  `isAllData` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `isLogicAnd` ;
ALTER TABLE `eq_base_setting` ADD  `isMd5Pass` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `ajaxCheckURL` ;
ALTER TABLE `eq_issue_rule` ADD  `cookieVarName` VARCHAR( 50 ) BINARY NOT NULL DEFAULT  '' AFTER  `exposureVar` ;
ALTER TABLE `eq_base_setting` ADD  `isPostMethod` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `ajaxOverURL` ;
ALTER TABLE `eq_base_setting` ADD  `ajaxTokenURL` VARCHAR( 255 ) BINARY NOT NULL DEFAULT  '' AFTER  `loginURL` ;