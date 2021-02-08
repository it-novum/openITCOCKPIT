<?php


namespace itnovum\openITCOCKPIT\Agent;


use App\Model\Table\AgentchecksTable;
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
     * @var AgentchecksTable $AgentchecksTable
     */
    private $AgentchecksTable;

    /**
     * AgentResponseToServices constructor.
     * @param int $hostId
     * @param array $agentResponse
     */
    public function __construct($hostId, $agentResponse = []) {
        $this->hostId = $hostId;
        $this->agentResponse = $agentResponse;
        $this->AgentchecksTable = TableRegistry::getTableLocator()->get('Agentchecks');

    }

    /**
     * @todo implement: libvirt, docker, alfresco, windows_eventlog, customchecks, cpu_percentage
     */
    public function getAllServices() {
        $services = [];
        foreach ($this->agentResponse as $mainKey => $items) {
            /**
             * @todo check if service already exists
             */
            switch ($mainKey) {
                case 'memory':
                    $memoryService = $this->getServiceStructByName('memory', __('Memory usage percentage'));
                    if ($memoryService) {
                        $services['memory'] = $memoryService;
                    }
                    break;
                case 'swap':
                    $swapService = $this->getServiceStructByName('swap', __('Swap usage percentage'));
                    if ($swapService) {
                        $services['swap'] = $swapService;
                    }
                    break;
                case 'system_load':
                    $systemLoad = $this->getServiceStructForSystemLoad(__('CPU load'));
                    if ($systemLoad) {
                        $services['system_load'] = $systemLoad;
                    }
                    break;
                case 'sensors':
                    $sensorServices = $this->getServiceStructForSensors([
                            'Temperatures',
                            'Batteries'
                        ]
                    );
                    if ($sensorServices) {
                        $services['sensors'] = $sensorServices;
                    }
                    break;
                case 'disk_io':
                    $diskIoServices = $this->getServiceStructForDiskIo();
                    if ($diskIoServices) {
                        $services['disk_io'] = $diskIoServices;
                    }
                    break;
                case 'disks':
                    $disksServices = $this->getServiceStructForDisks();
                    if ($disksServices) {
                        $services['disks'] = $disksServices;
                    }
                    break;
                case 'net_io':
                    $netIoServices = $this->getServiceStructForNetIo();
                    if ($netIoServices) {
                        $services['net_io'] = $netIoServices;
                    }
                    break;
                case 'net_stats':
                    $netStatsServices = $this->getServiceStructForNetStats();
                    if ($netStatsServices) {
                        $services['net_stats'] = $netStatsServices;
                    }
                    break;
                case 'processes':
                    $processServices = $this->getServiceStructForProcesses();
                    if ($processServices) {
                        $services['processes'] = $processServices;
                    }
                    break;
                case 'systemd_services':
                    $systemdServices = $this->getServiceStructForSystemdServices();
                    if ($systemdServices) {
                        $services['systemd_services'] = $systemdServices;
                    }
                    break;
                case 'launchd_services':
                    $launchdServices = $this->getServiceStructForLaunchdServices();
                    if ($launchdServices) {
                        $services['launchd_services'] = $launchdServices;
                    }
                    break;
                case 'windows_services':
                    $windowsServices = $this->getServiceStructForwindowsServices();
                    if ($windowsServices) {
                        $services['windows_services'] = $windowsServices;
                    }
                    break;
            }
        }
        return $services;

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

    /**
     * @param array $agentchecks
     * @param string $mainKey
     * @param string|null $subKey
     * @return array|false
     */
    private function getAgentcheckByJsonKeys($agentchecks, $mainKey, $subKey = null) {
        $needle = $mainKey;
        if ($subKey !== null) {
            $needle = sprintf('%s.%s', $mainKey, $subKey);
        }

        if (isset($agentchecks[$needle])) {
            return $agentchecks[$needle];
        }

        return false;
    }

    /**
     * @param string $name
     * @param string $servicename
     * @return array|bool
     */
    private function getServiceStructByName(string $name, string $servicename) {
        $agentcheck = $this->AgentchecksTable->getAgentcheckByName($name);
        if (empty($agentcheck)) {
            return false;
        }
        return $this->getServiceStruct(
            $agentcheck['servicetemplate_id'],
            $servicename,
            $agentcheck['servicetemplate']['servicetemplatecommandargumentvalues']
        );
    }

    /**
     * @param string $servicename
     * @return array|bool
     */
    private function getServiceStructForSystemLoad(string $servicename) {
        $agentcheck = $this->AgentchecksTable->getAgentcheckByName('system_load');
        if (empty($agentcheck)) {
            return false;
        }
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

        return $this->getServiceStruct(
            $agentcheck['servicetemplate_id'],
            $servicename,
            $servicetemplatecommandargumentvalues
        );

    }

    /**
     * @param array $categories
     * @return array|bool
     */
    private function getServiceStructForSensors(array $categories) {
        $services = [];
        foreach ($categories as $category) {
            $agentcheck = $this->AgentchecksTable->getAgentcheckByName(sprintf('%s.%s', 'sensors', $category));
            if (empty($agentcheck)) {
                continue;
            }
            if (isset($this->agentResponse['sensors'][$category])) {
                foreach ($this->agentResponse['sensors'][$category] as $itemKey => $items) {
                    $servicetemplatecommandargumentvalues = $agentcheck['servicetemplate']['servicetemplatecommandargumentvalues'];
                    switch ($category) {
                        case 'Temperatures':
                            $servicetemplatecommandargumentvalues[3]['value'] = $items['label'];
                            $services[] = $this->getServiceStruct(
                                $agentcheck['servicetemplate_id'],
                                __('Sensor: {0}', $items['label']),
                                $servicetemplatecommandargumentvalues
                            );
                            break;
                        case 'Batteries':
                            $servicetemplatecommandargumentvalues[3]['value'] = $items['id'];
                            $services[] = $this->getServiceStruct(
                                $agentcheck['servicetemplate_id'],
                                __('Battery: {0}', $items['id']),
                                $servicetemplatecommandargumentvalues
                            );
                            break;
                    }
                }
            }
        }
        if (empty($services)) {
            return false;
        }
        return $services;
    }

    /**
     * @return array|bool
     */
    private function getServiceStructForDiskIo() {
        $agentcheck = $this->AgentchecksTable->getAgentcheckByName('disk_io');
        if (empty($agentcheck)) {
            return false;
        }
        $services = [];
        if (isset($this->agentResponse['disk_io'])) {
            $servicetemplatecommandargumentvalues = $agentcheck['servicetemplate']['servicetemplatecommandargumentvalues'];
            foreach ($this->agentResponse['disk_io'] as $deviceName => $device) {
                $servicetemplatecommandargumentvalues[2]['value'] = $deviceName; //sda
                $services[] = $this->getServiceStruct(
                    $agentcheck['servicetemplate_id'],
                    __('Disk stats of: {0}', $deviceName),
                    $servicetemplatecommandargumentvalues
                );

            }
        }
        if (empty($services)) {
            return false;
        }
        return $services;
    }

    /**
     * @return array|bool
     */
    private function getServiceStructForDisks() {
        $agentcheck = $this->AgentchecksTable->getAgentcheckByName('disks');
        if (empty($agentcheck)) {
            return false;
        }
        $services = [];
        if (isset($this->agentResponse['disks'])) {
            $servicetemplatecommandargumentvalues = $agentcheck['servicetemplate']['servicetemplatecommandargumentvalues'];
            foreach ($this->agentResponse['disks'] as $device) {
                $servicetemplatecommandargumentvalues[2]['value'] = $device['disk']['device']; // /dev/sda1

                $services[] = $this->getServiceStruct(
                    $agentcheck['servicetemplate_id'],
                    __('Disk usage of: {0}', $device['disk']['device']),
                    $servicetemplatecommandargumentvalues
                );
            }
        }
        if (empty($services)) {
            return false;
        }
        return $services;
    }

    /**
     * @return array|bool
     */
    private function getServiceStructForNetIo() {
        $agentcheck = $this->AgentchecksTable->getAgentcheckByName('net_io');
        if (empty($agentcheck)) {
            return false;
        }
        $services = [];
        if (isset($this->agentResponse['net_io'])) {
            $speed = 1000; // default bandwidth speed
            $servicetemplatecommandargumentvalues = $agentcheck['servicetemplate']['servicetemplatecommandargumentvalues'];
            foreach ($this->agentResponse['net_io'] as $nicName => $nic) {
                if (isset($this->agentResponse['net_stats'][$nicName]['speed'])) {
                    if ($this->agentResponse['net_stats'][$nicName]['speed'] > 0) {
                        $speed = $this->agentResponse['net_stats'][$nicName]['speed'];
                    }
                }
                $maxMegabytePerSecond = $speed / 8;
                $maxBytePerSecond = $maxMegabytePerSecond * 1024 * 1024;

                $warning = $maxBytePerSecond / 100 * 85; // 85% of total bandwidth
                $critical = $maxBytePerSecond / 100 * 90; // 90% of total bandwidth

                $servicetemplatecommandargumentvalues[0]['value'] = $warning; // Total average bytes warning per second
                $servicetemplatecommandargumentvalues[1]['value'] = $critical; // Total average bytes critical per second
                $servicetemplatecommandargumentvalues[2]['value'] = 5; // Total average errors warning
                $servicetemplatecommandargumentvalues[3]['value'] = 10; // Total average errors critical
                $servicetemplatecommandargumentvalues[4]['value'] = 5; // Total average drops warning
                $servicetemplatecommandargumentvalues[5]['value'] = 10; // Total average drops critical
                $servicetemplatecommandargumentvalues[6]['value'] = $nicName; // Device

                $services[] = $this->getServiceStruct(
                    $agentcheck['servicetemplate_id'],
                    __('Network stats of: {0}', $nicName),
                    $servicetemplatecommandargumentvalues
                );
            }
        }
        if (empty($services)) {
            return false;
        }
        return $services;
    }

    /**
     * @return array|bool
     */
    private function getServiceStructForNetStats() {
        $agentcheck = $this->AgentchecksTable->getAgentcheckByName('net_stats');
        if (empty($agentcheck)) {
            return false;
        }
        $services = [];
        if (isset($this->agentResponse['net_stats'])) {
            $servicetemplatecommandargumentvalues = $agentcheck['servicetemplate']['servicetemplatecommandargumentvalues'];
            foreach ($this->agentResponse['net_stats'] as $nicName => $nic) {
                $servicetemplatecommandargumentvalues[0]['value'] = 'critical'; // Nagios state if interface is down
                $servicetemplatecommandargumentvalues[1]['value'] = $nicName; // Device

                $services[] = $this->getServiceStruct(
                    $agentcheck['servicetemplate_id'],
                    __('Network state of: {0}', $nicName),
                    $servicetemplatecommandargumentvalues
                );
            }
        }
        if (empty($services)) {
            return false;
        }
        return $services;
    }

    /**
     * @return array|bool
     */
    private function getServiceStructForProcesses() {
        $agentcheck = $this->AgentchecksTable->getAgentcheckByName('processes');
        if (empty($agentcheck)) {
            return false;
        }
        $services = [];
        if (isset($this->agentResponse['processes'])) {
            $servicetemplatecommandargumentvalues = $agentcheck['servicetemplate']['servicetemplatecommandargumentvalues'];
            foreach ($this->agentResponse['processes'] as $item) {
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
                    $processName = $item['cmdline'];
                }


                $servicetemplatecommandargumentvalues[6]['value'] = $processName; // match

                $services[] = $this->getServiceStruct(
                    $agentcheck['servicetemplate_id'],
                    __('Process: {0}', $processName),
                    $servicetemplatecommandargumentvalues
                );
            }
        }
        if (empty($services)) {
            return false;
        }
        return $services;
    }

    /**
     * @return array|bool
     */
    private function getServiceStructForSystemdServices() {
        $agentcheck = $this->AgentchecksTable->getAgentcheckByName('systemd_services');
        if (empty($agentcheck)) {
            return false;
        }
        $services = [];
        if (isset($this->agentResponse['systemd_services'])) {
            $servicetemplatecommandargumentvalues = $agentcheck['servicetemplate']['servicetemplatecommandargumentvalues'];
            foreach ($this->agentResponse['systemd_services'] as $itemKey => $item) {
                $servicetemplatecommandargumentvalues[0]['value'] = $item['Name']; // apache2.service

                $services[] = $this->getServiceStruct(
                    $agentcheck['servicetemplate_id'],
                    $item['Name'],
                    $servicetemplatecommandargumentvalues
                );
            }
        }
        if (empty($services)) {
            return false;
        }
        return $services;
    }

    /**
     * @return array|bool
     */
    private function getServiceStructForLaunchdServices() {
        $agentcheck = $this->AgentchecksTable->getAgentcheckByName('launchd_services');
        if (empty($agentcheck)) {
            return false;
        }
        $services = [];
        if (isset($this->agentResponse['launchd_services'])) {
            $servicetemplatecommandargumentvalues = $agentcheck['servicetemplate']['servicetemplatecommandargumentvalues'];
            foreach ($this->agentResponse['launchd_services'] as $itemKey => $item) {
                $servicetemplatecommandargumentvalues[0]['value'] = $item['Label']; // com.apple.trustd

                $services[] = $this->getServiceStruct(
                    $agentcheck['servicetemplate_id'],
                    $item['Label'],
                    $servicetemplatecommandargumentvalues
                );
            }
        }
        if (empty($services)) {
            return false;
        }
        return $services;
    }

    /**
     * @return array|bool
     */
    private function getServiceStructForwindowsServices() {
        $agentcheck = $this->AgentchecksTable->getAgentcheckByName('windows_services');
        if (empty($agentcheck)) {
            return false;
        }
        $services = [];
        if (isset($this->agentResponse['windows_services'])) {
            $servicetemplatecommandargumentvalues = $agentcheck['servicetemplate']['servicetemplatecommandargumentvalues'];
            foreach ($this->agentResponse['windows_services'] as $itemKey => $item) {
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
                $servicetemplatecommandargumentvalues[2]['value'] = $match; // C:\WINDOWS\System32\DriverStore\FileRepository\sgx_psw.inf_amd64_bff7913eb62bbf90\aesm_service.exe
                $services[] = $this->getServiceStruct(
                    $agentcheck['servicetemplate_id'],
                    $serviceName, // Intel® SGX AESM
                    $servicetemplatecommandargumentvalues
                );
            }
        }
        if (empty($services)) {
            return false;
        }
        return $services;
    }
}
