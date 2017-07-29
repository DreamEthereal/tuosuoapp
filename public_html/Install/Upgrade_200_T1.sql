#
# 2.0 T1修订
#

ALTER TABLE  `eq_survey` ADD  `exitMode` INT( 1 ) UNSIGNED DEFAULT  '1' NOT NULL AFTER  `surveyInfo` ;
ALTER TABLE `eq_survey` ADD `mainShowQtn` INT( 30 ) UNSIGNED DEFAULT '0' NOT NULL AFTER `ajaxRtnValue` ;

#
# 表的结构 `eq_grade`
#

CREATE TABLE IF NOT EXISTS eq_grade (
  gradeID int(6) unsigned NOT NULL auto_increment,
  startOperator varchar(4) binary NOT NULL default '',
  startGrade float(4,2) NOT NULL default '0.00',
  endOperator varchar(4) binary NOT NULL default '',
  endGrade float(4,2) NOT NULL default '0.00',
  surveyID int(20) unsigned NOT NULL default '0',
  conclusion text NOT NULL default '',
  PRIMARY KEY  (gradeID),
  KEY surveyID (surveyID)
) TYPE=MyISAM;
