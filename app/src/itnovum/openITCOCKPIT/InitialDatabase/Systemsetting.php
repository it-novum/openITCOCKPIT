<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//  This program is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, version 3 of the License.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 2.
//  If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//  under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//  License agreement and license key will be shipped with the order
//  confirmation.

namespace itnovum\openITCOCKPIT\InitialDatabase;


class Systemsetting extends Importer
{
    /**
     * @property \Systemsetting $Model
     */

    /**
     * @return bool
     */
    public function import()
    {
        $data = $this->getData();
        foreach ($data as $record) {
            if (!$this->exists($record['Systemsetting']['key'])) {
                $this->Model->create();
                $this->Model->save($record);
            }
        }

        return true;
    }

    public function exists($key)
    {
        $record = $this->Model->find('first', [
            'conditions' => [
                'Systemsetting.key' => $key,
            ],
        ]);

        return !empty($record);
    }

    /**
     * @return array
     */
    public function getData()
    {
        $data = [
            (int)0  => [
                'Systemsetting' => [
                    'id'       => '1',
                    'key'      => 'SUDO_SERVER.SOCKET',
                    'value'    => '/usr/share/openitcockpit/app/run/',
                    'info'     => 'Path where the sudo server will try to create its socket file',
                    'section'  => 'SUDO_SERVER',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)1  => [
                'Systemsetting' => [
                    'id'       => '2',
                    'key'      => 'SUDO_SERVER.SOCKET_NAME',
                    'value'    => 'sudo.sock',
                    'info'     => 'Sudoservers socket name',
                    'section'  => 'SUDO_SERVER',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)2  => [
                'Systemsetting' => [
                    'id'       => '3',
                    'key'      => 'SUDO_SERVER.SOCKETPERMISSIONS',
                    'value'    => '49588',
                    'info'     => 'Permissions of the socket file',
                    'section'  => 'SUDO_SERVER',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)3  => [
                'Systemsetting' => [
                    'id'       => '4',
                    'key'      => 'WEBSERVER.USER',
                    'value'    => 'www-data',
                    'info'     => 'Username of the webserver',
                    'section'  => 'WEBSERVER',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)4  => [
                'Systemsetting' => [
                    'id'       => '5',
                    'key'      => 'WEBSERVER.GROUP',
                    'value'    => 'www-data',
                    'info'     => 'Usergroup of the webserver',
                    'section'  => 'WEBSERVER',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)5  => [
                'Systemsetting' => [
                    'id'       => '6',
                    'key'      => 'SUDO_SERVER.FOLDERPERMISSIONS',
                    'value'    => '16877',
                    'info'     => 'Permissions of the socket folder',
                    'section'  => 'SUDO_SERVER',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)6  => [
                'Systemsetting' => [
                    'id'       => '7',
                    'key'      => 'SUDO_SERVER.API_KEY',
                    'value'    => '1fea123e07f730f76e661bced33a94152378611e',
                    'info'     => 'API key for the sudoserver socket API',
                    'section'  => 'SUDO_SERVER',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)7  => [
                'Systemsetting' => [
                    'id'       => '8',
                    'key'      => 'MONITORING.USER',
                    'value'    => 'nagios',
                    'info'     => 'The user of your monitoring system',
                    'section'  => 'MONITORING',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)8  => [
                'Systemsetting' => [
                    'id'       => '9',
                    'key'      => 'MONITORING.GROUP',
                    'value'    => 'nagios',
                    'info'     => 'The group of your monitoring system',
                    'section'  => 'MONITORING',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)9  => [
                'Systemsetting' => [
                    'id'       => '10',
                    'key'      => 'MONITORING.FROM_ADDRESS',
                    'value'    => 'foo@example.org',
                    'info'     => 'Sender mail address for notifications',
                    'section'  => 'MONITORING',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)10 => [
                'Systemsetting' => [
                    'id'       => '11',
                    'key'      => 'MONITORING.FROM_NAME',
                    'value'    => 'openITCOCKPIT Notification',
                    'info'     => 'The name we should display in your mail client',
                    'section'  => 'MONITORING',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)11 => [
                'Systemsetting' => [
                    'id'       => '12',
                    'key'      => 'MONITORING.MESSAGE_HEADER',
                    'value'    => '**** openITCOCKPIT notification by it-novum GmbH ****',
                    'info'     => 'The header in the plain text mail',
                    'section'  => 'MONITORING',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)12 => [
                'Systemsetting' => [
                    'id'       => '13',
                    'key'      => 'MONITORING.CMD',
                    'value'    => '/opt/openitc/nagios/var/rw/nagios.cmd',
                    'info'     => 'The command pipe for your monitoring system',
                    'section'  => 'MONITORING',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)13 => [
                'Systemsetting' => [
                    'id'       => '14',
                    'key'      => 'CRONJOB.RECURRING_DOWNTIME',
                    'value'    => '10',
                    'info'     => 'Time in minutes the cron will check for recurring downtimes',
                    'section'  => 'CRONJOB',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-02 10:59:39',
                ],
            ],
            (int)14 => [
                'Systemsetting' => [
                    'id'       => '15',
                    'key'      => 'SYSTEM.ADDRESS',
                    'value'    => '127.0.0.1',
                    'info'     => 'The IP address or FQDN of the system',
                    'section'  => 'SYSTEM',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)15 => [
                'Systemsetting' => [
                    'id'       => '16',
                    'key'      => 'MONITORING.HOST.INITSTATE',
                    'value'    => 'u',
                    'info'     => 'Host initial state [o,d,u]',
                    'section'  => 'MONITORING',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)16 => [
                'Systemsetting' => [
                    'id'       => '17',
                    'key'      => 'MONITORING.SERVICE.INITSTATE',
                    'value'    => 'u',
                    'info'     => 'Service initial state [o,w,u,c]',
                    'section'  => 'MONITORING',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)17 => [
                'Systemsetting' => [
                    'id'       => '18',
                    'key'      => 'MONITORING.RESTART',
                    'value'    => 'service nagios restart',
                    'info'     => 'Command to restart your monitoring software',
                    'section'  => 'MONITORING',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)18 => [
                'Systemsetting' => [
                    'id'       => '19',
                    'key'      => 'MONITORING.RELOAD',
                    'value'    => 'service nagios reload',
                    'info'     => 'Command to reload your monitoring software',
                    'section'  => 'MONITORING',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)19 => [
                'Systemsetting' => [
                    'id'       => '20',
                    'key'      => 'MONITORING.STOP',
                    'value'    => 'service nagios stop',
                    'info'     => 'Command to stop your monitoring software',
                    'section'  => 'MONITORING',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)20 => [
                'Systemsetting' => [
                    'id'       => '21',
                    'key'      => 'MONITORING.START',
                    'value'    => 'service nagios start',
                    'info'     => 'Command to start your monitoring software',
                    'section'  => 'MONITORING',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)21 => [
                'Systemsetting' => [
                    'id'       => '22',
                    'key'      => 'MONITORING.STATUS',
                    'value'    => 'service nagios status',
                    'info'     => 'Command to query the status of your monitoring software',
                    'section'  => 'MONITORING',
                    'created'  => '2016-12-05 11:29:04',
                    'modified' => '2016-12-05 11:29:04',
                ],
            ],
            (int)22 => [
                'Systemsetting' => [
                    'id'       => '23',
                    'key'      => 'FRONTEND.SYSTEMNAME',
                    'value'    => 'openITCOCKPIT',
                    'info'     => 'The name of your system',
                    'section'  => 'FRONTEND',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)23 => [
                'Systemsetting' => [
                    'id'       => '24',
                    'key'      => 'SUDO_SERVER.WORKERSOCKET_NAME',
                    'value'    => 'worker.sock',
                    'info'     => 'Sudoservers worker socket name',
                    'section'  => 'SUDO_SERVER',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)24 => [
                'Systemsetting' => [
                    'id'       => '25',
                    'key'      => 'SUDO_SERVER.WORKERSOCKETPERMISSIONS',
                    'value'    => '49588',
                    'info'     => 'Permissions of the worker socket file',
                    'section'  => 'SUDO_SERVER',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)25 => [
                'Systemsetting' => [
                    'id'       => '26',
                    'key'      => 'CHECK_MK.BIN',
                    'value'    => '/opt/openitc/nagios/3rd/check_mk/bin/check_mk',
                    'info'     => 'Path to check_mk binary',
                    'section'  => 'CHECK_MK',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)26 => [
                'Systemsetting' => [
                    'id'       => '27',
                    'key'      => 'SUDO_SERVER.RESPONSESOCKET_NAME',
                    'value'    => 'response.sock',
                    'info'     => 'Sudoservers worker socket name',
                    'section'  => 'SUDO_SERVER',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)27 => [
                'Systemsetting' => [
                    'id'       => '28',
                    'key'      => 'SUDO_SERVER.RESPONSESOCKETPERMISSIONS',
                    'value'    => '49588',
                    'info'     => 'Permissions of the worker socket file',
                    'section'  => 'SUDO_SERVER',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)28 => [
                'Systemsetting' => [
                    'id'       => '29',
                    'key'      => 'MONITORING.CORECONFIG',
                    'value'    => '/etc/openitcockpit/nagios.cfg',
                    'info'     => 'Path to monitoring core configuration file',
                    'section'  => 'MONITORING',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)29 => [
                'Systemsetting' => [
                    'id'       => '30',
                    'key'      => 'CHECK_MK.MATCH',
                    'value'    => '(perl|dsmc|java|ksh|VBoxHeadless)',
                    'info'     => 'These are the services that should not be compressed by check_mk as regular expression',
                    'section'  => 'CHECK_MK',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)30 => [
                'Systemsetting' => [
                    'id'       => '31',
                    'key'      => 'CHECK_MK.ETC',
                    'value'    => '/opt/openitc/nagios/3rd/check_mk/etc/',
                    'info'     => 'Path to Check_MK confi files',
                    'section'  => 'CHECK_MK',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)31 => [
                'Systemsetting' => [
                    'id'       => '32',
                    'key'      => 'CHECK_MK.VAR',
                    'value'    => '/opt/openitc/nagios/3rd/check_mk/var/',
                    'info'     => 'Path to Check_MK variable files',
                    'section'  => 'CHECK_MK',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)32 => [
                'Systemsetting' => [
                    'id'       => '33',
                    'key'      => 'CHECK_MK.ACTIVE_CHECK',
                    'value'    => 'CHECK_MK_ACTIVE',
                    'info'     => 'The name of the check_mk active check service template',
                    'section'  => 'CHECK_MK',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)33 => [
                'Systemsetting' => [
                    'id'       => '34',
                    'key'      => 'FRONTEND.MASTER_INSTANCE',
                    'value'    => 'Mastersystem',
                    'info'     => 'The name of your openITCOCKPIT main instance',
                    'section'  => 'FRONTEND',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)34 => [
                'Systemsetting' => [
                    'id'       => '35',
                    'key'      => 'FRONTEND.AUTH_METHOD',
                    'value'    => 'session',
                    'info'     => 'The authentication method that shoud be used for login',
                    'section'  => 'FRONTEND',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)35 => [
                'Systemsetting' => [
                    'id'       => '36',
                    'key'      => 'FRONTEND.LDAP.ADDRESS',
                    'value'    => '192.168.1.10',
                    'info'     => 'The address or hostname of your LDAP server',
                    'section'  => 'FRONTEND',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-15 19:20:23',
                ],
            ],
            (int)36 => [
                'Systemsetting' => [
                    'id'       => '37',
                    'key'      => 'FRONTEND.LDAP.PORT',
                    'value'    => '389',
                    'info'     => 'The port where your LDAP server is listen to. Notice: If you want to use TLS the default port 389 shoud work as well! (SSL default Port is 636)',
                    'section'  => 'FRONTEND',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-15 19:20:23',
                ],
            ],
            (int)37 => [
                'Systemsetting' => [
                    'id'       => '38',
                    'key'      => 'FRONTEND.LDAP.BASEDN',
                    'value'    => 'DC=example,DC=org',
                    'info'     => 'Your BASEDN',
                    'section'  => 'FRONTEND',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-15 19:20:23',
                ],
            ],
            (int)38 => [
                'Systemsetting' => [
                    'id'       => '39',
                    'key'      => 'FRONTEND.LDAP.USERNAME',
                    'value'    => 'administrator',
                    'info'     => 'The username that the system will use to connect to your LDAP server',
                    'section'  => 'FRONTEND',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-15 19:20:23',
                ],
            ],
            (int)39 => [
                'Systemsetting' => [
                    'id'       => '40',
                    'key'      => 'FRONTEND.LDAP.PASSWORD',
                    'value'    => 'Testing123!',
                    'info'     => 'The password that the system will use to connect to your LDAP server',
                    'section'  => 'FRONTEND',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-15 19:20:23',
                ],
            ],
            (int)40 => [
                'Systemsetting' => [
                    'id'       => '41',
                    'key'      => 'FRONTEND.LDAP.SUFFIX',
                    'value'    => '@example.org',
                    'info'     => 'The Suffix of your domain',
                    'section'  => 'FRONTEND',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-15 19:20:23',
                ],
            ],
            (int)41 => [
                'Systemsetting' => [
                    'id'       => '42',
                    'key'      => 'FRONTEND.LDAP.USE_TLS',
                    'value'    => '1',
                    'info'     => 'If PHP should upgrade the security of a plain connection to a TLS encrypted connection',
                    'section'  => 'FRONTEND',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)42 => [
                'Systemsetting' => [
                    'id'       => '43',
                    'key'      => 'CRONJOB.CLEANUP_DATABASE',
                    'value'    => '1440',
                    'info'     => 'Time in minutes the cron will check for partitions in database and drop old partitions',
                    'section'  => 'CRONJOB',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-02 10:59:39',
                ],
            ],
            (int)43 => [
                'Systemsetting' => [
                    'id'       => '44',
                    'key'      => 'ARCHIVE.AGE.SERVICECHECKS',
                    'value'    => '2',
                    'info'     => 'Time in weeks how long service check results will be stored',
                    'section'  => 'ARCHIVE',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)44 => [
                'Systemsetting' => [
                    'id'       => '45',
                    'key'      => 'ARCHIVE.AGE.HOSTCHECKS',
                    'value'    => '2',
                    'info'     => 'Time in weeks how long host check results will be stored',
                    'section'  => 'ARCHIVE',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)45 => [
                'Systemsetting' => [
                    'id'       => '46',
                    'key'      => 'ARCHIVE.AGE.STATEHISTORIES',
                    'value'    => '53',
                    'info'     => 'Time in weeks how long state change events will be stored',
                    'section'  => 'ARCHIVE',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)46 => [
                'Systemsetting' => [
                    'id'       => '47',
                    'key'      => 'ARCHIVE.AGE.LOGENTRIES',
                    'value'    => '2',
                    'info'     => 'Time in weeks how long logentries will be stored',
                    'section'  => 'ARCHIVE',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)47 => [
                'Systemsetting' => [
                    'id'       => '48',
                    'key'      => 'MONITORING.STATUS_DAT',
                    'value'    => '/opt/openitc/nagios/var/status.dat',
                    'info'     => 'Path to the status.dat of the monitoring system',
                    'section'  => 'MONITORING',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)48 => [
                'Systemsetting' => [
                    'id'       => '49',
                    'key'      => 'ARCHIVE.AGE.NOTIFICATIONS',
                    'value'    => '2',
                    'info'     => 'Time in weeks how long notifications will be stored (keep eq to CONTACTNOTIFICATIONS AND CONTACTNOTIFICATIONMETHODS)',
                    'section'  => 'ARCHIVE',
                    'created'  => '2014-12-23 10:32:55',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)49 => [
                'Systemsetting' => [
                    'id'       => '50',
                    'key'      => 'ARCHIVE.AGE.CONTACTNOTIFICATIONS',
                    'value'    => '2',
                    'info'     => 'Time in weeks how long contactnotifications will be stored (keep eq to NOTIFICATIONS AND CONTACTNOTIFICATIONMETHODS)',
                    'section'  => 'ARCHIVE',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)50 => [
                'Systemsetting' => [
                    'id'       => '51',
                    'key'      => 'ARCHIVE.AGE.CONTACTNOTIFICATIONMETHODS',
                    'value'    => '2',
                    'info'     => 'Time in weeks how long contactnotificationmethods will be stored (keep eq to NOTIFICATIONS AND CONTACTNOTIFICATIONS)',
                    'section'  => 'ARCHIVE',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)51 => [
                'Systemsetting' => [
                    'id'       => '52',
                    'key'      => 'CRONJOB.CLENUP_TEMPFILES',
                    'value'    => '10',
                    'info'     => 'Deletes tmp files',
                    'section'  => 'CRONJOB',
                    'created'  => '2014-12-23 11:45:31',
                    'modified' => '2015-01-02 10:59:39',
                ],
            ],
            (int)52 => [
                'Systemsetting' => [
                    'id'       => '53',
                    'key'      => 'MONITORING.FRESHNESS_THRESHOLD_ADDITION',
                    'value'    => '300',
                    'info'     => 'Value in seconds that get added to the service check interval for passive services, before the monitoring system will fire up the freshness check',
                    'section'  => 'MONITORING',
                    'created'  => '2014-12-23 11:45:31',
                    'modified' => '2015-01-02 10:59:39',
                ],
            ],
            (int)53 => [
                'Systemsetting' => [
                    'id'       => '54',
                    'key'      => 'MONITORING.AFTER_EXPORT',
                    'value'    => '#echo 1',
                    'info'     => 'A command that get executed on each export (Notice: this command runs as root, so be careful)',
                    'section'  => 'MONITORING',
                    'created'  => '2014-12-23 11:45:31',
                    'modified' => '2015-01-02 10:59:39',
                ],
            ],
            (int)54 => [
                'Systemsetting' => [
                    'id'       => '55',
                    'key'      => 'INIT.SUDO_SERVER_STATUS',
                    'value'    => 'service sudo_server status',
                    'info'     => 'Command to query the status of your sudo_server',
                    'section'  => 'INIT',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '1970-01-01 00:00:00',
                ],
            ],
            (int)55 => [
                'Systemsetting' => [
                    'id'       => '56',
                    'key'      => 'INIT.GEARMAN_WORKER_STATUS',
                    'value'    => 'service gearman_worker status',
                    'info'     => 'Command to query the status of gearman_worker',
                    'section'  => 'INIT',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '1970-01-01 00:00:00',
                ],
            ],
            (int)56 => [
                'Systemsetting' => [
                    'id'       => '57',
                    'key'      => 'INIT.OITC_CMD_STATUS',
                    'value'    => 'service oitc_cmd status',
                    'info'     => 'Command to query the status of your oitc_cmd',
                    'section'  => 'INIT',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '1970-01-01 00:00:00',
                ],
            ],
            (int)57 => [
                'Systemsetting' => [
                    'id'       => '58',
                    'key'      => 'INIT.NPCD_STATUS',
                    'value'    => 'service npcd status',
                    'info'     => 'Command to query the status of your NPCD',
                    'section'  => 'INIT',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '1970-01-01 00:00:00',
                ],
            ],
            (int)58 => [
                'Systemsetting' => [
                    'id'       => '59',
                    'key'      => 'INIT.NDO_STATUS',
                    'value'    => 'service ndo status',
                    'info'     => 'Command to query the status of your NDOUtils',
                    'section'  => 'INIT',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '1970-01-01 00:00:00',
                ],
            ],
            (int)59 => [
                'Systemsetting' => [
                    'id'       => '60',
                    'key'      => 'INIT.STATUSENIGNE_STATUS',
                    'value'    => 'service statusengine status',
                    'info'     => 'Command to query the status of your statusengine',
                    'section'  => 'INIT',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '1970-01-01 00:00:00',
                ],
            ],
            (int)60 => [
                'Systemsetting' => [
                    'id'       => '61',
                    'key'      => 'INIT.GEARMAN_JOB_SERVER_STATUS',
                    'value'    => 'service gearman-job-server status',
                    'info'     => 'Command to query the status of  gearman-job-server',
                    'section'  => 'INIT',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '1970-01-01 00:00:00',
                ],
            ],
            (int)61 => [
                'Systemsetting' => [
                    'id'       => '62',
                    'key'      => 'INIT.PHPNSTA_STATUS',
                    'value'    => 'service phpNSTA status',
                    'info'     => 'Command to query the status of  phpNSTA',
                    'section'  => 'INIT',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '1970-01-01 00:00:00',
                ],
            ],
            (int)62 => [
                'Systemsetting' => [
                    'id'       => '63',
                    'key'      => 'TICKET_SYSTEM.URL',
                    'value'    => '',
                    'info'     => 'Link to the ticket system',
                    'section'  => 'TICKET_SYSTEM',
                    'created'  => '2016-06-13 11:47:47',
                    'modified' => '2016-06-13 11:47:47',
                ],
            ],
            (int)63 => [
                'Systemsetting' => [
                    'id'       => '64',
                    'key'      => 'MONITORING.QUERY_HANDLER',
                    'value'    => '/opt/openitc/nagios/var/rw/nagios.qh',
                    'info'     => 'Path to the query handler of your monitoring engine',
                    'section'  => 'MONITORING',
                    'created'  => '2016-06-13 11:47:47',
                    'modified' => '2016-06-13 11:47:47',
                ],
            ],
            (int)64 => [
                'Systemsetting' => [
                    'id'       => '65',
                    'key'      => 'FRONTEND.SSO.CLIENT_ID',
                    'value'    => '1',
                    'info'     => 'Client id generated in SSO Server',
                    'section'  => 'FRONTEND',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)65 => [
                'Systemsetting' => [
                    'id'       => '66',
                    'key'      => 'FRONTEND.SSO.CLIENT_SECRET',
                    'value'    => '1',
                    'info'     => 'Client secret generated in SSO Server',
                    'section'  => 'FRONTEND',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)66 => [
                'Systemsetting' => [
                    'id'       => '67',
                    'key'      => 'FRONTEND.SSO.AUTH_ENDPOINT',
                    'value'    => '1',
                    'info'     => 'Authorization endpoint of SSO Server',
                    'section'  => 'FRONTEND',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)67 => [
                'Systemsetting' => [
                    'id'       => '68',
                    'key'      => 'FRONTEND.SSO.TOKEN_ENDPOINT',
                    'value'    => '1',
                    'info'     => 'Token endpoint of SSO Server',
                    'section'  => 'FRONTEND',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)68 => [
                'Systemsetting' => [
                    'id'       => '69',
                    'key'      => 'FRONTEND.SSO.USER_ENDPOINT',
                    'value'    => '1',
                    'info'     => 'User info endpoint of SSO Server',
                    'section'  => 'FRONTEND',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)69 => [
                'Systemsetting' => [
                    'id'       => '70',
                    'key'      => 'FRONTEND.SSO.NO_EMAIL_MESSAGE',
                    'value'    => '1',
                    'info'     => 'The error message that appears when provided E-mail address was not found in openITCOCKPIT',
                    'section'  => 'FRONTEND',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)71 => [
                'Systemsetting' => [
                    'id'       => '71',
                    'key'      => 'FRONTEND.SSO.LOG_OFF_LINK',
                    'value'    => '1',
                    'info'     => 'SSO Server log out link',
                    'section'  => 'FRONTEND',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
            (int)72 => [
                'Systemsetting' => [
                    'id'       => '72',
                    'key'      => 'FRONTEND.CERT.DEFAULT_USER_EMAIL',
                    'value'    => 'default.user@email.de',
                    'info'     => 'Default user E-mail address to be used if no E-mail address was found during the login with certificate',
                    'section'  => 'FRONTEND',
                    'created'  => '1970-01-01 00:00:00',
                    'modified' => '2015-01-16 00:41:41',
                ],
            ],
        ];

        return $data;
    }
}
