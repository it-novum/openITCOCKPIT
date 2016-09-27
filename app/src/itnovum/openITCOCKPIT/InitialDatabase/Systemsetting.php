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
				'Systemsetting.key' => $key
			]
		]);
		return !empty($record);
	}

	/**
	 * @return array
	 */
	public function getData()
	{
		$data = array(
			0 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '1',
							'key' => 'SUDO_SERVER.SOCKET',
							'value' => '/usr/share/openitcockpit/app/run/',
							'info' => 'Path where the sudo server will try to create its socket file',
							'section' => 'SUDO_SERVER',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-16 00:41:41',
						),
				),
			1 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '2',
							'key' => 'SUDO_SERVER.SOCKET_NAME',
							'value' => 'sudo.sock',
							'info' => 'Sudoservers socket name',
							'section' => 'SUDO_SERVER',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-16 00:41:41',
						),
				),
			2 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '3',
							'key' => 'SUDO_SERVER.SOCKETPERMISSIONS',
							'value' => '49588',
							'info' => 'Permissions of the socket file',
							'section' => 'SUDO_SERVER',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-16 00:41:41',
						),
				),
			3 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '4',
							'key' => 'WEBSERVER.USER',
							'value' => 'www-data',
							'info' => 'Username of the webserver',
							'section' => 'WEBSERVER',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-16 00:41:41',
						),
				),
			4 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '5',
							'key' => 'WEBSERVER.GROUP',
							'value' => 'www-data',
							'info' => 'Usergroup of the webserver',
							'section' => 'WEBSERVER',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-16 00:41:41',
						),
				),
			5 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '6',
							'key' => 'SUDO_SERVER.FOLDERPERMISSIONS',
							'value' => '16877',
							'info' => 'Permissions of the socket folder',
							'section' => 'SUDO_SERVER',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-16 00:41:41',
						),
				),
			6 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '20',
							'key' => 'SUDO_SERVER.API_KEY',
							'value' => '1fea123e07f730f76e661bced33a94152378611e',
							'info' => 'API key for the sudoserver socket API',
							'section' => 'SUDO_SERVER',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-16 00:41:41',
						),
				),
			7 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '21',
							'key' => 'MONITORING.USER',
							'value' => 'nagios',
							'info' => 'The user of your monitoring system',
							'section' => 'MONITORING',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-16 00:41:41',
						),
				),
			8 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '22',
							'key' => 'MONITORING.GROUP',
							'value' => 'nagios',
							'info' => 'The group of your monitoring system',
							'section' => 'MONITORING',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-16 00:41:41',
						),
				),
			9 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '23',
							'key' => 'MONITORING.FROM_ADDRESS',
							'value' => 'openitcockpit@example.org',
							'info' => 'Sender mail address for notifications',
							'section' => 'MONITORING',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-16 00:41:41',
						),
				),
			10 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '24',
							'key' => 'MONITORING.FROM_NAME',
							'value' => 'openITCOCKPIT Notification',
							'info' => 'The name we should display in your mail client',
							'section' => 'MONITORING',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-16 00:41:41',
						),
				),
			11 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '25',
							'key' => 'MONITORING.MESSAGE_HEADER',
							'value' => '**** openITCOCKPIT notification by it-novum GmbH ****',
							'info' => 'The header in the plain text mail',
							'section' => 'MONITORING',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-16 00:41:41',
						),
				),
			12 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '26',
							'key' => 'MONITORING.CMD',
							'value' => '/opt/openitc/nagios/var/rw/nagios.cmd',
							'info' => 'The command pipe for your monitoring system',
							'section' => 'MONITORING',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-16 00:41:41',
						),
				),
			13 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '27',
							'key' => 'CRONJOB.RECURRING_DOWNTIME',
							'value' => '10',
							'info' => 'Time in minutes the cron will check for recurring downtimes',
							'section' => 'CRONJOB',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-02 10:59:39',
						),
				),
			14 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '28',
							'key' => 'SYSTEM.ADDRESS',
							'value' => '192.168.0.1',
							'info' => 'The IP address or FQDN of the system',
							'section' => 'SYSTEM',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-16 00:41:41',
						),
				),
			15 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '29',
							'key' => 'MONITORING.HOST.INITSTATE',
							'value' => 'u',
							'info' => 'Host initial state [o,d,u]',
							'section' => 'MONITORING',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-16 00:41:41',
						),
				),
			16 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '30',
							'key' => 'MONITORING.SERVICE.INITSTATE',
							'value' => 'u',
							'info' => 'Service initial state [o,w,u,c]',
							'section' => 'MONITORING',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-16 00:41:41',
						),
				),
			17 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '31',
							'key' => 'MONITORING.RESTART',
							'value' => 'service nagios restart',
							'info' => 'Command to restart your monitoring software',
							'section' => 'MONITORING',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-16 00:41:41',
						),
				),
			18 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '32',
							'key' => 'MONITORING.RELOAD',
							'value' => 'service nagios reload',
							'info' => 'Command to reload your monitoring software',
							'section' => 'MONITORING',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-16 00:41:41',
						),
				),
			19 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '33',
							'key' => 'MONITORING.STOP',
							'value' => 'service nagios stop',
							'info' => 'Command to stop your monitoring software',
							'section' => 'MONITORING',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-16 00:41:41',
						),
				),
			20 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '34',
							'key' => 'MONITORING.START',
							'value' => 'service nagios start',
							'info' => 'Command to start your monitoring software',
							'section' => 'MONITORING',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-16 00:41:41',
						),
				),
			21 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '35',
							'key' => 'FRONTEND.SYSTEMNAME',
							'value' => 'openITCOCKPIT',
							'info' => 'The name of your system',
							'section' => 'FRONTEND',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-16 00:41:41',
						),
				),
			22 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '36',
							'key' => 'SUDO_SERVER.WORKERSOCKET_NAME',
							'value' => 'worker.sock',
							'info' => 'Sudoservers worker socket name',
							'section' => 'SUDO_SERVER',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-16 00:41:41',
						),
				),
			23 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '37',
							'key' => 'SUDO_SERVER.WORKERSOCKETPERMISSIONS',
							'value' => '49588',
							'info' => 'Permissions of the worker socket file',
							'section' => 'SUDO_SERVER',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-16 00:41:41',
						),
				),
			24 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '38',
							'key' => 'CHECK_MK.BIN',
							'value' => '/opt/openitc/nagios/3rd/check_mk/bin/check_mk',
							'info' => 'Path to check_mk binary',
							'section' => 'CHECK_MK',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-16 00:41:41',
						),
				),
			25 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '39',
							'key' => 'SUDO_SERVER.RESPONSESOCKET_NAME',
							'value' => 'response.sock',
							'info' => 'Sudoservers worker socket name',
							'section' => 'SUDO_SERVER',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-16 00:41:41',
						),
				),
			26 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '40',
							'key' => 'SUDO_SERVER.RESPONSESOCKETPERMISSIONS',
							'value' => '49588',
							'info' => 'Permissions of the worker socket file',
							'section' => 'SUDO_SERVER',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-16 00:41:41',
						),
				),
			27 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '41',
							'key' => 'MONITORING.CORECONFIG',
							'value' => '/etc/openitcockpit/nagios.cfg',
							'info' => 'Path to monitoring core configuration file',
							'section' => 'MONITORING',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-16 00:41:41',
						),
				),
			28 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '42',
							'key' => 'CHECK_MK.MATCH',
							'value' => '(perl|dsmc|java|ksh|VBoxHeadless)',
							'info' => 'These are the services that should not be compressed by check_mk as regular expression',
							'section' => 'CHECK_MK',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-16 00:41:41',
						),
				),
			29 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '43',
							'key' => 'CHECK_MK.ETC',
							'value' => '/opt/openitc/nagios/3rd/check_mk/etc/',
							'info' => 'Path to Check_MK confi files',
							'section' => 'CHECK_MK',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-16 00:41:41',
						),
				),
			30 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '44',
							'key' => 'CHECK_MK.VAR',
							'value' => '/opt/openitc/nagios/3rd/check_mk/var/',
							'info' => 'Path to Check_MK variable files',
							'section' => 'CHECK_MK',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-16 00:41:41',
						),
				),
			31 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '45',
							'key' => 'CHECK_MK.ACTIVE_CHECK',
							'value' => 'CHECK_MK_ACTIVE',
							'info' => 'The name of the check_mk active check service template',
							'section' => 'CHECK_MK',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-16 00:41:41',
						),
				),
			32 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '46',
							'key' => 'FRONTEND.MASTER_INSTANCE',
							'value' => 'Mastersystem',
							'info' => 'The name of your openITCOCKPIT main instance',
							'section' => 'FRONTEND',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-16 00:41:41',
						),
				),
			33 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '47',
							'key' => 'FRONTEND.AUTH_METHOD',
							'value' => 'session',
							'info' => 'The authentication method that shoud be used for login',
							'section' => 'FRONTEND',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-16 00:41:41',
						),
				),
			34 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '48',
							'key' => 'FRONTEND.LDAP.ADDRESS',
							'value' => '192.168.1.10',
							'info' => 'The address or hostname of your LDAP server',
							'section' => 'FRONTEND',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-15 19:20:23',
						),
				),
			35 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '49',
							'key' => 'FRONTEND.LDAP.PORT',
							'value' => '389',
							'info' => 'The port where your LDAP server is listen to. Notice: If you want to use TLS the default port 389 shoud work as well! (SSL default Port is 636)',
							'section' => 'FRONTEND',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-15 19:20:23',
						),
				),
			36 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '50',
							'key' => 'FRONTEND.LDAP.BASEDN',
							'value' => 'DC=example,DC=org',
							'info' => 'Your BASEDN',
							'section' => 'FRONTEND',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-15 19:20:23',
						),
				),
			37 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '51',
							'key' => 'FRONTEND.LDAP.USERNAME',
							'value' => 'administrator',
							'info' => 'The username that the system will use to connect to your LDAP server',
							'section' => 'FRONTEND',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-15 19:20:23',
						),
				),
			38 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '52',
							'key' => 'FRONTEND.LDAP.PASSWORD',
							'value' => 'Testing123!',
							'info' => 'The password that the system will use to connect to your LDAP server',
							'section' => 'FRONTEND',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-15 19:20:23',
						),
				),
			39 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '53',
							'key' => 'FRONTEND.LDAP.SUFFIX',
							'value' => '@example.org',
							'info' => 'The Suffix of your domain',
							'section' => 'FRONTEND',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-15 19:20:23',
						),
				),
			40 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '54',
							'key' => 'FRONTEND.LDAP.USE_TLS',
							'value' => '1',
							'info' => 'If PHP shoud upgrade the security of a plain connection to a TLS encrypted connection',
							'section' => 'FRONTEND',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-16 00:41:41',
						),
				),
			41 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '55',
							'key' => 'CRONJOB.CLEANUP_DATABASE',
							'value' => '1440',
							'info' => 'Time in minutes the cron will check for partitions in database and drop old partitions',
							'section' => 'CRONJOB',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-02 10:59:39',
						),
				),
			42 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '56',
							'key' => 'ARCHIVE.AGE.SERVICECHECKS',
							'value' => '2',
							'info' => 'Time in weeks how long service check results will be stored',
							'section' => 'ARCHIVE',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-16 00:41:41',
						),
				),
			43 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '57',
							'key' => 'ARCHIVE.AGE.HOSTCHECKS',
							'value' => '2',
							'info' => 'Time in weeks how long host check results will be stored',
							'section' => 'ARCHIVE',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-16 00:41:41',
						),
				),
			44 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '58',
							'key' => 'ARCHIVE.AGE.STATEHISTORIES',
							'value' => '53',
							'info' => 'Time in weeks how long state change events will be stored',
							'section' => 'ARCHIVE',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-16 00:41:41',
						),
				),
			45 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '59',
							'key' => 'ARCHIVE.AGE.LOGENTRIES',
							'value' => '2',
							'info' => 'Time in weeks how long logentries will be stored',
							'section' => 'ARCHIVE',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-16 00:41:41',
						),
				),
			46 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '60',
							'key' => 'MONITORING.STATUS_DAT',
							'value' => '/opt/openitc/nagios/var/status.dat',
							'info' => 'Path to the status.dat of the monitoring system',
							'section' => 'MONITORING',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-16 00:41:41',
						),
				),
			47 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '61',
							'key' => 'ARCHIVE.AGE.NOTIFICATIONS',
							'value' => '2',
							'info' => 'Time in weeks how long notifications will be stored (keep eq to CONTACTNOTIFICATIONS AND CONTACTNOTIFICATIONMETHODS)',
							'section' => 'ARCHIVE',
							'created' => '2014-12-23 10:32:55',
							'modified' => '2015-01-16 00:41:41',
						),
				),
			48 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '62',
							'key' => 'ARCHIVE.AGE.CONTACTNOTIFICATIONS',
							'value' => '2',
							'info' => 'Time in weeks how long contactnotifications will be stored (keep eq to NOTIFICATIONS AND CONTACTNOTIFICATIONMETHODS)',
							'section' => 'ARCHIVE',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-16 00:41:41',
						),
				),
			49 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '63',
							'key' => 'ARCHIVE.AGE.CONTACTNOTIFICATIONMETHODS',
							'value' => '2',
							'info' => 'Time in weeks how long contactnotificationmethods will be stored (keep eq to NOTIFICATIONS AND CONTACTNOTIFICATIONS)',
							'section' => 'ARCHIVE',
							'created' => '0000-00-00 00:00:00',
							'modified' => '2015-01-16 00:41:41',
						),
				),
			50 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '64',
							'key' => 'CRONJOB.CLENUP_TEMPFILES',
							'value' => '10',
							'info' => 'Deletes tmp files',
							'section' => 'CRONJOB',
							'created' => '2014-12-23 11:45:31',
							'modified' => '2015-01-02 10:59:39',
						),
				),
			51 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '65',
							'key' => 'MONITORING.FRESHNESS_THRESHOLD_ADDITION',
							'value' => '300',
							'info' => 'Value in seconds that get added to the service check interval for passive services, before the monitoring system will fire up the freshness check',
							'section' => 'MONITORING',
							'created' => '2014-12-23 11:45:31',
							'modified' => '2015-01-02 10:59:39',
						),
				),
			52 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '66',
							'key' => 'MONITORING.AFTER_EXPORT',
							'value' => '#echo 1',
							'info' => 'A command that get executed on each export (Notice: this command runs as root, so be careful)',
							'section' => 'MONITORING',
							'created' => '2014-12-23 11:45:31',
							'modified' => '2015-01-02 10:59:39',
						),
				),
			53 =>
				array(
					'Systemsetting' =>
						array(
							'id' => '67',
							'key' => 'TICKET_SYSTEM.URL',
							'value' => '',
							'info' => 'Link to the ticket system',
							'section' => 'TICKET_SYSTEM',
							'created' => '2016-06-13 11:47:47',
							'modified' => '2016-06-13 11:47:47',
						),
				),
		);
		return $data;
	}
}
