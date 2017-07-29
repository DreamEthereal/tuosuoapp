#
# 10.10 ÐÞ¶©
#

ALTER TABLE `eq_relation` ADD  `relationDefine` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '1' AFTER  `relationID` ;
ALTER TABLE `eq_survey` ADD  `uitheme` VARCHAR( 16 ) BINARY NOT NULL DEFAULT  'Phone' AFTER  `theme` ;
ALTER TABLE `eq_survey` CHANGE  `dbSize`  `dbSize` VARCHAR( 50 ) BINARY NOT NULL DEFAULT '180,0,180,100,200,120,0,120,255,255';
ALTER TABLE `eq_survey` ADD  `isRateIndex` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `isRelZero` ;
