<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Serbia
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
 * @author   Boban Acimovic <boban.acimovic@gmail.com>
 * @license  http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @version  0.1
 * @link     http://pear.php.net/package/Date_Holidays
 */

/**
 * Driver class that calculates Serbian holidays
 *
 * @category   Date
 * @package    Date_Holidays
 * @subpackage Driver
 * @author     Boban Acimovic <boban.acimovic@gmail.com>
 * @license    http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @version    0.1
 * @link       http://pear.php.net/package/Date_Holidays
 */
class Date_Holidays_Driver_Serbia extends Date_Holidays_Driver {
    /**
     * Constructor
     *
     * Use the Date_Holidays::factory() method to construct an object of
     * a certain driver
     *
     * @access   protected
     */
    function Date_Holidays_Driver_Serbia() {
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
         * Nova godina
         */
        $this->_addHoliday('novagodina', $this->_year . '-01-01', 'Nova godina');

        /**
         * Božić - Rođenje Gospoda Isusa Hrista
         */
        $this->_addHoliday('bozic', $this->_year . '-01-07', 'Božić - Rođenje Gospoda Isusa Hrista');

        /**
         * Sretenje - Dan državnosti Srbije
         */
        $this->_addHoliday('sretenje', $this->_year . '-01-15', 'Sretenje - Dan državnosti Srbije');

        /**
         * Veliki petak
         */
        $this->_addHoliday('velikipetak', $this->_year . $this->_getGoodFriday(), 'Veliki petak');

        /**
         * Vaskrs
         */
        $this->_addHoliday('vaskrs', $this->_year . $this->_getEaster(), 'Vaskrsenje Gospoda Isusa Hrista');

        /**
         * Praznik rada
         */
        $this->_addHoliday('praznikrada', $this->_year . '-05-01', 'Praznik rada');

        /**
         * Dan primirja u Prvom svetskom ratu
         */
        $this->_addHoliday('danprimirja', $this->_year . '-11-11', 'Dan primirja u Prvom svetskom ratu');

        if (Date_Holidays::errorsOccurred()) {
            return 'Error in Serbia Driver';
        }
        return true;
    }

    /**
     * Helper method that Calculates Orthodox Easter date in Julian calendar
     * Meeus Julian algorithm
     *
     * @static
     * @access private
     * @return string
     */
    function _calcEaster() {
        $a = $this->_year % 4;
        $b = $this->_year % 7;
        $c = $this->_year % 19;
        $d = (19 * $c + 15) % 30;
        $e = (2 * $a + 4 * $b - $d + 34) % 7;
        $month = floor(($d + $e + 114) / 31);
        $day = (($d + $e + 114) % 31) + 1;
        return [$month, $day];
    }

    /**
     * Helper method that returns formatted Orthodox Easter date
     *
     * @static
     * @access private
     * @return string
     */
    function _getEaster() {
        list($month, $day) = $this->_calcEaster();
        // Add 13 days to convert from Julian to Gregorian calendar
        $day += 13;
        if ($day > 30) {
            if ($month == 5) {
                $day -= 31;
            }
            if ($month == 4) {
                $day -= 30;
            }
            $month++;
        }
        // Format numbers
        $month = sprintf('%02d', $month);
        $day = sprintf('%02d', $day);
        return "-$month-$day";
    }

    /**
     * Helper method that returns formatted Orthodox Good Friday date
     *
     * @static
     * @access private
     * @return string
     */
    function _getGoodFriday() {
        list($month, $day) = $this->_calcEaster();
        // Add 13 days to convert from Julian to Gregorian calendar
        $day += 13;
        // Remove 2 days to get Good Friday intead of Easter
        $day -= 2;
        if ($day > 30) {
            if ($month == 5) {
                $day -= 31;
            }
            if ($month == 4) {
                $day -= 30;
            }
            $month++;
        }
        // Format numbers
        $month = sprintf('%02d', $month);
        $day = sprintf('%02d', $day);
        return "-$month-$day";
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
        return ['rs', 'srb'];
    }
}
