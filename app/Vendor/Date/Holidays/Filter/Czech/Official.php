<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Filter for Official holidays in Czech Republic.
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
 * @author   Martin Zdrahal <zdrahal@ipnp.mff.cuni.cz>
 * @license  http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @version  CVS: $Id$
 * @link     http://pear.php.net/package/Date_Holidays
 */

/**
 * Filter that only accepts official holidays of the Czech Republic.
 * By Martin Zdrahal; based on Austria/Official.php by Karin Seifert-Lorenz.
 *
 * @category   Date
 * @package    Date_Holidays
 * @subpackage Filter
 * @author     Martin Zdrahal <zdrahal@ipnp.mff.cuni.cz>
 * @license    http://www.php.net/license/3_01.txt PHP License 3.0.1
 * @link       http://pear.php.net/package/Date_Holidays
 */
class Date_Holidays_Filter_Czech_Official extends Date_Holidays_Filter_Whitelist {
    /**
     * Constructor.
     *
     */
    function __construct() {
        parent::__construct(
            [
                'newYearsDay',
                'IndependentCzechState',
                'easterMonday',
                'dayOfWork',
                'liberationDay',
                'CyrilMethodius',
                'HusDay',
                'WenceslasDay',
                'nationalDayCzechoslovakia',
                'FreedomDay',
                'christmasEve',
                'christmasDay',
                'boxingDay'
            ]
        );
    }

    /**
     * Constructor.
     *
     * Only accepts official public holidays of the Czech Republic as described on
     * http://www.mpsv.cz/cs/74
     *
     * @link http://www.mpsv.cz/cs/74
     */
    function Date_Holidays_Filter_Czech_Official() {
        $this->__construct();
    }
}
