<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Filter for Official holidays in Portugal.
 *
 * PHP Version 5
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
 * @author   Ken Guest <kguest@php.net>
 * @license  http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @version  CVS: $Id: Official.php 277509 2009-03-20 10:19:53Z kguest $
 * @link     http://pear.php.net/package/Date_Holidays
 */

/**
 * Filter that only accepts official Portugal holidays.
 * By Carlos Pires; based on Ireland/Official.php by Ken Guest.
 *
 * @category   Date
 * @package    Date_Holidays
 * @subpackage Filter
 * @author     Ken Guest <ken.guest@linux.ie>
 * @license    http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @version    CVS: $Id: Official.php 277509 2009-03-20 10:19:53Z kguest $
 * @link       http://pear.php.net/package/Date_Holidays
 */
class Date_Holidays_Filter_Portugal_Official extends Date_Holidays_Filter_Whitelist {
    /**
     * Constructor.
     *
     */
    function __construct() {
        parent::__construct(
            [
                'newYearsDay',
                'carnival',
                'goodFriday',
                'easter',
                'libertyDayPortugal',
                'dayOfWork',
                'corpusChristi',
                'nationalDayPortugal',
                'mariaAscension',
                'republicDayPortugal',
                'allSaintsDay',
                'independenceOfPortugal',
                'immaculateConceptionDay',
                'christmasDay'
            ]
        );
    }

    /**
     * Constructor.
     *
     * Only accepts official Portugal public holidays as described on
     * https://www.bmf.gv.at/Finanzministerium/GesetzlicheFeiertag_4523/_start.htm
     *
     * @link https://www.bmf.gv.at/Finanzministerium/GesetzlicheFeiertag_4523/_start.htm
     */
    function Date_Holidays_Filter_Portugal_Official() {
        $this->__construct();
    }
}
