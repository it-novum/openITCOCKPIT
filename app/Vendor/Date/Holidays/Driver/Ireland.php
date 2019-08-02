<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Driver for determining holidays in Ireland.
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
 * @version  CVS: $Id: Ireland.php 300625 2010-06-20 22:12:46Z kguest $
 * @link     http://pear.php.net/package/Date_Holidays
 */

require_once 'Christian.php';

/**
 * Driver class that calculates Irish holidays
 * deriving most calculations from 'Public holidays in Ireland' document
 * on http://www.citizensinformation.ie/
 *
 * @category   Date
 * @package    Date_Holidays
 * @subpackage Driver
 * @author     Ken Guest <kguest@php.net>
 * @license    http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @version    CVS: $Id: Ireland.php 300625 2010-06-20 22:12:46Z kguest $
 * @link       http://pear.php.net/package/Date_Holidays
 */
class Date_Holidays_Driver_Ireland extends Date_Holidays_Driver
{
    /**
     * this driver's name
     *
     * @access   protected
     * @var      string
     */
    var $_driverName = 'Ireland';

    /**
     * Constructor
     *
     * Use the Date_Holidays::factory() method to construct an object of a
     * certain driver
     *
     * @access   protected
     */
    function Date_Holidays_Driver_Ireland()
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
        $this->_addTranslationForHoliday('newYearsDay', 'ga_IE', 'Lá na Caille');

        /**
         * Epiphany
         */
        $this->_addHoliday('epiphany', $this->_year . '-01-06', 'Epiphany');
        $this->_addTranslationForHoliday('epiphany', 'ga_IE', 'Nollag na mBan');


        /**
         * St Patrick's Day.
         */
        $this->_addHoliday('stPatricksDay',
                           $this->_year . '-03-17',
                           'Saint Patrick\'s Day');
        $this->_addTranslationForHoliday('stPatricksDay',
                                         'ga_IE',
                                         'Lá Fhéile Pádraig');

        /**
         * Easter Sunday
         */
        $easterDate = Date_Holidays_Driver_Ireland::calcEaster($this->_year);
        $this->_addHoliday('easter', $easterDate, 'Easter Sunday');
        $this->_addTranslationForHoliday('easter', 'ga_IE', 'Domhnach Cásca');

        /**
         * Good Friday / Black Friday
         */
        $goodFridayDate = $this->_addDays($easterDate, -2);
        $this->_addHoliday('goodFriday', $goodFridayDate, 'Good Friday');
        $this->_addTranslationForHoliday('goodFriday',
                                         'ga_IE',
                                         'Aoine Cásca');

        /**
         * Easter Monday
         */
        $this->_addHoliday('easterMonday',
                           $easterDate->getNextDay(),
                           'Easter Monday');
        $this->_addTranslationForHoliday('easterMonday',
                                         'ga_IE',
                                         'Luan Cásca');

        /**
         * Mothers Day
         */
        $this->_addHoliday('mothersDay',
                           $this->_addDays($easterDate, -21),
                           'Mother\'s Day');


        /**
         * May Bank Holiday
         */
        $dn = $this->_calcFirstMonday('05');
        $this->_addHoliday('mayDayBankHoliday', $dn, 'May Bank Holiday');
        $this->_addTranslationForHoliday('mayDayBankHoliday',
                                         'ga_IE',
                                         'Lá Bealtaine');

        /**
         * Pentecost (determines Whit Monday, Ascension Day and
         * Feast of Corpus Christi)
         */
        $pentecostDate = $this->_addDays($easterDate, 49);
        $this->_addHoliday('pentecost', $pentecostDate, 'Pentecost');
        $this->_addTranslationForHoliday('pentecost',
                                         'ga_IE',
                                         'An Chincís');

        /**
         * Ascension Day
         */
        $ascensionDayDate = $this->_addDays($pentecostDate, -10);
        $this->_addHoliday('ascensionDay', $ascensionDayDate, 'Ascension Day');
        $this->_addTranslationForHoliday('ascensionDay',
                                         'ga_IE',
                                         'Deascabhála');

        /**
         * June Bank Holiday
         */
        $dn = $this->_calcFirstMonday('06');
        $this->_addHoliday('juneBankHoliday', $dn, 'June Bank Holiday');
        $this->_addTranslationForHoliday('juneBankHoliday',
                                         'ga_IE',
                                         'Lá Meitheamh');


        $dn = $this->_calcNthWeekDayInMonth(3, 0, 6);
        $this->_addHoliday('fathersDay', $dn, 'Father\'s Day');

        /**
         * Midsummer
         * Saturday past 20th, June
         */
        $juneDate  = new Date($this->_year . '-06-20');
        $dayOfWeek = $juneDate->getDayOfWeek();
        $midSummerDate = $this->_addDays($juneDate, 6 - $dayOfWeek);
        $this->_addHoliday('midSummer', $midSummerDate, 'Midsummer Day');
        $this->_addTranslationForHoliday('midSummer',
                                         'ga_IE',
                                         'Lá Fhéile Eoin');

        /**
         * August Bank Holiday
         */
        $dn = $this->_calcFirstMonday('08');
        $this->_addHoliday('augustBankHoliday', $dn, 'August Bank Holiday');
        $this->_addTranslationForHoliday('augustBankHoliday',
                                         'ga_IE',
                                         'Lá Lúnasa');

        /**
         * October Bank Holiday
         */
        $dn = $this->_calcLastMonday('10');
        $this->_addHoliday('octoberBankHoliday', $dn, 'October Bank Holiday');
        $this->_addTranslationForHoliday('octoberBankHoliday',
                                         'ga_IE',
                                         'Lá Samhna');

        /**
         * Christmas Eve
         */
        $this->_addHoliday('christmasEve', $this->_year . '-12-24', 'Christmas Eve');
        $this->_addTranslationForHoliday('christmasEve',
                                         'ga_IE',
                                         'Oíche Nollag');

        /**
         * Christmas day
         */
        $this->_addHoliday('christmasDay', $this->_year . '-12-25', 'Christmas Day');
        $this->_addTranslationForHoliday('christmasDay',
                                         'ga_IE',
                                         'Lá Nollag');

        /**
         * St. Stephen's Day
         */
        $this->_addHoliday('stStephensDay',
                           $this->_year . '-12-26',
                           'Saint Stephen\'s Day');
        $this->_addTranslationForHoliday('stStephensDay',
                                         'ga_IE',
                                         'Lá Fhéile Stiofáin');

        /**
         * New Year's Eve
         */
        $this->_addHoliday('newYearsEve',
                           $this->_year . '-12-31',
                           'New Year\'s Eve');
        $this->_addTranslationForHoliday('newYearsEve',
                                         'ga_IE',
                                         'Oíche Chinn Bliana');

        if (Date_Holidays::errorsOccurred()) {
            return 'Error in Ireland Driver';
        }
        return true;
    }

    /**
     * Calculates the date for Easter. Actually this methods delegates the
     * calculation to the {@link Date_Holidays_Driver_Christian#calcEaster()}
     * method.
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
        return array('ie', 'irl');
    }
}
