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

class NagiosNotificationShell extends AppShell {
    public $tasks = ['NagiosNotification'];
    public $uses = ['Systemsetting', 'Host', 'Service', 'Hosttemplate', 'Servicetemplate'];

    public function main() {

        $this->logfile = null;

        $this->NagiosNotification->construct();

        if (array_key_exists('type', $this->params)) {
            $parameters = $this->fetchEnv();
            //$this->dump($parameters);

            $parameters['format'] = 'both';
            if (array_key_exists('format', $this->params)) {
                $parameters['format'] = $this->params['format'];
            }

            $parameters['no-attachments'] = false;
            if (array_key_exists('no-attachments', $this->params)) {
                $parameters['no-attachments'] = true;
            }

            if ($this->params['type'] === 'Host') {
                $host = $this->getHostname($parameters['hostname']);
                $parameters['hostUuid'] = $parameters['hostname'];
                $parameters['hostname'] = $host['hostname'];
                $parameters['hostdescription'] = $host['hostdescription'];
                $this->NagiosNotification->hostNotification($parameters);
            }

            if ($this->params['type'] === 'Service') {
                $service = $this->getServicename($parameters['servicedesc']);
                $parameters['servicedesc'] = $service['servicedesc'];
                $parameters['serviceUuid'] = $service['serviceUuid'];
                $parameters['hostUuid'] = $parameters['hostname'];
                $parameters['hostname'] = $service['hostname'];
                $parameters['hostdescription'] = $service['hostdescription'];
                $parameters['serviceType'] = (int)$service['serviceType'];
                $parameters['hostId'] = $service['hostId'];
                $parameters['serviceId'] = $service['serviceId'];
                $this->NagiosNotification->serviceNotification($parameters);
            }
        }


    }

    public function getOptionParser() {
        $parser = parent::getOptionParser();
        $parser->addOptions([
            'type'              => ['short' => 't', 'help' => __d('oitc_console', 'Type of the notification host or service')],
            'notificationtype'  => ['help' => __d('oitc_console', 'Notification type of monitoring engine')],
            'hostname'          => ['help' => __d('oitc_console', 'Host uuid you want to send a notification')],
            'hostdescription'   => ['help' => __d('oitc_console', 'Host description you want to send a notification')],
            'hoststate'         => ['help' => __d('oitc_console', 'current host state')],
            'hostaddress'       => ['help' => __d('oitc_console', 'host address')],
            'hostoutput'        => ['help' => __d('oitc_console', 'host output')],
            'hostlongoutput'    => ['help' => __d('oitc_console', 'host long output')],
            'hostackauthor'     => ['help' => __d('oitc_console', 'host acknowledgement author')],
            'hostackcomment'    => ['help' => __d('oitc_console', 'host acknowledgement comment')],
            'contactmail'       => ['help' => __d('oitc_console', 'recivers mail address')],
            'contactalias'      => ['help' => __d('oitc_console', 'human name of the contact')],
            'servicedesc'       => ['help' => __d('oitc_console', 'Service uuid you want to notify')],
            'servicestate'      => ['help' => __d('oitc_console', 'service state')],
            'serviceoutput'     => ['help' => __d('oitc_console', 'service output')],
            'servicelongoutput' => ['help' => __d('oitc_console', 'service long output')],
            'serviceackauthor'  => ['help' => __d('oitc_console', 'service acknowledgement author')],
            'serviceackcomment' => ['help' => __d('oitc_console', 'service acknowledgement comment')],
            'format'            => ['help' => __d('oitc_console', 'Email type for notifications [text, html, both]')],
            'no-attachments'    => ['help' => __d('oitc_console', 'Email without attachments')],
        ]);

        return $parser;
    }

    public function fetchEnv() {
        $return = [];

        if (isset($_SERVER['NAGIOS_NOTIFICATIONTYPE'])) {
            $return['notificationtype'] = $_SERVER['NAGIOS_NOTIFICATIONTYPE'];
        } else {
            if (array_key_exists('notificationtype', $this->params)) {
                $return['notificationtype'] = $this->params['notificationtype'];
            }
        }

        if (isset($_SERVER['NAGIOS_HOSTNAME'])) {
            $return['hostname'] = $_SERVER['NAGIOS_HOSTNAME'];
        } else {
            if (array_key_exists('hostname', $this->params)) {
                $return['hostname'] = $this->params['hostname'];
            }
        }

        if (isset($_SERVER['NAGIOS_HOSTALIAS'])) {
            $return['hostdescription'] = $_SERVER['NAGIOS_HOSTALIAS'];
        } else {
            if (array_key_exists('hostdescription', $this->params)) {
                $return['hostdescription'] = $this->params['hostdescription'];
            }
        }

        if (isset($_SERVER['NAGIOS_HOSTSTATE'])) {
            $return['hoststate'] = $_SERVER['NAGIOS_HOSTSTATE'];
        } else {
            if (array_key_exists('hoststate', $this->params)) {
                $return['hoststate'] = $this->params['hoststate'];
            }
        }
        if (isset($_SERVER['NAGIOS_HOSTADDRESS'])) {
            $return['hostaddress'] = $_SERVER['NAGIOS_HOSTADDRESS'];
        } else {
            if (array_key_exists('hostaddress', $this->params)) {
                $return['hostaddress'] = $this->params['hostaddress'];
            }
        }
        if (isset($_SERVER['NAGIOS_HOSTOUTPUT'])) {
            $return['hostoutput'] = $_SERVER['NAGIOS_HOSTOUTPUT'];
        } else {
            if (array_key_exists('hostoutput', $this->params)) {
                $return['hostoutput'] = $this->params['hostoutput'];
            }
        }

        $return['hostlongoutput'] = '';
        if (array_key_exists('hostlongoutput', $this->params)) {
            $return['hostlongoutput'] = $this->params['hostlongoutput'];
        }

        if (isset($_SERVER['NAGIOS_NOTIFICATIONAUTHOR'])) {
            $return['hostackauthor'] = $_SERVER['NAGIOS_NOTIFICATIONAUTHOR'];
        } else {
            if (array_key_exists('hostackauthor', $this->params)) {
                $return['hostackauthor'] = $this->params['hostackauthor'];
            }
        }

        if (isset($_SERVER['NAGIOS_NOTIFICATIONCOMMENT'])) {
            $return['hostackcomment'] = $_SERVER['NAGIOS_NOTIFICATIONCOMMENT'];
        } else {
            if (array_key_exists('hostackcomment', $this->params)) {
                $return['hostackcomment'] = $this->params['hostackcomment'];
            }
        }


        if (isset($_SERVER['NAGIOS_CONTACTEMAIL'])) {
            $return['contactmail'] = $_SERVER['NAGIOS_CONTACTEMAIL'];
        } else {
            if (array_key_exists('contactmail', $this->params)) {
                $return['contactmail'] = $this->params['contactmail'];
            }
        }
        if (isset($_SERVER['NAGIOS_CONTACTALIAS'])) {
            $return['contactalias'] = $_SERVER['NAGIOS_CONTACTALIAS'];
        } else {
            if (array_key_exists('contactalias', $this->params)) {
                $return['contactalias'] = $this->params['contactalias'];
            }
        }

        if (isset($_SERVER['NAGIOS_SERVICEDESC'])) {
            $return['servicedesc'] = $_SERVER['NAGIOS_SERVICEDESC'];
        } else {
            if (array_key_exists('servicedesc', $this->params)) {
                $return['servicedesc'] = $this->params['servicedesc'];
            }
        }

        if (isset($_SERVER['NAGIOS_SERVICESTATE'])) {
            $return['servicestate'] = $_SERVER['NAGIOS_SERVICESTATE'];
        } else {
            if (array_key_exists('servicestate', $this->params)) {
                $return['servicestate'] = $this->params['servicestate'];
            }
        }

        if (isset($_SERVER['NAGIOS_SERVICEOUTPUT'])) {
            $return['serviceoutput'] = $_SERVER['NAGIOS_SERVICEOUTPUT'];
        } else {
            if (array_key_exists('serviceoutput', $this->params)) {
                $return['serviceoutput'] = $this->params['serviceoutput'];
            }
        }

        $return['servicelongoutput'] = '';
        if (array_key_exists('servicelongoutput', $this->params)) {
            $return['servicelongoutput'] = $this->params['servicelongoutput'];
        }

        if (isset($_SERVER['NAGIOS_NOTIFICATIONAUTHOR'])) {
            $return['serviceackauthor'] = $_SERVER['NAGIOS_NOTIFICATIONAUTHOR'];
        } else {
            if (array_key_exists('serviceackauthor', $this->params)) {
                $return['serviceackauthor'] = $this->params['serviceackauthor'];
            }
        }

        if (isset($_SERVER['NAGIOS_NOTIFICATIONCOMMENT'])) {
            $return['serviceackcomment'] = $_SERVER['NAGIOS_NOTIFICATIONCOMMENT'];
        } else {
            if (array_key_exists('serviceoutput', $this->params)) {
                $return['serviceackcomment'] = $this->params['serviceackcomment'];
            }
        }

        return $return;
    }

    public function getHostname($hostUuid) {
        //$host = $this->Host->findByUuid($hostUuid);
        $host = $this->Host->find('first', [
            'recurisve'  => -1,
            'conditions' => [
                'Host.uuid' => $hostUuid,
            ],
            'fields'     => [
                'Host.id', 'Host.name', 'Host.uuid', 'Host.description',
            ],
            'contain'    => [],
        ]);

        return ['hostUuid' => $host['Host']['uuid'], 'hostname' => $host['Host']['name'], 'hostdescription' => $host['Host']['description']];
    }

    public function getServicename($serviceUuid) {
        //$service = $this->Service->findByUuid($serviceUuid);
        $service = $this->Service->find('first', [
            'recurisve'  => -1,
            'conditions' => [
                'Service.uuid' => $serviceUuid,
            ],
            'fields'     => [
                'Service.id',
                'Service.uuid',
                'Service.name',
                'Service.service_type'
            ],
            'contain'    => [
                'Servicetemplate' => [
                    'fields' => [
                        'Servicetemplate.id',
                        'Servicetemplate.name',
                    ],
                ],
                'Host'            => [
                    'fields' => [
                        'Host.id',
                        'Host.uuid',
                        'Host.name',
                        'Host.description',
                    ],
                ],
            ],
        ]);

        //$this->dump($service);
        if ($service['Service']['name'] === null || $service['Service']['name'] === '') {
            $service['Service']['name'] = $service['Servicetemplate']['name'];
        }

        return [
            'serviceUuid'     => $service['Service']['uuid'],
            'servicedesc'     => $service['Service']['name'],
            'hostname'        => $service['Host']['name'],
            'hostdescription' => $service['Host']['description'],
            'serviceType'     => $service['Service']['service_type'],
            'hostId'          => $service['Host']['id'],
            'serviceId'       => $service['Service']['id']
        ];
    }

    public function dump($mixed) {
        if (!is_resource($this->logfile)) {
            $this->logfile = fopen('/tmp/notifications', 'w+');
        }

        fwrite($this->logfile, var_export($mixed, true));
    }
}
