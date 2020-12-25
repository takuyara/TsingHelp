<?php
require 'ts-init.php';
require 'ts-config.php';

$SETUP_MARK = 'setup.txt';
error_reporting(E_ALL | E_STRICT);
if (!file_exists($SETUP_MARK)) {
	if (!TS_DEV) {
		file_put_contents($SETUP_MARK, 'ts setup done');
	}
	ts_import('ts/db');
	ts_load('user/setup');
	ts_load('yyj/setup');
}
echo 'ts setup done';
