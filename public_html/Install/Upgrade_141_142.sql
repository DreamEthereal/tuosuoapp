#
# 1.42修订
#

ALTER TABLE `eq_response_group_list` CHANGE `adUserGroupName` `adUserName` VARCHAR( 100 ) BINARY DEFAULT '' NOT NULL;

#
# 表的结构 `eq_input_user_list`
#

CREATE TABLE IF NOT EXISTS eq_input_user_list (
  inputUserListID int(20) unsigned NOT NULL default '0',
  administratorsID int(30) unsigned NOT NULL default '0',
  surveyID int(20) unsigned NOT NULL default '0',
  KEY administratorsID (administratorsID),
  KEY surveyID (surveyID)
) TYPE=MyISAM;