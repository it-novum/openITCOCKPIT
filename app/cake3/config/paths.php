<?php

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}


define('OLD_APP', __DIR__ . DS . '../../');

define('OLD_CONFIG', OLD_APP . 'Config');


/**
 * The full path to the directory which holds "src", WITHOUT a trailing DS.
 */
define('NEW_ROOT', dirname(__DIR__ . '../'));

/**
 * The actual directory name for the application directory. Normally
 * named 'src'.
 */
define('NEW_APP_DIR', 'src');

/**
 * Path to the application's directory.
 */
define('NEW_APP', NEW_ROOT . DS . NEW_APP_DIR . DS);

/**
 * Path to the config directory.
 */
define('NEW_CONFIG', NEW_ROOT . DS . 'config' . DS);

/**
 * File path to the webroot directory.
 *
 * To derive your webroot from your webserver change this to:
 *
 * `define('WWW_ROOT', rtrim($_SERVER['DOCUMENT_ROOT'], DS) . DS);`
 */
define('NEW_WWW_ROOT', NEW_ROOT . DS . 'webroot' . DS);

/**
 * Path to the tests directory.
 */
define('NEW_TESTS', NEW_ROOT . DS . 'tests' . DS);

/**
 * Path to the temporary files directory.
 */
define('NEW_TMP', NEW_ROOT . DS . 'tmp' . DS);

/**
 * Path to the logs directory.
 */
define('NEW_LOGS', NEW_ROOT . DS . 'logs' . DS);

/**
 * Path to the cache files directory. It can be shared between hosts in a multi-server setup.
 */
define('NEW_CACHE', NEW_TMP . 'cache' . DS);

/**
 * The absolute path to the "cake" directory, WITHOUT a trailing DS.
 *
 * CakePHP should always be installed with composer, so look there.
 */
define('CAKE_CORE_INCLUDE_PATH', ROOT . DS . 'vendor' . DS . 'cakephp' . DS . 'cakephp');

/**
 * Path to the cake directory.
 */
define('CORE_PATH', CAKE_CORE_INCLUDE_PATH . DS);
define('CAKE', CORE_PATH . 'src' . DS);