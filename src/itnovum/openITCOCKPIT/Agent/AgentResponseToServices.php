<?php


namespace itnovum\openITCOCKPIT\Agent;


use App\Model\Table\AgentchecksTable;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;

class AgentResponseToServices {

    /**
     * @var int
     */
    private $hostId;

    /**
     * @var array
     */
    private $agentResponse;

    /**
     * AgentResponseToServices constructor.
     * @param int $hostId
     * @param array $agentResponse
     */
    public function __construct($hostId, $agentResponse = []) {
        $this->hostId = $hostId;
        $this->agentResponse = $agentResponse;
    }

    /**
     * @todo implement: launchd, libvirt, docker, alfresco, windows_eventlog
     */
    public function getAllServices() {
        /** @var AgentchecksTable $AgentchecksTable */
        $AgentchecksTable = TableRegistry::getTableLocator()->get('Agentchecks');
        $agentchecks = $AgentchecksTable->getAgentchecksForMapping();

        $services = [];

        foreach ($this->agentResponse as $jsonKey => $items) {
            if (!isset($agentchecks[$jsonKey]) && $jsonKey !== 'cpu') {
                Log::info(sprintf('No Agentcheck defined for json key "%s"', $jsonKey));
                continue;
            }
            if ($jsonKey === 'cpu') {
                $agentcheck = $agentchecks['cpu_percentage'];
            } else {
                $agentcheck = $agentchecks[$jsonKey];
            }


            switch ($jsonKey) {
                case 'memory':
                    $services[] = $this->getServiceStruct(
                        $agentcheck['servicetemplate_id'],
                        __('Memory usage percentage'),
                        $agentcheck['servicetemplate']['servicetemplatecommandargumentvalues']
                    );
                    break;

                case 'swap':
                    $services[] = $this->getServiceStruct(
                        $agentcheck['servicetemplate_id'],
                        __('Swap usage percentage'),
                        $agentcheck['servicetemplate']['servicetemplatecommandargumentvalues']
                    );
                    break;
            }

            if (is_array($items)) {
                foreach ($items as $itemKey => $item) {
                    switch ($jsonKey) {
                        case 'cpu_percentage':
                            $services[] = $this->getServiceStruct(
                                $agentcheck['servicetemplate_id'],
                                __('CPU usage percentage'),
                                $agentcheck['servicetemplate']['servicetemplatecommandargumentvalues']
                            );
                            break;

                        case 'system_load':
                            $servicetemplatecommandargumentvalues = $agentcheck['servicetemplate']['servicetemplatecommandargumentvalues'];
                            $warning = 24;
                            $critical = 32;
                            if (isset($this->agentResponse['cpu']['cpu_percentage'])) {
                                $cpuCount = sizeof($this->agentResponse['cpu']['cpu_percentage']);
                                if ($cpuCount < 1) {
                                    $cpuCount = 1;
                                }
                                $warning = $cpuCount;
                                $critical = $cpuCount + 2;
                            }

                            $servicetemplatecommandargumentvalues[0]['value'] = $warning;
                            $servicetemplatecommandargumentvalues[1]['value'] = $critical;

                            $services[] = $this->getServiceStruct(
                                $agentcheck['servicetemplate_id'],
                                __('CPU load'),
                                $servicetemplatecommandargumentvalues
                            );
                            break;

                        case 'disk_io':
                            $servicetemplatecommandargumentvalues = $agentcheck['servicetemplate']['servicetemplatecommandargumentvalues'];
                            $servicetemplatecommandargumentvalues[2]['value'] = $itemKey; //sda

                            $services[] = $this->getServiceStruct(
                                $agentcheck['servicetemplate_id'],
                                __('Disk stats of: {0}', $itemKey),
                                $servicetemplatecommandargumentvalues
                            );
                            break;

                        case 'disks':
                            $servicetemplatecommandargumentvalues = $agentcheck['servicetemplate']['servicetemplatecommandargumentvalues'];
                            $servicetemplatecommandargumentvalues[2]['value'] = $item['disk']['device']; // /dev/sda1

                            $services[] = $this->getServiceStruct(
                                $agentcheck['servicetemplate_id'],
                                __('Disk usage of: {0}', $item['disk']['device']),
                                $servicetemplatecommandargumentvalues
                            );
                            break;

                        case 'net_io':
                            $speed = 1000; // default bandwidth speed
                            if (isset($this->agentResponse['net_stats'][$itemKey]['speed'])) {
                                if ($this->agentResponse['net_stats'][$itemKey]['speed'] > 0) {
                                    $speed = $this->agentResponse['net_stats'][$itemKey]['speed'];
                                }
                            }

                            $maxMegabytePerSecond = $speed / 8;
                            $maxBytePerSecond = $maxMegabytePerSecond * 1024 * 1024;

                            $warning = $maxBytePerSecond / 100 * 85; // 85% of total bandwidth
                            $critical = $maxBytePerSecond / 100 * 90; // 90% of total bandwidth

                            $servicetemplatecommandargumentvalues = $agentcheck['servicetemplate']['servicetemplatecommandargumentvalues'];
                            $servicetemplatecommandargumentvalues[0]['value'] = $warning; // Total average bytes warning per second
                            $servicetemplatecommandargumentvalues[1]['value'] = $critical; // Total average bytes critical per second
                            $servicetemplatecommandargumentvalues[2]['value'] = 5; // Total average errors warning
                            $servicetemplatecommandargumentvalues[3]['value'] = 10; // Total average errors critical
                            $servicetemplatecommandargumentvalues[4]['value'] = 5; // Total average drops warning
                            $servicetemplatecommandargumentvalues[5]['value'] = 10; // Total average drops critical
                            $servicetemplatecommandargumentvalues[6]['value'] = $itemKey; // Device

                            $services[] = $this->getServiceStruct(
                                $agentcheck['servicetemplate_id'],
                                __('Network stats of: {0}', $itemKey),
                                $servicetemplatecommandargumentvalues
                            );
                            break;

                        case 'net_stats':
                            $servicetemplatecommandargumentvalues = $agentcheck['servicetemplate']['servicetemplatecommandargumentvalues'];
                            $servicetemplatecommandargumentvalues[0]['value'] = 'critical'; // Nagios state if interface is down
                            $servicetemplatecommandargumentvalues[1]['value'] = $itemKey; // Device

                            $services[] = $this->getServiceStruct(
                                $agentcheck['servicetemplate_id'],
                                __('Network state of: {0}', $itemKey),
                                $servicetemplatecommandargumentvalues
                            );
                            break;

                        case 'processes':
                            $processName = $item['name'];
                            if (!empty($item['exec'])) {
                                $processName = $item['exec'];
                            }
                            if (!empty($item['cmdline']) && is_array($item['cmdline'])) {
                                // Agent 1.x
                                $processName = implode(' ', $item['cmdline']);
                            }
                            if (!empty($item['cmdline']) && is_string($item['cmdline'])) {
                                // Agent 3.x
                                $processName = implode(' ', $item['cmdline']);
                            }


                            $servicetemplatecommandargumentvalues = $agentcheck['servicetemplate']['servicetemplatecommandargumentvalues'];
                            $servicetemplatecommandargumentvalues[6]['value'] = $processName; // match

                            $services[] = $this->getServiceStruct(
                                $agentcheck['servicetemplate_id'],
                                __('Process: {0}', $processName),
                                $servicetemplatecommandargumentvalues
                            );
                            break;

                        case 'systemd_services':
                            $servicetemplatecommandargumentvalues = $agentcheck['servicetemplate']['servicetemplatecommandargumentvalues'];
                            $servicetemplatecommandargumentvalues[0]['value'] = $itemKey; // apache2.service

                            $services[] = $this->getServiceStruct(
                                $agentcheck['servicetemplate_id'],
                                $itemKey,
                                $servicetemplatecommandargumentvalues
                            );
                            break;

                        case 'launchd_services':
                            $servicetemplatecommandargumentvalues = $agentcheck['servicetemplate']['servicetemplatecommandargumentvalues'];
                            $servicetemplatecommandargumentvalues[0]['value'] = $itemKey; // com.apple.trustd

                            $services[] = $this->getServiceStruct(
                                $agentcheck['servicetemplate_id'],
                                $itemKey,
                                $servicetemplatecommandargumentvalues
                            );
                            break;

                        case 'windows_services':
                            $match = $item['Name'];
                            if (!empty($item['DisplayName'])) {
                                $match = $item['DisplayName'];
                            }
                            if (!empty($item['BinPath'])) {
                                $match = $item['BinPath'];
                            }

                            $serviceName = $item['Name'];
                            if (!empty($item['BinPath'])) {
                                $serviceName = $item['BinPath'];
                            }
                            if (!empty($item['DisplayName'])) {
                                $serviceName = $item['DisplayName'];
                            }

                            $servicetemplatecommandargumentvalues = $agentcheck['servicetemplate']['servicetemplatecommandargumentvalues'];
                            $servicetemplatecommandargumentvalues[2]['value'] = $match; // C:\WINDOWS\System32\DriverStore\FileRepository\sgx_psw.inf_amd64_bff7913eb62bbf90\aesm_service.exe

                            $services[] = $this->getServiceStruct(
                                $agentcheck['servicetemplate_id'],
                                $serviceName, // Intel® SGX AESM
                                $servicetemplatecommandargumentvalues
                            );
                            break;

                        case 'customchecks':

                            break;

                        /*
                    case 'sensors':
                        foreach($item as $sensorKey => $sensor){
                            switch($sensorKey){
                                case 'Batteries':


                                    break;

                                case 'Temperatures':
                                    break;
                            }
                        }

                        break;
                        */
                    }

                    break;
                }
            }
        }


    }

    /**
     * @param int $servicetemplateId
     * @param string $servicename
     * @param array $commandargumentvalues
     * @return array
     */
    public function getServiceStruct($servicetemplateId, $servicename, $commandargumentvalues = []) {
        return [
            'host_id'                      => $this->hostId,
            'servicetemplate_id'           => $servicetemplateId,
            'name'                         => $servicename,
            'servicecommandargumentvalues' => $commandargumentvalues
        ];
    }


}
