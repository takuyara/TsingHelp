<?php if (!defined('TS_DEV')) { header('HTTP/1.1 403 Forbidden'); die(); }

$TABLE_STORES = 'ts_stores';
$TABLE_PRODUCTS = 'ts_products';

if (TS_DEV) {
	$ts_db->exec('DROP TABLE IF EXISTS ' . $TABLE_STORES);
	$ts_db->exec('DROP TABLE IF EXISTS ' . $TABLE_PRODUCTS);
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
	if (!$ts_db->exec('INSERT INTO ' . $TABLE_STORES . ' (s_name, s_icon, s_deliv, s_price, s_rating, s_p_n, s_fee, s_price_min) VALUES (\'Store 1\', \'favicon.ico\', 100, 12, 10, 5, 3, 5), (\'Store 2\', \'favicon.ico\', 100, 12, 10, 5, 3, 5)')) {
		echo 'could not insert into ', $TABLE_STORES;
		exit(-1);
	}
	if (!$ts_db->exec('INSERT INTO ' . $TABLE_PRODUCTS . ' (p_name, p_price, s_id) VALUES (\'Product 11\', 20, 1), (\'Product 12\', 30, 1), (\'Product 21\', 20, 2), (\'Product 22\', 20, 2)')) {
		echo 'could not insert into ', $TABLE_PRODUCTS;
		exit(-1);
	}
}
