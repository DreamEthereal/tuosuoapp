
#
# 8.10 ÐÞ¶©
#


ALTER TABLE `eq_survey_index_result` CHANGE  `indexValue`  `indexValue` FLOAT( 10, 2 ) DEFAULT  '0.00';
ALTER TABLE `eq_survey_index` ADD  `isMinZero` INT( 1 ) UNSIGNED DEFAULT  '0' NOT NULL;