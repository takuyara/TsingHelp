<?php if (!defined('TS_DEV')) { header('HTTP/1.1 403 Forbidden'); die(); }

/**
 * Error Controller
 * @todo further process by template
 * @todo try catch
 *
 * @package Tsinghelp/Libraries/Error
 */

define('TS_ERROR_TYPE_ERROR', 0);
define('TS_ERROR_TYPE_WARNING', 1);
define('TS_ERROR_TYPE_NOTICE', 2);
define('TS_ERROR_TYPE_REDIR_301', 3);
define('TS_ERROR_TYPE_REDIR_302', 4);
define('TS_ERROR_TYPE_UNKNOWN', -1);

/**
 * @property private static int $__num number of errors registered
 * @property private static string $__error[$i]['_name'] error name
 * @property private static int $__error[$i]['_type'] error type
 * @property private static TsValue $__error[$i]['_info'] error information
 * @method public static boolean register(string $token, string $name, int $type, string $info, mixed $argv, boolean $is_file)
 * @method public static boolean trigger(string|int $code, ...)
 */
class TsError
{
	private static $__num = 0, $__error = array();
	/**
	 * @param string $token token of the error (as the constant name of the error)
	 * @param string $name name of the error to be displayed
	 * @param int $type error type (among TS_ERROR_TYPE_*)
	 * @param string $info, mixed $argv, boolean $is_file error information (to be saved as a TsValue object)
	 * @param boolean FALSE when failed
	 */
	public static function register($token, $name, $type, $info = '', $argv = array(), $is_file = FALSE)
	{
		if (!is_string($token) || defined($token))
			return FALSE;
		self::$__error[self::$__num] = array
		(
			'_name' => (string) $name,
			'_info' => new TsValue($info, $argv, $is_file)
		);
		switch ($type)
		{
		case TS_ERROR_TYPE_ERROR:
		case TS_ERROR_TYPE_WARNING:
		case TS_ERROR_TYPE_NOTICE:
		case TS_ERROR_TYPE_REDIR_301:
		case TS_ERROR_TYPE_REDIR_302:
			self::$__error[self::$__num]['_type'] = $type;
			break;
		default:
			self::$__error[self::$__num]['_type'] = TS_ERROR_TYPE_UNKNOWN;
		}
		define($token, self::$__num++);
		return TRUE;
	}
	/**
	 * @param string|int $code error code (since the constant $token (i.e. TS_ERROR_0) is defined, you can just use TS_ERROR_0 instead of 'TS_ERROR_0')
	 * @return boolean FALSE when failed
	 */
	public static function trigger($code)
	{
		if (is_string($code))
		{
			if (!defined($code))
				return FALSE;
			else
				$code = constant($code);
		}
		if (!is_int($code) || !isset(self::$__error[$code]))
			return FALSE;
		$info = self::$__error[$code]['_info']->get(array_slice(func_get_args(), 1));
		switch (self::$__error[$code]['_type'])
		{
		case TS_ERROR_TYPE_REDIR_301:
			header(TS_HTTP_PROTOCOL . ' 301 Moved Permanently'); // TS_HTTP_PROTOCOL ?
			header('Location: ' . $info);
			die();
		case TS_ERROR_TYPE_REDIR_302:
			header(TS_HTTP_PROTOCOL . ' 302 Found'); // TS_HTTP_PROTOCOL ?
			header('Location: ' . $info);
			die();
		default:
			/** @todo further process by template */
			echo '<div><strong>', htmlspecialchars(self::$__error[$code]['_name']), '</strong>: ', htmlspecialchars($info), '</div>';
			if (self::$__error[$code]['_type'] === TS_ERROR_TYPE_ERROR)
				die();
		}
		return TRUE;
	}
};

/* End of /ts-libs/ts/error.php */
