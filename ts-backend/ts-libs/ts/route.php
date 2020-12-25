<?php if (!defined('TS_DEV')) { header('HTTP/1.1 403 Forbidden'); die(); }
ts_import('ts/error');

/**
 * Routing Controller
 *
 * @package Tsinghelp\Libraries\Route
 */

define('TS_ROUTE_KEY_POST',  1);
define('TS_ROUTE_KEY_GET',   2);
define('TS_ROUTE_KEY_FILES', 4);

class TsRoute {
	private static
		$__hooks = array();
	public static function hook($uri, $keys) {
		if (array_key_exists($uri, self::$__hooks))
			return FALSE;//TODO Error!
		$handlers = func_get_args();
		unset($handlers[0], $handlers[1]);
		self::$__hooks[$uri] = array(
			$keys,     // key => type
			$handlers, // sequential
		);
	}
	public static function run($uri) {
		if (!array_key_exists($uri, self::$__hooks)) {
			echo 'invalid uri';			
			return FALSE;//TODO 404
		}
		list($keys, $handlers) = self::$__hooks[$uri];
		$ts_args = array();
		foreach ($keys as $key => $type) {
			if (($type & TS_ROUTE_KEY_POST) && array_key_exists($key, $_POST))
				$ts_args[$key] = $_POST[$key];
			elseif (($type & TS_ROUTE_KEY_GET) && array_key_exists($key, $_GET))
				$ts_args[$key] = $_GET[$key];
			elseif (($type & TS_ROUTE_KEY_FILES) && array_key_exists($key, $_FILES))
				$ts_args[$key] = $_FILES[$key];
			else {
				echo 'key [', $key,'] not found';
				return FALSE;//TODO Error!
			}
		}
		foreach ($handlers as $handler)
			$result = call_user_func($handler, $ts_args);
		return $result;
	}
	public static function uri() {
		$uri = $_SERVER['REQUEST_URI'];
		$pos = strlen(TS_URI_ROOT);
		if (strlen($uri) < $pos || substr($uri, 0, $pos) !== TS_URI_ROOT) {
			echo 'invalid uri root';
			return FALSE;//TODO 404
		}
		$uri = substr($uri, $pos);
		$pos = strpos($uri, '?');
		if ($pos !== FALSE)
			$uri = substr($uri, 0, $pos);
		return $uri;
	}
};

/* End of /ts-libs/ts/route.php */
