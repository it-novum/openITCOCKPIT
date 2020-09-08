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
                'description'      => 'Number of threads created per sec',
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
                    'description'      => 'Number of aborted connections per sec',
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
                    'description'      => 'Number of aborted connections (because the client died) per sec',
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
                    'uuid'             => '430bd37b-1fba-4c62-aeed-cbda4275d7e0',
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
                    'uuid'             => 'a42e7d0d-ceba-42a3-8128-6764ce5cde8c',
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
                'contacts'                                  => []
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
                'servicegroups'                             => [],
                'contactgroups'                             => [],
                'contacts'                                  => []
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
                'servicegroups'                             => [],
                'contactgroups'                             => [],
                'contacts'                                  => []
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
                        'commandargument_id' => '$ARG3$',
                        'value'              => '%',
                    ]
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [],
                'contactgroups'                             => [],
                'contacts'                                  => []
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
                'servicegroups'                             => [],
                'contactgroups'                             => [],
                'contacts'                                  => []
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
                    ],
                    [
                        'commandargument_id' => '$ARG2$',
                        'value'              => 'sda1',
                    ]
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [],
                'contactgroups'                             => [],
                'contacts'                                  => []
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
                        'value'              => '%',
                    ]
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [],
                'contactgroups'                             => [],
                'contacts'                                  => []
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
                        'value'              => '0',
                    ],
                    [
                        'commandargument_id' => '$ARG4$',
                        'value'              => 'thinkpad',
                    ]
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [],
                'contactgroups'                             => [],
                'contacts'                                  => []
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
                'servicegroups'                             => [],
                'contactgroups'                             => [],
                'contacts'                                  => []
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
                'servicegroups'                             => [],
                'contactgroups'                             => [],
                'contacts'                                  => []
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
                'servicegroups'                             => [],
                'contactgroups'                             => [],
                'contacts'                                  => []
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
                'servicegroups'                             => [],
                'contactgroups'                             => [],
                'contacts'                                  => []
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
                'servicegroups'                             => [],
                'contactgroups'                             => [],
                'contacts'                                  => []
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
                'servicegroups'                             => [],
                'contactgroups'                             => [],
                'contacts'                                  => []
            ],

            [
                'uuid'                                      => 'd21e3462-7430-4d38-b92a-853bcfc12356',
                'template_name'                             => 'OITC_AGENT_SYSTEMD_SERVICES',
                'name'                                      => 'Check systemd service running',
                'container_id'                              => ROOT_CONTAINER,
                'servicetemplatetype_id'                    => OITC_AGENT_SERVICE,
                'check_period_id'                           => '1',
                'notify_period_id'                          => '1',
                'description'                               => '',
                'command_id'                                => '44cf7fa5-38cc-45fb-aa5c-d4bb082e195d',
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
                        'value'              => '1',
                    ]
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [],
                'contactgroups'                             => [],
                'contacts'                                  => []
            ],

            [
                'uuid'                                      => 'dda2d5dc-7987-49a5-b4c2-0540e8aaf45e',
                'template_name'                             => 'OITC_AGENT_WINDOWS_EVENTLOG',
                'name'                                      => 'Check windows eventlog entry',
                'container_id'                              => ROOT_CONTAINER,
                'servicetemplatetype_id'                    => OITC_AGENT_SERVICE,
                'check_period_id'                           => '1',
                'notify_period_id'                          => '1',
                'description'                               => '',
                'command_id'                                => 'af3f0cac-f562-4830-9935-a9b2d69d494e',
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
                        'value'              => 'ok',
                    ],
                    [
                        'commandargument_id' => '$ARG3$',
                        'value'              => '60',
                    ],
                    [
                        'commandargument_id' => '$ARG4$',
                        'value'              => '',
                    ],
                    [
                        'commandargument_id' => '$ARG5$',
                        'value'              => '1',
                    ],
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [],
                'contactgroups'                             => [],
                'contacts'                                  => []
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
                        'value'              => 'name',
                    ],
                    [
                        'commandargument_id' => '$ARG2$',
                        'value'              => 'fancy_name....',
                    ]
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [],
                'contactgroups'                             => [],
                'contacts'                                  => []
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
                        'value'              => 'name',
                    ],
                    [
                        'commandargument_id' => '$ARG2$',
                        'value'              => 'fancy_name....',
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
                'servicegroups'                             => [],
                'contactgroups'                             => [],
                'contacts'                                  => []
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
                        'value'              => 'name',
                    ],
                    [
                        'commandargument_id' => '$ARG2$',
                        'value'              => 'fancy_name....',
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
                'servicegroups'                             => [],
                'contactgroups'                             => [],
                'contacts'                                  => []
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
                'servicegroups'                             => [],
                'contactgroups'                             => [],
                'contacts'                                  => []
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
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [],
                'contactgroups'                             => [],
                'contacts'                                  => []
            ],


            /* Alfresco servicetemplates */

            [
                'uuid'                                      => 'e453767c-b216-4b17-a012-9bc037d7da49',
                'template_name'                             => 'OITC_AGENT_ALFRESCO',
                'name'                                      => 'Alfresco check',
                'container_id'                              => ROOT_CONTAINER,
                'servicetemplatetype_id'                    => OITC_AGENT_SERVICE,
                'check_period_id'                           => '1',
                'notify_period_id'                          => '1',
                'description'                               => '',
                'command_id'                                => '0d2e47c7-d584-4a4a-9a6c-1a1ff0f9365c',
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
                        'value'              => '0',
                    ],
                    [
                        'commandargument_id' => '$ARG2$',
                        'value'              => '0',
                    ],
                    [
                        'commandargument_id' => '$ARG3$',
                        'value'              => 'Alfresco check name',
                    ],
                ],
                'customvariables'                           => [],
                'servicegroups'                             => [],
                'contactgroups'                             => [],
                'contacts'                                  => []
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
