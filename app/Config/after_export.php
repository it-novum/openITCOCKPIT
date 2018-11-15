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
    'after_export' => [
        'SSH' => [

            //Username via the SSH connection is established
            'username'        => 'nagios',

            //Path to your ssh private key file
            'private_key'     => '/var/lib/nagios/.ssh/id_rsa',

            //Path to your ssh public key file
            'public_key'      => '/var/lib/nagios/.ssh/id_rsa.pub',

            //Command to restart remote monitoring engine
            'restart_command' => 'sudo /opt/openitc/nagios/bin/restart-monitoring.sh',

            //Use rsync or PHP SSH lib to copy data
            'use_rsync'       => true,

            /**
             * A command that will be executed on the remote host
             * Be careful with this option
             * Example:
             * 'remote_command' => [
             *        'whoami',
             *        'echo 1 >> /tmp/after_export'
             * ]
             **/
            'remote_command'  => [],

            //Remote SSH port
            'port'            => 22,
        ],

        'REMOTE' => [
            //Path on the remote system were config files will be copied to
            //With ending / !!!!!!
            'path' => '/opt/openitc/nagios/etc/',
        ],
    ],
];

