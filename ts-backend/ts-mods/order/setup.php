<?php if (!defined('TS_DEV')) { header('HTTP/1.1 403 Forbidden'); die(); }

$TABLE_ORDERS = 'ts_orders';
$TABLE_CBS = 'ts_cbs';

if (TS_DEV) {
	$ts_db->exec('DROP TABLE IF EXISTS ' . $TABLE_ORDERS);
}
if (!$ts_db->exec('CREATE TABLE ' . $TABLE_ORDERS . 'CREATE TABLE orders (
	o_id INT AUTO_INCREMENT,
	o_u_id VARCHAR(31),
	o_s_id VARCHAR(30),
	o_s_name VARCHAR(30),
	o_p_ids VARCHAR(1023),
	o_p_n VARCHAR(80),
	o_cb_id VARCHAR(30),
	o_cb_n INT(6),
	o_price FLOAT(24),
	o_raw_price FLOAT(24),
	o_confirm bit(1),
	o_paid bit(1),
) ENGINE=InnoDB DEFAULT CHARSET=' . TsConfig::get('db', 'charset'))) {
	echo 'could not create table ' . $TABLE_ORDERS;
	exit(-1);
}
if (!$ts_db->exec('CREATE TABLE ' . $TABLE_CBS . ' (
	cb_id VARCHAR(30),
	cb_s_id VARCHAR(30),
    cb_s_name VARCHAR(30),
	cb_o_ids VARCHAR(80),
    cb_n INT(6),
    cb_status INT(2),
    cb_from INT(6),
    cb_to INT(6),
    cb_pay VARBINARY(200),
    cb_done_time INT(30),
    cb_done bit(1),
    cb_g_id INT(6),
	cb_start_time INT(20),
	cb_raw_price INT(20),
	cb_price INT(20)
)')) {
	echo 'could not create table ' . $TABLE_CBS;
	exit(-1);
}
if (TS_DEV) {
	/*if (!$ts_db->exec('INSERT INTO ' . $TABLE_ORDERS . ' (o_id, o_u_id, o_s_id, o_s_name, o_p_ids, o_p_n, o_cb_id, o_cb_n, o_price, o_raw_price, o_confirm, o_paid) VALUES
		()
')) {
		echo 'could not insert into ' . $TABLE_ORDERS;
		exit(-1);
	}*/
}
