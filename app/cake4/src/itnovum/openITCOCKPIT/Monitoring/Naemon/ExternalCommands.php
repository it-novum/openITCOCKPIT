<?php


namespace App\itnovum\openITCOCKPIT\Monitoring\Naemon;


use App\Model\Entity\Service;
use App\Model\Table\HostgroupsTable;
use App\Model\Table\HostsTable;
use App\Model\Table\ServicesTable;
use Cake\Core\Plugin;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use itnovum\openITCOCKPIT\Core\DbBackend;
use itnovum\openITCOCKPIT\Core\HoststatusFields;
use itnovum\openITCOCKPIT\Core\ServicestatusFields;

class ExternalCommands {

    /**
     * @var string
     */
    private $externalCommandFile;

    /**
     * @var false|resource
     */
    private $NaemonCmd;

    public function __construct(string $externalCommandFile) {
        $this->externalCommandFile = $externalCommandFile;
    }

    /**
     * Create an external command to reschedule a host or the host with all Services
     * ### Options
     * - `uuid`            The UUID of the host you want to reschedule
     * - `type`            The type of the external command ('hostOnly' or 'hostAndServices')
     * - `satellite_id`    The id of the satellite system
     *
     * @param array $options with the options
     * @param int $timestamp timestamp, when Naemon should reschedule the host
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
     * @param array $options with the options
     */
    public function rescheduleHostWithQuery($options = []) {
        /** @var HostsTable $HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

        try {
            $host = $HostsTable->getHostByUuid($options['uuid']);

            $this->rescheduleHost([
                'uuid'         => $options['uuid'],
                'type'         => $options['type'],
                'satellite_id' => $host->get('satellite_id')
            ]);

        } catch (RecordNotFoundException $e) {
            Log::error(sprintf('SudoServer: No host with uuid "%s" found!', $options['uuid']));
        }
    }

    /**
     * Create an external command to reschedule a host group or hostgroup with all Services
     * ### Options
     * - `hostgroupUuid`       The UUID of the host you want to reschedule
     * - `type`                The type of the external command ('hostOnly' or 'hostAndServices')
     *
     * @param array $options with the options
     * @param int $timestamp when Naemon should reschedule the host
     */
    public function rescheduleHostgroup($options, $timestamp = null) {
        /** @var HostgroupsTable $HostgroupsTable */
        $HostgroupsTable = TableRegistry::getTableLocator()->get('Hostgroups');
        $hostgroup = $HostgroupsTable->getHostsByHostgroupUuidForExternalcommandsIncludeingHosttemplateHosts(
            $options['hostgroupUuid']
        );

        if (isset($hostgroup['hosts'])) {
            foreach ($hostgroup['hosts'] as $host) {
                $activeChecksEnabled = $host['active_checks_enabled'];
                if ($activeChecksEnabled === null || $activeChecksEnabled === '') {
                    $activeChecksEnabled = $host['Hosttemplates']['active_checks_enabled'];
                }
                $activeChecksEnabled = (int)$activeChecksEnabled;

                if ($activeChecksEnabled === 1) {
                    $this->rescheduleHost([
                        'uuid'         => $host['uuid'],
                        'type'         => $options['type'],
                        'satellite_id' => $host['satellite_id']
                    ], $timestamp);
                }
            }
        }
    }

    /**
     * Transfer a passive host check result to Naemon
     * ### Options
     * - `uuid`              The UUID of the host you want to submit the checresult
     * - `comment`           The comment for the passive check result (Example: 'test alert')
     * - `state`             The state of the passive checl result (0, 1 or 2)
     * - `forceHardstate`    If the Host should be forced into hard state (for testing notifications) (values: 1, 0,
     * ture or false)
     * - `repetitions`       the number of repetitions as interger value (normaly the number of max_check_attempts to
     * force hard state)
     *
     * @param array $options with the options
     */
    public function passiveTransferHostCheckresult($options) {
        $_options = [
            'comment'        => 'No comment given',
            'state'          => 2,
            'forceHardstate' => 0,
            'repetitions'    => 1
        ];
        $options = Hash::merge($_options, $options);

        $options['forceHardstate'] = (int)$options['forceHardstate'];
        $options['repetitions'] = (int)$options['repetitions'];

        if ($options['forceHardstate'] === 1 && $options['repetitions'] >= 1) {
            for ($i = 0; $i < $options['repetitions']; $i++) {
                $this->_write('PROCESS_HOST_CHECK_RESULT;' . $options['uuid'] . ';' . $options['state'] . ';' . $options['comment']);
            }
        } else {
            $this->_write('PROCESS_HOST_CHECK_RESULT;' . $options['uuid'] . ';' . $options['state'] . ';' . $options['comment']);
        }

    }


    /**
     * Transfer a passive service check result to Naemon
     * ### Options
     * - `hostUuid`            The UUID of the host
     * - `serviceUuid`         The UUID of the service you want to submit the checkresult
     * - `comment`             The comment for the passive check result (Example: 'test alert')
     * - `state`               The state of the passive check result (0, 1, 2 or 3)
     * - `forceHardstate`      If the Host should be forced into hard state (for testing notifications) (values: 1, 0,
     * ture or false)
     * - `repetitions`         the number of repetitions as interger value (normaly the number of max_check_attempts to
     * force hard state)
     *
     * @param array $options with the options
     */
    public function passiveTransferServiceCheckresult($options) {
        $_options = [
            'comment'        => 'No comment given',
            'state'          => 3,
            'forceHardstate' => 0,
            'repetitions'    => 1
        ];
        $options = Hash::merge($_options, $options);

        $options['forceHardstate'] = (int)$options['forceHardstate'];
        $options['repetitions'] = (int)$options['repetitions'];

        if ($options['forceHardstate'] === 1 && $options['repetitions'] >= 1) {
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
     * - `condition`       1 = enable or 0 disable flap detection
     *
     * @param array $options with the options
     */
    public function enableOrDisableHostFlapdetection($options) {
        $_options = ['condition' => 1];
        $options = Hash::merge($_options, $options);

        $options['condition'] = (int)$options['condition'];

        if ($options['condition'] === 1) {
            $this->_write('ENABLE_HOST_FLAP_DETECTION;' . $options['uuid']);
            return;
        }

        $this->_write('DISABLE_HOST_FLAP_DETECTION;' . $options['uuid']);
    }

    /**
     * Enables or disabled the flap detection for the given UUID
     * ### Options
     * - `hostUuid`        The UUID of the host
     * - `serviceUuid`     The UUID of the service you want to enable/disable flap detection
     * - `condition`       1 = enable or 0 disable flap detection
     *
     * @param array $options with the options
     */
    public function enableOrDisableServiceFlapdetection($options) {
        $_options = ['condition' => 1];
        $options = Hash::merge($_options, $options);

        $options['condition'] = (int)$options['condition'];

        if ($options['condition'] === 1) {
            $this->_write('ENABLE_SVC_FLAP_DETECTION;' . $options['hostUuid'] . ';' . $options['serviceUuid']);
            return;
        }

        $this->_write('DISABLE_SVC_FLAP_DETECTION;' . $options['hostUuid'] . ';' . $options['serviceUuid']);
    }

    /**
     * Create an external command to reschedule a service of an host
     * ### Options
     * - `hostUuid`        The UUID of the host
     * - `serviceUuid`     The UUID of the service you want to reschedule
     * - `satellite_id`    The satellite_id
     *
     * @param array $options with the options
     * @param int $timestamp when Naemon should reschedule the host
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
     * @param array $options with the options
     * @param int $timestamp when Naemon should reschedule the host
     */
    public function rescheduleServiceWithQuery($options = [], $timestamp = null) {
        /** @var ServicesTable $ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');

        try {
            $service = $ServicesTable->getServiceByUuidForExternalCommand($options['uuid']);

            $this->rescheduleService([
                'hostUuid'     => $service->get('host')->get('uuid'),
                'serviceUuid'  => $service->get('uuid'),
                'satellite_id' => $service->get('host')->get('satellite_id')
            ], $timestamp);

        } catch (RecordNotFoundException $e) {
            Log::error(sprintf('SudoServer: No service with uuid "%s" found!', $options['uuid']));
        }
    }


    /**
     * Send a custom host notification
     * ### Options
     * - `hostUuid`    The UUID of the host you want to send a notification
     * - `type`        The notification type (0 = default, 1 = broadcast, 2 = forced, 3 = broadcast and forced)
     * - `author`      The author of the message
     * - `comment`     The comment of the message
     *
     * @param array $options with the options
     * @link https://assets.nagios.com/downloads/nagioscore/docs/externalcmds/cmdinfo.php?command_id=134
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
     * - `serviceUuid`     The UUID of the service you want to send a notification
     * - `type`            The notification type (0 = default, 1 = broadcast, 2 = forced, 3 = broadcast and forced
     * - `author`          The author of the message
     * - `comment`         The comment of the message
     *
     * @param array $options with the options
     * @link https://assets.nagios.com/downloads/nagioscore/docs/externalcmds/cmdinfo.php?command_id=134
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
     * - `author`          The author of the ack
     * - `comment`         The comment of the ack
     * - `sticky`          Integer if sticky or not (0 or 2)
     * - `type`            The type of the external command ('hostOnly' or 'hostAndServices')
     *
     * @param array $options with the options
     * @throws \App\Lib\Exceptions\MissingDbBackendException
     */
    public function setHostAck($options) {
        $_options = [
            'type' => 'hostOnly',
        ];

        $options = Hash::merge($_options, $options);

        $this->_write('ACKNOWLEDGE_HOST_PROBLEM;' . $options['hostUuid'] . ';' . $options['sticky'] . ';1;1;' . $options['author'] . ';' . $options['comment']);

        if ($options['type'] == 'hostAndServices') {
            //Set ACK for host + services

            /** @var HostsTable $HostsTable */
            $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
            try {
                $host = $HostsTable->getHostWithServicesByUuid($options['hostUuid']);
            } catch (RecordNotFoundException $e) {
                Log::error(sprintf('SudoServer: No host with uuid "%s" found!', $options['uuid']));
                return;
            }

            $DbBackend = new DbBackend();
            $ServicestatusTable = $DbBackend->getServicestatusTable();
            $ServicestatusFields = new ServicestatusFields($DbBackend);
            $ServicestatusFields->currentState();

            $serviceUuids = [];
            foreach ($host->get('services') as $service) {
                /** @var Service $service */
                $serviceUuids[] = $service->get('uuid');
            }

            if (!empty($serviceUuids)) {
                $servicestatus = $ServicestatusTable->byUuid($serviceUuids, $ServicestatusFields);

                foreach ($serviceUuids as $serviceUuid) {
                    if (isset($servicestatus[$serviceUuid]['Servicestatus']['current_state'])) {
                        if ($servicestatus[$serviceUuid]['Servicestatus']['current_state'] > 0) {
                            $this->setServiceAck([
                                'hostUuid'    => $options['hostUuid'],
                                'serviceUuid' => $serviceUuid,
                                'author'      => $options['author'],
                                'comment'     => $options['comment'],
                                'sticky'      => $options['sticky']
                            ]);
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
     * - `author`          The author of the ack
     * - `comment`         The comment of the ack
     * - `sticky`          Integer if sticky or not (0 or 2)
     * - `type`            The type of the external command ('hostOnly' or 'hostAndServices')
     *
     * @param array $options with the options
     * @throws \App\Lib\Exceptions\MissingDbBackendException
     */
    public function setHostAckWithQuery($options) {
        $_options = [
            'type' => 'hostOnly',
        ];

        $options = Hash::merge($_options, $options);

        $DbBackend = new DbBackend();
        $HoststatusTable = $DbBackend->getHoststatusTable();

        $HoststatusFields = new HoststatusFields($DbBackend);
        $HoststatusFields->currentState();
        $hoststatus = $HoststatusTable->byUuid($options['hostUuid'], $HoststatusFields);

        if (isset($hoststatus['Hoststatus']['current_state'])) {
            if ($hoststatus['Hoststatus']['current_state'] > 0) {
                $this->_write('ACKNOWLEDGE_HOST_PROBLEM;' . $options['hostUuid'] . ';' . $options['sticky'] . ';1;1;' . $options['author'] . ';' . $options['comment']);
            }
        }


        if ($options['type'] == 'hostAndServices') {
            //Set ACK for host + services

            /** @var HostsTable $HostsTable */
            $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
            try {
                $host = $HostsTable->getHostWithServicesByUuid($options['hostUuid']);
            } catch (RecordNotFoundException $e) {
                Log::error(sprintf('SudoServer: No host with uuid "%s" found!', $options['uuid']));
                return;
            }

            $ServicestatusTable = $DbBackend->getServicestatusTable();
            $ServicestatusFields = new ServicestatusFields($DbBackend);
            $ServicestatusFields->currentState();

            $serviceUuids = [];
            foreach ($host->get('services') as $service) {
                /** @var Service $service */
                $serviceUuids[] = $service->get('uuid');
            }

            if (!empty($serviceUuids)) {
                $servicestatus = $ServicestatusTable->byUuid($serviceUuids, $ServicestatusFields);

                foreach ($serviceUuids as $serviceUuid) {
                    if (isset($servicestatus[$serviceUuid]['Servicestatus']['current_state'])) {
                        if ($servicestatus[$serviceUuid]['Servicestatus']['current_state'] > 0) {
                            $this->setServiceAck([
                                'hostUuid'    => $options['hostUuid'],
                                'serviceUuid' => $serviceUuid,
                                'author'      => $options['author'],
                                'comment'     => $options['comment'],
                                'sticky'      => $options['sticky']
                            ]);
                        }
                    }
                }
            }
        }
    }

    /**
     * Set an acknowledgment for the given host group
     * ### Options
     * - `hostgroupUuid`     The UUID of the host
     * - `author`            The author of the ack
     * - `comment`           The comment of the ack
     * - `sticky`            Integer if sticky or not (0 or 2)
     * - `type`              The type of the external command ('hostOnly' or 'hostAndServices')
     *
     * @param array $options with the options
     * @throws \App\Lib\Exceptions\MissingDbBackendException
     */
    public function setHostgroupAck($options) {
        /** @var HostgroupsTable $HostgroupsTable */
        $HostgroupsTable = TableRegistry::getTableLocator()->get('Hostgroups');
        $hostgroup = $HostgroupsTable->getHostsByHostgroupUuidForExternalcommandsIncludeingHosttemplateHosts(
            $options['hostgroupUuid']
        );

        if (isset($hostgroup['hosts'])) {

            $DbBackend = new DbBackend();
            $HoststatusTable = $DbBackend->getHoststatusTable();
            $HoststatusFields = new HoststatusFields($DbBackend);
            $HoststatusFields->currentState();


            $hostUuids = [];
            foreach ($hostgroup['hosts'] as $host) {
                $hostUuids[] = $host['uuid'];
            }
            if (!empty($hostUuids)) {
                $hoststatus = $HoststatusTable->byUuid($hostUuids, $HoststatusFields);
                foreach ($hostUuids as $uuid) {
                    if (isset($hoststatus[$uuid]['Hoststatus']['current_state'])) {
                        if ($hoststatus[$uuid]['Hoststatus']['current_state'] > 0) {
                            $this->setHostAck([
                                'hostUuid' => $host['uuid'],
                                'author'   => $options['author'],
                                'comment'  => $options['comment'],
                                'sticky'   => $options['sticky'],
                                'type'     => $options['type']
                            ]);
                        }
                    }
                }
            }
        }
    }

    /**
     * Set an acknowledgment for the given service
     * ### Options
     * - `hostUuid`        The UUID of the host
     * - `serviceUuid`     The UUID of the service you want to acknowledge
     * - `author`          The author of the ack
     * - `comment`         The comment of the ack
     * - `sticky`          Integer if sticky or not (0 or 2)
     *
     * @param array $options with the options
     */
    public function setServiceAck($options) {
        $this->_write('ACKNOWLEDGE_SVC_PROBLEM;' . $options['hostUuid'] . ';' . $options['serviceUuid'] . ';' . $options['sticky'] . ';1;1;' . $options['author'] . ';' . $options['comment']);
    }

    /**
     * Set an acknowledgment for the given service
     * Query service status from DB and only set ack if current_state > 0
     * ### Options
     * - `hostUuid`        The UUID of the host
     * - `serviceUuid`     The UUID of the service you want to acknowledge
     * - `author`          The author of the ack
     * - `comment`         The comment of the ack
     * - `sticky`          Integer if sticky or not (0 or 2)
     *
     * @param array $options with the options
     * @throws \App\Lib\Exceptions\MissingDbBackendException
     */
    public function setServiceAckWithQuery($options) {
        $DbBackend = new DbBackend();
        $ServicestatusTable = $DbBackend->getServicestatusTable();

        $ServicestatusFields = new ServicestatusFields($DbBackend);
        $ServicestatusFields->currentState();

        $servicestatus = $ServicestatusTable->byUuid($options['serviceUuid'], $ServicestatusFields);

        if (isset($servicestatus['Servicestatus']['current_state'])) {
            if ($servicestatus['Servicestatus']['current_state'] > 0) {
                $this->setServiceAck([
                    'hostUuid'    => $options['hostUuid'],
                    'serviceUuid' => $options['serviceUuid'],
                    'author'      => $options['author'],
                    'comment'     => $options['comment'],
                    'sticky'      => $options['sticky']
                ]);
            }
        }
    }


    /**
     * Set an downtime for the given host
     * ### Options
     * - `hostUuid`       The UUID of the host you want to set a downtime for
     * - `start`          Start time as unix timestamp
     * - `end`            End time as unix timestamp
     * - `duration`       Duration of the downtime (auto calc if empty!)
     * - `author`         The author of the downtime
     * - `comment`        The comment of the downtime
     * - `downtimetype`   The type of the downtime as int (0 => default, 1 => 'Host inc. services, 2 => triggered, 3 =>
     * non-triggered)
     *
     * @param array $options with the options
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
     * - `end`              End time as unix timestamp
     * - `duration`         Duration of the downtime (auto calc if empty!)
     * - `author`           The author of the downtime
     * - `comment`          The comment of the downtime
     * - `downtimetype`     The type of the downtime as int (0 => hosts only, 1 => 'Host inc. services)
     *
     * @param array $options with the options
     * @throws \App\Lib\Exceptions\MissingDbBackendException
     */
    public function setHostgroupDowntime($options) {
        $_options = [
            'duration'     => $options['end'] - $options['start'],
            'downtimetype' => 0,
        ];

        /** @var HostgroupsTable $HostgroupsTable */
        $HostgroupsTable = TableRegistry::getTableLocator()->get('Hostgroups');
        $hostgroup = $HostgroupsTable->getHostsByHostgroupUuidForExternalcommandsIncludeingHosttemplateHosts(
            $options['hostgroupUuid']
        );

        if (isset($hostgroup['hosts'])) {
            foreach ($hostgroup['hosts'] as $host) {
                $this->setHostDowntime([
                    'hostUuid'     => $host['uuid'],
                    'start'        => $options['start'],
                    'end'          => $options['end'],
                    'comment'      => $options['comment'],
                    'author'       => $options['author'],
                    'downtimetype' => $options['downtimetype']
                ]);
            }
        }
    }

    /**
     * Set an downtime for the given service
     * ### Options
     * - `hostUuid`        The UUID of the host
     * - `serviceUuid`     The UUID of the service you want set a downtime for
     * - `start`           Start time as unix timestamp
     * - `end`             End time as unix timestamp
     * - `duration`        Duration of the downtime (auto calc if empty!)
     * - `author`          The author of the downtime
     * - `comment`         The comment of the downtime
     *
     * @param array $options with the options
     */
    public function setServiceDowntime($options) {
        $_options['duration'] = $options['end'] - $options['start'];
        $options = Hash::merge($_options, $options);
        $this->_write('SCHEDULE_SVC_DOWNTIME;' . $options['hostUuid'] . ';' . $options['serviceUuid'] . ';' . $options['start'] . ';' . $options['end'] . ';1;0;' . $options['duration'] . ';' . $options['author'] . ';' . $options['comment'] . '');
    }

    /**
     * @param $options
     */
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
     * @param array $options with the options
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
     * @param array $options with the options
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
     * - `hostgroupUuid`       The UUID of the host
     * - `type`                The type of the external command ('hostOnly' or 'hostAndServices')
     *
     * @param array $options with the options
     */
    public function disableHostgroupNotifications($options = []) {
        /** @var HostgroupsTable $HostgroupsTable */
        $HostgroupsTable = TableRegistry::getTableLocator()->get('Hostgroups');
        $hostgroup = $HostgroupsTable->getHostsByHostgroupUuidForExternalcommandsIncludeingHosttemplateHosts(
            $options['hostgroupUuid']
        );

        if (isset($hostgroup['hosts'])) {
            foreach ($hostgroup['hosts'] as $host) {
                $this->disableHostNotifications([
                    'uuid' => $host['uuid'],
                    'type' => $options['type']
                ]);
            }
        }
    }

    /**
     * Create an external command to enable host or host + services notifications for a hostgroup
     * ### Options
     * - `hostgroupUuid`       The UUID of the host
     * - `type`                The type of the external command ('hostOnly' or 'hostAndServices')
     *
     * @param array $options with the options
     */
    public function enableHostgroupNotifications($options = []) {
        /** @var HostgroupsTable $HostgroupsTable */
        $HostgroupsTable = TableRegistry::getTableLocator()->get('Hostgroups');
        $hostgroup = $HostgroupsTable->getHostsByHostgroupUuidForExternalcommandsIncludeingHosttemplateHosts(
            $options['hostgroupUuid']
        );

        if (isset($hostgroup['hosts'])) {
            foreach ($hostgroup['hosts'] as $host) {
                $this->enableHostNotifications([
                    'uuid' => $host['uuid'],
                    'type' => $options['type']
                ]);
            }
        }
    }

    /**
     * Create an external command to disable service notifications of given service
     * ### Options
     * - `hostUuid`        The UUID of the host
     * - `serviceUuid`     The UUID of the service
     *
     * @param array $options with the options
     */
    public function disableServiceNotifications($options = []) {
        $this->_write('DISABLE_SVC_NOTIFICATIONS;' . $options['hostUuid'] . ';' . $options['serviceUuid'], 0);
    }

    /**
     * Create an external command to enable service notifications of given service
     * ### Options
     * - `hostUuid`        The UUID of the host
     * - `serviceUuid`     The UUID of the service
     *
     * @param array $options with the options
     */
    public function enableServiceNotifications($options = []) {
        $this->_write('ENABLE_SVC_NOTIFICATIONS;' . $options['hostUuid'] . ';' . $options['serviceUuid'], 0);
    }

    /**
     * Send a custom command to the Naemon.cmd
     * This is used by https://xxx.xxx.xxx.xxx/nagios_module/cmd/submit/ for example
     * ### Parameters
     * - `command`        Name of the Naemon external command as string
     * - `parameters`     Parameters for this command as array (for implode(';', $parameters))
     *                    Info: Check Plugins/NagiosModule/CmdController.php function __externalCommands
     *
     * @param array $$payload with the options
     * @deprecated
     */
    public function runCmdCommand($payload) {
        $payload['parameters'] = (array)$payload['parameters'];
        $this->_write($payload['command'] . ';' . implode(';', $payload['parameters']), isset($payload['satelliteId']) ? $payload['satelliteId'] : 0);
    }


    /**
     * Delete a host downtime by given internal_downtime_id
     * ### Parameters
     * - `internal_downtime_id`        The internal id of the monitoring engine
     *
     * @param int $internal_downtime_id with the options
     */
    public function deleteHostDowntime($internal_downtime_id = 0) {
        if ($internal_downtime_id > 0) {
            $this->_write('DEL_HOST_DOWNTIME;' . $internal_downtime_id);
        }
    }

    /**
     * Delete a service downtime by give internal_downtime_id
     * ### Parameters
     * - `internal_downtime_id`        The internal id of the monitoring engine
     *
     * @param int $internal_downtime_id with the options
     */
    public function deleteServiceDowntime($internal_downtime_id = 0) {
        if ($internal_downtime_id > 0) {
            $this->_write('DEL_SVC_DOWNTIME;' . $internal_downtime_id);
        }
    }

    /**
     * Write external command data to naemon.cmd
     *
     * @param string $content external command to write to naemon.cmd
     * @param int $satelliteId
     * @return bool
     */
    private function _write($content = '', $satelliteId = 0) {
        if ($satelliteId > 0) {
            if (Plugin::isLoaded('DistributeModule')) {
                // DistributeModule is installed and loaded...
                // Host or service on SAT system or command for $SAT$_nagios.cmd

                /** @var  \DistributeModule\Model\Table\SatellitesTable $SatellitesTable */
                $SatellitesTable = TableRegistry::getTableLocator()->get('DistributeModule.Satellites');

                $satellite = $SatellitesTable->get($satelliteId);
                if (empty($satellite)) {
                    Log::error(sprintf('SudoServer: No Satellite with id "%s" found!', $satelliteId));
                    return false;
                }
                $file = fopen('/opt/openitc/nagios/var/rw/' . md5($satellite->get('name')) . '_nagios.cmd', 'a+');
                fwrite($file, sprintf('[%s] %s%s', time(), $content, PHP_EOL));
                fclose($file);
                return true;
            }

            // DistributeModule not installed or loaded.
            return false;
        } else {
            // Host or service from master system or command for master naemon.cmd
            if ($this->open()) {
                fwrite($this->NaemonCmd, sprintf('[%s] %s%s', time(), $content, PHP_EOL));
                // If the naemon.cmd is missing (due to Naemon restart or so) the file descripter gets lost.
                // To avoid any errors, we fclose the file naeon.cmd
                $this->close();
                return true;
            }
            Log::error(
                sprintf('SudoServer: Lost external command "%s"', sprintf('[%s] %s', time(), $content))
            );
        }
        return false;
    }


    public function close() {
        if (is_resource($this->NaemonCmd)) {
            fclose($this->NaemonCmd);
        }
    }

    /**
     * @return bool
     */
    public function open() {
        if (!file_exists($this->externalCommandFile)) {
            Log::error(sprintf('SudoServer: File "%s" does not exisits!', $this->externalCommandFile));
            return false;
        }

        if (!is_resource($this->NaemonCmd)) {
            $this->NaemonCmd = fopen($this->externalCommandFile, 'w+');
        }

        return is_resource($this->NaemonCmd);
    }

}
