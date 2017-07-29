#
# 2.0修订
#

ALTER TABLE `eq_question` ADD `weight` INT( 2 ) UNSIGNED DEFAULT '0' NOT NULL AFTER `allowType` ;
ALTER TABLE `eq_question` ADD `startScale` INT( 1 ) UNSIGNED DEFAULT '0' NOT NULL AFTER `allowType` ;
ALTER TABLE `eq_question` ADD `endScale` INT( 1 ) UNSIGNED DEFAULT '0' NOT NULL AFTER `startScale` ;

#
# 表的结构 eq_user_group
#

CREATE TABLE IF NOT EXISTS eq_user_group (
  userGroupID int(6) unsigned NOT NULL auto_increment,
  userGroupName varchar(60) binary NOT NULL default '',
  createDate int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (userGroupID)
) TYPE=MyISAM;

ALTER TABLE `eq_administrators` ADD `userGroupID` INT( 6 ) UNSIGNED DEFAULT '0' NOT NULL AFTER `administratorsGroupID` ;
UPDATE `eq_downs` SET `createDate` =1221204314,`updateDate` =1221204314 ;