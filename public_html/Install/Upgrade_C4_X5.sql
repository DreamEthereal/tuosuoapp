#
# 2.0 X5�޶�
#

ALTER TABLE  `eq_survey` ADD  `isDisRefresh` INT( 1 ) UNSIGNED DEFAULT  '1' NOT NULL AFTER  `isViewResult` ;
ALTER TABLE  `eq_survey` ADD  `isAllowView` INT( 1 ) UNSIGNED DEFAULT  '0' NOT NULL AFTER  `isDisRefresh` ;
ALTER TABLE  `eq_survey` ADD  `isAllowEdit` INT( 1 ) UNSIGNED DEFAULT  '0' NOT NULL AFTER  `isAllowView` ;
ALTER TABLE  `eq_conditions` ADD  `qtnID` INT( 20 ) UNSIGNED DEFAULT  '0' NOT NULL ;
ALTER TABLE  `eq_conditions` ADD INDEX (`qtnID` );
ALTER TABLE  `eq_question_range_option` CHANGE  `optionName`  `optionName` TEXT DEFAULT '' NOT NULL;
ALTER TABLE  `eq_question_rank` CHANGE  `optionName`  `optionName` TEXT DEFAULT '' NOT NULL;
UPDATE  `eq_downs` SET  `filesize` =  '227.00',`createDate` =  '1244166190',`updateDate` =  '1244166190' WHERE  `downsID` =  '3';
INSERT INTO eq_downs VALUES (4, 'CSV�ļ��ָ���', 'EnableQ�����ʾ��������-CSV�ļ��ָ���<br>\r\nEnableQϵͳ�ṩ�ĸ������ߣ����ԶԵ������ʾ�������CSV���ļ������Զ���С�ļ��ָ<br>\r\n�ɰѽ϶�������������CSV�ļ��ָ�ɶ����Ķ��СCSV�ļ���', 'csvsplit.exe', '52.00', 'y', 1244166190, 1244166190, 4);
