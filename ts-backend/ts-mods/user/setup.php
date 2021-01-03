<?php if (!defined('TS_DEV')) { header('HTTP/1.1 403 Forbidden'); die(); }

$TABLE_USERS = 'ts_users';

if (TS_DEV) {
	$ts_db->exec('DROP TABLE IF EXISTS ' . $TABLE_USERS);
}
if (!$ts_db->exec('CREATE TABLE ' . $TABLE_USERS . ' (uid VARCHAR(63), hashed_pwd VARCHAR(511), token VARCHAR(127), PRIMARY KEY (uid)) ENGINE=InnoDB DEFAULT CHARSET=' . TsConfig::get('db', 'charset'))) {
	echo 'could not create table ' . $TABLE_USERS;
	exit(-1);
}
if (TS_DEV) {
	if (!$ts_db->exec('INSERT INTO ' . $TABLE_USERS . ' (uid, hashed_pwd, token) VALUES (\'admin\', \'$2y$10$T1SqgEKTpmL5/ksngWkVCuzZYTK.XKG6zFgyZB.rNB9de1.mNe0ju\', \'test_token\')')) { // pwd = 1234
		echo 'could not insert into ' . $TABLE_USERS;
		exit(-1);
	}
}
