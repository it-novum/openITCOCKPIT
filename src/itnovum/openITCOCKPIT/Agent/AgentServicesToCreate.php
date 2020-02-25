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


class AgentServicesToCreate {

    private $servicesToCreate = [];
    private $servicesForFrontend = [];

    private $agentJsonOutput = null;
    private $agentchecks_mapping = [];
    private $hostId = null;
    private $allHostServices = [];

    public function __construct($agentJsonOutput, $agentchecks_mapping, $hostId, $allHostServices) {
        $this->agentJsonOutput = $agentJsonOutput;
        $this->agentchecks_mapping = $agentchecks_mapping;
        $this->hostId = $hostId;
        $this->allHostServices = $allHostServices;

        $this->serviceMatchingLogic($this->agentJsonOutput, $this->agentchecks_mapping, $this->hostId, $this->allHostServices);
    }

    public function getServicesToCreate() {
        return $this->servicesToCreate;
    }

    public function getServicesForFrontend() {
        return $this->servicesForFrontend;
    }

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

    private function addServiceToCreate(array $service, $receiverPluginName, $services, $servicecommandargumentvalue = null, $servicecommandargumentvaluePosition = null) {
        if (!empty($service) && !$this->isInExistingServices($service, $services, $servicecommandargumentvalue, $servicecommandargumentvaluePosition)) {
            if (isset($service['agent_wizard_option_description'])) {
                $service['name'] .= ' ' . $service['agent_wizard_option_description'];
            }

            if (!isset($this->servicesToCreate[$receiverPluginName])) {
                $this->servicesToCreate[$receiverPluginName] = [];
            }
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
                                $service = $this->getServiceFromAgentcheckForMapping($agentCheckName, $receiverPluginName, $agentchecks_mapping, $hostId);
                                $service['servicecommandargumentvalues'][0]['value'] = 'id';
                                $service['servicecommandargumentvalues'][1]['value'] = $dockercontainer['id'];
                                $service['agent_wizard_option_description'] = $dockercontainer['id'] . (isset($dockercontainer['name']) ? ' (' . $dockercontainer['name'] . ')' : '');

                                $this->addServiceToCreate($service, $receiverPluginName, $services, $dockercontainer['id'], 1);
                            }
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
                                $service['servicecommandargumentvalues'][2]['value'] = $fanname;
                                $service['agent_wizard_option_description'] = $fanname;

                                $this->addServiceToCreate($service, $receiverPluginName, $services, $fanname, 2);
                            }
                        } else if ($sensorType === 'temperatures') {
                            $receiverPluginName = 'Temperature';
                            foreach ($sensor as $tempsensorname => $tempsensordata) {
                                $service = $this->getServiceFromAgentcheckForMapping($agentCheckName, $receiverPluginName, $agentchecks_mapping, $hostId);
                                $service['servicecommandargumentvalues'][3]['value'] = $tempsensorname;
                                $service['agent_wizard_option_description'] = $tempsensorname;

                                $this->addServiceToCreate($service, $receiverPluginName, $services, $tempsensorname, 3);
                            }
                        } else if ($sensorType === 'battery') {
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
                            if ($process['cmdline'] !== '') {
                                $value = implode(' ', $process['cmdline']);
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

                            $service['servicecommandargumentvalues'][2]['value'] = $value;
                            $service['agent_wizard_option_description'] = $value;

                            $this->addServiceToCreate($service, $receiverPluginName, $services, $value, 2);
                        }
                    }
                    break;
            }
        }
    }
}
