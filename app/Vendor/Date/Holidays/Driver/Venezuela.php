<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Driver for holidays in Venezuela
 *
 * PHP Version 4
 *
 * @category Date
 * @package  Date_Holidays
 * @author   Alan Mizrahi
 * @license  BSD http://www.opensource.org/licenses/bsd-license.php
 * @version  0.0.1
 * @link     http://pear.php.net/package/Date_Holidays
 */

/**
 * Extends Christian Driver
 */
require_once 'Christian.php';

/**
 * Driver class that calculates holidays in Venezuela
 *
 * @category   Date
 * @package    Date_Holidays
 * @subpackage Driver
 * @author     Alan Mizrahi
 * @license    BSD http://www.opensource.org/licenses/bsd-license.php
 * @version    0.0.1
 * @link       http://pear.php.net/package/Date_Holidays
 */
class Date_Holidays_Driver_Venezuela extends Date_Holidays_Driver_Christian {
    /**
     * Constructor
     *
     * Use the Date_Holidays::factory() method to construct an object of a
     * certain driver
     *
     * @access   protected
     */
    function Date_Holidays_Driver_Venezuela() {
    }

    /**
     * Build the internal arrays that contain data about the calculated holidays
     *
     * @access   protected
     * @return   boolean true on success, otherwise a PEAR_ErrorStack object
     * @throws   object PEAR_ErrorStack
     */
    function _buildHolidays() {
        parent::_buildHolidays();

        // We need a few specific christian holidays
        // before clearing the list of holidays
        $ashWednesdayDate = $this->getHolidayDate('ashWednesday');
        $greenThursday = $this->getHolidayDate('greenThursday');
        $goodFridayDate = $this->getHolidayDate('goodFriday');

        $this->_clearHolidays();

        $this->_addHoliday(
            'newYearsDay',
            $this->_year . '-01-01',
            'New Year\'s Day'
        );
        $this->_addTranslationForHoliday('newYearsDay', 'es_VE', 'Año Nuevo');

        // carnaval
        $carnival = new Date($ashWednesdayDate);
        $carnival = $carnival->getPrevDay();
        $this->_addHoliday('carnival2', $carnival, 'Carnival');
        $carnival = $carnival->getPrevDay();
        $this->_addHoliday('carnival1', $carnival, 'Carnival');
        $this->_addTranslationForHoliday('carnival1', 'es_VE', 'Carnaval');
        $this->_addTranslationForHoliday('carnival2', 'es_VE', 'Carnaval');

        // semana santa
        $this->_addHoliday('holyweek1', $greenThursday, 'Holy Week');
        $this->_addHoliday('holyweek2', $goodFridayDate, 'Holy Week');
        $this->_addTranslationForHoliday('holyweek1', 'es_VE', 'Semana Santa');
        $this->_addTranslationForHoliday('holyweek2', 'es_VE', 'Semana Santa');

        $this->_addHoliday(
            'independenceMovementDay',
            $this->_year . '-04-19',
            'Independence Declaration'
        );
        $this->_addTranslationForHoliday(
            'independenceMovementDay',
            'es_VE',
            'Declaración de Independencia'
        );

        $this->_addHoliday('laborDay', $this->_year . '-05-01', 'Labor Day');
        $this->_addTranslationForHoliday('laborDay', 'es_VE', 'Día del Trabajador');

        $this->_addHoliday(
            'battleOfCarabobo',
            $this->_year . '-06-24',
            'Battle of Carabobo'
        );
        $this->_addTranslationForHoliday(
            'battleOfCarabobo',
            'es_VE',
            'Día de la Batalla de Carabobo'
        );

        $this->_addHoliday(
            'independenceDay',
            $this->_year . '-07-05',
            'Independence Day'
        );
        $this->_addTranslationForHoliday(
            'independenceDay',
            'es_VE',
            'Día de Independencia'
        );

        $this->_addHoliday(
            'bolivarBirthday',
            $this->_year . '-07-24',
            'Birthday of Simon Bolivar'
        );
        $this->_addTranslationForHoliday(
            'bolivarBirthday',
            'es_VE',
            'Natalicio de Simón Bolívar'
        );

        $this->_addHoliday('columbosDay', $this->_year . '-10-12', 'Columbos Day');
        $this->_addTranslationForHoliday('columbosDay', 'es_VE', 'Día de la Raza');

        $this->_addHoliday('christmas', $this->_year . '-12-25', 'Christmas');
        $this->_addTranslationForHoliday('christmas', 'es_VE', 'Navidad');

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
        return ['ve', 'ven'];
    }

    /**
     * clear all holidays
     *
     * @access   private
     * @return   void
     */
    function _clearHolidays() {
        $this->_holidays = [];
        $this->_internalNames = [];
        $this->_dates = [];
        $this->_titles = [];
    }
}
