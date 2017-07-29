
#
# 6.20 修订
#

TRUNCATE TABLE  `eq_downs`;

INSERT INTO eq_downs VALUES (1, 'EnableQ系统快速使用指南', 'EnableQ在线问卷调查引擎参考手册：快速使用指南', 'EnableQQuickGuide.zip', '728', 'y', 1334897089, 1334897089, 1);
INSERT INTO eq_downs VALUES (2, 'EnableQ系统用户手册', 'EnableQ在线问卷调查引擎参考手册：系统用户手册', 'EnableQUserManual.html', '1.00', 'y', 1250052832, 1250052832, 2);
INSERT INTO eq_downs VALUES (3, 'EnableQ开发人员手册', 'EnableQ在线问卷调查引擎参考手册：数据接口规范', 'EnableQDeveloperManual.zip', '233.00', 'y', 1250052832, 1250052832, 3);
INSERT INTO eq_downs VALUES (4, 'CSV文件分割器', 'EnableQ在线问卷调查引擎-CSV文件分割器<br>EnableQ系统提供的辅助工具，用以对导出的问卷结果数据CSV大文件进行自定义小文件分割。<br>可把较多行数或列数的CSV文件分割成独立的多个小CSV文件。', 'csvsplit.exe', '52.00', 'y', 1244166190, 1244166190, 4);

ALTER TABLE  `eq_android_info` ADD  `line1Number` VARCHAR( 15 ) BINARY NOT NULL DEFAULT '' AFTER  `responseID` ;
