<?php
/**
 * Driver for holidays in Perú
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
 * @author   Cristian Contreras <ccontrerasl@gmail.com>
 * @license  http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @version  CVS: $Id$
 * @link     http://pear.php.net/package/Date_Holidays
 */

/**
 * Requires Christian driver
 */
require_once 'Christian.php';

/**
 * Class that calculates Peruvian holidays
 *
 * @category   Date
 * @package    Date_Holidays
 * @subpackage Driver
 * @author     Cristian Contreras <ccontrerasl@gmail.com>
 * @license    http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/Date_Holidays
 */
class Date_Holidays_Driver_Peru extends Date_Holidays_Driver
{
    /**
     * This driver's name
     *
     * @access protected
     * @var    string
     */
    var $_driverName = 'Peru';

    /**
     * Constructor
     *
     * Use the Date_Holidays::factory() method to construct an object of a certain
     * driver
     *
     * @access protected
     */
    public function __construct()
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
        $easterDate = Date_Holidays_Driver_Christian::calcEaster($this->_year);
        /**
         * New Year's Day
         */
        $this->_addHoliday('newYearsDay', $this->_year . '-01-01', 'Año Nuevo');


        /**
         * Jueves Santo
         */
        $maundyThursday = $this->_addDays($easterDate, -3);
        $this->_addHoliday('maundyThursday', $maundyThursday, 'Jueves santo');

        /**
         * Viernes Santo
         */
        $goodFriday = $this->_addDays($easterDate, -2);
        $this->_addHoliday('goodFriday', $goodFriday, 'Viernes santo');

        /**
         * Sabado Santo
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
         * Saint Peter and Saint Paul
         */
        $this->_addHoliday(
            'petrusAndPaulus',
            $this->_year . '-06-29',
            'San Pedro y San Pablo'
        );

        /**
         * Independence Day
         */
        $this->_addHoliday(
            'independenceDay',
            $this->_year . '-07-28',
            'Independencia Nacional'
        );

        /**
         * Army Day
         */
        $this->_addHoliday(
            'armyDay',
            $this->_year . '-07-29',
            'Fuerzas armadas y policia nacional'
        );

        /**
         * Patron Saint of Lima
         */
        $this->_addHoliday(
            'saintRose',
            $this->_year . '-08-30',
            'Santa Rosa de Lima'
        );

        /**
         * Battle of Angamos
         */
        $this->_addHoliday(
            'battleAngamos',
            $this->_year . '-10-08',
            'Batalla de Angamos'
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
            return 'Error in Peru Driver';
        }
        return true;
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
        return array('pe', 'per');
    }
}
