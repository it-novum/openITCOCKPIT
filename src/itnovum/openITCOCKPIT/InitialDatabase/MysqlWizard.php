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
use App\Model\Table\WizardsTable;

/**
 * Class Cronjob
 * @package itnovum\openITCOCKPIT\InitialDatabase
 */
class MysqlWizard extends Importer {

    /**
     * @var CommandsTable
     */
    private $CommandsTable;

    /**
     * @var ServicetemplatesTable
     */
    private $ServicetemplatesTable;

    /**
     * @var WizardsTable
     */
    private $WizardsTable;

    /**
     * Agent constructor.
     * @param CommandsTable $CommandsTable
     * @param ServicetemplatesTable $ServicetemplatesTable
     * @param WizardsTable $WizardsTable
     */
    public function __construct(CommandsTable $CommandsTable, ServicetemplatesTable $ServicetemplatesTable, WizardsTable $WizardsTable) {
        $this->CommandsTable = $CommandsTable;
        $this->ServicetemplatesTable = $ServicetemplatesTable;
        $this->WizardsTable = $WizardsTable;
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

        /*
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
        */

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
                'name'               => 'systemd_services',
                'plugin_name'        => 'SystemdService',
                'servicetemplate_id' => 'd21e3462-7430-4d38-b92a-853bcfc12356'
            ],
            [
                'name'               => 'windows_eventlog',
                'plugin_name'        => 'WindowsEventlog',
                'servicetemplate_id' => 'dda2d5dc-7987-49a5-b4c2-0540e8aaf45e'
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
            [
                'name'               => 'alfrescostats',
                'plugin_name'        => 'Alfresco',
                'servicetemplate_id' => 'e453767c-b216-4b17-a012-9bc037d7da49'
            ],
        ];
        return $data;
    }

    public function getCommandsData() {
        $data = [
            /* connection-time in seconds*/
            [
                'name'             => 'check_mysql_health__connection-time',
                'command_line'     => '$USER1$/check_mysql_health --hostname $HOSTNAME$ --username $_SERVICEMYSQL_USER$ --password $_SERVICEMYSQL_PASS$ --mode connection-time --units seconds --warning $ARG1$ --critical $ARG2$',
                'command_type'     => CHECK_COMMAND,
                'human_args'       => null,
                'uuid'             => 'f503de41-54e2-40e9-9c26-fdfdb786a3c4',
                'description'      => 'Time to connect to the server',
                'commandarguments' => [
                    [
                        'name'       => '$ARG1$',
                        'human_name' => 'Warning in seconds'
                    ],
                    [
                        'name'       => '$ARG2$',
                        'human_name' => 'Critical in seconds'
                    ]
                ]
            ],
            /* uptime in seconds*/
            [
                'name'             => 'check_mysql_health__uptime',
                'command_line'     => '$USER1$/check_mysql_health --hostname $HOSTNAME$ --username $_SERVICEMYSQL_USER$ --password $_SERVICEMYSQL_PASS$ --mode uptime --units seconds --warning $ARG1$ --critical $ARG2$',
                'command_type'     => CHECK_COMMAND,
                'human_args'       => null,
                'uuid'             => 'b553d65d-b422-44c2-ab1e-d8922d41e8af',
                'description'      => 'Time the server is running',
                'commandarguments' => [
                    [
                        'name'       => '$ARG1$',
                        'human_name' => 'Warning in seconds'
                    ],
                    [
                        'name'       => '$ARG2$',
                        'human_name' => 'Critical in seconds'
                    ]
                ]
            ],
            /* threads-connected */
            [
                'name'             => 'check_mysql_health__threads-connected',
                'command_line'     => '$USER1$/check_mysql_health --hostname $HOSTNAME$ --username $_SERVICEMYSQL_USER$ --password $_SERVICEMYSQL_PASS$ --mode threads-connected --warning $ARG1$ --critical $ARG2$',
                'command_type'     => CHECK_COMMAND,
                'human_args'       => null,
                'uuid'             => '63317cd9-85d4-41d6-a8e2-a7d1602bd0f6',
                'description'      => 'Number of currently open connections',
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
            /* threadcache-hitrate */
            [
                'name'             => 'check_mysql_health__threadcache-hitrate',
                'command_line'     => '$USER1$/check_mysql_health --hostname $HOSTNAME$ --username $_SERVICEMYSQL_USER$ --password $_SERVICEMYSQL_PASS$ --mode threadcache-hitrate --warning $ARG1$ --critical $ARG2$',
                'command_type'     => CHECK_COMMAND,
                'human_args'       => null,
                'uuid'             => 'f5db0b4e-2af7-4fb5-937f-124f6d798f2c',
                'description'      => 'Hit rate of the thread-cache',
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
            /* threads-created */
            [
                'name'             => 'check_mysql_health__threads-created',
                'command_line'     => '$USER1$/check_mysql_health --hostname $HOSTNAME$ --username $_SERVICEMYSQL_USER$ --password $_SERVICEMYSQL_PASS$ --mode threads-created --units "created threads per sec" --warning $ARG1$ --critical $ARG2$',
                'command_type'     => CHECK_COMMAND,
                'human_args'       => null,
                'uuid'             => '33c569c2-8561-4d7a-83f2-ea017eb9f07c',
                'description'      => 'Number of threads created per second',
                'commandarguments' => [
                    [
                        'name'       => '$ARG1$',
                        'human_name' => 'Warning - Number of created threads per second'
                    ],
                    [
                        'name'       => '$ARG2$',
                        'human_name' => 'Critical - Number of created threads per second'
                    ]
                ]
            ],
            /* threads-running */
            [
                'name'             => 'check_mysql_health__threads-running',
                'command_line'     => '$USER1$/check_mysql_health --hostname $HOSTNAME$ --username $_SERVICEMYSQL_USER$ --password $_SERVICEMYSQL_PASS$ --mode threads-running --warning $ARG1$ --critical $ARG2$',
                'command_type'     => CHECK_COMMAND,
                'human_args'       => null,
                'uuid'             => 'c53847e7-2ca4-498e-8634-5eec26b12e01',
                'description'      => 'Number of currently running threads',
                'commandarguments' => [
                    [
                        'name'       => '$ARG1$',
                        'human_name' => 'Warning'
                    ],
                    [
                        'name'       => '$ARG2$',
                        'human_name' => 'Critical'
                    ]
                ],
                /* threads-cached */
                [
                    'name'             => 'check_mysql_health__threads-cached',
                    'command_line'     => '$USER1$/check_mysql_health --hostname $HOSTNAME$ --username $_SERVICEMYSQL_USER$ --password $_SERVICEMYSQL_PASS$ --mode threads-cached --warning $ARG1$ --critical $ARG2$',
                    'command_type'     => CHECK_COMMAND,
                    'human_args'       => null,
                    'uuid'             => '6dd4f561-3e80-4b4a-a683-d418dacbea3b',
                    'description'      => 'Number of currently cached threads',
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
                /* connects-aborted */
                [
                    'name'             => 'check_mysql_health__connects-aborted',
                    'command_line'     => '$USER1$/check_mysql_health --hostname $HOSTNAME$ --username $_SERVICEMYSQL_USER$ --password $_SERVICEMYSQL_PASS$ --mode connects-aborted --units "aborted connects per sec" --warning $ARG1$ --critical $ARG2$',
                    'command_type'     => CHECK_COMMAND,
                    'human_args'       => null,
                    'uuid'             => 'd4b97b6f-7364-4717-b6d8-695cd2965ae8',
                    'description'      => 'Number of aborted connections per second',
                    'commandarguments' => [
                        [
                            'name'       => '$ARG1$',
                            'human_name' => 'Warning - Number of aborted connects per second'
                        ],
                        [
                            'name'       => '$ARG2$',
                            'human_name' => 'Critical - Number of aborted connects per second'
                        ]
                    ]
                ],
                /* clients-aborted */
                [
                    'name'             => 'check_mysql_health__clients-aborted',
                    'command_line'     => '$USER1$/check_mysql_health --hostname $HOSTNAME$ --username $_SERVICEMYSQL_USER$ --password $_SERVICEMYSQL_PASS$ --mode clients-aborted --units "aborted connects per sec" --warning $ARG1$ --critical $ARG2$',
                    'command_type'     => CHECK_COMMAND,
                    'human_args'       => null,
                    'uuid'             => '117a2c52-8493-426f-bfcf-36a255f4eea4',
                    'description'      => 'Number of aborted connections (because the client died) per second',
                    'commandarguments' => [
                        [
                            'name'       => '$ARG1$',
                            'human_name' => 'Warning - Number of aborted connects per second'
                        ],
                        [
                            'name'       => '$ARG2$',
                            'human_name' => 'Critical - Number of aborted connects per second'
                        ]
                    ]
                ],
                /* qcache-hitrate */
                [
                    'name'             => 'check_mysql_health__qcache-hitrate',
                    'command_line'     => '$USER1$/check_mysql_health --hostname $HOSTNAME$ --username $_SERVICEMYSQL_USER$ --password $_SERVICEMYSQL_PASS$ --mode qcache-hitrate --units "%" --warning $ARG1$ --critical $ARG2$',
                    'command_type'     => CHECK_COMMAND,
                    'human_args'       => null,
                    'uuid'             => '45af9220-3666-422d-bf1a-9335cd958181',
                    'description'      => 'Query cache hitrate',
                    'commandarguments' => [
                        [
                            'name'       => '$ARG1$',
                            'human_name' => 'Warning in %'
                        ],
                        [
                            'name'       => '$ARG2$',
                            'human_name' => 'Critical in %'
                        ]
                    ]
                ],
                /* qcache-lowmem-prunes */
                [
                    'name'             => 'check_mysql_health__qcache-lowmem-prunes',
                    'command_line'     => '$USER1$/check_mysql_health --hostname $HOSTNAME$ --username $_SERVICEMYSQL_USER$ --password $_SERVICEMYSQL_PASS$ --mode qcache-lowmem-prunes --units "%" --warning $ARG1$ --critical $ARG2$',
                    'command_type'     => CHECK_COMMAND,
                    'human_args'       => null,
                    'uuid'             => '60be76fa-1687-42b3-ae3a-679027177359',
                    'description'      => 'Query cache entries pruned because of low memory',
                    'commandarguments' => [
                        [
                            'name'       => '$ARG1$',
                            'human_name' => 'Warning in %'
                        ],
                        [
                            'name'       => '$ARG2$',
                            'human_name' => 'Critical in %'
                        ]
                    ]
                ],
                /* bufferpool-hitrate */
                [
                    'name'             => 'check_mysql_health__bufferpool-hitrate',
                    'command_line'     => '$USER1$/check_mysql_health --hostname $HOSTNAME$ --username $_SERVICEMYSQL_USER$ --password $_SERVICEMYSQL_PASS$ --mode bufferpool-hitrate --units "%" --warning $ARG1$ --critical $ARG2$',
                    'command_type'     => CHECK_COMMAND,
                    'human_args'       => null,
                    'uuid'             => '9b2d90f0-6c8c-44a6-b826-dd22a5ab02f5',
                    'description'      => 'InnoDB buffer pool hitrate',
                    'commandarguments' => [
                        [
                            'name'       => '$ARG1$',
                            'human_name' => 'Warning in %'
                        ],
                        [
                            'name'       => '$ARG2$',
                            'human_name' => 'Critical in %'
                        ]
                    ]
                ],
                /* bufferpool-wait-free */
                [
                    'name'             => 'check_mysql_health__bufferpool-wait-free',
                    'command_line'     => '$USER1$/check_mysql_health --hostname $HOSTNAME$ --username $_SERVICEMYSQL_USER$ --password $_SERVICEMYSQL_PASS$ --mode bufferpool-wait-free --units "%" --warning $ARG1$ --critical $ARG2$',
                    'command_type'     => CHECK_COMMAND,
                    'human_args'       => null,
                    'uuid'             => '999ae421-6229-4197-9803-a88996905448',
                    'description'      => 'InnoDB buffer pool waits for clean page available',
                    'commandarguments' => [
                        [
                            'name'       => '$ARG1$',
                            'human_name' => 'Warning in %'
                        ],
                        [
                            'name'       => '$ARG2$',
                            'human_name' => 'Critical in %'
                        ]
                    ]
                ],
                /* log-waits */
                [
                    'name'             => 'check_mysql_health__log-waits',
                    'command_line'     => '$USER1$/check_mysql_health --hostname $HOSTNAME$ --username $_SERVICEMYSQL_USER$ --password $_SERVICEMYSQL_PASS$ --mode log-waits --units "%" --warning $ARG1$ --critical $ARG2$',
                    'command_type'     => CHECK_COMMAND,
                    'human_args'       => null,
                    'uuid'             => '016fca63-d656-457c-a556-68714bae8fa0',
                    'description'      => 'InnoDB log waits because of a too small log buffer',
                    'commandarguments' => [
                        [
                            'name'       => '$ARG1$',
                            'human_name' => 'Warning in %'
                        ],
                        [
                            'name'       => '$ARG2$',
                            'human_name' => 'Critical in %'
                        ]
                    ]
                ],
                /* tablecache-hitrate */
                [
                    'name'             => 'check_mysql_health__tablecache-hitrate',
                    'command_line'     => '$USER1$/check_mysql_health --hostname $HOSTNAME$ --username $_SERVICEMYSQL_USER$ --password $_SERVICEMYSQL_PASS$ --mode tablecache-hitrate --units "%" --warning $ARG1$ --critical $ARG2$',
                    'command_type'     => CHECK_COMMAND,
                    'human_args'       => null,
                    'uuid'             => '430bd37b-1fba-4c62-aeed-cbda4275d7e0',
                    'description'      => 'Table cache hitrate',
                    'commandarguments' => [
                        [
                            'name'       => '$ARG1$',
                            'human_name' => 'Warning in %'
                        ],
                        [
                            'name'       => '$ARG2$',
                            'human_name' => 'Critical in %'
                        ]
                    ]
                ],
                /* table-lock-contention */
                [
                    'name'             => 'check_mysql_health__table-lock-contention',
                    'command_line'     => '$USER1$/check_mysql_health --hostname $HOSTNAME$ --username $_SERVICEMYSQL_USER$ --password $_SERVICEMYSQL_PASS$ --mode table-lock-contention --units "%" --warning $ARG1$ --critical $ARG2$',
                    'command_type'     => CHECK_COMMAND,
                    'human_args'       => null,
                    'uuid'             => '2dc1ed45-2fcd-42c5-a6d1-91288d78f788',
                    'description'      => 'Table lock contention',
                    'commandarguments' => [
                        [
                            'name'       => '$ARG1$',
                            'human_name' => 'Warning in %'
                        ],
                        [
                            'name'       => '$ARG2$',
                            'human_name' => 'Critical in %'
                        ]
                    ]
                ],
                /* index-usage */
                [
                    'name'             => 'check_mysql_health__index-usage',
                    'command_line'     => '$USER1$/check_mysql_health --hostname $HOSTNAME$ --username $_SERVICEMYSQL_USER$ --password $_SERVICEMYSQL_PASS$ --mode index-usage --units "%" --warning $ARG1$ --critical $ARG2$',
                    'command_type'     => CHECK_COMMAND,
                    'human_args'       => null,
                    'uuid'             => 'a42e7d0d-ceba-42a3-8128-6764ce5cde8c',
                    'description'      => 'Usage of indices',
                    'commandarguments' => [
                        [
                            'name'       => '$ARG1$',
                            'human_name' => 'Warning in %'
                        ],
                        [
                            'name'       => '$ARG2$',
                            'human_name' => 'Critical in %'
                        ]
                    ]
                ],
                /* tmp-disk-tables */
                [
                    'name'             => 'check_mysql_health__tmp-disk-tables',
                    'command_line'     => '$USER1$/check_mysql_health --hostname $HOSTNAME$ --username $_SERVICEMYSQL_USER$ --password $_SERVICEMYSQL_PASS$ --mode tmp-disk-tables --units "%" --warning $ARG1$ --critical $ARG2$',
                    'command_type'     => CHECK_COMMAND,
                    'human_args'       => null,
                    'uuid'             => '1e5b6d2f-503b-41dd-92ee-cfc9717d0dd3',
                    'description'      => 'Percent of temp tables created on disk',
                    'commandarguments' => [
                        [
                            'name'       => '$ARG1$',
                            'human_name' => 'Warning in %'
                        ],
                        [
                            'name'       => '$ARG2$',
                            'human_name' => 'Critical in %'
                        ]
                    ]
                ],
                /* table-fragmentation */
                [
                    'name'             => 'check_mysql_health__table-fragmentation',
                    'command_line'     => '$USER1$/check_mysql_health --hostname $HOSTNAME$ --username $_SERVICEMYSQL_USER$ --password $_SERVICEMYSQL_PASS$ --mode table-fragmentation --units "Tables" --warning $ARG1$ --critical $ARG2$',
                    'command_type'     => CHECK_COMMAND,
                    'human_args'       => null,
                    'uuid'             => 'b464e382-1aff-4f92-970a-0807f6882fa3',
                    'description'      => 'Show tables which should be optimized',
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
                /* open-files */
                [
                    'name'             => 'check_mysql_health__open-files',
                    'command_line'     => '$USER1$/check_mysql_health --hostname $HOSTNAME$ --username $_SERVICEMYSQL_USER$ --password $_SERVICEMYSQL_PASS$ --mode open-files --units "%" --warning $ARG1$ --critical $ARG2$',
                    'command_type'     => CHECK_COMMAND,
                    'human_args'       => null,
                    'uuid'             => '2a08c744-6a1e-4281-bbbb-065665afb4ea',
                    'description'      => 'Percent of opened files',
                    'commandarguments' => [
                        [
                            'name'       => '$ARG1$',
                            'human_name' => 'Warning in %'
                        ],
                        [
                            'name'       => '$ARG2$',
                            'human_name' => 'Critical in %'
                        ]
                    ]
                ],
                /* slow-queries */
                [
                    'name'             => 'check_mysql_health__slow-queries',
                    'command_line'     => '$USER1$/check_mysql_health --hostname $HOSTNAME$ --username $_SERVICEMYSQL_USER$ --password $_SERVICEMYSQL_PASS$ --mode slow-queries --units "%" --warning $ARG1$ --critical $ARG2$',
                    'command_type'     => CHECK_COMMAND,
                    'human_args'       => null,
                    'uuid'             => '822d6c2a-3475-45ca-9f77-d1a7be3a57be',
                    'description'      => 'Slow queries',
                    'commandarguments' => [
                        [
                            'name'       => '$ARG1$',
                            'human_name' => 'Warning in %'
                        ],
                        [
                            'name'       => '$ARG2$',
                            'human_name' => 'Critical in %'
                        ]
                    ]
                ],
                /* long-running-procs */
                [
                    'name'             => 'check_mysql_health__long-running-procs',
                    'command_line'     => '$USER1$/check_mysql_health --hostname $HOSTNAME$ --username $_SERVICEMYSQL_USER$ --password $_SERVICEMYSQL_PASS$ --mode long-running-procs --units "%" --warning $ARG1$ --critical $ARG2$',
                    'command_type'     => CHECK_COMMAND,
                    'human_args'       => null,
                    'uuid'             => '3b6d29ff-966e-49cf-9b1a-159d3139ac49',
                    'description'      => 'Long running processes',
                    'commandarguments' => [
                        [
                            'name'       => '$ARG1$',
                            'human_name' => 'Warning in %'
                        ],
                        [
                            'name'       => '$ARG2$',
                            'human_name' => 'Critical in %'
                        ]
                    ]
                ]
            ]
        ];
        return $data;
    }

    public function getServicetemplatesData() {
        $data = [
            [
                'uuid'                                      => '976da093-ed3a-4fc2-978a-b8c37bb95a9d',
                'template_name'                             => 'MYSQL_CONNECTION_TIME',
                'name'                                      => 'Connection time',
                'container_id'                              => ROOT_CONTAINER,
                'servicetemplatetype_id'                    => GENERIC_SERVICE,
                'check_period_id'                           => '1',
                'notify_period_id'                          => '1',
                'description'                               => '',
                'command_id'                                => 'f503de41-54e2-40e9-9c26-fdfdb786a3c4',
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
                'servicetemplatecommandargumentvalues'      => [
                    [
                        'commandargument_id' => '$ARG1$',
                        'value'              => '90',
                    ],
                    [
                        'commandargument_id' => '$ARG2$',
                        'value'              => '95',
                    ]
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [],
                'contactgroups'                             => [],
                'contacts'                                  => []
            ],
            [
                'uuid'                                      => 'b471e570-2921-487d-8189-c4bbfa9a09d5',
                'template_name'                             => 'MYSQL_UPTIME',
                'name'                                      => 'Mysql Update',
                'container_id'                              => ROOT_CONTAINER,
                'servicetemplatetype_id'                    => GENERIC_SERVICE,
                'check_period_id'                           => '1',
                'notify_period_id'                          => '1',
                'description'                               => 'Seconds since the server was started. We can use this to detect respawns.',
                'command_id'                                => 'b553d65d-b422-44c2-ab1e-d8922d41e8af',
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
                'servicetemplatecommandargumentvalues'      => [
                    [
                        'commandargument_id' => '$ARG1$',
                        'value'              => '90',
                    ],
                    [
                        'commandargument_id' => '$ARG2$',
                        'value'              => '95',
                    ]
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [],
                'contactgroups'                             => [],
                'contacts'                                  => []
            ],
            [
                'uuid'                                      => '7542af3e-14e7-4a39-afff-6ae316313858',
                'template_name'                             => 'MYSQL_THREADS_CONNECTED',
                'name'                                      => 'Threads connected',
                'container_id'                              => ROOT_CONTAINER,
                'servicetemplatetype_id'                    => GENERIC_SERVICE,
                'check_period_id'                           => '1',
                'notify_period_id'                          => '1',
                'description'                               => 'Number of clients currently connected. If none or too high, something is wrong.',
                'command_id'                                => '63317cd9-85d4-41d6-a8e2-a7d1602bd0f6',
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
                'servicetemplatecommandargumentvalues'      => [
                    [
                        'commandargument_id' => '$ARG1$',
                        'value'              => '90',
                    ],
                    [
                        'commandargument_id' => '$ARG2$',
                        'value'              => '95',
                    ]
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [],
                'contactgroups'                             => [],
                'contacts'                                  => []
            ],
            [
                'uuid'                                      => '41b49614-c2f1-4bdd-a03c-ef7523b810dd',
                'template_name'                             => 'MYSQL_THREADCACHE_HITRATE',
                'name'                                      => 'Threadcache hitrate',
                'container_id'                              => ROOT_CONTAINER,
                'servicetemplatetype_id'                    => GENERIC_SERVICE,
                'check_period_id'                           => '1',
                'notify_period_id'                          => '1',
                'description'                               => 'Hit rate of the thread-cache',
                'command_id'                                => 'f5db0b4e-2af7-4fb5-937f-124f6d798f2c',
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
                'servicetemplatecommandargumentvalues'      => [
                    [
                        'commandargument_id' => '$ARG1$',
                        'value'              => '90',
                    ],
                    [
                        'commandargument_id' => '$ARG2$',
                        'value'              => '95',
                    ]
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [],
                'contactgroups'                             => [],
                'contacts'                                  => []
            ],
            [
                'uuid'                                      => '9f4ef558-847e-4953-8119-fedabffc29bc',
                'template_name'                             => 'MYSQL_THREADS_CREATED',
                'name'                                      => 'Threads created',
                'container_id'                              => ROOT_CONTAINER,
                'servicetemplatetype_id'                    => GENERIC_SERVICE,
                'check_period_id'                           => '1',
                'notify_period_id'                          => '1',
                'description'                               => 'Number of threads created per second',
                'command_id'                                => '33c569c2-8561-4d7a-83f2-ea017eb9f07c',
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
                'servicetemplatecommandargumentvalues'      => [
                    [
                        'commandargument_id' => '$ARG1$',
                        'value'              => '90',
                    ],
                    [
                        'commandargument_id' => '$ARG2$',
                        'value'              => '95',
                    ]
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [],
                'contactgroups'                             => [],
                'contacts'                                  => []
            ],
            [
                'uuid'                                      => '8c9a0fa8-2939-4aac-b252-f4f356313fe3',
                'template_name'                             => 'MYSQL_THREADS_RUNNING',
                'name'                                      => 'Threads running',
                'container_id'                              => ROOT_CONTAINER,
                'servicetemplatetype_id'                    => GENERIC_SERVICE,
                'check_period_id'                           => '1',
                'notify_period_id'                          => '1',
                'description'                               => 'Number of currently running threads',
                'command_id'                                => 'c53847e7-2ca4-498e-8634-5eec26b12e01',
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
                'servicetemplatecommandargumentvalues'      => [
                    [
                        'commandargument_id' => '$ARG1$',
                        'value'              => '90',
                    ],
                    [
                        'commandargument_id' => '$ARG2$',
                        'value'              => '95',
                    ]
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [],
                'contactgroups'                             => [],
                'contacts'                                  => []
            ],
            [
                'uuid'                                      => '46229355-cc9d-4179-a484-b3075a7861a0',
                'template_name'                             => 'MYSQL_THREADS_CACHED',
                'name'                                      => 'Threads cached',
                'container_id'                              => ROOT_CONTAINER,
                'servicetemplatetype_id'                    => GENERIC_SERVICE,
                'check_period_id'                           => '1',
                'notify_period_id'                          => '1',
                'description'                               => 'Number of currently cached threads',
                'command_id'                                => '6dd4f561-3e80-4b4a-a683-d418dacbea3b',
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
                'servicetemplatecommandargumentvalues'      => [
                    [
                        'commandargument_id' => '$ARG1$',
                        'value'              => '90',
                    ],
                    [
                        'commandargument_id' => '$ARG2$',
                        'value'              => '95',
                    ]
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [],
                'contactgroups'                             => [],
                'contacts'                                  => []
            ],
            [
                'uuid'                                      => '177ec9fe-6ec1-4bad-aa67-c20475bd8972',
                'template_name'                             => 'MYSQL_CONNECTS_ABORTED',
                'name'                                      => 'Connects aborted',
                'container_id'                              => ROOT_CONTAINER,
                'servicetemplatetype_id'                    => GENERIC_SERVICE,
                'check_period_id'                           => '1',
                'notify_period_id'                          => '1',
                'description'                               => 'Number of aborted connections per second',
                'command_id'                                => 'd4b97b6f-7364-4717-b6d8-695cd2965ae8',
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
                'servicetemplatecommandargumentvalues'      => [
                    [
                        'commandargument_id' => '$ARG1$',
                        'value'              => '90',
                    ],
                    [
                        'commandargument_id' => '$ARG2$',
                        'value'              => '95',
                    ]
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [],
                'contactgroups'                             => [],
                'contacts'                                  => []
            ],
            [
                'uuid'                                      => 'fea34274-eaca-48af-b64b-6f247b29fa07',
                'template_name'                             => 'MYSQL_CLIENTS_ABORTED',
                'name'                                      => 'Clients aborted',
                'container_id'                              => ROOT_CONTAINER,
                'servicetemplatetype_id'                    => GENERIC_SERVICE,
                'check_period_id'                           => '1',
                'notify_period_id'                          => '1',
                'description'                               => 'Number of failed connection attempts. When growing over a period of time either some credentials are wrong or we are being attacked.',
                'command_id'                                => '117a2c52-8493-426f-bfcf-36a255f4eea4',
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
                'servicetemplatecommandargumentvalues'      => [
                    [
                        'commandargument_id' => '$ARG1$',
                        'value'              => '90',
                    ],
                    [
                        'commandargument_id' => '$ARG2$',
                        'value'              => '95',
                    ]
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [],
                'contactgroups'                             => [],
                'contacts'                                  => []
            ],
            [
                'uuid'                                      => 'e9bddda3-9581-4472-b774-25c641219490',
                'template_name'                             => 'MYSQL_QCACHE_HITRATE',
                'name'                                      => 'Query cache hitrate',
                'container_id'                              => ROOT_CONTAINER,
                'servicetemplatetype_id'                    => GENERIC_SERVICE,
                'check_period_id'                           => '1',
                'notify_period_id'                          => '1',
                'description'                               => '',
                'command_id'                                => '45af9220-3666-422d-bf1a-9335cd958181',
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
                'servicetemplatecommandargumentvalues'      => [
                    [
                        'commandargument_id' => '$ARG1$',
                        'value'              => '90',
                    ],
                    [
                        'commandargument_id' => '$ARG2$',
                        'value'              => '95',
                    ]
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [],
                'contactgroups'                             => [],
                'contacts'                                  => []
            ],
            [
                'uuid'                                      => '3baceaba-3396-4400-a970-133c8e848eca',
                'template_name'                             => 'MYSQL_QCACHE_LOWMEM_PRUNES',
                'name'                                      => 'Qcache low memory prunes',
                'container_id'                              => ROOT_CONTAINER,
                'servicetemplatetype_id'                    => GENERIC_SERVICE,
                'check_period_id'                           => '1',
                'notify_period_id'                          => '1',
                'description'                               => 'Query cache entries pruned because of low memory',
                'command_id'                                => '60be76fa-1687-42b3-ae3a-679027177359',
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
                'servicetemplatecommandargumentvalues'      => [
                    [
                        'commandargument_id' => '$ARG1$',
                        'value'              => '90',
                    ],
                    [
                        'commandargument_id' => '$ARG2$',
                        'value'              => '95',
                    ]
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [],
                'contactgroups'                             => [],
                'contacts'                                  => []
            ],
            [
                'uuid'                                      => '18bfc587-97b3-4f45-9ab4-73245453dee1',
                'template_name'                             => 'MYSQL_BUFFERPOOL_HITRATE',
                'name'                                      => 'Bufferpool hitrate',
                'container_id'                              => ROOT_CONTAINER,
                'servicetemplatetype_id'                    => GENERIC_SERVICE,
                'check_period_id'                           => '1',
                'notify_period_id'                          => '1',
                'description'                               => 'InnoDB buffer pool hitrate',
                'command_id'                                => '9b2d90f0-6c8c-44a6-b826-dd22a5ab02f5',
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
                'servicetemplatecommandargumentvalues'      => [
                    [
                        'commandargument_id' => '$ARG1$',
                        'value'              => '90',
                    ],
                    [
                        'commandargument_id' => '$ARG2$',
                        'value'              => '95',
                    ]
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [],
                'contactgroups'                             => [],
                'contacts'                                  => []
            ],
            [
                'uuid'                                      => '1e9eb002-aeb8-45b7-86a6-ea3f5f12d631',
                'template_name'                             => 'MYSQL_BUFFERPOOL_WAIT_FREE',
                'name'                                      => 'Bufferpool wait free',
                'container_id'                              => ROOT_CONTAINER,
                'servicetemplatetype_id'                    => GENERIC_SERVICE,
                'check_period_id'                           => '1',
                'notify_period_id'                          => '1',
                'description'                               => 'InnoDB buffer pool waits for clean page available',
                'command_id'                                => '999ae421-6229-4197-9803-a88996905448',
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
                'servicetemplatecommandargumentvalues'      => [
                    [
                        'commandargument_id' => '$ARG1$',
                        'value'              => '90',
                    ],
                    [
                        'commandargument_id' => '$ARG2$',
                        'value'              => '95',
                    ]
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [],
                'contactgroups'                             => [],
                'contacts'                                  => []
            ],
            [
                'uuid'                                      => '421af7c7-2736-4c03-a239-64dc8c2e2cf7',
                'template_name'                             => 'MYSQL_LOG_WAITS',
                'name'                                      => 'Log waits',
                'container_id'                              => ROOT_CONTAINER,
                'servicetemplatetype_id'                    => GENERIC_SERVICE,
                'check_period_id'                           => '1',
                'notify_period_id'                          => '1',
                'description'                               => 'InnoDB log waits because of a too small log buffer',
                'command_id'                                => '016fca63-d656-457c-a556-68714bae8fa0',
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
                'servicetemplatecommandargumentvalues'      => [
                    [
                        'commandargument_id' => '$ARG1$',
                        'value'              => '90',
                    ],
                    [
                        'commandargument_id' => '$ARG2$',
                        'value'              => '95',
                    ]
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [],
                'contactgroups'                             => [],
                'contacts'                                  => []
            ],
            [
                'uuid'                                      => '421af7c7-2736-4c03-a239-64dc8c2e2cf7',
                'template_name'                             => 'MYSQL_TABLECACHE_HITRATE',
                'name'                                      => 'Table cache hitrate',
                'container_id'                              => ROOT_CONTAINER,
                'servicetemplatetype_id'                    => GENERIC_SERVICE,
                'check_period_id'                           => '1',
                'notify_period_id'                          => '1',
                'description'                               => '',
                'command_id'                                => '430bd37b-1fba-4c62-aeed-cbda4275d7e0',
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
                'servicetemplatecommandargumentvalues'      => [
                    [
                        'commandargument_id' => '$ARG1$',
                        'value'              => '90',
                    ],
                    [
                        'commandargument_id' => '$ARG2$',
                        'value'              => '95',
                    ]
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [],
                'contactgroups'                             => [],
                'contacts'                                  => []
            ],
            [
                'uuid'                                      => '373e5fe2-22f9-4ebc-8055-a375ba4e951e',
                'template_name'                             => 'MYSQL_TABLE_LOCK_CONTENTION',
                'name'                                      => 'Table lock contention',
                'container_id'                              => ROOT_CONTAINER,
                'servicetemplatetype_id'                    => GENERIC_SERVICE,
                'check_period_id'                           => '1',
                'notify_period_id'                          => '1',
                'description'                               => '',
                'command_id'                                => '2dc1ed45-2fcd-42c5-a6d1-91288d78f788',
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
                'servicetemplatecommandargumentvalues'      => [
                    [
                        'commandargument_id' => '$ARG1$',
                        'value'              => '90',
                    ],
                    [
                        'commandargument_id' => '$ARG2$',
                        'value'              => '95',
                    ]
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [],
                'contactgroups'                             => [],
                'contacts'                                  => []
            ],
            [
                'uuid'                                      => '5bae7650-0694-4a2f-bfce-b4bb63659ff3',
                'template_name'                             => 'MYSQL_INDEX_USAGE',
                'name'                                      => 'Usage of indices',
                'container_id'                              => ROOT_CONTAINER,
                'servicetemplatetype_id'                    => GENERIC_SERVICE,
                'check_period_id'                           => '1',
                'notify_period_id'                          => '1',
                'description'                               => '',
                'command_id'                                => 'a42e7d0d-ceba-42a3-8128-6764ce5cde8c',
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
                'servicetemplatecommandargumentvalues'      => [
                    [
                        'commandargument_id' => '$ARG1$',
                        'value'              => '90',
                    ],
                    [
                        'commandargument_id' => '$ARG2$',
                        'value'              => '95',
                    ]
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [],
                'contactgroups'                             => [],
                'contacts'                                  => []
            ],
            [
                'uuid'                                      => 'a0bce589-92c9-46a5-a44d-7b62efcfdd90',
                'template_name'                             => 'MYSQL_TMP_DISK_TABLES',
                'name'                                      => 'Temp disk tables',
                'container_id'                              => ROOT_CONTAINER,
                'servicetemplatetype_id'                    => GENERIC_SERVICE,
                'check_period_id'                           => '1',
                'notify_period_id'                          => '1',
                'description'                               => 'Number of temporary tables (typically for joins) stored on slow spinning disks, instead of faster RAM.',
                'command_id'                                => '1e5b6d2f-503b-41dd-92ee-cfc9717d0dd3',
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
                'servicetemplatecommandargumentvalues'      => [
                    [
                        'commandargument_id' => '$ARG1$',
                        'value'              => '90',
                    ],
                    [
                        'commandargument_id' => '$ARG2$',
                        'value'              => '95',
                    ]
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [],
                'contactgroups'                             => [],
                'contacts'                                  => []
            ],
            [
                'uuid'                                      => '460246de-98d2-43a0-805e-e2cf3417124d',
                'template_name'                             => 'MYSQL_TABLE_FRAGMENTATION',
                'name'                                      => 'Table fragmentation',
                'container_id'                              => ROOT_CONTAINER,
                'servicetemplatetype_id'                    => GENERIC_SERVICE,
                'check_period_id'                           => '1',
                'notify_period_id'                          => '1',
                'description'                               => 'Show tables which should be optimized',
                'command_id'                                => 'b464e382-1aff-4f92-970a-0807f6882fa3',
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
                'servicetemplatecommandargumentvalues'      => [
                    [
                        'commandargument_id' => '$ARG1$',
                        'value'              => '90',
                    ],
                    [
                        'commandargument_id' => '$ARG2$',
                        'value'              => '95',
                    ]
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [],
                'contactgroups'                             => [],
                'contacts'                                  => []
            ],
            [
                'uuid'                                      => '6163bdce-5fc1-4328-8f50-c5e21e021c39',
                'template_name'                             => 'MYSQL_OPEN_FILES',
                'name'                                      => 'Open files',
                'container_id'                              => ROOT_CONTAINER,
                'servicetemplatetype_id'                    => GENERIC_SERVICE,
                'check_period_id'                           => '1',
                'notify_period_id'                          => '1',
                'description'                               => 'Percent of opened files',
                'command_id'                                => '2a08c744-6a1e-4281-bbbb-065665afb4ea',
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
                'servicetemplatecommandargumentvalues'      => [
                    [
                        'commandargument_id' => '$ARG1$',
                        'value'              => '90',
                    ],
                    [
                        'commandargument_id' => '$ARG2$',
                        'value'              => '95',
                    ]
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [],
                'contactgroups'                             => [],
                'contacts'                                  => []
            ],
            [
                'uuid'                                      => '8b0895be-bcc2-4699-baf4-9f69a40b00a2',
                'template_name'                             => 'MYSQL_SLOW_QUERIES',
                'name'                                      => 'Slow queries',
                'container_id'                              => ROOT_CONTAINER,
                'servicetemplatetype_id'                    => GENERIC_SERVICE,
                'check_period_id'                           => '1',
                'notify_period_id'                          => '1',
                'description'                               => 'Number of queries that took more than long_query_time seconds to execute. Slow queries generate excessive disk reads, memory and CPU usage. Check slow_query_log to find them.',
                'command_id'                                => '822d6c2a-3475-45ca-9f77-d1a7be3a57be',
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
                'servicetemplatecommandargumentvalues'      => [
                    [
                        'commandargument_id' => '$ARG1$',
                        'value'              => '90',
                    ],
                    [
                        'commandargument_id' => '$ARG2$',
                        'value'              => '95',
                    ]
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [],
                'contactgroups'                             => [],
                'contacts'                                  => []
            ],
            [
                'uuid'                                      => '2e64c98a-254d-4655-9ec1-7816a7c97cff',
                'template_name'                             => 'MYSQL_LONG_RUNNING_PROCESSES',
                'name'                                      => 'Long running processes',
                'container_id'                              => ROOT_CONTAINER,
                'servicetemplatetype_id'                    => GENERIC_SERVICE,
                'check_period_id'                           => '1',
                'notify_period_id'                          => '1',
                'description'                               => 'Excessive Number of Long Running Processes',
                'command_id'                                => '3b6d29ff-966e-49cf-9b1a-159d3139ac49',
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
                'servicetemplatecommandargumentvalues'      => [
                    [
                        'commandargument_id' => '$ARG1$',
                        'value'              => '90',
                    ],
                    [
                        'commandargument_id' => '$ARG2$',
                        'value'              => '95',
                    ]
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [],
                'contactgroups'                             => [],
                'contacts'                                  => []
            ]
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
