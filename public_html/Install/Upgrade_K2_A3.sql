#
# 2.0 A3修订
#

ALTER TABLE  `eq_question` ADD  `alias` VARCHAR( 100 ) BINARY DEFAULT '' NOT NULL AFTER  `questionName` ;
ALTER TABLE `eq_question_yesno` CHANGE `optionName` `optionName` VARCHAR( 255 ) BINARY DEFAULT '' NOT NULL;

#
# 表的结构 `eq_cond_rel`
#

CREATE TABLE IF NOT EXISTS eq_cond_rel (
  condRelID int(30) unsigned NOT NULL auto_increment,
  fatherID int(20) unsigned NOT NULL default '0',
  questionID int(30) unsigned NOT NULL default '0',
  sonID int(20) unsigned NOT NULL default '0',
  PRIMARY KEY  (condRelID),
  KEY fatherID (fatherID),
  KEY sonID (sonID),
  KEY questionID (questionID)
) TYPE=MyISAM;