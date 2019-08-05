<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Driver for Christian holidays
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
 * @author   Carsten Lucke <luckec@tool-garage.de>
 * @license  http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @version  CVS: $Id$
 * @link     http://pear.php.net/package/Date_Holidays
 */

/**
 * class that calculates Christian holidays
 *
 * @category   Date
 * @package    Date_Holidays
 * @subpackage Driver
 * @author     Carsten Lucke <luckec@tool-garage.de>
 * @license    http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/Date_Holidays
 */
class Date_Holidays_Driver_Christian extends Date_Holidays_Driver {
    /**
     * this driver's name
     *
     * @access   protected
     * @var      string
     */
    var $_driverName = 'Christian';

    /**
     * Constructor
     *
     * Use the Date_Holidays::factory() method to construct an object of a
     * certain driver
     *
     * @access   protected
     */
    function Date_Holidays_Driver_Christian() {
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
         * Circumcision of Jesus
         */
        $this->_addHoliday('jesusCircumcision',
            $this->_year . '-01-01',
            'Circumcision of Jesus');

        /**
         * Epiphanias
         */
        $this->_addHoliday('epiphany', $this->_year . '-01-06', 'Epiphany');

        /**
         * Cleaning of Mariä
         */
        $this->_addHoliday('mariaCleaning',
            $this->_year . '-02-02',
            'Cleaning of Maria');

        /**
         * Josef's Day
         */
        $this->_addHoliday('josefsDay',
            $this->_year . '-03-19',
            'Josef\'s Day');

        /**
         * Maria Announcement
         */
        $this->_addHoliday('mariaAnnouncement',
            $this->_year . '-03-25',
            'Maria Announcement');

        /**
         * Easter Sunday
         */
        $easterDate = Date_Holidays_Driver_Christian::calcEaster($this->_year);
        $this->_addHoliday('easter', $easterDate, 'Easter Sunday');

        /**
         * Palm Sunday
         */
        $palmSundayDate = $this->_addDays($easterDate, -7);
        $this->_addHoliday('palmSunday', $palmSundayDate, 'Palm Sunday');

        /**
         * Passion Sunday
         */
        $passionSundayDate = $this->_addDays($palmSundayDate, -7);
        $this->_addHoliday('passionSunday', $passionSundayDate, 'Passion Sunday');

        /**
         * Painful Friday
         */
        $painfulFridayDate = $this->_addDays($palmSundayDate, -2);
        $this->_addHoliday('painfulFriday', $painfulFridayDate, 'Painful Friday');

        /**
         * White Sunday
         */
        $whiteSundayDate = $this->_addDays($easterDate, 7);
        $this->_addHoliday('whiteSunday', $whiteSundayDate, 'White Sunday');

        /**
         * Ash Wednesday
         */
        $ashWednesdayDate = $this->_addDays($easterDate, -46);
        $this->_addHoliday('ashWednesday', $ashWednesdayDate, 'Ash Wednesday');

        /**
         * Good Friday / Black Friday
         */
        $goodFridayDate = $this->_addDays($easterDate, -2);
        $this->_addHoliday('goodFriday', $goodFridayDate, 'Good Friday');

        /**
         * Green Thursday
         */
        $this->_addHoliday('greenThursday',
            $goodFridayDate->getPrevDay(),
            'Green Thursday');

        /**
         * Easter Monday
         */
        $this->_addHoliday('easterMonday',
            $easterDate->getNextDay(),
            'Easter Monday');

        /**
         * Whitsun (determines Whit Monday, Ascension Day and
         * Feast of Corpus Christi)
         */
        $whitsunDate = $this->_addDays($easterDate, 49);
        $this->_addHoliday('whitsun', $whitsunDate, 'Whitsun');

        /**
         * Request Sunday
         */
        $requestSunday = $this->_addDays($whitsunDate, -14);
        $this->_addHoliday('requestSunday', $requestSunday, 'Request Sunday');

        /**
         * Ascension Day
         */
        $ascensionDayDate = $this->_addDays($whitsunDate, -10);
        $this->_addHoliday('ascensionDay', $ascensionDayDate, 'Ascension Day');

        /**
         * Whit Monday
         */
        $this->_addHoliday('whitMonday', $whitsunDate->getNextDay(), 'Whit Monday');

        /**
         * Haunting of Mariä
         */
        $this->_addHoliday('mariaHaunting',
            $this->_year . '-05-31',
            'Haunting of Maria');

        /**
         * Trinitatis
         */
        $trinitatisDate = $this->_addDays($whitsunDate, 7);
        $this->_addHoliday('trinitatis',
            $trinitatisDate,
            'Trinitatis');

        /**
         * Feast of Corpus Christi
         */
        $corpusChristiDate = $this->_addDays($whitsunDate, 11);
        $this->_addHoliday('corpusChristi',
            $corpusChristiDate,
            'Feast of Corpus Christi');

        /**
         * Heart of Jesus
         *
         * Friday of the 3rd week past Whitsun
         */
        $heartJesusDate = $this->_addDays($whitsunDate, 19);
        $this->_addHoliday('heartJesus',
            $heartJesusDate,
            'Heart of Jesus celebration');

        /**
         * Johannis celebration
         */
        $this->_addHoliday('johannisCelebration',
            $this->_year . '-06-24',
            'Johannis celebration');

        /**
         * Petrus and Paulus
         */
        $this->_addHoliday('petrusAndPaulus',
            $this->_year . '-06-29',
            'Petrus and Paulus');

        /**
         * Ascension of Maria
         */
        $this->_addHoliday('mariaAscension',
            $this->_year . '-08-15',
            'Ascension of Maria');

        /**
         * Celebration of raising the Cross
         */
        $this->_addHoliday('crossRaising',
            $this->_year . '-09-14',
            'Celebration of raising the Cross');

        /**
         * Thanks Giving
         *
         * Sunday past Michaelis (29. September)
         */
        $michaelisDate = new Date($this->_year . '-09-29');
        $dayOfWeek = $michaelisDate->getDayOfWeek();
        $michaelisDate = $this->_addDays($michaelisDate, 7 - $dayOfWeek);
        $thanksGivingDate = $michaelisDate;
        $this->_addHoliday('thanksGiving', $thanksGivingDate, 'Thanks Giving');

        /**
         * Kermis
         *
         * 3rd Sunday in October
         */
        $kermisDate = new Date($this->_year . '-10-01');
        $dayOfWeek = $kermisDate->getDayOfWeek();
        if ($dayOfWeek != 0) {
            $kermisDate = $this->_addDays($kermisDate, 7 - $dayOfWeek);
        }
        $kermisDate = $this->_addDays($kermisDate, 14);
        $this->_addHoliday('kermis', $kermisDate, 'Kermis');

        /**
         * Reformation Day
         */
        $this->_addHoliday('reformationDay',
            $this->_year . '-10-31',
            'Reformation Day');

        /**
         * All Saints' Day
         */
        $this->_addHoliday('allSaintsDay',
            $this->_year . '-11-01',
            'All Saints\' Day');

        /**
         * All Souls' Day
         */
        $this->_addHoliday('allSoulsDay',
            $this->_year . '-11-02',
            'All Souls\' Day');

        /**
         * Martin's Day
         */
        $this->_addHoliday('martinsDay', $this->_year . '-11-11', 'Martin\'s Day');

        /**
         * 4th Advent
         */
        $Advent4Date = new Date($this->_year . '-12-25');
        $dayOfWeek = $Advent4Date->getDayOfWeek();
        if ($dayOfWeek == 0) {
            $dayOfWeek = 7;
        }
        $Advent4Date = $this->_addDays($Advent4Date, -$dayOfWeek);
        $this->_addHoliday('advent4', $Advent4Date, '4th Advent');

        /**
         * 1st Advent
         */
        $Advent1Date = $this->_addDays($Advent4Date, -21);
        $this->_addHoliday('advent1', $Advent1Date, '1st Advent');

        /**
         * 2nd Advent
         */
        $Advent2Date = $this->_addDays($Advent4Date, -14);
        $this->_addHoliday('advent2', $Advent2Date, '2nd Advent');

        /**
         * 3rd Advent
         */
        $Advent3Date = $this->_addDays($Advent4Date, -7);
        $this->_addHoliday('advent3', $Advent3Date, '3rd Advent');

        /**
         * Death' Sunday
         */
        $deathSundayDate = $this->_addDays($Advent1Date, -7);
        $this->_addHoliday('deathSunday', $deathSundayDate, 'Death\' Sunday');

        /**
         * Day of Repentance
         */
        $dayOfRepentance = $this->_addDays($deathSundayDate, -4);
        $this->_addHoliday('dayOfRepentance', $dayOfRepentance, 'Day of Repentance');

        /**
         * St. Nicholas' Day
         */
        $this->_addHoliday('stNicholasDay',
            $this->_year . '-12-06',
            'St. Nicholas\' Day');

        /**
         * Maria' conception
         */
        $this->_addHoliday('mariaConception',
            $this->_year . '-12-08',
            'Conception of Maria');

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
            return 'Error in Christian holidays Driver';
        }
        return true;
    }

    /**
     * Calculates date for Easter using the Gaussian algorithm.
     *
     * @param int $year year
     *
     * @static
     * @access   public
     * @return   object Date
     */
    public static function calcEaster($year) {
        if (function_exists("easter_days")) {
            $easter = new Date();
            $easter->setDate(
                strtotime("$year-03-21 + " . easter_days($year) . " days"),
                DATE_FORMAT_UNIXTIME
            );
            return $easter;
        }
        // golden number
        $golden = null;
        $century = null;
        // 23-Epact (modulo 30)
        $epact = null;
        // number of days from 21 March to the Paschal Full Moon
        $i = null;
        // weekday of the Full Moon (0=Sunday,...)
        $j = null;

        if ($year > 1582) {
            $golden = $year % 19;
            $century = floor($year / 100);
            $l = floor($century / 4);
            $epact = ($century - $l - floor((8 * $century + 13) / 25)
                    + 19 * $golden + 15) % 30;
            $i = $epact - floor($epact / 28) * (1 - floor($epact / 28) *
                    floor(29 / ($epact + 1)) * floor((21 - $golden) / 11));
            $j = ($year + floor($year / 4) + $i + 2 - $century + $l);
            $j = $j % 7;
        } else {
            $golden = $year % 19;
            $i = (19 * $golden + 15) % 30;
            $j = ($year + floor($year / 4) + $i) % 7;
        }
        $l = $i - $j;
        $month = 3 + floor(($l + 40) / 44);
        $day = $l + 28 - 31 * floor($month / 4);

        $date = new Date(sprintf('%04d-%02d-%02d', $year, $month, $day));
        return $date;
    }
}
