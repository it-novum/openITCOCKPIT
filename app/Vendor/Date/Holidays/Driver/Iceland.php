<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Driver for determining holidays in Iceland.
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
 * @version  CVS: $Id: Iceland.php,v 1.11 2009/03/15 20:17:00 kguest Exp $
 * @link     http://pear.php.net/package/Date_Holidays
 */

require_once 'Christian.php';

/**
 * Driver class that calculates Icelandic holidays
 * deriving most calculations from 'Public holidays in Iceland' document
 * on http://en.wikipedia.org/wiki/Public_holidays_in_Iceland
 *
 * @category   Date
 * @package    Date_Holidays
 * @subpackage Driver
 * @author     Ken Guest <kguest@php.net>
 * @license    http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @version    CVS: $Id: Iceland.php,v 1.11 2009/03/15 20:17:00 kguest Exp $
 * @link       http://pear.php.net/package/Date_Holidays
 */
class Date_Holidays_Driver_Iceland extends Date_Holidays_Driver
{
    /**
     * this driver's name
     *
     * @access   protected
     * @var      string
     */
    var $_driverName = 'Iceland';

    /**
     * Constructor
     *
     * Use the Date_Holidays::factory() method to construct an object of a
     * certain driver
     *
     * @access   protected
     */
    function Date_Holidays_Driver_Iceland()
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
        $easterDate = Date_Holidays_Driver_Iceland::calcEaster($this->_year);
        /**
         * New Year's Day
         */
        $this->_addHoliday('newYearsDay',
                           $this->_year . '-01-01',
                           'New Year\'s Day');
        $this->_addTranslationForHoliday('newYearsDay', 'is_IS', 'Nýársdagur');

        /**
         * Epiphany
         */
        $this->_addHoliday('epiphany', $this->_year . '-01-06', 'Epiphany');
        $this->_addTranslationForHoliday('epiphany', 'is_IS', 'Þrettándinn');

        /**
         * Husband's Day
         * From http://www.isholf.is/gullis/jo/feasts_and_celebrations.htm
         * Þorri is one of the old Icelandic months. It always begins on
         * a Friday, between the 19th and the 25th of January, and ends on
         * a Saturday between the 18th and 24th of February. The first day
         * of Þorri is called Bóndadagur or "Husband's Day/Farmer's Day"
         */
        $hdate = new Date($this->_year . "-01-19");
        while ($hdate->getDayOfWeek() != 5) {
            $hdate = $hdate->getNextDay();
        }
        $this->_addHoliday('husbandsDay', $hdate, 'Husband\'s Day');
        $this->_addTranslationForHoliday('husbandsDay', 'is_IS', 'Bóndadagur');

        /**
         * Woman's Day
         * Calculate Sunday in the 18th week of winter, ie between Feb 18-24.
         */
        $wdate = new Date($this->_year . "-02-18");
        while ($wdate->getDayOfWeek() != 0) {
            $wdate = $wdate->getNextDay();
        }
        $this->_addHoliday('womansDay', $wdate, 'Woman\'s Day');
        $this->_addTranslationForHoliday('womansDay', 'is_IS', 'Konudagur');

        $shroveMondayDate = $this->_addDays($easterDate, -41);
        $this->_addHoliday('shroveMonday', $shroveMondayDate, 'Shrove Monday');
        $this->_addTranslationForHoliday('shroveMonday', 'is_IS', 'Bolludagur');

        $shroveTuesdayDate = $this->_addDays($easterDate, -40);
        $this->_addHoliday('shroveTuesday', $shroveTuesdayDate, 'Shrove Tuesday');
        $this->_addTranslationForHoliday('shroveTuesday', 'is_IS', 'Sprengidagur');

        $ashWednesdayDate = $this->_addDays($easterDate, -39);
        $this->_addHoliday('ashWednesday', $ashWednesdayDate, 'Ash Wednesday');
        $this->_addTranslationForHoliday('ashWednesday', 'is_IS', 'Öskudagur');

        /**
         * Palm Sunday
         */
        $palmSundayDate = $this->_addDays($easterDate, -7);
        $this->_addHoliday('palmSunday', $palmSundayDate, 'Palm Sunday');
        $this->_addTranslationForHoliday('palmSunday', 'is_IS', 'Pálmasunnudagur');

        /**
         * Maundy Thursday
         */
        $maundyThursdayDate = $this->_addDays($easterDate, -3);
        $this->_addHoliday('maundyThursday', $maundyThursdayDate, 'Maundy Thursday');
        $this->_addTranslationForHoliday('maundyThursday', 'is_IS', 'Skírdagur');
        /**
         * Good Friday / Black Friday
         */
        $goodFridayDate = $this->_addDays($easterDate, -2);
        $this->_addHoliday('goodFriday', $goodFridayDate, 'Good Friday');
        $this->_addTranslationForHoliday('goodFriday',
                                         'is_IS',
                                         'Föstudaginn langi');

        /**
         * Easter Day
         */
        $this->_addHoliday('easter', $easterDate, 'Easter Day');
        $this->_addTranslationForHoliday('easter', 'is_IS', 'Páskadagur');

        /**
         * Easter Monday
         */
        $this->_addHoliday('easterMonday',
                           $easterDate->getNextDay(),
                           'Easter Monday');
        $this->_addTranslationForHoliday('easterMonday',
                                         'is_IS',
                                         'Annar í páskum');

        /**
         * First Day of Summer
         * First Thursday after 18 April
         */
        $juneDate  = new Date($this->_year . '-04-18');
        $dayOfWeek = $juneDate->getDayOfWeek();
        $midSummerDate = $this->_addDays($juneDate, 4 - $dayOfWeek);
        $this->_addHoliday('firstDayOfSummer',
                           $midSummerDate,
                           'First Day of Summer');
        $this->_addTranslationForHoliday('firstDayOfSummer',
                                         'is_IS',
                                         'Sumardagurinn fyrsti');

        $mayDay = new Date($this->_year . '-05-01');
        $this->_addHoliday('mayDay', $mayDay, 'May Day');
        $this->_addTranslationForHoliday('mayDay', 'is_IS', 'Verkalýðsdagurinn');

        $mothersDay = new Date($this->_year . '-05-13');
        $this->_addHoliday('mothersDay', $mothersDay, 'Mothers\' Day');
        $this->_addTranslationForHoliday('mothersDay', 'is_IS', 'Mæðradagurinn');


        $whitsunDate = $this->_addDays($easterDate, 49);
        $this->_addHoliday('whitsun', $whitsunDate, 'White Sunday');
        $this->_addTranslationForHoliday('whitsun', 'is_IS', 'Hvítasunnudagur');

        /**
         * Whit Monday
         */
        $this->_addHoliday('whitMonday', $whitsunDate->getNextDay(), 'White Monday');
        $this->_addTranslationForHoliday('whitMonday',
                                         'is_IS',
                                         'Annar í hvítasunnu');

        $ascensionDayDate = $this->_addDays($whitsunDate, -10);
        $this->_addHoliday('ascensionDay', $ascensionDayDate, 'Ascension Day');
        $this->_addTranslationForHoliday('ascensionDay',
                                         'is_IS',
                                         'Uppstigningardagur');


        $this->_addHoliday('seamansDay',
                           $this->_year . '-06-03',
                           'The Seamen\'s Day');
        $this->_addTranslationForHoliday('seamansDay', 'is_IS', 'Sjómannadagurinn');

        $this->_addHoliday('nationalDay',
                           $this->_year . '-06-17',
                           'Icelandic National Day');
        $this->_addTranslationForHoliday('nationalDay',
                                         'is_IS',
                                         'Lýðveldisdagurinn');

        $this->_addHoliday('jonsMass', $this->_year . '-06-24', 'Jón\'s Mass');
        $this->_addTranslationForHoliday('jonsMass', 'is_IS', 'Jónsmessa');

        $augDate   = new Date($this->_year . '-08-01');
        $dayOfWeek = $augDate->getDayOfWeek();
        $commerceDate = $this->_addDays($augDate, 6 - $dayOfWeek);
        $this->_addHoliday('commerceDay', $commerceDate, 'Commerce Day');
        $this->_addTranslationForHoliday('commerceDay',
                                         'is_IS',
                                         'Frídagur verslunarmanna');

        $this->_addHoliday('languageDay',
                           $this->_year . '-11-16',
                           'Icelandic Language Day');
        $this->_addTranslationForHoliday('languageDay',
                                         'is_IS',
                                         'Dagur íslenskrar tungu');

        $this->_addHoliday('independenceDay',
                           $this->_year . '-12-01',
                           'Independence Day');
        $this->_addTranslationForHoliday('independenceDay',
                                         'is_IS',
                                         'Fullveldisdagurinn');


        /**
         * Christmas Eve
         */
        $this->_addHoliday('christmas', $this->_year . '-12-24', 'Christmas Eve');
        $this->_addTranslationForHoliday('christmas', 'is_IS', 'Aðfangadagur');

        $this->_addHoliday('christmasDay', $this->_year . '-12-25', 'Christmas Day');
        $this->_addTranslationForHoliday('christmasDay', 'is_IS', 'Jóladagur');

        $this->_addHoliday('secondChristmasDay', $this->_year . '-12-26', 'Boxing Day');
        $this->_addTranslationForHoliday('secondChristmasDay',
                                         'is_IS',
                                         'Annar í jólum');



        /**
         * New Year's Eve
         */
        $this->_addHoliday('newYearsEve',
                           $this->_year . '-12-31',
                           'New Year\'s Eve');
        $this->_addTranslationForHoliday('newYearsEve', 'is_IS', 'Gamlárskvöld');


        if (Date_Holidays::errorsOccurred()) {
            return 'Error in Iceland Driver';
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
        return array('is', 'isl');
    }
}
?>
