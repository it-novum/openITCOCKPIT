<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * This file contains only the Driver class for determining holidays in South
 * Australia.
 *
 * @see http://www.safework.sa.gov.au/show_page.jsp?id=2483
 *
 * PHP Version 5
 *
 * Copyright (c) 1997-2008 The PHP Group
 *
 * @category Date
 * @package  Date_Holidays
 * @author   Daniel O'Connor <daniel.oconnor@gmail.com>
 * @license  BSD http://www.opensource.org/licenses/bsd-license.php
 * @link     http://pear.php.net/package/Date_Holidays
 */

require_once 'Date/Calc.php';

/**
 * This Driver class calculates holidays in South Australia.  It should be used in
 * conjunction with the Australia driver.
 *
 * @category   Date
 * @package    Date_Holidays
 * @subpackage Driver
 * @author     Daniel O'Connor <daniel.oconnor@gmail.com>
 * @license    BSD http://www.opensource.org/licenses/bsd-license.php
 * @link       http://pear.php.net/package/Date_Holidays
 */
class Date_Holidays_Driver_AustraliaSA extends Date_Holidays_Driver
{
    /**
     * this driver's name
     *
     * @access   protected
     * @var      string
     */
    var $_driverName = 'AustraliaSA';

    /**
     * Constructor
     *
     * @access   protected
     */
    function Date_Holidays_Driver_AustraliaSA()
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
         * Labour Day - 1st Monday of October
         */
        $labourDay = Date_Calc::nWeekdayOfMonth(1, 1, 10, $this->_year);
        $this->_addHoliday('labourDay', $labourDay, "Labour Day");
        $this->_addTranslationForHoliday('labourDay', 'en_EN', 'Labour Day');

        /*
         * See http://en.wikipedia.org/wiki/Queen%27s_Official_Birthday#Australia
         */
        $queensBirthday = Date_Calc::nWeekdayOfMonth(2, 1, 6, $this->_year);
        $this->_addHoliday('queensBirthday', $queensBirthday, "Queen's Birthday");
        $this->_addTranslationForHoliday('queensBirthday', 'en_EN', "Queen's Birthday");

        $volunteersDay = Date_Calc::nWeekdayOfMonth(2, 1, 6, $this->_year);
        $this->_addHoliday('volunteersDay', $volunteersDay, "Volunteer's Day");
        $this->_addTranslationForHoliday('volunteersDay', 'en_EN', "Volunteer's Day");

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


        $proclamationDay = new Date($this->_year . '-12-26');
        if ($proclamationDay->getDayOfWeek() == 6) {
            //26 December - if that date falls on a Saturday the public holiday transfers to the following Monday.
            $this->_addHoliday('proclamationDay',
                               $this->_year . '-12-28',
                               'Substitute Bank Holiday in lieu of Proclamation Day');
        } else if ($proclamationDay->getDayOfWeek() == 0) {
            // If that date falls on a Sunday that day and the following Tuesday will be public holidays.
            $this->_addHoliday('proclamationDay',
                               $this->_year . '-12-28',
                               'Substitute Bank Holiday in lieu of Proclamation Day');
        } else if ($proclamationDay->getDayOfWeek() == 1) {
            // If that date falls on a Monday that day and the following Tuesday will be public holidays.
            $this->_addHoliday('proclamationDay',
                               $this->_year . '-12-26',
                               'Substitute Bank Holiday in lieu of Proclamation Day');
            $this->_addHoliday('proclamationDay',
                               $this->_year . '-12-27',
                               'Substitute Bank Holiday in lieu of Proclamation Day');
        } else {
            $this->_addHoliday('proclamationDay', $this->_year . '-12-26', 'Proclamation Day');
        }

        $this->_addTranslationForHoliday('christmasDay', 'en_EN', 'Christmas Day');
        $this->_addTranslationForHoliday('proclamationDay', 'en_EN', 'Proclamation Day');


        //The Holidays Act 1910 provides for the third Monday in May to be a public holiday.
        if ($this->_year < 2006) {
            $adelaideCup = Date_Calc::nWeekdayOfMonth(3, 1, 5, $this->_year);
        } else {
            // Since 2006, on a trial basis, this public holiday has been observed on the second Monday in March through the issuing of a special Proclamation by the Governor.
            $adelaideCup = Date_Calc::nWeekdayOfMonth(2, 1, 3, $this->_year);
        }

        $this->_addHoliday('adelaideCup', $adelaideCup, 'Adelaide Cup');
        $this->_addTranslationForHoliday('adelaideCup', 'en_EN', 'Adelaide Cup');

    } // _buildHolidays()

} // Date_Holidays_Driver_AustraliaSA
