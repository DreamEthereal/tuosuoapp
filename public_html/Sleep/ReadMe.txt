
			INSTALL NOTES ABOUT ENABLEQ System


有关大并发高负载情形下
-----------------------------------

大并发高负载情形下Mysql系统可能有过多的Sleep连接，导致数据库访问缓慢，或者Mysql假死的情形

将本程序目录下的KillSleep.php程序加入操作系统的任务计划，让其每2分钟执行一次

KillSleep.php将轮询目前超过120s的数据库Sleep链接，并将其Kill掉

