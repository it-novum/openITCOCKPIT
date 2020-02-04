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
     * Agent constructor.
     * @param CommandsTable $CommandsTable
     * @param ServicetemplatesTable $ServicetemplatesTable
     */
    public function __construct(CommandsTable $CommandsTable, ServicetemplatesTable $ServicetemplatesTable) {
        $this->CommandsTable = $CommandsTable;
        $this->ServicetemplatesTable = $ServicetemplatesTable;
    }

    /**
     * @return bool
     */
    public function import() {
        if ($this->isTableEmpty()) {
            $data = $this->getData();
            foreach ($data as $record) {
                $entity = $this->Table->newEntity($record);
                $this->Table->save($entity);
            }
        }

        return true;
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
                    "CPU: Warning and critical percentage values (0-100) of cpu usage for the matching process(es)\n" .
                    "Memory: Warning and critical percentage values (0-100) of memory usage for the matching process(es)\n" .
                    "Amount: Warning and critical values (e.g. 5 or 10) as amount of matching processes\n" .
                    "Match: String that must match with the process command line (use process 'cmdline' > 'exec' > 'name')\n" .
                    "Strict: Decides if the match must be completely or just in a part (1/0)\n",
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
                'name'             => 'check_oitc_agent_docker_running',
                'command_line'     => '$USER1$/check_dummy 3 "No data received from agent"',
                'command_type'     => CHECK_COMMAND,
                'human_args'       => null,
                'uuid'             => '577da91f-e70d-4218-bef1-c8b3b72d2ad5',
                'description'      => "Return the state of a docker container (ok/critical).\n" .
                    "Identifier Type: Values: name or id - Determines if the name of the id should be used to identify the container.\n" .
                    "Identifier:  Name or id of the container\n",
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
                'name'             => 'check_oitc_agent_qemu_vm_running',
                'command_line'     => '$USER1$/check_dummy 3 "No data received from agent"',
                'command_type'     => CHECK_COMMAND,
                'human_args'       => null,
                'uuid'             => '67ca5f1d-cc94-4bf1-8397-fc6e4abdbf92',
                'description'      => "Return the state of a qemu virtual machine (ok/critical).\n" .
                    "Identifier Type: Values: name or id - Determines if the name of the id should be used to identify the virtual machine.\n" .
                    "Identifier:  Name or id of the virtual machine\n",
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
                'description'      => "Commands that should be executed by the openITCOCKPIT Agent to replace check_by_ssh or check_nrpe.\n"
                    . "Name: Unique name of the command (e.g. check_users)\n" .
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

    /**
     * @return array
     */
    public function getData() {
        return [];
    }
}
