<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Driver for holidays in Spain
 *
 * PHP Version 5
 *
 * Copyright (c) 1997-2011 The PHP Group
 *
 * @category Date
 * @package  Date_Holidays_Spain
 * @author   Jesús Espino <jespinog@gmail.com>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License 2.1
 * @version  SVN: $Id: Porugal.php 277207 2009-03-15 20:17:00Z kguest $
 * @link     http://pear.php.net/package/Date_Holidays_Spain
 */

/**
 * Requires Christian driver
 */
require_once 'Christian.php';

/**
 * class that calculates Spain holidays
 *
 * @category   Date
 * @package    Date_Holidays
 * @subpackage Driver
 * @author     Jesús Espino <jespinog@gmail.com>
 * @license    http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @version    SVN: $Id: Spain.php 277207 2009-03-15 20:17:00Z kguest $
 * @link       http://pear.php.net/package/Date_Holidays
 */
class Date_Holidays_Driver_Spain extends Date_Holidays_Driver {
    /**
     * this driver's name
     *
     * @access   protected
     * @var      string
     */
    var $_driverName = 'Spain';

    /**
     * Constructor
     *
     * Use the Date_Holidays::factory() method to construct an object of a certain
     * driver
     *
     * @access   protected
     */
    function Date_Holidays_Driver_Spain() {
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
        $this->_addHoliday('newYearsDay', $this->_year . '-01-01', 'Año Nuevo');

        /**
         * Epiphanias
         */
        $this->_addHoliday('epiphany', $this->_year . '-01-06', 'Día de Reyes');

        /**
         * Valentine Day
         */
        $this->_addHoliday(
            'valentinesDay',
            $this->_year . '-02-14',
            'San Valentín'
        );

        /**
         * Easter Sunday
         */
        $easterDate = Date_Holidays_Driver_Christian::calcEaster($this->_year);
        $this->_addHoliday('easter', $easterDate, 'Semana Santa');

        /**
         * Carnival
         */
        $carnival = $this->_addDays($easterDate, -47);
        $this->_addHoliday('carnival', $carnival, 'Carnaval');

        /**
         * Ash Wednesday
         */
        $ashWednesday = $this->_addDays($easterDate, -46);
        $this->_addHoliday('ashWednesday', $ashWednesday, 'Miércoles de Ceniza');

        /**
         * Palm Sunday
         */
        $palmSunday = $this->_addDays($easterDate, -7);
        $this->_addHoliday('palmSunday', $palmSunday, 'Domingo de Ramos');

        /**
         * Maundy Thursday
         */
        $maundyThursday = $this->_addDays($easterDate, -3);
        $this->_addHoliday('maundyThursday', $maundyThursday, 'Jueves Santo');

        /**
         * Good Friday
         */
        $goodFriday = $this->_addDays($easterDate, -2);
        $this->_addHoliday('goodFriday', $goodFriday, 'Viernes Santo');

        /**
         * Saint Saturday
         */
        $goodFriday = $this->_addDays($easterDate, -1);
        $this->_addHoliday('saintSaturday', $goodFriday, 'Sábado Santo');

        /**
         * Easter
         */
        $this->_addHoliday('easter', $easterDate, 'Domingo de Resurrección');

        /**
         * Fathers Day
         */
        $this->_addHoliday('fathersDay', $this->_year . '-03-19', 'Día del Padre');

        /**
         * Day of Work
         */
        $this->_addHoliday(
            'dayOfWork',
            $this->_year . '-05-01',
            'Día del Trabajador'
        );

        /**
         * Mothers Day
         */
        $mothersDay = $this->_calcFirstMonday("05");
        $mothersDay = $mothersDay->getPrevDay();
        if ($mothersDay->getDay() == 30) {
            $mothersDay = $this->_addDays($mothersDay, 7);
        }
        $this->_addHoliday('mothersDay', $mothersDay, 'Día de la Madre');

        /**
         * Corpus Christi
         */
        $corpusChristi = $this->_addDays($easterDate, 60);
        $this->_addHoliday('corpusChristi', $corpusChristi, 'Corpus Cristi');

        /**
         * Apostle Santiago
         */
        $this->_addHoliday(
            'apostleSantiago',
            $this->_year . '-07-25',
            'Santiago Apóstol'
        );

        /**
         * Ascension of Maria
         */
        $this->_addHoliday(
            'mariaAscension',
            $this->_year . '-08-15',
            'Asunción de la Virgen María'
        );

        /**
         * Hispanity Day
         */
        $this->_addHoliday(
            'hispanityDay',
            $this->_year . '-10-12',
            'Día de la Hispanidad'
        );

        /**
         * All Saints' Day
         */
        $this->_addHoliday(
            'allSaintsDay',
            $this->_year . '-11-01',
            'Todos los Santos'
        );

        /**
         * Constitution's Day
         */
        $this->_addHoliday(
            'constitutionDay',
            $this->_year . '-12-06',
            'Día de la Constitución'
        );

        /**
         * Immaculate Conception
         */
        $this->_addHoliday(
            'immaculateConceptionDay',
            $this->_year . '-12-08',
            'Inmaculada Concepción'
        );

        /**
         * Christmas Eve
         */
        $this->_addHoliday('christmasEve', $this->_year . '-12-24', 'Noche Buena');

        /**
         * Christmas day
         */
        $this->_addHoliday('christmasDay', $this->_year . '-12-25', 'Navidad');

        /**
         * New Year´s Eve
         */
        $this->_addHoliday(
            'newYearsEve',
            $this->_year . '-12-31',
            'Noche vieja'
        );

        if (Date_Holidays::errorsOccurred()) {
            return 'Error in Spain Driver';
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
        return ['es', 'esp'];
    }
}
