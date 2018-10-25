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

/*
 * Daniel Zielger <daniel.ziegler@it-novum.com>
 * Usage Example: ./nagiostats -D '|' -m -d $OUTPUT OF THIS SCRIPT$
 */

$minMax = [
    'HSTPSC',
    'PSVHSTPSC',
    'PSVHSTLAT',
    'ACTHSTPSC',
    'ACTHSTEXT',
    'ACTHSTLAT',
    'SVCPSC',
    'PSVSVCPSC',
    'ACTSVCPSC',
    'PSVSVCLAT',
    'ACTSVCEXT',
    'ACTSVCLAT',
];

$values = [
    0 => 'MIN',
    1 => 'MAX',
    2 => 'AVG',
];

//Define static values:
$echo = [
    'NAGIOSVERSION',
    'NUMHOSTS',
    'NUMSERVICES',
    'NAGIOSPID',
];

foreach ($minMax as $key) {
    for ($i = 0; $i < 3; $i++) {
        $echo[] = $values[$i] . $key;
    }
}

$numeric = [
    'NUMEXTCMDS',
    'NUMPSVSVCCHECKS',
    'NUMSACTSVCCHECKS',
    'NUMCACHEDSVCCHECKS',
    'NUMOACTSVCCHECKS',
    'NUMACTSVCCHECKS',
    'NUMPSVHSTCHECKS',
    'NUMSERHSTCHECKS',
    'NUMPARHSTCHECKS',
    'NUMPARHSTCHECKS',
    'NUMSACTHSTCHECKS',
    'NUMCACHEDHSTCHECKS',
    'NUMOACTHSTCHECKS',
    'NUMACTHSTCHECKS',
];


$values = [
    0 => 1,
    1 => 5,
    2 => 15,
];

foreach ($numeric as $key) {
    for ($i = 0; $i < 3; $i++) {
        $echo[] = $key . $values[$i] . 'M';
    }
}

$numeric = [
    'NUMHSTACTCHK',
    'NUMHSTPSVCHK',
    'NUMSVCACTCHK',
    'NUMSVCPSVCHK',
    'NUMHSTCHECKED',
    'NUMSVCCHECKED',

];


$values = [
    0 => 1,
    1 => 5,
    2 => 15,
    3 => 60,
];

foreach ($numeric as $key) {
    for ($i = 0; $i < 4; $i++) {
        $echo[] = $key . $values[$i] . 'M';
    }
}

echo implode(',', $echo);