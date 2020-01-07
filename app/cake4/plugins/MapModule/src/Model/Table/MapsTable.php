<?php
declare(strict_types=1);

namespace MapModule\Model\Table;

use App\Lib\Interfaces\HoststatusTableInterface;
use App\Lib\Interfaces\ServicestatusTableInterface;
use App\Lib\Traits\Cake2ResultTableTrait;
use App\Lib\Traits\CustomValidationTrait;
use App\Lib\Traits\PaginationAndScrollIndexTrait;
use App\Model\Entity\Host;
use App\Model\Entity\Service;
use App\Model\Table\ContainersTable;
use App\Model\Table\HostgroupsTable;
use App\Model\Table\ServicegroupsTable;
use App\Model\Table\ServicesTable;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Association\HasMany;
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Utility\Hash;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\Core\DbBackend;
use itnovum\openITCOCKPIT\Core\HoststatusFields;
use itnovum\openITCOCKPIT\Core\MapConditions;
use itnovum\openITCOCKPIT\Core\ServicestatusConditions;
use itnovum\openITCOCKPIT\Core\ServicestatusFields;
use itnovum\openITCOCKPIT\Core\Views\BBCodeParser;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\MapFilter;
use MapModule\Model\Entity\Map;
use Statusengine\PerfdataParser;

/**
 * Maps Model
 *
 * @property MapgadgetsTable&HasMany $Mapgadgets
 * @property MapiconsTable&HasMany $Mapicons
 * @property MapitemsTable&HasMany $Mapitems
 * @property MaplinesTable&HasMany $Maplines
 * @property ContainersTable&HasMany $MapsToContainers
 * @property RotationsTable&HasMany $MapsToRotations
 * @property MapsummaryitemsTable&HasMany $Mapsummaryitems
 * @property MaptextsTable&HasMany $Maptexts
 *
 * @method Map get($primaryKey, $options = [])
 * @method Map newEntity($data = null, array $options = [])
 * @method Map[] newEntities(array $data, array $options = [])
 * @method Map|false save(EntityInterface $entity, $options = [])
 * @method Map saveOrFail(EntityInterface $entity, $options = [])
 * @method Map patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Map[] patchEntities($entities, array $data, array $options = [])
 * @method Map findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin TimestampBehavior
 */
class MapsTable extends Table {

    use Cake2ResultTableTrait;
    use PaginationAndScrollIndexTrait;
    use CustomValidationTrait;

    private $hostIcons = [
        0 => 'up.png',
        1 => 'down.png',
        2 => 'unreachable.png'
    ];
    private $serviceIcons = [
        0 => 'up.png',
        1 => 'warning.png',
        2 => 'critical.png',
        3 => 'unknown.png'
    ];
    private $ackIcon = 'ack.png';
    private $downtimeIcon = 'downtime.png';
    private $ackAndDowntimeIcon = 'downtime_ack.png';

    private $errorIcon = 'error.png';

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void {
        parent::initialize($config);

        $this->setTable('maps');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsToMany('Containers', [
            'className'        => 'Containers',
            'foreignKey'       => 'map_id',
            'targetForeignKey' => 'container_id',
            'joinTable'        => 'maps_to_containers',
            //'saveStrategy'     => 'replace'
        ]);

        $this->hasMany('Mapgadgets', [
            'foreignKey' => 'map_id',
            'className'  => 'MapModule.Mapgadgets',
        ]);
        $this->hasMany('Mapicons', [
            'foreignKey' => 'map_id',
            'className'  => 'MapModule.Mapicons',
        ]);
        $this->hasMany('Mapitems', [
            'foreignKey' => 'map_id',
            'className'  => 'MapModule.Mapitems',
        ]);
        $this->hasMany('Maplines', [
            'foreignKey' => 'map_id',
            'className'  => 'MapModule.Maplines',
        ]);
        $this->hasMany('MapsToRotations', [
            'foreignKey' => 'map_id',
            'className'  => 'MapModule.MapsToRotations',
        ]);
        $this->hasMany('Mapsummaryitems', [
            'foreignKey' => 'map_id',
            'className'  => 'MapModule.Mapsummaryitems',
        ]);
        $this->hasMany('Maptexts', [
            'foreignKey' => 'map_id',
            'className'  => 'MapModule.Maptexts',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param Validator $validator Validator instance.
     * @return Validator
     */
    public function validationDefault(Validator $validator): Validator {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

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
            ->scalar('title')
            ->maxLength('title', 255)
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->scalar('background')
            ->maxLength('background', 128)
            ->allowEmptyString('background');

        $validator
            ->integer('refresh_interval')
            ->notEmptyString('refresh_interval');

        return $validator;
    }

    /**
     * @param MapFilter $MapFilter
     * @param null|PaginateOMat $PaginateOMat
     * @param array $MY_RIGHTS
     * @return array
     */
    public function getMapsIndex(MapFilter $MapFilter, $PaginateOMat = null, $MY_RIGHTS = []) {
        if (!is_array($MY_RIGHTS)) {
            $MY_RIGHTS = [$MY_RIGHTS];
        }
        $query = $this->find('all')
            ->where($MapFilter->indexFilter())
            ->distinct('Maps.id')
            ->contain(['Containers'])
            ->innerJoinWith('Containers', function (Query $query) use ($MY_RIGHTS) {
                if (!empty($MY_RIGHTS)) {
                    return $query->where(['Containers.id IN' => $MY_RIGHTS]);
                }
                return $query;
            })
            ->order($MapFilter->getOrderForPaginator('Maps.name', 'asc'));

        if ($PaginateOMat === null) {
            //Just execute query
            $result = $query->toArray();
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
     * @param int $id
     * @return bool
     */
    public function existsById($id) {
        return $this->exists(['Maps.id' => $id]);
    }

    /**
     * @param $realMapId
     * @param $mapItemMapId
     * @return array
     */
    public function getMapsForMaps($realMapId, $mapItemMapId) {
        $query = $this->find()
            ->contain(['Containers'])
            ->join([
                'table'      => 'mapitems',
                'type'       => 'INNER',
                'alias'      => 'Mapitems',
                'conditions' => 'Mapitems.object_id = Maps.id',
            ])
            ->where([
                'Maps.id'         => $realMapId,
                'Mapitems.map_id' => $mapItemMapId,
            ]);

        $result = $query->first();
        if (empty($result)) {
            return [];
        }
        return $query->toArray();
    }

    /**
     * @param $realMapId
     * @param $mapItemMapId
     * @return array
     */
    public function getMapsForMapsummaryitems($realMapId, $mapItemMapId) {
        $query = $this->find()
            ->contain(['Containers'])
            ->join([
                'table'      => 'mapsummaryitems',
                'type'       => 'INNER',
                'alias'      => 'Mapsummaryitems',
                'conditions' => 'Mapsummaryitems.object_id = Maps.id',
            ])
            ->where([
                'Maps.id'                => $realMapId,
                'Mapsummaryitems.map_id' => $mapItemMapId,
            ]);

        $result = $query->first();
        if (empty($result)) {
            return [];
        }
        return $query->toArray();
    }

    /**
     * @param int $id
     * @return array
     */
    public function getMapForEdit($id) {
        $query = $this->find()
            ->where([
                'Maps.id' => $id
            ])
            ->contain([
                'Containers'
            ])
            ->disableHydration()
            ->first();


        $contact = $query;
        $contact['containers'] = [
            '_ids' => Hash::extract($query, 'containers.{n}.id')
        ];

        return [
            'Map' => $contact
        ];
    }

    /**
     * @param array $ids
     * @param array $MY_RIGHTS
     * @return array
     */
    public function getMapsForCopy($ids = [], $MY_RIGHTS = []) {
        if (!is_array($MY_RIGHTS)) {
            $MY_RIGHTS = [$MY_RIGHTS];
        }

        $query = $this->find()
            ->select([
                'Maps.id',
                'Maps.name',
                'Maps.title',
                'Maps.refresh_interval'
            ])
            ->where(['Maps.id IN' => $ids])
            ->order(['Maps.id' => 'asc'])
            ->contain(['Containers'])
            ->innerJoinWith('Containers', function (Query $query) use ($MY_RIGHTS) {
                if (!empty($MY_RIGHTS)) {
                    return $query->where(['Containers.id IN' => $MY_RIGHTS]);
                }
                return $query;
            })
            ->group(['Maps.id'])
            ->disableHydration()
            ->all();

        return $query->toArray();
    }

    /**
     * @param MapConditions $MapConditions
     * @param array $selected
     * @param array $excluded
     * @return array|null
     */
    public function getMapsForAngular(MapConditions $MapConditions, $selected = [], $excluded = []) {
        if (!is_array($selected)) {
            $selected = [$selected];
        }
        $query = $this->find('list')
            ->limit(ITN_AJAX_LIMIT)
            ->select([
                'Maps.id',
                'Maps.name'
            ])
            ->join([
                'table'      => 'maps_to_containers',
                'alias'      => 'MapsToContainers',
                'type'       => 'INNER',
                'conditions' => [
                    'MapsToContainers.map_id = Maps.id',
                ]
            ])->where($MapConditions->getConditionsForFind());

        $selected = array_filter($selected);
        if (!empty($selected)) {
            $query->where([
                'Maps.id NOT IN' => $selected
            ]);
        }

        $query->order(['Maps.name' => 'ASC'])->group('Maps.id');
        $mapsWithLimit = $query->toArray();

        $selectedMaps = [];
        if (!empty($selected)) {
            $query = $this->find('list')
                ->select([
                    'Maps.id',
                    'Maps.name'
                ])
                ->where([
                    'Maps.id IN' => $selected
                ])
                ->join([
                    'table'      => 'maps_to_containers',
                    'alias'      => 'MapsToContainers',
                    'type'       => 'INNER',
                    'conditions' => [
                        'MapsToContainers.map_id = Maps.id',
                    ]
                ]);

            if ($MapConditions->hasContainer()) {
                $query->where([
                    'MapsToContainers.container_id IN' => $MapConditions->getContainerIds()
                ]);
            }
            $query->orderAsc('Maps.name')->groupBy('Maps.id');
            $selectedMaps = $query->toArray();
        }
        $maps = $mapsWithLimit + $selectedMaps;
        if (is_array($excluded) && !empty($excluded)) {
            foreach ($excluded as $idToExclude) {
                if (isset($maps[$idToExclude])) {
                    unset($maps[$idToExclude]);
                }
            }
        }
        asort($maps, SORT_FLAG_CASE | SORT_NATURAL);
        return $maps;
    }

    /**
     * @param ServicesTable $Service
     * @param HoststatusTableInterface $Hoststatus
     * @param ServicestatusTableInterface $Servicestatus
     * @param Host $host
     * @return array
     */
    public function getHostInformation(ServicesTable $Service, HoststatusTableInterface $Hoststatus, ServicestatusTableInterface $Servicestatus, Host $host) {
        $HoststatusFields = new HoststatusFields(new DbBackend());
        $HoststatusFields->currentState()->scheduledDowntimeDepth()->problemHasBeenAcknowledged();
        $hoststatus = $Hoststatus->byUuid($host->get('uuid'), $HoststatusFields);
        $HostView = new \itnovum\openITCOCKPIT\Core\Views\Host($host->toArray());

        if (empty($hoststatus) || $host->get('disabled')) {
            $HoststatusView = new \itnovum\openITCOCKPIT\Core\Hoststatus([]);
            return [
                'icon'           => $this->errorIcon,
                'icon_property'  => $this->errorIcon,
                'isAcknowledged' => false,
                'isInDowntime'   => false,
                'color'          => 'text-primary',
                'background'     => 'bg-color-blueLight',
                'Host'           => $HostView->toArray(),
                'Hoststatus'     => $HoststatusView->toArray(),
            ];
        }

        $hoststatus = new \itnovum\openITCOCKPIT\Core\Hoststatus($hoststatus['Hoststatus']);
        $icon = $this->hostIcons[$hoststatus->currentState()];
        $color = $hoststatus->HostStatusColor();
        $background = $hoststatus->HostStatusBackgroundColor();

        $iconProperty = $icon;
        if ($hoststatus->isAcknowledged()) {
            $iconProperty = $this->ackIcon;
        }

        if ($hoststatus->isInDowntime()) {
            $iconProperty = $this->downtimeIcon;
        }

        if ($hoststatus->isAcknowledged() && $hoststatus->isInDowntime()) {
            $iconProperty = $this->ackAndDowntimeIcon;
        }

        if ($hoststatus->currentState() > 0) {
            return [
                'icon'           => $icon,
                'icon_property'  => $this->errorIcon,
                'isAcknowledged' => $hoststatus->isAcknowledged(),
                'isInDowntime'   => $hoststatus->isInDowntime(),
                'color'          => $color,
                'background'     => $background,
                'Host'           => $HostView->toArray(),
                'Hoststatus'     => $hoststatus->toArray(),
            ];
        }

        //Check services for cumulated state (only if host is up)

        $services = $Service->getActiveServicesByHostId($host->get('id'), false);
        $serviceUuids = Hash::extract($services->toArray(), '{n}.uuid');

        $ServicestatusFieds = new ServicestatusFields(new DbBackend());
        $ServicestatusFieds->currentState()->scheduledDowntimeDepth()->problemHasBeenAcknowledged();
        $ServicestatusConditions = new ServicestatusConditions(new DbBackend());
        $ServicestatusConditions->servicesWarningCriticalAndUnknown();
        $servicestatus = $Servicestatus->byUuid($serviceUuids, $ServicestatusFieds, $ServicestatusConditions);

        if (!empty($servicestatus)) {
            $worstServiceState = array_values(
                Hash::sort($servicestatus, '{s}.Servicestatus.current_state', 'desc')
            );

            $servicestatus = new \itnovum\openITCOCKPIT\Core\Servicestatus($worstServiceState[0]['Servicestatus']);
            $serviceIcon = $this->serviceIcons[$servicestatus->currentState()];

            $serviceIconProperty = $serviceIcon;
            if ($servicestatus->isAcknowledged()) {
                $serviceIconProperty = $this->ackIcon;
            }

            if ($servicestatus->isInDowntime()) {
                $serviceIconProperty = $this->downtimeIcon;
            }

            if ($servicestatus->isAcknowledged() && $servicestatus->isInDowntime()) {
                $serviceIconProperty = $this->ackAndDowntimeIcon;
            }

            return [
                'icon'           => $serviceIcon,
                'icon_property'  => $serviceIconProperty,
                'isAcknowledged' => $servicestatus->isAcknowledged(),
                'isInDowntime'   => $servicestatus->isInDowntime(),
                'color'          => $servicestatus->ServiceStatusColor(),
                'background'     => $servicestatus->ServiceStatusBackgroundColor(),
                'Host'           => $HostView->toArray(),
                'Hoststatus'     => $hoststatus->toArray(),
            ];
        }

        return [
            'icon'           => $icon,
            'icon_property'  => $iconProperty,
            'isAcknowledged' => $hoststatus->isAcknowledged(),
            'isInDowntime'   => $hoststatus->isInDowntime(),
            'color'          => $color,
            'background'     => $background,
            'Host'           => $HostView->toArray(),
            'Hoststatus'     => $hoststatus->toArray()
        ];
    }

    /**
     * @param ServicestatusTableInterface $Servicestatus
     * @param Service $service
     * @param bool $includeServiceOutput
     * @return array
     */
    public function getServiceInformation(ServicestatusTableInterface $Servicestatus, Service $service, $includeServiceOutput = false) {
        $ServicestatusFields = new ServicestatusFields(new DbBackend());
        $ServicestatusFields->currentState()->scheduledDowntimeDepth()->problemHasBeenAcknowledged()->perfdata()->isFlapping();
        if ($includeServiceOutput === true) {
            $ServicestatusFields->output()->longOutput();
        }
        $serviceArray = $service->toArray();
        $servicestatus = $Servicestatus->byUuid($service->get('uuid'), $ServicestatusFields);
        $HostView = new \itnovum\openITCOCKPIT\Core\Views\Host($serviceArray['host']);
        $ServiceView = new \itnovum\openITCOCKPIT\Core\Views\Service($serviceArray);
        if (empty($servicestatus) || $service->get('disabled')) {
            $ServicestatusView = new \itnovum\openITCOCKPIT\Core\Servicestatus([]);
            $tmpServicestatus = $ServicestatusView->toArray();
            if ($includeServiceOutput === true) {
                $tmpServicestatus['output'] = null;
                $tmpServicestatus['longOutputHtml'] = null;
            }

            return [
                'icon'           => $this->errorIcon,
                'icon_property'  => $this->errorIcon,
                'isAcknowledged' => false,
                'isInDowntime'   => false,
                'color'          => 'text-primary',
                'background'     => 'bg-color-blueLight',
                'Host'           => $HostView->toArray(),
                'Service'        => $ServiceView->toArray(),
                'Servicestatus'  => $tmpServicestatus,
                'Perfdata'       => []
            ];
        }

        $servicestatus = new \itnovum\openITCOCKPIT\Core\Servicestatus($servicestatus['Servicestatus']);

        $icon = $this->serviceIcons[$servicestatus->currentState()];

        $iconProperty = $icon;
        if ($servicestatus->isAcknowledged()) {
            $iconProperty = $this->ackIcon;
        }

        if ($servicestatus->isInDowntime()) {
            $iconProperty = $this->downtimeIcon;
        }

        if ($servicestatus->isAcknowledged() && $servicestatus->isInDowntime()) {
            $iconProperty = $this->ackAndDowntimeIcon;
        }

        $perfdata = new PerfdataParser($servicestatus->getPerfdata());

        $tmpServicestatus = $servicestatus->toArray();
        if ($includeServiceOutput === true) {
            $Parser = new BBCodeParser();
            $tmpServicestatus['output'] = h($servicestatus->getOutput());
            $tmpServicestatus['longOutputHtml'] = $Parser->nagiosNl2br($Parser->asHtml($servicestatus->getLongOutput(), true));
        }

        return [
            'icon'           => $icon,
            'icon_property'  => $iconProperty,
            'isAcknowledged' => $servicestatus->isAcknowledged(),
            'isInDowntime'   => $servicestatus->isInDowntime(),
            'color'          => $servicestatus->ServiceStatusColor(),
            'background'     => $servicestatus->ServiceStatusBackgroundColor(),
            'Host'           => $HostView->toArray(),
            'Service'        => $ServiceView->toArray(),
            'Perfdata'       => $perfdata->parse(),
            'Servicestatus'  => $tmpServicestatus
        ];
    }

    /**
     * @param ServicesTable $Service
     * @param array $hostgroup
     * @param HoststatusTableInterface $HoststatusTable
     * @param ServicestatusTableInterface $ServicestatusTable
     * @return array
     */
    public function getHostgroupInformation(ServicesTable $Service, array $hostgroup, HoststatusTableInterface $HoststatusTable, ServicestatusTableInterface $ServicestatusTable) {
        $HoststatusFields = new HoststatusFields(new DbBackend());
        $HoststatusFields->currentState()->scheduledDowntimeDepth()->problemHasBeenAcknowledged();

        $hostUuids = \Cake\Utility\Hash::extract($hostgroup['hosts'], '{n}.uuid');

        $hoststatusByUuids = $HoststatusTable->byUuid($hostUuids, $HoststatusFields);
        $hostgroupLight = [
            'id'          => (int)$hostgroup['id'],
            'name'        => $hostgroup['Containers']['name'],
            'description' => $hostgroup['description']
        ];

        if (empty($hoststatusByUuids)) {
            return [
                'icon'       => $this->errorIcon,
                'color'      => 'text-primary',
                'background' => 'bg-color-blueLight',
                'Hostgroup'  => $hostgroupLight
            ];
        }
        $worstHostState = array_values(
            Hash::sort($hoststatusByUuids, '{s}.Hoststatus.current_state', 'desc')
        );

        $hoststatus = new \itnovum\openITCOCKPIT\Core\Hoststatus($worstHostState[0]['Hoststatus']);

        $icon = $this->hostIcons[$hoststatus->currentState()];
        $color = $hoststatus->HostStatusColor();
        $background = $hoststatus->HostStatusBackgroundColor();


        if ($hoststatus->isAcknowledged()) {
            $icon = $this->ackIcon;
        }

        if ($hoststatus->isInDowntime()) {
            $icon = $this->downtimeIcon;
        }

        if ($hoststatus->isAcknowledged() && $hoststatus->isInDowntime()) {
            $icon = $this->ackAndDowntimeIcon;
        }

        if ($hoststatus->currentState() > 0) {
            return [
                'icon'       => $icon,
                'color'      => $color,
                'background' => $background,
                'Hostgroup'  => $hostgroupLight
            ];
        }

        //Check services for cumulated state (only if host is up)
        $hostIds = \Cake\Utility\Hash::extract($hostgroup['hosts'], '{n}.id');

        //Check services for cumulated state (only if host is up)
        $services = $Service->getActiveServicesByHostIds($hostIds, false);

        $ServicestatusFieds = new ServicestatusFields(new DbBackend());
        $ServicestatusFieds->currentState()->scheduledDowntimeDepth()->problemHasBeenAcknowledged();
        $ServicestatusConditions = new ServicestatusConditions(new DbBackend());
        $ServicestatusConditions->servicesWarningCriticalAndUnknown();
        $servicestatus = $ServicestatusTable->byUuid($services, $ServicestatusFieds, $ServicestatusConditions);

        if (!empty($servicestatus)) {
            $worstServiceState = array_values(
                Hash::sort($servicestatus, '{s}.Servicestatus.current_state', 'desc')
            );

            $servicestatus = new \itnovum\openITCOCKPIT\Core\Servicestatus($worstServiceState[0]['Servicestatus']);
            $serviceIcon = $this->serviceIcons[$servicestatus->currentState()];

            if ($servicestatus->isAcknowledged()) {
                $serviceIcon = $this->ackIcon;
            }

            if ($servicestatus->isInDowntime()) {
                $serviceIcon = $this->downtimeIcon;
            }

            if ($servicestatus->isAcknowledged() && $servicestatus->isInDowntime()) {
                $serviceIcon = $this->ackAndDowntimeIcon;
            }
            return [
                'icon'       => $serviceIcon,
                'color'      => $servicestatus->ServiceStatusColor(),
                'background' => $servicestatus->ServiceStatusBackgroundColor(),
                'Hostgroup'  => $hostgroupLight
            ];
        }

        return [
            'icon'       => $icon,
            'color'      => $color,
            'background' => $background,
            'Hostgroup'  => $hostgroupLight
        ];
    }

    /**
     * @param ServicesTable $Service
     * @param ServicestatusTableInterface $Servicestatus
     * @param array $servicegroup
     * @return array
     */
    public function getServicegroupInformation(ServicesTable $Service, ServicestatusTableInterface $Servicestatus, $servicegroup = []) {
        $ServicestatusFields = new ServicestatusFields(new DbBackend());
        $ServicestatusFields->currentState()->scheduledDowntimeDepth()->problemHasBeenAcknowledged();

        $serviceUuids = Hash::extract($servicegroup['services'], '{n}.uuid');

        $servicestatusByUuids = $Servicestatus->byUuid($serviceUuids, $ServicestatusFields);

        $servicegroupLight = [
            'id'          => (int)$servicegroup['id'],
            'name'        => $servicegroup['container']['name'],
            'description' => $servicegroup['description']
        ];

        if (empty($servicestatusByUuids)) {
            return [
                'icon'         => $this->errorIcon,
                'color'        => 'text-primary',
                'background'   => 'bg-color-blueLight',
                'Servicegroup' => $servicegroupLight
            ];
        }
        $worstServiceState = array_values(
            Hash::sort($servicestatusByUuids, '{s}.Servicestatus.current_state', 'desc')
        );

        $servicestatus = new \itnovum\openITCOCKPIT\Core\Servicestatus($worstServiceState[0]['Servicestatus']);

        $icon = $this->serviceIcons[$servicestatus->currentState()];
        $color = $servicestatus->ServiceStatusColor();
        $background = $servicestatus->ServiceStatusBackgroundColor();


        if ($servicestatus->isAcknowledged()) {
            $icon = $this->ackIcon;
        }

        if ($servicestatus->isInDowntime()) {
            $icon = $this->downtimeIcon;
        }

        if ($servicestatus->isAcknowledged() && $servicestatus->isInDowntime()) {
            $icon = $this->ackAndDowntimeIcon;
        }

        return [
            'icon'         => $icon,
            'color'        => $color,
            'background'   => $background,
            'Servicegroup' => $servicegroupLight
        ];
    }

    /**
     * @param $dependentMapsIds
     * @param HostgroupsTable $HostgroupsTable
     * @param ServicegroupsTable $ServicegroupsTable
     * @return array
     */
    public function getAllDependentMapsElements($dependentMapsIds, HostgroupsTable $HostgroupsTable, ServicegroupsTable $ServicegroupsTable, $MY_RIGHTS = []) {
        if (!is_array($MY_RIGHTS)) {
            $MY_RIGHTS = [$MY_RIGHTS];
        }
        if (!is_array($dependentMapsIds)) {
            $dependentMapsIds = [$dependentMapsIds];
        }

        $allDependentMapElements = $this->find()
            ->contain([
                'Mapitems'        => function (Query $query) {
                    $query->select([
                        'Mapitems.type',
                        'Mapitems.object_id',
                        'Mapitems.map_id'
                    ])->where([
                        'Mapitems.type !=' => 'map'
                    ]);
                    return $query;
                },
                'Maplines'        => function (Query $query) {
                    $query->select([
                        'Maplines.type',
                        'Maplines.object_id',
                        'Maplines.map_id'
                    ])->where([
                        'Maplines.type !=' => 'stateless'
                    ]);
                    return $query;
                },
                'Mapgadgets'      => function (Query $query) {
                    $query->select([
                        'Mapgadgets.type',
                        'Mapgadgets.object_id',
                        'Mapgadgets.map_id'
                    ]);
                    return $query;
                },
                'Mapsummaryitems' => function (Query $query) {
                    $query->select([
                        'Mapsummaryitems.type',
                        'Mapsummaryitems.object_id',
                        'Mapsummaryitems.map_id'
                    ])->where([
                        'Mapsummaryitems.type !=' => 'map'
                    ]);
                    return $query;
                },
            ])
            ->where([
                'Maps.id IN' => $dependentMapsIds
            ])
            ->all()->toArray();
        /*
        $allDependentMapElements = $this->find('all', [
            'recursive'  => -1,
            'contain'    => [
                'Mapitem'        => [
                    'conditions' => [
                        'NOT' => [
                            'Mapitem.type' => 'map'
                        ]
                    ],
                    'fields'     => [
                        'Mapitem.type',
                        'Mapitem.object_id'
                    ]
                ],
                'Mapline'        => [
                    'conditions' => [
                        'NOT' => [
                            'Mapline.type' => 'stateless'
                        ]
                    ],
                    'fields'     => [
                        'Mapline.type',
                        'Mapline.object_id'
                    ]
                ],
                'Mapgadget'      => [
                    'fields' => [
                        'Mapgadget.type',
                        'Mapgadget.object_id'
                    ]
                ],
                'Mapsummaryitem' => [
                    'conditions' => [
                        'NOT' => [
                            'Mapsummaryitem.type' => 'map'
                        ]
                    ],
                    'fields'     => [
                        'Mapsummaryitem.type',
                        'Mapsummaryitem.object_id'
                    ]
                ]
            ],
            'conditions' => [
                'Map.id' => $dependentMapsIds
            ]
        ]);
        */
        $mapElementsByCategory = [
            'host'         => [],
            'hostgroup'    => [],
            'service'      => [],
            'servicegroup' => []
        ];
        $allDependentMapElements = Hash::filter($allDependentMapElements);

        foreach ($allDependentMapElements as $allDependentMapElementArray) {
            foreach ($allDependentMapElementArray->toArray() as $mapElementKey => $mapElementData) {
                if ($mapElementKey === 'Map') {
                    continue;
                }
                if (is_array($mapElementData)) {
                    foreach ($mapElementData as $mapElement) {
                        $mapElementsByCategory[$mapElement['type']][$mapElement['object_id']] = $mapElement['object_id'];
                    }
                }
            }

        }

        $hostIds = $mapElementsByCategory['host'];
        if (!empty($mapElementsByCategory['hostgroup'])) {
            $query = $HostgroupsTable->find()
                ->join([
                    [
                        'table'      => 'hosts_to_hostgroups',
                        'type'       => 'INNER',
                        'alias'      => 'HostsToHostgroups',
                        'conditions' => 'HostsToHostgroups.hostgroup_id = Hostgroups.id',
                    ]
                ])
                ->select([
                    'HostsToHostgroups.host_id'
                ])
                ->where([
                    'Hostgroups.id IN' => $mapElementsByCategory['hostgroup']
                ]);
            /*
            $query = [
                'recursive'  => -1,
                'joins'      => [
                    [
                        'table'      => 'hosts_to_hostgroups',
                        'type'       => 'INNER',
                        'alias'      => 'HostsToHostgroups',
                        'conditions' => 'HostsToHostgroups.hostgroup_id = Hostgroup.id',
                    ],
                ],
                'fields'     => [
                    'HostsToHostgroups.host_id'
                ],
                'conditions' => [
                    'Hostgroup.id' => $mapElementsByCategory['hostgroup']
                ]
            ];
            */
            if (!empty($MY_RIGHTS)) {
                $query->where([
                    'Hostgroups.container_id IN' => $MY_RIGHTS
                ]);
            }

            $hostIdsByHostgroup = $query->all()->toArray();
            foreach ($hostIdsByHostgroup as $hostIdByHostgroup) {
                $hostIds[$hostIdByHostgroup['HostsToHostgroups']['host_id']] = $hostIdByHostgroup['HostsToHostgroups']['host_id'];
            }
        }
        $serviceIds = $mapElementsByCategory['service'];
        if (!empty($mapElementsByCategory['servicegroup'])) {
            $query = $ServicegroupsTable->find()
                ->join([
                    [
                        'table'      => 'services_to_servicegroups',
                        'type'       => 'INNER',
                        'alias'      => 'ServicesToServicegroups',
                        'conditions' => 'ServicesToServicegroups.servicegroup_id = Servicegroups.id',
                    ]
                ])
                ->select([
                    'ServicesToServicegroups.service_id'
                ])
                ->where([
                    'Servicegroups.id IN' => $mapElementsByCategory['servicegroup']
                ]);
            /*
            $query = [
                'recursive'  => -1,
                'joins'      => [
                    [
                        'table'      => 'services_to_servicegroups',
                        'type'       => 'INNER',
                        'alias'      => 'ServicesToServicegroups',
                        'conditions' => 'ServicesToServicegroups.servicegroup_id = Servicegroup.id',
                    ],
                ],
                'fields'     => [
                    'ServicesToServicegroups.service_id'

                ],
                'conditions' => [
                    'Servicegroup.id' => $mapElementsByCategory['servicegroup']
                ]
            ];
            */
            if (!empty($MY_RIGHTS)) {
                $query->where([
                    'Servicegroups.container_id IN' => $MY_RIGHTS
                ]);
            }

            $serviceIdsByServicegroup = $query->all()->toArray();
            foreach ($serviceIdsByServicegroup as $serviceIdByServicegroup) {
                $serviceIds[$serviceIdByServicegroup['ServicesToServicegroups']['service_id']] = $serviceIdByServicegroup['ServicesToServicegroups']['service_id'];
            }
        }
        return [
            'hostIds'    => $hostIds,
            'serviceIds' => $serviceIds
        ];
    }

    /**
     * @param HoststatusTableInterface $HoststatusTable
     * @param ServicestatusTableInterface $ServicestatusTable
     * @param array $map
     * @param array $hosts
     * @param array $services
     * @return array
     */
    public function getMapInformation(HoststatusTableInterface $HoststatusTable, ServicestatusTableInterface $ServicestatusTable, array $map, array $hosts, array $services) {
        $map = [
            'id'    => $map[0]['id'],
            'name'  => $map[0]['name'],
            'title' => $map[0]['title']
        ];

        if (empty($hosts) && empty($services)) {
            return [
                'icon'       => $this->errorIcon,
                'color'      => 'text-primary',
                'background' => 'bg-color-blueLight',
                'Map'        => $map
            ];
        }

        $hostsUuids = Hash::extract($hosts, '{n}.Host.uuid');

        $HoststatusFields = new HoststatusFields(new DbBackend());
        $HoststatusFields->currentState()->scheduledDowntimeDepth()->problemHasBeenAcknowledged();
        $hoststatusByUuids = $HoststatusTable->byUuid($hostsUuids, $HoststatusFields);

        if (empty($hoststatusByUuids)) {
            $hoststatus = new \itnovum\openITCOCKPIT\Core\Hoststatus([]);
            $icon = $this->errorIcon;
            $color = $hoststatus->HostStatusColor();
            $background = $hoststatus->HostStatusBackgroundColor();
            $iconProperty = $icon;
        } else {
            $worstHostState = array_values(
                Hash::sort($hoststatusByUuids, '{s}.Hoststatus.current_state', 'desc')
            );
            if (!empty($worstHostState)) {
                $hoststatus = new \itnovum\openITCOCKPIT\Core\Hoststatus($worstHostState[0]['Hoststatus']);
            }
            $icon = $this->hostIcons[$hoststatus->currentState()];
            $color = $hoststatus->HostStatusColor();
            $background = $hoststatus->HostStatusBackgroundColor();
            $iconProperty = $icon;


            if ($hoststatus->isAcknowledged()) {
                $iconProperty = $this->ackIcon;
            }

            if ($hoststatus->isInDowntime()) {
                $iconProperty = $this->downtimeIcon;
            }

            if ($hoststatus->isAcknowledged() && $hoststatus->isInDowntime()) {
                $iconProperty = $this->ackAndDowntimeIcon;
            }
            if ($hoststatus->currentState() > 0) {
                return [
                    'icon'          => $icon,
                    'icon_property' => $iconProperty,
                    'color'         => $color,
                    'background'    => $background,
                    'Map'           => $map
                ];
            }
        }

        $servicesUuids = Hash::extract($services, '{n}.Service.uuid');
        $ServicestatusFieds = new ServicestatusFields(new DbBackend());
        $ServicestatusFieds->currentState()->scheduledDowntimeDepth()->problemHasBeenAcknowledged();
        $ServicestatusConditions = new ServicestatusConditions(new DbBackend());
        $ServicestatusConditions->servicesWarningCriticalAndUnknown();
        $servicestatus = $ServicestatusTable->byUuid($servicesUuids, $ServicestatusFieds, $ServicestatusConditions);

        if (!empty($servicestatus)) {
            $worstServiceState = array_values(
                Hash::sort($servicestatus, '{s}.Servicestatus.current_state', 'desc')
            );
            $servicestatus = new \itnovum\openITCOCKPIT\Core\Servicestatus($worstServiceState[0]['Servicestatus']);
            $serviceIcon = $this->serviceIcons[$servicestatus->currentState()];

            $serviceIconProperty = $serviceIcon;
            if ($servicestatus->isAcknowledged()) {
                $serviceIconProperty = $this->ackIcon;
            }

            if ($servicestatus->isInDowntime()) {
                $serviceIconProperty = $this->downtimeIcon;
            }

            if ($servicestatus->isAcknowledged() && $servicestatus->isInDowntime()) {
                $serviceIconProperty = $this->ackAndDowntimeIcon;
            }

            return [
                'icon'           => $serviceIcon,
                'icon_property'  => $serviceIconProperty,
                'isAcknowledged' => $servicestatus->isAcknowledged(),
                'isInDowntime'   => $servicestatus->isInDowntime(),
                'color'          => $servicestatus->ServiceStatusColor(),
                'background'     => $servicestatus->ServiceStatusBackgroundColor(),
                'Map'            => $map,
            ];
        }
        return [
            'icon'           => $icon,
            'icon_property'  => $iconProperty,
            'isAcknowledged' => $hoststatus->isAcknowledged(),
            'isInDowntime'   => $hoststatus->isInDowntime(),
            'color'          => $color,
            'background'     => $background,
            'Map'            => $map
        ];
    }

    /**
     * @param HoststatusTableInterface $HoststatusTable
     * @param ServicestatusTableInterface $ServicestatusTable
     * @param array $host
     * @return array
     */
    public function getHostInformationForSummaryIcon(HoststatusTableInterface $HoststatusTable, ServicestatusTableInterface $ServicestatusTable, array $host) {
        $bitMaskHostState = 0;
        $bitMaskServiceState = 0;
        $HoststatusFields = new HoststatusFields(new DbBackend());
        $HoststatusFields->currentState();
        $ServicestatusFields = new ServicestatusFields(new DbBackend());
        $ServicestatusFields->currentState();
        $hoststatus = $HoststatusTable->byUuid($host['uuid'], $HoststatusFields);
        $serviceUuids = Hash::extract($host['services'], '{n}.uuid');
        $servicestatus = $ServicestatusTable->byUuid($serviceUuids, $ServicestatusFields);

        $HostView = new \itnovum\openITCOCKPIT\Core\Views\Host($host);

        if ((empty($hoststatus) && empty($servicestatus)) || $host['disabled']) {
            return [
                'BitMaskHostState'    => $bitMaskHostState,
                'BitMaskServiceState' => $bitMaskServiceState,
                'Host'                => $HostView->toArray(),
            ];
        }
        if (isset($hoststatus['Hoststatus']['current_state'])) {
            $bitMaskHostState = 1 << $hoststatus['Hoststatus']['current_state'];
        }

        foreach ($servicestatus as $statusDetails) {
            $bitMaskServiceState |= 1 << $statusDetails['Servicestatus']['current_state'];
        }
        return [
            'BitMaskHostState'    => $bitMaskHostState,
            'BitMaskServiceState' => $bitMaskServiceState,
            'Host'                => $HostView->toArray(),
        ];
    }

    /**
     * @param HoststatusTableInterface $HoststatusTable
     * @param ServicestatusTableInterface $ServicestatusTable
     * @param array $service
     * @return array
     */
    public function getServiceInformationForSummaryIcon(HoststatusTableInterface $HoststatusTable, ServicestatusTableInterface $ServicestatusTable, array $service) {
        $bitMaskHostState = 0;
        $bitMaskServiceState = 0;
        $HoststatusFields = new HoststatusFields(new DbBackend());
        $HoststatusFields->currentState();
        $ServicestatusFields = new ServicestatusFields(new DbBackend());
        $ServicestatusFields->currentState();
        $hoststatus = $HoststatusTable->byUuid($service['host']['uuid'], $HoststatusFields);
        $servicestatus = $ServicestatusTable->byUuid($service['uuid'], $ServicestatusFields);

        $HostView = new \itnovum\openITCOCKPIT\Core\Views\Host($service);
        $ServiceView = new \itnovum\openITCOCKPIT\Core\Views\Service($service);


        if ((empty($hoststatus) && empty($servicestatus)) || $service['disabled']) {
            return [
                'BitMaskHostState'    => $bitMaskHostState,
                'BitMaskServiceState' => $bitMaskServiceState,
                'Host'                => $HostView->toArray(),
                'Service'             => $ServiceView->toArray(),
            ];
        }
        if (isset($hoststatus['Hoststatus']['current_state'])) {
            $bitMaskHostState = 1 << $hoststatus['Hoststatus']['current_state'];
        }

        if (isset($servicestatus['Servicestatus']['current_state'])) {
            $bitMaskServiceState = 1 << $servicestatus['Servicestatus']['current_state'];
        }

        return [
            'BitMaskHostState'    => $bitMaskHostState,
            'BitMaskServiceState' => $bitMaskServiceState,
            'Host'                => $HostView->toArray(),
            'Service'             => $ServiceView->toArray(),
        ];
    }

    /**
     * @param HoststatusTableInterface $HoststatusTable
     * @param ServicestatusTableInterface $ServicestatusTable
     * @param array $hostgroup
     * @return array
     */
    public function getHostgroupInformationForSummaryIcon(HoststatusTableInterface $HoststatusTable, ServicestatusTableInterface $ServicestatusTable, array $hostgroup) {
        $hostgroupLight = [
            'id'          => (int)$hostgroup['id'],
            'name'        => $hostgroup['container']['name'],
            'description' => $hostgroup['description']
        ];
        $bitMaskHostState = 0;
        $bitMaskServiceState = 0;
        $HoststatusFields = new HoststatusFields(new DbBackend());
        $HoststatusFields->currentState();
        $ServicestatusFields = new ServicestatusFields(new DbBackend());
        $ServicestatusFields->currentState();
        $hostUuids = Hash::extract($hostgroup['hosts'], '{n}.uuid');
        $serviceUuids = Hash::extract($hostgroup['hosts'], '{n}.Service.{n}.uuid');

        $hoststatus = $HoststatusTable->byUuid($hostUuids, $HoststatusFields);
        $servicestatus = $ServicestatusTable->byUuid($serviceUuids, $ServicestatusFields);

        if (empty($hoststatus) && empty($servicestatus)) {
            return [
                'BitMaskHostState'    => $bitMaskHostState,
                'BitMaskServiceState' => $bitMaskServiceState,
                'Hostgroup'           => $hostgroupLight
            ];
        }
        foreach ($hoststatus as $statusDetails) {
            $bitMaskHostState |= 1 << $statusDetails['Hoststatus']['current_state'];
        }
        foreach ($servicestatus as $statusDetails) {
            $bitMaskServiceState |= 1 << $statusDetails['Servicestatus']['current_state'];
        }
        return [
            'BitMaskHostState'    => $bitMaskHostState,
            'BitMaskServiceState' => $bitMaskServiceState,
            'Hostgroup'           => $hostgroupLight
        ];
    }

    /**
     * @param HoststatusTableInterface $HoststatusTable
     * @param ServicestatusTableInterface $ServicestatusTable
     * @param array $servicegroup
     * @return array
     */
    public function getServicegroupInformationForSummaryIcon(HoststatusTableInterface $HoststatusTable, ServicestatusTableInterface $ServicestatusTable, array $servicegroup) {
        $servicegroupLight = [
            'id'          => (int)$servicegroup['id'],
            'name'        => $servicegroup['container']['name'],
            'description' => $servicegroup['description']
        ];
        $bitMaskHostState = 0;
        $bitMaskServiceState = 0;
        $HoststatusFields = new HoststatusFields(new DbBackend());
        $HoststatusFields->currentState();
        $ServicestatusFields = new ServicestatusFields(new DbBackend());
        $ServicestatusFields->currentState();
        $hostUuids = Hash::extract($servicegroup['services'], '{n}.host.uuid');
        $serviceUuids = Hash::extract($servicegroup['services'], '{n}.uuid');
        $hoststatus = $HoststatusTable->byUuid($hostUuids, $HoststatusFields);
        $servicestatus = $ServicestatusTable->byUuid($serviceUuids, $ServicestatusFields);

        if (empty($hoststatus) && empty($servicestatus)) {
            return [
                'BitMaskHostState'    => $bitMaskHostState,
                'BitMaskServiceState' => $bitMaskServiceState,
                'Servicegroup'        => $servicegroupLight
            ];
        }
        foreach ($hoststatus as $statusDetails) {
            $bitMaskHostState |= 1 << $statusDetails['Hoststatus']['current_state'];
        }
        foreach ($servicestatus as $statusDetails) {
            $bitMaskServiceState |= 1 << $statusDetails['Servicestatus']['current_state'];
        }
        return [
            'BitMaskHostState'    => $bitMaskHostState,
            'BitMaskServiceState' => $bitMaskServiceState,
            'Servicegroup'        => $servicegroupLight
        ];
    }

    /**
     * @param HoststatusTableInterface $HoststatusTable
     * @param ServicestatusTableInterface $ServicestatusTable
     * @param array $map
     * @param array $hosts
     * @param array $services
     * @return array
     */
    public function getMapInformationForSummaryIcon(HoststatusTableInterface $HoststatusTable, ServicestatusTableInterface $ServicestatusTable, array $map, array $hosts, array $services) {
        $bitMaskHostState = 0;
        $bitMaskServiceState = 0;

        if (empty($hosts) && empty($services)) {
            return [
                'BitMaskHostState'    => $bitMaskHostState,
                'BitMaskServiceState' => $bitMaskServiceState,
                'Map'                 => $map
            ];
        }
        $HoststatusFields = new HoststatusFields(new DbBackend());
        $HoststatusFields->currentState();
        $ServicestatusFields = new ServicestatusFields(new DbBackend());
        $ServicestatusFields->currentState();
        $hostsUuids = Hash::extract($hosts, '{n}.uuid');
        $servicesUuids = Hash::extract($services, '{n}.Service.uuid');
        $hoststatus = $HoststatusTable->byUuid($hostsUuids, $HoststatusFields);
        $servicestatus = $ServicestatusTable->byUuid($servicesUuids, $ServicestatusFields);

        if (empty($hoststatus) && empty($servicestatus)) {
            return [
                'BitMaskHostState'    => $bitMaskHostState,
                'BitMaskServiceState' => $bitMaskServiceState,
                'Map'                 => $map
            ];
        }
        foreach ($hoststatus as $statusDetails) {
            $bitMaskHostState |= 1 << $statusDetails['Hoststatus']['current_state'];
        }
        foreach ($servicestatus as $statusDetails) {
            $bitMaskServiceState |= 1 << $statusDetails['Servicestatus']['current_state'];
        }
        return [
            'BitMaskHostState'    => $bitMaskHostState,
            'BitMaskServiceState' => $bitMaskServiceState,
            'Map'                 => $map
        ];
    }
}
