#
# 9.20 修订
#

ALTER TABLE `eq_survey` ADD `isPanelCache` INT( 1 ) UNSIGNED NOT NULL DEFAULT '1' AFTER  `isCache` ;

#
# 表的结构 `eq_cascade`
#

CREATE TABLE IF NOT EXISTS eq_cascade (
  `cascadeID` int(50) unsigned NOT NULL AUTO_INCREMENT,
  `surveyID` int(20) unsigned NOT NULL DEFAULT '0',
  `questionID` int(30) unsigned NOT NULL DEFAULT '0',
  `nodeID` int(50) unsigned NOT NULL DEFAULT '0',
  `nodeName` varchar(255) NOT NULL DEFAULT '',
  `nodeFatherID` int(50) unsigned NOT NULL DEFAULT '0',
  `level` int(2) unsigned NOT NULL DEFAULT '0',
  `flag` int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`cascadeID`),
  KEY `surveyID` (`surveyID`),
  KEY `questionID` (`questionID`),
  KEY `nodeID` (`nodeID`),
  KEY `nodeFatherID` (`nodeFatherID`),
  KEY `level` (`level`)
) ENGINE=MyISAM ;

ALTER TABLE `eq_survey` ADD `isModiData` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER `isDeleteData` ,ADD `isOneData` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER `isModiData` ,ADD `isGeolocation` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER `isOneData` ;

ALTER TABLE `eq_survey` ADD `AppId` VARCHAR( 100 ) BINARY NOT NULL DEFAULT  '' AFTER  `apiVarName` ,ADD  `AppSecret` VARCHAR( 100 ) BINARY NOT NULL DEFAULT  '' AFTER  `AppId` ,ADD  `isOnlyWeChat` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `AppSecret` ,ADD  `getChatUserInfo` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `isOnlyWeChat` ,ADD  `getChatUserMode` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '1' AFTER  `getChatUserInfo` ;

#
# 表的结构 `eq_track_code`
#

CREATE TABLE IF NOT EXISTS eq_track_code (
  `tagID` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `tagName` varchar(255) NOT NULL DEFAULT '',
  `surveyID` int(30) unsigned NOT NULL DEFAULT '0',
  `tagCate` int(1) unsigned NOT NULL DEFAULT '1',
  `exposure` varchar(30) NOT NULL DEFAULT '',
  `firstExposure` varchar(30) NOT NULL DEFAULT '',
  `lastExposure` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`tagID`),
  KEY `surveyID` (`surveyID`)
) ENGINE=MyISAM;

ALTER TABLE `eq_result_general_info` ADD  `exposureDomain` VARCHAR( 50 ) BINARY NOT NULL DEFAULT  '',ADD  `trackBeginTime` VARCHAR( 20 ) BINARY NOT NULL DEFAULT  '',ADD  `trackEndTime` VARCHAR( 20 ) BINARY NOT NULL DEFAULT  '',ADD  `exposureCampaign` VARCHAR( 30 ) BINARY NOT NULL DEFAULT  '',ADD  `firstExposureCampaign` VARCHAR( 30 ) BINARY NOT NULL DEFAULT  '',ADD  `lastExposureCampaign` VARCHAR( 30 ) BINARY NOT NULL DEFAULT  '',ADD  `exposureControl` VARCHAR( 30 ) BINARY NOT NULL DEFAULT  '',ADD  `firstExposureControl` VARCHAR( 30 ) BINARY NOT NULL DEFAULT  '',ADD  `lastExposureControl` VARCHAR( 30 ) BINARY NOT NULL DEFAULT  '',ADD  `exposureNormal` VARCHAR( 30 ) BINARY NOT NULL DEFAULT  '',ADD  `firstExposureNormal` VARCHAR( 30 ) BINARY NOT NULL DEFAULT  '',ADD  `lastExposureNormal` VARCHAR( 30 ) BINARY NOT NULL DEFAULT  '';

ALTER TABLE `eq_result_general_info` ADD  `isOpen` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '2',ADD  `issueMode` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '1',ADD  `issueRate` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '100',ADD  `issueCookie` VARCHAR( 100 ) BINARY NOT NULL DEFAULT  '';

ALTER TABLE `eq_result_general_info` ADD  `renderingCode` TEXT NOT NULL ;

ALTER TABLE `eq_question` CHANGE  `hiddenVarName`  `hiddenVarName` VARCHAR( 100 ) BINARY NOT NULL DEFAULT  '';

#
# 表的结构 `eq_issue_rule`
#

CREATE TABLE IF NOT EXISTS eq_issue_rule (
  `ruleID` int(30) unsigned NOT NULL AUTO_INCREMENT,
  `surveyID` int(20) unsigned NOT NULL DEFAULT '0',
  `exposureVar` int(20) unsigned NOT NULL DEFAULT '0',
  `ruleValue` int(20) unsigned NOT NULL DEFAULT '0',
  `ruleOrderID` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ruleID`),
  KEY `surveyID` (`surveyID`),
  KEY `exposureVar` (`exposureVar`)
) ENGINE=MyISAM;

