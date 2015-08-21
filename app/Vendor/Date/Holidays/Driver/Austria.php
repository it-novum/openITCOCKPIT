<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Driver for holidays in Austria
 *
 * PHP Version 5
 *
 * Copyright (c) 1997-2008 The PHP Group
 *
 * This source file is subject to version 3.0 of the PHP license,
 * that is bundled with this package in the file LICENSE, and is
 * available at through the world-wide-web at
 * http://www.php.net/license/3_01.txt.
 * If you did not receive a copy of the PHP license and are unable to
 * obtain it through the world-wide-web, please send a note to
 * license@php.net so we can mail you a copy immediately.
 *
 * @category Date
 * @package  Date_Holidays
 * @author   Stephan Schmidt <schst@php-tools.net>
 * @author   Carsten Lucke <luckec@tool-garage.de>
 * @license  http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @version  CVS: $Id$
 * @link     http://pear.php.net/package/Date_Holidays
 */

/**
 * Requires Christian driver
 */
require_once 'Christian.php';

/**
 * class that calculates Austrian holidays
 *
 * @category   Date
 * @package    Date_Holidays
 * @subpackage Driver
 * @author     Klemens Ullmann <klemens@ull.at>
 * @license    http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/Date_Holidays
 */
class Date_Holidays_Driver_Austria extends Date_Holidays_Driver
{
	/**
	 * this driver's name
	 *
	 * @access   protected
	 * @var      string
	 */
	var $_driverName = 'Austria';

	/**
	 * Constructor
	 *
	 * Use the Date_Holidays::factory() method to construct an object of a certain
	 * driver
	 *
	 * @access   protected
	 */
	function Date_Holidays_Driver_Austria()
	{
	}

	/**
	 * Build the internal arrays that contain data about the calculated holidays
	 *
	 * @access   protected
	 * @return   boolean true on success, otherwise a PEAR_ErrorStack object
	 * @throws   object PEAR_ErrorStack
	 */
	function _buildHolidays()
	{
		/**
		 * New Year's Day
		 */
		$this->_addHoliday('newYearsDay', $this->_year . '-01-01', 'Neujahr');
		$this->_addTranslationForHoliday('newYearsDay', 'en_EN', 'New Year\'s Day');

		/**
		 * Epiphanias
		 */
		$this->_addHoliday(
			'epiphany',
			$this->_year . '-01-06',
			'Heilige Drei Könige'
		);
		$this->_addTranslationForHoliday('epiphany', 'en_EN', 'Epiphany');

		/**
		 * Valentine´s Day
		 */
		$this->_addHoliday(
			'valentinesDay',
			$this->_year . '-02-14',
			'Valentinstag'
		);
		$this->_addTranslationForHoliday('valentinesDay', 'en_EN', 'Valentines Day');

		/**
		 * Easter Sunday
		 */
		$easterDate = Date_Holidays_Driver_Christian::calcEaster($this->_year);
		$this->_addHoliday('easter', $easterDate, 'Ostersonntag');
		$this->_addTranslationForHoliday('easter', 'en_EN', 'Easter');

		/**
		 * Ash Wednesday
		 */
		$ashWednesday = $this->_addDays($easterDate, -46);
		$this->_addHoliday('ashWednesday', $ashWednesday, 'Aschermittwoch');
		$this->_addTranslationForHoliday('ashWednesday', 'en_EN', 'Ash Wednesday');

		/**
		 * Palm Sunday
		 */
		$palmSunday = $this->_addDays($easterDate, -7);
		$this->_addHoliday('palmSunday', $palmSunday, 'Palmsonntag');
		$this->_addTranslationForHoliday('palmSunday', 'en_EN', 'Palm Sunday');

		/**
		 * Maundy Thursday
		 */
		$maundyThursday = $this->_addDays($easterDate, -3);
		$this->_addHoliday('maundyThursday', $maundyThursday, 'Gründonnerstag');
		$this->_addTranslationForHoliday(
			'maundyThursday',
			'en_EN',
			'Maundy Thursday'
		);

		/**
		 * Good Friday
		 */
		$goodFriday = $this->_addDays($easterDate, -2);
		$this->_addHoliday('goodFriday', $goodFriday, 'Karfreitag');
		$this->_addTranslationForHoliday('goodFriday', 'en_EN', 'Good Friday');

		/**
		 * Easter Monday
		 */
		$this->_addHoliday('easterMonday', $easterDate->getNextDay(), 'Ostermontag');
		$this->_addTranslationForHoliday('easterMonday', 'en_EN', 'Easter Monday');

		/**
		 * Day of Work
		 */
		$this->_addHoliday(
			'dayOfWork', $this->_year . '-05-01',
			'Staatsfeiertag Österreich'
		);
		$this->_addTranslationForHoliday('dayOfWork', 'en_EN', 'Day of Work');

		/**
		 * Saint Florian
		 */
		$this->_addHoliday('saintFlorian', $this->_year . '-05-04', 'St. Florian');
		$this->_addTranslationForHoliday('saintFlorian', 'en_EN', 'St. Florian');

		/**
		 * Mothers Day
		 */
		$mothersDay = $this->_calcFirstMonday("05");
		$mothersDay = $mothersDay->getPrevDay();
		$mothersDay = $this->_addDays($mothersDay, 7);
		$this->_addHoliday('mothersDay', $mothersDay, 'Muttertag');
		$this->_addTranslationForHoliday('mothersDay', 'en_EN', 'Mothers Day');

		/**
		 * Ascension Day
		 */
		$ascensionDate = $this->_addDays($easterDate, 39);
		$this->_addHoliday('ascensionDate', $ascensionDate, 'Christi Himmelfahrt');
		$this->_addTranslationForHoliday('ascensionDate', 'en_EN', 'Ascension Day');

		/**
		 * Whitsun (determines Whit Monday, Ascension Day and
		 * Feast of Corpus Christi)
		 */
		$whitsunDate = $this->_addDays($easterDate, 49);
		$this->_addHoliday('whitsun', $whitsunDate, 'Pfingstsonntag');
		$this->_addTranslationForHoliday('whitsun', 'en_EN', 'Whitsun');

		/**
		 * Whit Monday
		 */
		$this->_addHoliday(
			'whitMonday',
			$whitsunDate->getNextDay(),
			'Pfingstmontag'
		);
		$this->_addTranslationForHoliday('whitMonday', 'en_EN', 'Whit Monday');

		/**
		 * Corpus Christi
		 */
		$corpusChristi = $this->_addDays($easterDate, 60);
		$this->_addHoliday('corpusChristi', $corpusChristi, 'Fronleichnam');
		$this->_addTranslationForHoliday('corpusChristi', 'en_EN', 'Corpus Christi');

		/**
		 * Fathers Day
		 */
		$fathersDay = $this->_calcFirstMonday("06");
		$fathersDay = $fathersDay->getPrevDay();
		$fathersDay = $this->_addDays($fathersDay, 7);
		$this->_addHoliday(
			'fathersDay',
			$fathersDay,
			'Vatertag'
		);
		$this->_addTranslationForHoliday('fathersDay', 'en_EN', 'Fathers Day');

		/**
		 * Ascension of Maria
		 */
		$this->_addHoliday(
			'mariaAscension',
			$this->_year . '-08-15',
			'Maria Himmelfahrt'
		);
		$this->_addTranslationForHoliday(
			'mariaAscension',
			'en_EN',
			'Ascension of Maria'
		);

		/**
		 * Österreichischer Nationalfeiertag
		 */
		$this->_addHoliday(
			'nationalDayAustria',
			$this->_year . '-10-26',
			'Österreichischer Nationalfeiertag'
		);
		$this->_addTranslationForHoliday(
			'nationalDayAustria',
			'en_EN',
			'The Austrian National Day'
		);

		/**
		 * All Saints' Day
		 */
		$this->_addHoliday(
			'allSaintsDay',
			$this->_year . '-11-01',
			'Allerheiligen'
		);
		$this->_addTranslationForHoliday('allSaintsDay', 'en_EN', 'All Saints Day');

		/**
		 *All Souls´ Day
		 */
		$this->_addHoliday(
			'allSoulsDay',
			$this->_year . '-11-02',
			'Allerseelen'
		);
		$this->_addTranslationForHoliday('allSoulsDay', 'en_EN', 'All Souls Day');

		/**
		 * Santa Claus
		 */
		$this->_addHoliday(
			'santasDay',
			$this->_year . '-12-06',
			'St. Nikolaus'
		);
		$this->_addTranslationForHoliday('santasDay', 'en_EN', 'St. Nikolaus');

		/**
		 * Immaculate Conception
		 */
		$this->_addHoliday(
			'immaculateConceptionDay',
			$this->_year . '-12-08',
			'Maria Empfängnis'
		);
		$this->_addTranslationForHoliday(
			'immaculateConceptionDay',
			'en_EN',
			'Immaculate Conception Day'
		);

		/**
		 * Sunday in commemoration of the dead (sundayIcotd)
		 */
		$sundayIcotd = $this->_calcFirstMonday(12);
		$sundayIcotd = $this->_addDays($this->_calcFirstMonday(12), -8);
		$this->_addHoliday(
			'sundayIcotd',
			$sundayIcotd,
			'Totensonntag'
		);
		$this->_addTranslationForHoliday(
			'sundayIcotd',
			'en_EN',
			'Sunday in commemoration of the dead'
		);

		/**
		 * 1. Advent
		 */
		$firstAdv = new Date($this->_year . '-12-03');
		$dayOfWeek = $firstAdv->getDayOfWeek();
		$firstAdv = $this->_addDays($firstAdv, - $dayOfWeek);
		$this->_addHoliday(
			'firstAdvent',
			$firstAdv,
			'1. Advent'
		);
		$this->_addTranslationForHoliday('firstAdvent', 'en_EN', '1. Advent');

		/**
		 * 2. Advent
		 */
		$secondAdv = $this->_addDays($firstAdv, 7);
		$this->_addHoliday(
			'secondAdvent',
			$secondAdv,
			'2. Advent'
		);
		$this->_addTranslationForHoliday('secondAdvent', 'en_EN', '2. Advent');

		/**
		 * 3. Advent
		 */
		$thirdAdv = $this->_addDays($firstAdv, 14);
		$this->_addHoliday(
			'thirdAdvent',
			$thirdAdv,
			'3. Advent'
		);
		$this->_addTranslationForHoliday('thirdAdvent', 'en_EN', '3. Advent');

		/**
		 * 4. Advent
		 */
		$fourthAdv = $this->_addDays($firstAdv, 21);
		$this->_addHoliday(
			'fourthAdvent',
			$fourthAdv,
			'4. Advent'
		);
		$this->_addTranslationForHoliday('fourthAdvent', 'en_EN', '4. Advent');

		/**
		 * Christmas Eve
		 */
		$this->_addHoliday(
			'christmasEve',
			$this->_year . '-12-24',
			'Heiliger Abend'
		);
		$this->_addTranslationForHoliday('christmasEve', 'en_EN', 'Christmas Eve');

		/**
		 * Christmas day
		 */
		$this->_addHoliday(
			'christmasDay',
			$this->_year . '-12-25',
			'Christtag'
		);
		$this->_addTranslationForHoliday('christmasDay', 'en_EN', 'Christmas Day');

		/**
		 * Boxing day
		 */
		$this->_addHoliday(
			'boxingDay',
			$this->_year . '-12-26',
			'Stefanitag'
		);
		$this->_addTranslationForHoliday('boxingDay', 'en_EN', 'Boxing Day');

		/**
		 * New Year´s Eve
		 */
		$this->_addHoliday(
			'newYearsEve',
			$this->_year . '-12-31',
			'Silvester'
		);
		$this->_addTranslationForHoliday('newYearsEve', 'en_EN', 'New Years Eve');

		if (Date_Holidays::errorsOccurred()) {
			return 'Error in Christian Driver';
		}
		return true;
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
		return array('at');
	}
}
