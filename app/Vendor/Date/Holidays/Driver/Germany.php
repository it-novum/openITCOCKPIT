<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Driver for holidays in Germany
 *
 * PHP Version 4
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
 * @version  CVS: $Id: Germany.php,v 1.13 2009/03/14 22:30:14 kguest Exp $
 * @link     http://pear.php.net/package/Date_Holidays
 */

/**
 * Extends Christian driver
 */
require_once 'Christian.php';

/**
 * class that calculates German holidays
 *
 * @category   Date
 * @package    Date_Holidays
 * @subpackage Driver
 * @author     Carsten Lucke <luckec@tool-garage.de>
 * @author     Stephan Schmidt <schst@php.net>
 * @license    http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @version    CVS: $Id: Germany.php,v 1.13 2009/03/14 22:30:14 kguest Exp $
 * @link       http://pear.php.net/package/Date_Holidays
 */
class Date_Holidays_Driver_Germany extends Date_Holidays_Driver_Christian
{
	/**
	 * this driver's name
	 *
	 * @access   protected
	 * @var      string
	 */
	var $_driverName = 'Germany';

	/**
	 * Constructor
	 *
	 * Use the Date_Holidays::factory() method to construct an object of a
	 * certain driver
	 *
	 * @access   protected
	 */
	function Date_Holidays_Driver_Germany()
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
		parent::_buildHolidays();

		$easterDate       = $this->getHolidayDate('easter');
		$ashWednesdayDate = $this->getHolidayDate('ashWednesday');
		$ascensionDayDate = $this->getHolidayDate('ascensionDay');
		$advent1Date      = $this->getHolidayDate('advent1');

		/**
		 * New Year's Day
		 */
		$this->_addHoliday('newYearsDay',
						   $this->_year . '-01-01',
						   'New Year\'s Day');

		/**
		 * Valentine's Day
		 */
		$this->_addHoliday('valentinesDay',
						   $this->_year . '-02-14',
						   'Valentine\'s Day');

		/**
		 * "Weiberfastnacht"
		 */
		$wFasnetDate = $this->_addDays($ashWednesdayDate, -6);
		$this->_addHoliday('womenFasnet', $wFasnetDate, 'Carnival');

		/**
		 * Carnival / "Fastnacht"
		 */
		$fasnetDate = $this->_addDays($easterDate, -47);
		$this->_addHoliday('fasnet', $fasnetDate, 'Carnival');

		/**
		 * Rose Monday
		 */
		$roseMondayDate = $this->_addDays($easterDate, -48);
		$this->_addHoliday('roseMonday', $roseMondayDate, 'Rose Monday');

		/**
		 * International Women's Day
		 */
		$this->_addHoliday('womensDay',
						   $this->_year . '-03-08',
						   'International Women\'s Day');

		/**
		 * April 1st
		 */
		$this->_addHoliday('april1st', $this->_year . '-04-01', 'April 1st');

		/**
		 * Girls' Day (fourth Thursday in April)
		 */
		$girlsDayDate = new Date($this->_year . '-04-01');
		$dayOfWeek    = $girlsDayDate->getDayOfWeek();
		switch ($dayOfWeek) {
		case 0:
		case 1:
		case 2:
		case 3:
			$girlsDayDate = $this->_addDays($girlsDayDate, 4 - $dayOfWeek + 21);
			break;
		case 4:
			$girlsDayDate = $this->_addDays($girlsDayDate, 21);
			break;
		case 5:
		case 6:
			$girlsDayDate = $this->_addDays($girlsDayDate, -1 * $dayOfWeek + 11 + 21);
			break;
		}
		$this->_addHoliday('girlsDay', $girlsDayDate, 'Girls\' Day');

		/**
		 * International Earth' Day
		 */
		$this->_addHoliday('earthDay',
						   $this->_year . '-04-22',
						   'International Earth\' Day');

		/**
		 * German Beer's Day
		 */
		$this->_addHoliday('beersDay',
						   $this->_year . '-04-23',
						   'German Beer\'s Day');

		/**
		 * Walpurgis Night
		 */
		$this->_addHoliday('walpurgisNight',
						   $this->_year . '-04-30',
						   'Walpurgis Night');

		/**
		 * Day of Work
		 */
		$this->_addHoliday('dayOfWork',
						   $this->_year . '-05-01',
						   'Day of Work');

		/**
		 * World's Laughing Day
		 */
		$laughingDayDate = new Date($this->_year . '-05-01');
		while ($laughingDayDate->getDayOfWeek() != 0) {
			$laughingDayDate = $laughingDayDate->getNextDay();
		}
		$this->_addHoliday('laughingDay',
						   $laughingDayDate,
						   'World\'s Laughing Day');

		/**
		 * Europe Day
		 */
		$this->_addHoliday('europeDay',
						   $this->_year . '-05-05',
						   'Europe Day');

		/**
		 * Mothers' Day
		 */
		$mothersDay = $this->_addDays($laughingDayDate, 7);
		$this->_addHoliday('mothersDay', $mothersDay, 'Mothers\' Day');

		/**
		 * End of World War 2 in Germany
		 */
		$this->_addHoliday('endOfWWar2',
						   $this->_year . '-05-08',
						   'End of World War 2 in Germany');

		/**
		 * Fathers' Day
		 */
		$this->_addHoliday('fathersDay', $ascensionDayDate, 'Fathers\' Day');

		/**
		 * Amnesty International Day
		 */
		$this->_addHoliday('aiDay',
						   $this->_year . '-05-28',
						   'Amnesty International Day');

		/**
		 * International Children' Day
		 */
		$this->_addHoliday('intChildrenDay',
						   $this->_year . '-06-01',
						   'International Children\'s Day');

		/**
		 * Day of organ donation
		 */
		$organDonationDate = new Date($this->_year . '-06-01');
		while ($organDonationDate->getDayOfWeek() != 6) {
			$organDonationDate = $organDonationDate->getNextDay();
		}
		$this->_addHoliday('organDonationDay',
						   $organDonationDate,
						   'Day of organ donation');

		/**
		 * Dormouse' Day
		 */
		$this->_addHoliday('dormouseDay',
						   $this->_year . '-06-27',
						   'Dormouse\' Day');

		/**
		 * Christopher Street Day
		 */
		$this->_addHoliday('christopherStreetDay',
						   $this->_year . '-06-27',
						   'Christopher Street Day');

		/**
		 * Hiroshima Commemoration Day
		 */
		$this->_addHoliday('hiroshimaCommemorationDay',
						   $this->_year . '-08-06',
						   'Hiroshima Commemoration Day');

		/**
		 * Augsburg peace celebration
		 */
		$this->_addHoliday('augsburgPeaceCelebration',
						   $this->_year . '-08-08',
						   'Augsburg peace celebration');

		/**
		 * International left-handeds' Day
		 */
		$this->_addHoliday('leftHandedDay',
						   $this->_year . '-08-13',
						   'International left-handeds\' Day');

		/**
		 * Anti-War Day
		 */
		$this->_addHoliday('antiWarDay',
						   $this->_year . '-09-01',
						   'Anti-War Day');

		/**
		 * Day of German Language
		 */
		$germanLangDayDate = new Date($this->_year . '-09-01');
		while ($germanLangDayDate->getDayOfWeek() != 6) {
			$germanLangDayDate = $germanLangDayDate->getNextDay();
		}
		$germanLangDayDate = $this->_addDays($germanLangDayDate, 7);
		$this->_addHoliday('germanLanguageDay',
						   $germanLangDayDate,
						   'Day of German Language');

		/**
		 * International diabetes day
		 */
		$this->_addHoliday('diabetesDay',
						   $this->_year . '-11-14',
						   'International diabetes day');

		/**
		 * German Unification Day
		 */
		$this->_addHoliday('germanUnificationDay',
						   $this->_year . '-10-03',
						   'German Unification Day');

		/**
		 * Libraries' Day
		 */
		$this->_addHoliday('librariesDay',
						   $this->_year . '-10-24',
						   'Libraries\' Day');

		/**
		 * World's Savings Day
		 */
		$this->_addHoliday('savingsDay',
						   $this->_year . '-10-30',
						   'World\'s Savings Day');

		/**
		 * Halloween
		 */
		$this->_addHoliday('halloween', $this->_year . '-10-31', 'Halloween');

		/**
		 * Stamp's Day
		 *
		 * year <= 1948: 7th of January
		 * year > 1948: last Sunday in October
		 */
		$stampsDayDate = null;
		if ($this->_year <= 1948) {
			$stampsDayDate = new Date($this->_year . '-01-07');
			while ($stampsDayDate->getDayOfWeek() != 0) {
				$stampsDayDate = $stampsDayDate->getNextDay();
			}
		} else {
			$stampsDayDate = new Date($this->_year . '-10-31');
			while ($stampsDayDate->getDayOfWeek() != 0) {
				$stampsDayDate = $stampsDayDate->getPrevDay();
			}
		}
		$this->_addHoliday('stampsDay', $stampsDayDate, 'Stamp\'s Day');

		/**
		 * International Men's Day
		 */
		$this->_addHoliday('mensDay',
						   $this->_year . '-11-03',
						   'International Men\'s Day');

		/**
		 * Fall of the Wall of Berlin
		 */
		$this->_addHoliday('wallOfBerlin',
						   $this->_year . '-11-09',
						   'Fall of the Wall of Berlin 1989');

		/**
		 * Beginning of the Carnival
		 */
		$this->_addHoliday('carnivalBeginning',
						   $this->_year . '-11-11',
						   'Beginning of the Carnival');

		/**
		 * People's Day of Mourning
		 */
		$dayOfMourning = $this->_addDays($advent1Date, -14);
		$this->_addHoliday('dayOfMourning',
						   $dayOfMourning,
						   'People\'s Day of Mourning');

		if (Date_Holidays::errorsOccurred()) {
			return 'Error in Germany driver';
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
		return array('de', 'deu');
	}
}
