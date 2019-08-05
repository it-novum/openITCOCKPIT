<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Driver for determining holidays in Italy.
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
 * @author   Valerio Pulese <valerio@dei.unipd.it>
 * @license  http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @version  CVS: $Id: Italy.php,v 1.3 2009/03/15 20:17:00 kguest Exp $
 * @link     http://pear.php.net/package/Date_Holidays
 */

require_once 'Christian.php';

/**
 * Driver class that calculates Italy holidays
 * deriving most calculations from 'Public holidays in Ireland' document
 * on http://www.citizensinformation.ie/
 *
 * @category   Date
 * @package    Date_Holidays
 * @subpackage Driver
 * @author     Valerio Pulese <valerio@dei.unipd.it>
 * @license    http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @version    CVS: $Id: Italy.php,v 1.3 2009/03/15 20:17:00 kguest Exp $
 * @link       http://pear.php.net/package/Date_Holidays
 */
class Date_Holidays_Driver_Italy extends Date_Holidays_Driver
{
    /**
     * this driver's name
     *
     * @access   protected
     * @var      string
     */
    var $_driverName = 'Italy';

    /**
     * Constructor
     *
     * Use the Date_Holidays::factory() method to construct an object of a
     * certain driver
     *
     * @access   protected
     */
    function Date_Holidays_Driver_Italy()
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
        $this->_addTranslationForHoliday('newYearsDay', 'it_IT', 'Capodanno');

        /**
         * Epiphany
         */
        $this->_addHoliday('epiphany', $this->_year . '-01-06', 'Epiphany');
        $this->_addTranslationForHoliday('epiphany', 'it_IT', 'Epifania');


        /**
         * Easter Sunday
         */
        $easterDate = Date_Holidays_Driver_Italy::calcEaster($this->_year);
        $this->_addHoliday('easter', $easterDate, 'Easter Sunday');
        $this->_addTranslationForHoliday('easter', 'it_IT', 'Pasqua');

        /**
         * Good Friday / Black Friday
         */
        $goodFridayDate = $this->_addDays($easterDate, -2);
        $this->_addHoliday('goodFriday', $goodFridayDate, 'Good Friday');
        $this->_addTranslationForHoliday('goodFriday',
                                         'it_IT',
                                         'Venerdi` Santo');

        /**
         * Easter Monday
         */
        $this->_addHoliday('easterMonday',
                           $easterDate->getNextDay(),
                           'Easter Monday');
        $this->_addTranslationForHoliday('easterMonday',
                                         'it_IT',
                                         'Lunedi` dell\'Angelo');

        /**
        * Day of Work
        */
        $this->_addHoliday('dayOfWork',
                            $this->_year . '-05-01',
                            'Day of Work');
        $this->_addTranslationForHoliday('dayOfWork',
                                         'it_IT',
                                         'Festa del Lavoro');


        /**
        * Republic Day
        */
        $this->_addHoliday('republicDay',
                            $this->_year . '-06-02',
                            'Republic Day');
        $this->_addTranslationForHoliday('republicDay',
                                         'it_IT',
                                         'Festa della Repubblica');
        /**
        * End of War Day
        */
        $this->_addHoliday('endofwarDay',
                            $this->_year . '-04-25',
                            'End of War');
        $this->_addTranslationForHoliday('endofwarDay',
                                         'it_IT',
                                         'Festa della Liberazione');



        /**
         * Ascension of Maria
         */
        $this->_addHoliday('mariaAscension',
                           $this->_year . '-08-15',
                           'Ascension of Maria');
        $this->_addTranslationForHoliday('mariaAscension',
                                         'it_IT',
                                         'Assunzione di Maria Vergine');
         /**
         * Maria' conception
         */
            $this->_addHoliday('mariaConception',
                           $this->_year . '-12-08',
                           'Conception of Maria');
        $this->_addTranslationForHoliday('mariaConception',
                                         'it_IT',
                                         'Immacolata Concezione');

        /**
         * All Saints' Day
         */
        $this->_addHoliday('allSaintsDay',
                           $this->_year . '-11-01',
                           'All Saints\' Day');
        $this->_addTranslationForHoliday('allSaintsDay',
                                         'it_IT',
                                         'Ognissanti');

        /**
         * All Souls' Day
         */
        $this->_addHoliday('allSoulsDay',
                            $this->_year . '-11-02',
                            'All Souls\' Day');
        $this->_addTranslationForHoliday('allSoulsDay',
                                         'it_IT',
                                         'Commemorazione dei Defunti');



        /**
         * Christmas Eve
         */
        $this->_addHoliday('christmasEve', $this->_year . '-12-24', 'Christmas Eve');
        $this->_addTranslationForHoliday('christmasEve',
                                         'it_IT',
                                         'Vigilia di Natale');

        /**
         * Christmas day
         */
        $this->_addHoliday('christmasDay', $this->_year . '-12-25', 'Christmas Day');
        $this->_addTranslationForHoliday('christmasDay',
                                         'it_IT',
                                         'Santo Natale');

        /**
         * St. Stephen's Day
         */
        $this->_addHoliday('StStephensDay',
                           $this->_year . '-12-26',
                           'Saint Stephen\'s Day');
        $this->_addTranslationForHoliday('StStephensDay',
                                         'it_IT',
                                         'Santo Stefano');

        /**
         * New Year's Eve
         */
        $this->_addHoliday('newYearsEve',
                           $this->_year . '-12-31',
                           'New Year\'s Eve');
        $this->_addTranslationForHoliday('newYearsEve',
                                         'it_IT',
                                         'San Silvestro');

        if (Date_Holidays::errorsOccurred()) {
            return 'Error in Italy Driver';
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
        return array('it', 'ita');
    }
}
