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

$config = [
    'NagiosModule' => [
        'OBJECTTYPE_ID'   => [
            'HOST'              => 1,
            'SERVICE'           => 2,
            'HOSTGROUP'         => 3,
            'SERVICEGROUP'      => 4,
            'HOSTESCALATION'    => 5,
            'SERVICEESCALATION' => 6,
            'HOSTDEPENDENCY'    => 7,
            'SERVICEDEPENDENCY' => 8,
            'TIMEPERIOD'        => 9,
            'CONTACT'           => 10,
            'CONTACTGROUP'      => 11,
            'COMMAND'           => 12,
        ],
        'CONFIG_TYPE'     => 0,
        'INSTANCE_ID'     => 1,
        'PREFIX'          => '/opt/openitc/nagios/',
        'NAGIOS_CMD'      => 'var/rw/nagios.cmd',
        'BIN'             => 'bin/nagios',
        'ETC'             => 'etc',
        'ETC_BACKUP'      => '/opt/openitc/nagios/backup',
        'SLIDER_STEPSIZE' => 30,
        'SLIDER_MIN'      => 30,
        'SLIDER_MAX'      => 14400,
        'CONFIGFILES'     => [
            'NAGIOS'           => [
                'FILE'         => 'etc/nagios.cfg',
                'TYPE'         => 'cfg',
                'DISPLAY_NAME' => 'Nagios (nagios.cfg)',
            ],
            'NDO2DB'           => [
                'FILE'         => 'etc/ndo2db.cfg',
                'TYPE'         => 'cfg',
                'DISPLAY_NAME' => 'NDO (ndo2db.cfg)',
            ],
            'NDOMOD'           => [
                'FILE'         => 'etc/ndomod.cfg',
                'TYPE'         => 'cfg',
                'DISPLAY_NAME' => 'NDO (ndomod.cfg)',
            ],
            'RESOURCE'         => [
                'FILE'         => 'etc/resource.cfg',
                'TYPE'         => 'cfg',
                'DISPLAY_NAME' => 'User defined macros',
            ],
            'PHPNSTA'          => [
                'FILE'         => 'bin/phpNSTA/config.php',
                'TYPE'         => 'php',
                'DISPLAY_NAME' => 'phpNSTA (config.php)',
            ],
            'NPCD'             => [
                'FILE'         => 'etc/pnp/npcd.cfg',
                'TYPE'         => 'cfg',
                'DISPLAY_NAME' => 'PNP (npcd.cfg)',
            ],
            'PROCESS_PERFDATA' => [
                'FILE'         => 'etc/pnp/process_perfdata.cfg',
                'TYPE'         => 'cfg',
                'DISPLAY_NAME' => 'PNP (process_perfdata.cfg)',
            ],
            'RRA'              => [
                'FILE'         => 'etc/pnp/rra.cfg',
                'TYPE'         => 'cfg',
                'DISPLAY_NAME' => 'RRDTools (rra.cfg)',
            ],
        ],
    ],
];