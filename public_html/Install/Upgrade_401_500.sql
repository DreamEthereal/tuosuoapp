#
# 5.00 �޶�
#

#
# �޶����е����� `eq_downs`
#

TRUNCATE TABLE `eq_downs` ;
INSERT INTO eq_downs VALUES (2, 'EnableQ������Ա�ֲ�', 'EnableQ�����ʾ��������ο��ֲ᣺���ݽӿڹ淶', 'EnableQDeveloperManual.zip', '233.00', 'y', 1250052832, 1250052832, 2);
INSERT INTO eq_downs VALUES (1, 'EnableQϵͳ�û��ֲ�', 'EnableQ�����ʾ��������ο��ֲ᣺ϵͳ�û��ֲ�', 'EnableQUserManual.html', '1.00', 'y', 1250052832, 1250052832, 1);
INSERT INTO eq_downs VALUES (4, 'CSV�ļ��ָ���', 'EnableQ�����ʾ��������-CSV�ļ��ָ���<br>EnableQϵͳ�ṩ�ĸ������ߣ����ԶԵ������ʾ�������CSV���ļ������Զ���С�ļ��ָ<br>�ɰѽ϶�������������CSV�ļ��ָ�ɶ����Ķ��СCSV�ļ���', 'csvsplit.exe', '52.00', 'y', 1244166190, 1244166190, 4);


ALTER TABLE `eq_survey` ADD `isCache` INT( 1 ) UNSIGNED DEFAULT '1' NOT NULL AFTER `mainShowQtn` ;
ALTER TABLE `eq_survey` ADD `isSecureImage` INT( 1 ) UNSIGNED DEFAULT '0' NOT NULL AFTER `isLogicAnd` ;
ALTER TABLE `eq_survey` ADD `mainAttribute` VARCHAR( 100 ) BINARY DEFAULT '' NOT NULL AFTER `isSecureImage` ;
ALTER TABLE `eq_administratorslog` CHANGE `operationTitle` `operationTitle` VARCHAR( 255 ) DEFAULT '' NOT NULL;
ALTER TABLE `eq_administratorsoption` CHANGE `optionFieldName` `optionFieldName` VARCHAR( 50 ) BINARY DEFAULT '' NOT NULL;
ALTER TABLE `eq_response_group_list` CHANGE `adUserName` `adUserName` VARCHAR( 255 ) BINARY DEFAULT '' NOT NULL;