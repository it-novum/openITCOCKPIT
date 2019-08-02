<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Driver for determining holidays in Russia
 *
 * PHP Version 4 5
 *
 * Copyright (c) 2011 The PHP Group
 *
 * @category Date
 * @package  Date_Holidays
 * @author   Veronica Morales Marquez <veronica.morales.marquez@lut.fi>
 * @author   Poorang Vosough <poorang.vosough@lut.fi>
 * @license  BSD http://www.opensource.org/licenses/bsd-license.php
 * @version  Russia.php v 0.0.4
 * @link     http://pear.php.net/package/Date_Holidays
 */

require_once __DIR__ . '/../../Calc.php';
require_once 'Christian.php';

/**
 * Driver class that calculates Russia holidays
 *
 * @category Date
 * @package  Date_Holidays
 * @author   Veronica Morales Marquez <veronica.morales.marquez@lut.fi>
 * @author   Poorang Vosough <poorang.vosough@lut.fi>
 * @license  BSD http://www.opensource.org/licenses/bsd-license.php
 * @version  Russia.php v 0.0.4
 * @link     http://pear.php.net/package/Date_Holidays
 */
class Date_Holidays_Driver_Russia extends Date_Holidays_Driver {
    /**
     * this driver's name
     *
     * @access   protected
     * @var      string
     */
    var $_driverName = 'Russia';

    /**
     * Constructor
     *
     * Use the Date_Holidays::factory() method to construct an object of
     * a certain driver
     *
     * @access   protected
     */
    function Date_Holidays_Driver_Russia() {
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

        /**
         * New Year's Day and Christmas
         */
        $newYearsDay1 = new Date($this->_year . '-01-01');
        $this->_addHoliday('newYearsDay1', $newYearsDay1, 'New Year\'s Day');
        if ($newYearsDay1->getDayOfWeek() == 0) { // 0 = Sunday
            $this->_addHoliday(
                'newYearsDay2',
                $this->_year . '-01-02',
                'New Year\'s Day'
            );
            $this->_addHoliday(
                'newYearsDay3',
                $this->_year . '-01-03',
                'New Year\'s Day'
            );
            $this->_addHoliday(
                'newYearsDay4',
                $this->_year . '-01-04',
                'New Year\'s Day'
            );
            $this->_addHoliday(
                'newYearsDay5',
                $this->_year . '-01-05',
                'New Year\'s Day'
            );
            $this->_addHoliday(
                'newYearsDay6',
                $this->_year . '-01-06',
                'New Year\'s Day'
            );
            $this->_addHoliday(
                'christmasDay',
                $this->_year . '-01-07',
                'Christmas Day'
            );
        } else if ($newYearsDay1->getDayOfWeek() == 1) { // 1 = Monday
            $this->_addHoliday(
                'newYearsDay2',
                $this->_year . '-01-02',
                'New Year\'s Day'
            );
            $this->_addHoliday(
                'newYearsDay3',
                $this->_year . '-01-03',
                'New Year\'s Day'
            );
            $this->_addHoliday(
                'newYearsDay4',
                $this->_year . '-01-04',
                'New Year\'s Day'
            );
            $this->_addHoliday(
                'newYearsDay5',
                $this->_year . '-01-05',
                'New Year\'s Day'
            );
            $this->_addHoliday(
                'christmasDay',
                $this->_year . '-01-07',
                'Christmas Day'
            );
            $this->_addHoliday(
                'newYearsDay6',
                $this->_year . '-01-08',
                'New Year\'s Day'
            );
        } else if ($newYearsDay1->getDayOfWeek() == 2) { // 2 = Tuesday
            $this->_addHoliday(
                'newYearsDay2',
                $this->_year . '-01-02',
                'New Year\'s Day'
            );
            $this->_addHoliday(
                'newYearsDay3',
                $this->_year . '-01-03',
                'New Year\'s Day'
            );
            $this->_addHoliday(
                'newYearsDay4',
                $this->_year . '-01-04',
                'New Year\'s Day'
            );
            $this->_addHoliday(
                'christmasDay',
                $this->_year . '-01-07',
                'Christmas Day'
            );
            $this->_addHoliday(
                'newYearsDay5',
                $this->_year . '-01-08',
                'New Year\'s Day'
            );
        } else if ($newYearsDay1->getDayOfWeek() == 3) { // 3 = Wednesday
            $this->_addHoliday(
                'newYearsDay2',
                $this->_year . '-01-02',
                'New Year\'s Day'
            );
            $this->_addHoliday(
                'newYearsDay3',
                $this->_year . '-01-03',
                'New Year\'s Day'
            );
            $this->_addHoliday(
                'newYearsDay4',
                $this->_year . '-01-06',
                'New Year\'s Day'
            );
            $this->_addHoliday(
                'christmasDay',
                $this->_year . '-01-07',
                'Christmas Day'
            );
            $this->_addHoliday(
                'newYearsDay5',
                $this->_year . '-01-08',
                'New Year\'s Day'
            );
        } else if ($newYearsDay1->getDayOfWeek() == 4) { // 4 = Thursday
            $this->_addHoliday(
                'newYearsDay2',
                $this->_year . '-01-02',
                'New Year\'s Day'
            );
            $this->_addHoliday(
                'newYearsDay3',
                $this->_year . '-01-05',
                'New Year\'s Day'
            );
            $this->_addHoliday(
                'newYearsDay4',
                $this->_year . '-01-06',
                'New Year\'s Day'
            );
            $this->_addHoliday(
                'christmasDay',
                $this->_year . '-01-07',
                'Christmas Day'
            );
            $this->_addHoliday(
                'newYearsDay5',
                $this->_year . '-01-08',
                'New Year\'s Day'
            );
        } else if ($newYearsDay1->getDayOfWeek() == 5) { // 5 = Friday
            $this->_addHoliday(
                'newYearsDay2',
                $this->_year . '-01-04',
                'New Year\'s Day'
            );
            $this->_addHoliday(
                'newYearsDay3',
                $this->_year . '-01-05',
                'New Year\'s Day'
            );
            $this->_addHoliday(
                'newYearsDay4',
                $this->_year . '-01-06',
                'New Year\'s Day'
            );
            $this->_addHoliday(
                'christmasDay',
                $this->_year . '-01-07',
                'Christmas Day'
            );
            $this->_addHoliday(
                'newYearsDay5',
                $this->_year . '-01-08',
                'New Year\'s Day'
            );
        } else if ($newYearsDay1->getDayOfWeek() == 6) { // 6 = Saturday
            $this->_addHoliday(
                'newYearsDay2',
                $this->_year . '-03-03',
                'New Year\'s Day'
            );
            $this->_addHoliday(
                'newYearsDay3',
                $this->_year . '-01-04',
                'New Year\'s Day'
            );
            $this->_addHoliday(
                'newYearsDay4',
                $this->_year . '-01-05',
                'New Year\'s Day'
            );
            $this->_addHoliday(
                'newYearsDay5',
                $this->_year . '-01-06',
                'New Year\'s Day'
            );
            $this->_addHoliday(
                'christmasDay',
                $this->_year . '-01-07',
                'Christmas Day'
            );
            $this->_addHoliday(
                'newYearsDay6',
                $this->_year . '-01-08',
                'New Year\'s Day'
            );
        }

        /**
         * International Women's Day
         */
        $this->_addHoliday(
            'womensDay2',
            $this->_year . '-03-08',
            'International Women\'s Day'
        );
        $this->_addHoliday(
            'defenderFatherlandDay2',
            $this->_year . '-02-23',
            'Defender of the Fatherland Day'
        );


        /**
         * Spring and Labour Day
         */
        $this->_addHoliday(
            'springLabourDay1',
            $this->_year . '-05-01',
            'Spring and Labour Day'
        );

        /**
         * Victory Day
         */
        $this->_addHoliday(
            'victoryDay1',
            $this->_year . '-05-09',
            'Victory Day'
        );

        /**
         * Russia Day
         */

        $russiaDay = new Date($this->_year . '-06-12');
        $this->_addHoliday('russiaDay', $russiaDay, 'Russia Day');


        /**
         * Unity Day
         */
        $unityDay = new Date($this->_year . '-11-04');
        $this->_addHoliday('unityDay', $unityDay, 'Unity Day');


        if (Date_Holidays::errorsOccurred()) {
            return 'Error in Russia Driver';
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
        return ['ru', 'RU'];
    }
}
