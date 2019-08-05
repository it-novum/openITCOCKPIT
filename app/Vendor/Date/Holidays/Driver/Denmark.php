<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Driver for Discordian holidays
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
 * @author   Henrik Hansen <hh@fsck.dk>
 * @license  http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @version  CVS: $Id: Denmark.php,v 1.13 2009/03/15 20:17:00 kguest Exp $
 * @link     http://pear.php.net/package/Date_Holidays
 */

require_once 'Christian.php';

/**
 * Driver class that calculates Danish holidays
 *
 * @category   Date
 * @package    Date_Holidays
 * @subpackage Driver
 * @author     Henrik Hansen <hh@fsck.dk>
 * @license    http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @version    CVS: $Id: Denmark.php,v 1.13 2009/03/15 20:17:00 kguest Exp $
 * @link       http://pear.php.net/package/Date_Holidays
 */
class Date_Holidays_Driver_Denmark extends Date_Holidays_Driver
{
    /**
     * this driver's name
     *
     * @access   protected
     * @var      string
     */
    var $_driverName = 'Denmark';

    /**
     * Constructor
     *
     * Use the Date_Holidays::factory() method to construct an object of a
     * certain driver
     *
     * @access   protected
     */
    function Date_Holidays_Driver_Denmark()
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
        $this->_addTranslationForHoliday('newYearsDay', 'da_DK', 'Nytårsdag');

        /**
         * Epiphanias
         */
        $this->_addHoliday('epiphany', $this->_year . '-01-06', 'Epiphany');
        $this->_addTranslationForHoliday('epiphany', 'da_DK', 'Hellig 3 Konger');

        /**
         * Easter Sunday
         */
        $easterDate = Date_Holidays_Driver_Christian::calcEaster($this->_year);
        $this->_addHoliday('easter', $easterDate, 'Easter Sunday');
        $this->_addTranslationForHoliday('easter', 'da_DK', 'Påskedag');

        /**
         * Easter Monday
         */
        $this->_addHoliday('easterMonday',
                           $easterDate->getNextDay(),
                           'Easter Monday');
        $this->_addTranslationForHoliday('easterMonday', 'da_DK', '2. Påskedag');

        /**
         * Palm Sunday
         */
        $palmSundayDate = $this->_addDays($easterDate, -7);
        $this->_addHoliday('palmSunday', $palmSundayDate, 'Palm Sunday');
        $this->_addTranslationForHoliday('palmSunday', 'da_DK', 'Palme Søndag');

        /**
         * Good Friday / Black Friday
         */
        $goodFridayDate = $this->_addDays($easterDate, -2);
        $this->_addHoliday('goodFriday', $goodFridayDate, 'Good Friday');
        $this->_addTranslationForHoliday('goodFriday', 'da_DK', 'Langfredag');

        /**
         * Green Thursday
         */
        $this->_addHoliday('greenThursday',
                           $goodFridayDate->getPrevDay(),
                           'Green Thursday');
        $this->_addTranslationForHoliday('greenThursday', 'da_DK', 'Skærtorsdag');

        /**
         * Whitsun (determines Whit Monday, Ascension Day and
         * Feast of Corpus Christi)
         */
        $whitsunDate = $this->_addDays($easterDate, 49);
        $this->_addHoliday('whitsun', $whitsunDate, 'Whitsun');
        $this->_addTranslationForHoliday('whitsun', 'da_DK', 'Pinsedag');

        /**
         * Whit Monday
         */
        $this->_addHoliday('whitMonday',
                           $whitsunDate->getNextDay(),
                           'Whit Monday');
        $this->_addTranslationForHoliday('whitMonday', 'da_DK', '2. Pinsedag');

        /**
         * Ascension Day
         */
        $ascensionDayDate = $this->_addDays($whitsunDate, -10);
        $this->_addHoliday('ascensionDay', $ascensionDayDate, 'Ascension Day');
        $this->_addTranslationForHoliday('ascensionDay', 'da_DK', 'Kr. Himmelfart');

        /**
         * Heart of Jesus (General Prayer Day)
         *
         * Friday of the 3rd week past Whitsun
         */
        $heartJesusDate = $this->_addDays($goodFridayDate, 28);
        $this->_addHoliday('heartJesus',
                           $heartJesusDate,
                           'Heart of Jesus celebration');
        $this->_addTranslationForHoliday('heartJesus', 'da_DK', 'Store Bededag');

        /**
         * Christmas Eve
         */
        $this->_addHoliday('christmasEve', $this->_year . '-12-24', 'Christmas Eve');
        $this->_addTranslationForHoliday('christmasEve', 'da_DK', 'Juleaften');

        /**
         * Christmas day
         */
        $this->_addHoliday('christmasDay', $this->_year . '-12-25', 'Christmas Day');
        $this->_addTranslationForHoliday('christmasDay', 'da_DK', '1. Juledag');

        /**
         * Second Christmas Day
         */
        $this->_addHoliday('secondChristmasDay',
                           $this->_year . '-12-26',
                           'Boxing Day');
        $this->_addTranslationForHoliday('secondChristmasDay',
                                         'da_DK',
                                         '2. Juledag');

        /**
         * New Year's Eve
         */
        $this->_addHoliday('newYearsEve',
                           $this->_year . '-12-31',
                           'New Year\'s Eve');
        $this->_addTranslationForHoliday('newYearsEve', 'da_DK', 'Nytårsaften');

        if (Date_Holidays::errorsOccurred()) {
            return 'Error in Denmark Driver';
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
        return array('dk', 'dnk');
    }
}
?>
