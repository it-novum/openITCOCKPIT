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

use App\Model\Table\AgentchecksTable;
use App\Model\Table\CommandsTable;
use App\Model\Table\ServicetemplatesTable;

/**
 * Class Cronjob
 * @package itnovum\openITCOCKPIT\InitialDatabase
 */
class Agent extends Importer {

    /**
     * @var CommandsTable
     */
    private $CommandsTable;

    /**
     * @var ServicetemplatesTable
     */
    private $ServicetemplatesTable;

    /**
     * @var AgentchecksTable
     */
    private $AgentchecksTable;

    /**
     * Agent constructor.
     * @param CommandsTable $CommandsTable
     * @param ServicetemplatesTable $ServicetemplatesTable
     * @param AgentchecksTable $AgentchecksTable
     */
    public function __construct(CommandsTable $CommandsTable, ServicetemplatesTable $ServicetemplatesTable, AgentchecksTable $AgentchecksTable) {
        $this->CommandsTable = $CommandsTable;
        $this->ServicetemplatesTable = $ServicetemplatesTable;
        $this->AgentchecksTable = $AgentchecksTable;
    }

    /**
     * @return bool
     */
    public function import() {
        $data = $this->getData();

        foreach ($data['Commands'] as $command) {
            if (isset($command['uuid']) && !$this->CommandsTable->existsByUuid($command['uuid'])) {
                $entity = $this->CommandsTable->newEntity($command);
                $this->CommandsTable->save($entity);
            }
        }

        foreach ($data['Servicetemplates'] as $servicetemplate) {
            if (isset($servicetemplate['uuid']) && !$this->ServicetemplatesTable->existsByUuid($servicetemplate['uuid']))
                if (isset($servicetemplate['command_id']) && $this->CommandsTable->existsByUuid($servicetemplate['command_id'])) {
                    $command = $this->CommandsTable->getCommandByUuid($servicetemplate['command_id'], true, false)[0];
                    $servicetemplate['command_id'] = $command['id'];
                    if (!empty($servicetemplate['servicetemplatecommandargumentvalues']) && !empty($command['commandarguments'])) {
                        foreach ($servicetemplate['servicetemplatecommandargumentvalues'] as $templateArgumentKey => $templateArgumentValue) {
                            $servicetemplate['servicetemplatecommandargumentvalues'][$templateArgumentKey] = [
                                'commandargument_id' => $this->getCommandArgumentIdByName($templateArgumentValue['commandargument_id'], $command['commandarguments']),
                                'value'              => $templateArgumentValue['value'],
                            ];
                        }
                    }
                    $entity = $this->ServicetemplatesTable->newEntity($servicetemplate);
                    $this->ServicetemplatesTable->save($entity);
                }
        }

        foreach ($data['Agentchecks'] as $agentcheck) {
            //debug($this->ServicetemplatesTable->getServicetemplateByUuid($agentcheck['servicetemplate_id']));die();
            $servicetemplate = $this->ServicetemplatesTable->getServicetemplateByUuid($agentcheck['servicetemplate_id']);
            if (isset($servicetemplate['Servicetemplate']) && isset($servicetemplate['Servicetemplate']['id'])) {
                $agentcheck['servicetemplate_id'] = $servicetemplate['Servicetemplate']['id'];

                if (!$this->AgentchecksTable->existsByNameAndServicetemplateId($agentcheck['name'], $agentcheck['servicetemplate_id'])) {
                    $entity = $this->AgentchecksTable->newEntity($agentcheck);
                    $this->AgentchecksTable->save($entity);
                }
            }
        }

        return true;
    }

    /**
     * @param string $name
     * @param array $commandarguments
     * @return bool|mixed
     */
    public function getCommandArgumentIdByName(string $name, array $commandarguments) {
        foreach ($commandarguments as $commandargument) {
            if (isset($commandargument['name']) && $commandargument['name'] === $name) {
                return $commandargument['id'];
            }
        }
        return false;
    }

    public function getAgentchecksData() {
        $data = [
            [
                'name'               => 'agent',
                'plugin_name'        => 'Agent',
                'servicetemplate_id' => 'c475f1c8-fd28-493d-aad0-7861e418170d'
            ],
            [
                'name'               => 'cpu_percentage',
                'plugin_name'        => 'CpuTotalPercentage',
                'servicetemplate_id' => 'be4c9649-8771-4704-b409-c56b5f67abc8'
            ],
            [
                'name'               => 'system_load',
                'plugin_name'        => 'SystemLoad',
                'servicetemplate_id' => '566a710f-a554-4fa6-b2a2-e46b1d937a64'
            ],
            [
                'name'               => 'memory',
                'plugin_name'        => 'MemoryUsage',
                'servicetemplate_id' => '4052fe4f-50b1-443a-8be1-9dbca8d43ebd'
            ],
            [
                'name'               => 'swap',
                'plugin_name'        => 'SwapUsage',
                'servicetemplate_id' => 'f9d5e18a-8894-4324-b54a-497db87b6f4f'
            ],
            [
                'name'               => 'disk_io',
                'plugin_name'        => 'DiskIO',
                'servicetemplate_id' => '68fb72a3-5bf5-4d97-8690-91242628659b'
            ],
            [
                'name'               => 'disks',
                'plugin_name'        => 'DiskUsage',
                'servicetemplate_id' => '24851d0d-32fa-4048-bd67-6a30d710bba1'
            ],
            [
                'name'               => 'sensors',
                'plugin_name'        => 'Fan',
                'servicetemplate_id' => '3e0bd59e-822d-47ed-a5b2-15f1e53fe043'
            ],
            [
                'name'               => 'sensors',
                'plugin_name'        => 'Temperature',
                'servicetemplate_id' => 'f73dc076-bdb0-4302-9776-88ca1ba79364'
            ],
            [
                'name'               => 'sensors',
                'plugin_name'        => 'Battery',
                'servicetemplate_id' => '21057a75-57a1-4972-8f2f-073c8a6000b0'
            ],
            [
                'name'               => 'net_io',
                'plugin_name'        => 'NetIO',
                'servicetemplate_id' => 'e5d848f5-a323-4bfe-9ec5-1c5cdf138abf'
            ],
            [
                'name'               => 'net_stats',
                'plugin_name'        => 'NetStats',
                'servicetemplate_id' => 'f6f64207-ef75-4a3d-b9f2-2eaab398a6f1'
            ],
            [
                'name'               => 'processes',
                'plugin_name'        => 'Process',
                'servicetemplate_id' => '37a78eca-4a58-46cd-9fb1-6029724cab35'
            ],
            [
                'name'               => 'windows_services',
                'plugin_name'        => 'WindowsService',
                'servicetemplate_id' => '370731ed-34d4-48f4-933e-90e488bb390f'
            ],
            [
                'name'               => 'dockerstats',
                'plugin_name'        => 'DockerContainerRunning',
                'servicetemplate_id' => 'ca73653f-2bba-4542-b11b-0bbd0ecc8b7a'
            ],
            [
                'name'               => 'dockerstats',
                'plugin_name'        => 'DockerContainerCPU',
                'servicetemplate_id' => 'a9f7757e-34b0-4df9-8fca-ab8b594c2c26'
            ],
            [
                'name'               => 'dockerstats',
                'plugin_name'        => 'DockerContainerMemory',
                'servicetemplate_id' => 'aef4c1a8-ed71-4799-a164-3ad469baadc5'
            ],
            [
                'name'               => 'qemustats',
                'plugin_name'        => 'QemuVMRunning',
                'servicetemplate_id' => 'c1c8c77a-cecf-4a94-8418-a69081946ba0'
            ],
            [
                'name'               => 'customchecks',
                'plugin_name'        => 'Customcheck',
                'servicetemplate_id' => '03b32b83-df7b-4204-a8bb-138a4939c554'
            ],
        ];
        return $data;
    }

    public function getCommandsData() {
        $data = [
            [
                'name'             => 'check_oitc_agent_active',
                'command_line'     => '/opt/openitc/receiver/bin/poller.php poller -H $HOSTNAME$ -S -k -c /opt/openitc/etc/receiver/production.json',
                'command_type'     => CHECK_COMMAND,
                'human_args'       => null,
                'uuid'             => 'be116ff1-f797-4ccb-993c-b80ccb337de8',
                'description'      => "Actively executed by the monitoring engine.\nSend HTTP-Request to the target device and query the openITCOCKPIT Agent API interface.",
                'commandarguments' => []
            ],

            [
                'name'             => 'check_oitc_agent_cpu_total_percentage',
                'command_line'     => '$USER1$/check_dummy 3 "No data received from agent"',
                'command_type'     => CHECK_COMMAND,
                'human_args'       => null,
                'uuid'             => '27551d06-610d-4331-af2d-aab77c45bd36',
                'description'      => "Check CPU usage in percentage.\nWarning and Critical thresholds are percentage values from 0-100\n If per core is set to 1 a seperate gauge per cpu core will be generated.",
                'commandarguments' => [
                    [
                        'name'       => '$ARG1$',
                        'human_name' => 'Warning %'
                    ],
                    [
                        'name'       => '$ARG2$',
                        'human_name' => 'Critical %'
                    ],
                    [
                        'name'       => '$ARG3$',
                        'human_name' => 'Per core 1/0'
                    ]
                ]
            ],

            [
                'name'             => 'check_oitc_agent_system_load',
                'command_line'     => '$USER1$/check_dummy 3 "No data received from agent"',
                'command_type'     => CHECK_COMMAND,
                'human_args'       => null,
                'uuid'             => 'd4e55865-5cbc-4f01-a4e3-896074a1d7d1',
                'description'      => "Check system load.\n" .
                    "Warning and Critical thresholds are integer values (e.g. 2 or 4).\n" .
                    "Timerange: Integer value that decides if thresholds will be used for load1, load5 or load15. (Value: 1 / 5 / 15)",
                'commandarguments' => [
                    [
                        'name'       => '$ARG1$',
                        'human_name' => 'Warning'
                    ],
                    [
                        'name'       => '$ARG2$',
                        'human_name' => 'Critical'
                    ],
                    [
                        'name'       => '$ARG3$',
                        'human_name' => 'Timerange'
                    ]
                ]
            ],

            [
                'name'             => 'check_oitc_agent_memory_usage',
                'command_line'     => '$USER1$/check_dummy 3 "No data received from agent"',
                'command_type'     => CHECK_COMMAND,
                'human_args'       => null,
                'uuid'             => 'ab3fe6f2-b5c6-4ba0-9d72-123696d42277',
                'description'      => "Return memory usage as percentage\n" .
                    "Warning and Critical thresholds are percentage values from 0-100 or float values depending on the chosen unit." .
                    "Unit: Determines if warning and critical is defined as percentage (%) or amount of ('B', 'kB', 'KiB', 'MB', 'MiB', 'GB', 'GiB', 'TB', 'TiB', 'PB', 'PiB', 'EB', 'EiB')",
                'commandarguments' => [
                    [
                        'name'       => '$ARG1$',
                        'human_name' => 'Warning'
                    ],
                    [
                        'name'       => '$ARG2$',
                        'human_name' => 'Critical'
                    ],
                    [
                        'name'       => '$ARG3$',
                        'human_name' => 'Unit'
                    ]
                ]
            ],

            [
                'name'             => 'check_oitc_agent_swap_usage',
                'command_line'     => '$USER1$/check_dummy 3 "No data received from agent"',
                'command_type'     => CHECK_COMMAND,
                'human_args'       => null,
                'uuid'             => 'ebff0db2-e5de-4430-8799-3c6662422646',
                'description'      => "Return swap usage as percentage\n" .
                    "Warning and Critical thresholds are percentage values from 0-100",
                'commandarguments' => [
                    [
                        'name'       => '$ARG1$',
                        'human_name' => 'Warning'
                    ],
                    [
                        'name'       => '$ARG2$',
                        'human_name' => 'Critical'
                    ]
                ]
            ],


            [
                'name'             => 'check_oitc_agent_disk_io',
                'command_line'     => '$USER1$/check_dummy 3 "No data received from agent"',
                'command_type'     => CHECK_COMMAND,
                'human_args'       => null,
                'uuid'             => 'ffd0a58f-01e0-4a4d-8db9-a3107a9e765f',
                'description'      => "Fetch disk io statistics.\n" .
                    "Linux only: Warning and Critical thresholds for disk load as percentage value.",
                'commandarguments' => [
                    [
                        'name'       => '$ARG1$',
                        'human_name' => 'Warning %'
                    ],
                    [
                        'name'       => '$ARG2$',
                        'human_name' => 'Critical %'
                    ]
                ]
            ],

            [
                'name'             => 'check_oitc_agent_disk_usage',
                'command_line'     => '$USER1$/check_dummy 3 "No data received from agent"',
                'command_type'     => CHECK_COMMAND,
                'human_args'       => null,
                'uuid'             => '3830078a-ccc5-41fd-ae33-8369bca1a6b7',
                'description'      => "Checks disk usage of a device.\n" .
                    "Warning: percentage threshold (0-100) or GB value (20)\n" .
                    "Critical: percentage threshold (0-100) or GB value (10)\n" .
                    "Mountpoint: Mountpoint of the device. Examples: Linux: / Windows: C:\\\n" .
                    "Percentage: Determines if warning and critical is defined as percentage or amount of GB\n",
                'commandarguments' => [
                    [
                        'name'       => '$ARG1$',
                        'human_name' => 'Warning'
                    ],
                    [
                        'name'       => '$ARG2$',
                        'human_name' => 'Critical'
                    ],
                    [
                        'name'       => '$ARG3$',
                        'human_name' => 'Mountpoint'
                    ],
                    [
                        'name'       => '$ARG4$',
                        'human_name' => 'Percentage (0/1)'
                    ]
                ]
            ],

            [
                'name'             => 'check_oitc_agent_fan',
                'command_line'     => '$USER1$/check_dummy 3 "No data received from agent"',
                'command_type'     => CHECK_COMMAND,
                'human_args'       => null,
                'uuid'             => '7bee1594-b029-4db1-8059-694a75b4f83e',
                'description'      => "Return fan speed of a given device.\n" .
                    "Warning: Fan RPM as integer\n" .
                    "Critical:  Fan RPM as integer\n" .
                    "Device: Fan device name (e.g. cpu_fan) \n" .
                    "Only available on Linux and macOS.\n",
                'commandarguments' => [
                    [
                        'name'       => '$ARG1$',
                        'human_name' => 'Warning'
                    ],
                    [
                        'name'       => '$ARG2$',
                        'human_name' => 'Critical'
                    ],
                    [
                        'name'       => '$ARG3$',
                        'human_name' => 'Device'
                    ]
                ]
            ],

            [
                'name'             => 'check_oitc_agent_temperature',
                'command_line'     => '$USER1$/check_dummy 3 "No data received from agent"',
                'command_type'     => CHECK_COMMAND,
                'human_args'       => null,
                'uuid'             => '42e3e6c9-a989-4044-a993-193558ad7964',
                'description'      => "Return the temperature of a given sensor.\n" .
                    "Warning: Temperature as integer\n" .
                    "Critical: Temperature as integer\n" .
                    "Average: Use average value of all temperatures, if a device has multiple temperature values (eg. for each core of one cpu device) \n" .
                    "Device: Sensor name (e.g. coretemp / acpitz / pch_skylake) \n" .
                    "Only available on Linux.\n",
                'commandarguments' => [
                    [
                        'name'       => '$ARG1$',
                        'human_name' => 'Warning'
                    ],
                    [
                        'name'       => '$ARG2$',
                        'human_name' => 'Critical'
                    ],
                    [
                        'name'       => '$ARG3$',
                        'human_name' => 'Average 1/0'
                    ],
                    [
                        'name'       => '$ARG4$',
                        'human_name' => 'Device'
                    ]
                ]
            ],

            [
                'name'             => 'check_oitc_agent_battery',
                'command_line'     => '$USER1$/check_dummy 3 "No data received from agent"',
                'command_type'     => CHECK_COMMAND,
                'human_args'       => null,
                'uuid'             => '02289d0e-0a3c-46e5-94f1-b18e57be4e70',
                'description'      => "Check battery power left in percentage. \nWarning and Critical thresholds are percentage values from 0-100",
                'commandarguments' => [
                    [
                        'name'       => '$ARG1$',
                        'human_name' => 'Warning %'
                    ],
                    [
                        'name'       => '$ARG2$',
                        'human_name' => 'Critical %'
                    ]
                ]
            ],

            [
                'name'             => 'check_oitc_agent_net_io',
                'command_line'     => '$USER1$/check_dummy 3 "No data received from agent"',
                'command_type'     => CHECK_COMMAND,
                'human_args'       => null,
                'uuid'             => '4186bc96-0081-48fc-bee6-0ff163c9f4a2',
                'description'      => "Fetch network io statistics and returns the specific nagios status, if at least one value exceeds the given (warning or critical) edge.\n" .
                    "Total average bytes: Average bytes sent and received per second\n" .
                    "Total average bytes (warning/critical) as integer (e.g. 1073741824 = 1GB)\n" .
                    "Total average errors: Average value of packages send and received with errors\n" .
                    "Total average errors (warning/critical) as integer (e.g. 5 or 10)\n" .
                    "Total average drops: Average value of (input and output) packages which were dropped\n" .
                    "Total average drops (warning/critical) as integer (e.g. 5 or 10)\n" .
                    "Device: Network device name (e.g. eth0) \n",
                'commandarguments' => [
                    [
                        'name'       => '$ARG1$',
                        'human_name' => 'Total average bytes warning'
                    ],
                    [
                        'name'       => '$ARG2$',
                        'human_name' => 'Total average bytes critical'
                    ],
                    [
                        'name'       => '$ARG3$',
                        'human_name' => 'Total average errors warning'
                    ],
                    [
                        'name'       => '$ARG4$',
                        'human_name' => 'Total average errors critical'
                    ],
                    [
                        'name'       => '$ARG5$',
                        'human_name' => 'Total average drops warning'
                    ],
                    [
                        'name'       => '$ARG6$',
                        'human_name' => 'Total average drops critical'
                    ],
                    [
                        'name'       => '$ARG7$',
                        'human_name' => 'Device'
                    ]
                ]
            ],

            [
                'name'             => 'check_oitc_agent_net_stats',
                'command_line'     => '$USER1$/check_dummy 3 "No data received from agent"',
                'command_type'     => CHECK_COMMAND,
                'human_args'       => null,
                'uuid'             => '2c96d1c9-de93-4bfa-ab69-2b45bf40d2e3',
                'description'      => "Checks if the link of the device / interface is down and returns a custom nagios state\n" .
                    "Link Down State: The service state for nagios if link is down (warning/critical/unknown)" .
                    "Device: Network device name (e.g. eth0) \n",
                'commandarguments' => [
                    [
                        'name'       => '$ARG1$',
                        'human_name' => 'Link Down State'
                    ],
                    [
                        'name'       => '$ARG2$',
                        'human_name' => 'Device'
                    ]
                ]
            ],

            [
                'name'             => 'check_oitc_agent_process',
                'command_line'     => '$USER1$/check_dummy 3 "No data received from agent"',
                'command_type'     => CHECK_COMMAND,
                'human_args'       => null,
                'uuid'             => '629050dd-1359-4c8d-9f13-fc49fdab84dc',
                'description'      => "Checks if a custom process or many matching process(es) exceeds the given values for cpu, memory or amount.\n" .
                    "CPU: Warning and critical percentage values (0-100) of cpu usage for the matching process(es).\n" .
                    "Memory: Warning and critical percentage values (0-100) of memory usage for the matching process(es).\n" .
                    "Amount: Warning and critical values (e.g. 5 or 10) as amount of matching processes.\n" .
                    "Match: String that must match with the process command line (use process 'cmdline' > 'exec' > 'name'). Leave empty to select all.\n" .
                    "Strict: Decides if the match must be completely or just in a part (1/0).\n",
                'commandarguments' => [
                    [
                        'name'       => '$ARG1$',
                        'human_name' => 'CPU warning'
                    ],
                    [
                        'name'       => '$ARG2$',
                        'human_name' => 'CPU critical'
                    ],
                    [
                        'name'       => '$ARG3$',
                        'human_name' => 'Memory warning'
                    ],
                    [
                        'name'       => '$ARG4$',
                        'human_name' => 'Memory critical'
                    ],
                    [
                        'name'       => '$ARG5$',
                        'human_name' => 'Amount warning'
                    ],
                    [
                        'name'       => '$ARG6$',
                        'human_name' => 'Amount critical'
                    ],
                    [
                        'name'       => '$ARG7$',
                        'human_name' => 'Match'
                    ],
                    [
                        'name'       => '$ARG8$',
                        'human_name' => 'Strict'
                    ]
                ]
            ],

            [
                'name'             => 'check_oitc_agent_windows_service_running',
                'command_line'     => '$USER1$/check_dummy 3 "No data received from agent"',
                'command_type'     => CHECK_COMMAND,
                'human_args'       => null,
                'uuid'             => '06bf6bc1-f228-4adc-ac14-5fe902349768',
                'description'      => "Returns the amount of running windows services.\n" .
                    "Amount: Warning and critical values (e.g. 5 or 10) as amount of matching services.\n" .
                    "Match: String that must match with the service binpath (use service 'binpath' > 'display_name' > 'name'). Leave empty to select all.\n" .
                    "Strict: Decides if the match must be completely or just in a part (1/0).\n",
                'commandarguments' => [
                    [
                        'name'       => '$ARG1$',
                        'human_name' => 'Amount warning'
                    ],
                    [
                        'name'       => '$ARG2$',
                        'human_name' => 'Amount critical'
                    ],
                    [
                        'name'       => '$ARG3$',
                        'human_name' => 'Match'
                    ],
                    [
                        'name'       => '$ARG4$',
                        'human_name' => 'Strict'
                    ]
                ]
            ],

            [
                'name'             => 'check_oitc_agent_docker_running',
                'command_line'     => '$USER1$/check_dummy 3 "No data received from agent"',
                'command_type'     => CHECK_COMMAND,
                'human_args'       => null,
                'uuid'             => '577da91f-e70d-4218-bef1-c8b3b72d2ad5',
                'description'      => "Return the state of a docker container (ok/critical).\n" .
                    "Identifier Type: Values: name or id - Determines if the name of the id should be used to identify the container.\n" .
                    "Identifier:  Name or id of the container.\n",
                'commandarguments' => [
                    [
                        'name'       => '$ARG1$',
                        'human_name' => 'Identifier Type'
                    ],
                    [
                        'name'       => '$ARG2$',
                        'human_name' => 'Identifier'
                    ]
                ]
            ],

            [
                'name'             => 'check_oitc_agent_docker_cpu',
                'command_line'     => '$USER1$/check_dummy 3 "No data received from agent"',
                'command_type'     => CHECK_COMMAND,
                'human_args'       => null,
                'uuid'             => '198c52aa-28c0-45d8-a22d-7df3dad0f716',
                'description'      => "Return the cpu usage of a docker container.\n" .
                    "Identifier Type: Values: name or id - Determines if the name of the id should be used to identify the container.\n" .
                    "Identifier:  Name or id of the container.\n" .
                    "Warning and Critical thresholds are percentage values from 0-100.\n",
                'commandarguments' => [
                    [
                        'name'       => '$ARG1$',
                        'human_name' => 'Identifier Type'
                    ],
                    [
                        'name'       => '$ARG2$',
                        'human_name' => 'Identifier'
                    ],
                    [
                        'name'       => '$ARG3$',
                        'human_name' => 'Warning %'
                    ],
                    [
                        'name'       => '$ARG4$',
                        'human_name' => 'Critical %'
                    ],
                ]
            ],

            [
                'name'             => 'check_oitc_agent_docker_memory',
                'command_line'     => '$USER1$/check_dummy 3 "No data received from agent"',
                'command_type'     => CHECK_COMMAND,
                'human_args'       => null,
                'uuid'             => 'ba2b3392-a769-4b20-8ea2-10b60f2c74e8',
                'description'      => "Return the memory usage of a docker container.\n" .
                    "Identifier Type: Values: name or id - Determines if the name of the id should be used to identify the container.\n" .
                    "Identifier:  Name or id of the container.\n" .
                    "Warning and Critical thresholds are percentage values from 0-100 or float values depending on the chosen unit.\n" .
                    "Unit: Determines if warning and critical is defined as percentage (%) or amount of ('B', 'kB', 'KiB', 'MB', 'MiB', 'GB', 'GiB', 'TB', 'TiB', 'PB', 'PiB', 'EB', 'EiB')",
                'commandarguments' => [
                    [
                        'name'       => '$ARG1$',
                        'human_name' => 'Identifier Type'
                    ],
                    [
                        'name'       => '$ARG2$',
                        'human_name' => 'Identifier'
                    ],
                    [
                        'name'       => '$ARG3$',
                        'human_name' => 'Warning'
                    ],
                    [
                        'name'       => '$ARG4$',
                        'human_name' => 'Critical'
                    ],
                    [
                        'name'       => '$ARG5$',
                        'human_name' => 'Unit'
                    ],
                ]
            ],

            [
                'name'             => 'check_oitc_agent_qemu_vm_running',
                'command_line'     => '$USER1$/check_dummy 3 "No data received from agent"',
                'command_type'     => CHECK_COMMAND,
                'human_args'       => null,
                'uuid'             => '67ca5f1d-cc94-4bf1-8397-fc6e4abdbf92',
                'description'      => "Return the state of a qemu virtual machine (ok/critical).\n" .
                    "Identifier Type: Values: name or id - Determines if the name of the id should be used to identify the virtual machine.\n" .
                    "Identifier:  Name or id of the virtual machine.\n",
                'commandarguments' => [
                    [
                        'name'       => '$ARG1$',
                        'human_name' => 'Identifier Type'
                    ],
                    [
                        'name'       => '$ARG2$',
                        'human_name' => 'Identifier'
                    ]
                ]
            ],

            [
                'name'             => 'check_oitc_agent_customcheck',
                'command_line'     => '$USER1$/check_dummy 3 "No data received from agent"',
                'command_type'     => CHECK_COMMAND,
                'human_args'       => null,
                'uuid'             => 'fbd23c8c-453d-4107-ae27-2cfafe63fef5',
                'description'      => "Commands that should be executed by the openITCOCKPIT Agent to replace check_by_ssh or check_nrpe.\n" .
                    "Name: Unique name of the command (e.g. username)\n" .
                    "CMD_Line: Command line that will be executed by the agent (e.g. /usr/lib/nagios/plugins/check_users -w 5 -c 10)\n" .
                    "Interval: Execution interval in seconds\n" .
                    "Timeout: Check timeout in seconds\n",
                'commandarguments' => [
                    [
                        'name'       => '$ARG1$',
                        'human_name' => 'Name'
                    ],
                    [
                        'name'       => '$ARG2$',
                        'human_name' => 'CMD_LINE'
                    ],
                    [
                        'name'       => '$ARG3$',
                        'human_name' => 'Interval'
                    ],
                    [
                        'name'       => '$ARG4$',
                        'human_name' => 'Timeout'
                    ]
                ]
            ],

        ];
        return $data;
    }

    public function getServicetemplatesData() {
        $data = [
            [
                'uuid'                                      => 'c475f1c8-fd28-493d-aad0-7861e418170d',
                'template_name'                             => 'OITC_AGENT_ACTIVE',
                'name'                                      => 'OITC_AGENT_ACTIVE',
                'container_id'                              => ROOT_CONTAINER,
                'servicetemplatetype_id'                    => OITC_AGENT_SERVICE,
                'check_period_id'                           => '1',
                'notify_period_id'                          => '1',
                'description'                               => '',
                'command_id'                                => 'be116ff1-f797-4ccb-993c-b80ccb337de8',
                'check_command_args'                        => '',
                'checkcommand_info'                         => '',
                'eventhandler_command_id'                   => '0',
                'timeperiod_id'                             => '0',
                'check_interval'                            => '60',
                'retry_interval'                            => '60',
                'max_check_attempts'                        => '3',
                'first_notification_delay'                  => '0',
                'notification_interval'                     => '7200',
                'notify_on_warning'                         => '1',
                'notify_on_unknown'                         => '1',
                'notify_on_critical'                        => '1',
                'notify_on_recovery'                        => '1',
                'notify_on_flapping'                        => '0',
                'notify_on_downtime'                        => '0',
                'flap_detection_enabled'                    => '0',
                'flap_detection_on_ok'                      => '0',
                'flap_detection_on_warning'                 => '0',
                'flap_detection_on_unknown'                 => '0',
                'flap_detection_on_critical'                => '0',
                'low_flap_threshold'                        => '0',
                'high_flap_threshold'                       => '0',
                'process_performance_data'                  => '1',
                'freshness_checks_enabled'                  => '0',
                'freshness_threshold'                       => null,
                'passive_checks_enabled'                    => '1',
                'event_handler_enabled'                     => '0',
                'active_checks_enabled'                     => '1',
                'retain_status_information'                 => '0',
                'retain_nonstatus_information'              => '0',
                'notifications_enabled'                     => '0',
                'notes'                                     => '',
                'priority'                                  => '1',
                'tags'                                      => '',
                'service_url'                               => '',
                'is_volatile'                               => '0',
                'check_freshness'                           => '0',
                'servicetemplateeventcommandargumentvalues' => [],
                'servicetemplatecommandargumentvalues'      => [],
                'customvariables'                           => [],
                'servicegroups'                             => [],
                'contactgroups'                             => [],
                'contacts'                                  => [
                    '_ids' => [
                        '1'
                    ]
                ]
            ],

            [
                'uuid'                                      => 'be4c9649-8771-4704-b409-c56b5f67abc8',
                'template_name'                             => 'OITC_AGENT_CPU_TOTAL_PERCENTAGE',
                'name'                                      => 'Total CPU Percentage',
                'container_id'                              => ROOT_CONTAINER,
                'servicetemplatetype_id'                    => OITC_AGENT_SERVICE,
                'check_period_id'                           => '1',
                'notify_period_id'                          => '1',
                'description'                               => '',
                'command_id'                                => '27551d06-610d-4331-af2d-aab77c45bd36',
                'check_command_args'                        => '',
                'checkcommand_info'                         => '',
                'eventhandler_command_id'                   => '0',
                'timeperiod_id'                             => '0',
                'check_interval'                            => '300',
                'retry_interval'                            => '60',
                'max_check_attempts'                        => '3',
                'first_notification_delay'                  => '0',
                'notification_interval'                     => '7200',
                'notify_on_warning'                         => '1',
                'notify_on_unknown'                         => '1',
                'notify_on_critical'                        => '1',
                'notify_on_recovery'                        => '1',
                'notify_on_flapping'                        => '0',
                'notify_on_downtime'                        => '0',
                'flap_detection_enabled'                    => '0',
                'flap_detection_on_ok'                      => '0',
                'flap_detection_on_warning'                 => '0',
                'flap_detection_on_unknown'                 => '0',
                'flap_detection_on_critical'                => '0',
                'low_flap_threshold'                        => '0',
                'high_flap_threshold'                       => '0',
                'process_performance_data'                  => '1',
                'freshness_checks_enabled'                  => '0',
                'freshness_threshold'                       => null,
                'passive_checks_enabled'                    => '1',
                'event_handler_enabled'                     => '0',
                'active_checks_enabled'                     => '0',
                'retain_status_information'                 => '0',
                'retain_nonstatus_information'              => '0',
                'notifications_enabled'                     => '0',
                'notes'                                     => '',
                'priority'                                  => '1',
                'tags'                                      => '',
                'service_url'                               => '',
                'is_volatile'                               => '0',
                'check_freshness'                           => '0',
                'servicetemplateeventcommandargumentvalues' => [],
                'servicetemplatecommandargumentvalues'      => [
                    [
                        'commandargument_id' => '$ARG1$',
                        'value'              => '90',
                    ],
                    [
                        'commandargument_id' => '$ARG2$',
                        'value'              => '95',
                    ],
                    [
                        'commandargument_id' => '$ARG3$',
                        'value'              => '0',
                    ]
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [
                    '_ids' => [
                        '1'
                    ]
                ],
                'contactgroups'                             => [],
                'contacts'                                  => [
                    '_ids' => [
                        '1'
                    ]
                ]
            ],

            [
                'uuid'                                      => '566a710f-a554-4fa6-b2a2-e46b1d937a64',
                'template_name'                             => 'OITC_AGENT_SYSTEM_LOAD',
                'name'                                      => 'System Load',
                'container_id'                              => ROOT_CONTAINER,
                'servicetemplatetype_id'                    => OITC_AGENT_SERVICE,
                'check_period_id'                           => '1',
                'notify_period_id'                          => '1',
                'description'                               => '',
                'command_id'                                => 'd4e55865-5cbc-4f01-a4e3-896074a1d7d1',
                'check_command_args'                        => '',
                'checkcommand_info'                         => '',
                'eventhandler_command_id'                   => '0',
                'timeperiod_id'                             => '0',
                'check_interval'                            => '300',
                'retry_interval'                            => '60',
                'max_check_attempts'                        => '3',
                'first_notification_delay'                  => '0',
                'notification_interval'                     => '7200',
                'notify_on_warning'                         => '1',
                'notify_on_unknown'                         => '1',
                'notify_on_critical'                        => '1',
                'notify_on_recovery'                        => '1',
                'notify_on_flapping'                        => '0',
                'notify_on_downtime'                        => '0',
                'flap_detection_enabled'                    => '0',
                'flap_detection_on_ok'                      => '0',
                'flap_detection_on_warning'                 => '0',
                'flap_detection_on_unknown'                 => '0',
                'flap_detection_on_critical'                => '0',
                'low_flap_threshold'                        => '0',
                'high_flap_threshold'                       => '0',
                'process_performance_data'                  => '1',
                'freshness_checks_enabled'                  => '0',
                'freshness_threshold'                       => null,
                'passive_checks_enabled'                    => '1',
                'event_handler_enabled'                     => '0',
                'active_checks_enabled'                     => '0',
                'retain_status_information'                 => '0',
                'retain_nonstatus_information'              => '0',
                'notifications_enabled'                     => '0',
                'notes'                                     => '',
                'priority'                                  => '1',
                'tags'                                      => '',
                'service_url'                               => '',
                'is_volatile'                               => '0',
                'check_freshness'                           => '0',
                'servicetemplateeventcommandargumentvalues' => [],
                'servicetemplatecommandargumentvalues'      => [
                    [
                        'commandargument_id' => '$ARG1$',
                        'value'              => '24',
                    ],
                    [
                        'commandargument_id' => '$ARG2$',
                        'value'              => '32',
                    ],
                    [
                        'commandargument_id' => '$ARG3$',
                        'value'              => '5',
                    ]
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [
                    '_ids' => [
                        '1'
                    ]
                ],
                'contactgroups'                             => [],
                'contacts'                                  => [
                    '_ids' => [
                        '1'
                    ]
                ]
            ],

            [
                'uuid'                                      => '4052fe4f-50b1-443a-8be1-9dbca8d43ebd',
                'template_name'                             => 'OITC_AGENT_MEMORY_USAGE',
                'name'                                      => 'Memory Usage',
                'container_id'                              => ROOT_CONTAINER,
                'servicetemplatetype_id'                    => OITC_AGENT_SERVICE,
                'check_period_id'                           => '1',
                'notify_period_id'                          => '1',
                'description'                               => '',
                'command_id'                                => 'ab3fe6f2-b5c6-4ba0-9d72-123696d42277',
                'check_command_args'                        => '',
                'checkcommand_info'                         => '',
                'eventhandler_command_id'                   => '0',
                'timeperiod_id'                             => '0',
                'check_interval'                            => '300',
                'retry_interval'                            => '60',
                'max_check_attempts'                        => '3',
                'first_notification_delay'                  => '0',
                'notification_interval'                     => '7200',
                'notify_on_warning'                         => '1',
                'notify_on_unknown'                         => '1',
                'notify_on_critical'                        => '1',
                'notify_on_recovery'                        => '1',
                'notify_on_flapping'                        => '0',
                'notify_on_downtime'                        => '0',
                'flap_detection_enabled'                    => '0',
                'flap_detection_on_ok'                      => '0',
                'flap_detection_on_warning'                 => '0',
                'flap_detection_on_unknown'                 => '0',
                'flap_detection_on_critical'                => '0',
                'low_flap_threshold'                        => '0',
                'high_flap_threshold'                       => '0',
                'process_performance_data'                  => '1',
                'freshness_checks_enabled'                  => '0',
                'freshness_threshold'                       => null,
                'passive_checks_enabled'                    => '1',
                'event_handler_enabled'                     => '0',
                'active_checks_enabled'                     => '0',
                'retain_status_information'                 => '0',
                'retain_nonstatus_information'              => '0',
                'notifications_enabled'                     => '0',
                'notes'                                     => '',
                'priority'                                  => '1',
                'tags'                                      => '',
                'service_url'                               => '',
                'is_volatile'                               => '0',
                'check_freshness'                           => '0',
                'servicetemplateeventcommandargumentvalues' => [],
                'servicetemplatecommandargumentvalues'      => [
                    [
                        'commandargument_id' => '$ARG1$',
                        'value'              => '80',
                    ],
                    [
                        'commandargument_id' => '$ARG2$',
                        'value'              => '90',
                    ],
                    [
                        'commandargument_id' => '$ARG5$',
                        'value'              => '%',
                    ]
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [
                    '_ids' => [
                        '1'
                    ]
                ],
                'contactgroups'                             => [],
                'contacts'                                  => [
                    '_ids' => [
                        '1'
                    ]
                ]
            ],

            [
                'uuid'                                      => 'f9d5e18a-8894-4324-b54a-497db87b6f4f',
                'template_name'                             => 'OITC_AGENT_SWAP_USAGE',
                'name'                                      => 'SWAP Usage',
                'container_id'                              => ROOT_CONTAINER,
                'servicetemplatetype_id'                    => OITC_AGENT_SERVICE,
                'check_period_id'                           => '1',
                'notify_period_id'                          => '1',
                'description'                               => '',
                'command_id'                                => 'ebff0db2-e5de-4430-8799-3c6662422646',
                'check_command_args'                        => '',
                'checkcommand_info'                         => '',
                'eventhandler_command_id'                   => '0',
                'timeperiod_id'                             => '0',
                'check_interval'                            => '300',
                'retry_interval'                            => '60',
                'max_check_attempts'                        => '3',
                'first_notification_delay'                  => '0',
                'notification_interval'                     => '7200',
                'notify_on_warning'                         => '1',
                'notify_on_unknown'                         => '1',
                'notify_on_critical'                        => '1',
                'notify_on_recovery'                        => '1',
                'notify_on_flapping'                        => '0',
                'notify_on_downtime'                        => '0',
                'flap_detection_enabled'                    => '0',
                'flap_detection_on_ok'                      => '0',
                'flap_detection_on_warning'                 => '0',
                'flap_detection_on_unknown'                 => '0',
                'flap_detection_on_critical'                => '0',
                'low_flap_threshold'                        => '0',
                'high_flap_threshold'                       => '0',
                'process_performance_data'                  => '1',
                'freshness_checks_enabled'                  => '0',
                'freshness_threshold'                       => null,
                'passive_checks_enabled'                    => '1',
                'event_handler_enabled'                     => '0',
                'active_checks_enabled'                     => '0',
                'retain_status_information'                 => '0',
                'retain_nonstatus_information'              => '0',
                'notifications_enabled'                     => '0',
                'notes'                                     => '',
                'priority'                                  => '1',
                'tags'                                      => '',
                'service_url'                               => '',
                'is_volatile'                               => '0',
                'check_freshness'                           => '0',
                'servicetemplateeventcommandargumentvalues' => [],
                'servicetemplatecommandargumentvalues'      => [
                    [
                        'commandargument_id' => '$ARG1$',
                        'value'              => '40',
                    ],
                    [
                        'commandargument_id' => '$ARG2$',
                        'value'              => '80',
                    ]
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [
                    '_ids' => [
                        '1'
                    ]
                ],
                'contactgroups'                             => [],
                'contacts'                                  => [
                    '_ids' => [
                        '1'
                    ]
                ]
            ],

            [
                'uuid'                                      => '68fb72a3-5bf5-4d97-8690-91242628659b',
                'template_name'                             => 'OITC_AGENT_DISK_IO',
                'name'                                      => 'Disk Load',
                'container_id'                              => ROOT_CONTAINER,
                'servicetemplatetype_id'                    => OITC_AGENT_SERVICE,
                'check_period_id'                           => '1',
                'notify_period_id'                          => '1',
                'description'                               => '',
                'command_id'                                => 'ffd0a58f-01e0-4a4d-8db9-a3107a9e765f',
                'check_command_args'                        => '',
                'checkcommand_info'                         => '',
                'eventhandler_command_id'                   => '0',
                'timeperiod_id'                             => '0',
                'check_interval'                            => '300',
                'retry_interval'                            => '60',
                'max_check_attempts'                        => '3',
                'first_notification_delay'                  => '0',
                'notification_interval'                     => '7200',
                'notify_on_warning'                         => '1',
                'notify_on_unknown'                         => '1',
                'notify_on_critical'                        => '1',
                'notify_on_recovery'                        => '1',
                'notify_on_flapping'                        => '0',
                'notify_on_downtime'                        => '0',
                'flap_detection_enabled'                    => '0',
                'flap_detection_on_ok'                      => '0',
                'flap_detection_on_warning'                 => '0',
                'flap_detection_on_unknown'                 => '0',
                'flap_detection_on_critical'                => '0',
                'low_flap_threshold'                        => '0',
                'high_flap_threshold'                       => '0',
                'process_performance_data'                  => '1',
                'freshness_checks_enabled'                  => '0',
                'freshness_threshold'                       => null,
                'passive_checks_enabled'                    => '1',
                'event_handler_enabled'                     => '0',
                'active_checks_enabled'                     => '0',
                'retain_status_information'                 => '0',
                'retain_nonstatus_information'              => '0',
                'notifications_enabled'                     => '0',
                'notes'                                     => '',
                'priority'                                  => '1',
                'tags'                                      => '',
                'service_url'                               => '',
                'is_volatile'                               => '0',
                'check_freshness'                           => '0',
                'servicetemplateeventcommandargumentvalues' => [],
                'servicetemplatecommandargumentvalues'      => [
                    [
                        'commandargument_id' => '$ARG1$',
                        'value'              => '60',
                    ],
                    [
                        'commandargument_id' => '$ARG2$',
                        'value'              => '80',
                    ]
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [
                    '_ids' => [
                        '1'
                    ]
                ],
                'contactgroups'                             => [],
                'contacts'                                  => [
                    '_ids' => [
                        '1'
                    ]
                ]
            ],

            [
                'uuid'                                      => '24851d0d-32fa-4048-bd67-6a30d710bba1',
                'template_name'                             => 'OITC_AGENT_DISK_USAGE',
                'name'                                      => 'Disk Usage',
                'container_id'                              => ROOT_CONTAINER,
                'servicetemplatetype_id'                    => OITC_AGENT_SERVICE,
                'check_period_id'                           => '1',
                'notify_period_id'                          => '1',
                'description'                               => '',
                'command_id'                                => '3830078a-ccc5-41fd-ae33-8369bca1a6b7',
                'check_command_args'                        => '',
                'checkcommand_info'                         => '',
                'eventhandler_command_id'                   => '0',
                'timeperiod_id'                             => '0',
                'check_interval'                            => '300',
                'retry_interval'                            => '60',
                'max_check_attempts'                        => '3',
                'first_notification_delay'                  => '0',
                'notification_interval'                     => '7200',
                'notify_on_warning'                         => '1',
                'notify_on_unknown'                         => '1',
                'notify_on_critical'                        => '1',
                'notify_on_recovery'                        => '1',
                'notify_on_flapping'                        => '0',
                'notify_on_downtime'                        => '0',
                'flap_detection_enabled'                    => '0',
                'flap_detection_on_ok'                      => '0',
                'flap_detection_on_warning'                 => '0',
                'flap_detection_on_unknown'                 => '0',
                'flap_detection_on_critical'                => '0',
                'low_flap_threshold'                        => '0',
                'high_flap_threshold'                       => '0',
                'process_performance_data'                  => '1',
                'freshness_checks_enabled'                  => '0',
                'freshness_threshold'                       => null,
                'passive_checks_enabled'                    => '1',
                'event_handler_enabled'                     => '0',
                'active_checks_enabled'                     => '0',
                'retain_status_information'                 => '0',
                'retain_nonstatus_information'              => '0',
                'notifications_enabled'                     => '0',
                'notes'                                     => '',
                'priority'                                  => '1',
                'tags'                                      => '',
                'service_url'                               => '',
                'is_volatile'                               => '0',
                'check_freshness'                           => '0',
                'servicetemplateeventcommandargumentvalues' => [],
                'servicetemplatecommandargumentvalues'      => [
                    [
                        'commandargument_id' => '$ARG1$',
                        'value'              => '80',
                    ],
                    [
                        'commandargument_id' => '$ARG2$',
                        'value'              => '90',
                    ],
                    [
                        'commandargument_id' => '$ARG3$',
                        'value'              => '/',
                    ],
                    [
                        'commandargument_id' => '$ARG4$',
                        'value'              => '1',
                    ]
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [
                    '_ids' => [
                        '1'
                    ]
                ],
                'contactgroups'                             => [],
                'contacts'                                  => [
                    '_ids' => [
                        '1'
                    ]
                ]
            ],

            [
                'uuid'                                      => '3e0bd59e-822d-47ed-a5b2-15f1e53fe043',
                'template_name'                             => 'OITC_AGENT_FAN_SPEED',
                'name'                                      => 'Fan Speed',
                'container_id'                              => ROOT_CONTAINER,
                'servicetemplatetype_id'                    => OITC_AGENT_SERVICE,
                'check_period_id'                           => '1',
                'notify_period_id'                          => '1',
                'description'                               => '',
                'command_id'                                => '7bee1594-b029-4db1-8059-694a75b4f83e',
                'check_command_args'                        => '',
                'checkcommand_info'                         => '',
                'eventhandler_command_id'                   => '0',
                'timeperiod_id'                             => '0',
                'check_interval'                            => '300',
                'retry_interval'                            => '60',
                'max_check_attempts'                        => '3',
                'first_notification_delay'                  => '0',
                'notification_interval'                     => '7200',
                'notify_on_warning'                         => '1',
                'notify_on_unknown'                         => '1',
                'notify_on_critical'                        => '1',
                'notify_on_recovery'                        => '1',
                'notify_on_flapping'                        => '0',
                'notify_on_downtime'                        => '0',
                'flap_detection_enabled'                    => '0',
                'flap_detection_on_ok'                      => '0',
                'flap_detection_on_warning'                 => '0',
                'flap_detection_on_unknown'                 => '0',
                'flap_detection_on_critical'                => '0',
                'low_flap_threshold'                        => '0',
                'high_flap_threshold'                       => '0',
                'process_performance_data'                  => '1',
                'freshness_checks_enabled'                  => '0',
                'freshness_threshold'                       => null,
                'passive_checks_enabled'                    => '1',
                'event_handler_enabled'                     => '0',
                'active_checks_enabled'                     => '0',
                'retain_status_information'                 => '0',
                'retain_nonstatus_information'              => '0',
                'notifications_enabled'                     => '0',
                'notes'                                     => '',
                'priority'                                  => '1',
                'tags'                                      => '',
                'service_url'                               => '',
                'is_volatile'                               => '0',
                'check_freshness'                           => '0',
                'servicetemplateeventcommandargumentvalues' => [],
                'servicetemplatecommandargumentvalues'      => [
                    [
                        'commandargument_id' => '$ARG1$',
                        'value'              => '2900',
                    ],
                    [
                        'commandargument_id' => '$ARG2$',
                        'value'              => '3300',
                    ],
                    [
                        'commandargument_id' => '$ARG3$',
                        'value'              => 'thinkpad',
                    ]
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [
                    '_ids' => [
                        '1'
                    ]
                ],
                'contactgroups'                             => [],
                'contacts'                                  => [
                    '_ids' => [
                        '1'
                    ]
                ]
            ],

            [
                'uuid'                                      => 'f73dc076-bdb0-4302-9776-88ca1ba79364',
                'template_name'                             => 'OITC_AGENT_DEVICE_TEMPERATURE',
                'name'                                      => 'Device Temperature',
                'container_id'                              => ROOT_CONTAINER,
                'servicetemplatetype_id'                    => OITC_AGENT_SERVICE,
                'check_period_id'                           => '1',
                'notify_period_id'                          => '1',
                'description'                               => '',
                'command_id'                                => '42e3e6c9-a989-4044-a993-193558ad7964',
                'check_command_args'                        => '',
                'checkcommand_info'                         => '',
                'eventhandler_command_id'                   => '0',
                'timeperiod_id'                             => '0',
                'check_interval'                            => '300',
                'retry_interval'                            => '60',
                'max_check_attempts'                        => '3',
                'first_notification_delay'                  => '0',
                'notification_interval'                     => '7200',
                'notify_on_warning'                         => '1',
                'notify_on_unknown'                         => '1',
                'notify_on_critical'                        => '1',
                'notify_on_recovery'                        => '1',
                'notify_on_flapping'                        => '0',
                'notify_on_downtime'                        => '0',
                'flap_detection_enabled'                    => '0',
                'flap_detection_on_ok'                      => '0',
                'flap_detection_on_warning'                 => '0',
                'flap_detection_on_unknown'                 => '0',
                'flap_detection_on_critical'                => '0',
                'low_flap_threshold'                        => '0',
                'high_flap_threshold'                       => '0',
                'process_performance_data'                  => '1',
                'freshness_checks_enabled'                  => '0',
                'freshness_threshold'                       => null,
                'passive_checks_enabled'                    => '1',
                'event_handler_enabled'                     => '0',
                'active_checks_enabled'                     => '0',
                'retain_status_information'                 => '0',
                'retain_nonstatus_information'              => '0',
                'notifications_enabled'                     => '0',
                'notes'                                     => '',
                'priority'                                  => '1',
                'tags'                                      => '',
                'service_url'                               => '',
                'is_volatile'                               => '0',
                'check_freshness'                           => '0',
                'servicetemplateeventcommandargumentvalues' => [],
                'servicetemplatecommandargumentvalues'      => [
                    [
                        'commandargument_id' => '$ARG1$',
                        'value'              => '100',
                    ],
                    [
                        'commandargument_id' => '$ARG2$',
                        'value'              => '110',
                    ],
                    [
                        'commandargument_id' => '$ARG3$',
                        'value'              => '1',
                    ],
                    [
                        'commandargument_id' => '$ARG4$',
                        'value'              => 'coretemp',
                    ]
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [
                    '_ids' => [
                        '1'
                    ]
                ],
                'contactgroups'                             => [],
                'contacts'                                  => [
                    '_ids' => [
                        '1'
                    ]
                ]
            ],

            [
                'uuid'                                      => '21057a75-57a1-4972-8f2f-073c8a6000b0',
                'template_name'                             => 'OITC_AGENT_BATTERY_LEVEL',
                'name'                                      => 'Battery Level',
                'container_id'                              => ROOT_CONTAINER,
                'servicetemplatetype_id'                    => OITC_AGENT_SERVICE,
                'check_period_id'                           => '1',
                'notify_period_id'                          => '1',
                'description'                               => '',
                'command_id'                                => '02289d0e-0a3c-46e5-94f1-b18e57be4e70',
                'check_command_args'                        => '',
                'checkcommand_info'                         => '',
                'eventhandler_command_id'                   => '0',
                'timeperiod_id'                             => '0',
                'check_interval'                            => '300',
                'retry_interval'                            => '60',
                'max_check_attempts'                        => '3',
                'first_notification_delay'                  => '0',
                'notification_interval'                     => '7200',
                'notify_on_warning'                         => '1',
                'notify_on_unknown'                         => '1',
                'notify_on_critical'                        => '1',
                'notify_on_recovery'                        => '1',
                'notify_on_flapping'                        => '0',
                'notify_on_downtime'                        => '0',
                'flap_detection_enabled'                    => '0',
                'flap_detection_on_ok'                      => '0',
                'flap_detection_on_warning'                 => '0',
                'flap_detection_on_unknown'                 => '0',
                'flap_detection_on_critical'                => '0',
                'low_flap_threshold'                        => '0',
                'high_flap_threshold'                       => '0',
                'process_performance_data'                  => '1',
                'freshness_checks_enabled'                  => '0',
                'freshness_threshold'                       => null,
                'passive_checks_enabled'                    => '1',
                'event_handler_enabled'                     => '0',
                'active_checks_enabled'                     => '0',
                'retain_status_information'                 => '0',
                'retain_nonstatus_information'              => '0',
                'notifications_enabled'                     => '0',
                'notes'                                     => '',
                'priority'                                  => '1',
                'tags'                                      => '',
                'service_url'                               => '',
                'is_volatile'                               => '0',
                'check_freshness'                           => '0',
                'servicetemplateeventcommandargumentvalues' => [],
                'servicetemplatecommandargumentvalues'      => [
                    [
                        'commandargument_id' => '$ARG1$',
                        'value'              => '@35',
                    ],
                    [
                        'commandargument_id' => '$ARG2$',
                        'value'              => '@20',
                    ]
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [
                    '_ids' => [
                        '1'
                    ]
                ],
                'contactgroups'                             => [],
                'contacts'                                  => [
                    '_ids' => [
                        '1'
                    ]
                ]
            ],

            [
                'uuid'                                      => 'e5d848f5-a323-4bfe-9ec5-1c5cdf138abf',
                'template_name'                             => 'OITC_AGENT_NET_IO',
                'name'                                      => 'Network Stats',
                'container_id'                              => ROOT_CONTAINER,
                'servicetemplatetype_id'                    => OITC_AGENT_SERVICE,
                'check_period_id'                           => '1',
                'notify_period_id'                          => '1',
                'description'                               => '',
                'command_id'                                => '4186bc96-0081-48fc-bee6-0ff163c9f4a2',
                'check_command_args'                        => '',
                'checkcommand_info'                         => '',
                'eventhandler_command_id'                   => '0',
                'timeperiod_id'                             => '0',
                'check_interval'                            => '300',
                'retry_interval'                            => '60',
                'max_check_attempts'                        => '3',
                'first_notification_delay'                  => '0',
                'notification_interval'                     => '7200',
                'notify_on_warning'                         => '1',
                'notify_on_unknown'                         => '1',
                'notify_on_critical'                        => '1',
                'notify_on_recovery'                        => '1',
                'notify_on_flapping'                        => '0',
                'notify_on_downtime'                        => '0',
                'flap_detection_enabled'                    => '0',
                'flap_detection_on_ok'                      => '0',
                'flap_detection_on_warning'                 => '0',
                'flap_detection_on_unknown'                 => '0',
                'flap_detection_on_critical'                => '0',
                'low_flap_threshold'                        => '0',
                'high_flap_threshold'                       => '0',
                'process_performance_data'                  => '1',
                'freshness_checks_enabled'                  => '0',
                'freshness_threshold'                       => null,
                'passive_checks_enabled'                    => '1',
                'event_handler_enabled'                     => '0',
                'active_checks_enabled'                     => '0',
                'retain_status_information'                 => '0',
                'retain_nonstatus_information'              => '0',
                'notifications_enabled'                     => '0',
                'notes'                                     => '',
                'priority'                                  => '1',
                'tags'                                      => '',
                'service_url'                               => '',
                'is_volatile'                               => '0',
                'check_freshness'                           => '0',
                'servicetemplateeventcommandargumentvalues' => [],
                'servicetemplatecommandargumentvalues'      => [
                    [
                        'commandargument_id' => '$ARG1$',
                        'value'              => '',
                    ],
                    [
                        'commandargument_id' => '$ARG2$',
                        'value'              => '',
                    ],
                    [
                        'commandargument_id' => '$ARG3$',
                        'value'              => '5',
                    ],
                    [
                        'commandargument_id' => '$ARG4$',
                        'value'              => '10',
                    ],
                    [
                        'commandargument_id' => '$ARG5$',
                        'value'              => '5',
                    ],
                    [
                        'commandargument_id' => '$ARG6$',
                        'value'              => '10',
                    ],
                    [
                        'commandargument_id' => '$ARG7$',
                        'value'              => '',
                    ],
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [
                    '_ids' => [
                        '1'
                    ]
                ],
                'contactgroups'                             => [],
                'contacts'                                  => [
                    '_ids' => [
                        '1'
                    ]
                ]
            ],

            [
                'uuid'                                      => 'f6f64207-ef75-4a3d-b9f2-2eaab398a6f1',
                'template_name'                             => 'OITC_AGENT_NET_DEVICE_STATS',
                'name'                                      => 'Network Device Status',
                'container_id'                              => ROOT_CONTAINER,
                'servicetemplatetype_id'                    => OITC_AGENT_SERVICE,
                'check_period_id'                           => '1',
                'notify_period_id'                          => '1',
                'description'                               => '',
                'command_id'                                => '2c96d1c9-de93-4bfa-ab69-2b45bf40d2e3',
                'check_command_args'                        => '',
                'checkcommand_info'                         => '',
                'eventhandler_command_id'                   => '0',
                'timeperiod_id'                             => '0',
                'check_interval'                            => '300',
                'retry_interval'                            => '60',
                'max_check_attempts'                        => '3',
                'first_notification_delay'                  => '0',
                'notification_interval'                     => '7200',
                'notify_on_warning'                         => '1',
                'notify_on_unknown'                         => '1',
                'notify_on_critical'                        => '1',
                'notify_on_recovery'                        => '1',
                'notify_on_flapping'                        => '0',
                'notify_on_downtime'                        => '0',
                'flap_detection_enabled'                    => '0',
                'flap_detection_on_ok'                      => '0',
                'flap_detection_on_warning'                 => '0',
                'flap_detection_on_unknown'                 => '0',
                'flap_detection_on_critical'                => '0',
                'low_flap_threshold'                        => '0',
                'high_flap_threshold'                       => '0',
                'process_performance_data'                  => '1',
                'freshness_checks_enabled'                  => '0',
                'freshness_threshold'                       => null,
                'passive_checks_enabled'                    => '1',
                'event_handler_enabled'                     => '0',
                'active_checks_enabled'                     => '0',
                'retain_status_information'                 => '0',
                'retain_nonstatus_information'              => '0',
                'notifications_enabled'                     => '0',
                'notes'                                     => '',
                'priority'                                  => '1',
                'tags'                                      => '',
                'service_url'                               => '',
                'is_volatile'                               => '0',
                'check_freshness'                           => '0',
                'servicetemplateeventcommandargumentvalues' => [],
                'servicetemplatecommandargumentvalues'      => [
                    [
                        'commandargument_id' => '$ARG1$',
                        'value'              => 'critical',
                    ],
                    [
                        'commandargument_id' => '$ARG2$',
                        'value'              => 'eth0',
                    ]
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [
                    '_ids' => [
                        '1'
                    ]
                ],
                'contactgroups'                             => [],
                'contacts'                                  => [
                    '_ids' => [
                        '1'
                    ]
                ]
            ],

            [
                'uuid'                                      => '37a78eca-4a58-46cd-9fb1-6029724cab35',
                'template_name'                             => 'OITC_AGENT_PROCESSES',
                'name'                                      => 'Check Process',
                'container_id'                              => ROOT_CONTAINER,
                'servicetemplatetype_id'                    => OITC_AGENT_SERVICE,
                'check_period_id'                           => '1',
                'notify_period_id'                          => '1',
                'description'                               => '',
                'command_id'                                => '629050dd-1359-4c8d-9f13-fc49fdab84dc',
                'check_command_args'                        => '',
                'checkcommand_info'                         => '',
                'eventhandler_command_id'                   => '0',
                'timeperiod_id'                             => '0',
                'check_interval'                            => '300',
                'retry_interval'                            => '60',
                'max_check_attempts'                        => '3',
                'first_notification_delay'                  => '0',
                'notification_interval'                     => '7200',
                'notify_on_warning'                         => '1',
                'notify_on_unknown'                         => '1',
                'notify_on_critical'                        => '1',
                'notify_on_recovery'                        => '1',
                'notify_on_flapping'                        => '0',
                'notify_on_downtime'                        => '0',
                'flap_detection_enabled'                    => '0',
                'flap_detection_on_ok'                      => '0',
                'flap_detection_on_warning'                 => '0',
                'flap_detection_on_unknown'                 => '0',
                'flap_detection_on_critical'                => '0',
                'low_flap_threshold'                        => '0',
                'high_flap_threshold'                       => '0',
                'process_performance_data'                  => '1',
                'freshness_checks_enabled'                  => '0',
                'freshness_threshold'                       => null,
                'passive_checks_enabled'                    => '1',
                'event_handler_enabled'                     => '0',
                'active_checks_enabled'                     => '0',
                'retain_status_information'                 => '0',
                'retain_nonstatus_information'              => '0',
                'notifications_enabled'                     => '0',
                'notes'                                     => '',
                'priority'                                  => '1',
                'tags'                                      => '',
                'service_url'                               => '',
                'is_volatile'                               => '0',
                'check_freshness'                           => '0',
                'servicetemplateeventcommandargumentvalues' => [],
                'servicetemplatecommandargumentvalues'      => [
                    [
                        'commandargument_id' => '$ARG1$',
                        'value'              => '50',
                    ],
                    [
                        'commandargument_id' => '$ARG2$',
                        'value'              => '60',
                    ],
                    [
                        'commandargument_id' => '$ARG3$',
                        'value'              => '50',
                    ],
                    [
                        'commandargument_id' => '$ARG4$',
                        'value'              => '60',
                    ],
                    [
                        'commandargument_id' => '$ARG5$',
                        'value'              => '',
                    ],
                    [
                        'commandargument_id' => '$ARG6$',
                        'value'              => '1:1',
                    ],
                    [
                        'commandargument_id' => '$ARG7$',
                        'value'              => '',
                    ],
                    [
                        'commandargument_id' => '$ARG8$',
                        'value'              => '0',
                    ],
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [
                    '_ids' => [
                        '1'
                    ]
                ],
                'contactgroups'                             => [],
                'contacts'                                  => [
                    '_ids' => [
                        '1'
                    ]
                ]
            ],

            [
                'uuid'                                      => '370731ed-34d4-48f4-933e-90e488bb390f',
                'template_name'                             => 'OITC_AGENT_WINDOWS_SERVICES',
                'name'                                      => 'Check windows service running',
                'container_id'                              => ROOT_CONTAINER,
                'servicetemplatetype_id'                    => OITC_AGENT_SERVICE,
                'check_period_id'                           => '1',
                'notify_period_id'                          => '1',
                'description'                               => '',
                'command_id'                                => '06bf6bc1-f228-4adc-ac14-5fe902349768',
                'check_command_args'                        => '',
                'checkcommand_info'                         => '',
                'eventhandler_command_id'                   => '0',
                'timeperiod_id'                             => '0',
                'check_interval'                            => '300',
                'retry_interval'                            => '60',
                'max_check_attempts'                        => '3',
                'first_notification_delay'                  => '0',
                'notification_interval'                     => '7200',
                'notify_on_warning'                         => '1',
                'notify_on_unknown'                         => '1',
                'notify_on_critical'                        => '1',
                'notify_on_recovery'                        => '1',
                'notify_on_flapping'                        => '0',
                'notify_on_downtime'                        => '0',
                'flap_detection_enabled'                    => '0',
                'flap_detection_on_ok'                      => '0',
                'flap_detection_on_warning'                 => '0',
                'flap_detection_on_unknown'                 => '0',
                'flap_detection_on_critical'                => '0',
                'low_flap_threshold'                        => '0',
                'high_flap_threshold'                       => '0',
                'process_performance_data'                  => '1',
                'freshness_checks_enabled'                  => '0',
                'freshness_threshold'                       => null,
                'passive_checks_enabled'                    => '1',
                'event_handler_enabled'                     => '0',
                'active_checks_enabled'                     => '0',
                'retain_status_information'                 => '0',
                'retain_nonstatus_information'              => '0',
                'notifications_enabled'                     => '0',
                'notes'                                     => '',
                'priority'                                  => '1',
                'tags'                                      => '',
                'service_url'                               => '',
                'is_volatile'                               => '0',
                'check_freshness'                           => '0',
                'servicetemplateeventcommandargumentvalues' => [],
                'servicetemplatecommandargumentvalues'      => [
                    [
                        'commandargument_id' => '$ARG1$',
                        'value'              => '',
                    ],
                    [
                        'commandargument_id' => '$ARG2$',
                        'value'              => '1:1',
                    ],
                    [
                        'commandargument_id' => '$ARG3$',
                        'value'              => '',
                    ],
                    [
                        'commandargument_id' => '$ARG4$',
                        'value'              => '0',
                    ],
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [
                    '_ids' => [
                        '1'
                    ]
                ],
                'contactgroups'                             => [],
                'contacts'                                  => [
                    '_ids' => [
                        '1'
                    ]
                ]
            ],

            [
                'uuid'                                      => 'ca73653f-2bba-4542-b11b-0bbd0ecc8b7a',
                'template_name'                             => 'OITC_AGENT_DOCKER_RUNNING',
                'name'                                      => 'Docker Container Running',
                'container_id'                              => ROOT_CONTAINER,
                'servicetemplatetype_id'                    => OITC_AGENT_SERVICE,
                'check_period_id'                           => '1',
                'notify_period_id'                          => '1',
                'description'                               => '',
                'command_id'                                => '577da91f-e70d-4218-bef1-c8b3b72d2ad5',
                'check_command_args'                        => '',
                'checkcommand_info'                         => '',
                'eventhandler_command_id'                   => '0',
                'timeperiod_id'                             => '0',
                'check_interval'                            => '300',
                'retry_interval'                            => '60',
                'max_check_attempts'                        => '3',
                'first_notification_delay'                  => '0',
                'notification_interval'                     => '7200',
                'notify_on_warning'                         => '1',
                'notify_on_unknown'                         => '1',
                'notify_on_critical'                        => '1',
                'notify_on_recovery'                        => '1',
                'notify_on_flapping'                        => '0',
                'notify_on_downtime'                        => '0',
                'flap_detection_enabled'                    => '0',
                'flap_detection_on_ok'                      => '0',
                'flap_detection_on_warning'                 => '0',
                'flap_detection_on_unknown'                 => '0',
                'flap_detection_on_critical'                => '0',
                'low_flap_threshold'                        => '0',
                'high_flap_threshold'                       => '0',
                'process_performance_data'                  => '1',
                'freshness_checks_enabled'                  => '0',
                'freshness_threshold'                       => null,
                'passive_checks_enabled'                    => '1',
                'event_handler_enabled'                     => '0',
                'active_checks_enabled'                     => '0',
                'retain_status_information'                 => '0',
                'retain_nonstatus_information'              => '0',
                'notifications_enabled'                     => '0',
                'notes'                                     => '',
                'priority'                                  => '1',
                'tags'                                      => '',
                'service_url'                               => '',
                'is_volatile'                               => '0',
                'check_freshness'                           => '0',
                'servicetemplateeventcommandargumentvalues' => [],
                'servicetemplatecommandargumentvalues'      => [
                    [
                        'commandargument_id' => '$ARG1$',
                        'value'              => 'id',
                    ],
                    [
                        'commandargument_id' => '$ARG2$',
                        'value'              => '44e1d03c....',
                    ]
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [
                    '_ids' => [
                        '1'
                    ]
                ],
                'contactgroups'                             => [],
                'contacts'                                  => [
                    '_ids' => [
                        '1'
                    ]
                ]
            ],

            [
                'uuid'                                      => 'a9f7757e-34b0-4df9-8fca-ab8b594c2c26',
                'template_name'                             => 'OITC_AGENT_DOCKER_CPU',
                'name'                                      => 'Docker Container CPU Percentage',
                'container_id'                              => ROOT_CONTAINER,
                'servicetemplatetype_id'                    => OITC_AGENT_SERVICE,
                'check_period_id'                           => '1',
                'notify_period_id'                          => '1',
                'description'                               => '',
                'command_id'                                => '198c52aa-28c0-45d8-a22d-7df3dad0f716',
                'check_command_args'                        => '',
                'checkcommand_info'                         => '',
                'eventhandler_command_id'                   => '0',
                'timeperiod_id'                             => '0',
                'check_interval'                            => '300',
                'retry_interval'                            => '60',
                'max_check_attempts'                        => '3',
                'first_notification_delay'                  => '0',
                'notification_interval'                     => '7200',
                'notify_on_warning'                         => '1',
                'notify_on_unknown'                         => '1',
                'notify_on_critical'                        => '1',
                'notify_on_recovery'                        => '1',
                'notify_on_flapping'                        => '0',
                'notify_on_downtime'                        => '0',
                'flap_detection_enabled'                    => '0',
                'flap_detection_on_ok'                      => '0',
                'flap_detection_on_warning'                 => '0',
                'flap_detection_on_unknown'                 => '0',
                'flap_detection_on_critical'                => '0',
                'low_flap_threshold'                        => '0',
                'high_flap_threshold'                       => '0',
                'process_performance_data'                  => '1',
                'freshness_checks_enabled'                  => '0',
                'freshness_threshold'                       => null,
                'passive_checks_enabled'                    => '1',
                'event_handler_enabled'                     => '0',
                'active_checks_enabled'                     => '0',
                'retain_status_information'                 => '0',
                'retain_nonstatus_information'              => '0',
                'notifications_enabled'                     => '0',
                'notes'                                     => '',
                'priority'                                  => '1',
                'tags'                                      => '',
                'service_url'                               => '',
                'is_volatile'                               => '0',
                'check_freshness'                           => '0',
                'servicetemplateeventcommandargumentvalues' => [],
                'servicetemplatecommandargumentvalues'      => [
                    [
                        'commandargument_id' => '$ARG1$',
                        'value'              => 'id',
                    ],
                    [
                        'commandargument_id' => '$ARG2$',
                        'value'              => '44e1d03c....',
                    ],
                    [
                        'commandargument_id' => '$ARG3$',
                        'value'              => '80',
                    ],
                    [
                        'commandargument_id' => '$ARG4$',
                        'value'              => '90',
                    ]
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [
                    '_ids' => [
                        '1'
                    ]
                ],
                'contactgroups'                             => [],
                'contacts'                                  => [
                    '_ids' => [
                        '1'
                    ]
                ]
            ],

            [
                'uuid'                                      => 'aef4c1a8-ed71-4799-a164-3ad469baadc5',
                'template_name'                             => 'OITC_AGENT_DOCKER_MEMORY',
                'name'                                      => 'Docker Container Memory Usage',
                'container_id'                              => ROOT_CONTAINER,
                'servicetemplatetype_id'                    => OITC_AGENT_SERVICE,
                'check_period_id'                           => '1',
                'notify_period_id'                          => '1',
                'description'                               => '',
                'command_id'                                => 'ba2b3392-a769-4b20-8ea2-10b60f2c74e8',
                'check_command_args'                        => '',
                'checkcommand_info'                         => '',
                'eventhandler_command_id'                   => '0',
                'timeperiod_id'                             => '0',
                'check_interval'                            => '300',
                'retry_interval'                            => '60',
                'max_check_attempts'                        => '3',
                'first_notification_delay'                  => '0',
                'notification_interval'                     => '7200',
                'notify_on_warning'                         => '1',
                'notify_on_unknown'                         => '1',
                'notify_on_critical'                        => '1',
                'notify_on_recovery'                        => '1',
                'notify_on_flapping'                        => '0',
                'notify_on_downtime'                        => '0',
                'flap_detection_enabled'                    => '0',
                'flap_detection_on_ok'                      => '0',
                'flap_detection_on_warning'                 => '0',
                'flap_detection_on_unknown'                 => '0',
                'flap_detection_on_critical'                => '0',
                'low_flap_threshold'                        => '0',
                'high_flap_threshold'                       => '0',
                'process_performance_data'                  => '1',
                'freshness_checks_enabled'                  => '0',
                'freshness_threshold'                       => null,
                'passive_checks_enabled'                    => '1',
                'event_handler_enabled'                     => '0',
                'active_checks_enabled'                     => '0',
                'retain_status_information'                 => '0',
                'retain_nonstatus_information'              => '0',
                'notifications_enabled'                     => '0',
                'notes'                                     => '',
                'priority'                                  => '1',
                'tags'                                      => '',
                'service_url'                               => '',
                'is_volatile'                               => '0',
                'check_freshness'                           => '0',
                'servicetemplateeventcommandargumentvalues' => [],
                'servicetemplatecommandargumentvalues'      => [
                    [
                        'commandargument_id' => '$ARG1$',
                        'value'              => 'id',
                    ],
                    [
                        'commandargument_id' => '$ARG2$',
                        'value'              => '44e1d03c....',
                    ],
                    [
                        'commandargument_id' => '$ARG3$',
                        'value'              => '50.0',
                    ],
                    [
                        'commandargument_id' => '$ARG4$',
                        'value'              => '80.5',
                    ],
                    [
                        'commandargument_id' => '$ARG5$',
                        'value'              => 'MiB',
                    ]
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [
                    '_ids' => [
                        '1'
                    ]
                ],
                'contactgroups'                             => [],
                'contacts'                                  => [
                    '_ids' => [
                        '1'
                    ]
                ]
            ],

            [
                'uuid'                                      => 'c1c8c77a-cecf-4a94-8418-a69081946ba0',
                'template_name'                             => 'OITC_AGENT_QEMU_VM_RUNNING',
                'name'                                      => 'QEMU VM Running',
                'container_id'                              => ROOT_CONTAINER,
                'servicetemplatetype_id'                    => OITC_AGENT_SERVICE,
                'check_period_id'                           => '1',
                'notify_period_id'                          => '1',
                'description'                               => '',
                'command_id'                                => '67ca5f1d-cc94-4bf1-8397-fc6e4abdbf92',
                'check_command_args'                        => '',
                'checkcommand_info'                         => '',
                'eventhandler_command_id'                   => '0',
                'timeperiod_id'                             => '0',
                'check_interval'                            => '300',
                'retry_interval'                            => '60',
                'max_check_attempts'                        => '3',
                'first_notification_delay'                  => '0',
                'notification_interval'                     => '7200',
                'notify_on_warning'                         => '1',
                'notify_on_unknown'                         => '1',
                'notify_on_critical'                        => '1',
                'notify_on_recovery'                        => '1',
                'notify_on_flapping'                        => '0',
                'notify_on_downtime'                        => '0',
                'flap_detection_enabled'                    => '0',
                'flap_detection_on_ok'                      => '0',
                'flap_detection_on_warning'                 => '0',
                'flap_detection_on_unknown'                 => '0',
                'flap_detection_on_critical'                => '0',
                'low_flap_threshold'                        => '0',
                'high_flap_threshold'                       => '0',
                'process_performance_data'                  => '1',
                'freshness_checks_enabled'                  => '0',
                'freshness_threshold'                       => null,
                'passive_checks_enabled'                    => '1',
                'event_handler_enabled'                     => '0',
                'active_checks_enabled'                     => '0',
                'retain_status_information'                 => '0',
                'retain_nonstatus_information'              => '0',
                'notifications_enabled'                     => '0',
                'notes'                                     => '',
                'priority'                                  => '1',
                'tags'                                      => '',
                'service_url'                               => '',
                'is_volatile'                               => '0',
                'check_freshness'                           => '0',
                'servicetemplateeventcommandargumentvalues' => [],
                'servicetemplatecommandargumentvalues'      => [
                    [
                        'commandargument_id' => '$ARG1$',
                        'value'              => 'id',
                    ],
                    [
                        'commandargument_id' => '$ARG2$',
                        'value'              => '100',
                    ]
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [
                    '_ids' => [
                        '1'
                    ]
                ],
                'contactgroups'                             => [],
                'contacts'                                  => [
                    '_ids' => [
                        '1'
                    ]
                ]
            ],

            [
                'uuid'                                      => '03b32b83-df7b-4204-a8bb-138a4939c554',
                'template_name'                             => 'OITC_AGENT_CUSTOMCHECK',
                'name'                                      => 'Custom check',
                'container_id'                              => ROOT_CONTAINER,
                'servicetemplatetype_id'                    => OITC_AGENT_SERVICE,
                'check_period_id'                           => '1',
                'notify_period_id'                          => '1',
                'description'                               => '',
                'command_id'                                => 'fbd23c8c-453d-4107-ae27-2cfafe63fef5',
                'check_command_args'                        => '',
                'checkcommand_info'                         => '',
                'eventhandler_command_id'                   => '0',
                'timeperiod_id'                             => '0',
                'check_interval'                            => '300',
                'retry_interval'                            => '60',
                'max_check_attempts'                        => '3',
                'first_notification_delay'                  => '0',
                'notification_interval'                     => '7200',
                'notify_on_warning'                         => '1',
                'notify_on_unknown'                         => '1',
                'notify_on_critical'                        => '1',
                'notify_on_recovery'                        => '1',
                'notify_on_flapping'                        => '0',
                'notify_on_downtime'                        => '0',
                'flap_detection_enabled'                    => '0',
                'flap_detection_on_ok'                      => '0',
                'flap_detection_on_warning'                 => '0',
                'flap_detection_on_unknown'                 => '0',
                'flap_detection_on_critical'                => '0',
                'low_flap_threshold'                        => '0',
                'high_flap_threshold'                       => '0',
                'process_performance_data'                  => '1',
                'freshness_checks_enabled'                  => '0',
                'freshness_threshold'                       => null,
                'passive_checks_enabled'                    => '1',
                'event_handler_enabled'                     => '0',
                'active_checks_enabled'                     => '0',
                'retain_status_information'                 => '0',
                'retain_nonstatus_information'              => '0',
                'notifications_enabled'                     => '0',
                'notes'                                     => '',
                'priority'                                  => '1',
                'tags'                                      => '',
                'service_url'                               => '',
                'is_volatile'                               => '0',
                'check_freshness'                           => '0',
                'servicetemplateeventcommandargumentvalues' => [],
                'servicetemplatecommandargumentvalues'      => [
                    [
                        'commandargument_id' => '$ARG1$',
                        'value'              => 'username',
                    ],
                    [
                        'commandargument_id' => '$ARG2$',
                        'value'              => 'whoami',
                    ],
                    [
                        'commandargument_id' => '$ARG3$',
                        'value'              => '30',
                    ],
                    [
                        'commandargument_id' => '$ARG4$',
                        'value'              => '5',
                    ]
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [
                    '_ids' => [
                        '1'
                    ]
                ],
                'contactgroups'                             => [],
                'contacts'                                  => [
                    '_ids' => [
                        '1'
                    ]
                ]
            ],
        ];
        return $data;
    }

    /**
     * @return array
     */
    public function getData() {
        return [
            'Commands'         => $this->getCommandsData(),
            'Servicetemplates' => $this->getServicetemplatesData(),
            'Agentchecks'      => $this->getAgentchecksData()
        ];
    }
}
