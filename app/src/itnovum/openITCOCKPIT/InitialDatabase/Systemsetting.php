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


class Systemsetting extends Importer {
    /**
     * @property \Systemsetting $Model
     */

    /**
     * @return bool
     */
    public function import() {
        $data = $this->getData();
        foreach ($data as $record) {
            if (isset($record['Systemsetting']['id'])) {
                unset($record['Systemsetting']['id']);
            }
            if (!$this->exists($record['Systemsetting']['key'])) {
                $this->Model->create();
                $this->Model->save($record);
            }
        }

        return true;
    }

    public function exists($key) {
        $record = $this->Model->find('first',[
            'conditions' => [
                'Systemsetting.key' => $key,
            ],
        ]);

        return !empty($record);
    }

    /**
     * @return array
     */
    public function getData() {
        $data = [
            [
                'Systemsetting' => [
                    'key'     => 'SUDO_SERVER.API_KEY',
                    'value'   => '1fea123e07f730f76e661bced33a94152378611e',
                    'info'    => 'API key for the sudoserver socket API',
                    'section' => 'SUDO_SERVER'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'WEBSERVER.USER',
                    'value'   => 'www-data',
                    'info'    => 'Username of the webserver',
                    'section' => 'WEBSERVER'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'WEBSERVER.GROUP',
                    'value'   => 'www-data',
                    'info'    => 'Usergroup of the webserver',
                    'section' => 'WEBSERVER'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'MONITORING.USER',
                    'value'   => 'nagios',
                    'info'    => 'The user of your monitoring system',
                    'section' => 'MONITORING'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'MONITORING.GROUP',
                    'value'   => 'nagios',
                    'info'    => 'The group of your monitoring system',
                    'section' => 'MONITORING'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'MONITORING.FROM_ADDRESS',
                    'value'   => 'foo@example.org',
                    'info'    => 'Sender mail address for notifications',
                    'section' => 'MONITORING'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'MONITORING.FROM_NAME',
                    'value'   => 'openITCOCKPIT Notification',
                    'info'    => 'The name we should display in your mail client',
                    'section' => 'MONITORING'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'MONITORING.MESSAGE_HEADER',
                    'value'   => '**** openITCOCKPIT notification by it-novum GmbH ****',
                    'info'    => 'The header in the plain text mail',
                    'section' => 'MONITORING'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'MONITORING.ACK_RECEIVER_SERVER',
                    'value'   => 'imap.gmail.com:993/imap/ssl',
                    'info'    => 'Email server to connect. Must be provided in following format: server.com:port/imap[/ssl]',
                    'section' => 'MONITORING'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'MONITORING.ACK_RECEIVER_ADDRESS',
                    'value'   => 'my_email@gmail.com',
                    'info'    => 'Username for sender notification mail',
                    'section' => 'MONITORING'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'MONITORING.ACK_RECEIVER_PASSWORD',
                    'value'   => 'my_password',
                    'info'    => 'Password for sender notification mail',
                    'section' => 'MONITORING'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'MONITORING.CMD',
                    'value'   => '/opt/openitc/nagios/var/rw/nagios.cmd',
                    'info'    => 'The command pipe for your monitoring system',
                    'section' => 'MONITORING'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'MONITORING.HOST.INITSTATE',
                    'value'   => 'u',
                    'info'    => 'Host initial state [o,d,u]',
                    'section' => 'MONITORING'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'MONITORING.SERVICE.INITSTATE',
                    'value'   => 'u',
                    'info'    => 'Service initial state [o,w,u,c]',
                    'section' => 'MONITORING'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'MONITORING.RESTART',
                    'value'   => 'service nagios restart',
                    'info'    => 'Command to restart your monitoring software',
                    'section' => 'MONITORING'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'MONITORING.RELOAD',
                    'value'   => 'service nagios reload',
                    'info'    => 'Command to reload your monitoring software',
                    'section' => 'MONITORING'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'MONITORING.STOP',
                    'value'   => 'service nagios stop',
                    'info'    => 'Command to stop your monitoring software',
                    'section' => 'MONITORING'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'MONITORING.START',
                    'value'   => 'service nagios start',
                    'info'    => 'Command to start your monitoring software',
                    'section' => 'MONITORING'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'MONITORING.STATUS',
                    'value'   => 'service nagios status',
                    'info'    => 'Command to query the status of your monitoring software',
                    'section' => 'MONITORING'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'MONITORING.CORECONFIG',
                    'value'   => '/etc/openitcockpit/nagios.cfg',
                    'info'    => 'Path to monitoring core configuration file',
                    'section' => 'MONITORING'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'MONITORING.STATUS_DAT',
                    'value'   => '/opt/openitc/nagios/var/status.dat',
                    'info'    => 'Path to the status.dat of the monitoring system',
                    'section' => 'MONITORING'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'MONITORING.FRESHNESS_THRESHOLD_ADDITION',
                    'value'   => '300',
                    'info'    => 'Value in seconds that get added to the service check interval for passive services, before the monitoring system will fire up the freshness check',
                    'section' => 'MONITORING'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'MONITORING.AFTER_EXPORT',
                    'value'   => '#echo 1',
                    'info'    => 'A command that get executed on each export (Notice: this command runs as root, so be careful)',
                    'section' => 'MONITORING'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'MONITORING.SINGLE_INSTANCE_SYNC',
                    'value'   => '0',
                    'info'    => 'If enabled, you can select which openITCOCKPIT instance you like to push the new configuration to. If disabled all instances will be synchronized',
                    'section' => 'MONITORING',
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'MONITORING.QUERY_HANDLER',
                    'value'   => '/opt/openitc/nagios/var/rw/nagios.qh',
                    'info'    => 'Path to the query handler of your monitoring engine',
                    'section' => 'MONITORING'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'MONITORING.HOST_CHECK_ACTIVE_DEFAULT',
                    'value'   => '1',
                    'info'    => 'If enabled, new host templates will have active_checks enabled by default',
                    'section' => 'MONITORING',
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'MONITORING.SERVICE_CHECK_ACTIVE_DEFAULT',
                    'value'   => '1',
                    'info'    => 'If enabled, new service templates will have active_checks enabled by default',
                    'section' => 'MONITORING',
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'SYSTEM.ADDRESS',
                    'value'   => '127.0.0.1',
                    'info'    => 'The IP address or FQDN of the system',
                    'section' => 'SYSTEM'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'FRONTEND.SYSTEMNAME',
                    'value'   => 'openITCOCKPIT',
                    'info'    => 'The name of your system',
                    'section' => 'FRONTEND'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'FRONTEND.SHOW_EXPORT_RUNNING',
                    'value'   => 'yes',
                    'info'    => 'Show if an export is running in headarea',
                    'section' => 'FRONTEND'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'FRONTEND.MASTER_INSTANCE',
                    'value'   => 'Mastersystem',
                    'info'    => 'The name of your openITCOCKPIT main instance',
                    'section' => 'FRONTEND'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'FRONTEND.AUTH_METHOD',
                    'value'   => 'session',
                    'info'    => 'The authentication method that shoud be used for login',
                    'section' => 'FRONTEND',
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'FRONTEND.LDAP.TYPE',
                    'value'   => 'adldap',
                    'info'    => 'LDAP server type',
                    'section' => 'FRONTEND'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'FRONTEND.LDAP.ADDRESS',
                    'value'   => '192.168.1.10',
                    'info'    => 'The address or hostname of your LDAP server',
                    'section' => 'FRONTEND'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'FRONTEND.LDAP.PORT',
                    'value'   => '389',
                    'info'    => 'The port where your LDAP server is listen to. Notice: If you want to use TLS the default port 389 shoud work as well! (SSL default Port is 636)',
                    'section' => 'FRONTEND'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'FRONTEND.LDAP.QUERY',
                    'value'   => '(&(objectClass=user)(samaccounttype=805306368)(objectCategory=person)(cn=*))',
                    'info'    => 'Your Filter Query',
                    'section' => 'FRONTEND'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'FRONTEND.LDAP.BASEDN',
                    'value'   => 'DC=example,DC=org',
                    'info'    => 'Your BASEDN',
                    'section' => 'FRONTEND'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'FRONTEND.LDAP.USERNAME',
                    'value'   => 'administrator',
                    'info'    => 'The username that the system will use to connect to your LDAP server',
                    'section' => 'FRONTEND'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'FRONTEND.LDAP.PASSWORD',
                    'value'   => 'Testing123!',
                    'info'    => 'The password that the system will use to connect to your LDAP server',
                    'section' => 'FRONTEND'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'FRONTEND.LDAP.SUFFIX',
                    'value'   => '@example.org',
                    'info'    => 'The Suffix of your domain',
                    'section' => 'FRONTEND'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'FRONTEND.LDAP.USE_TLS',
                    'value'   => '1',
                    'info'    => 'If PHP should upgrade the security of a plain connection to a TLS encrypted connection',
                    'section' => 'FRONTEND'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'FRONTEND.SSO.CLIENT_ID',
                    'value'   => 'my_client_id',
                    'info'    => 'Client id generated in SSO Server',
                    'section' => 'FRONTEND'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'FRONTEND.SSO.CLIENT_SECRET',
                    'value'   => 'some_client_password',
                    'info'    => 'Client secret generated in SSO Server',
                    'section' => 'FRONTEND'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'FRONTEND.SSO.AUTH_ENDPOINT',
                    'value'   => 'https://sso.server.com/authorization.oauth2',
                    'info'    => 'Authorization endpoint of SSO Server',
                    'section' => 'FRONTEND'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'FRONTEND.SSO.TOKEN_ENDPOINT',
                    'value'   => 'https://sso.server.com/token.oauth2',
                    'info'    => 'Token endpoint of SSO Server',
                    'section' => 'FRONTEND'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'FRONTEND.SSO.USER_ENDPOINT',
                    'value'   => 'https://sso.server.com/userinfo.oauth2',
                    'info'    => 'User info endpoint of SSO Server',
                    'section' => 'FRONTEND'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'FRONTEND.SSO.NO_EMAIL_MESSAGE',
                    'value'   => 'Email address not found. Please contact your <a href="mailto:admin@my.com">administrator</a>',
                    'info'    => 'The error message that appears when provided E-mail address was not found in openITCOCKPIT',
                    'section' => 'FRONTEND'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'FRONTEND.SSO.LOG_OFF_LINK',
                    'value'   => 'https://sso.server.com/sso/logoff',
                    'info'    => 'SSO Server log out link',
                    'section' => 'FRONTEND'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'FRONTEND.CERT.DEFAULT_USER_EMAIL',
                    'value'   => 'default.user@email.de',
                    'info'    => 'Default user E-mail address to be used if no E-mail address was found during the login with certificate',
                    'section' => 'FRONTEND'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'FRONTEND.HIDDEN_USER_IN_CHANGELOG',
                    'value'   => '0',
                    'info'    => 'Hide the user name in the change log due to privacy reasons',
                    'section' => 'FRONTEND'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'FRONTEND.PRESELECTED_DOWNTIME_OPTION',
                    'value'   => '0',
                    'info'    => 'Set preselected Host downtime options individual',
                    'section' => 'FRONTEND'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'FRONTEND.DISABLE_LOGIN_ANIMATION',
                    'value'   => '0',
                    'info'    => 'Determine if you want to disable the animation in login screen.',
                    'section' => 'FRONTEND'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'CHECK_MK.BIN',
                    'value'   => '/opt/openitc/nagios/3rd/check_mk/bin/check_mk',
                    'info'    => 'Path to check_mk binary',
                    'section' => 'CHECK_MK'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'CHECK_MK.MATCH',
                    'value'   => '(perl|dsmc|java|ksh|VBoxHeadless|php)',
                    'info'    => 'These are the services that should not be compressed by check_mk as regular expression',
                    'section' => 'CHECK_MK'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'CHECK_MK.ETC',
                    'value'   => '/opt/openitc/nagios/3rd/check_mk/etc/',
                    'info'    => 'Path to Check_MK confi files',
                    'section' => 'CHECK_MK'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'CHECK_MK.VAR',
                    'value'   => '/opt/openitc/nagios/3rd/check_mk/var/',
                    'info'    => 'Path to Check_MK variable files',
                    'section' => 'CHECK_MK'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'CHECK_MK.ACTIVE_CHECK',
                    'value'   => 'CHECK_MK_ACTIVE',
                    'info'    => 'The name of the check_mk active check service template',
                    'section' => 'CHECK_MK'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'ARCHIVE.AGE.SERVICECHECKS',
                    'value'   => '2',
                    'info'    => 'Time in weeks how long service check results will be stored',
                    'section' => 'ARCHIVE'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'ARCHIVE.AGE.HOSTCHECKS',
                    'value'   => '2',
                    'info'    => 'Time in weeks how long host check results will be stored',
                    'section' => 'ARCHIVE'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'ARCHIVE.AGE.STATEHISTORIES',
                    'value'   => '53',
                    'info'    => 'Time in weeks how long state change events will be stored',
                    'section' => 'ARCHIVE'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'ARCHIVE.AGE.LOGENTRIES',
                    'value'   => '2',
                    'info'    => 'Time in weeks how long logentries will be stored',
                    'section' => 'ARCHIVE'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'ARCHIVE.AGE.NOTIFICATIONS',
                    'value'   => '2',
                    'info'    => 'Time in weeks how long notifications will be stored (keep eq to CONTACTNOTIFICATIONS AND CONTACTNOTIFICATIONMETHODS)',
                    'section' => 'ARCHIVE'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'ARCHIVE.AGE.CONTACTNOTIFICATIONS',
                    'value'   => '2',
                    'info'    => 'Time in weeks how long contactnotifications will be stored (keep eq to NOTIFICATIONS AND CONTACTNOTIFICATIONMETHODS)',
                    'section' => 'ARCHIVE'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'ARCHIVE.AGE.CONTACTNOTIFICATIONMETHODS',
                    'value'   => '2',
                    'info'    => 'Time in weeks how long contactnotificationmethods will be stored (keep eq to NOTIFICATIONS AND CONTACTNOTIFICATIONS)',
                    'section' => 'ARCHIVE'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'INIT.SUDO_SERVER_STATUS',
                    'value'   => 'service sudo_server status',
                    'info'    => 'Command to query the status of your sudo_server',
                    'section' => 'INIT'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'INIT.GEARMAN_WORKER_STATUS',
                    'value'   => 'service gearman_worker status',
                    'info'    => 'Command to query the status of gearman_worker',
                    'section' => 'INIT'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'INIT.OITC_CMD_STATUS',
                    'value'   => 'service oitc_cmd status',
                    'info'    => 'Command to query the status of your oitc_cmd',
                    'section' => 'INIT'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'INIT.NPCD_STATUS',
                    'value'   => 'service npcd status',
                    'info'    => 'Command to query the status of your NPCD',
                    'section' => 'INIT'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'INIT.NDO_STATUS',
                    'value'   => 'service ndo status',
                    'info'    => 'Command to query the status of your NDOUtils',
                    'section' => 'INIT'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'INIT.STATUSENIGNE_STATUS',
                    'value'   => 'service statusengine status',
                    'info'    => 'Command to query the status of your statusengine',
                    'section' => 'INIT'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'INIT.GEARMAN_JOB_SERVER_STATUS',
                    'value'   => 'service gearman-job-server status',
                    'info'    => 'Command to query the status of  gearman-job-server',
                    'section' => 'INIT'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'INIT.PHPNSTA_STATUS',
                    'value'   => 'service phpNSTA status',
                    'info'    => 'Command to query the status of  phpNSTA',
                    'section' => 'INIT'
                ],
            ],
            [
                'Systemsetting' => [
                    'key'     => 'TICKET_SYSTEM.URL',
                    'value'   => '',
                    'info'    => 'Link to the ticket system',
                    'section' => 'TICKET_SYSTEM'
                ],
            ],
        ];

        return $data;
    }
}
