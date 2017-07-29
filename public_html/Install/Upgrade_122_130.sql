#
# 1.30修订
#

ALTER TABLE `eq_survey` CHANGE `theme` `theme` VARCHAR( 10 ) BINARY DEFAULT 'Standard' NOT NULL;
UPDATE `eq_survey` SET theme = 'Standard';

#
# 表的结构 `eq_resultdaynum`
#

CREATE TABLE IF NOT EXISTS eq_result_day_num (
  h0 int(11) unsigned NOT NULL default '0',
  h1 int(11) unsigned NOT NULL default '0',
  h2 int(11) unsigned NOT NULL default '0',
  h3 int(11) unsigned NOT NULL default '0',
  h4 int(11) unsigned NOT NULL default '0',
  h5 int(11) unsigned NOT NULL default '0',
  h6 int(11) unsigned NOT NULL default '0',
  h7 int(11) unsigned NOT NULL default '0',
  h8 int(11) unsigned NOT NULL default '0',
  h9 int(11) unsigned NOT NULL default '0',
  h10 int(11) unsigned NOT NULL default '0',
  h11 int(11) unsigned NOT NULL default '0',
  h12 int(11) unsigned NOT NULL default '0',
  h13 int(11) unsigned NOT NULL default '0',
  h14 int(11) unsigned NOT NULL default '0',
  h15 int(11) unsigned NOT NULL default '0',
  h16 int(11) unsigned NOT NULL default '0',
  h17 int(11) unsigned NOT NULL default '0',
  h18 int(11) unsigned NOT NULL default '0',
  h19 int(11) unsigned NOT NULL default '0',
  h20 int(11) unsigned NOT NULL default '0',
  h21 int(11) unsigned NOT NULL default '0',
  h22 int(11) unsigned NOT NULL default '0',
  h23 int(11) unsigned NOT NULL default '0',
  TDay varchar(10) binary NOT NULL default '0',
  surveyID int(20) unsigned NOT NULL default '0',
  KEY surveyID (surveyID)
) TYPE=MyISAM;

#
# 表的结构 `eq_resultgeneralinfo`
#

CREATE TABLE IF NOT EXISTS eq_result_general_info (
  id int(4) unsigned NOT NULL auto_increment,
  surveyID int(20) unsigned NOT NULL default '0',
  TotalNum int(11) unsigned NOT NULL default '0',
  StartDate varchar(10) binary default NULL,
  MonthNum int(11) unsigned NOT NULL default '0',
  MonthMaxNum int(11) unsigned NOT NULL default '0',
  OldMonth varchar(10) binary default NULL,
  MonthMaxDate varchar(10) binary default NULL,
  DayNum int(11) unsigned NOT NULL default '0',
  DayMaxNum int(11) unsigned NOT NULL default '0',
  OldDay varchar(10) binary default NULL,
  DayMaxDate varchar(10) binary default NULL,
  HourNum int(11) unsigned default '0',
  HourMaxNum int(11) unsigned default '0',
  OldHour varchar(20) binary default '0',
  HourMaxTime varchar(20) binary default '0',
  PRIMARY KEY  (id),
  KEY surveyID (surveyID)
) TYPE=MyISAM;

#
# 表的结构 `eq_resultmonthnum`
#

CREATE TABLE IF NOT EXISTS eq_result_month_num (
  d1 int(11) unsigned NOT NULL default '0',
  d2 int(11) unsigned NOT NULL default '0',
  d3 int(11) unsigned NOT NULL default '0',
  d4 int(11) unsigned NOT NULL default '0',
  d5 int(11) unsigned NOT NULL default '0',
  d6 int(11) unsigned NOT NULL default '0',
  d7 int(11) unsigned NOT NULL default '0',
  d8 int(11) unsigned NOT NULL default '0',
  d9 int(11) unsigned NOT NULL default '0',
  d10 int(11) unsigned NOT NULL default '0',
  d11 int(11) unsigned NOT NULL default '0',
  d12 int(11) unsigned NOT NULL default '0',
  d13 int(11) unsigned NOT NULL default '0',
  d14 int(11) unsigned NOT NULL default '0',
  d15 int(11) unsigned NOT NULL default '0',
  d16 int(11) unsigned NOT NULL default '0',
  d17 int(11) unsigned NOT NULL default '0',
  d18 int(11) unsigned NOT NULL default '0',
  d19 int(11) unsigned NOT NULL default '0',
  d20 int(11) unsigned NOT NULL default '0',
  d21 int(11) unsigned NOT NULL default '0',
  d22 int(11) unsigned NOT NULL default '0',
  d23 int(11) unsigned NOT NULL default '0',
  d24 int(11) unsigned NOT NULL default '0',
  d25 int(11) unsigned NOT NULL default '0',
  d26 int(11) unsigned NOT NULL default '0',
  d27 int(11) unsigned NOT NULL default '0',
  d28 int(11) unsigned NOT NULL default '0',
  d29 int(11) unsigned NOT NULL default '0',
  d30 int(11) unsigned NOT NULL default '0',
  d31 int(11) unsigned NOT NULL default '0',
  TMonth varchar(10) binary NOT NULL default '0',
  surveyID int(20) unsigned NOT NULL default '0',
  KEY surveyID (surveyID)
) TYPE=MyISAM;

#
# 表的结构 `eq_resultyearnum`
#

CREATE TABLE IF NOT EXISTS eq_result_year_num (
  m1 int(11) unsigned NOT NULL default '0',
  m2 int(11) unsigned NOT NULL default '0',
  m3 int(11) unsigned NOT NULL default '0',
  m4 int(11) unsigned NOT NULL default '0',
  m5 int(11) unsigned NOT NULL default '0',
  m6 int(11) unsigned NOT NULL default '0',
  m7 int(11) unsigned NOT NULL default '0',
  m8 int(11) unsigned NOT NULL default '0',
  m9 int(11) unsigned NOT NULL default '0',
  m10 int(11) unsigned NOT NULL default '0',
  m11 int(11) unsigned NOT NULL default '0',
  m12 int(11) unsigned NOT NULL default '0',
  TYear varchar(10) binary NOT NULL default '0',
  surveyID int(20) unsigned NOT NULL default '0',
  KEY surveyID (surveyID)
) TYPE=MyISAM;


#
# 表的结构 `eq_archiving`
#

CREATE TABLE IF NOT EXISTS eq_archiving (
  archivingID int(20) unsigned NOT NULL auto_increment,
  surveyID int(20) unsigned NOT NULL default '0',
  surveyTitle varchar(255) NOT NULL default '',
  surveyName varchar(64) binary NOT NULL default '',
  administratorsID int(30) unsigned NOT NULL default '0',
  isPublic int(1) unsigned NOT NULL default '1',
  beginTime date NOT NULL default '0000-00-00',
  endTime date NOT NULL default '0000-00-00',
  archivingOwner int(30) unsigned NOT NULL default '0',
  archivingFile varchar(50) binary NOT NULL default '',
  archivingTime int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (archivingID),
  KEY surveyID (surveyID)
) TYPE=MyISAM;


ALTER TABLE `eq_question` ADD `isRandOptions` INT( 1 ) UNSIGNED DEFAULT '0' NOT NULL AFTER `isRequired` ;
ALTER TABLE `eq_responsegrouplist` RENAME `eq_response_group_list` ;
ALTER TABLE `eq_viewuserlist` RENAME `eq_view_user_list` ;


#
# 表的结构 `eq_result_comb_name`
#

CREATE TABLE IF NOT EXISTS eq_result_comb_name (
  combNameID int(10) unsigned NOT NULL auto_increment,
  surveyID int(20) unsigned NOT NULL default '0',
  questionID int(30) unsigned NOT NULL default '0',
  administratorsID int(30) unsigned NOT NULL default '0',
  combName varchar(100) binary NOT NULL default '',
  PRIMARY KEY  (combNameID),
  KEY surveyID (surveyID),
  KEY questionID (questionID),
  KEY administratorsID (administratorsID)
) TYPE=MyISAM;

#
# 表的结构 `eq_result_comb_list`
#

CREATE TABLE IF NOT EXISTS eq_result_comb_list (
  combListID int(20) unsigned NOT NULL auto_increment,
  surveyID int(20) unsigned NOT NULL default '0',
  questionID int(30) unsigned NOT NULL default '0',
  administratorsID int(30) unsigned NOT NULL default '0',
  optionID int(20) unsigned NOT NULL default '0',
  combNameID int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (combListID),
  KEY surveyID (surveyID),
  KEY questionID (questionID),
  KEY administratorsID (administratorsID),
  KEY optionID (optionID),
  KEY combNameID (combNameID)
) TYPE=MyISAM;