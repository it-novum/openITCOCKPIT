<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * This file contains only the Driver class for determining holidays in New South Wales
 *
 * PHP Version 5
 *
 * @category Date
 * @package  Date_Holidays
 * @author   Daniel O'Connor <daniel.oconnor@gmail.com>
 * @license  BSD http://www.opensource.org/licenses/bsd-license.php
 * @link     http://pear.php.net/package/Date_Holidays
 */

require_once 'Date/Calc.php';

/**
 * This Driver class calculates holidays in Western Australia.  It should be used in
 * conjunction with the Australia driver.
 *
 * @category   Date
 * @package    Date_Holidays
 * @subpackage Driver
 * @author     Daniel O'Connor <daniel.oconnor@gmail.com>
 * @license    BSD http://www.opensource.org/licenses/bsd-license.php
 * @link       http://pear.php.net/package/Date_Holidays
 */
class Date_Holidays_Driver_AustraliaNSW extends Date_Holidays_Driver
{
    /**
     * this driver's name
     *
     * @access   protected
     * @var      string
     */
    var $_driverName = 'AustraliaNSW';

    /**
     * Constructor
     *
     * @access   protected
     */
    function Date_Holidays_Driver_AustraliaNSW()
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
        $this->_addTranslationForHoliday('labourDay', 'en_EN', "Labour Day");

        /*
         * See http://en.wikipedia.org/wiki/Queen%27s_Official_Birthday#Australia
         */
        $queensBirthday = Date_Calc::nWeekdayOfMonth(2, 1, 6, $this->_year);
        $this->_addHoliday('queensBirthday', $queensBirthday, "Queen's Birthday");
        $this->_addTranslationForHoliday('queensBirthday', 'en_EN', "Queen's Birthday");

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


        // http://www.legislation.nsw.gov.au/maintop/view/inforce/act+49+2008+cd+0+N
        //  the first Monday in August (Bank Holiday).
        $bankHoliday = Date_Calc::nWeekdayOfMonth(1, 1, 8, $this->_year);
        $this->_addHoliday('bankHoliday', $bankHoliday, 'Bank Holiday');
        $this->_addTranslationForHoliday('bankHoliday', 'en_EN', 'Bank Holiday');

    } // _buildHolidays()

} // Date_Holidays_Driver_AustraliaNSW
