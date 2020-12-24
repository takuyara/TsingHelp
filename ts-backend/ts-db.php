<?php if (!defined('TS_DEV')) { header('HTTP/1.1 403 Forbidden'); die(); }
/**
 * DB Connection
 *
 * @package Tsinghelp
 */

class TsDB {
	private $__conn;
	public function __construct() {
		$this->__conn = new mysqli(TsConfig::get('db', 'host'), TsConfig::get('db', 'user'), TsConfig::get('db', 'pwd'), TsConfig::get('db', 'db_name'));
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
};

$ts_db = new TsDB();

/* End of /ts-db.php */

