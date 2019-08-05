<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Holidays.php
 *
 * PHP Version 5
 *
 * Copyright (c) 1997-2008 The PHP Group
 *
 * This source file is subject to version 2.0 of the PHP license,
 * that is bundled with this package in the file LICENSE, and is
 * available at through the world-wide-web at
 * http://www.php.net/license/2_02.txt.
 * If you did not receive a copy of the PHP license and are unable to
 * obtain it through the world-wide-web, please send a note to
 * license@php.net so we can mail you a copy immediately.
 *
 * Authors:   Carsten Lucke <luckec@tool-garage.de>
 *
 * CVS file id: $Id$
 *
 * @category Date
 * @package  Date_Holidays
 * @author   Carsten Lucke <luckec@tool-garage.de>
 * @author   Stephan Schmidt <schst@php.net>
 * @license  http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @version  CVS: $Id$
 * @link     http://pear.php.net/package/Date_Holidays
 */

require_once 'Date.php';

/**
 * Class that wraps a holiday's data
 */
require_once 'Holidays/Holiday.php';

/**
 * Driver baseclass
 */
require_once 'Holidays/Driver.php';

/**
 * could not find file of driver-class
 *
 * @access  public
 */
define('DATE_HOLIDAYS_ERROR_DRIVERFILE_NOT_FOUND', 1);

/**
 * invalid argument was passed to a method
 *
 * @access  public
 */
define('DATE_HOLIDAYS_ERROR_INVALID_ARGUMENT', 2);

/**
 * Driver directory does not exist
 *
 * @access  public
 */
define('DATE_HOLIDAYS_ERROR_MISSING_DRIVER_DIR', 3);

/**
 * Filter directory does not exist
 *
 * @access  public
 */
define('DATE_HOLIDAYS_ERROR_MISSING_FILTER_DIR', 4);

/**
 * class that helps you to locate holidays for a year
 *
 * @category Date
 * @package  Date_Holidays
 * @author   Carsten Lucke <luckec@tool-garage.de>
 * @author   Stephan Schmidt <schst@php.net>
 * @license  http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @version  CVS: $Id$
 * @link     http://pear.php.net/package/Date_Holidays
 * @abstract
 */
class Date_Holidays {
    /**
     * Constructor
     *
     * Use the Date_Holidays::factory() method to construct an object of
     * a certain driver
     *
     * @access   protected
     */
    function Date_Holidays() {
    }

    /**
     * Factory method that creates a driver-object
     *
     * @param string $driverId driver-name
     * @param string $year year
     * @param string $locale locale name
     * @param boolean $external external driver
     *
     * @static
     * @access   public
     * @return   object  Date_Holidays driver-object on success,
     *                   otherwise a PEAR_Error object
     * @throws   object PEAR_Error
     */
    public static function factory($driverId, $year = null, $locale = null, $external = false) {
        if (!isset($GLOBALS['_DATE_HOLIDAYS']['DIE_ON_MISSING_LOCALE'])) {
            Date_Holidays::staticSetProperty('DIE_ON_MISSING_LOCALE', true);
        }
        $driverId = basename($driverId);
        $driverClass = 'Date_Holidays_Driver_' . $driverId;
        if ($external) {
            $driverClass = $driverId;
        }

        if (!class_exists($driverClass)) {
            $driverFile = 'Holidays/Driver/' . $driverId . '.php';
            if ($external) {
                $driverFile = str_replace('_', '/', $driverClass) . '.php';
            }

            require_once $driverFile;

            if (!class_exists($driverClass)) {
                return 'Couldn\'t find file of the driver-class,  filename: ' . $driverFile;
            }
        }
        $driver = new $driverClass;

        if (is_null($year)) {
            $year = date('Y');
        }
        // sets internal var $_year and performs _buildHolidays()
        $res = $driver->setYear($year);
        if (Date_Holidays::isError($res)) {
            return $res;
        }

        if (is_null($locale)) {
            $locale = setlocale(LC_MESSAGES, 0);
            //encoding might be appended to the locale, For example en_IE.UTF-8
            //so ignore it
            $tmp = explode(".", $locale);
            $locale = $tmp[0];
        }
        $driver->setLocale($locale);
        return $driver;
    }

    /**
     * Factory method that creates a driver-object
     *
     * @param string $isoCode ISO3166 code identifying the driver
     * @param string $year year
     * @param string $locale locale name
     * @param boolean $external external driver
     *
     * @static
     * @access   public
     * @return   object  Date_Holidays driver-object on success, otherwise
     *                   a PEAR_Error object
     * @throws   object PEAR_Error
     */
    function factoryISO3166($isoCode,
                            $year = null,
                            $locale = null,
                            $external = false) {
        $driverDir = dirname(__FILE__) . '/Driver';

        if (!is_dir($driverDir)) {
            return 'Date_Holidays driver directory does not exist';
        }

        $driverMappings = [];
        $driverFiles = [];
        $dh = opendir($driverDir);
        while (false !== ($filename = readdir($dh))) {
            array_push($driverFiles, $filename);
        }

        foreach ($driverFiles as $driverFileName) {

            $file = dirname(__FILE__) . '/Driver/' . $driverFileName;
            if (!is_file($file)) {
                continue;
            }

            $driverId = str_replace('.php', '', $driverFileName);
            $driverClass = 'Date_Holidays_Driver_' . $driverId;
            $driverFilePath = $driverDir . '/' . $driverFileName;

            require_once $driverFilePath;
            if (!class_exists($driverClass)) {
                return 'Couldn\'t find file of the driver-class ' . $driverClass
                    . ',  filename: ' . $driverFilePath;
            }

            $isoCodes = call_user_func([
                $driverClass,
                DATE_HOLIDAYS_DRIVER_IDENTIFY_ISO3166_METHOD
            ]);

            foreach ($isoCodes as $code) {
                if (strtolower($code) === $isoCode) {
                    return Date_Holidays::factory($driverId,
                        $year,
                        $locale,
                        $external);
                }
            }
        }

        /*
         * If this line is reached the iso-code couldn't be mapped to
         * a driver-class and an error will be thrown.
         */
        return 'Couldn\'t find a driver for the given ISO 3166 code: ' . $isoCode;

    }

    /**
     * Returns a list of the installed drivers
     *
     * @param string $directory where the drivers are installed
     *
     * @access public
     * @static
     * @return array
     */
    function getInstalledDrivers($directory = null) {
        $drivers = [];
        if ($directory === null) {
            $directory = dirname(__FILE__) . '/Driver';
        }
        if (!file_exists($directory) || !is_dir($directory)) {
            return 'The driver directory "' . $directory . '" does not exist';
        }
        return Date_Holidays::_getModulesFromDir($directory);
    }

    /**
     * Returns a list of the installed filters
     *
     * @param string $directory where the filters are installed
     *
     * @access public
     * @static
     * @return array
     */
    function getInstalledFilters($directory = null) {
        $filters = [];
        if ($directory === null) {
            $directory = dirname(__FILE__) . '/Filter';
        }
        if (!file_exists($directory) || !is_dir($directory)) {
            return 'The filter directory "' . $directory . '" does not exist';
        }
        return Date_Holidays::_getModulesFromDir($directory);
    }

    /**
     * Fetch all modules from a directory and its subdirectories
     *
     * @param string $directory specified directory
     * @param string $prefix for the class names, will be used in recursive calls
     *
     * @static
     * @access protected
     * @return array modules
     */
    function _getModulesFromDir($directory, $prefix = '') {
        $modules = [];
        $d = dir($directory);
        while (false !== $moduleFile = $d->read()) {
            if ($moduleFile === '.' ||
                $moduleFile === '..' ||
                $moduleFile === 'CVS') {
                continue;
            }
            if (is_dir($directory . '/' . $moduleFile)) {
                $modules = array_merge($modules,
                    Date_Holidays::_getModulesFromDir($directory . '/' . $moduleFile,
                        $prefix . $moduleFile . '_'));
                continue;
            }
            $matches = [];
            if (preg_match('/(.*)\.php$/', $moduleFile, $matches)) {
                array_push($modules, [
                    'id'    => $prefix . $matches[1],
                    'title' => $prefix . $matches[1]
                ]);
            }
        }
        return $modules;
    }

    /**
     * Checks a variable to determine whether it represents an error object or not
     *
     * @param mixed $data variable to test
     * @param int $code if $data is an PEAR_Error object, return true
     *                    only if $code is a string and
     *                    $obj->getMessage() == $code or
     *                    $code is an integer and $obj->getCode() == $code
     *
     * @static
     * @access   public
     * @return   boolean true if $subject is an error object
     */
    public static function isError($data, $code = null) {
        if (!is_object($data)) {
            return false;
        }
        $errorClass = get_class($data);
        switch (strtolower($errorClass)) {
            case 'pear_error':
                return $data . ': ' . $code;
            case 'pear_errorstack':
                return $data->hasErrors();
        }
        return false;
    }

    /**
     * Checks whether errors occured
     *
     * @static
     * @access   public
     * @return   boolean true if errors occurred
     */
    public static function errorsOccurred() {
        return 'Error errorsOccurred';
    }

    /**
     * Returns the errors the error-stack contains
     *
     * @param boolean $purge true if the stall shall be purged
     *
     * @static
     * @access   public
     * @return   array   errors
     */
    public static function getErrors($purge = false) {
        return 'Error : getErrors';
    }

    /**
     * Set a property for the Date_Holidays drivers
     *
     * Available properties:
     * <pre>
     * DIE_ON_MISSING_LOCALE = boolean
     *   false: if no localized holiday-title is found an error will be returned
     *   true: if no localized holiday-title is found then the default
     *         translation (English) will be used
     * </pre>
     *
     * @param string $prop property
     * @param string $value property-value
     *
     * @access   public
     * @static
     * @return   void
     */
    public static function staticSetProperty($prop, $value) {
        if (!isset($GLOBALS['_DATE_HOLIDAYS'])) {
            $GLOBALS['_DATE_HOLIDAYS'] = [];
        }

        switch ($prop) {
            case 'DIE_ON_MISSING_LOCALE':
                if (is_bool($value)) {
                    $GLOBALS['_DATE_HOLIDAYS'][$prop] = $value;
                }
                break;
        }
    }

    /**
     * Returns an internal property value
     *
     * @param string $prop property-name
     *
     * @static
     * @access   public
     * @return   mixed   property value on success, otherwise null
     */
    function staticGetProperty($prop) {
        if (!isset($GLOBALS['_DATE_HOLIDAYS'])) {
            return null;
        }

        switch ($prop) {
            case 'DIE_ON_MISSING_LOCALE':
                if (isset($GLOBALS['_DATE_HOLIDAYS'][$prop])) {
                    return $GLOBALS['_DATE_HOLIDAYS'][$prop];
                }
        }

        return null;
    }
}

?>
