
#
# 7.20 ÐÞ¶©
#

ALTER TABLE  `eq_report_define` ADD  `administratorsID` INT( 30 ) UNSIGNED DEFAULT  '0' NOT NULL AFTER  `surveyID` ;
ALTER TABLE  `eq_report_define` ADD INDEX (  `administratorsID` );
ALTER TABLE  `eq_report_define` ADD  `defineShare` INT( 1 ) UNSIGNED DEFAULT  '1' NOT NULL AFTER  `surveyID` ;
ALTER TABLE  `eq_report_define` ADD INDEX (  `defineShare` );
ALTER TABLE  `eq_query_list` ADD  `defineShare` INT( 1 ) UNSIGNED DEFAULT  '0' NOT NULL ;
ALTER TABLE  `eq_query_list` ADD INDEX (  `defineShare` );
ALTER TABLE  `eq_survey` ADD  `forbidViewId` text NOT NULL AFTER  `offlineCount` ;
ALTER TABLE  `eq_survey` ADD  `isExportData` INT( 1 ) UNSIGNED DEFAULT  '0' NOT NULL AFTER  `isViewAuthData` ;
ALTER TABLE  `eq_survey` ADD  `isImportData` INT( 1 ) UNSIGNED DEFAULT  '0' NOT NULL AFTER  `isExportData` ;
