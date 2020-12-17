<?php
/**
 * Initialization
 *
 * @package Tsinghelp
 */

/**
 * Cancels magic_quotes_gpc
 */
if (get_magic_quotes_gpc()) {
	$handler = function (&$val, $key) { $val = stripslashes($val); };
	array_walk_recursive($_GET, $handler);
	array_walk_recursive($_POST, $handler);
	array_walk_recursive($_COOKIE, $handler);
	array_walk_recursive($_REQUEST, $handler);
	unset($handler);
}

/**
 * Convenient line-break characters
 */
define('TS_LF', "\n");
define('TS_BR', '<br />');

/**
 * @const TS_URL_ROOT URL root without domain name
 */
$root = dirname($_SERVER['SCRIPT_NAME']);
if ($root === '/' || $root === '\\')
	$root = '';
define('TS_URI_ROOT', $root . '/');
unset($root);

/**
 * @const TS_DIR absolute path of this program
 */
define('TS_DIR', dirname(__FILE__));

/**
 * @const TS_DIR_LIBS  the relative path of common libraries
 * @const TS_DIR_MODS  the relative path of common modules
 * @const TS_DIR_FILES the relative path of common files
 */
define('TS_DIR_LIBS',  '/ts-libs');
define('TS_DIR_MODS',  '/ts-mods');
define('TS_DIR_FILES', '/ts-files');

/**
 * @const TS_HTTP_PROTOCOL default HTTP protocol
 */
define('TS_HTTP_PROTOCOL', isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');

/**
 * @param $__ts_mod the module to be loaded
 * @param $__ts_dir the relative path of the module
 * @return returns whatever the file returns
 * @note no security check for file names
 */
function ts_load($__ts_mod, $__ts_dir = TS_DIR_MODS) {
	require TS_DIR . '/ts-globals.php';
	return require TS_DIR . $__ts_dir . '/' . $__ts_mod . '.php';
}
/**
 * @param $__ts_lib the library to be required
 * @param $__ts_dir the relative path of the library
 * @return returns whatever the file returns
 * @note no security check for file names
 */
function ts_import($__ts_lib, $__ts_dir = TS_DIR_LIBS) {
	require TS_DIR . '/ts-globals.php';
	return require_once TS_DIR . $__ts_dir . '/' . $__ts_lib . '.php';
}

/* End of /ts-init.php */
