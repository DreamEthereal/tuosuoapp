
			INSTALL NOTES ABOUT ENABLEQ System


�й�EnableQϵͳ���ڶ�Web�������µĻ�����װ
------------------------------------------

EnableQϵͳ�������LVS+��web����������ʵ�ַ��ʷ�������ʵ�ָ߸����µ�Ӧ��ѹ������ô���Ӧ�ĸı��ǣ�

1) �޸�Config/MMConfig.inc.php�ļ���$Config['dataDirectory']������ֵΪ'RepData'��
   �����ʾ�ظ��ĸ����ļ��洢�ڡ�RepData���У�
2) �޸�Config/MMConfig.inc.php�ļ���$Config['cacheDirectory']������ֵΪ'PerUserData/Cache'��
   �����ʾ����ļ��洢�ڡ�PerUserData/Cache���У�
3) ����rsyncϵͳ���߽�PerUserData/Ŀ¼�µ��ļ������Թ��������ʵʱͬ�������Web��������
4) ����rsyncϵͳ���߽�RepData/Ŀ¼�µĻظ��ļ������Զ��Web������ʵʱͬ���������������
5) �޸�Config/MMConfig.inc.php�ļ���$Config['dataDomainName']������ֵΪ�ʾ����ݻ��շ��������(���ܰ�������·��)��
6) �޸�Config/MMConfig.inc.php�ļ���$Config['pubDomainName']������ֵΪ�����������������IP��ַ(���ܰ�������·��)��
7) �޸�Config/MMConfig.inc.php�ļ���$Config['serverAddress']������ֵΪ�ʾ����ݻ��շ�������IP��ַ����(���ܰ�������·��)��

