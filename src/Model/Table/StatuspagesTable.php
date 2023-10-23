<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//	This program is free software: you can redistribute it and/or modify
//	it under the terms of the GNU General Public License as published by
//	the Free Software Foundation, version 3 of the License.
//
//	This program is distributed in the hope that it will be useful,
//	but WITHOUT ANY WARRANTY; without even the implied warranty of
//	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//	GNU General Public License for more details.
//
//	You should have received a copy of the GNU General Public License
//	along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

// 2.
//	If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//	under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//	License agreement and license key will be shipped with the order
//	confirmation.
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Table\ServicesTable;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\ORM\Association\HasMany;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\Core\DowntimeHostConditions;
use itnovum\openITCOCKPIT\Core\Views\AcknowledgementHost;
use itnovum\openITCOCKPIT\Core\Views\Downtime;
use itnovum\openITCOCKPIT\Core\DowntimeServiceConditions;
use itnovum\openITCOCKPIT\Filter\StatuspagesFilter;
use App\Lib\Traits\PaginationAndScrollIndexTrait;

use App\Lib\Interfaces\HoststatusTableInterface;
use App\Lib\Interfaces\DowntimehistoryHostsTableInterface;
use App\Lib\Interfaces\DowntimehistoryServicesTableInterface;
use App\Lib\Interfaces\ServicestatusTableInterface;
use App\Model\Entity\Host;
use App\Model\Entity\Service;
use itnovum\openITCOCKPIT\Core\DbBackend;
use itnovum\openITCOCKPIT\Core\Hoststatus;
use itnovum\openITCOCKPIT\Core\HoststatusFields;
use itnovum\openITCOCKPIT\Core\HoststatusConditions;
use itnovum\openITCOCKPIT\Core\Servicestatus;
use itnovum\openITCOCKPIT\Core\ServicestatusConditions;
use itnovum\openITCOCKPIT\Core\ServicestatusFields;
use itnovum\openITCOCKPIT\Core\Views\AcknowledgementService;
use itnovum\openITCOCKPIT\Core\Views\UserTime;

/**
 * Statuspages Model

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
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class StatuspagesTable extends Table
{
    use PaginationAndScrollIndexTrait;

    private $public = false;
    private $comments = false;
    private $publicCall = false;
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('statuspages');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

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
        ])->setDependent(true);

        $this->belongsToMany('Services', [
            'className'        => 'Services',
            'foreignKey'       => 'statuspage_id',
            'targetForeignKey' => 'service_id',
            'joinTable'        => 'statuspages_to_services'
        ])->setDependent(true);

        $this->belongsToMany('Hostgroups', [
            'className'        => 'Hostgroups',
            'foreignKey'       => 'statuspage_id',
            'targetForeignKey' => 'hostgroup_id',
            'joinTable'        => 'statuspages_to_hostgroups'
        ])->setDependent(true);
        $this->belongsToMany('Servicegroups', [
            'className'        => 'Servicegroups',
            'foreignKey'       => 'statuspage_id',
            'targetForeignKey' => 'servicegroup_id',
            'joinTable'        => 'statuspages_to_servicegroups'
        ])->setDependent(true);

    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->requirePresence('containers', true, __('You have to choose at least one option.'))
            ->allowEmptyString('containers', null, false)
            ->multipleOptions('containers', [
                'min' => 1
            ], __('You have to choose at least one option.'));

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('description')
            ->maxLength('description', 1000)
            ->allowEmptyString('description');

        $validator
            ->boolean('public')
            ->notEmptyString('public');

        $validator
            ->boolean('show_comments')
            ->notEmptyString('show_comments');

        return $validator;
    }

    /**
     * @param Validator $validator
     * @return Validator
     */
    public function  validationAlias(Validator $validator): Validator {

        return $validator;
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
        $query->where($StatuspagesFilter->indexFilter())
            ->distinct('Statuspages.id');
        ;

        $query->innerJoinWith('Containers', function (Query $q) use ($MY_RIGHTS) {
            if (!empty($MY_RIGHTS)) {
                return $q->where(['Containers.id IN' => $MY_RIGHTS]);
            }
            return $q;
        });

        $query->disableHydration();
        $query->order($StatuspagesFilter->getOrderForPaginator('Statuspages.id', 'asc'));

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
    public function getStatuspageObjects($id) {
        if (!$this->existsById($id)) {
            return;
        }

        $conditions = array_merge(['Statuspages.id' => $id]);

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
                        'hostname' => 'host.name'

                    ])->innerJoin(['host' => 'hosts'], [
                        'host.id = Services.host_id'
                    ])
                    ->innerJoin(['Servicetemplates' => 'servicetemplates'], [
                        'Servicetemplates.id = Services.servicetemplate_id'
                    ]);
            })
            ->contain('Hostgroups', function (Query $q) {
                return $q
                    ->select([
                        'id',
                        'uuid',
                        'name' => 'Containers.name'
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
                        'uuid',
                        'name' => 'Containers.name'
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
     * @param string|null $id
     * @param @param UserTime $userTime
     * @return array
     */
    public function getStatuspageView ( $id, UserTime $UserTime, $isPublicCall = false){
        if (!$this->existsById($id)) {
            return;
        }
        $allhosts = [];
        $allservices = [];
        $DbBackend = new DbBackend();
        $statuspage = $this->getStatuspageObjects($id);
        $this->public = $statuspage['public'];
        $this->comments = $statuspage['show_comments'];
        $this->publicCall = $isPublicCall;

        $statuspageView = [
            'statuspage' => [
                'name' => $statuspage['name'],
                'description' => $statuspage['description'],
                'public' => $statuspage['public'],
                'showComments' => $statuspage['show_comments'],
            ],
            'hosts' => [],
            'services' => [],
            'hostgroups' => [],
            'servicegroups' => []
        ];

        foreach ($statuspage as $key => $objectData) {

            if ($key === 'hosts' && count($objectData) > 0) {
                $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
                $HoststatusTable = $DbBackend->getHoststatusTable();
                $AcknowledgementHostsTable = $DbBackend->getAcknowledgementHostsTable();
                $DowntimehistoryHostsTable = $DbBackend->getDowntimehistoryHostsTable();
                $ServicesTable = TableRegistry::getTableLocator()->get(alias: 'Services');
                /** @var ServicesTable $ServicesTable */
                $ServicestatusTable = $DbBackend->getServicestatusTable();
                $hosts = $objectData;
                $allhosts[] = array_merge($allhosts, Hash::extract($hosts, '{n}.uuid'));
                $hostsViewData = [];
                foreach ($hosts as $host) {
                    $services = $ServicesTable->getActiveServicesByHostId($host['id'], false);
                    $services = $services->toArray();
                    $uuids = Hash::extract($services, '{n}.uuid');
                    $allservices = array_merge($allservices, $uuids);
                    $hostExtended = $HostsTable->getHostById($host['id']);
                    $properties = $this->getHostInformation($ServicesTable, $HoststatusTable, $ServicestatusTable, $hostExtended);
                    $hostViewData = [];
                    $hostViewData['type'] = 'Host';
                    if(!$this->publicCall) {
                        $hostViewData['id'] = $host['id'];
                        $hostViewData['uuid'] = $host['uuid'];
                    }
                    $hostViewData['name'] = ($host['_joinData']['display_alias'] !== null && $host['_joinData']['display_alias'] !== '') ? $host['_joinData']['display_alias'] : $host['name'];
                    $hostViewData = array_merge($hostViewData, $properties);
                    $plannedDowntimes = $this->getPlannedHostDowntimes($host['uuid'], $DowntimehistoryHostsTable, $UserTime);
                    if(count($plannedDowntimes) > 0) {
                        $hostViewData['plannedDowntimes'] = $this->getPlannedHostDowntimes($host['uuid'], $DowntimehistoryHostsTable, $UserTime);
                    }
                    if ($hostViewData['isAcknowledged']) {
                        $acknowledgement = $AcknowledgementHostsTable->byhostUuid($host['uuid']);
                        if (!empty($acknowledgement)) {
                            $Acknowledgement = new AcknowledgementHost($acknowledgement, $UserTime);
                            $hostViewData['acknowledgeData'] = $Acknowledgement->toArray();
                        }
                    }
                    if ($hostViewData['isInDowntime']) {
                        $downtime = $this->getHostDowntime($host['uuid'], $DowntimehistoryHostsTable, $UserTime);
                      //  $downtime = $DowntimehistoryHostsTable->byHostUuid($host['uuid']);
                        if (!empty($downtime)) {
                           // $Downtime = new Downtime($downtime, false, $UserTime);
                            $hostViewData['downtimeData'] = $downtime;
                        }
                    }
                    $hostsViewData[] = $hostViewData;
                }
                $statuspageView['hosts'] = $hostsViewData;
            }

            if ($key === 'services' && count($objectData) > 0) {
                $ServicesTable = TableRegistry::getTableLocator()->get('Services');
                $ServicestatusTable = $DbBackend->getServicestatusTable();
                $AcknowledgementServicesTable = $DbBackend->getAcknowledgementServicesTable();
                $DowntimehistoryServicesTable = $DbBackend->getDowntimehistoryServicesTable();
                $services = $objectData;
                $servicesViewData = [];

                foreach ($services as $service) {
                    $serviceExtended = $ServicesTable->getServiceByIdWithHostAndServicetemplate($service['id']);
                    $properties = $this->getServiceInformation($ServicestatusTable, $serviceExtended);

                    $serviceViewData = [];
                    $serviceViewData['type'] = 'Service';
                    if(!$this->publicCall) {
                        $serviceViewData['id'] = $service['id'];
                        $serviceViewData['uuid'] = $service['uuid'];
                    }
                    $serviceViewData['name'] = ($service['_joinData']['display_alias'] !== null && $service['_joinData']['display_alias'] !== '') ? $service['_joinData']['display_alias'] : $service['servicename'];
                    $serviceViewData = array_merge($serviceViewData, $properties);
                    $plannedDowntimes = $this->getPlannedServiceDowntimes($service['uuid'], $DowntimehistoryServicesTable, $UserTime);
                    if (count($plannedDowntimes) > 0 ) {
                        $serviceViewData['plannedDowntimes'] = $this->getPlannedServiceDowntimes($service['uuid'], $DowntimehistoryServicesTable, $UserTime);
                    }
                    if ($serviceViewData['isAcknowledged'] && $statuspageView['statuspage']['showComments']) {
                        $acknowledgement = $AcknowledgementServicesTable->byServiceUuid($service['uuid']);
                        if (!empty($acknowledgement)) {
                            $Acknowledgement = new AcknowledgementService($acknowledgement, $UserTime);
                            $serviceViewData['acknowledgeData'] = $Acknowledgement->toArray();
                        }
                    }
                    if ($serviceViewData['isInDowntime']) {
                        $downtime = $this->getserviceDowntime($service['uuid'], $DowntimehistoryServicesTable, $UserTime);
                        if (!empty($downtime)) {
                            $serviceViewData['downtimeData'] = $downtime;
                        }
                    }
                    $servicesViewData[] = $serviceViewData;
                }
                $statuspageView['services'] = $servicesViewData;
            }

            if ($key === 'servicegroups' && count($objectData) > 0) {
                $servicegroups = $objectData;
                $servicegroupsViewData = [];
                $ServicegroupsTable = TableRegistry::getTableLocator()->get('Servicegroups');
                /** @var ServicesTable $ServicesTable */
                $ServicesTable = TableRegistry::getTableLocator()->get('Services');
                $ServicestatusTable = $DbBackend->getServicestatusTable();
                foreach ($servicegroups as $servicegroup) {
                    $servicegroupViewData = [];
                    $servicegroupViewData['type'] = 'Servicegroup';
                    if(!$this->publicCall) {
                        $servicegroupViewData['id'] = $servicegroup['id'];
                        $servicegroupViewData['uuid'] = $servicegroup['uuid'];
                    }
                    $servicegroupViewData['name'] = ($servicegroup['_joinData']['display_alias'] !== null && $servicegroup['_joinData']['display_alias'] !== '') ? $servicegroup['_joinData']['display_alias'] : $servicegroup['name'];
                    $servicegroupProperties = $ServicegroupsTable->getServicegroupsByServicegroupForMaps($servicegroup['id']);
                    $servicegroupProperties['services'] = array_merge(
                        $servicegroupProperties['services'],
                        Hash::extract($servicegroupProperties, 'servicetemplates.{n}.services.{n}')
                    );
                    $properties = $this->getServicegroupInformation(
                        $ServicesTable,
                        $ServicestatusTable,
                        $servicegroupProperties
                    );
                    $servicegroupViewData = array_merge($servicegroupViewData, $properties);
                    $servicegroupViewData['currentState'] = $servicegroupViewData['cumulatedState'];
                    $servicegroupViewData['isAcknowledged'] = false;
                    $servicegroupViewData['isInDowntime'] = false;
                    $servicegroupsViewData[] = $servicegroupViewData;
                }
                $statuspageView['servicegroups'] = $servicegroupsViewData;
            }

            if ($key === 'hostgroups' && count($objectData) > 0) {
                $hostgroups = $objectData;
                $hostgroupsViewData = [];
                $HostgroupsTable = TableRegistry::getTableLocator()->get('Hostgroups');
                $HoststatusTable = $DbBackend->getHoststatusTable();
                /** @var ServicesTable $ServicesTable */
                $ServicesTable = TableRegistry::getTableLocator()->get('Services');
                $ServicestatusTable = $DbBackend->getServicestatusTable();
                foreach ($hostgroups as $hostgroup) {
                    $hostgroupViewData = [];
                    $hostgroupViewData['type'] = 'Hostgroup';
                    if(!$this->publicCall) {
                        $hostgroupViewData['id'] = $hostgroup['id'];
                        $hostgroupViewData['uuid'] = $hostgroup['uuid'];
                    }
                    $hostgroupViewData['name'] = ($hostgroup['_joinData']['display_alias'] !== null && $hostgroup['_joinData']['display_alias'] !== '') ? $hostgroup['_joinData']['display_alias'] : $hostgroup['name'];
                    $hostgroupProperties = $HostgroupsTable->getHostsByHostgroupForMaps($hostgroup['id']);
                    $hostgroupProperties['hosts'] = array_merge(
                        $hostgroupProperties['hosts'],
                        Hash::extract($hostgroupProperties, 'hosttemplates.{n}.hosts.{n}')
                    );
                    $properties = $this->getHostgroupInformation(
                        $ServicesTable,
                        $hostgroupProperties,
                        $HoststatusTable,
                        $ServicestatusTable);
                    $hostgroupViewData = array_merge($hostgroupViewData, $properties);
                    $hostgroupViewData['currentState'] = $hostgroupViewData['cumulatedState'];
                    $hostgroupViewData['isAcknowledged'] = false;
                    $hostgroupViewData['isInDowntime'] = false;
                    $hostgroupsViewData[] = $hostgroupViewData;
                }
                $statuspageView['hostgroups'] = $hostgroupsViewData;
            }
        }

        $items = array_merge($statuspageView['hostgroups'], $statuspageView['hosts'], $statuspageView['servicegroups'], $statuspageView['services']);
       // $itemsSortedState = Hash::sort($items, '{s}.type', 'desc');
        $itemsSortedState = Hash::sort($items, '{n}.cumulatedState', 'desc');
        $statuspageView['items'] = $itemsSortedState;

        return $statuspageView;
    }

    /**
     * @param ServicesTable $Service
     * @param HoststatusTableInterface $Hoststatus
     * @param ServicestatusTableInterface $Servicestatus
     * @param Host $host
     * @return array
     */
    private function getHostInformation(ServicesTable $Service, HoststatusTableInterface $Hoststatus, ServicestatusTableInterface $Servicestatus, Host $host): array
    {
        $info = [];
        $HoststatusFields = new HoststatusFields(new DbBackend());
        $HoststatusFields->currentState()->scheduledDowntimeDepth()->problemHasBeenAcknowledged();
        $hoststatus = $Hoststatus->byUuid($host->get('uuid'), $HoststatusFields);
        if (empty($hoststatus)) {
            $hoststatus['Hoststatus'] = [];
        }
        $hoststatus = new Hoststatus($hoststatus['Hoststatus']);
        $info['currentState'] = $hoststatus->currentState();
        $info['cumulatedState'] = $hoststatus->currentState();
        $info['color'] = $hoststatus->HostStatusColor();
        $info['isAcknowledged'] = $hoststatus->isAcknowledged();
        $info['isInDowntime'] = $hoststatus->isInDowntime();
        if ($info['currentState'] == 1) {
            $info['cumulatedState'] = 2;
        }
        if ($info['currentState'] == 2) {
            $info['cumulatedState'] = 3;
        }
        if ($info['currentState'] == 0) {
            $services = $Service->getActiveServicesByHostId($host->get('id'), false);
            $services = $services->toArray();
            $serviceUuids = Hash::extract($services, '{n}.uuid');
            $servicestatus = [];
            if (!empty($serviceUuids)) {
                $ServicestatusFieds = new ServicestatusFields(new DbBackend());
                $ServicestatusFieds->currentState()->scheduledDowntimeDepth()->problemHasBeenAcknowledged();
                $ServicestatusConditions = new ServicestatusConditions(new DbBackend());
                $ServicestatusConditions->servicesWarningCriticalAndUnknown();
                $servicestatus = $Servicestatus->byUuid($serviceUuids, $ServicestatusFieds, $ServicestatusConditions);
            }
            if (!empty($servicestatus)) {
                $worstServiceState = array_values(
                    Hash::sort($servicestatus, '{s}.Servicestatus.current_state', 'desc')
                );
                $info['color'] = $this->getServiceStatusColor($worstServiceState[0]['Servicestatus']['current_state']);
                $info['cumulatedState'] = $worstServiceState[0]['Servicestatus']['current_state'];
                $problems = count($servicestatus);
                $problemsNotAcknowledged = 0;
                foreach ($worstServiceState as $problemState) {
                    if ($problemState['Servicestatus']['problem_has_been_acknowledged'] == false) {
                        $problemsNotAcknowledged++;
                    }
                }
                $problemsAcknowledged = $problems - $problemsNotAcknowledged;
                if ($problemsNotAcknowledged > 0) {
                    $info['problemtext'] = "{$problemsAcknowledged} of {$problems} problems acknowledged";
                }
            }
        }
        return $info;
    }

    /**
     * @param ServicesTable $Service
     * @param ServicestatusTableInterface $Servicestatus
     * @return array
     */
    private function getServiceInformation(ServicestatusTableInterface $Servicestatus, Service $service, $includeServiceOutput = false) {
        $ServicestatusFields = new ServicestatusFields(new DbBackend());
        $ServicestatusFields->currentState()->scheduledDowntimeDepth()->problemHasBeenAcknowledged();
        $serviceArray = $service->toArray();
        $servicestatus = $Servicestatus->byUuid($service->get('uuid'), $ServicestatusFields);
        $servicestatus = new Servicestatus($servicestatus['Servicestatus']);
        $tmpServicestatus = $servicestatus->toArray();
        return [
            'currentState' => $tmpServicestatus['currentState'],
            'cumulatedState' => $tmpServicestatus['currentState'],
            'isAcknowledged' => $servicestatus->isAcknowledged(),
            'isInDowntime' => $servicestatus->isInDowntime(),
            'color' => $servicestatus->ServiceStatusColor(),
            'background' => $servicestatus->ServiceStatusBackgroundColor(),
        ];
    }

    /*
    * @param ServicesTable $Service
    * @param ServicestatusTableInterface $Servicestatus
    * @param array $servicegroup
    * @return array
    */
    private function getServicegroupInformation(ServicesTable $Service, ServicestatusTableInterface $Servicestatus, $servicegroup = []) {
        $info = [];
        $info['color'] = $this->getServiceStatusColor(0);
        $info['cumulatedState'] = 0;
        $ServicestatusFields = new ServicestatusFields(new DbBackend());
        $ServicestatusFields->currentState()->scheduledDowntimeDepth()->problemHasBeenAcknowledged();

        $serviceUuids = Hash::extract($servicegroup['services'], '{n}.uuid');
        if (!empty($serviceUuids)) {
            $ServicestatusFieds = new ServicestatusFields(new DbBackend());
            $ServicestatusFieds->currentState()->scheduledDowntimeDepth()->problemHasBeenAcknowledged();
            $ServicestatusConditions = new ServicestatusConditions(new DbBackend());
            $ServicestatusConditions->servicesWarningCriticalAndUnknown();
            $servicestatusProblems = $Servicestatus->byUuid($serviceUuids, $ServicestatusFieds, $ServicestatusConditions);
        }
        if (!empty($servicestatusProblems)) {
            $worstServiceState = array_values(
                Hash::sort($servicestatusProblems, '{s}.Servicestatus.current_state', 'desc')
            );
            $servicestatus = new Servicestatus($worstServiceState[0]['Servicestatus']);
            $info['color'] = $servicestatus->ServiceStatusColor();
            $info['cumulatedState'] = $worstServiceState[0]['Servicestatus']['current_state'];
            $problems = count($servicestatusProblems);
            $problemsNotAcknowledged = 0;
            $problemsInDowntime = 0;
            foreach ($servicestatusProblems as $uuid => $status) {
                if ($status['Servicestatus']['problem_has_been_acknowledged'] == false) {
                    $problemsNotAcknowledged++;
                }
                if ($status['Servicestatus']['scheduled_downtime_depth'] > 0) {
                    $problemsInDowntime++;
                }
            }
            $problemsAcknowledged = $problems - $problemsNotAcknowledged;
            if ($problemsNotAcknowledged > 0) {
                $info['problemtext'] = "{$problemsAcknowledged} of {$problems} problems acknowledged";
            }
            if ($problemsInDowntime > 0) {
                $info['problemtext_down'] = "{$problemsInDowntime} of {$problems} problems currently in a planned maintenance period";
            }
        }
        return $info;
    }

    /*
    * @param ServicesTable $Service
    *  @param array $hostgroup
    * @param ShostStatusTableInterface $HoststatusTable
    * @param ServicestatusTableInterface $ServicestatusTable
    * @return array
    */
    private function getHostgroupInformation(ServicesTable $Service, array $hostgroup, HoststatusTableInterface $HoststatusTable, ServicestatusTableInterface $ServicestatusTable)
    {
        $info = [];
        $HoststatusFields = new HoststatusFields(new DbBackend());
        $HoststatusFields->currentState()->scheduledDowntimeDepth()->problemHasBeenAcknowledged();
        $hostUuids = Hash::extract($hostgroup['hosts'], '{n}.uuid');
        $HoststatusConditions = new HoststatusConditions(new DbBackend());
        $HoststatusConditions->hostsDownAndUnreachable();
        $hoststatusProblems = $HoststatusTable->byUuid($hostUuids, $HoststatusFields, $HoststatusConditions);
        if (!empty($hoststatusProblems)) {
            $worstHostState = array_values(
                Hash::sort($hoststatusProblems, '{s}.Hoststatus.current_state', 'desc')
            );
            $info['cumulatedState'] = $worstHostState[0]['Hoststatus']['current_state'] + 1;
            $info['color'] = $this->getServiceStatusColor($info['cumulatedState']);
            $hostProblems = count($hoststatusProblems);
            $hostProblemsNotAcknowledged = 0;
            $hostProblemsInDowntime = 0;
            foreach ($hoststatusProblems as $uuid => $status) {
                if ($status['Hoststatus']['problem_has_been_acknowledged'] == false) {
                    $hostProblemsNotAcknowledged++;
                }
                if ($status['Hoststatus']['scheduled_downtime_depth'] > 0) {
                    $hostProblemsInDowntime++;
                }
                $problemsAcknowledged = $hostProblems - $hostProblemsNotAcknowledged;
                if ($hostProblemsNotAcknowledged > 0) {
                    $info['problemtext'] = "{$problemsAcknowledged} of {$hostProblems} problems in hostgroup acknowledged";
                }
                if ($hostProblemsInDowntime > 0) {
                    $info['problemtext_down'] = "{$hostProblemsInDowntime} of {$hostProblems} problems in hostgroup currently in a planned maintenance period";
                }
            }
            return $info;
        }


        $hostIds = Hash::extract($hostgroup['hosts'], '{n}.id');
        $services = $Service->getActiveServicesByHostIds($hostIds, false);
        $services = $services->toArray();
        $servicestatus = [];
        if (!empty($services)) {
            $ServicestatusFieds = new ServicestatusFields(new DbBackend());
            $ServicestatusFieds->currentState()->scheduledDowntimeDepth()->problemHasBeenAcknowledged();
            $ServicestatusConditions = new ServicestatusConditions(new DbBackend());
            $ServicestatusConditions->servicesWarningCriticalAndUnknown();
            $servicestatus = $ServicestatusTable->byUuid(Hash::extract($services, '{n}.uuid'), $ServicestatusFieds, $ServicestatusConditions);
        }

        if (!empty($servicestatus)) {
            $worstServiceState = array_values(
                Hash::sort($servicestatus, '{s}.Servicestatus.current_state', 'desc')
            );
            $info['cumulatedState'] = $worstServiceState[0]['Servicestatus']['current_state'];
            $info['color'] = $this->getServiceStatusColor($info['cumulatedState']);
            $serviceProblems = count($servicestatus);
            $problemsNotAcknowledged = 0;
            $problemsInDowntime = 0;
            foreach ($servicestatus as $uuid => $status) {
                if ($status['Servicestatus']['problem_has_been_acknowledged'] == false) {
                    $problemsNotAcknowledged++;
                }
                if ($status['Servicestatus']['scheduled_downtime_depth'] > 0) {
                    $problemsInDowntime++;
                }
            }
            $problemsAcknowledged = $serviceProblems- $problemsNotAcknowledged;
            if ($problemsNotAcknowledged > 0) {
                $info['problemtext'] = "{$problemsAcknowledged} of {$serviceProblems}  problems of services acknowledged";
            }
            if ($problemsInDowntime > 0) {
                $info['problemtext_down'] = "{$problemsInDowntime} of {$serviceProblems} problems of services currently in a planned maintenance period";
            }
        }
        return $info;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function existsById($id) {
        return $this->exists(['Statuspages.id' => $id]);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function isPublic($id) {
        $conditions = ['Statuspages.id' => $id, 'Statuspages.public' => 1];
        $query = $this->find()->where($conditions)
            ->first();
        if(empty($query)){
            return false;
        }
        return true;
    }

    /**
     * @param int $id|null
     * @return bool
     */
    private function getServiceStatusColor($state = null) {
        if ($state === null) {
            return 'text-primary';
        }

        switch ($state) {
            case 0:
                return 'ok';

            case 1:
                return 'warning';

            case 2:
                return 'critical';

            default:
                return 'unknown';
        }
    }

    /**
     * @param string $uuid
     * @param DowntimehistoryServicesTableInterface $table
     * @return array
     */
    private function getServiceDowntime($uuid, DowntimehistoryServicesTableInterface $table, $userTime, $isRunning = true) {
        $downtime = $table->byServiceUuid($uuid, $isRunning);
        $downtime = new Downtime($downtime->toArray(), true, $userTime);
        $downtime = $downtime->toArray();
        $comment = "Work in progress";
        if($this->comments){
            $comment = $downtime['commentData'];
        }
        return [
            'scheduledStartTime' => $downtime['scheduledStartTime'],
            'scheduledEndTime' => $downtime['scheduledEndTime'],
            'commentData' => $comment
        ];
    }

    /**
     * @param string $uuid
     * @param DowntimehistoryHostsTableInterface $table
     * @param $userTime
     * $return array
     */
    private function getHostDowntime($uuid, DowntimehistoryHostsTableInterface $table, $userTime, $isRunning = true) {
        $downtime = $table->byHostUuid($uuid, $isRunning);
        $downtime = new Downtime($downtime->toArray(), true, $userTime);
        $downtime = $downtime->toArray();
        $comment = "Work in progress";
        if($this->comments){
            $comment = $downtime['commentData'];
        }
        return [
            'scheduledStartTime' => $downtime['scheduledStartTime'],
            'scheduledEndTime' => $downtime['scheduledEndTime'],
            'commentData' => $comment
        ];
    }

    /**
     * @param string $uuid
     * @param DowntimehistoryHostsTableInterface $table
     * @param $usertime
     * $return array
     */
    private function getPlannedHostDowntimes($uuid, DowntimehistoryHostsTableInterface $table, $userTime)
    {
        $planned = [];
        $DowntimeHostConditions = new DowntimeHostConditions();
        $DowntimeHostConditions->setFrom(time());
        $DowntimeHostConditions->setTo(time() + (3600 * 24 * 10));
        $DowntimeHostConditions->setOrder(['DowntimeHosts.scheduled_start_time' => "asc"] );
        $DowntimeHostConditions->setConditions([
            'DowntimeHosts.hostname IN' => [$uuid],
            'DowntimeHosts.was_started' => 0,
            'DowntimeHosts.was_cancelled' => 0]);
        $hostDowntimes = $table->getDowntimes($DowntimeHostConditions);
        if (!empty($hostDowntimes)){
            foreach ($hostDowntimes as $hostDowntime) {
                $HostDowntime = new Downtime($hostDowntime->toArray(), true, $userTime);
                $downtimeArray = $HostDowntime->toArray();
                $comment = "Work in progress";
                if($this->comments){
                    $comment = $downtimeArray['commentData'];
                }
                $planned[] = [
                    'scheduledStartTime' => $downtimeArray['scheduledStartTime'],
                    'scheduledEndTime' => $downtimeArray['scheduledEndTime'],
                    'commentData' => $comment
                ];
            }
        }
        return $planned;
    }

    /**
     * @param string $uuid
     * @param DowntimehistoryServicesTableInterface $table
     * @param $usertime
     * $return array
     */
    private function getPlannedServiceDowntimes($uuid, DowntimehistoryServicesTableInterface $table, $userTime)
    {
        $planned = [];
        $DowntimeServiceConditions = new DowntimeServiceConditions();
        $DowntimeServiceConditions->setFrom(time());
        $DowntimeServiceConditions->setTo(time() + (3600 * 24 * 10));
        $DowntimeServiceConditions->setOrder(['DowntimeServices.scheduled_start_time' => "asc"] );
        $DowntimeServiceConditions->setConditions([
            'DowntimeServices.service_description IN' => [$uuid],
            'DowntimeServices.was_started' => 0,
            'DowntimeServices.was_cancelled' => 0]);
        $serviceDowntimes = $table->getDowntimes($DowntimeServiceConditions);
        if (!empty($serviceDowntimes)){
            foreach ($serviceDowntimes as $serviceDowntime) {
                $ServiceDowntime = new Downtime($serviceDowntime->toArray(), true, $userTime);
                $downtimeArray = $ServiceDowntime->toArray();
                $comment = "TestWork in progress";
                if($this->comments){
                    $comment = $downtimeArray['commentData'];
                }
                $planned[] = [
                    'scheduledStartTime' => $downtimeArray['scheduledStartTime'],
                    'scheduledEndTime' => $downtimeArray['scheduledEndTime'],
                    'commentData' => $comment
                ];
            }
        }
        return $planned;
    }


}
