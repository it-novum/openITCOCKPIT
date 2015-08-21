<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Driver.php
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
 * @license  http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @version  CVS: $Id$
 * @link     http://pear.php.net/package/Date_Holidays
 */

/**
 * DriverClass and associated defines.
 *
 * @abstract
 * @category Date
 * @package  Date_Holidays
 * @author   Carsten Lucke <luckec@tool-garage.de>
 * @license  http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @version  CVS: $Id$
 * @link     http://pear.php.net/package/Date_Holidays
 */

/**
 * uses PEAR_Errorstack
 */

require_once 'Filter.php';
require_once 'Filter/Whitelist.php';
require_once 'Filter/Blacklist.php';

/**
 * invalid internal name
 *
 * @access  public
 */
define('DATE_HOLIDAYS_INVALID_INTERNAL_NAME', 51);

/**
 * title for a holiday is not available
 *
 * @access  public
 */
define('DATE_HOLIDAYS_TITLE_UNAVAILABLE', 52);

/**
 * date could not be converted into a PEAR::Date object
 *
 * date was neither a timestamp nor a string
 *
 * @access  public
 * @deprecated   will certainly be removed
 */
define('DATE_HOLIDAYS_INVALID_DATE', 53);

/**
 * string that represents a date has wrong format
 *
 * format must be YYYY-MM-DD
 *
 * @access  public
 * @deprecated   will certainly be removed
 */
define('DATE_HOLIDAYS_INVALID_DATE_FORMAT', 54);

/**
 * date for a holiday is not available
 *
 * @access  public
 */
define('DATE_HOLIDAYS_DATE_UNAVAILABLE', 55);

/**
 * language-file doesn't exist
 *
 * @access  public
 */
define('DATE_HOLIDAYS_LANGUAGEFILE_NOT_FOUND', 56);

/**
 * unable to read language-file
 *
 * @access  public
 */
define('DATE_HOLIDAYS_UNABLE_TO_READ_TRANSLATIONDATA', 57);

/**
 * Name of the static {@link Date_Holidays_Driver} method returning
 * a array of possible ISO3166 codes that identify itself.
 *
 * @access  public
 */
define('DATE_HOLIDAYS_DRIVER_IDENTIFY_ISO3166_METHOD', 'getISO3166Codes');

/**
 * class that helps you to locate holidays for a year
 *
 * @abstract
 * @category   Date
 * @package    Date_Holidays
 * @subpackage Driver
 * @author     Carsten Lucke <luckec@tool-garage.de>
 * @license    http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/Date_Holidays
 */
class Date_Holidays_Driver
{
	/**
	 * this driver's name
	 *
	 * @access   protected
	 * @var      string
	 */
	var $_driverName;

	/**
	 * locale setting for output
	 *
	 * @access   protected
	 * @var      string
	 */
	var $_locale;

	/**
	 * locales for which translations of holiday titles are available
	 *
	 * @access   private
	 * @var      array
	 */
	var $_availableLocales = array('C');

	/**
	 * object's current year
	 *
	 * @access   protected
	 * @var      int
	 */
	var $_year;

	/**
	 * internal names for the available holidays
	 *
	 * @access   protected
	 * @var      array
	 */
	var $_internalNames = array();

	/**
	 * dates of the available holidays
	 *
	 * @access   protected
	 * @var      array
	 */
	var $_dates = array();

	/**
	 * array of the available holidays indexed by date
	 *
	 * @access   protected
	 * @var      array
	 */
	var $_holidays = array();

	/**
	 * localized names of the available holidays
	 *
	 * @access   protected
	 * @var      array
	 */
	var $_titles = array();

	/**
	 * Array of holiday-properties indexed by internal-names and
	 * furthermore by locales.
	 *
	 * <code>
	 * $_holidayProperties = array(
	 *       'internalName1' =>  array(
	 *                               'de_DE' => array(),
	 *                               'en_US' => array(),
	 *                               'fr_FR' => array()
	 *                           )
	 *       'internalName2' =>  array(
	 *                               'de_DE' => array(),
	 *                               'en_US' => array(),
	 *                               'fr_FR' => array()
	 *                           )
	 * );
	 * </code>
	 */
	var $_holidayProperties = array();

	/**
	 * Constructor
	 *
	 * Use the Date_Holidays::factory() method to construct an object of a
	 * certain driver
	 *
	 * @access   protected
	 */
	function Date_Holidays_Driver()
	{
	}

	/**
	 * Method that returns an array containing the ISO3166 codes that may possibly
	 * identify a driver.
	 *
	 * @static
	 * @access public
	 * @return array possible ISO3166 codes
	 */
	function getISO3166Codes()
	{
		return array();
	}

	/**
	 * Sets the driver's current year
	 *
	 * Calling this method forces the object to rebuild the holidays
	 *
	 * @param int $year year
	 *
	 * @access   public
	 * @return   boolean true on success, otherwise a PEAR_ErrorStack object
	 * @throws   object PEAR_ErrorStack
	 * @uses     _buildHolidays()
	 */
	function setYear($year)
	{
		$this->_year = $year;
		return $this->_buildHolidays();
	}

	/**
	 * Returns the driver's current year
	 *
	 * @access   public
	 * @return   int     current year
	 */
	function getYear()
	{
		return $this->_year;
	}

	/**
	 * Build the internal arrays that contain data about the calculated holidays
	 *
	 * @abstract
	 * @access   protected
	 * @return   boolean true on success, otherwise a PEAR_ErrorStack object
	 * @throws   object PEAR_ErrorStack
	 */
	function _buildHolidays()
	{
	}

	/**
	 * Add a driver component
	 *
	 * @param object $driver Date_Holidays_Driver object
	 *
	 * @abstract
	 * @access public
	 * @return void
	 */
	function addDriver($driver)
	{
	}

	/**
	 * addTranslation
	 *
	 * Search for installed language files appropriate for the specified
	 * locale and add them to the driver
	 *
	 * @param string $locale locale setting to be used
	 *
	 * @access public
	 * @return boolean true on success, otherwise false
	 */
	function addTranslation($locale)
	{
		$data_dir = "/usr/share/php/data";
		$bestLocale = $this->_findBestLocale($locale);
		$matches = array();
		$loaded = false;

		if ($data_dir == '@'.'DATA-DIR'.'@') {
			$data_dir = dirname(dirname(dirname(__FILE__)));
			$stubdirs = array(
				"$data_dir/lang/{$this->_driverName}/",
				"$data_dir/lang/Christian/");
		} else {
			//Christian driver is exceptional...
			if ($this->_driverName == 'Christian') {
				$stubdir = "$data_dir/Date_Holidays/lang/Christian/";
			} else {
				$stubdir = "$data_dir/Date_Holidays_{$this->_driverName}/lang/{$this->_driverName}/";
				if (! is_dir($stubdir)) {
					$stubdir = $data_dir . "/Date_Holidays/lang/";
				}
			}
			$stubdirs = array(
				$stubdir,
				"$data_dir/Date_Holidays_{$this->_driverName}/lang/Christian/");
		}

		foreach ($stubdirs as $stubdir) {
			if (is_dir($stubdir)) {
				if ($dh = opendir($stubdir)) {
					while (($file = readdir($dh)) !== false) {
						if (strlen($locale) == 5) {
							if (((strncasecmp($file, $bestLocale, 5) == 0))
								|| (strncasecmp($file, $locale, 5) == 0)
							) {
								array_push($matches, $file);
							}
						}
						if (strlen($locale) == 2) {
							if (((strncasecmp($file, $bestLocale, 2) == 0))
								|| (strncasecmp($file, $locale, 2) == 0)
							) {
								array_push($matches, $file);
							}
						}
					}
					closedir($dh);
					$forget = array();
					sort($matches);
					foreach ($matches as $am) {
						if (strpos($am, ".ser") !== false) {
							$this->addCompiledTranslationFile($stubdir.$am, $locale);
							$loaded = true;
							array_push($forget, basename($am, ".ser") . ".xml");
						} else {
							if (!in_array($am, $forget)) {
								$this->addTranslationFile(
									$stubdir . $am,
									str_replace(".xml", "", $am)
								);
								$loaded = true;
							}
						}
					}
				}
			}
		}
		return $loaded;
	}

	/**
	 * Remove a driver component
	 *
	 * @param object $driver Date_Holidays_Driver driver-object
	 *
	 * @abstract
	 * @access   public
	 * @return   boolean true on success, otherwise a PEAR_Error object
	 * @throws   object PEAR_Error   DATE_HOLIDAYS_DRIVER_NOT_FOUND
	 */
	function removeDriver($driver)
	{
	}

	/**
	 * Returns the internal names of holidays that were calculated
	 *
	 * @access   public
	 * @return   array
	 */
	function getInternalHolidayNames()
	{
		return $this->_internalNames;
	}

	/**
	 * Returns localized titles of all holidays or those accepted by the filter
	 *
	 * @param Date_Holidays_Filter $filter filter-object (or an array !DEPRECATED!)
	 * @param string               $locale locale setting that shall be used
	 *                                     by this method
	 *
	 * @access   public
	 * @return   array   $filter array with localized holiday titles on success,
	 *                           otherwise a PEAR_Error object
	 * @throws   object PEAR_Error   DATE_HOLIDAYS_INVALID_INTERNAL_NAME
	 * @uses     getHolidayTitle()
	 */
	function getHolidayTitles($filter = null, $locale = null)
	{
		if (is_null($filter)) {
			$filter = new Date_Holidays_Filter_Blacklist(array());
		} elseif (is_array($filter)) {
			$filter = new Date_Holidays_Filter_Whitelist($filter);
		}

		$titles =   array();

		foreach ($this->_internalNames as $internalName) {
			if ($filter->accept($internalName)) {
				$title = $this->getHolidayTitle($internalName, $locale);
				if (Date_Holidays::isError($title)) {
					return $title;
				}
				$titles[$internalName] = $title;
			}
		}

		return $titles;
	}

	/**
	 * Returns localized title for a holiday
	 *
	 * @param string $internalName internal name for holiday
	 * @param string $locale       locale setting to be used by this method
	 *
	 * @access   public
	 * @return   string  title on success, otherwise a PEAR_Error object
	 * @throws   object PEAR_Error DATE_HOLIDAYS_INVALID_INTERNAL_NAME
	 * @throws   object PEAR_Error DATE_HOLIDAYS_TITLE_UNAVAILABLE
	 */
	function getHolidayTitle($internalName, $locale = null)
	{
		if (! in_array($internalName, $this->_internalNames)) {
			$msg = 'Invalid internal name: ' . $internalName;
			return Date_Holidays::raiseError(DATE_HOLIDAYS_INVALID_INTERNAL_NAME,
											 $msg);

		}

		if (is_null($locale)) {
			$locale = $this->_findBestLocale($this->_locale);
		} else {
			$locale = $this->_findBestLocale($locale);
		}

		if (! isset($this->_titles[$locale][$internalName])) {
			if (Date_Holidays::staticGetProperty('DIE_ON_MISSING_LOCALE')) {
				$err = DATE_HOLIDAYS_TITLE_UNAVAILABLE;
				$msg = 'The internal name (' . $internalName . ') ' .
					   'for the holiday was correct but no ' .
					   'localized title could be found';
				return Date_Holidays::raiseError($err, $msg);
			}
		}

		if (isset($this->_titles[$locale][$internalName])) {
			return $this->_titles[$locale][$internalName];
		} else {
			return $this->_titles['C'][$internalName];
		}
	}


	/**
	 * Returns the localized properties of a holiday. If no properties have
	 * been stored an empty array will be returned.
	 *
	 * @param string $internalName internal name for holiday
	 * @param string $locale       locale setting that shall be used by this method
	 *
	 * @access   public
	 * @return   array   array of properties on success, otherwise
	 *                   a PEAR_Error object
	 * @throws   object PEAR_Error   DATE_HOLIDAYS_INVALID_INTERNAL_NAME
	 */
	function getHolidayProperties($internalName, $locale = null)
	{
		if (! in_array($internalName, $this->_internalNames)) {
			$msg = 'Invalid internal name: ' . $internalName;
			return Date_Holidays::raiseError(DATE_HOLIDAYS_INVALID_INTERNAL_NAME,
											 $msg);
		}

		if (is_null($locale)) {
			$locale =   $this->_findBestLocale($this->_locale);
		} else {
			$locale =   $this->_findBestLocale($locale);
		}


		$properties = array();
		if (isset($this->_holidayProperties[$internalName][$locale])) {
			$properties = $this->_holidayProperties[$internalName][$locale];
		}
		return $properties;
	}


	/**
	 * Returns all holidays that the driver knows.
	 *
	 * You can limit the holidays by passing a filter, then only those
	 * holidays accepted by the filter will be returned.
	 *
	 * Return format:
	 * <pre>
	 *   array(
	 *       'easter'        =>  object of type Date_Holidays_Holiday,
	 *       'eastermonday'  =>  object of type Date_Holidays_Holiday,
	 *       ...
	 *   )
	 * </pre>
	 *
	 * @param Date_Holidays_Filter $filter filter-object
	 *                                     (or an array !DEPRECATED!)
	 * @param string               $locale locale setting that shall be used
	 *                                      by this method
	 *
	 * @access   public
	 * @return   array   numeric array containing objects of
	 *                   Date_Holidays_Holiday on success, otherwise a
	 *                   PEAR_Error object
	 * @throws   object PEAR_Error   DATE_HOLIDAYS_INVALID_INTERNAL_NAME
	 * @see      getHoliday()
	 */
	function getHolidays($filter = null, $locale = null)
	{
		if (is_null($filter)) {
			$filter = new Date_Holidays_Filter_Blacklist(array());
		} elseif (is_array($filter)) {
			$filter = new Date_Holidays_Filter_Whitelist($filter);
		}

		if (is_null($locale)) {
			$locale = $this->_locale;
		}

		$holidays = array();

		foreach ($this->_internalNames as $internalName) {
			if ($filter->accept($internalName)) {
				// no need to check for valid internal-name, will be
				// done by #getHoliday()
				$holidays[$internalName] = $this->getHoliday($internalName,
															 $locale);
			}
		}

		return $holidays;
	}

	/**
	 * Returns the specified holiday
	 *
	 * Return format:
	 * <pre>
	 *   array(
	 *       'title' =>  'Easter Sunday'
	 *       'date'  =>  '2004-04-11'
	 *   )
	 * </pre>
	 *
	 * @param string $internalName internal name of the holiday
	 * @param string $locale       locale setting that shall be used
	 *                              by this method
	 *
	 * @access   public
	 * @return   object Date_Holidays_Holiday holiday's information on
	 *                                         success, otherwise a PEAR_Error
	 *                                         object
	 * @throws   object PEAR_Error       DATE_HOLIDAYS_INVALID_INTERNAL_NAME
	 * @uses     getHolidayTitle()
	 * @uses     getHolidayDate()
	 */
	function getHoliday($internalName, $locale = null)
	{
		if (! in_array($internalName, $this->_internalNames)) {
			return Date_Holidays::raiseError(DATE_HOLIDAYS_INVALID_INTERNAL_NAME,
				'Invalid internal name: ' . $internalName);
		}
		if (is_null($locale)) {
			$locale = $this->_locale;
		}

		$title = $this->getHolidayTitle($internalName, $locale);
		if (Date_Holidays::isError($title)) {
			return $title;
		}
		$date = $this->getHolidayDate($internalName);
		if (Date_Holidays::isError($date)) {
			return $date;
		}
		$properties = $this->getHolidayProperties($internalName, $locale);
		if (Date_Holidays::isError($properties)) {
			return $properties;
		}

		$holiday = new Date_Holidays_Holiday($internalName,
											 $title,
											 $date,
											 $properties);
		return $holiday;
	}

	/**
	 * Determines whether a date represents a holiday or not
	 *
	 * @param mixed                $date   a timestamp, string or PEAR::Date object
	 * @param Date_Holidays_Filter $filter filter-object (or an array !DEPRECATED!)
	 *
	 * @access   public
	 * @return   boolean true if date represents a holiday, otherwise false
	 * @throws   object PEAR_Error   DATE_HOLIDAYS_INVALID_DATE_FORMAT
	 * @throws   object PEAR_Error   DATE_HOLIDAYS_INVALID_DATE
	 */
	function isHoliday($date, $filter = null)
	{
		if (! is_a($date, 'Date')) {
			$date = $this->_convertDate($date);
			if (Date_Holidays::isError($date)) {
				return $date;
			}
		}

		//rebuild internal array of holidays if required.
		$compare_year = $date->getYear();
		$this_year = $this->getYear();
		if ($this_year !== $compare_year) {
			$this->setYear($compare_year);
		}

		if (is_null($filter)) {
			$filter = new Date_Holidays_Filter_Blacklist(array());
		} elseif (is_array($filter)) {
			$filter = new Date_Holidays_Filter_Whitelist($filter);
		}

		foreach (array_keys($this->_dates) as $internalName) {
			if ($filter->accept($internalName)) {
				if (Date_Holidays_Driver::dateSloppyCompare($date,
										  $this->_dates[$internalName]) != 0) {
					continue;
				}
				$this->setYear($this_year);
				return true;
			}
		}
		$this->setYear($this_year);
		return false;
	}

	/**
	 * Returns a <code>Date_Holidays_Holiday</code> object, if any was found,
	 * matching the specified date.
	 *
	 * Normally the method will return the object of the first holiday matching
	 * the date. If you want the method to continue searching holidays for the
	 * specified date, set the 4th param to true.
	 *
	 * If multiple holidays match your date, the return value will be an array
	 * containing a number of <code>Date_Holidays_Holiday</code> items.
	 *
	 * @param mixed   $date     date (timestamp | string | PEAR::Date object)
	 * @param string  $locale   locale setting that shall be used by this method
	 * @param boolean $multiple if true, continue searching holidays for
	 *                           specified date
	 *
	 * @access   public
	 * @return   object  object of type Date_Holidays_Holiday on success
	 *                   (numeric array of those on multiple search),
	 *                   if no holiday was found, matching this date,
	 *                   null is returned
	 * @throws   object PEAR_Error   DATE_HOLIDAYS_INVALID_DATE_FORMAT
	 * @throws   object PEAR_Error   DATE_HOLIDAYS_INVALID_DATE
	 * @uses     getHoliday()
	 * @uses     getHolidayTitle()
	 * @see      getHoliday()
	 **/
	function getHolidayForDate($date, $locale = null, $multiple = false)
	{
		if (!is_a($date, 'Date')) {
			$date = $this->_convertDate($date);
			if (Date_Holidays::isError($date)) {
				return $date;
			}
		}

		if ($date->getYear() != $this->_year) {
			return null;
		}

		$isodate = mktime(0,
						  0,
						  0,
						  $date->getMonth(),
						  $date->getDay(),
						  $date->getYear());
		unset($date);
		if (is_null($locale)) {
			$locale = $this->_locale;
		}
		if (array_key_exists($isodate, $this->_holidays)) {
			if (!$multiple) {
				//get only the first feast for this day
				$internalName = $this->_holidays[$isodate][0];
				$result       = $this->getHoliday($internalName, $locale);
				return Date_Holidays::isError($result) ? null : $result;
			}
			// array that collects data, if multiple searching is done
			$data = array();
			foreach ($this->_holidays[$isodate] as $internalName) {
				$result = $this->getHoliday($internalName, $locale);
				if (Date_Holidays::isError($result)) {
					continue;
				}
				$data[] = $result;
			}
			return $data;
		}
		return null;
	}

	/**
	 * Returns an array containing a number of
	 * <code>Date_Holidays_Holiday</code> items.
	 *
	 * If no items have been found the returned array will be empty.
	 *
	 * @param mixed                $start  date: timestamp, string or PEAR::Date
	 * @param mixed                $end    date: timestamp, string or PEAR::Date
	 * @param Date_Holidays_Filter $filter filter-object (or
	 *                                      an array !DEPRECATED!)
	 * @param string               $locale locale setting that shall be used
	 *                                      by this method
	 *
	 * @access   public
	 * @throws   object PEAR_Error   DATE_HOLIDAYS_INVALID_DATE_FORMAT
	 * @throws   object PEAR_Error   DATE_HOLIDAYS_INVALID_DATE
	 * @return   array   an array containing a number
	 *                   of <code>Date_Holidays_Holiday</code> items
	 */
	function getHolidaysForDatespan($start, $end, $filter = null, $locale = null)
	{
		if (is_null($filter)) {
			$filter = new Date_Holidays_Filter_Blacklist(array());
		} elseif (is_array($filter)) {
			$filter = new Date_Holidays_Filter_Whitelist($filter);
		}

		if (!is_a($start, 'Date')) {
			$start = $this->_convertDate($start);
			if (Date_Holidays::isError($start)) {
				return $start;
			}
		}
		if (!is_a($end, 'Date')) {
			$end = $this->_convertDate($end);
			if (Date_Holidays::isError($end)) {
				return $end;
			}
		}

		$isodateStart = mktime(0,
							   0,
							   0,
							   $start->getMonth(),
							   $start->getDay(),
							   $start->getYear());
		unset($start);
		$isodateEnd = mktime(0,
							 0,
							 0,
							 $end->getMonth(),
							 $end->getDay(),
							 $end->getYear());
		unset($end);
		if (is_null($locale)) {
			$locale = $this->_locale;
		}

		$internalNames = array();

		foreach ($this->_holidays as $isoDateTS => $arHolidays) {
			if ($isoDateTS >= $isodateStart && $isoDateTS <= $isodateEnd) {
				$internalNames = array_merge($internalNames, $arHolidays);
			}
		}

		$retval = array();
		foreach ($internalNames as $internalName) {
			if ($filter->accept($internalName)) {
				$retval[] = $this->getHoliday($internalName, $locale);
			}
		}
		return $retval;

	}

	/**
	 * Converts timestamp or date-string into da PEAR::Date object
	 *
	 * @param mixed $date date
	 *
	 * @static
	 * @access   private
	 * @return   object PEAR_Date
	 * @throws   object PEAR_Error   DATE_HOLIDAYS_INVALID_DATE_FORMAT
	 * @throws   object PEAR_Error   DATE_HOLIDAYS_INVALID_DATE
	 */
	function _convertDate($date)
	{
		if (is_string($date)) {
			if (! preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}/', $date)) {
				return Date_Holidays::raiseError(DATE_HOLIDAYS_INVALID_DATE_FORMAT,
					'Date-string has wrong format (must be YYYY-MM-DD)');
			}
			$date = new Date($date);
			return $date;
		}

		if (is_int($date)) {
			$date = new Date(date('Y-m-d', $date));
			return $date;
		}

		return Date_Holidays::raiseError(DATE_HOLIDAYS_INVALID_DATE,
			'The date you specified is invalid');
	}

	/**
	 * Adds all holidays in the array to the driver's internal list of holidays.
	 *
	 * Format of the array:
	 * <pre>
	 *   array(
	 *       'newYearsDay'   => array(
	 *           'date'          => '01-01',
	 *           'title'         => 'New Year\'s Day',
	 *           'translations'  => array(
	 *               'de_DE' =>  'Neujahr',
	 *               'en_EN' =>  'New Year\'s Day'
	 *           )
	 *       ),
	 *       'valentinesDay' => array(
	 *           ...
	 *       )
	 *   );
	 * </pre>
	 *
	 * @param array $holidays static holidays' data
	 *
	 * @access   protected
	 * @uses     _addHoliday()
	 * @return   void
	 */
	function _addStaticHolidays($holidays)
	{
		foreach ($holidays as $internalName => $holiday) {
			// add the holiday's basic data
			$this->_addHoliday($internalName,
							   $this->_year . '-' . $holiday['date'],
							   $holiday['title']);
		}
	}

	/**
	 * Adds a holiday to the driver's holidays
	 *
	 * @param string $internalName internal name - must not contain characters
	 *                              that aren't allowed as variable-names
	 * @param mixed  $date         date (timestamp | string | PEAR::Date object)
	 * @param string $title        holiday title
	 *
	 * @access   protected
	 * @return   void
	 */
	function _addHoliday($internalName, $date, $title)
	{
		if (! is_a($date, 'Date')) {
			$date = new Date($date);
		}

		$this->_dates[$internalName]       = $date;
		$this->_titles['C'][$internalName] = $title;
		$isodate                           = mktime(0, 0, 0,
													$date->getMonth(),
													$date->getDay(),
													$date->getYear());
		if (!isset($this->_holidays[$isodate])) {
			$this->_holidays[$isodate] = array();
		}
		array_push($this->_holidays[$isodate], $internalName);
		array_push($this->_internalNames, $internalName);
	}

	/**
	 * Add a localized translation for a holiday's title. Overwrites existing data.
	 *
	 * @param string $internalName internal name of an existing holiday
	 * @param string $locale       locale setting that shall be used by this method
	 * @param string $title        title
	 *
	 * @access   protected
	 * @return   true on success, otherwise a PEAR_Error object
	 * @throws   object PEAR_Error       DATE_HOLIDAYS_INVALID_INTERNAL_NAME
	 */
	function _addTranslationForHoliday($internalName, $locale, $title)
	{
		if (! in_array($internalName, $this->_internalNames)) {
			$msg = 'Couldn\'t add translation (' . $locale . ') ' .
				   'for holiday with this internal name: ' . $internalName;
			return Date_Holidays::raiseError(DATE_HOLIDAYS_INVALID_INTERNAL_NAME,
											 $msg);
		}

		if (! in_array($locale, $this->_availableLocales)) {
			array_push($this->_availableLocales, $locale);
		}
		$this->_titles[$locale][$internalName] = $title;
		return true;
	}

	/**
	 * Adds a localized (regrading translation etc.) string-property for a holiday.
	 * Overwrites existing data.
	 *
	 * @param string $internalName internal-name
	 * @param string $locale       locale-setting
	 * @param string $propId       property-identifier
	 * @param mixed  $propVal      property-value
	 *
	 * @access   public
	 * @return   boolean true on success, false otherwise
	 * @throws   PEAR_ErrorStack if internal-name does not exist
	 */
	function _addStringPropertyForHoliday($internalName, $locale, $propId, $propVal)
	{
		if (! in_array($internalName, $this->_internalNames)) {
			$msg = 'Couldn\'t add property (locale: ' . $locale . ') '.
				   'for holiday with this internal name: ' . $internalName;
			return Date_Holidays::raiseError(DATE_HOLIDAYS_INVALID_INTERNAL_NAME,
											 $msg);
		}

		if (!isset($this->_holidayProperties[$internalName]) ||
				!is_array($this->_holidayProperties[$internalName])) {

			$this->_holidayProperties[$internalName] = array();
		}

		if (! isset($this->_holidayProperties[$internalName][$locale]) ||
				!is_array($this->_holidayProperties[$internalName][$locale])) {

			$this->_holidayProperties[$internalName][$locale] = array();
		}

		$this->_holidayProperties[$internalName][$locale][$propId] = $propVal;
		return true;
	}

	/**
	 * Adds a arbitrary number of localized string-properties for the
	 * specified holiday.
	 *
	 * @param string $internalName internal-name
	 * @param string $locale       locale-setting
	 * @param array  $properties   associative array: array(propId1 => val1,...)
	 *
	 * @access   public
	 * @return   boolean true on success, false otherwise
	 * @throws   PEAR_ErrorStack if internal-name does not exist
	 */
	function _addStringPropertiesForHoliday($internalName, $locale, $properties)
	{
		foreach ($properties as $propId => $propValue) {
			return $this->_addStringPropertyForHoliday($internalName,
													   $locale,
													   $propId,
													   $propValue);
		}

		return true;
	}

	/**
	 * Add a language-file's content
	 *
	 * The language-file's content will be parsed and translations,
	 * properties, etc. for holidays will be made available with the specified
	 * locale.
	 *
	 * @param string $file   filename of the language file
	 * @param string $locale locale-code of the translation
	 *
	 * @access   public
	 * @return   boolean true on success, otherwise a PEAR_ErrorStack object
	 * @throws   object PEAR_Errorstack
	 */
	function addTranslationFile($file, $locale)
	{
		if (! file_exists($file)) {
			return 'Language-file not found: ' . $file;
		}

		// unserialize the document
		$document = simplexml_load_file($file);

		$content = array();
		$content['holidays'] = array();
		$content['holidays']['holiday'] = array();

		$nodes = $document->xpath('//holiday');
		foreach ($nodes as $node) {
			$content['holidays']['holiday'][] = (array)$node;
		}

		return $this->_addTranslationData($content, $locale);
	}

	/**
	 * Add a compiled language-file's content
	 *
	 * The language-file's content will be unserialized and translations,
	 * properties, etc. for holidays will be made available with the
	 * specified locale.
	 *
	 * @param string $file   filename of the compiled language file
	 * @param string $locale locale-code of the translation
	 *
	 * @access   public
	 * @return   boolean true on success, otherwise a PEAR_ErrorStack object
	 * @throws   object PEAR_Errorstack
	 */
	function addCompiledTranslationFile($file, $locale)
	{
		if (! file_exists($file)) {
			return 'Language-file not found: ' . $file;
		}

		$content = file_get_contents($file);
		if ($content === false) {
			return false;
		}
		$data = unserialize($content);
		if ($data === false) {
			$e   = DATE_HOLIDAYS_UNABLE_TO_READ_TRANSLATIONDATA;
			$msg = "Unable to read translation-data - file maybe damaged: $file";
			return Date_Holidays::raiseError($e, $msg);
		}
		return $this->_addTranslationData($data, $locale);
	}

	/**
	 * Add a language-file's content. Translations, properties, etc. for
	 * holidays will be made available with the specified locale.
	 *
	 * @param array  $data   translated data
	 * @param string $locale locale-code of the translation
	 *
	 * @access   public
	 * @return   boolean true on success, otherwise a PEAR_ErrorStack object
	 * @throws   object PEAR_Errorstack
	 */
	function _addTranslationData($data, $locale)
	{
		foreach ($data['holidays']['holiday'] as $holiday) {
			$this->_addTranslationForHoliday($holiday['internal-name'],
											 $locale,
											 $holiday['translation']);

			if (isset($holiday['properties']) && is_array($holiday['properties'])) {
				foreach ($holiday['properties'] as $propId => $propVal) {
					$this->_addStringPropertyForHoliday($holiday['internal-name'],
														$locale,
														$propId,
														$propVal);
				}
			}

		}

		if (Date_Holidays::errorsOccurred()) {
			return '_addTranslationData';
		}

		return true;
	}

	/**
	 * Remove a holiday from internal storage
	 *
	 * This method should be used within driver classes to unset holidays that
	 * were inherited from parent-drivers
	 *
	 * @param $string $internalName internal name
	 *
	 * @access   protected
	 * @return   boolean     true on success, otherwise a PEAR_Error object
	 * @throws   object PEAR_Error   DATE_HOLIDAYS_INVALID_INTERNAL_NAME
	 */
	function _removeHoliday($internalName)
	{
		if (! in_array($internalName, $this->_internalNames)) {
			$msg = "Couldn't remove holiday with this internal name: $internalName";
			return Date_Holidays::raiseError(DATE_HOLIDAYS_INVALID_INTERNAL_NAME,
											 $msg);
		}

		if (isset($this->_dates[$internalName])) {
			unset($this->_dates[$internalName]);
		}
		$locales = array_keys($this->_titles);
		foreach ($locales as $locale) {
			if (isset($this->_titles[$locale][$internalName])) {
				unset($this->_titles[$locale][$internalName]);
			}
		}
		$index = array_search($internalName, $this->_internalNames);
		if (! is_null($index)) {
			unset($this->_internalNames[$index]);
		}
		return true;
	}

	/**
	 * Finds the best internally available locale for the specified one
	 *
	 * @param string $locale locale
	 *
	 * @access   protected
	 * @return   string  best locale available
	 */
	function _findBestLocale($locale)
	{
		/* exact locale is available */
		if (in_array($locale, $this->_availableLocales)) {
			return $locale;
		}

		/* first two letter are equal */
		foreach ($this->_availableLocales as $aLocale) {
			if (strncasecmp($aLocale, $locale, 2) == 0) {
				return $aLocale;
			}
		}

		/* no appropriate locale available, will use driver's internal locale */
		return 'C';
	}

	/**
	 * Returns date of a holiday
	 *
	 * @param string $internalName internal name for holiday
	 *
	 * @access   public
	 * @return   object Date             date of holiday as PEAR::Date object
	 *                                   on success, otherwise a PEAR_Error object
	 * @throws   object PEAR_Error       DATE_HOLIDAYS_DATE_UNAVAILABLE
	 * @throws   object PEAR_Error       DATE_HOLIDAYS_INVALID_INTERNAL_NAME
	 */
	function getHolidayDate($internalName)
	{
		if (! in_array($internalName, $this->_internalNames)) {
			$msg = 'Invalid internal name: ' . $internalName;
			return Date_Holidays::raiseError(DATE_HOLIDAYS_INVALID_INTERNAL_NAME,
											 $msg);
		}

		if (! isset($this->_dates[$internalName])) {
			$msg = 'Date for holiday with internal name ' .
				   $internalName . ' is not available';
			return Date_Holidays::raiseError(DATE_HOLIDAYS_DATE_UNAVAILABLE, $msg);
		}

		return $this->_dates[$internalName];
	}

	/**
	 * Returns dates of all holidays or those accepted by the applied filter.
	 *
	 * Structure of the returned array:
	 * <pre>
	 * array(
	 *   'internalNameFoo' => object of type date,
	 *   'internalNameBar' => object of type date
	 * )
	 * </pre>
	 *
	 * @param Date_Holidays_Filter $filter filter-object (or an array !DEPRECATED!)
	 *
	 * @access   public
	 * @return   array with holidays' dates on success, otherwise a PEAR_Error object
	 * @throws   object PEAR_Error   DATE_HOLIDAYS_INVALID_INTERNAL_NAME
	 * @uses     getHolidayDate()
	 */
	function getHolidayDates($filter = null)
	{
		if (is_null($filter)) {
			$filter = new Date_Holidays_Filter_Blacklist(array());
		} elseif (is_array($filter)) {
			$filter = new Date_Holidays_Filter_Whitelist($filter);
		}

		$dates = array();

		foreach ($this->_internalNames as $internalName) {
			if ($filter->accept($internalName)) {
				$date = $this->getHolidayDate($internalName);
				if (Date_Holidays::isError($date)) {
					return $date;
				}
				$dates[$internalName] = $this->getHolidayDate($internalName);
			}
		}
		return $dates;
	}

	/**
	 * Sets the driver's locale
	 *
	 * @param string $locale locale
	 *
	 * @access   public
	 * @return   void
	 */
	function setLocale($locale)
	{
		$this->_locale = $locale;
		//if possible, load the translation files for this locale
		$this->addTranslation($locale);
	}

	/**
	 * Sloppily compares two date objects (only year, month and day are compared).
	 * Does not take the date's timezone into account.
	 *
	 * @param Date $d1 a date object
	 * @param Date $d2 another date object
	 *
	 * @static
	 * @access private
	 * @return int 0 if the dates are equal,
	 *             -1 if d1 is before d2,
	 *             1 if d1 is after d2
	 */
	function dateSloppyCompare($d1, $d2)
	{
		$d1->setTZ(new Date_TimeZone('UTC'));
		$d2->setTZ(new Date_TimeZone('UTC'));
		$days1 = Date_Calc::dateToDays($d1->day, $d1->month, $d1->year);
		$days2 = Date_Calc::dateToDays($d2->day, $d2->month, $d2->year);
		if ($days1 < $days2) return -1;
		if ($days1 > $days2) return 1;
		return 0;
	}
	/**
	 * Find the date of the first monday in the specified year of the current year.
	 *
	 * @param integer $month month
	 *
	 * @access   private
	 * @return   object Date date of first monday in specified month.
	 */
	function _calcFirstMonday($month)
	{
		$month = sprintf("%02d", $month);
		$date = new Date($this->_year . "-$month-01");
		while ($date->getDayOfWeek() != 1) {
			$date = $date->getNextDay();
		}
		return ($date);
	}
	/**
	 * Find the date of the last monday in the specified year of the current year.
	 *
	 * @param integer $month month
	 *
	 * @access   private
	 * @return   object Date date of last monday in specified month.
	 */
	function _calcLastMonday($month)
	{
		//work backwards from the first day of the next month.
		$month = sprintf("%02d", $month);
		$nm = ((int) $month ) + 1;
		if ($nm > 12) {
			$nm = 1;
		}
		$nm = sprintf("%02d", $nm);

		$date = new Date($this->_year . "-$nm-01");
		$date = $date->getPrevDay();
		while ($date->getDayOfWeek() != 1) {
			$date = $date->getPrevDay();
		}
		return ($date);
	}
	/**
	 * Calculate Nth monday in a month
	 *
	 * @param int $month    month
	 * @param int $position position
	 *
	 * @access   private
	 * @return   object Date date
	 */
	function _calcNthMondayInMonth($month, $position)
	{
		if ($position  == 1) {
			$startday = '01';
		} elseif ($position == 2) {
			$startday = '08';
		} elseif ($position == 3) {
			$startday = '15';
		} elseif ($position == 4) {
			$startday = '22';
		} elseif ($position == 5) {
			$startday = '29';
		}
		$month = sprintf("%02d", $month);

		$date = new Date($this->_year . '-' . $month . '-' . $startday);
		while ($date->getDayOfWeek() != 1) {
			$date = $date->getNextDay();
		}
		return $date;
	}

	/**
	 * Calculate Nth day of the week in a month
	 *
	 * @param int $position position
	 * @param int $weekday  day of the week starting from 1 == sunday
	 * @param int $month    month
	 *
	 * @access   private
	 * @return   object Date date
	 */
	function _calcNthWeekDayInMonth($position, $weekday, $month)
	{
		if ($position  == 1) {
			$startday = '01';
		} elseif ($position == 2) {
			$startday = '08';
		} elseif ($position == 3) {
			$startday = '15';
		} elseif ($position == 4) {
			$startday = '22';
		} elseif ($position == 5) {
			$startday = '29';
		}
		$month = sprintf("%02d", $month);

		$date = new Date($this->_year . '-' . $month . '-' . $startday);
		while ($date->getDayOfWeek() != $weekday) {
			$date = $date->getNextDay();
		}
		return $date;
	}

	/**
	 * Converts the date to the specified no of days from the given date
	 *
	 * To subtract days use a negative value for the '$pn_days' parameter
	 *
	 * @param Date $date Date object
	 * @param int $pn_days days to add
	 *
	 * @return   Date
	 * @access   protected
	 */
	function _addDays($date, $pn_days)
	{
		$new_date = new Date($date);
		list($new_date->year, $new_date->month, $new_date->day) =
			explode(' ',
					Date_Calc::daysToDate(Date_Calc::dateToDays($date->day,
																$date->month,
																$date->year) +
										  $pn_days,
										  '%Y %m %d'));
		if (isset($new_date->on_standardyear)) {
			$new_date->on_standardyear = $new_date->year;
			$new_date->on_standardmonth = $new_date->month;
			$new_date->on_standardday = $new_date->day;
		}
		return $new_date;
	}
}
