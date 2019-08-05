<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Driver class that calculates Norwegian holidays
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
 * @author   Vegard Fiksdal <fiksdal@sensorlink.no>
 * @license  http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @version  CVS: $Id: Norway.php,v 1.6 2009/03/15 20:17:00 kguest Exp $
 * @link     http://pear.php.net/package/Date_Holidays
 */


require_once 'Christian.php';

/**
 * Driver class that calculates Norwegian holidays
 *
 * @category   Date
 * @package    Date_Holidays
 * @subpackage Driver
 * @author     Vegard Fiksdal <fiksdal@sensorlink.no>
 * @license    http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @version    CVS: $Id: Norway.php,v 1.6 2009/03/15 20:17:00 kguest Exp $
 * @link       http://pear.php.net/package/Date_Holidays
 * Sensorlink AS (c) 2006
 */
class Date_Holidays_Driver_Norway extends Date_Holidays_Driver
{
    /**
     * this driver's name
     *
     * @access   protected
     * @var      string
     */
    var $_driverName = 'Norway';

    /**
     * Constructor
     *
     * Use the Date_Holidays::factory() method to construct an object of a certain
     * driver
     *
     * @access   protected
     */
    function Date_Holidays_Driver_Norway ()
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
        $this->_addHoliday('newYearsDay',
                           $this->_year . '-01-01',
                           'New Year\'s Day');

        /**
         * Easter Sunday
         */
        $easterDate = Date_Holidays_Driver_Norway::calcEaster($this->_year);
        $this->_addHoliday('easter', $easterDate, 'Easter Sunday');

        /**
         * Good Friday / Black Friday
         */
        $goodFridayDate = $this->_addDays($easterDate, -2);
        $this->_addHoliday('goodFriday', $goodFridayDate, 'Good Friday');

        /**
         * Easter Monday
         */
        $this->_addHoliday('easterMonday',
                           $easterDate->getNextDay(),
                           'Easter Monday');

        /**
         * May Day
         */
        $this->_addHoliday('mayDay', $this->_year . '-05-01', 'May Day');

        /**
         * Pentecost (determines Whit Monday, Ascension Day and Feast of
         * Corpus Christi)
         */
        $pentecostDate = $this->_addDays($easterDate, 49);
        $this->_addHoliday('pentecost', $pentecostDate, 'Pentecost');

        /**
         * Ascension Day
         */
        $ascensionDayDate = $this->_addDays($pentecostDate, -10);
        $this->_addHoliday('ascensionDay', $ascensionDayDate, 'Ascension Day');

        /**
         * Norwegian National Day
         */
        $this->_addHoliday('norwayNationalDay', $this->_year . '-05-17',
                'Norwegian National Day');

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
        $this->_addHoliday('newYearsEve',
                           $this->_year . '-12-31',
                           'New Year\'s Eve');

        if (Date_Holidays::errorsOccurred()) {
            return 'Error in Norway Driver';
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
     * @access private
     * @return object Date
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
        return array('no', 'nor');
    }
}
