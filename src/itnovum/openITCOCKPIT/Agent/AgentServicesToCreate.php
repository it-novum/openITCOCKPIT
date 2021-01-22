<?php
// Copyright (C) <2018>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//    This program is free software: you can redistribute it and/or modify
//    it under the terms of the GNU General Public License as published by
//    the Free Software Foundation, version 3 of the License.
//
//    This program is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU General Public License for more details.
//
//    You should have received a copy of the GNU General Public License
//    along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

// 2.
//    If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//    under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//    License agreement and license key will be shipped with the order
//    confirmation.

namespace itnovum\openITCOCKPIT\Agent;

/**
 * Class AgentServicesToCreate
 * @package itnovum\openITCOCKPIT\Agent
 * @deprecated
 */
class AgentServicesToCreate {

    /**
     * @var array
     */
    private $servicesToCreate = [];

    /**
     * @var array - Services which are displayed in the agent frontend dropdown boxes where you can choose from
     */
    private $servicesForFrontend = [];

    /**
     * @var null
     */
    private $agentJsonOutput = null;

    /**
     * @var array
     */
    private $agentchecks_mapping = [];

    /**
     * @var null
     */
    private $hostId = null;

    /**
     * @var array
     */
    private $allHostServices = [];

    /**
     * AgentServicesToCreate constructor.
     * @param $agentJsonOutput - json which has been loaded from the agent on the remote host
     * @param $agentchecks_mapping - these are the checks you will find in agentchecks/index
     * @param $hostId - current host id where the services should be rolled out on
     * @param $allHostServices - services which are already monitored on the host
     */
    public function __construct($agentJsonOutput, $agentchecks_mapping, $hostId, $allHostServices) {
        $this->agentJsonOutput = $agentJsonOutput;
        $this->agentchecks_mapping = $agentchecks_mapping;
        $this->hostId = $hostId;
        $this->allHostServices = $allHostServices;

        $this->serviceMatchingLogic($this->agentJsonOutput, $this->agentchecks_mapping, $this->hostId, $this->allHostServices);
    }

    /**
     * @return array
     */
    public function getServicesToCreate() {
        return $this->servicesToCreate;
    }

    /**
     * @return array
     */
    public function getServicesForFrontend() {
        return $this->servicesForFrontend;
    }

    /**
     * @param $serviceToCompare
     * @param $services
     * @param null $servicecommandargumentvalue
     * @param null $servicecommandargumentvaluePosition
     * @return bool
     */
    private function isInExistingServices($serviceToCompare, $services, $servicecommandargumentvalue = null, $servicecommandargumentvaluePosition = null) {
        foreach ($services as $index => $service) {
            //Service with same servicetemplate already monitored
            if ($serviceToCompare['servicetemplate_id'] === $service['servicetemplate_id']) {
                //check if a servicecommandargumentvalue identifier should be checked
                if (!empty($service['servicecommandargumentvalues']) && $servicecommandargumentvalue !== null && $servicecommandargumentvaluePosition !== null) {

                    //if identifier in servicecommandargumentvalues does not match, continue to check next service
                    //if it does match, do nothing to return true by default
                    if (isset($service['servicecommandargumentvalues'][$servicecommandargumentvaluePosition]) &&
                        $service['servicecommandargumentvalues'][$servicecommandargumentvaluePosition]['value'] !== $servicecommandargumentvalue) {
                        continue;
                    }
                }
                return true;
            }
        }
        return false;
    }

    /**
     * @param array $service - the service to create
     * @param $receiverPluginName - eg. WindowsService
     * @param $services - already monitored services on the host
     * @param null $servicecommandargumentvalue
     * @param null $servicecommandargumentvaluePosition
     */
    private function addServiceToCreate(array $service, $receiverPluginName, $services, $servicecommandargumentvalue = null, $servicecommandargumentvaluePosition = null) {
        $service['name'] = substr($service['name'], 0, 1450);   //database field length: varchar(1500)
        if (isset($service['agent_wizard_option_description'])) {
            $service['agent_wizard_option_description'] = substr($service['agent_wizard_option_description'], 0, 1450);
        }
        foreach ($service['servicecommandargumentvalues'] as $key => $argumentvalue) {
            $service['servicecommandargumentvalues'][$key]['value'] = substr($argumentvalue['value'], 0, 1000);   //database field length: varchar(1000)
        }

        if (!empty($service) && !$this->isInExistingServices($service, $services, $servicecommandargumentvalue, $servicecommandargumentvaluePosition)) {
            if (isset($service['agent_wizard_option_description'])) {
                $service['name'] .= ' ' . $service['agent_wizard_option_description'];
            }

            if (!isset($this->servicesToCreate[$receiverPluginName])) {
                $this->servicesToCreate[$receiverPluginName] = [];
            }
            //add the service to servicesToCreate
            $this->servicesToCreate[$receiverPluginName][] = $service;
            if (!isset($this->servicesForFrontend[$receiverPluginName])) {
                $this->servicesForFrontend[$receiverPluginName] = [];
            }

            $frontendService = [
                'name'               => $service['name'],
                'servicetemplate_id' => $service['servicetemplate_id'],
            ];
            if (isset($service['agent_wizard_option_description'])) {
                $frontendService['agent_wizard_option_description'] = $service['agent_wizard_option_description'];
            }
            $this->servicesForFrontend[$receiverPluginName][] = $frontendService;
        }
    }

    /**
     * Sets the Host id for the new Service and return the whole service
     *
     * @param $name
     * @param $receiverPluginName
     * @param $agentchecks_mapping
     * @param $hostId
     * @return array|mixed
     */
    private function getServiceFromAgentcheckForMapping($name, $receiverPluginName, $agentchecks_mapping, $hostId) {
        foreach ($agentchecks_mapping as $agentcheck) {
            if ($agentcheck['name'] === $name) {

                if ($receiverPluginName !== null && $agentcheck['plugin_name'] !== $receiverPluginName) {
                    continue;
                }
                $agentcheck['service']['host_id'] = $hostId;
                return $agentcheck['service'];
            }
        }
        return [];
    }

    /**
     * @param $agentJsonOutput - json which has been loaded from the agent on the remote host
     * @param $agentchecks_mapping - these are the checks you find in agentchecks/index
     * @param $hostId - current host id where the services should be rolled out on
     * @param $services - services which are already monitored on the host
     */
    private function serviceMatchingLogic($agentJsonOutput, $agentchecks_mapping, $hostId, $services) {
        foreach ($agentJsonOutput as $agentCheckName => $objects) {
            $receiverPluginNames = [];
            foreach ($agentchecks_mapping as $agentcheck) {
                if ($agentcheck['name'] === $agentCheckName) {
                    $receiverPluginNames[] = $agentcheck['plugin_name'];
                }
            }

            switch ($agentCheckName) {
                case 'agent':
                case 'cpu_percentage':
                case 'system_load':
                case 'memory':
                case 'swap':
                    foreach ($receiverPluginNames as $receiverPluginName) {
                        $service = $this->getServiceFromAgentcheckForMapping($agentCheckName, $receiverPluginName, $agentchecks_mapping, $hostId);
                        $this->addServiceToCreate($service, $receiverPluginName, $services);
                    }
                    break;
                case 'dockerstats':
                    if (isset($objects['result'])) {
                        foreach ($objects['result'] as $dockercontainer) {
                            foreach ($receiverPluginNames as $receiverPluginName) {
                                //if is running check or container is really running for any other check
                                $dockerDefaultIdentifier = 'name';  //'id' / 'name' (see servicetemplate or command for description)
                                if (isset($dockercontainer[$dockerDefaultIdentifier]) && ($receiverPluginName === 'DockerContainerRunning' || (isset($dockercontainer['pids']) || isset($dockercontainer['cpu_percent'])))) {
                                    $service = $this->getServiceFromAgentcheckForMapping($agentCheckName, $receiverPluginName, $agentchecks_mapping, $hostId);
                                    $service['servicecommandargumentvalues'][0]['value'] = $dockerDefaultIdentifier;
                                    $service['servicecommandargumentvalues'][1]['value'] = $dockercontainer[$dockerDefaultIdentifier];
                                    $service['agent_wizard_option_description'] = $dockercontainer['id'] . (isset($dockercontainer['name']) ? ' (' . $dockercontainer['name'] . ')' : '');
                                    if ($dockerDefaultIdentifier == 'name' && isset($dockercontainer['name'])) {
                                        $service['agent_wizard_option_description'] = $dockercontainer['name'] . ' (' . $dockercontainer['id'] . ')';
                                    }

                                    $this->addServiceToCreate($service, $receiverPluginName, $services, $dockercontainer[$dockerDefaultIdentifier], 1);
                                }
                            }
                        }
                    }
                    break;
                case 'alfrescostats':
                    foreach ($objects as $alfrescocheck) {
                        foreach ($receiverPluginNames as $receiverPluginName) {
                            $service = $this->getServiceFromAgentcheckForMapping($agentCheckName, $receiverPluginName, $agentchecks_mapping, $hostId);
                            $service['servicecommandargumentvalues'][2]['value'] = $alfrescocheck['name'];
                            $service['agent_wizard_option_description'] = $alfrescocheck['name'];

                            $this->addServiceToCreate($service, $receiverPluginName, $services, $alfrescocheck['name'], 2);
                        }
                    }
                    break;
                case 'disk_io':
                    foreach ($objects as $diskname => $disk) {
                        if ($diskname === 'timestamp') {
                            continue;
                        }
                        foreach ($receiverPluginNames as $receiverPluginName) {
                            $service = $this->getServiceFromAgentcheckForMapping($agentCheckName, $receiverPluginName, $agentchecks_mapping, $hostId);
                            $service['servicecommandargumentvalues'][2]['value'] = $diskname;
                            $service['agent_wizard_option_description'] = $diskname;

                            $this->addServiceToCreate($service, $receiverPluginName, $services, $diskname, 2);
                        }
                    }
                    break;
                case 'disks':
                    foreach ($objects as $disk) {
                        foreach ($receiverPluginNames as $receiverPluginName) {
                            $service = $this->getServiceFromAgentcheckForMapping($agentCheckName, $receiverPluginName, $agentchecks_mapping, $hostId);
                            $service['servicecommandargumentvalues'][2]['value'] = $disk['disk']['mountpoint'];
                            $service['agent_wizard_option_description'] = $disk['disk']['mountpoint'];

                            $this->addServiceToCreate($service, $receiverPluginName, $services, $disk['disk']['mountpoint'], 2);
                        }
                    }
                    break;
                case 'sensors':
                    foreach ($objects as $sensorType => $sensor) {
                        if ($sensorType === 'fans') {
                            $receiverPluginName = 'Fan';
                            foreach ($sensor as $fanname => $fandata) {
                                $service = $this->getServiceFromAgentcheckForMapping($agentCheckName, $receiverPluginName, $agentchecks_mapping, $hostId);
                                $service['servicecommandargumentvalues'][3]['value'] = $fanname;
                                $service['agent_wizard_option_description'] = $fanname;

                                $this->addServiceToCreate($service, $receiverPluginName, $services, $fanname, 3);
                            }
                        } else if ($sensorType === 'temperatures') {
                            $receiverPluginName = 'Temperature';
                            foreach ($sensor as $tempsensorname => $tempsensordata) {
                                $service = $this->getServiceFromAgentcheckForMapping($agentCheckName, $receiverPluginName, $agentchecks_mapping, $hostId);
                                $service['servicecommandargumentvalues'][3]['value'] = $tempsensorname;
                                $service['agent_wizard_option_description'] = $tempsensorname;

                                $this->addServiceToCreate($service, $receiverPluginName, $services, $tempsensorname, 3);
                            }
                        } else if ($sensorType === 'battery' && !empty($sensor)) {
                            $receiverPluginName = 'Battery';
                            $service = $this->getServiceFromAgentcheckForMapping($agentCheckName, $receiverPluginName, $agentchecks_mapping, $hostId);

                            $this->addServiceToCreate($service, $receiverPluginName, $services);
                        }
                    }
                    break;
                case 'net_io':
                    foreach ($objects as $devicename => $device) {
                        if ($devicename === 'timestamp') {
                            continue;
                        }
                        foreach ($receiverPluginNames as $receiverPluginName) {
                            $service = $this->getServiceFromAgentcheckForMapping($agentCheckName, $receiverPluginName, $agentchecks_mapping, $hostId);
                            $service['servicecommandargumentvalues'][6]['value'] = $devicename;
                            $service['agent_wizard_option_description'] = $devicename;

                            $this->addServiceToCreate($service, $receiverPluginName, $services, $devicename, 6);
                        }
                    }
                    break;
                case 'net_stats':
                    foreach ($objects as $devicename => $device) {
                        foreach ($receiverPluginNames as $receiverPluginName) {
                            $service = $this->getServiceFromAgentcheckForMapping($agentCheckName, $receiverPluginName, $agentchecks_mapping, $hostId);
                            $service['servicecommandargumentvalues'][1]['value'] = $devicename;
                            $service['agent_wizard_option_description'] = $devicename;

                            $this->addServiceToCreate($service, $receiverPluginName, $services, $devicename, 1);
                        }
                    }
                    break;
                case 'processes':
                    foreach ($objects as $key => $process) {
                        foreach ($receiverPluginNames as $receiverPluginName) {
                            $service = $this->getServiceFromAgentcheckForMapping($agentCheckName, $receiverPluginName, $agentchecks_mapping, $hostId);
                            $value = $process['name'];
                            if ($process['exec'] !== '') {
                                $value = $process['exec'];
                            }
                            if (is_array($process['cmdline']) && !empty($process['cmdline'])) {
                                $value = implode(' ', $process['cmdline']);
                            } else if($process['cmdline'] !== '') {
                                $value = $process['cmdline'];
                            }
                            $service['servicecommandargumentvalues'][6]['value'] = $value;
                            $service['agent_wizard_option_description'] = $value;

                            $this->addServiceToCreate($service, $receiverPluginName, $services, $value, 6);
                        }
                    }
                    break;
                case 'qemustats':
                    if (isset($objects['result'])) {
                        foreach ($objects['result'] as $qemuVM) {
                            foreach ($receiverPluginNames as $receiverPluginName) {
                                $service = $this->getServiceFromAgentcheckForMapping($agentCheckName, $receiverPluginName, $agentchecks_mapping, $hostId);

                                if (isset($qemuVM['name'])) {
                                    $type = 'name';
                                    $value = $qemuVM['name'];
                                }
                                if (isset($qemuVM['id'])) {
                                    $type = 'id';
                                    $value = $qemuVM['id'];
                                }
                                if (isset($qemuVM['uuid'])) {
                                    $type = 'uuid';
                                    $value = $qemuVM['uuid'];
                                }

                                if (isset($type) && isset($value)) {
                                    $service['servicecommandargumentvalues'][0]['value'] = $type;
                                    $service['servicecommandargumentvalues'][1]['value'] = $value;
                                    $service['agent_wizard_option_description'] = $value . (isset($qemuVM['name']) ? ' (' . $qemuVM['name'] . ')' : '');

                                    $this->addServiceToCreate($service, $receiverPluginName, $services, $value, 1);
                                }
                            }
                        }
                    }
                    break;
                case 'customchecks':
                    foreach ($objects as $checkname => $check) {
                        foreach ($receiverPluginNames as $receiverPluginName) {
                            $service = $this->getServiceFromAgentcheckForMapping($agentCheckName, $receiverPluginName, $agentchecks_mapping, $hostId);
                            $service['servicecommandargumentvalues'][0]['value'] = $checkname;
                            $service['agent_wizard_option_description'] = $checkname;

                            $this->addServiceToCreate($service, $receiverPluginName, $services, $checkname, 0);
                        }
                    }
                    break;
                case 'windows_services':
                    foreach ($objects as $key => $windows_service) {
                        foreach ($receiverPluginNames as $receiverPluginName) {

                            $service = $this->getServiceFromAgentcheckForMapping($agentCheckName, $receiverPluginName, $agentchecks_mapping, $hostId);

                            $value = $windows_service['name'];
                            if (isset($windows_service['display_name'])) {
                                $value = $windows_service['display_name'];
                            }
                            if (isset($windows_service['binpath'])) {
                                $value = $windows_service['binpath'];
                            }

                            $serviceName = $windows_service['name'];

                            if (isset($windows_service['binpath'])) {
                                $serviceName = $windows_service['binpath'];
                            }
                            if (isset($windows_service['display_name'])) {
                                $serviceName = $windows_service['display_name'];
                            }

                            $service['servicecommandargumentvalues'][2]['value'] = $value;
                            $service['agent_wizard_option_description'] = $serviceName;
                            $this->addServiceToCreate($service, $receiverPluginName, $services, $value, 2);
                        }
                    }

                    break;
                case 'systemd_services':
                    if (isset($objects['result'])) {
                        foreach ($objects['result'] as $systemd_service) {
                            foreach ($receiverPluginNames as $receiverPluginName) {
                                $service = $this->getServiceFromAgentcheckForMapping($agentCheckName, $receiverPluginName, $agentchecks_mapping, $hostId);

                                $service['servicecommandargumentvalues'][0]['value'] = $systemd_service['unit'];
                                $service['agent_wizard_option_description'] = $systemd_service['unit'];

                                $this->addServiceToCreate($service, $receiverPluginName, $services, $systemd_service['unit'], 0);
                            }
                        }
                    }
                    break;
                case 'windows_eventlog':
                    foreach ($objects as $logType => $logTypeLogs) {
                        foreach ($logTypeLogs as $key => $eventLog) {
                            foreach ($receiverPluginNames as $receiverPluginName) {
                                $service = $this->getServiceFromAgentcheckForMapping($agentCheckName, $receiverPluginName, $agentchecks_mapping, $hostId);

                                $value = $eventLog['source_name'];

                                $service['servicecommandargumentvalues'][0]['value'] = $logType;    //$logType is not part of the oitc service identifier (in ...[3]['value'])!
                                $service['servicecommandargumentvalues'][3]['value'] = $value;
                                $service['agent_wizard_option_description'] = sprintf('%s - (LogType: %s)', $value, $logType);

                                $this->addServiceToCreate($service, $receiverPluginName, $services, $value, 3);
                            }
                        }
                    }
                    break;
            }
        }
    }
}
