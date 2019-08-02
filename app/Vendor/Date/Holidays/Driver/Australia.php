<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * This file contains only the Driver class for determining holidays in Australia.
 *
 * PHP Version 5
 *
 * @category Date
 * @package  Date_Holidays
 * @author   Sam Wilson <sam@archives.org.au>
 * @license  BSD http://www.opensource.org/licenses/bsd-license.php
 * @link     http://pear.php.net/package/Date_Holidays
 */

require_once 'Christian.php';

/**
 * This is a Driver class that calculates holidays in Australia.  Individual states
 * generally have other holidays as well (ones that sometimes override those defined
 * herein) and so if one is available you should combine it with this one.
 *
 * @category   Date
 * @package    Date_Holidays
 * @subpackage Driver
 * @author     Sam Wilson <sam@archives.org.au>
 * @license    BSD http://www.opensource.org/licenses/bsd-license.php
 * @link       http://pear.php.net/package/Date_Holidays
 */
class Date_Holidays_Driver_Australia extends Date_Holidays_Driver
{
    /**
     * this driver's name
     *
     * @access   protected
     * @var      string
     */
    var $_driverName = 'Australia';

    /**
     * Constructor
     *
     * Use the Date_Holidays::factory() method to construct an object of a
     * certain driver
     *
     * @access   protected
     */
    function Date_Holidays_Driver_Australia()
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
         * New Year's Day
         */
        $newYearsDay = new Date($this->_year . '-01-01');
        if ($newYearsDay->getDayOfWeek() == 0) { // 0 = Sunday
            $newYearsDay = $this->_year . '-01-02';
        } elseif ($newYearsDay->getDayOfWeek() == 6) { // 6 = Saturday
            $newYearsDay = $this->_year . '-01-03';
        }
        $this->_addHoliday('newYearsDay', $newYearsDay, 'New Year\'s Day');
        $this->_addTranslationForHoliday('newYearsDay', 'en_EN', 'New Year\'s Day');

        /*
         * Australia Day
         */
        $australiaDay = new Date($this->_year . '-01-26');
        if ($australiaDay->getDayOfWeek() == 0) { // 0 = Sunday
            $australiaDay = $this->_year . '-01-27';
        } elseif ($australiaDay->getDayOfWeek() == 6) { // 6 = Saturday
            $australiaDay = $this->_year . '-01-28';
        }
        $this->_addHoliday('australiaDay', $australiaDay, 'Australia Day');
        $this->_addTranslationForHoliday('australiaDay', 'en_EN', 'Australia Day');
        /*
         * Easter
         */
        $easter = Date_Holidays_Driver_Christian::calcEaster($this->_year);
        $goodFridayDate = new Date($easter);
        $goodFridayDate = $this->_addDays($easter, -2);
        $easterMonday = $easter->getNextDay();

        // Conflicts with Anzac day?
        if ($easterMonday->getDay() == 25) {
            $this->_addHoliday('easterTuesday', $easterMonday->getNextDay(), 'Easter Tuesday');
            $this->_addTranslationForHoliday('easterTuesday', 'en_EN', 'Easter Tuesday');
        }

        $this->_addHoliday('goodFriday', $goodFridayDate, 'Good Friday');
        $this->_addHoliday('easterMonday', $easterMonday, 'Easter Monday');
        $this->_addTranslationForHoliday('goodFriday', 'en_EN', 'Good Friday');
        $this->_addTranslationForHoliday('easterMonday', 'en_EN', 'Easter Monday');


        /*
         * Anzac Day
         */
        $anzacDay = new Date($this->_year . '-04-25');
        $this->_addHoliday('anzacDay', $anzacDay, 'Anzac Day');
        $this->_addTranslationForHoliday('anzacDay', 'en_EN', "Anzac Day");
        if ($anzacDay->getDayOfWeek() == 0) { // 0 = Sunday
            $anzacDayHol = $this->_year . '-04-26';
            $this->_addHoliday('anzacDay', $anzacDayHol, 'Anzac Day Holiday');
        } elseif ($anzacDay->getDayOfWeek() == 6) { // 6 = Saturday
            $anzacDayHol = $this->_year . '-04-27';
            $this->_addHoliday('anzacDay', $anzacDayHol, 'Anzac Day Holiday');
        }

        /*
         * The Queen's Birthday.
         * See http://en.wikipedia.org/wiki/Queen%27s_Official_Birthday#Australia
         */
        $queensBirthday = Date_Calc::nWeekdayOfMonth(1, 1, 6, $this->_year);
        $this->_addHoliday('queensBirthday', $queensBirthday, "Queen's Birthday");
        $this->_addTranslationForHoliday('queensBirthday', 'en_EN', "Queen's Birthday");

        /*
         * Christmas and Boxing days
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

        // Boxing day isn't quite a national holiday, as it's labelled Proclamation day in SA.
        // See AustraliaNSW for this implementation.
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
         * Check for errors, and return.
         */
        if (Date_Holidays::errorsOccurred()) {
            return 'Error in Australia Driver';
        }

        return true;

    }

    /**
     * Method that returns an array containing the ISO3166 codes ('au' and 'aus')
     * that identify this driver.
     *
     * @static
     * @access public
     * @return array possible ISO3166 codes
     */
    function getISO3166Codes()
    {
        return array('au', 'aus');
    }
}
