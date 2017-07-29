
#
# 5.20 修订
#

#
# 表的结构 `eq_maillist`
#

CREATE TABLE IF NOT EXISTS eq_mail_list (
  maillistID int(30) unsigned NOT NULL auto_increment,
  mailTitle varchar(255) binary NOT NULL default '',
  sendMailName text NOT NULL,
  sendFailName text NOT NULL,
  sendMailContent text NOT NULL,
  administratorsID int(30) unsigned NOT NULL default '0',
  mailType int(1) unsigned NOT NULL default '0',
  createDate int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (maillistID),
  KEY administratorsID (administratorsID)
) TYPE=MyISAM;

#
# 表的结构 `eq_reportdefine`
#

CREATE TABLE IF NOT EXISTS eq_report_define (
  defineID int(10) unsigned NOT NULL auto_increment,
  surveyID int(20) unsigned NOT NULL default '0',
  questionID int(30) unsigned NOT NULL default '0',
  condOnID int(30) unsigned NOT NULL default '0',
  optionID int(20) unsigned NOT NULL default '0',
  qtnID int(20) unsigned NOT NULL default '0',
  condOnID2 int(30) unsigned NOT NULL default '0',
  optionID2 int(20) unsigned NOT NULL default '0',
  qtnID2 int(20) unsigned NOT NULL default '0',
  defineType int(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (defineID),
  KEY surveyID (surveyID),
  KEY questionID (questionID),
  KEY optionID (optionID),
  KEY qtnID (qtnID),
  KEY defineType (defineType),
  KEY condOnID (condOnID),
  KEY condOnID2 (condOnID2),
  KEY optionID2 (optionID2),
  KEY qtnID2 (qtnID2)
) TYPE=MyISAM;
