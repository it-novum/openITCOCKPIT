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
 * Grapher template for check_snmp_traffic
 */

$serviceName = $service['Service']['name'];
if ($service['Service']['name'] == '' || $service['Service']['name'] == null) {
    $serviceName = $service['Servicetemplate']['name'];
}

$templateSettings = [
    [
        '--vertical-label=Traffic bits per sec',
        '--title=Traffic bits per sec',
        'DEF:var1=' . $rrd_path . $service['Host']['uuid'] . DS . $service['Service']['uuid'] . '.rrd:1:AVERAGE',
        'DEF:var2=' . $rrd_path . $service['Host']['uuid'] . DS . $service['Service']['uuid'] . '.rrd:2:AVERAGE',
        'CDEF:var3=var2,-1,*',
        'AREA:var1#FF000055:',
        'LINE1:var1#FF0000FF:in',
        'GPRINT:var1:LAST:%7.2lf bits last',
        'GPRINT:var1:AVERAGE:%7.2lf bits avg',
        'GPRINT:var1:MAX:%7.2lf bits max\n',
        'AREA:var3#00ff0055:',
        'LINE1:var3#00ff00FF:out',
        'GPRINT:var2:LAST:%7.2lf bits last',
        'GPRINT:var2:AVERAGE:%7.2lf bits avg',
        'GPRINT:var2:MAX:%7.2lf bits max',
    ],
    [
        '--vertical-label=Utilisation %',
        '--title=Utilisation % (' . $serviceName . ')',
        'DEF:var1=' . $rrd_path . $service['Host']['uuid'] . DS . $service['Service']['uuid'] . '.rrd:3:AVERAGE',
        'AREA:var1#0000FF55:',
        'LINE1:var1#0000FFFF:Utilisation',
        'GPRINT:var1:LAST:%7.2lf %% last',
        'GPRINT:var1:AVERAGE:%7.2lf %% avg',
        'GPRINT:var1:MAX:%7.2lf %% max',
    ],
];

