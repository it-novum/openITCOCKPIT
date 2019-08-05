<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * This file contains only the Driver class for determining holidays in Queensland.
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
 * @author   Daniel O'Connor <daniel.oconnor@gmail.com>
 * @license  http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @link     http://pear.php.net/package/Date_Holidays
 */

require_once 'Date/Calc.php';

/**
 * This Driver class calculates holidays in Queensland.  It should be used in
 * conjunction with the Australia driver.
 *
 * @category   Date
 * @package    Date_Holidays
 * @subpackage Driver
 * @author     Daniel O'Connor <daniel.oconnor@gmail.com>
 * @license    http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @link       http://pear.php.net/package/Date_Holidays
 */
class Date_Holidays_Driver_AustraliaQLD extends Date_Holidays_Driver
{
    /**
     * this driver's name
     *
     * @access   protected
     * @var      string
     */
    var $_driverName = 'AustraliaQLD';

    /**
     * Constructor
     *
     * @access   protected
     */
    function Date_Holidays_Driver_AustraliaQLD()
    {
    }

    /**
     * Build the internal arrays that contain data about holidays.
     *
     * @access   protected
     * @return   boolean true on success, otherwise a PEAR_ErrorStack object
     * @throws   object PEAR_ErrorStack
     */
    function _buildHolidays()
    {
        parent::_buildHolidays();

        /*
         * Labour Day - 1st Monday of May
         */
        $labourDay = Date_Calc::nWeekdayOfMonth(1, 1, 5, $this->_year);
        $this->_addHoliday('labourDay', $labourDay, "Labour Day");
        $this->_addTranslationForHoliday('labourDay', 'en_EN', 'Labour Day');

        // Royal National Agricultural (RNA) Show Day (Brisbane only) usually held on second Wednesday in August except when there are five Wednesdays in August it is held on third Wednesday.
        if (Date_Calc::nWeekdayOfMonth(5, 3, 8, $this->_year) !== -1) {
            $royalQueenslandShow = Date_Calc::nWeekdayOfMonth(3, 3, 8, $this->_year);
        } else {
            $royalQueenslandShow = Date_Calc::nWeekdayOfMonth(2, 3, 8, $this->_year);
        }
        $this->_addHoliday('royalQueenslandShow', $royalQueenslandShow, "Royal Queensland Show"); // Brisbane area only
        $this->_addTranslationForHoliday('royalQueenslandShow', 'en_EN', 'Royal Queensland Show');

        /**
         * Christmas and Boxing Day
         */
        $christmasDay = new Date($this->_year . '-12-25');
        if ($christmasDay->getDayOfWeek() == 6) {
            // 25 December - if that date falls on a Saturday the public holiday transfers to the following Monday.
            $this->_addHoliday('christmasDay',
                               $this->_year . '-12-27',
                               'Substitute Bank Holiday in lieu of Christmas Day');

        } else if ($christmasDay->getDayOfWeek() == 0) {
            // If that date falls on a Sunday that day and the following Monday will be public holidays.
            $this->_addHoliday('christmasDay',
                               $this->_year . '-12-26',
                               'Substitute Bank Holiday in lieu of Christmas Day');
        } else {
            $this->_addHoliday('christmasDay', $christmasDay, 'Christmas Day');
        }

        $boxingDay = new Date($this->_year . '-12-26');
        if ($boxingDay->getDayOfWeek() == 6) {
            //26 December - if that date falls on a Saturday the public holiday transfers to the following Monday.
            $this->_addHoliday('boxingDay',
                               $this->_year . '-12-28',
                               'Substitute Bank Holiday in lieu of Boxing Day');
        } else if ($boxingDay->getDayOfWeek() == 0) {
            // If that date falls on a Sunday that day and the following Tuesday will be public holidays.
            $this->_addHoliday('boxingDay',
                               $this->_year . '-12-28',
                               'Substitute Bank Holiday in lieu of Boxing Day');
        } else if ($boxingDay->getDayOfWeek() == 1) {
            // If that date falls on a Monday that day and the following Tuesday will be public holidays.
            $this->_addHoliday('boxingDay',
                               $this->_year . '-12-26',
                               'Substitute Bank Holiday in lieu of Boxing Day');
            $this->_addHoliday('boxingDay',
                               $this->_year . '-12-27',
                               'Substitute Bank Holiday in lieu of Boxing Day');
        } else {
            $this->_addHoliday('boxingDay', $this->_year . '-12-26', 'Boxing Day');
        }

        $this->_addTranslationForHoliday('christmasDay', 'en_EN', 'Christmas Day');
        $this->_addTranslationForHoliday('boxingDay', 'en_EN', 'Boxing Day');


        /*
         * See http://en.wikipedia.org/wiki/Queen%27s_Official_Birthday#Australia
         */
        if ($this->_year < 2012) {
            $queensBirthday = Date_Calc::nWeekdayOfMonth(2, 1, 6, $this->_year);
            $this->_addHoliday('queensBirthday', $queensBirthday, "Queen's Birthday");
            $this->_addTranslationForHoliday('queensBirthday', 'en_EN', "Queen's Birthday");
        }

        if ($this->_year == '2012') {
            $this->_addHoliday('queensDiamondJubilee', Date_Calc::nWeekdayOfMonth(2, 1, 6, $this->_year), "Queen's Diamond Jubilee");
            $this->_addTranslationForHoliday('queensDiamondJubilee', 'en_EN', "Queen's Diamond Jubilee");
        }

        if ($this->_year >= 2012) {
            $queensBirthday = Date_Calc::nWeekdayOfMonth(1, 1, 10, $this->_year);
            $this->_addHoliday('queensBirthday', $queensBirthday, "Queen's Birthday");
            $this->_addTranslationForHoliday('queensBirthday', 'en_EN', "Queen's Birthday");
        }
    } // _buildHolidays()

} // Date_Holidays_Driver_AustraliaQLD
