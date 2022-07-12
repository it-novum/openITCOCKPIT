<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Lib\Interfaces\HoststatusTableInterface;
use App\Lib\Interfaces\ServicestatusTableInterface;
use App\Lib\Traits\PaginationAndScrollIndexTrait;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\Core\DbBackend;
use itnovum\openITCOCKPIT\Core\Hoststatus;
use itnovum\openITCOCKPIT\Core\HoststatusFields;
use itnovum\openITCOCKPIT\Core\Servicestatus;
use itnovum\openITCOCKPIT\Core\ServicestatusConditions;
use itnovum\openITCOCKPIT\Core\ServicestatusFields;
use itnovum\openITCOCKPIT\Core\Views\ServiceStateSummary;
use itnovum\openITCOCKPIT\Core\Views\UserTime;
use itnovum\openITCOCKPIT\Filter\StatuspagesFilter;
use MapModule\Model\Table\MapsTable;

/**
 * Statuspages Model
 *
 * @property \App\Model\Table\StatuspagesToContainersTable&\Cake\ORM\Association\HasMany $StatuspagesToContainers
 * @property \App\Model\Table\StatuspagesToHostgroupsTable&\Cake\ORM\Association\HasMany $StatuspagesToHostgroups
 * @property \App\Model\Table\StatuspagesToHostsTable&\Cake\ORM\Association\HasMany $StatuspagesToHosts
 * @property \App\Model\Table\StatuspagesToServicegroupsTable&\Cake\ORM\Association\HasMany $StatuspagesToServicegroups
 * @property \App\Model\Table\StatuspagesToServicesTable&\Cake\ORM\Association\HasMany $StatuspagesToServices
 *
 * @method \App\Model\Entity\Statuspage newEmptyEntity()
 * @method \App\Model\Entity\Statuspage newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Statuspage[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Statuspage get($primaryKey, $options = [])
 * @method \App\Model\Entity\Statuspage findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Statuspage patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Statuspage[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Statuspage|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Statuspage saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Statuspage[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Statuspage[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Statuspage[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Statuspage[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class StatuspagesTable extends Table {
    use PaginationAndScrollIndexTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void {
        parent::initialize($config);

        $this->setTable('statuspages');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsToMany('Containers', [
            'className'        => 'Containers',
            'foreignKey'       => 'statuspage_id',
            'targetForeignKey' => 'container_id',
            'joinTable'        => 'statuspages_to_containers'
        ]);

        $this->belongsToMany('Hosts', [
            'className'        => 'Hosts',
            'foreignKey'       => 'statuspage_id',
            'targetForeignKey' => 'host_id',
            'joinTable'        => 'statuspages_to_hosts'
        ]);

        $this->belongsToMany('Services', [
            'className'        => 'Services',
            'foreignKey'       => 'statuspage_id',
            'targetForeignKey' => 'service_id',
            'joinTable'        => 'statuspages_to_services'
        ]);
        $this->belongsToMany('Hostgroups', [
            'className'        => 'Hostgroups',
            'foreignKey'       => 'statuspage_id',
            'targetForeignKey' => 'hostgroup_id',
            'joinTable'        => 'statuspages_to_hostgroups'
        ]);
        $this->belongsToMany('Servicegroups', [
            'className'        => 'Servicegroups',
            'foreignKey'       => 'statuspage_id',
            'targetForeignKey' => 'servicegroup_id',
            'joinTable'        => 'statuspages_to_servicegroups'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator {
        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('description')
            ->maxLength('description', 255)
            ->requirePresence('description', 'create')
            ->notEmptyString('description');

        $validator
            ->boolean('public')
            ->notEmptyString('public');

        return $validator;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function existsById($id): bool {
        return $this->exists(['Statuspages.id' => $id]);
    }


    /**
     * @param StatuspagesFilter $StatuspagesFilter
     * @param $PaginateOMat
     * @param $MY_RIGHTS
     * @return array
     */
    public function getStatuspagesIndex(StatuspagesFilter $StatuspagesFilter, $PaginateOMat = null, $MY_RIGHTS = []) {
        $query = $this->find('all');
        $query->contain(['Containers']);
        $query->where($StatuspagesFilter->indexFilter());

        $query->innerJoinWith('Containers', function (Query $q) use ($MY_RIGHTS) {
            if (!empty($MY_RIGHTS)) {
                return $q->where(['Containers.id IN' => $MY_RIGHTS]);
            }
            return $q;
        });

        $query->distinct('Statuspages.id');

        $query->disableHydration();
        $query->order($StatuspagesFilter->getOrderForPaginator('Statuspages.name', 'asc'));

        if ($PaginateOMat === null) {
            //Just execute query
            $result = $this->emptyArrayIfNull($query->toArray());
        } else {
            if ($PaginateOMat->useScroll()) {
                $result = $this->scrollCake4($query, $PaginateOMat->getHandler());
            } else {
                $result = $this->paginateCake4($query, $PaginateOMat->getHandler());
            }
        }

        return $result;
    }

    /**
     * @param $id
     * @return array|void
     */
    public function getStatuspageObjects($id = null, $conditions = []) {
        if (!$this->existsById($id)) {
            return;
        }

        $conditions = array_merge(['Statuspages.id' => $id], $conditions);

        $query = $this->find()
            ->contain('Hosts', function (Query $q) {
                return $q
                    ->select(['id', 'name']);
            })
            ->contain('Services', function (Query $q) {
                return $q
                    ->select([
                        'id',
                        'servicename' => $q->newExpr('IF(Services.name IS NULL, Servicetemplates.name, Services.name)'),
                    ])
                    ->innerJoin(['Servicetemplates' => 'servicetemplates'], [
                        'Servicetemplates.id = Services.servicetemplate_id'
                    ]);
            })
            ->contain('Hostgroups', function (Query $q) {
                return $q
                    ->select([
                        'id',
                        'Containers.name'
                    ])
                    ->innerJoin(['Containers' => 'containers'], [
                        'Containers.id = Hostgroups.container_id',
                        'Containers.containertype_id' => CT_HOSTGROUP
                    ]);
            })
            ->contain('Servicegroups', function (Query $q) {
                return $q
                    ->select([
                        'id',
                        'Containers.name'
                    ])
                    ->innerJoin(['Containers' => 'containers'], [
                        'Containers.id = Servicegroups.container_id',
                        'Containers.containertype_id' => CT_SERVICEGROUP
                    ]);
            })
            ->where($conditions)
            ->firstOrFail();
        $statuspage = $query->toArray();

        return $statuspage;
    }

    /**
     * @param $id
     * @param $DbBackend
     * @return array
     */
    public function getStatuspageObjectsForView($id = null, $DbBackend = null, $conditions = []) {
        /** @var HostsTable $HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        /** @var ServicesTable $ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');

        $HoststatusTable = $DbBackend->getHoststatusTable();
        $ServicestatusTable = $DbBackend->getServicestatusTable();

        $statuspageData = $this->getStatuspageObjects($id, $conditions);

        $statuspageObjects = [
            'hosts'         => (isset($statuspageData['hosts']) ? $statuspageData['hosts'] : []),
            'services'      => (isset($statuspageData['services']) ? $statuspageData['services'] : []),
            'hostgroups'    => (isset($statuspageData['hostgroups']) ? $statuspageData['hostgroups'] : []),
            'servicegroups' => (isset($statuspageData['servicegroups']) ? $statuspageData['servicegroups'] : [])
        ];

        $this->getDowntimesForStatuspageObjects($statuspageObjects);

        $statuspageForView = [
            'statuspage'    => [
                'name'        => $statuspageData['name'],
                'description' => $statuspageData['description'],
            ],
            'hosts'         => [],
            'services'      => [],
            'hostgroups'    => [],
            'servicegroups' => []
        ];


        foreach ($statuspageData as $key => $statuspage) {
            if ($key == 'hosts') {
                foreach ($statuspage as $subKey => $item) {
                    $host = $this->getHostForStatuspage($item['id']);

                    if (!empty($host)) {
                        // This is plain host state - no cumulated service state
                        $hoststatus = $this->getHostSummary(
                            $HoststatusTable,
                            $host
                        );

                        $statuspageForView[$key][$subKey]['name'] = (!empty($item['_joinData']['display_name']) ? $item['_joinData']['display_name'] : $item['name']);
                        if (!empty($hoststatus['Hoststatus'])) {
                            $statuspageForView[$key][$subKey]['currentState'] = $hoststatus['Hoststatus']['currentState'];
                            $statuspageForView[$key][$subKey]['humanState'] = $hoststatus['Hoststatus']['humanState'];
                        }

                    }
                }
            }
            if ($key == 'services') {
                foreach ($statuspage as $subKey => $item) {
                    $service = $this->getServiceForStatuspage($item['id']);

                    if (!empty($service)) {
                        $servicestatus = $this->getServiceSummary(
                            $ServicestatusTable,
                            $service
                        );

                        $statuspageForView[$key][$subKey]['name'] = (!empty($item['_joinData']['display_name']) ? $item['_joinData']['display_name'] : $item['servicename']);
                        if (isset($servicestatus['Servicestatus'])) {
                            $statuspageForView[$key][$subKey]['currentState'] = $servicestatus['Servicestatus']['currentState'];
                            $statuspageForView[$key][$subKey]['humanState'] = $servicestatus['Servicestatus']['humanState'];
                        }
                    }
                }
            }
            if ($key == 'hostgroups') {
                foreach ($statuspage as $subKey => $item) {
                    $hostgroup = $this->getHostsByHostgroupForStatuspages($item['id']);
                    $hostgroup['hosts'] = array_merge(
                        $hostgroup['hosts'],
                        Hash::extract($hostgroup, 'hosttemplates.{n}.hosts.{n}')
                    );

                    $hostgroupstatus = $this->getHostgroupSummary(
                        $HostsTable,
                        $ServicesTable,
                        $HoststatusTable,
                        $ServicestatusTable,
                        $hostgroup
                    );
                    $statuspageForView[$key][$subKey]['name'] = (!empty($item['_joinData']['display_name']) ? $item['_joinData']['display_name'] : $item['Containers']['name']);
                    if (isset($hostgroupstatus['CumulatedState'])) {
                        $statuspageForView[$key][$subKey]['currentState'] = $hostgroupstatus['CumulatedState']['currentState'];
                        $statuspageForView[$key][$subKey]['humanState'] = $hostgroupstatus['CumulatedState']['humanState'];
                        $statuspageForView[$key][$subKey]['stateType'] = $hostgroupstatus['CumulatedState']['stateType'];
                    }
                }
            }
            if ($key == 'servicegroups') {
                foreach ($statuspage as $subKey => $item) {
                    $servicegroup = $this->getServicegroupByIdForStatuspages($item['id']);
                    $servicegroup['services'] = array_merge(
                        $servicegroup['services'],
                        Hash::extract($servicegroup, 'servicetemplates.{n}.services.{n}')
                    );
                    $servicegroupstatus = $this->getServicegroupSummary(
                        $ServicesTable,
                        $ServicestatusTable,
                        $servicegroup
                    );

                    $statuspageForView[$key][$subKey]['name'] = (!empty($item['_joinData']['display_name']) ? $item['_joinData']['display_name'] : $item['Containers']['name']);
                    if (isset($servicegroupstatus['CumulatedState'])) {
                        $statuspageForView[$key][$subKey]['currentState'] = $servicegroupstatus['CumulatedState']['currentState'];
                        $statuspageForView[$key][$subKey]['humanState'] = $servicegroupstatus['CumulatedState']['humanState'];;
                    }

                }
            }
        }

        $statuspageForView['statuspage']['cumulatedState'] = $this->getCumulatedStateForStatuspage($statuspageForView);


        return $statuspageForView;
    }


    /**
     * @param $statuspageData
     * @return array
     */
    public function getCumulatedStateForStatuspage($statuspageData): array {
        $states = [
            'hosts'         => [],
            'services'      => [],
            'hostgroups'    => [],
            'servicegroups' => []
        ];

        foreach ($statuspageData as $key => $statuspage) {
            if ($key == 'statuspage') {
                continue;
            }
            $itemState = [];
            foreach ($statuspage as $subKey => $item) {
                $itemState[$subKey] = $item['currentState'];
            }
            $worstItemStatusKey = array_keys($itemState, max($itemState))[0];
            $states[$key] = $statuspage[$worstItemStatusKey];
        }


        $tmpStates = [];
        foreach ($states as $key => $item) {
            $tmpStates[$key] = $item['currentState'];
        }
        $worstTmpItemStatusKey = array_keys($tmpStates, max($tmpStates))[0];

        $stateType = $worstTmpItemStatusKey;
        if ($worstTmpItemStatusKey == 'hostgroups') {
            $stateType = Inflector::pluralize($states[$worstTmpItemStatusKey]['stateType']);
        }

        $worstState = [
            'state'      => $states[$worstTmpItemStatusKey]['currentState'], // int status
            'stateType'  => $stateType, // hosts or services
            'humanState' => $states[$worstTmpItemStatusKey]['humanState']
        ];

        return $worstState;
    }


    /**
     * @param $id
     * @return array
     */
    private function getHostForStatuspage($id) {
        /** @var HostsTable $HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

        $host = $HostsTable->get($id, [
            'fields' => [
                'Hosts.id',
                'Hosts.uuid',
                'Hosts.name',
                'Hosts.description',
                'Hosts.disabled'
            ],
        ])->toArray();

        return $host;
    }

    /**
     * @param ServicesTable $ServicesTable
     * @param HoststatusTableInterface $HoststatusTable
     * @param ServicestatusTableInterface $Servicestatus
     * @param array $host
     * @return array
     */
    private function getHostSummary(HoststatusTableInterface $HoststatusTable, array $host) {
        $HoststatusFields = new HoststatusFields(new DbBackend());
        $HoststatusFields
            ->currentState();

        $hoststatus = $HoststatusTable->byUuid($host['uuid'], $HoststatusFields);
        if (empty($hoststatus)) {
            $hoststatus['Hoststatus'] = [];
        }

        $hoststatus = new Hoststatus($hoststatus['Hoststatus']);
        $hoststatusAsString = $hoststatus->HostStatusAsString();

        $hoststatus = [
            'currentState' => $hoststatus->toArray()['currentState'],
            'humanState'   => $hoststatusAsString
        ];

        return [
            'Hoststatus' => $hoststatus
        ];
    }

    /**
     * USE WITH CAUTION!!
     * NO CONTAINER CHECKING!!
     *
     * @param $id
     * @return array
     */
    private function getServiceForStatuspage($id) {
        /** @var $ServicesTable ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');
        $service = $ServicesTable->get($id, [
            'contain' => [
                'Hosts'            => [
                    'fields' => [
                        'Hosts.id',
                        'Hosts.uuid',
                        'Hosts.name'
                    ],
                    'HostsToContainersSharing',
                ],
                'Servicetemplates' => [
                    'fields' => [
                        'Servicetemplates.name'
                    ]
                ]
            ],
            'fields'  => [
                'Services.id',
                'Services.name',
                'Services.uuid',
                'Services.description',
                'Services.disabled'
            ],
        ])->toArray();
        return $service;
    }

    /**
     * Retrieve status for give service
     *
     * @param ServicestatusTableInterface $Servicestatus
     * @param array $service
     * @return array[]
     */
    private function getServiceSummary(ServicestatusTableInterface $Servicestatus, array $service) {
        $ServicestatusFields = new ServicestatusFields(new DbBackend());
        $ServicestatusFields
            ->currentState();

        $ServicestatusConditions = new ServicestatusConditions(new DbBackend());

        $Servicestatus = $Servicestatus->byUuid($service['uuid'], $ServicestatusFields, $ServicestatusConditions);
        if (!empty($Servicestatus)) {
            $Servicestatus = new Servicestatus(
                $Servicestatus['Servicestatus']
            );
        } else {
            $Servicestatus = new Servicestatus(
                ['Servicestatus' => []]
            );
        }

        $Servicestatus = [
            'currentState' => $Servicestatus->toArray()['currentState'],
            'humanState'   => $Servicestatus->toArray()['humanState']
        ];

        return [
            'Servicestatus' => $Servicestatus
        ];
    }

    /**
     * USE WITH CAUTION!!
     * NO CONTAINER CHECKING
     * Status function for the public state of statuspage hostgroups.
     *
     * @param $id
     * @return array|\Cake\Datasource\EntityInterface
     */
    private function getHostsByHostgroupForStatuspages($id) {
        /** @var $HostgroupsTable HostgroupsTable */
        $HostgroupsTable = TableRegistry::getTableLocator()->get('Hostgroups');

        $where = [
            'Hostgroups.id' => $id
        ];

        $hostgroup = $HostgroupsTable->find()
            ->select([
                'Hostgroups.id',
                'Hostgroups.description',
                'Containers.name'
            ])
            ->contain([
                'Containers',
                'Hosts'         => function (Query $q) {
                    return $q->enableAutoFields(false)
                        ->select([
                            'Hosts.id',
                            'Hosts.uuid',
                            'Hosts.name',
                            'Hosts.description'
                        ]);
                },
                'Hosttemplates' => function (Query $q) {
                    return $q->enableAutoFields(false)
                        ->select([
                            'id'
                        ])
                        ->contain([
                            'Hosts' => function (Query $query) {
                                $query
                                    ->disableAutoFields()
                                    ->select([
                                        'Hosts.id',
                                        'Hosts.uuid',
                                        'Hosts.name',
                                        'Hosts.hosttemplate_id'
                                    ]);
                                $query
                                    ->leftJoinWith('Hostgroups')
                                    ->whereNull('Hostgroups.id');
                                return $query;
                            }
                        ]);
                }
            ])
            ->where($where)
            ->disableHydration()
            ->firstOrFail();

        return $hostgroup;
    }

    /**
     * @param HostsTable $HostsTable
     * @param ServicesTable $ServicesTable
     * @param HoststatusTableInterface $HoststatusTable
     * @param ServicestatusTableInterface $ServicestatusTable
     * @param array $hostgroup
     * @return array[]
     */
    private function getHostgroupSummary(HostsTable $HostsTable, ServicesTable $ServicesTable, HoststatusTableInterface $HoststatusTable, ServicestatusTableInterface $ServicestatusTable, array $hostgroup) {
        $HoststatusFields = new HoststatusFields(new DbBackend());
        $HoststatusFields
            ->currentState()
            ->isHardstate()
            ->output()
            ->perfdata()
            ->currentCheckAttempt()
            ->maxCheckAttempts()
            ->lastCheck()
            ->nextCheck()
            ->lastStateChange()
            ->scheduledDowntimeDepth()
            ->problemHasBeenAcknowledged();

        $hostUuids = Hash::extract($hostgroup['hosts'], '{n}.uuid');

        $hoststatusByUuids = $HoststatusTable->byUuid($hostUuids, $HoststatusFields);
        $hostStateSummary = $HostsTable->getHostStateSummary($hoststatusByUuids, false);

        $ServicestatusFieds = new ServicestatusFields(new DbBackend());
        $ServicestatusFieds
            ->currentState()
            ->isHardstate()
            ->scheduledDowntimeDepth()
            ->problemHasBeenAcknowledged()
            ->output();
        $ServicestatusConditions = new ServicestatusConditions(new DbBackend());


        if (empty($hoststatusByUuids)) {
            $hoststatusByUuids['Hoststatus'] = [];
        }
        $hoststatusResult = [];
        $cumulatedHostState = -1;
        $cumulatedServiceState = null;
        $allServiceStatus = [];
        $totalServiceStateSummary = [
            'state' => [
                0 => 0,
                1 => 0,
                2 => 0,
                3 => 0,
            ],
            'total' => 0
        ];

        $hostIdsGroupByState = [
            0 => [],
            1 => [],
            2 => []
        ];

        $serviceIdsGroupByState = [
            0 => [],
            1 => [],
            2 => [],
            3 => []
        ];


        foreach ($hostgroup['hosts'] as $host) {
            $Host = new \itnovum\openITCOCKPIT\Core\Views\Host(['Host' => $host]);
            if (isset($hoststatusByUuids[$Host->getUuid()])) {
                $Hoststatus = new Hoststatus(
                    $hoststatusByUuids[$Host->getUuid()]['Hoststatus']
                );
                $hostIdsGroupByState[$Hoststatus->currentState()][] = $host['id'];

                if ($Hoststatus->currentState() > $cumulatedHostState) {
                    $cumulatedHostState = $Hoststatus->currentState();
                }
            } else {
                $Hoststatus = new Hoststatus(
                    ['Hoststatus' => []]
                );
            }
            $services = $ServicesTable->find()
                ->join([
                    [
                        'table'      => 'servicetemplates',
                        'type'       => 'INNER',
                        'alias'      => 'Servicetemplates',
                        'conditions' => 'Servicetemplates.id = Services.servicetemplate_id',
                    ],
                ])
                ->select([
                    'Services.id',
                    'Services.name',
                    'Services.uuid',
                    'Servicetemplates.name'
                ])
                ->where([
                    'Services.host_id'  => $Host->getId(),
                    'Services.disabled' => 0
                ])->all()->toArray();

            $servicesUuids = Hash::extract($services, '{n}.uuid');
            $servicesIdsByUuid = Hash::combine($services, '{n}.uuid', '{n}.id');
            $servicestatusResults = $ServicestatusTable->byUuid($servicesUuids, $ServicestatusFieds, $ServicestatusConditions);

            $serviceIdsGroupByStatePerHost = [
                0 => [],
                1 => [],
                2 => [],
                3 => []
            ];
            foreach ($servicestatusResults as $serviceUuid => $servicestatusResult) {
                $allServiceStatus[] = $servicestatusResult['Servicestatus']['current_state'];
                $serviceIdsGroupByState[$servicestatusResult['Servicestatus']['current_state']][] = $servicesIdsByUuid[$serviceUuid];
                $serviceIdsGroupByStatePerHost[$servicestatusResult['Servicestatus']['current_state']][] = $servicesIdsByUuid[$serviceUuid];
            }

            $ServicestatusObjects = Servicestatus::fromServicestatusByUuid($servicestatusResults);
            $serviceStateSummary = ServiceStateSummary::getServiceStateSummary($ServicestatusObjects, false);

            $hoststatusResult[] = [
                'Host'                   => $Host->toArray(),
                'Hoststatus'             => $Hoststatus->toArray(),
                'ServiceSummary'         => $serviceStateSummary,
                'ServiceIdsGroupByState' => $serviceIdsGroupByStatePerHost
            ];

            foreach ($serviceStateSummary['state'] as $state => $stateValue) {
                $totalServiceStateSummary['state'][$state] += $stateValue;
            }
            $totalServiceStateSummary['total'] += $serviceStateSummary['total'];
        }
        $hoststatusResult = Hash::sort($hoststatusResult, '{s}.Hoststatus.currentState', 'desc');


        if ($cumulatedHostState > 0) {
            $CumulatedHostStatus = new Hoststatus([
                'current_state' => $cumulatedHostState
            ]);
            $CumulatedHumanState = [
                'stateType'    => 'host',
                'currentState' => $CumulatedHostStatus->toArray()['currentState'],
                'humanState'   => $CumulatedHostStatus->toArray()['humanState']
            ];
        } else {
            if (!empty($allServiceStatus)) {
                $cumulatedServiceState = (int)max($allServiceStatus);
            }
            $CumulatedServiceStatus = new Servicestatus([
                'current_state' => $cumulatedServiceState
            ]);
            $CumulatedHumanState = [
                'stateType'    => 'service',
                'currentState' => $CumulatedServiceStatus->toArray()['currentState'],
                'humanState'   => $CumulatedServiceStatus->toArray()['humanState']
            ];
        }
        return [
            'CumulatedState' => $CumulatedHumanState,
        ];
    }

    /**
     * USE WITH CAUTION!!
     * NO CONTAINER CHECKING!!
     * Status function for the public state of statuspage servicegroups.
     *
     * @param $id
     * @return array
     */
    private function getServicegroupByIdForStatuspages($id) {
        /** @var $ServicegroupsTable ServicegroupsTable */
        $ServicegroupsTable = TableRegistry::getTableLocator()->get('Servicegroups');

        $query = $ServicegroupsTable->find()
            ->contain([
                'Containers' => function (Query $q) {
                    $q->select([
                        'Containers.id',
                        'Containers.name'
                    ]);
                    return $q;
                },
                'Services'   => function (Query $q) {
                    return $q->contain([
                        'Hosts' => function (Query $q) {
                            return $q->contain([
                                'HostsToContainersSharing'
                            ])->select([
                                'Hosts.id',
                                'Hosts.uuid',
                                'Hosts.name'
                            ])->where([
                                'Hosts.disabled' => 0
                            ]);
                        }
                    ])->select([
                        'Services.id',
                        'Services.uuid',
                        'Services.name'
                    ])->where([
                        'Services.disabled' => 0
                    ]);
                },
            ])
            ->where([
                'Servicegroups.id' => $id
            ])
            ->select([
                'Servicegroups.id',
                'Servicegroups.description'
            ]);

        $result = $query->first();
        if (empty($result)) {
            return [];
        }
        return $result->toArray();
    }

    /**
     * @param ServicesTable $ServicesTable
     * @param ServicestatusTableInterface $ServicestatusTable
     * @param array $servicegroup
     * @return array[]
     */
    private function getServicegroupSummary(ServicesTable $ServicesTable, ServicestatusTableInterface $ServicestatusTable, array $servicegroup) {
        $ServicestatusFields = new ServicestatusFields(new DbBackend());
        $ServicestatusFields
            ->currentState()
            ->isHardstate()
            ->output()
            ->scheduledDowntimeDepth()
            ->problemHasBeenAcknowledged();

        $serviceUuids = Hash::extract($servicegroup['services'], '{n}.uuid');
        $ServicestatusConditions = new ServicestatusConditions(new DbBackend());

        $servicestatusResults = $ServicestatusTable->byUuid($serviceUuids, $ServicestatusFields, $ServicestatusConditions);
        $ServicestatusObjects = Servicestatus::fromServicestatusByUuid($servicestatusResults);
        $serviceStateSummary = ServiceStateSummary::getServiceStateSummary($ServicestatusObjects, false);
        $serviceIdsGroupByState = [
            0 => [],
            1 => [],
            2 => [],
            3 => []
        ];
        $cumulatedServiceState = null;
        $servicesResult = [];
        foreach ($servicegroup['services'] as $service) {
            $Service = new \itnovum\openITCOCKPIT\Core\Views\Service([
                'Service' => $service,
            ]);
            $Host = new \itnovum\openITCOCKPIT\Core\Views\Host($service['host']);

            if (isset($servicestatusResults[$Service->getUuid()])) {
                $Servicestatus = new Servicestatus(
                    $servicestatusResults[$Service->getUuid()]['Servicestatus']
                );
                $serviceIdsGroupByState[$Servicestatus->currentState()][] = $service['id'];

            } else {
                $Servicestatus = new Servicestatus(
                    ['Servicestatus' => []]
                );
            }
            $servicesResult[] = [
                'Service'       => $Service->toArray(),
                'Servicestatus' => $Servicestatus->toArray(),
                'Host'          => $Host->toArray()
            ];
        }
        $servicesResult = Hash::sort($servicesResult, '{s}.Servicestatus.currentState', 'desc');
        if (!empty($servicestatusResults)) {
            $cumulatedServiceState = Hash::apply($servicestatusResults, '{s}.Servicestatus.current_state', 'max');
        }
        $CumulatedServiceStatus = new Servicestatus([
            'current_state' => $cumulatedServiceState
        ]);

        $CumulatedState = [
            'currentState' => $CumulatedServiceStatus->toArray()['currentState'],
            'humanState'   => $CumulatedServiceStatus->toArray()['humanState']
        ];

        return [
            'CumulatedState' => $CumulatedState,
        ];
    }

    public function getDowntimesForStatuspageObjects($statuspageData) {
       // debug($statuspageData);
        /** @var HostsTable $HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        /** @var ServicesTable $ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');
        /** @var HostgroupsTable $HostgroupsTable */
        $HostgroupsTable = TableRegistry::getTableLocator()->get('Hostgroups');
        /** @var ServicegroupsTable $ServicegroupsTable */
        $ServicegroupsTable = TableRegistry::getTableLocator()->get('Servicegroups');

        foreach ($statuspageData as $key => $statuspageItem) {

        }
    }
}
