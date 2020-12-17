<?php if (!defined('TS_DEV')) { header('HTTP/1.1 403 Forbidden'); die(); }
ts_import('ts/error');

/**
 * Value Type
 * Combines string, callable and file, with arguments attached
 *
 * @package Tsinghelp\Libraries\Value
 */

define('TS_VALUE_RAW',      0);
define('TS_VALUE_CALLABLE', 1);
define('TS_VALUE_FILE',     2);

/**
 * @property private mixed $__value
 * @property private int $__type
 * @property private mixed $__argv
 * @method public boolean set(mixed $value, mixed $argv, boolean $is_file)
 * @method public mixed get(...)
 */
class TsValue
{
	private $__value = NULL, $__type = NULL, $__argv = NULL;
	/**
	 * @param mixed $value if it is a file, its path is DA_DIR . $value
	 * @param mixed $argv arguments for $value, and if $type === DA_VALUE_CALLABLE $argv must be an array, and if $type === DA_VALUE_RAW or DA_VALUE_NULL $argv will be ignored
	 * @param boolean $is_file whether this value is to be evaluated from a file
	 * @return boolean FALSE when $is_file but !file_exists
	 * @note be aware of safty issues because $value could be a file
	 * @note be careful when using NULL as parameters
	 */
	public function set($value = NULL, $argv = NULL, $is_file = FALSE)
	{
		$this->__value = $value;
		if (is_file)
		{
			if (!file_exists(TS_DIR . $value))
				return FALSE;
			$this->__type = TS_VALUE_FILE;
			$this->__argv = (array) $argv;
		}
		else if (is_callable($value))
		{
			$this->__type = TS_VALUE_CALLABLE;
			$this->__argv = (array) $argv;
		}
		else
			$this->__type = TS_VALUE_RAW;
		return TRUE;
	}
	/**
	 * @return mixed the evaluated value
	 */
	public function get()
	{
		switch ($this->__type)
		{
		case TS_VALUE_RAW:
			return $this->__value;
		case TS_VALUE_CALLABLE:
			return call_user_func_array($this->__value, array_merge($this->__argv, func_get_args()));
		case TS_VALUE_FILE:
			return ts_load($this->__value, array_merge($this->__argv, func_get_args()));
		}
	}
	/**
	 * the same as $this->set, except without return value
	 */
	public function __construct()
	{
		call_user_func_array(array($this, 'set'), func_get_args());
	}
};

/* End of /ts-libs/ts/value.php */
