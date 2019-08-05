<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Driver for holidays in Germany
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
 * @author   Tim Dodge <timmy@invisibles.org>
 * @license  http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @version  CVS: $Id$
 * @link     http://pear.php.net/package/Date_Holidays
 */
require_once 'Christian.php';

/**
 * Driver class that calculates holidays in England and Wales
 *
 * @category   Date
 * @package    Date_Holidays
 * @subpackage Driver
 * @author     Tim Dodge <timmy@invisibles.org>
 * @license    http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/Date_Holidays
 */
class Date_Holidays_Driver_EnglandWales extends Date_Holidays_Driver
{
    /**
     * this driver's name
     *
     * @access   protected
     * @var      string
     */
    var $_driverName = 'EnglandWales';

    /**
     * Constructor
     *
     * Use the Date_Holidays::factory() method to construct an object of a
     * certain driver
     *
     * @access protected
     */
    function Date_Holidays_Driver_EnglandWales()
    {
    }

    /**
     * Build the internal arrays that contain data about the calculated holidays
     *
     * @access protected
     * @return boolean true on success, otherwise a PEAR_ErrorStack object
     * @throws object PEAR_ErrorStack
     */
    function _buildHolidays()
    {
        /**
         * New Year's Day
         */
        $newYearsDay = new Date($this->_year . '-01-01');
        if ($newYearsDay->getDayOfWeek() == 0) {
            $this->_addHoliday('newYearsDay',
                               $this->_year . '-01-02',
                               'Substitute Bank Holiday in lieu of New Year\'s Day');
        } elseif ($newYearsDay->getDayOfWeek() == 6) {
            $this->_addHoliday('newYearsDay',
                               $this->_year . '-01-03',
                               'Substitute Bank Holiday in lieu of New Year\'s Day');
        } else {
            $this->_addHoliday('newYearsDay',
                               $newYearsDay,
                               'New Year\'s Day');
        }

        /*
         * Bug 19060
         */
        if ($this->_year == 2012) {
            $this->_addHoliday(
                'queensJubilee',
                new Date('2012-06-05'),
                'Queen\'s Jubilee');
        }

        /**
         * Easter Sunday
         */
        $easterDate = Date_Holidays_Driver_Christian::calcEaster($this->_year);

        /**
         * Good Friday
         */
        $goodFridayDate = new Date($easterDate);
        $goodFridayDate = $this->_addDays($easterDate, -2);
        $this->_addHoliday('goodFriday', $goodFridayDate, 'Good Friday');

        /**
         * Easter Monday
         */
        $this->_addHoliday('easterMonday',
                           $easterDate->getNextDay(),
                           'Easter Monday');

        /**
         * May Day Bank Holiday
         */
        $earlyMayDate = Date_Holidays_Driver::_calcFirstMonday(5);
        $this->_addHoliday('mayDay', $earlyMayDate, 'May Day Bank Holiday');

        /**
         * Bug 19060
         * substitute Spring Bank Holiday in 2012.
         * http://www.direct.gov.uk/en/Employment/Employees/Timeoffandholidays/DG_073741
         */
        if ($this->_year == 2012) {
            $this->_addHoliday(
                'springBank',
                new Date('2012-06-04'),
                'Spring Bank Holiday');
        } else {
            $springBankDate = Date_Holidays_Driver::_calcLastMonday(5);
            $this->_addHoliday('springBank', $springBankDate, 'Spring Bank Holiday');
        }

        /**
         * Summer Bank Holiday
         */
        $summerBankDate = Date_Holidays_Driver::_calcLastMonday(8);
        $this->_addHoliday('summerBank', $summerBankDate, 'Summer Bank Holiday');

        /**
         * Christmas and Boxing Day
         */
        $christmasDay = new Date($this->_year . '-12-25');
        if ($christmasDay->getDayOfWeek() == 0) {
            $this->_addHoliday('boxingDay', $this->_year . '-12-26', 'Boxing Day');
            $this->_addHoliday('christmasDay',
                               $this->_year . '-12-27',
                               'Substitute Bank Holiday in lieu of Christmas Day');
        } elseif ($christmasDay->getDayOfWeek() == 5) {
            $this->_addHoliday('christmasDay', $christmasDay, 'Christmas Day');
            $this->_addHoliday('boxingDay',
                               $this->_year . '-12-28',
                               'Substitute Bank Holiday in lieu of Boxing Day');
        } elseif ($christmasDay->getDayOfWeek() == 6) {
            $this->_addHoliday('christmasDay',
                               $this->_year . '-12-28',
                               'Substitute Bank Holiday in lieu of Christmas Day');
            $this->_addHoliday('boxingDay',
                               $this->_year . '-12-27',
                               'Substitute Bank Holiday in lieu of Boxing Day');
        } else {
            $this->_addHoliday('christmasDay', $christmasDay, 'Christmas Day');
            $this->_addHoliday('boxingDay', $this->_year . '-12-26', 'Boxing Day');
        }

        if (Date_Holidays::errorsOccurred()) {
            return 'Error in EnglandWales Driver';
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
        return array('gb', 'gbr');
    }
}
?>
