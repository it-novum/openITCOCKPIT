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
use App\Model\Table\CronjobsTable;
use App\Model\Table\ServicetemplatesTable;
use Cake\ORM\Table;

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
                'name'             => 'check_oitc_agent_docker_running',
                'command_line'     => '$USER1$/check_dummy 3 "No data received from agent"',
                'command_type'     => CHECK_COMMAND,
                'human_args'       => null,
                'uuid'             => '577da91f-e70d-4218-bef1-c8b3b72d2ad5',
                'description'      => "Return fan speed of a given device.\n" .
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
                'name'             => 'check_oitc_agent_net_io',
                'command_line'     => '$USER1$/check_dummy 3 "No data received from agent"',
                'command_type'     => CHECK_COMMAND,
                'human_args'       => null,
                'uuid'             => '4186bc96-0081-48fc-bee6-0ff163c9f4a2',
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
