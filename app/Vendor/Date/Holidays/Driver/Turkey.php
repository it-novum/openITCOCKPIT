<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Turkey.php
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
 * @author   Serkan Cetintopcu <sc@st.net.tr>
 * @license  http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @version  CVS: $Id: Turkey.php,v 1.11 2008/03/17 11:37:49 kguest Exp $
 * @link     http://pear.php.net/package/Date_Holidays
 */
//


/**
 * class that calculates observed Turkey holidays
 *
 * @category   Date
 * @package    Date_Holidays
 * @subpackage Driver
 * @author     Serkan Cetintopcu <sc@st.net.tr>
 * @license    http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @version    CVS: $Id: Turkey.php,v 1.11 2008/03/17 11:37:49 kguest Exp $
 * @link       http://pear.php.net/package/Date_Holidays
 */
class Date_Holidays_Driver_Turkey extends Date_Holidays_Driver {
    /**
     * Constructor
     *
     * Use the Date_Holidays::factory() method to construct an object of a
     * certain driver
     *
     * @access   protected
     */
    function Date_Holidays_Driver_Turkey() {
    }

    /**
     * Build the internal arrays that contain data about the calculated holidays
     *
     * @access   protected
     * @return   boolean true on success, otherwise a PEAR_ErrorStack object
     * @throws   object PEAR_ErrorStack
     */
    function _buildHolidays() {


        $newYearsDay = $this->_calcNearestWorkDay('01', '01');
        $this->_addHoliday('newYearsDay', $newYearsDay, 'Yılbaşı');


        $this->_addHoliday('egemenlikDay', $this->_year . '-04-23', 'Ulusal Egemenlik ve Çocuk Bayramı');


        $this->_addHoliday('genclikDay', $this->_year . '-05-19', 'Atatürk\'ü Anma Gençlik ve Spor Bayramı');

        $this->_addHoliday('zaferDay', $this->_year . '-08-30', 'Zafer Bayramı');

        $this->_addHoliday('cumhuriyetDay', $this->_year . '-10-29', 'Cumhuriyet Bayramı');


        return true;
    }


    function _calcNearestWorkDay($month, $day) {
        $month = sprintf("%02d", $month);
        $day = sprintf("%02d", $day);
        $date = new Date($this->_year . '-' . $month . '-' . $day);

        // When one of these holidays falls on a Saturday, the previous day is
        // also a holiday
        // When New Year's Day, Independence Day, or Christmas Day falls on a
        // Sunday, the next day is also a holiday.
        if ($date->getDayOfWeek() == 0) {
            // bump it up one
            $date = $date->getNextDay();
        }
        if ($date->getDayOfWeek() == 6) {
            // push it back one
            $date = $date->getPrevDay();
        }

        return $date;
    }


    function getISO3166Codes() {
        return ['tr', 'tur'];
    }
}
