
#
# 7.00 ÐÞ¶©
#

ALTER TABLE `eq_administrators` CHANGE `administratorsName` `administratorsName` varchar(100) binary NOT NULL default '';
ALTER TABLE `eq_administrators` CHANGE `nickName` `nickName` varchar(100) binary NOT NULL default '';
ALTER TABLE  `eq_survey` CHANGE `dbSize` `dbSize` varchar(50) binary NOT NULL default '180,140,180,100,200,120,120,120,255,255';
ALTER TABLE  `eq_survey` ADD  `isRecord` INT(1) UNSIGNED NOT NULL DEFAULT  '1' AFTER  `isSecureImage` ;
ALTER TABLE  `eq_survey` ADD  `isPanelFlag` INT(1) UNSIGNED NOT NULL DEFAULT  '1' AFTER  `isRecord` ;
ALTER TABLE  `eq_survey` ADD  `isUploadRec` INT( 1 ) UNSIGNED DEFAULT  '1' NOT NULL AFTER  `isRecord` ;
ALTER TABLE  `eq_quota` ADD  `quotaText` text NOT NULL AFTER  `surveyID` ;

CREATE TABLE IF NOT EXISTS eq_android_log (
  logId int(50) unsigned NOT NULL AUTO_INCREMENT,
  surveyID int(30) unsigned NOT NULL DEFAULT '0',
  userId int(50) unsigned NOT NULL DEFAULT '0',
  nickName varchar(100) NOT NULL default '',
  actionMess text NOT NULL,
  actionTime int(11) unsigned NOT NULL DEFAULT '0',
  actionType INT(1) UNSIGNED DEFAULT  '0' NOT NULL,
  PRIMARY KEY (logId)
) TYPE=MyISAM;