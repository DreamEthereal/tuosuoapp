
#
# 6.10 ÐÞ¶©
#

CREATE TABLE IF NOT EXISTS eq_android_list (
  listID int(30) unsigned NOT NULL auto_increment,
  surveyID int(20) unsigned NOT NULL default '0',
  PRIMARY KEY  (listID)
) TYPE=MyISAM;

CREATE TABLE IF NOT EXISTS eq_android_info (
  surveyID int(20) unsigned NOT NULL DEFAULT '0',
  responseID int(30) unsigned NOT NULL DEFAULT '0',
  deviceId varchar(100) NOT NULL DEFAULT '',
  brand varchar(50) NOT NULL DEFAULT '',
  model varchar(50) NOT NULL DEFAULT '',
  currentCity varchar(100) NOT NULL DEFAULT '',
  simOperatorName varchar(50) NOT NULL DEFAULT '',
  simSerialNumber varchar(100) NOT NULL DEFAULT '',
  gpsTime varchar(15) NOT NULL DEFAULT '',
  accuracy varchar(30) NOT NULL DEFAULT '',
  longitude varchar(30) NOT NULL DEFAULT '',
  latitude varchar(30) NOT NULL DEFAULT '',
  speed varchar(30) NOT NULL DEFAULT '',
  bearing varchar(30) NOT NULL DEFAULT '',
  altitude varchar(30) NOT NULL DEFAULT '',
  KEY surveyID (surveyID),
  KEY responseID (responseID)
) TYPE=MyISAM;

CREATE TABLE IF NOT EXISTS eq_android_push (
  pushID int(40) unsigned NOT NULL auto_increment,
  pushTitle varchar(255) binary NOT NULL default '',
  pushInfo text NOT NULL,
  pushURL varchar(255) binary NOT NULL default '',
  surveyID int(20) unsigned NOT NULL default '0',
  stat int(1) unsigned NOT NULL default '0',
  isCommon INT( 1 ) UNSIGNED DEFAULT  '1',
  administratorsID int(30) unsigned NOT NULL default '0',
  pushTime int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (pushID),
  KEY surveyID (surveyID),
  KEY administratorsID (administratorsID),
  KEY stat (stat)
) TYPE=MyISAM;

ALTER TABLE `eq_survey` ADD `isViewAuthData` INT( 1 ) UNSIGNED DEFAULT '0' NOT NULL AFTER `isOnline0View` ;
ALTER TABLE `eq_administrators` CHANGE `userGroupID` `userGroupID` VARCHAR( 255 ) DEFAULT '0' ;

