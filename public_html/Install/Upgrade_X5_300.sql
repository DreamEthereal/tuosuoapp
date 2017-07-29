#
# 3.0修订
#

ALTER TABLE `eq_ipdatabase` CHANGE `Area` `Area` VARCHAR( 100 ) BINARY NOT NULL DEFAULT '' ;
ALTER TABLE `eq_grade` CHANGE `startGrade` `startGrade` FLOAT( 8, 2 ) DEFAULT '0.00' NOT NULL;
ALTER TABLE `eq_grade` CHANGE `endGrade` `endGrade` FLOAT( 8, 2 ) DEFAULT '0.00' NOT NULL;
ALTER TABLE `eq_response_group_list` ADD `administratorsoptionID` INT( 20 ) UNSIGNED DEFAULT '0' NOT NULL AFTER `administratorsGroupID` ;
ALTER TABLE `eq_response_group_list` ADD INDEX ( `administratorsoptionID` ) ;
ALTER TABLE `eq_response_group_list` ADD `value` VARCHAR( 255 ) BINARY NOT NULL DEFAULT '' AFTER `administratorsoptionID` ;
UPDATE  `eq_downs` SET  `filesize` =  '2480.00',`createDate` =  '1250052832',`updateDate` =  '1250052832' WHERE  `downsID` =  '1' LIMIT 1 ;
UPDATE  `eq_downs` SET  `filesize` =  '233.00',`createDate` =  '1250052832',`updateDate` =  '1250052832' WHERE  `downsID` =  '2' LIMIT 1 ;
UPDATE  `eq_downs` SET  `filesize` =  '38.10',`createDate` =  '1250052832',`updateDate` =  '1250052832' WHERE  `downsID` =  '3' LIMIT 1 ;
INSERT INTO `eq_downs` ( `downsID` , `downsName` , `downsContent` , `filename` , `filesize` , `isPublic` , `createDate` , `updateDate` , `orderByID` ) 
VALUES ( '', '为什么选择EnableQ', 'EnableQ在线问卷调查引擎参考手册：为什么选择EnableQ?', 'WhyIsEnableQ.zip', '30.2', 'y', '1250052832', '1250052832', '5');

#
# 表的结构 `eq_text_option`
#

CREATE TABLE IF NOT EXISTS eq_text_option (
  optionID int(50) unsigned NOT NULL auto_increment,
  questionID int(30) unsigned NOT NULL default '0',
  optionText varchar(255) binary NOT NULL default '',
  PRIMARY KEY  (optionID),
  KEY questionID (questionID)
) TYPE=MyISAM;

#
# 表的结构 `eq_plan`
#

CREATE TABLE IF NOT EXISTS eq_plan (
  planID int(50) unsigned NOT NULL auto_increment,
  surveyID int(20) unsigned NOT NULL default '0',
  planDay date NOT NULL default '0000-00-00',
  planNum int(6) unsigned NOT NULL default '0',
  planDesc text NOT NULL default '',
  PRIMARY KEY  (planID),
  KEY surveyID (surveyID)
) TYPE=MyISAM;

#
# 表的结构 `eq_survey_index`
#

CREATE TABLE IF NOT EXISTS eq_survey_index (
  indexID int(30) unsigned NOT NULL auto_increment,
  surveyID int(20) unsigned NOT NULL default '0',
  indexName varchar(200) binary NOT NULL default '',
  indexDesc text NOT NULL default '',
  PRIMARY KEY  (indexID),
  KEY surveyID (surveyID)
) TYPE=MyISAM;

#
# 表的结构 `eq_survey_index list`
#

CREATE TABLE IF NOT EXISTS eq_survey_index_list (
  listID int(50) unsigned NOT NULL auto_increment,
  surveyID int(20) unsigned NOT NULL default '0',
  indexID int(30) unsigned NOT NULL default '0',
  questionID int(30) unsigned NOT NULL default '0',
  PRIMARY KEY  (listID),
  KEY surveyID (surveyID),
  KEY indexID (indexID),
  KEY questionID (questionID)
) TYPE=MyISAM;
    