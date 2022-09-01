<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Lib\Interfaces\AcknowledgementHostsTableInterface;
use App\Lib\Interfaces\AcknowledgementServicesTableInterface;
use App\Lib\Interfaces\DowntimehistoryHostsTableInterface;
use App\Lib\Interfaces\DowntimehistoryServicesTableInterface;
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
use itnovum\openITCOCKPIT\Core\DowntimeHostConditions;
use itnovum\openITCOCKPIT\Core\Hoststatus;
use itnovum\openITCOCKPIT\Core\HoststatusFields;
use itnovum\openITCOCKPIT\Core\Servicestatus;
use itnovum\openITCOCKPIT\Core\ServicestatusConditions;
use itnovum\openITCOCKPIT\Core\ServicestatusFields;
use itnovum\openITCOCKPIT\Core\Views\ServiceStateSummary;
use itnovum\openITCOCKPIT\Core\Views\UserTime;
use itnovum\openITCOCKPIT\Filter\StatuspagesFilter;
use Statusengine2Module\Model\Table\HoststatusTable;

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

        $validator
            ->boolean('show_comments')
            ->notEmptyString('show_comments');

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
     * @param $statuspageId
     * @param $MY_RIGHTS
     * @return bool
     */
    public function allowedByStatuspageId($statuspageId = [], $MY_RIGHTS): bool {
        if (empty($statuspageId)) {
            return false;
        }

        if (!$this->existsById($statuspageId)) {
            return false;
        }

        $query = $this->get($statuspageId, [
            'contain' => ['Containers']
        ]);

        $result = null;
        if (!empty($query)) {
            $result = $query->toArray();
        }

        $containerIds = null;
        if (!empty($result) && !empty($result['containers'])) {
            $containerIds = Hash::extract($result, 'containers.{n}.id');
        }

        if (!empty($containerIds)) {
            if (empty(array_diff($containerIds, $MY_RIGHTS))) {
                return true;
            }
        }

        return false;
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
                    ->select(['id', 'uuid', 'name']);
            })
            ->contain('Services', function (Query $q) {
                return $q
                    ->select([
                        'id',
                        'uuid',
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
     * @param $conditions
     * @param $public
     * @return array
     */
    public function getStatuspageObjectsForView($id = null, $DbBackend = null, $conditions = [], bool $public = false) {
        /** @var ServicesTable $ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');

        /** @var HoststatusTable $HoststatusTable */
        $HoststatusTable = $DbBackend->getHoststatusTable();
        /** @var ServicestatusTable $ServicestatusTable */
        $ServicestatusTable = $DbBackend->getServicestatusTable();
        /** @var AcknowledgementHostsTableInterface $AcknowledgementHostsTable */
        $AcknowledgementHostsTable = $DbBackend->getAcknowledgementHostsTable();
        /** @var DowntimehistoryHostsTableInterface $DowntimehistoryHostsTable */
        $DowntimehistoryHostsTable = $DbBackend->getDowntimehistoryHostsTable();
        /** @var AcknowledgementServicesTableInterface $AcknowledgementServicesTable */
        $AcknowledgementServicesTable = $DbBackend->getAcknowledgementServicesTable();
        /** @var DowntimehistoryServicesTableInterface $DowntimehistoryServicesTable */
        $DowntimehistoryServicesTable = $DbBackend->getDowntimehistoryServicesTable();


        $statuspageData = $this->getStatuspageObjects($id, $conditions);

        $statuspageForView = [
            'statuspage'    => [
                'name'        => $statuspageData['name'],
                'description' => $statuspageData['description'],
                'public'      => $statuspageData['public']
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
                        $host['name'] = (!empty($item['_joinData']['display_name']) ? $item['_joinData']['display_name'] : $item['name']);

                        $hoststatus = $this->getHostSummary(
                            $ServicesTable,
                            $ServicestatusTable,
                            $AcknowledgementServicesTable,
                            $DowntimehistoryServicesTable,
                            $HoststatusTable,
                            $AcknowledgementHostsTable,
                            $DowntimehistoryHostsTable,
                            $host
                        );

                        $statuspageForView[$key][$subKey]['name'] = (!empty($item['_joinData']['display_name']) ? $item['_joinData']['display_name'] : $item['name']);
                        if (!empty($hoststatus['Hoststatus'])) {
                            $statuspageForView[$key][$subKey]['currentState'] = $hoststatus['Hoststatus']['currentState'];
                            $statuspageForView[$key][$subKey]['humanState'] = $hoststatus['Hoststatus']['humanState'];
                            $statuspageForView[$key][$subKey]['inDowntime'] = $hoststatus['Hoststatus']['inDowntime'];
                            $statuspageForView[$key][$subKey]['acknowledged'] = $hoststatus['Hoststatus']['acknowledged'];
                            $statuspageForView[$key][$subKey]['parentName'] = $hoststatus['Hoststatus']['parentName'];
                            $statuspageForView[$key][$subKey]['parentType'] = $hoststatus['Hoststatus']['parentType'];

                            if ($hoststatus['Hoststatus']['acknowledged'] === 1 && !empty($hoststatus['Hoststatus']['acknowledgement'])) {
                                $statuspageForView[$key][$subKey]['acknowlegement']['entry_time'] = $hoststatus['Hoststatus']['acknowledgement']['entry_time'];
                                if (!$public) {
                                    //do not show user entered comment in public view
                                    $statuspageForView[$key][$subKey]['acknowlegement']['comment_data'] = $hoststatus['Hoststatus']['acknowledgement']['comment_data'];
                                }
                            }

                            if (!empty($hoststatus['Hoststatus']['downtime'])) {
                                $statuspageForView[$key][$subKey]['downtime']['entry_time'] = $hoststatus['Hoststatus']['downtime']['entry_time'];
                                $statuspageForView[$key][$subKey]['downtime']['scheduled_start_time'] = $hoststatus['Hoststatus']['downtime']['scheduled_start_time'];
                                $statuspageForView[$key][$subKey]['downtime']['scheduled_end_time'] = $hoststatus['Hoststatus']['downtime']['scheduled_end_time'];
                                if (!$public) {
                                    //do not show user entered comment in public view
                                    $statuspageForView[$key][$subKey]['downtime']['comment_data'] = $hoststatus['Hoststatus']['downtime']['comment_data'];
                                }
                            }

                            //Service Summary for Hosts
                            if (!empty($hoststatus['Hoststatus']['serviceSummary'])) {
                                foreach ($hoststatus['Hoststatus']['serviceSummary'] as $serviceSummaryForHost) {
                                    $currentServiceSummary = [
                                        'currentState'    => $serviceSummaryForHost['Servicestatus']['currentState'],
                                        'humanState'      => $serviceSummaryForHost['Servicestatus']['humanState'],
                                        'inDowntime'      => $serviceSummaryForHost['Servicestatus']['inDowntime'],
                                        'acknowledged'    => $serviceSummaryForHost['Servicestatus']['acknowledged'],
                                        'acknowledgement' => [],
                                        'downtime'        => [],
                                        'parentName'      => $serviceSummaryForHost['Servicestatus']['parentName'],
                                        'parentType'      => $serviceSummaryForHost['Servicestatus']['parentType'],
                                    ];

                                    if (!empty($serviceSummaryForHost['Servicestatus']['acknowledgement'])) {
                                        $currentServiceSummary['acknowledgement']['entry_time'] = $serviceSummaryForHost['Servicestatus']['acknowledgement']['entry_time'];
                                        if (!$public) {
                                            //do not show user entered comment in public view
                                            $currentServiceSummary['acknowledgement']['comment_data'] = $serviceSummaryForHost['Servicestatus']['acknowledgement']['comment_data'];
                                        }
                                    }

                                    if (!empty($serviceSummaryForHost['Servicestatus']['downtime'])) {
                                        $currentServiceSummary['downtime']['entry_time'] = $serviceSummaryForHost['Servicestatus']['downtime']['entry_time'];
                                        $currentServiceSummary['downtime']['scheduled_start_time'] = $serviceSummaryForHost['Servicestatus']['downtime']['scheduled_start_time'];
                                        $currentServiceSummary['downtime']['scheduled_end_time'] = $serviceSummaryForHost['Servicestatus']['downtime']['scheduled_end_time'];
                                        if (!$public) {
                                            //do not show user entered comment in public view
                                            $currentServiceSummary['downtime']['comment_data'] = $serviceSummaryForHost['Servicestatus']['downtime']['comment_data'];
                                        }
                                    }

                                    $statuspageForView[$key][$subKey]['serviceSummary'][] = $currentServiceSummary;
                                    unset($currentServiceSummary);
                                }

                            }

                            if (isset($hoststatus['Hoststatus']['cumulatedServiceState'])) {
                                $statuspageForView[$key][$subKey]['cumulatedServiceState'] = $hoststatus['Hoststatus']['cumulatedServiceState'];
                            }

                            if (isset($hoststatus['Hoststatus']['serviceAcknowledged'])) {
                                $statuspageForView[$key][$subKey]['serviceAcknowledged'] = $hoststatus['Hoststatus']['serviceAcknowledged'];
                            }

                            if (isset($hoststatus['Hoststatus']['serviceInDowntime'])) {
                                $statuspageForView[$key][$subKey]['serviceInDowntime'] = $hoststatus['Hoststatus']['serviceInDowntime'];
                            }

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
                            $AcknowledgementServicesTable,
                            $DowntimehistoryServicesTable,
                            $service
                        );

                        $statuspageForView[$key][$subKey]['name'] = (!empty($item['_joinData']['display_name']) ? $item['_joinData']['display_name'] : $item['servicename']);
                        if (isset($servicestatus['Servicestatus'])) {
                            $statuspageForView[$key][$subKey]['currentState'] = $servicestatus['Servicestatus']['currentState'];
                            $statuspageForView[$key][$subKey]['humanState'] = $servicestatus['Servicestatus']['humanState'];
                            $statuspageForView[$key][$subKey]['inDowntime'] = $servicestatus['Servicestatus']['inDowntime'];
                            $statuspageForView[$key][$subKey]['acknowledged'] = $servicestatus['Servicestatus']['acknowledged'];
                            $statuspageForView[$key][$subKey]['parentName'] = $servicestatus['Servicestatus']['parentName'];
                            $statuspageForView[$key][$subKey]['parentType'] = $servicestatus['Servicestatus']['parentType'];

                            if ($servicestatus['Servicestatus']['acknowledged'] === 1 && !empty($servicestatus['Servicestatus']['acknowledgement'])) {
                                $statuspageForView[$key][$subKey]['acknowlegement']['entry_time'] = $servicestatus['Servicestatus']['acknowledgement']['entry_time'];
                                if (!$public) {
                                    //do not show user entered comment in public view
                                    $statuspageForView[$key][$subKey]['acknowlegement']['comment_data'] = $servicestatus['Servicestatus']['acknowledgement']['comment_data'];
                                }
                            }

                            if (!empty($servicestatus['Servicestatus']['downtime'])) {
                                $statuspageForView[$key][$subKey]['downtime']['entry_time'] = $servicestatus['Servicestatus']['downtime']['entry_time'];
                                $statuspageForView[$key][$subKey]['downtime']['scheduled_start_time'] = $servicestatus['Servicestatus']['downtime']['scheduled_start_time'];
                                $statuspageForView[$key][$subKey]['downtime']['scheduled_end_time'] = $servicestatus['Servicestatus']['downtime']['scheduled_end_time'];
                                if (!$public) {
                                    //do not show user entered comment in public view
                                    $statuspageForView[$key][$subKey]['downtime']['comment_data'] = $servicestatus['Servicestatus']['downtime']['comment_data'];
                                }
                            }
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

                    $hostgroup['container']['name'] = (!empty($item['_joinData']['display_name']) ? $item['_joinData']['display_name'] : $item['Containers']['name']);

                    $hostgroupstatus = $this->getHostgroupSummary(
                        $ServicesTable,
                        $ServicestatusTable,
                        $AcknowledgementServicesTable,
                        $DowntimehistoryServicesTable,
                        $HoststatusTable,
                        $AcknowledgementHostsTable,
                        $DowntimehistoryHostsTable,
                        $hostgroup
                    );

                    $statuspageForView[$key][$subKey]['name'] = (!empty($item['_joinData']['display_name']) ? $item['_joinData']['display_name'] : $item['Containers']['name']);
                    if (isset($hostgroupstatus['CumulatedState'])) {
                        $statuspageForView[$key][$subKey]['currentState'] = $hostgroupstatus['CumulatedState']['currentState'];
                        $statuspageForView[$key][$subKey]['humanState'] = $hostgroupstatus['CumulatedState']['humanState'];
                        $statuspageForView[$key][$subKey]['inDowntime'] = $hostgroupstatus['CumulatedState']['inDowntime'];
                        $statuspageForView[$key][$subKey]['acknowledged'] = $hostgroupstatus['CumulatedState']['acknowledged'];
                        $statuspageForView[$key][$subKey]['parentName'] = '';
                        $statuspageForView[$key][$subKey]['parentType'] = '';
                    }

                    if (!empty($hostgroupstatus['CumulatedState']['hostSummary'])) {
                        foreach ($hostgroupstatus['CumulatedState']['hostSummary'] as $hostSummaryforHostgroup) {
                            $currentHostSummary = [
                                'currentState'          => $hostSummaryforHostgroup['currentState'],
                                'humanState'            => $hostSummaryforHostgroup['humanState'],
                                'inDowntime'            => $hostSummaryforHostgroup['inDowntime'],
                                'acknowledged'          => $hostSummaryforHostgroup['acknowledged'],
                                'acknowledgement'       => [],
                                'downtime'              => [],
                                'serviceSummary'        => [],
                                'cumulatedServiceState' => null,
                                'serviceAcknowledged'   => null,
                                'serviceInDowntime'     => null,
                                'parentName'            => $hostSummaryforHostgroup['parentName'],
                                'parentType'            => $hostSummaryforHostgroup['parentType'],
                            ];

                            if (!empty($hostSummaryforHostgroup['downtime'])) {
                                $currentHostSummary['downtime']['entry_time'] = $hostSummaryforHostgroup['downtime']['entry_time'];
                                $currentHostSummary['downtime']['scheduled_start_time'] = $hostSummaryforHostgroup['downtime']['scheduled_start_time'];
                                $currentHostSummary['downtime']['scheduled_end_time'] = $hostSummaryforHostgroup['downtime']['scheduled_end_time'];
                                if (!$public) {
                                    //do not show user entered comment in public view
                                    $currentHostSummary['downtime']['comment_data'] = $hostSummaryforHostgroup['downtime']['comment_data'];
                                }
                            }

                            if (!empty($hostSummaryforHostgroup['acknowledgement'])) {
                                $currentHostSummary['acknowledgement']['entry_time'] = $hostSummaryforHostgroup['acknowledgement']['entry_time'];
                                if (!$public) {
                                    //do not show user entered comment in public view
                                    $currentHostSummary['acknowledgement']['comment_data'] = $hostSummaryforHostgroup['acknowledgement']['comment_data'];
                                }
                            }

                            if (!empty($hostSummaryforHostgroup['serviceSummary'])) {
                                foreach ($hostSummaryforHostgroup['serviceSummary'] as $serviceSummaryforHostgroup) {

                                    $currentServiceSummary = [
                                        'currentState'    => $serviceSummaryforHostgroup['Servicestatus']['currentState'],
                                        'humanState'      => $serviceSummaryforHostgroup['Servicestatus']['humanState'],
                                        'inDowntime'      => $serviceSummaryforHostgroup['Servicestatus']['inDowntime'],
                                        'acknowledged'    => $serviceSummaryforHostgroup['Servicestatus']['acknowledged'],
                                        'acknowledgement' => [],
                                        'downtime'        => [],
                                        'parentName'      => $serviceSummaryforHostgroup['Servicestatus']['parentName'],
                                        'parentType'      => $serviceSummaryforHostgroup['Servicestatus']['parentType']
                                    ];

                                    if (!empty($serviceSummaryforHostgroup['Servicestatus']['downtime'])) {
                                        $currentServiceSummary['downtime']['entry_time'] = $serviceSummaryforHostgroup['Servicestatus']['downtime']['entry_time'];
                                        $currentServiceSummary['downtime']['scheduled_start_time'] = $serviceSummaryforHostgroup['Servicestatus']['downtime']['scheduled_start_time'];
                                        $currentServiceSummary['downtime']['scheduled_end_time'] = $serviceSummaryforHostgroup['Servicestatus']['downtime']['scheduled_end_time'];
                                        if (!$public) {
                                            //do not show user entered comment in public view
                                            $currentServiceSummary['downtime']['comment_data'] = $serviceSummaryforHostgroup['Servicestatus']['downtime']['comment_data'];
                                        }
                                    }

                                    if (!empty($serviceSummaryforHostgroup['Servicestatus']['acknowledgement'])) {
                                        $currentServiceSummary['acknowledgement']['entry_time'] = $serviceSummaryforHostgroup['Servicestatus']['acknowledgement']['entry_time'];
                                        if (!$public) {
                                            //do not show user entered comment in public view
                                            $currentServiceSummary['acknowledgement']['comment_data'] = $serviceSummaryforHostgroup['Servicestatus']['acknowledgement']['comment_data'];
                                        }
                                    }
                                    $currentHostSummary['serviceSummary'][] = $currentServiceSummary;
                                    unset($currentServiceSummary);
                                }
                            }
                            if (isset($hostSummaryforHostgroup['cumulatedServiceState'])) {
                                $currentHostSummary['cumulatedServiceState'] = $hostSummaryforHostgroup['cumulatedServiceState'];
                            }

                            if (isset($hostSummaryforHostgroup['serviceAcknowledged'])) {
                                $currentHostSummary['serviceAcknowledged'] = $hostSummaryforHostgroup['serviceAcknowledged'];
                            }

                            if (isset($hostSummaryforHostgroup['serviceInDowntime'])) {
                                $currentHostSummary['serviceInDowntime'] = $hostSummaryforHostgroup['serviceInDowntime'];
                            }
                            $statuspageForView[$key][$subKey]['hostSummary'][] = $currentHostSummary;
                            unset($currentHostSummary);
                        }
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

                    $servicegroup['container']['name'] = (!empty($item['_joinData']['display_name']) ? $item['_joinData']['display_name'] : $item['Containers']['name']);
                    $servicegroupstatus = $this->getServicegroupSummary(
                        $ServicesTable,
                        $ServicestatusTable,
                        $AcknowledgementServicesTable,
                        $DowntimehistoryServicesTable,
                        $servicegroup
                    );

                    $statuspageForView[$key][$subKey]['name'] = (!empty($item['_joinData']['display_name']) ? $item['_joinData']['display_name'] : $item['Containers']['name']);
                    if (isset($servicegroupstatus['CumulatedState'])) {
                        $statuspageForView[$key][$subKey]['currentState'] = $servicegroupstatus['CumulatedState']['currentState'];
                        $statuspageForView[$key][$subKey]['humanState'] = $servicegroupstatus['CumulatedState']['humanState'];
                        $statuspageForView[$key][$subKey]['inDowntime'] = $servicegroupstatus['CumulatedState']['inDowntime'];
                        $statuspageForView[$key][$subKey]['acknowledged'] = $servicegroupstatus['CumulatedState']['acknowledged'];
                        $statuspageForView[$key][$subKey]['parentName'] = '';
                        $statuspageForView[$key][$subKey]['parentType'] = '';

                        if (!empty($servicegroupstatus['CumulatedState']['serviceSummary'])) {
                            foreach ($servicegroupstatus['CumulatedState']['serviceSummary'] as $serviceSummaryForServicegroup) {
                                $currentServiceSummary = [
                                    'currentState'    => $serviceSummaryForServicegroup['currentState'],
                                    'humanState'      => $serviceSummaryForServicegroup['humanState'],
                                    'inDowntime'      => $serviceSummaryForServicegroup['inDowntime'],
                                    'acknowledged'    => $serviceSummaryForServicegroup['acknowledged'],
                                    'acknowledgement' => [],
                                    'downtime'        => [],
                                    'parentName'      => $serviceSummaryForServicegroup['parentName'],
                                    'parentType'      => $serviceSummaryForServicegroup['parentType']
                                ];

                                if (!empty($serviceSummaryForServicegroup['acknowledgement'])) {
                                    $currentServiceSummary['acknowledgement']['entry_time'] = $serviceSummaryForServicegroup['acknowledgement']['entry_time'];
                                    if (!$public) {
                                        //do not show user entered comment in public view
                                        $currentServiceSummary['acknowledgement']['comment_data'] = $serviceSummaryForServicegroup['acknowledgement']['comment_data'];
                                    }
                                }

                                if (!empty($serviceSummaryForServicegroup['downtime'])) {
                                    $currentServiceSummary['downtime']['entry_time'] = $serviceSummaryForServicegroup['downtime']['entry_time'];
                                    $currentServiceSummary['downtime']['scheduled_start_time'] = $serviceSummaryForServicegroup['downtime']['scheduled_start_time'];
                                    $currentServiceSummary['downtime']['scheduled_end_time'] = $serviceSummaryForServicegroup['downtime']['scheduled_end_time'];
                                    if (!$public) {
                                        //do not show user entered comment in public view
                                        $currentServiceSummary['downtime']['comment_data'] = $serviceSummaryForServicegroup['downtime']['comment_data'];
                                    }
                                }

                                $statuspageForView[$key][$subKey]['serviceSummary'][] = $currentServiceSummary;
                                unset($currentServiceSummary);
                            }

                        }
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
            if (!empty($statuspage)) {
                foreach ($statuspage as $subKey => $item) {
                    $itemState[$subKey] = $item['currentState'];
                }
                $worstItemStatusKey = array_keys($itemState, max($itemState))[0];
                $states[$key] = $statuspage[$worstItemStatusKey];
            }
        }

        $tmpStates = [];
        foreach ($states as $key => $item) {
            if (!empty($item)) {
                $tmpStates[$key] = $item['currentState'];
            }
        }
        if (!empty(($tmpStates))) {
            $worstTmpItemStatusKey = array_keys($tmpStates, max($tmpStates))[0];
            $stateType = $worstTmpItemStatusKey;
            $worstState = [
                'state'      => $states[$worstTmpItemStatusKey]['currentState'], // int status
                'stateType'  => $stateType, // hosts or services
                'humanState' => $states[$worstTmpItemStatusKey]['humanState']
            ];
        } else {
            $worstState = [
                'state'      => 2, // int status
                'stateType'  => 'host', // hosts or services
                'humanState' => 'unreachable'
            ];
        }
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
     * @param ServicestatusTableInterface $ServicestatusTable
     * @param $AcknowledgementServicesTable
     * @param DowntimehistoryServicesTableInterface $DowntimehistoryServicesTable
     * @param HoststatusTableInterface $HoststatusTable
     * @param AcknowledgementHostsTableInterface $AcknowledgementHostsTable
     * @param DowntimehistoryHostsTableInterface $DowntimehistoryHostsTable
     * @param array $host
     * @param $parentName
     * @param $parentType
     * @return array[]
     */
    private function getHostSummary(ServicesTable $ServicesTable, ServicestatusTableInterface $ServicestatusTable, $AcknowledgementServicesTable, DowntimehistoryServicesTableInterface $DowntimehistoryServicesTable, HoststatusTableInterface $HoststatusTable, AcknowledgementHostsTableInterface $AcknowledgementHostsTable, DowntimehistoryHostsTableInterface $DowntimehistoryHostsTable, array $host, $parentName = '', $parentType = '') {
        $HoststatusFields = new HoststatusFields(new DbBackend());
        $HoststatusFields
            ->currentState()
            ->scheduledDowntimeDepth()
            ->problemHasBeenAcknowledged();

        $hoststatus = $HoststatusTable->byUuid($host['uuid'], $HoststatusFields);
        $acknowledgement = $AcknowledgementHostsTable->byHostUuid($host['uuid']);
        $downtime = $DowntimehistoryHostsTable->byHostUuid($host['uuid']);

        if (empty($hoststatus)) {
            $hoststatus['Hoststatus'] = [];
        }

        $hoststatus = new Hoststatus($hoststatus['Hoststatus']);

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
                'Services.host_id'  => $host['id'],
                'Services.disabled' => 0
            ])->all()->toArray();

        $parentNameForService = $parentName;
        if (empty($parentName)) {
            $parentNameForService = $host['name'];
        }

        $parentTypeForService = $parentType;
        if (empty($parentType)) {
            $parentTypeForService = 'host';
        }

        $allServiceStates = [];
        $allServiceDowntimes = [];
        $allServiceAcknowledgements = [];
        $ServiceSummary = [];

        foreach ($services as $service) {
            $service = [
                'id'   => $service['id'],
                'uuid' => $service['uuid'],
            ];

            $currentServiceSummary = $this->getServiceSummary($ServicestatusTable, $AcknowledgementServicesTable, $DowntimehistoryServicesTable, $service, $parentNameForService, $parentTypeForService);
            $ServiceSummary[] = $currentServiceSummary;
            $allServiceStates[] = $currentServiceSummary['Servicestatus']['currentState'];
            $allServiceDowntimes[] = $currentServiceSummary['Servicestatus']['inDowntime'];
            $allServiceAcknowledgements[] = $currentServiceSummary['Servicestatus']['acknowledged'];
        }

        $cumulatedServiceState = null;
        if (!empty($allServiceStates)) {
            $cumulatedServiceState = max($allServiceStates);
        }

        $serviceIsInDowntime = false;
        if (!empty($allServiceDowntimes)) {
            if (max($allServiceDowntimes) > 0) {
                $serviceIsInDowntime = true;
            }
        }

        $serviceAcknowledged = false;
        if (!empty($allServiceAcknowledgements)) {
            if (max($allServiceAcknowledgements) > 0) {
                $serviceAcknowledged = true;
            }
        }


        $hoststatusAsString = $hoststatus->HostStatusAsString();
        $hostIsInDowntime = $hoststatus->isInDowntime();
        $hostIsAckd = $hoststatus->isAcknowledged();

        $hoststatus = [
            'currentState'          => $hoststatus->toArray()['currentState'],
            'humanState'            => $hoststatusAsString,
            'inDowntime'            => (int)$hostIsInDowntime,
            'acknowledged'          => (int)$hostIsAckd,
            'acknowledgement'       => [],
            'downtime'              => [],
            'serviceSummary'        => [],
            'cumulatedServiceState' => null,
            'serviceAcknowledged'   => (int)$serviceAcknowledged,
            'serviceInDowntime'     => (int)$serviceIsInDowntime,
            'parentName'            => $parentName,
            'parentType'            => $parentType
        ];

        if (!empty($ServiceSummary)) {
            $hoststatus['serviceSummary'] = $ServiceSummary;
        }

        if (!empty($cumulatedServiceState)) {
            $hoststatus['cumulatedServiceState'] = $cumulatedServiceState;
        }

        if (!empty($acknowledgement)) {
            $acknowledgement = $acknowledgement->toArray();
            $hoststatus['acknowledgement'] = [
                'comment_data' => $acknowledgement['comment_data'],
                'entry_time'   => $acknowledgement['entry_time'],
            ];
        }

        if (!empty($downtime)) {
            $downtime = $downtime->toArray();
            $hoststatus['downtime'] = [
                'comment_data'         => $downtime['comment_data'],
                'scheduled_start_time' => $downtime['scheduled_start_time'],
                'scheduled_end_time'   => $downtime['scheduled_end_time'],
                'entry_time'           => $downtime['entry_time'],
            ];
        }
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
     * @param ServicestatusTableInterface $Servicestatus
     * @param $AcknowledgementServicesTable
     * @param DowntimehistoryServicesTableInterface $DowntimehistoryServicesTable
     * @param array $service
     * @param $parentName
     * @param $parentType
     * @return array[]
     */
    private function getServiceSummary(ServicestatusTableInterface $Servicestatus, $AcknowledgementServicesTable, DowntimehistoryServicesTableInterface $DowntimehistoryServicesTable, array $service, $parentName = '', $parentType = '') {
        $ServicestatusFields = new ServicestatusFields(new DbBackend());
        $ServicestatusFields
            ->currentState()
            ->scheduledDowntimeDepth()
            ->problemHasBeenAcknowledged();

        $ServicestatusConditions = new ServicestatusConditions(new DbBackend());

        $Servicestatus = $Servicestatus->byUuid($service['uuid'], $ServicestatusFields, $ServicestatusConditions);
        $acknowledgement = $AcknowledgementServicesTable->byServiceUuid($service['uuid']);
        $downtime = $DowntimehistoryServicesTable->byServiceUuid(($service['uuid']));

        if (!empty($Servicestatus)) {
            $Servicestatus = new Servicestatus(
                $Servicestatus['Servicestatus']
            );
        } else {
            $Servicestatus = new Servicestatus(
                ['Servicestatus' => []]
            );
        }

        $serviceIsInDowntime = $Servicestatus->isInDowntime();
        $serviceIsAcknowledged = $Servicestatus->isAcknowledged();
        $Servicestatus = [
            'currentState'    => $Servicestatus->toArray()['currentState'],
            'humanState'      => $Servicestatus->toArray()['humanState'],
            'inDowntime'      => (int)$serviceIsInDowntime,
            'acknowledged'    => (int)$serviceIsAcknowledged,
            'acknowledgement' => [],
            'downtime'        => [],
            'parentName'      => $parentName,
            'parentType'      => $parentType
        ];


        if (!empty($acknowledgement)) {
            $acknowledgement = $acknowledgement->toArray();
            $Servicestatus['acknowledgement'] = [
                'comment_data' => $acknowledgement['comment_data'],
                'entry_time'   => $acknowledgement['entry_time'],
            ];
        }
        if (!empty($downtime)) {
            $downtime = $downtime->toArray();
            $Servicestatus['downtime'] = [
                'comment_data'         => $downtime['comment_data'],
                'scheduled_start_time' => $downtime['scheduled_start_time'],
                'scheduled_end_time'   => $downtime['scheduled_end_time'],
                'entry_time'           => $downtime['entry_time'],
            ];
        }

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
    private function getHostgroupSummary(ServicesTable $ServicesTable, ServicestatusTableInterface $ServicestatusTable, $AcknowledgementServicesTable, DowntimehistoryServicesTableInterface $DowntimehistoryServicesTable, HoststatusTableInterface $HoststatusTable, AcknowledgementHostsTableInterface $AcknowledgementHostsTable, DowntimehistoryHostsTableInterface $DowntimehistoryHostsTable, array $hostgroup) {
        $HoststatusFields = new HoststatusFields(new DbBackend());
        $HoststatusFields
            ->currentState()
            ->scheduledDowntimeDepth()
            ->problemHasBeenAcknowledged();

        $hostUuids = Hash::extract($hostgroup['hosts'], '{n}.uuid');

        $ServicestatusFieds = new ServicestatusFields(new DbBackend());
        $ServicestatusFieds
            ->currentState()
            ->scheduledDowntimeDepth()
            ->problemHasBeenAcknowledged();

        $cumulatedServiceState = null;
        $allServiceStatus = [];
        $hostSummary = [];
        $parentName = $hostgroup['container']['name'];
        $parentType = 'hostgroup';
        foreach ($hostgroup['hosts'] as $host) {
            $Host = new \itnovum\openITCOCKPIT\Core\Views\Host(['Host' => $host]);

            $host = [
                'id'   => $Host->getId(),
                'uuid' => $Host->getUuid()
            ];

            $currentHostSummary = $this->getHostSummary(
                $ServicesTable,
                $ServicestatusTable,
                $AcknowledgementServicesTable,
                $DowntimehistoryServicesTable,
                $HoststatusTable,
                $AcknowledgementHostsTable,
                $DowntimehistoryHostsTable,
                $host,
                $parentName,
                $parentType
            );

            if (!empty($currentHostSummary)) {
                $currentHostSummary['Hoststatus']['parentName'] = $parentName;
                $currentHostSummary['Hoststatus']['parentType'] = $parentType;
                $hostSummary[] = $currentHostSummary['Hoststatus'];
            }
        }

        $cumulatedHostState = Hash::apply($hostSummary, '{n}.currentState', 'max');
        $hostDowntimes = Hash::extract($hostSummary, '{n}.inDowntime');
        $hostAcknowledgements = Hash::extract($hostSummary, '{n}.acknowledged');
        $allServiceStatus = Hash::extract($hostSummary, '{n}.serviceSummary.{n}.Servicestatus.currentState');


        $hostgroupDowntime = true;
        $hostgroupAck = true;
        if (in_array(false, $hostDowntimes)) {
            $hostgroupDowntime = false;
        }

        if (in_array(false, $hostAcknowledgements)) {
            $hostgroupAck = false;
        }

        $cumulatedState = null;
        $cumulatedStateType = 'host';
        $cumulatedHumanState = '';
        if ($cumulatedHostState > 0) {
            $CumulatedHostStatus = new Hoststatus([
                'current_state' => $cumulatedHostState
            ]);


            $cumulatedStateType = 'host';
            $cumulatedState = $CumulatedHostStatus->toArray()['currentState'];
            $cumulatedHumanState = $CumulatedHostStatus->toArray()['humanState'];
        } else {
            if (!empty($allServiceStatus)) {
                $cumulatedServiceState = (int)max($allServiceStatus);
            }
            $CumulatedServiceStatus = new Servicestatus([
                'current_state' => $cumulatedServiceState
            ]);
            $cumulatedStateType = 'service';
            $cumulatedState = $CumulatedServiceStatus->toArray()['currentState'];
            $cumulatedHumanState = $CumulatedServiceStatus->toArray()['humanState'];
        }

        $hostgroupState = [
            'currentState'    => $cumulatedState,
            'curentStateType' => $cumulatedStateType,
            'humanState'      => $cumulatedHumanState,
            'inDowntime'      => (int)$hostgroupDowntime,
            'acknowledged'    => (int)$hostgroupAck,
            'hostSummary'     => [],
            'parentName'      => $parentName,
            'parentType'      => $parentType
        ];

        if (!empty($hostSummary)) {
            $hostgroupState['hostSummary'] = $hostSummary;
        }

        return [
            'CumulatedState' => $hostgroupState,
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
    private function getServicegroupSummary(ServicesTable $ServicesTable, ServicestatusTableInterface $ServicestatusTable, $AcknowledgementServicesTable, DowntimehistoryServicesTableInterface $DowntimehistoryServicesTable, array $servicegroup) {
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

        $ServiceSummary = [];
        $allServiceStates = [];
        // $allServiceDowntimes = [];
        // $allServiceAcknowledgements = [];

        $parentName = $servicegroup['container']['name'];
        $parentType = 'servicegroup';
        foreach ($servicegroup['services'] as $service) {
            $service = [
                'id'   => $service['id'],
                'uuid' => $service['uuid'],
            ];
            $currentServiceSummary = $this->getServiceSummary($ServicestatusTable, $AcknowledgementServicesTable, $DowntimehistoryServicesTable, $service, $parentName, $parentType);
            $ServiceSummary[] = $currentServiceSummary['Servicestatus'];
            $allServiceStates[] = $currentServiceSummary['Servicestatus']['currentState'];
            //$allServiceDowntimes[] = $currentServiceSummary['Servicestatus']['inDowntime'];
            //$allServiceAcknowledgements[] = $currentServiceSummary['Servicestatus']['acknowledged'];
        }

        if (!empty($allServiceStates)) {
            $cumulatedServiceState = max($allServiceStates);
        }

        $CumulatedServiceStatus = new Servicestatus([
            'current_state' => $cumulatedServiceState
        ]);

        $servicegroupDowntimes = Hash::extract($ServiceSummary, '{n}.inDowntime');
        $servicegroupAcknowledgements = Hash::extract($ServiceSummary, '{n}.acknowledged');

        $servicegroupDowntime = true;
        $servicegroupAck = true;

        if (in_array(0, $servicegroupDowntimes, true)) {
            $servicegroupDowntime = false;
        }

        if (in_array(0, $servicegroupAcknowledgements, true)) {
            $servicegroupAck = false;
        }


        $CumulatedState = [
            'currentState'   => $CumulatedServiceStatus->toArray()['currentState'],
            'humanState'     => $CumulatedServiceStatus->toArray()['humanState'],
            'inDowntime'     => (int)$servicegroupDowntime,
            'acknowledged'   => (int)$servicegroupAck,
            'serviceSummary' => [],
            'parentName'     => $parentName,
            'parentType'     => $parentType
        ];

        if (!empty($ServiceSummary)) {
            $CumulatedState['serviceSummary'] = $ServiceSummary;
        }

        return [
            'CumulatedState' => $CumulatedState,
        ];
    }


    /**
     * @param $statuspageData
     * @param bool $public
     * @return array|\ArrayAccess|\ArrayAccess[]
     */
    public function getDowntimeAndAckHistory($statuspageData, bool $public = true) {
        $downtimesAndAcks = [];
        foreach ($statuspageData as $key => $statuspage) {
            if ($key === 'statuspage') {
                continue;
            }

            $data = $this->getDowntimesAndAcks($statuspage, $public);
            if (!empty($data)) {
                $downtimesAndAcks[] = Hash::extract($data, '{n}.{n}');
            }
        }
        if (!empty($downtimesAndAcks)) {
            $downtimesAndAcks = Hash::extract($downtimesAndAcks, '{n}.{n}');
            //sort array by sort_time key
            usort($downtimesAndAcks, function ($a, $b) {
                return $a['sort_time'] - $b['sort_time'];
            });
        }

        return $downtimesAndAcks;
    }

    /**
     * @param $statuspageData
     * @param $public
     * @param $data
     * @return array|mixed
     */
    private function getDowntimesAndAcks($statuspageData, $public, &$data = []) {
        foreach ($statuspageData as $statuspage) {
            $downtimesAndAcks = [];
            if (isset($statuspage['downtime']) && is_array($statuspage['downtime'])) {
                if (!empty($statuspage['downtime'])) {
                    $currentDowntime = [
                        'name'                 => (!empty($statuspage['name']) ? $statuspage['name'] : ''), //given real name or anonymized name for Hosts or services. This is not filled if its a child element
                        'parentName'           => (!empty($statuspage['parentName']) ? $statuspage['parentName'] : ''), // name of the parent Host, hostgroup or servicegroup -> if empty then there is no parent -> object has been placed directly as host or service
                        'parentType'           => (!empty($statuspage['parentType']) ? $statuspage['parentType'] : ''), //or hostgroup or servicegroup -> if empty then there is no parent -> object has been placed directly as host or service
                        'type'                 => 'downtime', //or acknowledgement
                        'comment_data'         => ($public === true ? '' : $statuspage['downtime']['comment_data']),// downtime or ack message. if its a public statuspage, this will not be filled
                        'sort_time'            => $statuspage['downtime']['scheduled_start_time'], // entry_time on acks and scheduled_start_time on downtimes
                        'entry_time'           => $statuspage['downtime']['entry_time'], //entry time of the ACK or Downtime
                        'scheduled_start_time' => $statuspage['downtime']['scheduled_start_time'], //only for downtimes
                        'scheduled_end_time'   => $statuspage['downtime']['scheduled_end_time'], //only for downtimes
                    ];
                    $downtimesAndAcks[] = $currentDowntime;
                }
            }
            if (isset($statuspage['acknowledgement']) && is_array($statuspage['acknowledgement'])) {
                if (!empty($statuspage['acknowledgement'])) {
                    $currentAcknowledgement = [
                        'name'                 => (!empty($statuspage['name']) ? $statuspage['name'] : ''),
                        'parentName'           => (!empty($statuspage['parentName']) ? $statuspage['parentName'] : ''),
                        'parentType'           => (!empty($statuspage['parentType']) ? $statuspage['parentType'] : ''),
                        'type'                 => 'acknowledgement',
                        'comment_data'         => ($public === true ? '' : $statuspage['acknowledgement']['comment_data']),
                        'sort_time'            => $statuspage['acknowledgement']['entry_time'],
                        'entry_time'           => $statuspage['acknowledgement']['entry_time'],
                        'scheduled_start_time' => '',
                        'scheduled_end_time'   => '',
                    ];
                    $downtimesAndAcks[] = $currentAcknowledgement;
                }
            }

            if (isset($statuspage['hostSummary']) && is_array($statuspage['hostSummary'])) {
                $this->getDowntimesAndAcks($statuspage['hostSummary'], $public, $data);
            }
            if (isset($statuspage['serviceSummary']) && is_array($statuspage['serviceSummary'])) {
                $this->getDowntimesAndAcks($statuspage['serviceSummary'], $public, $data);
            }
            if (!empty($downtimesAndAcks)) {
                $data[] = $downtimesAndAcks;
            }
        }
        return $data;
    }
}
