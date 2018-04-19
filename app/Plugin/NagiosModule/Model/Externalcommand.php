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

use itnovum\openITCOCKPIT\Core\DbBackend;
use itnovum\openITCOCKPIT\Core\HoststatusFields;
use itnovum\openITCOCKPIT\Core\ServicestatusFields;

class Externalcommand extends NagiosModuleAppModel {
    public $useTable = false;

    /**
     * @var null|DbBackend
     */
    protected $DbBackend = null;

    /**
     * Check if nagios.cmd exits and fopen() it
     * @throw     NotFoundException if nagios.cmd does not exists
     * @return    void
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0
     */
    private function _initialize() {
        $this->nagiosCmd = null;
        if (!file_exists(Configure::read('NagiosModule.PREFIX') . Configure::read('NagiosModule.NAGIOS_CMD'))) {
            throw new NotFoundException('Error file ' . Configure::read('NagiosModule.PREFIX') . Configure::read('NagiosModule.NAGIOS_CMD') . ' does not exist');
        }
        $this->nagiosCmd = fopen(Configure::read('NagiosModule.PREFIX') . Configure::read('NagiosModule.NAGIOS_CMD'), 'w+');
    }

    /**
     * Create an external command to reschedule a host or the host with all Services
     * ### Options
     * - `uuid`            The UUID of the host you want to reschedule
     * - `type`            The type of the external command ('hostOnly' or 'hostAndServices')
     * - `satellite_id`    The id of the satellite system
     *
     * @param    array $options with the options
     * @param    integer $timestamp timestamp, when nagios should reschedule the host
     *
     * @return    void
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0
     */
    public function rescheduleHost($options = [], $timestamp = null) {
        if ($timestamp === null) {
            $timestamp = time();
        }

        if ($options['type'] == 'hostOnly') {
            $this->_write('SCHEDULE_FORCED_HOST_CHECK;' . $options['uuid'] . ';' . $timestamp, $options['satellite_id']);
        } else {
            $this->_write('SCHEDULE_FORCED_HOST_CHECK;' . $options['uuid'] . ';' . $timestamp, $options['satellite_id']);
            $this->_write('SCHEDULE_FORCED_HOST_SVC_CHECKS;' . $options['uuid'] . ';' . $timestamp, $options['satellite_id']);
        }

    }

    /**
     * Create an external command to reschedule a host or the host with all Services and select the satellite_id out of
     * the database
     * ### Options
     * - `uuid`            The UUID of the host you want to reschedule
     * - `type`            The type of the external command ('hostOnly' or 'hostAndServices')
     *
     * @param    array $options with the options
     * @param    integer $timestamp timestamp, when nagios should reschedule the host
     *
     * @return    void
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0.1
     */
    public function rescheduleHostWithQuery($options = []) {
        $this->Host = ClassRegistry::init('Host');
        $host = $this->Host->find('first', [
            'conditions' => [
                'Host.uuid' => $options['uuid'],
            ],
            'contain'    => [],
            'fields'     => [
                'Host.satellite_id',
            ],
        ]);

        if (isset($host['Host']['satellite_id'])) {
            $this->rescheduleHost(['uuid' => $options['uuid'], 'type' => $options['type'], 'satellite_id' => $host['Host']['satellite_id']]);
        }
    }

    /**
     * Create an external command to reschedule a host group or hostgroup with all Services
     * ### Options
     * - `hostgroupUuid`    The UUID of the host you want to reschedule
     * - `type`                The type of the external command ('hostOnly' or 'hostAndServices')
     *
     * @param    array $options with the options
     * @param    integer $timestamp timestamp, when nagios should reschedule the host
     *
     * @return    void
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0.1
     */
    public function rescheduleHostgroup($options, $timestamp = null) {
        $this->Hostgroup = ClassRegistry::init('Hostgroup');
        $hostgroup = $this->Hostgroup->find('first', [
            'recursive'  => -1,
            'conditions' => [
                'Hostgroup.uuid' => $options['hostgroupUuid'],
            ],
            'contain'    => [
                'Host' => [
                    'fields' => [
                        'Host.uuid',
                        'Host.satellite_id',
                        'Host.active_checks_enabled',
                    ],

                    'Hosttemplate' => [
                        'fields' => [
                            'Hosttemplate.active_checks_enabled',
                        ],
                    ],
                ],
            ],
        ]);
        if (isset($hostgroup['Host']) && !empty($hostgroup['Host'])) {
            foreach ($hostgroup['Host'] as $host) {
                if ($host['active_checks_enabled'] === null || $host['active_checks_enabled'] === '') {
                    if ($host['Hosttemplate']['active_checks_enabled'] == 0) {
                        //Do not reschedule pasive hosts
                        continue;
                    }
                } else {
                    if ($host['active_checks_enabled'] == 0) {
                        //Do not reschedule pasive hosts
                        continue;
                    }
                }

                $this->rescheduleHost(['uuid' => $host['uuid'], 'type' => $options['type'], 'satellite_id' => $host['satellite_id']], $timestamp);
            }
        }
    }

    /**
     * Transfer a passive host check result to nagios
     * ### Options
     * - `uuid`                The UUID of the host you want to submit the checresult
     * - `comment`            The comment for the passive check result (Example: 'test alert')
     * - `state`            The state of the passive checl result (0, 1 or 2)
     * - `forceHardstate`    If the Host should be forced into hard state (for testing notifications) (values: 1, 0,
     * ture or false)
     * - `repetitions`        the number of repetitions as interger value (normaly the number of max_check_attempts to
     * force hard state)
     *
     * @param    array $options with the options
     *
     * @return    void
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0
     */
    public function passiveTransferHostCheckresult($options) {
        $_options = ['comment' => 'No comment given', 'state' => 2, 'forceHardstate' => 0, 'repetitions' => 1];
        $options = Hash::merge($_options, $options);
        if ($options['forceHardstate'] == 1 && $options['repetitions'] >= 1) {
            for ($i = 0; $i < $options['repetitions']; $i++) {
                $this->_write('PROCESS_HOST_CHECK_RESULT;' . $options['uuid'] . ';' . $options['state'] . ';' . $options['comment']);
            }
        } else {
            $this->_write('PROCESS_HOST_CHECK_RESULT;' . $options['uuid'] . ';' . $options['state'] . ';' . $options['comment']);
        }

    }


    /**
     * Transfer a passive service check result to nagios
     * ### Options
     * - `hostUuid`            The UUID of the host
     * - `serviceUuid`        The UUID of the service you want to submit the checkresult
     * - `comment`            The comment for the passive check result (Example: 'test alert')
     * - `state`            The state of the passive check result (0, 1, 2 or 3)
     * - `forceHardstate`    If the Host should be forced into hard state (for testing notifications) (values: 1, 0,
     * ture or false)
     * - `repetitions`        the number of repetitions as interger value (normaly the number of max_check_attempts to
     * force hard state)
     *
     * @param    array $options with the options
     *
     * @return    void
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0
     */
    public function passiveTransferServiceCheckresult($options) {
        $_options = ['comment' => 'No comment given', 'state' => 3, 'forceHardstate' => 0, 'repetitions' => 1];
        $options = Hash::merge($_options, $options);
        if ($options['forceHardstate'] == 1 && $options['repetitions'] >= 1) {
            for ($i = 0; $i < $options['repetitions']; $i++) {
                $this->_write('PROCESS_SERVICE_CHECK_RESULT;' . $options['hostUuid'] . ';' . $options['serviceUuid'] . ';' . $options['state'] . ';' . $options['comment']);
            }
        } else {
            $this->_write('PROCESS_SERVICE_CHECK_RESULT;' . $options['hostUuid'] . ';' . $options['serviceUuid'] . ';' . $options['state'] . ';' . $options['comment']);
        }

    }


    /**
     * Enables or disabled the flap detection for the given host UUID
     * ### Options
     * - `uuid`            The UUID of the host you want to enable/disable flap detection
     * - `condition`    1 = enable or 0 disable flap detection
     *
     * @param    array $options with the options
     *
     * @return    void
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0
     */
    public function enableOrDisableHostFlapdetection($options) {
        $_options = ['condition' => 1];
        $options = Hash::merge($_options, $options);

        if ($options['condition'] == 1) {
            $this->_write('ENABLE_HOST_FLAP_DETECTION;' . $options['uuid']);

            return;
        }

        $this->_write('DISABLE_HOST_FLAP_DETECTION;' . $options['uuid']);
    }

    /**
     * Enables or disabled the flap detection for the given UUID
     * ### Options
     * - `hostUuid`        The UUID of the host
     * - `serviceUuid`    The UUID of the service you want to enable/disable flap detection
     * - `condition`    1 = enable or 0 disable flap detection
     *
     * @param    array $options with the options
     *
     * @return    void
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0
     */
    public function enableOrDisableServiceFlapdetection($options) {
        $_options = ['condition' => 1];
        $options = Hash::merge($_options, $options);

        if ($options['condition'] == 1) {
            $this->_write('ENABLE_SVC_FLAP_DETECTION;' . $options['hostUuid'] . ';' . $options['serviceUuid']);

            return;
        }

        $this->_write('DISABLE_SVC_FLAP_DETECTION;' . $options['hostUuid'] . ';' . $options['serviceUuid']);
    }

    /**
     * Create an external command to reschedule a service of an host
     * ### Options
     * - `hostUuid`        The UUID of the host
     * - `serviceUuid`    The UUID of the service you want to reschedule
     * - `satellite_id`    The satellite_id
     *
     * @param    array $options with the options
     * @param    integer $timestamp timestamp, when nagios should reschedule the host
     *
     * @return    void
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0
     */
    public function rescheduleService($options, $timestamp = null) {
        if ($timestamp === null) {
            $timestamp = time();
        }
        $this->_write('SCHEDULE_FORCED_SVC_CHECK;' . $options['hostUuid'] . ';' . $options['serviceUuid'] . ';' . $timestamp, $options['satellite_id']);
    }

    /**
     * Create an external command to reschedule a service of an host by given service uuid
     * Will query the satellite_id out of the database
     * ### Options
     * - `uuid`        The UUID of the service you want to reschedule
     *
     * @param    array $options with the options
     * @param    integer $timestamp timestamp, when nagios should reschedule the host
     *
     * @return    void
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0.1
     */
    public function rescheduleServiceWithQuery($options = [], $timestamp = null) {
        $this->Service = ClassRegistry::init('Service');
        $service = $this->Service->find('first', [
            'conditions' => [
                'Service.uuid' => $options['uuid'],
            ],
            'contain'    => [
                'Host' => [
                    'fields' => [
                        'Host.id',
                        'Host.uuid',
                        'Host.satellite_id',
                    ],
                ],
            ],
            'fields'     => [
                'Service.id',
            ],
        ]);

        if (isset($service['Host']['satellite_id'])) {
            $this->rescheduleService(['hostUuid' => $service['Host']['uuid'], 'serviceUuid' => $options['uuid'], 'satellite_id' => $service['Host']['satellite_id']], $timestamp);
        }
    }


    /**
     * Send a custom host notification
     * ### Options
     * - `hostUuid`    The UUID of the host you want to send a notification
     * - `type`        The notification type (0 = default, 1 = broadcast, 2 = forced, 3 = broadcast and forced Weblink:
     * http://old.nagios.org/developerinfo/externalcommands/commandinfo.php?command_id=134)
     * - `author`    The author of the message
     * - `comment`    The comment of the message
     *
     * @param    array $options with the options
     *
     * @return    void
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0
     */
    public function sendCustomHostNotification($options) {
        switch ($options['type']) {
            case 1:
                $this->_write('SEND_CUSTOM_HOST_NOTIFICATION;' . $options['hostUuid'] . ';1;' . $options['author'] . ';' . $options['comment']);
                break;

            case 2:
                $this->_write('SEND_CUSTOM_HOST_NOTIFICATION;' . $options['hostUuid'] . ';2;' . $options['author'] . ';' . $options['comment']);
                break;

            case 3:
                $this->_write('SEND_CUSTOM_HOST_NOTIFICATION;' . $options['hostUuid'] . ';3;' . $options['author'] . ';' . $options['comment']);
                break;

            default:
                $this->_write('SEND_CUSTOM_HOST_NOTIFICATION;' . $options['hostUuid'] . ';0;' . $options['author'] . ';' . $options['comment']);
                break;
        }
    }

    /**
     * Send a custom service notification
     * ### Options
     * - `hostUuid`        The UUID of the host
     * - `serviceUuid`    The UUID of the service you want to send a notification
     * - `type`            The notification type (0 = default, 1 = broadcast, 2 = forced, 3 = broadcast and forced
     * Weblink: http://old.nagios.org/developerinfo/externalcommands/commandinfo.php?command_id=134)
     * - `author`        The author of the message
     * - `comment`        The comment of the message
     *
     * @param    array $options with the options
     *
     * @return    void
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0
     */
    public function sendCustomServiceNotification($options) {
        switch ($options['type']) {
            case 1:
                $this->_write('SEND_CUSTOM_SVC_NOTIFICATION;' . $options['hostUuid'] . ';' . $options['serviceUuid'] . ';1;' . $options['author'] . ';' . $options['comment']);
                break;

            case 2:
                $this->_write('SEND_CUSTOM_SVC_NOTIFICATION;' . $options['hostUuid'] . ';' . $options['serviceUuid'] . ';2;' . $options['author'] . ';' . $options['comment']);
                break;

            case 3:
                $this->_write('SEND_CUSTOM_SVC_NOTIFICATION;' . $options['hostUuid'] . ';' . $options['serviceUuid'] . ';3;' . $options['author'] . ';' . $options['comment']);
                break;

            default:
                $this->_write('SEND_CUSTOM_SVC_NOTIFICATION;' . $options['hostUuid'] . ';' . $options['serviceUuid'] . ';0;' . $options['author'] . ';' . $options['comment']);
                break;
        }
    }

    /**
     * Set an acknowledgment for the given host
     * ### Options
     * - `hostUuid`        The UUID of the host
     * - `author`        The author of the ack
     * - `comment`        The comment of the ack
     * - `sticky`        Integer if sticky or not (0 or 2)
     * - `type`            The type of the external command ('hostOnly' or 'hostAndServices')
     *
     * @param    array $options with the options
     *
     * @return    void
     * @author     Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since      3.0
     * @version    3.0.1
     */
    public function setHostAck($options) {
        $_options = [
            'type' => 'hostOnly',
        ];

        $options = Hash::merge($_options, $options);

        $this->_write('ACKNOWLEDGE_HOST_PROBLEM;' . $options['hostUuid'] . ';' . $options['sticky'] . ';1;1;' . $options['author'] . ';' . $options['comment']);

        if ($options['type'] == 'hostAndServices') {
            //Set ACK for host + services
            $this->Host = ClassRegistry::init('Host');
            $this->Servicestatus = ClassRegistry::init(MONITORING_SERVICESTATUS);
            $hostAndServices = $this->Host->find('first', [
                'conditions' => [
                    'Host.id',
                    'Host.uuid' => $options['hostUuid'],
                ],
                'fields'     => [
                    'Host.uuid',
                ],
                'contain'    => [
                    'Service' => [
                        'fields' => [
                            'Service.id',
                            'Service.uuid',
                        ],
                    ],
                ],
            ]);

            if (isset($hostAndServices['Service']) && !empty($hostAndServices['Service'])) {
                $serviceUuids = Hash::extract($hostAndServices['Service'], '{n}.uuid');

                if($this->DbBackend === null) {
                    Configure::load('dbbackend');
                    $this->DbBackend = new DbBackend(Configure::read('dbbackend'));
                }

                $ServicestatusFields = new ServicestatusFields($this->DbBackend);
                $ServicestatusFields->currentState();
                $servicestatus = $this->Servicestatus->byUuid($serviceUuids, $ServicestatusFields);

                foreach ($hostAndServices['Service'] as $service) {
                    if (isset($servicestatus[$service['uuid']]['Servicestatus']['current_state'])) {
                        if ($servicestatus[$service['uuid']]['Servicestatus']['current_state'] > 0) {
                            $this->setServiceAck(['hostUuid' => $options['hostUuid'], 'serviceUuid' => $service['uuid'], 'author' => $options['author'], 'comment' => $options['comment'], 'sticky' => $options['sticky']]);
                        }
                    }
                }
            }
        }
    }

    /**
     * Set an acknowledgment for the given host and services
     * Only set service acks if the host is ok
     * ### Options
     * - `hostUuid`        The UUID of the host
     * - `author`        The author of the ack
     * - `comment`        The comment of the ack
     * - `sticky`        Integer if sticky or not (0 or 2)
     * - `type`            The type of the external command ('hostOnly' or 'hostAndServices')
     *
     * @param    array $options with the options
     *
     * @return    void
     * @author     Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since      3.0
     * @version    3.0.1
     */
    public function setHostAckWithQuery($options) {
        $_options = [
            'type' => 'hostOnly',
        ];

        $options = Hash::merge($_options, $options);

        if($this->DbBackend === null) {
            Configure::load('dbbackend');
            $this->DbBackend = new DbBackend(Configure::read('dbbackend'));
        }

        $this->Hoststatus = ClassRegistry::init(MONITORING_HOSTSTATUS);
        $HoststatusFields = new HoststatusFields($this->DbBackend);
        $HoststatusFields->currentState();
        $hoststatus = $this->Hoststatus->byUuid($options['hostUuid'], $HoststatusFields);

        if (isset($hoststatus['Hoststatus']['current_state'])) {
            if ($hoststatus['Hoststatus']['current_state'] > 0) {
                $this->_write('ACKNOWLEDGE_HOST_PROBLEM;' . $options['hostUuid'] . ';' . $options['sticky'] . ';1;1;' . $options['author'] . ';' . $options['comment']);
            }
        }

        if ($options['type'] == 'hostAndServices') {
            //Set ACK for host + services
            $this->Host = ClassRegistry::init('Host');
            $this->Servicestatus = ClassRegistry::init(MONITORING_SERVICESTATUS);
            $hostAndServices = $this->Host->find('first', [
                'conditions' => [
                    'Host.id',
                    'Host.uuid' => $options['hostUuid'],
                ],
                'fields'     => [
                    'Host.uuid',
                ],
                'contain'    => [
                    'Service' => [
                        'fields' => [
                            'Service.id',
                            'Service.uuid',
                        ],
                    ],
                ],
            ]);

            if (isset($hostAndServices['Service']) && !empty($hostAndServices['Service'])) {
                $serviceUuids = Hash::extract($hostAndServices['Service'], '{n}.uuid');

                if($this->DbBackend === null) {
                    Configure::load('dbbackend');
                    $this->DbBackend = new DbBackend(Configure::read('dbbackend'));
                }

                $ServicestatusFields = new ServicestatusFields($this->DbBackend);
                $ServicestatusFields->currentState();

                $servicestatus = $this->Servicestatus->byUuid($serviceUuids, $ServicestatusFields);

                foreach ($hostAndServices['Service'] as $service) {
                    if (isset($servicestatus[$service['uuid']]['Servicestatus']['current_state'])) {
                        if ($servicestatus[$service['uuid']]['Servicestatus']['current_state'] > 0) {
                            $this->setServiceAck(['hostUuid' => $options['hostUuid'], 'serviceUuid' => $service['uuid'], 'author' => $options['author'], 'comment' => $options['comment'], 'sticky' => $options['sticky']]);
                        }
                    }
                }
            }
        }
    }


    /**
     * Set an acknowledgment for the given host group
     * ### Options
     * - `hostgroupUuid`    The UUID of the host
     * - `author`            The author of the ack
     * - `comment`            The comment of the ack
     * - `sticky`            Integer if sticky or not (0 or 2)
     * - `type`                The type of the external command ('hostOnly' or 'hostAndServices')
     *
     * @param    array $options with the options
     *
     * @return    void
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0.1
     */
    public function setHostgroupAck($options) {
        $this->Hostgroup = ClassRegistry::init('Hostgroup');
        $this->Hoststatus = ClassRegistry::init(MONITORING_HOSTSTATUS);
        $hostgroup = $this->Hostgroup->find('first', [
            'recursive'  => -1,
            'conditions' => [
                'Hostgroup.uuid' => $options['hostgroupUuid'],
            ],
            'contain'    => [
                'Host' => [
                    'fields' => [
                        'Host.uuid',
                        'Host.satellite_id',
                    ],
                ],
            ],
        ]);
        if (isset($hostgroup['Host']) && !empty($hostgroup['Host'])) {
            $hostUuids = Hash::extract($hostgroup, 'Host.{n}.uuid');

            if($this->DbBackend === null) {
                Configure::load('dbbackend');
                $this->DbBackend = new DbBackend(Configure::read('dbbackend'));
            }

            $this->Hoststatus = ClassRegistry::init(MONITORING_HOSTSTATUS);
            $HoststatusFields = new HoststatusFields($this->DbBackend);
            $HoststatusFields->currentState();

            $hoststatus = $this->Hoststatus->byUuid($hostUuids, $HoststatusFields);

            foreach ($hostgroup['Host'] as $host) {
                if (isset($hoststatus[$host['uuid']]['Hoststatus']['current_state'])) {
                    if ($hoststatus[$host['uuid']]['Hoststatus']['current_state'] > 0) {
                        $this->setHostAck(['hostUuid' => $host['uuid'], 'author' => $options['author'], 'comment' => $options['comment'], 'sticky' => $options['sticky'], 'type' => $options['type']]);
                    }
                }
            }
        }
    }

    /**
     * Set an acknowledgment for the given service
     * ### Options
     * - `hostUuid`        The UUID of the host
     * - `serviceUuid`    The UUID of the service you want to acknowledge
     * - `author`        The author of the ack
     * - `comment`        The comment of the ack
     * - `sticky`        Integer if sticky or not (0 or 2)
     *
     * @param    array $options with the options
     *
     * @return    void
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0
     */
    public function setServiceAck($options) {
        $this->_write('ACKNOWLEDGE_SVC_PROBLEM;' . $options['hostUuid'] . ';' . $options['serviceUuid'] . ';' . $options['sticky'] . ';1;1;' . $options['author'] . ';' . $options['comment']);
    }

    /**
     * Set an acknowledgment for the given service
     * Query service status from DB and only set ack if current_state > 0
     * ### Options
     * - `hostUuid`        The UUID of the host
     * - `serviceUuid`    The UUID of the service you want to acknowledge
     * - `author`        The author of the ack
     * - `comment`        The comment of the ack
     * - `sticky`        Integer if sticky or not (0 or 2)
     *
     * @param    array $options with the options
     *
     * @return    void
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0.1
     */
    public function setServiceAckWithQuery($options) {
        $this->Servicestatus = ClassRegistry::init(MONITORING_SERVICESTATUS);

        if($this->DbBackend === null) {
            Configure::load('dbbackend');
            $this->DbBackend = new DbBackend(Configure::read('dbbackend'));
        }

        $ServicestatusFields = new ServicestatusFields($this->DbBackend);
        $ServicestatusFields->currentState();

        $servicestatus = $this->Servicestatus->byUuid($options['serviceUuid'], $ServicestatusFields);
        if (isset($servicestatus['Servicestatus']['current_state'])) {
            if ($servicestatus['Servicestatus']['current_state'] > 0) {
                $this->setServiceAck(['hostUuid' => $options['hostUuid'], 'serviceUuid' => $options['serviceUuid'], 'author' => $options['author'], 'comment' => $options['comment'], 'sticky' => $options['sticky']]);
            }
        }
    }


    /**
     * Set an downtime for the given host
     * ### Options
     * - `hostUuid`        The UUID of the host you want to set a downtime for
     * - `start`        Start time as unix timestamp
     * - `end`            End time as unix timestamp
     * - `duration`        Duration of the downtime (auto calc if empty!)
     * - `author`        The author of the downtime
     * - `comment`        The comment of the downtime
     * - `downtimetype` The type of the downtime as int (0 => default, 1 => 'Host inc. services, 2 => triggered, 3 =>
     * non-triggered)
     *
     * @param    array $options with the options
     *
     * @return    void
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0
     */
    public function setHostDowntime($options) {
        $_options = [
            'duration'     => $options['end'] - $options['start'],
            'downtimetype' => 0,
        ];

        $options = Hash::merge($_options, $options);

        switch ($options['downtimetype']) {
            case 0:
                //Host only
                $this->_write('SCHEDULE_HOST_DOWNTIME;' . $options['hostUuid'] . ';' . $options['start'] . ';' . $options['end'] . ';1;0;' . $options['duration'] . ';' . $options['author'] . ';' . $options['comment']);
                break;

            case 1:
                //Host inc services
                $this->_write('SCHEDULE_HOST_DOWNTIME;' . $options['hostUuid'] . ';' . $options['start'] . ';' . $options['end'] . ';1;0;' . $options['duration'] . ';' . $options['author'] . ';' . $options['comment']);
                $this->_write('SCHEDULE_HOST_SVC_DOWNTIME;' . $options['hostUuid'] . ';' . $options['start'] . ';' . $options['end'] . ';1;0;' . $options['duration'] . ';' . $options['author'] . ';' . $options['comment']);
                break;

            case 2:
                //Host triggered
                $this->_write('SCHEDULE_AND_PROPAGATE_TRIGGERED_HOST_DOWNTIME;' . $options['hostUuid'] . ';' . $options['start'] . ';' . $options['end'] . ';1;0;' . $options['duration'] . ';' . $options['author'] . ';' . $options['comment']);
                break;

            case 3:
                //Host non triggerd
                $this->_write('SCHEDULE_AND_PROPAGATE_HOST_DOWNTIME;' . $options['hostUuid'] . ';' . $options['start'] . ';' . $options['end'] . ';1;0;' . $options['duration'] . ';' . $options['author'] . ';' . $options['comment']);
                break;
        }
    }

    /**
     * Set an downtime for the given hostgroup
     * ### Options
     * - `hostgroupUuid`    The UUID of the hostgroup you want to set a downtime for
     * - `start`            Start time as unix timestamp
     * - `end`                End time as unix timestamp
     * - `duration`            Duration of the downtime (auto calc if empty!)
     * - `author`            The author of the downtime
     * - `comment`            The comment of the downtime
     * - `downtimetype`    The type of the downtime as int (0 => hosts only, 1 => 'Host inc. services)
     *
     * @param    array $options with the options
     *
     * @return    void
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0
     */
    public function setHostgroupDowntime($options) {
        $_options = [
            'duration'     => $options['end'] - $options['start'],
            'downtimetype' => 0,
        ];

        $options = Hash::merge($_options, $options);
        //Nagios workaround -.-
        $this->Hostgroup = ClassRegistry::init('Hostgroup');
        $this->Host = ClassRegistry::init('Host');
        $hostgroup = $this->Hostgroup->find('first', [
            'recursive'  => -1,
            'contain'    => [
                'Host'         => [
                    'fields' => [
                        'Host.id',
                        'Host.uuid',
                    ],
                    'conditions' => [
                        'Host.disabled' => 0
                    ]
                ],
                'Hosttemplate' => [
                    'fields' => [
                        'Hosttemplate.id'
                    ]
                ],
            ],
            'conditions' => [
                'Hostgroup.uuid' => $options['hostgroupUuid']
            ]
        ]);
        $hostIds = [];
        if (!empty($hostgroup['Host'])) {
            $hostIds = Hash::extract($hostgroup['Host'], '{n}.id');
        }
        $hostTemlateIds = Hash::extract($hostgroup, 'Hosttemplate.{n}.id');
        $hostsByHosttemplateIds = $this->Host->find('all', [
            'recursive'  => -1,
            'contain'    => [
                'Hostgroup',
                'Hosttemplate' => [
                    'Hostgroup' => [
                        'conditions' => [
                            'Hostgroup.id' => $hostgroup['Hostgroup']['id']
                        ]
                    ]
                ]
            ],
            'conditions' => [
                'Host.hosttemplate_id' => $hostTemlateIds,
                'NOT'                  => [
                    'Host.id' => $hostIds
                ],
                'Host.disabled' => 0
            ],
            'fields'     => [
                'Host.uuid'
            ]
        ]);

        switch ($options['downtimetype']) {
            case 0:
                /*
                 * NAGIOS ARE U F*&§@#G KIDDING ME?? SCHEDULE_HOSTGROUP_HOST_DOWNTIME AND SCHEDULE_HOSTGROUP_SVC_DOWNTIME BROKEN???? SINCE 2007?!
                 */
                //Host only and may be this will work some day
                //$this->_write('SCHEDULE_HOSTGROUP_HOST_DOWNTIME;'.$options['hostgroupUuid'].';'.$options['start'].';'.$options['end'].';1;0;'.$options['duration'].';'.$options['author'].';'.$options['comment']);
                //Nagios workaround
                foreach ($hostgroup['Host'] as $host) {
                    $this->setHostDowntime([
                        'hostUuid'     => $host['uuid'],
                        'start'        => $options['start'],
                        'end'          => $options['end'],
                        'comment'      => $options['comment'],
                        'author'       => $options['author'],
                        'downtimetype' => 0,
                    ]);
                }
                foreach ($hostsByHosttemplateIds as $hostUuidFromHosttemplate) {
                    $this->setHostDowntime([
                        'hostUuid'     => $hostUuidFromHosttemplate['Host']['uuid'],
                        'start'        => $options['start'],
                        'end'          => $options['end'],
                        'comment'      => $options['comment'],
                        'author'       => $options['author'],
                        'downtimetype' => 0,
                    ]);
                }
                break;

            case 1:
                //Host inc services and may be this will work some day
                //$this->_write('SCHEDULE_HOSTGROUP_HOST_DOWNTIME;'.$options['hostgroupUuid'].';'.$options['start'].';'.$options['end'].';1;0;'.$options['duration'].';'.$options['author'].';'.$options['comment']);
                //$this->_write(' SCHEDULE_HOSTGROUP_SVC_DOWNTIME;'.$options['hostgroupUuid'].';'.$options['start'].';'.$options['end'].';1;0;'.$options['duration'].';'.$options['author'].';'.$options['comment']);
                //Nagios workaround
                foreach ($hostgroup['Host'] as $host) {
                    $this->setHostDowntime([
                        'hostUuid'     => $host['uuid'],
                        'start'        => $options['start'],
                        'end'          => $options['end'],
                        'comment'      => $options['comment'],
                        'author'       => $options['author'],
                        'downtimetype' => 1,
                    ]);
                }
                foreach ($hostsByHosttemplateIds as $hostUuidFromHosttemplate) {
                    $this->setHostDowntime([
                        'hostUuid'     => $hostUuidFromHosttemplate['Host']['uuid'],
                        'start'        => $options['start'],
                        'end'          => $options['end'],
                        'comment'      => $options['comment'],
                        'author'       => $options['author'],
                        'downtimetype' => 1,
                    ]);
                }
                break;

            default:
                foreach ($hostgroup['Host'] as $host) {
                    $this->setHostDowntime([
                        'hostUuid'     => $host['uuid'],
                        'start'        => $options['start'],
                        'end'          => $options['end'],
                        'comment'      => $options['comment'],
                        'author'       => $options['author'],
                        'downtimetype' => $options['downtimetype'],
                    ]);
                }
                foreach ($hostsByHosttemplateIds as $hostUuidFromHosttemplate) {
                    $this->setHostDowntime([
                        'hostUuid'     => $hostUuidFromHosttemplate['Host']['uuid'],
                        'start'        => $options['start'],
                        'end'          => $options['end'],
                        'comment'      => $options['comment'],
                        'author'       => $options['author'],
                        'downtimetype' => $options['downtimetype'],
                    ]);
                }
                break;
        }
    }

    /**
     * Set an downtime for the given service
     * ### Options
     * - `hostUuid`        The UUID of the host
     * - `serviceUuid`    The UUID of the service you want set a downtime for
     * - `start`        Start time as unix timestamp
     * - `end`            End time as unix timestamp
     * - `duration`        Duration of the downtime (auto calc if empty!)
     * - `author`        The author of the downtime
     * - `comment`        The comment of the downtime
     *
     * @param    array $options with the options
     *
     * @return    void
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0
     */
    public function setServiceDowntime($options) {
        $_options['duration'] = $options['end'] - $options['start'];
        $options = Hash::merge($_options, $options);
        $this->_write('SCHEDULE_SVC_DOWNTIME;' . $options['hostUuid'] . ';' . $options['serviceUuid'] . ';' . $options['start'] . ';' . $options['end'] . ';1;0;' . $options['duration'] . ';' . $options['author'] . ';' . $options['comment'] . '');
    }

    public function setContainerDowntime($options) {
        $_options = [
            'duration'     => $options['end'] - $options['start'],
            'downtimetype' => 0,
        ];

        $options = Hash::merge($_options, $options);

        $hostUuids = [];
        if (!empty($options['hostUuids'])) {
            $hostUuids = $options['hostUuids'];
        }

        switch ($options['downtimetype']) {
            case 0:
                //Host only
                foreach ($hostUuids as $hostUuid) {
                    $this->setHostDowntime([
                        'hostUuid'     => $hostUuid,
                        'start'        => $options['start'],
                        'end'          => $options['end'],
                        'comment'      => $options['comment'],
                        'author'       => $options['author'],
                        'downtimetype' => 0,
                    ]);
                }

                break;
            case 1:
                //Host inc services
                foreach ($hostUuids as $hostUuid) {
                    $this->setHostDowntime([
                        'hostUuid'     => $hostUuid,
                        'start'        => $options['start'],
                        'end'          => $options['end'],
                        'comment'      => $options['comment'],
                        'author'       => $options['author'],
                        'downtimetype' => 1,
                    ]);
                }

                break;
            default:
                foreach ($hostUuids as $hostUuid) {
                    $this->setHostDowntime([
                        'hostUuid'     => $hostUuid,
                        'start'        => $options['start'],
                        'end'          => $options['end'],
                        'comment'      => $options['comment'],
                        'author'       => $options['author'],
                        'downtimetype' => $options['downtimetype'],
                    ]);
                }
                break;
        }

    }

    /**
     * Create an external command to disable host or host + services notifications
     * ### Options
     * - `uuid`        The UUID of the host you want to reschedule
     * - `type`        The type of the external command ('hostOnly' or 'hostAndServices')
     *
     * @param    array $options with the options
     * @param    integer $timestamp timestamp, when nagios should reschedule the host
     *
     * @return    void
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0.1
     */
    public function disableHostNotifications($options = []) {
        $this->_write('DISABLE_HOST_NOTIFICATIONS;' . $options['uuid'], 0);
        if ($options['type'] == 'hostAndServices') {
            $this->_write('DISABLE_HOST_SVC_NOTIFICATIONS;' . $options['uuid'], 0);
        }
    }

    /**
     * Create an external command to enable host or host + services notifications
     * ### Options
     * - `uuid`        The UUID of the host
     * - `type`        The type of the external command ('hostOnly' or 'hostAndServices')
     *
     * @param    array $options with the options
     * @param    integer $timestamp timestamp, when nagios should reschedule the host
     *
     * @return    void
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0.1
     */
    public function enableHostNotifications($options = []) {
        $this->_write('ENABLE_HOST_NOTIFICATIONS;' . $options['uuid'], 0);
        if ($options['type'] == 'hostAndServices') {
            $this->_write('ENABLE_HOST_SVC_NOTIFICATIONS;' . $options['uuid'], 0);
        }
    }

    /**
     * Create an external command to disable host or host + services notifications for a hostgroup
     * ### Options
     * - `hostgroupUuid`    The UUID of the host
     * - `type`                The type of the external command ('hostOnly' or 'hostAndServices')
     *
     * @param    array $options with the options
     * @param    integer $timestamp timestamp, when nagios should reschedule the host
     *
     * @return    void
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0.1
     */
    public function disableHostgroupNotifications($options = []) {
        $this->Hostgroup = ClassRegistry::init('Hostgroup');
        $hostgroup = $this->Hostgroup->find('first', [
            'recursive'  => -1,
            'conditions' => [
                'Hostgroup.uuid' => $options['hostgroupUuid'],
            ],
            'contain'    => [
                'Host' => [
                    'fields' => [
                        'Host.uuid',
                    ],
                ],
            ],
        ]);

        if (isset($hostgroup['Host']) && !empty($hostgroup['Host'])) {
            foreach ($hostgroup['Host'] as $host) {
                $this->disableHostNotifications(['uuid' => $host['uuid'], 'type' => $options['type']]);
            }
        }
    }

    /**
     * Create an external command to enable host or host + services notifications for a hostgroup
     * ### Options
     * - `hostgroupUuid`    The UUID of the host
     * - `type`                The type of the external command ('hostOnly' or 'hostAndServices')
     *
     * @param    array $options with the options
     * @param    integer $timestamp timestamp, when nagios should reschedule the host
     *
     * @return    void
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0.1
     */
    public function enableHostgroupNotifications($options = []) {
        $this->Hostgroup = ClassRegistry::init('Hostgroup');
        $hostgroup = $this->Hostgroup->find('first', [
            'recursive'  => -1,
            'conditions' => [
                'Hostgroup.uuid' => $options['hostgroupUuid'],
            ],
            'contain'    => [
                'Host' => [
                    'fields' => [
                        'Host.uuid',
                    ],
                ],
            ],
        ]);

        if (isset($hostgroup['Host']) && !empty($hostgroup['Host'])) {
            foreach ($hostgroup['Host'] as $host) {
                $this->enableHostNotifications(['uuid' => $host['uuid'], 'type' => $options['type']]);
            }
        }
    }

    /**
     * Create an external command to disable service notifications of given service
     * ### Options
     * - `hostUuid`        The UUID of the host
     * - `serviceUuid`    The UUID of the service
     *
     * @param    array $options with the options
     * @param    integer $timestamp timestamp, when nagios should reschedule the host
     *
     * @return    void
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0.1
     */
    public function disableServiceNotifications($options = []) {
        $this->_write('DISABLE_SVC_NOTIFICATIONS;' . $options['hostUuid'] . ';' . $options['serviceUuid'], 0);
    }

    /**
     * Create an external command to enable service notifications of given service
     * ### Options
     * - `hostUuid`        The UUID of the host
     * - `serviceUuid`    The UUID of the service
     *
     * @param    array $options with the options
     * @param    integer $timestamp timestamp, when nagios should reschedule the host
     *
     * @return    void
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0.1
     */
    public function enableServiceNotifications($options = []) {
        $this->_write('ENABLE_SVC_NOTIFICATIONS;' . $options['hostUuid'] . ';' . $options['serviceUuid'], 0);
    }

    /**
     * Return the prefix for each external command that is required by nagios
     * @return    string with external command prefix
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0
     */
    private function _prefix() {
        return '[' . time() . '] ';
    }

    /**
     * Send a custom command to the nagios.cmd
     * This is used by https://xxx.xxx.xxx.xxx/nagios_module/cmd/submit/ for example
     * ### Parameters
     * - `command`        Name of the nagios external command as string
     * - `parameters`    Parameters for this command as array (for implode(';', $parameters))
     * Info: Check Plugins/NagiosModule/CmdController.php function __externalCommands
     *
     * @param    array $$payload with the options
     *
     * @return    void
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0
     */
    public function runCmdCommand($payload) {
        $payload['parameters'] = (array)$payload['parameters'];
        $this->_write($payload['command'] . ';' . implode(';', $payload['parameters']), isset($payload['satelliteId']) ? $payload['satelliteId'] : 0);
    }


    /**
     * Delete a host downtime by given internal_downtime_id
     * ### Parameters
     * - `internal_downtime_id`        The internal id of the monitoring engine
     * - `downtimehistory_id`        The downtimehistory_id of table nagios_downtimehistory //May be not needed or
     * coming soon ;)
     *
     * @param    int $internal_downtime_id with the options
     * @param    int $downtimehistory_id of nagios_downtimehistory for force delete //May be not needed or coming
     *                                     soon ;)
     *
     * @return    void
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0.1
     */
    public function deleteHostDowntime($internal_downtime_id = 0, $downtimehistory_id = 0) {
        if ($internal_downtime_id > 0) {
            $this->_write('DEL_HOST_DOWNTIME;' . $internal_downtime_id);
        }
    }

    /**
     * Delete a service downtime by give internal_downtime_id
     * ### Parameters
     * - `internal_downtime_id`        The internal id of the monitoring engine
     * - `downtimehistory_id`        The downtimehistory_id of table nagios_downtimehistory //May be not needed or
     * coming soon ;)
     *
     * @param    int $internal_downtime_id with the options
     * @param    int $downtimehistory_id of nagios_downtimehistory for force  //May be not needed or coming soon ;)
     *
     * @return    void
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0.1
     */
    public function deleteServiceDowntime($internal_downtime_id = 0, $downtimehistory_id = 0) {
        if ($internal_downtime_id > 0) {
            $this->_write('DEL_SVC_DOWNTIME;' . $internal_downtime_id);
        }
    }


    /**
     * Test if the nagios.cmd exists
     * @return    void
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0
     */
    public function test() {
        try {
            $this->_initialize();
        } catch (Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }

    /**
     * Write your data into nagios.cmd
     *
     * @param    $content the content you want to write to nagios.cmd
     *
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0
     */
    private function _write($content = '', $satellite_id = 0) {
        if ($satellite_id > 0) {
            //Loading distributed Monitoring support, if plugin is loaded/installed
            $modulePlugins = array_filter(CakePlugin::loaded(), function ($value) {
                return strpos($value, 'Module') !== false;
            });

            if (in_array('DistributeModule', $modulePlugins)) {
                //DistributeModule is loaded and installed...
                //Host or service on SAT system or command for $SAT$_nagios.cmd
                $Satellite = ClassRegistry::init('DistributeModule.Satellite');

                //Host or service on SAT system or command for $SAT$_nagios.cmd
                $result = $Satellite->find('first', [
                    'recursive'  => -1,
                    'conditions' => [
                        'Satellite.id' => $satellite_id
                    ],
                    'fields'     => [
                        'Satellite.id',
                        'Satellite.name'
                    ]
                ]);
                if (isset($result['Satellite']['name'])) {
                    $file = fopen('/opt/openitc/nagios/var/rw/' . md5($result['Satellite']['name']) . '_nagios.cmd', 'a+');
                    fwrite($file, $this->_prefix() . $content . PHP_EOL);
                    fclose($file);
                }
                unset($result);

            } else {
                return false;
            }

        } else {
            //Host or service from master system or command for master nagios.cmd
            if ($this->_is_resource()) {
                fwrite($this->nagiosCmd, $this->_prefix() . $content . PHP_EOL);
                // If the nagios cmd is missing your ressource crashes. So if we fclose the file
                // we dont need to restart the sudo_server if nagios.cmd was missing some times
                $this->close();
            }
        }
    }

    /**
     * Close the nagios.cmd file
     * @return    void
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0
     */
    public function close() {
        if ($this->_is_resource()) {
            fclose($this->nagiosCmd);
        }
    }

    /**
     * Checks if $this->nagiosCmd is a resource.
     * If not it trys to create the resource
     *
     * @param    $recursive if the function was called recusive
     *
     * @return    bool
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0
     */
    private function _is_resource($recursive = false) {
        if (is_resource($this->nagiosCmd)) {
            return true;
        }

        $this->_initialize();
        if (!$recursive) {
            return $this->_is_resource(true);
        }

        return false;

    }

}