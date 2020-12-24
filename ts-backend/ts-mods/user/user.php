<?php if (!defined('TS_DEV')) { header('HTTP/1.1 403 Forbidden'); die(); }

/**
 * User Module
 *
 * @package Tsinghelp/Modules/User
 */

class UserHandler {
	public static function login($args) {
		$uid = $args['u-id'];
		$pwd = $args['u-pwd'];
		$result = array('token' => 'invalid login');
		if (self::__uid_exists($uid) && self::__check_pwd($uid, $pwd))
				$result['token'] = self::__renew_token($uid);
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
	public static function signup($args) {
		$uname = $args['u-name'];
		$pwd = $args['u-pwd'];
		$result = array('signup' => 'failed', 'u-id' => '');
		if () { // TODO
			// TODO
			$result['signup'] = 'done';
			$result['u-id'] = $uid;
		}
		echo json_encode($result);
	}
	public static function hash_pwd($pwd) {
		$pwd = sha256($pwd);
		$pwd = sha256(TsConfig::get('user', 'pwd_salt0') . $pwd);
		$pwd = sha256(TsConfig::get('user', 'pwd_salt1') . $pwd);
		$pwd = sha256(TsConfig::get('user', 'pwd_salt2') . $pwd);
		return $pwd;
	}
	public static function gen_token() {
		static $__charset = array(
			'a','b','c','d','e','f','g','h','i','j','k','l','m',
			'n','o','p','q','r','s','t','u','v','w','x','y','z',
			'A','B','C','D','E','F','G','H','I','J','K','L','M',
			'N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
			'0','1','2','3','4','5','6','7','8','9'
		);
		return implode(array_rand($__charset, TsConfig::get('user', 'token_len')));
	}
	private static function __uid_exists($uid) {
		//TODO
	}
	private static function __check_pwd($uid, $pwd) {
		$pwd = self::hash_pwd($pwd);
		$res = $ts_db->query_1('SELECT @TODO');//TODO query $res['hashed_pwd']
		return $pwd === $res['hashed_pwd'];
	}
	private static function __update_pwd($uid, $pwd) {
		$pwd = self::hash_pwd($pwd);
		return $ts_db->update();//TODO
	}
	private static function __check_token($uid, $token) {
		$res = $ts_db->query_1('SELECT'); //TODO token, time
		return ($token === $res['token'] && @TODO <= TsConfig::get('auth', 'expire'));
	}
	private static function __renew_token($uid) {
		$token = self::gen_token();
		$time = ;//TODO
		return $ts_db->exec('UPDATE');//TODO
	}
	private static function __clear_token($uid) {
		$time = ;//TODO
		return $ts_db->exec('UPDATE');//TODO
	}
};

TsRoute::hook('user/login',
	array(
		'u-id' => TS_ROUTE_KEY_POST,
		'u-pwd' => TS_ROUTE_KEY_POST,
	),
	array('UserHandler', 'login'),
);
TsRoute::hook('user/logout',
	array(
		'u-id' => TS_ROUTE_KEY_POST,
		'u-token' => TS_ROUTE_KEY_POST,
	),
	array('UserHandler', 'logout'),
);
TsRoute::hook('user/change-u-pwd',
	array(
		'u-id' => TS_ROUTE_KEY_POST,
		'u-token' => TS_ROUTE_KEY_POST,
		'u-pwd-old' => TS_ROUTE_KEY_POST,
		'u-pwd-new' => TS_ROUTE_KEY_POST,
	),
	array('UserHandler', 'change_pwd'),
);

/** End of /ts-mods/user/user.php */
