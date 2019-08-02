<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Driver for holidays in France
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
 * @author   Stephan Schmidt <schst@php-tools.net>
 * @author   Carsten Lucke <luckec@tool-garage.de>
 * @license  http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @version  CVS: $Id: France.php,v 1.13 2009/03/14 22:30:14 kguest Exp $
 * @link     http://pear.php.net/package/Date_Holidays
 */

/**
 * Extends Christian driver
 */
require_once 'Christian.php';

/**
 * class that calculates French holidays
 *
 * @category   Date
 * @package    Date_Holidays
 * @subpackage Driver
 * @author     Carsten Lucke <luckec@tool-garage.de>
 * @author     Stephan Schmidt <schst@php.net>
 * @license    http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @version    CVS: $Id: France.php,v 1.13 2009/03/14 22:30:14 kguest Exp $
 * @link       http://pear.php.net/package/Date_Holidays
 */
class Date_Holidays_Driver_France extends Date_Holidays_Driver
{
    /**
     * this driver's name
     *
     * @access   protected
     * @var      string
     */
    var $_driverName = 'France';

    /**
     * Constructor
     *
     * Use the Date_Holidays::factory() method to construct an object of a
     * certain driver
     *
     * @access   protected
     */
    function Date_Holidays_Driver_France()
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
        parent::_buildHolidays();

        $this->_addHoliday('newYearsDay',
                           $this->_year . '-01-01',
                           'New Year\'s Day');

        $this->_addHoliday('dayOfWork',
                           $this->_year . '-05-01',
                           'Day of Work');

        $this->_addHoliday('VEDay',
                            $this->_year . '-05-08',
                            'Victory in Europe Day');

        $this->_addHoliday('bastilleDay',
                            $this->_year . '-07-14',
                            'Bastille Day');

        $this->_addHoliday('armisticeDay',
                            $this->_year . '-11-11',
                            'Armistice Day');

        /* from Christian.php */
        $easterDate = Date_Holidays_Driver_Christian::calcEaster($this->_year);
        $this->_addHoliday('easterMonday',
                           $easterDate->getNextDay(),
                           'Easter Monday');

        $whitsunDate = $this->_addDays($easterDate, 49);
        $ascensionDayDate = $this->_addDays($whitsunDate, -10);
        $this->_addHoliday('ascensionDay', $ascensionDayDate, 'Ascension Day');
        $this->_addHoliday('whitMonday', $whitsunDate->getNextDay(), 'Whit Monday');

        $this->_addHoliday('mariaAscension',
                           $this->_year . '-08-15',
                           'Ascension of Maria');
        $this->_addHoliday('allSaintsDay',
                           $this->_year . '-11-01',
                           'All Saints\' Day');
        $this->_addHoliday('christmasDay', $this->_year . '-12-25', 'Christmas Day');
        
        $translations = array(
            'newYearsDay' => "Jour de l'an",
            'easterMonday' => "Lundi de Pâques",
            'dayOfWork' => "Fête du travail",
            'VEDay' => "Victoire de 1945",
            'ascensionDay' => "Ascension",
            'whitMonday' => "Lundi de Pentecôte",
            'bastilleDay' => "Fête nationale",
            'mariaAscension' => "Assomption",
            'allSaintsDay' => "Toussaint",
            'armisticeDay' => "Armistice de 1918",
            'christmasDay' => "Noël",
        );
        foreach ($translations as $key => $text) {
            $this->_addTranslationForHoliday($key, 'fr_FR', $text);
        }
            
        if (Date_Holidays::errorsOccurred()) {
            return 'Error in France Driver';
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
        return array('fr');
    }
}
?>
