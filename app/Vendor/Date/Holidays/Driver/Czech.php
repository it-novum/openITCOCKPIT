<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Driver for holidays in Czech Republic
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
 * @author   Martin Zdrahal <zdrahal@ipnp.mff.cuni.cz>
 * @license  http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @version  CVS: $Id$
 * @link     http://pear.php.net/package/Date_Holidays
 */

/**
 * Requires Christian driver
 */
require_once 'Christian.php';

/**
 * class that calculates Czech holidays
 * basen on Austria.php file by Klemens Ullmann
 * advent determination modified according to the correct rules
 *
 * @category   Date
 * @package    Date_Holidays
 * @subpackage Driver
 * @author     Martin Zdrahal <zdrahal@ipnp.mff.cuni.cz>
 * @license    http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @link       http://pear.php.net/package/Date_Holidays
 */
class Date_Holidays_Driver_Czech extends Date_Holidays_Driver
{
    /**
     * this driver's name
     *
     * @access   protected
     * @var      string
     */
    var $_driverName = 'Czech';

    /**
     * Constructor
     *
     * Use the Date_Holidays::factory() method to construct an object of a certain
     * driver
     *
     * @access   protected
     */
    function Date_Holidays_Driver_Czech()
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
            'Nový rok'
        );

        /**
         * Restoration Day of the Independent Czech State
         */
        $this->_addHoliday(
            'IndependentCzechState',
            $this->_year . '-01-01',
            'Den obnovy samostatného českého státu'
        );

        /**
         * Epiphanias
         */
        $this->_addHoliday(
            'epiphany',
            $this->_year . '-01-06',
            'Tři králové'
        );

        /**
         * Valentine´s Day
         */
        $this->_addHoliday(
            'valentinesDay',
            $this->_year . '-02-14',
            'Svatý Valentýn'
        );

        /**
         * Easter Sunday
         */
        $easterDate = Date_Holidays_Driver_Christian::calcEaster($this->_year);
        $this->_addHoliday(
            'easter',
            $easterDate,
            'Velikonoční neděle'
        );

        /**
         * Ash Wednesday
         */
        $ashWednesday = $this->_addDays($easterDate, -46);
        $this->_addHoliday(
            'ashWednesday',
            $ashWednesday,
            'Popeleční středa'
        );

        /**
         * Palm Sunday
         */
        $palmSunday = $this->_addDays($easterDate, -7);
        $this->_addHoliday(
            'palmSunday',
            $palmSunday,
            'Květná neděle'
        );

        /**
         * Maundy Thursday
         */
        $maundyThursday = $this->_addDays($easterDate, -3);
        $this->_addHoliday(
            'maundyThursday',
            $maundyThursday,
            'Zelený čtvrtek'
        );

        /**
         * Good Friday
         */
        $goodFriday = $this->_addDays($easterDate, -2);
        $this->_addHoliday(
            'goodFriday',
            $goodFriday,
            'Velký pátek'
        );

        /**
         * Easter Monday
         */
        $this->_addHoliday(
            'easterMonday',
            $easterDate->getNextDay(),
            'Velikonoční pondělí'
        );

        /**
         * Day of Work
         */
        $this->_addHoliday(
            'dayOfWork',
            $this->_year . '-05-01',
            'Svátek práce'
        );

        /**
         * Liberation Day
         */
        $this->_addHoliday(
            'libarationDay',
            $this->_year . '-05-08',
            'Den vítězství'
        );

        /**
         * Mothers Day
         */
        $mothersDay = $this->_calcFirstMonday("05");
        $mothersDay = $mothersDay->getPrevDay();
        $mothersDay = $this->_addDays($mothersDay, 7);
        $this->_addHoliday(
            'mothersDay',
            $mothersDay,
            'Den matek'
        );

        /**
         * Ascension Day
         */
        $ascensionDate = $this->_addDays($easterDate, 39);
        $this->_addHoliday(
            'ascensionDate',
            $ascensionDate,
            'Nanebevstoupení Páně'
        );

        /**
         * Whitsun (determines Whit Monday, Ascension Day and
         * Feast of Corpus Christi)
         */
        $whitsunDate = $this->_addDays($easterDate, 49);
        $this->_addHoliday(
            'whitsun',
            $whitsunDate,
            'Svatodušní neděle'
        );

        /**
         * Whit Monday
         */
        $this->_addHoliday(
            'whitMonday',
            $whitsunDate->getNextDay(),
            'Svatodušní pondělí'
        );

        /**
         * Corpus Christi
         */
        $corpusChristi = $this->_addDays($easterDate, 60);
        $this->_addHoliday(
            'corpusChristi',
            $corpusChristi,
            'Corpus Christi'
        );

        /**
         * Saints Cyril and Methodius Day
         */
        $this->_addHoliday(
            'CyrilMethodius',
            $this->_year . '-07-05',
            'Den slovanských věrozvěstů Cyrila a Metoděje'
        );

        /**
         * Jan Hus Day
         */
        $this->_addHoliday(
            'HusDay',
            $this->_year . '-07-06',
            'Den upálení mistra Jana Husa'
        );


        /**
         * Ascension of Maria
         */
        $this->_addHoliday(
            'mariaAscension',
            $this->_year . '-08-15',
            'Nanebevzetí panny Marie'
        );

        /**
         * Czech Statehood Day
         */
        $this->_addHoliday(
            'WenceslasDay',
            $this->_year . '-09-28',
            'Den české státnosti'
        );

        /**
         * Independent Czechoslovak State Day
         */
        $this->_addHoliday(
            'nationalDayCzechoslovakia',
            $this->_year . '-10-28',
            'Den vzniku samostatného československého státu'
        );

        /**
         * All Saints' Day
         */
        $this->_addHoliday(
            'allSaintsDay',
            $this->_year . '-11-01',
            'Svátek všech svatých'
        );

        /**
         * All Souls´ Day
         */
        $this->_addHoliday(
            'allSoulsDay',
            $this->_year . '-11-02',
            'Památka zesnulých'
        );

        /**
         * Struggle for Freedom and Democracy Day
         */
        $this->_addHoliday(
            'FreedomDay',
            $this->_year . '-11-17',
            'Den boje za svobodu a demokracii'
        );

        /**
         * Veterans Day
         */
        $this->_addHoliday(
            'veteranDay',
            $this->_year . '-11-11',
            'Den válečných veteránů'
        );

        /**
         * Santa Claus
         */
        $this->_addHoliday(
            'santasDay',
            $this->_year . '-12-06',
            'Svatý Mikuláš'
        );

        /**
         * 1. Advent
         */
        $firstAdv = new Date($this->_year . '-12-03');
        $dayOfWeek = $firstAdv->getDayOfWeek();
        $firstAdv = $this->_addDays($firstAdv, -$dayOfWeek);
        $this->_addHoliday(
            'firstAdvent',
            $firstAdv,
            '1. neděle adventní'
        );

        /**
         * 2. Advent
         */
        $secondAdv = $this->_addDays($firstAdv, 7);
        $this->_addHoliday(
            'secondAdvent',
            $secondAdv,
            '2. neděle adventní'
        );

        /**
         * 3. Advent
         */
        $thirdAdv = $this->_addDays($firstAdv, 14);
        $this->_addHoliday(
            'thirdAdvent',
            $thirdAdv,
            '3. neděle adventní'
        );

        /**
         * 4. Advent
         */
        $fourthAdv = $this->_addDays($firstAdv, 21);
        $this->_addHoliday(
            'fourthAdvent',
            $fourthAdv,
            '4. neděle adventní'
        );

        /**
         * Christmas Eve
         */
        $this->_addHoliday(
            'christmasEve',
            $this->_year . '-12-24',
            'Štědrý den'
        );

        /**
         * Christmas day
         */
        $this->_addHoliday(
            'christmasDay',
            $this->_year . '-12-25',
            '1. svátek vánoční'
        );

        /**
         * Boxing day
         */
        $this->_addHoliday(
            'boxingDay',
            $this->_year . '-12-26',
            '2. svátek vánoční'
        );

        /**
         * New Year´s Eve
         */
        $this->_addHoliday(
            'newYearsEve',
            $this->_year . '-12-31',
            'Silvestr'
        );

        if (Date_Holidays::errorsOccurred()) {
            return 'Error in Czech Driver';
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
        return array('cz');
    }
}
?>
