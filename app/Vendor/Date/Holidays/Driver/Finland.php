<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Driver for determining holidays in Finland
 *
 * PHP Version 4 5
 *
 * Copyright (c) 2011 The PHP Group
 *
 * @category Date
 * @package  Date_Holidays
 * @author   Carsten Lucke <luckec@tool-garage.de>
 * @author   Anders Karlsson <anders.x.karlsson@tdcsong.se>
 * @license  BSD http://www.opensource.org/licenses/bsd-license.php
 * @version  SVN: $Id: Finland.php 311411 2011-05-25 00:32:38Z kguest $
 * @link     http://pear.php.net/package/Date_Holidays
 */

require_once 'Christian.php';

/**
 * Driver class that calculates Finnish holidays
 *
 * @category   Date
 * @package    Date_Holidays
 * @subpackage Driver
 * @author     Rami Lehti <rami.lehti@bitwise.fi>
 * @license    BSD http://www.opensource.org/licenses/bsd-license.php
 * @version    Release: 0.1.2
 * @link       http://pear.php.net/package/Date_Holidays
 */
class Date_Holidays_Driver_Finland extends Date_Holidays_Driver
{
    /**
     * this driver's name
     *
     * @access   protected
     * @var      string
     */
    var $_driverName = 'Finland';

    /**
     * Constructor
     *
     * Use the Date_Holidays::factory() method to construct an object of
     * a certain driver
     *
     * @access   protected
     */
    function Date_Holidays_Driver_Finland()
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
        $this->_addHoliday(
            'newYearsDay',
            $this->_year . '-01-01',
            'New Year\'s Day'
        );

        /**
         * Epiphanias
         */
        $this->_addHoliday(
            'epiphany',
            $this->_year . '-01-06',
            'Epiphany'
        );

        /**
         * Easter Sunday
         */
        $easterDate = Date_Holidays_Driver_Finland::calcEaster($this->_year);
        $this->_addHoliday('easter', $easterDate, 'Easter Sunday');

        /**
         * Good Friday / Black Friday
         */
        $goodFridayDate = $this->_addDays($easterDate, -2);
        $this->_addHoliday('goodFriday', $goodFridayDate, 'Good Friday');

        /**
         * Easter Monday
         */
        $this->_addHoliday(
            'easterMonday',
            $easterDate->getNextDay(),
            'Easter Monday'
        );

        /**
         * May Day
         */
        $this->_addHoliday(
            'mayDay',
            $this->_year . '-05-01',
            'May Day'
        );

        /**
         * Pentecost (determines Whit Monday, Ascension Day and
         * Feast of Corpus Christi)
         */
        $pentecostDate = $this->_addDays($easterDate, 49);
        $this->_addHoliday('pentecost', $pentecostDate, 'Pentecost');

        /**
         * Ascension Day
         */
        $ascensionDayDate = $this->_addDays($pentecostDate, -10);
        $this->_addHoliday('ascensionDay', $ascensionDayDate, 'Ascension Day');

        /**
         * Midsummer
         * Saturday past 20th, June
         */
        $juneDate  = new Date($this->_year . '-06-20');
        $dayOfWeek = $juneDate->getDayOfWeek();
        $midSummerDate = $this->_addDays($juneDate, 6 - $dayOfWeek);
        $this->_addHoliday('midSummer', $midSummerDate, 'Midsummer Day');

        /**
         * Midsummer Eve
         * Day before Midsummer.
         */
        $this->_addHoliday(
            'midSummerEve',
            $midSummerDate->getPrevDay(),
            'Midsummer Eve'
        );

        /**
         * All Saints' Day
         */
        $saintspanDate = new Date($this->_year . '-10-31');
        $dayOfWeek     = $saintspanDate->getDayOfWeek();
        $allSaintsDate = $this->_addDays($saintspanDate, 6 - $dayOfWeek);
        $this->_addHoliday('allSaintsDay', $allSaintsDate, 'All Saints\' Day');

        /**
         * Finnish National Day
         */
        $this->_addHoliday(
            'finlandNationalDay',
            $this->_year . '-12-06',
            'Finnish National Day'
        );

        /**
         * Christmas Eve
         */
        $this->_addHoliday('christmasEve', $this->_year . '-12-24', 'Christmas Eve');

        /**
         * Christmas day
         */
        $this->_addHoliday('christmasDay', $this->_year . '-12-25', 'Christmas Day');

        /**
         * Boxing day
         */
        $this->_addHoliday('boxingDay', $this->_year . '-12-26', 'Boxing Day');

        /**
         * New Year's Eve
         */
        $this->_addHoliday(
            'newYearsEve',
            $this->_year . '-12-31',
            'New Year\'s Eve'
        );

        if (Date_Holidays::errorsOccurred()) {
            return 'Error in Finland Driver';
        }
        return true;
    }

    /**
     * Calculates the date for Easter. Actually this methods delegates the
     * calculation to the {@link Date_Holidays_Driver_Christian#calcEaster()} method.
     *
     * @param int $year year
     *
     * @static
     * @access   private
     * @return   object Date
     */
    function calcEaster($year)
    {
        return Date_Holidays_Driver_Christian::calcEaster($year);
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
        return array('fi', 'fin');
    }
}
?>
