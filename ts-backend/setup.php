<?php
require 'ts-config.php';

$SETUP_MARK = 'setup.txt';
$TABLE_USERS = 'ts_users';
error_reporting(E_ALL | E_STRICT);
if (!file_exists($SETUP_MARK)) {
	if (!TS_DEV) {
		file_put_contents($SETUP_MARK, 'ts setup done');
	}
	require 'ts-db.php';
	if (TS_DEV) {
		$ts_db->exec('DROP TABLE IF EXISTS ' . $TABLE_USERS);
	}
	if (!$ts_db->exec('CREATE TABLE ' . $TABLE_USERS . ' (uid VARCHAR(63), pwd VARCHAR(511), token VARCHAR(127), PRIMARY KEY (uid)) ENGINE=InnoDB DEFAULT CHARSET=' . TsConfig::get('db', 'charset'))) {
		echo 'could not create table ts_users';
		exit(-1);
	}
}
echo 'ts setup done';
