#
# 2.0 X5修订
#

ALTER TABLE  `eq_survey` ADD  `isDisRefresh` INT( 1 ) UNSIGNED DEFAULT  '1' NOT NULL AFTER  `isViewResult` ;
ALTER TABLE  `eq_survey` ADD  `isAllowView` INT( 1 ) UNSIGNED DEFAULT  '0' NOT NULL AFTER  `isDisRefresh` ;
ALTER TABLE  `eq_survey` ADD  `isAllowEdit` INT( 1 ) UNSIGNED DEFAULT  '0' NOT NULL AFTER  `isAllowView` ;
ALTER TABLE  `eq_conditions` ADD  `qtnID` INT( 20 ) UNSIGNED DEFAULT  '0' NOT NULL ;
ALTER TABLE  `eq_conditions` ADD INDEX (`qtnID` );
ALTER TABLE  `eq_question_range_option` CHANGE  `optionName`  `optionName` TEXT DEFAULT '' NOT NULL;
ALTER TABLE  `eq_question_rank` CHANGE  `optionName`  `optionName` TEXT DEFAULT '' NOT NULL;
UPDATE  `eq_downs` SET  `filesize` =  '227.00',`createDate` =  '1244166190',`updateDate` =  '1244166190' WHERE  `downsID` =  '3';
INSERT INTO eq_downs VALUES (4, 'CSV文件分割器', 'EnableQ在线问卷调查引擎-CSV文件分割器<br>\r\nEnableQ系统提供的辅助工具，用以对导出的问卷结果数据CSV大文件进行自定义小文件分割。<br>\r\n可把较多行数或列数的CSV文件分割成独立的多个小CSV文件。', 'csvsplit.exe', '52.00', 'y', 1244166190, 1244166190, 4);
