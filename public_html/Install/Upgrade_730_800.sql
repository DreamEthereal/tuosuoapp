
#
# 8.0 修订
#

#
# 表的结构 `eq_associate`
#

CREATE TABLE IF NOT EXISTS eq_associate (
  associateID int(10) unsigned NOT NULL AUTO_INCREMENT,
  surveyID int(20) unsigned NOT NULL DEFAULT '0',
  administratorsID int(30) unsigned NOT NULL DEFAULT '0',
  questionID int(30) unsigned NOT NULL DEFAULT '0',
  qtnID int(20) unsigned NOT NULL DEFAULT '0',
  optionID int(20) unsigned NOT NULL DEFAULT '0',
  condOnID int(30) unsigned NOT NULL DEFAULT '0',
  condQtnID int(20) unsigned NOT NULL DEFAULT '0',
  condOptionID int(20) unsigned NOT NULL DEFAULT '0',
  opertion int(1) unsigned NOT NULL DEFAULT '1',
  assType int(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (associateID),
  KEY surveyID (surveyID),
  KEY questionID (questionID),
  KEY qtnID (qtnID),
  KEY optionID (optionID),
  KEY condOnID (condOnID),
  KEY condQtnID (condQtnID),
  KEY condOptionID (condOptionID),
  KEY opertion (opertion)
) ENGINE=MyISAM;

ALTER TABLE `eq_question` CHANGE  `isLogicAnd`  `isLogicAnd` INT( 1 ) UNSIGNED DEFAULT  '1';

ALTER TABLE `eq_question_range_option` ADD  `isLogicAnd` INT( 1 ) UNSIGNED DEFAULT  '1' NOT NULL ;
ALTER TABLE `eq_question_rank` ADD  `isLogicAnd` INT( 1 ) UNSIGNED DEFAULT  '1' NOT NULL ;
ALTER TABLE `eq_question_radio` ADD  `isLogicAnd` INT( 1 ) UNSIGNED DEFAULT  '1' NOT NULL AFTER  `unitText` ;
ALTER TABLE `eq_question_checkbox` ADD  `isLogicAnd` INT( 1 ) UNSIGNED DEFAULT  '1' NOT NULL AFTER  `unitText` ;
ALTER TABLE `eq_question_range_answer` ADD  `isLogicAnd` INT( 1 ) UNSIGNED DEFAULT  '1' NOT NULL ;

#填空#列表
ALTER TABLE `eq_question` CHANGE `minOption` `minOption` INT( 2 ) DEFAULT '0' NOT NULL;
ALTER TABLE `eq_question` CHANGE `maxOption` `maxOption` INT( 2 ) DEFAULT '0' NOT NULL;
#单多选
ALTER TABLE `eq_question` CHANGE `minSize`  `minSize` INT( 2 ) DEFAULT  '0' NOT NULL;
ALTER TABLE `eq_question` CHANGE `maxSize`  `maxSize` INT( 2 ) DEFAULT  '2' NOT NULL;
#矩阵填空
ALTER TABLE `eq_question_range_label` CHANGE  `minOption`  `minOption` INT( 2 ) DEFAULT  '0' NOT NULL;
ALTER TABLE `eq_question_range_label` CHANGE  `maxOption`  `maxOption` INT( 2 ) DEFAULT  '0' NOT NULL;
#组合填空
ALTER TABLE `eq_question_yesno` CHANGE  `minOption`  `minOption` INT( 2 ) DEFAULT  '0' NOT NULL;
ALTER TABLE `eq_question_yesno` CHANGE  `maxOption`  `maxOption` INT( 2 ) DEFAULT  '0' NOT NULL;
#复合单选
ALTER TABLE `eq_question_radio` CHANGE  `minOption`  `minOption` INT( 2 ) DEFAULT  '0' NOT NULL;
ALTER TABLE `eq_question_radio` CHANGE  `maxOption`  `maxOption` INT( 2 ) DEFAULT  '0' NOT NULL;
#复合多选
ALTER TABLE `eq_question_checkbox` CHANGE  `minOption`  `minOption` INT( 2 ) DEFAULT  '0' NOT NULL;
ALTER TABLE `eq_question_checkbox` CHANGE  `maxOption`  `maxOption` INT( 2 ) DEFAULT  '0' NOT NULL;

#用户体系
ALTER TABLE `eq_user_group` ADD  `fatherId` INT( 20 ) UNSIGNED DEFAULT  '0' NOT NULL ,
ADD  `groupType` INT( 1 ) UNSIGNED DEFAULT  '1' NOT NULL ;
ALTER TABLE `eq_user_group` CHANGE  `userGroupID`  `userGroupID` INT( 20 ) unsigned NOT NULL auto_increment;
ALTER TABLE `eq_user_group` CHANGE  `userGroupName`  `userGroupName` VARCHAR( 255 ) binary NOT NULL DEFAULT '';
ALTER TABLE `eq_user_group` ADD  `userGroupDesc` VARCHAR( 255 ) binary NOT NULL DEFAULT '' AFTER  `groupType` ;
ALTER TABLE `eq_user_group` ADD  `absPath` VARCHAR( 255 ) BINARY DEFAULT  '0' NOT NULL AFTER  `groupType` ,
ADD  `isLeaf` INT( 1 ) UNSIGNED DEFAULT  '1' NOT NULL AFTER  `absPath` ;
ALTER TABLE `eq_user_group` ADD  `userGroupLabel` VARCHAR( 100 ) BINARY NOT NULL DEFAULT '' AFTER  `userGroupDesc` ;

ALTER TABLE `eq_administrators` CHANGE  `userGroupID`  `userGroupID` INT( 20 ) UNSIGNED NOT NULL DEFAULT  '0';
ALTER TABLE `eq_administrators` ADD  `groupType` INT( 1 ) UNSIGNED DEFAULT  '1' NOT NULL AFTER  `isAdmin` ;

ALTER TABLE `eq_survey` ADD  `isOnline0Auth` INT( 1 ) UNSIGNED DEFAULT  '0' NOT NULL AFTER  `isOnline0View` ;
ALTER TABLE `eq_survey` ADD  `projectType` INT( 1 ) UNSIGNED DEFAULT  '2' NOT NULL AFTER  `isOnline0Auth` ,
ADD  `projectOwner` INT( 1 ) UNSIGNED DEFAULT  '0' NOT NULL AFTER  `projectType` ;
ALTER TABLE `eq_survey` ADD  `isViewAuthInfo` INT( 1 ) UNSIGNED DEFAULT  '0' NOT NULL AFTER  `isViewAuthData` ;
ALTER TABLE `eq_survey` DROP  `isAllowView` ,DROP  `isAllowEdit` ,DROP  `isGroup0View` ,DROP  `isGroup0Edit` ;
ALTER TABLE `eq_survey` ADD  `custLogo` VARCHAR( 100 ) BINARY DEFAULT '' NOT NULL AFTER  `projectOwner` ;
ALTER TABLE `eq_survey` ADD  `custTel` VARCHAR( 24 ) BINARY DEFAULT '' NOT NULL AFTER  `custLogo` ;

ALTER TABLE `eq_view_user_list` ADD  `isAuth` INT( 1 ) UNSIGNED DEFAULT  '0' NOT NULL ;

#
# 表的结构 `eq_appeal_user_list`
#

CREATE TABLE IF NOT EXISTS eq_appeal_user_list (
  appealUserListID int(20) unsigned NOT NULL DEFAULT '0',
  administratorsID int(30) unsigned NOT NULL DEFAULT '0',
  surveyID int(20) unsigned NOT NULL DEFAULT '0',
  isAuth int(1) unsigned NOT NULL DEFAULT '0',
  KEY administratorsID (administratorsID),
  KEY surveyID (surveyID)
) ENGINE=MyISAM;

#任务体系
ALTER TABLE `eq_task_list` DROP  `taskName` ,DROP  `taskDesc` ,DROP  `taskTime` ;
ALTER TABLE `eq_task_list` CHANGE  `taskID`  `taskID` INT( 30 ) UNSIGNED DEFAULT '0' NOT NULL;
ALTER TABLE `eq_task_list` DROP PRIMARY KEY;
ALTER TABLE `eq_task_list` ADD INDEX (  `taskID` );
TRUNCATE TABLE `eq_task_list`;

#
# 表的结构 `eq_data_task`
#

CREATE TABLE IF NOT EXISTS eq_data_task (
  taskID int(50) unsigned NOT NULL AUTO_INCREMENT,
  surveyID int(20) unsigned NOT NULL DEFAULT '0',
  responseID int(30) unsigned NOT NULL DEFAULT '0',
  administratorsID int(30) unsigned NOT NULL DEFAULT '0',
  taskTime int(11) unsigned NOT NULL DEFAULT '0',
  authStat int(1) unsigned NOT NULL DEFAULT '0',
  appStat int(1) unsigned NOT NULL DEFAULT '0',
  nextUserId int(20) unsigned NOT NULL DEFAULT '0',
  reason text NOT NULL,
  PRIMARY KEY (taskID),
  KEY administratorsID (administratorsID),
  KEY responseID (responseID),
  KEY surveyID (surveyID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_data_trace`
#

CREATE TABLE IF NOT EXISTS eq_data_trace (
  traceID int(50) unsigned NOT NULL AUTO_INCREMENT,
  surveyID int(20) unsigned NOT NULL DEFAULT '0',
  responseID int(30) unsigned NOT NULL DEFAULT '0',
  administratorsID int(30) unsigned NOT NULL DEFAULT '0',
  questionID int(30) unsigned NOT NULL DEFAULT '0',
  traceTime int(11) unsigned NOT NULL DEFAULT '0',
  varName varchar(100) NOT NULL DEFAULT '',
  oriValue text NOT NULL,
  updateValue text NOT NULL,
  isAppData int(1) unsigned NOT NULL DEFAULT '0',
  evidence varchar(100) NOT NULL DEFAULT '',
  reason text NOT NULL,
  PRIMARY KEY (traceID),
  KEY surveyID (surveyID),
  KEY responseID (responseID),
  KEY administratorsID (administratorsID),
  KEY questionID (questionID),
  KEY isAppData (isAppData)
) ENGINE=MyISAM;

#指标分析
ALTER TABLE `eq_survey` ADD  `indexVersion` INT( 1 ) UNSIGNED DEFAULT  '0' NOT NULL AFTER  `apiVarName` ,
ADD  `indexTime` INT( 11 ) UNSIGNED DEFAULT  '0' NOT NULL AFTER  `indexVersion` ,
ADD  `indexAdminId` INT( 30 ) UNSIGNED DEFAULT  '0' NOT NULL AFTER  `indexTime`;

#
# 表的结构 `eq_survey_index_result`
#

CREATE TABLE IF NOT EXISTS eq_survey_index_result (
  responseID int(30) unsigned NOT NULL DEFAULT '0',
  taskID int(30) unsigned NOT NULL DEFAULT '0',
  surveyID int(20) unsigned NOT NULL DEFAULT '0',
  indexID int(30) unsigned NOT NULL DEFAULT '0',
  indexValue float(10,2) unsigned NOT NULL DEFAULT '0.00',
  KEY indexID (indexID),
  KEY surveyID (surveyID),
  KEY responseID (responseID)
) ENGINE=MyISAM;
