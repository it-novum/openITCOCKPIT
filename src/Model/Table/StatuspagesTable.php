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
use Cake\Utility\Hash;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\Core\Views\AcknowledgementHost;
use itnovum\openITCOCKPIT\Core\Views\Downtime;
use itnovum\openITCOCKPIT\Filter\StatuspagesFilter;
use App\Lib\Traits\PaginationAndScrollIndexTrait;
use itnovum\openITCOCKPIT\Core\DbBackend;
use itnovum\openITCOCKPIT\Core\Hoststatus;
use itnovum\openITCOCKPIT\Core\HoststatusFields;
use itnovum\openITCOCKPIT\Core\Servicestatus;
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

        $this->addBehavior('Timestamp');

        $this->belongsToMany('Containers', [
            'className'        => 'Containers',
            'foreignKey'       => 'statuspage_id',
            'targetForeignKey' => 'container_id',
            'joinTable'        => 'statuspages_to_containers'
        ])->setDependent(true);


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
    public function validationDefault(Validator $validator): Validator {
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
    public function validationAlias(Validator $validator): Validator {

        return $validator;
    }

    /**
     * @param StatuspagesFilter $StatuspagesFilter
     * @param $PaginateOMat | null
     * @param $MY_RIGHTS
     * @return array
     */
    public function getStatuspagesIndex(StatuspagesFilter $StatuspagesFilter, $PaginateOMat = null, $MY_RIGHTS = []) {
        $query = $this->find('all');
        $query->contain(['Containers']);
        $query->where($StatuspagesFilter->indexFilter())->distinct('Statuspages.id');;

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
     * @param int $id |null
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
        $query = $this->find()->where($conditions)->first();
        if (empty($query)) {
            return false;
        }
        return true;
    }

    /**
     * @param int $id
     * @param array $MY_RIGHTS
     * @param UserTime $userTime
     * @param bool $includeComments
     * @return array
     */
    public function getStatuspageForView(int $id, array $MY_RIGHTS, UserTime $userTime, bool $includeComments = false) {
        $statuspage = $this->getStatuspageWithAllObjects($id, $MY_RIGHTS);

        // Merge all host and service uuids to select the host and service status
        $hostUuids = [];
        $serviceUuids = [];
        foreach ($statuspage['hosts'] as $key => $host) {
            $hostUuids[$host['id']] = $host['uuid'];
            $statuspage['hosts'][$key]['host_uuids'] = [
                $host['uuid'] => null // We make this to have the same code for hosts, host groups, service groups and services
            ];
            $statuspage['hosts'][$key]['service_uuids'] = [];
            foreach ($host['services'] as $service) {
                $serviceUuids[$service['id']] = $service['uuid'];
                $statuspage['hosts'][$key]['service_uuids'][$service['uuid']] = null;
            }
        }

        foreach ($statuspage['services'] as $key => $service) {
            $serviceUuids[$service['id']] = $service['uuid'];
            $hostUuids[$service['host']['id']] = $service['host']['uuid'];

            // We make this to have the same code for hosts, host groups, service groups and services
            $statuspage['services'][$key]['host_uuids'] = [
                $service['host']['uuid'] => null
            ];
            $statuspage['services'][$key]['service_uuids'] = [
                $service['uuid'] => null
            ];
        }

        foreach ($statuspage['hostgroups'] as $key => $hostgroup) {
            $statuspage['hostgroups'][$key]['host_uuids'] = [];
            $statuspage['hostgroups'][$key]['service_uuids'] = [];
            foreach ($hostgroup['hosts'] as $host) {
                $hostUuids[$host['id']] = $host['uuid']; // Store all host uuids for the status query
                $statuspage['hostgroups'][$key]['host_uuids'][$host['uuid']] = null; // store the status of the host in here to determine the worst host status
                foreach ($host['services'] as $service) {
                    $serviceUuids[$service['id']] = $service['uuid'];
                    $statuspage['hostgroups'][$key]['service_uuids'][$service['uuid']] = null;
                }
            }

            foreach ($hostgroup['hosttemplates'] as $hosttemplate) {
                foreach ($hosttemplate['hosts'] as $host) {
                    $hostUuids[$host['id']] = $host['uuid'];
                    $statuspage['hostgroups'][$key]['host_uuids'][$host['uuid']] = null;
                    foreach ($host['services'] as $service) {
                        $serviceUuids[$service['id']] = $service['uuid'];
                        $statuspage['hostgroups'][$key]['service_uuids'][$service['uuid']] = null;
                    }
                }
            }
        }

        foreach ($statuspage['servicegroups'] as $key => $servicegroup) {
            $statuspage['servicegroups'][$key]['host_uuids'] = [];
            $statuspage['servicegroups'][$key]['service_uuids'] = [];
            foreach ($servicegroup['services'] as $service) {
                $serviceUuids[$service['id']] = $service['uuid'];
                $statuspage['servicegroups'][$key]['service_uuids'][$service['uuid']] = null;
                $hostUuids[$service['host']['id']] = $service['host']['uuid'];
                $statuspage['servicegroups'][$key]['host_uuids'][$host['uuid']] = null;
            }

            foreach ($servicegroup['servicetemplates'] as $servicetemplate) {
                foreach ($servicetemplate['services'] as $service) {
                    $serviceUuids[$service['id']] = $service['uuid'];
                    $statuspage['servicegroups'][$key]['service_uuids'][$service['uuid']] = null;
                    $hostUuids[$service['host']['id']] = $service['host']['uuid'];
                    $statuspage['servicegroups'][$key]['host_uuids'][$host['uuid']] = null;
                }
            }
        }

        // Query host and service status for all objects in two queries
        $DbBackend = new DbBackend();
        $HoststatusTable = $DbBackend->getHoststatusTable();
        $ServicestatusTable = $DbBackend->getServicestatusTable();

        $HoststatusFields = new HoststatusFields($DbBackend);
        $HoststatusFields
            ->currentState()
            ->isHardstate()
            ->problemHasBeenAcknowledged()
            ->scheduledDowntimeDepth();

        $ServicestatusFields = new ServicestatusFields($DbBackend);
        $ServicestatusFields
            ->currentState()
            ->isHardstate()
            ->problemHasBeenAcknowledged()
            ->scheduledDowntimeDepth();

        $AllHoststatus = $HoststatusTable->byUuid($hostUuids, $HoststatusFields);
        $AckHostUuids = Hash::extract($AllHoststatus, '{s}.Hoststatus[problem_has_been_acknowledged=true].hostname');
        $DowntimeHostUuids = Hash::extract($AllHoststatus, '{s}.Hoststatus[scheduled_downtime_depth>0].hostname');

        $AllServicestatus = $ServicestatusTable->byUuids($serviceUuids, $ServicestatusFields);
        $AckServiceUuids = Hash::extract($AllServicestatus, '{s}.Servicestatus[problem_has_been_acknowledged=true].service_description');
        $DowntimeServiceUuids = Hash::extract($AllServicestatus, '{s}.Servicestatus[scheduled_downtime_depth>0].service_description');

        // Query Acknowledgements for all objects
        $AcknowledgementHostsTable = $DbBackend->getAcknowledgementHostsTable();
        $AcknowledgementServicesTable = $DbBackend->getAcknowledgementServicesTable();

        $AllHostAcknowledgemens = $AcknowledgementHostsTable->byUuids($AckHostUuids);
        $AllServiceAcknowledgemens = $AcknowledgementServicesTable->byUuids($AckServiceUuids);

        // Query Downtimes for all objects
        $DowntimehistoryHostsTable = $DbBackend->getDowntimehistoryHostsTable();
        $DowntimehistoryServicesTable = $DbBackend->getDowntimehistoryServicesTable();

        $AllHostDowntimes = $DowntimehistoryHostsTable->byUuidsNoJoins($DowntimeHostUuids, true);
        $AllServiceDowntimes = $DowntimehistoryServicesTable->byUuidsNoJoins($DowntimeServiceUuids, true);

        // Query all planned downtimes for all objects
        $AllPlannedHostDowntimes = $DowntimehistoryHostsTable->getPlannedDowntimes($hostUuids, time(), (time() + (3600 * 24 * 10)));
        $AllPlannedServiceDowntimes = $DowntimehistoryServicesTable->getPlannedDowntimes($serviceUuids, time(), (time() + (3600 * 24 * 10)));


        foreach ($statuspage['hosts'] as $host) {
            $hostUuids[$host['id']] = $host['uuid'];
            foreach ($host['services'] as $service) {
                $serviceUuids[$service['id']] = $service['uuid'];
            }
        }
        foreach ($statuspage['services'] as $service) {
            $serviceUuids[$service['id']] = $service['uuid'];
            $hostUuids[$service['host']['id']] = $service['host']['uuid'];
        }

        // Calculate worst state per object
        // Cumulate all object types
        foreach (['hosts', 'services', 'hostgroups', 'servicegroups'] as $objectType) {
            foreach ($statuspage[$objectType] as $index => $objectGroup) {
                $statuspage[$objectType][$index]['state_summary'] = [
                    'hosts'    => [
                        'state'                    => [
                            0 => 0, // Up
                            1 => 0, // Down
                            2 => 0, // Unreachable
                        ],
                        'acknowledgements'         => 0,
                        'acknowledgement_details'  => [],
                        'downtimes'                => 0,
                        'downtime_details'         => [],
                        'planned_downtime_details' => [],
                        'total'                    => 0, // Total amount of hosts
                        'problems'                 => 0, // Hosts in none up state
                        'cumulatedStateId'         => 0,
                        'cumulatedStateName'       => __('Operational'),
                    ],
                    'services' => [
                        'state'                    => [
                            0 => 0, // OK
                            1 => 0, // Warning
                            2 => 0, // Critical
                            3 => 0, // Unknown
                        ],
                        'acknowledgements'         => 0,
                        'acknowledgement_details'  => [],
                        'downtimes'                => 0,
                        'downtime_details'         => [],
                        'planned_downtime_details' => [],
                        'total'                    => 0, // Total amount of services
                        'problems'                 => 0, // Services in none ok state
                        'cumulatedStateId'         => 0,
                        'cumulatedStateName'       => __('Operational'),
                    ]
                ];

                foreach ($objectGroup['host_uuids'] as $hostUuid => $v) {
                    if (isset($AllPlannedHostDowntimes[$hostUuid])) {
                        $statuspage[$objectType][$index]['state_summary']['hosts']['planned_downtime_details'] = array_merge(
                            $statuspage[$objectType][$index]['state_summary']['hosts']['planned_downtime_details'],
                            $AllPlannedHostDowntimes[$hostUuid]
                        );
                    }

                    if (!isset($AllHoststatus[$hostUuid]['Hoststatus'])) {
                        continue;
                    }

                    $Hoststatus = new Hoststatus($AllHoststatus[$hostUuid]['Hoststatus']);
                    $statuspage[$objectType][$index]['state_summary']['hosts']['total']++;

                    $statuspage[$objectType][$index]['state_summary']['hosts']['state'][$Hoststatus->currentState()]++;

                    if ($Hoststatus->currentState() > 0) {
                        $statuspage[$objectType][$index]['state_summary']['hosts']['problems']++;
                    }

                    if ($Hoststatus->isAcknowledged() && $Hoststatus->currentState() > 0) {
                        $statuspage[$objectType][$index]['state_summary']['hosts']['acknowledgements']++;
                        if (isset($AllHostAcknowledgemens[$hostUuid])) {
                            $statuspage[$objectType][$index]['state_summary']['hosts']['acknowledgement_details'][] = (new AcknowledgementHost(
                                $AllHostAcknowledgemens[$hostUuid]
                            ))->toArray();
                        }
                    }
                    if ($Hoststatus->isInDowntime()) {
                        $statuspage[$objectType][$index]['state_summary']['hosts']['downtimes']++;
                        if (isset($AllHostDowntimes[$hostUuid])) {
                            $statuspage[$objectType][$index]['state_summary']['hosts']['downtime_details'][] = array_merge((new Downtime(
                                $AllHostDowntimes[$hostUuid]
                            ))->toArray(), ['hostname' => $hostUuid]);
                        }
                    }
                }

                foreach ($objectGroup['service_uuids'] as $serviceUuid => $v) {
                    if (isset($AllPlannedServiceDowntimes[$serviceUuid])) {
                        $statuspage[$objectType][$index]['state_summary']['services']['planned_downtime_details'] = array_merge(
                            $statuspage[$objectType][$index]['state_summary']['services']['planned_downtime_details'],
                            $AllPlannedServiceDowntimes[$serviceUuid]
                        );
                    }

                    if (!isset($AllServicestatus[$serviceUuid]['Servicestatus'])) {
                        continue;
                    }

                    $Servicestatus = new Servicestatus($AllServicestatus[$serviceUuid]['Servicestatus']);
                    $statuspage[$objectType][$index]['state_summary']['services']['total']++;

                    $statuspage[$objectType][$index]['state_summary']['services']['state'][$Servicestatus->currentState()]++;

                    if ($Servicestatus->currentState() > 0) {
                        $statuspage[$objectType][$index]['state_summary']['services']['problems']++;
                    }

                    if ($Servicestatus->isAcknowledged() && $Servicestatus->currentState() > 0) {
                        $statuspage[$objectType][$index]['state_summary']['services']['acknowledgements']++;
                        if (isset($AllServiceAcknowledgemens[$serviceUuid])) {
                            $statuspage[$objectType][$index]['state_summary']['services']['acknowledgement_details'][] = (new AcknowledgementService(
                                $AllServiceAcknowledgemens[$serviceUuid]
                            ))->toArray();
                        }
                    }
                    if ($Servicestatus->isInDowntime()) {
                        $statuspage[$objectType][$index]['state_summary']['services']['downtimes']++;
                        if (isset($AllServiceDowntimes[$serviceUuid])) {
                            $statuspage[$objectType][$index]['state_summary']['services']['downtime_details'][] = array_merge((new Downtime(
                                $AllServiceDowntimes[$serviceUuid]
                            ))->toArray(), ['servicename' => $serviceUuid]);
                        }
                    }
                }
            }
        }

        // Set cumulatedState state
        foreach (['hosts', 'services', 'hostgroups', 'servicegroups'] as $objectType) {
            foreach ($statuspage[$objectType] as $index => $objectGroup) {
                if ($objectGroup['state_summary']['hosts']['state'][1] > 0) {
                    // Host is down
                    $statuspage[$objectType][$index]['state_summary']['hosts']['cumulatedStateId'] = 1;
                    $statuspage[$objectType][$index]['state_summary']['hosts']['cumulatedStateName'] = __('Major Outage');
                }
                if ($objectGroup['state_summary']['hosts']['state'][2] > 0) {
                    // Host is unreachable
                    $statuspage[$objectType][$index]['state_summary']['hosts']['cumulatedStateId'] = 2;
                    $statuspage[$objectType][$index]['state_summary']['hosts']['cumulatedStateName'] = __('Unknown');
                }


                if ($objectGroup['state_summary']['services']['state'][1] > 0) {
                    // Services is warning
                    $statuspage[$objectType][$index]['state_summary']['services']['cumulatedStateId'] = 1;
                    $statuspage[$objectType][$index]['state_summary']['services']['cumulatedStateName'] = __('Performance Issues');
                }
                if ($objectGroup['state_summary']['services']['state'][2] > 0) {
                    // Services is critical
                    $statuspage[$objectType][$index]['state_summary']['services']['cumulatedStateId'] = 2;
                    $statuspage[$objectType][$index]['state_summary']['services']['cumulatedStateName'] = __('Major Outage');
                }
                if ($objectGroup['state_summary']['services']['state'][3] > 0) {
                    // Services is unknown
                    $statuspage[$objectType][$index]['state_summary']['services']['cumulatedStateId'] = 3;
                    $statuspage[$objectType][$index]['state_summary']['services']['cumulatedStateName'] = __('Unknown');
                }
            }
        }
        $items = [];
        // Create total summary state of the complete Status page
        foreach (['hosts', 'services', 'hostgroups', 'servicegroups'] as $objectType) {
            foreach ($statuspage[$objectType] as $index => $objectGroup) {
                if ($objectType ==='hosts') {
                    $item = [];
                    $item['type'] = 'host';
                    $item['id'] = $objectGroup['id'];
                    $item['name'] = ($objectGroup['_joinData']['display_alias'] !== null && $objectGroup['_joinData']['display_alias'] !== '')
                        ? $objectGroup['_joinData']['display_alias'] : $objectGroup['name'];
                    if ($objectGroup['state_summary']['hosts']['cumulatedStateId'] > 0) {
                        $item['cumulatedStateName'] = $objectGroup['state_summary']['hosts']['cumulatedStateName'];
                        $item['cumulatedColorId'] = $objectGroup['state_summary']['hosts']['cumulatedStateId'] + 1;
                        $item['cumulatedColor'] = $this->getServiceStatusColor($item['cumulatedColorId']);
                        $item['background'] = 'bg-' . $item['cumulatedColor'];
                        if ($objectGroup['state_summary']['hosts']['acknowledgements'] > 0) {
                            $item['isAcknowledge'] = true;
                            $item['acknowledgedProblemsText'] = __('State is acknowledged');
                            $item['acknowledgeComment'] = ($statuspage['show_comments'])
                                ? $objectGroup['state_summary']['hosts']['acknowledgement_details'][0]['comment_data'] : __('Work in progress');;
                        }
                        if ($objectGroup['state_summary']['hosts']['acknowledgements'] === 0) {
                            $item['isAcknowledge'] = false;
                            $item['acknowledgedProblemsText'] = __('State is not acknowledged');
                        }
                    }
                    if ($objectGroup['state_summary']['hosts']['cumulatedStateId'] === 0) {
                        $item['cumulatedStateName'] = $objectGroup['state_summary']['services']['cumulatedStateName'];
                        $item['cumulatedColorId'] = $objectGroup['state_summary']['services']['cumulatedStateId'];
                        $item['cumulatedColor'] = $this->getServiceStatusColor($item['cumulatedColorId']);
                        $item['background'] = 'bg-' . $item['cumulatedColor'];
                        $problems = $objectGroup['state_summary']['services']['problems'];
                        if($problems > 0) {
                            $problemsAcknowledged = $objectGroup['state_summary']['services']['acknowledgements'];
                            $item['acknowledgedProblemsText'] = __('{0} of {1} problems acknowledged', $problemsAcknowledged, $problems);
                        }
                    }

                    if($objectGroup['state_summary']['hosts']['downtimes'] === 1) {
                        $downtimeDataHost = [];
                        $downtimeDataHost['scheduledStartTime'] = $this->time2string($objectGroup['state_summary']['hosts']['downtime_details'][0]['scheduledStartTime'], $userTime);
                        $downtimeDataHost['scheduledEndTime'] = $this->time2string($objectGroup['state_summary']['hosts']['downtime_details'][0]['scheduledEndTime'], $userTime);
                        $downtimeDataHost['comment'] = ($statuspage['show_comments'])
                            ? $objectGroup['state_summary']['hosts']['downtime_details'][0]['commentData'] : __('Work in progress');
                        $item['isInDowntime'] = true;
                        $item['downtimeData'] = $downtimeDataHost;
                    }

                    if(count($objectGroup['state_summary']['hosts']['planned_downtime_details']) > 0) {
                        $plannedDowntimeDataHosts = [];
                        foreach($objectGroup['state_summary']['hosts']['planned_downtime_details'] as $planned) {
                            $downtimePlannedDataHost = [];
                            $downtimePlannedDataHost['scheduledStartTime'] = $this->time2string($planned['scheduled_start_time'], $userTime);
                            $downtimePlannedDataHost['scheduledEndTime'] = $this->time2string($planned['scheduled_end_time'], $userTime);
                            $downtimePlannedDataHost['comment'] = ($statuspage['show_comments'])
                                ? $planned['comment_data'] : __('Work in progress');
                            $plannedDowntimeDataHosts[] = $downtimePlannedDataHost;
                        }
                        $item['plannedDowntimeData'] = $plannedDowntimeDataHosts;
                    }

                    $items[] = $item;
                }

                if ($objectType === 'services') {
                    $item = [];
                    $item['type'] = 'service';
                    $item['id'] = $objectGroup['id'];
                    $item['name'] = ($objectGroup['_joinData']['display_alias'] !== null && $objectGroup['_joinData']['display_alias'] !== '')
                        ? $objectGroup['_joinData']['display_alias'] : $objectGroup['servicename'];
                    $item['cumulatedStateName'] = $objectGroup['state_summary']['services']['cumulatedStateName'];
                    $item['cumulatedColorId'] = $objectGroup['state_summary']['services']['cumulatedStateId'];
                    $item['cumulatedColor'] = $this->getServiceStatusColor($item['cumulatedColorId']);
                    $item['background'] = 'bg-' . $item['cumulatedColor'];
                    if ($item['cumulatedColorId'] > 0) {
                        if($objectGroup['state_summary']['services']['acknowledgements'] === 0) {
                            $item['isAcknowledge'] = false;
                            $item['acknowledgedProblemsText'] = __('State is not acknowledged');
                        } else {
                            $item['isAcknowledge'] = true;
                            $item['acknowledgedProblemsText'] = __('State is acknowledged');
                            $item['acknowledgeComment'] = ($statuspage['show_comments'])
                                ? $objectGroup['state_summary']['services']['acknowledgement_details'][0]['comment_data'] : __('Work in progress');
                        }
                    }

                    if($objectGroup['state_summary']['services']['downtimes'] === 1) {
                        $downtimeDataService = [];
                        $downtimeDataService['scheduledStartTime'] = $this->time2string($objectGroup['state_summary']['services']['downtime_details'][0]['scheduledStartTime'], $userTime);
                        $downtimeDataService['scheduledEndTime'] = $this->time2string($objectGroup['state_summary']['services']['downtime_details'][0]['scheduledEndTime'], $userTime);
                        $downtimeDataService['comment'] = ($statuspage['show_comments'])
                            ? $objectGroup['state_summary']['services']['downtime_details'][0]['commentData'] : __('In progress');
                        $item['isInDowntime'] = true;
                        $item['downtimeData'] = $downtimeDataService;
                    }

                    if(count($objectGroup['state_summary']['services']['planned_downtime_details']) > 0) {
                        $plannedDowntimeDataServices = [];
                        foreach($objectGroup['state_summary']['services']['planned_downtime_details'] as $planned) {
                            $downtimePlannedDataService = [];
                            $downtimePlannedDataService['scheduledStartTime'] = $this->time2string($planned['scheduled_start_time'], $userTime);
                            $downtimePlannedDataService['scheduledEndTime'] = $this->time2string($planned['scheduled_end_time'], $userTime);
                            $downtimePlannedDataService['comment'] = ($statuspage['show_comments'])
                                ? $planned['comment_data'] : __('In progress');
                            $plannedDowntimeDataServices[] = $downtimePlannedDataService;
                        }
                        $item['plannedDowntimeData'] = $plannedDowntimeDataServices;
                    }

                    $items[] = $item;
                }

                if ($objectType ==='hostgroups') {
                    $item = [
                        'type' => 'hostgroup',
                        'cumulatedStateName' => __('Operational'),
                        'cumulatedColorId' => -1,
                        'cumulatedColor' => 'primary',
                        'background' => 'bg-primary'
                    ];
                    $item['id'] = $statuspage[$objectType][$index]['id'];
                    $item['name'] = ($objectGroup['_joinData']['display_alias'] !== null && $objectGroup['_joinData']['display_alias'] !== '')
                        ? $objectGroup['_joinData']['display_alias'] : $objectGroup['name'];
                    if ($objectGroup['state_summary']['hosts']['cumulatedStateId'] > 0) {
                        $item['cumulatedStateName'] = $objectGroup['state_summary']['hosts']['cumulatedStateName'];
                        $item['cumulatedColorId'] = $objectGroup['state_summary']['hosts']['cumulatedStateId'] + 1;
                        $item['cumulatedColor'] = $this->getServiceStatusColor($item['cumulatedColorId']);
                        $item['background'] = 'bg-' . $item['cumulatedColor'];
                    }

                    if ($objectGroup['state_summary']['hosts']['cumulatedStateId'] === 0) {
                        $item['cumulatedStateName'] = $objectGroup['state_summary']['services']['cumulatedStateName'];
                        $item['cumulatedColorId'] = $objectGroup['state_summary']['services']['cumulatedStateId'];
                        $item['cumulatedColor'] = $this->getServiceStatusColor($item['cumulatedColorId']);
                        $item['background'] = 'bg-' . $item['cumulatedColor'];
                    }

                    if($item['cumulatedColorId'] > 0) {
                        $problems = $objectGroup['state_summary']['hosts']['problems'];
                        if($problems > 0) {
                            $hostgroupAcknowledgements = $objectGroup['state_summary']['hosts']['acknowledgements'];
                            $item['hostgroupHostAcknowledgementText'] = __('{0} of {1} Hostproblems are acknowledged', $hostgroupAcknowledgements, $problems);
                        }
                        $problems = $objectGroup['state_summary']['services']['problems'];
                        if($problems > 0) {
                            $hostgroupAcknowledgements = $objectGroup['state_summary']['services']['acknowledgements'];
                            $item['hostgroupServiceAcknowledgementText'] = __('{0} of {1} Serviceproblems are acknowledged', $hostgroupAcknowledgements, $problems);
                        }
                    }

                    if($objectGroup['state_summary']['hosts']['downtimes'] > 0) {
                        $downtimes = $objectGroup['state_summary']['hosts']['downtimes'];
                        $total = $objectGroup['state_summary']['hosts']['total'];
                        $item['downtimeHostgroupHostText'] = __('{0} of {1} Hosts are currently in downtime', $downtimes, $total);
                    }
                    if(count($objectGroup['state_summary']['hosts']['planned_downtime_details']) > 0) {
                        $plannedDowntimes = count($objectGroup['state_summary']['hosts']['planned_downtime_details']);
                        $item['plannedDowntimeHostgroupHostText'] =
                            __('{0} Downtimes for hosts are planned in the next 10 Days', $plannedDowntimes);
                    }

                    if($objectGroup['state_summary']['services']['downtimes'] > 0) {
                        $downtimes = $objectGroup['state_summary']['services']['downtimes'];
                        $total = $objectGroup['state_summary']['services']['total'];
                        $item['downtimeHostgroupServiceText'] = __('{0} of {1} services are currently in downtime', $downtimes, $total);
                    }
                    if(count($objectGroup['state_summary']['services']['planned_downtime_details']) > 0) {
                        $plannedDowntimes = count($objectGroup['state_summary']['services']['planned_downtime_details']);
                        $item['plannedDowntimeHostgroupServiceText'] =
                            __('{0} Downtimes for services are planned in the next 10 Days', $plannedDowntimes);
                    }

                    $items[] = $item;
                }

                if ($objectType === 'servicegroups') {
                    $item = [
                        'type' => 'servicegroup',
                        'cumulatedStateName' => __('Operational'),
                        'cumulatedColorId' => -1,
                        'cumulatedColor' => 'primary',
                        'background' => 'bg-primary'
                    ];
                    $item['id'] = $objectGroup['id'];
                    $item['name'] = ($objectGroup['_joinData']['display_alias'] !== null && $objectGroup['_joinData']['display_alias'] !== '')
                        ? $objectGroup['_joinData']['display_alias'] : $objectGroup['name'];
                    $item['cumulatedStateName'] = $objectGroup['state_summary']['services']['cumulatedStateName'];
                    $item['cumulatedColorId'] = $objectGroup['state_summary']['services']['cumulatedStateId'];
                    $item['cumulatedColor'] = $this->getServiceStatusColor($item['cumulatedColorId']);
                    $item['background'] = 'bg-' . $item['cumulatedColor'];
                    if ($item['cumulatedColorId'] > 0) {
                        $problems = $objectGroup['state_summary']['services']['problems'];
                        if($problems > 0) {
                            $problemsAcknowledged = $objectGroup['state_summary']['services']['acknowledgements'];
                            $item['acknowledgedProblemsText'] = __('{0} of {1} problems acknowledged', $problemsAcknowledged, $problems);
                        }
                    }
                    if($objectGroup['state_summary']['services']['downtimes'] > 0) {
                        $downtimes = $objectGroup['state_summary']['services']['downtimes'];
                        $total = $objectGroup['state_summary']['services']['total'];
                        $item['downtimeText'] = __('{0} of {1} Services are currently in downtime', $downtimes, $total);
                    }
                    if(count($objectGroup['state_summary']['services']['planned_downtime_details']) > 0) {
                        $plannedDowntimes = count($objectGroup['state_summary']['services']['planned_downtime_details']);
                        $item['plannedDowntimeText'] =
                            __('{0} Downtimes are planned in the next 10 Days', $plannedDowntimes);
                    }
                    $items[] = $item;
                }
            }
        }

        $items = Hash::sort($items, '{n}.cumulatedColorId', 'desc');
        $statuspageView = [
            'statuspage' => [
                'name' => $statuspage['name'],
                'description' => $statuspage['description'],
                'public' => $statuspage['public'],
                'showComments' => $statuspage['show_comments'],
                'cumulatedColorId' => $items[0]['cumulatedColorId'] ?? -1,
                'cumulatedColor' => $items[0]['cumulatedColor'] ?? 'primary',
                'background' => !empty($items[0]['cumulatedColor']) ? 'bg-' . $items[0]['cumulatedColor'] : 'bg-primary'
            ],
            'items'   => $items,
        ];

        //debug($statuspage);
        //exit(1);
        return $statuspageView;
    }


    private function time2string(int $stamp, UserTime $userTime) {
        return $userTime->format($stamp);
    }

    /**
     * @param int $id
     * @param array $MY_RIGHTS
     * @return array|\Cake\Datasource\EntityInterface
     */
    public function getStatuspageWithAllObjects(int $id, array $MY_RIGHTS = []) {
        $query = $this->find()
            ->contain('Containers', function (Query $q) {
                $q->select([
                    'Containers.id',
                    'Containers.name'
                ]);
                return $q;
            })
            ->contain('Hosts', function (Query $q) use ($MY_RIGHTS) {
                $q
                    ->select([
                        'Hosts.id',
                        'Hosts.uuid',
                        'Hosts.name'
                    ]);
                if (!empty($MY_RIGHTS)) {
                    $q->innerJoin(['HostsToContainersSharing' => 'hosts_to_containers'], [
                        'HostsToContainersSharing.host_id = Hosts.id'
                    ]);
                    $q->where([
                        'HostsToContainersSharing.container_id IN' => $MY_RIGHTS
                    ]);
                }
                $q->contain([
                    'HostsToContainersSharing',
                    'Services' => function (Query $q) {
                        return $q->where([
                            'Services.disabled' => 0
                        ])
                            ->select([
                                'Services.id',
                                'Services.uuid',
                                'Services.host_id'
                            ]);
                    }
                ])->where([
                    'Hosts.disabled' => 0
                ]);

                return $q;
            })
            ->contain('Services', function (Query $q) use ($MY_RIGHTS) {
                return $q
                    ->select([
                        'Services.id',
                        'Services.uuid',
                        'Services.host_id',
                        'servicename' => $q->newExpr('IF(Services.name IS NULL, Servicetemplates.name, Services.name)'),
                    ])
                    ->contain([
                        'Servicetemplates' => function (Query $q) {
                            return $q->select([
                                'Servicetemplates.id',
                                'Servicetemplates.name'
                            ]);
                        },
                        'Hosts' => function (Query $q) {
                            return $q->select([
                                'Hosts.id',
                                'Hosts.uuid',
                                'Hosts.name'
                            ])
                                ->contain([
                                    'HostsToContainersSharing'
                                ]);
                        }

                    ])
                    ->innerJoinWith('Hosts')
                    ->innerJoinWith('Hosts.HostsToContainersSharing', function (Query $q) use ($MY_RIGHTS) {
                        if (!empty($MY_RIGHTS)) {
                            $q->where([
                                'HostsToContainersSharing.id IN ' => $MY_RIGHTS
                            ]);
                        }
                        return $q;
                    })
                    ->where([
                        'Services.disabled' => 0
                    ]);
            })
            ->contain('Hostgroups', function (Query $q) use ($MY_RIGHTS) {
                return $q
                    ->select([
                        'Hostgroups.id',
                        'Hostgroups.uuid',
                        'Containers.name',
                        'name' => 'Containers.name'
                    ])
                    ->contain([
                        'Containers'    => function (Query $q) {
                            return $q->select([
                                'Containers.id',
                                'Containers.name'
                            ]);
                        },
                        'Hosts'         => function (Query $q) use ($MY_RIGHTS) {
                            $q->select([
                                'Hosts.id',
                                'Hosts.uuid',
                                'Hosts.name'
                            ]);
                            if (!empty($MY_RIGHTS)) {
                                $q->innerJoin(['HostsToContainersSharing' => 'hosts_to_containers'], [
                                    'HostsToContainersSharing.host_id = Hosts.id'
                                ]);
                                $q->where([
                                    'HostsToContainersSharing.container_id IN' => $MY_RIGHTS
                                ]);
                            }
                            $q->contain([
                                'HostsToContainersSharing',
                                'Services' => function (Query $q) {
                                    return $q->where([
                                        'Services.disabled' => 0
                                    ])
                                        ->select([
                                            'Services.id',
                                            'Services.uuid',
                                            'Services.host_id'
                                        ]);
                                }
                            ])->where([
                                'Hosts.disabled' => 0
                            ]);
                            return $q;
                        },
                        'Hosttemplates' => function (Query $q) use ($MY_RIGHTS) {
                            return $q->enableAutoFields(false)
                                ->select([
                                    'id'
                                ])
                                ->contain([
                                    'Hosts' => function (Query $query) use ($MY_RIGHTS) {
                                        $query->select([
                                            'Hosts.id',
                                            'Hosts.uuid',
                                            'Hosts.name'
                                        ]);

                                        if (!empty($MY_RIGHTS)) {
                                            $query->innerJoin(['HostsToContainersSharing' => 'hosts_to_containers'], [
                                                'HostsToContainersSharing.host_id = Hosts.id'
                                            ]);
                                            $query->where([
                                                'HostsToContainersSharing.container_id IN' => $MY_RIGHTS
                                            ]);
                                        }

                                        $query
                                            ->disableAutoFields()
                                            ->select([
                                                'Hosts.id',
                                                'Hosts.uuid',
                                                'Hosts.name',
                                                'Hosts.hosttemplate_id'
                                            ])
                                            ->contain([
                                                'HostsToContainersSharing',
                                                'Services' => function (Query $q) {
                                                    return $q->select([
                                                        'Services.id',
                                                        'Services.uuid',
                                                        'Services.host_id'
                                                    ]);
                                                }
                                            ]);
                                        $query
                                            ->leftJoinWith('Hostgroups')
                                            ->whereNull('Hostgroups.id');
                                        return $query;
                                    }
                                ]);
                        }
                    ]);
            })
            ->contain('Servicegroups', function (Query $q) use ($MY_RIGHTS) {
                return $q
                    ->select([
                        'Servicegroups.id',
                        'Servicegroups.uuid',
                        'Containers.name',
                        'name' => 'Containers.name'
                    ])
                    ->contain([
                        'Containers'       => function (Query $q) use ($MY_RIGHTS) {
                            $q->select([
                                'Containers.id',
                                'Containers.name'
                            ]);
                            if (!empty($MY_RIGHTS)) {
                                return $q->where(['Containers.parent_id IN' => $MY_RIGHTS]);
                            }
                            return $q;
                        },
                        'Services'         => function (Query $q) use ($MY_RIGHTS) {
                            $q->select([
                                'Services.id',
                                'Services.uuid',
                                'Services.name',
                                'Services.host_id'
                            ])
                                ->contain([
                                    'Servicetemplates' => function (Query $q) {
                                        return $q->select([
                                            'Servicetemplates.id',
                                            'Servicetemplates.name'
                                        ]);
                                    },
                                    'Hosts'            => function (Query $q) {
                                        return $q->select([
                                            'Hosts.id',
                                            'Hosts.uuid',
                                            'Hosts.name'
                                        ])
                                            ->contain([
                                                'HostsToContainersSharing'
                                            ]);
                                    }

                                ])
                                ->innerJoinWith('Hosts')
                                ->innerJoinWith('Hosts.HostsToContainersSharing', function (Query $q) use ($MY_RIGHTS) {
                                    if (!empty($MY_RIGHTS)) {
                                        $q->where([
                                            'HostsToContainersSharing.id IN ' => $MY_RIGHTS
                                        ]);
                                    }
                                    return $q;
                                })
                                ->where([
                                    'Services.disabled' => 0
                                ]);
                            return $q;
                        },
                        'Servicetemplates' => function (Query $q) use ($MY_RIGHTS) {
                            return $q->enableAutoFields(false)
                                ->select([
                                    'id'
                                ])
                                ->contain([
                                    'Services' => function (Query $query) use ($MY_RIGHTS) {
                                        $query
                                            ->disableAutoFields()
                                            ->select([
                                                'Services.id',
                                                'Services.servicetemplate_id',
                                                'Services.uuid',
                                                'Services.name',
                                                'Services.host_id'
                                            ])
                                            ->contain([
                                                'Servicetemplates' => function (Query $q) {
                                                    return $q->select([
                                                        'Servicetemplates.id',
                                                        'Servicetemplates.name'
                                                    ]);
                                                },
                                                'Hosts'            => function (Query $q) {
                                                    return $q->select([
                                                        'Hosts.id',
                                                        'Hosts.uuid',
                                                        'Hosts.name'
                                                    ])
                                                        ->contain([
                                                            'HostsToContainersSharing'
                                                        ]);
                                                }
                                            ])
                                            ->innerJoinWith('Hosts')
                                            ->innerJoinWith('Hosts.HostsToContainersSharing', function (Query $q) use ($MY_RIGHTS) {
                                                if (!empty($MY_RIGHTS)) {
                                                    $q->where([
                                                        'HostsToContainersSharing.id IN ' => $MY_RIGHTS
                                                    ]);
                                                }
                                                return $q;
                                            })
                                            ->where([
                                                'Services.disabled' => 0,
                                                'Hosts.disabled'    => 0
                                            ]);
                                        $query
                                            ->leftJoinWith('Servicegroups')
                                            ->whereNull('Servicegroups.id');
                                        return $query;
                                    }
                                ]);
                        }
                    ]);
            })
            ->where([
                'Statuspages.id' => $id
            ])
            ->disableHydration();

        return $query->firstOrFail();
    }

}
