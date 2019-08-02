<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Driver for holidays in Chile
 *
 * PHP Version 5
 *
 * Copyright (c) 1997-2014 The PHP Group
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
 * @author   Xavier Noguer <xnoguer@gmail.com>
 * @license  http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @version  CVS: $Id$
 * @link     http://pear.php.net/package/Date_Holidays
 */

/**
 * Requires Christian driver
 */
require_once 'Christian.php';

/**
 * class that calculates Chilean holidays
 *
 * @category   Date
 * @package    Date_Holidays
 * @subpackage Driver
 * @author     Xavier Noguer <xnoguer@gmail.com>
 * @license    http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/Date_Holidays
 */
class Date_Holidays_Driver_Chile extends Date_Holidays_Driver
{
    /**
     * This driver's name
     *
     * @access protected
     * @var    string
     */
    var $_driverName = 'Chile';

    /**
     * Constructor
     *
     * Use the Date_Holidays::factory() method to construct an object of a certain
     * driver
     *
     * @access protected
     */
    function Date_Holidays_Driver_Chile()
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
        $this->_addHoliday('newYearsDay', $this->_year . '-01-01', 'Año Nuevo');

        /**
         * Good Friday
         */
        $easterDate = Date_Holidays_Driver_Christian::calcEaster($this->_year);
        $goodFriday = $this->_addDays($easterDate, -2);
        $this->_addHoliday('goodFriday', $goodFriday, 'Viernes santo');

        /**
         * Holy Saturday
         */
        $holySaturday = $this->_addDays($easterDate, -1);
        $this->_addHoliday('holySaturday', $holySaturday, 'Sábado santo');

        /**
         * Labour Day
         */
        $this->_addHoliday(
            'dayOfWork', $this->_year . '-05-01',
            'Día nacional del trabajo'
        );

        /**
         * Navy Day
         */
        $this->_addHoliday(
            'navyDay',
            $this->_year . '-05-21',
            'Día de las glorias navales'
        );

        /**
         * Saint Peter and Saint Paul
         */
        $petrusAndPaulus = $this->_calcNearestMonday('06', '29');
        $this->_addHoliday(
            'petrusAndPaulus',
            $petrusAndPaulus,
            'San Pedro y San Pablo'
        );

        /**
         * Our Lady of Mount Carmel
         */
        $this->_addHoliday(
            'ourLadyOfMountCarmel',
            $this->_year . '-07-16',
            'Día de la Virgen del Carmen'
        );

        /**
         * Ascension of Maria
         */
        $this->_addHoliday(
            'mariaAscension',
            $this->_year . '-08-15',
            'Asunción de la Virgen'
        );

        /**
         * Fiestas Patrias (national holidays, september 17)
         * (http://www.leychile.cl/Navegar?idNorma=264651&idParte=&idVersion=2007-09-14)
         */
        $fiestasPatrias_17 = new Date($this->_year . '-09-17');
        if ($fiestasPatrias_17->getDayOfWeek() == 1) {
            $this->_addHoliday(
                'fiestasPatrias',
                $fiestasPatrias_17,
                'Fiestas Patrias'
            );
        }

        /**
         * Independence Day
         */
        $this->_addHoliday(
            'independenceDay',
            $this->_year . '-09-18',
            'Independencia Nacional'
        );

        /**
         * Army Day
         */
        $this->_addHoliday(
            'armyDay',
            $this->_year . '-09-19',
            'Día de las Glorias del Ejército'
        );

        /**
         * Fiestas Patrias (national holidays, september 20)
         * (http://www.leychile.cl/Navegar?idNorma=264651&idParte=&idVersion=2007-09-14)
         */
        $fiestasPatrias_20 = new Date($this->_year . '-09-20');
        if ($fiestasPatrias_20->getDayOfWeek() == 5) {
            $this->_addHoliday(
                'fiestasPatrias',
                $fiestasPatrias_20,
                'Fiestas Patrias'
            );
        }

        /**
         * Columbus Day
         */
        $columbusDay = $this->_calcNearestMonday('10', '12');
        $this->_addHoliday(
            'columbusDay',
            $columbusDay,
            'Día del descubrimiento de dos mundos'
        );

        /**
         * Reformation Day
         */
        $reformationDay = $this->_calcNearestFriday('10', '31');
        $this->_addHoliday(
            'reformationDay',
            $reformationDay,
            'Día de las Iglesias Evangélicas y Protestantes'
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
         * Immaculate Conception
         */
        $this->_addHoliday(
            'immaculateConceptionDay',
            $this->_year . '-12-08',
            'Inmaculada Concepción'
        );

        /**
         * Christmas day
         */
        $this->_addHoliday(
            'christmasDay',
            $this->_year . '-12-25',
            'Navidad'
        );

        if (Date_Holidays::errorsOccurred()) {
            return 'Error in Chile Driver';
        }
        return true;
    }

    /**
     * Calculate nearest monday for a certain day, under the following
     * rule: "a los días lunes de la semana en que ocurren, en caso de
     * corresponder a día martes, miércoles o jueves, o los días lunes
     * de la semana siguiente, en caso de corresponder a día viernes."
     *
     * @param int $month month
     * @param int $day   day
     *
     * @access private
     * @return object Date date
     */
    function _calcNearestMonday($month, $day)
    {
        $month = sprintf("%02d", $month);
        $day   = sprintf("%02d", $day);
        $date  = new Date($this->_year . '-' . $month . '-' . $day);

        // for tuesdays, wednesdays and thursdays, it gets bumped
        // to the previous monday
        if ($date->getDayOfWeek() == 2) {
            $date = $date->getPrevDay();
        } elseif ($date->getDayOfWeek() == 3) {
            $date = $date->getPrevDay();
            $date = $date->getPrevDay();
        } elseif ($date->getDayOfWeek() == 4) {
            $date = $date->getPrevDay();
            $date = $date->getPrevDay();
            $date = $date->getPrevDay();
        } elseif ($date->getDayOfWeek() == 5) {
            // for fridays it gets bumped to the next monday
            $date = $date->getNextDay();
            $date = $date->getNextDay();
            $date = $date->getNextDay();
            $date = $date->getNextDay();
        }
        return $date;
    }

    /**
     * Calculate nearest friday for a certain day, under the following
     * rule: "... al día viernes de la misma semana en caso de corresponder
     * el 31 de octubre a día miércoles, y trasládase al día viernes de la
     * semana inmediatamente anterior en caso de corresponder dicha fecha
     * a día martes."
     *
     * @param int $month month
     * @param int $day   day
     *
     * @access private
     * @return object Date date
     */
    function _calcNearestFriday($month, $day)
    {
        $month = sprintf("%02d", $month);
        $day   = sprintf("%02d", $day);
        $date  = new Date($this->_year . '-' . $month . '-' . $day);

        if ($date->getDayOfWeek() == 2) {
            // for tuesdays it gets bumped to the previous monday
            $date = $date->getPrevDay();
            $date = $date->getPrevDay();
            $date = $date->getPrevDay();
            $date = $date->getPrevDay();
        } elseif ($date->getDayOfWeek() == 3) {
            // for wednesdays it gets bumped to the next monday
            $date = $date->getNextDay();
            $date = $date->getNextDay();
        }
        return $date;
    }

    /**
     * Method that returns an array containing the ISO3166 codes that may
     * possibly identify a driver.
     *
     * @static
     * @access public
     * @return array possible ISO3166 codes
     */
    function getISO3166Codes()
    {
        return array('cl', 'chl');
    }
}
?>
