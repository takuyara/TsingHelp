<?php if (!defined('TS_DEV')) { header('HTTP/1.1 403 Forbidden'); die(); }
/**
 * DB Library
 *
 * @package Tsinghelp/Libraries/TsDB
 */

class TsDB {
	private $__conn;
	public function __construct() {
		$this->__conn = new mysqli(TsConfig::get('db', 'host'), TsConfig::get('db', 'user'), TsConfig::get('db', 'pwd'), TsConfig::get('db', 'db_name'));
		$this->__conn->set_charset(TsConfig::get('db', 'charset'));
	}
	public function __destruct() {
		$this->__conn->close();
	}
	public function exec($sql) {
		return $this->__conn->query($sql);
	}
	public function fetch1($sql) {
		return $this->__conn->query($sql)->fetch_assoc();
	}
	public function escape($str) {
		return $this->__conn->real_escape_string($str);
	}
};

$ts_db = new TsDB();

/* End of /ts-libs/ts/db.php */
