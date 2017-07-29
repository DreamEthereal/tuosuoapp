
#
# 6.00 修订
#

ALTER TABLE  `eq_survey` ADD  `isGroup0View` INT( 1 ) UNSIGNED DEFAULT  '0' NOT NULL AFTER  `isAllowEdit` ,
ADD  `isGroup0Edit` INT( 1 ) UNSIGNED DEFAULT  '0' NOT NULL AFTER  `isGroup0View` ;
ALTER TABLE  `eq_question_yesno` ADD  `isNeg` INT( 1 ) UNSIGNED DEFAULT  '0' NOT NULL AFTER  `isRequired` ;
ALTER TABLE  `eq_survey` ADD  `isShowResultBut` INT( 1 ) UNSIGNED DEFAULT  '0' NOT NULL AFTER  `isProcessBar` ;
ALTER TABLE  `eq_administrators` CHANGE  `administratorsName`  `administratorsName` VARCHAR( 100 ) BINARY NOT NULL;
ALTER TABLE  `eq_administrators` CHANGE  `nickName`  `nickName` VARCHAR( 40 ) BINARY NOT NULL;
ALTER TABLE  `eq_administratorsconfig` CHANGE  `mainAttribute`  `mainAttribute` VARCHAR( 100 ) BINARY NOT NULL;
ALTER TABLE  `eq_response_group_list` CHANGE  `value`  `value` VARCHAR( 255 ) BINARY NOT NULL;
ALTER TABLE  `eq_response_group_list` CHANGE  `adUserName`  `adUserName` VARCHAR( 255 ) BINARY NOT NULL;
ALTER TABLE  `eq_survey` CHANGE  `surveyName`  `surveyName` VARCHAR( 64 ) BINARY NOT NULL;
ALTER TABLE  `eq_panel` ADD  `isSystem` INT( 1 ) UNSIGNED DEFAULT  '0' NOT NULL ;
INSERT INTO  `eq_panel` (  `panelID` ,  `tplTagName` ,  `tplName` ,  `administratorsID` ,  `isDefault` ,  `isSystem` ) VALUES ('30001',  '问卷展现系统模板(蓝色)',  'DefaultPanel1.html',  '0',  '0',  '1');
INSERT INTO  `eq_panel` (  `panelID` ,  `tplTagName` ,  `tplName` ,  `administratorsID` ,  `isDefault` ,  `isSystem` ) VALUES ('30002',  '问卷展现系统模板(浅蓝色)',  'DefaultPanel2.html',  '0',  '0',  '1');
INSERT INTO  `eq_panel` (  `panelID` ,  `tplTagName` ,  `tplName` ,  `administratorsID` ,  `isDefault` ,  `isSystem` ) VALUES ('30003',  '问卷展现系统模板(深蓝色)',  'DefaultPanel3.html',  '0',  '0',  '1');
INSERT INTO  `eq_panel` (  `panelID` ,  `tplTagName` ,  `tplName` ,  `administratorsID` ,  `isDefault` ,  `isSystem` ) VALUES ('30004',  '问卷展现系统模板(红色)',  'DefaultPanel4.html',  '0',  '0',  '1');
INSERT INTO  `eq_panel` (  `panelID` ,  `tplTagName` ,  `tplName` ,  `administratorsID` ,  `isDefault` ,  `isSystem` ) VALUES ('30005',  '问卷展现系统模板(多色)',  'DefaultPanel5.html',  '0',  '0',  '1');
INSERT INTO  `eq_panel` (  `panelID` ,  `tplTagName` ,  `tplName` ,  `administratorsID` ,  `isDefault` ,  `isSystem` ) VALUES ('30006',  '问卷展现系统模板(绿色)',  'DefaultPanel6.html',  '0',  '0',  '1');

CREATE TABLE IF NOT EXISTS eq_query_list (
  queryID int(30) unsigned NOT NULL auto_increment,
  administratorsID int(30) unsigned NOT NULL default '0',
  surveyID int(20) unsigned NOT NULL default '0',
  queryName varchar(255) binary NOT NULL default '',
  PRIMARY KEY  (queryID),
  KEY surveyID (surveyID),
  KEY administratorsID (administratorsID)
) TYPE=MyISAM;

CREATE TABLE IF NOT EXISTS eq_query_cond (
  querycondID int(50) unsigned NOT NULL auto_increment,
  administratorsID int(30) unsigned NOT NULL default '0',
  surveyID int(20) unsigned NOT NULL default '0',
  queryID int(30) unsigned NOT NULL default '0',
  fieldsID varchar(30) binary NOT NULL default '0',
  optionID int(30) unsigned NOT NULL default '0',
  labelID int(30) unsigned NOT NULL default '0',
  queryValue varchar(255) binary NOT NULL default '0',
  logicOR int(1) unsigned NOT NULL default '0',
  isRadio int(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (querycondID),
  KEY administratorsID (administratorsID),
  KEY surveyID (surveyID),
  KEY queryID (queryID),
  KEY fieldsID (fieldsID),
  KEY logicOR (logicOR),
  KEY optionID (optionID),
  KEY labelID (labelID)
) TYPE=MyISAM;

ALTER TABLE  `eq_conditions` ADD  `opertion` INT( 1 ) UNSIGNED DEFAULT  '1' NOT NULL AFTER  `condOnID` ;
ALTER TABLE  `eq_question_range_option` ADD  `optionOptionID` INT( 10 ) UNSIGNED DEFAULT  '0' NOT NULL ,
ADD  `isRequired` INT( 1 ) UNSIGNED DEFAULT  '0' NOT NULL ,
ADD  `minOption` INT( 2 ) UNSIGNED DEFAULT  '0' NOT NULL ,
ADD  `maxOption` INT( 2 ) UNSIGNED DEFAULT  '0' NOT NULL ;
ALTER TABLE  `eq_survey` ADD  `isOnline0View` INT( 1 ) UNSIGNED DEFAULT  '1' NOT NULL AFTER  `isGroup0Edit` ;
ALTER TABLE  `eq_survey` ADD  `dbSize` VARCHAR( 50 ) BINARY DEFAULT  '180,140,180,100,200,120,110,120,255,30' NOT NULL AFTER  `isCache` ;

CREATE TABLE IF NOT EXISTS eq_relation (
  relationID int(20) unsigned NOT NULL auto_increment,
  relationMode int(1) unsigned NOT NULL default '0',
  relationNum float(12,2) unsigned NOT NULL default '0.00',
  questionID int(30) unsigned NOT NULL default '0',
  optionID int(20) unsigned NOT NULL default '0',
  labelID int(20) unsigned NOT NULL default '0',
  opertion int(1) unsigned NOT NULL default '1',
  surveyID int(20) unsigned NOT NULL default '0',
  PRIMARY KEY  (relationID),
  KEY relationMode (relationMode),
  KEY questionID (questionID),
  KEY optionID (optionID),
  KEY labelID (labelID),
  KEY surveyID (surveyID),
  KEY opertion (opertion)
) TYPE=MyISAM;
    
CREATE TABLE IF NOT EXISTS eq_relation_list (
  listID int(30) unsigned NOT NULL auto_increment,
  relationID int(20) unsigned NOT NULL default '0',
  questionID int(30) unsigned NOT NULL default '0',
  optionID int(20) unsigned NOT NULL default '0',
  labelID int(20) unsigned NOT NULL default '0',
  opertion int(1) unsigned NOT NULL default '1',
  optionOptionID int(6) unsigned NOT NULL default '0',
  surveyID int(20) unsigned NOT NULL default '0',
  PRIMARY KEY  (listID),
  KEY relationID (relationID),
  KEY questionID (questionID),
  KEY optionID (optionID),
  KEY labelID (labelID),
  KEY opertion (opertion),
  KEY surveyID (surveyID)
) TYPE=MyISAM;

ALTER TABLE  `eq_administrators` CHANGE  `userGroupID`  `userGroupID` VARCHAR( 200 ) BINARY DEFAULT  '0' NOT NULL;

