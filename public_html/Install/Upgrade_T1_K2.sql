#
# 2.0 K2�޶�
#

#
# ��Ľṹ `eq_survey_cate`
#

CREATE TABLE IF NOT EXISTS eq_survey_cate (
  cateID int(6) unsigned NOT NULL auto_increment,
  cateTag varchar(64) binary NOT NULL default '',
  cateName varchar(255) NOT NULL default '',
  PRIMARY KEY  (cateID)
) TYPE=MyISAM;

#
# ��Ľṹ `eq_survey_cate_list`
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
# ��Ľṹ `eq_option`
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
# �������е����� `eq_option`
#

TRUNCATE TABLE  `eq_option`;

INSERT INTO eq_option VALUES (1, '�����', '�ܲ�����\r\n������\r\nһ��\r\n����\r\n������', 0);
INSERT INTO eq_option VALUES (2, '��Ҫ��', '�ܲ���Ҫ\r\n����Ҫ\r\nһ��\r\n��Ҫ\r\n����Ҫ', 0);
INSERT INTO eq_option VALUES (3, 'Ը���', '�ܲ�Ը��\r\n��Ը��\r\nԸ��\r\n��Ը��\r\n�ǳ�Ը��', 0);
INSERT INTO eq_option VALUES (4, '��ͬ��', '�ܲ�ͬ��\r\n��ͬ��\r\nһ��\r\nͬ��\r\n��ͬ��', 0);
INSERT INTO eq_option VALUES (5, '����', '1\r\n2\r\n3\r\n4\r\n5', 0);
INSERT INTO eq_option VALUES (6, '����ֵ', '0\r\n2.5\r\n5\r\n7.5\r\n10', 0);

ALTER TABLE `eq_question` CHANGE  `questionName`  `questionName` TEXT NOT NULL default '';
ALTER TABLE `eq_question` ADD `baseID` INT( 30 ) UNSIGNED DEFAULT '0' NOT NULL AFTER `isSelect` ;
