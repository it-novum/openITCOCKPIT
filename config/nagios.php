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


return [
    'nagios' => [
        'logfilepath' => '/opt/openitc/nagios/var/',
        'logfilename' => 'nagios.log',
        'basepath'    => '/opt/openitc/nagios/',
        'etc'         => 'etc/',
        'bin'         => 'bin/',
        'libexec'     => 'libexec/',
        'nagios_bin'  => 'nagios',
        'nagiostats'  => 'nagiostats',
        'user'        => 'nagios',
        'group'       => 'nagios',
        'verify'      => '-v',
        'nagios_cfg'  => '/opt/openitc/nagios/etc/nagios.cfg',
        'export'      => [
            'minified'                                 => true,
            'backupSource'                             => '/opt/openitc/nagios/etc/config',
            'backupTarget'                             => '/opt/openitc/nagios/backup',
            'path'                                     => '/opt/openitc/nagios/etc/',
            'service_perfdata_file_processing_command' => '/bin/mv /opt/openitc/nagios/var/service-perfdata /opt/openitc/nagios/var/spool/perfdata/service-perfdata.$TIMET$',
            'service_perfdata_command'                 => '/usr/bin/perl /opt/openitc/nagios/libexec/process_perfdata.pl',
            'check_fresh'                              => '/opt/openitc/nagios/libexec/check_dummy 3 "Service is no longer current"',
            'config'                                   => 'config/',
            'suffix'                                   => '.cfg',
            'hosttemplates'                            => 'config/hosttemplates/',
            'hosts'                                    => 'config/hosts/',
            'servicetemplates'                         => 'config/servicetemplates/',
            'services'                                 => 'config/services/',
            'commands'                                 => 'config/commands/',
            'timeperiods'                              => 'config/timeperiods/',
            'contacts'                                 => 'config/contacts/',
            'contactgroups'                            => 'config/contactgroups/',
            'hostgroups'                               => 'config/hostgroups/',
            'hostescalations'                          => 'config/hostescalations/',
            'serviceescalations'                       => 'config/serviceescalations/',
            'servicegroups'                            => 'config/servicegroups/',
            'hostdependencies'                         => 'config/hostdependencies/',
            'servicedependencies'                      => 'config/servicedependencies/',
            'satellite_path'                           => '/opt/openitc/nagios/satellites/',
            'rollout'                                  => '/opt/openitc/nagios/rollout/',

            'macros' => 'resource',
        ],
    ],
];
