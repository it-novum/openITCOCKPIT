<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//	This program is free software: you can redistribute it and/or modify
//	it under the terms of the GNU General Public License as published by
//	the Free Software Foundation, version 3 of the License.
//
//	This program is distributed in the hope that it will be useful,
//	but WITHOUT ANY WARRANTY; without even the implied warranty of
//	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//	GNU General Public License for more details.
//
//	You should have received a copy of the GNU General Public License
//	along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

// 2.
//	If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//	under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//	License agreement and license key will be shipped with the order
//	confirmation.

/**
 * Class Nagiostat
 * @deprecated
 */
class Nagiostat extends NagiosModuleAppModel {
    public $useTable = false;
    public $MRTG = [
        'NAGIOSVERSION',
        'NAGIOSPID',
        'NUMHOSTS',
        'NUMSERVICES',
        'MINHSTPSC',
        'MAXHSTPSC',
        'AVGHSTPSC',
        'MINPSVHSTPSC',
        'MAXPSVHSTPSC',
        'AVGPSVHSTPSC',
        'MINPSVHSTLAT',
        'MAXPSVHSTLAT',
        'AVGPSVHSTLAT',
        'MINACTHSTPSC',
        'MAXACTHSTPSC',
        'AVGACTHSTPSC',
        'MINACTHSTEXT',
        'MAXACTHSTEXT',
        'AVGACTHSTEXT',
        'MINACTHSTLAT',
        'MAXACTHSTLAT',
        'AVGACTHSTLAT',
        'MINSVCPSC',
        'MAXSVCPSC',
        'AVGSVCPSC',
        'MINPSVSVCPSC',
        'MAXPSVSVCPSC',
        'AVGPSVSVCPSC',
        'MINACTSVCPSC',
        'MAXACTSVCPSC',
        'AVGACTSVCPSC',
        'MINPSVSVCLAT',
        'MAXPSVSVCLAT',
        'AVGPSVSVCLAT',
        'MINACTSVCEXT',
        'MAXACTSVCEXT',
        'AVGACTSVCEXT',
        'MINACTSVCLAT',
        'MAXACTSVCLAT',
        'AVGACTSVCLAT',
        'NUMEXTCMDS1M',
        'NUMEXTCMDS5M',
        'NUMEXTCMDS15M',
        'NUMPSVSVCCHECKS1M',
        'NUMPSVSVCCHECKS5M',
        'NUMPSVSVCCHECKS15M',
        'NUMSACTSVCCHECKS1M',
        'NUMSACTSVCCHECKS5M',
        'NUMSACTSVCCHECKS15M',
        'NUMCACHEDSVCCHECKS1M',
        'NUMCACHEDSVCCHECKS5M',
        'NUMCACHEDSVCCHECKS15M',
        'NUMOACTSVCCHECKS1M',
        'NUMOACTSVCCHECKS5M',
        'NUMOACTSVCCHECKS15M',
        'NUMACTSVCCHECKS1M',
        'NUMACTSVCCHECKS5M',
        'NUMACTSVCCHECKS15M',
        'NUMPSVHSTCHECKS1M',
        'NUMPSVHSTCHECKS5M',
        'NUMPSVHSTCHECKS15M',
        'NUMSERHSTCHECKS1M',
        'NUMSERHSTCHECKS5M',
        'NUMSERHSTCHECKS15M',
        'NUMPARHSTCHECKS1M',
        'NUMPARHSTCHECKS5M',
        'NUMPARHSTCHECKS15M',
        'NUMPARHSTCHECKS1M',
        'NUMPARHSTCHECKS5M',
        'NUMPARHSTCHECKS15M',
        'NUMSACTHSTCHECKS1M',
        'NUMSACTHSTCHECKS5M',
        'NUMSACTHSTCHECKS15M',
        'NUMCACHEDHSTCHECKS1M',
        'NUMCACHEDHSTCHECKS5M',
        'NUMCACHEDHSTCHECKS15M',
        'NUMOACTHSTCHECKS1M',
        'NUMOACTHSTCHECKS5M',
        'NUMOACTHSTCHECKS15M',
        'NUMACTHSTCHECKS1M',
        'NUMACTHSTCHECKS5M',
        'NUMACTHSTCHECKS15M',
        'NUMHSTACTCHK1M',
        'NUMHSTACTCHK5M',
        'NUMHSTACTCHK15M',
        'NUMHSTACTCHK60M',
        'NUMHSTPSVCHK1M',
        'NUMHSTPSVCHK5M',
        'NUMHSTPSVCHK15M',
        'NUMHSTPSVCHK60M',
        'NUMSVCACTCHK1M',
        'NUMSVCACTCHK5M',
        'NUMSVCACTCHK15M',
        'NUMSVCACTCHK60M',
        'NUMSVCPSVCHK1M',
        'NUMSVCPSVCHK5M',
        'NUMSVCPSVCHK15M',
        'NUMSVCPSVCHK60M',
        'NUMSVCCHECKED',
        'NUMHSTCHECKED',
        'PROGRUNTIME',
    ];

    public $delimiter = '|';

    /**
     * @return string
     * @deprecated
     */
    public function command() {
        Configure::load('nagios');
        return sprintf(
            '%s%s%s -c %s -D "%s" -m -d %s',
            Configure::read('nagios.basepath'),
            Configure::read('nagios.bin'),
            Configure::read('nagios.nagiostats'),
            Configure::read('nagios.nagios_cfg'),
            $this->delimiter,
            implode(',', $this->MRTG)
        );
    }

    /**
     * @param $result
     * @return array|false
     * @deprecated
     */
    public function mergeResult($result) {

        /*
         * Nagios add the delimiter also the the end of the string
         * this is bad, because explode will create and empty value in the array,
         * and this throw a warning in array_combine
         * If you know a better solution, email us ASAP :)
         */

        $result = explode($this->delimiter, $result);
        $result_sizeof = sizeof($result);
        if (sizeof($this->MRTG) < $result_sizeof) {
            if ($result[$result_sizeof - 1] == '') {
                unset($result[$result_sizeof - 1]);
            }
        }

        return array_combine($this->MRTG, $result);
    }
}