<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Filter.php
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
 * @category Date
 * @package  Date_Holidays
 * @author   Carsten Lucke <luckec@tool-garage.de>
 * @license  http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @version  CVS: $Id$
 * @link     http://pear.php.net/package/Date_Holidays
 */

/**
 * Baseclass for a holiday-filter.
 *
 * @abstract
 * @category Date
 * @package  Date_Holidays
 * @author   Carsten Lucke <luckec@tool-garage.de>
 * @license  http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @version  CVS: $Id$
 * @link     http://pear.php.net/package/Date_Holidays
 */
class Date_Holidays_Filter
{
	/**
	 * Internal names of holidays that are subject to the filter.
	 *
	 * @access   private
	 * @var      array
	 */
	var $_internalNames = array();

	/**
	 * Constructor.
	 *
	 * Creates a new filter that knows, which holidays the
	 * calculating driver shall be restricted to.
	 *
	 * @param array $holidays numerical array that contains internal
	 *                          names of holidays
	 */
	function __construct($holidays)
	{
		if (! is_array($holidays)) {
			$holidays = array();
		}

		$this->_internalNames = $holidays;
	}

	/**
	 * Constructor.
	 *
	 * Creates a new filter that knows, which holidays the
	 * calculating driver shall be restricted to.
	 *
	 * @param array $holidays numerical array that contains internal
	 *                          names of holidays
	 */
	function Date_Holidays_Filter($holidays)
	{
		$this->__construct($holidays);
	}

	/**
	 * Returns the internal names of holidays that are subject to the filter.
	 *
	 * @return array
	 */
	function getFilteredHolidays()
	{
		return $this->_internalNames;
	}

	/**
	 * Sets the internal names of holidays that are subject to the filter.
	 *
	 * @param array $holidays internal holiday-names
	 *
	 * @return void
	 */
	function setFilteredHolidays($holidays)
	{
		if (! is_array($holidays)) {
			$holidays = array();
		}

		$this->_internalNames = $holidays;
	}

	/**
	 * Lets the filter decide whether a holiday shall be processed or not.
	 *
	 * @param string $internalName a holidays' internal name
	 *
	 * @abstract
	 * @return   boolean true, if a holidays shall be processed, false otherwise
	 */
	function accept($internalName)
	{
	}
}
