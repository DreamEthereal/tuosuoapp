#
# V1.21ÐÞ¶©
#

ALTER TABLE eq_survey ADD tokenCode VARCHAR( 20 ) BINARY DEFAULT '' NOT NULL AFTER isPublic ;
ALTER TABLE `eq_question` CHANGE `otherText` `otherText` VARCHAR( 200 ) DEFAULT '' NOT NULL;
ALTER TABLE `eq_question_radio` CHANGE `optionName` `optionName` VARCHAR( 255 ) BINARY DEFAULT '' NOT NULL;
ALTER TABLE `eq_question_rank` CHANGE `optionName` `optionName` VARCHAR( 255 ) BINARY DEFAULT '' NOT NULL ;
ALTER TABLE `eq_question_range_option` CHANGE `optionName` `optionName` VARCHAR( 255 ) BINARY DEFAULT '' NOT NULL ;
ALTER TABLE `eq_question_checkbox` CHANGE `optionName` `optionName` VARCHAR( 255 ) BINARY DEFAULT '' NOT NULL ;
ALTER TABLE `eq_question_range_answer` CHANGE `optionAnswer` `optionAnswer` VARCHAR( 200 ) BINARY DEFAULT '' NOT NULL ;