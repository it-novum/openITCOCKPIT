<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Driver for determining holidays in Ukraine.
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
 * @author   Ken Guest <kguest@php.net>
 * @license  http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @version  CVS: $Id: Ukraine.php,v 1.8 2009/03/15 20:17:00 kguest Exp $
 * @link     http://pear.php.net/package/Date_Holidays
 */

require_once 'EasternChristian.php';

/**
 * Driver class that calculates holidays in the Ukraine.
 *
 * @category   Date
 * @package    Date_Holidays
 * @subpackage Driver
 * @author     Ken Guest <kguest@php.net>
 * @license    http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @version    CVS: $Id: Ukraine.php,v 1.8 2009/03/15 20:17:00 kguest Exp $
 * @link       http://pear.php.net/package/Date_Holidays
 */
class Date_Holidays_Driver_Ukraine extends Date_Holidays_Driver {
    /**
     * this driver's name
     *
     * @access   protected
     * @var      string
     */
    var $_driverName = 'Ukraine';

    /**
     * Constructor
     *
     * Use the Date_Holidays::factory() method to construct an object of a
     * certain driver
     *
     * @access   protected
     */
    function Date_Holidays_Driver_Ukraine() {
    }

    /**
     * Build the internal arrays that contain data about the calculated holidays
     *
     * @access   protected
     * @return   boolean true on success, otherwise a PEAR_ErrorStack object
     * @throws   object PEAR_ErrorStack
     */
    function _buildHolidays() {
        /**
         * New Year's Day
         */
        $this->_addHoliday('newYearsDay',
            $this->_year . '-01-01',
            'New Year\'s Day');

        /**
         * Christmas day (orthodox).
         */
        $this->_addHoliday('christmasDay',
            $this->_year . '-01-07',
            'Christmas Day');

        $easterDate = Date_Holidays_Driver_Ukraine::calcEaster($this->_year);
        $this->_addHoliday('easter', $easterDate, "Velykden'/Easter");
        /**
         * Triytsia
         */
        $triyDate = $this->_addDays($easterDate, 49);
        $this->_addHoliday('triytsia', $triyDate, 'Triytsia');

        /**
         * Unification of Ukraine Day  - January 22
         */
        $this->_addHoliday('ukraineDay', $this->_year . '-01-22', 'Ukraine Day');

        /**
         *  Women's Day
         */
        $this->_addHoliday('womensDay', $this->_year . '-03-08', "Women's Day");


        /**
         *  Labour Day[s]
         */
        $this->_addHoliday('labourDay1', $this->_year . '-05-01', "Labour Day");
        $this->_addHoliday('labourDay2', $this->_year . '-05-02', "Labour Day");

        /**
         *Victory Day  - May 9.
         */
        $this->_addHoliday('victoryDay', $this->_year . '-05-09', 'Victory Day');

        /**
         * Constitution Day - June 28
         */
        $this->_addHoliday('constitutionDay',
            $this->_year . '-06-28',
            'Constitution Day');

        /**
         * Independence Day - August 24
         */
        $this->_addHoliday('independenceDay',
            $this->_year . '-08-24',
            "Independence Day");

        if (Date_Holidays::errorsOccurred()) {
            return 'Error in Ukraine Driver';
        }
        return true;
    }

    /**
     * Calculates the date for Easter (Orthodox).
     *
     * @param int $year year
     *
     * @static
     * @access   private
     * @return   object Date
     */
    function calcEaster($year) {
        return Date_Holidays_Driver_EasternChristian::calcEaster($year);
    }

    /**
     * Method that returns an array containing the ISO3166 codes that may possibly
     * identify a driver.
     *
     * @static
     * @access public
     * @return array possible ISO3166 codes
     */
    function getISO3166Codes() {
        return ['ua', 'ukr'];
    }
}
