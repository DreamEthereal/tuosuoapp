
#
# 7.10 修订
#

ALTER TABLE  `eq_survey` ADD  `offlineCount` VARCHAR( 255 ) BINARY NOT NULL DEFAULT  '' AFTER  `isPanelFlag` ;
ALTER TABLE  `eq_survey` ADD  `isCheckStat0` INT( 1 ) UNSIGNED DEFAULT  '0' NOT NULL AFTER  `limitedTime` ;
ALTER TABLE  `eq_survey` ADD  `isOfflineModi` INT( 1 ) UNSIGNED DEFAULT  '0' NOT NULL AFTER  `isCheckStat0` ;
ALTER TABLE  `eq_survey_cate` CHANGE  `cateName`  `cateName` VARCHAR( 255 ) BINARY NOT NULL default '';
ALTER TABLE  `eq_survey_cate_list` ADD INDEX (  `cateListID` );
ALTER TABLE  `eq_survey_cate_list` CHANGE  `cateListID`  `cateListID` INT( 20 ) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE  `eq_report_define` CHANGE `questionID` `questionID` VARCHAR( 255 ) BINARY NOT NULL DEFAULT  '';

#
# 表的结构 `eq_task_list`
#

CREATE TABLE IF NOT EXISTS eq_task_list (
  taskID int(30) unsigned NOT NULL AUTO_INCREMENT,
  surveyID int(20) unsigned NOT NULL DEFAULT '0',
  taskName varchar(255) NOT NULL default '',
  taskDesc varchar(255) NOT NULL default '',
  administratorsID int(30) unsigned NOT NULL DEFAULT '0',
  taskTime int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (taskID),
  KEY administratorsID (administratorsID),
  KEY surveyID (surveyID)
) TYPE=MyISAM;