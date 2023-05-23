<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         3.0.0
 * @license       MIT License (https://opensource.org/licenses/mit-license.php)
 */

/*
 * Use the DS to separate the directories in other defines
 */
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

/*
 * These defines should only be edited if you have cake installed in
 * a directory layout other than the way it is distributed.
 * When using custom settings be sure to use the DS and do not add a trailing DS.
 */

/*
 * The full path to the directory which holds "src", WITHOUT a trailing DS.
 */
define('ROOT', dirname(__DIR__));

/*
 * The actual directory name for the application directory. Normally
 * named 'src'.
 */
define('APP_DIR', 'src');

/*
 * Path to the application's directory.
 */
define('APP', ROOT . DS . APP_DIR . DS);

/*
 * Path to the config directory.
 */
define('CONFIG', ROOT . DS . 'config' . DS);

/*
 * File path to the webroot directory.
 *
 * To derive your webroot from your webserver change this to:
 *
 * `define('WWW_ROOT', rtrim($_SERVER['DOCUMENT_ROOT'], DS) . DS);`
 */
if (!defined('WWW_ROOT')) {
    //@todo remove the if and always define the WWW_ROOT.
    //This is to load cake2 and cake4 at the same time.
    define('WWW_ROOT', ROOT . DS . 'webroot' . DS);
}

$isCli = PHP_SAPI === 'cli';

// This var determins if openITCOCKPIT is running inside a workhorse container like mod_gearman
// It yes, we use different path for logfiles and caching to avoid permission issues
$isWorkhorseContainer = filter_var(env('IS_WORKHORSE_CONTAINER', false), FILTER_VALIDATE_BOOLEAN);

/*
 * Path to the tests directory.
 */
define('TESTS', ROOT . DS . 'tests' . DS);

/*
 * Path to the temporary files directory.
 */
if ($isWorkhorseContainer === false) {
    // Default installation of openITCOCKPIT via apt, dnf or git
    // Also used if openITCOCKPIT is running inside a container like docker
    if ($isCli === false) {
        // www-data
        define('TMP', ROOT . DS . 'tmp' . DS);
    } else {
        // root or nagios
        if ($_SERVER['USER'] !== 'root') {
            //nagios user or so
            define('TMP', ROOT . DS . 'tmp' . DS . 'nagios' . DS);
        } else {
            //root user
            define('TMP', ROOT . DS . 'tmp' . DS . 'cli' . DS);
        }
    }
} else {
    // This openITCOCKPIT is running inside a workhorse container like mod_gearman
    // The primary job of this container is only to provide the openITCOCKPIT CLI backend to be able
    // to execute notification commands or the evc check plugin
    if ($isCli === false) {
        // www-data
        define('TMP', DS . 'tmp' . DS . 'openitcockpit' . DS . 'tmp'); // /tmp/openitcockpit/tmp/
    } else {
        // root or nagios
        if ($_SERVER['USER'] !== 'root') {
            //nagios user or so
            define('TMP', DS . 'tmp' . DS . 'openitcockpit' . DS . 'tmp' . DS . 'nagios' . DS); // /tmp/openitcockpit/tmp/nagios
        } else {
            //root user
            define('TMP', DS . 'tmp' . DS . 'openitcockpit' . DS . 'tmp' . DS . 'cli' . DS); // /tmp/openitcockpit/tmp/cli
        }
    }
}


/*
 * Path to the logs directory.
 */
if ($isWorkhorseContainer === false) {
    // Default installation of openITCOCKPIT via apt, dnf or git
    // Also used if openITCOCKPIT is running inside a container like docker
    if ($isCli === false) {
        //www-data
        define('LOGS', ROOT . DS . 'logs' . DS);
    } else {
        if ($_SERVER['USER'] !== 'root') {
            define('LOGS', ROOT . DS . 'logs' . DS . 'nagios' . DS);
        } else {
            define('LOGS', ROOT . DS . 'logs' . DS);
        }
    }
} else {
    // This openITCOCKPIT is running inside a workhorse container like mod_gearman
    // The primary job of this container is only to provide the openITCOCKPIT CLI backend to be able
    // to execute notification commands or the evc check plugin
    if ($isCli === false) {
        //www-data
        define('LOGS', DS . 'tmp' . DS . 'openitcockpit' . DS . 'logs' . DS); // /tmp/openitcockpit/logs/
    } else {
        if ($_SERVER['USER'] !== 'root') {
            define('LOGS', DS . 'tmp' . DS . 'openitcockpit' . DS . 'logs' . DS . 'nagios' . DS); // /tmp/openitcockpit/logs/nagios
        } else {
            define('LOGS', DS . 'tmp' . DS . 'openitcockpit' . DS . 'logs' . DS); // /tmp/openitcockpit/logs/
        }
    }
}

/*
 * Path to the cache files directory. It can be shared between hosts in a multi-server setup.
 */
if ($isWorkhorseContainer === false) {
    // Default installation of openITCOCKPIT via apt, dnf or git
    // Also used if openITCOCKPIT is running inside a container like docker
    if ($isCli === false) {
        //www-data
        define('CACHE', TMP . 'cache' . DS);
    } else {
        if ($_SERVER['USER'] !== 'root') {
            //nagios user or so
            define('CACHE', TMP . 'cache' . DS . 'nagios' . DS);
        } else {
            //root user
            define('CACHE', TMP . 'cache' . DS . 'cli' . DS);
        }
    }
}else{
    // This openITCOCKPIT is running inside a workhorse container like mod_gearman
    // The primary job of this container is only to provide the openITCOCKPIT CLI backend to be able
    // to execute notification commands or the evc check plugin
    if ($isCli === false) {
        //www-data
        define('CACHE', DS . 'tmp' . DS . 'openitcockpit' . DS . 'cache' . DS); // /tmp/openitcockpit/cache/
    } else {
        if ($_SERVER['USER'] !== 'root') {
            //nagios user or so
            define('CACHE', DS . 'tmp' . DS . 'openitcockpit' . DS . 'cache' . DS . 'nagios' . DS); // /tmp/openitcockpit/cache/nagios/
        } else {
            //root user
            define('CACHE', DS . 'tmp' . DS . 'openitcockpit' . DS . 'cache' . DS . 'cli' . DS); // /tmp/openitcockpit/cache/cli/
        }
    }
}

/**
 * Path to the resources directory.
 */
define('RESOURCES', ROOT . DS . 'resources' . DS);

/**
 * The absolute path to the "cake" directory, WITHOUT a trailing DS.
 *
 * CakePHP should always be installed with composer, so look there.
 */
define('CAKE_CORE_INCLUDE_PATH', ROOT . DS . 'vendor' . DS . 'cakephp' . DS . 'cakephp');

/*
 * Path to the cake directory.
 */
define('CORE_PATH', CAKE_CORE_INCLUDE_PATH . DS);
define('CAKE', CORE_PATH . 'src' . DS);
