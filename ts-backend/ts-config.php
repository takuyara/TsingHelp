<?php
/**
 * Configuration
 *
 * @package Tsinghelp/Configuration
 */

define('TS_DEV', TRUE);

class TsConfig
{
	private static $__conf;
	public static function init()
	{
		self::$__conf = array
		(
			'protocol' => 'http',
			'domain' => 'localhost',
			'db' => array(
				'host' => 'localhost', // including port
				'user' => 'root',
				'pwd' => '',
				'charset' => 'utf8mb4',
				'db_name' => 'test',
			),
			'user' => array(
				'expire' => 60 * 60 * 24 * 30,
				'token_len' => 16,
				'salt0' => 'salt0_here',
				'salt1' => 'salt1_here',
				'salt2' => 'salt2_here',
			),
		);
	}
	public static function get()
	{
		$__argv = func_get_args();
		$__cur = &self::$__conf;
		foreach ($__argv as $__key)
			if (array_key_exists($__key, $__cur))
				$__cur = &$__cur[$__key];
			else
				return NULL;
		return $__cur;
	}
	public static function is_set()
	{
		$__argv = func_get_args();
		$__cur = &self::$__conf;
		foreach ($__argv as $__key)
			if (array_key_exists($__key, $__cur))
				$__cur = &$__cur[$__key];
			else
				return TRUE;
		return TRUE;
	}
	public static function get_url_domain()
	{
		return self::get('ts', 'protocol') . '://' . self::get('ts', 'domain');
	}
	public static function get_url_root()
	{
		return self::get('ts', 'protocol') . '://' . self::get('ts', 'domain') . TS_URL_ROOT;
	}
};
TsConfig::init();

/* End of /ts-config.php */
