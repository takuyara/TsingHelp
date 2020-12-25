<?php if (!defined('TS_DEV')) { header('HTTP/1.1 403 Forbidden'); die(); }

$TABLE_STORES = 'ts_stores';
$TABLE_PRODUCTS = 'ts_products';

if (TS_DEV) {
	$ts_db->exec('DROP TABLE IF EXISTS ' . $TABLE_STORES);
}
if (!$ts_db->exec('CREATE TABLE ' . $TABLE_STORES . ' (s_id INT AUTO_INCREMENT, s_name VARCHAR(63), s_icon VARCHAR(511), s_deliv FLOAT, s_price FLOAT, s_rating FLOAT, s_p_n FLOAT, s_fee FLOAT, s_price_min FLOAT, PRIMARY KEY (s_id)) ENGINE=InnoDB DEFAULT CHARSET=' . TsConfig::get('db', 'charset'))) {
	echo 'could not create table ', $TABLE_STORES;
	exit(-1);
}
if (!$ts_db->exec('CREATE TABLE ' . $TABLE_PRODUCTS . ' (p_id INT AUTO_INCREMENT, p_name VARCHAR(63), p_price FLOAT, s_id INT, PRIMARY KEY (p_id)) ENGINE=InnoDB DEFAULT CHARSET=' . TsConfig::get('db', 'charset'))) {
	echo 'could not create table ', $TABLE_PRODUCTS;
	exit(-1);
}
if (TS_DEV) {
	if (!$ts_db->exec('INSERT INTO ' . $TABLE_STORES . ' 
	(s_name, s_icon, s_deliv, s_price, s_rating, s_p_n, s_fee, s_price_min) 
	VALUES 
	(\'¡�Ӳ��̣������ڵ꣩\', \'favicon.ico\', 41, 21, 9.7, 13, 7, 29),
	(\'�������ʣ���������ڵ꣩\', \'favicon.ico\', 36, 13, 9.2, 15, 0, 15), 
	(\'�������ɲݣ�����ʳ���ֵ꣩\', \'favicon.ico\', 55, 13, 9.5, 6, 6, 20),
	(\'ϲ�裨�����廪�����ŵ꣩\', \'favicon.ico\', 75, 33, 9.6, 8, 6, 30),
	(\'LELECHA���ֲ裨����ڵ꣩\', \'favicon.ico\', 68, 28, 9.7, 6, 1, 30), 
	(\'1��㣨�����ڵ꣩\', \'favicon.ico\', 42, 17, 9.4, 15, 5.5, 20), 
	(\'�մգ�������ׯ�꣩\', \'favicon.ico\', 46, 20, 9.8, 5, 5, 20), 
	(\'��������\', \'favicon.ico\', 32, 18, 9.9, 6, 3, 15), 
	(\'һ��̨��ˮ���裨����ڵ꣩\', \'favicon.ico\', 34, 17, 9.4, 8, 0, 20)
	')) {
		echo 'could not insert into ', $TABLE_STORES;
		exit(-1);
	}
	if (!$ts_db->exec('INSERT INTO ' . $TABLE_PRODUCTS . ' (p_name, p_price, s_id) VALUES 
		(\'¡�������̲�\', 17, 1), 
		(\'�ɷ������̲�\', 16, 1), 
		(\'ů����ĸ�̲�\', 16, 1), 
		(\'���������̲�\', 16, 1), 
		(\'�Ϲ�˿������\', 24, 1), 
		(\'�������̣����ƾ���\', 23, 1), 
		(\'Ҭ���춹����\', 24, 1), 
		(\'����Ĩ��\', 17, 1), 
		(\'��֦��贿��\', 13, 1), 
		(\'���Һ�Ĩ\', 17, 1), 
		(\'õ���ն�����\', 13, 1), 
		(\'�����ն�����\', 13, 1), 
		(\'���ҽ��洿��\', 16, 1), 
		
		(\'OREO�����̲�\', 15, 2), 
		(\'��ԲС�����̲�\', 16, 2), 
		(\'����С���������̲�\', 16, 2), 
		(\'���쵰���ζ�̲�\', 20, 2), 
		(\'���쵰�������̲�\', 17, 2), 
		(\'�����̲�\', 14, 2), 
		(\'â�Ȱ����ļ�����\', 17, 2), 
		(\'�����᲼���̲�\', 17, 2), 
		(\'���ʲ�����\', 16, 2), 
		(\'�춹�����̲�\', 15, 2), 
		(\'����֥ʿ�̲�\', 17, 2), 
		(\'��ݮ���쵰���̲�\', 20, 2), 
		(\'ݮݮ��������ˬ\', 18, 2), 
		(\'�ƶ��ɿ�\', 18, 2), 
		(\'���ֶ������̲�\', 20, 2), 

		(\'�������ɲݣ��󱭣�\', 15, 3), 
		(\'����С���̲�\', 15, 3), 
		(\'��֦��¶���ɲ�\', 16, 3), 
		(\'С��Բ���ɲ�\', 14, 3), 
		(\'ҬҬ���ɲݣ��󱭣�\', 16, 3), 
		(\'����С��Բ�̲�\', 15, 3),

		(\'â�Ȱ����ļ�����\', 17, 3), 
		(\'�����᲼���̲�\', 17, 3), 
		(\'���ʲ�����\', 16, 3), 
		(\'�춹�����̲�\', 15, 3), 
		(\'����֥ʿ�̲�\', 17, 3), 
		(\'��ݮ���쵰���̲�\', 30, 3), 
		(\'ݮݮ��������ˬ\', 18, 3), 
		(\'�ƶ��ɿ�\', 18, 3), 
		(\'���ֶ������̲�\', 30, 3), 

		(\'��������\', 29, 4), 
		(\'�����ನ���̲�\', 28, 4), 
		(\'����𻨶�\', 26, 4),
		(\'ѩɽ�������\', 29, 4), 
		(\'����ââ��¶\', 27, 4), 
		(\'���յ��Ⲩ���̲�\', 28, 4),
		(\'ѩɽ�������\', 29, 4), 
		(\'��¤���\', 16, 4), 

		(\'�����������̲�\', 19, 5), 
		(\'��ݮ��������\', 30, 5), 
		(\'���ǲ��������\', 21, 5),
		(\'��֦��¶����\', 29, 5), 
		(\'â������\', 29, 5), 
		(\'õ���۹�����\', 28, 5),

		(\'���󱭣��ļ�����\', 14, 6), 
		(\'���󱭣������̲�\', 14, 6), 
		(\'���б��������̲�\', 15, 6),
		(\'���б���Ĩ���̲�\', 34, 6), 
		(\'���б����ļ�����\', 11, 6), 
		(\'���󱭣���������+�䲨Ҭ\', 15, 6),
		(\'���б����ɿɶ��������̲�\', 20, 6), 
		(\'���󱭣��ɿɶ��������̲�\', 28, 6), 
		(\'���󱭣�â��������\', 24, 6), 
		(\'���б���â��������\', 19, 6),
		(\'���б����������������\', 15, 6), 
		(\'���󱭣��ƶ�â����\', 24, 6), 
		(\'���󱭣��������������\', 19, 6),
		(\'���б����ƶ�â����\', 19, 6), 
		(\'�ƶ������\', 18, 6), 

		(\'����������̲�\', 24, 7), 
		(\'��������\', 20, 7), 
		(\'��������\', 26, 7),
		(\'�������̲�\', 20, 7), 
		(\'��÷�̲�\', 24, 7),

		(\'��ͷ��ʯ��\', 22, 8), 
		(\'ââ��\', 20, 8), 
		(\'��Ū��\', 18, 8),
		(\'�׾�����\', 18, 8), 
		(\'ݮݮ����\', 21, 8), 
		(\'�̱�ʯ��\', 20, 8),

		(\'���ۼ@Ҭ��L\', 24, 9), 
		(\'���ۼ@Ҭ��M\', 20, 9), 
		(\'����������¶L\', 21, 9), 
		(\'����������¶M\', 17, 9), 
		(\'��֦��¶L\', 23, 9), 
		(\'��֦��¶M\', 19, 9), 
		(\'�����������̲�L\', 22, 9), 
		(\'�����������̲�M\', 18, 9) 
	')) {
		echo 'could not insert into ', $TABLE_PRODUCTS;
		exit(-1);
	}
}
