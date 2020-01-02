<?php
declare(strict_types=1);

namespace MapModule\Model\Table;

use App\Lib\Traits\Cake2ResultTableTrait;
use App\Lib\Traits\CustomValidationTrait;
use App\Lib\Traits\PaginationAndScrollIndexTrait;
use App\Model\Entity\Host;
use App\Model\Entity\Service;
use App\Model\Table\ContainersTable;
use App\Model\Table\ServicesTable;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Association\HasMany;
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Utility\Hash;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\Core\DbBackend;
use itnovum\openITCOCKPIT\Core\Hoststatus;
use itnovum\openITCOCKPIT\Core\HoststatusFields;
use itnovum\openITCOCKPIT\Core\Servicestatus;
use itnovum\openITCOCKPIT\Core\ServicestatusConditions;
use itnovum\openITCOCKPIT\Core\ServicestatusFields;
use itnovum\openITCOCKPIT\Core\Views\BBCodeParser;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\MapFilter;
use MapModule\Model\Entity\Map;
use Statusengine\PerfdataParser;
use Statusengine2Module\Model\Table\HoststatusTable;
use Statusengine2Module\Model\Table\ServicestatusTable;

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
     * @param ServicesTable $Service
     * @param HoststatusTable $Hoststatus
     * @param ServicestatusTable $Servicestatus
     * @param array $host
     * @return array
     */
    public function getHostInformation(ServicesTable $Service, HoststatusTable $Hoststatus, ServicestatusTable $Servicestatus, $host) {
        $HoststatusFields = new HoststatusFields(new DbBackend());
        $HoststatusFields->currentState()->scheduledDowntimeDepth()->problemHasBeenAcknowledged();
        $hoststatus = $Hoststatus->byUuid($host['uuid'], $HoststatusFields);
        $HostView = new \itnovum\openITCOCKPIT\Core\Views\Host($host);

        if (empty($hoststatus) || $host['disabled']) {
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
        $services = $Service->find('list', [
            'recursive'  => -1,
            'fields'     => [
                'Services.uuid'
            ],
            'conditions' => [
                'Services.host_id'  => $host['id'],
                'Services.disabled' => 0
            ]
        ]);

        $ServicestatusFieds = new ServicestatusFields(new DbBackend());
        $ServicestatusFieds->currentState()->scheduledDowntimeDepth()->problemHasBeenAcknowledged();
        $ServicestatusConditions = new ServicestatusConditions(new DbBackend());
        $ServicestatusConditions->servicesWarningCriticalAndUnknown();
        $servicestatus = $Servicestatus->byUuid($services, $ServicestatusFieds, $ServicestatusConditions);

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
     * @param ServicestatusTable $Servicestatus
     * @param array $service
     * @param bool $includeServiceOutput
     * @return array
     */
    public function getServiceInformation(ServicestatusTable $Servicestatus, $service, $includeServiceOutput = false) {
        $ServicestatusFields = new ServicestatusFields(new DbBackend());
        $ServicestatusFields->currentState()->scheduledDowntimeDepth()->problemHasBeenAcknowledged()->perfdata()->isFlapping();
        if ($includeServiceOutput === true) {
            $ServicestatusFields->output()->longOutput();
        }
        $servicestatus = $Servicestatus->byUuid($service['Service']['uuid'], $ServicestatusFields);
        $HostView = new \itnovum\openITCOCKPIT\Core\Views\Host($service);
        $ServiceView = new \itnovum\openITCOCKPIT\Core\Views\Service($service);
        if (empty($servicestatus) || $service['Service']['disabled']) {
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
}
