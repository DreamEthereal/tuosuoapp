
#
# 6.20 �޶�
#

TRUNCATE TABLE  `eq_downs`;

INSERT INTO eq_downs VALUES (1, 'EnableQϵͳ����ʹ��ָ��', 'EnableQ�����ʾ��������ο��ֲ᣺����ʹ��ָ��', 'EnableQQuickGuide.zip', '728', 'y', 1334897089, 1334897089, 1);
INSERT INTO eq_downs VALUES (2, 'EnableQϵͳ�û��ֲ�', 'EnableQ�����ʾ��������ο��ֲ᣺ϵͳ�û��ֲ�', 'EnableQUserManual.html', '1.00', 'y', 1250052832, 1250052832, 2);
INSERT INTO eq_downs VALUES (3, 'EnableQ������Ա�ֲ�', 'EnableQ�����ʾ��������ο��ֲ᣺���ݽӿڹ淶', 'EnableQDeveloperManual.zip', '233.00', 'y', 1250052832, 1250052832, 3);
INSERT INTO eq_downs VALUES (4, 'CSV�ļ��ָ���', 'EnableQ�����ʾ��������-CSV�ļ��ָ���<br>EnableQϵͳ�ṩ�ĸ������ߣ����ԶԵ������ʾ�������CSV���ļ������Զ���С�ļ��ָ<br>�ɰѽ϶�������������CSV�ļ��ָ�ɶ����Ķ��СCSV�ļ���', 'csvsplit.exe', '52.00', 'y', 1244166190, 1244166190, 4);

ALTER TABLE  `eq_android_info` ADD  `line1Number` VARCHAR( 15 ) BINARY NOT NULL DEFAULT '' AFTER  `responseID` ;
