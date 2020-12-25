<?php if (!defined('TS_DEV')) { header('HTTP/1.1 403 Forbidden'); die(); }

/**
 * User Module
 *
 * @package Tsinghelp/Modules/User
 */

class UserHandler {
	static $TABLE_USERS = 'ts_users';
	public static function login($args) {
		global $ts_db;
		$uid = $args['u-id'];
		$pwd = $args['u-pwd'];
		$result = array('msg' => 'unknown error', 'u-token' => '');
		if (!self::valid_uid($uid))
			$result['msg'] = 'invalid uid';
		elseif (!self::__uid_exists($uid)) {
			$hashed_pwd = self::hash_pwd($pwd);
			$token = self::gen_token();
			$exe = $ts_db->exec('INSERT INTO ' . self::$TABLE_USERS . ' (uid, hashed_pwd, token) VALUES (\'' . self::escape($uid) . '\', \'' . self::escape($hashed_pwd) . '\', \'' . self::escape($token) . '\')');
			if ($exe) {
				$result['msg'] = 'signup: ok';
				$result['u-token'] = $token;
			}
			else {
				$result['msg'] = 'signup: could not create user';
			}
		}
		else {
			if (self::__check_pwd($uid, $pwd)) {
				$result['msg'] = 'login: ok';
				$result['u-token'] = self::__renew_token($uid);
			}
			else
				$result['msg'] = 'login: wrong password';
		}
		echo json_encode($result);
	}
	public static function logout($args) {
		$uid = $args['u-id'];
		$token = $args['u-token'];
		$result = array('logout' => 'invalid token');
		if (self::__uid_exists($uid) && self::__check_token($uid, $token)) {
			$result['logout'] = 'done';
			self::__clear_token($uid, $token);
		}
		echo json_encode($result);
	}
	public static function change_pwd($args) {
		$uid = $args['u-id'];
		$token = $args['u-token'];
		$pwd_old = $args['u-pwd-old'];
		$pwd_new = $args['u-pwd-new'];
		$result = array('change-u-pwd' => 'invalid');
		if (self::__uid_exists($uid) && self::__check_token($uid, $token) && self::__check_pwd($uid, $pwd_old)) {
			self::__update_pwd($uid, $pwd_new);
			$result['change-u-pwd'] = 'done';
		}
		echo json_encode($result);
	}
	public static function check_token($args) {
		$uid = $args['u-id'];
		$token = $args['u-token'];
		if (!self::__check_token($uid, $token))
			exit('invalid token');
	}
	public static function hash_pwd($pwd) {
		return password_hash($pwd, PASSWORD_DEFAULT);
	}
	public static function gen_token() {
		static $__charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
		$__charset = str_shuffle($__charset);
		return substr($__charset, 0, TsConfig::get('user', 'token_len'));
	}
	public static function escape($str) {
		global $ts_db;
		return $ts_db->escape($str);
	}
	public static function valid_uid($uid) {
		$len = strlen($uid);
		return $len >= 4 && $len <= 60;
	}
	private static function __uid_exists($uid) {
		global $ts_db;
		$res = $ts_db->fetch1('SELECT 1 FROM ' . self::$TABLE_USERS . ' WHERE uid=\'' . self::escape($uid) . '\' LIMIT 1');
		return $res !== NULL;
	}
	private static function __check_pwd($uid, $pwd) {
		global $ts_db;		
		$res = $ts_db->fetch1('SELECT hashed_pwd FROM ' . self::$TABLE_USERS . ' WHERE uid=\'' . self::escape($uid) . '\' LIMIT 1');
		return password_verify($pwd, $res['hashed_pwd']);
	}
	private static function __update_pwd($uid, $pwd) {
		global $ts_db;
		$pwd = self::hash_pwd($pwd);
		return $ts_db->exec('UPDATE ' . self::$TABLE_USERS . ' SET hashed_pwd=\'' . self::escape($pwd) . '\' WHERE uid=\'' . self::escape($uid) . '\'');
	}
	private static function __check_token($uid, $token) {
		global $ts_db;
		$res = $ts_db->fetch1('SELECT 1 FROM ' . self::$TABLE_USERS . ' WHERE uid=\'' . self::escape($uid) . '\' AND token=\'' . self::escape($token) . '\' LIMIT 1');
		return $res !== NULL;
	}
	private static function __renew_token($uid) {
		global $ts_db;
		$token = self::gen_token();
		$ts_db->exec('UPDATE ' . self::$TABLE_USERS . 'SET token=\'' . self::escape($token) . '\' WHERE uid=\'' . self::escape($uid) . '\'');
		return $token;
	}
	private static function __clear_token($uid) {
		global $ts_db;
		return $ts_db->exec('UPDATE ' . self::$TABLE_USERS . ' SET token=\'\' WHERE uid=\'' . self::escape($uid) . '\'');
	}
};

TsRoute::hook('login',
	array(
		'u-id' => TS_ROUTE_KEY_POST,
		'u-pwd' => TS_ROUTE_KEY_POST,
	),
	array('UserHandler', 'login'),
);
TsRoute::hook('logout',
	array(
		'u-id' => TS_ROUTE_KEY_POST,
		'u-token' => TS_ROUTE_KEY_POST,
	),
	array('UserHandler', 'logout'),
);
TsRoute::hook('change-u-pwd',
	array(
		'u-id' => TS_ROUTE_KEY_POST,
		'u-token' => TS_ROUTE_KEY_POST,
		'u-pwd-old' => TS_ROUTE_KEY_POST,
		'u-pwd-new' => TS_ROUTE_KEY_POST,
	),
	array('UserHandler', 'change_pwd'),
);

/** End of /ts-mods/user/user.php */
