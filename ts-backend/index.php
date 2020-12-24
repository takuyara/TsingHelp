<?php
/**
 * Main Controller
 *
 * @package Tsinghelp
 */

/* initializes */
require 'ts-init.php';

/* gets configuration */
require 'ts-config.php';

/* disables all error reporting if not developing */
error_reporting(TS_DEV ? E_ALL | E_STRICT : 0);

/* imports core libraries */
require 'ts-libs.php';

/* loads modules */
require 'ts-mods.php';

/* connects to database */
require 'ts-db.php';

/* runs requested procedures */
TsRoute::run(TsRoute::uri());

/* End of /index.php */
