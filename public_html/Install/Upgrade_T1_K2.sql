#
# 2.0 K2修订
#

#
# 表的结构 `eq_survey_cate`
#

CREATE TABLE IF NOT EXISTS eq_survey_cate (
  cateID int(6) unsigned NOT NULL auto_increment,
  cateTag varchar(64) binary NOT NULL default '',
  cateName varchar(255) NOT NULL default '',
  PRIMARY KEY  (cateID)
) TYPE=MyISAM;

#
# 表的结构 `eq_survey_cate_list`
#

CREATE TABLE IF NOT EXISTS eq_survey_cate_list (
  cateListID int(20) unsigned NOT NULL default '0',
  cateID int(30) unsigned NOT NULL default '0',
  surveyID int(20) unsigned NOT NULL default '0',
  KEY cateID (cateID),
  KEY surveyID (surveyID)
) TYPE=MyISAM;

ALTER TABLE  `eq_license` CHANGE  `md5`  `md5` VARCHAR( 32 ) BINARY NOT NULL default '';

#
# 表的结构 `eq_option`
#

CREATE TABLE IF NOT EXISTS eq_option (
  optionID int(4) unsigned NOT NULL auto_increment,
  optionCate varchar(50) binary NOT NULL default '',
  optionName text NOT NULL default '',
  administratorsID int(30) unsigned NOT NULL default '0',
  PRIMARY KEY  (optionID),
  KEY administratorsID (administratorsID)
) TYPE=MyISAM;

#
# 导出表中的数据 `eq_option`
#

TRUNCATE TABLE  `eq_option`;

INSERT INTO eq_option VALUES (1, '满意度', '很不满意\r\n不满意\r\n一般\r\n满意\r\n很满意', 0);
INSERT INTO eq_option VALUES (2, '重要度', '很不重要\r\n不重要\r\n一般\r\n重要\r\n很重要', 0);
INSERT INTO eq_option VALUES (3, '愿意度', '很不愿意\r\n不愿意\r\n愿意\r\n很愿意\r\n非常愿意', 0);
INSERT INTO eq_option VALUES (4, '认同度', '很不同意\r\n不同意\r\n一般\r\n同意\r\n很同意', 0);
INSERT INTO eq_option VALUES (5, '数字', '1\r\n2\r\n3\r\n4\r\n5', 0);
INSERT INTO eq_option VALUES (6, '评估值', '0\r\n2.5\r\n5\r\n7.5\r\n10', 0);

ALTER TABLE `eq_question` CHANGE  `questionName`  `questionName` TEXT NOT NULL default '';
ALTER TABLE `eq_question` ADD `baseID` INT( 30 ) UNSIGNED DEFAULT '0' NOT NULL AFTER `isSelect` ;
