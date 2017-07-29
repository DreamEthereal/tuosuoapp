#
# 1.40修订
#

ALTER TABLE `eq_question` ADD `isNeg` INT( 1 ) UNSIGNED DEFAULT '0' NOT NULL AFTER `otherText` ;

ALTER TABLE `eq_panel` ADD `administratorsID` INT( 30 ) UNSIGNED DEFAULT '0' NOT NULL AFTER `tplName` ;
ALTER TABLE `eq_panel` ADD INDEX ( `administratorsID` ) ;
ALTER TABLE `eq_administrators` ADD `byUserID` INT( 30 ) UNSIGNED DEFAULT '0' NOT NULL AFTER `isActive` ;
ALTER TABLE `eq_administrators` ADD INDEX ( `byUserID` ) ;

#
# 表的结构 `eq_award_list`
#

CREATE TABLE IF NOT EXISTS eq_award_list (
  awardID int(20) unsigned NOT NULL auto_increment,
  surveyID int(20) unsigned NOT NULL default '0',
  awardListID int(20) unsigned NOT NULL default '0',
  responseID int(30) unsigned NOT NULL default '0',
  ipAddress varchar(15) binary NOT NULL default '',
  administratorsName varchar(100) binary NOT NULL default '',
  PRIMARY KEY  (awardID),
  KEY surveyID (surveyID),
  KEY awardListID (awardListID),
  KEY responseID (responseID),
  KEY ipAddress (ipAddress),
  KEY administratorsName (administratorsName)
) TYPE=MyISAM;

#
# 表的结构 `eq_award_product`
#

CREATE TABLE IF NOT EXISTS eq_award_product (
  awardListID int(20) unsigned NOT NULL auto_increment,
  surveyID int(20) unsigned NOT NULL default '0',
  awardType varchar(20) binary NOT NULL default '',
  awardProduct varchar(200) NOT NULL default '',
  awardNum int(6) NOT NULL default '0',
  PRIMARY KEY  (awardListID),
  KEY surveyID (surveyID)
) TYPE=MyISAM;

ALTER TABLE `eq_basesetting` ADD `ajaxURL` VARCHAR( 255 ) NOT NULL default '' AFTER `loginURL`;
ALTER TABLE `eq_basesetting` ADD `domainControllers` VARCHAR( 255 ) NOT NULL default '' AFTER `ajaxURL`;
ALTER TABLE `eq_basesetting` ADD `adUsername` VARCHAR( 60 ) BINARY NOT NULL default '' AFTER `domainControllers` ;
ALTER TABLE `eq_basesetting` ADD `accountSuffix` VARCHAR( 100 ) BINARY NOT NULL default '' AFTER `adUsername` ;
ALTER TABLE `eq_basesetting` ADD `adPassword` VARCHAR( 20 ) BINARY NOT NULL default '' AFTER `accountSuffix` ;
ALTER TABLE `eq_basesetting` ADD `baseDN` VARCHAR( 255 ) NOT NULL default '' AFTER `adPassword` ;

ALTER TABLE `eq_response_group_list` ADD `adUserGroupName` VARCHAR( 160 ) BINARY NOT NULL default '' AFTER `administratorsGroupID` ;
ALTER TABLE `eq_response_group_list` ADD INDEX ( `adUserGroupName` ) ;

ALTER TABLE `eq_question` ADD `DSNConnect` VARCHAR( 100 ) BINARY NOT NULL default '' AFTER `allowType`;
ALTER TABLE `eq_question` ADD `DSNSQL` TEXT NOT NULL default '' AFTER `DSNConnect` ;
ALTER TABLE `eq_question` ADD `DSNUser` VARCHAR( 40 ) BINARY NOT NULL default '' AFTER `DSNSQL` ;
ALTER TABLE `eq_question` ADD `DSNPassword` VARCHAR( 20 ) BINARY NOT NULL default '' AFTER `DSNUser` ;
ALTER TABLE `eq_question` ADD `hiddenVarName` VARCHAR( 20 ) BINARY NOT NULL default '' AFTER `DSNPassword` ;
ALTER TABLE `eq_question` ADD `hiddenFromSession` INT( 1 ) UNSIGNED DEFAULT '0' NOT NULL AFTER `hiddenVarName` ;
ALTER TABLE `eq_basesetting` RENAME `eq_base_setting` ;
ALTER TABLE `eq_base_setting` CHANGE `ajaxURL` `ajaxResponseURL` VARCHAR( 255 ) NOT NULL default '';
ALTER TABLE `eq_base_setting` ADD `ajaxCheckURL` VARCHAR( 255 ) NOT NULL default '' AFTER `ajaxResponseURL` ;
ALTER TABLE `eq_base_setting` ADD `ajaxRtnValue` VARCHAR( 255 ) NOT NULL default '' AFTER `ajaxCheckURL` ;
ALTER TABLE `eq_base_setting` ADD `isAllowIP` INT( 1 ) UNSIGNED DEFAULT '0' NOT NULL ;

#
# 表的结构 `eq_ip_banned`
#

CREATE TABLE IF NOT EXISTS eq_ip_banned (
  bannedID int(30) unsigned NOT NULL auto_increment,
  ipAddress varchar(15) binary NOT NULL default '',
  PRIMARY KEY  (bannedID)
) TYPE=MyISAM;

#
# 表的结构 `eq_ip_allow`
#

CREATE TABLE IF NOT EXISTS eq_ip_allow (
  allowIpID int(6) unsigned NOT NULL auto_increment,
  startIP varchar(15) NOT NULL default '',
  endIP varchar(15) NOT NULL default '',
  PRIMARY KEY  (allowIpID)
) TYPE=MyISAM;

INSERT INTO `eq_downs` ( `downsID` , `downsName` , `downsContent` , `filename` , `filesize` , `isPublic` , `createDate` , `updateDate` , `orderByID` ) VALUES ('2', 'EnableQ开发人员手册','EnableQ在线问卷调查引擎参考手册：数据接口规范', 'EnableQDeveloperManual.zip', '209', 'y','1206449606', '1206449606', '2');
INSERT INTO `eq_downs` ( `downsID` , `downsName` , `downsContent` , `filename` , `filesize` , `isPublic` , `createDate` , `updateDate` , `orderByID` ) VALUES ('3', 'EnableQ DataSheet','EnableQ在线问卷调查引擎参考手册：DataSheet', 'EnableQDataSheet.zip', '330', 'y', '1206449606', '1206449606', '3');