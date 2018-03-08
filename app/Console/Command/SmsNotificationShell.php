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
 * Usage:
 *  Host:
 *   oitc smsNotification --address 128.1.1.85 -m nrpe --type host --hostname c36b8048-93ce-4385-ac19-ab5c90574b77 --contactpager 0049123456789 --notificationtype PROBLEM
 *
 *  Monitoring command:
 *   /usr/share/openitcockpit/app/Console/cake sms_notification -q --address 128.1.1.85 -m nrpe --type Host --contactpager $CONTACTPAGER$ --hostname "$HOSTNAME$" --notificationtype "$NOTIFICATIONTYPE$"
 *
 *  Services:
 *   oitc smsNotification --address 128.1.1.85 -m nrpe --type service --hostname c36b8048-93ce-4385-ac19-ab5c90574b77 --servicedesc 74f14950-a58f-4f18-b6c3-5cfa9dffef4e --contactpager 0049123456789 --notificationtype PROBLEM
 *
*  Monitoring command:
 *   /usr/share/openitcockpit/app/Console/cake sms_notification -q --address 128.1.1.85 -m nrpe --type Service --contactpager $CONTACTPAGER$ --hostname "$HOSTNAME$" --servicedesc "$SERVICEDESC$" --notificationtype "$NOTIFICATIONTYPE$"
 */

use itnovum\openITCOCKPIT\Core\DbBackend;
use itnovum\openITCOCKPIT\Core\HoststatusFields;
use itnovum\openITCOCKPIT\Core\ServicestatusFields;

class SmsNotificationShell extends AppShell
{
    public $uses = [
        'Host',
        'Service',
        'Servicetemplate',
        MONITORING_HOSTSTATUS,
        MONITORING_SERVICESTATUS,
    ];

    public function main()
    {
        $this->config = [
            'nrpe' => [
                'port'                     => 5666,
                'timeout'                  => 120,
                'check_nrpe'               => '/opt/openitc/nagios/libexec/check_nrpe',
                'command_template_host'    => ' -H %s -p %s -t %s -c send_sms -a %s %s %s %s %s "cmd ak %s"',
                'command_template_service' => ' -H %s -p %s -t %s -c send_sms -a %s %s %s %s %s "cmd ak %s %s"',
                'date_format'              => 'd.m.Y H:i:s',
            ],
        ];

        Configure::load('dbbackend');
        $DbBackend = new DbBackend(Configure::read('dbbackend'));

        $address = $this->params['address'];
        $method = 'nrpe';
        if (isset($this->params['method'])) {
            $method = strtolower($this->params['method']);
        }

        if (array_key_exists('type', $this->params)) {
            if ($this->params['type'] === 'Host' || $this->params['type'] === 'host') {
                $hostUuid = $this->params['hostname'];
                $hostname = $this->getHostname($hostUuid);
                $HoststatusFields = new HoststatusFields($DbBackend);
                $HoststatusFields->output();
                $hoststatus = $this->Hoststatus->byUuid($hostUuid, $HoststatusFields);
                switch ($method) {
                    case 'nrpe':
                        $args = vsprintf($this->config['nrpe']['command_template_host'], [
                            escapeshellarg($address),
                            escapeshellarg($this->config['nrpe']['port']),
                            escapeshellarg($this->config['nrpe']['timeout']),
                            escapeshellarg($this->params['contactpager']),
                            escapeshellarg($this->params['notificationtype']),
                            escapeshellarg($hostname),
                            escapeshellarg($hoststatus['Hoststatus']['output']),
                            escapeshellarg(date($this->config['nrpe']['date_format'])),
                            escapeshellarg($hostname),
                        ]);

                        $command = $this->config['nrpe']['check_nrpe'].$args;
                        //debug($command);
                        exec($command);
                        break;
                }
            }

            if ($this->params['type'] === 'Service' || $this->params['type'] === 'service') {
                $hostUuid = $this->params['hostname'];
                $serviceUuid = $this->params['servicedesc'];
                $hostname = $this->getHostname($hostUuid);
                $servicename = $this->getServicename($serviceUuid);
                $ServicestatusFields = new ServicestatusFields($DbBackend);
                $ServicestatusFields->output();
                $servicestatus = $this->Servicestatus->byUuid($serviceUuid, $ServicestatusFields);
                switch ($method) {
                    case 'nrpe':
                        $args = vsprintf($this->config['nrpe']['command_template_service'], [
                            escapeshellarg($address),
                            escapeshellarg($this->config['nrpe']['port']),
                            escapeshellarg($this->config['nrpe']['timeout']),
                            escapeshellarg($this->params['contactpager']),
                            escapeshellarg($this->params['notificationtype']),
                            escapeshellarg($hostname.'/'.$servicename),
                            escapeshellarg($servicestatus['Servicestatus']['output']),
                            escapeshellarg(date($this->config['nrpe']['date_format'])),
                            escapeshellarg($hostname),
                            escapeshellarg($servicename),
                        ]);

                        $command = $this->config['nrpe']['check_nrpe'].$args;
                        //debug($command);
                        exec($command);
                        break;
                }
            }
        }
    }

    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser->addOptions([
            'address'          => ['help' => __d('oitc_console', 'IP address of the SMS gateway')],
            'type'             => ['short' => 't', 'help' => __d('oitc_console', 'Type of the notification host or service')],
            'notificationtype' => ['help' => __d('oitc_console', 'Notification type of monitoring engine')],
            'method'           => ['short' => 'm', 'help' => __d('oitc_console', 'Transport method for example NRPE')],
            'hostname'         => ['help' => __d('oitc_console', 'Host uuid you want to send a notification')],
            'contactpager'     => ['help' => __d('oitc_console', 'recivers mail address')],
            'servicedesc'      => ['help' => __d('oitc_console', 'Service uuid you want to notify')],
        ]);

        return $parser;
    }

    public function getHostname($hostUuid)
    {
        //$host = $this->Host->findByUuid($hostUuid);
        $host = $this->Host->find('first', [
            'recurisve'  => -1,
            'conditions' => [
                'Host.uuid' => $hostUuid,
            ],
            'fields'     => [
                'Host.id',
                'Host.name',
                'Host.uuid',
            ],
            'contain'    => [],
        ]);

        return $host['Host']['name'];
    }

    public function getServicename($serviceUuid)
    {
        $service = $this->Service->find('first', [
            'recurisve'  => -1,
            'conditions' => [
                'Service.uuid' => $serviceUuid,
            ],
            'fields'     => [
                'Service.id',
                'Service.uuid',
                'Service.name',
            ],
            'contain'    => [
                'Servicetemplate' => [
                    'fields' => [
                        'Servicetemplate.id',
                        'Servicetemplate.name',
                    ],
                ],
            ],
        ]);
        $serviceName = $service['Service']['name'];
        if ($serviceName === null) {
            $serviceName = $service['Servicetemplate']['name'];
        }

        return $serviceName;
    }

    public function _welcome()
    {
        //Disable CakePHP welcome messages
    }
}
