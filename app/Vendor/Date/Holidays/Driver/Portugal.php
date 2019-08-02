<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Driver for holidays in Portugal
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
 * @author   Stephan Schmidt <schst@php-tools.net>
 * @author   Carsten Lucke <luckec@tool-garage.de>
 * @license  http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @version  CVS: $Id: Porugal.php 277207 2009-03-15 20:17:00Z kguest $
 * @link     http://pear.php.net/package/Date_Holidays
 */

/**
 * Requires Christian driver
 */
require_once 'Christian.php';

/**
 * class that calculates Portugal holidays
 *
 * @category   Date
 * @package    Date_Holidays
 * @subpackage Driver
 * @author     Klemens Ullmann <klemens@ull.at>
 * @license    http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @version    CVS: $Id: Portugal.php 277207 2009-03-15 20:17:00Z kguest $
 * @link       http://pear.php.net/package/Date_Holidays
 */
class Date_Holidays_Driver_Portugal extends Date_Holidays_Driver {
    /**
     * this driver's name
     *
     * @access   protected
     * @var      string
     */
    var $_driverName = 'Portugal';

    /**
     * Constructor
     *
     * Use the Date_Holidays::factory() method to construct an object of a certain
     * driver
     *
     * @access   protected
     */
    function Date_Holidays_Driver_Portugal() {
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
        $this->_addHoliday('newYearsDay', $this->_year . '-01-01', 'Ano Novo');

        /**
         * Epiphanias
         */
        $this->_addHoliday('epiphany', $this->_year . '-01-06', 'Dia de Reis');

        /**
         * Valentine Day
         */
        $this->_addHoliday(
            'valentinesDay',
            $this->_year . '-02-14',
            'Dia dos Namorados'
        );

        /**
         * Easter Sunday
         */
        $easterDate = Date_Holidays_Driver_Christian::calcEaster($this->_year);
        $this->_addHoliday('easter', $easterDate, 'Páscoa');

        /**
         * Carnival
         */
        $carnival = $this->_addDays($easterDate, -47);
        $this->_addHoliday('carnival', $carnival, 'Carnaval');

        /**
         * Ash Wednesday
         */
        $ashWednesday = $this->_addDays($easterDate, -46);
        $this->_addHoliday('ashWednesday', $ashWednesday, 'Quarta-feira de Cinzas');

        /**
         * Palm Sunday
         */
        $palmSunday = $this->_addDays($easterDate, -7);
        $this->_addHoliday('palmSunday', $palmSunday, 'Dia de Ramos');

        /**
         * Maundy Thursday
         */
        $maundyThursday = $this->_addDays($easterDate, -3);
        $this->_addHoliday('maundyThursday', $maundyThursday, 'Quinta-feira Santa');

        /**
         * Good Friday
         */
        $goodFriday = $this->_addDays($easterDate, -2);
        $this->_addHoliday('goodFriday', $goodFriday, 'Sexta-feira Santa');

        /**
         * Easter Monday
         */
        $this->_addHoliday(
            'easterMonday',
            $easterDate->getNextDay(),
            'Segunda de Páscoa'
        );

        /**
         * Fathers Day
         */
        $this->_addHoliday('fathersDay', $this->_year . '-03-19', 'Dia do Pai');

        /**
         * Liberty Day (In 25 April 1974 - Revolution of the Carnations)
         */
        $this->_addHoliday(
            'libertyDayPortugal',
            $this->_year . '-04-25',
            'Dia da Liberdade'
        );

        /**
         * Day of Work
         */
        $this->_addHoliday(
            'dayOfWork',
            $this->_year . '-05-01',
            'Dia do Trabalhador'
        );

        /**
         * Portugal National Day
         * (In 1580 - Day of the death of Luís Vaz de Camões poet)
         */
        $this->_addHoliday(
            'nationalDayPortugal',
            $this->_year . '-06-10',
            'Dia de Portugal'
        );

        /**
         * Mothers Day
         */
        $mothersDay = $this->_calcFirstMonday("05");
        $mothersDay = $mothersDay->getPrevDay();
        $mothersDay = $this->_addDays($mothersDay, 7);
        $this->_addHoliday('mothersDay', $mothersDay, 'Dia da Mãe');

        /**
         * Ascension Day
         */
        $ascensionDate = $this->_addDays($easterDate, 39);
        $this->_addHoliday('ascensionDate', $ascensionDate, 'Dia da Ascensão');

        /**
         * Whitsun (determines Whit Monday, Ascension Day and
         * Feast of Corpus Christi)
         */
        $whitsunDate = $this->_addDays($easterDate, 49);
        $this->_addHoliday('whitsun', $whitsunDate, 'Pentecostes');

        /**
         * Whit Monday
         */
        $this->_addHoliday(
            'whitMonday',
            $whitsunDate->getNextDay(),
            'Segunta-feira de Pentecostes'
        );

        /**
         * Corpus Christi
         */
        $corpusChristi = $this->_addDays($easterDate, 60);
        $this->_addHoliday('corpusChristi', $corpusChristi, 'Corpo de Deus');

        /**
         * Ascension of Maria
         */
        $this->_addHoliday(
            'mariaAscension',
            $this->_year . '-08-15',
            'Assunção de Maria'
        );

        /**
         * Republic Day (In 1910 - End of Monarchy)
         */
        $this->_addHoliday(
            'republicDayPortugal',
            $this->_year . '-10-05',
            'Implantação da República'
        );

        /**
         * All Saints' Day
         */
        $this->_addHoliday(
            'allSaintsDay',
            $this->_year . '-11-01',
            'Todos os Santos'
        );

        /**
         * All Souls Day TODO
         */
        $this->_addHoliday('allSoulsDay', $this->_year . '-11-02', 'Allerseelen');

        /**
         * Portugal independence day (In 1640)
         */
        $this->_addHoliday(
            'independenceOfPortugal',
            $this->_year . '-12-01',
            'Restauração da Independência'
        );

        /**
         * Santa Claus
         */
        $this->_addHoliday(
            'santasDay',
            $this->_year . '-12-06',
            'Dia de S. Nicolau'
        );

        /**
         * Immaculate Conception
         */
        $this->_addHoliday(
            'immaculateConceptionDay',
            $this->_year . '-12-08',
            'Imaculada Conceição'
        );

        /**
         * Sunday in commemoration of the dead (sundayIcotd) TODO
         */
        $sundayIcotd = $this->_calcFirstMonday(12);
        $sundayIcotd = $this->_addDays($this->_calcFirstMonday(12), -8);
        $this->_addHoliday(
            'sundayIcotd',
            $sundayIcotd,
            'Totensonntag'
        );

        /**
         * 1. Advent
         */
        $firstAdv = $this->_calcFirstMonday(12);
        $firstAdv = $firstAdv->getPrevDay();
        $this->_addHoliday(
            'firstAdvent',
            $firstAdv,
            '1. Advento'
        );

        /**
         * 2. Advent
         */
        $secondAdv = $this->_addDays($firstAdv, 7);
        $this->_addHoliday(
            'secondAdvent',
            $secondAdv,
            '2. Advento'
        );

        /**
         * 3. Advent
         */
        $thirdAdv = $this->_addDays($firstAdv, 14);
        $this->_addHoliday(
            'thirdAdvent',
            $thirdAdv,
            '3. Advento'
        );

        /**
         * 4. Advent
         */
        $fourthAdv = $this->_addDays($firstAdv, 21);
        $this->_addHoliday(
            'fourthAdvent',
            $fourthAdv,
            '4. Advento'
        );

        /**
         * Christmas Eve
         */
        $this->_addHoliday('christmasEve', $this->_year . '-12-24', 'Consoada');

        /**
         * Christmas day
         */
        $this->_addHoliday('christmasDay', $this->_year . '-12-25', 'Natal');

        /**
         * New Year´s Eve
         */
        $this->_addHoliday(
            'newYearsEve',
            $this->_year . '-12-31',
            'Véspera de Ano Novo'
        );

        if (Date_Holidays::errorsOccurred()) {
            return 'Error in Portugal Driver';
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
    function getISO3166Codes() {
        return ['pt'];
    }
}
