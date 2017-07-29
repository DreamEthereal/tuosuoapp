#
# 4.0修订
#

ALTER TABLE  `eq_question` ADD  `minSize` INT( 2 ) UNSIGNED DEFAULT  '0' NOT NULL AFTER  `length` ;
UPDATE  `eq_question` SET length =25 WHERE questionType IN ( 2, 3 );

#
# 配额管理
#

ALTER TABLE  `eq_conditions` ADD  `quotaID` INT( 20 ) UNSIGNED DEFAULT  '0' NOT NULL ;
ALTER TABLE  `eq_conditions` ADD INDEX (  `quotaID` );

#
# 表的结构 `eq_quota`
#

CREATE TABLE IF NOT EXISTS eq_quota (
  quotaID int(20) unsigned NOT NULL auto_increment,
  quotaName varchar(255) binary NOT NULL default '',
  quotaNum int(20) unsigned NOT NULL default '0',
  surveyID int(30) unsigned NOT NULL default '0',
  PRIMARY KEY  (quotaID),
  KEY surveyID (surveyID)
) TYPE=MyISAM;

ALTER TABLE `eq_question_radio` ADD `isHaveText` INT( 1 ) UNSIGNED DEFAULT '1' NOT NULL AFTER `optionNameFile` ;
ALTER TABLE `eq_question_radio` ADD `optionSize` INT( 2 ) UNSIGNED DEFAULT '20' NOT NULL AFTER `isHaveText` ;
ALTER TABLE `eq_question_radio` ADD `isRequired` INT( 1 ) UNSIGNED DEFAULT '1' NOT NULL AFTER `optionSize` ;
ALTER TABLE `eq_question_radio` ADD `isCheckType` INT( 1 ) UNSIGNED DEFAULT '0' NOT NULL AFTER `isRequired` ;
ALTER TABLE `eq_question_radio` ADD `minOption` INT( 2 ) UNSIGNED DEFAULT '0' NOT NULL AFTER `isCheckType` ;
ALTER TABLE `eq_question_radio` ADD `maxOption` INT( 2 ) UNSIGNED DEFAULT '0' NOT NULL AFTER `minOption` ;

ALTER TABLE `eq_question_checkbox` ADD `isHaveText` INT( 1 ) UNSIGNED DEFAULT '1' NOT NULL AFTER `optionNameFile` ;
ALTER TABLE `eq_question_checkbox` ADD `optionSize` INT( 2 ) UNSIGNED DEFAULT '20' NOT NULL AFTER `isHaveText` ;
ALTER TABLE `eq_question_checkbox` ADD `isRequired` INT( 1 ) UNSIGNED DEFAULT '1' NOT NULL AFTER `optionSize` ;
ALTER TABLE `eq_question_checkbox` ADD `isCheckType` INT( 1 ) UNSIGNED DEFAULT '0' NOT NULL AFTER `isRequired` ;
ALTER TABLE `eq_question_checkbox` ADD `minOption` INT( 2 ) UNSIGNED DEFAULT '0' NOT NULL AFTER `isCheckType` ;
ALTER TABLE `eq_question_checkbox` ADD `maxOption` INT( 2 ) UNSIGNED DEFAULT '0' NOT NULL AFTER `minOption` ;
ALTER TABLE `eq_question_checkbox` ADD `isExclusive` INT( 1 ) UNSIGNED DEFAULT '0' NOT NULL AFTER `optionNameFile` ;

#
# 表的结构 `eq_question_range_label`
#

CREATE TABLE IF NOT EXISTS eq_question_range_label (
  question_range_labelID int(20) unsigned NOT NULL auto_increment,
  surveyID int(20) unsigned NOT NULL default '0',
  administratorsID int(30) unsigned NOT NULL default '0',
  questionID int(30) unsigned NOT NULL default '0',
  optionLabel text NOT NULL default '',
  optionOptionID int(10) unsigned NOT NULL default '0',
  optionSize int(2) unsigned NOT NULL default '16',
  isRequired int(1) unsigned NOT NULL default '0',
  isCheckType int(1) unsigned NOT NULL default '0',
  minOption int(2) unsigned NOT NULL default '0',
  maxOption int(2) unsigned NOT NULL default '0',
  orderByID int(20) unsigned NOT NULL default '0',
  PRIMARY KEY  (question_range_labelID),
  KEY surveyID (surveyID),
  KEY administratorsID (administratorsID),
  KEY questionID (questionID)
) TYPE=MyISAM;

DELETE FROM `eq_downs` WHERE `downsID` = '3' LIMIT 1 ;
DELETE FROM `eq_downs` WHERE `downsID` = '5' LIMIT 1 ;
UPDATE `eq_downs` SET `filename` = 'EnableQUserManual.html',`filesize` = '1' WHERE `downsID` = '1' LIMIT 1 ;

ALTER TABLE  `eq_base_setting` ADD  `license` VARCHAR( 100 ) BINARY NOT NULL ;
ALTER TABLE  `eq_base_setting` ADD  `licensetime` VARCHAR( 100 ) BINARY NOT NULL ;
