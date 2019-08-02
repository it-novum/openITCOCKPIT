<?php

/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Driver for determining holidays in San Marino.
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
 * @author   Andrea Venturi <a.venturi@gmail.com>
 * @license  http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @link     http://pear.php.net/package/Date_Holidays
 */

require_once 'Christian.php';

/**
 * Driver class that calculates San Marino holidays
 *
 * @category   Date
 * @package    Date_Holidays
 * @subpackage Driver
 * @author     Andrea Venturi <a.venturi@gmail.com>
 * @license    http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @link       http://pear.php.net/package/Date_Holidays
 */
class Date_Holidays_Driver_SanMarino extends Date_Holidays_Driver {
    /**
     * this driver's name
     *
     * @access   protected
     * @var      string
     */
    var $_driverName = 'SanMarino';

    /**
     * Constructor
     *
     * Use the Date_Holidays::factory() method to construct an object of a
     * certain driver
     *
     * @access   protected
     */
    function Date_Holidays_Driver_SanMarino() {
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
        $this->_addHoliday(
            'newYearsDay',
            $this->_year . '-01-01',
            'New Year\'s Day'
        );
        $this->_addTranslationForHoliday('newYearsDay', 'it_IT', 'Capodanno');

        /**
         * Epiphany
         */
        $this->_addHoliday('epiphany', $this->_year . '-01-06', 'Epiphany');
        $this->_addTranslationForHoliday('epiphany', 'it_IT', 'Epifania');

        /**
         * St Agata's Day and
         * Anniversary of the Liberation from the Occupation of Cardinal Alberoni
         */
        $this->_addHoliday(
            'stagataday',
            $this->_year . '-02-05',
            'St Agata\'s Day'
        );
        $this->_addTranslationForHoliday('stagataday', 'it_IT', 'Sant\'Agata');

        /**
         * Anniversary of the "Arengo"
         */
        $this->_addHoliday(
            'arengo',
            $this->_year . '-03-25',
            'Anniversary of the "Arengo"'
        );
        $this->_addTranslationForHoliday(
            'arengo',
            'it_IT',
            'Anniversario dell\'Arengo'
        );

        /**
         * Investiture of the Captains Regent (Heads of  State)
         */
        $this->_addHoliday(
            'reggentiaprile',
            $this->_year . '-04-01',
            'Investiture of the Captains Regent (Heads of  State)'
        );
        $this->_addTranslationForHoliday(
            'reggentiaprile',
            'it_IT',
            'Ingresso Capitani Reggenti'
        );

        /**
         * Easter Sunday
         */
        $easterDate = Date_Holidays_Driver_Christian::calcEaster($this->_year);
        $this->_addHoliday('easter', $easterDate, 'Easter Sunday');
        $this->_addTranslationForHoliday('easter', 'it_IT', 'Pasqua');

        /**
         * Easter Monday
         */
        $this->_addHoliday(
            'easterMonday',
            $easterDate->getNextDay(),
            'Easter Monday'
        );
        $this->_addTranslationForHoliday(
            'easterMonday',
            'it_IT',
            'Lunedi` dell\'Angelo'
        );

        /**
         * Day of Work
         */
        $this->_addHoliday('dayOfWork', $this->_year . '-05-01', 'Day of Work');
        $this->_addTranslationForHoliday('dayOfWork', 'it_IT', 'Festa del Lavoro');

        /**
         * Corpus Domini
         */
        $corpus = $this->_addDays($easterDate, 60);
        $this->_addHoliday('corpusdomini', $corpus, 'Corpus Domini');
        $this->_addTranslationForHoliday('corpusdomini', 'it_IT', 'Corpus Domini');

        /**
         * Fall of Fascism
         */
        $this->_addHoliday(
            'falloffascism',
            $this->_year . '-07-28',
            'Fall of Fascism'
        );
        $this->_addTranslationForHoliday(
            'falloffascism',
            'it_IT',
            'Caduta del Fascismo'
        );

        /**
         * Ascension of Maria
         */
        $this->_addHoliday(
            'mariaAscension',
            $this->_year . '-08-15',
            'Ascension of Maria'
        );
        $this->_addTranslationForHoliday(
            'mariaAscension',
            'it_IT',
            'Assunzione di Maria Vergine'
        );

        /**
         * San Marino National Holiday and Foundation of the Republic
         */
        $this->_addHoliday(
            'nationalholiday',
            $this->_year . '-09-03',
            'San Marino National Holiday and Foundation of the Republic'
        );
        $this->_addTranslationForHoliday(
            'nationalholiday',
            'it_IT',
            'San Marino, festa della fondazione'
        );

        /**
         * Investiture of the Captains Regent (Heads of  State)
         */
        $this->_addHoliday(
            'reggentiottobre',
            $this->_year . '-10-01',
            'Investiture of the Captains Regent (Heads of  State)'
        );
        $this->_addTranslationForHoliday(
            'reggentiottobre',
            'it_IT',
            'Ingresso Capitani Reggenti'
        );

        /**
         * All Saints' Day
         */
        $this->_addHoliday(
            'allSaintsDay',
            $this->_year . '-11-01',
            'All Saints\' Day'
        );
        $this->_addTranslationForHoliday(
            'allSaintsDay',
            'it_IT',
            'Ognissanti'
        );

        /**
         * All Souls' Day
         */
        $this->_addHoliday(
            'allSoulsDay',
            $this->_year . '-11-02',
            'All Souls\' Day'
        );
        $this->_addTranslationForHoliday(
            'allSoulsDay',
            'it_IT',
            'Commemorazione dei Defunti'
        );

        /**
         * Maria' conception
         */
        $this->_addHoliday(
            'mariaConception',
            $this->_year . '-12-08',
            'Conception of Maria'
        );
        $this->_addTranslationForHoliday(
            'mariaConception',
            'it_IT',
            'Immacolata Concezione'
        );

        /**
         * Christmas day
         */
        $this->_addHoliday('christmasDay', $this->_year . '-12-25', 'Christmas Day');
        $this->_addTranslationForHoliday('christmasDay', 'it_IT', 'Santo Natale');

        /**
         * St. Stephen's Day
         */
        $this->_addHoliday(
            'StStephensDay',
            $this->_year . '-12-26',
            'Saint Stephen\'s Day'
        );
        $this->_addTranslationForHoliday(
            'StStephensDay',
            'it_IT',
            'Santo Stefano'
        );


        if (Date_Holidays::errorsOccurred()) {
            return 'Error in SanMarino Driver';
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
        return ['sm', 'sra'];
    }
}
