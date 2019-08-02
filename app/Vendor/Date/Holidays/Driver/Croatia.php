<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Croatia
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
 * @author   Valentin Vidic <Valentin.Vidic@CARNet.hr>
 * @license  http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @version  CVS: $Id: Croatia.php,v 1.8 2008/10/08 22:04:12 kguest Exp $
 * @link     http://pear.php.net/package/Date_Holidays
 */

require_once 'Christian.php';

/**
 * Driver class that calculates Croatian holidays
 *
 * @category   Date
 * @package    Date_Holidays
 * @subpackage Driver
 * @author     Valentin Vidic <Valentin.Vidic@CARNet.hr>
 * @license    http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @version    CVS: $Id: Croatia.php,v 1.8 2008/10/08 22:04:12 kguest Exp $
 * @link       http://pear.php.net/package/Date_Holidays
 */
class Date_Holidays_Driver_Croatia extends Date_Holidays_Driver
{
    /**
     * Constructor
     *
     * Use the Date_Holidays::factory() method to construct an object of
     * a certain driver
     *
     * @access   protected
     */
    function Date_Holidays_Driver_Croatia()
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
        $this->_addHoliday('novagodina', $this->_year . '-01-01', 'Nova godina');

        /**
         * Sveta tri kralja
         */
        $this->_addHoliday('trikralja', $this->_year . '-01-06', 'Sveta tri kralja');

        /**
         * Easter Sunday
         */
        $easter = Date_Holidays_Driver_Christian::calcEaster($this->_year);
        $this->_addHoliday('uskrs', $easter, 'Uskrs');

        /**
         * Easter Monday
         */
        $easterMon = clone $easter;
        $easterMon->day++;
        $this->_addHoliday('uskrspon', $easterMon, 'Uskršnji ponedjeljak');

        /**
         * Praznik rada
         */
        $this->_addHoliday('rada', $this->_year . '-05-01', 'Praznik rada');

        /**
         * Tijelovo
         */
        $easterMon = clone $easter;
        $easterMon->day+=60;
        $this->_addHoliday('Tijelovo', $easterMon, 'Tijelovo');

        /**
         * Dan antifašističke borbe
         */
        $this->_addHoliday(
            'antifasizma',
            $this->_year . '-06-22',
            'Dan antifašističke borbe'
        );

        /**
         * Dan državnosti
         */
        $this->_addHoliday('drzavnosti', $this->_year . '-06-25', 'Dan državnosti');

        /**
         * Dan domovinske zahvalnosti
         */
        $this->_addHoliday(
            'zahvalnosti',
            $this->_year . '-08-05',
            'Dan domovinske zahvalnosti'
        );

        /**
         * Velika Gospa
         */
        $this->_addHoliday('gospa', $this->_year . '-08-15', 'Velika Gospa');

        /**
         * Dan neovisnosti
         */
        $this->_addHoliday(
            'neovisnosti',
            $this->_year . '-10-08',
            'Dan neovisnosti'
        );

        /**
         * Dan svih svetih
         */
        $this->_addHoliday('svisveti', $this->_year . '-11-01', 'Dan svih svetih');

        /**
         * Božić
         */
        $this->_addHoliday('bozic', $this->_year . '-12-25', 'Božić');

        /**
         * Sveti Stjepan
         */
        $this->_addHoliday('svetistjepan', $this->_year . '-12-26', 'Sveti Stjepan');

        if (Date_Holidays::errorsOccurred()) {
            return 'Error in Croatia Driver';
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
        return array('hr', 'hrv');
    }
}
?>
